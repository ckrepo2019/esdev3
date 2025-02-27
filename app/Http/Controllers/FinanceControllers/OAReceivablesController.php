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

class OAReceivablesController extends Controller
{
    public function oareceivables()
    {
        return view('finance.reports.oareceivables');
    }

    public function oareceivables_load(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $action = $request->get('action');
        $progid = $request->get('acadprog');
        $range = explode(' - ', $request->get('range'));
        $_stud = array();
        $levelarray = array();

        $datefrom = date_format(date_create($range[0]), 'Y-m-d 00:00');
        $dateto = date_format(date_create($range[1]), 'Y-m-d 23:59');

        if($progid == 2 || $progid == 3 || $progid == 4)
        {
            $enrolltb = 'enrolledstud';
            $levelfield = 'levelid';
            $dateenrolledfield = 'dateenrolled';

            if($progid == 2)        
            {
                array_push($levelarray, 4);
                array_push($levelarray, 3);
            }
            elseif($progid == 3)
            {
                array_push($levelarray, 1);
                array_push($levelarray, 5);
                array_push($levelarray, 6);
                array_push($levelarray, 7);
                array_push($levelarray, 16);
                array_push($levelarray, 9);
            }
            elseif($progid == 4)
            {
                array_push($levelarray, 10);
                array_push($levelarray, 11);
                array_push($levelarray, 12);
                array_push($levelarray, 13);
            }
        }
        elseif($progid == 5)
        {
            $enrolltb = 'sh_enrolledstud';
            $levelfield = 'levelid';
            $dateenrolledfield = 'dateenrolled';

            array_push($levelarray, 14);
            array_push($levelarray, 15);
        }
        else{
            $enrolltb = 'college_enrolledstud';
            $levelfield = 'yearlevel';
            $dateenrolledfield = 'date_enrolled';

            array_push($levelarray, 17);
            array_push($levelarray, 18);
            array_push($levelarray, 19);
            array_push($levelarray, 20);
            array_push($levelarray, 21);

            $glevel = 'COLLEGE';
        }

        $students = db::table('gradelevel')
            ->select('studid')
            ->join($enrolltb, 'gradelevel.id', '=', $enrolltb . '.' . $levelfield)
            ->where('syid', $syid)
            ->where(function($q) use($progid, $levelfield, $enrolltb, $semid){
                if($progid == 1)
                {
                    $q->where($levelfield, 3);
                }
                elseif($progid == 2)
                {
                    $q->where($levelfield, 4);
                }
                else{
                    $q->where('acadprogid', $progid);
                    if($progid == 6)
                    {
                        $q->where($enrolltb . '.semid', $semid);
                    }
                }
            })
            // ->whereBetween($dateenrolledfield, [$datefrom, $dateto])
            ->where('gradelevel.deleted', 0)
            ->where($enrolltb . '.deleted', 0)
            ->where($enrolltb . '.studstatus', '>', 0)
            ->orderBy('sortid')
            ->get();

        foreach($students as $stud)
        {
            array_push($_stud, $stud->studid);
        }

        // return $_stud;

        // if($progid != 6)
        // {
        //     $edatefrom = db::table($enrolltb)
        //         ->where('syid', $syid)
        //         ->where('deleted', 0)
        //         ->whereIn('studstatus', [1,2,4])
        //         ->orderBy('dateenrolled')
        //         ->first()
        //         ->dateenrolled;
        // }
        // else{
        //     $edatefrom = db::table($enrolltb)
        //         ->where('syid', $syid)
        //         ->where('semid', $semid)
        //         ->where('deleted', 0)
        //         ->whereIn('studstatus', [1,2,4])
        //         ->orderBy('date_enrolled')
        //         ->first()
        //         ->date_enrolled;
        // }

        // $edatefrom = date_format(date_create($edatefrom), 'Y-m-d 00:00');

        $oldacc = db::table('studledger')
            ->select(db::raw('studledger.createddatetime, lastname, firstname, middlename, suffix, studledger.`particulars`, amount'))
            ->join('studinfo', 'studledger.studid', 'studinfo.id')
            // ->whereIn('studid', $_stud)
            ->where('studledger.deleted', 0)
            ->where('studledger.syid', $syid)
            ->where(function($q) use($enrolltb, $semid){
                if($enrolltb == 'college_enrolledstud')
                {
                    $q->where('studledger.semid', $semid);
                }
            })            
            ->where('void', 0)
            ->whereBetween('studledger.createddatetime', [$datefrom, $dateto])
            ->where('particulars', 'not like', '%DISCOUNT:%')
            ->where('particulars', 'not like', '%ADJ:%')
            ->where('particulars', 'like', '%OLD ACCOUNTS FORWARDED FROM SY%')
            ->where('amount', '>', 0)
            ->orderBy('lastname')
            ->orderBy('firstname')
            // ->take(25)
            ->get();

        if($action != 'print')
        {
            return $oldacc;
        }
        else{
            if($progid == 1)
            {
                $progname = 'KINDER';
            }
            else{
                $progname = db::table('academicprogram')
                    ->where('id', $progid)
                    ->first()
                    ->progname;
            }

            $dtfrom = date_format(date_create($range[0]), 'm/d/Y');
            $dtto = date_format(date_create($range[1]), 'm/d/Y');

            $pdf = PDF::loadview('finance.reports.pdf.pdf_oareceivable', compact('oldacc', 'syid', 'semid', 'progname', 'dtfrom', 'dtto'));
            return $pdf->stream('Old Accounts Receivables.pdf'); 
        }
    }
    
}
