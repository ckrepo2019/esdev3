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

class AccountingController extends Controller
{
    public function setup()
    {
        return view('finance/accounting/setup');
    }

    public function accountingsetup()
    {
        return view('finance/accounting/accountingsetup');   
    }

    Public function journalentries()
    {
    	return view('finance/accounting/journalentries');
    }

    public function jeloadcoa(Request $request)
    {
    	if($request->ajax())
    	{
    		$coalist = FinanceModel::loadCOA();

    		$list = '<option value="0">Select Account</option>';

    		foreach($coalist as $coa)
    		{
    			$list .='
                    <option value="'.$coa->id.'">' .$coa->code. ' - ' .$coa->account.'</option>
    			';
    		}

    		$data = array(
    			'coalist' => $list
    		);

    		echo json_encode($data);
    	}
    }

    public function saveje(Request $request)
    {
        if($request->ajax())
        {
            $accid = $request->get('accid');
            $debit = str_replace(',', '', $request->get('isdebit'));
            $credit = str_replace(',', '', $request->get('iscredit'));
            $datetrans = $request->get('transdate');
            $refid = $request->get('refid');
            $action = $request->get('action');
            $remarks = $request->get('remarks');

            $refnum = '';

            // echo $refid;
            
                if($action == 'create')
                {
                    if($refid == '')
                    {
                        $jeid = db::table('acc_je')
                            ->insertGetId([
                                'transdate' => $datetrans,
                                'jestatus' => 'Draft',
                                'remarks' => $remarks,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);

                        db::table('acc_jedetails')
                            ->insert([
                                'headerid' => $jeid,
                                'accid' => $accid,
                                'debit' => $debit,
                                'credit' => $credit,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);

                        $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);

                        db::table('acc_je')
                            ->where('id', $jeid)
                            ->update([
                                'refid' => $refnum
                            ]);

                        $data = array(
                            'refid' => $jeid,
                            'refnum' => $refnum
                        );

                        return $data;

                        // echo json_encode($data);
                    }
                    else
                    {
                        db::table('acc_jedetails')
                            ->insert([
                                'headerid' => $refid,
                                'accid' => $accid,
                                'debit' => $debit,
                                'credit' => $credit,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);                    
                    }
                }
                else
                {
                    $detailid = $request->get('detailid');
                    db::table('acc_je')
                        ->where('id', $refid)
                        ->update([
                            'transdate' => $datetrans,
                            'remarks' => $remarks,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);

                    if($detailid != '')
                    {
                        db::table('acc_jedetails')
                            ->where('id', $detailid)
                            ->update([
                                'accid' => $accid,
                                'debit' => $debit,
                                'credit' => $credit,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => FinanceModel::getServerDateTime()
                            ]);
                    }
                    else
                    {
                        db::table('acc_jedetails')
                            ->insert([
                                'headerid' => $refid,
                                'accid' => $accid,
                                'debit' => $debit,
                                'credit' => $credit,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);
                    }

                }
            // return 0;
        }
    }

    public function loadje(Request $request)
    {
        if($request->ajax())
        {
            $daterange = $request->get('daterange');

            // return $daterange;

            $daterange = explode(" - ",$daterange);

            $dateArray = array();

            $totalamount = 0;
            $totaldebit = 0;
            $totalcredit = 0;

            // return $daterange[0];

            if($daterange[0] != '')
            {
                $d1 = date_create($daterange[0]);
                $d1 = date_format($d1, 'Y-m-d 00:00');
                array_push($dateArray, $d1);
            }
            else
            {
                $d1 = date_create($FinanceModel::getServerDateTime());
                $d1 = date_format($d1, 'Y-m-d 00:00');
                array_push($dateArray, $d1);
            }

            $d2 = date_create($daterange[1]);
            $d2 = date_format($d2, 'Y-m-d 23:59');
            array_push($dateArray, $d2);

            // $entries = db::table('acc_je')
            //     ->select(DB::raw('acc_je.id, transdate, refid, SUM(debit) as damount, jestatus'))
            //     ->join('acc_jedetails', 'acc_je.id', '=', 'acc_jedetails.headerid')
            //     ->whereBetween('transdate', $dateArray)
            //     ->where('acc_je.deleted', 0)
            //     ->groupBy('headerid')
            //     ->orderBy('acc_je.transdate', 'DESC')
            //     ->get();

            $entries = db::table('acc_je')
                ->select(db::raw('acc_je.id, acc_je.createddatetime, refid, acc_coa.`code`, acc_coa.`account`, debit, credit, remarks'))
                ->join('acc_jedetails', 'acc_je.id', '=', 'acc_jedetails.headerid')
                ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
                ->where('acc_je.deleted', 0)
                ->where('acc_jedetails.deleted', 0)
                ->whereBetween('transdate', $dateArray)
                ->get();

            $jelist = '';

            foreach($entries as $je)
            {
                $date = date_create($je->createddatetime);
                $date = date_format($date, 'm-d-Y');
                $totaldebit += $je->debit;
                $totalcredit += $je->credit;
                // $jelist .='
                //     <tr data-id="'.$je->id.'">
                //         <td>'.$date.'</td>
                //         <td>'.$je->refid.'</td>
                //         <td class="text-right" style="width:180px">'.number_format($je->damount, 2).'</td>
                //         <td>'.$je->jestatus.'</td>
                //     </tr>
                // ';

                // $totalamount += $je->damount;

                $jelist .='
                    <tr data-id="'.$je->id.'">
                        <td>'.$date.'</td>
                        <td>'.$je->refid.'</td>
                        <td>'.$je->code.' - '.$je->account.'</td>
                        <td class="text-right">'.number_format($je->debit, 2).'</td>
                        <td class="text-right">'.number_format($je->credit, 2).'</td>
                        <td>'.$je->remarks.'</td>
                    </tr>
                ';
            }

            $data = array(
                'jelist' => $jelist,
                // 'totalamount' => number_format($totalamount, 2)
                'totaldebit' => number_format($totaldebit, 2),
                'totalcredit' => number_format($totalcredit, 2)
            );

            echo json_encode($data);

        }

    }

    public function editje(Request $request)
    {
        $jeid = $request->get('jeid');
        $action = $request->get('action');

        // return $action;

        $debit = 0;
        $credit = 0;

        $acc_array = array();

        $acc_je = db::table('acc_je')
            ->select('id', 'transdate', 'refid', 'jestatus', 'remarks')
            ->where('id', $jeid)
            ->first();

        $detail = db::table('acc_jedetails')
            ->select('acc_jedetails.*', 'code', 'account', 'acc_coa.id as accid')
            ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
            ->where('headerid', $jeid)
            ->where('acc_jedetails.deleted', 0)
            ->get();

        $jelist = '';
        $transdate = date_create($acc_je->transdate);
        $transdate = date_format($transdate, 'Y-m-d');

        $count = 0;
        foreach($detail as $je)
        {
            $count += 1;

            $debit += $je->debit;
            $credit += $je->credit;

            $coalist = FinanceModel::loadCOA();

            // return $coalist;

            $chart = '<option value="0">Select Account</option>';

            foreach($coalist as $coa)
            {
                if($coa->id == $je->accid)
                {
                    $chart .='
                        <option value="'.$coa->id.'" selected>' .$coa->code. ' - ' .$coa->account.'</option>
                    ';
                }
                else
                {
                    $chart .='
                        <option value="'.$coa->id.'">' .$coa->code. ' - ' .$coa->account.'</option>
                    ';
                }
            }

            // return $chart;

            $jelist ='
                <div class="row je-body p-1" data-line="'.$count.'" data-id="'.$je->id.'">

                    <div class="col-md-7">
                        <select id="je-coa" class="select2bs4 je-coa form-control">
                          '.$chart.'
                        </select>
                    </div>

                    <div class="col-md-2 text-right">
                        <input id="txtdebit" type="text" class="text-right isdebit form-control" pattern="^\\$\\d{1,3}(,\\d{3})*(\\.\\d+)?$" name="currency-field" data-type="currency" autocomplete="off" value="'.number_format($je->debit, 2).'">
                    </div>

                    <div class="col-md-2 text-right">
                        <input id="txtcredit" class="text-right iscredit form-control" pattern="^\\$\\d{1,3}(,\\d{3})*(\\.\\d+)?$" name="currency-field" data-type="currency" autocomplete="off" value="'.number_format($je->credit, 2).'">
                    </div>

                    <div class="col-md-1">
                        <button class="btn btn-danger btn-sm btn-linedel" data-id="'.$je->id.'">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                </div>
            ';

            array_push($acc_array, $jelist);

        }

        // return $detail;

        $data = array(
            'refid' => $acc_je->refid,
            'transdate' => $transdate,
            'jeid' => $acc_je->id,
            'jestatus' => $acc_je->jestatus,
            'jearray' => $acc_array,
            'iscredit' => number_format($credit, 2),
            'isdebit' => number_format($debit, 2),
            'remarks' => $acc_je->remarks
        );

        if($action == 'print')
        {
            // $supplier = db::table('expense_company')->where('id', $purchasing->supplierid)->first()->companyname;
            // return $action;
            // return view('finance.purchasing.reports.pdf_po', compact('purchasing', 'data'));
            $pdf = PDF::loadview('finance.accounting.reports.pdf_jv', compact('data', 'detail'));
            return $pdf->stream('Journal Voucher.pdf');
        }
        else{
            echo json_encode($data);
        }
    
    }

    public function deletejedetail(Request $request)
    {
        if($request->ajax())
        {
            $detailid = $request->get('detailid');

            db::table('acc_jedetails')
                ->where('id', $detailid)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => FinanceModel::getServerDateTime()
                ]);

            return 1;
        }
    }

    public function appendeditdetail(Request $request)
    {
        if($request->ajax())
        {
            $headerid = $request->get('headerid');

            $detailid = db::table('acc_jedetails')
                ->insertGetId([
                    'headerid' => $headerid,
                    'deleted' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);


            $detail = db::table('acc_jedetails')
                ->where('id', $detailid)
                ->first();

            $data = array(
                'detailid' => $detailid
            );

            echo json_encode($data);
        }
    }

    public function postje(Request $request)
    {
        if($request->ajax())
        {
            $refid = $request->get('refid');

            db::table('acc_je')
                ->where('id', $refid)
                ->update([
                    'jestatus' => 'Posted',
                    'transtype' => 'JE',
                    'posteddatetime' => FinanceModel::getServerDateTime(),
                    'postedby' => auth()->user()->id,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => FinanceModel::getServerDateTime()
                ]);

        }
    }

    public function je_calcdebit(Request $request)
    {
        if($request->ajax())
        {
            $val = $request->get('val');

            return number_format($val, 2);
        }
    }

    public function reports()
    {
        return view('finance/accounting/reports');
    }

    // public function bs_generate(Request $request)
    // {
    //     if($request->ajax())
    //     {
    //         $daterange = $request->get('daterange');
    //         $setupid = 1; //$request->get('setupid');

    //         $daterange = explode(' - ', $daterange);
    //         $datefrom = $daterange[0];
    //         $dateto = $daterange[1];

    //         $datefrom = date_create($datefrom);
    //         $datefrom = date_format($datefrom, 'Y-m-d');

    //         $dateto = date_create($dateto);
    //         $dateto = date_format($dateto, 'Y-m-d');

    //         // return $daterange;

    //         $date_range = array();
    //         array_push($date_range, $datefrom . ' 00:00');
    //         array_push($date_range, $dateto . ' 23:59');

    //         $list_assets1 = '';
    //         $list_assets2 = '';

    //         $list_liabilities ='';
    //         $totalliabilities = 0;

    //         $list_equity = '';
    //         $totalequity = 0;

    //         $subid = 0;
    //         $classification = '';
    //         $subcount = 0;

    //         $acc_amount = 0;

    //         $total_ca = 0;
    //         $total_nca = 0;
    //         $totalassets = 0;

    //         $revenue = '';
    //         $totalrevenue = 0;

    //         $cor = '';
    //         $totalcor = 0;

    //         $oth = '';
    //         $totaloth = 0;

    //         $genexpenses = '';
    //         $totalgenexpenses = 0;

    //         // IS

    //         $header = db::table('acc_rptsetup')
    //             ->select(db::raw('headerid, mapid, subid, acc_rptsetupdetail.description'))
    //             ->join('acc_rptsetupheader', 'acc_rptsetup.id', '=', 'acc_rptsetupheader.setupid')
    //             ->join('acc_rptsetupsub', 'acc_rptsetupheader.id', '=', 'acc_rptsetupsub.headerid')
    //             ->join('acc_rptsetupdetail', 'acc_rptsetupsub.id', '=', 'acc_rptsetupdetail.subid')
    //             ->where('acc_rptsetup.id', 2)
    //             ->where('acc_rptsetup.deleted', 0)
    //             ->where('acc_rptsetupheader.deleted', 0)
    //             ->where('acc_rptsetupsub.deleted', 0)
    //             ->where('acc_rptsetupdetail.deleted', 0)
    //             ->get();

    //         foreach($header as $head)
    //         {
    //             $jedetails = db::table('acc_jedetails')                
    //                 ->select(db::raw('refid, debit, credit, gid, `code`, sum(debit)-sum(credit) as amount'))
    //                 ->join('acc_je', 'acc_jedetails.headerid', '=', 'acc_je.id')
    //                 ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
    //                 ->where('mapid', $head->mapid)
    //                 ->where('acc_jedetails.deleted', 0)
    //                 ->where('acc_je.deleted', 0)
    //                 ->where('jestatus', 'Posted')
    //                 ->whereBetween('transdate', $date_range)
    //                 ->first();

    //             if($jedetails)
    //             {
    //                 $_amount = 0;

    //                 if($jedetails->amount < 0)
    //                 {
    //                     $_amount = $jedetails->amount * -1;
    //                 }
    //                 else
    //                 {
    //                     $_amount = $jedetails->amount;
    //                 }

    //                 $jedetails = db::table('acc_jedetails')                
    //                     ->select(db::raw('refid, debit, credit, gid, `code`, sum(debit)-sum(credit) as amount'))
    //                     ->join('acc_je', 'acc_jedetails.headerid', '=', 'acc_je.id')
    //                     ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
    //                     ->where('mapid', $head->mapid)
    //                     ->where('acc_jedetails.deleted', 0)
    //                     ->where('acc_je.deleted', 0)
    //                     ->where('jestatus', 'Posted')
    //                     ->whereBetween('transdate', $date_range)
    //                     ->first();


    //                 if($head->headerid == 4)
    //                 {
    //                     $revenue .='
    //                         <div class="row" data-subid="'.$head->subid.'">
    //                             <div class="col-md-8 pl-5">
    //                                 '.$head->description.'
    //                             </div>
    //                             <div class="col-md-4 text-right">
    //                                 '.number_format($_amount, 2).'
    //                             </div>
    //                         </div>
    //                     ';

    //                     $totalrevenue += $_amount;
                        
    //                 }
    //                 elseif($head->headerid == 5)
    //                 {
    //                     $cor .='
    //                         <div class="row" data-subid="'.$head->subid.'">
    //                             <div class="col-md-8 pl-5">
    //                                 '.$head->description.'
    //                             </div>
    //                             <div class="col-md-4 text-right">
    //                                 '.number_format($_amount, 2).'
    //                             </div>
    //                         </div>
    //                     ';

    //                     $totalcor += $_amount;   
    //                 }
    //                 elseif($head->headerid == 6)
    //                 {
    //                     if($oth != $head->description)
    //                     {
    //                         $oth = $head->description;    
    //                     }

    //                     $totaloth += $_amount;
    //                 }
    //                 elseif($head->headerid == 7)
    //                 {
    //                     $genexpenses .='
    //                         <div class="row" data-subid="'.$head->subid.'">
    //                             <div class="col-md-8 pl-5">
    //                                 '.$head->description.'
    //                             </div>
    //                             <div class="col-md-4 text-right">
    //                                 '.number_format($_amount, 2).'
    //                             </div>
    //                         </div>
    //                     ';

    //                     $totalgenexpenses += $_amount;
    //                 }
    //             }
    //         }

    //         $beforetax = $totalrevenue - $totalcor + $totaloth - $totalgenexpenses;
    //         $btax = 0;

    //         if($beforetax < 0)
    //         {
    //             $btax = $beforetax * -1;
    //             $btax = '(' .number_format($btax, 2). ')';
    //         }
    //         else
    //         {
    //             $btax = number_format($beforetax, 2);
    //         }

    //         $isexpense = db::table('acc_jedetails')                
    //                 ->select(db::raw('refid, debit, credit, gid, `code`, sum(credit)-sum(debit) as amount'))
    //                 ->join('acc_je', 'acc_jedetails.headerid', '=', 'acc_je.id')
    //                 ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
    //                 ->where('mapid', 23)
    //                 ->where('acc_jedetails.deleted', 0)
    //                 ->where('acc_je.deleted', 0)
    //                 ->where('jestatus', 'Posted')
    //                 ->whereBetween('transdate', $date_range)
    //                 ->first();

    //         $taxamount = 0;

    //         if($isexpense)
    //         {
    //             $taxamount = $isexpense->amount;
    //             $tamount = 0;

    //             if($taxamount < 0)
    //             {
    //                 $tamount = $taxamount * -1;
    //                 $tamount = '(' . number_format($tamount, 2) . ')';
    //             }
    //             else
    //             {
    //                 $tamount = number_format($taxamount, 2);
    //             }
    //         }

    //         $nincome = $beforetax + $taxamount;
    //         $netincome = 0;

    //         if($nincome < 0)
    //         {
    //             $netincome = $nincome * -1;
    //             $netincome = '(' . number_format($netincome, 2) . ')';
    //         }


    //         //BS

    //         $header = db::table('acc_rptsetupheader')
    //             ->select(db::raw('headerid, acc_rptsetupdetail.id as detailid, subid, classification, `group`, acc_rptsetupdetail.`description`, mapid'))
    //             ->join('acc_coaclass', 'acc_rptsetupheader.classid', '=', 'acc_coaclass.id')
    //             ->join('acc_rptsetupsub', 'acc_rptsetupheader.id', '=', 'acc_rptsetupsub.headerid')
    //             ->join('acc_coagroup', 'acc_rptsetupsub.groupid', '=', 'acc_coagroup.id')
    //             ->join('acc_rptsetupdetail', 'acc_rptsetupsub.id', '=', 'acc_rptsetupdetail.subid')
    //             ->where('setupid', $setupid)
    //             ->where('acc_rptsetupdetail.deleted', 0)
    //             ->whereBetween('mapid', [1,9])
    //             ->get();

    //         foreach($header as $head)
    //         {
    //             if($head->headerid == 1)
    //             {
    //                 $jedetails = db::table('acc_jedetails')                
    //                     ->select(db::raw('refid, debit, credit, gid, `code`, sum(debit)-sum(credit) as amount'))
    //                     ->join('acc_je', 'acc_jedetails.headerid', '=', 'acc_je.id')
    //                     ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
    //                     ->where('mapid', $head->mapid)
    //                     ->where('acc_jedetails.deleted', 0)
    //                     ->where('acc_je.deleted', 0)
    //                     ->where('jestatus', 'Posted')
    //                     ->whereBetween('transdate', $date_range)
    //                     ->first();
    //             }
    //             elseif($head->headerid == 2)
    //             {
    //                 $jedetails = db::table('acc_jedetails')                
    //                     ->select(db::raw('refid, debit, credit, gid, `code`, sum(credit)-sum(debit) as amount'))
    //                     ->join('acc_je', 'acc_jedetails.headerid', '=', 'acc_je.id')
    //                     ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
    //                     ->where('mapid', $head->mapid)
    //                     ->where('acc_jedetails.deleted', 0)
    //                     ->where('acc_je.deleted', 0)
    //                     ->where('jestatus', 'Posted')
    //                     ->whereBetween('transdate', $date_range)
    //                     ->first();
    //             }
    //             elseif($head->headerid == 3)
    //             {
    //                 $jedetails = db::table('acc_jedetails')                
    //                     ->select(db::raw('refid, debit, credit, gid, `code`, sum(credit)-sum(debit) as amount'))
    //                     ->join('acc_je', 'acc_jedetails.headerid', '=', 'acc_je.id')
    //                     ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
    //                     ->where('mapid', $head->mapid)
    //                     ->where('acc_jedetails.deleted', 0)
    //                     ->where('acc_je.deleted', 0)
    //                     ->where('jestatus', 'Posted')
    //                     ->whereBetween('transdate', $date_range)
    //                     ->first();   
    //             }

    //             // echo $head->mapid . ' ' . $jedetails->amount . '<br>';

    //             // echo $head->description . ' - ' . number_format($jedetails->amount, 2) . ' subid: ' . $head->subid . ' group: ' . $head->group . '<br>' ;
    //             // if($head->mapid == 9)
    //             // {
    //             //     return $jedetails->amount. ' - ' . $jedetails->mapid;
    //             // }

    //             if($jedetails)
    //             {
    //                 if($head->headerid == 1 && $head->subid == 1)
    //                 {
    //                     $list_assets1 .='
    //                         <div class="row" data-subid="'.$head->subid.'">
    //                             <div class="col-md-8">
    //                                 '.$head->description.'
    //                             </div>
    //                             <div class="col-md-4 text-right">
    //                                 '.number_format($jedetails->amount, 2).'
    //                             </div>
    //                         </div>
    //                     ';

    //                     $total_ca += $jedetails->amount;
    //                 }

    //                 if($head->headerid == 1 && $head->subid == 2)
    //                 {
    //                     $list_assets2 .='
    //                         <div class="row" data-subid="'.$head->subid.'">
    //                             <div class="col-md-8">
    //                                 '.$head->description.'
    //                             </div>
    //                             <div class="col-md-4 text-right">
    //                                 '.number_format($jedetails->amount, 2).'
    //                             </div>
    //                         </div>
    //                     ';

    //                     $total_nca += $jedetails->amount;
    //                 }

    //                 if($head->headerid == 2)
    //                 {
    //                     $list_liabilities .='
    //                         <div class="row" data-subid="'.$head->subid.'">
    //                             <div class="col-md-8">
    //                                 '.$head->description.'
    //                             </div>
    //                             <div class="col-md-4 text-right">
    //                                 '.number_format($jedetails->amount, 2).'
    //                             </div>
    //                         </div>
    //                     '; 

    //                     $totalliabilities += $jedetails->amount;                       
    //                 }

    //                 if($head->headerid == 3)
    //                 {
    //                     $list_equity .='
    //                         <div class="row" data-subid="'.$head->subid.'">
    //                             <div class="col-md-8">
    //                                 '.$head->description.'
    //                             </div>
    //                             <div class="col-md-4 text-right">
    //                                 '.number_format($jedetails->amount + $nincome, 2).'
    //                             </div>
    //                         </div>
    //                     '; 

    //                     $totalequity += $jedetails->amount;                       
    //                 }
    //             }

    //         }

    //         $totalassets = $total_ca + $total_nca;
    //         $totalequity += $totalliabilities;
    //         $totalequity += $nincome;
    //         //BS

    //         // echo 'total ca: ' . number_format($total_ca, 2) . '; <br> ';
    //         // echo 'total nca: ' . number_format($total_nca, 2) . '; <br> ';

    //         $data = array(
    //             'assets1' => $list_assets1,
    //             'totalassets1' => number_format($total_ca, 2),
    //             'assets2' => $list_assets2,
    //             'totalassets2' => number_format($total_nca, 2),
    //             'totalassets' => number_format($totalassets, 2),
    //             'liabilities' => $list_liabilities,
    //             'totalliabilities' => number_format($totalliabilities, 2),
    //             'equity' => $list_equity,
    //             'totalequity' => number_format($totalequity, 2),
    //             'revenue' => $revenue,
    //             'totalrevenue' => number_format($totalrevenue, 2),
    //             'cor' => $cor,
    //             'totalcor' => number_format($totalcor, 2),
    //             'oth' => $oth,
    //             'totaloth' => number_format($totaloth, 2),
    //             'genexpenses' => $genexpenses,
    //             'totalgenexpenses' => number_format($totalgenexpenses),
    //             'btax' => $btax,
    //             'taxamount' => $tamount,
    //             'netincome' => $netincome
    //         );

    //         echo json_encode($data);

    //     }
    // }

    public static function is_generate(Request $request)
    {
        $range = explode(' - ', $request->get('range'));
        $datefrom = date_format(date_create($range[0]), 'Y-m-d 00:00');
        $dateto = date_format(date_create($range[1]), 'Y-m-d 23:59');
        $action = $request->get('action');

        $gl = db::table('acc_je')
            ->select(db::raw('code, account, SUM(debit) AS debit, SUM(credit) AS credit, classification, mapid'))
            ->join('acc_jedetails', 'acc_je.id', '=', 'acc_jedetails.headerid')
            ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
            ->join('acc_coagroup', 'acc_coa.gid', '=', 'acc_coagroup.id')
            ->join('acc_coaclass', 'acc_coagroup.coaclass', '=', 'acc_coaclass.id')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('acc_jedetails.deleted', 0)
            ->where('acc_je.deleted', 0)
            ->groupBy('accid')
            ->orderBy('code')
            ->get();

        $ledger = collect($gl);

        $issetup = db::table('acc_reportsetup_is')
            ->where('deleted', 0)
            ->where('rpttype', 'revenue')
            ->get();

        $is_setup = collect($issetup);

        // return $issetup;

        $isreport = array();
        $headername = '';
        $totalassets = 0;
        $totalexpenses = 0;
        $totalincome = 0;


        foreach($issetup as $is)
        {
            if($is->header == 1)
            {
                array_push($isreport, (object)[
                    'header' => 1,
                    'title' => $is->description,
                    'rpttype' => $is->rpttype,
                    'mapid' => '',
                    'amount' => '',
                    'sortid' => $is->sortid
                ]);

                $headername = $is->description;
            }
            else
            {
                // return $is->mapid;
                $subtotal = 0;
                $gl_revenue = $ledger->where('mapid', $is->mapid);
                $gl_revenue->all();

                // return $gl_revenue;
                

                foreach($gl_revenue as $gl_rev)
                {
                    $amount = 0;

                    // if($gl_rev->debit >= $gl_rev->credit)
                    // {
                    //     $amount = $gl_rev->debit - $gl_rev->credit;
                    // }
                    // else
                    // {
                    //     $amount = $gl_rev->credit - $gl_rev->debit;
                    // }

                    $amount = $gl_rev->credit - $gl_rev->debit;
                    $_amount = '';
                    if($amount < 0)
                    {
                        $_amount = $amount * -1;
                        // return $_amount;

                        $_amount = '(' . number_format($_amount, 2) . ')';
                    }
                    else{
                        $_amount = number_format($amount, 2);
                    }

                    array_push($isreport, (object)[
                        'header' => 0,
                        'title' => $gl_rev->code . ' ' . $gl_rev->account,
                        'rpttype' => $is->rpttype,
                        'mapid' => $gl_rev->mapid,
                        'amount' => $_amount,
                        'sortid' => $is->sortid
                    ]);

                    $subtotal += $amount;
                }

                if($subtotal > 0)
                {
                    array_push($isreport, (object)[
                        'header' => 0,
                        'title' => $headername . ' TOTAL: ',
                        'rpttype' => 'subtotal',
                        'mapid' => '',
                        'amount' => number_format($subtotal, 2),
                        // 'sortid' => $is->sortid
                    ]);

                    $totalincome += $subtotal;
                }
                
            }

            
            
        }

        $issetup = db::table('acc_reportsetup_is')
            ->where('deleted', 0)
            ->where('rpttype', 'expenses')
            ->get();

        $is_setup = collect($issetup);

        $headername = $is->description;

        foreach($issetup as $is)
        {
            if($is->header == 1)
            {
                array_push($isreport, (object)[
                    'header' => 1,
                    'title' => $is->description,
                    'rpttype' => $is->rpttype,
                    'mapid' => '',
                    'amount' => '',
                    'sortid' => $is->sortid
                ]);

                $headername = $is->description;
            }
            else
            {
                // return $is->mapid;
                $subtotal = 0;
                $gl_revenue = $ledger->where('mapid', $is->mapid);
                $gl_revenue->all();

                // return $gl_revenue;

                foreach($gl_revenue as $gl_rev)
                {
                    $amount = 0;

                    // if($gl_rev->debit >= $gl_rev->credit)
                    // {
                    //     $amount = $gl_rev->debit - $gl_rev->credit;
                    // }
                    // else
                    // {
                    //     $amount = $gl_rev->credit - $gl_rev->debit;
                    // }
                    $amount = $gl_rev->debit - $gl_rev->credit;
                    $_amount = '';
                    if($amount < 0)
                    {
                        $_amount = $amount * -1;
                        // return $_amount;

                        $_amount = '(' . number_format($_amount, 2) . ')';
                    }
                    else{
                        $_amount = number_format($amount, 2);
                    }

                    array_push($isreport, (object)[
                        'header' => 0,
                        'title' => $gl_rev->code . ' ' . $gl_rev->account,
                        'rpttype' => $is->rpttype,
                        'mapid' => $gl_rev->mapid,
                        'amount' => $_amount,
                        'sortid' => $is->sortid
                    ]);

                    $subtotal += $amount;
                }

                if($subtotal > 0)
                {
                    array_push($isreport, (object)[
                        'header' => 0,
                        'title' => $headername . ' TOTAL: ',
                        'rpttype' => 'subtotal',
                        'mapid' => '',
                        'amount' => number_format($subtotal, 2),
                        // 'sortid' => $is->sortid
                    ]);

                    $totalexpenses += $subtotal;
                }
                
            }
            
        }

        array_push($isreport, (object)[
            'header' => 0,
            'title' => 'NET INCOME',
            'rpttype' => 'nettotal',
            'mapid' => '',
            'amount' => number_format($totalincome - $totalexpenses, 2),
            // 'sortid' => $is->sortid
        ]);

        if($action == 'print')
        {
            $pdf = PDF::loadview('finance.accounting.reports.pdf_incomestatement', compact('isreport', 'datefrom', 'dateto'));
		    return $pdf->stream('IncomeStatement.pdf');
        }
        elseif($action=='bs_print')
        {
            return number_format($totalincome - $totalexpenses, 2);
        }
        else{
            return $isreport;
        }

        
        
        

        

        // $rev1 = db::table('')
        
    }

    public function bs_generate(Request $request)
    {
        $range = explode(' - ', $request->get('range'));
        $datefrom = date_format(date_create($range[0]), 'Y-m-d 00:00');
        $dateto = date_format(date_create($range[1]), 'Y-m-d 23:59');
        $action = $request->get('action');

        $gl = db::table('acc_je')
            ->select(db::raw('code, account, SUM(debit) AS debit, SUM(credit) AS credit, classification, mapid'))
            ->join('acc_jedetails', 'acc_je.id', '=', 'acc_jedetails.headerid')
            ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
            ->join('acc_coagroup', 'acc_coa.gid', '=', 'acc_coagroup.id')
            ->join('acc_coaclass', 'acc_coagroup.coaclass', '=', 'acc_coaclass.id')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('acc_jedetails.deleted', 0)
            ->where('acc_je.deleted', 0)
            ->groupBy('accid')
            ->orderBy('code')
            ->get();

        $ledger = collect($gl);

        $bssetup = db::table('acc_reportsetup_bs')
            ->where('deleted', 0)
            ->where('rpttype', 'assets')
            ->orderBy('sortid')
            ->get();

        $bsreport = array();
        $headername = '';
        $totalassets = 0;
        $totalliabilities = 0;
        // $totalexpenses = 0;
        // $totalincome = 0;


        // return $gl;

        foreach($bssetup as $bs)
        {
            if($bs->header == 1)
            {
                array_push($bsreport, (object)[
                    'header' => 1,
                    'title' => $bs->description,
                    'rpttype' => $bs->rpttype,
                    'mapid' => '',
                    'amount' => '',
                    'sortid' => $bs->sortid
                ]);

                $headername = $bs->description;
            }
            else
            {
                // return $is->mapid;
                $subtotal = 0;
                $gl_assets = $ledger->where('mapid', $bs->mapid);
                $gl_assets->all();

                // return $gl_revenue;
                

                foreach($gl_assets as $asset)
                {
                    $amount = 0;

                    // if($asset->debit >= $asset->credit)
                    // {
                    //     $amount = $asset->debit - $asset->credit;
                    // }
                    // else
                    // {
                    //     $amount = $asset->credit - $asset->debit;
                    // }

                    $amount = $asset->debit - $asset->credit;
                    $_amount = '';

                    if($amount < 0)
                    {
                        $_amount = $amount * -1;
                        // return $amount;

                        $_amount = '(' . number_format($_amount, 2) . ')';
                    }
                    else{
                        $_amount = number_format($amount, 2);
                    }

                    array_push($bsreport, (object)[
                        'header' => 0,
                        'title' => $asset->code . ' ' . $asset->account,
                        'rpttype' => $bs->rpttype,
                        'mapid' => $asset->mapid,
                        'amount' => $_amount,
                        'sortid' => $bs->sortid
                    ]);

                    $subtotal += $amount;
                }

                $_subtotal = '';

                if($subtotal < 0)
                {
                    $_subtotal = $subtotal * -1;
                    // return $amount;

                    $_subtotal = '(' . number_format($_subtotal, 2) . ')';
                }
                else{
                    $_subtotal = number_format($subtotal, 2);
                }

                array_push($bsreport, (object)[
                    'header' => 0,
                    'title' => $headername . ' TOTAL: ',
                    'rpttype' => 'subtotal',
                    'mapid' => '',
                    'amount' => $_subtotal
                    // 'sortid' => $is->sortid
                ]);

                $totalassets += $subtotal;
                $_totalassets = 0;

                if($totalassets < 0)
                {
                    $_totalassets = $totalassets * -1;
                    // return $amount;

                    $_totalassets = '(' . number_format($_totalassets, 2) . ')';
                }
                else{
                    $_totalassets = number_format($totalassets, 2);
                }
                
            } 
        }

        array_push($bsreport, (object)[
            'header' => 0,
            'title' => 'TOTAL ASSETS',
            'rpttype' => 'totalassets',
            'mapid' => '',
            'amount' => $_totalassets
            // 'sortid' => $is->sortid
        ]);

        $bssetup = db::table('acc_reportsetup_bs')
            ->where('deleted', 0)
            ->where('rpttype', 'liability')
            ->orderBy('sortid')
            ->get();

        foreach($bssetup as $bs)
        {
            if($bs->header == 1)
            {
                array_push($bsreport, (object)[
                    'header' => 1,
                    'title' => $bs->description,
                    'rpttype' => $bs->rpttype,
                    'mapid' => '',
                    'amount' => '',
                    'sortid' => $bs->sortid
                ]);

                $headername = $bs->description;
            }
            else
            {
                // return $is->mapid;
                $subtotal = 0;
                $gl_assets = $ledger->where('mapid', $bs->mapid);
                $gl_assets->all();

                // return $gl_revenue;
                

                foreach($gl_assets as $asset)
                {
                    $amount = 0;
                    $_amount = '';
                    $amount = $asset->credit - $asset->debit;
                    
                    if($amount < 0)
                    {
                        $_amount = $amount * -1;
                        // return $_amount;

                        $_amount = '(' . number_format($_amount, 2) . ')';
                    }
                    else{
                        $_amount = number_format($amount, 2);
                    }

                    // if($asset->debit >= $asset->credit)
                    // {
                    //     $amount = $asset->debit - $asset->credit;
                    // }
                    // else
                    // {
                    //     $amount = $asset->credit - $asset->debit;
                    // }

                    array_push($bsreport, (object)[
                        'header' => 0,
                        'title' => $asset->code . ' ' . $asset->account,
                        'rpttype' => $bs->rpttype,
                        'mapid' => $asset->mapid,
                        'amount' => $_amount,
                        'sortid' => $bs->sortid
                    ]);

                    $subtotal += $amount;
                }

                if($subtotal > 0)
                {
                    array_push($bsreport, (object)[
                        'header' => 0,
                        'title' => $headername . ' TOTAL: ',
                        'rpttype' => 'subtotal',
                        'mapid' => '',
                        'amount' => number_format($subtotal, 2),
                        // 'sortid' => $is->sortid
                    ]);

                    $totalliabilities += $subtotal;
                }
                
            } 
        }

        $bssetup = db::table('acc_reportsetup_bs')
            ->where('deleted', 0)
            ->where('rpttype', 'equity')
            ->orderBy('sortid')
            ->get();

        $totalequity = 0;
        $totalwithdrawal = 0;
        $equityaccount = '';

        foreach($bssetup as $bs)
        {
            if($bs->header == 1)
            {
                array_push($bsreport, (object)[
                    'header' => 1,
                    'title' => $bs->description,
                    'rpttype' => $bs->rpttype,
                    'mapid' => '',
                    'amount' => '',
                    'sortid' => $bs->sortid
                ]);

                $headername = $bs->description;
            }
            else
            {
                $subtotal = 0;
                $gl_assets = $ledger->where('mapid', $bs->mapid);
                $gl_assets->all();

                // return $gl_assets;
                

                foreach($gl_assets as $asset)
                {
                    if($equityaccount == '')
                    {
                        if($asset->credit > 0)
                        {
                            $equityaccount = $asset->code . ' - ' . $asset->account;
                        }
                    }

                    $amount = 0;
                    $_amount = '';
                    
                    // return $amount;
                    // if($amount < 0)
                    // {
                    //     $_amount = $amount * -1;
                    //     // return $_amount;

                    //     // $_amount = '(' . number_format($_amount, 2) . ')';
                    // }
                    // else{
                    //     $_amount = number_format($amount, 2);
                    // }
                    
                    
                    // if($asset->debit >= $asset->credit)
                    // {
                    //     $amount = $asset->debit - $asset->credit;
                    // }
                    // else
                    // {
                    //     $amount = $asset->credit - $asset->debit;
                    // }

                    $map = db::table('acc_map')
                        ->where('id', $bs->mapid)
                        ->first();

                    if($map)
                    {
                        if(strtoupper($map->mapname) == 'WITHDRAWAL')
                        {
                            // return $map->mapname;
                            $amount = $asset->debit - $asset->credit;
                            $totalwithdrawal += $amount;
                        }
                        else{
                            $amount = $asset->credit - $asset->debit;
                            $totalequity += $amount;
                        }
                    }
                }                
            } 

            

            $isreport = collect($this->is_generate($request));
            // return $isreport;
            if($action == 'bs_print')
            {
                
                // $nettotal = $isreport[0];
                $nettotal = str_replace(',', '', $isreport[0]);
                // return $nettotal;
            }
            else{
                $nettotal = $isreport->where('rpttype', 'nettotal')->first();
                $nettotal = str_replace(',', '', $nettotal->amount);
            }
            
            // return $nettotal;
            
            $amount = $totalequity - $totalwithdrawal;
            $amount += floatval($nettotal);

            if($amount < 0)
            {
                $_amount = $amount * -1;
                $_amount = '(' . number_format($_amount, 2) . ')';
            }
            else{
                $_amount = number_format($amount, 2);
            }
        }

        // return $totalequity .' - ' . $totalwithdrawal;

        array_push($bsreport, (object)[
            'header' => 0,
            'title' => $equityaccount,
            'rpttype' => $bs->rpttype,
            // 'mapid' => $asset->mapid,
            'amount' => $_amount,
            'sortid' => $bs->sortid
        ]);

        array_push($bsreport, (object)[
            'header' => 0,
            'title' => 'TOTAL EQUITY AND LIABILITY',
            'rpttype' => 'eqliability',
            'mapid' => '',
            'amount' => number_format($amount + $totalliabilities, 2)
            // 'sortid' => $bs->sortid
        ]);

        if($action == 'bs_print')
        {
            $pdf = PDF::loadview('finance.accounting.reports.pdf_balancesheet', compact('bsreport', 'datefrom', 'dateto'));
		    return $pdf->stream('BalanceSheet.pdf');
        }
        else{
            return $bsreport;
        }

        
    }

    public function gl_generate(Request $request)
    {
        $range = explode(' - ', $request->get('range'));
        $filter = $request->get('filter');
        $datefrom = date_format(date_create($range[0]), 'Y-m-d 00:00');
        $dateto = date_format(date_create($range[1]), 'Y-m-d 23:59');
        $action = $request->get('action');

        // return $filter;

        $gl = db::table('acc_je')
            ->select(db::raw('code, account, SUM(debit) AS debit, SUM(credit) AS credit, classification, mapname'))
            ->join('acc_jedetails', 'acc_je.id', '=', 'acc_jedetails.headerid')
            ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
            ->join('acc_coagroup', 'acc_coa.gid', '=', 'acc_coagroup.id')
            ->leftjoin('acc_map','acc_coa.mapid','=','acc_map.id')
            ->join('acc_coaclass', 'acc_coagroup.coaclass', '=', 'acc_coaclass.id')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('acc_jedetails.deleted', 0)
            ->where('acc_je.deleted', 0)
            ->where(function($q) use($filter){
                if($filter != 'ALL')
                {
                    $q->where('transtype', $filter);
                }
            })
            ->groupBy('accid')
            ->orderBy('code')
            ->get();

        // return $gl;

        if($action == 'print')
        {
            $pdf = PDF::loadview('finance.accounting.reports.pdf_generalledger', compact('gl', 'datefrom', 'dateto', 'filter'));
            return $pdf->stream('General Ledger.pdf');
        }
        else{
            return $gl;
        }
    }

    public function tb_generate(Request $request)
    {
        $action = $request->get('action');
        // $filter = $request->get('filter');
        $range = explode(' - ', $request->get('range'));
        $datefrom = date_format(date_create($range[0]), 'Y-m-d 00:00');
        $dateto = date_format(date_create($range[1]), 'Y-m-d 23:59');

        $debittotal = 0;
        $credittotal = 0;
        $balancetotal = 0;
        $tbarray = [];

        $ledger = db::table('acc_je')
            ->select(db::raw('code, account, SUM(debit) AS debit, SUM(credit) AS credit, `group`, classification, normalbalance as nb'))
            ->join('acc_jedetails', 'acc_je.id', '=', 'acc_jedetails.headerid')
            ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
            ->join('acc_coagroup', 'acc_coa.gid', '=', 'acc_coagroup.id')
            ->join('acc_coaclass', 'acc_coagroup.coaclass', '=', 'acc_coaclass.id')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('acc_je.deleted', 0)
            ->groupBy('accid')
            ->get();

        foreach($ledger as $tb)
        {
            $total = $tb->debit - $tb->credit;
            $debittotal += $tb->debit;
            $credittotal += $tb->credit;
            $balancetotal += $total;

            $strtotal = '';

            if($total < 0)
            {
                $total = $total * -1;
                $strtotal = "(" . number_format($total, 2) . ")";
            }
            else{
                $strtotal = number_format($total, 2);
            }

            array_push($tbarray, (object)[
                'code' => $tb->code,
                'account' => $tb->account,
                'debit' => ($tb->debit == null)?'0.00':number_format($tb->debit, 2),
                'credit' => ($tb->credit == null)? '0.00':number_format($tb->credit, 2),
                'balance' => $strtotal
            ]);

            // if($tb->nb == 'DEBIT')
            // {
                
            //     $debittotal += $total;
            // }
            // else{
            //     $total = $tb->credit - $tb->debit;   
            //     $credittotal += $total;
            // }

            // $strtotal = '';

            // if($total < 0)
            // {
            //     $total = $total * -1;
            //     $strtotal = "(" . number_format($total, 2) . ")";
            // }
            // else{
            //     $strtotal = number_format($total, 2);
            // }

            // if($tb->nb == 'DEBIT')
            // {
            //     array_push($tbarray, (object)[
            //         'code' => $tb->code,
            //         'account' => $tb->account,
            //         'debit' => $strtotal,
            //         'credit' => ''
            //     ]);
            // }
            // else{
            //     array_push($tbarray, (object)[
            //         'code' => $tb->code,
            //         'account' => $tb->account,
            //         'debit' => '',
            //         'credit' => $strtotal
            //     ]);   
            // }
        }

        $data = array(
            'tbarray' => $tbarray,
            'debittotal' => number_format($debittotal, 2),
            'credittotal' => number_format($credittotal, 2),
            'balancetotal' => number_format($balancetotal, 2)
        );

        if($action == 'print')
        {
            // return $data;
            // return view('finance.accounting.reports.pdf_trialbalance', compact('data', 'datefrom', 'dateto'));
            $pdf = PDF::loadview('finance.accounting.reports.pdf_trialbalance', compact('data', 'datefrom', 'dateto'));
            return $pdf->stream('Trial Balance.pdf');
        }
        else{
            return $data;
        }

        


    }

    public function je_autogenerate(Request $request)
    {
        $range = explode(' - ', $request->get('range'));
        $datefrom = date_format(date_create($range[0]), 'Y-m-d 00:00');
        $dateto = date_format(date_create($range[1]), 'Y-m-d 23:59');
        $syid = $request->get('syid');
        $oldclassid = db::table('balforwardsetup')
            ->first()->classid;

        $level = db::table('gradelevel')
            ->where('deleted', 0)
            ->orderBy('sortid')
            ->get();

        $levelarray = collect($level);

        // return $levelarray;

        $autoje = db::table('acc_je')
            ->where('autogen', 1)
            ->whereBetween('createddatetime', [$datefrom, $dateto])
            ->where('deleted', 0)
            ->get();

        $jearray = [];

        foreach($autoje as $je)
        {
            array_push($jearray, $je->id);
        }

        db::table('acc_je')
            ->where('autogen', 1)
            ->whereBetween('createddatetime', [$datefrom, $dateto])
            ->where('deleted', 0)
            ->update([
                'deleted' => 1,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);

            db::table('acc_jedetails')
                ->whereIn('headerid', $jearray)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => FinanceModel::getServerDateTime()
                ]);

        // return 'aaa';

        //AR

        $pre = db::table('enrolledstud')
            ->select(db::raw('enrolledstud.studid, SUM(amount) AS amount, levelid, dateenrolled as createddatetime, gl_1, gl_2, gl_3, gl_credit, levelname'))
            ->join('studledger', 'enrolledstud.studid', '=', 'studledger.studid')
            ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            // ->whereIn('levelid', [3,4])
            ->where('enrolledstud.deleted', 0)
            ->where('enrolledstud.syid', $syid)
            ->whereBetween('dateenrolled', [$datefrom, $dateto])
            ->where('studledger.syid', $syid)
            ->where('studledger.deleted', 0)
            ->where('particulars', 'not like', '%ADJ:%')
            ->where('particulars', 'not like', '%DISCOUNT:%')
            ->groupBy('enrolledstud.studid')
            ->get();

        foreach($pre as $e)
        {
            $jeid = db::table('acc_je')
                ->insertGetId([
                    'transdate' => $e->createddatetime,
                    'jestatus' => 'Posted',
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime,
                    'transtype' => 'SALES',
                    'remarks' =>"ENROLLMENT " . $e->levelname,
                    'autogen' => 1
                ]);

            $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);

            db::table('acc_je')
                ->where('id', $jeid)
                ->update([
                    'refid' => $refnum
                ]);

            db::table('acc_jedetails')
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $e->gl_1,
                    'debit' => $e->amount,
                    'credit' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime
                ]);

            db::table('acc_jedetails')
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $e->gl_credit,
                    'debit' => 0,
                    'credit' => $e->amount,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime
                ]);

            
        }


        $elem = db::table('enrolledstud')
            ->select(db::raw('enrolledstud.studid, SUM(amount) AS amount, levelid, dateenrolled as createddatetime, gl_1, gl_2, gl_3, gl_credit, levelname'))
            ->join('studledger', 'enrolledstud.studid', '=', 'studledger.studid')
            ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            ->whereIn('levelid', [1,5,6,7,16,9])
            ->where('enrolledstud.deleted', 0)
            ->where('enrolledstud.syid', $syid)
            ->whereBetween('dateenrolled', [$datefrom, $dateto])
            ->where('studledger.classid', '!=', $oldclassid)
            ->where('studledger.syid', $syid)
            ->where('studledger.deleted', 0)
            ->where('particulars', 'not like', '%ADJ:%')
            ->where('particulars', 'not like', '%DISCOUNT:%')
            ->groupBy('enrolledstud.studid')
            ->get();

        foreach($elem as $e)
        {
            $jeid = db::table('acc_je')
                ->insertGetId([
                    'transdate' => $e->createddatetime,
                    'jestatus' => 'Posted',
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime,
                    'transtype' => 'SALES',
                    'remarks' =>"ENROLLMENT " . $e->levelname,
                    'autogen' => 1
                ]);

            $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);

            db::table('acc_je')
                ->where('id', $jeid)
                ->update([
                    'refid' => $refnum
                ]);

            db::table('acc_jedetails')
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $e->gl_1,
                    'debit' => $e->amount,
                    'credit' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime
                ]);

            db::table('acc_jedetails')
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $e->gl_credit,
                    'debit' => 0,
                    'credit' => $e->amount,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime
                ]);

            
        }

        $hs = db::table('enrolledstud')
            ->select(db::raw('enrolledstud.studid, SUM(amount) AS amount, levelid, dateenrolled as createddatetime, gl_1, gl_2, gl_3, gl_credit, levelname'))
            ->join('studledger', 'enrolledstud.studid', '=', 'studledger.studid')
            ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            ->whereBetween('levelid', [10, 13])
            ->where('enrolledstud.deleted', 0)
            ->where('enrolledstud.syid', $syid)
            ->where('gradelevel.deleted', 0)
            ->whereBetween('dateenrolled', [$datefrom, $dateto])
            ->where('studledger.syid', $syid)
            ->where('studledger.deleted', 0)
            ->where('particulars', 'not like', '%ADJ:%')
            ->where('particulars', 'not like', '%DISCOUNT:%')
            ->groupBy('enrolledstud.studid')
            ->get();

        foreach($hs as $e)
        {
            $jeid = db::table('acc_je')
                ->insertGetId([
                    'transdate' => $e->createddatetime,
                    'jestatus' => 'Posted',
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime,
                    'transtype' => 'SALES',
                    'remarks' =>"ENROLLMENT " . $e->levelname,
                    'autogen' => 1
                ]);

            $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);

            db::table('acc_je')
                ->where('id', $jeid)
                ->update([
                    'refid' => $refnum
                ]);

            db::table('acc_jedetails')
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $e->gl_1,
                    'debit' => $e->amount,
                    'credit' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime
                ]);

            db::table('acc_jedetails')
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $e->gl_credit,
                    'debit' => 0,
                    'credit' => $e->amount,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime
                ]);  

            echo 'jeid: ' . $jeid . ' amount: ' . $e->amount;
        }

        $hs = db::table('sh_enrolledstud')
            ->select(db::raw('sh_enrolledstud.studid, SUM(amount) AS amount, levelid, dateenrolled as createddatetime, gl_1, gl_2, gl_3, gl_credit, levelname'))
            ->join('studledger', 'sh_enrolledstud.studid', '=', 'studledger.studid')
            ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
            ->whereBetween('levelid', [14, 15])
            ->where('sh_enrolledstud.deleted', 0)
            ->where('sh_enrolledstud.syid', $syid)
            ->whereBetween('dateenrolled', [$datefrom, $dateto])
            ->where('studledger.syid', $syid)
            ->where('studledger.deleted', 0)
            ->where('particulars', 'not like', '%ADJ:%')
            ->where('particulars', 'not like', '%DISCOUNT:%')
            ->groupBy('sh_enrolledstud.studid')
            ->get();

        foreach($hs as $e)
        {
            $jeid = db::table('acc_je')
                ->insertGetId([
                    'transdate' => $e->createddatetime,
                    'jestatus' => 'Posted',
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime,
                    'transtype' => 'SALES',
                    'remarks' =>"ENROLLMENT " . $e->levelname,
                    'autogen' => 1
                ]);

            $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);

            db::table('acc_je')
                ->where('id', $jeid)
                ->update([
                    'refid' => $refnum
                ]);

            db::table('acc_jedetails')
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $e->gl_1,
                    'debit' => $e->amount,
                    'credit' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime
                ]);

            db::table('acc_jedetails')
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $e->gl_credit,
                    'debit' => 0,
                    'credit' => $e->amount,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime
                ]);  
        }

        $col = db::table('college_enrolledstud')
            ->select(db::raw('college_enrolledstud.studid, SUM(amount) AS amount, yearLevel as levelid, date_enrolled as createddatetime, gl_1, gl_2, gl_3, gl_credit, college_enrolledstud.semid, levelname'))
            ->join('studledger', 'college_enrolledstud.studid', '=', 'studledger.studid')
            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            ->whereBetween('yearLevel', [17, 21])
            ->where('college_enrolledstud.deleted', 0)
            ->where('college_enrolledstud.syid', $syid)
            ->whereBetween('date_enrolled', [$datefrom, $dateto])
            ->where('studledger.syid', $syid)
            ->where('studledger.deleted', 0)
            ->where('particulars', 'not like', '%ADJ:%')
            ->where('particulars', 'not like', '%DISCOUNT:%')
            ->groupBy('college_enrolledstud.studid')
            ->get();

        foreach($col as $e)
        {
            $jeid = db::table('acc_je')
                ->insertGetId([
                    'transdate' => $e->createddatetime,
                    'jestatus' => 'Posted',
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime,
                    'transtype' => 'SALES',
                    'remarks' =>"ENROLLMENT " . $e->levelname,
                    'autogen' => 1
                ]);

            $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);

            db::table('acc_je')
                ->where('id', $jeid)
                ->update([
                    'refid' => $refnum
                ]);

            $col_gl = 0;

            if($e->semid == 1)
            {
                $col_gl = $e->gl_1;
            }
            elseif($e->semid == 2)
            {
                $col_gl = $e->gl_2;
            }
            else{
                $col_gl = $e->gl_3;
            }

            db::table('acc_jedetails')
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $col_gl,
                    'debit' => $e->amount,
                    'credit' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime
                ]);

            db::table('acc_jedetails')
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $e->gl_credit,
                    'debit' => 0,
                    'credit' => $e->amount,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $e->createddatetime
                ]);  
        }

        //AR

        //Payment

        //AR
        $cashtrans = db::table('chrngtrans')
            ->select(db::raw('chrngtrans.`studid`, ornum, kind, SUM(amount) AS amount, gl_1, gl_2, gl_3, gradelevel.`id` as levelid, itemid, classid, transdate, chrngtrans.syid, chrngtrans.semid'))
            ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            ->join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('cancelled', 0)
            ->where('chrngcashtrans.deleted', 0)
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('kind', '!=', 'item')
            ->where('kind', '!=', 'old')
            // ->where('ornum', 544551)
            ->groupBy('ornum')
            ->get();

        $transsetup = db::table('acc_transsetup')
            ->where('transname', 'cashier')
            ->where('deleted', 0)
            ->first();

        $gl_debit = 0;

        if($transsetup)
        {
            $gl_debit = $transsetup->glid;
        }

        foreach($cashtrans as $trans)
        {
            $glid = 0;
            if($trans->levelid >= 17 && $trans->levelid <= 21)
            {
                if($trans->semid == 1)
                {
                    $glid = $trans->gl_1;
                }
                elseif($trans->semid == 2)
                {
                    $glid = $trans->gl_2;
                }
                else{
                    $glid = $trans->gl_3;
                }
            }
            else{
                $glid = $trans->gl_1;
            }

            $jeid = db::table('acc_je')
                ->insertGetId([
                    'transdate' => $trans->transdate,
                    'jestatus' => 'Posted',
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $trans->transdate,
                    'transtype' => 'CR',
                    'remarks' => 'OR number: ' . $trans->ornum,
                    'autogen' => 1
                ]);

            $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);

            db::table('acc_je')
                ->where('id', $jeid)
                ->update([
                    'refid' => $refnum
                ]);

            db::table('acc_jedetails') //DEBIT
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $gl_debit,
                    'debit' => $trans->amount,
                    'credit' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $trans->transdate
                ]);

            db::table('acc_jedetails') //CREDIT
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $glid,
                    'debit' => 0,
                    'credit' => $trans->amount,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $trans->transdate,
                ]);  

            // if($trans->ornum == '544551')
            // {
            //     return $trans->amount;
            // }

        }
        //AR

        //OLDACCOUNTS

        $cashtrans = db::table('chrngtrans')
            ->select(db::raw('chrngtrans.`studid`, ornum, kind, SUM(amount) AS amount, transdate, chrngtrans.syid, chrngtrans.semid'))
            ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            ->join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('cancelled', 0)
            ->where('chrngcashtrans.deleted', 0)
            ->whereBetween('transdate', [$datefrom, $dateto])
            // ->where('kind', '!=', 'item')
            ->where('kind', 'old')
            ->groupBy('ornum')
            ->get();

        $transold = db::table('balforwardsetup')
            ->first()->glid;

        foreach($cashtrans as $trans)
        {
            $jeid = db::table('acc_je')
                ->insertGetId([
                    'transdate' => $trans->transdate,
                    'jestatus' => 'Posted',
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $trans->transdate,
                    'transtype' => 'CR',
                    'remarks' => 'OR number: ' . $trans->ornum,
                    'autogen' => 1
                ]);

            $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);

            db::table('acc_je')
                ->where('id', $jeid)
                ->update([
                    'refid' => $refnum
                ]);

            db::table('acc_jedetails') //DEBIT
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $gl_debit,
                    'debit' => $trans->amount,
                    'credit' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $trans->transdate
                ]);

            db::table('acc_jedetails') //CREDIT
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $transold,
                    'debit' => 0,
                    'credit' => $trans->amount,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $trans->transdate,
                ]);  
        }

        //OLDACCOUNTS

        // //items
        $cashtrans = db::table('chrngtrans')
            ->select(db::raw('glid, chrngcashtrans.amount, ornum, chrngcashtrans.transno, payscheddetailid AS itemdid, code, account, chrngcashtrans.`particulars`, 
                chrngtrans.`syid`, chrngtrans.`semid`'))
            ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            ->join('items', 'chrngcashtrans.payscheddetailid', '=', 'items.id')
            ->join('acc_coa', 'items.glid', '=', 'acc_coa.id')
            ->where('cancelled', 0)
            ->where('chrngcashtrans.deleted', 0)
            ->whereBetween('transdate', [$datefrom, $dateto])
            // ->where('kind', '!=', 'item')
            ->where('kind', 'item')
            ->get();

        $_trans = collect($cashtrans);

        $chrngtrans = db::table('chrngtrans')
            ->select(db::raw('sum(amount) as amount, ornum, transdate, chrngtrans.transno'))
            ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            ->where('cancelled', 0)
            ->where('deleted', 0)
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('kind', 'item')
            ->groupBy('transno')
            ->get();

        // return $chrngtrans;

        foreach($chrngtrans as $trans)
        {
            $jeid = db::table('acc_je')
                ->insertGetId([
                    'transdate' => $trans->transdate,
                    'jestatus' => 'Posted',
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $trans->transdate,
                    'transtype' => 'CR',
                    'remarks' => 'OR number: ' .$trans->ornum,
                    'autogen' => 1
                ]);

            $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);

            db::table('acc_je')
                ->where('id', $jeid)
                ->update([
                    'refid' => $refnum
                ]);
            
            $transitems = $_trans->where('transno', $trans->transno);
            $transitems->all();

            // return $trans->transno;

            $i_total = 0;
            $credit_array = [];

            foreach($transitems as $i)
            {
                array_push($credit_array, (object)[
                    'glid' => $i->glid,
                    'amount' => $i->amount
                ]);

                $i_total += $i->amount;
            }


            db::table('acc_jedetails') //DEBIT
                ->insert([
                    'headerid' => $jeid,
                    'accid' => $gl_debit,
                    'debit' => $i_total,
                    'credit' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $trans->transdate
                ]);

            // $transactions = $_trans->where('ornum', $trans->ornum);
            // $transactions->all();

            // return $credit_array;

            foreach($credit_array as $trx)
            {
                db::table('acc_jedetails') //CREDIT
                    ->insert([
                        'headerid' => $jeid,
                        'accid' => $trx->glid,
                        'debit' => 0,
                        'credit' => $trx->amount,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => $trans->transdate
                    ]);
            }

            

            
        }


        // //items

        //Payment

        //Adjustment - Debit

        //Adjustment - Debit

        //Adjustment - Credit

        //Adjustment - Credit

        //Discount

        //Discount

    }

    public function postjepayroll(Request $request)
    {
        $amount = $request->get('amount');
        $transdate = $request->get('transdate');
        $payrollid = $request->get('payrollid');

        $payrollsetup = db::table('acc_transsetup')
            ->where('transname', 'payroll')
            ->first();

        $debitgl = $payrollsetup->debitgl;
        $creditgl = $payrollsetup->creditgl;

        $jeid = db::table('acc_je')
            ->insertGetId([
                'transdate' => $transdate,
                'jestatus' => 'Posted',
                'transtype' => 'PAYROLL',
                'transid' => $payrollid,
                // 'createdby' => auth()->user()->id,
                'createddatetime' => FinanceModel::getServerDateTime()
            ]);

        $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);

        db::table('acc_je')
            ->where('id', $jeid)
            ->update([
                'refid' => $refnum
            ]);

        
    
        db::table('acc_jedetails')                  
            ->insert([
                'headerid' => $jeid,
                'accid' => $debitgl,
                'debit' => $amount,
                'credit' => 0,
                'createddatetime' => FinanceModel::getServerDateTime()
            ]);

        db::table('acc_jedetails')                  
            ->insert([
                'headerid' => $jeid,
                'accid' => $creditgl,
                'debit' => 0,
                'credit' => $amount,
                'createddatetime' => FinanceModel::getServerDateTime()
            ]);
        

        return 'done';


    }

    public function sl_generate(Request $request)
    {
        $range = explode(' - ', $request->get('range'));
        $filter = $request->get('filter');
        $datefrom = date_format(date_create($range[0]), 'Y-m-d 00:00');
        $dateto = date_format(date_create($range[1]), 'Y-m-d 23:59');
        $action = $request->get('action');

        // return $filter;

        $sl = db::table('acc_je')
            ->select(db::raw('acc_je.createddatetime, `code`, account, debit, credit, mapname, acc_je.`jestatus`'))
            ->join('acc_jedetails', 'acc_je.id', '=', 'acc_jedetails.headerid')
            ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
            ->leftjoin('acc_map','acc_coa.mapid','=','acc_map.id')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('acc_jedetails.deleted', 0)
            ->where('acc_je.deleted', 0)
            ->where(function($q) use($filter){
                if($filter != 0)
                {
                    $q->where('acc_coa.id', $filter);
                }
            })
            ->orderBy('createddatetime')
            ->get();

        // return $gl;

        if($action == 'print')
        {
            $pdf = PDF::loadview('finance.accounting.reports.pdf_sl', compact('sl', 'datefrom', 'dateto', 'filter'));
            return $pdf->stream('Subsidiary Ledger.pdf');
        }
        else{
            return array (
                'sl' => $sl,
                'filters' => $this->sl_filter($range)
            );
        }
    }

    public static function sl_filter($range)
	{  
        // $range = explode(' - ', $range);
        $datefrom = date_format(date_create($range[0]), 'Y-m-d 00:00');
        $dateto = date_format(date_create($range[1]), 'Y-m-d 23:59');

		return db::table('acc_je')
            ->select(db::raw('acc_coa.id, code, account'))
            ->join('acc_jedetails', 'acc_je.id', '=', 'acc_jedetails.headerid')
            ->join('acc_coa', 'acc_jedetails.accid', '=', 'acc_coa.id')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('acc_jedetails.deleted', 0)
            ->where('acc_je.deleted', 0)
            ->groupBy('acc_coa.id')
            ->orderBy('acc_coa.code')
            ->get();
	}
}
