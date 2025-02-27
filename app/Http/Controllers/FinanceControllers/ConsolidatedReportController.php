<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use TCPDF;
use App\FinanceModel;
use App\Models\Finance\FinanceUtilityModel;
class ConsolidatedReportController extends Controller
{
    public function index(Request $request)
    {
        $terminals = Db::table('chrngterminals')->get();
        return view('finance.reports.consolidatedreport.index')->with('terminals',$terminals);
    }

    public function generate(Request $request)
    {
        $selecteddaterange = $request->get('selecteddaterange');
        $selectedterminal  = $request->get('selectedterminal');

        $dates             = explode(' - ', $selecteddaterange);
        $datefrom          = date('Y-m-d', strtotime($dates[0]));
        $dateto            = date('Y-m-d', strtotime($dates[1]));



        $list = '';



        $col = 0;
        $shs = 0;
        $hs = 0;
        $gs = 0;

        $gentotalcol = 0;
        $gentotalshs = 0;
        $gentotalhs = 0;
        $gentotalgs = 0;
        $gentotal = 0;

        $itemtotal = 0;
        $totalperincome = 0;

        $classcodes = db::table('items_classcode')
            ->get();

        foreach($classcodes as $class)
        {
            $list .='
                <tr>
                    <td colspan="7" class="text-bold">'.$class->description.'</td>
                </tr>
            ';

            $trxarray = array();
            $itemid = 0;
            $col = 0;
            $shs = 0;
            $hs = 0;
            $gs = 0;

            $totalcol = 0;
            $totalshs = 0;
            $totalhs = 0;
            $totalgs = 0;

            $trxitemcode = '';
            $trxdescription = '';

            $itemtotal = 0;

            //transitems
            $cashtransaction = db::table('chrngtrans')
                ->select(db::raw('chrngtrans.ornum, chrngtransitems.itemid, itemcode, items.description, SUM(chrngtransitems.amount) AS amount, acadprogid, classcode, progid'))
                ->join('chrngtransitems', 'chrngtrans.id', '=', 'chrngtransitems.chrngtransid')
                ->join('items', 'chrngtransitems.itemid', '=', 'items.id')
                ->Join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('chrngtransitems.deleted', 0)
                ->where('gradelevel.deleted', 0)
                ->where('classcode', $class->id)
                ->groupBy('acadprogid', 'chrngtransitems.itemid')
                ->having('amount', '>', 0)
                ->orderBy('classcode', 'ASC')
                ->orderBy('chrngtransitems.itemid', 'ASC')
                ->get();

            foreach($cashtransaction as $trxitems)
            {
                array_push($trxarray, $trxitems);
            }

            $balclassid = db::table('balforwardsetup')
                ->first()->classid;

            // cashtrans
            $transitems  = db::table('chrngtrans')
                ->select(db::raw('chrngtrans.ornum, chrngcashtrans.payscheddetailid as itemid, itemcode, description, SUM(chrngcashtrans.amount) AS amount, classcode, progid as acadprogid, classcode, progid'))
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                ->join('items', 'chrngcashtrans.payscheddetailid', '=', 'items.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('chrngcashtrans.deleted', 0)
                ->where('kind', 'item')
                ->where('classcode', $class->id)
                ->where('chrngcashtrans.classid', '!=', $balclassid)
                ->groupBy('chrngcashtrans.payscheddetailid', 'chrngtrans.id')
                ->having('amount', '>', 0)
                ->orderBy('classcode', 'ASC')
                ->orderBy('chrngcashtrans.payscheddetailid', 'ASC')
                ->get();

            // echo $transitems;

            foreach($transitems as $trx)
            {
                // print_r($trx) . '<br>';
                array_push($trxarray, $trx);
            }

            //oldaccounts
            $transoldacc = db::table('chrngtrans')
                ->select(db::raw('ornum, itemid, itemcode, description, SUM(chrngtransdetail.`amount`) AS amount, classcode, progid, progid AS acadprogid'))
                ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                ->join('balforwardsetup', 'chrngtransdetail.classid', '=', 'balforwardsetup.classid')
                ->join('items', 'balforwardsetup.itemid', '=', 'items.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('classcode', $class->id)
                ->groupBy('itemid', 'chrngtrans.id')
                ->having('amount', '>', 0)
                ->orderBy('classcode')
                ->orderBy('itemid')
                ->get();

            foreach($transoldacc as $trxold)
            {
                array_push($trxarray, $trxold);
            }

            $transnoitem = db::table('chrngtrans')
                ->select(db::raw('chrngtrans.id, chrngtrans.ornum, sum(chrngtransitems.amount) AS amount, classid, progid'))
                ->join('chrngtransitems', 'chrngtrans.id', '=', 'chrngtransitems.chrngtransid')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('chrngtransitems.deleted', 0)
                ->where('itemid', null)
                ->having('amount', '>', 0)
                ->groupBy('ornum', 'classid')
                ->orderBy('ornum')
                ->get();

            $booksetup = db::table('bookentrysetup')
                ->first();

            // return $transnoitem;

            foreach($transnoitem as $noitem)
            {
                $_itemlist = db::table('items')
                    ->where('id', $booksetup->itemid)
                    ->first();

                if($noitem->classid == $booksetup->classid && $class->id == $_itemlist->classcode)
                {

                    $_progid = $noitem->progid;
                    $_amount = $noitem->amount;
                    $_classcode = $_itemlist->classcode;
                    $_desc = $_itemlist->description;
                    $_itemcode = $_itemlist->itemcode;
                    $_ornum = $noitem->ornum;
                    $_itemid = $booksetup->itemid;

                    array_push($trxarray, (object)[
                        'ornum' => $_ornum,
                        'itemid' => $_itemid,
                        'itemcode' => $_itemcode,
                        'description' => $_desc,
                        'amount' => $_amount,
                        'acadprogid' => $_progid,
                        'classcode' => $_classcode,
                        'progid' => $_progid
                    ]);

                    // array_push($trxarray, $_noitemarray);
                }
                // else
                // {

                // }

                $item = db::table('items')
                    ->where('classid', $noitem->classid)
                    ->where('classid', '!=', $booksetup->classid)
                    ->where('classcode', $class->id)
                    ->where('deleted', 0)
                    ->first();

                if($item)
                {
                    $_ccode = db::table('items_classcode')
                        ->where('id', $item->classcode)
                        ->first();

                    $_classcode = '';

                    if($_ccode)
                    {
                        $_classcode = $_ccode->description;
                    }

                    // echo $noitem->ornum . ' - ' . $noitem->classid . ' - ' . $noitem->id .  '<br>';

                    array_push($trxarray, (object)[
                        'id' => $noitem->id,
                        'ornum' => $noitem->ornum,
                        'itemid' => $item->id,
                        'itemcode' => $item->itemcode,
                        'description' => $item->description,
                        'amount' => $noitem->amount,
                        'acadprogid' => $noitem->progid,
                        'classcode' => $_classcode,
                        'progid' => $noitem->progid
                    ]);
                }
            }

            // return $trxarray;
            // print_r($trxarray);
            $trxarray = collect($trxarray);

            $trxarray = $trxarray->sortBy('itemcode');

            // echo $trxarray;

            foreach($trxarray as $trx)
            {
                $totalperincome = 0;
                if($itemid == $trx->itemid || $itemid == 0)
                {
                    if($trx->acadprogid == 2 || $trx->acadprogid == 3 || $trx->progid == 2 || $trx->progid == 3)
                    {
                        $gs += $trx->amount;
                        $totalgs += $trx->amount;
                        $gentotalgs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 4 || $trx->progid == 4)
                    {
                        // echo $trx->ornum . '<br>' ;
                        $hs += $trx->amount;
                        $totalhs += $trx->amount;
                        $gentotalhs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 5 || $trx->progid == 5)
                    {
                        $shs += $trx->amount;
                        $totalshs += $trx->amount;
                        $gentotalshs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 6 || $trx->progid == 6)
                    {
                        $col += $trx->amount;
                        $totalcol += $trx->amount;
                        $gentotalcol += $trx->amount;
                        // if($class->id == 2)
                        // {
                        //     echo 'ornum: ' .  $trx->itemid . '<br>';
                        // }
                    }


                    $itemtotal += $trx->amount;

                    $trxitemcode = $trx->itemcode;
                    $trxdescription = $trx->description;
                    $totalperincome = $totalcol + $totalshs + $totalhs + $totalgs;
                }
                else
                {
                    $list .='
                        <tr>
                            <td class="td-code">'.$trxitemcode.'</td>
                            <td class="td-desc">'.$trxdescription.'</td>
                            <td class="text-right">'.number_format($col, 2).'</td>
                            <td class="text-right">'.number_format($shs, 2).'</td>
                            <td class="text-right">'.number_format($hs, 2).'</td>
                            <td class="text-right">'.number_format($gs, 2).'</td>
                            <td class="text-right">'.number_format($itemtotal, 2).'</td>
                        </tr>
                    ';

                    $col = 0;
                    $shs = 0;
                    $hs = 0;
                    $gs = 0;

                    $itemtotal = 0;
                    $trxitemcode = '';
                    $trxdescription = '';

                    if($trx->acadprogid == 2 || $trx->acadprogid == 3 || $trx->progid == 2 || $trx->progid == 3)
                    {
                        $gs += $trx->amount;
                        $totalgs += $trx->amount;
                        $gentotalgs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 4 || $trx->progid == 4)
                    {
                        $hs += $trx->amount;
                        $totalhs += $trx->amount;
                        $gentotalhs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 5 || $trx->progid == 5)
                    {
                        $shs += $trx->amount;
                        $totalshs += $trx->amount;
                        $gentotalshs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 6 || $trx->progid == 6)
                    {
                        $col += $trx->amount;
                        $totalcol += $trx->amount;
                        $gentotalcol += $trx->amount;
                    }

                    $itemtotal += $trx->amount;

                    $trxitemcode = $trx->itemcode;
                    $trxdescription = $trx->description;
                    $totalperincome = $totalcol + $totalshs + $totalhs + $totalgs;

                }

                $itemid = $trx->itemid;

            }

            $list .='
                <tr>
                    <td>'.$trxitemcode.'</td>
                    <td>'.$trxdescription.'</td>
                    <td class="text-right">'.number_format($col, 2).'</td>
                    <td class="text-right">'.number_format($shs, 2).'</td>
                    <td class="text-right">'.number_format($hs, 2).'</td>
                    <td class="text-right">'.number_format($gs, 2).'</td>
                    <td class="text-right">'.number_format($itemtotal, 2).'</td>
                </tr>
            ';



            $list .='
                <tr>
                    <td colspan="2" class="text-bold text-right gentotal" style="margin-top:130px">TOTAL ' . strtoupper($class->description) . ':</td>
                    <td class="text-bold text-right">' . number_format($totalcol, 2) . '</td>
                    <td class="text-bold text-right">' . number_format($totalshs, 2) . '</td>
                    <td class="text-bold text-right">' . number_format($totalhs, 2) . '</td>
                    <td class="text-bold text-right">' . number_format($totalgs, 2) . '</td>
                    <td class="text-bold text-right">' . number_format($totalperincome, 2) . '</td>
                </tr>
            ';

            $totalperincome = 0;
        }


        $gentotal = $gentotalcol + $gentotalshs + $gentotalhs + $gentotalgs;



        // foreach($cashtransaction as $trx)
        // {
        //     if($itemid == $trx->itemid || $itemid == 0)
        //     {
        //         if($trx->acadprogid == 2 || $trx->acadprogid == 3)
        //         {
        //             $gs += $trx->amount;
        //         }
        //         elseif($trx->acadprogid == 4)
        //         {
        //             $hs += $trx->amount;
        //         }
        //         elseif($trx->acadprogid == 5)
        //         {
        //             $shs += $trx->amount;
        //         }
        //         elseif($trx->acadprogid == 6)
        //         {
        //             $col += $trx->amount;
        //         }

        //         $itemtotal += $trx->amount;

        //         $trxitemcode = $trx->itemcode;
        //         $trxdescription = $trx->description;
        //     }
        //     else
        //     {
        //         $list .='
        //             <tr>
        //                 <td>'.$trxitemcode.'</td>
        //                 <td>'.$trxdescription.'</td>
        //                 <td>'.number_format($col, 2).'</td>
        //                 <td>'.number_format($shs, 2).'</td>
        //                 <td>'.number_format($hs, 2).'</td>
        //                 <td>'.number_format($gs, 2).'</td>
        //                 <td>'.number_format($itemtotal, 2).'</td>
        //             </tr>
        //         ';

        //         $col = 0;
        //         $shs = 0;
        //         $hs = 0;
        //         $gs = 0;
        //         $itemtotal = 0;
        //         $trxitemcode = '';
        //         $trxdescription = '';

        //         if($trx->acadprogid == 2 || $trx->acadprogid == 3)
        //         {
        //             $gs += $trx->amount;
        //         }
        //         elseif($trx->acadprogid == 4)
        //         {
        //             $hs += $trx->amount;
        //         }
        //         elseif($trx->acadprogid == 5)
        //         {
        //             $shs += $trx->amount;
        //         }
        //         elseif($trx->acadprogid == 6)
        //         {
        //             $col += $trx->amount;
        //         }

        //         $itemtotal += $trx->amount;

        //         $trxitemcode = $trx->itemcode;
        //         $trxdescription = $trx->description;

        //     }

        //     $itemid = $trx->itemid;
        // }



        // return $list;

        if(!$request->has('exporttype'))
        {
            return view('finance.reports.consolidatedreport.filtertable')
                ->with('cashtransaction', $list)
                ->with('gentotalcol', $gentotalcol)
                ->with('gentotalshs', $gentotalshs)
                ->with('gentotalhs', $gentotalhs)
                ->with('gentotalgs', $gentotalgs)
                ->with('gentotal', $gentotal);
        }else{

            $pdf = new ConsolidatedReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            $pdf->SetCreator('CK');
            $pdf->SetAuthor('CK Children\'s Publishing');
            $pdf->SetTitle(DB::table('schoolinfo')->first()->schoolname.' - Consolidated Report');
            $pdf->SetSubject('Account Receivables');

            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            $pdf->SetMargins(7, 8,7);
            // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            $pdf->setPrintHeader(false);

            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $pdf->setLanguageArray($l);
            }

            $pdf->AddPage();


            $view = \View::make('finance/reports/pdf/pdf_consolidatedreport',compact('datefrom','dateto'))
                ->with('cashtransaction', $list)
                ->with('gentotalcol', $gentotalcol)
                ->with('gentotalshs', $gentotalshs)
                ->with('gentotalhs', $gentotalhs)
                ->with('gentotalgs', $gentotalgs)
                ->with('gentotal', $gentotal);

            $html = $view->render();
            $pdf->writeHTML($html, true, false, true, false, '');
            // ---------------------------------------------------------
            //Close and output PDF document
            $pdf->Output('Cash Receipt Summary.pdf', 'I');
            // return view('finance.reports.pdf.pdf_consolidatedreport');
        }
    }

    public function consolidated_chktrans(Request $request)
    {
        $daterange = explode(' - ', $request->get('daterange'));
        $datefrom = date_format(date_create($daterange[0]), 'Y-m-d 00:00');
        $dateto = date_format(date_create($daterange[1]), 'Y-m-d 23:59');

        $trxarray = array();
        // transitems

        $balclassid = db::table('balforwardsetup')
            ->first()->classid;

        $transitems = db::table('chrngtrans')
            ->select(db::raw('chrngtrans.id, chrngtrans.ornum, chrngtransitems.itemid, itemcode, items.description, chrngtransitems.amount AS amount, acadprogid, items_classcode.description as classcode, progid'))
            ->join('chrngtransitems', 'chrngtrans.id', '=', 'chrngtransitems.chrngtransid')
            ->join('items', 'chrngtransitems.itemid', '=', 'items.id')
            ->join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->leftJoin('items_classcode', 'items.classcode', '=', 'items_classcode.id')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('cancelled', 0)
            ->where('chrngtransitems.deleted', 0)
            ->where('gradelevel.deleted', 0)
            ->where('chrngtransitems.classid', '!=', 9)
            ->having('amount', '>', 0)
            ->orderBy('ornum')
            ->get();

        foreach($transitems as $trxitems)
        {
            array_push($trxarray, $trxitems);
        }

        // detail

        $transdetail = db::table('chrngtrans')
            ->select(db::raw('chrngtrans.id, chrngtrans.ornum, chrngcashtrans.payscheddetailid AS itemid, itemcode, items.description, chrngcashtrans.amount AS amount, items_classcode.description as classcode, progid AS acadprogid, progid'))
            ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            ->join('items', 'chrngcashtrans.payscheddetailid', '=', 'items.id')
            ->leftJoin('items_classcode', 'items.classcode', '=', 'items_classcode.id')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('cancelled', 0)
            ->where('chrngcashtrans.deleted', 0)
            ->where('kind', 'item')
            ->where('chrngcashtrans.classid', '!=', $balclassid)
            ->having('amount', '>', 0)
            ->get();

        // return $transdetail;

        foreach($transdetail as $detail)
        {
            array_push($trxarray, $detail);
        }

        $balforward = db::table('chrngtrans')
            ->select(db::raw('chrngtrans.id, ornum, itemid, itemcode, items.description, chrngtransdetail.`amount` AS amount, items_classcode.description as classcode, progid, progid AS acadprogid'))
            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
            ->join('balforwardsetup', 'chrngtransdetail.classid', '=', 'balforwardsetup.classid')
            ->join('items', 'balforwardsetup.itemid', '=', 'items.id')
            ->leftJoin('items_classcode', 'items.classcode', '=', 'items_classcode.id')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('cancelled', 0)
            ->having('amount', '>', 0)
            ->get();

        foreach($balforward as $bal)
        {
            array_push($trxarray, $bal);
        }

        $noitems = db::table('chrngtrans')
            ->select(db::raw('chrngtrans.id, chrngtrans.ornum, chrngtransitems.amount AS amount, classid, progid'))
            ->join('chrngtransitems', 'chrngtrans.id', 'chrngtransitems.chrngtransid')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('cancelled', 0)
            ->where('chrngtransitems.deleted', 0)
            ->where('itemid', null)
            ->having('amount', '>', 0)
            ->get();

        foreach($noitems as $noitem)
        {
            $booksetup = db::table('bookentrysetup')
                ->first();

            $_itemlist = db::table('items')
                ->where('id', $booksetup->itemid)
                ->first();

            $_ccode = db::table('items_classcode')
                ->where('id', $_itemlist->classcode)
                ->first();

            if($noitem->classid == $booksetup->classid && $_ccode)
            {

                $_progid = $noitem->progid;
                $_amount = $noitem->amount;
                $_desc = $_itemlist->description;
                $_itemcode = $_itemlist->itemcode;
                $_ornum = $noitem->ornum;
                $_itemid = $booksetup->itemid;

                if($_ccode)
                {
                    $_classcode = $_ccode->description;
                }

                array_push($trxarray, (object)[
                    'id' => $noitem->id,
                    'ornum' => $_ornum,
                    'itemid' => $_itemid,
                    'itemcode' => $_itemcode,
                    'description' => $_desc,
                    'amount' => $_amount,
                    'acadprogid' => $_progid,
                    'classcode' => $_classcode,
                    'progid' => $_progid
                ]);

                // array_push($trxarray, $_noitemarray);
            }

            $item = db::table('items')
                ->where('classid', $noitem->classid)
                ->where('classid', '!=', $booksetup->classid)
                // ->where('classcode', $class->id)
                ->where('deleted', 0)
                ->first();

            if($item)
            {
                $_ccode = db::table('items_classcode')
                    ->where('id', $item->classcode)
                    ->first();

                $_classcode = '';

                if($_ccode)
                {
                    $_classcode = $_ccode->description;
                }

                // echo $noitem->ornum . ' - ' . $noitem->classid . ' - ' . $noitem->id .  '<br>';

                array_push($trxarray, (object)[
                    'id' => $noitem->id,
                    'ornum' => $noitem->ornum,
                    'itemid' => $item->id,
                    'itemcode' => $item->itemcode,
                    'description' => $item->description,
                    'amount' => $noitem->amount,
                    'acadprogid' => $noitem->progid,
                    'classcode' => $_classcode,
                    'progid' => $noitem->progid
                ]);
            }
        }

        $trxarray = collect($trxarray);
        $trxarray = $trxarray->sortBy('ornum');
        $trxlist = '';

        $totalamount = 0;
        $curor = '';

        foreach($trxarray as $trx)
        {
            $acadcode = '';

            if($trx->acadprogid != 0)
            {
                $_acad = db::table('academicprogram')
                    ->where('id', $trx->acadprogid)
                    ->first();

                if($_acad)
                {
                    $acadcode = $_acad->acadprogcode;
                }
            }
            else
            {
                $acadcode = '';
            }

            if($curor == '' || $curor != $trx->ornum)
            {
                $trxlist .='
                    <tr data-ornum="'.$trx->ornum.'" data-id="'.$trx->id.'" class="trxheader">
                        <td class="text-bold">'.$trx->ornum.'</td>
                        <td class="text-bold">'.$acadcode.'</td>
                        <td></td>
                        <td></td>

                    </tr>
                    <tr data-itemid="'.$trx->itemid.'" class="trxdetail">
                        <td></td>
                        <td>'.$trx->description.'</td>
                        <td class="text-right">'.number_format($trx->amount, 2).'</td>
                        <td>'.$trx->classcode.'</td>

                    </tr>
                ';

                $curor = $trx->ornum;
            }
            else
            {
                $trxlist .='
                    <tr data-itemid="'.$trx->itemid.'" class="trxdetail">
                        <td></td>
                        <td>'.$trx->description.'</td>
                        <td class="text-right">'.number_format($trx->amount, 2).'</td>
                        <td>'.$trx->classcode.'</td>
                    </tr>
                ';

                $curor = $trx->ornum;
            }

            $totalamount += $trx->amount;
        }

        $trxlist .='
            <tr>
                <td class="text-right" colspan="2">TOTAL: </td>
                <td class="text-bold text-right">'.number_format($totalamount, 2).'</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        ';

        $data = array(
            'list' => $trxlist
        );

        echo json_encode($data);


    }

    public function consolidated_trans(Request $request)
    {
        $transid = $request->get('transid');

        $chrngtrans = db::table('chrngtrans')
            ->where('id', $transid)
            ->first();

        return $chrngtrans->progid;
    }

    public function consolidated_trans_update(Request $request)
    {
        $transid = $request->get('transid');
        $progid = $request->get('progid');

        db::table('chrngtrans')
            ->where('id', $transid)
            ->update([
                'progid' => $progid
            ]);

        return 'done';
    }

    public function generate_v2(Request $request)
    {
        $selecteddaterange = $request->get('selecteddaterange');
        $selectedterminal  = $request->get('selectedterminal');

        $dates             = explode(' - ', $selecteddaterange);
        $datefrom          = date('Y-m-d', strtotime($dates[0]));
        $dateto            = date('Y-m-d', strtotime($dates[1]));

        $list = '';

        $col = 0;
        $shs = 0;
        $hs = 0;
        $gs = 0;
        $gen = 0;

        $gentotalcol = 0;
        $gentotalshs = 0;
        $gentotalhs = 0;
        $gentotalgs = 0;
        $gentotalgen = 0;
        $gentotal = 0;

        $itemtotal = 0;
        $totalperincome = 0;

        // $_chrngtrans = db::table('chrngtrans')
        //     ->select('chrngtrans.studid', 'syid', 'semid')
        //     ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
        //     ->where('cancelled', 0)
        //     ->where('studid', '>', 0)
        //     ->groupBy('studid')
        //     ->get();

        // foreach($_chrngtrans as $_trans)
        // {
        //     FinanceModel::ledgeritemizedreset($_trans->studid, $_trans->syid, $_trans->semid);
        //     FinanceModel::transitemsreset($_trans->studid, $_trans->syid, $_trans->semid);
        // }



        $t = db::table('chrngtrans')
            ->select('ornum')
            ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
            ->where('cancelled', 0)
            ->orderBy('ornum', 'ASC')
            ->first();

        if(!$t)
        {
            return 'no data';
        }

        $or1 = $t->ornum;

        $t = db::table('chrngtrans')
            ->select('ornum')
            ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
            ->where('cancelled', 0)
            ->orderBy('ornum', 'DESC')
            ->first();

        $or2 = $t->ornum;

        $rangeOR = $or1 . ' - '. $or2;

        $classcodes = db::table('items_classcode')
            ->get();

        foreach($classcodes as $class)
        {
            $list .='
                <tr>
                    <td colspan="7" class="text-bold">'.$class->description.'</td>
                </tr>
            ';

            $trxarray = array();
            $itemid = 0;
            $col = 0;
            $shs = 0;
            $hs = 0;
            $gs = 0;
            $gen = 0;

            $totalcol = 0;
            $totalshs = 0;
            $totalhs = 0;
            $totalgs = 0;
            $totalgen = 0;

            $trxitemcode = '';
            $trxdescription = '';

            $itemtotal = 0;

            //tuition
            $transtui = db::table('chrngtrans')
                ->select(db::raw('chrngtrans.ornum, "" as itemcode, itemclassification.id as itemid, itemclassification.description, SUM(chrngcashtrans.amount) AS amount, acadprogid, classcode, progid '))
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                ->join('itemclassification', 'chrngcashtrans.classid', '=', 'itemclassification.id')
                ->Join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('kind', 'tui')
                ->where('chrngcashtrans.deleted', 0)
                ->where('gradelevel.deleted', 0)
                ->where('classcode', $class->id)
                ->where('itemclassification.deleted', 0)
                ->groupBy('acadprogid', 'itemclassification.id')
                ->having('amount', '>', 0)
                ->orderBy('classcode', 'ASC')
                // ->orderBy('items.id', 'ASC')
                ->get();

            foreach($transtui as $trx)
            {
                array_push($trxarray, $trx);
            }



            //tuition

            // misc

            $transmisc = db::table('chrngtrans')
                ->select(db::raw('chrngtrans.ornum, items.id AS itemid, itemcode, items.description, SUM(chrngcashtrans.amount) AS amount, acadprogid, classcode, progid'))
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                ->join('items', 'chrngcashtrans.itemid', '=', 'items.id')
                ->Join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('kind', 'misc')
                ->where('chrngcashtrans.deleted', 0)
                ->where('gradelevel.deleted', 0)
                ->where('classcode', $class->id)
                ->groupBy('acadprogid', 'items.id')
                ->having('amount', '>', 0)
                ->orderBy('classcode', 'ASC')
                ->orderBy('items.id', 'ASC')
                ->get();

            foreach($transmisc as $trx)
            {
                // print_r($trx) . '<br>';
                array_push($trxarray, $trx);
            }

            $transmisc = db::table('chrngtrans')
                ->select(db::raw('chrngtrans.ornum, "" as itemcode, itemclassification.id AS itemid, itemclassification.description, SUM(chrngcashtrans.amount) AS amount, acadprogid, classcode, progid'))
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                // ->join('items', 'chrngcashtrans.classid', '=', 'items.classid')
                ->join('itemclassification', 'chrngcashtrans.classid', '=', 'itemclassification.id')
                ->Join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('kind', 'misc')
				->where('itemid', null)
                ->where('chrngcashtrans.deleted', 0)
                ->where('gradelevel.deleted', 0)
                ->where('classcode', $class->id)
                ->groupBy('acadprogid', 'itemclassification.id')
                ->having('amount', '>', 0)
                ->orderBy('classcode', 'ASC')
                // ->orderBy('items.id', 'ASC')
                ->get();

            foreach($transmisc as $trx)
            {
                // print_r($trx) . '<br>';
                array_push($trxarray, $trx);
            }

            // dd($trxarray);
            // misc


            // item

            $transitem = db::table('chrngtrans')
                ->select(db::raw('chrngtrans.ornum, items.id AS itemid, itemcode, items.description, SUM(chrngcashtrans.amount) AS amount, classcode, progid as acadprogid'))
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                ->join('items', 'chrngcashtrans.payscheddetailid', '=', 'items.id')
                ->leftJoin('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
                ->leftjoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->leftjoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('kind', 'item')
                ->where('chrngcashtrans.deleted', 0)
                ->where('classcode', $class->id)
                ->groupBy('acadprogid', 'items.id', 'progid')
                ->having('amount', '>', 0)
                ->orderBy('classcode', 'ASC')
                ->orderBy('items.id', 'ASC')
                ->get();

            foreach($transitem as $trx)
            {
                // print_r($trx) . '<br>';
                array_push($trxarray, $trx);
            }

            // item



            $chrngsetup  = db::table('chrngsetup')
                ->where('deleted', 0)
                ->where('groupname', 'OTH')
                // ->where('classid', '!=', 3)
                ->get();

            $_setup = array();

            foreach($chrngsetup as $setup)
            {
                array_push($_setup, $setup->classid);
            }

            $bookentryclassid = db::table('bookentrysetup')
                ->first()->classid;


            $transoth2 = db::table('chrngtrans')
                ->select(db::raw('chrngtrans.ornum, "" as itemcode, itemclassification.id AS itemid, itemclassification.description, SUM(chrngcashtrans.amount) AS amount, acadprogid, classcode, progid'))
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                // ->join('items', 'chrngcashtrans.classid', '=', 'items.classid')
                ->join('itemclassification', 'chrngcashtrans.classid', '=', 'itemclassification.id')
                ->Join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('kind', 'oth')
                // ->whereIn('kind', ['oth', 'dp'])
                // ->whereNotIn('chrngcashtrans.classid', [3])
                // ->whereIn('chrngcashtrans.classid', $_setup)
                ->where('chrngcashtrans.deleted', 0)
                ->where('chrngcashtrans.classid', '!=', $bookentryclassid)
                ->where('gradelevel.deleted', 0)
                ->where('classcode', $class->id)
                ->where('chrngcashtrans.itemid', null)
                ->groupBy('acadprogid', 'itemclassification.id')
                ->having('amount', '>', 0)
                ->orderBy('classcode', 'ASC')
                // ->orderBy('itemclassification.id', 'ASC')
                ->get();

            foreach($transoth2 as $trx)
            {
                // print_r($trx) . '<br>';
                array_push($trxarray, $trx);
            }

            $transoth1 = db::table('chrngtrans')
                ->select(db::raw('chrngtrans.ornum, items.id AS itemid, itemcode, items.description, SUM(chrngcashtrans.amount) AS amount, acadprogid, classcode, progid'))
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                ->join('items', 'chrngcashtrans.itemid', '=', 'items.id')
                ->Join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('kind', 'oth')
                // ->whereIn('kind', ['oth', 'dp'])
                // ->where('chrngcashtrans.classid', )
                // ->whereIn('chrngcashtrans.classid', $_setup)
                ->where('chrngcashtrans.deleted', 0)
                // ->where('items.deleted', 0)
                ->where('gradelevel.deleted', 0)
                ->where('classcode', $class->id)
                ->where('chrngcashtrans.itemid', '!=', null)
                ->groupBy('acadprogid', 'items.id')
                ->having('amount', '>', 0)
                ->orderBy('classcode', 'ASC')
                ->orderBy('items.id', 'ASC')
                ->get();

            foreach($transoth1 as $trx)
            {
                // print_r($trx) . '<br>';
                array_push($trxarray, $trx);
            }


            //books

            $transbooks = db::table('chrngtrans')
                ->select(db::raw('chrngtrans.ornum, "" as itemcode, itemclassification.id AS itemid, itemclassification.description, SUM(chrngcashtrans.amount) AS amount, acadprogid, classcode, progid'))
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                ->join('itemclassification', 'chrngcashtrans.classid', '=', 'itemclassification.id')
                ->Join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('kind', 'oth')
                ->where('chrngcashtrans.classid', '=', $bookentryclassid)
                // ->whereNotIn('chrngcashtrans.classid', [3])
                // ->whereIn('chrngcashtrans.classid', $_setup)
                ->where('chrngcashtrans.deleted', 0)
                // ->where('items.deleted', 0)
                ->where('gradelevel.deleted', 0)
                ->where('classcode', $class->id)
                ->where('itemid', null)
                ->groupBy('acadprogid', 'itemclassification.id')
                ->having('amount', '>', 0)
                ->orderBy('classcode', 'ASC')
                // ->orderBy('items.id', 'ASC')
                ->get();

            foreach($transbooks as $trx)
            {
                // print_r($trx) . '<br>';
                array_push($trxarray, $trx);
            }

            //books
            // oth2

            // oth3

            // $transoth3 = db::table('chrngtrans')
            //     ->select(db::raw('chrngtrans.ornum, items.id AS itemid, itemcode, items.description, SUM(chrngcashtrans.amount) AS amount, acadprogid, classcode, progid'))
            //     ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            //     ->join('items', 'chrngcashtrans.particulars', '=', 'items.description')
            //     ->Join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
            //     ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            //     ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            //     ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
            //     ->where('cancelled', 0)
            //     ->where('kind', 'oth')
            //     ->whereIn('chrngcashtrans.classid', [35, 43])
            //     ->where('chrngcashtrans.deleted', 0)
            //     ->where('items.deleted', 0)
            //     ->where('gradelevel.deleted', 0)
            //     ->where('classcode', $class->id)
            //     ->groupBy('acadprogid', 'items.id')
            //     ->having('amount', '>', 0)
            //     ->orderBy('classcode', 'ASC')
            //     ->orderBy('items.id', 'ASC')
            //     ->get();

            // foreach($transoth3 as $trx)
            // {
            //     // print_r($trx) . '<br>';
            //     array_push($trxarray, $trx);
            // }

            // $transoth4 = db::table('chrngtrans')
            //     ->select(db::raw('chrngtrans.ornum, items.id AS itemid, itemcode, items.description, SUM(chrngcashtrans.amount) AS amount, acadprogid, classcode, progid'))
            //     ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            //     ->join('items', 'chrngcashtrans.particulars', '=', 'items.description')
            //     ->Join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
            //     ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            //     ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            //     ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
            //     ->where('cancelled', 0)
            //     ->where('kind', 'oth')
            //     ->where('chrngcashtrans.classid', 43)
            //     ->where('chrngcashtrans.deleted', 0)
            //     ->where('items.deleted', 0)
            //     ->where('gradelevel.deleted', 0)
            //     ->where('classcode', $class->id)
            //     ->groupBy('acadprogid', 'items.id')
            //     ->having('amount', '>', 0)
            //     ->orderBy('classcode', 'ASC')
            //     ->orderBy('items.id', 'ASC')
            //     ->get();

            // foreach($transoth4 as $trx)
            // {
            //     // print_r($trx) . '<br>';
            //     array_push($trxarray, $trx);
            // }

            // oth3

            // old
            $oldsetup = db::table('balforwardsetup')->first();
            $oldclassid = $oldsetup->classid;
            $olditemid = $oldsetup->itemid;

            $transold = db::table('chrngtrans')
            ->select(db::raw('chrngtrans.ornum, "" as itemcode, itemclassification.id AS itemid, itemclassification.description, SUM(chrngcashtrans.amount) AS amount, acadprogid, classcode, progid'))
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                // ->join('items', 'chrngcashtrans.classid', '=', 'items.classid')
                // ->join('items', function($q) use($olditemid){
                //     $q->on('chrngcashtrans.classid', '=', 'items.classid')
                //         ->where('items.id', '=', $olditemid);
                // })
                ->join('itemclassification', 'chrngcashtrans.classid', '=', 'itemclassification.id')
                ->Join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('kind', 'old')
                ->where('chrngcashtrans.deleted', 0)
                // ->where('items.id', 87)
                ->where('gradelevel.deleted', 0)
                ->where('classcode', $class->id)
                ->where('itemclassification.deleted', 0)
                ->groupBy('acadprogid', 'itemclassification.id')
                ->having('amount', '>', 0)
                ->orderBy('classcode', 'ASC')
                // ->orderBy('items.id', 'ASC')
                ->get();

            foreach($transold as $trx)
            {
                // print_r($trx) . '<br>';
                array_push($trxarray, $trx);
            }



            // old

            // return $trxarray;
            // print_r($trxarray);
            $trxarray = collect($trxarray);

            $trxarray = $trxarray->sortBy('itemid');

            // dd($trxarray);


            foreach($trxarray as $trx)
            {
                $totalperincome = 0;
                if($itemid == $trx->itemid || $itemid == 0)
                {
                    // if($trx->acadprogid == 2 || $trx->progid == 3 || $trx->progid == 2 || $trx->progid == 3)
                    if($trx->acadprogid == 2 || $trx->acadprogid == 3)
                    {
                        $gs += $trx->amount;
                        $totalgs += $trx->amount;
                        $gentotalgs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 4)
                    {
                        // echo $trx->ornum . '<br>' ;
                        $hs += $trx->amount;
                        $totalhs += $trx->amount;
                        $gentotalhs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 5)
                    {
                        $shs += $trx->amount;
                        $totalshs += $trx->amount;
                        $gentotalshs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 6)
                    {
                        $col += $trx->amount;
                        $totalcol += $trx->amount;
                        $gentotalcol += $trx->amount;
                        // if($class->id == 2)
                        // {
                        //     echo 'ornum: ' .  $trx->itemid . '<br>';
                        // }
                    }
                    else
                    {
                        // $gen += $trx->amount;
                        // $totalgen  += $trx->amount;
                        // $gentotalgen += $trx->amount;

                        $col += $trx->amount;
                        $totalcol += $trx->amount;
                        $gentotalcol += $trx->amount;
                    }


                    $itemtotal += $trx->amount;

                    $trxitemcode = $trx->itemcode;
                    $trxdescription = $trx->description;
                    $totalperincome = $totalcol + $totalshs + $totalhs + $totalgs + $totalgen;
                }
                else
                {
                    $list .='
                        <tr>
                            <td class="td-code">'.$trxitemcode.'</td>
                            <td class="td-desc">'.$trxdescription.'</td>
                            <td class="text-right">'.number_format($col, 2).'</td>
                            <td class="text-right">'.number_format($shs, 2).'</td>
                            <td class="text-right">'.number_format($hs, 2).'</td>
                            <td class="text-right">'.number_format($gs, 2).'</td>
                            <td class="text-right">'.number_format($itemtotal, 2).'</td>
                        </tr>
                    ';

                    $col = 0;
                    $shs = 0;
                    $hs = 0;
                    $gs = 0;
                    $gen = 0;

                    $itemtotal = 0;
                    $trxitemcode = '';
                    $trxdescription = '';

                    if($trx->acadprogid == 2 || $trx->acadprogid == 3)
                    {
                        $gs += $trx->amount;
                        $totalgs += $trx->amount;
                        $gentotalgs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 4)
                    {
                        $hs += $trx->amount;
                        $totalhs += $trx->amount;
                        $gentotalhs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 5)
                    {
                        $shs += $trx->amount;
                        $totalshs += $trx->amount;
                        $gentotalshs += $trx->amount;
                    }
                    elseif($trx->acadprogid == 6)
                    {
                        $col += $trx->amount;
                        $totalcol += $trx->amount;
                        $gentotalcol += $trx->amount;
                    }
                    else
                    {
                        $gen += $trx->amount;
                        $totalgen  += $trx->amount;
                        $gentotalgen += $trx->amount;
                    }

                    $itemtotal += $trx->amount;

                    $trxitemcode = $trx->itemcode;
                    $trxdescription = $trx->description;
                    $totalperincome = $totalcol + $totalshs + $totalhs + $totalgs + $totalgen;

                }

                $itemid = $trx->itemid;

            }

            $list .='
                <tr>
                    <td>'.$trxitemcode.'</td>
                    <td>'.$trxdescription.'</td>
                    <td class="text-right">'.number_format($col, 2).'</td>
                    <td class="text-right">'.number_format($shs, 2).'</td>
                    <td class="text-right">'.number_format($hs, 2).'</td>
                    <td class="text-right">'.number_format($gs, 2).'</td>

                    <td class="text-right">'.number_format($itemtotal, 2).'</td>
                </tr>
            ';



            $list .='
                <tr>
                    <td colspan="2" class="text-bold text-right gentotal" style="margin-top:130px">TOTAL ' . strtoupper($class->description) . ':</td>
                    <td class="text-bold text-right">' . number_format($totalcol, 2) . '</td>
                    <td class="text-bold text-right">' . number_format($totalshs, 2) . '</td>
                    <td class="text-bold text-right">' . number_format($totalhs, 2) . '</td>
                    <td class="text-bold text-right">' . number_format($totalgs, 2) . '</td>

                    <td class="text-bold text-right">' . number_format($totalperincome, 2) . '</td>
                </tr>
            ';

            $totalperincome = 0;
        }

        // dd($trxarray);
        $gentotal = $gentotalcol + $gentotalshs + $gentotalhs + $gentotalgs + $gentotalgen;

        if(!$request->has('exporttype'))
        {
            return view('finance.reports.consolidatedreport.filtertable')
                ->with('cashtransaction', $list)
                ->with('gentotalcol', $gentotalcol)
                ->with('gentotalshs', $gentotalshs)
                ->with('gentotalhs', $gentotalhs)
                ->with('gentotalgs', $gentotalgs)
                ->with('gentotalgen', $gentotalgen)
                ->with('gentotal', $gentotal)
                ->with('rangeOR', $rangeOR);
        }else{

            $pdf = new ConsolidatedReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            $pdf->SetCreator('CK');
            $pdf->SetAuthor('CK Children\'s Publishing');
            $pdf->SetTitle(DB::table('schoolinfo')->first()->schoolname.' - Consolidated Report');
            $pdf->SetSubject('Account Receivables');

            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            $pdf->SetMargins(7, 8,7);
            // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            $pdf->setPrintHeader(false);

            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $pdf->setLanguageArray($l);
            }

            $pdf->AddPage();


            $view = \View::make('finance/reports/pdf/pdf_consolidatedreport',compact('datefrom','dateto'))
                ->with('cashtransaction', $list)
                ->with('gentotalcol', $gentotalcol)
                ->with('gentotalshs', $gentotalshs)
                ->with('gentotalhs', $gentotalhs)
                ->with('gentotalgs', $gentotalgs)
                ->with('gentotalgen', $gentotalgen)
                ->with('gentotal', $gentotal)
                ->with('rangeOR', $rangeOR);

            $html = $view->render();
            $pdf->writeHTML($html, true, false, true, false, '');
            // ---------------------------------------------------------
            //Close and output PDF document
            $pdf->Output('Cash Receipt Summary.pdf', 'I');
            // return view('finance.reports.pdf.pdf_consolidatedreport');
            exit;
        }
    }

    public static function ucon_maketransitems($ornum, $tamount)
    {
        start:
        $transitems = db::table('chrngtransitems')
            ->where('ornum', $ornum)
            ->where('classid', 3)
            ->where('deleted', 0)
            ->get();

        if(count($transitems) > 0)
        {
            foreach($transitems as $item)
            {

                // $ucon = db::table('consolidated_oth')
                //     ->where('ornum', $ornum)
                //     ->where('itemid', $item->itemid)
                //     ->where('deleted', 0)
                //     ->count();

                // echo 'ornum: ' . $ornum . ' - ' . $tamount . '<br>';

                if($tamount > 0)
                {
                    if($item->amount > $tamount)
                    {
                        db::table('consolidated_oth')
                            ->insert([
                                'transdate' => $item->createddatetime,
                                'ornum' => $ornum,
                                'itemid' => $item->itemid,
                                'amount' => $tamount,
                                'classid' => $item->classid,
                                'userid' => auth()->user()->id
                            ]);

                        $tamount = 0;
                    }
                    else
                    {
                        db::table('consolidated_oth')
                            ->insert([
                                'transdate' => $item->createddatetime,
                                'ornum' => $ornum,
                                'itemid' => $item->itemid,
                                'amount' => $item->amount,
                                'classid' => $item->classid,
                                'userid' => auth()->user()->id
                            ]);

                        $tamount -= $item->amount;
                    }
                }
            }



            if($tamount > 0)
            {
                goto start;
            }
        }
        else
        {
            $syid = 0;
            $semid = 0;
            $trx = db::table('chrngtrans')
                ->where('ornum', $ornum)
                ->where('cancelled', 0)
                ->first();

            if($trx)
            {
                $syid= $trx->syid;
                $semid = $trx->semid;

                $stud = db::table('studinfo')
                    ->select('levelid')
                    ->where('id', $trx->studid)
                    ->first();

                $tuitions = db::table('tuitionheader')
                    ->select('itemid', 'tuitionitems.amount', 'classificationid')
                    ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                    ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                    ->where('levelid', $stud->levelid)
                    ->where('syid', $syid)
                    ->where(function($q) use($stud, $semid){
                        if($stud->levelid == 14 || $stud->levelid == 15)
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', $semid);
                            }
                        }
                        if($stud->levelid >= 17 && $stud->levelid <= 20)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('tuitiondetail.deleted', 0)
                    ->where('tuitionheader.deleted', 0)
                    ->where('tuitionitems.deleted', 0)
                    ->where('classificationid', 3)
                    ->groupBy('itemid')
                    ->get();

                $countin = 0;

                foreach($tuitions as $tui)
                {
                    if($tui->amount == $tamount)
                    {
                        db::table('consolidated_oth')
                            ->insert([
                                'transdate' => $trx->transdate,
                                'ornum' => $ornum,
                                'itemid' => $tui->itemid,
                                'amount' => $tui->amount,
                                'classid' => $tui->classificationid,
                                'userid' => auth()->user()->id
                            ]);

                        $countin += 1;
                    }
                }

                // return $countin;

                if($countin == 0)
                {
                    foreach($tuitions as $tui)
                    {
                        if($tamount > 0)
                        {
                            if($tui->amount >= $tamount)
                            {
                                db::table('consolidated_oth')
                                ->insert([
                                    'transdate' => $trx->transdate,
                                    'ornum' => $ornum,
                                    'itemid' => $tui->itemid,
                                    'amount' => $tamount,
                                    'classid' => $tui->classificationid,
                                    'userid' => auth()->user()->id
                                ]);

                                $tamount = 0;
                            }
                            else
                            {
                                db::table('consolidated_oth')
                                ->insert([
                                    'transdate' => $trx->transdate,
                                    'ornum' => $ornum,
                                    'itemid' => $tui->itemid,
                                    'amount' => $tui->amount,
                                    'classid' => $tui->classificationid,
                                    'userid' => auth()->user()->id
                                ]);

                                $tamount -= $tui->amount;
                            }
                        }
                    }
                }
            }
        }
    }


}
class ConsolidatedReport extends TCPDF {

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
    public function Footer() {
        $schoollogo = DB::table('schoolinfo')->first();
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        // $this->Cell(0, 15, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // $this->Cell(0, 5, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        if(strtolower($schoollogo->abbreviation) != 'msmi')
        {
            $this->Cell(0, 10, date('l, F d, Y'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
            $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            // $this->Cell(0, 15, date('m/d/Y'), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
    }
}
