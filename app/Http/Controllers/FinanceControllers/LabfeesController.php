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

class LabfeesController extends Controller
{
    public function labfees()
    {
        return view('/finance/labfees/labfees');
    }

    public function labfee_append(Request $request)
    {
        if($request->ajax())
        {
            $subjid = $request->get('subjid');
            $amount = str_replace(',', '', $request->get('amount'));
            $action = $request->get('action');
            $dataid = $request->get('dataid');
            $courseid = $request->get('courseid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');

            if($action == 'New')
            {
                $checkexist = db::table('labfees')
                    ->where('subjid', $subjid)
                    ->where('syid', $syid)
                    ->where('courseid', $courseid)
                    ->where('levelid', $levelid)
                    ->where('deleted', 0)
                    ->count();

                if($checkexist > 0)
                {
                    return 'exist';
                }
                else
                {
                    db::table('labfees')
                        ->insert([
                            'subjid' => $subjid,
                            'amount' => $amount,
                            'syid' => $syid,
                            'semid' => $semid,
                            'courseid' => $courseid,
                            'levelid' => $levelid,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);

                    return 'saved';
                }
            }                
            else
            {
                $checkexist = db::table('labfees')
                    ->where('subjid', $subjid)
                    ->where('courseid', $courseid)
                    ->where('levelid', $levelid)
                    ->where('deleted', 0)
                    ->where('id', '!=', $dataid)
                    ->count();

                if($checkexist > 0)
                {
                    return 'exist';
                }
                else
                {
                    db::table('labfees')
                        ->where('id', $dataid)
                        ->update([
                            'subjid' => $subjid,
                            'amount' => $amount,
                            'syid' => $syid,
                            'semid' => $semid,
                            'courseid' => $courseid,
                            'levelid' => $levelid,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => FinanceModel::getServerDateTime()
                        ]);    

                    return 'saved';
                }
            }
            

        }
    }

    public function labfee_search(Request $request)
    {
        if($request->ajax())
        {
            $filter = $request->get('filter');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $labfees = db::table('labfees')
                ->select(db::raw('labfees.id, subjid, subjcode, subjdesc, amount'))
                ->leftJoin('college_subjects', 'labfees.subjid', '=', 'college_subjects.id')
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where(function($q) use($filter){
                    $q->where('subjcode', 'like', '%'.$filter.'%')
                        ->orWhere('subjdesc', 'like', '%'.$filter.'%');
                })
                ->where('labfees.deleted', 0)
                ->get();

            $list ='';

            foreach($labfees as $fee)
            {
                $list .= '
                    <tr data-id="'.$fee->id.'">
                        <td>'.$fee->subjcode.'</td>
                        <td>'.$fee->subjdesc.'</td>
                        <td class="text-right">'.number_format($fee->amount, 2).'</td>
                    </tr>
                ';
            }

            $labfees = db::table('labfees')
                ->select(db::raw('labfees.id, coursedesc, courseabrv, amount'))
                ->join('college_courses', 'labfees.courseid', '=', 'college_courses.id')
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where(function($q) use($filter){
                    $q->where('courseDesc', 'like', '%'.$filter.'%')
                        ->orWhere('courseabrv', 'like', '%'.$filter.'%');
                })
                ->where('labfees.deleted', 0)
                ->get();

            foreach($labfees as $fee)
            {
                $list .= '
                    <tr data-id="'.$fee->id.'">
                        <td>'.$fee->courseabrv.'</td>
                        <td>'.$fee->coursedesc.'</td>
                        <td class="text-right">'.number_format($fee->amount, 2).'</td>
                    </tr>
                ';
            }

            $data = array(
                'list' => $list
            );

            echo json_encode($data);
        }
    }

    public function labfee_edit(Request $request)
    {
        if($request->ajax())
        {
            $dataid = $request->get('dataid');

            $labfees = db::table('labfees')
                ->where('id', $dataid)
                ->first();

            $data = array(
                'subjid' => $labfees->subjid,
                'amount' => $labfees->amount,
                'syid' => $labfees->syid,
                'semid' => $labfees->semid,
                'courseid' => $labfees->courseid,
                'levelid' => $labfees->levelid
            );

            echo json_encode($data);
        }
    }

    public function labfee_delete(Request $request)
    {
        if($request->ajax())
        {
            $dataid = $request->get('dataid');

            db::table('labfees')
                ->where('id', $dataid)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => FinanceModel::getServerDateTime()
                ]);
        }
    }

    public function labfee_setup_append(Request $request)
    {
        if($request->ajax())
        {
            $classid = $request->get('classid');
            $mop = $request->get('mop');
            $semid = $request->get('semid');
            $sortid = $request->get('sortid');

            $checkexist = db::table('labfee_setup')
                ->where('semid', $semid)
                ->where('deleted', 0)
                ->count();
            
            if($checkexist > 0)
            {
                return 'exist';
            }
            else
            {
                db::table('labfee_setup')
                    ->insert([
                        'classid' => $classid,
                        'mop' => $mop,
                        'semid' => $semid,
                        'sortid' => $sortid,
                        'deleted' => 0,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);


                return 'done';
            }
        }
    }

    public function labfee_setup_edit(Request $request)
    {
        if($request->ajax())
        {
            $classid = $request->get('classid');
            $mop = $request->get('mop');
            $semid = $request->get('semid');
            $sortid = $request->get('sortid');

            $checkexist = db::table('labfee_setup')
                ->where('semid', $semid)
                ->where('sortid', '!=', $sortid)
                ->where('deleted', 0)
                ->count();
            
            if($checkexist > 0)
            {
                return 'exist';
            }
            else
            {
                db::table('labfee_setup')
                    ->where('sortid', $sortid)
                    ->update([
                        'classid' => $classid,
                        'mop' => $mop,
                        'semid' => $semid,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);


                return 'done';
            }
        }
    }

    public function labfee_setup_load(Request $request)
    {
        if($request->ajax())
        {
            $labfee_setup = db::table('labfee_setup')
                ->where('deleted', 0)
                ->orderBy('sortid')
                ->get();

            $list = '';
            $labfeeid = 0;

            foreach($labfee_setup as $setup)
            {
                $itemclassification = db::table('itemclassification')
                ->where('deleted', 0)
                ->get();

                $class_list ='';

                foreach($itemclassification as $class)
                {
                    if($setup->classid == $class->id)
                    {
                        $class_list .='
                            <option value="'.$class->id.'" selected>'.$class->description.'</option>
                        ';
                    }
                    else
                    {
                        $class_list .='
                            <option value="'.$class->id.'">'.$class->description.'</option>
                        ';
                    }
                }

                $modeofpayment = db::table('paymentsetup')
                    ->where('deleted', 0)
                    ->get();

                $mop_list = '';

                foreach($modeofpayment as $mop)
                {
                    if($setup->mop == $mop->id)
                    {
                        $mop_list .='
                            <option value="'.$mop->id.'" selected>'.$mop->paymentdesc.'</option>
                        ';
                    }
                    else
                    {
                        $mop_list .='
                            <option value="'.$mop->id.'">'.$mop->paymentdesc.'</option>
                        ';
                    }
                }

                $semester = db::table('semester')
                    ->where('deleted', 0)
                    ->get();

                $sem_list = '';

                foreach($semester as $sem)
                {
                    if($setup->semid == $sem->id)
                    {
                        $sem_list .='
                            <option value="'.$sem->id.'" selected>'.$sem->semester.'</option>
                        ';
                    }
                    else
                    {
                        $sem_list .='
                            <option value="'.$sem->id.'">'.$sem->semester.'</option>
                        ';
                    }
                }


                $labfeeid += 1;
                $list .='
                    <div id="'.$labfeeid.'" data-id="0" class="row mt-2 labfee_item">
                        <div class="col-md-4">
                            <select id="labfee_classid'.$labfeeid.'" class="select2bs4 labfee_classid labfee_fields" data-sort="'.$labfeeid.'" style="width: 100%">
                                <option>Classification</option>
                                '.$class_list.'
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="labfee_mop'.$labfeeid.'" class="select2bs4 labfee_mop labfee_fields" data-sort="'.$labfeeid.'" style="width: 100%">
                                <option>Mode of Payment</option>
                                '.$mop_list.'
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="labfee_sem'.$labfeeid.'" class="select2bs4 labfee_sem labfee_fields" data-sort="'.$labfeeid.'" style="width: 100%">
                                <option>Semester</option>
                                '.$sem_list.'
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-danger labfee_savesetup"  data-sort="'.$labfeeid.'"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                ';
            }

            $data = array(
                'list' => $list,
                'labfeeid' => $labfeeid
            );

            echo json_encode($data);
        }
    }

    public function labfee_setup_delete(Request $request)
    {
        if($request->ajax())
        {
            $sortid = $request->get('sortid');

            db::table('labfee_setup')
                ->where('sortid', $sortid)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => FinanceModel::getServerDateTime()
                ]);
        }
    }

    public function labfee_duplicate(Request $request)
    {
        $fromsyid = $request->get('fromsyid');
        $fromsemid = $request->get('fromsemid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $check = db::table('labfees')
            ->where('deleted', 0)
            ->where('semid', $semid)
            ->where('syid', $syid)
            ->first();

        if($check)
        {
            return 'exist';
        }
        else{
            // db::table('')

            $labfees = db::table('labfees')
                ->where('syid', $fromsyid)
                ->where('semid', $fromsemid)
                ->where('deleted', 0)
                ->get();
            
            if(count($labfees) > 0)
            {
                foreach($labfees as $lab)
                {
                    db::table('labfees')
                        ->insert([
                            'subjid' => $lab->subjid,
                            'amount' => $lab->amount,
                            'syid' => $syid,
                            'semid' => $semid,
                            'courseid' => $lab->courseid,
                            'deleted' => $lab->deleted
                        ]);
                }

                return 'done';
            }
            else{
                return 'nodata';
            }


        }
    }
    

}