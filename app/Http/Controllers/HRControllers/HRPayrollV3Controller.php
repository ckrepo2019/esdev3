<?php
namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Illuminate\Support\Facades\View;
use Barryvdh\Snappy\Facades\SnappyPdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use DatePeriod;
class HRPayrollV3Controller extends Controller
{
    public function index(Request $request)
    {
        $employees = DB::table('teacher')
            ->select('teacher.id', 'lastname', 'firstname', 'middlename', 'suffix', 'amount as salaryamount', 'utype as designation')
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            // ->where('employee_basicsalaryinfo.deleted','0')
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get();
        foreach ($employees as $employee) {
            if ($employee->salaryamount === null) {
                $employee->salary = 'no salary';
            } else {
                $employee->salary = 'with salary';
            }
        }

        // return $employees;
        $payrollperiod = DB::table('hr_payrollv2')
            ->where('status', 1)
            ->first();

        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();


        return view('hr.payroll.v3.index')
            ->with('employees', $employees)
            ->with('payrollperiod', $payrollperiod)
            ->with('sy', $sy);
    }
    public function getProgress(Request $request)
    {
        // Your logic to retrieve the progress goes here
        // Return the progress as a JSON response
        return response()->json(['progress' => 50]); // Replace '50' with the actual progress value
    }
    public function salarybasistype(Request $request)
    {
        $salarybasistype = DB::table('employee_basistype')
            ->select('type as text', 'id')
            ->where('deleted', '0')
            ->get();

        return $salarybasistype;
    }
    // public function allemployees(Request $request)
    // {

    //     $payrollid = $request->get('payrollid');

    //     // return $request->all();
    //     $employees = DB::table('teacher')
    //         ->selectRaw('teacher.id,
    //             CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) as text,
    //             employee_basicsalaryinfo.salarybasistype,
    //             amount as salaryamount,
    //             utype as designation')
    //         ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
    //         ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
    //         // ->where('employee_basicsalaryinfo.deleted','0')
    //         ->where('teacher.deleted','0')
    //         ->where('teacher.isactive','1')
    //         ->orderBy('lastname','asc')
    //         ->get();

    //     $employeereleased = DB::table('hr_payrollv2history')
    //         ->where('payrollid',$payrollid)
    //         ->where('deleted','0')
    //         ->where('released', 1)
    //         ->get();

    //     foreach ($employees as $employee) {
    //         foreach ($employeereleased as $eachemployeereleased) {
    //             if ($employee->id == $eachemployeereleased->employeeid) {
    //                 $employees->released = 1;
    //             }
    //         }
    //     }

    //     return $employees;
    // }
    public function allemployees(Request $request)
    {
        $payrollid = $request->get('payrollid');
        $setup = $request->get('setup');
        $deptid = $request->get('deptid');
        $salid = $request->get('salid');

        $payrolldate = DB::table('hr_payrollv2')
            ->where('id', $payrollid)
            ->first();

        $employeeleaved = DB::table('hr_leaveemployees')
            ->where('datefrom', '<=', $payrolldate->datefrom)
            ->where('dateto', '>=', $payrolldate->dateto)
            ->get();

        $employeeleaveids = $employeeleaved->pluck('employeeid');

        // return $employeeleaveids;

        if ($setup == 'releasedsetup') {
            $employees = DB::table('teacher')
                ->selectRaw('teacher.id,employee_personalinfo.departmentid,
                    employee_basicsalaryinfo.parttimer,
                    CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) as text,
                    employee_basicsalaryinfo.salarybasistype,
                    amount as salaryamount,
                    utype as designation')
                ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
                ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                ->where('teacher.deleted', '0')
                ->where('teacher.isactive', '1')
                ->where('employee_personalinfo.departmentid', $deptid)
                ->where('employee_basicsalaryinfo.salarybasistype', $salid)
                ->orderBy('lastname', 'asc')
                ->get();

        } else {

            $employees = DB::table('teacher')
                ->selectRaw('teacher.id,employee_personalinfo.departmentid,
                    employee_basicsalaryinfo.parttimer,
                    CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) as text,
                    employee_basicsalaryinfo.salarybasistype,
                    amount as salaryamount,
                    utype as designation')
                ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
                ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                ->where('teacher.deleted', '0')
                ->where('teacher.isactive', '1')
                ->whereNotIn('teacher.id', $employeeleaveids)
                ->orderBy('lastname', 'asc')
                ->get();

            // return $employees;
        }

        // Create an associative array to store the status of each employee
        $statusMap = [];
        // Update the employee data with status information
        $updatedEmployees = [];
        if (count($employees) > 0) {
            $employeereleased = DB::table('hr_payrollv2history')
                ->where('payrollid', $payrollid)
                ->where('deleted', '0')
                ->where('released', 1)
                ->get();
            // return $employeereleased;
            $employeeconfigured = DB::table('hr_payrollv2history')
                ->where('payrollid', $payrollid)
                ->where('deleted', '0')
                ->where('configured', 1)
                ->where('released', '!=', 1)
                ->get();



            foreach ($employeereleased as $eachemployeereleased) {
                $statusMap[$eachemployeereleased->employeeid]['released'] = 1;
            }

            foreach ($employeeconfigured as $employeeconfigure) {
                $statusMap[$employeeconfigure->employeeid]['configured'] = 1;
            }


            foreach ($employees as $employee) {
                $employee->released = $statusMap[$employee->id]['released'] ?? 0;
                $employee->configured = $statusMap[$employee->id]['configured'] ?? 0;

                // Add the updated employee data to the array
                $updatedEmployees[] = $employee;
            }


        }
        return $updatedEmployees;
    }

    public function countemployeerelease(Request $request)
    {
        $salid = $request->get('salid');

        $payroll = DB::table('hr_payrollv2')
            ->where('deleted', '0')
            ->where('status', '1')
            ->where('salarytypeid', $salid)
            ->first();

        if ($payroll) {
            $payrollid = $payroll->id;

            $countemployee = DB::table('hr_payrollv2history')
                ->where('payrollid', $payrollid)
                ->where('deleted', '0')
                ->where('released', '1')
                ->count();

            return $countemployee;
        } else {
            return 0; // or return an appropriate value as needed.
        }
    }


    public function getallholiday(Request $request)
    {
        $employeeid = $request->get('employeeid');
        $payrollid = $request->get('pid');
        $holidays = DB::table('hr_payrollv2historydetail')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $employeeid)
            ->where('particulartype', 8)
            ->where('deleted', '0')
            ->get();

        return $holidays;
    }

    public function voidpayslip(Request $request)
    {
        $voidremarks = $request->get('voidremarks');
        $payrollid = $request->get('payrollid');
        $employeeid = $request->get('employeeid');

        $voidpayhistory = DB::table('hr_payrollv2history')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $employeeid)
            ->where('deleted', 0)
            ->update([
                'remarks' => $voidremarks,
                'configured' => 0,
                'released' => 0,
                'deleted' => 1,
                'void' => 1,
                'voidby' => auth()->user()->id,
                'voiddatetime' => date('Y-m-d H:i:s')
            ]);

        $voidpayhistorydetail = DB::table('hr_payrollv2historydetail')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $employeeid)
            ->update([
                'void' => 1,
                'deleted' => 1
            ]);

        $voidpayaddedparticular = DB::table('hr_payrollv2addparticular')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $employeeid)
            ->update([
                'void' => 1,
                'deleted' => 1
            ]);

        return 1;
    }

    public function editpayslip(Request $request)
    {
        $editremarks = $request->get('editremarks');
        $payrollid = $request->get('payrollid');
        $employeeid = $request->get('employeeid');

        $voidpayhistory = DB::table('hr_payrollv2history')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $employeeid)
            ->where('deleted', 0)
            ->update([
                'editremarks' => $editremarks,
                'released' => 0,
                'editby' => auth()->user()->id,
                'editdatetime' => date('Y-m-d H:i:s')
            ]);

        return 1;
    }

    public function getsalaryinfo(Request $request)
    {
        //    return $request->all();
        $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
            ->select('employee_basicsalaryinfo.*', 'employee_basistype.type as salarytype', 'employee_basistype.type as ratetype')
            ->join('employee_basistype', 'employee_basicsalaryinfo.salarybasistype', '=', 'employee_basistype.id')
            ->where('employee_basicsalaryinfo.deleted', '0')
            ->where('employee_basicsalaryinfo.employeeid', $request->get('employeeid'))
            ->first();
        // return collect($basicsalaryinfo);

        if ($basicsalaryinfo->hoursperday == null || $basicsalaryinfo->hoursperday == 0) {
            $basicsalaryinfo->hoursperday = 1;
        }

        $employeeinfo = DB::table('teacher')
            ->select('teacher.*', 'employee_personalinfo.gender', 'utype', 'teacher.id as employeeid', 'employee_personalinfo.departmentid')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.id', $request->get('employeeid'))
            ->where('teacher.deleted', '0')
            ->first();


        $empstatus = $employeeinfo->employmentstatus;
        // return $empstatus;
        // try{

        // }catch(\Exception $error)
        // {}
        // return collect($basicsalaryinfo);
        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();

        $semesterdateactive = DB::table('semester_date')
            ->where('deleted', 0)
            ->where('syid', $sy->id)
            ->where('active', 1)
            ->get();

        $payrollperiod = DB::table('hr_payrollv2')
            ->where('id', $request->get('payrollid'))
            ->first();

        $taphistory = DB::table('taphistory')
            // ->where('tdate', $dates)
            ->where('studid', $request->get('employeeid'))
            ->whereBetween('tdate', [$payrollperiod->datefrom, $payrollperiod->dateto])
            ->where('utype', '!=', '7')
            ->orderBy('ttime', 'asc')
            ->where('deleted', '0')
            ->get();

        $hr_attendance = DB::table('hr_attendance')
            // ->where('tdate', $dates)
            ->where('studid', $request->get('employeeid'))
            ->whereBetween('tdate', [$payrollperiod->datefrom, $payrollperiod->dateto])
            ->where('deleted', 0)
            ->orderBy('ttime', 'asc')
            ->get();

        $departmentid = DB::table('teacher')
            ->select(
                'hr_departments.id as departmentid',
                'teacher.id as tid'
            )
            ->leftJoin('employee_personalinfo', 'teacher.id', 'employee_personalinfo.employeeid')
            ->leftJoin('civilstatus', 'employee_personalinfo.maritalstatusid', 'civilstatus.civilstatus')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->leftJoin('hr_departments', 'employee_personalinfo.departmentid', 'hr_departments.id')
            ->where('teacher.id', $request->get('employeeid'))
            ->first()->departmentid;
        // ->get();

        // return $departmentid;

        $payrollStartDate = Carbon::parse($payrollperiod->datefrom);
        $payrollEndDate = Carbon::parse($payrollperiod->dateto);

        $matchingSemesterDates = [];

        foreach ($semesterdateactive as $semesterDate) {
            $semesterStartDate = Carbon::parse($semesterDate->sdate);
            $semesterEndDate = Carbon::parse($semesterDate->edate);

            if ($payrollStartDate->between($semesterStartDate, $semesterEndDate) || $payrollEndDate->between($semesterStartDate, $semesterEndDate)) {
                $matchingSemesterDates[] = $semesterDate;
            }
        }
        // return $matchingSemesterDates;
        // Check if there are matching semester dates before proceeding
        $collegedata = []; // Initialize $collegedata before the if statement

        if (!empty($matchingSemesterDates)) {
            foreach ($matchingSemesterDates as $matchingSemesterDate) {
                $collegedata = DB::table('employee_clloadsubjects')
                    ->where('employeeid', $employeeinfo->id)
                    ->where('semid', $matchingSemesterDate->semester)
                    ->where('syid', $matchingSemesterDate->syid)
                    ->get();
            }
        }

        $clregulardata = [];
        $cloverloaddata = [];
        $clparttimedata = [];
        if (count($collegedata) > 0) {
            foreach ($collegedata as $subject) {
                $amountperhalfmonth = $subject->amountperhalfmonth;

                switch ($subject->subjtype) {
                    case 1:
                        // Regular data
                        $clregulardata[] = $subject;
                        break;

                    case 2:
                        // Overload data
                        $cloverloaddata[] = $subject;
                        break;

                    case 3:
                        // Part-time data
                        $clparttimedata[] = $subject;
                        break;

                    // Add more cases if needed for other subjtype values

                    default:
                        // Handle other cases or unknown values if needed
                        break;
                }
            }
        }

        // Calculate the sum of amountperhalfmonth for each array
        $sumClRegular = number_format(array_sum(array_column($clregulardata, 'amountperhalfmonth')), 2);
        $sumClOverload = number_format(array_sum(array_column($cloverloaddata, 'amountperhalfmonth')), 2);
        $sumClPartTime = number_format(array_sum(array_column($clparttimedata, 'amountperhalfmonth')), 2);

        // return $sumClPartTime;
        // Optionally, you can return the segregated data and the sums
        $result = [
            'clregulardata' => $clregulardata,
            'sumClRegular' => $sumClRegular,
            'cloverloaddata' => $cloverloaddata,
            'sumClOverload' => $sumClOverload,
            'clparttimedata' => $clparttimedata,
            'sumClPartTime' => $sumClPartTime,
        ];

        $monthlypayroll = DB::table('hr_payrollv2')
            ->select('hr_payrollv2history.*', 'hr_payrollv2.datefrom', 'hr_payrollv2.dateto')
            ->join('hr_payrollv2history', 'hr_payrollv2.id', '=', 'hr_payrollv2history.payrollid')
            ->whereYear('hr_payrollv2.datefrom', date('Y', strtotime($payrollperiod->datefrom)))
            ->whereMonth('hr_payrollv2.datefrom', date('m', strtotime($payrollperiod->datefrom)))
            ->where('hr_payrollv2.deleted', '0')
            ->where('hr_payrollv2history.deleted', '0')
            ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
            ->get();
        // return collect($basicsalaryinfo);
        // return $monthlypayroll;
        $dates = array();

        if (!$basicsalaryinfo) {
            return view('hr.payroll.v3.getsalaryinfoempty');
        }

        if ($basicsalaryinfo) {

            $interval = new DateInterval('P1D');
            $realEnd = new DateTime($payrollperiod->dateto);
            $realEnd->add($interval);
            $period = new DatePeriod(new DateTime($payrollperiod->datefrom), $interval, $realEnd);
            // return collect($period);
            foreach ($period as $date) {
                if (strtolower($date->format('l')) == 'monday') {
                    if ($basicsalaryinfo->mondays == 1) {
                        $dates[] = $date->format('Y-m-d');
                    }
                } elseif (strtolower($date->format('l')) == 'tuesday') {
                    if ($basicsalaryinfo->tuesdays == 1) {
                        $dates[] = $date->format('Y-m-d');
                    }
                } elseif (strtolower($date->format('l')) == 'wednesday') {
                    if ($basicsalaryinfo->wednesdays == 1) {
                        $dates[] = $date->format('Y-m-d');
                    }
                } elseif (strtolower($date->format('l')) == 'thursday') {
                    if ($basicsalaryinfo->thursdays == 1) {
                        $dates[] = $date->format('Y-m-d');
                    }
                } elseif (strtolower($date->format('l')) == 'friday') {
                    if ($basicsalaryinfo->fridays == 1) {
                        $dates[] = $date->format('Y-m-d');
                    }
                } elseif (strtolower($date->format('l')) == 'saturday') {
                    if ($basicsalaryinfo->saturdays == 1) {
                        $dates[] = $date->format('Y-m-d');
                    }
                } elseif (strtolower($date->format('l')) == 'sunday') {
                    if ($basicsalaryinfo->sundays == 1) {
                        $dates[] = $date->format('Y-m-d');
                    }
                }
            }
            $employeeinfo->ratetype = $basicsalaryinfo->ratetype;

            $startDate = new DateTime($payrollperiod->datefrom);
            $endDate = new DateTime($payrollperiod->dateto);

            $mondayCount = 0;
            $tuesdayCount = 0;
            $wednesdayCount = 0;
            $thursdayCount = 0;
            $fridayCount = 0;
            $saturdayCount = 0;
            $sundayCount = 0;


            // Iterate through the date range
            while ($startDate <= $endDate) {
                // Get the day of the week as a lowercase string (e.g., "monday")
                $day_of_week = strtolower($startDate->format("l"));
                // Check if the day is Monday
                if ($day_of_week === "monday") {
                    $mondayCount++;
                } elseif ($day_of_week === "tuesday") {
                    $tuesdayCount++;
                } elseif ($day_of_week === "wednesday") {
                    $wednesdayCount++;
                } elseif ($day_of_week === "thursday") {
                    $thursdayCount++;
                } elseif ($day_of_week === "friday") {
                    $fridayCount++;
                } elseif ($day_of_week === "saturday" && $basicsalaryinfo->saturdays === 0) {
                    $saturdayCount++;
                } elseif ($day_of_week === "sunday") {
                    $sundayCount++;
                }
                // Move to the next day
                $startDate->modify("+1 day");
            }

            // return $fridayCount;
            if (strtolower($basicsalaryinfo->salarytype) == 'monthly' || strtolower($basicsalaryinfo->salarytype) == 'custom') {
                //return $dates;
                if ($basicsalaryinfo->amount == null || $basicsalaryinfo->amount == 0) {
                    $basicsalaryinfo->amountperday = 0;
                    $basicsalaryinfo->amountperhour = 0;
                } else {
                    if ($dates == null) {
                        // $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount/2);
                        // $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;

                        $basicsalaryinfo->amountperday = 0;
                        $basicsalaryinfo->amountperhour = 0;
                    } else {
                        // not fixed 26
                        // if ($basicsalaryinfo->halfdaysat == 1 || $basicsalaryinfo->halfdaysat == 2) {
                        //     $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount/2) / (count($dates));
                        //     $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
                        // } else {
                        //     $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount/2) / (count($dates) + $saturdayCount);
                        //     $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday;
                        // }

                        if ($basicsalaryinfo->halfdaysat == 1 || $basicsalaryinfo->halfdaysat == 2) {
                            $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount / 2) / 13;
                            $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                        } else {
                            $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount / 2) / 13;
                            $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                        }

                    }
                }

            } elseif (strtolower($basicsalaryinfo->salarytype) == 'daily') {
                $basicsalaryinfo->amountperday = $basicsalaryinfo->amount;
                $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                $basicsalaryinfo->amount = ($basicsalaryinfo->amount * count($dates));
            } elseif (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount * $basicsalaryinfo->hoursperday) * count($dates);
                $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
            }
        }

        // return collect($basicsalaryinfo);
        //return ($basicsalaryinfo->amount/2)/11;
        $payrollinfo = DB::table('hr_payrollv2history')
            ->where('payrollid', $payrollperiod->id)
            ->where('employeeid', $request->get('employeeid'))
            ->where('deleted', '0')
            ->first();

        $configured = 0;
        $released = 0;
        if ($payrollinfo) {
            $configured = $payrollinfo->configured;
            $released = $payrollinfo->released;
        }

        // return $dates;
        // $attendance = \App\Models\HR\HREmployeeAttendance::gethours($dates, $request->get('employeeid'));
        if (!empty($dates)) {
            // $attendance = \App\Models\HR\HREmployeeAttendance::gethours($matchingDates, $employeeinfo->id,$taphistory,$hr_attendance,$departmentid);
            $attendance = \App\Models\HR\HREmployeeAttendance::gethours($dates, $request->get('employeeid'), $taphistory, $hr_attendance, $departmentid);

        } else {
            $attendance = [];
        }
        $timebrackets = array();

        // // $attendance = array();
        // return collect($basicsalaryinfo);
        if (count($attendance) > 0) {
            foreach ($attendance as $eachdate) {
                $eachdate->totalminuteswork = 0;
                $eachdate->totalminuteslate = 0;
                $eachdate->totalundertime = 0;
                $eachdate->totalminutesundertime = 0;
                $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date, $employeeinfo, ($basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday), $basicsalaryinfo, $taphistory, $hr_attendance);
                // $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date,$employeeinfo,($basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday),$basicsalaryinfo);
                // return collect($latedeductiondetail);
                $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                $eachdate->lateminutes = $latedeductiondetail->lateminutes;
                $eachdate->holidayname = '';
                if (count($latedeductiondetail->brackets) > 0) {
                    foreach ($latedeductiondetail->brackets as $eachbracket) {
                        array_push($timebrackets, $eachbracket);
                    }
                }
                $eachdate->amountdeduct = 0;
                // $eachdateatt = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate,$employeeinfo,($basicsalaryinfo->amountperday/$basicsalaryinfo->hoursperday),$basicsalaryinfo);
                // return collect($eachdateatt);
            }
        }
        // $attendance = \App\Models\HR\HREmployeeAttendance::gethours($dates, $request->get('employeeid'));
        // return collect($attendance);
        if ($employeeinfo->departmentid) {
            $tardinessbaseonsalary = DB::table('hr_tardinesscomp')
                ->where('hr_tardinesscomp.deleted', '0')
                ->where('departmentid', $employeeinfo->departmentid)
                ->where('baseonattendance', 1)
                ->where('isactive', '1')
                ->first();
        }

        $tardiness_computations = DB::table('hr_tardinesscomp')
            ->where('hr_tardinesscomp.deleted', '0')
            ->where('hr_tardinesscomp.isactive', '1')
            ->get();

        $tardinessamount = 0;
        $lateduration = 0;
        $durationtype = 0;
        $tardinessallowance = 0;
        $tardinessallowancetype = 0;

        if (count($attendance) > 0 && count($tardiness_computations) > 0) {
            foreach ($attendance as $eachatt) {
                $eachatt->lateamminutes = ($eachatt->lateamhours * 60);
                $eachatt->latepmminutes = ($eachatt->latepmhours * 60);
                $eachatt->lateminutes = ($eachatt->latehours * 60);
                $eachatt->appliedleave = null;

                //return $eachatt->lateminutes;
                $eachcomputations = collect($tardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                //return $eachcomputations;
                $fromcomputations = collect($tardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                //return $fromcomputations;
                $eachcomputations = $eachcomputations->merge($fromcomputations);
                $eachcomputations = $eachcomputations->unique();
                //return $eachcomputations;

                if ($basicsalaryinfo->attendancebased == 1) {
                    if (count($eachcomputations) > 0) {
                        foreach ($eachcomputations as $eachcomputation) {
                            if ($eachcomputation->latetimetype == 1) {
                                if ($eachcomputation->deducttype == 1) {
                                    $eachatt->amountdeduct = $eachcomputation->amount;
                                } else {
                                    $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                }

                            } else {
                                $computehours = ($eachatt->lateminutes / 60);
                                if ($eachcomputation->deducttype == 1) {
                                    $eachatt->amountdeduct = $eachcomputation->amount;
                                } else {
                                    $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                }
                            }
                        }
                    }
                } else {
                    $eachatt->amountdeduct = 0;
                }
            }
        }
        $latecomputationdetails = (object) array(
            'tardinessamount' => $tardinessamount,
            'lateduration' => $lateduration,
            'durationtype' => $durationtype,
            'tardinessallowance' => $tardinessallowance,
            'tardinessallowancetype' => $tardinessallowancetype
        );
        // return $attendance;


        // return collect($latecomputationdetails);
        ##allowances
        $standardallowances = Db::table('allowance_standard')
            ->select(
                'allowance_standard.id',
                'allowance_standard.baseonattendance',
                'allowance_standard.amountperday',
                'employee_allowancestandard.id as empallowanceid',
                'allowance_standard.description',
                'employee_allowancestandard.amount as eesamount',
                'employee_allowancestandard.amountbaseonsalary',
                'employee_allowancestandard.monday',
                'employee_allowancestandard.tuesday',
                'employee_allowancestandard.wednesday',
                'employee_allowancestandard.thursday',
                'employee_allowancestandard.friday',
                'employee_allowancestandard.saturday',
                'employee_allowancestandard.sunday'
            )
            ->leftJoin('employee_allowancestandard', 'allowance_standard.id', '=', 'employee_allowancestandard.allowance_standardid')
            ->where('employee_allowancestandard.employeeid', $request->get('employeeid'))
            ->where('employee_allowancestandard.status', '1')
            ->where('allowance_standard.deleted', '0')
            ->where('employee_allowancestandard.deleted', '0')
            ->get();



        // ================================================================================================================================================================================================================================
        // Additional Allowance for Days Allowances

        // $startDate = new DateTime($payrollperiod->datefrom);
        // $endDate = new DateTime($payrollperiod->dateto);

        // $mondayCount = 0;
        // $tuesdayCount = 0;
        // $wednesdayCount = 0;
        // $thursdayCount = 0;
        // $fridayCount = 0;
        // $saturdayCount = 0;
        // $sundayCount = 0;


        // // Iterate through the date range
        // while ($startDate <= $endDate) {
        //     // Get the day of the week as a lowercase string (e.g., "monday")
        //     $day_of_week = strtolower($startDate->format("l"));
        //     // Check if the day is Monday
        //     if ($day_of_week === "monday") {
        //         $mondayCount++;
        //     } elseif ($day_of_week === "tuesday") {
        //         $tuesdayCount++;
        //     } elseif ($day_of_week === "wednesday"){
        //         $wednesdayCount++;
        //     } elseif ($day_of_week === "thursday"){
        //         $thursdayCount++;
        //     } elseif ($day_of_week === "friday"){
        //         $fridayCount++;
        //     } elseif ($day_of_week === "saturday"){
        //         $saturdayCount++;
        //     } elseif ($day_of_week === "sunday"){
        //         $sundayCount++;
        //     }
        //     // Move to the next day
        //     $startDate->modify("+1 day");
        // }
        // return $saturdayCount;
        // $daysstandardallowances = Db::table('allowance_standard')
        //     ->select(
        //         'allowance_standard.id',
        //         'allowance_standard.baseonattendance',
        //         'allowance_standard.amountperday',
        //         'employee_allowancestandard.id as empallowanceid',
        //         'allowance_standard.description',
        //         'employee_allowancestandard.amount as eesamount',
        //         'employee_allowancestandard.amountbaseonsalary',
        //         'employee_allowancestandard.monday',
        //         'employee_allowancestandard.tuesday',
        //         'employee_allowancestandard.wednesday',
        //         'employee_allowancestandard.thursday',
        //         'employee_allowancestandard.friday',
        //         'employee_allowancestandard.saturday',
        //         'employee_allowancestandard.sunday'
        //     )
        //     ->leftJoin('employee_allowancestandard','allowance_standard.id','=','employee_allowancestandard.allowance_standardid')
        //     ->where('employee_allowancestandard.employeeid', $request->get('employeeid'))
        //     ->where('employee_allowancestandard.amountbaseonsalary', 1)
        //     ->where('employee_allowancestandard.status','1')
        //     ->where('allowance_standard.deleted','0')
        //     ->where('employee_allowancestandard.deleted','0')
        //     ->get();

        // return collect($basicsalaryinfo);
        // return $daysstandardallowances; 
        $alltotalamount = 0;

        // if (count($daysstandardallowances) > 0) {
        //     foreach ($daysstandardallowances as $daysstandardallowance) {
        //         if ($daysstandardallowance->monday == 1) {
        //             $mondayAmount = $basicsalaryinfo->amountperday * $mondayCount;
        //             $alltotalamount += $mondayAmount;
        //             $daysstandardallowance->mondayAmount = number_format($mondayAmount, 2);
        //         }
        //         if ($daysstandardallowance->tuesday == 1) {
        //             $tuesdayAmount = $basicsalaryinfo->amountperday * $tuesdayCount;
        //             $alltotalamount += $tuesdayAmount;
        //             $daysstandardallowance->tuesdayAmount = number_format($tuesdayAmount, 2);
        //         }
        //         if ($daysstandardallowance->wednesday == 1) {
        //             $wednesdayAmount = $basicsalaryinfo->amountperday * $wednesdayCount;
        //             $alltotalamount += $wednesdayAmount;
        //             $daysstandardallowance->wednesdayAmount = number_format($wednesdayAmount, 2);
        //         }
        //         if ($daysstandardallowance->thursday == 1) {
        //             $thursdayAmount = $basicsalaryinfo->amountperday * $thursdayCount;
        //             $alltotalamount += $thursdayAmount;
        //             $daysstandardallowance->thursdayAmount = number_format($thursdayAmount, 2);
        //         }
        //         if ($daysstandardallowance->friday == 1) {
        //             $fridayAmount = $basicsalaryinfo->amountperday * $fridayCount;
        //             $alltotalamount += $fridayAmount;
        //             $daysstandardallowance->fridayAmount = number_format($fridayAmount, 2);
        //         }
        //         if ($daysstandardallowance->saturday == 1) {
        //             $saturdayAmount = $basicsalaryinfo->amountperday * $saturdayCount;
        //             $alltotalamount += $saturdayAmount;
        //             $daysstandardallowance->saturdayAmount = number_format($saturdayAmount, 2);
        //         }
        //         if ($daysstandardallowance->sunday == 1) {
        //             $sundayAmount = $basicsalaryinfo->amountperday * $sundayCount;
        //             $alltotalamount += $sundayAmount;
        //             $daysstandardallowance->sundayAmount = number_format($sundayAmount, 2);
        //         }
        //         $daysstandardallowance->totaldaysallowanceamount = number_format($alltotalamount, 2);
        //         $alldaysstandardallowance[] = $daysstandardallowance;
        //     }
        // }

        // Return $alldaysstandardallowance with the updated values
        // return $alldaysstandardallowance;
        // ================================================================================================================================================================================================================================


        // return $standardallowances;
        if (count($standardallowances) > 0) {
            foreach ($standardallowances as $allowancetype) {
                if ($allowancetype->amountbaseonsalary === 1) {
                    if ($allowancetype->monday == 1) {
                        $mondayAmount = $basicsalaryinfo->amountperday * $mondayCount;
                        $alltotalamount += $mondayAmount;
                        $allowancetype->mondayAmount = number_format($mondayAmount, 2);
                    }
                    if ($allowancetype->tuesday == 1) {
                        $tuesdayAmount = $basicsalaryinfo->amountperday * $tuesdayCount;
                        $alltotalamount += $tuesdayAmount;
                        $allowancetype->tuesdayAmount = number_format($tuesdayAmount, 2);
                    }
                    if ($allowancetype->wednesday == 1) {
                        $wednesdayAmount = $basicsalaryinfo->amountperday * $wednesdayCount;
                        $alltotalamount += $wednesdayAmount;
                        $allowancetype->wednesdayAmount = number_format($wednesdayAmount, 2);
                    }
                    if ($allowancetype->thursday == 1) {
                        $thursdayAmount = $basicsalaryinfo->amountperday * $thursdayCount;
                        $alltotalamount += $thursdayAmount;
                        $allowancetype->thursdayAmount = number_format($thursdayAmount, 2);
                    }
                    if ($allowancetype->friday == 1) {
                        $fridayAmount = $basicsalaryinfo->amountperday * $fridayCount;
                        $alltotalamount += $fridayAmount;
                        $allowancetype->fridayAmount = number_format($fridayAmount, 2);
                    }
                    if ($allowancetype->saturday == 1) {
                        $saturdayAmount = $basicsalaryinfo->amountperday * $saturdayCount;
                        $alltotalamount += $saturdayAmount;
                        $allowancetype->saturdayAmount = number_format($saturdayAmount, 2);
                    }
                    if ($allowancetype->sunday == 1) {
                        $sundayAmount = $basicsalaryinfo->amountperday * $sundayCount;
                        $alltotalamount += $sundayAmount;
                        $allowancetype->sundayAmount = number_format($sundayAmount, 2);
                    }
                    $allowancetype->totaldaysallowanceamount = number_format($alltotalamount, 2);

                    // return $saturdayCount;
                    $allowancetype->amount = sprintf("%.2f", $alltotalamount);
                    $allowancetype->baseonattendance = 0;
                    $allowancetype->amountperday = 0;
                    $allowancetype->lock = 1;
                    $allowancetype->paidforthismonth = 0;
                    $allowancetype->totalamount = sprintf("%.2f", $alltotalamount);
                    $allowancetype->paymenttype = 0;
                    if ($basicsalaryinfo->salarybasistype == 5) {
                        $allowancetype->perday = $basicsalaryinfo->amountperday;
                    }
                } else {
                    // return collect($payrollperiod);

                    $eachallowance = \App\Models\HR\HRAllowances::getstandardallowances($request->get('employeeid'), $payrollperiod, $allowancetype->empallowanceid, $allowancetype->id, $allowancetype->baseonattendance, $allowancetype->amountperday, $allowancetype->amountbaseonsalary);
                    // return collect($eachallowance);
                    $allowancetype->amount = $eachallowance->amount;
                    $allowancetype->baseonattendance = $eachallowance->baseonattendance;
                    $allowancetype->amountperday = $eachallowance->amountperday;
                    $allowancetype->lock = $eachallowance->lock;
                    $allowancetype->paidforthismonth = $eachallowance->paidforthismonth;
                    $allowancetype->totalamount = $eachallowance->totalamount;
                    $allowancetype->paymenttype = $eachallowance->paymenttype;
                    $allowancetype->paidstatus = $eachallowance->paidstatus;
                }
            }
        }
        // return $request->all();
        // return $standardallowances;
        $otherallowances = Db::table('employee_allowanceother')
            ->select(
                'employee_allowanceother.id',
                'employee_allowanceother.description',
                'employee_allowanceother.amount',
                'employee_allowanceother.term'
            )
            ->where('employee_allowanceother.employeeid', $request->get('employeeid'))
            ->where('employee_allowanceother.deleted', '0')
            ->get();

        $otherallowancesarray = array();
        if (count($otherallowances) > 0) {
            foreach ($otherallowances as $eachotherallowance) {
                $paidallowances = DB::table('hr_payrollv2history')
                    ->select(DB::raw('SUM(`amountpaid`) as amountpaid'))
                    ->join('hr_payrollv2historydetail', 'hr_payrollv2history.id', '=', 'hr_payrollv2historydetail.headerid')
                    ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
                    ->where('hr_payrollv2history.released', '1')
                    ->where('hr_payrollv2history.deleted', '0')
                    ->where('hr_payrollv2historydetail.particulartype', '4')
                    ->where('hr_payrollv2historydetail.particularid', $eachotherallowance->id)
                    ->where('hr_payrollv2history.payrollid', '<', $payrollperiod->id)
                    ->first()->amountpaid;

                if ($paidallowances == null || $paidallowances == 0) {
                    $eachotherallowance->paidforthismonth = 0;
                    $eachotherallowance->lock = 0;
                    $eachotherallowance->totalamount = 0;
                    $eachotherallowance->amounttopay = 0;
                    $eachotherallowance->paymenttype = 0; // 0 = full; 1 = half;
                    if (count($monthlypayroll) == 0) {
                        if ($eachotherallowance->term == 0) {
                            $eachotherallowance->amounttopay = $eachotherallowance->amount;
                            $eachotherallowance->totalamount = $eachotherallowance->totalamount;
                        } else {
                            $eachotherallowance->amounttopay = ($eachotherallowance->amount / $eachotherallowance->term);
                            $eachotherallowance->totalamount = ($eachotherallowance->amount / $eachotherallowance->term);
                        }

                    } elseif (count($monthlypayroll) == 1) {
                        if ($payrollinfo) {
                            if ($payrollinfo->id == $monthlypayroll[0]->id) {
                                $allowanceinfo = DB::table('hr_payrollv2historydetail')
                                    ->where('headerid', $payrollinfo->id)
                                    ->where('particulartype', 4)
                                    ->where('deleted', '0')
                                    ->where('particularid', $eachotherallowance->id)
                                    ->first();

                                if ($allowanceinfo) {
                                    $eachotherallowance->paymenttype = $allowanceinfo->paymenttype;
                                    $eachotherallowance->amounttopay = $allowanceinfo->amountpaid;
                                    $eachotherallowance->totalamount = $allowanceinfo->totalamount;
                                } else {
                                    $eachotherallowance->paymenttype = null;
                                    $eachotherallowance->amounttopay = ($eachotherallowance->amount / $eachotherallowance->term);
                                    $eachotherallowance->totalamount = ($eachotherallowance->amount / $eachotherallowance->term);
                                }
                            } else {
                                if (date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom))) {
                                    $allowanceinfo = DB::table('hr_payrollv2historydetail')
                                        ->where('headerid', $monthlypayroll[0]->id)
                                        ->where('particulartype', 4)
                                        ->where('deleted', '0')
                                        ->where('particularid', $eachotherallowance->id)
                                        ->first();

                                    if ($allowanceinfo) {
                                        $eachotherallowance->paymenttype = $allowanceinfo->paymenttype;
                                        $eachotherallowance->amounttopay = $allowanceinfo->amountpaid;
                                        $eachotherallowance->totalamount = $allowanceinfo->totalamount;
                                    } else {
                                        if ($eachotherallowance->term == 0) {
                                            $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                            $eachotherallowance->totalamount = $eachotherallowance->totalamount;
                                        } else {
                                            $eachotherallowance->amounttopay = ($eachotherallowance->amount / $eachotherallowance->term);
                                            $eachotherallowance->totalamount = ($eachotherallowance->amount / $eachotherallowance->term);
                                        }
                                        $eachotherallowance->lock = 1;
                                    }

                                } else {
                                    if ($eachotherallowance->term == 0) {
                                        $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                        $eachotherallowance->totalamount = $eachotherallowance->totalamount;
                                    } else {
                                        $eachotherallowance->amounttopay = ($eachotherallowance->amount / $eachotherallowance->term);
                                        $eachotherallowance->totalamount = ($eachotherallowance->amount / $eachotherallowance->term);
                                    }
                                }

                            }

                            // return collect($deductinfo);
                        } else {
                            $allowanceinfo = DB::table('hr_payrollv2historydetail')
                                ->where('headerid', $monthlypayroll[0]->id)
                                ->where('particulartype', 4)
                                ->where('deleted', '0')
                                ->where('particularid', $eachotherallowance->id)
                                ->first();

                            if (date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom))) {

                                if ($allowanceinfo) {
                                    if ($allowanceinfo->paymenttype == 1) {
                                        $eachotherallowance->paymenttype = 1;
                                    } else {
                                        $eachotherallowance->paidforthismonth = 1;
                                    }
                                    // return $alloweinfo->paymenttype;
                                    $eachotherallowance->amounttopay = $allowanceinfo->amountpaid;
                                    $eachotherallowance->totalamount = $allowanceinfo->totalamount;
                                    $eachotherallowance->lock = 1;
                                } else {
                                    if ($allowanceinfo) {
                                        $eachotherallowance->amounttopay = $allowanceinfo->amount;
                                        $eachotherallowance->totalamount = $allowanceinfo->amount;
                                        $eachotherallowance->lock = 1;
                                    }

                                }

                            } else {
                                if ($eachotherallowance->term == 0) {
                                    $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                    $eachotherallowance->totalamount = $eachotherallowance->totalamount;
                                } else {
                                    $eachotherallowance->amounttopay = ($eachotherallowance->amount / $eachotherallowance->term);
                                    $eachotherallowance->totalamount = ($eachotherallowance->amount / $eachotherallowance->term);
                                }
                            }
                        }

                    } elseif (count($monthlypayroll) == 2) {
                        $allowanceinfo = DB::table('hr_payrollv2historydetail')
                            ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->id)
                            ->where('particulartype', 4)
                            ->where('deleted', '0')
                            ->where('particularid', $eachotherallowance->id)
                            ->first();

                        if ($allowanceinfo) {
                            if (collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->released == 1) {

                                if ($allowanceinfo) {
                                    if ($allowanceinfo->paymenttype == 1) {
                                        $eachotherallowance->paymenttype = 1;
                                    } else {
                                        $eachotherallowance->paidforthismonth = 1;
                                    }
                                    $eachotherallowance->amounttopay = $allowanceinfo->amountpaid;
                                    $eachotherallowance->totalamount = $allowanceinfo->totalamount;
                                    $eachotherallowance->lock = 1;
                                }

                            } else {
                                // return collect($deductinfo);
                                if ($allowanceinfo) {
                                    if ($allowanceinfo->paymenttype == 1) {
                                        $eachotherallowance->paymenttype = 1;
                                    } else {
                                        $eachotherallowance->paidforthismonth = 0;
                                    }
                                    $eachotherallowance->amounttopay = ($eachotherallowance->totalamount / $eachotherallowance->term);
                                    $eachotherallowance->totalamount = ($eachotherallowance->amount / $eachotherallowance->term);
                                    $eachotherallowance->lock = 1;
                                } else {
                                    if ($allowanceinfo) {
                                        $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                        $eachotherallowance->totalamount = $eachotherallowance->amount;
                                        $eachotherallowance->lock = 1;
                                    }

                                }
                            }
                        } else {
                            $allowanceinfo = DB::table('hr_payrollv2historydetail')
                                ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->id)
                                ->where('particulartype', 4)
                                ->where('deleted', '0')
                                ->where('particularid', $eachotherallowance->id)
                                ->first();

                            if ($allowanceinfo) {
                                if ($allowanceinfo->paymenttype == 0) {
                                    $eachotherallowance->paidforthismonth = 1;
                                    $eachotherallowance->amounttopay = $allowanceinfo->amountpaid;
                                    $eachotherallowance->totalamount = $allowanceinfo->totalamount;
                                    $eachotherallowance->lock = 1;
                                }
                            }
                        }
                    }
                    array_push($otherallowancesarray, $eachotherallowance);

                } else {
                    if ($eachotherallowance->amount > $paidallowances) {
                        $paidallowances = DB::table('hr_payrollv2history')
                            ->select(DB::raw('SUM(`amountpaid`) as amountpaid'))
                            ->join('hr_payrollv2historydetail', 'hr_payrollv2history.id', '=', 'hr_payrollv2historydetail.headerid')
                            ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
                            ->where('hr_payrollv2history.released', '1')
                            ->where('hr_payrollv2history.deleted', '0')
                            ->where('hr_payrollv2historydetail.particulartype', '4')
                            ->where('hr_payrollv2historydetail.particularid', $eachotherallowance->id)
                            ->where('hr_payrollv2history.payrollid', '<', $payrollperiod->id)
                            ->first()->amountpaid;


                        $eachotherallowance->paidforthismonth = 0;
                        $eachotherallowance->lock = 0;
                        $eachotherallowance->totalamount = 0;
                        $eachotherallowance->amounttopay = 0;
                        $eachotherallowance->paymenttype = 0; // 0 = full; 1 = half;
                        if (count($monthlypayroll) == 0) {
                            if ($eachotherallowance->term == 0) {
                                $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                $eachotherallowance->totalamount = $eachotherallowance->amount;
                            } else {
                                $eachotherallowance->amounttopay = ($eachotherallowance->amount / $eachotherallowance->term);
                                $eachotherallowance->totalamount = ($eachotherallowance->amount / $eachotherallowance->term);
                            }

                        } elseif (count($monthlypayroll) == 1) {
                            if ($payrollinfo) {
                                if ($payrollinfo->id == $monthlypayroll[0]->id) {
                                    $alloweinfo = DB::table('hr_payrollv2historydetail')
                                        ->where('headerid', $payrollinfo->id)
                                        ->where('particulartype', 4)
                                        ->where('deleted', '0')
                                        ->where('particularid', $eachotherallowance->id)
                                        ->first();

                                    if ($alloweinfo) {
                                        $eachotherallowance->paymenttype = $alloweinfo->paymenttype;
                                        $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                        $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                    }
                                } else {
                                    if (date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom))) {
                                        $alloweinfo = DB::table('hr_payrollv2historydetail')
                                            ->where('headerid', $monthlypayroll[0]->id)
                                            ->where('particulartype', 4)
                                            ->where('deleted', '0')
                                            ->where('particularid', $eachotherallowance->id)
                                            ->first();

                                        if ($alloweinfo) {
                                            $eachotherallowance->paymenttype = $alloweinfo->paymenttype;
                                            $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                            $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                        } else {
                                            if ($allowanceinfo) {
                                                $eachotherallowance->amounttopay = $allowanceinfo->amount;
                                                $eachotherallowance->totalamount = $allowanceinfo->amount;
                                                $eachotherallowance->lock = 1;
                                            }

                                        }

                                    } else {
                                        if ($allowanceinfo) {
                                            $eachotherallowance->amounttopay = $allowanceinfo->amount;
                                            $eachotherallowance->totalamount = $allowanceinfo->amount;
                                        }
                                    }
                                }
                            } else {
                                $alloweinfo = DB::table('hr_payrollv2historydetail')
                                    ->where('headerid', $monthlypayroll[0]->id)
                                    ->where('particulartype', 4)
                                    ->where('deleted', '0')
                                    ->where('particularid', $eachotherallowance->id)
                                    ->first();
                                if (date('Y-m', strtotime($monthlypayroll[0]->datefrom)) == date('Y-m', strtotime($payrollperiod->datefrom))) {
                                    // return collect($alloweinfo);
                                    if ($alloweinfo) {
                                        if ($alloweinfo->paymenttype == 1) {
                                            $eachotherallowance->paymenttype = 1;
                                        } else {
                                            $eachotherallowance->paidforthismonth = 1;
                                        }
                                        // return $alloweinfo->paymenttype;
                                        $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                        $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                        $eachotherallowance->lock = 1;
                                    } else {
                                        if ($allowanceinfo) {
                                            $eachotherallowance->amounttopay = $allowanceinfo->amount;
                                            $eachotherallowance->totalamount = $allowanceinfo->amount;
                                            $eachotherallowance->lock = 1;
                                        }

                                    }

                                } else {
                                    if ($eachotherallowance->term == 0) {
                                        $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                        $eachotherallowance->totalamount = $eachotherallowance->amount;
                                    } else {
                                        $eachotherallowance->amounttopay = ($eachotherallowance->amount / $eachotherallowance->term);
                                        $eachotherallowance->totalamount = ($eachotherallowance->amount / $eachotherallowance->term);
                                    }
                                }
                            }

                        } elseif (count($monthlypayroll) == 2) {
                            $alloweinfo = DB::table('hr_payrollv2historydetail')
                                ->where('headerid', collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->id ?? 0)
                                ->where('particulartype', 4)
                                ->where('deleted', '0')
                                ->where('particularid', $eachotherallowance->id)
                                ->first();

                            if ($alloweinfo) {
                                if (collect($monthlypayroll)->where('payrollid', $payrollperiod->id)->first()->released == 1) {

                                    if ($alloweinfo) {
                                        if ($alloweinfo->paymenttype == 1) {
                                            $eachotherallowance->paymenttype = 1;
                                        } else {
                                            $eachotherallowance->paidforthismonth = 1;
                                        }
                                        $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                        $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                        $eachotherallowance->lock = 1;
                                    }

                                } else {
                                    if ($alloweinfo) {
                                        if ($alloweinfo->paymenttype == 1) {
                                            $eachotherallowance->paymenttype = 1;
                                        } else {
                                            $eachotherallowance->paidforthismonth = 1;
                                        }
                                        $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                        $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                        $eachotherallowance->lock = 1;
                                    } else {
                                        if ($allowanceinfo) {
                                            $eachotherallowance->amounttopay = $eachotherallowance->amount;
                                            $eachotherallowance->totalamount = $eachotherallowance->amount;
                                            $eachotherallowance->lock = 1;
                                        }

                                    }
                                }
                            } else {
                                if (isset($payrollinfo->id)) {
                                    $alloweinfo = DB::table('hr_payrollv2historydetail')
                                        ->where('headerid', collect($monthlypayroll)->where('payrollid', '!=', $payrollinfo->id)->first()->id)
                                        ->where('particulartype', 4)
                                        ->where('deleted', '0')
                                        ->where('particularid', $eachotherallowance->id)
                                        ->first();

                                    if ($alloweinfo) {
                                        if ($alloweinfo->paymenttype == 0) {
                                            $eachotherallowance->paidforthismonth = 1;
                                            $eachotherallowance->amounttopay = $alloweinfo->amountpaid;
                                            $eachotherallowance->totalamount = $alloweinfo->totalamount;
                                            $eachotherallowance->lock = 1;
                                        }
                                    }
                                }
                            }
                        }
                        array_push($otherallowancesarray, $eachotherallowance);
                    }
                }
            }
        }


        $otherallowances = $otherallowancesarray;
        ##deductions

        $deductiontypes = Db::table('deduction_standard')
            ->where('deleted', '0')
            // ->where('id','7')
            ->get();

        $standarddeductions = array();

        $employeestandardded = DB::table('employee_deductionstandard')
            ->where('employeeid', $request->get('employeeid'))
            ->where('deleted', '0')
            ->where('status', '1')
            ->get();

        if (count($deductiontypes) > 0) {
            foreach ($deductiontypes as $deductiontype) {
                $checkifapplied = DB::table('employee_deductionstandard')
                    ->where('employeeid', $request->get('employeeid'))
                    ->where('deduction_typeid', $deductiontype->id)
                    ->where('deleted', '0')
                    ->where('status', '1')
                    ->first();

                if ($checkifapplied) {
                    // return collect($deductiontype);
                    // try{
                    $eachdeduction = \App\Models\HR\HRDeductions::getstandarddeductions($request->get('employeeid'), $payrollperiod, $deductiontype->id);
                    // return collect($eachdeduction);
                    $deductiontype->amount = $eachdeduction->amount;
                    $deductiontype->lock = $eachdeduction->lock;
                    $deductiontype->paidforthismonth = $eachdeduction->paidforthismonth;
                    $deductiontype->totalamount = $eachdeduction->totalamount;
                    $deductiontype->paymenttype = 1;
                    $deductiontype->balances = $eachdeduction->balances;
                    $deductiontype->paidstatus = $eachdeduction->paidstatus;
                    // array_push($standarddeductions, $deductiontype);
                    if ($deductiontype->amount < 1 && count($deductiontype->balances) == 0) {
                        if ($basicsalaryinfo->salarybasistype == 5 || $basicsalaryinfo->salarytype == 'Daily') {
                            array_push($standarddeductions, $deductiontype);
                        }
                    } else {
                        if ($deductiontype->amount > 0) {
                            array_push($standarddeductions, $deductiontype);
                        }
                    }
                    // }catch(\Exception $error)
                    // {
                    //     $eachdeduction = \App\Models\HR\HRDeductions::getstandarddeductions($request->get('employeeid'), $payrollperiod, $deductiontype->id);
                    //     return collect($eachdeduction);
                    // }
                    // return collect($eachdeduction);
                }
                // else{
                //     $amount = \App\Http\Controllers\HRControllers\HRDeductionSetupController::getbracket_standard($request->get('employeeid'), $basicsalaryinfo->amount, $deductiontype->id, 'amount');
                //     $deductiontype->amount = $amount;
                //     $deductiontype->lock = 0;
                //     $deductiontype->paidforthismonth = 0;
                //     $deductiontype->totalamount = $amount;
                //     $deductiontype->paymenttype = 0;
                //     $deductiontype->balances = array();
                // }
            }
        }



        foreach ($employeestandardded as $employeestandard) {
            foreach ($standarddeductions as $standarddeduction) {
                if ($employeestandard->deduction_typeid == $standarddeduction->id) {
                    if ($standarddeduction->totalamount != $employeestandard->eesamount) {
                        $standarddeduction->updatedamount = $employeestandard->eesamount;
                    } else {
                        $standarddeduction->updatedamount = 0;
                    }
                }
            }
        }

        // return $employeestandardded;
        // return $standarddeductions;
        // $otherdeductions = Db::table('employee_deductionother')
        //     // ->select(
        //     //     'employee_deductionother.id',
        //     //     'employee_deductionother.description',
        //     //     'employee_deductionother.amount',
        //     //     'employee_deductionother.term'
        //     // )
        //     ->where('employee_deductionother.employeeid', $request->get('employeeid'))
        //     ->where('employee_deductionother.paid','0')
        //     ->where('employee_deductionother.status','1')
        //     ->where('employee_deductionother.deleted','0')
        //     ->where('deductionotherid','!=',null)
        //     ->get();

        // return $payrolldetails;
        $payrollv2history = DB::table('hr_payrollv2history')
            ->leftJoin('hr_payrollv2', 'hr_payrollv2history.payrollid', '=', 'hr_payrollv2.id')
            ->where('hr_payrollv2history.released', 1)
            ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
            ->get();


        $otherdeductions = Db::table('employee_deductionother')
            ->where('employee_deductionother.employeeid', $request->get('employeeid'))
            ->where('employee_deductionother.paid', '0')
            ->where('employee_deductionother.status', '1')
            ->where('employee_deductionother.deleted', '0')
            // ->where('employee_deductionother.balanceamount', '=', null)
            ->where('deductionotherid', '!=', null)
            ->where('employee_deductionother.paidna', null)
            // ->where('description', 'DELP')
            ->get();

        $payrolldetails = DB::table('hr_payrollv2historydetail')
            ->leftJoin('hr_payrollv2history', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2history.payrollid')
            ->where('hr_payrollv2historydetail.particulartype', 2)
            ->where('hr_payrollv2historydetail.employeeid', $request->get('employeeid'))
            ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
            ->where('hr_payrollv2historydetail.paidstatus', 1)
            ->where('hr_payrollv2historydetail.deleted', 0)
            ->where('hr_payrollv2history.released', 1)
            ->get();

        foreach ($otherdeductions as $otherdeduction) {
            $totalotherdeductionpaid = 0;
            foreach ($payrolldetails as $payrolldetail) {
                if ($otherdeduction->id == $payrolldetail->particularid) {
                    if ($payrolldetail->deductionid == $otherdeduction->deductionotherid) {
                        $totalotherdeductionpaid += $payrolldetail->amountpaid;
                    }
                }

            }
            $otherdeduction->totalotherdeductionpaid = round($totalotherdeductionpaid + $otherdeduction->paidamount, 2);
        }


        foreach ($otherdeductions as $otherdeduction) {
            $remainingamount = $otherdeduction->fullamount - $otherdeduction->totalotherdeductionpaid;
            $otherdeduction->monthlypayment = $otherdeduction->amount;
            $otherdeduction->remainingamount = round($remainingamount, 2);



            // if ($remainingamount < $otherdeduction->amount) {
            //    $otherdeduction->amount = $remainingamount;
            // }
            if ($otherdeduction->term != 0) {

                if ($remainingamount < $otherdeduction->amount) {
                    $otherdeduction->amount = round($remainingamount, 2);
                    $otherdeduction->paidthispayroll = 1;
                } else {
                    $otherdeduction->paidthispayroll = 0;
                }
            }
        }

        $otherdeductionsarray = array();
        $lastpayroll = [];
        $headerarr = [];

        if (count($otherdeductions) > 0) {

            foreach ($otherdeductions as $eachotherdeduction) {
                if ($eachotherdeduction->fullamount == $eachotherdeduction->totalotherdeductionpaid && strpos($eachotherdeduction->description, 'PERAA') === false) {

                } else {
                    // return collect($eachotherdeduction);
                    // return $eachotherdeduction->remainingamount;

                    $monthlypayment = $eachotherdeduction->monthlypayment;

                    $deductionStartDate = new DateTime($eachotherdeduction->startdate);
                    $formattedStartDate = $deductionStartDate->format('Y-m-d');

                    if ($formattedStartDate < $payrollperiod->datefrom || $formattedStartDate == $payrollperiod->datefrom || $formattedStartDate == $payrollperiod->dateto) {
                        $eachotherdeduction->particulartype = 2;
                        $eachotherdeduction->particularid = $eachotherdeduction->id;
                        $eachotherdeduction->dataid = $eachotherdeduction->deductionotherid;
                        $amountpaid = 0;
                        $eachotherdeduction->paidforthismonth = 0;
                        // $eachotherdeduction->lock = 0;
                        $eachotherdeduction->totalamount = 0;
                        $eachotherdeduction->amounttopay = 0;
                        $eachotherdeduction->paidstatus = 0;
                        $eachotherdeduction->paidforthismonth = 0;
                        $eachotherdeduction->totalpaidamount = $eachotherdeduction->totalotherdeductionpaid;

                        $paiddeductions = DB::table('hr_payrollv2history')
                            ->select(DB::raw('SUM(`amountpaid`) as amountpaid'))
                            ->leftjoin('hr_payrollv2historydetail', 'hr_payrollv2history.id', '=', 'hr_payrollv2historydetail.headerid')
                            ->leftjoin('hr_payrollv2', 'hr_payrollv2history.payrollid', '=', 'hr_payrollv2.id')
                            ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
                            ->where('hr_payrollv2history.released', '1')
                            ->where('hr_payrollv2history.deleted', '0')
                            ->where('hr_payrollv2.deleted', '0')
                            ->where('hr_payrollv2historydetail.particulartype', '2')
                            ->where('hr_payrollv2historydetail.particularid', $eachotherdeduction->id)
                            ->where('hr_payrollv2history.payrollid', '<=', $payrollperiod->id)
                            ->first()->amountpaid;

                        // determine payroll cutoff if first or second /last
                        if ($payrollperiod) {
                            $datefrom_day = date('d', strtotime($payrollperiod->datefrom));
                            $dateto_day = date('d', strtotime($payrollperiod->dateto));
                            $payrollyear = date('Y', strtotime($payrollperiod->dateto));
                            $payrollmonth = date('m', strtotime($payrollperiod->dateto));

                            if ($datefrom_day == 1 && $dateto_day == 15) {
                                $cutoff = 1; // first cutoff of the month
                            } else {
                                $cutoff = 2; // second cutoff of the month
                            }
                        }

                        // return $cutoff;
                        if ($cutoff == 2) {

                            if ($eachotherdeduction->description != 'PERAA') {
                                $firstpayroll_cutoff = DB::table('hr_payrollv2')
                                    ->where('deleted', 0)
                                    ->where('id', '!=', $payrollperiod->id)
                                    ->whereYear('dateto', $payrollyear)
                                    ->whereMonth('dateto', $payrollmonth)
                                    ->first();

                                $headerid = DB::table('hr_payrollv2history')
                                    ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
                                    ->where('deleted', 0)
                                    ->where(function ($query) use ($payrollperiod, $firstpayroll_cutoff) {
                                        $query->where('payrollid', $payrollperiod->id)
                                            ->orWhere('payrollid', $firstpayroll_cutoff->id ?? null);
                                    })
                                    ->get();

                                $headerid = $headerid->pluck('id');
                                $headerarr = $headerid;
                                $paid_deductions = DB::table('hr_payrollv2historydetail')
                                    ->select('hr_payrollv2historydetail.*')
                                    ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                                    ->whereIn('headerid', $headerarr)
                                    ->where('particulartype', 2)
                                    ->where('hr_payrollv2.deleted', '0')
                                    ->where('hr_payrollv2historydetail.employeeid', $request->get('employeeid'))
                                    ->where('hr_payrollv2historydetail.deleted', '0')
                                    ->where('hr_payrollv2historydetail.deductionid', $eachotherdeduction->deductionotherid)
                                    ->get();

                                $firstpayrol = null;
                                if ($firstpayroll_cutoff) {
                                    $firstpayrol = DB::table('hr_payrollv2historydetail')
                                        ->select('hr_payrollv2historydetail.*')
                                        ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                                        ->where('particulartype', 2)
                                        ->where('hr_payrollv2.deleted', '0')
                                        ->where('hr_payrollv2historydetail.employeeid', $employeeinfo->id)
                                        ->where('hr_payrollv2historydetail.payrollid', $firstpayroll_cutoff->id)
                                        ->where('hr_payrollv2historydetail.paidstatus', 1)
                                        ->where('hr_payrollv2historydetail.totalamount', '!=', 0)
                                        ->where('hr_payrollv2historydetail.deleted', '0')
                                        ->where('hr_payrollv2historydetail.deductionid', $eachotherdeduction->deductionotherid)
                                        ->first();
                                }

                                if ($firstpayrol) {
                                    if ($firstpayrol->paymenttype == 0) {

                                        $eachotherdeduction->paidforthismonth = 0;
                                        $eachotherdeduction->paidstatus = 1;
                                        $eachotherdeduction->paymenttype = 1;

                                    } else if ($firstpayrol->paymenttype == 1) {
                                        $remainingamountod = $eachotherdeduction->fullamount - $eachotherdeduction->totalpaidamount;
                                        if ($eachotherdeduction->remainingamount < (float) $eachotherdeduction->monthlypayment) {
                                            $eachotherdeduction->paymenttype = 0;
                                            $eachotherdeduction->amounttopay = $remainingamountod;
                                        } else {
                                            $eachotherdeduction->paymenttype = 1;
                                        }

                                    }
                                } else {


                                    if (count($paid_deductions) == 0) {
                                        $eachotherdeduction->paymenttype = 1;
                                        $eachotherdeduction->paidstatus = 1;
                                        $eachotherdeduction->amounttopay = $eachotherdeduction->monthlypayment / 2;
                                    } else {
                                        foreach ($paid_deductions as $paiddeduction) {
                                            // return collect($paiddeduction);
                                            //  first cutoff oth the month
                                            if ($paiddeduction->payrollid == $payrollperiod->id) {

                                                if ($paiddeduction->paidstatus == 1) {
                                                    $eachotherdeduction->paidstatus = 1;

                                                    $amountpaid = $paiddeduction->amountpaid;
                                                    if ($amountpaid == $eachotherdeduction->amount) {

                                                        $eachotherdeduction->paymenttype = 0;
                                                        $eachotherdeduction->amounttopay = $amountpaid;

                                                    } else if ($amountpaid == floor(($eachotherdeduction->amount / 2) * 100) / 100) {
                                                        $eachotherdeduction->paymenttype = 1;
                                                        $eachotherdeduction->amounttopay = $amountpaid;
                                                    } else {
                                                        $eachotherdeduction->paymenttype = 0;
                                                        $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                                    }

                                                } else {
                                                    // wala nabayran or iya gi unselect

                                                }

                                            } else {

                                                $amountpaid = $paiddeduction->amountpaid;

                                                if ($amountpaid == $eachotherdeduction->amount) {
                                                    $eachotherdeduction->paidforthismonth = 1;
                                                    $eachotherdeduction->amounttopay = 0;
                                                    $eachotherdeduction->paidstatus = 0;
                                                    $eachotherdeduction->amounttobededuct = 0;
                                                    $eachotherdeduction->paymenttype = 0;

                                                } else if ($amountpaid == floor(($eachotherdeduction->amount / 2) * 100) / 100) {
                                                    $eachotherdeduction->paymenttype = 1;
                                                    $eachotherdeduction->amounttopay = $amountpaid;
                                                    $eachotherdeduction->amounttobededuct = $amountpaid;
                                                    $eachotherdeduction->paidstatus = 1;
                                                } else {
                                                    $eachotherdeduction->paymenttype = 0;
                                                    $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                                    $eachotherdeduction->amounttobededuct = $eachotherdeduction->amount;
                                                    $eachotherdeduction->paidstatus = 1;
                                                }

                                            }
                                        }
                                    }
                                }

                                array_push($otherdeductionsarray, $eachotherdeduction);

                            } else {
                                $eachotherdeduction->paidforthismonth = 1;
                                $eachotherdeduction->paidstatus = 0;
                                $eachotherdeduction->paymenttype = 0;

                                array_push($otherdeductionsarray, $eachotherdeduction);

                            }


                        } else {
                            // return $eachotherdeduction->remainingamount;

                            $headerid = DB::table('hr_payrollv2history')
                                ->where('deleted', 0)
                                ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
                                ->where('payrollid', $payrollperiod->id)
                                ->first()->id ?? '';

                            $headerarr[] = $headerid;

                            // this is to retirieve the paid deductions
                            $paid_deductions = DB::table('hr_payrollv2historydetail')
                                ->select('hr_payrollv2historydetail.*')
                                ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                                ->whereIn('hr_payrollv2historydetail.headerid', $headerarr)
                                ->where('hr_payrollv2historydetail.particulartype', 2)
                                ->where('hr_payrollv2historydetail.particularid', $eachotherdeduction->id)
                                ->where('hr_payrollv2.deleted', '0')
                                ->where('hr_payrollv2historydetail.employeeid', $request->get('employeeid'))
                                ->where('hr_payrollv2historydetail.deleted', '0')
                                ->where('hr_payrollv2historydetail.deductionid', $eachotherdeduction->deductionotherid)
                                ->get();

                            // return collect($eachotherdeduction);
                            // return $paid_deductions;
                            // return $eachotherdeduction->remainingamount;

                            // return collect($eachotherdeduction);

                            if (count($paid_deductions) > 0) {

                                if ($eachotherdeduction->deductionstatus == 0) {
                                    // if deduction is need to be paid per kinsina
                                    $eachotherdeduction->paymenttype = 1; // 0 = full; 1 = half;

                                    foreach ($paid_deductions as $paiddeduction) {
                                        // return $otherdeduction->remainingamount;
                                        // return $monthlypayment / 2;
                                        // return $payrollperiod->id;
                                        //  first cutoff oth the month
                                        if ($paiddeduction->payrollid == $payrollperiod->id) {

                                            if ($paiddeduction->paidstatus == 1) {
                                                $eachotherdeduction->paidstatus = 1;

                                                if ($eachotherdeduction->remainingamount == $monthlypayment / 2) {

                                                    $eachotherdeduction->paymenttype = 0;
                                                    $eachotherdeduction->amounttopay = $eachotherdeduction->remainingamount;
                                                    $eachotherdeduction->paidforthismonth = 1;

                                                } else if ($eachotherdeduction->remainingamount > $monthlypayment / 2) {

                                                    $eachotherdeduction->paymenttype = 1;
                                                    $eachotherdeduction->amounttopay = $eachotherdeduction->monthlypayment / 2;

                                                } else if ($eachotherdeduction->remainingamount <= $monthlypayment / 2) {

                                                    $eachotherdeduction->paymenttype = 0;
                                                    $eachotherdeduction->amounttopay = $eachotherdeduction->remainingamount;
                                                    $eachotherdeduction->paidforthismonth = 1;

                                                } else {

                                                    $amountpaid = $paiddeduction->amountpaid;


                                                    if ($amountpaid == $eachotherdeduction->amount) {
                                                        $eachotherdeduction->paymenttype = 0;
                                                        $eachotherdeduction->amounttopay = $amountpaid;
                                                        $eachotherdeduction->paidforthismonth = 1;

                                                    } else if ($amountpaid == floor(($eachotherdeduction->amount / 2) * 100) / 100) {

                                                        $eachotherdeduction->paymenttype = 1;
                                                        $eachotherdeduction->amounttopay = $amountpaid;

                                                    } else {
                                                        $eachotherdeduction->paymenttype = 3;
                                                        $eachotherdeduction->amounttopay = $amountpaid;

                                                    }
                                                }

                                            } else {
                                                // wala nabayran or iya gi unselect

                                            }
                                        }
                                    }

                                } else {
                                    // if deduction is need to be paid per binulan
                                    $eachotherdeduction->paymenttype = 0; // 0 = full; 1 = half;
                                    $eachotherdeduction->amounttopay = $eachotherdeduction->amount;

                                }

                            } else {

                                if ($eachotherdeduction->deductionstatus == 0) {
                                    // if deduction is need to be paid per kinsina
                                    $eachotherdeduction->paymenttype = 1; // 0 = full; 1 = half;
                                    $eachotherdeduction->paidstatus = 1;
                                    $eachotherdeduction->amounttopay = floor(($eachotherdeduction->amount / 2) * 100) / 100;
                                } else {
                                    // if deduction is need to be paid per binulan
                                    $eachotherdeduction->paidstatus = 1; // 0 = full; 1 = half;
                                    $eachotherdeduction->paymenttype = 0; // 0 = full; 1 = half;
                                    $eachotherdeduction->amounttopay = $eachotherdeduction->amount;

                                }
                            }

                            array_push($otherdeductionsarray, $eachotherdeduction);

                        }
                    }
                }

            }
        }

        $otherdeductions = $otherdeductionsarray;
        // return $otherdeductions;
        $deductionsetup = DB::table('employee_basicsalaryinfo')
            ->where('employeeid', $request->get('employeeid'))
            ->where('deleted', '0')
            ->first()
            ->deductionsetup;
        $deductiontypes = Db::table('deduction_standard')
            ->where('deleted', '0')
            ->get();

        $deletedparticularsdetails = DB::table('hr_payrollv2historydetail')
            ->where('employeeid', $employeeinfo->id)
            ->where('payrollid', $payrollperiod->id)
            ->where('particulartype', null)
            ->where('deleted', 1)
            ->get();

        $deletedEntries = $deletedparticularsdetails->map(function ($item) {

            return [
                'description' => $item->description,
                'employeeid' => $item->employeeid,
                'payrollid' => $item->payrollid
            ];
        })->toArray();


        $addedparticulars = array();

        if ($payrollinfo) {
            $addedparticulars = DB::table('hr_payrollv2addparticular')
                ->where('payrollid', $payrollperiod->id)
                ->where('employeeid', $request->get('employeeid'))
                ->where('deleted', '0')
                ->get();

            // return $addedparticulars;
        }
        if ($addedparticulars) {
            $addedparticulars = $addedparticulars->filter(function ($item) use ($deletedEntries) {
                foreach ($deletedEntries as $deletedItem) {
                    if (
                        $item->description === $deletedItem['description'] &&
                        $item->employeeid === $deletedItem['employeeid'] &&
                        $item->payrollid === $deletedItem['payrollid']
                    ) {
                        return false;
                    }
                }
                return true;
            });
        }


        $getholidays = DB::table('schoolcal')
            ->leftJoin('schoolcaltype', 'schoolcal.type', '=', 'schoolcaltype.id')
            ->where('schoolcal.syid', DB::table('sy')->where('isactive', '1')->first()->id)
            ->where('schoolcal.deleted', '0')
            ->where('schoolcaltype.type', '1')
            ->get();


        $holidaypay = 0;

        if (count($getholidays) > 0) {
            foreach ($getholidays as $holiday) {

                $holidays = array();

                $holidaybegin = new DateTime($holiday->datefrom);

                $holidayend = new DateTime($holiday->dateto);

                $holidayend = $holidayend->modify('+1 day');

                $holidayintervalday = new DateInterval('P1D');

                $holidaydaterange = new DatePeriod($holidaybegin, $holidayintervalday, $holidayend);

                foreach ($holidaydaterange as $holidaydate) {

                    array_push($holidays, $holidaydate->format("Y-m-d"));
                }
                if (count($holidays) > 0) {
                    // foreach($holidays as $holidaydate)
                    // {
                    //     if(in_array($holidaydate, $attendanceabsent))
                    //     {
                    //         //no work
                    //         $holidaypay += ($dailyrate * ($holiday->ratepercentagenowork/100));
                    //     }
                    //     if(in_array($holidaydate, $attendancepresent))
                    //     {

                    //         $holidaypay+=($dailyrate * ($holiday->ratepercentageworkon/100));
                    //     }
                    // }
                }

            }
        }

        $perdaysalary = 0;
        $perhour = 0;

        if ($basicsalaryinfo) {
            if ($basicsalaryinfo->amount == null || $basicsalaryinfo->amount == 0) {
                $perdaysalary = 0;
                $perhour = 0;
            } else {
                $perdaysalary = $basicsalaryinfo->amountperday;
                $perhour = ($basicsalaryinfo->amountperday) / $basicsalaryinfo->hoursperday;
            }

        }


        if ($configured == 0) {

            $leavedetails = \App\Models\HR\HRSalaryDetails::getleavesapplied($request->get('employeeid'), $payrollperiod);

            if (count($leavedetails) > 0) {

                foreach ($leavedetails as $leave) {

                    $leave->amount = 0.00;

                    $getpay = DB::table('hr_leaves')
                        ->where('id', $leave->id)
                        ->first();

                    if (strtolower(date('D', strtotime($leave->ldate))) == 'mon') {
                        if ($basicsalaryinfo->mondays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'tue') {
                        if ($basicsalaryinfo->tuesdays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'wed') {
                        if ($basicsalaryinfo->wednesdays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'thu') {
                        if ($basicsalaryinfo->thursdays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'fri') {
                        if ($basicsalaryinfo->fridays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'sat') {
                        // return date('D',strtotime($leavedatesperiod));
                        // return $basicsalaryinfo->saturdays;
                        if ($basicsalaryinfo->saturdays == 1 && $getpay->withpay == 1) {
                            // return 'asdsa';
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'sun') {
                        if ($basicsalaryinfo->sundays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if ($leave->dayshift == 0) {
                        $leave->leave_type = '' . $leave->leave_type;
                        $leave->amount = round($leave->amount, 2);
                    } elseif ($leave->dayshift == 1) {
                        $leave->leave_type = 'AM - ' . $leave->leave_type;
                        $leave->amount = round(($leave->amount / 2), 2);
                    } elseif ($leave->dayshift == 2) {
                        $leave->leave_type = 'PM - ' . $leave->leave_type;
                        $leave->amount = round(($leave->amount / 2), 2);
                    }

                }
            }

        } else if ($configured == 1) {
            $leavedetails = \App\Models\HR\HRSalaryDetails::getleavesapplied($request->get('employeeid'), $payrollperiod);

            if (count($leavedetails) > 0) {

                foreach ($leavedetails as $leave) {

                    $leave->amount = 0.00;

                    $getpay = DB::table('hr_leaves')
                        ->where('id', $leave->id)
                        ->first();

                    if (strtolower(date('D', strtotime($leave->ldate))) == 'mon') {
                        if ($basicsalaryinfo->mondays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'tue') {
                        if ($basicsalaryinfo->tuesdays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'wed') {
                        if ($basicsalaryinfo->wednesdays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'thu') {
                        if ($basicsalaryinfo->thursdays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'fri') {
                        if ($basicsalaryinfo->fridays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'sat') {
                        // return date('D',strtotime($leavedatesperiod));
                        // return $basicsalaryinfo->saturdays;
                        if ($basicsalaryinfo->saturdays == 1 && $getpay->withpay == 1) {
                            // return 'asdsa';
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if (strtolower(date('D', strtotime($leave->ldate))) == 'sun') {
                        if ($basicsalaryinfo->sundays == 1 && $getpay->withpay == 1) {
                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                            } else {
                                $leave->amount = $perdaysalary;
                            }
                        }
                    }
                    if ($leave->dayshift == 0) {
                        $leave->leave_type = '' . $leave->leave_type;
                        $leave->amount = round($leave->amount, 2);
                    } elseif ($leave->dayshift == 1) {
                        $leave->leave_type = 'AM - ' . $leave->leave_type;
                        $leave->amount = round(($leave->amount / 2), 2);
                    } elseif ($leave->dayshift == 2) {
                        $leave->leave_type = 'PM - ' . $leave->leave_type;
                        $leave->amount = round(($leave->amount / 2), 2);
                    }

                }
            }

        } else {
            $leavedetails = DB::table('hr_payrollv2historydetail')
                ->select('hr_payrollv2historydetail.amountpaid as amount', 'hr_payrollv2historydetail.description as leave_type', 'hr_payrollv2historydetail.leavedateid as ldateid', 'hr_payrollv2historydetail.employeeleaveid', 'hr_leaveempdetails.ldate', 'hr_leaveempdetails.dayshift')
                ->join('hr_leaveemployees', 'hr_payrollv2historydetail.employeeleaveid', 'hr_leaveemployees.id')
                ->join('hr_leaveempdetails', 'hr_payrollv2historydetail.leavedateid', '=', 'hr_leaveempdetails.id')
                ->where('hr_payrollv2historydetail.headerid', $payrollinfo->id)
                // ->where('particulartype', 6)
                ->where('hr_payrollv2historydetail.employeeid', $request->get('employeeid'))
                ->where('hr_payrollv2historydetail.deleted', 0)
                ->where('hr_payrollv2historydetail.leavedateid', '>', 0)
                ->get();
        }

        // return $leavedetails[1]->ldateid ?? collect($leavedetails[1]);
        if ($released == 0) {
            $filedovertimes = DB::table('employee_overtime')
                ->where('deleted', '0')
                ->where('overtimestatus', '1')
                ->where('payrolldone', '0')
                ->whereIn('datefrom', $dates)
                ->where('employeeid', $request->get('employeeid'))
                ->get();

            if (count($filedovertimes) > 0) {
                foreach ($filedovertimes as $filedovertime) {
                    $timefrom = strtotime($filedovertime->timefrom);
                    $timeto = strtotime($filedovertime->timeto);
                    $difference = round(abs($timeto - $timefrom) / 3600, 2);
                    $filedovertime->totalhours = $difference;
                    $filedovertime->amount = $difference * $perhour;
                }
            }
        } else {
            $filedovertimes = DB::table('hr_payrollv2historydetail')
                ->select('employee_overtime.*', 'hr_payrollv2historydetail.amountpaid as amount', 'hr_payrollv2historydetail.description as totalhours')
                ->join('employee_overtime', 'hr_payrollv2historydetail.particularid', 'employee_overtime.id')
                ->where('headerid', $payrollinfo->id)
                ->where('particulartype', 6)
                ->where('hr_payrollv2historydetail.deleted', 0)
                ->get();


            if (count($filedovertimes) > 0) {
                foreach ($filedovertimes as $filedovertime) {
                    $filedovertime->totalhours = (int) filter_var($filedovertime->totalhours, FILTER_SANITIZE_NUMBER_INT);
                }
            }
            // return collect($payrollinfo);
        }


        // return collect($basicsalaryinfo);
        // deduction_tardinessdetail
        // deduction_tardinessapplication
        // return collect($latecomputationdetails);
        // return $latecomputationdetails;
        // return $released;
        // return collect($attendance)->where('totalworkinghours','>',0)->pluck('totalworkinghours');
        // return collect($attendance)->pluck('totalworkinghours');
        // return $leavedetails;
        // Convert the stdClass objects to associative arrays
        $otherdeductions = json_decode(json_encode($otherdeductions), true);
        // Filter the $otherdeductions array based on the condition where totalotherdeductionpaid is less than amount
        $otherdeductions = array_filter($otherdeductions, function ($deduction) {
            return $deduction['totalotherdeductionpaid'] < $deduction['fullamount'] || $deduction['term'] == 0; //this return other deduction where term == 0 for forever deduction
            // return $deduction['totalotherdeductionpaid'] < $deduction['fullamount'] ; //this is original 
        });


        $dayswithattendance = []; // Initialize $dayswithattendance as an empty array
        // return collect($employeeinfo);
        if (!empty($dates)) {
            if ($employeeinfo->employmentstatus == 1 || $employeeinfo->employmentstatus == 2 || $employeeinfo->employmentstatus == 3 || $employeeinfo->employmentstatus == 4 || $employeeinfo->employmentstatus == null) {
                // if ($employeeinfo->employmentstatus) {
                // For HOLIDAY
                // get all the days where basic salary info is equal to 0 or get the rest day
                $restday = [];
                if ($basicsalaryinfo) {
                    if ($basicsalaryinfo->mondays == 0) {
                        $restday[] = 'Monday';
                    }
                    if ($basicsalaryinfo->tuesdays == 0) {
                        $restday[] = 'Tuesday';
                    }
                    if ($basicsalaryinfo->wednesdays == 0) {
                        $restday[] = 'Wednesday';
                    }
                    if ($basicsalaryinfo->thursdays == 0) {
                        $restday[] = 'Thursday';
                    }
                    if ($basicsalaryinfo->fridays == 0) {
                        $restday[] = 'Friday';
                    }
                    if ($basicsalaryinfo->saturdays == 0) {
                        $restday[] = 'Saturday';
                    }
                    if ($basicsalaryinfo->sundays == 0) {
                        $restday[] = 'Sunday';
                    }
                }
                $datesAbsents = [];
                $datesAbsences = [];
                $datesPresent = [];

                foreach ($attendance as $attendanceData) {
                    $date = $attendanceData->date;

                    if ($attendanceData->status == 1) {
                        $datesPresent[] = $date;
                    } else {
                        $datesAbsents[] = ['date' => $date];
                        $datesAbsences[] = $date;
                    }
                }
                // get the missing dates from payroll period start and end, usually 15days
                $startDate = $payrollperiod->datefrom;
                $endDate = $payrollperiod->dateto;
                $datesInRange = [];

                $currentDate = strtotime($startDate);
                $endTimestamp = strtotime($endDate);

                while ($currentDate <= $endTimestamp) {
                    $date = date("Y-m-d", $currentDate);
                    $dayName = date("l", $currentDate); // Get the full day name
                    $datesInRange[$date] = $dayName; // Store in an associative array
                    $currentDate = strtotime("+1 day", $currentDate);
                }

                // this is the result together with the days name
                $missingDates = array_diff_key($datesInRange, array_flip($datesPresent));


                // compare missing dates if nag match sa gi return nga rest day 
                $matchingMissingDates = [];

                foreach ($missingDates as $date => $dayName) {
                    if (in_array($dayName, $restday)) {
                        $matchingMissingDates[$date] = $dayName;
                    }
                }

                $matchingDates = array_keys($matchingMissingDates);
                $attendanceforrestday = \App\Models\HR\HREmployeeAttendance::gethours($matchingDates, $employeeinfo->id, $taphistory, $hr_attendance, $departmentid);

                $timebracketsrestday = array();

                if (count($attendanceforrestday) > 0) {
                    foreach ($attendanceforrestday as $eachdate) {
                        $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date, $employeeinfo, ($basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday), $basicsalaryinfo, $taphistory, $hr_attendance);
                        $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                        $eachdate->lateminutes = $latedeductiondetail->lateminutes;
                        $eachdate->restday = 1;

                        if (count($latedeductiondetail->brackets) > 0) {
                            foreach ($latedeductiondetail->brackets as $eachbracket) {
                                array_push($timebracketsrestday, $eachbracket);
                            }
                        }
                        $eachdate->amountdeduct = 0;
                    }
                }

                // return $attendance;

                $rtardiness_computations = DB::table('hr_tardinesscomp')
                    ->where('hr_tardinesscomp.deleted', '0')
                    ->where('hr_tardinesscomp.isactive', '1')
                    ->get();

                $rtardinessamount = 0;
                $rlateduration = 0;
                $rdurationtype = 0;
                $rtardinessallowance = 0;
                $rtardinessallowance = 0;

                if (count($attendanceforrestday) > 0 && count($rtardiness_computations) > 0) {
                    foreach ($attendanceforrestday as $eachatt) {
                        $eachatt->lateamminutes = ($eachatt->lateamhours * 60);
                        $eachatt->latepmminutes = ($eachatt->latepmhours * 60);
                        $eachatt->lateminutes = ($eachatt->latehours * 60);

                        $eachcomputations = collect($rtardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                        $fromcomputations = collect($rtardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                        $eachcomputations = $eachcomputations->merge($fromcomputations);
                        $eachcomputations = $eachcomputations->unique();

                        if ($basicsalaryinfo->attendancebased == 1) {
                            if (count($eachcomputations) > 0) {
                                foreach ($eachcomputations as $eachcomputation) {
                                    if ($eachcomputation->latetimetype == 1) {
                                        if ($eachcomputation->deducttype == 1) {
                                            $eachatt->amountdeduct = $eachcomputation->amount;
                                        } else {
                                            $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                        }

                                    } else {
                                        $computehours = ($eachatt->lateminutes / 60);
                                        if ($eachcomputation->deducttype == 1) {
                                            $eachatt->amountdeduct = $eachcomputation->amount;
                                        } else {
                                            $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                        }
                                    }
                                }
                            }
                        } else {
                            $eachatt->amountdeduct = 0;
                        }
                    }
                }

                $attendanceArray = json_decode($attendanceforrestday, true);
                $filteredAttendance = array_filter($attendanceArray, function ($record) {
                    return $record['status'] == 1;
                });

                $datesArrayrestdays = [];

                foreach ($filteredAttendance as $record) {
                    $datesArrayrestdays[] = $record['date'];
                }

                $dayswithattendance = collect(array());
                $dayswithattendance = $dayswithattendance->merge($datesPresent);
                $dayswithattendance = $dayswithattendance->merge($datesArrayrestdays);

                $holidays = DB::table('school_calendar')
                    ->select(
                        'school_calendar.*',
                        'schoolcaltype.typename',
                        'hr_holidaytype.ifwork',
                        'hr_holidaytype.ifnotwork',
                        'hr_holidaytype.restdayifwork',
                        'hr_holidaytype.restdayifnotwork',
                        'hr_holidaytype.description'
                    )
                    ->leftJoin('schoolcaltype', 'school_calendar.holiday', '=', 'schoolcaltype.id')
                    ->leftJoin('hr_holidaytype', 'school_calendar.holidaytype', '=', 'hr_holidaytype.id')
                    ->where('schoolcaltype.type', 1)
                    ->where('school_calendar.deleted', 0)
                    ->get();

                $holidaydates = [];
                foreach ($holidays as $holiday) {
                    $startDate = new \DateTime($holiday->start);
                    $endDate = new \DateTime($holiday->end);

                    $interval = new \DateInterval('P1D'); // 1 day interval
                    $dateRange = new \DatePeriod($startDate, $interval, $endDate);

                    foreach ($dateRange as $date) {
                        // $dates[] =  $date->format('Y-m-d');
                        $holidaydates[] = [
                            'date' => $date->format('Y-m-d'),
                            'type' => $holiday->description,
                            'holidaytype' => $holiday->holidaytype,
                            'holidayname' => $holiday->title
                        ];

                    }
                }

                $employeecustomsched = DB::table('employee_customtimesched')
                    ->where('employeeid', $employeeinfo->id)
                    ->where('shiftid', '!=', null)
                    ->where('createdby', '!=', null)
                    ->where('deleted', 0)
                    ->first();

                // return collect($employeecustomsched);
                // if ($employeecustomsched) {

                //     $amin = new DateTime($employeecustomsched->amin);
                //     $amout = new DateTime($employeecustomsched->amout);
                //     $pmin = new DateTime($employeecustomsched->pmin);
                //     $pmout = new DateTime($employeecustomsched->pmout);

                //     // Calculate morning working hours
                //     $morningWorkingHours = $amin->diff($amout);
                //     $amhours = $morningWorkingHours->h;

                //     // Calculate afternoon working hours
                //     $afternoonWorkingHours = $pmin->diff($pmout);
                //     $pmhours = $afternoonWorkingHours->h;
                //     $totalworkinghours = $amhours + $pmhours;



                //     // return $holidaydates;
                //     if (count($holidaydates) > 0) {
                //         foreach ($attendance as $att) {
                //             $att->holiday = 0;
                //             $att->holidayname = "";

                //             foreach ($holidaydates as $holidaydate) {
                //                 // if ($att->date == $holidaydate['date'] && $att->status == 2 && ($holidaydate['holidaytype'] == 1 || $holidaydate['type'] == 'Regular Holiday')) {
                //                 if ($att->date == $holidaydate['date'] && $att->status == 2) {
                //                     $att->amtimein = $employeecustomsched->amin;
                //                     $att->amtimeout = $employeecustomsched->amout;
                //                     $att->pmtimein = $employeecustomsched->pmin;
                //                     $att->pmtimeout = $employeecustomsched->pmout;
                //                     $att->timeinam = $employeecustomsched->amin;
                //                     $att->timeoutam = $employeecustomsched->amout;
                //                     $att->timeinpm = $employeecustomsched->pmin;
                //                     $att->timeoutpm = $employeecustomsched->pmout;
                //                     $att->amin = $employeecustomsched->amin;
                //                     $att->amout = $employeecustomsched->amout;
                //                     $att->pmin = $employeecustomsched->pmin;
                //                     $att->pmout = $employeecustomsched->pmout;
                //                     $att->status = 1;
                //                     $att->totalworkinghours = $totalworkinghours;
                //                     $att->holiday = 1;
                //                     $att->holidayname = $holidaydate['holidayname'];

                //                 } else if($att->date == $holidaydate['date'] && $att->status == 1){
                //                     $att->amtimein = $employeecustomsched->amin;
                //                     $att->amtimeout = $employeecustomsched->amout;
                //                     $att->pmtimein = $employeecustomsched->pmin;
                //                     $att->pmtimeout = $employeecustomsched->pmout;
                //                     $att->timeinam = $employeecustomsched->amin;
                //                     $att->timeoutam = $employeecustomsched->amout;
                //                     $att->timeinpm = $employeecustomsched->pmin;
                //                     $att->timeoutpm = $employeecustomsched->pmout;
                //                     $att->amin = $employeecustomsched->amin;
                //                     $att->amout = $employeecustomsched->amout;
                //                     $att->pmin = $employeecustomsched->pmin;
                //                     $att->pmout = $employeecustomsched->pmout;
                //                     $att->status = 1;
                //                     $att->totalworkinghours = $totalworkinghours;
                //                     $att->holiday = 1;
                //                     $att->holidayname = $holidaydate['holidayname'];


                //                     $att->lateminutes = 0;
                //                     $att->lateamminutes = 0;
                //                     $att->latepmminutes = 0;
                //                     $att->lateamhours = 0;
                //                     $att->latepmhours = 0;
                //                     $att->latehours = 0;
                //                     $att->undertimeamhours = 0;
                //                     $att->undertimepmhours = 0;

                //                 }
                //             }
                //         }
                //     }
                // }
                // dd($attendance);

                // if ($payrollperiod) {
                //     $datefrom = $payrollperiod->datefrom;
                //     $dateto = $payrollperiod->dateto;
                //     $holidays_within_range = [];


                //     foreach ($holidays as $holiday) {
                //         $start_date = strtotime($holiday->start); 
                //         $end_date = strtotime($holiday->end); 

                //         if ($start_date <= strtotime($dateto) && $end_date >= strtotime($datefrom)) {
                //             // Check if the start date of the holiday matches any date in $datesArrayrestdays
                //             if (in_array(date("Y-m-d", $start_date), $datesArrayrestdays)) {
                //                 // If it matches, set attendance to 'present'
                //                 $holiday->attendance = 'present';
                //             } else {
                //                 // If it doesn't match, set attendance to 'not present'
                //                 $holiday->attendance = 'not present';
                //             }

                //             // Calculate holiday pay percentage
                //             $duration = ceil(($end_date - $start_date) / (60 * 60 * 24)); // Calculate duration in days

                //             // Calculate the amount per day
                //             // $amountPerDay = floor($basicsalaryinfo->amountperday * 100) / 100;
                //             $amountPerDay = $basicsalaryinfo->amountperday;
                //             $holiday->matching = "";
                //             $holiday->hoursperday = $basicsalaryinfo->hoursperday;
                //             $holiday->duration = $duration; // Add duration as a property
                //             $holiday->amountPerDay = $amountPerDay; // Add amount per day as a property
                //             $holiday->holidaypay = $amountPerDay;

                //             $holidays_within_range[] = $holiday;
                //         }
                //     }
                // }

                // return $amountPerDay;
                // return $uniqueDates;
                // return $datesAbsents;
                // return $dayswithattendance;
                // return $holidays_within_range;
                // return $datesArrayrestdays;
                // foreach ($dayswithattendance as $datesp) {
                //     if (in_array($datesp, $datesArrayrestdays)) {
                //         $datepresentstart = strtotime($datesp); // Access the date directly since $datesp is a string
                //         foreach ($holidays_within_range as $holiday_within) { // Use &$holiday_within to modify the original array
                //             $start = strtotime($holiday_within->start);
                //             if ($start == $datepresentstart) { // Use == for comparison, not =
                //                 $holiday_within->matching = "Matching"; // Add a property to indicate the match
                //                 $holiday_within->attendance = 'present';
                //                 $holidaypercentage = $holiday_within->restdayifwork / 100;
                //                 $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
                //                 $holidayperday =  $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
                //                 $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                //                 $holidaywithdurationpay = $holidaywithdurationpay + $holiday_within->amountPerDay;
                //                 $holiday_within->holidaypay = round($holidaywithdurationpay, 2); 
                //             }
                //         }
                //     }
                //      else {
                //         $datepresentstart = strtotime($datesp); // Access the date directly since $datesp is a string
                //         foreach ($holidays_within_range as $holiday_within) { // Use &$holiday_within to modify the original array
                //             $start = strtotime($holiday_within->start);
                //             if ($start == $datepresentstart) { // Use == for comparison, not =
                //                 $holiday_within->matching = "Matching"; // Add a property to indicate the match
                //                 $holiday_within->attendance = 'present';
                //                 $holidaypercentage = $holiday_within->ifwork / 100;

                //                 $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;

                //                 $holidayperday =  $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;

                //                 $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                //                 // $holidaywithdurationpay = $holiday_within->amountPerDay * $holiday_within->duration;
                //                 // $holiday_within->holidaypay = number_format($holidaywithdurationpay - .005, 2);
                //                 $holiday_within->holidaypay = floor($holidaywithdurationpay * 100) / 100 ;

                //             }
                //         }
                //     }
                // }

                // foreach ($datesAbsences as $datesp) {
                // 	$dateabsentstart = strtotime($datesp);
                // 	foreach ($holidays_within_range as $holiday_within) {
                //         if ($holiday_within->ifnotwork > 0) {
                //             $start = strtotime($holiday_within->start);
                //             if ($start == $dateabsentstart) { 
                //                 $holiday_within->matching = "Matching";
                //                 $holidaypercentage = $holiday_within->ifwork / 100;
                //                 $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
                //                 $holidayperday =  $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
                //                 $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                //                 $holiday_within->holidaypay = floor($holidaywithdurationpay * 100) / 100;
                //             }
                //         }

                // 	}
                // }


                // Get the total holiday payment
                // $totalPresentHolidaypay = 0; // Initialize the counter


                // foreach ($holidays_within_range as $holidaypayments) {
                //     if ($holidaypayments->matching == 'Matching') {
                //         $totalPresentHolidaypay += $holidaypayments->holidaypay; // Increment the counter
                //     }

                // }
                // return $totalPresentHolidaypay;
                // foreach ($standardallowances as &$sallowancestandard) {

                //     if ($sallowancestandard->baseonattendance == 1) {
                //         $amountPerDay = $sallowancestandard->amountperday;
                //         $totalDaysAmount = $amountPerDay * ( count($dayswithattendance) + $saturdayCount);
                //         $sallowancestandard->totalDaysAmount = $totalDaysAmount;
                //         // $sallowancestandard->totalDayspresent = count($dayswithattendance);
                //         $sallowancestandard->totalDayspresent = collect($attendance)->where('totalworkinghours','>',0)->count() + $saturdayCount;
                //     } else {
                //         $sallowancestandard->totalDaysAmount = 0;
                //     }
                // }
                // unset($sallowancestandard); // Unset the reference to the last element

                // $holidays_within_range = json_decode(json_encode($holidays_within_range), true);
                // return $holidays_within_range;
                // Filter out items with attendance "not present"
                // $filtered_holidays = array_filter($holidays_within_range, function ($holiday) {
                //     return $holiday["attendance"] !== "not present" || $holiday["holidaytype"] === 1 || $holiday["description"] === "Regular Holiday";
                // });
                // $filtered_holidays = $holidays_within_range;

            } else {
                foreach ($standardallowances as &$sallowancestandard) {

                    if ($sallowancestandard->baseonattendance == 1) {
                        $amountPerDay = $sallowancestandard->amountperday;
                        $totalDaysAmount = $amountPerDay * (count($dayswithattendance) + $saturdayCount);
                        $sallowancestandard->totalDaysAmount = $totalDaysAmount;

                        //$sallowancestandard->totalDayspresent = count($dayswithattendance);
                        $sallowancestandard->totalDayspresent = collect($attendance)->where('totalworkinghours', '>', 0)->count() + $saturdayCount;
                    } else {
                        $sallowancestandard->totalDaysAmount = 0;
                    }
                }
                unset($sallowancestandard); // Unset the reference to the last element
            }
        }
        foreach ($attendance as $item) {
            $item->exceed8hours = $item->totalworkinghours >= 8 ? 1 : 0;
        }

        // Now you can safely use count() on $dayswithattendance
        $totaldayspresent = count($dayswithattendance);



        $totalLateHours = 0;
        $totalUndertimeHours = 0;
        $totalworkinghoursrender = 0;
        $flexihours = 0;
        $flexihoursundertime = 0;

        foreach ($attendance as $entry) {
            if ($entry->totalworkinghours != 0 && $entry->totalworkinghours !== null && $entry->amin !== null && $entry->pmout !== null) {
                // if ($entry->totalworkinghours != 0 && $entry->totalworkinghours !== null) {
                // return $entry->date;
                $totalLateHours = $entry->latehours;
                $totalUndertimeHours = $entry->undertimehours;
                $totalWorkingHoursRender = 8 - ($totalLateHours + $totalUndertimeHours);
                $entry->totalworkinghoursrender = $totalWorkingHoursRender;

                $flexihours = $entry->totalworkinghoursflexi;
                $flexihoursundertime = 8 - $flexihours;

                if ($flexihours > 8) {
                    $entry->flexihours = 8;
                } else {
                    // $entry->flexihours = number_format($flexihours - .005, 2);
                    $entry->flexihours = $flexihours;
                }
                if ($flexihoursundertime < 0) {
                    $entry->flexihoursundertime = 0;
                } else {
                    $entry->flexihoursundertime = $flexihoursundertime;
                }
            } else {
                $entry->totalworkinghoursrender = 0;
                $entry->flexihours = 0;
                $entry->flexihoursundertime = 0;
            }
        }

        // return $attendance;

        //check if this employee late activate per department
        $tardinessperdepactivated = 0;
        $employeedepartmentid = collect($employeeinfo->departmentid);
        $countdep = count($employeedepartmentid);

        if ($employeeinfo->departmentid) {
            $tardinessbaseonattendance = DB::table('hr_tardinesscomp')
                ->where('hr_tardinesscomp.deleted', '0')
                ->where('departmentid', $employeeinfo->departmentid)
                ->where('baseonattendance', 0)
                ->first();
            if ($tardinessbaseonattendance) {
                if ($tardinessbaseonattendance->isactive == 1) {
                    $employeeinfo->tardinessperdepactivated = 1;
                } else {
                    $employeeinfo->tardinessperdepactivated = 0;
                }
            } else {
                $employeeinfo->tardinessperdepactivated = 0;
            }

        } else {
            $employeeinfo->tardinessperdepactivated = 0;
        }

        $resultearnings = [];
        $addedparticularearndeductions = DB::table('hr_payrollv2additionalearndeduct')
            // ->where('payrollid', $payrollperiod->id)
            ->where('employeeid', $request->get('employeeid'))
            ->where('deleted', '0')
            ->get();

        // return $addedparticularearndeductions;
        // Convert the object to an array
        // $addedparticularearndeductions = json_decode(json_encode($addedparticularearndeductions), true);
        if ($addedparticularearndeductions) {
            foreach ($addedparticularearndeductions as $addedparticularearndeduction) {
                $foundMatch = false;

                foreach ($addedparticulars as $addedparticular) {
                    if (
                        $addedparticular->description == $addedparticularearndeduction->description &&
                        $addedparticular->type == $addedparticularearndeduction->type
                        // $addedparticular->amount == $addedparticularearndeduction->amount
                    ) {
                        $foundMatch = true;
                        break;
                    }
                }

                if (!$foundMatch) {
                    $resultearnings[] = $addedparticularearndeduction;
                }
            }
        }

        // return $addedparticularearndeductions;
        // return $resultearnings;



        // for halfday saturday

        // $employeecustomsched = DB::table('employee_customtimesched')
        //     ->where('employeeid', $employeeinfo->id)
        //     ->where('shiftid', '!=', null)
        //     ->where('createdby', '!=', null)
        //     ->where('deleted', 0)
        //     ->first();

        // return collect($employeecustomsched);


        // if ($employeecustomsched) {

        //     if ($basicsalaryinfo) {
        //         if ($basicsalaryinfo->halfdaysat == 1) {
        //             foreach ($attendance as $attt) {
        //                 if ($attt->day == 'Saturday') {

        //                     $attt->amtimein = $employeecustomsched->amin;
        //                     $attt->amtimeout = $employeecustomsched->amout;
        //                     $attt->timeinam = $employeecustomsched->amin;
        //                     $attt->timeoutam = $employeecustomsched->amout;
        //                     $attt->amin = $employeecustomsched->amin;
        //                     $attt->amout = $employeecustomsched->amout;
        //                     $attt->status = 1;
        //                     $attt->totalworkinghours = $amhours;

        //                     $attt->lateamminutes = 0;
        //                     $attt->lateamhours = 0;
        //                     $attt->undertimeamhours = 0;
        //                 }
        //             }
        //         } else if($basicsalaryinfo->halfdaysat == 2){
        //             foreach ($attendance as $attt) {
        //                 if ($attt->day == 'Saturday') {
        //                     $attt->pmtimein = $employeecustomsched->pmin;
        //                     $attt->pmtimeout = $employeecustomsched->pmout;
        //                     $attt->timeinpm = $employeecustomsched->pmin;
        //                     $attt->timeoutpm = $employeecustomsched->pmout;
        //                     $attt->pmin = $employeecustomsched->pmin;
        //                     $attt->pmout = $employeecustomsched->pmout;
        //                     $attt->status = 1;

        //                     if ($attt->amin == null || $attt->amout == null ) {
        //                         $attt->totalworkinghours = $pmhours;
        //                     } else {
        //                         $attt->totalworkinghours = $pmhours + ($amhours - $attt->lateamhours);

        //                     }

        //                     $attt->latepmminutes = 0;
        //                     $attt->latepmhours = 0;
        //                     $attt->undertimepmhours = 0;


        //                 }

        //             }

        //         }
        //     } 

        // }
        $employeecustomsched = DB::table('employee_customtimesched')
            ->where('employeeid', $employeeinfo->id)
            ->where('shiftid', '!=', null)
            ->where('createdby', '!=', null)
            ->where('deleted', 0)
            ->first();

        if ($employeecustomsched) {

            // $amin = new DateTime($employeecustomsched->amin);
            // $amout = new DateTime($employeecustomsched->amout);
            // $pmin = new DateTime($employeecustomsched->pmin);
            // $pmout = new DateTime($employeecustomsched->pmout);

            // Calculate morning working hours
            // $morningWorkingHours = $amin->diff($amout);
            // $amhours = $morningWorkingHours->h + ($morningWorkingHours->i / 60); // Convert minutes to fraction of an hour

            // Calculate afternoon working hours
            // $afternoonWorkingHours = $pmin->diff($pmout);
            // $pmhours = $afternoonWorkingHours->h + ($afternoonWorkingHours->i / 60); // Convert minutes to fraction of an hour

            // Calculate total working hours
            // $totalworkinghours = $amhours + $pmhours;

            // return collect($basicsalaryinfo);

            // return $attendance;
            // if ($basicsalaryinfo) {
            //     if ($basicsalaryinfo->halfdaysat == 1) {

            //         foreach ($attendance as $attt) {

            //             if ($attt->pmin != null || $attt->pmout != null) {
            //                 if ($attt->day == 'Saturday' && $attt->holiday == 0) {

            //                     $attt->amtimein = $employeecustomsched->amin;
            //                     $attt->amtimeout = $employeecustomsched->amout;
            //                     $attt->timeinam = $employeecustomsched->amin;
            //                     $attt->timeoutam = $employeecustomsched->amout;
            //                     $attt->amin = $employeecustomsched->amin;
            //                     $attt->amout = $employeecustomsched->amout;
            //                     $attt->status = 1;


            //                     if ($attt->pmin == null || $attt->pmout == null ) {
            //                         $attt->totalworkinghours = $amhours;
            //                         $attt->totalworkinghoursrender = $amhours;
            //                         $attt->undertimepmhours = $pmhours;

            //                     } else {
            //                         $attt->totalworkinghours = $amhours + ($amhours - $attt->latepmhours);
            //                         $attt->totalworkinghoursrender = $amhours + ($amhours - $attt->latepmhours);
            //                         $attt->lateamminutes = 0;
            //                         $attt->lateamhours = 0;
            //                         $attt->undertimeamhours = 0;
            //                     }
            //                 }
            //             }

            //         }
            //     } else if($basicsalaryinfo->halfdaysat == 2){
            //         foreach ($attendance as $attt) {
            //             if ($attt->amin != null || $attt->amout != null) {
            //                 if ($attt->day == 'Saturday' && $attt->holiday == 0) {
            //                     $attt->pmtimein = $employeecustomsched->pmin;
            //                     $attt->pmtimeout = $employeecustomsched->pmout;
            //                     $attt->timeinpm = $employeecustomsched->pmin;
            //                     $attt->timeoutpm = $employeecustomsched->pmout;
            //                     $attt->pmin = $employeecustomsched->pmin;
            //                     $attt->pmout = $employeecustomsched->pmout;
            //                     $attt->status = 1;

            //                     if ($attt->amin == null || $attt->amout == null ) {
            //                         $attt->totalworkinghours = $pmhours;
            //                         $attt->totalworkinghoursrender = $pmhours;
            //                         $attt->lateamminutes = $pmhours * 60;
            //                         $attt->lateamhours = $pmhours;
            //                         // $attt->undertimeamhours = $pmhours;
            //                     } else {
            //                         $attt->totalworkinghours = $pmhours + ($amhours - $attt->lateamhours);
            //                         $attt->totalworkinghoursrender = $pmhours + ($amhours - $attt->lateamhours);
            //                         $attt->latepmminutes = 0;
            //                         $attt->latepmhours = 0;
            //                         $attt->undertimepmhours = 0;
            //                     }
            //                 }
            //             }

            //         }

            //     }
            // } 


            // if (count($leavedetails)>0) {
            //     foreach ($leavedetails as $leavesdetail) {
            //         if ($leavesdetail->halfday == 0) {
            //             $leavesdetail->daycoverd = 'Whole Day';
            //         } else if ($leavesdetail->halfday == 1) {
            //             $leavesdetail->daycoverd = 'Half Day AM';
            //         } else if ($leavesdetail->halfday == 2) {
            //             $leavesdetail->daycoverd = 'Half Day PM';
            //         } else {
            //             $leavesdetail->daycoverd = '';
            //         }
            //     }
            // }

            // if(count($attendance)>0){
            //     foreach ($attendance as $lognull) {
            //         $lognull->leavetype = '';
            //         $lognull->leavedaystatus = '';
            //         $lognull->daycoverd = '';
            //         $lognull->leaveremarks = '';

            //         if (count($leavedetails)>0) {

            //             foreach ($leavedetails as $employeeleavesapp) {
            //                 if ($employeeleavesapp->ldate == $lognull->date) {
            //                     $lognull->leavetype = $employeeleavesapp->leave_type;
            //                     $lognull->leavedaystatus = $employeeleavesapp->halfday;
            //                     $lognull->daycoverd = $employeeleavesapp->daycoverd;
            //                     $lognull->leaveremarks = $employeeleavesapp->remarks ?? '';

            //                     // if ($lognull->daycoverd == ) {
            //                     //     # code...
            //                     // }
            //                 }
            //             }
            //         }
            //     }

            // }

            // $attendance = collect($attendance)->where('date', '2024-10-15')->values();

            // foreach ($attendance as $att) {

            //     if ($att->status == 1) {

            //         if ($att->leavedaystatus === 0) {

            //             $att->amtimein = $employeecustomsched->amin;
            //             $att->amtimeout = $employeecustomsched->amout;
            //             $att->pmtimein = $employeecustomsched->pmin;
            //             $att->pmtimeout = $employeecustomsched->pmout;
            //             $att->timeinam = $employeecustomsched->amin;
            //             $att->timeoutam = $employeecustomsched->amout;
            //             $att->timeinpm = $employeecustomsched->pmin;
            //             $att->timeoutpm = $employeecustomsched->pmout;
            //             $att->amin = $employeecustomsched->amin;
            //             $att->amout = $employeecustomsched->amout;
            //             $att->pmin = $employeecustomsched->pmin;
            //             $att->pmout = $employeecustomsched->pmout;
            //             $att->status = 1;
            //             $att->totalworkinghours = $totalworkinghours;
            //             $att->totalworkinghoursrender = $totalworkinghours;
            //             $att->appliedleave = 1;
            //             // $att->leavetype = $leavedetail->leave_type;
            //             $att->latepmminutes = 0;
            //             $att->latepmhours = 0;
            //             $att->undertimepmhours = 0;
            //             $att->lateamminutes = 0;
            //             $att->lateamhours = 0;
            //             $att->undertimeamhours = 0;
            //             $att->latehours = 0;
            //             $att->appliedleave = 1;


            //         } else if($att->leavedaystatus == 1){

            //             //return floor(($att->totalworkinghours + $amhours) * 100) / 100;
            //             $att->amtimein = $employeecustomsched->amin;
            //             $att->amtimeout = $employeecustomsched->amout;
            //             $att->timeinam = $employeecustomsched->amin;
            //             $att->timeoutam = $employeecustomsched->amout;
            //             $att->amin = $employeecustomsched->amin;
            //             $att->amout = $employeecustomsched->amout;
            //             // $att->leavetype = $leavedetail->leave_type;
            //             // return collect($att->totalworkinghours);


            //             $att->totalworkinghours = floor(($att->totalworkinghours + $amhours) * 100) / 100;
            //             $att->totalworkinghoursrender = floor(($att->totalworkinghours) * 100) / 100;
            //             $att->totalworkinghoursflexi = floor(($att->totalworkinghours) * 100) / 100;
            //             $att->flexihours = floor(($att->totalworkinghours) * 100) / 100;


            //             if (($att->timeinpm != null || $att->pmtimein != null) && ($att->timeoutpm == null || $att->pmtimeout == null)) {
            //                 $att->latepmminutes = 0;
            //                 $att->latepmhours = 0;
            //                 $att->undertimepmhours = $pmhours - $att->latepmhours;
            //                 $att->undertimeminutes = ($pmhours -$att->latepmhours) * 60;
            //             }else {
            //                 $att->latepmminutes = $att->latepmhours * 60;
            //                 $att->latepmhours = $att->latepmhours;
            //                 $att->undertimeamhours = 0;
            //                 $att->lateamhours = 0;
            //                 $att->lateamminutes = 0;

            //             }

            //             // $att->appliedleave = 1;
            //             // return collect($att);

            //         } else if($att->leavedaystatus == 2){

            //             $att->pmtimein = $employeecustomsched->pmin;
            //             $att->pmtimeout = $employeecustomsched->pmout;
            //             $att->timeinpm = $employeecustomsched->pmin;
            //             $att->timeoutpm = $employeecustomsched->pmout;
            //             $att->pmin = $employeecustomsched->pmin;
            //             $att->pmout = $employeecustomsched->pmout;
            //             // $att->leavetype = $leavedetail->leave_type;
            //             $att->status = 1;
            //             // return $att->date;


            //             $att->totalworkinghours = floor(($att->totalworkinghours + $pmhours) * 100) / 100;
            //             // $att->totalworkinghours = floor(($att->totalworkinghoursrender + $pmhours) * 100) / 100;
            //             // $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $pmhours) * 100) / 100;
            //             // $att->totalworkinghoursflexi = floor(($att->totalworkinghoursflexi + $pmhours) * 100) / 100;
            //             // $att->flexihours = floor(($att->flexihours + $pmhours) * 100) / 100;
            //             $att->totalworkinghoursrender = floor(($att->totalworkinghours) * 100) / 100;
            //             $att->totalworkinghoursflexi = floor(($att->totalworkinghours) * 100) / 100;
            //             $att->flexihours = floor(($att->totalworkinghours) * 100) / 100;

            //             if (($att->timeinam == null || $att->amtimein == null) ) {
            //                 // return 'a';
            //                 $att->lateamminutes = $amhours * 60;
            //                 $att->lateamhours = $amhours;
            //                 $att->undertimepmhours = 0;
            //                 $att->undertimeminutes = 0;
            //                 $att->latepmminutes = 0;
            //                 $att->latepmhours = 0;
            //             }else {

            //                 $att->lateaminutes = $att->lateamhours * 60;
            //                 $att->lateamhours = $att->lateamhours;
            //                 $att->undertimepmhours = 0;

            //                 $att->latepmhours = 0;
            //                 $att->latepmminutes = 0;
            //             }
            //             $att->appliedleave = 1;
            //             // return collect($att);
            //         }
            //     } else {
            //         if ($att->leavedaystatus === 0) {
            //             $att->amtimein = $employeecustomsched->amin;
            //             $att->amtimeout = $employeecustomsched->amout;
            //             $att->pmtimein = $employeecustomsched->pmin;
            //             $att->pmtimeout = $employeecustomsched->pmout;
            //             $att->timeinam = $employeecustomsched->amin;
            //             $att->timeoutam = $employeecustomsched->amout;
            //             $att->timeinpm = $employeecustomsched->pmin;
            //             $att->timeoutpm = $employeecustomsched->pmout;
            //             $att->amin = $employeecustomsched->amin;
            //             $att->amout = $employeecustomsched->amout;
            //             $att->pmin = $employeecustomsched->pmin;
            //             $att->pmout = $employeecustomsched->pmout;
            //             $att->status = 1;
            //             $att->totalworkinghours = $totalworkinghours;
            //             $att->totalworkinghoursrender = $totalworkinghours;
            //             $att->appliedleave = 1;
            //             // $att->leavetype = $leavedetail->leave_type;
            //             $att->latepmminutes = 0;
            //             $att->latepmhours = 0;
            //             $att->undertimepmhours = 0;
            //             $att->lateamminutes = 0;
            //             $att->lateamhours = 0;
            //             $att->undertimeamhours = 0;
            //             $att->latehours = 0;
            //             $att->appliedleave = 1;


            //         } else if($att->leavedaystatus == 1){

            //             $att->amtimein = $employeecustomsched->amin;
            //             $att->amtimeout = $employeecustomsched->amout;
            //             $att->timeinam = $employeecustomsched->amin;
            //             $att->timeoutam = $employeecustomsched->amout;
            //             $att->amin = $employeecustomsched->amin;
            //             $att->amout = $employeecustomsched->amout;
            //             // $att->leavetype = $leavedetail->leave_type;
            //             $att->status = 1;



            //             $att->totalworkinghours = $amhours;
            //             $att->totalworkinghoursrender = $amhours;
            //             $att->totalworkinghoursflexi = $amhours;
            //             $att->flexihours = $amhours;
            //             $att->latepmminutes = 0;
            //             $att->latepmhours = 0;
            //             $att->undertimepmhours = $pmhours;
            //             $att->undertimeminutes = $pmhours * 60;
            //             // $att->appliedleave = 1;

            //             // return collect($att);

            //         } else if($att->leavedaystatus == 2){
            //             $att->pmtimein = $employeecustomsched->pmin;
            //             $att->pmtimeout = $employeecustomsched->pmout;
            //             $att->timeinpm = $employeecustomsched->pmin;
            //             $att->timeoutpm = $employeecustomsched->pmout;
            //             $att->pmin = $employeecustomsched->pmin;
            //             $att->pmout = $employeecustomsched->pmout;
            //             // $att->leavetype = $leavedetail->leave_type;
            //             $att->status = 1;
            //             $att->totalworkinghours = $pmhours;
            //             $att->totalworkinghours = $pmhours;
            //             // $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $att->latepmhours) * 100) / 100;
            //             $att->totalworkinghoursflexi =  $pmhours;
            //             $att->flexihours = $pmhours;

            //             $att->lateamminutes = $amhours * 60;
            //             $att->lateamhours = $amhours;
            //             $att->undertimeamhours = 0;

            //         }
            //     }

            // }

            // return collect($attendance);


            // foreach ($attendance as $attendancefinal) {

            //     $amlate = floatval($attendancefinal->lateamminutes);
            //     $pmlate = floatval($attendancefinal->latepmminutes);
            //     $amlatehours = floatval($attendancefinal->lateamhours);
            //     $pmlatehours = floatval($attendancefinal->latepmhours);
            //     $amundertime = floatval($attendancefinal->undertimeamhours);
            //     $pmundertime = floatval($attendancefinal->undertimepmhours);



            //     // return ($amhours - $amundertime) * 60;
            //     if ($attendancefinal->amin == null && $attendancefinal->amout != null) {
            //         $attendancefinal->lateamminutes = 0; // Initialize latepmminutes
            //         // $totalworkinghoursfinal = $totalworkinghours - $attendancefinal->totalworkinghours;
            //         $attendancefinal->lateamminutes = ($amhours - $amundertime) * 60;
            //         $attendancefinal->totalworkinghoursrender = $attendancefinal->totalworkinghours;
            //         // return $attendancefinal->lateamminutes;
            //     }
            //     else if ($attendancefinal->amin != null && $attendancefinal->amout == null) {

            //         $attendancefinal->undertimeamhours = 0; // Initialize lateamminutes
            //         $attendancefinal->lateamminutes = ($amhours - $amlatehours) * 60;

            //     } 
            //      else if ($attendancefinal->pmin == null && $attendancefinal->pmout != null) {

            //         $attendancefinal->latepmminutes = 0; // Initialize latepmminutes

            //         $attendancefinal->latepmminutes = ($pmhours - $pmundertime) * 60;

            //     // }  else if ($attendancefinal->pmin != null && $attendancefinal->pmout == null) {
            //     }  else if ($attendancefinal->pmin != null && $attendancefinal->pmout == null) {
            //         $attendancefinal->undertimepmhours = 0; // Initialize latepmminutes

            //         $attendancefinal->undertimepmhours = $pmhours - $pmlatehours;

            //     } 

            //     $attendancefinal->latehours = ($attendancefinal->lateamminutes + $attendancefinal->latepmminutes) / 60;

            //     // $employeecustomschedPmin = new DateTime($employeecustomsched->pmin);
            //     // $attendancefinalPmin = new DateTime($attendancefinal->pmin);

            //     // $pmTimeDiff = $employeecustomschedPmin->diff($attendancefinalPmin);
            //     // $pmHours = $pmTimeDiff->format('%h');
            //     // $pmMinutes = $pmTimeDiff->format('%i');
            //     // $totalMinutespmlate = ($pmHours * 60) + $pmMinutes;

            //     $attendancefinal->lateminutes = $attendancefinal->lateamminutes + $attendancefinal->latepmminutes;
            //     // $attendancefinal->lateminutes = $totalMinutespmlate + $attendancefinal->lateamminutes;
            //     // $attendancefinal->lateamminutes = $totalMinutesamlate;
            //     $attendancefinal->undertimehours = $attendancefinal->undertimeamhours + $attendancefinal->undertimepmhours;

            // }
            // return $attendance;


            // return 'masyaswasd';
            // This is to minus leave in the absent pinataka hahaha
            // return collect($basicsalaryinfo);

            //     if (count($attendance)>0) {

            //         foreach ($attendance as $time) {

            //             if ($time->totalworkinghours > 8) {
            //                 $time->totalworkinghours = $totalworkinghours;
            //                 $time->totalworkinghoursrender = $totalworkinghours;
            //             }
            //             $totalminuteswork = 0;
            //             $totalminuteslate = 0;
            //             $totalundertime = 0;
            //             $totalminutesundertime = 0;
            //             $totalHours = $time->totalworkinghours;
            //             if ($totalHours > 8) {
            //                 $totalHours = $totalworkinghours;
            //             } else {
            //                 $totalHours = $time->totalworkinghours;
            //             }
            //             // Calculate whole hours
            //             $hours = floor($totalHours);
            //             // Calculate remaining minutes
            //             $minutes = round(($totalHours - $hours) * 60);

            //             if ($hours != 0) {
            //                 $time->totalminuteswork = ($hours * 60) + $minutes;
            //             }

            //             // for late minutes
            //             $totalLateHours = $time->latehours;
            //             $hoursLate = floor($totalLateHours);
            //             $minuteslate = round(($totalLateHours - $hoursLate) * 60);
            //             $time->totalminuteslate = ($hoursLate * 60) + $minuteslate;
            //             $time->lateminutes = ($hoursLate * 60) + $minuteslate;

            //             // for undertime
            //             $totalundertime = $time->undertimeamhours + $time->undertimepmhours;
            //             $totalUndertimeHours = $totalundertime;
            //             $hoursUndertime = floor($totalUndertimeHours);
            //             $minutesundertime = round(($totalUndertimeHours - $hoursUndertime) * 60);
            //             $time->totalminutesundertime = ($hoursUndertime * 60) + $minutesundertime;
            //             $time->undertimeminutes = ($hoursUndertime * 60) + $minutesundertime;

            //         }
            //     }

        }

        $regulardload = 0;
        $overload = 0;
        $emergencyload = 0;
        $peroras = 0;

        $activedaterange = DB::table('employee_cldaterange')
            ->where('datefrom', $payrollperiod->datefrom)
            ->where('dateto', $payrollperiod->dateto)
            ->where('status', '1')
            ->where('deleted', '0')
            ->first();

        if ($basicsalaryinfo) {
            $peroras = $basicsalaryinfo->clsubjperhour;
        }

        if ($activedaterange) {
            $activeemployeetardy = DB::table('employee_cltardy')
                ->where('headerid', $activedaterange->id)
                ->where('employeeid', $request->get('employeeid'))
                ->where('payrollid', $payrollperiod->id)
                ->where('status', '1')
                ->where('datastatus', $activedaterange->setupstatus)
                ->where('deleted', '0')
                ->groupBy('type', 'totalminutes')
                ->get();


            foreach ($activeemployeetardy as $tardy) {
                if ($tardy->type == 'Regular Late' || $tardy->type == 'Regular Undertime' || $tardy->type == 'Regular Absent') {
                    $regulardload += $tardy->totalminutes;
                } else if ($tardy->type == 'Overload Late' || $tardy->type == 'Overload Undertime' || $tardy->type == 'Overload Absent') {
                    $overload += $tardy->totalminutes;
                } else if ($tardy->type == 'Emergency Late' || $tardy->type == 'Emergency Undertime' || $tardy->type == 'Emergency Absent') {
                    $emergencyload += $tardy->totalminutes;
                }
            }
        }

        if ($request->get('action') == 'modaldetails') {
            $data = [
                'leaves' => $leavedetails,
                'empstatus' => $empstatus,
                'timebrackets' => $timebrackets,
                'employeeinfo' => $employeeinfo,
                'latecomputationdetails' => $latecomputationdetails,
                'addedparticulars' => $addedparticulars,
                'addedparticularearndeductions' => $resultearnings ?? null,
                'dates' => $dates,
                'configured' => $configured,
                'released' => $released,
                'attendance' => $attendance,
                'basicsalaryinfo' => $basicsalaryinfo,
                'otherallowances' => $otherallowances,
                'otherdeductions' => $otherdeductions,
                'standardallowances' => $standardallowances,
                'standarddeductions' => $standarddeductions,
                'filedovertimes' => $filedovertimes,
                'payrollinfo' => $payrollinfo,
                'payrollperiod' => $payrollperiod,
                'holidays' => $filtered_holidays ?? null,
                'totalPresentHolidaypay' => $totalPresentHolidaypay ?? null,
                'employeeid' => $request->get('employeeid'),
                'totaldayspresent' => count($dayswithattendance) + $saturdayCount,
                'satCount' => $saturdayCount,
                'tardinessbaseonsalary' => $tardinessbaseonsalary ?? null,
                'tardiness_computations' => $tardiness_computations ?? null,
                'collegeregularload',
                $clregulardata ?? null,
                'collegeoverloadload',
                $cloverloaddata ?? null,
                'collegeparttimeload',
                $clparttimedata ?? null,
                'sumregularload',
                $sumClRegular ?? 0,
                'sumoverloadload',
                $sumClOverload ?? 0,
                'sumparttimeload',
                $sumClPartTime ?? 0,
                'regulardload',
                $regulardload ?? 0,
                'overload',
                $overload ?? 0,
                'emergencyload',
                $emergencyload ?? 0,
                'peroras',
                $peroras
            ];

            return $data;
        }

        return view('hr.payroll.v3.getsalaryinfo')
            ->with('leaves', $leavedetails)
            ->with('empstatus', $empstatus)
            ->with('timebrackets', $timebrackets)
            ->with('employeeinfo', $employeeinfo)
            ->with('latecomputationdetails', $latecomputationdetails)
            ->with('addedparticulars', $addedparticulars)
            ->with('addedparticularearndeductions', $resultearnings ?? null)
            ->with('dates', $dates)
            ->with('configured', $configured)
            ->with('released', $released)
            ->with('attendance', $attendance)
            ->with('basicsalaryinfo', $basicsalaryinfo)
            ->with('otherallowances', $otherallowances)
            ->with('otherdeductions', $otherdeductions)
            ->with('standardallowances', $standardallowances)
            ->with('standarddeductions', $standarddeductions)
            ->with('filedovertimes', $filedovertimes)
            ->with('payrollinfo', $payrollinfo)
            ->with('payrollperiod', $payrollperiod)
            ->with('holidays', $filtered_holidays ?? null)
            ->with('totalPresentHolidaypay', $totalPresentHolidaypay ?? null)
            ->with('employeeid', $request->get('employeeid'))
            ->with('totaldayspresent', count($dayswithattendance))
            ->with('saturdayCount', $saturdayCount ?? 0)
            ->with('tardinessbaseonsalary', $tardinessbaseonsalary ?? null)
            ->with('tardiness_computations', $tardiness_computations ?? null)
            ->with('collegeregularload', $clregulardata ?? null)
            ->with('collegeoverloadload', $cloverloaddata ?? null)
            ->with('collegeparttimeload', $clparttimedata ?? null)
            ->with('sumregularload', $sumClRegular ?? 0)
            ->with('sumoverloadload', $sumClOverload ?? 0)
            ->with('sumparttimeload', $sumClPartTime ?? 0)
            ->with('regulardload', $regulardload ?? 0)
            ->with('overload', $overload ?? 0)
            ->with('emergencyload', $emergencyload ?? 0)
            ->with('peroras', $peroras);
    }
    public function addedparticular(Request $request)
    {
        if ($request->get('action') == 'delete') {
            $employeeid = $request->get('employeeid');

            // Retrieve the active payroll
            $payrollid = DB::table('hr_payrollv2')
                ->where('deleted', 0)
                ->where('status', 1)
                ->first();

            if (!$payrollid) {
                return 0; // No active payroll found
            }

            // Retrieve payroll history
            $payrollhistory = DB::table('hr_payrollv2history')
                ->where('payrollid', $payrollid->id)
                ->where('employeeid', $employeeid)
                ->where('deleted', 0)
                ->first();

            if (!$payrollhistory) {
                return 0; // No payroll history found
            }

            // Retrieve the particular to delete
            $ifexist = DB::table('hr_payrollv2addparticular')
                ->where('id', $request->get('id'))
                ->where('deleted', 0)
                ->first();


            if ($ifexist) {
                // Adjust net salary
                if ($ifexist->type == 2) { // If type is 2, it's an addition
                    $changenet = $payrollhistory->netsalary + $ifexist->amount;
                    $totaldeduction = $payrollhistory->totaldeduction - $ifexist->amount;
                } else { // Otherwise, it's a deduction
                    $changenet = $payrollhistory->netsalary - $ifexist->amount;
                    $totalearning = $payrollhistory->totalearning - $ifexist->amount;
                }

                // Update net salary in payroll history
                DB::table('hr_payrollv2history')
                    ->where('id', $payrollhistory->id)
                    ->where('deleted', 0)
                    ->update([
                        'netsalary' => $changenet,
                        'totalearning' => isset($totalearning) ? $totalearning : $payrollhistory->totalearning,
                        'totaldeduction' => isset($totaldeduction) ? $totaldeduction : $payrollhistory->totaldeduction
                    ]);

                // Insert the deleted particular into the deleted table
                DB::table('hr_payrollv2addparticular_deleted')
                    ->insert([
                        'payrollid' => $ifexist->payrollid,
                        'employeeid' => $ifexist->employeeid,
                        'additionalid' => $ifexist->additionalid,
                        'headerid' => $ifexist->id,
                        'description' => $ifexist->description,
                        'amount' => $ifexist->amount,
                        'type' => $ifexist->type,
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);

                DB::table('hr_payrollv2historydetail')
                    ->where('payrollid', $payrollid->id)
                    ->where('employeeid', $employeeid)
                    ->where('headerid', $ifexist->headerid)
                    ->where('description', $ifexist->description)
                    ->where('amountpaid', $ifexist->amount)
                    ->delete();

                // Delete the particular from the main table
                DB::table('hr_payrollv2addparticular')
                    ->where('id', $request->get('id'))
                    ->where('payrollid', $payrollid->id)
                    ->where('employeeid', $employeeid)
                    ->delete();



                // Update the additional earnings/deductions table
                DB::table('hr_payrollv2additionalearndeduct')
                    ->where('id', $ifexist->additionalid)
                    ->where('employeeid', $employeeid)
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);

                return 1;
            } else {
                return 0; // Particular not found
            }
        }
    }
    public function configuration(Request $request)
    {
        $employeeid = $request->get('employeeid');
        $payrollid = $request->get('payrollid');

        // return 'aaa';
        $particulars = json_decode($request->get('particulars'));
        $additionalparticulars = json_decode($request->get('additionalparticulars'));
        $lateminutes = $request->get('lateminutes');
        $undertimeminutes = $request->get('undertimeminutes');
        $totalworkedhours = $request->get('totalworkedhours');
        $amountperday = str_replace(',', '', $request->get('amountperday'));
        $totalearnings = str_replace(',', '', $request->get('totalearnings'));
        $totaldeductions = str_replace(',', '', $request->get('totaldeductions'));
        $amountperday = str_replace(',', '', $request->get('amountperday'));
        $tardinessamount = str_replace(',', '', $request->get('tardinessamount'));
        $amountabsent = str_replace(',', '', $request->get('amountabsent'));
        $amountlate = str_replace(',', '', $request->get('amountlate'));
        $amountundertime = str_replace(',', '', $request->get('amountundertime'));
        $amounttardyregular = str_replace(',', '', $request->get('amounttardyregular'));
        $amounttardyoverload = str_replace(',', '', $request->get('amounttardyoverload'));
        $amounttardyemergencyload = str_replace(',', '', $request->get('amounttardyemergencyload'));
        $netsalary = str_replace(',', '', $request->get('netsalary'));
        $monthlysalary = $request->get('monthlysalary');
        $regularloadamount = $request->get('regularloadamount');
        $overloadloadamount = $request->get('overloadloadamount');
        $parttimeloadamount = $request->get('parttimeloadamount');
        // $newsalary = str_replace( ',', '', $request->get('newsalary'));
        // return gettype($payrollid);/
        // return $additionalparticulars;


        // Filter the array to get only those with particulartype == 2
        $filteredParticularsstandarddeductions = array_filter($particulars, function ($particular) {
            return $particular->particulartype == 1;
        });
        $filteredParticularsotherdeductions = array_filter($particulars, function ($particular) {
            return $particular->particulartype == 2;
        });
        $filteredParticularsstandardallowances = array_filter($particulars, function ($particular) {
            return $particular->particulartype == 3;
        });
        // return $filteredParticularsstandardallowances;

        $leaves = array();
        if (count($particulars) > 0) {
            foreach ($particulars as $eachleave) {
                if (collect($eachleave)->contains('ldateid')) {
                    array_push($leaves, $eachleave);
                }
            }
        }
        // return $leaves;
        $particulars = collect($particulars)->where('particularid', '!=', '0')->values();
        // return $particulars;
        $checkhistoryifexists = DB::table('hr_payrollv2history')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $employeeid)
            ->where('deleted', '0')
            ->first();

        if ($tardinessamount == 'NaN') {
            $tardinessamount = 0;
            ;
        }
        if ($checkhistoryifexists) {
            DB::table('hr_payrollv2history')
                ->where('id', $checkhistoryifexists->id)
                ->update([
                    'dailyrate' => $amountperday,
                    'daysabsentamount' => $amountabsent,
                    'lateminutes' => $lateminutes,
                    'lateamount' => $amountlate,
                    'undertimeminutes' => $undertimeminutes,
                    'undertimeamount' => $amountundertime,
                    'regulartardyamount' => $amounttardyregular,
                    'overloadtardyamount' => $amounttardyoverload,
                    'emergencyloadtardyamount' => $amounttardyemergencyload,
                    'totalhoursworked' => $totalworkedhours,
                    'totalearning' => $totalearnings,
                    'totaldeduction' => $totaldeductions,
                    'amountperday' => $amountperday,
                    'presentdays' => $request->get('dayspresent'),
                    'absentdays' => $request->get('daysabsent'),
                    'basicsalaryamount' => str_replace(',', '', $request->get('basicsalary')),
                    'netsalary' => str_replace(',', '', $request->get('netsalary')),
                    'basicsalarytype' => $request->get('salarytype'),
                    'clregularloadamount' => $request->get('regularloadamount'),
                    'cloverloadloadamount' => $request->get('overloadloadamount'),
                    'clparttimeloadamount' => $request->get('parttimeloadamount'),
                    'monthlysalary' => $monthlysalary,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => date('Y-m-d H:i:s')
                ]);

            if (count($particulars) == 0) {
                DB::table('hr_payrollv2historydetail')
                    ->where('headerid', $checkhistoryifexists->id)
                    ->where('type', 0)
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                $allstandarddeductions = DB::table('hr_payrollv2historydetail')
                    ->where('payrollid', $payrollid)
                    ->where('employeeid', $employeeid)
                    ->where('particulartype', 1)
                    ->where('deleted', 0)
                    ->get();

                $allotherdeductions = DB::table('hr_payrollv2historydetail')
                    ->where('payrollid', $payrollid)
                    ->where('employeeid', $employeeid)
                    ->where('particulartype', 2)
                    ->where('deleted', 0)
                    ->get();

                $allstandardallowances = DB::table('hr_payrollv2historydetail')
                    ->where('payrollid', $payrollid)
                    ->where('employeeid', $employeeid)
                    ->where('particulartype', 3)
                    ->where('deleted', 0)
                    ->get();

                $allparts = DB::table('hr_payrollv2historydetail')
                    ->where('headerid', $checkhistoryifexists->id)
                    ->where('type', '0')
                    ->where('deleted', '0')
                    ->get();

                $notmatchingsdeductions = [];
                $notmatchingodeductions = [];
                $notmatchingsallowances = [];
                // return $allstandarddeductions;

                foreach ($allstandarddeductions as $allstandarddeduction) {
                    $matchFound = false;

                    foreach ($filteredParticularsstandarddeductions as $fpdeduction) {
                        if ($allstandarddeduction->particularid == $fpdeduction->particularid) {
                            $matchFound = true;
                            break;
                        }
                    }

                    if (!$matchFound) {
                        $notmatchingsdeductions[] = $allstandarddeduction;
                    }
                }

                foreach ($allotherdeductions as $allotherdeduction) {
                    $matchFound = false;

                    foreach ($filteredParticularsotherdeductions as $opdeduction) {
                        if ($allotherdeduction->particularid == $opdeduction->particularid) {
                            $matchFound = true;
                            break;
                        }
                    }

                    if (!$matchFound) {
                        $notmatchingodeductions[] = $allotherdeduction;
                    }
                }

                foreach ($allstandardallowances as $allstandardallowance) {
                    $matchFound = false;

                    foreach ($filteredParticularsstandardallowances as $saallowance) {
                        if ($allstandardallowance->particularid == $saallowance->particularid) {
                            $matchFound = true;
                            break;
                        }
                    }

                    if (!$matchFound) {
                        $notmatchingsallowances[] = $allstandardallowance;
                    }
                }

                // return $notmatchingsallowances;
                foreach ($notmatchingsdeductions as $notmatchingsdeduction) {
                    DB::table('hr_payrollv2historydetail')
                        ->where('id', $notmatchingsdeduction->id)
                        ->where('payrollid', $payrollid)
                        ->where('employeeid', $employeeid)
                        ->where('particulartype', 1)
                        ->where('deleted', 0)
                        ->update([
                            'paidstatus' => 0
                        ]);
                }
                foreach ($notmatchingodeductions as $notmatchingodeduction) {
                    DB::table('hr_payrollv2historydetail')
                        ->where('id', $notmatchingodeduction->id)
                        ->where('payrollid', $payrollid)
                        ->where('employeeid', $employeeid)
                        ->where('particulartype', 2)
                        ->where('deleted', 0)
                        ->update([
                            'paidstatus' => 0
                        ]);
                }
                foreach ($notmatchingsallowances as $notmatchingsallowance) {
                    DB::table('hr_payrollv2historydetail')
                        ->where('id', $notmatchingsallowance->id)
                        ->where('payrollid', $payrollid)
                        ->where('employeeid', $employeeid)
                        ->where('particulartype', 3)
                        ->where('deleted', 0)
                        ->update([
                            'paidstatus' => 0
                        ]);
                }

                if (count($allparts) == 0) {
                    // return 'llll';
                    foreach ($particulars as $particular) {
                        $type = 0;
                        // return 'asads';
                        // return $particular->paidstatus;
                        if ($particular->particularid) {
                            $type = $particular->particulartype;
                        }
                        $detailid = DB::table('hr_payrollv2historydetail')
                            ->insertGetId([
                                'payrollid' => $payrollid,
                                'employeeid' => $employeeid,
                                'headerid' => $checkhistoryifexists->id,
                                'description' => $particular->description,
                                'totalamount' => str_replace(',', '', $particular->totalamount),
                                'amountpaid' => str_replace(',', '', $particular->amountpaid),
                                'paymenttype' => $particular->paymenttype,
                                'particulartype' => $particular->particulartype,
                                'particularid' => $particular->particularid,
                                'totalpaidamount' => isset($particular->totalamountpaid) ? $particular->totalamountpaid : null,
                                'paidstatus' => isset($particular->particulartype) && ($particular->particulartype == 1 || $particular->particulartype == 2 || $particular->particulartype == 3) ? $particular->paidstatus : 0,
                                // 'deductionid'           => $particular->odid,
                                // 'deductionid'           => $particular->odid,
                                'deductionid' => isset($particular->odid) ? $particular->odid : 0, // Set default value 0 if odid is not set.
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);

                        $balance = $particular->totalamount - $particular->amountpaid;
                        if ($balance > 0.00) {

                            DB::table('hr_payrollv2balance')
                                ->insert([
                                    'payrollid' => $payrollid,
                                    'detailid' => $detailid,
                                    'balance' => str_replace(',', '', $balance),
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => date('Y-m-d H:i:s')
                                ]);
                        }

                        // return collect($particular);
                    }
                    // return 'bbbbbbbbbb';

                } else {
                    // return 'ccccccc';

                    // return 'mmm';
                    // return $particulars;
                    foreach ($particulars as $particular) {
                        // return $particular->particularid;
                        $type = 0;
                        if ($particular->particularid) {
                            $type = $particular->particulartype;
                        }

                        if (collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->count() > 0) {
                            // return 'aaaaaaaa';
                            DB::table('hr_payrollv2historydetail')
                                ->where('id', collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->first()->id)
                                ->update([
                                    'description' => $particular->description,
                                    'totalamount' => str_replace(',', '', $particular->totalamount),
                                    'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                    'totalpaidamount' => isset($particular->totalamountpaid) ? $particular->totalamountpaid : null,
                                    'paidstatus' => isset($particular->particulartype) && ($particular->particulartype == 1 || $particular->particulartype == 2 || $particular->particulartype == 3) ? $particular->paidstatus : 0,
                                    'amountpaid' => str_replace(',', '', $particular->amountpaid),
                                    'paymenttype' => $particular->paymenttype,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => date('Y-m-d H:i:s')
                                ]);

                            $checkbalanceifexists = DB::table('hr_payrollv2balance')
                                ->where('payrollid', $payrollid)
                                ->where('detailid', collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->first()->id)
                                ->where('deleted', '0')
                                ->first();

                            $balance = $particular->totalamount - $particular->amountpaid;

                            if ($balance > 0.00) {
                                if ($checkbalanceifexists) {
                                    DB::table('hr_payrollv2balance')
                                        ->where('id', $checkbalanceifexists->id)
                                        ->update([
                                            'balance' => str_replace(',', '', $balance),
                                            'updatedby' => auth()->user()->id,
                                            'updateddatetime' => date('Y-m-d H:i:s')
                                        ]);
                                } else {
                                    DB::table('hr_payrollv2balance')
                                        ->insert([
                                            'payrollid' => $payrollid,
                                            'detailid' => collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->first()->id,
                                            'balance' => str_replace(',', '', $balance),
                                            'createdby' => auth()->user()->id,
                                            'createddatetime' => date('Y-m-d H:i:s')
                                        ]);
                                }
                            } else {
                                if ($checkbalanceifexists) {
                                    DB::table('hr_payrollv2balance')
                                        ->where('id', $checkbalanceifexists->id)
                                        ->insert([
                                            'paid' => 1,
                                            'updatedby' => auth()->user()->id,
                                            'updateddatetime' => date('Y-m-d H:i:s')
                                        ]);
                                }
                            }

                        } else {
                            DB::table('hr_payrollv2historydetail')
                                // ->where('id', collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->first()->id)
                                ->insert([
                                    'payrollid' => $payrollid,
                                    'employeeid' => $employeeid,
                                    'headerid' => $checkhistoryifexists->id,
                                    'description' => $particular->description,
                                    'totalamount' => str_replace(',', '', $particular->totalamount),
                                    'amountpaid' => str_replace(',', '', $particular->amountpaid),
                                    'paymenttype' => $particular->paymenttype,
                                    'particulartype' => $particular->particulartype,
                                    'particularid' => $particular->particularid,
                                    'totalpaidamount' => isset($particular->totalamountpaid) ? $particular->totalamountpaid : null,
                                    'paidstatus' => isset($particular->particulartype) && ($particular->particulartype == 1 || $particular->particulartype == 2 || $particular->particulartype == 3) ? $particular->paidstatus : 0,
                                    'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                    'type' => ($particular->particulartype == 2 || $particular->particulartype == 3) ? 0 : $type,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => date('Y-m-d H:i:s')
                                ]);

                        }
                    }
                    // return 'dddddd';
                }
            }
            if (count($additionalparticulars) == 0) {
                // return 'aadad';
                DB::table('hr_payrollv2addparticular')
                    ->where('headerid', $checkhistoryifexists->id)
                    // ->where('type', '>','0')
                    ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);


            } else {
                // return 'bbb';

                $allparts = DB::table('hr_payrollv2addparticular')
                    ->where('headerid', $checkhistoryifexists->id)
                    ->where('deleted', '0')
                    ->get();

                if (count($allparts) == 0) {

                    foreach ($additionalparticulars as $addedparticular) {
                        $detailid = DB::table('hr_payrollv2addparticular')
                            ->insertGetId([
                                'payrollid' => $payrollid,
                                'employeeid' => $employeeid,
                                'headerid' => $checkhistoryifexists->id,
                                'description' => $addedparticular->description,
                                'amount' => str_replace(',', '', $addedparticular->amount),
                                'type' => $addedparticular->type,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s'),
                                'additionalid' => $addedparticular->dataid
                            ]);
                    }
                } else {
                    foreach ($additionalparticulars as $addedparticular) {


                        if ($addedparticular->id == 0) {
                            DB::table('hr_payrollv2addparticular')
                                ->insert([
                                    'payrollid' => $payrollid,
                                    'employeeid' => $employeeid,
                                    'headerid' => $checkhistoryifexists->id,
                                    'description' => $addedparticular->description,
                                    'amount' => str_replace(',', '', $addedparticular->amount),
                                    'type' => $addedparticular->type,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => date('Y-m-d H:i:s')
                                ]);
                        } else {

                            if (collect($allparts)->where('id', $addedparticular->id)->count() > 0) {
                                // return collect($allparts)->where('id', $addedparticular->id)->first()->id;

                                DB::table('hr_payrollv2addparticular')
                                    ->where('id', collect($allparts)->where('id', $addedparticular->id)->first()->id)
                                    ->update([
                                        'description' => $addedparticular->description,
                                        'amount' => str_replace(',', '', $addedparticular->amount),
                                        'type' => $addedparticular->type,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => date('Y-m-d H:i:s'),
                                        'additionalid' => $addedparticular->dataid
                                    ]);
                            } else {
                                // return 'vv';
                                DB::table('hr_payrollv2addparticular')
                                    ->insert([
                                        'payrollid' => $payrollid,
                                        'employeeid' => $employeeid,
                                        'headerid' => $checkhistoryifexists->id,
                                        'description' => $addedparticular->description,
                                        'amount' => str_replace(',', '', $addedparticular->amount),
                                        'type' => $addedparticular->type,
                                        'createdby' => auth()->user()->id,
                                        'createddatetime' => date('Y-m-d H:i:s'),
                                        'additionalid' => $addedparticular->dataid
                                    ]);
                            }
                        }
                    }
                }


            }
        } else {
            $headerid = DB::table('hr_payrollv2history')
                ->insertGetId([
                    'dailyrate' => $amountperday,
                    'payrollid' => $payrollid,
                    'employeeid' => $employeeid,
                    'lateminutes' => $lateminutes,
                    'lateamount' => $amountlate,
                    'undertimeminutes' => $undertimeminutes,
                    'undertimeamount' => $amountundertime,
                    'regulartardyamount' => $amounttardyregular,
                    'overloadtardyamount' => $amounttardyoverload,
                    'emergencyloadtardyamount' => $amounttardyemergencyload,
                    'totalhoursworked' => $totalworkedhours,
                    'totalearning' => $totalearnings,
                    'totaldeduction' => $totaldeductions,
                    'amountperday' => $amountperday,
                    'presentdays' => $request->get('dayspresent'),
                    'absentdays' => $request->get('daysabsent'),
                    'daysabsentamount' => $amountabsent,
                    'basicsalaryamount' => str_replace(',', '', $request->get('basicsalary')),
                    'netsalary' => str_replace(',', '', $request->get('netsalary')),
                    'basicsalarytype' => $request->get('salarytype'),
                    'monthlysalary' => $monthlysalary,
                    'clregularloadamount' => $request->get('regularloadamount'),
                    'cloverloadloadamount' => $request->get('overloadloadamount'),
                    'clparttimeloadamount' => $request->get('parttimeloadamount'),
                    'configured' => 1,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);
            if (count($leaves) > 0) {
                // foreach($leaves as $eachleave)
                // {
                //     DB::table('hr_payrollv2historydetail')
                //     ->insert([
                //         'payrollid'             => $payrollid,
                //         'employeeid'            => $employeeid,
                //         'headerid'              => $headerid,
                //         'description'           => $eachleave->description,
                //         'totalamount'           => str_replace( ',', '', $eachleave->totalamount),
                //         'amountpaid'           => str_replace( ',', '', $eachleave->amountpaid),
                //         'totalpaidamount'           => isset($particular->totalamountpaid) ? $particular->totalamountpaid : null,
                //         // 'paymenttype'           => $particular->paymenttype,
                //         // 'particulartype'           => $particular->particulartype,
                //         'days'                  => 1,
                //         'particularid'           => $eachleave->particularid,
                //         'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                //         'employeeleaveid'           => $eachleave->employeeleaveid ?? 0,
                //         'leavedateid'           => $eachleave->ldateid ?? 0,
                //         'createdby'             => auth()->user()->id,
                //         'createddatetime'       => date('Y-m-d H:i:s')
                //     ]);
                // }
                foreach ($leaves as $eachleave) {
                    DB::table('hr_payrollv2historydetail')
                        ->insert([
                            'payrollid' => $payrollid,
                            'employeeid' => $employeeid,
                            'headerid' => $headerid,
                            'description' => $eachleave->description,
                            'totalamount' => str_replace(',', '', $eachleave->totalamount),
                            'amountpaid' => str_replace(',', '', $eachleave->amountpaid),
                            'totalpaidamount' => isset($eachleave->totalamountpaid) ? $eachleave->totalamountpaid : null,
                            'days' => 1,
                            'particularid' => $eachleave->particularid,
                            'deductionid' => ($eachleave->particulartype == 2) ? $eachleave->dataid : null,
                            'employeeleaveid' => $eachleave->employeeleaveid ?? 0,
                            'leavedateid' => $eachleave->ldateid ?? 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);
                }
            }
            if (count($particulars) > 0) {
                foreach ($particulars as $particular) {
                    $detailid = DB::table('hr_payrollv2historydetail')
                        ->insertGetId([
                            'payrollid' => $payrollid,
                            'employeeid' => $employeeid,
                            'headerid' => $headerid,
                            'description' => $particular->description,
                            'totalamount' => str_replace(',', '', $particular->totalamount),
                            'amountpaid' => str_replace(',', '', $particular->amountpaid),
                            'paymenttype' => $particular->paymenttype,
                            'totalpaidamount' => isset($particular->totalamountpaid) ? $particular->totalamountpaid : null,
                            'particulartype' => $particular->particulartype,
                            'particularid' => $particular->particularid,
                            'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s'),
                            'paidstatus' => isset($particular->particulartype) && ($particular->particulartype == 1 || $particular->particulartype == 2 || $particular->particulartype == 3) ? $particular->paidstatus : 0

                        ]);

                    $balance = $particular->totalamount - $particular->amountpaid;
                    if ($balance > 0.00) {
                        DB::table('hr_payrollv2balance')
                            ->insert([
                                'payrollid' => $payrollid,
                                'detailid' => $detailid,
                                'balance' => str_replace(',', '', $balance),
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }
            if (count($additionalparticulars) > 0) {
                foreach ($additionalparticulars as $eachparticular) {
                    DB::table('hr_payrollv2addparticular')
                        ->insert([
                            'payrollid' => $payrollid,
                            'employeeid' => $employeeid,
                            'headerid' => $headerid,
                            'description' => $eachparticular->description,
                            'amount' => $eachparticular->amount,
                            'type' => $eachparticular->type,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s'),
                            'additionalid' => $eachparticular->dataid
                        ]);
                }
            }
        }
        return 1;
    }
    public function payrolldates(Request $request)
    {
        $salid = $request->get('salid');
        if ($request->get('action') != 'closepayroll' && $request->get('action') != 'getnumberofreleased') {
            $dates = explode(' - ', $request->get('dates'));
            $datefrom = date('Y-m-d', strtotime($dates[0]));
            $dateto = date('Y-m-d', strtotime($dates[1]));
        }

        if ($request->get('action') == 'update') {
            try {
                $checkifexists = DB::table('hr_payrollv2')
                    ->where('status', '1')
                    ->where('salarytypeid', $salid)
                    ->first();

                $checkifexistsid = DB::table('hr_payrollv2')
                    ->where('status', '1')
                    ->where('salarytypeid', $salid)
                    ->first()->id;

                if ($checkifexists) {

                    $sy = DB::table('sy')
                        ->where('isactive', 1)
                        ->first();

                    $semesterdateactive = DB::table('semester_date')
                        ->where('deleted', 0)
                        ->where('syid', $sy->id)
                        ->where('active', 1)
                        ->get();


                    $payrollperiod = DB::table('hr_payrollv2')
                        ->where('id', $checkifexistsid)
                        ->first();

                    // $empids = [77,240,111,219,186,256,242];
                    // $empids =[186];
                    // $empids = [8];
                    // $empids =[350];
                    //  $empids = [77,240,111,219,186,256];

                    $employees = DB::table('teacher')
                        ->select('teacher.*', 'employee_personalinfo.gender', 'utype', 'teacher.id as employeeid', 'employee_personalinfo.departmentid', 'employee_basicsalaryinfo.salarybasistype as salid')
                        ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                        ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                        ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
                        ->where('teacher.deleted', '0')
                        ->where('teacher.isactive', '1')
                        ->where('employee_basicsalaryinfo.salarybasistype', $request->get('salid'))
                        // ->whereIn('teacher.id', $empids)
                        ->orderBy('employeeid', 'ASC')
                        ->get();

                    // $employeeids = $employees->pluck('id')->toArray();
                    $basicsalaryinfos = DB::table('employee_basicsalaryinfo')
                        ->select('employee_basicsalaryinfo.*', 'employee_basistype.type as salarytype', 'employee_basistype.type as ratetype')
                        ->leftJoin('employee_basistype', 'employee_basicsalaryinfo.salarybasistype', '=', 'employee_basistype.id')
                        ->where('employee_basicsalaryinfo.deleted', '0')
                        ->where('employee_basistype.deleted', '0')
                        ->get();


                    // return dd($basicsalaryinfos);
                    foreach ($basicsalaryinfos as $basicsalaryinfo) {
                        if ($basicsalaryinfo->hoursperday == null || $basicsalaryinfo->hoursperday == 0) {
                            $basicsalaryinfo->hoursperday = 1;
                        }
                    }

                    $monthlypayroll_all = DB::table('hr_payrollv2')
                        ->select('hr_payrollv2history.*', 'hr_payrollv2.datefrom', 'hr_payrollv2.dateto', 'hr_payrollv2history.employeeid')
                        ->join('hr_payrollv2history', 'hr_payrollv2.id', '=', 'hr_payrollv2history.payrollid')
                        ->whereYear('hr_payrollv2.datefrom', date('Y', strtotime($payrollperiod->datefrom)))
                        ->whereMonth('hr_payrollv2.datefrom', date('m', strtotime($payrollperiod->datefrom)))
                        ->where('hr_payrollv2.deleted', '0')
                        ->where('hr_payrollv2history.deleted', '0')
                        ->get();

                    // return $monthlypayroll_all;

                    // if(!$basicsalaryinfo){
                    //     return view('hr.payroll.v3.getsalaryinfoempty');
                    // }
                    // if($basicsalaryinfo)
                    // {

                    $interval = new DateInterval('P1D');
                    $realEnd = new DateTime($payrollperiod->dateto);
                    $realEnd->add($interval);
                    $period = new DatePeriod(new DateTime($payrollperiod->datefrom), $interval, $realEnd);


                    $taphistory_all = DB::table('taphistory')
                        // ->where('tdate', $dates)
                        // ->where('studid', $employee->employeeid)
                        ->whereBetween('tdate', [$payrollperiod->datefrom, $payrollperiod->dateto])
                        ->where('utype', '!=', '7')
                        ->orderBy('ttime', 'asc')
                        ->where('deleted', '0')
                        ->get();


                    $hr_attendance_all = DB::table('hr_attendance')
                        // ->where('tdate', $dates)
                        // ->where('studid', $employee->employeeid)
                        ->whereBetween('tdate', [$payrollperiod->datefrom, $payrollperiod->dateto])
                        ->where('deleted', 0)
                        ->orderBy('ttime', 'asc')
                        ->get();

                    $departmentid_all = DB::table('teacher')
                        ->select(
                            'hr_departments.id as departmentid',
                            'teacher.id'
                        )
                        ->leftJoin('employee_personalinfo', 'teacher.id', 'employee_personalinfo.employeeid')
                        ->leftJoin('civilstatus', 'employee_personalinfo.maritalstatusid', 'civilstatus.civilstatus')
                        ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                        ->leftJoin('hr_departments', 'employee_personalinfo.departmentid', 'hr_departments.id')
                        ->get();

                    $holidays = DB::table('school_calendar')
                        ->select(
                            'school_calendar.*',
                            'schoolcaltype.typename',
                            'hr_holidaytype.ifwork',
                            'hr_holidaytype.ifnotwork',
                            'hr_holidaytype.restdayifwork',
                            'hr_holidaytype.restdayifnotwork',
                            'hr_holidaytype.description'
                        )
                        ->leftJoin('schoolcaltype', 'school_calendar.holiday', '=', 'schoolcaltype.id')
                        ->leftJoin('hr_holidaytype', 'school_calendar.holidaytype', '=', 'hr_holidaytype.id')
                        ->where('schoolcaltype.type', 1)
                        ->where('school_calendar.deleted', 0)
                        ->get();

                    $rtardiness_computations = DB::table('hr_tardinesscomp')
                        ->where('hr_tardinesscomp.deleted', '0')
                        ->where('hr_tardinesscomp.isactive', '1')
                        ->get();
                    $tardiness_computations = DB::table('hr_tardinesscomp')
                        ->where('hr_tardinesscomp.deleted', '0')
                        ->where('hr_tardinesscomp.isactive', '1')
                        ->get();
                    $tardinessbaseonsalary_all = DB::table('hr_tardinesscomp')
                        ->where('hr_tardinesscomp.deleted', '0')
                        // ->where('departmentid', $employeeinfo->departmentid)
                        ->where('baseonattendance', 1)
                        ->where('isactive', '1')
                        ->get();

                    $standardallowances_all = DB::table('allowance_standard')
                        ->select(
                            'allowance_standard.id',
                            'allowance_standard.baseonattendance',
                            'allowance_standard.amountperday',
                            'employee_allowancestandard.id as empallowanceid',
                            'allowance_standard.description',
                            'employee_allowancestandard.amount as eesamount',
                            'employee_allowancestandard.allowance_standardid',
                            'employee_allowancestandard.amountbaseonsalary',
                            'employee_allowancestandard.monday',
                            'employee_allowancestandard.tuesday',
                            'employee_allowancestandard.wednesday',
                            'employee_allowancestandard.thursday',
                            'employee_allowancestandard.friday',
                            'employee_allowancestandard.saturday',
                            'employee_allowancestandard.sunday',
                            'employee_allowancestandard.employeeid'
                        )
                        ->leftJoin('employee_allowancestandard', 'allowance_standard.id', '=', 'employee_allowancestandard.allowance_standardid')
                        // ->where('employee_allowancestandard.employeeid', $request->get('employeeid'))
                        ->where('employee_allowancestandard.status', '1')
                        ->where('allowance_standard.deleted', '0')
                        ->where('employee_allowancestandard.deleted', '0')
                        ->get();

                    $otherallowances_all = Db::table('employee_allowanceother')
                        ->select(
                            'employee_allowanceother.id',
                            'employee_allowanceother.description',
                            'employee_allowanceother.amount',
                            'employee_allowanceother.term'
                        )
                        // ->where('employee_allowanceother.employeeid', $request->get('employeeid'))
                        ->where('employee_allowanceother.deleted', '0')
                        ->get();

                    $deductiontypes = Db::table('deduction_standard')
                        ->where('deleted', '0')
                        // ->where('id','7')
                        // ->distinct('deduction_typeid')
                        ->get();

                    $checkifapplied_all = DB::table('employee_deductionstandard')
                        // ->where('employeeid', 111)
                        // ->where('deduction_typeid', $deductiontype->id)
                        ->where('deleted', '0')
                        ->where('status', '1')
                        ->get();


                    // return $checkifapplied_all;
                    $payrollDateFrom = Carbon::parse($payrollperiod->datefrom)->day;  // Extract day from the date
                    $payrollDateTo = Carbon::parse($payrollperiod->dateto)->day;      // Extract day from the date

                    // return $payrollDateTo;

                    // if ($payrollDateFrom == 6 && $payrollDateTo == 20) {
                    //     $otherdeductions_all = Db::table('employee_deductionother')
                    //     ->where('employee_deductionother.paid', '0')
                    //     ->where('employee_deductionother.status', '1')
                    //     ->where('employee_deductionother.deleted', '0')
                    //     ->where('deductionotherid', '!=', null)
                    //     ->where('deductionotherid', '!=', 5)
                    //     ->get();
                    // } else {

                    //     $otherdeductions_all = Db::table('employee_deductionother')
                    //     ->where('employee_deductionother.paid', '0')
                    //     ->where('employee_deductionother.status', '1')
                    //     ->where('employee_deductionother.deleted', '0')
                    //     ->where('deductionotherid', '!=', null)
                    //     ->get();
                    // }

                    $otherdeductions_all = Db::table('employee_deductionother')
                        ->where('employee_deductionother.paid', '0')
                        ->where('employee_deductionother.status', '1')
                        ->where('employee_deductionother.deleted', '0')
                        ->where('deductionotherid', '!=', null)
                        ->where('paidna', null)
                        ->get();

                    $paidotherdeductions_all = Db::table('employee_deductionother')
                        // ->where('employee_deductionother.paid', '0')
                        ->where('employee_deductionother.status', '1')
                        ->where('employee_deductionother.deleted', '0')
                        ->where('deductionotherid', '!=', null)
                        ->where('paidna', 1)
                        ->get();


                    // return collect($otherdeductions_all)->where('employeeid', 182);
                    $payrolldetails_all = DB::table('hr_payrollv2historydetail')
                        // ->leftJoin('hr_payrollv2history', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2history.payrollid')
                        ->where('hr_payrollv2historydetail.particulartype', 2)
                        // ->where('hr_payrollv2historydetail.employeeid', 77)
                        ->where('hr_payrollv2historydetail.paidstatus', 1)
                        ->where('hr_payrollv2historydetail.deleted', 0)
                        ->where('hr_payrollv2historydetail.payrollid', '!=', $checkifexistsid)
                        // ->where('hr_payrollv2history.released', 1)
                        ->get();

                    // return $payrolldetails_all;
                    $paiddeduction_all = DB::table('hr_payrollv2historydetail')
                        // ->leftJoin('hr_payrollv2history', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2history.payrollid')
                        ->where('hr_payrollv2historydetail.particulartype', 2)
                        // ->where('hr_payrollv2historydetail.employeeid', 77)
                        // ->where('hr_payrollv2history.employeeid', 77)
                        ->where('hr_payrollv2historydetail.payrollid', $checkifexistsid)
                        ->where('hr_payrollv2historydetail.paidstatus', 1)
                        ->where('hr_payrollv2historydetail.deleted', 0)
                        ->get();


                    $paiddeductions_all = DB::table('hr_payrollv2historydetail')
                        ->select(
                            'hr_payrollv2historydetail.amountpaid',
                            'hr_payrollv2historydetail.employeeid',
                            'hr_payrollv2historydetail.particularid',
                            'hr_payrollv2historydetail.payrollid'
                        )
                        // ->leftjoin('hr_payrollv2historydetail','hr_payrollv2history.id','=','hr_payrollv2historydetail.headerid')
                        // ->leftjoin('hr_payrollv2','hr_payrollv2history.payrollid','=','hr_payrollv2.id')
                        // ->where('hr_payrollv2history.employeeid', $request->get('employeeid'))
                        ->where('hr_payrollv2historydetail.paidstatus', '1')
                        ->where('hr_payrollv2historydetail.payrollid', '!=', $checkifexistsid)
                        // ->where('hr_payrollv2history.deleted','0')
                        // ->where('hr_payrollv2.deleted','0')
                        ->where('hr_payrollv2historydetail.particulartype', '2')
                        // ->where('hr_payrollv2historydetail.particularid',$eachotherdeduction->id)
                        // ->where('hr_payrollv2history.payrollid','<=',$payrollperiod->id)
                        ->get();


                    // return $paiddeductions_all;
                    $otheraddedearnings_all = Db::table('hr_payrollv2additionalearndeduct')
                        // ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                        ->where('type', 1)
                        // ->where('employeeid', 123)
                        ->where('deleted', '0')
                        ->get();

                    // return $otheraddedearnings_all;
                    $otheraddedearningsparticulars_all = Db::table('hr_payrollv2addparticular')
                        // ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                        ->where('payrollid', $checkifexistsid)
                        ->where('type', 1)
                        // ->where('employeeid', 123)
                        ->where('deleted', '0')
                        ->get();

                    // return $otheraddedearningsparticulars_all;
                    $otheraddeddeductions_all = Db::table('hr_payrollv2additionalearndeduct')
                        // ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                        ->where('type', 2)
                        ->where('deleted', '0')
                        ->get();

                    $otheraddeddeductionsparticulars_all = Db::table('hr_payrollv2addparticular')
                        // ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                        ->where('payrollid', $checkifexistsid)
                        ->where('type', 2)
                        ->where('deleted', '0')
                        ->get();

                    // return $otheraddeddeductionsparticulars_all;
                    $rtardiness_computations = DB::table('hr_tardinesscomp')
                        ->where('hr_tardinesscomp.deleted', '0')
                        ->where('hr_tardinesscomp.isactive', '1')
                        ->get();

                    $employeecustomsched_all = DB::table('employee_customtimesched')
                        // ->where('employeeid', $employeeinfo->id)
                        ->where('shiftid', '!=', null)
                        // ->where('createdby', '!=', null)
                        ->where('deleted', 0)
                        ->get();

                    $deductinfo_all = DB::table('hr_payrollv2historydetail')
                        ->select('hr_payrollv2historydetail.*')
                        ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                        // ->where('headerid', $payrollinfo->id)
                        // ->where('employeeid', 182)
                        // ->where('particulartype',2)
                        ->where('hr_payrollv2.deleted', '0')
                        ->where('hr_payrollv2historydetail.deleted', '0')
                        // ->where('particularid',$eachotherdeduction->id)
                        ->get();

                    // return $deductinfo_all;
                    $checkhistoryifexists_all = DB::table('hr_payrollv2history')
                        // ->where('payrollid', $payrollinseredid)
                        // ->where('employeeid', $employeeinfo->id)
                        ->where('deleted', '0')
                        ->get();


                    $activedaterange = DB::table('employee_cldaterange')
                        ->where('datefrom', $payrollperiod->datefrom)
                        ->where('dateto', $payrollperiod->dateto)
                        ->where('status', '1')
                        ->where('deleted', '0')
                        ->first();

                    if ($activedaterange) {
                        $activeemployeetardy = DB::table('employee_cltardy')
                            ->where('headerid', $activedaterange->id)
                            ->where('status', '1')
                            ->where('datastatus', $activedaterange->setupstatus)
                            ->where('deleted', '0')
                            ->get();
                    }

                    $mondayCount = 0;
                    $tuesdayCount = 0;
                    $wednesdayCount = 0;
                    $thursdayCount = 0;
                    $fridayCount = 0;
                    $saturdayCount = 0;
                    $sundayCount = 0;
                    $startDate = new DateTime($payrollperiod->datefrom);
                    $endDate = new DateTime($payrollperiod->dateto);

                    while ($startDate <= $endDate) {
                        $day_of_week = strtolower($startDate->format("l"));
                        if ($day_of_week === "monday") {
                            $mondayCount++;
                        } elseif ($day_of_week === "tuesday") {
                            $tuesdayCount++;
                        } elseif ($day_of_week === "wednesday") {
                            $wednesdayCount++;
                        } elseif ($day_of_week === "thursday") {
                            $thursdayCount++;
                        } elseif ($day_of_week === "friday") {
                            $fridayCount++;
                        } elseif ($day_of_week === "saturday") {
                            $saturdayCount++;
                        } elseif ($day_of_week === "sunday") {
                            $sundayCount++;
                        }
                        $startDate->modify("+1 day");
                    }


                    DB::table('hr_payrollv2')
                        ->where('id', $checkifexists->id)
                        ->where('salarytypeid', $salid)
                        ->update([
                            'datefrom' => $datefrom,
                            'dateto' => $dateto,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);


                    foreach ($employees as $employeeinfo) {

                        $attendance = [];
                        $leavedetails = [];
                        $holidays_within_range = [];

                        $payrollinfo = DB::table('hr_payrollv2history')
                            ->where('payrollid', $payrollperiod->id)
                            ->where('employeeid', $employeeinfo->id)
                            ->where('deleted', '0')
                            ->first();

                        // return collect($payrollinfo);
                        // return $tardiness_computations;
                        $tardinessamount = 0;
                        $lateduration = 0;
                        $durationtype = 0;
                        $tardinessallowance = 0;
                        $tardinessallowancetype = 0;
                        $totalPresentHolidaypay = 0; // Initialize the counter

                        // return $otheraddeddeductions;
                        $leaveamounttotal = 0;
                        $standardallowancesamount = 0;
                        $otheraddedearningsamount = 0;
                        $otheraddedearningsparticularsamount = 0;
                        $standarddeductionsamount = 0;
                        $otherdeductionsamount = 0;
                        $otheraddeddeductionsamount = 0;
                        $otheraddeddeductionsparticularsamount = 0;
                        // return $particulars;
                        $workinghours = 0;
                        $latemin = 0;
                        $lateam = 0;
                        $latepm = 0;
                        $latehours = 0;
                        $lateamount = 0;

                        $undertimemin = 0;
                        $undertimehours = 0;
                        $undertimeamount = 0;
                        $totalLateAmount = 0;
                        $totalLateMin = 0;
                        $totalUndertimeAmount = 0;
                        $totalUndertimeMin = 0;
                        $totalhourworks = 0;
                        $totalpresentdays = 0;
                        $totalabsentdays = 0;
                        $totalabsentamount = 0;
                        $totalabsentamounttotal = 0;

                        $totalearningamount = 0;
                        $totaldeductionamount = 0;
                        $netsalaryamount = 0;

                        $basicsalaryinfo = collect($basicsalaryinfos)->where('employeeid', $employeeinfo->id)->first();


                        $perdaysalary = 0;
                        $perhour = 0;

                        // ---------------------------------------------------------------
                        // mao ning additional for overload tardy
                        $regulardload = 0;
                        $overload = 0;
                        $emergencyload = 0;
                        $peroras = 0;
                        $regulardloadtardyamount = 0;
                        $overloadtardyamount = 0;
                        $emergencyloadtardyamount = 0;


                        // ---------------------------------------------------------------

                        if ($basicsalaryinfo) {
                            // If $basicsalaryinfo exists, set $employeeinfo->ratetype
                            $employeeinfo->ratetype = $basicsalaryinfo->ratetype;

                        } else {
                            // If $basicsalaryinfo is null or not found, set $employeeinfo->ratetype to an empty string
                            $employeeinfo->ratetype = '';
                        }

                        $basicsalaryinfo = (object) $basicsalaryinfo;
                        // return collect($basicsalaryinfo);
                        // return $basicsalaryinfo->attendancebased;

                        $monthlypayroll = collect($monthlypayroll_all)->where('employeeid', $employeeinfo->id)->values();
                        $taphistory = collect($taphistory_all)->where('studid', $employeeinfo->id)->values();
                        $hr_attendance = collect($hr_attendance_all)->where('studid', $employeeinfo->id)->values();
                        $departmentid = collect($departmentid_all)->where('id', $employeeinfo->id)->first()->departmentid;


                        $configured = 0;
                        $released = 0;

                        if ($payrollinfo) {
                            $configured = $payrollinfo->configured;
                            $released = $payrollinfo->released;
                        }

                        // return collect($payrollinfo);

                        // return $basicsalaryinfo->ratetype;


                        // return collect($basicsalaryinfo);

                        $dates = array();
                        $restday = [];




                        // if ($employeeinfo->id == 111) {
                        //     return strtolower($startDate->format("l"));
                        // }
                        if ($basicsalaryinfo && isset($basicsalaryinfo->attendancebased) && $basicsalaryinfo->attendancebased == 1) {

                            foreach ($period as $date) {
                                if (strtolower($date->format('l')) == 'monday') {
                                    if ($basicsalaryinfo->mondays == 1) {
                                        $dates[] = $date->format('Y-m-d');
                                    } else {
                                        $restday[] = 'Monday';
                                    }
                                } elseif (strtolower($date->format('l')) == 'tuesday') {
                                    if ($basicsalaryinfo->tuesdays == 1) {
                                        $dates[] = $date->format('Y-m-d');
                                    } else {
                                        $restday[] = 'Tuesday';
                                    }
                                } elseif (strtolower($date->format('l')) == 'wednesday') {
                                    if ($basicsalaryinfo->wednesdays == 1) {
                                        $dates[] = $date->format('Y-m-d');
                                    } else {
                                        $restday[] = 'Wednesday';
                                    }
                                } elseif (strtolower($date->format('l')) == 'thursday') {
                                    if ($basicsalaryinfo->thursdays == 1) {
                                        $dates[] = $date->format('Y-m-d');
                                    } else {
                                        $restday[] = 'Thursday';
                                    }
                                } elseif (strtolower($date->format('l')) == 'friday') {
                                    if ($basicsalaryinfo->fridays == 1) {
                                        $dates[] = $date->format('Y-m-d');
                                    } else {
                                        $restday[] = 'Friday';
                                    }
                                } elseif (strtolower($date->format('l')) == 'saturday') {
                                    if ($basicsalaryinfo->saturdays == 1) {
                                        $dates[] = $date->format('Y-m-d');
                                    } else {
                                        $restday[] = 'Saturday';
                                    }
                                } elseif (strtolower($date->format('l')) == 'sunday') {
                                    if ($basicsalaryinfo->sundays == 1) {
                                        $dates[] = $date->format('Y-m-d');
                                    } else {
                                        $restday[] = 'Sunday';
                                    }
                                }

                            }

                            // $employeeinfo->ratetype = $basicsalaryinfo->ratetype;

                            if (strtolower($basicsalaryinfo->salarytype) == 'monthly' || strtolower($basicsalaryinfo->salarytype) == 'custom') {
                                if ($basicsalaryinfo->amount == null || $basicsalaryinfo->amount == 0) {
                                    $basicsalaryinfo->amountperday = 0;
                                    $basicsalaryinfo->amountperhour = 0;
                                } else {
                                    if ($dates == null) {
                                        $basicsalaryinfo->amountperday = 0;
                                        $basicsalaryinfo->amountperhour = 0;

                                    } else {
                                        if ($basicsalaryinfo->halfdaysat == 1 || $basicsalaryinfo->halfdaysat == 2) {
                                            $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount / 2) / 13;
                                            $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                                            $perdaysalary = floor($basicsalaryinfo->amountperday * 100) / 100;
                                            $perhour = ($basicsalaryinfo->amountperday) / $basicsalaryinfo->hoursperday;
                                        } else {
                                            $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount / 2) / 13;
                                            $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                                            $perdaysalary = floor($basicsalaryinfo->amountperday * 100) / 100;
                                            $perhour = ($basicsalaryinfo->amountperday) / $basicsalaryinfo->hoursperday;
                                        }
                                    }
                                }
                            } elseif (strtolower($basicsalaryinfo->salarytype) == 'daily') {
                                $basicsalaryinfo->amountperday = $basicsalaryinfo->amount;
                                $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                                $basicsalaryinfo->amount = ($basicsalaryinfo->amount * count($dates));
                            } elseif (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount * $basicsalaryinfo->hoursperday) * count($dates);
                                $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                            }

                            if ($basicsalaryinfo) {
                                $peroras = $basicsalaryinfo->clsubjperhour;
                                $perminute = $peroras / 60;
                            }

                            if ($activedaterange) {
                                $tardys = collect($activeemployeetardy)->where('employeeid', $employeeinfo->id)->where('payrollid', $payrollperiod->id)->values();
                                foreach ($tardys as $tardy) {
                                    if ($tardy->type == 'Regular Late' || $tardy->type == 'Regular Undertime' || $tardy->type == 'Regular Absent') {
                                        $regulardload += $tardy->totalminutes;
                                    } else if ($tardy->type == 'Overload Late' || $tardy->type == 'Overload Undertime' || $tardy->type == 'Overload Absent') {
                                        $overload += $tardy->totalminutes;
                                    } else if ($tardy->type == 'Emergency Late' || $tardy->type == 'Emergency Undertime' || $tardy->type == 'Emergency Absent') {
                                        $emergencyload += $tardy->totalminutes;
                                    }
                                }
                            }

                            if ($regulardload > 0) {
                                $regulardloadtardyamount = round(($regulardload * $basicsalaryinfo->amountperhour / 60), 2);
                            }
                            if ($overload > 0) {
                                $overloadtardyamount = round($overload * $perminute, 2);
                            }
                            if ($emergencyload > 0) {
                                $emergencyloadtardyamount = round($emergencyload * $perminute, 2);
                            }

                            if (!empty($dates) && is_array($dates)) {
                                $attendance = \App\Models\HR\HREmployeeAttendance::gethours($dates, $employeeinfo->id, $taphistory, $hr_attendance, $departmentid);
                            }

                            $timebrackets = array();

                            if (count($attendance) > 0) {

                                foreach ($attendance as $eachdate) {
                                    // return $eachdate->date;

                                    $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date, $employeeinfo, ($basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday), $basicsalaryinfo, $taphistory, $hr_attendance);
                                    $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                                    $eachdate->lateminutes = $latedeductiondetail->lateminutes;
                                    $eachdate->holidayname = '';
                                    $eachdate->totalminuteswork = 0;
                                    if (count($latedeductiondetail->brackets) > 0) {
                                        foreach ($latedeductiondetail->brackets as $eachbracket) {
                                            array_push($timebrackets, $eachbracket);
                                        }
                                    }
                                    $eachdate->amountdeduct = 0;
                                }

                            }


                            if ($employeeinfo->departmentid) {
                                $tardinessbaseonsalary = collect($tardinessbaseonsalary_all)->where('departmentid', $employeeinfo->departmentid)->first();
                            }

                            if (count($attendance) > 0 && count($tardiness_computations) > 0) {
                                foreach ($attendance as $eachatt) {
                                    $eachatt->lateamminutes = ($eachatt->lateamhours * 60);
                                    $eachatt->latepmminutes = $eachatt->latepmhours * 60;
                                    $eachatt->lateminutes = $eachatt->latehours * 60;

                                    $eachcomputations = collect($tardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                                    $fromcomputations = collect($tardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                                    $eachcomputations = $eachcomputations->merge($fromcomputations);
                                    $eachcomputations = $eachcomputations->unique();

                                    if ($basicsalaryinfo->attendancebased == 1) {
                                        if (count($eachcomputations) > 0) {
                                            foreach ($eachcomputations as $eachcomputation) {
                                                if ($eachcomputation->latetimetype == 1) {
                                                    if ($eachcomputation->deducttype == 1) {
                                                        $eachatt->amountdeduct = $eachcomputation->amount;
                                                    } else {
                                                        $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                                    }

                                                } else {
                                                    $computehours = ($eachatt->lateminutes / 60);
                                                    if ($eachcomputation->deducttype == 1) {
                                                        $eachatt->amountdeduct = $eachcomputation->amount;
                                                    } else {
                                                        $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $eachatt->amountdeduct = 0;
                                    }
                                }
                            }


                            $latecomputationdetails = (object) array(
                                'tardinessamount' => $tardinessamount,
                                'lateduration' => $lateduration,
                                'durationtype' => $durationtype,
                                'tardinessallowance' => $tardinessallowance,
                                'tardinessallowancetype' => $tardinessallowancetype
                            );

                            if (!empty($dates)) {
                                // For HOLIDAY
                                $datesAbsents = [];
                                $datesAbsences = [];
                                $datesPresent = [];

                                foreach ($attendance as $attendanceData) {
                                    $date = $attendanceData->date;

                                    if ($attendanceData->status == 1) {
                                        $datesPresent[] = $date;
                                    } else {
                                        $datesAbsents[] = ['date' => $date];
                                        $datesAbsences[] = $date;
                                    }
                                }

                                // return $datesAbsences;

                                // get the missing dates from payroll period start and end, usually 15days
                                $startDate = $payrollperiod->datefrom;
                                $endDate = $payrollperiod->dateto;
                                $datesInRange = [];

                                $currentDate = strtotime($startDate);
                                $endTimestamp = strtotime($endDate);

                                while ($currentDate <= $endTimestamp) {
                                    $date = date("Y-m-d", $currentDate);
                                    $dayName = date("l", $currentDate); // Get the full day name
                                    $datesInRange[$date] = $dayName; // Store in an associative array
                                    $currentDate = strtotime("+1 day", $currentDate);
                                }

                                // this is the result together with the days name
                                $missingDates = array_diff_key($datesInRange, array_flip($datesPresent));


                                // compare missing dates if nag match sa gi return nga rest day 
                                $matchingMissingDates = [];

                                foreach ($missingDates as $date => $dayName) {
                                    if (in_array($dayName, $restday)) {
                                        $matchingMissingDates[$date] = $dayName;
                                    }
                                }

                                $matchingDates = array_keys($matchingMissingDates);
                                $attendanceforrestday = \App\Models\HR\HREmployeeAttendance::gethours($matchingDates, $employeeinfo->id, $taphistory, $hr_attendance, $departmentid);

                                $timebracketsrestday = array();

                                if (count($attendanceforrestday) > 0) {
                                    foreach ($attendanceforrestday as $eachdate) {
                                        $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date, $employeeinfo, ($basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday), $basicsalaryinfo, $taphistory, $hr_attendance);
                                        $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                                        $eachdate->lateminutes = $latedeductiondetail->lateminutes;
                                        $eachdate->restday = 1;

                                        if (count($latedeductiondetail->brackets) > 0) {
                                            foreach ($latedeductiondetail->brackets as $eachbracket) {
                                                array_push($timebracketsrestday, $eachbracket);
                                            }
                                        }
                                        $eachdate->amountdeduct = 0;
                                    }
                                }

                                // $rtardiness_computations = DB::table('hr_tardinesscomp')
                                //     ->where('hr_tardinesscomp.deleted','0')
                                //     ->where('hr_tardinesscomp.isactive','1')
                                //     ->get();

                                $rtardinessamount = 0;
                                $rlateduration = 0;
                                $rdurationtype = 0;
                                $rtardinessallowance = 0;
                                $rtardinessallowance = 0;

                                if (count($attendanceforrestday) > 0 && count($rtardiness_computations) > 0) {
                                    foreach ($attendanceforrestday as $eachatt) {
                                        $eachatt->lateamminutes = ($eachatt->lateamhours * 60);
                                        $eachatt->latepmminutes = number_format($eachatt->latepmhours * 60);
                                        $eachatt->lateminutes = number_format($eachatt->latehours * 60);

                                        $eachcomputations = collect($rtardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                                        $fromcomputations = collect($rtardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                                        $eachcomputations = $eachcomputations->merge($fromcomputations);
                                        $eachcomputations = $eachcomputations->unique();

                                        if ($basicsalaryinfo->attendancebased == 1) {
                                            if (count($eachcomputations) > 0) {
                                                foreach ($eachcomputations as $eachcomputation) {
                                                    if ($eachcomputation->latetimetype == 1) {
                                                        if ($eachcomputation->deducttype == 1) {
                                                            $eachatt->amountdeduct = $eachcomputation->amount;
                                                        } else {
                                                            $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                                        }

                                                    } else {
                                                        $computehours = ($eachatt->lateminutes / 60);
                                                        if ($eachcomputation->deducttype == 1) {
                                                            $eachatt->amountdeduct = $eachcomputation->amount;
                                                        } else {
                                                            $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $eachatt->amountdeduct = 0;
                                        }
                                    }
                                }
                                $attendanceArray = json_decode($attendanceforrestday, true);
                                $filteredAttendance = array_filter($attendanceArray, function ($record) {
                                    return $record['status'] == 1;
                                });

                                $datesArrayrestdays = [];

                                foreach ($filteredAttendance as $record) {
                                    $datesArrayrestdays[] = $record['date'];
                                }

                                $dayswithattendance = collect(array());
                                $dayswithattendance = $dayswithattendance->merge($datesPresent);
                                $dayswithattendance = $dayswithattendance->merge($datesArrayrestdays);


                                $holidaydates = [];
                                foreach ($holidays as $holiday) {
                                    $startDate = new \DateTime($holiday->start);
                                    $endDate = new \DateTime($holiday->end);

                                    $interval = new \DateInterval('P1D'); // 1 day interval
                                    $dateRange = new \DatePeriod($startDate, $interval, $endDate);

                                    foreach ($dateRange as $date) {
                                        $holidaydates[] = [
                                            'date' => $date->format('Y-m-d'),
                                            'type' => $holiday->description,
                                            'holidaytype' => $holiday->holidaytype,
                                            'holidayname' => $holiday->title
                                        ];

                                    }
                                }

                                // $employeecustomsched = DB::table('employee_customtimesched')
                                //     ->where('employeeid', $employeeinfo->id)
                                //     ->where('shiftid', '!=', null)
                                //     ->where('createdby', '!=', null)
                                //     ->where('deleted', 0)
                                //     ->first();

                                $employeecustomsched = collect($employeecustomsched_all)->where('employeeid', $employeeinfo->id)->first();
                                $leavedetails = \App\Models\HR\HRSalaryDetails::getleavesapplied($employeeinfo->id, $payrollperiod);
                                // return $leavedetails;

                                if (count($leavedetails) > 0) {

                                    foreach ($leavedetails as $leave) {

                                        $leave->amount = 0.00;

                                        $getpay = DB::table('hr_leaves')
                                            ->where('id', $leave->id)
                                            ->first();

                                        if (strtolower(date('D', strtotime($leave->ldate))) == 'mon') {
                                            if ($basicsalaryinfo->mondays == 1 && $getpay->withpay == 1) {
                                                if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                    $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                                } else {
                                                    $leave->amount = $perdaysalary;
                                                }
                                            }
                                        }
                                        if (strtolower(date('D', strtotime($leave->ldate))) == 'tue') {
                                            if ($basicsalaryinfo->tuesdays == 1 && $getpay->withpay == 1) {
                                                if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                    $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                                } else {
                                                    $leave->amount = $perdaysalary;
                                                }
                                            }
                                        }
                                        if (strtolower(date('D', strtotime($leave->ldate))) == 'wed') {
                                            if ($basicsalaryinfo->wednesdays == 1 && $getpay->withpay == 1) {
                                                if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                    $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                                } else {
                                                    $leave->amount = $perdaysalary;
                                                }
                                            }
                                        }
                                        if (strtolower(date('D', strtotime($leave->ldate))) == 'thu') {
                                            if ($basicsalaryinfo->thursdays == 1 && $getpay->withpay == 1) {
                                                if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                    $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                                } else {
                                                    $leave->amount = $perdaysalary;
                                                }
                                            }
                                        }
                                        if (strtolower(date('D', strtotime($leave->ldate))) == 'fri') {
                                            if ($basicsalaryinfo->fridays == 1 && $getpay->withpay == 1) {
                                                if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                    $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                                } else {
                                                    $leave->amount = $perdaysalary;
                                                }
                                            }
                                        }
                                        if (strtolower(date('D', strtotime($leave->ldate))) == 'sat') {
                                            // return date('D',strtotime($leavedatesperiod));
                                            // return $basicsalaryinfo->saturdays;
                                            if ($basicsalaryinfo->saturdays == 1 && $getpay->withpay == 1) {
                                                // return 'asdsa';
                                                if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                    $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                                } else {
                                                    $leave->amount = $perdaysalary;
                                                }
                                            }
                                        }
                                        if (strtolower(date('D', strtotime($leave->ldate))) == 'sun') {
                                            if ($basicsalaryinfo->sundays == 1 && $getpay->withpay == 1) {
                                                if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                    $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                                } else {
                                                    $leave->amount = $perdaysalary;
                                                }
                                            }
                                        }
                                        if ($leave->dayshift == 0) {
                                            $leave->leave_type = '' . $leave->leave_type;
                                            $leave->amount = round($leave->amount, 2);
                                        } elseif ($leave->dayshift == 1) {
                                            $leave->leave_type = 'AM - ' . $leave->leave_type;
                                            $leave->amount = round(($leave->amount / 2), 2);
                                        } elseif ($leave->dayshift == 2) {
                                            $leave->leave_type = 'PM - ' . $leave->leave_type;
                                            $leave->amount = round(($leave->amount / 2), 2);
                                        }

                                    }
                                }



                                if (count($leavedetails) > 0) {
                                    foreach ($leavedetails as $leavesdetail) {
                                        if ($leavesdetail->halfday == 0) {
                                            $leavesdetail->daycoverd = 'Whole Day';
                                        } else if ($leavesdetail->halfday == 1) {
                                            $leavesdetail->daycoverd = 'Half Day AM';
                                        } else if ($leavesdetail->halfday == 2) {
                                            $leavesdetail->daycoverd = 'Half Day PM';
                                        } else {
                                            $leavesdetail->daycoverd = '';
                                        }
                                    }
                                }

                                if (count($attendance) > 0) {
                                    foreach ($attendance as $lognull) {
                                        $lognull->leavetype = '';
                                        $lognull->leavedaystatus = '';
                                        $lognull->daycoverd = '';
                                        $lognull->leaveremarks = '';

                                        if (count($leavedetails) > 0) {

                                            foreach ($leavedetails as $employeeleavesapp) {
                                                if ($employeeleavesapp->ldate == $lognull->date) {
                                                    $lognull->leavetype = $employeeleavesapp->leave_type;
                                                    $lognull->leavedaystatus = $employeeleavesapp->halfday;
                                                    $lognull->daycoverd = $employeeleavesapp->daycoverd;
                                                    $lognull->leaveremarks = $employeeleavesapp->remarks ?? '';

                                                    // if ($lognull->daycoverd == ) {
                                                    //     # code...
                                                    // }
                                                }
                                            }
                                        }
                                    }

                                }

                                // HOLIDAY 
                                // if ($employeecustomsched) {

                                //     $amin = new DateTime($employeecustomsched->amin);
                                //     $amout = new DateTime($employeecustomsched->amout);
                                //     $pmin = new DateTime($employeecustomsched->pmin);
                                //     $pmout = new DateTime($employeecustomsched->pmout);

                                //     // Calculate morning working hours
                                //     $morningWorkingHours = $amin->diff($amout);
                                //     $amhours = $morningWorkingHours->h;

                                //     // Calculate afternoon working hours
                                //     $afternoonWorkingHours = $pmin->diff($pmout);
                                //     $pmhours = $afternoonWorkingHours->h;
                                //     $totalworkinghours = $amhours + $pmhours;



                                //     // return $holidaydates;
                                //     if (count($holidaydates) > 0) {
                                //         foreach ($attendance as $att) {
                                //             $att->holiday = 0;
                                //             $att->holidayname = "";

                                //             foreach ($holidaydates as $holidaydate) {
                                //                 // if ($att->date == $holidaydate['date'] && $att->status == 2 && ($holidaydate['holidaytype'] == 1 || $holidaydate['type'] == 'Regular Holiday')) {
                                //                 if ($att->date == $holidaydate['date'] && $att->status == 2) {
                                //                     $att->amtimein = $employeecustomsched->amin;
                                //                     $att->amtimeout = $employeecustomsched->amout;
                                //                     $att->pmtimein = $employeecustomsched->pmin;
                                //                     $att->pmtimeout = $employeecustomsched->pmout;
                                //                     $att->timeinam = $employeecustomsched->amin;
                                //                     $att->timeoutam = $employeecustomsched->amout;
                                //                     $att->timeinpm = $employeecustomsched->pmin;
                                //                     $att->timeoutpm = $employeecustomsched->pmout;
                                //                     $att->amin = $employeecustomsched->amin;
                                //                     $att->amout = $employeecustomsched->amout;
                                //                     $att->pmin = $employeecustomsched->pmin;
                                //                     $att->pmout = $employeecustomsched->pmout;
                                //                     $att->status = 1;
                                //                     $att->totalworkinghours = $totalworkinghours;
                                //                     $att->holiday = 1;
                                //                     $att->holidayname = $holidaydate['holidayname'];

                                //                 } else if ($att->date == $holidaydate['date'] && $att->status == 1) {
                                //                     $att->amtimein = $employeecustomsched->amin;
                                //                     $att->amtimeout = $employeecustomsched->amout;
                                //                     $att->pmtimein = $employeecustomsched->pmin;
                                //                     $att->pmtimeout = $employeecustomsched->pmout;
                                //                     $att->timeinam = $employeecustomsched->amin;
                                //                     $att->timeoutam = $employeecustomsched->amout;
                                //                     $att->timeinpm = $employeecustomsched->pmin;
                                //                     $att->timeoutpm = $employeecustomsched->pmout;
                                //                     $att->amin = $employeecustomsched->amin;
                                //                     $att->amout = $employeecustomsched->amout;
                                //                     $att->pmin = $employeecustomsched->pmin;
                                //                     $att->pmout = $employeecustomsched->pmout;
                                //                     $att->status = 1;
                                //                     $att->totalworkinghours = $totalworkinghours;
                                //                     $att->holiday = 1;
                                //                     $att->holidayname = $holidaydate['holidayname'];


                                //                     $att->lateminutes = 0;
                                //                     $att->lateamminutes = 0;
                                //                     $att->latepmminutes = 0;
                                //                     $att->lateamhours = 0;
                                //                     $att->latepmhours = 0;
                                //                     $att->latehours = 0;
                                //                     $att->undertimeamhours = 0;
                                //                     $att->undertimepmhours = 0;

                                //                 }
                                //             }
                                //         }
                                //     }
                                // }

                                // if ($payrollperiod) {
                                //     $datefrom = $payrollperiod->datefrom;
                                //     $dateto = $payrollperiod->dateto;


                                //     foreach ($holidays as $holiday) {
                                //         $start_date = strtotime($holiday->start);
                                //         $end_date = strtotime($holiday->end);

                                //         if ($start_date <= strtotime($dateto) && $end_date >= strtotime($datefrom)) {
                                //             // Check if the start date of the holiday matches any date in $datesArrayrestdays
                                //             if (in_array(date("Y-m-d", $start_date), $datesArrayrestdays)) {
                                //                 // If it matches, set attendance to 'present'
                                //                 $holiday->attendance = 'present';
                                //             } else {
                                //                 // If it doesn't match, set attendance to 'not present'
                                //                 $holiday->attendance = 'not present';
                                //             }

                                //             // Calculate holiday pay percentage
                                //             $duration = ceil(($end_date - $start_date) / (60 * 60 * 24)); // Calculate duration in days

                                //             // Calculate the amount per day
                                //             $amountPerDay = $basicsalaryinfo->amountperday;
                                //             $holiday->matching = "";
                                //             $holiday->hoursperday = $basicsalaryinfo->hoursperday;
                                //             $holiday->duration = $duration; // Add duration as a property
                                //             $holiday->amountPerDay = $amountPerDay; // Add amount per day as a property
                                //             $holiday->holidaypay = $amountPerDay;

                                //             $holidays_within_range[] = $holiday;
                                //         }
                                //     }
                                // }


                                // foreach ($dayswithattendance as $datesp) {
                                //     if (in_array($datesp, $datesArrayrestdays)) {
                                //         $datepresentstart = strtotime($datesp); // Access the date directly since $datesp is a string
                                //         foreach ($holidays_within_range as $holiday_within) { // Use &$holiday_within to modify the original array
                                //             $start = strtotime($holiday_within->start);
                                //             if ($start == $datepresentstart) { // Use == for comparison, not =
                                //                 $holiday_within->matching = "Matching"; // Add a property to indicate the match
                                //                 $holiday_within->attendance = 'present';
                                //                 $holidaypercentage = $holiday_within->restdayifwork / 100;
                                //                 $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
                                //                 $holidayperday = $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
                                //                 $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                //                 $holidaywithdurationpay = $holidaywithdurationpay + $holiday_within->amountPerDay;
                                //                 $holiday_within->holidaypay = $holidaywithdurationpay;
                                //             }
                                //         }
                                //     } else {
                                //         $datepresentstart = strtotime($datesp); // Access the date directly since $datesp is a string
                                //         foreach ($holidays_within_range as $holiday_within) { // Use &$holiday_within to modify the original array
                                //             $start = strtotime($holiday_within->start);
                                //             if ($start == $datepresentstart) { // Use == for comparison, not =
                                //                 $holiday_within->matching = "Matching"; // Add a property to indicate the match
                                //                 $holiday_within->attendance = 'present';
                                //                 $holidaypercentage = $holiday_within->ifwork / 100;

                                //                 $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;

                                //                 $holidayperday = $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;

                                //                 $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                //                 // $holidaywithdurationpay = $holiday_within->amountPerDay * $holiday_within->duration;
                                //                 // $holiday_within->holidaypay = number_format($holidaywithdurationpay - .005, 2);
                                //                 $holiday_within->holidaypay = floor($holidaywithdurationpay * 100) / 100;

                                //             }
                                //         }
                                //     }
                                // }

                                // foreach ($datesAbsences as $datesp) {
                                //     $dateabsentstart = strtotime($datesp);
                                //     foreach ($holidays_within_range as $holiday_within) {
                                //         if ($holiday_within->ifnotwork > 0) {
                                //             $start = strtotime($holiday_within->start);
                                //             if ($start == $dateabsentstart) {
                                //                 $holiday_within->matching = "Matching";
                                //                 $holidaypercentage = $holiday_within->ifwork / 100;
                                //                 $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
                                //                 $holidayperday = $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
                                //                 $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                //                 $holiday_within->holidaypay = floor($holidaywithdurationpay * 100) / 100;
                                //             }
                                //         }

                                //     }
                                // }


                                // // Get the total holiday payment
                                // if ($employeeinfo->employmentstatus == 1) {
                                //     foreach ($holidays_within_range as $holidaypayments) {
                                //         if ($holidaypayments->matching == 'Matching') {
                                //             $totalPresentHolidaypay += $holidaypayments->holidaypay; // Increment the counter
                                //         }
                                //     }
                                // }

                                if ($employeecustomsched) {
                                    $amin = new DateTime($employeecustomsched->amin);
                                    $amout = new DateTime($employeecustomsched->amout);
                                    $pmin = new DateTime($employeecustomsched->pmin);
                                    $pmout = new DateTime($employeecustomsched->pmout);


                                    // Calculate morning working hours
                                    $morningWorkingHours = $amin->diff($amout);
                                    $amhours = $morningWorkingHours->h + ($morningWorkingHours->i / 60); // Convert minutes to fraction of an hour

                                    // Calculate afternoon working hours
                                    $afternoonWorkingHours = $pmin->diff($pmout);
                                    $pmhours = $afternoonWorkingHours->h + ($afternoonWorkingHours->i / 60); // Convert minutes to fraction of an hour

                                    // Calculate total working hours
                                    $totalworkinghours = $amhours + $pmhours;

                                    // if ($basicsalaryinfo) {
                                    //     if ($basicsalaryinfo->halfdaysat == 1) {
                                    //         foreach ($attendance as $attt) {
                                    //             if ($attt->pmin != null || $attt->pmout != null) {
                                    //                 if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                                    //                     $attt->amtimein = $employeecustomsched->amin;
                                    //                     $attt->amtimeout = $employeecustomsched->amout;
                                    //                     $attt->timeinam = $employeecustomsched->amin;
                                    //                     $attt->timeoutam = $employeecustomsched->amout;
                                    //                     $attt->amin = $employeecustomsched->amin;
                                    //                     $attt->amout = $employeecustomsched->amout;
                                    //                     $attt->status = 1;


                                    //                     if ($attt->pmin == null || $attt->pmout == null) {
                                    //                         $attt->totalworkinghours = $amhours;
                                    //                         $attt->totalworkinghoursrender = $amhours;
                                    //                         $attt->undertimepmhours = $pmhours;

                                    //                     } else {
                                    //                         $attt->totalworkinghours = $amhours + ($amhours - $attt->latepmhours);
                                    //                         $attt->totalworkinghoursrender = $amhours + ($amhours - $attt->latepmhours);
                                    //                         $attt->lateamminutes = 0;
                                    //                         $attt->lateamhours = 0;
                                    //                         $attt->undertimeamhours = 0;
                                    //                     }
                                    //                 }
                                    //             }
                                    //         }
                                    //     } else if ($basicsalaryinfo->halfdaysat == 2) {
                                    //         foreach ($attendance as $attt) {
                                    //             if ($attt->amin != null || $attt->amout != null) {
                                    //                 if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                                    //                     $attt->pmtimein = $employeecustomsched->pmin;
                                    //                     $attt->pmtimeout = $employeecustomsched->pmout;
                                    //                     $attt->timeinpm = $employeecustomsched->pmin;
                                    //                     $attt->timeoutpm = $employeecustomsched->pmout;
                                    //                     $attt->pmin = $employeecustomsched->pmin;
                                    //                     $attt->pmout = $employeecustomsched->pmout;
                                    //                     $attt->status = 1;

                                    //                     if ($attt->amin == null || $attt->amout == null) {
                                    //                         $attt->totalworkinghours = $pmhours;
                                    //                         $attt->totalworkinghoursrender = $pmhours;
                                    //                         $attt->lateamminutes = $pmhours * 60;
                                    //                         $attt->lateamhours = $pmhours;
                                    //                         // $attt->undertimeamhours = $pmhours;
                                    //                     } else {
                                    //                         $attt->totalworkinghours = $pmhours + ($amhours - $attt->lateamhours);
                                    //                         $attt->totalworkinghoursrender = $pmhours + ($amhours - $attt->lateamhours);
                                    //                         $attt->latepmminutes = 0;
                                    //                         $attt->latepmhours = 0;
                                    //                         $attt->undertimepmhours = 0;
                                    //                     }
                                    //                 }
                                    //             }

                                    //         }

                                    //     }
                                    // }


                                    $totalLateHours = 0;
                                    $totalUndertimeHours = 0;
                                    $totalworkinghoursrender = 0;
                                    $flexihours = 0;
                                    $flexihoursundertime = 0;

                                    // foreach ($attendance as $entry) {
                                    //     if ($entry->totalworkinghours != 0 && $entry->totalworkinghours !== null && $entry->amin !== null && $entry->pmout !== null) {
                                    //         // if ($entry->totalworkinghours != 0 && $entry->totalworkinghours !== null) {
                                    //         // return $entry->date;
                                    //         $totalLateHours = $entry->latehours;
                                    //         $totalUndertimeHours = $entry->undertimehours;
                                    //         $totalWorkingHoursRender = 8 - ($totalLateHours + $totalUndertimeHours);
                                    //         $entry->totalworkinghoursrender = $totalWorkingHoursRender;

                                    //         $flexihours = $entry->totalworkinghoursflexi;
                                    //         $flexihoursundertime = 8 - $flexihours;

                                    //         if ($flexihours > 8) {
                                    //             $entry->flexihours = 8;
                                    //         } else {
                                    //             // $entry->flexihours = number_format($flexihours - .005, 2);
                                    //             $entry->flexihours = $flexihours;
                                    //         }
                                    //         if ($flexihoursundertime < 0) {
                                    //             $entry->flexihoursundertime = 0;
                                    //         } else {
                                    //             $entry->flexihoursundertime = $flexihoursundertime;
                                    //         }
                                    //     } else {
                                    //         $entry->totalworkinghoursrender = 0;
                                    //         $entry->flexihours = 0;
                                    //         $entry->flexihoursundertime = 0;
                                    //     }
                                    // }

                                    // foreach ($attendance as $att) {

                                    //     if ($att->status == 1) {

                                    //         if ($att->leavedaystatus === 0) {

                                    //             $att->amtimein = $employeecustomsched->amin;
                                    //             $att->amtimeout = $employeecustomsched->amout;
                                    //             $att->pmtimein = $employeecustomsched->pmin;
                                    //             $att->pmtimeout = $employeecustomsched->pmout;
                                    //             $att->timeinam = $employeecustomsched->amin;
                                    //             $att->timeoutam = $employeecustomsched->amout;
                                    //             $att->timeinpm = $employeecustomsched->pmin;
                                    //             $att->timeoutpm = $employeecustomsched->pmout;
                                    //             $att->amin = $employeecustomsched->amin;
                                    //             $att->amout = $employeecustomsched->amout;
                                    //             $att->pmin = $employeecustomsched->pmin;
                                    //             $att->pmout = $employeecustomsched->pmout;
                                    //             $att->status = 1;
                                    //             $att->totalworkinghours = $totalworkinghours;
                                    //             $att->totalworkinghoursrender = $totalworkinghours;
                                    //             $att->appliedleave = 1;
                                    //             // $att->leavetype = $leavedetail->leave_type;
                                    //             $att->latepmminutes = 0;
                                    //             $att->latepmhours = 0;
                                    //             $att->undertimepmhours = 0;
                                    //             $att->lateamminutes = 0;
                                    //             $att->lateamhours = 0;
                                    //             $att->undertimeamhours = 0;
                                    //             $att->latehours = 0;
                                    //             $att->appliedleave = 1;


                                    //         } else if ($att->leavedaystatus == 1) {

                                    //             //return floor(($att->totalworkinghours + $amhours) * 100) / 100;
                                    //             $att->amtimein = $employeecustomsched->amin;
                                    //             $att->amtimeout = $employeecustomsched->amout;
                                    //             $att->timeinam = $employeecustomsched->amin;
                                    //             $att->timeoutam = $employeecustomsched->amout;
                                    //             $att->amin = $employeecustomsched->amin;
                                    //             $att->amout = $employeecustomsched->amout;
                                    //             // $att->leavetype = $leavedetail->leave_type;
                                    //             // return collect($att->totalworkinghours);


                                    //             $att->totalworkinghours = floor(($att->totalworkinghours + $amhours) * 100) / 100;
                                    //             $att->totalworkinghoursrender = floor(($att->totalworkinghours) * 100) / 100;
                                    //             $att->totalworkinghoursflexi = floor(($att->totalworkinghours) * 100) / 100;
                                    //             $att->flexihours = floor(($att->totalworkinghours) * 100) / 100;


                                    //             if (($att->timeinpm != null || $att->pmtimein != null) && ($att->timeoutpm == null || $att->pmtimeout == null)) {
                                    //                 $att->latepmminutes = 0;
                                    //                 $att->latepmhours = 0;
                                    //                 $att->undertimepmhours = $pmhours - $att->latepmhours;
                                    //                 $att->undertimeminutes = ($pmhours - $att->latepmhours) * 60;
                                    //             } else {
                                    //                 $att->latepmminutes = $att->latepmhours * 60;
                                    //                 $att->latepmhours = $att->latepmhours;
                                    //                 $att->undertimeamhours = 0;
                                    //                 $att->lateamhours = 0;
                                    //                 $att->lateamminutes = 0;

                                    //             }

                                    //             // $att->appliedleave = 1;
                                    //             // return collect($att);

                                    //         } else if ($att->leavedaystatus == 2) {
                                    //             $att->pmtimein = $employeecustomsched->pmin;
                                    //             $att->pmtimeout = $employeecustomsched->pmout;
                                    //             $att->timeinpm = $employeecustomsched->pmin;
                                    //             $att->timeoutpm = $employeecustomsched->pmout;
                                    //             $att->pmin = $employeecustomsched->pmin;
                                    //             $att->pmout = $employeecustomsched->pmout;
                                    //             // $att->leavetype = $leavedetail->leave_type;
                                    //             $att->status = 1;
                                    //             // return $att->date;


                                    //             $att->totalworkinghours = floor(($att->totalworkinghours + $pmhours) * 100) / 100;
                                    //             // $att->totalworkinghours = floor(($att->totalworkinghoursrender + $pmhours) * 100) / 100;
                                    //             // $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $pmhours) * 100) / 100;
                                    //             // $att->totalworkinghoursflexi = floor(($att->totalworkinghoursflexi + $pmhours) * 100) / 100;
                                    //             // $att->flexihours = floor(($att->flexihours + $pmhours) * 100) / 100;
                                    //             $att->totalworkinghoursrender = floor(($att->totalworkinghours) * 100) / 100;
                                    //             $att->totalworkinghoursflexi = floor(($att->totalworkinghours) * 100) / 100;
                                    //             $att->flexihours = floor(($att->totalworkinghours) * 100) / 100;


                                    //             if (($att->timeinam == null || $att->amtimein == null)) {
                                    //                 $att->lateamminutes = $amhours * 60;
                                    //                 $att->lateamhours = $amhours;
                                    //                 $att->undertimepmhours = 0;
                                    //                 $att->undertimeminutes = 0;
                                    //                 $att->latepmminutes = 0;
                                    //                 $att->latepmhours = 0;
                                    //             } else {

                                    //                 $att->lateaminutes = $att->lateamhours * 60;
                                    //                 $att->lateamhours = $att->lateamhours;
                                    //                 $att->undertimepmhours = 0;

                                    //                 $att->latepmhours = 0;
                                    //                 $att->latepmminutes = 0;
                                    //             }
                                    //             $att->appliedleave = 1;
                                    //             // return collect($att);
                                    //         }
                                    //     } else {
                                    //         if ($att->leavedaystatus === 0) {
                                    //             $att->amtimein = $employeecustomsched->amin;
                                    //             $att->amtimeout = $employeecustomsched->amout;
                                    //             $att->pmtimein = $employeecustomsched->pmin;
                                    //             $att->pmtimeout = $employeecustomsched->pmout;
                                    //             $att->timeinam = $employeecustomsched->amin;
                                    //             $att->timeoutam = $employeecustomsched->amout;
                                    //             $att->timeinpm = $employeecustomsched->pmin;
                                    //             $att->timeoutpm = $employeecustomsched->pmout;
                                    //             $att->amin = $employeecustomsched->amin;
                                    //             $att->amout = $employeecustomsched->amout;
                                    //             $att->pmin = $employeecustomsched->pmin;
                                    //             $att->pmout = $employeecustomsched->pmout;
                                    //             $att->status = 1;
                                    //             $att->totalworkinghours = $totalworkinghours;
                                    //             $att->totalworkinghoursrender = $totalworkinghours;
                                    //             $att->appliedleave = 1;
                                    //             // $att->leavetype = $leavedetail->leave_type;
                                    //             $att->latepmminutes = 0;
                                    //             $att->latepmhours = 0;
                                    //             $att->undertimepmhours = 0;
                                    //             $att->lateamminutes = 0;
                                    //             $att->lateamhours = 0;
                                    //             $att->undertimeamhours = 0;
                                    //             $att->latehours = 0;
                                    //             $att->appliedleave = 1;


                                    //         } else if ($att->leavedaystatus == 1) {

                                    //             $att->amtimein = $employeecustomsched->amin;
                                    //             $att->amtimeout = $employeecustomsched->amout;
                                    //             $att->timeinam = $employeecustomsched->amin;
                                    //             $att->timeoutam = $employeecustomsched->amout;
                                    //             $att->amin = $employeecustomsched->amin;
                                    //             $att->amout = $employeecustomsched->amout;
                                    //             // $att->leavetype = $leavedetail->leave_type;
                                    //             $att->status = 1;



                                    //             $att->totalworkinghours = $amhours;
                                    //             $att->totalworkinghoursrender = $amhours;
                                    //             $att->totalworkinghoursflexi = $amhours;
                                    //             $att->flexihours = $amhours;
                                    //             $att->latepmminutes = 0;
                                    //             $att->latepmhours = 0;
                                    //             $att->undertimepmhours = $pmhours;
                                    //             $att->undertimeminutes = $pmhours * 60;
                                    //             // $att->appliedleave = 1;

                                    //             // return collect($att);

                                    //         } else if ($att->leavedaystatus == 2) {
                                    //             $att->pmtimein = $employeecustomsched->pmin;
                                    //             $att->pmtimeout = $employeecustomsched->pmout;
                                    //             $att->timeinpm = $employeecustomsched->pmin;
                                    //             $att->timeoutpm = $employeecustomsched->pmout;
                                    //             $att->pmin = $employeecustomsched->pmin;
                                    //             $att->pmout = $employeecustomsched->pmout;
                                    //             // $att->leavetype = $leavedetail->leave_type;
                                    //             $att->status = 1;
                                    //             $att->totalworkinghours = $pmhours;
                                    //             $att->totalworkinghours = $pmhours;
                                    //             // $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $att->latepmhours) * 100) / 100;
                                    //             $att->totalworkinghoursflexi = $pmhours;
                                    //             $att->flexihours = $pmhours;

                                    //             $att->lateamminutes = $amhours * 60;
                                    //             $att->lateamhours = $amhours;
                                    //             $att->undertimeamhours = 0;

                                    //         }
                                    //     }

                                    // }

                                    if (count($attendance) > 0) {
                                        foreach ($attendance as $time) {
                                            // for late
                                            $time->lateminutes = $time->latehours;
                                            // for undertime
                                            $time->undertimeminutes = $time->undertimehours;

                                        }

                                        foreach ($attendance as $att) {

                                            $latemin = $att->latehours;
                                            $lateamount = $att->latehours * number_format($basicsalaryinfo->amountperhour / 60, 2);
                                            $totalLateAmount += $lateamount;
                                            $totalLateMin += $latemin;

                                            // for undertime

                                            $undertimemin += $att->undertimehours;
                                            $undertimeamount = $att->undertimehours * number_format($basicsalaryinfo->amountperhour / 60, 2);
                                            $totalUndertimeAmount += $undertimeamount;
                                            $totalUndertimeMin = $undertimemin;

                                            // totalworkinghours
                                            $workinghours = $att->totalworkinghours;
                                            $totalhourworks += $workinghours;

                                            //presentdays
                                            if ($att->totalworkinghours != 0) {
                                                $totalpresentdays += 1;
                                            }
                                            //absentdays
                                            if ($att->totalworkinghours == 0) {
                                                $totalabsentdays += 1;
                                            }
                                        }

                                    }

                                }


                                if ($basicsalaryinfo->amountperday != null) {
                                    $perday = $basicsalaryinfo->amountperday;
                                } else {
                                    $perday = 0;
                                }

                                // Calculate total absent amount for the employee
                                if ($totalabsentdays > 0) {
                                    $totalabsentamounttotal = $totalabsentdays * $basicsalaryinfo->amountperday;
                                } else {
                                    $totalabsentamounttotal = 0;
                                }
                            }
                        } else {
                            if ($activedaterange) {
                                $tardys = collect($activeemployeetardy)->where('employeeid', $employeeinfo->id)->where('payrollid', $payrollperiod->id)->values();

                                foreach ($tardys as $tardy) {
                                    if ($tardy->type == 'Regular Late' || $tardy->type == 'Regular Undertime' || $tardy->type == 'Regular Absent') {
                                        $regulardload += $tardy->totalminutes;
                                    } else if ($tardy->type == 'Overload Late' || $tardy->type == 'Overload Undertime' || $tardy->type == 'Overload Absent') {
                                        $overload += $tardy->totalminutes;
                                    } else if ($tardy->type == 'Emergency Late' || $tardy->type == 'Emergency Undertime' || $tardy->type == 'Emergency Absent') {
                                        $emergencyload += $tardy->totalminutes;
                                    }
                                }
                            }

                            if ($regulardload > 0) {
                                if ($basicsalaryinfo) {
                                    if ($basicsalaryinfo->amount == 0) {
                                        $peroras = $basicsalaryinfo->clsubjperhour;
                                        $perminute = $peroras / 60;
                                        $regulardloadtardyamount = round($regulardload * $perminute, 2);
                                    } else {
                                        $kinsina = $basicsalaryinfo->amount / 2;
                                        $perday = $kinsina / 13;
                                        $perhour = $perday / 8;
                                        $amountperhour = $perhour / 60;

                                        $regulardloadtardyamount = round($regulardload * $amountperhour, 2);
                                    }
                                }
                            }

                            if ($overload > 0) {
                                $peroras = $basicsalaryinfo->clsubjperhour;
                                $perminute = $peroras / 60;
                                $overloadtardyamount = round($overload * $perminute, 2);
                            }


                            if ($emergencyload > 0) {

                                $peroras = $basicsalaryinfo->clsubjperhour;
                                $perminute = $peroras / 60;
                                $emergencyloadtardyamount = round($emergencyload * $perminute, 2);
                            }

                            $basicsalaryinfo->amountperday = 0;
                            $basicsalaryinfo->amountperhour = 0;
                        }

                        $standardallowances = collect($standardallowances_all)->where('employeeid', $employeeinfo->id)->values();

                        $alltotalamount = 0;
                        if (count($standardallowances) > 0) {
                            foreach ($standardallowances as $allowancetype) {
                                // return collect($allowancetype);
                                $allowancetype->particulartype = 3;
                                // if ($allowancetype->amountbaseonsalary == 1) {
                                //     $allowancetype->particularid = $allowancetype->empallowanceid;
                                //     $allowancetype->paymenttype = 0;
                                // } else {
                                $allowancetype->particularid = $allowancetype->allowance_standardid;
                                // }

                                // if ($allowancetype->amountbaseonsalary === 1) {
                                //     if ($allowancetype->monday == 1) {
                                //         $mondayAmount = $basicsalaryinfo->amountperday * $mondayCount;
                                //         $alltotalamount += $mondayAmount;
                                //         $allowancetype->mondayAmount = number_format($mondayAmount, 2);
                                //     }
                                //     if ($allowancetype->tuesday == 1) {
                                //         $tuesdayAmount = $basicsalaryinfo->amountperday * $tuesdayCount;
                                //         $alltotalamount += $tuesdayAmount;
                                //         $allowancetype->tuesdayAmount = number_format($tuesdayAmount, 2);
                                //     }
                                //     if ($allowancetype->wednesday == 1) {
                                //         $wednesdayAmount = $basicsalaryinfo->amountperday * $wednesdayCount;
                                //         $alltotalamount += $wednesdayAmount;
                                //         $allowancetype->wednesdayAmount = number_format($wednesdayAmount, 2);
                                //     }
                                //     if ($allowancetype->thursday == 1) {
                                //         $thursdayAmount = $basicsalaryinfo->amountperday * $thursdayCount;
                                //         $alltotalamount += $thursdayAmount;
                                //         $allowancetype->thursdayAmount = number_format($thursdayAmount, 2);
                                //     }
                                //     if ($allowancetype->friday == 1) {
                                //         $fridayAmount = $basicsalaryinfo->amountperday * $fridayCount;
                                //         $alltotalamount += $fridayAmount;
                                //         $allowancetype->fridayAmount = number_format($fridayAmount, 2);
                                //     }
                                //     if ($allowancetype->saturday == 1) {
                                //         $saturdayAmount = $basicsalaryinfo->amountperday * $saturdayCount;
                                //         $alltotalamount += $saturdayAmount;
                                //         $allowancetype->saturdayAmount = number_format($saturdayAmount, 2);
                                //     }
                                //     if ($allowancetype->sunday == 1) {
                                //         $sundayAmount = $basicsalaryinfo->amountperday * $sundayCount;
                                //         $alltotalamount += $sundayAmount;
                                //         $allowancetype->sundayAmount = number_format($sundayAmount, 2);
                                //     }
                                //     $allowancetype->totaldaysallowanceamount = number_format($alltotalamount, 2);

                                //     $allowancetype->amount = sprintf("%.2f", $alltotalamount);
                                //     $allowancetype->baseonattendance = 0;
                                //     $allowancetype->amountperday = 0;
                                //     $allowancetype->lock = 1;
                                //     $allowancetype->paidforthismonth = 0;
                                //     $allowancetype->totalamount = sprintf("%.2f", $alltotalamount);
                                //     $allowancetype->paymenttype = 0;
                                //     if ($basicsalaryinfo->salarybasistype == 5) {
                                //         $allowancetype->perday = $basicsalaryinfo->amountperday;
                                //     }
                                // } else {
                                // return collect($payrollperiod);

                                $eachallowance = \App\Models\HR\HRAllowances::getstandardallowances($employeeinfo->id, $payrollperiod, $allowancetype->empallowanceid, $allowancetype->id, $allowancetype->baseonattendance, $allowancetype->amountperday, $allowancetype->amountbaseonsalary);
                                $allowancetype->amount = $eachallowance->amount;
                                $allowancetype->baseonattendance = $eachallowance->baseonattendance;
                                $allowancetype->amountperday = $eachallowance->amountperday;
                                $allowancetype->lock = $eachallowance->lock;
                                $allowancetype->paidforthismonth = $eachallowance->paidforthismonth;
                                $allowancetype->totalamount = $eachallowance->totalamount;
                                $allowancetype->paymenttype = $eachallowance->paymenttype;
                                $allowancetype->paidstatus = $eachallowance->paidstatus;
                                // }
                            }
                        }
                        // return $saturdayCount;
                        // return $attendance;

                        foreach ($standardallowances as $sallowancestandard) {
                            // if ($sallowancestandard->baseonattendance == 1) {
                            //     $amountPerDay = $sallowancestandard->amountperday;
                            //     $totalDaysAmount = $amountPerDay * (count($dayswithattendance) + $saturdayCount);
                            //     $sallowancestandard->amount = $totalDaysAmount;
                            //     // $sallowancestandard->totalDayspresent = count($dayswithattendance);
                            //     $sallowancestandard->totalDayspresent = collect($attendance)->where('totalworkinghours', '>', 0)->count() + $saturdayCount;
                            // } else {
                            $sallowancestandard->totalDaysAmount = 0;
                            // }
                        }

                        unset($sallowancestandard); // Unset the reference to the last element

                        $holidays_within_range = json_decode(json_encode($holidays_within_range), true);

                        $filtered_holidays = $holidays_within_range;


                        //  standard deduction
                        // return $standardallowances;
                        $standarddeductions = array();
                        // return $deductiontypes;
                        if (count($deductiontypes) > 0) {
                            foreach ($deductiontypes as $deductiontype) {

                                // return collect($deductiontype);
                                $deductiontype->particulartype = 1;
                                $deductiontype->particularid = $deductiontype->id;
                                // $checkifapplied = DB::table('employee_deductionstandard')
                                //     ->where('employeeid', $request->get('employeeid'))
                                //     ->where('deduction_typeid', $deductiontype->id)
                                //     ->where('deleted','0')
                                //     ->where('status','1')
                                //     ->first();

                                $checkifapplied = collect($checkifapplied_all)->where('employeeid', $employeeinfo->id)->where('deduction_typeid', $deductiontype->id)->first();

                                if ($checkifapplied) {
                                    $eachdeduction = \App\Models\HR\HRDeductions::getstandarddeductions($employeeinfo->id, $payrollperiod, $deductiontype->id);
                                    // return collect($eachdeduction);
                                    if ($checkifapplied->eesamount != $eachdeduction->totalamount) {
                                        $deductiontype->amount = floor(($checkifapplied->eesamount / 2) * 100) / 100;
                                        $deductiontype->totalamount = $checkifapplied->eesamount;

                                    } else {
                                        $deductiontype->amount = $eachdeduction->amount;
                                        $deductiontype->totalamount = $eachdeduction->totalamount;
                                    }
                                    $deductiontype->lock = $eachdeduction->lock;
                                    $deductiontype->paidforthismonth = $eachdeduction->paidforthismonth;
                                    // $deductiontype->paymenttype = $eachdeduction->paymenttype;
                                    $deductiontype->paymenttype = 1;
                                    $deductiontype->balances = $eachdeduction->balances;
                                    $deductiontype->paidstatus = $eachdeduction->paidstatus;

                                    if ($deductiontype->amount < 1 && count($deductiontype->balances) == 0) {
                                        if ($basicsalaryinfo->salarybasistype == 5 || $basicsalaryinfo->salarytype == 'Daily') {
                                            array_push($standarddeductions, $deductiontype);
                                        }
                                    } else {
                                        if ($deductiontype->amount > 0) {
                                            array_push($standarddeductions, $deductiontype);
                                        }
                                    }

                                }
                            }
                        }

                        $otherdeductions = collect($otherdeductions_all)->where('employeeid', $employeeinfo->id)->values();
                        $paidotherdeductions = collect($paidotherdeductions_all)->where('employeeid', $employeeinfo->id)->values();

                        $payrolldetails = collect($payrolldetails_all)->where('employeeid', $employeeinfo->id)->values();
                        $paiddeductionsaved = collect($paiddeduction_all)->where('employeeid', $employeeinfo->id)->values();

                        if (count($otherdeductions) > 0) {
                            foreach ($otherdeductions as $otherdeduction) {
                                $totalotherdeductionpaid = 0;
                                foreach ($payrolldetails as $payrolldetail) {
                                    if ($payrolldetail->particularid == $otherdeduction->id) {
                                        $totalotherdeductionpaid += $payrolldetail->amountpaid;
                                    }
                                }
                                $otherdeduction->totalotherdeductionpaid = $totalotherdeductionpaid + $otherdeduction->paidamount;
                            }
                        }


                        foreach ($otherdeductions as $otherdeduction) {
                            $remainingamount = $otherdeduction->fullamount - $otherdeduction->totalotherdeductionpaid;
                            $otherdeduction->monthlypayment = $otherdeduction->amount;
                            $otherdeduction->remainingamount = round($remainingamount, 3);

                            if ($otherdeduction->term != 0) {
                                if ($remainingamount < $otherdeduction->amount) {
                                    $otherdeduction->amount = round($remainingamount, 2);
                                    $otherdeduction->paidthispayroll = 1;
                                } else {
                                    $otherdeduction->paidthispayroll = 0;
                                }
                            }

                        }

                        $otherdeductionsarray = array();
                        $paidotherdeductionsarray = array();
                        $lastpayroll = [];
                        $headerarr = [];

                        if (count($paidotherdeductions) > 0) {
                            foreach ($paidotherdeductions as $paideachotherdeduction) {
                                $paideachotherdeduction->particulartype = 2;
                                $paideachotherdeduction->particularid = $paideachotherdeduction->id;
                                $paideachotherdeduction->dataid = $paideachotherdeduction->deductionotherid;
                                $paideachotherdeduction->lock = 0;
                                $paideachotherdeduction->paidstatus = 0;
                                $paideachotherdeduction->paymenttype = null;

                                array_push($paidotherdeductionsarray, $paideachotherdeduction);

                            }
                        }

                        if (count($otherdeductions) > 0) {

                            foreach ($otherdeductions as $eachotherdeduction) {

                                if ($eachotherdeduction->fullamount == $eachotherdeduction->totalotherdeductionpaid && strpos($eachotherdeduction->description, 'PERAA') === false) {

                                } else {
                                    $monthlypayment = $eachotherdeduction->monthlypayment;
                                    $deductionStartDate = new DateTime($eachotherdeduction->startdate);
                                    $formattedStartDate = $deductionStartDate->format('Y-m-d');


                                    if ($formattedStartDate < $payrollperiod->datefrom || $formattedStartDate == $payrollperiod->datefrom || $formattedStartDate == $payrollperiod->dateto || $formattedStartDate == $payrollperiod->dateto) {

                                        $eachotherdeduction->particulartype = 2;
                                        $eachotherdeduction->particularid = $eachotherdeduction->id;
                                        $eachotherdeduction->dataid = $eachotherdeduction->deductionotherid;
                                        $amountpaid = 0;
                                        $eachotherdeduction->paidforthismonth = 0;
                                        // $eachotherdeduction->lock = 0;
                                        $eachotherdeduction->totalamount = 0;
                                        $eachotherdeduction->amounttopay = 0;
                                        $eachotherdeduction->paidstatus = 0;
                                        $eachotherdeduction->paidforthismonth = 0;
                                        $eachotherdeduction->totalpaidamount = $eachotherdeduction->totalotherdeductionpaid;

                                        $paiddeductions = DB::table('hr_payrollv2history')
                                            ->select(DB::raw('SUM(`amountpaid`) as amountpaid'))
                                            ->leftjoin('hr_payrollv2historydetail', 'hr_payrollv2history.id', '=', 'hr_payrollv2historydetail.headerid')
                                            ->leftjoin('hr_payrollv2', 'hr_payrollv2history.payrollid', '=', 'hr_payrollv2.id')
                                            ->where('hr_payrollv2history.employeeid', $employeeinfo->id)
                                            ->where('hr_payrollv2history.released', '1')
                                            ->where('hr_payrollv2history.deleted', '0')
                                            ->where('hr_payrollv2.deleted', '0')
                                            ->where('hr_payrollv2historydetail.particulartype', '2')
                                            ->where('hr_payrollv2historydetail.particularid', $eachotherdeduction->id)
                                            ->where('hr_payrollv2history.payrollid', '<=', $payrollperiod->id)
                                            ->first()->amountpaid;

                                        // determine payroll cutoff if first or second /last
                                        if ($payrollperiod) {
                                            $datefrom_day = date('d', strtotime($payrollperiod->datefrom));
                                            $dateto_day = date('d', strtotime($payrollperiod->dateto));
                                            $payrollyear = date('Y', strtotime($payrollperiod->dateto));
                                            $payrollmonth = date('m', strtotime($payrollperiod->dateto));

                                            if ($datefrom_day == 1 && $dateto_day == 15) {
                                                $cutoff = 1; // first cutoff of the month
                                            } else {
                                                $cutoff = 2; // second cutoff of the month
                                            }
                                        }

                                        if ($cutoff == 2) {
                                            if ($eachotherdeduction->description != 'PERAA') {
                                                $firstpayroll_cutoff = DB::table('hr_payrollv2')
                                                    ->where('deleted', 0)
                                                    ->where('id', '!=', $payrollperiod->id)
                                                    ->whereYear('dateto', $payrollyear)
                                                    ->whereMonth('dateto', $payrollmonth)
                                                    ->first();

                                                $headerid = DB::table('hr_payrollv2history')
                                                    ->where('hr_payrollv2history.employeeid', $employeeinfo->id)
                                                    ->where('deleted', 0)
                                                    ->where(function ($query) use ($payrollperiod, $firstpayroll_cutoff) {
                                                        $query->where('payrollid', $payrollperiod->id)
                                                            ->orWhere('payrollid', $firstpayroll_cutoff->id ?? null);
                                                    })
                                                    ->get();

                                                $headerid = $headerid->pluck('id');
                                                $headerarr = $headerid;
                                                $paid_deductions = DB::table('hr_payrollv2historydetail')
                                                    ->select('hr_payrollv2historydetail.*')
                                                    ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                                                    ->whereIn('headerid', $headerarr)
                                                    ->where('particulartype', 2)
                                                    ->where('particularid', $eachotherdeduction->id)
                                                    ->where('hr_payrollv2.deleted', '0')
                                                    ->where('hr_payrollv2historydetail.employeeid', $employeeinfo->id)
                                                    ->where('hr_payrollv2historydetail.deleted', '0')
                                                    ->where('hr_payrollv2historydetail.deductionid', $eachotherdeduction->deductionotherid)
                                                    ->get();

                                                if ($firstpayroll_cutoff) {
                                                    $firstpayrol = DB::table('hr_payrollv2historydetail')
                                                        ->select('hr_payrollv2historydetail.*')
                                                        ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                                                        ->where('particulartype', 2)
                                                        ->where('hr_payrollv2.deleted', '0')
                                                        ->where('hr_payrollv2historydetail.employeeid', $employeeinfo->id)
                                                        ->where('hr_payrollv2historydetail.payrollid', $firstpayroll_cutoff->id)
                                                        ->where('hr_payrollv2historydetail.paidstatus', 1)
                                                        ->where('hr_payrollv2historydetail.totalamount', '!=', 0)
                                                        ->where('hr_payrollv2historydetail.deleted', '0')
                                                        ->where('hr_payrollv2historydetail.deductionid', $eachotherdeduction->deductionotherid)
                                                        ->first();
                                                }
                                                $firstpayrol = null;
                                                if ($firstpayrol) {
                                                    if ($firstpayrol->paymenttype == 0) {
                                                        $eachotherdeduction->paidforthismonth = 0;
                                                        $eachotherdeduction->paidstatus = 1;
                                                        $eachotherdeduction->paymenttype = 1;

                                                    } else if ($firstpayrol->paymenttype == 1) {
                                                        $remainingamountod = $eachotherdeduction->fullamount - $eachotherdeduction->totalpaidamount;

                                                        if ($eachotherdeduction->remainingamount < (float) $eachotherdeduction->monthlypayment) {
                                                            $eachotherdeduction->paymenttype = 0;
                                                            $eachotherdeduction->amounttopay = $remainingamountod;
                                                            $eachotherdeduction->paidstatus = 1;

                                                        } else {
                                                            $eachotherdeduction->paymenttype = 1;
                                                            $eachotherdeduction->paidstatus = 1;
                                                            $eachotherdeduction->amounttopay = $eachotherdeduction->monthlypayment / 2;
                                                        }
                                                    }
                                                } else {
                                                    if (count($paid_deductions) == 0) {
                                                        $eachotherdeduction->paymenttype = 1;
                                                        $eachotherdeduction->paidstatus = 1;
                                                        $eachotherdeduction->amounttopay = $eachotherdeduction->monthlypayment / 2;
                                                    } else {
                                                        foreach ($paid_deductions as $paiddeduction) {
                                                            //  first cutoff oth the month
                                                            if ($paiddeduction->payrollid == $payrollperiod->id) {
                                                                if ($paiddeduction->paidstatus == 1) {
                                                                    $eachotherdeduction->paidstatus = 1;
                                                                    $amountpaid = $paiddeduction->amountpaid;
                                                                    if ($amountpaid == $eachotherdeduction->amount) {

                                                                        $eachotherdeduction->paymenttype = 0;
                                                                        $eachotherdeduction->amounttopay = $amountpaid;

                                                                    } else if ($amountpaid == floor(($eachotherdeduction->amount / 2) * 100) / 100) {

                                                                        $eachotherdeduction->paymenttype = 1;
                                                                        $eachotherdeduction->amounttopay = $amountpaid;

                                                                    } else {
                                                                        $eachotherdeduction->paymenttype = 0;
                                                                        $eachotherdeduction->amounttopay = $eachotherdeduction->amount;

                                                                    }

                                                                }

                                                            } else {

                                                                $amountpaid = $paiddeduction->amountpaid;

                                                                if ($amountpaid == $eachotherdeduction->amount) {
                                                                    $eachotherdeduction->paidforthismonth = 1;
                                                                    $eachotherdeduction->amounttopay = 0;
                                                                    $eachotherdeduction->paidstatus = 0;
                                                                    $eachotherdeduction->amounttobededuct = 0;
                                                                    $eachotherdeduction->paymenttype = 0;

                                                                } else if ($amountpaid == floor(($eachotherdeduction->amount / 2) * 100) / 100) {
                                                                    $eachotherdeduction->paymenttype = 1;
                                                                    $eachotherdeduction->amounttopay = $amountpaid;
                                                                    $eachotherdeduction->amounttobededuct = $amountpaid;
                                                                    $eachotherdeduction->paidstatus = 1;
                                                                } else {
                                                                    $eachotherdeduction->paymenttype = 0;
                                                                    $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                                                    $eachotherdeduction->amounttobededuct = $eachotherdeduction->amount;
                                                                    $eachotherdeduction->paidstatus = 1;
                                                                }

                                                            }
                                                        }
                                                    }

                                                }



                                                array_push($otherdeductionsarray, $eachotherdeduction);
                                            } else {
                                                $eachotherdeduction->paidforthismonth = 1;
                                                $eachotherdeduction->paidstatus = 0;
                                                $eachotherdeduction->paymenttype = 0;

                                                array_push($otherdeductionsarray, $eachotherdeduction);
                                            }

                                        } else {
                                            $headerid = DB::table('hr_payrollv2history')
                                                ->where('deleted', 0)
                                                ->where('hr_payrollv2history.employeeid', $employeeinfo->id)
                                                ->where('payrollid', $payrollperiod->id)
                                                ->first()->id;

                                            $headerarr[] = $headerid;

                                            // this is to retirieve the paid deductions
                                            $paid_deductions = DB::table('hr_payrollv2historydetail')
                                                ->select('hr_payrollv2historydetail.*')
                                                ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                                                ->whereIn('headerid', $headerarr)
                                                ->where('particulartype', 2)
                                                ->where('particularid', $eachotherdeduction->id)
                                                ->where('hr_payrollv2.deleted', '0')
                                                ->where('hr_payrollv2historydetail.employeeid', $employeeinfo->id)
                                                ->where('hr_payrollv2historydetail.deleted', '0')
                                                ->where('hr_payrollv2historydetail.deductionid', $eachotherdeduction->deductionotherid)
                                                ->get();

                                            if (count($paid_deductions) > 0) {

                                                if ($eachotherdeduction->deductionstatus == 0) {
                                                    // if deduction is need to be paid per kinsina
                                                    $eachotherdeduction->paymenttype = 1; // 0 = full; 1 = half;

                                                    foreach ($paid_deductions as $paiddeduction) {

                                                        //  first cutoff oth the month
                                                        if ($paiddeduction->payrollid == $payrollperiod->id) {


                                                            if ($paiddeduction->paidstatus == 1) {

                                                                $eachotherdeduction->paidstatus = 1;

                                                                if ($eachotherdeduction->remainingamount == $monthlypayment / 2) {
                                                                    $eachotherdeduction->paymenttype = 0;
                                                                    $eachotherdeduction->amounttopay = $eachotherdeduction->remainingamount;
                                                                    $eachotherdeduction->paidforthismonth = 1;
                                                                } else if ($eachotherdeduction->remainingamount <= $monthlypayment / 2) {
                                                                    $eachotherdeduction->paymenttype = 0;
                                                                    $eachotherdeduction->amounttopay = $eachotherdeduction->remainingamount;
                                                                    $eachotherdeduction->paidforthismonth = 1;
                                                                } else {
                                                                    $amountpaid = $paiddeduction->amountpaid;
                                                                    if ($amountpaid == $eachotherdeduction->amount) {
                                                                        $eachotherdeduction->paymenttype = 0;
                                                                        $eachotherdeduction->amounttopay = $amountpaid;
                                                                        $eachotherdeduction->paidforthismonth = 1;

                                                                    } else if ($amountpaid == floor(($eachotherdeduction->amount / 2) * 100) / 100) {

                                                                        $eachotherdeduction->paymenttype = 1;
                                                                        $eachotherdeduction->amounttopay = $amountpaid;

                                                                    } else {
                                                                        $eachotherdeduction->paymenttype = 3;
                                                                        $eachotherdeduction->amounttopay = $amountpaid;

                                                                    }
                                                                }




                                                            } else {

                                                                // wala nabayran or iya gi unselect

                                                            }
                                                        }
                                                    }

                                                } else {

                                                    // if deduction is need to be paid per binulan
                                                    $eachotherdeduction->paidstatus = 1; // 0 = full; 1 = half;
                                                    $eachotherdeduction->paymenttype = 0; // 0 = full; 1 = half;
                                                    $eachotherdeduction->amounttopay = $eachotherdeduction->amount;

                                                }

                                            } else {

                                                if ($eachotherdeduction->deductionstatus == 0) {
                                                    // if deduction is need to be paid per kinsina
                                                    $eachotherdeduction->paymenttype = 1; // 0 = full; 1 = half;
                                                    $eachotherdeduction->paidstatus = 1;
                                                    $eachotherdeduction->amounttopay = floor(($eachotherdeduction->amount / 2) * 100) / 100;
                                                } else {
                                                    // if deduction is need to be paid per binulan
                                                    $eachotherdeduction->paidstatus = 1; // 0 = full; 1 = half;
                                                    $eachotherdeduction->paymenttype = 0; // 0 = full; 1 = half;
                                                    $eachotherdeduction->amounttopay = $eachotherdeduction->amount;

                                                }
                                            }

                                            array_push($otherdeductionsarray, $eachotherdeduction);


                                        }
                                    }
                                }

                            }
                        }

                        // $otherdeductions = $otherdeductionsarray + $paidotherdeductionsarray;

                        $otherdeductions = array_merge($otherdeductionsarray, $paidotherdeductionsarray);
                        if (count($standardallowances) > 0) {
                            foreach ($standardallowances as $standardallowance) {
                                // if ($standardallowance->amountbaseonsalary == 1) {
                                //     $standardallowance->paidstatus = 1;
                                //     $standardallowance->paymenttype = 0;
                                // } else {
                                $standardallowance->paidstatus = 1;
                                $standardallowance->paymenttype = 1;
                                // }

                            }
                        }

                        $otheraddedearnings = collect($otheraddedearnings_all)->where('employeeid', $employeeinfo->id)->values();
                        $otherids = $otheraddedearnings->pluck('id');
                        $notdeletedearningsall = collect($otheraddedearningsparticulars_all)->where('employeeid', $employeeinfo->id)->where('deleted', 0)->where('additionalid', '!=', 0)->whereNotIn('additionalid', $otherids)->values();


                        $deletedparticularsdetails = DB::table('hr_payrollv2historydetail')
                            ->where('employeeid', $employeeinfo->id)
                            ->where('payrollid', $checkifexistsid)
                            ->where('particulartype', null)
                            ->where('deleted', 1)
                            ->get();

                        // return $deletedparticularsdetails;

                        $deletedEntries = $deletedparticularsdetails->map(function ($item) {

                            return [
                                'description' => $item->description,
                                'employeeid' => $item->employeeid,
                                'payrollid' => $item->payrollid
                            ];
                        })->toArray();

                        $notdeletedearnings = $notdeletedearningsall->filter(function ($item) use ($deletedEntries) {
                            foreach ($deletedEntries as $deletedItem) {
                                if (
                                    $item->description === $deletedItem['description'] &&
                                    $item->employeeid === $deletedItem['employeeid'] &&
                                    $item->payrollid === $deletedItem['payrollid']
                                ) {
                                    return false;
                                }
                            }
                            return true;
                        });
                        // $otheraddedearnings = $notdeletedearnings->merge($otheraddedearnings);
                        // foreach ($otheraddedearnings as $otheraddedearning) {
                        //     foreach ($notdeletedearnings as $notdeletedearning) {
                        //         if ($otheraddedearning->description == $notdeletedearning->description) {

                        //         }
                        //     }
                        // }

                        if (count($otheraddedearnings) > 0) {
                            foreach ($otheraddedearnings as $otheraddedearning) {
                                $otheraddedearning->dataid = $otheraddedearning->type;
                                $otheraddedearning->paidstatus = 1;
                                $otheraddedearning->paymenttype = 0;
                                $otheraddedearning->totalamount = $otheraddedearning->amount;
                                $otheraddedearning->particulartype = null;
                                $otheraddedearning->particularid = null;
                            }
                        }

                        if (count($otheraddedearnings) > 0) {
                            $otheraddedearningsparticulars = collect($otheraddedearningsparticulars_all)->where('employeeid', $employeeinfo->id)->where('additionalid', 0)->values();
                        } else {
                            $otheraddedearningsparticulars = collect($otheraddedearningsparticulars_all)->where('employeeid', $employeeinfo->id)->values();
                        }


                        $otheraddedearningsparticulars = $otheraddedearningsparticulars->filter(function ($item) use ($deletedEntries) {
                            foreach ($deletedEntries as $deletedItem) {
                                if (
                                    $item->description === $deletedItem['description'] &&
                                    $item->employeeid === $deletedItem['employeeid'] &&
                                    $item->payrollid === $deletedItem['payrollid']
                                ) {
                                    return false;
                                }
                            }
                            return true;
                        });


                        if (count($otheraddedearningsparticulars) > 0) {
                            foreach ($otheraddedearningsparticulars as $otheraddedearningsparticular) {
                                $otheraddedearningsparticulars->dataid = $otheraddedearningsparticular->type;
                                $otheraddedearningsparticulars->paidstatus = 1;
                                $otheraddedearningsparticulars->paymenttype = 0;
                                $otheraddedearningsparticulars->particulartype = null;
                                $otheraddedearningsparticulars->particularid = null;

                            }
                        }

                        $otheraddeddeductionsparticulars = collect($otheraddeddeductionsparticulars_all)->where('employeeid', $employeeinfo->id)->where('additionalid', 0)->values();

                        // return $otheraddeddeductionsparticulars;
                        if (count($otheraddeddeductionsparticulars) > 0) {
                            foreach ($otheraddeddeductionsparticulars as $otheraddeddeductionsparticular) {
                                $otheraddeddeductionsparticulars->dataid = $otheraddeddeductionsparticular->type;
                                $otheraddeddeductionsparticulars->paidstatus = 1;
                                $otheraddeddeductionsparticulars->paymenttype = 0;
                                $otheraddeddeductionsparticulars->particulartype = null;
                                $otheraddeddeductionsparticulars->particularid = null;
                            }
                        }

                        // return $otheraddeddeductionsparticulars;
                        // other added Deductions
                        $otheraddeddeductions = collect($otheraddeddeductions_all)->where('employeeid', $employeeinfo->id)->values();
                        if (count($otheraddeddeductions) > 0) {
                            foreach ($otheraddeddeductions as $otheraddeddeduction) {
                                $otheraddeddeduction->dataid = $otheraddeddeduction->type;
                                // $otheraddeddeduction->paidstatus = 1;
                                $otheraddeddeduction->paymenttype = 0;
                                $otheraddeddeduction->totalamount = $otheraddeddeduction->amount;

                                $otheraddeddeduction->particulartype = null;
                                $otheraddeddeduction->particularid = null;
                            }
                        }

                        if (count($leavedetails) > 0) {
                            foreach ($leavedetails as $leavedetail) {
                                $leaveamounttotal += $leavedetail->amount;
                            }
                        }


                        if (count($standardallowances) > 0) {
                            foreach ($standardallowances as $standardallowance) {
                                // if ($standardallowance->amountbaseonsalary == 1) {
                                //     $standardallowancesamount += $standardallowance->totalamount;

                                // } else if ($standardallowance->baseonattendance == 1) {
                                //     $standardallowancesamount += $standardallowance->amount;
                                //     $standardallowance->paymenttype = 0;

                                // } else {
                                $standardallowancesamount += $standardallowance->totalamount / 2;
                                // }
                            }
                        }

                        if (count($otheraddedearningsparticulars) > 0) {
                            foreach ($otheraddedearningsparticulars as $otheraddedearningsparticular) {
                                $otheraddedearningsparticularsamount += $otheraddedearningsparticular->amount;
                            }
                        }


                        if (count($otheraddedearnings) > 0) {
                            foreach ($otheraddedearnings as $otheraddedearning) {
                                $otheraddedearningsamount += $otheraddedearning->amount;
                            }
                        }
                        // return $otheraddedearnings;

                        // return $otheraddedearningsparticulars;

                        if (count($standarddeductions) > 0) {
                            foreach ($standarddeductions as $standarddeduction) {
                                $standarddeductionsamount += floor(($standarddeduction->totalamount / 2) * 100) / 100;
                                // $standarddeductionsamount += ($standarddeduction->totalamount / 2);
                            }
                        }
                        // return $standarddeductions;
                        if (count($otherdeductions) > 0) {
                            foreach ($otherdeductions as $otherdeduction) {
                                $otherdeduction->totalamount = $otherdeduction->amount;

                                if ($otherdeduction->paidstatus == 1) {
                                    // if ($otherdeduction->description == 'PERAA') {
                                    if ($otherdeduction->deductionstatus == 1) {

                                        $otherdeductionsamount += $otherdeduction->amount;
                                    } else if ($otherdeduction->deductionstatus == 0 && $otherdeduction->paymenttype == 0) {

                                        $otherdeductionsamount += floor(($otherdeduction->amount) * 100) / 100;
                                    } else {
                                        $otherdeductionsamount += floor(($otherdeduction->amount / 2) * 100) / 100;
                                    }
                                }

                            }
                        }

                        if (count($otheraddeddeductionsparticulars) > 0) {
                            foreach ($otheraddeddeductionsparticulars as $otheraddeddeductionsparticular) {
                                // return 'collect($otheraddeddeductionsparticular)';
                                $otheraddeddeductionsparticularsamount += $otheraddeddeductionsparticular->amount;
                            }
                        }
                        if (count($otheraddeddeductions) > 0) {
                            foreach ($otheraddeddeductions as $otheraddeddeduction) {
                                $otheraddeddeductionsamount += $otheraddeddeduction->amount;
                            }
                        }

                        if ($employeeinfo->departmentid == 6) {
                            $particulars = array_merge($standardallowances->toArray(), $otherdeductions, $otheraddedearnings->toArray(), $otheraddeddeductions->toArray());

                        } else {
                            $particulars = array_merge($standardallowances->toArray(), $standarddeductions, $otherdeductions, $otheraddedearnings->toArray(), $otheraddeddeductions->toArray());
                        }

                        // return $particulars;
                        // $particulars = array_merge($standardallowances->toArray(), $standarddeductions, $otherdeductions, $otheraddedearnings->toArray(), $otheraddeddeductions->toArray());
                        // $totalearningamount = $standardallowancesamount + $otheraddedearningsamount + ( $basicsalaryinfo->amount / 2 ) + $totalPresentHolidaypay;
                        // $totalearningamount = $standardallowancesamount + $otheraddedearningsamount + $totalPresentHolidaypay; mao ni ang naay holiday
                        if ($otheraddedearningsamount > 0) {
                            $otheraddedearningsamount = round($otheraddedearningsamount, 2);
                        }
                        if ($otheraddedearningsparticularsamount > 0) {
                            $otheraddedearningsparticularsamount = round($otheraddedearningsparticularsamount, 2);
                        }

                        // return $standardallowancesamount;
                        $totalearningamount = $standardallowancesamount + $otheraddedearningsamount + $otheraddedearningsparticularsamount;

                        // Check if $basicsalaryinfo->amount is not empty or null before adding it to the total
                        if (!empty($basicsalaryinfo->amount)) {
                            $totalearningamount += floor(($basicsalaryinfo->amount / 2) * 100) / 100;
                        }


                        if ($standarddeductionsamount > 0) {
                            // $standarddeductionsamount = floor($standarddeductionsamount * 100) / 100;
                            if ($employeeinfo->departmentid == 6) {
                                $standarddeductionsamount = 0;
                            } else {
                                $standarddeductionsamount = $standarddeductionsamount;
                            }
                        }

                        if ($otherdeductionsamount > 0) {
                            $otherdeductionsamount = $otherdeductionsamount;
                        }
                        // return $particulars;

                        if ($totalUndertimeAmount > 0) {
                            $totalUndertimeAmount = $totalUndertimeAmount;
                        }
                        // return $totalUndertimeAmount;


                        if ($employeeinfo->departmentid == null) {
                            $totalLateAmount = 0;

                        } else {

                            $tardyonattendance = DB::table('hr_tardinesscomp')
                                ->where('departmentid', $employeeinfo->departmentid)
                                ->first();

                            if ($tardyonattendance) {
                                if ($tardyonattendance->isactive == 1) {
                                    if ($totalLateAmount > 0) {
                                        $totalLateAmount = round($totalLateAmount, 2);
                                    }
                                } else {
                                    $totalLateAmount = 0;
                                    $totalLateAmount = 0;
                                    $totalLateMin = 0;
                                }
                            } else {
                                $totalLateAmount = 0;
                                $totalLateAmount = 0;
                                $totalLateMin = 0;
                            }


                        }
                        // return $employeeinfo->departmentid;

                        if ($otheraddeddeductionsamount > 0) {
                            $otheraddeddeductionsamount = $otheraddeddeductionsamount;
                        }
                        if ($otheraddeddeductionsparticularsamount > 0) {
                            $otheraddeddeductionsparticularsamount = $otheraddeddeductionsparticularsamount;
                        }

                        if ($totalabsentamounttotal > 0) {
                            $totalabsentamounttotal = bcdiv($totalabsentamounttotal, 1, 2);
                        }

                        // return $emergencyloadtardyamount;
                        $totaldeductionamount = round(($standarddeductionsamount + $otherdeductionsamount + $totalUndertimeAmount + $totalLateAmount + $otheraddeddeductionsamount + $totalabsentamounttotal + $otheraddeddeductionsparticularsamount + $regulardloadtardyamount + $overloadtardyamount + $emergencyloadtardyamount), 2);

                        if ($totalearningamount > 0) {
                            $totalearningamount = round($totalearningamount, 2);
                        }
                        // if ($totaldeductionamount > 0) {
                        //     $totaldeductionamount = floor($totaldeductionamount * 100) / 100;
                        // }
                        // return $basicsalaryinfo->amountperhour;
                        $netsalaryamount = round($totalearningamount - $totaldeductionamount, 2);

                        $employeeinfo->lateamounttotal = $totalLateAmount;
                        $employeeinfo->latemintotal = $totalLateMin;
                        $employeeinfo->undertimeamounttotal = $totalUndertimeAmount;
                        $employeeinfo->undertimemintotal = $totalUndertimeMin;
                        $employeeinfo->workinghourstotal = $totalhourworks;
                        $employeeinfo->amountperday = number_format($basicsalaryinfo->amountperday, 2);
                        $employeeinfo->amountperhour = round($basicsalaryinfo->amountperhour, 2);
                        $employeeinfo->presentdays = $totalpresentdays + $saturdayCount;
                        $employeeinfo->absentdays = $totalabsentdays;
                        $employeeinfo->totalabsentamount = $totalabsentamounttotal;
                        $employeeinfo->totalearning = $totalearningamount;
                        $employeeinfo->totaldeduction = $totaldeductionamount;
                        $employeeinfo->netsalary = $netsalaryamount;

                        $checkhistoryifexists = collect($checkhistoryifexists_all)->where('payrollid', $checkifexistsid)->where('employeeid', $employeeinfo->id)->first();

                        // $checkhistoryifexists = DB::table('hr_payrollv2history')
                        //     ->where('payrollid', $payrollinseredid)
                        //     ->where('employeeid', $employeeinfo->id)
                        //     ->where('deleted','0')
                        //     ->first();

                        // return collect($employeeinfo);
                        // return $regulardloadtardyamount;

                        if (!$checkhistoryifexists) {
                            $basicSalaryAmount = $basicsalaryinfo->amount ?? 0;
                            if ($basicSalaryAmount > 0) {
                                $basicSalaryAmount = $basicSalaryAmount / 2;
                            }
                            $headerid = DB::table('hr_payrollv2history')
                                ->insertGetId([
                                    'dailyrate' => $basicsalaryinfo->amountperday ?? null,
                                    'payrollid' => $checkifexistsid,
                                    'employeeid' => $employeeinfo->id,
                                    'lateminutes' => $employeeinfo->latemintotal,
                                    'lateamount' => $employeeinfo->lateamounttotal,
                                    'undertimeminutes' => $employeeinfo->undertimemintotal,
                                    'undertimeamount' => $employeeinfo->undertimeamounttotal,
                                    'totalhoursworked' => $employeeinfo->workinghourstotal,
                                    'totalearning' => $employeeinfo->totalearning,
                                    'totaldeduction' => $employeeinfo->totaldeduction,
                                    'amountperday' => $employeeinfo->amountperday,
                                    'presentdays' => $employeeinfo->presentdays,
                                    'absentdays' => $employeeinfo->absentdays,
                                    'daysabsentamount' => $employeeinfo->totalabsentamount,
                                    'basicsalaryamount' => floor($basicSalaryAmount * 100) / 100,
                                    'netsalary' => $employeeinfo->netsalary,
                                    'basicsalarytype' => $basicsalaryinfo->salarytype ?? null,
                                    'monthlysalary' => $basicsalaryinfo->amount ?? null,
                                    'clregularloadamount' => null,
                                    'cloverloadloadamount' => null,
                                    'clparttimeloadamount' => null,
                                    'regulartardyamount' => $regulardloadtardyamount,
                                    'overloadtardyamount' => $overloadtardyamount,
                                    'emergencyloadtardyamount' => $emergencyloadtardyamount,
                                    'configured' => 1,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => date('Y-m-d H:i:s')
                                ]);

                            // return 1;
                            // if(count($leaves)>0)
                            // { 
                            //     foreach ($leaves as $eachleave) {
                            //         DB::table('hr_payrollv2historydetail')
                            //             ->insert([
                            //                 'payrollid'          => $payrollid,
                            //                 'employeeid'         => $employeeid,
                            //                 'headerid'           => $headerid,
                            //                 'description'        => $eachleave->description,
                            //                 'totalamount'        => str_replace(',', '', $eachleave->totalamount),
                            //                 'amountpaid'         => str_replace(',', '', $eachleave->amountpaid),
                            //                 'totalpaidamount'    => isset($eachleave->totalamountpaid) ? $eachleave->totalamountpaid : null,
                            //                 'days'               => 1,
                            //                 'particularid'       => $eachleave->particularid,
                            //                 'deductionid'        => ($eachleave->particulartype == 2) ? $eachleave->dataid : null,
                            //                 'employeeleaveid'    => $eachleave->employeeleaveid ?? 0,
                            //                 'leavedateid'        => $eachleave->ldateid ?? 0,
                            //                 'createdby'          => auth()->user()->id,
                            //                 'createddatetime'    => date('Y-m-d H:i:s')
                            //             ]);
                            //     }
                            // }

                            $paymenttypes = 0;
                            if (count($particulars) > 0) {
                                foreach ($particulars as $particular) {
                                    if ($particular->paidstatus == 1) {
                                        switch ($particular->particulartype) {
                                            case 1:
                                                $amountpaid = $particular->amount / 2;
                                                if ($particular->paymenttype == 0) {
                                                    $paymenttypes = 1;
                                                } else {
                                                    $paymenttypes = $particular->paymenttype;
                                                }

                                                break;
                                            case 2:
                                                // if ($particular->description == 'PERAA' || $particular->deductionstatus == 1) {
                                                //     $amountpaid = $particular->amount;
                                                //     if ($particular->paymenttype == 0) {
                                                //         $paymenttypes = 0;
                                                //     }
                                                // } else {
                                                $amountpaid = $particular->amount / 2;
                                                if ($particular->paymenttype == 0) {
                                                    $paymenttypes = 1;
                                                } else {
                                                    $paymenttypes = $particular->paymenttype;
                                                }
                                                // }

                                                break;
                                            case 3:
                                                if ($particular->amountbaseonsalary == 1) {
                                                    $amountpaid = $particular->amount;
                                                    $paymenttypes = 2;
                                                } else {
                                                    $amountpaid = $particular->amount / 2;
                                                    if ($particular->paymenttype == 0) {
                                                        $paymenttypes = 1;
                                                    } else {
                                                        $paymenttypes = $particular->paymenttype;
                                                    }
                                                }
                                            case NULL:
                                                // $amountpaid = $particular->amount / 2;
                                                $amountpaid = $particular->amount;

                                            // Add more cases if needed
                                            default:
                                            // Handle the default case if particulartype doesn't match any of the specified cases
                                            // break;
                                        }


                                        $detailid = DB::table('hr_payrollv2historydetail')
                                            ->insertGetId([
                                                'payrollid' => $checkifexistsid,
                                                'employeeid' => $employeeinfo->id,
                                                'headerid' => $headerid,
                                                'description' => $particular->description,
                                                'totalamount' => floor($particular->amount * 100) / 100,
                                                'amountpaid' => floor($amountpaid * 100) / 100,
                                                'paymenttype' => $paymenttypes,
                                                'totalpaidamount' => isset($particular->totalamountpaid) ? floor($particular->totalamountpaid * 100) / 100 : null,
                                                'particulartype' => $particular->particulartype,
                                                'particularid' => $particular->particularid,
                                                'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                                'createdby' => auth()->user()->id,
                                                'createddatetime' => date('Y-m-d H:i:s'),
                                                'paidstatus' => 1

                                            ]);

                                        // $detailid = DB::table('hr_payrollv2historydetail')
                                        //     ->insertGetId([
                                        //         'payrollid'             => $payrollid,
                                        //         'employeeid'            => $employeeinfo->,
                                        //         'headerid'              => $headerid,
                                        //         'description'           => $particular->description,
                                        //         'totalamount'           => str_replace( ',', '', $particular->totalamount),
                                        //         'amountpaid'           => str_replace( ',', '', $particular->amountpaid),
                                        //         'paymenttype'           => $particular->paymenttype,
                                        //         'totalpaidamount'           => isset($particular->totalamountpaid) ? $particular->totalamountpaid : null,
                                        //         'particulartype'           => $particular->particulartype,
                                        //         'particularid'           => $particular->particularid,
                                        //         'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                        //         'createdby'             => auth()->user()->id,
                                        //         'createddatetime'       => date('Y-m-d H:i:s'),
                                        //         'paidstatus'       => isset($particular->particulartype) && ($particular->particulartype == 1 || $particular->particulartype == 2 || $particular->particulartype == 3) ? $particular->paidstatus : 0

                                        //     ]);

                                        // $balance = $particular->totalamount - $particular->amountpaid;
                                        // if($balance>0.00)
                                        // {
                                        //     DB::table('hr_payrollv2balance')
                                        //     ->insert([
                                        //         'payrollid'             => $payrollid,
                                        //         'detailid'            => $detailid,
                                        //         'balance'              => str_replace( ',', '', $balance),
                                        //         'createdby'             => auth()->user()->id,
                                        //         'createddatetime'       => date('Y-m-d H:i:s')
                                        //     ]);
                                        // }
                                    }

                                }
                            }
                            if (count($particulars) > 0) {
                                foreach ($particulars as $eachparticular) {
                                    if ($eachparticular->particulartype == null && !isset($eachparticular->additionalid)) {
                                        DB::table('hr_payrollv2addparticular')
                                            ->insert([
                                                'payrollid' => $checkifexistsid,
                                                'employeeid' => $employeeinfo->id,
                                                'headerid' => $headerid,
                                                'description' => $eachparticular->description,
                                                'amount' => (float) $eachparticular->amount,
                                                'type' => $eachparticular->type,
                                                'createdby' => auth()->user()->id,
                                                'createddatetime' => date('Y-m-d H:i:s'),
                                                'additionalid' => $eachparticular->id
                                            ]);
                                    }

                                }
                            }
                        } else {

                            $basicSalaryAmount = $basicsalaryinfo->amount ?? 0;
                            if ($basicSalaryAmount > 0) {
                                $basicSalaryAmount = $basicSalaryAmount / 2;
                            }


                            DB::table('hr_payrollv2history')
                                ->where('id', $checkhistoryifexists->id)
                                ->update([
                                    'dailyrate' => $basicsalaryinfo->amountperday ?? null,
                                    'daysabsentamount' => $employeeinfo->totalabsentamount,
                                    'lateminutes' => $employeeinfo->latemintotal,
                                    'lateamount' => $employeeinfo->lateamounttotal,
                                    'undertimeminutes' => $employeeinfo->undertimemintotal,
                                    'undertimeamount' => $employeeinfo->undertimeamounttotal,
                                    'totalhoursworked' => $employeeinfo->workinghourstotal,
                                    'totalhoursworked' => $employeeinfo->workinghourstotal,
                                    'totalearning' => $employeeinfo->totalearning,
                                    'totaldeduction' => $employeeinfo->totaldeduction,
                                    'amountperday' => $employeeinfo->amountperday,
                                    'presentdays' => $employeeinfo->presentdays,
                                    'absentdays' => $employeeinfo->absentdays,
                                    'basicsalaryamount' => floor($basicSalaryAmount * 100) / 100,
                                    'netsalary' => $employeeinfo->netsalary,
                                    'basicsalarytype' => $basicsalaryinfo->salarytype ?? null,
                                    'clregularloadamount' => null,
                                    'cloverloadloadamount' => null,
                                    'clparttimeloadamount' => null,
                                    'regulartardyamount' => $regulardloadtardyamount,
                                    'overloadtardyamount' => $overloadtardyamount,
                                    'emergencyloadtardyamount' => $emergencyloadtardyamount,
                                    'monthlysalary' => $basicsalaryinfo->amount ?? null,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => date('Y-m-d H:i:s')
                                ]);

                            $updatedRecord = DB::table('hr_payrollv2history')->where('id', $checkhistoryifexists->id)->first();

                            // Now $updatedRecord contains the updated record
                            $updatedRecordId = $updatedRecord->id;
                            // $otheraddedearningsparticulars = collect($otheraddedearningsparticulars_all)->where('employeeid', $employeeinfo->id)->where('additionalid', 0)->values();
                            $otheraddedearningsparticulars = collect($otheraddedearningsparticulars_all)->where('employeeid', $employeeinfo->id)->values();
                            $otheraddedearnings = collect($otheraddedearnings_all)->where('employeeid', $employeeinfo->id)->values();

                            // return $otheraddedearnings;
                            // if($tardinessamount == 'NaN')
                            // {
                            //     $tardinessamount = 0;;
                            // }

                            // DB::table('hr_payrollv2history')
                            //     ->where('id', $checkhistoryifexists->id)
                            //     ->update([
                            //         'dailyrate'           => $amountperday,
                            //         'daysabsentamount'           => $amountabsent,
                            //         'lateminutes'           => $lateminutes,
                            //         'lateamount'            => $amountlate,
                            //         'undertimeminutes'      => $undertimeminutes,
                            //         'undertimeamount'      => $amountundertime,
                            //         'totalhoursworked'      => $totalworkedhours,
                            //         'totalearning'          => $totalearnings,
                            //         'totaldeduction'          => $totaldeductions,
                            //         'amountperday'          => $amountperday,
                            //         'presentdays'           => $request->get('dayspresent'),
                            //         'absentdays'            => $request->get('daysabsent'),
                            //         'basicsalaryamount'     => str_replace( ',', '', $request->get('basicsalary')),
                            //         'netsalary'             => str_replace( ',', '', $request->get('netsalary')),
                            //         'basicsalarytype'       => $request->get('salarytype'),
                            //         'clregularloadamount'       => $request->get('regularloadamount'),
                            //         'cloverloadloadamount'       => $request->get('overloadloadamount'),
                            //         'clparttimeloadamount'       => $request->get('parttimeloadamount'),
                            //         'monthlysalary'         => $monthlysalary,
                            //         'updatedby'             => auth()->user()->id,
                            //         'updateddatetime'       => date('Y-m-d H:i:s')
                            //     ]);

                            // if(count($leaves)>0)
                            // {
                            //     foreach($leaves as $eachleave)
                            //     {
                            //         DB::table('hr_payrollv2historydetail')
                            //         ->insert([
                            //             'payrollid'             => $payrollid,
                            //             'employeeid'            => $employeeid,
                            //             'headerid'              => $checkhistoryifexists->id,
                            //             'description'           => $eachleave->description,
                            //             'totalamount'           => str_replace( ',', '', $eachleave->totalamount),
                            //             'amountpaid'           => str_replace( ',', '', $eachleave->amountpaid),
                            //             // 'paymenttype'           => $particular->paymenttype,
                            //             // 'particulartype'           => $particular->particulartype,
                            //             'days'                  => 1,
                            //             'particularid'           => $eachleave->particularid,
                            //             'employeeleaveid'           => $eachleave->particularid,
                            //             'leavedateid'           => $eachleave->ldateid,
                            //             'createdby'             => auth()->user()->id,
                            //             'createddatetime'       => date('Y-m-d H:i:s')
                            //         ]);
                            //     }
                            // }

                            if (count($particulars) == 0) {
                                // return 'yyy';
                                // DB::table('hr_payrollv2historydetail')
                                //     ->where('headerid', $checkhistoryifexists->id)
                                //     ->where('type', 0)
                                //     ->update([
                                //         'deleted'               => 1,
                                //         'deletedby'             => auth()->user()->id,
                                //         'deleteddatetime'       => date('Y-m-d H:i:s')
                                //     ]);
                            } else {
                                // return $particulars;

                                // $allstandarddeductions = DB::table('hr_payrollv2historydetail')
                                //     ->where('payrollid', $payrollid)
                                //     ->where('employeeid', $employeeid)
                                //     ->where('particulartype', 1)
                                //     ->where('deleted',0)
                                //     ->get();

                                // $allotherdeductions = DB::table('hr_payrollv2historydetail')
                                //     ->where('payrollid', $payrollid)
                                //     ->where('employeeid', $employeeid)
                                //     ->where('particulartype', 2)
                                //     ->where('deleted',0)
                                //     ->get();

                                // $allstandardallowances = DB::table('hr_payrollv2historydetail')
                                //     ->where('payrollid', $payrollid)
                                //     ->where('employeeid', $employeeid)
                                //     ->where('particulartype', 3)
                                //     ->where('deleted',0)
                                //     ->get();
                                // $allpartsdeleted = DB::table('hr_payrollv2historydetail')
                                //     ->where('headerid', $checkhistoryifexists->id)
                                //     ->where('payrollid', $checkifexistsid)
                                //     // ->where('description','!=', 'Honorarium')
                                //     ->where('type','0')
                                //     ->where('deleted','0')
                                //     ->get();
                                // return $allpartsdeleted;
                                $alladdedparticulars = DB::table('hr_payrollv2addparticular')
                                    ->where('payrollid', $checkifexistsid)
                                    ->where('employeeid', $employeeinfo->id)
                                    ->where('deleted', '0')
                                    ->get();

                                $allnotdeletedaddedparticulars = collect($particulars)
                                    ->where('particulartype', null)
                                    ->where('particularid', null)
                                    // ->where('description', 'COLLEGE OVERLOAD')
                                    ->where('deleted', 0)
                                    ->values();

                                foreach ($allnotdeletedaddedparticulars as $notDeletedParticular) {
                                    // Find all added particulars with the same description
                                    $matchingAddedParticulars = $alladdedparticulars->filter(function ($addedParticular) use ($notDeletedParticular) {
                                        return $addedParticular->description === $notDeletedParticular->description;
                                    });

                                    // If there's more than one match, compare amounts
                                    foreach ($matchingAddedParticulars as $addedParticular) {

                                        if ($notDeletedParticular->amount != $addedParticular->amount && $notDeletedParticular->id != $addedParticular->additionalid) {
                                            // Update the one with a mismatched amount to deleted
                                            DB::table('hr_payrollv2addparticular')
                                                ->where('id', $addedParticular->id)
                                                ->update(['deleted' => 1]);
                                        }
                                    }
                                }

                                $allparts = DB::table('hr_payrollv2historydetail')
                                    ->where('headerid', $checkhistoryifexists->id)
                                    // ->where('description','!=', 'Honorarium')
                                    ->where('type', '0')
                                    ->where('deleted', '0')
                                    ->get();

                                $allpartsnotod = DB::table('hr_payrollv2historydetail')
                                    ->where('headerid', $checkhistoryifexists->id)
                                    ->where('particulartype', null)
                                    ->where('type', '0')
                                    ->where('deleted', '0')
                                    ->get();

                                if (count($allparts) == 0) {
                                    foreach ($particulars as $particular) {
                                        if ($particular->paidstatus == 1) {
                                            $type = 0;
                                            if ($particular->particularid) {
                                                $type = $particular->particulartype;
                                            }

                                            $detailid = DB::table('hr_payrollv2historydetail') // xcv
                                                ->insertGetId([
                                                    'payrollid' => $checkifexistsid,
                                                    'employeeid' => $employeeinfo->id,
                                                    'headerid' => $updatedRecordId,
                                                    'description' => $particular->description,
                                                    'totalamount' => floor($particular->amount * 100) / 100,
                                                    'amountpaid' => floor($particular->totalamount * 100) / 100,
                                                    'paymenttype' => $particular->paymenttype,
                                                    'totalpaidamount' => isset($particular->totalamountpaid) ? floor($particular->totalamountpaid * 100) / 100 : null,
                                                    'particulartype' => $particular->particulartype,
                                                    'particularid' => $particular->particularid,
                                                    'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                                    'createdby' => auth()->user()->id,
                                                    'createddatetime' => date('Y-m-d H:i:s'),
                                                    'paidstatus' => 1

                                                ]);
                                        }
                                    }

                                } else {
                                    foreach ($particulars as $particular) {
                                        if (property_exists($particular, 'paidstatus') && $particular->paidstatus == 1) {
                                            switch ($particular->particulartype) {
                                                case 1:
                                                    $amountpaid = $particular->totalamount / 2;

                                                    if ($particular->paymenttype == 0) {
                                                        $paymenttypes = 0;
                                                    } else {
                                                        $paymenttypes = $particular->paymenttype;
                                                    }
                                                    break;

                                                case 2:
                                                    if ($particular->paymenttype == 0) {
                                                        $paymenttypes = 0;
                                                        $amountpaid = $particular->totalamount;
                                                    } else {
                                                        $paymenttypes = $particular->paymenttype;
                                                        $amountpaid = $particular->totalamount / 2;

                                                    }
                                                    break;
                                                case 3:
                                                    if ($particular->amountbaseonsalary == 1) {
                                                        $amountpaid = $particular->amount;
                                                        $paymenttypes = 2;
                                                    } else {
                                                        if ($particular->paymenttype == 0) {
                                                            $paymenttypes = 0;
                                                            $amountpaid = $particular->totalamount / 2;
                                                        } else {
                                                            $paymenttypes = $particular->paymenttype;
                                                            $amountpaid = $particular->totalamount / 2;
                                                        }
                                                    }

                                                case NULL:
                                                    $amountpaid = $particular->amount;
                                                    $paymenttypes = $particular->paymenttype;

                                                // Add more cases if needed
                                                default:
                                                // Handle the default case if particulartype doesn't match any of the specified cases
                                                // break;
                                            }
                                            // return $amountpaid;
                                            $type = 0;
                                            if ($particular->particularid) {
                                                $type = $particular->particulartype;
                                            }
                                            // dd(collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->where('description', $particular->description)->values());
                                            if (collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->where('description', $particular->description)->count() > 0) {
                                                // return collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->where('description', 'PERAA')->values();
                                                // DB::table('hr_payrollv2historydetail')
                                                //     ->where('id', collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->first()->id)
                                                //     ->update([
                                                //         'description'           => $particular->description,
                                                //         'totalamount'           => str_replace( ',', '', $particular->totalamount),
                                                //         'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                                //         'totalpaidamount'           => isset($particular->totalamountpaid) ? $particular->totalamountpaid : null,
                                                //         'paidstatus'       => isset($particular->particulartype) && ($particular->particulartype == 1 || $particular->particulartype == 2 || $particular->particulartype == 3) ? $particular->paidstatus : 0,
                                                //         'amountpaid'           => str_replace( ',', '', $particular->amountpaid),
                                                //         'paymenttype'           => $particular->paymenttype,
                                                //         'updatedby'             => auth()->user()->id,
                                                //         'updateddatetime'       => date('Y-m-d H:i:s')
                                                //     ]);

                                                // return collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->first()->id;
                                                DB::table('hr_payrollv2historydetail')
                                                    ->where('id', collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->where('description', $particular->description)->first()->id)
                                                    ->update([
                                                        // 'description'           => $particular->description,
                                                        'payrollid' => $checkifexistsid,
                                                        'totalamount' => floor($particular->totalamount * 100) / 100,
                                                        'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                                        'totalpaidamount' => isset($particular->totalamountpaid) ? floor($particular->totalamountpaid * 100) / 100 : null,
                                                        'paidstatus' => isset($particular->paidstatus) ? $particular->paidstatus : 0,
                                                        // 'paidstatus'       => isset($particular->particulartype) && ($particular->particulartype == 1 || $particular->particulartype == 2 || $particular->particulartype == 3) ? $particular->paidstatus : $particular->paidstatus,
                                                        'amountpaid' => floor($amountpaid * 100) / 100,
                                                        'paymenttype' => $particular->paymenttype,
                                                        'updatedby' => auth()->user()->id,
                                                        'updateddatetime' => date('Y-m-d H:i:s')
                                                    ]);

                                                if (is_null($particular->particulartype)) {
                                                    $ifexistaddparticular = DB::table('hr_payrollv2addparticular')
                                                        ->where('employeeid', $employeeinfo->id)
                                                        ->where('payrollid', $checkifexistsid)
                                                        ->where('headerid', $checkhistoryifexists->id)
                                                        ->where('description', $particular->description)
                                                        ->where('amount', $particular->amount)
                                                        ->where('type', $particular->type)
                                                        ->where('deleted', 0)
                                                        ->first();

                                                    if (!$ifexistaddparticular) {

                                                        DB::table('hr_payrollv2addparticular')
                                                            ->insert([
                                                                'payrollid' => $checkifexistsid,
                                                                'employeeid' => $employeeinfo->id,
                                                                'headerid' => $checkhistoryifexists->id,
                                                                'description' => $particular->description,
                                                                'amount' => (float) $particular->amount,
                                                                'type' => $particular->type,
                                                                'createdby' => auth()->user()->id,
                                                                'createddatetime' => date('Y-m-d H:i:s'),
                                                                'additionalid' => $particular->id
                                                            ]);
                                                    }
                                                }


                                            } else {

                                                if (is_null($particular->particulartype)) {
                                                    $ifexistaddparticular = DB::table('hr_payrollv2addparticular')
                                                        ->where('employeeid', $employeeinfo->id)
                                                        ->where('payrollid', $checkifexistsid)
                                                        ->where('headerid', $checkhistoryifexists->id)
                                                        ->where('description', $particular->description)
                                                        ->where('amount', $particular->amount)
                                                        ->where('type', $particular->type)
                                                        ->where('deleted', 0)
                                                        ->first();


                                                    if (!$ifexistaddparticular) {

                                                        DB::table('hr_payrollv2addparticular')
                                                            ->insert([
                                                                'payrollid' => $checkifexistsid,
                                                                'employeeid' => $employeeinfo->id,
                                                                'headerid' => $checkhistoryifexists->id,
                                                                'description' => $particular->description,
                                                                'amount' => (float) $particular->amount,
                                                                'type' => $particular->type,
                                                                'createdby' => auth()->user()->id,
                                                                'createddatetime' => date('Y-m-d H:i:s'),
                                                                'additionalid' => $particular->id
                                                            ]);
                                                    }
                                                }
                                                DB::table('hr_payrollv2historydetail')
                                                    ->insert([
                                                        'payrollid' => $checkifexistsid,
                                                        'employeeid' => $employeeinfo->id,
                                                        'headerid' => $updatedRecordId,
                                                        'description' => $particular->description,
                                                        'totalamount' => floor($particular->amount * 100) / 100,
                                                        'amountpaid' => floor($amountpaid * 100) / 100,
                                                        'paymenttype' => $paymenttypes,
                                                        'totalpaidamount' => isset($particular->totalamountpaid) ? floor($particular->totalamountpaid * 100) / 100 : null,
                                                        'particulartype' => $particular->particulartype,
                                                        'particularid' => $particular->particularid,
                                                        'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                                        'createdby' => auth()->user()->id,
                                                        'createddatetime' => date('Y-m-d H:i:s'),
                                                        'paidstatus' => 1

                                                    ]);

                                            }

                                        } else {
                                            if (collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->count() > 0) {
                                                if ($particular->particulartype == 2) {
                                                    $amountpaid = $particular->totalamount / 2;
                                                }
                                                DB::table('hr_payrollv2historydetail')
                                                    ->where('id', collect($allparts)->where('particulartype', $particular->particulartype)->where('particularid', $particular->particularid)->where('description', $particular->description)->first()->id)
                                                    ->update([
                                                        // 'description'           => $particular->description,
                                                        'totalamount' => floor($particular->totalamount * 100) / 100,
                                                        'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                                        'totalpaidamount' => isset($particular->totalamountpaid) ? floor($particular->totalamountpaid * 100) / 100 : null,
                                                        'paidstatus' => isset($particular->particulartype) && ($particular->particulartype == 1 || $particular->particulartype == 2 || $particular->particulartype == 3) ? $particular->paidstatus : 0,
                                                        'amountpaid' => floor($amountpaid * 100) / 100,
                                                        'paymenttype' => $particular->paymenttype,
                                                        'updatedby' => auth()->user()->id,
                                                        'updateddatetime' => date('Y-m-d H:i:s')
                                                    ]);
                                            }
                                        }
                                    }

                                    $allparticularnotodsd = collect($particulars)->where('particulartype', null)->where('particularid', null)->values();


                                    // Ensure $allparticularnotodsd is an array
                                    $allparticularnotodsdArray = is_array($allparticularnotodsd) ? $allparticularnotodsd : json_decode(json_encode($allparticularnotodsd), true);

                                    // Collect all descriptions from $allparticularnotodsd
                                    $descriptionsInAllParticularnotodsd = array_map(function ($item) {
                                        return $item['description'];
                                    }, $allparticularnotodsdArray);


                                    // Loop through $allpartsnotod and update records not existing in $allparticularnotodsd
                                    foreach ($allpartsnotod as $particularnotexisted) {
                                        if (
                                            $particularnotexisted->particulartype === null &&
                                            !in_array($particularnotexisted->description, $descriptionsInAllParticularnotodsd)
                                        ) {
                                            DB::table('hr_payrollv2historydetail')
                                                ->where('id', $particularnotexisted->id)
                                                ->update([
                                                    'paidstatus' => 0,
                                                    'deleted' => 1,
                                                    'updatedby' => auth()->user()->id,
                                                    'updateddatetime' => now()
                                                ]);
                                        }
                                    }
                                }
                            }
                            // return $otheraddedearningsparticulars;
                            // if(count($additionalparticulars) == 0)
                            if (count($otheraddedearningsparticulars) == 0) {

                                foreach ($particulars as $part) {

                                    if ($part->particulartype == null) {
                                        DB::table('hr_payrollv2addparticular')
                                            ->insert([
                                                'payrollid' => $checkifexistsid,
                                                'employeeid' => $employeeinfo->id,
                                                'headerid' => $checkhistoryifexists->id,
                                                'description' => $part->description,
                                                'amount' => (float) $part->amount,
                                                'type' => $part->type,
                                                'createdby' => auth()->user()->id,
                                                'createddatetime' => date('Y-m-d H:i:s'),
                                                'additionalid' => $part->id
                                            ]);
                                    }

                                }
                            } else {

                                $allparts = DB::table('hr_payrollv2addparticular')
                                    ->where('headerid', $checkhistoryifexists->id)
                                    ->where('deleted', '0')
                                    ->get();

                                // foreach ($particulars as $part) {
                                //     if ($part->particulartype == null) {
                                //         $particularexist = DB::table('hr_payrollv2addparticular')
                                //             ->where('headerid', $checkhistoryifexists->id)
                                //             ->where('description', $part->description)
                                //             ->where('deleted', 0)
                                //             ->where('employeeid', $employeeinfo->id)
                                //             // ->where('type', '>','0')
                                //             ->count();

                                //         if ($particularexist) {

                                //             DB::table('hr_payrollv2addparticular')
                                //             ->where('headerid', $checkhistoryifexists->id)
                                //             ->where('employeeid', $employeeinfo->id)
                                //             ->where('description', $part->description)
                                //             ->update([
                                //                 'amount'                => $part->amount,
                                //                 'type'                  => $part->type,
                                //                 'updatedby'             => auth()->user()->id,
                                //                 'updateddatetime'       => date('Y-m-d H:i:s'),
                                //                 'additionalid'          => $part->id
                                //             ]);
                                //         } else {
                                //             DB::table('hr_payrollv2addparticular')
                                //                 ->insert([
                                //                     'payrollid'             => $checkifexistsid,
                                //                     'employeeid'            => $employeeinfo->id,
                                //                     'headerid'              => $checkhistoryifexists->id,
                                //                     'description'           => $part->description,
                                //                     'amount'                => $part->amount,
                                //                     'type'                  => $part->type,
                                //                     'createdby'             => auth()->user()->id,
                                //                     'createddatetime'       => date('Y-m-d H:i:s'),
                                //                     'additionalid'          => $part->id
                                //                 ]);
                                //         }

                                //     } 

                                // }


                                if (count($allparts) == 0) {

                                    // foreach($additionalparticulars as $addedparticular)
                                    // {
                                    //     $detailid = DB::table('hr_payrollv2addparticular')
                                    //         ->insertGetId([
                                    //             'payrollid'             => $checkifexistsid,
                                    //             'employeeid'             => $employeeid,
                                    //             'headerid'             =>  $checkhistoryifexists->id,
                                    //             'description'            => $addedparticular->description,
                                    //             'amount'            => str_replace( ',', '', $addedparticular->amount),
                                    //             'type'              => $addedparticular->type,
                                    //             'createdby'             => auth()->user()->id,
                                    //             'createddatetime'       => date('Y-m-d H:i:s'),
                                    //             'additionalid'          => $addedparticular->dataid
                                    //         ]);
                                    // }
                                    // return 'something in particulars';
                                } else {
                                    foreach ($otheraddedearningsparticulars as $addedparticular) {

                                        if (isset($addedparticular->particulartype) == null) {

                                            if ($addedparticular->id == 0) {
                                                // DB::table('hr_payrollv2addparticular')
                                                // ->insert([
                                                //     'payrollid'             => $checkifexistsid,
                                                //     'employeeid'             => $employeeid,
                                                //     'headerid'             => $checkhistoryifexists->id,
                                                //     'description'            => $addedparticular->description,
                                                //     'amount'            => str_replace( ',', '', $addedparticular->amount),
                                                //     'type'              => $addedparticular->type,
                                                //     'createdby'             => auth()->user()->id,
                                                //     'createddatetime'       => date('Y-m-d H:i:s')
                                                // ]);

                                                return 'something in particulars';

                                            }
                                            // else{ remove 09082024

                                            //     if(collect($allparts)->where('id', $addedparticular->id)->count()>0)
                                            //     {

                                            //         DB::table('hr_payrollv2addparticular')
                                            //             ->where('id', collect($allparts)->where('id', $addedparticular->id)->first()->id)
                                            //             ->update([
                                            //                 'description'           => $addedparticular->description,
                                            //                 // 'amount'           => floor($addedparticular->amount * 100) / 100,
                                            //                 'amount'                => $addedparticular->amount,
                                            //                 'type'           => $addedparticular->type,
                                            //                 'updatedby'             => auth()->user()->id,
                                            //                 'updateddatetime'       => date('Y-m-d H:i:s'),
                                            //                 'additionalid'          => $addedparticular->additionalid
                                            //             ]);
                                            //     } else {
                                            //         DB::table('hr_payrollv2addparticular')
                                            //         ->insert([
                                            //             'payrollid'             => $checkifexistsid,
                                            //             'employeeid'             => $employeeid->id,
                                            //             'headerid'             => $checkhistoryifexists->id,
                                            //             'description'            => $addedparticular->description,
                                            //             'amount'            => str_replace( ',', '', $addedparticular->amount),
                                            //             'type'              => $addedparticular->type,
                                            //             'createdby'             => auth()->user()->id,
                                            //             'createddatetime'       => date('Y-m-d H:i:s'),
                                            //             'additionalid'          => $addedparticular->dataid
                                            //         ]);

                                            //         return 'something in particulars';

                                            //     }
                                            // }
                                        }
                                    }
                                }


                            }


                        }




                        // }

                    }


                } else {
                    DB::table('hr_payrollv2')
                        ->where('salarytypeid', $salid)
                        ->update([
                            'status' => 0
                        ]);

                    DB::table('hr_payrollv2')
                        ->insert([
                            'datefrom' => $datefrom,
                            'dateto' => $dateto,
                            'salarytypeid' => $salid,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);
                }
                return 1;



            } catch (\Exception $error) {
                // return $employeeinfo->id;
                return $error;
            }
        } elseif ($request->get('action') == 'closepayroll') {
            try {
                DB::table('hr_payrollv2')
                    ->where('id', $request->get('payrollid'))
                    ->update([
                        'status' => 0,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
                return 1;
            } catch (\Exception $error) {
                return $error;
            }
        } elseif ($request->get('action') == 'getnumberofreleased') {
            return DB::table('hr_payrollv2history')
                ->where('payrollid', $request->get('payrollid'))
                ->where('deleted', '0')
                ->where('released', '1')
                ->count();
        } else {

            try {
                $checkifexists = DB::table('hr_payrollv2')
                    ->where('salarytypeid', $salid)
                    ->count();

                if ($checkifexists) {
                    DB::table('hr_payrollv2')
                        ->where('salarytypeid', $salid)
                        ->update([
                            'status' => 0
                        ]);
                }

                // Use insertGetId instead of insert
                $payrollinseredid = DB::table('hr_payrollv2')->insertGetId([
                    'datefrom' => $datefrom,
                    'dateto' => $dateto,
                    'salarytypeid' => $salid,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => now(), // You can use the now() helper function
                ]);

                // $empids = [77];
                // $empids =[114];
                // $empids =[226];
                // $empids =[185];

                $employees = DB::table('teacher')
                    ->select('teacher.*', 'employee_personalinfo.gender', 'utype', 'teacher.id as employeeid', 'employee_personalinfo.departmentid', 'employee_basicsalaryinfo.salarybasistype as salid')
                    ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                    ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                    ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
                    ->where('teacher.deleted', '0')
                    ->where('teacher.isactive', '1')
                    ->where('employee_basicsalaryinfo.salarybasistype', $request->get('salid'))
                    // ->whereIn('teacher.id',$empids)
                    ->orderBy('employeeid', 'ASC')
                    ->get();


                // $employeeids = $employees->pluck('id')->toArray();
                $basicsalaryinfos = DB::table('employee_basicsalaryinfo')
                    ->select('employee_basicsalaryinfo.*', 'employee_basistype.type as salarytype', 'employee_basistype.type as ratetype')
                    ->leftJoin('employee_basistype', 'employee_basicsalaryinfo.salarybasistype', '=', 'employee_basistype.id')
                    ->where('employee_basicsalaryinfo.deleted', '0')
                    ->where('employee_basistype.deleted', '0')
                    ->get();


                // return dd($employees);
                foreach ($basicsalaryinfos as $basicsalaryinfo) {
                    if ($basicsalaryinfo->hoursperday == null || $basicsalaryinfo->hoursperday == 0) {
                        $basicsalaryinfo->hoursperday = 1;
                    }
                }

                // $empstatus = $employeeinfo->employmentstatus;
                $sy = DB::table('sy')
                    ->where('isactive', 1)
                    ->first();

                $semesterdateactive = DB::table('semester_date')
                    ->where('deleted', 0)
                    ->where('syid', $sy->id)
                    ->where('active', 1)
                    ->get();


                $payrollperiod = DB::table('hr_payrollv2')
                    ->where('id', $payrollinseredid)
                    ->first();

                $monthlypayroll_all = DB::table('hr_payrollv2')
                    ->select('hr_payrollv2history.*', 'hr_payrollv2.datefrom', 'hr_payrollv2.dateto', 'hr_payrollv2history.employeeid')
                    ->join('hr_payrollv2history', 'hr_payrollv2.id', '=', 'hr_payrollv2history.payrollid')
                    ->whereYear('hr_payrollv2.datefrom', date('Y', strtotime($payrollperiod->datefrom)))
                    ->whereMonth('hr_payrollv2.datefrom', date('m', strtotime($payrollperiod->datefrom)))
                    ->where('hr_payrollv2.deleted', '0')
                    ->where('hr_payrollv2history.deleted', '0')
                    ->get();

                $interval = new DateInterval('P1D');
                $realEnd = new DateTime($payrollperiod->dateto);
                $realEnd->add($interval);
                $period = new DatePeriod(new DateTime($payrollperiod->datefrom), $interval, $realEnd);


                $taphistory_all = DB::table('taphistory')
                    ->whereBetween('tdate', [$payrollperiod->datefrom, $payrollperiod->dateto])
                    ->where('utype', '!=', '7')
                    ->orderBy('ttime', 'asc')
                    ->where('deleted', '0')
                    ->get();


                $hr_attendance_all = DB::table('hr_attendance')
                    ->whereBetween('tdate', [$payrollperiod->datefrom, $payrollperiod->dateto])
                    ->where('deleted', 0)
                    ->orderBy('ttime', 'asc')
                    ->get();

                $departmentid_all = DB::table('teacher')
                    ->select(
                        'hr_departments.id as departmentid',
                        'teacher.id'
                    )
                    ->leftJoin('employee_personalinfo', 'teacher.id', 'employee_personalinfo.employeeid')
                    ->leftJoin('civilstatus', 'employee_personalinfo.maritalstatusid', 'civilstatus.civilstatus')
                    ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                    ->leftJoin('hr_departments', 'employee_personalinfo.departmentid', 'hr_departments.id')
                    ->get();

                $holidays = DB::table('school_calendar')
                    ->select(
                        'school_calendar.*',
                        'schoolcaltype.typename',
                        'hr_holidaytype.ifwork',
                        'hr_holidaytype.ifnotwork',
                        'hr_holidaytype.restdayifwork',
                        'hr_holidaytype.restdayifnotwork',
                        'hr_holidaytype.description'
                    )
                    ->leftJoin('schoolcaltype', 'school_calendar.holiday', '=', 'schoolcaltype.id')
                    ->leftJoin('hr_holidaytype', 'school_calendar.holidaytype', '=', 'hr_holidaytype.id')
                    ->where('schoolcaltype.type', 1)
                    ->where('school_calendar.deleted', 0)
                    ->get();

                $rtardiness_computations = DB::table('hr_tardinesscomp')
                    ->where('hr_tardinesscomp.deleted', '0')
                    ->where('hr_tardinesscomp.isactive', '1')
                    ->get();
                $tardiness_computations = DB::table('hr_tardinesscomp')
                    ->where('hr_tardinesscomp.deleted', '0')
                    ->where('hr_tardinesscomp.isactive', '1')
                    ->get();
                $tardinessbaseonsalary_all = DB::table('hr_tardinesscomp')
                    ->where('hr_tardinesscomp.deleted', '0')
                    // ->where('departmentid', $employeeinfo->departmentid)
                    ->where('baseonattendance', 1)
                    ->where('isactive', '1')
                    ->get();

                $standardallowances_all = DB::table('allowance_standard')
                    ->select(
                        'allowance_standard.id',
                        'allowance_standard.baseonattendance',
                        'allowance_standard.amountperday',
                        'employee_allowancestandard.id as empallowanceid',
                        'allowance_standard.description',
                        'employee_allowancestandard.amount as eesamount',
                        'employee_allowancestandard.allowance_standardid',
                        'employee_allowancestandard.amountbaseonsalary',
                        'employee_allowancestandard.monday',
                        'employee_allowancestandard.tuesday',
                        'employee_allowancestandard.wednesday',
                        'employee_allowancestandard.thursday',
                        'employee_allowancestandard.friday',
                        'employee_allowancestandard.saturday',
                        'employee_allowancestandard.sunday',
                        'employee_allowancestandard.employeeid'
                    )
                    ->leftJoin('employee_allowancestandard', 'allowance_standard.id', '=', 'employee_allowancestandard.allowance_standardid')
                    // ->where('employee_allowancestandard.employeeid', $request->get('employeeid'))
                    ->where('employee_allowancestandard.status', '1')
                    ->where('allowance_standard.deleted', '0')
                    ->where('employee_allowancestandard.deleted', '0')
                    ->get();

                $otherallowances_all = Db::table('employee_allowanceother')
                    ->select(
                        'employee_allowanceother.id',
                        'employee_allowanceother.description',
                        'employee_allowanceother.amount',
                        'employee_allowanceother.term'
                    )
                    // ->where('employee_allowanceother.employeeid', $request->get('employeeid'))
                    ->where('employee_allowanceother.deleted', '0')
                    ->get();

                $deductiontypes = Db::table('deduction_standard')
                    ->where('deleted', '0')
                    // ->distinct('deduction_typeid')
                    ->get();

                $checkifapplied_all = DB::table('employee_deductionstandard')
                    // ->where('employeeid', $request->get('employeeid'))
                    // ->where('deduction_typeid', $deductiontype->id)
                    ->where('deleted', '0')
                    ->where('status', '1')
                    ->get();

                // return $checkifapplied_all;
                $payrollDateFrom = Carbon::parse($payrollperiod->datefrom)->day;  // Extract day from the date
                $payrollDateTo = Carbon::parse($payrollperiod->dateto)->day;      // Extract day from the date

                $otherdeductions_all = Db::table('employee_deductionother')
                    ->where('employee_deductionother.paid', '0')
                    ->where('employee_deductionother.status', '1')
                    ->where('employee_deductionother.deleted', '0')
                    ->where('deductionotherid', '!=', null)
                    ->where('paidna', null)
                    ->get();

                $paidotherdeductions_all = Db::table('employee_deductionother')
                    // ->where('employee_deductionother.paid', '0')
                    ->where('employee_deductionother.status', '1')
                    ->where('employee_deductionother.deleted', '0')
                    ->where('deductionotherid', '!=', null)
                    ->where('paidna', 1)
                    ->get();

                $payrolldetails_all = DB::table('hr_payrollv2historydetail')
                    // ->leftJoin('hr_payrollv2history', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2history.payrollid')
                    ->where('hr_payrollv2historydetail.particulartype', 2)
                    // ->where('hr_payrollv2historydetail.employeeid', 77)
                    ->where('hr_payrollv2historydetail.paidstatus', 1)
                    ->where('hr_payrollv2historydetail.deleted', 0)
                    ->where('hr_payrollv2historydetail.payrollid', '!=', $payrollinseredid)
                    // ->where('hr_payrollv2history.released', 1)
                    ->get();

                $paiddeduction_all = DB::table('hr_payrollv2historydetail')
                    // ->leftJoin('hr_payrollv2history', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2history.payrollid')
                    ->where('hr_payrollv2historydetail.particulartype', 2)
                    // ->where('hr_payrollv2historydetail.employeeid', 77)
                    // ->where('hr_payrollv2history.employeeid', 77)
                    ->where('hr_payrollv2historydetail.payrollid', $payrollinseredid)
                    ->where('hr_payrollv2historydetail.paidstatus', 1)
                    ->where('hr_payrollv2historydetail.deleted', 0)
                    ->get();

                $otheraddedearnings_all = Db::table('hr_payrollv2additionalearndeduct')
                    // ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                    ->where('type', 1)
                    ->where('deleted', '0')
                    ->get();


                $otheraddedearningsparticulars_all = Db::table('hr_payrollv2addparticular')
                    // ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                    ->where('payrollid', $payrollinseredid)
                    ->where('type', 1)
                    ->where('deleted', '0')
                    ->get();

                $otheraddeddeductions_all = Db::table('hr_payrollv2additionalearndeduct')
                    // ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                    ->where('type', 2)
                    ->where('deleted', '0')
                    ->get();

                $otheraddeddeductionsparticulars_all = Db::table('hr_payrollv2addparticular')
                    // ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                    ->where('payrollid', $payrollinseredid)
                    ->where('type', 2)
                    ->where('deleted', '0')
                    ->get();

                $rtardiness_computations = DB::table('hr_tardinesscomp')
                    ->where('hr_tardinesscomp.deleted', '0')
                    ->where('hr_tardinesscomp.isactive', '1')
                    ->get();

                $employeecustomsched_all = DB::table('employee_customtimesched')
                    // ->where('employeeid', $employeeinfo->id)
                    ->where('shiftid', '!=', null)
                    // ->where('createdby', '!=', null)
                    ->where('deleted', 0)
                    ->get();

                $deductinfo_all = DB::table('hr_payrollv2historydetail')
                    ->select('hr_payrollv2historydetail.*')
                    ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                    // ->where('headerid', $payrollinfo->id)
                    // ->where('particulartype',2)
                    ->where('hr_payrollv2.deleted', '0')
                    ->where('hr_payrollv2historydetail.deleted', '0')
                    // ->where('particularid',$eachotherdeduction->id)
                    ->get();

                $checkhistoryifexists_all = DB::table('hr_payrollv2history')
                    // ->where('payrollid', $payrollinseredid)
                    // ->where('employeeid', $employeeinfo->id)
                    ->where('deleted', '0')
                    ->get();

                $activedaterange = DB::table('employee_cldaterange')
                    ->where('datefrom', $payrollperiod->datefrom)
                    ->where('dateto', $payrollperiod->dateto)
                    ->where('status', '1')
                    ->where('deleted', '0')
                    ->first();

                if ($activedaterange) {
                    $activeemployeetardy = DB::table('employee_cltardy')
                        ->where('headerid', $activedaterange->id)
                        ->where('status', '1')
                        ->where('datastatus', $activedaterange->setupstatus)
                        ->where('deleted', '0')
                        ->get();
                }


                $mondayCount = 0;
                $tuesdayCount = 0;
                $wednesdayCount = 0;
                $thursdayCount = 0;
                $fridayCount = 0;
                $saturdayCount = 0;
                $sundayCount = 0;
                $startDate = new DateTime($payrollperiod->datefrom);
                $endDate = new DateTime($payrollperiod->dateto);

                while ($startDate <= $endDate) {
                    $day_of_week = strtolower($startDate->format("l"));
                    if ($day_of_week === "monday") {
                        $mondayCount++;
                    } elseif ($day_of_week === "tuesday") {
                        $tuesdayCount++;
                    } elseif ($day_of_week === "wednesday") {
                        $wednesdayCount++;
                    } elseif ($day_of_week === "thursday") {
                        $thursdayCount++;
                    } elseif ($day_of_week === "friday") {
                        $fridayCount++;
                    } elseif ($day_of_week === "saturday") {
                        $saturdayCount++;
                    } elseif ($day_of_week === "sunday") {
                        $sundayCount++;
                    }
                    $startDate->modify("+1 day");
                }

                // return $otheraddedearnings_all;
                // return $otheraddeddeductions_all;
                //    return $paiddeductions_all;
                // return $checkifapplied_all;
                // return $standardallowances_all;
                // return $departmentid_all;
                // return $request->get('dates');
                // $perday = 0;


                // return $employees;

                foreach ($employees as $employeeinfo) {

                    $attendance = [];
                    $leavedetails = [];
                    $holidays_within_range = [];

                    $payrollinfo = DB::table('hr_payrollv2history')
                        ->where('payrollid', $payrollperiod->id)
                        ->where('employeeid', $employeeinfo->id)
                        ->where('deleted', '0')
                        ->first();

                    // return $tardiness_computations;
                    $tardinessamount = 0;
                    $lateduration = 0;
                    $durationtype = 0;
                    $tardinessallowance = 0;
                    $tardinessallowancetype = 0;
                    $totalPresentHolidaypay = 0; // Initialize the counter

                    // return $otheraddeddeductions;
                    $leaveamounttotal = 0;
                    $standardallowancesamount = 0;
                    $otheraddedearningsamount = 0;
                    $otheraddedearningsparticularsamount = 0;
                    $standarddeductionsamount = 0;
                    $otherdeductionsamount = 0;
                    $otheraddeddeductionsamount = 0;
                    $otheraddeddeductionsparticularsamount = 0;
                    // return $particulars;
                    $workinghours = 0;
                    $latemin = 0;
                    $lateam = 0;
                    $latepm = 0;
                    $latehours = 0;
                    $lateamount = 0;

                    $undertimemin = 0;
                    $undertimehours = 0;
                    $undertimeamount = 0;
                    $totalLateAmount = 0;
                    $totalLateMin = 0;
                    $totalUndertimeAmount = 0;
                    $totalUndertimeMin = 0;
                    $totalhourworks = 0;
                    $totalpresentdays = 0;
                    $totalabsentdays = 0;
                    $totalabsentamount = 0;
                    $totalabsentamounttotal = 0;

                    $totalearningamount = 0;
                    $totaldeductionamount = 0;
                    $netsalaryamount = 0;

                    $basicsalaryinfo = collect($basicsalaryinfos)->where('employeeid', $employeeinfo->id)->first();



                    $perdaysalary = 0;
                    $perhour = 0;

                    // ---------------------------------------------------------------
                    // mao ning additional for overload tardy
                    $regulardload = 0;
                    $overload = 0;
                    $emergencyload = 0;
                    $peroras = 0;
                    $regulardloadtardyamount = 0;
                    $overloadtardyamount = 0;
                    $emergencyloadtardyamount = 0;

                    // ---------------------------------------------------------------


                    if ($basicsalaryinfo) {
                        // If $basicsalaryinfo exists, set $employeeinfo->ratetype
                        $employeeinfo->ratetype = $basicsalaryinfo->ratetype;

                    } else {
                        // If $basicsalaryinfo is null or not found, set $employeeinfo->ratetype to an empty string
                        $employeeinfo->ratetype = '';
                    }

                    $basicsalaryinfo = (object) $basicsalaryinfo;
                    // return collect($basicsalaryinfo);
                    // return $basicsalaryinfo->attendancebased;

                    $monthlypayroll = collect($monthlypayroll_all)->where('employeeid', $employeeinfo->id)->values();
                    $taphistory = collect($taphistory_all)->where('studid', $employeeinfo->id)->values();
                    $hr_attendance = collect($hr_attendance_all)->where('studid', $employeeinfo->id)->values();
                    $departmentid = collect($departmentid_all)->where('id', $employeeinfo->id)->first()->departmentid;

                    // return collect($basicsalaryinfo);

                    $configured = 0;
                    $released = 0;

                    if ($payrollinfo) {
                        $configured = $payrollinfo->configured;
                        $released = $payrollinfo->released;
                    }

                    // return collect($basicsalaryinfo);

                    // return $basicsalaryinfo->ratetype;


                    // return collect($basicsalaryinfo);

                    $dates = array();
                    $restday = [];




                    // if ($employeeinfo->id == 111) {
                    //     return strtolower($startDate->format("l"));
                    // }
                    if ($basicsalaryinfo && isset($basicsalaryinfo->attendancebased) && $basicsalaryinfo->attendancebased == 1) {


                        foreach ($period as $date) {
                            if (strtolower($date->format('l')) == 'monday') {
                                if ($basicsalaryinfo->mondays == 1) {
                                    $dates[] = $date->format('Y-m-d');
                                } else {
                                    $restday[] = 'Monday';
                                }
                            } elseif (strtolower($date->format('l')) == 'tuesday') {
                                if ($basicsalaryinfo->tuesdays == 1) {
                                    $dates[] = $date->format('Y-m-d');
                                } else {
                                    $restday[] = 'Tuesday';
                                }
                            } elseif (strtolower($date->format('l')) == 'wednesday') {
                                if ($basicsalaryinfo->wednesdays == 1) {
                                    $dates[] = $date->format('Y-m-d');
                                } else {
                                    $restday[] = 'Wednesday';
                                }
                            } elseif (strtolower($date->format('l')) == 'thursday') {
                                if ($basicsalaryinfo->thursdays == 1) {
                                    $dates[] = $date->format('Y-m-d');
                                } else {
                                    $restday[] = 'Thursday';
                                }
                            } elseif (strtolower($date->format('l')) == 'friday') {
                                if ($basicsalaryinfo->fridays == 1) {
                                    $dates[] = $date->format('Y-m-d');
                                } else {
                                    $restday[] = 'Friday';
                                }
                            } elseif (strtolower($date->format('l')) == 'saturday') {
                                if ($basicsalaryinfo->saturdays == 1) {
                                    $dates[] = $date->format('Y-m-d');
                                } else {
                                    $restday[] = 'Saturday';
                                }
                            } elseif (strtolower($date->format('l')) == 'sunday') {
                                if ($basicsalaryinfo->sundays == 1) {
                                    $dates[] = $date->format('Y-m-d');
                                } else {
                                    $restday[] = 'Sunday';
                                }
                            }

                        }


                        // $employeeinfo->ratetype = $basicsalaryinfo->ratetype;

                        if (strtolower($basicsalaryinfo->salarytype) == 'monthly' || strtolower($basicsalaryinfo->salarytype) == 'custom') {
                            if ($basicsalaryinfo->amount == null || $basicsalaryinfo->amount == 0) {
                                $basicsalaryinfo->amountperday = 0;
                                $basicsalaryinfo->amountperhour = 0;
                            } else {
                                if ($dates == null) {
                                    // $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount / 2);
                                    // $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                                    $basicsalaryinfo->amountperday = 0;
                                    $basicsalaryinfo->amountperhour = 0;
                                } else {
                                    // not fixed 26
                                    // if ($basicsalaryinfo->halfdaysat == 1 || $basicsalaryinfo->halfdaysat == 2) {
                                    //     $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount / 2) / (count($dates));
                                    //     $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                                    //     $perdaysalary = floor($basicsalaryinfo->amountperday * 100) / 100;
                                    //     $perhour = ($basicsalaryinfo->amountperday)/$basicsalaryinfo->hoursperday;
                                    // } else {
                                    //     $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount / 2) / (count($dates) + ($basicsalaryinfo->saturdays == 0 ? $saturdayCount : 0));
                                    //     $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                                    //     $perdaysalary = floor($basicsalaryinfo->amountperday * 100) / 100;
                                    //     $perhour = ($basicsalaryinfo->amountperday)/$basicsalaryinfo->hoursperday;
                                    // }

                                    if ($basicsalaryinfo->halfdaysat == 1 || $basicsalaryinfo->halfdaysat == 2) {
                                        $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount / 2) / 13;
                                        $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                                        $perdaysalary = floor($basicsalaryinfo->amountperday * 100) / 100;
                                        $perhour = ($basicsalaryinfo->amountperday) / $basicsalaryinfo->hoursperday;
                                    } else {
                                        $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount / 2) / 13;
                                        $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                                        $perdaysalary = floor($basicsalaryinfo->amountperday * 100) / 100;
                                        $perhour = ($basicsalaryinfo->amountperday) / $basicsalaryinfo->hoursperday;
                                    }

                                }
                            }

                            // if ($employeeinfo->id == 111) {
                            //     return $saturdayCount;

                            // }

                        } elseif (strtolower($basicsalaryinfo->salarytype) == 'daily') {
                            $basicsalaryinfo->amountperday = $basicsalaryinfo->amount;
                            $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                            $basicsalaryinfo->amount = ($basicsalaryinfo->amount * count($dates));
                        } elseif (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                            $basicsalaryinfo->amountperday = ($basicsalaryinfo->amount * $basicsalaryinfo->hoursperday) * count($dates);
                            $basicsalaryinfo->amountperhour = $basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday;
                        }


                        if ($basicsalaryinfo) {
                            $peroras = $basicsalaryinfo->clsubjperhour;
                            $perminute = $peroras / 60;
                        }

                        if ($activedaterange) {
                            $tardys = collect($activeemployeetardy)->where('employeeid', $employeeinfo->id)->values();

                            foreach ($tardys as $tardy) {
                                if ($tardy->type == 'Regular Late' || $tardy->type == 'Regular Undertime' || $tardy->type == 'Regular Absent') {
                                    $regulardload += $tardy->totalminutes;
                                } else if ($tardy->type == 'Overload Late' || $tardy->type == 'Overload Undertime' || $tardy->type == 'Overload Absent') {
                                    $overload += $tardy->totalminutes;
                                } else if ($tardy->type == 'Emergency Late' || $tardy->type == 'Emergency Undertime' || $tardy->type == 'Emergency Absent') {
                                    $emergencyload += $tardy->totalminutes;
                                }
                            }
                        }

                        if ($regulardload > 0) {
                            // $regulardloadtardyamount = $regulardload * $perminute;
                            $regulardloadtardyamount = round(($regulardload * $basicsalaryinfo->amountperhour / 60), 2);

                        }
                        if ($overload > 0) {
                            $overloadtardyamount = round($overload * $perminute, 2);
                        }
                        if ($emergencyload > 0) {
                            $emergencyloadtardyamount = round($emergencyload * $perminute, 2);
                        }

                        if (!empty($dates)) {
                            $attendance = \App\Models\HR\HREmployeeAttendance::gethours($dates, $employeeinfo->id, $taphistory, $hr_attendance, $departmentid);
                        }

                        $timebrackets = array();

                        if (count($attendance) > 0) {

                            foreach ($attendance as $eachdate) {
                                // return $eachdate->date;

                                $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date, $employeeinfo, ($basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday), $basicsalaryinfo, $taphistory, $hr_attendance);
                                $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                                $eachdate->lateminutes = $latedeductiondetail->lateminutes;
                                $eachdate->holidayname = '';

                                if (count($latedeductiondetail->brackets) > 0) {
                                    foreach ($latedeductiondetail->brackets as $eachbracket) {
                                        array_push($timebrackets, $eachbracket);
                                    }
                                }
                                $eachdate->amountdeduct = 0;
                            }

                        }


                        // if ($employeeinfo->id == 111) {
                        //     $result = array(
                        //         'basicsalaryinfo' => $basicsalaryinfo,
                        //         'employeeinfo' => $employeeinfo
                        //     );

                        //     return $result;
                        // }


                        if ($employeeinfo->departmentid) {
                            $tardinessbaseonsalary = collect($tardinessbaseonsalary_all)->where('departmentid', $employeeinfo->departmentid)->first();
                        }

                        if (count($attendance) > 0 && count($tardiness_computations) > 0) {
                            foreach ($attendance as $eachatt) {
                                $eachatt->lateamminutes = ($eachatt->lateamhours * 60);
                                $eachatt->latepmminutes = $eachatt->latepmhours * 60;
                                $eachatt->lateminutes = $eachatt->latehours * 60;

                                $eachcomputations = collect($tardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                                $fromcomputations = collect($tardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                                $eachcomputations = $eachcomputations->merge($fromcomputations);
                                $eachcomputations = $eachcomputations->unique();

                                if ($basicsalaryinfo->attendancebased == 1) {
                                    if (count($eachcomputations) > 0) {
                                        foreach ($eachcomputations as $eachcomputation) {
                                            if ($eachcomputation->latetimetype == 1) {
                                                if ($eachcomputation->deducttype == 1) {
                                                    $eachatt->amountdeduct = $eachcomputation->amount;
                                                } else {
                                                    $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                                }

                                            } else {
                                                $computehours = ($eachatt->lateminutes / 60);
                                                if ($eachcomputation->deducttype == 1) {
                                                    $eachatt->amountdeduct = $eachcomputation->amount;
                                                } else {
                                                    $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $eachatt->amountdeduct = 0;
                                }
                            }
                        }


                        $latecomputationdetails = (object) array(
                            'tardinessamount' => $tardinessamount,
                            'lateduration' => $lateduration,
                            'durationtype' => $durationtype,
                            'tardinessallowance' => $tardinessallowance,
                            'tardinessallowancetype' => $tardinessallowancetype
                        );


                        // return collect($dates);
                        // return collect($basicsalaryinfo);


                        if (!empty($dates)) {
                            // For HOLIDAY

                            $datesAbsents = [];
                            $datesAbsences = [];
                            $datesPresent = [];

                            foreach ($attendance as $attendanceData) {
                                $date = $attendanceData->date;

                                if ($attendanceData->status == 1) {
                                    $datesPresent[] = $date;
                                } else {
                                    $datesAbsents[] = ['date' => $date];
                                    $datesAbsences[] = $date;
                                }
                            }

                            // return $datesAbsences;

                            // get the missing dates from payroll period start and end, usually 15days
                            $startDate = $payrollperiod->datefrom;
                            $endDate = $payrollperiod->dateto;
                            $datesInRange = [];

                            $currentDate = strtotime($startDate);
                            $endTimestamp = strtotime($endDate);

                            while ($currentDate <= $endTimestamp) {
                                $date = date("Y-m-d", $currentDate);
                                $dayName = date("l", $currentDate); // Get the full day name
                                $datesInRange[$date] = $dayName; // Store in an associative array
                                $currentDate = strtotime("+1 day", $currentDate);
                            }

                            // this is the result together with the days name
                            $missingDates = array_diff_key($datesInRange, array_flip($datesPresent));


                            // compare missing dates if nag match sa gi return nga rest day 
                            $matchingMissingDates = [];

                            foreach ($missingDates as $date => $dayName) {
                                if (in_array($dayName, $restday)) {
                                    $matchingMissingDates[$date] = $dayName;
                                }
                            }

                            $matchingDates = array_keys($matchingMissingDates);
                            $attendanceforrestday = \App\Models\HR\HREmployeeAttendance::gethours($matchingDates, $employeeinfo->id, $taphistory, $hr_attendance, $departmentid);

                            $timebracketsrestday = array();

                            if (count($attendanceforrestday) > 0) {
                                foreach ($attendanceforrestday as $eachdate) {
                                    $latedeductiondetail = \App\Models\HR\HREmployeeAttendance::payrollattendancev2($eachdate->date, $employeeinfo, ($basicsalaryinfo->amountperday / $basicsalaryinfo->hoursperday), $basicsalaryinfo, $taphistory, $hr_attendance);
                                    $eachdate->latedeductionamount = $latedeductiondetail->latedeductionamount;
                                    $eachdate->lateminutes = $latedeductiondetail->lateminutes;
                                    $eachdate->restday = 1;

                                    if (count($latedeductiondetail->brackets) > 0) {
                                        foreach ($latedeductiondetail->brackets as $eachbracket) {
                                            array_push($timebracketsrestday, $eachbracket);
                                        }
                                    }
                                    $eachdate->amountdeduct = 0;
                                }
                            }

                            // $rtardiness_computations = DB::table('hr_tardinesscomp')
                            //     ->where('hr_tardinesscomp.deleted','0')
                            //     ->where('hr_tardinesscomp.isactive','1')
                            //     ->get();

                            $rtardinessamount = 0;
                            $rlateduration = 0;
                            $rdurationtype = 0;
                            $rtardinessallowance = 0;
                            $rtardinessallowance = 0;

                            if (count($attendanceforrestday) > 0 && count($rtardiness_computations) > 0) {
                                foreach ($attendanceforrestday as $eachatt) {
                                    $eachatt->lateamminutes = ($eachatt->lateamhours * 60);
                                    $eachatt->latepmminutes = number_format($eachatt->latepmhours * 60);
                                    $eachatt->lateminutes = number_format($eachatt->latehours * 60);

                                    $eachcomputations = collect($rtardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                                    $fromcomputations = collect($rtardiness_computations)->where('latefrom', '<=', $eachatt->lateminutes)->where('lateto', '>=', $eachatt->lateminutes);
                                    $eachcomputations = $eachcomputations->merge($fromcomputations);
                                    $eachcomputations = $eachcomputations->unique();

                                    if ($basicsalaryinfo->attendancebased == 1) {
                                        if (count($eachcomputations) > 0) {
                                            foreach ($eachcomputations as $eachcomputation) {
                                                if ($eachcomputation->latetimetype == 1) {
                                                    if ($eachcomputation->deducttype == 1) {
                                                        $eachatt->amountdeduct = $eachcomputation->amount;
                                                    } else {
                                                        $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                                    }

                                                } else {
                                                    $computehours = ($eachatt->lateminutes / 60);
                                                    if ($eachcomputation->deducttype == 1) {
                                                        $eachatt->amountdeduct = $eachcomputation->amount;
                                                    } else {
                                                        $eachatt->amountdeduct = ($eachcomputation->amount / 100) * $basicsalaryinfo->amountperday;
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $eachatt->amountdeduct = 0;
                                    }
                                }
                            }
                            $attendanceArray = json_decode($attendanceforrestday, true);
                            $filteredAttendance = array_filter($attendanceArray, function ($record) {
                                return $record['status'] == 1;
                            });

                            $datesArrayrestdays = [];

                            foreach ($filteredAttendance as $record) {
                                $datesArrayrestdays[] = $record['date'];
                            }

                            $dayswithattendance = collect(array());
                            $dayswithattendance = $dayswithattendance->merge($datesPresent);
                            $dayswithattendance = $dayswithattendance->merge($datesArrayrestdays);


                            $holidaydates = [];
                            foreach ($holidays as $holiday) {
                                $startDate = new \DateTime($holiday->start);
                                $endDate = new \DateTime($holiday->end);

                                $interval = new \DateInterval('P1D'); // 1 day interval
                                $dateRange = new \DatePeriod($startDate, $interval, $endDate);

                                foreach ($dateRange as $date) {
                                    $holidaydates[] = [
                                        'date' => $date->format('Y-m-d'),
                                        'type' => $holiday->description,
                                        'holidaytype' => $holiday->holidaytype,
                                        'holidayname' => $holiday->title
                                    ];

                                }
                            }

                            // $employeecustomsched = DB::table('employee_customtimesched')
                            //     ->where('employeeid', $employeeinfo->id)
                            //     ->where('shiftid', '!=', null)
                            //     ->where('createdby', '!=', null)
                            //     ->where('deleted', 0)
                            //     ->first();
                            $employeecustomsched = collect($employeecustomsched_all)->where('employeeid', $employeeinfo->id)->first();
                            $leavedetails = \App\Models\HR\HRSalaryDetails::getleavesapplied($employeeinfo->id, $payrollperiod);
                            // return $leavedetails;

                            if (count($leavedetails) > 0) {

                                foreach ($leavedetails as $leave) {

                                    $leave->amount = 0.00;

                                    $getpay = DB::table('hr_leaves')
                                        ->where('id', $leave->id)
                                        ->first();

                                    if (strtolower(date('D', strtotime($leave->ldate))) == 'mon') {
                                        if ($basicsalaryinfo->mondays == 1 && $getpay->withpay == 1) {
                                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                            } else {
                                                $leave->amount = $perdaysalary;
                                            }
                                        }
                                    }
                                    if (strtolower(date('D', strtotime($leave->ldate))) == 'tue') {
                                        if ($basicsalaryinfo->tuesdays == 1 && $getpay->withpay == 1) {
                                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                            } else {
                                                $leave->amount = $perdaysalary;
                                            }
                                        }
                                    }
                                    if (strtolower(date('D', strtotime($leave->ldate))) == 'wed') {
                                        if ($basicsalaryinfo->wednesdays == 1 && $getpay->withpay == 1) {
                                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                            } else {
                                                $leave->amount = $perdaysalary;
                                            }
                                        }
                                    }
                                    if (strtolower(date('D', strtotime($leave->ldate))) == 'thu') {
                                        if ($basicsalaryinfo->thursdays == 1 && $getpay->withpay == 1) {
                                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                            } else {
                                                $leave->amount = $perdaysalary;
                                            }
                                        }
                                    }
                                    if (strtolower(date('D', strtotime($leave->ldate))) == 'fri') {
                                        if ($basicsalaryinfo->fridays == 1 && $getpay->withpay == 1) {
                                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                            } else {
                                                $leave->amount = $perdaysalary;
                                            }
                                        }
                                    }
                                    if (strtolower(date('D', strtotime($leave->ldate))) == 'sat') {
                                        // return date('D',strtotime($leavedatesperiod));
                                        // return $basicsalaryinfo->saturdays;
                                        if ($basicsalaryinfo->saturdays == 1 && $getpay->withpay == 1) {
                                            // return 'asdsa';
                                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                            } else {
                                                $leave->amount = $perdaysalary;
                                            }
                                        }
                                    }
                                    if (strtolower(date('D', strtotime($leave->ldate))) == 'sun') {
                                        if ($basicsalaryinfo->sundays == 1 && $getpay->withpay == 1) {
                                            if (strtolower($basicsalaryinfo->salarytype) == 'hourly') {
                                                $leave->amount = ($basicsalaryinfo->saturdayhours * $basicsalaryinfo->amount);
                                            } else {
                                                $leave->amount = $perdaysalary;
                                            }
                                        }
                                    }
                                    if ($leave->dayshift == 0) {
                                        $leave->leave_type = '' . $leave->leave_type;
                                        $leave->amount = round($leave->amount, 2);
                                    } elseif ($leave->dayshift == 1) {
                                        $leave->leave_type = 'AM - ' . $leave->leave_type;
                                        $leave->amount = round(($leave->amount / 2), 2);
                                    } elseif ($leave->dayshift == 2) {
                                        $leave->leave_type = 'PM - ' . $leave->leave_type;
                                        $leave->amount = round(($leave->amount / 2), 2);
                                    }

                                }
                            }

                            if (count($leavedetails) > 0) {
                                foreach ($leavedetails as $leavesdetail) {
                                    if ($leavesdetail->halfday == 0) {
                                        $leavesdetail->daycoverd = 'Whole Day';
                                    } else if ($leavesdetail->halfday == 1) {
                                        $leavesdetail->daycoverd = 'Half Day AM';
                                    } else if ($leavesdetail->halfday == 2) {
                                        $leavesdetail->daycoverd = 'Half Day PM';
                                    } else {
                                        $leavesdetail->daycoverd = '';
                                    }
                                }
                            }

                            if (count($attendance) > 0) {
                                foreach ($attendance as $lognull) {
                                    $lognull->leavetype = '';
                                    $lognull->leavedaystatus = '';
                                    $lognull->daycoverd = '';
                                    $lognull->leaveremarks = '';

                                    if (count($leavedetails) > 0) {

                                        foreach ($leavedetails as $employeeleavesapp) {
                                            if ($employeeleavesapp->ldate == $lognull->date) {
                                                $lognull->leavetype = $employeeleavesapp->leave_type;
                                                $lognull->leavedaystatus = $employeeleavesapp->halfday;
                                                $lognull->daycoverd = $employeeleavesapp->daycoverd;
                                                $lognull->leaveremarks = $employeeleavesapp->remarks ?? '';

                                                // if ($lognull->daycoverd == ) {
                                                //     # code...
                                                // }
                                            }
                                        }
                                    }
                                }

                            }

                            // HOLIDAY 
                            if ($employeecustomsched) {

                                $amin = new DateTime($employeecustomsched->amin);
                                $amout = new DateTime($employeecustomsched->amout);
                                $pmin = new DateTime($employeecustomsched->pmin);
                                $pmout = new DateTime($employeecustomsched->pmout);

                                // Calculate morning working hours
                                $morningWorkingHours = $amin->diff($amout);
                                $amhours = $morningWorkingHours->h;

                                // Calculate afternoon working hours
                                $afternoonWorkingHours = $pmin->diff($pmout);
                                $pmhours = $afternoonWorkingHours->h;
                                // $totalworkinghours = floor(($amhours + $pmhours) * 100) / 100;
                                $totalworkinghours = $amhours + $pmhours;

                                if (count($holidaydates) > 0) {
                                    foreach ($attendance as $att) {
                                        $att->holiday = 0;
                                        $att->holidayname = "";

                                        foreach ($holidaydates as $holidaydate) {
                                            // if ($att->date == $holidaydate['date'] && $att->status == 2 && ($holidaydate['holidaytype'] == 1 || $holidaydate['type'] == 'Regular Holiday')) {
                                            if ($att->date == $holidaydate['date'] && $att->status == 2) {
                                                $att->amtimein = $employeecustomsched->amin;
                                                $att->amtimeout = $employeecustomsched->amout;
                                                $att->pmtimein = $employeecustomsched->pmin;
                                                $att->pmtimeout = $employeecustomsched->pmout;
                                                $att->timeinam = $employeecustomsched->amin;
                                                $att->timeoutam = $employeecustomsched->amout;
                                                $att->timeinpm = $employeecustomsched->pmin;
                                                $att->timeoutpm = $employeecustomsched->pmout;
                                                $att->amin = $employeecustomsched->amin;
                                                $att->amout = $employeecustomsched->amout;
                                                $att->pmin = $employeecustomsched->pmin;
                                                $att->pmout = $employeecustomsched->pmout;
                                                $att->status = 1;
                                                $att->totalworkinghours = $totalworkinghours;
                                                $att->holiday = 1;
                                                $att->holidayname = $holidaydate['holidayname'];

                                            } else if ($att->date == $holidaydate['date'] && $att->status == 1) {
                                                $att->amtimein = $employeecustomsched->amin;
                                                $att->amtimeout = $employeecustomsched->amout;
                                                $att->pmtimein = $employeecustomsched->pmin;
                                                $att->pmtimeout = $employeecustomsched->pmout;
                                                $att->timeinam = $employeecustomsched->amin;
                                                $att->timeoutam = $employeecustomsched->amout;
                                                $att->timeinpm = $employeecustomsched->pmin;
                                                $att->timeoutpm = $employeecustomsched->pmout;
                                                $att->amin = $employeecustomsched->amin;
                                                $att->amout = $employeecustomsched->amout;
                                                $att->pmin = $employeecustomsched->pmin;
                                                $att->pmout = $employeecustomsched->pmout;
                                                $att->status = 1;
                                                $att->totalworkinghours = $totalworkinghours;
                                                $att->holiday = 1;
                                                $att->holidayname = $holidaydate['holidayname'];


                                                $att->lateminutes = 0;
                                                $att->lateamminutes = 0;
                                                $att->latepmminutes = 0;
                                                $att->lateamhours = 0;
                                                $att->latepmhours = 0;
                                                $att->latehours = 0;
                                                $att->undertimeamhours = 0;
                                                $att->undertimepmhours = 0;

                                            }
                                        }
                                    }
                                }
                            }

                            if ($payrollperiod) {
                                $datefrom = $payrollperiod->datefrom;
                                $dateto = $payrollperiod->dateto;


                                foreach ($holidays as $holiday) {
                                    $start_date = strtotime($holiday->start);
                                    $end_date = strtotime($holiday->end);

                                    if ($start_date <= strtotime($dateto) && $end_date >= strtotime($datefrom)) {
                                        // Check if the start date of the holiday matches any date in $datesArrayrestdays
                                        if (in_array(date("Y-m-d", $start_date), $datesArrayrestdays)) {
                                            // If it matches, set attendance to 'present'
                                            $holiday->attendance = 'present';
                                        } else {
                                            // If it doesn't match, set attendance to 'not present'
                                            $holiday->attendance = 'not present';
                                        }

                                        // Calculate holiday pay percentage
                                        $duration = ceil(($end_date - $start_date) / (60 * 60 * 24)); // Calculate duration in days

                                        // Calculate the amount per day
                                        $amountPerDay = $basicsalaryinfo->amountperday;
                                        $holiday->matching = "";
                                        $holiday->hoursperday = $basicsalaryinfo->hoursperday;
                                        $holiday->duration = $duration; // Add duration as a property
                                        $holiday->amountPerDay = $amountPerDay; // Add amount per day as a property
                                        $holiday->holidaypay = $amountPerDay;

                                        $holidays_within_range[] = $holiday;
                                    }
                                }
                            }


                            foreach ($dayswithattendance as $datesp) {
                                if (in_array($datesp, $datesArrayrestdays)) {
                                    $datepresentstart = strtotime($datesp); // Access the date directly since $datesp is a string
                                    foreach ($holidays_within_range as $holiday_within) { // Use &$holiday_within to modify the original array
                                        $start = strtotime($holiday_within->start);
                                        if ($start == $datepresentstart) { // Use == for comparison, not =
                                            $holiday_within->matching = "Matching"; // Add a property to indicate the match
                                            $holiday_within->attendance = 'present';
                                            $holidaypercentage = $holiday_within->restdayifwork / 100;
                                            $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
                                            $holidayperday = $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
                                            $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                            $holidaywithdurationpay = $holidaywithdurationpay + $holiday_within->amountPerDay;
                                            $holiday_within->holidaypay = $holidaywithdurationpay;
                                        }
                                    }
                                } else {
                                    $datepresentstart = strtotime($datesp); // Access the date directly since $datesp is a string
                                    foreach ($holidays_within_range as $holiday_within) { // Use &$holiday_within to modify the original array
                                        $start = strtotime($holiday_within->start);
                                        if ($start == $datepresentstart) { // Use == for comparison, not =
                                            $holiday_within->matching = "Matching"; // Add a property to indicate the match
                                            $holiday_within->attendance = 'present';
                                            $holidaypercentage = $holiday_within->ifwork / 100;

                                            $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;

                                            $holidayperday = $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;

                                            $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                            // $holidaywithdurationpay = $holiday_within->amountPerDay * $holiday_within->duration;
                                            // $holiday_within->holidaypay = number_format($holidaywithdurationpay - .005, 2);
                                            $holiday_within->holidaypay = floor($holidaywithdurationpay * 100) / 100;

                                        }
                                    }
                                }
                            }

                            foreach ($datesAbsences as $datesp) {
                                $dateabsentstart = strtotime($datesp);
                                foreach ($holidays_within_range as $holiday_within) {
                                    if ($holiday_within->ifnotwork > 0) {
                                        $start = strtotime($holiday_within->start);
                                        if ($start == $dateabsentstart) {
                                            $holiday_within->matching = "Matching";
                                            $holidaypercentage = $holiday_within->ifwork / 100;
                                            $hourlyrate = $holiday_within->amountPerDay / $holiday_within->hoursperday;
                                            $holidayperday = $hourlyrate * $holidaypercentage * $holiday_within->hoursperday;
                                            $holidaywithdurationpay = $holidayperday * $holiday_within->duration;
                                            $holiday_within->holidaypay = floor($holidaywithdurationpay * 100) / 100;
                                        }
                                    }

                                }
                            }


                            // Get the total holiday payment
                            if ($employeeinfo->employmentstatus == 1) {
                                foreach ($holidays_within_range as $holidaypayments) {
                                    if ($holidaypayments->matching == 'Matching') {
                                        $totalPresentHolidaypay += $holidaypayments->holidaypay; // Increment the counter
                                    }
                                }
                            }



                            if ($employeecustomsched) {
                                $amin = new DateTime($employeecustomsched->amin);
                                $amout = new DateTime($employeecustomsched->amout);
                                $pmin = new DateTime($employeecustomsched->pmin);
                                $pmout = new DateTime($employeecustomsched->pmout);

                                // Calculate morning working hours
                                $morningWorkingHours = $amin->diff($amout);
                                $amhours = $morningWorkingHours->h + ($morningWorkingHours->i / 60); // Convert minutes to fraction of an hour

                                // Calculate afternoon working hours
                                $afternoonWorkingHours = $pmin->diff($pmout);
                                $pmhours = $afternoonWorkingHours->h + ($afternoonWorkingHours->i / 60); // Convert minutes to fraction of an hour

                                // Calculate total working hours
                                $totalworkinghours = $amhours + $pmhours;

                                if ($basicsalaryinfo) {
                                    if ($basicsalaryinfo->halfdaysat == 1) {
                                        foreach ($attendance as $attt) {
                                            if ($attt->pmin != null || $attt->pmout != null) {
                                                if ($attt->day == 'Saturday' && $attt->holiday == 0) {

                                                    $attt->amtimein = $employeecustomsched->amin;
                                                    $attt->amtimeout = $employeecustomsched->amout;
                                                    $attt->timeinam = $employeecustomsched->amin;
                                                    $attt->timeoutam = $employeecustomsched->amout;
                                                    $attt->amin = $employeecustomsched->amin;
                                                    $attt->amout = $employeecustomsched->amout;
                                                    $attt->status = 1;


                                                    if ($attt->pmin == null || $attt->pmout == null) {
                                                        $attt->totalworkinghours = $amhours;
                                                        $attt->totalworkinghoursrender = $amhours;
                                                        $attt->undertimepmhours = $pmhours;

                                                    } else {
                                                        $attt->totalworkinghours = $amhours + ($amhours - $attt->latepmhours);
                                                        $attt->totalworkinghoursrender = $amhours + ($amhours - $attt->latepmhours);
                                                        $attt->lateamminutes = 0;
                                                        $attt->lateamhours = 0;
                                                        $attt->undertimeamhours = 0;
                                                    }


                                                }
                                            }
                                        }
                                    } else if ($basicsalaryinfo->halfdaysat == 2) {
                                        foreach ($attendance as $attt) {
                                            if ($attt->amin != null || $attt->amout != null) {
                                                if ($attt->day == 'Saturday' && $attt->holiday == 0) {
                                                    $attt->pmtimein = $employeecustomsched->pmin;
                                                    $attt->pmtimeout = $employeecustomsched->pmout;
                                                    $attt->timeinpm = $employeecustomsched->pmin;
                                                    $attt->timeoutpm = $employeecustomsched->pmout;
                                                    $attt->pmin = $employeecustomsched->pmin;
                                                    $attt->pmout = $employeecustomsched->pmout;
                                                    $attt->status = 1;

                                                    if ($attt->amin == null || $attt->amout == null) {
                                                        $attt->totalworkinghours = $pmhours;
                                                        $attt->totalworkinghoursrender = $pmhours;
                                                        $attt->lateamminutes = $pmhours * 60;
                                                        $attt->lateamhours = $pmhours;
                                                        // $attt->undertimeamhours = $pmhours;
                                                    } else {
                                                        $attt->totalworkinghours = $pmhours + ($amhours - $attt->lateamhours);
                                                        $attt->totalworkinghoursrender = $pmhours + ($amhours - $attt->lateamhours);
                                                        $attt->latepmminutes = 0;
                                                        $attt->latepmhours = 0;
                                                        $attt->undertimepmhours = 0;
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }

                                $totalLateHours = 0;
                                $totalUndertimeHours = 0;
                                $totalworkinghoursrender = 0;
                                $flexihours = 0;
                                $flexihoursundertime = 0;

                                foreach ($attendance as $entry) {
                                    if ($entry->totalworkinghours != 0 && $entry->totalworkinghours !== null && $entry->amin !== null && $entry->pmout !== null) {
                                        // if ($entry->totalworkinghours != 0 && $entry->totalworkinghours !== null) {
                                        // return $entry->date;
                                        $totalLateHours = $entry->latehours;
                                        $totalUndertimeHours = $entry->undertimehours;
                                        $totalWorkingHoursRender = 8 - ($totalLateHours + $totalUndertimeHours);
                                        $entry->totalworkinghoursrender = $totalWorkingHoursRender;

                                        $flexihours = $entry->totalworkinghoursflexi;
                                        $flexihoursundertime = 8 - $flexihours;

                                        if ($flexihours > 8) {
                                            $entry->flexihours = 8;
                                        } else {
                                            // $entry->flexihours = number_format($flexihours - .005, 2);
                                            $entry->flexihours = $flexihours;
                                        }
                                        if ($flexihoursundertime < 0) {
                                            $entry->flexihoursundertime = 0;
                                        } else {
                                            $entry->flexihoursundertime = $flexihoursundertime;
                                        }
                                    } else {
                                        $entry->totalworkinghoursrender = 0;
                                        $entry->flexihours = 0;
                                        $entry->flexihoursundertime = 0;
                                    }
                                }

                                foreach ($attendance as $att) {
                                    if ($att->status == 1) {
                                        if ($att->leavedaystatus === 0) {
                                            $att->amtimein = $employeecustomsched->amin;
                                            $att->amtimeout = $employeecustomsched->amout;
                                            $att->pmtimein = $employeecustomsched->pmin;
                                            $att->pmtimeout = $employeecustomsched->pmout;
                                            $att->timeinam = $employeecustomsched->amin;
                                            $att->timeoutam = $employeecustomsched->amout;
                                            $att->timeinpm = $employeecustomsched->pmin;
                                            $att->timeoutpm = $employeecustomsched->pmout;
                                            $att->amin = $employeecustomsched->amin;
                                            $att->amout = $employeecustomsched->amout;
                                            $att->pmin = $employeecustomsched->pmin;
                                            $att->pmout = $employeecustomsched->pmout;
                                            $att->status = 1;
                                            $att->totalworkinghours = $totalworkinghours;
                                            $att->totalworkinghoursrender = $totalworkinghours;
                                            $att->appliedleave = 1;
                                            // $att->leavetype = $leavedetail->leave_type;
                                            $att->latepmminutes = 0;
                                            $att->latepmhours = 0;
                                            $att->undertimepmhours = 0;
                                            $att->lateamminutes = 0;
                                            $att->lateamhours = 0;
                                            $att->undertimeamhours = 0;
                                            $att->latehours = 0;
                                            $att->appliedleave = 1;


                                        } else if ($att->leavedaystatus == 1) {

                                            //return floor(($att->totalworkinghours + $amhours) * 100) / 100;
                                            $att->amtimein = $employeecustomsched->amin;
                                            $att->amtimeout = $employeecustomsched->amout;
                                            $att->timeinam = $employeecustomsched->amin;
                                            $att->timeoutam = $employeecustomsched->amout;
                                            $att->amin = $employeecustomsched->amin;
                                            $att->amout = $employeecustomsched->amout;
                                            // $att->leavetype = $leavedetail->leave_type;
                                            // return collect($att->totalworkinghours);


                                            $att->totalworkinghours = floor(($att->totalworkinghours + $amhours) * 100) / 100;
                                            $att->totalworkinghoursrender = floor(($att->totalworkinghours) * 100) / 100;
                                            $att->totalworkinghoursflexi = floor(($att->totalworkinghours) * 100) / 100;
                                            $att->flexihours = floor(($att->totalworkinghours) * 100) / 100;


                                            if (($att->timeinpm != null || $att->pmtimein != null) && ($att->timeoutpm == null || $att->pmtimeout == null)) {
                                                $att->latepmminutes = 0;
                                                $att->latepmhours = 0;
                                                $att->undertimepmhours = $pmhours - $att->latepmhours;
                                                $att->undertimeminutes = ($pmhours - $att->latepmhours) * 60;
                                            } else {
                                                $att->latepmminutes = $att->latepmhours * 60;
                                                $att->latepmhours = $att->latepmhours;
                                                $att->undertimeamhours = 0;
                                                $att->lateamhours = 0;
                                                $att->lateamminutes = 0;

                                            }

                                            // $att->appliedleave = 1;
                                            // return collect($att);

                                        } else if ($att->leavedaystatus == 2) {
                                            $att->pmtimein = $employeecustomsched->pmin;
                                            $att->pmtimeout = $employeecustomsched->pmout;
                                            $att->timeinpm = $employeecustomsched->pmin;
                                            $att->timeoutpm = $employeecustomsched->pmout;
                                            $att->pmin = $employeecustomsched->pmin;
                                            $att->pmout = $employeecustomsched->pmout;
                                            // $att->leavetype = $leavedetail->leave_type;
                                            $att->status = 1;
                                            // return $att->date;


                                            $att->totalworkinghours = floor(($att->totalworkinghours + $pmhours) * 100) / 100;
                                            // $att->totalworkinghours = floor(($att->totalworkinghoursrender + $pmhours) * 100) / 100;
                                            // $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $pmhours) * 100) / 100;
                                            // $att->totalworkinghoursflexi = floor(($att->totalworkinghoursflexi + $pmhours) * 100) / 100;
                                            // $att->flexihours = floor(($att->flexihours + $pmhours) * 100) / 100;
                                            $att->totalworkinghoursrender = floor(($att->totalworkinghours) * 100) / 100;
                                            $att->totalworkinghoursflexi = floor(($att->totalworkinghours) * 100) / 100;
                                            $att->flexihours = floor(($att->totalworkinghours) * 100) / 100;

                                            if (($att->timeinam == null || $att->amtimein == null)) {
                                                $att->lateamminutes = $amhours * 60;
                                                $att->lateamhours = $amhours;
                                                $att->undertimepmhours = 0;
                                                $att->undertimeminutes = 0;
                                                $att->latepmminutes = 0;
                                                $att->latepmhours = 0;
                                            } else {

                                                $att->lateaminutes = $att->lateamhours * 60;
                                                $att->lateamhours = $att->lateamhours;
                                                $att->undertimeamhours = 0;

                                                $att->latepmhours = 0;
                                                $att->latepmminutes = 0;
                                            }
                                            $att->appliedleave = 1;
                                            // return collect($att);
                                        }
                                    } else {
                                        if ($att->leavedaystatus === 0) {
                                            $att->amtimein = $employeecustomsched->amin;
                                            $att->amtimeout = $employeecustomsched->amout;
                                            $att->pmtimein = $employeecustomsched->pmin;
                                            $att->pmtimeout = $employeecustomsched->pmout;
                                            $att->timeinam = $employeecustomsched->amin;
                                            $att->timeoutam = $employeecustomsched->amout;
                                            $att->timeinpm = $employeecustomsched->pmin;
                                            $att->timeoutpm = $employeecustomsched->pmout;
                                            $att->amin = $employeecustomsched->amin;
                                            $att->amout = $employeecustomsched->amout;
                                            $att->pmin = $employeecustomsched->pmin;
                                            $att->pmout = $employeecustomsched->pmout;
                                            $att->status = 1;
                                            $att->totalworkinghours = $totalworkinghours;
                                            $att->totalworkinghoursrender = $totalworkinghours;
                                            $att->appliedleave = 1;
                                            // $att->leavetype = $leavedetail->leave_type;
                                            $att->latepmminutes = 0;
                                            $att->latepmhours = 0;
                                            $att->undertimepmhours = 0;
                                            $att->lateamminutes = 0;
                                            $att->lateamhours = 0;
                                            $att->undertimeamhours = 0;
                                            $att->latehours = 0;
                                            $att->appliedleave = 1;


                                        } else if ($att->leavedaystatus == 1) {

                                            $att->amtimein = $employeecustomsched->amin;
                                            $att->amtimeout = $employeecustomsched->amout;
                                            $att->timeinam = $employeecustomsched->amin;
                                            $att->timeoutam = $employeecustomsched->amout;
                                            $att->amin = $employeecustomsched->amin;
                                            $att->amout = $employeecustomsched->amout;
                                            // $att->leavetype = $leavedetail->leave_type;
                                            $att->status = 1;



                                            $att->totalworkinghours = $amhours;
                                            $att->totalworkinghoursrender = $amhours;
                                            $att->totalworkinghoursflexi = $amhours;
                                            $att->flexihours = $amhours;
                                            $att->latepmminutes = 0;
                                            $att->latepmhours = 0;
                                            $att->undertimepmhours = $pmhours;
                                            $att->undertimeminutes = $pmhours * 60;
                                            // $att->appliedleave = 1;

                                            // return collect($att);

                                        } else if ($att->leavedaystatus == 2) {
                                            $att->pmtimein = $employeecustomsched->pmin;
                                            $att->pmtimeout = $employeecustomsched->pmout;
                                            $att->timeinpm = $employeecustomsched->pmin;
                                            $att->timeoutpm = $employeecustomsched->pmout;
                                            $att->pmin = $employeecustomsched->pmin;
                                            $att->pmout = $employeecustomsched->pmout;
                                            // $att->leavetype = $leavedetail->leave_type;
                                            $att->status = 1;
                                            $att->totalworkinghours = $pmhours;
                                            $att->totalworkinghours = $pmhours;
                                            // $att->totalworkinghoursrender = floor(($att->totalworkinghoursrender + $att->latepmhours) * 100) / 100;
                                            $att->totalworkinghoursflexi = $pmhours;
                                            $att->flexihours = $pmhours;

                                            $att->lateamminutes = $amhours * 60;
                                            $att->lateamhours = $amhours;
                                            $att->undertimeamhours = 0;

                                        }
                                    }

                                }


                                foreach ($attendance as $attendancefinal) {

                                    $amlate = floatval($attendancefinal->lateamminutes);
                                    $pmlate = floatval($attendancefinal->latepmminutes);
                                    $amlatehours = floatval($attendancefinal->lateamhours);
                                    $pmlatehours = floatval($attendancefinal->latepmhours);
                                    $amundertime = floatval($attendancefinal->undertimeamhours);
                                    $pmundertime = floatval($attendancefinal->undertimepmhours);



                                    // return ($amhours - $amundertime) * 60;
                                    if ($attendancefinal->amin == null && $attendancefinal->amout != null) {
                                        $attendancefinal->lateamminutes = 0; // Initialize latepmminutes
                                        // $totalworkinghoursfinal = $totalworkinghours - $attendancefinal->totalworkinghours;
                                        $attendancefinal->lateamminutes = ($amhours - $amundertime) * 60;

                                        // return $attendancefinal->lateamminutes;
                                    } else if ($attendancefinal->amin != null && $attendancefinal->amout == null) {

                                        $attendancefinal->undertimeamhours = 0; // Initialize lateamminutes
                                        $attendancefinal->lateamminutes = ($amhours - $amlatehours) * 60;

                                    } else if ($attendancefinal->pmin == null && $attendancefinal->pmout != null) {

                                        $attendancefinal->latepmminutes = 0; // Initialize latepmminutes

                                        $attendancefinal->latepmminutes = ($pmhours - $pmundertime) * 60;

                                        // }  else if ($attendancefinal->pmin != null && $attendancefinal->pmout == null) {
                                    } else if ($attendancefinal->pmin != null && $attendancefinal->pmout == null) {
                                        $attendancefinal->undertimepmhours = 0; // Initialize latepmminutes

                                        $attendancefinal->undertimepmhours = $pmhours - $pmlatehours;

                                    }

                                    $attendancefinal->latehours = ($attendancefinal->lateamminutes + $attendancefinal->latepmminutes) / 60;

                                    // $employeecustomschedPmin = new DateTime($employeecustomsched->pmin);
                                    // $attendancefinalPmin = new DateTime($attendancefinal->pmin);

                                    // $pmTimeDiff = $employeecustomschedPmin->diff($attendancefinalPmin);
                                    // $pmHours = $pmTimeDiff->format('%h');
                                    // $pmMinutes = $pmTimeDiff->format('%i');
                                    // $totalMinutespmlate = ($pmHours * 60) + $pmMinutes;

                                    $attendancefinal->lateminutes = $attendancefinal->lateamminutes + $attendancefinal->latepmminutes;
                                    // $attendancefinal->lateminutes = $totalMinutespmlate + $attendancefinal->lateamminutes;
                                    // $attendancefinal->lateamminutes = $totalMinutesamlate;
                                    $attendancefinal->undertimehours = $attendancefinal->undertimeamhours + $attendancefinal->undertimepmhours;

                                }

                                if (count($attendance) > 0) {
                                    foreach ($attendance as $time) {

                                        if ($time->totalworkinghours > 8) {
                                            $time->totalworkinghours = $totalworkinghours;
                                            $time->totalworkinghoursrender = $totalworkinghours;
                                        }
                                        $totalminuteswork = 0;
                                        $totalminuteslate = 0;
                                        $totalundertime = 0;
                                        $totalminutesundertime = 0;
                                        $totalHours = $time->totalworkinghours;
                                        if ($totalHours > 8) {
                                            $totalHours = $totalworkinghours;
                                        } else {
                                            $totalHours = $time->totalworkinghours;
                                        }
                                        // Calculate whole hours
                                        $hours = $totalHours;
                                        // Calculate remaining minutes
                                        $minutes = round(($totalHours - $hours) * 60);

                                        if ($hours != 0) {
                                            $time->totalminuteswork = ($hours * 60) + $minutes;
                                        } else {
                                            $time->totalminuteswork = 0;
                                        }


                                        // for late minutes
                                        $totalLateHours = $time->latehours;
                                        $hoursLate = floor($totalLateHours);
                                        $minuteslate = round(($totalLateHours - $hoursLate) * 60);
                                        $time->totalminuteslate = ($hoursLate * 60) + $minuteslate;
                                        $time->lateminutes = ($hoursLate * 60) + $minuteslate;

                                        // for undertime
                                        $totalundertime = $time->undertimeamhours + $time->undertimepmhours;
                                        $totalUndertimeHours = $totalundertime;
                                        $hoursUndertime = floor($totalUndertimeHours);
                                        $minutesundertime = round(($totalUndertimeHours - $hoursUndertime) * 60);
                                        $time->totalminutesundertime = ($hoursUndertime * 60) + $minutesundertime;
                                        $time->undertimeminutes = ($hoursUndertime * 60) + $minutesundertime;

                                    }







                                    // return collect($employeeinfo);
                                    // return $attendance;
                                    foreach ($attendance as $att) {
                                        if ($att->amin != null || $att->amout != null || $att->pmin != null || $att->pmout != null) {
                                            // for late
                                            if (property_exists($att, 'lateamminutes')) {
                                                $lateam = $att->lateamminutes;
                                            }
                                            if (property_exists($att, 'latepmminutes')) {
                                                $latepm = $att->latepmminutes;
                                            }
                                            // $latemin = $lateam + $latepm;
                                            // $latemin = number_format($lateam + $latepm);
                                            $latemin = $att->totalminuteslate;
                                            $latehours = $att->latehours;
                                            // if ($latemin != 0 || $latemin != null) {
                                            //     $latehours = $latemin / 60;
                                            // }

                                            // $lateamount = $latehours * $basicsalaryinfo->amountperhour; gian
                                            $lateamount = $latemin * number_format($basicsalaryinfo->amountperhour / 60, 2);

                                            $totalLateAmount += $lateamount;

                                            // return $totalLateAmount;
                                            $totalLateMin += $latemin;

                                            // for undertime
                                            // $undertimehours = $att->undertimeminutes == 0 ? 0 : $att->undertimeminutes / 60;
                                            $undertimehours = $att->totalminutesundertime / 60;
                                            if ($undertimehours != 0 || $undertimehours != null) {
                                                // $undertimemin = $undertimehours * 60;
                                                $undertimemin += $att->totalminutesundertime;
                                            }
                                            $undertimeamount = $att->totalminutesundertime * number_format($basicsalaryinfo->amountperhour / 60, 2);

                                            $totalUndertimeAmount += $undertimeamount;
                                            $totalUndertimeMin = $undertimemin;

                                            // totalworkinghours
                                            $workinghours = $att->totalminuteswork;
                                            $totalhourworks += $workinghours;

                                            //presentdays
                                            if ($att->totalworkinghours != 0) {
                                                $totalpresentdays += 1;
                                            }
                                        }
                                        //absentdays
                                        if ($att->totalworkinghours == 0) {
                                            $totalabsentdays += 1;
                                        }
                                    }

                                }

                            }
                            // ... (your existing code)

                            // Calculate total absent amount for the employee
                            if ($basicsalaryinfo->amountperday != null) {
                                $perday = $basicsalaryinfo->amountperday;
                            } else {
                                $perday = 0;
                            }

                            // Calculate total absent amount for the employee
                            if ($totalabsentdays > 0) {
                                $totalabsentamounttotal = $totalabsentdays * $basicsalaryinfo->amountperday;
                            } else {
                                $totalabsentamounttotal = 0;
                            }
                        }
                    } else {
                        if ($activedaterange) {
                            $tardys = collect($activeemployeetardy)->where('employeeid', $employeeinfo->id)->values();
                            foreach ($tardys as $tardy) {
                                if ($tardy->type == 'Regular Late' || $tardy->type == 'Regular Undertime' || $tardy->type == 'Regular Absent') {
                                    $regulardload += $tardy->totalminutes;
                                } else if ($tardy->type == 'Overload Late' || $tardy->type == 'Overload Undertime' || $tardy->type == 'Overload Absent') {
                                    $overload += $tardy->totalminutes;
                                } else if ($tardy->type == 'Emergency Late' || $tardy->type == 'Emergency Undertime' || $tardy->type == 'Emergency Absent') {
                                    $emergencyload += $tardy->totalminutes;
                                }
                            }
                        }

                        if ($regulardload > 0) {
                            if ($basicsalaryinfo) {
                                if ($basicsalaryinfo->amount == 0) {
                                    $peroras = $basicsalaryinfo->clsubjperhour;
                                    $perminute = $peroras / 60;
                                    $regulardloadtardyamount = round($regulardload * $perminute, 2);
                                } else {
                                    $kinsina = $basicsalaryinfo->amount / 2;
                                    $perday = $kinsina / 13;
                                    $perhour = $perday / 8;
                                    $amountperhour = $perhour / 60;

                                    $regulardloadtardyamount = round($regulardload * $amountperhour, 2);
                                }
                            }
                        }

                        if ($overload > 0) {
                            $peroras = $basicsalaryinfo->clsubjperhour;
                            $perminute = $peroras / 60;
                            $overloadtardyamount = round($overload * $perminute, 2);
                        }
                        if ($emergencyload > 0) {
                            $peroras = $basicsalaryinfo->clsubjperhour;
                            $perminute = $peroras / 60;
                            $emergencyloadtardyamount = round($emergencyload * $perminute, 2);
                        }

                        $basicsalaryinfo->amountperday = 0;
                        $basicsalaryinfo->amountperhour = 0;
                    }

                    // return $totalUndertimeMin;
                    // return collect($attendance);
                    // for standard allowance
                    // return $standardallowances_all;
                    $standardallowances = collect($standardallowances_all)->where('employeeid', $employeeinfo->id)->values();

                    $alltotalamount = 0;
                    if (count($standardallowances) > 0) {
                        foreach ($standardallowances as $allowancetype) {
                            $allowancetype->particulartype = 3;
                            if ($allowancetype->amountbaseonsalary == 1) {
                                $allowancetype->particularid = $allowancetype->empallowanceid;
                                $allowancetype->paymenttype = 0;
                            } else {
                                $allowancetype->particularid = $allowancetype->allowance_standardid;

                            }

                            if ($allowancetype->amountbaseonsalary === 1) {
                                if ($allowancetype->monday == 1) {
                                    $mondayAmount = $basicsalaryinfo->amountperday * $mondayCount;
                                    $alltotalamount += $mondayAmount;
                                    $allowancetype->mondayAmount = number_format($mondayAmount, 2);
                                }
                                if ($allowancetype->tuesday == 1) {
                                    $tuesdayAmount = $basicsalaryinfo->amountperday * $tuesdayCount;
                                    $alltotalamount += $tuesdayAmount;
                                    $allowancetype->tuesdayAmount = number_format($tuesdayAmount, 2);
                                }
                                if ($allowancetype->wednesday == 1) {
                                    $wednesdayAmount = $basicsalaryinfo->amountperday * $wednesdayCount;
                                    $alltotalamount += $wednesdayAmount;
                                    $allowancetype->wednesdayAmount = number_format($wednesdayAmount, 2);
                                }
                                if ($allowancetype->thursday == 1) {
                                    $thursdayAmount = $basicsalaryinfo->amountperday * $thursdayCount;
                                    $alltotalamount += $thursdayAmount;
                                    $allowancetype->thursdayAmount = number_format($thursdayAmount, 2);
                                }
                                if ($allowancetype->friday == 1) {
                                    $fridayAmount = $basicsalaryinfo->amountperday * $fridayCount;
                                    $alltotalamount += $fridayAmount;
                                    $allowancetype->fridayAmount = number_format($fridayAmount, 2);
                                }
                                if ($allowancetype->saturday == 1) {
                                    $saturdayAmount = $basicsalaryinfo->amountperday * $saturdayCount;
                                    $alltotalamount += $saturdayAmount;
                                    $allowancetype->saturdayAmount = number_format($saturdayAmount, 2);
                                }
                                if ($allowancetype->sunday == 1) {
                                    $sundayAmount = $basicsalaryinfo->amountperday * $sundayCount;
                                    $alltotalamount += $sundayAmount;
                                    $allowancetype->sundayAmount = number_format($sundayAmount, 2);
                                }
                                $allowancetype->totaldaysallowanceamount = number_format($alltotalamount, 2);

                                $allowancetype->amount = sprintf("%.2f", $alltotalamount);
                                $allowancetype->baseonattendance = 0;
                                $allowancetype->amountperday = 0;
                                $allowancetype->lock = 1;
                                $allowancetype->paidforthismonth = 0;
                                $allowancetype->totalamount = sprintf("%.2f", $alltotalamount);
                                $allowancetype->paymenttype = 0;
                                if ($basicsalaryinfo->salarybasistype == 5) {
                                    $allowancetype->perday = $basicsalaryinfo->amountperday;
                                }
                            } else {
                                // return collect($payrollperiod);

                                $eachallowance = \App\Models\HR\HRAllowances::getstandardallowances($employeeinfo->id, $payrollperiod, $allowancetype->empallowanceid, $allowancetype->id, $allowancetype->baseonattendance, $allowancetype->amountperday, $allowancetype->amountbaseonsalary);
                                // return collect($eachallowance);
                                $allowancetype->amount = $eachallowance->amount;
                                $allowancetype->baseonattendance = $eachallowance->baseonattendance;
                                $allowancetype->amountperday = $eachallowance->amountperday;
                                $allowancetype->lock = $eachallowance->lock;
                                $allowancetype->paidforthismonth = $eachallowance->paidforthismonth;
                                $allowancetype->totalamount = $eachallowance->totalamount;
                                $allowancetype->paymenttype = $eachallowance->paymenttype;
                                $allowancetype->paidstatus = $eachallowance->paidstatus;
                            }
                        }
                    }
                    // return $saturdayCount;

                    foreach ($standardallowances as $sallowancestandard) {
                        if ($sallowancestandard->baseonattendance == 1) {
                            $amountPerDay = $sallowancestandard->amountperday;
                            $totalDaysAmount = $amountPerDay * (count($dayswithattendance) + $saturdayCount);
                            $sallowancestandard->amount = $totalDaysAmount;
                            // $sallowancestandard->totalDayspresent = count($dayswithattendance);
                            $sallowancestandard->totalDayspresent = collect($attendance)->where('totalworkinghours', '>', 0)->count() + $saturdayCount;
                        } else {
                            $sallowancestandard->totalDaysAmount = 0;
                        }
                    }

                    unset($sallowancestandard); // Unset the reference to the last element

                    $holidays_within_range = json_decode(json_encode($holidays_within_range), true);

                    $filtered_holidays = $holidays_within_range;

                    // return $standardallowances;

                    //  standard deduction
                    // return $standardallowances;
                    $standarddeductions = array();
                    // return $deductiontypes;
                    if (count($deductiontypes) > 0) {
                        foreach ($deductiontypes as $deductiontype) {

                            // return collect($deductiontype);
                            $deductiontype->particulartype = 1;
                            $deductiontype->particularid = $deductiontype->id;
                            // $checkifapplied = DB::table('employee_deductionstandard')
                            //     ->where('employeeid', $request->get('employeeid'))
                            //     ->where('deduction_typeid', $deductiontype->id)
                            //     ->where('deleted','0')
                            //     ->where('status','1')
                            //     ->first();

                            $checkifapplied = collect($checkifapplied_all)->where('employeeid', $employeeinfo->id)->where('deduction_typeid', $deductiontype->id)->first();

                            if ($checkifapplied) {

                                $eachdeduction = \App\Models\HR\HRDeductions::getstandarddeductions($employeeinfo->id, $payrollperiod, $deductiontype->id);

                                if ($checkifapplied->eesamount != $eachdeduction->totalamount) {

                                    $deductiontype->amount = floor(($checkifapplied->eesamount / 2) * 100) / 100;
                                    $deductiontype->totalamount = $checkifapplied->eesamount;

                                } else {

                                    $deductiontype->amount = $eachdeduction->amount;
                                    $deductiontype->totalamount = $eachdeduction->totalamount;
                                }
                                $deductiontype->lock = $eachdeduction->lock;
                                $deductiontype->paidforthismonth = $eachdeduction->paidforthismonth;
                                $deductiontype->paymenttype = $eachdeduction->paymenttype;
                                $deductiontype->balances = $eachdeduction->balances;
                                $deductiontype->paidstatus = $eachdeduction->paidstatus;

                                if ($deductiontype->amount < 1 && count($deductiontype->balances) == 0) {
                                    if ($basicsalaryinfo->salarybasistype == 5 || $basicsalaryinfo->salarytype == 'Daily') {
                                        array_push($standarddeductions, $deductiontype);
                                    }
                                } else {
                                    if ($deductiontype->amount > 0) {
                                        array_push($standarddeductions, $deductiontype);
                                    }
                                }

                            }
                        }
                    }

                    $otherdeductions = collect($otherdeductions_all)->where('employeeid', $employeeinfo->id)->values();
                    $paidotherdeductions = collect($paidotherdeductions_all)->where('employeeid', $employeeinfo->id)->values();

                    $payrolldetails = collect($payrolldetails_all)->where('employeeid', $employeeinfo->id)->values();
                    $paiddeductionsaved = collect($paiddeduction_all)->where('employeeid', $employeeinfo->id)->values();

                    foreach ($otherdeductions as $otherdeduction) {
                        $totalotherdeductionpaid = 0;
                        foreach ($payrolldetails as $payrolldetail) {
                            if ($payrolldetail->particularid == $otherdeduction->id) {
                                $totalotherdeductionpaid += $payrolldetail->amountpaid;
                            }
                        }
                        $otherdeduction->totalotherdeductionpaid = $totalotherdeductionpaid + $otherdeduction->paidamount;
                    }


                    foreach ($otherdeductions as $otherdeduction) {
                        $remainingamount = $otherdeduction->fullamount - $otherdeduction->totalotherdeductionpaid;
                        $otherdeduction->monthlypayment = $otherdeduction->amount;
                        $otherdeduction->remainingamount = round($remainingamount, 3);

                        if ($otherdeduction->term != 0) {
                            if ($remainingamount < $otherdeduction->amount) {
                                $otherdeduction->amount = round($remainingamount, 2);
                                $otherdeduction->paidthispayroll = 1;
                            } else {
                                $otherdeduction->paidthispayroll = 0;
                            }
                        }

                    }

                    $otherdeductionsarray = array();
                    $paidotherdeductionsarray = array();
                    $lastpayroll = [];
                    $headerarr = [];


                    if (count($otherdeductions) > 0) {

                        foreach ($otherdeductions as $eachotherdeduction) {

                            if ($eachotherdeduction->fullamount == $eachotherdeduction->totalotherdeductionpaid && strpos($eachotherdeduction->description, 'PERAA') === false) {

                            } else {
                                $monthlypayment = $eachotherdeduction->monthlypayment;
                                $deductionStartDate = new DateTime($eachotherdeduction->startdate);
                                $formattedStartDate = $deductionStartDate->format('Y-m-d');



                                if ($formattedStartDate < $payrollperiod->datefrom || $formattedStartDate == $payrollperiod->datefrom || $formattedStartDate == $payrollperiod->dateto || $formattedStartDate == $payrollperiod->dateto) {

                                    $eachotherdeduction->particulartype = 2;
                                    $eachotherdeduction->particularid = $eachotherdeduction->id;
                                    $eachotherdeduction->dataid = $eachotherdeduction->deductionotherid;
                                    $amountpaid = 0;
                                    $eachotherdeduction->paidforthismonth = 0;
                                    // $eachotherdeduction->lock = 0;
                                    $eachotherdeduction->totalamount = 0;
                                    $eachotherdeduction->amounttopay = 0;
                                    $eachotherdeduction->paidstatus = 0;
                                    $eachotherdeduction->paidforthismonth = 0;
                                    $eachotherdeduction->totalpaidamount = $eachotherdeduction->totalotherdeductionpaid;

                                    $paiddeductions = DB::table('hr_payrollv2history')
                                        ->select(DB::raw('SUM(`amountpaid`) as amountpaid'))
                                        ->leftjoin('hr_payrollv2historydetail', 'hr_payrollv2history.id', '=', 'hr_payrollv2historydetail.headerid')
                                        ->leftjoin('hr_payrollv2', 'hr_payrollv2history.payrollid', '=', 'hr_payrollv2.id')
                                        ->where('hr_payrollv2history.employeeid', $employeeinfo->id)
                                        ->where('hr_payrollv2history.released', '1')
                                        ->where('hr_payrollv2history.deleted', '0')
                                        ->where('hr_payrollv2.deleted', '0')
                                        ->where('hr_payrollv2historydetail.particulartype', '2')
                                        ->where('hr_payrollv2historydetail.particularid', $eachotherdeduction->id)
                                        ->where('hr_payrollv2history.payrollid', '<=', $payrollperiod->id)
                                        ->first()->amountpaid;

                                    // determine payroll cutoff if first or second /last
                                    if ($payrollperiod) {
                                        $datefrom_day = date('d', strtotime($payrollperiod->datefrom));
                                        $dateto_day = date('d', strtotime($payrollperiod->dateto));
                                        $payrollyear = date('Y', strtotime($payrollperiod->dateto));
                                        $payrollmonth = date('m', strtotime($payrollperiod->dateto));

                                        if ($datefrom_day == 1 && $dateto_day == 15) {
                                            $cutoff = 1; // first cutoff of the month
                                        } else {
                                            $cutoff = 2; // second cutoff of the month
                                        }
                                    }

                                    if ($cutoff == 2) {
                                        if ($eachotherdeduction->description != 'PERAA') {
                                            $firstpayroll_cutoff = DB::table('hr_payrollv2')
                                                ->where('deleted', 0)
                                                ->where('id', '!=', $payrollperiod->id)
                                                ->whereYear('dateto', $payrollyear)
                                                ->whereMonth('dateto', $payrollmonth)
                                                ->first();

                                            $headerid = DB::table('hr_payrollv2history')
                                                ->where('hr_payrollv2history.employeeid', $employeeinfo->id)
                                                ->where('deleted', 0)
                                                ->where(function ($query) use ($payrollperiod, $firstpayroll_cutoff) {
                                                    $query->where('payrollid', $payrollperiod->id)
                                                        ->orWhere('payrollid', $firstpayroll_cutoff->id ?? null);
                                                })
                                                ->get();

                                            $headerid = $headerid->pluck('id');
                                            $headerarr = $headerid;
                                            $paid_deductions = DB::table('hr_payrollv2historydetail')
                                                ->select('hr_payrollv2historydetail.*')
                                                ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                                                ->whereIn('headerid', $headerarr)
                                                ->where('particulartype', 2)
                                                ->where('particularid', $eachotherdeduction->id)
                                                ->where('hr_payrollv2.deleted', '0')
                                                ->where('hr_payrollv2historydetail.employeeid', $employeeinfo->id)
                                                ->where('hr_payrollv2historydetail.deleted', '0')
                                                ->where('hr_payrollv2historydetail.deductionid', $eachotherdeduction->deductionotherid)
                                                ->get();
                                            $firstpayrol = null;
                                            if ($firstpayroll_cutoff) {
                                                $firstpayrol = DB::table('hr_payrollv2historydetail')
                                                    ->select('hr_payrollv2historydetail.*')
                                                    ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                                                    ->where('particulartype', 2)
                                                    ->where('hr_payrollv2.deleted', '0')
                                                    ->where('hr_payrollv2historydetail.employeeid', $employeeinfo->id)
                                                    ->where('hr_payrollv2historydetail.payrollid', $firstpayroll_cutoff->id)
                                                    ->where('hr_payrollv2historydetail.paidstatus', 1)
                                                    ->where('hr_payrollv2historydetail.totalamount', '!=', 0)
                                                    ->where('hr_payrollv2historydetail.deleted', '0')
                                                    ->where('hr_payrollv2historydetail.deductionid', $eachotherdeduction->deductionotherid)
                                                    ->first();
                                            }

                                            if ($firstpayrol) {
                                                if ($firstpayrol->paymenttype == 0) {
                                                    $eachotherdeduction->paidforthismonth = 0;
                                                    $eachotherdeduction->paidstatus = 1;
                                                    $eachotherdeduction->paymenttype = 1;

                                                } else if ($firstpayrol->paymenttype == 1) {
                                                    $remainingamountod = $eachotherdeduction->fullamount - $eachotherdeduction->totalpaidamount;

                                                    if ($eachotherdeduction->remainingamount < (float) $eachotherdeduction->monthlypayment) {
                                                        $eachotherdeduction->paymenttype = 0;
                                                        $eachotherdeduction->amounttopay = $remainingamountod;
                                                        $eachotherdeduction->paidstatus = 1;

                                                    } else {
                                                        $eachotherdeduction->paymenttype = 1;
                                                        $eachotherdeduction->paidstatus = 1;
                                                        $eachotherdeduction->amounttopay = $eachotherdeduction->monthlypayment / 2;
                                                    }
                                                }
                                            } else {

                                                foreach ($paid_deductions as $paiddeduction) {
                                                    //  first cutoff oth the month
                                                    if ($paiddeduction->payrollid == $payrollperiod->id) {
                                                        if ($paiddeduction->paidstatus == 1) {
                                                            $eachotherdeduction->paidstatus = 1;
                                                            $amountpaid = $paiddeduction->amountpaid;
                                                            if ($amountpaid == $eachotherdeduction->amount) {

                                                                $eachotherdeduction->paymenttype = 0;
                                                                $eachotherdeduction->amounttopay = $amountpaid;

                                                            } else if ($amountpaid == floor(($eachotherdeduction->amount / 2) * 100) / 100) {

                                                                $eachotherdeduction->paymenttype = 1;
                                                                $eachotherdeduction->amounttopay = $amountpaid;

                                                            } else {
                                                                $eachotherdeduction->paymenttype = 0;
                                                                $eachotherdeduction->amounttopay = $eachotherdeduction->amount;

                                                            }

                                                        } else {

                                                            // wala nabayran or iya gi unselect

                                                        }

                                                    } else {

                                                        $amountpaid = $paiddeduction->amountpaid;

                                                        if ($amountpaid == $eachotherdeduction->amount) {
                                                            $eachotherdeduction->paidforthismonth = 1;
                                                            $eachotherdeduction->amounttopay = 0;
                                                            $eachotherdeduction->paidstatus = 0;
                                                            $eachotherdeduction->amounttobededuct = 0;
                                                            $eachotherdeduction->paymenttype = 0;

                                                        } else if ($amountpaid == floor(($eachotherdeduction->amount / 2) * 100) / 100) {
                                                            $eachotherdeduction->paymenttype = 1;
                                                            $eachotherdeduction->amounttopay = $amountpaid;
                                                            $eachotherdeduction->amounttobededuct = $amountpaid;
                                                            $eachotherdeduction->paidstatus = 1;
                                                        } else {
                                                            $eachotherdeduction->paymenttype = 0;
                                                            $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                                            $eachotherdeduction->amounttobededuct = $eachotherdeduction->amount;
                                                            $eachotherdeduction->paidstatus = 1;
                                                        }

                                                    }
                                                }
                                            }



                                            array_push($otherdeductionsarray, $eachotherdeduction);
                                        } else {
                                            $eachotherdeduction->paidforthismonth = 1;
                                            $eachotherdeduction->paidstatus = 0;
                                            $eachotherdeduction->paymenttype = 0;

                                            array_push($otherdeductionsarray, $eachotherdeduction);
                                        }

                                    } else {
                                        $headerid = DB::table('hr_payrollv2history')
                                            ->where('deleted', 0)
                                            ->where('hr_payrollv2history.employeeid', $employeeinfo->id)
                                            ->where('payrollid', $payrollperiod->id)
                                            ->first();

                                        if ($headerid) {
                                            $headerarr[] = $headerid;
                                        }

                                        // this is to retirieve the paid deductions
                                        $paid_deductions = DB::table('hr_payrollv2historydetail')
                                            ->select('hr_payrollv2historydetail.*')
                                            ->join('hr_payrollv2', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2.id')
                                            ->whereIn('headerid', $headerarr)
                                            ->where('particulartype', 2)
                                            ->where('particularid', $eachotherdeduction->id)
                                            ->where('hr_payrollv2.deleted', '0')
                                            ->where('hr_payrollv2historydetail.employeeid', $employeeinfo->id)
                                            ->where('hr_payrollv2historydetail.deleted', '0')
                                            ->where('hr_payrollv2historydetail.deductionid', $eachotherdeduction->deductionotherid)
                                            ->get();

                                        if (count($paid_deductions) > 0) {

                                            if ($eachotherdeduction->deductionstatus == 0) {
                                                // if deduction is need to be paid per kinsina
                                                $eachotherdeduction->paymenttype = 1; // 0 = full; 1 = half;

                                                foreach ($paid_deductions as $paiddeduction) {

                                                    //  first cutoff oth the month
                                                    if ($paiddeduction->payrollid == $payrollperiod->id) {


                                                        if ($paiddeduction->paidstatus == 1) {

                                                            $eachotherdeduction->paidstatus = 1;

                                                            if ($eachotherdeduction->remainingamount == $monthlypayment / 2) {
                                                                $eachotherdeduction->paymenttype = 0;
                                                                $eachotherdeduction->amounttopay = $eachotherdeduction->remainingamount;
                                                                $eachotherdeduction->paidforthismonth = 1;
                                                            } else if ($eachotherdeduction->remainingamount <= $monthlypayment / 2) {
                                                                $eachotherdeduction->paymenttype = 0;
                                                                $eachotherdeduction->amounttopay = $eachotherdeduction->remainingamount;
                                                                $eachotherdeduction->paidforthismonth = 1;
                                                            } else {
                                                                $amountpaid = $paiddeduction->amountpaid;
                                                                if ($amountpaid == $eachotherdeduction->amount) {
                                                                    $eachotherdeduction->paymenttype = 0;
                                                                    $eachotherdeduction->amounttopay = $amountpaid;
                                                                    $eachotherdeduction->paidforthismonth = 1;

                                                                } else if ($amountpaid == floor(($eachotherdeduction->amount / 2) * 100) / 100) {

                                                                    $eachotherdeduction->paymenttype = 1;
                                                                    $eachotherdeduction->amounttopay = $amountpaid;

                                                                } else {
                                                                    $eachotherdeduction->paymenttype = 3;
                                                                    $eachotherdeduction->amounttopay = $amountpaid;

                                                                }
                                                            }




                                                        } else {

                                                            // wala nabayran or iya gi unselect

                                                        }
                                                    }
                                                }

                                            } else {

                                                // if deduction is need to be paid per binulan
                                                $eachotherdeduction->paidstatus = 1; // 0 = full; 1 = half;
                                                $eachotherdeduction->paymenttype = 0; // 0 = full; 1 = half;
                                                $eachotherdeduction->amounttopay = $eachotherdeduction->amount;

                                            }

                                        } else {

                                            if ($eachotherdeduction->deductionstatus == 0) {
                                                if ($eachotherdeduction->description == 'PERAA') {
                                                    $eachotherdeduction->amounttopay = $eachotherdeduction->amount;
                                                    $eachotherdeduction->paidstatus = 1; // 0 = full; 1 = half;
                                                    $eachotherdeduction->paymenttype = 0; // 0 = full; 1 = half;
                                                } else {
                                                    // if deduction is need to be paid per kinsina
                                                    $eachotherdeduction->paymenttype = 1; // 0 = full; 1 = half;
                                                    $eachotherdeduction->paidstatus = 1;
                                                    $eachotherdeduction->amounttopay = floor(($eachotherdeduction->amount / 2) * 100) / 100;
                                                }

                                            } else {
                                                // if deduction is need to be paid per binulan
                                                $eachotherdeduction->paidstatus = 1; // 0 = full; 1 = half;
                                                $eachotherdeduction->paymenttype = 0; // 0 = full; 1 = half;
                                                $eachotherdeduction->amounttopay = $eachotherdeduction->amount;

                                            }
                                        }

                                        array_push($otherdeductionsarray, $eachotherdeduction);


                                    }
                                }
                            }

                        }
                    }

                    $otherdeductions = $otherdeductionsarray;


                    if (count($standardallowances) > 0) {
                        foreach ($standardallowances as $standardallowance) {
                            if ($standardallowance->amountbaseonsalary == 1) {
                                $standardallowance->paidstatus = 1;
                                $standardallowance->paymenttype = 0;
                            } else {
                                $standardallowance->paidstatus = 1;
                                $standardallowance->paymenttype = 1;
                            }

                        }
                    }
                    $otheraddedearnings = collect($otheraddedearnings_all)->where('employeeid', $employeeinfo->id)->values();
                    $otherids = $otheraddedearnings->pluck('id');
                    $notdeletedearnings = collect($otheraddedearningsparticulars_all)->where('employeeid', $employeeinfo->id)->where('deleted', 0)->where('additionalid', '!=', 0)->whereNotIn('additionalid', $otherids)->values();

                    if (count($otheraddedearnings) > 0) {
                        foreach ($otheraddedearnings as $otheraddedearning) {
                            $otheraddedearning->dataid = $otheraddedearning->type;
                            $otheraddedearning->paidstatus = 1;
                            $otheraddedearning->paymenttype = 0;
                            $otheraddedearning->totalamount = $otheraddedearning->amount;
                            $otheraddedearning->particulartype = null;
                            $otheraddedearning->particularid = null;
                        }
                    }

                    if (count($otheraddedearnings) > 0) {
                        $otheraddedearningsparticulars = collect($otheraddedearningsparticulars_all)->where('employeeid', $employeeinfo->id)->where('additionalid', 0)->values();
                    } else {
                        $otheraddedearningsparticulars = collect($otheraddedearningsparticulars_all)->where('employeeid', $employeeinfo->id)->values();
                    }



                    if (count($otheraddedearningsparticulars) > 0) {
                        foreach ($otheraddedearningsparticulars as $otheraddedearningsparticular) {
                            $otheraddedearningsparticulars->dataid = $otheraddedearningsparticular->type;
                            $otheraddedearningsparticulars->paidstatus = 1;
                            $otheraddedearningsparticulars->paymenttype = 0;
                            $otheraddedearningsparticulars->particulartype = null;
                            $otheraddedearningsparticulars->particularid = null;

                        }
                    }
                    // return $otheraddedearnings;

                    $otheraddeddeductionsparticulars = collect($otheraddeddeductionsparticulars_all)->where('employeeid', $employeeinfo->id)->where('additionalid', 0)->values();

                    // return $otheraddeddeductionsparticulars;
                    if (count($otheraddeddeductionsparticulars) > 0) {
                        foreach ($otheraddeddeductionsparticulars as $otheraddeddeductionsparticular) {
                            $otheraddeddeductionsparticulars->dataid = $otheraddeddeductionsparticular->type;
                            $otheraddeddeductionsparticulars->paidstatus = 1;
                            $otheraddeddeductionsparticulars->paymenttype = 0;
                            $otheraddeddeductionsparticulars->particulartype = null;
                            $otheraddeddeductionsparticulars->particularid = null;
                        }
                    }

                    // return $otheraddeddeductionsparticulars;
                    // other added Deductions
                    $otheraddeddeductions = collect($otheraddeddeductions_all)->where('employeeid', $employeeinfo->id)->values();
                    if (count($otheraddeddeductions) > 0) {
                        foreach ($otheraddeddeductions as $otheraddeddeduction) {
                            $otheraddeddeduction->dataid = $otheraddeddeduction->type;
                            // $otheraddeddeduction->paidstatus = 1;
                            $otheraddeddeduction->paymenttype = 0;
                            $otheraddeddeduction->totalamount = $otheraddeddeduction->amount;

                            $otheraddeddeduction->particulartype = null;
                            $otheraddeddeduction->particularid = null;
                        }
                    }

                    if (count($leavedetails) > 0) {
                        foreach ($leavedetails as $leavedetail) {
                            $leaveamounttotal += $leavedetail->amount;
                        }
                    }

                    // return $otheraddedearnings;

                    if (count($standardallowances) > 0) {
                        foreach ($standardallowances as $standardallowance) {
                            if ($standardallowance->amountbaseonsalary == 1) {
                                $standardallowancesamount += $standardallowance->totalamount;

                            } else if ($standardallowance->baseonattendance == 1) {
                                $standardallowancesamount += $standardallowance->amount;
                                $standardallowance->paymenttype = 0;

                            } else {
                                $standardallowancesamount += $standardallowance->amount / 2;
                                // return $standardallowancesamount;
                            }
                        }
                    }
                    if (count($otheraddedearningsparticulars) > 0) {
                        foreach ($otheraddedearningsparticulars as $otheraddedearningsparticular) {
                            $otheraddedearningsparticularsamount += $otheraddedearningsparticular->amount;
                        }
                    }


                    if (count($otheraddedearnings) > 0) {
                        foreach ($otheraddedearnings as $otheraddedearning) {
                            $otheraddedearningsamount += $otheraddedearning->amount;
                        }
                    }
                    // return $otheraddedearnings;

                    // return $otheraddedearningsparticulars;

                    if (count($standarddeductions) > 0) {
                        foreach ($standarddeductions as $standarddeduction) {
                            $standarddeductionsamount += floor(($standarddeduction->totalamount / 2) * 100) / 100;
                            // $standarddeductionsamount += ($standarddeduction->totalamount / 2);
                        }
                    }
                    // return $standarddeductions;
                    if (count($otherdeductions) > 0) {
                        foreach ($otherdeductions as $otherdeduction) {
                            $otherdeduction->totalamount = $otherdeduction->amount;

                            if ($otherdeduction->paidstatus == 1) {
                                // if ($otherdeduction->description == 'PERAA') {
                                if ($otherdeduction->deductionstatus == 1) {

                                    $otherdeductionsamount += $otherdeduction->amount;
                                } else if ($otherdeduction->deductionstatus == 0 && $otherdeduction->paymenttype == 0) {

                                    $otherdeductionsamount += floor(($otherdeduction->amount) * 100) / 100;
                                } else {
                                    $otherdeductionsamount += floor(($otherdeduction->amount / 2) * 100) / 100;
                                }
                            }

                        }
                    }

                    // return $otheraddeddeductionsparticulars;
                    if (count($otheraddeddeductionsparticulars) > 0) {
                        foreach ($otheraddeddeductionsparticulars as $otheraddeddeductionsparticular) {
                            // return 'collect($otheraddeddeductionsparticular)';
                            $otheraddeddeductionsparticularsamount += $otheraddeddeductionsparticular->amount;
                        }
                    }
                    if (count($otheraddeddeductions) > 0) {
                        foreach ($otheraddeddeductions as $otheraddeddeduction) {
                            $otheraddeddeductionsamount += $otheraddeddeduction->amount;
                        }
                    }


                    $particulars = array_merge($standardallowances->toArray(), $standarddeductions, $otherdeductions, $otheraddedearnings->toArray(), $otheraddeddeductions->toArray());
                    if ($otheraddedearningsamount > 0) {
                        $otheraddedearningsamount = round($otheraddedearningsamount, 2);
                    }
                    if ($otheraddedearningsparticularsamount > 0) {
                        $otheraddedearningsparticularsamount = round($otheraddedearningsparticularsamount, 2);
                    }

                    $totalearningamount = $standardallowancesamount + $otheraddedearningsamount + $otheraddedearningsparticularsamount;
                    if (!empty($basicsalaryinfo->amount)) {
                        $totalearningamount += floor(($basicsalaryinfo->amount / 2) * 100) / 100;
                    }


                    if ($standarddeductionsamount > 0) {
                        if ($employeeinfo->departmentid == 6) {
                            $standarddeductionsamount = 0;
                        } else {
                            $standarddeductionsamount = $standarddeductionsamount;
                        }
                    }

                    if ($otherdeductionsamount > 0) {
                        $otherdeductionsamount = $otherdeductionsamount;
                    }
                    if ($totalUndertimeAmount > 0) {
                        $totalUndertimeAmount = $totalUndertimeAmount;
                    }


                    if ($employeeinfo->departmentid == null) {
                        $totalLateAmount = 0;

                    } else {

                        $tardyonattendance = DB::table('hr_tardinesscomp')
                            ->where('departmentid', $employeeinfo->departmentid)
                            ->first();

                        if ($tardyonattendance) {
                            if ($tardyonattendance->isactive == 1) {
                                if ($totalLateAmount > 0) {
                                    $totalLateAmount = round($totalLateAmount, 2);
                                }
                            } else {
                                $totalLateAmount = 0;
                                $totalLateAmount = 0;
                                $totalLateMin = 0;
                            }
                        } else {
                            $totalLateAmount = 0;
                            $totalLateAmount = 0;
                            $totalLateMin = 0;
                        }

                    }

                    if ($otheraddeddeductionsamount > 0) {
                        $otheraddeddeductionsamount = $otheraddeddeductionsamount;
                    }
                    if ($otheraddeddeductionsparticularsamount > 0) {
                        $otheraddeddeductionsparticularsamount = $otheraddeddeductionsparticularsamount;
                    }

                    if ($totalabsentamounttotal > 0) {
                        $totalabsentamounttotal = floor($totalabsentamounttotal * 100) / 100;
                    }


                    $totaldeductionamount = round(($standarddeductionsamount + $otherdeductionsamount + $totalUndertimeAmount + $totalLateAmount + $otheraddeddeductionsamount + $totalabsentamounttotal + $otheraddeddeductionsparticularsamount + $regulardloadtardyamount + $overloadtardyamount + $emergencyloadtardyamount), 2);
                    // return $standarddeductionsamount;

                    if ($totalearningamount > 0) {
                        $totalearningamount = round($totalearningamount, 2);
                    }
                    // if ($totaldeductionamount > 0) {
                    //     $totaldeductionamount = floor($totaldeductionamount * 100) / 100;
                    // }
                    // return $basicsalaryinfo->amountperhour;
                    // return $totalearningamount;
                    $netsalaryamount = round($totalearningamount - $totaldeductionamount, 2);

                    $employeeinfo->lateamounttotal = $totalLateAmount;
                    $employeeinfo->latemintotal = $totalLateMin;
                    $employeeinfo->undertimeamounttotal = $totalUndertimeAmount;
                    $employeeinfo->undertimemintotal = $totalUndertimeMin;
                    $employeeinfo->workinghourstotal = $totalhourworks;
                    $employeeinfo->amountperday = number_format($basicsalaryinfo->amountperday, 2);
                    $employeeinfo->amountperhour = round($basicsalaryinfo->amountperhour, 2);
                    $employeeinfo->presentdays = $totalpresentdays + $saturdayCount;
                    $employeeinfo->absentdays = $totalabsentdays;
                    $employeeinfo->totalabsentamount = $totalabsentamounttotal;
                    $employeeinfo->totalearning = $totalearningamount;
                    $employeeinfo->totaldeduction = $totaldeductionamount;
                    $employeeinfo->netsalary = $netsalaryamount;
                    // return $totalearningamount;

                    $checkhistoryifexists = collect($checkhistoryifexists_all)->where('payrollid', $payrollinseredid)->where('employeeid', $employeeinfo->id)->first();

                    // $checkhistoryifexists = DB::table('hr_payrollv2history')
                    //     ->where('payrollid', $payrollinseredid)
                    //     ->where('employeeid', $employeeinfo->id)
                    //     ->where('deleted','0')
                    //     ->first();


                    if (!$checkhistoryifexists) {
                        $basicSalaryAmount = $basicsalaryinfo->amount ?? 0;
                        if ($basicSalaryAmount > 0) {
                            $basicSalaryAmount = $basicSalaryAmount / 2;
                        }
                        $headerid = DB::table('hr_payrollv2history')
                            ->insertGetId([
                                'dailyrate' => $basicsalaryinfo->amountperday ?? null,
                                'payrollid' => $payrollinseredid,
                                'employeeid' => $employeeinfo->id,
                                'lateminutes' => $employeeinfo->latemintotal,
                                'lateamount' => $employeeinfo->lateamounttotal,
                                'undertimeminutes' => $employeeinfo->undertimemintotal,
                                'undertimeamount' => $employeeinfo->undertimeamounttotal,
                                'totalhoursworked' => $employeeinfo->workinghourstotal,
                                'totalearning' => $employeeinfo->totalearning,
                                'totaldeduction' => $employeeinfo->totaldeduction,
                                'amountperday' => $employeeinfo->amountperday,
                                'presentdays' => $employeeinfo->presentdays,
                                'absentdays' => $employeeinfo->absentdays,
                                'daysabsentamount' => $employeeinfo->totalabsentamount,
                                'basicsalaryamount' => floor($basicSalaryAmount * 100) / 100,
                                'netsalary' => $employeeinfo->netsalary,
                                'basicsalarytype' => $basicsalaryinfo->salarytype ?? null,
                                'monthlysalary' => $basicsalaryinfo->amount ?? null,
                                'clregularloadamount' => null,
                                'cloverloadloadamount' => null,
                                'clparttimeloadamount' => null,
                                'regulartardyamount' => $regulardloadtardyamount,
                                'overloadtardyamount' => $overloadtardyamount,
                                'emergencyloadtardyamount' => $emergencyloadtardyamount,
                                'configured' => 1,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);

                        // return 1;
                        // if(count($leaves)>0)
                        // { 
                        //     foreach ($leaves as $eachleave) {
                        //         DB::table('hr_payrollv2historydetail')
                        //             ->insert([
                        //                 'payrollid'          => $payrollid,
                        //                 'employeeid'         => $employeeid,
                        //                 'headerid'           => $headerid,
                        //                 'description'        => $eachleave->description,
                        //                 'totalamount'        => str_replace(',', '', $eachleave->totalamount),
                        //                 'amountpaid'         => str_replace(',', '', $eachleave->amountpaid),
                        //                 'totalpaidamount'    => isset($eachleave->totalamountpaid) ? $eachleave->totalamountpaid : null,
                        //                 'days'               => 1,
                        //                 'particularid'       => $eachleave->particularid,
                        //                 'deductionid'        => ($eachleave->particulartype == 2) ? $eachleave->dataid : null,
                        //                 'employeeleaveid'    => $eachleave->employeeleaveid ?? 0,
                        //                 'leavedateid'        => $eachleave->ldateid ?? 0,
                        //                 'createdby'          => auth()->user()->id,
                        //                 'createddatetime'    => date('Y-m-d H:i:s')
                        //             ]);
                        //     }
                        // }

                        // return $particulars;
                        $paymenttypes = 0;
                        if (count($particulars) > 0) {
                            foreach ($particulars as $particular) {
                                if ($particular->paidstatus == 1) {
                                    switch ($particular->particulartype) {
                                        case 1:
                                            if ($particular->paymenttype == 0) {
                                                $paymenttypes = 0;
                                                $amountpaid = $particular->amount;

                                            } else {
                                                $paymenttypes = $particular->paymenttype;
                                                $amountpaid = $particular->amount / 2;
                                            }

                                            break;
                                        case 2:
                                            // if ($particular->description == 'PERAA') {
                                            //     $amountpaid = $particular->amount;
                                            //     if ($particular->paymenttype == 0) {
                                            //         $paymenttypes = 0;
                                            //     }
                                            // } else {
                                            if ($particular->paymenttype == 0) {
                                                $paymenttypes = 0;
                                                $amountpaid = $particular->amount;

                                            } else {
                                                $paymenttypes = $particular->paymenttype;
                                                $amountpaid = $particular->amount / 2;

                                            }
                                            // }

                                            break;
                                        case 3:
                                            if ($particular->amountbaseonsalary == 1) {
                                                $amountpaid = $particular->amount;
                                                $paymenttypes = 2;
                                            } else {
                                                if ($particular->paymenttype == 0) {
                                                    $paymenttypes = 0;
                                                    $amountpaid = $particular->amount;

                                                } else {
                                                    $paymenttypes = $particular->paymenttype;
                                                    $amountpaid = $particular->amount / 2;

                                                }
                                            }
                                        case NULL:
                                            // $amountpaid = $particular->amount / 2;
                                            $amountpaid = $particular->amount;

                                        // Add more cases if needed
                                        default:
                                        // Handle the default case if particulartype doesn't match any of the specified cases
                                        // break;
                                    }


                                    $detailid = DB::table('hr_payrollv2historydetail')
                                        ->insertGetId([
                                            'payrollid' => $payrollinseredid,
                                            'employeeid' => $employeeinfo->id,
                                            'headerid' => $headerid,
                                            'description' => $particular->description,
                                            'totalamount' => floor($particular->amount * 100) / 100,
                                            'amountpaid' => floor($amountpaid * 100) / 100,
                                            'paymenttype' => $paymenttypes,
                                            'totalpaidamount' => isset($particular->totalamountpaid) ? floor($particular->totalamountpaid * 100) / 100 : null,
                                            'particulartype' => $particular->particulartype,
                                            'particularid' => $particular->particularid,
                                            'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                            'createdby' => auth()->user()->id,
                                            'createddatetime' => date('Y-m-d H:i:s'),
                                            'paidstatus' => 1

                                        ]);

                                    // $detailid = DB::table('hr_payrollv2historydetail')
                                    //     ->insertGetId([
                                    //         'payrollid'             => $payrollid,
                                    //         'employeeid'            => $employeeinfo->,
                                    //         'headerid'              => $headerid,
                                    //         'description'           => $particular->description,
                                    //         'totalamount'           => str_replace( ',', '', $particular->totalamount),
                                    //         'amountpaid'           => str_replace( ',', '', $particular->amountpaid),
                                    //         'paymenttype'           => $particular->paymenttype,
                                    //         'totalpaidamount'           => isset($particular->totalamountpaid) ? $particular->totalamountpaid : null,
                                    //         'particulartype'           => $particular->particulartype,
                                    //         'particularid'           => $particular->particularid,
                                    //         'deductionid' => ($particular->particulartype == 2) ? $particular->dataid : null,
                                    //         'createdby'             => auth()->user()->id,
                                    //         'createddatetime'       => date('Y-m-d H:i:s'),
                                    //         'paidstatus'       => isset($particular->particulartype) && ($particular->particulartype == 1 || $particular->particulartype == 2 || $particular->particulartype == 3) ? $particular->paidstatus : 0

                                    //     ]);

                                    // $balance = $particular->totalamount - $particular->amountpaid;
                                    // if($balance>0.00)
                                    // {
                                    //     DB::table('hr_payrollv2balance')
                                    //     ->insert([
                                    //         'payrollid'             => $payrollid,
                                    //         'detailid'            => $detailid,
                                    //         'balance'              => str_replace( ',', '', $balance),
                                    //         'createdby'             => auth()->user()->id,
                                    //         'createddatetime'       => date('Y-m-d H:i:s')
                                    //     ]);
                                    // }
                                }

                            }
                        }
                        if (count($particulars) > 0) {
                            foreach ($particulars as $eachparticular) {
                                if ($eachparticular->particulartype == null) {
                                    DB::table('hr_payrollv2addparticular')
                                        ->insert([
                                            'payrollid' => $payrollinseredid,
                                            'employeeid' => $employeeinfo->id,
                                            'headerid' => $headerid,
                                            'description' => $eachparticular->description,
                                            'amount' => (float) $eachparticular->amount,
                                            'type' => $eachparticular->type,
                                            'createdby' => auth()->user()->id,
                                            'createddatetime' => date('Y-m-d H:i:s'),
                                            'additionalid' => $eachparticular->id
                                        ]);
                                }

                            }
                        }
                    }
                    // }

                }

                // return $basicsalaryinfo;

                return 1;

            } catch (\Exception $error) {
                // return $error;
                // Log the error for future reference if needed
                \Log::error($error);

                return $error;
                // Return a meaningful response to the client
                return response()->json([
                    'message' => 'An error occurred. Please try again later.',
                    'error' => $error->getMessage(), // Optionally include the error message
                ], 500); // You can adjust the HTTP status code as needed
            }
        }

    }


    public static function getstandarddeductions($employeeid)
    {
        $dates = explode(' - ', $request->get('dates'));
        $datefrom = date('Y-m-d', strtotime($dates[0]));
        $dateto = date('Y-m-d', strtotime($dates[1]));
        try {
            DB::table('hr_payrollv2')
                ->insert([
                    'datefrom' => $datefrom,
                    'dateto' => $dateto,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);

            return 1;
        } catch (\Exception $error) {
            return 0;
        }
    }
    public function exportpayslip(Request $request)
    {

        $schoolinfo = DB::table('schoolinfo')
            ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
            ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
            ->select('schoolinfo.*', 'refregion.regDesc', 'refcitymun.citymunDesc')
            ->get();

        $employeeinfo = DB::table('teacher')
            ->select('teacher.*', 'employee_personalinfo.gender', 'utype', 'teacher.id as employeeid', 'employee_personalinfo.departmentid')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.id', $request->get('employeeid'))
            ->where('teacher.deleted', '0')
            ->first();

        $preparedby = DB::table('teacher')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.userid', auth()->user()->id)
            ->where('teacher.deleted', '0')
            ->first();



        $payrollid = $request->get('payrollid');
        if ($request->get('exporttype') == 1) {
            $employeeid = $request->get('employeeid');

            // =====================================================================================================================

            $payrollv2history = DB::table('hr_payrollv2history')
                ->leftJoin('hr_payrollv2', 'hr_payrollv2history.payrollid', '=', 'hr_payrollv2.id')
                ->where('hr_payrollv2history.released', 1)
                // ->where('hr_payrollv2history.payrollid',  $request->get('payrollid'))
                ->where('hr_payrollv2history.employeeid', $employeeid)
                ->get();

            $empotherdeductions = DB::table('employee_deductionother')
                ->where('deleted', 0)
                ->where('employeeid', $employeeid)
                ->get();

            $otherdeductions = Db::table('employee_deductionother')
                ->where('employee_deductionother.employeeid', $employeeid)
                ->where('employee_deductionother.paid', '0')
                ->where('employee_deductionother.status', '1')
                ->where('employee_deductionother.deleted', '0')
                // ->where('employee_deductionother.description', 'TUITION')
                ->where('deductionotherid', '!=', null)
                ->get();

            // return $otherdeductions;

            $payrolldetails = DB::table('hr_payrollv2historydetail')
                ->leftJoin('hr_payrollv2history', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2history.payrollid')
                ->where('hr_payrollv2historydetail.particulartype', 2)
                ->where('hr_payrollv2historydetail.employeeid', $employeeid)
                ->where('hr_payrollv2history.employeeid', $employeeid)
                ->where('hr_payrollv2historydetail.deleted', 0)
                ->where('hr_payrollv2history.released', 1)
                ->where('hr_payrollv2historydetail.paidstatus', 1)
                ->get();


            foreach ($empotherdeductions as $otherdeduction) {
                $totalotherdeductionpaid = 0;
                $interest = 0;
                foreach ($payrolldetails as $payrolldetail) {
                    if ($payrolldetail->particularid == $otherdeduction->id) {
                        $totalotherdeductionpaid += $payrolldetail->amountpaid;
                        $interest = $otherdeduction->od_interest / 100;
                        $interest = $interest * $otherdeduction->fullamount;
                        $interest = $interest + $otherdeduction->fullamount;
                    }
                }
                $otherdeduction->totalotherdeductionpaid = $totalotherdeductionpaid + $otherdeduction->paidamount;
                $otherdeduction->interestamount = $interest;
                $otherdeduction->totalamountpaid = $totalotherdeductionpaid + $otherdeduction->paidamount;

                $otherdeduction->totalotherdeductionpaid = round($otherdeduction->totalotherdeductionpaid, 2, PHP_ROUND_HALF_UP);
                $otherdeduction->totalamountpaid = round($otherdeduction->totalamountpaid, 2, PHP_ROUND_HALF_UP);
            }

            // return $empotherdeductions;

            // foreach ($otherdeductions as $otherdeduction) {
            //     $totalotherdeductionpaid = 0;
            //     $interest = 0;
            //     foreach ($payrolldetails as $payrolldetail) {
            //         if ($payrolldetail->deductionid == $otherdeduction->deductionotherid) {
            //             $totalotherdeductionpaid += $payrolldetail->amountpaid;
            //             $interest = $otherdeduction->od_interest / 100;
            //             $interest = $interest * $otherdeduction->fullamount;
            //             $interest = $interest + $otherdeduction->fullamount;
            //         }
            //     }
            //     $otherdeduction->totalotherdeductionpaid = $totalotherdeductionpaid + $otherdeduction->paidamount;
            //     $otherdeduction->interestamount = $interest;
            //     $otherdeduction->totalamountpaid = $totalotherdeductionpaid + $otherdeduction->paidamount;
            // }

            // return $otherdeductions;

            // =====================================================================================================================

            $header = DB::table('hr_payrollv2history')
                ->select('hr_payrollv2history.*', 'teacher.title', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix', 'teacher.tid', 'usertype.utype')
                ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
                ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                ->where('payrollid', $payrollid)
                ->where('employeeid', $employeeid)
                ->where('hr_payrollv2history.deleted', '0')
                ->first();

            $payrollinfo = DB::table('hr_payrollv2')
                ->where('id', $payrollid)
                ->where('deleted', '0')
                ->first();

            $balance = 0;
            $totaldeduction = 0;
            $particulars = DB::table('hr_payrollv2historydetail')
                // ->select('payrollid','employeeid','headerid','description','totalamount','amountpaid','paymenttype','particulartype','remarks','particularid','type','leavedateid','deductionid','deductiondesc','allowanceid','allowancedesc','paymentoption','employeeleaveid','days','employeeovertimeid','overtimehours','amount','dateissued')
                ->where('payrollid', $payrollid)
                ->where('employeeid', $employeeid)
                ->where('deleted', '0')
                ->groupBy('description', 'amount')
                ->get();

            // return $particulars;

            foreach ($particulars as $particular) {
                $totalAmount = floatval($particular->totalamount);
                $amountPaid = floatval($particular->amountpaid);
                $balance = $totalAmount - $amountPaid;

                // Add the "balance" key to each item in the $particulars array
                $particular->balance = number_format($balance, 2); // Format the balance with 2 decimal places if needed
            }

            // return $particulars;

            if (count($particulars) > 0) {
                foreach ($particulars as $particular) {
                    if ($particular->particulartype == 6) {
                        DB::table('employee_overtime')
                            ->where('id', $particular->particularid)
                            ->update([
                                'payrolldone' => 1
                            ]);
                    }
                    $totaldeduction += $particular->amountpaid;


                }
            }
            // return $particulars;

            // return $totaldeduction;
            if ($header->middlename != null) {
                $header->middlename = $header->middlename[0] . '.';
            }

            $holidays = DB::table('hr_payrollv2historydetail')
                ->where('payrollid', $payrollid)
                ->where('employeeid', $employeeid)
                ->where('particulartype', 8)
                ->where('deleted', '0')
                ->get();
            // return $holidays;


            $addedparticulars = DB::table('hr_payrollv2addparticular')
                ->where('payrollid', $payrollid)
                ->where('employeeid', $employeeid)
                ->where('deleted', '0')
                ->groupBy('description', 'amount') // Group by 'description' and 'amount' to ensure uniqueness
                ->get();


            // foreach ($particulars as $addparticulars) {
            //     if ($addparticulars->type == 0) {
            //         foreach ($addedparticulars as $addedparticular) {
            //             if ($addparticulars->headerid == $addedparticular->headerid) {
            //                 $addedparticular->custom = 1;
            //             }
            //         }
            //     }
            // }

            // return collect($addedparticular);

            $checkifexistsreleased = DB::table('hr_payrollv2history')
                ->where('id', $header->id)
                ->first();

            if ($checkifexistsreleased->released == 0) {
                DB::table('hr_payrollv2history')
                    ->where('id', $header->id)
                    ->update([
                        'released' => 1,
                        'releasedby' => auth()->user()->id,
                        'releaseddatetime' => date('Y-m-d H:i:s')
                    ]);
            }


            $leavedetails = DB::table('hr_payrollv2historydetail')
                ->select('hr_payrollv2historydetail.amountpaid as amount', 'hr_payrollv2historydetail.description as leave_type', 'hr_payrollv2historydetail.leavedateid as ldateid', 'hr_payrollv2historydetail.employeeleaveid', 'hr_leaveempdetails.ldate', 'hr_leaveempdetails.dayshift')
                ->leftJoin('hr_leaveemployees', 'hr_payrollv2historydetail.employeeleaveid', 'hr_leaveemployees.id')
                ->leftJoin('hr_leaveempdetails', 'hr_payrollv2historydetail.leavedateid', '=', 'hr_leaveempdetails.id')
                ->where('hr_payrollv2historydetail.headerid', $header->payrollid)
                // ->where('particulartype', 6)
                ->where('hr_payrollv2historydetail.employeeid', $header->employeeid)
                ->where('hr_payrollv2historydetail.deleted', 0)
                ->where('hr_payrollv2historydetail.leavedateid', '>', 0)
                ->get();

            // return $particulars;
            // foreach ($otherdeductions as $otherdeduction) {
            //     $matchingParticular = collect($particulars)->where('description', $otherdeduction->description)->first();
            //     if ($matchingParticular) {
            //         $otherdeduction->totalamountpaid = $matchingParticular->totalpaidamount;
            //     }
            // }


            // return $otherdeductions;
            $payrolldetail = $checkifexistsreleased;
            $payrolldetail->totaldeduction += $totaldeduction;
            $payrolldetail->netsalary = ($payrolldetail->totalearning - $payrolldetail->totaldeduction) ?? 0.00;
            // return collect($payrollinfo);
            // return $totaldeduction;
            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi') {
                $pdf = PDF::loadview('hr/payroll/v3/pdf_single', compact('header', 'particulars', 'payrollinfo', 'addedparticulars', 'leavedetails'));
            } else {
                // return $empotherdeductions;
                // return collect($header);
                // return $particulars;
                // $pdf = PDF::loadview('hr/payroll/v3/sma_payslip',compact('schoolinfo','header','particulars','payrollinfo','addedparticulars','payrolldetail','leavedetails','otherdeductions', 'holidays','employeeinfo','preparedby'));
                $pdf = PDF::loadview('hr/payroll/v3/pdf_singlev4', compact('schoolinfo', 'header', 'particulars', 'payrollinfo', 'addedparticulars', 'payrolldetail', 'leavedetails', 'otherdeductions', 'empotherdeductions', 'holidays', 'employeeinfo', 'preparedby'));
            }
            // $pdf = PDF::loadview('hr/payroll/v3/pdf_single',compact('header','particulars','payrollinfo','addedparticulars'));
            // return $particulars;

            return $pdf->stream('Payslip - ' . $header->lastname . '_' . $header->firstname . '_' . date('Y') . '.pdf');
        } elseif ($request->get('exporttype') == 2) {
            $payrolldates = DB::table('hr_payrollv2')
                ->where('id', $payrollid)
                ->where('deleted', '0')
                ->get();

            $employees = DB::table('hr_payrollv2history')
                ->select('teacher.*', 'hr_payrollv2history.id as headerid', 'employee_basicsalaryinfo.*')
                ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
                ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
                ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                ->where('hr_payrollv2history.payrollid', $payrollid)
                ->where('hr_payrollv2history.deleted', '0')
                ->where('released', '1')
                ->orderBy('lastname', 'ASC')
                ->get();

            if (count($employees) > 0) {
                foreach ($employees as $employee) {
                    $employee->header = DB::table('hr_payrollv2history')
                        ->where('id', $employee->headerid)
                        ->first();

                    $employee->netsalary = $employee->header->netsalary;
                    $employee->particulars = DB::table('hr_payrollv2historydetail')
                        ->where('headerid', $employee->headerid)
                        ->where('deleted', '0')
                        ->groupBy('description', 'headerid') // Add this line to group by description
                        ->get();

                    $employee->totalstandarddeductions = collect($employee->particulars)->whereIn('particulartype', [1, 2])->sum('amountpaid');
                    $employee->totalstandardallowances = collect($employee->particulars)->whereIn('particulartype', [3, 4])->sum('amountpaid');


                    $employee->addedparticulars = DB::table('hr_payrollv2addparticular')
                        ->where('headerid', $employee->headerid)
                        ->where('deleted', '0')
                        ->get();

                    $employee->totaladdeddeductions = collect($employee->addedparticulars)->where('type', 2)->sum('amount');
                    $employee->totaladdedallowances = collect($employee->addedparticulars)->where('type', 1)->sum('amount');

                }
            }

            $employeeids = $employees->pluck('employeeid'); // orig
            // $employeeids = [127]; // orig

            $payrollStartDate = Carbon::parse($payrolldates[0]->datefrom);
            $payrollEndDate = Carbon::parse($payrolldates[0]->dateto);
            $payrolldates = $payrollStartDate->format('M d') . ' - ' . $payrollEndDate->format('M d Y');
            // $employeeids = explode(',',$employeeids);
            $employeeidsrrays = [];

            foreach ($employeeids as $id) {
                $employeeidsrrays[] = json_decode(json_encode([
                    'employeeid' => $id,
                ]));
            }

            $generateddatahistorys = DB::table('hr_payrollv2history') // Replace with your actual table name
                ->whereIn('hr_payrollv2history.employeeid', $employeeids)
                ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
                ->where('hr_payrollv2history.payrollid', $payrollid)
                ->where('hr_payrollv2history.deleted', 0)
                ->where('hr_payrollv2history.configured', 1)
                ->get();

            $generateddataparticularsearnings = DB::table('hr_payrollv2addparticular') // Replace with your actual table name
                ->whereIn('employeeid', $employeeids)
                ->where('payrollid', $payrollid)
                ->where('type', 1)
                ->where('additionalid', '!>', 2)
                ->where('deleted', 0)
                ->get();

            // return $generateddataparticularsearnings;
            $generateddataparticularsdeductions = DB::table('hr_payrollv2addparticular') // Replace with your actual table name
                ->whereIn('employeeid', $employeeids)
                ->where('payrollid', $payrollid)
                ->where('type', 2)
                ->where('additionalid', '!>', 2)
                ->where('deleted', 0)
                ->get();

            $mergedaddedparticulars = $generateddataparticularsearnings->merge($generateddataparticularsdeductions);
            foreach ($mergedaddedparticulars as $key => $mergedaddedparticular) {
                $mergedaddedparticular->amountpaid = $mergedaddedparticular->amount;
            }

            $otherdeductions = DB::table('deduction_others')
                ->where('deleted', 0)
                ->where('deductionunder', null)
                ->get();


            $underdeductions = DB::table('deduction_others')
                ->where('deleted', 0)
                ->where('deductionunder', '!=', null)
                ->get();

            $standarallowances = DB::table('allowance_standard')
                ->where('deleted', 0)
                ->get();

            $addedearnings = $generateddataparticularsearnings->pluck('description')->unique()->toArray();
            $addedeductions = $generateddataparticularsdeductions->pluck('description')->unique()->toArray();
            $otherdeductionslist = $otherdeductions->pluck('description')->unique()->toArray();
            $standarallowanceslist = $standarallowances->pluck('description')->unique()->toArray();


            $generateddatadetails = DB::table('hr_payrollv2historydetail') // Replace with your actual table name
                ->whereIn('employeeid', $employeeids)
                ->where('payrollid', $payrollid)
                ->where('deleted', 0)
                ->where('paidstatus', 1)
                ->get();

            foreach ($generateddatadetails as $key => $generateddatadetail) {
                if ($generateddatadetail->description == "SSS") {
                    $generateddatadetail->description = "SSS Contrn";
                } else if ($generateddatadetail->description == "PAG-IBIG") {
                    $generateddatadetail->description = "PAG-IBIG Contrn";
                } else if ($generateddatadetail->description == "PHILHEALTH") {
                    $generateddatadetail->description = "PHIL Contrn";
                }
            }

            $totalamountholiday = 0;

            if (count($generateddatadetails) > 0) {

                foreach ($generateddatadetails as $detail) {
                    if ($detail->particulartype == 8) {
                        // Add the amountpaid if particulartype is 8
                        $totalamountholiday += floatval($detail->amountpaid);
                    }
                }

            }


            // return $generateddatahistorys;
            // Assuming you have an array of header values based on your data

            $descriptionsearnings = array_unique($addedearnings);

            $descriptionsdeductions = array_unique($addedeductions);
            $descriptionsotherdeductions = array_unique($otherdeductionslist);
            $descriptionsstandardearnings = array_unique($standarallowanceslist);
            $headers = ['S.No.', 'NAME OF EMPLOYEE', 'Basic Pay', 'GROSS', 'SSS Contrn', 'PHIL Contrn', 'PAG-IBIG Contrn', 'Undertime', 'Absent/Late', 'Total Deductions', 'Net Pay'];


            // Find the positions where you want to insert the additional descriptions
            $insertaddearnings = array_search('Basic Pay', $headers) + 1;
            array_splice($headers, $insertaddearnings, 0, $descriptionsearnings);

            $insertstandardearnings = array_search('Basic Pay', $headers) + 1;
            array_splice($headers, $insertstandardearnings, 0, $descriptionsstandardearnings);

            $insertotherdeductions = array_search('PAG-IBIG Contrn', $headers) + 1;
            array_splice($headers, $insertotherdeductions, 0, $descriptionsotherdeductions);

            $insertadditionaldeductions = array_search('Absent/Late', $headers) + 1;
            array_splice($headers, $insertadditionaldeductions, 0, $descriptionsdeductions);

            // Insert the additional descriptions at the specified positions

            $filterheader = array_unique($headers);
            // Separate the first 20 descriptions
            $firstTwentyDescriptions = array_slice($headers, 0, 20); // Index 2 to 21 (exclusive)
            $firstTwentyDescriptionsdata = array_slice($headers, 2, 18); // Index 2 to 21 (exclusive)
            $remainingDescriptions = array_slice($headers, 20);

            $lastColumnIndex = null; // Define $lastColumnIndex before the loop
            $netsalary = 0;


            $mergdatas = $generateddatadetails->merge($mergedaddedparticulars);
            $finaldetails = $mergdatas;
            // return $finaldetails;

            // foreach ($employeeidsrrays as $employeeidsrray) {
            //     $mergedaddedparticularss = collect($mergedaddedparticulars)->where('employeeid', $employeeidsrray->employeeid)->values();
            //     $generateddatadetails = DB::table('hr_payrollv2historydetail') // Replace with your actual table name
            //         ->select('hr_payrollv2historydetail.*', 'deduction_others.deductionunder','deduction_others.deductionunderdesc')
            //         ->leftJoin('deduction_others', 'hr_payrollv2historydetail.deductionid', '=', 'deduction_others.id')
            //         ->where('hr_payrollv2historydetail.employeeid', $employeeidsrray->employeeid)
            //         ->where('hr_payrollv2historydetail.payrollid', $payrollid)
            //         // ->where('particulartype', 1)
            //         ->where('hr_payrollv2historydetail.deleted', 0)
            //         ->get();

            //     $generateddatahistorys = DB::table('hr_payrollv2history') // Replace with your actual table name
            //         ->where('hr_payrollv2history.employeeid', $employeeidsrray->employeeid)
            //         ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
            //         ->where('hr_payrollv2history.payrollid', $payrollid)
            //         ->where('hr_payrollv2history.deleted', 0)
            //         ->where('hr_payrollv2history.configured', 1)
            //         ->get();


            //     foreach($generateddatahistorys as $generateddatahistory)
            //     {

            //         $mapping = [];
            //         $mappingnotwithunder = [];

            //         foreach ($generateddatadetails as $item) {
            //             $deductionunderdesc = $item->deductionunderdesc;
            //             $deductionunder = $item->deductionunder;
            //             $item->combinedamounttotal = $item->amountpaid;

            //             // Check if deductionunderdesc already exists in the mapping and deductionunder is not null
            //             if (!array_key_exists($deductionunderdesc, $mapping) && $deductionunder !== null) {
            //                 $mapping[$deductionunderdesc] = json_decode(json_encode([
            //                     "deductionunderid" => $deductionunder,
            //                     "deductionunder" => $deductionunder,
            //                     "deductionunderdesc" => $deductionunderdesc,
            //                     "description" => $deductionunderdesc,
            //                     "particulartype" => 2,
            //                 ]));
            //             } else {
            //                 // if ($item->particulartype == 2) {
            //                     $mappingnotwithunder[] = json_decode(json_encode([
            //                         "deductionunderid" => $item->id,
            //                         "deductionunder" => $item->id,
            //                         "deductionunderdesc" => $item->description,
            //                         "description" => $item->description,
            //                         "particulartype" => $item->particulartype,
            //                     ]));
            //                 // }

            //             }
            //         }


            //         // Convert the associative array to indexed array of objects
            //         $mapping = array_values($mapping);
            //         $mappingnotwithunder = array_values($mappingnotwithunder);
            //         $mapping = array_merge($mapping, $mappingnotwithunder);

            //         // return $mapping;
            //         // return $generateddatadetails;
            //         foreach ($mapping as $item) {
            //             $item->combinedamount = null; // Set default value to null

            //             foreach ($generateddatadetails as $generateddatadetail) {
            //                 if ($item->deductionunderid == $generateddatadetail->deductionunder || $item->deductionunderid == $generateddatadetail->deductionid) {
            //                     $item->combinedamount += $generateddatadetail->amountpaid;
            //                 }
            //             }
            //         }

            //         if (count($mapping) > 0) {
            //             foreach ($mapping as $insert) {
            //                 foreach ($generateddatadetails as $combine) {
            //                     if ($insert->deductionunderid == $combine->deductionid) {

            //                         $combine->combinedamounttotal = $insert->combinedamount;
            //                     } else {
            //                         $combine->combinedamount = $combine->amountpaid;

            //                     }
            //                 }
            //             }
            //         }
            //         foreach ($mergedaddedparticularss as $mergedaddedparticular) {
            //             if ($mergedaddedparticular->amount) {
            //                 $mergedaddedparticular->amountpaid = $mergedaddedparticular->amount;
            //                 $mergedaddedparticular->combinedamount = $mergedaddedparticular->amount;
            //                 $mergedaddedparticular->combinedamounttotal = $mergedaddedparticular->amount;

            //             }
            //         }

            //         $mergdata = $generateddatadetails->merge($mergedaddedparticularss);
            //         $finaldetails = $mergdata;
            //         $netsalary += $generateddatahistory->netsalary;
            //     }


            // }


            // $particulars = collect($employees)->pluck('particulars')->toArray();
            // return $particulars;
            // return $finaldetails;

            $standarddeductions = DB::table('deduction_standard')
                ->where('deleted', '0')
                ->get();

            return view('hr/payroll/v3/pdf_summarydcc2')
                ->with('firstTwentyDescriptions', $firstTwentyDescriptions)
                ->with('remainingDescriptions', $remainingDescriptions)
                ->with('finaldetails', $finaldetails)
                ->with('generateddatahistorys', $generateddatahistorys)
                ->with('schoolinfo', $schoolinfo)
                ->with('payrolldates', $payrolldates)
                ->with('employees', $employees)
                ->with('standarddeductions', $standarddeductions);

            // $pdf = PDF::loadview('hr/payroll/v3/pdf_summarydcc2',compact('firstTwentyDescriptions','remainingDescriptions','finaldetails','schoolinfo','payrolldates','employees','standarddeductions'))->setPaper('8.5x14','landscape');
            // return $pdf->stream('Payroll Summary - '.date('Y').'.pdf');
        }
    }

    public static function exportpayslipbydepartment(Request $request)
    {
        $payrollid = $request->get('payrollid');
        $depid = $request->get('depid');

        // return $payrollid;
        $schoolinfo = DB::table('schoolinfo')
            ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
            ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
            ->select('schoolinfo.*', 'refregion.regDesc', 'refcitymun.citymunDesc')
            ->get();

        $preparedby = DB::table('teacher')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.userid', auth()->user()->id)
            ->where('teacher.deleted', '0')
            ->first();

        $payrollinfo = DB::table('hr_payrollv2')
            ->where('id', $payrollid)
            ->where('deleted', '0')
            ->first();

        // return collect($payrollinfo);

        if ($depid == null) {
            $histories = DB::table('hr_payrollv2history')
                ->select(
                    'hr_payrollv2history.id',
                    'hr_payrollv2history.employeeid',
                    'hr_payrollv2history.presentdays',
                    'hr_payrollv2history.absentdays',
                    'hr_payrollv2history.basicsalaryamount',
                    'hr_payrollv2history.basicsalarytype',
                    'hr_payrollv2history.daysabsentamount',
                    'hr_payrollv2history.lateminutes',
                    'hr_payrollv2history.lateamount',
                    'hr_payrollv2history.undertimeminutes',
                    'hr_payrollv2history.undertimeamount',
                    'hr_payrollv2history.totalhoursworked',
                    'hr_payrollv2history.amountperday',
                    'hr_payrollv2history.netsalary',
                    'hr_payrollv2history.totalearning',
                    'hr_payrollv2history.totaldeduction',
                    'hr_payrollv2history.monthlysalary',
                    'hr_payrollv2history.releaseddatetime',
                    'hr_payrollv2history.clregularloadamount',
                    'hr_payrollv2history.cloverloadloadamount',
                    'hr_payrollv2history.clparttimeloadamount',
                    'hr_payrollv2history.regulartardyamount',
                    'hr_payrollv2history.overloadtardyamount',
                    'hr_payrollv2history.emergencyloadtardyamount',
                    'teacher.lastname',
                    'teacher.middlename',
                    'teacher.firstname',
                    'teacher.suffix',
                    'teacher.title',
                    'teacher.tid',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.departmentid',
                    'usertype.utype',
                    'teacher.picurl'
                )
                ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
                ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                // ->leftJoin('usertype','teacher.id','=','usertype.id')
                ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                ->where('hr_payrollv2history.payrollid', $payrollid)
                ->where('hr_payrollv2history.deleted', '0')
                ->where('hr_payrollv2history.released', '1')
                ->orderBy('lastname', 'asc')
                ->get();
        } else {
            $histories = DB::table('hr_payrollv2history')
                ->select(
                    'hr_payrollv2history.id',
                    'hr_payrollv2history.employeeid',
                    'hr_payrollv2history.presentdays',
                    'hr_payrollv2history.absentdays',
                    'hr_payrollv2history.basicsalaryamount',
                    'hr_payrollv2history.basicsalarytype',
                    'hr_payrollv2history.daysabsentamount',
                    'hr_payrollv2history.lateminutes',
                    'hr_payrollv2history.lateamount',
                    'hr_payrollv2history.undertimeminutes',
                    'hr_payrollv2history.undertimeamount',
                    'hr_payrollv2history.totalhoursworked',
                    'hr_payrollv2history.amountperday',
                    'hr_payrollv2history.netsalary',
                    'hr_payrollv2history.totalearning',
                    'hr_payrollv2history.totaldeduction',
                    'hr_payrollv2history.monthlysalary',
                    'hr_payrollv2history.releaseddatetime',
                    'hr_payrollv2history.clregularloadamount',
                    'hr_payrollv2history.cloverloadloadamount',
                    'hr_payrollv2history.clparttimeloadamount',
                    'hr_payrollv2history.regulartardyamount',
                    'hr_payrollv2history.overloadtardyamount',
                    'hr_payrollv2history.emergencyloadtardyamount',
                    'teacher.lastname',
                    'teacher.middlename',
                    'teacher.firstname',
                    'teacher.suffix',
                    'teacher.title',
                    'teacher.tid',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.departmentid',
                    'usertype.utype',
                    'teacher.picurl'
                )
                ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
                ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                // ->leftJoin('usertype','teacher.id','=','usertype.id')
                ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                ->where('hr_payrollv2history.payrollid', $payrollid)
                ->where('employee_personalinfo.departmentid', $depid)
                ->where('hr_payrollv2history.deleted', '0')
                ->where('hr_payrollv2history.released', '1')
                ->orderBy('lastname', 'asc')
                ->get();


            // return $histories;
        }

        $empids = $histories->pluck('employeeid');
        $payrollid = $request->get('payrollid');
        if ($request->get('exporttype') == 2) {
            // $employeeid = $request->get('employeeid');


            // $payrollv2history = DB::table('hr_payrollv2history')
            //     ->leftJoin('hr_payrollv2', 'hr_payrollv2history.payrollid', '=', 'hr_payrollv2.id')
            //     ->where('hr_payrollv2history.released', 1)
            //     ->where('hr_payrollv2history.employeeid', $employeeid)
            //     ->get();


            $otherdeductions = Db::table('employee_deductionother')
                ->whereIn('employee_deductionother.employeeid', $empids)
                ->where('employee_deductionother.paid', '0')
                ->where('employee_deductionother.paidna', null)
                ->where('employee_deductionother.status', '1')
                ->where('employee_deductionother.deleted', '0')
                ->where('deductionotherid', '!=', null)
                // ->where('employee_deductionother.employeeid', 239)
                // ->where('employee_deductionother.id', 328)
                ->get();

            // return $otherdeductions;
            foreach ($otherdeductions as $otherdeduction) {
                $interest = 0;
                $otherdeduction->totalotherdeductionpaid = $otherdeduction->paidamount;
                $otherdeduction->totalamountpaid = $otherdeduction->paidamount;
                $interest = $otherdeduction->od_interest / 100;
                $interest = $interest * $otherdeduction->fullamount;
                $interest = $interest + $otherdeduction->fullamount;

                $otherdeduction->interestamount = $interest;

            }
            // return $empids;
            // return $otherdeductions;

            $payrolldetails = DB::table('hr_payrollv2historydetail')
                // ->leftJoin('hr_payrollv2history', 'hr_payrollv2historydetail.payrollid', '=', 'hr_payrollv2history.payrollid')
                ->where('hr_payrollv2historydetail.particulartype', 2)
                // ->where('hr_payrollv2historydetail.employeeid', 239)
                ->whereIn('hr_payrollv2historydetail.employeeid', $empids)
                // ->whereIn('hr_payrollv2history.employeeid', $empids)
                ->where('hr_payrollv2historydetail.deleted', 0)
                ->where('hr_payrollv2historydetail.payrollid', '<=', $payrollid)
                ->where('hr_payrollv2historydetail.paidstatus', 1)
                // ->where('hr_payrollv2history.released', 1)
                ->get();

            // return $payrolldetails;
            foreach ($otherdeductions as $otherdeduction) {
                foreach ($payrolldetails as $payrolldetail) {
                    if ($payrolldetail->particularid == $otherdeduction->id && $payrolldetail->employeeid == $otherdeduction->employeeid) {
                        $otherdeduction->totalotherdeductionpaid += $payrolldetail->amountpaid;
                        $otherdeduction->totalamountpaid += $payrolldetail->amountpaid;
                    }
                }

                $otherdeduction->totalotherdeductionpaid = round($otherdeduction->totalotherdeductionpaid, 2);
                $otherdeduction->totalamountpaid = round($otherdeduction->totalamountpaid, 2);
            }


            // return $otherdeductions;
            // $header = DB::table('hr_payrollv2history')
            //     ->select('hr_payrollv2history.*','teacher.title','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix','teacher.tid','usertype.utype')
            //     ->leftJoin('teacher','hr_payrollv2history.employeeid','=','teacher.id')
            //     ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            //     ->where('payrollid',$payrollid)
            //     ->where('employeeid',$employeeid)
            //     ->where('hr_payrollv2history.deleted','0')
            //     ->first();

            $balance = 0;
            $totaldeduction = 0;
            $particulars = DB::table('hr_payrollv2historydetail')
                ->where('payrollid', $payrollid)
                ->whereIn('employeeid', $empids)
                ->where('deleted', '0')
                ->distinct()
                ->get();

            foreach ($particulars as $particular) {
                $totalAmount = floatval($particular->totalamount);
                $amountPaid = floatval($particular->amountpaid);
                $balance = $totalAmount - $amountPaid;
                $particular->balance = number_format($balance, 2);
            }


            if (count($particulars) > 0) {
                foreach ($particulars as $particular) {
                    if ($particular->particulartype == 6) {
                        DB::table('employee_overtime')
                            ->where('id', $particular->particularid)
                            ->update([
                                'payrolldone' => 1
                            ]);
                    }
                    $totaldeduction += $particular->amountpaid;


                }
            }

            // return $particulars;


            // if($header->middlename != null)
            // {
            //     $header->middlename = $header->middlename[0].'.';
            // }

            $holidays = DB::table('hr_payrollv2historydetail')
                ->where('payrollid', $payrollid)
                ->whereIn('employeeid', $empids)
                ->where('particulartype', 8)
                ->where('deleted', '0')
                ->get();
            // return $holidays;
            $addedparticulars = DB::table('hr_payrollv2addparticular')
                ->where('payrollid', $payrollid)
                ->whereIn('employeeid', $empids)
                ->where('deleted', '0')

                ->get();
            // return $addedparticulars;
            // $checkifexistsreleased = DB::table('hr_payrollv2history')
            // ->where('id', $header->id)
            // ->first();

            // if($checkifexistsreleased->released == 0)
            // {
            //     DB::table('hr_payrollv2history')
            //         ->where('id', $header->id)
            //         ->update([
            //             'released'          => 1,
            //             'releasedby'        => auth()->user()->id,
            //             'releaseddatetime'  => date('Y-m-d H:i:s')
            //         ]);
            // }


            $leavedetails = DB::table('hr_payrollv2historydetail')
                ->select('hr_payrollv2historydetail.amountpaid as amount', 'hr_payrollv2historydetail.description as leave_type', 'hr_payrollv2historydetail.leavedateid as ldateid', 'hr_payrollv2historydetail.employeeleaveid', 'hr_leaveempdetails.ldate', 'hr_leaveempdetails.dayshift')
                ->leftJoin('hr_leaveemployees', 'hr_payrollv2historydetail.employeeleaveid', 'hr_leaveemployees.id')
                ->leftJoin('hr_leaveempdetails', 'hr_payrollv2historydetail.leavedateid', '=', 'hr_leaveempdetails.id')
                ->where('hr_payrollv2historydetail.headerid', $payrollid)
                ->whereIn('hr_payrollv2historydetail.employeeid', $empids)
                ->where('hr_payrollv2historydetail.deleted', 0)
                ->where('hr_payrollv2historydetail.leavedateid', '>', 0)
                ->get();

            // return $leavedetails;
            // foreach ($otherdeductions as $otherdeduction) {
            //     $matchingParticular = collect($particulars)->firstWhere('description', $otherdeduction->description);

            //     if ($matchingParticular) {
            //         $otherdeduction->totalamountpaid = $matchingParticular->totalpaidamount;
            //     } else {
            //         $otherdeduction->totalamountpaid = 0;

            //     }
            // }

            // return $histories;
            // $payrolldetail = $checkifexistsreleased;
            // $payrolldetail->totaldeduction+=$totaldeduction;
            // $payrolldetail->netsalary = ($payrolldetail->totalearning - $payrolldetail->totaldeduction) ?? 0.00;

            // return $otherdeductions;
            $pdf = PDF::loadview('hr/payroll/v3/pdf_bulkpayslip', compact('schoolinfo', 'histories', 'payrollinfo', 'preparedby', 'otherdeductions', 'particulars', 'addedparticulars', 'holidays', 'leavedetails'));

            return $pdf->stream('Payslip - ' . $payrollinfo->datefrom . '_' . $payrollinfo->dateto . '_' . date('Y') . '.pdf');
        }

    }


    public function payrollhistory(Request $request)
    {
        if (!$request->has('action')) {
            $payrollperiods = DB::table('hr_payrollv2')
                ->where('deleted', '0')
                ->get();


            return view('hr.payroll.v3.summary_index')
                ->with('payrollperiods', $payrollperiods);
        } else {
            if ($request->get('action') == 'gethistory') {
                $histories = DB::table('hr_payrollv2history')
                    ->select(
                        'hr_payrollv2history.id',
                        'hr_payrollv2history.employeeid',
                        'hr_payrollv2history.presentdays',
                        'hr_payrollv2history.absentdays',
                        'hr_payrollv2history.basicsalaryamount',
                        'hr_payrollv2history.basicsalarytype',
                        'hr_payrollv2history.daysabsentamount',
                        'hr_payrollv2history.lateamount',
                        'hr_payrollv2history.undertimeminutes',
                        'hr_payrollv2history.totalhoursworked',
                        'hr_payrollv2history.amountperday',
                        'hr_payrollv2history.netsalary',
                        'hr_payrollv2history.totalearning',
                        'hr_payrollv2history.totaldeduction',
                        'hr_payrollv2history.monthlysalary',
                        'hr_payrollv2history.releaseddatetime',
                        'teacher.lastname',
                        'teacher.middlename',
                        'teacher.firstname',
                        'teacher.suffix',
                        'teacher.title',
                        'teacher.tid',
                        'employee_personalinfo.gender',
                        'usertype.utype',
                        'teacher.picurl'
                    )
                    ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
                    ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                    ->leftJoin('usertype', 'teacher.id', '=', 'usertype.id')
                    ->where('hr_payrollv2history.payrollid', $request->get('payrollid'))
                    ->where('hr_payrollv2history.deleted', '0')
                    ->where('hr_payrollv2history.released', '1')
                    ->orderBy('lastname', 'asc')
                    ->get();


                return view('hr.payroll.v3.summary_results')
                    ->with('histories', $histories);

            } elseif ($request->get('action') == 'getdetails') {
                $history = DB::table('hr_payrollv2history')
                    ->select(
                        'hr_payrollv2history.id',
                        'hr_payrollv2history.employeeid',
                        'hr_payrollv2history.presentdays',
                        'hr_payrollv2history.absentdays',
                        'hr_payrollv2history.basicsalaryamount',
                        'hr_payrollv2history.basicsalarytype',
                        'hr_payrollv2history.daysabsentamount',
                        'hr_payrollv2history.lateamount',
                        'hr_payrollv2history.undertimeminutes',
                        'hr_payrollv2history.undertimeamount',
                        'hr_payrollv2history.totalhoursworked',
                        'hr_payrollv2history.amountperday',
                        'hr_payrollv2history.netsalary',
                        'hr_payrollv2history.totalearning',
                        'hr_payrollv2history.totaldeduction',
                        'hr_payrollv2history.monthlysalary',
                        'hr_payrollv2history.releaseddatetime',
                        'hr_payrollv2history.clregularloadamount',
                        'hr_payrollv2history.cloverloadloadamount',
                        'hr_payrollv2history.clparttimeloadamount',
                        'teacher.lastname',
                        'teacher.middlename',
                        'teacher.firstname',
                        'teacher.suffix',
                        'teacher.title',
                        'teacher.tid',
                        'employee_personalinfo.gender',
                        'usertype.utype',
                        'teacher.picurl'
                    )
                    ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
                    ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                    ->leftJoin('usertype', 'teacher.id', '=', 'usertype.id')
                    ->where('hr_payrollv2history.id', $request->get('historyid'))
                    ->orderBy('lastname', 'asc')
                    ->first();

                // return collect($history);

                $particulars = DB::table('hr_payrollv2historydetail')
                    ->where('headerid', $request->get('historyid'))
                    ->where('deleted', '0')
                    ->get();

                $addedparticulars = DB::table('hr_payrollv2addparticular')
                    ->where('headerid', $request->get('historyid'))
                    ->where('deleted', '0')
                    ->get();

                return view('hr.payroll.v3.summary_details')
                    ->with('history', $history)
                    ->with('particulars', $particulars)
                    ->with('addedparticulars', $addedparticulars);
            }
        }
    }
    // Gian Additional
    public function loadnetsalary(Request $request)
    {
        $histories = DB::table('hr_payrollv2history')
            ->select(
                'hr_payrollv2history.id',
                'hr_payrollv2history.employeeid',
                'hr_payrollv2history.presentdays',
                'hr_payrollv2history.absentdays',
                'hr_payrollv2history.basicsalaryamount',
                'hr_payrollv2history.basicsalarytype',
                'hr_payrollv2history.daysabsentamount',
                'hr_payrollv2history.lateamount',
                'hr_payrollv2history.undertimeminutes',
                'hr_payrollv2history.totalhoursworked',
                'hr_payrollv2history.amountperday',
                'hr_payrollv2history.netsalary',
                'hr_payrollv2history.totalearning',
                'hr_payrollv2history.totaldeduction',
                'hr_payrollv2history.monthlysalary',
                'hr_payrollv2history.releaseddatetime',
                'teacher.lastname',
                'teacher.middlename',
                'teacher.firstname',
                'teacher.suffix',
                'teacher.title',
                'teacher.tid',
                'employee_personalinfo.gender',
                'usertype.utype',
                'teacher.picurl'
            )
            ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->leftJoin('usertype', 'teacher.id', '=', 'usertype.id')
            ->where('hr_payrollv2history.payrollid', $request->get('payrollid'))
            ->where('hr_payrollv2history.deleted', '0')
            ->where('hr_payrollv2history.released', '1')
            ->orderBy('lastname', 'asc')
            ->get();


        $sumNetsalary = 0; // Initialize the sum to zero

        // Loop through the array and accumulate the 'netsalary' values
        foreach ($histories as $history) {
            // Convert the 'netsalary' value to a float and add it to the sum
            $sumNetsalary += floatval($history->netsalary);
        }

        return $sumNetsalary;

    }

    // All Released Payroll in Employee
    public function emp_allreleasedpayrolldates(Request $request)
    {
        $formattedPayrollperiods = [];
        $employeeid = $request->get('employeeid');

        $payrollreleased = DB::table('hr_payrollv2history')
            ->select('payrollid')
            ->where('deleted', '0')
            ->where('released', '1')
            ->where('employeeid', $employeeid)
            ->get();

        if (count($payrollreleased) > 0) {

            $payrollreleasedIds = $payrollreleased->pluck('payrollid');

            $filteredPayrollperiods = DB::table('hr_payrollv2')
                ->leftJoin('hr_payrollv2history', 'hr_payrollv2.id', '=', 'hr_payrollv2history.payrollid')
                ->where('hr_payrollv2.deleted', '0')
                ->where('hr_payrollv2history.deleted', '0')
                ->where('hr_payrollv2history.deleted', '0')
                ->where('hr_payrollv2history.released', '1')
                ->where('hr_payrollv2history.employeeid', $employeeid)
                ->whereIn('hr_payrollv2.id', $payrollreleasedIds)
                ->get();



            foreach ($filteredPayrollperiods as $period) {
                $dateFrom = date("F j, Y", strtotime($period->datefrom));
                $dateTo = date("F j, Y", strtotime($period->dateto));
                $formattedDateRange = $dateFrom . " - " . $dateTo;
                $period->text = $formattedDateRange;
                $formattedPayrollperiods[] = $period;
            }

        }


        return $formattedPayrollperiods;
    }

    public function payrolldate_data(Request $request)
    {
        return view('hr.payroll.v3.payroll_details');
    }

    public function payrolldetails(Request $request)
    {
        // return 'msaysas';
        // return $request->all();
        $empid = $request->get('employeeid');

        // return $empid;
        $history = DB::table('hr_payrollv2history')
            ->select(
                'hr_payrollv2history.id as payrollid',
                'hr_payrollv2history.employeeid',
                'hr_payrollv2history.presentdays',
                'hr_payrollv2history.absentdays',
                'hr_payrollv2history.basicsalaryamount',
                'hr_payrollv2history.basicsalarytype',
                'hr_payrollv2history.daysabsentamount',
                'hr_payrollv2history.lateamount',
                'hr_payrollv2history.undertimeminutes',
                'hr_payrollv2history.undertimeamount',
                'hr_payrollv2history.totalhoursworked',
                'hr_payrollv2history.amountperday',
                'hr_payrollv2history.netsalary',
                'hr_payrollv2history.totalearning',
                'hr_payrollv2history.totaldeduction',
                'hr_payrollv2history.monthlysalary',
                'hr_payrollv2history.releaseddatetime',
                'teacher.lastname',
                'teacher.middlename',
                'teacher.firstname',
                'teacher.suffix',
                'teacher.title',
                'teacher.tid',
                'employee_personalinfo.gender',
                'usertype.utype',
                'teacher.picurl'
            )
            ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->leftJoin('usertype', 'teacher.id', '=', 'usertype.id')
            ->where('payrollid', $request->get('historyid'))
            ->where('teacher.id', $empid)
            ->orderBy('lastname', 'asc')
            ->first();

        // return collect($history);

        $particulars = DB::table('hr_payrollv2historydetail')
            ->where('payrollid', $request->get('historyid'))
            ->where('employeeid', $empid)
            ->where('deleted', '0')
            ->get();
        // return $particulars;
        $addedparticulars = DB::table('hr_payrollv2addparticular')
            ->where('payrollid', $request->get('historyid'))
            ->where('employeeid', $empid)
            ->where('deleted', '0')
            ->get();

        $data = [
            'history' => $history,
            'particulars' => $particulars,
            'addedparticulars' => $addedparticulars
        ];

        return $data;
    }

    public function batchreleaseemployeepayroll(Request $request)
    {
        $employeeids = $request->get('checkedEmpIds');

        if (count($employeeids) > 0) {
            DB::table('hr_payrollv2history') // Replace with your actual table name
                ->whereIn('employeeid', $employeeids)
                ->update(['released' => 1]);

            return array(
                (object) [
                    'status' => 1,
                    'message' => 'Released Successfully!',
                ]
            );
        }
    }

    public function batchgenerateemployeepayroll(Request $request)
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("PAYROLLSUMMARY/payrollgenerate.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $employeeids = $request->get('checkedEmpIds'); // orig

        $payrollid = $request->get('payrollid');

        $payrollperiod = DB::table('hr_payrollv2')
            ->where('id', $payrollid)
            ->first();


        $payrollStartDate = Carbon::parse($payrollperiod->datefrom);
        $payrollEndDate = Carbon::parse($payrollperiod->dateto);
        $payrolldates = $payrollStartDate->format('M d') . ' - ' . $payrollEndDate->format('M d Y');

        $employeeids = explode(',', $employeeids);

        $employeeidsrrays = [];

        foreach ($employeeids as $id) {
            $employeeidsrrays[] = json_decode(json_encode([
                'employeeid' => $id,
            ]));
        }

        $schoolinfo = DB::table('schoolinfo')
            ->first();

        $consultants = DB::table('teacher')
            ->select('teacher.id', 'teacher.lastname', 'teacher.firstname')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->whereIn('teacher.id', $employeeids)
            ->where('employee_personalinfo.departmentid', 6)
            ->get();

        $notdeletedparticularsdetails = DB::table('hr_payrollv2historydetail')
            ->whereIn('employeeid', $employeeids)
            ->where('payrollid', $payrollid)
            ->where('particulartype', null)
            ->where('deleted', 0)
            ->get();

        $deletedparticularsdetails = DB::table('hr_payrollv2historydetail')
            ->whereIn('employeeid', $employeeids)
            ->where('payrollid', $payrollid)
            ->where('particulartype', null)
            ->where('deleted', 1)
            ->get();

        // Filter deleted records that do not exist in the not-deleted list
        $notInNotDeleted = $deletedparticularsdetails->filter(function ($deleted) use ($notdeletedparticularsdetails) {
            return !$notdeletedparticularsdetails->contains(function ($notDeleted) use ($deleted) {
                return $notDeleted->description == $deleted->description &&
                    $notDeleted->totalamount == $deleted->totalamount; // Compare totalamount instead of amount
            });
        });

        // Return the filtered result
        // return $deletedparticularsdetails;

        // Return the filtered result
        $deletedEntries = $notInNotDeleted->map(function ($item) {
            return [
                'description' => $item->description,
                'employeeid' => $item->employeeid,
                'totalamount' => $item->totalamount,
                'payrollid' => $item->payrollid
            ];
        })->toArray();

        $generateddataparticularsearnings = DB::table('hr_payrollv2addparticular') // Replace with your actual table name
            ->whereIn('employeeid', $employeeids)
            ->where('payrollid', $payrollid)
            ->where('type', 1)
            ->where('deleted', 0)
            ->get();

        $filteredGeneratedDataParticularsEarnings = $generateddataparticularsearnings->filter(function ($item) use ($deletedEntries) {
            foreach ($deletedEntries as $deletedItem) {
                if (
                    $item->description === $deletedItem['description'] &&
                    $item->employeeid === $deletedItem['employeeid'] &&
                    $item->amount === $deletedItem['totalamount'] &&
                    $item->payrollid === $deletedItem['payrollid']
                ) {
                    return false;
                }
            }
            return true;
        });

        $generateddataparticularsdeductions = DB::table('hr_payrollv2addparticular') // Replace with your actual table name
            ->whereIn('employeeid', $employeeids)
            ->where('payrollid', $payrollid)
            ->where('type', 2)
            ->where('deleted', 0)
            ->get();

        $filteredGeneratedDataParticularsDeductions = $generateddataparticularsdeductions->filter(function ($item) use ($deletedEntries) {
            foreach ($deletedEntries as $deletedItem) {
                if (
                    $item->description === $deletedItem['description'] &&
                    $item->employeeid === $deletedItem['employeeid'] &&
                    $item->payrollid === $deletedItem['payrollid']
                ) {
                    return false;
                }
            }
            return true;
        });

        // $mergedaddedparticulars = $generateddataparticularsearnings->merge($generateddataparticularsdeductions);
        $mergedaddedparticulars = $filteredGeneratedDataParticularsEarnings->merge($filteredGeneratedDataParticularsDeductions);


        $otherdeductions = DB::table('deduction_others')
            ->where('deleted', 0)
            ->where('deductionunder', null)
            ->get();

        $underdeductions = DB::table('deduction_others')
            ->where('deleted', 0)
            ->where('deductionunder', '!=', null)
            ->get();

        $standarallowances = DB::table('allowance_standard')
            ->where('deleted', 0)
            ->get();

        // $addedearnings = $generateddataparticularsearnings->pluck('description')->unique()->toArray();
        // $addedeductions = $generateddataparticularsdeductions->pluck('description')->unique()->toArray();
        $addedearnings = $filteredGeneratedDataParticularsEarnings->pluck('description')->unique()->toArray();
        $addedeductions = $filteredGeneratedDataParticularsDeductions->pluck('description')->unique()->toArray();
        $otherdeductionslist = $otherdeductions->pluck('description')->unique()->toArray();
        $standarallowanceslist = $standarallowances->pluck('description')->unique()->toArray();


        $generateddatadetails = DB::table('hr_payrollv2historydetail') // Replace with your actual table name
            ->whereIn('employeeid', $employeeids)
            ->where('payrollid', $payrollid)
            // ->where('paidstatus', 1)
            // ->where('employeeid', 133)
            ->where('deleted', 0)
            ->get();

        $totalamountholiday = 0;

        if (count($generateddatadetails) > 0) {

            foreach ($generateddatadetails as $detail) {
                if ($detail->particulartype == 8) {
                    // Add the amountpaid if particulartype is 8
                    $totalamountholiday += floatval($detail->amountpaid);
                }
            }

        }


        // return $generateddatahistorys;
        // Assuming you have an array of header values based on your data

        $descriptionsearnings = array_unique($addedearnings);

        $descriptionsdeductions = array_unique($addedeductions);
        $descriptionsotherdeductions = array_unique($otherdeductionslist);
        $descriptionsstandardearnings = array_unique($standarallowanceslist);
        $headers = ['S.No.', 'NAME OF EMPLOYEE', 'Basic Pay', 'GROSS', 'SSS Contrn', 'PHIL Contrn', 'PAG-IBIG Contrn', 'Undertime', 'Absent/Late', 'Regularload Tardy', 'Overload Tardy', 'Emergency Tardy', 'Total Deductions', 'Net Pay'];

        // return $generateddatahistorys;

        // Find the positions where you want to insert the additional descriptions
        $insertaddearnings = array_search('Basic Pay', $headers) + 1;
        array_splice($headers, $insertaddearnings, 0, $descriptionsearnings);

        $insertstandardearnings = array_search('Basic Pay', $headers) + 1;
        array_splice($headers, $insertstandardearnings, 0, $descriptionsstandardearnings);

        $insertotherdeductions = array_search('PAG-IBIG Contrn', $headers) + 1;
        array_splice($headers, $insertotherdeductions, 0, $descriptionsotherdeductions);

        $insertadditionaldeductions = array_search('Absent/Late', $headers) + 1;
        array_splice($headers, $insertadditionaldeductions, 0, $descriptionsdeductions);

        // Insert the additional descriptions at the specified positions
        // Initialize an array to store unique headers
        $uniqueHeaders = [];
        // Loop through each header
        // foreach ($headers as $header) {
        //     // Check if the lowercase version of the header already exists
        //     if (!in_array(strtolower($header), array_map('strtolower', $uniqueHeaders))) {
        //         // If not, add the original header (preserving case)
        //         $uniqueHeaders[] = $header;
        //     }
        // }
        // $filterheader = array_unique($uniqueHeaders);
        $filterheader = array_unique($headers);

        $lastColumnIndex = null; // Define $lastColumnIndex before the loop

        // Set headers dynamically in row 6
        foreach ($filterheader as $key => $header) {
            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1); // Adjust the index to start from column B
            $sheet->setCellValue($column . '6', $header);
            $lastColumnIndex = $key + 1; // Update $lastColumnIndex during each iteration
        }
        $lastColumnLetter6 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColumnIndex);
        // Define the range from A to AE
        $range6 = 'A' . 6 . ':' . $lastColumnLetter6 . 6;

        // Apply borders and make data bold for the entire range
        $sheet->getStyle($range6)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'bold' => true,
                'size' => 10,
                'name' => 'Arial',
            ],
        ]);

        $startcellno = 7;
        $sNoCounter = 1;
        $sheet->setCellValue('A1', $schoolinfo->schoolname);
        $sheet->setCellValue('A2', $schoolinfo->address);
        $sheet->setCellValue('A3', 'SALARY - Period covering ' . $payrolldates);

        $columnTotals = array_fill_keys($filterheader, 0);

        // return $columnTotals;
        $staticDataStartRow = 11;
        // return $generateddatahistorys;
        $netsalary = 0;
        $basicsalaryamounttotal = 0;

        foreach ($employeeidsrrays as $employeeidsrray) {
            $consultant = 0;
            foreach ($consultants as $consultantid) {
                if ($employeeidsrray->employeeid == $consultantid->id) {
                    $consultant = 1;
                }
            }

            $mergedaddedparticularss = collect($mergedaddedparticulars)->where('employeeid', $employeeidsrray->employeeid)->values();
            $generateddatadetails = DB::table('hr_payrollv2historydetail') // Replace with your actual table name
                ->select('hr_payrollv2historydetail.*', 'deduction_others.deductionunder', 'deduction_others.deductionunderdesc')
                ->leftJoin('deduction_others', 'hr_payrollv2historydetail.deductionid', '=', 'deduction_others.id')
                ->where('hr_payrollv2historydetail.employeeid', $employeeidsrray->employeeid)
                ->where('hr_payrollv2historydetail.payrollid', $payrollid)
                ->where('hr_payrollv2historydetail.paidstatus', 1)
                ->where('hr_payrollv2historydetail.deleted', 0)
                ->get();

            // return $generateddatadetails;
            // return $uniqueDescriptions;
            $generateddatahistorys = DB::table('hr_payrollv2history') // Replace with your actual table name
                ->where('hr_payrollv2history.employeeid', $employeeidsrray->employeeid)
                ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
                ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                ->where('hr_payrollv2history.payrollid', $payrollid)
                ->where('hr_payrollv2history.deleted', 0)
                ->where('hr_payrollv2history.configured', 1)
                // ->where('hr_payrollv2history.released', 1)
                ->get();

            // return $generateddatahistorys;
            foreach ($generateddatahistorys as $generateddatahistory) {
                // return collect($generateddatahistory);
                $mapping = [];
                $mappingnotwithunder = [];

                foreach ($generateddatadetails as $item) {
                    $deductionunderdesc = $item->deductionunderdesc;
                    $deductionunder = $item->deductionunder;
                    $item->combinedamounttotal = $item->amountpaid;

                    // Check if deductionunderdesc already exists in the mapping and deductionunder is not null
                    if (!array_key_exists($deductionunderdesc, $mapping) && $deductionunder !== null) {
                        $mapping[$deductionunderdesc] = json_decode(json_encode([
                            "deductionunderid" => $deductionunder,
                            "deductionunder" => $deductionunder,
                            "deductionunderdesc" => $deductionunderdesc,
                            "description" => $deductionunderdesc,
                            "particulartype" => 2,
                        ]));
                    } else {
                        // if ($item->particulartype == 2) {
                        $mappingnotwithunder[] = json_decode(json_encode([
                            "deductionunderid" => $item->id,
                            "deductionunder" => $item->id,
                            "deductionunderdesc" => $item->description,
                            "description" => $item->description,
                            "particulartype" => $item->particulartype,
                        ]));
                        // }

                    }
                }

                // Convert the associative array to indexed array of objects
                $mapping = array_values($mapping);
                $mappingnotwithunder = array_values($mappingnotwithunder);
                $mapping = array_merge($mapping, $mappingnotwithunder);

                foreach ($mapping as $item) {
                    $item->combinedamount = null; // Set default value to null

                    foreach ($generateddatadetails as $generateddatadetail) {
                        if ($item->deductionunderid == $generateddatadetail->deductionunder || $item->deductionunderid == $generateddatadetail->deductionid) {
                            $item->combinedamount += $generateddatadetail->amountpaid;
                        }
                    }
                }

                if (count($mapping) > 0) {
                    foreach ($mapping as $insert) {
                        foreach ($generateddatadetails as $combine) {
                            if ($insert->deductionunderid == $combine->deductionid) {

                                $combine->combinedamounttotal = $insert->combinedamount;
                            } else {
                                $combine->combinedamount = $combine->amountpaid;

                            }
                        }
                    }
                }
                foreach ($mergedaddedparticularss as $mergedaddedparticular) {
                    if ($mergedaddedparticular->amount) {
                        $mergedaddedparticular->amountpaid = $mergedaddedparticular->amount;
                        $mergedaddedparticular->combinedamount = $mergedaddedparticular->amount;
                        $mergedaddedparticular->combinedamounttotal = $mergedaddedparticular->amount;

                    }
                }


                $mergdata = $generateddatadetails->merge($mergedaddedparticularss);

                $finaldetails = $mergdata;

                // foreach ($finaldetails as $details) {
                //         // dd($details);
                //         if ($consultant == 0) {
                //             $combinedamountt = $details->combinedamounttotal;
                //         } else {
                //             if (!in_array($details->description, ['PAG-IBIG', 'PHILHEALTH', 'SSS'])) {
                //                 $combinedamountt = $details->combinedamounttotal;
                //             } else {
                //                 $combinedamountt = '';
                //             }
                //         }
                //         if ($details->description == 'PAG-IBIG') {
                //             $description = 'PAG-IBIG Contrn';
                //         } else if ($details->description == 'PHILHEALTH') {
                //             $description = 'PHIL Contrn';
                //         } else if ($details->description == 'SSS') {
                //             $description = 'SSS Contrn';
                //         } else {
                //             $description = $details->description;
                //         }
                //         // if ($details->description == 'LIBRENG KWARTA') {
                //         //     $columnIndex = array_search($description, $filterheader);

                //         //     return $columnIndex;
                //         // }       

                //             // return $columnTotals[$header];
                //         $columnIndex = array_search($description, $filterheader);

                //         if ($columnIndex !== false) {
                //             $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);

                //             // Check if the property 'combinedamount' exists before accessing it
                //             $combinedAmount = $combinedamountt;
                //             $sheet->setCellValue($column . $startcellno, $combinedAmount);
                //         }

                // }

                foreach ($finaldetails as $details) {
                    if ($consultant == 0) {
                        $combinedamountt = $details->combinedamounttotal;
                    } else {
                        if (!in_array($details->description, ['PAG-IBIG', 'PHILHEALTH', 'SSS'])) {
                            $combinedamountt = $details->combinedamounttotal;
                        } else {
                            $combinedamountt = '';
                        }
                    }

                    // Determine the description based on the conditions
                    if ($details->description == 'PAG-IBIG') {
                        $description = 'PAG-IBIG Contrn';
                    } else if ($details->description == 'PHILHEALTH') {
                        $description = 'PHIL Contrn';
                    } else if ($details->description == 'SSS') {
                        $description = 'SSS Contrn';
                    } else {
                        $description = $details->description;
                    }

                    // Search for the exact description in filterheader
                    $columnIndex = null;
                    foreach ($filterheader as $index => $header) {
                        if ($header === $description) { // Exact match
                            $columnIndex = $index;
                            break; // Stop searching once you find a match
                        }
                    }

                    if ($columnIndex !== null) { // Check if a match was found
                        $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);

                        // Assign value to the correct header
                        $sheet->setCellValue($column . $startcellno, $combinedamountt);
                    }
                }


                $sheet->setCellValue('A' . $startcellno, $sNoCounter)->getStyle('A' . $startcellno)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                // $sheet->setCellValue('A'.$startcellno, $key + 1)->getStyle('A'.$startcellno)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->setCellValue('B' . $startcellno, $generateddatahistory->title . ' ' . $generateddatahistory->lastname . ', ' . $generateddatahistory->firstname . ' ' . $generateddatahistory->middlename . ' ' . $generateddatahistory->suffix);
                $sheet->setCellValue('C' . $startcellno, floor($generateddatahistory->basicsalaryamount * 100) / 100);

                $grossIndex = array_search('GROSS', $filterheader);
                $netPayIndex = array_search('Net Pay', $filterheader);
                $totaldeductionIndex = array_search('Total Deductions', $filterheader);
                $UndertimeIndex = array_search('Undertime', $filterheader);
                $absentlateIndex = array_search('Absent/Late', $filterheader);
                $regularloadtardyIndex = array_search('Regularload Tardy', $filterheader);
                $overloadtardyIndex = array_search('Overload Tardy', $filterheader);
                $emergencyloadtardyIndex = array_search('Emergency Tardy', $filterheader);

                if ($grossIndex !== false) {
                    $grossCell = $sheet->getCellByColumnAndRow($grossIndex + 1, $startcellno);
                    $sheet->setCellValue($grossCell->getCoordinate(), $generateddatahistory->totalearning);
                }
                if ($netPayIndex !== false) {
                    $netPayCell = $sheet->getCellByColumnAndRow($netPayIndex + 1, $startcellno);
                    $sheet->setCellValue($netPayCell->getCoordinate(), $generateddatahistory->netsalary);
                }
                if ($totaldeductionIndex !== false) {
                    $totaldeductionCell = $sheet->getCellByColumnAndRow($totaldeductionIndex + 1, $startcellno);
                    $sheet->setCellValue($totaldeductionCell->getCoordinate(), $generateddatahistory->totaldeduction);
                }
                if ($UndertimeIndex !== false) {
                    $UndertimeCell = $sheet->getCellByColumnAndRow($UndertimeIndex + 1, $startcellno);
                    $sheet->setCellValue($UndertimeCell->getCoordinate(), $generateddatahistory->undertimeamount);
                }
                if ($absentlateIndex !== false) {
                    $absentlateCell = $sheet->getCellByColumnAndRow($absentlateIndex + 1, $startcellno);
                    $sheet->setCellValue($absentlateCell->getCoordinate(), $generateddatahistory->daysabsentamount + $generateddatahistory->lateamount);
                }
                if ($regularloadtardyIndex !== false) {
                    $regularloadtardyCell = $sheet->getCellByColumnAndRow($regularloadtardyIndex + 1, $startcellno);
                    if ($generateddatahistory->regulartardyamount == 0) {
                        $sheet->setCellValue($regularloadtardyCell->getCoordinate(), null);
                    } else {
                        $sheet->setCellValue($regularloadtardyCell->getCoordinate(), $generateddatahistory->regulartardyamount);
                    }
                }
                if ($overloadtardyIndex !== false) {
                    $overloadtardyCell = $sheet->getCellByColumnAndRow($overloadtardyIndex + 1, $startcellno);
                    if ($generateddatahistory->overloadtardyamount == 0) {
                        $sheet->setCellValue($overloadtardyCell->getCoordinate(), null);
                    } else {
                        $sheet->setCellValue($overloadtardyCell->getCoordinate(), $generateddatahistory->overloadtardyamount);
                    }
                }
                if ($emergencyloadtardyIndex !== false) {
                    $emergencyloadtardyCell = $sheet->getCellByColumnAndRow($emergencyloadtardyIndex + 1, $startcellno);
                    if ($generateddatahistory->emergencyloadtardyamount == 0) {
                        $sheet->setCellValue($emergencyloadtardyCell->getCoordinate(), null);
                    } else {
                        $sheet->setCellValue($emergencyloadtardyCell->getCoordinate(), $generateddatahistory->emergencyloadtardyamount);
                    }
                }

                $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColumnIndex);
                // Define the range from A to AE
                $range = 'A' . $startcellno . ':' . $lastColumnLetter . $startcellno;

                // Apply borders and make data bold for the entire range
                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'name' => 'Arial',
                    ],
                ]);

                // Set alignment for column A and B to left, and the rest to center
                $sheet->getStyle('A' . $startcellno . ':B' . $startcellno)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C' . $startcellno . ':' . $lastColumnLetter . $startcellno)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $startcellno += 1;
                $sNoCounter += 1;

                $netsalary += $generateddatahistory->netsalary;
                $basicsalaryamounttotal += floor($generateddatahistory->basicsalaryamount * 100) / 100;
            }


        }
        // return $basicsalaryamounttotal;

        // Determine the last row where data is inserted
        $lastRow = $startcellno;

        // foreach ($filterheader as $key => $header) {
        //     $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);
        //     $sheet->setCellValue($column . $lastRow, $columnTotals[$header]);

        // }

        // return $lastRow;
        $totalnetpayRow = $lastRow + 1;
        $columnNumber = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumnLetter);

        // return $lastRow;
        $previousColumnNumber = $columnNumber - 1;
        $previousColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($previousColumnNumber);

        // $sheet->setCellValue($previousColumnLetter . $startcellno, 'Total =' .' '.$netsalary);
        // $sheet->setCellValue($lastColumnLetter . $startcellno, '');

        // // Get the range of cells
        $cellRange = $previousColumnLetter . $startcellno . ':' . $lastColumnLetter . $startcellno;
        // return $cellRange;
        // // Merge the cells
        // $sheet->mergeCells($cellRange);

        $totalrow = $lastRow + 1;

        // Insert the dynamic SUM formulas for each column in the total row
        foreach ($filterheader as $key => $header) {
            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($key + 1);

            // Exclude the first column (S.No.) from formatting
            if ($key > 0) {
                $sumFormula = '=SUM(' . $column . '7:' . $column . ($lastRow - 1) . ')';
                $sheet->setCellValue($column . $totalrow, $sumFormula);

                // Apply bold, center, and border formatting
                $sheet->getStyle($column . $lastRow . ':' . $column . $totalrow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ]);
            }
        }

        $sheet->setCellValue('B' . $totalrow, 'TOTAL');

        $grandtotalrow = $totalrow + 2;
        $sheet->setCellValue('B' . $grandtotalrow, 'GRAND TOTAL');
        $sheet->setCellValue('C' . $grandtotalrow, $netsalary);
        // Add the signatory to column C after the last row

        $signatoryRow = $grandtotalrow + 3;
        // $sheet->setCellValue('B' . $signatoryRow, 'Prepared by:');
        // $sheet->setCellValue('C' . $signatoryRow, 'Checked by:');
        // $sheet->setCellValue('E' . $signatoryRow, '');
        // $sheet->setCellValue('G' . $signatoryRow, 'Verified by:');
        // $sheet->setCellValue('J' . $signatoryRow, 'Recommending Approval');
        // $sheet->setCellValue('N' . $signatoryRow, 'Approved by:');

        // $nameRow = $signatoryRow + 2;
        // $sheet->setCellValue('B' . $nameRow, 'LAARNIE F. NEROSA');
        // $sheet->setCellValue('C' . $nameRow, 'LYCA ESCALON');
        // $sheet->setCellValue('E' . $nameRow, 'NOEL A. PUNU');
        // $sheet->setCellValue('G' . $nameRow, 'DR. SUZANNE A. PUNU');
        // $sheet->setCellValue('J' . $nameRow, 'DR. DELIA C. ADVINCULA');
        // $sheet->setCellValue('N' . $nameRow, 'DR. RUBEN L. DELA CRUZ, RGC.');

        // $positionRow = $nameRow + 1;
        // $sheet->setCellValue('B' . $positionRow, 'Payroll');
        // $sheet->setCellValue('C' . $positionRow, 'Accounting Incharge');
        // $sheet->setCellValue('E' . $positionRow, 'Accounting Staff');
        // $sheet->setCellValue('G' . $positionRow, 'School Treasurer');
        // $sheet->setCellValue('J' . $positionRow, 'VP for Finance and Administration');
        // $sheet->setCellValue('N' . $positionRow, 'President');

        $sheet->getStyle('B' . ($totalrow - 1) . ':' . $lastColumnLetter . ($totalrow - 1))
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('ffffff00'); // Use 'FFFF00' for yellow
        // Set alignment for the Grand Total row (yellow)
        $sheet->getStyle('B' . $grandtotalrow . ':' . $lastColumnLetter . $grandtotalrow)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set alignment for the values on the Grand Total row
        $sheet->getStyle('C' . $grandtotalrow . ':' . $lastColumnLetter . $grandtotalrow)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set background color for $totalrow (yellow)
        $sheet->getStyle('B' . $totalrow . ':' . $lastColumnLetter . $totalrow)
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('ffffff00'); // Use 'FFFF00' for yellow

        // Set background color for the row above $grandtotalrow (red)
        // Define the common style array
        $commonStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'ff91d2ff'], // Use 'FF0000' for red
            ],
            'font' => [
                'bold' => true,
            ]
        ];

        // Loop through each column and apply style
        for ($colIndex = 2; $colIndex <= $lastColumnIndex; $colIndex++) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);

            // Set background color, border, and bold for the Grand Total row
            $sheet->getStyle($columnLetter . $grandtotalrow)->applyFromArray($commonStyle);

            // Set background color, border, and bold for the row above the Grand Total row
            $sheet->getStyle($columnLetter . ($grandtotalrow - 1))->applyFromArray($commonStyle);
        }

        // $sheet->getStyle('B' . $nameRow . ':N' . $nameRow)->applyFromArray([
        //     'font' => [
        //         'bold' => true,
        //         'underline' => true,
        //         'name' => 'Arial Bold',
        //     ],
        // ]);

        // $sheet->getStyle($previousColumnLetter . $startcellno. ':' . $lastColumnLetter . $startcellno)->applyFromArray([
        //     'font' => [
        //         'bold' => true,
        //         'name' => 'Arial Bold',
        //     ],
        //     'alignment' => [
        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        //         'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //     ],
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //         ],
        //     ]
        // ]);

        // $styleArray = [
        //     'font' => [
        //         'bold' => true,
        //         'name' => 'Arial Bold',
        //     ],
        //     'alignment' => [
        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //         'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //     ],
        //     'borders' => [
        //         'outline' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['argb' => '000000'], // Border color
        //         ],
        //     ],
        // ];

        // $sheet->getStyle($cellRange)->applyFromArray($styleArray);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="payrollgenerate.xlsx"');
        $writer->save("php://output");
        exit();

    }


    public static function payrollhistorybydepartment(Request $request)
    {
        $payrollid = $request->get('payrollid');

        $departments = DB::table('hr_departments')
            ->select('department as text', 'id')
            ->where('deleted', '0')
            ->get();

        return $departments;

    }
    public static function loademployeebydepartment(Request $request)
    {
        $payrollid = $request->get('payrollid');
        $depid = $request->get('depid');

        $histories = DB::table('hr_payrollv2history')
            ->select(
                'hr_payrollv2history.id',
                'hr_payrollv2history.employeeid',
                'hr_payrollv2history.presentdays',
                'hr_payrollv2history.absentdays',
                'hr_payrollv2history.basicsalaryamount',
                'hr_payrollv2history.basicsalarytype',
                'hr_payrollv2history.daysabsentamount',
                'hr_payrollv2history.lateamount',
                'hr_payrollv2history.undertimeminutes',
                'hr_payrollv2history.totalhoursworked',
                'hr_payrollv2history.amountperday',
                'hr_payrollv2history.netsalary',
                'hr_payrollv2history.totalearning',
                'hr_payrollv2history.totaldeduction',
                'hr_payrollv2history.monthlysalary',
                'hr_payrollv2history.releaseddatetime',
                'teacher.lastname',
                'teacher.middlename',
                'teacher.firstname',
                'teacher.suffix',
                'teacher.title',
                'teacher.tid',
                'employee_personalinfo.gender',
                'employee_personalinfo.departmentid',
                'usertype.utype',
                'teacher.picurl'
            )
            ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->leftJoin('usertype', 'teacher.id', '=', 'usertype.id')
            ->where('hr_payrollv2history.payrollid', $payrollid)
            ->where('employee_personalinfo.departmentid', $depid)
            ->where('hr_payrollv2history.deleted', '0')
            ->where('hr_payrollv2history.released', '1')
            ->orderBy('lastname', 'asc')
            ->get();

        // return $histories;
        return view('hr.payroll.v3.summary_results')
            ->with('histories', $histories);

    }

    public static function allreleasedpayrolldates(Request $request)
    {
        $salid = $request->get('salid');

        $releaseddates = DB::table('hr_payrollv2')
            ->select(
                'id',
                'datefrom',
                'dateto',
                'status',
                'datefrom',
                'salarytypeid',
                DB::raw("CONCAT(MONTHNAME(datefrom), ' ', DAY(datefrom), '-', DAY(dateto), ' ', YEAR(dateto)) as text")
            )
            ->where('deleted', 0)
            ->where('status', '!=', 1)
            ->where('salarytypeid', $salid)
            ->orderBy('id', 'ASC')
            ->get();

        return $releaseddates;
    }
    public static function allreleasedemployees(Request $request)
    {

        $releasepdateid = $request->get('releasepdateid');

        $released = DB::table('hr_payrollv2history')
            ->select(
                DB::raw("CONCAT(COALESCE(teacher.title, ''), ' ', COALESCE(teacher.firstname, ''), ' ', COALESCE(teacher.middlename, ''), '. ', COALESCE(teacher.lastname, '')) as text"),
                'teacher.id',
                'hr_payrollv2history.employeeid',
                'hr_payrollv2history.released',
                'hr_payrollv2history.configured'
            )
            ->leftJoin('teacher', 'hr_payrollv2history.employeeid', '=', 'teacher.id')
            ->where('hr_payrollv2history.deleted', 0)
            ->where('teacher.deleted', 0)
            // ->where('hr_payrollv2history.released', 1)
            ->where(function ($query) {
                $query->where('hr_payrollv2history.released', 1)
                    ->orWhereNotNull('hr_payrollv2history.editby');
            })
            ->where('hr_payrollv2history.configured', 1)
            ->where('hr_payrollv2history.payrollid', $releasepdateid)
            ->get();

        return $released;
    }

    public static function listofemployees(Request $request)
    {

        $employees = DB::table('teacher')
            ->selectRaw('teacher.id,employee_personalinfo.departmentid,
                employee_basicsalaryinfo.parttimer,
                CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) as text,
                employee_basicsalaryinfo.salarybasistype,
                amount as salaryamount,
                utype as designation')
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get();

        return $employees;
    }
}
