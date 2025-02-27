<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use TCPDF;
use App\FinanceModel;
use App\Models\Finance\FinanceUtilityModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class OldAccountsController extends Controller
{
    public function oldaccounts(Request $request)
    {
        return view('finance..oldaccounts');
    }

    public function oa_loadsy(Request $request)
    {
        $levelid = $request->get('levelid');
        $sylist ='';
        $semlist ='';

        if($levelid >= 17 && $levelid <= 20)
        {
            $schoolyear = db::table('sy')
                ->orderBy('sydesc')
                ->get();

            if(FinanceModel::getSemID() == 2)
            {
                foreach($schoolyear as $sy)
                {
                    if($sy->isactive == 1)
                    {
                        $sylist .='
                            <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                        ';    
                        break;
                    }
                    else
                    {
                        $sylist .='
                            <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                        ';    
                    }
                    
                }
            }
            else
            {
                foreach($schoolyear as $sy)
                {
                    // echo $sy->isactive . ' ' . $sy->sydesc . '<br>';
                    if($sy->isactive == 1)
                    {
                        break;
                    }
                    else
                    {
                        $sylist .='
                            <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                        ';
                    }
                }   
            }
        }
        elseif($levelid == 14 || $levelid == 15)
        {
            $schoolyear = db::table('sy')
                ->orderBy('sydesc')
                ->get();

            if(db::table('schoolinfo')->first()->shssetup == 0)
            {
                if(FinanceModel::getSemID() == 2)
                {
                    foreach($schoolyear as $sy)
                    {
                        if($sy->isactive == 1)
                        {
                            $sylist .='
                                <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                            ';    
                            break;
                        }
                        else
                        {
                            $sylist .='
                                <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                            ';    
                        }
                        
                    }
                }
                else
                {
                    foreach($schoolyear as $sy)
                    {
                        // echo $sy->isactive . ' ' . $sy->sydesc . '<br>';
                        if($sy->isactive == 1)
                        {
                            break;
                        }
                        else
                        {
                            $sylist .='
                                <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                            ';
                        }
                    }   
                }                
            }
            else
            {
                foreach($schoolyear as $sy)
                {
                    if($sy->isactive == 1)
                    {
                        break;
                    }
                    else
                    {
                        $sylist .='
                            <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                        ';
                    }
                }
            }
        }
        else
        {
            $schoolyear = db::table('sy')
                ->orderBy('sydesc')
                ->get();

                foreach($schoolyear as $sy)
                {
                    if($sy->isactive == 1)
                    {
                        break;
                    }
                    else
                    {
                        $sylist .='
                            <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                        ';
                    }
                }
        }

        $shssetup = db::table('schoolinfo')->first()->shssetup;

        $data = array(
            'shssetup' => $shssetup,
            'sylist'=> $sylist,
            'semactive' => FinanceModel::getSemID(),
            'syactive' => FinanceModel::getSYID()
        );

        echo json_encode($data);
    }

    public function oa_load(Request $request)
    {
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $filter = $request->get('filter');

        $oaclassid = db::table('balforwardsetup')->first()->classid;
        $old_list = '';

        $oldaccounts = db::table('studledger')
            ->select(db::raw('CONCAT(lastname, ", ", firstname) AS fullname, SUM(amount) AS amount, SUM(payment) AS payment, SUM(amount) - SUM(payment) AS balance, studid, sid'))
            ->join('studinfo', 'studledger.studid', '=', 'studinfo.id')
            ->where('studledger.deleted', 0)
            ->where('syid', $syid)
            ->where('levelid', $levelid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid ==15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('studledger.semid', $semid);
                }
            })
            ->groupBy('studid')
            ->having('balance', '>', 0)
            ->having('fullname', 'like', '%'.$filter.'%')
            ->get();

        foreach($oldaccounts as $old)
        {
            $old_list .='
                <tr>
                    <td class="fullname">'.$old->sid . ' - ' . $old->fullname.'</td>
                    <td class="text-right">'.number_format($old->amount, 2).'</td>
                    <td class="text-right">'.number_format($old->payment, 2).'</td>
                    <td class="text-right">'.number_format($old->balance, 2).'</td>
                    <td class="text-center">
                        <button id="" class="btn btn-primary btn-sm oa_forward" data-toggle="tooltip" title="Forward Old Account" data-id="'.$old->studid.'" data-amount="'.$old->balance.'">
                            <i class="fas fa-external-link-alt"></i>
                        </button>
                        <button class="btn btn-success btn-sm v-ledger" data-toggle="tooltip" title="View Ledger" data-id="'.$old->studid.'">
                            <i class="fas fa-file-invoice"></i>
                        </button>
                    </td>
                </t>
            ';
        }

        // return $old_list;

        $data = array(
            'list' => $old_list
        );

        echo json_encode($data);
    }

    // public function oa_forward(Request $request)
    // {
    //     $studid = $request->get('studid');
    //     $syfrom = $request->get('syfrom');
    //     $semfrom = $request->get('semfrom');
    //     $amount = $request->get('amount');
    //     $action = $request->get('action');
    //     $tempamount = 0;

    //     if($semfrom == null)
    //     {
    //         $semfrom = 1;
    //     }

    //     $sy = db::table('sy')
    //         ->where('id', $syfrom)
    //         ->first();

    //     $sem = db::table('semester')
    //         ->where('id', $semfrom)
    //         ->first();

    //     $syid = FinanceModel::getSYID();
    //     $semid = FinanceModel::getSemID();

    //     $studinfo = db::table('studinfo')
    //         ->select('id', 'levelid')
    //         ->where('id', $studid)
    //         ->first();

    //     $levelid = $studinfo->levelid;

    //     if($syfrom == $syid)
    //     {
    //         if($semfrom == $semid)
    //         {
    //             return 'error';
    //         }
    //     }

    //     $balclassid = db::table('balforwardsetup')->first()->classid;

    //     $particulars = 'Balance forwarded from SY ' . $sy->sydesc . ' ' . $sem->semester;
    //     $reverse_particulars = 'Balance forwarded to ' . FinanceModel::getSYDesc() . ' ' . FinanceModel::getSemDesc();

    //     $studledger = db::table('studledger')
    //         ->where('studid', $studid)
    //         ->where('syid', FinanceModel::getSYID())
    //         ->where('semid', FinanceModel::getSemID())
    //         ->where('particulars', 'like',  '%'.$particulars.'%')
    //         ->where('deleted', 0)
    //         ->first();

    //     if(!$studledger)
    //     {
    //         $oldledger = db::table('studledger')
    //             ->select(db::raw('SUM(amount) - SUM(payment) AS balance'))
    //             ->where('studid', $studid)
    //             ->where('syid', $syfrom)
    //             ->where(function($q)use($levelid, $semfrom){
    //                 if($levelid == 14 || $levelid == 15)
    //                 {
    //                     if(db::table('schoolinfo')->first()->shssetup == 0)
    //                     {
    //                         $q->where('semid', $semfrom);
    //                     }
    //                 }
    //                 if($levelid >= 17 && $levelid <= 20)
    //                 {
    //                     $q->where('semid', $semfrom);
    //                 }
    //             })
    //             ->where('deleted', 0)
    //             ->where('void', 0)
    //             ->first();

    //         if($oldledger)
    //         {
    //             if($oldledger->balance > 0)
    //             {
    //                 if($action == 'create')
    //                 {
    //                     $tempamount = $amount;
    //                     $reverse_particulars = $reverse_particulars . ' - Amount: ' . $amount;
    //                     $amount = $oldledger->balance;
    //                 }
    //                 else{
    //                     $tempamount = $amount;
    //                 }

    //                 db::table('studledger')
    //                     ->insert([
    //                         'studid' => $studid,
    //                         'syid' => $syfrom,
    //                         'semid' => $semfrom,
    //                         'classid' => $balclassid,
    //                         'particulars' =>$reverse_particulars,
    //                         'payment' => $amount,
    //                         'createddatetime' => FinanceModel::getServerDateTime(),
    //                         'deleted' => 0,
    //                         'void' => 0
    //                     ]);

    //                 $itemized = db::table('studledgeritemized')
    //                     ->where('studid', $studid)
    //                     ->where('syid', $syfrom)
    //                     ->where(function($q)use($levelid, $semfrom){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             if(db::table('schoolinfo')->first()->shssetup == 0)
    //                             {
    //                                 $q->where('semid', $semfrom);
    //                             }
    //                         }
    //                         if($levelid >= 17 && $levelid <= 20)
    //                         {
    //                             $q->where('semid', $semfrom);
    //                         }
    //                     })
    //                     ->where('deleted', 0)
    //                     ->whereColumn('totalamount', '!=', 'itemamount')
    //                     ->get();

    //                 foreach($itemized as $item)
    //                 {
    //                     db::table('studledgeritemized')   
    //                         ->where('id', $item->id)
    //                         ->update([
    //                             'totalamount' => $item->itemamount
    //                         ]);
    //                 }

    //                 $payscheddetail = db::table('studpayscheddetail')
    //                     ->where('studid', $studid)
    //                     ->where('syid', $syfrom)
    //                     ->where(function($q)use($levelid, $semfrom){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             if(db::table('schoolinfo')->first()->shssetup == 0)
    //                             {
    //                                 $q->where('semid', $semfrom);
    //                             }
    //                         }
    //                         if($levelid >= 17 && $levelid <= 20)
    //                         {
    //                             $q->where('semid', $semfrom);
    //                         }
    //                     })
    //                     ->where('deleted', 0)
    //                     ->where('balance', '>', 0)
    //                     ->get();

    //                 foreach($payscheddetail as $detail)
    //                 {
    //                     db::table('studpayscheddetail')
    //                         ->where('id', $detail->id)
    //                         ->update([
    //                             'amountpay' => $detail->amountpay + $detail->balance,
    //                             'balance' => 0,
    //                             'updateddatetime' => FinanceModel::getServerDateTime()
    //                         ]);
    //                 }

    //             }
	// 			else{
    //                 $tempamount = $amount;
    //             }
    //         }

    //         db::table('studledger')
    //             ->insert([
    //                 'studid' => $studid,
    //                 'syid' => FinanceModel::getSYID(),
    //                 'semid' => FinanceModel::getSemID(),
    //                 'classid' => $balclassid,
    //                 'particulars' =>$particulars,
    //                 'amount' => $tempamount,
    //                 'createddatetime' => FinanceModel::getServerDateTime(),
    //                 'deleted' => 0,
    //                 'void' => 0
    //             ]);

    //         FinanceUtilityModel::resetv3_generateoldaccounts($studid, $levelid, $syid, $semid);

    //         return 'done';
    //     }
    //     else
    //     {
    //         return 'exist';
    //     }
    // }

    public function oa_forward(Request $request)
    {
        $studid = $request->get('studid');
        $syfrom = $request->get('syfrom');
        $semfrom = $request->get('semfrom');
        $amount = $request->get('amount');
        $action = $request->get('action');
        $tempamount = 0;
        $levelid = 0;

        if($semfrom == null)
        {
            $semfrom = 1;
        }

        $sy = db::table('sy')
            ->where('id', $syfrom)
            ->first();

        $sem = db::table('semester')
            ->where('id', $semfrom)
            ->first();

        $syid = FinanceModel::getSYID();
        $semid = FinanceModel::getSemID();

        $einfo = db::table('enrolledstud')
            ->select(db::raw('levelid'))
            ->where('studid', $studid)
            ->where('syid', $syfrom)
            ->where('deleted', 0)
            ->first();
        
        if($einfo)
        {
            $levelid = $einfo->levelid;
        }
        else{
            $einfo = db::table('sh_enrolledstud')
                ->select(db::raw('levelid'))
                ->where('studid', $studid)
                ->where('syid', $syfrom)
                ->where('deleted', 0)
                ->first();

            if($einfo)
            {
                $levelid = $einfo->levelid;
            }
            else{
                $einfo = db::table('college_enrolledstud')
                    ->select(db::raw('yearLevel as levelid'))
                    ->where('studid', $studid)
                    ->where('syid', $syfrom)
                    ->where('semid', $semfrom)
                    ->where('deleted', 0)
                    ->first();
                
                if($einfo)
                {
                    $levelid = $einfo->levelid;
                }
                else{
                    $einfo = db::table('studinfo')
                        ->select('levelid')
                        ->where('id', $studid)
                        ->first();

                    if($einfo)
                    {
                        $levelid = $einfo->levelid;
                    }
                }
            }
        }

        $payscheddetail = db::table('studpayscheddetail')
            ->select(db::raw('studid, classid, particulars, SUM(balance) AS amount'))
            ->where('studid', $studid)
            ->where('syid', $syfrom)
            ->where(function($q) use($levelid, $semfrom){
                if($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semfrom);
                }
            })
            ->where('deleted', 0)
            ->where('balance', '>', 0)
            ->groupBy('classid')
            ->get();

        $payscheddetail = collect($payscheddetail);

        $balsetup = db::table('balforwardsetup')->first();

        $oa_check = db::table('oldaccounts')
            ->where('studid', $studid)
            ->where('syfrom', $syfrom)
            ->where(function($q) use($levelid, $semfrom){
                if($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semfrom);
                }
            })
            ->where('deleted', 0)
            ->count();

        if($oa_check > 0)
        {
            return 'exist';
        }
        else{
            if($balsetup->classified == 0)
            {
                $totalamount = 0;

                foreach($payscheddetail as $detail)
                {
                    $totalamount += $detail->amount;
                }

                $oa_header = db::table('oldaccounts')
                    ->insertGetID([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        'syfrom' => $syfrom,
                        'semfrom' => $semfrom,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);
                
                db::table('oldaccountdetails')
                    ->insert([
                        'headerid' => $oa_header,
                        'amount' => $totalamount,
                        'classid' => 0,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);


                FinanceUtilityModel::resetv3_generateoa_v2($studid, $levelid, $syid, $semid, $oa_header);
                return 'done';
            }
            else{
                $oa_header = db::table('oldaccounts')
                    ->insertGetID([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        'syfrom' => $syfrom,
                        'semfrom' => $semfrom,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);

                foreach($payscheddetail as $detail)
                {
                    db::table('oldaccountdetails')
                        ->insert([
                            'headerid' => $oa_header,
                            'amount' => $detail->amount,
                            'classid' => $detail->classid,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }

                FinanceUtilityModel::resetv3_generateoa_v2($studid, $levelid, $syid, $semid, $oa_header);
                return 'done';
            }
        }

        
    }
	
    public function oa_setup(Request $request)
    {
        $setup = db::table('balforwardsetup')
            ->first();



        if($setup)
        {
            $data = array(
                'classid' => $setup->classid,
                'mopid' => $setup->mopid,
                'classified' => $setup->classified
            );

            return $data;
        }
    }

    public function oa_setupsave(Request $request)
    {
        $classid = $request->get('classid');
        $mop = $request->get('mop');
        $classified = $request->get('classified');

        db::table('balforwardsetup')
            ->where('id', 1)
            ->update([
                'classid' => $classid,
                'mopid' => $mop,
                'classified' => $classified
            ]);
    }

    public function old_add_studlist(Request $request)
    {
        if($request->ajax())
        {
            $studid = $request->get('studid');
            $studlist = '<option value="0">NAME</option>';
            $sylist = '<option value="0">School Year</option>';
            $semlist = '<option value="0">Semester</option>';
            if($studid > 0)
            {
                $stud = db::table('studinfo')
                    ->select('studinfo.id', 'levelname', 'levelid', 'sectionid', 'courseid', 'grantee.description as grantee')
                    ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                    ->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
                    ->where('studinfo.id', $studid)
                    ->first();

                if($stud)
                {
                    $section = '';

                    if($stud->levelid >= 17 && $stud->levelid <= 21)
                    {
                        $collegecourse = db::table('college_courses')
                            ->where('id', $stud->courseid)
                            ->first();

                        if($collegecourse)
                        {
                            $section = $collegecourse->courseabrv;
                        }

                        $sem = db::table('semester')
                            ->where('isactive', 1)
                            ->first();

                        if($sem->id == 1)
                        {
                            $schoolyear = db::table('sy')
                                ->where('sydesc', '<', FinanceModel::getSYDesc())
                                ->get();

                            foreach($schoolyear as $sy)
                            {
                                $sylist .='
                                    <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                                ';    
                            }

                            $_semester = db::table('semester')
                                ->get();

                            foreach($_semester as $_sem)
                            {
                                $semlist .='
                                    <option value="'.$_sem->id.'">'.$_sem->semester.'</option>
                                ';       
                            }
                        }
                        elseif($sem->id == 2)
                        {
                            $schoolyear = db::table('sy')
                                ->where('sydesc', '<=', FinanceModel::getSYDesc())
                                ->get();

                            foreach($schoolyear as $sy)
                            {
                                $sylist .='
                                    <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                                ';    
                            }
                        }

                        
                    }
                    else
                    {
                        $_section = db::table('sections')
                            ->where('id', $stud->sectionid)
                            ->first();

                        if($_section)
                        {
                            $section = $_section->sectionname;
                        }

                        $schoolyear = db::table('sy')
                            ->where('sydesc', '<', FinanceModel::getSYDesc())
                            ->get();

                        foreach($schoolyear as $sy)
                        {
                            $sylist .='
                                <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                            ';    
                        }
                    }

                    // return $section;

                    $data = array(
                        'levelname' => $stud->levelname,
                        'grantee' => $stud->grantee,
                        'section' => $section,
                        'levelid' => $stud->levelid,
                        'semlist' => $semlist,
                        'sylist' => $sylist
                    );
                }
            }
            else
            {
                $studinfo = db::table('studinfo')
                    ->select('id', 'sid', 'lastname', 'firstname')
                    ->where('deleted', 0)
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->get();

                foreach($studinfo as $stud)
                {
                    $studlist .='
                        <option value="'.$stud->id.'">'.$stud->sid.' - '.$stud->lastname.', '.$stud->firstname.' </option>
                    ';
                }

                $data = array(
                    'studlist' => $studlist
                );
            }

            echo json_encode($data);
        }
    }

    public function old_getsem(Request $request)
    {
        $syid = $request->get('syid');
        $semlist = '';

        if($syid == FinanceModel::getSYID())
        {
            $semlist .='
                <option value="0">Semester</option>
                <option value="1">1st Semester</option>
            ';
        }
        else
        {
            $semlist .='
                <option value="0">Semester</option>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
            ';   
        }

        return $semlist;
    }

    
}
class DCPR extends TCPDF {

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
