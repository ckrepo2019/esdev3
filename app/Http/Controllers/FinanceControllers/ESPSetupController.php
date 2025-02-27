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

class ESPSetupController extends Controller
{
    public function espsetup()
    {
        return view('/finance/espsetup');
    }

    public function esp_loaddetail(Request $request)    
    {
        $levelid = $request->get('levelid');
        // $syid = $request->get('syid');
        $estud = '';
        $subjlist = '';

        if($levelid == 14 || $levelid == 15)
        {
            $estud = 'sh_subjects';
        }
        elseif($levelid >= 17 && $levelid <= 20)
        {
            
        }
        else
        {
            $estud = 'subjects';
        }

        if($estud != '')
        {
            $subjects = db::table($estud)
                    ->where('deleted', 0)
                    ->get();

            foreach($subjects as $subj)
            {
                $amountlist = '';
                $esp = db::table('tuitionesp')
                    ->select(db::raw('subjdesc, itemclassification.`description`, amount'))
                    ->join($estud, 'tuitionesp.subjid', '=', $estud.'.id')
                    ->join('tuitionespdetail', 'tuitionesp.id', 'tuitionespdetail.headerid')
                    ->join('itemclassification', 'tuitionespdetail.classid', 'itemclassification.id')
                    ->where('tuitionesp.levelid', $levelid)
                    ->where('tuitionespdetail.deleted', 0)
                    ->where('subjid', $subj->id)
                    ->get();

                if(count($esp) > 0)
                {
                    foreach($esp as $e)
                    {
                        $amountlist .='
                            '.$e->description.' - '.number_format($e->amount, 2).' <br>
                        ';
                    }
                }

                $subjlist .='
                    <tr data-id="'.$subj->id.'">
                        <td class="subj">'.strtoUpper($subj->subjdesc).'</td>
                        <td>'.strtoUpper($amountlist).'
                        </td>
                    </tr>
                ';
            }
        }

        return $subjlist;
    }
        

    public function esp_update(Request $request)
    {
        $levelid = $request->get('levelid');
        $subjid = $request->get('subjid');
        $classid = $request->get('classid');
        $amount = $request->get('amount');
        $headerid = 0;

        $chkheader = db::table('tuitionesp')
            ->where('levelid', $levelid)
            ->where('subjid', $subjid)
            ->first();

        if($chkheader)
        {
            $headerid = $chkheader->id;
        }
        else
        {
            $headerid = db::table('tuitionesp')
                ->insertGetId([
                    'levelid' => $levelid,
                    'subjid' => $subjid,
                    'deleted' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);
        }

        $chkdetail = db::table('tuitionespdetail')
            ->where('headerid', $headerid)
            ->where('classid', $classid)
            ->first();

        if($chkdetail)
        {
            db::table('tuitionespdetail')
                ->where('id', $chkdetail->id)
                ->update([
                    'amount' => $amount,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => FinanceModel::getServerDateTime()
                ]);
        }
        else
        {
            db::table('tuitionespdetail')
                ->insert([
                    'headerid' => $headerid,
                    'classid' => $classid,
                    'amount' => $amount,
                    'deleted' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);
        }
    }

}