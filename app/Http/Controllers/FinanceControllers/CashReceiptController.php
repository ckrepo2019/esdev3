<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\Finance\CashReceiptSummaryModel;
class CashReceiptController extends Controller
{
    public function index()
    {
        // return 'sadsad';
        $terminals = Db::table('chrngterminals')->get();
        return view('finance.cashreceiptsummary.index')->with('terminals',$terminals);
    }
    public function filter(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        $dateexplode = explode(' - ', $request->get('selecteddaterange'));
        $dtFrom = $dateexplode[0];
        $dtTo = $dateexplode[1];
        $terminalno = $request->get('selectedterminal');

        $dtFrom = date_create($dtFrom);
        $dtFrom = date_format($dtFrom, 'Y-m-d 00:00');

        $dtTo = date_create($dtTo);
        $dtTo = date_format($dtTo, 'Y-m-d 23:59');

        $output = '';
        $gtotal = '';

        $datearray = array();

        array_push($datearray, $dtFrom);
        array_push($datearray, $dtTo);

        $coa = db::table('acc_coa')
            ->where('id', 2)
            ->first();

        if($terminalno == null)
        {
            $cash = db::table('chrngtrans')
                ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                ->where('cancelled', 0)
                ->where('posted', 1)
                ->whereBetween('transdate', $datearray)
                ->sum('chrngtransdetail.amount');

            $output .= '
                <tr class="">
                    <td class="crs-print">'.$coa->code.' - ' .$coa->account. '</td>
                    <td class="text-right crs-print">'.number_format($cash, 2).'</td>
                    <td class="text-right crs-print"></td>
                </tr>
                ';
            $getCRS = array();
            $terminals = Db::table('chrngterminals')->get();
            foreach($terminals as $terminal)
            {
                $getCRSquery = db::table('chrngtrans')
                    ->select(db::raw('acc_coa.code, acc_coa.`account`, sum(`chrngtransdetail`.`amount`) as credit'))
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                    ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                    ->where('terminalno', $terminal->id)
                    ->where('cancelled', 0)
                    ->where('posted', 1)
                    ->whereBetween('transdate', $datearray)
                    ->groupBy('glid')
                    ->get();
                if(count($getCRSquery)>0)
                {
                    foreach($getCRSquery as $crsquery)
                    {
                        array_push($getCRS,$crsquery);
                    }
                }
            }
        }
        else{
            $cash = db::table('chrngtrans')
                ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                ->where('cancelled', 0)
                ->where('terminalno', $terminalno)
                ->where('posted', 1)
                ->whereBetween('transdate', $datearray)
                ->sum('chrngtransdetail.amount');

            $output .= '
                <tr class="">
                    <td class="crs-print">'.$coa->code.' - ' .$coa->account. '</td>
                    <td class="text-right crs-print">'.number_format($cash, 2).'</td>
                    <td class="text-right crs-print"></td>
                </tr>
                ';
            $getCRS = db::table('chrngtrans')
                ->select(db::raw('acc_coa.code, acc_coa.`account`, sum(`chrngtransdetail`.`amount`) as credit'))
                ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                ->where('terminalno', $terminalno)
                ->where('cancelled', 0)
                ->where('posted', 1)
                ->whereBetween('transdate', $datearray)
                ->groupBy('glid')
                ->get();
        }
            


        
        $debit = 0;
        $credit = 0;
        foreach($getCRS as $CRS)
        {

            $credit += $CRS->credit;

            $output .= '
            <tr class="">
                <td class="crs-print">'.$CRS->code.' - ' .$CRS->account. '</td>
                <td class="text-right crs-print"></td>
                <td class="text-right crs-print">'.number_format($CRS->credit, 2).'</td>
            </tr>
            ';
        }

        $gtotal .='
            <th class="text-right crs-print" colspan="2">'.number_format($cash, 2).'</th>
            <th class="text-right crs-print" colspan="">'.number_format($credit, 2).'</th>
        ';

        $data = array(
            'output' => $output,
            'gtotal' =>$gtotal
        );

        echo json_encode($data);


    }
    public function export(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
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

        $dateexplode = explode(' - ', $request->get('selecteddaterange'));
        $dtFrom = $dateexplode[0];
        $dtTo = $dateexplode[1];
        $terminalno = $request->get('selectedterminal');

        $dtFrom = date_create($dtFrom);
        $dtFrom = date_format($dtFrom, 'Y-m-d 00:00');

        $dtTo = date_create($dtTo);
        $dtTo = date_format($dtTo, 'Y-m-d 23:59');

        $output = '';
        $gtotal = '';

        $datearray = array();

        array_push($datearray, $dtFrom);
        array_push($datearray, $dtTo);


        $coa = db::table('acc_coa')
            ->where('id', 2)
            ->first();

        if($request->get('exporttype') == 'pdf')
        {
            
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            $pdf->SetCreator('CK');
            $pdf->SetAuthor('CK Children\'s Publishing');
            $pdf->SetTitle($schoolinfo->schoolname.' - Account Receivables');
            $pdf->SetSubject('Account Receivables');
            
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $pdf->setLanguageArray($l);
            }
            
            $pdf->SetFont('dejavusans', '', 10);
            
            $pdf->AddPage();
        
                $html='
                
                <table border="1" cellpadding="2" >
                    <thead>
                        <tr>
                            <th style="font-size: 10px !important; font-weight: bold;" width="35" align="center">#</th>
                            <th style="font-size: 10px !important; font-weight: bold;" align="center" width="250" >Account</th>
                            <th style="font-size: 10px !important; font-weight: bold;" width="100" align="center">Department</th>
                            <th style="font-size: 10px !important; font-weight: bold;" align="center">Debit</th>
                            <th style="font-size: 10px !important; font-weight: bold;" align="center">Credit</th>
                        </tr>
                    </thead>
                    <tbody>';
                $count = 1;
        
        
                if($terminalno == null)
                {
                    $cash = db::table('chrngtrans')
                        ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                        ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                        ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                        ->where('cancelled', 0)
                        ->where('posted', 1)
                        ->whereBetween('transdate', $datearray)
                        ->sum('chrngtransdetail.amount');
                    $html.='<tr class="">
                                <td style="font-size: 10px !important;" width="35" align="center">'.$count.'</td>
                                <td style="font-size: 10px !important;" align="left" width="250" >'.$coa->code.' - ' .$coa->account. '</td>
                                <td style="font-size: 10px !important;" width="100" align="center"></td>
                                <td style="font-size: 10px !important;" align="center">'.number_format($cash, 2).'</td>
                                <td style="font-size: 10px !important;" align="center"></td>
                            </tr>';
                    $count+=1;

                    $getCRS = array();
                    $terminals = Db::table('chrngterminals')->get();
                    foreach($terminals as $terminal)
                    {
                        $getCRSquery = db::table('chrngtrans')
                            ->select(db::raw('acc_coa.code, acc_coa.`account`, sum(`chrngtransdetail`.`amount`) as credit'))
                            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                            ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                            ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                            ->where('terminalno', $terminal->id)
                            ->where('cancelled', 0)
                            ->where('posted', 1)
                            ->whereBetween('transdate', $datearray)
                            ->groupBy('glid')
                            ->get();
                        if(count($getCRSquery)>0)
                        {
                            foreach($getCRSquery as $crsquery)
                            {
                                array_push($getCRS,$crsquery);
                            }
                        }
                    }
                }
                else{
                    $cash = db::table('chrngtrans')
                        ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                        ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                        ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                        ->where('cancelled', 0)
                        ->where('terminalno', $terminalno)
                        ->where('posted', 1)
                        ->whereBetween('transdate', $datearray)
                        ->sum('chrngtransdetail.amount');
                    $html.='<tr class="">
                                <td style="font-size: 10px !important;" width="35" align="center">'.$count.'</td>
                                <td style="font-size: 10px !important;" align="left" width="250" >'.$coa->code.' - ' .$coa->account. '</td>
                                <td style="font-size: 10px !important;" width="100" align="center"></td>
                                <td style="font-size: 10px !important;" align="center">'.number_format($cash, 2).'</td>
                                <td style="font-size: 10px !important;" align="center"></td>
                            </tr>';
                    $count+=1;
                    $getCRS = db::table('chrngtrans')
                        ->select(db::raw('acc_coa.code, acc_coa.`account`, sum(`chrngtransdetail`.`amount`) as credit'))
                        ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                        ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                        ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                        ->where('terminalno', $terminalno)
                        ->where('cancelled', 0)
                        ->where('posted', 1)
                        ->whereBetween('transdate', $datearray)
                        ->groupBy('glid')
                        ->get();
                }
                
                
                $debit = 0;
                $credit = 0;
                
                foreach($getCRS as $CRS)
                {

                    $credit += $CRS->credit;
                    $count+=1;
                    $html.='
                    <tr class="">
                        <td style="font-size: 10px !important;" width="35" align="center">'.$count.'</td>
                        <td style="font-size: 10px !important;" align="left" width="250" >'.$CRS->code.' - ' .$CRS->account. '</td>
                        <td style="font-size: 10px !important;" width="100" align="center"></td>
                        <td style="font-size: 10px !important;" align="center"></td>
                        <td style="font-size: 10px !important;" align="center">'.number_format($CRS->credit, 2).'</td>
                    </tr>
                    ';
                }
                
                $html .='<tr class="">
                        <th style="font-size: 10px !important; font-weight: bold;" align="right" colspan="4">'.number_format($cash, 2).'</th>
                        <th style="font-size: 10px !important; font-weight: bold;" >'.number_format($credit, 2).'</th>
                        </tr>';
                $html .='</tbody>
                </table>';
                // output the HTML content
                
                set_time_limit(3000);
                $pdf->writeHTML($html, true, false, true, false, '');
                
                
                // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                
                // test custom bullet points for list
                
                
                // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                
                // reset pointer to the last page
                $pdf->lastPage();
                
                // ---------------------------------------------------------
                //Close and output PDF document
                $pdf->Output('Cash Receipt Summary.pdf', 'I');
                
        }else{
            
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

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(base_path().'/public/'.$schoolinfo->picurl);
            $drawing->setHeight(80);
            $drawing->setWorksheet($sheet);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(20);
            $drawing->setOffsetY(20);
            // $drawing->setRotation(25);
            $drawing->getShadow()->setVisible(true);
            $drawing->getShadow()->setDirection(45);

            $sheet->mergeCells('C2:F2');
            $sheet->setCellValue('C2', $schoolinfo->schoolname);
            $sheet->mergeCells('B10:E10');
            $sheet->setCellValue('A10','#');
            $sheet->setCellValue('B10','Account');
            $sheet->setCellValue('F10','Department');
            $sheet->setCellValue('G10','Debit');
            $sheet->setCellValue('H10','Credit');
            $sheet->getStyle('A10:H10')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('A10:H10')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('A10:H10')->getFont()->setBold(true);

        
            if($terminalno == null)
            {
                $cash = db::table('chrngtrans')
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                    ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                    ->where('cancelled', 0)
                    ->where('posted', 1)
                    ->whereBetween('transdate', $datearray)
                    ->sum('chrngtransdetail.amount');
                $getCRS = array();
                $terminals = Db::table('chrngterminals')->get();
                foreach($terminals as $terminal)
                {
                    $getCRSquery = db::table('chrngtrans')
                        ->select(db::raw('acc_coa.code, acc_coa.`account`, sum(`chrngtransdetail`.`amount`) as credit'))
                        ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                        ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                        ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                        ->where('terminalno', $terminal->id)
                        ->where('cancelled', 0)
                        ->where('posted', 1)
                        ->whereBetween('transdate', $datearray)
                        ->groupBy('glid')
                        ->get();
                    if(count($getCRSquery)>0)
                    {
                        foreach($getCRSquery as $crsquery)
                        {
                            array_push($getCRS,$crsquery);
                        }
                    }
                }
            }
            else{
                $cash = db::table('chrngtrans')
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                    ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                    ->where('cancelled', 0)
                    ->where('terminalno', $terminalno)
                    ->where('posted', 1)
                    ->whereBetween('transdate', $datearray)
                    ->sum('chrngtransdetail.amount');
                $getCRS = db::table('chrngtrans')
                    ->select(db::raw('acc_coa.code, acc_coa.`account`, sum(`chrngtransdetail`.`amount`) as credit'))
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                    ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                    ->where('terminalno', $terminalno)
                    ->where('cancelled', 0)
                    ->where('posted', 1)
                    ->whereBetween('transdate', $datearray)
                    ->groupBy('glid')
                    ->get();
            }
            $count = 1;
            $startcell = 11;

            $sheet->mergeCells('B'.$startcell.':E'.$startcell);
            $sheet->setCellValue('A'.$startcell,$count);
            $sheet->setCellValue('B'.$startcell,$coa->code.' - '.$coa->account);
            $sheet->setCellValue('F'.$startcell,'');
            $sheet->setCellValue('G'.$startcell,number_format($cash, 2));
            $sheet->setCellValue('H'.$startcell,'');
            $sheet->getStyle('G'.$startcell)->getNumberFormat()->setFormatCode( ' #,##0.00_-' );
            $count+=1;
            $startcell+=1;
            
            
            $debit = 0;
            $credit = 0;

            foreach($getCRS as $CRS)
            {
                $credit += $CRS->credit;
                $sheet->setCellValue('A'.$startcell,$count);
                $sheet->mergeCells('B'.$startcell.':E'.$startcell);
                $sheet->setCellValue('B'.$startcell,$CRS->code.' - ' .$CRS->account);
                $sheet->setCellValue('F'.$startcell,'');
                $sheet->setCellValue('G'.$startcell,'');
                $sheet->setCellValue('H'.$startcell,number_format($CRS->credit, 2));
                $sheet->getStyle('H'.$startcell)->getNumberFormat()->setFormatCode( ' #,##0.00_-' );
                $count+=1;
                $startcell+=1;
            }
                
            $sheet->setCellValue('F'.$startcell,'TOTAL');
            $sheet->setCellValue('G'.$startcell,number_format($cash, 2));
            $sheet->setCellValue('H'.$startcell,number_format($credit, 2));
            $sheet->getStyle('G'.$startcell.':H'.$startcell)->getNumberFormat()->setFormatCode( ' #,##0.00_-' );

            $sheet->getStyle('F'.$startcell)->getFont()->setBold(true);
            $sheet->getStyle('F'.$startcell.':H'.$startcell)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('F'.$startcell.':H'.$startcell)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Cash Receipt Summary.xlsx"');
            $writer->save("php://output");
        }
    }
}

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        // $this->Image('@'.file_get_contents('/home/xxxxxx/public_html/xxxxxxxx/uploads/logo/logo.png'),10,6,0,13);
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.$schoollogo->picurl;
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),15,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'Cash Receipt Summary', false, false, false, $reseth=true, $align='L', $autopadding=true);
        
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