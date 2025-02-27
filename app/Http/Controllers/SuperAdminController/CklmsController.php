<?php

namespace App\Http\Controllers\SuperadminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class CklmsController extends Controller
{
    public static function fetchinfo(Request $request)
    {

        // http://sppv2.ck/ecr/download?syid=2&levelid=10&subjid=2&sectionid=1

        $activeSy = DB::table('sy')->where('isactive', 1)->first();

        $sections = DB::table('sectiondetail')
            ->where('syid', $activeSy->id)
            ->whereNotNull('sectiondetail.teacherid')
            ->where('sectiondetail.deleted', 0)
            ->join('sections', function ($join) {
                $join->on('sections.id', '=', 'sectiondetail.sectionid');
            })
            ->join('gradelevel', function ($join) {
                $join->on('gradelevel.id', '=', 'sections.levelid');
            })
            ->select('sections.*', 'gradelevel.levelname')
            ->get();


        foreach ($sections as $item) {
            $item->teacher = DB::table('teacher')
                ->where('teacher.id', $item->teacherid)
                ->join('users', function ($join) {
                    $join->on('users.id', '=', 'teacher.userid');
                })
                ->first();

            $item->student = DB::table('enrolledstud')
                ->where('enrolledstud.sectionid', $item->id)
                ->where('enrolledstud.deleted', 0)
                ->join('studinfo', function ($join) {
                    $join->on('studinfo.id', '=', 'enrolledstud.studid');
                })
                ->join('users', function ($join) {
                    $join->on('studinfo.sid', '=', DB::raw('SUBSTRING(users.email, 2)'));
                })
                ->get();



        }


        return $sections;



    }
}
