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

class COAController extends Controller
{
    public function chartofaccounts(Request $request)
    {
        
        return view('finance.coa_v2');
    }

    public function coa_group(Request $request)
    {
        $list = '<option value="0">ACOUNT TYPE</option>';
        $coagroup = db::table('acc_coagroup')
            ->where('deleted', 0)
            ->get();

        foreach($coagroup as $group)        
        {
            $list .='
                <option value="'.$group->id.'">'.strtoupper($group->group).'</option>
            ';
        }

        return $list;
    }

    public function coa_view(Request $request)
    {
        $accname = $request->get('filter');
        $groupid = $request->get('acctype');
    
        $chartgroup = db::table('acc_coagroup')
            ->where(function($q) use($groupid){
                if($groupid != 0)
                {
                    $q->where('id', $groupid);
                }
            })
            ->where('deleted', 0)
            ->orderBy('sortid', 'ASC')
            ->get();

        $coalist = '';

        foreach($chartgroup as $group)
        {
            $coalist .='
                <tr class="text-bold" data-value="group" data-id="'.$group->id.'">
                    <td colspan="4">
                        '.strtoupper($group->group).' &nbsp;&nbsp;&nbsp;
                        <span class="d-none subfunction">
                            <span class="btn btn-xs btn-info text-xs group_edit " style="width:35px;">
                                <i class="far fa-edit"></i>
                            </span>
                            <span class="btn btn-xs btn-danger text-xs group_remove " style="width:35px;">
                                <i class="fas fa-trash-alt"></i>
                            </span>
                            <span class="btn btn-xs btn-primary text-xs group_addchild " style="width:35px;">
                                <i class="fas fa-plus"></i>
                            </span>
                        </span>
                    </td>
                </tr>
            ';


            $chartlist = db::table('acc_coa')
                ->select('acc_coa.id as coaid', 'code', 'account', 'groupid', 'classification', 'mapname')
                ->join('acc_coagroup', 'acc_coa.gid', '=', 'acc_coagroup.id')
                ->join('acc_coaclass', 'acc_coagroup.coaclass', '=', 'acc_coaclass.id')
                ->leftjoin('acc_map', 'acc_coa.mapid', '=', 'acc_map.id')
                ->where('sub1', 0)
                ->where('sub2', 0)
                ->where('gid', $group->id)
                ->where(function($q) use($accname){
                    $q->where('account', 'like', '%'.$accname.'%')
                        ->orWhere('code', 'like', '%'.$accname.'%');
                })
                ->where('acc_coa.deleted', 0)
                ->orderBy('code', 'ASC')
                ->get();

            foreach($chartlist as $list)
            {
                $coalist .= '
                    <tr data-value="acc" data-id="'.$list->coaid.'" data-value="account">
                        <td>'.$list->code.'</td>
                        <td>
                            '.$list->account.' &nbsp;&nbsp;&nbsp;
                            <span class="d-none subfunction">
                                <span class="btn btn-xs btn-info text-xs account_edit " style="width:35px;">
                                    <i class="far fa-edit"></i>
                                </span>
                                <span class="btn btn-xs btn-danger text-xs account_remove " style="width:35px;">
                                    <i class="fas fa-trash-alt"></i>
                                </span>
                                <span class="btn btn-xs btn-primary text-xs account_addchild " style="width:35px;">
                                    <i class="fas fa-plus"></i>
                                </span>
                            </span>
                        </td>
                        <td>'.$list->classification.'</td>
                        <td>'.$list->mapname.'</td>
                    </tr>
                ';

                $sublist = db::table('acc_coa')
                    ->select('acc_coa.id as coaid', 'code', 'account', 'groupid', 'classification', 'mapname')
                    ->join('acc_coagroup', 'acc_coa.gid', '=', 'acc_coagroup.id')
                    ->join('acc_coaclass', 'acc_coagroup.coaclass', '=', 'acc_coaclass.id')
                    ->leftjoin('acc_map', 'acc_coa.mapid', '=', 'acc_map.id')
                    ->where('sub1id', $list->coaid)
                    ->where('acc_coa.deleted', 0)
                    ->where(function($q) use($accname){
                        $q->where('account', 'like', '%'.$accname.'%')
                            ->orWhere('code', 'like', '%'.$accname.'%');
                    })
                    ->orderBy('code', 'ASC')
                    ->get();

                if(count($sublist) > 0)
                {
                    foreach($sublist as $sub)
                    {
                        $coalist .='
                            <tr class="font-italic" data-value="sub" data-id="'.$sub->coaid.'">
                                <td></td>
                                <td> 
                                    '.$sub->code.' - '.$sub->account.' &nbsp;&nbsp;&nbsp;
                                    <span class="d-none subfunction">
                                        <span class="btn btn-xs btn-info text-xs account_edit " style="width:35px;">
                                            <i class="far fa-edit"></i>
                                        </span>
                                        <span class="btn btn-xs btn-danger text-xs account_remove " style="width:35px;">
                                            <i class="fas fa-trash-alt"></i>
                                        </span>
                                        <span class="btn btn-xs btn-primary text-xs account_addchild " style="width:35px;">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                    </span>
                                </td>
                                <td>'.$sub->classification.'</td>
                                <td>'.$sub->mapname.'</td>
                            </tr>           
                        ';

                        $itemlist = db::table('acc_coa')
                            ->select('acc_coa.id as coaid', 'code', 'account', 'groupid', 'classification', 'mapname')
                            ->join('acc_coagroup', 'acc_coa.gid', '=', 'acc_coagroup.id')
                            ->join('acc_coaclass', 'acc_coagroup.coaclass', '=', 'acc_coaclass.id')
                            ->leftjoin('acc_map', 'acc_coa.mapid', '=', 'acc_map.id')
                            ->where('sub2id', $list->coaid)
                            ->where('acc_coa.deleted', 0)
                            ->where(function($q) use($accname){
                                $q->where('account', 'like', '%'.$accname.'%')
                                    ->orWhere('code', 'like', '%'.$accname.'%');
                            })
                            ->orderBy('code', 'ASC')
                            ->get();

                        if(count($itemlist) >0)
                        {
                            foreach($itemlist as $ilist)
                            {
                                $coalist .='
                                    <tr class="font-italic" data-value="item" data-id="'.$ilist->coaid.'">
                                        <td></td>
                                        <td class="pl-5"> 
                                            '.$ilist->code.' - '.$ilist->account.' &nbsp;&nbsp;&nbsp;
                                            <span class="d-none subfunction">
                                                <span class="btn btn-xs btn-info text-xs account_edit " style="width:35px;">
                                                    <i class="far fa-edit"></i>
                                                </span>
                                                <span class="btn btn-xs btn-danger text-xs account_remove " style="width:35px;">
                                                    <i class="fas fa-trash-alt"></i>
                                                </span>
                                                <span class="btn btn-xs btn-primary text-xs account_addchild " style="width:35px;">
                                                    <i class="fas fa-plus"></i>
                                                </span>
                                            </span>
                                        </td>
                                        <td>'.$ilist->classification.'</td>
                                        <td>'.$ilist->mapname.'</td>
                                    </tr>           
                                ';
                            }
                        }


                    }
                }
            }
        }

        return $coalist;
    }

    public function coa_viewgroup(Request $request)
    {
        $groupid = $request->get('groupid');

        $group = db::table('acc_coagroup')
            ->where('id', $groupid)
            ->first();

        $groupname = '';
        $maxcode = '';

        if($group)
        {
            $groupname = $group->group;
            $maxcode = db::table('acc_coa')
                ->where('gid', $groupid)
                ->where('sub1', 0)
                ->where('sub2', 0)
                ->where('sub3', 0)
                ->where('deleted', 0)
                ->max('code');
        }

        // return $maxcode;

        $maxcode ++;

        $data = array(
            'groupname' => $groupname,
            'maxcode' => $maxcode
        );

        echo json_encode($data);
    }

    public function coa_saveaccount(Request $request)
    {
        $dataid = $request->get('dataid');
        $groupid = $request->get('groupid');
        $groupname = $request->get('groupname');
        $dataval = $request->get('dataval');
        $code = $request->get('code');
        $account = $request->get('account');
        $mapid = $request->get('mapid');
        $maxcode = $code;

        if($dataid == 0)
        {
            if($dataval == 'group')
            {
                $check = db::table('acc_coa')
                    ->where(function($q) use($code, $account){
                        $q->where('code', $code)
							->where('account', $account);
                    })
                    ->where('deleted', 0)
                    ->count();

                if($check == 0)
                {
                    db::table('acc_coa')
                        ->insert([
                            'code' => $code,
                            'account' => $account,
                            'groupid' => $groupname,
                            'gid' => $groupid,
                            'mapid' => $mapid,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);

                    $maxcode ++;
                    return $maxcode;
                }
                else
                {
                    return 'exist';
                }
            }
            else
            {
                $subid = $request->get('subid');

                $check = db::table('acc_coa')
                    ->where('code', $code)
                    ->where('account', $account)
                    ->where('deleted', 0)
                    ->count();

                if($check == 0)                
                {
                    $sub1 = 0;
                    $sub2 = 0;
                    $sub3 = 0;
                    $sub = 'sub1';


                    $coa = db::table('acc_coa')
                        ->where('id', $subid)
                        ->first();
                    if($coa)
                    {
                        $groupid = $coa->gid;
                        $groupname = $coa->groupid;

                        if($coa->sub1 == 0)
                        {
                            $sub1 = 0;
                        }
                        else
                        {
                            $sub1 = 1;
                            $sub = 'sub2';
                        }

                        if($coa->sub2 == 0)
                        {
                            $sub2 = 0;
                        }
                        else
                        {
                            $sub2 = 1;
                            $sub = 'sub3';
                        }                        
                    }

                    db::table('acc_coa')
                        ->insert([
                            'code' => $code,
                            'account' => $account,
                            'groupid' => $groupname,
                            'gid' => $groupid,
                            'mapid' => $mapid,
                            $sub => 1,
                            $sub.'id' => $subid,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);

                    $maxcode ++;
                    return $maxcode;   
                }
                else
                {
                    return 'exist';
                }
            }
        }
        else
        {
            $check = db::table('acc_coa')
                ->where(function($q) use($code, $account){
                    $q->where('code', $code)
                        ->orWhere('account', $account);
                })
                ->where('deleted', 0)
                ->where('id', '!=', $dataid)
                ->count();

            if($check == 0)
            {
                db::table('acc_coa')
                    ->where('id', $dataid)
                    ->update([
                        'code' => $code,
                        'account' => $account,
                        'mapid' => $mapid,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);

                return 'update';
            }
            else
            {
                return 'exist';
            }
        }
    }

    public function coa_removeaccount(Request $request)
    {
        $accountid = $request->get('accountid');

        $check = db::table('acc_coa')
            ->where('sub1id', $accountid)
            ->where('deleted', 0)
            ->count();

        if($check > 0)
        {
            return 'exist';
        }
        else
        {
            db::table('acc_coa')
                ->where('id', $accountid)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => FinanceModel::getServerDateTime(),
                    'deletedby' => auth()->user()->id
                ]);

            return 'done';
        }



            
    }

    public function coa_editaccount(Request $request)
    {
        $dataid = $request->get('dataid');

        $acc = db::table('acc_coa')
            ->where('id', $dataid)
            ->first();

        $group = $acc->groupid;
        $code = $acc->code;
        $account = $acc->account;
        $mapid = $acc->mapid;

        $data = array(
            'group' => $group,
            'code' => $code,
            'account' => $account,
            'mapid' => $mapid
        );

        echo json_encode($data);
    }

    public function coa_addsubaccount(Request $request)
    {
        $subid = $request->get('subid');

        $acc = db::table('acc_coa')
            ->where('id', $subid)
            ->first();

        if($acc->sub1id == 0)
        {
            $subcode = db::table('acc_coa')
                ->where('sub1id', $subid)
                ->where('deleted', 0)
                ->max('code');

            if($subcode > 0 || $subcode != '')
            {
                $subcode ++;
            }
        }
        else
        {
            $subcode = db::table('acc_coa')
                ->where('sub2id', $subid)
                ->where('deleted', 0)
                ->max('code');

            if($subcode > 0 || $subcode != '')
            {
                $subcode ++;
            }   
        }



        $data = array(
            'subcode' => $subcode,
            'account' => $acc->code . ' - ' . $acc->account
        );

        echo json_encode($data);
    }

    public function coa_classcreate(Request $request)
    {
        $list = '';

        $classifications = db::table('acc_coaclass')
            ->where('deleted', 0)
            ->get();

        foreach($classifications as $class)
        {
            $list .='
                <tr data-id="'.$class->id.'">
                    <td>'.$class->classification.'</td>
                </<tr>
            ';
        }

        return $list;
    }

    public function coa_class_load(Request $request)
    {
        $dataid = $request->get('dataid');
        $class = db::table('acc_coaclass')
            ->where('id', $dataid)
            ->first();

        return $class->classification;

    }

    public function coa_class_update(Request $request)
    {
        $dataid = $request->get('dataid');
        $class = $request->get('class');

        $check = db::table('acc_coaclass')
            ->where('classification', $class)
            ->where('id', '!=', $dataid)
            ->where('deleted', 0)
            ->count();

        if($check == 0)
        {
            db::table('acc_coaclass')
                ->where('id', $dataid)
                ->update([
                    'classification' => $class,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => FinanceModel::getServerDateTime()
                ]);

            return 'done';
        }
        else
        {
            return 'exist';
        }
    }

    public function coa_class_create(Request $request)
    {
        $class = $request->get('class');

        $check = db::table('acc_coaclass')
            ->where('classification', $class)
            ->where('deleted', 0)
            ->count();

        // return $ch/eck;

        if($check == 0)
        {
            db::table('acc_coaclass')
                ->insert([
                    'classification' => $class,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);

            return 'done';
        }
        else
        {
            return 'exist';
        }
    }

    public function coa_class_remove(Request $request)
    {
        $dataid = $request->get('dataid');

        $check = db::table('acc_coagroup')
            ->where('coaclass', $dataid)
            ->where('deleted', 0)
            ->count();

        if($check == 0)
        {
            db::table('acc_coaclass')
                ->where('id', $dataid)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => FinanceModel::getServerDateTime(),
                    'deletedby' => auth()->user()->id
                ]);

            return 'done';
        }
        else
        {
            return 'exist';
        }
    }

    public function coa_coaclass_load()
    {
        $classifications = db::table('acc_coaclass')
            ->where('deleted', 0)
            ->get();

        $list = '<option value="0">CLASSIFICATION</option>';

        foreach($classifications as $class)
        {
            $list .='
                <option value="'.$class->id.'">'.strtoupper($class->classification).'</option>
            ';
        }

        $accgroup = db::table('acc_coagroup')
            ->select('sortid')
            ->where('deleted', 0)
            ->orderBy('sortid', 'DESC')
            ->first();

        $sortid = 0;

        if($accgroup)
        {
            $sortid = $accgroup->sortid + 1;
        }
        else
        {
            $sortid = 1;
        }
        
        $data = array(
            'sortid' => $sortid,
            'list' => $list
        );

        return json_encode($data);
    }

    public function coa_acctype_create(Request $request)
    {
        $acctype = $request->get('acctype');
        $classid = $request->get('classid');
        $sortid = $request->get('sortid');

        $check = db::table('acc_coagroup')
            ->where('group', $acctype)
            ->where('deleted', 0)
            ->count();

        if($check > 0)
        {
            return 'exist';
        }
        else
        {
            db::table('acc_coagroup')   
                ->insert([
                    'group' => $acctype,
                    'deleted' => 0,
                    'sortid' => $sortid,
                    'coaclass' => $classid
                ]);

            return 'done';
        }
    }

    public function coa_acctype_read(Request $request)
    {
        $dataid = $request->get('dataid');

        $groups = db::table('acc_coagroup')
            ->where('id', $dataid)
            ->first();

        $data = array(
            'group' => $groups->group,
            'classid' => $groups->coaclass,
            'sortid' => $groups->sortid
        );

        return json_encode($data);

    }

    public function coa_acctype_update(Request $request)
    {
        $dataid = $request->get('dataid');
        $acctype = $request->get('acctype');
        $classid = $request->get('classid');
        $sortid = $request->get('sortid');

        $check = db::table('acc_coagroup')
            ->where('group', $acctype)
            ->where('id', '!=', $dataid)
            ->count();

        if($check > 0)
        {
            return 'exist';
        }
        else
        {
            db::table('acc_coagroup')
                ->where('id', $dataid)
                ->update([
                    'group' => $acctype,
                    'coaclass' => $classid,
                    'sortid' => $sortid,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => FinanceModel::getServerDateTime()
                ]);

            return 'done';

        }
    }

    public function coa_acctype_delete(Request $request)
    {
        $dataid = $request->get('dataid');

        $check = db::table('acc_coa')
            ->where('gid', $dataid)
            ->where('deleted', 0)
            ->count();

        if($check > 0)
        {
            return 'exist';
        }
        else
        {
            db::table('acc_coagroup')
                ->where('id', $dataid)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => FinanceModel::getServerDateTime(),
                    'deletedby' => auth()->user()->id
                ]);

            return 'done';
        }

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
