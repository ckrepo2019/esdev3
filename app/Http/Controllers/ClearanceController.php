<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class ClearanceController extends Controller
{
    public function index(Request $request) // Singatory Page
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;
        if (Session::get('currentPortal') == '1') {

            $extends = "teacher.layouts.app";
        } elseif (Session::get('currentPortal') == '2') {

            $extends = "principalsportal.layouts.app2";
        } elseif (Session::get('currentPortal') == '3' || Session::get('currentPortal') == '8') {

            $extends = "registrar.layouts.app";
        } elseif (Session::get('currentPortal') == '4' || Session::get('currentPortal') == '15') {

            $extends = "finance.layouts.app";
        } elseif (Session::get('currentPortal') == '6') {

            $extends = "adminPortal.layouts.app2";
        } elseif (Session::get('currentPortal') == '10' || $refid == 26) {

            $extends = "hr.layouts.app";
        } elseif (Session::get('currentPortal') == '12') {

            $extends = "adminITPortal.layouts.app";
        } elseif (Session::get('currentPortal') == 14) {

            $extends = "deanportal.layouts.app2";
        } else {
            $extends = "general.defaultportal..layouts.app";
        }

        return view('clearance.index')
            ->with('extends', $extends);
    }

    public function approval(Request $request) // Clearance Approval Page
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

        if (Session::get('currentPortal') == '1') {
            $extends = "teacher.layouts.app";
        } elseif (Session::get('currentPortal') == '2') {

            $extends = "principalsportal.layouts.app2";
        } elseif (Session::get('currentPortal') == '3' || Session::get('currentPortal') == '8') {

            $extends = "registrar.layouts.app";
        } elseif (Session::get('currentPortal') == '4' || Session::get('currentPortal') == '15') {

            $extends = "finance.layouts.app";
        } elseif (Session::get('currentPortal') == '6') {

            $extends = "adminPortal.layouts.app2";
        } elseif (Session::get('currentPortal') == '10' || $refid == 26) {

            $extends = "hr.layouts.app";
        } elseif (Session::get('currentPortal') == '12') {

            $extends = "adminITPortal.layouts.app";
        } elseif (Session::get('currentPortal') == 14) {

            $extends = "deanportal.layouts.app2";
        } else {
            $extends = "general.defaultportal..layouts.app";
        }

        return view('clearance.clearanceapproval')
            ->with('extends', $extends);
    }

    public static function systatus(Request $request)
    { // get school year
        $sy = $request->get('syid');

        $getsy = DB::table('sy')
            ->where('id', $sy)
            ->select('id', 'sydesc', 'isactive', 'ended')
            ->get();

        return $getsy;
    }

    public static function signatories_list(Request $request) // get signatories
    { // table data
        $sy = $request->get('syid');
        $acadprog = $request->get('acadprogid');
        $acadterm = $request->get('acadterm');

        $term_table = DB::table('clearance_signatory') // 1st table clearance_acadterm
            ->join('teacher', function ($join) use ($sy) {
                $join->on('clearance_signatory.teacherid', '=', 'teacher.id') // 2nd table academicterm_acadprog
                    ->where('clearance_signatory.deleted', 0)
                    ->where('clearance_signatory.syid', '=', $sy)
                    ->select(
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.suffix'
                    );
            })
            ->join('clearance_acadterm', 'clearance_signatory.termid', '=', 'clearance_acadterm.id') // 3rd table academicprogram
            ->where('clearance_signatory.deleted', 0)
            ->select(
                'clearance_acadterm.term'
            )
            ->select(
                'clearance_signatory.*',
                'teacher.lastname',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.suffix',
                'clearance_acadterm.term'
            )
            ->when($acadprog, function ($query, $acadprog) {
                return $query->whereRaw("json_extract(clearance_signatory.acadprogid, '$[*]') LIKE ?", ["%$acadprog%"]);
            })
            ->when($sy, function ($query, $sy) {
                return $query->where('clearance_signatory.syid', $sy);
            })
            ->when($acadterm, function ($query, $acadterm) {
                return $query->where('clearance_signatory.termid', $acadterm);
            })
            ->get();

        return $term_table;
    }

    public static function acadprogid_list(Request $request) // get academic program
    {
        $sy = $request->get('syid');

        $academicprog = DB::table('academicprogram')
            ->select(
                'id',
                'progname',
                'acadprogcode'
            )
            ->get();

        return $academicprog;
    }

    public function save_signatory(Request $request)
    { // save signatory and updates
        $title = $request->get('title');
        $name = $request->get('name');
        $department = $request->get('department');
        $acadprog = $request->get('acadprog');
        $acadterm = $request->get('acadterm');
        $syid = $request->get('syid');
        $selectedid = $request->get('selectedid');

        $serializedNumbers = json_encode($acadprog);
        try {
            if ($selectedid == null) {
                $signatory = DB::table('clearance_signatory') // insert signatory data
                    ->insert([
                        'title' => $title,
                        'teacherid' => $name,
                        'departmentid' => $department,
                        'acadprogid' => $serializedNumbers,
                        'termid' => $acadterm,
                        'syid' => $syid,
                        'deleted' => 0,
                        'isactive' => 0,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

                return array(
                    (object) [
                        'status' => 1,
                        'data' => 'Created Successfully!',
                        'info' => $signatory
                    ]
                );
            } else {
                $signatory = DB::table('clearance_signatory') // update signatory data
                    ->where('id', $selectedid)
                    ->update([
                        'title' => $title,
                        'teacherid' => $name,
                        'departmentid' => $department,
                        'acadprogid' => $serializedNumbers,
                        'termid' => $acadterm,
                        'syid' => $syid,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

                return array(
                    (object) [
                        'status' => 1,
                        'data' => 'Updated Successfully!',
                    ]
                );
            }
        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public static function delete_signatory(Request $request) // remove signatory
    { // signatory deletion
        $selectedid = $request->get('selectedid');

        try {
            DB::table('clearance_signatory')
                ->where('id', $selectedid)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('clearance_stud_status') // delete the clearance_stud_status of deleted signatory
                ->where('clearance_type', $selectedid)
                ->update([
                    'deleted' => 1,
                ]);

            return array(
                (object) [
                    'status' => 1,
                    'data' => 'Deleted Successfully!',
                ]
            );
        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public static function activate_clearance(Request $request)
    { // enable clearance for subteacher and adviser
        $status = $request->get('isactive');
        $cler_acadprogid = $request->get('cler_acadprogid');
        $clearancefor = $request->get('clearancefor');

        try {
            if ($clearancefor == 'SUBJECT TEACHER') {
                $enableclearance = DB::table('clearance_acadterm_acadprog')
                    ->where('id', $cler_acadprogid)
                    ->update([
                        'subjteacher' => $status,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    ]);
            }

            if ($clearancefor == 'CLASS ADVISER') {
                $enableclearance = DB::table('clearance_acadterm_acadprog')
                    ->where('id', $cler_acadprogid)
                    ->update([
                        'classadviser' => $status,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    ]);
            }
        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public function getenablestatus(Request $request)
    { // enable status for tsubteacher and adviser clearance
        $sy = $request->get('syid');
        $acadterm = $request->get('acadterm');

        $getacdprog = DB::table('clearance_acadterm_acadprog')
            ->join('clearance_acadterm', function ($join) use ($sy, $acadterm) {
                $join->on('clearance_acadterm_acadprog.termid', '=', 'clearance_acadterm.id')
                    ->where('clearance_acadterm.deleted', 0)
                    ->where('clearance_acadterm.syid', $sy)
                    ->where('clearance_acadterm.isactive', 0)
                    ->where('clearance_acadterm_acadprog.deleted', 0)
                    ->where('clearance_acadterm_acadprog.termid', $acadterm);
            })
            ->join('academicprogram', function ($join) {
                $join->on('clearance_acadterm_acadprog.acadprogid', '=', 'academicprogram.id');
            })
            ->select(
                'clearance_acadterm_acadprog.id',
                'clearance_acadterm_acadprog.subjteacher',
                'clearance_acadterm_acadprog.classadviser',
                'clearance_acadterm_acadprog.termid',
                'clearance_acadterm_acadprog.acadprogid',
                'academicprogram.progname'
            )
            ->get();

        return $getacdprog;
    }

    // public function getsubteacher_clearance(Request $request)
    // { // Subject Teacher get clearance status
    //     $sy = $request->get('syid');
    //     $acadterm = $request->get('acadterm');
    //     $acadprogid = $request->get('acadprogid');
    //     $clearancefor = 'SUBJECT TEACHER';

    //     $getactivated_id = DB::table('clearance_signatory')
    //         ->select('id', 'isactive')
    //         ->where('title', $clearancefor)
    //         ->where('departmentid', $clearancefor)
    //         ->where('syid', $sy)
    //         ->where('termid', $acadterm)
    //         ->where('acadprogid', $acadprogid)
    //         ->get();

    //     if($getactivated_id->isEmpty()){
    //         $subteacher = DB::table('clearance_signatory')
    //             ->insert([
    //                 'title' => $clearancefor,
    //                 'teacherid' => 0,
    //                 'departmentid' => $clearancefor,
    //                 'acadprogid' => $acadprogid,
    //                 'termid' => $acadterm,
    //                 'syid' => $sy,
    //                 'isactive' => 1,
    //                 'deleted' => 0,
    //                 'createdby' => auth()->user()->id,
    //                 'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
    //             ]);

    //         $getactivated_id = DB::table('clearance_signatory')
    //             ->select('id', 'isactive')
    //             ->where('title', $clearancefor)
    //             ->where('departmentid', $clearancefor)
    //             ->where('syid', $sy)
    //             ->where('termid', $acadterm)
    //             ->where('acadprogid', $acadprogid)
    //             ->get();
    //     }

    //     return $getactivated_id;
    // }

    // public static function getadviser_clearance(Request $request)
    // { // Adviser get clearance status
    //     $sy = $request->get('syid');
    //     $acadterm = $request->get('acadterm');
    //     $acadprogid = $request->get('acadprogid');
    //     $clearancefor = 'CLASS ADVISER';

    //     if($acadprogid <= 5){
    //         $getactivated_id = DB::table('clearance_signatory')
    //             ->select('id', 'isactive')
    //             ->where('title', $clearancefor)
    //             ->where('departmentid', $clearancefor)
    //             ->where('syid', $sy)
    //             ->where('termid', $acadterm)
    //             ->where('acadprogid', $acadprogid)
    //             ->get();

    //         if($getactivated_id->isEmpty()){
    //             $subteacher = DB::table('clearance_signatory')
    //                 ->insert([
    //                     'title' => $clearancefor,
    //                     'teacherid' => 0,
    //                     'departmentid' => $clearancefor,
    //                     'acadprogid' => $acadprogid,
    //                     'termid' => $acadterm,
    //                     'syid' => $sy,
    //                     'isactive' => 1,
    //                     'deleted' => 0,
    //                     'createdby' => auth()->user()->id,
    //                     'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
    //                 ]);

    //             $getactivated_id = DB::table('clearance_signatory')
    //                 ->select('id', 'isactive')
    //                 ->where('title', $clearancefor)
    //                 ->where('departmentid', $clearancefor)
    //                 ->where('syid', $sy)
    //                 ->where('termid', $acadterm)
    //                 ->where('acadprogid', $acadprogid)
    //                 ->get();
    //         }
    //     }else{
    //         $getactivated_id = '';
    //     }
    //     return $getactivated_id;
    // }

    public function getacadterm(Request $request) // get clearance type 
    {
        $syid = $request->get('syid');
        $terms = [];

        $getterm = DB::table('clearance_acadterm')
            ->select('id', 'term', 'isactive')
            ->where('isactive', 0)
            ->where('deleted', 0)
            ->where('syid', $syid)
            ->get();

        foreach ($getterm as $item) {
            $data = array(
                'id' => $item->id,
                'text' => $item->term,
                'isactive' => $item->isactive
            );
            $terms[] = $data;
        }

        return $terms;
    }

    public function activate_acadterm(Request $request)
    {
        $termselectedid = $request->get('termselectedid');
        $acadtermstatus = $request->get('acadtermstatus');

        try {
            $update_isactive = DB::table('clearance_acadterm') // update the academic term isactive
                ->where('id', '=', $termselectedid)
                ->update([
                    'isactive' => $acadtermstatus,
                ]);

            return array(
                (object) [
                    'status' => 1,
                    'data' => 'Clearance Status Updated Successfully!',
                ]
            );
        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public static function getallgradelevel(Request $request)
    {

        $getgradelevel = DB::table('gradelevel')
            ->select('id', 'levelname')
            ->orderBy('sortid', 'asc')
            ->where('deleted', 0)
            ->get();

        $gradelevels = array();
        foreach ($getgradelevel as $item) {
            $data = array(
                'id' => $item->id,
                'text' => $item->levelname
            );
            array_push($gradelevels, $data);
        }

        return $gradelevels;
    }

    public static function getgradelevel(Request $request)
    {
        $syid = $request->get('syid');
        $acadterm = $request->get('acadterm');
        $levels = [];

        $getteacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->where('deleted', 0)
            ->get();

        $string_teacherid = json_encode($getteacherid[0]->id);

        $getacadprogid = DB::table('clearance_signatory')
            ->select('acadprogid')
            ->where('teacherid', $string_teacherid)
            ->where('termid', $acadterm)
            ->where('deleted', 0)
            ->where('syid', $syid)
            ->get();
        // ->pluck('acadprogid')
        // ->toArray();

        if ($getacadprogid->isNotEmpty()) {
            $acadprogids = json_decode($getacadprogid[0]->acadprogid);

            $getgradelevel = DB::table('gradelevel')
                ->join('clearance_acadterm_acadprog', function ($join) use ($acadterm, $acadprogids) {
                    $join->on('gradelevel.acadprogid', '=', 'clearance_acadterm_acadprog.acadprogid')
                        ->where('clearance_acadterm_acadprog.termid', $acadterm)
                        ->where('clearance_acadterm_acadprog.deleted', 0)
                        ->whereIn('gradelevel.acadprogid', $acadprogids)
                        ->where('gradelevel.deleted', 0);
                })
                ->select('gradelevel.id', 'gradelevel.levelname')
                ->orderBy('sortid', 'asc')
                ->get();

            foreach ($getgradelevel as $item) {
                $data = array(
                    'id' => $item->id,
                    'text' => $item->levelname
                );
                $levels[] = $data;
            }

        }

        return $levels;
    }

    public function getsection(Request $request)
    {
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $getsection = [];
        $sections = [];

        if ($levelid >= 1 && $levelid <= 16) {
            $getsection = DB::table('sectiondetail') // find a way to store a the data in an array and use it to find the activated acadterm in the academic_term table
                ->join('sections', function ($join) use ($levelid, $syid) {
                    $join->on('sectiondetail.sectionid', '=', 'sections.id')
                        ->where('sections.deleted', 0)
                        ->where('sectiondetail.deleted', 0)
                        ->where('sectiondetail.syid', $syid)
                        ->where('sections.levelid', $levelid);
                })
                ->select(
                    'sections.id',
                    'sections.sectionname'
                )
                ->get();
        }

        if ($levelid >= 17 && $levelid <= 21) {
            $getsection = DB::table('college_schedgroup') // find a way to store a the data in an array and use it to find the activated acadterm in the academic_term table
                ->select(
                    'id',
                    'schedgroupdesc'
                )
                ->where('deleted', 0)
                ->where('levelid', $levelid)
                ->get();
        }
        // if($levelid == 30 ){       // For Tech-Voc commented
        //     $getsection = DB::table('sectiondetail') // find a way to store a the data in an array and use it to find the activated acadterm in the academic_term table
        //     ->join('sections', function ($join) use ($levelid, $syid) {
        //         $join->on('sectiondetail.sectionid', '=', 'sections.id')
        //             ->where('sections.deleted', 0)
        //             ->where('sectiondetail.deleted', 0)
        //             ->where('sectiondetail.syid', $syid)
        //             ->where('sections.levelid', $levelid);
        //     })
        //     ->select(
        //         'sections.id',
        //         'sections.sectionname'
        //     )
        //     ->get();
        // }

        foreach ($getsection as $item) {
            if (isset($item->sectionname)) {
                $data = array(
                    'id' => $item->id,
                    'text' => $item->sectionname,
                );
                $sections[] = $data;
            } else {
                $data = array(
                    'id' => $item->id,
                    'text' => $item->schedgroupdesc,
                );
                $sections[] = $data;
            }
        }

        return $sections;
    }

    public function getacadprog(Request $request)
    {
        $syid = $request->get('syid');
        $acadterm = $request->get('acadterm');
        $acadprogs = [];

        $getacadprog = DB::table('academicprogram')
            ->join('clearance_acadterm_acadprog', function ($join) use ($syid, $acadterm) {
                $join->on('academicprogram.id', '=', 'clearance_acadterm_acadprog.acadprogid')
                    ->where('clearance_acadterm_acadprog.deleted', 0)
                    // ->when($acadterm, function ($query, $acadterm) {
                    //     return $query->where('clearance_acadterm_acadprog.termid', $acadterm);
                    // });
                    ->where('clearance_acadterm_acadprog.termid', $acadterm);
            })
            ->select(
                'academicprogram.id',
                'academicprogram.progname'
            )
            ->get();

        foreach ($getacadprog as $item) {
            $data = array(
                'id' => $item->id,
                'text' => $item->progname
            );
            $acadprog[] = $data;
        }

        return $acadprog;
    }

    public function getclearancestud(Request $request)
    {
        $syid = $request->get('syid');
        $acadterm = $request->get('acadterm');
        $sectionid = $request->get('sectionid');
        $levelid = $request->get('levelid');
        $clearstatus = $request->get('clearstatus');
        $iscleared = $request->get('iscleared');
        $requested = $request->get('requested');
        $getstudid = "";
        $getstudent = "";
        $isclearance_enabled = false;

        $getacademicprogid = DB::table('gradelevel') // Get Academic Program
            ->join('academicprogram', function ($join) use ($levelid) {
                $join->on('gradelevel.acadprogid', '=', 'academicprogram.id')
                    ->where('gradelevel.id', $levelid);
            })
            ->select(
                'gradelevel.acadprogid'
            )
            ->get();

        if (count($getacademicprogid) == 0) {
            return array();
        }

        $string_acadprogid = json_encode($getacademicprogid[0]->acadprogid);

        if ($requested == 'MONITORING') {
            if ($string_acadprogid == 6) {
                $getstudent = DB::table('college_enrolledstud') // SHS
                    ->join('studinfo', function ($join) use ($syid, $levelid, $sectionid) {
                        $join->on('college_enrolledstud.studid', '=', 'studinfo.id')
                            ->where('college_enrolledstud.deleted', 0)
                            ->where('studinfo.deleted', 0)
                            ->where('college_enrolledstud.syid', $syid)
                            ->where('college_enrolledstud.yearLevel', $levelid)
                            ->where('college_enrolledstud.sectionID', $sectionid);
                    })
                    ->join('sectiondetail', function ($join) {
                        $join->on('college_enrolledstud.sectionID', '=', 'sectiondetail.sectionid')
                            ->where('sectiondetail.deleted', 0);
                    })
                    ->join('gradelevel', function ($join) {
                        $join->on('college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                            ->where('gradelevel.deleted', 0);
                    })
                    ->join('clearance_studinfo', function ($join) use ($syid, $acadterm) {
                        $join->on('studinfo.id', '=', 'clearance_studinfo.studid')
                            ->where('clearance_studinfo.syid', $syid)
                            ->where('clearance_studinfo.termid', $acadterm)
                            ->where('clearance_studinfo.deleted', 0);
                    })
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'college_enrolledstud.sectionID',
                        'gradelevel.levelname',
                        'clearance_studinfo.termid',
                        'clearance_studinfo.id',
                        'clearance_studinfo.syid',
                        'clearance_studinfo.iscleared'
                    )
                    ->when(isset($clearstatus), function ($query) use ($clearstatus) {
                        return $query->where('clearance_stud_status.status', $clearstatus);
                    })
                    ->get();
            } else if ($string_acadprogid == 5) {
                $getstudent = DB::table('sh_enrolledstud') // SHS
                    ->join('studinfo', function ($join) use ($syid, $levelid, $sectionid) {
                        $join->on('sh_enrolledstud.studid', '=', 'studinfo.id')
                            ->where('sh_enrolledstud.deleted', 0)
                            ->where('studinfo.deleted', 0)
                            ->where('sh_enrolledstud.syid', $syid)
                            ->where('sh_enrolledstud.levelid', $levelid)
                            ->where('sh_enrolledstud.sectionid', $sectionid);
                    })
                    ->join('sectiondetail', function ($join) {
                        $join->on('sh_enrolledstud.sectionid', '=', 'sectiondetail.sectionid')
                            ->where('sectiondetail.deleted', 0);
                    })
                    ->join('gradelevel', function ($join) {
                        $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id')
                            ->where('gradelevel.deleted', 0);
                    })
                    ->join('clearance_studinfo', function ($join) use ($syid, $acadterm) {
                        $join->on('studinfo.id', '=', 'clearance_studinfo.studid')
                            ->where('clearance_studinfo.syid', $syid)
                            ->where('clearance_studinfo.termid', $acadterm)
                            ->where('clearance_studinfo.deleted', 0);
                    })
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'sh_enrolledstud.sectionid',
                        'sectiondetail.sectname',
                        'gradelevel.levelname',
                        'clearance_studinfo.termid',
                        'clearance_studinfo.id',
                        'clearance_studinfo.syid',
                        'clearance_studinfo.iscleared'
                    )
                    ->when(isset($clearstatus), function ($query) use ($clearstatus) {
                        return $query->where('clearance_stud_status.status', $clearstatus);
                    })
                    ->get();
            } else {
                $getstudent = DB::table('enrolledstud') // BasicEd w/o SHS
                    ->join('studinfo', function ($join) use ($syid, $levelid, $sectionid) {
                        $join->on('enrolledstud.studid', '=', 'studinfo.id')
                            ->where('enrolledstud.deleted', 0)
                            ->where('studinfo.deleted', 0)
                            ->where('enrolledstud.syid', $syid)
                            ->where('enrolledstud.levelid', $levelid)
                            ->where('enrolledstud.sectionid', $sectionid);
                    })
                    ->join('sectiondetail', function ($join) {
                        $join->on('enrolledstud.sectionid', '=', 'sectiondetail.sectionid')
                            ->where('sectiondetail.deleted', 0);
                    })
                    ->join('gradelevel', function ($join) {
                        $join->on('enrolledstud.levelid', '=', 'gradelevel.id')
                            ->where('gradelevel.deleted', 0);
                    })
                    ->join('clearance_studinfo', function ($join) use ($syid, $acadterm) {
                        $join->on('studinfo.id', '=', 'clearance_studinfo.studid')
                            ->where('clearance_studinfo.syid', $syid)
                            ->where('clearance_studinfo.termid', $acadterm)
                            ->where('clearance_studinfo.deleted', 0);
                    })
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'enrolledstud.sectionid',
                        'sectiondetail.sectname',
                        'gradelevel.levelname',
                        'clearance_studinfo.termid',
                        'clearance_studinfo.id',
                        'clearance_studinfo.syid',
                        'clearance_studinfo.iscleared'
                    )
                    ->when(isset($clearstatus), function ($query) use ($clearstatus) {
                        return $query->where('clearance_stud_status.status', $clearstatus);
                    })
                    ->get();
            }

        } else {

            $getteacherid = DB::table('teacher') // fetch teacher ID
                ->join('clearance_signatory', function ($join) use ($syid, $acadterm, $string_acadprogid) {
                    $join->on('teacher.id', '=', 'clearance_signatory.teacherid')
                        ->where('clearance_signatory.deleted', 0)
                        ->where('clearance_signatory.syid', $syid)
                        ->where('clearance_signatory.termid', $acadterm)
                        ->whereRaw("json_extract(clearance_signatory.acadprogid, '$[*]') LIKE ?", ["%$string_acadprogid%"])
                        ->where('teacher.userid', auth()->user()->id);
                })
                ->select('clearance_signatory.id', 'clearance_signatory.title', 'clearance_signatory.teacherid') // updated must be updated also in student Controller
                ->get();

            $string_id = $getteacherid[0]->id;

            if ($getteacherid->isNotEmpty()) {
                $isclearance_enabled = true;
                $string_title = $getteacherid[0]->title;

                if ($string_acadprogid == 2 || $string_acadprogid == 3 || $string_acadprogid == 4) {
                    $enrolledstud = DB::table('enrolledstud') // fetch all enrolled student
                        ->select('studid', 'levelid', 'sectionid')
                        ->where('syid', $syid)
                        ->where('deleted', 0)
                        ->where('studstatus', '!=', 0)
                        ->where('levelid', $levelid)
                        ->where('sectionid', $sectionid)
                        ->get();
                }

                if ($string_acadprogid == 5) {
                    $enrolledstud = DB::table('sh_enrolledstud') // fetch all shs enrolled student
                        ->select('studid', 'levelid', 'sectionid')
                        ->where('syid', $syid)
                        ->where('deleted', 0)
                        ->where('studstatus', '!=', 0)
                        ->where('levelid', $levelid)
                        ->where('sectionid', $sectionid)
                        ->get();
                }

                if ($string_acadprogid == 6) {
                    $enrolledstud = DB::table('college_enrolledstud') // fetch all shs enrolled student
                        ->select('studid', 'yearLevel', 'sectionID')
                        ->where('syid', $syid)
                        ->where('deleted', 0)
                        ->where('studstatus', '!=', 0)
                        ->where('yearLevel', $levelid)
                        ->where('sectionID', $sectionid)
                        ->get();
                }
                // $enrolled_basiced = $get_enrolledstud->concat($get_shenrolledstud); 
                // $enrolledstud = $enrolled_basiced->concat($get_collegeenrolledstud); 

                foreach ($enrolledstud as $stud) {
                    $studid = $stud->studid;
                    if (isset($stud->sectionid)) {
                        $sectionid = $stud->sectionid;
                        $levelid = $stud->levelid;
                    } else {
                        $sectionid = $stud->sectionID;
                        $levelid = $stud->yearLevel;
                    }

                    $check_duplicate = DB::table('clearance_studinfo') // use foreach to check every student if already have record in clearance_studinfo
                        ->select('id')
                        ->where('studid', '=', $studid)
                        ->where('termid', '=', $acadterm)
                        ->where('syid', '=', $syid)
                        ->get();

                    if ($check_duplicate->isEmpty()) {
                        $insertstud = DB::table('clearance_studinfo') // if student don't have a record it will be inserted to the table
                            ->insertGetId([
                                'studid' => $studid,
                                'syid' => $syid,
                                'termid' => $acadterm,
                                'isactive' => 0,
                                'iscleared' => 1,
                                'deleted' => 0,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);
                    } else {
                        $getstud = DB::table('clearance_studinfo')
                            ->select('id')
                            ->where('studid', '=', $studid)
                            ->where('termid', '=', $acadterm)
                            ->where('syid', '=', $syid)
                            ->get();
                        $insertstud = $getstud[0]->id;
                    }

                    if ($requested != 'Monitoring') {
                        // Insert clearance_stud_status
                        $getstatusid = DB::table('clearance_stud_status') // check if student clearance has been approved once
                            ->select('id')
                            ->where('clearance_studid', $insertstud)
                            ->where('clearance_type', $getteacherid[0]->id)
                            ->where('teacher_id', $getteacherid[0]->teacherid)
                            ->where('subject_id', $string_title)
                            ->get();

                        if ($getstatusid->isEmpty()) {
                            $isempty_studid = true; // if student is not approved once
                        } else {
                            $isempty_studid = false; // if student is already approved once
                        }

                        if ($isempty_studid == true) {
                            $savestudclearance = DB::table('clearance_stud_status')
                                ->insert([
                                    'clearance_studid' => $insertstud,
                                    'clearance_type' => $getteacherid[0]->id,
                                    'teacher_id' => $getteacherid[0]->teacherid,
                                    'subject_id' => $string_title,
                                    'status' => 1,
                                    'isactive' => 0,
                                    'deleted' => 0,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);
                        }
                        // Insert clearance_stud_status -close
                    }
                }//for each end

                if ($string_acadprogid == 6) {
                    $getstudent = DB::table('clearance_studinfo')
                        ->join('studinfo', function ($join) {
                            $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                                ->where('studinfo.deleted', 0);
                        })
                        ->join('college_enrolledstud', function ($join) use ($syid, $sectionid) {
                            $join->on('clearance_studinfo.studid', '=', 'college_enrolledstud.studid')
                                ->where('college_enrolledstud.deleted', 0)
                                ->where('college_enrolledstud.syid', $syid)
                                ->where('college_enrolledstud.sectionID', $sectionid);
                        })
                        ->join('sectiondetail', function ($join) {
                            $join->on('college_enrolledstud.sectionID', '=', 'sectiondetail.sectionid')
                                ->where('sectiondetail.deleted', 0);
                        })
                        ->join('gradelevel', function ($join) {
                            $join->on('college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                                ->where('gradelevel.deleted', 0);
                        })
                        ->leftJoin('clearance_stud_status', function ($join) {
                            $join->on('clearance_studinfo.id', '=', 'clearance_stud_status.clearance_studid');
                            // ->where('clearance_stud_status.isactive', 0);
                        })
                        ->select(
                            // 'clearance_studinfo.id',
                            'clearance_studinfo.iscleared',
                            'clearance_studinfo.cleareddatetime',
                            'clearance_studinfo.syid',
                            'clearance_studinfo.termid',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'college_enrolledstud.sectionID',
                            'clearance_stud_status.id',
                            'clearance_stud_status.clearance_studid',
                            'clearance_stud_status.status',
                            'clearance_stud_status.remarks',
                            'clearance_stud_status.approveddatetime',
                            'clearance_stud_status.updateddatetime',
                            'clearance_stud_status.subject_id',
                            'gradelevel.levelname'
                        )
                        ->where('clearance_studinfo.isactive', 0)
                        ->where('clearance_stud_status.subject_id', $string_title)
                        ->where('clearance_stud_status.clearance_type', $string_id)
                        ->when($levelid, function ($query, $levelid) {
                            return $query->where('college_enrolledstud.yearLevel', $levelid); // filter grade level
                        })
                        ->when($acadterm, function ($query, $acadterm) {
                            return $query->where('clearance_studinfo.termid', $acadterm);
                        })
                        ->when(isset($clearstatus), function ($query) use ($clearstatus) {
                            return $query->where('clearance_stud_status.status', $clearstatus);
                        })
                        ->when(isset($iscleared), function ($query) use ($iscleared) {
                            return $query->where('clearance_studinfo.iscleared', $iscleared);
                        })
                        ->get();
                } else if ($string_acadprogid == 5) {
                    $getstudent = DB::table('clearance_studinfo')
                        ->join('studinfo', function ($join) {
                            $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                                ->where('studinfo.deleted', 0);
                        })
                        ->join('sh_enrolledstud', function ($join) use ($syid, $sectionid) {
                            $join->on('clearance_studinfo.studid', '=', 'sh_enrolledstud.studid')
                                ->where('sh_enrolledstud.deleted', 0)
                                ->where('sh_enrolledstud.syid', $syid)
                                ->where('sh_enrolledstud.sectionid', $sectionid);
                        })
                        ->join('sectiondetail', function ($join) {
                            $join->on('sh_enrolledstud.sectionid', '=', 'sectiondetail.sectionid')
                                ->where('sectiondetail.deleted', 0);
                        })
                        ->join('gradelevel', function ($join) {
                            $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id')
                                ->where('gradelevel.deleted', 0);
                        })
                        ->leftJoin('clearance_stud_status', function ($join) {
                            $join->on('clearance_studinfo.id', '=', 'clearance_stud_status.clearance_studid');
                            // ->where('clearance_stud_status.isactive', 0);
                        })
                        ->select(
                            // 'clearance_studinfo.id',
                            'clearance_studinfo.iscleared',
                            'clearance_studinfo.cleareddatetime',
                            'clearance_studinfo.syid',
                            'clearance_studinfo.termid',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'sh_enrolledstud.sectionid',
                            'clearance_stud_status.id',
                            'clearance_stud_status.clearance_studid',
                            'clearance_stud_status.status',
                            'clearance_stud_status.remarks',
                            'clearance_stud_status.approveddatetime',
                            'clearance_stud_status.updateddatetime',
                            'clearance_stud_status.subject_id',
                            'sectiondetail.sectname',
                            'gradelevel.levelname'
                        )
                        ->where('clearance_studinfo.isactive', 0)
                        ->where('clearance_stud_status.subject_id', $string_title)
                        ->where('clearance_stud_status.clearance_type', $string_id)
                        ->when($levelid, function ($query, $levelid) {
                            return $query->where('sh_enrolledstud.levelid', $levelid); // filter grade level
                        })
                        ->when($acadterm, function ($query, $acadterm) {
                            return $query->where('clearance_studinfo.termid', $acadterm);
                        })
                        ->when(isset($clearstatus), function ($query) use ($clearstatus) {
                            return $query->where('clearance_stud_status.status', $clearstatus);
                        })
                        ->when(isset($iscleared), function ($query) use ($iscleared) {
                            return $query->where('clearance_studinfo.iscleared', $iscleared);
                        })
                        ->get();
                } else {
                    $getstudent = DB::table('clearance_studinfo')
                        ->join('studinfo', function ($join) {
                            $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                                ->where('studinfo.deleted', 0);
                        })
                        ->join('enrolledstud', function ($join) use ($syid, $sectionid) {
                            $join->on('clearance_studinfo.studid', '=', 'enrolledstud.studid')
                                ->where('enrolledstud.deleted', 0)
                                ->where('enrolledstud.syid', $syid)
                                ->where('enrolledstud.sectionid', $sectionid);
                        })
                        ->join('sectiondetail', function ($join) {
                            $join->on('enrolledstud.sectionid', '=', 'sectiondetail.sectionid')
                                ->where('sectiondetail.deleted', 0);
                        })
                        ->join('gradelevel', function ($join) {
                            $join->on('enrolledstud.levelid', '=', 'gradelevel.id')
                                ->where('gradelevel.deleted', 0);
                        })
                        ->leftJoin('clearance_stud_status', function ($join) {
                            $join->on('clearance_studinfo.id', '=', 'clearance_stud_status.clearance_studid');
                            // ->where('clearance_stud_status.isactive', 0);
                        })
                        ->select(
                            // 'clearance_studinfo.id',
                            'clearance_studinfo.iscleared',
                            'clearance_studinfo.cleareddatetime',
                            'clearance_studinfo.syid',
                            'clearance_studinfo.termid',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'enrolledstud.sectionid',
                            'clearance_stud_status.id',
                            'clearance_stud_status.clearance_studid',
                            'clearance_stud_status.status',
                            'clearance_stud_status.remarks',
                            'clearance_stud_status.approveddatetime',
                            'clearance_stud_status.updateddatetime',
                            'clearance_stud_status.subject_id',
                            'sectiondetail.sectname',
                            'gradelevel.levelname'
                        )
                        ->where('clearance_studinfo.isactive', 0)
                        ->where('clearance_stud_status.subject_id', $string_title)
                        ->where('clearance_stud_status.clearance_type', $string_id)
                        ->when($levelid, function ($query, $levelid) {
                            return $query->where('enrolledstud.levelid', $levelid); // filter grade level
                        })
                        ->when($acadterm, function ($query, $acadterm) {
                            return $query->where('clearance_studinfo.termid', $acadterm);
                        })
                        ->when(isset($clearstatus), function ($query) use ($clearstatus) {
                            return $query->where('clearance_stud_status.status', $clearstatus);
                        })
                        ->when(isset($iscleared), function ($query) use ($iscleared) {
                            return $query->where('clearance_studinfo.iscleared', $iscleared);
                        })
                        ->get();
                }
            }//if empty end
        }//else end
        return [$getstudent, $isclearance_enabled];
    }

    public function approve_clearance(Request $request)
    {
        $clear_stat_id = $request->get('clear_stat_id');
        $syid = $request->get('syid');
        $clear_studid = $request->get('clear_studid'); // clearance_studinfo id foreign key of clearance_stud_status table
        $status = $request->get('status'); // 0-approved  1-Unapproved 2-Pending
        $remarks = $request->get('remarks');

        try {
            //** Commented becasue the id of the clearane_stud_status in now used as the Identifier **//
            // $getteacherid = DB::table('teacher') // fetch teacher ID
            // ->join('clearance_signatory', function ($join) use ($syid) {
            //     $join->on('teacher.id', '=', 'clearance_signatory.teacherid')
            //         ->where('clearance_signatory.deleted', 0)
            //         ->where('clearance_signatory.syid', $syid)
            //         ->where('teacher.userid', auth()->user()->id);
            // })
            // ->select('clearance_signatory.id', 'clearance_signatory.title', 'clearance_signatory.teacherid') // updated must be updated also in student Controller
            // ->get();

            // $string_signatoryid = $getteacherid[0]->id;
            // $string_title = $getteacherid[0]->title; 
            // $string_teacherid = $getteacherid[0]->teacherid; 

            // $getstatusid = DB::table('clearance_stud_status') // check if student clearance has been approved once
            //     ->select('id')
            //     ->where('clearance_studid', $clear_studid)
            //     ->where('clearance_type', $string_signatoryid)
            //     ->where('teacher_id', $string_teacherid)
            //     ->where('subject_id', $string_title)
            //     ->get();

            // $clear_statusid = json_encode($getstatusid[0]->id); // commented becasue the tr data-id is now used as the identifier
            //** Commented becasue the id of the clearane_stud_status in now used as the Identifier **//

            if ($status == 0) {
                $savestudclearance = DB::table('clearance_stud_status')
                    ->where('id', $clear_stat_id)
                    ->update([
                        'status' => 0,
                        'remarks' => $remarks,
                        'approvedby' => auth()->user()->id,
                        'approveddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            } else {
                $savestudclearance = DB::table('clearance_stud_status')
                    ->where('id', $clear_stat_id)
                    ->update([
                        'status' => $status,
                        'remarks' => $remarks,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

            // check if all clearance is approved
            $countstudcler = DB::table('clearance_stud_status')
                ->where('clearance_studid', $clear_studid)
                ->where('isactive', 0)
                ->count();

            $countstudclerapproved = DB::table('clearance_stud_status')
                ->where('clearance_studid', $clear_studid)
                ->where('isactive', 0)
                ->where('status', 0)
                ->count();

            if ($countstudcler === $countstudclerapproved) {
                $updateclearedstatus = DB::table('clearance_studinfo')
                    ->where('id', $clear_studid)
                    ->where('isactive', 0)
                    ->update([
                        'iscleared' => 0,
                        'cleareddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    ]);
            } else if ($countstudclerapproved > 0) {
                $updateclearedstatus = DB::table('clearance_studinfo')
                    ->where('id', $clear_studid)
                    ->where('isactive', 0)
                    ->update([
                        'iscleared' => 2,
                    ]);
            } else {
                $updateclearedstatus = DB::table('clearance_studinfo')
                    ->where('id', $clear_studid)
                    ->where('isactive', 0)
                    ->update([
                        'iscleared' => 1,
                    ]);
            }
            // check if all clearance is approved -close

            return array(
                (object) [
                    'status' => 1,
                    'data' => 'Saved Successfully!',
                ]
            );
        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public function approve_allclearance(Request $request)
    {
        $syid = $request->get('syid');
        $section = $request->get('section');
        $levelid = $request->get('levelid');
        $acadterm = $request->get('acadterm');
        $status = $request->get('status');
        $userType = auth()->user()->type;
        $remarks = "";

        try {
            $getacademicprogid = DB::table('gradelevel') // get acaademic program of section
                ->join('academicprogram', function ($join) use ($levelid) {
                    $join->on('gradelevel.acadprogid', '=', 'academicprogram.id')
                        ->where('gradelevel.id', $levelid);
                })
                ->select(
                    'gradelevel.acadprogid'
                )
                ->get();
            $string_acadprogid = json_encode($getacademicprogid[0]->acadprogid);

            $getteacherid = DB::table('teacher') // fetch teacher ID
                ->join('clearance_signatory', function ($join) use ($syid, $acadterm, $string_acadprogid) {
                    $join->on('teacher.id', '=', 'clearance_signatory.teacherid')
                        ->where('clearance_signatory.deleted', 0)
                        ->where('clearance_signatory.syid', $syid)
                        ->where('clearance_signatory.termid', $acadterm)
                        ->whereRaw("json_extract(clearance_signatory.acadprogid, '$[*]') LIKE ?", ["%$string_acadprogid%"])
                        ->where('teacher.userid', auth()->user()->id);
                })
                ->select('clearance_signatory.id', 'clearance_signatory.title', 'clearance_signatory.teacherid') // updated must be updated also in student Controller
                ->get();

            // $acadprogid = collect($getteacherid)->pluck('acadprogid');
            $string_signatoryid = $getteacherid[0]->id;
            $string_title = $getteacherid[0]->title;
            $string_teacherid = $getteacherid[0]->teacherid;

            if ($string_acadprogid == 6) {
                $getstudid = DB::table('clearance_studinfo') // get student where section is set
                    ->join('college_enrolledstud', function ($join) use ($syid, $section) {
                        $join->on('clearance_studinfo.studid', '=', 'college_enrolledstud.studid')
                            ->where('college_enrolledstud.deleted', 0)
                            ->where('college_enrolledstud.syid', $syid)
                            ->where('college_enrolledstud.sectionID', $section);
                    })
                    ->join('gradelevel', function ($join) use ($levelid) {
                        $join->on('college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                            ->where('gradelevel.id', $levelid)
                            ->where('gradelevel.deleted', 0);
                    })
                    ->join('academicprogram', function ($join) {
                        $join->on('gradelevel.acadprogid', '=', 'academicprogram.id')
                            ->where('gradelevel.deleted', 0);
                    })
                    ->select('clearance_studinfo.id')
                    ->get()
                    ->pluck('id')
                    ->toArray();
            } else if ($string_acadprogid == 5) { // check if students is SHS
                $getstudid = DB::table('clearance_studinfo') // get student where section is set
                    ->join('sh_enrolledstud', function ($join) use ($syid, $section) {
                        $join->on('clearance_studinfo.studid', '=', 'sh_enrolledstud.studid')
                            ->where('sh_enrolledstud.deleted', 0)
                            ->where('sh_enrolledstud.syid', $syid)
                            ->where('sh_enrolledstud.sectionid', $section);
                    })
                    ->join('gradelevel', function ($join) use ($levelid) {
                        $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id')
                            ->where('gradelevel.id', $levelid)
                            ->where('gradelevel.deleted', 0);
                    })
                    ->join('academicprogram', function ($join) {
                        $join->on('gradelevel.acadprogid', '=', 'academicprogram.id')
                            ->where('gradelevel.deleted', 0);
                    })
                    ->select('clearance_studinfo.id')
                    ->get()
                    ->pluck('id')
                    ->toArray();
            } else {
                $getstudid = DB::table('clearance_studinfo') // get student where section is set
                    ->join('enrolledstud', function ($join) use ($syid, $section) {
                        $join->on('clearance_studinfo.studid', '=', 'enrolledstud.studid')
                            ->where('enrolledstud.deleted', 0)
                            ->where('enrolledstud.syid', $syid)
                            ->where('enrolledstud.sectionid', $section);
                    })
                    ->join('gradelevel', function ($join) use ($levelid) {
                        $join->on('enrolledstud.levelid', '=', 'gradelevel.id')
                            ->where('gradelevel.id', $levelid)
                            ->where('gradelevel.deleted', 0);
                    })
                    ->join('academicprogram', function ($join) {
                        $join->on('gradelevel.acadprogid', '=', 'academicprogram.id')
                            ->where('gradelevel.deleted', 0);
                    })
                    ->select('clearance_studinfo.id')
                    ->get()
                    ->pluck('id')
                    ->toArray();
            }

            if ($userType == 2) {
                if ($status == 0) {
                    foreach ($getstudid as $id) {
                        $getsubjectstatus = DB::table('clearance_stud_status')
                            ->select('id', 'status')
                            ->where('clearance_studid', $id)
                            ->where('subject_id', '!=', $string_title)
                            ->where('isactive', 0)
                            ->get();

                        $allStatusAreZero = $getsubjectstatus->every(function ($items) {
                            return $items->status === 0;
                        });

                        if ($allStatusAreZero == true) {
                            // approve clearance 
                            $getstatusid = DB::table('clearance_stud_status')
                                ->select('id')
                                ->where('clearance_studid', $id)
                                ->where('clearance_type', $string_signatoryid)
                                ->where('teacher_id', $string_teacherid)
                                ->where('subject_id', $string_title)
                                ->where('isactive', 0)
                                ->get();

                            $savestudclearance = DB::table('clearance_stud_status')
                                ->where('id', $getstatusid[0]->id)
                                ->update([
                                    'remarks' => $remarks,
                                    'status' => 0,
                                    'approvedby' => auth()->user()->id,
                                    'approveddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);
                            // approve clearance -close

                            // check if all clearance is approved
                            $countclearance = DB::table('clearance_stud_status')
                                ->where('clearance_studid', $id)
                                ->where('isactive', 0)
                                ->count();

                            $countcleared = DB::table('clearance_stud_status')
                                ->where('clearance_studid', $id)
                                ->where('isactive', 0)
                                ->where('status', 0)
                                ->count();

                            if ($countclearance === $countcleared) { // clearance cleared status
                                $updateclearedstatus = DB::table('clearance_studinfo')
                                    ->where('id', $id)
                                    ->where('isactive', 0)
                                    ->update([
                                        'iscleared' => 0,
                                        'cleareddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    ]);
                            } else if ($countcleared > 0) {
                                $updateclearedstatus = DB::table('clearance_studinfo')
                                    ->where('id', $id)
                                    ->where('isactive', 0)
                                    ->update([
                                        'iscleared' => 2,
                                        // 'updatedby' => auth()->user()->id,
                                        // 'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                            } else {
                                $updateclearedstatus = DB::table('clearance_studinfo')
                                    ->where('id', $id)
                                    ->where('isactive', 0)
                                    ->update([
                                        'iscleared' => 1,
                                        // 'updatedby' => auth()->user()->id,
                                        // 'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                            } // clearance cleared status -close
                            // check if all clearance is approved -close
                        }
                    }
                }

                if ($status == 2) {
                    $getstatusid = DB::table('clearance_stud_status')
                        ->select('id')
                        ->whereIn('clearance_studid', $getstudid)
                        ->where('clearance_type', $string_signatoryid)
                        ->where('teacher_id', $string_teacherid)
                        ->where('subject_id', $string_title)
                        ->where('status', 1)
                        ->where('isactive', 0)
                        ->get()
                        ->pluck('id')
                        ->toArray();

                    $savestudclearance = DB::table('clearance_stud_status')
                        ->whereIn('id', $getstatusid)
                        ->update([
                            'remarks' => $remarks,
                            'status' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                }
            } else {
                if ($status == 0) {
                    foreach ($getstudid as $id) {
                        $getstatusid = DB::table('clearance_stud_status')
                            ->select('id')
                            ->where('clearance_studid', $id)
                            ->where('clearance_type', $string_signatoryid)
                            ->where('teacher_id', $string_teacherid)
                            ->where('subject_id', $string_title)
                            ->whereIn('status', [1, 2])
                            ->where('isactive', 0)
                            ->get()
                            ->pluck('id')
                            ->toArray();

                        $savestudclearance = DB::table('clearance_stud_status')
                            ->where('id', $getstatusid)
                            ->update([
                                'remarks' => $remarks,
                                'status' => $status,
                                'approvedby' => auth()->user()->id,
                                'approveddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);

                        // check if all clearance is approved
                        $countclearance = DB::table('clearance_stud_status')
                            ->where('clearance_studid', $id)
                            ->where('isactive', 0)
                            ->count();

                        $countcleared = DB::table('clearance_stud_status')
                            ->where('clearance_studid', $id)
                            ->where('isactive', 0)
                            ->where('status', 0)
                            ->count();

                        if ($countclearance === $countcleared) { // clearance cleared status
                            $updateclearedstatus = DB::table('clearance_studinfo')
                                ->where('id', $id)
                                ->where('isactive', 0)
                                ->update([
                                    'iscleared' => 0,
                                    'cleareddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                ]);
                        } else if ($countcleared > 0) {
                            $updateclearedstatus = DB::table('clearance_studinfo')
                                ->where('id', $id)
                                ->where('isactive', 0)
                                ->update([
                                    'iscleared' => 2,
                                    // 'updatedby' => auth()->user()->id,
                                    // 'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);
                        } else {
                            $updateclearedstatus = DB::table('clearance_studinfo')
                                ->where('id', $id)
                                ->where('isactive', 0)
                                ->update([
                                    'iscleared' => 1,
                                    // 'updatedby' => auth()->user()->id,
                                    // 'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);
                        } // clearance cleared status -close
                        // check if all clearance is approved -close
                    }
                }

                if ($status == 2) {
                    $getstatusid = DB::table('clearance_stud_status')
                        ->select('id')
                        ->whereIn('clearance_studid', $getstudid)
                        ->where('clearance_type', $string_signatoryid)
                        ->where('teacher_id', $string_teacherid)
                        ->where('subject_id', $string_title)
                        ->where('status', 1)
                        ->where('isactive', 0)
                        ->get()
                        ->pluck('id')
                        ->toArray();

                    $savestudclearance = DB::table('clearance_stud_status')
                        ->whereIn('id', $getstatusid)
                        ->update([
                            'remarks' => $remarks,
                            'status' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                }
            }

            return array(
                (object) [
                    'status' => 1,
                    'data' => 'Saved Successfully!',
                ]
            );

        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public function view(Request $request)
    {
        return view('clearance.clearanceview');
    }

    public function gethistory()
    {
        $history = [];
        try {
            $userid = auth()->user()->id;

            $getstudsid = DB::table('users')
                ->select('email')
                ->where('id', auth()->user()->id)
                ->get();

            $string_sid = $getstudsid[0]->email;

            if (preg_match('/S/', $string_sid)) {
                $new_string = preg_replace('/S/', '', $string_sid);
            }
            if (preg_match('/P/', $string_sid)) {
                $new_string = preg_replace('/P/', '', $string_sid);
            }

            $getstudsid = DB::table('studinfo')
                ->select('id')
                ->where('sid', $new_string)
                ->get();

            $gethistory = DB::table('clearance_studinfo')
                ->join('sy', function ($join) {
                    $join->on('clearance_studinfo.syid', '=', 'sy.id');
                })
                ->join('clearance_acadterm', function ($join) {
                    $join->on('clearance_studinfo.termid', '=', 'clearance_acadterm.id')
                        ->where('clearance_acadterm.deleted', 0);
                })
                ->select('clearance_studinfo.id', 'clearance_studinfo.isactive', 'clearance_studinfo.termid', 'clearance_acadterm.term', 'sy.sydesc')
                ->where('clearance_studinfo.studid', $getstudsid[0]->id)
                ->get();

            if ($gethistory->isEmpty()) {
                $getacadterm = DB::table('clearance_acadterm')
                    ->join('sy', function ($join) {
                        $join->on('clearance_acadterm.syid', '=', 'sy.id');
                    })
                    ->select('clearance_acadterm.id', 'clearance_acadterm.syid')
                    ->get();

                if ($getacadterm->isNotEmpty()) {
                    $insertstud = DB::table('clearance_studinfo') // if student don't have a record it will be inserted to the table
                        ->insertGetId([
                            'studid' => $getstudsid[0]->id,
                            'syid' => $getacadterm[0]->syid,
                            'termid' => $getacadterm[0]->id,
                            'isactive' => 0,
                            'iscleared' => 1,
                            'deleted' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                    $gethistory = DB::table('clearance_studinfo')
                        ->join('sy', function ($join) {
                            $join->on('clearance_studinfo.syid', '=', 'sy.id');
                        })
                        ->join('clearance_acadterm', function ($join) {
                            $join->on('clearance_studinfo.termid', '=', 'clearance_acadterm.id')
                                ->where('clearance_acadterm.deleted', 0);
                        })
                        ->select('clearance_studinfo.id', 'clearance_studinfo.isactive', 'clearance_studinfo.termid', 'clearance_acadterm.term', 'sy.sydesc')
                        ->where('clearance_studinfo.studid', $getstudsid[0]->id)
                        ->get();
                }
            }

            foreach ($gethistory as $item) {
                $data = array(
                    'id' => $item->id,
                    'text' => $item->sydesc . ' ' . $item->term,
                    'termid' => $item->termid
                );
                $history[] = $data;
            }
            return $history;

        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public function getclearancedata(Request $request)
    {
        $clerstudid = $request->get('clerstudid');
        $syid = $request->get('syid');
        $termid = $request->get('termid');
        $getassignsubj = "";
        $getadviser = "";
        $userid = auth()->user()->id;

        try {
            $getsection = DB::table('clearance_studinfo')
                ->join('enrolledstud', function ($join) use ($clerstudid, $syid) {
                    $join->on('clearance_studinfo.studid', '=', 'enrolledstud.studid')
                        ->where('enrolledstud.syid', $syid)
                        ->where('enrolledstud.deleted', 0);
                })
                ->where('clearance_studinfo.id', $clerstudid)
                ->select('enrolledstud.sectionid', 'enrolledstud.levelid')
                ->get();

            if (count($getsection) == 0) {
                $getsection = DB::table('clearance_studinfo')
                    ->join('sh_enrolledstud', function ($join) use ($clerstudid, $syid) {
                        $join->on('clearance_studinfo.studid', '=', 'sh_enrolledstud.studid')
                            ->where('sh_enrolledstud.syid', $syid)
                            ->where('clearance_studinfo.id', $clerstudid);
                    })
                    ->select('sh_enrolledstud.sectionid', 'sh_enrolledstud.levelid')
                    ->get();

            } else {
                $section = $getsection[0]->sectionid;
                $levelid = $getsection[0]->levelid;
            }



            if ($getsection->isEmpty()) {
                $getsection = DB::table('clearance_studinfo')
                    ->join('college_enrolledstud', function ($join) use ($clerstudid, $syid) {
                        $join->on('clearance_studinfo.studid', '=', 'college_enrolledstud.studid')
                            ->where('college_enrolledstud.syid', $syid)
                            ->where('clearance_studinfo.id', $clerstudid);
                    })
                    ->select('college_enrolledstud.sectionID', 'college_enrolledstud.yearLevel')
                    ->get();

                $section = $getsection[0]->sectionID;
                $levelid = $getsection[0]->yearLevel;
            } else {
                $section = $getsection[0]->sectionid;
                $levelid = $getsection[0]->levelid;
            }


            $getacadprog = DB::table('gradelevel')
                ->where('id', $levelid)
                ->select('acadprogid')
                ->get();

            $acadprogid = $getacadprog[0]->acadprogid;

            $check_subteacher_status = DB::table('clearance_signatory')
                ->where('termid', $termid)
                ->where('syid', $syid)
                ->where('departmentid', 'SUBJECT TEACHER')
                ->whereIn('acadprogid', [0, $acadprogid])
                ->select('acadprogid', 'isactive')
                ->get();

            $check_subteacher_status = DB::table('clearance_acadterm_acadprog')
                ->where('termid', $termid)
                ->where('deleted', 0)
                ->where('acadprogid', $acadprogid)
                ->get();

            //return $check_subteacher_status;

            $subteacher_isactive = false;
            $classadviser_isactive = false;

            foreach ($check_subteacher_status as $status) {

                if ($status->subjteacher == 1) {
                    $subteacher_isactive = true;
                }
                if ($status->classadviser == 1) {
                    $classadviser_isactive = true;
                }
            }




            if ($subteacher_isactive) {
                // $getassignsubj = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$section);
                $getassignsubj = DB::table('assignsubj')
                    ->where('assignsubj.syid', $syid)
                    ->where('assignsubj.deleted', 0)
                    ->join('assignsubjdetail', function ($join) use ($levelid, $section, $syid) {
                        $join->on('assignsubj.id', '=', 'assignsubjdetail.headerid')
                            ->where('assignsubj.deleted', 0)
                            ->where('assignsubjdetail.deleted', 0)
                            ->where('assignsubj.glevelid', $levelid)
                            ->where('assignsubj.sectionid', $section);

                    })
                    ->join('subjects', function ($join) {
                        $join->on('assignsubjdetail.subjid', '=', 'subjects.id')
                            ->where('subjects.deleted', 0)
                            ->where('assignsubjdetail.deleted', 0);
                    })
                    ->join('teacher', function ($join) {
                        $join->on('assignsubjdetail.teacherid', '=', 'teacher.id')
                            ->where('teacher.deleted', 0)
                            ->where('assignsubjdetail.deleted', 0);
                    })
                    ->leftJoin('clearance_stud_status', function ($join) use ($clerstudid) {
                        $join->on('assignsubjdetail.subjid', '=', 'clearance_stud_status.subject_id')
                            ->where('clearance_stud_status.deleted', '!=', 1)
                            ->where('clearance_stud_status.clearance_studid', '=', $clerstudid);
                    })

                    ->distinct('clearance_stud_status.subject_id')
                    ->select(
                        'assignsubjdetail.subjid',
                        'subjects.subjdesc',
                        'teacher.title',
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'clearance_stud_status.subject_id',
                        'clearance_stud_status.clearance_type',
                        'clearance_stud_status.status',
                        'clearance_stud_status.remarks',
                        'clearance_stud_status.approveddatetime',
                        'clearance_stud_status.updateddatetime'
                    )
                    ->get();


                $getassignsubj = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid, $syid, $section);

                //return $getassignsubj;

            }



            //$check_classadviser_status = DB::table('clearance_signatory')
            //->where('termid', $termid)
            //->where('syid', $syid)
            //->where('departmentid', 'CLASS ADVISER')
            //->whereIn('acadprogid', [0,$acadprogid])
            //->select('acadprogid','isactive')
            //->get();

            //$classadviser_isactive = false;
            //foreach ($check_classadviser_status as $status) {
            //if ($status->acadprogid == $acadprogid && $status->isactive == 0) {
            //$classadviser_isactive = true;
            //break;
            //}
            //if ($status->acadprogid == 0 && $status->isactive == 0) {
            //$classadviser_isactive = true;
            //break;
            //}
            //}

            if ($classadviser_isactive) {



                $getadviser = DB::table('sectiondetail')
                    ->join('teacher', function ($join) use ($section) {
                        $join->on('sectiondetail.teacherid', '=', 'teacher.id')
                            ->where('sectionid', '=', $section)
                            ->where('teacher.deleted', 0);
                    })
                    ->leftJoin('clearance_stud_status', function ($join) use ($clerstudid) {
                        $join->on('teacher.id', '=', 'clearance_stud_status.teacher_id')
                            ->where('subject_id', '=', 'CLASS ADVISER')
                            ->where('teacher.deleted', 0)
                            ->where('clearance_stud_status.clearance_studid', '=', $clerstudid);
                    })
                    ->distinct('clearance_stud_status.subject_id')
                    ->select(
                        'teacher.title',
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'clearance_stud_status.subject_id',
                        'clearance_stud_status.clearance_type',
                        'clearance_stud_status.status',
                        'clearance_stud_status.remarks',
                        'clearance_stud_status.approveddatetime',
                        'clearance_stud_status.updateddatetime'
                    )
                    ->get();
            }


            $checksignatories = DB::table('clearance_signatory')
                ->join('teacher', function ($join) use ($syid, $termid) {
                    $join->on('clearance_signatory.teacherid', '=', 'teacher.id')
                        ->where('teacher.deleted', 0)
                        ->where('clearance_signatory.deleted', 0)
                        ->where('clearance_signatory.syid', $syid)
                        ->where('clearance_signatory.termid', $termid)
                        ->where('clearance_signatory.isactive', 0);
                })
                ->leftJoin('clearance_stud_status', function ($join) use ($clerstudid) {
                    $join->on('clearance_signatory.id', '=', 'clearance_stud_status.clearance_type')
                        ->where('clearance_stud_status.deleted', '=', 0)
                        ->where('clearance_stud_status.clearance_studid', '=', $clerstudid);
                })
                ->whereRaw("json_extract(clearance_signatory.acadprogid, '$[*]') LIKE ?", ["%$acadprogid%"])
                ->select(
                    'clearance_signatory.id',
                    'clearance_signatory.departmentid',
                    'teacher.title',
                    'teacher.lastname',
                    'teacher.firstname',
                    'teacher.middlename',
                    'clearance_stud_status.subject_id',
                    'clearance_stud_status.clearance_type',
                    'clearance_stud_status.status',
                    'clearance_stud_status.remarks',
                    'clearance_stud_status.approveddatetime',
                    'clearance_stud_status.updateddatetime'
                )
                ->distinct('clearance_signatory.title')
                ->get();

            if ($getassignsubj == null && $getadviser == null || $getassignsubj == "" && $getadviser == "") {
                $data = $checksignatories;
            } else if ($getassignsubj == null || $getassignsubj == "") {
                $data = $getadviser->concat($checksignatories);
            } else if ($getadviser == null || $getadviser == "") {
                $data = $getassignsubj->concat($checksignatories);
            } else {
                $data1 = $getassignsubj->concat($getadviser);
                $data = $data1->concat($checksignatories);
            }

            $getclearedstatus = DB::table('clearance_studinfo')
                ->select('iscleared')
                ->where('id', $clerstudid)
                ->where('termid', $termid)
                ->get();

            return [$data, $getclearedstatus];

        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public function getallclearancestat(Request $request)
    {
        $clear_studid = $request->get('clear_studid');
        $syid = $request->get('syid');
        $acadterm = $request->get('acadterm');

        $getteacherid = DB::table('teacher') // fetch clearance_signatory subject_id to identify the one who is log-in
            ->join('clearance_signatory', function ($join) use ($syid, $acadterm) {
                $join->on('teacher.id', '=', 'clearance_signatory.teacherid')
                    ->where('clearance_signatory.deleted', 0)
                    ->where('clearance_signatory.syid', $syid)
                    ->where('clearance_signatory.termid', $acadterm)
                    ->where('teacher.userid', auth()->user()->id);
            })
            ->select('clearance_signatory.title')
            ->get();

        $string_title = $getteacherid[0]->title;

        $getallclearancestat = DB::table('clearance_stud_status')
            ->select('id', 'subject_id', 'status')
            ->where('clearance_studid', $clear_studid)
            ->where('subject_id', '!=', $string_title)
            ->where('isactive', 0)
            ->get();

        return $getallclearancestat;
    }

    public function monitoring()
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;
        if (Session::get('currentPortal') == '1') {

            $extends = "teacher.layouts.app";
        } elseif (Session::get('currentPortal') == '2') {

            $extends = "principalsportal.layouts.app2";
        } elseif (Session::get('currentPortal') == '3' || Session::get('currentPortal') == '8') {

            $extends = "registrar.layouts.app";
        } elseif (Session::get('currentPortal') == '4' || Session::get('currentPortal') == '15') {

            $extends = "finance.layouts.app";
        } elseif (Session::get('currentPortal') == '6') {

            $extends = "adminPortal.layouts.app2";
        } elseif (Session::get('currentPortal') == '10' || $refid == 26) {

            $extends = "hr.layouts.app";
        } elseif (Session::get('currentPortal') == '12') {

            $extends = "adminITPortal.layouts.app";
        } elseif (Session::get('currentPortal') == 14) {

            $extends = "deanportal.layouts.app2";
        } else {
            $extends = "general.defaultportal..layouts.app";
        }

        return view('clearance.monitorclearance')
            ->with('extends', $extends);
    }

    ///////////////ACADEMIC TERM///////////////////
    public static function acadterm_list(Request $request)
    {
        $sy = $request->get('syid');
        $acadprog = $request->get('acadprogid');

        if ($acadprog) {
            $get_termid = DB::table('clearance_acadterm') // 1st table clearance_acadterm
                ->join('clearance_acadterm_acadprog', function ($join) use ($sy, $acadprog) {
                    $join->on('clearance_acadterm.id', '=', 'clearance_acadterm_acadprog.termid') // 2nd table academicterm_acadprog
                        ->where('clearance_acadterm.deleted', 0)
                        ->where('clearance_acadterm.syid', '=', $sy)
                        ->where('clearance_acadterm_acadprog.deleted', 0)
                        ->where('clearance_acadterm_acadprog.acadprogid', $acadprog);
                })
                ->distinct()
                ->pluck('clearance_acadterm_acadprog.termid')
                ->toArray();

            $term_table = DB::table('clearance_acadterm') // 1st table clearance_acadterm
                ->join('clearance_acadterm_acadprog', function ($join) use ($sy, $get_termid) {
                    $join->on('clearance_acadterm.id', '=', 'clearance_acadterm_acadprog.termid') // 2nd table academicterm_acadprog
                        ->whereIn('clearance_acadterm.id', $get_termid)
                        ->where('clearance_acadterm.deleted', 0)
                        ->where('clearance_acadterm.syid', '=', $sy)
                        ->where('clearance_acadterm_acadprog.deleted', 0);
                })
                ->join('academicprogram', function ($join) {
                    $join->on('clearance_acadterm_acadprog.acadprogid', '=', 'academicprogram.id');
                })
                ->select(
                    'clearance_acadterm.*',
                    'academicprogram.progname',
                    'academicprogram.acadprogcode',
                    'clearance_acadterm_acadprog.acadprogid'
                )
                ->get();
        } else {
            $term_table = DB::table('clearance_acadterm') // 1st table clearance_acadterm
                ->join('clearance_acadterm_acadprog', function ($join) use ($sy) {
                    $join->on('clearance_acadterm.id', '=', 'clearance_acadterm_acadprog.termid') // 2nd table academicterm_acadprog
                        ->where('clearance_acadterm.deleted', 0)
                        ->where('clearance_acadterm.syid', '=', $sy)
                        ->where('clearance_acadterm_acadprog.deleted', 0);
                })
                ->join('academicprogram', function ($join) {
                    $join->on('clearance_acadterm_acadprog.acadprogid', '=', 'academicprogram.id');
                })
                ->select(
                    'clearance_acadterm.*',
                    'academicprogram.progname',
                    'academicprogram.acadprogcode',
                    'clearance_acadterm_acadprog.acadprogid'
                )
                // ->when($acadprog, function ($query, $acadprog) {
                //     return $query->where('clearance_acadterm_acadprog.acadprogid', $acadprog);
                // })      
                ->get();
        }

        return $term_table;
    }

    public static function save_term(Request $request)
    {
        $acadprog = $request->get('acadprog');
        $selectedid = $request->get('selectedid');

        if ($selectedid == null) {
            try {
                $term = DB::table('clearance_acadterm') // insert the new academic term
                    ->insert([
                        'term' => $request->get('term'),
                        'syid' => $request->get('syid'),
                        'isactive' => 1,
                        'deleted' => 0,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

                $get_term = DB::table('clearance_acadterm') // retrieve the lastest inserted academic term
                    ->select('id')
                    ->where('deleted', 0)
                    ->latest('id')
                    ->first();

                $term_id = json_encode($get_term->id);

                foreach ($acadprog as $items) {
                    $term = DB::table('clearance_acadterm_acadprog') // insert all the acadprogam link to the academic term
                        ->insert([
                            'termid' => $term_id,
                            'acadprogid' => $items,
                            'subjteacher' => 1,
                            'classadviser' => 1,
                            'deleted' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                }
                ;

                return array(
                    (object) [
                        'status' => 1,
                        'data' => 'Clearance Activated Successfully!',
                        'info' => $term
                    ]
                );

            } catch (\Exception $e) {
                return self::store_error($e);
            }
        } else {
            try {
                $term = DB::table('clearance_acadterm') // will update the term name in the acadterm table because the acadprogs are stored in the academicterm_acadprog table
                    ->where('id', $selectedid)
                    ->update([
                        'term' => $request->get('term'),
                        'syid' => $request->get('syid'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

                foreach ($acadprog as $item) {
                    $item_exists = DB::table('clearance_acadterm_acadprog')
                        ->where('termid', $selectedid)
                        ->where('acadprogid', $item)
                        ->exists(); // check if the acadpog is already in the database

                    if ($item_exists) { // If the acadprog is already in the database the deleted will only be updated
                        DB::table('clearance_acadterm_acadprog')
                            ->where('termid', '=', $selectedid)
                            ->whereIn('acadprogid', $acadprog)
                            ->update([
                                'deleted' => 0,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);
                    } else { // else the new acadprogam will be inserted in the database
                        DB::table('clearance_acadterm_acadprog')
                            ->where('termid', '=', $selectedid)
                            ->whereIn('acadprogid', $acadprog)
                            ->insert([
                                'termid' => $selectedid,
                                'acadprogid' => $item,
                                'subjteacher' => 1,
                                'classadviser' => 1,
                                'deleted' => 0,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);
                    }
                }


                DB::table('clearance_acadterm_acadprog') // updete deleted to 1 if the acadprog is remove from the select field
                    ->where('termid', '=', $selectedid)
                    ->whereNotIn('acadprogid', $acadprog)
                    ->update([
                        'deleted' => 1,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

                return array(
                    (object) [
                        'status' => 1,
                        'data' => 'Updated Successfully!',
                        'info' => $term
                    ]
                );

            } catch (\Exception $e) {
                return self::store_error($e);
            }
        }
    }

    public static function delete_term(Request $request)
    {
        $selectedid = $request->get('selectedid');

        try {
            $select_acadprog = DB::table('clearance_acadterm_acadprog')// retrieve all the ID of acadprograms link to the selected academic term
                ->select('id')
                ->where('termid', '=', $selectedid)
                ->where('deleted', 0)
                ->get();

            foreach ($select_acadprog as $item) { // loop and delete all retrive id in select_acadprog variable
                DB::table('clearance_acadterm_acadprog')
                    ->where('id', $item->id)
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

            DB::table('clearance_acadterm')// delete the academic term in clearance_acadterm table
                ->where('id', $selectedid)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                ]);

            return array(
                (object) [
                    'status' => 1,
                    'data' => 'Deleted Successfully!',
                ]
            );

        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    ///////////////ACADEMIC TERM///////////////////

    // Clearance By Advisory
    public function clearance(Request $request)
    {
        return view('clearance.studentsclearance');
    }

    public function getacadterm_teacher(Request $request)
    {
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $acadprog = $request->get('acadprog');
        $terms = [];

        if ($acadprog == null) {
            $getacademicprogid = DB::table('gradelevel')
                ->join('academicprogram', function ($join) use ($levelid) {
                    $join->on('gradelevel.acadprogid', '=', 'academicprogram.id')
                        ->where('gradelevel.id', $levelid);
                })
                ->select(
                    'gradelevel.acadprogid'
                )
                ->get();

            $string_acadporgid = json_encode($getacademicprogid[0]->acadprogid);

            $getterm = DB::table('clearance_acadterm')
                ->join('clearance_acadterm_acadprog', function ($join) use ($string_acadporgid, $syid) {
                    $join->on('clearance_acadterm.id', '=', 'clearance_acadterm_acadprog.termid')
                        ->where('clearance_acadterm.deleted', 0)
                        ->where('clearance_acadterm.syid', $syid)
                        ->where('clearance_acadterm.isactive', 0)
                        ->where('clearance_acadterm_acadprog.deleted', 0)
                        ->where('clearance_acadterm_acadprog.acadprogid', $string_acadporgid);
                })
                ->select(
                    'clearance_acadterm.id',
                    'clearance_acadterm.term'
                )
                ->get();
        } else {
            $getterm = DB::table('clearance_acadterm')
                ->join('clearance_acadterm_acadprog', function ($join) use ($syid, $acadprog) {
                    $join->on('clearance_acadterm.id', '=', 'clearance_acadterm_acadprog.termid')
                        ->where('clearance_acadterm.deleted', 0)
                        ->where('clearance_acadterm.syid', $syid)
                        ->where('clearance_acadterm.isactive', 0)
                        ->where('clearance_acadterm_acadprog.deleted', 0)
                        ->where('clearance_acadterm_acadprog.acadprogid', $acadprog);
                })
                ->select(
                    'clearance_acadterm.id',
                    'clearance_acadterm.term'
                )
                ->get();
        }

        foreach ($getterm as $item) {
            $data = array(
                'id' => $item->id,
                'text' => $item->term
            );
            $terms[] = $data;
        }

        return $terms;
    }

    public function getgradelevel_teacher(Request $request)
    {
        $syid = $request->get('syid');
        $requestedby = $request->get('requestedby');
        // $acadterm = $request->get('acadterm');
        $levels = [];

        $getteacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->where('deleted', 0)
            ->get();

        $string_teacherid = json_encode($getteacherid[0]->id);

        // return json_encode($string_teacherid);

        $syid = $request->syid;
        $getgradelevel = DB::table('sections')
            ->join('sectiondetail', function ($join) use ($string_teacherid, $syid) {
                $join->on('sections.id', '=', 'sectiondetail.sectionid')
                    ->where('sectiondetail.deleted', 0)
                    ->where('sectiondetail.syid', $syid)
                    ->where('sectiondetail.teacherid', $string_teacherid);
            })
            ->join('gradelevel', function ($join) {
                $join->on('sections.levelid', '=', 'gradelevel.id')
                    ->where('gradelevel.deleted', 0);
            })
            ->select(
                'gradelevel.id',
                'gradelevel.levelname'
            )
            ->get();

        // return response()->json($getgradelevel);

        foreach ($getgradelevel as $items) {
            $data = array(
                'id' => $items->id,
                'text' => $items->levelname
            );
            $levels[] = $data;
        }

        return response()->json($levels);

    }

    public function getsection_teacher(Request $request)
    {
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        $sections = [];

        $getteacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->where('deleted', 0)
            ->get();

        $string_teacherid = json_encode($getteacherid[0]->id);

        $getsection = DB::table('sectiondetail') // find a way to store a the data in an array and use it to find the activated acadterm in the academic_term table
            ->join('sections', function ($join) use ($string_teacherid, $syid, $levelid) {
                $join->on('sectiondetail.sectionid', '=', 'sections.id')
                    ->where('sections.deleted', 0)
                    ->where('sectiondetail.deleted', 0)
                    ->where('sectiondetail.syid', $syid)
                    ->where('sections.levelid', $levelid)
                    ->where('sectiondetail.teacherid', $string_teacherid);
            })
            // ->when($levelid, function ($query, $levelid) {
            //     return $query->where('sections.levelid', $levelid); // filter grade level
            // })
            ->select(
                'sections.id',
                'sections.sectionname'
            )
            ->get();

        foreach ($getsection as $item) {
            $data = array(
                'id' => $item->id,
                'text' => $item->sectionname
            );
            $sections[] = $data;
        }

        return $sections;
    }

    public function getclearancestud_teacher(Request $request)
    {
        $action = $request->get('action');
        $syid = $request->get('syid');
        $acadterm = $request->get('acadterm');
        $sectionid = $request->get('sectionid');
        $levelid = $request->get('levelid');
        $subjectid = $request->get('subjectid');
        $clearance_type = $request->get('clearance_type');
        $clearstatus = $request->get('clearstatus');
        $getstudid = "";
        $getstudent = "";
        $iseanbled_subjteacher = "";
        $iseanbled_adviser = "";



        $getteacherid = DB::table('teacher') // fetch teacher ID
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->where('deleted', 0)
            ->get();


        $string_teacherid = json_encode($getteacherid[0]->id);


        $getacademicprogid = DB::table('gradelevel')
            ->join('academicprogram', function ($join) use ($levelid) {
                $join->on('gradelevel.acadprogid', '=', 'academicprogram.id')
                    ->where('gradelevel.id', $levelid);
            })
            ->select(
                'gradelevel.acadprogid'
            )
            ->get();


        $string_acadprogid = json_encode($getacademicprogid[0]->acadprogid);

        // return $request->all();   

        $issubj_teach_enabled = DB::table('clearance_acadterm_acadprog')
            ->select('subjteacher')
            ->where('termid', $acadterm)
            ->where('acadprogid', $string_acadprogid)
            ->where('deleted', 0)
            ->get();

        // return $issubj_teach_enabled;

        // $issubj_teach_enabled = DB::table('clearance_signatory')
        //     ->select('acadprogid')
        //     ->where('title', 'SUBJECT TEACHER')
        //     ->where('teacherid', 0)
        //     ->where('departmentid', 'SUBJECT TEACHER')
        //     ->where('termid', $acadterm)
        //     ->where('syid', $syid)
        //     ->where('isactive', 0)
        //     ->get()
        //     ->pluck('acadprogid')
        //     ->toArray();

        $iseanbled_subjteacher = false;



        if (count($issubj_teach_enabled) > 0 && $issubj_teach_enabled[0]->subjteacher == 0) {
            $iseanbled_subjteacher = true;
        } else {
            $iseanbled_subjteacher = false;
        }


        if ($iseanbled_subjteacher == true) {
            $getassignsubj = DB::table('assignsubj')
                ->join('assignsubjdetail', function ($join) use ($sectionid, $subjectid) {
                    $join->on('assignsubj.id', '=', 'assignsubjdetail.headerid')
                        ->where('assignsubjdetail.deleted', 0)
                        ->where('assignsubj.deleted', 0)
                        ->where('assignsubjdetail.subjid', $subjectid)
                        ->where('sectionid', $sectionid);
                })
                ->select('assignsubj.sectionid', 'assignsubjdetail.subjid', 'assignsubjdetail.teacherid')
                ->get();

            foreach ($getassignsubj as $subject) {
                $cleartype = 0;
                $section = $subject->sectionid;
                $subjid = $subject->subjid;
                $teacherid = $subject->teacherid;

                // $check_studclearancesubj = DB::table('clearance_stud_status')
                // ->join('clearance_studinfo', function($join){
                //     $join->on('clearance_stud_status.clearance_studid', '=', 'clearance_studinfo.id')
                //     ->where('clearance_studinfo.deleted', 0)
                //     ->where('clearance_studinfo.isactive', 0);
                // })
                // ->join('enrolledstud', function($join) use($section){
                //     $join->on('clearance_studinfo.studid', '=', 'enrolledstud.studid')
                //     ->where('enrolledstud.deleted', 0)
                //     ->where('enrolledstud.sectionid', $section);
                // })
                // ->select('clearance_stud_status.id','clearance_stud_status.clearance_studid')
                // ->where('clearance_stud_status.clearance_type', $cleartype)
                // ->where('clearance_stud_status.subject_id', $subjid)
                // ->where('clearance_stud_status.teacher_id', $teacherid)
                // ->get();

                // if($check_studclearancesubj->isEmpty()){
                $getstudid = $this->clearance_studinfo($string_acadprogid, $syid, $sectionid, $acadterm);
                // Insert clearance_stud_status
                foreach ($getstudid as $stud) {
                    $id = $stud->id;

                    $getstatusid = DB::table('clearance_stud_status') // check if student clearance has been approved once
                        ->select('id')
                        ->where('clearance_studid', $id)
                        ->where('clearance_type', $cleartype)
                        ->where('teacher_id', $teacherid)
                        ->where('subject_id', $subjid)
                        ->get();

                    if ($getstatusid->isEmpty()) {
                        $isempty_studid = true; // if student is not approved once
                    } else {
                        $isempty_studid = false; // if student is already approved once
                    }

                    if ($isempty_studid == true) {
                        $savestudclearance = DB::table('clearance_stud_status')
                            ->insert([
                                'clearance_studid' => $id,
                                'clearance_type' => $cleartype,
                                'teacher_id' => $teacherid,
                                'subject_id' => $subjid,
                                'status' => 1,
                                'isactive' => 0,
                                'deleted' => 0,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);
                    }
                }
                // Insert clearance_stud_status -close
                // }
            }
        }
        // Insert assigned subject of student -close


        $isadviser_enabled = DB::table('clearance_acadterm_acadprog')
            ->select('classadviser')
            ->where('termid', $acadterm)
            ->where('acadprogid', $string_acadprogid)
            ->where('deleted', 0)
            ->get();

        // // Insert class Adviser
        // $issubj_teach_enabled = DB::table('clearance_signatory')
        //     ->select('acadprogid')
        //     ->where('title', 'CLASS ADVISER')
        //     ->where('teacherid', 0)
        //     ->where('departmentid', 'CLASS ADVISER')
        //     ->where('termid', $acadterm)
        //     ->where('syid', $syid)
        //     ->where('isactive', 0)
        //     ->get()
        //     ->pluck('acadprogid')
        //     ->toArray();


        if (count($isadviser_enabled) > 0 && $isadviser_enabled[0]->classadviser === 0) {
            $iseanbled_adviser = true;
        } else {
            $iseanbled_adviser = false;
        }


        if ($iseanbled_adviser == true) {
            // $check_studclearance = DB::table('clearance_stud_status')
            // ->join('clearance_studinfo', function($join){
            //     $join->on('clearance_stud_status.clearance_studid', '=', 'clearance_studinfo.id')
            //     ->where('clearance_studinfo.deleted', 0)
            //     ->where('clearance_studinfo.isactive', 0);
            // })
            // ->join('enrolledstud', function($join) use($sectionid){
            //     $join->on('clearance_studinfo.studid', '=', 'enrolledstud.studid')
            //     ->where('enrolledstud.deleted', 0)
            //     ->where('enrolledstud.sectionid', $sectionid);
            // })
            // ->select('clearance_stud_status.id','clearance_stud_status.clearance_studid')
            // ->where('clearance_stud_status.clearance_type', $clearance_type)
            // ->where('clearance_stud_status.subject_id', $subjectid)
            // ->where('clearance_stud_status.teacher_id', $getteacherid[0]->id)
            // ->get();

            // if($check_studclearance->isEmpty()){
            //     $getstudid = getstudentclearance($string_acadprogid, $syid, $sectionid);
            //     // insert student in clearance_studinfo if their is no record found
            //     if ($getstudid->isEmpty()) {
            //         /*** Fetch enrolled student***/
            //         $enrolledstud = fetchenrolledstud($string_acadprogid, $syid, $sectionid);    
            $getstudid = $this->getstudentclearance($string_acadprogid, $syid, $sectionid);
            $count_getstudid = $getstudid->count();

            /*** Fetch enrolled student***/
            $enrolledstud = $this->fetchenrolledstud($string_acadprogid, $syid, $sectionid);
            $count_enrolledstud = $enrolledstud->count();

            if ($count_getstudid < $count_enrolledstud) {
                foreach ($enrolledstud as $stud) {
                    $studid = $stud->studid;
                    if (isset($stud->sectionid)) {
                        $sectionid = $stud->sectionid;
                        $levelid = $stud->levelid;
                    } else {
                        $sectionid = $stud->sectionID;
                        $levelid = $stud->yearLevel;
                    }

                    $check_duplicate = DB::table('clearance_studinfo') // use foreach to check every student if already have record in clearance_studinfo
                        ->select('id')
                        ->where('studid', '=', $studid)
                        ->where('termid', '=', $acadterm)
                        ->where('syid', '=', $syid)
                        ->get();
                    if ($check_duplicate->isEmpty()) {
                        $insertstud = DB::table('clearance_studinfo') // if student don't have a record it will be inserted to the table
                            ->insertGetId([
                                'studid' => $studid,
                                'syid' => $syid,
                                'termid' => $acadterm,
                                'isactive' => 0,
                                'iscleared' => 1,
                                'deleted' => 0,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                            ]);
                    }
                    // else{
                    //     $insertstud= DB::table('clearance_studinfo')
                    //         ->select('id')
                    //         ->where('studid', '=', $studid)
                    //         ->where('termid', '=', $acadterm)
                    //         ->where('syid', '=', $syid)
                    //         ->get();
                    // }
                }

                $getstudid = $this->getstudentclearance($string_acadprogid, $syid, $sectionid);
            }
            // insert student in clearance_studinfo if their is no record found

            foreach ($getstudid as $stud) {
                $id = $stud->id;

                $getstatusid = DB::table('clearance_stud_status') // check if student clearance has been approved once
                    ->select('id')
                    ->where('clearance_studid', $id)
                    ->where('clearance_type', $clearance_type)
                    ->where('teacher_id', $string_teacherid)
                    ->where('subject_id', $subjectid)
                    ->get();

                if ($getstatusid->isEmpty()) {
                    $isempty_studid = true; // if student is not approved once
                } else {
                    $isempty_studid = false; // if student is already approved once
                }

                if ($isempty_studid == true) {
                    $savestudclearance = DB::table('clearance_stud_status')
                        ->insert([
                            'clearance_studid' => $id,
                            'clearance_type' => $clearance_type,
                            'teacher_id' => $string_teacherid,
                            'subject_id' => $subjectid,
                            'status' => 1,
                            'deleted' => 0,
                            'isactive' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                }
            }
            // }
        }
        // Insert class Adviser -close

        // fetch the students clearance data 
        if ($string_acadprogid == 5) {
            $getstudent = DB::table('clearance_studinfo')
                ->join('studinfo', function ($join) {
                    $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                        ->where('studinfo.deleted', 0);
                })
                ->join('sh_enrolledstud', function ($join) use ($syid, $sectionid) {
                    $join->on('clearance_studinfo.studid', '=', 'sh_enrolledstud.studid')
                        ->where('sh_enrolledstud.deleted', 0)
                        ->where('sh_enrolledstud.syid', $syid)
                        ->where('sh_enrolledstud.sectionid', $sectionid);
                })
                ->join('sectiondetail', function ($join) {
                    $join->on('sh_enrolledstud.sectionid', '=', 'sectiondetail.sectionid')
                        ->where('sectiondetail.deleted', 0);
                })
                ->join('gradelevel', function ($join) {
                    $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id')
                        ->where('gradelevel.deleted', 0);
                })
                ->leftJoin('clearance_stud_status', function ($join) {
                    $join->on('clearance_studinfo.id', '=', 'clearance_stud_status.clearance_studid')
                        ->where('clearance_stud_status.isactive', 0);
                })
                ->select(
                    // 'clearance_studinfo.id',
                    'clearance_studinfo.iscleared',
                    'clearance_studinfo.cleareddatetime',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'sh_enrolledstud.sectionid',
                    'clearance_stud_status.id',
                    'clearance_stud_status.clearance_studid',
                    'clearance_stud_status.status',
                    'clearance_stud_status.remarks',
                    'clearance_stud_status.approveddatetime',
                    'clearance_stud_status.updateddatetime',
                    'clearance_stud_status.subject_id',
                    'sectiondetail.sectname',
                    'gradelevel.levelname'
                )
                ->where('clearance_studinfo.isactive', 0)
                ->where('sh_enrolledstud.levelid', $levelid)
                ->where('clearance_studinfo.termid', $acadterm)
                ->where('clearance_stud_status.subject_id', $subjectid)
                // ->when($levelid, function ($query, $levelid) {
                //     return $query->where('sh_enrolledstud.levelid', $levelid); // filter grade level
                // })
                // ->when($acadterm, function ($query, $acadterm) {
                //     return $query->where('clearance_studinfo.termid', $acadterm);
                // })
                // ->when($subjectid, function ($query, $subjectid) {
                //     return $query->where('clearance_stud_status.subject_id', $subjectid);
                // })
                ->when(isset($clearstatus), function ($query) use ($clearstatus) {
                    return $query->where('clearance_stud_status.status', $clearstatus);
                })
                ->get();
        } else {
            $getstudent = DB::table('clearance_studinfo')
                ->join('studinfo', function ($join) {
                    $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                        ->where('studinfo.deleted', 0);
                })
                ->join('enrolledstud', function ($join) use ($syid, $sectionid) {
                    $join->on('clearance_studinfo.studid', '=', 'enrolledstud.studid')
                        ->where('enrolledstud.deleted', 0)
                        ->where('enrolledstud.syid', $syid)
                        ->where('enrolledstud.sectionid', $sectionid);
                })
                ->join('sectiondetail', function ($join) {
                    $join->on('enrolledstud.sectionid', '=', 'sectiondetail.sectionid')
                        ->where('sectiondetail.deleted', 0);
                })
                ->join('gradelevel', function ($join) {
                    $join->on('enrolledstud.levelid', '=', 'gradelevel.id')
                        ->where('gradelevel.deleted', 0);
                })
                ->leftJoin('clearance_stud_status', function ($join) {
                    $join->on('clearance_studinfo.id', '=', 'clearance_stud_status.clearance_studid')
                        ->where('clearance_stud_status.isactive', 0);
                })
                // ->join('sectiondetail', 'enrolledstud.sectionid', '=', 'sectiondetail.sectionid')
                // ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->select(
                    // 'clearance_studinfo.id',
                    'clearance_studinfo.iscleared',
                    'clearance_studinfo.cleareddatetime',
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'enrolledstud.sectionid',
                    'clearance_stud_status.id',
                    'clearance_stud_status.clearance_studid',
                    'clearance_stud_status.status',
                    'clearance_stud_status.remarks',
                    'clearance_stud_status.approveddatetime',
                    'clearance_stud_status.updateddatetime',
                    'clearance_stud_status.subject_id',
                    'sectiondetail.sectname',
                    'gradelevel.levelname'
                )
                ->where('clearance_studinfo.isactive', 0)
                ->where('enrolledstud.levelid', $levelid)
                ->where('clearance_studinfo.termid', $acadterm)
                ->where('clearance_stud_status.subject_id', $subjectid)
                // ->when($levelid, function ($query, $levelid) {
                //     return $query->where('enrolledstud.levelid', $levelid); // filter grade level
                // })
                // ->when($acadterm, function ($query, $acadterm) {
                //     return $query->where('clearance_studinfo.termid', $acadterm);
                // })
                // ->when($subjectid, function ($query, $subjectid) {
                //     return $query->where('clearance_stud_status.subject_id', $subjectid);
                // })
                ->when(isset($clearstatus), function ($query) use ($clearstatus) {
                    return $query->where('clearance_stud_status.status', $clearstatus);
                })
                ->get();
        }

        if ($getstudent->isEmpty()) {
            // $isnoclearance = true; // used to set if the Approve All button will be disabled
            if ($string_acadprogid == 5) {
                $getstudent = DB::table('sh_enrolledstud') // SHS
                    ->join('studinfo', function ($join) use ($syid, $levelid, $sectionid) {
                        $join->on('sh_enrolledstud.studid', '=', 'studinfo.id')
                            ->where('sh_enrolledstud.deleted', 0)
                            ->where('studinfo.deleted', 0)
                            ->where('sh_enrolledstud.syid', $syid)
                            ->where('sh_enrolledstud.levelid', $levelid)
                            ->where('sh_enrolledstud.sectionid', $sectionid);
                    })
                    ->join('sectiondetail', function ($join) {
                        $join->on('sh_enrolledstud.sectionid', '=', 'sectiondetail.sectionid')
                            ->where('sectiondetail.deleted', 0);
                    })
                    ->join('gradelevel', function ($join) {
                        $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id')
                            ->where('gradelevel.deleted', 0);
                    })
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'sh_enrolledstud.sectionid',
                        'sectiondetail.sectname',
                        'gradelevel.levelname'
                    )
                    ->when(isset($clearstatus), function ($query) use ($clearstatus) {
                        return $query->where('clearance_stud_status.status', $clearstatus);
                    })
                    ->get();
            } else {
                $getstudent = DB::table('enrolledstud') // BasicEd w/o SHS
                    ->join('studinfo', function ($join) use ($syid, $levelid, $sectionid) {
                        $join->on('enrolledstud.studid', '=', 'studinfo.id')
                            ->where('enrolledstud.deleted', 0)
                            ->where('studinfo.deleted', 0)
                            ->where('enrolledstud.syid', $syid)
                            ->where('enrolledstud.levelid', $levelid)
                            ->where('enrolledstud.sectionid', $sectionid);
                    })
                    ->join('sectiondetail', function ($join) {
                        $join->on('enrolledstud.sectionid', '=', 'sectiondetail.sectionid')
                            ->where('sectiondetail.deleted', 0);
                    })
                    ->join('gradelevel', function ($join) {
                        $join->on('enrolledstud.levelid', '=', 'gradelevel.id')
                            ->where('gradelevel.deleted', 0);
                    })
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'enrolledstud.sectionid',
                        'sectiondetail.sectname',
                        'gradelevel.levelname'
                    )
                    ->when(isset($clearstatus), function ($query) use ($clearstatus) {
                        return $query->where('clearance_stud_status.status', $clearstatus);
                    })
                    ->get();
            }
        }

        if ($action == 'SUBJECT TEACHER') {
            $isclearance_enabled = $iseanbled_subjteacher;
        } else if ($action == 'CLASS ADVISER') {
            $isclearance_enabled = $iseanbled_adviser;
        } else {
            $isclearance_enabled = "";
        }

        return [$getstudent, $isclearance_enabled];
    }


    /*** function fetch student clearance studinfo ***/
    function getstudentclearance($string_acadprogid, $syid, $section)
    {

        if ($string_acadprogid == 5) {
            $getstudid = DB::table('clearance_studinfo') // get student where section is set
                ->join('studinfo', function ($join) {
                    $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                        ->where('studinfo.deleted', 0);
                })
                ->join('sh_enrolledstud', function ($join) use ($syid, $section) {
                    $join->on('clearance_studinfo.studid', '=', 'sh_enrolledstud.studid')
                        ->where('sh_enrolledstud.deleted', 0)
                        ->where('sh_enrolledstud.syid', $syid)
                        ->where('sh_enrolledstud.sectionid', $section);
                })
                ->select('clearance_studinfo.id')
                ->get();
        } else {
            $getstudid = DB::table('clearance_studinfo') // get student where section is set
                ->join('studinfo', function ($join) {
                    $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                        ->where('studinfo.deleted', 0);
                })
                ->join('enrolledstud', function ($join) use ($syid, $section) {
                    $join->on('clearance_studinfo.studid', '=', 'enrolledstud.studid')
                        ->where('enrolledstud.deleted', 0)
                        ->where('enrolledstud.syid', $syid)
                        ->where('enrolledstud.sectionid', $section);
                })
                ->select('clearance_studinfo.id')
                ->get();
        }

        return $getstudid;
    }
    /*** function fetch student clearance studinfo -close ***/

    /*** function fetch enrolled student by gradelevel ***/
    function fetchenrolledstud($string_acadprogid, $syid, $sectionid)
    {
        $get_gradelevel = DB::table('gradelevel')
            ->join('academicprogram', function ($join) use ($string_acadprogid) {
                $join->on('gradelevel.acadprogid', '=', 'academicprogram.id')
                    ->where('gradelevel.deleted', '=', 0);
            })
            ->where('gradelevel.acadprogid', $string_acadprogid)
            ->select('gradelevel.id')
            ->get()
            ->pluck('id')
            ->toArray();

        $get_enrolledstud = DB::table('enrolledstud') // fetch all enrolled student
            ->select('studid', 'levelid', 'sectionid')
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->where('studstatus', '!=', 0)
            ->where('sectionid', $sectionid)
            ->whereIn('levelid', $get_gradelevel)
            ->get();

        $get_shenrolledstud = DB::table('sh_enrolledstud') // fetch all shs enrolled student
            ->select('studid', 'levelid', 'sectionid')
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->where('studstatus', '!=', 0)
            ->where('sectionid', $sectionid)
            ->whereIn('levelid', $get_gradelevel)
            ->get();

        $get_collegeenrolledstud = DB::table('college_enrolledstud') // fetch all shs enrolled student
            ->select('studid', 'yearLevel', 'sectionID')
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->where('studstatus', '!=', 0)
            ->whereIn('yearLevel', $get_gradelevel)
            ->get();

        $enrolled_basiced = $get_enrolledstud->concat($get_shenrolledstud);
        $enrolledstud = $enrolled_basiced->concat($get_collegeenrolledstud);

        return $enrolledstud;
    }
    /*** function fetch enrolled student by gradelevel -close ***/

    /*** function check and insert clearance_studinfo ***/
    function clearance_studinfo($string_acadprogid, $syid, $sectionid, $acadterm)
    {
        $getstudid = $this->getstudentclearance($string_acadprogid, $syid, $sectionid);
        $count_getstudid = $getstudid->count();

        /*** Fetch enrolled student***/
        $enrolledstud = $this->fetchenrolledstud($string_acadprogid, $syid, $sectionid);
        $count_enrolledstud = $enrolledstud->count();

        // insert student in clearance_studinfo if their is no record found
        if ($count_getstudid < $count_enrolledstud) {
            foreach ($enrolledstud as $stud) {
                $studid = $stud->studid;
                if (isset($stud->sectionid)) {
                    $sectionid = $stud->sectionid;
                    $levelid = $stud->levelid;
                } else {
                    $sectionid = $stud->sectionID;
                    $levelid = $stud->yearLevel;
                }

                $check_duplicate = DB::table('clearance_studinfo') // use foreach to check every student if already have record in clearance_studinfo
                    ->select('id')
                    ->where('studid', '=', $studid)
                    ->where('termid', '=', $acadterm)
                    ->where('syid', '=', $syid)
                    ->get();
                if ($check_duplicate->isEmpty()) {
                    $insertstud = DB::table('clearance_studinfo') // if student don't have a record it will be inserted to the table
                        ->insertGetId([
                            'studid' => $studid,
                            'syid' => $syid,
                            'termid' => $acadterm,
                            'isactive' => 0,
                            'iscleared' => 1,
                            'deleted' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                }
                // else{
                //     $insertstud= DB::table('clearance_studinfo')
                //         ->select('id')
                //         ->where('studid', '=', $studid)
                //         ->where('termid', '=', $acadterm)
                //         ->where('syid', '=', $syid)
                //         ->get();
                // }
            }

            $getstudid = getstudentclearance($string_acadprogid, $syid, $sectionid);
        }
        // insert student in clearance_studinfo if their is no record found
        return $getstudid;
    }
    /*** function check and insert clearance_studinfo -close ***/

    public function approve_clearance_teacher(Request $request)
    {
        $clear_stat_id = $request->get('clear_stat_id');
        // $clear_studid = $request->get('clear_studid'); // clearance_studinfo id foreign key of clearance_stud_status table
        $status = $request->get('status'); // 0-approved  1-Unapproved 2-Pending
        $remarks = $request->get('remarks');
        // $clearance_type = $request->get('clearance_type');// 0-sbuject teacher & advicer clearance_signatory(id)-signatory(principal/finance/registrar)
        // $subjectid = $request->get('subjectid');

        try {

            // $getteacherid= DB::table('teacher') // fetch teacher ID
            // ->select('id')
            // ->where('userid', auth()->user()->id)
            // ->where('deleted', 0)
            // ->get();

            // $string_teacherid = json_encode($getteacherid[0]->id);

            // $getstatusid = DB::table('clearance_stud_status') // check if student clearance has been approved once
            // ->select('id')
            // ->where('clearance_studid', $clear_studid)
            // ->where('clearance_type', $clearance_type)
            // ->where('teacher_id', $string_teacherid)
            // ->where('subject_id', $subjectid)
            // ->get();

            // $clear_statusid= json_encode($getstatusid[0]->id);

            if ($status == 0) {
                $savestudclearance = DB::table('clearance_stud_status')
                    ->where('id', $clear_stat_id)
                    ->update([
                        'status' => 0,
                        'remarks' => $remarks,
                        'approvedby' => auth()->user()->id,
                        'approveddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            } else {
                $savestudclearance = DB::table('clearance_stud_status')
                    ->where('id', $clear_stat_id)
                    ->update([
                        'status' => $status,
                        'remarks' => $remarks,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

            return array(
                (object) [
                    'status' => 1,
                    'data' => 'Saved Successfully!',
                    // 'info'=> $getstatusid
                ]
            );

        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public function approve_allclearance_teacher(Request $request)
    {
        // $remarks = $request->get('remarks');
        $clearance_type = $request->get('clearance_type');
        $syid = $request->get('syid');
        $section = $request->get('section');
        $subjectid = $request->get('subjectid');
        $levelid = $request->get('levelid');
        $status = $request->get('status');

        try {
            $getacademicprogid = DB::table('gradelevel') // get acaademic program of section
                ->join('academicprogram', function ($join) use ($levelid) {
                    $join->on('gradelevel.acadprogid', '=', 'academicprogram.id')
                        ->where('gradelevel.id', $levelid);
                })
                ->select(
                    'gradelevel.acadprogid'
                )
                ->get();
            $string_acadprogid = json_encode($getacademicprogid[0]->acadprogid);

            $getteacherid = DB::table('teacher') // fetch teacher ID
                ->select('id')
                ->where('userid', auth()->user()->id)
                ->where('deleted', 0)
                ->get();

            $string_teacherid = json_encode($getteacherid[0]->id);

            if ($string_acadprogid == 5) {
                $getstudid = DB::table('clearance_studinfo') // get student where section is set
                    ->join('studinfo', function ($join) {
                        $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                            ->where('studinfo.deleted', 0);
                    })
                    ->join('sh_enrolledstud', function ($join) use ($syid, $section) {
                        $join->on('clearance_studinfo.studid', '=', 'sh_enrolledstud.studid')
                            ->where('sh_enrolledstud.deleted', 0)
                            ->where('sh_enrolledstud.syid', $syid)
                            ->where('sh_enrolledstud.sectionid', $section);
                    })
                    ->select('clearance_studinfo.id')
                    ->get()
                    ->pluck('id')
                    ->toArray();
            } else {
                $getstudid = DB::table('clearance_studinfo') // get student where section is set
                    ->join('studinfo', function ($join) {
                        $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                            ->where('studinfo.deleted', 0);
                    })
                    ->join('enrolledstud', function ($join) use ($syid, $section) {
                        $join->on('clearance_studinfo.studid', '=', 'enrolledstud.studid')
                            ->where('enrolledstud.deleted', 0)
                            ->where('enrolledstud.syid', $syid)
                            ->where('enrolledstud.sectionid', $section);
                    })
                    ->select('clearance_studinfo.id')
                    ->get()
                    ->pluck('id')
                    ->toArray();
            }

            if ($status == 0) {
                $getstatusid = DB::table('clearance_stud_status')
                    ->select('id')
                    ->whereIn('clearance_studid', $getstudid)
                    ->where('clearance_type', $clearance_type)
                    ->where('teacher_id', $string_teacherid)
                    ->where('subject_id', $subjectid)
                    ->whereIn('status', [1, 2])
                    ->where('isactive', 0)
                    ->get()
                    ->pluck('id')
                    ->toArray();

                $savestudclearance = DB::table('clearance_stud_status')
                    ->whereIn('id', $getstatusid)
                    ->update([
                        'status' => $status,
                        // 'remarks' => $remarks,
                        'approvedby' => auth()->user()->id,
                        'approveddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

            if ($status == 2) {
                $getstatusid = DB::table('clearance_stud_status')
                    ->select('id')
                    ->whereIn('clearance_studid', $getstudid)
                    ->where('clearance_type', $clearance_type)
                    ->where('teacher_id', $string_teacherid)
                    ->where('subject_id', $subjectid)
                    ->where('status', 1)
                    ->where('isactive', 0)
                    ->get()
                    ->pluck('id')
                    ->toArray();

                $savestudclearance = DB::table('clearance_stud_status')
                    ->whereIn('id', $getstatusid)
                    ->update([
                        'status' => $status,
                        // 'remarks' => $remarks,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

            return array(
                (object) [
                    'status' => 1,
                    'data' => 'Saved Successfully!',
                    'info' => $getstatusid
                ]
            );

        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public function approve_allclearanceadvisory(Request $request)
    {
        $clearance_type = $request->get('clearance_type');
        $syid = $request->get('syid');
        $section = $request->get('section');
        $subjectid = $request->get('subjectid');
        $levelid = $request->get('levelid');
        $acadterm = $request->get('acadterm');
        $status = $request->get('status');

        try {
            $getacademicprogid = DB::table('gradelevel') // get acaademic program of section
                ->join('academicprogram', function ($join) use ($levelid) {
                    $join->on('gradelevel.acadprogid', '=', 'academicprogram.id')
                        ->where('gradelevel.id', $levelid);
                })
                ->select(
                    'gradelevel.acadprogid'
                )
                ->get();
            $string_acadprogid = json_encode($getacademicprogid[0]->acadprogid);

            $getteacherid = DB::table('teacher') // fetch teacher ID
                ->select('id')
                ->where('userid', auth()->user()->id)
                ->where('deleted', 0)
                ->get();

            $string_teacherid = json_encode($getteacherid[0]->id);

            if ($string_acadprogid == 5) {
                $getstudid = DB::table('clearance_studinfo') // get student where section is set
                    ->join('studinfo', function ($join) {
                        $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                            ->where('studinfo.deleted', 0);
                    })
                    ->join('sh_enrolledstud', function ($join) use ($syid, $section) {
                        $join->on('clearance_studinfo.studid', '=', 'sh_enrolledstud.studid')
                            ->where('sh_enrolledstud.deleted', 0)
                            ->where('sh_enrolledstud.syid', $syid)
                            ->where('sh_enrolledstud.sectionid', $section);
                    })
                    ->select('clearance_studinfo.id')
                    ->get();
            } else {
                $getstudid = DB::table('clearance_studinfo') // get student where section is set
                    ->join('studinfo', function ($join) {
                        $join->on('clearance_studinfo.studid', '=', 'studinfo.id')
                            ->where('studinfo.deleted', 0);
                    })
                    ->join('enrolledstud', function ($join) use ($syid, $section) {
                        $join->on('clearance_studinfo.studid', '=', 'enrolledstud.studid')
                            ->where('enrolledstud.deleted', 0)
                            ->where('enrolledstud.syid', $syid)
                            ->where('enrolledstud.sectionid', $section);
                    })
                    ->select('clearance_studinfo.id')
                    ->get();
            }

            foreach ($getstudid as $item) {
                //fetch the status of subteacher enable status
                $issubj_teach_enabled = DB::table('clearance_acadterm_acadprog')
                    ->select('subjteacher')
                    ->where('termid', $acadterm)
                    ->where('acadprogid', $string_acadprogid)
                    ->where('deleted', 0)
                    ->get();

                $issubjteacher_enbled = false;

                // check if subject teacher clearance is enabled
                if ($issubj_teach_enabled[0]->subjteacher === 0) {
                    $issubjteacher_enbled = true;
                } else {
                    $issubjteacher_enbled = false;
                }

                if ($issubjteacher_enbled == true) {
                    if ($status == 0) {
                        $getsubjectstatus = DB::table('clearance_stud_status')
                            ->select('status')
                            ->where('clearance_studid', $item->id)
                            ->where('clearance_type', $clearance_type)
                            ->where('subject_id', '!=', 'CLASS ADVISER')
                            ->where('isactive', 0)
                            ->get();

                        // check if all subject is approved
                        $allStatusAreZero = $getsubjectstatus->every(function ($item) {
                            return $item->status === 0;
                        });

                        if ($allStatusAreZero === true) {
                            $getstatusid = DB::table('clearance_stud_status')
                                ->select('id')
                                ->where('clearance_studid', $item->id)
                                ->where('clearance_type', $clearance_type)
                                ->where('teacher_id', $string_teacherid)
                                ->where('subject_id', $subjectid)
                                ->whereIn('status', [1, 2])
                                ->where('isactive', 0)
                                ->get();

                            if ($getstatusid->isNotEmpty()) {
                                $string_id = json_encode($getstatusid[0]->id);
                                $savestudclearance = DB::table('clearance_stud_status')
                                    ->where('id', $string_id)
                                    ->update([
                                        'status' => 0,
                                        'approvedby' => auth()->user()->id,
                                        'approveddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                            }
                        }
                    }

                    if ($status == 2) {
                        $getstatusid = DB::table('clearance_stud_status')
                            ->select('id')
                            ->where('clearance_studid', $item->id)
                            ->where('clearance_type', $clearance_type)
                            ->where('teacher_id', $string_teacherid)
                            ->where('subject_id', $subjectid)
                            ->where('status', 1)
                            ->where('isactive', 0)
                            ->get();

                        if ($getstatusid->isNotEmpty()) {
                            $string_id = json_encode($getstatusid[0]->id);
                            $savestudclearance = DB::table('clearance_stud_status')
                                ->where('id', $string_id)
                                ->update([
                                    'status' => $status,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);
                        }
                    }
                } else {
                    if ($status == 0) {
                        $getstatusid = DB::table('clearance_stud_status')
                            ->select('id')
                            ->where('clearance_studid', $item->id)
                            ->where('clearance_type', $clearance_type)
                            ->where('teacher_id', $string_teacherid)
                            ->where('subject_id', $subjectid)
                            ->whereIn('status', [1, 2])
                            ->where('isactive', 0)
                            ->get();

                        if ($getstatusid->isNotEmpty()) {
                            $string_id = json_encode($getstatusid[0]->id);
                            $savestudclearance = DB::table('clearance_stud_status')
                                ->where('id', $string_id)
                                ->update([
                                    'status' => $status,
                                    'approvedby' => auth()->user()->id,
                                    'approveddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);
                        }
                    }

                    if ($status == 2) {
                        $getstatusid = DB::table('clearance_stud_status')
                            ->select('id')
                            ->where('clearance_studid', $item->id)
                            ->where('clearance_type', $clearance_type)
                            ->where('teacher_id', $string_teacherid)
                            ->where('subject_id', $subjectid)
                            ->where('status', 1)
                            ->where('isactive', 0)
                            ->get();

                        if ($getstatusid->isNotEmpty()) {
                            $string_id = json_encode($getstatusid[0]->id);
                            $savestudclearance = DB::table('clearance_stud_status')
                                ->where('id', $string_id)
                                ->update([
                                    'status' => $status,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ]);
                        }
                    }
                }
            }

            return array(
                (object) [
                    'status' => 1,
                    'data' => 'Saved Successfully!',
                ]
            );

        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    public function getsubjects(Request $request)
    {
        $clear_studid = $request->get('clear_studid');
        // $status = $request->get('clearance_type');
        $clearance_type = $request->get('clearance_type');

        $getsubjectstatus = DB::table('clearance_stud_status')
            ->select('id', 'subject_id', 'status')
            ->where('clearance_studid', $clear_studid)
            ->where('clearance_type', $clearance_type)
            ->where('subject_id', '!=', 'CLASS ADVISER')
            ->where('isactive', 0)
            ->get();

        return $getsubjectstatus;
    }

    // Clearance By Subject
    public function clearancebysubject()
    {
        return view('clearance.studentsclearancesubject');
    }

    public static function store_error($e)
    { // Error logs
        DB::table('zerrorlogs')
            ->insert([
                'error' => $e,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

        return array(
            (object) [
                'status' => 0,
                'data' => "$e",
                // 'data'=>'Something went wrong!'
            ]
        );
    }

    //View Grade
    public static function viewgrades()
    {
        return view('clearance.s_grades');
    }

    //View Ledger
    public static function viewledger()
    {
        return view('clearance.ledger');
    }
}
