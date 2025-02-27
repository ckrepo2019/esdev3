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

class StudUpdateLedgerController extends Controller
{
    public function studupdateledger()
    {
        return view('finance.students');
    }

    public function studupdateledgerLoadSections(Request $request)
    {
        $levelid = $request->get('levelid');
        $list = '';

        if($levelid == 14 || $levelid == 15)
        {
            $sections = db::table('sections')
                ->select('sections.id', 'sectionname')
                ->join('sectiondetail', 'sections.id', '=', 'sectiondetail.sectionid')
                ->where('sections.deleted', 0)
                ->where('syid', FinanceModel::getSYID())
                ->where('levelid', $levelid)
                ->get();

            $list .='
                <option value="0">All - Section</option>
            ';

            foreach($sections as $section)
            {
                $list .='
                    <option value="'.$section->id.'">'.$section->sectionname.'</option>
                ';
            }

        }
        elseif($levelid >= 17 && $levelid <= 25)
        {
            $courses = db::table('college_courses')
                ->select('id', 'courseabrv as code')
                ->where('deleted', 0)
                ->get();

            $list .='
                <option value="0">All - Courses</option>
            ';

            foreach($courses as $course)
            {
                $list .='
                    <option value="'.$course->id.'">'.$course->code.'</option>
                ';
            }
        }
        else
        {
            $sections = db::table('sections')
                ->select('sections.id', 'sectionname')
                ->join('sectiondetail', 'sections.id', '=', 'sectiondetail.sectionid')
                ->where('sections.deleted', 0)
                ->where('syid', FinanceModel::getSYID())
                ->where('levelid', $levelid)
                ->get();

            $list .='
                <option value="0">All - Section</option>
            ';

            foreach($sections as $section)
            {
                $list .='
                    <option value="'.$section->id.'">'.$section->sectionname.'</option>
                ';
            }
        }

        return $list;
    }

    public function studupdateledgerLoadStudents(Request $request)
    {
        $levelid = $request->get('levelid');
        $sectionid = $request->get('sectionid');
        $courseid = $request->get('courseid');
        $subjid = $request->get('subjid');
        $grantee = $request->get('grantee');
        $strand = $request->get('strand');
        $filter = $request->get('filter');
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        if($levelid == 14 || $levelid == 15)
        {
            $enrollinfo = db::table('sh_enrolledstud')
                ->select(db::raw('sid, studid, lastname, firstname, middlename, CONCAT(lastname, ", ", firstname) AS fullname, levelname, sections.sectionname, tuitionheader.description as feesname, sh_enrolledstud.feesid'))
                ->join('studinfo', 'sh_enrolledstud.studid','=', 'studinfo.id')
                ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->leftjoin('tuitionheader', 'sh_enrolledstud.feesid', 'tuitionheader.id')
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
                ->where(function($q) use($sectionid){
                    if($sectionid != 0)
                    {
                        $q->where('sh_enrolledstud.sectionid', $sectionid);
                    }
                })
                ->where(function($q) use($strand, $grantee){
                    if($grantee != 0)
                    {
                        $q->where('sh_enrolledstud.grantee', $grantee);
                    }

                    if($strand != 0)
                    {
                        $q->where('sh_enrolledstud.strandid', $strand);
                    }
                })
                ->where('sh_enrolledstud.deleted', 0)
                ->having('fullname', 'like', '%'.$filter.'%')
                ->orderBy('lastname')
                ->orderBy('firstname')
				->groupBy('studid')
                ->get();
        }
        elseif($levelid >= 17 && $levelid <= 25)
        {
            if($subjid == 0)
            {
                $enrollinfo = db::table('college_enrolledstud')
                    ->select(db::raw('sid, studid, lastname, firstname, middlename, CONCAT(lastname, ", ", firstname) AS fullname, levelname, college_sections.sectiondesc as sectionname, tuitionheader.description as feesname, college_enrolledstud.feesid'))
                    ->join('studinfo', 'college_enrolledstud.studid', 'studinfo.id')
                    ->join('college_sections', 'college_enrolledstud.sectionid', '=', 'college_sections.id')
                    ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                    ->leftjoin('tuitionheader', 'college_enrolledstud.feesid', 'tuitionheader.id')
                    ->where('yearlevel', $levelid)
                    ->where('college_enrolledstud.syid', $syid)
                    ->where('college_enrolledstud.semid', $semid)
                    ->where('college_enrolledstud.deleted', 0)
                    ->where(function($q) use($courseid){
                        if($courseid != 0)
                        {
                            $q->where('college_enrolledstud.courseid', $courseid);
                        }
                    })
                    ->having('fullname', 'like', '%'.$filter.'%')
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->get();
            }
            else
            {
                $enrollinfo = db::table('college_studsched')
                    ->select(db::raw('sid, CONCAT(sid, " - ", lastname, ", ", firstname) AS fullname, middlename, college_studsched.studid, college_prospectus.subjectid AS subjid, college_studsched.feesid, tuitionheader.description AS feesname, subjcode, subjdesc, courseabrv, sectiondesc as sectionname, levelname'))
                    ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                    ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                    ->join('college_sections', 'college_classsched.sectionid', '=', 'college_sections.id')
                    ->join('college_enrolledstud', 'college_studsched.studid', '=', 'college_enrolledstud.studid')
                    ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                    ->join('tuitionheader', 'college_enrolledstud.feesid', '=', 'tuitionheader.id')
                    ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                    ->join('gradelevel', 'college_enrolledstud.yearlevel', '=', 'gradelevel.id')
                    ->where('college_studsched.deleted', 0)
                    ->where('college_classsched.deleted', 0)
                    ->where('college_classsched.syid', $syid)
                    ->where('college_classsched.semesterid', $semid)
                    ->where('college_prospectus.subjectid', $subjid)
                    ->where('college_prospectus.deleted', 0)
                    ->where('college_enrolledstud.deleted', 0)
                    ->groupBy('college_enrolledstud.studid')
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->get();
            }
        }
        else
        {
            $enrollinfo = db::table('enrolledstud')
                ->select(db::raw('sid, studid, lastname, firstname, middlename, CONCAT(lastname, ", ", firstname) AS fullname, levelname, sections.sectionname,  tuitionheader.description as feesname, enrolledstud.feesid'))
                ->join('studinfo', 'enrolledstud.studid','=', 'studinfo.id')
                ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->leftjoin('tuitionheader', 'enrolledstud.feesid', 'tuitionheader.id')
                ->where('enrolledstud.levelid', $levelid)
                ->where('enrolledstud.syid', $syid)
                ->where(function($q) use($semid){
                    if($semid == 3)
                    {
                        $q->where('enrolledstud.ghssemid', 3);
                    }
                    else
                    {
                        $q->where('enrolledstud.ghssemid', '!=', 3);
                    }
                })
                ->where(function($q) use($sectionid){
                    if($sectionid != 0)
                    {
                        $q->where('enrolledstud.sectionid', $sectionid);
                    }
                })
                ->where(function($q) use($grantee){
                    if($grantee != 0)
                    {
                        $q->where('enrolledstud.grantee', $grantee);
                    }
                })
                ->where('enrolledstud.deleted', 0)
                ->having('fullname', 'like', '%'.$filter.'%')
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->get();   
        }

        $list = '';

        foreach($enrollinfo as $stud)
        {
            $list .='
                <tr data-id="'.$stud->studid.'">
                    <td>'.$stud->sid.' - '.$stud->fullname.'</td>
                    <td>'.$stud->levelname.' - '.$stud->sectionname.'</td>
                    <td>
                        <button stud-id="'.$stud->studid.'" fees-id="'.$stud->feesid.'" sy="'.$syid.'" sem="'.$semid.'" class="btn btn-sm text-sm btn-primary btn-reset">'.$stud->feesname.'</button>
                    </td>
                </tr>
            ';
        }

        return $list;
    }

    public function stud_loadfee(Request $request)
    {
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $array_fees = array();

        if($semid == 0)
        {
            $semid = FinanceModel::getSYID();
        }
        
        $fees = db::table('tuitionheader')
            ->select('id', 'description as text')
            ->where('deleted', 0)
            ->where('syid', $syid)
            ->where(function($q) use($semid, $levelid){
                if($semid == 3)
                {
                    $q->where('semid', 3);
                }
                else{
                    if($levelid == 14 || $levelid == 15)
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    }
                    elseif($levelid >= 17 && $levelid <= 25)
                    {
                        $q->where('semid', $semid);
                    }
                }
            })
            ->where('levelid', $levelid)
            ->get();
        
        array_push($array_fees, (object)[
            'id'=> 0,
            'text' => 'FEES AND COLLECTION'
        ]);
        
        foreach($fees as $fee)
        {
            array_push($array_fees, (object)[
                'id' => $fee->id,
                'text' => $fee->text
            ]);
        }
        
        
        // $array_fees = collect($fees);
        

        return $array_fees;
    }

}