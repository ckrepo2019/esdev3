<?php

namespace App\Http\Controllers\RegistrarControllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use TCPDF;
use File;
use App\GenerateGrade;
use App\AttendanceReport;
use \Carbon\Carbon;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_Gradelevel;
use App\Models\Principal\SPP_Subject;
use App\Models\Principal\SPP_Attendance;
use App\Models\Principal\Section;
use App\Models\Forms\SF4;
use App\Models\Grades\GradesData;
use Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class ReportsController extends  Controller
{
    public function index(Request $request)
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

        if(Session::get('currentPortal') == 1){

            $extends = "teacher.layouts.app";
            
        }elseif(Session::get('currentPortal') == 2){

            $extends = "principalsportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 3  ||  Session::get('currentPortal') == 8){

            $extends = "registrar.layouts.app";

        }elseif(Session::get('currentPortal') == 4  ||  Session::get('currentPortal') == 15){

            $extends = "finance.layouts.app";

        }elseif(Session::get('currentPortal') == 6){

            $extends = "adminPortal.layouts.app2";

        }elseif(Session::get('currentPortal') == 10 || $refid == 26){

            $extends = "hr.layouts.app";

        }elseif(Session::get('currentPortal') == 12){

            $extends = "adminITPortal.layouts.app";

        }elseif(Session::get('currentPortal') == 14){

            $extends = "deanportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 16){

            $extends = "chairpersonportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 18){

            $extends = "ctportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 29  || $refid == 29){
    
            $extends = "idmanagement.layouts.app2";

        }else{

            $extends = "general.defaultportal.layouts.app";

        }
        if($request->has('esc'))
        {
            $esc = $request->get('esc');
        }else{
            $esc = 0;
        }

        if(!$request->has('action'))
        {
            if($request->get('sf') == '0')
            {
                return view('registrar.forms.masterlist.index')
                    ->with('acadprogid', $request->get('acadprogid'))
                    ->with('extends', $extends);
            }
            elseif($request->get('sf') == 'ssf')
            {
                if(Session::get('currentPortal') == 1)
                {
                    return view('teacher.forms.studentstatusform.index');
                }else{
                    return view('registrar.forms.studentstatusform.index')
                        ->with('acadprogid', $request->get('acadprogid'));
                }
            }
            elseif($request->get('sf') == '1')
            {
                return view('registrar.forms.form1.index')
                    ->with('acadprogid', $request->get('acadprogid'));
            }
            elseif($request->get('sf') == '2')
            {
                return view('registrar.forms.form2.index')
                    ->with('acadprogid', $request->get('acadprogid'));
            }
            elseif($request->get('sf') == '3')
            {
                return view('registrar.forms.form3.index')
                    ->with('acadprogid', $request->get('acadprogid'));
            }
            elseif($request->get('sf') == '4') //byacadprrog
            {
                return view('registrar.forms.form4.newv.index');
            }
            elseif($request->get('sf') == '5')
            {
                return view('registrar.forms.form5.index')
                    ->with('acadprogid', $request->get('acadprogid'))
                    ->with('sf', $request->get('sf'));
            }
            elseif($request->get('sf') == '5a')
            {
                return view('registrar.forms.form5a.index')
                    ->with('acadprogid', $request->get('acadprogid'))
                    ->with('sf', $request->get('sf'));
            }
            elseif($request->get('sf') == '5b')
            {
                return view('registrar.forms.form5b.index')
                    ->with('acadprogid', $request->get('acadprogid'))
                    ->with('sf', $request->get('sf'));
            }
            elseif($request->get('sf') == '6')
            {
                return view('registrar.forms.form6.index')
                    ->with('acadprogid', $request->get('acadprogid'))
                    ->with('sf', $request->get('sf'));
            }
            elseif($request->get('sf') == '9')
            {
                return view('registrar.forms.form9.index')
                    ->with('acadprogid', $request->get('acadprogid'))
                    ->with('sf', $request->get('sf'));
            }
        }else{
            if($request->get('action') == 'getlevels')
            {
                $levels = DB::table('sectiondetail')
                    ->select('gradelevel.id','gradelevel.levelname','sections.id as sectionid','sections.sectionname','gradelevel.acadprogid')
                    ->join('sections','sectiondetail.sectionid','=','sections.id')
                    ->join('gradelevel','sections.levelid','=','gradelevel.id')
                    ->where('sectiondetail.syid', $request->get('syid'))
                    ->where('sectiondetail.teacherid', DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->id)
                    ->where('sectiondetail.deleted','0')
                    ->get();

                return collect($levels)->unique('id')->values();
            }
            elseif($request->get('action') == 'getsections')
            {
                $acadprogid = $request->get('acadprogid');    
                if(!$request->has('acadprogid'))
                {
                    $acadprogid = DB::table('gradelevel')
                    ->where('id', $request->get('levelid'))->first()->acadprogid;
                }
                if($acadprogid == 6)
                {   
                    $sections = DB::table('college_sections')
                        ->select('college_sections.*','college_sections.yearID as levelid','college_sections.sectionDesc as sectionname','college_courses.collegeid')
                        ->join('college_courses','college_sections.courseID','=','college_courses.id')
                        ->where('college_sections.deleted','0')
                        ->where('college_sections.syID', $request->get('syid'))
                        ->where('college_sections.semesterID', $request->get('semid'))
                        ->get();
                    if($request->get('levelid') > 0)
                    {
                        $sections = collect($sections)->where('levelid', $request->get('levelid'))->values()->all();
                    }
                    if($request->get('collegeid') > 0)
                    {
                        $sections = collect($sections)->where('collegeid', $request->get('collegeid'))->values()->all();
                    }
                    if($request->get('courseid') > 0)
                    {
                        $sections = collect($sections)->where('courseID', $request->get('courseid'))->values()->all();
                    }

                }else{
                    $sections = DB::table('sectiondetail')
                        ->select('sections.*','teacher.id as teacherid','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix','gradelevel.acadprogid','semid')
                        ->join('sections','sectiondetail.sectionid','=','sections.id')
                        ->join('gradelevel','sections.levelid','=','gradelevel.id')
                        ->leftJoin('teacher','sectiondetail.teacherid','=','teacher.id')
                        ->where('sectiondetail.syid', $request->get('syid'))
                        ->where('gradelevel.acadprogid',$acadprogid)
                        ->where('gradelevel.deleted',0)
                        ->where('sectiondetail.deleted',0)
                        ->where('sections.deleted',0)
                        ->orderBy('sectionname','asc')
                        ->get();
                }
                
                if($acadprogid == 5)
                {
                    // $sections = collect($sections)->where('semid', $request->get('semid'))->values();
                    if(Session::get('currentPortal') == 1)
                    {
                        $sections = collect($sections)->where('teacherid', DB::table('teacher')->where('userid', auth()->user()->id)->first()->id)->values();
                    }
                }
                if(count($sections)>0)
                {
                    foreach($sections as $section)
                    {
                        if($request->get('acadprogid') == 6)
                        {
                            $students = DB::table('college_enrolledstud')  
                                ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','college_enrolledstud.studstatus','college_enrolledstud.courseid','college_courses.collegeid','college_enrolledstud.yearLevel as levelid')
                                ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                                ->leftJoin('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                                ->where('college_enrolledstud.syid', $request->get('syid'))
                                ->where('college_enrolledstud.semid', $request->get('semid'))
                                ->where('college_enrolledstud.sectionid', $section->id)
                                ->where('college_enrolledstud.deleted','0')
                                ->where('college_enrolledstud.studstatus','!=','0')
                                ->where('college_enrolledstud.studstatus','<=','5')
                                ->distinct('studinfo.id')
                                ->get();
                                if($request->get('levelid') > 0)
                                {
                                    $students = collect($students)->where('levelid', $request->get('levelid'))->values()->all();
                                }
                                if($request->get('collegeid') > 0)
                                {
                                    $students = collect($students)->where('collegeid', $request->get('collegeid'))->values()->all();
                                }
                                if($request->get('courseid') > 0)
                                {
                                    $students = collect($students)->where('courseID', $request->get('courseid'))->values()->all();
                                }
                            
                        }
                        elseif($request->get('acadprogid') == 5)
                        {
                            $students = DB::table('sh_enrolledstud')  
                                ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','sh_enrolledstud.studstatus','sh_strand.strandname','sh_enrolledstud.strandid')
                                ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                                ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                                ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                                ->where('sh_enrolledstud.syid', $request->get('syid'))
                                ->where('sh_enrolledstud.semid', $request->get('semid'))
                                ->where('sh_enrolledstud.sectionid', $section->id)
                                ->where('sh_enrolledstud.deleted','0')
                                ->where('sh_enrolledstud.studstatus','!=','0')
                                ->where('sh_enrolledstud.studstatus','<=','5')
                                ->distinct('studinfo.id')
                                ->get();

                        }else{
                            $students = DB::table('enrolledstud')  
                                ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','enrolledstud.studstatus')
                                ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                                ->where('enrolledstud.levelid', $request->get('levelid'))
                                ->where('enrolledstud.syid', $request->get('syid'))
                                ->where('enrolledstud.sectionid', $section->id)
                                ->where('enrolledstud.deleted','0')
                                ->where('enrolledstud.studstatus','!=','0')
                                ->where('enrolledstud.studstatus','<=','5')
                                ->distinct('studinfo.id')
                                ->get();
                        }
                        $section->students = collect($students)->sortBy('firstname')->sortBy('lastname');
                        
                    }
                }
                if($request->get('sf') == 1)
                {
                    return view('registrar.forms.form1.sections')
                        ->with('sections', collect($sections)->where('levelid', $request->get('levelid'))->values())
                        ->with('acadprogid', $request->get('acadprogid'));
                }
                if($request->get('sf') == 2 || $request->get('sf') == 0)
                {                    
                    if($request->get('levelid') > 0)
                    {
                        $sections = collect($sections)->where('levelid', $request->get('levelid'))->values()->all();
                        return $sections;
                    }else{
                        return $sections;
                    }
                }
                if($request->get('sf') == 3)
                {
                    
                    return collect($sections)->where('levelid', $request->get('levelid'))->values();
                }
                if($request->get('sf') == 5)
                {
                    
                    return collect($sections)->where('levelid', $request->get('levelid'))->values();
                }
                if($request->get('sf') == 9)
                {
                    
                    return collect($sections)->where('levelid', $request->get('levelid'))->values();
                }
            }
            elseif($request->get('action') == 'getstrands')
            {   
                
                $students = DB::table('sh_enrolledstud')  
                    ->select('sh_strand.id','sh_strand.strandcode')
                    ->join('sh_strand','strandid','=','sh_strand.id')
                    ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                    ->where('sh_enrolledstud.syid', $request->get('syid'))
                    ->where('sh_enrolledstud.semid', $request->get('semid'))
                    ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                    ->where('sh_enrolledstud.deleted','0')
                    ->distinct()
                    ->get();
                    
                    return $students;
                

            }
            elseif($request->get('action') == 'getcourses')
            {
                $courses = DB::table('college_courses')
                    ->where('collegeid', $request->get('collegeid'))
                    ->where('deleted','0')
                    ->get();

                return $courses;
            }
            elseif($request->get('action') == 'getsetup')
            {                

                $setup = DB::table('sf2_setup')
                    ->where('deleted','0')
                    ->where('syid', $request->get('syid'))
                    ->where('sectionid', $request->get('sectionid'))
                    ->where('year', $request->get('yearid'))
                    ->get();

                if(count($setup)>0)
                {
                    foreach($setup as $eachsetup)
                    {
                        if($eachsetup->month < 10)
                        {
                            $eachsetup->month = '0'.$eachsetup->month;
                        }
                        $eachsetup->monthname = date('F', strtotime('2020-'.$eachsetup->month));
                    }
                }
                if($request->get('levelid') == 14 || $request->get('levelid') == 15)
                {
                    $setup = collect($setup)->where('strandid', $request->get('strandid'))->values();
                }
                return $setup;
            }
            elseif($request->get('action') == 'getstudents')
            {         
                $acadprogid = $request->get('acadprogid');    
                if(!$request->has('acadprogid'))
                {
                    $acadprogid = DB::table('gradelevel')
                    ->where('id', $request->get('levelid'))->first()->acadprogid;
                }
                if($request->has('semid'))
                {
                    $semid = $request->get('semid');
                }else{
                    $semid = DB::table('semester')
                    ->where('isactive', '1')->first()->id;
                }
                $students = array();
                
                if($acadprogid == 6)
                {
                    $students = DB::table('college_enrolledstud')
                        ->select('studinfo.*',DB::raw('LOWER(`gender`) as gender'),'college_enrolledstud.yearLevel as levelid','college_enrolledstud.sectionid','college_courses.id as strandid','college_courses.id as courseid','college_courses.courseDesc as strandname','college_courses.courseabrv as strandcode','grantee.description as granteedesc','college_enrolledstud.studstatus','college_enrolledstud.semid','college_courses.collegeid', DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"))
                        ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                        ->leftJoin('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                        ->leftJoin('grantee','studinfo.grantee','=','grantee.id')
                        ->where('college_enrolledstud.syid', $request->get('syid'))
                        ->where('college_enrolledstud.studstatus','!=','0')
                        ->where('college_enrolledstud.studstatus','<=','5')
                        ->where('college_enrolledstud.deleted','0')
                        ->where('studinfo.deleted','0')
                        ->distinct('studinfo.id')
                        ->get();
                    if($request->get('levelid') > 0)
                    {
                        $students = collect($students)->where('levelid', $request->get('levelid'))->values()->all();
                    }
                    if($request->get('collegeid') > 0)
                    {
                        $students = collect($students)->where('collegeid', $request->get('collegeid'))->values()->all();
                    }
                    if($request->get('courseid') > 0)
                    {
                        $students = collect($students)->where('courseid', $request->get('courseid'))->values()->all();
                    }
                    if($request->get('sectionid') > 0)
                    {
                        $students = collect($students)->where('sectionid', $request->get('sectionid'))->values()->all();
                    }
                    if($request->get('sf') != 'ssf')
                    {
                        $students = collect($students)->where('semid', $semid);
                    }
                    $students = collect($students)->sortBy('studentname')->values();
                }
                elseif($acadprogid == 5)
                {
                    $students = DB::table('sh_enrolledstud')
                        ->select('studinfo.*',DB::raw('LOWER(`gender`) as gender'),'sh_enrolledstud.levelid','sh_enrolledstud.sectionid','sh_strand.id as strandid','sh_strand.strandname','sh_strand.strandcode','grantee.description as granteedesc','sh_enrolledstud.studstatus','sh_enrolledstud.semid', DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"))
                        ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                        ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                        ->leftJoin('grantee','studinfo.grantee','=','grantee.id')
                        ->where('sh_enrolledstud.syid', $request->get('syid'))
                        ->where('sh_enrolledstud.studstatus','!=','0')
                        ->where('sh_enrolledstud.studstatus','<=','5')
                        ->where('sh_enrolledstud.deleted','0')
                        ->where('studinfo.deleted','0')
                        ->distinct('studinfo.id')
                        ->get();

                        if($request->get('levelid') > 0)
                        {
                            $students = collect($students)->where('levelid', $request->get('levelid'))->values()->all();
                        }
                        if($request->get('sectionid') > 0)
                        {
                            $students = collect($students)->where('sectionid', $request->get('sectionid'))->values()->all();
                        }

                        if($request->get('sf') != 'ssf')
                        {
                            $students = collect($students)->where('semid', $semid);
                        }
                        $students = collect($students)->sortBy('studentname')->all();
                        
                }else{
                    $students = DB::table('enrolledstud')
                        ->select('studinfo.*',DB::raw('LOWER(`gender`) as gender'),'enrolledstud.levelid','enrolledstud.sectionid','grantee.description as granteedesc','enrolledstud.studstatus', DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"))
                        ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                        ->leftJoin('grantee','studinfo.grantee','=','grantee.id')
                        ->where('enrolledstud.syid', $request->get('syid'))
                        ->where('enrolledstud.studstatus','!=','0')
                        ->where('enrolledstud.studstatus','<=','5')
                        ->where('enrolledstud.deleted','0')
                        ->where('studinfo.deleted','0')
                        ->distinct('studinfo.id')
                        ->get();
                        if($request->get('levelid') > 0)
                        {
                            $students = collect($students)->where('levelid', $request->get('levelid'))->values()->all();
                        }
                        if($request->get('sectionid') > 0)
                        {
                            $students = collect($students)->where('sectionid', $request->get('sectionid'))->values()->all();
                        }
                        $students = collect($students)->sortBy('studentname')->all();
                }
                if($request->has('sf'))
                {
                    if($request->get('sf') != 'ssf')
                    {
                        $students = collect($students)->whereIn('studstatus',[1,2,4]);
                    }else{
                        $students = collect($students)->unique('id')->all();
                    }
                }else{
                    $students = collect($students)->unique('id')->all();
                }
                
                if(count($students)>0)
                {
                    foreach($students as $student)
                    {
                        $student->sortthis = $student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename;
                    }
                }
                $students = collect($students)->sortBy('sortthis')->values();
                if($request->has('sf'))
                {
                    if($request->get('sf') == '0')
                    {
                        if($request->has('esc'))
                        {
                            $esc = $request->get('esc');
                            if($request->get('esc') > 0)
                            {
                                $students = collect($students)->where('granteedesc','ESC')->values();
                            }
                        }else{
                            $esc = 0;
                        }
                        
                        $notes = DB::table('reports_notes')
                            ->where('reportname','certificateesc')
                            ->where('deleted','0')
                            ->where('createdby',auth()->user()->id)
                            ->get();
                            
                        return view('registrar.forms.masterlist.students')
                            ->with('esc',$esc)
                            ->with('notes',$notes)
                            ->with('sectionid',$request->get('sectionid'))
                            ->with('levelid',$request->get('levelid'))
                            ->with('students',$students)
                            ->with('collegeid',$request->get('collegeid'))
                            ->with('courseid',$request->get('courseid'))
                            ->with('syid',$request->get('syid'))
                            ->with('semid',$request->get('semid'))
                            ->with('acadprogid',$request->get('acadprogid'));
                    }
                    if($request->get('sf') == 'ssf')
                    {
                        $acadprogid = $request->get('acadprogid');    
                        if(!$request->has('acadprogid'))
                        {
                            $acadprogid = DB::table('gradelevel')
                            ->where('id', $request->get('levelid'))->first()->acadprogid;
                        }
                        if($request->has('esc'))
                        {
                            $esc = $request->get('esc');
                            if($request->get('esc') > 0)
                            {
                                $students = collect($students)->where('granteedesc','ESC')->values();
                            }
                        }else{
                            $esc = 0;
                        }
                        foreach($students as $student)
                        {
                            if($acadprogid == 5)
                            {
                                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($student->levelid,$student->id,$request->get('syid'),$student->strandid,null,$student->sectionid);
                            }else{
                                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($student->levelid,$student->id,$request->get('syid'),null,null,$student->sectionid);
                            }
                            
                            $generalaverage = array();
                            foreach($studgrades as $item){
                                if($item->id == 'G1'){
                                    array_push($generalaverage,$item);
                                }
                            }
                            
                            if(count($generalaverage) == 0)
                            {
                                $student->q1 = null;
                                $student->q2 = null;
                                $student->q3 = null;
                                $student->q4 = null;
                                $student->finalrating = null;
                                $student->status = null;
                            }else{
                                if($acadprogid == 5)
                                {
                                    $student->q1 = number_format(collect($generalaverage)->where('semid',1)->first()->q1comp,2);
                                    $student->q2 = number_format(collect($generalaverage)->where('semid',1)->first()->q2comp,2);
                                    $student->fcomp1 = round(collect($generalaverage)->where('semid',1)->first()->fcomp);
                                    $student->q3 = number_format(collect($generalaverage)->where('semid',2)->first()->q3comp,2);
                                    $student->q4 = number_format(collect($generalaverage)->where('semid',2)->first()->q4comp,2);
                                    $student->fcomp2 = round(collect($generalaverage)->where('semid',2)->first()->fcomp);
                                    $student->finalrating = round(collect($generalaverage)->avg('fcomp'));
                                    $student->status = collect($generalaverage)->avg('fcomp') < 75 ? 'FAILED' : 'PASSED';
                                }else{
                                    $student->q1 = $generalaverage[0]->q1;
                                    $student->q2 = $generalaverage[0]->q2;
                                    $student->q3 = $generalaverage[0]->q3;
                                    $student->q4 = $generalaverage[0]->q4;
                                    $student->finalrating = $generalaverage[0]->finalrating;
                                    $student->status = $generalaverage[0]->finalrating < 75 ? 'FAILED' : 'PASSED';
                                }
                            }
                        
                        }
                        return view('registrar.forms.studentstatusform.students')
                            ->with('esc',$esc)
                            ->with('sectionid',$request->get('sectionid'))
                            ->with('levelid',$request->get('levelid'))
                            ->with('students',$students)
                            ->with('syid',$request->get('syid'))
                            ->with('semid',$request->get('semid'))
                            ->with('acadprogid',$acadprogid);
                    }
                }else{
                    return view('registrar.forms.form9.table_students')
                        ->with('students',$students)
                        ->with('syid',$request->get('syid'))
                        ->with('semid',$request->get('semid'))
                        ->with('levelid',$request->get('levelid'))
                        ->with('acadprogid',$request->get('acadprogid'));
                }
            }
            elseif($request->get('action') == 'getsf4results')
            {                
                $acadprogids = [$request->get('acadprogid')];
                if($request->get('acadprogid') == '3')
                {
                    $acadprogids = [$request->get('acadprogid'),2];
                }
                $gradelevels = DB::table('gradelevel')
                        ->select('gradelevel.*')
                        ->where('gradelevel.deleted','0')
                        ->whereIn('gradelevel.acadprogid',$acadprogids)
                        ->orderBy('gradelevel.sortid', 'asc')
                        ->get();


                if($request->get('acadprogid') == '5')
                {
                    $students = DB::table('sh_enrolledstud')
                        ->select('sh_enrolledstud.*', DB::raw('LOWER(`gender`) as gender'))
                        ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                        ->whereIn('sh_enrolledstud.levelid', collect($gradelevels)->pluck('id'))
                        ->where('sh_enrolledstud.syid',$request->get('syid'))
                        ->where('sh_enrolledstud.deleted','0')
                        ->where('sh_enrolledstud.studstatus','!=','0')
                        ->where('sh_enrolledstud.studstatus','<=','5')
                        ->get();
                }else{
                    $students = DB::table('enrolledstud')
                        ->select('enrolledstud.*', DB::raw('LOWER(`gender`) as gender'))
                        ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                        ->whereIn('enrolledstud.levelid', collect($gradelevels)->pluck('id'))
                        ->where('enrolledstud.syid',$request->get('syid'))
                        ->where('enrolledstud.deleted','0')
                        ->where('enrolledstud.studstatus','!=','0')
                        ->where('enrolledstud.studstatus','<=','5')
                        ->get();
                }
                $studattendance = DB::table('studattendance')
                    ->whereIn('studid', collect($students)->pluck('studid'))
                    ->whereMonth('tdate',$request->get('selectmonth'))
                    ->whereYear('tdate',$request->get('selectyear'))
                    ->where('deleted','0')
                    ->get();
                    
                $setups = DB::table('sf2_setup')
                    ->select('sf2_setup.*','sh_strand.strandname','sh_strand.strandcode')
                    ->leftJoin('sh_strand','sf2_setup.strandid','=','sh_strand.id')
                    ->where('sf2_setup.deleted','0')
                    ->where('sf2_setup.syid', $request->get('syid'))
                    ->where('sf2_setup.month',$request->get('selectmonth'))
                    ->where('sf2_setup.year',$request->get('selectyear'))
                    ->get();
                
                if($request->get('selectmonth') == '01')
                {
                    $lastmonth = '12';
                    $lastyear = (int)$request->get('selectyear') - 1;
                }else{
                    $lastmonth = $request->get('selectmonth') - 1;
                    $lastyear = (int)$request->get('selectyear') - 1;
                }
                if(count($gradelevels)>0)
                {
                    foreach($gradelevels as $gradelevel)
                    {
                        $getsections = DB::table('sectiondetail')
                            ->select('sections.id','sections.sectionname','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix','teacher.id as teacherid')
                            ->where('sectiondetail.syid', $request->get('syid'))
                            ->join('sections', 'sectiondetail.sectionid','=','sections.id')
                            ->leftJoin('teacher', 'sectiondetail.teacherid','=','teacher.id')
                            ->where('sections.levelid', $gradelevel->id)
                            ->where('sections.deleted','0')
                            ->where('sectiondetail.deleted','0')
                            ->get();

                        $getstudents = collect($students)->whereIn('sectionid', collect($getsections)->pluck('id'))->values()->all();
                        
                        $gradelevel->countstudents = count($getstudents);
                        if(count($getsections)>0)
                        {
                            foreach($getsections as $getsection)
                            {
                                $sectionsetup = collect($setups)->where('sectionid', $getsection->id)->values()->all();
                                $data = array();
                                $dates = array();

                                if(count($sectionsetup)>0)
                                {
                                    $dates = DB::table('sf2_setupdates')
                                        ->where('setupid', $sectionsetup[0]->id)
                                        ->where('deleted','0')
                                        ->orderBy('dates','asc')
                                        ->get();
                                    
                                    if(count($dates)>0)
                                    {
                                        foreach($dates as $eachdate)
                                        {                                        
                                            $presentmale = 0;
                                            $presentfemale = 0;

                                            if(count($getstudents)>0)
                                            {
                                                foreach($getstudents as $eachstudent)
                                                {
                                                    $eachday = collect($studattendance)
                                                        ->where('studid', $eachstudent->studid)
                                                        ->where('tdate',$request->get('selectyear').'-'.$request->get('selectmonth').'-'.date('d',strtotime($eachdate->dates)))
                                                        ->where('deleted','0')
                                                        ->first();

                                                    if($eachday)
                                                    {
                                                        if($eachday->present == 1 || $eachday->tardy == 1 ||$eachday->cc == 1 )
                                                        {
                                                            if($eachstudent->gender == 'male')
                                                            {
                                                                $presentmale+=1;
                                                            }elseif($eachstudent->gender == 'female')
                                                            {
                                                                $presentfemale+=1;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            $eachdate->presentmale = $presentmale;
                                            $eachdate->presentfemale = $presentfemale;
                                        }                                        
                                    }                                        
                                }
                                
                                $getsection->countdates =  count($dates);
                                $getsection->presentmale =  collect($dates)->sum('presentmale');
                                $getsection->presentfemale =  collect($dates)->sum('presentfemale');

                                $getsection->registeredmale = collect($getstudents)->where('sectionid',$getsection->id)->whereIn('studstatus',[1,2,4])->where('gender','male')->count();
                                $getsection->registeredfemale = collect($getstudents)->where('sectionid',$getsection->id)->whereIn('studstatus',[1,2,4])->where('gender','female')->count();

                                
                                $getsection->nlpa_a_m = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',3)->where('gender','male')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
                                $getsection->nlpa_a_f = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',3)->where('gender','female')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
                                $getsection->nlpa_b_m = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',3)->where('gender','female')->whereBetween('studstatdate', [$request->get('selectyear').'-'.$request->get('selectmonth').'-01',$request->get('selectyear').'-'.$request->get('selectmonth').'-'.date('t')])->count();
                                $getsection->nlpa_b_f = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',3)->where('gender','female')->whereBetween('studstatdate', [$request->get('selectyear').'-'.$request->get('selectmonth').'-01',$request->get('selectyear').'-'.$request->get('selectmonth').'-'.date('t')])->count();
                                
                                $getsection->to_a_m = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',5)->where('gender','male')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
                                $getsection->to_a_f = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',5)->where('gender','female')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
                                $getsection->to_b_m = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',5)->where('gender','female')->whereBetween('studstatdate', [$request->get('selectyear').'-'.$request->get('selectmonth').'-01',$request->get('selectyear').'-'.$request->get('selectmonth').'-'.date('t')])->count();
                                $getsection->to_b_f = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',5)->where('gender','female')->whereBetween('studstatdate', [$request->get('selectyear').'-'.$request->get('selectmonth').'-01',$request->get('selectyear').'-'.$request->get('selectmonth').'-'.date('t')])->count();

                                $getsection->ti_a_m = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',4)->where('gender','male')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
                                $getsection->ti_a_f = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',4)->where('gender','female')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
                                $getsection->ti_b_m = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',4)->where('gender','female')->whereBetween('studstatdate', [$request->get('selectyear').'-'.$request->get('selectmonth').'-01',$request->get('selectyear').'-'.$request->get('selectmonth').'-'.date('t')])->count();
                                $getsection->ti_b_f = collect($getstudents)->where('sectionid',$getsection->id)->where('studstatus',4)->where('gender','female')->whereBetween('studstatdate', [$request->get('selectyear').'-'.$request->get('selectmonth').'-01',$request->get('selectyear').'-'.$request->get('selectmonth').'-'.date('t')])->count();

                                
                                
                            }
                        }                        
                        
                        $gradelevel->sections = $getsections;
                        $gradelevel->countsections = count($getsections);                                       
                    }
                }
                if(!$request->has('export'))
                {
                    return view('registrar.forms.form4.newv.results')
                        ->with('students',$students)
                        ->with('gradelevels',$gradelevels)
                        ->with('syid',$request->get('syid'))
                        ->with('semid',$request->get('semid'))
                        ->with('levelid',$request->get('levelid'))
                        ->with('acadprogid',$request->get('acadprogid'));
                }else{
                    $monthname = date('F', strtotime($request->get('selectyear').'-'.$request->get('selectmonth').'-01'));
                    $acadprogdesc = DB::table('academicprogram')
                        ->where('id', $request->get('acadprogid'))
                        ->first()->progname;
                    $sydesc = DB::table('sy')
                        ->where('id', $request->get('syid'))
                        ->first()->sydesc;
                    $syid =  $request->get('syid');
                        
                    $pdf = PDF::loadview('registrar/forms/form4/newv/pdf_form4',compact('gradelevels','acadprogdesc','sydesc','monthname','syid'));
            
                    $pdf->getDomPDF()->set_option("enable_php", true);
                    return $pdf->stream('School Form 4.pdf');
                }                
            }
            elseif($request->get('action') == 'getsf6results')
            {
                $gradelevels = DB::table('gradelevel')
                        ->select('gradelevel.*')
                        ->where('gradelevel.deleted','0')
                        ->where('gradelevel.acadprogid',$request->get('acadprogid'))
                        ->orderBy('gradelevel.sortid', 'asc')
                        ->get();
                        
                $strands = array();
                if($request->get('acadprogid') == '5')
                {
                    $alllevels = array();
                    $strands = DB::table('sh_strand')
                        ->select('sh_strand.*', 'sh_track.trackname')
                        ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                        ->where('sh_strand.active','1')
                        ->where('sh_strand.deleted','0')
                        ->get();
                        
                    foreach($gradelevels as $eachlevel)
                    {
                        if(count($strands)>0)
                        {
                            foreach($strands as $strand)
                            {
                                $studentseachgradelevel = DB::table('studinfo')
                                    ->select(
                                        'studinfo.id',
                                        'sh_enrolledstud.studid',
                                        'studinfo.lrn',
                                        'studinfo.lastname',
                                        'studinfo.firstname',
                                        'studinfo.middlename',
                                        'studinfo.suffix',
                                        'studinfo.gender',
                                        'gradelevel.acadprogid',
                                        'sh_enrolledstud.sectionid',
                                        'sh_enrolledstud.strandid',
                                        'sh_enrolledstud.levelid',
                                        'sh_enrolledstud.levelid',
                                        'sh_enrolledstud.semid',
                                        'sections.blockid',
                                        'studinfo.sectionid as ensectid',
                                        'studinfo.levelid as enlevelid',
                                        'gradelevel.acadprogid',
                                        'sh_enrolledstud.promotionstatus'
                                    )
                                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                                    ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                                    ->where('sh_enrolledstud.levelid', $eachlevel->id)
                                    ->where('sh_enrolledstud.syid',$request->get('syid'))
                                    ->where('sh_enrolledstud.strandid',$strand->id)
                                    ->where('studinfo.deleted','0')
                                    ->where('sh_enrolledstud.deleted','0')
                                    ->get();
                                    
                                if($request->has('semid'))
                                {
                                    $studentseachgradelevel = collect($studentseachgradelevel)->where('semid', $request->get('semid'))->values();
                                }

                                array_push($alllevels,(object)array(
                                    'id'            => $eachlevel->id,
                                    'levelname'            => $eachlevel->levelname,
                                    'acadprogid'            => $eachlevel->acadprogid,
                                    'deleted'            => $eachlevel->deleted,
                                    'sortid'            => $eachlevel->sortid,
                                    'strandid'            => $strand->id,
                                    'strandcode'            => $strand->strandcode,
                                    'strandname'            => $strand->strandname,
                                    'trackname'            => $strand->trackname,
                                    'students'            => $studentseachgradelevel
                                ));
                            }
                        }
                        
                    }
                    $gradelevels = $alllevels;
                }else{
                    foreach($gradelevels as $eachlevel)
                    {
                        $studentseachgradelevel = DB::table('studinfo')
                            ->select(
                                'studinfo.id',
                                'enrolledstud.studid',
                                'studinfo.lrn',
                                'studinfo.lastname',
                                'studinfo.firstname',
                                'studinfo.middlename',
                                'studinfo.suffix',
                                'studinfo.gender',
                                'gradelevel.acadprogid',
                                'enrolledstud.sectionid',
                                'enrolledstud.levelid',
                                'sections.blockid',
                                'studinfo.sectionid as ensectid',
                                'studinfo.levelid as enlevelid',
                                'gradelevel.acadprogid',
                                'enrolledstud.promotionstatus'
                            )
                            ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                            ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                            ->join('sections','enrolledstud.sectionid','=','sections.id')
                            ->where('enrolledstud.levelid', $eachlevel->id)
                            ->where('enrolledstud.syid',$request->get('syid'))
                            ->where('studinfo.deleted','0')
                            ->where('enrolledstud.deleted','0')
                            ->get();
                            
                        $eachlevel->students = $studentseachgradelevel;
                        
                    }
                }
                $getsf5status = DB::table('sf5')
                    ->where('deleted','0')
                    ->where('syid',$request->get('syid'))
                    ->get();
                if(count($gradelevels)>0)
                {
                    $getsf5status = collect($getsf5status)->whereIn('levelid', collect($gradelevels)->pluck('id'))->values();
                }
                
                // if(count($strands)>0)
                // {
                //     $getsf5status = collect($getsf5status)->whereIn('levelid', collect($strands)->pluck('id'))->values();
                // }
                foreach($gradelevels as $eachlevel)
                {
                    if(count($eachlevel->students)>0)
                    {
                        // return $eachlevel->students;
                        foreach($eachlevel->students as $eachstudent)
                        {
                            $eachstudent->gendersort = strtoupper($eachstudent->gender);
                            if(collect($eachstudent)->has('strandid'))
                            {
                                $strand = $eachstudent->strandid;
                            }else{
                                
                            $strand = 0;
                            }
                            $generalaverage = array();
                            
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'csl')
                            {
                                $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                        
                                if($eachstudent->levelid == 14 || $eachstudent->levelid == 15){
                                    if($grading_version->version == 'v2'){
                                        $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $eachstudent->levelid,$eachstudent->id,$request->get('syid'),$strand,null,$eachstudent->sectionid);
                                    }else{
                                        $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $eachstudent->levelid,$eachstudent->id,$request->get('syid'),$strand,null,$eachstudent->sectionid);
                                    }
                                    $temp_grades = array();
                                    $finalgrade = array();
                                    foreach($studgrades as $item){
                                        if($item->id == 'G1'){
                                            array_push($finalgrade,$item);
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
                                    $generalaverage =  $finalgrade;
                                }else{
                                    if($grading_version->version == 'v2'){
                                        $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $eachstudent->levelid,$eachstudent->id,$request->get('syid'),null,null,$eachstudent->sectionid);
                                    }else{
                                        $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $eachstudent->levelid,$eachstudent->id,$request->get('syid'),null,null,$eachstudent->sectionid);
                                    }
                                    
                                    
                                    $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel);
                                    $grades = $studgrades;
                                    $grades = collect($grades)->sortBy('sortid')->values();
                                    $finalgrade = collect($grades)->where('id','G1')->values();
                                    unset($grades[count($grades)-1]);
                                    $studgrades = collect($grades)->where('isVisible','1')->values();
                                    $generalaverage =  $finalgrade;
                                }
    
                            }else{
                                $schoolyear = DB::table('sy')->where('id',$request->get('syid'))->first();
                                Session::put('schoolYear', $schoolyear);
                                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $eachstudent->levelid,$eachstudent->id,$request->get('syid'),$strand,null,$eachstudent->sectionid);
                                
                
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
                                // return $studgrades;
                                if($request->has('semid'))
                                {
                                    $generalaverage = collect($generalaverage)->where('semid',$request->get('semid'))->values();
                                    $temp_grades = collect($temp_grades)->where('semid',$request->get('semid'))->values();
                                    // $studentseachgradelevel = collect($studentseachgradelevel)->where('semid', $request->get('semid'))->values();
                                }else{
                                    $generalaverage = collect($generalaverage)->where('semid',DB::table('semester')->where('isactive','1')->first()->id)->values();
                                    $temp_grades = collect($temp_grades)->where('semid',DB::table('semester')->where('isactive','1')->first()->id)->values();
                                }
                                $studgrades = $temp_grades;
                                $grades = collect($studgrades)->sortBy('sortid')->values();
                            }

                            $checksf5stat = collect($getsf5status)->where('studid', $eachstudent->id)->first();
                            if($eachstudent->promotionstatus == 0){
                                // $eachstudent->promstat = "";
                                if($checksf5stat)
                                {
                                    if($checksf5stat->actiontaken == 1)
                                    {
                                        $eachstudent->promstat = "PROMOTED";

                                    }
                                    elseif($checksf5stat->actiontaken == 2)
                                    {
                                        
                                        $eachstudent->promstat = "IRREGULAR";
                                    }
                                    elseif($checksf5stat->actiontaken == 3)
                                    {
                                        
                                        $eachstudent->promstat = "RETAINED";
                                    }

                                }else{
                                    // return $grades;
                                    
                                    if(count($grades) == 0){
                                        $eachstudent->promstat = "";
                                    }else{
                                        $numFailed = count(collect($generalaverage)->where('actiontaken','FAILED'));
                                        
                                        if($numFailed == 0){
                                            $eachstudent->promstat = "PROMOTED";
                                        }
                                        elseif($numFailed == 2 || $numFailed == 1) {
                                            $eachstudent->promstat = "IRREGULAR";
                                        }
                                        elseif($numFailed >= 3){
                                            $eachstudent->promstat = "RETAINED";
                                        }
                                    }
                                }
                            }else{

                                if($eachstudent->promotionstatus == 1)
                                {
                                    $eachstudent->promstat = "PROMOTED";

                                }
                                elseif($eachstudent->promotionstatus == 2)
                                {
                                    
                                    $eachstudent->promstat = "IRREGULAR";
                                }
                                elseif($eachstudent->promotionstatus == 3)
                                {
                                    
                                    $eachstudent->promstat = "RETAINED";
                                }
                            }
                            // return $generalaverage;
                            if(count($generalaverage) == 0)
                            {
                                $eachstudent->proficiency = "";
                            }else{
                                $grade_genave = ($generalaverage[0]->finalrating > 0 ? $generalaverage[0]->finalrating : $generalaverage[0]->lfr) ?? $generalaverage[0]->finalrating;
                                if($grade_genave == 0){
                                    $eachstudent->proficiency = "";
                                }elseif($grade_genave <= 74){
                                    $eachstudent->proficiency = "B";
                                }elseif($grade_genave >= 75 && $grade_genave <= 79){
                                    $eachstudent->proficiency = "D";
                                }elseif($grade_genave >= 80 && $grade_genave <= 84){
                                    $eachstudent->proficiency = "AP";
                                }elseif($grade_genave >= 85 && $grade_genave <= 89){
                                    $eachstudent->proficiency = "P";
                                }elseif($grade_genave >= 90){
                                    $eachstudent->proficiency = "A";
                                }
                            }

                        }
                    }
                    // PROMOTED
                        $eachlevel->promoted = count(collect($eachlevel->students)->where('promstat','PROMOTED'));
                        $eachlevel->promotedmale = count(collect($eachlevel->students)->where('promstat','PROMOTED')->where('gendersort','MALE'));
                        $eachlevel->promotedfemale = count(collect($eachlevel->students)->where('promstat','PROMOTED')->where('gendersort','FEMALE'));
                    // IRREGULAR
                        $eachlevel->irregular = count(collect($eachlevel->students)->where('promstat','IRREGULAR'));
                        $eachlevel->irregularmale = count(collect($eachlevel->students)->where('promstat','IRREGULAR')->where('gendersort','MALE'));
                        $eachlevel->irregularfemale = count(collect($eachlevel->students)->where('promstat','IRREGULAR')->where('gendersort','FEMALE'));
                    // RETAINED
                        $eachlevel->retained = count(collect($eachlevel->students)->where('promstat','RETAINED'));
                        $eachlevel->retainedmale = count(collect($eachlevel->students)->where('promstat','RETAINED')->where('gendersort','MALE'));
                        $eachlevel->retainedfemale = count(collect($eachlevel->students)->where('promstat','RETAINED')->where('gendersort','FEMALE'));
                    
                    // LEVEL OF PROFICIENCY: BEGINNNING (B: 74% and below)
                        $eachlevel->proficiencyb = count(collect($eachlevel->students)->where('proficiency','B'));
                        $eachlevel->proficiencybmale = count(collect($eachlevel->students)->where('proficiency','B')->where('gendersort','MALE'));
                        $eachlevel->proficiencybfemale = count(collect($eachlevel->students)->where('proficiency','B')->where('gendersort','FEMALE'));
                    // LEVEL OF PROFICIENCY: DEVELOPING (D: 75%-79%)
                        $eachlevel->proficiencyd = count(collect($eachlevel->students)->where('proficiency','D'));
                        $eachlevel->proficiencydmale = count(collect($eachlevel->students)->where('proficiency','D')->where('gendersort','MALE'));
                        $eachlevel->proficiencydfemale = count(collect($eachlevel->students)->where('proficiency','D')->where('gendersort','FEMALE'));
                    // LEVEL OF PROFICIENCY: APPROACHING PROFICIENCY (AP: 80%-84%)
                        $eachlevel->proficiencyap = count(collect($eachlevel->students)->where('proficiency','AP'));
                        $eachlevel->proficiencyapmale = count(collect($eachlevel->students)->where('proficiency','AP')->where('gendersort','MALE'));
                        $eachlevel->proficiencyapfemale = count(collect($eachlevel->students)->where('proficiency','AP')->where('gendersort','FEMALE'));
                    // LEVEL OF PROFICIENCY: PROFICIENT (P: 85% -89%)
                        $eachlevel->proficiencyp = count(collect($eachlevel->students)->where('proficiency','P'));
                        $eachlevel->proficiencypmale = count(collect($eachlevel->students)->where('proficiency','P')->where('gendersort','MALE'));
                        $eachlevel->proficiencypfemale = count(collect($eachlevel->students)->where('proficiency','P')->where('gendersort','FEMALE'));
                    // LEVEL OF PROFICIENCY: ADVANCED (A: 90% and above)
                        $eachlevel->proficiencya = count(collect($eachlevel->students)->where('proficiency','A'));
                        $eachlevel->proficiencyamale = count(collect($eachlevel->students)->where('proficiency','A')->where('gendersort','MALE'));
                        $eachlevel->proficiencyafemale = count(collect($eachlevel->students)->where('proficiency','A')->where('gendersort','FEMALE'));
        
        
                }
                // return $gradelevels;
                if(!$request->has('export'))
                {
                    return view('registrar.forms.form6.results')
                        ->with('gradelevels',$gradelevels)
                        ->with('acadprogid',$request->get('acadprogid'))
                        ->with('semid',$request->get('semid'));
                }else{
                    $acadprogid = $request->get('acadprogid');
                    $sydesc = DB::table('sy')->where('id',$request->get('syid'))->first()->sydesc;
                    // return $gradelevels;
                    if($request->has('template'))
                    {
                        $semid = $request->get('semid');
                        if($acadprogid == 5)
                        {
                            $pdf = PDF::loadview('registrar/forms/form6/pdf_form6_shs_temp'.$request->get('template'),compact('gradelevels','acadprogid','sydesc','semid'));
                            $pdf->getDomPDF()->set_option("enable_php", true);
                            return $pdf->stream('School Form 6');
                        }else{
                            $pdf = PDF::loadview('registrar/forms/form6/pdf_form6',compact('gradelevels','acadprogid','sydesc'));
                            $pdf->getDomPDF()->set_option("enable_php", true);
                            return $pdf->stream('School Form 6');
                        }
                    }else{
                        $pdf = PDF::loadview('registrar/forms/form6/pdf_form6',compact('gradelevels','acadprogid','sydesc'));
                        $pdf->getDomPDF()->set_option("enable_php", true);
                        return $pdf->stream('School Form 6');
                    }
            
                }
            }
            elseif($request->get('action') == 'export')
            {
                if($request->get('report') == 'studentesc')
                {
                    $notes = DB::table('reports_notes')
                        ->where('reportname','certificateesc')
                        ->where('deleted','0')
                        ->where('createdby',auth()->user()->id)
                        ->get();
                    $levelname = DB::table('gradelevel')->where('id', $request->get('levelid'))->first()->levelname;
                    $studinfo = DB::table('studinfo')->where('id', $request->get('studentid'))->first();
                    $acadprogid = $request->get('acadprogid');
                    $acadprogname = DB::table('academicprogram')->where('id', $acadprogid)->first()->progname;
                    $sydesc = DB::table('sy')->where('id',$request->get('schoolyear'))->first()->sydesc;
                    $pdf = PDF::loadview('registrar/forms/masterlist/pdf_masterlist',compact('studinfo','levelname','acadprogname','notes'));
            
                    $pdf->getDomPDF()->set_option("enable_php", true);
                    return $pdf->stream('Student ESC Form');
                }
            }
        }      
	}
    public function printable_certifications(Request $request)
    {
        if(!$request->has('action'))
        {
            return view('registrar.otherprintables.certifications.index');
        }else{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $certtype = $request->get('certtype');
            $students = collect();
            $students_1 = DB::table('enrolledstud')
                ->select('studinfo.id','sid','lrn','lastname','firstname',
							'middlename','suffix',
							'gradelevel.id as levelid',
							'gender',
							'levelname','gradelevel.acadprogid','studentstatus.description as studentstatus')
                ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                ->join('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                ->where('studinfo.deleted','0')
                ->where('gradelevel.deleted','0')
                ->where('enrolledstud.deleted','0')
                ->where('enrolledstud.syid',$syid)
                // ->whereIn('studstatus',[1,2,4])
                ->orderBy('lastname','asc')
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
					'middlename','suffix','gradelevel.id as levelid','levelname','gradelevel.acadprogid','studentstatus.description as studentstatus')
                ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
				->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                ->join('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                ->where('studinfo.deleted','0')
                ->where('gradelevel.deleted','0')
                ->where('sh_enrolledstud.deleted','0')
                ->where('sh_enrolledstud.syid',$syid)
                ->where('sh_enrolledstud.semid',$semid)
                // ->whereIn('studstatus',[1,2,4])
                ->orderBy('lastname','asc')
                ->get();
    
            $students_3 = DB::table('college_enrolledstud')
                ->select('studinfo.id','sid','lrn','lastname','firstname',
							'middlename','suffix','gradelevel.id as levelid',
							'courseDesc as coursename','collegeDesc as collegename',
							'gender',
							'levelname','gradelevel.acadprogid','studentstatus.description as studentstatus')
                ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
				->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
				->join('college_colleges','college_courses.collegeid','=','college_colleges.id')
                ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                ->join('studentstatus','college_enrolledstud.studstatus','=','studentstatus.id')
                ->where('studinfo.deleted','0')
                ->where('gradelevel.deleted','0')
                ->where('college_enrolledstud.deleted','0')
                ->where('college_enrolledstud.syid',$syid)
                ->where('college_enrolledstud.semid',$semid)
                // ->whereIn('studstatus',[1,2,4])
                ->orderBy('lastname','asc')
                ->get();
                
            $students = $students->merge($students_1);
            $students = $students->merge($students_2);
            $students = $students->merge($students_3);
            $students = $students->unique('id');
            $students = $students->sortBy('firstname')->sortBy('lastname')->values()->all();
            if($request->get('action') == 'filter')
            {
                $templates = DB::table('printables')
                    ->where('deleted','0')
                    ->get();

                return view('registrar.otherprintables.certifications.results')
                    ->with('students',$students)
                    ->with('templates',$templates);

            }elseif($request->get('action') == 'export')
            {
                $schoolyear = DB::table('sy')->where('id', $request->get('syid'))->first()->sydesc;
                $semester = DB::table('semester')->where('id', $request->get('semid'))->first()->semester;
                // return $request->all();
                $studentinfo = collect($students)->where('id', $request->get('studid'))->first();
                $PHPWord = new \PhpOffice\PhpWord\PhpWord();
				
				//return collect($studentinfo);
                
                $templateinfo = DB::table('printables')
                    ->where('id', $request->get('templateid'))
                    ->first();
                $document = $PHPWord->loadTemplate(public_path().'/'.$templateinfo->filepath);



    
                $document->setValue('sid', $studentinfo->sid);
                $document->setValue('lrn',$studentinfo->lrn);

                $document->setValue('fullname_first',$studentinfo->firstname.' '.$studentinfo->middlename.' '.$studentinfo->lastname.' '.$studentinfo->suffix);
                $document->setValue('fullname_last',$studentinfo->lastname.', '.$studentinfo->firstname.' '.$studentinfo->suffix.' '.$studentinfo->middlename);

                $document->setValue('firstname',$studentinfo->firstname);
                $document->setValue('middlename',$studentinfo->middlename);
                $document->setValue('lastname',$studentinfo->lastname);
                $document->setValue('suffix',$studentinfo->suffix);
                $document->setValue('schoolyear',$schoolyear);
                $document->setValue('semester',$semester);
				
				$middleinitial = '';
				if(isset($studentinfo->middlename)){
					$middleinitial = $studentinfo->middlename[0].'.';
				}
				
				
				$yearlevel = '';
				if($studentinfo->levelid == 17){
					$yearlevel = 'First Year';
				}else if($studentinfo->levelid == 18){
					$yearlevel = 'Second Year';
				}else if($studentinfo->levelid == 19){
					$yearlevel = 'Third Year';
				}else if($studentinfo->levelid == 20){
					$yearlevel = 'Fourth Year';
				}else if($studentinfo->levelid == 21){
					$yearlevel = 'Fifth Year';
				}
				
                $document->setValue('gradelevel',$yearlevel);

                //$document->setValue('gradelevel',str_replace(' COLLEGE','',$studentinfo->levelname));


				$objectpronoun = 'him';
				$subjectpronoun = 'He';

				if($studentinfo->gender == 'FEMALE'){
					$objectpronoun = 'her';
					$subjectpronoun = 'She';
				}

				$document->setValue('objectpronoun',$objectpronoun);
				$document->setValue('section',$studentinfo->strandname ?? '');

                $document->setValue('subjectpronoun',$subjectpronoun);

				
                $document->setValue('section',$studentinfo->sectionname ?? '');
                $document->setValue('track',$studentinfo->trackname ?? '');
                $document->setValue('strand',$studentinfo->strandname ?? '');
                $document->setValue('college',$studentinfo->collegename ?? '');
                $document->setValue('course',$studentinfo->coursename ?? '');
                
				$document->setValue('dateissued',date('jS', strtotime($request->get('dateissued'))).' day of '.date('F Y', strtotime($request->get('dateissued'))));
                $document->setValue('purpose',$request->get('purpose'));
					
				$datemonthyear = date('F Y', strtotime($request->get('dateissued')));
				$date1 = date('j', strtotime($request->get('dateissued')));
				$date2 = date('S', strtotime($request->get('dateissued')));
				
				$document->setValue('datemonthyear',$datemonthyear);
                $document->setValue('date1',$date1);
                $document->setValue('date2',$date2);
			
                
                $records = collect(\App\Models\College\TOR::getrecords($request->get('studid'), DB::table('sy')->select('sy.*','id as syid')->where('id', $request->get('syid'))->get()))->where('semid',$request->get('semid'));
                $subjects = collect($records)->count() == 0 ? array() : collect($records)->first()->subjdata;


                if(count($subjects)>0)
                {
                    foreach($subjects as $subject)
                    {
                        $subject->subjectcode = $subject->subjcode;
                        $subject->subjectdesc = $subject->subjdesc;
                        $subject->subjectgrade = $subject->subjgrade;
                        $subject->subjectunits = $subject->subjunit;
                        $subject->subjectcredits = $subject->subjcredit;

                        $subject->subjremarks = $subject->subjgrade > 0 ? ($subject->subjgrade > 74 ? 'PASSED' : 'FAILED') : '';
                        $subject->subjectremarks = $subject->subjremarks;
                    }
                }
                // return $subjects;
                // $values = [
                //     (object)['subjcode' => 'Testing 1', 'subjunit' => 3, 'subjgrade' => 1.2 , 'subjremark'=>'FAILED'],
                //     (object)['subjcode' => 'Testing 2', 'subjunit' => 3, 'subjgrade' => 1.2 , 'subjremark'=>'FAILED'],
                //     (object)['subjcode' => 'Testing 3', 'subjunit' => 3, 'subjgrade' => 1.2 , 'subjremark'=>'FAILED'],
                //     (object)['subjcode' => 'Testing 4', 'subjunit' => 3, 'subjgrade' => 1.2 , 'subjremark'=>'PASSED'],
                //     (object)['subjcode' => 'Testing 5', 'subjunit' => 3, 'subjgrade' => 1.2 , 'subjremark'=>'FAILED'],
                // ];
                // return $values;
                // $contentsArray = explode("\n",$contents);
                
				//return $subjects;	

				try{
					$document->cloneRowAndSetValues('subjectcode',$subjects);
					
				}catch(\Exception $e){
				}
                
				$document->saveAs($templateinfo->name.'.'.$templateinfo->extension);
                $file_url = $templateinfo->name.'.'.$templateinfo->extension;
                header('Content-Type: application/octet-stream');  
                header("Content-Transfer-Encoding: utf-8");   
                header("Content-disposition: attachment; filename=".$file_url);   
                readfile($file_url);  
    
                exit();
            }elseif($request->get('action') == 'upload')
            {
                $syid = $request->get('syid');
                $semid = $request->get('semid');
                $cert_type = $request->get('certtype');

                $localfolder = 'certifications';
                // return $localfolder;
                $file = $request->file('file');
            
                $filename = $file->getClientOriginalName();
        
                $extension = $file->getClientOriginalExtension();
        
                if (! File::exists(public_path().$localfolder)) {
        
                    $path = public_path($localfolder);
        
                    if(!File::isDirectory($path)){
                        
                        File::makeDirectory($path, 0777, true, true);
        
                    }
                    
                }
                if (strpos($request->root(),'http://') !== false) {
                    $urlFolder = str_replace('http://','',$request->root());
                } else {
                    $urlFolder = str_replace('https://','',$request->root());
                }
                    
                if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder)) {
        
                    $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
                    
                    if(!File::isDirectory($cloudpath)){
        
                        File::makeDirectory($cloudpath, 0777, true, true);
                        
                    }
                    
                }
                
        
                // $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
                // try{
        
                //     $file->move($clouddestinationPath, $filename);
                // }
                // catch(\Exception $e){
                //     file_put_contents($clouddestinationPath, $filename);
                // }
                
                $id = DB::table('printables')
                    ->insertGetId([
                        'name'          => pathinfo($filename,PATHINFO_FILENAME),
                        'extension'          => $extension,
                        // 'filepath'          => $localfolder.'/'.$filename,
                        // 'syid'         => $extension,
                        // 'semid'         => $extension,
                        'cert_type'         => $cert_type,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
                $destinationPath = public_path($localfolder.'/');
                $newname = pathinfo($filename,PATHINFO_FILENAME).'-'.$id.'.'.$extension;
                try{
        
                    $file->move($destinationPath,$newname);
        
                }
                catch(\Exception $e){
                    // file_put_contents($destinationPath, $filename);
            
                }
        

                DB::table('printables')
                    ->where('id', $id)
                    ->update([
                        'filepath'      => $localfolder.'/'.pathinfo($filename,PATHINFO_FILENAME).'-'.$id.'.'.$extension
                    ]);

                $templateinfo = DB::table('printables')->where('id', $id)->first();
                return collect($templateinfo);
        
            }elseif($request->get('action') == 'template_delete')
            {
                DB::table('printables')
                    ->where('id', $request->get('tempid'))
                    ->update([
                        'deleted'           => 1,
                        'deletedby'         => auth()->user()->id,
                        'deleteddatetime'   => date('Y-m-d H:i:s')
                    ]);
                return 1;
            }else{
                return view('registrar.otherprintables.certifications.index');
            }
        }
            // ->with();
    }
	public function schoolhead($action, Request $request)
    {
        $adasdsad = DB::table('schoolinfo')
            ->update([
                'authorized'    => strtoupper($request->get('schoolhead'))
            ]);

        return back();
    }
    public function reports($id, Request $request)
    {
        if($id == 'selectForms'){
            return view("registrar.reportsforms");
        }
        elseif($id == 'selectSy'){
            $gradelevels = DB::table('gradelevel')
                ->where('acadprogid','5')
                ->where('deleted','0')
                ->orderBy('levelname','asc')
                ->get();

            $semester = DB::table('semester')
                ->select('id','semester','isactive')
                ->orderbyDesc('semester')
                ->get();
            $schoolyear = DB::table('sy')
                ->select('id','sydesc','isactive')
                ->orderbyDesc('sydesc')
                ->get();
            $academicprogram = $request->get('academicprogram');
            // return 'sdf';
            return view("registrar.reports")
                ->with('selectedform',$request->get('selectedform'))
                ->with('academicprogram',$academicprogram)
                ->with('schoolyear',$schoolyear)
                ->with('semesters',$semester)
                ->with('gradelevels',$gradelevels);
        }
        elseif($id == 'selectSection'){
            
            // return 'asdasd';
            $selectedyear = $request->get('syid');
            $selectedform = $request->get('selectedform');
            $schoolyear = DB::table('sy')
                ->select('id','sydesc','isactive')
                ->where('id',$selectedyear)
                ->get();
            $academicprogram = $request->get('academicprogram');
            // -----------------------------------------
            if($academicprogram != 'seniorhighschool')
            {
                $students = DB::table('studinfo')
                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','sections.id as sectionid','sections.sectionname','gradelevel.id as levelid','gradelevel.levelname','gradelevel.acadprogid','gradelevel.sortid','enrolledstud.studstatus','studentstatus.description')
                    ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                    ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                    // ->join('sectiondetail','enrolledstud.sectionid','=','sectiondetail.sectionid')
                    // ->join('teacher','sectiondetail.teacherid','=','teacher.id')
                    ->join('sections','enrolledstud.sectionid','=','sections.id')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->where('enrolledstud.syid',$selectedyear)
                    // ->where('sectiondetail.syid',$selectedyear)
                    ->where('enrolledstud.studstatus','!=','0')
                    ->where('enrolledstud.studstatus','<=','5')
                    ->where('studinfo.deleted','0')
                    ->where('enrolledstud.deleted','0')
                    ->orderBy('sortid','asc')
                    ->get();
            }else{
                $students = DB::table('studinfo')
                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','sections.id as sectionid','sections.sectionname','gradelevel.id as levelid','gradelevel.levelname','gradelevel.acadprogid','gradelevel.sortid','sh_enrolledstud.studstatus','studentstatus.description')
                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                    ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                    ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->where('sh_enrolledstud.syid',$selectedyear)
                    // ->where('sh_enrolledstud.semid',DB::table('semester')->where('isactive','1')->first()->id)
                    ->where('sh_enrolledstud.studstatus','!=','0')
                    ->where('sh_enrolledstud.studstatus','<=','5')
                    ->where('studinfo.deleted','0')
                    ->where('sh_enrolledstud.deleted','0')
                    ->orderBy('sortid','asc')
                    ->get();
            }
                  
            if(count($students)>0)
            {
                foreach($students as $student)
                {
                    $checkifexistteacher = DB::table('sectiondetail')
                        ->select('teacher.title','teacher.lastname','teacher.firstname')
                        ->join('teacher','sectiondetail.teacherid','teacher.id')
                        ->where('sectionid', $student->sectionid)
                        ->where('syid', $selectedyear)
                        ->where('sectiondetail.deleted','0')
                        ->first();

                    if($checkifexistteacher)
                    {
                        $student->teachername = $checkifexistteacher->title.' '.$checkifexistteacher->firstname.' '.$checkifexistteacher->lastname; 
                    }else{
                        $student->teachername = '';
                    }

                }

            }
            
            if($academicprogram == 'preschool')
            {
                $acadprogid = 2;
            }
            elseif($academicprogram == 'elementary')
            {
                $acadprogid = 3;
            }
            elseif($academicprogram == 'juniorhighschool')
            {
                $acadprogid = 4;
            }
            elseif($academicprogram == 'seniorhighschool')
            {
                $acadprogid = 5;
            }
            $students = collect($students)->where('acadprogid', $acadprogid);
            $students = collect($students)->groupBy('levelname');
            // return $request->get('syid');
            if($request->get('selectedform') == 'Student Masterlist'){
                return view("registrar.forms.studentmasterlist")
                    ->with('students',$students)
                    ->with('schoolyear',$request->get('syid'))
                    ->with('selectedyear',$selectedyear)
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('academicprogram',$academicprogram)
                    ->with('selectedform',$request->get('selectedform'));
            }
            elseif($request->get('selectedform') == 'School Form 1' ){
                return view("registrar.forms.schoolform1")
                    ->with('students',$students)
                    ->with('schoolyear',$request->get('syid'))
                    ->with('selectedyear',$selectedyear)
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('academicprogram',$academicprogram)
                    ->with('selectedform',$request->get('selectedform'));
            }
            elseif($request->get('selectedform') == 'School Form 5' ){
                return view("registrar.forms.form5.schoolform5")
                    ->with('students',$students)
                    ->with('schoolyear',$request->get('syid'))
                    ->with('selectedyear',$selectedyear)
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('academicprogram',$academicprogram)
                    ->with('selectedform',$request->get('selectedform'));

            }
            elseif($request->get('selectedform') == 'School Form 5A'){
                // return $higher_level;
                return view("registrar.forms.form5.schoolform5a")
                    ->with('students',$students)
                    ->with('schoolyear',$request->get('syid'))
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('academicprogram',$academicprogram)
                    ->with('selectedform',$request->get('selectedform'));
            }
            elseif($request->get('selectedform') == 'School Form 5B'){
                return view("registrar.forms.form5.schoolform5b")
                    ->with('students',$students)
                    ->with('schoolyear',$request->get('syid'))
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('academicprogram',$academicprogram)
                    ->with('selectedform',$request->get('selectedform'));
            }
            elseif($request->get('selectedform') == 'School Form 6'){
                if($academicprogram == 'elementary'){
                    return view("registrar.reportsschoolform6")
                    ->with('grade_levels_lower',$lower_level)
                    ->with('grade_sections_lower',$lower_section)
                    ->with('schoolyear',$request->get('syid'))
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('academicprogram',$academicprogram)
                    ->with('selectedform',$request->get('selectedform'));
                }
                elseif($academicprogram == 'juniorhighschool'){
                    return view("registrar.reportsschoolform6")
                    ->with('grade_levels_lower',$junior_level)
                    ->with('grade_sections_lower',$junior_section)
                    ->with('schoolyear',$request->get('syid'))
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('academicprogram',$academicprogram)
                    ->with('selectedform',$request->get('selectedform'));
                }
                elseif($academicprogram == 'seniorhighschool'){
                    return view("registrar.reportsschoolform6")
                    ->with('grade_levels_higher',$higher_level)
                    ->with('grade_sections_higher',$higher_section)
                    ->with('schoolyear',$request->get('syid'))
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('academicprogram',$academicprogram)
                    ->with('selectedform',$request->get('selectedform'));
                }

            }
            elseif($request->get('selectedform') == 'School Form 9'){
                // return 'adas';
                return view("registrar.forms.schoolform9")
                    ->with('students',$students)
                    ->with('schoolyear',$request->get('syid'))
                    ->with('selectedyear',$selectedyear)
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('academicprogram',$academicprogram)
                    ->with('selectedform',$request->get('selectedform'));

            }
            elseif($request->get('selectedform') == 'School Form 10'){
                if($academicprogram == 'elementary'){
                    return view("registrar.reportsschoolform10")
                        ->with('grade_levels_lower',$lower_level)
                        ->with('grade_sections_lower',$lower_section)
                        ->with('schoolyear',$request->get('syid'))
                        ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                        ->with('academicprogram',$academicprogram)
                        ->with('selectedform',$request->get('selectedform'));
                }
                elseif($academicprogram == 'juniorhighschool'){
                    return view("registrar.reportsschoolform10")
                        ->with('grade_levels_lower',$junior_level)
                        ->with('grade_sections_lower',$junior_section)
                        ->with('schoolyear',$request->get('syid'))
                        ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                        ->with('academicprogram',$academicprogram)
                        ->with('selectedform',$request->get('selectedform'));
                }
                elseif($academicprogram == 'seniorhighschool'){
                    return view("registrar.reportsschoolform10")
                        ->with('grade_levels_higher',$higher_level)
                        ->with('grade_sections_higher',$higher_section)
                        ->with('schoolyear',$request->get('syid'))
                        ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                        ->with('academicprogram',$academicprogram)
                        ->with('selectedform',$request->get('selectedform'));
                }

            }
        }
        elseif($id == 'studentsform9'){

            // return $request->all();
            if($request->get('academicprogram') == 'seniorhighschool'){
                $students = Db::table('sh_enrolledstud')
                    ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','sh_enrolledstud.syid')
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->orderByDesc('syid')
                    ->distinct()
                    ->get();
                    return view("registrar.studentsform10senior")
                        ->with('academicprogram', $request->get('academicprogram'))
                        ->with('students', $students);
                
            }
            elseif($request->get('academicprogram') == 'juniorhighschool'){
                $students = Db::table('enrolledstud')
                    ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','enrolledstud.syid')
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('academicprogram.id','4')
                    ->orderByDesc('syid')
                    ->distinct()
                    ->get();
                    return view("registrar.studentsform10junior")
                        ->with('academicprogram', $request->get('academicprogram'))
                        ->with('students', $students);
                
            }
            elseif($request->get('academicprogram') == 'elementary'){
                // return 'ad';
                $students = Db::table('enrolledstud')
                    ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','enrolledstud.syid')
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('academicprogram.id','3')
                    ->orderByDesc('syid')
                    ->distinct()
                    ->get();
                    return view("registrar.studentsform10elem")
                        ->with('academicprogram', $request->get('academicprogram'))
                        ->with('students', $students);
                
            }

        }
        elseif($id == 'studentsform10'){
            if($request->get('academicprogram') == 'seniorhighschool'){
                
                $students = Db::table('studinfo')
                    ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix')
                    ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('academicprogram.acadprogcode','SHS')
                    ->distinct()
                    ->get();
                    
                    return view("registrar.studentsform10senior")
                        ->with('academicprogram', $request->get('academicprogram'))
                        ->with('students', $students);
                
            }
            elseif($request->get('academicprogram') == 'juniorhighschool'){

                $students = Db::table('studinfo')
                        ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix')
                        ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                        ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                        ->where('academicprogram.id','4')
                        ->distinct()
                        ->get();


                    return view("registrar.studentsform10junior")
                        ->with('academicprogram', $request->get('academicprogram'))
                        ->with('students', $students);
                
            }
            elseif($request->get('academicprogram') == 'elementary'){
                
                $students = Db::table('studinfo')
                                ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix')
                                ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                                ->where('academicprogram.id','3')
                                ->distinct()
                                ->get();

                    return view("registrar.studentsform10elem")
                                    ->with('academicprogram', $request->get('academicprogram'))
                                    ->with('students', $students);
                            
            }
                // return $students;
        }
        else{
            return view("registrar.reportsforms")
                ->with('academicprogram', $id);
        }
    }
    public function filterstudents(Request $request)
    {
        // return $request->all();
        $selectedform       = $request->get('selectedform');
        $academicprogram    = $request->get('academicprogram');
        $selectedschoolyear = $request->get('selectedschoolyear');
        $selectsemester     = $request->get('selectsemester');
        $selectgradelevel   = $request->get('selectgradelevel');

        if($academicprogram == 'seniorhighschool')
        {
            
            $students = Db::table('studinfo')
                ->select('studinfo.*','sh_enrolledstud.sectionid','sh_enrolledstud.strandid','sh_enrolledstud.levelid','gradelevel.levelname','sections.sectionname')
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                ->where('sh_enrolledstud.semid',$selectsemester)
                ->where('sh_enrolledstud.syid',$selectedschoolyear)
                ->where('sh_enrolledstud.levelid',$selectgradelevel)
                ->where('studinfo.deleted','0')
                ->distinct()
                ->get();
                
            if(count($students)>0)
            {
                foreach($students as $student)
                {

                    $strandinfo = DB::table('sh_strand')
                        ->where('id', $student->strandid)
                        ->first();

                    $student->strandname = $strandinfo->strandname;
                }
            }

            return view('registrar.forms.form9.table_students')
                ->with('students', $students)
                ->with('selectedform', $selectedform)
                ->with('syid', $selectedschoolyear)
                ->with('acadprogid', 5)
                ->with('semid', $selectsemester)
                ->with('levelid', $selectgradelevel)
                ->with('academicprogram', $academicprogram)
                ->with('selectedschoolyear', $selectedschoolyear)
                ->with('selectsemester', $selectsemester)
                ->with('selectgradelevel', $selectgradelevel);
        }
    }
    public function reportstudentmasterlist(Request $request, $id, $syid, $sectionid)
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

        if(Session::get('currentPortal') == 1){

            $extends = "teacher.layouts.app";
            
        }elseif(Session::get('currentPortal') == 2){

            $extends = "principalsportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 3  ||  Session::get('currentPortal') == 8){

            $extends = "registrar.layouts.app";

        }elseif(Session::get('currentPortal') == 4  ||  Session::get('currentPortal') == 15){

            $extends = "finance.layouts.app";

        }elseif(Session::get('currentPortal') == 6){

            $extends = "adminPortal.layouts.app2";

        }elseif(Session::get('currentPortal') == 10 || $refid == 26){

            $extends = "hr.layouts.app";

        }elseif(Session::get('currentPortal') == 12){

            $extends = "adminITPortal.layouts.app";

        }elseif(Session::get('currentPortal') == 14){

            $extends = "deanportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 16){

            $extends = "chairpersonportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 18){

            $extends = "ctportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 29  || $refid == 29){
    
            $extends = "idmanagement.layouts.app2";

        }else{

            $extends = "general.defaultportal.layouts.app";

        }
        if($request->has('esc'))
        {
            $esc = $request->get('esc');
        }else{
            $esc = 0;
        }

        $acadprogid = $request->get('acadprogid');
        // $acadprogid = DB::table('gradelevel')
        //     ->where('gradelevel.id', $request->get('levelid'))->first()->acadprogid;

        if($request->has('academicprogram'))
        {
            $academicprogram = $request->get('academicprogram');
        }else{
            $academicprogram = strtolower(DB::table('academicprogram')
                ->where('id', $acadprogid)
                ->first()->progname);
            // $academicprogram = strtolower(DB::table('gradelevel')
            //     ->where('gradelevel.id', $request->get('levelid'))
            //     ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            //     ->first()->progname);
        }
        
        
        $schoolyear = DB::table('sy')
            ->where('id',$syid)
            ->get();
            
            
        $getSection = DB::table('sections')
            ->select('sections.*','gradelevel.levelname')
            ->where('sections.id', $sectionid)
            ->leftJoin('gradelevel','sections.levelid','=','gradelevel.id')
            ->get();
        
        $teacher = DB::table('sectiondetail')
            ->select(
                'teacher.title',
                'teacher.lastname',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.suffix'
            )
            ->join('teacher','sectiondetail.teacherid','=','teacher.id')
            ->where('sectionid', $sectionid)
            ->where('syid', $syid)
            ->where('sectiondetail.deleted','0')
            ->first();
            
        $semid = DB::table('semester')
            ->where('isactive','1')
            ->first()->id;
        
        $enrolledstud = DB::table('studinfo')
            ->select(
                'studinfo.id as studentid',
                'studinfo.lrn as student_lrn',
                'studinfo.sid as student_idnumber',
                'studinfo.firstname as student_firstname',
                'studinfo.middlename as student_middlename',
                'studinfo.lastname as student_lastname',
                'studinfo.suffix as student_suffix',
                DB::raw('UPPER(`gender`) as student_gender'),
                DB::raw('LOWER(`gender`) as gender'),
                'studinfo.dob',
                'studinfo.semail',
                'studinfo.mothername',
                'studinfo.mcontactno',
                'studinfo.moccupation',
                'studinfo.fathername',
                'studinfo.fcontactno',
                'studinfo.foccupation',
                'studinfo.guardianname',
                'studinfo.contactno as studentcontactno',
                'studinfo.street',
                'studinfo.barangay',
                'studinfo.city',
                'studinfo.province',
                'studinfo.gcontactno',
                'studinfo.ismothernum',
                'studinfo.isfathernum',
                'studinfo.isguardannum',
                'enrolledstud.sectionid',
                'enrolledstud.levelid',
                'sections.sectionname as sectionname',
                'gradelevel.acadprogid',
                'gradelevel.levelname as gradelevelname',
                'grantee.description as grantee',
                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                'enrolledstud.studstatus',
                'studentstatus.description'
                )
            ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
            ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
            ->join('sections','enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
            ->leftJoin('grantee','studinfo.grantee','=','grantee.id')
            ->where('enrolledstud.studstatus','!=','0')
            ->where('enrolledstud.studstatus','<=','5')
            ->where('enrolledstud.deleted','0')
            ->where('studinfo.deleted','0')
            ->where('enrolledstud.syid',$syid)
            // ->where('enrolledstud.levelid',$request->get('levelid'))
            // ->where('enrolledstud.sectionid',$sectionid)
            ->whereIn('enrolledstud.studstatus',[1,2,4])
            ->distinct('studentid')
            ->get();

            
        $sh_enrolledstud = DB::table('studinfo')
            ->select(
                'studinfo.id as studentid',
                'studinfo.lrn as student_lrn',
                'studinfo.sid as student_idnumber',
                'studinfo.firstname as student_firstname',
                'studinfo.middlename as student_middlename',
                'studinfo.lastname as student_lastname',
                'studinfo.suffix as student_suffix',
                DB::raw('UPPER(`gender`) as student_gender'),
                DB::raw('LOWER(`gender`) as gender'),
                'studinfo.dob',
                'studinfo.semail',
                'studinfo.mothername',
                'studinfo.mcontactno',
                'studinfo.moccupation',
                'studinfo.fathername',
                'studinfo.fcontactno',
                'studinfo.foccupation',
                'studinfo.guardianname',
                'studinfo.contactno as studentcontactno',
                'studinfo.street',
                'studinfo.barangay',
                'studinfo.city',
                'studinfo.province',
                'studinfo.gcontactno',
                'studinfo.ismothernum',
                'studinfo.isfathernum',
                'studinfo.isguardannum',
                'sh_enrolledstud.sectionid',
                'sh_enrolledstud.levelid',
                'sections.sectionname as sectionname',
                'gradelevel.levelname as gradelevelname',
                'gradelevel.acadprogid',
                'grantee.description as grantee',
                'sh_strand.id as strandid',
                'sh_strand.strandname',
                'sh_strand.strandcode',
                'sh_track.trackname',
                'grantee.description as grantee',
                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                'sh_enrolledstud.studstatus',
                'studentstatus.description'
                )
            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
            ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
            ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
            ->leftJoin('grantee','studinfo.grantee','=','grantee.id')
            ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
            ->join('sh_track','sh_strand.trackid','=','sh_track.id')
            // ->leftJoin('sections','sectiondetail.sectionid','=','sections.id')
            // ->leftJoin('gradelevel','sections.levelid','=','gradelevel.id')
            // ->leftJoin('enrolledstud','studinfo.id','=','enrolledstud.sectionid')
            // ->leftJoin('sy','enrolledstud.syid','=','sy.id')
            ->where('sh_enrolledstud.studstatus','!=','0')
            ->where('sh_enrolledstud.studstatus','<=','5')
            ->where('sh_enrolledstud.deleted','0')
            ->where('studinfo.deleted','0')
            ->where('sh_enrolledstud.syid',$syid)
            ->where('sh_enrolledstud.semid',$request->get('semid'))
            // ->where('sh_enrolledstud.levelid',$request->get('levelid'))
            // ->where('sh_enrolledstud.sectionid',$sectionid)
            ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
            ->distinct('studentid')
            ->get();
            
        $college_enrolledstud = DB::table('studinfo')
        ->select(
            'studinfo.id as studentid',
            'studinfo.lrn as student_lrn',
            'studinfo.sid as student_idnumber',
            'studinfo.firstname as student_firstname',
            'studinfo.middlename as student_middlename',
            'studinfo.lastname as student_lastname',
            'studinfo.suffix as student_suffix',
            DB::raw('UPPER(`gender`) as student_gender'),
            DB::raw('LOWER(`gender`) as gender'),
            'studinfo.dob',
            'studinfo.semail',
            'studinfo.mothername',
            'studinfo.mcontactno',
            'studinfo.moccupation',
            'studinfo.fathername',
            'studinfo.fcontactno',
            'studinfo.foccupation',
            'studinfo.guardianname',
            'studinfo.contactno as studentcontactno',
            'studinfo.street',
            'studinfo.barangay',
            'studinfo.city',
            'studinfo.province',
            'studinfo.gcontactno',
            'studinfo.ismothernum',
            'studinfo.isfathernum',
            'studinfo.isguardannum',
            'college_enrolledstud.sectionid',
            'college_enrolledstud.yearLevel as levelid',
            'college_sections.sectionDesc as sectionname',
            'gradelevel.levelname as gradelevelname',
            'gradelevel.acadprogid',
            'grantee.description as grantee',
            'college_courses.id as strandid',
            'college_courses.id as courseid',
            'college_courses.collegeid',
            'college_courses.courseDesc as strandname',
            'college_courses.courseabrv as strandcode',
            'college_colleges.collegeDesc as trackname',
            'grantee.description as grantee',
            DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
            'college_enrolledstud.studstatus',
            'college_year.yearDesc',
            'studentstatus.description'
            )
        ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
        ->leftJoin('college_year','college_enrolledstud.yearLevel','=','college_year.levelid')
        ->leftJoin('studentstatus','college_enrolledstud.studstatus','=','studentstatus.id')
        ->leftJoin('college_sections','college_enrolledstud.sectionid','=','college_sections.id')
        ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
        ->leftJoin('grantee','studinfo.grantee','=','grantee.id')
        ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
        ->join('college_colleges','college_courses.collegeid','=','college_colleges.id')
        // ->leftJoin('sections','sectiondetail.sectionid','=','sections.id')
        // ->leftJoin('gradelevel','sections.levelid','=','gradelevel.id')
        // ->leftJoin('enrolledstud','studinfo.id','=','enrolledstud.sectionid')
        // ->leftJoin('sy','enrolledstud.syid','=','sy.id')
        ->where('college_enrolledstud.studstatus','!=','0')
        ->where('college_enrolledstud.studstatus','<=','5')
        ->where('college_enrolledstud.deleted','0')
        ->where('studinfo.deleted','0')
        ->where('college_enrolledstud.syid',$syid)
        ->where('college_enrolledstud.semid',$request->get('semid'))
        // ->where('sh_enrolledstud.levelid',$request->get('levelid'))
        // ->where('sh_enrolledstud.sectionid',$sectionid)
        ->whereIn('college_enrolledstud.studstatus',[1,2,4])
        ->distinct('studentid')
        ->get();
        
        $students = collect();
        $students = $students->merge($enrolledstud);
        $students = $students->merge($sh_enrolledstud);
        $students = $students->merge($college_enrolledstud);
        $students = $students->unique('studentid');
        $students = $students->sortBy('studentname');
        if($request->get('acadprogid') > 0)
        {
            $students = collect($students)->where('acadprogid', $request->get('acadprogid'))->values()->all();
        }
        
        if($request->get('levelid') > 0)
        {
            $students = collect($students)->where('levelid', $request->get('levelid'))->values()->all();
        }
        if($request->get('collegeid') > 0)
        {
            $students = collect($students)->where('collegeid', $request->get('collegeid'))->values()->all();
        }
        if($request->get('courseid') > 0)
        {
            $students = collect($students)->where('courseid', $request->get('courseid'))->values()->all();
        }
        // return $students;
        if($request->get('sectionid') > 0)
        {
            $students = collect($students)->where('sectionid', $request->get('sectionid'))->values()->all();
        }
        if($request->has('strandid'))
        {
            $students = $students->where('strandid', $request->get('strandid'));
        }
        $students = collect($students)->values()->all();
        
        if($esc > 0){
            $students = collect($students)->where('grantee','ESC')->values();
            if(count($students) == 0 && $id == 'preview'){
                return view("registrar.forms.studentmasterlistpreview")
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('schoolyear',$syid)
                    ->with('esc',$esc)
                    ->with('extends',$extends)
                    ->with('sectiondetail',$getSection)
                    ->with('sectionid',$sectionid)
                    ->with('levelid',$request->get('levelid'))
                    ->with('selectedsection',$getSection[0]->sectionname)
                    ->with('selectedform',$request->get('selectedform'))
                    ->with('academicprogram',$academicprogram)
                    ->with('escmessage','No ESC Grantees enrolled!');
            }
        }else{
            
            if(count($students) == 0 && $id == 'preview'){
                
                return view("registrar.forms.studentmasterlistpreview")
                    ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                    ->with('esc',$esc)
                    ->with('extends',$extends)
                    ->with('sectiondetail',$getSection)
                    ->with('selectedsection',$getSection[0]->sectionname)
                    ->with('schoolyear',$syid)
                    ->with('sectionid',$sectionid)
                    ->with('levelid',$request->get('levelid'))
                    ->with('selectedform',$request->get('selectedform'))
                    ->with('academicprogram',$academicprogram)
                    ->with('message','No Students enrolled!');
            }
        }
        
        $maleCount = 0;
        $femaleCount = 0;
    // return $syid;
        foreach($students as $countGender){
            $countGender->student_lastname = ucwords(mb_strtolower($countGender->student_lastname,'UTF-8'));
            $countGender->student_firstname = ucwords(mb_strtolower($countGender->student_firstname,'UTF-8'));
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
            {
                $countGender->student_middlename = ucwords(mb_strtolower($countGender->student_middlename[0].'.','UTF-8'));
            }else{
                $countGender->student_middlename = ucwords(mb_strtolower($countGender->student_middlename,'UTF-8'));
            }
            $countGender->student_suffix = ucwords(mb_strtolower($countGender->student_suffix,'UTF-8'));
            if(strtoupper($countGender->student_gender) == "MALE"){
                $maleCount+=1;
            }
            elseif(strtoupper($countGender->student_gender) == "FEMALE"){
                $femaleCount+=1;
            }
        }
        $genderCount =['maleCount'=> $maleCount,'femaleCount'=>$femaleCount];
        $schoolinfo = DB::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->get();
            
        if($id == 'preview'){
            $students = $students->values();
            return view("registrar.forms.studentmasterlistpreview")
                ->with('sectiondetail',$getSection)
                ->with('schoolyeardesc',$schoolyear[0]->sydesc)
                ->with('selectedform',$request->get('selectedform'))
                ->with('selectedsection',$getSection[0]->sectionname)
                ->with('schoolyear',$syid)
                ->with('esc',$esc)
                ->with('extends',$extends)
                ->with('sectionid',$sectionid)
                ->with('levelid',$request->get('levelid'))
                ->with('academicprogram',$academicprogram)
                ->with('selectedsection',$getSection[0]->sectionname)
                ->with('data',$students);
        }
        elseif($id == 'print'){
            
            $data = $students;
            $middlename = "";
            
            if($teacher)
            {
                if($teacher->middlename == null)
                {
                    $middlename = "";
                }else{
                    $middlename = $teacher->middlename[0].'.';
                }
                $teacher = $teacher->title.' '.$teacher->firstname.' '.$middlename.' '.$teacher->lastname.' '.$teacher->suffix;
            }else{
                $teacher = null;
            }

            if($request->has('format'))
            {
                $format = $request->get('format');
            }else{
                $format = 'lastname_first';
            }
            $sectionid = $request->get('sectionid');
            $roomname = '';

            try{
                $roomname = DB::table('sectiondetail')
                    ->where('sectiondetail.sectionid', $request->get('sectionid'))
                    ->where('sectiondetail.syid', $syid)
                    ->where('sectiondetail.deleted', 0)
                    ->join('rooms','sectiondetail.sd_roomid','=','rooms.id')
                    ->first()->roomname;
            }catch(\Exception $error)
            {
            }
            if($request->get('exporttype') == 'pdf')
            {
                $levelid = $request->get('levelid');
                // $syid = $request->get('syid');
                if($request->get('sectionid') == '0')
                {
                    $docname = 'Student Masterlist.pdf';
                }else{
                    $docname = 'Student Masterlist - '.$data[0]->sectionname.'.pdf';
                }
                $pdf = PDF::loadview('registrar/pdf/pdf_studentmasterlist',compact('sectionid','roomname','data','schoolinfo','genderCount','schoolyear','esc','teacher','academicprogram','acadprogid','levelid','syid','format'));
                 return $pdf->stream($docname);
            }elseif($request->get('exporttype') == 'excel')
            {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $borderstyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                        ]
                    ]
                ];
                $font_bold = [
                        'font' => [
                            'bold' => true,
                        ]
                    ];
                    
                if($request->get('sectionid') == '0')
                {
                    $docname = 'Student Masterlist';
                }else{
                    $docname = 'Student Masterlist - '.$data[0]->sectionname;
                }

                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Student Masterlist');
                $startcellno = 3;
                    // if($subjectkey > 0)
                    // {
                    //     $spreadsheet->createSheet();
                    //     $spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma');
                    //     $spreadsheet->getDefaultStyle()->getFont()->setSize(8);
                    //     $sheet = $spreadsheet->getActiveSheet();
                    //     $sheet = $spreadsheet->getSheet($subjectkey);
                    //     $sheet->setTitle($subject->nstpcomponent.' '.$semester);
                    // }else{
                    //     $spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma');
                    //     $spreadsheet->getDefaultStyle()->getFont()->setSize(8);
                    //     $sheet = $spreadsheet->getActiveSheet();
                    //     $sheet = $spreadsheet->getSheet($subjectkey);
                    //     $sheet->setTitle($subject->nstpcomponent.' '.$semester);
                    // }
                $sheet->getColumnDimension('A')->setWidth(6);
                $sheet->getColumnDimension('B')->setWidth(6);
                $sheet->getColumnDimension('C')->setWidth(6);
                $sheet->getColumnDimension('D')->setWidth(6);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(10);

                $sheet->getColumnDimension('G')->setWidth(6);
                $sheet->getColumnDimension('H')->setWidth(6);
                $sheet->getColumnDimension('I')->setWidth(6);
                $sheet->getColumnDimension('J')->setWidth(6);
                $sheet->getColumnDimension('K')->setWidth(20);
                $sheet->getColumnDimension('L')->setWidth(10);
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setWorksheet($sheet);
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                if (strpos($schoolinfo[0]->picurl, '?') !== false)
                {
                    $drawing->setPath(base_path().'/public/'.substr($schoolinfo[0]->picurl, 0, strpos($schoolinfo[0]->picurl, "?")));
                }else{
                    $drawing->setPath(base_path().'/public/'.DB::table('schoolinfo')->first()->picurl);
                }
                $drawing->setHeight(80);
                $drawing->setCoordinates('D1');
                // $drawing->setOffsetX(20);
                $drawing->setOffsetY(15);
                    
                    $sheet->getStyle('G'.$startcellno)->getFont()->setBold(true);
                    $sheet->getStyle('G'.$startcellno)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('G'.$startcellno)->getFont()->setSize(14);
                    $sheet->setCellValue('G'.$startcellno,DB::table('schoolinfo')->first()->schoolname.'    ');
                    $startcellno+=1;
                    $sheet->getStyle('G'.$startcellno)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('G'.$startcellno)->getFont()->setSize(9);
                    $sheet->setCellValue('G'.$startcellno,DB::table('schoolinfo')->first()->address.'    ');
                    $startcellno+=4;                    
                    
                    $sheet->getStyle('D'.$startcellno)->getFont()->setBold(true);
                    $sheet->getStyle('D'.$startcellno)->getAlignment()->setHorizontal('left');
                    $sheet->setCellValue('D'.$startcellno,'Schoolyear:');
                    $sheet->setCellValue('F'.$startcellno,$schoolyear[0]->sydesc);
                    $sheet->getStyle('F'.$startcellno)->getAlignment()->setHorizontal('left');
                    $sheet->getStyle('F'.$startcellno)->getFont()->setUnderline(true);
                    $startcellno+=1;
                    $sheet->getStyle('D'.$startcellno)->getFont()->setBold(true);
                    $sheet->getStyle('D'.$startcellno)->getAlignment()->setHorizontal('left');
                    $sheet->setCellValue('D'.$startcellno,'Grade Level & Section:');
                    
                    if($sectionid > 0)
                    {
                        $sheet->setCellValue('F'.$startcellno,$data[0]->gradelevelname.' - '.$data[0]->sectionname);
                    }else{
                        $sheet->setCellValue('F'.$startcellno,$data[0]->gradelevelname.' - ALL SECTIONS');
                    }
                    $sheet->getStyle('F'.$startcellno)->getAlignment()->setHorizontal('left');
                    $sheet->getStyle('F'.$startcellno)->getFont()->setUnderline(true);
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')
                    {
                        $startcellno+=1;
                        $sheet->getStyle('D'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('D'.$startcellno)->getAlignment()->setHorizontal('left');
                        $sheet->setCellValue('D'.$startcellno,'Adviser:');
                        $sheet->setCellValue('F'.$startcellno,$teacher);
                        $sheet->getStyle('F'.$startcellno)->getAlignment()->setHorizontal('left');
                        $sheet->getStyle('F'.$startcellno)->getFont()->setUnderline(true);
                    }
                    $startcellno+=1;
                    $sheet->getStyle('D'.$startcellno)->getFont()->setBold(true);
                    $sheet->getStyle('D'.$startcellno)->getAlignment()->setHorizontal('left');
                    $sheet->setCellValue('D'.$startcellno,'Room:');
                    $sheet->setCellValue('F'.$startcellno,$roomname);
                    $sheet->getStyle('F'.$startcellno)->getAlignment()->setHorizontal('left');
                    $sheet->getStyle('F'.$startcellno)->getFont()->setUnderline(true);
                    
                    $startcellno+=2;
                    $sheet->getStyle('G'.$startcellno)->getFont()->setBold(true);
                    $sheet->getStyle('G'.$startcellno)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('G'.$startcellno)->getFont()->setSize(9);
                    $sheet->setCellValue('G'.$startcellno,'List of Students    ');
                    if($esc == 1)
                    {
                        $startcellno+=1;
                        $sheet->getStyle('G'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('G'.$startcellno)->getAlignment()->setHorizontal('center');
                        $sheet->getStyle('G'.$startcellno)->getFont()->setSize(13);
                        $sheet->setCellValue('G'.$startcellno,'(ESC Grantees)');
                    }
                    $startcellno+=1;
                    // return $acadprogid;
                    if($acadprogid == 5 || $acadprogid == 6)
                    {
                        $strands = collect($data)->groupBy('strandcode')->all();
                        foreach($strands as $eachkey => $eachstrand)
                        {
                            $startcellno+=1;
                            // $startcellno += 2;
                            $sheet->mergeCells('A'.$startcellno.':L'.$startcellno);
                            $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                            $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('center');
                            $sheet->getStyle('A'.$startcellno)->getFont()->setSize(14);
                            $sheet->setCellValue('A'.$startcellno,$eachkey);

                            $startcellno+=1;
                            $male = 0;
                            $female = 0;
                            // $startcellno+=1;
                            $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                            $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('center');
                            $sheet->setCellValue('A'.$startcellno,'No.');
                            $sheet->mergeCells('B'.$startcellno.':F'.$startcellno);
                            $sheet->getStyle('B'.$startcellno)->getFont()->setBold(true);
                            $sheet->getStyle('B'.$startcellno)->getAlignment()->setHorizontal('center');
                            $sheet->setCellValue('B'.$startcellno,'MALE');
                            foreach (collect($eachstrand)->where('gender','male')->values() as $keymale=>$student)
                            {
                                // $startcellno+=1;
                                $male+=1;
                                $sheet->setCellValue('A'.($startcellno+$male),($keymale+1));
                                $sheet->getStyle('A'.($startcellno+$male))->getAlignment()->setHorizontal('center');
                                $sheet->mergeCells('B'.($startcellno+$male).':F'.($startcellno+$male));
                                if($format == 'lastname_first')
                                {
                                    $sheet->setCellValue('B'.($startcellno+$male),ucwords(mb_strtolower($student->student_lastname,'UTF-8')).', '.ucwords(strtolower($student->student_firstname)).' '.(isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : '').' '.ucwords(strtolower($student->student_suffix)));
                                }
                                else
                                {
                                    $sheet->setCellValue('B'.($startcellno+$male),ucwords(strtolower($student->student_firstname)).' '.(isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : '').' '.ucwords(mb_strtolower($student->student_lastname,'UTF-8')).' '.ucwords(strtolower($student->student_suffix)));
                                }
                            }
                            $sheet->getStyle('G'.$startcellno)->getFont()->setBold(true);
                            $sheet->getStyle('G'.$startcellno)->getAlignment()->setHorizontal('center');
                            $sheet->setCellValue('G'.$startcellno,'No.');
                            $sheet->mergeCells('H'.$startcellno.':L'.$startcellno);
                            $sheet->getStyle('H'.$startcellno)->getFont()->setBold(true);
                            $sheet->getStyle('H'.$startcellno)->getAlignment()->setHorizontal('center');
                            $sheet->setCellValue('H'.$startcellno,'FEMALE');
                            foreach (collect($eachstrand)->where('gender','female')->values() as $keyfemale=>$student)
                            {
                                $female+=1;
                                $sheet->setCellValue('G'.($startcellno+$female),($keyfemale+1));
                                $sheet->getStyle('G'.($startcellno+$female))->getAlignment()->setHorizontal('center');
                                $sheet->mergeCells('H'.($startcellno+$female).':L'.($startcellno+$female));
                                if($format == 'lastname_first')
                                {
                                    $sheet->setCellValue('H'.($startcellno+$female),ucwords(mb_strtolower($student->student_lastname,'UTF-8')).', '.ucwords(strtolower($student->student_firstname)).' '.(isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : '').' '.ucwords(strtolower($student->student_suffix)));
                                }
                                else
                                {
                                    $sheet->setCellValue('H'.($startcellno+$female),ucwords(strtolower($student->student_firstname)).' '.(isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : '').' '.ucwords(mb_strtolower($student->student_lastname,'UTF-8')).' '.ucwords(strtolower($student->student_suffix)));
                                }
                            }
                            $maxnum = max(array(collect($eachstrand)->where('gender','male')->count(), collect($eachstrand)->where('gender','female')->count()));
                            $startcellno+=($maxnum+2);
                        }
                    }else{
                        $male = 0;
                        $female = 0;
                        $startcellno+=1;
                        $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('center');
                        $sheet->setCellValue('A'.$startcellno,'No.');
                        $sheet->mergeCells('B'.$startcellno.':F'.$startcellno);
                        $sheet->getStyle('B'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('B'.$startcellno)->getAlignment()->setHorizontal('center');
                        $sheet->setCellValue('B'.$startcellno,'MALE');
                        foreach (collect($data)->where('gender','male')->values() as $keymale=>$student)
                        {
                            // $startcellno+=1;
                            $male+=1;
                            $sheet->setCellValue('A'.($startcellno+$male),($keymale+1));
                            $sheet->getStyle('A'.($startcellno+$male))->getAlignment()->setHorizontal('center');
                            $sheet->mergeCells('B'.($startcellno+$male).':F'.($startcellno+$male));
                            if($format == 'lastname_first')
                            {
                                $sheet->setCellValue('B'.($startcellno+$male),ucwords(mb_strtolower($student->student_lastname,'UTF-8')).', '.ucwords(strtolower($student->student_firstname)).' '.(isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : '').' '.ucwords(strtolower($student->student_suffix)));
                            }
                            else
                            {
                                $sheet->setCellValue('B'.($startcellno+$male),ucwords(strtolower($student->student_firstname)).' '.(isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : '').' '.ucwords(mb_strtolower($student->student_lastname,'UTF-8')).' '.ucwords(strtolower($student->student_suffix)));
                            }
                        }
                        $sheet->getStyle('G'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('G'.$startcellno)->getAlignment()->setHorizontal('center');
                        $sheet->setCellValue('G'.$startcellno,'No.');
                        $sheet->mergeCells('H'.$startcellno.':L'.$startcellno);
                        $sheet->getStyle('H'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('H'.$startcellno)->getAlignment()->setHorizontal('center');
                        $sheet->setCellValue('H'.$startcellno,'FEMALE');
                        foreach (collect($data)->where('gender','female')->values() as $keyfemale=>$student)
                        {
                            $female+=1;
                            $sheet->setCellValue('G'.($startcellno+$female),($keyfemale+1));
                            $sheet->getStyle('G'.($startcellno+$female))->getAlignment()->setHorizontal('center');
                            $sheet->mergeCells('H'.($startcellno+$female).':L'.($startcellno+$female));
                            if($format == 'lastname_first')
                            {
                                $sheet->setCellValue('H'.($startcellno+$female),ucwords(mb_strtolower($student->student_lastname,'UTF-8')).', '.ucwords(strtolower($student->student_firstname)).' '.(isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : '').' '.ucwords(strtolower($student->student_suffix)));
                            }
                            else
                            {
                                $sheet->setCellValue('H'.($startcellno+$female),ucwords(strtolower($student->student_firstname)).' '.(isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : '').' '.ucwords(mb_strtolower($student->student_lastname,'UTF-8')).' '.ucwords(strtolower($student->student_suffix)));
                            }
                        }
                        $maxnum = max(array(collect($data)->where('gender','male')->count(), collect($data)->where('gender','female')->count()));
                        $startcellno+=($maxnum+2);
                    }
                    $startcellno+=2;
                    $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                    $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                    $sheet->setCellValue('A'.$startcellno,'Male');
                    $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                    $sheet->getStyle('C'.$startcellno)->getFont()->setBold(true);
                    $sheet->setCellValue('C'.$startcellno,collect($data)->where('gender','male')->count());
                    $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('center');
                    $startcellno+=1;
                    $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                    $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                    $sheet->setCellValue('A'.$startcellno,'Female');
                    $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                    $sheet->getStyle('C'.$startcellno)->getFont()->setBold(true);
                    $sheet->setCellValue('C'.$startcellno,collect($data)->where('gender','female')->count());
                    $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('center');
                    
                    $startcellno+=2;
                    $sheet->setCellValue('C'.$startcellno,'Certified and verified under oath to be true and correct:');
                    $startcellno+=3;

                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                    {
                        $sheet->setCellValue('A'.$startcellno,$teacher ?? '');
                        $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('left');
                        $sheet->setCellValue('G'.$startcellno,'CHRISTINE J. CASILAGAN');
                        $sheet->getStyle('G'.$startcellno)->getFont()->setBold(true);
                        $sheet->getStyle('G'.$startcellno)->getAlignment()->setHorizontal('left');
                        $startcellno+=1;
                        if($sectionid > 0)
                        {
                            $sheet->setCellValue('A'.$startcellno,'Adviser');
                            $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('left');
                        }
                        $sheet->setCellValue('G'.$startcellno,'School Registrar');
                        $sheet->getStyle('G'.$startcellno)->getAlignment()->setHorizontal('left');
                    }
                    // $sheet->getStyle('H'.$startcellno)->getAlignment()->setHorizontal('center');
                    // $startcellno += 1;
                    // $sheet->getStyle('H'.$startcellno)->getFont()->setBold(true);
                    // $sheet->setCellValue('H'.$startcellno,'Office of the President');
                    // $sheet->getStyle('H'.$startcellno)->getAlignment()->setHorizontal('center');
                    // $startcellno += 1;
                    // $sheet->getStyle('H'.$startcellno)->getFont()->setBold(true);
                    // $sheet->setCellValue('H'.$startcellno,'COMMISSION ON HIGHER EDUCATION');
                    // $sheet->getStyle('H'.$startcellno)->getAlignment()->setHorizontal('center');
                    // $startcellno += 2;
                    // $sheet->setCellValue('H'.$startcellno,'LIST OF NSTP GRADUATES FOR SERIAL NUMBER');
                    // $sheet->getStyle('H'.$startcellno)->getAlignment()->setHorizontal('center');
                    // $startcellno += 1;
                    // $sheet->setCellValue('F'.$startcellno,$request->get('semid'));
                    // $sheet->getStyle('F'.$startcellno)->getAlignment()->setHorizontal('center');
                    // $sheet->setCellValue('G'.$startcellno,'Semester');
                    // $sheet->setCellValue('H'.$startcellno,'Academic Year:');
                    // $sheet->setCellValue('I'.$startcellno,$sydesc);
                    // $sheet->getStyle('I'.$startcellno)->getAlignment()->setHorizontal('left');
                    
                    // $startcellno += 2;
                    // $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                    // $sheet->setCellValue('A'.$startcellno,'Name of HEI:');
                    // $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('left');
                    
                    // $sheet->setCellValue('C'.$startcellno,DB::table('schoolinfo')->first()->schoolname);
                    // $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('left');
                    // $sheet->getStyle('C'.$startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    // $sheet->getStyle('D'.$startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    // $sheet->getStyle('E'.$startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    
                    // $sheet->getStyle('H'.$startcellno)->getFont()->setBold(true);
                    // $sheet->setCellValue('H'.$startcellno,'Region:');
                    // $sheet->getStyle('H'.$startcellno)->getAlignment()->setHorizontal('center');
                    // $sheet->setCellValue('I'.$startcellno,DB::table('schoolinfo')->first()->regiontext ?? $schoolinfo->regDesc);
                    // $sheet->getStyle('I'.$startcellno)->getFont()->setUnderline(true);
                    // $sheet->getStyle('I'.$startcellno)->getAlignment()->setHorizontal('left');
                    
                    // $startcellno += 1;
                    // $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                    // $sheet->setCellValue('A'.$startcellno,'Address:');
                    // $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('left');
                    // $sheet->setCellValue('C'.$startcellno,DB::table('schoolinfo')->first()->address);
                    // $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('left');
                    // $sheet->getStyle('C'.$startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    // $sheet->getStyle('D'.$startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    // $sheet->getStyle('E'.$startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    
                    // $sheet->getStyle('H'.$startcellno)->getFont()->setBold(true);
                    // $sheet->setCellValue('H'.$startcellno,'NSTP Component:');
                    // $sheet->getStyle('H'.$startcellno)->getAlignment()->setHorizontal('center');
                    // $sheet->setCellValue('I'.$startcellno,$subject->nstpcomponent);
                    // $sheet->getStyle('I'.$startcellno)->getFont()->setUnderline(true);
                    // $sheet->getStyle('I'.$startcellno)->getAlignment()->setHorizontal('left');


                    
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="'.$docname.'.xlsx"');
                $writer->save("php://output");

                exit;
            }
            elseif($request->get('exporttype') == 'exportexcelinfo')
            {
                if($request->get('acadprogid') == 6)
                {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path().'/public/excelformats/masterlist_hccsi.xls');
                    if($format == 'firstname_first')
                    {
                        for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
                        }
                    }
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->setTitle('Student Masterlist');
                    $border    = [
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

                    
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
                    {
                        $sheet->setCellValue('I6', 'Birthday')
                        ->setCellValue('J6', 'Address')
                        ->setCellValue('K6', 'Contact Number')
                        ->setCellValue('L6', 'Contact Person')
                        ->setCellValue('M6', 'Relation');
                    }
                    $sheet->setCellValue('A1', DB::table('schoolinfo')->first()->schoolname);
                    $sheet->setCellValue('A3', 'Date Generated: '.date('M d, Y h:i A'));
                    if($request->get('courseid') == null || $request->get('courseid') == 0)
                    {
                        $sheet->setCellValue('A4', 'All Courses');
                    }else{
                        $sheet->setCellValue('A4', DB::table('college_courses')->where('id', $request->get('courseid'))->first()->courseDesc);
                    }
                    if(count($data)>0)
                    {
                        for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
                        }
                        $count = 7;
                        foreach($data as $key=>$studentinfo)
                        {
                            // return collect($studentinfo);
                            $sheet->getStyle('A'.$count)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                            $sheet->getStyle('B'.$count)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                            if($format == 'lastname_first')
                            {
                                $sheet
                                    ->setCellValue('A'.$count, ($key+1))
                                    ->setCellValue('B'.$count, $studentinfo->student_idnumber)
                                    ->setCellValue('C'.$count, $studentinfo->student_lastname)
                                    ->setCellValue('D'.$count, $studentinfo->student_firstname)
                                    ->setCellValue('E'.$count, $studentinfo->student_middlename)
                                    ->setCellValue('F'.$count, $studentinfo->student_gender[0])
                                    ->setCellValue('G'.$count, $studentinfo->strandcode ?? '')
                                    ->setCellValue('H'.$count, isset($studentinfo->yearDesc) ? filter_var($studentinfo->yearDesc, FILTER_SANITIZE_NUMBER_INT) : '');
                                    
                            }else{
                                $sheet
                                    ->setCellValue('A'.$count, ($key+1))
                                    ->setCellValue('B'.$count, $studentinfo->student_idnumber)
                                    ->setCellValue('C'.$count, $studentinfo->student_firstname)
                                    ->setCellValue('D'.$count, $studentinfo->student_middlename)
                                    ->setCellValue('E'.$count, $studentinfo->student_lastname)
                                    ->setCellValue('F'.$count, $studentinfo->student_gender[0])
                                    ->setCellValue('G'.$count, $studentinfo->strandcode ?? '')
                                    ->setCellValue('H'.$count, isset($studentinfo->yearDesc) ? filter_var($studentinfo->yearDesc, FILTER_SANITIZE_NUMBER_INT) : '');
                            }
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
                            {
                                $sheet->setCellValue('I'.$count, $studentinfo->dob != null ? date('F d, Y', strtotime($studentinfo->dob)) : ' ');
                                $sheet->setCellValue('J'.$count, ucwords(mb_strtolower($studentinfo->street.', '.$studentinfo->barangay.', '.$studentinfo->city.', '.$studentinfo->province,'UTF-8')));
                                
                                $sheet->setCellValue('K'.$count, $studentinfo->ismothernum == 1 ?  $studentinfo->mcontactno : ($studentinfo->isfathernum == 1 ? $studentinfo->fcontactno : ($studentinfo->isguardannum == 1 ? $studentinfo->gcontactno : $studentinfo->studentcontactno)));
                                $sheet->setCellValue('L'.$count, $studentinfo->ismothernum == 1 ?  ucwords(mb_strtolower($studentinfo->mothername,'UTF-8')) : ($studentinfo->isfathernum == 1 ? ucwords(mb_strtolower($studentinfo->fathername,'UTF-8')) : ($studentinfo->isguardannum == 1 ? ucwords(mb_strtolower($studentinfo->guardianname,'UTF-8')) : '')));
                                $sheet->setCellValue('M'.$count, $studentinfo->ismothernum == 1 ? 'Mother' : ($studentinfo->isfathernum == 1 ? 'Father' : ($studentinfo->isguardannum == 1 ? 'Guardian' : '')));
                            }
    
                            $count+=1;
                        }
                    }
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="Student Masterlist.xls"');
                    $writer->save("php://output");

                }else{
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    if($format == 'firstname_first')
                    {
                        for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
                        }
                    }
                    $sheet = $spreadsheet->getActiveSheet();
                    foreach(range('A','Z') as $columnID) {
                        $sheet->getColumnDimension($columnID)
                            ->setAutoSize(true);
                    }
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
                    {
                        $sheet
                            ->setCellValue('A1', 'ID No.')
                            ->setCellValue('B1', 'LRN')
                            ->setCellValue('C1', 'Student Name')
                            ->setCellValue('D1', 'Gender')
                            ->setCellValue('E1', 'Grade Level')
                            ->setCellValue('F1', 'Section')
                            ->setCellValue('G1', 'Grantee')
                            ->setCellValue('H1', 'Birthday')
                            ->setCellValue('I1', 'Address')
                            ->setCellValue('J1', 'Contact Number')
                            ->setCellValue('K1', 'Contact Person')
                            ->setCellValue('L1', 'Relation');
                    }else{
                        $sheet
                            ->setCellValue('A1', 'ID No.')
                            ->setCellValue('B1', 'LRN')
                            ->setCellValue('C1', 'Student Name')
                            ->setCellValue('D1', 'Gender')
                            ->setCellValue('E1', 'Grade Level')
                            ->setCellValue('F1', 'Section')
                            ->setCellValue('G1', 'Grantee')
                            ->setCellValue('H1', 'Birthday')
                            ->setCellValue('I1', 'Address')
                            ->setCellValue('J1', 'Contact Number')
                            ->setCellValue('K1', 'Mother\'s Name')
                            ->setCellValue('L1', 'Contact Number')
                            ->setCellValue('M1', 'Occupation')
                            ->setCellValue('N1', 'Father\'s name')
                            ->setCellValue('O1', 'Contact Number')
                            ->setCellValue('P1', 'Occupation')
                            ->setCellValue('Q1', 'Email Address');
                    }
    
                    if(count($data)>0)
                    {
                        $count = 2;
                        foreach($data as $studentinfo)
                        {
                            $sheet->getStyle('A'.$count)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
    
                            $sheet
                                ->setCellValue('A'.$count, $studentinfo->student_idnumber)
                                ->setCellValue('B'.$count, ' '.$studentinfo->student_lrn.' ');
                                
                            if($format == 'lastname_first')
                            {
                                $sheet->setCellValue('C'.$count, $studentinfo->student_lastname.', '.$studentinfo->student_firstname.' '.$studentinfo->student_middlename.' '.$studentinfo->student_suffix);
                            }else{
                                $sheet->setCellValue('C'.$count, $studentinfo->student_firstname.' '.$studentinfo->student_middlename.' '.$studentinfo->student_lastname.' '.$studentinfo->student_suffix);
                            }
                            $sheet
                                ->setCellValue('D'.$count, $studentinfo->student_gender)
                                ->setCellValue('E'.$count, ucwords(mb_strtolower($studentinfo->gradelevelname,'UTF-8')))
                                ->setCellValue('F'.$count, ucwords(mb_strtolower($studentinfo->sectionname,'UTF-8')))
                                ->setCellValue('G'.$count, $studentinfo->grantee)
                                ->setCellValue('H'.$count, $studentinfo->dob != null ? date('F d, Y', strtotime($studentinfo->dob)) : ' ')
                                ->setCellValue('I'.$count, ucwords(mb_strtolower($studentinfo->street.', '.$studentinfo->barangay.', '.$studentinfo->city.', '.$studentinfo->province,'UTF-8')));
                                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
                                {
                                    $sheet->setCellValue('J'.$count, $studentinfo->ismothernum == 1 ?  $studentinfo->mcontactno : ($studentinfo->isfathernum == 1 ? $studentinfo->fcontactno : ($studentinfo->isguardannum == 1 ? $studentinfo->gcontactno : $studentinfo->studentcontactno)));
                                    $sheet->setCellValue('K'.$count, $studentinfo->ismothernum == 1 ?  ucwords(mb_strtolower($studentinfo->mothername,'UTF-8')) : ($studentinfo->isfathernum == 1 ? ucwords(mb_strtolower($studentinfo->fathername,'UTF-8')) : ($studentinfo->isguardannum == 1 ? ucwords(mb_strtolower($studentinfo->guardianname,'UTF-8')) : '')));
                                    $sheet->setCellValue('L'.$count, $studentinfo->ismothernum == 1 ? 'Mother' : ($studentinfo->isfathernum == 1 ? 'Father' : ($studentinfo->isguardannum == 1 ? 'Guardian' : '')));
                                }else{
                                    $sheet->setCellValue('J'.$count, $studentinfo->studentcontactno)
                                    ->setCellValue('K'.$count, $studentinfo->mothername)
                                    ->setCellValue('L'.$count, $studentinfo->mcontactno)
                                    ->setCellValue('M'.$count, $studentinfo->moccupation)
                                    ->setCellValue('N'.$count, $studentinfo->fathername)
                                    ->setCellValue('O'.$count, $studentinfo->fcontactno)
                                    ->setCellValue('P'.$count, $studentinfo->foccupation)
                                    ->setCellValue('Q'.$count, $studentinfo->semail);
                                }
    
                            $count+=1;
                        }
                    }
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="Student Masterlist - '.$data[0]->sectionname.'.xlsx"');
                    $writer->save("php://output");
                }
                
            }
            elseif($request->get('exporttype') == 'excellist')
            {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path().'/public/excelformats/studentmasterlist.xlsx');
                if($format == 'firstname_first')
                {
                    for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
                        $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
                    }
                }
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', $schoolinfo[0]->schoolname);
                $sheet->setCellValue('A2', $schoolinfo[0]->address);
                
                if(count($data)>0)
                {
                    $sheet->setCellValue('A4', $data[0]->gradelevelname.' '.$data[0]->sectionname);
                }
                $sheet->setCellValue('A6', $schoolyear[0]->sydesc);

                $totalcells = 10;
                $malecellcount = 10;
                $femalecellcount = 10;
                if(count(collect($data)->where('student_gender','MALE')) > count(collect($data)->where('student_gender','FEMALE')))
                {
                    $malecount = count(collect($data)->where('student_gender','MALE'));
                    $femalecount = 1;
                    
                    $malestudents = collect($data)->where('student_gender','MALE')->sortByDesc('studentname')->values();
                    foreach($malestudents as $studentinfo)
                    {
                        if($studentinfo->student_middlename == null)
                        {
                            $studentinfo->student_middlename = "";
                        }else{
                            $studentinfo->student_middlename = $studentinfo->student_middlename[0].'.';
                        }
                        $sheet->insertNewRowBefore($malecellcount, 1);
                        $totalcells += 1;
                        if($format == 'lastname_first')
                        {
                            $sheet
                                ->setCellValue('A'.$malecellcount, $malecount)
                                ->setCellValue('B'.$malecellcount, $studentinfo->student_lastname)
                                ->setCellValue('C'.$malecellcount, ',')
                                ->setCellValue('D'.$malecellcount, $studentinfo->student_firstname)
                                ->setCellValue('E'.$malecellcount, $studentinfo->student_middlename);
                        }else{
                            $sheet
                                ->setCellValue('A'.$malecellcount, $malecount)
                                ->setCellValue('B'.$malecellcount, $studentinfo->student_firstname)
                                // ->setCellValue('C'.$malecellcount, '')
                                ->setCellValue('D'.$malecellcount, $studentinfo->student_middlename)
                                ->setCellValue('E'.$malecellcount, $studentinfo->student_lastname);
                        }
    
                        $malecount-=1;
                    }
                    $femalestudents = collect($data)->where('student_gender','FEMALE')->sortByDesc('studentname')->values();
                    foreach($femalestudents as $studentinfo)
                    {
                        if($studentinfo->student_middlename == null)
                        {
                            $studentinfo->student_middlename = "";
                        }else{
                            $studentinfo->student_middlename = $studentinfo->student_middlename[0].'.';
                        }
                        if($format == 'lastname_first')
                        {
                            $sheet
                                ->setCellValue('H'.$femalecellcount, $femalecount)
                                ->setCellValue('I'.$femalecellcount, $studentinfo->student_lastname)
                                ->setCellValue('J'.$femalecellcount, ',')
                                ->setCellValue('K'.$femalecellcount, $studentinfo->student_firstname)
                                ->setCellValue('L'.$femalecellcount, $studentinfo->student_middlename);
                        }else{
                            $sheet
                                ->setCellValue('H'.$femalecellcount, $femalecount)
                                ->setCellValue('I'.$femalecellcount, $studentinfo->student_firstname)
                                // ->setCellValue('J'.$femalecellcount, ',')
                                ->setCellValue('K'.$femalecellcount, $studentinfo->student_middlename)
                                ->setCellValue('L'.$femalecellcount, $studentinfo->student_lastname);
                        }
                            // ->setCellValue('C'.$count, $studentinfo->student_idnumber)
                            // ->setCellValue('D'.$count, $studentinfo->student_gender)
                            // ->setCellValue('E'.$count, $studentinfo->dob)
                            // ->setCellValue('F'.$count, $studentinfo->street.', '.$studentinfo->barangay.', '.$studentinfo->city.', '.$studentinfo->province)
                            // ->setCellValue('G'.$count, $studentinfo->mothername)
                            // ->setCellValue('H'.$count, $studentinfo->fathername)
                            // ->setCellValue('I'.$count, $studentinfo->contactno);
    
                        $femalecount+=1;
                        $femalecellcount+=1;
                    }
                }else{
                    
                    $femalecount = count(collect($data)->where('student_gender','FEMALE'));
                    $malecount = 1;
                    $femalestudents = collect($data)->where('student_gender','FEMALE')->sortByDesc('studentname')->values();
                    // return $femalecount;
                    foreach($femalestudents as $studentinfo)
                    {
                        if($studentinfo->student_middlename == null)
                        {
                            $studentinfo->student_middlename = "";
                        }else{
                            $studentinfo->student_middlename = $studentinfo->student_middlename[0].'.';
                        }
                            
                        $sheet->insertNewRowBefore($femalecellcount, 1);
                        $totalcells += 1;
                        if($format == 'lastname_first')
                        {
                            $sheet
                                ->setCellValue('H'.$femalecellcount, $femalecount)
                                ->setCellValue('I'.$femalecellcount, $studentinfo->student_lastname)
                                ->setCellValue('J'.$femalecellcount, ',')
                                ->setCellValue('K'.$femalecellcount, $studentinfo->student_firstname)
                                ->setCellValue('L'.$femalecellcount, $studentinfo->student_middlename);
                        }else{
                            $sheet
                                ->setCellValue('H'.$femalecellcount, $femalecount)
                                ->setCellValue('I'.$femalecellcount, $studentinfo->student_firstname)
                                // ->setCellValue('J'.$femalecellcount, ',')
                                ->setCellValue('K'.$femalecellcount, $studentinfo->student_middlename)
                                ->setCellValue('L'.$femalecellcount, $studentinfo->student_lastname);
                        }
                            // ->setCellValue('C'.$count, $studentinfo->student_idnumber)
                            // ->setCellValue('D'.$count, $studentinfo->student_gender)
                            // ->setCellValue('E'.$count, $studentinfo->dob)
                            // ->setCellValue('F'.$count, $studentinfo->street.', '.$studentinfo->barangay.', '.$studentinfo->city.', '.$studentinfo->province)
                            // ->setCellValue('G'.$count, $studentinfo->mothername)
                            // ->setCellValue('H'.$count, $studentinfo->fathername)
                            // ->setCellValue('I'.$count, $studentinfo->contactno);

                        $femalecount-=1;
                    }
                    
                    $malestudents = collect($data)->where('student_gender','MALE')->sortByDesc('studentname')->values();
                    foreach($malestudents as $studentinfo)
                    {
                        if($studentinfo->student_middlename == null)
                        {
                            $studentinfo->student_middlename = "";
                        }else{
                            $studentinfo->student_middlename = $studentinfo->student_middlename[0].'.';
                        }
                        if($format == 'lastname_first')
                        {
                            $sheet
                                ->setCellValue('A'.$malecellcount, $malecount)
                                ->setCellValue('B'.$malecellcount, $studentinfo->student_lastname)
                                ->setCellValue('C'.$malecellcount, ',')
                                ->setCellValue('D'.$malecellcount, $studentinfo->student_firstname)
                                ->setCellValue('E'.$malecellcount, $studentinfo->student_middlename);
                        }else{
                            $sheet
                                ->setCellValue('A'.$malecellcount, $malecount)
                                ->setCellValue('B'.$malecellcount, $studentinfo->student_firstname)
                                // ->setCellValue('C'.$malecellcount, ',')
                                ->setCellValue('D'.$malecellcount, $studentinfo->student_middlename)
                                ->setCellValue('E'.$malecellcount, $studentinfo->student_lastname);
                        }
    
                        $malecount+=1;
                        $malecellcount+=1;
                    }
                }
                
                $totalcells+=1;
                $sheet
                ->setCellValue('D'.$totalcells, count(collect($data)->where('student_gender','MALE')));
                $sheet
                ->setCellValue('I'.$totalcells, $teacher);
                $totalcells+=1;
                $sheet
                ->setCellValue('D'.$totalcells, count(collect($data)->where('student_gender','FEMALE')));
                $totalcells+=1;
                $sheet
                ->setCellValue('D'.$totalcells, count($data));
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="Student Masterlist - '.$data[0]->sectionname.'.xlsx"');
                $writer->save("php://output");
            }
        }
    }
    public function exportsf1(Request $request)
    {
        // return $request->all();
        // $studinfo = DB::table('studinfo')
        //     ->where('deleted','0')
        //     ->
        $acadprogcode = DB::table('gradelevel')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id', $request->get('levelid'))
            ->first()
            ->acadprogcode;
        
        if(strtolower($acadprogcode) == 'shs')
        {
            $students = DB::table('sh_enrolledstud')
                ->select('studinfo.*')
                ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                ->where('sh_enrolledstud.syid', $request->get('schoolyear'))
                ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                ->where('sh_enrolledstud.deleted', 0)
                ->where('studinfo.deleted', 0)
                ->where('sh_enrolledstud.studstatus', '!=',0)
                ->orderBy('lastname','asc')
                ->get();

        }else{
            $students = DB::table('enrolledstud')
                ->select('studinfo.*')
                ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                ->where('enrolledstud.syid', $request->get('schoolyear'))
                ->where('enrolledstud.levelid', $request->get('levelid'))
                ->where('enrolledstud.sectionid', $request->get('sectionid')) 
                ->where('enrolledstud.deleted', 0)
                ->where('studinfo.deleted', 0)
                ->where('enrolledstud.studstatus', '!=',0)
                ->orderBy('lastname','asc')
                ->get();
        }
        
        
        if(count($students) > 0){

            foreach($students as $student){

                $student->dob = date('m/d/Y', strtotime($student->dob));

                $today = date("Y-m-d");
                try{
                    $diff = date_diff(date_create($student->dob), date_create($today));
    
                    $student->age = $diff->format('%y');
    
                    $firstcomparison = ['01','02','03','04','05'];
    
                    if(in_array(date('m', strtotime($student->dob)),$firstcomparison)){
    
                        $student->age = ((int)$student->age - 1);
    
                    }
                }catch(\Exception $error)
                {
                    $student->age = null;
                }

            }

        }


        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'schoolinfo.picurl',
                'refcitymun.citymunDesc',
                'schoolinfo.district',
                'schoolinfo.address',
                'refregion.regDesc'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();

        
        $sydesc = Db::table('sy')
            ->where('id',$request->get('schoolyear'))
            ->first()
            ->sydesc;

        $sectionname = Db::table('sections')
            ->where('id', $request->get('sectionid'))
            ->first()
            ->sectionname;

        $levelname = Db::table('gradelevel')
            ->where('id', $request->get('levelid'))
            ->first()
            ->levelname;

        $preparedby = Db::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first();

        if($request->get('exporttype') == 'pdf')
        {
            $forms = array();
            array_push($forms, (object)array(
                'schoolinfo'        => $schoolinfo,
                'schoolyear'        => $sydesc,
                'gradelevel'        => $levelname,
                'section'           => $sectionname,
                'preparedby'        => $preparedby,
                'students'          => $students
            ));
    
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs cp' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
            {
                $pdf = PDF::loadview('teacher/pdf/pdf_form1_withlmod',compact('forms'))->setPaper('legal','landscape'); ;
        
                return $pdf->stream('School Form 1.pdf');
            }else{
                
            $pdf = PDF::loadview('registrar/pdf/pdf_schoolform1',compact('forms'))->setPaper('legal','landscape');
    
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream('School Form 1');
            }
        }else{
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path().'/public/excelformats/schoolform1.xlsx');
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
            
            $sheet->setCellValue('F4',$schoolinfo->schoolid);
            $sheet->setCellValue('H4',$schoolinfo->regDesc);
            $sheet->setCellValue('N4',$schoolinfo->citymunDesc);
            $sheet->setCellValue('U4',$schoolinfo->district);
            $sheet->setCellValue('F6',$schoolinfo->schoolname);
            $sheet->setCellValue('P6',$sydesc);
            $sheet->setCellValue('U6',$levelname);
            $sheet->setCellValue('X6',$sectionname);

    // ================================  TABLE HEADER     
            $malecount = 1;
            $femalecount = 1;
            $cellcount = 11;
            
            if(count($students)>0)
            {
                // ================================  MALE STUDENTS     
                $malestudents = collect($students->where('gender','MALE'));
                foreach($malestudents as $male)
                {
                    $sheet->insertNewRowBefore($cellcount, 1);
                    $contactno = null;
                    if($male->ismothernum == 1)
                    {
                        $contactno = $male->mcontactno;
                    }
                    if($male->isfathernum == 1)
                    {
                        $contactno = $male->fcontactno;
                    }
                    if($male->isguardannum == 1)
                    {
                        $contactno = $male->gcontactno;
                    }
                    $sheet->setCellValue('A'.$cellcount, $malecount);
                    $sheet->setCellValue('B'.$cellcount, $male->lrn.' ');
                    $sheet->mergeCells('C'.$cellcount.':F'.$cellcount);
                    $sheet->setCellValue('C'.$cellcount, $male->lastname.', '.$male->firstname.' '.$male->middlename.' '.$male->suffix);
                    $sheet->setCellValue('G'.$cellcount, $male->gender[0]);
                    $sheet->setCellValue('H'.$cellcount, $male->dob);
                    $sheet->setCellValue('I'.$cellcount, $male->age);
                    $sheet->setCellValue('L'.$cellcount, $male->mtname);
                    $sheet->setCellValue('M'.$cellcount, $male->egname);
                    $sheet->setCellValue('N'.$cellcount, $male->religionname);
                    $sheet->setCellValue('O'.$cellcount, $male->street);
                    $sheet->setCellValue('P'.$cellcount, $male->barangay);
                    $sheet->setCellValue('Q'.$cellcount, $male->city);
                    $sheet->mergeCells('R'.$cellcount.':S'.$cellcount);
                    $sheet->setCellValue('S'.$cellcount, $male->province);
                    $sheet->mergeCells('T'.$cellcount.':U'.$cellcount);
                    $sheet->setCellValue('T'.$cellcount, strtoupper($male->fathername));
                    $sheet->mergeCells('V'.$cellcount.':W'.$cellcount);
                    $sheet->setCellValue('V'.$cellcount, strtoupper($male->mothername));
                    $sheet->setCellValue('X'.$cellcount, $male->guardianname);
                    $sheet->setCellValue('Y'.$cellcount, $male->guardianrelation);
                    $sheet->setCellValue('Z'.$cellcount, $contactno);
                    $sheet->getStyle('A'.$cellcount.':AA'.$cellcount)->applyFromArray($borderstyle);
                    $malecount+=1;
                    $cellcount+=1;
                }

                $sheet->insertNewRowBefore($cellcount, 1);
                $sheet->setCellValue('A'.$cellcount, '');
                $sheet->setCellValue('B'.$cellcount, ($malecount-1));
                $sheet->mergeCells('C'.$cellcount.':F'.$cellcount);
                $sheet->setCellValue('C'.$cellcount, ' == TOTAL MALE');
                $sheet->setCellValue('G'.$cellcount, '');
                $sheet->setCellValue('H'.$cellcount, '');
                $sheet->setCellValue('I'.$cellcount, '');
                $sheet->setCellValue('L'.$cellcount, '');
                $sheet->setCellValue('M'.$cellcount, '');
                $sheet->setCellValue('N'.$cellcount, '');
                $sheet->setCellValue('O'.$cellcount, '');
                $sheet->setCellValue('P'.$cellcount, '');
                $sheet->setCellValue('Q'.$cellcount, '');
                $sheet->mergeCells('R'.$cellcount.':S'.$cellcount);
                $sheet->setCellValue('S'.$cellcount, '');
                $sheet->mergeCells('T'.$cellcount.':U'.$cellcount);
                $sheet->setCellValue('T'.$cellcount, '');
                $sheet->mergeCells('V'.$cellcount.':W'.$cellcount);
                $sheet->setCellValue('V'.$cellcount, '');
                $sheet->setCellValue('X'.$cellcount, '');
                $sheet->setCellValue('Y'.$cellcount, '');
                $sheet->getStyle('A'.$cellcount.':AA'.$cellcount)->applyFromArray($borderstyle);
                $sheet->getStyle('A'.$cellcount.':AA'.$cellcount)->applyFromArray($borderstyle);
                $cellcount+=1;
                
    // ================================  FEMALE STUDENTS     
                $femalestudents = collect($students->where('gender','FEMALE'));
                foreach($femalestudents as $female)
                {
                    $contactno = null;
                    $sheet->insertNewRowBefore($cellcount, 1);
                    if($female->ismothernum == 1)
                    {
                        $contactno = $female->mcontactno;
                    }
                    if($female->isfathernum == 1)
                    {
                        $contactno = $female->fcontactno;
                    }
                    if($female->isguardannum == 1)
                    {
                        $contactno = $female->gcontactno;
                    }
                    $sheet->setCellValue('A'.$cellcount, $femalecount);
                    $sheet->setCellValue('B'.$cellcount, $female->lrn.' ');
                    $sheet->mergeCells('C'.$cellcount.':F'.$cellcount);
                    $sheet->setCellValue('C'.$cellcount, $female->lastname.', '.$female->firstname.' '.$female->middlename.' '.$female->suffix);
                    $sheet->setCellValue('G'.$cellcount, $female->gender[0]);
                    $sheet->setCellValue('H'.$cellcount, $female->dob);
                    $sheet->setCellValue('I'.$cellcount, $female->age);
                    $sheet->setCellValue('L'.$cellcount, $female->mtname);
                    $sheet->setCellValue('M'.$cellcount, $female->egname);
                    $sheet->setCellValue('N'.$cellcount, $female->religionname);
                    $sheet->setCellValue('O'.$cellcount, $female->street);
                    $sheet->setCellValue('P'.$cellcount, $female->barangay);
                    $sheet->setCellValue('Q'.$cellcount, $female->city);
                    $sheet->mergeCells('R'.$cellcount.':S'.$cellcount);
                    $sheet->setCellValue('S'.$cellcount, $female->province);
                    $sheet->mergeCells('T'.$cellcount.':U'.$cellcount);
                    $sheet->setCellValue('T'.$cellcount, strtoupper($female->fathername));
                    $sheet->mergeCells('V'.$cellcount.':W'.$cellcount);
                    $sheet->setCellValue('V'.$cellcount, strtoupper($female->mothername));
                    $sheet->setCellValue('X'.$cellcount, $female->guardianname);
                    $sheet->setCellValue('Y'.$cellcount, $female->guardianrelation);
                    $sheet->setCellValue('Z'.$cellcount, $contactno);
                    
                    $sheet->getStyle('A'.$cellcount.':AA'.$cellcount)->applyFromArray($borderstyle);
                    $femalecount+=1;
                    $cellcount+=1;
                }
                $sheet->insertNewRowBefore($cellcount, 1);
                $sheet->setCellValue('A'.$cellcount, '');
                $sheet->setCellValue('B'.$cellcount, ($femalecount-1));
                $sheet->mergeCells('C'.$cellcount.':F'.$cellcount);
                $sheet->setCellValue('C'.$cellcount, ' == TOTAL FEMALE');
                $sheet->setCellValue('G'.$cellcount, '');
                $sheet->setCellValue('H'.$cellcount, '');
                $sheet->setCellValue('I'.$cellcount, '');
                $sheet->setCellValue('L'.$cellcount, '');
                $sheet->setCellValue('M'.$cellcount, '');
                $sheet->setCellValue('N'.$cellcount, '');
                $sheet->setCellValue('O'.$cellcount, '');
                $sheet->setCellValue('P'.$cellcount, '');
                $sheet->setCellValue('Q'.$cellcount, '');
                $sheet->mergeCells('R'.$cellcount.':S'.$cellcount);
                $sheet->setCellValue('S'.$cellcount, '');
                $sheet->mergeCells('T'.$cellcount.':U'.$cellcount);
                $sheet->setCellValue('T'.$cellcount, '');
                $sheet->mergeCells('V'.$cellcount.':W'.$cellcount);
                $sheet->setCellValue('V'.$cellcount, '');
                $sheet->setCellValue('X'.$cellcount, '');
                $sheet->setCellValue('Y'.$cellcount, '');
                $sheet->getStyle('A'.$cellcount.':AA'.$cellcount)->applyFromArray($borderstyle);
                $cellcount+=1;
            }
            $sheet->insertNewRowBefore($cellcount, 1);
            $sheet->setCellValue('A'.$cellcount, '');
            $sheet->setCellValue('B'.$cellcount, (($femalecount-1) + ($malecount-1)));
            $sheet->mergeCells('C'.$cellcount.':F'.$cellcount);
            $sheet->setCellValue('C'.$cellcount, ' == COMBINED');
            $sheet->setCellValue('G'.$cellcount, '');
            $sheet->setCellValue('H'.$cellcount, '');
            $sheet->setCellValue('I'.$cellcount, '');
            $sheet->setCellValue('L'.$cellcount, '');
            $sheet->setCellValue('M'.$cellcount, '');
            $sheet->setCellValue('N'.$cellcount, '');
            $sheet->setCellValue('O'.$cellcount, '');
            $sheet->setCellValue('P'.$cellcount, '');
            $sheet->setCellValue('Q'.$cellcount, '');
            $sheet->mergeCells('R'.$cellcount.':S'.$cellcount);
            $sheet->setCellValue('S'.$cellcount, '');
            $sheet->mergeCells('T'.$cellcount.':U'.$cellcount);
            $sheet->setCellValue('T'.$cellcount, '');
            $sheet->mergeCells('V'.$cellcount.':W'.$cellcount);
            $sheet->setCellValue('V'.$cellcount, '');
            $sheet->setCellValue('X'.$cellcount, '');
            $sheet->setCellValue('Y'.$cellcount, '');
            $sheet->getStyle('A'.$cellcount.':AA'.$cellcount)->applyFromArray($borderstyle);
            // $cellcount+=1;

            
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="School Form 1 '.$levelname.' - '.$sectionname.' ('.$sydesc.').xlsx"');
			$writer->save("php://output");
        }
        
    }
    function reportschoolform_4(Request $request, $id)
    {
        
        date_default_timezone_set('Asia/Manila');
        // return $request->all();
        if($id == 'dashboard')
        {
            $schoolyears = DB::table('sy')
                ->orderBy('sydesc')
                ->get();

            return view("registrar.forms.form4.index")
                ->with('schoolyears',$schoolyears);

        }elseif($id == 'calendar')
        {
            
            $list=array();
            $today = date("d"); // Current day
            $month = $request->get('selectedmonth');
            $year =  $request->get('selectedyear');
            function draw_calendar($month,$year){

                /* draw table */
                $calendar = '<table class="table-bordered" style="width: 100%;">';
            
                /* table headings */
                $headings = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
                $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
            
                /* days and weeks vars now ... */
                $running_day = date('w',mktime(0,0,0,$month,1,$year));
                $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
                $days_in_this_week = 1;
                $day_counter = 0;
                $dates_array = array();
            
                /* row for week one */
                $calendar.= '<tr class="calendar-row">';
            
                /* print "blank" days until the first of the current week */
                for($x = 0; $x < $running_day; $x++):
                    $calendar.= '<td class="calendar-day-np"> </td>';
                    $days_in_this_week++;
                endfor;
            
                /* keep going with days.... */
                for($list_day = 1; $list_day <= $days_in_month; $list_day++):
                    $calendar.= '<td class="calendar-day">';
                        /* add in the day number */
                        $calendar.= '<div class="day-number"><a class="btn btn-block active-date"  data-id="'.$list_day.'">'.$list_day.'</a></div>';
            
                        /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
                        $calendar.= str_repeat('<p> </p>',2);
                        
                    $calendar.= '</td>';
                    if($running_day == 6):
                        $calendar.= '</tr>';
                        if(($day_counter+1) != $days_in_month):
                            $calendar.= '<tr class="calendar-row">';
                        endif;
                        $running_day = -1;
                        $days_in_this_week = 0;
                    endif;
                    $days_in_this_week++; $running_day++; $day_counter++;
                endfor;
            
                /* finish the rest of the days in the week */
                if($days_in_this_week < 8):
                    for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                        $calendar.= '<td class="calendar-day-np"> </td>';
                    endfor;
                endif;
            
                /* final row */
                $calendar.= '</tr>';
            
                /* end the table */
                $calendar.= '</table>';
                
                /* all done, return result */
                return $calendar;
            }
            
            /* sample usages */
            echo '<h2><center>' . date('F Y', strtotime($year.'-'.$month)) . '</center></h2>';
            return draw_calendar($month,$year);
        }
        elseif($id == 'generate')
        {
            
            $data = SF4::generate($request->get('selectedyear'),$request->get('selectedmonth'),$request->get('dates'),$request->get('selectedsy'));
            return view("registrar.forms.form4.quickview")
                ->with('data',$data);
        
        }
        elseif($id == 'export')
        {
            $schoolinfo = Db::table('schoolinfo')
                ->select(
                    'schoolinfo.schoolid',
                    'schoolinfo.schoolname',
                    'schoolinfo.authorized',
                    'refcitymun.citymunDesc',
                    'refcitymun.citymunDesc as division',
                    'schoolinfo.district',
                    'schoolinfo.address',
                    'schoolinfo.picurl',
                    'refregion.regDesc',
                    'refregion.regDesc as region'
                )
                ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
                ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
                ->get();
                    
            $year = $request->get('selectedyear');
            $month = $request->get('selectedmonth');
            $days = $request->get('dates');
            $syid = $request->get('selectedsy');
            $data = SF4::generate($year,$month,$days,$syid);
            // return collect($data);
            
            $schoolyear = DB::table('sy')
                    ->where('id', $syid)->first()->sydesc;

            $monthname = date('F', mktime(0, 0, 0, $month, 10)); // March

            if($request->get('exporttype') == 'pdf')
            {
                $pdf = PDF::loadView('registrar.pdf.pdf_schoolform4v2',compact('data','month','schoolinfo','syid'));
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->stream();
            }
            if($request->get('exporttype') == 'excel')
            {
                // return $data;
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path().'/public/excelformats/schoolform4.xls');
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
                
                if(strpos(DB::table('schoolinfo')->first()->picurl, '?') !== false){
                    $picurl = substr(DB::table('schoolinfo')->first()->picurl, 0, strpos(DB::table('schoolinfo')->first()->picurl, "?"));
                }else{
                    $picurl = DB::table('schoolinfo')->first()->picurl;
                }
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(base_path().'/public/'.$picurl);
                $drawing->setHeight(100);
                $drawing->setWorksheet($sheet);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(20);
                $drawing->setOffsetY(20);
                
                $sheet->setCellValue('D5', $schoolinfo[0]->schoolid);
                $sheet->setCellValue('I5', DB::table('schoolinfo')->first()->regiontext ?? $schoolinfo[0]->region);
                $sheet->setCellValue('N5', DB::table('schoolinfo')->first()->divisiontext ?? $schoolinfo[0]->division);
                $sheet->mergeCells('Y5:AF5');
                $sheet->setCellValue('Y5', DB::table('schoolinfo')->first()->districttext ?? $schoolinfo[0]->district);
                $sheet->setCellValue('C7', $schoolinfo[0]->schoolname);
                $sheet->setCellValue('Y7', $schoolyear);
                $sheet->setCellValue('AJ7', $monthname);
                $cellcount = 12;
                $sheet->insertNewRowBefore($cellcount, 1);
                if(count($data)>0)
                {
                    foreach($data as $dataval)
                    {
                        $sheet->setCellValue('A'.$cellcount, $dataval->levelname);
                        $sheet->setCellValue('B'.$cellcount, $dataval->sectionname);
                        $sheet->mergeCells('C'.$cellcount.':D'.$cellcount);
                        $sheet->setCellValue('C'.$cellcount, $dataval->firstname.' '.$dataval->lastname);

                        $sheet->setCellValue('E'.$cellcount, $dataval->registered->male);
                        $sheet->setCellValue('F'.$cellcount, $dataval->registered->female);
                        $sheet->setCellValue('G'.$cellcount, $dataval->registered->total);

                        $sheet->setCellValue('H'.$cellcount, $dataval->attendance->male);
                        $sheet->setCellValue('I'.$cellcount, $dataval->attendance->female);
                        $sheet->setCellValue('J'.$cellcount, $dataval->attendance->total);

                        $sheet->setCellValue('K'.$cellcount, 0);
                        $sheet->setCellValue('L'.$cellcount, 0);
                        $sheet->setCellValue('M'.$cellcount, 0);

                        $sheet->setCellValue('N'.$cellcount, $dataval->dropped_out_a->male);
                        $sheet->setCellValue('O'.$cellcount, $dataval->dropped_out_a->female);
                        $sheet->setCellValue('P'.$cellcount, $dataval->dropped_out_a->total);

                        $sheet->setCellValue('Q'.$cellcount, $dataval->dropped_out_b->male);
                        $sheet->setCellValue('R'.$cellcount, $dataval->dropped_out_b->female);
                        $sheet->setCellValue('S'.$cellcount, $dataval->dropped_out_b->total);

                        $sheet->setCellValue('T'.$cellcount, '=SUM(N'.$cellcount.',Q'.$cellcount.')');
                        $sheet->setCellValue('U'.$cellcount, '=SUM(O'.$cellcount.',R'.$cellcount.')');
                        $sheet->setCellValue('V'.$cellcount, '=SUM(P'.$cellcount.',S'.$cellcount.')');

                        $sheet->setCellValue('W'.$cellcount, $dataval->transferred_out_a->male);
                        $sheet->setCellValue('X'.$cellcount, $dataval->transferred_out_a->female);
                        $sheet->setCellValue('Y'.$cellcount, $dataval->transferred_out_a->total);

                        $sheet->setCellValue('Z'.$cellcount, $dataval->transferred_out_b->male);
                        $sheet->setCellValue('AA'.$cellcount, $dataval->transferred_out_b->female);
                        $sheet->setCellValue('AB'.$cellcount, $dataval->transferred_out_b->total);

                        $sheet->setCellValue('AC'.$cellcount, '=SUM(W'.$cellcount.',Z'.$cellcount.')');
                        $sheet->setCellValue('AD'.$cellcount, '=SUM(X'.$cellcount.',AA'.$cellcount.')');
                        $sheet->setCellValue('AE'.$cellcount, '=SUM(Y'.$cellcount.',AB'.$cellcount.')');

                        $sheet->setCellValue('AF'.$cellcount, $dataval->transferred_in_a->male);
                        $sheet->setCellValue('AG'.$cellcount, $dataval->transferred_in_a->female);
                        $sheet->setCellValue('AH'.$cellcount, $dataval->transferred_in_a->total);

                        $sheet->setCellValue('AI'.$cellcount, $dataval->transferred_in_b->male);
                        $sheet->setCellValue('AJ'.$cellcount, $dataval->transferred_in_b->female);
                        $sheet->setCellValue('AK'.$cellcount, $dataval->transferred_in_b->total);

                        $sheet->setCellValue('AL'.$cellcount, '=SUM(AF'.$cellcount.',AI'.$cellcount.')');
                        $sheet->setCellValue('AM'.$cellcount, '=SUM(AG'.$cellcount.',AJ'.$cellcount.')');
                        $sheet->setCellValue('AN'.$cellcount, '=SUM(AH'.$cellcount.',AK'.$cellcount.')');
                        
                        $cellcount+=1;
                        $sheet->insertNewRowBefore($cellcount, 1);
                    }
                    $cellcount+=5;

                //PRE_SCHOOL
                    $sheet->setCellValue('E'.$cellcount, collect($data)->where('acadprogid',2)->pluck('registered')->sum('male'));
                    $sheet->setCellValue('F'.$cellcount, collect($data)->where('acadprogid',2)->pluck('registered')->sum('female'));
                    $sheet->setCellValue('G'.$cellcount, collect($data)->where('acadprogid',2)->pluck('registered')->sum('total'));

                    $sheet->setCellValue('H'.$cellcount, collect($data)->where('acadprogid',2)->pluck('attendance')->sum('male'));
                    $sheet->setCellValue('I'.$cellcount, collect($data)->where('acadprogid',2)->pluck('attendance')->sum('female'));
                    $sheet->setCellValue('J'.$cellcount, collect($data)->where('acadprogid',2)->pluck('attendance')->sum('total'));

                    $sheet->setCellValue('K'.$cellcount, 0);
                    $sheet->setCellValue('L'.$cellcount, 0);
                    $sheet->setCellValue('M'.$cellcount, 0);

                    $sheet->setCellValue('N'.$cellcount, collect($data)->where('acadprogid',2)->pluck('dropped_out_a')->sum('male'));
                    $sheet->setCellValue('O'.$cellcount, collect($data)->where('acadprogid',2)->pluck('dropped_out_a')->sum('female'));
                    $sheet->setCellValue('P'.$cellcount, collect($data)->where('acadprogid',2)->pluck('dropped_out_a')->sum('total'));

                    $sheet->setCellValue('Q'.$cellcount, collect($data)->where('acadprogid',2)->pluck('dropped_out_b')->sum('male'));
                    $sheet->setCellValue('R'.$cellcount, collect($data)->where('acadprogid',2)->pluck('dropped_out_b')->sum('female'));
                    $sheet->setCellValue('S'.$cellcount, collect($data)->where('acadprogid',2)->pluck('dropped_out_b')->sum('total'));

                    // return $data;
                    $sheet->setCellValue('T'.$cellcount, $dataval->attendance->male);
                    $sheet->setCellValue('U'.$cellcount, $dataval->attendance->female);
                    $sheet->setCellValue('V'.$cellcount, $dataval->attendance->total);

                    $sheet->setCellValue('W'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_out_a')->sum('male'));
                    $sheet->setCellValue('X'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_out_a')->sum('female'));
                    $sheet->setCellValue('Y'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_out_a')->sum('total'));

                    $sheet->setCellValue('Z'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_out_b')->sum('male'));
                    $sheet->setCellValue('AA'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_out_b')->sum('female'));
                    $sheet->setCellValue('AB'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_out_b')->sum('total'));

                    $sheet->setCellValue('AC'.$cellcount, '=SUM(AC12,AC'.($cellcount-6).')');
                    $sheet->setCellValue('AD'.$cellcount, '=SUM(AD12,AD'.($cellcount-6).')');
                    $sheet->setCellValue('AE'.$cellcount, '=SUM(AE12,AE'.($cellcount-6).')');

                    $sheet->setCellValue('AF'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_in_a')->sum('male'));
                    $sheet->setCellValue('AG'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_in_a')->sum('female'));
                    $sheet->setCellValue('AH'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_in_a')->sum('total'));

                    $sheet->setCellValue('AI'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_in_b')->sum('male'));
                    $sheet->setCellValue('AJ'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_in_b')->sum('female'));
                    $sheet->setCellValue('AK'.$cellcount, collect($data)->where('acadprogid',2)->pluck('transferred_in_b')->sum('total'));

                    $sheet->setCellValue('AL'.$cellcount, '=SUM(AL12,AL'.($cellcount-6).')');
                    $sheet->setCellValue('AM'.$cellcount, '=SUM(AM12,AM'.($cellcount-6).')');
                    $sheet->setCellValue('AN'.$cellcount, '=SUM(AN12,AN'.($cellcount-6).')');
                    $cellcount+=1;

                // GRADE 1 - GRADE 7
                    $sheet->setCellValue('E'.$cellcount, collect($data)->where('levelid',1)->pluck('registered')->sum('male').'/'.collect($data)->where('levelid',10)->pluck('registered')->sum('male'));
                    $sheet->setCellValue('F'.$cellcount, collect($data)->where('levelid',1)->pluck('registered')->sum('female').'/'.collect($data)->where('levelid',10)->pluck('registered')->sum('female'));
                    $sheet->setCellValue('G'.$cellcount,  collect($data)->where('levelid',1)->pluck('registered')->sum('total').'/'.collect($data)->where('levelid',10)->pluck('registered')->sum('total'));

                    $sheet->setCellValue('H'.$cellcount, collect($data)->where('levelid',1)->pluck('attendance')->sum('male').'/'.collect($data)->where('levelid',10)->pluck('attendance')->sum('male'));
                    $sheet->setCellValue('I'.$cellcount, collect($data)->where('levelid',1)->pluck('attendance')->sum('female').'/'.collect($data)->where('levelid',10)->pluck('attendance')->sum('female'));
                    $sheet->setCellValue('J'.$cellcount, collect($data)->where('levelid',1)->pluck('attendance')->sum('total').'/'.collect($data)->where('levelid',10)->pluck('attendance')->sum('total'));

                    $sheet->setCellValue('K'.$cellcount, '0/0');
                    $sheet->setCellValue('L'.$cellcount, '0/0');
                    $sheet->setCellValue('M'.$cellcount, '0/0');

                    $sheet->setCellValue('N'.$cellcount, collect($data)->where('levelid',1)->pluck('dropped_out_a')->sum('male').'/'.collect($data)->where('levelid',10)->pluck('dropped_out_a')->sum('male'));
                    $sheet->setCellValue('O'.$cellcount, collect($data)->where('levelid',1)->pluck('dropped_out_a')->sum('female').'/'.collect($data)->where('levelid',10)->pluck('dropped_out_a')->sum('female'));
                    $sheet->setCellValue('P'.$cellcount, collect($data)->where('levelid',1)->pluck('dropped_out_a')->sum('total').'/'.collect($data)->where('levelid',10)->pluck('dropped_out_a')->sum('total'));

                    $sheet->setCellValue('Q'.$cellcount, collect($data)->where('levelid',1)->pluck('dropped_out_b')->sum('male').'/'.collect($data)->where('levelid',10)->pluck('dropped_out_b')->sum('male'));
                    $sheet->setCellValue('R'.$cellcount, collect($data)->where('levelid',1)->pluck('dropped_out_b')->sum('female').'/'.collect($data)->where('levelid',10)->pluck('dropped_out_b')->sum('female'));
                    $sheet->setCellValue('S'.$cellcount, collect($data)->where('levelid',1)->pluck('dropped_out_b')->sum('total').'/'.collect($data)->where('levelid',10)->pluck('dropped_out_b')->sum('total'));

                    $droppedout_male_a1 = collect($data)->where('levelid',1)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b1 = collect($data)->where('levelid',1)->pluck('dropped_out_b')->sum('male');
                    $droppedout_male_a2 = collect($data)->where('levelid',10)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b2 = collect($data)->where('levelid',10)->pluck('dropped_out_b')->sum('male');

                    $droppedout_female_a1 = collect($data)->where('levelid',1)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b1 = collect($data)->where('levelid',1)->pluck('dropped_out_b')->sum('female');
                    $droppedout_female_a2 = collect($data)->where('levelid',10)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b2 = collect($data)->where('levelid',10)->pluck('dropped_out_b')->sum('female');

                    $droppedout_total_a1 = $droppedout_male_a1+$droppedout_female_a1;
                    $droppedout_total_b1 = $droppedout_male_b1+$droppedout_female_b1;
                    $droppedout_total_a2 = $droppedout_male_a2+$droppedout_female_a2;
                    $droppedout_total_b2 = $droppedout_male_b2+$droppedout_female_b2;
                    
                    $sheet->setCellValue('T'.$cellcount, ($droppedout_male_a1+$droppedout_male_b1).'/'.($droppedout_male_a2+$droppedout_male_b2));
                    $sheet->setCellValue('U'.$cellcount, ($droppedout_female_a1+$droppedout_female_b1).'/'.($droppedout_female_a2+$droppedout_female_b2));
                    $sheet->setCellValue('V'.$cellcount, ($droppedout_total_a1+$droppedout_total_b1).'/'.($droppedout_total_a2+$droppedout_total_b2));

                    $sheet->setCellValue('W'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_out_a')->sum('male').'/'.collect($data)->where('levelid',10)->pluck('transferred_out_a')->sum('male'));
                    $sheet->setCellValue('X'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_out_a')->sum('female').'/'.collect($data)->where('levelid',10)->pluck('transferred_out_a')->sum('female'));
                    $sheet->setCellValue('Y'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_out_a')->sum('total').'/'.collect($data)->where('levelid',10)->pluck('transferred_out_a')->sum('total'));

                    $sheet->setCellValue('Z'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_out_b')->sum('male').'/'.collect($data)->where('levelid',10)->pluck('transferred_out_b')->sum('male'));
                    $sheet->setCellValue('AA'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_out_b')->sum('female').'/'.collect($data)->where('levelid',10)->pluck('transferred_out_b')->sum('female'));
                    $sheet->setCellValue('AB'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_out_b')->sum('total').'/'.collect($data)->where('levelid',10)->pluck('transferred_out_b')->sum('total'));

                    $transferred_out_male_a1 = collect($data)->where('levelid',1)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b1 = collect($data)->where('levelid',1)->pluck('transferred_out_b')->sum('male');
                    $transferred_out_male_a2 = collect($data)->where('levelid',10)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b2 = collect($data)->where('levelid',10)->pluck('transferred_out_b')->sum('male');

                    $transferred_out_female_a1 = collect($data)->where('levelid',1)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b1 = collect($data)->where('levelid',1)->pluck('transferred_out_b')->sum('female');
                    $transferred_out_female_a2 = collect($data)->where('levelid',10)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b2 = collect($data)->where('levelid',10)->pluck('transferred_out_b')->sum('female');

                    $transferred_out_total_a1 = $transferred_out_male_a1+$transferred_out_female_a1;
                    $transferred_out_total_b1 = $transferred_out_male_b1+$transferred_out_female_b1;
                    $transferred_out_total_a2 = $transferred_out_male_a2+$transferred_out_female_a2;
                    $transferred_out_total_b2 = $transferred_out_male_b2+$transferred_out_female_b2;
                    
                    $sheet->setCellValue('AC'.$cellcount, ($transferred_out_male_a1+$transferred_out_male_b1).'/'.($transferred_out_male_a2+$transferred_out_male_b2));
                    $sheet->setCellValue('AD'.$cellcount, ($transferred_out_female_a1+$transferred_out_female_b1).'/'.($transferred_out_female_a2+$transferred_out_female_b2));
                    $sheet->setCellValue('AE'.$cellcount, ($transferred_out_total_a1+$transferred_out_total_b1).'/'.($transferred_out_total_a2+$transferred_out_total_b2));

                    $sheet->setCellValue('AF'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_in_a')->sum('male').'/'.collect($data)->where('levelid',10)->pluck('transferred_in_a')->sum('male'));
                    $sheet->setCellValue('AG'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_in_a')->sum('female').'/'.collect($data)->where('levelid',10)->pluck('transferred_in_a')->sum('female'));
                    $sheet->setCellValue('AH'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_in_a')->sum('total').'/'.collect($data)->where('levelid',10)->pluck('transferred_in_a')->sum('total'));

                    $sheet->setCellValue('AI'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_in_b')->sum('male').'/'.collect($data)->where('levelid',10)->pluck('transferred_in_b')->sum('male'));
                    $sheet->setCellValue('AJ'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_in_b')->sum('female').'/'.collect($data)->where('levelid',10)->pluck('transferred_in_b')->sum('female'));
                    $sheet->setCellValue('AK'.$cellcount, collect($data)->where('levelid',1)->pluck('transferred_in_b')->sum('total').'/'.collect($data)->where('levelid',10)->pluck('transferred_in_b')->sum('total'));

                    $transferred_in_male_a1 = collect($data)->where('levelid',1)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b1 = collect($data)->where('levelid',1)->pluck('transferred_in_b')->sum('male');
                    $transferred_in_male_a2 = collect($data)->where('levelid',10)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b2 = collect($data)->where('levelid',10)->pluck('transferred_in_b')->sum('male');

                    $transferred_in_female_a1 = collect($data)->where('levelid',1)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b1 = collect($data)->where('levelid',1)->pluck('transferred_in_b')->sum('female');
                    $transferred_in_female_a2 = collect($data)->where('levelid',10)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b2 = collect($data)->where('levelid',10)->pluck('transferred_in_b')->sum('female');

                    $transferred_in_total_a1 = $transferred_in_male_a1+$transferred_in_female_a1;
                    $transferred_in_total_b1 = $transferred_in_male_b1+$transferred_in_female_b1;
                    $transferred_in_total_a2 = $transferred_in_male_a2+$transferred_in_female_a2;
                    $transferred_in_total_b2 = $transferred_in_male_b2+$transferred_in_female_b2;
                    
                    $sheet->setCellValue('AL'.$cellcount, ($transferred_in_male_a1+$transferred_in_male_b1).'/'.($transferred_in_male_a2+$transferred_in_male_b2));
                    $sheet->setCellValue('AM'.$cellcount, ($transferred_in_female_a1+$transferred_in_female_b1).'/'.($transferred_in_female_a2+$transferred_in_female_b2));
                    $sheet->setCellValue('AN'.$cellcount, ($transferred_in_total_a1+$transferred_in_total_b1).'/'.($transferred_in_total_a2+$transferred_in_total_b2));

                    $cellcount+=1;

                // GRADE 2 - GRADE 8
                    $sheet->setCellValue('E'.$cellcount, collect($data)->where('levelid',5)->pluck('registered')->sum('male').'/'.collect($data)->where('levelid',11)->pluck('registered')->sum('male'));
                    $sheet->setCellValue('F'.$cellcount, collect($data)->where('levelid',5)->pluck('registered')->sum('female').'/'.collect($data)->where('levelid',11)->pluck('registered')->sum('female'));
                    $sheet->setCellValue('G'.$cellcount,  collect($data)->where('levelid',5)->pluck('registered')->sum('total').'/'.collect($data)->where('levelid',11)->pluck('registered')->sum('total'));

                    $sheet->setCellValue('H'.$cellcount, collect($data)->where('levelid',5)->pluck('attendance')->sum('male').'/'.collect($data)->where('levelid',11)->pluck('attendance')->sum('male'));
                    $sheet->setCellValue('I'.$cellcount, collect($data)->where('levelid',5)->pluck('attendance')->sum('female').'/'.collect($data)->where('levelid',11)->pluck('attendance')->sum('female'));
                    $sheet->setCellValue('J'.$cellcount, collect($data)->where('levelid',5)->pluck('attendance')->sum('total').'/'.collect($data)->where('levelid',11)->pluck('attendance')->sum('total'));

                    $sheet->setCellValue('K'.$cellcount, '0/0');
                    $sheet->setCellValue('L'.$cellcount, '0/0');
                    $sheet->setCellValue('M'.$cellcount, '0/0');

                    $sheet->setCellValue('N'.$cellcount, collect($data)->where('levelid',5)->pluck('dropped_out_a')->sum('male').'/'.collect($data)->where('levelid',11)->pluck('dropped_out_a')->sum('male'));
                    $sheet->setCellValue('O'.$cellcount, collect($data)->where('levelid',5)->pluck('dropped_out_a')->sum('female').'/'.collect($data)->where('levelid',11)->pluck('dropped_out_a')->sum('female'));
                    $sheet->setCellValue('P'.$cellcount, collect($data)->where('levelid',5)->pluck('dropped_out_a')->sum('total').'/'.collect($data)->where('levelid',11)->pluck('dropped_out_a')->sum('total'));

                    $sheet->setCellValue('Q'.$cellcount, collect($data)->where('levelid',5)->pluck('dropped_out_b')->sum('male').'/'.collect($data)->where('levelid',11)->pluck('dropped_out_b')->sum('male'));
                    $sheet->setCellValue('R'.$cellcount, collect($data)->where('levelid',5)->pluck('dropped_out_b')->sum('female').'/'.collect($data)->where('levelid',11)->pluck('dropped_out_b')->sum('female'));
                    $sheet->setCellValue('S'.$cellcount, collect($data)->where('levelid',5)->pluck('dropped_out_b')->sum('total').'/'.collect($data)->where('levelid',11)->pluck('dropped_out_b')->sum('total'));

                    $droppedout_male_a1 = collect($data)->where('levelid',5)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b1 = collect($data)->where('levelid',5)->pluck('dropped_out_b')->sum('male');
                    $droppedout_male_a2 = collect($data)->where('levelid',11)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b2 = collect($data)->where('levelid',11)->pluck('dropped_out_b')->sum('male');

                    $droppedout_female_a1 = collect($data)->where('levelid',5)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b1 = collect($data)->where('levelid',5)->pluck('dropped_out_b')->sum('female');
                    $droppedout_female_a2 = collect($data)->where('levelid',11)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b2 = collect($data)->where('levelid',11)->pluck('dropped_out_b')->sum('female');

                    $droppedout_total_a1 = $droppedout_male_a1+$droppedout_female_a1;
                    $droppedout_total_b1 = $droppedout_male_b1+$droppedout_female_b1;
                    $droppedout_total_a2 = $droppedout_male_a2+$droppedout_female_a2;
                    $droppedout_total_b2 = $droppedout_male_b2+$droppedout_female_b2;
                    
                    $sheet->setCellValue('T'.$cellcount, ($droppedout_male_a1+$droppedout_male_b1).'/'.($droppedout_male_a2+$droppedout_male_b2));
                    $sheet->setCellValue('U'.$cellcount, ($droppedout_female_a1+$droppedout_female_b1).'/'.($droppedout_female_a2+$droppedout_female_b2));
                    $sheet->setCellValue('V'.$cellcount, ($droppedout_total_a1+$droppedout_total_b1).'/'.($droppedout_total_a2+$droppedout_total_b2));

                    $sheet->setCellValue('W'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_out_a')->sum('male').'/'.collect($data)->where('levelid',11)->pluck('transferred_out_a')->sum('male'));
                    $sheet->setCellValue('X'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_out_a')->sum('female').'/'.collect($data)->where('levelid',11)->pluck('transferred_out_a')->sum('female'));
                    $sheet->setCellValue('Y'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_out_a')->sum('total').'/'.collect($data)->where('levelid',11)->pluck('transferred_out_a')->sum('total'));

                    $sheet->setCellValue('Z'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_out_b')->sum('male').'/'.collect($data)->where('levelid',11)->pluck('transferred_out_b')->sum('male'));
                    $sheet->setCellValue('AA'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_out_b')->sum('female').'/'.collect($data)->where('levelid',11)->pluck('transferred_out_b')->sum('female'));
                    $sheet->setCellValue('AB'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_out_b')->sum('total').'/'.collect($data)->where('levelid',11)->pluck('transferred_out_b')->sum('total'));

                    $transferred_out_male_a1 = collect($data)->where('levelid',5)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b1 = collect($data)->where('levelid',5)->pluck('transferred_out_b')->sum('male');
                    $transferred_out_male_a2 = collect($data)->where('levelid',11)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b2 = collect($data)->where('levelid',11)->pluck('transferred_out_b')->sum('male');

                    $transferred_out_female_a1 = collect($data)->where('levelid',5)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b1 = collect($data)->where('levelid',5)->pluck('transferred_out_b')->sum('female');
                    $transferred_out_female_a2 = collect($data)->where('levelid',11)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b2 = collect($data)->where('levelid',11)->pluck('transferred_out_b')->sum('female');

                    $transferred_out_total_a1 = $transferred_out_male_a1+$transferred_out_female_a1;
                    $transferred_out_total_b1 = $transferred_out_male_b1+$transferred_out_female_b1;
                    $transferred_out_total_a2 = $transferred_out_male_a2+$transferred_out_female_a2;
                    $transferred_out_total_b2 = $transferred_out_male_b2+$transferred_out_female_b2;
                    
                    $sheet->setCellValue('AC'.$cellcount, ($transferred_out_male_a1+$transferred_out_male_b1).'/'.($transferred_out_male_a2+$transferred_out_male_b2));
                    $sheet->setCellValue('AD'.$cellcount, ($transferred_out_female_a1+$transferred_out_female_b1).'/'.($transferred_out_female_a2+$transferred_out_female_b2));
                    $sheet->setCellValue('AE'.$cellcount, ($transferred_out_total_a1+$transferred_out_total_b1).'/'.($transferred_out_total_a2+$transferred_out_total_b2));

                    $sheet->setCellValue('AF'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_in_a')->sum('male').'/'.collect($data)->where('levelid',11)->pluck('transferred_in_a')->sum('male'));
                    $sheet->setCellValue('AG'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_in_a')->sum('female').'/'.collect($data)->where('levelid',11)->pluck('transferred_in_a')->sum('female'));
                    $sheet->setCellValue('AH'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_in_a')->sum('total').'/'.collect($data)->where('levelid',11)->pluck('transferred_in_a')->sum('total'));

                    $sheet->setCellValue('AI'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_in_b')->sum('male').'/'.collect($data)->where('levelid',11)->pluck('transferred_in_b')->sum('male'));
                    $sheet->setCellValue('AJ'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_in_b')->sum('female').'/'.collect($data)->where('levelid',11)->pluck('transferred_in_b')->sum('female'));
                    $sheet->setCellValue('AK'.$cellcount, collect($data)->where('levelid',5)->pluck('transferred_in_b')->sum('total').'/'.collect($data)->where('levelid',11)->pluck('transferred_in_b')->sum('total'));

                    $transferred_in_male_a1 = collect($data)->where('levelid',5)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b1 = collect($data)->where('levelid',5)->pluck('transferred_in_b')->sum('male');
                    $transferred_in_male_a2 = collect($data)->where('levelid',11)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b2 = collect($data)->where('levelid',11)->pluck('transferred_in_b')->sum('male');

                    $transferred_in_female_a1 = collect($data)->where('levelid',5)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b1 = collect($data)->where('levelid',5)->pluck('transferred_in_b')->sum('female');
                    $transferred_in_female_a2 = collect($data)->where('levelid',11)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b2 = collect($data)->where('levelid',11)->pluck('transferred_in_b')->sum('female');

                    $transferred_in_total_a1 = $transferred_in_male_a1+$transferred_in_female_a1;
                    $transferred_in_total_b1 = $transferred_in_male_b1+$transferred_in_female_b1;
                    $transferred_in_total_a2 = $transferred_in_male_a2+$transferred_in_female_a2;
                    $transferred_in_total_b2 = $transferred_in_male_b2+$transferred_in_female_b2;
                    
                    $sheet->setCellValue('AL'.$cellcount, ($transferred_in_male_a1+$transferred_in_male_b1).'/'.($transferred_in_male_a2+$transferred_in_male_b2));
                    $sheet->setCellValue('AM'.$cellcount, ($transferred_in_female_a1+$transferred_in_female_b1).'/'.($transferred_in_female_a2+$transferred_in_female_b2));
                    $sheet->setCellValue('AN'.$cellcount, ($transferred_in_total_a1+$transferred_in_total_b1).'/'.($transferred_in_total_a2+$transferred_in_total_b2));

                    $cellcount+=1;

                // GRADE 3 - GRADE 9
                    $sheet->setCellValue('E'.$cellcount, collect($data)->where('levelid',6)->pluck('registered')->sum('male').'/'.collect($data)->where('levelid',12)->pluck('registered')->sum('male'));
                    $sheet->setCellValue('F'.$cellcount, collect($data)->where('levelid',6)->pluck('registered')->sum('female').'/'.collect($data)->where('levelid',12)->pluck('registered')->sum('female'));
                    $sheet->setCellValue('G'.$cellcount,  collect($data)->where('levelid',6)->pluck('registered')->sum('total').'/'.collect($data)->where('levelid',12)->pluck('registered')->sum('total'));

                    $sheet->setCellValue('H'.$cellcount, collect($data)->where('levelid',6)->pluck('attendance')->sum('male').'/'.collect($data)->where('levelid',12)->pluck('attendance')->sum('male'));
                    $sheet->setCellValue('I'.$cellcount, collect($data)->where('levelid',6)->pluck('attendance')->sum('female').'/'.collect($data)->where('levelid',12)->pluck('attendance')->sum('female'));
                    $sheet->setCellValue('J'.$cellcount, collect($data)->where('levelid',6)->pluck('attendance')->sum('total').'/'.collect($data)->where('levelid',12)->pluck('attendance')->sum('total'));

                    $sheet->setCellValue('K'.$cellcount, '0/0');
                    $sheet->setCellValue('L'.$cellcount, '0/0');
                    $sheet->setCellValue('M'.$cellcount, '0/0');

                    $sheet->setCellValue('N'.$cellcount, collect($data)->where('levelid',6)->pluck('dropped_out_a')->sum('male').'/'.collect($data)->where('levelid',12)->pluck('dropped_out_a')->sum('male'));
                    $sheet->setCellValue('O'.$cellcount, collect($data)->where('levelid',6)->pluck('dropped_out_a')->sum('female').'/'.collect($data)->where('levelid',12)->pluck('dropped_out_a')->sum('female'));
                    $sheet->setCellValue('P'.$cellcount, collect($data)->where('levelid',6)->pluck('dropped_out_a')->sum('total').'/'.collect($data)->where('levelid',12)->pluck('dropped_out_a')->sum('total'));

                    $sheet->setCellValue('Q'.$cellcount, collect($data)->where('levelid',6)->pluck('dropped_out_b')->sum('male').'/'.collect($data)->where('levelid',12)->pluck('dropped_out_b')->sum('male'));
                    $sheet->setCellValue('R'.$cellcount, collect($data)->where('levelid',6)->pluck('dropped_out_b')->sum('female').'/'.collect($data)->where('levelid',12)->pluck('dropped_out_b')->sum('female'));
                    $sheet->setCellValue('S'.$cellcount, collect($data)->where('levelid',6)->pluck('dropped_out_b')->sum('total').'/'.collect($data)->where('levelid',12)->pluck('dropped_out_b')->sum('total'));

                    $droppedout_male_a1 = collect($data)->where('levelid',6)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b1 = collect($data)->where('levelid',6)->pluck('dropped_out_b')->sum('male');
                    $droppedout_male_a2 = collect($data)->where('levelid',12)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b2 = collect($data)->where('levelid',12)->pluck('dropped_out_b')->sum('male');

                    $droppedout_female_a1 = collect($data)->where('levelid',6)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b1 = collect($data)->where('levelid',6)->pluck('dropped_out_b')->sum('female');
                    $droppedout_female_a2 = collect($data)->where('levelid',12)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b2 = collect($data)->where('levelid',12)->pluck('dropped_out_b')->sum('female');

                    $droppedout_total_a1 = $droppedout_male_a1+$droppedout_female_a1;
                    $droppedout_total_b1 = $droppedout_male_b1+$droppedout_female_b1;
                    $droppedout_total_a2 = $droppedout_male_a2+$droppedout_female_a2;
                    $droppedout_total_b2 = $droppedout_male_b2+$droppedout_female_b2;
                    
                    $sheet->setCellValue('T'.$cellcount, ($droppedout_male_a1+$droppedout_male_b1).'/'.($droppedout_male_a2+$droppedout_male_b2));
                    $sheet->setCellValue('U'.$cellcount, ($droppedout_female_a1+$droppedout_female_b1).'/'.($droppedout_female_a2+$droppedout_female_b2));
                    $sheet->setCellValue('V'.$cellcount, ($droppedout_total_a1+$droppedout_total_b1).'/'.($droppedout_total_a2+$droppedout_total_b2));

                    $sheet->setCellValue('W'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_out_a')->sum('male').'/'.collect($data)->where('levelid',12)->pluck('transferred_out_a')->sum('male'));
                    $sheet->setCellValue('X'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_out_a')->sum('female').'/'.collect($data)->where('levelid',12)->pluck('transferred_out_a')->sum('female'));
                    $sheet->setCellValue('Y'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_out_a')->sum('total').'/'.collect($data)->where('levelid',12)->pluck('transferred_out_a')->sum('total'));

                    $sheet->setCellValue('Z'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_out_b')->sum('male').'/'.collect($data)->where('levelid',12)->pluck('transferred_out_b')->sum('male'));
                    $sheet->setCellValue('AA'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_out_b')->sum('female').'/'.collect($data)->where('levelid',12)->pluck('transferred_out_b')->sum('female'));
                    $sheet->setCellValue('AB'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_out_b')->sum('total').'/'.collect($data)->where('levelid',12)->pluck('transferred_out_b')->sum('total'));

                    $transferred_out_male_a1 = collect($data)->where('levelid',6)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b1 = collect($data)->where('levelid',6)->pluck('transferred_out_b')->sum('male');
                    $transferred_out_male_a2 = collect($data)->where('levelid',12)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b2 = collect($data)->where('levelid',12)->pluck('transferred_out_b')->sum('male');

                    $transferred_out_female_a1 = collect($data)->where('levelid',6)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b1 = collect($data)->where('levelid',6)->pluck('transferred_out_b')->sum('female');
                    $transferred_out_female_a2 = collect($data)->where('levelid',12)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b2 = collect($data)->where('levelid',12)->pluck('transferred_out_b')->sum('female');

                    $transferred_out_total_a1 = $transferred_out_male_a1+$transferred_out_female_a1;
                    $transferred_out_total_b1 = $transferred_out_male_b1+$transferred_out_female_b1;
                    $transferred_out_total_a2 = $transferred_out_male_a2+$transferred_out_female_a2;
                    $transferred_out_total_b2 = $transferred_out_male_b2+$transferred_out_female_b2;
                    
                    $sheet->setCellValue('AC'.$cellcount, ($transferred_out_male_a1+$transferred_out_male_b1).'/'.($transferred_out_male_a2+$transferred_out_male_b2));
                    $sheet->setCellValue('AD'.$cellcount, ($transferred_out_female_a1+$transferred_out_female_b1).'/'.($transferred_out_female_a2+$transferred_out_female_b2));
                    $sheet->setCellValue('AE'.$cellcount, ($transferred_out_total_a1+$transferred_out_total_b1).'/'.($transferred_out_total_a2+$transferred_out_total_b2));

                    $sheet->setCellValue('AF'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_in_a')->sum('male').'/'.collect($data)->where('levelid',12)->pluck('transferred_in_a')->sum('male'));
                    $sheet->setCellValue('AG'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_in_a')->sum('female').'/'.collect($data)->where('levelid',12)->pluck('transferred_in_a')->sum('female'));
                    $sheet->setCellValue('AH'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_in_a')->sum('total').'/'.collect($data)->where('levelid',12)->pluck('transferred_in_a')->sum('total'));

                    $sheet->setCellValue('AI'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_in_b')->sum('male').'/'.collect($data)->where('levelid',12)->pluck('transferred_in_b')->sum('male'));
                    $sheet->setCellValue('AJ'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_in_b')->sum('female').'/'.collect($data)->where('levelid',12)->pluck('transferred_in_b')->sum('female'));
                    $sheet->setCellValue('AK'.$cellcount, collect($data)->where('levelid',6)->pluck('transferred_in_b')->sum('total').'/'.collect($data)->where('levelid',12)->pluck('transferred_in_b')->sum('total'));

                    $transferred_in_male_a1 = collect($data)->where('levelid',6)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b1 = collect($data)->where('levelid',6)->pluck('transferred_in_b')->sum('male');
                    $transferred_in_male_a2 = collect($data)->where('levelid',12)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b2 = collect($data)->where('levelid',12)->pluck('transferred_in_b')->sum('male');

                    $transferred_in_female_a1 = collect($data)->where('levelid',6)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b1 = collect($data)->where('levelid',6)->pluck('transferred_in_b')->sum('female');
                    $transferred_in_female_a2 = collect($data)->where('levelid',12)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b2 = collect($data)->where('levelid',12)->pluck('transferred_in_b')->sum('female');

                    $transferred_in_total_a1 = $transferred_in_male_a1+$transferred_in_female_a1;
                    $transferred_in_total_b1 = $transferred_in_male_b1+$transferred_in_female_b1;
                    $transferred_in_total_a2 = $transferred_in_male_a2+$transferred_in_female_a2;
                    $transferred_in_total_b2 = $transferred_in_male_b2+$transferred_in_female_b2;
                    
                    $sheet->setCellValue('AL'.$cellcount, ($transferred_in_male_a1+$transferred_in_male_b1).'/'.($transferred_in_male_a2+$transferred_in_male_b2));
                    $sheet->setCellValue('AM'.$cellcount, ($transferred_in_female_a1+$transferred_in_female_b1).'/'.($transferred_in_female_a2+$transferred_in_female_b2));
                    $sheet->setCellValue('AN'.$cellcount, ($transferred_in_total_a1+$transferred_in_total_b1).'/'.($transferred_in_total_a2+$transferred_in_total_b2));

                    $cellcount+=1;

                // GRADE 4 - GRADE 10
                    $sheet->setCellValue('E'.$cellcount, collect($data)->where('levelid',7)->pluck('registered')->sum('male').'/'.collect($data)->where('levelid',13)->pluck('registered')->sum('male'));
                    $sheet->setCellValue('F'.$cellcount, collect($data)->where('levelid',7)->pluck('registered')->sum('female').'/'.collect($data)->where('levelid',13)->pluck('registered')->sum('female'));
                    $sheet->setCellValue('G'.$cellcount,  collect($data)->where('levelid',7)->pluck('registered')->sum('total').'/'.collect($data)->where('levelid',13)->pluck('registered')->sum('total'));

                    $sheet->setCellValue('H'.$cellcount, collect($data)->where('levelid',7)->pluck('attendance')->sum('male').'/'.collect($data)->where('levelid',13)->pluck('attendance')->sum('male'));
                    $sheet->setCellValue('I'.$cellcount, collect($data)->where('levelid',7)->pluck('attendance')->sum('female').'/'.collect($data)->where('levelid',13)->pluck('attendance')->sum('female'));
                    $sheet->setCellValue('J'.$cellcount, collect($data)->where('levelid',7)->pluck('attendance')->sum('total').'/'.collect($data)->where('levelid',13)->pluck('attendance')->sum('total'));

                    $sheet->setCellValue('K'.$cellcount, '0/0');
                    $sheet->setCellValue('L'.$cellcount, '0/0');
                    $sheet->setCellValue('M'.$cellcount, '0/0');

                    $sheet->setCellValue('N'.$cellcount, collect($data)->where('levelid',7)->pluck('dropped_out_a')->sum('male').'/'.collect($data)->where('levelid',13)->pluck('dropped_out_a')->sum('male'));
                    $sheet->setCellValue('O'.$cellcount, collect($data)->where('levelid',7)->pluck('dropped_out_a')->sum('female').'/'.collect($data)->where('levelid',13)->pluck('dropped_out_a')->sum('female'));
                    $sheet->setCellValue('P'.$cellcount, collect($data)->where('levelid',7)->pluck('dropped_out_a')->sum('total').'/'.collect($data)->where('levelid',13)->pluck('dropped_out_a')->sum('total'));

                    $sheet->setCellValue('Q'.$cellcount, collect($data)->where('levelid',7)->pluck('dropped_out_b')->sum('male').'/'.collect($data)->where('levelid',13)->pluck('dropped_out_b')->sum('male'));
                    $sheet->setCellValue('R'.$cellcount, collect($data)->where('levelid',7)->pluck('dropped_out_b')->sum('female').'/'.collect($data)->where('levelid',13)->pluck('dropped_out_b')->sum('female'));
                    $sheet->setCellValue('S'.$cellcount, collect($data)->where('levelid',7)->pluck('dropped_out_b')->sum('total').'/'.collect($data)->where('levelid',13)->pluck('dropped_out_b')->sum('total'));

                    $droppedout_male_a1 = collect($data)->where('levelid',7)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b1 = collect($data)->where('levelid',7)->pluck('dropped_out_b')->sum('male');
                    $droppedout_male_a2 = collect($data)->where('levelid',13)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b2 = collect($data)->where('levelid',13)->pluck('dropped_out_b')->sum('male');

                    $droppedout_female_a1 = collect($data)->where('levelid',7)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b1 = collect($data)->where('levelid',7)->pluck('dropped_out_b')->sum('female');
                    $droppedout_female_a2 = collect($data)->where('levelid',13)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b2 = collect($data)->where('levelid',13)->pluck('dropped_out_b')->sum('female');

                    $droppedout_total_a1 = $droppedout_male_a1+$droppedout_female_a1;
                    $droppedout_total_b1 = $droppedout_male_b1+$droppedout_female_b1;
                    $droppedout_total_a2 = $droppedout_male_a2+$droppedout_female_a2;
                    $droppedout_total_b2 = $droppedout_male_b2+$droppedout_female_b2;
                    
                    $sheet->setCellValue('T'.$cellcount, ($droppedout_male_a1+$droppedout_male_b1).'/'.($droppedout_male_a2+$droppedout_male_b2));
                    $sheet->setCellValue('U'.$cellcount, ($droppedout_female_a1+$droppedout_female_b1).'/'.($droppedout_female_a2+$droppedout_female_b2));
                    $sheet->setCellValue('V'.$cellcount, ($droppedout_total_a1+$droppedout_total_b1).'/'.($droppedout_total_a2+$droppedout_total_b2));

                    $sheet->setCellValue('W'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_out_a')->sum('male').'/'.collect($data)->where('levelid',13)->pluck('transferred_out_a')->sum('male'));
                    $sheet->setCellValue('X'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_out_a')->sum('female').'/'.collect($data)->where('levelid',13)->pluck('transferred_out_a')->sum('female'));
                    $sheet->setCellValue('Y'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_out_a')->sum('total').'/'.collect($data)->where('levelid',13)->pluck('transferred_out_a')->sum('total'));

                    $sheet->setCellValue('Z'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_out_b')->sum('male').'/'.collect($data)->where('levelid',13)->pluck('transferred_out_b')->sum('male'));
                    $sheet->setCellValue('AA'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_out_b')->sum('female').'/'.collect($data)->where('levelid',13)->pluck('transferred_out_b')->sum('female'));
                    $sheet->setCellValue('AB'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_out_b')->sum('total').'/'.collect($data)->where('levelid',13)->pluck('transferred_out_b')->sum('total'));

                    $transferred_out_male_a1 = collect($data)->where('levelid',7)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b1 = collect($data)->where('levelid',7)->pluck('transferred_out_b')->sum('male');
                    $transferred_out_male_a2 = collect($data)->where('levelid',13)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b2 = collect($data)->where('levelid',13)->pluck('transferred_out_b')->sum('male');

                    $transferred_out_female_a1 = collect($data)->where('levelid',7)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b1 = collect($data)->where('levelid',7)->pluck('transferred_out_b')->sum('female');
                    $transferred_out_female_a2 = collect($data)->where('levelid',13)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b2 = collect($data)->where('levelid',13)->pluck('transferred_out_b')->sum('female');

                    $transferred_out_total_a1 = $transferred_out_male_a1+$transferred_out_female_a1;
                    $transferred_out_total_b1 = $transferred_out_male_b1+$transferred_out_female_b1;
                    $transferred_out_total_a2 = $transferred_out_male_a2+$transferred_out_female_a2;
                    $transferred_out_total_b2 = $transferred_out_male_b2+$transferred_out_female_b2;
                    
                    $sheet->setCellValue('AC'.$cellcount, ($transferred_out_male_a1+$transferred_out_male_b1).'/'.($transferred_out_male_a2+$transferred_out_male_b2));
                    $sheet->setCellValue('AD'.$cellcount, ($transferred_out_female_a1+$transferred_out_female_b1).'/'.($transferred_out_female_a2+$transferred_out_female_b2));
                    $sheet->setCellValue('AE'.$cellcount, ($transferred_out_total_a1+$transferred_out_total_b1).'/'.($transferred_out_total_a2+$transferred_out_total_b2));

                    $sheet->setCellValue('AF'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_in_a')->sum('male').'/'.collect($data)->where('levelid',13)->pluck('transferred_in_a')->sum('male'));
                    $sheet->setCellValue('AG'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_in_a')->sum('female').'/'.collect($data)->where('levelid',13)->pluck('transferred_in_a')->sum('female'));
                    $sheet->setCellValue('AH'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_in_a')->sum('total').'/'.collect($data)->where('levelid',13)->pluck('transferred_in_a')->sum('total'));

                    $sheet->setCellValue('AI'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_in_b')->sum('male').'/'.collect($data)->where('levelid',13)->pluck('transferred_in_b')->sum('male'));
                    $sheet->setCellValue('AJ'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_in_b')->sum('female').'/'.collect($data)->where('levelid',13)->pluck('transferred_in_b')->sum('female'));
                    $sheet->setCellValue('AK'.$cellcount, collect($data)->where('levelid',7)->pluck('transferred_in_b')->sum('total').'/'.collect($data)->where('levelid',13)->pluck('transferred_in_b')->sum('total'));

                    $transferred_in_male_a1 = collect($data)->where('levelid',7)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b1 = collect($data)->where('levelid',7)->pluck('transferred_in_b')->sum('male');
                    $transferred_in_male_a2 = collect($data)->where('levelid',13)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b2 = collect($data)->where('levelid',13)->pluck('transferred_in_b')->sum('male');

                    $transferred_in_female_a1 = collect($data)->where('levelid',7)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b1 = collect($data)->where('levelid',7)->pluck('transferred_in_b')->sum('female');
                    $transferred_in_female_a2 = collect($data)->where('levelid',13)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b2 = collect($data)->where('levelid',13)->pluck('transferred_in_b')->sum('female');

                    $transferred_in_total_a1 = $transferred_in_male_a1+$transferred_in_female_a1;
                    $transferred_in_total_b1 = $transferred_in_male_b1+$transferred_in_female_b1;
                    $transferred_in_total_a2 = $transferred_in_male_a2+$transferred_in_female_a2;
                    $transferred_in_total_b2 = $transferred_in_male_b2+$transferred_in_female_b2;
                    
                    $sheet->setCellValue('AL'.$cellcount, ($transferred_in_male_a1+$transferred_in_male_b1).'/'.($transferred_in_male_a2+$transferred_in_male_b2));
                    $sheet->setCellValue('AM'.$cellcount, ($transferred_in_female_a1+$transferred_in_female_b1).'/'.($transferred_in_female_a2+$transferred_in_female_b2));
                    $sheet->setCellValue('AN'.$cellcount, ($transferred_in_total_a1+$transferred_in_total_b1).'/'.($transferred_in_total_a2+$transferred_in_total_b2));

                    $cellcount+=1;

                // GRADE 5 - GRADE 11
                    $sheet->setCellValue('E'.$cellcount, collect($data)->where('levelid',16)->pluck('registered')->sum('male').'/'.collect($data)->where('levelid',14)->pluck('registered')->sum('male'));
                    $sheet->setCellValue('F'.$cellcount, collect($data)->where('levelid',16)->pluck('registered')->sum('female').'/'.collect($data)->where('levelid',14)->pluck('registered')->sum('female'));
                    $sheet->setCellValue('G'.$cellcount,  collect($data)->where('levelid',16)->pluck('registered')->sum('total').'/'.collect($data)->where('levelid',14)->pluck('registered')->sum('total'));

                    $sheet->setCellValue('H'.$cellcount, collect($data)->where('levelid',16)->pluck('attendance')->sum('male').'/'.collect($data)->where('levelid',14)->pluck('attendance')->sum('male'));
                    $sheet->setCellValue('I'.$cellcount, collect($data)->where('levelid',16)->pluck('attendance')->sum('female').'/'.collect($data)->where('levelid',14)->pluck('attendance')->sum('female'));
                    $sheet->setCellValue('J'.$cellcount, collect($data)->where('levelid',16)->pluck('attendance')->sum('total').'/'.collect($data)->where('levelid',14)->pluck('attendance')->sum('total'));

                    $sheet->setCellValue('K'.$cellcount, '0/0');
                    $sheet->setCellValue('L'.$cellcount, '0/0');
                    $sheet->setCellValue('M'.$cellcount, '0/0');

                    $sheet->setCellValue('N'.$cellcount, collect($data)->where('levelid',16)->pluck('dropped_out_a')->sum('male').'/'.collect($data)->where('levelid',14)->pluck('dropped_out_a')->sum('male'));
                    $sheet->setCellValue('O'.$cellcount, collect($data)->where('levelid',16)->pluck('dropped_out_a')->sum('female').'/'.collect($data)->where('levelid',14)->pluck('dropped_out_a')->sum('female'));
                    $sheet->setCellValue('P'.$cellcount, collect($data)->where('levelid',16)->pluck('dropped_out_a')->sum('total').'/'.collect($data)->where('levelid',14)->pluck('dropped_out_a')->sum('total'));

                    $sheet->setCellValue('Q'.$cellcount, collect($data)->where('levelid',16)->pluck('dropped_out_b')->sum('male').'/'.collect($data)->where('levelid',14)->pluck('dropped_out_b')->sum('male'));
                    $sheet->setCellValue('R'.$cellcount, collect($data)->where('levelid',16)->pluck('dropped_out_b')->sum('female').'/'.collect($data)->where('levelid',14)->pluck('dropped_out_b')->sum('female'));
                    $sheet->setCellValue('S'.$cellcount, collect($data)->where('levelid',16)->pluck('dropped_out_b')->sum('total').'/'.collect($data)->where('levelid',14)->pluck('dropped_out_b')->sum('total'));

                    $droppedout_male_a1 = collect($data)->where('levelid',16)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b1 = collect($data)->where('levelid',16)->pluck('dropped_out_b')->sum('male');
                    $droppedout_male_a2 = collect($data)->where('levelid',14)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b2 = collect($data)->where('levelid',14)->pluck('dropped_out_b')->sum('male');

                    $droppedout_female_a1 = collect($data)->where('levelid',16)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b1 = collect($data)->where('levelid',16)->pluck('dropped_out_b')->sum('female');
                    $droppedout_female_a2 = collect($data)->where('levelid',14)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b2 = collect($data)->where('levelid',14)->pluck('dropped_out_b')->sum('female');

                    $droppedout_total_a1 = $droppedout_male_a1+$droppedout_female_a1;
                    $droppedout_total_b1 = $droppedout_male_b1+$droppedout_female_b1;
                    $droppedout_total_a2 = $droppedout_male_a2+$droppedout_female_a2;
                    $droppedout_total_b2 = $droppedout_male_b2+$droppedout_female_b2;
                    
                    $sheet->setCellValue('T'.$cellcount, ($droppedout_male_a1+$droppedout_male_b1).'/'.($droppedout_male_a2+$droppedout_male_b2));
                    $sheet->setCellValue('U'.$cellcount, ($droppedout_female_a1+$droppedout_female_b1).'/'.($droppedout_female_a2+$droppedout_female_b2));
                    $sheet->setCellValue('V'.$cellcount, ($droppedout_total_a1+$droppedout_total_b1).'/'.($droppedout_total_a2+$droppedout_total_b2));

                    $sheet->setCellValue('W'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_out_a')->sum('male').'/'.collect($data)->where('levelid',14)->pluck('transferred_out_a')->sum('male'));
                    $sheet->setCellValue('X'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_out_a')->sum('female').'/'.collect($data)->where('levelid',14)->pluck('transferred_out_a')->sum('female'));
                    $sheet->setCellValue('Y'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_out_a')->sum('total').'/'.collect($data)->where('levelid',14)->pluck('transferred_out_a')->sum('total'));

                    $sheet->setCellValue('Z'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_out_b')->sum('male').'/'.collect($data)->where('levelid',14)->pluck('transferred_out_b')->sum('male'));
                    $sheet->setCellValue('AA'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_out_b')->sum('female').'/'.collect($data)->where('levelid',14)->pluck('transferred_out_b')->sum('female'));
                    $sheet->setCellValue('AB'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_out_b')->sum('total').'/'.collect($data)->where('levelid',14)->pluck('transferred_out_b')->sum('total'));

                    $transferred_out_male_a1 = collect($data)->where('levelid',16)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b1 = collect($data)->where('levelid',16)->pluck('transferred_out_b')->sum('male');
                    $transferred_out_male_a2 = collect($data)->where('levelid',14)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b2 = collect($data)->where('levelid',14)->pluck('transferred_out_b')->sum('male');

                    $transferred_out_female_a1 = collect($data)->where('levelid',16)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b1 = collect($data)->where('levelid',16)->pluck('transferred_out_b')->sum('female');
                    $transferred_out_female_a2 = collect($data)->where('levelid',14)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b2 = collect($data)->where('levelid',14)->pluck('transferred_out_b')->sum('female');

                    $transferred_out_total_a1 = $transferred_out_male_a1+$transferred_out_female_a1;
                    $transferred_out_total_b1 = $transferred_out_male_b1+$transferred_out_female_b1;
                    $transferred_out_total_a2 = $transferred_out_male_a2+$transferred_out_female_a2;
                    $transferred_out_total_b2 = $transferred_out_male_b2+$transferred_out_female_b2;
                    
                    $sheet->setCellValue('AC'.$cellcount, ($transferred_out_male_a1+$transferred_out_male_b1).'/'.($transferred_out_male_a2+$transferred_out_male_b2));
                    $sheet->setCellValue('AD'.$cellcount, ($transferred_out_female_a1+$transferred_out_female_b1).'/'.($transferred_out_female_a2+$transferred_out_female_b2));
                    $sheet->setCellValue('AE'.$cellcount, ($transferred_out_total_a1+$transferred_out_total_b1).'/'.($transferred_out_total_a2+$transferred_out_total_b2));

                    $sheet->setCellValue('AF'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_in_a')->sum('male').'/'.collect($data)->where('levelid',14)->pluck('transferred_in_a')->sum('male'));
                    $sheet->setCellValue('AG'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_in_a')->sum('female').'/'.collect($data)->where('levelid',14)->pluck('transferred_in_a')->sum('female'));
                    $sheet->setCellValue('AH'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_in_a')->sum('total').'/'.collect($data)->where('levelid',14)->pluck('transferred_in_a')->sum('total'));

                    $sheet->setCellValue('AI'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_in_b')->sum('male').'/'.collect($data)->where('levelid',14)->pluck('transferred_in_b')->sum('male'));
                    $sheet->setCellValue('AJ'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_in_b')->sum('female').'/'.collect($data)->where('levelid',14)->pluck('transferred_in_b')->sum('female'));
                    $sheet->setCellValue('AK'.$cellcount, collect($data)->where('levelid',16)->pluck('transferred_in_b')->sum('total').'/'.collect($data)->where('levelid',14)->pluck('transferred_in_b')->sum('total'));

                    $transferred_in_male_a1 = collect($data)->where('levelid',16)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b1 = collect($data)->where('levelid',16)->pluck('transferred_in_b')->sum('male');
                    $transferred_in_male_a2 = collect($data)->where('levelid',14)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b2 = collect($data)->where('levelid',14)->pluck('transferred_in_b')->sum('male');

                    $transferred_in_female_a1 = collect($data)->where('levelid',16)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b1 = collect($data)->where('levelid',16)->pluck('transferred_in_b')->sum('female');
                    $transferred_in_female_a2 = collect($data)->where('levelid',14)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b2 = collect($data)->where('levelid',14)->pluck('transferred_in_b')->sum('female');

                    $transferred_in_total_a1 = $transferred_in_male_a1+$transferred_in_female_a1;
                    $transferred_in_total_b1 = $transferred_in_male_b1+$transferred_in_female_b1;
                    $transferred_in_total_a2 = $transferred_in_male_a2+$transferred_in_female_a2;
                    $transferred_in_total_b2 = $transferred_in_male_b2+$transferred_in_female_b2;
                    
                    $sheet->setCellValue('AL'.$cellcount, ($transferred_in_male_a1+$transferred_in_male_b1).'/'.($transferred_in_male_a2+$transferred_in_male_b2));
                    $sheet->setCellValue('AM'.$cellcount, ($transferred_in_female_a1+$transferred_in_female_b1).'/'.($transferred_in_female_a2+$transferred_in_female_b2));
                    $sheet->setCellValue('AN'.$cellcount, ($transferred_in_total_a1+$transferred_in_total_b1).'/'.($transferred_in_total_a2+$transferred_in_total_b2));

                    $cellcount+=1;

                // GRADE 6 - GRADE 12
                    $sheet->setCellValue('E'.$cellcount, collect($data)->where('levelid',9)->pluck('registered')->sum('male').'/'.collect($data)->where('levelid',15)->pluck('registered')->sum('male'));
                    $sheet->setCellValue('F'.$cellcount, collect($data)->where('levelid',9)->pluck('registered')->sum('female').'/'.collect($data)->where('levelid',15)->pluck('registered')->sum('female'));
                    $sheet->setCellValue('G'.$cellcount,  collect($data)->where('levelid',9)->pluck('registered')->sum('total').'/'.collect($data)->where('levelid',15)->pluck('registered')->sum('total'));

                    $sheet->setCellValue('H'.$cellcount, collect($data)->where('levelid',9)->pluck('attendance')->sum('male').'/'.collect($data)->where('levelid',15)->pluck('attendance')->sum('male'));
                    $sheet->setCellValue('I'.$cellcount, collect($data)->where('levelid',9)->pluck('attendance')->sum('female').'/'.collect($data)->where('levelid',15)->pluck('attendance')->sum('female'));
                    $sheet->setCellValue('J'.$cellcount, collect($data)->where('levelid',9)->pluck('attendance')->sum('total').'/'.collect($data)->where('levelid',15)->pluck('attendance')->sum('total'));

                    $sheet->setCellValue('K'.$cellcount, '0/0');
                    $sheet->setCellValue('L'.$cellcount, '0/0');
                    $sheet->setCellValue('M'.$cellcount, '0/0');

                    $sheet->setCellValue('N'.$cellcount, collect($data)->where('levelid',9)->pluck('dropped_out_a')->sum('male').'/'.collect($data)->where('levelid',15)->pluck('dropped_out_a')->sum('male'));
                    $sheet->setCellValue('O'.$cellcount, collect($data)->where('levelid',9)->pluck('dropped_out_a')->sum('female').'/'.collect($data)->where('levelid',15)->pluck('dropped_out_a')->sum('female'));
                    $sheet->setCellValue('P'.$cellcount, collect($data)->where('levelid',9)->pluck('dropped_out_a')->sum('total').'/'.collect($data)->where('levelid',15)->pluck('dropped_out_a')->sum('total'));

                    $sheet->setCellValue('Q'.$cellcount, collect($data)->where('levelid',9)->pluck('dropped_out_b')->sum('male').'/'.collect($data)->where('levelid',15)->pluck('dropped_out_b')->sum('male'));
                    $sheet->setCellValue('R'.$cellcount, collect($data)->where('levelid',9)->pluck('dropped_out_b')->sum('female').'/'.collect($data)->where('levelid',15)->pluck('dropped_out_b')->sum('female'));
                    $sheet->setCellValue('S'.$cellcount, collect($data)->where('levelid',9)->pluck('dropped_out_b')->sum('total').'/'.collect($data)->where('levelid',15)->pluck('dropped_out_b')->sum('total'));

                    $droppedout_male_a1 = collect($data)->where('levelid',9)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b1 = collect($data)->where('levelid',9)->pluck('dropped_out_b')->sum('male');
                    $droppedout_male_a2 = collect($data)->where('levelid',15)->pluck('dropped_out_a')->sum('male');
                    $droppedout_male_b2 = collect($data)->where('levelid',15)->pluck('dropped_out_b')->sum('male');

                    $droppedout_female_a1 = collect($data)->where('levelid',9)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b1 = collect($data)->where('levelid',9)->pluck('dropped_out_b')->sum('female');
                    $droppedout_female_a2 = collect($data)->where('levelid',15)->pluck('dropped_out_a')->sum('female');
                    $droppedout_female_b2 = collect($data)->where('levelid',15)->pluck('dropped_out_b')->sum('female');

                    $droppedout_total_a1 = $droppedout_male_a1+$droppedout_female_a1;
                    $droppedout_total_b1 = $droppedout_male_b1+$droppedout_female_b1;
                    $droppedout_total_a2 = $droppedout_male_a2+$droppedout_female_a2;
                    $droppedout_total_b2 = $droppedout_male_b2+$droppedout_female_b2;
                    
                    $sheet->setCellValue('T'.$cellcount, ($droppedout_male_a1+$droppedout_male_b1).'/'.($droppedout_male_a2+$droppedout_male_b2));
                    $sheet->setCellValue('U'.$cellcount, ($droppedout_female_a1+$droppedout_female_b1).'/'.($droppedout_female_a2+$droppedout_female_b2));
                    $sheet->setCellValue('V'.$cellcount, ($droppedout_total_a1+$droppedout_total_b1).'/'.($droppedout_total_a2+$droppedout_total_b2));

                    $sheet->setCellValue('W'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_out_a')->sum('male').'/'.collect($data)->where('levelid',15)->pluck('transferred_out_a')->sum('male'));
                    $sheet->setCellValue('X'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_out_a')->sum('female').'/'.collect($data)->where('levelid',15)->pluck('transferred_out_a')->sum('female'));
                    $sheet->setCellValue('Y'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_out_a')->sum('total').'/'.collect($data)->where('levelid',15)->pluck('transferred_out_a')->sum('total'));

                    $sheet->setCellValue('Z'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_out_b')->sum('male').'/'.collect($data)->where('levelid',15)->pluck('transferred_out_b')->sum('male'));
                    $sheet->setCellValue('AA'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_out_b')->sum('female').'/'.collect($data)->where('levelid',15)->pluck('transferred_out_b')->sum('female'));
                    $sheet->setCellValue('AB'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_out_b')->sum('total').'/'.collect($data)->where('levelid',15)->pluck('transferred_out_b')->sum('total'));

                    $transferred_out_male_a1 = collect($data)->where('levelid',9)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b1 = collect($data)->where('levelid',9)->pluck('transferred_out_b')->sum('male');
                    $transferred_out_male_a2 = collect($data)->where('levelid',15)->pluck('transferred_out_a')->sum('male');
                    $transferred_out_male_b2 = collect($data)->where('levelid',15)->pluck('transferred_out_b')->sum('male');

                    $transferred_out_female_a1 = collect($data)->where('levelid',9)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b1 = collect($data)->where('levelid',9)->pluck('transferred_out_b')->sum('female');
                    $transferred_out_female_a2 = collect($data)->where('levelid',15)->pluck('transferred_out_a')->sum('female');
                    $transferred_out_female_b2 = collect($data)->where('levelid',15)->pluck('transferred_out_b')->sum('female');

                    $transferred_out_total_a1 = $transferred_out_male_a1+$transferred_out_female_a1;
                    $transferred_out_total_b1 = $transferred_out_male_b1+$transferred_out_female_b1;
                    $transferred_out_total_a2 = $transferred_out_male_a2+$transferred_out_female_a2;
                    $transferred_out_total_b2 = $transferred_out_male_b2+$transferred_out_female_b2;
                    
                    $sheet->setCellValue('AC'.$cellcount, ($transferred_out_male_a1+$transferred_out_male_b1).'/'.($transferred_out_male_a2+$transferred_out_male_b2));
                    $sheet->setCellValue('AD'.$cellcount, ($transferred_out_female_a1+$transferred_out_female_b1).'/'.($transferred_out_female_a2+$transferred_out_female_b2));
                    $sheet->setCellValue('AE'.$cellcount, ($transferred_out_total_a1+$transferred_out_total_b1).'/'.($transferred_out_total_a2+$transferred_out_total_b2));

                    $sheet->setCellValue('AF'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_in_a')->sum('male').'/'.collect($data)->where('levelid',15)->pluck('transferred_in_a')->sum('male'));
                    $sheet->setCellValue('AG'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_in_a')->sum('female').'/'.collect($data)->where('levelid',15)->pluck('transferred_in_a')->sum('female'));
                    $sheet->setCellValue('AH'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_in_a')->sum('total').'/'.collect($data)->where('levelid',15)->pluck('transferred_in_a')->sum('total'));

                    $sheet->setCellValue('AI'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_in_b')->sum('male').'/'.collect($data)->where('levelid',15)->pluck('transferred_in_b')->sum('male'));
                    $sheet->setCellValue('AJ'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_in_b')->sum('female').'/'.collect($data)->where('levelid',15)->pluck('transferred_in_b')->sum('female'));
                    $sheet->setCellValue('AK'.$cellcount, collect($data)->where('levelid',9)->pluck('transferred_in_b')->sum('total').'/'.collect($data)->where('levelid',15)->pluck('transferred_in_b')->sum('total'));

                    $transferred_in_male_a1 = collect($data)->where('levelid',9)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b1 = collect($data)->where('levelid',9)->pluck('transferred_in_b')->sum('male');
                    $transferred_in_male_a2 = collect($data)->where('levelid',15)->pluck('transferred_in_a')->sum('male');
                    $transferred_in_male_b2 = collect($data)->where('levelid',15)->pluck('transferred_in_b')->sum('male');

                    $transferred_in_female_a1 = collect($data)->where('levelid',9)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b1 = collect($data)->where('levelid',9)->pluck('transferred_in_b')->sum('female');
                    $transferred_in_female_a2 = collect($data)->where('levelid',15)->pluck('transferred_in_a')->sum('female');
                    $transferred_in_female_b2 = collect($data)->where('levelid',15)->pluck('transferred_in_b')->sum('female');

                    $transferred_in_total_a1 = $transferred_in_male_a1+$transferred_in_female_a1;
                    $transferred_in_total_b1 = $transferred_in_male_b1+$transferred_in_female_b1;
                    $transferred_in_total_a2 = $transferred_in_male_a2+$transferred_in_female_a2;
                    $transferred_in_total_b2 = $transferred_in_male_b2+$transferred_in_female_b2;
                    
                    $sheet->setCellValue('AL'.$cellcount, ($transferred_in_male_a1+$transferred_in_male_b1).'/'.($transferred_in_male_a2+$transferred_in_male_b2));
                    $sheet->setCellValue('AM'.$cellcount, ($transferred_in_female_a1+$transferred_in_female_b1).'/'.($transferred_in_female_a2+$transferred_in_female_b2));
                    $sheet->setCellValue('AN'.$cellcount, ($transferred_in_total_a1+$transferred_in_total_b1).'/'.($transferred_in_total_a2+$transferred_in_total_b2));

                    $cellcount+=1;

                }
                
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="School Form 4.xlsx"');
                $writer->save("php://output");
    
            }
        
        }
    }
    public function reportschoolform_5(Request $request)
    {
        $id = $request->get('action');
        $selectedform = $request->get('selectedform');
        $syid = $request->get('syid');
        $sectionid = $request->get('sectionid');
        $gradelevelid = $request->get('levelid');
        // return $request->all();
        if($request->get('schoolhead') == true){
            if($request->get('schoolhead') != ""){
                DB::table('schoolinfo')
                    ->update([
                        'authorized'    => strtoupper($request->get('schoolhead'))
                    ]);
            }
        }
        $academicprogram = $request->get('academicprogram');
        $acadProg = DB::table('gradelevel')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id',$gradelevelid)
            ->get();
        $getSectionAndLevel = DB::table('sectiondetail')
            ->select(
                'teacher.id',
                'sections.levelid',
                'gradelevel.levelname',
                'sections.id as sectionid',
                'sections.sectionname'
                )
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->leftJoin('teacher','sectiondetail.teacherid','=','teacher.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->where('gradelevel.id',$gradelevelid)
            ->where('sections.id',$sectionid)
            ->where('sectiondetail.syid',$syid)
            ->distinct()
            ->get();
        // return $getSectionAndLevel;
        $getSy = DB::table('sy')
            ->where('id', $syid)
            ->get();
        $getSection = DB::table('sections')
            ->where('id', $sectionid)
            ->get();
        if(count($getSectionAndLevel)==0){
            return view("registrar.reportsschoolform5Apreview")
                ->with('selectedform',$request->get('selectedform'))
                ->with('selectedsection',$getSection[0]->sectionname)
                ->with('schoolyeardesc',$request->get('schoolyeardesc'))
                ->with('academicprogram',$academicprogram)
                ->with('message','No Teacher assigned!');
        }
        else{

            
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
                ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
                ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
                ->get();
    
            $getSchoolYear = DB::table('sy')
                ->select('id','sydesc')
                ->where('id',$syid)
                ->get();
            // return $getSchoolYear;
            if($selectedform == 'School Form 5A' || $selectedform == 'School Form 5B'){
                // return 'asd';
                // return $gradelevelid;
                $getStudents = DB::table('sh_enrolledstud')
                    ->select(
                        'sh_enrolledstud.id as enrollid',
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
                        'sh_enrolledstud.strandid',
                        'sh_enrolledstud.semid',
                        'sh_enrolledstud.sectionid as ensectid',
                        'sh_enrolledstud.levelid as enlevelid'
                        )
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->join('sy','sh_enrolledstud.syid','=','sy.id')
                    ->join('sectiondetail','sh_enrolledstud.sectionid','=','sectiondetail.sectionid')
                    ->join('sections','sectiondetail.sectionid','=','sections.id')
                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    // ->where('sectiondetail.teacherid',$teacherid)
                    ->where('sh_enrolledstud.sectionid',$sectionid)
                    ->where('sh_enrolledstud.levelid',$gradelevelid)
                    ->where('sh_enrolledstud.syid',$syid)
                    // // ->where('sy.id',$syid)
                    ->orderBy('studinfo.lastname','asc')
                    ->distinct()
                    ->get();
            }
            else{
                
                $getStudents = DB::table('enrolledstud')
                    ->select(
                        'studinfo.id',
                        'enrolledstud.studid',
                        'studinfo.lrn',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.acadprogid',
                        'enrolledstud.sectionid',
                        'enrolledstud.levelid',
                        'enrolledstud.sectionid as ensectid',
                        'enrolledstud.levelid as enlevelid',
                        'enrolledstud.promotionstatus'
                        )
                    ->join('sy','enrolledstud.syid','=','sy.id')
                    ->join('sectiondetail','enrolledstud.syid','=','sectiondetail.syid')
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    // ->where('sectiondetail.teacherid',$teacherid)
                    ->whereIn('studinfo.studstatus',[1,2,4])
                    ->where('enrolledstud.sectionid',$sectionid)
                    ->where('enrolledstud.levelid',$gradelevelid)
                    ->where('sectiondetail.syid',$syid)
                    ->distinct()
                    // ->groupBy('')
                    ->orderBy('studinfo.lastname','asc')
                    ->get();
                    
                // return ;
            }
            // return $getStudents;
            if(count($getStudents)==0){
                // return 'sdsf';
                if($selectedform == 'School Form 5A'){
                    // return 'afd';
                    return view("registrar.reportsschoolform5Apreview")
                        ->with('selectedform',$request->get('selectedform'))
                        ->with('selectedsection',$getSection[0]->sectionname)
                        ->with('schoolyeardesc',$request->get('schoolyeardesc'))
                        ->with('schoolyear',$syid)
                        ->with('academicprogram',$academicprogram)
                        ->with('message','No students enrolled!');
                }
                elseif($selectedform == 'School Form 5B'){
                    return view("registrar.reportsschoolform5Bpreview")
                        ->with('selectedform',$request->get('selectedform'))
                        ->with('selectedsection',$getSection[0]->sectionname)
                        ->with('schoolyeardesc',$request->get('schoolyeardesc'))
                        ->with('schoolyear',$syid)
                        ->with('academicprogram',$academicprogram)
                        ->with('message','No students enrolled!');
                }
                elseif($selectedform == 'School Form 5'){
                    // return
                    return view("registrar.forms.form5.reportsschoolform5preview")
                        ->with('selectedform',$request->get('selectedform'))
                        ->with('selectedsection',$getSection[0]->sectionname)
                        ->with('schoolyeardesc',$request->get('schoolyeardesc'))
                        ->with('schoolyear',$syid)
                        ->with('academicprogram',$academicprogram)
                        ->with('message','No students enrolled!');
                }
            }
            if($selectedform == 'School Form 5'){

                // $finalGrades = array();
                
                foreach($getStudents as $student){
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                    {
                        $grades = \App\Models\Principal\GenerateGrade::reportCardV5($student, true, 'sf9');  
                    }else{
                        $grades = GradesData::student_grades($syid,$sectionid,$student->id);
                    }
                    // $generateGrade = GenerateGrade::generalAverage($student);
                    // return $grades;
                    $generalaverage = 0;
                    if(count($grades)>0)
                    {
                        $failedsubjects = 0;

                        if(collect($grades)->where('quarter1','!=',null)->where('quarter2','!=',null)->where('quarter2','!=',null)->where('quarter4','!=',null)->count() == count($grades))
                        {

                            foreach($grades as $grade)
                            {
                                $finalrating = number_format(($grade->quarter1+$grade->quarter2+$grade->quarter3+$grade->quarter4)/4);
                                $grade->finalrating = $finalrating;
                                $grade->failed = 0;
                                if($finalrating<75)
                                {
                                    $failedsubjects+=1;
                                    $grade->failed = 1;
                                }
                                $generalaverage += $generalaverage;
                            }

                            if($student->promotionstatus == 1)
                            {
                                if($failedsubjects > 2)
                                {
                                    $student->promotionstat = 'PROMOTED';
                                }
                            }else{
                                if($failedsubjects > 2)
                                {
                                    $student->promotionstat = 'RETAINED';
                                }
                                elseif($failedsubjects == 2 || $failedsubjects == 1)
                                {
                                    $student->promotionstat = 'CONDITIONAL';
                                }else{
                                    $student->promotionstat = '';
                                }
                            }

                            $generalaverage = $generalaverage/count($grades);
                        }else{
                            foreach($grades as $grade)
                            {
                                $finalrating = 0;
                                $grade->failed = 0;
                                $grade->finalrating = $finalrating;
                            }
                            if($student->promotionstatus == 1)
                            {
                                    $student->promotionstat = 'PROMOTED';
                            }else{
                                    $student->promotionstat = '';
                            }
                        }
                        
                    }
                    $student->generalaverage = $generalaverage;
                    $student->grades = $grades;
                }
                
                $getTeacherName = DB::table('sectiondetail')
                    ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                    ->leftJoin('teacher','sectiondetail.teacherid','=','teacher.id')
                    ->where('sectiondetail.sectionid',$sectionid)
                    ->where('sectiondetail.syid',$syid)
                    ->first();
        
                $getPrincipal = DB::table('gradelevel')
                    ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                    ->leftJoin('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->leftJoin('teacher','academicprogram.principalid','=','teacher.id')
                    ->where('gradelevel.id',$getSectionAndLevel[0]->levelid)
                    ->get();
                    
                if($id == 'preview'){ 
                    
                    return view("registrar.forms.form5.reportsschoolform5preview")
                        ->with('school',$getSchoolInfo)
                        ->with('sy',$getSchoolYear[0]->sydesc)
                        ->with('gradeAndLevel',$getSectionAndLevel)
                        ->with('students',$getStudents)
                        ->with('gradelevelid',$gradelevelid)
                        ->with('sectionid',$sectionid)
                        // ->with('teacherid',$teacherid)
                        ->with('teachername',$getTeacherName)
                        ->with('selectedform',$request->get('selectedform'))
                        ->with('selectedsection',$getSection[0]->sectionname)
                        ->with('schoolyeardesc',$request->get('schoolyeardesc'))
                        ->with('schoolyear',$syid)
                        ->with('academicprogram',$academicprogram)
                        ->with('principalname',$getPrincipal);
        
                }
                elseif($id == 'export'){ 
                    // return 'adsad';
                    // $curriculum = $request->get()
                    $pdf = PDF::loadview('registrar/pdf/pdf_schoolform5preview',compact('getSchoolInfo','getSchoolYear','getSectionAndLevel','finalGrades','getTeacherName','getPrincipal','getStudents'))->setPaper('legal','landscape'); 
                    return $pdf->stream('School Form 5 - '.$getSectionAndLevel[0]->levelname.' - '.$getSectionAndLevel[0]->sectionname.'.pdf');
                }
            }
            elseif($selectedform == 'School Form 5A'){
                // return $getStudents;
                $getSemester = DB::table('semester')
                    ->select('id','semester')
                    ->where('isactive','1')
                    ->get();

                $getTeacherName = DB::table('sectiondetail')
                    ->select(
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.lastname',
                        'teacher.suffix'
                        )
                    ->leftJoin('teacher','sectiondetail.teacherid','=','teacher.id')
                    ->where('sectiondetail.sectionid',$sectionid)
                    ->get();

                $getPrincipal = DB::table('gradelevel')
                    ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->join('teacher','academicprogram.principalid','=','teacher.id')
                    ->where('gradelevel.id',$getSectionAndLevel[0]->levelid)
                    ->get();
                $strandids = array();
                foreach($getStudents as $student){
                    array_push($strandids, array(
                        'strandid' => $student->strandid
                    ));
                }
                $strandids = collect($strandids)->unique();
                // return $strandids;
                $trackAndStrands = array();
                foreach($strandids as $strandid){
                    
                    $strand = DB::table('sh_strand')
                        ->select('strandcode','trackname')
                        ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                        ->where('sh_strand.id',$strandid['strandid'])
                        ->get();
                    // return $strand;
                    array_push($trackAndStrands, array(
                        'track' => $strand[0]->trackname,
                        'strand' => $strand[0]->strandcode
                    ));
                }
                // return $trackAndStrands;
                // return $getStudents;
                $gradesArray = array();
                $firstsemgradesArray = array();
                $secondsemgradesArray = array();
                foreach($getStudents as $student){
                    if($student->semid == 1){
                        $sh_grades = DB::table('grades')
                            ->select('sh_subjects.subjtitle','gradesdetail.qg','grades.quarter')
                            ->join('gradesdetail','grades.id','=','gradesdetail.headerid')
                            ->join('sh_subjects','grades.subjid','=','sh_subjects.id')
                            ->where('grades.syid',$syid)
                            ->where('grades.levelid',$getSectionAndLevel[0]->levelid)
                            ->where('grades.sectionid',$getSectionAndLevel[0]->sectionid)
                            ->whereIn('grades.quarter',[1,2])
                            ->where('gradesdetail.enrollid',$student->enrollid)
                            // ->groupBy('subjtitle','qg')
                            ->get();
                            // return $sh_grades;
                        
                        $backSubjects = array();
                        foreach(collect($sh_grades->groupBy('subjtitle')) as $filtergrades){
                            // return $filtergrades;
                            if(count($filtergrades)==2){
                                if((($filtergrades[0]->qg + $filtergrades[1]->qg) / 2) < 75){
                                    array_push($backSubjects, $filtergrades[0]);
                                }
                            }
                        }
                        array_push($firstsemgradesArray, array(
                            'studentdata' =>  $student,
                            'backsubjects' => $backSubjects
                        ));
                    }
                    elseif($student->semid == 2){
                        $sh_grades = DB::table('grades')
                            ->select('sh_subjects.subjtitle','gradesdetail.qg','grades.quarter')
                            ->join('gradesdetail','grades.id','=','gradesdetail.headerid')
                            ->join('sh_subjects','grades.subjid','=','sh_subjects.id')
                            ->where('grades.syid',$syid)
                            ->where('grades.levelid',$getSectionAndLevel[0]->levelid)
                            ->where('grades.sectionid',$getSectionAndLevel[0]->sectionid)
                            ->whereIn('grades.quarter',[3,4])
                            ->where('gradesdetail.enrollid',$student->enrollid)
                            // ->groupBy('subjtitle','qg')
                            ->get();
                            // return ;
                        
                        $backSubjects = array();
                        foreach(collect($sh_grades->groupBy('subjtitle')) as $filtergrades){
                            // return $filtergrades;
                            if(count($filtergrades)==2){
                                if((($filtergrades[0]->qg + $filtergrades[1]->qg) / 2) < 75){
                                    array_push($backSubjects, $filtergrades[0]);
                                }
                            }
                        }
                        array_push($secondsemgradesArray, array(
                            'studentdata' =>  $student,
                            'backsubjects' => $backSubjects
                        ));
                    }
                    // return $sh_grades;
                }
                array_push($gradesArray,array(
                    'firstsem' => $firstsemgradesArray,
                    'secondsem' => $secondsemgradesArray
                ));
                // return count($gradesArray[0]['firstsem']);
                if($id == 'preview'){
                    // return $request->get('selectedform');
                    $generalAverage = array();
                    // return $getSectionAndLevel[0];
                    // return $gradesArray[0]['firstsem'][5];
                    return view('registrar.forms.form5.reportsschoolform5Apreview')
                        ->with('academicprogram',$request->get('academicprogram'))
                        ->with('selectedform',$request->get('selectedform'))
                        ->with('schoolyeardesc',$request->get('schoolyeardesc'))
                        ->with('selectedsection',$getSection[0]->sectionname)
                        ->with('school',$getSchoolInfo)
                        ->with('sy',$getSchoolYear)
                        ->with('gradeAndLevel',$getSectionAndLevel)
                        ->with('semester',$getSemester)
                        ->with('grades',$gradesArray[0])
                        ->with('students',$getStudents)
                        ->with('trackAndStrands',$trackAndStrands)
                        ->with('teachername',$getTeacherName)
                        ->with('principalname',$getPrincipal);
                }
                elseif($id == 'export'){
                    // returns
                    $divisionrep = $request->get('divisionrep');
                    $pdf = PDF::loadview('registrar/pdf/pdf_schoolform5a',compact('getSchoolInfo','getSchoolYear','getSectionAndLevel','gradesArray','getTeacherName','getPrincipal','getSemester','trackAndStrands','divisionrep'))->setPaper('legal','landscape');
        
                    return $pdf->stream('School Form 5A.pdf');
                }
            }
            elseif($selectedform == 'School Form 5B'){
                
                // return $getStudents;
                $getSemester = DB::table('semester')
                    ->select('id','semester')
                    ->where('isactive','1')
                    ->get();
                    
                $getTeacherName = DB::table('sectiondetail')
                    ->select(
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.lastname',
                        'teacher.suffix'
                        )
                    ->leftJoin('teacher','sectiondetail.teacherid','=','teacher.id')
                    ->where('sectiondetail.sectionid',$sectionid)
                    ->get();

                $getPrincipal = DB::table('gradelevel')
                    ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->join('teacher','academicprogram.principalid','=','teacher.id')
                    ->where('gradelevel.id',$getSectionAndLevel[0]->levelid)
                    ->get();
                $strandids = array();
                foreach($getStudents as $student){
                    array_push($strandids, array(
                        'strandid' => $student->strandid
                    ));
                }
                $strandids = collect($strandids)->unique();
                
                $trackAndStrands = array();
                foreach($strandids as $strandid){
                    
                    $strand = DB::table('sh_strand')
                        ->select('strandcode','trackname')
                        ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                        ->where('sh_strand.id',$strandid['strandid'])
                        ->get();
                    // return $strand;
                    array_push($trackAndStrands, array(
                        'track' => $strand[0]->trackname,
                        'strand' => $strand[0]->strandcode
                    ));
                }
                
                $getstudentsarray = array();
                foreach($getStudents as $student){
                    array_push($getstudentsarray,(object)array(
                        'studentid' => $student->id
                    ));
                }
                
                $finalstudents = collect($getstudentsarray)->unique('studentid');
                
                $filterArray = array();
                foreach($finalstudents as $finalstudent){

                    $eachinfo = array();
                    
                    foreach($getStudents as $student){
                        
                        if($student->id == $finalstudent->studentid){

                            $semcompleted = DB::table('sh_enrolledstud')
                                ->where('id',$student->enrollid)
                                ->where('syid',$syid)
                                ->distinct()
                                ->get();
                            
                            if(count($semcompleted)==4){
                                
                                foreach($semcompleted as $filtercompleted){
                                    $sh_grades = DB::table('grades')
                                        ->select('sh_subjects.subjtitle','gradesdetail.qg','grades.quarter')
                                        ->join('gradesdetail','grades.id','=','gradesdetail.headerid')
                                        ->join('sh_subjects','grades.subjid','=','sh_subjects.id')
                                        ->where('grades.syid',$syid)
                                        ->where('grades.levelid',$filtercompleted->levelid)
                                        ->where('grades.sectionid',$filtercompleted->sectionid)
                                        ->whereIn('grades.quarter',[1,2,3,4])
                                        ->where('gradesdetail.enrollid',$student->enrollid)
                                        // ->groupBy('subjtitle','qg')
                                        ->get();
                                        
                                        // return ;
                                    
                                    $backSubjects = array();
                                    foreach(collect($sh_grades->groupBy('subjtitle')) as $filtergrades){
                                        // return $filtergrades;
                                        if(count($filtergrades)==4){
                                            if((($filtergrades[0]->qg + $filtergrades[1]->qg) / 2) < 75){
                                                array_push($backSubjects, $filtergrades[0]);
                                            }
                                            if((($filtergrades[2]->qg + $filtergrades[3]->qg) / 2) < 75){
                                                array_push($backSubjects, $filtergrades[0]);
                                            }
                                        }
                                    }
                                    if(count($backSubjects)!=0){
                                        
                                        $student->status = 'COMPLETED';
                                        // array_push($eachinfo, array(
                                        //     'studentdata' =>  $student,
                                        //     'status' => 'COMPLETED'
                                        // ));
                                    }
                                }
                            }
                            elseif(count($semcompleted)>4){
                                $student->status = 'OVERSTAYING';
                                // array_push($eachinfo, array(
                                //     'studentdata' =>  $student,
                                //     'status' => 'OVERSTAYING'
                                // ));
                            }
                            elseif(count($semcompleted)<4){
                                $student->status = 'INCOMPLETE';
                                // array_push($eachinfo, array(
                                //     'studentdata' =>  $student,
                                //     'status' => 'INCOMPLETE'
                                // ));
                            }
                            array_push($filterArray, $student);
                        }
                        
                    }
                    

                }
                
                // return $filterArray[0][0]['studentdata']->lastname;
                if($id == 'preview'){
                    
                    $generalAverage = array();
                    // return $gradesArray[0]['firstsem'][5];
                    return view('registrar.forms.form5.reportsschoolform5Bpreview')
                        ->with('academicprogram',$request->get('academicprogram'))
                        ->with('selectedform',$request->get('selectedform'))
                        ->with('schoolyeardesc',$request->get('schoolyeardesc'))
                        ->with('selectedsection',$getSection[0]->sectionname)
                        ->with('school',$getSchoolInfo)
                        ->with('sy',$getSchoolYear)
                        ->with('gradeAndLevel',$getSectionAndLevel)
                        ->with('semester',$getSemester)
                        ->with('filter',$filterArray)
                        ->with('students',$getStudents)
                        ->with('trackAndStrands',$trackAndStrands)
                        ->with('teachername',$getTeacherName)
                        ->with('principalname',$getPrincipal);
                }
                elseif($id == 'export'){
                    // return 'adasd';
                    $certificationattained = array();
                    foreach($request->except('curriculum','teacher','schoolhead','divisionrep','nciiimale','nciiifemale','nciiitotal','nciimale','nciifemale','nciitotal','ncimale','ncifemale','ncitotal','nctotalmale','nctotalfemale','nctotal') as $key => $value){
                        // return $key;
                        array_push($certificationattained, (object) array(
                            'name' => $key,
                            'certificate' => $value
                        ));
                    }
                    $ncArray = array();
                    array_push($ncArray,(object)array(
                        'nciiimale' => $request->get('nciiimale'),
                        'nciiifemale' => $request->get('nciiifemale'),
                        'nciiitotal' => $request->get('nciiitotal'),
                        'nciimale' => $request->get('nciimale'),
                        'nciifemale' => $request->get('nciifemale'),
                        'nciitotal' => $request->get('nciitotal'),
                        'ncimale' => $request->get('ncimale'),
                        'ncifemale' => $request->get('ncifemale'),
                        'ncitotal' => $request->get('ncitotal'),
                        'nctotalmale' => $request->get('nctotalmale'),
                        'nctotalfemale' => $request->get('nctotalfemale'),
                        'nctotal' => $request->get('nctotal')
                    ));
                set_time_limit(1300);
                    $divisionrep = $request->get('divisionrep');
                    $pdf = PDF::loadview('registrar/pdf/pdf_schoolform5b',compact('getSchoolInfo','getSchoolYear','getSectionAndLevel','getTeacherName','getPrincipal','getSemester','trackAndStrands','divisionrep','filterArray','certificationattained','ncArray'))->setPaper('legal','landscape');
        
                    return $pdf->stream('School Form 5B.pdf');
                }
            }
    
        }
    }
    // function reportschoolform_6(Request $request, $id,$syid,$sectionid,$gradelevelid){ 
    function reportschoolform_6(Request $request, $id){ 
        // return $id;
        // $academicprogram = $request->get('academicprogram');
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
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->get();
        
        if($id == 'dashboard'){
            $sy = DB::table('sy')
                ->select('id','sydesc')
                ->where('isactive', 1)
                ->first();
        }else{
            $sy = DB::table('sy')
                ->select('id','sydesc')
                ->where('id', $request->get('selectedschoolyear'))
                ->first();
        }


        $gradelevels = DB::table('gradelevel')
                ->select('gradelevel.*','academicprogram.acadprogcode')
                ->join('academicprogram', 'gradelevel.acadprogid','=','academicprogram.id')
                ->where('gradelevel.deleted','0')
                ->orderBy('gradelevel.sortid', 'asc')
                ->get();

        $studentseachlevel = array();

        foreach($gradelevels as $gradelevel){
            if(strtolower($gradelevel->acadprogcode) == 'shs'){
                $studentseachgradelevel = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'sh_enrolledstud.studid',
                        'studinfo.lrn',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.acadprogid',
                        'sh_enrolledstud.sectionid',
                        'sh_enrolledstud.levelid',
                        'sh_enrolledstud.strandid',
                        'sections.blockid',
                        'studinfo.sectionid as ensectid',
                        'studinfo.levelid as enlevelid',
                        'gradelevel.acadprogid',
                        'sh_enrolledstud.promotionstatus'
                    )
                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                    ->where('sh_enrolledstud.levelid', $gradelevel->id)
                    ->where('sh_enrolledstud.syid', $sy->id)
                    ->where('studinfo.deleted','0')
                    ->where('sh_enrolledstud.deleted','0')
                    ->get();
                    
            }else{
                $studentseachgradelevel = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'enrolledstud.studid',
                        'studinfo.lrn',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.acadprogid',
                        'enrolledstud.sectionid',
                        'enrolledstud.levelid',
                        'sections.blockid',
                        'studinfo.sectionid as ensectid',
                        'studinfo.levelid as enlevelid',
                        'gradelevel.acadprogid',
                        'enrolledstud.promotionstatus'
                        )
                    ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->join('sections','enrolledstud.sectionid','=','sections.id')
                    ->where('enrolledstud.levelid', $gradelevel->id)
                    ->where('enrolledstud.syid', $sy->id)
                    ->where('studinfo.deleted','0')
                    ->where('enrolledstud.deleted','0')
                    ->get();
                    
            }
            array_push($studentseachlevel,(object)array(
                'gradelevelinfo'    => $gradelevel,
                'students'          => $studentseachgradelevel
            ));
        }
        // $finalGrades = array();
        foreach($studentseachlevel as $student){
            if(count($student->students) > 0){

                foreach($student->students as $stud){
                    $stud->gendersort = strtoupper($stud->gender);
                    if(collect($stud)->has('strandid'))
                    {
                        $strand = $stud->strandid;
                    }else{
                        
                    $strand = 0;
                    }
                    $generalaverage = array();
                    if($stud->promotionstatus == 0){
                        $stud->promstat = "";
                    }else{
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'csl')
                        {
                            $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                    
                            if($stud->levelid == 14 || $stud->levelid == 15){
                                if($grading_version->version == 'v2'){
                                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $stud->levelid,$stud->id,$sy->id,$strand,null,$stud->sectionid);
                                }else{
                                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $stud->levelid,$stud->id,$sy->id,$strand,null,$stud->sectionid);
                                }
                                $temp_grades = array();
                                $finalgrade = array();
                                foreach($studgrades as $item){
                                    if($item->id == 'G1'){
                                        array_push($finalgrade,$item);
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
                                $generalaverage =  $finalgrade;
                            }else{
                                if($grading_version->version == 'v2'){
                                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $stud->levelid,$stud->id,$sy->id,null,null,$stud->sectionid);
                                }else{
                                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $stud->levelid,$stud->id,$sy->id,null,null,$stud->sectionid);
                                }
                                
                                
                                $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel);
                                $grades = $studgrades;
                                $grades = collect($grades)->sortBy('sortid')->values();
                                $finalgrade = collect($grades)->where('id','G1')->values();
                                unset($grades[count($grades)-1]);
                                $studgrades = collect($grades)->where('isVisible','1')->values();
                                $generalaverage =  $finalgrade;
                            }

                        }else{
                
                            $schoolyear = DB::table('sy')->where('id',$sy->id)->first();
                            Session::put('schoolYear', $schoolyear);
                            $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $stud->levelid,$stud->id,$sy->id,$strand,null,$stud->sectionid);
                            // $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $sy->levelid,$studinfo->id,$sy->syid,$sy->strandid,null,$sy->ensectid,true);
            
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
                            $generalaverage = collect($generalaverage)->where('semid',DB::table('semester')->where('isactive','1')->first()->id)->values();
                            $temp_grades = collect($temp_grades)->where('semid',DB::table('semester')->where('isactive','1')->first()->id)->values();
                            $studgrades = $temp_grades;
                            $grades = collect($studgrades)->sortBy('sortid')->values();
                            
                            if(count($grades) == 0){
                                $stud->promstat = "";
                            }else{
                                $numFailed = count(collect($generalaverage)->where('actiontaken','FAILED'));
                                
                                if($numFailed == 0){
                                    $stud->promstat = "PROMOTED";
                                }
                                elseif($numFailed == 2 || $numFailed == 1) {
                                    $stud->promstat = "IRREGULAR";
                                }
                                elseif($numFailed >= 3){
                                    $stud->promstat = "RETAINED";
                                }
                            }
                        }
                    }

                    if(count($generalaverage) == 0)
                    {
                        $stud->proficiency = "";
                    }else{
                        if($generalaverage[0]->finalrating == 0){
                            $stud->proficiency = "";
                        }elseif($generalaverage[0]->finalrating <= 74){
                            $stud->proficiency = "B";
                        }elseif($generalaverage[0]->finalrating >= 75 && $generalaverage[0]->finalrating <= 79){
                            $stud->proficiency = "D";
                        }elseif($generalaverage[0]->finalrating >= 80 && $generalaverage[0]->finalrating <= 84){
                            $stud->proficiency = "AP";
                        }elseif($generalaverage[0]->finalrating >= 85 && $generalaverage[0]->finalrating <= 89){
                            $stud->proficiency = "P";
                        }elseif($generalaverage[0]->finalrating >= 90){
                            $stud->proficiency = "A";
                        }
                    }
                }
            }
            // PROMOTED
                $student->promoted = count(collect($student->students)->where('promstat','PROMOTED'));
                $student->promotedmale = count(collect($student->students)->where('promstat','PROMOTED')->where('gendersort','MALE'));
                $student->promotedfemale = count(collect($student->students)->where('promstat','PROMOTED')->where('gendersort','FEMALE'));
            // IRREGULAR
                $student->irregular = count(collect($student->students)->where('promstat','IRREGULAR'));
                $student->irregularmale = count(collect($student->students)->where('promstat','IRREGULAR')->where('gendersort','MALE'));
                $student->irregularfemale = count(collect($student->students)->where('promstat','IRREGULAR')->where('gendersort','FEMALE'));
            // RETAINED
                $student->retained = count(collect($student->students)->where('promstat','RETAINED'));
                $student->retainedmale = count(collect($student->students)->where('promstat','RETAINED')->where('gendersort','MALE'));
                $student->retainedfemale = count(collect($student->students)->where('promstat','RETAINED')->where('gendersort','FEMALE'));
            
            // LEVEL OF PROFICIENCY: BEGINNNING (B: 74% and below)
                $student->proficiencyb = count(collect($student->students)->where('proficiency','B'));
                $student->proficiencybmale = count(collect($student->students)->where('proficiency','B')->where('gendersort','MALE'));
                $student->proficiencybfemale = count(collect($student->students)->where('proficiency','B')->where('gendersort','FEMALE'));
            // LEVEL OF PROFICIENCY: DEVELOPING (D: 75%-79%)
                $student->proficiencyd = count(collect($student->students)->where('proficiency','D'));
                $student->proficiencydmale = count(collect($student->students)->where('proficiency','D')->where('gendersort','MALE'));
                $student->proficiencydfemale = count(collect($student->students)->where('proficiency','D')->where('gendersort','FEMALE'));
            // LEVEL OF PROFICIENCY: APPROACHING PROFICIENCY (AP: 80%-84%)
                $student->proficiencyap = count(collect($student->students)->where('proficiency','AP'));
                $student->proficiencyapmale = count(collect($student->students)->where('proficiency','AP')->where('gendersort','MALE'));
                $student->proficiencyapfemale = count(collect($student->students)->where('proficiency','AP')->where('gendersort','FEMALE'));
            // LEVEL OF PROFICIENCY: PROFICIENT (P: 85% -89%)
                $student->proficiencyp = count(collect($student->students)->where('proficiency','P'));
                $student->proficiencypmale = count(collect($student->students)->where('proficiency','P')->where('gendersort','MALE'));
                $student->proficiencypfemale = count(collect($student->students)->where('proficiency','P')->where('gendersort','FEMALE'));
            // LEVEL OF PROFICIENCY: ADVANCED (A: 90% and above)
                $student->proficiencya = count(collect($student->students)->where('proficiency','A'));
                $student->proficiencyamale = count(collect($student->students)->where('proficiency','A')->where('gendersort','MALE'));
                $student->proficiencyafemale = count(collect($student->students)->where('proficiency','A')->where('gendersort','FEMALE'));


        }
        // return $studentseachlevel;
        if($id == 'dashboard' || $id == 'changeschoolyear'){
            // return count($finalGrades);
            $schoolyears = DB::table('sy')
                ->get();

            return view("registrar.reportsschoolform6preview")
                ->with('school',$getSchoolInfo)
                ->with('selectedschoolyear',$sy)
                ->with('schoolyears', $schoolyears)
                ->with('studentseachlevel', $studentseachlevel);
        }
        elseif($id == 'print'){
            $schoolhead = $request->get('formschoolhead');
            $divrep     = $request->get('formdivrep');
            $divsup     = $request->get('formdivsup');
            // $pdf = PDF::loadview('registrar/pdf/pdf_schoolform6preview',compact('studentseachlevel','getSchoolInfo','sy','schoolhead','divrep','divsup'))->setPaper('8.5x14','landscape');
            $pdf = PDF::loadview('registrar/forms/deped/form6',compact('studentseachlevel','getSchoolInfo','sy','schoolhead','divrep','divsup'))->setPaper('8.5x14','landscape');

            return $pdf->stream('School Form 6 SY'.$sy->sydesc.'.pdf');
        }
    }
    function reports_schoolform9(Request $request, $id,$syid,$sectionid,$gradelevelid){ 

        $students = array();

        $acadprogcode = Db::table('gradelevel')
            ->select(
                'acadprogcode'
            )
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id',$gradelevelid)
            ->first();

        if(strtolower($acadprogcode->acadprogcode) == 'shs'){


            $getstudents = Db::table('studinfo')
                ->select(
                    'studinfo.id',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'gradelevel.sortid',
                    'gradelevel.levelname'
                )
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->join('gradelevel', 'sh_enrolledstud.levelid','=','gradelevel.id')
                ->where('sh_enrolledstud.syid',$syid)
                ->where('gradelevel.id',$gradelevelid)
                ->where('sh_enrolledstud.sectionid',$sectionid)
				->where('sh_enrolledstud.studstatus','!=','0')
                ->where('sh_enrolledstud.deleted','0')
                ->where('studinfo.deleted','0')
                ->orderBy('gradelevel.sortid','asc')
                ->distinct()
                ->get();

        }else{

            $getstudents = Db::table('studinfo')
                ->select(
                    'studinfo.id',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'gradelevel.sortid',
                    'gradelevel.levelname'
                )
                ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                ->join('gradelevel', 'enrolledstud.levelid','=','gradelevel.id')
                ->where('enrolledstud.syid',$syid)
                ->where('gradelevel.id',$gradelevelid)
                ->where('enrolledstud.sectionid',$sectionid)
				->where('enrolledstud.studstatus','!=','0')
                ->where('gradelevel.deleted','0')
                ->orderBy('gradelevel.sortid','asc')
                ->where('enrolledstud.deleted','0')
                ->where('studinfo.deleted','0')
                ->distinct()
                ->get();

        }

        if(count($getstudents) > 0){

            foreach($getstudents as $getstudent){
                
                array_push($students, $getstudent);

            }

        }
        return view('registrar.reportsschoolform9preview')
            ->with('students', $students)
            ->with('academicprogram', $request->get('academicprogram'))
            ->with('selectedform', $request->get('selectedform'))
            ->with('schoolyeardesc', $request->get('schoolyeardesc'))
            ->with('schoolyear', $syid)
            ->with('sectionid', $sectionid)
            ->with('gradelevelid', $gradelevelid);

    }
    public function printablecertificationindex(Request $request)
    { 
        $students1   = DB::table('studinfo')
            ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender')
            ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
            ->where('studinfo.deleted','0')
            ->where('enrolledstud.deleted','0')
            ->where('enrolledstud.studstatus','>',0)
            ->orderBy('lastname','asc')
            ->get();

        $students2   = DB::table('studinfo')
            ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','sh_strand.strandname','studinfo.gender')
            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
            ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
            ->where('studinfo.deleted','0')
            ->where('sh_enrolledstud.deleted','0')
            ->where('sh_enrolledstud.studstatus','>',0)
            ->orderBy('lastname','asc')
            ->get();

        $students3   = DB::table('studinfo')
            ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','college_courses.courseDesc as strandname','studinfo.gender')
            ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
            ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
            ->where('studinfo.deleted','0')
            ->where('college_enrolledstud.deleted','0')
            ->where('college_enrolledstud.studstatus','>',0)
            ->orderBy('lastname','asc')
            ->get();

        $students = collect();
        $students = $students->merge($students1);
        $students = $students->merge($students2);
        $students = $students->merge($students3);
        $students = $students->sortByDesc('syid');
        $students = $students->unique('id');
        $students = $students->values();

        if(count($students)>0)
        {
            foreach($students as $student)
            {
                $student->sortname = $student->lastname.', '.$student->firstname; 
            }
        }
        $students = collect($students)->sortBy('sortname');

        if($request->has('type'))
        {
            if($request->get('type') == 'goodmoral')
            {
                return view('registrar.otherprintables.cogm.index')
                    ->with('students', $students);
            }
            elseif($request->get('type') == 'graduation')
            {
                if(count($students3)>0)
                {
                    foreach($students3 as $student)
                    {
                        $student->sortname = $student->lastname.', '.$student->firstname; 
                    }
                }
                $students3 = collect($students3)->unique()->values()->all();
                
                return view('registrar.otherprintables.cograduation.index')
                    ->with('students', $students3);
            }
        }else{
            return view('registrar.otherprintables.index')
                ->with('students', $students);
        }
    }
    public function printablecertificationgenerate(Request $request)
    {
        $givendate = $request->get('givendate');
        $syinfo = DB::table('sy')
            ->where('id', $request->get('syid'))
            ->first();

        $semesterinfo = DB::table('semester')
            ->where('id', $request->get('semid'))
            ->first();
            
        $schoolinfo = DB::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region',
                'schoolinfo.abbreviation'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();
        
        // if($request->has('schoolregistrar-withunits'))
        // {
        //     $schoolregistrar = $request->get('schoolregistrar-withunits');
        // }else{
            $schoolregistrar = $request->get('schoolregistrar');
        // }
        
        $formname = '';
        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'nsdphs')
        {
            if($request->get('template') == 'elem')
            {
                $formname = 'coe_elem';    
    
            }elseif($request->get('template') == 'jhs')
            {
                $formname = 'coe_jhs';    
    
            }elseif($request->get('template') == 'shs')
            {
                $formname = 'coe_shs';    
    
            }else{
                $formname = 'coe_college';    
            }
            // return $request->get('sig-name');
            $signatories = array();
            if($request->has('sig-name'))
            {
                foreach($request->get('sig-name') as $keysig=>$eachsign)
                {
                    array_push($signatories, (object)array(
                        'id'        => $request->get('sig-id')[$keysig],
                        'name'        => $eachsign,
                        'label'        => $request->get('sig-label')[$keysig],
                    ));
                }
                if(count($signatories)>0)
                {
                    foreach($signatories as $signatory)
                    {
                        if($signatory->id == 0 || $signatory->id == null)
                        {                            
                            DB::table('signatory')
                            ->insert([
                                'form'      => $formname,
                                'name'      => $signatory->name,
                                'description'      => $signatory->label,
                                'createdby' => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                        }else{                            
                            DB::table('signatory')
                            ->where('id', $signatory->id)
                            ->update([
                                'name'      => $signatory->name,
                                'description'      => $signatory->label,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }
            }
            $signatoryinfo = DB::table('signatory')
                ->where('form', $formname)
                ->where('deleted', 0)
                ->get();

        }else{
            if($request->get('template') == 'elem' || $request->get('template') == 'jhs')
            {
                if($request->get('template') == 'elem')
                {
                    $formname = 'reg_coe_elem';
                }else{
                    $formname = 'reg_coe_jhs';
                }
                $signatorylabel = $request->get('signatorylabel');
                
                $checkifexistregistrar = DB::table('signatory')
                ->where('form',$formname)
                ->where('deleted','0')
                ->first();
                
                if($checkifexistregistrar)
                {
                    if($schoolregistrar == null || $schoolregistrar == "")
                    {
                        $schoolregistrar = $checkifexistregistrar->name;
                    }else{
                        if($schoolregistrar != $checkifexistregistrar->name || $signatorylabel != $checkifexistregistrar->title)
                        {                   
                            if($request->has('signatorylabel'))
                            {
                                DB::table('signatory')
                                    ->where('id', $checkifexistregistrar->id)
                                    ->update([
                                        'name'      => $schoolregistrar,
                                        'title'      => $signatorylabel,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                }else{
                    if($schoolregistrar != null || $schoolregistrar != "")
                    {                 
                        if($request->has('signatorylabel'))
                        {
                            DB::table('signatory')
                                ->insert([
                                    'form'      => $formname,
                                    'name'      => $schoolregistrar,
                                    'title'      => $signatorylabel,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }
            }elseif($request->get('template') == 'shs')
            {
                $formname = 'reg_coe_shs';
                $signatorylabel = $request->get('signatorylabel');
                $checkifexistregistrar = DB::table('signatory')
                    ->where('form',$formname)
                    ->where('deleted','0')
                    ->first();
    
                if($checkifexistregistrar)
                {
                    if($schoolregistrar == null || $schoolregistrar == "")
                    {
                        $schoolregistrar = $checkifexistregistrar->name;
                    }else{
                        if($schoolregistrar != $checkifexistregistrar->name || $signatorylabel != $checkifexistregistrar->title)
                        {           
                            if($request->has('signatorylabel'))
                            {
                                DB::table('signatory')
                                    ->where('id', $checkifexistregistrar->id)
                                    ->update([
                                        'name'      => $schoolregistrar,
                                        'title'      => $signatorylabel,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                }else{
                    if($schoolregistrar != null || $schoolregistrar != "")
                    {
                        if($request->has('signatorylabel'))
                        {
                            DB::table('signatory')
                                ->insert([
                                    'form'      => $formname,
                                    'name'      => $schoolregistrar,
                                    'title'      => $signatorylabel,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }
    
            }else{
                $formname = 'reg_coe_college';
                $signatorylabel = $request->get('signatorylabel');
                $checkifexistregistrar = DB::table('signatory')
                    ->where('form',$formname)
                    ->where('deleted','0')
                    ->first();
    
                if($checkifexistregistrar)
                {
                    if($schoolregistrar == null || $schoolregistrar == "" || $schoolregistrar == " ")
                    {
                        $schoolregistrar = $checkifexistregistrar->name;
                        DB::table('signatory')
                            ->where('id', $checkifexistregistrar->id)
                            ->update([
                                'name'      => null,
                                'title'      => $signatorylabel,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }else{
                        if($schoolregistrar != $checkifexistregistrar->name || $signatorylabel != $checkifexistregistrar->title)
                        {
                            if($request->has('signatorylabel'))
                            {
                                DB::table('signatory')
                                    ->where('id', $checkifexistregistrar->id)
                                    ->update([
                                        'name'      => $schoolregistrar,
                                        'title'      => $signatorylabel,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                }else{
                    if($schoolregistrar != null || $schoolregistrar != "")
                    {
                        if($request->has('signatorylabel'))
                        {
                            DB::table('signatory')
                                ->insert([
                                    'form'      => $formname,
                                    'name'      => $schoolregistrar,
                                    'title'      => $signatorylabel,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }
            }
            $signatoryinfo = DB::table('signatory')
            ->where('form',$formname)
            ->where('deleted','0')
            ->first();
        }
        
        if(!$request->has('export'))
        {

            if($request->get('template') == 'elem' || $request->get('template') == 'jhs')
            {
                // return collect($signatoryinfo);

                $studentinfo =  DB::table('studinfo')
                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.sid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.levelid','gradelevel.levelname','sections.sectionname','gradelevel.acadprogid')
                    ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                    ->leftJoin('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->leftJoin('sections','enrolledstud.sectionid','=','sections.id')
                    ->where('studinfo.id',$request->get('studid'))
                    ->where('enrolledstud.syid',$request->get('syid'))
                    ->first();
                    
                return view('registrar.otherprintables.templatejhs')
                ->with('studentinfo', $studentinfo)
                ->with('semesterinfo', $semesterinfo)
                ->with('template', $request->get('template'))
                ->with('syinfo', $syinfo)
                ->with('signatoryinfo', $signatoryinfo)
                ->with('schoolregistrar', $schoolregistrar)
                ->with('givendate',$givendate)
                ->with('schoolinfo', $schoolinfo);
    
            }elseif($request->get('template') == 'shs')
            {                    
                $studentinfo   = DB::table('studinfo')
                ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.levelid','sh_strand.strandname','sh_strand.strandcode','gradelevel.levelname','sections.sectionname')
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->leftJoin('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                ->leftJoin('sections','sh_enrolledstud.sectionid','=','sections.id')
                ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                ->where('studinfo.deleted','0')
                ->where('sh_enrolledstud.deleted','0')
                ->where('sh_enrolledstud.syid',$request->get('syid'))
                ->where('sh_enrolledstud.semid',$request->get('semid'))
                ->where('studinfo.id',$request->get('studid'))
                ->first();
                return view('registrar.otherprintables.templateshs')
                    ->with('studentinfo', $studentinfo)
                    ->with('semesterinfo', $semesterinfo)
                    ->with('template', $request->get('template'))
                    ->with('givendate',$givendate)
                    ->with('syinfo', $syinfo)
                    ->with('signatoryinfo', $signatoryinfo)
                    ->with('schoolregistrar', $schoolregistrar)
                    ->with('schoolinfo', $schoolinfo);
            }else{                
                    $studentinfo   = DB::table('studinfo')
                    ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.levelid','college_courses.courseDesc as strandname','college_courses.courseabrv as strandcode','gradelevel.levelname','college_sections.sectionDesc as sectionname','college_colleges.collegeDesc as collegename')
                    ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                    ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                    ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
                    ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
                    ->join('college_colleges','college_courses.collegeid','=','college_colleges.id')
                    ->where('studinfo.deleted','0')
                    ->where('college_enrolledstud.deleted','0')
                    ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                    ->where('college_enrolledstud.syid',$request->get('syid'))
                    ->where('college_enrolledstud.semid',$request->get('semid'))
                    ->where('studinfo.id',$request->get('studid'))
                    ->first();

                    $schoolyears = Db::table('sy')
                    ->select('id as syid','sydesc'
                    ,'isactive'
                    )
                    ->where('id',$request->get('syid'))
                    ->get();
                    $subjects = array();
                    if($studentinfo)
                    {
                        $subjects = collect(\App\Models\College\TOR::getrecords($studentinfo->id, $schoolyears))->where('semid',$request->get('semid'))->where('syid',$request->get('syid'))->values();
                        // return $subjects;
                        $subjects = collect($subjects[0]->subjdata) ?? array();
                    }
                    
                    
                    return view('registrar.otherprintables.templatecollege')
                        ->with('subjects', $subjects)
                        ->with('studentinfo', $studentinfo)
                        ->with('semesterinfo', $semesterinfo)
                        ->with('template', $request->get('template'))
                        ->with('givendate',$givendate)
                        ->with('syinfo', $syinfo)
                        ->with('schoolregistrar', $schoolregistrar)
                        ->with('schoolinfo', $schoolinfo);
            }
        }else{
            if($request->get('template') == 'jhs' || $request->get('template') == 'elem')
            {
                $studentinfo =  DB::table('studinfo')
                    ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.levelid','gradelevel.levelname','sections.sectionname','gradelevel.acadprogid','studinfo.gender')
                    ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                    ->leftJoin('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->leftJoin('sections','enrolledstud.sectionid','=','sections.id')
                    ->where('studinfo.id',$request->get('studid'))
                    ->where('enrolledstud.syid',$request->get('syid'))
                    ->first();
                    
                $purpose = $request->get('purpose');
                $signatorylabel = $request->get('signatorylabel');
                
                $pdf = PDF::loadview('registrar.otherprintables.pdf_templatejhs',compact('studentinfo','syinfo','schoolinfo','semesterinfo','givendate','schoolregistrar','purpose','signatorylabel','signatoryinfo'))->setPaper('letter');

                return $pdf->stream('Certification - '.$studentinfo->lastname.', '.$studentinfo->firstname.' - '.$syinfo->sydesc.'.pdf');

            }elseif($request->get('template') == 'shs')
            {
                    
                $studentinfo   = DB::table('studinfo')
                ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.levelid','sh_strand.strandname','sh_strand.strandcode','gradelevel.levelname','sections.sectionname','studinfo.gender')
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->leftJoin('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                ->leftJoin('sections','sh_enrolledstud.sectionid','=','sections.id')
                ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                ->where('studinfo.deleted','0')
                ->where('sh_enrolledstud.deleted','0')
                ->where('sh_enrolledstud.syid',$request->get('syid'))
                ->where('sh_enrolledstud.semid',$request->get('semid'))
                ->where('studinfo.id',$request->get('studid'))
                ->first();
                $signatorylabel = $request->get('signatorylabel');
                $pdf = PDF::loadview('registrar.otherprintables.pdf_templateshs',compact('studentinfo','syinfo','schoolinfo','semesterinfo','givendate','schoolregistrar','signatorylabel','signatoryinfo'))->setPaper('letter');

                    return $pdf->stream('Certification - '.$studentinfo->lastname.', '.$studentinfo->firstname.' - '.$syinfo->sydesc.'.pdf');
            }else{
                
                $studentinfo   = DB::table('studinfo')
                ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.levelid','college_courses.courseDesc as strandname','college_courses.courseabrv as strandcode','gradelevel.levelname','college_sections.sectionDesc as sectionname','college_colleges.collegeDesc as collegename','college_year.yearDesc as yearlevel')
                ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                ->leftJoin('college_year','college_enrolledstud.yearLevel','=','college_year.levelid')
                ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
                ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
                ->join('college_colleges','college_courses.collegeid','=','college_colleges.id')
                ->where('studinfo.deleted','0')
                ->where('college_enrolledstud.deleted','0')
                ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                ->where('college_enrolledstud.syid',$request->get('syid'))
                ->where('college_enrolledstud.semid',$request->get('semid'))
                ->where('studinfo.id',$request->get('studid'))
                ->first();

                $tabletemplate = $request->get('exporttype');
                $schoolyears = Db::table('sy')
                ->select('id as syid','sydesc'
                ,'isactive'
                )
                ->where('id',$request->get('syid'))
                ->get();
                $subjects = \App\Models\College\TOR::getrecords($studentinfo->id, $schoolyears);
                $subjects = $subjects[0]->subjdata;
                $purpose = $request->get('purpose');
                $pdf = PDF::loadview('registrar.otherprintables.pdf_templatecollege',compact('studentinfo','syinfo','schoolinfo','semesterinfo','givendate','schoolregistrar','tabletemplate','subjects','purpose','signatoryinfo'))->setPaper('letter');

                return $pdf->stream('Certificate of Enrollment - '.$studentinfo->lastname.', '.$studentinfo->firstname.' - '.$syinfo->sydesc.'.pdf');
            }
        }
    }
    public function printablecertificationgoodmoral(Request $request)
    {
        // if($request->get('action') == 'filter')
        // {
            $givendate = $request->get('givendate');
            $syinfo = DB::table('sy')
                ->where('id', $request->get('syid'))
                ->first();
    
            $semesterinfo = DB::table('semester')
                ->where('id', $request->get('semid'))
                ->first();
                
            $schoolinfo = DB::table('schoolinfo')
                ->select(
                    'schoolinfo.schoolid',
                    'schoolinfo.schoolname',
                    'schoolinfo.authorized',
                    'refcitymun.citymunDesc as division',
                    'schoolinfo.district',
                    'schoolinfo.address',
                    'schoolinfo.picurl',
                    'refregion.regDesc as region',
                    'schoolinfo.abbreviation'
                )
                ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
                ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
                ->first();
            
            $schoolregistrar = $request->get('schoolregistrar');
            
            $formname = '';
            if($request->get('template') == 'jhs')
            {
                $formname = 'goodmoral_jhs';    
    
            }elseif($request->get('template') == 'shs')
            {
                $formname = 'goodmoral_shs';    
    
            }else{
                $formname = 'goodmoral_college';    
            }
            
            $signatories = array();
            if($request->has('sig-name'))
            {
                foreach($request->get('sig-name') as $keysig=>$eachsign)
                {
                    if($eachsign != null &&  $request->get('sig-label')[$keysig] != null)
                    {
                        array_push($signatories, (object)array(
                            'id'        => $request->get('sig-id')[$keysig],
                            'name'        => $eachsign,
                            'label'        => $request->get('sig-label')[$keysig],
                        ));
                    }
                }
                if(count($signatories)>0)
                {
                    foreach($signatories as $signatory)
                    {
                        if($signatory->id == 0 || $signatory->id == null)
                        {                 
                            $checkifexists = DB::table('signatory')
                            ->where('name', 'like', '%'.$signatory->name.'%')
                            ->where('description', 'like', '%'.$signatory->label.'%')
                            ->where('deleted','0')
                            ->where('form',$formname)
                            ->first();
                            if(!$checkifexists)
                            {
                                DB::table('signatory')
                                ->insert([
                                    'form'      => $formname,
                                    'name'      => $signatory->name,
                                    'description'      => $signatory->label,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime'   => date('Y-m-d H:i:s')
                                ]);
                            }
                        }else{                            
                            DB::table('signatory')
                            ->where('id', $signatory->id)
                            ->update([
                                'name'      => $signatory->name,
                                'description'      => $signatory->label,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }
            }
            $signatories = DB::table('signatory')
                ->select('id','name','description')
                ->where('form', $formname)
                ->where('name', '!=',null)
                ->where('description', '!=',null)
                ->where('deleted', 0)
                ->get();
            if(!$request->has('export'))
            {
                if($request->get('template') == 'jhs')
                {        
                    $studentinfo =  DB::table('studinfo')
                        ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.levelid','gradelevel.levelname','sections.sectionname','studinfo.gender')
                        ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                        ->leftJoin('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                        ->leftJoin('sections','enrolledstud.sectionid','=','sections.id')
                        ->where('studinfo.id',$request->get('studid'))
                        ->where('enrolledstud.syid',$request->get('syid'))
                        ->first();
                }elseif($request->get('template') == 'shs')
                {                    
                    $studentinfo   = DB::table('studinfo')
                        ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.levelid','sh_strand.strandname','sh_strand.strandcode','gradelevel.levelname','sections.sectionname','studinfo.gender')
                        ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                        ->leftJoin('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                        ->leftJoin('sections','sh_enrolledstud.sectionid','=','sections.id')
                        ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                        ->where('studinfo.deleted','0')
                        ->where('sh_enrolledstud.deleted','0')
                        ->where('sh_enrolledstud.syid',$request->get('syid'))
                        ->where('sh_enrolledstud.semid',$request->get('semid'))
                        ->where('studinfo.id',$request->get('studid'))
                        ->first();
                }else{                
                    $studentinfo   = DB::table('studinfo')
                        ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.levelid','college_courses.courseDesc as strandname','college_courses.courseabrv as strandcode','gradelevel.levelname','college_sections.sectionDesc as sectionname','studinfo.gender')
                        ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                        ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                        ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
                        ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
                        ->where('studinfo.deleted','0')
                        ->where('college_enrolledstud.deleted','0')
                        ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                        ->where('college_enrolledstud.syid',$request->get('syid'))
                        ->where('college_enrolledstud.semid',$request->get('semid'))
                        ->where('studinfo.id',$request->get('studid'))
                        ->first();
                }
                
                return view('registrar.otherprintables.cogm.results')
                    ->with('template', $request->get('template'))
                    ->with('signatories', $signatories)
                    ->with('studentinfo', $studentinfo)
                    ->with('semesterinfo', $semesterinfo)
                    ->with('givendate',$givendate)
                    ->with('syinfo', $syinfo)
                    ->with('schoolregistrar', $schoolregistrar)
                    ->with('schoolinfo', $schoolinfo);
            }else{
                $template = $request->get('template');
                $purpose = $request->get('purpose');
                $escid = $request->get('escid');
                if($request->get('template') == 'jhs')
                {
                    $studentinfo =  DB::table('studinfo')
                        ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.levelid','gradelevel.levelname','sections.sectionname','studinfo.gender')
                        ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                        ->leftJoin('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                        ->leftJoin('sections','enrolledstud.sectionid','=','sections.id')
                        ->where('studinfo.id',$request->get('studid'))
                        ->where('enrolledstud.syid',$request->get('syid'))
                        ->first();
                    
                    $pdf = PDF::loadview('registrar.otherprintables.cogm.pdf_goodmoral',compact('studentinfo','syinfo','schoolinfo','semesterinfo','givendate','signatories','template','escid','purpose'))->setPaper('letter');
    
                     return $pdf->stream('Certificate of Good Moral - '.$studentinfo->lastname.', '.$studentinfo->firstname.' - '.$syinfo->sydesc.'.pdf');
    
                }elseif($request->get('template') == 'shs')
                {
                        
                    $studentinfo   = DB::table('studinfo')
                    ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.levelid','sh_strand.strandname','sh_strand.strandcode','gradelevel.levelname','sections.sectionname','sh_enrolledstud.semid','studinfo.gender')
                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                    ->leftJoin('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->leftJoin('sections','sh_enrolledstud.sectionid','=','sections.id')
                    ->leftJoin('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                    ->where('studinfo.deleted','0')
                    ->where('sh_enrolledstud.deleted','0')
                    ->where('sh_enrolledstud.syid',$request->get('syid'))
                    // ->where('sh_enrolledstud.semid',$request->get('semid'))
                    ->where('studinfo.id',$request->get('studid'))
                    ->get();
                    
                    if($request->has('semid'))
                    {
                        $studentinfo = collect($studentinfo)->where('semid',$request->get('semid'))->first();
                    }else{
                        $studentinfo = collect($studentinfo)->first();
                    }
                    $pdf = PDF::loadview('registrar.otherprintables.cogm.pdf_goodmoral',compact('studentinfo','syinfo','schoolinfo','semesterinfo','givendate','signatories','template','escid','purpose'))->setPaper('letter');
    
                     return $pdf->stream('Certificate of Good Moral - '.$studentinfo->lastname.', '.$studentinfo->firstname.' - '.$syinfo->sydesc.'.pdf');
                }else{
                    // return $request->all();
                    $studentinfo   = DB::table('studinfo')
                    ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.levelid','college_courses.courseDesc as strandname','college_courses.courseabrv as strandcode','gradelevel.levelname','college_sections.sectionDesc as sectionname','studinfo.gender')
                    ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                    ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                    ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
                    ->leftJoin('college_courses','college_enrolledstud.courseID','=','college_courses.id')
                    ->where('studinfo.deleted','0')
                    ->where('college_enrolledstud.deleted','0')
                    ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                    ->where('college_enrolledstud.syid',$request->get('syid'))
                    ->where('college_enrolledstud.semid',$request->get('semid'))
                    ->where('studinfo.id',$request->get('studid'))
                    ->first();
                    // return $studentinfo;
                    $tabletemplate = $request->get('exporttype');
                    $schoolyears = Db::table('sy')
                    ->select('id as syid','sydesc'
                    ,'isactive'
                    )
                    ->where('id',$request->get('syid'))
                    ->get();
                    $subjects = \App\Models\College\TOR::getrecords($studentinfo->id, $schoolyears);
                    // return $subjects;
                    if(count($subjects)>0)
                    {
                        $subjects = $subjects[0]->subjdata;
                    }
                    $pdf = PDF::loadview('registrar.otherprintables.cogm.pdf_goodmoral',compact('studentinfo','syinfo','schoolinfo','semesterinfo','givendate','signatories','tabletemplate','subjects','template','escid','purpose'))->setPaper('letter');
    
                    return $pdf->stream('Certificate of Good Moral - '.$studentinfo->lastname.', '.$studentinfo->firstname.' - '.$syinfo->sydesc.'.pdf');
                }

            }
        // }else{

        // }
    }
    public function printablecertificationcertofgraduation(Request $request)
    {
        date_default_timezone_set('Asia/Manila'); 

        $studentinfo   = DB::table('studinfo')
        ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.levelid','college_courses.courseDesc as strandname','college_courses.courseabrv as strandcode','gradelevel.levelname','college_sections.sectionDesc as sectionname')
        ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
        ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
        ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
        ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
        ->where('studinfo.deleted','0')
        ->where('college_enrolledstud.deleted','0')
        ->whereIn('college_enrolledstud.studstatus',[1,2,4])
        // ->where('college_enrolledstud.syid',$request->get('syid'))
        // ->where('college_enrolledstud.semid',$request->get('semid'))
        ->where('studinfo.id',$request->get('studid'))
        ->first();
        
        if($request->get('template') == 0)
        {
            $studcertformname = 'certgradnonaccredited';
            $signatoryformname = 'studcertgradnonaccredited';
        }else{
            $studcertformname = 'certgradaccredited';
            $signatoryformname = 'studcertgradaccredited';
        }


        $checkifexists = DB::table('studcertinfo')
        ->where('studid', $request->get('studid'))
        ->where('deleted','0')
        ->where('certdesc',$studcertformname)
        ->first();

        $signatory = DB::table('signatory')
        ->where('form',$signatoryformname)
        ->where('createdby', auth()->user()->id)
        ->first();

        if($request->get('action') == 'filter')
        {

            if($request->get('template') == 0)
            {
                return view('registrar.otherprintables.cograduation.results_nonaccredited')
                    ->with('studinfo', $studentinfo)
                    ->with('signatory', $signatory)
                    ->with('studcertinfo', $checkifexists)
                    // ->with('syid', $request->get('syid'))
                    // ->with('semid', $request->get('semid'))
                    ->with('givendate', $request->get('givendate'));
            }else{
                return view('registrar.otherprintables.cograduation.results_accredited')
                    ->with('studinfo', $studentinfo)
                    ->with('signatory', $signatory)
                    ->with('studcertinfo', $checkifexists)
                    // ->with('syid', $request->get('syid'))
                    // ->with('semid', $request->get('semid'))
                    ->with('givendate', $request->get('givendate'));
            }
        }
        elseif($request->get('action') == 'export')
        {

            if($checkifexists)
            {
                if($request->get('graduatedasof') != $checkifexists->dategraduated || $request->get('sono') != $checkifexists->specialorderno || $request->get('series') != $checkifexists->yearseries || $request->get('dated') != $checkifexists->seriesdate || $request->get('certipurpose') != $checkifexists->certipurpose || $request->get('issueddate') != $checkifexists->dateissued)
                {
                    // return 'asdasdasd';
                    DB::table('studcertinfo')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'dategraduated'         => $request->get('graduatedasof'),
                            'specialorderno'        => $request->get('sono'),
                            'yearseries'            => $request->get('series'),
                            'seriesdate'            => $request->get('dated'),
                            'certipurpose'          => $request->get('certipurpose'),
                            'dateissued'            => $request->get('issueddate'),
                            'gwagrade'            => $request->get('gwagrade'),
                            'percentgrade'            => $request->get('percentgrade'),
                            'updatedby'             => auth()->user()->id,
                            'updateddatetime'       => date('Y-m-d H:i:s')
                        ]);
                }
            }else{
                DB::table('studcertinfo')
                    ->insert([
                        'studid'                => $request->get('studid'),
                        'certdesc'              => $studcertformname,
                        'dategraduated'         => $request->get('graduatedasof'),
                        'specialorderno'        => $request->get('sono'),
                        'yearseries'            => $request->get('series'),
                        'seriesdate'            => $request->get('dated'),
                        'certipurpose'          => $request->get('certipurpose'),
                        'dateissued'            => $request->get('issueddate'),
                        'gwagrade'            => $request->get('gwagrade'),
                        'percentgrade'            => $request->get('percentgrade'),
                        'createdby'             => auth()->user()->id,
                        'createddatetime'       => date('Y-m-d H:i:s')
                    ]);
            }

                

            if($signatory)
            {
                if($request->get('registrar') != $signatory->name)
                {
                    DB::table('signatory')
                        ->where('id', $signatory->id)
                        ->update([
                            'name'                 => $request->get('registrar'),
                            'updatedby'             => auth()->user()->id,
                            'updateddatetime'       => date('Y-m-d H:i:s')
                        ]);
                }
            }else{
                DB::table('signatory')
                    ->insert([
                        'form'               => $signatoryformname,
                        'name'               => $request->get('registrar'),
                        'description'        => 'Registrar',
                        'createdby'          => auth()->user()->id,
                        'createddatetime'    => date('Y-m-d H:i:s')
                    ]);
            }
                
            $studcertinfo = DB::table('studcertinfo')
                ->where('studid', $request->get('studid'))
                ->where('deleted','0')
                ->where('certdesc',$studcertformname)
                ->first();

            $signatory = DB::table('signatory')
                ->where('form',$signatoryformname)
                ->where('createdby', auth()->user()->id)
                ->first();

            if($request->get('template') == 0)
            {
                $pdf = PDF::loadview('registrar.otherprintables.cograduation.pdf_nonaccredited',compact('studentinfo','studcertinfo','signatory'));
        
                return $pdf->stream('CERTIFICATION OF GRADUATION FOR NON-ACCREDITED COURSES - '.$studentinfo->lastname.', '.$studentinfo->firstname.'.pdf');
            }else{
                $pdf = PDF::loadview('registrar.otherprintables.cograduation.pdf_accredited',compact('studentinfo','studcertinfo','signatory'));
        
                return $pdf->stream('CERTIFICATION OF GRADUATION FOR NON-ACCREDITED COURSES - '.$studentinfo->lastname.', '.$studentinfo->firstname.'.pdf');
            }
            
        }
    }
    public function printablenumofstudentsindex(Request $request)
    {
        
        $gradelevels = DB::table('gradelevel')
            ->where('deleted','0')
            ->orderBy('sortid','asc')
            ->get();

        $schoolyears = DB::table('sy')->get();
        $semesters = DB::table('semester')->where('deleted','0')->get();
        $studentstatus = DB::table('studentstatus')
            ->get();

        return view('registrar.otherprintables.numofstudents.index')
            ->with('studentstatus', $studentstatus)
            ->with('schoolyears', $schoolyears)
            ->with('semesters', $semesters)
            ->with('gradelevels', $gradelevels);
    }
    public function printablenumofstudentsgenerate(Request $request)
    {
        // return $request->all();
            $syinfo = DB::table('sy')
                ->where('id', $request->get('syid'))
                ->first();
    
            $semesterinfo = DB::table('semester')
                ->where('id', $request->get('semid'))
                ->first();
    
            $gradelevelids = json_decode($request->get('gradelevels'));    
    
            $studtype = $request->get('studtype');

            if(count($gradelevelids) == 0)
            {
                $gradelevels = DB::table('gradelevel')
                    ->select('id','levelname')
                    ->where('deleted','0')
                    ->orderBy('sortid','asc')
                    ->get();
    
                $gradelevelids = collect($gradelevels)->pluck('id');
    
            }
            
            $statusids = json_decode($request->get('studentstatus'));
    
            $students = collect();

            $college_enrolledstud = DB::table('college_enrolledstud')
                ->select('studinfo.id',DB::raw('LOWER(`gender`) as gender'),'college_enrolledstud.yearLevel as levelid','studtype')
                ->join('studinfo','college_enrolledstud.studid','studinfo.id')
                ->where('college_enrolledstud.syid',$syinfo->id)
                ->where('college_enrolledstud.semid',$semesterinfo->id)
                ->where('college_enrolledstud.deleted','0')
                ->where('studinfo.deleted','0')
                ->whereIn('college_enrolledstud.studstatus',$statusids)
                ->get();
                
            $students = $students->merge($college_enrolledstud);
    
            $sh_enrolledstud = DB::table('sh_enrolledstud')
                ->select('studinfo.id',DB::raw('LOWER(`gender`) as gender'),'sh_enrolledstud.levelid','studtype')
                ->join('studinfo','sh_enrolledstud.studid','studinfo.id')
                ->where('sh_enrolledstud.syid',$syinfo->id)
                ->where('sh_enrolledstud.semid',$semesterinfo->id)
                ->where('sh_enrolledstud.deleted','0')
                ->where('studinfo.deleted','0')
                ->whereIn('sh_enrolledstud.studstatus',$statusids)
                ->get();
                
            $students = $students->merge($sh_enrolledstud);
    
            $enrolledstud = DB::table('enrolledstud')
                ->select('studinfo.id',DB::raw('LOWER(`gender`) as gender'),'enrolledstud.levelid','studtype')
                ->join('studinfo','enrolledstud.studid','studinfo.id')
                ->where('enrolledstud.syid',$syinfo->id)
                ->where('enrolledstud.deleted','0')
                ->where('studinfo.deleted','0')
                ->whereIn('enrolledstud.studstatus',$statusids)
                ->get();
                
            $students = $students->merge($enrolledstud);
            $students = collect($students)->unique('id');
            if($request->get('gender') == 'female' || $request->get('gender') == 'male')
            {
                $students = collect($students)->where('gender',$request->get('gender'))->values();
            }
            if($studtype == 'old' || $studtype == 'returnee')
            {
                $students = collect($students)->where('studtype',$studtype)->values();
            }
            if($studtype == 'new')
            {
                $students = collect($students)->where('studtype','!=','old')->where('studtype','!=','returnee')->values();
            }
    
            $gradelevels = array();
    
            foreach($gradelevelids as $level)
            {
                $levelinfo = DB::table('gradelevel')
                    ->where('id', $level)
                    ->first();
    
                array_push($gradelevels,(object)array(
                    'levelinfo'     => $levelinfo,
                    'studcount'     => collect($students)->where('levelid',$level)->count()
                ));
            }
    
        if($request->ajax())
        {
            return $gradelevels;
        }else{
            $gender = $request->get('gender');
            $pdf = PDF::loadview('registrar.otherprintables.numofstudents.pdf_results',compact('gradelevels','syinfo','semesterinfo','gender','statusids','studtype'));

             return $pdf->stream('Number of Students.pdf');
        }

    }
    public function printablecor(Request $request)
    {

        if(!$request->has('action'))
        {
            $gradelevels = DB::table('gradelevel')
                ->where('deleted','0')
                ->orderBy('sortid','asc')
                ->get();
    
            $schoolyears = DB::table('sy')->get();
            $semesters = DB::table('semester')->where('deleted','0')->get();

            return view('registrar.otherprintables.cor.index')
                // ->with('studentstatus', $studentstatus)
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters)
                ->with('gradelevels', $gradelevels);
        }else{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');

            $students_1 = DB::table('studinfo')
                    ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','enrolledstud.levelid','gradelevel.levelname','enrolledstud.dateenrolled','enrolledstud.syid')
                    ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->where('enrolledstud.syid',$syid)
                    ->where('studinfo.deleted','0')
                    ->where('enrolledstud.deleted','0')
                    ->get();

            $students_2 = DB::table('studinfo')
                    ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','sh_enrolledstud.levelid','gradelevel.levelname','sh_enrolledstud.dateenrolled','sh_enrolledstud.syid')
                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->where('sh_enrolledstud.syid',$syid)
                    ->where('sh_enrolledstud.semid',$semid)
                    ->where('studinfo.deleted','0')
                    ->where('sh_enrolledstud.deleted','0')
                    ->get();

            $students = collect();
            $students = $students->merge($students_1);
            $students = $students->merge($students_2);
            if($levelid != 0)
            {
                $students = $students->where('levelid', $levelid);
            }
            $students = $students->values();
            if($request->get('action') == 'generate')
            {
                return view('registrar.otherprintables.cor.filtertable')
                    ->with('students', $students);
            }else{

                $sydesc = DB::table('sy')
                    ->where('id', $request->get('syid'))
                    ->first()->sydesc;

                $studid = $request->get('studid');
                $levelname = $request->get('levelname');
                $enrolleddate = date('m/d/Y', strtotime($request->get('enrolleddate')));

                $studinfo = DB::table('studinfo')
                    ->where('id', $studid)
                    ->first();

                $pdf = PDF::loadview('registrar/otherprintables/cor/pdf_printablecor',compact('sydesc','levelname','enrolleddate','studinfo'));

                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->stream('COR');
                    
            }

        }
    }
    public function printablecoranking(Request $request)
    {
        if(!$request->has('action'))
        {
            $gradelevels = DB::table('gradelevel')
                ->where('acadprogid','6')
                ->where('deleted','0')
                ->orderBy('sortid','asc')
                ->get();
    
            $schoolyears = DB::table('sy')->get();
            $semesters = DB::table('semester')->where('deleted','0')->get();


            $students = DB::table('studinfo')
                    ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','college_enrolledstud.yearLevel as levelid','gradelevel.levelname','college_enrolledstud.date_enrolled as dateenrolled','college_enrolledstud.syid','college_enrolledstud.courseid','college_courses.courseDesc as coursename','college_courses.courseabrv','college_enrolledstud.syid','college_enrolledstud.semid')
                    ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                    ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                    ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                    // ->where('college_enrolledstud.syid',$syid)
                    // ->where('college_enrolledstud.semid',$semid)
                    ->where('studinfo.deleted','0')
                    ->where('college_enrolledstud.deleted','0')
                    ->get();

            $students = collect($students)->sortBy('semid')->sortBy('syid')->values();
            $students = collect($students)->unique('id')->values();
            $students = collect($students)->sortBy('lastname')->values();
            // return $students;
            return view('registrar.otherprintables.coranking.index')
                ->with('students', $students)
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters)
                ->with('gradelevels', $gradelevels);
        }else{
            $studid = $request->get('studid');
            
            $studinfo = DB::table('studinfo')
                    ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','college_enrolledstud.yearLevel as levelid','gradelevel.levelname','college_enrolledstud.date_enrolled as dateenrolled','college_enrolledstud.syid','college_enrolledstud.courseid','college_courses.courseDesc as coursename','college_courses.courseabrv','college_enrolledstud.syid','college_enrolledstud.semid')
                    ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                    ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                    ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                    // ->where('college_enrolledstud.syid',$syid)
                    // ->where('college_enrolledstud.semid',$semid)
                    ->where('studinfo.deleted','0')
                    ->where('studinfo.id',$studid)
                    ->where('college_enrolledstud.deleted','0')
                    ->get();

            $studinfo = collect($studinfo)->sortByDesc('semid')->sortBy('syid')->values();
            // $syid = $request->get('syid');
            // $semid = $request->get('semid');
            // $levelid = $request->get('levelid');
            // $monthid = $request->get('monthid');

            
            // $students = collect();
            // $students = $students->merge($students_1);
            // $students = $students->merge($students_2);
            
            $schoolregistrar = $request->get('schoolregistrar');
            
            $checkifexistregistrar = DB::table('signatory')
            ->where('form','reg_coranking')
            ->where('deleted','0')
            ->first();
            // return collect($checkifexistregistrar);
            if($checkifexistregistrar)
            {
                if($schoolregistrar == null || $schoolregistrar == "")
                {
                    $schoolregistrar = $checkifexistregistrar->name;
                }else{
                    if($schoolregistrar != $checkifexistregistrar->name)
                    {
                        DB::table('signatory')
                            ->where('id', $checkifexistregistrar->id)
                            ->update([
                                'name'      => $schoolregistrar,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }else{
                if($schoolregistrar != null || $schoolregistrar != "")
                {
                    DB::table('signatory')
                        ->insert([
                            'form'      => 'reg_coranking',
                            'name'      => $schoolregistrar,
                            'createdby' => auth()->user()->id,
                            'deleted'  => 0,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
            if($request->get('action') == 'generate')
            {
                return view('registrar.otherprintables.coranking.results')
                    ->with('schoolregistrar',$schoolregistrar)
                    ->with('studinfo', collect($studinfo)->first());
            }else{
                // return $request->all();
                $studinfo = collect($studinfo)->first();
                $dateasof = $request->get('input-date-asof');
                $program = $request->get('input-program');
                $dateissued = $request->get('input-date-issued');
                $pdf = PDF::loadview('registrar/otherprintables/coranking/pdf_coranking',compact('studinfo','schoolregistrar','dateasof','program','dateissued'));

                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->stream('GWA Ranking');
                    
            }
        }
    }
    public function printablegwaranking(Request $request)
    {
        if(!$request->has('action'))
        {
            $gradelevels = DB::table('gradelevel')
                ->where('deleted','0')
                ->orderBy('sortid','asc')
                ->get();
    
            $schoolyears = DB::table('sy')->get();
            $semesters = DB::table('semester')->where('deleted','0')->get();

            return view('registrar.otherprintables.gwaranking.index')
                // ->with('studentstatus', $studentstatus)
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters)
                ->with('gradelevels', $gradelevels);
        }else{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $monthid = $request->get('monthid');

            // $students_1 = DB::table('studinfo')
            //         ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','enrolledstud.levelid','gradelevel.levelname','enrolledstud.dateenrolled','enrolledstud.syid')
            //         ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
            //         ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
            //         ->where('enrolledstud.syid',$syid)
            //         ->where('studinfo.deleted','0')
            //         ->where('enrolledstud.deleted','0')
            //         ->get();

            // $students_2 = DB::table('studinfo')
            //         ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','sh_enrolledstud.levelid','gradelevel.levelname','sh_enrolledstud.dateenrolled','sh_enrolledstud.syid')
            //         ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
            //         ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
            //         ->where('sh_enrolledstud.syid',$syid)
            //         ->where('sh_enrolledstud.semid',$semid)
            //         ->where('studinfo.deleted','0')
            //         ->where('sh_enrolledstud.deleted','0')
            //         ->get();

            $students = DB::table('studinfo')
                    ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','college_enrolledstud.yearLevel as levelid','gradelevel.levelname','college_enrolledstud.date_enrolled as dateenrolled','college_enrolledstud.syid','college_enrolledstud.courseid','college_courses.courseDesc as coursename','college_courses.courseabrv')
                    ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                    ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                    ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                    ->where('college_enrolledstud.syid',$syid)
                    // ->where('college_enrolledstud.semid',$semid)
                    ->where('studinfo.deleted','0')
                    ->where('college_enrolledstud.deleted','0')
                    ->get();

            if(count($students)>0)
            {
                foreach($students as $student)
                {
                    $result = '';
                    
                    if(strpos(strtolower($student->coursename), 'major in ')  !== false){
                        $index = strpos(strtolower($student->coursename), 'major in ') + strlen('major in ');
                        $result = substr(strtolower($student->coursename), $index);
                    }

                    $student->major = strtoupper($result);
                    $student->display = 0;

                }
            }
            
            // $students = collect();
            // $students = $students->merge($students_1);
            // $students = $students->merge($students_2);
            if($levelid != 0)
            {
                $students = $students->where('levelid', $levelid);
            }
            $students = $students->values();
            
            if($request->get('action') == 'generate')
            {
                return view('registrar.otherprintables.gwaranking.filtertable')
                    ->with('students', $students);
            }else{

                $sydesc = DB::table('sy')
                    ->where('id', $request->get('syid'))
                    ->first()->sydesc;

                $levelname = "";
                // $semester = DB::table('semester')
                //     ->where('id', $request->get('semid'))
                //     ->first()->semester;

                if($request->get('levelid') > 0)
                {
                    $levelname = DB::table('gradelevel')
                        ->where('id', $request->get('levelid'))
                        ->first()->levelname;
                }
                
                $monthname = date('F', strtotime('2022-'.$request->get('monthid')));
                
                $pdf = PDF::loadview('registrar/otherprintables/gwaranking/pdf_gwaranking',compact('sydesc','monthname','levelname','students'));

                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->stream('GWA Ranking');
                    
            }
        }
    }
    public function form3(Request $request)
    {
        if($request->get('acadprogid') == 5)
        {
            
        }else{

        }
    }
    public function notenrolled(Request $request)
    {
        if(!$request->has('action'))
        {
            return view('registrar.summaries.notenrolled.index');
        }else{
            $syid = DB::table('sy')->where('isactive','1')->first()->id;
            $semid = DB::table('semester')->where('isactive','1')->first()->id;
            $levelid = $request->get('levelid');
            $acadprogid = 0;
            if($levelid>0)
            {
                $acadprogid = DB::table('gradelevel')
                    ->where('id', $levelid)
                    ->where('deleted','0')
                    ->first()->acadprogid;
            }
            $students = collect();

            if($acadprogid<=4)
            {
                $stud1 = DB::table('studinfo')
                    ->select('studinfo.id','lastname','firstname','middlename','suffix','lrn','sid','levelname','levelid','gender')
                    ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                    ->where('gradelevel.deleted','0')
                    ->where('studinfo.deleted','0')
                    ->where('gradelevel.acadprogid','<=','4')
                    ->where('studstatus','0')
                    ->where('lastname','!=',null)
                    ->get();

                $students = $students->merge($stud1);
            }
            if($acadprogid == 0 || $acadprogid == 5)
            {
                $stud2 = DB::table('studinfo')
                    ->select('studinfo.id','lastname','firstname','middlename','suffix','lrn','sid','levelname','levelid','gender')
                    ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                    ->where('gradelevel.deleted','0')
                    ->where('studinfo.deleted','0')
                    ->whereIn('gradelevel.acadprogid',[0,5])
                    ->where('studstatus','0')
                    ->where('lastname','!=',null)
                    ->get();

                $students = $students->merge($stud2);

            }
            if($acadprogid == 0 || $acadprogid == 6)
            {
                $stud3 = DB::table('studinfo')
                    ->select('studinfo.id','lastname','firstname','middlename','suffix','lrn','sid','levelname','levelid','gender')
                    ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                    ->where('gradelevel.deleted','0')
                    ->where('studinfo.deleted','0')
                    ->whereIn('gradelevel.acadprogid',[0,6])
                    ->where('studstatus','0')
                    ->where('lastname','!=',null)
                    ->get();
                    
                $students = $students->merge($stud3);

            }
            if($levelid>0)
            {
                $students = collect($students)->where('levelid', $levelid)->values();
            }
            $students = collect($students)->unique('id');
            if(count($students)>0)
            {
                foreach($students as $student)
                {
                    $student->gender = strtolower($student->gender);
                    $student->sortname = strtolower($student->lastname.' '.$student->firstname.' '.$student->middlename);
                }    
            }
            $students = collect($students)->sortBy('sortname')->values()->all();
            
            if($request->get('action') == 'filter')
            {
                return view('registrar.summaries.notenrolled.results')
                    ->with('levelid', $levelid)
                    ->with('students', $students);
            }else{
                $pdf = PDF::loadview('registrar/summaries/notenrolled/pdf_notenrolled',compact('students','syid','semid','levelid','acadprogid'));

                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->stream('COR');
            }
            
        }
    }
    public function printablestudentacademicrecord(Request $request)
    {
        if(!$request->has('action'))
        {
            $gradelevels = DB::table('gradelevel')
                ->where('acadprogid','6')
                ->where('deleted','0')
                ->orderBy('sortid','asc')
                ->get();
    
            $schoolyears = DB::table('sy')->get();
            $semesters = DB::table('semester')->where('deleted','0')->get();


            $students = DB::table('studinfo')
                    ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','gender','suffix','college_enrolledstud.yearLevel as levelid','gradelevel.levelname','college_enrolledstud.date_enrolled as dateenrolled','college_enrolledstud.syid','college_enrolledstud.courseid','college_courses.courseDesc as coursename','college_courses.courseabrv','college_enrolledstud.syid','college_enrolledstud.semid')
                    ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                    ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                    ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                    // ->where('college_enrolledstud.syid',$syid)
                    // ->where('college_enrolledstud.semid',$semid)
                    ->where('studinfo.deleted','0')
                    ->where('college_enrolledstud.deleted','0')
                    ->get();

            $students = collect($students)->sortBy('semid')->sortBy('syid')->values();
            $students = collect($students)->unique('id')->values();
            $students = collect($students)->sortBy('lastname')->values();
            // return $students;
            return view('registrar.otherprintables.studacademicrecord.index')
                ->with('students', $students)
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters)
                ->with('gradelevels', $gradelevels);
        }else{
            $schoolyears = Db::table('sy')
                ->select('id as syid','sydesc'
                ,'isactive'
                )
                ->orderByDesc('sydesc')
                ->get();
                
            $studid = $request->get('studid');
            
            $studinfo = DB::table('studinfo')
                    ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','gender','picurl','college_enrolledstud.yearLevel as levelid','gradelevel.levelname','college_enrolledstud.date_enrolled as dateenrolled','college_enrolledstud.syid','college_enrolledstud.courseid','college_courses.courseDesc as coursename','college_courses.courseabrv','college_enrolledstud.syid','college_enrolledstud.semid')
                    ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                    ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                    ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                    ->where('studinfo.deleted','0')
                    ->where('studinfo.id',$studid)
                    ->where('college_enrolledstud.deleted','0')
                    ->get();

            $studinfo = collect($studinfo)->sortByDesc('semid')->sortBy('syid')->values();
            
            $records = \App\Models\College\TOR::getrecords($studid, $schoolyears);

            $transmutations = array();

            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
            {
                $transmutations = array(
                    (object)['gd'=>98,'gdto'=>number_format(100,2),'hpaeq'=>number_format(1.24,2),'hpaeqto'=>number_format(1.00,2),'honorpointeq'=>number_format(5.00,2)],
                    (object)['gd'=>95,'gdto'=>number_format(97,2),'hpaeq'=>number_format(1.49,2),'hpaeqto'=>number_format(1.25,2),'honorpointeq'=>number_format(4.50,2)],
                    (object)['gd'=>92,'gdto'=>number_format(94,2),'hpaeq'=>number_format(1.74,2),'hpaeqto'=>number_format(1.50,2),'honorpointeq'=>number_format(4.00,2)],
                    (object)['gd'=>89,'gdto'=>number_format(91,2),'hpaeq'=>number_format(1.99,2),'hpaeqto'=>number_format(1.75,2),'honorpointeq'=>number_format(3.50,2)],
                    (object)['gd'=>86,'gdto'=>number_format(88,2),'hpaeq'=>number_format(2.24,2),'hpaeqto'=>number_format(2.00,2),'honorpointeq'=>number_format(3.00,2)],
                    (object)['gd'=>83,'gdto'=>number_format(85,2),'hpaeq'=>number_format(2.49,2),'hpaeqto'=>number_format(2.25,2),'honorpointeq'=>number_format(2.50,2)],
                    (object)['gd'=>80,'gdto'=>number_format(82,2),'hpaeq'=>number_format(2.74,2),'hpaeqto'=>number_format(2.50,2),'honorpointeq'=>number_format(2.00,2)],
                    (object)['gd'=>77,'gdto'=>number_format(79,2),'hpaeq'=>number_format(2.99,2),'hpaeqto'=>number_format(2.75,2),'honorpointeq'=>number_format(1.50,2)],
                    (object)['gd'=>75,'gdto'=>number_format(76,2),'hpaeq'=>number_format(4.99,2),'hpaeqto'=>number_format(3.00,2),'honorpointeq'=>number_format(1.00,2)],
                    (object)['gd'=>60,'gdto'=>number_format(74,2),'hpaeq'=>number_format(0.00,2),'hpaeqto'=>number_format(5.00,2),'honorpointeq'=>'Failure']
                );
                
            }
            
            if($request->get('action') == 'generate')
            {

                return view('registrar.otherprintables.studacademicrecord.results')
                    ->with('records', $records)
                    ->with('transmutations', $transmutations)
                    ->with('studinfo', collect($studinfo)->first());
            }else{
                
                $studinfo = collect($studinfo)->first();
                
                $schoolregistrar = null;
                $pdf = PDF::loadview('registrar/otherprintables/studacademicrecord/pdf_studacadrecord_sbc',compact('studinfo','schoolregistrar','records','transmutations'));
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->stream('Student Academic Record With Weighted Average Computation_'.$studinfo->lastname.'_'.$studinfo->firstname.'.pdf');
                    
            }
        }
    }
	public function enrollment(Request $request)
    {
        if(!$request->has('action'))
        {
            return view('registrar.summaries.enrollmentreport.index');
        }else{
            $schoolyears = Db::table('sy')
            ->select('id as syid','sydesc'
            ,'isactive'
            )
            ->where('id',$request->get('syid'))
            ->get();
            


            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
            {
            $students = collect(
                [
                    'display'   => 0
                ],
                [
                    'display'   => 0
                ],
                [
                    'display'   => 0
                ],
                [
                    'display'   => 0
                ]
                );
            }else{
                $students = collect();
            }

            $getstudents   = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.lrn',
                'studinfo.sid',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.dob',
                'college_enrolledstud.yearLevel as levelid',
                // 'college_courses.courseDesc as strandname',
                'college_courses.id as courseid',
                'college_courses.collegeid',
                'college_courses.courseabrv',
                'college_courses.courseDesc as coursename',
                'nationality.nationality',
                'gradelevel.sortid',
                // 'college_sections.sectionDesc as sectionname',
                'college_year.id as yearid'
                )
            ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
            ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
            ->leftJoin('college_year','gradelevel.id','=','college_year.levelid')
            ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
            ->leftJoin('nationality','studinfo.nationality','=','nationality.id')
            ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
            ->where('studinfo.deleted','0')
            ->where('college_enrolledstud.deleted','0')
            ->whereIn('college_enrolledstud.studstatus',[1,2,4])
            ->where('college_enrolledstud.syid',$request->get('syid'))
            ->where('college_enrolledstud.semid',$request->get('semid'))
            ->get();
                
			$syid = $request->get('syid');
			$semid = $request->get('semid');
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sbc')
            {
                if($request->get('levelid')>0)
                {
                    $students = collect($students)->where('levelid', $request->get('levelid'))->values();
                }
                if($request->get('collegeid')>0)
                {
                    $students = collect($students)->where('collegeid', $request->get('collegeid'))->values();
                }
                if($request->get('courseid')>0)
                {
                    $students = collect($students)->where('courseid', $request->get('courseid'))->values();
                }
            }
            if(count($getstudents)>0)
            {
                foreach($getstudents as $student)
                {
                    
                    $student->sortname = $student->lastname.' '.$student->firstname;
                    $student->display = 1;
                }
            }
            $getstudents = collect($getstudents)->sortBy('sortname')->values()->all();
            if(count($getstudents)>0)
            {
                foreach($getstudents as $keyastudent=>$student)
                {
                    $student->idno = $keyastudent+1;
                }
            }
            $students = collect($students)->merge($getstudents);
            
            function lower_case($given_coursename)
            {
                $exclude = array('and','in','of','the','on','at','or','for','as','sa');
                $course_desc = strtolower($given_coursename);
                $words = explode(' ', $course_desc);
                foreach($words as $key => $word) {
                    
                    if (str_contains($word, '-')) { 
                        $word = explode('-', $word);
                        $word = ucfirst($word[0]).'-'.ucfirst($word[1]);
                     }
                    if($key == 0)
                    {
                        $words[$key] = ucfirst($word);
                    }else{
                        if(in_array($word, $exclude)) {
                            continue;
                        }
                        $words[$key] = ucfirst($word);
                    }
                }
                return $coursename = implode(' ', $words);
            }
            // return $students;
            if(count($students)>0)
            {
                $college_classsched = DB::table('college_classsched')
                    ->where('college_classsched.syID',$syid)
                    ->where('college_classsched.semesterID',$semid)
                    ->where('college_classsched.deleted','0')
                    ->join('college_studsched','college_classsched.id','college_studsched.schedid')
                    ->where('college_studsched.deleted','0')
                    ->whereIn('college_studsched.studid',collect($students)->pluck('id'))
                    ->join('college_prospectus','college_classsched.subjectID','college_prospectus.id')
                    ->where('college_prospectus.deleted','0')
                    ->where('college_studsched.schedstatus','!=','DROPPED')
                    ->select('subjCode as subjectcode','subjDesc as subjectname', 'labunits','lecunits' ,'college_prospectus.psubjsort as subjsort','college_studsched.studid','college_classsched.subjectID as subjectid','college_prospectus.subjectid as pros_subjectid')
                    ->get();


                $college_grades = DB::table('college_studentprospectus')    
                    ->where('college_studentprospectus.syid',$syid)
                    ->where('college_studentprospectus.semid',$semid)
                    ->where('college_studentprospectus.deleted','0')
                    ->select('finalgrade as subjgrade','prospectusID as subjectid','studid')
                    ->get();

                
                $college_classsched = collect($college_classsched)->groupBy('studid');
                $college_grades = collect($college_grades)->groupBy('studid');
                // return $college_classsched[410];
                // $students = collect($students)->where('id', 410)->values();
                
                $exclude = array('and','in','of','the','on','at','or','for','sa');
                foreach($students as $student)
                {
                    // return collect($college_classsched[410])->pluck('pros_subjectid');
                    $college_prospectus_eachstud = DB::table('college_prospectus')
                        ->whereIn('subjectID',collect(collect($college_classsched[$student->id]))->pluck('pros_subjectid'))
                        ->where('courseID',$student->courseid)
                        ->where('deleted','0')
                        ->get();
                        // return $college_prospectus;
                    try{   

                        $student->major = " "; 
                        $majorin = explode("major in ",strtolower($student->coursename));
                        if(count($majorin)>1)
                        {
                            $student->coursename = strtoupper($majorin[0]);
                            $student->major = strtoupper($majorin[1]);
                        }
                        $student->sortname = $student->lastname.' '.$student->firstname;
                        $student->sortcourseandlevel = $student->coursename.' '.$student->sortid.' '.$student->sortname;
                        
                        $grades = $college_classsched[$student->id];
                        // return $grades;
                        if(count($grades)>0)
                        {
                            foreach($grades as $grade)
                            {
                                $grade->subjgrade = null;
                                try{ 
                                $grade->subjgrade =  collect($college_grades[$student->id])->where('subjectid',$grade->subjectid)->first()->subjgrade ?? null;
                                }catch(\Exception $error)
                                {}            
                                $grade->subjcredit = 0;
                                $grade->subjunit = ($grade->lecunits+$grade->labunits);
                                $grade->subjsort = collect($college_prospectus_eachstud)->where('subjectID',$grade->pros_subjectid)->first()->psubjsort ?? null;
                                $grade->subjsortname = $grade->subjsort.' '.$grade->subjectcode;
                            }
                        }
                        // return collect($grades)->pluck('pros_subjectid');
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sbc')
                        {
                            $student->display = 0;
                        }
                        $student->subjects = collect($grades)->sortBy('subjsortname')->values()->all();
                        // if(strtolower($student->lastname) == 'abanto')
                        // {
                            // return $student->subjects;
                            
                        // }
                        $student->countsubj = count($grades);
                        // $college_classsched = collect($college_classsched)->where('studid', '!=',  $student->id)->values();
                        // $college_grades = collect($college_grades)->where('studid', '!=',  $student->id)->values();
                    }catch(\Exception $error)
                    {
                    }
                    $student->coursename = lower_case($student->coursename);
                }
            }
            // return $students;
            // return $students;
            $totalnoofstudents = count($getstudents);
            // return $totalnoofstudents;
            $totalexports = $totalnoofstudents%315 > 0 ? floor($totalnoofstudents/315)+1 : floor($totalnoofstudents/315);
            
            $studentspertab = array_chunk(collect($students)->toArray(), 315);
            $countlastpages = (floor((collect(collect($studentspertab)->last())->count())/21));
            if($request->has('tabno'))
            {
                $students = $studentspertab[($request->get('tabno')-1)];

            }
            if(count(collect($students)->pluck('countsubj')->toArray()) == 0)
            {
                $maxsubjects = 0;
            }else{
                $maxsubjects = max(collect($students)->pluck('countsubj')->toArray());
            }
            if($request->get('action') == 'filter')
            {
                $checkifexist = DB::table('signatory')
                ->where('form','enrollment_report_reg')
                ->where('deleted','0')
                ->get();
                
                // return $students;
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                {
                    return view('registrar.summaries.enrollmentreport.hccsi.results')
                        ->with('maxsubjects',$maxsubjects)
                        ->with('signatories',$checkifexist)
                        ->with('students',$students);
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                {
                    $students = collect($course)->sortBy('sortcourseandlevel');
                    return view('registrar.summaries.enrollmentreport.pcc.results')
                        ->with('maxsubjects',$maxsubjects)
                        ->with('signatories',$checkifexist)
                        ->with('students',$students);
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                {
                    return view('registrar.summaries.enrollmentreport.sbc.results')
                        ->with('totalnoofstudents', $totalnoofstudents)
                        ->with('totalexports', $totalexports)
                        ->with('countlastpages', $countlastpages)
                        ->with('maxsubjects',$maxsubjects)
                        ->with('signatories',$checkifexist)
                        ->with('students',$students);
                }else{
                    // return $students;
                    // return array_keys(collect($students)->toArray());
                    return view('registrar.summaries.enrollmentreport.results')
                        ->with('maxsubjects',$maxsubjects)
                        ->with('signatories',$checkifexist)
                        ->with('students',$students);
                }
            }else{
                $courses = collect($getstudents)->sortBy('coursename');
                $courses = $courses->map(function ($course) {
                    return (object)collect(collect($course)->toArray())
                        ->only(['courseid','courseabrv', 'coursename','major'])
                        ->all();
                })->unique()->values();
                $schoolregistrar = $request->get('registrar');
                $president = $request->get('president');

                $checkifexistregistrar = DB::table('signatory')
                ->where('form','enrollment_report_reg')
                ->where('title','Registrar')
                ->where('deleted','0')
                ->first();
    
                if($checkifexistregistrar)
                {
                    if($schoolregistrar == null || $schoolregistrar == "")
                    {
                        $schoolregistrar = $checkifexistregistrar->name;
                    }else{
                        if($schoolregistrar != $checkifexistregistrar->name)
                        {
                            DB::table('signatory')
                                ->where('id', $checkifexistregistrar->id)
                                ->update([
                                    'name'      => $schoolregistrar,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }else{
                    if($schoolregistrar != null || $schoolregistrar != "")
                    {
                        DB::table('signatory')
                            ->insert([
                                'form'      => 'enrollment_report_reg',
                                'name'      => $schoolregistrar,
                                'title'      => 'Registrar',
                                'createdby' => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                $checkifexistpresident = DB::table('signatory')
                ->where('form','enrollment_report_reg')
                ->where('title','President')
                ->where('deleted','0')
                ->first();
    
                if($checkifexistpresident)
                {
                    if($president == null || $president == "")
                    {
                        $president = $checkifexistpresident->name;
                    }else{
                        if($president != $checkifexistpresident->name)
                        {
                            DB::table('signatory')
                                ->where('id', $checkifexistpresident->id)
                                ->update([
                                    'name'      => $president,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }else{
                    if($president != null || $president != "")
                    {
                        DB::table('signatory')
                            ->insert([
                                'form'      => 'enrollment_report_reg',
                                'name'      => $president,
                                'title'      => 'President',
                                'createdby' => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                $syinfo = DB::table('sy')->where('id', $request->get('syid'))->first();
                $seminfo = DB::table('semester')->where('id', $request->get('semid'))->first();
                $registrar = $schoolregistrar;

                if($request->get('exporttype') == 'pdf')
                {
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                    {
                        $pdf = PDF::loadview('registrar/summaries/enrollmentreport/hccsi/pdf_enrollmentreport',compact('maxsubjects','students','syinfo','seminfo','registrar','president'));
                        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
                        return $pdf->stream('Enrollment Report.pdf');
                    }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'){
                        
                        $tabno = $request->get('tabno');
                        $pagedesc = $request->get('pagedesc');
                        $firstpageno = $request->get('firstpageno');
                        $lastpageno = 1;
                        if($totalexports>0)
                        {
                            $firstpage = 1;
                            for($x = 0; $x<$totalexports; $x++)
                            {
                                if(($x+1) == $totalexports)
                                {
                                    $lastpageno+=$countlastpages;
                                }else{
                                    $lastpageno+=(15);
                                }
                                $firstpage += 15;
                            }
                        }
                        $pdf = PDF::loadview('registrar/summaries/enrollmentreport/pdf_enrollmentreport_sbc',compact('maxsubjects','students','syinfo','seminfo','registrar','president','courses','tabno','pagedesc','firstpageno','lastpageno','countlastpages','totalexports'));
                        return $pdf->stream('Enrollment Report.pdf');
                        
                    }else{
                        $pdf = PDF::loadview('registrar/summaries/enrollmentreport/pdf_enrollmentreport',compact('maxsubjects','students','syinfo','seminfo','registrar','president'));
                        return $pdf->stream('Enrollment Report.pdf');
                    }
                }else{        
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
                            
                    $sydesc         = $syinfo->sydesc;
                    $semester       = $seminfo->semester;
                    $inputFileType  = 'Xlsx';
                    function defaulttemplate_ched($students,$sydesc,$semester)
                    {
                        $inputFileType  = 'Xlsx';
                        
                        $inputFileName = base_path().'/public/excelformats/enrollmentlist.xlsx';
                        // $sheetname = 'Front';
    
                        /**  Create a new Reader of the type defined in $inputFileType  **/
                        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                        /**  Advise the Reader of which WorkSheets we want to load  **/
                        // $reader->setLoadAllSheets();
                        /**  Load $inputFileName to a Spreadsheet Object  **/
                        $spreadsheet = $reader->load($inputFileName);
                        $sheet = $spreadsheet->getSheet(0);
                        
                        $borderstyle = [
                            // 'alignment' => [
                            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                            // ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                                ]
                            ]
                        ];
                        $sheet->setCellValue('D3',DB::table('schoolinfo')->first()->schoolname);
                        $sheet->setCellValue('R3',DB::table('schoolinfo')->first()->schoolid);
                        $sheet->setCellValue('Z3',DB::table('schoolinfo')->first()->address);
    
                        $preparedby = DB::table('teacher')
                            ->where('userid', auth()->user()->id)
                            ->where('isactive','1')
                            ->where('deleted','0')
                            ->first();

                        $prepared_name = '';
                        if($preparedby)
                        {
                            $prepared_name .= $preparedby->firstname.' ';
                            $prepared_name .= $preparedby->middlename == null ? '  ' : $preparedby->middlename[0].'. ';
                            $prepared_name .= $preparedby->lastname;
                        }else{
                            $prepared_name = auth()->user()->name;
                        }
                        $certified_name = '';
                        $verified_name = '';
                        $noted_name = '';
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa')
                        {
                            $certified_name = 'ESTELLE RICA P. HIACO';
                            $verified_name = 'ESTELLE RICA P. HIACO';
                            $noted_name = 'LEONITO A. FABIAN';
                        }

                        $sheet->setCellValue('D6',$prepared_name);
                        $sheet->setCellValue('J6',$certified_name);
                        $sheet->setCellValue('O6',$verified_name);
                        $sheet->setCellValue('U6',$noted_name);
                        $startcellno = 11;
                        // $sheet->getStyle('A15:K15')->applyFromArray($borderstyle);
                        // $sheet->getStyle('M15:W15')->applyFromArray($borderstyle);
                        if(count($students)>0)
                        {
                            
                 
                            foreach($students as $key=>$student)
                            {
                                $student->yearlevel = $student->yearid;
                                
                                $sheet->setCellValue('A'.$startcellno,($key+1));
                                if($student->lastname != null)
                                {
                                    $sheet->setCellValue('B'.$startcellno,$student->lastname);
                                }
                                if($student->firstname != null)
                                {
                                    $sheet->setCellValue('C'.$startcellno,$student->firstname);
                                }
                                if($student->middlename != null)
                                {
                                    $sheet->setCellValue('D'.$startcellno,$student->middlename);
                                }
                                if($student->suffix != null)
                                {
                                    $sheet->setCellValue('E'.$startcellno,$student->suffix);
                                }
                                if($student->gender != null)
                                {
                                    $sheet->setCellValue('F'.$startcellno,ucwords(strtolower($student->gender)));
                                }
                                if($student->dob != null)
                                {
                                    $sheet->setCellValue('G'.$startcellno,date('m/d/Y', strtotime($student->dob)));
                                }
                                if($student->coursename != null)
                                {
                                    $sheet->setCellValue('H'.$startcellno,$student->coursename);
                                }
                                if($student->yearlevel != null)
                                {
                                    $sheet->setCellValue('I'.$startcellno,$student->yearlevel);
                                }
                                
                                $subjectindex = 0;
                                $subjectcount = 0;
                                for($x = 1; $x <= 50; $x++)
                                {
                                    $sheet->getStyle(getNameFromNumber($x).$startcellno)->applyFromArray($borderstyle);
    
                                }
                                for($x = 10; $x <= 50; $x++)
                                {
                                    if(isset($student->subjects[$subjectindex]))
                                    {
                                        $sheet->setCellValue(getNameFromNumber($x).$startcellno,$student->subjects[$subjectindex]->subjcode ?? $student->subjects[$subjectindex]->subjectcode);
                                        $sheet->setCellValue(getNameFromNumber($x+1).$startcellno,$student->subjects[$subjectindex]->subjunit);
    
                                        $subjectindex+=1;
                                        $x+=1;
                                    }
                                }
                                $sheet->setCellValue('AX'.$startcellno,collect($student->subjects)->sum('subjunit'));
                                // return 'asdsadsa';
                                $startcellno+=1;
                            }
                        }
                        
    
                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment; filename="Enrollment List S.Y '.$sydesc.' '.$semester.'.xlsx"');
                        $writer->save("php://output");
    
                        exit;
                    
                    }

                    function template_ccsa($students,$sydesc,$semester)
                    {               
                        $inputFileType  = 'Xls';                   
                                
                        $inputFileName = base_path().'/public/excelformats/ccsa/ccsa_enrollmentlist.xls';
                        // $sheetname = 'Front';

                        /**  Create a new Reader of the type defined in $inputFileType  **/
                        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                        /**  Advise the Reader of which WorkSheets we want to load  **/
                        $reader->setLoadAllSheets();
                        /**  Load $inputFileName to a Spreadsheet Object  **/
                        $spreadsheet = $reader->load($inputFileName);
                        $sheet = $spreadsheet->getSheet(0);
                        
                        $borderstyle = [
                            // 'alignment' => [
                            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                            // ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                                ]
                            ]
                        ];
                        $borderstyle_white = [
                            // 'alignment' => [
                            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                            // ],
                                'borders' => array(
                                    'outline' => array(
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE
                                        // 'color' => array('argb' => '00000000'),
                                    ),
                                ),
                        ];

                        
                        // $styleArray = $sheet->getStyle('A1:AI1')->exportArray();
                        // $sheet->getStyle('A46:AI46')->applyFromArray($styleArray);
                                // $data = $sheet->rangeToArray('A1'.':AI44');
                                // $sheet->fromArray($data, null, 'A'.$startingperpagestartrow);
                        $sheet->setCellValue('A10',$semester.' - ACADEMIC YEAR '.$sydesc);
                        $startingperpagestartrow = 45;
                        $startcellno = 14;
                        $studentsperpage = array_chunk(collect($students)->toArray(),15);
                        // $lastpage = (object)array(collect($studentsperpage)->last());
                        // $studentsperpage =collect($studentsperpage)->take(2);
                        // $studentsperpage =collect($studentsperpage)->merge($lastpage);
                        // return getNameFromNumber(1);
                        $countstud = 16;
                        if(count($studentsperpage)>0)
                        {
                            $sheet->setCellValue('A55',count($studentsperpage).' OF '.count($studentsperpage));
                            foreach($studentsperpage as $keyperpage => $perpage)
                            {
                                if($keyperpage > 0)
                                {
                                    $sheet->insertNewRowBefore($startingperpagestartrow,44);
                                    $data = $sheet->rangeToArray('A1:AI44');
                                    $sheet->fromArray($data, null, 'A'.$startingperpagestartrow);
                                    
                                    $sheet->setCellValue('A'.($startingperpagestartrow+9),$semester.' - ACADEMIC YEAR '.$sydesc);
                                    $startingperpagestartrow += (44);
                                }
                                
                                if($keyperpage == 0)
                                {
                                    foreach($perpage as $eachstudent)
                                    {
                                        $sheet->setCellValue('B'.$startcellno,$eachstudent->lastname);
                                        $sheet->setCellValue('C'.$startcellno,$eachstudent->firstname);
                                        $sheet->setCellValue('D'.$startcellno,$eachstudent->middlename);
                                        $sheet->setCellValue('E'.$startcellno,$eachstudent->gender[0]);
                                        $sheet->setCellValue('F'.$startcellno,$eachstudent->courseabrv);
                                        $sheet->setCellValue('G'.$startcellno,$eachstudent->yearid);
                                        $sheet->setCellValue('H'.$startcellno,$eachstudent->dob != null ? date('m/d/Y', strtotime($eachstudent->dob)) : '');
                                        if(isset($eachstudent->subjects))
                                        {
                                            if(count($eachstudent->subjects)>0)
                                            {
                                                $subjectkey = 0;
                                                for($letternum = 9; $letternum <=35; $letternum++)
                                                {
                                                    if($letternum < 35) //I-AH
                                                    {
                                                        if($letternum % 2 != 0){
                                                            if(isset($eachstudent->subjects[$subjectkey]))
                                                            {
                                                                $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,$eachstudent->subjects[$subjectkey]->subjectcode); //upper
                                                                $sheet->setCellValue(getNameFromNumber($letternum).($startcellno+1),$eachstudent->subjects[$subjectkey]->subjunit); //lowerjects)>0)
                                                                    $subjectkey +=1;
                                                            }
                                                        }
                                                    }else{
                                                        $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,collect($eachstudent->subjects)->sum('subjunit'));
                                                    }
                                                } 
                                            }
                                        }
                                        $startcellno+=2;
                                    }
                                    $startcellno+=1;
                                }else{
                                    // return $perpage;
                                    // $startcellno+=1;
                                    for($x = 1; $x < 11; $x++)
                                    {
                                        if($x == 1)
                                        {                                            
                                            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                                            $drawing->setWorksheet($sheet);
                                            $drawing->setName('Logo');
                                            $drawing->setDescription('Logo');
                                            $drawing->setPath(base_path().'/public/excelformats/ccsa/leftlogo.png');
                                            $drawing->setHeight(40);
                                            $drawing->setCoordinates('H'.$startcellno);
                                            // $drawing->setOffsetX(20);
                                            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                                            $drawing->setWorksheet($sheet);
                                            $drawing->setName('Logo');
                                            $drawing->setDescription('Logo');
                                            $drawing->setPath(base_path().'/public/excelformats/ccsa/rightlogo.png');
                                            $drawing->setHeight(40);
                                            $drawing->setCoordinates('U'.$startcellno);
                                        }
                                        $sheet->getRowDimension($startcellno)->setRowHeight($sheet->getRowDimension($x)->getRowHeight());
                                        if($x != 4 && $x != 7)
                                        {
                                            $sheet->mergeCells('A'.$startcellno.':AI'.$startcellno);
                                            
                                            $styleArray = $sheet->getStyle('A'.$x)->exportArray();
                                            $sheet->getStyle('A'.$startcellno)->applyFromArray($styleArray);
                                        }
                                        if($x != 3)
                                        {
                                            $sheet->getStyle('A'.$startcellno.':AI'.$startcellno)->applyFromArray($borderstyle_white);
                                        }
                                        if($x == 3 && $x == 4)
                                        {                                            
                                            
                                          $sheet->getStyle('A'.$startcellno)
                                        ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);
                                        }
                                        if($x == 4)
                                        {                  
                                            // return 'A'.($startcellno-1);                          
                                          $sheet->getStyle('A'.($startcellno-1).':AI'.($startcellno-1))
                                        ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                        }
                                        // return 'A'.$startcellno;
                                        // return $sheet->getRowDimension($startcellno)->getRowHeight();

                                        $startcellno+=1;
                                    }
                                    $sheet->getStyle('A'.$startcellno.':AI'.$startcellno)->applyFromArray($borderstyle_white);
                                    $sheet->getRowDimension($startcellno)->setRowHeight($sheet->getRowDimension('11')->getRowHeight());
                                    $startcellno+=1;
                                    $sheet->getStyle('A'.$startcellno.':AI'.($startcellno+31))->applyFromArray($borderstyle);
                                    for($letternum = 1; $letternum <=35; $letternum++)
                                    {
                                        if($letternum < 9) //A-H
                                        {
                                            $sheet->mergeCells(getNameFromNumber($letternum).$startcellno.':'.getNameFromNumber($letternum).($startcellno+1));
                                        }elseif($letternum <= 34) //I-AH
                                        {
                                            if($letternum % 2 != 0){
                                                $sheet->mergeCells(getNameFromNumber($letternum).$startcellno.':'.getNameFromNumber($letternum+1).($startcellno)); //upper
                                                $sheet->mergeCells(getNameFromNumber($letternum).($startcellno+1).':'.getNameFromNumber($letternum+1).($startcellno+1)); //lower
                                            }
                                        }else{
                                            $sheet->mergeCells(getNameFromNumber($letternum).$startcellno.':'.getNameFromNumber($letternum).($startcellno+1));
                                        }
                                        $styleArray = $sheet->getStyle(getNameFromNumber($letternum).'12')->exportArray();
                                        $sheet->getStyle(getNameFromNumber($letternum).$startcellno)->applyFromArray($styleArray);
                                    }  
                                    $startcellno+=1;  
                                    for($letternum = 9; $letternum <=35; $letternum++) //upper to lower style copy
                                    {
                                        $styleArray = $sheet->getStyle(getNameFromNumber($letternum).'13')->exportArray();
                                        $sheet->getStyle(getNameFromNumber($letternum).$startcellno)->applyFromArray($styleArray);
                                    }     
                                    $startcellno+=1;        
                                    
                                    foreach($perpage as $eachstudent)
                                    {
                                        for($letternum = 1; $letternum <=35; $letternum++)
                                        {
                                            if($letternum < 9) //A-H
                                            {
                                                $sheet->mergeCells(getNameFromNumber($letternum).$startcellno.':'.getNameFromNumber($letternum).($startcellno+1));
                                            }elseif($letternum <= 34) //I-AH
                                            {
                                                if($letternum % 2 != 0){
                                                    $sheet->mergeCells(getNameFromNumber($letternum).$startcellno.':'.getNameFromNumber($letternum+1).($startcellno)); //upper
                                                    $sheet->mergeCells(getNameFromNumber($letternum).($startcellno+1).':'.getNameFromNumber($letternum+1).($startcellno+1)); //lower
                                                }
                                            }else{
                                                $sheet->mergeCells(getNameFromNumber($letternum).$startcellno.':'.getNameFromNumber($letternum).($startcellno+1));
                                            }
                                            $styleArray = $sheet->getStyle(getNameFromNumber($letternum).'14')->exportArray();
                                            $sheet->getStyle(getNameFromNumber($letternum).$startcellno)->applyFromArray($styleArray);
                                        }  
                                        $sheet->setCellValue('A'.$startcellno,$countstud);
                                        $sheet->setCellValue('B'.$startcellno,$eachstudent->lastname);
                                        $sheet->setCellValue('C'.$startcellno,$eachstudent->firstname);
                                        $sheet->setCellValue('D'.$startcellno,$eachstudent->middlename);
                                        $sheet->setCellValue('E'.$startcellno,$eachstudent->gender[0]);
                                        $sheet->setCellValue('F'.$startcellno,$eachstudent->courseabrv);
                                        $sheet->setCellValue('G'.$startcellno,$eachstudent->yearid);
                                        $sheet->setCellValue('H'.$startcellno,$eachstudent->dob != null ? date('m/d/Y', strtotime($eachstudent->dob)) : '');

                                        
                                        $subjectkey = 0;
                                        if(isset($eachstudent->subjects))
                                        {
                                            if(count($eachstudent->subjects)>0)
                                            {
                                                for($letternum = 9; $letternum <=35; $letternum++)
                                                {
                                                    if($letternum < 35) //I-AH
                                                    {
                                                        if($letternum % 2 != 0){
                                                            if(isset($eachstudent->subjects[$subjectkey]))
                                                            {
                                                                $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,$eachstudent->subjects[$subjectkey]->subjectcode); //upper
                                                                $sheet->setCellValue(getNameFromNumber($letternum).($startcellno+1),$eachstudent->subjects[$subjectkey]->subjunit); //lowerjects
                                                                    $subjectkey +=1;
                                                            }else{
                                                                $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,''); //upper
                                                                $sheet->setCellValue(getNameFromNumber($letternum).($startcellno+1),''); //lowerjects
                                                            }
                                                        }
                                                    }else{
                                                        $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,collect($eachstudent->subjects)->sum('subjunit'));
                                                    }
                                                } 
                                            }else{
                                                for($letternum = 9; $letternum <=35; $letternum++)
                                                {
                                                    if($letternum < 35) //I-AH
                                                    {
                                                        if($letternum % 2 != 0){
                                                            $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,''); //upper
                                                            $sheet->setCellValue(getNameFromNumber($letternum).($startcellno+1),''); //lowerjects
                                                        }
                                                    }else{
                                                        $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,'');
                                                    }
                                                } 
                                            }
                                        }else{
                                            for($letternum = 9; $letternum <=35; $letternum++)
                                            {
                                                if($letternum < 35) //I-AH
                                                {
                                                    if($letternum % 2 != 0){
                                                        $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,''); //upper
                                                        $sheet->setCellValue(getNameFromNumber($letternum).($startcellno+1),''); //lowerjects
                                                    }
                                                }else{
                                                    $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,'');
                                                }
                                            } 
                                        }

                                        $startcellno+=2;
                                        $countstud +=1;
                                    }
                                    // if($keyperpage == 2)
                                    // {
                                    //    return count($perpage);
                                    // }
                                    for($y = count($perpage); $y < 15; $y++)
                                    {
                                        $sheet->setCellValue('A'.$startcellno,$countstud);
                                        $sheet->setCellValue('B'.$startcellno,'');
                                        $sheet->setCellValue('C'.$startcellno,'');
                                        $sheet->setCellValue('D'.$startcellno,'');
                                        $sheet->setCellValue('E'.$startcellno,'');
                                        $sheet->setCellValue('F'.$startcellno,'');
                                        $sheet->setCellValue('G'.$startcellno,'');
                                        $sheet->setCellValue('H'.$startcellno,'');
                                        for($letternum = 1; $letternum <=35; $letternum++)
                                        {
                                            if($letternum < 9) //A-H
                                            {
                                                $sheet->mergeCells(getNameFromNumber($letternum).$startcellno.':'.getNameFromNumber($letternum).($startcellno+1));
                                            }elseif($letternum <= 34) //I-AH
                                            {
                                                if($letternum % 2 != 0){
                                                    $sheet->mergeCells(getNameFromNumber($letternum).$startcellno.':'.getNameFromNumber($letternum+1).($startcellno)); //upper
                                                    $sheet->mergeCells(getNameFromNumber($letternum).($startcellno+1).':'.getNameFromNumber($letternum+1).($startcellno+1)); //lower
                                                    
                                                    $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,''); //upper
                                                    $sheet->setCellValue(getNameFromNumber($letternum).($startcellno+1),''); //lowerjects)>0)
                                                }
                                            }else{
                                                $sheet->mergeCells(getNameFromNumber($letternum).$startcellno.':'.getNameFromNumber($letternum).($startcellno+1));
                                                $sheet->setCellValue(getNameFromNumber($letternum).$startcellno,'');
                                            }
                                            $styleArray = $sheet->getStyle(getNameFromNumber($letternum).'14')->exportArray();
                                            $sheet->getStyle(getNameFromNumber($letternum).$startcellno)->applyFromArray($styleArray);
                                        }  

                                        $startcellno+=2;
                                        $countstud +=1;
                                    }
                                    if(($keyperpage+1)<count($studentsperpage))
                                    {
                                        $sheet->mergeCells('A'.$startcellno.':AI'.$startcellno);
                                        $sheet->setCellValue('A'.$startcellno,($keyperpage+1).' OF '.count($studentsperpage));
    
                                        $startcellno+=1;
                                    }
                                    if(($keyperpage+1)==count($studentsperpage))
                                    {
                                        $sheet->mergeCells('A'.$startcellno.':AI'.$startcellno);
                                        $sheet->setCellValue('A'.$startcellno,'');
    
                                    }
                                }
                            }
                        }
                        
                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment; filename="Enrollment List S.Y '.$sydesc.' '.$semester.'.xls"');
                        $writer->save("php://output");
                        exit;                        
                    
                    }
                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc')
                    {                                
                        $inputFileName = base_path().'/public/excelformats/pcc/enrollmentlist.xlsx';
                        // $sheetname = 'Front';

                        /**  Create a new Reader of the type defined in $inputFileType  **/
                        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                        /**  Advise the Reader of which WorkSheets we want to load  **/
                        $reader->setLoadAllSheets();
                        /**  Load $inputFileName to a Spreadsheet Object  **/
                        $spreadsheet = $reader->load($inputFileName);
                        $sheet = $spreadsheet->getSheet(0);
                        
                        $borderstyle = [
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                                ]
                            ]
                        ];

                        
                        $sheet->setCellValue('A1','ENROLLMENT LIST');
                        $sheet->setCellValue('A2','SY '.$sydesc);
                        $sheet->setCellValue('A3',$semester);
                        $sheet->setCellValue('B5','10067');
                        $sheet->setCellValue('B6',DB::table('schoolinfo')->first()->schoolname);
                        $sheet->setCellValue('B7',DB::table('schoolinfo')->first()->address);

                        $sheet->setCellValue('M5',count($students));
                        
                        if(count($students)>0)
                        {
                            
                            $students = collect($students)->values()->all();     

                            $courses = collect($students)->groupBy('coursename');
                            $startcellno = 13;
                            
                            if(count($courses)>0)
                            {
                                foreach($courses as $coursename => $course)
                                {
                                    // return collect($course)->count();
                                    $sheet->getStyle('A'.$startcellno)->getAlignment()->setVertical('center');
                                    $sheet->setCellValue('A'.$startcellno,$coursename);
                                    $sheet->getStyle('A'.$startcellno)->getAlignment()->setWrapText(true);
                                    // $students = collect($course)->sortBy('sortname')->all();
                                    $course = collect($course)->sortBy('sortcourseandlevel');
                                    if(count($course)>0)
                                    {
                                        foreach($course as $student)
                                        {
                                            $sheet->getStyle('A'.$startcellno.':M'.$startcellno)->applyFromArray($borderstyle);
                                            $sheet->getStyle('B'.$startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('B'.$startcellno,$student->major);
                                            $sheet->getStyle('B'.$startcellno)->getAlignment()->setWrapText(true);
                                            $student->yearlevel = 0;
                                            if($student->levelid == 17)
                                            {
                                                $student->yearlevel = 1;
                                            }
                                            if($student->levelid == 18)
                                            {
                                                $student->yearlevel = 2;
                                            }
                                            if($student->levelid == 19)
                                            {
                                                $student->yearlevel = 3;
                                            }
                                            if($student->levelid == 20)
                                            {
                                                $student->yearlevel = 4;
                                            }
                                            if($student->levelid == 21)
                                            {
                                                $student->yearlevel = 5;
                                            }
                                            $sheet->setCellValue('C'.$startcellno,$student->sid);
                                            $sheet->setCellValue('D'.$startcellno,$student->yearlevel);
                                            if($student->lastname != null)
                                            {
                                                $sheet->setCellValue('E'.$startcellno,$student->lastname);
                                            }
                                            if($student->firstname != null)
                                            {
                                                $sheet->setCellValue('F'.$startcellno,$student->firstname);
                                            }
                                            if($student->middlename != null)
                                            {
                                                $sheet->setCellValue('G'.$startcellno,$student->middlename);
                                            }
                                            if($student->suffix != null)
                                            {
                                                $sheet->setCellValue('H'.$startcellno,$student->suffix);
                                            }
                                            if($student->gender != null)
                                            {
                                                $sheet->setCellValue('I'.$startcellno,ucwords(strtolower($student->gender)));
                                            }
                                            if($student->dob != null)
                                            {
                                                $sheet->setCellValue('J'.$startcellno,$student->nationality);
                                            }
                                            if(count($student->subjects)>0)
                                            {
                                                foreach($student->subjects as $eachsubject)
                                                {
                                                    $sheet->getStyle('A'.$startcellno.':M'.$startcellno)->applyFromArray($borderstyle);
                                                    $sheet->setCellValue('K'.$startcellno,$eachsubject->subjectcode);
                                                    $sheet->getStyle('L'.$startcellno)->getAlignment()->setVertical('center');
                                                    $sheet->setCellValue('L'.$startcellno,$eachsubject->subjectname);
                                                    $sheet->getStyle('L'.$startcellno)->getAlignment()->setWrapText(true);
                                                    $sheet->setCellValue('M'.$startcellno,$eachsubject->subjunit);
                                                    $startcellno+=1;
                                                    // $sheet->insertNewRowBefore($startcellno+1);
                                                }
                                                $sheet->getStyle('A'.$startcellno.':M'.$startcellno)->applyFromArray($borderstyle);
                                            }
                                            $startcellno+=1;
                                            // $sheet->insertNewRowBefore($startcellno+1);
                                        }
                                    }
                                }
                            }
                            $coursestartcellno = $startcellno;
                            if(count($courses)>0)
                            {
                                $sheet->setCellValue('A'.$coursestartcellno,'SUMMARY OF STUDENTS PER PROGRAM:');
                                $coursestartcellno+=1;
                                foreach($courses as $key=>$course)
                                {
                                    $sheet->setCellValue('B'.$coursestartcellno,$key);
                                    $sheet->setCellValue('F'.$coursestartcellno,count($course));
                                    $sheet->getStyle('F'.$coursestartcellno)
                                        ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                    $coursestartcellno +=1;
                                }
                            }
                            
                        }
                        // return 'yozupp';
                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment; filename="Enrollment List S.Y '.$sydesc.' '.$semester.'.xlsx"');
                        $writer->save("php://output");
                        exit;
                    }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                    {                                   
                                
                        $inputFileName = base_path().'/public/excelformats/sic/enrollmentlist.xlsx';
                        // $sheetname = 'Front';

                        /**  Create a new Reader of the type defined in $inputFileType  **/
                        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                        /**  Advise the Reader of which WorkSheets we want to load  **/
                        $reader->setLoadAllSheets();
                        /**  Load $inputFileName to a Spreadsheet Object  **/
                        $spreadsheet = $reader->load($inputFileName);
                        $sheet = $spreadsheet->getSheet(0);
                        
                        $borderstyle = [
                            // 'alignment' => [
                            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                            // ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                                ]
                            ]
                        ];

                        
                        $sheet->setCellValue('A1','ENROLLMENT LIST');
                        $sheet->setCellValue('A2','SY '.$sydesc);
                        $sheet->setCellValue('A3',$semester);
                        $sheet->setCellValue('B5',DB::table('schoolinfo')->first()->schoolid);
                        $sheet->setCellValue('B6',DB::table('schoolinfo')->first()->schoolname);
                        $sheet->setCellValue('B7',DB::table('schoolinfo')->first()->address);

                        $sheet->setCellValue('L5','TOTAL NO. OF STUDENTS: ___'.count($students).'___');
                        
                        if(count($students)>0)
                        {
                            foreach($students as $student)
                            {
                                $student->sortname = $student->lastname.' '.$student->firstname;
                                $student->sortcourseandlevel = $student->coursename.' '.$student->sortid.' '.$student->sortname;
                                $subjects = DB::table('college_studsched')
                                    ->join('college_classsched',function($join)use($syid,$semid){
                                        $join->on('college_studsched.schedid','=','college_classsched.id');
                                        $join->where('college_classsched.deleted',0);
                                        $join->where('syID',$syid);
                                        $join->where('semesterID',$semid);
                                    })
                                    ->join('college_prospectus',function($join)use($syid,$semid){
                                        $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                        $join->where('college_prospectus.deleted',0);
                                    })
                                    ->leftJoin('college_studentprospectus',function($join)use($syid,$semid, $student){
                                        $join->on('college_prospectus.id','=','college_studentprospectus.prospectusID');
                                        $join->where('college_studentprospectus.deleted',0);
                                        $join->where('college_studentprospectus.syid',$syid);
                                        $join->where('college_studentprospectus.semid',$semid);
                                        $join->where('college_studentprospectus.studid',$student->id);
                                    })
                                    ->where('schedstatus','!=','DROPPED')
                                    ->where('college_studsched.deleted',0)
                                    ->where('college_studsched.studid',$student->id)
                                    ->select('subjCode as subjectcode','subjDesc as subjectname', 'labunits','lecunits','finalgrade as subjgrade','college_prospectus.psubjsort as subjsort')
                                    ->orderBy('subjsort')
                                    ->get();
                
                                $subjects = collect($subjects)->unique();
                                if(count($subjects)>0)
                                {
                                    foreach($subjects as $grade)
                                    {
                                        $grade->subjcredit = 0;
                                        $grade->units = ($grade->lecunits+$grade->labunits);
                                    }
                                }
                                $student->subjects = $subjects;
                            }
                            $students = collect($students)->values()->all();     
                            $coursestartcellno = 17;
                            $courses = collect($students)->groupBy('coursename');
                            if(count($courses)>0)
                            {
                                foreach($courses as $key=>$course)
                                {
                                    $sheet->setCellValue('B'.$coursestartcellno,$key);
                                    $sheet->setCellValue('F'.$coursestartcellno,count($course));
                                    $sheet->getStyle('F'.$coursestartcellno)
                                        ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                    $coursestartcellno +=1;
                                }
                            }
                            
                            $startcellno = 13;
                            // $students = collect($students)->sortBy('sortcourseandlevel')->all();
                            $students = collect($students)->values()->all();     
                            
                            $courses = collect($students)->groupBy('coursename');
                            // return $courses;
                            if(count($courses)>0)
                            {
                                foreach($courses as $coursename => $course)
                                {
                                    $sheet->getStyle('A'.$startcellno)->getAlignment()->setVertical('center');
                                    $sheet->setCellValue('A'.$startcellno,$coursename);
                                    $sheet->getStyle('A'.$startcellno)->getAlignment()->setWrapText(true);
                                    $sheet->getRowDimension($startcellno)->setRowHeight(25);

                                    // $students = collect($course)->sortBy('sortname')->all();
                                    $course = collect($course)->sortBy('sortcourseandlevel');
                                    if(count($course)>0)
                                    {
                                        foreach($course as $student)
                                        {
                                            $sheet->getStyle('B'.$startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('B'.$startcellno,$student->major);
                                            $sheet->getStyle('B'.$startcellno)->getAlignment()->setWrapText(true);
                                            $student->yearlevel = 0;
                                            if($student->levelid == 17)
                                            {
                                                $student->yearlevel = 1;
                                            }
                                            if($student->levelid == 18)
                                            {
                                                $student->yearlevel = 2;
                                            }
                                            if($student->levelid == 19)
                                            {
                                                $student->yearlevel = 3;
                                            }
                                            if($student->levelid == 20)
                                            {
                                                $student->yearlevel = 4;
                                            }
                                            if($student->levelid == 21)
                                            {
                                                $student->yearlevel = 5;
                                            }
                                            $sheet->setCellValue('C'.$startcellno,$student->sid);
                                            $sheet->setCellValue('D'.$startcellno,$student->yearlevel);
                                            if($student->lastname != null)
                                            {
                                                $sheet->setCellValue('E'.$startcellno,$student->lastname);
                                            }
                                            if($student->firstname != null)
                                            {
                                                $sheet->setCellValue('F'.$startcellno,$student->firstname);
                                            }
                                            if($student->middlename != null)
                                            {
                                                $sheet->setCellValue('G'.$startcellno,$student->middlename);
                                            }
                                            if($student->suffix != null)
                                            {
                                                $sheet->setCellValue('H'.$startcellno,$student->suffix);
                                            }
                                            if($student->gender != null)
                                            {
                                                $sheet->setCellValue('I'.$startcellno,ucwords(strtolower($student->gender)));
                                            }
                                            if($student->dob != null)
                                            {
                                                $sheet->setCellValue('J'.$startcellno,$student->nationality);
                                            }
                                            if(count($student->subjects)>0)
                                            {
                                                foreach($student->subjects as $eachsubject)
                                                {
                                                    if(strlen($eachsubject->subjectname)>58)
                                                    {
                                                        $sheet->getRowDimension($startcellno)->setRowHeight(45);
                                                        $sheet->getStyle('L'.$startcellno)->getAlignment()->setWrapText(true);
                                                    }
                                                    $sheet->setCellValue('K'.$startcellno,$eachsubject->subjectcode);
                                                    $sheet->getStyle('L'.$startcellno)->getAlignment()->setVertical('center');
                                                    $sheet->setCellValue('L'.$startcellno,$eachsubject->subjectname);
                                                    $sheet->setCellValue('M'.$startcellno,$eachsubject->units);
                                                    // $sheet->setCellValue('N'.$startcellno,$eachsubject->subjgrade);
                                                    // if($eachsubject->subjgrade != null)
                                                    // {
                                                    //     $sheet->setCellValue('O'.$startcellno,$eachsubject->subjgrade < 5.0 ? 'PASSED' : 'FAILED');
                                                    // }
                                                    $startcellno+=1;
                                                    $sheet->insertNewRowBefore($startcellno+1);
                                                }
                                            }
                                            $startcellno+=1;
                                            $sheet->insertNewRowBefore($startcellno+1);
                                        }
                                    }
                                }
                            }
                        }
                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment; filename="Enrollment List S.Y '.$sydesc.' '.$semester.'.xlsx"');
                        $writer->save("php://output");
                        exit;
                        
                    }
                    elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa')
                    {
                        if($request->has('template'))
                        {
                            if($request->get('template') == '1')
                            {
                                defaulttemplate_ched($students,$sydesc,$semester);
                            }else{
                                template_ccsa($students,$sydesc,$semester);
                            }
                        }else{
                            template_ccsa($students,$sydesc,$semester);
                        }
                    }
                    else{
                        defaulttemplate_ched($students,$sydesc,$semester);
                    }

                }
            }
        }
    }
	public function printablestudentsvaccindex(Request $request)
   {
        if(!$request->has('action'))
        {
            return view('registrar.summaries.studentvaccination.index');
        }else{
            $schoolyears = Db::table('sy')
            ->select('id as syid','sydesc'
            ,'isactive'
            )
            ->where('id',$request->get('syid'))
            ->get();
            


            $students1   = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.lrn',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.dob',
                'studinfo.mol',
                'enrolledstud.levelid',
                'gradelevel.levelname',
                'sections.id as sectionid',
                'sections.sectionname'
                )
            ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
            ->leftJoin('gradelevel','enrolledstud.levelid','=','gradelevel.id')
            ->leftJoin('sections','enrolledstud.sectionid','=','sections.id')
            ->where('studinfo.deleted','0')
            ->where('enrolledstud.deleted','0')
            ->whereIn('enrolledstud.studstatus',[1,2,4])
            ->where('enrolledstud.syid',$request->get('syid'))
            ->get();

            $students2   = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.lrn',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.dob',
                'studinfo.mol',
                'sh_enrolledstud.levelid',
                'gradelevel.levelname',
                'sections.id as sectionid',
                'sections.sectionname'
                )
            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
            ->leftJoin('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
            ->leftJoin('sections','sh_enrolledstud.sectionid','=','sections.id')
            ->where('studinfo.deleted','0')
            ->where('sh_enrolledstud.deleted','0')
            ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
            ->where('sh_enrolledstud.syid',$request->get('syid'))
            ->get();

            $students3   = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.lrn',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.dob',
                'studinfo.mol',
                'college_enrolledstud.yearLevel as levelid',
                'gradelevel.levelname',
                'college_sections.id as sectionid',
                'college_sections.sectionDesc as sectionname'
                )
            ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
            ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
            ->leftJoin('college_year','gradelevel.id','=','college_year.levelid')
            ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
            ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
            ->where('studinfo.deleted','0')
            ->where('college_enrolledstud.deleted','0')
            ->whereIn('college_enrolledstud.studstatus',[1,2,4])
            ->where('college_enrolledstud.syid',$request->get('syid'))
            ->get();
            
            $students = collect();
            $students = $students->merge($students1);
            $students = $students->merge($students2);
            $students = $students->merge($students3);
            $students = $students->unique('id')->all();

            $allstudents = array();
            if($request->get('levelid')>0)
            {
                $students = collect($students)->where('levelid', $request->get('levelid'))->values();
            }
            if($request->get('mod')>0)
            {
                $students = collect($students)->where('mol', $request->get('mod'))->values();
            }
            if(count($students)>0)
            {
                foreach($students as $student)
                {
                    $student->sortname = $student->lastname.' '.$student->firstname;
                    $checkifexists = DB::table('apmc_midinfo')
                        ->where('studid', $student->id)
                        // ->where('syid', $request->get('syid'))
                        ->where('deleted','0')
                        ->first();

                    if($checkifexists)
                    {
                        $student->medinfo = $checkifexists;
                        $student->vaccstatus = $checkifexists->vacc;

                    }else{
                        $student->vaccstatus = 0;
                    }
                    array_push($allstudents, $student);
                }
            }
            // return count($allstudents);
            
            // return $request->all();
            if($request->get('status')!='all')
            {
                $allstudents = collect($allstudents)->where('vaccstatus', $request->get('status'))->values();
            }
            $allstudents = collect($allstudents)->unique('id')->sortBy('sortname')->values()->all();
            // $allstudents = collect($allstudents)->unique('id');
            // return count($allstudents); 
            if($request->get('action') == 'filter')
            {
                $checkifexist = DB::table('signatory')
                ->where('form','enrollment_report_reg')
                ->where('deleted','0')
                ->get();
                
                return view('registrar.summaries.studentvaccination.results')
                    // ->with('maxsubjects',$maxsubjects)
                    // ->with('signatories',$checkifexist)
                    ->with('students',$allstudents);
            }else{
                $schoolregistrar = $request->get('registrar');
                $president = $request->get('president');

                $checkifexistregistrar = DB::table('signatory')
                ->where('form','enrollment_report_reg')
                ->where('title','Registrar')
                ->where('deleted','0')
                ->first();
    
                if($checkifexistregistrar)
                {
                    if($schoolregistrar == null || $schoolregistrar == "")
                    {
                        $schoolregistrar = $checkifexistregistrar->name;
                    }else{
                        if($schoolregistrar != $checkifexistregistrar->name)
                        {
                            DB::table('signatory')
                                ->where('id', $checkifexistregistrar->id)
                                ->update([
                                    'name'      => $schoolregistrar,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }else{
                    if($schoolregistrar != null || $schoolregistrar != "")
                    {
                        DB::table('signatory')
                            ->insert([
                                'form'      => 'enrollment_report_reg',
                                'name'      => $schoolregistrar,
                                'title'      => 'Registrar',
                                'createdby' => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                $checkifexistpresident = DB::table('signatory')
                ->where('form','enrollment_report_reg')
                ->where('title','President')
                ->where('deleted','0')
                ->first();
    
                if($checkifexistpresident)
                {
                    if($president == null || $president == "")
                    {
                        $president = $checkifexistpresident->name;
                    }else{
                        if($president != $checkifexistpresident->name)
                        {
                            DB::table('signatory')
                                ->where('id', $checkifexistpresident->id)
                                ->update([
                                    'name'      => $president,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }else{
                    if($president != null || $president != "")
                    {
                        DB::table('signatory')
                            ->insert([
                                'form'      => 'enrollment_report_reg',
                                'name'      => $president,
                                'title'      => 'President',
                                'createdby' => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                $sydesc = 'S.Y. '.DB::table('sy')->where('id', $request->get('syid'))->first()->sydesc ?? null;
                $levelname = DB::table('gradelevel')->where('id',$request->get('levelid'))->first() ? 'Grade '.DB::table('gradelevel')->where('id',$request->get('levelid'))->first()->levelname : null;
                $seminfo = DB::table('semester')->where('id', $request->get('semid'))->first();
                $registrar = $schoolregistrar;
                $students = $allstudents;
                // return count($students); 
                $pdf = PDF::loadview('registrar/summaries/studentvaccination/pdf_studentvaccination',compact('students','sydesc','levelname','registrar','president'));
                return $pdf->stream('Students Vaccination Status.pdf');
            }
        }
    }
    public function ssf(Request $request)
    {
        $acadprogid = DB::table('gradelevel')->where('id', $request->get('levelid'))->first()->acadprogid;
        if($request->has('semid'))
        {
            $semid = $request->get('semid');
        }else{
            $semid = DB::table('semester')
            ->where('isactive', '1')->first()->id;
        }
        if($acadprogid == 5)
        {
            $students = DB::table('sh_enrolledstud')
                ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.middlename','studinfo.firstname','studinfo.suffix',DB::raw('LOWER(`gender`) as gender'),'sh_enrolledstud.levelid','sh_enrolledstud.sectionid','sh_strand.id as strandid','sh_strand.strandname','sh_strand.strandcode','grantee.description as granteedesc','sh_track.trackname')
                ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                ->leftJoin('grantee','studinfo.grantee','=','grantee.id')
                ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                ->where('sh_enrolledstud.syid', $request->get('syid'))
                // ->where('sh_enrolledstud.semid', $request->get('semid'))
                ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                ->where('sh_enrolledstud.studstatus','!=','0')
                ->where('sh_enrolledstud.studstatus','<=','5')
                ->where('sh_enrolledstud.deleted','0')
                ->where('studinfo.deleted','0')
                ->distinct('studinfo.id')
                ->get();
                
        }else{
            $students = DB::table('enrolledstud')
                ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.middlename','studinfo.firstname','studinfo.suffix',DB::raw('LOWER(`gender`) as gender'),'enrolledstud.levelid','enrolledstud.sectionid','grantee.description as granteedesc')
                ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                ->leftJoin('grantee','studinfo.grantee','=','grantee.id')
                ->where('enrolledstud.levelid', $request->get('levelid'))
                ->where('enrolledstud.syid', $request->get('syid'))
                ->where('enrolledstud.sectionid', $request->get('sectionid'))
                ->where('enrolledstud.studstatus','!=','0')
                ->where('enrolledstud.studstatus','<=','5')
                ->where('enrolledstud.deleted','0')
                ->where('studinfo.deleted','0')
                ->distinct('studinfo.id')
                ->get();
        }
                if(count($students)>0)
                {
                    foreach($students as $student)
                    {
                        $student->sortthis = $student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename;
                    }
                }
                $students = collect($students)->sortBy('sortthis')->values();
        foreach($students as $student)
        {
            
            if($acadprogid == 5)
            {
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($student->levelid,$student->id,$request->get('syid'),$student->strandid,null,$student->sectionid);
            }else{
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($student->levelid,$student->id,$request->get('syid'),null,null,$student->sectionid);
            }
            $generalaverage = array();
            foreach($studgrades as $item){
                if($item->id == 'G1'){
                    array_push($generalaverage,$item);
                    // array_push($temp_grades,$item);
                }
            }
            if(count($generalaverage) == 0)
            {
                $student->q1 = null;
                $student->q2 = null;
                $student->q3 = null;
                $student->q4 = null;
                $student->finalrating = null;
                $student->status = null;
            }else{
                if($acadprogid == 5)
                {
                    $student->q1 = number_format(collect($generalaverage)->where('semid',1)->first()->q1comp,2);
                    $student->q2 = number_format(collect($generalaverage)->where('semid',1)->first()->q2comp,2);
                    $student->fcomp1 = round(collect($generalaverage)->where('semid',1)->first()->fcomp);
                    $student->q3 = number_format(collect($generalaverage)->where('semid',2)->first()->q3comp,2);
                    $student->q4 = number_format(collect($generalaverage)->where('semid',2)->first()->q4comp,2);
                    $student->fcomp2 = round(collect($generalaverage)->where('semid',2)->first()->fcomp);
                    // $student->finalrating = round(collect($generalaverage)->avg('fcomp'));
                    $student->status = collect($generalaverage)->avg('fcomp') < 75 ? 'FAILED' : 'PASSED';
                }else{
                    $student->q1 = $generalaverage[0]->q1;
                    $student->q2 = $generalaverage[0]->q2;
                    $student->q3 = $generalaverage[0]->q3;
                    $student->q4 = $generalaverage[0]->q4;
                    $student->finalrating = $generalaverage[0]->finalrating;
                    $student->status = $generalaverage[0]->finalrating < 75 ? 'FAILED' : 'PASSED';
                }
            }
        
        }
        // return $students;
        $sydesc = DB::table('sy')->where('id', $request->get('syid'))->first()->sydesc;
        $levelname = DB::table('gradelevel')->where('id', $request->get('levelid'))->first()->levelname;
        $sectionname = DB::table('sections')->where('id', $request->get('sectionid'))->first()->sectionname;
        $sydesc = DB::table('sy')->where('id', $request->get('syid'))->first()->sydesc;
        
        $sectiondetail = DB::table('sectiondetail')
        ->select('sections.*','teacher.id as teacherid','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix','gradelevel.acadprogid')
        ->join('sections','sectiondetail.sectionid','=','sections.id')
        ->join('gradelevel','sections.levelid','=','gradelevel.id')
        ->leftJoin('teacher','sectiondetail.teacherid','=','teacher.id')
        ->where('sectiondetail.syid', $request->get('syid'))
        ->where('sectiondetail.sectionid', $request->get('sectionid'))
        ->where('gradelevel.acadprogid',$acadprogid)
        ->where('gradelevel.deleted',0)
        ->where('sectiondetail.deleted',0)
        ->where('sections.deleted',0)
        ->orderBy('sectionname','asc')
        ->first();

        $teachername = null;
        if($sectiondetail)
        {
            $teachername.=$sectiondetail->firstname.' '.$sectiondetail->middlename.' '.$sectiondetail->lastname.' '.$sectiondetail->suffix;
        }
        // return $students;
        if($acadprogid == 5)
        {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path().'/public/excelformats/apmc/ssf-shs.xlsx');
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('STATUS');
            $border    = [
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
    
            $sheet->setCellValue('I5', $sydesc);
            $sheet->setCellValue('M7', $levelname.' '.$sectionname);
    
            $sheet->setCellValue('D16', $teachername);
            $sheet->setCellValue('L16', DB::table('schoolinfo')->first()->authorized);
            
            // // $sheet->getRowDimension('29')->setRowHeight(18);
            $malecount = 1;
            $startcellno = 12;
            foreach(collect($students)->where('gender','male')->values() as $student)
            {
                    if($malecount == 1)
                    {
                        $sheet->insertNewRowBefore($startcellno+2);
                    }else{
                        $sheet->insertNewRowBefore($startcellno+1);
                    }
                    if(strlen(strtoupper($student->lastname).', '.strtoupper($student->firstname).' '.strtoupper($student->middlename).' '.strtoupper($student->suffix)) <= 30)
                    {
                        $sheet->getRowDimension($startcellno)->setRowHeight(18);
                    }else{
                        $sheet->getRowDimension($startcellno)->setRowHeight(36);
                    }
                    $sheet->setCellValue('A'.$startcellno, $student->trackname.' - '.$student->strandcode);
                    $sheet->setCellValue('B'.$startcellno, $malecount);
                    $sheet->setCellValue('C'.$startcellno, $student->lrn.' ');
                    $sheet->setCellValue('D'.$startcellno, strtoupper($student->lastname).', '.strtoupper($student->firstname).' '.strtoupper($student->suffix).' '.strtoupper($student->middlename));
                    $sheet->setCellValue('E'.$startcellno, strtoupper($student->gender));
                    $sheet->setCellValue('F'.$startcellno, $student->granteedesc);
                    $sheet->setCellValue('G'.$startcellno, $student->q1);
                    $sheet->setCellValue('H'.$startcellno, $student->q2);
                    $sheet->setCellValue('I'.$startcellno, $student->fcomp1);
                    $sheet->setCellValue('J'.$startcellno, $student->q3);
                    $sheet->setCellValue('K'.$startcellno, $student->q4);
                    $sheet->setCellValue('L'.$startcellno, $student->fcomp2);
                    $sheet->setCellValue('M'.$startcellno, $student->status);
                    // $sheet->getStyle('A'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('B'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('C'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('D'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('E'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('F'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('G'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('H'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('I'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('J'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('K'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('L'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('M'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('N'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('0'.$startcellno)->applyFromArray($border);
    
                    $sheet->getStyle('D'.$startcellno.':M'.$startcellno)->getAlignment()->setHorizontal('center');
                    
                    $malecount += 1;
                    $startcellno += 1;
            }
            $sheet->getRowDimension($startcellno)->setRowHeight(18);
            $femalecount = 1;
            $startcellno+=1;
            foreach(collect($students)->where('gender','female')->values() as $student)
            {
                    if($femalecount == 1)
                    {
                        $sheet->insertNewRowBefore($startcellno+2);
                    }else{
                        $sheet->insertNewRowBefore($startcellno+1);
                    }
                    if(strlen(strtoupper($student->lastname).', '.strtoupper($student->firstname).' '.strtoupper($student->middlename).' '.strtoupper($student->suffix)) <= 30)
                    {
                        $sheet->getRowDimension($startcellno)->setRowHeight(18);
                    }else{
                        $sheet->getRowDimension($startcellno)->setRowHeight(36);
                    }
                    $sheet->setCellValue('A'.$startcellno, $student->trackname.' - '.$student->strandcode);
                    $sheet->setCellValue('B'.$startcellno, $femalecount);
                    $sheet->setCellValue('C'.$startcellno, $student->lrn.' ');
                    $sheet->setCellValue('D'.$startcellno, strtoupper($student->lastname).', '.strtoupper($student->firstname).' '.strtoupper($student->suffix).' '.strtoupper($student->middlename));
                    $sheet->setCellValue('E'.$startcellno, strtoupper($student->gender));
                    $sheet->setCellValue('F'.$startcellno, $student->granteedesc);
                    $sheet->setCellValue('G'.$startcellno, $student->q1);
                    $sheet->setCellValue('H'.$startcellno, $student->q2);
                    $sheet->setCellValue('I'.$startcellno, $student->fcomp1);
                    $sheet->setCellValue('J'.$startcellno, $student->q3);
                    $sheet->setCellValue('K'.$startcellno, $student->q4);
                    $sheet->setCellValue('L'.$startcellno, $student->fcomp2);
                    $sheet->setCellValue('M'.$startcellno, $student->status);
                    // $sheet->getStyle('A'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('B'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('C'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('D'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('E'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('F'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('G'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('H'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('I'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('J'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('K'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('L'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('M'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('N'.$startcellno)->applyFromArray($border);
                    // $sheet->getStyle('0'.$startcellno)->applyFromArray($border);

                    $sheet->getStyle('D'.$startcellno.':M'.$startcellno)->getAlignment()->setHorizontal('center');
                    $femalecount += 1;
                    $startcellno += 1;
            }
            
            // set_time_limit(1300);
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Student Status Form - '.$sydesc.' - '.$levelname.' '.$sectionname.'.xlsx"');
            $writer->save("php://output");
        }else{
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path().'/public/excelformats/apmc/ssf.xlsx');
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('STATUS');
            $border    = [
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
    
            $sheet->setCellValue('G5', $sydesc);
            $sheet->setCellValue('J7', $levelname.' '.$sectionname);
    
            $sheet->setCellValue('B16', $teachername);
            $sheet->setCellValue('H16', DB::table('schoolinfo')->first()->authorized);
            
            // $sheet->getRowDimension('29')->setRowHeight(18);
            $malecount = 1;
            $startcellno = 12;
            foreach($students as $student)
            {
                if(strtolower($student->gender) == 'male')
                {
                    if($malecount == 1)
                    {
                        $sheet->insertNewRowBefore($startcellno+2);
                    }else{
                        $sheet->insertNewRowBefore($startcellno+1);
                    }
                    if(strlen($student->lrn.' '.strtoupper($student->lastname).', '.strtoupper($student->firstname).' '.strtoupper($student->middlename).' '.strtoupper($student->suffix)) <= 45)
                    {
                        $sheet->getRowDimension($startcellno)->setRowHeight(18);
                    }else{
                        $sheet->getRowDimension($startcellno)->setRowHeight(36);
                    }
                    $sheet->setCellValue('A'.$startcellno, $malecount);
                    $sheet->setCellValue('B'.$startcellno, $student->lrn.' '.strtoupper($student->lastname).', '.strtoupper($student->firstname).' '.strtoupper($student->suffix).' '.strtoupper($student->middlename));
                    $sheet->setCellValue('C'.$startcellno, strtoupper($student->gender));
                    $sheet->setCellValue('D'.$startcellno, $student->granteedesc);
                    $sheet->setCellValue('E'.$startcellno, $student->q1);
                    $sheet->setCellValue('F'.$startcellno, $student->q2);
                    $sheet->setCellValue('G'.$startcellno, $student->q3);
                    $sheet->setCellValue('H'.$startcellno, $student->q4);
                    $sheet->setCellValue('I'.$startcellno, $student->finalrating);
                    $sheet->setCellValue('J'.$startcellno, $student->status);
                    $sheet->getStyle('A'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('B'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('C'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('D'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('E'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('F'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('G'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('H'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('I'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('J'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('K'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('L'.$startcellno)->applyFromArray($border);
    
                    $sheet->getStyle('D'.$startcellno.':J'.$startcellno)->getAlignment()->setHorizontal('center');
                    
                    $malecount += 1;
                    $startcellno += 1;
                }
            }
            $sheet->getRowDimension($startcellno)->setRowHeight(18);
            $femalecount = 1;
            $startcellno+=1;
            foreach($students as $student)
            {
                if(strtolower($student->gender) == 'female')
                {
                    if($femalecount == 1)
                    {
                        $sheet->insertNewRowBefore($startcellno+2);
                    }else{
                        $sheet->insertNewRowBefore($startcellno+1);
                    }
                    if(strlen($student->lrn.' '.strtoupper($student->lastname).', '.strtoupper($student->firstname).' '.strtoupper($student->middlename).' '.strtoupper($student->suffix)) <= 45)
                    {
                        $sheet->getRowDimension($startcellno)->setRowHeight(18);
                    }else{
                        $sheet->getRowDimension($startcellno)->setRowHeight(36);
                    }
                    $sheet->setCellValue('A'.$startcellno, $femalecount);
                    $sheet->setCellValue('B'.$startcellno, $student->lrn.' '.strtoupper($student->lastname).', '.strtoupper($student->firstname).' '.strtoupper($student->suffix).' '.strtoupper($student->middlename));
                    $sheet->setCellValue('C'.$startcellno, strtoupper($student->gender));
                    $sheet->setCellValue('D'.$startcellno, $student->granteedesc);
                    $sheet->setCellValue('E'.$startcellno, $student->q1);
                    $sheet->setCellValue('F'.$startcellno, $student->q2);
                    $sheet->setCellValue('G'.$startcellno, $student->q3);
                    $sheet->setCellValue('H'.$startcellno, $student->q4);
                    $sheet->setCellValue('I'.$startcellno, $student->finalrating);
                    $sheet->setCellValue('J'.$startcellno, $student->status);
                    $sheet->getStyle('A'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('B'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('C'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('D'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('E'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('F'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('G'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('H'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('I'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('J'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('K'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('L'.$startcellno)->applyFromArray($border);
                    $sheet->getStyle('D'.$startcellno.':J'.$startcellno)->getAlignment()->setHorizontal('center');
                    $femalecount += 1;
                    $startcellno += 1;
                }
            }
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Student Status Form - '.$sydesc.' - '.$levelname.' '.$sectionname.'.xlsx"');
            $writer->save("php://output");
        }
    }
    public function printableclearance(Request $request)
    {
        if($request->has('syid'))
        {
            $syid = $request->get('syid');
        }else{
            $syid = DB::table('sy')
            ->where('isactive',1)
            ->first()->id;
        }
        if($request->has('semid'))
        {
            $semid = $request->get('semid');
        }else{
            $semid = DB::table('semester')
            ->where('isactive',1)
            ->first()->id;
        }
        
        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
        {                
            $students   = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.lrn',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.dob',
                'studinfo.mol',
                'college_enrolledstud.yearLevel as levelid',
                'gradelevel.levelname',
                'college_sections.id as sectionid',
                'college_sections.sectionDesc as sectionname'
                )
            ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
            ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
            ->leftJoin('college_year','gradelevel.id','=','college_year.levelid')
            ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
            ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
            ->where('studinfo.deleted','0')
            ->where('college_enrolledstud.deleted','0')
            ->whereIn('college_enrolledstud.studstatus',[1,2,4])
            ->where('college_enrolledstud.syid',$syid)
            ->where('college_enrolledstud.semid',$semid)
            ->get();
        }else{
            $students1   = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.lrn',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.dob',
                'studinfo.mol',
                'enrolledstud.levelid',
                'gradelevel.levelname',
                'sections.id as sectionid',
                'sections.sectionname'
                )
            ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
            ->leftJoin('gradelevel','enrolledstud.levelid','=','gradelevel.id')
            ->leftJoin('sections','enrolledstud.sectionid','=','sections.id')
            ->where('studinfo.deleted','0')
            ->where('enrolledstud.deleted','0')
            ->whereIn('enrolledstud.studstatus',[1,2,4])
            ->where('enrolledstud.syid',$syid)
            ->get();

            $students2   = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.lrn',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.dob',
                'studinfo.mol',
                'sh_enrolledstud.levelid',
                'gradelevel.levelname',
                'sections.id as sectionid',
                'sections.sectionname'
                )
            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
            ->leftJoin('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
            ->leftJoin('sections','sh_enrolledstud.sectionid','=','sections.id')
            ->where('studinfo.deleted','0')
            ->where('sh_enrolledstud.deleted','0')
            ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
            ->where('sh_enrolledstud.syid',$syid)
            ->where('sh_enrolledstud.semid',$semid)
            ->get();

            $students3   = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.lrn',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.dob',
                'studinfo.mol',
                'college_enrolledstud.yearLevel as levelid',
                'gradelevel.levelname',
                'college_sections.id as sectionid',
                'college_sections.sectionDesc as sectionname'
                )
            ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
            ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
            ->leftJoin('college_year','gradelevel.id','=','college_year.levelid')
            ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
            ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
            ->where('studinfo.deleted','0')
            ->where('college_enrolledstud.deleted','0')
            ->whereIn('college_enrolledstud.studstatus',[1,2,4])
            ->where('college_enrolledstud.syid',$syid)
            ->where('college_enrolledstud.semid',$semid)
            ->get();
            
            $students = collect();
            $students = $students->merge($students1);
            $students = $students->merge($students2);
            $students = $students->merge($students3);
            $students = $students->unique('id')->all();
        }
        if(count($students)>0)
        {
            foreach($students as $student)
            {
                $student->sortname = $student->lastname.', '.$student->firstname.' '.$student->middlename;
            }
        }
        if(!$request->has('action'))
        {            
            return view('registrar.otherprintables.clearance.college.index')
                ->with('students',$students);
        }else{
            if($request->get('action') == 'getstudents')
            {
                return collect($students)->sortBy('sortname')->values()->all();
            }elseif($request->get('action') == 'gettemplate')
            {
                $studinfo = DB::table('studinfo')->where('id', $request->get('studentid'))->first();
                return view('registrar.otherprintables.clearance.college.template')
                    ->with('students',$students);
            }
        }
    }
    public function printablecertificationeligtotransfer(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        if($request->has('syid'))
        {
            $syid = $request->get('syid');
        }else{
            $syid = DB::table('sy')
            ->where('isactive',1)
            ->first()->id;
        }
        // if($request->has('semid'))
        // {
        //     $semid = $request->get('semid');
        // }else{
        //     $semid = DB::table('semester')
        //     ->where('isactive',1)
        //     ->first()->id;
        // }

        $students   = DB::table('studinfo')
        ->select(
            'studinfo.id',
            'studinfo.sid',
            'studinfo.lrn',
            'studinfo.lastname',
            'studinfo.firstname',
            'studinfo.middlename',
            'studinfo.suffix',
            'studinfo.gender',
            'studinfo.dob',
            'studinfo.mol',
            'college_enrolledstud.yearLevel as levelid',
            'gradelevel.levelname',
            'college_sections.id as sectionid',
            'college_sections.sectionDesc as sectionname',
            'college_courses.courseDesc as coursename',
            'college_colleges.collegeDesc as collegename'
            )
        ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
        ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
        ->leftJoin('college_year','gradelevel.id','=','college_year.levelid')
        ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
        ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
        ->join('college_colleges','college_courses.collegeid','=','college_colleges.id')
        ->where('studinfo.deleted','0')
        ->where('college_enrolledstud.deleted','0')
        ->whereIn('college_enrolledstud.studstatus',[1,2,4])
        ->where('college_enrolledstud.syid',$syid)
        ->distinct()
        // ->where('college_enrolledstud.semid',$semid)
        ->get();

        if(!$request->has('action'))
        {            
            return view('registrar.otherprintables.certofeligibilitytotransfer.index')
                ->with('students',$students);
        }else{
            if($request->get('action') == 'getstudents')
            {
                return collect($students)->sortBy('sortname')->values()->all();
            }elseif($request->get('action') == 'gettemplate')
            {
                $studtransferelig = DB::table('studtransferelig')
                    ->where('studid', $request->get('studentid'))
                    ->where('syid', $request->get('syid'))
                    ->where('deleted','0')
                    ->first();

                $signatory = DB::table('signatory')
                    ->where('form','studtransferelig')
                    ->where('createdby', auth()->user()->id)
                    ->where('deleted','0')
                    ->get();
                $studinfo = collect($students)->where('id', $request->get('studentid'))->first();
                
                return view('registrar.otherprintables.certofeligibilitytotransfer.results')
                    ->with('studtransferelig',$studtransferelig)
                    ->with('signatory',$signatory)
                    ->with('studinfo',$studinfo);
            }elseif($request->get('action') == 'export')
            {
                $studinfo = collect($students)->where('id', $request->get('studentid'))->first();
                
                $checkifexists = DB::table('studtransferelig')
                    ->where('studid', $request->get('studentid'))
                    ->where('syid', $request->get('syid'))
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    if($checkifexists->transferno != $request->get('transferno') || $checkifexists->transferdate != $request->get('transferdate'))
                    {
                        DB::table('studtransferelig')
                            ->where('id', $checkifexists->id)
                            ->update([
                                'transferno'            => $request->get('transferno'),
                                'transferdate'          => $request->get('transferdate'),
                                'updatedby'             => auth()->user()->id,
                                'updateddatetime'       => date('Y-m-d H:i:s')
                            ]);
                    }
                }else{
                    DB::table('studtransferelig')                    
                        ->insert([
                            'studid'                => $request->get('studentid'),
                            'syid'                  => $request->get('syid'),
                            'transferno'            => $request->get('transferno'),
                            'transferdate'          => $request->get('transferdate'),
                            'createdby'             => auth()->user()->id,
                            'createddatetime'       => date('Y-m-d H:i:s')
                        ]);
                }
                $checkformifexists = DB::table('signatory')
                    ->where('form','studtransferelig')
                    ->where('createdby', auth()->user()->id)
                    ->where('deleted','0')
                    ->first();

                if($checkformifexists)
                {
                    if($checkformifexists->name != $request->get('registrar'))
                    {
                        DB::table('signatory')
                            ->where('id', $checkformifexists->id)
                            ->update([
                                'name'                  => $request->get('registrar'),
                                'description'           => 'Registrar',
                                'updatedby'             => auth()->user()->id,
                                'updateddatetime'       => date('Y-m-d H:i:s')
                            ]);
                    }
                }else{
                    DB::table('signatory')                    
                        ->insert([
                            'form'                => 'studtransferelig',
                            'name'                  => $request->get('registrar'),
                            'description'           => 'Registrar',
                            'createdby'             => auth()->user()->id,
                            'createddatetime'       => date('Y-m-d H:i:s')
                        ]);
                }

                $studtransferelig = DB::table('studtransferelig')
                    ->where('studid', $request->get('studentid'))
                    ->where('syid', $request->get('syid'))
                    ->where('deleted','0')
                    ->first();

                $signatory = DB::table('signatory')
                    ->where('form','studtransferelig')
                    ->where('createdby', auth()->user()->id)
                    ->where('deleted','0')
                    ->get();

                $pdf = PDF::loadview('registrar/otherprintables/certofeligibilitytotransfer/pdf_certofeligtotransfer',compact('studtransferelig','signatory','studinfo'));
                return $pdf->stream('Certificate of Eligibility to Transfer.pdf');
            }
        }
    }
    public function othercertifications(Request $request)
    {
        if($request->has('syid'))
        {
            $syid = $request->get('syid');
        }else{
            $syid = DB::table('sy')->where('isactive','1')->first()->id;
        }
        if($request->has('semid'))
        {
            $semid = $request->get('semid');
        }else{
            $semid = DB::table('semester')->where('isactive','1')->first()->id;
        }
        $students1   = DB::table('studinfo')
        ->select(
            'studinfo.id',
            'studinfo.sid',
            'studinfo.lrn',
            'studinfo.lastname',
            'studinfo.firstname',
            'studinfo.middlename',
            'studinfo.suffix',
            'studinfo.gender',
            'studinfo.dob',
            'studinfo.mol',
            'enrolledstud.levelid',
            'gradelevel.levelname',
            'sections.id as sectionid',
            'sections.sectionname'
            )
        ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
        ->leftJoin('gradelevel','enrolledstud.levelid','=','gradelevel.id')
        ->leftJoin('sections','enrolledstud.sectionid','=','sections.id')
        ->where('studinfo.deleted','0')
        ->where('enrolledstud.deleted','0')
        ->whereIn('enrolledstud.studstatus',[1,2,4])
        ->where('enrolledstud.syid',$syid)
        ->get();

        $students2   = DB::table('studinfo')
        ->select(
            'studinfo.id',
            'studinfo.sid',
            'studinfo.lrn',
            'studinfo.lastname',
            'studinfo.firstname',
            'studinfo.middlename',
            'studinfo.suffix',
            'studinfo.gender',
            'studinfo.dob',
            'studinfo.mol',
            'sh_enrolledstud.levelid',
            'gradelevel.levelname',
            'sections.id as sectionid',
            'sections.sectionname'
            )
        ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
        ->leftJoin('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
        ->leftJoin('sections','sh_enrolledstud.sectionid','=','sections.id')
        ->where('studinfo.deleted','0')
        ->where('sh_enrolledstud.deleted','0')
        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
        ->where('sh_enrolledstud.syid',$syid)
        ->where('sh_enrolledstud.semid',$semid)
        ->get();

        $students3   = DB::table('studinfo')
        ->select(
            'studinfo.id',
            'studinfo.sid',
            'studinfo.lrn',
            'studinfo.lastname',
            'studinfo.firstname',
            'studinfo.middlename',
            'studinfo.suffix',
            'studinfo.gender',
            'studinfo.dob',
            'studinfo.mol',
            'college_enrolledstud.yearLevel as levelid',
            'gradelevel.levelname',
            'college_sections.id as sectionid',
            'college_sections.sectionDesc as sectionname'
            )
        ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
        ->leftJoin('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
        ->leftJoin('college_year','gradelevel.id','=','college_year.levelid')
        ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
        ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
        ->where('studinfo.deleted','0')
        ->where('college_enrolledstud.deleted','0')
        ->whereIn('college_enrolledstud.studstatus',[1,2,4])
        ->where('college_enrolledstud.syid',$syid)
        ->where('college_enrolledstud.semid',$semid)
        ->get();
        
        $students = collect();
        $students = $students->merge($students1);
        $students = $students->merge($students2);
        $students = $students->merge($students3);
        $students = $students->unique('id')->all();

        if(!$request->has('action'))
        {
            return view('registrar.otherprintables.othercerts.index')
                ->with('students',$students);
                // ->with('signatory',$signatory)
                // ->with('studinfo',$studinfo);
        }else{
            return view('registrar.otherprintables.certofeligibilitytotransfer.results')
                ->with('studtransferelig',$studtransferelig)
                ->with('signatory',$signatory)
                ->with('studinfo',$studinfo);
        }
    }
    public function promotional(Request $request)
    {
        if(!$request->has('action'))
        {
            return view('registrar.summaries.promotionalreport.index');
        }else{
            $schoolyears = Db::table('sy')
                ->select('id as syid','sydesc'
                ,'isactive'
                )
                ->where('id',$request->get('syid'))
                ->get();
            

                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                {
                    $students = array(
                        [
                            'display'   => 0
                        ],
                        [
                            'display'   => 0
                        ],
                        [
                            'display'   => 0
                        ],
                        [
                            'display'   => 0
                        ]
                        );
                }else{
                    $students = array();
                }

                $getstudents   = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'studinfo.lrn',
                    'studinfo.sid',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'studinfo.dob',
                    'college_enrolledstud.yearLevel as levelid',
                    // 'college_courses.courseDesc as strandname',
                    'college_courses.id as courseid',
                    'college_courses.collegeid',
                    'college_courses.courseabrv',
                    'college_courses.courseDesc as coursename',
                    'nationality.nationality',
                    'gradelevel.sortid',
                    // 'college_sections.sectionDesc as sectionname',
                    'college_year.id as yearid'
                    )
                ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                ->leftJoin('college_year','gradelevel.id','=','college_year.levelid')
                ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
                ->leftJoin('nationality','studinfo.nationality','=','nationality.id')
                ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
                ->where('studinfo.deleted','0')
                ->where('college_enrolledstud.deleted','0')
                ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                ->where('college_enrolledstud.syid',$request->get('syid'))
                ->where('college_enrolledstud.semid',$request->get('semid'))
                ->get();


                $syid = $request->get('syid');
                $semid = $request->get('semid');
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sbc')
                {
                    if($request->get('levelid')>0)
                    {
                        $getstudents = collect($getstudents)->where('levelid', $request->get('levelid'))->values();
                    }
                    if($request->get('collegeid')>0)
                    {
                        $getstudents = collect($getstudents)->where('collegeid', $request->get('collegeid'))->values();
                    }
                    if($request->get('courseid')>0)
                    {
                        $getstudents = collect($getstudents)->where('courseid', $request->get('courseid'))->values();
                    }
                }
                // return count($getstudents);
                if(count($getstudents)>0)
                {
                    foreach($getstudents as $student)
                    {
                        
                        $student->sortname = $student->lastname.' '.$student->firstname;
                        $student->display = 1;
                    }
                }
                $getstudents = collect($getstudents)->sortBy('sortname')->values()->all();
                if(count($getstudents)>0)
                {
                    foreach($getstudents as $keyastudent=>$student)
                    {
                        $student->idno = $keyastudent+1;
                    }
                }
                $students = collect($students)->merge($getstudents);
                $totalnoofstudents = count($getstudents);
                // return $totalnoofstudents;
                $totalexports = $totalnoofstudents%315 > 0 ? floor($totalnoofstudents/315)+1 : floor($totalnoofstudents/315);
                
                $studentspertab = array_chunk(collect($students)->toArray(), 315);
                $countlastpages = (floor((collect(collect($studentspertab)->last())->count())/21));
                if($request->has('tabno'))
                {
                    $students = $studentspertab[($request->get('tabno')-1)];

                }
                
                if(count($students)>0)
                {
                    foreach($students as $keyastudent=>$student)
                    {
                        try{
                            $student->yearlevel = 0;
                            if($student->levelid == 17)
                            {
                                $student->yearlevel = 1;
                            }
                            if($student->levelid == 18)
                            {
                                $student->yearlevel = 2;
                            }
                            if($student->levelid == 19)
                            {
                                $student->yearlevel = 3;
                            }
                            if($student->levelid == 20)
                            {
                                $student->yearlevel = 4;
                            }
                            if($student->levelid == 21)
                            {
                                $student->yearlevel = 5;
                            }
                            $student->major = " "; 
                            $student->courseDesc = $student->coursename; 
                            $majorin = explode("major in ",strtolower($student->coursename));
                            if(count($majorin)>1)
                            {
                                $student->coursename = strtoupper($majorin[0]);
                                $student->major = strtoupper($majorin[1]);
                            }
                            
                            // if($student->id == 3611)
                            // {
                                $grades = collect(\App\Models\College\TOR::getrecords($student->id, DB::table('sy')->select('sy.*','id as syid')->where('id', $syid)->get()))->where('semid',$semid)->values();
                                // return collect($grades);
                                $grades = collect($grades[0]->subjdata) ?? array();
                                
                                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sbc')
                                {
                                    $student->display = 0;
                                }
                                $student->subjects = $grades;
                                $student->countsubj = count($grades);
                            // }
                            
                        }catch(\Exception $error){}
                    }
                }
                if(count(collect($students)->pluck('countsubj')->toArray()) == 0)
                {
                    $maxsubjects = 0;
                }else{
                    $maxsubjects = max(collect($students)->pluck('countsubj')->toArray());
                }
                
                
            if($request->get('action') == 'filter')
            {
                $checkifexist = DB::table('signatory')
                    ->where('form','promotional_report_reg')
                    ->where('deleted','0')
                    ->get();
                    
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                {
                    return view('registrar.summaries.promotionalreport.hccsi.results')
                        ->with('maxsubjects',$maxsubjects)
                        ->with('signatories',$checkifexist)
                        ->with('students',$students);
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
                {
                    return view('registrar.summaries.promotionalreport.pcc.results')
                        ->with('maxsubjects',$maxsubjects)
                        ->with('signatories',$checkifexist)
                        ->with('students',$students);
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                {
                    return view('registrar.summaries.promotionalreport.sbc.results')
                        ->with('totalnoofstudents', $totalnoofstudents)
                        ->with('totalexports', $totalexports)
                        ->with('countlastpages', $countlastpages)
                        ->with('maxsubjects',$maxsubjects)
                        ->with('signatories',$checkifexist)
                        ->with('students',$students);
                }else{
                    return view('registrar.summaries.promotionalreport.results')
                        ->with('maxsubjects',$maxsubjects)
                        ->with('signatories',$checkifexist)
                        ->with('students',$students);
                }
            }else{
                $schoolregistrar = $request->get('registrar');
                $president = $request->get('president');

                $checkifexistregistrar = DB::table('signatory')
                    ->where('form','promotional_report_reg')
                    ->where('title','Registrar')
                    ->where('deleted','0')
                    ->first();
    
                if($checkifexistregistrar)
                {
                    if($schoolregistrar == null || $schoolregistrar == "")
                    {
                        $schoolregistrar = $checkifexistregistrar->name;
                    }else{
                        if($schoolregistrar != $checkifexistregistrar->name)
                        {
                            DB::table('signatory')
                                ->where('id', $checkifexistregistrar->id)
                                ->update([
                                    'name'      => $schoolregistrar,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }else{
                    if($schoolregistrar != null || $schoolregistrar != "")
                    {
                        DB::table('signatory')
                            ->insert([
                                'form'      => 'promotional_report_reg',
                                'name'      => $schoolregistrar,
                                'title'      => 'Registrar',
                                'createdby' => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                $checkifexistpresident = DB::table('signatory')
                    ->where('form','promotional_report_reg')
                    ->where('title','President')
                    ->where('deleted','0')
                    ->first();
    
                if($checkifexistpresident)
                {
                    if($president == null || $president == "")
                    {
                        $president = $checkifexistpresident->name;
                    }else{
                        if($president != $checkifexistpresident->name)
                        {
                            DB::table('signatory')
                                ->where('id', $checkifexistpresident->id)
                                ->update([
                                    'name'      => $president,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }else{
                    if($president != null || $president != "")
                    {
                        DB::table('signatory')
                            ->insert([
                                'form'      => 'promotional_report_reg',
                                'name'      => $president,
                                'title'      => 'President',
                                'createdby' => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                $syinfo = DB::table('sy')->where('id', $request->get('syid'))->first();
                $seminfo = DB::table('semester')->where('id', $request->get('semid'))->first();
                $registrar = $schoolregistrar;
                
                $sydesc = $syinfo->sydesc;
                $semester = $seminfo->semester;
                $inputFileType = 'Xlsx';

                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                {

                    
                    $inputFileName = base_path().'/public/excelformats/sic/promotionalreport.xlsx';
                    // $sheetname = 'Front';

                    /**  Create a new Reader of the type defined in $inputFileType  **/
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Advise the Reader of which WorkSheets we want to load  **/
                    $reader->setLoadAllSheets();
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($inputFileName);
                    $sheet = $spreadsheet->getSheet(0);
                    
                    $borderstyle = [
                        // 'alignment' => [
                        //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        // ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                            ]
                        ]
                    ];

                    
                    $sheet->setCellValue('A1','PROMOTIONAL REPORT');
                    $sheet->setCellValue('A2','SY '.$sydesc);
                    $sheet->setCellValue('A3',$semester);
                    $sheet->setCellValue('B5',DB::table('schoolinfo')->first()->schoolid);
                    $sheet->setCellValue('B6',DB::table('schoolinfo')->first()->schoolname);
                    $sheet->setCellValue('B7',DB::table('schoolinfo')->first()->address);

                    $sheet->setCellValue('L5','TOTAL NO. OF STUDENTS: ___'.count($students).'___');
                    // return $students;
                    if(count($students)>0)
                    {
                        foreach($students as $student)
                        {
                            $student->sortname = $student->lastname.' '.$student->firstname;
                            $student->sortcourseandlevel = $student->coursename.' '.$student->sortid.' '.$student->sortname;
            
                            $subjects = collect($student->subjects)->unique();
                            if(count($subjects)>0)
                            {
                                foreach($subjects as $grade)
                                {
                                    $grade->subjcredit = 0;
                                    $grade->units = ($grade->lecunits+$grade->labunits);
                                }
                            }
                            $student->subjects = $subjects;
                        }
                        $students = collect($students)->values()->all();     
                        $coursestartcellno = 17;
                        $courses = collect($students)->groupBy('coursename');
                        if(count($courses)>0)
                        {
                            foreach($courses as $key=>$course)
                            {
                                $sheet->setCellValue('B'.$coursestartcellno,$key);
                                $sheet->setCellValue('F'.$coursestartcellno,count($course));
                                $sheet->getStyle('F'.$coursestartcellno)
                                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                $coursestartcellno +=1;
                            }
                        }
                        
                        $startcellno = 13;
                        $students = collect($students)->values()->all();     
                        
                        $courses = collect(collect($students)->pluck('coursename'))->unique();
                        if(count($courses)>0)
                        {
                            foreach($courses as $coursename)
                            {
                                $sheet->getStyle('A'.$startcellno)->getAlignment()->setVertical('center');
                                $sheet->setCellValue('A'.$startcellno,$coursename);
                                $sheet->getStyle('A'.$startcellno)->getAlignment()->setWrapText(true);
                                $sheet->getRowDimension($startcellno)->setRowHeight(25);

                                $studentsbycourse = collect($students)->where('coursename', $coursename)->sortBy('sortcourseandlevel')->values()->all();
                                // return $studentsbycourse;
                                if(count($studentsbycourse)>0)
                                {
                                    foreach($studentsbycourse as $student)
                                    {
                                        $sheet->getStyle('B'.$startcellno)->getAlignment()->setVertical('center');
                                        $sheet->setCellValue('B'.$startcellno,$student->major);
                                        $sheet->getStyle('B'.$startcellno)->getAlignment()->setWrapText(true);
                                        $sheet->setCellValue('C'.$startcellno,$student->sid);
                                        $sheet->setCellValue('D'.$startcellno,$student->yearlevel);
                                        if($student->lastname != null)
                                        {
                                            $sheet->setCellValue('E'.$startcellno,$student->lastname);
                                        }
                                        if($student->firstname != null)
                                        {
                                            $sheet->setCellValue('F'.$startcellno,$student->firstname);
                                        }
                                        if($student->middlename != null)
                                        {
                                            $sheet->setCellValue('G'.$startcellno,$student->middlename);
                                        }
                                        if($student->suffix != null)
                                        {
                                            $sheet->setCellValue('H'.$startcellno,$student->suffix);
                                        }
                                        if($student->gender != null)
                                        {
                                            $sheet->setCellValue('I'.$startcellno,ucwords(strtolower($student->gender)));
                                        }
                                        if($student->dob != null)
                                        {
                                            $sheet->setCellValue('J'.$startcellno,$student->nationality);
                                        }
                                        if(count($student->subjects)>0)
                                        {
                                            foreach($student->subjects as $eachsubject)
                                            {
                                                if(strlen($eachsubject->subjectname)>58)
                                                {
                                                    $sheet->getRowDimension($startcellno)->setRowHeight(45);
                                                    $sheet->getStyle('L'.$startcellno)->getAlignment()->setWrapText(true);
                                                }
                                                $sheet->setCellValue('K'.$startcellno,$eachsubject->subjectcode);
                                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setVertical('center');
                                                $sheet->setCellValue('L'.$startcellno,$eachsubject->subjectname);
                                                $sheet->setCellValue('M'.$startcellno,$eachsubject->units);
                                                $sheet->setCellValue('N'.$startcellno,$eachsubject->subjgrade);
                                                $sheet->getStyle('N'.$startcellno)->getNumberFormat()->setFormatCode('#,##0.0');
                                                if($eachsubject->subjgrade != null)
                                                {
                                                    $sheet->setCellValue('O'.$startcellno,$eachsubject->subjgrade < 5.0 ? 'PASSED' : 'FAILED');
                                                }
                                                $startcellno+=1;
                                                $sheet->insertNewRowBefore($startcellno+1);
                                            }
                                        }
                                        $startcellno+=1;
                                        $sheet->insertNewRowBefore($startcellno+1);
                                    }
                                }
                            }
                        }
                    }
                    
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="Promotional Report S.Y '.$sydesc.' '.$semester.'.xlsx"');
                    $writer->save("php://output");
                    exit;
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
                {                                
                    $inputFileName = base_path().'/public/excelformats/pcc/promotionalreport.xlsx';
                    // $sheetname = 'Front';

                    /**  Create a new Reader of the type defined in $inputFileType  **/
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Advise the Reader of which WorkSheets we want to load  **/
                    $reader->setLoadAllSheets();
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($inputFileName);
                    $sheet = $spreadsheet->getSheet(0);
                    
                    $borderstyle = [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                            ]
                        ]
                    ];

                    
                    $sheet->setCellValue('A1','PROMOTIONAL REPORT');
                    $sheet->setCellValue('A2','SY '.$sydesc);
                    $sheet->setCellValue('A3',$semester);
                    $sheet->setCellValue('B5','10067');
                    $sheet->setCellValue('B6',DB::table('schoolinfo')->first()->schoolname);
                    $sheet->setCellValue('B7',DB::table('schoolinfo')->first()->address);

                    $sheet->setCellValue('M5',count($students));
                    
                    if(count($students)>0)
                    {
                        foreach($students as $student)
                        {
                            $student->sortname = $student->lastname.' '.$student->firstname;
                            $student->sortcourseandlevel = $student->coursename.' '.$student->sortid.' '.$student->sortname;
                            // $subjects = DB::table('college_studsched')
                            //     ->join('college_classsched',function($join)use($syid,$semid){
                            //         $join->on('college_studsched.schedid','=','college_classsched.id');
                            //         $join->where('college_classsched.deleted',0);
                            //         $join->where('syID',$syid);
                            //         $join->where('semesterID',$semid);
                            //     })
                            //     ->join('college_prospectus',function($join)use($syid,$semid){
                            //         $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            //         $join->where('college_prospectus.deleted',0);
                            //     })
                            //     ->leftJoin('college_studentprospectus',function($join)use($syid,$semid, $student){
                            //         $join->on('college_prospectus.id','=','college_studentprospectus.prospectusID');
                            //         $join->where('college_studentprospectus.deleted',0);
                            //         $join->where('college_studentprospectus.syid',$syid);
                            //         $join->where('college_studentprospectus.semid',$semid);
                            //         $join->where('college_studentprospectus.studid',$student->id);
                            //     })
                            //     ->where('schedstatus','!=','DROPPED')
                            //     ->where('college_studsched.deleted',0)
                            //     ->where('college_studsched.studid',$student->id)
                            //     ->select('subjCode as subjectcode','subjDesc as subjectname', 'labunits','lecunits','finalgrade as subjgrade','college_prospectus.psubjsort as subjsort')
                            //     ->orderBy('subjsort')
                            //     ->get();
            
                            // $subjects = collect($subjects)->unique();
                            // if(count($subjects)>0)
                            // {
                            //     foreach($subjects as $grade)
                            //     {
                            //         $grade->subjcredit = 0;
                            //         $grade->units = ($grade->lecunits+$grade->labunits);
                            //     }
                            // }
                            // $student->subjects = $subjects;

                            
                            
                        }
                        $students = collect($students)->values()->all();     
                        $coursestartcellno = 18;
                        $courses = collect($students)->groupBy('coursename');
                        if(count($courses)>0)
                        {
                            foreach($courses as $key=>$course)
                            {
                                $sheet->setCellValue('B'.$coursestartcellno,$key);
                                $sheet->setCellValue('F'.$coursestartcellno,count($course));
                                $sheet->getStyle('F'.$coursestartcellno)
                                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                $coursestartcellno +=1;
                            }
                        }

                        // return $courses;
                        $startcellno = 13;
                        
                        if(count($courses)>0)
                        {
                            foreach($courses as $coursename => $course)
                            {
                                $sheet->getStyle('A'.$startcellno)->getAlignment()->setVertical('center');
                                $sheet->setCellValue('A'.$startcellno,$coursename);
                                $sheet->getStyle('A'.$startcellno)->getAlignment()->setWrapText(true);
                                // $students = collect($course)->sortBy('sortname')->all();
                                $course = collect($course)->sortBy('sortcourseandlevel');
                                if(count($course)>0)
                                {
                                    foreach($course as $student)
                                    {
                                        $sheet->getStyle('B'.$startcellno)->getAlignment()->setVertical('center');
                                        $sheet->setCellValue('B'.$startcellno,$student->major);
                                        $sheet->getStyle('B'.$startcellno)->getAlignment()->setWrapText(true);
                                        $student->yearlevel = 0;
                                        if($student->levelid == 17)
                                        {
                                            $student->yearlevel = 1;
                                        }
                                        if($student->levelid == 18)
                                        {
                                            $student->yearlevel = 2;
                                        }
                                        if($student->levelid == 19)
                                        {
                                            $student->yearlevel = 3;
                                        }
                                        if($student->levelid == 20)
                                        {
                                            $student->yearlevel = 4;
                                        }
                                        if($student->levelid == 21)
                                        {
                                            $student->yearlevel = 5;
                                        }
                                        $sheet->setCellValue('C'.$startcellno,$student->sid);
                                        $sheet->setCellValue('D'.$startcellno,$student->yearlevel);
                                        if($student->lastname != null)
                                        {
                                            $sheet->setCellValue('E'.$startcellno,$student->lastname);
                                        }
                                        if($student->firstname != null)
                                        {
                                            $sheet->setCellValue('F'.$startcellno,$student->firstname);
                                        }
                                        if($student->middlename != null)
                                        {
                                            $sheet->setCellValue('G'.$startcellno,$student->middlename);
                                        }
                                        if($student->suffix != null)
                                        {
                                            $sheet->setCellValue('H'.$startcellno,$student->suffix);
                                        }
                                        if($student->gender != null)
                                        {
                                            $sheet->setCellValue('I'.$startcellno,ucwords(strtolower($student->gender)));
                                        }
                                        if($student->dob != null)
                                        {
                                            $sheet->setCellValue('J'.$startcellno,$student->nationality);
                                        }
                                        if(count($student->subjects)>0)
                                        {
                                            foreach($student->subjects as $eachsubject)
                                            {
                                                $sheet->setCellValue('K'.$startcellno,$eachsubject->subjectcode ?? $eachsubject->subjcode);
                                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setVertical('center');
                                                $sheet->setCellValue('L'.$startcellno,$eachsubject->subjectname ?? $eachsubject->subjdesc );
                                                $sheet->getStyle('L'.$startcellno)->getAlignment()->setWrapText(true);
                                                $sheet->setCellValue('M'.$startcellno,$eachsubject->units ?? $eachsubject->subjunit);
                                                $sheet->getStyle('N'.$startcellno)->getNumberFormat()->setFormatCode('#,##0.0');
                                                $sheet->setCellValue('N'.$startcellno,$eachsubject->subjgrade);
                                                if($eachsubject->subjgrade != null)
                                                {
                                                    $sheet->setCellValue('O'.$startcellno,$eachsubject->subjgrade < 5.0 ? 'PASSED' : 'FAILED');
                                                }
                                                $startcellno+=1;
                                                $sheet->insertNewRowBefore($startcellno+1);
                                            }
                                        }
                                        $startcellno+=1;
                                        $sheet->insertNewRowBefore($startcellno+1);
                                    }
                                }
                            }
                        }
                        
                    }
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="Promotional Report S.Y '.$sydesc.' '.$semester.'.xlsx"');
                    $writer->save("php://output");
                    exit;
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi'){
                    
                    if($request->get('exporttype') == 'pdf')
                    {
                        $pdf = PDF::loadview('registrar/summaries/promotionalreport/hccsi/pdf_enrollmentreport',compact('maxsubjects','students','syinfo','seminfo','registrar','president'));
                        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
                        return $pdf->stream('Promotional Report.pdf');
                    }else{
                        
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
                        $inputFileType  = 'Xlsx';
                        
                        $inputFileName = base_path().'/public/excelformats/hccsi/hccsi_promotionalreport.xlsx';
                        // $sheetname = 'Front';
    
                        /**  Create a new Reader of the type defined in $inputFileType  **/
                        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                        /**  Advise the Reader of which WorkSheets we want to load  **/
                        // $reader->setLoadAllSheets();
                        /**  Load $inputFileName to a Spreadsheet Object  **/
                        $spreadsheet = $reader->load($inputFileName);
                        $sheet = $spreadsheet->getSheet(0);
                        
                        $borderstyle = [
                            // 'alignment' => [
                            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                            // ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                                ]
                            ]
                        ];
                        // $sheet->setCellValue('D3',DB::table('schoolinfo')->first()->schoolname);
                        // $sheet->setCellValue('R3',DB::table('schoolinfo')->first()->schoolid);
                        // $sheet->setCellValue('Z3',DB::table('schoolinfo')->first()->address);
    
                        // $preparedby = DB::table('teacher')
                        //     ->where('userid', auth()->user()->id)
                        //     ->where('isactive','1')
                        //     ->where('deleted','0')
                        //     ->first();

                        // $prepared_name = '';
                        // if($preparedby)
                        // {
                        //     $prepared_name .= $preparedby->firstname.' ';
                        //     $prepared_name .= $preparedby->middlename == null ? '  ' : $preparedby->middlename[0].'. ';
                        //     $prepared_name .= $preparedby->lastname;
                        // }else{
                        //     $prepared_name = auth()->user()->name;
                        // }
                        // $certified_name = '';
                        // $verified_name = '';
                        // $noted_name = '';
                        // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa')
                        // {
                        //     $certified_name = 'ESTELLE RICA P. HIACO';
                        //     $verified_name = 'ESTELLE RICA P. HIACO';
                        //     $noted_name = 'LEONITO A. FABIAN';
                        // }

                        // $sheet->setCellValue('D6',$prepared_name);
                        // $sheet->setCellValue('J6',$certified_name);
                        // $sheet->setCellValue('O6',$verified_name);
                        // $sheet->setCellValue('U6',$noted_name);
                        $startcellno = 11;
                        // return $students;
                        // $sheet->getStyle('A15:K15')->applyFromArray($borderstyle);
                        // $sheet->getStyle('M15:W15')->applyFromArray($borderstyle);
                        if(count($students)>0)
                        {
                            
                 
                            foreach($students as $key=>$student)
                            {
                                $student->yearlevel = $student->yearid;
                                
                                $sheet->setCellValue('A'.$startcellno,($key+1));
                                if($student->lastname != null)
                                {
                                    $sheet->setCellValue('B'.$startcellno,$student->lastname);
                                }
                                if($student->firstname != null)
                                {
                                    $sheet->setCellValue('C'.$startcellno,$student->firstname);
                                }
                                if($student->middlename != null)
                                {
                                    $sheet->setCellValue('D'.$startcellno,$student->middlename);
                                }
                                if($student->suffix != null)
                                {
                                    $sheet->setCellValue('E'.$startcellno,$student->suffix);
                                }
                                if($student->gender != null)
                                {
                                    $sheet->setCellValue('F'.$startcellno,ucwords(strtolower($student->gender)));
                                }
                                if($student->dob != null)
                                {
                                    $sheet->setCellValue('G'.$startcellno,date('m/d/Y', strtotime($student->dob)));
                                }
                                if($student->coursename != null)
                                {
                                    $sheet->setCellValue('H'.$startcellno,$student->coursename);
                                }
                                if($student->yearlevel != null)
                                {
                                    $sheet->setCellValue('I'.$startcellno,$student->yearlevel);
                                }
                                
                                $subjectindex = 0;
                                $subjectcount = 0;
                                for($x = 1; $x <= 50; $x++)
                                {
                                    $sheet->getStyle(getNameFromNumber($x).$startcellno)->applyFromArray($borderstyle);
    
                                }
                                // return $student->subjects;
                                for($x = 10; $x <= 50; $x++)
                                {
                                    if(isset($student->subjects[$subjectindex]))
                                    {
                                        // return $student->subjects[$subjectindex]->subjgrade;
                                        $sheet->setCellValue(getNameFromNumber($x).$startcellno,$student->subjects[$subjectindex]->subjcode ?? $student->subjects[$subjectindex]->subjectcode);
                                        $sheet->setCellValue(getNameFromNumber($x+1).$startcellno,$student->subjects[$subjectindex]->subjgrade ?? $student->subjects[$subjectindex]->subjgrade);
                                        $sheet->setCellValue(getNameFromNumber($x+2).$startcellno,$student->subjects[$subjectindex]->subjunit);
    
                                        $subjectindex+=1;
                                        $x+=2;
                                    }
                                }
                                $sheet->setCellValue('BR'.$startcellno,collect($student->subjects)->sum('subjunit'));
                                if(collect($student->subjects)->where('subjgrade','>',50)->count() == collect($student->subjects)->count())
                                {
                                    $sheet->setCellValue('BS'.$startcellno,collect($student->subjects)->avg('subjgrade'));
                                }
                                // return 'asdsadsa';
                                $startcellno+=1;
                            }
                        }
                        
    
                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment; filename="Promotional Report S.Y '.$sydesc.' '.$semester.'.xlsx"');
                        $writer->save("php://output");
    
                        exit;
                        // function getNameFromNumber($num) {
                        //     $numeric = ($num - 1) % 26;
                        //     $letter = chr(65 + $numeric);
                        //     $num2 = intval(($num - 1) / 26);
                        //     if ($num2 > 0) {
                        //         return getNameFromNumber($num2) . $letter;
                        //     } else {
                        //         return $letter;
                        //     }
                        // }
                    
                        // $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
                        // $sheet = $spreadsheet->getActiveSheet();
                        // $border = [
                        //     'borders' => [
                        //         'allBorders' => [
                        //             'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                        //         ]
                        //     ]
                        // ];
                        // // $border    = [
                        // //                     'borders' => [
                        // //                         'top' => [
                        // //                             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        // //                         ],
                        // //                         'bottom' => [
                        // //                             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        // //                         ],
                        // //                         'left' => [
                        // //                             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        // //                         ],
                        // //                         'right' => [
                        // //                             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        // //                         ],
                        // //                     ]
                        // //                 ];
                        // $font_bold = [
                        //         'font' => [
                        //             'bold' => true,
                        //         ]
                        //     ];

                        // // return $seminfo;
                        // $schoolinfo = DB::table('schoolinfo')
                        //     ->first();
                        // $startcellno = 2;
                        // $sheet->mergeCells('A'.$startcellno.':AD'.$startcellno);
                        // $sheet->setCellValue('A'.$startcellno, $schoolinfo->schoolname);
                        // $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                        // $sheet->getStyle('A'.$startcellno)->getFont()->setSize(16);
                        // $startcellno += 1;
                        // $sheet->mergeCells('A'.$startcellno.':AD'.$startcellno);
                        // $sheet->setCellValue('A'.$startcellno, 'KM 9, SASA, DAVAO CITY');
                        // $sheet->getStyle('A'.$startcellno)->getFont()->setSize(11);
                        // $startcellno += 1;
                        // $sheet->getRowDimension($startcellno)->setRowHeight(10);
                        // $startcellno += 1;
                        // $sheet->mergeCells('A'.$startcellno.':AD'.$startcellno);
                        // $sheet->setCellValue('A'.$startcellno, 'Promotional Report');
                        // $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                        // $sheet->getStyle('A'.$startcellno)->getFont()->setSize(16);
                        // $startcellno += 1;
                        // $sheet->mergeCells('A'.$startcellno.':AD'.$startcellno);
                        // $sheet->setCellValue('A'.$startcellno, 'AY '.$syinfo->sydesc.' '.$seminfo->semester);
                        // $sheet->getStyle('A'.$startcellno)->getFont()->setSize(11);

                        // $sheet->getStyle('A2:AD'.$startcellno)->getAlignment()->setHorizontal('center');

                        // $startcellno += 2;
                        
                        // $sheet->getColumnDimension('A')->setWidth(6);
                        // $sheet->getColumnDimension('B')->setWidth(35);
                        // $sheet->getColumnDimension('C')->setWidth(6);
                        // $sheet->getColumnDimension('D')->setWidth(12);
                        // $sheet->getColumnDimension('E')->setWidth(12);
                        // $sheet->mergeCells('A'.$startcellno.':A'.($startcellno+1));
                        // $sheet->mergeCells('B'.$startcellno.':B'.($startcellno+1));
                        // $sheet->mergeCells('C'.$startcellno.':C'.($startcellno+1));
                        // $sheet->mergeCells('D'.$startcellno.':D'.($startcellno+1));
                        // $sheet->mergeCells('E'.$startcellno.':E'.($startcellno+1));
                        // $sheet->getStyle('A'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('B'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('C'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('D'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('E'.$startcellno)->applyFromArray($border);
                        // $sheet->setCellValue('A'.$startcellno, 'No.');
                        // // $sheet->getStyle('A'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('A'.$startcellno)->getAlignment()->setVertical('center');
                        // $sheet->getStyle('A'.$startcellno)->getAlignment()->setWrapText(true);
                        // $sheet->setCellValue('B'.$startcellno, 'Name (Surname, First Name, Middle Name) in Alphabetical Order');
                        // // $sheet->getStyle('B'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('B'.$startcellno)->getAlignment()->setVertical('center');
                        // $sheet->getStyle('B'.$startcellno)->getAlignment()->setWrapText(true);
                        // $sheet->setCellValue('C'.$startcellno, 'Sex');
                        // // $sheet->getStyle('C'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('C'.$startcellno)->getAlignment()->setVertical('center');
                        // $sheet->getStyle('C'.$startcellno)->getAlignment()->setWrapText(true);
                        // $sheet->setCellValue('D'.$startcellno, 'Course');
                        // // $sheet->getStyle('D'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('D'.$startcellno)->getAlignment()->setVertical('center');
                        // $sheet->getStyle('D'.$startcellno)->getAlignment()->setWrapText(true);
                        // $sheet->setCellValue('E'.$startcellno, 'Birthdate');
                        // // $sheet->getStyle('E'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('E'.$startcellno)->getAlignment()->setVertical('center');
                        // $sheet->getStyle('E'.$startcellno)->getAlignment()->setWrapText(true);

                        // for($x = 6; $x < 31; $x++)
                        // {
                        //     $sheet->getColumnDimension(getNameFromNumber($x))->setWidth(7);
                        // }
                        // $sheet->mergeCells('F'.$startcellno.':G'.($startcellno));
                        // $sheet->mergeCells('H'.$startcellno.':I'.($startcellno));
                        // $sheet->mergeCells('J'.$startcellno.':K'.($startcellno));
                        // $sheet->mergeCells('L'.$startcellno.':M'.($startcellno));
                        // $sheet->mergeCells('N'.$startcellno.':O'.($startcellno));
                        // $sheet->mergeCells('P'.$startcellno.':Q'.($startcellno));
                        // $sheet->mergeCells('R'.$startcellno.':S'.($startcellno));
                        // $sheet->mergeCells('T'.$startcellno.':U'.($startcellno));
                        // $sheet->mergeCells('V'.$startcellno.':W'.($startcellno));
                        // $sheet->mergeCells('X'.$startcellno.':Y'.($startcellno));
                        // $sheet->mergeCells('Z'.$startcellno.':AA'.($startcellno));
                        // $sheet->mergeCells('AB'.$startcellno.':AC'.($startcellno));
                        // $sheet->mergeCells('AD'.$startcellno.':AD'.($startcellno+1));
                        // $sheet->getStyle('F'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('H'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('J'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('L'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('N'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('P'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('R'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('T'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('V'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('X'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('Z'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('AB'.$startcellno)->applyFromArray($border);
                        // $sheet->getStyle('AD'.$startcellno)->applyFromArray($border);
                        
                        // for($x = 6; $x < 30; $x++)
                        // {
                        //     $sheet->getStyle(getNameFromNumber($x).$startcellno)->getAlignment()->setVertical('center');
                        //     $sheet->getStyle(getNameFromNumber($x).$startcellno)->getAlignment()->setWrapText(true);
                        //     $sheet->setCellValue(getNameFromNumber($x).$startcellno, 'Subject');
                        //     $x+=1;
                        // }
                        // $sheet->setCellValue('AD'.$startcellno, 'Total Units');
                        // $sheet->getStyle('AD'.$startcellno)->getAlignment()->setVertical('center');
                        // $sheet->getStyle('AD'.$startcellno)->getAlignment()->setWrapText(true);

                        // $sheet->getStyle('A'.$startcellno.':AD'.$startcellno)->getAlignment()->setHorizontal('center');

                        // $startcellno+=1;
                        // for($x = 6; $x < 29; $x++)
                        // {
                        //     $sheet->getStyle(getNameFromNumber($x).$startcellno)->getAlignment()->setVertical('center');
                        //     $sheet->getStyle(getNameFromNumber($x).$startcellno)->getAlignment()->setHorizontal('center');
                        //     $sheet->getStyle(getNameFromNumber($x).$startcellno)->getAlignment()->setWrapText(true);
                        //     $sheet->setCellValue(getNameFromNumber($x).$startcellno, 'Grade');
                        //     $sheet->getStyle(getNameFromNumber($x+1).$startcellno)->getAlignment()->setVertical('center');
                        //     $sheet->getStyle(getNameFromNumber($x+1).$startcellno)->getAlignment()->setHorizontal('center');
                        //     $sheet->getStyle(getNameFromNumber($x+1).$startcellno)->getAlignment()->setWrapText(true);
                        //     $sheet->setCellValue(getNameFromNumber($x+1).$startcellno, 'Units');
                        //     $x+=1;
                        // }
                        // $startcellno+=1;
                        
                        // if(count($students)>0)
                        // {
                        //     foreach($students as $studentkey=>$student)
                        //     {
                        //         $sheet->mergeCells('A'.$startcellno.':A'.($startcellno+1));
                        //         $sheet->mergeCells('B'.$startcellno.':B'.($startcellno+1));
                        //         $sheet->mergeCells('C'.$startcellno.':C'.($startcellno+1));
                        //         $sheet->mergeCells('D'.$startcellno.':D'.($startcellno+1));
                        //         $sheet->mergeCells('E'.$startcellno.':E'.($startcellno+1));
                        //         $sheet->setCellValue('A'.$startcellno, $studentkey+1);
                        //         $sheet->getStyle('A'.$startcellno)->getAlignment()->setVertical('center');
                        //         $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('center');
                        //         $sheet->getStyle('A'.$startcellno)->getAlignment()->setWrapText(true);
                        //         $sheet->setCellValue('B'.$startcellno, $student->lastname.', '.$student->firstname.' '.$student->middlename);
                        //         $sheet->getStyle('B'.$startcellno)->getAlignment()->setVertical('center');
                        //         $sheet->getStyle('B'.$startcellno)->getAlignment()->setWrapText(true);
                        //         $sheet->setCellValue('C'.$startcellno, $student->gender[0]);
                        //         $sheet->getStyle('C'.$startcellno)->getAlignment()->setVertical('center');
                        //         $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('center');
                        //         $sheet->getStyle('C'.$startcellno)->getAlignment()->setWrapText(true);
                        //         $sheet->setCellValue('D'.$startcellno, $student->courseabrv);
                        //         $sheet->getStyle('D'.$startcellno)->getAlignment()->setVertical('center');
                        //         $sheet->getStyle('D'.$startcellno)->getAlignment()->setHorizontal('center');
                        //         $sheet->getStyle('D'.$startcellno)->getAlignment()->setWrapText(true);
                        //         $sheet->setCellValue('E'.$startcellno, $student->dob != null ? date('m/d/Y', strtotime($student->dob)) : '');
                        //         $sheet->getStyle('E'.$startcellno)->getAlignment()->setVertical('center');
                        //         $sheet->getStyle('E'.$startcellno)->getAlignment()->setHorizontal('center');
                        //         $sheet->getStyle('E'.$startcellno)->getAlignment()->setWrapText(true);

                        //         $sheet->mergeCells('F'.$startcellno.':G'.($startcellno));
                        //         $sheet->mergeCells('H'.$startcellno.':I'.($startcellno));
                        //         $sheet->mergeCells('J'.$startcellno.':K'.($startcellno));
                        //         $sheet->mergeCells('L'.$startcellno.':M'.($startcellno));
                        //         $sheet->mergeCells('N'.$startcellno.':O'.($startcellno));
                        //         $sheet->mergeCells('P'.$startcellno.':Q'.($startcellno));
                        //         $sheet->mergeCells('R'.$startcellno.':S'.($startcellno));
                        //         $sheet->mergeCells('T'.$startcellno.':U'.($startcellno));
                        //         $sheet->mergeCells('V'.$startcellno.':W'.($startcellno));
                        //         $sheet->mergeCells('X'.$startcellno.':Y'.($startcellno));
                        //         $sheet->mergeCells('Z'.$startcellno.':AA'.($startcellno));
                        //         $sheet->mergeCells('AB'.$startcellno.':AC'.($startcellno));
                        //         $sheet->mergeCells('AD'.$startcellno.':AD'.($startcellno+1));

                        //         $startcolumn = 6;
                        //         for($x = 0; $x < $maxsubjects; $x++)
                        //         {
                        //             if(isset($student->subjects[$x]))
                        //             {
                        //                 $sheet->getStyle(getNameFromNumber($startcolumn).$startcellno)->getAlignment()->setVertical('center');
                        //                 $sheet->getStyle(getNameFromNumber($startcolumn).$startcellno)->getAlignment()->setWrapText(true);
                        //                 $sheet->setCellValue(getNameFromNumber($startcolumn).$startcellno, $student->subjects[$x]->subjcode ?? $student->subjects[$x]->subjectcode);
                        //                 $startcolumn+=2;
                        //             }

                        //         }
                        //         $sheet->setCellValue('AD'.$startcellno,collect($student->subjects)->sum('subjunit'));
                        //         $sheet->getStyle('AD'.$startcellno)->getAlignment()->setVertical('center');
                        //         $sheet->getStyle('AD'.$startcellno)->getAlignment()->setHorizontal('center');
                        //         $startcellno+=1;
                        //         $startcolumn = 6;
                        //         for($x = 0; $x < $maxsubjects; $x++)
                        //         {
                        //             if(isset($student->subjects[$x]))
                        //             {
                        //                 $sheet->getStyle(getNameFromNumber($startcolumn).$startcellno)->getAlignment()->setVertical('center');
                        //                 $sheet->getStyle(getNameFromNumber($startcolumn).$startcellno)->getAlignment()->setHorizontal('center');
                        //                 $sheet->getStyle(getNameFromNumber($startcolumn).$startcellno)->getAlignment()->setWrapText(true);
                        //                 $sheet->setCellValue(getNameFromNumber($startcolumn).$startcellno, $student->subjects[$x]->subjgrade);
                        //                 $startcolumn+=1;
                        //                 $sheet->getStyle(getNameFromNumber($startcolumn).$startcellno)->getAlignment()->setVertical('center');
                        //                 $sheet->getStyle(getNameFromNumber($startcolumn).$startcellno)->getAlignment()->setHorizontal('center');
                        //                 $sheet->getStyle(getNameFromNumber($startcolumn).$startcellno)->getAlignment()->setWrapText(true);
                        //                 $sheet->setCellValue(getNameFromNumber($startcolumn).$startcellno, $student->subjects[$x]->subjunit);
                        //                 $startcolumn+=1;
                        //             }

                        //         }
        
                        //         $startcellno+=1;
                        //     }
                        // }
                        // $sheet->getStyle('A8:AD'.$startcellno)->applyFromArray($border);
                        
                        // $startcellno+=2;
                        // $sheet->mergeCells('E'.$startcellno.':S'.($startcellno));
                        // $sheet->setCellValue('E'.$startcellno,'CERTIFICATION: I hereby certify that the names contained herein are true and correct names of students.');
                        
                        // $sheet->getStyle('E'.$startcellno)->getAlignment()->setVertical('center');
                        // $sheet->getStyle('E'.$startcellno)->getAlignment()->setHorizontal('center');
                        // $startcellno+=3;
                        // $sheet->mergeCells('T'.$startcellno.':Y'.($startcellno));
                        // $sheet->setCellValue('T'.$startcellno,$registrar);     
                        // $sheet->getStyle('T'.$startcellno)->getAlignment()->setVertical('center');
                        // $sheet->getStyle('T'.$startcellno)->getAlignment()->setHorizontal('center');                   
                        // $sheet->getStyle('T'.$startcellno.':Y'.($startcellno))
                        // ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        // $startcellno+=1;
                        // $sheet->mergeCells('T'.$startcellno.':Y'.($startcellno));
                        // $sheet->setCellValue('T'.$startcellno,'Registrar');     
                        // $sheet->getStyle('T'.$startcellno)->getAlignment()->setVertical('center');
                        // $sheet->getStyle('T'.$startcellno)->getAlignment()->setHorizontal('center');  

                        // $activesheet = 1;

                        // // $spreadsheet->createSheet();
                        // // // Zero based, so set the second tab as active sheet
                        // // $spreadsheet->setActiveSheetIndex(1);
                        // // $spreadsheet->getActiveSheet()->setTitle('Second tab');
                        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        // header('Content-Type: application/vnd.ms-excel');
                        // header('Content-Disposition: attachment; filename="PROMOTIONAL REPORT AY '.$syinfo->sydesc.' - '.$seminfo->semester.'.xlsx"');
                        // $writer->save("php://output");

                        // exit;
                        //     // $sheet->getColumnDimension('A')->setAutoSize(true);
                        //     // $sheet->getColumnDimension('B')->setAutoSize(true);
                        //     // $sheet->getColumnDimension('C')->setAutoSize(true);
                        //     // $sheet->getColumnDimension('D')->setAutoSize(true);
                        //     // $sheet->getColumnDimension('E')->setAutoSize(true);
                        //     // $sheet->getColumnDimension('F')->setAutoSize(true);
                        //     // $sheet->getColumnDimension('G')->setAutoSize(true);
    

                    }
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'){
                    
                    $courses = collect($students)->sortBy('coursename');
                    $courses = $courses->map(function ($course) {
                        return (object)collect(collect($course)->toArray())
                            ->only(['courseid','courseabrv', 'coursename','major'])
                            ->all();
                    })->unique()->values();
                    
                    $tabno = $request->get('tabno');
                    $pagedesc = $request->get('pagedesc');
                    $firstpageno = $request->get('firstpageno');
                    $lastpageno = 1;
                    if($totalexports>0)
                    {
                        $firstpage = 1;
                        for($x = 0; $x<$totalexports; $x++)
                        {
                            if(($x+1) == $totalexports)
                            {
                                $lastpageno+=$countlastpages;
                            }else{
                                $lastpageno+=(15);
                            }
                            $firstpage += 15;
                        }
                    }
                    
                    $pdf = PDF::loadview('registrar/summaries/promotionalreport/pdf_promotionalreport_sbc',compact('maxsubjects','students','syinfo','seminfo','registrar','president','courses','tabno','pagedesc','firstpageno','lastpageno','countlastpages','totalexports'));
                    return $pdf->stream('Promotional Report.pdf');
                }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                {

                    $students = collect($students)->where('collegeid', $request->get('collegeid'))->values();
                    if($request->get('courseid') > 0)
                    {
                        $students = collect($students)->where('courseid', $request->get('courseid'))->values();
                    }
                    // return $request->all();
                    // return $students;
                    
                    $inputFileName = base_path().'/public/excelformats/mci/mci_promotionalreport.xlsx';
                    // $sheetname = 'Front';

                    /**  Create a new Reader of the type defined in $inputFileType  **/
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Advise the Reader of which WorkSheets we want to load  **/
                    $reader->setLoadAllSheets();
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($inputFileName);
                    $sheet = $spreadsheet->getSheet(0);
                    
                    $borderstyle = [
                        // 'alignment' => [
                        //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        // ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                            ]
                        ]
                    ];
                    if(count($students)>0)
                    {
                        foreach($students as $student)
                        {
                            $student->sortname = $student->lastname.' '.$student->firstname;
                            $student->sortcourseandlevel = $student->coursename.' '.$student->sortid.' '.$student->sortname;
                            
                            // return Db::table('sy')>where('id',$request->get('syid'))->get();
                            $subjects = collect(\App\Models\College\TOR::getrecords($student->id, Db::table('sy')->select('sy.*','id as syid')->where('id',$request->get('syid'))->get()))->where('semid',$request->get('semid'))->values();
                            $subjects = collect($subjects[0]->subjdata) ?? array();

                            // $subjects = collect($student->subjects)->unique();
                            // if(count($subjects)>0)
                            // {
                            //     foreach($subjects as $grade)
                            //     {
                            //         $grade->subjcredit = 0;
                            //         $grade->units = ($grade->lecunits+$grade->labunits);
                            //     }
                            // }
                            $student->subjects = $subjects;
                        }
                    }
                    // return $students;
                    $students = collect($students)->sortBy('courseabrv')->values()->all();
                    $bycourse = collect($students)->groupBy('courseabrv');
                    // return count($bycourse);
                    if(count($bycourse)>0)
                    {
                        foreach($bycourse as $eachcoursekey => $eachscourseval)
                        {
                            $clonedWorksheet = clone $spreadsheet->getSheetByName('Sheet1');
                            $clonedWorksheet->setTitle($eachscourseval[0]->courseabrv);
                            $spreadsheet->addSheet($clonedWorksheet);
                            // $spreadsheet->setActiveSheetIndexByName($eachscourseval[0]->courseabrv);
                            $sheet = $spreadsheet->getSheetByName($eachscourseval[0]->courseabrv);
                            
                            $sheet->setCellValue('A5', $semester.', '.$sydesc);
                            $sheet->setCellValue('C7', $eachscourseval[0]->courseDesc);
                            $eachscourseval = collect($eachscourseval)->sortByDesc('yearid');
                            $byyearlevel = collect($eachscourseval)->groupBy('yearid');
                            $studentcount = 0;
                            $startcellno = 12;
                            if(count($byyearlevel)>0)
                            {
                                foreach($byyearlevel as $key=>$byyear)
                                {
                                    $byyear = collect($byyear)->sortBy('sortname')->values();
                                    foreach($byyear as $eachstudent)
                                    {
                                        $studentcount+=1;
        
                                        $sheet->setCellValue('A'.$startcellno, $studentcount);
                                        $sheet->setCellValue('B'.$startcellno, $eachstudent->sid);
                                        $sheet->setCellValue('C'.$startcellno, $eachstudent->lastname.', '.$eachstudent->firstname.' '.$eachstudent->middlename);
                                        $sheet->setCellValue('D'.$startcellno, $eachstudent->gender[0] ?? '');
                                        $sheet->setCellValue('E'.$startcellno, $key);
        
                                        $startcellno+=1;
                                        $notcomplete = 0;
                                        if(count($eachstudent->subjects)> 0)
                                        {
                                            foreach($eachstudent->subjects as $eachsubject)
                                            {
                                                if($eachsubject->subjgrade == null || $eachsubject->subjgrade == 'INC' || $eachsubject->subjgrade == 0)
                                                {
                                                    $notcomplete = 1;
                                                }
                                                $sheet->setCellValue('F'.$startcellno, $eachsubject->subjcode);
                                                $sheet->setCellValue('G'.$startcellno, $eachsubject->subjunit);
                                                $sheet->setCellValue('H'.$startcellno, $eachsubject->subjgrade);
                                                $sheet->setCellValue('I'.$startcellno, ($eachsubject->subjgrade > 3 || $eachsubject->subjgrade == null || $eachsubject->subjgrade == 'INC' || $eachsubject->subjgrade == 0)  ? '' : 'PASSED');
                                                $startcellno+=1;
                                            }
                                        }
                                        $sheet->setCellValue('F'.$startcellno, 'Total No. Units');
                                        $sheet->setCellValue('G'.$startcellno, collect($eachstudent->subjects)->sum('subjunit'));
                                        $sheet->setCellValue('H'.$startcellno, 'Grade Point Average');
                                        if($notcomplete == 0)
                                        {
                                            // $sheet->setCellValue('J'.$startcellno, (float) collect($eachstudent->subjects)->avg('subjgrade') ?? '');
                                            $sheet->setCellValue('J'.$startcellno, number_format(collect($eachstudent->subjects)->avg('subjgrade'),4) ?? '');
                                        }
                                        $startcellno+=2;
        
                                    }
                                }
                            }
                        }
                        $spreadsheet->removeSheetByIndex(0);
                    }

                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="Promotional Report S.Y '.$sydesc.' '.$semester.'.xlsx"');
                    $writer->save("php://output");
                    exit;
                }else{
                    $pdf = PDF::loadview('registrar/summaries/promotionalreport/pdf_enrollmentreport',compact('maxsubjects','students','syinfo','seminfo','registrar','president'));
                    return $pdf->stream('Promotional Report.pdf');
                }
            }
        }
		
	}
	public function graduationlist(Request $request)
    {
        // return $request->all();
        if(!$request->has('action'))
        {
            return view('registrar.forms.graduationlist.index');
        }else{
            $schoolyears = Db::table('sy')
                ->select('id as syid','sydesc'
                ,'isactive'
                )
                ->where('id',$request->get('syid'))
                ->get();
            


                $students   = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'studinfo.lrn',
                    'studinfo.sid',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'studinfo.dob',
                    'college_enrolledstud.yearLevel as levelid',
                    // 'college_courses.courseDesc as strandname',
                    'college_courses.id as courseid',
                    'college_courses.collegeid',
                    'college_courses.courseabrv',
                    'college_courses.courseDesc as coursename',
                    'nationality.nationality',
                    'gradelevel.sortid',
                    // 'college_sections.sectionDesc as sectionname',
                    'college_year.id as yearid'
                    )
                ->join('college_enrolledstud','studinfo.id','=','college_enrolledstud.studid')
                ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                ->leftJoin('college_year','gradelevel.id','=','college_year.levelid')
                ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
                ->leftJoin('nationality','studinfo.nationality','=','nationality.id')
                ->join('college_courses','college_enrolledstud.courseID','=','college_courses.id')
                ->where('studinfo.deleted','0')
                ->where('college_enrolledstud.deleted','0')
                ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                ->where('college_enrolledstud.syid',$request->get('syid'))
                ->where('college_enrolledstud.semid',$request->get('semid'))
                ->get();


                $syid = $request->get('syid');
                $semid = $request->get('semid');
                if($request->get('levelid')>0)
                {
                    $students = collect($students)->where('levelid', $request->get('levelid'))->values();
                }
                if($request->get('collegeid')>0)
                {
                    $students = collect($students)->where('collegeid', $request->get('collegeid'))->values();
                }
                if($request->get('courseid')>0)
                {
                    $students = collect($students)->where('courseid', $request->get('courseid'))->values();
                }
                if(count($students)>0)
                {
                    foreach($students as $keyastudent=>$student)
                    {
                        $student->idno = $keyastudent+1;
                        $student->sortname = $student->lastname.' '.$student->firstname;
                        $student->display = 1;
                        $student->yearlevel = 0;
                        if($student->levelid == 17)
                        {
                            $student->yearlevel = 1;
                        }
                        if($student->levelid == 18)
                        {
                            $student->yearlevel = 2;
                        }
                        if($student->levelid == 19)
                        {
                            $student->yearlevel = 3;
                        }
                        if($student->levelid == 20)
                        {
                            $student->yearlevel = 4;
                        }
                        if($student->levelid == 21)
                        {
                            $student->yearlevel = 5;
                        }
                        $student->major = " "; 
                        $majorin = explode("major in ",strtolower($student->coursename));
                        if(count($majorin)>1)
                        {
                            $student->coursename = strtoupper($majorin[0]);
                            $student->major = strtoupper($majorin[1]);
                        }
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sbc')
                        {
                            $student->display = 0;
                        }
                    }
                }
                $students = collect($students)->sortBy('sortname')->values()->all();
                
            if($request->get('action') == 'filter')
            {
                // return $request->all();

                $checkifexist = DB::table('signatory')
                    ->where('form','promotional_report_reg')
                    ->where('deleted','0')
                    ->get();
                    
                return view('registrar.forms.graduationlist.results')
                    ->with('signatories',$checkifexist)
                    ->with('students',$students);
            }else{
                $schoolregistrar = $request->get('registrar');
                $president = $request->get('president');

                $checkifexistregistrar = DB::table('signatory')
                    ->where('form','promotional_report_reg')
                    ->where('title','Registrar')
                    ->where('deleted','0')
                    ->first();
    
                if($checkifexistregistrar)
                {
                    if($schoolregistrar == null || $schoolregistrar == "")
                    {
                        $schoolregistrar = $checkifexistregistrar->name;
                    }else{
                        if($schoolregistrar != $checkifexistregistrar->name)
                        {
                            DB::table('signatory')
                                ->where('id', $checkifexistregistrar->id)
                                ->update([
                                    'name'      => $schoolregistrar,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }else{
                    if($schoolregistrar != null || $schoolregistrar != "")
                    {
                        DB::table('signatory')
                            ->insert([
                                'form'      => 'promotional_report_reg',
                                'name'      => $schoolregistrar,
                                'title'      => 'Registrar',
                                'createdby' => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                $checkifexistpresident = DB::table('signatory')
                    ->where('form','promotional_report_reg')
                    ->where('title','President')
                    ->where('deleted','0')
                    ->first();
    
                if($checkifexistpresident)
                {
                    if($president == null || $president == "")
                    {
                        $president = $checkifexistpresident->name;
                    }else{
                        if($president != $checkifexistpresident->name)
                        {
                            DB::table('signatory')
                                ->where('id', $checkifexistpresident->id)
                                ->update([
                                    'name'      => $president,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }else{
                    if($president != null || $president != "")
                    {
                        DB::table('signatory')
                            ->insert([
                                'form'      => 'promotional_report_reg',
                                'name'      => $president,
                                'title'      => 'President',
                                'createdby' => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                $syinfo = DB::table('sy')->where('id', $request->get('syid'))->first();
                $seminfo = DB::table('semester')->where('id', $request->get('semid'))->first();
                $registrar = $schoolregistrar;
                
                $sydesc = $syinfo->sydesc;
                $semester = $seminfo->semester;
                $inputFileType = 'Xlsx';
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc')
                {                                
                    $inputFileName = base_path().'/public/excelformats/pcc/graduationlist.xlsx';
                    // $sheetname = 'Front';

                    /**  Create a new Reader of the type defined in $inputFileType  **/
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Advise the Reader of which WorkSheets we want to load  **/
                    $reader->setLoadAllSheets();
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($inputFileName);
                    $sheet = $spreadsheet->getSheet(0);
                    
                    $borderstyle = [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                            ]
                        ]
                    ];

                    
                    $sheet->setCellValue('A1','PROMOTIONAL REPORT');
                    $sheet->setCellValue('A2','SY '.$sydesc);
                    $sheet->setCellValue('A3',$semester.', '.date('F d, Y'));
                    $sheet->setCellValue('B5','10067');
                    $sheet->setCellValue('B6',DB::table('schoolinfo')->first()->schoolname);
                    $sheet->setCellValue('B7',DB::table('schoolinfo')->first()->address);

                    $sheet->setCellValue('J5','TOTAL NO. OF GRADUATES: '.count($students));

                    $sheet->setCellValue('M5',count($students));
                    
                    if(count($students)>0)
                    {
                        foreach($students as $student)
                        {
                            $student->sortname = $student->lastname.' '.$student->firstname;
                            $student->sortcourseandlevel = $student->coursename.' '.$student->sortid.' '.$student->sortname;                            
                            
                        }
                        $students = collect($students)->values()->all();     
                        $coursestartcellno = 18;
                        $courses = collect($students)->groupBy('coursename');
                        if(count($courses)>0)
                        {
                            foreach($courses as $key=>$course)
                            {
                                $sheet->setCellValue('B'.$coursestartcellno,$key);
                                $sheet->setCellValue('F'.$coursestartcellno,count($course));
                                $sheet->getStyle('F'.$coursestartcellno)
                                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                $coursestartcellno +=1;
                            }
                        }

                        $startcellno = 13;
                        
                        if(count($courses)>0)
                        {
                            foreach($courses as $coursename => $course)
                            {
                                $sheet->getStyle('A'.$startcellno)->getAlignment()->setVertical('center');
                                $sheet->setCellValue('A'.$startcellno,$coursename);
                                $sheet->getStyle('A'.$startcellno)->getAlignment()->setWrapText(true);
                                // $students = collect($course)->sortBy('sortname')->all();
                                $course = collect($course)->sortBy('sortcourseandlevel');
                                if(count($course)>0)
                                {
                                    foreach($course as $student)
                                    {
                                        $sheet->getStyle('B'.$startcellno)->getAlignment()->setVertical('center');
                                        $sheet->setCellValue('B'.$startcellno,$student->major);
                                        $sheet->getStyle('B'.$startcellno)->getAlignment()->setWrapText(true);
                                        $student->yearlevel = 0;
                                        if($student->levelid == 17)
                                        {
                                            $student->yearlevel = 1;
                                        }
                                        if($student->levelid == 18)
                                        {
                                            $student->yearlevel = 2;
                                        }
                                        if($student->levelid == 19)
                                        {
                                            $student->yearlevel = 3;
                                        }
                                        if($student->levelid == 20)
                                        {
                                            $student->yearlevel = 4;
                                        }
                                        if($student->levelid == 21)
                                        {
                                            $student->yearlevel = 5;
                                        }
                                        $sheet->setCellValue('C'.$startcellno,$student->sid);
                                        $sheet->getStyle('C'.$startcellno)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                                        // $sheet->setCellValue('D'.$startcellno,$student->yearlevel);
                                        if($student->lastname != null)
                                        {
                                            $sheet->setCellValue('D'.$startcellno,$student->lastname);
                                        }
                                        if($student->firstname != null)
                                        {
                                            $sheet->setCellValue('E'.$startcellno,$student->firstname);
                                        }
                                        if($student->middlename != null)
                                        {
                                            $sheet->setCellValue('F'.$startcellno,$student->middlename);
                                        }
                                        if($student->suffix != null)
                                        {
                                            $sheet->setCellValue('G'.$startcellno,$student->suffix);
                                        }
                                        if($student->gender != null)
                                        {
                                            $sheet->setCellValue('H'.$startcellno,ucwords(strtolower($student->gender)));
                                        }
                                        if($student->dob != null)
                                        {
                                            $sheet->setCellValue('I'.$startcellno,$student->nationality);
                                        }
                                        $startcellno+=1;
                                        $sheet->insertNewRowBefore($startcellno+1);
                                    }
                                }
                            }
                        }
                        
                    }
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="Graduation List S.Y '.$sydesc.' '.$semester.'.xlsx"');
                    $writer->save("php://output");
                    exit;
                }else{
                    
                    // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                    // {
                    //     $pdf = PDF::loadview('registrar/summaries/promotionalreport/hccsi/pdf_enrollmentreport',compact('maxsubjects','students','syinfo','seminfo','registrar','president'));
                    //     $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
                    //     return $pdf->stream('Promotional Report.pdf');
                    // }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'){
                    //     $courses = collect($students)->sortBy('coursename');
                    //     $courses = $courses->map(function ($course) {
                    //         return (object)collect(collect($course)->toArray())
                    //             ->only(['courseid','courseabrv', 'coursename','major'])
                    //             ->all();
                    //     })->unique()->values();
                        
                    //     $tabno = $request->get('tabno');
                    //     $pagedesc = $request->get('pagedesc');
                    //     $firstpageno = $request->get('firstpageno');
                    //     $lastpageno = 1;
                    //     if($totalexports>0)
                    //     {
                    //         $firstpage = 1;
                    //         for($x = 0; $x<$totalexports; $x++)
                    //         {
                    //             if(($x+1) == $totalexports)
                    //             {
                    //                 $lastpageno+=$countlastpages;
                    //             }else{
                    //                 $lastpageno+=(15);
                    //             }
                    //             $firstpage += 15;
                    //         }
                    //     }
                        
                    //     $pdf = PDF::loadview('registrar/summaries/promotionalreport/pdf_promotionalreport_sbc',compact('maxsubjects','students','syinfo','seminfo','registrar','president','courses','tabno','pagedesc','firstpageno','lastpageno','countlastpages','totalexports'));
                    //     return $pdf->stream('Promotional Report.pdf');
                        
                    // }else{
                    //     $pdf = PDF::loadview('registrar/summaries/promotionalreport/pdf_enrollmentreport',compact('maxsubjects','students','syinfo','seminfo','registrar','president'));
                    //     return $pdf->stream('Promotional Report.pdf');
                    // }
                }
            }
        }
    }
    public function report_monthlystat(Request $request)
    {                    
        if(!$request->has('action'))
        {
            return view('registrar.otherprintables.monthly_stats.index');
        }else{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $dateperiod = $request->get('dateperiod');

            $datefrom = date('Y-m-d', strtotime(explode(" - ", $dateperiod)[0]));
            $dateto   = date('Y-m-d', strtotime(explode(" - ", $dateperiod)[1]));
            
            $students_1 = DB::table('enrolledstud')
                ->select('studinfo.id',DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname"),'studinfo.userid','studinfo.lrn','studinfo.sid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','gradelevel.id as levelid','gradelevel.levelname','enrolledstud.sectionid','sections.sectionname','enrolledstud.studstatus','enrolledstud.studstatdate','dateenrolled','studentstatus.description as studentstatus','enrolledstud.updateddatetime')
                ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                ->join('sections','enrolledstud.sectionid','=','sections.id')
                ->join('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                ->where('enrolledstud.studstatus','!=','0')
                ->where('enrolledstud.studstatus','!=','6')
                ->where('enrolledstud.deleted','0')
                ->where('enrolledstud.syid',$syid)
                ->get();
                
            $students_2 = DB::table('sh_enrolledstud')
                ->select('studinfo.id',DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname"),'studinfo.userid','studinfo.lrn','studinfo.sid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','gradelevel.id as levelid','gradelevel.levelname','sh_enrolledstud.sectionid','sections.sectionname','sh_enrolledstud.studstatus','sh_enrolledstud.studstatdate','dateenrolled','studentstatus.description as studentstatus','sh_enrolledstud.updateddatetime')
                ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
                ->join('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                ->where('sh_enrolledstud.studstatus','!=','0')
                ->where('sh_enrolledstud.studstatus','!=','6')
                ->where('sh_enrolledstud.deleted','0')
                ->where('sh_enrolledstud.syid',$syid)
                ->where('sh_enrolledstud.semid',$semid)
                ->get();
                
            $students_3 = DB::table('college_enrolledstud')
                ->select('studinfo.id',DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname"),'studinfo.userid','studinfo.lrn','studinfo.sid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','gradelevel.id as levelid','gradelevel.levelname','college_enrolledstud.sectionid','sections.sectionname','college_enrolledstud.studstatus','college_enrolledstud.studstatdate','date_enrolled as dateenrolled','studentstatus.description as studentstatus','college_enrolledstud.updateddatetime')
                ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                ->leftJoin('sections','college_enrolledstud.sectionid','=','sections.id')
                ->join('studentstatus','college_enrolledstud.studstatus','=','studentstatus.id')
                ->where('college_enrolledstud.studstatus','!=','0')
                ->where('college_enrolledstud.studstatus','!=','6')
                ->where('college_enrolledstud.deleted','0')
                ->where('college_enrolledstud.syid',$syid)
                ->where('college_enrolledstud.semid',$semid)
                ->get();
                
            $allstudents = collect();
            $allstudents = $allstudents->merge($students_1);
            $allstudents = $allstudents->merge($students_2);
            $allstudents = $allstudents->merge($students_3);
            $allstudents = $allstudents->sortBy('studentname');
            
            $students = array();
            if(count($allstudents) > 0)
            {
                foreach($allstudents as $eachstudent)
                {
                    $statdate = date('Y-m-d',strtotime($eachstudent->studstatdate));
                    if($eachstudent->studstatus == 1 || $eachstudent->studstatus == 2 || $eachstudent->studstatus == 4 )
                    {                        
                        if($eachstudent->studstatdate == null)
                        {
                            $statdate =  date('Y-m-d',strtotime($eachstudent->dateenrolled));
                        }
                    }
                    else if($eachstudent->studstatus == 3 || $eachstudent->studstatus == 5)
                    {         
                        if($eachstudent->studstatdate == null)
                        {
                            $statdate =  date('Y-m-d',strtotime($eachstudent->updateddatetime));
                        }     
                    }

                    $eachstudent->lastdate = $statdate;

                    if($eachstudent->studstatus == 1 || $eachstudent->studstatus == 2 || $eachstudent->studstatus == 4 )
                    {         
                        if(($statdate <= $datefrom && $statdate <= $dateto) || ($statdate >= $datefrom && $statdate <= $dateto))
                        {
                            array_push($students, $eachstudent);
                        }
                    }
                    else if($eachstudent->studstatus == 3 || $eachstudent->studstatus == 5)
                    {       
                        if((date('Y-m-d',strtotime($eachstudent->dateenrolled)) <= $datefrom && date('Y-m-d',strtotime($eachstudent->dateenrolled)) <= $dateto)
                        //  && ($statdate >= $datefrom && $statdate <= $dateto)
                         )
                        {
                            if(($statdate >= $dateto) || ($statdate >= $datefrom && $statdate <= $dateto))
                            {
                                array_push($students, $eachstudent);
                            }else{
                               
                            }
                        }
                    }
                }
            }
            
            $gradelevels = DB::table('gradelevel')
            ->where('deleted','0')
            ->orderBy('sortid')
            ->get();
            if($request->get('action') == 'getstudents')
            {

                return view('registrar.otherprintables.monthly_stats.results')
                    ->with('gradelevels',$gradelevels)
                    ->with('students',$students);
            }
            else if($request->get('action') == 'export')
            {
                // return $request->all();
                $pdf = PDF::loadview('registrar/otherprintables/monthly_stats/pdf_monthlystats',compact('gradelevels','students'));
        
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->stream('Monthly Enrollment Staticstics.pdf');
            }
        }
    }
}
class PrintableCOR extends TCPDF {

    // //Page header
    // public function Header() {
    //     // Logo
    //     // $this->Image('@'.file_get_contents('/home/xxxxxx/public_html/xxxxxxxx/uploads/logo/logo.png'),10,6,0,13);
    //     $schoollogo = DB::table('schoolinfo')->first();
    //     $image_file = public_path().'/'.$schoollogo->picurl;
    //     $extension = explode('.', $schoollogo->picurl);
    //     $this->Image('@'.file_get_contents($image_file),20,9,17,17);

    //     if(strtolower($schoollogo->abbreviation) == 'msmi')
    //     {
    //         $this->Cell(0, 15, 'Page '.$this->getAliasNumPage(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    //         $this->Cell(0, 25, date('m/d/Y'), 0, false, 'R', 0, '', 0, false, 'T', 'M');   
    //     }
        
    //     $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
    //     $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
    //     $title = $this->writeHTMLCell(false, 50, 40, 20, 'Cash Receipt Summary', false, false, false, $reseth=true, $align='L', $autopadding=true);
    //     // Ln();
    // }

    // Page footer
    // public function Footer() {
    //     $schoollogo = DB::table('schoolinfo')->first();
    //     // Position at 15 mm from bottom
    //     $this->SetY(-15);
    //     // Set font
    //     $this->SetFont('helvetica', 'I', 8);
    //     // Page number
    //     // $this->Cell(0, 15, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    //     // $this->Cell(0, 5, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
    //     if(strtolower($schoollogo->abbreviation) != 'msmi')
    //     {
    //         $this->Cell(0, 10, date('l, F d, Y'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
    //         $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    //         // $this->Cell(0, 15, date('m/d/Y'), 0, false, 'R', 0, '', 0, false, 'T', 'M');   
    //     }
    // }
}
