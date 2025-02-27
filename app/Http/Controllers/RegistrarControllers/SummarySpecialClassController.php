<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class SummarySpecialClassController extends Controller
{
    public function reportssummariesspecialclass($id, Request $request)
    {

        $getstudents = DB::table('gradesspclass')
            ->select(
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'gradelevel.levelname',
                'gradesspclass.subjid',
                'academicprogram.acadprogcode'
            )
            ->join('studinfo', 'gradesspclass.studid','=','studinfo.id')
            ->join('gradelevel','gradesspclass.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->join('sy','gradesspclass.syid','=','sy.id')
            ->where('sy.isactive','1')
            ->where('gradelevel.deleted','0')
            ->get();
        // return $getstudents;
        return view('registrar.summaries.summaryspecialclass');

    }

}
