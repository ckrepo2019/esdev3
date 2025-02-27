<?php

namespace App\Http\Controllers\FinanceControllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\FinanceModel;
use App\DisplayModel;
use App\Models\Finance\FinanceUtilityModel;
use DB;
use NumConvert;
use PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class YearEndController extends Controller
{
    public function __construct()
    {
          // $this->middleware('auth');
    }

    public function yearend()
    {
        return view('finance/reports/yearend');
    }

    

    public function ye_generate(Request $request)
    {
        // $date = date_format(date_create($request->get('date')), 'Y-m-d');
        // $type = 
        $action = $request->get('action');
        $dates = array();
        $months = array();

        $y1 = $request->get('y1');
        $y2 = $request->get('y2');
        $y3 = $request->get('y3');
        $y4 = $request->get('y4');
        $y5 = $request->get('y5');
        $y6 = $request->get('y6');
        $y7 = $request->get('y7');
        $y8 = $request->get('y8');
        $y9 = $request->get('y9');
        $y10 = $request->get('y10');
        $y11 = $request->get('y11');
        $y12 = $request->get('y12');

        $begindate = '';
        $enddate = '';

        if($y1 != '')
        {
            $lastday = Carbon::createFromDate(null, 1, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');

            array_push($dates, $y1 . '-01-01 00:00 | ' . $y1 . $lastday);
            array_push($months, 'JANUARY ' . $y1);
        }

        if($y2 != '')
        {
            $lastday = Carbon::createFromDate(null, 2, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');

            array_push($dates, $y2 . '-02-01 00:00 | ' . $y2 . $lastday);
            array_push($months, 'FEBRUARY ' . $y2);
        }

        if($y3 != '')
        {
            $lastday = Carbon::createFromDate(null, 3, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');
            
            array_push($dates, $y3 . '-03-01 00:00 | ' . $y3 . $lastday);
            array_push($months, 'MARCH ' . $y3);
        }

        if($y4 != '')
        {
            $lastday = Carbon::createFromDate(null, 4, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');

            array_push($dates, $y4 . '-04-01 00:00 | ' . $y4 . $lastday);
            array_push($months, 'APRIL ' . $y4);
        }

        if($y5 != '')
        {
            $lastday = Carbon::createFromDate(null, 5, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');

            array_push($dates, $y5 . '-05-01 00:00 | ' . $y5 . $lastday);
            array_push($months, 'MAY ' . $y5);
        }

        if($y6 != '')
        {
            $lastday = Carbon::createFromDate(null, 6, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');

            array_push($dates, $y6 . '-06-01 00:00 | ' . $y6 . $lastday);
            array_push($months, 'JUNE ' . $y6);

            // return $lastday;
        }

        if($y7 != '')
        {
            $lastday = Carbon::createFromDate(null, 7, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');

            array_push($dates, $y7 . '-07-01 00:00 | ' . $y7 . $lastday);
            array_push($months, 'JULY ' . $y7);
        }

        if($y8 != '')
        {
            $lastday = Carbon::createFromDate(null, 8, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');

            array_push($dates, $y8 . '-08-01 00:00 | ' . $y8 . $lastday);
            array_push($months, 'AUGUST ' . $y8);
        }

        if($y9 != '')
        {
            $lastday = Carbon::createFromDate(null, 9, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');

            array_push($dates, $y9 . '-09-01 00:00 | ' . $y9 . $lastday);
            array_push($months, 'SEPTEMBER ' . $y9);
        }

        if($y10 != '')
        {
            $lastday = Carbon::createFromDate(null, 10, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');
            array_push($dates, $y10 . '-10-01 00:00 | ' . $y10 . $lastday);
            array_push($months, 'OCTOBER ' . $y10);
        }

        if($y11 != '')
        {
            $lastday = Carbon::createFromDate(null, 11, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');

            array_push($dates, $y11 . '-11-01 00:00 | ' . $y11 . $lastday);
            array_push($months, 'NOVEMBER ' . $y11);
        }

        if($y12 != '')
        {
            $lastday = Carbon::createFromDate(null, 12, 1)->endOfMonth();
            $lastday = $lastday->format('-m-d 23:59');

            array_push($dates, $y12 . '-12-01 00:00 | ' . $y12 . $lastday);
            array_push($months, 'DECEMBER ' . $y12);
        }


        $collect_dates = collect($dates);
        // return $collect_dates;
        $sort_dates = $collect_dates->sort();
        // return $sort_dates;
        
        foreach($sort_dates as $date)
        {
            if($begindate == '')
            {
                $begindate = $date;
            }

            $enddate = $date;
        }

        $_begin = explode(' | ', $begindate);
        $_begin = $_begin[0];

        $_end = explode(' | ', $enddate);
        $_end = $_end[1];

        $transactions = collect();
        $transbody = collect();
        $items = collect();
        $rowtotal = collect();
        $arrayrow = array();

        $headerlist = '';
        $bodylist = '';
        $gentotal = 0;

        // return 'begin' . $_begin . ' end' . $_end;


        $transaction1 = db::table('chrngtrans')
            ->select(db::raw('transdate,chrngcashtrans.`particulars`, sum(amount) as amount, itemid, classid'))
            ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            ->whereBetween('transdate', [$_begin, $_end])
            ->where('chrngcashtrans.deleted', 0)
            ->where('cancelled', 0)
            ->where('itemid', '!=', null)
            ->groupBy(db::raw('YEAR(transdate), MONTH(transdate), chrngcashtrans.itemid'))
            ->orderBy('transdate')
            ->get();

        $transaction2 = db::table('chrngtrans')
            ->select(db::raw('transdate, description as particulars, sum(amount) as amount, itemid, classid'))
            ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            ->join('itemclassification', 'chrngcashtrans.classid', '=', 'itemclassification.id')
            ->whereBetween('transdate', [$_begin, $_end])
            ->where('chrngcashtrans.deleted', 0)
            ->where('cancelled', 0)
            ->where('itemid', null)
            ->where('kind', '!=', 'item')
            ->groupBy(db::raw('YEAR(transdate), MONTH(transdate), chrngcashtrans.classid'))
            ->orderBy('transdate')
            ->get();

        $transaction3 = db::table('chrngtrans')
            ->select(db::raw('transdate, chrngcashtrans.particulars, sum(amount) as amount, itemid, classid'))
            ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            ->whereBetween('transdate', [$_begin, $_end])
            ->where('chrngcashtrans.deleted', 0)
            ->where('cancelled', 0)
            ->where('itemid', null)
            ->where('kind', 'item')
            ->groupBy(db::raw('YEAR(transdate), MONTH(transdate), chrngcashtrans.classid'))
            ->orderBy('transdate')
            ->get();

        $transactions = $transactions->merge($transaction1);
        $transactions = $transactions->merge($transaction2);
        $transactions = $transactions->merge($transaction3);
        $transactions = $transactions->sortBy('particulars');
        $transbody = $transactions;

        // return $months;

        $headerlist .='
            <tr>
                <th class="text-left print_border-bottom print_border-top">PARTICULARS</th>
        ';

        foreach($months as $month)
        {
            $headerlist .='
                <th class="text-center print_border-bottom print_border-top">'.$month.'</th>
            ';
        }

        $headerlist .='
            <th class="text-center print_border-bottom print_border-top">TOTAL</th>
        ';

        $_particulars = '';

        foreach($transactions as $trans)
        {   
            if($_particulars == $trans->particulars)
            {
                goto skip;
            }

            if($_particulars == '')   
            {
                $_particulars = $trans->particulars;
            }


            $coltotal = 0;
            $bodylist .='
                <tr class-id="'.$trans->classid.'" item-id="'.$trans->itemid.'">
                    <td class="print_border-bottom">'.$trans->particulars.'</td>
            ';





            foreach($collect_dates as $date)
            {
                $_dates = explode(' | ', $date);
                $_datefrom = date_format(date_create($_dates[0]), 'Y-m-d 00:00');
                $_dateto = date_format(date_create($_dates[1]), 'Y-m-d 23:59');
                
                $items = collect();
                if($trans->itemid != null)
                {
                    // $items = $transbody->whereBetween('transdate', [$_datefrom, $_dateto]);
                    $items = $transbody
                        // ->where('itemid', $trans->itemid)
                        ->whereBetween('transdate', [$_datefrom, $_dateto])
                        // ->where('itemid', '!=', null)
                        ->where('particulars', $trans->particulars)
                        ->sum('amount');
                }
                else
                {
                    $items = $transbody
                        // ->where('classid', $trans->classid)
                        ->whereBetween('transdate', [$_datefrom, $_dateto])
                        // ->where('itemid', null)
                        ->where('particulars', $trans->particulars)
                        ->sum('amount');
                }

                if($items)
                {
                    // if($items->amount > 0)
                    if($items > 0)
                    {
                        $bodylist .='
                            <td class="text-right print_border-bottom print_border-left">'.number_format($items, 2).'</td>
                        ';

                        $coltotal += $items;
                        $gentotal += $items;

                        array_push($arrayrow, (object)[
                            'date' => $date,
                            'amount' => $items
                        ]);



                        // echo $items->transdate . ' ' . $items->particulars . ' ' . $items->amount . '<br>';
                    }
                    else
                    {
                        $bodylist .='
                            <td class="print_border-bottom print_border-left"></td>
                        ';       
                    }
                }
                else
                {
                    $bodylist .='
                        <td class="print_border-bottom print_border-left"></td>
                    ';
                }
                
            }

            if($coltotal > 0)
            {
                $bodylist .='
                    <td class="text-bold text-right print_border-bottom print_border-left">'.number_format($coltotal, 2).'</td>
                ';
            }
            else
            {
                $bodylist .='
                    <td class="print_border-bottom print_border-left"></td>
                ';
            }

            $bodylist .='
                </tr>
            ';

            $_particulars = $trans->particulars;

            skip:


        }

        $bodylist .='
            <tr>
                <td class="text-right text-bold print_border-top print_border-bottom">TOTAL: </td>
        ';

        $rowtotal = collect($arrayrow);

        // return $_dates;

        foreach($collect_dates as $date)
        {
            $_row = $rowtotal->where('date', $date)->sum('amount');

            $bodylist .='
                <td class="text-right text-bold print_border-top print_border-bottom print_border-left">'.number_format($_row, 2).'</td>
            ';
        }

        $bodylist .='
                <td class="text-right text-bold print_border-top print_border-bottom print_border-left">'.number_format($gentotal, 2).'</td>
            </tr>
        ';


        $data = array(
            'headerlist' => $headerlist,
            'bodylist' => $bodylist
        );

        if($action == 'generate')
        {
            return $data;        }
        else
        {
            // return view('finance.reports.pdf.pdf_yearend', $data);
            $pdf = PDF::loadView('finance.reports.pdf.pdf_yearend', $data)->setPaper('legal','landscape');
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream('Year-endReport.pdf');
        }

    }

    public function ye_export(Request $request)
    {
        $date = $request->get('dcpr_date');
        $orfrom = $request->get('orfrom');
        $orto = $request->get('orto');
        $type = $request->get('type');

        // $returndate = date_format(date_create($date), 'mdY');
        // $displaydate = date_format(date_create($date), 'M d, Y');
        $month = $request->get('mc_month');
        $year = $request->get('mc_year');

        $displaydate = date_format(date_create('2022-' . $month . '-01'), 'F') . ' ' . $year;
        $datefrom = date_format(date_create($year . '-' . $month . '-' . 01), 'Y-m-d 00:00');
        $dateto = date_format(date_create($year . '-' . $month . '-' . 01), 'Y-m-t 23:59');

        // return $displaydate;

        // if($type == 'date')
        // {
        //     $orlist = db::table('chrngtrans')
        //         ->select('ornum')
        //         ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
        //         ->whereBetween('transdate', [$date . ' 00:00', $date . ' 23:59'])
        //         ->where('cancelled', 0)
        //         // ->where('chrngcashtrans.particulars', '!=', 'PTA')
        //         // ->where('chrngcashtrans.particulars', '!=', 'BACK ACCOUNTS')
        //         ->where('deleted', 0)
        //         ->groupBy('ornum')
        //         ->orderBy('ornum')
        //         ->first();
        // }
        // else
        // {
        //     $orlist = db::table('chrngtrans')
        //         ->select('ornum')
        //         ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
        //         ->whereBetween('ornum', [$orfrom, $orto])
        //         ->where('cancelled', 0)
        //         // ->where('chrngcashtrans.particulars', '!=', 'PTA')
        //         // ->where('chrngcashtrans.particulars', '!=', 'BACK ACCOUNTS')
        //         ->where('deleted', 0)
        //         ->groupBy('ornum')
        //         ->orderBy('ornum')
        //         ->first();   
        // }

        // $orfirst = $orlist->ornum;

        // if($type == 'date')
        // {
        //     $orlist = db::table('chrngtrans')
        //         ->select('ornum')
        //         ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
        //         ->whereBetween('transdate', [$date . ' 00:00', $date . ' 23:59'])
        //         ->where('cancelled', 0)
        //         // ->where('chrngcashtrans.particulars', '!=', 'PTA')
        //         // ->where('chrngcashtrans.particulars', '!=', 'BACK ACCOUNTS')
        //         ->where('deleted', 0)
        //         ->groupBy('ornum')
        //         ->orderBy('ornum', 'DESC')
        //         ->first();
        // }
        // else
        // {
        //     $orlist = db::table('chrngtrans')
        //         ->select('ornum')
        //         ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
        //         ->whereBetween('ornum', [$orfrom, $orto])
        //         ->where('cancelled', 0)
        //         // ->where('chrngcashtrans.particulars', '!=', 'PTA')
        //         // ->where('chrngcashtrans.particulars', '!=', 'BACK ACCOUNTS')
        //         ->where('deleted', 0)
        //         ->groupBy('ornum')
        //         ->orderBy('ornum', 'DESC')
        //         ->first();
        // }

        // $orlast = $orlist->ornum;

        $items = array();
        $headerlist = '<tr>';
        $bodylist = '';
        $arraygtotal = array();

        array_push($items, 'DATE');
        array_push($items, 'OR NUMBER');
        array_push($items, 'TOTAL AMOUNT');

        // if($type == 'date')
        // {
        //     $chrngtrans = db::table('chrngtrans')
        //         ->select('ornum', 'studname', 'chrngcashtrans.particulars')
        //         ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
        //         ->whereBetween('transdate', [$date . ' 00:00', $date . ' 23:59'])
        //         ->where('cancelled', 0)
        //         // ->where('chrngcashtrans.particulars', '!=', 'PTA')
        //         // ->where('chrngcashtrans.particulars', '!=', 'BACK ACCOUNTS')
        //         ->where('deleted', 0)
        //         ->groupBy('particulars')
        //         ->orderBy('particulars')
        //         ->get();
        // }
        // else
        // {
        //     $chrngtrans = db::table('chrngtrans')
        //         ->select('ornum', 'studname', 'chrngcashtrans.particulars')
        //         ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
        //         ->whereBetween('ornum', [$orfrom, $orto])
        //         ->where('cancelled', 0)
        //         // ->where('chrngcashtrans.particulars', '!=', 'PTA')
        //         // ->where('chrngcashtrans.particulars', '!=', 'BACK ACCOUNTS')
        //         ->where('deleted', 0)
        //         ->groupBy('particulars')
        //         ->orderBy('particulars')
        //         ->get();
        // }

        $chrngtrans = db::table('chrngtrans')
            ->select('chrngcashtrans.particulars')
            ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('cancelled', 0)
            // ->where('chrngcashtrans.particulars', '!=', 'PTA')
            // ->where('chrngcashtrans.particulars', '!=', 'BACK ACCOUNTS')
            ->where('deleted', 0)
            // ->groupBy(db::raw('day(transdate), particulars'))
            ->groupBy('particulars')
            ->orderBy('particulars')
            ->get();

        $genitem = '';

        $itemcount = count($chrngtrans);

        foreach($chrngtrans as $trans)
        {
            $item = explode(' GRADE ', $trans->particulars);
            if(!in_array($item[0], $items))
            {
                // $genitem = expload(' ', $item[0]);
                // if(strpos($genitem[0], $))
                    array_push($items, $item[0]);
            }

            // echo $item[0] . '<br>';
        }

        array_push($items, 'TOTAL');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getStyle('B1')->getFont()->setSize(14);
        $sheet->getStyle('B1')->getFont()->setName('Arial');
        $sheet->getStyle('B1')->getFont()->setBold(true);

        $sheet->getStyle('B2')->getFont()->setSize(12);
        $sheet->getStyle('B2')->getFont()->setName('Arial');
        $sheet->getStyle('B2')->getFont()->setBold(false);

        $sheet->getStyle('B4')->getFont()->setSize(12);
        $sheet->getStyle('B4')->getFont()->setName('Arial');
        $sheet->getStyle('B4')->getFont()->setBold(true);

        $sheet->getStyle('B5')->getFont()->setSize(12);
        $sheet->getStyle('B5')->getFont()->setName('Arial');
        $sheet->getStyle('B5')->getFont()->setBold(true);

        $sheet->getStyle('B6')->getFont()->setSize(12);
        $sheet->getStyle('B6')->getFont()->setName('Arial');
        $sheet->getStyle('B6')->getFont()->setBold(true);

        $sheet->setCellValue('B1', db::table('schoolinfo')->first()->schoolname);
        $sheet->setCellValue('B2', db::table('schoolinfo')->first()->address);
        $sheet->setCellValue('B4', 'Collection for the Month of ' . $displaydate);
        // $sheet->setCellValue('B5', $displaydate);
        // $sheet->setCellValue('B6', 'OR # ' . $orfirst . ' - ' . $orlast);

        $sheet->getColumnDimension('A')->setWidth('3', 'pt');
        $sheet->getColumnDimension('B')->setWidth('13', 'pt');
        $sheet->getColumnDimension('C')->setWidth('40', 'pt');
        $sheet->getColumnDimension('D')->setWidth('15', 'pt');

        $hcol = 'B';
        $sheet->getStyle('A9')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        foreach($items as $_item)
        {
            // echo $_item . '<br>';
            if(strpos($_item, 'Balance forwarded from SY') !== false)
            {
                $_item = 'BALANCE FORWARDED';
            }


            $sheet->setCellValue($hcol . '9', $_item);
            $sheet->getStyle($hcol . '9')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $hcol ++;

            // if($_item == 'NAME')
            // {



            //     $headerlist .='
            //             <th class="text-center bg-gray-dark" style="width:300px !important">'.$_item.'</th>
            //     ';
            // }
            // else
            // {
            //     $headerlist .='
            //             <th class="text-center bg-gray-dark" style="width:120px">'.$_item.'</th>
            //     ';
            // }
        }

        $array_bodylist = array();
        $grandtotal = 0;
        $row = 10;

        $chrngtransaction = db::table('chrngtrans')
            ->select(db::raw('transdate, ornum, studname, sum(amountpaid) as totalamount'))
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('cancelled', 0)
            ->groupBy(db::raw('day(transdate)'))
            ->orderBy('transdate')
            ->get();

        // if($type == 'date')
        // {
        //     $chrngtransaction = db::table('chrngtrans')
        //         ->select(db::raw('ornum, studname, sum(amountpaid) as totalamount'))
        //         ->whereBetween('transdate', [$date . ' 00:00', $date . ' 23:59'])
        //         ->where('cancelled', 0)
        //         ->groupBy('ornum')
        //         ->orderBy('ornum')
        //         ->get();
        // }
        // else
        // {
        //     $chrngtransaction = db::table('chrngtrans')
        //         ->select(db::raw('ornum, studname, sum(amountpaid) as totalamount'))
        //         ->whereBetween('ornum', [$orfrom, $orto])
        //         ->where('cancelled', 0)
        //         ->groupBy('ornum')
        //         ->orderBy('ornum')
        //         ->get();   
        // }

        $rcount = 1;

        foreach($chrngtransaction as $_trans)
        {
            $ornum = 0;
            $rangefrom = 0;
            $rangeto = 0;

            $_date = date_format(date_create($_trans->transdate), 'Y-m-d');

            $_trx = db::table('chrngtrans')
                ->whereBetween('transdate', [$_date . ' 00:00', $_date . ' 23:59'])
                ->where('cancelled', 0)
                ->get(); 

            foreach($_trx as $t)
            {
                if($rangefrom == 0)
                {
                    $rangefrom = $t->ornum;
                }

                $ornum = $t->ornum;
            } 

            $rangeto = $ornum;

            $sheet->setCellValue('A' .$row, $rcount);
            $sheet->setCellValue('B' .$row, $_date);
            $sheet->setCellValue('C' .$row, $rangefrom . ' - ' . $rangeto);
            $sheet->setCellValue('D' .$row, $_trans->totalamount);
            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

            $sheet->getStyle('A' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('B' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('C' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('D' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $grandtotal += $_trans->totalamount;

            $hcol = 'E';


            foreach($items as $_item)
            {
                if($_item != 'OR NUMBER' && $_item != 'DATE' && $_item != 'TOTAL AMOUNT' && $_item != 'TOTAL')
                {
                    // echo $_trans->ornum . ' - ' . $_item .  '<br>';
                    $trx = db::table('chrngtrans')
                        ->select(db::raw('ornum, studname, sum(chrngtrans.amountpaid) as totalamount, chrngcashtrans.particulars, sum(chrngcashtrans.amount) as amount'))
                        ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                        // ->where('ornum', $_trans->ornum)
                        ->whereBetween('transdate', [$_date . ' 00:00', $_date . ' 23:59'])
                        ->where('chrngcashtrans.particulars', $_item)
                        ->where('chrngcashtrans.deleted', 0)
                        ->where('cancelled', 0)
                        ->first();

                    if($trx->ornum != null)
                    {   
                        // echo 'ornum: ' . $trx->ornum . ' particulars: ' . $trx->particulars . ' amount: ' . $trx->amount . '<br>';
                        $arrayamount = 0;

                        array_push($arraygtotal, (object)[
                            $_item => $trx->amount
                        ]);

                        // $bodylist .='
                        //     <td style="width:70px" class="text-right">'.number_format($trx->amount, 2).'</td>
                        // ';

                        $sheet->setCellValue($hcol .$row, $trx->amount);
                        $sheet->getStyle($hcol . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                        $sheet->getColumnDimension($hcol)->setWidth('15', 'pt');
                        $sheet->getStyle($hcol . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    }
                    else
                    {
                        $sheet->setCellValue($hcol .$row, '');
                        $sheet->getStyle($hcol . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                        $sheet->getColumnDimension($hcol)->setWidth('15', 'pt');
                        $sheet->getStyle($hcol . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    } 
                    $hcol++;
                }
            }

            $sheet->setCellValue($hcol .$row, $_trans->totalamount);
            $sheet->getStyle($hcol . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getColumnDimension($hcol)->setWidth('15', 'pt');
            $sheet->getStyle($hcol . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $row++;
            $rcount++;
        }

        $arraygtotal = collect($arraygtotal);

        $sumtotal = array();

        foreach($items as $_item)
        {
            if($arraygtotal->sum($_item) > 0)
            {
                array_push($sumtotal, $arraygtotal->sum($_item));    
            }

            // if($type == 'date')
            // {
            //     $sum = db::table('chrngtrans')
            //         ->select(db::raw('sum(chrngcashtrans.amount) as amount'))
            //         ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            //         ->whereBetween('transdate', [$date . ' 00:00', $date . ' 23:59'])
            //         ->where('cancelled', 0)
            //         ->where('deleted', 0)
            //         // ->where('chrngcashtrans.particulars', $_item)
            //         ->where(function($q) use($_item){
            //             if(strpos($_item, 'TUITION') !== false)
            //             {
            //                 $q->where('chrngcashtrans.particulars', 'like', '%TUITION%');
            //             }
            //             else
            //             {
            //                 $q->where('chrngcashtrans.particulars', $_item);
            //             }
            //         })
            //         ->first();
            // }
            // else
            // {
            //     $sum = db::table('chrngtrans')
            //         ->select(db::raw('sum(chrngcashtrans.amount) as amount'))
            //         ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            //         ->whereBetween('ornum', [$orfrom, $orto])
            //         ->where('cancelled', 0)
            //         ->where('deleted', 0)
            //         ->where('chrngcashtrans.particulars', $_item)
            //         ->first();
            // }

            // if($sum->amount > 0)
            // {
            //     array_push($sumtotal, $sum->amount);
            // }
        }

        $sheet->getStyle($hcol . $row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $hcol = 'D';
        $sheet->setCellValue('B' .$row, 'TOTAL');
        $sheet->getStyle('B' .$row)->getFont()->setBold(true);
        $sheet->getStyle('B' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('B' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('C' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $sheet->setCellValue($hcol .$row, $grandtotal);
        $sheet->getStyle($hcol . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle($hcol . $row)->getFont()->setBold(true);
        $sheet->getStyle($hcol . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $hcol ++;

        foreach($sumtotal as $_sum)
        {
            $sheet->setCellValue($hcol .$row, $_sum);
            $sheet->getStyle($hcol . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle($hcol . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle($hcol . $row)->getFont()->setBold(true);
            $hcol ++;
        }

        $sheet->setCellValue($hcol .$row, $grandtotal);
        $sheet->getStyle($hcol . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle($hcol . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle($hcol . $row)->getFont()->setBold(true);

        $sig = db::table('finance_sigs')
            ->where('id', 2)
            ->first();

        $row ++;
        $row ++;
        $row ++;
        $row ++;
        $sheet->setCellValue('B' .$row, $sig->title_1 . ':');
        $sheet->setCellValue('D' .$row, $sig->title_2 . ':');

        $row ++;
        $sheet->setCellValue('B' .$row, $sig->sig_1);
        $sheet->setCellValue('D' .$row, $sig->sig_2);

        $sheet->getStyle('B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('D' . $row)->getFont()->setBold(true);

        $row ++;
        $sheet->setCellValue('B' .$row, $sig->designation_1);
        $sheet->setCellValue('D' .$row, $sig->designation_2);

        $row++;
        $row++;
        $row++;
        $row++;

        $sheet->setCellValue('C' .$row, $sig->title_3 .':');
        $row++;
        $sheet->setCellValue('C' .$row, $sig->sig_3);
        $sheet->getStyle('C' . $row)->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue('C' .$row, $sig->designation_3);


        $datenow = date_format(date_create(FinanceModel::getServerDateTime()), 'mdyhis');

        $dcprloc = 'dcpr/DCPR' . $datenow.'.xlsx';

        // $writer = new Xlsx($spreadsheet);
        // $writer->save($dcprloc);

        // return $dcprloc;

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$dcprloc.'"');
        $writer->save("php://output");
        exit();
    }

    

    // Page footer
    // public function Footer() {
    //     // Position at 15 mm from bottom
    //     $this->SetY(-15);
    //     // Set font
    //     $this->SetFont('helvetica', 'I', 8);
    //     // Page number
    //     $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    //     $this->Cell(0, 10, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    // }
}

// class EXPORTCRS extends TCPDF {

//     //Page header
//     public function Header() {
//         $schoollogo = DB::table('schoolinfo')->first();
//         $image_file = public_path().'/'.$schoollogo->picurl;
//         $extension = explode('.', $schoollogo->picurl);
//         $this->Image('@'.file_get_contents($image_file),60,9,17,17);
        
//         $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='C', $autopadding=true);
//         $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='C', $autopadding=true);

//         $this->writeHTMLCell(false, 50, 40, 20, 'Cash Receipt Summary', false, false, false, $reseth=true, $align='C', $autopadding=true);
//         // $this->writeHTMLCell(false, 50, 40, 20, 'For the month of <span style="text-transform:uppercase">' . date_format(CashierModel::getServerDateTime(), 'F') . '</span>', false, false, false, $reseth=true, $align='C', $autopadding=true);
//         // Ln();
//     }

//     // Page footer
//     // public function Footer() {
//     //     // Position at 15 mm from bottom
//     //     $this->SetY(-15);
//     //     // Set font
//     //     $this->SetFont('helvetica', 'I', 8);
//     //     // Page number
//     //     $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
//     //     $this->Cell(0, 10, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
//     // }
// }







// postTrans - Foreach
// $transamount = $trans->amount;
        
//         $ledgeritemized = db::table('studledgeritemized')
//           ->where('studid', $trans->studid)
//           ->where('syid', $trans->syid)
//           ->where('semid', $trans->semid)
//           ->where('classificationid', $trans->classid)
//           ->get();

//         foreach($ledgeritemized as $item)
//         {
//           if($transamount > 0)
//           {
//             $checkitem = db::table('studledgeritemized')
//               ->where('id', $item->id)
//               ->first();


//             if($checkitem->totalamount < $item->itemamount)
//             {
//               $_getamount = $item->itemamount - $item->totalamount;

//               if($transamount >= $_getamount)
//               {
//                 db::table('studledgeritemized')
//                   ->where('id', $item->id)
//                   ->update([
//                     'totalamount' => $item->totalamount + $_getamount,
//                     'updatedby' => auth()->user()->id,
//                     'updateddatetime' => CashierModel::getServerDateTime()
//                   ]);

//                 db::table('chrngtransitems')
//                   ->insert([
//                     'chrngtransid' => $trans->chrngtransid,
//                     'chrngtransdetailid' => $trans->chrngtransdetailid,
//                     'ornum' => $trans->ornum,
//                     'itemid' => $item->itemid,
//                     'classid' => $item->classificationid,
//                     'amount' => $_getamount,
//                     'studid' => $item->studid,
//                     'syid' => $trans->syid,
//                     'semid' => $trans->semid,
//                     'createdby' => auth()->user()->id,
//                     'createddatetime' => CashierModel::getServerDateTime()
//                   ]);

//                 $transamount -= $_getamount;

//               }
//               else
//               {
//                 db::table('studledgeritemized')
//                   ->where('id', $item->id)
//                   ->update([
//                     'totalamount' => $item->totalamount + $transamount,
//                     'updatedby' => auth()->user()->id,
//                     'updateddatetime' => CashierModel::getServerDateTime()
//                   ]);


//                 db::table('chrngtransitems')
//                   ->insert([
//                     'chrngtransid' => $trans->chrngtransid,
//                     'chrngtransdetailid' => $trans->chrngtransdetailid,
//                     'ornum' => $trans->ornum,
//                     'itemid' => $item->itemid,
//                     'classid' => $item->classificationid,
//                     'amount' => $transamount,
//                     'studid' => $item->studid,
//                     'syid' => $trans->syid,
//                     'semid' => $trans->semid,
//                     'createdby' => auth()->user()->id,
//                     'createddatetime' => CashierModel::getServerDateTime()
//                   ]);

//                 $transamount = 0;
//               }
//             }
//           }
//         }

