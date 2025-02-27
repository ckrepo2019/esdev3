<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\Finance\StudentAssessmentModel;
class StudentAssessmentController extends Controller
{
    public function index()
    {
        // return 'asdas';
        $schoolyears = DB::table('sy')
            ->get();

        $gradelevels = DB::table('gradelevel')
            ->where('deleted','0')
            ->orderBy('sortid','asc')
            ->get();

        $semesters = DB::table('semester')
            ->get();

        $monthsetups = DB::table('monthsetup')
            ->get();

        return view('finance.studentassessment.index')
            ->with('schoolyears', $schoolyears)
            ->with('gradelevels', $gradelevels)
            ->with('semesters', $semesters)
            ->with('monthsetups', $monthsetups);
    }
    public function filter(Request $request)
    {
        $selectedschoolyear = $request->get('selectedschoolyear');
        $selectedsemester   = $request->get('selectedsemester');
        if($request->get('selectedmonth') == null)
        {
            $selectedmonth = null;
        }else{
            $selectedmonth = $request->get('selectedmonth');     //= date('m', strtotime($request->get('selectedmonth')));
        }
        $selectedgradelevel = $request->get('selectedgradelevel');
        $selectedsection    = $request->get('selectedsection');
        
        $students = StudentAssessmentModel::allstudents($selectedschoolyear,$selectedsemester,$selectedmonth,$selectedgradelevel,$selectedsection);
        $studentstotalamount = 0;
        $studentstotalamountpay = 0;
        $studentstotalbalance = 0;
        $html = '<table id="example1" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Level</th>
                            <th>Section</th>
                            <th>Total Amount</th>
                            <th>Total Amount Paid</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>';   
                    // <tr>
                    //     <th colspan="4" class="text-right"><strong>Total</strong></th>
                    //     <th id="headerstudentstotalamount">'.number_format($studentstotalamount, 2,'.',',').'</th>
                    //     <th id="headerstudentstotalamountpay">'.number_format($studentstotalamountpay, 2,'.',',').'</th>
                    //     <th id="headerstudentstotalbalance">'.number_format($studentstotalbalance, 2,'.',',').'</th>
                    // </tr>       
        if(count($students) == 0)
        {
            $html .= '';
        }
        else{
            foreach($students as $student)
            {
                $scheddetail = StudentAssessmentModel::studpayscheddetail($student->id,$selectedschoolyear,$selectedsemester,$selectedmonth);

                // return $scheddetail;

                $scheddetail->totalamount;
                $studentstotalamount += $scheddetail->totalamount; 
                $studentstotalamountpay += $scheddetail->totalamountpay;
                $studentstotalbalance += $scheddetail->balance;
                $student->totalamount       = number_format($scheddetail->totalamount, 2,'.',',');
                $student->totalamountpay    = number_format($scheddetail->totalamountpay, 2,'.',',');
                $student->balance           = number_format($scheddetail->balance, 2,'.',',');
                $html.='<tr>
                            <td></td>
                            <td class="text-left">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->suffix.'</td>   
                            <td>'.$student->levelname.'</td>    
                            <td>'.$student->sectionname.'</td>    
                            <td>'.$student->totalamount.'</td>    
                            <td>'.$student->totalamountpay.'</td>    
                            <td>'.$student->balance.'</td>    
                        </tr>';
            }
        }
        $html.='</tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total</th>
                        <th id="studentstotalamount">'.number_format($studentstotalamount, 2,'.',',').'</th>
                        <th id="studentstotalamountpay">'.number_format($studentstotalamountpay, 2,'.',',').'</th>
                        <th id="studentstotalbalance">'.number_format($studentstotalbalance, 2,'.',',').'</th>
                    </tr>
                </tfoot>
        </table>';
        return $html;
    }
    public function export(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $selectedschoolyear = $request->get('selectedschoolyear');
        $selectedsemester   = $request->get('selectedsemester');
        $selectedmonth  = $request->get('selectedmonth');
        $selectedgradelevel = $request->get('selectedgradelevel');
        $selectedsection    = $request->get('selectedsection');
        
        $students = StudentAssessmentModel::allstudents($selectedschoolyear,$selectedsemester,$selectedmonth,$selectedgradelevel,$selectedsection);
        $studentstotalamount = 0;
        $studentstotalamountpay = 0;
        $studentstotalbalance = 0;

        foreach($students as $student)
        {
            // return StudentAssessmentModel::studpayscheddetail($student->id,$selectedschoolyear,$selectedsemester,$selectedmonth);

            $scheddetail = StudentAssessmentModel::studpayscheddetail($student->id,$selectedschoolyear,$selectedsemester,$selectedmonth);
            // return $scheddetail;
            if($student->id == 865)
            {
                
            }

            // return $scheddetail->totalamountpay;
            $studentstotalamount += $scheddetail->totalamount; 
            $studentstotalamountpay += $scheddetail->totalamountpay;
            $studentstotalbalance += $scheddetail->balance;
            $student->totalamount       = $scheddetail->totalamount;
            $student->totalamountpay    =$scheddetail->totalamountpay;
            $student->balance           = $scheddetail->balance;
        }
        $students = collect($students)->sortBy('lastname')->values()->all();
        
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

        $selectedschoolyear = DB::table('sy')
            ->where('id', $selectedschoolyear)
            ->first()
            ->sydesc;
        if($request->get('selectedmonth') == null)
        {
            $selectedmonth = null;
        }else{
            $selectedmonth      = date('m', strtotime($request->get('selectedmonth')));
        }
        if($selectedgradelevel != null)
        {
            $levelname = DB::table('gradelevel')
                ->where('id', $selectedgradelevel)
                ->first()
                ->levelname;

            $selectedgradelevel = $levelname;
        }
        if($selectedsemester != null)
        {
            $semester = DB::table('semester')
                ->where('id', $selectedsemester)
                ->first()
                ->semester;

            $selectedsemester = $semester;
        }
        // return $request->get('export');
        if($request->get('exporttype') == 'excel')
        {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
            $sheet = $spreadsheet->getActiveSheet();
            $borderstyle = [
                // 'alignment' => [
                //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                // ],
                'borders' => [
                    'allborders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        // 'color' => ['argb' => 'FFFF0000'],
                    ],
                ]
            ];

			$picurl = explode('?', $schoolinfo->picurl);
			$picurl = $picurl[0];
			
			
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(base_path().'/public/'.$picurl);
            $drawing->setHeight(80);
            $drawing->setWorksheet($sheet);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(20);
            $drawing->setOffsetY(20);
            
            $drawing->getShadow()->setVisible(true);
            $drawing->getShadow()->setDirection(45);

            $sheet->mergeCells('C2:F2');
            $sheet->setCellValue('C2', $schoolinfo->schoolname);
            $sheet->setCellValue('C3', 'S.Y '.$selectedschoolyear);
            
            if($selectedgradelevel != null)
            {
                // $sheet->mergeCells('D7:E7');
                $sheet->setCellValue('C7', 'DEPARTMENT : '.strtoupper($selectedgradelevel));
            }
            if($selectedsemester != null)
            {
                // $sheet->mergeCells('F7:G7');
                $sheet->setCellValue('C8', 'SEMESTER : '.strtoupper($selectedsemester));
            }
            
            $sheet->getStyle('D:K')->getAlignment()->setHorizontal('center');
            foreach(array('A','B','C','D','E','F','G') as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            $sheet->setCellValue('A10','#');
            $sheet->mergeCells('B10:D10');
            $sheet->setCellValue('B10','Student Name');
            $sheet->setCellValue('E10','Grade level');
            $sheet->setCellValue('F10','Section');
            $sheet->setCellValue('G10','Total Amount');
            $sheet->setCellValue('H10','Paid');
            $sheet->setCellValue('I10','Balance');
            $sheet->getStyle('A10:I10')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('A10:I10')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('A10:I10')->getFont()->setBold(true);
            $count = 1;
            $startcell = 11;

            if(count($students)>0)
            {
                foreach($students as $student)
                {
                    $sheet->setCellValue('A'.$startcell,$count);
                    // $sheet->setCellValue('B'.$startcell,$student->sid);
                    $sheet->mergeCells('B'.$startcell.':D'.$startcell);
                    $sheet->setCellValue('B'.$startcell,$student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->suffix);
                    // $sheet->setCellValue('D'.$startcell,$student->acadprogcode);
                    $sheet->setCellValue('E'.$startcell,$student->levelname);
                    $sheet->setCellValue('F'.$startcell,$student->sectionname);
                    $sheet->setCellValue('G'.$startcell,$student->totalamount);
                    $sheet->setCellValue('H'.$startcell,$student->totalamountpay);
                    $sheet->setCellValue('I'.$startcell,$student->balance);
                    // $sheet->setCellValue('J'.$startcell,number_format($student->totalpayment,2,'.',''));
                    // $sheet->setCellValue('K'.$startcell,number_format($student->balance,2,'.',''));
                    $sheet->getStyle('G'.$startcell.':I'.$startcell)->getNumberFormat()->setFormatCode( '#,##0.00' );
                    $count+=1;
                    $startcell+=1;
                }
            }
            
            $sheet->setCellValue('F'.$startcell,'TOTAL : ');
            $sheet->setCellValue('G'.$startcell,'=SUM(G11:G'.($startcell-1).')');
            $sheet->setCellValue('H'.$startcell,'=SUM(H11:H'.($startcell-1).')');
            $sheet->setCellValue('I'.$startcell,'=SUM(I11:I'.($startcell-1).')');
            // $sheet->setCellValue('J'.$startcell,'=SUM(J10:J'.($startcell-1).')');
            // $sheet->setCellValue('K'.$startcell,'=SUM(K10:K'.($startcell-1).')');
            $sheet->getStyle('G'.$startcell.':I'.$startcell)->getNumberFormat()->setFormatCode( '#,##0.00' );

            $sheet->getStyle('F'.$startcell)->getFont()->setBold(true);
            $sheet->getStyle('G'.$startcell.':I'.$startcell)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('G'.$startcell.':I'.$startcell)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Student Assessment.xlsx"');
            $writer->save("php://output");
        }else{

            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // set document information
            $pdf->SetCreator('CK');
            $pdf->SetAuthor('CK Children\'s Publishing');
            $pdf->SetTitle($schoolinfo->schoolname.' - Student Assessment');
            $pdf->SetSubject('Student Assessment');
            
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
                
            $html = '
            <table style="font-size: 9px; font-weight: bold; padding-top: 5px;" >
                    <tr>
                        <td>S.Y '.$selectedschoolyear.' <br/>';
                        
                        if($selectedmonth != null){
                            $html.='AS OF : '.strtoupper($selectedmonth).' <br/>';
                        }
                        if($selectedgradelevel != null){
                            $html.='GRADE LEVEL : '.strtoupper($selectedgradelevel).' <br/>';
                        }
                        $html.='</td>
                                <td>';
                            if($selectedsemester != null)
                            {
                                $html.='SEMESTER : '.strtoupper($selectedsemester).' <br/>';
                            }
                            $html.='</td>
                    </tr>
                </table>';
                $count = 1;
                $html.='<table border="1" cellpadding="2" >
                            <thead>
                                <tr>
                                    <th style="font-size: 8px !important; font-weight: bold;" width="35" align="center">#</th>
                                    <th style="font-size: 8px !important; font-weight: bold;" width="150" align="center">Student Name</th>
                                    <th style="font-size: 8px !important; font-weight: bold;" align="center">Level</th>
                                    <th style="font-size: 8px !important; font-weight: bold;" align="center">Section</th>
                                    <th style="font-size: 8px !important; font-weight: bold;" align="center">Total Amount</th>
                                    <th style="font-size: 8px !important; font-weight: bold;" align="center">Total Amount Paid</th>
                                    <th style="font-size: 8px !important; font-weight: bold;" align="center">Balance</th>
                                </tr>
                            </thead>';
                            if(count($students)>0)
                            {
                                foreach($students as $student)
                                {
                                    $html.='<tr nobr="true">
                                        <td width="35" style="font-size: 8px !important;" align="center">'.$count.'</td>
                                        <td style="font-size: 8px !important;" width="150">'.$student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->suffix.'</td>
                                        <td style="font-size: 8px !important;" align="center">'.$student->levelname.'</td>
                                        <td style="font-size: 8px !important;"  align="center">'.$student->sectionname.'</td>
                                        <td style="font-size: 8px !important;" align="center"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> '.number_format($student->totalamount,2,'.',',').'</td>
                                        <td style="font-size: 8px !important;" align="center"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> '.number_format($student->totalamountpay,2,'.',',').'</td>
                                        <td style="font-size: 8px !important;" align="center"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> '.number_format($student->balance,2,'.',',').'</td>
                                    </tr>';
                                    $count+=1;
                                }
                            }
                            $html.='<tr>
                                        <th colspan="4" align="right" style="font-size: 9px !important; font-weight: bold;">TOTAL</th>
                                        <th id="overalltotalassessment" align="center" style="font-size: 8px !important; font-weight: bold;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> '.number_format($studentstotalamount,2,'.',',').'</th>
                                        <th id="overalltotaldiscount" align="center" style="font-size: 8px !important; font-weight: bold;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> '.number_format($studentstotalamountpay,2,'.',',').'</th>
                                        <th id="overalltotalnetassessed" align="center" style="font-size: 8px !important; font-weight: bold;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> '.number_format($studentstotalbalance,2,'.',',').'</th>
                                    </tr>
                
                        </table>';
                // output the HTML content
                
                set_time_limit(3000);
                $pdf->writeHTML($html, true, false, true, false, '');
                
                $pdf->lastPage();
                
                // ---------------------------------------------------------
                //Close and output PDF document
                $pdf->Output('Student Assessment.pdf', 'I');
        }
    }
    
}

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.explode('?',$schoollogo->picurl)[0];
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),15,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'Student Assessment', false, false, false, $reseth=true, $align='L', $autopadding=true);
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