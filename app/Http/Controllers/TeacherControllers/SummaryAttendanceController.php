<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class SummaryAttendanceController extends Controller
{

    public function index()
    {
        date_default_timezone_set('Asia/Manila');

        $teacherid = DB::table('teacher')->where('userid', auth()->user()->id)->first()->id;
        
        $sem = DB::table('semester')
            ->where('isactive','1')
            ->first();
        $mutable = date('Y-m-d');
        $tdate =date('Y-m-d');
        
        $sy = DB::table('sy')
            ->where('isactive','1')
            ->first();
        // return $sy;
        $sections_1 = DB::table('assignsubj')
            // ->select('assignsubj.id','gradelevel.id as levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
            ->select('gradelevel.id as levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname','subjects.id as subjectid','subjects.subjdesc as subjectname','gradelevel.sortid')
            ->join('assignsubjdetail','assignsubj.id','=','assignsubjdetail.headerid')
            ->join('gradelevel','assignsubj.glevelid','=','gradelevel.id')
            ->join('sections','assignsubj.sectionid','=','sections.id')
            ->join('subjects','assignsubjdetail.subjid','=','subjects.id')
            ->where('assignsubj.syid',$sy->id)
            ->where('assignsubj.deleted','0')
            ->where('assignsubjdetail.deleted','0')
            ->where('assignsubjdetail.teacherid',$teacherid)
            ->distinct()
            ->get();

        
        $sections_2 = DB::table('sh_classsched')
            // ->select('sh_classsched.id','gradelevel.id as levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname')
            ->select('gradelevel.id as levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname','sh_subjects.id as subjectid','sh_subjects.subjtitle as subjectname','gradelevel.sortid')
            ->join('sh_classscheddetail','sh_classsched.id','=','sh_classscheddetail.headerid')
            ->join('gradelevel','sh_classsched.glevelid','=','gradelevel.id')
            ->join('sections','sh_classsched.sectionid','=','sections.id')
            ->join('sh_subjects','sh_classsched.subjid','=','sh_subjects.id')
            ->where('sh_classsched.teacherid',$teacherid)
            ->where('sh_classsched.syid',$sy->id)
            ->where('sh_classsched.semid',$sem->id)
            ->distinct()
            ->get();
            
        // $sections_3 = DB::table('sh_blocksched')
        //     ->select('gradelevel.id as glevelid','gradelevel.levelname','gradelevel.sortid')
        //     ->join('sh_blockscheddetail','sh_blocksched.id','=','sh_blockscheddetail.headerid')
        //     ->join('sh_subjects','sh_blocksched.subjid','=','sh_subjects.id')
        //     ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
        //     ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
        //     ->leftJoin('gradelevel','sections.levelid','=','gradelevel.id')
        //     ->join('days','sh_blockscheddetail.day','=','days.id')
        //     ->join('rooms','sh_blockscheddetail.roomid','=','rooms.id')
        //     ->where('sh_blocksched.teacherid',$teacherid)
        //     ->where('sh_blocksched.deleted','0')
        //     ->where('sh_blockscheddetail.deleted','0')
        //     ->where('sh_sectionblockassignment.deleted','0')
        //     // ->where('gradelevel.deleted','0')
        //     ->where('sections.deleted','0')
        //     ->where('sh_blocksched.syid',$sy->id)
        //     ->distinct()
        //     ->get();
            
            
        $sections = collect();
        $sections = $sections->merge($sections_1);
        $sections = $sections->merge($sections_2);
        // $sections = $sections->merge($sections_3);
        $sections = $sections->unique()->sortBy(function($sections) {
            return sprintf('%-12s%s', $sections->sortid, $sections->sectionname);
        })->values();
        
        return view('teacher.summaries.attendancereport.index')
            ->with('sections', $sections);
    }
    public function filter(Request $request)
    {
        // return $request->all();
        $period = explode(' - ', $request->get('dateperiod'));
        $datefrom = date('Y-m-d',strtotime($period[0]));
        $dateto = date('Y-m-d', strtotime($period[1]));

        
        $dates = array(); 
          
        // Use strtotime function 
        $Variable1 = strtotime($datefrom); 
        $Variable2 = strtotime($dateto); 
          
        // Use for loop to store dates into array 
        // 86400 sec = 24 hrs = 60*60*24 = 1 day 
        for ($currentDate = $Variable1; $currentDate <= $Variable2; $currentDate += (86400)) { 
            $Store = date('Y-m-d', $currentDate); 
            $dates[] = $Store; 
        } 

        $levelid = explode('-', $request->get('levelandsection'))[0];
        $sectionid = explode('-', $request->get('levelandsection'))[1];
        $subjectid = explode('-', $request->get('levelandsection'))[2];
        
        $acadprogcode = DB::table('gradelevel')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id',$levelid)
            ->first()
            ->acadprogcode;

        $levelname = DB::table('gradelevel')
            ->where('id', $levelid)->first()->levelname;

        $sectionname = DB::table('sections')
            ->where('id', $sectionid)->first()->sectionname;

        if(strtolower($acadprogcode) == 'shs')
        {
            $students = DB::table('studinfo')
                ->select('studinfo.id','lastname','firstname','middlename','suffix','gender')
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->where('sh_enrolledstud.deleted','0')
                ->where('sh_enrolledstud.levelid', $levelid)
                ->where('sh_enrolledstud.sectionid', $sectionid)
                ->orderBy('lastname','asc')
                ->get();   

            $subjectname = DB::table('sh_subjects')
                ->where('id', $subjectid)->first()->subjtitle;     

            $subjectcode = DB::table('sh_subjects')
                ->where('id', $subjectid)->first()->subjcode;     
                    
        }else{
            $students = DB::table('studinfo')
                ->select('studinfo.id','lastname','firstname','middlename','suffix','gender')
                ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                ->where('enrolledstud.deleted','0')
                ->where('enrolledstud.levelid', $levelid)
                ->where('enrolledstud.sectionid', $sectionid)
                ->orderBy('lastname','asc')
                ->get();

            $subjectname = DB::table('subjects')
                ->where('id', $subjectid)->first()->subjdesc;     

            $subjectcode = DB::table('subjects')
                ->where('id', $subjectid)->first()->subjcode;  
        }

        $studids = collect($students)->pluck('id');

        $attendance = DB::table('studentsubjectattendance')
            ->where('section_id', $sectionid)
            ->where('subject_id', $subjectid)
            ->where('deleted','0')
            ->whereBetween('date',[collect($dates)->first(),collect($dates)->last()])
            ->whereIn('student_id',$studids)
            ->get();
            
        if(count($students)>0)
        {
            foreach($students as $student)
            {
                $att = array();
                foreach($dates as $date)
                {
                    $subjectatt =  collect($attendance)->where('student_id', $student->id)->where('date', $date)->values();
                    
                    $status = "";
    
                    if(count($subjectatt)>0)
                    {
                        
                        $status = strtolower($subjectatt[0]->status);
                    }

                    array_push($att, (object)array(
                        'date'     =>    $date,
                        'status'    => $status
                    ));
                }

                $student->attendance = $att;
            }
        }
        
        if($request->get('action') == 'filter')
        {
            return view('teacher.summaries.attendancereport.tableresults')
                ->with('students',$students)
                ->with('dates',$dates);
        }else{
            $schoolinfo = DB::table('schoolinfo')
                ->first();
            if($request->get('exporttype') == 'pdf')
            {
                
                $pdf = new MYPDFSummaryAttendance(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // set document information
                $pdf->SetCreator('CK');
                $pdf->SetAuthor('CK Children\'s Publishing');
                $pdf->SetTitle($schoolinfo->schoolname.' - Attendance');
                $pdf->SetSubject('Attendance');
                
                // set header and footer fonts
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                
                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                
                // set margins
                // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetMargins(10, 20, 10, true);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                
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
                $pdf->SetFont('dejavusans', '', 10);
                
                
                // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                // Print a table
                
                
                $pdf->AddPage('Landscape');
        
                set_time_limit(3000);

                $view = \View::make('teacher/pdf/summaryattendancepersubject',compact('levelname','sectionname','subjectname','subjectcode','students','dates'));

                $html = $view->render();
                $pdf->writeHTML($html, true, false, true, false, '');
                // ---------------------------------------------------------
                //Close and output PDF document
                $pdf->Output('Attendance.pdf', 'I');
            }else{
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
                $sheet = $spreadsheet->getActiveSheet();
                $borderstyle    = [
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
                foreach(range('B','Z') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }

                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(base_path().'/public/'.$schoolinfo->picurl);
                $drawing->setHeight(80);
                $drawing->setWorksheet($sheet);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(20);
                $drawing->setOffsetY(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);

                $drawing->getShadow()->setVisible(true);
                $drawing->getShadow()->setDirection(45);
                $startcellno = 5;

                        
                // $sheet->mergeCells('C2:J2');
                $sheet->setCellValue('B2', $schoolinfo->schoolname);
                // $sheet->getStyle('C2:J2')->getAlignment()->setHorizontal('center');

                // $sheet->mergeCells('C3:J3');
                $sheet->setCellValue('B3', $schoolinfo->address);
                // $sheet->getStyle('C3:J3')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('B'.$startcellno, 'GRADE LEVEL & SECTION : '.$levelname.' - '.$sectionname);
                $startcellno+=1;

                $sheet->setCellValue('B'.$startcellno, 'SUBJECT : '.$subjectcode.' - '.$subjectname);
                $startcellno+=1;

                if(count($dates) == 1)
                {
                    $sheet->setCellValue('B'.$startcellno, 'AS OF : '.strtoupper(date('M d, Y', strtotime($dates[0]))));
                }else{
                    $sheet->setCellValue('B'.$startcellno, 'AS OF : '.strtoupper(date('M d, Y', strtotime(collect($dates)->first()))).' - '.strtoupper(date('M d, Y', strtotime(collect($dates)->last()))));
                }
                
                $startcellno+=2;

                $columnno = 2;
                
                $sheet->getStyle('A'.$startcellno)->applyFromArray($borderstyle);
                $sheet->getStyle('A'.(int)($startcellno+1))->applyFromArray($borderstyle);
                $sheet->mergeCells('A'.$startcellno.':B'.(int)($startcellno+1));
                $sheet->setCellValue('A'.$startcellno, 'STUDENTS');
                $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
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

                if(count($dates)>0)
                {
                    $columndate = 2;
                    foreach($dates as $date)
                    {
                        // return getNameFromNumber($columnno+count($dates)).$startcellno;
                        $sheet->getStyle(getNameFromNumber($columndate).$startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue(getNameFromNumber($columndate).$startcellno, date('M d',strtotime($date)));
                        $sheet->getStyle(getNameFromNumber($columndate).(int)($startcellno+1))->applyFromArray($borderstyle);
                        $sheet->setCellValue(getNameFromNumber($columndate).(int)($startcellno+1), date('D',strtotime($date)));
                        $sheet->getStyle(getNameFromNumber($columndate).$startcellno)->getFont()->setBold(true);
                        $columndate+=1;
                    }
                }
                $sheet->getStyle('A'.$startcellno)->applyFromArray($borderstyle);
                $startcellno+=2;

                $malecount = 1;
                $femalecount = 1;

                // return $dates;

                $sheet->mergeCells('A'.$startcellno.':'.sprintf(getNameFromNumber(1+count($dates))).$startcellno);
                $sheet->setCellValue('A'.$startcellno, 'MALE');
                $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                $sheet->getStyle('A'.(int)($startcellno))->applyFromArray($borderstyle);
                $startcellno+=1;
                foreach($students as $student)
                {
                    if(strtolower($student->gender) == 'male')
                    {
                        $sheet->getStyle('A'.($startcellno))->applyFromArray($borderstyle);
                        $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                        $sheet->setCellValue('A'.$startcellno, $malecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'. '.$student->suffix);
                        $attendancecolumn = 2;
                        if(count($student->attendance)>0)
                        {
                            foreach($student->attendance as $att)
                            {
                                $sheet->getStyle(sprintf(getNameFromNumber($attendancecolumn)).$startcellno)->applyFromArray($borderstyle);
                                $sheet->setCellValue(sprintf(getNameFromNumber($attendancecolumn)).$startcellno, strtoupper($att->status));
                                $attendancecolumn+=1;
                            }
                        }
                        $startcellno+=1;
                        $malecount+=1;
                    }
                }

                $sheet->mergeCells('A'.$startcellno.':'.sprintf(getNameFromNumber(1+count($dates))).$startcellno);
                $sheet->setCellValue('A'.$startcellno, 'FEMALE');
                $sheet->getStyle('A'.$startcellno)->getFont()->setBold(true);
                $startcellno+=1;
                foreach($students as $student)
                {
                    if(strtolower($student->gender) == 'female')
                    {
                        $sheet->getStyle('A'.($startcellno))->applyFromArray($borderstyle);
                        $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                        $sheet->setCellValue('A'.$startcellno, $femalecount.'. '.$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'. '.$student->suffix);
                        $attendancecolumn = 2;
                        if(count($student->attendance)>0)
                        {
                            foreach($student->attendance as $att)
                            {
                                $sheet->getStyle(sprintf(getNameFromNumber($attendancecolumn)).$startcellno)->applyFromArray($borderstyle);
                                $sheet->setCellValue(sprintf(getNameFromNumber($attendancecolumn)).$startcellno, strtoupper($att->status));
                                $attendancecolumn+=1;
                            }
                        }
                        $startcellno+=1;
                        $femalecount+=1;
                    }
                }


                        
                // $sheet->setCellValue('B'.$startcellno, 'SCHOOL YEAR');
                // $sheet->setCellValue('C'.$startcellno, ': '.$sy->sydesc);
                // $sheet->setCellValue('E'.$startcellno, 'COLLEGE/TRACK');
                // $sheet->setCellValue('F'.$startcellno, ': '.$trackname);
                // $sheet->setCellValue('H'.$startcellno, 'GENDER');
                // $sheet->setCellValue('I'.$startcellno, ': '.$selectedgender);
                // $startcellno+=1;
                

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="Attendance - '.$levelname.' '.$sectionname.' ('.$subjectcode.').xlsx"');
                $writer->save("php://output");
            }
        }

    }

    public function summaryattendancepersubject($id,Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');

        $myid = Db::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();
        

        $assignsubj = Db::table('assignsubj')
            ->select(
                'assignsubj.id',
                'assignsubj.sectionid',
                'assignsubj.glevelid',
                'sections.sectionname',
                'academicprogram.acadprogcode'
                )
            ->join('gradelevel','assignsubj.glevelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->join('sy','assignsubj.syid','=','sy.id')
            ->leftJoin('sections','assignsubj.sectionid','=','sections.id')
            ->where('sy.isactive','1')
            ->where('assignsubj.deleted','0')
            ->get();
            
        $subjectsarray = array();

        foreach($assignsubj as $assignedsubj){

            $getassignedsubj = DB::table('assignsubjdetail')
                ->where('assignsubjdetail.headerid', $assignedsubj->id)
                ->where('assignsubjdetail.teacherid', $myid->id)
                ->get();

            if(count($getassignedsubj) > 0){

                if(strtolower($assignedsubj->acadprogcode) == 'shs'){

                    foreach($getassignedsubj as $subject){

                        $subjectnames = DB::table('sh_subjects')
                            ->select(
                                'id',
                                'subjtitle as subjdesc',
                                'subjcode'
                            )
                            ->where('id', $subject->subjid)
                            ->where('isactive','1')
                            ->where('deleted','0')
                            ->get();

                        foreach($subjectnames as $subjectname){

                            array_push($subjectsarray, (object)array(
                                'id'                => $subjectname->id,
                                'subject'           => $subjectname->subjdesc,
                                'subjectcode'       => $subjectname->subjcode,
                                'sectionid'         => $assignedsubj->sectionid,
                                'sectionname'       => $assignedsubj->sectionname,
                                'glevelid'          => $assignedsubj->glevelid,
                                'academicprogram'   => $assignedsubj->acadprogcode
                            ));

                        }
    
                    }

                }
                else{

                    foreach($getassignedsubj as $subject){

                        $subjectnames = DB::table('subjects')
                            ->select(
                                'id',
                                'subjdesc',
                                'subjcode'
                            )
                            ->where('id', $subject->subjid)
                            ->where('isactive','1')
                            ->where('deleted','0')
                            ->get();

                        foreach($subjectnames as $subjectname){

                            array_push($subjectsarray, (object)array(
                                'id'                => $subjectname->id,
                                'subject'           => $subjectname->subjdesc,
                                'subjectcode'       => $subjectname->subjcode,
                                'sectionid'         => $assignedsubj->sectionid,
                                'glevelid'          => $assignedsubj->glevelid,
                                'sectionname'       => $assignedsubj->sectionname,
                                'academicprogram'   => $assignedsubj->acadprogcode
                            ));

                        }
    
                    }


                }

            } 

        }
        // return $subjectsarray;
        $teacherid = DB::table('teacher')->where('userid', auth()->user()->id)->first()->id;
        $block = DB::table('sh_blocksched')
            // ->select('sh_blocksched.*')
            ->select('sh_subjects.id','sh_subjects.subjtitle as subject','sh_subjects.subjcode as subjectcode','sections.id as sectionid','gradelevel.id as glevelid','sections.sectionname','academicprogram.acadprogcode as academicprogram')
            ->join('sh_blockscheddetail','sh_blocksched.id','=','sh_blockscheddetail.headerid')
            ->join('sh_subjects','sh_blocksched.subjid','=','sh_subjects.id')
            ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
            ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
            ->leftJoin('gradelevel','sections.levelid','=','gradelevel.id')
            ->leftJoin('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->join('sy','sh_blocksched.syid','=','sy.id')
            ->where('sh_blocksched.teacherid',$teacherid)
            ->where('sh_blocksched.deleted','0')
            ->where('sh_blockscheddetail.deleted','0')
            ->where('sh_sectionblockassignment.deleted','0')
            // ->where('gradelevel.deleted','0')
            ->where('sections.deleted','0')
            ->where('sy.isactive','1')
            ->distinct()
            ->get();

        if(count($block)>0)
        {
            foreach($block as $blockeach)
            {
                array_push($subjectsarray, $blockeach);
            }
        }

        if($id == 'dashboard'){

            return view('teacher.summaries.summaryattendancepersubject')
                ->with('currentdate', date('Y-m-d'))
                ->with('selectedsubject', "")
                ->with('subjects', collect($subjectsarray)->unique());

        }else{

            $attendance = array();

            if(strtolower($request->get('academicprogram')) == 'shs'){



            }else{
    
                $getstudents = DB::table('enrolledstud')
                    ->select(
                        'studinfo.id',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.lastname',
                        'studinfo.suffix'
                    )
                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                    ->join('sy','enrolledstud.syid','=','sy.id')
                    ->where('enrolledstud.levelid',$request->get('gradelevelid'))
                    ->where('enrolledstud.sectionid',$request->get('sectionid'))
                    ->where('sy.isactive','1')
                    ->get();
                
                if(count($getstudents) > 0){

                    foreach($getstudents as $student){

                        if($id == 'changedate'){

                            $getattendance = Db::table('studentsubjectattendance')
                                ->where('student_id', $student->id)
                                ->where('section_id', $request->get('sectionid'))
                                ->where('subject_id', $request->get('subjectid'))
                                ->where('date',$request->get('selecteddate'))
                                ->get();


                        }else{

                            $getattendance = Db::table('studentsubjectattendance')
                                ->where('student_id', $student->id)
                                ->where('section_id', $request->get('sectionid'))
                                ->where('subject_id', $request->get('subjectid'))
                                ->where('date',date('Y-m-d'))
                                ->get();
                                // return date('Y-m-d');

                        }
                        
                        if(count($getattendance) > 0){

                            array_push($attendance, (object)array(
                                'studentname'       => $student->lastname.', '.$student->firstname.' '.$student->middlename[0].' '.$student->suffix,
                                'status'            => $getattendance[0]->status,
                                'remarks'           => $getattendance[0]->remarks
                            ));

                        }

                    }

                }
    
            }
    
            if($id == 'changedate'){

                return  $attendance;


            }else{
                // return collect($subjectsarray)->unique();
                // return $attendance;
                return view('teacher.summaries.summaryattendancepersubject')
                    ->with('currentdate', date('Y-m-d'))
                    ->with('selectedsubject', $request->get('subjectid'))
                    ->with('selectedacademicprogram', $request->get('academicprogram'))
                    ->with('selectedsectionid', $request->get('sectionid'))
                    ->with('selectedgradelevelid', $request->get('gradelevelid'))
                    ->with('subjects', collect($subjectsarray)->unique())
                    ->with('attendance', $attendance);

            }


        }

    }

    // public function summaryattendancepersubjectgetattendance(Request $request)
    // {

    //     // return $

    // }

    public function summaryattendancepersubjectprint(Request $request)
    {


        $attendance = array();


        $gradelevel = DB::table('gradelevel')
            ->where('id',$request->get('printgradelevelid'))
            ->get();

        $sectionname = DB::table('sections')
            ->where('id',$request->get('printsectionid'))
            ->get();

        if(strtolower($request->get('printacademicprogram')) == 'shs'){



        }else{

            
            $subjectname = DB::table('subjects')
                ->where('id', $request->get('printsubjectid'))
                ->get();

            $getstudents = DB::table('enrolledstud')
                ->select(
                    'studinfo.id',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.lastname',
                    'studinfo.suffix'
                )
                ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                ->join('sy','enrolledstud.syid','=','sy.id')
                ->where('enrolledstud.levelid',$request->get('printgradelevelid'))
                ->where('enrolledstud.sectionid',$request->get('printsectionid'))
                ->where('sy.isactive','1')
                ->get();
            
            if(count($getstudents) == 0){

                return '';

            }else{

                foreach($getstudents as $student){

                    $getattendance = Db::table('studentsubjectattendance')
                        ->where('student_id', $student->id)
                        ->where('section_id', $request->get('printsectionid'))
                        ->where('subject_id', $request->get('printsubjectid'))
                        ->where('date',$request->get('changedate'))
                        ->get();
                
                    if(count($getattendance) > 0){

                        array_push($attendance, (object)array(
                            'studentname'       => $student->lastname.', '.$student->firstname.' '.$student->middlename[0].' '.$student->suffix,
                            'status'            => $getattendance[0]->status,
                            'remarks'           => $getattendance[0]->remarks
                        ));

                    }

                }

            }

        }

        $schoolinfo = Db::table('schoolinfo')
            ->get();

        $sy = Db::table('sy')
            ->where('isactive','1')
            ->get();

        $attendancedate = date('F d, Y', strtotime($request->get('changedate')));
            
        $pdf = PDF::loadview('teacher/pdf/summaryattendancebysubjectanddate',compact('gradelevel','sectionname','subjectname','attendance','schoolinfo','sy','attendancedate'))->setPaper('a4');

        return $pdf->stream('Attendance Summary by Subject.pdf');
        
        // summaryattendancebysubjectanddate.blade

    }
    public function summaryattendancepermonth($id, Request $request)
    {

        date_default_timezone_set('Asia/Manila');
        
        $sem = DB::table('semester')
            ->where('isactive','1')
            ->get();
        $syid = DB::table('sy')
                        ->where('isactive','1')
                        ->first();
        
        $sections = DB::table('teacher')
            ->select(
                'teacher.id',
                'sections.levelid',
                'gradelevel.levelname',
                'sections.id as sectionid',
                'sections.sectionname',
                'academicprogram.progname'
                )
            ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('teacher.userid',auth()->user()->id)
            ->where('sectiondetail.syid',$syid->id)
            ->where('sections.deleted','0')
            ->get();

            // return $sections;
        if(count($sections)>0){
            foreach($sections as $section)
            {
                $numberofstudents = Db::table('studinfo')
                    ->where('sectionid', $section->sectionid)
                    ->get();
                $section->numberofstudents = count($numberofstudents);
            }
        }

        return view('teacher.summaries.attendancereport.monthlyattendance')
            ->with('sections', $sections);
         

    }


}


class MYPDFSummaryAttendance extends TCPDF {

    //Page header
    public function Header() {
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.$schoollogo->picurl; 
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),15,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'Attendance', false, false, false, $reseth=true, $align='L', $autopadding=true);
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
