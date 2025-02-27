<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use App\RegistrarModel;
use App\FinanceModel;

class FinanceUtilityModel extends Model
{
    public static function resetv3_generatefees($studid, $glevel, $enrollid, $syid, $semid, $feesid)
    {
        $enroll_table = '';

        if($feesid == '' || $feesid == null || $feesid == 0)
        {
            $grantee = 1;
            if($glevel == 14 | $glevel == 15)
            {
                $_estud = db::table('sh_enrolledstud')
                    ->where('id', $enrollid)
                    ->first();
                if($_estud)
                {
                    $grantee = $_estud->grantee;
                }
            }
            elseif($glevel >= 17 && $levelid <= 20)
            {

            }
            else
            {
                $_estud = db::table('enrolledstud')
                    ->where('id', $enrollid)
                    ->first();

                if($_estud)
                {
                    $grantee = $_estud->grantee;
                }
            }


            $fees = db::table('tuitionheader')
                ->where('deleted', 0)
                ->where('levelid', $glevel)
                ->where('syid', $syid)
                // ->where('semid', $semid)
                ->where(function($q) use($glevel, $semid){
                    if($glevel == 14 || $glevel == 15)
                    {
                        if($semid == 3)
                        {
                            $q->where('semid', $semid);
                        }
                        else
                        {
                            if(FinanceModel::shssetup() == 0)
                            {
                                $q->where('semid', $semid);
                            }
                            else
                            {
                                $q->where('semid', '!=', 3);
                            }
                        }

                    }
                    elseif($glevel >= 17 && $glevel <= 21)
                    {
                        $q->where('semid', $semid);
                    }
                    else
                    {
                        if($semid == 3)
                        {
                            $q->where('semid', $semid);
                        }
                        else
                        {
                            $q->where('semid', '!=', 3);
                        }
                    }
                })
                ->where('grantee', $grantee)
                ->first();

            if(!$fees)
            {
                $fees = db::table('tuitionheader')
                    ->where('deleted', 0)
                    ->where('levelid', $glevel)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->first();

                if($fees)
                {
                    $feesid = $fees->id;
                }
                else{
                    $fees = db::table('tuitionheader')
                        ->where('deleted', 0)
                        ->where('levelid', $glevel)
                        ->where('syid', $syid)
                        // ->where('semid', $semid)
                        ->first();
                }
            }

            else
            {
                $feesid = $fees->id;
            }

        }

        if($glevel == 14 || $glevel == 15)
        {
            $enroll_table = 'sh_enrolledstud';
        }
        elseif($glevel >= 17 && $glevel <= 21)
        {
            $enroll_table = 'college_enrolledstud';
        }
        else
        {
            $enroll_table = 'enrolledstud';
        }

        $enrollinfo = db::table($enroll_table)
            ->where('id', $enrollid)
            ->first();

        if($enrollinfo)
        {
            $dateenrolled = $enrollinfo->createddatetime;

            db::table($enroll_table)
                ->where('id', $enrollinfo->id)
                ->update([
                    'feesid' => $feesid
                ]);
        }
        else
        {
            $dateenrolled = FinanceModel::getServerDateTime();
        }

        $tuition = db::table('tuitionheader')
            ->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'syid', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid', 'istuition', 'persubj', 'permop', 'permopid')
            ->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
            ->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
            ->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
            ->where('tuitionheader.id', $feesid)
            ->where('tuitiondetail.deleted', 0)
            ->get();


        if($glevel >= 17 && $glevel <=21)
        {
            $ausubjects = db::table('tuition_assessmentunit')
                ->where('deleted', 0)
                ->get();

            $ausubjects = collect($ausubjects);

            $totalunits = db::table('college_studsched')
                ->select('college_prospectus.subjectid as subjid', 'labunits', 'lecunits')
                ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                ->where('college_studsched.studid', $studid)
                ->where('college_studsched.deleted', 0)
                ->where('college_classsched.deleted', 0)
                ->where('college_classsched.syID', $syid)
                ->where('college_classsched.semesterID', $semid)
                ->where('college_studsched.schedstatus', '!=', 'DROPPED')
                // ->where('college_sections.section_specification', '!=', 2)
                ->get();

            $units = 0;

            foreach($totalunits as $subject)
            {
                $getau = $ausubjects->where('subjid', $subject->subjid)->first();

                if($getau)
                {
                    $units += $getau->assessmentunit;
                }
                else{
                    $units += $subject->lecunits + $subject->labunits;
                }
            }

            // if($totalunits)
            // {
            //     $units = $totalunits->totalunits;
            // }
            // else
            // {
            //     $units = 0;
            // }
        }

        // if(count($tuition) > 0)
        // {
        //     $feesid = $tuition[0]->id;

        //     db::table('studinfo')
        //         ->where('id', $studid)
        //         ->update([
        //             'feesid' => $feesid
        //         ]);
        // }

        $college_particulars = '';
        $tuitionamount = 0;
        $itemizedamount = 0;

        foreach($tuition as $tui)
        {
            if($glevel >= 17 && $glevel <=21)
            {
                if($tui->istuition == 1)
                {
                    // echo $tui->amount . ' * ' . $units;
                    $tuitionamount = $tui->amount * $units;
                    $college_particulars = ' | ' . $units . ' Units';
                }
                else
                {
                    $tuitionamount = $tui->amount;
                    $college_particulars = '';
                }

                if($tui->persubj == 1)
                {
                    $totalsubj = db::table('college_studsched')
                        ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                        ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                        ->where('college_studsched.studid', $studid)
                        ->where('college_studsched.deleted', 0)
                        ->where('college_classsched.deleted', 0)
                        ->where('college_classsched.syID', $syid)
                        ->where('college_classsched.semesterID', $semid)
                        ->count();

                    $tuitionamount *= $totalsubj;
                }

                if($tui->permop == 1)
                {
                    $paymentsetup = db::table('paymentsetup')
                        ->where('id', $tui->permopid)
                        ->first();

                    if($paymentsetup)
                    {
                        $tuitionamount *= $paymentsetup->noofpayment;
                    }
                }
            }
            else
            {
                $tuitionamount = $tui->amount;
                $college_particulars = '';
            }

            $sLedger = db::table('studledger')
                ->insert([
                    'studid' => $studid,
                    'enrollid' => $enrollid,
                    'syid' => $syid,
                    'semid' => $semid,
                    'classid' => $tui->classid,
                    'particulars' => $tui->particulars . $college_particulars,
                    'amount' => $tuitionamount,
                    'pschemeid' => $tui->pschemeid,
                    'deleted' => 0,
                    'createddatetime' => $dateenrolled
                ]);


                    //studledger Itemized

            $tuitionitems = db::table('tuitionitems')
                ->where('tuitiondetailid', $tui->tuitiondetailid)
                ->where('deleted', 0)
                ->get();

            foreach($tuitionitems as $tItems)
            {
                $checkitemized = db::table('studledgeritemized')
                    ->where('studid', $studid)
                    ->where('tuitionitemid', $tItems->id)
                    ->where('deleted', 0)
                    ->count();

                if($checkitemized == 0)
                {
                    if($tui->istuition == 1)
                    {
                        $itemizedamount = $tuitionamount;
                    }
                    else
                    {
                        $itemizedamount = $tItems->amount;
                    }


                    db::table('studledgeritemized')
                        ->insert([
                            'studid' => $studid,
                            'semid' => $semid,
                            'syid' => $syid,
                            'tuitiondetailid' => $tui->tuitiondetailid,
                            'classificationid' => $tui->classid,
                            'tuitionitemid' => $tItems->id,
                            'itemid' => $tItems->itemid,
                            'itemamount' => $itemizedamount,
                            // 'createdby' => auth()->user()->id,
                            'createddatetime' => RegistrarModel::getServerDateTime(),
                            'deleted' => 0
                        ]);
                }
            }

            $paymentsetup = db::table('paymentsetup')
                ->select('paymentsetup.id', 'paymentdesc', 'paymentsetup.noofpayment', 'paymentno', 'duedate', 'payopt', 'percentamount')
                ->leftjoin('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
                ->where('paymentsetup.id', $tui->pschemeid)
                ->where('paymentsetupdetail.deleted', 0)
                ->get();


            if($paymentsetup[0]->payopt == 'divided')
            {
                $divPay = 0;

                if(count($paymentsetup) > 1)
                {
                    $paymentno = $paymentsetup[0]->noofpayment;
                    $divPay = $tuitionamount / $paymentno;
                    $divPay = number_format($divPay, 2, '.', '');
                }
                else
                {
                    $paymentno = 1;
                    $divPay = $tuitionamount;
                    $divPay = number_format($divPay, 2, '.', '');
                }

                // echo ' divPay: ' . $divPay;
                $paycount = 0;
                $paytAmount = 0;
                $paydisbalance = 0;

                foreach($paymentsetup as $pay)
                {
                    $paycount += 1;
                    $paytAmount += $divPay;

                    if($paycount != $paymentno)
                    {
                        $scheditem = db::table('studpayscheddetail')
                            ->insert([
                                'studid' => $studid,
                                'enrollid' => $enrollid,
                                'syid' => $syid,
                                'semid' => $semid,
                                'tuitiondetailid' => $tui->tuitiondetailid,
                                'particulars' => $tui->particulars,
                                'duedate' => $pay->duedate,
                                'paymentno' => $pay->paymentno,
                                'amount' => $divPay,
                                'balance' => $divPay,
                                'classid' => $tui->classid
                            ]);
                    }
                    else
                    {
                        // echo ' payAmount: '. $paytAmount . ' <= ' . $tuitionamount . '; ';
                        if($paytAmount <= $tuitionamount)
                        {
                            $paydisbalance = $tuitionamount - $paytAmount;
                            $paydisbalance = number_format($paydisbalance, 2, '.', '');

                            $divPay += $paydisbalance;

                            // echo ' paydisbalance: ' . $paydisbalance;
                            // echo ' +divPay: '. $divPay;
                            $scheditem = db::table('studpayscheddetail')
                                ->insert([
                                    'studid' => $studid,
                                    'enrollid' => $enrollid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'tuitiondetailid' => $tui->tuitiondetailid,
                                    'particulars' => $tui->particulars,
                                    'duedate' => $pay->duedate,
                                    'paymentno' => $pay->paymentno,
                                    'amount' => $divPay,
                                    'balance' => $divPay,
                                    'classid' => $tui->classid
                                ]);

                        }
                        else
                        {
                            $paydisbalance = $paytAmount - $tuitionamount;
                            $paydisbalance = number_format($paydisbalance, 2, '.', '');


                            // $divPay = number_format($divPay - $paydisbalance);
                            $divPay -= $paydisbalance;
                            // echo ' paydisbalance: ' . $paydisbalance;
                            // echo ' -divPay: '. $divPay;

                            $scheditem = db::table('studpayscheddetail')
                            ->insert([
                                'studid' => $studid,
                                'enrollid' => $enrollid,
                                'syid' => $syid,
                                'semid' => $semid,
                                'tuitiondetailid' => $tui->tuitiondetailid,
                                'particulars' => $tui->particulars,
                                'duedate' => $pay->duedate,
                                'paymentno' => $pay->paymentno,
                                'amount' => $divPay,
                                'balance' => $divPay,
                                'classid' => $tui->classid
                            ]);
                        }
                    }
                }
            }
            else
            {
                $paycount = 0;
                $pAmount = 0;
                $curAmount = $tuitionamount;

                foreach($paymentsetup as $pay)
                {
                    $paycount +=1;
                    if($paycount < count($paymentsetup))
                    {
                        if($curAmount > 0)
                        {
                            $pAmount = round($pay->percentamount * ($tuitionamount/100), 2);
                            $curAmount = (round($curAmount - $pAmount, 2));

                            $scheditem = db::table('studpayscheddetail')
                            ->insert([
                                    'studid' => $studid,
                                    'enrollid' => $enrollid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'tuitiondetailid' => $tui->tuitiondetailid,
                                    'particulars' => $tui->particulars,
                                    'duedate' => $pay->duedate,
                                    'paymentno' => $pay->paymentno,
                                    'amount' => $pAmount,
                                    'balance' => $pAmount,
                                    'classid' => $tui->classid
                                ]);
                        }
                    }
                    else
                    {
                        if($curAmount > 0)
                        {
                            $scheditem = db::table('studpayscheddetail')
                            ->insert([
                                    'studid' => $studid,
                                    'enrollid' => $enrollid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'tuitiondetailid' => $tui->tuitiondetailid,
                                    'particulars' => $tui->particulars,
                                    'duedate' => $pay->duedate,
                                    'paymentno' => $pay->paymentno,
                                    'amount' => $curAmount,
                                    'balance' => $curAmount,
                                    'classid' => $tui->classid
                                ]);
                            $curAmount = 0;
                        }
                    }
                }
            }
        }
    }

    public static function resetv3_generatebookentries($studid, $levelid, $syid, $semid)
    {
        $be_setup = db::table('bookentrysetup')
            ->join('itemclassification', 'bookentrysetup.classid', '=', 'itemclassification.id')
            ->first();

        $bookentries = db::table('bookentries')
            ->select(db::raw('bookentries.id, bookid, bookentries.studid, bookentries.classid, mopid, bookentries.amount, bestatus, items.description, bookentries.createddatetime'))
            ->leftJoin('items', 'bookentries.bookid', '=', 'items.id')
            ->where('studid', $studid)
            ->where('bestatus', 'APPROVED')
            ->where('bookentries.deleted', 0)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if($semid == 3)
                    {
                        $q->where('semid', $semid);
                    }
                    else
                    {
                        if(FinanceModel::shssetup() == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    }

                }
                elseif($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semid);
                }
                else
                {
                    if($semid == 3)
                    {
                        $q->where('semid', $semid);
                    }
                    else
                    {
                        $q->where('semid', '!=', 3);
                    }
                }
            })
            ->get();

        foreach($bookentries as $be)
        {
            db::table('studledger')
                ->insert([
                    'studid' => $studid,
                    'semid' => $semid,
                    'syid' => $syid,
                    'classid' => $be_setup->classid,
                    'particulars' => 'BOOKS: ' . $be->description,
                    'amount' => $be->amount,
                    'ornum' => $be->id,
                    'pschemeid' => $be_setup->mopid,
                    'deleted' => 0,
                    // 'createdby' => auth()->user()->id,
                    'createddatetime' => $be->createddatetime
                ]);

            db::table('studledgeritemized')
                ->insert([
                    'studid' => $studid,
                    'semid' => $semid,
                    'syid' => $syid,
                    'classificationid' => $be_setup->classid,
                    'itemid' => $be_setup->itemid,
                    'itemamount' => $be->amount,
                    // 'createdby' => auth()->user()->id,
                    'createddatetime' => $be->createddatetime
                ]);

            $modeofpayment = db::table('paymentsetupdetail')
                ->where('paymentid', $be_setup->mopid)
                ->where('deleted', 0)
                ->get();

            $noofpayment = count($modeofpayment);

            $divAmount = $be->amount / $noofpayment;
            $divAmount = number_format($divAmount, 2, '.', '');
            $paymentno = 0;
            $total = 0;

            foreach($modeofpayment as $mop)
            {
                if($divAmount > 0)
                {
                    $paymentno += 1;

                    if($paymentno < $noofpayment)
                    {
                        $total += $divAmount;

                        db::table('studpayscheddetail')
                          ->insert([
                            'studid' => $studid,
                            'semid' => $semid,
                            'syid' => $syid,
                            'classid' => $be_setup->classid,
                            'paymentno' => $paymentno,
                            'particulars' => 'BOOKS: ' . $be->description,
                            'duedate' => $mop->duedate,
                            'amount' => $divAmount,
                            'balance' => $divAmount
                          ]);
                    }
                    else
                    {

                        $total = $be->amount - $total;
                        db::table('studpayscheddetail')
                        ->insert([
                            'studid' => $studid,
                            'semid' => $semid,
                            'syid' => $syid,
                            'classid' => $be_setup->classid,
                            'paymentno' => $paymentno,
                            'particulars' => 'BOOKS: ' . $be->description,
                            'duedate' => $mop->duedate,
                            'amount' => $total,
                            'balance' => $total,
                        ]);
                    }
                }
            }
        }


    }

    public static function resetv3_generatepayments($studid, $levelid, $enrollid, $syid, $semid)
    {
        $kind = '';
        $kinddesc = '';
        $ledgeramount = 0;
        $ledgerparticulars = '';

        $chrngtrans = db::table('chrngtrans')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if($semid == 3)
                    {
                        $q->where('semid', $semid);
                    }
                    else
                    {
                        if(FinanceModel::shssetup() == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    }

                }
                elseif($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semid);
                }
                else
                {
                    if($semid == 3)
                    {
                        $q->where('semid', $semid);
                    }
                    else
                    {
                        $q->where('semid', '!=', 3);
                    }
                }
            })
            ->where('cancelled', 0)
            // ->where('ornum', '1973863')
            ->get();

        foreach($chrngtrans as $trans)
        {
            $transno = $trans->transno;
            $ornum = $trans->ornum;
            $chrngtransid = $trans->id;

            $chrngcashtrans = db::table('chrngcashtrans')
                ->where('transno', $transno)
                ->where('studid', $studid)
                ->where('deleted', 0)
                ->get();

            $runcount = 0;

            foreach($chrngcashtrans as $cashtrans)
            {
                $lineamount = $cashtrans->amount;

                if($cashtrans->kind != 'item')
                {
                    $kind = 0;
                    $ledgeramount += $cashtrans->amount;

                }
                else
                {
                    $kind = 1;
                }

                if($cashtrans->kind != 'item')
                {
                    $payscheddetail = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('classid', $cashtrans->classid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if($semid == 3)
                                {
                                    $q->where('semid', $semid);
                                }
                                else
                                {
                                    if(FinanceModel::shssetup() == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }

                            }
                            elseif($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', $semid);
                            }
                            else
                            {
                                if($semid == 3)
                                {
                                    $q->where('semid', $semid);
                                }
                                else
                                {
                                    $q->where('semid', '!=', 3);
                                }
                            }
                        })
                        ->where('balance', '>', 0)
                        ->get();

                    foreach($payscheddetail as $scheddetail)
                    {
                        if($lineamount > 0)
                        {
                            $bookclassid = db::table('bookentrysetup')->first()->classid;

                            if($scheddetail->classid == $bookclassid)
                            {
                                $_bookpaysched = db::table('studpayscheddetail')
                                    // ->where('id', $cashtrans->payscheddetailid)
                                    ->where('particulars', $cashtrans->particulars)
                                    ->where('studid', $studid)
                                    ->where('syid', $syid)
                                    ->where(function($q) use($levelid, $semid){
                                        if($levelid == 14 || $levelid == 15)
                                        {
                                            if($semid == 3)
                                            {
                                                $q->where('semid', 3);
                                            }
                                            else
                                            {
                                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                                {
                                                    $q->where('semid', $semid);
                                                }
                                            }
                                        }
                                        elseif($levelid >= 17 && $levelid <= 21)
                                        {
                                            $q->where('semid', $semid);
                                        }
                                        else
                                        {
                                            if($semid == 3)
                                            {
                                                $q->where('semid', 3);
                                            }
                                            else
                                            {
                                                $q->where('semid', '!=', 3);
                                            }
                                        }
                                    })
                                    ->where('balance', '>', 0)
                                    ->where('deleted', 0)
                                    ->first();

                                if($lineamount > $_bookpaysched->balance)
                                {
                                    db::table('studpayscheddetail')
                                        ->where('id', $_bookpaysched->id)
                                        ->update([
                                            'amountpay' => $_bookpaysched->amountpay + $_bookpaysched->balance,
                                            'balance' => 0,
                                            'updatedby' => auth()->user()->id,
                                            'updateddatetime' => FinanceModel::getServerDateTime(),
                                        ]);

                                    // if($scheddetail->classid == 2)
                                    // {
                                    //     echo 'aaa; <br>';
                                    // }

                                    // FinanceModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $_bookpaysched->id, $_bookpaysched->classid, $_bookpaysched->balance);
                                    FinanceUtilityModel::procItemized($_bookpaysched->tuitiondetailid, $cashtrans->payscheddetailid, $_bookpaysched->balance, $_bookpaysched->classid, $levelid, $chrngtransid, $ornum, $studid, $kind, $syid, $semid, $cashtrans->itemid);

                                    $lineamount -= $_bookpaysched->balance;


                                }
                                else
                                {
                                    db::table('studpayscheddetail')
                                        ->where('id', $_bookpaysched->id)
                                        ->update([
                                            'amountpay' => $_bookpaysched->amountpay + $lineamount,
                                            'balance' => $_bookpaysched->balance - $lineamount,
                                            'updatedby' => auth()->user()->id,
                                            'updateddatetime' => FinanceModel::getServerDateTime(),
                                        ]);

                                    // FinanceModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $_bookpaysched->id, $_bookpaysched->classid, $lineamount);
                                    FinanceUtilityModel::procItemized($_bookpaysched->tuitiondetailid, $cashtrans->payscheddetailid, $lineamount, $_bookpaysched->classid, $levelid, $chrngtransid, $ornum, $studid, $kind, $syid, $semid, $cashtrans->itemid);


                                    $lineamount = 0;
                                }
                            }
                            else
                            {
                                if($lineamount > $scheddetail->balance)
                                {
                                    db::table('studpayscheddetail')
                                        ->where('id', $scheddetail->id)
                                        ->update([
                                            'amountpay' => $scheddetail->amountpay + $scheddetail->balance,
                                            'balance' => 0,
                                            // 'updatedby' => auth()->user()->id,
                                            'updateddatetime' => FinanceModel::getServerDateTime(),
                                        ]);

                                    FinanceUtilityModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $scheddetail->balance, $scheddetail->classid, $levelid, $trans->id, $ornum, $studid, $kind, $syid, $semid, $cashtrans->itemid);

                                    $lineamount -= $scheddetail->balance;
                                }
                                else
                                {
                                    db::table('studpayscheddetail')
                                        ->where('id', $scheddetail->id)
                                        ->update([
                                            'amountpay' => $scheddetail->amountpay + $lineamount,
                                            'balance' => $scheddetail->balance - $lineamount,
                                            // 'updatedby' => auth()->user()->id,
                                            'updateddatetime' => FinanceModel::getServerDateTime(),
                                        ]);


                                    FinanceUtilityModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $lineamount, $scheddetail->classid, $levelid, $chrngtransid, $ornum, $studid, $kind, $syid, $semid, $cashtrans->itemid);


                                    $lineamount = 0;
                                }
                            }
                        }
                    }

                    if($lineamount > 0)
                    {
                        $groupname = '';

                        nextgroup:

                        $runcount += 1;

                        if($runcount <= 30)
                        {
                            if($groupname == '')
                            {
                                $groupname = 'OTH';
                            }
                            elseif($groupname == 'OTH')
                            {
                                $groupname = 'MISC';
                            }
                            else
                            {
                                $groupname = 'TUI';
                            }

                            $setupclassid = array();

                            $chrngsetup = db::table('chrngsetup')
                                ->where('deleted', 0)
                                ->where('groupname', $groupname)
                                ->get();


                            foreach($chrngsetup as $setup)
                            {
                                array_push($setupclassid, $setup->classid);
                            }

                            $payscheddetail = db::table('studpayscheddetail')
                                ->where('studid', $studid)
                                ->where('deleted', 0)
                                ->where('syid', $syid)
                                ->where(function($q) use($levelid, $semid){
                                    if($levelid == 14 || $levelid == 15)
                                    {
                                        if($semid == 3)
                                        {
                                            $q->where('semid', $semid);
                                        }
                                        else
                                        {
                                            if(FinanceModel::shssetup() == 0)
                                            {
                                                $q->where('semid', $semid);
                                            }
                                        }

                                    }
                                    elseif($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                    else
                                    {
                                        if($semid == 3)
                                        {
                                            $q->where('semid', $semid);
                                        }
                                        else
                                        {
                                            $q->where('semid', '!=', 3);
                                        }
                                    }
                                })
                                // ->where(function($q) use($setupclassid){
                                //     if(count($setupclassid) > 0)
                                //     {
                                //         $q->whereIn('classid', $setupclassid);
                                //     }
                                // })
                                ->where('balance', '>', 0)
                                ->get();

                            foreach($payscheddetail as $scheddetail)
                            {
                                if($lineamount > 0)
                                {
                                    if($lineamount > $scheddetail->balance)
                                    {
                                        db::table('studpayscheddetail')
                                            ->where('id', $scheddetail->id)
                                            ->update([
                                                'amountpay' => $scheddetail->amountpay + $scheddetail->balance,
                                                'balance' => 0,
                                                // 'updatedby' => auth()->user()->id,
                                                'updateddatetime' => FinanceModel::getServerDateTime(),
                                            ]);

                                        FinanceUtilityModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $scheddetail->balance, $scheddetail->classid, $levelid, $trans->id, $ornum, $studid, $kind, $syid, $semid, $cashtrans->itemid);

                                        $lineamount -= $scheddetail->balance;


                                    }
                                    else
                                    {
                                        db::table('studpayscheddetail')
                                            ->where('id', $scheddetail->id)
                                            ->update([
                                                'amountpay' => $scheddetail->amountpay + $lineamount,
                                                'balance' => $scheddetail->balance - $lineamount,
                                                // 'updatedby' => auth()->user()->id,
                                                'updateddatetime' => FinanceModel::getServerDateTime()
                                            ]);


                                        FinanceUtilityModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $lineamount, $scheddetail->classid, $levelid, $chrngtransid, $ornum, $studid, $kind, $syid, $semid, $cashtrans->itemid);


                                        $lineamount = 0;
                                    }
                                }
                            }
                        }
                        else
                        {
                            $lineamount = 0;
                        }
                    }

                    if($lineamount > 0)
                    {
                        // goto nextgroup;
                        $lineamount = 0;
                    }

                    // return $lineamount;
                }
            }

            $transkind = db::table('chrngcashtrans')
                ->where('transno', $transno)
                ->where('studid', $studid)
                ->where('deleted', 0)
                ->groupBy('kind')
                ->get();

            $ledgerparticulars = '';

            foreach($transkind as $particulars)
            {
                if($particulars->kind != null && $particulars->kind != 'item')
                {
                    if($particulars->kind == 'reg')
                    {
                        $kinddesc = 'REGISTRATION';
                    }
                    elseif($particulars->kind == 'dp')
                    {
                        $kinddesc = 'DOWNPAYMENT';
                    }
                    elseif($particulars->kind == 'misc')
                    {
                        $kinddesc = 'MISCELLANEOUS';
                    }
                    elseif($particulars->kind == 'tui')
                    {
                        $kinddesc = 'TUITION';
                    }
                    elseif($particulars->kind == 'oth')
                    {
                        $kinddesc = 'OTHER FEES/BOOKS';
                    }
                    elseif($particulars->kind == 'old')
                    {
                        $kinddesc = 'OLD ACCOUNT';
                    }



                    if($ledgerparticulars == '')
                    {
                        $ledgerparticulars = $kinddesc;
                    }
                    else
                    {

                        $ledgerparticulars .= '/' . $kinddesc;

                    }
                }
            }

            // return 'particulars: ' . $ledgerparticulars;

            $paytype = $trans->paytype;
            $timenow = date_create($trans->transdate);
            $timenow = date_format($timenow, 'H:i');

            if($ledgerparticulars != '')
            {
                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'enrollid' => $enrollid,
                        'semid' => $semid,
                        'syid' => $syid,
                        'particulars' =>'PAYMENT FOR ' . $ledgerparticulars . ' - OR: ' . $ornum . ' - ' . $paytype,
                        'payment' => $ledgeramount,
                        'ornum' => $ornum,
                        'paytype' => $paytype,
                        'transid' => $trans->id,
                        // 'createdby' => auth()->user()->id,
                        'createddatetime' => $trans->transdate . ' ' . $timenow,
                        'deleted' => 0
                    ]);

                $ledgeramount = 0;
            }

            // return $trans->ornum;

        }
    }

    public static function procItemized($tuitiondetailid, $payschedid ,$amount, $classid, $levelid, $chrngtransid, $ornum, $studid, $kind, $syid, $semid, $itemid)
    {
        $setup = db::table('chrngsetup')
            ->where('classid', $classid)
            ->where('deleted', 0)
            ->first();

        if($setup)
        {
            // echo $classid . '<br>';
            if($setup->itemized == 0)
            {
                if($amount > 0)
                {
                    $itemized = db::table('studledgeritemized')
                        ->where('tuitiondetailid', $tuitiondetailid)
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('syid', $syid)
                        ->whereColumn('totalamount', '<', 'itemamount')
                        ->where('classificationid', $classid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(FinanceModel::shssetup() == 0)
                                {
                                    $q->where('semid', $semid);
                                }
                            }
                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->get();

                    // return 'aaaa';

                    if(count($itemized) == 0)
                    {
                        $itemized = db::table('studledgeritemized')
                            ->where('studid', $studid)
                            ->where('deleted', 0)
                            ->where('syid', $syid)
                            ->whereColumn('totalamount', '<', 'itemamount')
                            ->where('classificationid', $classid)
                            ->where(function($q) use($levelid, $semid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(FinanceModel::shssetup() == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }
                                if($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', $semid);
                                }
                            })
                            ->get();

                        if(count($itemized) == 0)
                        {
                            $itemized = db::table('studledgeritemized')
                                ->where('studid', $studid)
                                ->where('deleted', 0)
                                ->whereColumn('totalamount', '<', 'itemamount')
                                ->where('syid', $syid)
                                ->where(function($q) use($levelid, $semid){
                                    if($levelid == 14 || $levelid == 15)
                                    {
                                        if(FinanceModel::shssetup() == 0)
                                        {
                                            $q->where('semid', $semid);
                                        }
                                    }
                                    if($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                })
                                ->get();
                        }
                    }

                    // echo 'classid: ' . $classid . '<br>';

                    // return $itemized;

                    foreach($itemized as $item)
                    {
                        $balance = $item->itemamount - $item->totalamount;
                        if($amount > $balance)
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $balance,
                                    'updateddatetime' => FinanceModel::getServerDateTime(),
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    // 'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $balance,
                                    'studid' => $studid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'kind' => $kind,
                                    'createddatetime' => FinanceModel::getServerDateTime()
                                    // 'createdby' => auth()->user()->id,
                                ]);

                            $amount -= $balance;
                        }
                        else
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $amount,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    // 'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $amount,
                                    'studid' => $studid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'kind' => $kind,
                                    'createddatetime' => FinanceModel::getServerDateTime(),
                                    // 'createdby' => auth()->user()->id,
                                ]);

                            $amount = 0;
                        }
                    }
                }
            }
            else
            {
                if($amount > 0)
                {
                    $itemized = db::table('studledgeritemized')
                        ->where('itemid', $itemid)
                        // ->where('id', $payschedid)
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(FinanceModel::shssetup() == 0)
                                {
                                    $q->where('semid', $semid);
                                }
                            }
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->whereColumn('totalamount', '<', 'itemamount')
                        ->where('deleted', 0)
                        ->get();

                    if(count($itemized) == 0)
                    {
                        $itemized = db::table('studledgeritemized')
                            ->where('classificationid', $classid)
                            ->where('studid', $studid)
                            ->where('syid', $syid)
                            ->where(function($q) use($levelid, $semid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(FinanceModel::shssetup() == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }
                                if($levelid >= 17 && $levelid <= 20)
                                {
                                    $q->where('semid', $semid);
                                }
                            })
                            ->whereColumn('totalamount', '<', 'itemamount')
                            ->where('deleted', 0)
                            ->get();

                        if(count($itemized) == 0)
                        {
                            $itemized = db::table('studledgeritemized')
                                // ->where('classificationid', $classid)
                                ->where('studid', $studid)
                                ->where('syid', $syid)
                                ->where(function($q) use($levelid, $semid){
                                    if($levelid == 14 || $levelid == 15)
                                    {
                                        if(FinanceModel::shssetup() == 0)
                                        {
                                            $q->where('semid', $semid);
                                        }
                                    }
                                    if($levelid >= 17 && $levelid <= 20)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                })
                                ->whereColumn('totalamount', '<', 'itemamount')
                                ->where('deleted', 0)
                                ->get();
                        }
                    }

                    foreach($itemized as $item)
                    {
                        $balance = $item->itemamount - $item->totalamount;

                        if($amount > $balance)
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $balance,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    // 'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $balance,
                                    'studid' => $studid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'kind' => $kind,
                                    'createddatetime' => FinanceModel::getServerDateTime()
                                    // 'createdby' => auth()->user()->id
                                ]);

                            $amount -= $balance;
                        }
                        else
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $amount,
                                    'updateddatetime' => FinanceModel::getServerDateTime(),
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    // 'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $amount,
                                    'studid' => $studid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'kind' => $kind,
                                    'createddatetime' => FinanceModel::getServerDateTime(),
                                    // 'createdby' => auth()->user()->id,
                                ]);

                            $amount = 0;
                        }
                    }
                }
            }

            if($amount > 0)
            {
                // $itemized = db::table('studledgeritemized')
                //     // ->where('tuitiondetailid', $tuitiondetailid)
                //     ->where('deleted', 0)
                //     ->whereColumn('totalamount', '<', 'itemamount')
                //     ->where(function($q) use($levelid, $semid){
                //         if($levelid == 14 || $levelid == 15)
                //         {
                //             if(FinanceModel::shssetup() == 0)
                //             {
                //                 $q->where('semid', $semid);
                //             }
                //         }
                //         if($levelid >= 17 && $levelid <= 21)
                //         {
                //             $q->where('semid', $semid);
                //         }
                //     })
                //     ->get();

                $itemized = db::table('studledgeritemized')
                        // ->where('itemid', $itemid)
                        ->where('classificationid', $classid)
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(FinanceModel::shssetup() == 0)
                                {
                                    $q->where('semid', $semid);
                                }
                            }
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->whereColumn('totalamount', '<', 'itemamount')
                        ->where('deleted', 0)
                        ->get();

                foreach($itemized as $item)
                {
                    $balance = $item->itemamount - $item->totalamount;
                    if($amount > $balance)
                    {
                        db::table('studledgeritemized')
                            ->where('id', $item->id)
                            ->update([
                                'totalamount' => $item->totalamount + $balance,
                                'updateddatetime' => FinanceModel::getServerDateTime()
                                // 'updatedby' => auth()->user()->id
                            ]);

                        db::table('chrngtransitems')
                            ->insert([
                                'chrngtransid' => $chrngtransid,
                                // 'chrngtransdetailid' => $chrngtransdetailid,
                                'ornum' => $ornum,
                                'itemid' => $item->itemid,
                                'classid' => $classid,
                                'amount' => $balance,
                                'studid' => $studid,
                                'syid' => $syid,
                                'semid' => $semid,
                                'kind' => $kind,
                                'createddatetime' => FinanceModel::getServerDateTime()
                                // 'createdby' => auth()->user()->id,
                            ]);

                        $amount -= $balance;
                    }
                    else
                    {
                        db::table('studledgeritemized')
                            ->where('id', $item->id)
                            ->update([
                                'totalamount' => $item->totalamount + $amount,
                                'updateddatetime' => FinanceModel::getServerDateTime()
                                // 'updatedby' => auth()->user()->id
                            ]);

                        db::table('chrngtransitems')
                            ->insert([
                                'chrngtransid' => $chrngtransid,
                                // 'chrngtransdetailid' => $chrngtransdetailid,
                                'ornum' => $ornum,
                                'itemid' => $item->itemid,
                                'classid' => $classid,
                                'amount' => $amount,
                                'studid' => $studid,
                                'syid' => $syid,
                                'semid' => $semid,
                                'kind' => $kind,
                                'createddatetime' => FinanceModel::getServerDateTime()
                                // 'createdby' => auth()->user()->id,
                            ]);

                        $amount = 0;
                    }
                }

            }
        }
        else
        {

        }

        // echo 'amount: ' . $amount;
    }

	public static  function resetv3_generatelabfees($studid, $levelid, $enrollid, $syid, $semid)
    {
        if($levelid >= 17 && $levelid <= 21)
        {
            $enrollinfo = db::table('college_enrolledstud')
                ->where('id', $enrollid)
                ->first();

            if($enrollinfo)
            {
                $dateenrolled = $enrollinfo->createddatetime;
            }
            else
            {
                $dateenrolled = FinanceModel::getServerDateTime();
            }

            $labfees = db::table('labfees')
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('deleted', 0)
                ->get();

            $labsubjects = array();

            foreach($labfees as $labfee)
            {
                array_push($labsubjects, $labfee->subjid);
            }

            $studsched = db::table('college_studsched')
                ->select('college_prospectus.subjectID', 'college_prospectus.subjCode')
                ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                ->where('college_studsched.studid', $studid)
                ->where('college_studsched.deleted', 0)
                ->where('college_classsched.deleted', 0)
                ->where('college_classsched.syID', $syid)
                ->where('college_classsched.semesterID', $semid)
                ->whereIn('college_prospectus.subjectID', $labsubjects)
                ->get();

            foreach($studsched as $sched)
            {
                $lab = db::table('labfees')
                    ->where('subjid', $sched->subjectID)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('deleted', 0)
                    ->first();

                if($lab)
                {
                    $labfee_setup = db::table('labfee_setup')
                        ->where('semid', $semid)
                        ->first();

                    $labfee_particulars = $labfee_particulars = 'LABORATORY FEE(' . $sched->subjCode . ')' ;

                    $labfee = $lab->amount;

                    $sLedger = db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'enrollid' => $enrollid,
                        'syid' => $syid,
                        'semid' => $semid,
                        'classid' => $labfee_setup->classid,
                        'particulars' => $labfee_particulars,
                        'amount' => $labfee,
                        'pschemeid' => $labfee_setup->mop,
                        'deleted' => 0,
                        'createddatetime' => $dateenrolled
                    ]);

                    $paymentsetup = db::table('paymentsetup')
                        ->select('paymentsetup.id', 'paymentdesc', 'paymentsetup.noofpayment', 'paymentno', 'duedate', 'payopt', 'percentamount')
                        ->leftjoin('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
                        ->where('paymentsetup.id', $labfee_setup->mop)
                        ->where('paymentsetupdetail.deleted', 0)
                        ->get();

                    $divPay = 0;


                    if(count($paymentsetup) > 1)
                    {
                        $paymentno = $paymentsetup[0]->noofpayment;
                        $divPay = $labfee / $paymentno;
                        $divPay = number_format($divPay, 2, '.', '');
                    }
                    else
                    {
                        $paymentno = 1;
                        $divPay = $labfee;
                        $divPay = number_format($divPay, 2, '.', '');
                    }

                    // echo ' divPay: ' . $divPay;
                    $paycount = 0;
                    $paytAmount = 0;
                    $paydisbalance = 0;

                    foreach($paymentsetup as $pay)
                    {
                        $paycount += 1;
                        $paytAmount += $divPay;

                        if($paycount != $paymentno)
                        {
                            $scheditem = db::table('studpayscheddetail')
                                ->insert([
                                    'studid' => $studid,
                                    'enrollid' => $enrollinfo->id,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'tuitiondetailid' => 0,
                                    'particulars' => $labfee_particulars,
                                    'duedate' => $pay->duedate,
                                    'paymentno' => $pay->paymentno,
                                    'amount' => $divPay,
                                    'balance' => $divPay,
                                    'classid' => $labfee_setup->classid
                                ]);


                        }
                        else
                        {
                            // echo ' payAmount: '. $paytAmount . ' <= ' . $tuitionamount . '; ';
                            if($paytAmount <= $labfee)
                            {
                                $paydisbalance = $labfee - $paytAmount;
                                $paydisbalance = number_format($paydisbalance, 2, '.', '');

                                $divPay += $paydisbalance;

                                // echo ' paydisbalance: ' . $paydisbalance;
                                // echo ' +divPay: '. $divPay;
                                $scheditem = db::table('studpayscheddetail')
                                    ->insert([
                                        'studid' => $studid,
                                        'enrollid' => $enrollid,
                                        'syid' => $syid,
                                        'semid' => $semid,
                                        'tuitiondetailid' => 0,
                                        'particulars' => $labfee_particulars,
                                        'duedate' => $pay->duedate,
                                        'paymentno' => $pay->paymentno,
                                        'amount' => $divPay,
                                        'balance' => $divPay,
                                        'classid' => $labfee_setup->classid
                                    ]);

                            }
                            else
                            {
                                $paydisbalance = $paytAmount - $labfee;
                                $paydisbalance = number_format($paydisbalance, 2, '.', '');


                                // $divPay = number_format($divPay - $paydisbalance);
                                $divPay -= $paydisbalance;
                                // echo ' paydisbalance: ' . $paydisbalance;
                                // echo ' -divPay: '. $divPay;

                                $scheditem = db::table('studpayscheddetail')
                                    ->insert([
                                        'studid' => $studid,
                                        'enrollid' => $enrollid,
                                        'syid' => $syid,
                                        'semid' => $semid,
                                        'tuitiondetailid' => 0,
                                        'particulars' => $labfee_particulars,
                                        'duedate' => $pay->duedate,
                                        'paymentno' => $pay->paymentno,
                                        'amount' => $divPay,
                                        'balance' => $divPay,
                                        'classid' => $labfee_setup->classid
                                    ]);
                            }
                        }
                    }
                }
            }


        }
    }

    public static function resetv3_generateoldaccounts($studid, $levelid, $syid, $semid)
    {

        $balforwardsetup = db::table('balforwardsetup')
            ->first();

        $balclassid = $balforwardsetup->classid;

        $studledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('classid', $balclassid)
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20 )
                {
                    $q->where('semid', $semid);
                }
            })
            ->get();

        foreach($studledger as $ledger)
        {
            $studpaysched =  db::table('studpayscheddetail')
                ->where('studid', $studid)
                ->where('particulars', $ledger->particulars)
                ->where('syid', $syid)
                ->where('deleted', 0)
                ->where(function($q) use($levelid, $semid){
                    if($levelid == 14 || $levelid == 15)
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    }

                    if($levelid >= 17 && $levelid <= 20)
                    {
                        $q->where('semid', $semid);
                    }
                })
                ->first();

            if(!$studpaysched)
            {
                db::table('studpayscheddetail')
                    ->insert([
                        'studid' => $studid,
                        'semid' => $semid,
                        'syid' => $syid,
                        'classid' => $balclassid,
                        'paymentno' => 1,
                        'particulars' => $ledger->particulars,
                        // 'duedate' => $mop->duedate,
                        'amount' => $ledger->amount,
                        'amountpay' => 0,
                        'balance' => $ledger->amount,
                        // 'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);

                db::table('studledgeritemized')
                    ->insert([
                        'studid' => $studid,
                        'semid' => $semid,
                        'syid' => $syid,
                        'classificationid' => $balclassid,
                        'itemamount' => $ledger->amount,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }
        }
    }


	public static function resetv3_generateoa_v2($studid, $levelid, $syid, $semid, $oaid)
    {
        // $oldaccounts = collect($oldaccounts);
        $totalamount = 0;

        $balsetup = db::table('balforwardsetup')
            ->first();

        if($oaid != 0)
        {
            if($balsetup->classified != 1)
            {
                $oa = db::table('oldaccounts')
                    ->select(db::raw('headerid, oldaccountdetails.id as detailid, studid, syid, semid, syfrom, semfrom, classid, SUM(amount) AS amount, oldaccounts.createddatetime'))
                    ->join('oldaccountdetails', 'oldaccounts.id', '=', 'oldaccountdetails.headerid')
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('oldaccounts.id', $oaid)
                    ->where('oldaccounts.deleted', 0)
                    ->where('oldaccountdetails.deleted', 0)
                    ->first();

                if($oa)
                {
                    $syfromdesc = db::table('sy')
                        ->where('id', $oa->syfrom)
                        ->first()
                        ->sydesc;

                    $semfromdesc = db::table('semester')
                        ->where('id', $oa->semfrom)
                        ->first()
                        ->semester;

                    $sydesc = db::table('sy')
                        ->where('id', $oa->syid)
                        ->first()
                        ->sydesc;

                    $semdesc = db::table('semester')
                        ->where('id', $oa->semid)
                        ->first()
                        ->semester;


                    FinanceUtilityModel::oa_debit($studid, $oa, $balsetup, $levelid, $syfromdesc, $semfromdesc, $sydesc, $semdesc);
                    FinanceUtilityModel::oa_credit($studid, $oa, $balsetup, $levelid, $syfromdesc, $semfromdesc, $sydesc, $semdesc);
                }
            }
            else{
                $oaarray = array();
                $totalbalance = 0;
                $oa_syfrom = 0;
                $oa_semfrom = 0;
                $semdesc = '';
                $sydesc = '';
                $createddatetime = '';

                $oldaccounts = db::table('oldaccounts')
                    ->select(db::raw('headerid, oldaccountdetails.id as detailid, studid, syid, semid, syfrom, semfrom, classid, SUM(amount) AS amount, oldaccounts.createddatetime, description,
                        sy.sydesc, semester.`semester` as semdesc, semfrom.`semester` AS semfromdesc, syfrom.`sydesc` AS syfromdesc, oldaccounts.createddatetime'))
                    ->join('oldaccountdetails', 'oldaccounts.id', '=', 'oldaccountdetails.headerid')
                    ->join('itemclassification', 'oldaccountdetails.classid', '=', 'itemclassification.id')
                    ->join('sy', 'oldaccounts.syid', '=', 'sy.id')
                    ->join('semester', 'oldaccounts.semid', '=', 'semester.id')
                    ->join('sy as syfrom', 'oldaccounts.syfrom', '=', 'syfrom.id')
                    ->join('semester as semfrom', 'oldaccounts.semfrom', '=', 'semfrom.id')
                    ->where('oldaccounts.id', $oaid)
                    ->where('syid', $syid)
                    ->where('oldaccounts.deleted', 0)
                    ->where('oldaccountdetails.deleted', 0)
                    ->groupBy('classid')
                    ->get();

                foreach($oldaccounts as $oa)
                {
                    FinanceUtilityModel::oa_debit($studid, $oa, $balsetup, $levelid, $oa->syfromdesc, $oa->semfromdesc, $oa->sydesc, $oa->semdesc);
                    $totalbalance += $oa->amount;
                    $oa_syfrom = $oa->syfrom;
                    $oa_semfrom = $oa->semfrom;
                    $semdesc = $oa->semdesc;
                    $sydesc = $oa->sydesc;
                    $createddatetime = $oa->createddatetime;
                }

                array_push($oaarray, (object)[
                    'headerid' => $oaid,
                    'amount' => $totalbalance,
                    'semfrom' => $oa_semfrom,
                    'syfrom' => $oa_syfrom,
                    'createddatetime' => $createddatetime
                ]);

                if($totalbalance > 0)
                {
                    $oaarray = collect($oaarray);
                    FinanceUtilityModel::oa_credit($studid, $oaarray[0], $balsetup, $levelid, '', '', $sydesc, $semdesc);
                }


            }
        }
        else{
            if($balsetup->classified != 1)
            {
                $oldaccounts = db::table('oldaccounts')
                    ->select(db::raw('headerid, oldaccountdetails.id as detailid, studid, syid, semid, syfrom, semfrom, classid, SUM(amount) AS amount, oldaccounts.createddatetime'))
                    ->join('oldaccountdetails', 'oldaccounts.id', '=', 'oldaccountdetails.headerid')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('oldaccounts.deleted', 0)
                    ->where('oldaccountdetails.deleted', 0)
                    ->groupBy('headerid')
                    ->get();

                foreach($oldaccounts as $oa)
                {
                    $syfromdesc = db::table('sy')
                        ->where('id', $oa->syfrom)
                        ->first()
                        ->sydesc;

                    $semfromdesc = db::table('semester')
                        ->where('id', $oa->semfrom)
                        ->first()
                        ->semester;

                    $sydesc = db::table('sy')
                        ->where('id', $oa->syid)
                        ->first()
                        ->sydesc;

                    $semdesc = db::table('semester')
                        ->where('id', $oa->semid)
                        ->first()
                        ->semester;

                    FinanceUtilityModel::oa_debit($studid, $oa, $balsetup, $levelid, $syfromdesc, $semfromdesc, $sydesc, $semdesc);
                    FinanceUtilityModel::oa_credit($studid, $oa, $balsetup, $levelid, $syfromdesc, $semfromdesc, $sydesc, $semdesc);



                }

                $oldaccounts = db::table('oldaccounts')
                    ->select(db::raw('headerid, oldaccountdetails.id as detailid, studid, syid, semid, syfrom, semfrom, classid, SUM(amount) AS amount, oldaccounts.createddatetime'))
                    ->join('oldaccountdetails', 'oldaccounts.id', '=', 'oldaccountdetails.headerid')
                    ->where('studid', $studid)
                    ->where('syfrom', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semfrom', $semid);
                        }
                    })
                    ->where('oldaccounts.deleted', 0)
                    ->where('oldaccountdetails.deleted', 0)
                    ->groupBy('headerid')
                    ->get();

                foreach($oldaccounts as $oa)
                {
                    $syfromdesc = db::table('sy')
                        ->where('id', $oa->syfrom)
                        ->first()
                        ->sydesc;

                    $semfromdesc = db::table('semester')
                        ->where('id', $oa->semfrom)
                        ->first()
                        ->semester;

                    $sydesc = db::table('sy')
                        ->where('id', $oa->syid)
                        ->first()
                        ->sydesc;

                    $semdesc = db::table('semester')
                        ->where('id', $oa->semid)
                        ->first()
                        ->semester;

                    FinanceUtilityModel::oa_credit($studid, $oa, $balsetup, $levelid, $syfromdesc, $semfromdesc, $sydesc, $semdesc);
                }


            }
            else{

                $totalbalance = 0;
                $oa_syfrom = 0;
                $oa_semfrom = 0;
                $semdesc = '';
                $sydesc = '';
                $createddatetime = '';

                $olddata = db::table('oldaccounts')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('deleted', 0)
                    ->get();

                foreach($olddata as $old)
                {
                    $oldaccounts = db::table('oldaccounts')
                        ->select(db::raw('headerid, oldaccountdetails.id as detailid, studid, syid, semid, syfrom, semfrom, classid, SUM(amount) AS amount, oldaccounts.createddatetime, description,
                            sy.sydesc, semester.`semester` as semdesc, semfrom.`semester` AS semfromdesc, syfrom.`sydesc` AS syfromdesc, oldaccounts.createddatetime'))
                        ->join('oldaccountdetails', 'oldaccounts.id', '=', 'oldaccountdetails.headerid')
                        ->join('itemclassification', 'oldaccountdetails.classid', '=', 'itemclassification.id')
                        ->join('sy', 'oldaccounts.syid', '=', 'sy.id')
                        ->join('semester', 'oldaccounts.semid', '=', 'semester.id')
                        ->join('sy as syfrom', 'oldaccounts.syfrom', '=', 'syfrom.id')
                        ->join('semester as semfrom', 'oldaccounts.semfrom', '=', 'semfrom.id')
                        ->where('oldaccounts.id', $old->id)
                        ->where('syid', $syid)
                        ->where('oldaccounts.deleted', 0)
                        ->where('oldaccountdetails.deleted', 0)
                        ->groupBy('classid')
                        ->get();

                    $oaarray = array();

                    foreach($oldaccounts as $oa)
                    {
                        FinanceUtilityModel::oa_debit($studid, $oa, $balsetup, $levelid, $oa->syfromdesc, $oa->semfromdesc, $oa->sydesc, $oa->semdesc);
                        $totalbalance += $oa->amount;
                        $oa_syfrom = $oa->syfrom;
                        $oa_semfrom = $oa->semfrom;
                        $semdesc = $oa->semdesc;
                        $sydesc = $oa->sydesc;
                        $createddatetime = $oa->createddatetime;
                    }

                    array_push($oaarray, (object)[
                        'headerid' => $oaid,
                        'amount' => $totalbalance,
                        'semfrom' => $oa_semfrom,
                        'syfrom' => $oa_syfrom,
                        'createddatetime' => $createddatetime
                    ]);

                    if($totalbalance > 0)
                    {
                        $oaarray = collect($oaarray);
                        FinanceUtilityModel::oa_credit($studid, $oaarray[0], $balsetup, $levelid, '', '', $sydesc, $semdesc);
                    }
                }

                $oldaccounts = db::table('oldaccounts')
                    ->select(db::raw('headerid, oldaccountdetails.id as detailid, studid, syid, semid, syfrom, semfrom, classid, SUM(amount) AS amount, oldaccounts.createddatetime'))
                    ->join('oldaccountdetails', 'oldaccounts.id', '=', 'oldaccountdetails.headerid')
                    ->where('studid', $studid)
                    ->where('syfrom', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semfrom', $semid);
                        }
                    })
                    ->where('oldaccounts.deleted', 0)
                    ->where('oldaccountdetails.deleted', 0)
                    ->groupBy('headerid')
                    ->get();

                foreach($oldaccounts as $oa)
                {
                    $syfromdesc = db::table('sy')
                        ->where('id', $oa->syfrom)
                        ->first()
                        ->sydesc;

                    $semfromdesc = db::table('semester')
                        ->where('id', $oa->semfrom)
                        ->first()
                        ->semester;

                    $sydesc = db::table('sy')
                        ->where('id', $oa->syid)
                        ->first()
                        ->sydesc;

                    $semdesc = db::table('semester')
                        ->where('id', $oa->semid)
                        ->first()
                        ->semester;

                    FinanceUtilityModel::oa_credit($studid, $oa, $balsetup, $levelid, $syfromdesc, $semfromdesc, $sydesc, $semdesc);
                }


            }
        }

    }

    public static function oa_debit($studid, $oa, $balsetup, $levelid, $syfromdesc, $semfromdesc, $sydesc, $semdesc)
    {
        $particulars = '';

        if($balsetup->classified == 0)
        {
            $particulars = 'OLD ACCOUNTS FORWARDED FROM SY ' . $syfromdesc . ' - '.strtoupper($semfromdesc);
        }
        else{
            $particulars = $oa->description . ' OLD ACCOUNTS FROM SY ' . $syfromdesc . ' - '.strtoupper($semfromdesc);
        }

        db::table('studledger')
            ->insert([
                'studid' => $studid,
                'semid' => $oa->semid,
                'syid' => $oa->syid,
                'classid' => $balsetup->classid,
                'particulars' => $particulars,
                'amount' => $oa->amount,
                'ornum' => $oa->headerid,
                'deleted' => 0,
                'createddatetime' => $oa->createddatetime
            ]);


        db::table('studpayscheddetail')
            ->insert([
                'studid' => $studid,
                'semid' => $oa->semid,
                'syid' => $oa->syid,
                'classid' => $balsetup->classid,
                'particulars' => $particulars,
                'amount' => $oa->amount,
                'balance' => $oa->amount,
                'tuitiondetailid' => $oa->detailid,
                // 'createdby' => auth()->user()->id,
                'createddatetime' => FinanceModel::getServerDateTime()
            ]);

        $item = db::table('studledgeritemized')
            ->where('studid', $studid)
            ->where('syid', $oa->syid)
            ->where(function($q) use($levelid, $oa){
                if($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $oa->semid);
                }
            })
            ->where('classificationid', $balsetup->classid)
            ->where('deleted', 0)
            ->first();

        if($item)
        {
            db::table('studledgeritemized')
                ->where('id', $item->id)
                ->update([
                    'itemamount' => $item->itemamount + $oa->amount
                ]);
        }
        else{
            db::table('studledgeritemized')
                ->insert([
                    'studid' => $studid,
                    'semid' => $oa->semid,
                    'syid' => $oa->syid,
                    'classificationid' => $balsetup->classid,
                    'itemamount' => $oa->amount
                ]);
        }


    }

    public static function oa_credit($studid, $oa, $balsetup, $levelid, $syfromdesc, $semfromdesc, $sydesc, $semdesc)
    {
        $prevledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('syid', $oa->syfrom)
            ->where(function($q) use($levelid, $oa){
                if($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $oa->semfrom);
                }
            })
            ->where('deleted', 0)
            ->where('classid', $balsetup->classid)
            ->where('payment', $oa->amount)
            ->count();

        if($prevledger == 0)
        {
            db::table('studledger')
                ->insert([
                    'studid' => $studid,
                    'semid' => $oa->semfrom,
                    'syid' => $oa->syfrom,
                    'classid' => $balsetup->classid,
                    'particulars' => 'OLD ACCOUNTS FORWARDED TO SY ' . $sydesc . ' - ' . strtoupper($semdesc),
                    'payment' => $oa->amount,
                    'ornum' => $oa->headerid,
                    'deleted' => 0,
                    'createddatetime' => $oa->createddatetime
                ]);

            $paysched = db::table('studpayscheddetail')
                ->select(db::raw('id, studid, syid, semid, classid, amount, amountpay, balance'))
                ->where('studid', $studid)
                ->where('syid', $oa->syfrom)
                ->where(function($q) use($levelid, $oa){
                    if($levelid >= 17 && $levelid <= 21)
                    {
                        $q->where('semid', $oa->semfrom);
                    }
                })
                ->where('deleted', 0)
                ->get();

            foreach($paysched as $pay)
            {
                db::table('studpayscheddetail')
                    ->where('id', $pay->id)
                    ->update([
                        'amountpay' => $oa->amount,
                        'balance' => 0,
                        // 'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }

            $ledgeritems = db::table('studledgeritemized')
                ->select(db::raw('id, studid, syid, semid, classificationid, itemamount, totalamount'))
                ->where('studid', $studid)
                ->where('syid', $oa->syfrom)
                ->where(function($q) use($levelid, $oa){
                    if($levelid >= 17 && $levelid <= 21)
                    {
                        $q->where('semid', $oa->semfrom);
                    }
                })
                ->where('deleted', 0)
                ->get();

            foreach($ledgeritems as $item)
            {
                db::table('studledgeritemized')
                    ->where('id', $item->id)
                    ->update([
                        'totalamount' => $item->itemamount,
                        // 'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }

        }
    }

	public static function resetv3_generateadjustments($studid, $levelid, $syid, $semid)
    {
        $adjustments = db::table('adjustments')
            ->select(db::raw('adjustments.id, adjustmentdetails.id as detailid, classid, amount, description, mop, iscredit, isdebit, syid, semid, adjstatusdatetime, adjustments.createddatetime'))
            ->join('adjustmentdetails', 'adjustments.id', '=', 'adjustmentdetails.headerid')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('adjustmentdetails.deleted', 0)
			->where('adjustments.deleted', 0)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->get();

        foreach($adjustments as $adj)
        {
            if($adj->isdebit == 1)
            {
                //--------studLedger------//
                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        'classid' => $adj->classid,
                        'particulars' => 'ADJ: ' . $adj->description,
                        'amount' => $adj->amount,
                        'ornum' => $adj->id,
                        'pschemeid' => $adj->mop,
                        // 'createdby' => auth()->user()->id,
                        'createddatetime' => $adj->createddatetime,
                        'deleted' => 0,
                    ]);
                //--------studLedger------//

                //-----------------studledgeritemized--------------//
                $checkitemized = db::table('studledgeritemized')
                    ->where('studid', $studid)
                    ->where('classificationid', $adj->classid)
                    ->where('deleted', 0)
                    ->get();

                if(count($checkitemized) > 0)
                {
                    db::table('studledgeritemized')
                        ->where('id', $checkitemized[0]->id)
                        ->update([
                            'itemamount' => floatval($checkitemized[0]->itemamount) + floatval($adj->amount),
                            // 'updatedby' => auth()->user(),
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }
                else
                {
                    db::table('studledgeritemized')
                        ->insert([
                            'studid' => $studid,
                            'semid' => $semid,
                            'syid' => $syid,
                            'classificationid' => $adj->classid,
                            'itemamount' => $adj->amount,
                            'deleted' => 0,
                            // 'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }
                //-----------------studledgeritemized--------------//

                //--------------paymentsched--------------//

                $paymentsetup = db::table('paymentsetup')
                    ->select('paymentsetup.id', 'noofpayment', 'paymentno', 'duedate')
                    ->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
                    ->where('paymentsetup.id', $adj->mop)
                    ->where('paymentsetupdetail.deleted', 0)
                    ->get();

                $tAmount = $adj->amount;
                $schedAmount = 0;

                $schedAmount = $adj->amount / intval($paymentsetup[0]->noofpayment);
                $_id = array();

                foreach($paymentsetup as $setup)
                {
                    if($tAmount > 0)
                    {
                        $sched = db::table('studpayscheddetail')
                            ->where('studid', $studid)
                            ->where('classid', $adj->classid)
                            ->where('particulars', $adj->description)
                            ->where('deleted', 0)
                            ->where('syid', $syid)
                            ->where(function($q) use($levelid, $semid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(FinanceModel::shssetup() == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }
                                if($levelid >= 17 && $levelid <= 20)
                                {
                                    $q->where('semid', $semid);
                                }
                            })
                            ->whereNotIn('id', $_id)
                            ->take(1)
                            ->get();



                        if(count($sched) > 0)
                        {
                            array_push($_id, $sched[0]->id);
                            // echo 'studpayscheddetail+: ' . $sched[0]->id . '; ';
                            db::table('studpayscheddetail')
                                ->where('id', $sched[0]->id)
                                ->update([
                                    'amount' => $sched[0]->amount + $schedAmount,
                                    'balance' => $sched[0]->balance + $schedAmount,
                                    // 'updatedby' => auth()->user()->id,
                                    'updateddatetime' => FinanceModel::getServerDateTime()

                                ]);
                            $tAmount -= $schedAmount;
                        }
                        else
                        {
                            // echo 'studpayscheddetail-: ' . $sched[0] . '; ';
                            $schedid = db::table('studpayscheddetail')
                                ->insertGetId([
                                    'studid' => $studid,
                                    'semid' => $semid,
                                    'syid' => $syid,
                                    'classid' => $adj->classid,
                                    'tuitiondetailid' => $adj->detailid,
                                    'particulars' => $adj->description,
                                    'duedate' => $setup->duedate,
                                    'amount' => $schedAmount,
                                    'balance'=> $schedAmount,
                                    // 'createdby' => auth()->user()->id
                                    'createddatetime' => FinanceModel::getServerDateTime()
                                ]);

                            $tAmount -= $schedAmount;
                            array_push($_id, $schedid);

                        }

                    }
                }

                //--------------paymentsched--------------//
            }
            else // CREDIT
            {
                $zeroclass = 0;

                $adjdate = date_format(date_create($adj->adjstatusdatetime), 'Y-m-d H:i');
                // return $adjdate;

                //--------studLedger------//
                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        'classid' => $adj->classid,
                        'particulars' => 'ADJ: ' . $adj->description,
                        'payment' => $adj->amount,
                        'ornum' => $adj->id,
                        'pschemeid' => $adj->mop,
                        // 'createdby' => auth()->user()->id,
                        'createddatetime' => $adjdate,
                        'deleted' => 0
                    ]);
                //--------studLedger------//

                //-----------------studledgeritemized--------------//

                $adjamount = $adj->amount;

                $ledgeritemized = db::table('studledgeritemized')
                    ->where('studid', $studid)
                    ->where('classificationid', $adj->classid)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid) {
                        if($levelid >= 17 && $levelid <= 20)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->whereColumn('itemamount', '>', 'totalamount')
                    ->where('deleted', 0)
                    ->get();

                // echo 'first <br>';

                if(count($ledgeritemized) == 0)
                {
                    $ledgeritemized = db::table('studledgeritemized')
                        ->where('studid', $studid)
                        // ->where('classificationid', $adj->classid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid) {
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->whereColumn('itemamount', '>', 'totalamount')
                        ->where('deleted', 0)
                        ->get();

                    // echo 'second <br>';
                }

                // return $ledgeritemized;

                foreach($ledgeritemized as $item)
                {
                    $bal = $item->itemamount - $item->totalamount;

                    if($adjamount > $bal)
                    {
                        db::table('studledgeritemized')
                            ->where('id', $item->id)
                            ->update([
                                'totalamount' => $item->totalamount + $bal,
                                'updateddatetime' => FinanceModel::getServerDateTime()
                            ]);

                        $adjamount -= $bal;
                    }
                    else
                    {
                        db::table('studledgeritemized')
                            ->where('id', $item->id)
                            ->update([
                                'totalamount' => $item->totalamount + $adjamount,
                                'updateddatetime' => FinanceModel::getServerDateTime()
                            ]);

                        $adjamount = 0;
                    }
                }

                // echo 'adjamount ' . $adjamount . '<br>';

                if($adjamount > 0)
                {
                    $ledgeritemized = db::table('studledgeritemized')
                        ->where('studid', $studid)
                        // ->where('classificationid', $adj->classid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid) {
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->whereColumn('itemamount', '>', 'totalamount')
                        ->where('deleted', 0)
                        ->get();

                    foreach($ledgeritemized as $item)
                    {
                        $bal = $item->itemamount - $item->totalamount;

                        if($adjamount > $bal)
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $bal,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                ]);

                            $adjamount -= $bal;
                        }
                        else
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $adjamount,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                ]);

                            $adjamount = 0;
                        }
                    }

                    // echo 'third <br>';


                }

                // echo 'adjamount ' . $adjamount . '<br>';

                //-----------------studledgeritemized--------------//

                //-----------------studpayscheddetail--------------//

                if(db::table('schoolinfo')->first()->dpdist == 1)
                {
                    $payscheddetail = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('classid', $adj->classid)
                        ->where('deleted', 0)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->get();

                    // return $payscheddetail;

                    $paycount = count($payscheddetail);

                    $divamount = number_format($adj->amount / $paycount, 2, '.', '');
                    $d_adjamount = $adj->amount;
                    $counter = 0;

                    // return $payscheddetail;

                    foreach($payscheddetail as $detail)
                    {
                        $counter += 1;

                        if($counter != $paycount)
                        {
                            db::table('studpayscheddetail')
                                ->where('id', $detail->id)
                                ->update([
                                    'amountpay' => $detail->amountpay + $divamount,
                                    'balance' => $detail->balance - $divamount,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                ]);
                        }
                        else
                        {
                            db::table('studpayscheddetail')
                                ->where('id', $detail->id)
                                ->update([
                                    'amountpay' => $detail->amountpay + $d_adjamount,
                                    'balance' => $detail->balance - $d_adjamount,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                ]);
                        }

                        $d_adjamount -= $divamount;
                    }

                }
                else
                {
                    $adjamount = $adj->amount;

                    // return $adj->classid;

                    $paysched = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('classid', $adj->classid)
                        ->where('syid', $syid)
                        ->where(function($q) use($semid, $levelid){
                            if($levelid == 14 || $levelid = 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', $semid);
                                }
                            }
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->where('deleted', 0)
                        ->where('balance', '>', 0)
                        ->get();

                    if(count($paysched) == 0)
                    {
                        $paysched = db::table('studpayscheddetail')
                            ->where('studid', $studid)
                            // ->where('classid', $adj->classid)
                            ->where('syid', $syid)
                            ->where(function($q) use($semid, $levelid){
                                if($levelid == 14 || $levelid = 15)
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }
                                if($levelid >= 17 && $levelid <= 20)
                                {
                                    $q->where('semid', $semid);
                                }
                            })
                            ->where('deleted', 0)
                            ->where('balance', '>', 0)
                            ->get();
                    }

                    foreach($paysched as $sched)
                    {
                        if($adjamount > 0)
                        {
                            if($adjamount > $sched->balance)
                            {
                                db::table('studpayscheddetail')
                                    ->where('id', $sched->id)
                                    ->update([
                                        'amountpay' => $sched->balance + $sched->amountpay,
                                        'balance' => 0,
                                        'updateddatetime' => FinanceModel::getServerDateTime()
                                    ]);

                                $adjamount -= $sched->balance;
                            }
                            else
                            {
                                db::table('studpayscheddetail')
                                    ->where('id', $sched->id)
                                    ->update([
                                        'amountpay' => $sched->amountpay + $adjamount,
                                        'balance' => $sched->balance - $adjamount,
                                        'updateddatetime' => FinanceModel::getServerDateTime()
                                    ]);

                                $adjamount = 0;
                            }
                        }
                    }

                    // echo 'adjamount ' . $adjamount . '<br>';

                    if($adjamount > 0)
                    {
                        $paysched = db::table('studpayscheddetail')
                            ->where('studid', $studid)
                            // ->where('classid', $adj->classid)
                            ->where('syid', $syid)
                            ->where(function($q) use($semid, $levelid){
                                if($levelid == 14 || $levelid = 15)
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }
                                if($levelid >= 17 && $levelid <= 20)
                                {
                                    $q->where('semid', $semid);
                                }
                            })
                            ->where('deleted', 0)
                            ->where('balance', '>', 0)
                            ->get();
                        foreach($paysched as $sched)
                        {
                            if($adjamount > 0)
                            {
                                if($adjamount > $sched->balance)
                                {
                                    db::table('studpayscheddetail')
                                        ->where('id', $sched->id)
                                        ->update([
                                            'amountpay' => $sched->balance + $sched->amountpay,
                                            'balance' => 0,
                                            'updateddatetime' => FinanceModel::getServerDateTime()
                                        ]);

                                    $adjamount -= $sched->balance;
                                }
                                else
                                {
                                    db::table('studpayscheddetail')
                                        ->where('id', $sched->id)
                                        ->update([
                                            'amountpay' => $sched->amountpay + $adjamount,
                                            'balance' => $sched->balance - $adjamount,
                                            'updateddatetime' => FinanceModel::getServerDateTime()
                                        ]);

                                    $adjamount = 0;
                                }
                            }
                        }
                    }

                    // echo 'adjamount ' . $adjamount . '<br>';

                }
                //-----------------studpayscheddetail--------------//
            }
        }
    }

    public static function resetv3_generatediscounts($studid, $levelid, $syid, $semid, $studdiscountid)
    {
        $discamount = 0;
        $particulars = 'DISCOUNT: ';

        $studdiscount = db::table('studdiscounts')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else{
                        if(FinanceModel::shssetup() == 0)
                        {
                            $q->where('semid', $semid);
                        }
                        else{
                            $q->where('semid', '!=', 3);
                        }
                    }
                }
                if($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semid);
                }
                else{
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else{
                        $q->where('semid', '!=', 3);
                    }
                }
            })
            ->where(function($q) use($studdiscountid){
                if($studdiscountid > 0)
                {
                    $q->where('id', $studdiscountid);
                }
            })
            ->where('posted', 1)
            ->where('deleted', 0)
            ->get();



        foreach($studdiscount as $discount)
        {
            $discinfo = db::table('discounts')
                ->where('id', $discount->discountid)
                ->first();

            if($discinfo)
            {
                $psign = '';
                if($discount->percent == 1)
                {
                    $psign = '%';
                }
                else{
                    $psign = '';
                }

                $itemclass = db::table('itemclassification')
                    ->where('id', $discount->classid)
                    ->first();

                $classname = '';

                if($itemclass)
                {
                    $classname = $itemclass->description;
                }

                $particulars = 'DISCOUNT: ' . $discinfo->particulars . ' - ' . $classname . ' ' . $discount->discount . $psign;

                $studledger = db::table('studledger')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid == 14 || $levelid == 15)
                        {
                            if(FinanceModel::shssetup() == 0)
                            {
                                $q->where('semid', $semid);
                            }
                        }
                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('classid', $discount->classid)
                    ->where('deleted', 0)
                    ->first();

                if($studledger)
                {
                    $ledgeramount = $studledger->amount;

                    // if($discinfo->percent == 0)
                    // {
                    //     $discamount = $discinfo->amount;
                    // }
                    // else
                    // {
                    //     $discamount = ($discinfo->amount/100) * $ledgeramount;
                    // }

                    $discamount = $discount->discamount;
                }

                //studledger
                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        // 'particulars' =>'DISCOUNT: ' . $discinfo->particulars,
                        'particulars' => $particulars,
                        'payment' => $discamount,
                        'ornum' => $discount->id,
                        'deleted' => 0,
                        // 'createdby' => auth()->user()->id,
                        'createddatetime' => $discount->createddatetime
                    ]);

                //studledger
                $d_amount = $discamount;

                $studpayscheddetail = db::table('studpayscheddetail')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid == 14 || $levelid == 15)
                        {
                            if($semid == 3)
                            {
                                $q->where('semid', 3);
                            }
                            else
                            {
                                if(FinanceModel::shssetup() == 0)
                                {
                                    $q->where('semid', $semid);
                                }
                            }
                        }
                        elseif($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', $semid);
                        }
                        else
                        {
                            if($semid == 3)
                            {
                                $q->where('semid', 3);
                            }
                            else
                            {
                                $q->where('semid', '!=', 3);
                            }
                        }
                    })
                    ->where('deleted', 0)
                    ->where('classid', $discount->classid)
                    ->where('balance', '>', 0)
                    ->get();


                foreach($studpayscheddetail as $paysched)
                {
                    if($d_amount > 0)
                    {
                        $payInfo = db::table('studpayscheddetail')
                            ->where('id', $paysched->id)
                            ->first();

                        $_bal = $paysched->balance;

                        if($_bal < $d_amount)
                        {

                            $updpaySched = db::table('studpayscheddetail')
                                ->where('id', $paysched->id)
                                ->update([
                                    'amountpay' => $paysched->amountpay + $_bal,
                                    'balance' => 0,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            FinanceUtilityModel::discount_itemized($studid, $syid, $semid, $levelid, $_bal, $paysched->classid);
                            $d_amount -= $_bal;
                        }
                        else
                        {
                            $updpaySched = db::table('studpayscheddetail')
                                ->where('id', $paysched->id)
                                ->update([
                                    'amountpay' => $paysched->amountpay + $d_amount,
                                    'balance' => $paysched->balance - $d_amount,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            FinanceUtilityModel::discount_itemized($studid, $syid, $semid, $levelid, $d_amount, $paysched->classid);
                            $d_amount = 0;
                        }
                    }
                }

                // return 'd_amount: ' . $d_amount;

                if($d_amount > 0)
                {
                    $studpayscheddetail = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if($semid == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    if(FinanceModel::shssetup() == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }
                            }
                            elseif($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', $semid);
                            }
                            else
                            {
                                if($semid == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    $q->where('semid', '!=', 3);
                                }
                            }
                        })
                        ->where('deleted', 0)
                        ->where('balance', '>', 0)
                        // ->where('classid', $discount->classid)
                        ->get();


                    foreach($studpayscheddetail as $paysched)
                    {
                        if($d_amount > 0)
                        {
                            $payInfo = db::table('studpayscheddetail')
                                ->where('id', $paysched->id)
                                ->first();

                            $_bal = $paysched->balance;

                            if($_bal < $d_amount)
                            {

                                $updpaySched = db::table('studpayscheddetail')
                                    ->where('id', $paysched->id)
                                    ->update([
                                        'amountpay' => $paysched->amountpay + $_bal,
                                        'balance' => 0,
                                        'updateddatetime' => FinanceModel::getServerDateTime()
                                        // 'updatedby' => auth()->user()->id
                                    ]);

                                FinanceUtilityModel::discount_itemized($studid, $syid, $semid, $levelid, $_bal, $paysched->classid);
                                $d_amount -= $_bal;
                            }
                            else
                            {
                                $updpaySched = db::table('studpayscheddetail')
                                    ->where('id', $paysched->id)
                                    ->update([
                                        'amountpay' => $paysched->amountpay + $d_amount,
                                        'balance' => $paysched->balance - $d_amount,
                                        'updateddatetime' => FinanceModel::getServerDateTime()
                                        // 'updatedby' => auth()->user()->id
                                    ]);

                                FinanceUtilityModel::discount_itemized($studid, $syid, $semid, $levelid, $d_amount, $paysched->classid);
                                $d_amount = 0;
                            }
                        }

                        // echo '2ndround: d_amount: ' . $d_amount . '<br>';
                    }
                }
            }
        }
    }

	public static function discount_itemized($studid, $syid, $semid, $levelid, $amount, $classid)
    {
        $itemized = db::table('studledgeritemized')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        if(FinanceModel::shssetup() == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    }
                }
                elseif($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semid);
                }
                else
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        $q->where('semid', '!=', 3);
                    }
                }
            })
            ->where('deleted', 0)
            ->where('classificationid', $classid)
            ->whereColumn('itemamount', '>', 'totalamount')
            ->get();

        // echo 'amount: ' . $amount . '<br>';

        foreach($itemized as $item)
        {
            if($amount > 0)
            {
                $balance = $item->itemamount - $item->totalamount;

                if($balance > $amount)
                {
                    db::table('studledgeritemized')
                        ->where('id', $item->id)
                        ->update([
                            'totalamount' => $item->totalamount + $amount,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);

                    $amount = 0;
                }
                else
                {
                    db::table('studledgeritemized')
                        ->where('id', $item->id)
                        ->update([
                            'totalamount' => $item->totalamount + $balance,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);

                    $amount -= $balance;
                }
            }
        }
    }

    public static function discount_detaildist1($studid, $syid, $semid, $levelid, $discamount, $discountclassid)
    {
        //studpayscheddetail
        $count = db::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(FinanceModel::shssetup() == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->where('deleted', 0)
            ->where('classid', $discountclassid)
            ->count();

        $divamount = number_format($discamount/$count, 2, '.', '');
        $divtotal = 0;
        $counter = 1;
        $over = 0;

        $scheddetail = db::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(FinanceModel::shssetup() == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->where('deleted', 0)
            ->where('classid', $discountclassid)
            // ->where('balance', '>', 0)
            ->get();

        foreach($scheddetail as $detail)
        {
            if($counter < $count)
            {
                if($divamount > $detail->balance)
                {
                    db::table('studpayscheddetail')
                        ->where('id', $detail->id)
                        ->update([
                            'amountpay' => $detail->amountpay + $detail->balance,
                            'balance' => 0,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);
                    $divtotal += $detail->balance;
                    $over += $divamount - $detail->balance;
                }
                else
                {
                    db::table('studpayscheddetail')
                        ->where('id', $detail->id)
                        ->update([
                            'amountpay' => $detail->amountpay + $divamount,
                            'balance' => $detail->balance - $divamount,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);
                    $divtotal += $divamount;
                }


                $counter +=1;
            }
            else
            {
                $damount = 0;

                if($divtotal > $discamount)
                {
                    $damount = $divtotal-$discamount - $over;
                }
                else
                {
                    $damount = $discamount - $divtotal - $over;
                }

                if($damount > $detail->balance)
                {
                    db::table('studpayscheddetail')
                        ->where('id', $detail->id)
                        ->update([
                            'amountpay' => $detail->amountpay + $detail->balance,
                            'balance' => 0,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);

                    // echo 'over: ' . $over . ' damount: ' . $damount . ' - detail: ' . $detail->balance . '<br>';
                }
                else
                {
                    db::table('studpayscheddetail')
                        ->where('id', $detail->id)
                        ->update([
                            'amountpay' => $detail->amountpay + $damount,
                            'balance' => $detail->balance - $damount,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }
            }
        }
    }

    public static function discount_detaildist0($studid, $syid, $semid, $levelid, $discamount, $discountclassid)
    {

        $payschedDetail = db::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(FinanceModel::shssetup() == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->where('balance', '>', 0)
            ->where('deleted', 0)
            ->where('classid', $discountclassid)
            ->get();

        $_over = 0;

        $totalpay = 0;
        if(count($payschedDetail) > 0)
        {
            // $calcAmount = $tAmount / count($payschedDetail);
            $_over = $discamount;

            foreach($payschedDetail as $paysched)
            {
                $payInfo = db::table('studpayscheddetail')
                    ->where('id', $paysched->id)
                    ->first();

                $_bal = $paysched->balance;

                if($_bal <= $_over)
                {

                    $totalpay = $payInfo->amount;

                    $_over -= $payInfo->amount - $payInfo->amountpay;

                    $updpaySched = db::table('studpayscheddetail')
                            ->where('id', $paysched->id)
                            ->update([
                                'amountpay' => $totalpay,
                                'balance' => 0,
                                'updateddatetime' => FinanceModel::getServerDateTime()
                                // 'updatedby' => auth()->user()->id
                            ]);


                }
                else
                {
                    if($_over > 0)
                    {
                        $totalpay = $payInfo->amountpay + $_over;

                        $updpaySched = db::table('studpayscheddetail')
                                ->where('id', $paysched->id)
                                ->update([
                                    'amountpay' => $totalpay,
                                    'balance' => $payInfo->amount - $totalpay,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                    // 'updatedby' => auth()->user()->id
                                ]);

                        $_over = 0;
                    }
                }

            }
        }

    }

	public static function resetv3_generateesp($studid, $glevel, $enrollid, $syid, $semid, $feesid)
    {
        $studsubjects = db::table('student_specsubj')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($semid, $glevel){
                if($glevel == 14 || $glevel == 15)
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    }
                }
                elseif($glevel >= 17 && $glevel >= 21)
                {
                    $q->where('semid', $semid);
                }
                else
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        $q->where('semid', '!=', 3);
                    }
                }
            })
            ->where('deleted', 0)
            ->get();

        foreach($studsubjects as $subj)
        {
            $tuitionesp = db::table('tuitionesp')
                ->select('subjid', 'classid', 'amount', 'subjdesc', 'itemclassification.description')
                ->join('subjects', 'tuitionesp.subjid', '=', 'subjects.id')
                ->join('tuitionespdetail', 'tuitionesp.id', 'tuitionespdetail.headerid')
                ->join('itemclassification', 'tuitionespdetail.classid', '=', 'itemclassification.id')
                ->where('tuitionesp.levelid', $glevel)
                ->where('subjid', $subj->subjid)
                ->get();

            foreach($tuitionesp as $esp)
            {
                //ledger

                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'enrollid' => $enrollid,
                        'semid' => $semid,
                        'syid' => $syid,
                        'classid' => $esp->classid,
                        'particulars' => 'ESP: ' . $esp->subjdesc . ' - ' . $esp->description,
                        'amount' => $esp->amount,
                        'payment' => 0,
                        'createddatetime' => FinanceModel::getServerDateTime(),
                        'deleted' => 0,
                        'void' => 0
                    ]);

                //ledger

                //studpayscheddetail

                db::table('studpayscheddetail')
                    ->insert([
                        'studid' => $studid,
                        'enrollid' => $enrollid,
                        'semid' => $semid,
                        'syid' => $syid,
                        'classid' => $esp->classid,
                        'particulars' => 'ESP: ' . $esp->subjdesc . ' - ' . $esp->description,
                        'amount' => $esp->amount,
                        'amountpay' => 0,
                        'balance' => $esp->amount,
                        'createddatetime' => FinanceModel::getServerDateTime(),
                        'deleted' => 0,
                    ]);

                //studpayscheddetail

                //stuledgeritemized

                db::table('studledgeritemized')
                    ->insert([
                        'studid' => $studid,
                        'enrollid' => $enrollid,
                        'semid' => $semid,
                        'syid' => $syid,
                        'classificationid' => $esp->classid,
                        'itemamount' => $esp->amount,
                        'createddatetime' => FinanceModel::getServerDateTime(),
                        'deleted' => 0,
                    ]);

                //stuledgeritemized
            }
        }
    }

	public static function assessment_gen($studid, $syid, $semid, $month)
    {
        $levelid = 0;
        $amount = 0;
        $paymentno = 0;
        $currentamount = 0;

        $einfo = db::table('enrolledstud')
            ->select('levelid')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($semid){
                if($semid == 3)
                {
                    $q->where('ghssemid', 3);
                }
                else
                {
                    $q->where('ghssemid', '!=', 3);
                }
            })
            ->where('deleted', 0)
            ->first();

        if($einfo)
        {
            $levelid = $einfo->levelid;
        }
        else
        {
            $einfo = db::table('sh_enrolledstud')
                ->select('levelid')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where(function($q) use($semid){
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                        else
                        {
                            $q->where('semid', '!=', 3);
                        }
                    }
                })
                ->where('deleted', 0)
                ->first();

            if($einfo)
            {
                $levelid = $einfo->levelid;
            }
            else
            {
                $einfo = db::table('college_enrolledstud')
                    ->select('yearLevel as levelid')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('deleted', 0)
                    ->first();

                if($einfo)
                {
                    $levelid = $einfo->levelid;
                }
                else
                {
                    $levelid = db::table('studinfo')->where('id', $studid)->first()->levelid;
                }
            }
        }

        $acadid = db::table('gradelevel')
            ->where('id', $levelid)
            ->first()
            ->acadprogid;

        $setup = db::table('assessment_setup')
            ->where('acadprogid', $acadid)
            ->first();

        $mopsetup = db::table('paymentsetup')
            ->where('id', $setup->mop)
            ->first();

        $divamount = $mopsetup->noofpayment;

        $paysched = db::table('studpayscheddetail')
            ->select(db::raw('SUM(amount) AS amount'))
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($semid, $levelid){
                if($levelid == 14 || $levelid == 15)
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                        else
                        {
                            $q->where('semid', '!=', 3);
                        }
                    }
                }
                elseif($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semid);
                }
                else
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        $q->where('semid', '!=', 3);
                    }
                }
            })
            ->where('deleted', 0)
            ->first();

        if($paysched)
        {
            $amount = $paysched->amount/$divamount;
            $currentamount = $paysched->amount;
        }

        $paysetup = db::table('paymentsetup')
            ->select('paymentsetupdetail.*')
            ->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
            ->where('paymentsetup.id', $setup->mop)
            ->where('paymentsetupdetail.deleted', 0)
            ->get();

        $payment_array = array();
        $paycounter = 1;
        $_payamount = 0;

        foreach($paysetup as $pay)
        {
            $pay_month = date_format(date_create($pay->duedate), 'n');
            if($pay_month == $month)
            {
                $paymentno = $pay->paymentno;
            }

            if($paycounter != 10)
            {
                $amount = number_format($amount, 2, '.', '');
                array_push($payment_array, (object)[
                    'paymentno' => $pay->paymentno,
                    'duedate' => $pay->duedate,
                    'amount' => $amount
                ]);

                $paycounter += 1;
                $_payamount += $amount;
            }
            else
            {
                if($_payamount > $currentamount)
                {
                    $amount = $_payamount - $currentamount;
                }
                else
                {
                    $amount = $currentamount - $_payamount;
                }

                $amount = number_format($amount, 2, '.', '');
                array_push($payment_array, (object)[
                    'paymentno' => $pay->paymentno,
                    'duedate' => $pay->duedate,
                    'amount' => $amount
                ]);
            }


        }



        $fees = collect($payment_array);

        // return $fees;

        $paysched = db::table('studpayscheddetail')
            ->select(db::raw('SUM(amountpay) AS amount'))
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($semid, $levelid){
                if($levelid == 14 || $levelid == 15)
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                        else
                        {
                            $q->where('semid', '!=', 3);
                        }
                    }
                }
                elseif($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semid);
                }
                else
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        $q->where('semid', '!=', 3);
                    }
                }
            })
            ->where('deleted', 0)
            ->first();

        $totalpayment = $paysched->amount;

        $assessment = array();

        foreach($fees as $fee)
        {
            $month = date_format(date_create($fee->duedate), 'F');

            if($totalpayment > 0)
            {
                if($totalpayment > $fee->amount)
                {
                    array_push($assessment, (object)[
                        'paymentno' => $fee->paymentno,
                        'duedate' => $fee->duedate,
                        'amount' => number_format($fee->amount, 2, '.', ''),
                        'payment' => number_format($fee->amount, 2, '.', ''),
                        'balance' => 0.00,
                        'particulars' => $month
                    ]);

                    $totalpayment -= $fee->amount;
                }
                else
                {
                    array_push($assessment, (object)[
                        'paymentno' => $fee->paymentno,
                        'duedate' => $fee->duedate,
                        'amount' => number_format($fee->amount, 2, '.', ''),
                        'payment' => number_format($totalpayment, 2, '.', ''),
                        'balance' => number_format($fee->amount - $totalpayment, 2, '.', ''),
                        'particulars' => $month
                    ]);

                    $totalpayment = 0;
                }
            }
            else
            {
                array_push($assessment, (object)[
                    'paymentno' => $fee->paymentno,
                    'duedate' => $fee->duedate,
                    'amount' => number_format($fee->amount, 2, '.', ''),
                    'payment' => 0.00,
                    'balance' => number_format($fee->amount, 2, '.', ''),
                    'particulars' => $month
                ]);
            }

        }

        $assessment = collect($assessment)->where('paymentno', '<=', $paymentno);

        return $assessment;
    }

	public static function loaddiscount($studid, $levelid, $syid, $semid, $discountid)
    {
        $discamount = 0;
        $particulars = 'DISCOUNT: ';

        $studdiscount = db::table('studdiscounts')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else{
                        if(FinanceModel::shssetup() == 0)
                        {
                            $q->where('semid', $semid);
                        }
                        else{
                            $q->where('semid', '!=', 3);
                        }
                    }
                }
                if($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semid);
                }
                else{
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else{
                        $q->where('semid', '!=', 3);
                    }
                }
            })
            ->where('id', $discountid)
            ->where('posted', 1)
            ->where('deleted', 0)
            ->get();



        foreach($studdiscount as $discount)
        {
            $discinfo = db::table('discounts')
                ->where('id', $discount->discountid)
                ->first();

            if($discinfo)
            {
                $psign = '';
                if($discount->percent == 1)
                {
                    $psign = '%';
                }
                else{
                    $psign = '';
                }

                $itemclass = db::table('itemclassification')
                    ->where('id', $discount->classid)
                    ->first();

                $classname = '';

                if($itemclass)
                {
                    $classname = $itemclass->description;
                }

                $particulars = 'DISCOUNT: ' . $discinfo->particulars . ' - ' . $classname . ' ' . $discount->discount . $psign;

                $studledger = db::table('studledger')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid == 14 || $levelid == 15)
                        {
                            if(FinanceModel::shssetup() == 0)
                            {
                                $q->where('semid', $semid);
                            }
                        }
                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('classid', $discount->classid)
                    ->where('deleted', 0)
                    ->first();

                if($studledger)
                {
                    $ledgeramount = $studledger->amount;

                    // if($discinfo->percent == 0)
                    // {
                    //     $discamount = $discinfo->amount;
                    // }
                    // else
                    // {
                    //     $discamount = ($discinfo->amount/100) * $ledgeramount;
                    // }

                    $discamount = $discount->discamount;
                }

                //studledger
                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        // 'particulars' =>'DISCOUNT: ' . $discinfo->particulars,
                        'particulars' =>'DISCOUNT: ' . $particulars,
                        'payment' => $discamount,
                        'ornum' => $discount->id,
                        'deleted' => 0,
                        // 'createdby' => auth()->user()->id,
                        'createddatetime' => $discount->createddatetime
                    ]);

                //studledger
                $d_amount = $discamount;

                $studpayscheddetail = db::table('studpayscheddetail')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid == 14 || $levelid == 15)
                        {
                            if($semid == 3)
                            {
                                $q->where('semid', 3);
                            }
                            else
                            {
                                if(FinanceModel::shssetup() == 0)
                                {
                                    $q->where('semid', $semid);
                                }
                            }
                        }
                        elseif($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', $semid);
                        }
                        else
                        {
                            if($semid == 3)
                            {
                                $q->where('semid', 3);
                            }
                            else
                            {
                                $q->where('semid', '!=', 3);
                            }
                        }
                    })
                    ->where('deleted', 0)
                    ->where('classid', $discount->classid)
                    ->where('balance', '>', 0)
                    ->get();


                foreach($studpayscheddetail as $paysched)
                {
                    if($d_amount > 0)
                    {
                        $payInfo = db::table('studpayscheddetail')
                            ->where('id', $paysched->id)
                            ->first();

                        $_bal = $paysched->balance;

                        if($_bal < $d_amount)
                        {

                            $updpaySched = db::table('studpayscheddetail')
                                ->where('id', $paysched->id)
                                ->update([
                                    'amountpay' => $paysched->amountpay + $_bal,
                                    'balance' => 0,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            FinanceUtilityModel::discount_itemized($studid, $syid, $semid, $levelid, $_bal, $paysched->classid);
                            $d_amount -= $_bal;
                        }
                        else
                        {
                            $updpaySched = db::table('studpayscheddetail')
                                ->where('id', $paysched->id)
                                ->update([
                                    'amountpay' => $paysched->amountpay + $d_amount,
                                    'balance' => $paysched->balance - $d_amount,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            FinanceUtilityModel::discount_itemized($studid, $syid, $semid, $levelid, $d_amount, $paysched->classid);
                            $d_amount = 0;
                        }
                    }
                }

                // return 'd_amount: ' . $d_amount;

                if($d_amount > 0)
                {
                    $studpayscheddetail = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if($semid == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    if(FinanceModel::shssetup() == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }
                            }
                            elseif($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', $semid);
                            }
                            else
                            {
                                if($semid == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    $q->where('semid', '!=', 3);
                                }
                            }
                        })
                        ->where('deleted', 0)
                        ->where('balance', '>', 0)
                        // ->where('classid', $discount->classid)
                        ->get();


                    foreach($studpayscheddetail as $paysched)
                    {
                        if($d_amount > 0)
                        {
                            $payInfo = db::table('studpayscheddetail')
                                ->where('id', $paysched->id)
                                ->first();

                            $_bal = $paysched->balance;

                            if($_bal < $d_amount)
                            {

                                $updpaySched = db::table('studpayscheddetail')
                                    ->where('id', $paysched->id)
                                    ->update([
                                        'amountpay' => $paysched->amountpay + $_bal,
                                        'balance' => 0,
                                        'updateddatetime' => FinanceModel::getServerDateTime()
                                        // 'updatedby' => auth()->user()->id
                                    ]);

                                FinanceUtilityModel::discount_itemized($studid, $syid, $semid, $levelid, $_bal, $paysched->classid);
                                $d_amount -= $_bal;
                            }
                            else
                            {
                                $updpaySched = db::table('studpayscheddetail')
                                    ->where('id', $paysched->id)
                                    ->update([
                                        'amountpay' => $paysched->amountpay + $d_amount,
                                        'balance' => $paysched->balance - $d_amount,
                                        'updateddatetime' => FinanceModel::getServerDateTime()
                                        // 'updatedby' => auth()->user()->id
                                    ]);

                                FinanceUtilityModel::discount_itemized($studid, $syid, $semid, $levelid, $d_amount, $paysched->classid);
                                $d_amount = 0;
                            }
                        }

                        // echo '2ndround: d_amount: ' . $d_amount . '<br>';
                    }
                }
            }
        }
    }

    public static function einfo($studid, $syid, $semid)
    {
        $einfo = db::table('enrolledstud')
            ->select('levelid')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($semid){
                if($semid == 3)
                {
                    $q->where('ghssemid', 3);
                }
                else
                {
                    $q->where('ghssemid', '!=', 3);
                }
            })
            ->where('deleted', 0)
            ->first();

        if($einfo)
        {
            return $einfo;
        }
        else
        {
            $einfo = db::table('sh_enrolledstud')
                ->select('levelid')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where(function($q) use($semid){
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                        else
                        {
                            $q->where('semid', '!=', 3);
                        }
                    }
                })
                ->where('deleted', 0)
                ->first();

            if($einfo)
            {
                return $einfo;
            }
            else
            {
                $einfo = db::table('college_enrolledstud')
                    ->select('yearLevel as levelid')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('deleted', 0)
                    ->first();

                if($einfo)
                {
                    return $einfo;
                }

            }
        }
    }


}
