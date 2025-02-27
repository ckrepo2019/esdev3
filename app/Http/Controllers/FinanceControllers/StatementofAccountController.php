<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\Finance\StatementofAccountModel;
use App\Models\Finance\FinanceUtilityModel;
use App\FinanceModel;

class StatementofAccountController extends Controller
{
    public function index(Request $request)
    {
        $schoolyears = DB::table('sy')
            ->get();

        $semesters = DB::table('semester')
            ->where('deleted', '0')
            ->get();

        $monthsetups = DB::table('monthsetup')
            ->get();


        $notes = DB::table('schoolreportsnote')
            ->where('deleted', '0')
            ->where('type', '1')
            ->get();

        $status = 0;

        if (count($notes) > 0) {
            foreach ($notes as $note) {
                if ($note->status) {
                    $status += 1;
                }
            }
        }

        $gradelevels = DB::table('gradelevel')
            ->where('deleted', '0')
            ->orderBy('sortid')
            ->get();

        return view('finance.statementofaccount.index')
            ->with('gradelevels', $gradelevels)
            ->with('semesters', $semesters)
            // ->with('students', $students)
            ->with('schoolyears', $schoolyears)
            ->with('monthsetups', $monthsetups)
            ->with('notes', $notes)
            ->with('status', $status);
    }
    public function generate(Request $request)
    {
        $semid = $request->get('selectedsemester');
        $syid = $request->get('selectedschoolyear');
        $levelid = $request->get('selectedgradelevel');
        $sectionid = $request->get('selectedsection');
        $courseid = $request->get('selectedcourse');

        if ($request->get('selectedmonth') == null) {
            $month = null;
        } else {
            $month = date('m', strtotime($request->get('selectedmonth')));
        }

        if ($levelid == 0) {
            $students = collect(StatementofAccountModel::allstudents())->values();
        } else {
            $students = collect(StatementofAccountModel::allstudents($levelid, $syid, $semid, $sectionid, $courseid))->values();
        }
        return view('finance.statementofaccount.filtertable')
            ->with('students', $students);

    }

    public function getaccount(Request $request)
    {
        $studid = $request->get('studid');
        $semid = $request->get('selectedsemester');
        $syid = $request->get('selectedschoolyear');
        $monthsetupid = $request->get('selectedmonth');
        $month = null;
        if ($request->get('selectedmonth') > 0) {
            if (date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month']) {
                if (strlen(date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month']) == 1) {
                    $month = '0' . date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month'];
                } else {
                    $month = date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month'];
                }
            }
        }

        $studinfo = db::table('studinfo')
            ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'sectionname', 'levelid')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('studinfo.id', $studid)
            ->first();

        if ($studinfo->levelid == 14 || $studinfo->levelid == 15) {
            $ledger = db::table('studledger')
                ->select('studledger.*', 'chrngsetup.groupname')
                ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                ->where('studledger.studid', $studid)
                ->where('studledger.syid', $syid)
                ->where(function ($q) use ($semid) {
                    if (DB::table('schoolinfo')->first()->shssetup == 0) {
                        $q->where('semid', $semid);
                    }
                })
                ->where('studledger.void', 0)
                ->where('studledger.deleted', 0)
                ->orderBy('studledger.id', 'asc')
                ->get();
        } elseif ($studinfo->levelid >= 17 && $studinfo->levelid <= 25) {
            $ledger = db::table('studledger')
                ->select('studledger.*', 'chrngsetup.groupname')
                ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                ->where('studledger.studid', $studid)
                ->where('studledger.syid', $syid)
                ->where('studledger.semid', $semid)
                ->where('studledger.deleted', 0)
                ->where('studledger.void', 0)
                ->orderBy('studledger.id', 'asc')
                ->get();
        } else {
            $ledger = db::table('studledger')
                ->select('studledger.*', 'chrngsetup.groupname')
                ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                ->where('studledger.studid', $studid)
                ->where('studledger.syid', $syid)
                ->where('studledger.deleted', 0)
                ->where('studledger.void', 0)
                ->orderBy('studledger.id', 'asc')
                ->get();
        }
        // if($studinfo->levelid == 14 || $studinfo->levelid == 15)
        // {

        // $ledger = db::table('studledger')
        //     ->where('studid', $studid)
        //     ->where('syid', $syid)
        //     ->where(function($q) use($semid){
        //         if(DB::table('schoolinfo')->first()->shssetup == 0)
        //         {
        //             $q->where('semid', $semid);
        //         }
        //     })
        //     ->where('void', 0)
        //     ->where('deleted', 0)
        //     ->orderBy('id', 'asc')
        //     ->get();
        // }
        // elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
        // {
        // $ledger = db::table('studledger')
        //     ->where('studid', $studid)
        //     ->where('syid', $syid)
        //     ->where('semid', $semid)
        //     ->where('void', 0)
        //     ->where('deleted', 0)
        //     ->orderBy('id', 'asc')
        //     ->get();
        // }
        // else
        // {
        // $ledger = db::table('studledger')
        //     ->where('studid', $studid)
        //     ->where('syid', $syid)
        //     ->where('void', 0)
        //     ->where('deleted', 0)
        //     ->orderBy('id', 'asc')
        //     ->get();
        // }

        $bal = 0;
        $debit = 0;
        $credit = 0;

        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai') {

            foreach ($ledger as $led) {
                $debit += $led->amount;

                if ($led->void == 0) {
                    $credit += $led->payment;
                }

                $lDate = date_create($led->createddatetime);
                $lDate = date_format($lDate, 'm-d-Y');

                if ($led->amount > 0) {
                    $amount = number_format($led->amount, 2);
                } else {
                    $amount = '';
                }

                if ($led->payment > 0) {
                    $payment = number_format($led->payment, 2);
                } else {
                    $payment = '';
                }

                if ($led->void == 0) {
                    $bal += $led->amount - $led->payment;
                }

            }
            if ($studinfo->levelid == 14 || $studinfo->levelid == 15) {
                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
                    ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function ($q) use ($semid) {
                        if (db::table('schoolinfo')->first()->shssetup == 0) {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('studpayscheddetail.deleted', 0)

                    ->groupBy(db::raw('MONTH(duedate)'))
                    ->get();
                // return $getPaySched;

            } else if ($studinfo->levelid >= 17 && $studinfo->levelid <= 20) {

                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
                    ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('studpayscheddetail.deleted', 0)
                    ->groupBy(db::raw('MONTH(duedate)'))
                    ->get();
                // $getPaySched = db::table('studpayscheddetail')
                // ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
                //     ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
                //     ->where('studid', $studid)
                //     ->where('syid', $syid)
                //     ->where('semid', $semid)
                //     ->where('deleted', 0)
                //     ->groupBy(db::raw('MONTH(duedate)'))
                //     ->get();
            } else {
                // $getPaySched = db::select('description, sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                //     from studpayscheddetail
                //     INNER JOIN itemclassification
                //     ON studpayscheddetail.`classid` = itemclassification.id
                //     where studid = ? and syid = ? and studpayscheddetail.deleted = 0
                //     group by MONTH(duedate)
                //     order by duedate', [$studid, $syid]);

                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('description1, sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate'))
                    ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->groupBy(db::raw('month(duedate)'))
                    ->orderBy('duedate');
            }
            $assessbilling = 0;
            $assesspayment = 0;
            $assessbalance = 0;
            $totalBal = collect($getPaySched)->sum('balance');
            ;

            if (count($getPaySched) > 0) {
                foreach ($getPaySched as $psched) {

                    // return $getPaySched;
                    // $totalBal += $psched->balance;
                    $assessbilling += $psched->amountdue;
                    $assesspayment += $psched->amountpay;
                    $assessbalance += $psched->balance;

                    $m = date_create($psched->duedate);
                    $f = date_format($m, 'F');
                    $m = date_format($m, 'm');

                    if ($psched->duedate != '') {
                        $particulars = 'PAYABLES FOR ' . strtoupper($f);
                    } else {
                        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'faai') {
                            $particulars = 'REGISTRATION/MISCELLANEOUS/BOOKS/GENYO';
                        } else {
                            $particulars = 'ONE-TIME PAYMENT';
                        }
                        $m = 0;
                    }
                }


                $monthname = date('M', strtotime('2020-' . $month));
                return view('finance.statementofaccount.table_xai')
                    ->with('studinfo', $studinfo)
                    ->with('monthname', $monthname)
                    ->with('ledger', $ledger)
                    ->with('getPaySched', $getPaySched);



            } else {

                $monthname = date('M', strtotime('2020-' . $month));
                return view('finance.statementofaccount.table_xai')
                    ->with('studinfo', $studinfo)
                    ->with('monthname', $monthname)
                    ->with('ledger', $ledger)
                    ->with('getPaySched', $getPaySched);
            }

        } else {

            $output = '<table class="table table-bordered" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th colspan="5">LEDGER</th>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Billing</th>
                                <th>Deduction</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>';

            foreach ($ledger as $led) {
                $debit += $led->amount;

                if ($led->void == 0) {
                    $credit += $led->payment;
                }

                $lDate = date_create($led->createddatetime);
                $lDate = date_format($lDate, 'm-d-Y');

                if ($led->amount > 0) {
                    $amount = number_format($led->amount, 2);
                } else {
                    $amount = '';
                }

                if ($led->payment > 0) {
                    $payment = number_format($led->payment, 2);
                } else {
                    $payment = '';
                }

                if ($led->void == 0) {
                    $bal += $led->amount - $led->payment;
                }

                if ($led->void == 0) {
                    $output .= '
                    <tr>
                        <td>' . $lDate . ' </td>
                        <td>' . $led->particulars . '</td>
                        <td class="text-right">' . $amount . '</td>
                        <td class="text-right">' . $payment . '</td>
                        <td class="text-right">' . number_format($bal, 2) . '</td>
                    </tr>
                    ';
                } else {
                    $output .= '
                    <tr>
                        <td class="text-danger"><del>' . $lDate . ' </del></td>
                        <td class="text-danger"><del>' . $led->particulars . '</del></td>
                        <td class="text-right text-danger"><del>' . $amount . '</del></td>
                        <td class="text-right text-danger"><del>' . $payment . '</del></td>
                        <td class="text-right text-danger"><del>' . number_format($bal, 2) . '</del></td>
                    </tr>
                    ';
                }

            }

            $output .= '
            <tr style="background-color:#007bff91">
                <th></th>
                <th style="text-align:right">
                    <strong>TOTAL:<strong>
                </th>
                <th class="text-right">
                    <strong><u>' . number_format($debit, 2) . '</u></strong>
                </th>
                <th class="text-right">
                    <strong><u>' . number_format($credit, 2) . '</u></strong>
                </th>
                <th class="text-right">
                    <strong><u>' . number_format($bal, 2) . '</u></strong>
                </th>
            </tr>
            </tbody>
            <thead>
                <tr>
                    <th colspan="5">ASSESSMENT</th>
                </tr>
            </thead>
            <tbody>';
            if ($studinfo->levelid == 14 || $studinfo->levelid == 15) {
                //   $getPaySched = db::select('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                //       from studpayscheddetail
                //       where studid = ? and syid = ? and semid = ? and deleted = 0
                //       group by MONTH(duedate)
                //       order by duedate', [$studid, $syid, $semid]);

                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate'))
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function ($q) use ($semid) {
                        if (DB::table('schoolinfo')->first()->shssetup == 0) {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('deleted', 0)
                    ->groupBy(db::raw('MONTH(duedate)'))
                    ->get();

            } else if ($studinfo->levelid >= 17 && $studinfo->levelid <= 20) {

                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
                    ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('studpayscheddetail.deleted', 0)
                    ->groupBy(db::raw('MONTH(duedate)'))
                    ->get();
            } else {

                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
                    ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('studpayscheddetail.deleted', 0)
                    ->groupBy(db::raw('MONTH(duedate)'))
                    ->get();
            }
            $monthsetup = DB::table('monthsetup')
                ->get();

            if (count($getPaySched) > 0) {
                foreach ($getPaySched as $eachpaysched) {
                    if ($eachpaysched->duedate == null) {
                        $eachpaysched->monthid = 0;
                        $eachpaysched->monthnumber = 0;
                    } else {
                        if (collect($monthsetup)->where('description', strtoupper(strtolower(date('F', strtotime($eachpaysched->duedate)))))->count() > 0) {
                            $eachpaysched->monthid = collect($monthsetup)->where('description', strtoupper(strtolower(date('F', strtotime($eachpaysched->duedate)))))->first()->id;
                        } else {
                            $eachpaysched->monthid = 0;
                        }
                        if (strlen(date_parse(date('F', strtotime($eachpaysched->duedate)))['month']) == 1) {
                            $eachpaysched->monthnumber = '0' . date_parse(date('F', strtotime($eachpaysched->duedate)))['month'];
                        } else {
                            $eachpaysched->monthnumber = date_parse(date('F', strtotime($eachpaysched->duedate)))['month'];
                        }
                    }
                }
            }
            $getPaySched = collect($getPaySched)->sortBy('monthid')->sortBy('monthnum')->values();
            // return $getPaySched;
            $assessbilling = 0;
            $assesspayment = 0;
            $assessbalance = 0;
            $totalBal = collect($getPaySched)->sum('balance');

            if (count($getPaySched) > 0) {
                foreach ($getPaySched as $key => $psched) {
                    if ($psched->monthid <= $monthsetupid) {
                        if ($monthsetupid == 0) {
                            $assessbilling += $psched->amountdue;
                            $assesspayment += $psched->amountpay;
                            $assessbalance += $psched->balance;

                            $m = date_create($psched->duedate);
                            $f = date_format($m, 'F');
                            $m = date_format($m, 'm');

                            if ($psched->duedate != '') {
                                $particulars = 'PAYABLES FOR ' . strtoupper($f);
                            } else {
                                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'faai') {
                                    $particulars = 'REGISTRATION/MISCELLANEOUS/BOOKS/GENYO';
                                } else {
                                    $particulars = 'ONE-TIME PAYMENT';
                                }
                                $m = 0;
                            }
                            $output .= '
                                <tr>
                                <td></td>
                                <td>' . $particulars . '</td>
                                <td class="text-right">' . number_format($psched->amountdue, 2) . '</td>
                                <td class="text-right">' . number_format($psched->amountpay, 2) . '</td>
                                <td class="text-right">' . number_format($psched->balance, 2) . '</td>
                                </tr>
                            ';
                        } else {
                            $assessbilling += $psched->amountdue;
                            $assesspayment += $psched->amountpay;
                            $assessbalance += $psched->balance;

                            $m = date_create($psched->duedate);
                            $f = date_format($m, 'F');
                            $m = date_format($m, 'm');
                            if ($psched->duedate != '') {
                                $particulars = 'PAYABLES FOR ' . strtoupper($f);
                            } else {
                                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'faai') {
                                    $particulars = 'REGISTRATION/MISCELLANEOUS/BOOKS/GENYO';
                                } else {
                                    $particulars = 'ONE-TIME PAYMENT';
                                }
                                $m = 0;
                            }

                            $arraymonthsetups = collect($monthsetup)->where('id', '<=', $monthsetupid)->values();
                            if (count($arraymonthsetups) > 0) {
                                if ($psched->monthid == 0) {
                                    $output .= '
                                    <tr>
                                    <td></td>
                                        <td>' . $particulars . '</td>
                                        <td class="text-right">' . number_format($psched->amountdue, 2) . '</td>
                                        <td class="text-right">' . number_format($psched->amountpay, 2) . '</td>
                                        <td class="text-right">' . number_format($psched->balance, 2) . '</td>
                                    </tr>
                                    ';
                                } else {
                                    if (collect($arraymonthsetups)->where('id', $psched->monthid)->count() > 0) {
                                        $output .= '
                                        <tr>
                                        <td></td>
                                            <td>' . $particulars . '</td>
                                            <td class="text-right">' . number_format($psched->amountdue, 2) . '</td>
                                            <td class="text-right">' . number_format($psched->amountpay, 2) . '</td>
                                            <td class="text-right">' . number_format($psched->balance, 2) . '</td>
                                        </tr>
                                        ';

                                    }

                                }
                            }
                        }
                    }
                }


                $output .= '
                    <tr style="background-color:#007bff91">
                        <th></th>
                        <th style="text-align:right">
                            <strong>TOTAL:<strong>
                        </th>
                        <th class="text-right">
                            <strong><u>' . number_format($assessbilling, 2) . '</u></strong>
                        </th>
                        <th class="text-right">
                            <strong><u>' . number_format($assesspayment, 2) . '</u></strong>
                        </th>
                        <th class="text-right">
                            <strong><u>' . number_format($assessbalance, 2) . '</u></strong>
                        </th>
                    </tr>
                    <tr style="background-color:#ffc1078c">
                        <th></th>
                        <th style="text-align:right">
                            <strong>TOTAL BALANCE:<strong>
                        </th>
                        <th class="text-right">
                            <strong><u>' . number_format($assessbilling, 2) . '</u></strong>
                        </th>
                        <th class="text-right">
                            <strong><u>' . number_format($assesspayment, 2) . '</u></strong>
                        </th>
                        <th class="text-right">
                            <strong><u>' . number_format(($assessbalance), 2) . '</u></strong>
                        </th>
                    </tr>
                    <tr style="background-color:#ffc1078c">
                        <th></th>
                        <th style="text-align:right">
                            <strong>TOTAL AMOUNT DUE:<strong>
                        </th>
                        <th class="text-right">


                        </th>
                        <th class="text-right">

                        </th>
                        <th class="text-right" style="font-size:">
                            <h4><strong><u>' . number_format(($assessbalance), 2) . '</u></strong></h4>
                        </th>
                    </tr>
                </tbody>
                </table>';

            } else {

                $output .= '
                <tr style="background-color:#ffc1078c">
                    <th></th>
                    <th style="text-align:right">
                        <strong>TOTAL BALANCE:<strong>
                    </th>
                    <th class="text-right">
                        <strong><u>' . number_format($debit, 2) . '</u></strong>
                    </th>
                    <th class="text-right">
                        <strong><u>' . number_format($credit, 2) . '</u></strong>
                    </th>
                    <th class="text-right">
                        <strong><u>' . number_format($bal, 2) . '</u></strong>
                    </th>
                </tr>
                <tr style="background-color:#ffc1078c">
                    <th></th>
                    <th style="text-align:right">
                        <strong>TOTAL AMOUNT DUE:<strong>
                    </th>
                    <th class="text-right">


                    </th>
                    <th class="text-right">

                    </th>
                    <th class="text-right" style="font-size:">
                        <h4><strong><u>' . number_format($totalBal, 2) . '</u></strong></h4>
                    </th>
                </tr>
              </tbody>
              </table>';
            }
        }

        return $output;
    }

    // public function getaccount_v2(Request $request)
    // {
    //     $studid = $request->get('studid');
    //     $semid = $request->get('selectedsemester');
    //     $syid = $request->get('selectedschoolyear');
    //     $monthsetupid = $request->get('selectedmonth');
    //     $month  = null;
    //     if($request->get('selectedmonth') > 0)
    //     {
    //         if(date_parse(DB::table('monthsetup')->where('id',$monthsetupid)->first()->description)['month'])
    //         {
    //             if(strlen(date_parse(DB::table('monthsetup')->where('id',$monthsetupid)->first()->description)['month']) == 1)
    //             {
    //                 $month      = '0'.date_parse(DB::table('monthsetup')->where('id',$monthsetupid)->first()->description)['month'];
    //             }else{
    //                 $month      =date_parse(DB::table('monthsetup')->where('id',$monthsetupid)->first()->description)['month'];
    //             }
    //         }
    //     }

    //     $studinfo = db::table('studinfo')
    //         ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'sectionname', 'levelid')
    //         ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //         ->where('studinfo.id', $studid)
    //         ->first();

    //     if($studinfo->levelid == 14 || $studinfo->levelid == 15)
    //     {
    //         $ledger = db::table('studledger')
    //             ->select('studledger.*','chrngsetup.groupname')
    //             ->leftJoin('chrngsetup','studledger.classid','=','chrngsetup.classid')
    //             ->where('studledger.studid', $studid)
    //             ->where('studledger.syid', $syid)
    //             ->where(function($q) use($semid){
    //                 if(DB::table('schoolinfo')->first()->shssetup == 0)
    //                 {
    //                     $q->where('semid', $semid);
    //                 }
    //             })
    //             ->where('studledger.void', 0)
    //             ->where('studledger.deleted', 0)
    //             ->orderBy('studledger.id', 'asc')
    //             ->get();
    //     }
    //     elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
    //     {
    //         $ledger = db::table('studledger')
    //             ->select('studledger.*','chrngsetup.groupname')
    //             ->leftJoin('chrngsetup','studledger.classid','=','chrngsetup.classid')
    //             ->where('studledger.studid', $studid)
    //             ->where('studledger.syid', $syid)
    //             ->where('studledger.semid', $semid)
    //             ->where('studledger.deleted', 0)
    //             ->where('studledger.void', 0)
    //             ->orderBy('studledger.id', 'asc')
    //             ->get();
    //     }
    //     else
    //     {
    //         $ledger = db::table('studledger')
    //             ->select('studledger.*','chrngsetup.groupname')
    //             ->leftJoin('chrngsetup','studledger.classid','=','chrngsetup.classid')
    //             ->where('studledger.studid', $studid)
    //             ->where('studledger.syid', $syid)
    //             ->where('studledger.deleted', 0)
    //             ->where('studledger.void', 0)
    //             ->orderBy('studledger.id', 'asc')
    //             ->get();
    //     }
    //     // if($studinfo->levelid == 14 || $studinfo->levelid == 15)
    //     // {

    //     // $ledger = db::table('studledger')
    //     //     ->where('studid', $studid)
    //     //     ->where('syid', $syid)
    //     //     ->where(function($q) use($semid){
    //     //         if(DB::table('schoolinfo')->first()->shssetup == 0)
    //     //         {
    //     //             $q->where('semid', $semid);
    //     //         }
    //     //     })
    //     //     ->where('void', 0)
    //     //     ->where('deleted', 0)
    //     //     ->orderBy('id', 'asc')
    //     //     ->get();
    //     // }
    //     // elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
    //     // {
    //     // $ledger = db::table('studledger')
    //     //     ->where('studid', $studid)
    //     //     ->where('syid', $syid)
    //     //     ->where('semid', $semid)
    //     //     ->where('void', 0)
    //     //     ->where('deleted', 0)
    //     //     ->orderBy('id', 'asc')
    //     //     ->get();
    //     // }
    //     // else
    //     // {
    //     // $ledger = db::table('studledger')
    //     //     ->where('studid', $studid)
    //     //     ->where('syid', $syid)
    //     //     ->where('void', 0)
    //     //     ->where('deleted', 0)
    //     //     ->orderBy('id', 'asc')
    //     //     ->get();
    //     // }

    //     $bal = 0;
    //     $debit = 0;
    //     $credit = 0;

    //     if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
    //     {

    //         foreach($ledger as $led)
    //         {
    //             $debit += $led->amount;

    //             if($led->void == 0)
    //             {
    //                 $credit += $led->payment;
    //             }

    //             $lDate = date_create($led->createddatetime);
    //             $lDate = date_format($lDate, 'm-d-Y');

    //             if($led->amount > 0)
    //             {
    //                 $amount = number_format($led->amount,2);
    //             }
    //             else
    //             {
    //                 $amount = '';
    //             }

    //             if($led->payment > 0)
    //             {
    //                 $payment = number_format($led->payment,2);
    //             }
    //             else
    //             {
    //                 $payment = '';
    //             }

    //             if($led->void == 0)
    //             {
    //                 $bal += $led->amount - $led->payment;
    //             }

    //         }
    //         if($studinfo->levelid == 14 || $studinfo->levelid == 15)
    //         {
    //             $getPaySched = db::table('studpayscheddetail')
    //                 ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
    //                 ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
    //                 ->where('studid', $studid)
    //                 ->where('syid', $syid)
    //                 ->where(function($q) use($semid){
    //                     if(db::table('schoolinfo')->first()->shssetup == 0)
    //                     {
    //                         $q->where('semid', $semid);
    //                     }
    //                 })
    //                 ->where('studpayscheddetail.deleted', 0)

    //                 ->groupBy(db::raw('MONTH(duedate)'))
    //                 ->get();
    //                 // return $getPaySched;

    //         }
    //         else if($studinfo->levelid >= 17 && $studinfo->levelid <= 20)
    //         {

    //             $getPaySched = db::table('studpayscheddetail')
    //             ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
    //                 ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
    //                 ->where('studid', $studid)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 ->where('studpayscheddetail.deleted', 0)
    //                 ->groupBy(db::raw('MONTH(duedate)'))
    //                 ->get();
    //             // $getPaySched = db::table('studpayscheddetail')
    //             // ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
    //             //     ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
    //             //     ->where('studid', $studid)
    //             //     ->where('syid', $syid)
    //             //     ->where('semid', $semid)
    //             //     ->where('deleted', 0)
    //             //     ->groupBy(db::raw('MONTH(duedate)'))
    //             //     ->get();
    //         }
    //         else
    //         {
    //             // $getPaySched = db::select('description, sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
    //             //     from studpayscheddetail
    //             //     INNER JOIN itemclassification
    //             //     ON studpayscheddetail.`classid` = itemclassification.id
    //             //     where studid = ? and syid = ? and studpayscheddetail.deleted = 0
    //             //     group by MONTH(duedate)
    //             //     order by duedate', [$studid, $syid]);

    //             $getPaySched = db::table('studpayscheddetail')
    //                 ->select(db::raw('description1, sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate'))
    //                     ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
    //                     ->where('studid', $studid)
    //                     ->where('syid', $syid)
    //                     ->groupBy(db::raw('month(duedate)'))
    //                     ->orderBy('duedate');
    //         }
    //         $assessbilling = 0;
    //         $assesspayment = 0;
    //         $assessbalance = 0;
    //         $totalBal = collect($getPaySched)->sum('balance');;

    //         if(count($getPaySched) > 0)
    //         {
    //             foreach($getPaySched as $psched)
    //             {

    //                 // return $getPaySched;
    //                 // $totalBal += $psched->balance;
    //                 $assessbilling += $psched->amountdue;
    //                 $assesspayment += $psched->amountpay;
    //                 $assessbalance += $psched->balance;

    //                 $m = date_create($psched->duedate);
    //                 $f = date_format($m, 'F');
    //                 $m = date_format($m, 'm');

    //                 if($psched->duedate != '')
    //                 {
    //                     $particulars = 'PAYABLES FOR ' . strtoupper($f);
    //                 }
    //                 else
    //                 {
    //                     if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'faai')
    //                     {
    //                         $particulars = 'REGISTRATION/MISCELLANEOUS/BOOKS/GENYO';
    //                     }else{
    //                         $particulars = 'ONE-TIME PAYMENT';
    //                     }
    //                     $m = 0;
    //                 }
    //             }


    //             $monthname = date('M', strtotime('2020-'.$month));
    //             return view('finance.statementofaccount.table_xai')
    //                 ->with('studinfo', $studinfo)
    //                 ->with('monthname', $monthname)
    //                 ->with('ledger', $ledger)
    //                 ->with('getPaySched', $getPaySched);



    //         }else{

    //             $monthname = date('M', strtotime('2020-'.$month));
    //             return view('finance.statementofaccount.table_xai')
    //                 ->with('studinfo', $studinfo)
    //                 ->with('monthname', $monthname)
    //                 ->with('ledger', $ledger)
    //                 ->with('getPaySched', $getPaySched);
    //         }

    //     }else{

    //         $output = '<table class="table table-bordered" style="font-size: 12px;">
    //                     <thead>
    //                         <tr>
    //                             <th colspan="5">LEDGER</th>
    //                         </tr>
    //                         <tr>
    //                             <th>Date</th>
    //                             <th>Description</th>
    //                             <th>Billing</th>
    //                             <th>Payment</th>
    //                             <th>Balance</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>';

    //         foreach($ledger as $led)
    //         {
    //             $debit += $led->amount;

    //             if($led->void == 0)
    //             {
    //                 $credit += $led->payment;
    //             }

    //             $lDate = date_create($led->createddatetime);
    //             $lDate = date_format($lDate, 'm-d-Y');

    //             if($led->amount > 0)
    //             {
    //                 $amount = number_format($led->amount,2);
    //             }
    //             else
    //             {
    //                 $amount = '';
    //             }

    //             if($led->payment > 0)
    //             {
    //                 $payment = number_format($led->payment,2);
    //             }
    //             else
    //             {
    //                 $payment = '';
    //             }

    //             if($led->void == 0)
    //             {
    //                 $bal += $led->amount - $led->payment;
    //             }

    //             if($led->void == 0)
    //             {
    //                 $output .='
    //                 <tr>
    //                     <td>' .$lDate.' </td>
    //                     <td>'.$led->particulars.'</td>
    //                     <td class="text-right">'.$amount.'</td>
    //                     <td class="text-right">'.$payment.'</td>
    //                     <td class="text-right">'.number_format($bal, 2).'</td>
    //                 </tr>
    //                 ';
    //             }
    //             else
    //             {
    //                 $output .='
    //                 <tr>
    //                     <td class="text-danger"><del>' .$lDate.' </del></td>
    //                     <td class="text-danger"><del>'.$led->particulars.'</del></td>
    //                     <td class="text-right text-danger"><del>'.$amount.'</del></td>
    //                     <td class="text-right text-danger"><del>'.$payment.'</del></td>
    //                     <td class="text-right text-danger"><del>'.number_format($bal, 2).'</del></td>
    //                 </tr>
    //                 ';
    //             }

    //         }

    //         $output .='
    //         <tr style="background-color:#007bff91">
    //             <th></th>
    //             <th style="text-align:right">
    //                 <strong>TOTAL:<strong>
    //             </th>
    //             <th class="text-right">
    //                 <strong><u>'.number_format($debit, 2).'</u></strong>
    //             </th>
    //             <th class="text-right">
    //                 <strong><u>'.number_format($credit, 2).'</u></strong>
    //             </th>
    //             <th class="text-right">
    //                 <strong><u>'.number_format($bal, 2).'</u></strong>
    //             </th>
    //         </tr>
    //         </tbody>
    //         <thead>
    //             <tr>
    //                 <th colspan="5">ASSESSMENT</th>
    //             </tr>
    //         </thead>
    //         <tbody>';

    //         $_monthid = db::table('monthsetup')->where('id', $monthsetupid)->first()->monthid;

    //         $assessment = FinanceUtilityModel::assessment_gen($studid, $syid, $semid, $_monthid);
    //         $a_totalbill = 0;
    //         $a_totalpay = 0;
    //         $a_totalbalance = 0;

    //         foreach($assessment as $sched)
    //         {
    //             $_particulars = strtoupper($sched->particulars . ' PAYABLES');
    //             $a_totalbill += $sched->amount;
    //             $a_totalpay += $sched->payment;
    //             $a_totalbalance += $sched->balance;

    //             $output .='
    //                 <tr>
    //                     <td></td>
    //                     <td>'.$_particulars.'</td>
    //                     <td class="text-right">'.number_format($sched->amount, 2).'</td>
    //                     <td class="text-right">'.number_format($sched->payment, 2).'</td>
    //                     <td class="text-right">'.number_format($sched->balance, 2).'</td>
    //                 </tr>
    //             ';
    //         }

    //         $output .='
    //             <tr style="background-color:#007bff91">
    //                 <th></th>
    //                 <th style="text-align:right">
    //                     <strong>TOTAL:<strong>
    //                 </th>
    //                 <th class="text-right">
    //                     <strong><u>'.number_format($a_totalbill, 2).'</u></strong>
    //                 </th>
    //                 <th class="text-right">
    //                     <strong><u>'.number_format($a_totalpay, 2).'</u></strong>
    //                 </th>
    //                 <th class="text-right">
    //                     <strong><u>'.number_format($a_totalbalance, 2).'</u></strong>
    //                 </th>
    //             </tr>
    //             </tbody>
    //         ';
    //     }


    //     return $output;
    // }

    // public function getaccount_v2(Request $request)
    // {
    //     $studid = $request->get('studid');
    //     $semid = $request->get('selectedsemester');
    //     $syid = $request->get('selectedschoolyear');
    //     $monthsetupid = $request->get('selectedmonth');
    //     $action = $request->get('action');
    //     $month  = null;
    //     $levelid = 0;
    //     $sectionid = 0;
    //     $courseid = 0;
    //     $sectionname = '';
    //     $courseabrv = '';


    //     // return $request;

    //     if($request->get('selectedmonth') > 0)
    //     {
    //         if(date_parse(DB::table('monthsetup')->where('id',$monthsetupid)->first()->description)['month'])
    //         {
    //             if(strlen(date_parse(DB::table('monthsetup')->where('id',$monthsetupid)->first()->description)['month']) == 1)
    //             {
    //                 $month      = '0'.date_parse(DB::table('monthsetup')->where('id',$monthsetupid)->first()->description)['month'];
    //             }else{
    //                 $month      =date_parse(DB::table('monthsetup')->where('id',$monthsetupid)->first()->description)['month'];
    //             }
    //         }
    //     }

    //     $request['syid'] = $syid;
    //     $request['semid'] = $semid;
    //     $request['monthid'] = $month;

    //     $selectedschoolyear = DB::table('sy')
    //         ->where('id', $syid)
    //         ->first()
    //         ->sydesc;

    //     $selectedsemester = db::table('semester')
    //         ->where('id', $semid)
    //         ->first()
    //         ->semester;

    //     $studinfo = db::table('studinfo')
    //         ->select('id', 'sid', 'lastname', 'firstname', 'middlename', 'suffix')
    //         ->where('studinfo.id', $studid)
    //         ->first();

    //     $estud = db::table('enrolledstud')
    //         ->select('levelid', 'sectionid', 'studstatus')
    //         ->where('studid', $studid)
    //         ->where('syid', $syid)
    //         ->where('deleted', 0)
    //         ->first();

    //     if($estud)
    //     {
    //         $levelid = $estud->levelid;
    //         $sectionid = $estud->sectionid;

    //         $section = db::table('sections')
    //             ->where('id', $sectionid)
    //             ->first();

    //         if($section)
    //         {
    //             $sectionname = $section->sectionname;
    //         }

    //     }
    //     else{
    //         $estud = db::table('sh_enrolledstud')
    //             ->select('levelid', 'sectionid', 'studstatus')
    //             ->where('studid', $studid)
    //             ->where('deleted', 0)
    //             ->first();

    //         if($estud)
    //         {
    //             $levelid = $estud->levelid;
    //             $sectionid = $estud->sectionid;

    //             $section = db::table('sections')
    //                 ->where('id', $sectionid)
    //                 ->first();

    //             if($section)
    //             {
    //                 $sectionname = $section->sectionname;
    //             }

    //         }
    //         else{
    //             $estud = db::table('college_enrolledstud')
    //                 ->select('yearLevel as levelid', 'courseid', 'studstatus')
    //                 ->where('studid', $studid)
    //                 ->where('deleted', 0)
    //                 ->first();

    //             if($estud)
    //             {
    //                 $levelid = $estud->levelid;
    //                 $courseid = $estud->courseid;

    //                 $courses = db::table('college_courses')
    //                     ->where('id', $courseid)
    //                     ->first();

    //                 if($courses)
    //                 {
    //                     $courseabrv = $courses->courseabrv;
    //                 }
    //             }
    //             else{
    //                 $estud = db::table('studinfo')
    //                     ->select('levelid', 'studstatus')
    //                     ->where('id', $studid)
    //                     ->first();

    //                 if($estud)
    //                 {
    //                     $levelid = $estud->levelid;
    //                     $secionid = 0;
    //                 }
    //             }
    //         }
    //     }


    //     if($levelid == 14 || $levelid == 15)
    //     {
    //         $ledger = db::table('studledger')
    //             ->select('studledger.*','chrngsetup.groupname')
    //             ->leftJoin('chrngsetup','studledger.classid','=','chrngsetup.classid')
    //             ->where('studledger.studid', $studid)
    //             ->where('studledger.syid', $syid)
    //             ->where('studledger.amount', '>', 0)
    //             ->where(function($q) use($semid){
    //                 if(DB::table('schoolinfo')->first()->shssetup == 0)
    //                 {
    //                     $q->where('semid', $semid);
    //                 }
    //             })
    //             ->where('studledger.void', 0)
    //             ->where('studledger.deleted', 0)
    //             ->orderBy('studledger.createddatetime', 'asc')
    //             ->get();
    //     }
    //     elseif($levelid >= 17 && $levelid <= 21)
    //     {
    //         $ledger = db::table('studledger')
    //             ->select('studledger.*','chrngsetup.groupname')
    //             ->leftJoin('chrngsetup','studledger.classid','=','chrngsetup.classid')
    //             ->where('studledger.studid', $studid)
    //             ->where('studledger.syid', $syid)
    //             ->where('studledger.semid', $semid)
    //             ->where('studledger.classid', '!=', null)
    //             ->where('studledger.amount', '>', 0)
    //             ->where('studledger.deleted', 0)
    //             ->where('studledger.void', 0)
    //             ->orderBy('studledger.createddatetime', 'asc')
    //             ->get();
    //     }
    //     else
    //     {
    //         $ledger = db::table('studledger')
    //             ->select('studledger.*','chrngsetup.groupname')
    //             ->leftJoin('chrngsetup','studledger.classid','=','chrngsetup.classid')
    //             ->where('studledger.studid', $studid)
    //             ->where('studledger.syid', $syid)
    //             ->where('studledger.deleted', 0)
    //             ->where('studledger.void', 0)
    //             ->where('studledger.amount', '>', 0)
    //             ->orderBy('studledger.createddatetime', 'asc')
    //             ->get();
    //     }


    //     $bal = 0;
    //     $debit = 0;
    //     $credit = 0;

    //     if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
    //     {

    //         foreach($ledger as $led)
    //         {
    //             $debit += $led->amount;

    //             if($led->void == 0)
    //             {
    //                 $credit += $led->payment;
    //             }

    //             $lDate = date_create($led->createddatetime);
    //             $lDate = date_format($lDate, 'm-d-Y');

    //             if($led->amount > 0)
    //             {
    //                 $amount = number_format($led->amount,2);
    //             }
    //             else
    //             {
    //                 $amount = '';
    //             }

    //             if($led->payment > 0)
    //             {
    //                 $payment = number_format($led->payment,2);
    //             }
    //             else
    //             {
    //                 $payment = '';
    //             }

    //             if($led->void == 0)
    //             {
    //                 $bal += $led->amount - $led->payment;
    //             }

    //         }
    //         if($studinfo->levelid == 14 || $studinfo->levelid == 15)
    //         {
    //             $getPaySched = db::table('studpayscheddetail')
    //                 ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
    //                 ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
    //                 ->where('studid', $studid)
    //                 ->where('syid', $syid)
    //                 ->where(function($q) use($semid){
    //                     if(db::table('schoolinfo')->first()->shssetup == 0)
    //                     {
    //                         $q->where('semid', $semid);
    //                     }
    //                 })
    //                 ->where('studpayscheddetail.deleted', 0)

    //                 ->groupBy(db::raw('MONTH(duedate)'))
    //                 ->get();
    //                 // return $getPaySched;

    //         }
    //         else if($studinfo->levelid >= 17 && $studinfo->levelid <= 20)
    //         {

    //             $getPaySched = db::table('studpayscheddetail')
    //             ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
    //                 ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
    //                 ->where('studid', $studid)
    //                 ->where('syid', $syid)
    //                 ->where('semid', $semid)
    //                 ->where('studpayscheddetail.deleted', 0)
    //                 ->groupBy(db::raw('MONTH(duedate)'))
    //                 ->get();
    //             // $getPaySched = db::table('studpayscheddetail')
    //             // ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
    //             //     ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
    //             //     ->where('studid', $studid)
    //             //     ->where('syid', $syid)
    //             //     ->where('semid', $semid)
    //             //     ->where('deleted', 0)
    //             //     ->groupBy(db::raw('MONTH(duedate)'))
    //             //     ->get();
    //         }
    //         else
    //         {
    //             // $getPaySched = db::select('description, sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
    //             //     from studpayscheddetail
    //             //     INNER JOIN itemclassification
    //             //     ON studpayscheddetail.`classid` = itemclassification.id
    //             //     where studid = ? and syid = ? and studpayscheddetail.deleted = 0
    //             //     group by MONTH(duedate)
    //             //     order by duedate', [$studid, $syid]);

    //             $getPaySched = db::table('studpayscheddetail')
    //                 ->select(db::raw('description1, sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate'))
    //                     ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
    //                     ->where('studid', $studid)
    //                     ->where('syid', $syid)
    //                     ->groupBy(db::raw('month(duedate)'))
    //                     ->orderBy('duedate');
    //         }
    //         $assessbilling = 0;
    //         $assesspayment = 0;
    //         $assessbalance = 0;
    //         $totalBal = collect($getPaySched)->sum('balance');;

    //         if(count($getPaySched) > 0)
    //         {
    //             foreach($getPaySched as $psched)
    //             {

    //                 // return $getPaySched;
    //                 // $totalBal += $psched->balance;
    //                 $assessbilling += $psched->amountdue;
    //                 $assesspayment += $psched->amountpay;
    //                 $assessbalance += $psched->balance;

    //                 $m = date_create($psched->duedate);
    //                 $f = date_format($m, 'F');
    //                 $m = date_format($m, 'm');

    //                 if($psched->duedate != '')
    //                 {
    //                     $particulars = 'PAYABLES FOR ' . strtoupper($f);
    //                 }
    //                 else
    //                 {
    //                     if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'faai')
    //                     {
    //                         $particulars = 'REGISTRATION/MISCELLANEOUS/BOOKS/GENYO';
    //                     }else{
    //                         $particulars = 'ONE-TIME PAYMENT';
    //                     }
    //                     $m = 0;
    //                 }
    //             }


    //             $monthname = date('M', strtotime('2020-'.$month));
    //             return view('finance.statementofaccount.table_xai')
    //                 ->with('studinfo', $studinfo)
    //                 ->with('monthname', $monthname)
    //                 ->with('ledger', $ledger)
    //                 ->with('getPaySched', $getPaySched);



    //         }else{

    //             $monthname = date('M', strtotime('2020-'.$month));
    //             return view('finance.statementofaccount.table_xai')
    //                 ->with('studinfo', $studinfo)
    //                 ->with('monthname', $monthname)
    //                 ->with('ledger', $ledger)
    //                 ->with('getPaySched', $getPaySched);
    //         }

    //     }else{

    //         $output = '<table class="table table-bordered" style="font-size: 12px;">
    //             <thead>
    //                 <tr>
    //                     <th colspan="5">LEDGER</th>
    //                 </tr>
    //                 <tr>
    //                     <th>Date</th>
    //                     <th>Description</th>
    //                     <th>Billing</th>
    //                     <th>Balance</th>
    //                 </tr>
    //             </thead>
    //             <tbody>';

    //         foreach($ledger as $led)
    //         {
    //             $debit += $led->amount;

    //             if($led->void == 0)
    //             {
    //                 $credit += $led->payment;
    //             }

    //             $lDate = date_create($led->createddatetime);
    //             $lDate = date_format($lDate, 'm-d-Y');

    //             if($led->amount > 0)
    //             {
    //                 $amount = number_format($led->amount,2);
    //             }
    //             else
    //             {
    //                 $amount = '';
    //             }

    //             if($led->payment > 0)
    //             {
    //                 $payment = number_format($led->payment,2);
    //             }
    //             else
    //             {
    //                 $payment = '';
    //             }

    //             if($led->void == 0)
    //             {
    //                 $bal += $led->amount - $led->payment;
    //             }

    //             if($led->void == 0)
    //             {
    //                 $output .='
    //                 <tr>
    //                     <td>' .$lDate.' </td>
    //                     <td>'.$led->particulars.'</td>
    //                     <td class="text-right">'.$amount.'</td>
    //                     <td class="text-right">'.number_format($bal, 2).'</td>
    //                 </tr>
    //                 ';
    //             }
    //             else
    //             {
    //                 $output .='
    //                 <tr>
    //                     <td class="text-danger"><del>' .$lDate.' </del></td>
    //                     <td class="text-danger"><del>'.$led->particulars.'</del></td>
    //                     <td class="text-right text-danger"><del>'.$amount.'</del></td>
    //                     <td class="text-right text-danger"><del>'.number_format($bal, 2).'</del></td>
    //                 </tr>
    //                 ';
    //             }

    //         }

    //         $output .='
    //         <tr style="background-color:#007bff91">
    //             <th></th>
    //             <th style="text-align:right">
    //                 <strong>TOTAL:<strong>
    //             </th>
    //             <th class="text-right">
    //                 <strong><u>'.number_format($debit, 2).'</u></strong>
    //             </th>
    //             <th class="text-right">
    //                 <strong><u>'.number_format($bal, 2).'</u></strong>
    //             </th>
    //         </tr>
    //         </tbody>
    //         <thead>
    //             <tr>
    //                 <th colspan="5">PAYMENT</th>
    //             </tr>
    //         </thead>
    //         <tbody>';
    //         $output .='
    //             <tr>
    //                 <th>Date</th>
    //                 <th>Description</th>
    //                 <th>Payment</th>
    //                 <th>Balance</th>
    //             </tr>
    //         ';

    //         // $assessment = FinanceUtilityModel::assessment_gen($studid, $syid, $semid, $_monthid);
    //         $a_totalbill = 0;
    //         $a_totalpay = 0;
    //         $a_totalbalance = 0;
    //         $paybal = $bal;

    //         $payments = db::table('studledger')
    //             ->select('studledger.*','chrngsetup.groupname')
    //             ->leftJoin('chrngsetup','studledger.classid','=','chrngsetup.classid')
    //             ->where('studledger.studid', $studid)
    //             ->where('studledger.syid', $syid)
    //             ->where(function($q) use($semid, $levelid){
    //                 if($levelid == 14 || $levelid == 15)
    //                 {
    //                     if($semid == 3)
    //                     {
    //                         $q->where('semid', 3);
    //                     }
    //                     else{
    //                         if(DB::table('schoolinfo')->first()->shssetup == 0)
    //                         {
    //                             $q->where('semid', $semid);
    //                         }
    //                         else{
    //                             $q->where('semid', '!=', 3);
    //                         }
    //                     }
    //                 }
    //                 elseif($levelid >= 17 && $levelid <= 21)
    //                 {
    //                     $q->where('semid', $semid);
    //                 }
    //             })
    //             ->where('payment', '>', 0)
    //             ->where('studledger.deleted', 0)
    //             ->where('studledger.void', 0)
    //             ->orderBy('studledger.createddatetime', 'asc')
    //             ->get();

    //         foreach($payments as $pay)
    //         {
    //             // $_particulars = strtoupper($sched->particulars . ' PAYABLES');
    //             $paybal -= $pay->payment;
    //             $a_totalpay += $pay->payment;
    //             $a_totalbalance += $pay->payment;

    //             $lDate = date_create($pay->createddatetime);
    //             $lDate = date_format($lDate, 'm-d-Y');

    //             $output .='
    //                 <tr>
    //                     <td>' .$lDate.' </td>
    //                     <td>'.$pay->particulars.'</td>
    //                     <td class="text-right">'.number_format($pay->payment, 2).'</td>
    //                     <td class="text-right">'.number_format($paybal, 2).'</td>
    //                 </tr>
    //             ';
    //         }

    //         $output .='
    //             <tr style="background-color:#007bff91">
    //                 <th></th>
    //                 <th style="text-align:right">
    //                     <strong>TOTAL:<strong>
    //                 </th>
    //                 <th class="text-right">
    //                     <strong><u>'.number_format($a_totalpay, 2).'</u></strong>
    //                 </th>
    //                 <th class="text-right">
    //                     <strong><u>'.number_format($paybal, 2).'</u></strong>
    //                 </th>
    //             </tr>
    //             </tbody>
    //         ';

    //         //DUE FOR THE MONTH//
    //         $monthinword = '';
    //         $monthsetup = db::table('monthsetup')->where('id', $monthsetupid)->first();

    //         if($monthsetup)
    //         {
    //             $monthinword = $monthsetup->description;
    //         }
    //         else{
    //             $monthinword = '';
    //         }


    //         $monthdue = UtilityController::assessment_gen($request);
    //         // $monthdue =FinanceUtilityModel::assessment_gen($studid, $syid, $semid, $monthsetup);
    //         $totaldue = 0;

    //         // return $monthdue;
    //         if($monthdue)
    //         {
    //             foreach($monthdue as $_due)
    //             {
    //                 // return $_due->amount;
    //                 $totaldue += str_replace(',', '', $_due->amount);
    //             }
    //         }

    //         $output .='
    //             <thead>
    //                 <tr style="background-color:#007bff91">
    //                     <th colspan="3" class="text-right text-lg">DUE FOR THE MONTH OF ' . strtoupper($monthinword) . ' </th>
    //                     <th class="text-right text-lg">'.number_format($totaldue, 2).'</th>
    //                 </tr>
    //             </thead>
    //         ';








    //     }

    //     if($action == 'generate')
    //     {
    //         return $output;
    //     }
    //     elseif($action == 'ep')
    //     {
    //         return $totaldue;
    //     }
    //     else{
    //         $pdf = new MYPDFStatementOfAccount(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    //         // $pdf->SetCreator('CK');
    //         // $pdf->SetAuthor('CK Children\'s Publishing');
    //         // $pdf->SetTitle(DB::table('schoolinfo')->first()->schoolname.' - Statement of Account');
    //         // $pdf->SetSubject('Statement of Account');

    //         // set header and footer fonts
    //         // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    //         // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    //         // // set default monospaced font
    //         // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    //         // set margins
    //         $pdf->SetMargins(3, 10, 5);
    //         $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //         $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    //         $pdf->setPrintFooter(false);


    //         // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
    //         // set auto page breaks
    //         $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    //         // set image scale factor
    //         $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //         // set some language-dependent strings (optional)
    //         if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    //             require_once(dirname(__FILE__).'/lang/eng.php');
    //             $pdf->setLanguageArray($l);
    //         }

    //         // if(strtolower($schoolinfo->abbreviation) == 'apmc')
    //         // {
    //         //     $pdf->setPrintHeader(false);
    //         // }


    //         // ---------------------------------------------------------

    //         // set font
    //         // $pdf->SetFont('dejavusans', '', 10);


    //         // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //         // Print a table

    //         $pdf->AddPage('8.5x11');



    //         $pdf = PDF::loadView('finance/reports/pdf/pdf_statementofacct_default_v2', compact('ledger', 'payments', 'monthsetup', 'monthdue', 'selectedschoolyear', 'selectedsemester', 'monthinword', 'studinfo', 'levelid', 'courseabrv', 'sectionname'))
    //             ->setPaper('letter');
    //         return $pdf->stream('Statement Of Account.pdf');
    //     }
    // }



    public function getaccount_v2(Request $request)
    {
        $studid = $request->get('studid');
        $semid = $request->get('selectedsemester');
        $syid = $request->get('selectedschoolyear');
        $monthsetupid = $request->get('selectedmonth');
        $action = $request->get('action');
        $month = null;
        $levelid = 0;
        $sectionid = 0;
        $courseid = 0;
        $sectionname = '';
        $courseabrv = '';


       

        if ($request->get('selectedmonth') > 0) {
            if (date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month']) {
                if (strlen(date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month']) == 1) {
                    $month = '0' . date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month'];
                } else {
                    $month = date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month'];
                }
            }
        }

        $request['syid'] = $syid;
        $request['semid'] = $semid;
        $request['monthid'] = $month;

        $selectedschoolyear = DB::table('sy')
            ->where('id', $syid)
            ->first()
            ->sydesc;

        $selectedsemester = db::table('semester')
            ->where('id', $semid)
            ->first()
            ->semester;

        $studinfo = db::table('studinfo')
            ->select('id', 'sid', 'lastname', 'firstname', 'middlename', 'suffix')
            ->where('studinfo.id', $studid)
            ->first();

        $estud = db::table('enrolledstud')
            ->select('levelid', 'sectionid', 'studstatus')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->first();

        if ($estud) {
            $levelid = $estud->levelid;
            $sectionid = $estud->sectionid;

            $section = db::table('sections')
                ->where('id', $sectionid)
                ->first();

            if ($section) {
                $sectionname = $section->sectionname;
            }

        } else {
            $estud = db::table('sh_enrolledstud')
                ->select('levelid', 'sectionid', 'studstatus')
                ->where('studid', $studid)
                ->where('deleted', 0)
                ->first();

            if ($estud) {
                $levelid = $estud->levelid;
                $sectionid = $estud->sectionid;

                $section = db::table('sections')
                    ->where('id', $sectionid)
                    ->first();

                if ($section) {
                    $sectionname = $section->sectionname;
                }

            } else {
                $estud = db::table('college_enrolledstud')
                    ->select('yearLevel as levelid', 'courseid', 'studstatus')
                    ->where('studid', $studid)
                    ->where('deleted', 0)
                    ->first();

                if ($estud) {
                    $levelid = $estud->levelid;
                    $courseid = $estud->courseid;

                    $courses = db::table('college_courses')
                        ->where('id', $courseid)
                        ->first();

                    if ($courses) {
                        $courseabrv = $courses->courseabrv;
                    }
                } else {
                    $estud = db::table('studinfo')
                        ->select('levelid', 'studstatus')
                        ->where('id', $studid)
                        ->first();

                    if ($estud) {
                        $levelid = $estud->levelid;
                        $secionid = 0;
                    }
                }
            }
        }


        if ($levelid == 14 || $levelid == 15) {
            $ledger = db::table('studledger')
                ->select('studledger.*', 'chrngsetup.groupname')
                ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                ->where('studledger.studid', $studid)
                ->where('studledger.syid', $syid)
                ->where('studledger.amount', '>', 0)
                ->where(function ($q) use ($semid) {
                    if (DB::table('schoolinfo')->first()->shssetup == 0) {
                        $q->where('semid', $semid);
                    }
                })
                ->where('studledger.void', 0)
                ->where('studledger.deleted', 0)
                ->orderBy('studledger.createddatetime', 'asc')
                ->get();
        } elseif ($levelid >= 17 && $levelid <= 25) {
            $ledger = db::table('studledger')
                ->select('studledger.*', 'chrngsetup.groupname')
                ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                ->where('studledger.studid', $studid)
                ->where('studledger.syid', $syid)
                ->where('studledger.semid', $semid)
                ->where('studledger.classid', '!=', null)
                ->where('studledger.amount', '>', 0)
                ->where('studledger.deleted', 0)
                ->where('studledger.void', 0)
                ->orderBy('studledger.createddatetime', 'asc')
                ->get();
        } else {
            $ledger = db::table('studledger')
                ->select('studledger.*', 'chrngsetup.groupname')
                ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                ->where('studledger.studid', $studid)
                ->where('studledger.syid', $syid)
                ->where('studledger.deleted', 0)
                ->where('studledger.void', 0)
                ->where('studledger.amount', '>', 0)
                ->orderBy('studledger.createddatetime', 'asc')
                ->get();
        }


        $bal = 0;
        $debit = 0;
        $credit = 0;


        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai') {

            foreach ($ledger as $led) {
                $debit += $led->amount;

                if ($led->void == 0) {
                    $credit += $led->payment;
                }

                $lDate = date_create($led->createddatetime);
                $lDate = date_format($lDate, 'm-d-Y');

                if ($led->amount > 0) {
                    $amount = number_format($led->amount, 2);
                } else {
                    $amount = '';
                }

                if ($led->payment > 0) {
                    $payment = number_format($led->payment, 2);
                } else {
                    $payment = '';
                }

                if ($led->void == 0) {
                    $bal += $led->amount - $led->payment;
                }

            }
            if ($studinfo->levelid == 14 || $studinfo->levelid == 15) {
                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
                    ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function ($q) use ($semid) {
                        if (db::table('schoolinfo')->first()->shssetup == 0) {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('studpayscheddetail.deleted', 0)

                    ->groupBy(db::raw('MONTH(duedate)'))
                    ->get();
                // return $getPaySched;

            } else if ($studinfo->levelid >= 17 && $studinfo->levelid <= 20 || $studinfo->levelid >= 22 && $studinfo->levelid <= 25) {

                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
                    ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('studpayscheddetail.deleted', 0)
                    ->groupBy(db::raw('MONTH(duedate)'))
                    ->get();
                // $getPaySched = db::table('studpayscheddetail')
                // ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
                //     ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
                //     ->where('studid', $studid)
                //     ->where('syid', $syid)
                //     ->where('semid', $semid)
                //     ->where('deleted', 0)
                //     ->groupBy(db::raw('MONTH(duedate)'))
                //     ->get();
            } else {
                // $getPaySched = db::select('description, sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                //     from studpayscheddetail 
                //     INNER JOIN itemclassification 
                //     ON studpayscheddetail.`classid` = itemclassification.id
                //     where studid = ? and syid = ? and studpayscheddetail.deleted = 0
                //     group by MONTH(duedate)
                //     order by duedate', [$studid, $syid]);

                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('description1, sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate'))
                    ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->groupBy(db::raw('month(duedate)'))
                    ->orderBy('duedate');
            }
            $assessbilling = 0;
            $assesspayment = 0;
            $assessbalance = 0;
            $totalBal = collect($getPaySched)->sum('balance');
            ;

            if (count($getPaySched) > 0) {
                foreach ($getPaySched as $psched) {

                    // return $getPaySched;
                    // $totalBal += $psched->balance;
                    $assessbilling += $psched->amountdue;
                    $assesspayment += $psched->amountpay;
                    $assessbalance += $psched->balance;

                    $m = date_create($psched->duedate);
                    $f = date_format($m, 'F');
                    $m = date_format($m, 'm');

                    if ($psched->duedate != '') {
                        $particulars = 'PAYABLES FOR ' . strtoupper($f);
                    } else {
                        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'faai') {
                            $particulars = 'REGISTRATION/MISCELLANEOUS/BOOKS/GENYO';
                        } else {
                            $particulars = 'ONE-TIME PAYMENT';
                        }
                        $m = 0;
                    }
                }


                $monthname = date('M', strtotime('2020-' . $month));
                return view('finance.statementofaccount.table_xai')
                    ->with('studinfo', $studinfo)
                    ->with('monthname', $monthname)
                    ->with('ledger', $ledger)
                    ->with('getPaySched', $getPaySched);



            } else {

                $monthname = date('M', strtotime('2020-' . $month));
                return view('finance.statementofaccount.table_xai')
                    ->with('studinfo', $studinfo)
                    ->with('monthname', $monthname)
                    ->with('ledger', $ledger)
                    ->with('getPaySched', $getPaySched);
            }

        } else {
           

            $output = '<table class="table table-bordered" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th colspan="5">LEDGER</th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Billing</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($ledger as $led) {
                $debit += $led->amount;

                if ($led->void == 0) {
                    $credit += $led->payment;
                }

                $lDate = date_create($led->createddatetime);
                $lDate = date_format($lDate, 'm-d-Y');

                if ($led->amount > 0) {
                    $amount = number_format($led->amount, 2);
                } else {
                    $amount = '';
                }

                if ($led->payment > 0) {
                    $payment = number_format($led->payment, 2);
                } else {
                    $payment = '';
                }

                if ($led->void == 0) {
                    $bal += $led->amount - $led->payment;
                }

                if ($led->void == 0) {
                    $output .= '
                    <tr>
                        <td>' . $lDate . ' </td>
                        <td>' . $led->particulars . '</td>
                        <td class="text-right">' . $amount . '</td>
                        <td class="text-right">' . number_format($bal, 2) . '</td>
                    </tr>
                    ';
                } else {
                    $output .= '
                    <tr>
                        <td class="text-danger"><del>' . $lDate . ' </del></td>
                        <td class="text-danger"><del>' . $led->particulars . '</del></td>
                        <td class="text-right text-danger"><del>' . $amount . '</del></td>
                        <td class="text-right text-danger"><del>' . number_format($bal, 2) . '</del></td>
                    </tr>
                    ';
                }

            }

            $output .= '
            <tr style="background-color:#007bff91">
                <th></th>
                <th style="text-align:right">
                    <strong>TOTAL:<strong>
                </th>
                <th class="text-right">
                    <strong><u>' . number_format($debit, 2) . '</u></strong>
                </th>
                <th class="text-right">
                    <strong><u>' . number_format($bal, 2) . '</u></strong>
                </th>
            </tr>
            </tbody>
            <thead>
                <tr>
                    <th colspan="5">PAYMENT</th>
                </tr>
            </thead>
            <tbody>';
            $output .= '
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Payment</th>
                    <th>Balance</th>
                </tr>
            ';

            // $assessment = FinanceUtilityModel::assessment_gen($studid, $syid, $semid, $_monthid);
            $a_totalbill = 0;
            $a_totalpay = 0;
            $a_totalbalance = 0;
            $paybal = $bal;

            $payments = db::table('studledger')
                ->select('studledger.*', 'chrngsetup.groupname')
                ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                ->where('studledger.studid', $studid)
                ->where('studledger.syid', $syid)
                ->where(function ($q) use ($semid, $levelid) {
                    if ($levelid == 14 || $levelid == 15) {
                        if ($semid == 3) {
                            $q->where('semid', 3);
                        } else {
                            if (DB::table('schoolinfo')->first()->shssetup == 0) {
                                $q->where('semid', $semid);
                            } else {
                                $q->where('semid', '!=', 3);
                            }
                        }
                    } elseif ($levelid >= 17 && $levelid <= 25) {
                        $q->where('semid', $semid);
                    }
                })
                ->where('payment', '>', 0)
                ->where('studledger.deleted', 0)
                ->where('studledger.void', 0)
                ->orderBy('studledger.createddatetime', 'asc')
                ->get();
                
            foreach ($payments as $pay) {
                // $_particulars = strtoupper($sched->particulars . ' PAYABLES');
                $paybal -= $pay->payment;
                $a_totalpay += $pay->payment;
                $a_totalbalance += $pay->payment;

                $lDate = date_create($led->createddatetime);
                $lDate = date_format($lDate, 'm-d-Y');

                $output .= '
                    <tr>
                        <td>' . $lDate . ' </td>
                        <td>' . $pay->particulars . '</td>
                        <td class="text-right">' . number_format($pay->payment, 2) . '</td>
                        <td class="text-right">' . number_format($paybal, 2) . '</td>
                    </tr>
                ';
            }
         

            $output .= '
                <tr style="background-color:#007bff91">
                    <th></th>
                    <th style="text-align:right">
                        <strong>TOTAL:<strong>
                    </th>
                    <th class="text-right">
                        <strong><u>' . number_format($a_totalpay, 2) . '</u></strong>
                    </th>
                    <th class="text-right">
                        <strong><u>' . number_format($paybal, 2) . '</u></strong>
                    </th>
                </tr>
                </tbody>
            ';
            //DUE FOR THE MONTH//
            $monthinword = '';
            $monthsetup = db::table('monthsetup')->where('id', $monthsetupid)->first();

            if ($monthsetup) {
                $monthinword = $monthsetup->description;
            } else {
                $monthinword = '';
            }


            $monthdue = UtilityController::assessment_gen($request);
            // $monthdue =FinanceUtilityModel::assessment_gen($studid, $syid, $semid, $monthsetup);
            $totaldue = 0;
            // return $monthdue;
            if ($monthdue) {
                foreach ($monthdue as $_due) {
                    // return $_due->amount;
                    $totaldue += str_replace(',', '', $_due->amount);
                }
            }

            $output .= '
                <thead>
                    <tr style="background-color:#007bff91">
                        <th colspan="3" class="text-right text-lg">DUE FOR THE MONTH OF ' . strtoupper($monthinword) . ' </th>
                        <th class="text-right text-lg">' . number_format($totaldue, 2) . '</th>
                    </tr>
                </thead>
            ';








        }

        if ($action == 'generate') {
            return $output;
        } elseif ($action == 'ep') {
            return $totaldue;
        } else {
            $pdf = new MYPDFStatementOfAccount(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // $pdf->SetCreator('CK');
            // $pdf->SetAuthor('CK Children\'s Publishing');
            // $pdf->SetTitle(DB::table('schoolinfo')->first()->schoolname.' - Statement of Account');
            // $pdf->SetSubject('Statement of Account');

            // set header and footer fonts
            // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // // set default monospaced font
            // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(3, 10, 5);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            $pdf->setPrintFooter(false);


            // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf->setLanguageArray($l);
            }

            // if(strtolower($schoolinfo->abbreviation) == 'apmc')
            // {
            //     $pdf->setPrintHeader(false);
            // }


            // ---------------------------------------------------------

            // set font
            // $pdf->SetFont('dejavusans', '', 10);


            // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            // Print a table

            $pdf->AddPage('8.5x11');

                

            $pdf = PDF::loadView('finance/reports/pdf/pdf_statementofacct_default_v2', compact('ledger', 'payments', 'monthsetup', 'monthdue', 'selectedschoolyear', 'selectedsemester', 'monthinword', 'studinfo', 'levelid', 'courseabrv', 'sectionname'))
                ->setPaper('letter');
            return $pdf->stream('Statement Of Account.pdf');
        }
    }

    public function export(Request $request)
    {
        $studid = $request->get('studid');
        $semid = $request->get('selectedsemester');
        $syid = $request->get('selectedschoolyear');
        $strand = '';
        $selectedschoolyear = DB::table('sy')
            ->where('id', $request->get('selectedschoolyear'))
            ->first()
            ->sydesc;
        $monthsetupid = $request->get('selectedmonth');
        $selectedmonth = 0;
        if ($request->get('selectedmonth') > 0) {
            if (date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month']) {
                if (strlen(date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month']) == 1) {
                    $selectedmonth = '0' . date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month'];
                } else {
                    $selectedmonth = date_parse(DB::table('monthsetup')->where('id', $monthsetupid)->first()->description)['month'];
                }
            }
        }

        if ($request->get('selectedschoolyear') == null) {
            $selectedsemester = "";
        } else {
            $semester = DB::table('semester')
                ->where('id', $request->get('selectedsemester'))
                ->first()
                ->semester;

            $selectedsemester = $semester;
        }

        $studinfo = db::table('studinfo')
            ->select('studinfo.id', 'studinfo.sid', 'lastname', 'firstname', 'middlename', 'suffix', 'gender', 'dob', 'street', 'barangay', 'city', 'province', 'levelname', 'sectionname', 'levelid', 'courseid', 'feesid', 'studtype', 'sectionname', 'courseabrv')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->leftJoin('college_courses', 'studinfo.courseid', '=', 'college_courses.id')
            ->where('studinfo.id', $studid)
            ->first();

        $checksection = DB::table('enrolledstud')
            ->select('sectionname', 'levelname')
            ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
            ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            ->where('enrolledstud.studid', $studinfo->id)
            ->where('enrolledstud.syid', $syid)
            ->where('enrolledstud.deleted', '0')
            ->first();

        if ($checksection) {
            $studinfo->sectionname = $checksection->sectionname;
            $studinfo->levelname = $checksection->levelname;
        } else {
            $checksection = DB::table('sh_enrolledstud')
                ->select('sectionname', 'levelname', 'strandcode')
                ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('sh_strand', 'sh_enrolledstud.strandid', '=', 'sh_strand.id')
                ->where('sh_enrolledstud.studid', $studinfo->id)
                ->where('sh_enrolledstud.syid', $syid)
                ->where('sh_enrolledstud.deleted', '0')
                ->first();
            if ($checksection) {
                $studinfo->sectionname = $checksection->sectionname;
                $studinfo->levelname = $checksection->levelname;
                $strand = $checksection->strandcode;
            } else {
                $checksection = DB::table('college_enrolledstud')
                    ->select('sectionDesc as sectionname', 'levelname')
                    ->join('college_sections', 'college_enrolledstud.sectionid', '=', 'college_sections.id')
                    ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                    ->where('college_enrolledstud.studid', $studinfo->id)
                    ->where('college_enrolledstud.syid', $syid)
                    ->where('college_enrolledstud.deleted', '0')
                    ->first();

                if ($checksection) {
                    $studinfo->sectionname = $checksection->sectionname;
                    $studinfo->levelname = $checksection->levelname;
                }
            }

        }

        $studaddress = '';

        if ($studinfo->dob != null) {
            $studinfo->dob = date('d-F-y', strtotime($studinfo->dob));
        }
        if ($studinfo->street != null) {
            $studaddress .= $studinfo->street . ', ';
        }
        if ($studinfo->barangay != null) {
            $studaddress .= $studinfo->barangay . ', ';
        }
        if ($studinfo->city != null) {
            $studaddress .= $studinfo->city . ', ';
        }
        if ($studinfo->province != null) {
            $studaddress .= $studinfo->province . ', ';
        }

        if ($studaddress != '') {
            $studaddress = substr($studaddress, 0, -2);
        }

        $studinfo->address = $studaddress;

        $notes = DB::table('schoolreportsnote')
            ->where('deleted', '0')
            ->where('type', '1')
            ->get();

        $notestatus = 0;
        if (count($notes) > 0) {
            foreach ($notes as $note) {
                if ($note->status) {
                    $notestatus += 1;
                }
            }
        }
        if ($studinfo->middlename == null) {
            $studinfo->middlename = '';
        } else {
            $studinfo->middlename = $studinfo->middlename[0] . '.';
        }

        $preparedby = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        if ($request->get('selectedmonth') == null || $request->get('selectedmonth') == 0) {
            $month = 0;
        } else {
            $month = date('m', strtotime($request->get('selectedmonth')));
        }


        if ($studinfo->levelid == 14 || $studinfo->levelid == 15) {
            $ledger = db::table('studledger')
                ->select('studledger.*', 'chrngsetup.groupname')
                ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                ->where('studledger.studid', $studid)
                ->where('studledger.syid', $syid)
                ->where(function ($q) use ($semid) {
                    if (DB::table('schoolinfo')->first()->shssetup == 0) {
                        $q->where('semid', $semid);
                    }
                })
                ->where('studledger.void', 0)
                ->where('studledger.deleted', 0)
                ->orderBy('studledger.id', 'asc')
                ->get();
        } elseif ($studinfo->levelid >= 17 && $studinfo->levelid <= 25) {
            $ledger = db::table('studledger')
                ->select('studledger.*', 'chrngsetup.groupname')
                ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                ->where('studledger.studid', $studid)
                ->where('studledger.syid', $syid)
                ->where('studledger.semid', $semid)
                ->where('studledger.deleted', 0)
                ->orderBy('studledger.id', 'asc')
                ->get();
        } else {
            $ledger = db::table('studledger')
                ->select('studledger.*', 'chrngsetup.groupname')
                ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                ->where('studledger.studid', $studid)
                ->where('studledger.syid', $syid)
                ->where('studledger.deleted', 0)
                ->orderBy('studledger.id', 'asc')
                ->get();
        }
        // return collect($ledger)->sum('payment');



        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.abbreviation',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'schoolinfo.picurl',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
            ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
            ->first();
        // return $studinfo->id;
        $itemized = DB::table('studledgeritemized')
            ->select('itemamount', 'items.description', 'classid')
            ->join('items', 'studledgeritemized.itemid', '=', 'items.id')
            ->where('studid', $studinfo->id)
            ->where('studledgeritemized.syid', $syid)
            ->where('studledgeritemized.semid', $semid)
            ->where('studledgeritemized.deleted', 0)
            ->where('classid', '!=', 7)
            ->get();
        // $itemized = DB::select('itemamount, items.`description`, classid FROM studledgeritemized
        // INNER JOIN items ON  studledgeritemized.itemid = items.`id`
        // WHERE studid = '.$studinfo->id.'
        // AND studledgeritemized.syid = '.$syid.'
        // AND studledgeritemized.semid = '.$semid.'
        // AND studledgeritemized.deleted = 0 and classid != 7');
        // return collect($itemized)->sum('itemamount');

        if ($request->get('exporttype') == 'pdf') {

            $bal = 0;
            $debit = 0;
            $credit = 0;

            // if($studinfo->levelid == 14 || $studinfo->levelid == 15)
            // {

            //     $getPaySched = db::table('studpayscheddetail')
            //         ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
            //         ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
            //         ->where('studid', $studid)
            //         ->where('syid', $syid)
            //         ->where(function($q) use($semid){
            //             if(db::table('schoolinfo')->first()->shssetup == 0)
            //             {
            //                 $q->where('semid', $semid);
            //             }
            //         })
            //         ->where('studpayscheddetail.deleted', 0)
            //         ->groupBy(db::raw('MONTH(duedate)'))
            //         ->get();
            //         // return $getPaySched;

            // }
            // else if($studinfo->levelid >= 17 && $studinfo->levelid <= 20)
            // {

            //     $getPaySched = db::table('studpayscheddetail')
            //     ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
            //         ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
            //         ->where('studid', $studid)
            //         ->where('syid', $syid)
            //         ->where('semid', $semid)
            //         ->where('studpayscheddetail.deleted', 0)
            //         ->groupBy(db::raw('MONTH(duedate)'))
            //         ->get();
            // }
            // else
            // {

            //     $getPaySched = db::table('studpayscheddetail')
            //     ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate, itemclassification.description'))
            //         ->join('itemclassification','studpayscheddetail.classid','=','itemclassification.id')
            //         ->where('studid', $studid)
            //         ->where('syid', $syid)
            //         ->where('studpayscheddetail.deleted', 0)
            //         ->groupBy(db::raw('MONTH(duedate)'))
            //         ->get();
            // }
            // $monthsetup = DB::table('monthsetup')
            //     ->get();

            // if(count($getPaySched)>0)
            // {
            //     foreach($getPaySched as $eachpaysched)
            //     {
            //         if($eachpaysched->duedate == null)
            //         {
            //             $eachpaysched->monthid = 0;
            //             $eachpaysched->monthnumber = 0;
            //         }else{
            //             if(collect($monthsetup)->where('description', strtoupper(strtolower(date('F', strtotime($eachpaysched->duedate)))))->count()>0)
            //             {
            //                 $eachpaysched->monthid = collect($monthsetup)->where('description', strtoupper(strtolower(date('F', strtotime($eachpaysched->duedate)))))->first()->id;
            //             }else{
            //                 $eachpaysched->monthid = 0;
            //             }
            //             if(strlen(date_parse(date('F', strtotime($eachpaysched->duedate)))['month']) == 1)
            //             {
            //                 $eachpaysched->monthnumber = '0'.date_parse(date('F', strtotime($eachpaysched->duedate)))['month'];
            //             }else{
            //                 $eachpaysched->monthnumber = date_parse(date('F', strtotime($eachpaysched->duedate)))['month'];
            //             }
            //         }
            //     }
            // }
            // $getPaySched = collect($getPaySched)->sortBy('monthid')->sortBy('monthnum')->values();
            $_monthid = db::table('monthsetup')->where('id', $monthsetupid)->first()->monthid;

            $getPaySched = FinanceUtilityModel::assessment_gen($studid, $syid, $semid, $_monthid);
            // return $getPaySched;
            $assessbilling = 0;
            $assesspayment = 0;
            $assessbalance = 0;
            $totalBal = 0;

            $units = 0;
            // $totalunits = db::table('college_studsched')
            // ->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
            // ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
            // ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
            // ->where('college_studsched.studid', $studinfo->id)
            // ->where('college_studsched.deleted', 0)
            // ->where('college_classsched.deleted', 0)
            // ->where('college_classsched.syID', $syid)
            // ->where('college_classsched.semesterID', $semid)
            // ->first();
            // return collect($totalunits);
            try {
                $courseid = DB::table('college_enrolledstud')
                    ->where('college_enrolledstud.studid', $studinfo->id)
                    ->where('college_enrolledstud.syid', $syid)
                    ->where('college_enrolledstud.semid', $semid)
                    ->where('college_enrolledstud.yearLevel', $studinfo->levelid)
                    ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                    ->select('courseid')
                    ->first()->courseid;
            } catch (\Exception $e) {
                $courseid = $studinfo->courseid;
            }

            $units = db::table('college_studsched')
                ->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
                ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                ->where('college_studsched.studid', $studid)
                ->where('college_studsched.deleted', 0)
                ->where('college_classsched.deleted', 0)
                ->where('college_classsched.syID', $syid)
                ->where('college_classsched.semesterID', $semid)
                ->first()->totalunits;

            $unitprice = 0;

            if ($studinfo->feesid != null) {
                $tuitionheader = DB::table('tuitionheader')
                    ->where('id', $studinfo->feesid)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('levelid', $studinfo->levelid)
                    ->where('deleted', '0')
                    ->first();

                if ($tuitionheader) {
                    $tuitiondetail = DB::table('tuitiondetail')
                        ->select('tuitiondetail.amount')
                        ->join('chrngsetup', 'tuitiondetail.classificationid', '=', 'chrngsetup.classid')
                        ->where('tuitiondetail.headerid', $studinfo->feesid)
                        ->where('chrngsetup.groupname', 'TUI')
                        ->where('tuitiondetail.deleted', '0')
                        ->where('chrngsetup.deleted', '0')
                        ->first();

                    if ($tuitiondetail) {
                        $unitprice = $tuitiondetail->amount;
                    }
                    // return $tuitiondetail;

                }
            }
            $courseandyear = '';
            $course = DB::table('college_courses')
                ->where('id', $courseid)
                ->first();

            if ($course) {
                $courseandyear .= $course->courseabrv . ' - ';
            }
            $levelname = DB::table('gradelevel')
                ->where('id', $studinfo->levelid)
                ->first();
            if ($levelname) {
                $courseandyear .= filter_var($levelname->levelname, FILTER_SANITIZE_NUMBER_INT);
            }

            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai') {
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {
                $monthname = date('M', strtotime(date('Y') . '-' . $selectedmonth));

                // if($studinfo->levelid > 16)
                // {
                $pdf = PDF::loadView('finance/reports/pdf/pdf_statementofacct_apmc_college', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'studinfo', 'ledger', 'lDate', 'getPaySched', 'month', 'notestatus', 'notes', 'preparedby', 'schoolinfo', 'monthname', 'units', 'unitprice', 'itemized', 'courseandyear'))->setPaper('legal');
                return $pdf->stream('Statement Of Account.pdf');
                // }else{
                //     $pdf = PDF::loadView('finance/reports/pdf/pdf_statementofacct_apmc', compact('selectedschoolyear','selectedmonth','selectedsemester','studinfo','ledger','lDate','getPaySched','month','notestatus','notes','preparedby','schoolinfo','monthname','units','unitprice','courseandyear'))->setPaper('legal');
                //     return $pdf->stream('dailycashcollection.pdf');
                // }
                // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa') {

                $pdf = new MYPDFStatementOfAccountccsa(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xkhs') {
                $monthname = date('M', strtotime(date('Y') . '-' . $selectedmonth));

                // if($studinfo->levelid > 16)
                // {
                $pdf = PDF::loadView('finance/reports/pdf/pdf_statementofacct_xdkhs', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'studinfo', 'ledger', 'lDate', 'getPaySched', 'month', 'notestatus', 'notes', 'preparedby', 'schoolinfo', 'monthname', 'units', 'unitprice', 'itemized', 'courseandyear'))->setPaper('legal');
                return $pdf->stream('Statement Of Account.pdf');
                // }else{
                //     $pdf = PDF::loadView('finance/reports/pdf/pdf_statementofacct_apmc', compact('selectedschoolyear','selectedmonth','selectedsemester','studinfo','ledger','lDate','getPaySched','month','notestatus','notes','preparedby','schoolinfo','monthname','units','unitprice','courseandyear'))->setPaper('legal');
                //     return $pdf->stream('dailycashcollection.pdf');
                // }
                // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') {
                $monthname = '';
                if ($selectedmonth != '0') {
                    $monthname = date('F', strtotime(date('Y') . '-' . $selectedmonth));
                }
                $previousbalance = DB::table('balforwardsetup')
                    ->join('studledger', 'balforwardsetup.classid', '=', 'studledger.classid')
                    ->where('studledger.studid', $studid)
                    ->where('balforwardsetup.syid', $syid)
                    ->where('balforwardsetup.semid', $semid)
                    ->where('studledger.syid', $syid)
                    ->where('studledger.semid', $semid)
                    // ->where('balforwardsetup.deleted')
                    ->get();

                // return collect($studinfo);
                // if($studinfo->levelid > 16)
                // {
                // return $getPaySched;
                $pdf = PDF::loadView('finance/reports/pdf/pdf_statementofacct_default', compact('previousbalance', 'monthsetupid', 'monthsetup', 'selectedschoolyear', 'selectedmonth', 'selectedsemester', 'studinfo', 'ledger', 'lDate', 'getPaySched', 'month', 'notestatus', 'notes', 'preparedby', 'schoolinfo', 'monthname', 'units', 'unitprice', 'itemized', 'courseandyear'))->setPaper('legal');
                return $pdf->stream('Statement Of Account.pdf');
                // }else{
                //     $pdf = PDF::loadView('finance/reports/pdf/pdf_statementofacct_apmc', compact('selectedschoolyear','selectedmonth','selectedsemester','studinfo','ledger','lDate','getPaySched','month','notestatus','notes','preparedby','schoolinfo','monthname','units','unitprice','courseandyear'))->setPaper('legal');
                //     return $pdf->stream('dailycashcollection.pdf');
                // }
                // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            }
            // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
            // {
            //     $pdf = new MYPDFStatementOfAccountHccsi(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // }
            else {
                $pdf = new MYPDFStatementOfAccount(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            }

            // set document information
            $pdf->SetCreator('CK');
            $pdf->SetAuthor('CK Children\'s Publishing');
            $pdf->SetTitle($schoolinfo->schoolname . ' - Statement of Account');
            $pdf->SetSubject('Statement of Account');

            // set header and footer fonts
            $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            if (strtolower($schoolinfo->abbreviation) == 'xai') {
                $pdf->SetMargins(11, 5, 11);
                $pdf->SetPrintHeader(false);
                $pdf->SetPrintFooter(false);
            } elseif (strtolower($schoolinfo->abbreviation) == 'apmc') {
                $pdf->SetMargins(5, 2, 5);
                $pdf->SetPrintHeader(false);
                $pdf->SetPrintFooter(false);
            } elseif (strtolower($schoolinfo->abbreviation) == 'xkhs') {
                $pdf->SetMargins(5, 2, 5);
                $pdf->SetPrintHeader(false);
                $pdf->SetPrintFooter(false);
            } elseif (strtolower($schoolinfo->abbreviation) == 'ndsc') {
                $pdf->SetMargins(5, 2, 5);
                $pdf->SetPrintHeader(false);
                $pdf->SetPrintFooter(false);
            }
            // elseif(strtolower($schoolinfo->abbreviation) == 'hccsi')
            // {
            //     $pdf->SetMargins(3, 10, 5);
            // }
            else {
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            }

            // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf->setLanguageArray($l);
            }

            // if(strtolower($schoolinfo->abbreviation) == 'apmc')
            // {
            //     $pdf->setPrintHeader(false);
            // }
            // ---------------------------------------------------------

            // set font


            // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            // Print a table

            // add a page
            if (strtolower($schoolinfo->abbreviation) == 'xai') {
                $pdf->AddPage('P', 'A4');
            } else {
                $pdf->AddPage();
            }


            $month = $selectedmonth;
            if ($selectedmonth != 0) {
                $selectedmonth = date('F', strtotime(date('Y') . '-' . $selectedmonth));
                $monthname = date('F', strtotime(date('Y') . '-' . $selectedmonth));
            }
            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai') {
                // return $ledger;
                $view = \View::make('finance/reports/pdf/pdf_statementofacct_xai', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'studinfo', 'ledger', 'lDate', 'getPaySched', 'month', 'notestatus', 'notes', 'preparedby', 'schoolinfo', 'monthname'));
            } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {

                if ($studinfo->levelid > 16) {
                    $view = \View::make('finance/reports/pdf/pdf_statementofacct_apmc_college', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'studinfo', 'ledger', 'lDate', 'getPaySched', 'month', 'notestatus', 'notes', 'preparedby', 'schoolinfo', 'monthname', 'units', 'unitprice'));
                } else {
                    $view = \View::make('finance/reports/pdf/pdf_statementofacct_apmc', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'studinfo', 'ledger', 'lDate', 'getPaySched', 'month', 'notestatus', 'notes', 'preparedby', 'schoolinfo', 'monthname', 'units', 'unitprice'));
                }
            } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xkhs') {

                // if($studinfo->levelid > 16)
                // {
                //     $view = \View::make('finance/reports/pdf/pdf_statementofacct_apmc_college', compact('selectedschoolyear','selectedmonth','selectedsemester','studinfo','ledger','lDate','getPaySched','month','notestatus','notes','preparedby','schoolinfo','monthname','units','unitprice'));
                // }else{
                $view = \View::make('finance/reports/pdf/pdf_statementofacct_apmc', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'studinfo', 'ledger', 'lDate', 'getPaySched', 'month', 'notestatus', 'notes', 'preparedby', 'schoolinfo', 'monthname', 'units', 'unitprice'));
                // }
            } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') {

                // if($studinfo->levelid > 16)
                // {
                //     $view = \View::make('finance/reports/pdf/pdf_statementofacct_apmc_college', compact('selectedschoolyear','selectedmonth','selectedsemester','studinfo','ledger','lDate','getPaySched','month','notestatus','notes','preparedby','schoolinfo','monthname','units','unitprice'));
                // }else{
                $view = \View::make('finance/reports/pdf/pdf_statementofacct_default', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'studinfo', 'ledger', 'lDate', 'getPaySched', 'month', 'notestatus', 'notes', 'preparedby', 'schoolinfo', 'monthname', 'units', 'unitprice'));
                // }
            } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa') {

                // if($studinfo->levelid > 16)
                // {
                //     $view = \View::make('finance/reports/pdf/pdf_statementofacct_apmc_college', compact('selectedschoolyear','selectedmonth','selectedsemester','studinfo','ledger','lDate','getPaySched','month','notestatus','notes','preparedby','schoolinfo','monthname','units','unitprice'));
                // }else{
                $view = \View::make('finance/reports/pdf/pdf_statementofacct_ccsa_college', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'studinfo', 'ledger', 'lDate', 'getPaySched', 'month', 'notestatus', 'notes', 'preparedby', 'schoolinfo', 'monthname', 'units', 'unitprice'));
                // }
            }
            // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
            // {
            //     $monthname = date('M', strtotime(date('Y').'-'.$selectedmonth));
            //     // return $ledger;
            //     $view = \View::make('finance/reports/pdf/pdf_statementofacct_hccsi', compact('selectedschoolyear','selectedmonth','selectedsemester','studinfo','ledger','lDate','getPaySched','month','notestatus','notes','preparedby','schoolinfo','monthname'));
            // }
            else {
                // return $getPaySched;
                $view = \View::make('finance/reports/pdf/pdf_statementofacct_default', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'studinfo', 'ledger', 'getPaySched', 'month', 'notestatus', 'notes', 'preparedby', 'monthsetupid', 'monthsetupid', 'strand'));
            }


            $html = $view->render();
            $pdf->writeHTML($html, true, false, true, false, '');
            // ---------------------------------------------------------
            //Close and output PDF document
            $pdf->Output('Statement of Account.pdf', 'I');
        } elseif ($request->get('exporttype') == 'excel') {
            $studinfo = db::table('studinfo')
                ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'gender', 'dob', 'street', 'barangay', 'city', 'province', 'studtype', 'levelname', 'sectionname', 'levelid', 'courseid', 'feesid')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->where('studinfo.id', $studid)
                ->first();

            if ($studinfo->levelid >= 17 && strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {
                $units = 0;

                try {
                    $courseid = DB::table('college_enrolledstud')
                        ->where('college_enrolledstud.studid', $studinfo->id)
                        ->where('college_enrolledstud.syid', $syid)
                        ->where('college_enrolledstud.semid', $semid)
                        ->where('college_enrolledstud.yearLevel', $studinfo->levelid)
                        ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                        ->select('courseid')
                        ->first()->courseid;
                } catch (\Exception $e) {
                    $courseid = $studinfo->courseid;
                }

                $getsubjects = DB::table('college_studsched')
                    ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                    ->where('college_studsched.studid', $studinfo->id)
                    ->where('college_studsched.deleted', '0')
                    ->where('college_classsched.syID', $syid)
                    ->where('college_classsched.semesterID', $semid)
                    ->where('college_classsched.deleted', '0')
                    ->get();

                if (count($getsubjects) > 0) {
                    foreach ($getsubjects as $subject) {
                        $allunits = DB::table('college_prospectus')
                            ->select('lecunits', 'labunits')
                            // ->where('courseID', $courseid->courseid)
                            ->where('courseID', $courseid)
                            ->where('id', $subject->subjectID)
                            ->where('semesterID', $semid)
                            ->where('yearID', $studinfo->levelid)
                            ->where('deleted', '0')
                            ->get();

                        // return $allunits;
                        if (count($allunits) > 0) {
                            foreach ($allunits as $unit) {
                                $units += ($unit->lecunits + $unit->labunits);
                            }
                        }
                    }
                }

                // return $units;

                $unitprice = 0;

                if ($studinfo->feesid != null) {
                    $tuitionheader = DB::table('tuitionheader')
                        ->where('id', $studinfo->feesid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('levelid', $studinfo->levelid)
                        ->where('deleted', '0')
                        ->first();

                    if ($tuitionheader) {
                        $tuitiondetail = DB::table('tuitiondetail')
                            ->select('tuitiondetail.amount')
                            ->join('chrngsetup', 'tuitiondetail.classificationid', '=', 'chrngsetup.classid')
                            ->where('tuitiondetail.headerid', $studinfo->feesid)
                            ->where('chrngsetup.groupname', 'TUI')
                            ->where('tuitiondetail.deleted', '0')
                            ->where('chrngsetup.deleted', '0')
                            ->first();

                        if ($tuitionheader) {
                            $unitprice = $tuitiondetail->amount;
                        }
                        // return $tuitiondetail;

                    }
                }

                // return $unitprice;
                $headerstyle = array(
                    'font' => array(
                        'bold' => true,
                        'color' => array('rgb' => '25751d'),
                        'size' => 20,
                        'name' => 'Verdana'
                    )
                );

                $greentext = array(
                    'font' => array(
                        'color' => array('rgb' => '25751d'),
                        'size' => 8,
                        'name' => 'Verdana'
                    )
                );

                $greentext2 = array(
                    'font' => array(
                        'color' => array('rgb' => '25751d'),
                        'size' => 7,
                        'name' => 'Verdana'
                    )
                );

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path() . '/public/excelformats/dcc_statementofaccount.xlsx');
                $sheet = $spreadsheet->getActiveSheet();

                try {
                    $coursename = DB::table('college_enrolledstud')
                        ->where('studid', $studinfo->id)
                        ->where('yearLevel', $studinfo->levelid)
                        ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                        ->first()->courseabrv;
                } catch (\Exception $e) {
                    $coursename = DB::table('college_courses')
                        ->where('id', $courseid)
                        ->first()->courseabrv;
                }

                if ($studinfo->middlename != null) {

                    $studinfo->middlename == $studinfo->middlename[0] . '.';

                }
                $sheet->setCellValue('A13', $selectedsemester . ' for School Year ' . $selectedschoolyear);
                $sheet->setCellValue('C19', $studinfo->lastname . ',' . $studinfo->firstname . ', ' . $studinfo->middlename . ', ' . $coursename . ' - ' . $studinfo->levelname);

                $startcellno = 21;

                $bal = 0;
                $debit = 0;
                $credit = 0;
                // return $ledger;
                foreach ($ledger as $led) {
                    $debit += $led->amount;

                    if ($led->void == 0) {
                        $credit += $led->payment;
                    }

                    $lDate = date_create($led->createddatetime);
                    $lDate = date_format($lDate, 'm-d-Y');

                    if ($led->amount > 0) {
                        $amount = $led->amount;
                    } else {
                        $amount = null;
                    }

                    if ($led->payment > 0) {
                        $payment = $led->payment;
                    } else {
                        $payment = null;
                    }

                    if ($led->void == 0) {
                        $bal += $led->amount - $led->payment;
                    }
                    if ($amount > 0) {
                        if ($led->void == 0) {
                            $sheet->setCellValue('C' . $startcellno, $led->particulars);
                            if (strpos(strtolower($led->particulars), 'tuition') !== false) {
                                $sheet->setCellValue('E' . $startcellno, '(');
                                $sheet->setCellValue('F' . $startcellno, $units);
                                $sheet->setCellValue('G' . $startcellno, 'Units x');
                                $sheet->setCellValue('H' . $startcellno, $unitprice);
                                $sheet->setCellValue('I' . $startcellno, ')');
                                $sheet->setCellValue('J' . $startcellno, $amount);
                            } else {
                                $sheet->setCellValue('J' . $startcellno, $amount);
                            }
                        } else {
                            $sheet->setCellValue('C' . $startcellno, $led->particulars);
                            if (strpos(strtolower($led->particulars), 'tuition') !== false) {
                                $sheet->setCellValue('E' . $startcellno, '(');
                                $sheet->setCellValue('F' . $startcellno, $units);
                                $sheet->setCellValue('G' . $startcellno, 'Units x');
                                $sheet->setCellValue('H' . $startcellno, $unitprice);
                                $sheet->setCellValue('I' . $startcellno, ')');
                                $sheet->setCellValue('J' . $startcellno, $amount);
                            } else {
                                $sheet->setCellValue('J' . $startcellno, $amount);
                            }

                        }

                        $sheet->getStyle('J' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');

                        $startcellno += 1;
                        $sheet->insertNewRowBefore($startcellno, 1);
                    }

                }

                $startcellno += 1;

                $sheet->insertNewRowBefore($startcellno, 2);
                $sheet->setCellValue('C' . $startcellno, 'TOTAL ACCOUNTS PAYABLE');
                $sheet->getStyle('C' . $startcellno)->getFont()->setBold(true);
                $startcellno += 1;
                $sheet->setCellValue('I' . $startcellno, 'P');
                $sheet->setCellValue('J' . $startcellno, "=SUM(J21:J" . $startcellno . ")");
                $sheet->getStyle('J' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('J' . $startcellno)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('J' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('I' . $startcellno . ':J' . $startcellno)->getFont()->setBold(true);
                $startcellno += 2;

                $sheet->insertNewRowBefore($startcellno, 1);


                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="Statement Of Account.xlsx"');
                $writer->save("php://output");
            } elseif ($studinfo->levelid >= 17 && DB::table('schoolinfo')->first()->abbreviation == 'dcc') {
                $units = 0;

                $courseid = DB::table('college_enrolledstud')
                    ->where('college_enrolledstud.studid', $studinfo->id)
                    ->where('college_enrolledstud.syid', $syid)
                    ->where('college_enrolledstud.semid', $semid)
                    ->where('college_enrolledstud.yearLevel', $studinfo->levelid)
                    ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                    ->select('courseid')
                    ->first();

                $getsubjects = DB::table('college_studsched')
                    ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                    ->where('college_studsched.studid', $studinfo->id)
                    ->where('college_studsched.deleted', '0')
                    ->where('college_classsched.syID', $syid)
                    ->where('college_classsched.semesterID', $semid)
                    ->where('college_classsched.deleted', '0')
                    ->get();

                if (count($getsubjects) > 0) {
                    foreach ($getsubjects as $subject) {
                        $allunits = DB::table('college_prospectus')
                            ->select('lecunits', 'labunits')
                            ->where('courseID', $courseid->courseid)
                            ->where('id', $subject->subjectID)
                            ->where('semesterID', $semid)
                            ->where('yearID', $studinfo->levelid)
                            ->where('deleted', '0')
                            ->get();

                        // return $allunits;
                        if (count($allunits) > 0) {
                            foreach ($allunits as $unit) {
                                $units += ($unit->lecunits + $unit->labunits);
                            }
                        }
                    }
                }

                // return $units;

                $unitprice = 0;

                if ($studinfo->feesid != null) {
                    $tuitionheader = DB::table('tuitionheader')
                        ->where('id', $studinfo->feesid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('levelid', $studinfo->levelid)
                        ->where('deleted', '0')
                        ->first();

                    if ($tuitionheader) {
                        $tuitiondetail = DB::table('tuitiondetail')
                            ->select('tuitiondetail.amount')
                            ->join('chrngsetup', 'tuitiondetail.classificationid', '=', 'chrngsetup.classid')
                            ->where('tuitiondetail.headerid', $studinfo->feesid)
                            ->where('chrngsetup.groupname', 'TUI')
                            ->where('tuitiondetail.deleted', '0')
                            ->where('chrngsetup.deleted', '0')
                            ->first();

                        if ($tuitionheader) {
                            $unitprice = $tuitiondetail->amount;
                        }
                        // return $tuitiondetail;

                    }
                }

                // return $unitprice;
                $headerstyle = array(
                    'font' => array(
                        'bold' => true,
                        'color' => array('rgb' => '25751d'),
                        'size' => 20,
                        'name' => 'Verdana'
                    )
                );

                $greentext = array(
                    'font' => array(
                        'color' => array('rgb' => '25751d'),
                        'size' => 8,
                        'name' => 'Verdana'
                    )
                );

                $greentext2 = array(
                    'font' => array(
                        'color' => array('rgb' => '25751d'),
                        'size' => 7,
                        'name' => 'Verdana'
                    )
                );

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path() . '/public/excelformats/dcc_statementofaccount.xlsx');
                $sheet = $spreadsheet->getActiveSheet();

                $coursename = DB::table('college_enrolledstud')
                    ->where('studid', $studinfo->id)
                    ->where('yearLevel', $studinfo->levelid)
                    ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                    ->first()->courseabrv;

                if ($studinfo->middlename != null) {

                    $studinfo->middlename == $studinfo->middlename[0] . '.';

                }
                $sheet->setCellValue('A13', $selectedsemester . ' for School Year ' . $selectedschoolyear);
                $sheet->setCellValue('C19', $studinfo->lastname . ',' . $studinfo->firstname . ', ' . $studinfo->middlename . ', ' . $coursename . ' - ' . $studinfo->levelname);

                $startcellno = 21;

                $bal = 0;
                $debit = 0;
                $credit = 0;
                // return $ledger;
                foreach ($ledger as $led) {
                    $debit += $led->amount;

                    if ($led->void == 0) {
                        $credit += $led->payment;
                    }

                    $lDate = date_create($led->createddatetime);
                    $lDate = date_format($lDate, 'm-d-Y');

                    if ($led->amount > 0) {
                        $amount = $led->amount;
                    } else {
                        $amount = null;
                    }

                    if ($led->payment > 0) {
                        $payment = $led->payment;
                    } else {
                        $payment = null;
                    }

                    if ($led->void == 0) {
                        $bal += $led->amount - $led->payment;
                    }
                    if ($amount > 0) {
                        if ($led->void == 0) {
                            $sheet->setCellValue('C' . $startcellno, $led->particulars);
                            if (strpos(strtolower($led->particulars), 'tuition') !== false) {
                                $sheet->setCellValue('E' . $startcellno, '(');
                                $sheet->setCellValue('F' . $startcellno, $units);
                                $sheet->setCellValue('G' . $startcellno, 'Units x');
                                $sheet->setCellValue('H' . $startcellno, $unitprice);
                                $sheet->setCellValue('I' . $startcellno, ')');
                                $sheet->setCellValue('J' . $startcellno, $amount);
                            } else {
                                $sheet->setCellValue('J' . $startcellno, $amount);
                            }
                        } else {
                            $sheet->setCellValue('C' . $startcellno, $led->particulars);
                            if (strpos(strtolower($led->particulars), 'tuition') !== false) {
                                $sheet->setCellValue('E' . $startcellno, '(');
                                $sheet->setCellValue('F' . $startcellno, $units);
                                $sheet->setCellValue('G' . $startcellno, 'Units x');
                                $sheet->setCellValue('H' . $startcellno, $unitprice);
                                $sheet->setCellValue('I' . $startcellno, ')');
                                $sheet->setCellValue('J' . $startcellno, $amount);
                            } else {
                                $sheet->setCellValue('J' . $startcellno, $amount);
                            }

                        }

                        $sheet->getStyle('J' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');

                        $startcellno += 1;
                        $sheet->insertNewRowBefore($startcellno, 1);
                    }

                }

                $startcellno += 1;

                $sheet->insertNewRowBefore($startcellno, 2);
                $sheet->setCellValue('C' . $startcellno, 'TOTAL ACCOUNTS PAYABLE');
                $sheet->getStyle('C' . $startcellno)->getFont()->setBold(true);
                $startcellno += 1;
                $sheet->setCellValue('I' . $startcellno, 'P');
                $sheet->setCellValue('J' . $startcellno, "=SUM(J21:J" . $startcellno . ")");
                $sheet->getStyle('J' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('J' . $startcellno)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('J' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('I' . $startcellno . ':J' . $startcellno)->getFont()->setBold(true);
                $startcellno += 2;

                $sheet->insertNewRowBefore($startcellno, 1);

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="Statement Of Account.xlsx"');
                $writer->save("php://output");
            } else {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                ;
                $sheet = $spreadsheet->getActiveSheet();
                $borderstyle = array(
                    'borders' => array(
                        'outline' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => array('argb' => 'black'),
                        ),
                    ),
                );
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);

                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(base_path() . '/public/' . $schoolinfo->picurl);
                $drawing->setHeight(70);
                $drawing->setWorksheet($sheet);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(0);
                $drawing->setOffsetY(20);

                $drawing->getShadow()->setVisible(true);
                $drawing->getShadow()->setDirection(45);

                $sheet->mergeCells('C2:G2');
                $sheet->setCellValue('C2', $schoolinfo->schoolname);
                $sheet->mergeCells('C3:G3');
                $sheet->setCellValue('C3', $schoolinfo->address);
                $sheet->mergeCells('C4:G4');
                $sheet->setCellValue('C4', 'Statement of Account');
                $sheet->mergeCells('C5:E5');
                $sheet->setCellValue('C5', 'S.Y ' . $selectedschoolyear);
                $sheet->mergeCells('C6:E6');
                $sheet->setCellValue('C6', $selectedsemester);

                if ($request->get('selectedmonth') != null) {
                    $sheet->mergeCells('F6:G6');
                    $sheet->setCellValue('F6', 'AS OF MONTH OF: ' . strtoupper($request->get('selectedmonth')));
                }

                foreach (array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H') as $columnID) {
                    $sheet->getColumnDimension($columnID)
                        ->setAutoSize(true);
                }
                if ($request->get('selectedmonth') == null) {
                    $month = null;
                } else {
                    $month = date('m', strtotime($request->get('selectedmonth')));
                }

                $sheet->mergeCells('A8:E8');
                $sheet->setCellValue('A8', 'STUDENT: ' . $studinfo->lastname . ',' . $studinfo->firstname . ' ' . $studinfo->middlename . ' ' . $studinfo->suffix);
                $sheet->getStyle('A8:E8')->getFont()->setBold(true);
                $sheet->mergeCells('A9:H9');
                $sheet->setCellValue('A9', 'LEDGER');
                $sheet->getStyle('A9:H9')->getFont()->setBold(true);
                $sheet->getStyle('A9:H9')->applyFromArray($borderstyle);

                $sheet->mergeCells('A10:B10');
                $sheet->setCellValue('A10', 'Date');
                $sheet->getStyle('A10:B10')->applyFromArray($borderstyle);
                $sheet->mergeCells('C10:E10');
                $sheet->setCellValue('C10', 'Description');
                $sheet->getStyle('C10:E10')->applyFromArray($borderstyle);
                $sheet->setCellValue('F10', 'Billing');
                $sheet->getStyle('F10')->applyFromArray($borderstyle);
                $sheet->setCellValue('G10', 'Payment');
                $sheet->getStyle('G10')->applyFromArray($borderstyle);
                $sheet->setCellValue('H10', 'Balance');
                $sheet->getStyle('H10')->applyFromArray($borderstyle);

                $startcellno = 11;

                $bal = 0;
                $debit = 0;
                $credit = 0;

                foreach ($ledger as $led) {
                    $debit += $led->amount;

                    if ($led->void == 0) {
                        $credit += $led->payment;
                    }

                    $lDate = date_create($led->createddatetime);
                    $lDate = date_format($lDate, 'm-d-Y');

                    if ($led->amount > 0) {
                        $amount = $led->amount;
                    } else {
                        $amount = null;
                    }

                    if ($led->payment > 0) {
                        $payment = $led->payment;
                    } else {
                        $payment = null;
                    }

                    if ($led->void == 0) {
                        $bal += $led->amount - $led->payment;
                    }

                    if ($led->void == 0) {
                        $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                        $sheet->setCellValue('A' . $startcellno, $lDate);
                        $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                        $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                        $sheet->setCellValue('C' . $startcellno, $led->particulars);
                        $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('F' . $startcellno, $amount);
                        $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('G' . $startcellno, $payment);
                        $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('H' . $startcellno, $bal);
                        $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                    } else {
                        $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                        $sheet->setCellValue('A' . $startcellno, $lDate);
                        $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                        $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                        $sheet->setCellValue('C' . $startcellno, $led->particulars);
                        $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('F' . $startcellno, $amount);
                        $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('G' . $startcellno, $payment);
                        $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('H' . $startcellno, $bal);
                        $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                        $strikethrough = $sheet->getStyle('A' . $startcellno . ':H' . $startcellno)->getFont()->getStrikethrough();
                        $sheet->getStyle('A' . $startcellno . ':H' . $startcellno)->setStrikethrough($strikethrough);

                    }
                    $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');

                    $startcellno += 1;

                }

                $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                $sheet->setCellValue('A' . $startcellno, '');
                $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                $sheet->setCellValue('C' . $startcellno, 'TOTAL');
                $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('right');
                $sheet->setCellValue('F' . $startcellno, $debit);
                $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                $sheet->setCellValue('G' . $startcellno, $credit);
                $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                $sheet->setCellValue('H' . $startcellno, $bal);
                $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('A' . $startcellno . ':H' . $startcellno)->getFont()->setBold(true);

                $startcellno += 1;

                $sheet->mergeCells('A' . $startcellno . ':H' . $startcellno);
                $sheet->setCellValue('A' . $startcellno, 'ASSESSMENT');
                $sheet->getStyle('A' . $startcellno . ':H' . $startcellno)->applyFromArray($borderstyle);

                $startcellno += 1;

                if ($studinfo->levelid == 14 || $studinfo->levelid == 15) {
                    $getPaySched = db::select('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                    from studpayscheddetail
                    where studid = ? and syid = ? and semid = ? and deleted = 0
                    group by MONTH(duedate)
                    order by duedate', [$studid, $syid, $semid]);

                    $getPaySched = db::table('studpayscheddetail')
                        ->select(db::raw('select sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate'))
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where(function ($q) use ($semid) {
                            if (db::table('schoolinfo')->first()->shssetup == 0) {
                                $q->where('semid', $semid);
                            }
                        })
                        ->where('deleted', 0)
                        ->groupBy(db::raw('MONTH(duedate)'))
                        ->get();

                } else if ($studinfo->levelid >= 17 && $studinfo->levelid <= 20) {
                    $getPaySched = db::select('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                    from studpayscheddetail
                    where studid = ? and syid = ? and semid = ? and deleted = 0
                    group by MONTH(duedate)
                    order by duedate', [$studid, $syid, $semid]);

                    $getPaySched = db::table('studpayscheddetail')
                        ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate'))
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('deleted', 0)
                        ->groupBy(db::raw('MONTH(duedate)'))
                        ->get();
                } else {
                    $getPaySched = db::select('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                    from studpayscheddetail
                    where studid = ? and syid = ? and deleted = 0
                    group by MONTH(duedate)
                    order by duedate', [$studid, $syid]);
                }

                $assessbilling = 0;
                $assesspayment = 0;
                $assessbalance = 0;
                $totalBal = collect($getPaySched)->sum('balance');
                if (count($getPaySched) > 0) {
                    foreach ($getPaySched as $psched) {

                        // return $getPaySched;
                        // $totalBal += $psched->balance;
                        $assessbilling += $psched->amountdue;
                        $assesspayment += $psched->amountpay;
                        $assessbalance += $psched->balance;

                        $m = date_create($psched->duedate);
                        $f = date_format($m, 'F');
                        $m = date_format($m, 'm');

                        if ($psched->duedate != '') {
                            $particulars = 'TUITION/BOOKS/OTH FEE - ' . strtoupper($f);
                        } else {
                            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'faai') {
                                $particulars = 'REGISTRATION/MISCELLANEOUS/BOOKS/GENYO';
                            } else {
                                $particulars = 'TUITION/BOOKS/OTH FEE';
                            }
                            $m = 0;
                        }


                        // return $showall;
                        if ($month == null || $month == "") {
                            // return $m . ' != ' . $month;
                            if ($m != $month) {
                                if ($psched->balance > 0) {
                                    $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                                    $sheet->setCellValue('A' . $startcellno, '');
                                    $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                                    $sheet->setCellValue('C' . $startcellno, $particulars);
                                    $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->setCellValue('F' . $startcellno, $psched->amountdue);
                                    $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->setCellValue('G' . $startcellno, $psched->amountpay);
                                    $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->setCellValue('H' . $startcellno, $psched->balance);
                                    $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');

                                    $startcellno += 1;
                                }
                            } else {
                                if ($psched->balance > 0) {
                                    $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                                    $sheet->setCellValue('A' . $startcellno, '');
                                    $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                                    $sheet->setCellValue('C' . $startcellno, $particulars);
                                    $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->setCellValue('F' . $startcellno, $psched->amountdue);
                                    $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->setCellValue('G' . $startcellno, $psched->amountpay);
                                    $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->setCellValue('H' . $startcellno, $psched->balance);
                                    $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                                } else {
                                    $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                                    $sheet->setCellValue('A' . $startcellno, '');
                                    $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                                    $sheet->setCellValue('C' . $startcellno, $particulars);
                                    $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->setCellValue('F' . $startcellno, $psched->amountdue);
                                    $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->setCellValue('G' . $startcellno, $psched->amountpay);
                                    $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->setCellValue('H' . $startcellno, $psched->balance);
                                    $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                                    $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                                }
                                $startcellno += 1;
                                break;
                            }
                        } else {
                            // return $m . ' != ' . $month;
                            if ($m != $month) {

                                $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                                $sheet->setCellValue('A' . $startcellno, '');
                                $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                                $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                                $sheet->setCellValue('C' . $startcellno, $particulars);
                                $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                                $sheet->setCellValue('F' . $startcellno, $psched->amountdue);
                                $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                                $sheet->setCellValue('G' . $startcellno, $psched->amountpay);
                                $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                                $sheet->setCellValue('H' . $startcellno, $psched->balance);
                                $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                                $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                                $startcellno += 1;

                            } else {

                                $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                                $sheet->setCellValue('A' . $startcellno, '');
                                $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                                $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                                $sheet->setCellValue('C' . $startcellno, $particulars);
                                $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                                $sheet->setCellValue('F' . $startcellno, $psched->amountdue);
                                $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                                $sheet->setCellValue('G' . $startcellno, $psched->amountpay);
                                $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                                $sheet->setCellValue('H' . $startcellno, $psched->balance);
                                $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                                $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                                $startcellno += 1;

                                break;
                            }
                        }
                    }

                    $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, '');
                    $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                    $sheet->setCellValue('C' . $startcellno, 'TOTAL');
                    $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('right');
                    $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('F' . $startcellno, $assessbilling);
                    $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('G' . $startcellno, $assesspayment);
                    $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('H' . $startcellno, $assessbalance);
                    $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle('A' . $startcellno . ':H' . $startcellno)->getFont()->setBold(true);

                    $startcellno += 1;

                    $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, '');
                    $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                    $sheet->setCellValue('C' . $startcellno, 'TOTAL BALANCE');
                    $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('right');
                    $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('F' . $startcellno, $assessbilling);
                    $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('G' . $startcellno, $assesspayment);
                    $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('H' . $startcellno, ($totalBal - $assesspayment));
                    $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle('A' . $startcellno . ':H' . $startcellno)->getFont()->setBold(true);

                    $startcellno += 1;

                    $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, '');
                    $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                    $sheet->setCellValue('C' . $startcellno, 'TOTAL AMOUNT DUE');
                    $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('right');
                    $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('F' . $startcellno, '');
                    $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('G' . $startcellno, '');
                    $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('H' . $startcellno, ($totalBal - $assesspayment));
                    $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle('A' . $startcellno . ':H' . $startcellno)->getFont()->setBold(true);

                } else {
                    $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, '');
                    $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                    $sheet->setCellValue('C' . $startcellno, 'TOTAL BALANCE');
                    $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('right');
                    $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('F' . $startcellno, $debit);
                    $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('G' . $startcellno, $credit);
                    $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('H' . $startcellno, $bal);
                    $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle('A' . $startcellno . ':H' . $startcellno)->getFont()->setBold(true);

                    $startcellno += 1;

                    $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                    $sheet->setCellValue('A' . $startcellno, '');
                    $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                    $sheet->setCellValue('C' . $startcellno, 'TOTAL AMOUNT DUE');
                    $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('right');
                    $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('F' . $startcellno, '');
                    $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('G' . $startcellno, '');
                    $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('H' . $startcellno, $bal);
                    $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                    $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle('A' . $startcellno . ':H' . $startcellno)->getFont()->setBold(true);

                }
                $startcellno += 2;
                if ($notestatus > 0) {
                    $sheet->setCellValue('A' . $startcellno, 'NOTES:');
                    $startcellno += 1;
                    // $signatories.='<span style="font-size: 9px;font-weight: bold">NOTES:</span><br/>';
                    foreach ($notes as $note) {
                        $sheet->mergeCells('B' . $startcellno . ':G' . $startcellno);
                        $sheet->setCellValue('B' . $startcellno, $note->description);
                        $startcellno += 1;
                    }
                    $startcellno += 1;
                }
                $sheet->mergeCells('A' . $startcellno . ':B' . $startcellno);
                $sheet->setCellValue('A' . $startcellno, 'Prepared By:');
                $sheet->mergeCells('F' . $startcellno . ':H' . $startcellno);
                $sheet->setCellValue('F' . $startcellno, 'Received By:');

                $startcellno += 2;

                $sheet->mergeCells('A' . $startcellno . ':D' . $startcellno);
                $sheet->getStyle('A' . $startcellno . ':D' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->mergeCells('F' . $startcellno . ':H' . $startcellno);
                $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $startcellno += 1;

                $sheet->mergeCells('A' . $startcellno . ':D' . $startcellno);
                // $sheet->setCellValue('A'.$startcellno,$preparedby->firstname.' '.$preparedby->middlename.' '.$preparedby->lastname.' '.$preparedby->suffix);
                $sheet->getStyle('A' . $startcellno)->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('F' . $startcellno, 'Date:');
                $sheet->getStyle('F' . $startcellno . ':H' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="Statement of Account.xlsx"');
                $writer->save("php://output");
            }
        }
    }
    public function getnote(Request $request)
    {
        // return $request->all();
        $schoolreportsnote = DB::table('schoolreportsnote')
            ->where('type', $request->get('type'))
            ->where('deleted', '0')
            ->get();

        $html = '<div class="col-md-12">';
        if (count($schoolreportsnote) == 0) {
            $html .= '<label><em>Separated by paragraphs</em></label><br/>
                    <textarea class="form-control" rows="1" name="notes[]" id="0"></textarea><br/>';
            $html .= '<textarea class="form-control" rows="1" name="notes[]" id="0"></textarea><br/>';
            $html .= '<textarea class="form-control" rows="1" name="notes[]" id="0"></textarea><br/>';
            $html .= '<textarea class="form-control" rows="1" name="notes[]" id="0"></textarea><br/>';
        } else {
            $status = 0;
            for ($x = 0; $x < 4; $x++) {

                try {
                    if ($schoolreportsnote[$x]->status == 1) {
                        $status += 1;
                    }
                    $html .= '<textarea class="form-control" rows="1" name="notes[]" id="' . $schoolreportsnote[$x]->id . '">' . $schoolreportsnote[$x]->description . '</textarea><br/>';

                } catch (\Exception $e) {

                    $html .= '<textarea class="form-control" rows="1" name="notes[]"id="0"></textarea><br/>';

                }

            }
            if ($status == 0) {
                $active = '';
                $inactive = 'checked';
            } else {
                $inactive = '';
                $active = 'checked';
            }
            $html .= '<div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                        <input type="radio" id="radiobutton1" value="1" name="notestatus" ' . $active . '>
                        <label for="radiobutton1">
                            Active
                        </label>
                        </div>
                        <div class="icheck-primary d-inline">
                        <input type="radio" id="radiobutton2" value="0" name="notestatus" ' . $inactive . '>
                        <label for="radiobutton2">
                        Inactive
                        </label>
                        </div>
                    </div>';
        }
        $html .= '</div>';
        return $html;
    }
    public function submitnotes(Request $request)
    {
        // return $request->all();
        $notes = json_decode($request->get('notes'));
        $schoolreportsnotedetail = DB::table('schoolreportsnote')
            ->where('type', $request->get('type'))
            ->where('deleted', '0')
            ->count();

        if (count($notes) > 0) {
            foreach ($notes as $note) {
                if ($note->id == 0) {
                    $noteid = DB::table('schoolreportsnote')
                        ->insertGetID([
                            'description' => $note->content,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s'),
                            'type' => 1,
                            'status' => 1
                        ]);
                    // DB::table('schoolreportsnotedetail')
                    // ->insert([
                    //     'type'              => 1,
                    //     'status'            => 1,
                    //     'noteid'            => $noteid
                    // ]);
                } else {
                    DB::table('schoolreportsnote')
                        ->where('id', $note->id)
                        ->update([
                            'description' => $note->content,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s'),
                            'status' => $request->get('notestatus')
                        ]);

                }
            }
        }

        return '1';
    }
    public function exportall(Request $request)
    {
        $semid = $request->get('selectedsemester');
        $syid = $request->get('selectedschoolyear');
        $levelid = $request->get('selectedgradelevel');
        $sectionid = $request->get('selectedsection');
        $courseid = $request->get('selectedcourse');
        $selectedmonth = $request->get('selectedmonth');

        if ($levelid == 0) {
            $students = collect(StatementofAccountModel::allstudents($request->get('selectedschoolyear'), $semid))->values();
        } else {
            $students = collect(StatementofAccountModel::allstudents($levelid, $syid, $semid, $sectionid, $courseid))->values();
        }
        $students = collect($students)->unique('id')->all();

        if (count($students) > 0) {
            if (db::table('schoolinfo')->first()->snr == 'hcbi') {
                $pdf = PDF::loadview('finance/reports/pdf/pdf_statementofacct_all_hcbi', compact('syid', 'selectedmonth', 'semid', 'students', 'levelid'));
                return $pdf->stream('studledger.pdf');
            } else {
                foreach ($students as $student) {
                    // return collect($student);
                    $units = db::table('college_studsched')
                        ->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
                        ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                        ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                        ->where('college_studsched.studid', $student->id)
                        ->where('college_studsched.deleted', 0)
                        ->where('college_classsched.deleted', 0)
                        ->where('college_classsched.syID', $request->get('selectedschoolyear'))
                        ->where('college_classsched.semesterID', $request->get('selectedsemester'))
                        ->first()->totalunits;

                    $unitprice = 0;

                    if ($student->feesid != null) {
                        $tuitionheader = DB::table('tuitionheader')
                            ->where('id', $student->feesid)
                            ->where('syid', $request->get('selectedschoolyear'))
                            ->where('semid', $request->get('selectedsemester'))
                            ->where('levelid', $student->levelid)
                            ->where('deleted', '0')
                            ->first();

                        if ($tuitionheader) {
                            $tuitiondetail = DB::table('tuitiondetail')
                                ->select('tuitiondetail.amount')
                                ->join('chrngsetup', 'tuitiondetail.classificationid', '=', 'chrngsetup.classid')
                                ->where('tuitiondetail.headerid', $student->feesid)
                                ->where('chrngsetup.groupname', 'TUI')
                                ->where('tuitiondetail.deleted', '0')
                                ->where('chrngsetup.deleted', '0')
                                ->first();

                            if ($tuitiondetail) {
                                $unitprice = $tuitiondetail->amount;
                            }
                            // return $tuitiondetail;

                        }
                    }
                    $student->units = $units;
                    $student->unitprice = $unitprice;
                    $miscs = DB::table('studpayscheddetail')
                        ->select('chrngsetup.classid', 'itemized', 'groupname')
                        ->join('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
                        ->where('studpayscheddetail.deleted', '0')
                        ->where('studid', $student->id)
                        ->where('syid', $request->get('selectedschoolyear'))
                        ->where('groupname', 'like', '%misc%')
                        ->get();

                    if (count($miscs) > 0) {
                        foreach ($miscs as $misc) {
                            if ($student->levelid == 14 || $student->levelid == 15) {
                                $items = DB::table('studledgeritemized')
                                    ->select('studledgeritemized.*', 'items.itemcode', 'items.description')
                                    ->join('items', 'studledgeritemized.itemid', '=', 'items.id')
                                    ->where('studledgeritemized.studid', $student->id)
                                    ->where('studledgeritemized.syid', $request->get('selectedschoolyear'))
                                    ->where('studledgeritemized.deleted', 0)
                                    ->where(function ($q) use ($semid) {
                                        if (db::table('schoolinfo')->first()->shssetup == 0) {
                                            $q->where('semid', $semid);
                                        }
                                    })
                                    ->where('studledgeritemized.classificationid', $misc->classid)
                                    ->get();

                            } elseif ($student->levelid == 17 || $student->levelid == 18 || $student->levelid == 19 || $student->levelid == 20) {

                                $items = DB::table('studledgeritemized')
                                    ->select('studledgeritemized.*', 'items.itemcode', 'items.description')
                                    ->join('items', 'studledgeritemized.itemid', '=', 'items.id')
                                    ->where('studledgeritemized.studid', $student->id)
                                    ->where('studledgeritemized.syid', $request->get('selectedschoolyear'))
                                    ->where('studledgeritemized.deleted', 0)
                                    ->where('semid', $semid)
                                    ->where('studledgeritemized.classificationid', $misc->classid)
                                    ->get();

                            } else {
                                $items = DB::table('studledgeritemized')
                                    ->select('studledgeritemized.*', 'items.itemcode', 'items.description')
                                    ->join('items', 'studledgeritemized.itemid', '=', 'items.id')
                                    ->where('studledgeritemized.studid', $student->id)
                                    ->where('studledgeritemized.syid', $request->get('selectedschoolyear'))
                                    ->where('studledgeritemized.deleted', 0)
                                    ->where('studledgeritemized.classificationid', $misc->classid)
                                    ->get();
                            }
                            if (count($items) > 0) {
                                foreach ($items as $item) {
                                    $item->balance = 0;

                                    if ($item->totalamount > 0) {
                                        $item->balance = $item->itemamount - $item->totalamount;
                                    } else {
                                        $item->balance = $item->itemamount;
                                    }
                                }
                            }

                            $misc->items = $items;

                        }
                    }
                    $student->miscs = $miscs;

                    if ($student->levelid == 14 || $student->levelid == 15) {
                        $ledger = db::table('studledger')
                            ->select('studledger.*', 'chrngsetup.groupname')
                            ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                            ->where('studledger.studid', $student->id)
                            ->where('studledger.syid', $request->get('selectedschoolyear'))
                            ->where(function ($q) use ($semid) {
                                if (DB::table('schoolinfo')->first()->shssetup == 0) {
                                    $q->where('semid', $semid);
                                }
                            })
                            ->where('studledger.void', 0)
                            ->where('studledger.deleted', 0)
                            ->orderBy('studledger.id', 'asc')
                            ->get();
                    } elseif ($student->levelid >= 17 && $student->levelid <= 25) {
                        $ledger = db::table('studledger')
                            ->select('studledger.*', 'chrngsetup.groupname')
                            ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                            ->where('studledger.studid', $student->id)
                            ->where('studledger.syid', $request->get('selectedschoolyear'))
                            ->where('studledger.semid', $semid)
                            ->where('studledger.deleted', 0)
                            ->orderBy('studledger.id', 'asc')
                            ->get();
                    } else {
                        $ledger = db::table('studledger')
                            ->select('studledger.*', 'chrngsetup.groupname')
                            ->leftJoin('chrngsetup', 'studledger.classid', '=', 'chrngsetup.classid')
                            ->where('studledger.studid', $student->id)
                            ->where('studledger.syid', $request->get('selectedschoolyear'))
                            ->where('studledger.deleted', 0)
                            ->orderBy('studledger.id', 'asc')
                            ->get();
                    }
                    if ($request->get('selectedmonth') == null) {
                        $tuis = DB::table('studpayscheddetail')
                            // ->select('chrngsetup.classid','itemized','groupname')
                            ->join('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
                            ->where('studpayscheddetail.deleted', '0')
                            ->where('studid', $student->id)
                            ->where('syid', $request->get('selectedschoolyear'))
                            ->where('groupname', 'like', '%tui%')
                            ->get();
                    } else {
                        $tuis = DB::table('studpayscheddetail')
                            // ->select('chrngsetup.classid','itemized','groupname')
                            ->join('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
                            ->where('studpayscheddetail.deleted', '0')
                            ->where('studid', $student->id)
                            ->where('syid', $request->get('selectedschoolyear'))
                            ->where('duedate', '<=', date('Y') . '-' . date('m', strtotime($request->get('selectedmonth'))) . '-' . date('D'))
                            ->where('groupname', 'like', '%tui%')
                            ->get();
                    }
                    $student->tuis = $tuis;
                    $student->ledgers = $ledger;
                }
            }
        }


        if ($request->get('exporttype') == 'pdf') {
            $students = collect($students)->toArray();
            $students = array_chunk($students, 2);

            // return $students;

            // return $students;
            $pdf = new MYPDFStatementOfAccount(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator('CK');
            $pdf->SetAuthor('CK Children\'s Publishing');
            $pdf->SetTitle(DB::table('schoolinfo')->first()->schoolname . ' - Statement of Account');
            $pdf->SetSubject('Statement of Account');

            // set header and footer fonts
            // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // // set default monospaced font
            // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(3, 10, 5);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            $pdf->setPrintFooter(false);


            // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf->setLanguageArray($l);
            }

            // if(strtolower($schoolinfo->abbreviation) == 'apmc')
            // {
            //     $pdf->setPrintHeader(false);
            // }


            // ---------------------------------------------------------

            // set font
            // $pdf->SetFont('dejavusans', '', 10);


            // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            // Print a table

            $pdf->AddPage('8.5x11');

            // $monthname = date('M', strtotime(date('Y').'-'.));
            if (db::table('schoolinfo')->first()->snr == 'hcbi') {
                // return $students;
                $view = \View::make('finance/reports/pdf/pdf_statementofacct_all_hcbi', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'students'));
            } else {
                $view = \View::make('finance/reports/pdf/pdf_statementofacct_hccsi', compact('selectedschoolyear', 'selectedmonth', 'selectedsemester', 'students'));
            }

            $html = $view->render();
            $pdf->writeHTML($html, true, false, true, false, '');
            // ---------------------------------------------------------
            //Close and output PDF document
            $pdf->Output('Statement of Account.pdf', 'I');
        } else {
            $department = DB::table('gradelevel')
                ->where('id', $request->get('selectedgradelevel'))
                ->first();


            if ($department) {
                $acadprogid = $department->acadprogid;
                $department = $department->levelname;
            } else {
                $acadprogid = 6;
                $department = "College";
            }
            $semid = $request->get('selectedsemester');
            // return $students;
            // $data = $data->values();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(base_path() . '/public/' . DB::table('schoolinfo')->first()->picurl);
                $drawing->setHeight(80);
                $drawing->setWorksheet($sheet);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(20);
                $drawing->setOffsetY(10);

                $drawing->getShadow()->setVisible(true);
                $drawing->getShadow()->setDirection(45);

                $sheet->setCellValue('B2', '                      ' . DB::table('schoolinfo')->first()->schoolname);
                $sheet->setCellValue('B3', '                       S.Y ' . $selectedschoolyear);
                $sheet->setCellValue('B6', '                       DEPARTMENT : ' . $department);
                $sheet->setCellValue('B7', '                       SEMESTER : ' . DB::table('semester')->where('id', $request->get('selectedsemester'))->first()->semester);

                $sheet->getStyle('A9:L9')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('A9:L9')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->setCellValue('A9', '#');
                $sheet->setCellValue('B9', 'Student Name');
                $sheet->setCellValue('C9', 'Grade level');
                $sheet->setCellValue('D9', 'Course & Section');
                $sheet->setCellValue('E9', 'UNITS');
                $sheet->setCellValue('F9', 'TF');
                $sheet->setCellValue('G9', 'EF');
                $sheet->setCellValue('H9', 'AVR/IF');
                $sheet->setCellValue('I9', 'OF');
                $sheet->setCellValue('J9', 'CS LAB');
                $sheet->setCellValue('K9', 'NSTP');
                $sheet->setCellValue('L9', 'Total Amount');

                $startcellno = 10;
            } else {
                $sheet->mergeCells('A1:L1');
                $sheet->setCellValue('A1', 'COLLEGE STATEMENT OF ACCOUNTS S. Y. ' . $selectedschoolyear);
                $sheet->getStyle('A1:L1')->getFont()->setBold(true);
                $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A1:L1')->getFont()->setSize(20);

                $sheet->mergeCells('A2:L2');
                $sheet->setCellValue('A2', DB::table('semester')->where('id', $request->get('selectedsemester'))->first()->semester);
                $sheet->getStyle('A2:L2')->getFont()->setBold(true);
                $sheet->getStyle('A2:L2')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A2:L2')->getFont()->setSize(20);

                $sheet->getStyle('A5:L5')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('A5:L5')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->setCellValue('A5', '#');
                $sheet->setCellValue('B5', 'Student Name');
                $sheet->setCellValue('C5', 'Grade level');
                $sheet->setCellValue('D5', 'Course & Section');
                $sheet->setCellValue('E5', 'UNITS');
                $sheet->setCellValue('F5', 'TF');
                $sheet->setCellValue('G5', 'EF');
                $sheet->setCellValue('H5', 'AVR/IF');
                $sheet->setCellValue('I5', 'OF');
                $sheet->setCellValue('J5', 'CS LAB');
                $sheet->setCellValue('K5', 'NSTP');
                $sheet->setCellValue('L5', 'Total Amount');

                $startcellno = 6;
            }


            foreach (array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L') as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }

            if (count($students) > 0) {
                foreach ($students as $key => $student) {
                    // return collect($student);

                    $itemized = DB::table('studledgeritemized')
                        ->select('itemamount', 'items.description', 'classid', 'studledgeritemized.semid')
                        ->join('items', 'studledgeritemized.itemid', '=', 'items.id')
                        ->where('studid', $student->id)
                        ->where('studledgeritemized.syid', $request->get('selectedschoolyear'))
                        ->where('studledgeritemized.semid', $semid)
                        ->where('studledgeritemized.deleted', 0)
                        ->where('classid', '!=', 7)
                        ->where('studledgeritemized.deleted', 0)
                        ->get();


                    $nstp = 0;
                    $comlabfee = 0;
                    $totalassessment = (collect($itemized)->where('classid', '!=', 7)->sum('itemamount') + ($student->unitprice * $student->units));

                    if (collect($student->ledgers)->where('classid', 20)->count() > 0) {
                        $totalassessment += collect($student->ledgers)->where('classid', 20)->sum('amount');
                        $checknstp = collect($student->ledgers)->where('classid', 20)->filter(function ($item) {
                            return false !== stristr($item->particulars, 'nstp');
                        })->values();

                        if (count($checknstp) > 0) {
                            $nstp += collect($checknstp)->sum('amount');
                        }
                        $checkcomlabfee = collect($student->ledgers)->where('classid', 20)->filter(function ($item) {
                            return true !== stristr($item->particulars, 'cs');
                        })->values();

                        if (count($checkcomlabfee) > 0) {
                            $comlabfee += collect($checkcomlabfee)->sum('amount');
                        }
                    }
                    // if($acadprogid == 5 || $acadprogid == 6)
                    // {
                    //     $itemized = collect($itemized)->where('semid', $semid)->values();
                    // }
                    // return $itemized;
                    $student->items = collect($itemized)->filter(function ($item) {
                        return false === stristr($item->description, 'avr') || false === stristr($item->description, 'internet') || false === stristr($item->description, 'lab');
                    });
                    // $student->items = collect($student->items)->filter(function ($item) {
                    //     return false ===  stristr($item->description, 'internet');
                    // });
                    // $student->items = collect($student->items)->filter(function ($item) {
                    //     return false ===  stristr($item->description, 'registration');
                    // });
                    // $student->items = collect($student->items)->filter(function ($item) {
                    //     return false ===  stristr($item->description, 'lab');
                    // });
                    $sheet->setCellValue('A' . $startcellno, $key + 1);
                    $sheet->setCellValue('B' . $startcellno, $student->lastname . ', ' . $student->firstname . ' ' . $student->middlename);
                    $sheet->setCellValue('C' . $startcellno, $student->levelname);
                    if (collect($student)->has('coursename')) {
                        $sheet->setCellValue('D' . $startcellno, $student->coursename . ' - ' . $student->sectionname);
                    }
                    $sheet->setCellValue('E' . $startcellno, $student->units);
                    $sheet->setCellValue('F' . $startcellno, $student->units * $student->unitprice);
                    $sheet->setCellValue('G' . $startcellno, collect($itemized)->filter(function ($item) {
                        return false !== stristr($item->description, 'registration');
                    })->sum('itemamount'));
                    $sheet->setCellValue('H' . $startcellno, collect($itemized)->filter(function ($item) {
                        return false !== stristr($item->description, 'avr');
                    })->sum('itemamount') + collect($itemized)->filter(function ($item) {
                        return false !== stristr($item->description, 'internet');
                    })->sum('itemamount'));

                    $sheet->setCellValue('I' . $startcellno, collect($student->items)->sum('itemamount'));
                    // $sheet->setCellValue('J'.$startcellno, collect($itemized)->filter(function ($item) { return false !==  stristr($item->description, 'lab'); })->sum('itemamount'));
                    if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {
                        $sheet->setCellValue('J' . $startcellno, $comlabfee);
                        $sheet->setCellValue('K' . $startcellno, $nstp);
                    } else {
                        $sheet->setCellValue('J' . $startcellno, collect($itemized)->where('classid', '19')->sum('itemamount'));
                        $sheet->setCellValue('K' . $startcellno, collect($student->ledgers)->where('classid', '20')->sum('amount'));
                    }
                    $sheet->setCellValue('L' . $startcellno, "=SUM(F" . $startcellno . ",G" . $startcellno . ",H" . $startcellno . ",I" . $startcellno . ",J" . $startcellno . ",K" . $startcellno . ")");
                    $startcellno += 1;
                }
            }
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Statement of Account - ' . $selectedschoolyear . '.xlsx"');
            $writer->save("php://output");
        }
    }

    public function exportall_v2(Request $request)
    {
        $semid = $request->get('selectedsemester');
        $syid = $request->get('selectedschoolyear');
        $levelid = $request->get('selectedgradelevel');
        $sectionid = $request->get('selectedsection');
        $courseid = $request->get('selectedcourse');
        $selectedmonth = $request->get('selectedmonth');
        $students = $request->get('students');
        $balclassid = db::table('balforwardsetup')->first()->classid;
        $studarray = array();

        // return $students;

        $studinfo = db::table('studinfo')
            ->select(db::raw('id, sid, lastname, firstname, middlename, suffix'))
            ->whereIn('id', $students)
            ->get();

        $studinfo = collect($studinfo);

        $studledger = db::table('studledger')
            ->where('deleted', 0)
            ->whereIn('studid', $students)
            ->where('syid', $syid)
            ->where(function ($q) use ($levelid, $semid) {
                if ($levelid == 14 || $levelid == 15) {
                    if ($semid == 3) {
                        $q->where('semid', 3);
                    } else {
                        if (db::table('schoolinfo')->first()->shssetup == 0) {
                            $q->where('semid', $semid);
                        }
                    }
                } elseif ($levelid >= 17 && $levelid <= 25) {
                    $q->where('semid', $semid);
                } else {
                    if ($semid == 3) {
                        $q->where('semid', 3);
                    }
                }
            })
            ->orderBy('studid')
            ->orderBy('createddatetime')
            ->get();

        $ledgercollect = collect($studledger);
        // $ledger_old = $ledgercollect->where('classid', $balclassid)->where('amount', '>', 0);
        // $ledger_old->all();

        foreach ($students as $stud) {
            $totaldebit = 0;
            $totalcredit = 0;
            $oldtotal = 0;
            $chargestotal = 0;
            $paymenttotal = 0;

            $_stud = $studinfo->where('id', $stud)->first();
            $sid = $_stud->sid;
            $name = $_stud->lastname . ', ' . $_stud->firstname . ' ' . $_stud->middlename . ' ' . $_stud->suffix;
            $level = '';

            if ($levelid == 14 || $levelid == 15) {
                $einfo = db::table('sh_enrolledstud')
                    ->select('sectionname', 'levelname')
                    ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->where('studid', $stud)
                    ->where('sh_enrolledstud.syid', $syid)
                    ->where('sh_enrolledstud.deleted', 0)
                    ->first();

                if ($einfo) {
                    $level = $einfo->levelname . ' - ' . $einfo->sectionname;
                }
            } elseif ($levelid >= 17 && $levelid <= 25) {
                $einfo = db::table('college_enrolledstud')
                    ->select('courseabrv as course', 'levelname')
                    ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                    ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                    ->where('studid', $stud)
                    ->where('college_enrolledstud.syid', $syid)
                    ->where('college_enrolledstud.semid', $semid)
                    ->where('college_enrolledstud.deleted', 0)
                    ->first();

                if ($einfo) {
                    $level = $einfo->levelname . ' - ' . $einfo->course;
                }
            } else {
                $einfo = db::table('enrolledstud')
                    ->select('sectionname', 'levelname')
                    ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                    ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                    ->where('studid', $stud)
                    ->where('enrolledstud.syid', $syid)
                    ->where('enrolledstud.deleted', 0)
                    ->first();

                if ($einfo) {
                    $level = $einfo->levelname . ' - ' . $einfo->sectionname;
                }

            }

            $ledgerold = array();
            $oldclass = $ledgercollect
                ->where('studid', $stud)
                ->where('classid', $balclassid)
                ->where('amount', '>', 0)
                ->all();

            // return $oldclass;

            foreach ($oldclass as $old) {
                array_push($ledgerold, (object) [
                    'createddatetime' => $old->createddatetime,
                    'particulars' => strtoupper($old->particulars),
                    'amount' => number_format($old->amount, 2)
                ]);

                $oldtotal += $old->amount;
                $totaldebit += $old->amount;
            }

            $ledgercharges = array();

            $charges = $ledgercollect
                ->where('studid', $stud)
                ->where('classid', '!=', $balclassid)
                ->where('amount', '>', 0)
                ->all();

            foreach ($charges as $chgs) {
                array_push($ledgercharges, (object) [
                    'createddatetime' => $chgs->createddatetime,
                    'particulars' => $chgs->particulars,
                    'amount' => number_format($chgs->amount, 2)
                ]);

                $chargestotal += $chgs->amount;
                $totaldebit += $chgs->amount;
            }

            $ledgerpayment = array();

            $payment = $ledgercollect
                ->where('studid', $stud)
                // ->where('classid', '!=', $balclassid)
                ->where('payment', '>', 0)
                ->all();

            foreach ($payment as $pay) {
                array_push($ledgerpayment, (object) [
                    'createddatetime' => $pay->createddatetime,
                    'ornum' => $pay->ornum,
                    'particulars' => $pay->particulars,
                    'payment' => number_format($pay->payment, 2)
                ]);

                $paymenttotal += $pay->payment;
                $totalcredit += $pay->payment;
            }

            // $monthdue =FinanceUtilityModel::assessment_gen($stud, $syid, $semid, $selectedmonth);
            $request['studid'] = $stud;
            $request['syid'] = $syid;
            $request['semid'] = $semid;
            $request['monthid'] = $selectedmonth;

            $monthdue = UtilityController::assessment_gen($request);
            // return $monthdue;
            $monthdueamount = 0;
            $totaldue = $totaldebit - $totalcredit;

            if ($monthdue) {
                foreach ($monthdue as $due) {
                    $monthdueamount += str_replace(',', '', $due->amount);
                }
            }


            array_push($studarray, (object) [
                'sid' => $sid,
                'name' => $name,
                'levelid' => $levelid,
                'level' => $level,
                'oldtotal' => number_format($oldtotal, 2),
                'chargestotal' => number_format($chargestotal, 2),
                'totaldebit' => number_format($totaldebit, 2),
                'totalcredit' => number_format($totalcredit, 2),
                'paymenttotal' => number_format($paymenttotal, 2),
                'ledgerold' => $ledgerold,
                'ledgercharges' => $ledgercharges,
                'ledgerpayment' => $ledgerpayment,
                'monthdue' => number_format($monthdueamount, 2),
                'totaldue' => number_format($totaldue, 2)
                // 'monthdue' => $monthdue
            ]);

        }

        $monthinword = db::table('monthsetup')
            ->where('id', $selectedmonth)
            ->first()
            ->description;

        // return $studarray;

        $pdf = new MYPDFStatementOfAccount(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetMargins(3, 10, 5);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->setPrintFooter(false);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // Print a table

        $pdf->AddPage('8.5x11');



        $pdf = PDF::loadView('finance/reports/pdf/pdf_statementofacct_default_v2_all', compact(
            'monthinword',
            'studarray',
            'syid',
            'semid'
        ))
            ->setPaper('letter');
        return $pdf->stream('Statement Of Account.pdf');




    }

    public function statementofacctloadsection(Request $request)
    {
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        if ($levelid == 14 || $levelid == 15) {
            $sections = DB::table('sections')
                ->select('sections.id', 'sectionname as description')
                ->join('sectiondetail', 'sections.id', 'sectionid')
                ->where('sectiondetail.deleted', 0)
                ->where('levelid', $levelid)
                ->where('syid', $syid)
                ->where('sections.deleted', 0)
                ->get();
        } elseif ($levelid >= 17 && $levelid <= 25) {
            $sections = db::table('college_courses')
                ->select('id', 'courseabrv as description')
                ->where('deleted', 0)
                ->get();
        } else {
            $sections = DB::table('sections')
                ->select('sections.id', 'sectionname as description')
                ->join('sectiondetail', 'sections.id', 'sectionid')
                ->where('sectiondetail.deleted', 0)
                ->where('levelid', $levelid)
                ->where('syid', $syid)
                ->where('sections.deleted', 0)
                ->get();
        }

        $list = '<option value="0">All</option>';
        foreach ($sections as $section) {
            $list .= '
                <option value="' . $section->id . '">' . $section->description . '</option>
            ';
        }

        return $list;
    }

    public function statementofaccsmsnotify(Request $request)
    {
        $semid = $request->get('semid');
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $sectionid = $request->get('sectionid');
        $courseid = $request->get('courseid');
        $selectedmonth = $request->get('monthid');
        $students = $request->get('students');
        $soainfo = array();

        $students = collect(StatementofAccountModel::allstudents($levelid, $syid, $semid, $sectionid, $courseid))->values();
        $month = db::table('monthsetup')->where('id', $selectedmonth)->first()->description;

        foreach ($students as $stud) {
            $request->replace([
                'studid' => $stud->id,
                'selectedschoolyear' => $syid,
                'selectedsemester' => $semid,
                'selectedmonth' => $selectedmonth,
                'action' => 'ep'
            ]);

            $balance = $this->getaccount_v2($request);

            $sid = $stud->sid;
            $name = $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' ' . $stud->suffix;
            $contactno = '';

            if ($stud->ismothernum == 1) {
                $contactno = '+63' . substr($stud->mcontactno, 1);
            } elseif ($stud->isfathernum == 1) {
                $contactno = '+63' . substr($stud->fcontactno, 1);
            } else {
                $contactno = '+63' . substr($stud->gcontactno, 1);
            }


            array_push($soainfo, (object) array(
                'sid' => $sid,
                'name' => $name,
                'balance' => number_format($balance, 2),
                'contactno' => $contactno,
                'firstname' => $stud->firstname,
                'month' => $month
            ));
        }

        return $soainfo;

        // return UtilityControllers::statementofaccsmsnotify($semid, $syid, $levelid, $sectionid, $courseid, $selectedmonth, $students);

    }

    public function statementofaccsmssend(Request $request)
    {
        $smslist = $request->get('sms_list');
        $semid = $request->get('semid');
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $sectionid = $request->get('sectionid');
        $courseid = $request->get('courseid');
        $selectedmonth = $request->get('monthid');

        $datefrom = date_format(date_create(FinanceModel::getServerDateTime()), "Y-m-d 00:00");
        $dateto = date_format(date_create(FinanceModel::getServerDateTime()), "Y-m-d 23:59");

        $flag = db::table('soa_smsnotification')
            ->whereBetween('createddatetime', [$datefrom, $dateto])
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('levelid', $levelid)
            ->where('sectionid', $sectionid)
            ->where('courseid', $courseid)
            ->where('monthid', $selectedmonth)
            ->count();

        if ($flag == 0) {
            foreach ($smslist as $sms) {
                $message = 'Hi ' . $sms['firstname'] . ', you have a remaining balance of ' . $sms['balance'] . ' on ' . $sms['month'] . ' For questions, please reach out to the Billing Office. Thank you, LDCU.';

                db::table('tapbunker')
                    ->insert([
                        'message' => $message,
                        'receiver' => $sms['contactno'],
                        'smsstatus' => 0,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }
        } else {
            return 0;
        }

        return 1;
    }

    public function statementofaccaddflag(Request $request)
    {
        $semid = $request->get('semid');
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $sectionid = $request->get('sectionid');
        $courseid = $request->get('courseid');
        $selectedmonth = $request->get('monthid');

        db::table('soa_smsnotification')
            ->insert([
                'syid' => $syid,
                'semid' => $semid,
                'levelid' => $levelid,
                'sectionid' => $sectionid,
                'courseid' => $courseid,
                'monthid' => $selectedmonth,
                'datesent' => FinanceModel::getServerDateTime(),
                'createddatetime' => FinanceModel::getServerDateTime(),
                'createdby' => auth()->user()->id
            ]);
    }

    public function statementofacctgetmonthrange(Request $request)
    {
        $ranges = db::table('month_range_setup')
            ->where('deleted', 0)
            ->get();

        return $ranges;
    }


    public function statementofacctaddmonthrange(Request $request)
    {

        db::table('monthsetup')->delete();

        $s_month = $request->get('start_month');
        $e_month = $request->get('end_month');

        // Array of month names
        $months = [
            1 => 'JANUARY',
            2 => 'FEBRUARY',
            3 => 'MARCH',
            4 => 'APRIL',
            5 => 'MAY',
            6 => 'JUNE',
            7 => 'JULY',
            8 => 'AUGUST',
            9 => 'SEPTEMBER',
            10 => 'OCTOBER',
            11 => 'NOVEMBER',
            12 => 'DECEMBER'
        ];

        // Array to hold the month data
        $monthsarray = [];

        // If start_month is less than or equal to end_month (e.g., July to December)
        if ($s_month <= $e_month) {
            for ($month = $s_month; $month <= $e_month; $month++) {
                $monthsarray[] = [
                    'description' => $months[$month],
                    'monthid' => $month
                ];
            }
        }
        // If start_month is greater than end_month (e.g., July to May)
        else {
            // Loop from start_month to December
            for ($month = $s_month; $month <= 12; $month++) {
                $monthsarray[] = [
                    'description' => $months[$month],
                    'monthid' => $month
                ];
            }

            // Loop from January to end_month
            for ($month = 1; $month <= $e_month; $month++) {
                $monthsarray[] = [
                    'description' => $months[$month],
                    'monthid' => $month
                ];
            }
        }

        // Store the data in the database
        foreach ($monthsarray as $monthdata) {
            db::table('monthsetup')
                ->insert([
                    'description' => $monthdata['description'],
                    'monthid' => $monthdata['monthid']
                ]);
        }

        $ifexist = db::table('month_range_setup')
            ->where('sdate', $s_month)
            ->where('edate', $e_month)
            ->where('deleted', 0)
            ->first();

        if ($ifexist) {
            return 0;
        } else {

            $insertdata = db::table('month_range_setup')
                ->insert([
                    'sdate' => $s_month,
                    'edate' => $e_month,
                    'isactive' => 1,
                    'createddatetime' => FinanceModel::getServerDateTime(),
                    'createdby' => auth()->user()->id
                ]);




            return 1;
        }
    }

    public function statementofacctselectedmonthrange(Request $request)
    {
        // $id = $request->get('rangeid');

        // Get the selected month range setup
        $monthsetupsrange = DB::table('month_range_setup')
            ->where('isactive', 1)
            ->where('deleted', 0)
            ->first();

        if ($monthsetupsrange) {

            // Get the list of all months
            $monthslist = DB::table('monthsetup')->orderBy('monthid', 'asc')->get();

            $startMonth = $monthsetupsrange->sdate;
            $endMonth = $monthsetupsrange->edate;

            // Filter months based on whether the range wraps around the year
            if ($startMonth <= $endMonth) {
                // If the range does not wrap around (e.g., January to May)
                $filteredMonths = $monthslist->filter(function ($item) use ($startMonth, $endMonth) {
                    return $item->monthid >= $startMonth && $item->monthid <= $endMonth;
                });
            } else {
                $filteredMonths = $monthslist->filter(function ($item) use ($startMonth) {
                    return $item->monthid >= $startMonth;
                })
                    ->concat(
                        $monthslist->filter(function ($item) use ($endMonth) {
                            return $item->monthid <= $endMonth;
                        })
                    );
            }

            return $filteredMonths->values();
        }

    }


    public function statementofacctremovemonthrange(Request $request)
    {
        $id = $request->get('id');

        $iffound = db::table('month_range_setup')
            ->where('id', $id)
            ->where('deleted', 0)
            ->count();

        if ($iffound > 0) {
            $monthsetupsrange = db::table('month_range_setup')
                ->where('id', $id)
                ->update([
                    'deleted' => 1,
                    'isactive' => 0,
                    'deleteddatetime' => FinanceModel::getServerDateTime(),
                    'deletedby' => auth()->user()->id
                ]);

            db::table('monthsetup')->delete();
        }

        return 1;
    }

}

class MYPDFStatementOfAccount extends TCPDF
{

    //Page header
    public function Header()
    {
        $schoollogo = DB::table('schoolinfo')->first();
        $picurl = explode('?', $schoollogo->picurl);
        $image_file = public_path() . '/' . $picurl[0];
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@' . file_get_contents($image_file), 15, 9, 17, 17);

        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">' . $schoollogo->schoolname . '</span>', false, false, false, $reseth = true, $align = 'L', $autopadding = true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">' . $schoollogo->address . '</span>', false, false, false, $reseth = true, $align = 'L', $autopadding = true);
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'Statement of Account', false, false, false, $reseth = true, $align = 'L', $autopadding = true);
        // Ln();
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}


class MYPDFStatementOfAccountccsa extends TCPDF
{

    //Page header
    public function Header()
    {
        $schoollogo = DB::table('schoolinfo')->first();
        $picurl = explode('?', $schoollogo->picurl);
        $image_file = public_path() . '/' . $picurl[0];
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@' . file_get_contents($image_file), 15, 9, 17, 17);

        $schoolname = $this->writeHTMLCell(false, 50, 7, 10, '<span style="font-weight: bold; text-align: center; font-size: 14px">' . $schoollogo->schoolname . '</span>', false, false, false, $reseth = true, $align = 'L', $autopadding = true);
        $schooladdress = $this->writeHTMLCell(false, 50, 7, 15, '<span style="font-weight: bold; font-size: 10px; text-align: center;">' . $schoollogo->address . '</span>', false, false, false, $reseth = true, $align = 'L', $autopadding = true);
        $title = $this->writeHTMLCell(false, 50, 7, 18, '<span style="font-size: 13px; text-align: center;">Statement of Account</span>', false, false, false, $reseth = true, $align = 'L', $autopadding = true);

        $image_fileright = public_path() . '/assets/images/ccsa/ccsa.png';
        $this->Image('@' . file_get_contents($image_fileright), 171, 9, 23, 17);
        // $title = $this->writeHTMLCell(false, 50, 40, 20, 'Statement of Account', false, false, false, $reseth=true, $align='L', $autopadding=true);
        // Ln();
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}


class MYPDFStatementOfAccountHccsi extends TCPDF
{

    //Page header
    public function Header()
    {
        $this->SetX(5);
        $this->Cell(10, 0, date('m/d/Y'), 0, false, 'L', 0, '', 0, false, 'T', 'M');

        $this->Cell(0, 0, 'Page ' . $this->getAliasNumPage(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        // $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        // $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        // $title = $this->writeHTMLCell(false, 50, 40, 20, 'Statement of Account', false, false, false, $reseth=true, $align='L', $autopadding=true);
        // Ln();
    }

    // // Page footer
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
