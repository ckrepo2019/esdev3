<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;
use Crypt;
use File;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Models\HR\HRDeductions;
use App\Models\HR\HREmployeeAttendance;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class HREmployeeLeaveController extends Controller
{
    public function index(Request $request)
    {
        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();

        $leavetypes = DB::table('hr_leaves')
            ->where('deleted', '0')
            ->get();

        $semester = DB::table('semester')
            ->where('isactive', 1)
            ->first();

        return view('hr.employees.applyleave')
            ->with('sy', $sy)
            ->with('semester', $semester)
            ->with('leavetypes', $leavetypes);
    }

    // This function is to load all employees
    public function loademployees(Request $request)
    {
        $employees = DB::table('teacher')
            ->select(
                'teacher.id',
                DB::raw('CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) AS full_name'),
                'suffix',
                'amount as salaryamount',
                'utype as designation',
                'employee_basicsalaryinfo.salarybasistype',
                'employee_basicsalaryinfo.clsubjperhour',
                'employee_personalinfo.date_joined',
                'employee_personalinfo.yos'
            )
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get();

        foreach ($employees as $employee) {
            $yearsOfService = 0;
            $countleaves = 0;
            $leaveappl = [];

            if ($employee->date_joined != null) {

                $dateJoined = Carbon::parse($employee->date_joined);
                $currentDate = Carbon::now();

                // Calculate the difference in years
                $yearsOfService = $dateJoined->diffInYears($currentDate);

                $employee->yos = $yearsOfService;
            } else {
                $employee->yos = $yearsOfService;
            }


            // count how many leaves
            $currentYear = date('Y');
            $leaves = DB::table('hr_leaves')
                ->where('lyear', $currentYear)
                ->where('deleted', 0)
                ->get();

            if (count($leaves) > 0) {

                $appliedleaves = DB::table('hr_leaveemployees')
                    ->select('hr_leaveemployees.leaveid', 'hr_leaveemployees.id', 'hr_leaves.leave_type', 'hr_leaveemployees.leavestatus')
                    ->leftJoin('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
                    ->where('hr_leaveemployees.employeeid', $employee->id)
                    ->where('hr_leaveemployees.deleted', 0)
                    ->where('hr_leaves.deleted', 0)
                    ->where('hr_leaves.lyear', $currentYear)
                    ->orderBy('hr_leaves.leave_type', 'asc')
                    ->get()
                    ->unique('leaveid');

                $appliedleavesdata = DB::table('hr_leaveemployees')
                    ->leftJoin('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
                    ->where('hr_leaveemployees.employeeid', $employee->id)
                    ->where('hr_leaveemployees.deleted', 0)
                    ->where('hr_leaves.deleted', 0)
                    ->where('hr_leaves.lyear', $currentYear)
                    ->orderBy('hr_leaves.leave_type', 'asc')
                    ->get();


                if (count($appliedleaves) > 0) {
                    foreach ($appliedleaves as $appliedleave) {
                        $numdays = 0;
                        foreach ($appliedleavesdata as $data) {
                            if ($appliedleave->leave_type == $data->leave_type) {
                                $numdays += $data->numofdays;
                            }
                        }
                        $appliedleave->numofdays = $numdays;
                    }
                }

                if (count($appliedleaves) > 0) {

                    foreach ($appliedleaves as $appliedleave) {

                        $leaveappl[] = [
                            'id' => $appliedleave->id,
                            'leave_type' => $appliedleave->leave_type,
                            'numofdays' => $appliedleave->numofdays,
                            'leavestatus' => $appliedleave->leavestatus
                        ];


                    }
                }


                $employee->countleaves = count($appliedleaves);
                $employee->leavesapplied = $leaveappl;
            } else {
                $employee->countleaves = 0;
            }

        }



        return $employees;
    }

    public function loadleavesapplied(Request $request)
    {
        // count how many leaves
        $teacherid = $request->get('teacherid');
        $currentYear = date('Y');
        $leaves = DB::table('hr_leaves')
            ->where('lyear', $currentYear)
            ->where('deleted', 0)
            ->get();

        if (count($leaves) > 0) {

            $appliedleaves = DB::table('hr_leaveemployees')
                ->leftJoin('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
                ->where('hr_leaveemployees.employeeid', $teacherid)
                ->where('hr_leaveemployees.deleted', 0)
                ->where('hr_leaves.deleted', 0)
                ->where('hr_leaves.lyear', $currentYear)
                ->select(
                    'hr_leaves.*',
                    'hr_leaveemployees.*',
                    'hr_leaveemployees.createddatetime AS createddate'
                )
                ->get();

        }

        return $appliedleaves;
    }


    public function loadleaves(Request $request)
    {

        $collectleavetypes = Db::table('hr_leaves')
            ->select('leave_type as text', 'id', 'isactive', 'lyear', 'days')
            ->where('deleted', '0')
            ->where('isactive', '1')
            ->get();

        return $collectleavetypes;
    }

    public function applyleaveperemp(Request $request)
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

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


        $alloweddates = array();

        $collectleavetypes = Db::table('hr_leaves')
            ->where('deleted', '0')
            ->where('isactive', '1')
            // ->whereIn('hr_leaves.lyear',array(null, date('Y')))
            ->get();
        $leavetypes = collect();
        $leavetypes = collect($leavetypes)->merge(collect($collectleavetypes)->where('lyear', null)->values())->all();
        $leavetypes = collect($leavetypes)->merge(collect($collectleavetypes)->where('lyear', date('Y'))->values())->all();

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

    }

    public function add(Request $request)
    {
        $leaveid = $request->get('leaveid');
        $yos = $request->get('yos');
        $days = $request->get('days');

        $checkifexist = DB::table('hr_leavesadditionalsetup')
            ->where('headerid', $leaveid)
            ->where('years', $yos)
            ->where('days', $days)
            ->where('deleted', 0)
            ->count();

        if ($checkifexist) {
            return 0;
        } else {
            $data = DB::table('hr_leavesadditionalsetup')
                ->insert([
                    'headerid' => $leaveid,
                    'years' => $yos,
                    'days' => $days,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);
            return 1;
        }
    }
    public function edityear(Request $request)
    {
        $dataid = $request->get('dataid');
        $years = $request->get('years');


        $checkifexist = DB::table('hr_leavesadditionalsetup')
            // ->where('id', $dataid)
            ->where('years', $years)
            ->where('deleted', 0)
            ->count();

        if ($checkifexist) {
            return 0;
        } else {
            $data = DB::table('hr_leavesadditionalsetup')
                ->where('id', $dataid)
                ->update([
                    'years' => $years,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);
            return 1;
        }
    }
    public function editdays(Request $request)
    {
        $dataid = $request->get('dataid');
        $days = $request->get('days');

        $data = DB::table('hr_leavesadditionalsetup')
            ->where('id', $dataid)
            ->update([
                'days' => $days,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => date('Y-m-d H:i:s')
            ]);

        return 1;
    }
    public function delete(Request $request)
    {
        $dataid = $request->get('dataid');

        $checkifexist = DB::table('hr_leavesadditionalsetup')
            ->where('id', $dataid)
            ->where('deleted', 0)
            ->count();

        if (!$checkifexist) {
            return 0;
        } else {
            $data = DB::table('hr_leavesadditionalsetup')
                ->where('id', $dataid)
                ->update([
                    'deleted' => 1,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);
            return 1;
        }
    }

    public function loaddaysyears(Request $request)
    {
        $leaveid = $request->get('leaveid');

        $data = DB::table('hr_leavesadditionalsetup')
            ->where('headerid', $leaveid)
            ->where('deleted', 0)
            ->get();

        return $data;
    }

    public function loadavailableleaves(Request $request)
    {

        $teacherid = $request->get('teacherid');

        $departmentid = DB::table('employee_personalinfo')
            ->where('deleted', 0)
            ->where('employeeid', $teacherid)
            ->first();

        $yos = $request->get('yos');

        $filteredleaves = [];
        $allleaves = DB::table('hr_leaves')
            ->where('deleted', 0)
            // ->where('id', 13)
            ->get();

        $existabove = DB::table('hr_leavesadditionalsetup')
            ->where('deleted', 0)
            ->Where('above', 1)
            ->get();

        $yearswithabove = $existabove->pluck('years');

        if ($yearswithabove->contains($yos) || $yos > $yearswithabove->max()) {
            $additionalinfos = DB::table('hr_leavesadditionalsetup')
                ->where('deleted', 0)
                ->where('years', '=', $yos)
                ->orWhere('years', 0)
                ->orWhere('above', 1)
                ->get();
        } else {
            $additionalinfos = DB::table('hr_leavesadditionalsetup')
                ->where('deleted', 0)
                ->where('years', '=', $yos)
                ->orWhere('years', 0)
                ->get();
        }

        foreach ($allleaves as $allleave) {


            $countdays = 0;

            $allleave->applicable = 0; // Set the default value
            $allleave->dayss = 0;

            $allleave->permittedtoapply = 0;
            $allleave->countapplied = 0;

            foreach ($additionalinfos as $additionalinfo) {
                if ($allleave->id == $additionalinfo->headerid) {
                    $allleave->applicable = 1;
                    $allleave->dayss = $additionalinfo->days;
                    break; // No need to continue checking once a match is found
                }


            }

            $checkifpermittedtoapply = DB::table('hr_leaveemployees')
                ->where('employeeid', $teacherid)
                ->where('leaveid', $allleave->id)
                ->where('deleted', '0')
                ->count();

            $applied = DB::table('hr_leaveemployees')
                ->select('hr_leaveemployees.id', 'hr_leaveemployees.remarks', 'hr_leaveemployees.payrolldone', 'hr_leaveemployees.numofdays', 'hr_leaveemployees.leavestatus', 'hr_leaveemployees.createddatetime', 'hr_leaves.leave_type as leavetype', 'hr_leaves.id as leaveid')
                ->join('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
                ->where('hr_leaveemployees.employeeid', $teacherid)
                ->where('leaveid', $allleave->id)
                // ->where('leaveid', 10)
                ->where('hr_leaveemployees.deleted', '0')
                ->orderByDesc('hr_leaveemployees.createddatetime')
                ->groupBy('hr_leaveemployees.createddatetime')
                // ->first();
                ->get();

            $appliedids = $applied->pluck('id');


            if ($applied) {
                $dates = DB::table('hr_leaveempdetails')
                    ->select('id', 'ldate', 'dayshift', 'leavestatus', 'payrolldone', 'halfday')
                    // ->where('headerid',$applied->id)
                    ->whereIn('headerid', $appliedids)
                    ->where('deleted', '0')
                    ->get();

                foreach ($dates as $date) {
                    if ($date->halfday == 0) {
                        $countdays += 1;
                    } else if ($date->halfday == 1 || $date->halfday == 2) {
                        $countdays += .5;
                    } else {
                        $countdays += 0;
                    }
                }

            } else {
                $dates = 0;
            }
            if ($dates < $allleave->days) {
                $allleave->permittedtoapply = 1;
            }
            // $allleave->countapplied = $dates;
            $allleave->countapplied = $countdays;

            $filteredleaves[] = $allleave;
        }

        $filteredCollection = collect($filteredleaves);
        $leaveids = $filteredCollection->pluck('id');





        // $leavesapplied = DB::table('hr_leaveemployees')
        //     ->select('hr_leaveemployees.id','hr_leaveemployees.remarks','hr_leaveemployees.payrolldone','hr_leaveemployees.numofdays','hr_leaveemployees.leavestatus','hr_leaveemployees.createddatetime','hr_leaves.leave_type as leavetype','hr_leaves.id as leaveid')
        //     ->join('hr_leaves','hr_leaveemployees.leaveid','=','hr_leaves.id')
        //     ->where('hr_leaveemployees.employeeid', $teacherid)
        //     ->where('hr_leaves.lyear', date('Y'))
        //     ->where('hr_leaveemployees.deleted','0')
        //     ->orderByDesc('hr_leaveemployees.createddatetime')
        //     ->groupBy('hr_leaveemployees.createddatetime')
        //     ->get();

        //     return $leavesapplied;
        // if(count($leavesapplied)>0)
        // {
        //     foreach($leavesapplied as $leaveapp)
        //     {
        //         $leaveapp->canbedeleted = 0;
        //         $approvalheads = DB::table('hr_leaveemployees')
        //             ->select('teacher.id','teacher.userid','teacher.lastname','teacher.firstname','teacher.middlename')
        //             ->join('hr_leavesappr', 'hr_leaveemployees.leaveid','=','hr_leavesappr.leaveid')
        //             ->join('teacher', 'hr_leavesappr.appuserid','=','teacher.userid')
        //             ->where('hr_leaveemployees.leaveid', $leaveapp->leaveid)
        //             ->whereIn('hr_leaveemployees.employeeid', $leaveids)
        //             ->where('hr_leaveemployees.deleted','0')
        //             ->where('hr_leavesappr.deleted','0')
        //             ->get();

        //         if(count($approvalheads)>0)
        //         {

        //             foreach($approvalheads as $approvalhead)
        //             {
        //                 $approvalhead->remarks = '';
        //                 $checkapproval = DB::table('hr_leaveemployeesappr')   
        //                     ->where('headerid', $leaveapp->id)
        //                     ->where('appuserid', $approvalhead->userid)
        //                     ->where('deleted','0')
        //                     ->first();

        //                 if($checkapproval)
        //                 {
        //                     $approvalhead->remarks = $checkapproval->remarks;
        //                     $approvalhead->appstatus = $checkapproval->appstatus;
        //                 }else{
        //                     $approvalhead->appstatus = 0;
        //                 }
        //             }
        //             if(collect($approvalheads)->where('appstatus','0')->count() == count($approvalheads))
        //             {
        //                 $leaveapp->canbedeleted = 0;
        //             }
        //             if(collect($approvalheads)->where('appstatus','1')->count() >0)
        //             {
        //                 $leaveapp->canbedeleted = 1;
        //             }
        //             if(collect($approvalheads)->where('appstatus','2')->count() >0)
        //             {
        //                 $leaveapp->canbedeleted = 1;
        //                 $leaveapp->leavestatus = 2;
        //             }
        //         }

        //         $leaveapp->approvals = $approvalheads;
        //         $numdaysapproved = 0;

        //         $dates = DB::table('hr_leaveempdetails')
        //             ->select('id','ldate','dayshift','leavestatus','payrolldone')
        //             ->where('headerid',$leaveapp->id)
        //             ->where('deleted','0')
        //             ->get();

        //         if(count($dates)>0)
        //         {
        //             foreach($dates as $date)
        //             {
        //                 if(collect($approvalheads)->where('appstatus','1')->count() == count($approvalheads))
        //                 {
        //                     if($date->dayshift == 0)
        //                     {
        //                         $numdaysapproved+=1;
        //                     }else{
        //                         $numdaysapproved+=0.5;
        //                     }
        //                 }
        //                 if(collect($approvalheads)->where('appstatus','0')->count() >0)
        //                 {
        //                     if($date->dayshift == 0)
        //                     {
        //                         $numdaysapproved+=1;
        //                     }else{
        //                         $numdaysapproved+=0.5;
        //                     }
        //                 }
        //             }
        //         }



        //         $leaveapp->dates = $dates;
        //         $leaveapp->attachments = DB::table('employee_leavesatt')
        //             ->where('headerid', $leaveapp->id)
        //             ->where('deleted','0')
        //             ->get();

        //         $leaveapp->countapplied = $numdaysapproved;
        //         $leaveapp->countapproved = $numdaysapproved;

        //         if($leaveapp->countapproved ==  count($approvalheads))
        //         {                        
        //             $leaveapp->leavestatus = 1;
        //         }
        //     }
        // }
        // $alloweddates = array();

        // $collectleavetypes = Db::table('hr_leaves')
        //     ->where('deleted','0')
        //     ->where('isactive','1')
        //     ->get();

        // $leavetypes = collect();
        // $leavetypes = collect($leavetypes)->merge(collect($collectleavetypes)->where('lyear',null)->values())->all();
        // $leavetypes = collect($leavetypes)->merge(collect($collectleavetypes)->where('lyear',date('Y'))->values())->all();

        // if(count($leavetypes)>0)
        // {
        //     foreach($leavetypes as $leavetype)
        //     {

        //         $leavetype->permittedtoapply = 0;
        //         $leavetype->countapplied = 0;

        //         $checkifpermittedtoapply = DB::table('hr_leaveemployees')
        //             ->where('employeeid', $teacherid)
        //             ->where('leaveid', $leavetype->id)
        //             ->where('deleted','0')
        //             ->count();

        //         // return $checkifpermittedtoapply;
        //         if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
        //         {
        //             if($checkifpermittedtoapply > 0)
        //             {
        //                 $leaveid = $leavetype->id;
        //                 $leavetype->permittedtoapply = 1;
        //                 $leavetype->countapplied  = collect($leavesapplied)->where('leaveid', $leaveid)->sum('countapplied');


        //                 $leavetype->countapproved = collect($leavesapplied)->where('leaveid', $leaveid)->sum('countapproved');

        //                 $dates = DB::table('hr_leavedates')
        //                     ->select('id','ldate','ldatefrom','ldateto')
        //                     ->where('leaveid',$leavetype->id)
        //                     ->where('deleted','0')
        //                     ->where('ldatefrom','!=', null)
        //                     ->where('ldateto','!=', null)
        //                     ->get();

        //                 if(count($dates)>0)
        //                 {
        //                     foreach($dates as $date)
        //                     {
        //                         $interval = new DateInterval('P1D');

        //                         $realEnd = new DateTime($date->ldateto);
        //                         $realEnd->add($interval);

        //                         $period = new DatePeriod(new DateTime($date->ldatefrom), $interval, $realEnd);

        //                         foreach ($period as $key => $value) {
        //                             $value->format('Y-m-d')   ;    
        //                             array_push($alloweddates,  $value->format('Y-m-d'));
        //                         }
        //                     }
        //                 }
        //             }

        //         }else{
        //             $applied = DB::table('hr_leaveemployees')
        //                 ->select('hr_leaveemployees.id','hr_leaveemployees.remarks','hr_leaveemployees.payrolldone','hr_leaveemployees.numofdays','hr_leaveemployees.leavestatus','hr_leaveemployees.createddatetime','hr_leaves.leave_type as leavetype','hr_leaves.id as leaveid')
        //                 ->join('hr_leaves','hr_leaveemployees.leaveid','=','hr_leaves.id')
        //                 ->where('hr_leaveemployees.employeeid', $teacherid)
        //                 ->where('leaveid', $leavetype->id)
        //                 ->where('hr_leaveemployees.deleted','0')
        //                 ->orderByDesc('hr_leaveemployees.createddatetime')
        //                 ->groupBy('hr_leaveemployees.createddatetime')
        //                 ->first();

        //             if($applied)
        //             {
        //                 $dates = DB::table('hr_leaveempdetails')
        //                     ->select('id','ldate','dayshift','leavestatus','payrolldone')
        //                     ->where('headerid',$applied->id)
        //                     ->where('deleted','0')
        //                     ->count();

        //             }else{
        //                 $dates = 0;
        //             }
        //             if($dates < $leavetype->days)
        //             {
        //                 $leavetype->permittedtoapply = 1;
        //             }
        //             $leavetype->countapplied = $dates;
        //         }
        //     }
        // }

        // return $departmentid->departmentid;
        if ($departmentid && $departmentid->departmentid == 6) {
            foreach ($filteredleaves as $filteredleave) {
                if ($filteredleave->id == 6 || $filteredleave->id == 8) {
                    $filteredleave->dayss = 10;
                    $filteredleave->applicable = 1;
                }
            }
        }


        return $filteredleaves;
    }
    public function loadleavedetails(Request $request)
    {
        $leaveid = $request->get('leaveid');

        $additionalinfos = DB::table('hr_leavesadditionalsetup')
            ->where('headerid', $leaveid)
            ->where('deleted', 0)
            ->get();

        return $additionalinfos;
    }
    public function loaddates(Request $request)
    {
        $leaveid = $request->get('leaveid');
        $empid = $request->get('employeeid');

        $leaveempid = DB::table('hr_leaveemployees')
            ->where('leaveid', $leaveid)
            ->where('employeeid', $empid)
            ->where('deleted', 0)
            // ->first();
            ->get();

        $leaveempidids = $leaveempid->pluck('id');

        // return $leaveempid;
        if ($leaveempid) {
            $details = DB::table('hr_leaveempdetails')
                ->select('hr_leaveempdetails.*', 'hr_leaveemployees.employeeid')
                ->leftJoin('hr_leaveemployees', 'hr_leaveempdetails.headerid', 'hr_leaveemployees.id')
                // ->where('hr_leaveempdetails.headerid', $leaveempid->id)
                ->whereIn('hr_leaveempdetails.headerid', $leaveempidids)
                ->where('hr_leaveempdetails.deleted', 0)
                ->get();

            return $details;
        }

    }
    public function deletedates(Request $request)
    {
        $leaveempid = $request->get('leaveempid');
        $teacherid = $request->get('teacherid');

        $data = DB::table('hr_leaveemployees')
            ->where('id', $leaveempid)
            // ->where('employeeid', $teacherid)
            ->where('deleted', 0)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => date('Y-m-d H:i:s')
            ]);

        return 1;
    }
    public function deletedays(Request $request)
    {

        $leavedetailid = $request->get('id');
        $leaveid = $request->get('leaveid');
        $employeeid = $request->get('employeeid');

        $details = DB::table('hr_leaveempdetails')
            ->where('id', $leavedetailid)
            ->where('deleted', 0)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => date('Y-m-d H:i:s')
            ]);

        return 1;
    }

    public function loademployeeappliedleaves(Request $request)
    {
        $teacher_id = $request->get('teacherid');
        $attendanceArray = [];
        $leavesappr = DB::table('hr_leaveemployees')
            ->select(
                'hr_leaves.id',
                'hr_leaves.leave_type',
                'hr_leaveemployees.employeeid as employeeleaveid',
                'hr_leaveemployees.employeeid',
                'hr_leaveemployees.id as headerid',
                'hr_leaveemployees.datefrom',
                'hr_leaveemployees.dateto',
                'hr_leaveempdetails.id as ldateid',
                'hr_leaveempdetails.ldate',
                'hr_leaveempdetails.dayshift',
                'hr_leaveempdetails.halfday',
                'hr_leaveempdetails.createddatetime',
                'hr_leaveempdetails.remarks'
            )
            ->join('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
            ->join('hr_leaveempdetails', 'hr_leaveemployees.id', '=', 'hr_leaveempdetails.headerid')
            ->where('hr_leaveemployees.deleted', '0')
            ->where('hr_leaveempdetails.deleted', '0')
            ->where('hr_leaveemployees.employeeid', $teacher_id)
            ->orderByDesc('hr_leaveemployees.createddatetime')
            ->groupBy('hr_leaveemployees.id')
            ->get();

        if (count($leavesappr) > 0) {
            foreach ($leavesappr as $leaveapp) {
                // Initialize fields for each leave application
                $leaveapp->display = 0;
                $leaveapp->approvercount = 0; // Initialize approver count
                $leaveapp->disapprovercount = 0; // Initialize approver count
                $attachments = array();
                $leaveapp->leavestatus = 0;
                $leaveapp->approvers = [];

                // Fetch approvers for the current leave application
                $approvals = DB::table('hr_leavesappr')
                    ->select('teacher.id', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'hr_leavesappr.appuserid')
                    ->join('teacher', 'hr_leavesappr.appuserid', '=', 'teacher.userid')
                    ->where('hr_leavesappr.leaveid', $leaveapp->id)
                    ->where('hr_leavesappr.deleted', '0') // Ensure not deleted
                    ->get();

                if (count($approvals) > 0) {
                    foreach ($approvals as $approvalheader) {
                        // Check if the approval entry exists for this approver and leave application
                        $getapprdata = DB::table('hr_leaveemployeesappr')
                            ->where('headerid', $leaveapp->headerid)
                            ->where('appuserid', $approvalheader->appuserid)
                            ->where('deleted', '0') // Ensure approval entry is not deleted
                            ->first();

                        // Determine approval status
                        $status = 0; // Default: not approved or disapproved
                        if ($getapprdata) {
                            if ($getapprdata->appstatus == 1) {
                                $status = 1; // Approved
                                $leaveapp->approvercount++;
                            } elseif ($getapprdata->appstatus == 2) {
                                $status = 2; // Disapproved
                                $leaveapp->disapprovercount++;
                            }
                        }

                        // Add the approver and their approval status
                        $leaveapp->approvers[] = [
                            'userid' => $approvalheader->userid,
                            'name' => $approvalheader->lastname . ', ' . $approvalheader->firstname . ' ' . ($approvalheader->middlename ?? ''),
                            'status' => $status,
                        ];

                    }
                }
            }
        }

        return response()->json($leavesappr);
    }

}
