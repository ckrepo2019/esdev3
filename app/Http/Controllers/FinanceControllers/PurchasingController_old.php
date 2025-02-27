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

class PurchasingController extends Controller
{
    public function vendor()
    {
        return view('/finance/purchasing/vendor');
    }

    public function vendor_update(Request $request)
    {
        if($request->ajax())
        {
            $vendorid = $request->get('vendorid');
            $vendor = $request->get('vendor');

            $address = $request->get('address');
            $contactno = $request->get('contactno');
            $email = $request->get('email');
            $action = $request->get('action');

            if($action == 'create')
            {
                $_vendor = db::table('purchasing_supplier')
                    ->where('suppliername', $vendor)
                    ->where('deleted', 0)
                    ->first();

                if($_vendor)
                {
                    return 0;
                }
                else
                {
                    db::table('purchasing_supplier')
                        ->insert([
                            'suppliername' => $vendor,
                            'address' => $address,
                            'contactno' => $contactno,
                            'email' => $email,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);


                    return 1;
                }
            }
            else
            {
                $_vendor = db::table('purchasing_supplier')
                    ->where('suppliername', $vendor)
                    ->where('id', '!=', $vendorid)
                    ->where('deleted', 0)
                    ->first();

                if($_vendor)
                {
                    return 0;
                }
                else
                {
                    db::table('purchasing_supplier')
                        ->where('id', $vendorid)
                        ->update([
                            'suppliername' => $vendor,
                            'address' => $address,
                            'contactno' => $contactno,
                            'email' => $email,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);

                    return 1;
                }
            }
        }
    }

    public function vendor_load(Request $request)
    {
        if($request->ajax())
        {
            $vendors = db::table('purchasing_supplier')
                ->where('deleted', 0)
                ->get();


            $list = '';

            foreach($vendors as $vendor)
            {
                $list .='
                    <tr data-id="'.$vendor->id.'">
                        <td>'.$vendor->suppliername.'</td>
                        <td>'.$vendor->address.'</td>
                        <td>'.$vendor->contactno.'</td>
                        <td>'.$vendor->email.'</td>
                    </tr>
                ';
            }

            $data = array(
                'list' => $list
            );


            echo json_encode($data);
        }
    }

    public function vendor_edit(Request $request)
    {
        if($request->ajax())
        {
            $vendorid = $request->get('vendorid');

            $vendor = db::table('purchasing_supplier')
                ->where('id', $vendorid)
                ->first();


            $data = array(
                'vendor_name' => $vendor->suppliername,
                'vendor_address' => $vendor->address,
                'vendor_contactno' => $vendor->contactno,
                'vendor_email' => $vendor->email
            );

            echo json_encode($data);
        }
    }

    public function vendor_delete(Request $request)
    {
        if($request->ajax())
        {
            $vendorid = $request->get('vendorid');

            db::table('purchasing_supplier')
                ->where('id', $vendorid)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => FinanceModel::getServerDateTime()
                ]);
        }
    }

    public function purchaseorder()
    {
        return view('/finance/purchasing/purchaseorder');
    }

    public function po_additem(Request $request)
    {
        if($request->ajax())
        {
            $itemid = $request->get('itemid');
            $action = $request->get('action');

            // db::table('')

        }
    }

    public function purchase_create(Request $request)
    {
        $items = $request->get('items');
        $supplier = $request->get('supplier');
        $grandtotal = str_replace(',', '', $request->get('grandtotal'));
        $ptype = $request->get('ptype');
        $remarks = $request->get('remarks');
        $dataid = $request->get('dataid');
        $time = date_format(date_create(FinanceModel::getServerDateTime()), 'H:i:s');
        $date = date_format(date_create($request->get('date') . ' ' . $time), 'Y-m-d H:i:s');
        
        if($dataid == 0)
        {
            $purchaseid = db::table('purchasing')
                ->insertGetId([
                    'supplierid' => $supplier,
                    'totalamount' => $grandtotal,
                    'transdate' => $date, //FinanceModel::getServerDateTime(),
                    'ptype' => $ptype,
                    'remarks' => $remarks,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);

            $refnum = 'PO-' . sprintf('%07d', $purchaseid);
            
            db::table('purchasing')
                ->where('id', $purchaseid)
                ->update([
                    'refno' => $refnum
                ]);

            foreach($items as $item)
            {
                
                $id = $item['itemid'];
                $amount = $item['itemamount'];
                $qty = $item['qty'];
                $total = $item['total'];

                db::table('purchasing_details')
                    ->insert([
                        'headerid' => $purchaseid,
                        'itemid' => $id,
                        'amount' => $amount,
                        'qty' => $qty,
                        'totalamount' => $total,
                        'createdby'=> auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }

            return 'done';
        }
        else{
            db::table('purchasing')
                ->where('id', $dataid)
                ->update([
                    'supplierid' => $supplier,
                    'ptype' => $ptype,
                    'totalamount' => $grandtotal,
                    'transdate' => $date,
                    'remarks' => $remarks,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => FinanceModel::getServerDateTime()
                ]);

            foreach($items as $item)
            {
                $detailid = $item['dataid'];
                $id = $item['itemid'];
                $amount = $item['itemamount'];
                $qty = $item['qty'];
                $total = $item['total'];

                if($detailid == 0)
                {
                    db::table('purchasing_details')
                        ->insert([
                            'headerid' => $dataid,
                            'itemid' => $id,
                            'amount' => $amount,
                            'qty' => $qty,
                            'totalamount' => $total,
                            'createdby'=> auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }
                    
            }

            return 'done';
        }
    }

    public function purchase_load(Request $request)
    {
        $supplierid = $request->get('supplierid');
        $datefrom = date_format(date_create($request->get('datefrom')), 'Y-m-d 00:00');
        $dateto = date_format(date_create($request->get('dateto')), 'Y-m-d 23:59');
        $filter = $request->get('filter');
        $list = array();

        $purchasing = db::table('purchasing')
            ->select(db::raw('purchasing.id, purchasing.createddatetime, refno, companyname, totalamount, pstatus, ptype, remarks, transdate'))
            ->join('expense_company', 'purchasing.supplierid', '=', 'expense_company.id')
            ->where(function($q) use($supplierid){
                if($supplierid != 0)
                {
                    $q->where('supplierid', $supplierid);
                }
            })
            ->where('purchasing.deleted', 0)
            ->whereBetween('purchasing.transdate', [$datefrom, $dateto])
            ->orderBy('transdate', 'DESC')
            ->get();
            
        foreach($purchasing as $p)
        {
            array_push($list, (object)[
                'id' => $p->id,
                'date' => date_format(date_create($p->transdate), 'm-d-Y'),
                'ref' => $p->refno,
                'supplier' => $p->companyname,
                'amount' => number_format($p->totalamount, 2),
                'ptype' => $p->ptype,
                'pstatus' => $p->pstatus,
                'remarks' => $p->remarks
            ]);
        }

        return $list;
    }

    public function purchase_read(Request $request)
    {
        $id = $request->get('id');
        $action = $request->get('action');

        $purchasing = db::table('purchasing')
            ->where('id', $id)
            ->first();

        $supplierid = $purchasing->supplierid;
        $ptype = $purchasing->ptype;
        $remarks = $purchasing->remarks;
        $totalamount = $purchasing->totalamount;
        $pstatus = $purchasing->pstatus;

        $details = db::table('purchasing_details')
            ->select('purchasing_details.*', 'description')
            ->join('items', 'purchasing_details.itemid', '=', 'items.id')
            ->where('headerid', $id)
            ->where('purchasing_details.deleted', 0)
            ->get();

        $data = array(
            'supplierid' => $supplierid,
            'ptype' => $ptype,
            'remarks' => $remarks,
            'totalamount' => number_format($totalamount, 2),
            'pstatus' => $pstatus,
            'items' => $details,
            'date' => date_format(date_create($purchasing->transdate), 'Y-m-d')
        );

        // return $data;
        if($action == 'print')
        {
            $supplier = db::table('expense_company')->where('id', $purchasing->supplierid)->first()->companyname;
            // return $action;
            // return view('finance.purchasing.reports.pdf_po', compact('purchasing', 'data'));
            $pdf = PDF::loadview('finance.purchasing.reports.pdf_po', compact('purchasing', 'data', 'supplier'));
		    return $pdf->stream('Purchase Order.pdf');
        }
        else{
            return $data;
        }

        
    }

    public function purchase_loaditems(Request $request)
    {
        $headerid = $request->get('headerid');

        $details = db::table('purchasing_details')
            ->select('purchasing_details.*', 'description')
            ->join('items', 'purchasing_details.itemid', '=', 'items.id')
            ->where('headerid', $headerid)
            ->where('purchasing_details.deleted', 0)
            ->get();

        return $details;
    }

    public function purchase_deleteitem(Request $request)
    {
        $headerid = $request->get('headerid');
        $dataid = $request->get('dataid');

        $detail = db::table('purchasing_details')
            ->where('id', $dataid)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);
    }

    public function purchase_delete(Request $request)
    {
        $id = $request->get('dataid');

        db::table('purchasing')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);
        
        return 'done';
    }

    public function purchase_post(Request $request)
    {
        $id = $request->get('dataid');

        db::table('purchasing')
            ->where('id', $id)
            ->update([
                'pstatus' => 'POSTED',
                'postdatetime' => FinanceModel::getServerDateTime(),
                'postedby' => auth()->user()->id
            ]);
    }

    public function receiving_load(Request $request)
    {
        $supplierid = $request->get('supplierid');
        $datefrom = date_format(date_create($request->get('datefrom')), 'Y-m-d 00:00');
        $dateto = date_format(date_create($request->get('dateto')), 'Y-m-d 23:59');
        $filter = $request->get('filter');
        $list = array();

        $purchasing = db::table('purchasing')
            ->select(db::raw('purchasing.id, purchasing.createddatetime, refno, companyname, totalamount, pstatus, ptype, remarks'))
            ->join('expense_company', 'purchasing.supplierid', '=', 'expense_company.id')
            ->where(function($q) use($supplierid){
                if($supplierid != 0)
                {
                    $q->where('supplierid', $supplierid);
                }
            })
            ->where('purchasing.deleted', 0)
            ->where('pstatus', 'POSTED')
            ->whereBetween('purchasing.createddatetime', [$datefrom, $dateto])
            ->where('purchasing.deleted', 0)
            ->get();
        // return $purchasing;

        foreach($purchasing as $po)
        {
            $receiving = db::table('receiving')
                ->where('poid', $po->id)
                ->first();

            if($receiving)
            {
                array_push($list, (object)[
                    'id' => $po->id,
                    'date' => date_format(date_create($po->createddatetime), 'm-d-Y'),
                    'ref' => $receiving->refnum,
                    'ponum' => $po->refno,
                    'supplier' => $po->companyname,
                    'amount' => number_format($po->totalamount, 2),
                    'ptype' => $po->ptype,
                    'pstatus' => $receiving->rstatus,
                    'dstatus' => $receiving->dstatus,
                    'remarks' => $po->remarks
                ]);
            }
            else{
                array_push($list, (object)[
                    'id' => $po->id,
                    'date' => date_format(date_create($po->createddatetime), 'm-d-Y'),
                    'ref' => '',
                    'ponum' => $po->refno,
                    'supplier' => $po->companyname,
                    'amount' => number_format($po->totalamount, 2),
                    'ptype' => $po->ptype,
                    'pstatus' => 'PENDING',
                    'dstatus' => 'PENDING',
                    'remarks' => $po->remarks
                ]);
            }

            
        }

        return $list;

        
    }

    public function receiving(Request $request)
    {

        return view('/finance/purchasing/receiving');
    }

    public function receiving_read(Request $request)
    {
        $poid = $request->get('poid');
        $action = $request->get('action');

        // return $action;

        $po = db::table('purchasing')
            ->select(db::raw('purchasing.id, purchasing.createddatetime, purchasing.transdate, refno, expense_company.id as supplierid, companyname, totalamount, pstatus, ptype, remarks'))
            ->join('expense_company', 'purchasing.supplierid', '=', 'expense_company.id')
            ->where('purchasing.id', $poid)
            ->first();


        
        $invoiceno = '';
        $invoicedate = '';
        $datedelivered = '';
        $terms = '';
        $rstatus = '';
        $remarks = '';
        $items = array();
        $payables = array();
        $je = array();
        $rid = 0;
        $d_payment = 0;

        $receiving = db::table('receiving')
            ->where('poid', $po->id)
            ->first();

        if($receiving)
        {
            $rid = $receiving->id;
            $invoiceno = $receiving->invoiceno;
            $invoicedate = date_format(date_create($receiving->invoicedate), 'Y-m-d');
            $terms = $receiving->terms;
            $datedelivered = date_format(date_create($receiving->datedelivered), 'Y-m-d');
            $rstatus = $receiving->rstatus;
            $remarks = $receiving->remarks;
            

            $details = db::table('receiving_details')
                ->select('receiving_details.*', 'description')
                ->join('items', 'receiving_details.itemid', '=', 'items.id')
                ->where('headerid', $receiving->id)
                ->where('receiving_details.deleted', 0)
                ->get();

            foreach($details as $d)
            {
                array_push($items, (object)[
                    'id' => $d->id,
                    'headerid' => $d->headerid,
                    'poid' => $d->poid,
                    'itemid' => $d->itemid,
                    'description' => $d->description,
                    'amount' => $d->amount,
                    'qty' => $d->qty,
                    'totalamount' => $d->total,
                    'receivedqty' => $d->receivedqty
                ]);
            }


            $items = collect($items);

            $paysched = db::table('receiving_payables')
                ->where('headerid', $receiving->id)
                ->where('deleted', 0)
                ->get();

            $payables = collect($paysched);

            $je = db::table('receiving_jedetails')
                ->select(db::raw('receiving_jedetails.id, headerid, glid, debit, credit, `code`, account'))
                ->join('acc_coa', 'receiving_jedetails.glid', '=', 'acc_coa.id')
                ->where('receiving_jedetails.deleted', 0)
                ->where('headerid', $receiving->id)
                ->get();

            $je = collect($je);

            $disbursement = db::table('disbursement')
                ->select(db::raw('refnum, supplierid, sum(amount) as amount, trxstatus, headerid, rrid, transdate'))
                ->join('disbursement_details', 'disbursement.id', '=', 'disbursement_details.headerid')
                ->where('rrid', $rid)
                ->where('trxstatus', 'POSTED')
                ->first();

            if($disbursement)
            {
                $d_payment = $disbursement->amount;
            }

        }
        else{
            $details = db::table('purchasing_details')
                ->select('purchasing_details.*', 'description')
                ->join('items', 'purchasing_details.itemid', 'items.id')
                ->where('headerid', $po->id)
                ->where('purchasing_details.deleted', 0)
                ->get();

            foreach($details as $d)
            {
                array_push($items, (object)[
                    'id' => 0,
                    'headerid' => $d->headerid,
                    'podetailid' => $d->id,
                    'itemid' => $d->itemid,
                    'description' => $d->description,
                    'amount' => $d->amount,
                    'qty' => $d->qty,
                    'totalamount' => $d->totalamount,
                    // 'receivedqty' => $d->receivedqty
                ]);
            }

            $items = collect($items);
        }

        $data = array(
            'rid' => $rid,
            'refnum' => $po->refno,
            'ptype' => $po->ptype,
            'rstatus' => $rstatus,
            'supplier' => $po->companyname,
            'invoiceno' => $invoiceno,
            'invoicedate' => $invoicedate,
            'remarks' => $remarks,
            'terms' => $terms,
            'datedelivered' => $datedelivered,
            'd_payment' => $d_payment,
            'items' => $items,
            'payables' => $payables,
            'je' => $je,
        );

        // return $items;

        if($action == 'print')
        {
            $purchasing = $po;

            // return $purchasing->transdate;
            $supplier = db::table('expense_company')->where('id', $purchasing->supplierid)->first()->companyname;
            // return $action;
            // return view('finance.purchasing.reports.pdf_po', compact('purchasing', 'data'));
            $pdf = PDF::loadview('finance.purchasing.reports.pdf_rr', compact('purchasing', 'data', 'supplier'));
            return $pdf->stream('Purchase Order.pdf');
        }
        else{
            return $data;
        }
            

    }

    public function receiving_save(Request $request)
    {
        $poid = $request->get('poid');
        $rid = $request->get('rid');
        $invoiceno = $request->get('invoiceno');
        $invoicedate = $request->get('invoicedate');
        $datedelivered = $request->get('datedelivered');
        $terms = $request->get('terms');
        $items = $request->get('items');
        $termsched = $request->get('termsched');
        $jearray = $request->get('je_array');
        $remarks = $request->get('remarks');

        // return $termsched;

        if($rid == 0)
        {
            $receivingid = db::table('receiving')
                ->insertGetId([
                    'poid' => $poid,
                    'invoiceno' => $invoiceno,
                    'invoicedate' => $invoicedate,
                    'datedelivered' => $datedelivered,
                    'terms' => $terms,
                    'remarks' => $remarks,
                    'rstatus' => 'SUBMITTED',
                    'createddatetime' => FinanceModel::getServerDateTime(),
                    'createdby' => auth()->user()->id
                ]);

            $refnum = 'RR-' . sprintf('%07d', $receivingid);
            db::table('receiving')
                ->where('id', $receivingid)
                ->update([
                    'refnum' => $refnum
                ]);


            foreach($items as $item)
            {
                if($item['dataid'] == 0)
                {
                    db::table('receiving_details')
                        ->insert([
                            'headerid' => $receivingid,
                            'poid' => $poid,
                            'itemid' => $item['itemid'],
                            'amount' => $item['amount'],
                            'qty' => $item['qty'],
                            'total' => $item['totalamount'],
                            'receivedqty' => $item['r_qty'],
                            'rtotal' => str_replace(',', '', $item['r_totalamount']),
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }
                else{

                }
            }


            if($terms)
            {
                foreach($termsched as $sched)
                {
                    if($sched['dataid'] == 0)
                    {
                        db::table('receiving_payables')
                            ->insert([
                                'headerid' => $receivingid,
                                'duedate' => date_format(date_create($sched['due']), 'Y-m-d'),
                                'amount' => str_replace(',', '', $sched['amount']),
                                'amountpaid' => $sched['payment'],
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);
                    }
                }
            }

            if($jearray != '')
            {
                foreach($jearray as $je)
                {
                    if($je['dataid'] == 0)
                    {
                        db::table('receiving_jedetails')
                            ->insert([
                                'headerid' => $receivingid,
                                'glid' => $je['glid'],
                                'debit' => $je['debit'],
                                'credit' => $je['credit']
                            ]);
                    }
                }
            }

        }
        else{
            $receivingid = db::table('receiving')
                ->where('id', $rid)
                ->update([
                    'poid' => $poid,
                    'invoiceno' => $invoiceno,
                    'invoicedate' => $invoicedate,
                    'datedelivered' => $datedelivered,
                    'terms' => $terms,
                    'remarks' => $remarks,
                    'rstatus' => 'SUBMITTED',
                    'updateddatetime' => FinanceModel::getServerDateTime(),
                    'updatedby' => auth()->user()->id
                ]);

            foreach($items as $item)
            {
                if($item['dataid'] == 0)
                {
                    db::table('receiving_details')
                        ->insert([
                            'headerid' => $rid,
                            'poid' => $poid,
                            'itemid' => $item['itemid'],
                            'amount' => $item['amount'],
                            'qty' => $item['qty'],
                            'total' => $item['totalamount'],
                            'receivedqty' => $item['r_qty'],
                            'rtotal' => str_replace(',', '', $item['r_totalamount']),
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }
                else{
                    db::table('receiving_details')
                        ->where('id', $item['dataid'])
                        ->update([
                            'receivedqty' => $item['r_qty'],
                            'rtotal' => str_replace(',', '', $item['r_totalamount']),
                            'updateddatetime' => FinanceModel::getServerDateTime(),
                            'updatedby' => auth()->user()->id
                        ]);
                }
            }

            // return $termsched;

            if($termsched)
            {
                foreach($termsched as $sched)
                {
                    if($sched['dataid'] == 0)
                    {
                        db::table('receiving_payables')
                            ->insert([
                                'headerid' => $rid,
                                'duedate' => date_format(date_create($sched['due']), 'Y-m-d'),
                                'amount' => str_replace(',', '', $sched['amount']),
                                'amountpaid' => $sched['payment'],
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);
                    }
                } 
            }


            if($jearray != '')
            {
                foreach($jearray as $je)
                {
                    if($je['dataid'] == 0)
                    {
                        db::table('receiving_jedetails')
                            ->insert([
                                'headerid' => $rid,
                                'glid' => $je['glid'],
                                'debit' => $je['debit'],
                                'credit' => $je['credit']
                            ]);
                    }
                }           
            }
        }
    }

    public function receiving_post(Request $request)
    {
        $dataid = $request->get('dataid');

        db::table('receiving')
            ->where('id', $dataid)
            ->update([
                'rstatus' => 'POSTED',
                'postedby' => auth()->user()->id,
                'posteddatetime' => FinanceModel::getServerDateTime()
            ]);

        $chkstatus = db::table('receiving')
            ->where('id', $dataid)
            ->first();

        $jedetails = db::table('receiving_jedetails')
            ->where('headerid', $dataid)
            ->where('deleted', 0)
            ->get();

        if(count($jedetails) > 0)
        {
            $jeid = db::table('acc_je')
                ->insertGetId([
                    'transdate' => $chkstatus->invoicedate,
                    'jestatus' => 'Posted',
                    'transtype' => 'RECEIVING',
                    'remarks' => $chkstatus->remarks,
                    'transid' => $dataid,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);

            $refnum = 'JE'. date('Y') . sprintf('%06d', $jeid);
            db::table('acc_je')
                ->where('id', $jeid)
                ->update([
                    'refid' => $refnum
                ]);

            foreach($jedetails as $detail)
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

        return 'posted';
    }

    public function receiving_print(Request $request)
    {

    }

    public function receiving_removesched(Request $request)
    {
        $rrid = $request->get('rrid');

        db::table('receiving_payables')
            ->where('headerid', $rrid)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);
    }

}