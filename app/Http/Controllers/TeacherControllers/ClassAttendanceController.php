<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;
use Session;
// use App\SchoolYear;
// use App\GradeLevel;
// use App\Student;
// use App\Attendance;
use App\AttendanceBySubject;
use \Carbon\Carbon;
use TCPDF;
// use App\Section;
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.$schoollogo->picurl; 
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),15,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'Attendance Report', false, false, false, $reseth=true, $align='L', $autopadding=true);
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
class ClassAttendanceController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        if(!$request->has('action'))
        {
            return view('teacher.classattendance.advisory.index');
            
        }else{
            
            
            $sem = DB::table('semester')
            ->where('isactive','1')
            ->get();
            if($request->has('action'))
            {
                if($request->has('semid'))
                {
                    $sem = DB::table('semester')
                        ->where('id',$request->get('semid'))
                        ->get();
                }
                if($request->get('syid') == null)
                {
                    $syid = DB::table('sy')
                                    ->where('isactive','1')
                                    ->first();
                }else{
                    $syid = DB::table('sy')
                                    ->where('id',$request->get('syid'))
                                    ->first();
                }
            }else{
                $syid = DB::table('sy')
                                ->where('isactive','1')
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
                ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
                ->join('sections','sectiondetail.sectionid','=','sections.id')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('teacher.userid',auth()->user()->id)
                ->where('sectiondetail.syid',$syid->id)
                ->where('sectiondetail.deleted','0')
                ->where('gradelevel.deleted','0')
                ->orderBy('sortid','asc')
                ->get();

                
            if(Session::get('currentPortal') == 1)
            {
                $collectsections = collect($collectsections)->where('userid', auth()->user()->id)->values();
            }
            
            if($request->has('acadprogid'))
            {
                if($request->get('acadprogid') == 0)
                {
                    $collectsections = collect($collectsections)->where('acadprogid', '!=','5')->values();
                }elseif($request->get('acadprogid') == 5)
                {
                    $collectsections = collect($collectsections)->where('acadprogid', 5)->values();
                }
            } 
            
            $sections = array();
            if(count($collectsections)>0){
                foreach($collectsections as $eachsection)
                {
                    $semester = 0;
                    if(strtolower($eachsection->acadprogcode) == 'shs')
                    {
                        if($sem[0]->id == 1)
                        {
                            $eachsection->semester = 1;
                            array_push($sections, $eachsection);
                        }
                        elseif($sem[0]->id == 2)
                        {                    
                            foreach(collect(DB::table('semester')->where('deleted','0')->get())->where('id',2)->values() as $eachsem)
                            {
                                $pushsection = (object)[
                                    'id'        => $eachsection->id,
                                    'userid'        => $eachsection->userid,
                                    'levelid'        => $eachsection->levelid,
                                    'sortid'        => $eachsection->sortid,
                                    'levelname'        => $eachsection->levelname,
                                    'sectionid'        => $eachsection->sectionid,
                                    'sectionname'        => $eachsection->sectionname,
                                    'progname'        => $eachsection->progname,
                                    'acadprogid'        => $eachsection->acadprogid,
                                    'acadprogcode'        => $eachsection->acadprogcode,
                                    'semester'        => $eachsem->id
                                ];
                                array_push($sections, $pushsection);
                            }
                        }
                        
                    }else{
                        $eachsection->semester = $semester;
                        array_push($sections, $eachsection);
                    }
                }
            }
            
            if(count($sections)>0){
                foreach($sections as $section)
                {
                    if(strtolower($section->acadprogcode) == 'shs')
                    {
                        $numberofstudents = Db::table('studinfo')
                            ->select('studinfo.*','sh_enrolledstud.studstatus as enrolledstudstatus')
                            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                            ->where('sh_enrolledstud.sectionid', $section->sectionid)
                            ->where('sh_enrolledstud.levelid', $section->levelid)
                            ->where('sh_enrolledstud.studstatus','!=','0')
                            ->where('sh_enrolledstud.studstatus','!=','6')
                            ->where('studinfo.deleted','0')
                            ->where('sh_enrolledstud.deleted','0')
                            ->where('sh_enrolledstud.syid',$request->get('syid'))
                            ->where('sh_enrolledstud.semid',$request->get('semid'))
                            ->orderBy('lastname','asc')
                            ->get();
                            
                    }else{
                        $numberofstudents = Db::table('studinfo')
                            ->select('enrolledstud.studstatus as enrolledstudstatus')
                            ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                            ->where('enrolledstud.sectionid', $section->sectionid)
                            ->where('enrolledstud.studstatus', '!=','0')
                            ->where('enrolledstud.studstatus', '!=','6')
                            ->where('studinfo.deleted','0')
                            ->where('enrolledstud.deleted','0')
                            ->where('enrolledstud.syid',$request->get('syid'))
                            ->orderBy('lastname','asc')
                            ->get();
                    }
                    $section->numberofenrolled = count(collect($numberofstudents)->where('enrolledstudstatus','1'));
                    $section->numberoflateenrolled =  count(collect($numberofstudents)->where('enrolledstudstatus','2'));
                    $section->numberoftransferredin =  count(collect($numberofstudents)->where('enrolledstudstatus','4'));
                    $section->numberoftransferredout =  count(collect($numberofstudents)->where('enrolledstudstatus','5'));
                    $section->numberofdroppedout =  count(collect($numberofstudents)->where('enrolledstudstatus','3'));
    
                    // return count($numberofstudents);
                    $section->numberofstudents = count($numberofstudents);
                    // if(count($numberofstudents)>0)
                    // {
                    //     array_push($sections, (object)$section);
                    // }
                }
            }
            // else{
            return view('teacher.classattendance.advisory.sections')
                ->with('semid',$request->get('semid'))
                ->with('syid',$request->get('syid'))
                ->with('sections',collect($sections)->unique());
        }                           
        
    }
    public function viewsection_v1(Request $request)
    {
        // return $request->all();
        $syid = Db::table('sy')
            ->where('isactive','1')
            ->first();
        $sectionid = $request->get('sectionid');
        $sectioninfo = DB::table('sections')
            ->where('id', $sectionid)
            ->first();
        $gradelevelinfo = DB::table('gradelevel')
            ->select('gradelevel.*','academicprogram.acadprogcode')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id', $request->get('levelid'))
            ->first();
        // return collect($gradelevelinfo);
        $sem = DB::table('semester')
        ->where('isactive','1')
        ->first();      
        if($gradelevelinfo)
        {
            if(strtolower($gradelevelinfo->acadprogcode) != 'shs'){
                $students = DB::table('enrolledstud')
                            ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.dob','studinfo.gender','studinfo.rfid','studinfo.studstatus','studentstatus.description','enrolledstud.promotionstatus','enrolledstud.dateenrolled')
                            ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                            ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                            ->where('enrolledstud.sectionid', $sectionid)
                            // ->whereIn('enrolledstud.studstatus', [1,2,4])
                            // ->where('enrolledstud.levelid', $sectionid)
                            ->where('enrolledstud.deleted',0)
                            ->where('studinfo.deleted','0')
                            ->where('enrolledstud.studstatus','!=',0)
                            ->where('enrolledstud.studstatus','!=',6)
                            ->where('studinfo.studstatus','!=',6)
                            // ->where('enrolledstud.promotionstatus',0)
                            ->where('enrolledstud.syid',$syid->id)
                            ->distinct()
                            ->get();
            }else{
                $students = DB::table('sh_enrolledstud')
                            ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.dob','studinfo.gender','studinfo.rfid','studinfo.studstatus','studentstatus.description','sh_enrolledstud.promotionstatus','sh_enrolledstud.dateenrolled')
                            ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                            ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                            ->where('sh_enrolledstud.sectionid', $sectionid)
                            ->where('sh_enrolledstud.deleted',0)
                            ->where('studinfo.deleted','0')
                            // ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                            ->where('sh_enrolledstud.studstatus','!=',0)
                            ->where('sh_enrolledstud.studstatus','!=',6)
                            ->where('studinfo.studstatus','!=',6)
                            // ->where('sh_enrolledstud.promotionstatus',0)
                            ->where('sh_enrolledstud.syid',$syid->id)
                            ->where('sh_enrolledstud.semid',$sem->id)
                            ->distinct()
                            ->get();
            }
        }else{
            $students = array();
        }
        // return $students;
        $attendance = array();

        $mutable = Carbon::now();

        $tdate = $mutable->format('Y-m-d');
        // return $tdate;
        $date = substr($tdate, 8);
        

        $getAttendance = array();

        $countPresent = array();

        $countLate = array();

        $countHalfDay = array();

        $countAbsent = array();
        if(count($students)==0){
            return view('teacher.classattendance.advisory.classattendance')
                    ->with('section',$sectioninfo)
                    ->with('gradelevel',$gradelevelinfo)
                    ->with('message','Attendance is not available!');
        }
        else{
            
            $checkdate = 0;

            foreach($students as $student){
                
                $studentAttendance = DB::table('studattendance')
                    ->where('studid',$student->id)
                            ->where('tdate',$tdate)
                            ->distinct()
                            ->get();
                            
                if(count($studentAttendance) == 0){

                    $showAttendance = array();
                    
                    array_push($showAttendance,(object)array(
                        'present'           => '1',
                        'absent'            => '0',
                        'tardy'             => '0',
                        'cc'                => '0',
                        'remarks'           => "",
                        'lastname'          => $student->lastname,
                        'firstname'         => $student->firstname,
                        'middlename'        => $student->middlename,
                        'id'                => $student->id,
                        'tdate'             => $tdate,
                        'studstatus'        => $student->studstatus,
                        'description'        => $student->description,
                        'gender'            => $student->gender,
                        'promotionstatus'   => $student->promotionstatus
                    ));
                    
                }
                else{
                    $checkdate+=1;
                    $showAttendance = DB::table('studattendance')
                                ->select(
                                    'studattendance.present',
                                    'studattendance.absent',
                                    'studattendance.tardy',
                                    'studattendance.cc',
                                    'studattendance.remarks',
                                    'studinfo.lastname',
                                    'studinfo.firstname',
                                    'studinfo.middlename',
                                    'studinfo.id',
                                    'studattendance.tdate',
                                    'studinfo.studstatus',
                                    'studinfo.gender',
                                    'sh_enrolledstud.promotionstatus',
                                    'studentstatus.description'
                                )
                                ->join('studinfo','studinfo.id','=','studattendance.studid')
                                ->join('sh_enrolledstud','studattendance.studid','=','sh_enrolledstud.studid')
                                ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                                ->where('sh_enrolledstud.studid',$student->id)
                                ->where('sh_enrolledstud.sectionid',$sectionid)
                                // ->where('sh_enrolledstud.levelid',$section[0]->levelid)
                                ->where('tdate',$tdate)
                                ->distinct()
                                ->get();
                    // return $showAttendance;
                    if(count($showAttendance)==0){
                        $showAttendance = DB::table('studattendance')
                                ->select(
                                    'studattendance.present',
                                    'studattendance.absent',
                                    'studattendance.tardy',
                                    'studattendance.cc',
                                    'studattendance.remarks',
                                    'studinfo.lastname',
                                    'studinfo.firstname',
                                    'studinfo.middlename',
                                    'studinfo.id',
                                    'studattendance.tdate',
                                    'studinfo.studstatus',
                                    'studinfo.gender',
                                    'enrolledstud.promotionstatus',
                                    'studentstatus.description'
                                )
                                ->join('studinfo','studinfo.id','=','studattendance.studid')
                                ->join('enrolledstud','studattendance.studid','=','enrolledstud.studid')
                                ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                                ->where('enrolledstud.studid',$student->id)
                                ->where('enrolledstud.sectionid',$sectionid)
                                ->where('tdate',$tdate)
                                ->distinct()
                                ->get();
                                // return $showAttendance;
                    }
                    if(count($showAttendance)==0){

                        $showAttendance = array();

                        array_push($showAttendance,(object)array(
                            'present'           => '1',
                            'absent'            => '0',
                            'tardy'             => '0',
                            'cc'                => '0',
                            'remarks'           => "",
                            'lastname'          => $student->lastname,
                            'firstname'         => $student->firstname,
                            'middlename'        => $student->middlename,
                            'id'                => $student->id,
                            'tdate'             => $tdate,
                            'studstatus'        => $student->studstatus,
                            'description'        => $student->description,
                            'gender'            => $student->gender,
                            'promotionstatus'   => $student->promotionstatus
                        ));
                    }
                }
                if(count($showAttendance)>0){
                    array_push($getAttendance,$showAttendance[0]);
                }
                // return $showAttendance;

                
            }
                // return count($getAttendance);
    
            $status = collect($getAttendance)->sortBy('lastname', SORT_NATURAL|SORT_FLAG_CASE);
            
            $present = $status->where('present', 1);

            array_push($countPresent,$present->count());

            $late = $status->where('tardy', 1);

            array_push($countLate,$late->count());

            $halfday = $status->where('cc', 1);

            array_push($countHalfDay,$halfday->count());

            $absent = $status->where('absent', 1);

            array_push($countAbsent,$absent->count());

            $getAttendance = collect($getAttendance)->sortBy('lastname', SORT_NATURAL|SORT_FLAG_CASE);
            
            return view('teacher.classattendance.advisory.classattendance_v1')
                    ->with('section',$sectioninfo)
                    ->with('gradelevel',$gradelevelinfo)
                    ->with('attendance',$getAttendance)
                    ->with('present',$countPresent)
                    ->with('late',$countLate)
                    ->with('halfday',$countHalfDay)
                    ->with('date',$tdate)
                    ->with('checkdate',$checkdate)
                    ->with('absent',$countAbsent);
        }
    }
    public function viewsection_v2(Request $request)
    {
        $sectioninfo = DB::table('sections')
            ->where('id', $request->get('sectionid'))
            ->first();
        $gradelevelinfo = DB::table('gradelevel')
            ->select('gradelevel.*','academicprogram.acadprogcode')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id', $request->get('levelid'))
            ->first();
        $schoolyears = Db::table('sy')
            // ->where('deleted','0')
            ->get();

        $semesters = Db::table('semester')
            // ->where('deleted','0')
            ->get();

        return view('teacher.classattendance.advisory.classattendance_v2')
                ->with('schoolyears',$schoolyears)
                ->with('semesters',$semesters)
                ->with('sectioninfo',$sectioninfo)
                ->with('gradelevelinfo',$gradelevelinfo);
    }
    public function viewsection_v3(Request $request)
    {
        $sectioninfo = DB::table('sections')
            ->where('id', $request->get('sectionid'))
            ->first();
        $gradelevelinfo = DB::table('gradelevel')
            ->select('gradelevel.*','academicprogram.acadprogcode')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id', $request->get('levelid'))
            ->first();
        $schoolyears = Db::table('sy')
            // ->where('deleted','0')
            ->get();

        $semesters = Db::table('semester')
            // ->where('deleted','0')
            ->get();

        // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjchssi'|| strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
        // {
            return view('teacher.classattendance.advisory.classattendance_hccsi')
                    ->with('syid',$request->get('syid'))
                    ->with('semid',$request->get('semid'))
                    ->with('schoolyears',$schoolyears)
                    ->with('semesters',$semesters)
                    ->with('sectioninfo',$sectioninfo)
                    ->with('gradelevelinfo',$gradelevelinfo);
        // }else{
        //     return view('teacher.classattendance.advisory.classattendance_v3')
        //             ->with('syid',$request->get('syid'))
        //             ->with('semid',$request->get('semid'))
        //             ->with('schoolyears',$schoolyears)
        //             ->with('semesters',$semesters)
        //             ->with('sectioninfo',$sectioninfo)
        //             ->with('gradelevelinfo',$gradelevelinfo);
        // }

    }
    public function viewsection_v4 (Request $request)
    {
        // return $request->all();
        $sectioninfo = DB::table('sections')
            ->where('id', $request->get('sectionid'))
            ->first();
        $gradelevelinfo = DB::table('gradelevel')
            ->select('gradelevel.*','academicprogram.acadprogcode')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id', $request->get('levelid'))
            ->first();
        $schoolyears = Db::table('sy')
            // ->where('deleted','0')
            ->get();

        $semesters = Db::table('semester')
            // ->where('deleted','0')
            ->get();

		$sectionid      = $request->get('sectionid');
        $strandid      = $request->get('strandid');
        $semid      = $request->get('semid');
		$syid      = $request->get('syid');
        $levelid      = $request->get('levelid');
        $selectedmonth  = $request->get('selectedmonth');
	

        // $setup = DB::table('sf2_setup')
        //     // ->where('teacherid', $teacherid)
        //     ->select('sf2_setup.*','sh_strand.strandname','sh_strand.strandcode')
        //     ->leftJoin('sh_strand','sf2_setup.strandid','=','sh_strand.id')
        //     ->where('sf2_setup.deleted','0')
        //     ->where('sf2_setup.syid', $request->get('syid'))
        //     ->where('sf2_setup.sectionid', $request->get('sectionid'))
        //     ->where('sf2_setup.month', $selectedmonth)
        //     ->where('sf2_setup.year', $currentyearnum)
        //     ->get();

        // $setup_numdays = DB::table('studattendance_setup')
        //     ->where('syid', $request->get('syid'))
        //     ->where('levelid', $request->get('levelid'))
        //     ->where('month', $setup_numdaymonth)
        //     ->where('year', $currentyearnum)
        //     ->where('deleted', 0)
        //     ->first();
			
		

        if(!$request->has('action'))
        {
            $strands = array();
            if($request->get('levelid') > 13)
            {
                $strands = DB::table('sh_enrolledstud')
                    ->select('sh_strand.*')
                    ->join('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                    ->where('levelid', $request->get('levelid'))
                    ->where('sectionid', $request->get('sectionid'))
                    ->where('syid', $request->get('syid'))
                    ->where('semid', $request->get('semid'))
                    ->where('sh_enrolledstud.deleted','0')
                    ->whereIn('studstatus',[1,2,4])
                    ->distinct()
                    ->get();

            }
	
            return view('teacher.classattendance.advisory.classattendance_v4')
                    ->with('syid',$request->get('syid'))
                    ->with('semid',$request->get('semid'))
                    ->with('schoolyears',$schoolyears)
                    ->with('strands',$strands)
                    ->with('semesters',$semesters)
                    ->with('sectioninfo',$sectioninfo)
                    ->with('gradelevelinfo',$gradelevelinfo);
        }else{
            $calendar = '';
            $list=array();
            $today = date("d"); // Current day
            $month = $request->get('selectedmonth');
            $year =  $request->get('selectedyear');
            $selecteddates = array();
            function draw_calendar($month,$year,$calendar,$datesselected){
                $headings = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
                
                /* draw table */
                $calendar .= '<table class="table-bordered" style="width: 100%; font-size: 15px !important; text-align: center !important;">';
            
                /* table headings */
                $calendar .= '<tr class="calendar-row"><td class="calendar-day-head" style="padding: 5px !important; vertical-align: middle !important; border: 2px solid #ddd;">'.implode('</td><td class="calendar-day-head" style=" vertical-align: middle !important; border: 2px solid #ddd;">',$headings).'</td></tr>';
                
            
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
                    $calendar.= '<td class="calendar-day-np" style="padding: 5px !important; vertical-align: middle !important; border: 2px solid #ddd;"> </td>';
                    $days_in_this_week++;
                endfor;
            
                /* keep going with days.... */
                for($list_day = 1; $list_day <= $days_in_month; $list_day++):
                    $classselected = '';
                    if (in_array($list_day, $datesselected)) {
                        //if(strtolower(date('l', strtotime($year.'-'.$month.'-'.$list_day))) != 'saturday' && strtolower(date('l', strtotime($year.'-'.$month.'-'.$list_day))) != 'sunday')
                        //{
                            $classselected = 'selected-date';
                        //}
                    }
                    $calendar.= '<td class="calendar-day '.$classselected.'" data-id="'.$list_day.'" style="padding: 5px !important; vertical-align: middle !important; border: 2px solid #ddd;">';
                        /* add in the day number */
                        $calendar.= $list_day;
            
                        /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
                        // $calendar.= str_repeat('<p> </p>',2);
                        
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
                        $calendar.= '<td class="calendar-day-np" style="padding: 5px !important; vertical-align: middle !important; border: 2px solid #ddd;"> </td>';
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
            $calendar.= '<h6><center>' . date('F Y', strtotime($year.'-'.$month)) . '</center></h6>';
            
            if($request->get('action') == 'getsetup')
            {

                $setup = DB::table('sf2_setup')
							->select('sf2_setup.*','sh_strand.strandname','sh_strand.strandcode')
							->leftJoin('sh_strand','sf2_setup.strandid','=','sh_strand.id')
							->where('sf2_setup.deleted','0')
							->where('sf2_setup.syid', $request->get('syid'))
							->where('sf2_setup.sectionid', $request->get('sectionid'))
							->where('sf2_setup.month', $request->get('selectedmonth'))
							->where('sf2_setup.year', $request->get('selectedyear'))
							->get();

				//school days setup
				$teacherid = Db::table('teacher')
							->where('userid', auth()->user()->id)
							->first()
							->id;

				$schooldayssetup = \App\Http\Controllers\SuperAdminController\SchoolDaysController::attendance_setup_list($syid,$levelid,0,$selectedmonth);
		
				
	
				if(count($schooldayssetup) > 0){

					if(count($setup) == 0){
						$setupid = DB::table('sf2_setup')
							->insertGetId([
								'teacherid'             => $teacherid,
								'month'                 => $request->get('selectedmonth'),
								'syid'                  => $request->get('syid'),
								'sectionid'             => $request->get('sectionid'),
								'year'                  => $request->get('selectedyear'),
								'strandid'              => $request->get('selectedstrand'),
								'createdby'             => auth()->user()->id,
								'createddatetime'       => date('Y-m-d H:i:s'),
								'course'             => $request->get('tvlcourse')
							]);

					}else{
						$setupid = $setup[0]->id;
					}

					foreach($schooldayssetup[0]->dates as $item){
						DB::table('sf2_setupdates')
								->updateOrInsert(
									['setupid'=>$setupid,'dates'=>$item],
									[
										'setupid'=>$setupid,
										'dates'=>$item,
										'deleted'=>0,
										'createdby'=>auth()->user()->id,
										'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
										'updatedby'=>auth()->user()->id,
										'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')					
									]
						);
					}
					
					//remove other dates
					DB::table('sf2_setupdates')
						->where('setupid',$setupid)
						->whereNotIn('dates',$schooldayssetup[0]->dates)
						->update([
							'deleted'=>1,
							'deletedby'=>auth()->user()->id,
							'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
						]);
					
					$setup = DB::table('sf2_setup')
								->select('sf2_setup.*','sh_strand.strandname','sh_strand.strandcode')
								->leftJoin('sh_strand','sf2_setup.strandid','=','sh_strand.id')
								->where('sf2_setup.deleted','0')
								->where('sf2_setup.syid', $request->get('syid'))
								->where('sf2_setup.sectionid', $request->get('sectionid'))
								->where('sf2_setup.month', $request->get('selectedmonth'))
								->where('sf2_setup.year', $request->get('selectedyear'))
								->get();
				}
				//school days setup

                if($request->get('selectedstrand')>0)
                {
                    $setup = collect($setup)->where('strandid', $request->get('selectedstrand'))->all();
                }
                
                    
                $days = array();

                if(count($setup)==0)
                {
                    function dates_month($selectedmonth, $selectedyear) {
                        $num = cal_days_in_month(CAL_GREGORIAN, $selectedmonth, $selectedyear);
                        $dates_month = array();
                    
                        for ($i = 1; $i <= $num; $i++) {
                            $mktime = mktime(0, 0, 0, $selectedmonth, $i, $selectedyear);
                            $date = date("d", $mktime);
                            if($date[0] == '0')
                            {
                                $date  = $date[1];
                            }
                            array_push($dates_month,$date);
                        }
                    
                        return $dates_month;
                    }
                    $selecteddates = dates_month($month, $year);
                    
                }else{

            
                    if(count($setup)>0)
                    {
                        foreach($setup as $sf2setup)
                        {
                            $sf2setupdates = DB::table('sf2_setupdates')
                                ->where('setupid', $sf2setup->id)
                                ->where('deleted','0')
                                ->orderBy('dates','asc')
                                ->get();

                            if(count($sf2setupdates)>0)
                            {
                                foreach($sf2setupdates as $eachsf2setupdate)
                                {
                                    $eachsf2setupdate->dates = date('d', strtotime($eachsf2setupdate->dates));
                                    if($eachsf2setupdate->dates[0] == '0')
                                    {
                                        $eachsf2setupdatedate  = $eachsf2setupdate->dates[1];
                                    }else{
                                        $eachsf2setupdatedate  = $eachsf2setupdate->dates;
                                    }
                                    array_push($selecteddates,$eachsf2setupdatedate);
                                }
                            }
                        }
                    }else{
                        $setup = [];
                    }
                    
                    

                }
                
                return view('teacher.classattendance.advisory.v4_results')
                    ->with('syid',$request->get('syid'))
                    ->with('semid',$request->get('semid'))
                    ->with('schoolyears',$schoolyears)
                    ->with('semesters',$semesters)
                    ->with('sectioninfo',$sectioninfo)
                    ->with('gradelevelinfo',$gradelevelinfo)
                    ->with('setup', $setup)
                    ->with('selecteddates', $selecteddates)
                    ->with('month', $month)
                    ->with('year', $year)
                    ->with('calendar', draw_calendar($month,$year, $calendar,$selecteddates));
            }elseif($request->get('action') == 'getattendance')
            {
                // return $request->all();
                $acadprogid = DB::table('gradelevel')
                    ->where('id', $request->get('levelid'))
                    ->first()->acadprogid;

                $students = array();
                if($acadprogid == 5)
                {
                    $studentsquery = DB::table('sh_enrolledstud')
                        ->select('studinfo.id','studinfo.sid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','sh_enrolledstud.studstatus','studentstatus.description','sh_enrolledstud.studstatdate','sh_enrolledstud.dateenrolled')
                        ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                        ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                        ->where('sh_enrolledstud.syid', $request->get('selectedschoolyear'))
                        ->where('sh_enrolledstud.strandid', $request->get('selectedstrand'))
                        ->where('sh_enrolledstud.semid', $request->get('selectedsemester'))
                        ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                        ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                        ->where('sh_enrolledstud.deleted','0')
                        ->where('sh_enrolledstud.studstatus','!=','0')
                        ->get();
                }else{
                    $studentsquery = DB::table('enrolledstud')
                        ->select('studinfo.id','studinfo.sid','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','enrolledstud.studstatus','studentstatus.description','enrolledstud.studstatdate','enrolledstud.dateenrolled')
                        ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                        ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                        ->where('enrolledstud.syid', $request->get('selectedschoolyear'))
                        ->where('enrolledstud.levelid', $request->get('levelid'))
                        ->where('enrolledstud.sectionid', $request->get('sectionid'))
                        ->where('enrolledstud.deleted','0')
                        ->where('enrolledstud.studstatus','!=','0')
                        ->get();

                }
                if(count($studentsquery)>0)
                {
                    foreach($studentsquery as $studquery)
                    {
                        if($studquery->studstatdate == null)
                        {                                
                            $studquery->studstatdate = $studquery->dateenrolled;
                        }
                        $studquery->sortname = $studquery->lastname.', '.$studquery->firstname.' '.$studquery->middlename.' '.$studquery->suffix;
                        $studquery->crashedout = 0;
                        if($studquery->studstatus == 1 || $studquery->studstatus == 2 || $studquery->studstatus == 4)
                        {
                            array_push($students, $studquery);
                        }else{
    
                            if($studquery->studstatdate == null)
                            {
                                array_push($students, $studquery);
                            }else{
                                if(date('Y-m', strtotime($studquery->studstatdate)) >= date('Y-m', strtotime($request->get('selectedyear').'-'.$request->get('selectedmonth'))))
                                {
                                    array_push($students, $studquery);
                                }else{
                                    
                                    $studquery->crashedout = 1;
                                    array_push($students, $studquery);
                                }
                            }
                        }
                    }
                }
                $students = collect($students)->sortBy('sortname');
                $selecteddates = array();
                if(count($request->get('dates'))>0)
                {
                    foreach($request->get('dates') as $eachdate)
                    {
                        array_push($selecteddates,date('Y-m-d', strtotime($request->get('selectedyear').'-'.$request->get('selectedmonth').'-'.$eachdate)));
                    }
                }
                
                $studids = collect($students)->pluck('id');
                
                $attendance = DB::table('studattendance')
                    // ->where('syid', $syid->id)
                    ->where('deleted','0')
                    ->whereIn('tdate',$selecteddates)
                    ->whereIn('studid',$studids)
                    ->get();
                    
                if(count($students)>0)
                {
					foreach($students as $student)
                    {
						foreach($selecteddates as $date)
						{
							$attstatus = collect($attendance)->where('studid', $student->id)->where('tdate', $date)->values();
							if(count($attstatus) == 0)
                            {
								DB::table('studattendance')
									->insert([
										'studid'        =>  $student->id,
										'syid'          =>  $request->get('selectedschoolyear'),
										'semid'         =>  $request->get('selectedsemester'),
										'present'       =>  1,
										'absent'        =>  0,
										'tardy'         =>  0,
										'cc'            =>  0,
										'attdate'       => date('d', strtotime($date)),
										'attday'        => date('d', strtotime($date)),
										'deleted'       => 0,
										'createddatetime'=> date('Y-m-d H:i:s'),
										'tdate'         => $date
									]);
							}
						}
					}
					
					$attendance = DB::table('studattendance')
									// ->where('syid', $syid->id)
									->where('deleted','0')
									->whereIn('tdate',$selecteddates)
									->whereIn('studid',$studids)
									->get();
									
					$taphistories = DB::table('taphistory')
									->where('deleted','0')
									->whereIn('tdate',$selecteddates)
									->whereIn('studid',$studids)
									->where('utype',7)
									->where('tapstate','IN')
									->select(
										'studid',
										'tdate',
										'ttime'
									)
									->get();
									
                    foreach($students as $student)
                    {
                        $student->sortname = $student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->suffix;
                        $att = array();
    
                        foreach($selecteddates as $date)
                        {
                            if(count($selecteddates) == 1)
                            {
                                if($student->studstatdate <= $date && ($student->studstatus != 1 && $student->studstatus != 2 && $student->studstatus != 4))
                                {
                                    $student->crashedout = 1;
                                }
                            }
                            $attstatus = collect($attendance)->where('studid', $student->id)->where('tdate', $date)->values();
                            // return collect($attstatus);
    
                            $status = "";
    
                            if(count($attstatus)>0)
                            {
                                    if($attstatus[0]->present == 1)
                                    {
                                        $status = 'PRESENT';
                                    }
                                    if($attstatus[0]->absent == 1)
                                    {
                                        $status = 'ABSENT';
                                    }
                                    if($attstatus[0]->tardy == 1)
                                    {
                                        $status = 'LATE';
                                    }
                                    if($attstatus[0]->cc == 1)
                                    {
                                        $status = 'CC';
                                    }
                                    // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjchssi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                                    // {
                                        try{
                                            if($attstatus[0]->presentam == 1)
                                            {
                                                $status = 'AM PRESENT';
                                            }
                                            if($attstatus[0]->presentpm == 1)
                                            {
                                                $status = 'PM PRESENT';
                                            }
                                            if($attstatus[0]->absentam == 1)
                                            {
                                                $status = 'AM ABSENT';
                                            }
                                            if($attstatus[0]->absentpm == 1)
                                            {
                                                $status = 'PM ABSENT';
                                            }
                                            if($attstatus[0]->lateam == 1)
                                            {
                                                $status = 'AM LATE';
                                            }
                                            if($attstatus[0]->latepm == 1)
                                            {
                                                $status = 'PM LATE';
                                            }
                                            if($attstatus[0]->ccam == 1)
                                            {
                                                $status = 'AM CC';
                                            }
                                            if($attstatus[0]->ccpm == 1)
                                            {
                                                $status = 'PM CC';
                                            }
        
                                        }catch(\Exception $error)
                                        {}
                                    // }
                            }else{
								
								
							}
    
							$ttime = null;
							$taphistory = collect($taphistories)->where('studid', $student->id)->where('tdate', $date)->first();
							if(isset($taphistory)){
								$ttime = \Carbon\Carbon::create($taphistory->ttime)->isoFormat('hh:mm A');
							}
							
                            array_push($att, (object)array(
								'ttime'		=> $ttime,
                                'tdate'     =>    $date,
                                'status'    => $status
                            ));
                        }
    
                        $student->attendance = $att;
						
						//return $student->attendance;
                    }
                }

                $teacherid = DB::table('teacher')
                    ->where('userid', auth()->user()->id)
                    ->where('deleted','0')
                    ->first()->id;
                    
                $setup = DB::table('sf2_setup')
                    ->where('teacherid', $teacherid)
                    ->select('sf2_setup.*','sh_strand.strandname','sh_strand.strandcode')
                    ->leftJoin('sh_strand','sf2_setup.strandid','=','sh_strand.id')
                    ->where('sf2_setup.deleted','0')
                    ->where('sf2_setup.syid', $request->get('selectedschoolyear'))
                    ->where('sf2_setup.sectionid', $request->get('sectionid'))
                    ->where('sf2_setup.month', $request->get('selectedmonth'))
                    ->where('sf2_setup.year', $request->get('selectedyear'))
                    ->get();
                    
                $setup = collect($setup)->where('teacherid',$teacherid)->values();
                    
                if(count($setup)>0)
                {
                    foreach($setup as $sf2setup)
                    {
                        $sf2setup->dates = DB::table('sf2_setupdates')
                            ->where('setupid', $sf2setup->id)
                            ->where('deleted','0')
                            ->orderBy('dates','asc')
                            ->get();
                    }
                }else{
                    $setup = [];
                }
                
                $locksf2 = 0;
                if(count($setup) > 0)
                {
                    if(DB::getSchemaBuilder()->hasTable('sf2_setuplock'))
                    {
                        // return $setup;
                        $lockstatus = DB::table('sf2_setuplock')
                            ->where('setupid',$setup[0]->id)
                            ->where('deleted','0')
                            ->first();
        
                        // return collect($lockstatus);
                        if($lockstatus)
                        {
        
                            $locksf2 = $lockstatus->lockstatus;
                        }
                    }
        
                }
                $dates = array();
                foreach($request->get('dates') as $date)
                {
                    array_push($dates, (object)array(
                        'date'  => date('Y-m-d',strtotime($year.'-'.$month.'-'.$date)),
                        'datestr'  => date('M d',strtotime($year.'-'.$month.'-'.$date)),
                        'day'  => date('D',strtotime($year.'-'.$month.'-'.$date))
                    ));
                }
                
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                {
                    $students = collect($students)->where('crashedout','0')->values()->all();
                }
                return view('teacher.classattendance.advisory.v4_attendance')
                        ->with('locksf2', $locksf2)
                        ->with('dates', $dates)
                        ->with('students', $students);
                
            }
        }

    }
    public function showtable(Request $request)
    {
        $syid = Db::table('sy')
            ->where('id',$request->get('selectedschoolyear'))
            ->first();
        $semid = $request->get('selectedsemester');

        $sectionid = $request->get('sectionid');

        $sectioninfo = DB::table('sections')
            ->where('id', $sectionid)
            ->first();

        $gradelevelinfo = DB::table('gradelevel')
            ->select('gradelevel.*','academicprogram.acadprogcode')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id', $request->get('levelid'))
            ->first();
            
        $sem = DB::table('semester')
            ->where('isactive','1')
            ->first();   

        $students = array();
        
        if($gradelevelinfo)
        {
            if(strtolower($gradelevelinfo->acadprogcode) != 'shs'){
                $studentsquery = DB::table('enrolledstud')
                            ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','enrolledstud.studstatus','studinfo.studstatdate','studentstatus.description','enrolledstud.promotionstatus','enrolledstud.dateenrolled')
                            ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                            ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                            ->where('enrolledstud.sectionid', $sectionid)
                            ->where('enrolledstud.deleted',0)
                            ->where('enrolledstud.levelid',$gradelevelinfo->id)
                            ->where('studinfo.deleted','0')
                            ->where('enrolledstud.studstatus','!=',0)
                            // ->whereIn('enrolledstud.studstatus', [1,2,4])
                            // ->whereIn('studinfo.studstatus', [1,2,4])
                            ->where('enrolledstud.studstatus','!=',6)
                            ->where('studinfo.studstatus','!=',6)
                            // ->where('studinfo.studstatus','!=',0)
                            ->where('enrolledstud.syid',$syid->id)
                            ->distinct()
                            ->orderBy('lastname','asc')
                            ->get();
            }else{
                $studentsquery = DB::table('sh_enrolledstud')
                            ->select('studinfo.id','studinfo.sid','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','sh_enrolledstud.studstatus','studinfo.studstatdate','studentstatus.description','sh_enrolledstud.promotionstatus','sh_enrolledstud.dateenrolled')
                            ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                            ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                            ->where('sh_enrolledstud.sectionid', $sectionid)
                            ->where('sh_enrolledstud.deleted',0)
                            ->where('sh_enrolledstud.levelid',$gradelevelinfo->id)
                            ->where('studinfo.deleted','0')
                            // ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                            // ->whereIn('studinfo.studstatus', [1,2,4])
                            ->where('sh_enrolledstud.studstatus','!=',0)
                            ->where('sh_enrolledstud.studstatus','!=',6)
                            ->where('studinfo.studstatus','!=',6)
                            ->where('sh_enrolledstud.syid',$syid->id)
                            ->where('sh_enrolledstud.semid',$semid)
                            ->orderBy('lastname','asc')
                            ->distinct()
                            ->get();
            }
            
            if(count($studentsquery)>0)
            {
                foreach($studentsquery as $studquery)
                {
                    $studquery->crashedout = 0;
                    if($studquery->studstatus == 1 || $studquery->studstatus == 2 || $studquery->studstatus == 4)
                    {
                        array_push($students, $studquery);
                    }else{

                        if($studquery->studstatdate == null)
                        {
                            array_push($students, $studquery);
                        }else{
                            if(date('Y-m', strtotime($studquery->studstatdate)) >= date('Y-m', strtotime($request->get('selectedyear').'-'.$request->get('selectedmonth'))))
                            {
                                array_push($students, $studquery);
                            }else{
                                
                                $studquery->crashedout = 1;
                                array_push($students, $studquery);
                            }
                        }
                    }
                }
            }
        }else{
            $students = array();
        }
		
		
		
        
        if($request->get('version') == 2)
        {
            
            $attendance = array();
    
            if($request->has('selecteddate'))
            {
                $tdate = $request->get('selecteddate');
            }else{
                $mutable = Carbon::now();
                $tdate = $mutable->format('Y-m-d');
            }
            
            $date = substr($tdate, 8);            
    
            $checkdate = 0;

            if(count($students)>0)
            {

                foreach($students as $student){
                    
                    $studentAttendance = DB::table('studattendance')
                                ->where('studid',$student->id)
                                ->where('tdate',$tdate)
                                ->where('syid',$syid->id)
                                ->where('semid',$semid)
                                ->where('deleted','0')
                                ->distinct()
                                ->get();
                                
                    if(count($studentAttendance) == 0){
                        $student->present = 1;
                        $student->absent = 0;
                        $student->tardy = 0;
                        $student->cc = 0;
                        $student->halfdayshift = "";
                        $student->remarks = "";                        
                    }
                    else{
                        $checkdate+=1;
                        $student->present = $studentAttendance[0]->present;
                        $student->absent = $studentAttendance[0]->absent;
                        $student->tardy = $studentAttendance[0]->tardy;
                        $student->cc = $studentAttendance[0]->cc;
                        $student->halfdayshift = $studentAttendance[0]->halfdayshift;
                        $student->remarks = $studentAttendance[0]->remarks;  
                    }
                    
                }
            }
            
            $students = collect($students)->sortBy('lastname')->values()->all();

            $countmale = count(collect($students)->where('gender', 'MALE')->all());

            $countfemale = count(collect($students)->where('gender', 'FEMALE')->all());

            $countpresent = count(collect($students)->where('present', 1)->all());
    
            $countlate = count(collect($students)->where('tardy', 1)->all());
    
            $counthalfday = count(collect($students)->where('cc', 1)->all());
    
            $countabsent = count(collect($students)->where('absent', 1)->all());          
                
            return view('teacher.classattendance.advisory.attendancetable')
                    ->with('checkdate',$checkdate)
                    ->with('selecteddate',$tdate)
                    ->with('section',$sectioninfo)
                    ->with('gradelevel',$gradelevelinfo)
                    ->with('students', $students)
                    ->with('countmale', $countmale)
                    ->with('countfemale', $countfemale)
                    ->with('countpresent', $countpresent)
                    ->with('countlate', $countlate)
                    ->with('counthalfday', $counthalfday)
                    ->with('countabsent', $countabsent);
        }
        elseif($request->get('version') == 3)
        {
            $year = $request->get('selectedyear');
            $month = $request->get('selectedmonth');
            $dates = array();
            foreach($request->get('dates') as $date)
            {
                array_push($dates, (object)array(
                    'date'  => date('Y-m-d',strtotime($year.'-'.$month.'-'.$date)),
                    'datestr'  => date('M d',strtotime($year.'-'.$month.'-'.$date)),
                    'day'  => date('D',strtotime($year.'-'.$month.'-'.$date))
                ));
            }
            
            $studids = collect($students)->pluck('id');
            
            $attendance = DB::table('studattendance')
                // ->where('syid', $syid->id)
                ->where('deleted','0')
                ->whereBetween('tdate',[collect($dates)->first()->date,collect($dates)->last()->date])
                ->whereIn('studid',$studids)
                ->get();
                
            if(count($students)>0)
            {
                foreach($students as $student)
                {
                    $student->sortname = $student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->suffix;
                    $att = array();

                    foreach($dates as $date)
                    {
                        if(count($dates) == 1)
                        {
							if($student->studstatdate <= $date->date && ($student->studstatus != 1 && $student->studstatus != 2 && $student->studstatus != 4))
							{
								$student->crashedout = 1;
							}
						}
                        $attstatus = collect($attendance)->where('studid', $student->id)->where('tdate', $date->date)->values();
                        // return collect($attstatus);

                        $status = "";

                        if(count($attstatus)>0)
                        {
                                if($attstatus[0]->present == 1)
                                {
                                    $status = 'PRESENT';
                                }
                                if($attstatus[0]->absent == 1)
                                {
                                    $status = 'ABSENT';
                                }
                                if($attstatus[0]->tardy == 1)
                                {
                                    $status = 'LATE';
                                }
                                if($attstatus[0]->cc == 1)
                                {
                                    $status = 'CC';
                                }
                                // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjchssi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                                // {
                                    try{
                                        if($attstatus[0]->presentam == 1)
                                        {
                                            $status = 'AM PRESENT';
                                        }
                                        if($attstatus[0]->presentpm == 1)
                                        {
                                            $status = 'PM PRESENT';
                                        }
                                        if($attstatus[0]->absentam == 1)
                                        {
                                            $status = 'AM ABSENT';
                                        }
                                        if($attstatus[0]->absentpm == 1)
                                        {
                                            $status = 'PM ABSENT';
                                        }
                                        if($attstatus[0]->lateam == 1)
                                        {
                                            $status = 'AM LATE';
                                        }
                                        if($attstatus[0]->latepm == 1)
                                        {
                                            $status = 'PM LATE';
                                        }
                                        if($attstatus[0]->ccam == 1)
                                        {
                                            $status = 'AM CC';
                                        }
                                        if($attstatus[0]->ccpm == 1)
                                        {
                                            $status = 'PM CC';
                                        }
    
                                    }catch(\Exception $error)
                                    {}
                                // }
                        }

                        array_push($att, (object)array(
                            'tdate'     =>    $date->date,
                            'status'    => $status
                        ));
                    }

                    $student->attendance = $att;
                }
            }
            
            $teacherid = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->where('deleted','0')
                ->first()->id;
                
            $setup = DB::table('sf2_setup')
                ->where('teacherid', $teacherid)
                ->select('sf2_setup.*','sh_strand.strandname','sh_strand.strandcode')
                ->leftJoin('sh_strand','sf2_setup.strandid','=','sh_strand.id')
                ->where('sf2_setup.deleted','0')
                ->where('sf2_setup.syid', $syid->id)
                ->where('sf2_setup.sectionid', $sectionid)
                ->where('sf2_setup.month', $month)
                ->where('sf2_setup.year', $year)
                ->get();
                
            $setup = collect($setup)->where('teacherid',$teacherid)->values();
                
            if(count($setup)>0)
            {
                foreach($setup as $sf2setup)
                {
                    $sf2setup->dates = DB::table('sf2_setupdates')
                        ->where('setupid', $sf2setup->id)
                        ->where('deleted','0')
                        ->orderBy('dates','asc')
                        ->get();
                }
            }else{
                $setup = [];
            }
            
            $locksf2 = 0;
            if(count($setup) > 0)
            {
                if(DB::getSchemaBuilder()->hasTable('sf2_setuplock'))
                {
                    // return $setup;
                    $lockstatus = DB::table('sf2_setuplock')
                        ->where('setupid',$setup[0]->id)
                        ->where('deleted','0')
                        ->first();
    
                    // return collect($lockstatus);
                    if($lockstatus)
                    {
    
                        $locksf2 = $lockstatus->lockstatus;
                    }
                }
    
            }
            
            // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjchssi' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
            // {
                
                if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                {
                    $students = collect($students)->where('crashedout','0')->values()->all();
                }
                return view('teacher.classattendance.advisory.attendancetable_hccsi')
                        ->with('locksf2', $locksf2)
                        ->with('dates', $dates)
                        ->with('students', $students);
            // }else{
            //     return view('teacher.classattendance.advisory.attendancetable_v3')
            //             ->with('locksf2', $locksf2)
            //             ->with('dates', $dates)
            //             ->with('students', $students);
            // }
        }
    }
    public function submitattendance(Request $request)
    {
        // return $request->all();
        // return $request->get('datavalues');
        if($request->has('version') && $request->has('datavalues'))
        {
            if(count($request->get('datavalues'))>0)
            {
                foreach($request->get('datavalues') as $dataval)
                {
                    // return $dataval['studid'];
                    $checkifexists = DB::table('studattendance')
                        ->where('studid', $dataval['studid'])
                        // ->where('syid', $request->get('selectedschoolyear'))
                        ->whereDate('tdate', $dataval['tdate'])
                        ->where('deleted','0')
                        ->first();
                    // return collect($checkifexists);
                    $presentval = 0;
                    $absentval = 0;
                    $lateval = 0;
                    $ccval = 0;
                    if(strtolower($dataval['newstatus']) == 'present')
                    {
                        $presentval = 1;
                    }
                    if(strtolower($dataval['newstatus']) == 'absent')
                    {
                        $absentval = 1;
                    }
                    if(strtolower($dataval['newstatus']) == 'late')
                    {
                        $lateval = 1;
                    }
                    if(strtolower($dataval['newstatus']) == 'cc')
                    {
                        $ccval = 1;
                    }
                    
                    if($checkifexists)
                    {
                        if(strtolower($dataval['newstatus']) == 'none')
                        {
                            // return 'asdasd';
                            DB::table('studattendance')
                                ->where('id', $checkifexists->id)
                                ->update([
                                    'deleted'       =>  '1',
                                    'deleteddatetime'=> date('Y-m-d H:i:s')
                                ]);
                        }else{
                            
                            // try{
                                // return $ccval;
                                DB::table('studattendance')
                                    ->where('id', $checkifexists->id)
                                    ->update([
                                        'present'       =>  $presentval,
                                        'absent'        =>  $absentval,
                                        'tardy'         =>  $lateval,
                                        'cc'            =>  $ccval,
                                        'updateddatetime'=> date('Y-m-d H:i:s')
                                    ]);
                                    
                            // }catch(\Exception $error)
                            // {
                            //     return $error;
                            // }
                        }
                    }else{
                        DB::table('studattendance')
                            ->insert([
                                'studid'        =>  $dataval['studid'],
                                'syid'          =>  $request->get('selectedschoolyear'),
                                'semid'         =>  $request->get('selectedsemester'),
                                'present'       =>  $presentval,
                                'absent'        =>  $absentval,
                                'tardy'         =>  $lateval,
                                'cc'            =>  $ccval,
                                'attdate'       => date('d', strtotime($dataval['tdate'])),
                                'attday'        => date('d', strtotime($dataval['tdate'])),
                                'deleted'       => 0,
                                'createddatetime'=> date('Y-m-d H:i:s'),
                                'tdate'         => $dataval['tdate']
                            ]);
                    }
                }
            }

        }else{

            $selecteddate       = $request->get('selecteddate') ;
            $selectedschoolyear = $request->get('selectedschoolyear') ;
            $selectedsemester   = $request->get('selectedsemester') ;
            $selectedsection    = $request->get('selectedsection') ;
            $selectedgradelevel = $request->get('selectedgradelevel');

            if($request->has('attendance'))
            {
                $attendance = $request->get('attendance');
                
                foreach($attendance as $eachatt)
                {
                    $explode = explode('-',$eachatt);
    
                    $checkifexists = DB::table('studattendance')
                        ->where('studid',$explode[1])
                        ->where('tdate',$selecteddate)
                        ->where('syid',$selectedschoolyear)
                        ->where('semid',$selectedsemester)
                        ->distinct()
                        ->get();
                        
                    $halfdayshift = "";
                    $field        = "";
    
                    if(strpos($explode[0], 'am') !== false){
                        $field = 'cc';
                        $halfdayshift = 'am';
                    }elseif(strpos($explode[0], 'pm') !== false){
                        $field = 'cc';
                        $halfdayshift = 'pm';
                    }else{
                        $field = $explode[0];
                    }
                    
                    if(count($checkifexists) == 0)
                    {
                        DB::table('studattendance')
                            ->insert([
                                'studid'                => $explode[1],
                                'syid'                  => $selectedschoolyear,
                                'semid'                 => $selectedsemester,
                                'tdate'                 => $selecteddate,
                                $field                  => 1,
                                'halfdayshift'          => $halfdayshift,
                                'attday'                => date('d',strtotime($selecteddate)),
                                'attdate'               => date('d',strtotime($selecteddate)),
                                'createddatetime'       => date('Y-m-d H:i:s'),
                                'deleted'               => 0
                            ]);
                    }else{
                        DB::table('studattendance')
                            ->where('studid',$explode[1])
                            ->where('tdate',$selecteddate)
                            ->where('syid',$selectedschoolyear)
                            ->where('semid',$selectedsemester)
                            ->update([
                                'present'               => 0,
                                'tardy'                 => 0,
                                'cc'                    => 0,
                                'absent'                => 0,
                                'halfdayshift'          => '',
                                'updateddatetime'       => date('Y-m-d H:i:s'),
                                'deleted'               => 0
                            ]);
                        DB::table('studattendance')
                            ->where('studid',$explode[1])
                            ->where('tdate',$selecteddate)
                            ->where('syid',$selectedschoolyear)
                            ->where('semid',$selectedsemester)
                            ->update([
                                'attday'                => date('d',strtotime($selecteddate)),
                                'attdate'               => date('d',strtotime($selecteddate)),
                                $field           => 1,
                                'halfdayshift'      => $halfdayshift,
                                'updateddatetime'      => date('Y-m-d H:i:s'),
                                'deleted'               => 0
                            ]);
                    }
                }
            }
        }
        
    }
    public function deleteattendancecol(Request $request)
    {
        if($request->ajax())
        {
            $tdate          = $request->get('tdate');
            $studids        = json_decode($request->get('studids'));
            $levelid        = $request->get('levelid');
            $sectionid        = $request->get('sectionid');
            
            DB::table('studattendance')
                ->whereIn('studid',$studids)
                ->where('tdate',$tdate)
                ->update([
                    'deleted'               => 1,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
    }
    public function presentattendancecol(Request $request)
    {
        if($request->ajax())
        {
            $tdate          = $request->get('tdate');
            $studids        = json_decode($request->get('studids'));
            $levelid        = $request->get('levelid');
            $sectionid        = $request->get('sectionid');
            
            foreach($studids as $studid)
            {
                $checkifexists = DB::table('studattendance')
                    ->where('studid', $studid)
                    ->where('tdate', $tdate)
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    DB::table('studattendance')
                        ->where('studid', $studid)
                        ->where('tdate', $tdate)
                        ->where('deleted','0')
                        ->update([
                            'present'           => 1,
                            'absent'            => 0,
                            'tardy'             => 0,
                            'cc'                => 0,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studattendance')
                        ->insert([
                            'studid'            => $studid,
                            'syid'              => DB::table('sy')->where('isactive','1')->first()->id,
                            'semid'             => DB::table('semester')->where('isactive','1')->first()->id,
                            'present'           => 1,
                            'absent'            => 0,
                            'tardy'             => 0,
                            'cc'                => 0,
                            'attdate'           => date('d', strtotime($tdate)),
                            'attday'            => date('d', strtotime($tdate)),
                            'deleted'           => 0,
                            'tdate'             => $tdate,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
    }
    public function lateattendancecol(Request $request)
    {
        if($request->ajax())
        {
            $tdate          = $request->get('tdate');
            $studids        = json_decode($request->get('studids'));
            $levelid        = $request->get('levelid');
            $sectionid        = $request->get('sectionid');
            
            foreach($studids as $studid)
            {
                $checkifexists = DB::table('studattendance')
                    ->where('studid', $studid)
                    ->where('tdate', $tdate)
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    DB::table('studattendance')
                        ->where('studid', $studid)
                        ->where('tdate', $tdate)
                        ->where('deleted','0')
                        ->update([
                            'present'           => 0,
                            'absent'            => 0,
                            'tardy'             => 1,
                            'cc'                => 0,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studattendance')
                        ->insert([
                            'studid'            => $studid,
                            'syid'              => DB::table('sy')->where('isactive','1')->first()->id,
                            'semid'             => DB::table('semester')->where('isactive','1')->first()->id,
                            'present'           => 0,
                            'absent'            => 0,
                            'tardy'             => 1,
                            'cc'                => 0,
                            'attdate'           => date('d', strtotime($tdate)),
                            'attday'            => date('d', strtotime($tdate)),
                            'deleted'           => 0,
                            'tdate'             => $tdate,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
    }
    public function absentattendancecol(Request $request)
    {
        if($request->ajax())
        {
            $tdate          = $request->get('tdate');
            $studids        = json_decode($request->get('studids'));
            $levelid        = $request->get('levelid');
            $sectionid        = $request->get('sectionid');
            
            foreach($studids as $studid)
            {
                $checkifexists = DB::table('studattendance')
                    ->where('studid', $studid)
                    ->where('tdate', $tdate)
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    DB::table('studattendance')
                        ->where('studid', $studid)
                        ->where('tdate', $tdate)
                        ->where('deleted','0')
                        ->update([
                            'present'           => 0,
                            'absent'            => 1,
                            'tardy'             => 0,
                            'cc'                => 0,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studattendance')
                        ->insert([
                            'studid'            => $studid,
                            'syid'              => DB::table('sy')->where('isactive','1')->first()->id,
                            'semid'             => DB::table('semester')->where('isactive','1')->first()->id,
                            'present'           => 0,
                            'absent'            => 1,
                            'tardy'             => 0,
                            'cc'                => 0,
                            'attdate'           => date('d', strtotime($tdate)),
                            'attday'            => date('d', strtotime($tdate)),
                            'deleted'           => 0,
                            'tdate'             => $tdate,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
    }
    public function deleteattendancerow(Request $request)
    {
        if($request->ajax())
        {
            $studid      = $request->get('studid');
            $dates       = json_decode($request->get('dates'));
            $levelid     = $request->get('levelid');
            $sectionid   = $request->get('sectionid');
            
            DB::table('studattendance')
                ->whereIn('tdate',$dates)
                ->where('studid',$studid)
                ->update([
                    'deleted'               => 1,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
    }
    public function presentattendancerow(Request $request)
    {
        if($request->ajax())
        {
            $studid          = $request->get('studid');
            $dates           = json_decode($request->get('dates'));
            $levelid         = $request->get('levelid');
            $sectionid        = $request->get('sectionid');
            
            foreach($dates as $date)
            {
                $checkifexists = DB::table('studattendance')
                    ->where('tdate',$date)
                    ->where('studid',$studid)
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    DB::table('studattendance')
                        ->where('studid', $studid)
                        ->where('tdate', $date)
                        ->where('deleted','0')
                        ->update([
                            'present'           => 1,
                            'absent'            => 0,
                            'tardy'             => 0,
                            'cc'                => 0,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studattendance')
                        ->insert([
                            'studid'            => $studid,
                            'syid'              => DB::table('sy')->where('isactive','1')->first()->id,
                            'semid'             => DB::table('semester')->where('isactive','1')->first()->id,
                            'present'           => 1,
                            'absent'            => 0,
                            'tardy'             => 0,
                            'cc'                => 0,
                            'attdate'           => date('d', strtotime($date)),
                            'attday'            => date('d', strtotime($date)),
                            'deleted'           => 0,
                            'tdate'             => $date,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
    }

    public function lateattendancerow(Request $request)
    {
        if($request->ajax())
        {
            $studid          = $request->get('studid');
            $dates           = json_decode($request->get('dates'));
            $levelid        = $request->get('levelid');
            $sectionid        = $request->get('sectionid');
            
            foreach($dates as $date)
            {
                $checkifexists = DB::table('studattendance')
                    ->where('studid', $studid)
                    ->where('tdate', $date)
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    DB::table('studattendance')
                        ->where('studid', $studid)
                        ->where('tdate', $date)
                        ->where('deleted','0')
                        ->update([
                            'present'           => 0,
                            'absent'            => 0,
                            'tardy'             => 1,
                            'cc'                => 0,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studattendance')
                        ->insert([
                            'studid'            => $studid,
                            'syid'              => DB::table('sy')->where('isactive','1')->first()->id,
                            'semid'             => DB::table('semester')->where('isactive','1')->first()->id,
                            'present'           => 0,
                            'absent'            => 0,
                            'tardy'             => 1,
                            'cc'                => 0,
                            'attdate'           => date('d', strtotime($date)),
                            'attday'            => date('d', strtotime($date)),
                            'deleted'           => 0,
                            'tdate'             => $date,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
    }
    public function absentattendancerow(Request $request)
    {
        if($request->ajax())
        {
            $studid          = $request->get('studid');
            $dates           = json_decode($request->get('dates'));
            $levelid        = $request->get('levelid');
            $sectionid        = $request->get('sectionid');
            
            foreach($dates as $date)
            {
                $checkifexists = DB::table('studattendance')
                    ->where('studid', $studid)
                    ->where('tdate', $date)
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    DB::table('studattendance')
                        ->where('studid', $studid)
                        ->where('tdate', $date)
                        ->where('deleted','0')
                        ->update([
                            'present'           => 0,
                            'absent'            => 1,
                            'tardy'             => 0,
                            'cc'                => 0,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studattendance')
                        ->insert([
                            'studid'            => $studid,
                            'syid'              => DB::table('sy')->where('isactive','1')->first()->id,
                            'semid'             => DB::table('semester')->where('isactive','1')->first()->id,
                            'present'           => 0,
                            'absent'            => 1,
                            'tardy'             => 0,
                            'cc'                => 0,
                            'attdate'           => date('d', strtotime($date)),
                            'attday'            => date('d', strtotime($date)),
                            'deleted'           => 0,
                            'tdate'             => $date,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
    }
    public function getremarks(Request $request)
    {
        if($request->ajax())
        {
            $remarks = Db::table('studinfo')
                ->select('studinfo.lastname','studinfo.firstname','studinfo.middlename','studattendance.remarks')
                ->leftJoin('studattendance','studinfo.id','=','studattendance.studid')
                ->where('studinfo.id', $request->get('studentid'))
                ->where('studattendance.tdate', $request->get('selecteddate'))
                ->where('studattendance.deleted', 0)
                ->first();
    
               return collect($remarks);
            
        }
    }
    public function updateremarks(Request $request)
    {
        // return $request->all();
        
        if($request->ajax())
        {
            Db::table('studattendance')
                ->where('studid', $request->get('studentid'))
                ->where('tdate', $request->get('selecteddate'))
                ->where('syid', $request->get('selectedschoolyear'))
                ->where('semid', $request->get('selectedsemester'))
                ->update([
                    'remarks'       => $request->get('remarks')
                ]);
        }
    }
    public function changedate(Request $request){
        if($request->ajax())
        {
            // return $request->all();
            date_default_timezone_set('Asia/Manila');
            $syid = DB::table('sy')
                            ->where('isactive','1')
                            ->first();
            $tdate = $request->get('newDate');
            $section_id = $request->get('getSection');
            $StudentsToView = array();
            $acadprog = Db::table('sections')
                ->join('gradelevel', 'sections.levelid','gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid','academicprogram.id')
                ->where('gradelevel.deleted','0')
                ->where('sections.id',$section_id)
                ->first()->acadprogcode;
    
            // return $acadprog;
            if(strtolower($acadprog) == 'shs')
            {
                $students = DB::table('sh_enrolledstud')
                    ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.dob','studinfo.gender','studinfo.rfid','studinfo.studstatus','studentstatus.description','sh_enrolledstud.promotionstatus','sh_enrolledstud.dateenrolled')
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                    ->where('sh_enrolledstud.sectionid', $section_id)
                    ->where('sh_enrolledstud.deleted','0')
                    ->where('sh_enrolledstud.studstatus', '!=','0')
                    ->where('sh_enrolledstud.studstatus', '!=','6')
                    ->where('studinfo.studstatus', '!=','0')
                    // ->whereIn('sh_enrolledstud.studstatus',['1','2','4'])
                    ->where('sh_enrolledstud.syid',$syid->id)
                    ->distinct()
                    ->orderBy('lastname','asc')
                    ->get();
    
            }else{
                $students = DB::table('enrolledstud')
                    ->select('studinfo.id','studinfo.lrn','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.dob','studinfo.gender','studinfo.rfid','studinfo.studstatus','studentstatus.description','enrolledstud.promotionstatus','enrolledstud.dateenrolled')
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                    ->where('enrolledstud.sectionid', $section_id)
                    ->where('enrolledstud.deleted','0')
                    // ->whereIn('enrolledstud.studstatus',['1','2','4'])
                    ->where('enrolledstud.studstatus', '!=','0')
                    ->where('enrolledstud.studstatus', '!=','6')
                    ->where('studinfo.studstatus', '!=','0')
                    ->where('enrolledstud.syid',$syid->id)
                    ->distinct()
                    ->orderBy('lastname','asc')
                    ->get();
            }
            
            // $date = substr($id, 8);
            $attInfo = array();
            $checkdate = 0;
            foreach($students as $student){
                $getattendance = Db::table('studattendance')
                    ->where('studid', $student->id)
                    ->where('tdate', $tdate)
                    ->where('syid', $syid->id)
                    ->get();
                if(count($getattendance) == 0){
                        array_push($attInfo,(object)array(
                            'present'           => '1',
                            'absent'            => '0',
                            'tardy'             => '0',
                            'cc'                => '0',
                            'remarks'           => '',
                            'lastname'          => $student->lastname,
                            'firstname'         => $student->firstname,
                            'middlename'        => $student->middlename,
                            'id'                => $student->id,
                            'description'       => $student->description,
                            'gender'            => $student->gender
                        ));
                }else{
                    $checkdate+=1;
                    array_push($attInfo,(object)array(
                        'present'           => $getattendance[0]->present,
                        'absent'            => $getattendance[0]->absent,
                        'tardy'             => $getattendance[0]->tardy,
                        'cc'                => $getattendance[0]->cc,
                        'remarks'           => $getattendance[0]->remarks,
                        'lastname'          => $student->lastname,
                        'firstname'         => $student->firstname,
                        'middlename'        => $student->middlename,
                        'id'                => $student->id,
                        'gender'            => $student->gender,
                        'description'       => $student->description
                    ));
                }
            }
            // return $attInfo;
            $attendanceArray = array();
            array_push($attendanceArray,collect($attInfo)->unique('id')->values()->all());
            array_push($attendanceArray,$checkdate);
            return $attendanceArray;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $syid = DB::table('sy')
                        ->where('isactive','1')
                        ->first();
        $keys = array_keys($request->except('_token','currentDate','section_id'));
        $datarequest = $request->except('_token','currentDate','section_id');
        // return $datarequest;
        $keyStudId = 0;
        $keyRemarks = 1;

        // foreach($datarequest as $data){

        // }
        

        foreach($datarequest as $data){
            // return $datarequest;
            if($keyStudId<count($datarequest)){
                // return $data;
                // if($keyRemarks )
                // if($keyStudId>=1){
                    $section_id = $request->get('section_id');
                    $date = $request->get('currentDate');
                    $attday = date('d',strtotime($date));
                    // return $attday;
                    // return $keys[1];
                    $student_id = $keys[$keyStudId];
                    // return $student_id;
                    $status = $request->get($keys[$keyStudId]);
                    // return $status;
                    $remarks = $request->get($keys[$keyRemarks]);
                    // return $remarks;
                    $checkifexists = Db::table('studattendance')
                        ->where('studid',$student_id)
                        ->where('tdate',$date)
                        ->where('syid',$syid->id)
                        ->get();
                    if(count($checkifexists)==0){
                        if($status == 'present'){
                            DB::table('studattendance')
                                ->insert([
                                    'studid'            => $student_id,
                                    'syid'              => $syid->id,
                                    'present'           => '1',
                                    'absent'            => '0',
                                    'tardy'             => '0',
                                    'cc'                => '0',
                                    'attdate'           => $attday,
                                    'attday'            => $attday,
                                    'deleted'           => '0',
                                    'tdate'             => $date,
                                    'createddatetime'   => date('Y-m-d H:i:s'),
                                    'remarks'           => $remarks
                                ]);
                        }
                        elseif($status=='absent'){
                            DB::table('studattendance')
                                ->insert([
                                    'studid'            => $student_id,
                                    'syid'              => $syid->id,
                                    'present'           => '0',
                                    'absent'            => '1',
                                    'tardy'             => '0',
                                    'cc'                => '0',
                                    'attdate'           => $attday,
                                    'attday'            => $attday,
                                    'deleted'           => '0',
                                    'tdate'             => $date,
                                    'createddatetime'   => date('Y-m-d H:i:s'),
                                    'remarks'           => $remarks
                                ]);
                        }
                        elseif($status=='late'){
                            DB::table('studattendance')
                                ->insert([
                                    'studid'            => $student_id,
                                    'syid'              => $syid->id,
                                    'present'           => '0',
                                    'absent'            => '0',
                                    'tardy'             => '1',
                                    'cc'                => '0',
                                    'attdate'           => $attday,
                                    'attday'            => $attday,
                                    'deleted'           => '0',
                                    'tdate'             => $date,
                                    'createddatetime'   => date('Y-m-d H:i:s'),
                                    'remarks'           => $remarks
                                ]);
                        }
                        elseif($status=='halfday'){
                            DB::table('studattendance')
                                ->insert([
                                    'studid'            => $student_id,
                                    'syid'              => $syid->id,
                                    'present'           => '0',
                                    'absent'            => '0',
                                    'tardy'             => '0',
                                    'cc'                => '1',
                                    'attdate'           => $attday,
                                    'attday'            => $attday,
                                    'deleted'           => '0',
                                    'tdate'             => $date,
                                    'createddatetime'   => date('Y-m-d H:i:s'),
                                    'remarks'           => $remarks
                                ]);
                        }
                    }else{
                        if($status == 'present'){
                            DB::table('studattendance')
                                ->where('studid', $student_id)
                                ->where('tdate', $date)
                                ->update([
                                    'present'           => '1',
                                    'absent'            => '0',
                                    'tardy'             => '0',
                                    'cc'                => '0',
                                    'remarks'           => $remarks,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                        elseif($status=='absent'){
                            DB::table('studattendance')
                                ->where('studid', $student_id)
                                ->where('tdate', $date)
                                ->update([
                                    'present'           => '0',
                                    'absent'            => '1',
                                    'tardy'             => '0',
                                    'cc'                => '0',
                                    'remarks'           => $remarks,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                        elseif($status=='late'){
                            DB::table('studattendance')
                                ->where('studid', $student_id)
                                ->where('tdate', $date)
                                ->update([
                                    'present'           => '0',
                                    'absent'            => '0',
                                    'tardy'             => '1',
                                    'cc'                => '0',
                                    'remarks'           => $remarks,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                        elseif($status=='halfday'){
                            DB::table('studattendance')
                                ->where('studid', $student_id)
                                ->where('tdate', $date)
                                ->update([
                                    'present'           => '0',
                                    'absent'            => '0',
                                    'tardy'             => '0',
                                    'cc'                => '1',
                                    'remarks'           => $remarks,
                                    'updateddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                // }
            }
            $keyStudId+=2;
            $keyRemarks+=2;
        }
        return back();
    }
    public function fullattendance($id, Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $syid = DB::table('sy')
            ->where('isactive','1')
            ->first()->id;

        $acadprog = DB::table('gradelevel')
            ->select('academicprogram.acadprogcode')
            ->where('gradelevel.id', $request->get('levelid'))
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->first()->acadprogcode;

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
            ->join('refregion','schoolinfo.region','=','refregion.regCode')
            ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();
            
        if(strtolower($acadprog) == 'shs')
        {
            $students = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'studinfo.sid',
                    'sh_enrolledstud.levelid',
                    'sh_enrolledstud.sectionid'
                )
                ->where('studinfo.deleted','0')
                // ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                ->where('sh_enrolledstud.studstatus', '!=','0')
                ->where('sh_enrolledstud.studstatus', '!=','6')
                ->where('studinfo.studstatus', '!=','6')
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->where('sh_enrolledstud.syid', $syid)
                ->where('sh_enrolledstud.levelid', $request->get('levelid'))
                ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                ->orderBy('lastname','asc')
                ->get();
            
            $classsubjects = Db::table('classsched')
                ->select(
                    'sh_subjects.id',
                    'sh_subjects.subjtitle as subjectname',
                    'sh_subjects.subjcode as subjectcode',
                    'classscheddetail.days as day'
                )
                ->join('sh_subjects','classsched.subjid','=','sh_subjects.id')
                ->join('classscheddetail','classsched.id','=','classscheddetail.headerid')
                ->where('classsched.glevelid', $request->get('levelid'))
                ->where('classsched.sectionid', $request->get('sectionid'))
                ->where('classsched.syid', $syid)
                ->where('classsched.deleted', 0)
                ->where('sh_subjects.deleted', 0)
                ->where('sh_subjects.isactive', 1)
                ->orderBy('subjectcode','asc')
                ->distinct()
                ->get();

            
        }else{
            // return $request->get('levelid');
            // return 'asdsa';
            $students = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'studinfo.sid',
                    'enrolledstud.levelid',
                    'enrolledstud.sectionid'
                )
                ->where('studinfo.deleted','0')
                // ->whereIn('enrolledstud.studstatus',[1,2,4])
                ->where('enrolledstud.studstatus', '!=','0')
                ->where('enrolledstud.studstatus', '!=','6')
                ->where('studinfo.studstatus', '!=','6')
                ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                ->where('enrolledstud.syid', $syid)
                ->where('enrolledstud.levelid', $request->get('levelid'))
                ->where('enrolledstud.sectionid', $request->get('sectionid'))
                ->orderBy('lastname','asc')
                ->get();
            
            $classsubjects = Db::table('classsched')
                ->select(
                    'subjects.id',
                    'subjects.subjdesc as subjectname',
                    'subjects.subjcode as subjectcode',
                    'classscheddetail.days as day'
                )
                ->join('subjects','classsched.subjid','=','subjects.id')
                ->join('classscheddetail','classsched.id','=','classscheddetail.headerid')
                ->where('classsched.glevelid', $request->get('levelid'))
                ->where('classsched.sectionid', $request->get('sectionid'))
                ->where('classsched.syid', $syid)
                ->where('classsched.deleted', 0)
                ->where('subjects.deleted', 0)
                ->where('subjects.isactive', 1)
                ->orderBy('subjectcode','asc')
                ->distinct('id')
                ->get();

            // return $students;
        }
        // return $request->get('sectionid');
        $classsubjectsunique = collect($classsubjects)->unique('id')->values()->all();
        // return $classsubjectsunique;
        if($id == 'index')
        {
            $date       = date('Y-m-d');
            // return $date;
            $datestr    = date('N', strtotime($date));

            // return $datestr;
            if(count($students)>0){

                foreach($students as $student)
                {
                    // $studentid = $student->id;
                    $subjectsattendance = array();

                    if(count($classsubjectsunique)>0)
                    {
                        foreach($classsubjectsunique as $classsubject)
                        {
                            $attendsubj = DB::table('studentsubjectattendance')
                                ->where('student_id', $student->id)
                                ->where('subject_id', $classsubject->id)
                                ->where('section_id', $request->get('sectionid'))
                                ->where('date', $date)
                                // ->where('deleted', '0')
                                ->orderByDesc('id')
                                ->first();
                                
                            if($attendsubj)
                            {
                                $classsubject->status = $attendsubj->status;
                                $classsubject->remarks = $attendsubj->remarks;
                                
                            }else{

                                $classsubject->status = "";
                                $classsubject->remarks = "";

                            }
                            array_push($subjectsattendance, (object)array(
                                'id'            => $classsubject->id,
                                'subjectname'   => $classsubject->subjectname,
                                'subjectcode'   => $classsubject->subjectcode,
                                'status'        => $classsubject->status,
                                'remarks'       => $classsubject->remarks
                            ));
                        }
                    }
                    
                    $student->subjectattendance = $subjectsattendance;
                    
                    $advisoryattendance = DB::table('studattendance')
                        ->where('studid', $student->id)
                        ->where('syid', $syid)
                        ->where('deleted', '0')
                        ->where('tdate', $date)
                        ->orderByDesc('id')
                        ->first();

                    if($advisoryattendance)
                    {
                        //1 = present; 2 = late or tardy; 3 = halfday or cc ; 4 = absent
                        if($advisoryattendance->present == 1)
                        {
                            $attstatus = '1';
                        }
                        if($advisoryattendance->absent == 1)
                        {
                            $attstatus = '4';
                        }
                        if($advisoryattendance->tardy == 1)
                        {
                            $attstatus = '2';
                        }
                        if($advisoryattendance->cc == 1)
                        {
                            $attstatus = '3';
                        }
                        $attremarks = $advisoryattendance->remarks;

                    }else{
                        $attstatus = null;
                        $attremarks = null;
                    }
                    
                    $student->classattendance = $attstatus;
                    $student->remarks           = $attremarks;
                    
            
                }
            }
            // return $students;

            $subjectsfortoday = collect($classsubjects)->where('day', $datestr)->values();
            $today          = date('l', strtotime($date));
            // return $subjectsfortoday;
            return view('teacher.classattendance.advisory.fullattendance')
                ->with('date', $date)
                ->with('students', $students)
                ->with('classsubjects', $classsubjectsunique)
                ->with('sectionid', $request->get('sectionid'))
                ->with('levelid', $request->get('levelid'))
                ->with('today', $today)
                ->with('subjectsfortoday', $subjectsfortoday);

        }elseif($id == 'changedate')
        {
            
            if($request->ajax())
            {
                $date = $request->get('date');

                if(count($students)>0){

                    foreach($students as $student)
                    {
                        $subjectsattendance = array();

                        if(count($classsubjectsunique)>0)
                        {
                            foreach($classsubjectsunique as $classsubject)
                            {
                                $attend = DB::table('studentsubjectattendance')
                                    ->where('student_id', $student->id)
                                    ->where('subject_id', $classsubject->id)
                                    ->where('date', $date)
                                    // ->where('deleted', 0)
                                    ->orderByDesc('id')
                                    ->first();

                                    if($attend)
                                    {
                                        $classsubject->status = $attend->status;
                                        $classsubject->remarks = $attend->remarks;
                                        
                                    }else{
        
                                        $classsubject->status = "";
                                        $classsubject->remarks = "";
        
                                    }
                                    array_push($subjectsattendance, (object)array(
                                        'id'            => $classsubject->id,
                                        'subjectname'   => $classsubject->subjectname,
                                        'subjectcode'   => $classsubject->subjectcode,
                                        'status'        => $classsubject->status,
                                        'remarks'        => $classsubject->remarks
                                    ));
                            }
                        }


                        $student->subjectattendance = $subjectsattendance;
                        
                        $advisoryattendance = DB::table('studattendance')
                            ->where('studid', $student->id)
                            ->where('syid', $syid)
                            ->where('deleted', 0)
                            ->where('tdate', $date)
                            ->orderByDesc('id')
                            ->first();

                        if($advisoryattendance)
                        {
                            //1 = present; 2 = late or tardy; 3 = halfday or cc ; 4 = absent
                            if($advisoryattendance->present == 1)
                            {
                                $attstatus = '1';
                            }
                            if($advisoryattendance->absent == 1)
                            {
                                $attstatus = '4';
                            }
                            if($advisoryattendance->tardy == 1)
                            {
                                $attstatus = '2';
                            }
                            if($advisoryattendance->cc == 1)
                            {
                                $attstatus = '3';
                            }
                            $attremarks = $advisoryattendance->remarks;

                        }else{
                            $attstatus = null;
                            $attremarks = null;
                        }
                        $student->classattendance = $attstatus;
                        $student->remarks           = $attremarks;
                    }
                }


                
                return view('teacher.classattendance.advisory.fullattendancetable')
                    ->with('date', $date)
                    ->with('students', $students)
                    ->with('classsubjects', $classsubjectsunique)
                    ->with('sectionid', $request->get('sectionid'))
                    ->with('levelid', $request->get('levelid'));
            }

        }elseif($id == 'daydetails'){
            
            if($request->ajax())
            {
                
                $date = $request->get('date');
                $datestr    = date('N', strtotime($date));
                $subjectsfortoday = collect($classsubjects)->where('day', $datestr)->values();
                $today          = date('l', strtotime($date));
    
                return array(
                    'selecteddate'      => $today,
                    'subjects'          => $subjectsfortoday
                );
            }

        }elseif($id == 'print'){
            $date = $request->get('date');
            $datestr    = date('N', strtotime($date));

            // return $students;
            // return $request->all();
            if(count($students)>0){

                foreach($students as $student)
                {
                    // $studentid = $student->id;
                    $subjectsattendance = array();

                    if(count($classsubjectsunique)>0)
                    {
                        foreach($classsubjectsunique as $classsubject)
                        {
                            $attendsubj = DB::table('studentsubjectattendance')
                                ->where('student_id', $student->id)
                                ->where('subject_id', $classsubject->id)
                                ->where('section_id', $request->get('sectionid'))
                                ->where('date', $date)
                                // ->where('deleted', '0')
                                ->orderByDesc('id')
                                ->first();
                                
                            if($attendsubj)
                            {
                                $classsubject->status = $attendsubj->status;
                                $classsubject->remarks = $attendsubj->remarks;
                                
                            }else{

                                $classsubject->status = "";
                                $classsubject->remarks = "";

                            }
                            array_push($subjectsattendance, (object)array(
                                // 'id'            => $classsubject->id,
                                // 'subjectname'   => $classsubject->subjectname,
                                // 'subjectcode'   => $classsubject->subjectcode,
                                'status'        => $classsubject->status
                                // 'remarks'       => $classsubject->remarks
                            ));
                        }
                    }
                    
                    $student->subjectattendance = $subjectsattendance;
                    
                    $advisoryattendance = DB::table('studattendance')
                        ->where('studid', $student->id)
                        ->where('syid', $syid)
                        ->where('deleted', '0')
                        ->where('tdate', $date)
                        ->orderByDesc('id')
                        ->first();

                    if($advisoryattendance)
                    {
                        //1 = present; 2 = late or tardy; 3 = halfday or cc ; 4 = absent
                        if($advisoryattendance->present == 1)
                        {
                            $attstatus = 'PRESENT';
                        }
                        if($advisoryattendance->absent == 1)
                        {
                            $attstatus = 'ABSENT';
                        }
                        if($advisoryattendance->tardy == 1)
                        {
                            $attstatus = 'LATE';
                        }
                        if($advisoryattendance->cc == 1)
                        {
                            $attstatus = 'CUTTING CLASS';
                        }
                        $attremarks = $advisoryattendance->remarks;

                    }else{
                        $attstatus = null;
                        $attremarks = null;
                    }
                    
                    $student->classattendance = $attstatus;
                    $student->remarks           = $attremarks;
                    
            
                }
            }

            $subjectsfortoday = collect($classsubjects)->where('day', $datestr)->values();
            $today          = date('l', strtotime($date));
            
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // set document information
            $pdf->SetCreator('CK');
            $pdf->SetAuthor('CK Children\'s Publishing');
            $pdf->SetTitle($schoolinfo->schoolname.' - Summary');
            $pdf->SetSubject('Summary');
            
            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            
            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            
            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            
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
            $pdf->SetFont('dejavusans', '', 10);
            
            // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            // Print a table
            // return $data;
            // add a page
            $pdf->AddPage('L','A4');
            // return $request->all();
            $sectionname = DB::table('sections')
                ->where('id', $request->get('sectionid'))
                ->first()->sectionname;

            $levelname = DB::table('gradelevel')
                ->where('id', $request->get('levelid'))
                ->first()->levelname;

            $date = date('F d, Y',strtotime($date));

            $header = '
                        <table style="height: 20px;">
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <table style="font-size: 12px;margin-top: 5px;">
                            <tr>
                                <td>AS OF : <span style="font-weight: bold">'.strtoupper($date).'</span></td>
                                <td>GRADE LEVEL : <span style="font-weight: bold">'.$levelname.'</span></td>
                                <td>SECTION : <span style="font-weight: bold">'.$sectionname.'</span></td>
                            </tr>
                            <tr>
                                <td colspan="3">SUBJECTS OF THE DAY: ';
                                    if(count($subjectsfortoday) == 0)
                                    {
                                        $header.='<span style="font-weight: bold">NONE</span>';
                                    }else{
                                        foreach($subjectsfortoday as $subjecttoday)
                                        {
                                            $header.= '<span style="font-weight: bold">'.strtoupper($subjecttoday->subjectname).'</span> | ';
                                        }
                                    }
                                    $header.= '</td>
                            </tr>
                        </table>
            ';
            $pdf->writeHTML($header, true, false, false, false, '');

            
            $table = '<table border="1" cellspacing="0" cellpadding="1" style="font-size: 10px;text-transform: uppercase;width: 100%">
                            <thead>';
                                    if(count($classsubjectsunique)>0)
                                    {
                                        
                                        $table.='
                                        <tr>
                                            <th rowspan="2" width="20%" align="center">
                                                Student Name
                                            </th>
                                            <th colspan="'.count($classsubjectsunique).'"  width="70%" align="center">SUBJECTS</th>
                                            <th rowspan="2" align="center" width="10%">
                                                ADVISORY
                                            </th>
                                        </tr>';
                                    }else{
                                        
                                        $table.='
                                        <tr>
                                            <th rowspan="2" width="50%" align="center">
                                                Student Name
                                            </th>
                                            <th rowspan="2" align="center" width="50%">
                                                ADVISORY
                                            </th>
                                        </tr>';
                                    }
                                if(count($classsubjectsunique)>0)
                                {
                                    $table.='
                                <tr>';
                                    
                                    foreach($classsubjectsunique as $classsubject)
                                    {
                                        $table.='<th width="'.(70/count($classsubjectsunique)).'%" align="center">'.$classsubject->subjectcode.'</th>';
                                    }
                                    $table.='</tr>';
                                }
                               
                                $table.='</thead>';
                            if(count($students)>0)
                            {
                                $table.='<tr nobr="true">
                                            <td colspan="'.(count($classsubjectsunique)+2).'" style="background-color: #ddd">MALE</td>
                                        </tr>';

                                $totalmale = 1;

                                foreach($students as $student)
                                {
                                    if(strtolower($student->gender) == 'male')
                                    {
                                        if(count($classsubjectsunique)>0)
                                        {
                                            $table.='<tr nobr="true">
                                                        <td width="20%">'.$totalmale.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'. '.$student->suffix.'</td>';
                                                        if(count($student->subjectattendance)>0)
                                                        {
                                                            foreach($student->subjectattendance as $subjectsattendance)
                                                            {
                                                                $table.='
                                                                        <td width="'.(70/count($classsubjectsunique)).'%" align="center">'.strtoupper($subjectsattendance->status).'</td>';
                                                            }
                                                        }
                                                        $table.='<td align="center" width="10%">'.$student->classattendance.'</td>
                                                    </tr>';
                                        }else{
                                            $table.='<tr nobr="true">
                                                        <td width="50%">'.$totalmale.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'. '.$student->suffix.'</td>';
                                                        
                                                        $table.='<td align="center" width="50%">'.$student->classattendance.'</td>
                                                    </tr>';
                                        }

                                        $totalmale+=1;
                                    }
                                }
                                $table.='<tr nobr="true">
                                            <td colspan="'.(count($classsubjectsunique)+2).'" style="background-color: #ddd">FEMALE</td>
                                        </tr>';

                                $totalfemale = 1;
                                foreach($students as $student)
                                {
                                    if(strtolower($student->gender) == 'female')
                                    {
                                        if(count($classsubjectsunique)>0)
                                        {
                                            $table.='<tr nobr="true">
                                                        <td width="20%">'.$totalfemale.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'. '.$student->suffix.'</td>';
                                                        if(count($student->subjectattendance)>0)
                                                        {
                                                            foreach($student->subjectattendance as $subjectsattendance)
                                                            {
                                                                $table.='
                                                                        <td width="'.(70/count($classsubjectsunique)).'%" align="center">'.strtoupper($subjectsattendance->status).'</td>';
                                                            }
                                                        }
                                                        $table.='<td align="center">'.$student->classattendance.'</td>
                                                    </tr>';
                                        }else{
                                            $table.='<tr nobr="true">
                                                        <td width="50%">'.$totalfemale.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'. '.$student->suffix.'</td>';
                                                        
                                                        $table.='<td align="center"  width="50%">'.$student->classattendance.'</td>
                                                    </tr>';
                                        }
                                        $totalfemale+=1;
                                    }
                                }
                            } 
                            $table.='</table>';
            // if($shsbystrand == 1)
            // {
            //     $table = SummaryTables::table1($strands, $data, $selectedgender);
            // }else{
            //     if($trackid == null)
            //     {
            //         $table = SummaryTables::table2($selectedstudenttype, $data,$selectedgender);
            //     }else{
            //         if($strandid == null)
            //         {
            //             $table = SummaryTables::table3($strands, $data,$selectedgender);
                            
            //         }else{
                            
            //             $table = SummaryTables::table4($trackname,$strandname,$data,$selectedgender);
            //         }
            //     }
                    
            // }
                // output the HTML content
                
            set_time_limit(3000);
            $pdf->writeHTML($table, true, false, false, false, '');
            
            $pdf->lastPage();
            
            // ---------------------------------------------------------
            //Close and output PDF document
            $pdf->Output('Attendance Report.pdf', 'I');
        }
    }
    public function getcalendar(Request $request)
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
    public function generate(Request $request)
    {
        return $request->all();
    }
    public function hccsi(Request $request)
    {
        if($request->get('action') == 'update-column-status')
        {
            $tdate          = $request->get('tdate');
            $studids        = json_decode($request->get('studids'));
            $levelid        = $request->get('levelid');
            $status        = $request->get('valstatus');
            $sectionid        = $request->get('sectionid');
            

            $present = 0;
            $absent = 0;
            $tardy = 0;
            $cc = 0;
            $presentam = 0;
            $presentpm = 0;
            $absentam = 0;
            $absentpm = 0;
            $lateam = 0;
            $latepm = 0;
            $ccam = 0;
            $ccpm = 0;

            if($status == 'present')
            {
                $present = 1;
            }
            if($status == 'presentam')
            {
                $presentam = 1;
            }
            if($status == 'presentpm')
            {
                $presentpm = 1;
            }
            if($status == 'absent')
            {
                $absent = 1;
            }
            if($status == 'absentam')
            {
                $absentam = 1;
            }
            if($status == 'absentpm')
            {
                $absentpm = 1;
            }
            if($status == 'late')
            {
                $tardy = 1;
            }
            if($status == 'lateam')
            {
                $lateam = 1;
            }
            if($status == 'latepm')
            {
                $latepm = 1;
            }
            if($status == 'cc')
            {
                $cc = 1;
            }
            if($status == 'ccam')
            {
                $ccam = 1;
            }
            if($status == 'ccpm')
            {
                $ccpm = 1;
            }

            foreach($studids as $studid)
            {
                $checkifexists = DB::table('studattendance')
                    ->where('studid', $studid)
                    ->where('tdate', $tdate)
                    ->where('deleted','0')
                    ->first();
    
                if($checkifexists)
                {
                    DB::table('studattendance')
                        ->where('studid', $studid)
                        ->where('tdate', $tdate)
                        ->where('deleted','0')
                        ->update([
                            'present'           => $present,
                            'absent'            => $absent,
                            'tardy'             => $tardy,
                            'cc'                => $cc,
                            'presentam'                => $presentam,
                            'presentpm'                => $presentpm,
                            'absentam'                => $absentam,
                            'absentpm'                => $absentpm,
                            'lateam'                => $lateam,
                            'latepm'                => $latepm,
                            'ccam'                => $ccam,
                            'ccpm'                => $ccpm,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studattendance')
                        ->insert([
                            'studid'            => $studid,
                            'syid'              => DB::table('sy')->where('isactive','1')->first()->id,
                            'semid'             => DB::table('semester')->where('isactive','1')->first()->id,
                            'present'           => $present,
                            'absent'            => $absent,
                            'tardy'             => $tardy,
                            'cc'                => $cc,
                            'presentam'                => $presentam,
                            'presentpm'                => $presentpm,
                            'absentam'                => $absentam,
                            'absentpm'                => $absentpm,
                            'lateam'                => $lateam,
                            'latepm'                => $latepm,
                            'ccam'                => $ccam,
                            'ccpm'                => $ccpm,
                            'attdate'           => date('d', strtotime($tdate)),
                            'attday'            => date('d', strtotime($tdate)),
                            'deleted'           => 0,
                            'tdate'             => $tdate,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
            return 1;
        }
        if($request->get('action') == 'update-row-status')
        {
            $studid          = $request->get('studid');
            $dates           = json_decode($request->get('dates'));
            $levelid         = $request->get('levelid');
            $sectionid        = $request->get('sectionid');
            $status        = $request->get('valstatus');
            

            $present = 0;
            $absent = 0;
            $tardy = 0;
            $cc = 0;
            $presentam = 0;
            $presentpm = 0;
            $absentam = 0;
            $absentpm = 0;
            $lateam = 0;
            $latepm = 0;
            $ccam = 0;
            $ccpm = 0;

            if($status == 'present')
            {
                $present = 1;
            }
            if($status == 'presentam')
            {
                $presentam = 1;
            }
            if($status == 'presentpm')
            {
                $presentpm = 1;
            }
            if($status == 'absent')
            {
                $absent = 1;
            }
            if($status == 'absentam')
            {
                $absentam = 1;
            }
            if($status == 'absentpm')
            {
                $absentpm = 1;
            }
            if($status == 'late')
            {
                $tardy = 1;
            }
            if($status == 'lateam')
            {
                $lateam = 1;
            }
            if($status == 'latepm')
            {
                $latepm = 1;
            }
            if($status == 'cc')
            {
                $cc = 1;
            }
            if($status == 'ccam')
            {
                $ccam = 1;
            }
            if($status == 'ccpm')
            {
                $ccpm = 1;
            }
            foreach($dates as $date)
            {
                $checkifexists = DB::table('studattendance')
                    ->where('tdate',$date)
                    ->where('studid',$studid)
                    ->where('deleted','0')
                    ->first();

                if($checkifexists)
                {
                    DB::table('studattendance')
                        ->where('studid', $studid)
                        ->where('tdate', $date)
                        ->where('deleted','0')
                        ->update([
                            'present'           => $present,
                            'absent'            => $absent,
                            'tardy'             => $tardy,
                            'cc'                => $cc,
                            'presentam'                => $presentam,
                            'presentpm'                => $presentpm,
                            'absentam'                => $absentam,
                            'absentpm'                => $absentpm,
                            'lateam'                => $lateam,
                            'latepm'                => $latepm,
                            'ccam'                => $ccam,
                            'ccpm'                => $ccpm,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }else{
                    
                    DB::table('studattendance')
                        ->insert([
                            'studid'            => $studid,
                            'syid'              => DB::table('sy')->where('isactive','1')->first()->id,
                            'semid'             => DB::table('semester')->where('isactive','1')->first()->id,
                            'present'           => $present,
                            'absent'            => $absent,
                            'tardy'             => $tardy,
                            'cc'                => $cc,
                            'presentam'                => $presentam,
                            'presentpm'                => $presentpm,
                            'absentam'                => $absentam,
                            'absentpm'                => $absentpm,
                            'lateam'                => $lateam,
                            'latepm'                => $latepm,
                            'ccam'                => $ccam,
                            'ccpm'                => $ccpm,
                            'attdate'           => date('d', strtotime($date)),
                            'attday'            => date('d', strtotime($date)),
                            'deleted'           => 0,
                            'tdate'             => $date,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }

            return 1;

        }
        if($request->get('action') == 'submit')
        {
            if(count($request->get('datavalues'))>0)
            {
                foreach($request->get('datavalues') as $dataval)
                {
                    // return $dataval['studid'];
                    $checkifexists = DB::table('studattendance')
                        ->where('studid', $dataval['studid'])
                        // ->where('syid', $request->get('selectedschoolyear'))
                        ->whereDate('tdate', $dataval['tdate'])
                        ->where('deleted','0')
                        ->first();
                        $status        = strtolower($dataval['newstatus']);
                        $present = 0;
                        $absent = 0;
                        $tardy = 0;
                        $cc = 0;
                        $presentam = 0;
                        $presentpm = 0;
                        $absentam = 0;
                        $absentpm = 0;
                        $lateam = 0;
                        $latepm = 0;
                        $ccam = 0;
                        $ccpm = 0;
                    // return collect($checkifexists);
                    if($status == 'present')
                    {
                        $present = 1;
                    }
                    if($status == 'presentam')
                    {
                        $presentam = 1;
                    }
                    if($status == 'presentpm')
                    {
                        $presentpm = 1;
                    }
                    if($status == 'absent')
                    {
                        $absent = 1;
                    }
                    if($status == 'absentam')
                    {
                        $absentam = 1;
                    }
                    if($status == 'absentpm')
                    {
                        $absentpm = 1;
                    }
                    if($status == 'late')
                    {
                        $tardy = 1;
                    }
                    if($status == 'lateam')
                    {
                        $lateam = 1;
                    }
                    if($status == 'latepm')
                    {
                        $latepm = 1;
                    }
                    if($status == 'cc')
                    {
                        $cc = 1;
                    }
                    if($status == 'ccam')
                    {
                        $ccam = 1;
                    }
                    if($status == 'ccpm')
                    {
                        $ccpm = 1;
                    }
                    
                    if($checkifexists)
                    {
                        if(strtolower($dataval['newstatus']) == 'none')
                        {
                            // return 'asdasd';
                            DB::table('studattendance')
                                ->where('id', $checkifexists->id)
                                ->update([
                                    'deleted'       =>  '1',
                                    'deleteddatetime'=> date('Y-m-d H:i:s')
                                ]);
                        }else{
                            
                            // try{
                                // return $ccval;
                                DB::table('studattendance')
                                    ->where('id', $checkifexists->id)
                                    ->update([
                                        'present'           => $present,
                                        'absent'            => $absent,
                                        'tardy'             => $tardy,
                                        'cc'                => $cc,
                                        'presentam'                => $presentam,
                                        'presentpm'                => $presentpm,
                                        'absentam'                => $absentam,
                                        'absentpm'                => $absentpm,
                                        'lateam'                => $lateam,
                                        'latepm'                => $latepm,
                                        'ccam'                => $ccam,
                                        'ccpm'                => $ccpm,
                                        'updateddatetime'=> date('Y-m-d H:i:s')
                                    ]);
                                    
                            // }catch(\Exception $error)
                            // {
                            //     return $error;
                            // }
                        }
                    }else{
                        DB::table('studattendance')
                            ->insert([
                                'studid'        =>  $dataval['studid'],
                                'syid'          =>  $request->get('selectedschoolyear'),
                                'semid'         =>  $request->get('selectedsemester'),
                                'present'           => $present,
                                'absent'            => $absent,
                                'tardy'             => $tardy,
                                'cc'                => $cc,
                                'presentam'                => $presentam,
                                'presentpm'                => $presentpm,
                                'absentam'                => $absentam,
                                'absentpm'                => $absentpm,
                                'lateam'                => $lateam,
                                'latepm'                => $latepm,
                                'ccam'                => $ccam,
                                'ccpm'                => $ccpm,
                                'attdate'       => date('d', strtotime($dataval['tdate'])),
                                'attday'        => date('d', strtotime($dataval['tdate'])),
                                'deleted'       => 0,
                                'createddatetime'=> date('Y-m-d H:i:s'),
                                'tdate'         => $dataval['tdate']
                            ]);
                    }
                }
            }
return 1;
        }
    }
}