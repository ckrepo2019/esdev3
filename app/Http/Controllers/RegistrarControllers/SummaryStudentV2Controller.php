<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\Finance\StudentAssessmentModel;
use App\Models\Registrar\SummaryTables;
use DateTime;
use DateInterval;
use DatePeriod;
class SummaryStudentV2Controller extends Controller
{
    
    public function reportssummariesallstudentsnew($id, Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');
        
        $academicprogram = Db::table('academicprogram')
            ->get();
            
        $gradelevels = Db::table('gradelevel')
            ->where('deleted','0')
            ->orderBy('sortid','asc')
            ->get();

        $schoolyears = DB::table('sy')
            ->orderByDesc('sydesc','isactive')
            ->get();

        $semesters = DB::table('semester')
            ->get();

        $studentstatus = Db::table('studentstatus')
            ->where('id','!=','0')
            // ->where('id','!=','6')
            ->get();

        $modeoflearnings = Db::table('modeoflearning')
            ->where('deleted','0')
            ->get();
            
        $grantees = Db::table('grantee')
        ->get();
            // ->
            // ->get();
        if($id == 'dashboard'){

            $tracks = Db::table('sh_track')
                    ->where('deleted','0')
                    ->get();

            $strands = Db::table('sh_strand')
                    ->where('active','1')
                    ->where('deleted','0')
                    ->get();

            $colleges = DB::table('college_colleges')
                ->where('deleted','0')
                ->orderBy('collegeDesc','asc')
                ->get();

            $courses = DB::table('college_courses')
                ->where('deleted','0')
                ->orderBy('courseDesc','asc')
                ->get();              
            

            return view('registrar.summaries.summariesallstudents')
                ->with('grantees', $grantees)
                ->with('modeoflearnings', $modeoflearnings)
                ->with('studentstatus', $studentstatus)
                ->with('academicprogram', $academicprogram)
                ->with('gradelevels', $gradelevels)
                ->with('schoolyears', $schoolyears)
                ->with('semesters', $semesters)
                ->with('tracks', $tracks)
                ->with('strands', $strands)
                ->with('colleges', $colleges)
                ->with('courses', $courses);
                // ->with('students', $students);

        }
        elseif($id == 'getsections'){
            if($request->get('selectedgradelevel') == null || strtolower($request->get('selectedgradelevel')) == 'all')
            {
                return array();
            }else{
                $acadprogcode = DB::table('gradelevel')
                    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                    ->where('gradelevel.id', $request->get('selectedgradelevel'))
                    ->first()->acadprogcode;
    
                if(strtolower($acadprogcode) == 'college')
                {
                    $sections = DB::table('college_sections')
                        ->select('id', 'sectionDesc as sectionname')
                        ->where('yearID', $request->get('selectedgradelevel'))
                        ->where('deleted','0')
                        ->get();
                    return collect($sections);
                }else{
                    $sections = DB::table('sections')
                        ->select('sections.id', 'sections.sectionname')
                        ->join('sectiondetail','sections.id','=','sectiondetail.sectionid')
                        ->where('sections.levelid', $request->get('selectedgradelevel'))
                        ->where('sectiondetail.syid',$request->get('selectedschoolyear'))
                        ->where('sections.deleted','0')
                        ->where('sectiondetail.deleted','0')
                        ->get();
                    return collect($sections);
                }
            }
        }
        elseif($id == 'getgradelevels'){
            if($request->get('selectedacadprog') == 'basiced')
            {
                $gradelevels = DB::table('gradelevel')
                    ->select('id', 'levelname')
                    // ->where('acadprogid', $request->get('selectedacadprog'))
                    ->whereIn('acadprogid',[2,3,4,5])
                    ->where('deleted','0')
                    ->orderBy('sortid','asc')
                    ->get();
            }else{
                $gradelevels = DB::table('gradelevel')
                    ->select('id', 'levelname')
                    ->where('acadprogid', $request->get('selectedacadprog'))
                    ->where('deleted','0')
                    ->orderBy('sortid','asc')
                    ->get();
            }
            return collect($gradelevels);
        }
        elseif($id == 'getcourses'){
            $selectedcollege = $request->get('selectedcollege');
            if($selectedcollege == null)
            {
                $courses = DB::table('college_courses')
                            ->where('deleted','0')
                            ->orderBy('courseDesc','asc')
                            ->get();
            }else{
                $courses = DB::table('college_courses')
                            ->where('collegeid',$selectedcollege)
                            ->where('deleted','0')
                            ->orderBy('courseDesc','asc')
                            ->get();
            }

            return collect($courses);
        }
        elseif($id == 'updatesignatories'){
            // return $request->all();
            $formid = 'summaryofallstudents';
            $syid = $request->get('syid');
            $acadprogid = $request->get('acadprogid');
            $levelid = $request->get('levelid');
    
            $preparedby = $request->get('preparedby');
            $generatedby = $request->get('generatedby');

            // $dataid = $request->get('dataid');
            // $title = $request->get('title');
            // $name = $request->get('name');
            // $label = $request->get('label');
            $checkifexists = DB::table('signatory')
                ->where('form',$formid)
                ->where('title','Prepared by:')
                ->where('syid',$syid)
                ->where('acadprogid',$acadprogid)
                ->where('deleted',0)
                ->first();
            
            if($checkifexists)
            {
                DB::table('signatory')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'name'              => $preparedby,
                        'title'             => 'Prepared by:',
                        'description'       => '',
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);

            }else{
                $id = DB::table('signatory')
                    ->insertgetId([
                        'form'              => $formid,
                        'name'              => $preparedby,
                        'title'             => 'Prepared by:',
                        'description'       => '',
                        'syid'              => $syid,
                        'acadprogid'        => $acadprogid,
                        'deleted'           => 0,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
        
            }
            $checkifexists = DB::table('signatory')
                ->where('form',$formid)
                ->where('title','Generated by:')
                ->where('syid',$syid)
                ->where('acadprogid',$acadprogid)
                ->where('deleted',0)
                ->first();
            
            if($checkifexists)
            {
                DB::table('signatory')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'name'              => $generatedby,
                        'title'             => 'Generated by:',
                        'description'       => '',
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);

            }else{
                $id = DB::table('signatory')
                    ->insertgetId([
                        'form'              => $formid,
                        'name'              => $generatedby,
                        'title'             => 'Generated by:',
                        'description'       => '',
                        'syid'              => $syid,
                        'acadprogid'        => $acadprogid,
                        'deleted'           => 0,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
        
            }
            return 1;
        }elseif($id == 'getsignatories')
        {
            // return $request->all();
            $formid = 'summaryofallstudents';
            $preparedby = auth()->user()->name;
            $generatedby = auth()->user()->name;
            $checkifexists1 = DB::table('signatory')
                ->where('form',$formid)
                ->where('title','Prepared by:')
                ->where('syid',$request->get('syid'))
                ->where('deleted',0)
                ->first();

            if($checkifexists1)
            {
                $preparedby = $checkifexists1->name;
            }
            $checkifexists2 = DB::table('signatory')
                ->where('form',$formid)
                ->where('title','Generated by:')
                ->where('syid',$request->get('syid'))
                ->where('deleted',0)
                ->first();

            if($checkifexists2)
            {
                $generatedby = $checkifexists2->name;
            }
            return array(
                'preparedby'    => $preparedby,
                'generatedby'    => $generatedby
            );
        }
        else{
            
            $selectedschoolyear     = $request->get('selectedschoolyear');
            $selectedsemester       = $request->get('selectedsemester');
            $selectedacadprog       = $request->get('selectedacadprog');
            $selectedstudenttype    = $request->get('studenttype');
            $selectedstudentstatus  = $request->get('selectedstudentstatus');
            $selecteddate           = $request->get('selecteddate'); 
            $selectedgender         = $request->get('selectedgender'); 
            $selectedgradelevel     = $request->get('selectedgradelevel');
            $selectedsection        = $request->get('selectedsection');
            $trackid                = $request->get('trackid');
            $strandid               = $request->get('strandid');
            $selectedcollege        = $request->get('selectedcollege');
            $selectedcourse         = $request->get('selectedcourse');
            $selectedmode           = $request->get('selectedmode');
            $selectedgrantee        = $request->get('selectedgrantee');
                $teacherid = DB::table('teacher')
                ->where('tid',auth()->user()->email)
                ->select('id')
                ->first()
                ->id;

                $teacheradprogid = DB::table('teacheracadprog')
                    ->where('teacherid',$teacherid)
                    ->where('syid',$selectedschoolyear)
                    ->whereIn('acadprogutype',[3,8])
                    ->where('deleted',0)
                    ->get();
                    
                $isjs = collect($teacheradprogid)->where('acadprogid',4)->count() > 0 ? true :false;
                $issh = collect($teacheradprogid)->where('acadprogid',5)->count() > 0 ? true :false;
                $iscollege = collect($teacheradprogid)->where('acadprogid',6)->count() > 0 ? true :false;
                $isgs = collect($teacheradprogid)->where('acadprogid',3)->count() > 0 ? true :false;
                $isps = collect($teacheradprogid)->where('acadprogid',3)->count() > 0 ? true :false;

                $acadprogs = [];
                $acadpoverall = 0;
                if($isjs)
                {
                    array_push($acadprogs ,4);
                }
                if($issh)
                {
                    array_push($acadprogs ,5);
                }
                if($iscollege)
                {
                    array_push($acadprogs ,6);
                }
                if($isgs)
                {
                    array_push($acadprogs ,3);
                }
                if($isps)
                {
                    array_push($acadprogs ,3);
                }

            if($selectedstudentstatus == '' || $selectedstudentstatus == null || $selectedstudentstatus == 0)
            {
                $selectedstudentstatus = [1,2,4];
            }
            
            $formid = 'summaryofallstudents';
            $preparedby = auth()->user()->name;
            $generatedby = auth()->user()->name;
            $checkifexists1 = DB::table('signatory')
                ->where('form',$formid)
                ->where('title','Prepared by:')
                ->where('syid',$selectedschoolyear)
                ->where('deleted',0)
                ->first();

            if($checkifexists1)
            {
                $preparedby = $checkifexists1->name;
            }
            $checkifexists2 = DB::table('signatory')
                ->where('form',$formid)
                ->where('title','Generated by:')
                ->where('syid',$selectedschoolyear)
                ->where('deleted',0)
                ->first();

            if($checkifexists2)
            {
                $generatedby = $checkifexists2->name;
            }

            function getDatesFromRange($start, $end, $format = 'Y-m-d') {
            
                // Declare an empty array
                $array = array();
            
                // Variable that store the date interval
                // of period 1 day
                $interval = new DateInterval('P1D');
        
                $realEnd = new DateTime($end);
                $realEnd->add($interval);
        
                $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
        
                // Use loop to store date into array
                foreach($period as $date) {                 
                $array[] = $date->format($format); 
                }
        
                // Return the array elements
                return $array;
            }
            
                // Function call with passing the start date and end date
            $dates = array();
            if($selecteddate != null)
            {
                $daterange = explode(' - ',$selecteddate);
                $dates = getDatesFromRange($daterange[0], $daterange[1]);
            }


            $enrolledstuds = DB::table('enrolledstud')
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
                    'gradelevel.id as levelid',
                    'gradelevel.levelname',
                    'enrolledstud.sectionid',
                    'sections.sectionname',
                    'enrolledstud.studstatus',
                    'studinfo.studstatus as studinfostatus',
                    'enrolledstud.studstatus as enrolledstudstatus',
                    'studinfo.studstatdate',
                    'studinfo.grantee',
                    'studinfo.mol as molid',
                    'studinfo.studtype',
                    'studinfo.pantawid',
                    'studentstatus.description as studentstatus',
                    'enrolledstud.dateenrolled',
                    'gradelevel.acadprogid',
                    'gradelevel.sortid',
                    'modeoflearning.description as mol'
                )
                
                ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                ->leftJoin('sections','enrolledstud.sectionid','=','sections.id')
                ->leftJoin('modeoflearning','studinfo.mol','=','modeoflearning.id')
                ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                ->where('studinfo.deleted','0')
                ->where('enrolledstud.deleted','0')
                ->where('enrolledstud.syid',$selectedschoolyear)
                ->where('gradelevel.acadprogid','!=','5')
                ->where('gradelevel.acadprogid','!=','6')
                ->where('enrolledstud.studstatus','!=','0')
                // ->where('enrolledstud.studstatus','!=','6')
                ->where('gradelevel.deleted','0')
                ->distinct()
                ->get();
                
                
            if(count($enrolledstuds) > 0){
        
                foreach($enrolledstuds as $enrolledstud){
        
                    $enrolledstud->fullname = $enrolledstud->lastname.', '.$enrolledstud->firstname.' '.$enrolledstud->middlename;
                    $enrolledstud->gender = strtoupper($enrolledstud->gender);
                    $enrolledstud->dob = $enrolledstud->dob == null ? ' ' : date('m/d/Y', strtotime($enrolledstud->dob));
                    $enrolledstud->trackid = null;

                    $enrolledstud->strandid = null;

                    if($enrolledstud->middlename === null){
                        $enrolledstud->middlename = "";
                    }

                    if($enrolledstud->suffix === null){
                        $enrolledstud->suffix = "";
                    }
                    if($enrolledstud->mol === null){
                        $enrolledstud->mol = "";
                    }
                    if($enrolledstud->sectionname === null){
                        $enrolledstud->sectionname = "";
                    }

                    $enrolledstud->strandname = "";
                    $enrolledstud->trackname = "";
                    $enrolledstud->strandcode = "";
                    $enrolledstud->collegeid = "";
                    $enrolledstud->courseid = "";

                    $enrolledstud->semid = $selectedsemester;
        
                }
        
            }
            
            $shenrolledstuds = DB::table('sh_enrolledstud')
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
                    'sh_enrolledstud.studstatus',
                    'studinfo.studstatus as studinfostatus',
                    'sh_enrolledstud.studstatus as enrolledstudstatus',
                    'studinfo.studstatdate',
                    'studinfo.grantee',
                    'studinfo.mol as molid',
                    'studinfo.studtype',
                    'studinfo.pantawid',
                    'studentstatus.description as studentstatus',
                    'sh_enrolledstud.dateenrolled',
                    'sh_enrolledstud.strandid',
                    'sh_enrolledstud.sectionid',
                    'sections.sectionname',
                    'sh_strand.trackid',
                    'sh_strand.strandcode',
                    'sh_track.trackname',
                    'sh_strand.strandname',
                    'sh_enrolledstud.semid',
                    'gradelevel.id as levelid',
                    'gradelevel.acadprogid',
                    'gradelevel.sortid',
                    'gradelevel.levelname',
                    'modeoflearning.description as mol'
                )
                ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                ->leftJoin('modeoflearning','studinfo.mol','=','modeoflearning.id')
                ->join('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                ->leftJoin('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                ->leftJoin('sh_track','sh_strand.trackid','=','sh_track.id')
                ->leftJoin('sections','sh_enrolledstud.sectionid','=','sections.id')
                ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                ->where('studinfo.deleted','0')
                ->where('sh_enrolledstud.deleted','0')
                ->where('sh_enrolledstud.studstatus','!=','0')
                // ->where('sh_enrolledstud.studstatus','!=','6')
                ->where('gradelevel.deleted','0')
                ->where('sh_enrolledstud.syid',$selectedschoolyear)
                ->where('sh_enrolledstud.semid',$selectedsemester)
                ->distinct()
                ->get();
                    
            if(count($shenrolledstuds) > 0){
        
                foreach($shenrolledstuds as $shenrolledstud){
                    $shenrolledstud->fullname = $shenrolledstud->lastname.', '.$shenrolledstud->firstname.' '.$shenrolledstud->middlename;

                    $shenrolledstud->gender = strtoupper($shenrolledstud->gender);
                    $shenrolledstud->dob = $shenrolledstud->dob == null ? ' ' : date('m/d/Y', strtotime($shenrolledstud->dob));
                    if($shenrolledstud->middlename == null){
                        $shenrolledstud->middlename = "";
                    }

                    if($shenrolledstud->suffix == null){
                        $shenrolledstud->suffix = "";
                    }
                    if($shenrolledstud->mol == null){
                        $shenrolledstud->mol = "";
                    }
                    if($shenrolledstud->sectionname == null){
                        $shenrolledstud->sectionname = "";
                    }
                    $shenrolledstud->collegeid = "";
                    $shenrolledstud->courseid = "";
        
                }
        
            }
            $coenrolledstuds = DB::table('college_enrolledstud')
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
                    'college_enrolledstud.studstatus',
                    'studinfo.studstatus as studinfostatus',
                    'college_enrolledstud.studstatus as enrolledstudstatus',
                    'studinfo.studstatdate',
                    'studinfo.grantee',
                    'studinfo.mol as molid',
                    'studinfo.studtype',
                    'studinfo.pantawid',
                    'studentstatus.description as studentstatus',
                    DB::raw('DATE(college_enrolledstud.date_enrolled) as dateenrolled'),
                    'college_enrolledstud.sectionid',
                    'college_sections.sectionDesc as sectionname',
                    'gradelevel.id as levelid',
                    'gradelevel.acadprogid',
                    'gradelevel.sortid',
                    'gradelevel.levelname',
                    'modeoflearning.description as mol',
                    'college_enrolledstud.semid',
                    'college_enrolledstud.courseid',
                    'college_colleges.id as collegeid',
                    'college_colleges.collegeDesc as collegename',
                    'college_courses.courseabrv as coursename',
                    'college_courses.courseDesc as course',
                    'college_year.id as yearid'
                )
                ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
                ->leftJoin('modeoflearning','studinfo.mol','=','modeoflearning.id')
                ->leftJoin('studentstatus','college_enrolledstud.studstatus','=','studentstatus.id')
                ->leftJoin('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
                ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                ->leftJoin('college_colleges','college_courses.collegeid','=','college_colleges.id')
                ->leftJoin('college_year','college_enrolledstud.yearLevel','=','college_year.levelid')
                ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
                ->where('studinfo.deleted','0')
                ->where('gradelevel.deleted','0')
                ->where('college_enrolledstud.deleted','0')
                ->where('college_enrolledstud.studstatus','!=','0')
                // ->where('college_enrolledstud.studstatus','!=','6')
                ->where('college_enrolledstud.syid',$selectedschoolyear)
                ->where('college_enrolledstud.semid',$selectedsemester)
                ->distinct()
                ->get();

            if(count($coenrolledstuds) > 0){
        
                foreach($coenrolledstuds as $coenrolledstud){
                    $coenrolledstud->fullname = $coenrolledstud->lastname.', '.$coenrolledstud->firstname.' '.$coenrolledstud->middlename;

                    $coenrolledstud->gender = strtoupper($coenrolledstud->gender);
                    $coenrolledstud->dob = $coenrolledstud->dob == null ? ' ' : date('m/d/Y', strtotime($coenrolledstud->dob));
                    if($coenrolledstud->middlename === null){
                        $coenrolledstud->middlename = "";
                    }

                    if($coenrolledstud->suffix === null){
                        $coenrolledstud->suffix = "";
                    }
                    if($coenrolledstud->mol === null){
                        $coenrolledstud->mol = "";
                    }
                    if($coenrolledstud->sectionname === null){
                        $coenrolledstud->sectionname = "";
                    }
                    $coenrolledstud->strandname = $coenrolledstud->coursename ;
                    $coenrolledstud->trackname = $coenrolledstud->collegename ;
                    $coenrolledstud->strandcode = $coenrolledstud->coursename;
                    $coenrolledstud->strandid = 0;
                    $coenrolledstud->trackid = 0;
        
                }
        
            }
            
            $enrolledstudents = collect();
            $enrolledstudents = $enrolledstudents->merge($enrolledstuds);
            $enrolledstudents = $enrolledstudents->merge($shenrolledstuds);
            $enrolledstudents = $enrolledstudents->merge($coenrolledstuds);
            // return $enrolledstudents;
            if(!strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
            {
                $enrolledstudents = $enrolledstudents->where('enrolledstudstatus','!=','6')->values();
            }
            $enrolledstudents = $enrolledstudents->unique('id');
            $filteredstudents = $enrolledstudents->sortBy('fullname')->values()->all();
            // return $selectedstudenttype;
            if($selectedstudenttype != '0')
            {
                $filteredstudents = collect($filteredstudents)->where('studtype', $selectedstudenttype)->values()->all();
            }
            // return count($filteredstudents);
            // if($selectedstudenttype=='old')
            // {
            //     $filteredstudents = collect($filteredstudents)->where('studtype', $selectedstudenttype)->values();
            // }elseif($selectedstudenttype=='new'){
            //     $filteredstudents = collect($filteredstudents)->where('studtype', '!=','old')->values();
            // }

            
            if($selectedacadprog!='0')
            {
                if($selectedacadprog=='basiced')
                {
                    $filteredstudents = collect($filteredstudents)->whereIn('acadprogid', [2,3,4,5])->values();
                }else{
                    $filteredstudents = collect($filteredstudents)->where('acadprogid', $selectedacadprog)->values();
                }
            }
            
            if($selectedgradelevel>0)
            {
                $filteredstudents = collect($filteredstudents)->where('levelid', $selectedgradelevel)->values();
            }
            // return $filteredstudents->pluck('levelid');
            if($selectedsection>0)
            {
                $filteredstudents = collect($filteredstudents)->where('sectionid', $selectedsection)->values();
            }
            if($trackid>0)
            {
                $filteredstudents = collect($filteredstudents)->where('trackid', $trackid)->values();
            }
            if($strandid>0)
            {
                $filteredstudents = collect($filteredstudents)->where('strandid', $strandid)->values();
            }
            if($selectedcollege>0)
            {
                $filteredstudents = collect($filteredstudents)->where('collegeid', $selectedcollege)->values();
            }
            if($selectedcourse>0)
            {
                $filteredstudents = collect($filteredstudents)->where('courseid', $selectedcourse)->values();
            }
            
            if(!is_null($selectedstudentstatus)){
                $filteredstudents = collect($filteredstudents)->whereIn('studstatus',$selectedstudentstatus)->values();
                // $filteredstudentsarray = array();
                // return $selectedstudentstatus;
                // foreach($filteredstudents as $filteredstudent)
                // {
                    
                //     if(in_array($filteredstudent->studstatus,$selectedstudentstatus)){
                        
                //         if(in_array($filteredstudent,$filteredstudentsarray))
                //         {
                            
                //         }
                //         else{
                //             array_push($filteredstudentsarray, $filteredstudent);
                //         }
                //     }
                // }
                
                // $filteredstudents = $filteredstudentsarray;                
            }else{
                $filteredstudents = collect($filteredstudents)->whereIn('studstatus',[1,2,4])->values();
            }
            if($selectedgender != '0')
            {
                $filteredstudents = collect($filteredstudents)->where('gender', strtoupper($selectedgender))->values();
            }
            if($selectedmode != '0')
            {
                if($selectedmode == 'unspecified')
                {
                    $filteredstudents = collect($filteredstudents)->where('molid', null)->values();
                }else{
                    $filteredstudents = collect($filteredstudents)->where('molid', $selectedmode)->values();
                }
            }
            

            if($selectedgrantee != '0')
            {
                $filteredstudents = collect($filteredstudents)->where('grantee', $selectedgrantee)->values();
            }
            // return $dates;
            // return count($dates);
            if(count($dates)>0)
            {
                // return collect($filteredstudents)->where('dateenrolled','2022-02-17');
                $filteredstudents = collect($filteredstudents)->whereIn('dateenrolled', $dates)->values();
            }
            // return count($filteredstudents);
            // if($selecteddate != null && $selecteddate!=''){
                
            //     $selecteddate = explode(' - ', $selecteddate);

            //     $filteredstudents = collect($filteredstudents)->whereBetween('dateenrolled', [$selecteddate[0], $selecteddate[1]])->values();

            // }

            

            if($selectedcollege == 0)
            {
                $colleges = DB::table('college_colleges')
                            ->where('deleted','0')
                            ->orderBy('collegeDesc','asc')
                            ->get();

                $courses = DB::table('college_courses')
                            ->where('collegeid','!=',0)
                            ->where('deleted','0')
                            ->orderBy('courseDesc','asc')
                            ->get();
            }else{
                $colleges = DB::table('college_colleges')
                            ->where('id', $selectedcollege)
                            ->where('deleted','0')
                            ->orderBy('collegeDesc','asc')
                            ->get();

                $courses = DB::table('college_courses')
                            ->where('collegeid',$selectedcollege)
                            ->where('deleted','0')
                            ->orderBy('courseDesc','asc')
                            ->get();
            }

            $colleges = collect($colleges)->push((object)[
                'id'       => null,
                'collegeDesc'       => 'Not Specified',
                'dean'       => '0',
                'deleted'       => '0',
                'collegeabrv'       => 'N/S',
            ]);
            $courses = collect($courses)->push((object)[
                'id'       => null,
                'collegeid'       => null,
                'courseDesc'       => 'Not Specified',
                'deleted'       => '0',
                'courseChairman'       => '0',
                'courseabrv'       => 'N/S',
            ]);

            if($selectedcourse != 0)
            {

                $courses = collect($courses)->where('id', $selectedcourse)->all();

            }
            if(count($courses)>0)
            {
                foreach($courses as $course)
                {
                    $course->firstm         = collect($coenrolledstuds)->where('gender','MALE')->where('yearid','1')->where('courseid',$course->id)->count();
                    $course->firstf         = collect($coenrolledstuds)->where('gender','FEMALE')->where('yearid','1')->where('courseid',$course->id)->count();
                    $course->firsttotal     = collect($coenrolledstuds)->where('yearid','1')->where('courseid',$course->id)->count();

                    $course->secondm         = collect($coenrolledstuds)->where('gender','MALE')->where('yearid','2')->where('courseid',$course->id)->count();
                    $course->secondf         = collect($coenrolledstuds)->where('gender','FEMALE')->where('yearid','2')->where('courseid',$course->id)->count();
                    $course->secondtotal     = collect($coenrolledstuds)->where('yearid','2')->where('courseid',$course->id)->count();

                    $course->thirdm         = collect($coenrolledstuds)->where('gender','MALE')->where('yearid','3')->where('courseid',$course->id)->count();
                    $course->thirdf         = collect($coenrolledstuds)->where('gender','FEMALE')->where('yearid','3')->where('courseid',$course->id)->count();
                    $course->thirdtotal     = collect($coenrolledstuds)->where('yearid','3')->where('courseid',$course->id)->count();

                    $course->fourthm         = collect($coenrolledstuds)->where('gender','MALE')->where('yearid','4')->where('courseid',$course->id)->count();
                    $course->fourthf         = collect($coenrolledstuds)->where('gender','FEMALE')->where('yearid','4')->where('courseid',$course->id)->count();
                    $course->fourthtotal     = collect($coenrolledstuds)->where('yearid','4')->where('courseid',$course->id)->count();
                }
            }
            // return $courses;

            if(count($colleges)>0)
            {
                foreach($colleges as $college)
                {
                    $college->total = collect($courses)->where('collegeid', $college->id)->sum('firsttotal')+collect($courses)->where('collegeid', $college->id)->sum('secondtotal')+collect($courses)->where('collegeid', $college->id)->sum('thirdtotal')+collect($courses)->where('collegeid', $college->id)->sum('fourthtotal');
                }
            }            
            // return $colleges;
            $records = array();    

            if($trackid == 0)
            {
                $bytracks = DB::table('sh_track')
                            ->where('deleted','0')
                            ->orderBy('trackname','asc')
                            ->get();
            }else{
                $bytracks = DB::table('sh_track')
                            ->where('id',$trackid)
                            ->where('deleted','0')
                            ->orderBy('trackname','asc')
                            ->get();
            }
                 
            // return count($filteredstudents);       
            if(count($bytracks)>0)
            {
                foreach($bytracks as $eachtrack)
                {
                    $eachtrack->countmale = collect($filteredstudents)->where('gender','MALE')->where('trackid', $eachtrack->id)->count();
                    $eachtrack->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('trackid', $eachtrack->id)->count();
                    $eachtrack->total = collect($filteredstudents)->where('gender','MALE')->where('trackid', $eachtrack->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('trackid', $eachtrack->id)->count();
                }
            }
            if($strandid == 0)
            {
                if($trackid == 0)
                {
                    $bystrands = DB::table('sh_strand')
                                ->where('trackid','!=',0)
                                ->where('deleted','0')
                                ->orderBy('strandcode','asc')
                                ->get();
                }else{
                    $bystrands = DB::table('sh_strand')
                                ->where('trackid',$trackid)
                                ->where('deleted','0')
                                ->orderBy('strandcode','asc')
                                ->get();
                }
            }else{
                if($trackid == 0)
                {
                    $bystrands = DB::table('sh_strand')
                                ->where('id',$strandid)
                                ->where('deleted','0')
                                ->orderBy('strandcode','asc')
                                ->get();
                }else{
                    $bystrands = DB::table('sh_strand')
                                ->where('id',$strandid)
                                ->where('trackid',$trackid)
                                ->where('deleted','0')
                                ->orderBy('strandcode','asc')
                                ->get();
                }
            }
                        
            if(count($bystrands)>0)
            {
                foreach($bystrands as $eachstrand)
                {
                    $eachstrand->countmale = collect($filteredstudents)->where('gender','MALE')->where('strandid', $eachstrand->id)->count();
                    $eachstrand->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('strandid', $eachstrand->id)->count();
                    $eachstrand->total = collect($filteredstudents)->where('gender','MALE')->where('strandid', $eachstrand->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('strandid', $eachstrand->id)->count();
                }
            }        
            if($selectedcollege == 0)
            {
                $bycolleges = DB::table('college_colleges')
                            ->where('deleted','0')
                            ->orderBy('collegeDesc','asc')
                            ->get();
            }else{
                $bycolleges = DB::table('college_colleges')
                            ->where('id',$selectedcollege)
                            ->where('deleted','0')
                            ->orderBy('collegeDesc','asc')
                            ->get();
            }
               

            $bycolleges = collect($bycolleges)->push((object)[
                'id'       => null,
                'collegeDesc'       => 'Not Specified',
                'dean'       => '0',
                'deleted'       => '0',
                'collegeabrv'       => 'N/S',
            ]);
            
            if(count($bycolleges)>0)
            {
                foreach($bycolleges as $eachcollege)
                {
                    $eachcollege->countmale = collect($coenrolledstuds)->where('gender','MALE')->where('collegeid', $eachcollege->id)->count();
                    $eachcollege->countfemale = collect($coenrolledstuds)->where('gender','FEMALE')->where('collegeid', $eachcollege->id)->count();
                    $eachcollege->total = collect($coenrolledstuds)->where('gender','MALE')->where('collegeid', $eachcollege->id)->count()+collect($coenrolledstuds)->where('gender','FEMALE')->where('collegeid', $eachcollege->id)->count();
                }
            }
            if($selectedcourse == 0)
            {
                if($selectedcollege == 0)
                {
                    $bycourses = DB::table('college_courses')
                                ->where('collegeid','!=',0)
                                ->where('deleted','0')
                                ->orderBy('courseDesc','asc')
                                ->get();
                }else{
                    $bycourses = DB::table('college_courses')
                                ->where('collegeid',$selectedcollege)
                                ->where('deleted','0')
                                ->orderBy('courseDesc','asc')
                                ->get();
                }
            }else{
                if($selectedcollege == 0)
                {
                    $bycourses = DB::table('college_courses')
                                ->where('id',$selectedcourse)
                                ->where('collegeid','!=',0)
                                ->where('deleted','0')
                                ->orderBy('courseDesc','asc')
                                ->get();
                }else{
                    $bycourses = DB::table('college_courses')
                                ->where('id',$selectedcourse)
                                ->where('collegeid',$selectedcollege)
                                ->where('deleted','0')
                                ->orderBy('courseDesc','asc')
                                ->get();
                }
            }
            $bycourses = collect($bycourses)->push((object)[
                'id'       => null,
                'collegeid'       => null,
                'courseDesc'       => 'Not Specified',
                'deleted'       => '0',
                'courseChairman'       => '0',
                'courseabrv'       => 'N/S',
            ]);
            
            if(count($bycourses)>0)
            {
                foreach($bycourses as $eachcourse)
                {
                    $eachcourse->countmale = collect($coenrolledstuds)->where('gender','MALE')->where('courseid', $eachcourse->id)->count();
                    $eachcourse->countfemale = collect($coenrolledstuds)->where('gender','FEMALE')->where('courseid', $eachcourse->id)->count();
                    $eachcourse->total = collect($coenrolledstuds)->where('gender','MALE')->where('courseid', $eachcourse->id)->count()+collect($coenrolledstuds)->where('gender','FEMALE')->where('courseid', $eachcourse->id)->count();
                }
            }

            if($selectedacadprog == 0 && $selectedgradelevel == 0)
            {  
                $bygradelevels = DB::table('gradelevel')
                    ->select('id','levelname', 'acadprogid')
                    ->where('deleted','0')
                    ->orderBy('sortid','asc')
                    ->get();

                if(count($acadprogs)>0)
                {
                    $bygradelevels = collect($bygradelevels)->whereIn('acadprogid', $acadprogs)->values()->all();
                }

                if(count($bygradelevels)>0)
                {
                    foreach($bygradelevels as $eachlevel)
                    {
                        $eachlevel->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->count();
                        $eachlevel->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->count();
                        $eachlevel->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->count();
                        if($request->get('fourps') == 1)
                        {
                            $eachlevel->students = collect($filteredstudents)->where('levelid', $eachlevel->id)->where('pantawid', '1')->values();
                        }else{
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                            {
                                $eachlevel->students = collect($filteredstudents)->where('levelid', $eachlevel->id)->values();
                            }
                        }
                    }
                }

                $bysections = array();

                if(count($bygradelevels) > 0)
                {
                    foreach($bygradelevels as $eachlevel)
                    {
                        if($eachlevel->acadprogid == 6)
                        {
                            $getsections = DB::table('college_sections')
                                ->where('yearID', $eachlevel->id)
                                ->where('semesterID', $selectedsemester)
                                ->where('syID', $selectedschoolyear)
                                ->where('deleted','0')
                                ->get();

                            if($selectedcourse == null || $selectedcourse == 'ALL')
                            {
                                $getsections = array();
                            }else{
                                $getsections = collect($getsections)->where('courseID', $selectedcourse)->values();
                            }
                        }else{
                            $getsections = DB::table('sections')
                                ->where('levelid', $eachlevel->id)
                                ->where('deleted','0')
                                ->get();
                        }
                        if(count($getsections)>0)
                        {
                            foreach($getsections as $eachsection)
                            {
                                $eachsection->levelname = $eachlevel->levelname;
                                $eachsection->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                $eachsection->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                $eachsection->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                array_push($bysections, $eachsection);
                            }
                        }

                    }    
                }
                
                array_push($records, (object) array(
                    'gradelevels'       => $bygradelevels,
                    'tracks'            => $bytracks,
                    'strands'           => $bystrands,
                    'colleges'          => $bycolleges,
                    'courses'           => $bycourses,
                    'sections'          => $bysections,
                    'students'          => $filteredstudents
                ));
            }
            elseif($selectedacadprog == 'basiced')
            {  
                
                $bygradelevels = DB::table('gradelevel')
                    ->select('id','levelname', 'acadprogid')
                    ->where('deleted','0')
                    ->whereIn('acadprogid',[2,3,4,5])
                    ->orderBy('sortid','asc')
                    ->get();

                if(count($bygradelevels)>0)
                {
                    foreach($bygradelevels as $eachlevel)
                    {
                        $eachlevel->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->count();
                        $eachlevel->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->count();
                        $eachlevel->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->count();
                        if($request->get('fourps') == 1)
                        {
                            $eachlevel->students = collect($filteredstudents)->where('levelid', $eachlevel->id)->where('pantawid', '1')->values();
                        }else{
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                            {
                                $eachlevel->students = collect($filteredstudents)->where('levelid', $eachlevel->id)->values();
                            }
                        }
                    }
                }

                $bysections = array();

                if(count($bygradelevels) > 0)
                {
                    foreach($bygradelevels as $eachlevel)
                    {
                        if($eachlevel->acadprogid == 6)
                        {
                            $getsections = DB::table('college_sections')
                                ->where('yearID', $eachlevel->id)
                                ->where('semesterID', $selectedsemester)
                                ->where('syID', $selectedschoolyear)
                                ->where('deleted','0')
                                ->get();

                            if($selectedcourse == null || $selectedcourse == 'ALL')
                            {
                                $getsections = array();
                            }else{
                                $getsections = collect($getsections)->where('courseID', $selectedcourse)->values();
                            }
                        }else{
                            $getsections = DB::table('sections')
                                ->where('levelid', $eachlevel->id)
                                ->where('deleted','0')
                                ->get();
                        }
                        if(count($getsections)>0)
                        {
                            foreach($getsections as $eachsection)
                            {
                                $eachsection->levelname = $eachlevel->levelname;
                                $eachsection->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                $eachsection->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                $eachsection->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                array_push($bysections, $eachsection);
                            }
                        }

                    }    
                }
                
                array_push($records, (object) array(
                    'gradelevels'       => $bygradelevels,
                    'tracks'            => $bytracks,
                    'strands'           => $bystrands,
                    'colleges'          => $bycolleges,
                    'courses'           => $bycourses,
                    'sections'          => $bysections,
                    'students'          => $filteredstudents
                ));
            }
            elseif($selectedacadprog == 6)
            {
                $bygradelevels = DB::table('gradelevel')
                    ->select('id','levelname')
                    ->where('deleted','0')
                    ->where('acadprogid',$selectedacadprog)
                    ->orderBy('sortid','asc')
                    ->get();
                 
                if(count($bygradelevels)>0)
                {
                    foreach($bygradelevels as $eachlevel)
                    {
                        $eachlevel->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->count();
                        $eachlevel->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->count();
                        $eachlevel->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->count();
                        if($request->get('fourps') == 1)
                        {
                            $eachlevel->students = collect($filteredstudents)->where('levelid', $eachlevel->id)->where('pantawid', '1')->values();
                        }else{
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                            {
                                $eachlevel->students = collect($filteredstudents)->where('levelid', $eachlevel->id)->values();
                            }
                        }
                    }
                }
                

                $bysections = array();

                if(count($bygradelevels) > 0)
                {
                    foreach($bygradelevels as $eachlevel)
                    {
                        $getsections = DB::table('college_sections')
                            ->where('yearID', $eachlevel->id)
                            ->where('semesterID', $selectedsemester)
                            ->where('syID', $selectedschoolyear)
                            ->where('deleted','0')
                            ->get();

                        if($selectedcourse == null || $selectedcourse == 'ALL')
                        {
                            $getsections = array();
                        }else{
                            $getsections = collect($getsections)->where('courseID', $selectedcourse)->values();
                        }
                        if(count($getsections)>0)
                        {
                            foreach($getsections as $eachsection)
                            {
                                $eachsection->levelname = $eachlevel->levelname;
                                $eachsection->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                $eachsection->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                $eachsection->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                array_push($bysections, $eachsection);
                            }
                        }

                    }    
                }
                array_push($records, (object) array(
                    'gradelevels'       => $bygradelevels,
                    'tracks'            => array(),
                    'strands'           => array(),
                    'colleges'          => $bycolleges,
                    'courses'           => $bycourses,
                    'sections'          => $bysections,
                    'students'          => $filteredstudents
                ));
            }
            elseif($selectedacadprog == 5){

                $bygradelevels = DB::table('gradelevel')
                    ->select('id','levelname')
                    ->where('deleted','0')
                    ->where('acadprogid',$selectedacadprog)
                    ->orderBy('sortid','asc')
                    ->get();

                $bysections = array();

                if(count($bygradelevels)>0)
                {
                    foreach($bygradelevels as $eachlevel)
                    {
                        $eachlevelstrands  = DB::table('sh_strand')
                            ->select('sh_strand.id','sh_strand.strandname','sh_strand.strandcode','sh_track.trackname')
                            ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                            ->where('sh_strand.active','1')
                            ->where('sh_strand.deleted','0')
                            ->get();
                            

                        if(count($eachlevelstrands)>0)
                        {
                            foreach($eachlevelstrands as $eachstrand)
                            {
                                $eachstrand->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('strandid', $eachstrand->id)->count();
                                $eachstrand->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('strandid', $eachstrand->id)->count();
                                $eachstrand->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('strandid', $eachstrand->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('strandid', $eachstrand->id)->count();
                            }
                        }
                        $eachlevel->strands = $eachlevelstrands;
                        $eachlevel->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->count();
                        $eachlevel->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->count();
                        $eachlevel->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->count();
                        if($request->get('fourps') == 1)
                        {
                            $eachlevel->students = collect($filteredstudents)->where('levelid', $eachlevel->id)->where('pantawid', '1')->values();
                        }
                        else{
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                            {
                                $eachlevel->students = collect($filteredstudents)->where('levelid', $eachlevel->id)->values();
                            }
                        }
                        $getsections = DB::table('sections')
                        ->where('levelid', $eachlevel->id)
                        ->where('deleted','0')
                        ->get();

                        if(count($getsections)>0)
                        {
                            foreach($getsections as $eachsection)
                            {
                                $eachsection->levelname = $eachlevel->levelname;
                                $eachsection->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                $eachsection->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                $eachsection->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                array_push($bysections, $eachsection);
                            }
                        }
                    }
                }


                array_push($records, (object) array(
                    'gradelevels'       => $bygradelevels,
                    'tracks'            => $bytracks,
                    'strands'           => $bystrands,
                    'colleges'          => array(),
                    'courses'           => array(),
                    'sections'          => $bysections,
                    'students'          => $filteredstudents
                ));
                
            }else{
                $bysections = array();
                
                $bygradelevels = DB::table('gradelevel')
                    ->select('id','levelname')
                    ->where('deleted','0')
                    ->where('acadprogid',$selectedacadprog)
                    ->orderBy('sortid','asc')
                    ->get();
                
                if(count($bygradelevels)>0)
                {
                    foreach($bygradelevels as $eachlevel)
                    {
                        $eachlevel->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->count();
                        $eachlevel->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->count();
                        $eachlevel->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->count();
                        if($request->get('fourps') == 1)
                        {
                            $eachlevel->students = collect($filteredstudents)->where('levelid', $eachlevel->id)->where('pantawid', '1')->values();
                        }else{
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                            {
                                $eachlevel->students = collect($filteredstudents)->where('levelid', $eachlevel->id)->values();
                            }
                        }
                        $getsections = DB::table('sections')
                            ->where('levelid', $eachlevel->id)
                            ->where('deleted','0')
                            ->get();

                        if(count($getsections)>0)
                        {
                            foreach($getsections as $eachsection)
                            {
                                $eachsection->levelname = $eachlevel->levelname;
                                $eachsection->countmale = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                $eachsection->countfemale = collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                $eachsection->total = collect($filteredstudents)->where('gender','MALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count()+collect($filteredstudents)->where('gender','FEMALE')->where('levelid', $eachlevel->id)->where('sectionid', $eachsection->id)->count();
                                array_push($bysections, $eachsection);
                            }
                        }
                    }
                }                
                array_push($records, (object) array(
                    'gradelevels'       => $bygradelevels,
                    'tracks'            => $bytracks,
                    'strands'           => $bystrands,
                    'colleges'          => $bycolleges,
                    'courses'           => $bycourses,
                    'sections'          => $bysections,
                    'students'          => $filteredstudents
                ));
            }
            // return $filteredstudents;
            if($id == 'filter'){
                if($request->ajax())
                {
                    return $records;
                }
            }
            elseif($id == 'print'){

                $schoolinfo = Db::table('schoolinfo')
                    ->select(
                        'schoolinfo.schoolid',
                        'schoolinfo.schoolname',
                        'schoolinfo.authorized',
                        'refcitymun.citymunDesc as city',
                        'schoolinfo.district',
                        'schoolinfo.address',
                        'schoolinfo.picurl',
                        'refregion.regDesc as region'
                    )
                    ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
                    ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
                    ->first();

                $gradelevels = DB::table('gradelevel')
                        ->orderBy('sortid', 'asc')
                        ->where('deleted','0')
                        ->get();

                if($selectedgradelevel == '0')
                {
                    $acadprog = "";
                    $selectedsection = "ALL";
                }else{
                    $acadprog = DB::table('gradelevel')
                        ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                        ->where('gradelevel.id', $selectedgradelevel)
                        ->first()
                        ->acadprogcode;

                    if($request->get('selectedsection') == 0)
                    {
                        $selectedsection = "ALL";
                    }else{
                        if($selectedgradelevel>16)
                        {
                            $selectedsection = DB::table('college_sections')
                                ->where('id', $selectedsection)
                                ->where('yearID', $selectedgradelevel)
                                ->first()
                                ->sectionDesc;
                        }else{
                            $selectedsection = DB::table('sections')
                                ->where('id', $selectedsection)
                                ->where('levelid', $selectedgradelevel)
                                ->first()
                                ->sectionname;
                        }
                    }
                }
                
                $printoption = $request->get('printby');
                
                if($trackid == '0'){

                    $tracks = Db::table('sh_track')
                        ->where('deleted', '0')
                        ->get();

                    $trackname = "ALL";

                }else{

                    $tracks = [];

                    $trackname = Db::table('sh_track')
                    ->where('id', $trackid)
                    ->first()
                    ->trackname;
                }
                
                // if($trackid != 0){


                // }else{

                //     $trackname = "ALL";

                //     $tracks = [];

                // }
                if($selectedcollege!=0)
                {
                    $trackname = DB::table('college_colleges')
                        ->where('id', $selectedcollege)->first()->collegeDesc;
                }

                if($strandid != 0 ){

                    if($strandid == 'all'&& $strandid == '0'){

                        $strands = Db::table('sh_strand')
                            ->select(
                                'sh_strand.id',
                                'sh_strand.strandname',
                                'sh_track.id as trackid',
                                'sh_track.trackname'
                            )
                            ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                            ->where('sh_strand.active', '1')
                            ->where('sh_strand.deleted', '0')
                            ->where('sh_track.deleted', '0')
                            ->get();

                        $strandname = "ALL";

                    }else{

                        $strands = "ALL";

                        $strandname = Db::table('sh_strand')
                            ->where('id', $strandid)
                            ->first()
                            ->strandname;
                        

                    }

                }else{


                    $strands = DB::table('sh_strand')
                        ->where('deleted','0')
                        ->get();

                    $strandname = "ALL";

                }
                if($selectedcourse!=0)
                {
                    $strandname = DB::table('college_courses')
                        ->where('id', $selectedcourse)->first()->courseDesc;
                }
                
                if($selectedstudenttype == null){
                    $selectedstudenttype = 'ALL';

                }


                $sy = DB::table('sy')
                    ->where('id',$selectedschoolyear)
                    ->first();
                    
                if($selecteddate == null)
                {
                    $selecteddate = "NO SELECTED PERIOD";
                }else{
                    $selecteddatefrom   = date('M d,Y',strtotime($selecteddate[0]));
                    $selecteddateto     = date('M d,Y',strtotime($selecteddate[1]));
                    $selecteddate = $selecteddatefrom.' to '.$selecteddateto;
                }
                $shsbystrand = 0;
                if($selectedgradelevel == 14 || $selectedgradelevel == 15){
                    if($trackid == 0 && $strandid == 0){
                        $shsbystrand = 1;
                    }
                }
                
                if($selectedgender == 'male'){
                    $selectedgender = "(MALE)";
                }
                elseif($selectedgender == 'female'){
                    $selectedgender = "(FEMALE)";
                }else{
                    
                    $selectedgender = "ALL";
                }
                
                if($selectedstudentstatus == 'all'){
                    $selectedstudentstatus = "ALL";
                }
                elseif($selectedstudentstatus == ''){
                    $selectedstudentstatus = "ALL";
                }else{
                    $selectedstudentstatusarray = array();
                    foreach($selectedstudentstatus as $selectedstudentstat)
                    {
                        
                        $description = Db::table('studentstatus')
                        ->where('id', $selectedstudentstat)
                        ->first()
                        ->description;
                        array_push($selectedstudentstatusarray, $description);
                    }
                    $selectedstudentstatus = implode(" | ",$selectedstudentstatusarray);
                }
                
                if($selectedacadprog == 0){
                    $selectedacadprog = "ALL";
                }elseif($selectedacadprog == 'basiced'){
                    $selectedacadprog = "ALL BASIC EDUCATION  Programs";
                }else{
                    $selectedacadprog = Db::table('academicprogram')
                        ->where('id', $selectedacadprog)
                        ->first()
                        ->progname;
                }
                if($selectedgradelevel == 0){
                    $selectedgradelevel = "ALL";
                }else{
                    $selectedgradelevel = Db::table('gradelevel')
                        ->where('id', $selectedgradelevel)
                        ->first()
                        ->levelname;
                }
                if($selectedmode == 0)
                {
                    $selectedmode = "ALL";
                }
                elseif($selectedmode == 'unspecified')
                {
                    $selectedmode = 'UNSPECIFIED';
                }else{
                    
                    $selectedmode = DB::table('modeoflearning')
                        ->where('id', $selectedmode)
                        ->first()
                        ->description;
                }
                if($selectedgrantee == 0)
                {
                    $selectedgrantee = "ALL";
                }else{
                    $selectedgrantee = DB::table('grantee')
                        ->where('id', $selectedgrantee)
                        ->first()
                        ->description;
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
                    
                if($selectedsemester == 1)
                {
                    $semester = 'First';
                }
                elseif($selectedsemester == 2)
                {
                    $semester = 'Second';
                }
                if($request->get('selectedacadprog') == 0)
                {
                    $descacad = 'All Programs';
                }
                elseif($request->get('selectedacadprog') == 'All Basic Education Programs')
                {
                    $descacad = 'All Programs';
                }else{
                    $descacad = DB::table('academicprogram')
                        ->where('id',$request->get('selectedacadprog'))
                        ->first()->progname.' Program';
                }
                if($selectedstudenttype == 0)
                {
                    $selectedstudenttype = 'ALL';
                }
                // return $selectedstudenttype;
                if($request->get('exporttype') == 'excel')
                { 
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
                    $sheet = $spreadsheet->getActiveSheet();
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
                    $font_bold = [
                            'font' => [
                                'bold' => true,
                            ]
                        ];

                        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $drawing->setWorksheet($sheet);
                        $drawing->setName('Logo');
                        $drawing->setDescription('Logo');
                        if (strpos(DB::table('schoolinfo')->first()->picurl, '?') !== false)
                        {
                            $drawing->setPath(base_path().'/public/'.substr(DB::table('schoolinfo')->first()->picurl, 0, strpos(DB::table('schoolinfo')->first()->picurl, "?")));
                        }else{
                            $drawing->setPath(base_path().'/public/'.DB::table('schoolinfo')->first()->picurl);
                        }
                        $drawing->setHeight(80);
                        $drawing->setCoordinates('A3');
                        $drawing->setOffsetX(5);
                        
                    $sheet->mergeCells('A2:G2');
                    $sheet->setCellValue('A2', $schoolinfo->schoolname);
                    
                    $sheet->mergeCells('A3:G3');
                    $sheet->setCellValue('A3', $schoolinfo->address);

                    $sheet->mergeCells('A4:G4');
                    $sheet->setCellValue('A4', 'Office of the Registrar');

                    $sheet->getStyle('A2:G8')->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('A2:G2')->getFont()->setBold(true);

                    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                    {
                        $sheet->mergeCells('A5:G5');
                        $sheet->setCellValue('A5', 'SUMMARY OF ENROLMENT');
                    }
                    $sheet->mergeCells('A7:G7');
                    $sheet->setCellValue('A7', $semester.' Semester, School Year '.$sy->sydesc);

                    $sheet->mergeCells('A8:G8');
                    $sheet->setCellValue('A8', $descacad);
					
					 if($selectedacadprog == 'COLLEGE'){
                        
                        $tempsemid = $request->get('selectedsemester');
                        $enrolledunits = Db::Table('college_studsched')
                                            ->join('college_classsched',function($join) use($sy,$tempsemid){
                                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                                $join->where('college_classsched.deleted',0);
                                                $join->where('college_classsched.syid',$sy->id);
                                                $join->where('college_classsched.semesterID',$tempsemid);
                                            })
                                             ->join('college_prospectus',function($join){
                                                $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                                $join->where('college_prospectus.deleted',0);
                                            })
                                            ->where('college_studsched.deleted',0)
                                            ->whereIn('college_studsched.schedstatus',['REGULAR','ADDITIONAL'])
                                            ->groupBy('studid')
                                            ->select(
                                                DB::raw('SUM(lecunits + labunits) as totalunits'),
                                                'lecunits',
                                                'labunits',
                                                'studid'
                                            )
                                            ->get();
                                            
                    }

                    if($request->get('layout') == 'student')
                    {
                        $sheet->getColumnDimension('A')->setAutoSize(true);
                        $sheet->getColumnDimension('B')->setAutoSize(true);
                        $sheet->getColumnDimension('C')->setAutoSize(true);
                        $sheet->getColumnDimension('D')->setAutoSize(true);
                        $sheet->getColumnDimension('E')->setAutoSize(true);
                        $sheet->getColumnDimension('F')->setAutoSize(true);
                        $sheet->getColumnDimension('G')->setAutoSize(true);
                        
                        $sheet->mergeCells('A9:B9');
                        $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                        $sheet->mergeCells('C9:D9');
                        $sheet->setCellValue('C9', 'COLLEGE/TRACK : '.$trackname);
                        $sheet->mergeCells('F9:G9');
                        $sheet->setCellValue('F9', 'GENDER : '.$selectedgender);
    
                        $sheet->mergeCells('A10:B10');
                        $sheet->setCellValue('A10', 'DEPARTMENT : '.$selectedacadprog);
                        $sheet->mergeCells('C10:D10');
                        $sheet->setCellValue('C10', 'COURSE/STRAND : '.$strandname);
                        $sheet->mergeCells('F10:G10');
                        $sheet->setCellValue('F10', 'GRANTEE : '.$selectedgrantee);
                        
                        $sheet->mergeCells('A11:B11');
                        $sheet->setCellValue('A11', 'GRADE LEVEL : '.$selectedgradelevel);
                        $sheet->mergeCells('C11:D11');
                        $sheet->setCellValue('C11', 'ADMISSION STATUS : '.$selectedstudentstatus);
                        $sheet->mergeCells('F11:G11');
                        $sheet->setCellValue('F11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                        
                        $sheet->mergeCells('A12:B12');
                        $sheet->setCellValue('A12', 'SECTION : '.$selectedsection);
                        $sheet->mergeCells('C12:D12');
                        $sheet->setCellValue('C12', 'MOL : '.$selectedmode);
                        $sheet->mergeCells('F12:G12');
                        $sheet->setCellValue('F12', 'DATE PERIOD : '.$selecteddate);

                        $startcellno = 14;
                            
                        $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->getAlignment()->setHorizontal('center');

                        $sheet->setCellValue('A'.$startcellno, '#');
                        $sheet->setCellValue('B'.$startcellno, 'LRN');
                        $sheet->setCellValue('C'.$startcellno, 'Name');
                        $sheet->setCellValue('D'.$startcellno, 'Gender');
                        $sheet->setCellValue('E'.$startcellno, 'DOB');
                        $sheet->setCellValue('F'.$startcellno, 'Grade Level');
                        $sheet->setCellValue('G'.$startcellno, 'Section');
                        $sheet->setCellValue('H'.$startcellno, 'College/Track');
                        $sheet->setCellValue('I'.$startcellno, 'Course/Strand');
                        $sheet->setCellValue('J'.$startcellno, 'MOL'); // Adjust this column index as needed
                        $sheet ->getStyle('B'.$startcellno.':J'.$startcellno)->applyFromArray($border);

                        $startcellno+=1;
                        if(count($filteredstudents)>0)
                        {
                            foreach($filteredstudents as $studentkey => $filteredstudent)
                            {
                                $sheet->setCellValue('A'.$startcellno, $studentkey+1);
                                $sheet->setCellValueExplicit('B'.$startcellno, $filteredstudent->lrn, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                                $sheet->setCellValue('C'.$startcellno, $filteredstudent->lastname . ', ' . $filteredstudent->firstname . ' ' . $filteredstudent->suffix . ' ' . $filteredstudent->middlename); // Name in column C
                                $sheet->setCellValue('D'.$startcellno, strtoupper($filteredstudent->gender));
                                $sheet->setCellValue('E'.$startcellno, $filteredstudent->dob);
                                $sheet->setCellValue('F'.$startcellno, $filteredstudent->levelname);
                                $sheet->setCellValue('G'.$startcellno, $filteredstudent->sectionname);
                                $sheet->setCellValue('H'.$startcellno, $filteredstudent->trackname);
                                $sheet->setCellValue('I'.$startcellno, $filteredstudent->strandname);
                                $sheet->setCellValue('J'.$startcellno, $filteredstudent->mol); // Adjust this column index as needed
                                $sheet->getStyle('B'.$startcellno.':J'.$startcellno)->applyFromArray($border); // Adjust style range
                        
                                $startcellno+=1;
                            }
                        }    
                        $startcellno+=2;
                        $sheet->mergeCells('G'.$startcellno.':I'.$startcellno);
                        $sheet->setCellValue('G'.$startcellno, 'Prepared by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('G'.$startcellno.':I'.$startcellno);
                        $sheet->setCellValue('G'.$startcellno, $preparedby);
                        $sheet->getStyle('G'.$startcellno.':I'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('G'.$startcellno.':I'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        $startcellno+=2;
                        $sheet->mergeCells('G'.$startcellno.':I'.$startcellno);
                        $sheet->setCellValue('G'.$startcellno, 'Generated by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('G'.$startcellno.':I'.$startcellno);
                        $sheet->setCellValue('G'.$startcellno, $generatedby);
                        $sheet->getStyle('G'.$startcellno.':I'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('G'.$startcellno.':I'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    }
                    elseif($request->get('layout') == 'studdirectoryexcel')
                    {
                        $inputFileType = 'Xlsx';
                        $inputFileName = base_path().'/public/excelformats/dcc/studentdirectory.xlsx';
                        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                        $reader->setLoadAllSheets();
                        $spreadsheet = $reader->load($inputFileName);
                        

                        $sheet = $spreadsheet->getSheet(0);

                        $sheet->setCellValue('A2', DB::table('schoolinfo')->first()->schoolname);
                        $sheet->setCellValue('A3', DB::table('schoolinfo')->first()->address);
                        $sheet->setCellValue('A7', $semester.' Semester, School Year '.$sy->sydesc);
                        $startcellno = 10;;
                        if(count($filteredstudents)>0)
                        {
                            foreach($filteredstudents as $studentkey => $filteredstudent)
                            {
                                $eachstudinfo = DB::table('studinfo')
                                    ->where('id', $filteredstudent->id)
                                    ->first();
                                $studaddress = '';
                                $studaddress .= $eachstudinfo->street != null ? $eachstudinfo->street.' ' : '';
                                $studaddress .= $eachstudinfo->barangay != null ? $eachstudinfo->barangay.' ' : '';
                                $studaddress .= $eachstudinfo->city != null ? $eachstudinfo->city.' ' : '';
                                $studaddress .= $eachstudinfo->province != null ? $eachstudinfo->province.' ' : '';

                                $filteredstudent->contactno = $eachstudinfo->contactno;
                                $filteredstudent->address = $studaddress;
                                $filteredstudent->fathername = $eachstudinfo->fathername;
                                $filteredstudent->fcontactno = $eachstudinfo->fcontactno;
                                $filteredstudent->mothername = $eachstudinfo->mothername;
                                $filteredstudent->mcontactno = $eachstudinfo->mcontactno;
                                $filteredstudent->guardianname = $eachstudinfo->guardianname;
                                $filteredstudent->gcontactno = $eachstudinfo->gcontactno;

                                $sheet->setCellValue('A'.$startcellno, $studentkey+1);
                                $sheet->setCellValue('B'.$startcellno, $filteredstudent->lastname.', '.$filteredstudent->firstname.' '.$filteredstudent->suffix.' '.$filteredstudent->middlename);
                                $sheet->setCellValue('C'.$startcellno, strtoupper($filteredstudent->gender));
                                $sheet->setCellValue('D'.$startcellno, $filteredstudent->levelname);
                                $sheet->setCellValue('E'.$startcellno, $filteredstudent->sectionname);
                                $sheet->setCellValue('F'.$startcellno, $filteredstudent->trackname);
                                $sheet->setCellValue('G'.$startcellno, $filteredstudent->strandname);
                                $sheet->setCellValue('H'.$startcellno, $filteredstudent->mol);
                                $sheet->setCellValue('I'.$startcellno, $filteredstudent->contactno);
                                $sheet->setCellValue('J'.$startcellno, $filteredstudent->address);
                                $sheet->setCellValue('K'.$startcellno, $filteredstudent->fathername);
                                $sheet->setCellValue('L'.$startcellno, $filteredstudent->fcontactno);
                                $sheet->setCellValue('M'.$startcellno, $filteredstudent->mothername);
                                $sheet->setCellValue('N'.$startcellno, $filteredstudent->mcontactno);
                                $sheet->setCellValue('O'.$startcellno, $filteredstudent->guardianname);
                                $sheet->setCellValue('P'.$startcellno, $filteredstudent->gcontactno);
                                $sheet->getStyle('A'.$startcellno.':P'.$startcellno)->applyFromArray($border);
                                $startcellno+=1;

                            }
                        }

                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment; filename="STUDENT DIRECTORY '.$sy->sydesc.' - '.$semester.' Semester.xlsx"');
                        $writer->save("php://output");

                        exit;
                    }
                    elseif($request->get('layout') == 'studentinfo')
                    {
                        $sheet->getColumnDimension('B')->setWidth(20);
                        $sheet->getColumnDimension('D')->setWidth(20);
                        $sheet->getColumnDimension('G')->setWidth(25);
                        $sheet->setTitle('All Students');
                        
                        if($request->has('fourps'))
                        {
                            if($request->get('fourps') == 1)
                            {
                                $filteredstudents = collect($filteredstudents)->where('pantawid','1')->whereIn('studstatus',[1,2,4])->values();
                                $sheet->mergeCells('A9:G9');
                                $sheet->setCellValue('A9', 'List of Students (4Ps)');
                                $sheet->getStyle('A9:G9')->getAlignment()->setHorizontal('center');
                                $startcellno = 11;
                            }else{
                                if($request->get('selectedacadprog') == 6)
                                {
                                    $sheet->mergeCells('A9:B9');
                                    $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                    $sheet->mergeCells('C9:D9');
                                    $sheet->setCellValue('C9', 'COLLEGE : '.$trackname);
                                    $sheet->mergeCells('F9:G9');
                                    $sheet->setCellValue('F9', 'COURSE : '.$strandname);
                                    // $sheet->setCellValue('F9', 'GENDER : '.$selectedgender);
                
                                    $sheet->mergeCells('A10:B10');
                                    $sheet->setCellValue('A10', 'GRADE LEVEL : '.$selectedgradelevel);
                                    $sheet->mergeCells('C10:D10');
                                    $sheet->setCellValue('C10', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                    $sheet->mergeCells('F10:G10');
                                    $sheet->setCellValue('F10', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                    
                                    $sheet->mergeCells('A11:B11');
                                    $sheet->setCellValue('A11', 'SECTION : '.$selectedsection);
                                    $sheet->mergeCells('C11:D11');
                                    $sheet->setCellValue('C11', 'MOL : '.$selectedmode);
                                    $sheet->mergeCells('F11:G11');
                                    $sheet->setCellValue('F11', 'DATE PERIOD : '.$selecteddate);

                                }elseif($request->get('selectedacadprog') == 5)
                                {
                                    $sheet->mergeCells('A9:B9');
                                    $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                    $sheet->mergeCells('C9:D9');
                                    $sheet->setCellValue('C9', 'TRACK : '.$trackname);
                                    $sheet->mergeCells('F9:G9');
                                    $sheet->setCellValue('F9', 'STRAND : '.$strandname);
                
                                    $sheet->mergeCells('A10:B10');
                                    $sheet->setCellValue('A10', 'GRADE LEVEL : '.$selectedgradelevel);
                                    $sheet->mergeCells('C10:D10');
                                    $sheet->setCellValue('C10', 'SECTION : '.$selectedsection);
                                    $sheet->mergeCells('F10:G10');
                                    $sheet->setCellValue('F10', 'GENDER : '.$selectedgender);
                                    
                                    $sheet->mergeCells('A11:B11');
                                    $sheet->setCellValue('A11', 'GRANTEE : '.$selectedgrantee);
                                    $sheet->mergeCells('C11:D11');
                                    $sheet->setCellValue('C11', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                    $sheet->mergeCells('F11:G11');
                                    $sheet->setCellValue('F11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                    
                                    $sheet->mergeCells('A12:B12');
                                    $sheet->setCellValue('A12', 'MOL : '.$selectedmode);
                                    $sheet->mergeCells('C12:D12');
                                    $sheet->setCellValue('C12', 'DATE PERIOD : '.$selecteddate);
                                    
                                }elseif($request->get('selectedacadprog') == 4 || $request->get('selectedacadprog') == 3 || $request->get('selectedacadprog') == 2 )
                                {
                                    $sheet->mergeCells('A9:B9');
                                    $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                    $sheet->mergeCells('C9:D9');
                                    $sheet->setCellValue('C9', 'GRADE LEVEL : '.$selectedgradelevel);
                                    $sheet->mergeCells('F9:G9');
                                    $sheet->setCellValue('F9', 'SECTION : '.$selectedsection);
                
                                    $sheet->mergeCells('A10:B10');
                                    $sheet->setCellValue('A10', 'GRANTEE : '.$selectedgrantee);
                                    $sheet->mergeCells('C10:D10');
                                    $sheet->setCellValue('C10', 'MOL : '.$selectedmode);
                                    $sheet->mergeCells('F10:G10');
                                    $sheet->setCellValue('F10', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                    
                                    $sheet->mergeCells('A11:B11');
                                    $sheet->setCellValue('A11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                    $sheet->mergeCells('C11:D11');
                                    $sheet->setCellValue('C11', 'DATE PERIOD : '.$selecteddate);
                                    
                                }else{
                                    $sheet->mergeCells('A9:B9');
                                    $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                    $sheet->mergeCells('C9:D9');
                                    $sheet->setCellValue('C9', 'COLLEGE/TRACK : '.$trackname);
                                    $sheet->mergeCells('F9:G9');
                                    $sheet->setCellValue('F9', 'GENDER : '.$selectedgender);
                
                                    $sheet->mergeCells('A10:B10');
                                    $sheet->setCellValue('A10', 'DEPARTMENT : '.$selectedacadprog);
                                    $sheet->mergeCells('C10:D10');
                                    $sheet->setCellValue('C10', 'COURSE/STRAND : '.$strandname);
                                    $sheet->mergeCells('F10:G10');
                                    $sheet->setCellValue('F10', 'GRANTEE : '.$selectedgrantee);
                                    
                                    $sheet->mergeCells('A11:B11');
                                    $sheet->setCellValue('A11', 'GRADE LEVEL : '.$selectedgradelevel);
                                    $sheet->mergeCells('C11:D11');
                                    $sheet->setCellValue('C11', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                    $sheet->mergeCells('F11:G11');
                                    $sheet->setCellValue('F11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                    
                                    $sheet->mergeCells('A12:B12');
                                    $sheet->setCellValue('A12', 'SECTION : '.$selectedsection);
                                    $sheet->mergeCells('C12:D12');
                                    $sheet->setCellValue('C12', 'MOL : '.$selectedmode);
                                    $sheet->mergeCells('F12:G12');
                                    $sheet->setCellValue('F12', 'DATE PERIOD : '.$selecteddate);
                                }
        
                                $startcellno = 14;

                            }
                        }else{
                            if($request->get('selectedacadprog') == 6)
                            {
                                $sheet->mergeCells('A9:B9');
                                $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                $sheet->mergeCells('C9:D9');
                                $sheet->setCellValue('C9', 'COLLEGE : '.$trackname);
                                $sheet->mergeCells('F9:G9');
                                $sheet->setCellValue('F9', 'COURSE : '.$strandname);
                                // $sheet->setCellValue('F9', 'GENDER : '.$selectedgender);
            
                                $sheet->mergeCells('A10:B10');
                                $sheet->setCellValue('A10', 'GRADE LEVEL : '.$selectedgradelevel);
                                $sheet->mergeCells('C10:D10');
                                $sheet->setCellValue('C10', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                $sheet->mergeCells('F10:G10');
                                $sheet->setCellValue('F10', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                
                                $sheet->mergeCells('A11:B11');
                                $sheet->setCellValue('A11', 'SECTION : '.$selectedsection);
                                $sheet->mergeCells('C11:D11');
                                $sheet->setCellValue('C11', 'MOL : '.$selectedmode);
                                $sheet->mergeCells('F11:G11');
                                $sheet->setCellValue('F11', 'DATE PERIOD : '.$selecteddate);

                            }elseif($request->get('selectedacadprog') == 5)
                            {
                                $sheet->mergeCells('A9:B9');
                                $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                $sheet->mergeCells('C9:D9');
                                $sheet->setCellValue('C9', 'TRACK : '.$trackname);
                                $sheet->mergeCells('F9:G9');
                                $sheet->setCellValue('F9', 'STRAND : '.$strandname);
            
                                $sheet->mergeCells('A10:B10');
                                $sheet->setCellValue('A10', 'GRADE LEVEL : '.$selectedgradelevel);
                                $sheet->mergeCells('C10:D10');
                                $sheet->setCellValue('C10', 'SECTION : '.$selectedsection);
                                $sheet->mergeCells('F10:G10');
                                $sheet->setCellValue('F10', 'GENDER : '.$selectedgender);
                                
                                $sheet->mergeCells('A11:B11');
                                $sheet->setCellValue('A11', 'GRANTEE : '.$selectedgrantee);
                                $sheet->mergeCells('C11:D11');
                                $sheet->setCellValue('C11', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                $sheet->mergeCells('F11:G11');
                                $sheet->setCellValue('F11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                
                                $sheet->mergeCells('A12:B12');
                                $sheet->setCellValue('A12', 'MOL : '.$selectedmode);
                                $sheet->mergeCells('C12:D12');
                                $sheet->setCellValue('C12', 'DATE PERIOD : '.$selecteddate);
                                
                            }elseif($request->get('selectedacadprog') == 4 || $request->get('selectedacadprog') == 3 || $request->get('selectedacadprog') == 2 )
                            {
                                $sheet->mergeCells('A9:B9');
                                $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                $sheet->mergeCells('C9:D9');
                                $sheet->setCellValue('C9', 'GRADE LEVEL : '.$selectedgradelevel);
                                $sheet->mergeCells('F9:G9');
                                $sheet->setCellValue('F9', 'SECTION : '.$selectedsection);
            
                                $sheet->mergeCells('A10:B10');
                                $sheet->setCellValue('A10', 'GRANTEE : '.$selectedgrantee);
                                $sheet->mergeCells('C10:D10');
                                $sheet->setCellValue('C10', 'MOL : '.$selectedmode);
                                $sheet->mergeCells('F10:G10');
                                $sheet->setCellValue('F10', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                
                                $sheet->mergeCells('A11:B11');
                                $sheet->setCellValue('A11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                $sheet->mergeCells('C11:D11');
                                $sheet->setCellValue('C11', 'DATE PERIOD : '.$selecteddate);
                                
                            }else{
                                $sheet->mergeCells('A9:B9');
                                $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                $sheet->mergeCells('C9:D9');
                                $sheet->setCellValue('C9', 'COLLEGE/TRACK : '.$trackname);
                                $sheet->mergeCells('F9:G9');
                                $sheet->setCellValue('F9', 'GENDER : '.$selectedgender);
            
                                $sheet->mergeCells('A10:B10');
                                $sheet->setCellValue('A10', 'DEPARTMENT : '.$selectedacadprog);
                                $sheet->mergeCells('C10:D10');
                                $sheet->setCellValue('C10', 'COURSE/STRAND : '.$strandname);
                                $sheet->mergeCells('F10:G10');
                                $sheet->setCellValue('F10', 'GRANTEE : '.$selectedgrantee);
                                
                                $sheet->mergeCells('A11:B11');
                                $sheet->setCellValue('A11', 'GRADE LEVEL : '.$selectedgradelevel);
                                $sheet->mergeCells('C11:D11');
                                $sheet->setCellValue('C11', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                $sheet->mergeCells('F11:G11');
                                $sheet->setCellValue('F11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                
                                $sheet->mergeCells('A12:B12');
                                $sheet->setCellValue('A12', 'SECTION : '.$selectedsection);
                                $sheet->mergeCells('C12:D12');
                                $sheet->setCellValue('C12', 'MOL : '.$selectedmode);
                                $sheet->mergeCells('F12:G12');
                                $sheet->setCellValue('F12', 'DATE PERIOD : '.$selecteddate);
                            }
    
                            $startcellno = 14;
                        

                        }
                        // $sheet->getColumnDimension('A')->setAutoSize(true);
                        // $sheet->getColumnDimension('B')->setAutoSize(true);
                        // $sheet->getColumnDimension('C')->setAutoSize(true);
                        // $sheet->getColumnDimension('D')->setAutoSize(true);
                        // $sheet->getColumnDimension('E')->setAutoSize(true);
                        // $sheet->getColumnDimension('F')->setAutoSize(true);
                        // $sheet->getColumnDimension('G')->setAutoSize(true);
                        
                            
                        $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->getAlignment()->setHorizontal('center');

                        $sheet->setCellValue('A'.$startcellno, '#');
                        $sheet->mergeCells('B'.$startcellno.':D'.$startcellno);
                        $sheet->setCellValue('B'.$startcellno, 'First Name');
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Last Name');
                        
                        $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        $startcellno+=1;
                        if(count($filteredstudents)>0)
                        {
                            foreach($filteredstudents as $studentkey => $filteredstudent)
                            {
                                $sheet->setCellValue('A'.$startcellno, $studentkey+1);
                                $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('center');
                                $sheet->mergeCells('B'.$startcellno.':D'.$startcellno);
                                $sheet->setCellValue('B'.$startcellno, $filteredstudent->firstname);
                                $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                                $sheet->setCellValue('E'.$startcellno, $filteredstudent->lastname);
                                
                                $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                                $startcellno+=1;
                            }
                        }

                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Prepared by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $preparedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Generated by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $generatedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        $studentsbyten =  array_chunk(collect($filteredstudents)->toArray(), 20);
                        $sheetkey = 1;
                        foreach($studentsbyten as $byten)
                        {
                            $byten = collect($byten)->sortBy('fullname')->values()->all();
                            $spreadsheet->createSheet();
                            // // Zero based, so set the second tab as active sheet
                            $spreadsheet->setActiveSheetIndex($sheetkey);
                                
                            $sheet = $spreadsheet->getActiveSheet();
                            $sheet->getColumnDimension('B')->setWidth(20);
                            $sheet->getColumnDimension('D')->setWidth(20);
                            $sheet->getColumnDimension('G')->setWidth(25);
                            $sheet->mergeCells('A2:G2');
                            $sheet->setCellValue('A2', $schoolinfo->schoolname);
                            
                            $sheet->mergeCells('A3:G3');
                            $sheet->setCellValue('A3', $schoolinfo->address);
        
                            $sheet->mergeCells('A4:G4');
                            $sheet->setCellValue('A4', 'Office of the Registrar');
        
                            $sheet->getStyle('A2:G8')->getAlignment()->setHorizontal('center');
                            $sheet->getStyle('A2:G2')->getFont()->setBold(true);
        
                            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                            {
                                $sheet->mergeCells('A5:G5');
                                $sheet->setCellValue('A5', 'SUMMARY OF ENROLMENT');
                            }
                            $sheet->mergeCells('A7:G7');
                            $sheet->setCellValue('A7', $semester.' Semester, School Year '.$sy->sydesc);
        
                            $sheet->mergeCells('A8:G8');
                            $sheet->setCellValue('A8', $descacad);
                            $sheet->setTitle('Sheet '.$sheetkey);
                            if($request->has('fourps'))
                            {
                                if($request->get('fourps') == 1)
                                {
                                    $filteredstudents = collect($filteredstudents)->where('pantawid','1')->whereIn('studstatus',[1,2,4])->values();
                                    $sheet->mergeCells('A9:G9');
                                    $sheet->setCellValue('A9', 'List of Students (4Ps)');
                                    $sheet->getStyle('A9:G9')->getAlignment()->setHorizontal('center');
                                    $startcellno = 11;
                                }else{
                                    if($request->get('selectedacadprog') == 6)
                                    {
                                        $sheet->mergeCells('A9:B9');
                                        $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                        $sheet->mergeCells('C9:D9');
                                        $sheet->setCellValue('C9', 'COLLEGE : '.$trackname);
                                        $sheet->mergeCells('F9:G9');
                                        $sheet->setCellValue('F9', 'COURSE : '.$strandname);
                                        // $sheet->setCellValue('F9', 'GENDER : '.$selectedgender);
                    
                                        $sheet->mergeCells('A10:B10');
                                        $sheet->setCellValue('A10', 'GRADE LEVEL : '.$selectedgradelevel);
                                        $sheet->mergeCells('C10:D10');
                                        $sheet->setCellValue('C10', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                        $sheet->mergeCells('F10:G10');
                                        $sheet->setCellValue('F10', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                        
                                        $sheet->mergeCells('A11:B11');
                                        $sheet->setCellValue('A11', 'SECTION : '.$selectedsection);
                                        $sheet->mergeCells('C11:D11');
                                        $sheet->setCellValue('C11', 'MOL : '.$selectedmode);
                                        $sheet->mergeCells('F11:G11');
                                        $sheet->setCellValue('F11', 'DATE PERIOD : '.$selecteddate);
        
                                    }elseif($request->get('selectedacadprog') == 5)
                                    {
                                        $sheet->mergeCells('A9:B9');
                                        $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                        $sheet->mergeCells('C9:D9');
                                        $sheet->setCellValue('C9', 'TRACK : '.$trackname);
                                        $sheet->mergeCells('F9:G9');
                                        $sheet->setCellValue('F9', 'STRAND : '.$strandname);
                    
                                        $sheet->mergeCells('A10:B10');
                                        $sheet->setCellValue('A10', 'GRADE LEVEL : '.$selectedgradelevel);
                                        $sheet->mergeCells('C10:D10');
                                        $sheet->setCellValue('C10', 'SECTION : '.$selectedsection);
                                        $sheet->mergeCells('F10:G10');
                                        $sheet->setCellValue('F10', 'GENDER : '.$selectedgender);
                                        
                                        $sheet->mergeCells('A11:B11');
                                        $sheet->setCellValue('A11', 'GRANTEE : '.$selectedgrantee);
                                        $sheet->mergeCells('C11:D11');
                                        $sheet->setCellValue('C11', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                        $sheet->mergeCells('F11:G11');
                                        $sheet->setCellValue('F11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                        
                                        $sheet->mergeCells('A12:B12');
                                        $sheet->setCellValue('A12', 'MOL : '.$selectedmode);
                                        $sheet->mergeCells('C12:D12');
                                        $sheet->setCellValue('C12', 'DATE PERIOD : '.$selecteddate);
                                        
                                    }elseif($request->get('selectedacadprog') == 4 || $request->get('selectedacadprog') == 3 || $request->get('selectedacadprog') == 2 )
                                    {
                                        $sheet->mergeCells('A9:B9');
                                        $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                        $sheet->mergeCells('C9:D9');
                                        $sheet->setCellValue('C9', 'GRADE LEVEL : '.$selectedgradelevel);
                                        $sheet->mergeCells('F9:G9');
                                        $sheet->setCellValue('F9', 'SECTION : '.$selectedsection);
                    
                                        $sheet->mergeCells('A10:B10');
                                        $sheet->setCellValue('A10', 'GRANTEE : '.$selectedgrantee);
                                        $sheet->mergeCells('C10:D10');
                                        $sheet->setCellValue('C10', 'MOL : '.$selectedmode);
                                        $sheet->mergeCells('F10:G10');
                                        $sheet->setCellValue('F10', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                        
                                        $sheet->mergeCells('A11:B11');
                                        $sheet->setCellValue('A11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                        $sheet->mergeCells('C11:D11');
                                        $sheet->setCellValue('C11', 'DATE PERIOD : '.$selecteddate);
                                        
                                    }else{
                                        $sheet->mergeCells('A9:B9');
                                        $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                        $sheet->mergeCells('C9:D9');
                                        $sheet->setCellValue('C9', 'COLLEGE/TRACK : '.$trackname);
                                        $sheet->mergeCells('F9:G9');
                                        $sheet->setCellValue('F9', 'GENDER : '.$selectedgender);
                    
                                        $sheet->mergeCells('A10:B10');
                                        $sheet->setCellValue('A10', 'DEPARTMENT : '.$selectedacadprog);
                                        $sheet->mergeCells('C10:D10');
                                        $sheet->setCellValue('C10', 'COURSE/STRAND : '.$strandname);
                                        $sheet->mergeCells('F10:G10');
                                        $sheet->setCellValue('F10', 'GRANTEE : '.$selectedgrantee);
                                        
                                        $sheet->mergeCells('A11:B11');
                                        $sheet->setCellValue('A11', 'GRADE LEVEL : '.$selectedgradelevel);
                                        $sheet->mergeCells('C11:D11');
                                        $sheet->setCellValue('C11', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                        $sheet->mergeCells('F11:G11');
                                        $sheet->setCellValue('F11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                        
                                        $sheet->mergeCells('A12:B12');
                                        $sheet->setCellValue('A12', 'SECTION : '.$selectedsection);
                                        $sheet->mergeCells('C12:D12');
                                        $sheet->setCellValue('C12', 'MOL : '.$selectedmode);
                                        $sheet->mergeCells('F12:G12');
                                        $sheet->setCellValue('F12', 'DATE PERIOD : '.$selecteddate);
                                    }
            
                                    $startcellno = 14;
    
                                }
                            }else{
                                if($request->get('selectedacadprog') == 6)
                                {
                                    $sheet->mergeCells('A9:B9');
                                    $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                    $sheet->mergeCells('C9:D9');
                                    $sheet->setCellValue('C9', 'COLLEGE : '.$trackname);
                                    $sheet->mergeCells('F9:G9');
                                    $sheet->setCellValue('F9', 'COURSE : '.$strandname);
                                    // $sheet->setCellValue('F9', 'GENDER : '.$selectedgender);
                
                                    $sheet->mergeCells('A10:B10');
                                    $sheet->setCellValue('A10', 'GRADE LEVEL : '.$selectedgradelevel);
                                    $sheet->mergeCells('C10:D10');
                                    $sheet->setCellValue('C10', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                    $sheet->mergeCells('F10:G10');
                                    $sheet->setCellValue('F10', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                    
                                    $sheet->mergeCells('A11:B11');
                                    $sheet->setCellValue('A11', 'SECTION : '.$selectedsection);
                                    $sheet->mergeCells('C11:D11');
                                    $sheet->setCellValue('C11', 'MOL : '.$selectedmode);
                                    $sheet->mergeCells('F11:G11');
                                    $sheet->setCellValue('F11', 'DATE PERIOD : '.$selecteddate);
    
                                }elseif($request->get('selectedacadprog') == 5)
                                {
                                    $sheet->mergeCells('A9:B9');
                                    $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                    $sheet->mergeCells('C9:D9');
                                    $sheet->setCellValue('C9', 'TRACK : '.$trackname);
                                    $sheet->mergeCells('F9:G9');
                                    $sheet->setCellValue('F9', 'STRAND : '.$strandname);
                
                                    $sheet->mergeCells('A10:B10');
                                    $sheet->setCellValue('A10', 'GRADE LEVEL : '.$selectedgradelevel);
                                    $sheet->mergeCells('C10:D10');
                                    $sheet->setCellValue('C10', 'SECTION : '.$selectedsection);
                                    $sheet->mergeCells('F10:G10');
                                    $sheet->setCellValue('F10', 'GENDER : '.$selectedgender);
                                    
                                    $sheet->mergeCells('A11:B11');
                                    $sheet->setCellValue('A11', 'GRANTEE : '.$selectedgrantee);
                                    $sheet->mergeCells('C11:D11');
                                    $sheet->setCellValue('C11', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                    $sheet->mergeCells('F11:G11');
                                    $sheet->setCellValue('F11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                    
                                    $sheet->mergeCells('A12:B12');
                                    $sheet->setCellValue('A12', 'MOL : '.$selectedmode);
                                    $sheet->mergeCells('C12:D12');
                                    $sheet->setCellValue('C12', 'DATE PERIOD : '.$selecteddate);
                                    
                                }elseif($request->get('selectedacadprog') == 4 || $request->get('selectedacadprog') == 3 || $request->get('selectedacadprog') == 2 )
                                {
                                    $sheet->mergeCells('A9:B9');
                                    $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                    $sheet->mergeCells('C9:D9');
                                    $sheet->setCellValue('C9', 'GRADE LEVEL : '.$selectedgradelevel);
                                    $sheet->mergeCells('F9:G9');
                                    $sheet->setCellValue('F9', 'SECTION : '.$selectedsection);
                
                                    $sheet->mergeCells('A10:B10');
                                    $sheet->setCellValue('A10', 'GRANTEE : '.$selectedgrantee);
                                    $sheet->mergeCells('C10:D10');
                                    $sheet->setCellValue('C10', 'MOL : '.$selectedmode);
                                    $sheet->mergeCells('F10:G10');
                                    $sheet->setCellValue('F10', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                    
                                    $sheet->mergeCells('A11:B11');
                                    $sheet->setCellValue('A11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                    $sheet->mergeCells('C11:D11');
                                    $sheet->setCellValue('C11', 'DATE PERIOD : '.$selecteddate);
                                    
                                }else{
                                    $sheet->mergeCells('A9:B9');
                                    $sheet->setCellValue('A9', 'SCHOOL YEAR : '.$sy->sydesc);
                                    $sheet->mergeCells('C9:D9');
                                    $sheet->setCellValue('C9', 'COLLEGE/TRACK : '.$trackname);
                                    $sheet->mergeCells('F9:G9');
                                    $sheet->setCellValue('F9', 'GENDER : '.$selectedgender);
                
                                    $sheet->mergeCells('A10:B10');
                                    $sheet->setCellValue('A10', 'DEPARTMENT : '.$selectedacadprog);
                                    $sheet->mergeCells('C10:D10');
                                    $sheet->setCellValue('C10', 'COURSE/STRAND : '.$strandname);
                                    $sheet->mergeCells('F10:G10');
                                    $sheet->setCellValue('F10', 'GRANTEE : '.$selectedgrantee);
                                    
                                    $sheet->mergeCells('A11:B11');
                                    $sheet->setCellValue('A11', 'GRADE LEVEL : '.$selectedgradelevel);
                                    $sheet->mergeCells('C11:D11');
                                    $sheet->setCellValue('C11', 'ADMISSION STATUS : '.$selectedstudentstatus);
                                    $sheet->mergeCells('F11:G11');
                                    $sheet->setCellValue('F11', 'STUDENT TYPE : '.strtoupper($selectedstudenttype));
                                    
                                    $sheet->mergeCells('A12:B12');
                                    $sheet->setCellValue('A12', 'SECTION : '.$selectedsection);
                                    $sheet->mergeCells('C12:D12');
                                    $sheet->setCellValue('C12', 'MOL : '.$selectedmode);
                                    $sheet->mergeCells('F12:G12');
                                    $sheet->setCellValue('F12', 'DATE PERIOD : '.$selecteddate);
                                }
        
                                $startcellno = 14;
    
                            }
            
                            if(count($byten)>0)
                            {
                                foreach($byten as $studentkey=>$student)
                                {
                                    $sheet->setCellValue('A'.$startcellno, $studentkey+1);
                                    $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('center');
                                    $sheet->mergeCells('B'.$startcellno.':D'.$startcellno);
                                    $sheet->setCellValue('B'.$startcellno, $student->firstname);
                                    $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                                    $sheet->setCellValue('E'.$startcellno, $student->lastname);
                                    
                                    $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
    
                                    $startcellno+=1;
                                }
                            }
                            $startcellno+=2;
                            $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                            $sheet->setCellValue('E'.$startcellno, 'Prepared by:');
                            
                            $startcellno+=2;
                            $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                            $sheet->setCellValue('E'.$startcellno, $preparedby);
                            $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                                ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                            $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
                            $startcellno+=2;
                            $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                            $sheet->setCellValue('E'.$startcellno, 'Generated by:');
                            
                            $startcellno+=2;
                            $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                            $sheet->setCellValue('E'.$startcellno, $generatedby);
                            $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                                ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                            $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $sheetkey+=1;
                        }
                        $spreadsheet->setActiveSheetIndex(0);
    
                    }
                    elseif($request->get('layout') == 'gradelevel')
                    {
                        if($request->has('fourps'))
                        {
                            if($request->get('fourps') == 1)
                            {
                                $sheet->getColumnDimension('A')->setAutoSize(true);
                                $sheet->getColumnDimension('B')->setAutoSize(true);
                                $startcellno = 10;
                                
                                $sheet->setCellValue('B'.$startcellno, 'Grade Level');
                                $sheet->mergeCells('C'.$startcellno.':I'.$startcellno);
                                $sheet->setCellValue('C'.$startcellno, 'Students');
                                $sheet ->getStyle('A'.$startcellno.':I'.$startcellno)->applyFromArray($border);
        
                                $startcellno+=1;
        
                                if(count($records[0]->gradelevels) > 0)
                                {
                                    foreach($records[0]->gradelevels as $levelkey => $gradelevel)
                                    {
                                        // return collect($gradelevel);
                                        if(count($gradelevel->students)>0)
                                        {
                                            $studentno = 1;
                                            // $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                            $sheet->setCellValue('A'.$startcellno, $levelkey+1);
                                            $sheet->setCellValue('B'.$startcellno, $gradelevel->levelname);
                                            $startcellno+=1;
                                            foreach($gradelevel->students as $eachstud)
                                            {
                                                $sheet->mergeCells('C'.$startcellno.':G'.$startcellno);
                                                $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
                                                $sheet->setCellValue('C'.$startcellno, $studentno.'. '.$eachstud->lastname.', '.$eachstud->firstname.' '.$eachstud->suffix.' '.$eachstud->middlename);
                                                $sheet->mergeCells('H'.$startcellno.':I'.$startcellno);
                                                if($eachstud->acadprogid == 6)
                                                {
                                                    $sheet->setCellValue('H'.$startcellno, $eachstud->coursename);
                                                }else{
                                                    $sheet->setCellValue('H'.$startcellno, $eachstud->sectionname);
                                                }
                
                                                $startcellno+=1;
                                                $studentno+=1;
                                            }
                                            $startcellno+=1;
                                        }else{
                                            $sheet->setCellValue('A'.$startcellno, $levelkey+1);
                                            $sheet->setCellValue('B'.$startcellno, $gradelevel->levelname);
                                            $startcellno+=1;
                                            $sheet->mergeCells('C'.$startcellno.':G'.$startcellno);
                                            $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
                                            $sheet->setCellValue('C'.$startcellno,'No students shown');
            
                                            $startcellno+=1;
                                        }
                                    }
                                    // $sheet->setCellValue('B'.$startcellno,'Total');
                                    // $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                    // $sheet->setCellValue('C'.$startcellno, collect($records[0]->gradelevels)->sum('countmale'));
                                    // $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                    // $sheet->setCellValue('E'.$startcellno, collect($records[0]->gradelevels)->sum('countfemale'));
                                    // $sheet->setCellValue('G'.$startcellno, collect($records[0]->gradelevels)->sum('total'));
                                    // $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
        
                                }
                            }else{
                                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                                {
                                    $sheet->getColumnDimension('A')->setAutoSize(true);
                                    $sheet->getColumnDimension('B')->setAutoSize(true);
                                    $startcellno = 10;
                                    
                                    $sheet->setCellValue('B'.$startcellno, 'Grade Level');
                                    $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                    $sheet->setCellValue('C'.$startcellno, 'Male');
                                    $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                    $sheet->setCellValue('E'.$startcellno, 'Female');
                                    $sheet->setCellValue('G'.$startcellno, 'Total');
                                    $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
            
                                    $startcellno+=1;
            
                                    if(count($records[0]->gradelevels) > 0)
                                    {
                                        foreach($records[0]->gradelevels as $levelkey => $gradelevel)
                                        {
                                            // $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                            $sheet->setCellValue('A'.$startcellno, $levelkey+1);
                                            $sheet->setCellValue('B'.$startcellno, $gradelevel->levelname);
                                            $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                            $sheet->setCellValue('C'.$startcellno, $gradelevel->countmale);
                                            $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                            $sheet->setCellValue('E'.$startcellno, $gradelevel->countfemale);
                                            $sheet->setCellValue('G'.$startcellno, $gradelevel->total);
                                            $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
            
                                            $startcellno+=1;
                                            if(count($gradelevel->students)>0)
                                            {
                                                foreach($gradelevel->students as $studkey=>$eachstud)
                                                {
                                                    $sheet->setCellValue('B'.$startcellno, $studkey+1);
                                                    
                                                    $sheet->mergeCells('C'.$startcellno.':G'.$startcellno);
                                                    $sheet->setCellValue('B'.$startcellno, $eachstud->lastname.', '.$eachstud->firstname.' '.$eachstud->middlename);
                                                    $startcellno+=1;
                                                }
                                            }
                                        }
                                        $sheet->setCellValue('B'.$startcellno,'Total');
                                        $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                        $sheet->setCellValue('C'.$startcellno, collect($records[0]->gradelevels)->sum('countmale'));
                                        $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                        $sheet->setCellValue('E'.$startcellno, collect($records[0]->gradelevels)->sum('countfemale'));
                                        $sheet->setCellValue('G'.$startcellno, collect($records[0]->gradelevels)->sum('total'));
                                        $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
            
                                    }
    
                                }else{
                                    $sheet->getColumnDimension('A')->setAutoSize(true);
                                    $sheet->getColumnDimension('B')->setAutoSize(true);
                                    $startcellno = 10;
                                    
                                    $sheet->setCellValue('B'.$startcellno, 'Grade Level');
                                    $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                    $sheet->setCellValue('C'.$startcellno, 'Male');
                                    $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                    $sheet->setCellValue('E'.$startcellno, 'Female');
                                    $sheet->setCellValue('G'.$startcellno, 'Total');
                                    $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
            
                                    $startcellno+=1;
            
                                    if(count($records[0]->gradelevels) > 0)
                                    {
                                        foreach($records[0]->gradelevels as $levelkey => $gradelevel)
                                        {
                                            // $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                            $sheet->setCellValue('A'.$startcellno, $levelkey+1);
                                            $sheet->setCellValue('B'.$startcellno, $gradelevel->levelname);
                                            $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                            $sheet->setCellValue('C'.$startcellno, $gradelevel->countmale);
                                            $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                            $sheet->setCellValue('E'.$startcellno, $gradelevel->countfemale);
                                            $sheet->setCellValue('G'.$startcellno, $gradelevel->total);
                                            $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
            
                                            $startcellno+=1;
                                        }
                                        $sheet->setCellValue('B'.$startcellno,'Total');
                                        $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                        $sheet->setCellValue('C'.$startcellno, collect($records[0]->gradelevels)->sum('countmale'));
                                        $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                        $sheet->setCellValue('E'.$startcellno, collect($records[0]->gradelevels)->sum('countfemale'));
                                        $sheet->setCellValue('G'.$startcellno, collect($records[0]->gradelevels)->sum('total'));
                                        $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
            
                                    }
                                }
                            }
                        }else{
                            $sheet->getColumnDimension('A')->setAutoSize(true);
                            $sheet->getColumnDimension('B')->setAutoSize(true);
                            $startcellno = 10;
                            
                            $sheet->setCellValue('B'.$startcellno, 'Grade Level');
                            $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                            $sheet->setCellValue('C'.$startcellno, 'Male');
                            $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                            $sheet->setCellValue('E'.$startcellno, 'Female');
                            $sheet->setCellValue('G'.$startcellno, 'Total');
                            $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
    
                            $startcellno+=1;
    
                            if(count($records[0]->gradelevels) > 0)
                            {
                                foreach($records[0]->gradelevels as $levelkey => $gradelevel)
                                {
                                    // $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                    $sheet->setCellValue('A'.$startcellno, $levelkey+1);
                                    $sheet->setCellValue('B'.$startcellno, $gradelevel->levelname);
                                    $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                    $sheet->setCellValue('C'.$startcellno, $gradelevel->countmale);
                                    $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                    $sheet->setCellValue('E'.$startcellno, $gradelevel->countfemale);
                                    $sheet->setCellValue('G'.$startcellno, $gradelevel->total);
                                    $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
    
                                    $startcellno+=1;
                                }
                                $sheet->setCellValue('B'.$startcellno,'Total');
                                $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                $sheet->setCellValue('C'.$startcellno, collect($records[0]->gradelevels)->sum('countmale'));
                                $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                $sheet->setCellValue('E'.$startcellno, collect($records[0]->gradelevels)->sum('countfemale'));
                                $sheet->setCellValue('G'.$startcellno, collect($records[0]->gradelevels)->sum('total'));
                                $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);
    
                            }
                        }

                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Prepared by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $preparedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Generated by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $generatedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    }
                    elseif($request->get('layout') == 'track')
                    {
                        $sheet->getColumnDimension('A')->setAutoSize(true);
                        $sheet->getColumnDimension('B')->setAutoSize(true);
                        $startcellno = 10;
                        
                        $sheet->setCellValue('B'.$startcellno, 'Track');
                        $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                        $sheet->setCellValue('C'.$startcellno, 'Male');
                        $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Female');
                        $sheet->setCellValue('G'.$startcellno, 'Total');
                        $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        $startcellno+=1;

                        if(count($records[0]->tracks) > 0)
                        {
                            foreach($records[0]->tracks as $trackkey => $track)
                            {
                                // $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                $sheet->setCellValue('A'.$startcellno, $trackkey+1);
                                $sheet->setCellValue('B'.$startcellno, $track->trackname);
                                $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                $sheet->setCellValue('C'.$startcellno, $track->countmale);
                                $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                $sheet->setCellValue('E'.$startcellno, $track->countfemale);
                                $sheet->setCellValue('G'.$startcellno, $track->total);
                                $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                                $startcellno+=1;
                            }
                            $sheet->setCellValue('B'.$startcellno,'Total');
                            $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                            $sheet->setCellValue('C'.$startcellno, collect($records[0]->tracks)->sum('countmale'));
                            $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                            $sheet->setCellValue('E'.$startcellno, collect($records[0]->tracks)->sum('countfemale'));
                            $sheet->setCellValue('G'.$startcellno, collect($records[0]->tracks)->sum('total'));
                            $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        }

                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Prepared by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $preparedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Generated by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $generatedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    }
                    elseif($request->get('layout') == 'strand')
                    {
                        $sheet->getColumnDimension('A')->setAutoSize(true);
                        $sheet->getColumnDimension('B')->setAutoSize(true);
                        $startcellno = 10;
                        
                        $sheet->setCellValue('B'.$startcellno, 'Strand');
                        $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                        $sheet->setCellValue('C'.$startcellno, 'Male');
                        $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Female');
                        $sheet->setCellValue('G'.$startcellno, 'Total');
                        $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        $startcellno+=1;

                        if(count($records[0]->strands) > 0)
                        {
                            foreach($records[0]->strands as $strandkey => $strand)
                            {
                                // $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                $sheet->setCellValue('A'.$startcellno, $strandkey+1);
                                $sheet->setCellValue('B'.$startcellno, $strand->strandname);
                                $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                $sheet->setCellValue('C'.$startcellno, $strand->countmale);
                                $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                $sheet->setCellValue('E'.$startcellno, $strand->countfemale);
                                $sheet->setCellValue('G'.$startcellno, $strand->total);
                                $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                                $startcellno+=1;
                            }
                            $sheet->setCellValue('B'.$startcellno,'Total');
                            $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                            $sheet->setCellValue('C'.$startcellno, collect($records[0]->strands)->sum('countmale'));
                            $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                            $sheet->setCellValue('E'.$startcellno, collect($records[0]->strands)->sum('countfemale'));
                            $sheet->setCellValue('G'.$startcellno, collect($records[0]->strands)->sum('total'));
                            $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        }
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Prepared by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $preparedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Generated by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $generatedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    }
                    elseif($request->get('layout') == 'college')
                    {
                        $sheet->getColumnDimension('A')->setAutoSize(true);
                        $sheet->getColumnDimension('B')->setAutoSize(true);
                        $startcellno = 10;
                        
                        $sheet->setCellValue('B'.$startcellno, 'College');
                        $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                        $sheet->setCellValue('C'.$startcellno, 'Male');
                        $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Female');
                        $sheet->setCellValue('G'.$startcellno, 'Total');
                        $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        $startcellno+=1;

                        if(count($records[0]->colleges) > 0)
                        {
                            foreach($records[0]->colleges as $collegekey => $college)
                            {
                                // $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                $sheet->setCellValue('A'.$startcellno, $collegekey+1);
                                $sheet->setCellValue('B'.$startcellno, $college->collegeDesc);
                                $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                $sheet->setCellValue('C'.$startcellno, $college->countmale);
                                $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                $sheet->setCellValue('E'.$startcellno, $college->countfemale);
                                $sheet->setCellValue('G'.$startcellno, $college->total);
                                $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                                $startcellno+=1;
                            }
                            $sheet->setCellValue('B'.$startcellno,'Total');
                            $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                            $sheet->setCellValue('C'.$startcellno, collect($records[0]->colleges)->sum('countmale'));
                            $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                            $sheet->setCellValue('E'.$startcellno, collect($records[0]->colleges)->sum('countfemale'));
                            $sheet->setCellValue('G'.$startcellno, collect($records[0]->colleges)->sum('total'));
                            $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        }
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Prepared by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $preparedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Generated by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $generatedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    }
                    elseif($request->get('layout') == 'course')
                    {
                        $sheet->getColumnDimension('A')->setAutoSize(true);
                        $sheet->getColumnDimension('B')->setAutoSize(true);
                        $startcellno = 10;
                        
                        $sheet->setCellValue('B'.$startcellno, 'Course');
                        $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                        $sheet->setCellValue('C'.$startcellno, 'Male');
                        $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Female');
                        $sheet->setCellValue('G'.$startcellno, 'Total');
                        $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        $startcellno+=1;

                        if(count($records[0]->courses) > 0)
                        {
                            foreach($records[0]->courses as $coursekey => $course)
                            {
                                // $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                $sheet->setCellValue('A'.$startcellno, $coursekey+1);
                                $sheet->setCellValue('B'.$startcellno, $course->courseDesc);
                                $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                $sheet->setCellValue('C'.$startcellno, $course->countmale);
                                $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                $sheet->setCellValue('E'.$startcellno, $course->countfemale);
                                $sheet->setCellValue('G'.$startcellno, $course->total);
                                $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                                $startcellno+=1;
                            }
                            $sheet->setCellValue('B'.$startcellno,'Total');
                            $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                            $sheet->setCellValue('C'.$startcellno, collect($records[0]->courses)->sum('countmale'));
                            $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                            $sheet->setCellValue('E'.$startcellno, collect($records[0]->courses)->sum('countfemale'));
                            $sheet->setCellValue('G'.$startcellno, collect($records[0]->courses)->sum('total'));
                            $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        }
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Prepared by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $preparedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Generated by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $generatedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    }
                    elseif($request->get('layout') == 'section')
                    {
                        $sheet->getColumnDimension('A')->setAutoSize(true);
                        $sheet->getColumnDimension('B')->setAutoSize(true);
                        $startcellno = 10;
                        
                        $sheet->setCellValue('B'.$startcellno, 'Section');
                        $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                        $sheet->setCellValue('C'.$startcellno, 'Male');
                        $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Female');
                        $sheet->setCellValue('G'.$startcellno, 'Total');
                        $sheet ->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        $startcellno+=1;

                        if(count($records[0]->sections) > 0)
                        {
                            foreach($records[0]->sections as $sectionkey => $section)
                            {
                                // $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                                $sheet->setCellValue('A'.$startcellno, $sectionkey+1);
                                $sheet->setCellValue('B'.$startcellno, $section->levelname.' - '.$section->sectionname);
                                $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                                $sheet->setCellValue('C'.$startcellno, $section->countmale);
                                $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                                $sheet->setCellValue('E'.$startcellno, $section->countfemale);
                                $sheet->setCellValue('G'.$startcellno, $section->total);
                                $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                                $startcellno+=1;
                            }
                            $sheet->setCellValue('B'.$startcellno,'Total');
                            $sheet->mergeCells('C'.$startcellno.':D'.$startcellno);
                            $sheet->setCellValue('C'.$startcellno, collect($records[0]->sections)->sum('countmale'));
                            $sheet->mergeCells('E'.$startcellno.':F'.$startcellno);
                            $sheet->setCellValue('E'.$startcellno, collect($records[0]->sections)->sum('countfemale'));
                            $sheet->setCellValue('G'.$startcellno, collect($records[0]->sections)->sum('total'));
                            $sheet->getStyle('A'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                        }
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Prepared by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $preparedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, 'Generated by:');
                        
                        $startcellno+=2;
                        $sheet->mergeCells('E'.$startcellno.':G'.$startcellno);
                        $sheet->setCellValue('E'.$startcellno, $generatedby);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->getStyle('E'.$startcellno.':G'.$startcellno)
                            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    }
                    
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="Student Summary.xlsx"');
                    $writer->save("php://output");
                    
                    exit;
                }else{
                    if($request->get('layout') == 'student')
                    {
                        $students = array();
                        if(count($filteredstudents)>0)
                        {
                            foreach($filteredstudents as $stud)
                            {
                                $studvals = collect();
                                foreach($stud as $studkey => $studvalue)
                                {
                                    
                                    if($studkey == 'gender' ||$studkey == 'sid' ||$studkey == 'lrn' ||$studkey == 'lastname' ||$studkey == 'firstname' ||$studkey == 'middlename' ||$studkey == 'levelname' ||$studkey == 'sectionname' ||$studkey == 'trackname' ||$studkey == 'strandcode' ||$studkey == 'mol' ||$studkey == 'studentstatus'  ||$studkey == 'dateenrolled' )
                                    {
                                        $studvals = $studvals->merge([
                                                $studkey => $studvalue    
                                            ]);
                                        // array_push($studvals, (object)array(
                                        //         $studkey => $studvalue    
                                        //     )
                                        //     );
                                    }
                                }
                                $studvals = $studvals->all();
                                        array_push($students, (object)$studvals
                                            );
                                
                            }
                        }
                        $filteredstudents = $students;;
                        $list = $request->get('list');
                        $selectedacadprog = $request->get('selectedacadprog');
                        // $pdf = PDF::loadview('registrar/pdf/pdf_leasf')->setPaper('portrait');; 
                        $pdf = PDF::loadview('registrar/summaries/pdf/students',compact('list','filteredstudents','selectedgender','schoolinfo','sy','descacad','semester','trackname','selectedacadprog','strandname','selectedgrantee','selectedgradelevel','selectedstudentstatus','selectedstudenttype','selectedsection','selectedmode','selecteddate','records','preparedby','generatedby'))->setPaper('portrait');; 
                        return $pdf->stream('Student Summary.pdf');
                    }
                    elseif($request->get('layout') == 'gradelevel')
                    {
                        $list = $request->get('list');
                        $selectedacadprog = $request->get('selectedacadprog');
                        $semester = DB::table('semester')->where('id', $request->get('selectedsemester'))->first()->semester;
                        $selectedacadprog = $request->get('selectedacadprog');
                        $pdf = PDF::loadview('registrar/summaries/pdf/table_0',compact('semester','list','filteredstudents','selectedgender','schoolinfo','sy','descacad','trackname','selectedacadprog','strandname','selectedgrantee','selectedgradelevel','selectedstudentstatus','selectedstudenttype','selectedsection','selectedmode','selecteddate','records','preparedby','generatedby'))->setPaper('legal','portrait');; 
                        return $pdf->stream('Student Summary - By Grade Level.pdf');
                        // return $records[0]->gradelevels;
                        
                        // $pdf->writeHTML($header, true, false, false, false, '');
    
    
                        // $pdf->writeHTML($table, true, false, false, false, '');
                        
                        // // ---------------------------------------------------------
                        // //Close and output PDF document
                        // $pdf->Output('Enrollment Summary.pdf', 'I');

                    }
                    elseif($request->get('layout') == 'track')
                    {
                        $list = $request->get('list');
                        // return $records;
                        // return $request->all();
                        $pdf = PDF::loadview('registrar/summaries/pdf/bytrack',compact('list','filteredstudents','selectedgender','schoolinfo','sy','descacad','trackname','selectedacadprog','strandname','selectedgrantee','selectedgradelevel','selectedstudentstatus','selectedstudenttype','selectedsection','selectedmode','selecteddate','records','preparedby','generatedby'))->setPaper('legal','portrait');; 
                        return $pdf->stream('Student Summary - By Track.pdf');
                    }
                    elseif($request->get('layout') == 'strand')
                    {
                        $list = $request->get('list');
                        $pdf = PDF::loadview('registrar/summaries/pdf/bystrand',compact('list','filteredstudents','selectedgender','schoolinfo','sy','descacad','trackname','selectedacadprog','strandname','selectedgrantee','selectedgradelevel','selectedstudentstatus','selectedstudenttype','selectedsection','selectedmode','selecteddate','records','preparedby','generatedby'))->setPaper('legal','portrait');; 
                        return $pdf->stream('Student Summary - By Strand.pdf');
                    }
                    elseif($request->get('layout') == 'college')
                    {
                        // return $records[0]->colleges;
                        $list = $request->get('list');
                        $pdf = PDF::loadview('registrar/summaries/pdf/bycollege',compact('list','filteredstudents','selectedgender','schoolinfo','sy','descacad','trackname','selectedacadprog','strandname','selectedgrantee','selectedgradelevel','selectedstudentstatus','selectedstudenttype','selectedsection','selectedmode','selecteddate','records','preparedby','generatedby'))->setPaper('legal','portrait');; 
                        return $pdf->stream('Student Summary - By College.pdf');
                    }
                    elseif($request->get('layout') == 'course')
                    {
                        $list = $request->get('list');
                        if(count($filteredstudents)>0)
                        {
                            foreach($filteredstudents as $filteredstudent)
                            {
                                $filteredstudent->gender = strtolower($filteredstudent->gender);
                            }
                        }
                        $semester = DB::table('semester')->where('id', $request->get('selectedsemester'))->first()->semester;
                        $selectedacadprog = $request->get('selectedacadprog');
                        // return $records;
                        $pdf = PDF::loadview('registrar/summaries/pdf/bycourse',compact('semester','list','filteredstudents','selectedgender','schoolinfo','sy','descacad','trackname','selectedacadprog','strandname','selectedgrantee','selectedgradelevel','selectedstudentstatus','selectedstudenttype','selectedsection','selectedmode','selecteddate','records','preparedby','generatedby')); 
                        return $pdf->stream('Student Summary - By Course.pdf');
                    }
                    elseif($request->get('layout') == 'section')
                    {
                        $allsections = 0;
                        if($request->has('allsections'))
                        {
                            if($request->get('allsections') == 1)
                            {
                                $allsections = 1;
                            }
                        }
                        if($allsections == 0)
                        {
                            $pdf = PDF::loadview('registrar/summaries/pdf/bysection',compact('filteredstudents','selectedgender','schoolinfo','sy','descacad','trackname','selectedacadprog','strandname','selectedgrantee','selectedgradelevel','selectedstudentstatus','selectedstudenttype','selectedsection','selectedmode','selecteddate','records','preparedby','generatedby'))->setPaper('legal','portrait');; 
                            return $pdf->stream('Student Summary - By Section.pdf');
                        }else{
                            $pdf = new DCCFormatCollegeEnrollees(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                            $pdf->SetCreator('CK');
                            $pdf->SetAuthor('CK Children\'s Publishing');
                            $pdf->SetTitle($schoolinfo->schoolname.' - Summary');
                            $pdf->SetSubject('Summary');
                            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                            $pdf->SetMargins(7, 12, 7);
                            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                            
                            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                                require_once(dirname(__FILE__).'/lang/eng.php');
                                $pdf->setLanguageArray($l);
                            }
                            $pdf->AddPage();
                            $header = '';
    
                            
    
                            $header.='<table style="width: 100%; text-align: center; font-size: 11px;">
                                        <thead>
                                            <tr>
                                                <th style="font-weight: bold;">'.$schoolinfo->schoolname.'</th>
                                            </tr>
                                            <tr>
                                                <th>Toril, Davaop City</th>
                                            </tr>
                                            <tr>
                                                <th>&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <th style="font-weight: bold;">OFFICIAL ENROLMENT SUMMARY</th>
                                            </tr>
                                            <tr>
                                                <th style="font-weight: bold;">S.Y '.$sy->sydesc.'</th>
                                            </tr>
                                            <tr>
                                                <th style="font-weight: bold;">'.$descacad.'</th>
                                            </tr>
                                            <tr>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                    </table>';
                            $header .= '
                                        <table style="height: 20px; font-size: 10px;">
                                            <tr>
                                                <td>SCHOOL YEAR</td>
                                                <td>: '.$sy->sydesc.'</td>
                                                <td>COLLEGE/TRACK</td>
                                                <td>: '.$trackname.'</td>
                                                <td>GENDER</td>
                                                <td>: '.$selectedgender.'</td>
                                            </tr>
                                            <tr>
                                                <td>DEPARTMENT</td>
                                                <td>: '.$selectedacadprog.'</td>
                                                <td>COURSE/STRAND</td>
                                                <td>: '.$strandname.'</td>
                                                <td>GRANTEE</td>
                                                <td>: '.$selectedgrantee.'</td>
                                            </tr>
                                            <tr>
                                                <td>GRADE LEVEL</td>
                                                <td>: '.$selectedgradelevel.'</td>
                                                <td>ADMISSION STATUS</td>
                                                <td>: '.$selectedstudentstatus.'</td>
                                                <td>STUDENT TYPE</td>
                                                <td>: '.$selectedstudenttype.'</td>
                                            </tr>
                                            <tr>
                                                <td>SECTION</td>
                                                <td>: '.$selectedsection.'</td>
                                                <td>MOL</td>
                                                <td>: '.$selectedmode.'</td>
                                                <td>ENROLLMENT PERIOD</td>
                                                <td>: '.$selecteddate.'</td>
                                            </tr>
                                        </table>
                                        <table style="font-size: 11px;margin-top: 5px;">
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </table>
                            ';
                            $table='';
                                
                            if(collect($records[0]->sections)->count()>0)
                            {
                                foreach($records[0]->sections as $eachsection)
                                {
                                    $studcount = 1;
                                    
                                $table.='<table style="width: 100%; text-align: center; font-size: 10px;  border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;" >';
                                    $table.='<tr>
                                                <td style="text-align: left;" colspan="5">'.$eachsection->levelname.' - '.$eachsection->sectionname.'</td>
                                            </tr>';

                                    if(collect($records[0]->students)->where('sectionid', $eachsection->id)->count()>0)
                                    {
                                        foreach(collect($records[0]->students)->where('sectionid', $eachsection->id)->values() as $eachsdtudsection)
                                        {
                                            $table.='<tr>
                                                        <td style="text-align: center; width: 5%;">'.$studcount.'</td>
                                                        <td style="text-align: left; width: 45%;">'.$eachsdtudsection->lastname.', '.$eachsdtudsection->firstname.' '.$eachsdtudsection->suffix.' '.$eachsdtudsection->middlename.'</td>
                                                        <td style="text-align: left; width: 50%;" colspan="3">'.$eachsdtudsection->gender.'</td>
                                                    </tr>';
                                            $studcount+=1;
                                        }
                                        $table.='<tr>
                                                    <td style="text-align: left;" colspan="5">&nbsp;</td>
                                                </tr>';
                                        $table.='<tr>
                                                    <td style="text-align: left;" colspan="2"></td>
                                                    <td style="text-align: left;">Total Male : '.collect($records[0]->students)->where('sectionid', $eachsection->id)->where('gender','MALE')->count().'</td>
                                                    <td style="text-align: left;">Total Female : '.collect($records[0]->students)->where('sectionid', $eachsection->id)->where('gender','FEMALE')->count().'</td>
                                                    <td style="text-align: left;">Total # : '.collect($records[0]->students)->where('sectionid', $eachsection->id)->count().'</td>
                                                </tr>';
                                    }else{
                                        $table.='<tr>
                                                    <td style="text-align: left;" colspan="5">No students enrolled</td>
                                                </tr>';
                                    }
                                    $table.='</table>';
                                    $table.='<div style="width: 100%;"></div>';
                                }
                            }
                            // $table.='<tr>
                            //             <td style="border: 1px solid black; text-align: right;">TOTAL</td>
                            //             <td style="border: 1px solid black;">'.collect($records[0]->sections)->sum('countmale').'</td>
                            //             <td style="border: 1px solid black;">'.collect($records[0]->sections)->sum('countfemale').'</td>
                            //             <td style="border: 1px solid black;">'.collect($records[0]->sections)->sum('total').'</td>
                            //         </tr>';
    
                            $pdf->writeHTML($header, true, false, false, false, '');
                            $pdf->writeHTML($table, true, false, false, false, '');
                            
                            // ---------------------------------------------------------
                            //Close and output PDF document
                            $pdf->Output('Enrollment Summary.pdf', 'I');

                            exit;

                        }
                    }
                }

            }

        }

    }
}

class MYPDFSummary extends TCPDF {

    //Page header
    public function Header() {
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.$schoollogo->picurl; 
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),15,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'Students Summary', false, false, false, $reseth=true, $align='L', $autopadding=true);
        // Ln();
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class DCCFormatCollegeEnrollees extends TCPDF {
    public function Header() {
        $this->Cell(0, 10, date('m/d/Y'), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 20, 'Page: '.$this->getAliasNumPage(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // .' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        // $this->SetY(-15);
        // // Set font
        // $this->SetFont('helvetica', 'I', 8);
        // // Page number
        // $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // $this->Cell(0, 10, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}