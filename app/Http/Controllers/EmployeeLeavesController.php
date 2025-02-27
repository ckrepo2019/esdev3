<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
use Crypt;
use DatePeriod;
use DateTime;
use DateInterval;
use Session;
use App\Http\Controllers\NotificationController\NotificationController;
use App\Mail\HRMail;
use Mail;
class EmployeeLeavesController extends Controller
{
    public function applyindex()
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

        if (Session::get('currentPortal') == 1) {

            $extends = "teacher.layouts.app";

        } elseif (Session::get('currentPortal') == 2) {

            $extends = "principalsportal.layouts.app2";

        } elseif (Session::get('currentPortal') == 3) {

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

        } elseif (Session::get('currentPortal') == 8) {

            $extends = "admission.layouts.app2";

        }elseif($refid == 35){
            $extends = "tesda.layouts.app2";
        }
         else {

            $extends = "general.defaultportal.layouts.app";

        }

        $id = DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted', '0')->first()->id;

        $yearofservice = DB::table('employee_personalinfo')
            ->where('employeeid', $id)
            ->first()->yos;

        // return $yearofservice;
        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait') {
            $departmentid = 0;
            $departmenthead = array();
            $getdepartmentid = DB::table('teacher')
                ->where('id', $id)
                // ->where('deleted','0')
                ->first();

            if ($getdepartmentid) {
                $departmentid = $getdepartmentid->schooldeptid ?? 0;
                if ($departmentid > 0) {
                    $departmenthead = DB::table('hr_departmentheads')
                        ->select('teacher.*')
                        ->join('teacher', 'hr_departmentheads.deptheadid', '=', 'teacher.id')
                        ->where('hr_departmentheads.deptid', $departmentid)
                        ->where('hr_departmentheads.deleted', '0')
                        ->get();
                }
            }

            $signatories = DB::table('sait_leavesignatories')
                ->select('teacher.*', 'sait_leavesignatories.description')
                ->join('teacher', 'sait_leavesignatories.userid', '=', 'teacher.userid')
                ->where('sait_leavesignatories.deleted', '0')
                ->where('teacher.deleted', '0')
                ->get();


            $leavetypes = DB::table('hr_leaves')
                ->where('deleted', '0')
                ->get();

            $leaveapplications = DB::table('sait_leaveapply')
                ->select('sait_leaveapply.*', 'hr_leaves.leave_type')
                ->join('hr_leaves', 'sait_leaveapply.leavetypeid', '=', 'hr_leaves.id')
                ->where('sait_leaveapply.deleted', '0')
                ->where('sait_leaveapply.userid', auth()->user()->id)
                ->get();

            if (count($leaveapplications) > 0) {
                foreach ($leaveapplications as $leaveapplication) {
                    $approvals = array();
                    if (count($departmenthead) > 0) {
                        foreach ($departmenthead as $depthead) {
                            $checkapprecord = DB::table('sait_approvaldetails')
                                ->where('applicationid', $leaveapplication->id)
                                ->where('approvaluserid', $depthead->userid)
                                ->where('deleted', '0')
                                ->first();

                            if ($checkapprecord) {
                                $depthead->appstatus = $checkapprecord->appstatus;
                                $depthead->appstatusdesc = $checkapprecord->appstatus == 0 ? 'Pending' : ($checkapprecord->appstatus == 1 ? 'Approved' : 'Rejected');
                                $depthead->appstatusdate = date('m/d/Y', strtotime($checkapprecord->updateddatetime));

                            } else {
                                $depthead->appstatus = 0;
                                $depthead->appstatusdesc = 'Pending';
                                $depthead->appstatusdate = '';
                            }
                            $depthead->signatorylabel = 'Department Head';
                            array_push($approvals, $depthead);
                        }
                    }
                    if (count($signatories) > 0) {
                        foreach ($signatories as $signatory) {
                            $checkapprecord = DB::table('sait_approvaldetails')
                                ->where('applicationid', $leaveapplication->id)
                                ->where('approvaluserid', $signatory->userid)
                                ->where('deleted', '0')
                                ->first();

                            if ($checkapprecord) {
                                $signatory->appstatus = $checkapprecord->appstatus;
                                $signatory->appstatusdesc = $checkapprecord->appstatus == 0 ? 'Pending' : ($checkapprecord->appstatus == 1 ? 'Approved' : 'Rejected');
                                $signatory->appstatusdate = date('m/d/Y', strtotime($checkapprecord->updateddatetime));
                                $signatory->signatorylabel = $signatory->description;

                            } else {
                                $signatory->signatorylabel = $signatory->description;
                                $signatory->appstatusdesc = 'Pending';
                                $signatory->appstatusdate = '';
                                $signatory->appstatus = 0;
                            }
                            array_push($approvals, $signatory);
                        }
                    }
                    $leaveapplication->approvals = $approvals;
                }
            }


            return view('general.leaveapplication.sait.index')
                ->with('departmentid', $departmentid)
                ->with('leaveapplications', $leaveapplications)
                ->with('leavetypes', $leavetypes)
                ->with('extends', $extends);
        } else {

            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                $leavesapplied = DB::table('employee_leaves')
                    ->select('employee_leaves.id', 'employee_leaves.remarks', 'employee_leaves.payrolldone', 'employee_leaves.numofdays', 'employee_leaves.leavestatus', 'employee_leaves.createddatetime', 'hr_leaves.leave_type as leavetype', 'hr_leaves.id as leaveid')
                    ->join('hr_leaves', 'employee_leaves.leaveid', '=', 'hr_leaves.id')
                    ->where('employee_leaves.employeeid', $id)
                    ->where('employee_leaves.deleted', '0')
                    ->where('hr_leaves.deleted', '0')
                    ->orderByDesc('employee_leaves.createddatetime')
                    ->groupBy('employee_leaves.createddatetime')
                    ->get();

                if (count($leavesapplied) > 0) {
                    foreach ($leavesapplied as $leaveapp) {
                        $leaveapp->canbedeleted = 0;
                        $approvalheads = DB::table('hr_leaveemployees')
                            ->select('teacher.id', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename')
                            ->join('hr_leaveemployeesappr', 'hr_leaveemployees.id', '=', 'hr_leaveemployeesappr.headerid')
                            ->join('teacher', 'hr_leaveemployeesappr.appuserid', '=', 'teacher.userid')
                            ->where('hr_leaveemployees.leaveid', $leaveapp->leaveid)
                            ->where('hr_leaveemployees.employeeid', $id)
                            ->where('hr_leaveemployees.deleted', '0')
                            ->where('hr_leaveemployeesappr.deleted', '0')
                            ->get();

                        if (count($approvalheads) > 0) {

                            foreach ($approvalheads as $approvalhead) {
                                $approvalhead->remarks = '';
                                $checkapproval = DB::table('employee_leavesappr')
                                    ->where('ldateid', $leaveapp->id)
                                    ->where('appuserid', $approvalhead->userid)
                                    ->where('deleted', '0')
                                    ->first();


                                if ($checkapproval) {
                                    $approvalhead->remarks = $checkapproval->remarks;
                                    $approvalhead->appstatus = $checkapproval->appstatus;
                                } else {
                                    $approvalhead->appstatus = 0;
                                }
                            }
                            if (collect($approvalheads)->where('appstatus', '0')->count() == count($approvalheads)) {
                                $leaveapp->canbedeleted = 0;
                                $leaveapp->leavestatus = 0;
                            }
                            if (collect($approvalheads)->where('appstatus', '1')->count() == count($approvalheads)) {
                                $leaveapp->canbedeleted = 1;
                                $leaveapp->leavestatus = 1;
                            }
                            if (collect($approvalheads)->where('appstatus', '2')->count() > 0) {
                                $leaveapp->canbedeleted = 1;
                                $leaveapp->leavestatus = 2;
                            }
                        }

                        $leaveapp->approvals = $approvalheads;
                        $numdaysapproved = 0;

                        $dates = DB::table('employee_leavesdetail')
                            ->select('id', 'ldate', 'dayshift', 'leavestatus', 'payrolldone')
                            ->where('headerid', $leaveapp->id)
                            ->where('deleted', '0')
                            ->get();

                        if (count($dates) > 0) {
                            foreach ($dates as $date) {
                                if (collect($approvalheads)->where('appstatus', '1')->count() == count($approvalheads)) {
                                    if ($date->dayshift == 0) {
                                        $numdaysapproved += 1;
                                    } else {
                                        $numdaysapproved += 0.5;
                                    }
                                }
                                if (collect($approvalheads)->where('appstatus', '0')->count() > 0) {
                                    if ($date->dayshift == 0) {
                                        $numdaysapproved += 1;
                                    } else {
                                        $numdaysapproved += 0.5;
                                    }
                                }
                            }
                        }



                        $leaveapp->dates = $dates;
                        $leaveapp->attachments = DB::table('employee_leavesatt')
                            ->where('headerid', $leaveapp->id)
                            ->where('deleted', '0')
                            ->get();

                        $leaveapp->countapplied = $numdaysapproved;
                        $leaveapp->countapproved = $numdaysapproved;
                    }
                }

                // return $
                // return $leavesapplied;;


            } else {

                $leavesapplied = DB::table('hr_leaveemployees')
                    ->select('hr_leaveemployees.id', 'hr_leaveemployees.remarks', 'hr_leaveemployees.payrolldone', 'hr_leaveemployees.numofdays', 'hr_leaveemployees.leavestatus', 'hr_leaveemployees.createddatetime', 'hr_leaves.leave_type as leavetype', 'hr_leaves.id as leaveid')
                    ->join('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
                    ->where('hr_leaveemployees.employeeid', $id)
                    ->where('hr_leaves.lyear', date('Y'))
                    ->where('hr_leaveemployees.deleted', '0')
                    ->orderByDesc('hr_leaveemployees.createddatetime')
                    ->groupBy('hr_leaveemployees.createddatetime')
                    ->get();

                if (count($leavesapplied) > 0) {
                    foreach ($leavesapplied as $leaveapp) {
                        $leaveapp->canbedeleted = 0;
                        $approvalheads = DB::table('hr_leaveemployees')
                            ->select('teacher.id', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename')
                            ->join('hr_leavesappr', 'hr_leaveemployees.leaveid', '=', 'hr_leavesappr.leaveid')
                            ->join('teacher', 'hr_leavesappr.appuserid', '=', 'teacher.userid')
                            ->where('hr_leaveemployees.leaveid', $leaveapp->leaveid)
                            ->where('hr_leaveemployees.employeeid', $id)
                            ->where('hr_leaveemployees.deleted', '0')
                            ->where('hr_leavesappr.deleted', '0')
                            ->get();

                        if (count($approvalheads) > 0) {

                            foreach ($approvalheads as $approvalhead) {
                                $approvalhead->remarks = '';
                                $checkapproval = DB::table('hr_leaveemployeesappr')
                                    ->where('headerid', $leaveapp->id)
                                    ->where('appuserid', $approvalhead->userid)
                                    ->where('deleted', '0')
                                    ->first();

                                if ($checkapproval) {
                                    $approvalhead->remarks = $checkapproval->remarks;
                                    $approvalhead->appstatus = $checkapproval->appstatus;
                                } else {
                                    $approvalhead->appstatus = 0;
                                }
                            }
                            if (collect($approvalheads)->where('appstatus', '0')->count() == count($approvalheads)) {
                                $leaveapp->canbedeleted = 0;
                            }
                            if (collect($approvalheads)->where('appstatus', '1')->count() > 0) {
                                $leaveapp->canbedeleted = 1;
                            }
                            if (collect($approvalheads)->where('appstatus', '2')->count() > 0) {
                                $leaveapp->canbedeleted = 1;
                                $leaveapp->leavestatus = 2;
                            }
                        }

                        $leaveapp->approvals = $approvalheads;
                        $numdaysapproved = 0;

                        $dates = DB::table('hr_leaveempdetails')
                            ->select('id', 'ldate', 'dayshift', 'leavestatus', 'payrolldone')
                            ->where('headerid', $leaveapp->id)
                            ->where('deleted', '0')
                            ->get();

                        if (count($dates) > 0) {
                            foreach ($dates as $date) {
                                if (collect($approvalheads)->where('appstatus', '1')->count() == count($approvalheads)) {
                                    if ($date->dayshift == 0) {
                                        $numdaysapproved += 1;
                                    } else {
                                        $numdaysapproved += 0.5;
                                    }
                                }
                                if (collect($approvalheads)->where('appstatus', '0')->count() > 0) {
                                    if ($date->dayshift == 0) {
                                        $numdaysapproved += 1;
                                    } else {
                                        $numdaysapproved += 0.5;
                                    }
                                }
                            }
                        }



                        $leaveapp->dates = $dates;
                        $leaveapp->attachments = DB::table('employee_leavesatt')
                            ->where('headerid', $leaveapp->id)
                            ->where('deleted', '0')
                            ->get();

                        $leaveapp->countapplied = $numdaysapproved;
                        $leaveapp->countapproved = $numdaysapproved;

                        if ($leaveapp->countapproved == count($approvalheads)) {
                            $leaveapp->leavestatus = 1;
                        }
                    }
                }

            }

            $alloweddates = array();
            $collectleavetypes = Db::table('hr_leaves')
                ->where('deleted', '0')
                ->where('isactive', '1')
                // ->whereIn('hr_leaves.lyear',array(null, date('Y')))
                ->get();

            // return $yearofservice;
            $existabove = DB::table('hr_leavesadditionalsetup')
                ->where('deleted', 0)
                ->Where('above', 1)
                ->get();

            $yearswithabove = $existabove->pluck('years');

            if ($yearswithabove->contains($yearofservice) || $yearofservice > $yearswithabove->max()) {
                $additionalinfos = DB::table('hr_leavesadditionalsetup')
                    ->where('deleted', 0)
                    ->where('years', '=', $yearofservice)
                    ->orWhere('years', 0)
                    ->orWhere('above', 1)
                    ->get();
            } else {
                $additionalinfos = DB::table('hr_leavesadditionalsetup')
                    ->where('deleted', 0)
                    ->where('years', '=', $yearofservice)
                    ->orWhere('years', 0)
                    ->get();
            }

            foreach ($collectleavetypes as $collectleavetype) {
                $collectleavetype->applicable = 0; // Set the default value
                if ($collectleavetype->days == null) {
                    $collectleavetype->days = 0;
                } else {
                    $collectleavetypedays = $collectleavetype->days;
                }
                foreach ($additionalinfos as $additionalinfo) {
                    if ($collectleavetype->id == $additionalinfo->headerid) {
                        $collectleavetype->applicable = 1;
                        $collectleavetype->days = $additionalinfo->days;
                        break; // No need to continue checking once a match is found
                    }


                }
            }
            // return collect($alloweddates);
            // return collect($collectleavetypes);

            $leavetypes = collect();
            $leavetypes = collect($leavetypes)->merge(collect($collectleavetypes)->where('lyear', null)->values())->all();
            $leavetypes = collect($leavetypes)->merge(collect($collectleavetypes)->where('lyear', date('Y'))->values())->all();
            // return $leavetypes;
            if (count($leavetypes) > 0) {
                foreach ($leavetypes as $leavetype) {

                    $leavetype->permittedtoapply = 0;
                    $leavetype->countapplied = 0;

                    $checkifpermittedtoapply = DB::table('hr_leaveemployees')
                        ->where('employeeid', $id)
                        ->where('leaveid', $leavetype->id)
                        ->where('deleted', '0')
                        ->count();

                    // return $checkifpermittedtoapply;
                    if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                        if ($checkifpermittedtoapply > 0) {
                            $leaveid = $leavetype->id;
                            $leavetype->permittedtoapply = 1;
                            $leavetype->countapplied = collect($leavesapplied)->where('leaveid', $leaveid)->sum('countapplied');


                            $leavetype->countapproved = collect($leavesapplied)->where('leaveid', $leaveid)->sum('countapproved');

                            $dates = DB::table('hr_leavedates')
                                ->select('id', 'ldate', 'ldatefrom', 'ldateto')
                                ->where('leaveid', $leavetype->id)
                                ->where('deleted', '0')
                                ->where('ldatefrom', '!=', null)
                                ->where('ldateto', '!=', null)
                                ->get();

                            if (count($dates) > 0) {
                                foreach ($dates as $date) {
                                    $interval = new DateInterval('P1D');

                                    $realEnd = new DateTime($date->ldateto);
                                    $realEnd->add($interval);

                                    $period = new DatePeriod(new DateTime($date->ldatefrom), $interval, $realEnd);

                                    foreach ($period as $key => $value) {
                                        $value->format('Y-m-d');
                                        array_push($alloweddates, $value->format('Y-m-d'));
                                    }
                                }
                            }
                        }

                    } else {
                        $applied = DB::table('hr_leaveemployees')
                            ->select('hr_leaveemployees.id', 'hr_leaveemployees.remarks', 'hr_leaveemployees.payrolldone', 'hr_leaveemployees.numofdays', 'hr_leaveemployees.leavestatus', 'hr_leaveemployees.createddatetime', 'hr_leaves.leave_type as leavetype', 'hr_leaves.id as leaveid')
                            ->join('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
                            ->where('hr_leaveemployees.employeeid', $id)
                            ->where('leaveid', $leavetype->id)
                            ->where('hr_leaveemployees.deleted', '0')
                            ->orderByDesc('hr_leaveemployees.createddatetime')
                            ->groupBy('hr_leaveemployees.createddatetime')
                            ->first();

                        if ($applied) {
                            $dates = DB::table('hr_leaveempdetails')
                                ->select('id', 'ldate', 'dayshift', 'leavestatus', 'payrolldone')
                                ->where('headerid', $applied->id)
                                ->where('deleted', '0')
                                ->count();

                        } else {
                            $dates = 0;
                        }
                        if ($dates < $leavetype->days) {
                            $leavetype->permittedtoapply = 1;
                        }
                        $leavetype->countapplied = $dates;
                    }
                }
            }


            // $leavetypes = collect($leavetypes)->where('permittedtoapply','1')->values();
            // return $yearofservice;
            return view('general.leaveapplication.index')
                ->with('id', $id)
                ->with('leavetypes', $leavetypes)
                ->with('alloweddates', $alloweddates)
                ->with('leavesapplied', $leavesapplied)
                ->with('extends', $extends)
                ->with('yearofservice', $yearofservice ?? 0);
        }

    }
    public function applysubmit(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        // return $request->all();
        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait') {
            $checkifexists = DB::table('sait_leaveapply')
                ->where('userid', auth()->user()->id)
                ->where('leavetypeid', $request->get('leavetype'))
                ->where('deleted', '0')
                ->first();

            if ($checkifexists) {
                return 0;
            } else {
                $file = $request->file('file');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $localfolder = 'EmployeeLeaves/' . auth()->user()->email;

                if (!File::exists(public_path() . $localfolder)) {

                    $path = public_path($localfolder);

                    if (!File::isDirectory($path)) {

                        File::makeDirectory($path, 0777, true, true);

                    }

                }

                if (strpos($request->root(), 'http://') !== false) {
                    $urlFolder = str_replace('http://', '', $request->root());
                } else {
                    $urlFolder = str_replace('https://', '', $request->root());
                }

                if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder)) {

                    $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;

                    if (!File::isDirectory($cloudpath)) {

                        File::makeDirectory($cloudpath, 0777, true, true);

                    }

                }


                $clouddestinationPath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;
                //try{

                //$file->move($clouddestinationPath, $filename);
                //}
                //catch(\Exception $e){


                //}

                $destinationPath = public_path($localfolder . '/');

                try {

                    $file->move($destinationPath, $filename);

                } catch (\Exception $e) {


                }
                DB::table('sait_leaveapply')
                    ->insert([
                        'userid' => auth()->user()->id,
                        'noofdays' => $request->get('noofdays'),
                        'leavetypeid' => $request->get('leavetype'),
                        'otherleavedesc' => $request->get('otherleavedesc'),
                        'datefrom' => $request->get('datefrom'),
                        'dateto' => $request->get('dateto'),
                        'reason' => $request->get('reason'),
                        'advancepay' => $request->get('advancepay'),
                        'picurl' => $localfolder . '/' . $filename,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

            }

        } else {
            $employeeid = $request->get('employeeids');
            $leaveid = $request->get('leaveid');
            $dates = $request->get('selecteddates');
            $remarks = $request->get('remarks');
            $selecteddates = array();

            $dayshifts = array();
            foreach ($request->except('_token', 'leaveid', 'remarks', 'selecteddates', 'employeeids') as $shift) {
                array_push($dayshifts, $shift);
            }

            foreach ($dates as $datekey => $date) {
                if (count($dayshifts) == 0) {
                    array_push($selecteddates, (object) array(
                        'ldate' => $date,
                        'dayshift' => 0
                    ));
                } else {
                    if (array_key_exists($datekey, $dayshifts)) {
                        array_push($selecteddates, (object) array(
                            'ldate' => $date,
                            'dayshift' => $dayshifts[$datekey]
                        ));
                    } else {
                        array_push($selecteddates, (object) array(
                            'ldate' => $date,
                            'dayshift' => 0
                        ));
                    }
                }
            }
            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                $checkifexists = DB::table('employee_leaves')
                    ->where('employeeid', $employeeid)
                    ->where('leaveid', $leaveid)
                    ->where('deleted', '0')
                    ->first();

                if ($checkifexists) {
                    $employeeleaveid = $checkifexists->id;

                    foreach ($selecteddates as $selecteddate) {
                        $checkdateifexists = DB::table('employee_leavesdetail')
                            ->where('headerid', $checkifexists->id)
                            ->where('ldate', $selecteddate->ldate)
                            ->where('deleted', '0')
                            ->get();

                        if (count($checkdateifexists) == 0) {
                            DB::table('employee_leavesdetail')
                                ->insert([
                                    'headerid' => $checkifexists->id,
                                    'ldate' => $selecteddate->ldate,
                                    'dayshift' => $selecteddate->dayshift,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => date('Y-m-d H:i:s')
                                ]);
                        }
                    }

                } else {
                    $id = DB::table('employee_leaves')
                        ->insertGetId([
                            'employeeid' => $employeeid,
                            'leaveid' => $leaveid,
                            'datefrom' => collect($selecteddates)->first()->ldate,
                            'dateto' => collect($selecteddates)->last()->ldate,
                            'remarks' => $remarks,
                            'numofdays' => count($selecteddates),
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);

                    foreach ($selecteddates as $selecteddate) {
                        DB::table('employee_leavesdetail')
                            ->insert([
                                'headerid' => $id,
                                'ldate' => $selecteddate->ldate,
                                'dayshift' => $selecteddate->dayshift,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                    $employeeleaveid = $id;
                }

                if ($request->has('files')) {
                    foreach ($request->file('files') as $file) {
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();

                        $localfolder = 'EmployeeLeaves/' . auth()->user()->email;

                        if (!File::exists(public_path() . $localfolder)) {

                            $path = public_path($localfolder);

                            if (!File::isDirectory($path)) {

                                File::makeDirectory($path, 0777, true, true);

                            }

                        }

                        if (strpos($request->root(), 'http://') !== false) {
                            $urlFolder = str_replace('http://', '', $request->root());
                        } else {
                            $urlFolder = str_replace('https://', '', $request->root());
                        }

                        if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder)) {

                            $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;

                            if (!File::isDirectory($cloudpath)) {

                                File::makeDirectory($cloudpath, 0777, true, true);

                            }

                        }


                        $clouddestinationPath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;
                        try {

                            $file->move($clouddestinationPath, $filename);
                        } catch (\Exception $e) {


                        }

                        $destinationPath = public_path($localfolder . '/');

                        try {

                            $file->move($destinationPath, $filename);

                        } catch (\Exception $e) {


                        }
                        DB::table('employee_leavesatt')
                            ->insert([
                                'headerid' => $employeeleaveid,
                                'filename' => $filename,
                                'picurl' => $localfolder . '/' . $filename,
                                'extension' => $extension,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            } else {
                $checkifexists = DB::table('hr_leaveemployees')
                    ->where('employeeid', $employeeid)
                    ->where('leaveid', $leaveid)
                    ->where('deleted', '0')
                    ->first();

                if ($checkifexists) {
                    $employeeleaveid = $checkifexists->id;

                    foreach ($selecteddates as $selecteddate) {
                        $checkdateifexists = DB::table('hr_leaveempdetails')
                            ->where('headerid', $checkifexists->id)
                            ->where('ldate', $selecteddate->ldate)
                            ->where('deleted', '0')
                            ->get();

                        if (count($checkdateifexists) == 0) {
                            DB::table('hr_leaveempdetails')
                                ->insert([
                                    'headerid' => $checkifexists->id,
                                    'ldate' => $selecteddate->ldate,
                                    'dayshift' => $selecteddate->dayshift,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => date('Y-m-d H:i:s')
                                ]);
                        }
                    }

                } else {
                    $id = DB::table('hr_leaveemployees')
                        ->insertGetId([
                            'employeeid' => $employeeid,
                            'leaveid' => $leaveid,
                            'datefrom' => collect($selecteddates)->first()->ldate,
                            'dateto' => collect($selecteddates)->last()->ldate,
                            'remarks' => $remarks,
                            'numofdays' => count($selecteddates),
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);

                    foreach ($selecteddates as $selecteddate) {
                        DB::table('hr_leaveempdetails')
                            ->insert([
                                'headerid' => $id,
                                'ldate' => $selecteddate->ldate,
                                'dayshift' => $selecteddate->dayshift,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                    $employeeleaveid = $id;
                }

                if ($request->has('files')) {
                    foreach ($request->file('files') as $file) {
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();

                        $localfolder = 'EmployeeLeaves/' . auth()->user()->email;

                        if (!File::exists(public_path() . $localfolder)) {

                            $path = public_path($localfolder);

                            if (!File::isDirectory($path)) {

                                File::makeDirectory($path, 0777, true, true);

                            }

                        }

                        if (strpos($request->root(), 'http://') !== false) {
                            $urlFolder = str_replace('http://', '', $request->root());
                        } else {
                            $urlFolder = str_replace('https://', '', $request->root());
                        }

                        if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder)) {

                            $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;

                            if (!File::isDirectory($cloudpath)) {

                                File::makeDirectory($cloudpath, 0777, true, true);

                            }

                        }


                        //$clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
                        //try{

                        //$file->move($clouddestinationPath, $filename);
                        //}
                        //catch(\Exception $e){


                        //}

                        $destinationPath = public_path($localfolder . '/');

                        try {

                            $file->move($destinationPath, $filename);

                        } catch (\Exception $e) {


                        }

                        DB::table('employee_leavesatt')
                            ->insert([
                                'headerid' => $employeeleaveid,
                                'filename' => $filename,
                                'picurl' => $localfolder . '/' . $filename,
                                'extension' => $extension,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                }

            }
        }
        return back();
    }

    public function submitleave(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $employeeid = $request->get('employeeids');
        $leaveid = $request->get('leaveid');
        $dates = $request->get('selecteddates');
        $remarks = $request->get('remarks');
        $halfdaystatus = $request->get('halfdayleavestatus');
        $selecteddates = array();
        $dayshifts = array();
        $schoolinfo = DB::table('schoolinfo')->first();
        $leavetype = DB::table('hr_leaves')->where('id', $leaveid)->first();

        $employees_profile = DB::table('teacher')
            ->select(
                'teacher.id',
                DB::raw('CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) AS full_name'),
                'suffix'
            )
            ->where('deleted', '0')
            ->where('isactive', '1')
            ->where('userid', auth()->user()->id)
            ->first();

        foreach ($request->except('_token', 'leaveid', 'remarks', 'selecteddates', 'employeeids') as $shift) {
            array_push($dayshifts, $shift);
        }
        foreach ($dates as $datekey => $date) {
            if (count($dayshifts) == 0) {
                array_push($selecteddates, (object) array(
                    'ldate' => $date,
                    'dayshift' => 0,
                    'halfdaystatus' => $halfdaystatus
                ));
            } else {
                if (array_key_exists($datekey, $dayshifts)) {
                    array_push($selecteddates, (object) array(
                        'ldate' => $date,
                        'dayshift' => $dayshifts[$datekey],
                        'halfdaystatus' => $halfdaystatus

                    ));
                } else {
                    array_push($selecteddates, (object) array(
                        'ldate' => $date,
                        'dayshift' => 0,
                        'halfdaystatus' => $halfdaystatus

                    ));
                }
            }
        }

        // $checkifexists = DB::table('hr_leaveemployees')
        //     ->where('employeeid', $request->input('employeeid'))
        //     ->where('leaveid', $request->input('leaveid'))
        //     ->where('deleted','0')
        //     ->first();

        // if($checkifexists)
        // {

        //     $employeeleaveid = $checkifexists->id;

        //     foreach($selecteddates as $selecteddate)
        //     {
        //         $checkdateifexists =  DB::table('hr_leaveempdetails')
        //             ->where('headerid', $checkifexists->id)
        //             ->where('ldate', $selecteddate->ldate)
        //             ->where('deleted','0')
        //             ->get();

        //         if(count($checkdateifexists) == 0)
        //         {
        //             DB::table('hr_leaveempdetails')
        //                 ->insert([
        //                     'headerid'           => $checkifexists->id,
        //                     'ldate'              => $selecteddate->ldate,
        //                     'dayshift'           => $selecteddate->dayshift,
        //                     'halfday'           => $selecteddate->halfdaystatus,
        //                     'remarks'            => $remarks,
        //                     'createdby'          => auth()->user()->id,
        //                     'createddatetime'    => date('Y-m-d H:i:s')
        //                 ]);

        //         }
        //     }

        // }else{
        $usertypes = [];
        $usertypes_depheads = [];

        // Retrieve `userid` from faspriv and avoid duplicates
        $otherusertypes = DB::table('faspriv')
            ->where('usertype', 10)
            ->get();
        
        foreach ($otherusertypes as $usertype) {
            if (!in_array($usertype->userid, $usertypes)) {
                $usertypes[] = $usertype->userid;
            }
        }
           
        // Retrieve `id` from users and avoid duplicates
        $otherusertypes2 = DB::table('users')
            ->where('type', 10)
            ->get();
        
        foreach ($otherusertypes2 as $usertype2) {
            if (!in_array($usertype2->id, $usertypes)) {
                $usertypes[] = $usertype2->id;
            }
        }
        
        // Retrieve department ID of the teacher
        $teacher_info = DB::table('teacher')
            ->where('id', $request->get('employeeid'))
            ->where('deleted', '0')
            ->first();
        
        $id = DB::table('hr_leaveemployees')
            ->insertGetId([
                'employeeid' => $request->input('employeeid'),
                'leaveid' => $leaveid,
                'datefrom' => collect($selecteddates)->first()->ldate,
                'dateto' => collect($selecteddates)->last()->ldate,
                'remarks' => $remarks,
                'numofdays' => count($selecteddates),
                'createdby' => auth()->user()->id,
                'createddatetime' => date('Y-m-d H:i:s')
            ]);

        foreach ($selecteddates as $selecteddate) {
            DB::table('hr_leaveempdetails')
                ->insert([
                    'headerid' => $id,
                    'ldate' => $selecteddate->ldate,
                    'dayshift' => $selecteddate->dayshift,
                    'halfday' => $selecteddate->halfdaystatus,
                    'remarks' => $remarks,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);
        }

        $employeeleaveid = $id;

        foreach ($usertypes as $key => $usertype) {
            NotificationController::sendNotification(
                $leavetype->leave_type . ' Apply Leave',
                "{$teacher_info->firstname} {$teacher_info->lastname} has pending leave requests that need approval.",
                $usertype,   // Receiver ID (payroll officer)
                'notification',
                null,
                '/hr/leaves/index',
                null,
                10
            );
        }


        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $localfolder = 'EmployeeLeaves/' . auth()->user()->email;
                if (!File::exists(public_path() . $localfolder)) {

                    $path = public_path($localfolder);

                    if (!File::isDirectory($path)) {

                        File::makeDirectory($path, 0777, true, true);

                    }

                }

                if (strpos($request->root(), 'http://') !== false) {
                    $urlFolder = str_replace('http://', '', $request->root());
                } else {
                    $urlFolder = str_replace('https://', '', $request->root());
                }

                if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder)) {

                    $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;

                    if (!File::isDirectory($cloudpath)) {

                        File::makeDirectory($cloudpath, 0777, true, true);

                    }

                }

                $destinationPath = public_path($localfolder . '/');

                try {

                    $file->move($destinationPath, $filename);

                } catch (\Exception $e) {


                }

                $filenamelocation = $destinationPath . '/' . $filename;

                DB::table('employee_leavesatt')
                    ->insert([
                        'headerid' => $employeeleaveid,
                        'filename' => $filename,
                        'picurl' => $localfolder . '/' . $filename,
                        'extension' => $extension,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);
            }

        }

        $details = [
            'subject' => $leavetype->leave_type,
            'name' => $employees_profile->full_name,
            'content' => $remarks,
            'selecteddates' => $selecteddates
        ];

        $senderEmail = 'noreply@yourdomain.com'; // Default sender email
        $senderName = $employees_profile->full_name; // Employee's name as sender
        $filePath = $filenamelocation; // Path to the file to be attached

        Mail::to($schoolinfo->schoolemail)
            ->send(new HRMail($details, $senderEmail, $senderName, $filePath));

        return 1;


    }

    public function uploadfiles(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        if ($request->has('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = $file->getClientOriginalName();

                $extension = $file->getClientOriginalExtension();

                $localfolder = 'EmployeeLeaves/' . auth()->user()->email;

                if (!File::exists(public_path() . $localfolder)) {

                    $path = public_path($localfolder);

                    if (!File::isDirectory($path)) {

                        File::makeDirectory($path, 0777, true, true);

                    }

                }

                if (strpos($request->root(), 'http://') !== false) {
                    $urlFolder = str_replace('http://', '', $request->root());
                } else {
                    $urlFolder = str_replace('https://', '', $request->root());
                }

                if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder)) {

                    $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;

                    if (!File::isDirectory($cloudpath)) {

                        File::makeDirectory($cloudpath, 0777, true, true);

                    }

                }

                $clouddestinationPath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . $localfolder;

                try {

                    $file->move($clouddestinationPath, $filename);
                } catch (\Exception $e) {


                }

                $destinationPath = public_path($localfolder . '/');

                try {

                    $file->move($destinationPath, $filename);

                } catch (\Exception $e) {


                }

                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                    DB::table('employee_leavesatt')
                        ->insert([
                            'headerid' => $request->get('employeeleaveid'),
                            'filename' => $filename,
                            'picurl' => $localfolder . '/' . $filename,
                            'extension' => $extension,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('hr_leaveempdetails')
                        ->insert([
                            'headerid' => $request->get('employeeleaveid'),
                            'filename' => $filename,
                            'picurl' => $localfolder . '/' . $filename,
                            'extension' => $extension,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }

        return back();
    }
    public function getdatesallowed(Request $request)
    {
        // if($request->ajax())
        // {
        $dates = DB::table('hr_leavedates')
            ->where('leaveid', $request->get('leaveid'))
            ->where('deleted', '0')
            ->where('ldatefrom', '!=', null)
            ->where('ldateto', '!=', null)
            ->get();

        $specificdates = collect();

        if (count($dates) > 0) {
            foreach ($dates as $date) {
                // $date->datestr = date('M d, Y', strtotime($date->ldate));
                $date->datestr = date('M d, Y', strtotime($date->ldatefrom)) . ' - ' . date('M d, Y', strtotime($date->ldateto));
                $period = new DatePeriod(
                    new DateTime($date->ldatefrom),
                    new DateInterval('P1D'),
                    new DateTime($date->ldateto . ' +1 day')
                );
                foreach ($period as $key => $value) {
                    $specificdates = collect($specificdates)->merge($value->format('Y-m-d'));
                }
                // $date->datefromstr = date('M d, Y', strtotime($date->ldatefrom));
                // $date->datetostr = date('M d, Y', strtotime($date->ldateto));
            }
        }

        return view('general.leaveapplication.adddates')
            ->with('specificdates', $specificdates)
            ->with('selecttext', $request->get('selecttext'))
            ->with('dates', collect($dates));
        // }
    }
    public function updateremarks(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        if ($request->ajax()) {
            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                Db::table('employee_leaves')
                    ->where('id', $request->get('empleaveid'))
                    ->update([
                        'remarks' => $request->get('remarks'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                Db::table('hr_leaveemployees')
                    ->where('id', $request->get('empleaveid'))
                    ->update([
                        'remarks' => $request->get('remarks'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
            }
        }
    }
    public function deleteapplication(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        if ($request->ajax()) {
            try {
                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                    Db::table('employee_leaves')
                        ->where('id', $request->get('id'))
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    Db::table('hr_leaveemployees')
                        ->where('id', $request->get('id'))
                        ->update([
                            'deleted' => 1,
                            'deletedby' => auth()->user()->id,
                            'deleteddatetime' => date('Y-m-d H:i:s')
                        ]);

                }
                return 1;
            } catch (\Exception $error) {
                return 0;
            }
        }
    }
    public function deleteldate(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        if ($request->ajax()) {
            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                Db::table('employee_leavesdetail')
                    ->where('id', $request->get('ldateid'))
                    ->update([
                        'deleted' => '1',
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                Db::table('hr_leaveempdetails')
                    ->where('id', $request->get('ldateid'))
                    ->update([
                        'deleted' => '1',
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
            }
        }
    }
    public function deletefile(Request $request)
    {
        // return $request->all();
        try {
            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                DB::table('employee_leavesatt')
                    ->where('id', $request->get('attachmentid'))
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                DB::table('employee_leavesatt')
                    ->where('id', $request->get('attachmentid'))
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
            }
            return 1;
        } catch (\Exception $error) {
            return 0;
        }
    }

}
