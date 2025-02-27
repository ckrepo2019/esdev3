<?php

namespace App\Http\Controllers\FinanceControllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\FinanceModel;
use App\DisplayModel;
use App\Models\Finance\FinanceUtilityModel;
use DB;
use NumConvert;
use PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ItmclsItemClassificationController extends Controller
{
    public function itemclassification()
    {
        return view('finance/itemclassification_v2');
    }

    public function itmclsgenerate()
    {
        // return 'aaa';
        $array_class = array();
        $oaclassid = db::table('balforwardsetup')->first()->classid;
        $classification = db::table('itemclassification')
            ->select('itemclassification.id', 'itemclassification.description', 'code', 'account', 'items_classcode.description as classcode')
            ->leftjoin('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
            ->leftjoin('items_classcode', 'itemclassification.classcode', '=', 'items_classcode.id')
            ->where('itemclassification.deleted', 0)
            // ->where('itemclassification.id', '!=', $oaclassid)
            ->get();

        foreach($classification as $class)
        {
            $groupname = '';
            $itemized = 0;

            $setup = db::table('chrngsetup')
                ->where('classid', $class->id)
                ->first();
            
            if($setup)
            {
                $groupname = $setup->groupname;
                $itemized = $setup->itemized;
            }
            else
            {
                $groupname = '';
                $itemized = '';
            }

            array_push($array_class, (object)[
                'id' => $class->id,
                'description' => $class->description,
                'account' => $class->code . ' - ' . $class->account,
                'groupname' => $groupname,
                'itemized' => $itemized,
                'classcode' => $class->classcode
            ]);
        }
        // dd($array_class);
        return $array_class;

    }

    public function itmclscreate(Request $request)
    {
        $description = $request->get('description');
        $glid = $request->get('account');
        $group = $request->get('group');
        $itemized = $request->get('itemized');
        $classcode = $request->get('classcode');

        $check = db::table('itemclassification')
            ->where('description', 'like', '%'.$description.'%')
            ->where('deleted', 0)
            ->first();
        
        if($check)
        {
            return 'exist';
        }
        else
        {
            $classid = db::table('itemclassification')
                ->insertGetID([
                    'description' => $description,
                    'glid' => $glid,
                    'classcode' => $classcode,
                    'deleted' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);
            
            if($group != '')
            {
                $checkgroup = db::table('chrngsetup')
                    ->where('classid', $classid)
                    ->where('deleted', 0)
                    ->first();
                
                if(!$checkgroup)
                {
                    db::table('chrngsetup')
                        ->insert([
                            'classid' => $classid,
                            'itemized' => $itemized,
                            'groupname' => $group,
                            'deleted' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }
                else
                {
                    db::table('chrngsetup')
                        ->where('classid', $classid)
                        ->update([
                            // 'classid' => $classid,
                            'itemized' => $itemized,
                            'groupname' => $group,
                            'deleted' => 0,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }
            }
            
            return 'done';
        }
    }

    public function itmclsread(Request $request)
    {
        $dataid = $request->get('dataid');
        $withtransaction = 0;

        $class = db::table('itemclassification')
            ->select(db::raw('itemclassification.id, itemclassification.description, glid, chrngsetup.id AS setupid, groupname, itemized, items_classcode.id as classcode'))
            ->leftjoin('chrngsetup', 'itemclassification.id', '=', 'chrngsetup.classid')
            ->leftjoin('items_classcode', 'itemclassification.classcode', '=', 'items_classcode.id')
            ->where('itemclassification.id', $dataid)
            ->first();

        $cashtrans = db::table('chrngcashtrans')
            ->where('classid', $dataid)
            ->where('deleted', 0)
            ->first();
        
        if($cashtrans)
        {
            $withtransaction = 1;
        }
        else{
            $withtransaction = 0;
        }

        $data = array(
            'description' => $class->description,
            'glid' => $class->glid,
            'group' => $class->groupname,
            'itemized' => $class->itemized,
            'classcode' => $class->classcode,
            'withtransaction' => $withtransaction
        );

        return $data;
    }

    public function itmclsupdate(Request $request)
    {
        $dataid = $request->get('dataid');
        $description = $request->get('description');
        $glid = $request->get('account');
        $group = $request->get('group');
        $itemized = $request->get('itemized');
        $classcode = $request->get('classcode');

        $check = db::table('itemclassification')
            ->where('description', $description)
            ->where('deleted', 0)
            ->where('id', '!=', $dataid)
            ->first();
        
        if(!$check)
        {
            db::table('itemclassification')
                ->where('id', $dataid)
                ->update([
                    'description' => $description,
                    'glid' => $glid,
                    'classcode' => $classcode,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => FinanceModel::getServerDateTime()
                ]);
            
            $checkgroup = db::table('chrngsetup')
                ->where('classid', $dataid)
                ->where('deleted', 0)
                ->first();
            
            if(!$checkgroup)
            {
                db::table('chrngsetup')
                    ->insert([
                        'classid' => $dataid,
                        'itemized' => $itemized,
                        'groupname' => $group,
                        'deleted' => 0,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }
            else
            {
                db::table('chrngsetup')
                    ->where('classid', $dataid)
                    ->update([
                        // 'classid' => $classid,
                        'itemized' => $itemized,
                        'groupname' => $group,
                        'deleted' => 0,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }

            return 'done';
        }
        else{
            return 'exist';
        }
    }

    public function itmclsdelete(Request $request)
    {
        $dataid = $request->get('dataid');

        db::table('itemclassification')
            ->where('id', $dataid)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);
        
        db::table('chrngsetup')
            ->where('classid', $dataid)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);
    }
    

    

    
}
    

   

