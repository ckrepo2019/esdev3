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

class ExamPermitController extends Controller
{
    public function exampermit()
    {
        return view('/finance/exampermit_v2');
    }

    public function ep_section(Request $request)
    {
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $courselist = '<option value="0">COURSE | All</option>';

        if($levelid == 14 || $levelid == 15)
        {
            $sections = db::table('sections')
                ->select('sections.id', 'sectionname')
                ->join('sectiondetail', 'sections.id', '=', 'sectiondetail.sectionid')
                ->where('levelid', $levelid)
                ->where('syid', $syid)
                ->where('sections.deleted', 0)
                ->get();
        }   
        elseif($levelid >= 17 && $levelid <= 21)
        {
            $sections = db::table('college_sections')
                ->where('id', 'sectionDesc as sectionname')
                ->where('deleted', 0)
                ->where('yearID', $levelid)
                ->get();

            $courses = db::table('college_courses')
                ->select('id', 'coursedesc', 'courseabrv as code')
                ->where('deleted', 0)
                ->where('cisactive', 1)
                ->get();

            foreach($courses as $course)
            {
                $courselist .='
                    <option value="'.$course->id.'">'.$course->code.'</option>
                ';
            }

        }
        else
        {
            $sections = db::table('sections')
                ->select('sections.id', 'sectionname')
                ->join('sectiondetail', 'sections.id', '=', 'sectiondetail.sectionid')
                ->where('levelid', $levelid)
                ->where('syid', $syid)
                ->where('sections.deleted', 0)
                ->get();
        }

        $sectionlist = '<option value="0">SECTION | All</option>';

        foreach($sections as $sec)
        {
            $sectionlist .='
                <option value="'.$sec->id.'">'.$sec->sectionname.'</option>
            ';
        }

        
        $data = array(
            'sectionlist' => $sectionlist,
            'courselist' => $courselist
        );

        echo json_encode($data);
    }

    public function ep_gen(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $levelid = $request->get('levelid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        $monthid = $request->get('monthid');
        $search = $request->get('search');

        if($levelid == 14 || $levelid == 15)
        {
            $enrolled = db::table('sh_enrolledstud')
                ->select(db::raw('sid, sh_enrolledstud.studid, lastname, firstname, middlename, levelname, courseid AS CODE, sections.sectionname, CONCAT(lastname, ", ", firstname, ", ", sid) AS fullname'))
                ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
                ->join('sections', 'sh_enrolledstud.sectionid', 'sections.id')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->where('sh_enrolledstud.levelid', $levelid)
                ->where('sh_enrolledstud.syid', $syid)
                ->where(function($q) use($semid){
                    if($semid == 3)
                    {
                        $q->where('sh_enrolledstud.semid', 3);
                    }
                    else
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('sh_enrolledstud.semid', $semid);
                        }
                        else
                        {
                            $q->where('sh_enrolledstud.semid', '!=', 3);
                        }
                    }
                })
                ->where('sh_enrolledstud.deleted', 0)
                ->where('sh_enrolledstud.studstatus', '>', 0)
                ->where(function($q) use($sectionid){
                    if($sectionid > 0)
                    {
                        $q->where('sh_enrolledstud.sectionid', $sectionid);
                    }
                })
                ->having('fullname', 'like', '%'.$search.'%')
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->get();
        }
        elseif($levelid >= 17 && $levelid <= 25 )
        {
            $enrolled = db::table('college_enrolledstud')
                ->select(db::raw('sid, college_enrolledstud.studid, lastname, firstname, middlename, levelname, college_courses.courseabrv AS code, college_sections.sectiondesc as sectionname, CONCAT(lastname, ", ", firstname, ", ", sid) AS fullname')) 
                ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                ->leftjoin('college_sections', 'college_enrolledstud.sectionid', '=', 'college_sections.id')
                ->join('gradelevel', 'college_enrolledstud.yearlevel', '=', 'gradelevel.id')
                ->where('college_enrolledstud.syid', $syid)
                ->where('college_enrolledstud.semid', $semid)
                ->where('yearlevel', $levelid)
                ->where(function($q) use($courseid){
                    if($courseid > 0)
                    {
                        $q->where('college_enrolledstud.courseid', $courseid);
                    }
                })
                ->where(function($q) use($sectionid){
                    if($sectionid > 0)
                    {
                        $q->where('college_enrolledstud.sectionid', $sectionid);
                    }
                })
                ->where('college_enrolledstud.studstatus', '>', 0)
                ->where('college_enrolledstud.deleted', 0)
                ->having('fullname', 'like', '%'.$search.'%')
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->get();
        }
        else
        {
            $enrolled = db::table('enrolledstud')
                ->select(db::raw('sid, enrolledstud.studid, lastname, firstname, middlename, levelname, courseid AS code, sections.sectionname, CONCAT(lastname, ", ", firstname, ", ", sid) AS fullname'))
                ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('sections', 'enrolledstud.sectionid', 'sections.id')
                ->where('enrolledstud.levelid', $levelid)
                ->where('enrolledstud.syid', $syid)
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
                ->where('enrolledstud.deleted', 0)
                ->where('enrolledstud.studstatus', '>', 0)
                ->where(function($q) use($sectionid){
                    if($sectionid > 0)
                    {
                        $q->where('enrolledstud.sectionid', $sectionid);
                    }
                })
                ->having('fullname', 'like', '%'.$search.'%')
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->get();
        }

        $list = '';

        foreach($enrolled as $stud)
        {
            $course = '';
            if($levelid >= 17 && $levelid <= 25)
            {
                $course = $stud->code;
            }
            else
            {
                $course = '';
            }

            $list .='
                <tr studid="'.$stud->studid.'" syid="'.$syid.'" semid="'.$semid.'">
                    <td> '.$stud->sid.' - '.$stud->lastname.', '.$stud->firstname. ' ' . $stud->middlename.'</td>
                    <td>'.$stud->levelname.'</td>
                    <td>'.$stud->sectionname.'</td>
                    <td>'.$course.'</td>
                    <td>
                        <button class="btn btn-xs btm-block btn-primary btnstatus" studid="'.$stud->studid.'" syid="'.$syid.'" semid="'.$semid.'""  disabled>
                            Loading
                            <div class="spinner-grow" role="status" style="height: 20px; width: 20px">
                              <span class="sr-only">Loading...</span>
                            </div>
                        </button>
                    </td>
                </tr>
            ';
        }

        return $list;

    }

    public static function ep_accounts(Request $request)
    {
        $studid = $request->get('studid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $levelid = $request->get('levelid');
        $monthid = $request->get('monthid');

        

        $status = '';
        $balance = 0;
        $balancedetail = '';
        $epdetailid = 0;

        $request->request->add([
            'selectedschoolyear' => $syid, 
            'selectedsemester' => $semid,
            'selectedmonth' => $monthid,
            'action' => 'ep'
        ]);

        // $month = db::table('monthsetup')
        //     ->where('id', $monthid)
        //     ->first();

        // $selmonth = $month->monthid;
        $totaldue = app('App\Http\Controllers\FinanceControllers\StatementofAccountController')->getaccount_v2($request);
        // return $totaldue;

        $balance = $totaldue;
        $balancedetail = number_format($totaldue, 2);
        
        if($totaldue > 0)
        {
            $status = 'na';
        }
        else{
            $status = 'a';
        }

        $checkstatus = db::table('epermitdetails')
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
                    }
                }
                elseif($levelid >= 17 && $levelid <= 25)
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
            ->where('monthid', $monthid)
            ->first();

        if($checkstatus)
        {
            // $status = $checkstatus->examstatus;
            if($checkstatus->altered == 0)
            {
                db::table('epermitdetails')
                    ->where('id', $checkstatus->id)
                    ->update([
                        'balance' => $balance,
                        'examstatus' => $status,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);

                $epdetailid = $checkstatus->id;
            }
            else
            {
                db::table('epermitdetails')
                    ->where('id', $checkstatus->id)
                    ->update([
                        'balance' => $balance,
                        // 'examstatus' => $status,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);   
            }

            $epdetailid = $checkstatus->id;
            $status = $checkstatus->examstatus;
        }
        else
        {
            $ep = db::table('epermitdetails')
                ->insertGetId([
                    'studid' => $studid,
                    'syid' => $syid,
                    'semid' => $semid,
                    'monthid' => $monthid,
                    'balance' => $balance,
                    'examstatus' => $status,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);

            $epdetailid = $ep;
        }

        $data = array(
            'balance' => $balancedetail,
            'studid' => $studid,
            'detailid' => $epdetailid,
            'status' => $status
        );

        return json_encode($data);
    }

    public function ep_paysched(Request $request)
    {
        $studid = $request->get('studid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $levelid = $request->get('levelid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        $monthid = $request->get('monthid');
        $status = $request->get('status');

        $name = '';
        $level = '';
        $section = '';
        $status = 'ENROLLED';

        if($levelid == 14 || $levelid == 15)
        {
            $enrolled = db::table('sh_enrolledstud')
                ->select('sh_enrolledstud.studid', 'sid', 'lastname', 'firstname', 'middlename', 'levelname', 'courseid as code', 'sections.sectionname')
                ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
                ->join('sections', 'sh_enrolledstud.sectionid', 'sections.id')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->where('sh_enrolledstud.studid', $studid)
                ->where('sh_enrolledstud.levelid', $levelid)
                ->where('sh_enrolledstud.syid', $syid)
                ->where(function($q) use($semid){
                    if($semid == 3)
                    {
                        $q->where('sh_enrolledstud.semid', 3);
                    }
                    else
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('sh_enrolledstud.semid', $semid);
                        }
                        else
                        {
                            $q->where('sh_enrolledstud.semid', '!=', 3);
                        }
                    }
                })
                ->where('sh_enrolledstud.deleted', 0)
                ->where('sh_enrolledstud.studstatus', '>', 0)
                ->where(function($q) use($sectionid){
                    if($sectionid > 0)
                    {
                        $q->where('sh_enrolledstud.sectionid', $sectionid);
                    }
                })
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->first();

            if($enrolled)
            {
                $name = $enrolled->sid . ' - ' . $enrolled->lastname . ', ' . $enrolled->firstname . ' ' . $enrolled->middlename;
                $level = $enrolled->levelname . ' | ' . $enrolled->sectionname;
            }
        }
        elseif($levelid >= 17 && $levelid <= 21)
        {
            $enrolled = db::table('college_enrolledstud')
                ->select('college_enrolledstud.studid', 'sid', 'lastname', 'firstname', 'middlename', 'levelname', 'college_courses.courseabrv as code', 'college_sections.sectiondesc as sectionname')
                ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                ->leftjoin('college_sections', 'college_enrolledstud.sectionid', '=', 'college_sections.id')
                ->join('gradelevel', 'college_enrolledstud.yearlevel', '=', 'gradelevel.id')
                ->where('college_enrolledstud.studid', $studid)
                ->where('college_enrolledstud.syid', $syid)
                ->where('college_enrolledstud.semid', $semid)
                ->where('yearlevel', $levelid)
                ->where(function($q) use($courseid){
                    if($courseid > 0)
                    {
                        $q->where('college_enrolledstud.courseid', $courseid);
                    }
                })
                ->where(function($q) use($sectionid){
                    if($sectionid > 0)
                    {
                        $q->where('college_enrolledstud.sectionid', $sectionid);
                    }
                })
                ->where('college_enrolledstud.studstatus', '>', 0)
                ->where('college_enrolledstud.deleted', 0)
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->first();

            if($enrolled)
            {
                $name = $enrolled->sid . ' - ' . $enrolled->lastname . ', ' . $enrolled->firstname . ' ' . $enrolled->middlename;
                $level = $enrolled->code . ' | ' . $enrolled->levelname;
            }
        }
        else
        {
            $enrolled = db::table('enrolledstud')
                ->select('enrolledstud.studid', 'sid', 'lastname', 'firstname', 'middlename', 'levelname', 'courseid as code', 'sections.sectionname')
                ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('sections', 'enrolledstud.sectionid', 'sections.id')
                ->where('enrolledstud.studid', $studid)
                ->where('enrolledstud.levelid', $levelid)
                ->where('enrolledstud.syid', $syid)
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
                ->where('enrolledstud.deleted', 0)
                ->where('enrolledstud.studstatus', '>', 0)
                ->where(function($q) use($sectionid){
                    if($sectionid > 0)
                    {
                        $q->where('enrolledstud.sectionid', $sectionid);
                    }
                })
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->first();

            if($enrolled)
            {
                $name = $enrolled->sid . ' - ' . $enrolled->lastname . ', ' . $enrolled->firstname . ' ' . $enrolled->middlename;
                $level = $enrolled->levelname . ' | ' . $enrolled->sectionname;
            }
        }

        $tamount = db::table('studpayscheddetail')
            ->select(db::raw('sum(amount) as amount, sum(amountpay) as amountpay'))
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

        // return collect($tamount);
        $payamount = $tamount->amountpay;
        $schedamount = $tamount->amount;
        $noofpayment = 1;

        $setup = db::table('assessment_setup')
            ->select('noofpayment')
            ->join('academicprogram', 'assessment_setup.acadprogid', '=', 'academicprogram.id')
            ->join('gradelevel', 'academicprogram.id', '=', 'gradelevel.acadprogid')
            ->join('paymentsetup', 'assessment_setup.mop', '=', 'paymentsetup.id')
            ->first();

        if($setup)
        {
            $noofpayment = $setup->noofpayment;
        }

        $divamount = $schedamount / $noofpayment;
        $divamount = number_format($divamount, 2, '.', '');
        $schedarray = array();
        
        $paysched = db::table('studpayscheddetail')
            // ->select('studid', 'amount', 'amountpay', 'balance')
            ->select(db::raw('particulars, sum(amount) as amount, sum(amountpay) as amountpay, sum(balance) as balance, duedate'))
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
            ->where(function($q) use($monthid){
                $q->where('paymentno', '<=', $monthid)
                    ->orWhere('paymentno', null);
            })
            ->where('deleted', 0)
            ->groupBy(db::raw('month(duedate)'))
            ->orderBy('paymentno')
            ->get();

        $list = '';
        $totalamount = 0;
        $totalamountpay = 0;
        $totalbalance = 0;

        foreach($paysched as $sched)
        {
            $_total = 0;
            if($sched->duedate != null)
            {
                if($divamount >= $payamount)
                {
                    $_total = $divamount - $payamount;

                    $list .='
                        <tr>
                            <td>'.strtoUpper(date_format(date_create($sched->duedate), 'F')).' PAYABLES</td>
                            <td class="text-right">'.number_format($divamount, 2).'</td>
                            <td class="text-right">'.number_format($payamount, 2).'</td>
                            <td class="text-right">'.number_format($_total, 2).'</td>
                        </tr>
                    ';

                    $_total = $divamount - $payamount;
                    $totalamount += $divamount;
                    $totalamountpay += $payamount;
                    $totalbalance += $_total;

                    $payamount = 0;
                }
                else{
                    $list .='
                        <tr>
                            <td>'.strtoUpper(date_format(date_create($sched->duedate), 'F')).' PAYABLES</td>
                            <td class="text-right">'.number_format($divamount, 2).'</td>
                            <td class="text-right">'.number_format($divamount, 2).'</td>
                            <td class="text-right">'.number_format(0, 2).'</td>
                        </tr>
                    ';

                    $payamount = $payamount - $divamount;
                    $_total = 0;

                    $totalamount += $divamount;
                    $totalamountpay += $divamount;
                    $totalbalance += $_total;
                }

                
            }

                   
            // else
            // {
            //     $list .='
            //         <tr>
            //             <td>'.$sched->particulars.'</td>
            //             <td class="text-right">'.number_format($sched->amount, 2).'</td>
            //             <td class="text-right">'.number_format($sched->amountpay, 2).'</td>
            //             <td class="text-right">'.number_format($sched->balance, 2).'</td>
            //         </tr>
            //     ';   
            // }

            
        }

        // foreach($paysched as $sched)
        // {
        //     if($sched->duedate != null)
        //     {
        //         $list .='
        //             <tr>
        //                 <td>'.strtoUpper(date_format(date_create($sched->duedate), 'F')).' PAYABLES</td>
        //                 <td class="text-right">'.number_format($sched->amount, 2).'</td>
        //                 <td class="text-right">'.number_format($sched->amountpay, 2).'</td>
        //                 <td class="text-right">'.number_format($sched->balance, 2).'</td>
        //             </tr>
        //         ';
        //     }
        //     else
        //     {
        //         $list .='
        //             <tr>
        //                 <td>'.$sched->particulars.'</td>
        //                 <td class="text-right">'.number_format($sched->amount, 2).'</td>
        //                 <td class="text-right">'.number_format($sched->amountpay, 2).'</td>
        //                 <td class="text-right">'.number_format($sched->balance, 2).'</td>
        //             </tr>
        //         ';   
        //     }

        //     $totalamount += $sched->amount;
        //     $totalamountpay += $sched->amountpay;
        //     $totalbalance += $sched->balance;
        // }

        $list .='
            <tr>
                <th class="text-right text-bold">TOTAL: </th>
                <th class="text-right text-bold">'.number_format($totalamount, 2).'</th>
                <th class="text-right text-bold">'.number_format($totalamountpay, 2).'</th>
                <th class="text-right text-bold">'.number_format($totalbalance, 2).'</th>
            </tr>
        ';   

        $data = array(
            'list' => $list,
            'name' => $name,
            'level' => $level,
            'status' => $status
        );

        echo json_encode($data);
    }

    public function ep_changestatus(Request $request)
    {
        $dataid = $request->get('dataid');

        $detail = db::table('epermitdetails')
            ->where('id', $dataid)
            ->first();

        if($detail)
        {
            if($detail->examstatus == 'na')
            {
                db::table('epermitdetails')
                    ->where('id', $dataid)
                    ->update([
                        'examstatus' => 'a',
                        'altered' => true,
                        'updateddatetime' => FinanceModel::getServerDateTime(),
                        'updatedby' => auth()->user()->id
                    ]);
            }
            else
            {
                db::table('epermitdetails')
                    ->where('id', $dataid)
                    ->update([
                        'examstatus' => 'na',
                        'altered' => true,
                        'updateddatetime' => FinanceModel::getServerDateTime(),
                        'updatedby' => auth()->user()->id
                    ]);
            }
        }
    }

    

}