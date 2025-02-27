<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class SummaryController extends Controller
{
    public function reportssummariesallstudents($id, Request $request)
    {

        if ($id == 'dashboard') {

            $tracks = DB::table('sh_track')
                ->where('deleted', '0')
                ->get();

            $students = array();

            if ($request->get('studenttype') == true) {

                $selectedstudenttype = $request->get('studenttype');

                if ($request->get('studenttype') == 'new') {

                    $enrolledstudnew = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.gender',
                            'gradelevel.levelname'
                        )
                        ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                        ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                        ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                        ->where('enrolledstud.studstatus', '1')
                        ->where('sy.isactive', '1')
                        ->get();

                    if (count($enrolledstudnew) > 0) {

                        foreach ($enrolledstudnew as $stud) {

                            $studentinfo = Db::table('enrolledstud')
                                ->where('studid', $stud->id)
                                ->get();

                            if (count($studentinfo) == 1) {

                                array_push($students, $stud);

                            }

                        }

                    }

                    $shenrolledstudnew = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.gender',
                            'gradelevel.levelname'
                        )
                        ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                        ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                        ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                        ->where('sh_enrolledstud.studstatus', '1')
                        ->where('sy.isactive', '1')
                        ->get();

                    if (count($shenrolledstudnew) > 0) {

                        foreach ($shenrolledstudnew as $stud) {

                            $studentinfo = Db::table('sh_enrolledstud')
                                ->where('studid', $stud->id)
                                ->get();

                            if (count($studentinfo) == 1) {

                                array_push($students, $stud);

                            }

                        }

                    }

                } elseif ($request->get('studenttype') == 'old') {

                    $enrolledstudnew = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.gender',
                            'gradelevel.levelname'
                        )
                        ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                        ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                        ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                        ->where('enrolledstud.studstatus', '1')
                        ->where('sy.isactive', '1')
                        ->get();

                    if (count($enrolledstudnew) > 0) {

                        foreach ($enrolledstudnew as $stud) {

                            $studentinfo = Db::table('enrolledstud')
                                ->where('studid', $stud->id)
                                ->get();

                            if (count($studentinfo) > 1) {

                                array_push($students, $stud);

                            }

                        }

                    }

                    $shenrolledstudnew = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.gender',
                            'gradelevel.levelname'
                        )
                        ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                        ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                        ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                        ->where('sh_enrolledstud.studstatus', '1')
                        ->where('sy.isactive', '1')
                        ->get();

                    if (count($shenrolledstudnew) > 0) {

                        foreach ($shenrolledstudnew as $stud) {

                            $studentinfo = Db::table('sh_enrolledstud')
                                ->where('studid', $stud->id)
                                ->get();

                            if (count($studentinfo) > 1) {

                                array_push($students, $stud);

                            }

                        }

                    }

                } elseif ($request->get('studenttype') == 'transferee') {

                    $enrolledstudtransferee = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.gender',
                            'gradelevel.levelname'
                        )
                        ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                        ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                        ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                        ->where('enrolledstud.studstatus', '4')
                        ->where('sy.isactive', '1')
                        ->get();

                    if (count($enrolledstudtransferee) > 0) {

                        foreach ($enrolledstudtransferee as $stud) {

                            $studentinfo = Db::table('enrolledstud')
                                ->where('studid', $stud->id)
                                ->get();

                            if (count($studentinfo) == 1) {

                                array_push($students, $stud);

                            }

                        }

                    }

                    $shenrolledstudtransferee = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.gender',
                            'gradelevel.levelname'
                        )
                        ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                        ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                        ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                        ->where('sh_enrolledstud.studstatus', '4')
                        ->where('sy.isactive', '1')
                        ->get();

                    if (count($shenrolledstudtransferee) > 0) {

                        foreach ($shenrolledstudtransferee as $stud) {

                            $studentinfo = Db::table('sh_enrolledstud')
                                ->where('studid', $stud->id)
                                ->get();

                            if (count($studentinfo) > 1) {

                                array_push($students, $stud);

                            }

                        }

                    }

                } elseif ($request->get('studenttype') == 'all') {
                    $enrolledstuds = DB::table('studinfo')
                        ->select(
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.gender',
                            'gradelevel.levelname'
                        )
                        ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                        ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                        ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                        ->where('enrolledstud.studstatus', '1')
                        ->where('sy.isactive', '1')
                        ->get();

                    if (count($enrolledstuds) > 0) {

                        foreach ($enrolledstuds as $enrolledstud) {

                            array_push($students, $enrolledstud);

                        }

                    }

                    $shenrolledstuds = DB::table('studinfo')
                        ->select(
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.gender',
                            'gradelevel.levelname'
                        )
                        ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                        ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                        ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                        ->where('sh_enrolledstud.studstatus', '1')
                        ->where('sy.isactive', '1')
                        ->get();

                    if (count($shenrolledstuds) > 0) {

                        foreach ($shenrolledstuds as $shenrolledstud) {

                            array_push($students, $shenrolledstud);

                        }

                    }

                }

            } else {
                $selectedstudenttype = 'all';

                $enrolledstuds = DB::table('studinfo')
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname'
                    )
                    ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                    ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                    ->where('enrolledstud.studstatus', '1')
                    ->where('sy.isactive', '1')
                    ->get();

                if (count($enrolledstuds) > 0) {

                    foreach ($enrolledstuds as $enrolledstud) {

                        array_push($students, $enrolledstud);

                    }

                }

                $shenrolledstuds = DB::table('studinfo')
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname'
                    )
                    ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                    ->where('sh_enrolledstud.studstatus', '1')
                    ->where('sy.isactive', '1')
                    ->get();

                if (count($shenrolledstuds) > 0) {

                    foreach ($shenrolledstuds as $shenrolledstud) {

                        array_push($students, $shenrolledstud);

                    }

                }

            }

            return view('registrar.summaries.summariesallstudents')
                ->with('selectedstudenttype', $selectedstudenttype)
                ->with('tracks', $tracks)
                ->with('students', $students);

        }

    }

    public function reportssummariesallstudentswithtrack(Request $request)
    {
        $strands = DB::table('sh_strand')
            ->where('trackid', $request->get('trackid'))
            ->where('active', '1')
            ->where('deleted', '0')
            ->get();

        $students = array();

        // if($request->get('selectedstudenttype') == 'new'){

        $shenrolledstudnew = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.strandid',
                'gradelevel.levelname'
            )
            ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
            ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
            ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
            ->join('sh_strand', 'studinfo.strandid', '=', 'sh_strand.id')
            ->whereIn('sh_enrolledstud.studstatus', [1, 4])
            ->where('studinfo.strandid', '!=', null)
            ->where('sh_strand.active', '1')
            ->where('sh_strand.deleted', '0')
            ->where('sh_strand.trackid', $request->get('trackid'))
            ->where('sy.isactive', '1')
            ->get();

        if (count($shenrolledstudnew) > 0) {

            foreach ($shenrolledstudnew as $studnew) {

                // $trackinfo = Db::table('sh_strand')
                //     ->where('id', $studnew->strandid)
                //     ->where('trackid', $request->get('trackid'))
                //     ->where('trackid', $request->get('trackid'))
                //     ->get();

                // if(count($trackinfo) > 0){

                array_push($students, $studnew);

                // }

            }

        }

        $dataarray = array();

        array_push($dataarray, $students);
        array_push($dataarray, $strands);

        return $dataarray;

    }

    public function reportssummariesallstudentswithstrand(Request $request)
    {
        $students = array();

        $shenrolledstudnew = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.strandid',
                'gradelevel.levelname'
            )
            ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
            ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
            ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
            ->join('sh_strand', 'studinfo.strandid', '=', 'sh_strand.id')
            ->whereIn('sh_enrolledstud.studstatus', [1, 4])
            ->where('studinfo.strandid', $request->get('strandid'))
            ->where('sh_strand.active', '1')
            ->where('sh_strand.deleted', '0')
            ->where('sy.isactive', '1')
            ->get();
        if (count($shenrolledstudnew) > 0) {

            foreach ($shenrolledstudnew as $studnew) {

                // $trackinfo = Db::table('sh_strand')
                //     ->where('id', $studnew->strandid)
                //     ->where('trackid', $request->get('trackid'))
                //     ->where('trackid', $request->get('trackid'))
                //     ->get();

                // if(count($trackinfo) > 0){

                array_push($students, $studnew);

                // }

            }

        }

        return $students;

    }


    public function reportssummariesprint(Request $request)
    {

        // return $request->all();

        $selectedstudenttype = $request->get('selectedstudenttype');

        $trackid = $request->get('trackid');

        $strandid = $request->get('strandid');

        $students = array();

        if ($trackid == null) {


            if ($selectedstudenttype == 'new') {

                $enrolledstudnew = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname',
                        'gradelevel.sortid'
                    )
                    ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                    ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                    ->where('enrolledstud.studstatus', '1')
                    ->where('sy.isactive', '1')
                    ->orderBy('sortid', 'asc')
                    ->get();


                if (count($enrolledstudnew) > 0) {

                    foreach ($enrolledstudnew as $stud) {

                        $studentinfo = Db::table('enrolledstud')
                            ->where('studid', $stud->id)
                            ->get();

                        if (count($studentinfo) == 1) {

                            array_push($students, $stud);

                        }

                    }

                }

                $shenrolledstudnew = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname',
                        'gradelevel.sortid'
                    )
                    ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                    ->where('sh_enrolledstud.studstatus', '1')
                    ->where('sy.isactive', '1')
                    ->orderBy('sortid', 'asc')
                    ->get();

                if (count($shenrolledstudnew) > 0) {

                    foreach ($shenrolledstudnew as $stud) {

                        $studentinfo = Db::table('sh_enrolledstud')
                            ->where('studid', $stud->id)
                            ->get();

                        if (count($studentinfo) == 1) {

                            array_push($students, $stud);

                        }

                    }

                }

            } elseif ($selectedstudenttype == 'old') {

                $enrolledstudnew = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname',
                        'gradelevel.sortid'
                    )
                    ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                    ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                    ->where('enrolledstud.studstatus', '1')
                    ->where('sy.isactive', '1')
                    ->orderBy('sortid', 'asc')
                    ->get();

                if (count($enrolledstudnew) > 0) {

                    foreach ($enrolledstudnew as $stud) {

                        $studentinfo = Db::table('enrolledstud')
                            ->where('studid', $stud->id)
                            ->get();

                        if (count($studentinfo) > 1) {

                            array_push($students, $stud);

                        }

                    }

                }

                $shenrolledstudnew = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname',
                        'gradelevel.sortid'
                    )
                    ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                    ->where('sh_enrolledstud.studstatus', '1')
                    ->where('sy.isactive', '1')
                    ->orderBy('sortid', 'asc')
                    ->get();

                if (count($shenrolledstudnew) > 0) {

                    foreach ($shenrolledstudnew as $stud) {

                        $studentinfo = Db::table('sh_enrolledstud')
                            ->where('studid', $stud->id)
                            ->get();

                        if (count($studentinfo) > 1) {

                            array_push($students, $stud);

                        }

                    }

                }

            } elseif ($selectedstudenttype == 'transferee') {

                $enrolledstudtransferee = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname',
                        'gradelevel.sortid'
                    )
                    ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                    ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                    ->where('enrolledstud.studstatus', '4')
                    ->where('sy.isactive', '1')
                    ->orderBy('sortid', 'asc')
                    ->get();

                if (count($enrolledstudtransferee) > 0) {

                    foreach ($enrolledstudtransferee as $stud) {

                        $studentinfo = Db::table('enrolledstud')
                            ->where('studid', $stud->id)
                            ->get();

                        if (count($studentinfo) == 1) {

                            array_push($students, $stud);

                        }

                    }

                }

                $shenrolledstudtransferee = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname',
                        'gradelevel.sortid'
                    )
                    ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                    ->where('sh_enrolledstud.studstatus', '4')
                    ->where('sy.isactive', '1')
                    ->orderBy('sortid', 'asc')
                    ->get();

                if (count($shenrolledstudtransferee) > 0) {

                    foreach ($shenrolledstudtransferee as $stud) {

                        $studentinfo = Db::table('sh_enrolledstud')
                            ->where('studid', $stud->id)
                            ->get();

                        if (count($studentinfo) > 1) {

                            array_push($students, $stud);

                        }

                    }

                }

            } elseif ($selectedstudenttype == 'all') {
                $enrolledstuds = DB::table('studinfo')
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname',
                        'gradelevel.sortid'
                    )
                    ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                    ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                    ->where('enrolledstud.studstatus', '1')
                    ->where('sy.isactive', '1')
                    ->orderBy('sortid', 'asc')
                    ->get();

                if (count($enrolledstuds) > 0) {

                    foreach ($enrolledstuds as $enrolledstud) {

                        array_push($students, $enrolledstud);

                    }

                }

                $shenrolledstuds = DB::table('studinfo')
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname',
                        'gradelevel.sortid'
                    )
                    ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                    ->where('sh_enrolledstud.studstatus', '1')
                    ->where('sy.isactive', '1')
                    ->orderBy('sortid', 'asc')
                    ->get();

                if (count($shenrolledstuds) > 0) {

                    foreach ($shenrolledstuds as $shenrolledstud) {

                        array_push($students, $shenrolledstud);

                    }

                }

            }

        } elseif ($trackid != null) {


            if ($strandid == null) {


                $strands = DB::table('sh_strand')
                    ->where('trackid', $trackid)
                    ->where('active', '1')
                    ->where('deleted', '0')
                    ->get();

                $shenrolledstudnew = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.strandid',
                        'gradelevel.levelname',
                        'gradelevel.sortid'
                    )
                    ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                    ->join('sh_strand', 'studinfo.strandid', '=', 'sh_strand.id')
                    ->whereIn('sh_enrolledstud.studstatus', [1, 4])
                    ->where('studinfo.strandid', '!=', null)
                    ->where('sh_strand.active', '1')
                    ->where('sh_strand.deleted', '0')
                    ->where('sh_strand.trackid', $trackid)
                    ->where('sy.isactive', '1')
                    ->orderBy('sortid', 'asc')
                    ->get();

                if (count($shenrolledstudnew) > 0) {

                    foreach ($shenrolledstudnew as $studnew) {

                        // $trackinfo = Db::table('sh_strand')
                        //     ->where('id', $studnew->strandid)
                        //     ->where('trackid', $request->get('trackid'))
                        //     ->where('trackid', $request->get('trackid'))
                        //     ->get();

                        // if(count($trackinfo) > 0){

                        array_push($students, $studnew);

                        // }

                    }

                }

            } elseif ($strandid != null) {

                $shenrolledstudnew = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.strandid',
                        'gradelevel.levelname',
                        'gradelevel.sortid'
                    )
                    ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                    ->join('sh_strand', 'studinfo.strandid', '=', 'sh_strand.id')
                    ->whereIn('sh_enrolledstud.studstatus', [1, 4])
                    ->where('studinfo.strandid', $strandid)
                    ->where('sh_strand.active', '1')
                    ->where('sh_strand.deleted', '0')
                    ->where('sy.isactive', '1')
                    ->orderBy('sortid', 'asc')
                    ->get();

                if (count($shenrolledstudnew) > 0) {

                    foreach ($shenrolledstudnew as $studnew) {

                        // $trackinfo = Db::table('sh_strand')
                        //     ->where('id', $studnew->strandid)
                        //     ->where('trackid', $request->get('trackid'))
                        //     ->where('trackid', $request->get('trackid'))
                        //     ->get();

                        // if(count($trackinfo) > 0){

                        array_push($students, $studnew);

                        // }

                    }

                }

            }

        }

        $schoolinfo = Db::table('schoolinfo')
            ->get();

        if ($trackid != null) {

            $trackname = Db::table('sh_track')
                ->where('id', $trackid)
                ->get();

        } else {

            $trackname = "";

        }

        if ($strandid != null) {

            $strandname = Db::table('sh_strand')
                ->where('id', $strandid)
                ->get();

        } else {

            $strandname = "";

        }

        $sy = DB::table('sy')
            ->where('isactive', '1')
            ->get();
        // return collect($students)->sortby('');

        $pdf = PDF::loadview('registrar/pdf/pdf_summaryofallstudents', compact('students', 'selectedstudenttype', 'trackid', 'trackname', 'strandid', 'strandname', 'schoolinfo', 'sy'))->setPaper('a4');

        return $pdf->stream('Summary of Students.pdf');

    }
    public function studentlist(Request $request)
    {
        $colleges = DB::table('college_colleges')
            ->where('deleted', '0')
            ->get();

        try {
            $courses = DB::table('college_courses')
                ->where('deleted', '0')
                ->whereIn('collegeid', collect($colleges)->pluck('id'))
                ->orderBy('courseabrv', 'asc')
                ->get();
        } catch (\Exception $error) {
            $courses = DB::table('college_courses')
                ->where('deleted', '0')
                ->whereIn('collegeid', collect($colleges)->pluck('id'))
                ->orderBy('sortid')
                ->get();
        }

        if (!$request->has('action')) {

            return view('registrar.summaries.studentlist.index')
                ->with('colleges', $colleges)
                ->with('courses', $courses);
        } else {
            if ($request->get('action') == 'updatesignatory') {
                // return $request->all();
                $formid = $request->get('formid');
                $syid = $request->get('syid');
                $acadprogid = $request->get('acadprogid');
                if ($formid == 'es') {
                    $registrar = $request->get('registrar');
                    $president = $request->get('president');
                    $formname = 'Enrollment Summary';

                    if ($request->get('acadprogid') == 'all' || $request->get('acadprogid') == 'basiced') {
                        $acadprogid = 0;
                    } else {
                        $acadprogid = $request->get('acadprogid');
                    }
                    $checkifexists = DB::table('signatory')
                        ->where('form', $formname)
                        ->where('title', 'Registrar')
                        ->where('syid', $syid)
                        ->where('acadprogid', $acadprogid)
                        ->where('deleted', 0)
                        ->where('createdby', auth()->user()->id)
                        ->first();

                    if ($checkifexists) {
                        DB::table('signatory')
                            ->where('id', $checkifexists->id)
                            ->update([
                                'name' => $registrar,
                                'title' => 'Registrar',
                                'description' => '',
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => date('Y-m-d H:i:s')
                            ]);

                    } else {
                        $id = DB::table('signatory')
                            ->insertgetId([
                                'form' => $formname,
                                'name' => $registrar,
                                'title' => 'Registrar',
                                'description' => '',
                                'syid' => $syid,
                                'acadprogid' => $acadprogid,
                                'deleted' => 0,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);

                    }
                    $checkifexists = DB::table('signatory')
                        ->where('form', $formname)
                        ->where('title', 'President')
                        ->where('syid', $syid)
                        ->where('acadprogid', $acadprogid)
                        ->where('deleted', 0)
                        ->where('createdby', auth()->user()->id)
                        ->first();

                    if ($checkifexists) {
                        DB::table('signatory')
                            ->where('id', $checkifexists->id)
                            ->update([
                                'name' => $president,
                                'title' => 'President',
                                'description' => '',
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => date('Y-m-d H:i:s')
                            ]);

                    } else {
                        $id = DB::table('signatory')
                            ->insertgetId([
                                'form' => $formname,
                                'name' => $president,
                                'title' => 'President',
                                'description' => '',
                                'syid' => $syid,
                                'acadprogid' => $acadprogid,
                                'deleted' => 0,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);

                    }
                } else {
                    $levelid = $request->get('levelid');

                    $name = $request->get('reg_consultant');
                    $checkifexists = DB::table('signatory')
                        ->where('form', $formid)
                        ->where('title', 'Registrar Consultant')
                        ->where('syid', $syid)
                        ->where('acadprogid', $acadprogid)
                        ->where('deleted', 0)
                        ->where('createdby', auth()->user()->id)
                        ->first();

                    if ($checkifexists) {
                        DB::table('signatory')
                            ->where('id', $checkifexists->id)
                            ->update([
                                'name' => $name,
                                'title' => 'Registrar Consultant',
                                'description' => '',
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => date('Y-m-d H:i:s')
                            ]);

                    } else {
                        $id = DB::table('signatory')
                            ->insertgetId([
                                'form' => $formid,
                                'name' => $name,
                                'title' => 'Registrar Consulatant',
                                'description' => '',
                                'syid' => $syid,
                                'acadprogid' => $acadprogid,
                                'deleted' => 0,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);

                    }
                }
                return 1;
            } else {
                if ($request->get('type') == 'es') {
                    $schoolyears = DB::table('sy')->orderByDesc('sydesc')->where('sydesc', '<', DB::table('sy')->where('id', $request->get('syid'))->first()->sydesc)->select('id')->get();
                    $currentyear = (array_search($request->get('syid'), collect($schoolyears)->pluck('id')->toArray())) - 1;
                    $lastyear = array_search($currentyear, collect($schoolyears)->pluck('id')->toArray());
                    if ($lastyear) {
                        $lastyear = DB::table('sy')->orderByDesc('sydesc')->where('sydesc', '<', DB::table('sy')->where('id', $request->get('syid'))->first()->sydesc)->get()[$lastyear];
                    } else {
                        $lastyear = (object) array(
                            'id' => 0
                        );
                    }
                    $currentyear = DB::table('sy')->where('id', $request->get('syid'))->first();

                    $student_basiced = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.dob',
                            DB::raw('LOWER(`gender`) as gender'),
                            'sections.id as sectionid',
                            'sections.sectionname',
                            'gradelevel.id as levelid',
                            'gradelevel.sortid',
                            'gradelevel.levelname',
                            'gradelevel.acadprogid'
                        )
                        ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                        ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                        ->leftJoin('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                        ->where('enrolledstud.syid', $request->get('syid'))
                        ->whereIN('enrolledstud.studstatus', [1, 2, 4])
                        ->whereIN('gradelevel.acadprogid', [2, 3, 4, 5])
                        ->where('enrolledstud.deleted', '0')
                        ->distinct('studid')
                        // ->where('studinfo.deleted','0')
                        ->get();

                    if (count($student_basiced) > 0) {
                        foreach ($student_basiced as $studbasiced) {
                            $studbasiced->sortthis = $studbasiced->lastname . ', ' . $studbasiced->firstname . ' ' . (isset($studbasiced->middlename[0]) ? $studbasiced->middlename[0] . '. ' : ' ') . $studbasiced->suffix . ' ' . $studbasiced->sortid;
                            $studbasiced->courseid = 0;
                            $studbasiced->collegeid = 0;
                            $studbasiced->coursename = '';
                            $studbasiced->completecourse = '';
                            $studbasiced->coursesortid = $studbasiced->sortid;
                            if ($request->get('department') == 'all') {
                                $studbasiced->department = 'Basic Ed';
                            } elseif ($request->get('department') == 'basiced') {
                                $studbasiced->department = 'Basic Ed';
                            } else {
                                if ($studbasiced->acadprogid == 2) {
                                    $studbasiced->department = 'Pre-school';
                                }
                                if ($studbasiced->acadprogid == 3) {
                                    $studbasiced->department = 'Elementary';
                                }
                                if ($studbasiced->acadprogid == 4) {
                                    $studbasiced->department = 'High School';
                                }
                            }
                            if ($studbasiced->acadprogid == 2) {
                                $studbasiced->acadprogname = 'Pre-school';
                            }
                            if ($studbasiced->acadprogid == 3) {
                                $studbasiced->acadprogname = 'Elementary';
                            }
                            if ($studbasiced->acadprogid == 4) {
                                $studbasiced->acadprogname = 'High School';
                            }
                            if ($studbasiced->acadprogid == 5) {
                                $studbasiced->acadprogname = 'Senior High School';
                            }
                            if ($studbasiced->acadprogid == 6) {
                                $studbasiced->acadprogname = 'College';
                            }
                            $studbasiced->currentyear_sectionid = $studbasiced->sectionid;
                            $studbasiced_lastyear = DB::table('enrolledstud')
                                ->select(
                                    'enrolledstud.sectionid'
                                )
                                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                                ->where('enrolledstud.syid', $lastyear->id)
                                ->whereIN('enrolledstud.studstatus', [1, 2, 4])
                                ->whereIN('gradelevel.acadprogid', [2, 3, 4, 5])
                                ->where('enrolledstud.deleted', '0')
                                ->where('enrolledstud.studid', $studbasiced->id)
                                ->first();

                            if ($studbasiced_lastyear) {
                                $studbasiced->lastyear_sectionid = $studbasiced_lastyear->sectionid;
                            } else {
                                $studbasiced->lastyear_sectionid = null;
                            }
                            $studbasiced->ismwsp_currentyear = 0;
                            $studbasiced->ismwsp_lastyear = 0;
                        }
                    }

                    $student_shs = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.suffix',
                            'studinfo.dob',
                            DB::raw('LOWER(`gender`) as gender'),
                            'gradelevel.id as levelid',
                            'gradelevel.sortid',
                            'gradelevel.levelname',
                            'sections.id as sectionid',
                            'sections.sectionname',
                            'sh_track.trackname',
                            'sh_strand.strandname',
                            'sh_strand.strandcode',
                            'gradelevel.acadprogid'
                        )
                        ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                        ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                        ->leftJoin('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                        ->join('sh_strand', 'sh_enrolledstud.strandid', '=', 'sh_strand.id')
                        ->join('sh_track', 'sh_strand.trackid', '=', 'sh_track.id')
                        ->where('sh_enrolledstud.syid', $request->get('syid'))
                        ->where('sh_enrolledstud.semid', $request->get('semid'))
                        ->whereIN('sh_enrolledstud.studstatus', [1, 2, 4])
                        ->where('sh_enrolledstud.deleted', '0')
                        ->where('sh_track.deleted', '0')
                        // ->where('studinfo.deleted','0')
                        ->get();

                    if (count($student_shs) > 0) {
                        foreach ($student_shs as $studshs) {
                            $studshs->courseid = 0;
                            $studshs->collegeid = 0;
                            $studshs->coursename = $studshs->trackname . ' - ' . $studshs->strandcode;
                            $studshs->completecourse = $studshs->trackname . ' - ' . $studshs->strandcode;
                            $studshs->coursesortid = $studshs->sortid . ' ' . $studshs->trackname . ' - ' . $studshs->strandcode;
                            $studshs->sortthis = $studshs->lastname . ', ' . $studshs->firstname . ' ' . (isset($studshs->middlename[0]) ? $studshs->middlename[0] . '. ' : ' ') . $studshs->suffix . ' ' . $studshs->sortid . ' ' . $studshs->trackname . ' - ' . $studshs->strandcode;
                            if ($request->get('department') == 'all') {
                                $studshs->department = 'Basic Ed';
                            } elseif ($request->get('department') == 'basiced') {
                                $studshs->department = 'Basic Ed';
                            } else {
                                if ($studshs->acadprogid == 5) {
                                    $studshs->department = 'Senior High School';
                                }
                            }
                            $studshs->sort1 = $studshs->levelname . ' ' . $studshs->strandcode;
                            $studshs->currentyear_sectionid = $studshs->sectionid;
                            $studshs_lastyear = DB::table('sh_enrolledstud')
                                ->select(
                                    'sh_enrolledstud.sectionid'
                                )
                                ->where('sh_enrolledstud.syid', $lastyear->id)
                                ->where('sh_enrolledstud.semid', $request->get('semid'))
                                ->whereIN('sh_enrolledstud.studstatus', [1, 2, 4])
                                ->where('sh_enrolledstud.deleted', '0')
                                ->where('sh_enrolledstud.studid', $studshs->id)
                                ->first();

                            if ($studshs_lastyear) {
                                $studshs->lastyear_sectionid = $studshs_lastyear->sectionid;
                            } else {
                                $studshs->lastyear_sectionid = null;
                            }
                            $studshs->ismwsp_currentyear = 0;
                            $studshs->ismwsp_lastyear = 0;
                        }
                    }


                    try {

                        $student_college = DB::table('studinfo')
                            ->select(
                                'studinfo.id',
                                'studinfo.sid',
                                'studinfo.lastname',
                                'studinfo.firstname',
                                'studinfo.middlename',
                                'studinfo.suffix',
                                'studinfo.dob',
                                DB::raw('LOWER(`gender`) as gender'),
                                'gradelevel.id as levelid',
                                'nationality.nationality',
                                'gradelevel.sortid',
                                'gradelevel.levelname',
                                'college_sections.id as sectionid',
                                'college_sections.sectionDesc as sectionname',
                                'college_year.id as yearid',
                                'college_courses.id as courseid',
                                'college_colleges.id as collegeid',
                                'college_courses.courseabrv as coursecode',
                                'college_courses.sortid as coursesortid',
                                'college_courses.courseDesc as coursename',
                                'gradelevel.acadprogid'
                            )
                            ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
                            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                            ->leftJoin('college_sections', 'college_enrolledstud.sectionID', '=', 'college_sections.id')
                            ->leftJoin('nationality', 'studinfo.nationality', '=', 'nationality.id')
                            ->join('college_year', 'college_enrolledstud.yearLevel', '=', 'college_year.levelid')
                            ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                            ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
                            ->where('college_enrolledstud.syid', $request->get('syid'))
                            ->where('college_enrolledstud.semid', $request->get('semid'))
                            ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                            ->whereIn('college_enrolledstud.courseid', collect($courses)->pluck('id'))
                            ->where('college_enrolledstud.deleted', '0')
                            ->where('college_courses.deleted', '0')
                            ->where('college_colleges.deleted', '0')
                            // ->where('studinfo.deleted','0')
                            ->get();
                    } catch (\Exception $error) {

                        $student_college = DB::table('studinfo')
                            ->select(
                                'studinfo.id',
                                'studinfo.sid',
                                'studinfo.lastname',
                                'studinfo.firstname',
                                'studinfo.middlename',
                                'studinfo.suffix',
                                'studinfo.dob',
                                DB::raw('LOWER(`gender`) as gender'),
                                'gradelevel.id as levelid',
                                'nationality.nationality',
                                'gradelevel.sortid',
                                'gradelevel.levelname',
                                'college_sections.id as sectionid',
                                'college_sections.sectionDesc as sectionname',
                                'college_year.id as yearid',
                                'college_courses.id as courseid',
                                'college_colleges.id as collegeid',
                                'college_courses.courseabrv as coursecode',
                                'college_courses.courseDesc as coursename',
                                'gradelevel.acadprogid'
                            )
                            ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
                            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                            ->leftJoin('college_sections', 'college_enrolledstud.sectionID', '=', 'college_sections.id')
                            ->leftJoin('nationality', 'studinfo.nationality', '=', 'nationality.id')
                            ->join('college_year', 'college_enrolledstud.yearLevel', '=', 'college_year.levelid')
                            ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                            ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
                            ->where('college_enrolledstud.syid', $request->get('syid'))
                            ->where('college_enrolledstud.semid', $request->get('semid'))
                            ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                            ->whereIn('college_enrolledstud.courseid', collect($courses)->pluck('id'))
                            ->where('college_enrolledstud.deleted', '0')
                            ->where('college_courses.deleted', '0')
                            ->where('college_colleges.deleted', '0')
                            // ->where('studinfo.deleted','0')
                            ->get();
                    }
                    if (count($student_college) > 0) {
                        foreach ($student_college as $studcollege) {
                            $studcollege->completecourse = $studcollege->coursename;
                            $studcollege->major = " ";
                            $majorin = explode("major in ", strtolower($studcollege->coursename));
                            if (count($majorin) > 1) {
                                $studcollege->coursename = strtoupper($majorin[0]);
                                $studcollege->major = strtoupper($majorin[1]);
                            }
                            $studcollege->department = 'College';
                            $studcollege->sortthis = '';

                            $studcollege->sortthis .= $studcollege->lastname . ', ' . $studcollege->firstname . ' ';
                            $studcollege->sortthis .= $studcollege->middlename != null ? $studcollege->middlename[0] . ' ' : '';
                            $studcollege->sortthis .= $studcollege->suffix . ' ' . $studcollege->sortid . ' ' . $studcollege->department . ' - ' . $studcollege->coursename;
                            $studcollege->currentyear_sectionid = $studcollege->sectionid;
                            $studcollege_lastyear = DB::table('college_enrolledstud')
                                ->select(
                                    'college_enrolledstud.sectionid'
                                )
                                ->where('college_enrolledstud.syid', $lastyear->id)
                                ->where('college_enrolledstud.semid', $request->get('semid'))
                                ->whereIN('college_enrolledstud.studstatus', [1, 2, 4])
                                ->where('college_enrolledstud.deleted', '0')
                                ->where('college_enrolledstud.studid', $studcollege->id)
                                ->first();

                            if ($studcollege_lastyear) {
                                $studcollege->lastyear_sectionid = $studcollege_lastyear->sectionid;
                            } else {
                                $studcollege->lastyear_sectionid = null;
                            }
                            $studcollege->ismwsp_currentyear = 0;
                            $studcollege->ismwsp_lastyear = 0;
                        }
                    }


                    $students = collect();
                    $students = $students->merge($student_basiced);
                    $students = $students->merge($student_shs);
                    $students = $students->merge($student_college);

                    if ($request->get('department') == 'basiced') {
                        $students = $students->where('department', 'Basic Ed');

                    } elseif ($request->get('department') == 'college') {
                        $students = $students->where('department', 'College');
                    } else {
                        if ($request->get('department') == 2) {
                            $students = $students->where('department', 'Pre-school');
                        }
                        if ($request->get('department') == 3) {
                            $students = $students->where('department', 'Elementary');
                        }
                        if ($request->get('department') == 4) {
                            $students = $students->where('department', 'High School');
                        }
                        if ($request->get('department') == 5) {
                            $students = $students->where('department', 'Senior High School');
                        }
                        if ($request->get('department') == 6) {
                            $students = $students->where('acadprogid', 6);
                            // $students = $students->where('department','College');
                            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc') {
                                if ($request->get('collegeid') > 0) {
                                    $students = $students->where('collegeid', $request->get('collegeid'));
                                }
                                if ($request->get('courseid') > 0) {
                                    $students = $students->where('courseid', $request->get('courseid'));
                                }

                            }
                        }

                    }

                    if ($request->get('department') == 'all' || $request->get('department') == 'basiced') {
                        $acadprogid = 0;
                    } else {
                        $acadprogid = $request->get('department');
                    }

                    $signatories = DB::table('signatory')
                        ->where('form', 'Enrollment Summary')
                        ->where('syid', $request->get('syid'))
                        ->where('acadprogid', $acadprogid)
                        ->where('deleted', 0)
                        ->where('createdby', auth()->user()->id)
                        ->get();

                    $gradelevels = DB::table('gradelevel')
                        ->where('deleted', '0')
                        ->orderBy('sortid', 'asc')
                        ->get();

                    if ($request->get('action') == 'filter') {
                        $department = $request->get('department');
                        // return $department;
                        // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                        // {
                        $students = collect($students)->sortByDesc('sortthis')->sortByDesc('levelname')->values()->all();
                        // }
                        $students = collect($students)->groupBy('department');
                        // return $students;
                        // return $request->get('department');
                        return view('registrar.summaries.studentlist.es_table')
                            ->with('signatories', $signatories)
                            ->with('department', $department)
                            ->with('students', $students);
                    } else {
                        $semester = DB::table('semester')
                            ->where('id', $request->get('semid'))
                            ->first()->semester;
                        $sydesc = DB::table('sy')
                            ->where('id', $request->get('syid'))
                            ->first()->sydesc;
                        if ($request->get('action') == 'exportpdf') {
                            $syid = $request->get('syid');
                            $semid = $request->get('semid');
                            if ($request->get('format') == 'enrollmentsum') {
                                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                                    $students = collect($students)->values()->all();

                                    $user_fullname = DB::table('teacher')->where('userid', auth()->user()->id)->first();
                                    $user_fullname = ($user_fullname->title != null ? $user_fullname->title . ' ' : '') . $user_fullname->firstname . ' ' . ($user_fullname->middlename != null ? $user_fullname->middlename[0] . '. ' : '') . $user_fullname->lastname . ($user_fullname->suffix != null ? ' ' . $user_fullname->suffix : '');


                                    $mwsp_currentyear_sections = DB::table('sectiondetail')
                                        ->where('syid', $syid)
                                        ->where('deleted', '0')
                                        ->where('sd_ismwsp', '1')
                                        ->select('sectionid')
                                        ->get();

                                    $mwsp_currentyear_sections = collect($mwsp_currentyear_sections)->pluck('sectionid')->toArray();

                                    $mwsp_lastyear_sections = DB::table('sectiondetail')
                                        ->where('syid', $lastyear->id)
                                        ->where('deleted', '0')
                                        ->where('sd_ismwsp', '1')
                                        ->select('sectionid')
                                        ->get();

                                    $mwsp_lastyear_sections = collect($mwsp_lastyear_sections)->pluck('sectionid')->toArray();

                                    if (count($students) > 0) {
                                        foreach ($students as $eachstudent) {
                                            if (in_array($eachstudent->sectionid, $mwsp_currentyear_sections)) {
                                                $eachstudent->ismwsp_currentyear = 1;
                                            }
                                            if (in_array($eachstudent->lastyear_sectionid, $mwsp_lastyear_sections)) {
                                                $eachstudent->ismwsp_lastyear = 1;
                                            }
                                        }
                                    }
                                    // return collect($students)->where('ismwsp_currentyear','1')->count();
                                    $pdf = PDF::loadview('registrar.summaries.studentlist.pdf_enrollmentsummary_dcc', compact('students', 'semester', 'sydesc', 'colleges', 'courses', 'syid', 'semid', 'acadprogid', 'user_fullname'));
                                } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hsal') {
                                    $department = $request->get('department');
                                    foreach ($gradelevels as $gradelevel) {
                                        $gradelevel->sections = collect($students)->where('levelid', $gradelevel->id)->whereNotNull('sectionname')->sortBy('sectionname')->values()->all();
                                        $gradelevel->sections = collect($gradelevel->sections)->groupBy('sectionname');
                                        $gradelevel->sectioncount = count($gradelevel->sections);
                                        if ($department == 'basiced') {
                                            $gradelevel->acadprogid = $gradelevel->acadprogid == 2 ? 3 : $gradelevel->acadprogid;
                                        } elseif ($department == 0) {
                                            $gradelevel->acadprogid = $gradelevel->acadprogid == 2 ? 3 : $gradelevel->acadprogid;
                                        }
                                        // $gradelevel->acadprogid = $gradelevel->acadprogid == 2 ? 3 : $gradelevel->acadprogid;
                                    }
                                    if ($department == 'basiced') {
                                        $gradelevels = collect($gradelevels)->where('acadprogid', '<=', 5)->values();
                                    } elseif ($department > 0) {
                                        $gradelevels = collect($gradelevels)->where('acadprogid', $department)->values();
                                    }
                                    $acadprogs = collect($gradelevels)->groupBy('acadprogid');

                                    $pdf = PDF::loadview('registrar.summaries.studentlist.pdf_enrollmentsummary_hsa', compact('gradelevels', 'students', 'semester', 'sydesc', 'colleges', 'courses', 'syid', 'semid', 'acadprogid', 'department', 'acadprogs'));
                                } else {
                                    // return $request->get('department');
                                    if ($request->get('department') == 6) {
                                        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc') {
                                            $students = collect($students)->sortBy('coursesortid')->values()->all();
                                        } else {
                                            $students = collect($students)->values()->all();
                                            $students = collect($students)->sortBy('coursecode')->values()->all();
                                        }

                                        // return $courses;
                                        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic') {
                                            $courses = collect($students)->groupBy('coursename');
                                            $students = collect($students)->values()->all();
                                            foreach ($colleges as $college) {
                                                $getcourses = DB::table('college_courses')
                                                    ->where('collegeid', $college->id)
                                                    ->where('deleted', '0')
                                                    ->get();

                                                $collectcourses = array();

                                                if (count($getcourses) > 0) {
                                                    foreach ($getcourses as $getcourse) {
                                                        $countcutoff = 4;
                                                        if (strpos(strtolower($getcourse->courseDesc), 'engineering') !== false) {
                                                            $countcutoff = 5;
                                                        }
                                                        for ($x = 0; $x < $countcutoff; $x++) {
                                                            $courseandyear = $getcourse->courseabrv . '-' . ($x + 1);
                                                            array_push($collectcourses, (object) array(
                                                                'id' => $getcourse->id,
                                                                'collegeid' => $getcourse->collegeid,
                                                                'courseDesc' => $getcourse->courseDesc,
                                                                'courseabrv' => $getcourse->courseabrv,
                                                                'courseandyear' => $courseandyear,
                                                                'malecount' => collect($students)->where('yearid', ($x + 1))->where('courseid', $getcourse->id)->where('gender', 'male')->count(),
                                                                'femalecount' => collect($students)->where('yearid', ($x + 1))->where('courseid', $getcourse->id)->where('gender', 'female')->count(),
                                                                'studentcount' => collect($students)->where('yearid', ($x + 1))->where('courseid', $getcourse->id)->count(),
                                                                'yearid' => ($x + 1)
                                                            ));
                                                        }
                                                    }
                                                }
                                                $college->courses = $collectcourses;
                                            }

                                            $pdf = PDF::loadview('registrar.summaries.studentlist.pdf_enrollmentsummary_sic', compact('students', 'semester', 'sydesc', 'colleges', 'courses', 'syid', 'semid', 'acadprogid', 'signatories'));
                                        } else {
                                            if (count($courses) > 0) {
                                                foreach ($courses as $course) {
                                                    $course->students = collect($students)->where('courseid', $course->id)->values();
                                                }
                                            }

                                            $pdf = PDF::loadview('registrar.summaries.studentlist.pdf_enrollmentsummary_default', compact('students', 'semester', 'sydesc', 'colleges', 'courses', 'syid', 'semid', 'acadprogid', 'signatories'));
                                        }
                                    } else {
                                        // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                                        // {
                                        $students = collect($students)->sortByDesc('sortthis')->values()->all();
                                        // }
                                        $students = collect($students)->groupBy('department');
                                        $pdf = PDF::loadview('registrar.summaries.studentlist.pdf_enrollmentsummary', compact('students', 'semester', 'sydesc', 'syid', 'acadprogid'));
                                    }
                                }
                                return $pdf->stream('Enrollment Summary.pdf');
                            } elseif ($request->get('format') == 'enrollmenttable') {
                                $students = $students->where('acadprogid', 6);
                                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc') {
                                    $students = collect($students)->sortBy('coursesortid')->values()->all();
                                }
                                $students = collect($students)->groupBy('department');
                                $pdf = PDF::loadview('registrar.summaries.studentlist.pdf_enrollmentsummary', compact('students', 'semester', 'sydesc', 'syid', 'acadprogid'));
                                return $pdf->stream('Enrollment Summary.pdf');
                            } else {

                                $students = collect($students)->values()->all();
                                $students = collect($students)->sortBy('coursecode')->values()->all();

                                $courses = collect($students)->groupBy('coursecode');

                                $pdf = PDF::loadview('registrar.summaries.studentlist.pdf_enrollmentstatistics', compact('students', 'semester', 'sydesc', 'syid', 'acadprogid', 'courses'));
                                return $pdf->stream('Enrollment Statistics.pdf');
                            }
                        } else {


                            $students = collect($students)->values()->all();
                            $syid = $request->get('syid');
                            $semid = $request->get('semid');

                            function getNameFromNumber($num)
                            {
                                $numeric = ($num - 1) % 26;
                                $letter = chr(65 + $numeric);
                                $num2 = intval(($num - 1) / 26);
                                if ($num2 > 0) {
                                    return getNameFromNumber($num2) . $letter;
                                } else {
                                    return $letter;
                                }
                            }


                            $inputFileType = 'Xlsx';

                            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic') {


                                $inputFileName = base_path() . '/public/excelformats/sic/promotionalreport.xlsx';
                                // $sheetname = 'Front';

                                /**  Create a new Reader of the type defined in $inputFileType  **/
                                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                                /**  Advise the Reader of which WorkSheets we want to load  **/
                                $reader->setLoadAllSheets();
                                /**  Load $inputFileName to a Spreadsheet Object  **/
                                $spreadsheet = $reader->load($inputFileName);
                                $sheet = $spreadsheet->getSheet(0);

                                $borderstyle = [
                                    // 'alignment' => [
                                    //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                                    // ],
                                    'borders' => [
                                        'allBorders' => [
                                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                                        ]
                                    ]
                                ];


                                $sheet->setCellValue('A2', 'SY ' . $sydesc);
                                $sheet->setCellValue('A3', $semester);
                                $sheet->setCellValue('B5', DB::table('schoolinfo')->first()->schoolid);
                                $sheet->setCellValue('B6', DB::table('schoolinfo')->first()->schoolname);
                                $sheet->setCellValue('B7', DB::table('schoolinfo')->first()->address);

                                $sheet->setCellValue('L5', 'TOTAL NO. OF STUDENTS: ___' . count($students) . '___');

                                if (count($students) > 0) {
                                    foreach ($students as $student) {
                                        $student->sortname = $student->lastname . ' ' . $student->firstname;
                                        $student->sortcourseandlevel = $student->coursename . ' ' . $student->sortid . ' ' . $student->sortname;
                                        $subjects = DB::table('college_studsched')
                                            ->join('college_classsched', function ($join) use ($syid, $semid) {
                                                $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                                                $join->where('college_classsched.deleted', 0);
                                                $join->where('syID', $syid);
                                                $join->where('semesterID', $semid);
                                            })
                                            ->join('college_prospectus', function ($join) use ($syid, $semid) {
                                                $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                                $join->where('college_prospectus.deleted', 0);
                                            })
                                            ->leftJoin('college_studentprospectus', function ($join) use ($syid, $semid, $student) {
                                                $join->on('college_prospectus.id', '=', 'college_studentprospectus.prospectusID');
                                                $join->where('college_studentprospectus.deleted', 0);
                                                $join->where('college_studentprospectus.syid', $syid);
                                                $join->where('college_studentprospectus.semid', $semid);
                                                $join->where('college_studentprospectus.studid', $student->id);
                                            })
                                            ->where('schedstatus', '!=', 'DROPPED')
                                            ->where('college_studsched.deleted', 0)
                                            ->where('college_studsched.studid', $student->id)
                                            ->select('subjCode as subjectcode', 'subjDesc as subjectname', 'labunits', 'lecunits', 'fg as subjgrade', 'college_prospectus.psubjsort as subjsort')
                                            ->orderBy('subjsort')
                                            ->get();

                                        $subjects = collect($subjects)->unique();
                                        if (count($subjects) > 0) {
                                            foreach ($subjects as $grade) {
                                                $grade->subjcredit = 0;
                                                $grade->units = ($grade->lecunits + $grade->labunits);
                                            }
                                        }
                                        $student->subjects = $subjects;
                                    }
                                    $students = collect($students)->values()->all();
                                    $coursestartcellno = 17;
                                    $courses = collect($students)->groupBy('coursename');
                                    if (count($courses) > 0) {
                                        foreach ($courses as $key => $course) {
                                            $sheet->setCellValue('B' . $coursestartcellno, $key);
                                            $sheet->setCellValue('F' . $coursestartcellno, count($course));
                                            $sheet->getStyle('F' . $coursestartcellno)
                                                ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                            $coursestartcellno += 1;
                                        }
                                    }

                                    $startcellno = 13;
                                    // $students = collect($students)->sortBy('sortcourseandlevel')->all();
                                    $students = collect($students)->values()->all();

                                    $courses = collect($students)->groupBy('coursename');
                                    // return $courses;
                                    if (count($courses) > 0) {
                                        foreach ($courses as $coursename => $course) {
                                            $sheet->getStyle('A' . $startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('A' . $startcellno, $coursename);
                                            $sheet->getStyle('A' . $startcellno)->getAlignment()->setWrapText(true);
                                            $sheet->getRowDimension($startcellno)->setRowHeight(25);

                                            // $students = collect($course)->sortBy('sortname')->all();
                                            $course = collect($course)->sortBy('sortcourseandlevel');
                                            if (count($course) > 0) {
                                                foreach ($course as $student) {
                                                    $sheet->getStyle('B' . $startcellno)->getAlignment()->setVertical('center');
                                                    $sheet->setCellValue('B' . $startcellno, $student->major);
                                                    $sheet->getStyle('B' . $startcellno)->getAlignment()->setWrapText(true);
                                                    $student->yearlevel = 0;
                                                    if ($student->levelid == 17) {
                                                        $student->yearlevel = 1;
                                                    }
                                                    if ($student->levelid == 18) {
                                                        $student->yearlevel = 2;
                                                    }
                                                    if ($student->levelid == 19) {
                                                        $student->yearlevel = 3;
                                                    }
                                                    if ($student->levelid == 20) {
                                                        $student->yearlevel = 4;
                                                    }
                                                    if ($student->levelid == 21) {
                                                        $student->yearlevel = 5;
                                                    }
                                                    $sheet->setCellValue('C' . $startcellno, $student->sid);
                                                    $sheet->setCellValue('D' . $startcellno, $student->yearlevel);
                                                    if ($student->lastname != null) {
                                                        $sheet->setCellValue('E' . $startcellno, $student->lastname);
                                                    }
                                                    if ($student->firstname != null) {
                                                        $sheet->setCellValue('F' . $startcellno, $student->firstname);
                                                    }
                                                    if ($student->middlename != null) {
                                                        $sheet->setCellValue('G' . $startcellno, $student->middlename);
                                                    }
                                                    if ($student->suffix != null) {
                                                        $sheet->setCellValue('H' . $startcellno, $student->suffix);
                                                    }
                                                    if ($student->gender != null) {
                                                        $sheet->setCellValue('I' . $startcellno, ucwords(strtolower($student->gender)));
                                                    }
                                                    if ($student->dob != null) {
                                                        $sheet->setCellValue('J' . $startcellno, $student->nationality);
                                                    }
                                                    if (count($student->subjects) > 0) {
                                                        foreach ($student->subjects as $eachsubject) {
                                                            if (strlen($eachsubject->subjectname) > 58) {
                                                                $sheet->getRowDimension($startcellno)->setRowHeight(45);
                                                                $sheet->getStyle('L' . $startcellno)->getAlignment()->setWrapText(true);
                                                            }
                                                            $sheet->setCellValue('K' . $startcellno, $eachsubject->subjectcode);
                                                            $sheet->getStyle('L' . $startcellno)->getAlignment()->setVertical('center');
                                                            $sheet->setCellValue('L' . $startcellno, $eachsubject->subjectname);
                                                            $sheet->setCellValue('M' . $startcellno, $eachsubject->units);
                                                            $sheet->setCellValue('N' . $startcellno, $eachsubject->subjgrade);
                                                            $sheet->getStyle('N' . $startcellno)->getNumberFormat()->setFormatCode('#,##0.0');
                                                            if ($eachsubject->subjgrade != null) {
                                                                $sheet->setCellValue('O' . $startcellno, $eachsubject->subjgrade < 5.0 ? 'PASSED' : 'FAILED');
                                                            }
                                                            $startcellno += 1;
                                                            $sheet->insertNewRowBefore($startcellno + 1);
                                                        }
                                                    }
                                                    $startcellno += 1;
                                                    $sheet->insertNewRowBefore($startcellno + 1);
                                                }
                                            }
                                        }
                                    }
                                }
                                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                                header('Content-Type: application/vnd.ms-excel');
                                header('Content-Disposition: attachment; filename="Enrollment List S.Y ' . $sydesc . ' ' . $semester . '.xlsx"');
                                $writer->save("php://output");
                                exit;
                            } else
                                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spcmoi') {

                                    $inputFileName = base_path() . '/public/excelformats/pcc/promotionalreport.xlsx';
                                    // $sheetname = 'Front';

                                    /**  Create a new Reader of the type defined in $inputFileType  **/
                                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                                    /**  Advise the Reader of which WorkSheets we want to load  **/
                                    $reader->setLoadAllSheets();
                                    /**  Load $inputFileName to a Spreadsheet Object  **/
                                    $spreadsheet = $reader->load($inputFileName);
                                    $sheet = $spreadsheet->getSheet(0);

                                    $borderstyle = [
                                        'borders' => [
                                            'allBorders' => [
                                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                                            ]
                                        ]
                                    ];


                                    $sheet->setCellValue('A2', 'SY ' . $sydesc);
                                    $sheet->setCellValue('A3', $semester);
                                    $sheet->setCellValue('B5', '10067');
                                    $sheet->setCellValue('B6', DB::table('schoolinfo')->first()->schoolname);
                                    $sheet->setCellValue('B7', DB::table('schoolinfo')->first()->address);

                                    $sheet->setCellValue('M5', count($students));

                                    if (count($students) > 0) {
                                        foreach ($students as $student) {
                                            $student->sortname = $student->lastname . ' ' . $student->firstname;
                                            $student->sortcourseandlevel = $student->coursename . ' ' . $student->sortid . ' ' . $student->sortname;
                                            $subjects = DB::table('college_studsched')
                                                ->join('college_classsched', function ($join) use ($syid, $semid) {
                                                    $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                                                    $join->where('college_classsched.deleted', 0);
                                                    $join->where('syID', $syid);
                                                    $join->where('semesterID', $semid);
                                                })
                                                ->join('college_prospectus', function ($join) use ($syid, $semid) {
                                                    $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                                    $join->where('college_prospectus.deleted', 0);
                                                })
                                                ->leftJoin('college_studentprospectus', function ($join) use ($syid, $semid, $student) {
                                                    $join->on('college_prospectus.id', '=', 'college_studentprospectus.prospectusID');
                                                    $join->where('college_studentprospectus.deleted', 0);
                                                    $join->where('college_studentprospectus.syid', $syid);
                                                    $join->where('college_studentprospectus.semid', $semid);
                                                    $join->where('college_studentprospectus.studid', $student->id);
                                                })
                                                ->where('schedstatus', '!=', 'DROPPED')
                                                ->where('college_studsched.deleted', 0)
                                                ->where('college_studsched.studid', $student->id)
                                                ->select('subjCode as subjectcode', 'subjDesc as subjectname', 'labunits', 'lecunits', 'fg as subjgrade', 'college_prospectus.psubjsort as subjsort')
                                                ->orderBy('subjsort')
                                                ->get();

                                            $subjects = collect($subjects)->unique();
                                            if (count($subjects) > 0) {
                                                foreach ($subjects as $grade) {
                                                    $grade->subjcredit = 0;
                                                    $grade->units = ($grade->lecunits + $grade->labunits);
                                                }
                                            }
                                            $student->subjects = $subjects;



                                        }
                                        $students = collect($students)->values()->all();
                                        $coursestartcellno = 18;
                                        $courses = collect($students)->groupBy('coursename');
                                        if (count($courses) > 0) {
                                            foreach ($courses as $key => $course) {
                                                $sheet->setCellValue('B' . $coursestartcellno, $key);
                                                $sheet->setCellValue('F' . $coursestartcellno, count($course));
                                                $sheet->getStyle('F' . $coursestartcellno)
                                                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                                $coursestartcellno += 1;
                                            }
                                        }

                                        // return $courses;
                                        $startcellno = 13;

                                        if (count($courses) > 0) {
                                            foreach ($courses as $coursename => $course) {
                                                $sheet->getStyle('A' . $startcellno)->getAlignment()->setVertical('center');
                                                $sheet->setCellValue('A' . $startcellno, $coursename);
                                                $sheet->getStyle('A' . $startcellno)->getAlignment()->setWrapText(true);
                                                // $students = collect($course)->sortBy('sortname')->all();
                                                $course = collect($course)->sortBy('sortcourseandlevel');
                                                if (count($course) > 0) {
                                                    foreach ($course as $student) {
                                                        $sheet->getStyle('B' . $startcellno)->getAlignment()->setVertical('center');
                                                        $sheet->setCellValue('B' . $startcellno, $student->major);
                                                        $sheet->getStyle('B' . $startcellno)->getAlignment()->setWrapText(true);
                                                        $student->yearlevel = 0;
                                                        if ($student->levelid == 17) {
                                                            $student->yearlevel = 1;
                                                        }
                                                        if ($student->levelid == 18) {
                                                            $student->yearlevel = 2;
                                                        }
                                                        if ($student->levelid == 19) {
                                                            $student->yearlevel = 3;
                                                        }
                                                        if ($student->levelid == 20) {
                                                            $student->yearlevel = 4;
                                                        }
                                                        if ($student->levelid == 21) {
                                                            $student->yearlevel = 5;
                                                        }
                                                        $sheet->setCellValue('C' . $startcellno, $student->sid);
                                                        $sheet->setCellValue('D' . $startcellno, $student->yearlevel);
                                                        if ($student->lastname != null) {
                                                            $sheet->setCellValue('E' . $startcellno, $student->lastname);
                                                        }
                                                        if ($student->firstname != null) {
                                                            $sheet->setCellValue('F' . $startcellno, $student->firstname);
                                                        }
                                                        if ($student->middlename != null) {
                                                            $sheet->setCellValue('G' . $startcellno, $student->middlename);
                                                        }
                                                        if ($student->suffix != null) {
                                                            $sheet->setCellValue('H' . $startcellno, $student->suffix);
                                                        }
                                                        if ($student->gender != null) {
                                                            $sheet->setCellValue('I' . $startcellno, ucwords(strtolower($student->gender)));
                                                        }
                                                        if ($student->dob != null) {
                                                            $sheet->setCellValue('J' . $startcellno, $student->nationality);
                                                        }
                                                        if (count($student->subjects) > 0) {
                                                            foreach ($student->subjects as $eachsubject) {
                                                                $sheet->setCellValue('K' . $startcellno, $eachsubject->subjectcode);
                                                                $sheet->getStyle('L' . $startcellno)->getAlignment()->setVertical('center');
                                                                $sheet->setCellValue('L' . $startcellno, $eachsubject->subjectname);
                                                                $sheet->getStyle('L' . $startcellno)->getAlignment()->setWrapText(true);
                                                                $sheet->setCellValue('M' . $startcellno, $eachsubject->units);
                                                                $sheet->setCellValue('N' . $startcellno, $eachsubject->subjgrade);
                                                                if ($eachsubject->subjgrade != null) {
                                                                    $sheet->setCellValue('O' . $startcellno, $eachsubject->subjgrade < 5.0 ? 'PASSED' : 'FAILED');
                                                                }
                                                                $startcellno += 1;
                                                                $sheet->insertNewRowBefore($startcellno + 1);
                                                            }
                                                        }
                                                        $startcellno += 1;
                                                        $sheet->insertNewRowBefore($startcellno + 1);
                                                    }
                                                }
                                            }
                                        }

                                    }
                                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                                    header('Content-Type: application/vnd.ms-excel');
                                    header('Content-Disposition: attachment; filename="Enrollment List S.Y ' . $sydesc . ' ' . $semester . '.xlsx"');
                                    $writer->save("php://output");
                                    exit;
                                } else {

                                    if ($request->get('reporttype') == 'summary') {
                                        $inputFileName = base_path() . '/public/excelformats/dcc/enrollmentlist_ched.xlsx';
                                        // $sheetname = 'Front';

                                        /**  Create a new Reader of the type defined in $inputFileType  **/
                                        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                                        /**  Advise the Reader of which WorkSheets we want to load  **/
                                        $reader->setLoadAllSheets();
                                        /**  Load $inputFileName to a Spreadsheet Object  **/
                                        $spreadsheet = $reader->load($inputFileName);
                                        $sheet = $spreadsheet->getSheet(0);

                                        $borderstyle = [
                                            // 'alignment' => [
                                            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                                            // ],
                                            'borders' => [
                                                'allBorders' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                                                ]
                                            ]
                                        ];
                                        $sheet->setCellValue('C3', 'Institution Name : _' . DB::table('schoolinfo')->first()->schoolname . '____________________________________________________');
                                        $sheet->setCellValue('Q3', 'Institution Code : _' . DB::table('schoolinfo')->first()->schoolid . '_____________________________');
                                        $sheet->setCellValue('Y3', 'Address : _' . DB::table('schoolinfo')->first()->address . '_____________________________');
                                        $startcellno = 11;
                                        // $sheet->getStyle('A15:K15')->applyFromArray($borderstyle);
                                        // $sheet->getStyle('M15:W15')->applyFromArray($borderstyle);
                                        if (count($students) > 0) {
                                            foreach ($students as $student) {
                                                $student->sortname = $student->lastname . ' ' . $student->firstname;
                                                $student->sortcourseandlevel = $student->coursename . ' ' . $student->sortid . ' ' . $student->sortname;

                                                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                                                    $subjects = DB::table('college_enrolledstud')
                                                        ->select('schedulecoding.id', 'schedulecoding.code', 'college_subjects.id as subjectid', 'college_subjects.subjCode as subjectcode', 'college_subjects.subjDesc as subjectname', DB::raw('labunits + lecunits as units'), 'schedulecoding.id as prospectusid', 'schedulecoding.teacherid')
                                                        ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                                                        ->join('college_studsched', 'college_enrolledstud.studid', '=', 'college_studsched.studid')
                                                        ->join('schedulecoding', 'college_studsched.schedcodeid', '=', 'schedulecoding.id')
                                                        ->join('college_subjects', 'schedulecoding.subjid', '=', 'college_subjects.id')
                                                        ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                                                        ->where('college_enrolledstud.syid', $request->get('syid'))
                                                        // ->where('college_enrolledstud.studid',2853)
                                                        ->where('college_enrolledstud.studid', $student->id)
                                                        ->where('college_enrolledstud.semid', $request->get('semid'))
                                                        ->where('schedulecoding.syid', $request->get('syid'))
                                                        ->where('schedulecoding.semid', $request->get('semid'))
                                                        ->where('schedulecoding.deleted', '0')
                                                        ->where('college_enrolledstud.deleted', '0')
                                                        ->where('college_studsched.deleted', '0')
                                                        ->distinct()
                                                        ->get();
                                                } else {

                                                    $subjects = DB::table('college_studsched')
                                                        ->join('college_classsched', function ($join) use ($syid, $semid) {
                                                            $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                                                            $join->where('college_classsched.deleted', 0);
                                                            $join->where('syID', $syid);
                                                            $join->where('semesterID', $semid);
                                                        })
                                                        ->join('college_prospectus', function ($join) use ($syid, $semid) {
                                                            $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                                            $join->where('college_prospectus.deleted', 0);
                                                        })
                                                        ->where('schedstatus', 'REGULAR')
                                                        ->where('college_studsched.deleted', 0)
                                                        ->where('college_studsched.studid', $student->id)
                                                        ->leftJoin('college_studentprospectus', 'college_prospectus.id', '=', 'college_studentprospectus.prospectusID')
                                                        ->select('subjCode as subjectcode', 'subjDesc as subjectname', 'labunits', 'lecunits', 'fg as subjgrade')
                                                        ->orderBy('subjCode')
                                                        ->get();

                                                    if (count($subjects) > 0) {
                                                        foreach ($subjects as $grade) {
                                                            $grade->subjcredit = 0;
                                                            $grade->units = ($grade->lecunits + $grade->labunits);
                                                        }
                                                    }
                                                }

                                                $student->subjects = $subjects;

                                            }

                                            $students = collect($students)->sortBy('sortcourseandlevel')->all();
                                            $students = collect($students)->values()->all();
                                            // return $students;
                                            foreach ($students as $key => $student) {
                                                $student->yearlevel = 0;
                                                if ($student->levelid == 17) {
                                                    $student->yearlevel = 1;
                                                }
                                                if ($student->levelid == 18) {
                                                    $student->yearlevel = 2;
                                                }
                                                if ($student->levelid == 19) {
                                                    $student->yearlevel = 3;
                                                }
                                                if ($student->levelid == 20) {
                                                    $student->yearlevel = 4;
                                                }
                                                if ($student->levelid == 21) {
                                                    $student->yearlevel = 5;
                                                }
                                                $sheet->setCellValue('A' . $startcellno, ($key + 1));
                                                if ($student->lastname != null) {
                                                    $sheet->setCellValue('B' . $startcellno, $student->lastname);
                                                }
                                                if ($student->firstname != null) {
                                                    $sheet->setCellValue('C' . $startcellno, $student->firstname);
                                                }
                                                if ($student->middlename != null) {
                                                    $sheet->setCellValue('D' . $startcellno, $student->middlename);
                                                }
                                                if ($student->suffix != null) {
                                                    $sheet->setCellValue('E' . $startcellno, $student->suffix);
                                                }
                                                if ($student->gender != null) {
                                                    $sheet->setCellValue('F' . $startcellno, ucwords(strtolower($student->gender)));
                                                }
                                                if ($student->dob != null) {
                                                    $sheet->setCellValue('G' . $startcellno, date('m/d/Y', strtotime($student->dob)));
                                                }
                                                if ($student->coursename != null) {
                                                    $sheet->setCellValue('H' . $startcellno, $student->coursename);
                                                }
                                                if ($student->yearlevel != null) {
                                                    $sheet->setCellValue('I' . $startcellno, $student->yearlevel);
                                                }

                                                $subjectindex = 0;
                                                $subjectcount = 0;
                                                for ($x = 1; $x <= 50; $x++) {
                                                    $sheet->getStyle(getNameFromNumber($x) . $startcellno)->applyFromArray($borderstyle);

                                                }
                                                for ($x = 10; $x <= 50; $x++) {
                                                    if (isset($student->subjects[$subjectindex])) {
                                                        $sheet->setCellValue(getNameFromNumber($x) . $startcellno, $student->subjects[$subjectindex]->subjectcode);
                                                        $sheet->setCellValue(getNameFromNumber($x + 1) . $startcellno, $student->subjects[$subjectindex]->units);

                                                        $subjectindex += 1;
                                                        $x += 1;
                                                    }
                                                }
                                                $sheet->setCellValue('AX' . $startcellno, collect($student->subjects)->sum('units'));
                                                // return 'asdsadsa';
                                                $startcellno += 1;
                                            }
                                        }


                                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                                        header('Content-Type: application/vnd.ms-excel');
                                        header('Content-Disposition: attachment; filename="CHED - Enrollment List S.Y ' . $sydesc . ' ' . $semester . '.xlsx"');
                                        $writer->save("php://output");
                                        exit;

                                    } else {
                                        $transmutations = array();
                                        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                                            $transmutations = array(
                                                (object) ['gd' => null, 'gdto' => null, 'eq' => null],
                                                (object) ['gd' => 0.0, 'gdto' => number_format(73.99, 2), 'eq' => number_format(0.0, 1)],
                                                (object) ['gd' => 74.0, 'gdto' => number_format(74.49, 2), 'eq' => number_format(5.0, 1)],
                                                (object) ['gd' => 74.5, 'gdto' => number_format(75.99, 2), 'eq' => number_format(3.5, 1)],
                                                (object) ['gd' => 76.0, 'gdto' => number_format(76.99, 2), 'eq' => number_format(3.4, 1)],
                                                (object) ['gd' => 77.0, 'gdto' => number_format(77.99, 2), 'eq' => number_format(3.3, 1)],
                                                (object) ['gd' => 78.0, 'gdto' => number_format(78.99, 2), 'eq' => number_format(3.2, 1)],
                                                (object) ['gd' => 79.0, 'gdto' => number_format(79.99, 2), 'eq' => number_format(3.1, 1)],
                                                (object) ['gd' => 80.0, 'gdto' => number_format(80.99, 2), 'eq' => number_format(3.0, 1)],
                                                (object) ['gd' => 81.0, 'gdto' => number_format(81.99, 2), 'eq' => number_format(2.9, 1)],
                                                (object) ['gd' => 82.0, 'gdto' => number_format(82.99, 2), 'eq' => number_format(2.8, 1)],
                                                (object) ['gd' => 83.0, 'gdto' => number_format(83.99, 2), 'eq' => number_format(2.7, 1)],
                                                (object) ['gd' => 84.0, 'gdto' => number_format(84.99, 2), 'eq' => number_format(2.6, 1)],
                                                (object) ['gd' => 85.0, 'gdto' => number_format(85.99, 2), 'eq' => number_format(2.5, 1)],
                                                (object) ['gd' => 86.0, 'gdto' => number_format(86.99, 2), 'eq' => number_format(2.4, 1)],
                                                (object) ['gd' => 87.0, 'gdto' => number_format(87.99, 2), 'eq' => number_format(2.3, 1)],
                                                (object) ['gd' => 88.0, 'gdto' => number_format(88.99, 2), 'eq' => number_format(2.2, 1)],
                                                (object) ['gd' => 89.0, 'gdto' => number_format(89.99, 2), 'eq' => number_format(2.1, 1)],
                                                (object) ['gd' => 90.0, 'gdto' => number_format(90.99, 2), 'eq' => number_format(2.0, 1)],
                                                (object) ['gd' => 91.0, 'gdto' => number_format(91.99, 2), 'eq' => number_format(1.9, 1)],
                                                (object) ['gd' => 92.0, 'gdto' => number_format(92.99, 2), 'eq' => number_format(1.8, 1)],
                                                (object) ['gd' => 93.0, 'gdto' => number_format(93.99, 2), 'eq' => number_format(1.7, 1)],
                                                (object) ['gd' => 94.0, 'gdto' => number_format(94.99, 2), 'eq' => number_format(1.6, 1)],
                                                (object) ['gd' => 95.0, 'gdto' => number_format(95.99, 2), 'eq' => number_format(1.5, 1)],
                                                (object) ['gd' => 96.0, 'gdto' => number_format(96.99, 2), 'eq' => number_format(1.4, 1)],
                                                (object) ['gd' => 97.0, 'gdto' => number_format(97.99, 2), 'eq' => number_format(1.3, 1)],
                                                (object) ['gd' => 98.0, 'gdto' => number_format(98.99, 2), 'eq' => number_format(1.2, 1)],
                                                (object) ['gd' => 99.0, 'gdto' => number_format(99.99, 2), 'eq' => number_format(1.1, 1)],
                                                (object) ['gd' => 100.0, 'gdto' => number_format(100, 2), 'eq' => number_format(1.0, 1)]
                                            );
                                        }

                                        $inputFileName = base_path() . '/public/excelformats/dcc/promotionalreport.xlsx';
                                        // $sheetname = 'Front';

                                        /**  Create a new Reader of the type defined in $inputFileType  **/
                                        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                                        /**  Advise the Reader of which WorkSheets we want to load  **/
                                        $reader->setLoadAllSheets();
                                        /**  Load $inputFileName to a Spreadsheet Object  **/
                                        $spreadsheet = $reader->load($inputFileName);
                                        $sheet = $spreadsheet->getSheet(0);

                                        $borderstyle = [
                                            // 'alignment' => [
                                            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                                            // ],
                                            'borders' => [
                                                'allBorders' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                                                ]
                                            ]
                                        ];
                                        $sheet->setCellValue('E3', DB::table('schoolinfo')->first()->schoolname);
                                        $sheet->setCellValue('S3', DB::table('schoolinfo')->first()->schoolid);
                                        $sheet->setCellValue('AG3', DB::table('schoolinfo')->first()->address);
                                        $startcellno = 11;
                                        // $sheet->getStyle('A15:K15')->applyFromArray($borderstyle);
                                        // $sheet->getStyle('M15:W15')->applyFromArray($borderstyle);
                                        if (count($students) > 0) {
                                            foreach ($students as $student) {
                                                $student->sortname = $student->lastname . ' ' . $student->firstname;
                                                $student->sortcourseandlevel = $student->coursename . ' ' . $student->sortid . ' ' . $student->sortname;

                                                $subjects = collect(\App\Models\College\TOR::getrecords($student->id, DB::table('sy')->select('sy.*', 'id as syid')->where('id', $syid)->get()))->where('semid', $semid)->values();

                                                $subjects = collect($subjects[0]->subjdata) ?? array();

                                                $student->subjects = $subjects;
                                            }


                                            $students = collect($students)->sortBy('sortcourseandlevel')->all();
                                            $students = collect($students)->values()->all();
                                            // return $students;
                                            foreach ($students as $key => $student) {
                                                $student->yearlevel = 0;
                                                if ($student->levelid == 17) {
                                                    $student->yearlevel = 1;
                                                }
                                                if ($student->levelid == 18) {
                                                    $student->yearlevel = 2;
                                                }
                                                if ($student->levelid == 19) {
                                                    $student->yearlevel = 3;
                                                }
                                                if ($student->levelid == 20) {
                                                    $student->yearlevel = 4;
                                                }
                                                if ($student->levelid == 21) {
                                                    $student->yearlevel = 5;
                                                }
                                                $sheet->setCellValue('A' . $startcellno, ($key + 1));
                                                if ($student->lastname != null) {
                                                    $sheet->setCellValue('B' . $startcellno, $student->lastname);
                                                }
                                                if ($student->firstname != null) {
                                                    $sheet->setCellValue('C' . $startcellno, $student->firstname);
                                                }
                                                if ($student->middlename != null) {
                                                    $sheet->setCellValue('D' . $startcellno, $student->middlename);
                                                }
                                                if ($student->suffix != null) {
                                                    $sheet->setCellValue('E' . $startcellno, $student->suffix);
                                                }
                                                if ($student->gender != null) {
                                                    $sheet->setCellValue('F' . $startcellno, ucwords(strtolower($student->gender)));
                                                }
                                                if ($student->dob != null) {
                                                    $sheet->setCellValue('G' . $startcellno, date('m/d/Y', strtotime($student->dob)));
                                                }
                                                if ($student->coursename != null) {
                                                    $sheet->setCellValue('H' . $startcellno, $student->coursename);
                                                }
                                                if ($student->yearlevel != null) {
                                                    $sheet->setCellValue('I' . $startcellno, $student->yearlevel);
                                                }

                                                $subjectindex = 0;
                                                $subjectcount = 0;
                                                for ($x = 1; $x <= 72; $x++) {
                                                    $sheet->getStyle(getNameFromNumber($x) . $startcellno)->applyFromArray($borderstyle);

                                                }
                                                for ($x = 10; $x <= 70; $x++) {
                                                    if (isset($student->subjects[$subjectindex])) {
                                                        $sheet->setCellValue(getNameFromNumber($x) . $startcellno, $student->subjects[$subjectindex]->subjcode);
                                                        $sheet->setCellValue(getNameFromNumber($x + 1) . $startcellno, $student->subjects[$subjectindex]->subjgrade);
                                                        $sheet->setCellValue(getNameFromNumber($x + 2) . $startcellno, $student->subjects[$subjectindex]->subjcredit);

                                                        $subjectindex += 1;
                                                        $x += 2;
                                                    }
                                                }
                                                $sheet->setCellValue('BR' . $startcellno, collect($student->subjects)->sum('subjcredit'));
                                                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                                                    $sheet->setCellValue('BS' . $startcellno, number_format(collect($student->subjects)->where('subjgrade', '>', '74')->avg('subjgrade'), 2));
                                                } else {
                                                    $sheet->setCellValue('BS' . $startcellno, number_format(collect($student->subjects)->where('subjgrade', '<', '7')->avg('subjgrade'), 2));
                                                }
                                                // return 'asdsadsa';
                                                $startcellno += 1;
                                            }
                                        }


                                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                                        header('Content-Type: application/vnd.ms-excel');
                                        header('Content-Disposition: attachment; filename="CHED - Promotional Report S.Y ' . $sydesc . ' ' . $semester . '.xlsx"');
                                        $writer->save("php://output");
                                        exit;

                                    }
                                }

                        }

                    }

                } elseif ($request->get('type') == 'sl') {
                    $students = DB::table('college_enrolledstud')
                        ->select(
                            'studinfo.id',
                            'studinfo.sid',
                            'studinfo.lastname',
                            'studinfo.middlename',
                            'studinfo.firstname',
                            DB::raw('LOWER(`gender`) as gender'),
                            'gradelevel.id as levelid',
                            'gradelevel.sortid',
                            'gradelevel.levelname',
                            'college_courses.courseabrv as coursename',
                            'college_courses.courseDesc',
                            'gradelevel.acadprogid'
                        )
                        ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                        ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                        ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                        ->where('college_enrolledstud.syid', $request->get('syid'))
                        ->where('college_enrolledstud.semid', $request->get('semid'))
                        ->whereIN('college_enrolledstud.studstatus', [1, 2, 4])
                        ->where('college_enrolledstud.deleted', '0')
                        ->get();
                    // return $students;
                    if (count($students) > 0) {
                        foreach ($students as $student) {
                            $totalunits = db::table('college_studsched')
                                ->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
                                ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                                ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                                ->where('college_studsched.studid', $student->id)
                                ->where('college_studsched.deleted', 0)
                                ->where('college_classsched.deleted', 0)
                                ->where('college_classsched.syID', $request->get('syid'))
                                ->where('college_classsched.semesterID', $request->get('semid'))
                                ->first();

                            $student->totalunits = $totalunits->totalunits;
                        }
                    }
                    if ($request->get('action') == 'filter') {
                        return view('registrar.summaries.studentlist.sl_table')
                            ->with('students', $students->groupBy('coursename'));
                    } else if ($request->get('action') == 'exportpdf') {
                        $students = $students->groupBy('coursename');
                        $semester = DB::table('semester')
                            ->where('id', $request->get('semid'))
                            ->first()->semester;
                        $sydesc = DB::table('sy')
                            ->where('id', $request->get('syid'))
                            ->first()->sydesc;
                        $pdf = PDF::loadview('registrar.summaries.studentlist.pdf_studentlist', compact('students', 'semester', 'sydesc'));

                        return $pdf->stream('Student List.pdf');
                    } else if ($request->get('action') == 'exportxml') {
                        $students = $students->groupBy('coursename');

                        // Create the XML structure
                        $xml = new \SimpleXMLElement('<EnrollmentSummary/>');
                        $xml->addChild('University', 'Liceo de Cagayan University');
                        $xml->addChild('Semester', '1ST SEMESTER 2023-2024');

                        foreach ($students as $courseName => $courseStudents) {
                            $courseNode = $xml->addChild('Course');
                            $courseNode->addChild('CourseName', $courseName);
                            $courseNode->addChild('YearLevel', '1ST YEAR COLLEGE'); // Example static data, adjust as needed

                            foreach ($courseStudents as $student) {
                                $studentNode = $courseNode->addChild('Student');
                                $studentNode->addChild('StudentID', $student->id);
                                $studentNode->addChild('StudentName', $student->lastname . ', ' . $student->firstname ?? '' . ' ' . (isset($student->suffix) ? $student->suffix : ''));
                                $studentNode->addChild('Gender', $student->gender);
                                $studentNode->addChild('UnitsEnrolled', $student->totalunits);
                            }

                            // Add totals for each course
                            $courseNode->addChild('CourseTotal', count($courseStudents));
                        }

                        // Add grand total at the end
                        $xml->addChild('GrandTotal', $students->flatten()->count());

                        // Set headers for XML download
                        return response($xml->asXML())
                            ->header('Content-Type', 'application/xml')
                            ->header('Content-Disposition', 'attachment; filename="EnrollmentSummary.xml"');
                    } else if ($request->get('action') == 'exportexcel') {
                        $students = $students->groupBy('coursename');
                        $semester = DB::table('semester')
                            ->where('id', $request->get('semid'))
                            ->first()->semester;
                        $sydesc = DB::table('sy')
                            ->where('id', $request->get('syid'))
                            ->first()->sydesc;
                        $schoolInfo = DB::table('schoolinfo')->first();

                        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();

                        // Set Column Widths
                        $sheet->getColumnDimension('A')->setWidth(15);
                        $sheet->getColumnDimension('B')->setWidth(40);  // Set wider for the school name
                        $sheet->getColumnDimension('C')->setWidth(15);
                        $sheet->getColumnDimension('D')->setWidth(15);


                        // Add School Logo
                        $logoPath = public_path(parse_url($schoolInfo->picurl, PHP_URL_PATH)); // Extract only the path part

                        if (file_exists($logoPath)) {
                            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                            $drawing->setPath($logoPath);
                            $drawing->setHeight(80); // Adjust height if necessary
                            $drawing->setCoordinates('A1'); // Position of the logo
                            $drawing->setOffsetX(5); // Optional: Adjust horizontal offset
                            $drawing->setOffsetY(5); // Optional: Adjust vertical offset
                            $drawing->setWorksheet($sheet);
                        }

                        // Add School Name and Address
                        $sheet->setCellValue('B1', $schoolInfo->schoolname);
                        $sheet->setCellValue('B2', ucwords(strtolower($schoolInfo->address)));
                        $sheet->mergeCells('B1:D1');
                        $sheet->mergeCells('B2:D2');

                        // Style the School Name and Address
                        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
                        $sheet->getStyle('B2')->getFont()->setSize(12);
                        $sheet->getStyle('B1:D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        // Add Enrollment Summary Header
                        $sheet->setCellValue('A4', 'ENROLLMENT SUMMARY - ' . $semester . ' ' . $sydesc);
                        $sheet->mergeCells('A4:D4');
                        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12);
                        $sheet->getStyle('A4:D4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        // Add Table Headers
                        $sheet->fromArray(['Student ID', 'Student Name', 'Gender', 'Units Enrolled'], null, 'A6');
                        $sheet->getStyle('A6:D6')->getFont()->setBold(true);

                        // Populate Data
                        $row = 7;
                        foreach ($students as $courseName => $courseStudents) {
                            $sheet->setCellValue("A$row", $courseName);
                            $sheet->mergeCells("A$row:D$row");
                            $sheet->getStyle("A$row")->getFont()->setBold(true);
                            $row++;

                            foreach ($courseStudents as $student) {
                                $sheet->setCellValue("A$row", $student->id);
                                $sheet->setCellValue("B$row", $student->lastname . ', ' . $student->firstname . ' ' . (isset($student->suffix) ? $student->suffix : ''));
                                $sheet->setCellValue("C$row", $student->gender);
                                $sheet->setCellValue("D$row", $student->totalunits);
                                $row++;
                            }
                            $row++;
                        }

                        // Download as Excel
                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        $filename = 'StudentList.xlsx';
                        return response()->streamDownload(function () use ($writer) {
                            $writer->save('php://output');
                        }, $filename);
                    } else if ($request->get('action') == 'exportjson') {
                        $students = $students->groupBy('coursename');
                        $data = [];

                        foreach ($students as $courseName => $courseStudents) {
                            $courseData = [
                                'courseName' => $courseName,
                                'students' => []
                            ];

                            foreach ($courseStudents as $student) {
                                $courseData['students'][] = [
                                    'StudentID' => $student->id,
                                    'StudentName' => $student->lastname . ', ' . $student->firstname . ' ' . (isset($student->suffix) ? $student->suffix : ''),
                                    'Gender' => $student->gender,
                                    'UnitsEnrolled' => $student->totalunits,
                                ];
                            }

                            $data[] = $courseData;
                        }

                        return response()->json($data)->header('Content-Disposition', 'attachment; filename="StudentList.json"');
                    }



                } elseif ($request->get('type') == 'nstpel') {
                    $syid = $request->get('syid');
                    if ($request->has('semid')) {
                        $semid = $request->get('semid');
                    } else {
                        $semid = DB::table('semester')->where('isactive', '1')->first()->id;
                    }

                    $students = array();
                    $subjects = array();
                    if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                        $transmutations = array(
                            (object) ['gd' => null, 'gdto' => null, 'eq' => null],
                            (object) ['gd' => 0.0, 'gdto' => number_format(73.99, 2), 'eq' => number_format(0.0, 1)],
                            (object) ['gd' => 74.0, 'gdto' => number_format(74.49, 2), 'eq' => number_format(5.0, 1)],
                            (object) ['gd' => 74.5, 'gdto' => number_format(75.99, 2), 'eq' => number_format(3.5, 1)],
                            (object) ['gd' => 76.0, 'gdto' => number_format(76.99, 2), 'eq' => number_format(3.4, 1)],
                            (object) ['gd' => 77.0, 'gdto' => number_format(77.99, 2), 'eq' => number_format(3.3, 1)],
                            (object) ['gd' => 78.0, 'gdto' => number_format(78.99, 2), 'eq' => number_format(3.2, 1)],
                            (object) ['gd' => 79.0, 'gdto' => number_format(79.99, 2), 'eq' => number_format(3.1, 1)],
                            (object) ['gd' => 80.0, 'gdto' => number_format(80.99, 2), 'eq' => number_format(3.0, 1)],
                            (object) ['gd' => 81.0, 'gdto' => number_format(81.99, 2), 'eq' => number_format(2.9, 1)],
                            (object) ['gd' => 82.0, 'gdto' => number_format(82.99, 2), 'eq' => number_format(2.8, 1)],
                            (object) ['gd' => 83.0, 'gdto' => number_format(83.99, 2), 'eq' => number_format(2.7, 1)],
                            (object) ['gd' => 84.0, 'gdto' => number_format(84.99, 2), 'eq' => number_format(2.6, 1)],
                            (object) ['gd' => 85.0, 'gdto' => number_format(85.99, 2), 'eq' => number_format(2.5, 1)],
                            (object) ['gd' => 86.0, 'gdto' => number_format(86.99, 2), 'eq' => number_format(2.4, 1)],
                            (object) ['gd' => 87.0, 'gdto' => number_format(87.99, 2), 'eq' => number_format(2.3, 1)],
                            (object) ['gd' => 88.0, 'gdto' => number_format(88.99, 2), 'eq' => number_format(2.2, 1)],
                            (object) ['gd' => 89.0, 'gdto' => number_format(89.99, 2), 'eq' => number_format(2.1, 1)],
                            (object) ['gd' => 90.0, 'gdto' => number_format(90.99, 2), 'eq' => number_format(2.0, 1)],
                            (object) ['gd' => 91.0, 'gdto' => number_format(91.99, 2), 'eq' => number_format(1.9, 1)],
                            (object) ['gd' => 92.0, 'gdto' => number_format(92.99, 2), 'eq' => number_format(1.8, 1)],
                            (object) ['gd' => 93.0, 'gdto' => number_format(93.99, 2), 'eq' => number_format(1.7, 1)],
                            (object) ['gd' => 94.0, 'gdto' => number_format(94.99, 2), 'eq' => number_format(1.6, 1)],
                            (object) ['gd' => 95.0, 'gdto' => number_format(95.99, 2), 'eq' => number_format(1.5, 1)],
                            (object) ['gd' => 96.0, 'gdto' => number_format(96.99, 2), 'eq' => number_format(1.4, 1)],
                            (object) ['gd' => 97.0, 'gdto' => number_format(97.99, 2), 'eq' => number_format(1.3, 1)],
                            (object) ['gd' => 98.0, 'gdto' => number_format(98.99, 2), 'eq' => number_format(1.2, 1)],
                            (object) ['gd' => 99.0, 'gdto' => number_format(99.99, 2), 'eq' => number_format(1.1, 1)],
                            (object) ['gd' => 100.0, 'gdto' => number_format(100, 2), 'eq' => number_format(1.0, 1)]
                        );
                        $semid = $request->get('semid');

                        $classscheddetail_colleges = Db::table('schedulecoding')
                            ->select(
                                'schedulecoding.id',
                                // 'schedulecoding.code',
                                'college_subjects.id as subjectid',
                                'college_subjects.subjCode as subjcode',
                                'college_subjects.subjDesc as subjectname',
                                DB::raw('labunits + lecunits as units'),
                                'schedulecoding.id as prospectusid'
                                // 'schedulecoding.teacherid',
                                // 'teacher.lastname',
                                // 'teacher.firstname'
                            )
                            ->join('college_subjects', 'schedulecoding.subjid', '=', 'college_subjects.id')
                            ->leftJoin('teacher', 'schedulecoding.teacherid', 'teacher.id')
                            ->where('schedulecoding.syid', $syid)
                            ->where('schedulecoding.semid', $semid)
                            ->where('schedulecoding.deleted', '0')
                            ->where('college_subjects.deleted', '0')
                            ->orderBy('subjcode', 'asc')
                            ->distinct()
                            ->get();

                        $college_students = DB::table('college_enrolledstud')
                            ->select(
                                'studinfo.id',
                                'studinfo.sid',
                                'studinfo.lastname',
                                'studinfo.firstname',
                                'studinfo.middlename',
                                'studinfo.suffix',
                                'studinfo.gender',
                                'studinfo.dob',
                                'studinfo.contactno',
                                'studinfo.street',
                                'studinfo.barangay',
                                'studinfo.city',
                                'studinfo.province',
                                'college_courses.courseabrv',
                                'college_enrolledstud.studid',
                                'schedulecoding.id as prospectusid',
                                // ,'courseabrv as coursename','courseabrv as deptcourse',
                                // 'college_enrolledstud.yearLevel as yearlevel',
                                'college_year.yearDesc as yearlevel',
                                DB::raw('CONCAT(lastname, ", ",firstname) as sortname')
                                //  'college_courses.collegeid'
                            )
                            ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                            ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                            ->join('college_studsched', 'college_enrolledstud.studid', '=', 'college_studsched.studid')
                            ->join('schedulecoding', 'college_studsched.schedcodeid', '=', 'schedulecoding.id')
                            ->join('college_year', 'college_enrolledstud.yearLevel', '=', 'college_year.levelid')
                            ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                            ->where('college_enrolledstud.syid', $syid)
                            // ->where('college_enrolledstud.studid', 3820)
                            ->where('college_enrolledstud.semid', $semid)
                            ->whereIn('schedulecoding.id', collect($classscheddetail_colleges)->pluck('id'))
                            ->where('college_enrolledstud.deleted', '0')
                            ->where('college_studsched.deleted', '0')
                            ->distinct()
                            ->get();

                        $subjectsarray = array();

                        if (count($classscheddetail_colleges) > 0) {
                            foreach ($classscheddetail_colleges as $schedule_collegekey => $schedule_college) {
                                if (strpos(strtolower($schedule_college->subjcode), 'cwts') !== false) {


                                    $nstpcomp = 'CWTS';
                                    $schedule_college->nstpcomponent = $nstpcomp;
                                    try {
                                        array_push($subjectsarray, $schedule_college);
                                    } catch (\Exception $error) {
                                    }
                                }
                                if (strpos(strtolower($schedule_college->subjcode), 'lts') !== false) {

                                    $nstpcomp = 'LTS';
                                    $schedule_college->nstpcomponent = $nstpcomp;
                                    try {
                                        array_push($subjectsarray, $schedule_college);
                                    } catch (\Exception $error) {
                                    }
                                }
                                if (strpos(strtolower($schedule_college->subjcode), 'nstp') !== false) {

                                    $nstpcomp = 'NSTP';
                                    $schedule_college->nstpcomponent = $nstpcomp;
                                    try {
                                        array_push($subjectsarray, $schedule_college);
                                    } catch (\Exception $error) {
                                    }
                                }
                                if (strpos(strtolower($schedule_college->subjcode), 'mts') !== false) {

                                    $nstpcomp = 'MTS';
                                    $schedule_college->nstpcomponent = $nstpcomp;
                                    try {
                                        array_push($subjectsarray, $schedule_college);
                                    } catch (\Exception $error) {
                                    }
                                }
                            }
                        }
                        // $subjectsarray = collect($subjectsarray)->where('nstpcomponent','LTS')->where('id',2152);
                        // return $subjectsarray;
                        $grades = DB::table('college_studentprospectus')
                            ->whereIn('college_studentprospectus.studid', collect($college_students)->pluck('id'))
                            // ->where('prospectusID',collect($subjectsarray)->pluck('id'))
                            ->whereIn('prospectusID', collect($subjectsarray)->pluck('id'))
                            ->where('deleted', '0')
                            ->get();

                        // return collect($college_students)->where('prospectusid',2152);

                        $subjectscollect = collect($subjectsarray)->groupBy('subjcode')->all();
                        if (count($subjectscollect) > 0) {
                            foreach ($subjectscollect as $eachsubjkey => $eachsubj) {
                                $eachsubjstudents = collect($college_students)->sortBy('sortname')->whereIn('prospectusid', collect($eachsubj)->pluck('prospectusid')->toArray())->values()->all();

                                if ($request->get('reporttype') == 'promotional' || $request->get('reporttype') == 'listofgraduates') {
                                    if (count($eachsubjstudents) > 0) {
                                        foreach ($eachsubjstudents as $eachsubjstudent) {
                                            $eachsubjstudent->units = $eachsubj[0]->units;
                                            $eachsubjstudent->subjgrade = null;
                                            $eachsubjstudent->eqgrade = null;
                                            $eachsubjstudent->remarks = null;
                                            if (count($eachsubj) > 0) {
                                                foreach ($eachsubj as $eachsub) {
                                                    $grade = collect($grades)->where('studid', $eachsubjstudent->id)
                                                        ->where('prospectusID', $eachsub->id)
                                                        ->first();

                                                    if ($eachsubjstudent->eqgrade <= 0 || $eachsubjstudent->eqgrade == null) {

                                                        if ($grade) {
                                                            $eachsubjstudent->eqgrade = collect($transmutations)->where('gd', '!=', null)->where('gd', '<=', $grade->finalgrade)->where('gdto', '>=', $grade->finalgrade)->first()->eq ?? null;
                                                            $eachsubjstudent->subjgrade = $grade->finalgrade;
                                                            if ($grade->midtermstatus == 8 || $grade->prefistatus == 8) {
                                                                $eachsubjstudent->remarks = 'INCOMPLETE';
                                                                $eachsubjstudent->eqgrade = 0; //7
                                                                $eachsubjstudent->subjgrade = 0; //7
                                                                $eachsubjstudent->units = 0;
                                                            } else if ($grade->midtermstatus == 9 || $grade->prefistatus == 9) {
                                                                $eachsubjstudent->remarks = 'DROPPED';
                                                                $eachsubjstudent->eqgrade = 0; //9
                                                                $eachsubjstudent->subjgrade = 0; //9
                                                                $eachsubjstudent->units = 0;
                                                            } else {
                                                                if ($eachsubjstudent->eqgrade != null) {
                                                                    $eachsubjstudent->remarks = $eachsubjstudent->eqgrade < 5.0 && $eachsubjstudent->eqgrade != 0 ? 'PASSED' : 'FAILED';
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                if ($request->get('reporttype') == 'listofgraduates') {
                                    $eachsubjstudents = collect($eachsubjstudents)->where('subjgrade', '>', 74)->all();
                                }
                                // return collect($eachsubjstudents);
                                array_push($subjects, (object) array(
                                    'subjcode' => $eachsubjkey,
                                    'subjectname' => $eachsubj[0]->subjectname,
                                    'nstpcomponent' => $eachsubj[0]->nstpcomponent,
                                    'units' => $eachsubj[0]->units,
                                    'prospectusids' => collect($eachsubj)->pluck('prospectusid')->toArray(),
                                    'students' => $eachsubjstudents
                                ));
                            }
                        }


                    } else {

                        $students_college = DB::table('studinfo')
                            ->select(
                                'studinfo.id',
                                'studinfo.lastname',
                                'studinfo.firstname',
                                'studinfo.middlename',
                                DB::raw('LOWER(`gender`) as gender'),
                                'studinfo.dob',
                                'studinfo.contactno',
                                'studinfo.street',
                                'studinfo.barangay',
                                'studinfo.city',
                                'studinfo.province',
                                'studinfo.semail as email',
                                'gradelevel.id as levelid',
                                'gradelevel.sortid',
                                'gradelevel.levelname',
                                'college_enrolledstud.semid',
                                'college_enrolledstud.courseid',
                                'college_courses.courseabrv as coursename',
                                'gradelevel.acadprogid'
                            )
                            ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
                            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                            ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                            ->where('college_enrolledstud.syid', $request->get('syid'))
                            // ->where('college_enrolledstud.semid',$request->get('semid'))
                            ->whereIN('college_enrolledstud.studstatus', [1, 2, 4])
                            ->where('college_enrolledstud.deleted', '0')
                            // ->where('college_enrolledstud.yearLevel','17')
                            // ->where('studinfo.deleted','0')
                            ->orderBy('lastname', 'asc')
                            ->distinct()
                            ->get();



                        // if($request->has('exporttype'))
                        // {
                        //     if($request->get('exporttype') == 'persem')
                        //     {
                        //         $students_college = collect($students_college)->where('semid', $request->get('semid'))->values();
                        //     }else{
                        //         $semid = null;
                        //     }
                        // }else{
                        //     $students_college = collect($students_college)->where('semid', $request->get('semid'))->values();
                        // }

                        if (count($students_college) > 0) {
                            foreach ($students_college as $studcollege) {
                                $sched_list = array();
                                $studcollege->department = 'College';
                                $courseid = $studcollege->courseid;

                                $subjects = DB::table('college_studsched')
                                    ->join('college_classsched', function ($join) use ($syid) {
                                        $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                                        $join->where('college_classsched.deleted', 0);
                                        $join->where('college_classsched.syid', $syid);
                                    })
                                    ->join('college_prospectus', function ($join) {
                                        $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                                        $join->where('college_prospectus.deleted', 0);
                                    })
                                    ->join('college_sections', function ($join) {
                                        $join->on('college_classsched.sectionID', '=', 'college_sections.id');
                                        $join->where('college_sections.deleted', 0);
                                    })
                                    ->where('college_studsched.deleted', 0)
                                    ->where('college_studsched.studid', $studcollege->id)
                                    ->select(
                                        'college_classsched.semesterID as semid',
                                        'lecunits',
                                        'labunits',
                                        'college_prospectus.subjectID as main_subjid',
                                        'college_classsched.*',
                                        'schedid',
                                        'subjDesc',
                                        'subjCode',
                                        'sectionDesc'
                                        //   'schedstatus'
                                    )
                                    ->where('subjCode', 'like', '%nstp%')
                                    ->get();
                                $subjects = collect($subjects)->where('semid', $studcollege->semid)->values();
                                // if($semid != null)
                                // {
                                // }
                                // if($studcollege->id == 2204)
                                // {

                                if (count($subjects) > 0) {
                                    try {
                                        if (strpos(strtolower($subjects[0]->subjCode), 'cwts') !== false) {
                                            $nstpcomp = 'CWTS';
                                            $studcollege->nstpcomponent = $nstpcomp;
                                            array_push($students, $studcollege);
                                        }
                                        if (strpos(strtolower($subjects[0]->subjCode), 'lts') !== false) {
                                            $nstpcomp = 'LTS';
                                            $studcollege->nstpcomponent = $nstpcomp;
                                            array_push($students, $studcollege);
                                        }
                                        if (strpos(strtolower($subjects[0]->subjCode), 'nstp') !== false) {
                                            $nstpcomp = 'NSTP';
                                            $studcollege->nstpcomponent = $nstpcomp;
                                            array_push($students, $studcollege);
                                        }
                                        if (strpos(strtolower($subjects[0]->subjCode), 'mts') !== false) {
                                            $nstpcomp = 'MTS';
                                            $studcollege->nstpcomponent = $nstpcomp;
                                            array_push($students, $studcollege);
                                        }
                                    } catch (\Exception $error) {
                                    }
                                }
                            }
                        }
                        $semid = null;
                        $students = collect($students)->sortBy('firstname')->sortBy('lastname');
                    }



                    $formid = 'nstpel_reg_consultant';
                    $signatory = DB::table('signatory')
                        ->where('form', $formid)
                        ->where('title', 'Registrar Consulatant')
                        ->where('syid', $syid)
                        // ->where('acadprogid',$acadprogid)
                        ->where('deleted', 0)
                        ->where('createdby', auth()->user()->id)
                        ->first();

                    $reporttype = $request->get('reporttype');
                    if ($request->get('action') == 'filter') {
                        // return collect($students)->groupBy('nstpcomponent');
                        return view('registrar.summaries.studentlist.nstpel_table')
                            ->with('students', collect($students)->groupBy('nstpcomponent'))
                            ->with('subjects', $subjects)
                            ->with('semid', $semid)
                            ->with('syid', $syid)
                            ->with('reporttype', $reporttype)
                            ->with('signatory', $signatory);
                    } else {

                        $students = collect($students)->where('nstpcomponent', $request->get('nstpcomp'))->values();
                        $sydesc = DB::table('sy')
                            ->where('id', $request->get('syid'))
                            ->first()->sydesc;

                        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                            $semester = DB::table('semester')
                                ->where('id', $request->get('semid'))
                                ->first()->semester;
                            if ($request->has('nstpcomp')) {
                                $subjects = collect($subjects)->where('subjcode', $request->get('nstpcomp'))->values();
                            } else {
                                $subjects = collect($subjects)->values();
                            }
                            // return $subjects;
                            if ($reporttype == 'enrollmentlist') {
                                $reportname = 'NSTP ' . filter_var($subjects[0]->subjcode, FILTER_SANITIZE_NUMBER_INT) . ' - Enrollment List';
                            } elseif ($reporttype == 'promotional') {
                                $reportname = 'NSTP ' . filter_var($subjects[0]->subjcode, FILTER_SANITIZE_NUMBER_INT) . ' - Promotional Report';
                            } else {
                                $reportname = 'LIST OF NSTP GRADUATES FOR SERIAL NUMBER';
                            }
                            if ($request->get('filetype') == 'pdf') {
                                $pdf = PDF::loadview('registrar/summaries/studentlist/pdf_nstpeldcc', compact('subjects', 'sydesc', 'semester', 'signatory', 'reporttype', 'reportname'));

                                $pdf->getDomPDF()->set_option("enable_php", true);
                                return $pdf->stream('NSTP Enrollment List S.Y ' . $sydesc . ' ' . $semester . '.pdf');
                            } else {
                                $schoolinfo = Db::table('schoolinfo')
                                    ->select(
                                        'schoolinfo.schoolid',
                                        'schoolinfo.schoolname',
                                        'schoolinfo.authorized',
                                        'schoolinfo.picurl',
                                        'refcitymun.citymunDesc',
                                        'schoolinfo.district',
                                        'schoolinfo.address',
                                        'refregion.regDesc'
                                    )
                                    ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
                                    ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
                                    ->first();
                                // return $request->all();

                                if ($request->get('reporttype') == 'listofgraduates') {
                                    // return $subjects;
                                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                                    $borderstyle = [
                                        'borders' => [
                                            'allBorders' => [
                                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                                            ]
                                        ]
                                    ];
                                    $font_bold = [
                                        'font' => [
                                            'bold' => true,
                                        ]
                                    ];

                                    if (count($subjects) > 0) {
                                        foreach ($subjects as $subjectkey => $subject) {
                                            $startcellno = 1;
                                            if ($subjectkey > 0) {
                                                $spreadsheet->createSheet();
                                                $spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma');
                                                $spreadsheet->getDefaultStyle()->getFont()->setSize(8);
                                                $sheet = $spreadsheet->getActiveSheet();
                                                $sheet = $spreadsheet->getSheet($subjectkey);
                                                $sheet->setTitle($subject->nstpcomponent . ' ' . $semester);
                                            } else {
                                                $spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma');
                                                $spreadsheet->getDefaultStyle()->getFont()->setSize(8);
                                                $sheet = $spreadsheet->getActiveSheet();
                                                $sheet = $spreadsheet->getSheet($subjectkey);
                                                $sheet->setTitle($subject->nstpcomponent . ' ' . $semester);
                                            }
                                            $sheet->getColumnDimension('A')->setWidth(6);
                                            $sheet->getColumnDimension('B')->setWidth(10);
                                            $sheet->getColumnDimension('C')->setWidth(18);
                                            $sheet->getColumnDimension('D')->setWidth(25);
                                            $sheet->getColumnDimension('E')->setWidth(15);
                                            $sheet->getColumnDimension('F')->setWidth(17);
                                            $sheet->getColumnDimension('G')->setWidth(10);
                                            $sheet->getColumnDimension('H')->setWidth(14);
                                            $sheet->getColumnDimension('I')->setWidth(60);
                                            $sheet->getColumnDimension('J')->setWidth(20);
                                            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                                            $drawing->setWorksheet($sheet);
                                            $drawing->setName('Logo');
                                            $drawing->setDescription('Logo');
                                            $drawing->setPath(base_path() . '/public/' . DB::table('schoolinfo')->first()->picurl);
                                            $drawing->setHeight(80);
                                            $drawing->setCoordinates('D1');
                                            $drawing->setOffsetX(30);
                                            // $drawing->setOffsetY(20);


                                            // $sheet->mergeCells('A'.$startcellno.':J'.$startcellno);
                                            $sheet->getStyle('H' . $startcellno)->getFont()->setBold(true);
                                            $sheet->setCellValue('H' . $startcellno, 'Republic of the Philippines');
                                            $sheet->getStyle('H' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $startcellno += 1;
                                            $sheet->getStyle('H' . $startcellno)->getFont()->setBold(true);
                                            $sheet->setCellValue('H' . $startcellno, 'Office of the President');
                                            $sheet->getStyle('H' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $startcellno += 1;
                                            $sheet->getStyle('H' . $startcellno)->getFont()->setBold(true);
                                            $sheet->setCellValue('H' . $startcellno, 'COMMISSION ON HIGHER EDUCATION');
                                            $sheet->getStyle('H' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $startcellno += 2;
                                            $sheet->setCellValue('H' . $startcellno, 'LIST OF NSTP GRADUATES FOR SERIAL NUMBER');
                                            $sheet->getStyle('H' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $startcellno += 1;
                                            $sheet->setCellValue('F' . $startcellno, $request->get('semid'));
                                            $sheet->getStyle('F' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->setCellValue('G' . $startcellno, 'Semester');
                                            $sheet->setCellValue('H' . $startcellno, 'Academic Year:');
                                            $sheet->setCellValue('I' . $startcellno, $sydesc);
                                            $sheet->getStyle('I' . $startcellno)->getAlignment()->setHorizontal('left');

                                            $startcellno += 2;
                                            $sheet->getStyle('A' . $startcellno)->getFont()->setBold(true);
                                            $sheet->setCellValue('A' . $startcellno, 'Name of HEI:');
                                            $sheet->getStyle('A' . $startcellno)->getAlignment()->setHorizontal('left');

                                            $sheet->setCellValue('C' . $startcellno, DB::table('schoolinfo')->first()->schoolname);
                                            $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('left');
                                            $sheet->getStyle('C' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                            $sheet->getStyle('D' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                            $sheet->getStyle('E' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                                            $sheet->getStyle('H' . $startcellno)->getFont()->setBold(true);
                                            $sheet->setCellValue('H' . $startcellno, 'Region:');
                                            $sheet->getStyle('H' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->setCellValue('I' . $startcellno, DB::table('schoolinfo')->first()->regiontext ?? $schoolinfo->regDesc);
                                            $sheet->getStyle('I' . $startcellno)->getFont()->setUnderline(true);
                                            $sheet->getStyle('I' . $startcellno)->getAlignment()->setHorizontal('left');

                                            $startcellno += 1;
                                            $sheet->getStyle('A' . $startcellno)->getFont()->setBold(true);
                                            $sheet->setCellValue('A' . $startcellno, 'Address:');
                                            $sheet->getStyle('A' . $startcellno)->getAlignment()->setHorizontal('left');
                                            $sheet->setCellValue('C' . $startcellno, DB::table('schoolinfo')->first()->address);
                                            $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('left');
                                            $sheet->getStyle('C' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                            $sheet->getStyle('D' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                            $sheet->getStyle('E' . $startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                                            $sheet->getStyle('H' . $startcellno)->getFont()->setBold(true);
                                            $sheet->setCellValue('H' . $startcellno, 'NSTP Component:');
                                            $sheet->getStyle('H' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->setCellValue('I' . $startcellno, $subject->nstpcomponent);
                                            $sheet->getStyle('I' . $startcellno)->getFont()->setUnderline(true);
                                            $sheet->getStyle('I' . $startcellno)->getAlignment()->setHorizontal('left');

                                            $startcellno += 2;
                                            $sheet->mergeCells('A' . $startcellno . ':A' . ($startcellno + 1));
                                            $sheet->getStyle('A' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('A' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('A' . $startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('A' . $startcellno, 'No.');
                                            $sheet->getStyle('A' . $startcellno . ':A' . ($startcellno + 1))->applyFromArray($borderstyle);

                                            $sheet->mergeCells('B' . $startcellno . ':B' . ($startcellno + 1));
                                            $sheet->getStyle('B' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('B' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('B' . $startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('B' . $startcellno, 'Serial No.');
                                            $sheet->getStyle('B' . $startcellno . ':B' . ($startcellno + 1))->applyFromArray($borderstyle);

                                            $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                                            $sheet->getStyle('C' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->setCellValue('C' . $startcellno, 'Student Name');
                                            $sheet->getStyle('C' . $startcellno . ':E' . $startcellno)->applyFromArray($borderstyle);

                                            $sheet->mergeCells('F' . $startcellno . ':F' . ($startcellno + 1));
                                            $sheet->getStyle('F' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('F' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('F' . $startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('F' . $startcellno, 'Course/ Program');
                                            $sheet->getStyle('F' . $startcellno . ':F' . ($startcellno + 1))->applyFromArray($borderstyle);

                                            $sheet->mergeCells('G' . $startcellno . ':G' . ($startcellno + 1));
                                            $sheet->getStyle('G' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('G' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('G' . $startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('G' . $startcellno, 'Gender');
                                            $sheet->getStyle('G' . $startcellno . ':G' . ($startcellno + 1))->applyFromArray($borderstyle);

                                            $sheet->mergeCells('H' . $startcellno . ':H' . ($startcellno + 1));
                                            $sheet->getStyle('H' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('H' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('H' . $startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('H' . $startcellno, 'Birthdate');
                                            $sheet->getStyle('H' . $startcellno . ':H' . ($startcellno + 1))->applyFromArray($borderstyle);

                                            $sheet->mergeCells('I' . $startcellno . ':I' . ($startcellno + 1));
                                            $sheet->getStyle('I' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('I' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('I' . $startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('I' . $startcellno, 'Address');
                                            $sheet->getStyle('I' . $startcellno . ':I' . ($startcellno + 1))->applyFromArray($borderstyle);

                                            $sheet->mergeCells('J' . $startcellno . ':J' . ($startcellno + 1));
                                            $sheet->getStyle('J' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('J' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('J' . $startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('J' . $startcellno, 'Contact');
                                            $sheet->getStyle('J' . $startcellno . ':J' . ($startcellno + 1))->applyFromArray($borderstyle);

                                            $startcellno += 1;
                                            $sheet->getStyle('C' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('left');
                                            $sheet->setCellValue('C' . $startcellno, 'Surname');
                                            $sheet->getStyle('C' . $startcellno)->applyFromArray($borderstyle);
                                            $sheet->getStyle('D' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('D' . $startcellno)->getAlignment()->setHorizontal('left');
                                            $sheet->setCellValue('D' . $startcellno, 'First Name');
                                            $sheet->getStyle('D' . $startcellno)->applyFromArray($borderstyle);
                                            $sheet->getStyle('E' . $startcellno)->getFont()->setBold(true);
                                            $sheet->getStyle('E' . $startcellno)->getAlignment()->setHorizontal('left');
                                            $sheet->setCellValue('E' . $startcellno, 'Middle Name');
                                            $sheet->getStyle('E' . $startcellno)->applyFromArray($borderstyle);

                                            $startcellno += 1;
                                            foreach ($subject->students as $keystudent => $eachstudent) {
                                                $sheet->setCellValue('A' . $startcellno, $keystudent + 1);
                                                $sheet->getStyle('A' . $startcellno)->getAlignment()->setHorizontal('center');
                                                $sheet->getStyle('A' . $startcellno)->applyFromArray($borderstyle);


                                                $sheet->getStyle('B' . $startcellno)->applyFromArray($borderstyle);

                                                $sheet->setCellValue('C' . $startcellno, $eachstudent->lastname);
                                                $sheet->getStyle('C' . $startcellno)->applyFromArray($borderstyle);
                                                $sheet->setCellValue('D' . $startcellno, $eachstudent->firstname);
                                                $sheet->getStyle('D' . $startcellno)->applyFromArray($borderstyle);
                                                $sheet->setCellValue('E' . $startcellno, $eachstudent->middlename);
                                                $sheet->getStyle('E' . $startcellno)->applyFromArray($borderstyle);
                                                $sheet->setCellValue('F' . $startcellno, $eachstudent->courseabrv);
                                                $sheet->getStyle('F' . $startcellno)->applyFromArray($borderstyle);
                                                $sheet->getStyle('F' . $startcellno)->getAlignment()->setHorizontal('center');
                                                $sheet->setCellValue('G' . $startcellno, ucwords($eachstudent->gender));
                                                $sheet->getStyle('G' . $startcellno)->getAlignment()->setHorizontal('center');
                                                $sheet->getStyle('G' . $startcellno)->applyFromArray($borderstyle);
                                                $sheet->setCellValue('H' . $startcellno, $eachstudent->dob != null ? date('m/d/Y', strtotime($eachstudent->dob)) : ' ');
                                                $sheet->getStyle('H' . $startcellno)->applyFromArray($borderstyle);
                                                $sheet->getStyle('H' . $startcellno)->getAlignment()->setHorizontal('center');
                                                $sheet->setCellValue('I' . $startcellno, ucwords(strtolower($eachstudent->street . ', ' . $eachstudent->barangay . ', ' . $eachstudent->city . ', ' . $eachstudent->province)));
                                                $sheet->getStyle('I' . $startcellno)->applyFromArray($borderstyle);
                                                $sheet->setCellValue('J' . $startcellno, $eachstudent->contactno);
                                                $sheet->getStyle('J' . $startcellno)->getAlignment()->setHorizontal('center');
                                                $sheet->getStyle('J' . $startcellno)->applyFromArray($borderstyle);
                                                $startcellno += 1;
                                            }

                                            $sheet->mergeCells('B' . $startcellno . ':B' . ($startcellno + 1));
                                            $sheet->getStyle('B' . $startcellno . ':B' . ($startcellno + 1))->applyFromArray($borderstyle);
                                            $sheet->getStyle('B' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('B' . $startcellno)->getAlignment()->setVertical('center');
                                            $sheet->setCellValue('B' . $startcellno, 'Total');
                                            $sheet->getStyle('B' . $startcellno)->getFont()->setBold(true);

                                            $sheet->setCellValue('C' . $startcellno, 'Male:');
                                            $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('left');
                                            $sheet->setCellValue('D' . $startcellno, collect($subject->students)->whereIn('gender', ['MALE', 'male', 'Male'])->count());
                                            $sheet->getStyle('D' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('C' . $startcellno)->applyFromArray($borderstyle);
                                            $sheet->getStyle('D' . $startcellno)->applyFromArray($borderstyle);

                                            $startcellno += 1;
                                            $sheet->setCellValue('C' . $startcellno, 'Female:');
                                            $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('left');
                                            $sheet->setCellValue('D' . $startcellno, collect($subject->students)->whereIn('gender', ['FEMALE', 'female', 'Female'])->count());
                                            $sheet->getStyle('D' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('C' . $startcellno)->applyFromArray($borderstyle);
                                            $sheet->getStyle('D' . $startcellno)->applyFromArray($borderstyle);

                                            $startcellno += 3;
                                            $sheet->setCellValue('A' . $startcellno, 'Prepared by:');
                                            $sheet->getStyle('A' . $startcellno)->getAlignment()->setHorizontal('left');

                                            $sheet->mergeCells('C' . $startcellno . ':D' . $startcellno);
                                            $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->getStyle('C' . $startcellno)->getFont()->setBold(true);
                                            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                                                $sheet->setCellValue('C' . $startcellno, 'ELPIDIA G. SALAZAR, MAEd');
                                            }
                                            $sheet->getStyle('I' . $startcellno)->getAlignment()->setHorizontal('right');
                                            $sheet->setCellValue('I' . $startcellno, 'Certified Correct:');

                                            $startcellno += 1;
                                            $sheet->mergeCells('C' . $startcellno . ':D' . $startcellno);
                                            $sheet->getStyle('C' . $startcellno)->getAlignment()->setHorizontal('center');
                                            $sheet->setCellValue('C' . $startcellno, 'Registrar(Consultant)');

                                            $startcellno += 1;
                                            $sheet->setCellValue('A' . $startcellno, 'HEI Coordinator:');
                                            $sheet->getStyle('A' . $startcellno)->getAlignment()->setHorizontal('left');

                                            $sheet->getStyle('I' . $startcellno)->getAlignment()->setHorizontal('right');
                                            $sheet->setCellValue('I' . $startcellno, 'President/Authorized Representative of HEI:');



                                        }
                                    }


                                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                                    header('Content-Type: application/vnd.ms-excel');
                                    header('Content-Disposition: attachment; filename="' . $reportname . ' ' . $semester . ' ' . $sydesc . '.xlsx"');
                                    $writer->save("php://output");

                                    exit;
                                } else {
                                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                                    $sheet = $spreadsheet->getActiveSheet();
                                    $border = [
                                        'borders' => [
                                            'top' => [
                                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                            ],
                                            'bottom' => [
                                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                            ]
                                        ]
                                    ];
                                    $font_bold = [
                                        'font' => [
                                            'bold' => true,
                                        ]
                                    ];

                                    $setcellno = 2;
                                    $sheet->mergeCells('A' . $setcellno . ':G' . $setcellno);
                                    $sheet->setCellValue('A' . $setcellno, DB::table('schoolinfo')->first()->schoolname);
                                    $sheet->getStyle('A' . $setcellno)->getAlignment()->setHorizontal('center');
                                    $sheet->getStyle('A' . $setcellno)->applyFromArray($font_bold);
                                    $setcellno += 1;
                                    $sheet->mergeCells('A' . $setcellno . ':G' . $setcellno);
                                    $sheet->setCellValue('A' . $setcellno, DB::table('schoolinfo')->first()->address);
                                    $sheet->getStyle('A' . $setcellno)->getAlignment()->setHorizontal('center');
                                    $setcellno += 1;
                                    $sheet->mergeCells('A' . $setcellno . ':G' . $setcellno);
                                    $sheet->setCellValue('A' . $setcellno, $semester . ' ' . $sydesc);
                                    $sheet->getStyle('A' . $setcellno)->getAlignment()->setHorizontal('center');
                                    $setcellno += 1;
                                    $sheet->mergeCells('A' . $setcellno . ':G' . $setcellno);
                                    $sheet->setCellValue('A' . $setcellno, $subjects[0]->subjcode . ' - ' . $subjects[0]->subjectname);
                                    $sheet->getStyle('A' . $setcellno)->getAlignment()->setHorizontal('center');
                                    $sheet->getStyle('A' . $setcellno)->applyFromArray($font_bold);
                                    $setcellno += 1;
                                    $sheet->mergeCells('A' . $setcellno . ':G' . $setcellno);
                                    $sheet->setCellValue('A' . $setcellno, $reportname);
                                    $sheet->getStyle('A' . $setcellno)->getAlignment()->setHorizontal('center');
                                    $sheet->getStyle('A' . $setcellno)->applyFromArray($font_bold);

                                    $sheet->getColumnDimension('A')->setAutoSize(true);
                                    $sheet->getColumnDimension('B')->setAutoSize(true);
                                    $sheet->getColumnDimension('C')->setAutoSize(true);
                                    $sheet->getColumnDimension('D')->setAutoSize(true);
                                    $sheet->getColumnDimension('E')->setAutoSize(true);
                                    $sheet->getColumnDimension('F')->setAutoSize(true);
                                    $sheet->getColumnDimension('G')->setAutoSize(true);

                                    $setcellno += 2;
                                    $sheet->setCellValue('A' . $setcellno, 'I.D No.');
                                    $sheet->getStyle('A' . $setcellno)->applyFromArray($font_bold);
                                    $sheet->setCellValue('B' . $setcellno, 'Name');
                                    $sheet->getStyle('B' . $setcellno)->applyFromArray($font_bold);
                                    $sheet->setCellValue('C' . $setcellno, 'Sex');
                                    $sheet->getStyle('C' . $setcellno)->applyFromArray($font_bold);
                                    $sheet->setCellValue('D' . $setcellno, 'Year');
                                    $sheet->getStyle('D' . $setcellno)->applyFromArray($font_bold);
                                    $sheet->setCellValue('E' . $setcellno, 'Course');
                                    $sheet->getStyle('E' . $setcellno)->applyFromArray($font_bold);
                                    $sheet->setCellValue('F' . $setcellno, 'Grade');
                                    $sheet->getStyle('F' . $setcellno)->applyFromArray($font_bold);
                                    $sheet->setCellValue('G' . $setcellno, 'Units');
                                    $sheet->getStyle('G' . $setcellno)->applyFromArray($font_bold);

                                    $setcellno += 1;

                                    foreach ($subjects[0]->students as $key => $eachcourse) {
                                        $sheet->setCellValue('A' . $setcellno, $eachcourse->sid);
                                        $sheet->setCellValue('B' . $setcellno, $eachcourse->lastname . ', ' . $eachcourse->firstname . ' ' . $eachcourse->suffix . ' ' . $eachcourse->middlename);
                                        $sheet->setCellValue('C' . $setcellno, $eachcourse->gender);
                                        $sheet->setCellValue('D' . $setcellno, $eachcourse->yearlevel);
                                        $sheet->setCellValue('E' . $setcellno, $eachcourse->courseabrv);
                                        $sheet->setCellValue('F' . $setcellno, $reporttype == 'promotional' ? $eachcourse->eqgrade : '');
                                        $sheet->setCellValue('G' . $setcellno, $eachcourse->units ?? $subjects[0]->units);

                                        $setcellno += 1;
                                    }




                                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                                    header('Content-Type: application/vnd.ms-excel');
                                    header('Content-Disposition: attachment; filename="' . $reportname . ' ' . $semester . ' ' . $sydesc . '.xlsx"');
                                    $writer->save("php://output");

                                    exit;
                                }

                            }

                        } else {
                            if ($semid == null) {
                                $inputFileType = 'Xlsx';
                                $inputFileName = base_path() . '/public/excelformats/apmc/nstpformenrollmentlist.xlsx';
                                // $sheetname = 'Front';

                                /**  Create a new Reader of the type defined in $inputFileType  **/
                                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                                /**  Advise the Reader of which WorkSheets we want to load  **/
                                $reader->setLoadAllSheets();
                                /**  Load $inputFileName to a Spreadsheet Object  **/
                                $spreadsheet = $reader->load($inputFileName);
                                $sheet = $spreadsheet->getSheet(0);
                                $sheet->setTitle("Enrolment " . $request->get('nstpcomp'));

                                $borderstyle = [
                                    // 'alignment' => [
                                    //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                                    // ],
                                    'borders' => [
                                        'bottom' => [
                                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        ],
                                    ]
                                ];
                                //    return  $spreadsheet->getSheetByName();
                                $sheet->setCellValue('C9', DB::table('schoolinfo')->first()->schoolname);
                                $sheet->setCellValue('O9', DB::table('schoolinfo')->first()->schoolname);
                                $sheet->setCellValue('C10', DB::table('schoolinfo')->first()->address);
                                $sheet->setCellValue('H10', $request->get('nstpcomp'));
                                $sheet->setCellValue('H11', $sydesc);
                                $sheet->setCellValue('O10', DB::table('schoolinfo')->first()->address);
                                $sheet->setCellValue('T10', $request->get('nstpcomp'));
                                $sheet->setCellValue('T11', $sydesc);


                                $firstsem = collect($students)->where('semid', '1')->sortBy('lastname')->values();
                                $secondsem = collect($students)->where('semid', '2')->sortBy('lastname')->values();
                                $maxnum = max(count($firstsem), count($secondsem));


                                $sheet->setCellValue('C17', collect($firstsem)->where('gender', 'male')->count());
                                $sheet->setCellValue('C18', collect($firstsem)->where('gender', 'female')->count());
                                $sheet->setCellValue('O17', collect($secondsem)->where('gender', 'male')->count());
                                $sheet->setCellValue('O18', collect($secondsem)->where('gender', 'female')->count());
                                ;
                                $sheet->setCellValue('F24', date('m/d/Y'));

                                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {
                                    $sheet->setCellValue('C24', 'SERGIO GODOFREDO CENIZA');
                                    $sheet->setCellValue('C25', 'NSTP COORDINATOR');

                                    $sheet->setCellValue('I24', 'MERLIE S. SABUELO');
                                    $sheet->setCellValue('I25', 'SCHOOL REGISTRAR');
                                }

                                $sheet->setCellValue('O24', auth()->user()->name);
                                $sheet->setCellValue('O25', 'School Registrar');
                                $sheet->setCellValue('R24', date('m/d/Y'));

                                $sheet->getColumnDimension('A')->setWidth(10);
                                $sheet->getColumnDimension('M')->setWidth(10);

                                $startcellno = 15;
                                $totalcellno = 15;
                                $firstsemno = 1;
                                $secondsemno = 1;
                                $cellnoend = 15;
                                $sheet->getStyle('A15:K15')->applyFromArray($borderstyle);
                                $sheet->getStyle('M15:W15')->applyFromArray($borderstyle);
                                // $sheet->insertNewRowBefore(15);
                                for ($x = $totalcellno; $x < ($maxnum + 15); $x++) {
                                    $sheet->setCellValue('A' . $x, $firstsemno);
                                    $sheet->setCellValue('B' . $x, " ");
                                    $sheet->setCellValue('C' . $x, " ");
                                    $sheet->setCellValue('D' . $x, " ");
                                    $sheet->setCellValue('E' . $x, " ");
                                    $sheet->setCellValue('F' . $x, " ");
                                    $sheet->setCellValue('G' . $x, " ");
                                    $sheet->setCellValue('H' . $x, " ");
                                    $sheet->setCellValue('I' . $x, " ");
                                    $sheet->setCellValue('J' . $x, " ");
                                    $sheet->setCellValue('K' . $x, " ");


                                    $sheet->setCellValue('M' . $x, $secondsemno);
                                    $sheet->setCellValue('N' . $x, " ");
                                    $sheet->setCellValue('O' . $x, " ");
                                    $sheet->setCellValue('P' . $x, " ");
                                    $sheet->setCellValue('Q' . $x, " ");
                                    $sheet->setCellValue('R' . $x, " ");
                                    $sheet->setCellValue('S' . $x, " ");
                                    $sheet->setCellValue('T' . $x, " ");
                                    $sheet->setCellValue('U' . $x, " ");
                                    $sheet->setCellValue('V' . $x, " ");
                                    $sheet->setCellValue('W' . $x, " ");

                                    $sheet->getStyle('A' . $x . ':K' . $x)->applyFromArray($borderstyle);
                                    $sheet->getStyle('M' . $x . ':W' . $x)->applyFromArray($borderstyle);
                                    $firstsemno += 1;
                                    $secondsemno += 1;
                                    $cellnoend += 1;
                                    $sheet->insertNewRowBefore($x + 1);

                                }

                                $sheet->getStyle('A' . ($cellnoend - 1) . ':K' . ($cellnoend - 1))->applyFromArray($borderstyle);
                                $sheet->getStyle('M' . ($cellnoend - 1) . ':W' . ($cellnoend - 1))->applyFromArray($borderstyle);
                                $sheet->removeRow($cellnoend);

                                if (count($firstsem) > 0) {
                                    foreach ($firstsem as $eachstudfirst) {
                                        if ($eachstudfirst->lastname != null) {
                                            $sheet->setCellValue('B' . $startcellno, $eachstudfirst->lastname);
                                        }
                                        if ($eachstudfirst->firstname != null) {
                                            $sheet->setCellValue('C' . $startcellno, $eachstudfirst->firstname);
                                        }
                                        if ($eachstudfirst->middlename != null) {
                                            $sheet->setCellValue('D' . $startcellno, $eachstudfirst->middlename);
                                        }
                                        if ($eachstudfirst->coursename != null) {
                                            $sheet->setCellValue('E' . $startcellno, $eachstudfirst->coursename);
                                        }
                                        if ($eachstudfirst->gender != null) {
                                            $sheet->setCellValue('F' . $startcellno, strtoupper($eachstudfirst->gender[0]));
                                        }
                                        if ($eachstudfirst->dob != null) {
                                            $sheet->setCellValue('G' . $startcellno, date('m/d/Y', strtotime($eachstudfirst->dob)));
                                        }
                                        if ($eachstudfirst->city != null) {
                                            $sheet->setCellValue('H' . $startcellno, $eachstudfirst->city);
                                        }
                                        if ($eachstudfirst->province != null) {
                                            $sheet->setCellValue('I' . $startcellno, $eachstudfirst->province);
                                        }
                                        if ($eachstudfirst->contactno != null) {
                                            $sheet->setCellValue('J' . $startcellno, $eachstudfirst->contactno);
                                        }
                                        if ($eachstudfirst->email != null) {
                                            $sheet->setCellValue('K' . $startcellno, $eachstudfirst->email);
                                        }

                                        $startcellno += 1;
                                    }
                                }
                                $startcellno = 15;
                                if (count($secondsem) > 0) {
                                    foreach ($secondsem as $eachstudfirst) {
                                        if ($eachstudfirst->lastname != null) {
                                            $sheet->setCellValue('N' . $startcellno, $eachstudfirst->lastname);
                                        }
                                        if ($eachstudfirst->firstname != null) {
                                            $sheet->setCellValue('O' . $startcellno, $eachstudfirst->firstname);
                                        }
                                        if ($eachstudfirst->middlename != null) {
                                            $sheet->setCellValue('P' . $startcellno, $eachstudfirst->middlename);
                                        }
                                        if ($eachstudfirst->coursename != null) {
                                            $sheet->setCellValue('Q' . $startcellno, $eachstudfirst->coursename);
                                        }
                                        if ($eachstudfirst->gender != null) {
                                            $sheet->setCellValue('R' . $startcellno, strtoupper($eachstudfirst->gender[0]));
                                        }
                                        if ($eachstudfirst->dob != null) {
                                            $sheet->setCellValue('S' . $startcellno, date('m/d/Y', strtotime($eachstudfirst->dob)));
                                        }
                                        if ($eachstudfirst->city != null) {
                                            $sheet->setCellValue('T' . $startcellno, $eachstudfirst->city);
                                        }
                                        if ($eachstudfirst->province != null) {
                                            $sheet->setCellValue('U' . $startcellno, $eachstudfirst->province);
                                        }
                                        if ($eachstudfirst->contactno != null) {
                                            $sheet->setCellValue('V' . $startcellno, $eachstudfirst->contactno);
                                        }
                                        if ($eachstudfirst->email != null) {
                                            $sheet->setCellValue('W' . $startcellno, $eachstudfirst->email);
                                        }

                                        $startcellno += 1;
                                    }
                                }


                                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                                header('Content-Type: application/vnd.ms-excel');
                                header('Content-Disposition: attachment; filename="NSTP Enrollment List S.Y ' . $sydesc . '.xlsx"');
                                $writer->save("php://output");
                                exit;
                            }
                        }
                        // $pdf = PDF::loadview('registrar.summaries.studentlist.pdf_nstpenrollmentlist',compact('students','semester','sydesc'));

                        // return $pdf->stream('NSTP Enrollment List.pdf');
                    }
                }
            }
        }
    }
    public function alphaloadingindex(Request $request)
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

        if (Session::get('currentPortal') == 1) {

            $extends = "teacher.layouts.app";

        } elseif (Session::get('currentPortal') == 2) {

            $extends = "principalsportal.layouts.app2";

        } elseif (Session::get('currentPortal') == 3 || Session::get('currentPortal') == 8) {

            $extends = "registrar.layouts.app";

        } elseif (Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15) {

            $extends = "finance.layouts.app";

        } elseif (Session::get('currentPortal') == 6) {

            $extends = "adminPortal.layouts.app2";

        } elseif (Session::get('currentPortal') == 10 || $refid == 26) {

            $extends = "hr.layouts.app";

        } elseif (Session::get('currentPortal') == 12) {

            $extends = "adminITPortal.layouts.app";

        } elseif (Session::get('currentPortal') == 14) {

            $extends = "deanportal.layouts.app2";

        } elseif (Session::get('currentPortal') == 16) {

            $extends = "chairpersonportal.layouts.app2";

        } elseif (Session::get('currentPortal') == 18) {

            $extends = "ctportal.layouts.app2";

        } elseif (Session::get('currentPortal') == 29 || $refid == 29) {

            $extends = "idmanagement.layouts.app2";

        } else {

            $extends = "general.defaultportal.layouts.app";

        }
        $schoolyears = DB::table('sy')
            // ->where('deleted','0')
            ->get();

        $semesters = DB::table('semester')
            // ->where('deleted','0')
            ->get();

        $gradelevels = DB::table('gradelevel')
            ->where('deleted', '0')
            ->orderBy('sortid', 'asc')
            ->get();

        $courses = DB::table('college_courses')
            ->where('deleted', '0')
            ->orderBy('courseabrv', 'asc')
            ->get();


        if (
            strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct'
            || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc'
        ) {
            return view('registrar.summaries.summaryalphaloading')
                ->with('semesters', $semesters)
                ->with('schoolyears', $schoolyears)
                ->with('gradelevels', $gradelevels)
                ->with('courses', $courses)
                ->with('extends', $extends);
        } else {

            return view('registrar.summaries.alphaloading.index')
                ->with('semesters', $semesters)
                ->with('schoolyears', $schoolyears)
                ->with('gradelevels', $gradelevels)
                ->with('courses', $courses)
                ->with('extends', $extends);
        }
    }
    public function alphaloadinggetsection(Request $request)
    {
        // return $request->all();
        // if($request->ajax())
        // {
        $sections = DB::table('sections')
            ->select('sections.id', 'sectionname', 'levelid', 'acadprogid')
            ->join('gradelevel', 'sections.levelid', '=', 'gradelevel.id')
            ->where('sections.deleted', '0')
            ->get();

        $collegesections = DB::table('college_sections')
            ->select('college_sections.id', 'sectionDesc as sectionname', 'yearID as levelid', 'acadprogid')
            ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
            ->where('college_sections.deleted', '0')
            ->get();

        $allsections = collect();
        $allsections = $allsections->merge($sections);
        $allsections = $allsections->merge($collegesections);

        if ($request->get('acadprogid') != null && $request->get('acadprogid') != 0) {

            $allsections = $allsections->where('acadprogid', $request->get('acadprogid'))->values()->all();

        }
        if ($request->get('selectedgradelevel') != null && $request->get('selectedgradelevel') != 0) {

            $allsections = $allsections->where('levelid', $request->get('selectedgradelevel'))->values()->all();

        }
        return $allsections;
        // }
    }
    public function alphaloadinggetgradelevel(Request $request)
    {
        // return $request->all();
        if ($request->ajax()) {
            $gradelevels = DB::table('gradelevel')
                ->select('id', 'levelname')
                ->where('deleted', '0')
                ->where('acadprogid', $request->get('acadprogid'))
                ->get();

            return $gradelevels;
        }
    }
    public function alphaloadingfilter(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $selectedterm = $request->get('selectedterm');
        $acadprogid = $request->get('acadprogid');
        $selectedschoolyear = $request->get('selectedschoolyear');
        $selectedsemester = $request->get('selectedsemester');
        $selectedgradelevel = $request->get('selectedgradelevel');
        $selectedcourse = $request->get('selectedcourse');
        $selectedsection = $request->get('selectedsection');
        $selectedcollege = $request->get('selectedcollege');



        $allschedules = array();

        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
            // $classscheddetail_colleges = Db::table('schedulecoding')
            //     ->select('schedulecoding.id','schedulecoding.code','college_prospectus.subjectID as subjectid','college_prospectus.subjCode as subjcode','college_prospectus.subjDesc as subjectname',DB::raw('labunits + lecunits as units') ,'schedulecoding.id as prospectusid','schedulecoding.teacherid','teacher.lastname','teacher.firstname')
            //     ->join('college_prospectus','schedulecoding.subjid','=','college_prospectus.subjectID')
            //     ->leftJoin('teacher','schedulecoding.teacherid','teacher.id')
            //     ->where('schedulecoding.syid',$selectedschoolyear)
            //     ->where('schedulecoding.semid', $selectedsemester)
            //     ->where('schedulecoding.deleted','0')
            //     ->where('college_prospectus.deleted','0')
            //     ->distinct()
            //     ->get(); 
            $classscheddetail_colleges = Db::table('schedulecoding')
                ->select('schedulecoding.id', 'schedulecoding.code', 'college_subjects.id as subjectid', 'college_subjects.subjCode as subjcode', 'college_subjects.subjDesc as subjectname', DB::raw('labunits + lecunits as units'), 'schedulecoding.id as prospectusid', 'schedulecoding.teacherid', 'teacher.lastname', 'teacher.firstname', 'rooms.roomname')
                ->join('college_subjects', 'schedulecoding.subjid', '=', 'college_subjects.id')
                ->leftJoin('teacher', 'schedulecoding.teacherid', 'teacher.id')
                ->leftJoin('rooms', 'schedulecoding.roomid', '=', 'rooms.id')
                ->where('schedulecoding.syid', $selectedschoolyear)
                ->where('schedulecoding.semid', $selectedsemester)
                ->where('schedulecoding.deleted', '0')
                ->where('college_subjects.deleted', '0')
                ->distinct()
                ->get();
            // return 

            if ($request->has('schedid')) {
                $classscheddetail_colleges = collect($classscheddetail_colleges)->where('code', $request->get('schedid'))->values();
            }
            if ($request->has('from')) {
                $classscheddetail_colleges = collect($classscheddetail_colleges)->skip($request->get('from'))->take('50')->values();
            }

            $schedids = collect($classscheddetail_colleges)->pluck('id')->toArray();
            $schedulecodingdetails = DB::table('schedulecodingdetails')
                ->select('schedulecodingdetails.id', 'headerid', 'day', 'timestart as stime', 'timeend as etime', 'description')
                ->whereIn('schedulecodingdetails.headerid', $schedids)
                ->join('days', 'schedulecodingdetails.day', '=', 'days.id')
                ->where('deleted', '0')
                ->get();

            $students = DB::table('college_enrolledstud')
                ->select(
                    'college_enrolledstud.studid as id'
                )
                ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                ->where('college_enrolledstud.syid', $selectedschoolyear)
                ->where('college_enrolledstud.semid', $selectedsemester)
                ->where('college_enrolledstud.deleted', '0')
                ->distinct()
                ->get();


            $studsched = DB::table('college_studsched')
                ->select(
                    'college_studsched.studid',
                    'schedcodeid',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender'
                )
                ->join('studinfo', 'college_studsched.studid', '=', 'studinfo.id')
                ->whereIn('schedcodeid', $schedids)
                ->whereIn('college_studsched.studid', collect($students)->pluck('id')->toArray())
                ->where('college_studsched.deleted', '0')
                ->get();


        } elseif (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct') {


            $classscheddetail_colleges = Db::table('college_classsched')
                ->select('college_classsched.id', 'college_classsched.id as schedid', 'college_classsched.subjectID as subjectid', 'college_prospectus.subjCode as subjcode', 'college_prospectus.subjDesc as subjectname', 'college_prospectus.id as prospectusid', 'college_scheddetail.stime', 'college_scheddetail.etime', 'days.description as day', 'days.description', 'college_scheddetail.roomid', 'rooms.roomname', DB::raw('labunits + lecunits as units'), 'college_scheddetail.schedotherclass as term', 'college_classsched.teacherid', 'teacher.lastname', 'teacher.firstname', 'college_courses.courseDesc as deptcourse', 'college_courses.courseabrv', 'college_sections.sectionDesc as sectionname', 'college_sections.yearID as levelid', 'gradelevel.levelname', 'college_sections.id as sectionid', 'college_prospectus.courseID as courseid')
                ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                // ->join('college_year','college_sections.yearID','=','college_year.levelid')
                ->leftJoin('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                ->join('college_courses', 'college_prospectus.courseID', '=', 'college_courses.id')
                ->where('college_classsched.deleted', '0')
                // ->where('college_sections.yearID',$selectedgradelevel)
                ->where('college_classsched.syID', $selectedschoolyear)
                ->where('college_classsched.semesterID', $selectedsemester)
                ->join('college_scheddetail', 'college_classsched.id', 'college_scheddetail.headerid')
                ->leftJoin('teacher', 'college_classsched.teacherid', 'teacher.id')
                ->join('days', 'college_scheddetail.day', '=', 'days.id')
                ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                ->where('college_scheddetail.deleted', '0')
                ->distinct('subjectid')
                ->orderBy('subjcode', 'asc')
                ->get();

            // $classscheddetail_colleges = collect($classscheddetail_colleges)->whereIn('id',[1357,1368])->values()->all();
            $classscheddetail_colleges = collect($classscheddetail_colleges)->unique();
            if ($request->has('instructorid') && $request->get('instructorid') > 0) {
                $classscheddetail_colleges = collect($classscheddetail_colleges)->where('teacherid', $request->get('instructorid'))->values();
            }
            if ($request->has('schedid')) {
                $classscheddetail_colleges = collect($classscheddetail_colleges)->where('subjcode', $request->get('schedid'))->values();
            }
            // return $classscheddetail_colleges;
            if ($selectedterm > 0) {
                $classscheddetail_colleges_collect = collect($classscheddetail_colleges)->where('term', $selectedterm)->values();

                if (count($classscheddetail_colleges_collect) == 0) {
                    if ($selectedterm == 1) {
                        $classscheddetail_colleges = collect($classscheddetail_colleges)->where('term', "1st Term")->values();
                    } elseif ($selectedterm == 2) {
                        $classscheddetail_colleges = collect($classscheddetail_colleges)->where('term', '2nd Term')->values();
                    }

                } else {
                    $classscheddetail_colleges = $classscheddetail_colleges_collect;
                }
            }

            if ($selectedcourse > 0) {
                $classscheddetail_colleges = collect($classscheddetail_colleges)->where('courseid', $selectedcourse)->values();
            }
            if ($request->get('teacherid') > 0) {
                $classscheddetail_colleges = collect($classscheddetail_colleges)->where('teacherid', $request->get('teacherid'))->values();
                $classscheddetail_colleges = collect($classscheddetail_colleges)->unique('id', 'courseid', 'prospectusid', 'sectionid')->values()->all();
            }
            if ($request->has('from')) {
                $classscheddetail_colleges = collect($classscheddetail_colleges)->skip($request->get('from'))->take('50')->values();
            }
            $schedulecodingdetails = DB::table('college_scheddetail')
                ->select('college_scheddetail.*', 'college_scheddetail.headerID as headerid', 'days.description')
                ->join('days', 'college_scheddetail.day', '=', 'days.id')
                ->whereIn('headerID', collect($classscheddetail_colleges)->pluck('id'))
                ->get();

            $students = DB::table('college_enrolledstud')
                ->select(
                    'studinfo.id',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'college_enrolledstud.semid',
                    'college_enrolledstud.sectionid',
                    'college_courses.id as courseid',
                    'courseabrv as coursename',
                    'courseabrv',
                    'college_year.id as yearlevel',
                    'modeoflearning.description as mol'
                )
                ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                ->leftJoin('modeoflearning', 'studinfo.mol', '=', 'modeoflearning.id')
                ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                ->join('college_year', 'college_enrolledstud.yearLevel', '=', 'college_year.levelid')
                ->where('college_enrolledstud.syid', $selectedschoolyear)
                ->where('college_enrolledstud.semid', $selectedsemester)
                ->where('college_enrolledstud.deleted', 0)
                ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                ->distinct('id')
                ->get();

            // return $request->all();
            if ($selectedgradelevel > 0) {
                $students = collect($students)->where('yearLevel', $selectedgradelevel)->values();
            }
            if ($selectedcourse > 0) {
                $students = collect($students)->where('courseid', $selectedcourse)->values();
            }



            $studsched = DB::table('college_studsched')
                ->select('college_prospectus.id as prospectusid', 'college_studsched.studid', 'college_studsched.schedcodeid')
                ->whereIn('college_studsched.studid', collect($students)->pluck('id'))
                ->join('college_classsched', 'college_studsched.schedid', 'college_classsched.id')
                ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                ->where('college_classsched.syID', $selectedschoolyear)
                ->where('college_classsched.semesterID', $selectedsemester)
                ->where('college_studsched.deleted', 0)
                ->where('college_classsched.deleted', 0)
                ->where('college_prospectus.deleted', 0)
                // ->whereIn('college_prospectus.id', [collect($classscheddetail_colleges)->pluck('prospectusid')])
                ->get();

            // return collect($studsched)->where('studid', 2863);
            $students = collect($students)->whereIn('id', collect($studsched)->pluck('studid'))->values()->all();

            // return $students;


        } else {

            // return 'asdasd';  
            //spct-apmc
            if ($request->has('acadprogid')) {
                if ($request->get('acadprogid') < 1) {
                    $acadprogid = 6;
                } else {
                    $acadprogid = $request->get('acadprogid');
                }
            } else {
                $acadprogid = 6;
            }
            if ($acadprogid == '6') {
                // return $request->all();
                $students = DB::table('college_enrolledstud')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'college_enrolledstud.semid',
                        'college_enrolledstud.sectionid',
                        'college_courses.id as courseid',
                        'courseabrv as coursename',
                        'courseabrv',
                        'college_year.id as yearlevel',
                        'college_enrolledstud.yearlevel as levelid',
                        'modeoflearning.description as mol'
                    )
                    ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                    ->leftJoin('modeoflearning', 'studinfo.mol', '=', 'modeoflearning.id')
                    // ->join('college_studsched','college_enrolledstud.studid','college_studsched.studid')
                    // ->join('college_classsched','college_studsched.schedid','college_classsched.id')
                    // ->join('college_prospectus','college_classsched.subjectID','=','college_prospectus.id')
                    ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                    ->join('college_year', 'college_enrolledstud.yearLevel', '=', 'college_year.levelid')
                    ->where('college_enrolledstud.syid', $selectedschoolyear)
                    ->where('college_enrolledstud.semid', $selectedsemester)
                    // ->where('college_classsched.syID', $selectedschoolyear)
                    // ->where('college_classsched.semesterID', $selectedsemester)
                    ->where('college_enrolledstud.deleted', 0)
                    // ->where('college_studsched.deleted', 0)
                    // ->where('college_classsched.deleted', 0)
                    // ->where('college_prospectus.deleted', 0)
                    ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                    ->distinct('id')
                    ->get();
                // return $students;
                if ($selectedgradelevel > 0) {
                    $students = collect($students)->where('levelid', $selectedgradelevel)->values();
                }
                if ($selectedcourse > 0) {
                    $students = collect($students)->where('courseid', $selectedcourse)->values();
                }
                // return $students;
                $studsched = DB::table('college_loadsubject')
                    ->select('college_prospectus.id as prospectusid', 'college_loadsubject.studid', 'college_loadsubject.schedid')
                    ->whereIn('college_loadsubject.studid', collect($students)->pluck('id'))
                    ->join('college_classsched', 'college_loadsubject.schedid', 'college_classsched.id')
                    ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                    ->where('college_classsched.syID', $selectedschoolyear)
                    ->where('college_classsched.semesterID', $selectedsemester)
                    ->where('college_loadsubject.deleted', 0)
                    ->where('college_classsched.deleted', 0)
                    ->where('college_prospectus.deleted', 0)
                    ->get();
                 

                // return $studsched;

                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                    $classscheddetail_colleges = Db::table('college_classsched')
                        ->select('college_classsched.id', 'college_classsched.id as schedid', 'college_classsched.subjectID as subjectid', 'college_prospectus.subjCode as subjcode', 'college_prospectus.subjDesc as subjectname', 'college_prospectus.id as prospectusid', 'college_scheddetail.stime', 'college_scheddetail.etime', 'days.description as day', 'days.description', 'college_scheddetail.roomid', 'rooms.roomname', DB::raw('labunits + lecunits as units'), 'college_scheddetail.schedotherclass as term', 'college_classsched.teacherid', 'teacher.lastname', 'teacher.firstname', 'college_courses.courseDesc as deptcourse', 'college_courses.courseabrv', 'college_sections.sectionDesc as sectionname', 'college_sections.yearID as levelid', 'gradelevel.levelname', 'college_prospectus.courseID as courseid')
                        ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                        ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                        // ->join('college_year','college_sections.yearID','=','college_year.levelid')
                        ->leftJoin('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->join('college_courses', 'college_prospectus.courseID', '=', 'college_courses.id')
                        ->where('college_classsched.deleted', '0')
                        // ->where('college_sections.yearID',$selectedgradelevel)
                        ->where('college_classsched.syID', $selectedschoolyear)
                        ->where('college_classsched.semesterID', $selectedsemester)
                        ->join('college_scheddetail', 'college_classsched.id', 'college_scheddetail.headerid')
                        ->leftJoin('teacher', 'college_classsched.teacherid', 'teacher.id')
                        ->join('days', 'college_scheddetail.day', '=', 'days.id')
                        ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                        ->where('college_scheddetail.deleted', '0')
                        ->distinct('subjectid')
                        ->orderBy('subjcode', 'asc')
                        ->get();

                } else {
                    // dd($selectedsemester, $selectedschoolyear);
                    $classscheddetail_colleges = Db::table('college_classsched')
                        ->where('college_sections.deleted', 0)
                        ->where('college_classsched.deleted', 0)
                        ->where('college_prospectus.deleted', 0)
                        ->where('college_courses.deleted', 0)
                        ->where('college_classsched.syID', $selectedschoolyear)
                        ->where('college_classsched.semesterID', $selectedsemester)
                        ->where('college_sections.syID', $selectedschoolyear)
                        ->where('college_sections.semesterID', $selectedsemester)
                        // ->where('college_prospectus.yearID', $selectedschoolyear)
                        ->where('college_prospectus.semesterID', $selectedsemester)
                        ->select('college_classsched.id', 'college_classsched.id as schedid', 'college_classsched.subjectID as subjectid', 'college_prospectus.subjCode as subjcode', 'college_prospectus.subjDesc as subjectname', 'college_prospectus.id as prospectusid', 'college_scheddetail.stime', 'college_scheddetail.etime', 'days.description as day', 'days.description', 'college_scheddetail.roomid', 'rooms.roomname', DB::raw('labunits + lecunits as units'), 'college_scheddetail.schedotherclass as term', 'college_instructor.teacherid', 'teacher.lastname', 'teacher.firstname', 'college_courses.courseDesc as deptcourse', 'college_courses.courseabrv', 'college_sections.sectionDesc as sectionname', 'college_sections.yearID as levelid', 'gradelevel.levelname', 'college_sections.id as sectionid', 'college_prospectus.courseID as courseid')
                        ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                        ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                        // ->join('college_year','college_sections.yearID','=','college_year.levelid')
                        ->leftJoin('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->join('college_courses', 'college_sections.courseID', '=', 'college_courses.id')
                        ->where('college_classsched.deleted', '0')
                        // ->where('college_sections.yearID',$selectedgradelevel)
                        ->join('college_scheddetail', 'college_classsched.id', 'college_scheddetail.headerid')
                        // ->leftJoin('teacher', 'college_classsched.teacherid', 'teacher.id')
                        ->join('days', 'college_scheddetail.day', '=', 'days.id')
                        ->leftJoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                        ->leftJoin('college_instructor', function($join) {
                            $join->on('college_classsched.id', '=', 'college_instructor.classschedid')
                                  ->where('college_instructor.deleted', 0);
                        })
                        ->leftjoin('teacher', function($join){
                                $join->on('college_instructor.teacherid', '=', 'teacher.id');
                                $join->where('teacher.deleted', 0);;
                            })
                        ->where('college_scheddetail.deleted', '0')
                        ->distinct('subjectid')
                        ->orderBy('subjcode', 'asc')
                        ->get();
                }

                // return $classscheddetail_colleges;

                // $classscheddetail_colleges = collect($classscheddetail_colleges)->whereIn('id',[1357,1368])->values()->all();
                $classscheddetail_colleges = collect($classscheddetail_colleges)->unique();
                if ($request->has('instructorid') && $request->get('instructorid') > 0) {
                    $classscheddetail_colleges = collect($classscheddetail_colleges)->where('teacherid', $request->get('instructorid'))->values();
                }
                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'spct') {
                    if ($request->has('schedid')) {
                        $classscheddetail_colleges = collect($classscheddetail_colleges)->where('id', $request->get('schedid'))->values();
                    }
                }

                if ($selectedterm > 0) {
                    $classscheddetail_colleges_collect = collect($classscheddetail_colleges)->where('term', $selectedterm)->values();

                    if (count($classscheddetail_colleges_collect) == 0) {
                        if ($selectedterm == 1) {
                            $classscheddetail_colleges = collect($classscheddetail_colleges)->where('term', "1st Term")->values();
                        } elseif ($selectedterm == 2) {
                            $classscheddetail_colleges = collect($classscheddetail_colleges)->where('term', '2nd Term')->values();
                        }

                    } else {
                        $classscheddetail_colleges = $classscheddetail_colleges_collect;
                    }
                }

                if ($selectedcourse > 0) {
                    $classscheddetail_colleges = collect($classscheddetail_colleges)->where('courseid', $selectedcourse)->values();
                }
                if ($request->get('teacherid') > 0) {
                    $classscheddetail_colleges = collect($classscheddetail_colleges)->where('teacherid', $request->get('teacherid'))->values();
                    $classscheddetail_colleges = collect($classscheddetail_colleges)->unique('id', 'courseid', 'prospectusid', 'sectionid')->values()->all();
                }
                if ($request->has('from')) {
                    $classscheddetail_colleges = collect($classscheddetail_colleges)->skip($request->get('from'))->take('50')->values();
                }
                $schedulecodingdetails = DB::table('college_scheddetail')
                    ->select('college_scheddetail.*', 'college_scheddetail.headerID as headerid', 'days.description')
                    ->join('days', 'college_scheddetail.day', '=', 'days.id')
                    ->whereIn('headerID', collect($classscheddetail_colleges)->pluck('id'))
                    ->get();
                // return $classscheddetail_colleges;
            } elseif ($acadprogid == '5') {
                $students = DB::table('sh_enrolledstud')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'sh_enrolledstud.levelid as yearlevel',
                        'sh_enrolledstud.sectionid',
                        'modeoflearning.description as mol'
                    )
                    ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
                    ->leftJoin('modeoflearning', 'studinfo.mol', '=', 'modeoflearning.id')
                    ->where('sh_enrolledstud.syid', $selectedschoolyear)
                    ->where('sh_enrolledstud.semid', $selectedsemester)
                    ->where('sh_enrolledstud.deleted', 0)
                    ->whereIn('sh_enrolledstud.studstatus', [1, 2, 4])
                    ->distinct('id')
                    ->get();

                $classscheds = DB::table('sh_classsched')
                    ->select('sh_classsched.*', 'sh_classsched.id as schedid', 'sh_classsched.glevelid as yearLevel', 'sh_subjects.subjcode', 'sh_subjects.subjtitle as subjdesc', 'sections.sectionname', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix')
                    ->join('sh_subjects', 'sh_classsched.subjid', '=', 'sh_subjects.id')
                    ->join('sections', 'sh_classsched.sectionid', '=', 'sections.id')
                    ->leftJoin('teacher', 'sh_classsched.teacherid', '=', 'teacher.id')
                    ->where('sh_classsched.syid', $selectedschoolyear)
                    ->where('sh_classsched.semid', $selectedsemester)
                    ->where('sh_classsched.deleted', 0)
                    ->where('sh_subjects.deleted', 0)
                    ->get();


                if ($selectedgradelevel > 0) {
                    $students = collect($students)->where('yearLevel', $selectedgradelevel)->values();
                    $classscheds = collect($classscheds)->where('yearLevel', $selectedgradelevel)->values();
                }
                if ($selectedsection > 0) {
                    $students = collect($students)->where('sectionid', $selectedgradelevel)->values();
                    $classscheds = collect($classscheds)->where('sectionid', $selectedsection)->values();
                }
                if (count($classscheds) > 0) {
                    foreach ($classscheds as $classsched) {
                        $classscheddetails = DB::table('sh_classscheddetail')
                            ->select('sh_classscheddetail.*', 'days.description', 'rooms.roomname')
                            ->join('days', 'sh_classscheddetail.day', '=', 'days.id')
                            ->leftJoin('rooms', 'sh_classscheddetail.roomid', '=', 'rooms.id')
                            ->where('sh_classscheddetail.headerid', $classsched->id)
                            ->where('sh_classscheddetail.deleted', '0')
                            ->get();

                        if (count($classscheddetails) == 0) {
                            $classsched->stime = '';
                            $classsched->etime = '';
                            $classsched->days = '';
                            $classsched->roomname = '';
                        } else {
                            $daydescription = '';
                            $classsched->stime = date('h:i A', strtotime($classscheddetails[0]->stime));
                            $classsched->etime = date('h:i A', strtotime($classscheddetails[0]->etime));

                            foreach ($classscheddetails as $classscheddetail) {
                                if (strtolower($classscheddetail->description) == 'thursday' || strtolower($classscheddetail->description) == 'saturday') {
                                    if (strpos($daydescription, $classscheddetail->description[0] . $classscheddetail->description[1]) !== false) {
                                    } else {
                                        $daydescription .= ($classscheddetail->description[0] . $classscheddetail->description[1]);
                                    }
                                } else {
                                    if (strpos($daydescription, $classscheddetail->description[0]) !== false) {
                                    } else {
                                        $daydescription .= ($classscheddetail->description[0]);
                                    }
                                }
                            }
                            $classsched->days = $daydescription;
                            $classsched->roomname = $classscheddetails[0]->roomname;
                        }

                        $classsched->numstudents = collect($students)->where('sectionid', $classsched->sectionid)->count();
                        $classsched->students = collect($students)->where('sectionid', $classsched->sectionid)->values();

                        $classsched->teachername = $classsched->lastname . ', ' . $classsched->firstname . ' ' . $classsched->middlename[0] . '. ' . $classsched->suffix;
                        // assignsubjs
                    }
                }

                if ($selectedgradelevel > 0) {
                    $students = collect($students)->where('yearLevel', $selectedgradelevel)->values();
                    $classscheds = collect($classscheds)->where('yearLevel', $selectedgradelevel)->values();
                }
                if ($selectedsection > 0) {
                    $students = collect($students)->where('sectionid', $selectedgradelevel)->values();
                    $classscheds = collect($classscheds)->where('sectionid', $selectedsection)->values();
                }

                if (!$request->has('export')) {
                    return view('registrar.summaries.alphaloading.view_shs')
                        ->with('acadprogid', $acadprogid)
                        ->with('students', $students)
                        ->with('classscheds', $classscheds);
                    exit;
                } else {
                    if ($request->get('exporttype') == 'pdfstudents') {
                        if (count($classscheds) > 0) {
                            foreach ($classscheds as $schedule) {
                                if (count($schedule->students) > 0) {
                                    foreach ($schedule->students as $student) {
                                        $student->lastname = strtoupper($student->lastname);
                                        $student->firstname = strtoupper($student->firstname);
                                        // $student->lastname =  ucwords(mb_strtolower($student->lastname, 'UTF-8'));
                                        $student->gender = strtolower($student->gender);
                                        $student->fullname = $student->lastname . ' ' . $student->firstname;
                                    }
                                    $schedule->students = collect($schedule->students)->sortBy('fullname')->values();
                                }
                            }
                        }
                        // return $schedules;
                        $schedules = collect($classscheds)->where('id', $request->get('schedid'))->values()->all();

                        ini_set("memory_limit", "-1");
                        set_time_limit(0);
                        $pdf = PDF::loadview('registrar/summaries/alphaloading/pdf_students_jhs', compact('schedules', 'sydesc', 'semester', 'students', 'selectedterm'));

                        return $pdf->stream('Alpha Loading - List of Students.pdf');
                    }
                }
            } elseif ($acadprogid == '4') {

                $students = DB::table('enrolledstud')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'enrolledstud.levelid as yearlevel',
                        'enrolledstud.sectionid',
                        'modeoflearning.description as mol'
                    )
                    ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
                    ->leftJoin('modeoflearning', 'studinfo.mol', '=', 'modeoflearning.id')
                    ->where('enrolledstud.syid', $selectedschoolyear)
                    ->where('enrolledstud.deleted', 0)
                    ->whereIn('enrolledstud.studstatus', [1, 2, 4])
                    ->distinct('id')
                    ->get();

                $assignsubjs = DB::table('assignsubj')
                    ->select('assignsubj.*', 'assignsubj.glevelid as yearLevel', 'assignsubjdetail.subjid', 'assignsubjdetail.teacherid', 'teacher.lastname', 'teacher.firstname')
                    ->join('assignsubjdetail', 'assignsubj.id', '=', 'assignsubjdetail.headerid')
                    ->join('teacher', 'assignsubjdetail.teacherid', '=', 'teacher.id')
                    ->where('assignsubj.syid', $selectedschoolyear)
                    ->where('assignsubj.deleted', '0')
                    ->where('assignsubjdetail.deleted', '0')
                    ->get();

                $classscheds = DB::table('classsched')
                    ->select('classsched.*', 'classsched.id as schedid', 'classsched.glevelid as yearLevel', 'subjects.subjcode', 'subjects.subjdesc', 'sections.sectionname')
                    ->join('subjects', 'classsched.subjid', '=', 'subjects.id')
                    ->join('sections', 'classsched.sectionid', '=', 'sections.id')
                    ->where('classsched.syid', $selectedschoolyear)
                    ->where('classsched.deleted', 0)
                    ->where('subjects.deleted', 0)
                    ->get();


                if ($selectedgradelevel > 0) {
                    $students = collect($students)->where('yearLevel', $selectedgradelevel)->values();
                    $classscheds = collect($classscheds)->where('yearLevel', $selectedgradelevel)->values();
                    $assignsubjs = collect($assignsubjs)->where('yearLevel', $selectedgradelevel)->values();
                }
                if ($selectedsection > 0) {
                    $students = collect($students)->where('sectionid', $selectedgradelevel)->values();
                    $classscheds = collect($classscheds)->where('sectionid', $selectedsection)->values();
                    $assignsubjs = collect($assignsubjs)->where('sectionid', $selectedsection)->values();
                }
                if (count($classscheds) > 0) {
                    foreach ($classscheds as $classsched) {
                        $classscheddetails = DB::table('classscheddetail')
                            ->select('classscheddetail.*', 'days.description', 'rooms.roomname')
                            ->join('days', 'classscheddetail.days', '=', 'days.id')
                            ->leftJoin('rooms', 'classscheddetail.roomid', '=', 'rooms.id')
                            ->where('classscheddetail.headerid', $classsched->id)
                            ->where('classscheddetail.deleted', '0')
                            ->get();

                        if (count($classscheddetails) == 0) {
                            $classsched->stime = '';
                            $classsched->etime = '';
                            $classsched->days = '';
                            $classsched->roomname = '';
                        } else {
                            $daydescription = '';
                            $classsched->stime = date('h:i A', strtotime($classscheddetails[0]->stime));
                            $classsched->etime = date('h:i A', strtotime($classscheddetails[0]->etime));

                            foreach ($classscheddetails as $classscheddetail) {
                                if (strtolower($classscheddetail->description) == 'thursday' || strtolower($classscheddetail->description) == 'saturday') {
                                    if (strpos($daydescription, $classscheddetail->description[0] . $classscheddetail->description[1]) !== false) {
                                    } else {
                                        $daydescription .= ($classscheddetail->description[0] . $classscheddetail->description[1]);
                                    }
                                } else {
                                    if (strpos($daydescription, $classscheddetail->description[0]) !== false) {
                                    } else {
                                        $daydescription .= ($classscheddetail->description[0]);
                                    }
                                }
                            }
                            $classsched->days = $daydescription;
                            $classsched->roomname = $classscheddetails[0]->roomname;
                        }

                        $classsched->numstudents = collect($students)->where('sectionid', $classsched->sectionid)->count();
                        $classsched->students = collect($students)->where('sectionid', $classsched->sectionid)->values();

                        $teachernames = collect($assignsubjs)->where('subjid', $classsched->subjid)->where('sectionid', $classsched->sectionid)->values();

                        if (count($teachernames) == 0) {
                            $classsched->teachername = '';
                        } else {
                            $classsched->teachername = $teachernames[0]->lastname . ', ' . $teachernames[0]->firstname;
                        }
                        // assignsubjs
                    }
                }

                if ($selectedgradelevel > 0) {
                    $students = collect($students)->where('yearLevel', $selectedgradelevel)->values();
                    $classscheds = collect($classscheds)->where('yearLevel', $selectedgradelevel)->values();
                    $assignsubjs = collect($assignsubjs)->where('yearLevel', $selectedgradelevel)->values();
                }
                if ($selectedsection > 0) {
                    $students = collect($students)->where('sectionid', $selectedgradelevel)->values();
                    $classscheds = collect($classscheds)->where('sectionid', $selectedsection)->values();
                    $assignsubjs = collect($assignsubjs)->where('sectionid', $selectedsection)->values();
                }

                if (!$request->has('export')) {
                    return view('registrar.summaries.alphaloading.view_jhs')
                        ->with('acadprogid', $acadprogid)
                        ->with('students', $students)
                        ->with('classscheds', $classscheds)
                        ->with('assignsubjs', $assignsubjs);
                    exit;
                } else {
                    if ($request->get('exporttype') == 'pdfstudents') {
                        if (count($classscheds) > 0) {
                            foreach ($classscheds as $schedule) {
                                if (count($schedule->students) > 0) {
                                    foreach ($schedule->students as $student) {
                                        $student->lastname = strtoupper($student->lastname);
                                        $student->firstname = strtoupper($student->firstname);
                                        // $student->lastname =  ucwords(mb_strtolower($student->lastname, 'UTF-8'));
                                        $student->gender = strtolower($student->gender);
                                        $student->fullname = $student->lastname . ' ' . $student->firstname;
                                    }
                                    $schedule->students = collect($schedule->students)->sortBy('fullname')->values();
                                }
                            }
                        }
                        $schedules = collect($classscheds)->where('id', $request->get('schedid'))->values()->all();

                        ini_set("memory_limit", "-1");
                        set_time_limit(0);
                        $pdf = PDF::loadview('registrar/summaries/alphaloading/pdf_students_jhs', compact('schedules', 'sydesc', 'semester', 'students', 'selectedterm'));

                        return $pdf->stream('Alpha Loading - List of Students.pdf');
                    }
                }
            }
            if (count($students) > 0) {
                foreach ($students as $student) {
                    $student->sortname = $student->lastname . ' ' . $student->firstname;
                }
            }
            $students = collect($students)->sortBy('sortname');

        }

        if ($selectedterm == 0) {
            $selectedterm = 'All Terms';
        } elseif ($selectedterm == 1) {
            $selectedterm = '1st Term';
        } else {
            $selectedterm = '2nd Term';
        }
        // return $classscheddetail_colleges;
        // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
        // {
        if (count($classscheddetail_colleges) > 0) {
            foreach ($classscheddetail_colleges as $schedule_collegekey => $schedule_college) {
                // return $schedule_college->id;
                $schedinfo = collect($schedulecodingdetails)->where('headerid', $schedule_college->id)->values();
                // return $schedinfo;
                if (count($schedinfo) > 0) {
                    $schedule_college->stime = $schedinfo[0]->stime != null ? date('h:i A', strtotime($schedinfo[0]->stime)) : null;
                    $schedule_college->etime = $schedinfo[0]->etime != null ? date('h:i A', strtotime($schedinfo[0]->etime)) : null;
                    $daydescription = '';
                    foreach ($schedinfo as $eachsched) {
                        if (strtolower($eachsched->description) == 'thursday' || strtolower($eachsched->description) == 'saturday') {
                            if (strpos($daydescription, $eachsched->description[0] . $eachsched->description[1]) !== false) {
                            } else {
                                $daydescription .= ($eachsched->description[0] . $eachsched->description[1]);
                            }
                        } else {
                            if (strpos($daydescription, $eachsched->description[0]) !== false) {
                            } else {
                                $daydescription .= ($eachsched->description[0]);
                            }
                        }
                    }
                    $schedule_college->description = $daydescription;

                } else {
                    $schedule_college->stime = '';
                    $schedule_college->etime = '';
                    $schedule_college->description = '';
                }
                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                    if ($request->has('exporttype')) {
                        $studentlist = collect($studsched)->where('schedcodeid', $schedule_college->id)->values();
                        if (count($studentlist) > 0) {
                            foreach ($studentlist as $eachstudent) {
                                // $studinfo = DB::table('studinfo')
                                //     ->where('id', $eachstudent->studid)
                                //     ->first();
                                // $eachstudent->lastname = $studinfo->lastname;
                                // $eachstudent->firstname = $studinfo->firstname;
                                // $eachstudent->middlename = $studinfo->middlename;
                                // $eachstudent->suffix = $studinfo->suffix;
                                // $eachstudent->gender = $studinfo->gender;

                                $enrollmentdetail = DB::table('college_enrolledstud')
                                    ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                                    ->where('studid', $eachstudent->studid)
                                    // ->whereIn('college_enrolledstud.studstatus',[1,2,4])           
                                    ->where('college_enrolledstud.syid', $selectedschoolyear)
                                    ->where('college_enrolledstud.semid', $selectedsemester)
                                    ->where('college_enrolledstud.deleted', '0')
                                    ->where('college_courses.deleted', '0')
                                    ->first();

                                $eachstudent->courseabrv = $enrollmentdetail->courseabrv ?? 0;
                                $eachstudent->coursename = $enrollmentdetail->courseabrv ?? 0;
                                $eachstudent->deptcourse = $enrollmentdetail->courseabrv ?? 0;
                                $eachstudent->yearlevel = $enrollmentdetail->yearLevel ?? 0;
                                $eachstudent->collegeid = $enrollmentdetail->collegeid ?? 0;
                                $eachstudent->display = 0;


                            }
                        }
                        $schedule_college->numstudents = count($studentlist);
                        $schedule_college->studentlist = $studentlist;
                    } else {
                        $studentlist = collect($studsched)->where('schedcodeid', $schedule_college->id)->count();

                        $schedule_college->numstudents = $studentlist;
                        $schedule_college->studentlist = $studentlist;
                    }
                } else {
                    // return $studsched;
                    $studentlist = collect($studsched)->where('prospectusid', $schedule_college->prospectusid)->values();
                    $schedule_college->studentlist = collect($students)->whereIn('id', collect($studentlist)->pluck('studid'))->values();
                    $schedule_college->students = collect($students)->whereIn('id', collect($studentlist)->pluck('studid'))->values();
                    // return $studentlist;
                    // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
                    // {
                    //     $schedule_college->studentlist = collect($students)->whereIn('id',collect($studentlist)->pluck('studid'))->values();
                    //     $schedule_college->students = collect($students)->whereIn('id',collect($studentlist)->pluck('studid'))->values();
                    // }
                    // elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                    // {
                    //     $schedule_college->studentlist = collect($students)->whereIn('id',collect($studentlist)->pluck('studid'))->values();
                    //     $schedule_college->students = collect($students)->whereIn('id',collect($studentlist)->pluck('studid'))->values();
                    // }
                    // else
                    // // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                    // {
                    // //     return collect($schedule_college);
                    // //     $schedule_college->studentlist = collect($students)->whereIn('id',collect($studentlist)->pluck('studid'))->values();
                    // //     $schedule_college->students = collect($students)->whereIn('id',collect($studentlist)->pluck('studid'))->values();
                    // // }else{
                    //     $schedule_college->studentlist = collect($students)->where('sectionid',$schedule_college->sectionid)->whereIn('id',collect($studentlist)->pluck('studid'))->values();
                    //     $schedule_college->students = collect($students)->where('sectionid',$schedule_college->sectionid)->whereIn('id',collect($studentlist)->pluck('studid'))->values();
                    // }
                    // if(collect($studentlist)->where('id', 2863)->count()>0)
                    // {
                    //     return collect($schedule_college);
                    // }

                    $schedule_college->numstudents = count($schedule_college->students);

                }

            }
        }
        // return count($students);
        $allschedules = collect();
        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
            $allschedules = $classscheddetail_colleges;
        } else {
            $allschedules = collect($classscheddetail_colleges)->unique('schedid', 'subjectid')->values()->all();
        }
        if (count($allschedules) > 0 && strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct') {
            foreach ($allschedules as $allschedule) {
                $allschedule->groupby = $allschedule->subjcode . ' ' . $allschedule->description . ' ' . $allschedule->stime . '-' . $allschedule->etime . ' ' . $allschedule->term;
            }
        }

        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct') {
            if ($request->has('groupby')) {
                $allschedules = collect($allschedules)->where('groupby', $request->get('groupby'));
            }
        }

        if ($request->has('export')) {
            $schoolinfo = Db::table('schoolinfo')
                ->select(
                    'schoolinfo.schoolid',
                    'schoolinfo.schoolname',
                    'schoolinfo.authorized',
                    'schoolinfo.picurl',
                    'refcitymun.citymunDesc',
                    'schoolinfo.district',
                    'schoolinfo.address',
                    'refregion.regDesc'
                )
                ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
                ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
                ->first();


            $sydesc = DB::table('sy')
                ->where('id', $selectedschoolyear)
                ->first()->sydesc;
            $semester = DB::table('semester')
                ->where('id', $selectedsemester)
                ->first()->semester;
            if ($selectedterm == 1) {
                $selectedterm = 'First Term';
            }
            if ($selectedterm == 2) {
                $selectedterm = 'Second Term';
            }
            if ($request->get('exporttype') == 'pdfstudents') {
                $schedules = collect($allschedules)->values();
                // return $schedules;
                // $schedules = collect($allschedules)->where('code', $request->get('schedid'))->values();
                // $students = collect();
                if (count($schedules) > 0) {
                    foreach ($schedules as $schedule) {
                        if (count($schedule->studentlist) > 0) {
                            foreach ($schedule->studentlist as $student) {
                                $student->lastname = strtoupper($student->lastname);
                                $student->firstname = strtoupper($student->firstname);
                                // $student->lastname =  ucwords(mb_strtolower($student->lastname, 'UTF-8'));
                                $student->gender = strtolower($student->gender);
                                $student->fullname = $student->lastname . ' ' . $student->firstname;
                            }
                            $schedule->studentlist = collect($schedule->studentlist)->sortBy('fullname')->values();
                        }
                    }
                }
                ini_set("memory_limit", "-1");
                set_time_limit(0);


                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct') {
                    $students = collect();
                    foreach ($schedules as $eachsched) {

                        $students = $students->merge($eachsched->students);
                    }
                    $students = $students->sortby('fullname');
                    $students = $students->unique();

                } else {
                    $students = $schedules[0]->studentlist;
                }
                $template = 'list';
                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi') {
                    $template = 'bygender';
                }

                $pdf = PDF::loadview('registrar/summaries/pdf_alphaloadingstudents', compact('schedules', 'sydesc', 'semester', 'students', 'selectedterm', 'template'));

                return $pdf->stream('Alpha Loading - List of Students.pdf');
            } elseif ($request->get('exporttype') == 'pdfstudentsgs') {
                // return $allschedules;
                $grades = DB::table('college_studsched')
                    ->join('college_classsched', function ($join) use ($selectedschoolyear, $selectedsemester) {
                        $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                        $join->where('college_classsched.deleted', 0);
                        $join->where('syID', $selectedschoolyear);
                        $join->where('semesterID', $selectedsemester);
                    })
                    ->join('college_prospectus', function ($join) use ($selectedschoolyear, $selectedsemester) {
                        $join->on('college_classsched.subjectID', '=', 'college_prospectus.id');
                        $join->where('college_prospectus.deleted', 0);
                    })
                    ->where('schedstatus', '!=', 'DROPPED')
                    ->where('college_studsched.deleted', 0)
                    ->whereIn('college_studsched.studid', collect($students)->pluck('id'))
                    ->whereIn('college_prospectus.id', collect($allschedules)->pluck('subjectid'))
                    // ->where('college_studentprospectus.studid',$studentid)
                    ->leftJoin('college_studentprospectus', 'college_prospectus.id', '=', 'college_studentprospectus.prospectusID')
                    ->select(
                        'subjCode as subjcode',
                        'subjDesc as subjdesc',
                        'labunits',
                        'lecunits',
                        'fg as subjgrade'
                        ,
                        'college_studentprospectus.studid as studentprospectusstudid',
                        'college_prospectus.id as subjid'
                    )
                    ->orderBy('subjCode')
                    ->distinct()
                    ->get();
                // return $grades;
                $schedules = collect($allschedules)->where('schedid', $request->get('schedid'))->values();
                $students = array();
                if (count($schedules) > 0) {
                    $students = collect($schedules)->pluck('students')->flatten()->toArray();
                }

                $sydesc = DB::table('sy')
                    ->where('id', $selectedschoolyear)
                    ->first()->sydesc;
                // return $grades;
                $semester = $request->get('selectedsemester');

                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc') {
                    // return $grades;
                    $pdf = PDF::loadview('registrar/summaries/alphaloading/pdf_gradesheet_sbc', compact('schedules', 'sydesc', 'students', 'semester', 'grades'));
                } else {
                    $pdf = PDF::loadview('registrar/summaries/alphaloading/pdf_gradesheet', compact('schedules', 'sydesc', 'students', 'semester', 'grades'));
                }

                return $pdf->stream('Alpha Loading.pdf');
            } elseif ($request->get('exporttype') == 'pdf') {
                $schedules = $allschedules;

                $teachers = array();

                if ($request->get('teacherid') == 0) {
                    if (count($schedules) > 0) {
                        foreach ($schedules as $eachsched) {
                            if ($eachsched->id > 0) {
                                array_push($teachers, (object) array(
                                    'id' => $eachsched->teacherid,
                                    'lastname' => $eachsched->lastname,
                                    'firstname' => $eachsched->firstname
                                ));
                            }
                        }
                    }
                    $teachers = collect($teachers)->unique()->values()->all();
                }
                // return count($schedules);
                // $schedules =collect($allschedules)->take(100);
                if (count($schedules) > 0) {
                    foreach ($schedules as $schedule) {
                        if (count($schedule->studentlist) > 0) {
                            foreach ($schedule->studentlist as $student) {
                                $student->gender = strtolower($student->gender);
                            }
                        }
                    }
                }

                // return $schedules;
                $pdf = PDF::loadview('registrar/summaries/pdf_alphaloading', compact('schedules', 'sydesc', 'semester', 'students', 'selectedterm'));

                return $pdf->stream('Alpha Loading.pdf');
                
            } elseif ($request->get('exporttype') == 'pdflist') {
                $schedules = $allschedules;

                $teachers = array();

                if ($request->get('teacherid') == 0) {
                    if (count($schedules) > 0) {
                        foreach ($schedules as $eachsched) {
                            if ($eachsched->id > 0) {
                                array_push($teachers, (object) array(
                                    'id' => $eachsched->teacherid,
                                    'lastname' => $eachsched->lastname,
                                    'firstname' => $eachsched->firstname
                                ));
                            }
                        }
                    }
                    $teachers = collect($teachers)->unique()->values()->all();
                }


                $schedules = collect($schedules)->except(['studentlist', 'students']);
                $schedules = $schedules->all();
                // return $schedules;
                // if(count($schedules)>0)
                // {
                //     foreach($schedules as $schedule)
                //     {
                //         if(count($schedule->studentlist)>0)
                //         {
                //             foreach($schedule->studentlist as $student)
                //             {
                //                 $student->gender = strtolower($student->gender);
                //             }
                //         }
                //     }
                // }


                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc') {
                    $pdf = PDF::loadview('registrar/summaries/pdf_alphaloadinglist_sbc', compact('schedules', 'sydesc', 'semester', 'selectedterm'));

                    $pdf->getDomPDF()->set_option("enable_php", true);
                    return $pdf->stream('Class Listings.pdf');
                } else {

                    //return $schedules;
                    $pdf = PDF::loadview('registrar/summaries/pdf_alphaloadinglist', compact('schedules', 'sydesc', 'semester', 'selectedterm'));

                    $pdf->getDomPDF()->set_option("enable_php", true);
                    return $pdf->stream('Class List.pdf');
                }
                 
            } else {
                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                    $departmentname = 'Class List';
                    if ($selectedcollege > 0) {
                        $departmentname = DB::table('college_colleges')->where('id', $selectedcollege)->first()->collegeabrv;
                    }
                    $levelname = ' ';
                    if ($selectedgradelevel > 0) {
                        $levelname = DB::table('gradelevel')->where('id', $selectedgradelevel)->first()->levelname;
                    }
                    // return $departmentname;
                    // return $allschedules;
                    $inputFileType = 'Xlsx';
                    $inputFileName = base_path() . '/public/excelformats/dcc/alpha_loading.xlsx';
                    // $sheetname = 'Front';

                    /**  Create a new Reader of the type defined in $inputFileType  **/
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Advise the Reader of which WorkSheets we want to load  **/
                    $reader->setLoadAllSheets();
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($inputFileName);
                    $sheet = $spreadsheet->getSheet(0);
                    $sheet->setTitle($departmentname);
                    $borderstyle = [
                        // 'alignment' => [
                        //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        // ],
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ]
                    ];
                    // return $allschedules;
                    $sheet->setCellValue('H3', date('m/d/Y'));
                    $sheet->setCellValue('A4', $semester . ' ' . $sydesc . ' ALPHA LOADING');
                    $startcellno = 7;
                    if (count($allschedules) > 0) {
                        foreach ($allschedules as $schedule) {
                            $sheet->insertNewRowBefore($startcellno + 1);
                            // try{
                            $sheet->setCellValue('A' . $startcellno, $schedule->code);
                            $sheet->setCellValue('B' . $startcellno, $schedule->subjcode);
                            $sheet->setCellValue('C' . $startcellno, $schedule->subjectname);
                            $sheet->setCellValue('D' . $startcellno, $departmentname);
                            $sheet->setCellValue('E' . $startcellno, $levelname);
                            $sheet->setCellValue('F' . $startcellno, $schedule->stime);
                            $sheet->setCellValue('G' . $startcellno, $schedule->etime);
                            $sheet->setCellValue('H' . $startcellno, $schedule->description);
                            $sheet->setCellValue('I' . $startcellno, $schedule->units);
                            $sheet->setCellValue('J' . $startcellno, $schedule->numstudents);
                            $sheet->setCellValue('K' . $startcellno, $schedule->lastname . ', ' . $schedule->firstname);
                            $sheet->setCellValue('L' . $startcellno, $schedule->roomname);

                            $startcellno += 1;
                            // }catch(\Exception $e)
                            // {
                            //     return collect($schedule);
                            // }
                        }
                    }

                    // $sheet->getColumnDimension('A')->setWidth(10);
                    // $sheet->getColumnDimension('M')->setWidth(10);

                    // $sheet->getStyle('A15:K15')->applyFromArray($borderstyle);
                    // $sheet->getStyle('M15:W15')->applyFromArray($borderstyle);


                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="Alpha Loading - ' . $sydesc . '.xlsx"');
                    $writer->save("php://output");

                    exit;

                } else {
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    ;
                    $sheet = $spreadsheet->getActiveSheet();
                    $borderstyle = [
                        // 'alignment' => [
                        //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        // ],
                        'borders' => [
                            'allborders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                // 'color' => ['argb' => 'FFFF0000'],
                            ],
                        ]
                    ];
                    $font_bold = [
                        'font' => [
                            'bold' => true,
                        ]
                    ];

                    $sheet->mergeCells('A1:L1');
                    $sheet->setCellValue('A1', $schoolinfo->schoolname);
                    $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('A1')->applyFromArray($font_bold);

                    $sheet->mergeCells('A2:L2');
                    $sheet->setCellValue('A2', $schoolinfo->address);
                    $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                    $sheet->mergeCells('I3:J3');
                    $sheet->setCellValue('I3', date('m/d/Y'));
                    $sheet->getStyle('I3')->getAlignment()->setHorizontal('center');

                    $sydesc = DB::table('sy')
                        ->where('id', $selectedschoolyear)
                        ->first()->sydesc;

                    if ($selectedsemester == null) {
                        $semdesc = "";
                    } else {
                        $semdesc = DB::table('semester')
                            ->where('id', $selectedsemester)
                            ->first()->semester;
                    }
                    $sheet->mergeCells('A4:L4');
                    $sheet->setCellValue('A4', $semdesc . ' ' . $sydesc . ' ALPHA LOADING');
                    $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');

                    if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc') {
                        $sheet->setCellValue('A6', '#');
                        $sheet->setCellValue('B6', 'Subject');
                        $sheet->mergeCells('C6:E6');
                        $sheet->setCellValue('C6', 'Description');
                        $sheet->setCellValue('F6', 'TimeBegin');
                        $sheet->setCellValue('G6', 'TimeEnd');
                        $sheet->setCellValue('H6', 'Days');
                        $sheet->setCellValue('I6', 'Room');
                        $sheet->setCellValue('J6', 'Units');
                        $sheet->setCellValue('K6', 'Enrolled');
                        $sheet->setCellValue('L6', 'Instructor');
                        $sheet->getStyle('A6:L6')->applyFromArray($font_bold);
                        $sheet->getStyle('A6:L6')->getAlignment()->setHorizontal('center');

                        $startcellno = 7;
                        $countno = 1;
                        if (count($allschedules) > 0) {
                            foreach ($allschedules as $sched) {
                                // return collect($sched);
                                $sheet->setCellValue('A' . $startcellno, $countno);
                                $sheet->setCellValue('B' . $startcellno, $sched->subjcode);
                                $sheet->mergeCells('C' . $startcellno . ':E' . $startcellno);
                                $sheet->setCellValue('C' . $startcellno, $sched->subjectname);
                                $sheet->setCellValue('F' . $startcellno, $sched->stime);
                                $sheet->setCellValue('G' . $startcellno, $sched->etime);
                                $sheet->setCellValue('H' . $startcellno, $sched->days);
                                $sheet->setCellValue('I' . $startcellno, $sched->roomname);
                                $sheet->setCellValue('J' . $startcellno, $sched->units);
                                $sheet->setCellValue('K' . $startcellno, $sched->enrolled);
                                $sheet->setCellValue('L' . $startcellno, $sched->teachername);
                                $countno += 1;
                                $startcellno += 1;
                            }
                        }
                    } else {
                        $sheet->setCellValue('A6', '#');
                        $sheet->setCellValue('B6', 'Subject');
                        $sheet->setCellValue('C6', 'Description');
                        $sheet->setCellValue('D6', 'Course/Dept');
                        $sheet->setCellValue('E6', 'Section');
                        $sheet->setCellValue('F6', 'TimeBegin');
                        $sheet->setCellValue('G6', 'TimeEnd');
                        $sheet->setCellValue('H6', 'Days');
                        $sheet->setCellValue('I6', 'Room');
                        $sheet->setCellValue('J6', 'Units');
                        $sheet->setCellValue('K6', 'Enrolled');
                        $sheet->setCellValue('L6', 'Instructor');
                        $sheet->getStyle('A6:L6')->applyFromArray($font_bold);
                        $sheet->getStyle('A6:L6')->getAlignment()->setHorizontal('center');

                        $startcellno = 7;
                        $countno = 1;
                        if (count($allschedules) > 0) {
                            foreach ($allschedules as $sched) {
                                // return collect($sched);
                                $sheet->setCellValue('A' . $startcellno, $countno);
                                $sheet->setCellValue('B' . $startcellno, $sched->subjcode);
                                $sheet->setCellValue('C' . $startcellno, $sched->subjectname);
                                $sheet->setCellValue('D' . $startcellno, $sched->deptcourse);
                                $sheet->setCellValue('E' . $startcellno, $sched->sectionname);
                                $sheet->setCellValue('F' . $startcellno, date('h:i A ', strtotime($sched->stime)));
                                $sheet->setCellValue('G' . $startcellno, date('h:i A ', strtotime($sched->etime)));
                                $sheet->setCellValue('H' . $startcellno, $sched->description);
                                $sheet->setCellValue('I' . $startcellno, $sched->roomname);
                                $sheet->setCellValue('J' . $startcellno, $sched->units);
                                $sheet->setCellValue('K' . $startcellno, $sched->numstudents);
                                $sheet->setCellValue('L' . $startcellno, $sched->teachername ?? $sched->lastname . ', ' . $sched->firstname);
                                $countno += 1;
                                $startcellno += 1;
                            }
                        }
                    }
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="' . $semdesc . ' ' . $sydesc . ' ALPHA LOADING.xlsx"');
                    $writer->save("php://output");
                }
            }

        } else {
            // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
            // {
            //     $allschedules = collect($allschedules)->groupBy('subjcode','stime','etime')->values();
            // }else{
            //     $allschedules = $allschedules->groupBy('code','stime','etime','teacherid');
            // }
            // $finalscheds = array();
            // $allschedules = $allschedules->groupBy('code','stime','etime','teacherid');
            // $allschedules = collect($allschedules)->take(10)->values();
            // if(count($allschedules)>0)
            // {
            //     foreach($allschedules as $eachsched)
            //     {
            //         if (is_object($eachsched)) {
            //             array_push($finalscheds,collect($eachsched)->values()->toArray());
            //         }else{
            //             array_push($finalscheds,$eachsched);
            //         }
            //     }
            // }
            // $allschedules = $finalscheds;
            // $allschedules = collect($allschedules)->unique()->values()->all();
            $teachers = array();

            if ($request->get('teacherid') == 0) {
                if (count($allschedules) > 0) {
                    foreach ($allschedules as $eachsched) {
                        if ($eachsched->id > 0) {
                            array_push($teachers, (object) array(
                                'id' => $eachsched->teacherid,
                                'lastname' => $eachsched->lastname,
                                'firstname' => $eachsched->firstname
                            ));
                        }
                    }
                }
                $teachers = collect($teachers)->unique()->values()->all();
            }
            $allschedules = collect($allschedules)->unique()->values()->all();
            // return $allschedules;

            $collegeinstructors = DB::table('teacher')
                ->select('id', 'userid', 'lastname', 'firstname', 'middlename', 'suffix', 'usertypeid')
                ->where('deleted', '0')
                ->where('isactive', '1')
                ->get();

            $selectedteacherid = 0;
            if ($request->has('instructorid')) {
                $selectedteacherid = $request->get('instructorid');
            }
            // return 'registrar.summaries.alphaloadtable';
            // return $allschedules;
            // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
            // {
            //     return view('registrar.summaries.alphaloading.results_sbc')
            //         ->with('selectedteacherid',$selectedteacherid)
            //         ->with('collegeinstructors',$collegeinstructors)
            //         ->with('teachers',$teachers)
            //         ->with('allschedules',$allschedules);
            // }else{
            // return $allschedules;
            return view('registrar.summaries.alphaloadtable')
                ->with('selectedteacherid', $selectedteacherid)
                ->with('collegeinstructors', $collegeinstructors)
                ->with('teachers', $teachers)
                ->with('allschedules', $allschedules);
            // }
        }
    }
    public function alphaloadingassigninstructor(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        try {
            DB::table('college_classsched')
                ->where('id', $request->get('schedid'))
                ->update([
                    'teacherID' => $request->get('teacherid'),
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);

            return 1;

        } catch (\Exception $error) {
            return 0;
        }
    }
    public function prospectusevalsheet(Request $request)
    {
        if (!$request->has('action')) {

            // $students = DB::table('colle')
            return view('registrar.summaries.prospectusevalsheet.index')
                // ->with('allschedules',$allschedules)
            ;
        } else {

        }
    }
    public function oe(Request $request)
    {
        if ($request->get('action') == 'index') {
            return view('registrar.summaries.onlineenrollment.index');
        } else {
            $students = collect();

            $onlinestuds = DB::table("student_pregistration")
                ->select('studid', 'semid')
                // ->select('studinfo.id','studinfo.lastname','studinfo.middlename','studinfo.firstname','studinfo.suffix','studinfo.gender')
                // ->join('studinfo','student_pregistration.studid','=','studinfo.id')
                ->where('student_pregistration.syid', $request->get('syid'))
                ->where('student_pregistration.deleted', '0')
                // ->where('student_pregistration.status','APPROVED')
                ->get();

            $stud1 = DB::table('enrolledstud')
                ->select('studinfo.id', 'studinfo.sid', 'studinfo.lastname', 'studinfo.middlename', 'studinfo.firstname', 'studinfo.suffix', 'studinfo.gender', 'gradelevel.id as levelid', 'gradelevel.levelname', 'gradelevel.acadprogid', 'sections.sectionname')
                ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->leftJoin('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                ->where('enrolledstud.syid', $request->get('syid'))
                ->whereIn('enrolledstud.studid', collect($onlinestuds)->pluck('studid'))
                ->whereIn('enrolledstud.studstatus', [1, 2, 4])
                ->get();

            $stud2 = DB::table('sh_enrolledstud')
                ->select('studinfo.id', 'studinfo.sid', 'studinfo.lastname', 'studinfo.middlename', 'studinfo.firstname', 'studinfo.suffix', 'studinfo.gender', 'gradelevel.id as levelid', 'gradelevel.levelname', 'gradelevel.acadprogid', 'sections.sectionname')
                ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->leftJoin('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                ->where('sh_enrolledstud.syid', $request->get('syid'))
                ->whereIn('sh_enrolledstud.studid', collect($onlinestuds)->where('semid', $request->get('semid'))->pluck('studid'))
                ->whereIn('sh_enrolledstud.studstatus', [1, 2, 4])
                ->get();

            $stud3 = DB::table('college_enrolledstud')
                ->select('studinfo.id', 'studinfo.sid', 'studinfo.lastname', 'studinfo.middlename', 'studinfo.firstname', 'studinfo.suffix', 'studinfo.gender', 'gradelevel.id as levelid', 'gradelevel.levelname', 'gradelevel.acadprogid', 'college_sections.sectionDesc as sectionname')
                ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                ->leftJoin('college_sections', 'college_enrolledstud.sectionID', '=', 'college_sections.id')
                ->where('college_enrolledstud.syid', $request->get('syid'))
                ->whereIn('college_enrolledstud.studid', collect($onlinestuds)->where('semid', $request->get('semid'))->pluck('studid'))
                ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                ->get();

            $students = $students->merge($stud1);
            $students = $students->merge($stud2);
            $students = $students->merge($stud3);

            $students = $students->unique('id');
            $students = $students->sortBy('lastname');
            if ($request->get('department') == 'all') {

            } elseif ($request->get('department') == 'basiced') {
                $students = $students->whereIn('acadprogid', [2, 3, 4, 5]);
            } else {
                $students = $students->where('acadprogid', $request->get('department'));
            }

            $students = $students->values();
            if ($request->get('action') == 'generate') {
                return view('registrar.summaries.onlineenrollment.resultstable')
                    ->with('students', $students);
            } else {
                $sydesc = Db::table('sy')->where('id', $request->get('syid'))->first()->sydesc;

                if ($request->has('department')) {
                    if ($request->get('department') == 'all') {
                        $selectedacadprog = 'All Programs';
                    } elseif ($request->get('department') == 'basiced') {
                        $selectedacadprog = 'Basic Education Programs';
                    } else {
                        $selectedacadprog = Db::table('academicprogram')->where('id', $request->get('department'))->first()->progname . ' Program';
                    }
                } else {
                    $selectedacadprog = 'All Programs';
                }
                foreach ($students as $stud) {
                    $stud->gender = strtoupper($stud->gender);
                }
                $semester = DB::table('semester')->where('id', $request->get('semid'))->first()->semester;
                $pdf = PDF::loadview('registrar/summaries/onlineenrollment/pdf_oe', compact('students', 'sydesc', 'selectedacadprog', 'semester'))->setPaper('8.5x11');

                return $pdf->stream('Online Enrolled Students.pdf');
            }

        }
    }

}
