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

class ExpensesController extends Controller
{
    public function expenses()
    {
        return view('finance.expenses.expenses');
    }


    public function saveexpense(Request $request)
    {
        if ($request->ajax()) {
            $description = $request->get('description');
            $transdate = $request->get('transdate');
            $requestby = $request->get('requestby');
            $remarks = $request->get('remarks');
            $paidby = $request->get('paidby');
            $trans = $request->get('trans');
            $dataid = $request->get('dataid');
            $paytype = $request->get('paytype');
            $bankid = $request->get('bankid');
            $checkno = $request->get('checkno');
            $checkdate = $request->get('checkdate');
            $voucherno = $request->get('voucherno');
            // $voucherno = '';
            // if($request->get('voucherno') != '')
            // {
            //     $voucherno = explode(' - ', $request->get('voucherno'));
            //     $voucherno = $voucherno[1];
            // }

            if ($voucherno != '') {
                $voucherno = sprintf('%06d', $voucherno);
            }

            if ($trans == 1) {
                $delete = 0;
            } else {
                $delete = 1;
            }



            if ($dataid == '') {
                $ins = db::table('expense')
                    ->insertGetId([
                        'description' => $description,
                        'requestedbyid' => $requestby,
                        'amount' => 0,
                        'transdate' => $transdate,
                        'paidby' => $paidby,
                        'status' => 'SUBMITTED',
                        'remarks' => $remarks,
                        'paytype' => $paytype,
                        'bankid' => $bankid,
                        'checkno' => $checkno,
                        'checkdate' => $checkdate,
                        'createdby' => auth()->user()->id,
                        'deleted' => $delete,
                        'voucherno' => $voucherno
                    ]);

                $refnum = 'EXP-' . sprintf('%06d', $ins);

                $saveRef = db::table('expense')
                    ->where('id', $ins)
                    ->update([
                        'description' => $description,
                        'requestedbyid' => $requestby,
                        'amount' => 0,
                        'transdate' => $transdate,
                        'paidby' => $paidby,
                        'status' => 'SUBMITTED',
                        'refnum' => $refnum,
                        'remarks' => $remarks,
                        'paytype' => $paytype,
                        'bankid' => $bankid,
                        'checkno' => $checkno,
                        'checkdate' => $checkdate,
                        'createdby' => auth()->user()->id,
                        'deleted' => $delete,
                        'voucherno' => $voucherno
                    ]);
            } else {


                $expense = db::table('expense')
                    ->where('id', $dataid)
                    ->first();

                if ($expense->status == 'SUBMITTED') {
                    $gt = db::table('expensedetail')
                        ->where('headerid', $dataid)
                        ->where('deleted', 0)
                        ->sum('total');

                    // return $gt;


                    $ins = db::table('expense')
                        ->where('id', $dataid)
                        ->update([
                            'description' => $description,
                            'requestedbyid' => $requestby,
                            'amount' => $gt,
                            'transdate' => $transdate,
                            'paidby' => $paidby,
                            'status' => 'SUBMITTED',
                            // 'refnum' => $refnum . $ins,
                            'remarks' => $remarks,
                            'paytype' => $paytype,
                            'bankid' => $bankid,
                            'checkno' => $checkno,
                            'checkdate' => $checkdate,
                            'createdby' => auth()->user()->id,
                            'deleted' => $delete,
                            'voucherno' => $voucherno
                        ]);
                }

            }

            return $ins;

        }
    }

    public function searchexpenses(Request $request)
    {
        if ($request->ajax()) {
            $filter = $request->get('filter');
            $datefrom = $request->get('datefrom');
            $dateto = $request->get('dateto');
            $status = $request->get('status');

            $datefrom = date_create($datefrom);
            $datefrom = date_format($datefrom, 'Y-m-d 00:00');

            $dateto = date_create($dateto);
            $dateto = date_format($dateto, 'Y-m-d 23:59');

            // return $filter;

            $expenses = db::table('expense')
                ->select('expense.id as expenseid', 'transdate', 'description', 'companyname as name', 'amount', 'status', 'refnum', 'voucherno')
                ->join('expense_company', 'expense.requestedbyid', '=', 'expense_company.id')
                ->where(function ($q) use ($filter) {
                    $q->where('description', 'like', '%' . $filter . '%')
                        ->orWhere('voucherno', 'like', '%' . $filter . '%');
                })
                ->where(function ($q) use ($status) {
                    if ($status != 'ALL') {
                        $q->where('status', $status);
                    }
                })
                ->where('expense.deleted', 0)
                ->whereBetween('transdate', [$datefrom, $dateto])
                // ->orderBy('status', 'DESC')
                // ->orderBy('transdate', 'DESC')
                ->orderBy('voucherno')
                ->get();

            // echo ' ' . $datefrom . ' - ' . $dateto . ' ' . $filter;

            // return $expenses;
            $total = 0;
            if (count($expenses) > 0) {
                $list = '';
                foreach ($expenses as $expense) {
                    $total += $expense->amount;
                    $tdate = date_create($expense->transdate);
                    $tdate = date_format($tdate, 'm-d-Y');

                    if ($expense->status == 'SUBMITTED') {
                        $list .= '
                            <tr class="expense-tr" data-id="' . $expense->expenseid . '">
                            <td>' . $expense->voucherno . '</td>
                                <td>' . $expense->description . '</td>
                                <td>' . $tdate . '</td>
                                <td>' . $expense->name . '</td>
                                <td class="text-right">' . number_format($expense->amount, 2) . '</td>
                                <td>' . $expense->status . '</td>
                            </tr>
                        ';
                    } else if ($expense->status == 'APPROVED') {
                        $list .= '
                            <tr class="expense-tr text-success" data-id="' . $expense->expenseid . '">
                            <td>' . $expense->voucherno . '</td>
                                <td>' . $expense->description . '</td>
                                <td>' . $tdate . '</td>
                                <td>' . $expense->name . '</td>
                                <td class="text-right">' . number_format($expense->amount, 2) . '</td>
                                <td>' . $expense->status . '</td>
                            </tr>
                        ';
                    } else if ($expense->status == 'DISAPPROVED') {
                        $list .= '
                            <tr class="expense-tr text-danger" data-id="' . $expense->expenseid . '">
                            <td>' . $expense->voucherno . '</td>
                                <td>' . $expense->description . '</td>
                                <td>' . $tdate . '</td>
                                <td>' . $expense->name . '</td>
                                <td class="text-right">' . number_format($expense->amount, 2) . '</td>
                                <td>' . $expense->status . '</td>
                            </tr>
                        ';
                    }
                }

                $data = array(
                    'list' => $list,
                    'gtotal' => '<td colspan="5" class="text-right text-bold">TOTAL: <span class="text-success">' . number_format($total, 2) . '</span></td>'
                );

                echo json_encode($data);
            }
        }
    }

    public function saveexpensedetail(Request $request)
    {
        if ($request->ajax()) {
            $headerid = $request->get('headerid');
            $itemid = $request->get('itemid');
            $itemprice = str_replace(',', '', $request->get('itemprice'));
            $qty = $request->get('qty');
            $total = str_replace(',', '', $request->get('total'));
            $detailid = $request->get('detailid');
            $remarks = $request->get('remarks');


            if ($detailid == 0) {
                $insitem = db::table('expensedetail')
                    ->insert([
                        'headerid' => $headerid,
                        'itemid' => $itemid,
                        'itemprice' => $itemprice,
                        'qty' => $qty,
                        'total' => $total,
                        'remarks' => $remarks,
                        'deleted' => 0,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);
            } else {
                $upditem = db::table('expensedetail')
                    ->where('id', $detailid)
                    ->update([
                        'headerid' => $headerid,
                        'itemid' => $itemid,
                        'itemprice' => $itemprice,
                        'qty' => $qty,
                        'total' => $total,
                        'remarks' => $remarks,
                        'deleted' => 0,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }
        }
    }

    public function loadexpensedetail(Request $request)
    {
        $headerid = $request->get('headerid');
        $status = db::table('expense')
            ->where('id', $headerid)
            ->first()
            ->status;

        $details = db::table('expensedetail')
            ->select('expensedetail.id', 'items.description', 'itemprice', 'expensedetail.qty', 'total', 'itemid', 'glid', 'remarks')
            ->leftjoin('items', 'expensedetail.itemid', '=', 'items.id')
            ->where('headerid', $headerid)
            ->where('expensedetail.deleted', 0)
            ->get();

        $list = '';
        $gtotal = 0;
        $jearray = array();

        $coaid = 0;
        $coaaccount = '';
        $coaamount = '';

        foreach ($details as $detail) {
            $coa = db::table('acc_coa')
                ->where('id', $detail->glid)
                ->first();

            if ($coa) {
                if ($coaid == 0) {
                    $coaid = $coa->id;
                    $coaaccount = $coa->code . ' - ' . $coa->account;
                    $coaamount = $detail->total;
                } else {
                    if ($coaid == $coa->id) {
                        $coaamount += $detail->total;
                    } else {
                        array_push($jearray, (object) [
                            'id' => $coa->id,
                            'account' => $coa->code . ' - ' . $coa->account,
                            'amount' => $detail->total
                        ]);
                    }
                }


            }

            $gtotal += $detail->total;
            $list .= '
                <tr data-id="' . $detail->id . '">
                  <td>' . $detail->remarks . '</td>
                  <td class="text-right">' . number_format($detail->itemprice, 2) . '</td>
                  <td class="text-center">' . $detail->qty . '</td>
                  <td class="text-right">' . number_format($detail->total, 2) . '</td>
                </tr>
            ';
        }

        if ($gtotal > 0) {
            $grandtotal = '
                <td colspan="4" class="text-right text-bold">TOTAL: <span id="gt" class="text-success text-lg">' . number_format($gtotal, 2) . '</span></td>
            ';
        } else {
            $grandtotal = '';
        }
        $data = array(
            'list' => $list,
            'gtotal' => $grandtotal,
            'jearray' => $jearray,
            'status' => $status
        );

        echo json_encode($data);
    }

    public function expense_genje(Request $request)
    {
        $headerid = $request->get('headerid');
        $status = db::table('expense')
            ->where('id', $headerid)
            ->first()->status;

        $details = db::table('expensedetail')
            ->select(db::raw('glid, `code`, account, SUM(total) AS amount'))
            ->join('items', 'expensedetail.itemid', '=', 'items.id')
            ->join('acc_coa', 'items.glid', '=', 'acc_coa.id')
            ->where('headerid', $headerid)
            ->where('expensedetail.deleted', 0)
            ->groupBy('glid')
            ->get();

        $exdetail = collect($details);

        $coalist = array();

        foreach ($details as $detail) {
            $check = db::table('expense_jedetails')
                ->where('headerid', $headerid)
                ->where('glid', $detail->glid)
                ->where('deleted', 0)
                ->first();

            if ($check) {
                db::table('expense_jedetails')
                    ->where('id', $check->id)
                    ->update([
                        'debit' => $detail->amount,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);
            } else {
                db::table('expense_jedetails')
                    ->insert([
                        'headerid' => $headerid,
                        'glid' => $detail->glid,
                        'debit' => $detail->amount,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }
        }

        $je_details = db::table('expense_jedetails')
            ->where('headerid', $headerid)
            ->where('deleted', 0)
            ->get();

        foreach ($je_details as $je) {
            $ex = $exdetail->firstWhere('glid', $je->glid);
            // return $ex;
            if ($ex == null) {
                if ($je->credit == 0) {
                    db::table('expense_jedetails')
                        ->where('id', $je->id)
                        ->update([
                            'deleted' => 1,
                            'deleteddatetime' => FinanceModel::getServerDateTime(),
                            'deletedby' => auth()->user()->id
                        ]);
                }
            }
        }


        $coalist = db::table('expense_jedetails')
            ->select(db::raw('expense_jedetails.`id`, glid, code, account, debit, credit'))
            ->join('acc_coa', 'expense_jedetails.glid', '=', 'acc_coa.id')
            ->where('headerid', $headerid)
            ->where('expense_jedetails.deleted', 0)
            ->get();

        $data = array(
            'coalist' => $coalist,
            'status' => $status
        );

        return $data;
    }

    public function expense_loadje(Request $request)
    {
        $headerid = $request->get('headerid');

        $coalist = db::table('expense_jedetails')
            ->select(db::raw('expense_jedetails.`id`, glid, code, account, debit, credit'))
            ->join('acc_coa', 'expense_jedetails.glid', '=', 'acc_coa.id')
            ->where('headerid', $headerid)
            ->where('expense_jedetails.deleted', 0)
            ->get();

        $status = db::table('expense')
            ->where('id', $headerid)
            ->first()->status;

        $data = array(
            'coalist' => $coalist,
            'status' => $status
        );

        return $data;
    }

    public function expense_getje(Request $request)
    {
        $jeid = $request->get('jeid');

        $je = db::table('expense_jedetails')
            ->where('id', $jeid)
            ->first();

        $accounts = db::table('acc_coa')
            ->where('deleted', 0)
            ->orderBy('code')
            ->get();

        $acclist = '';

        foreach ($accounts as $acc) {
            if ($je->glid == $acc->id) {
                $acclist .= '
                    <option value="' . $acc->id . '" selected>' . $acc->code . ' - ' . $acc->account . '</option>
                ';
            } else {
                $acclist .= '
                    <option value="' . $acc->id . '">' . $acc->code . ' - ' . $acc->account . '</option>
                ';
            }

        }

        $data = array(
            [
                'id' => $je->id,
                'headerid' => $je->headerid,
                'glid' => $je->glid,
                'debit' => $je->debit,
                'credit' => $je->credit,
                'accounts' => $acclist
            ]
        );

        return $data;

    }

    public function loadexpense(Request $request)
    {
        if ($request->ajax()) {
            $headerid = $request->get('headerid');
            $voucherno = '';

            $expense = db::table('expense')
                ->where('id', $headerid)
                ->first();

            $transdate = date_create($expense->transdate);
            $transdate = date_format($transdate, 'Y-m-d');
            $voucherno = $expense->voucherno;
            // if($voucherno != null)
            // {
            //     $voucherno = ($expense->paytype == 'CASH') ? 'CV - ' . $voucherno : 'CHV - ' . $voucherno;
            // }
            // else{
            //     $voucherno = '';
            // }

            $data = array(
                'description' => $expense->description,
                'requestedbyid' => $expense->requestedbyid,
                'transdate' => $transdate,
                'paidby' => $expense->paidby,
                'status' => $expense->status,
                'refnum' => $expense->refnum,
                'remarks' => $expense->remarks,
                'bankid' => $expense->bankid,
                'paytype' => $expense->paytype,
                'checkno' => $expense->checkno,
                'checkdate' => $expense->checkdate,
                'voucherno' => $voucherno
            );

            echo json_encode($data);

        }
    }

    public function loadexpenseitems(Request $request)
    {
        if ($request->ajax()) {
            $itemid = $request->get('itemid');
            $items = FinanceModel::expenseitems();

            $list = '';
            foreach ($items as $item) {
                if ($itemid == $item->id) {
                    $list .= '
                        <option value="' . $item->id . '" selected>' . $item->description . '</option>
                    ';
                } else {
                    $list .= '
                        <option value="' . $item->id . '">' . $item->description . '</option>
                    ';
                }
            }

            $data = array(
                'list' => $list
            );

            echo json_encode($data);
        }
    }

    public function approveexpense(Request $request)
    {
        if ($request->ajax()) {
            $dataid = $request->get('dataid');

            $chkstatus = db::table('expense')
                ->where('id', $dataid)
                ->first();



            if ($chkstatus->status == 'SUBMITTED') {
                $chkelevate = db::table('chrngpermission')
                    ->where('userid', auth()->user()->id)
                    ->count();



                if (1 > 0) {
                    $expense = db::table('expense')
                        ->where('id', $dataid)
                        ->update([
                            'status' => 'APPROVED',
                            'approveby' => auth()->user()->id,
                            'approveddatetime' => FinanceModel::getServerDateTime(),
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);


                    $jedetails = db::table('expense_jedetails')
                        ->where('headerid', $dataid)
                        ->where('deleted', 0)
                        ->get();

                    if (count($jedetails) > 0) {
                        $jeid = db::table('acc_je')
                            ->insertGetId([
                                'transdate' => $chkstatus->transdate,
                                'jestatus' => 'Posted',
                                'transtype' => 'EXP',
                                'remarks' => $chkstatus->remarks,
                                'transid' => $dataid,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => FinanceModel::getServerDateTime()
                            ]);

                        $refnum = 'JE' . date('Y') . sprintf('%06d', $jeid);
                        db::table('acc_je')
                            ->where('id', $jeid)
                            ->update([
                                'refid' => $refnum
                            ]);

                        foreach ($jedetails as $detail) {
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



                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 2;
            }
        }
    }

    public function disapproveexpense(Request $request)
    {
        if ($request->ajax()) {
            $dataid = $request->get('dataid');

            $chkstatus = db::table('expense')
                ->where('id', $dataid)
                ->first();



            if ($chkstatus->status == 'SUBMITTED') {
                $chkelevate = db::table('chrngpermission')
                    ->where('userid', auth()->user()->id)
                    ->count();



                if ($chkelevate > 0) {
                    $expense = db::table('expense')
                        ->where('id', $dataid)
                        ->update([
                            'status' => 'DISAPPROVED',
                            'approveby' => auth()->user()->id,
                            'approveddatetime' => FinanceModel::getServerDateTime(),
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);

                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 2;
            }
        }
    }

    public function expenseItemInfo(Request $request)
    {
        if ($request->ajax()) {
            $detailid = $request->get('detailid');

            $detail = db::table('expensedetail')
                ->where('id', $detailid)
                ->first();

            $data = array(
                'itemid' => $detail->itemid,
                'itemprice' => $detail->itemprice,
                'qty' => $detail->qty,
                'total' => $detail->total,
                'remarks' => $detail->remarks
            );

            echo json_encode($data);
        }
    }


    public function saveNewItem(Request $request)
    {
        if ($request->ajax()) {
            $itemcode = $request->get('itemcode');
            $itemdesc = $request->get('itemdesc');
            $classid = $request->get('classid');
            $amount = $request->get('amount');
            $isexpense = $request->get('isexpense');

            $itemid = db::table('items')
                ->insertGetId([
                    'itemcode' => $itemcode,
                    'description' => $itemdesc,
                    'classid' => $classid,
                    'amount' => $amount,
                    'isdp' => 0,
                    'isreceivable' => 0,
                    'isexpense' => 1,
                    'deleted' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);

            return $itemid;

        }
    }



    public function company_load(Request $request)
    {
        $list = '';
        $company = db::table('expense_company')
            ->where('deleted', 0)
            ->orderBy('companyname')
            ->get();

        foreach ($company as $comp) {
            $list .= '
                <option value="' . $comp->id . '" comp-dept="' . $comp->department . '">
                    ' . strtoUpper($comp->companyname) . '
                </option>
            ';
        }

        $data = array(
            'list' => $list
        );

        echo json_encode($data);
    }

    public function expese_deletedetail(Request $request)
    {
        $dataid = $request->get('dataid');

        db::table('expensedetail')
            ->where('id', $dataid)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id
            ]);
    }



    public function expenses_items(Request $request)
    {
        $filter = $request->get('filter');

        $items = db::table('items')
            ->select('id', 'description', 'itemcode', 'qty')
            ->where('isexpense', 1)
            ->where('deleted', 0)
            ->get();

        return $items;
    }

    public function expenses_items_create(Request $request)
    {
        $dataid = $request->get('dataid');
        $code = $request->get('code');
        $description = $request->get('description');
        $cost = $request->get('cost');
        $classid = $request->get('classid');
        $coa = $request->get('coa');

        if ($dataid == 0) {
            $check = db::table('items')
                ->where('description', $description)
                ->where('deleted', 0)
                ->first();

            if ($check) {
                return 'exist';
            } else {
                db::table('items')
                    ->insert([
                        'itemcode' => $code,
                        'description' => $description,
                        'cost' => $cost,
                        'classid' => $classid,
                        'glid' => $coa,
                        'isexpense' => 1
                    ]);

                return 'done';
            }
        } else {
            $check = db::table('items')
                ->where('description', $description)
                ->where('id', '!=', $dataid)
                ->where('deleted', 0)
                ->first();

            if ($check) {
                return 'exist';
            } else {
                db::table('items')
                    ->where('id', $dataid)
                    ->update([
                        'itemcode' => $code,
                        'description' => $description,
                        'cost' => $cost,
                        'classid' => $classid,
                        'glid' => $coa
                    ]);

                return 'done';
            }
        }
    }

    public function expenses_items_read(Request $request)
    {
        $dataid = $request->get('dataid');

        $items = db::table('items')
            ->where('id', $dataid)
            ->first();

        $data = array(
            'code' => $items->itemcode,
            'description' => $items->description,
            'cost' => $items->cost,
            'classid' => $items->classid,
            'glid' => $items->glid
        );

        return $data;
    }

    public function expenses_items_delete(Request $request)
    {
        $dataid = $request->get('dataid');

        $check = db::table('expensedetail')
            ->where('itemid', $dataid)
            ->where('deleted', 0)
            ->first();

        if ($check) {
            return 'exist';
        } else {
            db::table('items')
                ->where('id', $dataid)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => FinanceModel::getServerDateTime(),
                    'deletedby' => auth()->user()->id
                ]);

            return 'done';
        }
    }

    public function expenses_gencoa()
    {
        $chartofaccounts = db::table('acc_coa')
            ->select('id', 'code', 'account')
            ->where('deleted', 0)
            ->orderBy('code')
            ->get();

        $coalist = '';

        foreach ($chartofaccounts as $coa) {
            $coalist .= '
                <option value="' . $coa->id . '">' . $coa->code . ' - ' . $coa->account . '</option>
            ';
        }

        return $coalist;
    }

    public function expense_saveje(Request $request)
    {
        $headerid = $request->get('headerid');
        $glid = $request->get('glid');
        $debit = str_replace(',', '', $request->get('debit'));
        $credit = str_replace(',', '', $request->get('credit'));

        $check = db::table('expense_jedetails')
            ->where('headerid', $headerid)
            ->where('glid', $glid)
            ->where('deleted', 0)
            ->first();

        if ($debit == 0 && $credit == 0) {
            return 'error';
        } else {
            db::table('expense_jedetails')
                ->insert([
                    'headerid' => $headerid,
                    'glid' => $glid,
                    'debit' => $debit,
                    'credit' => $credit,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);
        }

    }

    public function expense_deleteje(Request $request)
    {
        $jeid = $request->get('jeid');

        db::table('expense_jedetails')
            ->where('id', $jeid)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);

        $headerid = db::table('expense_jedetails')
            ->where('id', $jeid)
            ->first()->headerid;

        return $headerid;

    }

    public function expenses_print(Request $request)
    {
        $id = $request->get('id');

        $expense = db::table('expense')
            ->where('id', $id)
            ->first();

        if ($expense->voucherno == null) {
            $voucherno = db::table('expense')
                ->where('deleted', 0)
                // ->where('status', 'APPROVED')
                ->max('voucherno');

            $voucherno = $voucherno + 1;
            $voucherno = sprintf('%06d', $voucherno);

            // return $voucherno;

            db::table('expense')
                ->where('id', $id)
                ->update([
                    'voucherno' => $voucherno
                ]);

            $expense = db::table('expense')
                ->where('id', $id)
                ->first();

        }

        $details = db::table('expensedetail')
            ->select('description', 'total', 'remarks')
            ->join('items', 'expensedetail.itemid', '=', 'items.id')
            ->where('headerid', $id)
            ->where('expensedetail.deleted', 0)
            ->get();

        $jedetails = db::table('expense_jedetails')
            ->select(db::raw('code, account, debit, credit'))
            ->join('acc_coa', 'expense_jedetails.glid', '=', 'acc_coa.id')
            ->where('headerid', $id)
            ->where('expense_jedetails.deleted', 0)
            ->get();

        $bankname = '';
        $paytitle = '';

        if ($expense->bankid != null || $expense->bankid != '') {
            $bank = db::table('acc_bank')
                ->where('id', $expense->bankid)
                ->first();

            if ($bank) {
                $bankname = $bank->bankname;
            }
        }

        if ($expense->paytype == 'CASH') {
            $paytitle = 'CASH';
        } else {
            $paytitle = 'CHECK';
        }

        $supplier = db::table('expense_company')->where('id', $expense->requestedbyid)->first();

        $pdf = PDF::loadview('finance.expenses.reports.pdf_voucher', compact('expense', 'supplier', 'details', 'jedetails', 'bankname', 'paytitle'));
        return $pdf->stream('Cash/Check Voucher.pdf');

    }

    public function expenses_gencredit(Request $request)
    {
        $amount = str_replace(',', '', $request->get('amount'));
        $headerid = $request->get('headerid');

        $setuparray = array();

        $setup = db::table('acc_transsetup')
            ->where('transname', 'expense')
            ->where('deleted', 0)
            ->first();

        if ($setup) {
            $acc = db::table('acc_coa')
                ->where('id', $setup->creditgl)
                ->first();

            if ($acc) {
                array_push($setuparray, (object) [
                    'glid' => $acc->id,
                    'code' => $acc->code,
                    'account' => $acc->account
                ]);

                $chk = db::table('expense_jedetails')
                    ->where('headerid', $headerid)
                    ->where('glid', $setup->creditgl)
                    ->where('deleted', 0)
                    ->first();

                if ($chk) {
                    return 'exist';
                } else {
                    db::table('expense_jedetails')
                        ->insert([
                            'headerid' => $headerid,
                            'glid' => $setup->creditgl,
                            'credit' => $amount
                        ]);

                    return 'done';
                }
            } else {
                return 'error';
            }
        } else {
            return 'error';
        }

    }

    public static function expenses_getvoucherno(Request $request)
    {
        $type = $request->get('type');
        $paytype = $request->get('paytype');

        if($type == 'EXP')
        {
            $expense = db::table('expense')
                ->select(db::raw('voucherno'))
                ->where('deleted', 0)
                ->where('status', '!=', 'DISAPPROVED')
                ->where('paytype', $paytype)
                ->orderBy('id', 'DESC')
                ->first();

            if($expense)
            {
                return sprintf('%06d', $expense->voucherno+1);
            }
            else{
                return sprintf('%06d', 1);
            }
        }
        elseif($type == 'JE')
        {
            $je = db::table('acc_je')
            ->select(db::raw('voucherno'))
                ->where('transtype', 'JE')
                ->where('deleted', 0)
                ->orderBy('id', 'DESC')
                ->first();

            if($je)
            {
                return sprintf('%06d', $je->voucherno+1);
            }
            else{
                return sprintf('%06d', 1);
            }
        }
        elseif($type == 'DSMT')
        {
            $je = db::table('disbursement')
                ->select(db::raw('voucherno'))
                ->where('paytype', $paytype)
                ->where('deleted', 0)
                ->orderBy('id', 'DESC')
                ->first();

            if($je)
            {
                return sprintf('%06d', $je->voucherno+1);
            }
            else{
                return sprintf('%06d', 1);
            }
        }
    }

}
