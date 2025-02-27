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

class DisbursementController extends Controller
{
    public function disbursement()
    {
        return view('finance.disbursement.disbursement');
    }

    public function disbursement_loadsupplier()
    {
        $supplier = db::table('expense_company')
            ->select('id', 'companyname as text')
            ->where('deleted', 0)
            ->get();

        return $supplier;
    }

    public function disbursement_loadrr(Request $request)
    {
        $supplierid = $request->get('supplierid');
        $disbursementid = $request->get('disbursementid');
        // $ddid = $request->get('ddid');
        $rr_array = array();
        $paidamount = 0;
        $balance = 0;
        $ddid = 0;
        $payment = 0;
        $headerid = 0;
        $transdate = '';

        $receiving = db::table('receiving')
            ->select(db::raw('receiving.id, receiving.refnum, invoiceno, invoicedate, supplierid, SUM(rtotal) AS totalamount, purchasing.ptype'))
            ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
            ->join('receiving_details', 'receiving.id', '=', 'receiving_details.headerid')
            ->where('supplierid', $supplierid)
            ->where('receiving.deleted', 0)
            ->where('receiving_details.deleted', 0)
            ->groupBy('invoiceno')
            ->get();

        foreach($receiving as $rr)
        {
            $dis = db::table('disbursement')
                ->where('id', $disbursementid)
                ->first();

            if(!$dis)
            {
                goto details;
            }
            
            if($dis->trxstatus == 'POSTED')
            {
                $details = db::table('disbursement_details')
                    ->select(db::raw('disbursement.id as id, headerid, rrid, SUM(payment) AS payment, trxstatus'))
                    ->join('disbursement', 'disbursement_details.headerid', 'disbursement.id')
                    ->where('rrid', $rr->id)
                    ->where('disbursement_details.deleted', 0)
                    ->where('headerid', $disbursementid)
                    ->first();
            }
            else{
                details:
                $details = db::table('disbursement_details')
                    ->select(db::raw('disbursement.id as id, headerid, rrid, SUM(payment) AS payment, trxstatus'))
                    ->join('disbursement', 'disbursement_details.headerid', 'disbursement.id')
                    ->where('rrid', $rr->id)
                    ->where('disbursement_details.deleted', 0)
                    // ->where('trxstatus', 'POSTED')
                    ->first();
            }

            if($details)
            {
                // if($details->trxstatus == 'POSTED')
                // {
                //     $paidamount = $details->payment;    
                // }
                
                $headerid = $details->headerid;
                $ddid = $details->id;

                if($ddid > 0)
                {
                    $detail = db::table('disbursement_details')
                        // ->where('id', $ddid)
                        ->where('rrid', $rr->id)
                        ->where('headerid', $disbursementid)
                        ->where('deleted', 0)
                        ->first();

                    if($detail)
                    {
                        $payment = $detail->payment;
                    }
                    else{
                        $payment = 0;
                    }
                }
                else{
                    $payment = 0;
                }
            }
            else{
                $paidamount = 0;
                $headerid = 0;
                $ddid = 0; 
                $payment = 0;
            }

            $calcrr = db::table('disbursement')
                ->select(db::raw('headerid, rrid, SUM(payment) AS payment'))
                ->join('disbursement_details' ,'disbursement.id', '=', 'disbursement_details.headerid')
                ->where('disbursement.trxstatus', 'POSTED')
                ->where('rrid', $rr->id)
                ->where('disbursement_details.deleted', 0)
                ->first();

            if($calcrr)
            {
                $paidamount = $calcrr->payment;
            }
            else{
                $paidamount = 0;
            }

            $balance = $rr->totalamount - $paidamount;

            if(!$dis)
            {
                goto a_push;
            }
            if($dis->trxstatus == 'POSTED')
            {
                if($headerid != null)
                {
                    array_push($rr_array, (object)[
                        'id' => $rr->id,
                        'ddid' => $ddid,
                        'headerid' => $headerid,
                        'refnum' => $rr->refnum,
                        'invoice' => $rr->invoiceno,
                        'rrdate' => date_format(date_create($rr->invoicedate), 'm/d/Y'),
                        'amount' => number_format($rr->totalamount, 2),
                        'paidamount' => number_format($paidamount, 2),
                        'balance' => number_format($balance, 2),
                        'payment' => number_format($payment, 2),
                        'paytype' => $rr->ptype
                    ]);
                }
            }
            else{

                a_push:
                array_push($rr_array, (object)[
                    'id' => $rr->id,
                    'ddid' => $ddid,
                    'headerid' => $headerid,
                    'refnum' => $rr->refnum,
                    'invoice' => $rr->invoiceno,
                    'rrdate' => date_format(date_create($rr->invoicedate), 'm/d/Y'),
                    'amount' => number_format($rr->totalamount, 2),
                    'paidamount' => number_format($paidamount, 2),
                    'balance' => number_format($balance, 2),
                    'payment' => number_format($payment, 2),
                    'paytype' => $rr->ptype
                ]);
            }
        }

        return $rr_array;
    }
    
    public function disbursement_save(Request $request)
    {
        $id = $request->get('dataid');
        $supplierid = $request->get('supplierid');
        $date = $request->get('date');
        $bankid = $request->get('bankid');
        $checkno = $request->get('checkno');
        $checkdate = $request->get('checkdate');
        $remarks = $request->get('remarks');
        $paytype = $request->get('paytype');
        $rr_array = $request->get('rr_array');
        $jearray = $request->get('jearray');
        $totalamount = 0;
        $refnum = 'Reference Number';

        // return $rr_array;

        if($id == 0)
        {
            $did = db::table('disbursement')
                ->insertGetId([
                    'supplierid' => $supplierid,
                    'paytype' => $paytype,
                    'bankid' => $bankid,
                    'checkno' => $checkno,
                    'checkdate' => $checkdate,
                    'transdate' => date_format(date_create($date), 'Y-m-d'),
                    'remarks' => $remarks,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);


            foreach($rr_array as $rr)
            {
                $rrid = $rr['rrid'];
                $detailid = $rr['dataid'];
                $rramount = str_replace(',', '', $rr['rramount']);

                if($detailid == 0)
                {
                    if($rramount > 0)
                    {
                        db::table('disbursement_details')
                            ->insert([
                                'headerid' => $did,
                                'rrid' => $rrid,
                                'payment' => $rramount,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);

                        $totalamount += $rramount;
                    }
                }
                else{
                    if($rramount > 0)
                    {
                        // return 'aaa';
                        // db::table('disbursement_details')
                        //     ->where('id', $detailid)
                        //     ->update([
                        //         'headerid' => $did,
                        //         'rrid' => $rrid,
                        //         'payment' => $rramount,
                        //         'updatedby' => auth()->user()->id,
                        //         'updateddatetime' => FinanceModel::getServerDateTime()
                        //     ]);

                        db::table('disbursement_details')
                            ->insert([
                                'headerid' => $did,
                                'rrid' => $rrid,
                                'payment' => $rramount,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);

                        $totalamount += $rramount;
                    }
                }    
            }

            foreach($jearray as $je)
            {
                if($je['djeid'] == 0)
                {
                    db::table('disbursement_jedetails')
                        ->insert([
                            'headerid' => $did,
                            'glid' => $je['glid'],
                            'debit' => str_replace(',', '', $je['debit']),
                            'credit' => str_replace(',', '', $je['credit']),
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }
                else{

                }

            }

            $refnum =  'DSMT-' . sprintf('%07d', $did);

            db::table('disbursement')
                ->where('id', $did)
                ->update([
                    'refnum' => $refnum,
                    'amount' => $totalamount
                ]);

            $refnum = db::table('disbursement')
                ->where('id', $did)
                ->first()->refnum;

            $id = $did;
        }
        else{
            $disbursement = db::table('disbursement')
                ->where('id', $id)
                ->update([
                    'paytype' => $paytype,
                    'checkno' => $checkno,
                    'bankid' => $bankid,
                    'checkdate' => $checkdate,
                    'transdate' => date_format(date_create($date), 'Y-m-d'),
                    'remarks' => $remarks,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => FinanceModel::getServerDateTime()
                ]);

            // return $rr_array;
            $refnum = db::table('disbursement')
                ->where('id', $id)
                ->first()->refnum;


            foreach($rr_array as $rr)
            {
                $rrid = $rr['rrid'];
                $detailid = $rr['dataid'];
                $rramount = str_replace(',', '', $rr['rramount']);


                if($detailid == 0)
                {
                    if($rramount > 0)
                    {
                        db::table('disbursement_details')
                            ->insert([
                                'headerid' => $id,
                                'rrid' => $rrid,
                                'payment' => $rramount,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);

                        $totalamount += $rramount;
                    }
                }
                else{
                    if($rramount > 0)
                    {
                        $_d = db::table('disbursement_details')
                            ->where('rrid', $rrid)
                            ->where('headerid', $id)
                            ->where('deleted', 0)
                            ->first();

                        if($_d)
                        {
                            db::table('disbursement_details')
                                ->where('id', $_d->id)
                                ->update([
                                    'payment' => $rramount,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                ]);    
                        }
                        else{
                            db::table('disbursement_details')
                                ->insert([
                                    'headerid' => $id,
                                    'rrid' => $rrid,
                                    'payment' => $rramount,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => FinanceModel::getServerDateTime()
                                ]);
                        }

                        
                    }
                }
            }

            // return $jearray;
            if($jearray)
            {
                foreach($jearray as $je)
                {
                    if($je['djeid'] == 0)
                    {
                        db::table('disbursement_jedetails')
                            ->insert([
                                'headerid' => $id,
                                'glid' => $je['glid'],
                                'debit' => str_replace(',', '', $je['debit']),
                                'credit' => str_replace(',', '', $je['credit']),
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);
                    }
                    else{
                        db::table('disbursement_jedetails') 
                            ->where('id', $je['djeid'])
                            ->update([
                                'glid' => $je['glid'],
                                'debit' => str_replace(',', '', $je['debit']),
                                'credit' => str_replace(',', '', $je['credit']),
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => FinanceModel::getServerDateTime()
                            ]);
                    }
                }
            }


        }

        $data = array(
            'refnum' => $refnum,
            'dataid' => $id
        );

        return $data;
    }

    public function disbursement_load(Request $request)
    {
        $d_status = $request->get('d_status');
        $datefrom = $request->get('datefrom');
        $dateto = $request->get('dateto');
        $search = $request->get('search');
        $filter = $request->get('filter');

        $disbursement = db::table('disbursement')
            ->select(db::raw('disbursement.`id`, transdate,  refnum, companyname, paytype, amount, trxstatus, remarks'))
            ->join('expense_company', 'disbursement.supplierid', '=', 'expense_company.id')
            ->where('disbursement.deleted', 0)
            ->where(function($q) use($search){
                if($search != null)
                {
                    $q->where('refnum', 'like', '%'.$search.'%');
                }
            })
            ->where(function($q) use($filter){
                if($filter != 'all')
                {
                    $q->where('trxstatus', $filter);
                }
            })
            ->get();

        return $disbursement;
    }

    public function disbursement_read(Request $request)
    {
        $dataid = $request->get('dataid');
        $action = $request->get('action');

        $disbursement = db::table('disbursement')
            ->where('id', $dataid)
            ->first();

        $data = array(
            'id' => $disbursement->id,
            'refnum' => $disbursement->refnum,
            'supplierid' => $disbursement->supplierid,
            'paytype' => $disbursement->paytype,
            'bankid' => $disbursement->bankid,
            'checkno' => $disbursement->checkno,
            'checkdate' => $disbursement->checkdate,
            'transdate' => date_format(date_create($disbursement->transdate), 'Y-m-d'),
            'amount' => $disbursement->amount,
            'remarks' => $disbursement->remarks,
            'trxstatus' => $disbursement->trxstatus
        );
        
        if($action != 'print')
        {
            return $data;
        }
        else{
            $supplier = db::table('expense_company')->where('id', $disbursement->supplierid)->first();
            
            $detail = db::table('disbursement')
                ->select(db::raw('headerid, rrid, SUM(payment) AS payment, receiving.refnum'))
                ->join('disbursement_details', 'disbursement.id', 'disbursement_details.headerid')
                ->join('receiving', 'disbursement_details.rrid', '=', 'receiving.id')
                ->where('disbursement_details.deleted', 0)
                ->where('headerid', $disbursement->id)
                ->groupBy('rrid')
                ->get();

            $jedetails = db::table('disbursement_jedetails')
                ->select(db::raw('code, account, debit, credit'))
                ->join('acc_coa', 'disbursement_jedetails.glid', '=', 'acc_coa.id')
                ->where('headerid', $disbursement->id)
                ->where('disbursement_jedetails.deleted', 0)
                ->get();

            $payment = 0;

            foreach($detail as $d)
            {
                $payment += $d->payment;
            }

            $bankname = '';

            $bank = db::table('acc_bank')
                ->where('id', $disbursement->bankid)
                ->first();

            if($bank)
            {
                $bankname = $bank->accountno . ' - ' . $bank->bankname;
            }

            $paytitle = '';

            if($disbursement->paytype == 'CASH')
            {
                $paytitle = 'CASH';
            }
            else{
                $paytitle = 'CHECK';
            }

            // return $detail;

            // return $action;
            // return view('finance.purchasing.reports.pdf_po', compact('purchasing', 'data'));
            $pdf = PDF::loadview('finance.disbursement.reports.pdf_voucher', compact('disbursement', 'data', 'supplier', 'payment', 'detail', 'jedetails', 'bankname', 'paytitle'));
            return $pdf->stream('Cash/Check Voucher.pdf'); 
        }
    }

    public function disbursement_loadje(Request $request)
    {
        $headerid = $request->get('headerid');

        $d_je = db::table('disbursement_jedetails')
            ->where('headerid', $headerid)
            ->where('deleted', 0)
            ->get();

        return $d_je;
    }

    public function disbursement_post(Request $request)
    {
        $id = $request->get('id');

        db::table('disbursement')
            ->where('id', $id)
            ->update([
                'trxstatus' => 'POSTED',
                'posteddatetime' => FinanceModel::getServerDateTime(),
                'postedby' => auth()->user()->id
            ]);

        $chk = db::table('disbursement')
            ->where('id', $id)
            ->first();

        $disbursement_je = db::table('disbursement_jedetails')
            ->where('headerid', $id)
            ->where('deleted', 0)
            ->get();



        if(count($disbursement_je) > 0)
        {
            $jeid = db::table('acc_je')
                ->insertGetId([
                    'transdate' => $chk->transdate,
                    'jestatus' => 'Posted',
                    'transtype' => 'DSMT',
                    'transid' => $id,
                    'remarks' => $chk->remarks,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);

            $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);
            db::table('acc_je')
                ->where('id', $jeid)
                ->update([
                    'refid' => $refnum
                ]);

            foreach($disbursement_je as $detail)
            {
                db::table('acc_jedetails')                  
                    ->insert([
                        'headerid' => $jeid,
                        'accid' => $detail->glid,
                        'debit' => $detail->debit,
                        'credit' => $detail->credit,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }
        }

        $chk = db::table('disbursement')
            ->where('id', $id)
            ->first();

        return $chk->trxstatus;
    }

    public function disbursement_removeje(Request $request)
    {
        $id = $request->get('id');

        db::table('disbursement_jedetails')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);

        return 'done';
    }
    

}
