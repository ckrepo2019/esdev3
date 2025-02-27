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

class AssessmentUnitController extends Controller
{
    public function assessmentunit()
    {
        return view('/finance/assessmentunit/assessmentunit');
    }

    public function au_search(Request $request)
    {
        if($request->ajax())
        {
            $filter = $request->get('filter');

            $subjects = db::table('tuition_assessmentunit')
                ->select('tuition_assessmentunit.id', 'subjid', 'subjCode', 'subjDesc', 'lecunits', 'labunits', 'assessmentunit')
                ->join('college_subjects', 'tuition_assessmentunit.subjid', '=', 'college_subjects.id')
                ->where('subjCode', 'like', '%'.$filter.'%')
                ->where('tuition_assessmentunit.deleted', 0)
                ->orWhere('subjDesc', 'like', '%'.$filter.'%')
                ->where('tuition_assessmentunit.deleted', 0)
                ->get();

            $list = '';

            foreach($subjects as $subj)
            {
                $list .='
                    <tr data-id="'.$subj->id.'">
                        <td>'.$subj->subjCode.'</td>
                        <td>'.$subj->subjDesc.'</td>
                        <td class="text-center">'.$subj->lecunits.'</td>
                        <td class="text-center">'.$subj->labunits.'</td>
                        <td class="text-center">'.$subj->assessmentunit.'</td>
                    </tr>
                ';
            }

            $data = array(
                'list' => $list
            );

            echo json_encode($data);

        }
    }

    public function au_subjinfo(Request $request)
    {
        if($request->ajax())
        {
            $subjid = $request->get('subjid');

            $subj = db::table('college_subjects')
                ->select('lecunits', 'labunits')
                ->where('id', $subjid)
                ->first();

            $lecunits = $subj->lecunits;
            $labunits = $subj->labunits;

            $data = array(
                'lecunits' => $lecunits,
                'labunits' => $labunits
            );

            echo json_encode($data);
        }
    }

    public function au_savesubj(Request $request)
    {
        if($request->ajax())
        {
            $subjid = $request->get('subjid');
            $units = $request->get('units');

            $check = db::table('tuition_assessmentunit')
                ->where('subjid', $subjid)
				->where('deleted', 0)
                ->count();

            if($check > 0)
            {
                return 'exist';
            }
            else
            {
                db::table('tuition_assessmentunit')   
                    ->insert([
                        'subjid' => $subjid,
                        'assessmentunit' => $units,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime(),
                    ]);

                return 'done';
            }
        }
    }

    public function au_edit(Request $request)
    {
        if($request->ajax())
        {
            $dataid = $request->get('dataid');

            $subj = db::table('tuition_assessmentunit')
                ->select('tuition_assessmentunit.id', 'subjid', 'subjCode', 'subjDesc', 'lecunits', 'labunits', 'assessmentunit')
                ->join('college_subjects', 'tuition_assessmentunit.subjid', '=', 'college_subjects.id')
                ->where('tuition_assessmentunit.id', $dataid)
                ->first();


            $subjid = $subj->subjid;
            $lecunits = $subj->lecunits;
            $labunits = $subj->labunits;
            $units = $subj->assessmentunit;

            $data = array(
                'subjid' => $subjid,
                'lecunits' => $lecunits,
                'labunits' => $labunits,
                'units' => $units
            );

            echo json_encode($data);
        }
    }

    public function au_update(Request $request)
    {
        if($request->ajax())
        {
            $subjid = $request->get('subjid');
            $units = $request->get('units');
            $dataid = $request->get('dataid');

            $check = db::table('tuition_assessmentunit')
                ->where('subjid', $subjid)
                ->where('id', '!=', $dataid)
                ->count();


            if($check > 0)
            {
                return 'exist';
            }
            else
            {
                db::table('tuition_assessmentunit')
                    ->where('id', $dataid)
                    ->update([
                        'subjid' => $subjid,
                        'assessmentunit' => $units,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);

                return 'done';
            }
        }
    }

    public function au_delete(Request $request)
    {
        if($request->ajax())
        {
            $dataid = $request->get('dataid');

            db::table('tuition_assessmentunit')
                ->where('id', $dataid)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => FinanceModel::getServerDateTime(),
                    'deletedby' => auth()->user()->id
                ]);
        }
    }

}