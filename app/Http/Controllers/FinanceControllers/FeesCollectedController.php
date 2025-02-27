<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\FinanceModel;
use PDF;
use Dompdf\Dompdf;
use Session;
use Auth;
use Hash;

class FeesCollectedController extends Controller
{
    public function feescollected()
    {
        return view('finance.reports.feescollected');
    }

    public function fc_generate(Request $request)
    {
        $dates = explode(' - ', $request->get('dates'));
        $itemid = $request->get('itemid');
        $action = $request->get('action');
        $fc_list = collect();

        $datefrom = date_format(date_create($dates[0]), 'Y-m-d');
        $dateto = date_format(date_create($dates[1]), 'Y-m-d');

        $l = '';
        $total = 0;

        $item = db::table('items')
            ->where('id', $itemid)
            ->first();

            

        if($item->cash == 1)
        {
            $trans = db::table('chrngtrans')
                ->select('transdate', 'ornum', 'studname', 'chrngcashtrans.amount')
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('deleted', 0)
                ->where('kind', 'item')
                ->where('payscheddetailid', $itemid)
                ->where('chrngcashtrans.amount', '>', 0)
                ->orderBy('studname')
                ->get();

            if($trans)
            {
                $fc_list = $fc_list->merge($trans);
            }
        }

        if($item->isreceivable == 1)
        {
            $trans = db::table('chrngtrans')
                ->select('transdate', 'ornum', 'studname', 'chrngcashtrans.amount')
                ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                ->where('cancelled', 0)
                ->where('deleted', 0)
                ->where('kind', '!=', 'item')
                ->where('itemid', $itemid)
                ->where('chrngcashtrans.amount', '>', 0)
                ->orderBy('studname')
                ->get();

            if(count($trans) > 0)
            {
                $fc_list = $fc_list->merge($trans);
            }       
            else{
                $trans = db::table('chrngtrans')
                    ->select('transdate', 'ornum', 'studname', 'chrngcashtrans.amount')
                    ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
                    ->whereBetween('transdate', [$datefrom . ' 00:00', $dateto . ' 23:59'])
                    ->where('cancelled', 0)
                    ->where('deleted', 0)
                    ->where('kind', '!=', 'item')
                    ->where('chrngcashtrans.classid', $item->classid)
                    ->where('chrngcashtrans.amount', '>', 0)
                    ->orderBy('studname')
                    ->get();

                if(count($trans) > 0)
                {
                    $fc_list = $fc_list->merge($trans);
                }       
            }
        }

        foreach($fc_list as $list)
        {
            $l .='
                <tr>
                    <td>'.date_format(date_create($list->transdate), 'm-d-Y').'</td>
                    <td>'.$list->ornum.'</td>
                    <td>'.$list->studname.'</td>
                    <td>'.$item->description.'</td>
                    <td style="text-align:right">'.number_format($list->amount, 2).'</td>
                </tr>
            ';

            $total += $list->amount;
        }

        $l.='
            <tr>
                <td colspan="5" style="text-align:right; font-weight:bold">'.number_format($total, 2).'</td>
            </tr>
        ';

        $data = array(
            'list' => $l,
            'datefrom' => date_format(date_create($datefrom), 'F d, Y'),
            'dateto' => date_format(date_create($dateto), 'F d, Y'),
            'datenow' => date_format(date_create(FinanceModel::getServerDateTime()), 'F d, Y')
        );

        if($action == 'generate')
        {
            echo json_encode($data);
        }
        else
        {
            // $pdf = PDF::loadview('finance.reports.pdf.pdf_feescollected', compact('data'));
            // return $pdf->stream('studledger.pdf');

            $pdf = PDF::loadView('/finance/reports/pdf/pdf_feescollected', $data);
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream('Fees Collected.pdf');
        }


    }
    
    public function assessedfees()
    {
        return view('finance.reports.assessedfees');
    }
    
    public function af_generate(Request $request)
    {
        $dates = explode(' - ', $request->get('dates'));
        $itemid = $request->get('itemid');
        $action = $request->get('action');
        $af_list = collect();
        $shssetup = db::table('schoolinfo')->first()->shssetup;
        $itemdesc = db::table('items')->where('id', $itemid)->first()->description;
        $list = '';
        $datefrom = date_format(date_create($dates[0]), 'Y-m-d');
        $dateto = date_format(date_create($dates[1]), 'Y-m-d');
        $totalamount = 0;
        $acadprogid = $request->get('acadprog');

        $enrolledstud = collect();

        $levels = array();

        $gradelevel = db::table('gradelevel')
            ->where('acadprogid', $acadprogid)
            ->where('deleted', 0)
            ->get();

        foreach($gradelevel as $level)
        {
            array_push($levels, $level->id);
        }

        $_enrolled = db::table('enrolledstud')
            ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'dateenrolled', 'enrolledstud.levelid', 'syid', 'ghssemid as semid')
            ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
            ->whereBetween('dateenrolled', [$datefrom . ' 00:00', $dateto . ' 23:59'])
            ->where('enrolledstud.deleted', 0)
            ->where('enrolledstud.studstatus', '>', 0)
            ->whereIN('enrolledstud.levelid', $levels)
            ->groupBy('studid')
            ->get();

        $sh_enrolled = db::table('sh_enrolledstud')
            ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'dateenrolled', 'syid', 'sh_enrolledstud.semid', 'sh_enrolledstud.levelid')
            ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
            ->whereBetween('dateenrolled', [$datefrom . ' 00:00', $dateto . ' 23:59'])
            ->where('sh_enrolledstud.deleted', 0)
            ->where('sh_enrolledstud.studstatus', '>', 0)
            ->whereIN('sh_enrolledstud.levelid', $levels)
            ->groupBy('studid')
            ->get();

        $college_enrolled = db::table('college_enrolledstud')
            ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'date_enrolled as dateenrolled', 'yearlevel as levelid', 'syid', 'college_enrolledstud.semid')
            ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
            ->whereBetween('date_enrolled', [$datefrom . ' 00:00', $dateto . ' 23:59'])
            ->where('college_enrolledstud.deleted', 0)
            ->where('college_enrolledstud.studstatus', '>', 0)
            ->whereIN('college_enrolledstud.yearlevel', $levels)
            ->groupBy('studid')
            ->get();

        $enrolledstud = $enrolledstud->merge($_enrolled);
        $enrolledstud = $enrolledstud->merge($sh_enrolled);
        $enrolledstud = $enrolledstud->merge($college_enrolled);
        $enrolledstud = $enrolledstud->sortBy('lastname');

        foreach($enrolledstud as $stud)
        {
            $itemized = db::table('studledgeritemized')
                ->select('itemamount as amount')
                ->where('studid', $stud->id)
                ->where('itemid', $itemid)
                ->where('syid', $stud->syid)
                ->where(function($q) use($stud, $shssetup){
                    if($stud->levelid == 14 || $stud->levelid == 15)
                    {
                        if($stud->semid == 3)
                        {
                            $q->where('semid', 3);
                        }
                        else
                        {
                            if($shssetup == 0)
                            {
                                $q->where('semid', $stud->semid);
                            }
                        }
                    }
                    elseif($stud->levelid >= 17 && $stud->levelid <= 21)
                    {
                        $q->where('semid', $stud->semid);
                    }
                    else
                    {
                        if($stud->semid == 3)
                        {
                            $q->where('semid', 3);
                        }
                    }
                })
                ->where('deleted', 0)
                ->first();

            if($itemized)
            {
                $list .='
                    <tr>
                        <td>'.date_format(date_create($stud->dateenrolled), 'm-d-Y').'</td>
                        <td>'.$stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename.'</td>
                        <td>'.$itemdesc.'</td>
                        <td style="text-align:right;">'.number_format($itemized->amount, 2).'</td>
                    </tr>
                ';

                $totalamount += $itemized->amount;
            }

        }

        $list .='
            <tr>
                <td colspan="3" style="text-align:right; font-weight:bold;">TOTAL: '.number_format($totalamount, 2).'</td>
            </tr>
        ';

        $data = array(
            'list' => $list,
            'datefrom' => date_format(date_create($datefrom), 'F d, Y'),
            'dateto' => date_format(date_create($dateto), 'F d, Y'),
            'datenow' => date_format(date_create(FinanceModel::getServerDateTime()), 'F d, Y')
        );

        if($action == 'generate')
        {
            echo json_encode($data);
        }
        else
        {
            // $pdf = PDF::loadview('finance.reports.pdf.pdf_feescollected', compact('data'));
            // return $pdf->stream('studledger.pdf');

            $pdf = PDF::loadView('/finance/reports/pdf/pdf_assessedfees', $data);
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream('Assessed Fees.pdf');
        }
    }
    

    

    

}
