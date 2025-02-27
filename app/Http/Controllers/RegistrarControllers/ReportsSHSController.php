<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use TCPDF;
use Session;
use App\Models\Principal\GenerateGrade;
use App\Models\Principal\Section;
use App\Models\Principal\SPP_Attendance;
use App\Models\Principal\SPP_EnrolledStudent;
use App\Models\Principal\SPP_Gradelevel;
use App\Models\Principal\SPP_Subject;
use App\Models\Grading\GradingSystem;
class ReportsSHSController extends Controller
{
    function form5aindex(Request $request)
    {
        $strandids = array();

        $getstrandids = DB::table('studinfo')
            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
            ->select('sh_strand.id as strandid','sh_strand.strandcode','sh_strand.strandname','sh_track.id as trackid','sh_track.trackname','sh_enrolledstud.semid')
            ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
            ->join('sh_track','sh_strand.trackid','=','sh_track.id')
            ->where('sh_enrolledstud.syid', $request->get('syid'))
            ->where('sh_enrolledstud.levelid', $request->get('levelid'))
            ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
            // ->where('sh_enrolledstud.semid', DB::table('semester')->where('isactive','1')->first()->id)
            ->where('sh_enrolledstud.deleted','0')
            ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
            ->distinct()
            ->get();

        if(count($getstrandids) == 0)
        {
            $numofstudents = array();
        }else{
            $numofstudents = DB::table('studinfo')
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->where('sh_enrolledstud.syid', $request->get('syid'))
                ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                ->where('sh_enrolledstud.deleted','0')
                ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                ->whereIn('sh_enrolledstud.strandid',collect($getstrandids)->pluck('strandid'))
                ->get();    
        }
            
        if(count($getstrandids)>0)
        {
            foreach($getstrandids as $strandid)
            {
                $countstudents = collect($numofstudents)->where('semid', $strandid->semid)->values();                  

                $strandid->sem = $strandid->semid;
                $strandid->numofstudents = count($countstudents);
                $strandid->numofenrolledstudents = collect($countstudents)->where('studstatus','1')->count();
                $strandid->numoflateenrolledstudents = collect($countstudents)->where('studstatus','2')->count();
                $strandid->numofdroppedoutstudents = collect($countstudents)->where('studstatus','3')->count();
                $strandid->numoftransferredinstudents = collect($countstudents)->where('studstatus','4')->count();
                $strandid->numoftransferredoutstudents = collect($countstudents)->where('studstatus','5')->count();
                $strandid->numofwithdrawnstudents = collect($countstudents)->where('studstatus','6')->count();
                // return collect($strandid);
                array_push($strandids, $strandid);
            }
        }
        
        $levelname = DB::table('gradelevel')
            ->where('id', $request->get('levelid'))
            ->first();

        if(Session::get('currentPortal') == 1)
        {
            return view('teacher.forms.form5a.shsindex')
                ->with('formtype',$request->get('formtype'))
                ->with('levelname',$levelname->levelname)
                ->with('syid',$request->get('syid'))
                ->with('levelid',$levelname->id)
                ->with('sectionid',$request->get('sectionid'))
                ->with('selectedmonth',$request->get('selectedmonth'))
                ->with('strands',$strandids);
        }elseif(Session::get('currentPortal') == 3)
        {
            return view('registrar.forms.form5.form5ashsindex')
                ->with('formtype',$request->get('formtype'))
                ->with('levelname',$levelname->levelname)
                ->with('syid',$request->get('syid'))
                ->with('levelid',$levelname->id)
                ->with('sectionid',$request->get('sectionid'))
                ->with('selectedmonth',$request->get('selectedmonth'))
                ->with('strands',$strandids);
        }
    }
    function form5a(Request $request)
    {
        if($request->has('syid'))
        {
            $syid = $request->get('syid');
            $sy = DB::table('sy')
                ->select('id','sydesc')
                ->where('id',$syid)
                ->first();
        }else{
            $syid = DB::table('sy')
                ->select('id','sydesc')
                ->where('isactive',1)
                ->first()->id;
            $sy = DB::table('sy')
                ->select('id','sydesc')
                ->where('isactive',1)
                ->first();
        }
        
        if($request->has('semid'))
        {
            $sem = DB::table('semester')
                ->select('id','semester')
                ->where('id',$request->get('semid'))
                ->first();
        }else{
            $sem = DB::table('semester')
                ->select('id','semester')
                ->where('isactive','1')
                ->first();
        }

        if(Session::get('currentPortal') == 1)
        {
            $getSectionAndLevel = DB::table('teacher')
                ->select(
                    'teacher.id',
                    'sections.id as sectionid',
                    'sections.sectionname',
                    'gradelevel.id as levelid',
                    'gradelevel.levelname',
                    'academicprogram.acadprogcode'
                    )
                ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
                ->join('sections','sectiondetail.sectionid','=','sections.id')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('sectiondetail.syid',$syid)
                ->where('sections.id',$request->get('sectionid'))
                ->where('teacher.userid',auth()->user()->id)
                ->get();
        }else{
            
            $getSectionAndLevel = DB::table('teacher')
            ->select(
                'teacher.id',
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid',
                'gradelevel.levelname',
                'academicprogram.acadprogcode'
                )
            ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('sectiondetail.syid',$syid)
            ->where('sections.id',$request->get('sectionid'))
            ->get();
        }
        if(count($getSectionAndLevel) == 0)
        {
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
                'teacherid'     => 0,
                'levelid'     => $gradelevelname->id,
                'levelname'     => $gradelevelname->levelname,
                'sectionid'   => $sectionname->id,
                'sectionname'   => $sectionname->sectionname,
                'acadprogid'   => $acadprogcode->id,
                'acadprogcode'   => $acadprogcode->acadprogcode
            ));
        }
        $acadprogid = DB::table('gradelevel')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id', $getSectionAndLevel[0]->levelid)
            ->first()->id;

        $schoolinfo = DB::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.picurl',
                'schoolinfo.address',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();
            

        $getStudents = DB::table('studinfo')
            ->select(
                'sh_enrolledstud.id as enrollid',
                'studinfo.id',
                'studinfo.lrn',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'sh_enrolledstud.studstatus',
                'academicprogram.id as acadprogid',
                'gradelevel.id as levelid',
                'sections.id as sectionid',
                'sections.blockid',
                'sh_enrolledstud.strandid',
                'sh_enrolledstud.semid',
                'sh_enrolledstud.sectionid as ensectid',
                'gradelevel.id as enlevelid'
                )
            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
            ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
            ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
            ->where('sh_enrolledstud.levelid', $request->get('levelid'))
            ->where('sh_enrolledstud.strandid', $request->get('strandid'))
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('sh_enrolledstud.syid', $syid)
            ->where('sh_enrolledstud.semid',$sem->id)
            // ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
            ->where('sh_enrolledstud.studstatus','!=','0')
            ->where('sh_enrolledstud.studstatus','!=','6')
            ->where('sh_enrolledstud.deleted','0')
            ->orderBy('studinfo.lastname','asc')
            ->distinct()
            ->get();
            
        $getStudents = collect($getStudents)->sortByDesc('firstname')->sortBy('lastname')->all();
        $getStudents = collect($getStudents)->unique('id')->all(); 
        $strandid = $request->get('strandid');

        // foreach($getStudents as $student){
        //     array_push($strandids, array(
        //         'strandid' => $student->strandid
        //     ));
        // }

        // $strandids = collect($strandids)->unique();
        
        $trackAndStrands = array();

        // foreach($strandids as $strandid){
            
        $strandinfo = DB::table('sh_strand')
            ->select('strandcode as strand','trackname')
            ->join('sh_track','sh_strand.trackid','=','sh_track.id')
            ->where('sh_strand.id',$strandid)
            ->get();
                
        //     array_push($trackAndStrands, array(
        //         'track' => $strand[0]->trackname,
        //         'strand' => $strand[0]->strandcode
        //     ));

        // }
        
        // $gradesArray = array();

        // $firstsemgradesArray = array();

        // $secondsemgradesArray = array();
		
        foreach($getStudents as $student){

            $student->lastname = ucwords(mb_convert_case($student->lastname, MB_CASE_LOWER, "UTF-8"));
            $student->firstname = ucwords(mb_convert_case($student->firstname, MB_CASE_LOWER, "UTF-8"));
            $student->gender = strtolower($student->gender);
            if($student->middlename == null){
                $student->middlename = '';
            }else{
                $student->middlename = $student->middlename[0].'.';
            }
            if($student->suffix == null){
                $student->suffix = '';
            }else{
                $student->suffix = $student->suffix;
            }
            


            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'csl' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs'  || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lchs'   || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
            {
                
                $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                        
                $schoolyear = DB::table('sy')->where('id',$syid)->first();
                Session::put('schoolYear', $schoolyear);
                if($grading_version->version == 'v2'){
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $request->get('levelid'),$student->id,$schoolyear->id,$strandid,null,$request->get('sectionid'));
                }else{
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $request->get('levelid'),$student->id,$schoolyear->id,$strandid,null,$request->get('sectionid'));
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
            }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
            {
                $student->acadprogid = 4;
                $schoolyear = DB::table('sy')->where('id',$sy->id)->first();
                Session::put('schoolYear', $schoolyear);
                $checkGradingVersion = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
            
                if($checkGradingVersion->version == 'v1'){
                    $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV4($student, true, 'sf9',$schoolyear->id);
                
                }
                if($checkGradingVersion->version == 'v2'){
                    $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($student, true, 'sf9',$schoolyear->id);    
                    
                }
                $grades = $gradesv4;
        
                $studgrades = collect($grades)->unique('subjectcode');
                $generalaverage = array();
            }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'msmi')
            {
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $request->get('levelid'),$student->id,$sy->id,$strandid,null,$request->get('sectionid'));
                // return $studgrades;
                $temp_grades = array();
                $generalaverage = array();
                foreach($studgrades as $item){
                    if($item->id == 'G1'){
                        array_push($generalaverage,$item);
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
            }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'msmi')
            {
                $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$student->id,null);
        
                if($studentInfo[0]->count == 0){
        
                    $studentInfo = SPP_EnrolledStudent::getStudent(null,null,$student->id,null,5);
                }
        
                $grades = GenerateGrade::reportCardV3($studentInfo[0]->data[0], true, 'sf9');
        
                $checkGradingVersion = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
        
                if($checkGradingVersion->version == 'v1'){
        
                    $gradesv4 = GenerateGrade::reportCardV4($studentInfo[0]->data[0], true, 'sf9');
        
                }
                if($checkGradingVersion->version == 'v2'){
        
                    $gradesv4 = GenerateGrade::reportCardV5($studentInfo[0]->data[0], true, 'sf9');
        
                }
        
                $grades = $gradesv4;
        
                $strand = $studentInfo[0]->data[0]->strandid;
                $acad = $studentInfo[0]->data[0]->acadprogid;
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
                $studgrades = $grades;
                $generalaverage = GenerateGrade::genAveV3($grades);

            }else{
                $schoolyear = DB::table('sy')->where('id',$syid)->first();
                Session::put('schoolYear', $schoolyear);
                $subjects = \App\Models\Principal\SPP_Subject::getSubject(null,null,null,$request->get('sectionid'),null,null,null,null,'sf9',$schoolyear->id)[0]->data;
                // if()[0]->data;
                
                $temp_subject = array();
        
                foreach($subjects as $item){
                    array_push($temp_subject,$item);
                }           
                
                
                $subjects = $temp_subject;
                $studgrades = \App\Models\Grades\GradesData::student_grades_detail($syid,null,$request->get('sectionid'),null,$student->id, $request->get('levelid'),$strandid,null,$subjects);
    
                $studgrades =  \App\Models\Grades\GradesData::get_finalrating($studgrades,$acadprogid);;
                $finalgrade =  \App\Models\Grades\GradesData::general_average($studgrades);
                $generalaverage =  \App\Models\Grades\GradesData::get_finalrating($finalgrade,$acadprogid);
                
    
            }
            
            $grades1 = collect($studgrades)->where('semid','1')->values();
              
            foreach($grades1 as $filtergrades){
                $filtergrades->backsubject = 0;
                if(isset($filtergrades->subjdesc))
                {
                    $filtergrades->backsubject = 0;
                    
                    $filtergrades->subjectcode = $filtergrades->subjdesc;
                        
                    if($filtergrades->finalrating < 75 && $filtergrades->finalrating > 0){
    					
                        $filtergrades->remarks = 'INCOMPLETE';
                        $filtergrades->backsubject = 1;
                            
                     }else{
                        $filtergrades->remarks = 'COMPLETE';
                     }
                } 
            }
            if(collect($grades1)->where('backsubject',1)->count()>0)
            {
                
                $student->sem1status = 0; //incomplete
            }
            if(collect($grades1)->where('backsubject',1)->count()==0)
            {
                
                $student->sem1status = 1; //complete
            }
            $backsubjects = collect();
            $backsubjects = $backsubjects->merge(collect($grades1)->where('backsubject','1'));
            if(($student->studstatus == 3 || $student->studstatus == 5) && count($backsubjects) == 0)
            {
                    $student->sem1status = 0;//incomplete
            }


            $grades2 = collect($studgrades)->where('semid','2')->values();
			
            foreach($grades2 as $filtergrades){
                $filtergrades->backsubject = 0;
                if(isset($filtergrades->subjdesc))
                {
                    $filtergrades->subjectcode = $filtergrades->subjdesc;
                        
                    if($filtergrades->finalrating < 75 && $filtergrades->finalrating > 0){
    					
                        $filtergrades->remarks = 'INCOMPLETE';
                        $filtergrades->backsubject = 1;
                            
                     }else{
                        $filtergrades->remarks = 'COMPLETE';
                     }
                } 
                    
            }
            $backsubjects = $backsubjects->merge(collect($grades2)->where('backsubject','1'));
            if($sem->id == 1)
            {
                    $student->sem2status = null;
            }else{
                if(collect($grades2)->where('backsubject',1)->count()>0)
                {
                    
                    $student->sem2status = 0;//incomplete
                }
                if(collect($grades2)->where('backsubject',1)->count()==0)
                {
                    
                    $student->sem2status = 1;//complete
                }
                if(($student->studstatus == 3 || $student->studstatus == 5) && count($backsubjects) == 0)
                {
                        $student->sem2status = 0;//incomplete
                }
            }
            

            $student->backsubjects = $backsubjects;
			
        }
        if(Session::get('currentPortal') == 1)
        {
            $getTeacherName = DB::table('users')
                ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                ->join('teacher','users.id','=','teacher.userid')
                ->where('users.id',auth()->user()->id)
                ->first();

        }else{
            $getTeacherName = DB::table('teacher')
                ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
                ->where('sectiondetail.syid', $sy->id)
                ->where('sectiondetail.sectionid', $request->get('sectionid'))
                // ->where('users.id',auth()->user()->id)
                ->first();
        }
        if(!$getTeacherName)
        {
            $getTeacherName = (object) array(
                'teacherid'     => 0,
                'firstname'     => null,
                'lastname'     => null,
                'middlename'   => null,
                'suffix'   => null
            );
        }
        
        if($getTeacherName->middlename == null){
            $getTeacherName->middlename = '';
        }else{
            $getTeacherName->middlename = $getTeacherName->middlename[0].'.';
        }
        if($getTeacherName->suffix == null){
            $getTeacherName->suffix = '';
        }else{
            $getTeacherName->suffix = $getTeacherName->suffix;
        }

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
            ->where('grades.syid', $syid)
            ->get();
            
        if($request->get('action') == 'show' || $request->get('action') == 'preview'){

            $generalAverage = array();
            
            if(Session::get('currentPortal') == 1)
            {
                // return $getStudents;
                return view('teacher.forms.form5a.form5a')
                    ->with('school',$schoolinfo)
                    ->with('sy',$sy->sydesc)
                    ->with('syid',$sy->id)
                    ->with('gradeAndLevel',$getSectionAndLevel)
                    ->with('semester',$sem)
                    ->with('strandid',$request->get('strandid'))
                    ->with('students',$getStudents)
                    ->with('trackAndStrands',$trackAndStrands)
                    ->with('teachername',$getTeacherName)
                    ->with('principalname',$getPrincipal);
            }else
            //if(Session::get('currentPortal') == 3)
            {
                // return 'asdasdsa';
                return view('registrar.forms.form5a.form5a')
                    ->with('school',$schoolinfo)
                    ->with('sy',$sy->sydesc)
                    ->with('syid',$sy->id)
                    ->with('gradeAndLevel',$getSectionAndLevel)
                    ->with('semester',$sem)
                    ->with('strandid',$request->get('strandid'))
                    ->with('students',$getStudents)
                    ->with('trackAndStrands',$trackAndStrands)
                    ->with('teachername',$getTeacherName)
                    ->with('principalname',$getPrincipal);
            }

        }
        elseif($request->get('action') == 'export')
        {
            $principal = Db::table('teacher')
                ->select(
                    'teacher.title',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix'
                    )
                ->where('usertypeid','2')
                ->first();

            $principalname ='';
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

            $students = collect($getStudents)->all();
            
            $divisionrep = $request->get('divisionrep');
            
            if($request->get('exporttype') == 'pdf')
            {
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                // set document information
                $pdf->SetCreator('CK');
                $pdf->SetAuthor('CK Children\'s Publishing');
                // $pdf->SetTitle($schoolinfo->schoolname.' - Number of Enrollees');
                // $pdf->SetSubject('Number of Enrollees');
                
                // set header and footer fonts
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                
                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                
                // set margins
                // $pdf->SetMargins(5, 9, 5);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                
                // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, 9);
                
                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                
                // set some language-dependent strings (optional)
                if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
                }
                
                // ---------------------------------------------------------
                
                // set font
                // $pdf->SetFont('dejavusans', '', 10);
                
                
                // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                // Print a table
                
                // add a page
                // $pdf->AddPage('P','GOVERNMENTLEGAL');
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                {
                    $pdf->AddPage('L');
                }else{
                    $pdf->AddPage();
                }
                $courses = $request->get('courses');
                $view = \View::make('teacher/pdf/pdf_shsf5a',compact('schoolinfo','sy','getSectionAndLevel','acadprogid','students','getTeacherName','getPrincipal','sem','strandinfo','divisionrep','principalname','courses'));
                $html = $view->render();
                
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                {
                    $pdf->Image(base_path().'/public/'.DB::table('schoolinfo')->first()->picurl, 6, 8, 22, 22, '', '', '', false, 300, '', false, false, 0);
                }else{
                    if(strpos(DB::table('schoolinfo')->first()->picurl, '?') !== false){
                        $picurl = substr(DB::table('schoolinfo')->first()->picurl, 0, strpos(DB::table('schoolinfo')->first()->picurl, "?"));
                    }else{
                        $picurl = DB::table('schoolinfo')->first()->picurl;
                    }
                    $pdf->Image(base_path().'/public/'.$picurl, 8, 8, 15, 15, '', '', '', false, 300, '', false, false, 0);
                }
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                {
                    $pdf->Image(base_path().'/public/assets/images/department_of_Education.png', 265, 8, 22, 22, '', '', '', false, 300, '', false, false, 0);
                }else{
                    $pdf->Image(base_path().'/public/assets/images/department_of_Education.png', 185, 8, 15, 15, '', '', '', false, 300, '', false, false, 0);
                }
                

                $pdf->writeHTML($html, true, false, false, false, '');
                
                $pdf->lastPage();
                
                // ---------------------------------------------------------
                //Close and output PDF document
                if(count($getSectionAndLevel) == 0)
                {
                    $pdf->Output('School Form 5A.pdf', 'I');
                }else{
                    $pdf->Output('School Form 5A '.$getSectionAndLevel[0]->levelname.' - '.$getSectionAndLevel[0]->sectionname.'.pdf', 'I');
                }
                // $pdf = PDF::loadview('teacher/pdf/pdf_shsf5a',compact('schoolinfo','sy','getSectionAndLevel','students','getTeacherName','getPrincipal','getValues','sem','trackAndStrands','divisionrep','principalname'))->setPaper('legal','landscape');
    
                // return $pdf->stream('Class Record.pdf');
            }
            elseif($request->get('exporttype') == 'excel')
            {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path().'/public/excelformats/schoolform5a.xlsx');
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
                $sheet->setCellValue('F3', $schoolinfo->schoolname);
                $sheet->setCellValue('M3', $schoolinfo->schoolid);
                $sheet->setCellValue('O3', $schoolinfo->district);
                $sheet->setCellValue('R3', $schoolinfo->division);
                $sheet->setCellValue('U3', $schoolinfo->region);

                $sheet->setCellValue('E5', $sem->semester);
                $sheet->setCellValue('L5', $sy->sydesc);
                $sheet->mergeCells('O5:P5');
                $sheet->setCellValue('O5', $getSectionAndLevel[0]->levelname);
                $sheet->setCellValue('R5', $getSectionAndLevel[0]->sectionname);

                $sheet->setCellValue('E7', $strandinfo[0]->trackname.' - '.$strandinfo[0]->strand);

                $startcellno = 13;
                $malecellcounts = 0; //31
                $femalecellcounts = 0; // 22
                
                $malecount = 1;

                $firstmalecomplete = 0;
                $firstmaleincomplete = 0;
                $secondmalecomplete = 0;
                $secondmaleincomplete = 0;
                $firstfemalecomplete = 0;
                $firstfemaleincomplete = 0;
                $secondfemalecomplete = 0;
                $secondfemaleincomplete = 0;
                if(count($students)>0)
                {
                    foreach($students as $student)
                    {
                        if(strtolower($student->gender) == 'male')
                        {
                            
                            if($malecellcounts == 30)
                            {
                                $sheet->insertNewRowBefore($startcellno, 1);
                            }else{
                                $malecellcounts+=1;
                            }
                            
                            $sheet->setCellValue('B'.$startcellno, $malecount);
                            
                            $sheet->setCellValue('C'.$startcellno, $student->lrn);
                            $sheet->getStyle('C'.$startcellno)->getNumberFormat()->setFormatCode('0');
                            $sheet->mergeCells('D'.$startcellno.':I'.$startcellno);
                            $sheet->setCellValue('D'.$startcellno, $student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename);
                            $sheet->mergeCells('J'.$startcellno.':M'.$startcellno);
                            $sheet->setCellValue('J'.$startcellno, implode(", ",collect($student->backsubjects)->where('semid',$sem->id)->pluck('subjectcode')->values()->all()));

                            if(count(collect($student->backsubjects)->where('semid',$sem->id))==0)
                            {
                                $sheet->setCellValue('N'.$startcellno, 'COMPLETE');
                                if($sem->id == 1)
                                {
                                    $firstmalecomplete+=1;
                                }
                                elseif($sem->id == 2)
                                {
                                    $secondmalecomplete+=1;
                                }
                            }else{
                                $sheet->setCellValue('N'.$startcellno, 'INCOMPLETE');
                                if($sem->id == 1)
                                {
                                    $firstmaleincomplete+=1;
                                }
                                elseif($sem->id == 2)
                                {
                                    $secondmaleincomplete+=1;
                                }
                            }

                            $malecount+=1;
                            $startcellno+=1;
                        }
                    }

                    if($malecellcounts == 30)
                    {
                        $startcellno+=3;
                    }elseif($malecellcounts < 30)
                    {
                        $startcellno+=(2+(30-$malecellcounts));
                    }else{
                        $startcellno+=3;
                    }

                    $femalecount = 1;

                    $startcellno+=1;
                    foreach($students as $student)
                    {
                        if(strtolower($student->gender) == 'female')
                        {
                            
                            if($femalecellcounts == 21)
                            {
                                $sheet->insertNewRowBefore($startcellno, 1);
                            }else{
                                $femalecellcounts+=1;
                            }
                            $sheet->setCellValue('B'.$startcellno, $femalecount);
                            
                            $sheet->setCellValue('C'.$startcellno, $student->lrn);
                            $sheet->getStyle('C'.$startcellno)->getNumberFormat()->setFormatCode('0');
                            $sheet->mergeCells('D'.$startcellno.':I'.$startcellno);
                            $sheet->setCellValue('D'.$startcellno, $student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename);
                            $sheet->setCellValue('J'.$startcellno, implode(", ",collect($student->backsubjects)->where('semid',$sem->id)->pluck('subjectcode')->values()->all()));
                            $sheet->mergeCells('J'.$startcellno.':M'.$startcellno);

                            if(count(collect($student->backsubjects)->where('semid',$sem->id))==0)
                            {
                                $sheet->setCellValue('N'.$startcellno, 'COMPLETE');
                                if($sem->id == 1)
                                {
                                    $firstfemaleincomplete+=1;
                                }
                                elseif($sem->id == 2)
                                {
                                    $secondfemaleincomplete+=1;
                                }
                            }else{
                                $sheet->setCellValue('N'.$startcellno, 'INCOMPLETE');
                                if($sem->id == 1)
                                {
                                    $firstfemaleincomplete+=1;
                                }
                                elseif($sem->id == 2)
                                {
                                    $secondfemaleincomplete+=1;
                                }
                            }
                            $femalecount+=1;
                            $startcellno+=1;

                        }
                    }
                    if($femalecellcounts == 21)
                    {
                        $startcellno+=3;
                        $sheet->insertNewRowBefore($startcellno, 1);
                    }elseif($femalecellcounts < 21)
                    {
                        $startcellno+=(2+(21-$femalecellcounts));
                    }else{
                        $startcellno+=3;
                    }
                }

                $sheet->setCellValue('R15', $firstmalecomplete);
                $sheet->setCellValue('S15', $firstfemalecomplete);
                $sheet->setCellValue('T15', $firstmalecomplete+$firstfemalecomplete);

                $sheet->setCellValue('R16', $firstmaleincomplete);
                $sheet->setCellValue('S16', $firstfemaleincomplete);
                $sheet->setCellValue('T16', $firstmaleincomplete+$firstfemaleincomplete);
                
                $sheet->setCellValue('R17', '=SUM(R15,R16)');
                $sheet->setCellValue('S17', '=SUM(S15,S16)');
                $sheet->setCellValue('T17', '=SUM(T15,T16)');

                if($sem->id == 2)
                {
                    $sheet->setCellValue('R21', $secondmalecomplete);
                    $sheet->setCellValue('S21', $secondfemalecomplete);
                    $sheet->setCellValue('T21', $secondmalecomplete+$secondfemalecomplete);
    
                    $sheet->setCellValue('R22', $secondmaleincomplete);
                    $sheet->setCellValue('S22', $secondfemaleincomplete);
                    $sheet->setCellValue('T22', $secondmaleincomplete+$secondfemaleincomplete);
                    
                    $sheet->setCellValue('R23', '=SUM(R21,R22)');
                    $sheet->setCellValue('S23', '=SUM(S21,S22)');
                    $sheet->setCellValue('T23', '=SUM(T21,T22)');
                }
                $sheet->setCellValue('Q52', strtoupper($getTeacherName->firstname.' '.$getTeacherName->middlename.' '.$getTeacherName->lastname.' '.$getTeacherName->suffix));
                $sheet->setCellValue('Q59', strtoupper($principalname));
                $sheet->setCellValue('Q65', strtoupper($divisionrep));
                
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="School Form 5A '.$getSectionAndLevel[0]->levelname.' - '.$getSectionAndLevel[0]->sectionname.'.xlsx"');
                $writer->save("php://output");
            }
        }
    }
    function form5bindex(Request $request)
    {
        $strandids = array();

        $getstrandids = DB::table('studinfo')
            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
            ->select('sh_strand.id as strandid','sh_strand.strandcode','sh_strand.strandname','sh_track.id as trackid','sh_track.trackname','sh_enrolledstud.semid')
            ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
            ->join('sh_track','sh_strand.trackid','=','sh_track.id')
            ->where('sh_enrolledstud.syid', $request->get('syid'))
            ->where('sh_enrolledstud.levelid', $request->get('levelid'))
            ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
            // ->where('sh_enrolledstud.semid', DB::table('semester')->where('isactive','1')->first()->id)
            ->where('sh_enrolledstud.deleted','0')
            ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
            ->distinct()
            ->get();

        if(count($getstrandids) == 0)
        {
            $numofstudents = array();
        }else{
            $numofstudents = DB::table('studinfo')
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->where('sh_enrolledstud.syid', $request->get('syid'))
                ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                ->where('sh_enrolledstud.deleted','0')
                ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                ->whereIn('sh_enrolledstud.strandid',collect($getstrandids)->pluck('strandid'))
                ->get();    
        }
            
        if(count($getstrandids)>0)
        {
            foreach($getstrandids as $strandid)
            {
                $countstudents = collect($numofstudents)->where('semid', $strandid->semid)->values();                  

                $strandid->sem = $strandid->semid;
                $strandid->numofstudents = count($countstudents);
                $strandid->numofenrolledstudents = collect($countstudents)->where('studstatus','1')->count();
                $strandid->numoflateenrolledstudents = collect($countstudents)->where('studstatus','2')->count();
                $strandid->numofdroppedoutstudents = collect($countstudents)->where('studstatus','3')->count();
                $strandid->numoftransferredinstudents = collect($countstudents)->where('studstatus','4')->count();
                $strandid->numoftransferredoutstudents = collect($countstudents)->where('studstatus','5')->count();
                $strandid->numofwithdrawnstudents = collect($countstudents)->where('studstatus','6')->count();
                // return collect($strandid);
                array_push($strandids, $strandid);
            }
        }

        $levelname = DB::table('gradelevel')
            ->where('id', $request->get('levelid'))
            ->first();

        if(Session::get('currentPortal') == 1)
        {
            return view('teacher.forms.form5b.shsindex')
                ->with('formtype',$request->get('formtype'))
                ->with('levelname',$levelname->levelname)
                ->with('syid',$request->get('syid'))
                ->with('levelid',$levelname->id)
                ->with('sectionid',$request->get('sectionid'))
                ->with('selectedmonth',$request->get('selectedmonth'))
                ->with('strands',$strandids);
        }elseif(Session::get('currentPortal') == 3)
        {
            return view('registrar.forms.form5.form5bshsindex')
                ->with('formtype',$request->get('formtype'))
                ->with('levelname',$levelname->levelname)
                ->with('syid',$request->get('syid'))
                ->with('levelid',$levelname->id)
                ->with('sectionid',$request->get('sectionid'))
                ->with('selectedmonth',$request->get('selectedmonth'))
                ->with('strands',$strandids);
        }
    }
    function form5b(Request $request)
    {
        
        if($request->has('syid'))
        {
            $sy = DB::table('sy')
                ->select('id','sydesc')
                ->where('id',$request->get('syid'))
                ->first();
        }else{
            $sy = DB::table('sy')
                ->select('id','sydesc')
                ->where('isactive',1)
                ->first();
        }

        $acadprogid = DB::table('gradelevel')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id', $request->get('levelid'))
            ->first()->id;

        if(Session::get('currentPortal') == 1)
        {
            $getSectionAndLevel = DB::table('teacher')
                ->select(
                    'teacher.id',
                    'sections.id as sectionid',
                    'sections.sectionname',
                    'gradelevel.id as levelid',
                    'gradelevel.levelname',
                    'academicprogram.acadprogcode'
                    )
                ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
                ->join('sections','sectiondetail.sectionid','=','sections.id')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('sectiondetail.syid',$sy->id)
                ->where('sections.id',$request->get('sectionid'))
                ->where('teacher.userid',auth()->user()->id)
                ->get();

        }else{
            $getSectionAndLevel = DB::table('teacher')
                ->select(
                    'teacher.id',
                    'sections.id as sectionid',
                    'sections.sectionname',
                    'gradelevel.id as levelid',
                    'gradelevel.levelname',
                    'academicprogram.acadprogcode'
                    )
                ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
                ->join('sections','sectiondetail.sectionid','=','sections.id')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('sectiondetail.syid',$sy->id)
                ->where('sections.id',$request->get('sectionid'))
                // ->where('teacher.userid',auth()->user()->id)
                ->get();
        }
        if(count($getSectionAndLevel) == 0)
        {
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
                'teacherid'     => 0,
                'levelid'     => $gradelevelname->id,
                'levelname'     => $gradelevelname->levelname,
                'sectionid'   => $sectionname->id,
                'sectionname'   => $sectionname->sectionname,
                'acadprogid'   => $acadprogcode->id,
                'acadprogcode'   => $acadprogcode->acadprogcode
            ));
        }

        $getSchoolInfo = DB::table('schoolinfo')
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
            ->first();

        // $getSchoolYear = DB::table('sy')
        //     ->select('id','sydesc')
        //     ->where('isactive','1')
        //     ->first();

        if($request->has('semid'))
        {
            $sem = DB::table('semester')
                ->select('id','semester')
                ->where('id',$request->get('semid'))
                ->first();
        }else{
            $sem = DB::table('semester')
                ->select('id','semester')
                ->where('isactive','1')
                ->first();
        }
            
        // return $request->all();
        $getStudents = DB::table('studinfo')
            ->select(
                'sh_enrolledstud.id as enrollid',
                'studinfo.id',
                'studinfo.lrn',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'sh_enrolledstud.studstatus',
                'academicprogram.id as acadprogid',
                'gradelevel.id as levelid',
                'sections.id as sectionid',
                'sections.blockid',
                'sh_enrolledstud.strandid',
                'sh_enrolledstud.semid',
                'sh_enrolledstud.sectionid as ensectid',
                'studinfo.studtype',
                'gradelevel.id as enlevelid'
                )
            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
            ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
            ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
            ->where('sh_enrolledstud.strandid', $request->get('strandid'))
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('sh_enrolledstud.syid', $sy->id)
            ->where('sh_enrolledstud.semid',$sem->id)
            ->where('sh_enrolledstud.studstatus','!=','0')
            ->where('sh_enrolledstud.studstatus','!=','6')
            ->where('sh_enrolledstud.deleted','0')
            ->orderBy('studinfo.lastname','asc')
            ->distinct()
            ->get();
        
        $getStudents = collect($getStudents)->sortByDesc('firstname')->sortBy('lastname')->all();
        $getStudents = collect($getStudents)->unique('id')->all(); 
        $strandid = $request->get('strandid');

        $trackAndStrands = array();

        $strandinfo = DB::table('sh_strand')
            ->select('strandcode as strand','trackname')
            ->join('sh_track','sh_strand.trackid','=','sh_track.id')
            ->where('sh_strand.id',$strandid)
            ->get();
            
        $filterArray = array();

        foreach($getStudents as $student){
            $student->gender = strtolower($student->gender);
            $student->lastname = ucwords(mb_convert_case($student->lastname, MB_CASE_LOWER, "UTF-8"));
            $student->firstname = ucwords(mb_convert_case($student->firstname, MB_CASE_LOWER, "UTF-8"));
            if($student->middlename == null){
                $student->middlename = '';
            }else{
                $student->middlename = $student->middlename[0].'.';
            }
            if($student->suffix == null){
                $student->suffix = '';
            }else{
                $student->suffix = $student->suffix;
            }
            $semcompleted = DB::table('sh_enrolledstud')
                ->where('id',$student->enrollid)
                ->distinct()
                ->get();

            $completed = 0;
            $certificationlevel = null;
            
            $checkifexists = DB::table('sf5b')
                ->where('syid', $sy->id)
                ->where('semid', $sem->id)
                ->where('levelid', $request->get('levelid'))
                ->where('sectionid', $request->get('sectionid'))
                ->where('strandid', $request->get('strandid'))
                ->where('studid', $student->id)
                ->where('deleted','0')
                ->first();

            if($checkifexists)
            {
                $completed = $checkifexists->completed;
                $certificationlevel = $checkifexists->certificationlevel;
            }
            $student->completed = $completed;
            $student->certificationlevel = $certificationlevel;

            if(count($semcompleted)==4){
                foreach($semcompleted as $filtercompleted){
            
                    

                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'csl')
                    {
                        
                        $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                                
                        $schoolyear = DB::table('sy')->where('id',$syid)->first();
                        Session::put('schoolYear', $schoolyear);
                        if($grading_version->version == 'v2'){
                            $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $request->get('levelid'),$student->id,$schoolyear->id,$strandid,null,$request->get('sectionid'));
                        }else{
                            $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $request->get('levelid'),$student->id,$schoolyear->id,$strandid,null,$request->get('sectionid'));
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
                    }elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                    {
                        $student->acadprogid = 4;
                        $schoolyear = DB::table('sy')->where('id',$sy->id)->first();
                        Session::put('schoolYear', $schoolyear);
                        $checkGradingVersion = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                    
                        if($checkGradingVersion->version == 'v1'){
                            $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV4($student, true, 'sf9',$schoolyear->id);
                        
                        }
                        if($checkGradingVersion->version == 'v2'){
                            $gradesv4 = \App\Models\Principal\GenerateGrade::reportCardV5($student, true, 'sf9',$schoolyear->id);    
                            
                        }
                        $grades = $gradesv4;
                
                        $studgrades = collect($grades)->unique('subjectcode');
                        $generalaverage = array();
                    }else{
                        $schoolyear = DB::table('sy')->where('id',$syid)->first();
                        Session::put('schoolYear', $schoolyear);
                        $subjects = \App\Models\Principal\SPP_Subject::getSubject(null,null,null,$request->get('sectionid'),null,null,null,null,'sf9',$schoolyear->id)[0]->data;
                        
                        $temp_subject = array();
                
                        foreach($subjects as $item){
                            array_push($temp_subject,$item);
                        }
                        
                        $subjects = $temp_subject;
                        $studgrades = \App\Models\Grades\GradesData::student_grades_detail($syid,null,$request->get('sectionid'),null,$student->id, $request->get('levelid'),$strandid,null,$subjects);
                        
                        $studgrades =  \App\Models\Grades\GradesData::get_finalrating($studgrades,$acadprogid);
                        $finalgrade =  \App\Models\Grades\GradesData::general_average($studgrades);
                        $generalaverage =  \App\Models\Grades\GradesData::get_finalrating($finalgrade,$acadprogid);
                    }
                    
                    
                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                // {
                //     $pdf->AddPage('L');
                // }else{
                    $backSubjects = array();
                    foreach($studgrades as $filtergrades){
                        // return $filtergrades;
                        if((($filtergrades[0]->qg + $filtergrades[1]->qg) / 2) < 75){
                            array_push($backSubjects, $filtergrades[0]);
                        }
                        if((($filtergrades[2]->qg + $filtergrades[3]->qg) / 2) < 75){
                            array_push($backSubjects, $filtergrades[0]);
                        }
                    }
                    if(count($backSubjects)!=0){
                        $student->status = 'COMPLETED';
                        // array_push($filterArray, (object)array(
                        //     'studentdata' =>  $student,
                        //     'status' => 'COMPLETED'
                        // ));
                    }
                }
            }
            elseif(count($semcompleted)>4){
                $student->status = 'OVERSTAYING';
                // array_push($filterArray, (object)array(
                //     'studentdata' =>  $student,
                //     'status' => 'OVERSTAYING'
                // ));
            }
            elseif(count($semcompleted)<4){
                $student->status = 'COMPLETE';

                // array_push($filterArray, (object)array(
                //     'studentdata' =>  $student,
                //     'status' => 'COMPLETE'
                // ));
                
            }

            
        }
        
        if(Session::get('currentPortal') == 1)
        {
            $getTeacherName = DB::table('users')
                ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                ->join('teacher','users.id','=','teacher.userid')
                ->where('users.id',auth()->user()->id)
                ->first();

        }else{
            $getTeacherName = DB::table('teacher')
                ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
                ->where('sectiondetail.syid', $sy->id)
                ->where('sectiondetail.sectionid', $request->get('sectionid'))
                // ->where('users.id',auth()->user()->id)
                ->first();
        }
        
        if(!$getTeacherName)
        {
            $getTeacherName = (object) array(
                'teacherid'     => 0,
                'firstname'     => null,
                'lastname'     => null,
                'middlename'   => null,
                'suffix'   => null
            );
        }

        if($getTeacherName->middlename == null){
            $getTeacherName->middlename = '';
        }else{
            $getTeacherName->middlename = $getTeacherName->middlename[0].'.';
        }
        if($getTeacherName->suffix == null){
            $getTeacherName->suffix = '';
        }else{
            $getTeacherName->suffix = $getTeacherName->suffix;
        }
        
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
            ->where('grades.syid', $sy->id)
            ->get();

        if($request->get('action') == 'show'){
            
            if(Session::get('currentPortal') == 1)
            {
                return view('teacher.forms.form5b.form5b')
                    ->with('school',$getSchoolInfo)
                    ->with('strandid',$request->get('strandid'))
                    ->with('sy',$sy->sydesc)
                    ->with('syid',$sy->id)
                    ->with('gradeAndLevel',$getSectionAndLevel)
                    ->with('semester',$sem)
                    ->with('filter',$filterArray)
                    ->with('students',$getStudents)
                    ->with('trackAndStrands',$trackAndStrands)
                    ->with('teachername',$getTeacherName)
                    ->with('principalname',$getPrincipal);
                    
            }if(Session::get('currentPortal') == 3)
            {
                return view('registrar.forms.form5b.form5b')
                    ->with('school',$getSchoolInfo)
                    ->with('strandid',$request->get('strandid'))
                    ->with('sy',$sy->sydesc)
                    ->with('syid',$sy->id)
                    ->with('gradeAndLevel',$getSectionAndLevel)
                    ->with('semester',$sem)
                    ->with('filter',$filterArray)
                    ->with('students',$getStudents)
                    ->with('trackAndStrands',$trackAndStrands)
                    ->with('teachername',$getTeacherName)
                    ->with('principalname',$getPrincipal);
            }
        }
        elseif($request->get('action') == 'updateeachstudent')
        {
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $strandid = $request->get('strandid');
            $students = json_decode($request->get('students'));

            if(count($students) > 0)
            {
                foreach($students as $student)
                {
                    $checkifexists = DB::table('sf5b')
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('levelid', $levelid)
                        ->where('sectionid', $sectionid)
                        ->where('strandid', $strandid)
                        ->where('studid', $student->studid)
                        ->where('deleted','0')
                        ->first();

                    if($checkifexists)
                    {
                        DB::table('sf5b')
                            ->where('id', $checkifexists->id)
                            ->update([
                                'completed'     => $student->completed,
                                'certificationlevel'     => $student->certificationlevel,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }else{
                        DB::table('sf5b')                        
                            ->insert([
                                'syid'                  => $syid,
                                'semid'                  => $semid,
                                'levelid'                  => $levelid,
                                'sectionid'                  => $sectionid,
                                'strandid'                  => $strandid,
                                'studid'                  => $student->studid,
                                'completed'     => $student->completed,
                                'certificationlevel'     => $student->certificationlevel,
                                'createdby' => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }
            return 1;
        }
        elseif($request->get('action') == 'export'){

            $courses = $request->get('courses');
            $certificationattained = array();

            foreach($request->except('curriculum','teacher','schoolhead','divisionrep','nciiimale','nciiifemale','nciiitotal','nciimale','nciifemale','nciitotal','ncimale','ncifemale','ncitotal','nctotalmale','nctotalfemale','nctotal','courses') as $key => $value){
                
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
            
            $divisionrep = $request->get('divisionrep');
            
            $principal = Db::table('academicprogram')
                ->select(
                    'teacher.title',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.lastname',
                    'teacher.suffix'
                    )
                ->leftJoin('teacher','academicprogram.principalid','=','teacher.id')
                ->where('academicprogram.id', 5)
                ->first();
                
            $principalname ='';
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
            if($request->get('exporttype') == 'pdf')
            {
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                // set document information
                $pdf->SetCreator('CK');
                $pdf->SetAuthor('CK Children\'s Publishing');
                // $pdf->SetTitle($schoolinfo->schoolname.' - Number of Enrollees');
                $pdf->SetSubject('Number of Enrollees');
                
                // set header and footer fonts
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                
                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                
                // set margins
                // $pdf->SetMargins(5, 9, 5);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                
                // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                
                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                
                // set some language-dependent strings (optional)
                if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
                }
                
                // ---------------------------------------------------------
                
                // set font
                // $pdf->SetFont('dejavusans', '', 10);
                
                
                // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                // Print a table
                
                // add a page
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                {
                    $pdf->AddPage('L');
                }else{
                    $pdf->AddPage('P','GOVERNMENTLEGAL');
                }
                
                
                // return $getStudents;
                $view = \View::make('teacher/pdf/pdf_shsf5b',compact('getSchoolInfo','sy','getSectionAndLevel','filterArray','getTeacherName','getPrincipal','sem','strandinfo','divisionrep','certificationattained','ncArray','getStudents','courses'));
                $html = $view->render();
                
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                {
                    $pdf->Image(base_path().'/public/'.DB::table('schoolinfo')->first()->picurl, 6, 8, 22, 22, '', '', '', false, 300, '', false, false, 0);
                }else{
                    if(strpos(DB::table('schoolinfo')->first()->picurl, '?') !== false){
                        $picurl = substr(DB::table('schoolinfo')->first()->picurl, 0, strpos(DB::table('schoolinfo')->first()->picurl, "?"));
                    }else{
                        $picurl = DB::table('schoolinfo')->first()->picurl;
                    }
                    $pdf->Image(base_path().'/public/'.$picurl, 8, 8, 15, 15, '', '', '', false, 300, '', false, false, 0);
                }
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                {
                    $pdf->Image(base_path().'/public/assets/images/department_of_Education.png', 265, 8, 22, 22, '', '', '', false, 300, '', false, false, 0);
                }else{
                    $pdf->Image(base_path().'/public/assets/images/department_of_Education.png', 195, 8, 15, 15, '', '', '', false, 300, '', false, false, 0);
                }

                $pdf->writeHTML($html, true, false, false, false, '');
                
                $pdf->lastPage();
                
                // ---------------------------------------------------------
                //Close and output PDF document
                if(count($getSectionAndLevel) == 0)
                {
                    $pdf->Output('School Form 5B.pdf', 'I');
                }else{
                    $pdf->Output('School Form 5B '.$getSectionAndLevel[0]->levelname.' - '.$getSectionAndLevel[0]->sectionname.'.pdf', 'I');
                }
                // $pdf = PDF::loadview('teacher/pdf/pdf_shsf5b',compact('getSchoolInfo','sy','getSectionAndLevel','filterArray','getTeacherName','getPrincipal','getValues','sem','trackAndStrands','divisionrep','certificationattained','ncArray'))->setPaper('legal','landscape');
    
                // return $pdf->stream('School Form 5B.pdf');
            }else{
                $signatoriesv2 = DB::table('signatory')
                        ->where('form','form5b')
                        ->where('syid', $sy->id)
                        ->where('deleted','0')
                        ->where('acadprogid',5)
                        ->get();
                
                if(count($signatoriesv2) == 0)
                {
                    $signatoriesv2 = DB::table('signatory')
                    ->where('form','form5b')
                    ->where('syid', $sy->id)
                        ->where('deleted','0')
                        ->where('acadprogid',0)
                        ->get();
                
                    if(count($signatoriesv2)>0)
                    {
                        if(collect($signatoriesv2)->where('levelid', $getSectionAndLevel[0]->levelid)->count() == 0)
                        {
                            $signatoriesv2 = collect($signatoriesv2)->where('levelid',0)->values();
                        }else{
                            $signatoriesv2 = collect($signatoriesv2)->where('levelid', $getSectionAndLevel[0]->levelid)->values();
                        }
                    }
                
                    
                }else{
                    if(collect($signatoriesv2)->where('levelid', $getSectionAndLevel[0]->levelid)->count() == 0)
                    {
                        $signatoriesv2 = collect($signatoriesv2)->where('levelid',0)->values();
                    }else{
                        $signatoriesv2 = collect($signatoriesv2)->where('levelid', $getSectionAndLevel[0]->levelid)->values();
                    }
                }
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path().'/public/excelformats/schoolform5b.xlsx');
                $sheet = $spreadsheet->getActiveSheet();
                $borderstyle = [
                    'borders' => [
                        'outline' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => array('argb' => '00000000'),
                        ),
                    ]
                ];
                
                $sheet->setCellValue('E3', $getSchoolInfo->schoolname);
                $sheet->setCellValue('Q3', $getSchoolInfo->schoolid);
                $sheet->setCellValue('T3', $getSchoolInfo->district ?? DB::table('schoolinfo')->first()->districttext);
                $sheet->setCellValue('X3', $getSchoolInfo->division ?? DB::table('schoolinfo')->first()->divisiontext);
                $sheet->setCellValue('Z3', $getSchoolInfo->region ?? DB::table('schoolinfo')->first()->regiontext);

                $sheet->setCellValue('E5', $sem->semester);
                $sheet->setCellValue('Q5', $sy->sydesc);
                // $sheet->mergeCells('O5:P5');
                // $sheet->setCellValue('O5', $getSectionAndLevel[0]->levelname);
                $sheet->setCellValue('T5', $getSectionAndLevel[0]->sectionname);

                $sheet->mergeCells('E7:P7');
                $sheet->setCellValue('E7', $strandinfo[0]->trackname.' - '.$strandinfo[0]->strand);

                $startcellno = 15;
                $malecellcounts = 0; //29
                $femalecellcounts = 0; // 21

                // return $filterArray;

                $data = $filterArray;
                
                $completemale = 0;
                $overstayingmale = 0;
                $completefemale = 0;
                $overstayingfemale = 0;
                
                if(count($getStudents)>0)
                {
                    $malecount = 1;

                    foreach($getStudents as $dataval)
                    {
                        if(strtolower($dataval->gender) == 'male')
                        {
                            if($malecellcounts == 30)
                            {
                                $sheet->insertNewRowBefore($startcellno, 1);
                            }else{
                                $malecellcounts+=1;
                            }
                            $sheet->setCellValue('A'.$startcellno, $malecount);
                            
                            $sheet->setCellValue('B'.$startcellno, $dataval->lrn);
                            $sheet->getStyle('B'.$startcellno)->getNumberFormat()->setFormatCode('0');
                            // $sheet->mergeCells('D'.$startcellno.':I'.$startcellno);
                            $sheet->setCellValue('C'.$startcellno, ucwords(strtolower($dataval->lastname.', '.$dataval->firstname.' '.$dataval->suffix.' '.$dataval->middlename)));
                            $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('left');

                            if($dataval->completed == 1)
                            {
                                $sheet->setCellValue('S'.$startcellno, 'Y');
                                $completemale+=1;
                            }else{
                                $sheet->setCellValue('S'.$startcellno, 'N');
                            }
                            
                            $sheet->setCellValue('T'.$startcellno, $dataval->certificationlevel);
                            // foreach ($certificationattained as $certificate)
                            // {
                            //     if($dataval->id == $certificate->name)
                            //     {
                            //         $sheet->setCellValue('T'.$startcellno, $certificate->certificate);
                                    
                            //     }
                            // }
                            $malecount+=1;
                            $startcellno+=1;
                        }
                    }

                    if($malecellcounts == 28)
                    {
                        $startcellno+=2;
                    }elseif($malecellcounts < 28)
                    {
                        $startcellno+=(2+(28-$malecellcounts));
                    }else{
                        $startcellno+=2;
                    }

                    $femalecount = 1;

                    // $startcellno+=1;
                    foreach($getStudents as $dataval)
                    {
                        if(strtolower($dataval->gender) == 'female')
                        {
                            if($femalecellcounts == 19)
                            {
                                $sheet->insertNewRowBefore($startcellno, 1);
                            }else{
                                $femalecellcounts+=1;
                            }
                            $sheet->setCellValue('A'.$startcellno, $femalecount);
                            $sheet->setCellValue('B'.$startcellno, $dataval->lrn);
                            $sheet->getStyle('B'.$startcellno)->getNumberFormat()->setFormatCode('0');
                            $sheet->mergeCells('C'.$startcellno.':R'.$startcellno);
                            $sheet->setCellValue('C'.$startcellno, ucwords(strtolower($dataval->lastname.', '.$dataval->firstname.' '.$dataval->suffix.' '.$dataval->middlename)));
                            $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('left');

                            if($dataval->completed == 1)
                            {
                                $sheet->setCellValue('S'.$startcellno, 'Y');
                                $completefemale+=1;
                            }else{
                                $sheet->setCellValue('S'.$startcellno, 'N');
                            }
                            

                            $sheet->setCellValue('T'.$startcellno, $dataval->certificationlevel);
                            // foreach ($certificationattained as $certificate)
                            // {
                            //     if($dataval->id == $certificate->name)
                            //     {
                            //         $sheet->setCellValue('T'.$startcellno, $certificate->certificate);
                                    
                            //     }
                            // }
                            $startcellno+=1;
                            $femalecount+=1;
                        }
                    }
                }

                $sheet->setCellValue('X17', $completemale);
                $sheet->setCellValue('Y17', $completefemale);
                $sheet->setCellValue('Z17', $completemale+$completefemale);

                $sheet->setCellValue('X21', $overstayingmale);
                $sheet->setCellValue('Y21', $overstayingfemale);
                $sheet->setCellValue('Z21', $overstayingmale+$overstayingfemale);

                $sheet->setCellValue('X25', $completemale+$overstayingmale);
                $sheet->setCellValue('Y25', $completefemale+$overstayingfemale);
                $sheet->setCellValue('Z25', $completemale+$completefemale+$overstayingmale+$overstayingfemale);

                $sheet->setCellValue('X33', $request->get('ncimale'));
                $sheet->setCellValue('Y33', $request->get('ncifemale'));
                $sheet->setCellValue('Z33', $request->get('ncitotal'));

                $sheet->setCellValue('X34', $request->get('nctotalmale'));
                $sheet->setCellValue('Y34', $request->get('nctotalfemale'));
                $sheet->setCellValue('Z34', $request->get('nctotal'));

                // $sheet->setCellValue('R16', $firstmaleincomplete);
                // $sheet->setCellValue('S16', $firstfemaleincomplete);
                // $sheet->setCellValue('T16', $firstmaleincomplete+$firstfemaleincomplete);
                
                // $sheet->setCellValue('R17', '=SUM(R15,R16)');
                // $sheet->setCellValue('S17', '=SUM(S15,S16)');
                // $sheet->setCellValue('T17', '=SUM(T15,T16)');

                // if($sem->id == 2)
                // {
                //     $sheet->setCellValue('R21', $secondmalecomplete);
                //     $sheet->setCellValue('S21', $secondfemalecomplete);
                //     $sheet->setCellValue('T21', $secondmalecomplete+$secondfemalecomplete);
    
                //     $sheet->setCellValue('R22', $secondmaleincomplete);
                //     $sheet->setCellValue('S22', $secondfemaleincomplete);
                //     $sheet->setCellValue('T22', $secondmaleincomplete+$secondfemaleincomplete);
                    
                //     $sheet->setCellValue('R23', '=SUM(R21,R22)');
                //     $sheet->setCellValue('S23', '=SUM(S21,S22)');
                //     $sheet->setCellValue('T23', '=SUM(T21,T22)');
                // }
                $sheet->setCellValue('W47', $getTeacherName->firstname.' '.$getTeacherName->middlename.' '.$getTeacherName->lastname.' '.$getTeacherName->suffix);
                $principal = $principalname; 
                $principaldesc = 'Signature of School Head over Printed Name';
                $divrep = ''; 
                $divrepdesc = 'Signature of Division Representative over Printed Name';
                if(count($signatoriesv2)>0)
                {
                    foreach($signatoriesv2 as $eachsignatory)
                    {
                        if(strpos(strtolower($eachsignatory->description), 'principal') !== false || strpos(strtolower($eachsignatory->description), 'school head') !== false){
                            $principal = $eachsignatory->name;
                            $principaldesc = $eachsignatory->description;
                        }
                        if(strpos(strtolower($eachsignatory->description), 'division') !== false || strpos(strtolower($eachsignatory->description), 'representative') !== false){
                            $divrep = $eachsignatory->name;
                            $divrepdesc = $eachsignatory->description;
                        }
                        
                    }
                }
                $sheet->setCellValue('W54', $principal);
                $sheet->setCellValue('W55', $principaldesc);
                $sheet->setCellValue('W60', $divrep);
                $sheet->setCellValue('W61', $divrepdesc);

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="School Form 5B '.$getSectionAndLevel[0]->levelname.' - '.$getSectionAndLevel[0]->sectionname.'.xlsx"');
                $writer->save("php://output");
            }
        }
    }
}
