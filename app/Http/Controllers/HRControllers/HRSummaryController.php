<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use TCPDF;
class HRSummaryController extends Controller
{
    public function summaryofattendance($id, Request $request)
    {

        date_default_timezone_set('Asia/Manila');

        if($id == 'dashboard')
        {

            $selecteddate = date('Y-m-d');

            $selectedstatus = 'all';

        }elseif($id == 'filter' || $id == 'print')
        {

            $selecteddate = $request->get('selecteddate');

            $selectedstatus = $request->get('selectedstatus');

        }
        
        $employees = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'teacher.suffix',
                'usertype.utype as designation'
            )
            ->join('usertype','teacher.usertypeid','=','usertype.id')
            ->where('isactive','1')
            ->orderby('lastname','asc')
            ->get();
            
        $present = array();
        $tardy = array();
        $absent = array();

        if($selectedstatus == 'all')
        {

            foreach($employees as $employee)
            {
    
                $employeeattendance = DB::table('teacherattendance')
                    ->where('teacher_id', $employee->id)
                    ->where('tdate', $selecteddate)
                    ->get();

                if(count($employeeattendance) == 0)
                {

                    array_push($absent, $employee);

                }else{
                    // return $employeeattendance[0]->out_am;
                    $attendanceinam         =   $employeeattendance[0]->in_am;
                    $attendanceoutam        =   $employeeattendance[0]->out_am;
                    $attendanceinpm         =   $employeeattendance[0]->in_pm;
                    $attendanceoutpm        =  $employeeattendance[0]->out_pm;

                    $checkcustomtimeschedstatus = DB::table('deduction_tardinesssetup')
                        ->where('status', '1')
                        ->first();

                    $tardyreport = 0;


                    if(strtolower($checkcustomtimeschedstatus->type) == 'custom')
                    {

                        $gettimesched = DB::table('employee_customtimesched')
                            ->where('employeeid', $employee->id)
                            ->where('deleted', '0')
                            ->get();
                            
                        if(count($gettimesched) == 0)
                        {


                            if($attendanceinam > date('H:i:s',strtotime('8:00:00 AM')) || $attendanceoutam < date('H:i:s',strtotime('12:00:00 PM')) || $attendanceinpm > date('H:i:s',strtotime('01:00:00 PM')) || $attendanceoutpm < date('H:i:s',strtotime('05:00:00 PM')))
                            {

                                $tardyreport+=1;

                            }
                            

                        }else{

                            // if($gettimesched[0]->amin < $employeeattendance[0]->in_am || $gettimesched[0]->amout > $employeeattendance[0]->out_am || $gettimesched[0]->pmin < $employeeattendance[0]->in_pm || $gettimesched[0]->pmout > $employeeattendance[0]->out_pm)
                            if($attendanceinam > date('H:i:s', strtotime($gettimesched[0]->amin.' AM')) || $attendanceoutam < date('H:i:s', strtotime($gettimesched[0]->amout.' PM')) || $attendanceinpm > date('H:i:s', strtotime($gettimesched[0]->pmin.' PM')) || $attendanceoutpm < date('H:i:s', strtotime($gettimesched[0]->pmout.' PM')))
                            {

                                $tardyreport+=1;

                            }

                        }

                        if($tardyreport == 0)
                        {

                            array_push($present, $employee);

                        }
                        elseif($tardyreport == 1)
                        {

                            array_push($tardy, $employee);

                        }

                    }else{

                        // if($employeeattendance[0]->in_am > date('H:i:s',strtotime('08:00:00 AM')) || $employeeattendance[0]->out_am < date('H:i:s',strtotime('12:00:00 PM')) || $employeeattendance[0]->in_pm > date('H:i:s',strtotime('01:00:00 PM')) || $employeeattendance[0]->out_pm < date('H:i:s',strtotime('05:00:00 PM')))
                        // return $attendanceoutam;
                        if($attendanceinam > date('H:i:s',strtotime('8:00:00 AM')) || $attendanceoutam < date('H:i:s',strtotime('12:00:00 PM')) || $attendanceinpm > date('H:i:s',strtotime('01:00:00 PM')) || $attendanceoutpm < date('H:i:s',strtotime('05:00:00 PM')))
                        {

                            $tardyreport+=1;

                        }

                        if($tardyreport == 0)
                        {

                            array_push($present, $employee);

                        }
                        elseif($tardyreport == 1)
                        {

                            array_push($tardy, $employee);

                        }

                    }

                }
    
            }

        }
        elseif($selectedstatus == 'present')
        {

            foreach($employees as $employee)
            {
    
                $employeeattendance = DB::table('teacherattendance')
                    ->where('teacher_id', $employee->id)
                    ->where('tdate', $selecteddate)
                    ->get();

                if(count($employeeattendance) > 0)
                {
                    $attendanceinam         =   $employeeattendance[0]->in_am;
                    $attendanceoutam        =   $employeeattendance[0]->out_am;
                    $attendanceinpm         =   $employeeattendance[0]->in_pm;
                    $attendanceoutpm        =  $employeeattendance[0]->out_pm;

                    $checkcustomtimeschedstatus = DB::table('deduction_tardinesssetup')
                        ->where('status', '1')
                        ->first();

                    $tardyreport = 0;

                    if(strtolower($checkcustomtimeschedstatus->type) == 'custom')
                    {


                        $gettimesched = DB::table('employee_customtimesched')
                            ->where('employeeid', $employee->id)
                            ->where('deleted', '0')
                            ->get();

                        if(count($gettimesched) == 0)
                        {

                            // if($employeeattendance[0]->in_am > '8:00:00' || $employeeattendance[0]->out_am < '12:00:00' || $employeeattendance[0]->in_pm > '01:00:00' || $employeeattendance[0]->out_pm < '05:00:00')
                            if($attendanceinam > date('H:i:s',strtotime('8:00:00 AM')) || $attendanceoutam < date('H:i:s',strtotime('12:00:00 PM')) || $attendanceinpm > date('H:i:s',strtotime('01:00:00 PM')) || $attendanceoutpm < date('H:i:s',strtotime('05:00:00 PM')))
                            {

                                $tardyreport+=1;

                            }
                            

                        }else{

                            // if($gettimesched[0]->amin < $employeeattendance[0]->in_am || $gettimesched[0]->amout < $employeeattendance[0]->out_am || $gettimesched[0]->pmin < $employeeattendance[0]->in_pm || $gettimesched[0]->pmout > $employeeattendance[0]->out_pm)
                            if($attendanceinam > date('H:i:s', strtotime($gettimesched[0]->amin.' AM')) || $attendanceoutam < date('H:i:s', strtotime($gettimesched[0]->amout.' PM')) || $attendanceinpm > date('H:i:s', strtotime($gettimesched[0]->pmin.' PM')) || $attendanceoutpm < date('H:i:s', strtotime($gettimesched[0]->pmout.' PM')))
                            {

                                $tardyreport+=1;

                            }

                        }

                        if($tardyreport == 0)
                        {

                            array_push($present, $employee);

                        }

                    }else{


                        // if($employeeattendance[0]->in_am > '8:00:00' || $employeeattendance[0]->out_am < '12:00:00' || $employeeattendance[0]->in_pm > '01:00:00' || $employeeattendance[0]->out_pm < '05:00:00')
                        if($attendanceinam > date('H:i:s',strtotime('8:00:00 AM')) || $attendanceoutam < date('H:i:s',strtotime('12:00:00 PM')) || $attendanceinpm > date('H:i:s',strtotime('01:00:00 PM')) || $attendanceoutpm < date('H:i:s',strtotime('05:00:00 PM')))
                        {

                            $tardyreport+=1;

                        }

                        if($tardyreport == 0)
                        {

                            array_push($present, $employee);

                        }

                    }

                }
    
            }

        }
        elseif($selectedstatus == 'tardy')
        {

            foreach($employees as $employee)
            {
    
                $employeeattendance = DB::table('teacherattendance')
                    ->where('teacher_id', $employee->id)
                    ->where('tdate', $selecteddate)
                    ->get();

                if(count($employeeattendance) > 0)
                {
                    $attendanceinam         =   $employeeattendance[0]->in_am;
                    $attendanceoutam        =   $employeeattendance[0]->out_am;
                    $attendanceinpm         =   $employeeattendance[0]->in_pm;
                    $attendanceoutpm        =  $employeeattendance[0]->out_pm;
                    
                    $checkcustomtimeschedstatus = DB::table('deduction_tardinesssetup')
                        ->where('status', '1')
                        ->first();

                    $tardyreport = 0;

                    if(strtolower($checkcustomtimeschedstatus->type) == 'custom')
                    {

                        $gettimesched = DB::table('employee_customtimesched')
                            ->where('employeeid', $employee->id)
                            ->where('deleted', '0')
                            ->get();

                        if(count($gettimesched) == 0)
                        {

                            // if($employeeattendance[0]->in_am > '8:00:00' || $employeeattendance[0]->out_am < '12:00:00' || $employeeattendance[0]->in_pm > '01:00:00' || $employeeattendance[0]->out_pm < '05:00:00')
                            if($attendanceinam > date('H:i:s',strtotime('8:00:00 AM')) || $attendanceoutam < date('H:i:s',strtotime('12:00:00 PM')) || $attendanceinpm > date('H:i:s',strtotime('01:00:00 PM')) || $attendanceoutpm < date('H:i:s',strtotime('05:00:00 PM')))
                            {

                                $tardyreport+=1;

                            }
                            

                        }else{

                            // if($gettimesched[0]->amin < $employeeattendance[0]->in_am || $gettimesched[0]->amout > $employeeattendance[0]->out_am || $gettimesched[0]->pmin < $employeeattendance[0]->in_pm || $gettimesched[0]->pmout > $employeeattendance[0]->out_pm)
                            if($attendanceinam > date('H:i:s', strtotime($gettimesched[0]->amin.' AM')) || $attendanceoutam < date('H:i:s', strtotime($gettimesched[0]->amout.' PM')) || $attendanceinpm > date('H:i:s', strtotime($gettimesched[0]->pmin.' PM')) || $attendanceoutpm < date('H:i:s', strtotime($gettimesched[0]->pmout.' PM')))
                            {

                                $tardyreport+=1;

                            }

                        }

                        if($tardyreport == 1)
                        {

                            array_push($tardy, $employee);

                        }

                    }else{



                        // if($employeeattendance[0]->in_am > '07:00:00' || $employeeattendance[0]->out_am < '12:00:00' || $employeeattendance[0]->in_pm > '01:00:00' || $employeeattendance[0]->out_pm < '05:00:00')
                        if($attendanceinam > date('H:i:s',strtotime('8:00:00 AM')) || $attendanceoutam < date('H:i:s',strtotime('12:00:00 PM')) || $attendanceinpm > date('H:i:s',strtotime('01:00:00 PM')) || $attendanceoutpm < date('H:i:s',strtotime('05:00:00 PM')))
                        {

                            $tardyreport+=1;

                        }

                        if($tardyreport == 1)
                        {

                            array_push($tardy, $employee);

                        }

                    }

                }
    
            }

        }
        elseif($selectedstatus == 'absent')
        {

            foreach($employees as $employee)
            {
    
                $employeeattendance = DB::table('teacherattendance')
                    ->where('teacher_id', $employee->id)
                    ->where('tdate', $selecteddate)
                    ->get();

                if(count($employeeattendance) == 0)
                {

                    array_push($absent, $employee);

                }
    
            }

        }
        
        if($id == 'print'){

            $schoolinfo = DB::table('schoolinfo')
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
                ->first();


            $selecteddate = date('F d, Y', strtotime($selecteddate));
                
            $preparedby = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first();

            $dateprepared =date('F d, Y h:i:s A');

            // $pdf = PDF::loadview('hr/pdf/summaryemployeeattendace',compact('present','tardy','absent','selectedstatus','selecteddate','schoolinfo','preparedby','dateprepared'))->setPaper('8.5x11');

            // return $pdf->stream('Payroll History.pdf'); 
            $pdf = new MYPDFHRSummary(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // set document information
            $pdf->SetCreator('CK');
            $pdf->SetAuthor('CK Children\'s Publishing');
            $pdf->SetTitle($schoolinfo->schoolname.' - Attendance Report - Employees');
            $pdf->SetSubject('Attendance Report - Employees');
            
            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            
            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            
            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
            
            // add a page
            $pdf->AddPage();
                
            $html = '';
            $count = 1;
                if(count($present) > 0)
                {
                    $html.='
                        <table border="1" cellpadding="2" style="font-size: 10px">
                            <thead style="text-align: center;font-size: 10px !important; font-weight: bold;">
                                <tr>
                                    <th colspan="2" style="text-align: center;">PRESENT</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <th>Designation</th>
                                </tr>
                            </thead>
                            <tbody>';

                                foreach($present as $presentemployee){
                                    $html.='<tr>
                                        <td>'.$count.'. '.$presentemployee->lastname.', '.$presentemployee->firstname.' '.$presentemployee->middlename[0].'. '.$presentemployee->suffix.'</td>
                                        <td style="text-align: center;">'.$presentemployee->designation.'</td>
                                    </tr>';
                                    $count+=1;
                                }
                                $html.='</tbody>
                        </table>
                        <table >
                            <thead>
                                <tr>
                                    <th ></th>
                                </tr>
                                </thead>
                                </table>';
                }
                if(count($tardy) > 0)
                {
                    $html.='
                    <table border="1" cellpadding="2" style="font-size: 10px">
                        <thead style="text-align: center;font-size: 10px !important; font-weight: bold;">
                            <tr>
                                <th colspan="2" style="text-align: center;">TARDY</th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Designation</th>
                            </tr>
                        </thead>
                        <tbody>';

                        foreach($tardy as $tardyemployee){
                            $html.='<tr>
                                <td>'.$count.'. '.$tardyemployee->lastname.', '.$tardyemployee->firstname.' '.$tardyemployee->middlename[0].'. '.$tardyemployee->suffix.'</td>
                                <td style="text-align: center;">'.$tardyemployee->designation.'</td>
                            </tr>';
                            $count+=1;

                        }
                        $html.='</tbody>
                    </table>
                    <table >
                        <thead>
                            <tr>
                                <th ></th>
                            </tr>
                            </thead>
                            </table>';
                }
                if(count($absent) > 0)
                {
                    $html.='
                    <table border="1" cellpadding="2" style="font-size: 10px">
                        <thead style="text-align: center;font-size: 10px !important; font-weight: bold;">
                            <tr>
                                <th colspan="2" style="text-align: center;">ABSENT</th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Designation</th>
                            </tr>
                        </thead>
                        <tbody>';

                        foreach($absent as $absentemployee){
                            $html.='<tr>
                                <td>'.$count.'. '.$absentemployee->lastname.', '.$absentemployee->firstname.' '.$absentemployee->middlename[0].'. '.$absentemployee->suffix.'</td>
                                <td style="text-align: center;">'.$absentemployee->designation.'</td>
                            </tr>';
                            $count+=1;

                        }
                        $html.='</tbody>
                    </table>
                    <br>';
                }
                // output the HTML content
                
                set_time_limit(3000);
                $pdf->writeHTML($html, true, false, true, false, '');
                
                $pdf->lastPage();
                
                // ---------------------------------------------------------
                //Close and output PDF document
                $pdf->Output('Student Assessment.pdf', 'I');

        }else{

            return view('hr.summaries.employeeattendancereport')
                ->with('present', $present)
                ->with('tardy', $tardy)
                ->with('absent', $absent)
                ->with('selectedstatus', $selectedstatus)
                ->with('selecteddate', $selecteddate);

        }

    }
    public function summaryofemployees($action, Request $request)
    {
        // return $action;
        $departments = Db::table('hr_departments')
            ->where('deleted','0')
            ->orderBy('department','asc')
            ->get();
        $designations = Db::table('usertype')
            ->where('deleted','0')
            ->orderBy('utype','asc')
            ->get();
            // 1 = casual; 2 = prov; 3 = regu;4 = parttime; 5 = substitute

        $employees = Db::table('teacher')
            ->select('teacher.id','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix','teacher.tid as teacherid','employee_personalinfo.gender','teacher.employmentstatus','usertype.id as designationid','usertype.utype as designation','hr_departments.id as departmentid','hr_departments.department')
            ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('hr_departments','teacher.schooldeptid','=','hr_departments.id')
            // ->leftJoin('hr_school_department','usertype.departmentid','=','hr_school_department.id')
            ->where('teacher.isactive','1')
            ->where('teacher.deleted','0')
            ->orderBy('teacher.lastname','asc')
            ->get();
            
            
        if($action == 'dashboard')
        {
            return view('hr.summaries.employeesummary')
            ->with('designations', $designations)
            ->with('departments', $departments);

        }
        // elseif($action == 'getdesignations'){

        //     $designations = Db::table('usertype')
        //         ->where('deleted','0')
        //         ->where('departmentid',$request->get('selecteddepartment'))
        //         ->where('utype','!=','PARENT')
        //         ->where('utype','!=','STUDENT')
        //         ->orderBy('utype','asc')
        //         ->get();

        //     return $designations; 

        // }
        elseif($action == 'filter'){

            // return $request->all();
            if($request->get('selecteddepartment') != null)
            {
                $employees = collect($employees)->where('departmentid', $request->get('selecteddepartment'))->values();
            }
            if($request->get('selecteddesignation') != null)
            {
                $employees = collect($employees)->where('designationid', $request->get('selecteddesignation'))->values();
            }
            if($request->get('selectedstatus') != null)
            {
                $employees = collect($employees)->where('employmentstatus', $request->get('selectedstatus'))->values();
            }
            if($request->get('selectedgender') != null)
            {
                $selectedgender = $request->get('selectedgender');
                // $employees = collect($employees)->where('gender','like', '%'.$request->get('selectedgender').'%')->values();
                $employees = collect($employees)->filter(function ($item) use ($selectedgender) {
                    // replace stristr with your choice of matching function
                    if(strtolower($item->gender) == strtolower($selectedgender))
                    {
                        return $item;
                    }
                });
            }
            // return $employees;
            
            return view('hr.summaries.employeesummaryfilter')->with('employees', $employees);
        }elseif($action == 'export')
        {
            $schoolinfo = DB::table('schoolinfo')
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
                ->first();
            $departmentname = "ALL";
            $designationname = "ALL";
            $status = "ALL";
            $gender = "";
            if($request->get('selecteddepartment') != null)
            {
                $employees = collect($employees)->where('departmentid', $request->get('selecteddepartment'))->values();
                $departmentname = Db::table('hr_departments')
                    ->where('id', $request->get('selecteddepartment'))
                    ->first()->department;
            }
            if($request->get('selecteddesignation') != null)
            {
                $employees = collect($employees)->where('designationid', $request->get('selecteddesignation'))->values();
                $designationname = Db::table('usertype')
                    ->where('id', $request->get('selecteddesignation'))
                    ->first()->utype;
            }
            if($request->get('selectedstatus') != null)
            {
                $employees = collect($employees)->where('employmentstatus', $request->get('selectedstatus'))->values();
                if($request->get('selectedstatus') == '1')
                {
                    $status='CASUAL';
                }elseif($request->get('selectedstatus') == '2'){
                    $status='PROVISIONARY';
                }elseif($request->get('selectedstatus') == '3'){
                    $status='REGULAR';
                }elseif($request->get('selectedstatus') == '4'){
                    $status='PART-TIME';
                }elseif($request->get('selectedstatus') == '5'){
                    $status='SUBSTITUTE';
                }
            }
            if($request->get('selectedgender') != null)
            {
                $selectedgender = $request->get('selectedgender');
                // $employees = collect($employees)->where('gender','like', '%'.$request->get('selectedgender').'%')->values();
                $employees = collect($employees)->filter(function ($item) use ($selectedgender) {
                    // replace stristr with your choice of matching function
                    if(strtolower($item->gender) == strtolower($selectedgender))
                    {
                        return $item;
                    }
                });
                $gender = strtoupper($selectedgender);
            }
            
            if($request->get('exporttype') == 'pdf')
            {
                // return $request->all();
    
                // $pdf = PDF::loadview('hr/pdf/summaryemployeeattendace',compact('present','tardy','absent','selectedstatus','selecteddate','schoolinfo','preparedby','dateprepared'))->setPaper('8.5x11');
    
                // return $pdf->stream('Payroll History.pdf'); 
                $pdf = new EmployeeSummaryPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                // set document information
                $pdf->SetCreator('CK');
                $pdf->SetAuthor('CK Children\'s Publishing');
                $pdf->SetTitle($schoolinfo->schoolname.' - Attendance Report - Employees');
                $pdf->SetSubject('Employees');
                
                // set header and footer fonts
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                
                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                
                // set margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
                
                // add a page
                $pdf->AddPage('L','A4');
                $countemp = 1;
                $html = '';
                $html.='<table cellpadding="2" style="font-size: 11px;text-transform: uppercase;">
                <thead>
                      <tr>
                          <th width="30%">
                              <div style="vertical-align: middle;font-weight: bold;">Department : '.strtoupper($departmentname).'</div>
                          </th>
                          <th width="30%">
                              <div style="vertical-align: middle;font-weight: bold;">Designation : '.strtoupper($designationname).'</div>
                          </th>
                          <th width="20%">
                              <div style="vertical-align: middle;font-weight: bold;">Employee Status : '.strtoupper($status).'</div>
                          </th>
                          <th width="15%">
                              <div style="vertical-align: middle;font-weight: bold;">Gender : '.strtoupper($gender).'</div>
                          </th>
                      </tr>
                </thead>
                </table>';
                $html.='<div style="font-size: 11px;font-weight: bold;">Total: '.count($employees).' Employees</div>';
                $html.='<table  border="1" cellpadding="2" style="font-size: 10px;text-transform: uppercase;">
                  <thead>
                        <tr>
                            <th width="5%" style="text-align:center;">
                                <div style="vertical-align: middle;">No</div>
                            </th>
                            <th width="10%" style="text-align:center;">
                                <div style="vertical-align: middle;">ID</div>
                            </th>
                            <th width="25%" style="text-align:center;">
                                <div style="vertical-align: middle;">Name</div>
                            </th>
                            <th width="5%" style="text-align:center;">
                                <div style="vertical-align: middle;">Gender</div>
                            </th>
                            <th width="20%" style="text-align:center;">
                                <div style="vertical-align: middle;">Department</div>
                            </th>
                            <th width="20%" style="text-align:center;">
                                <div style="vertical-align: middle;">Designation</div>
                            </th>
                            <th width="15%" style="text-align:center;">
                                <div style="vertical-align: middle;">Employment Status</div>
                            </th>
                        </tr>
                  </thead>';
                      if(count($employees)>0)
                      {
                        foreach($employees as $employee)
                        {
                            $html.='<tr nobr="true">
                                <td width="5%" style="text-align:center;">'.$countemp.'</td>
                                <td width="10%" style="text-align:center;">'.$employee->teacherid.'</td>
                                <td width="25%">'.strtoupper($employee->lastname).', '.strtoupper($employee->firstname).' '.strtoupper($employee->middlename).' '.strtoupper($employee->suffix).'</td>
                                <td width="5%" style="text-align:center;">'.strtoupper($employee->gender).'</td>
                                <td width="20%" style="text-align:center;">'.strtoupper($employee->department).'</td>
                                <td width="20%" style="text-align:center;">'.strtoupper($employee->designation).'</td>
                                <td width="15%" style="text-align:center;">';
                                    if($employee->employmentstatus == 1)
                                    {
                                        $html.='CASUAL';
                                    }
                                    elseif($employee->employmentstatus == 2)
                                    {
                                        $html.='PROVISIONARY';
                                    }
                                    elseif($employee->employmentstatus == 3)
                                    {
                                        $html.='REGULAR';
                                    }elseif($employee->employmentstatus == 4)
                                    {
                                        $html.='PART-TIME';
                                    }elseif($employee->employmentstatus == 5)
                                    {
                                        $html.='SUBSTITUTE';
                                    }
                                $html.='</td>
                            </tr>';
                            $countemp+=1;
                        }
                      }
                  $html.='</tbody>
                </table>';
                // output the HTML content
                
                set_time_limit(3000);
                $pdf->writeHTML($html, true, false, true, false, '');
                
                $pdf->lastPage();
                
                // ---------------------------------------------------------
                //Close and output PDF document
                $pdf->Output('Employees.pdf', 'I');
            }
        }
    }
}

class MYPDFHRSummary extends TCPDF {

    //Page header
    public function Header() {
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.$schoollogo->picurl;
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),15,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'Attendance Report - Employees', false, false, false, $reseth=true, $align='L', $autopadding=true);
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
class EmployeeSummaryPDF extends TCPDF {

    //Page header
    public function Header() {
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.$schoollogo->picurl;
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),15,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'Employees', false, false, false, $reseth=true, $align='L', $autopadding=true);
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