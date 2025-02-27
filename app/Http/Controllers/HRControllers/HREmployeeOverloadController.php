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
class HREmployeeOverloadController extends Controller
{
    public function index(Request $request)
    {
        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();
        
        $semester = DB::table('semester')
            ->where('isactive', 1)
            ->first();

        return view('hr.employees.overload')
               ->with('sy', $sy)
               ->with('semester', $semester);
    }
    // This function is to load all employees
    public function loademployees(Request $request)
    {
        $employees = DB::table('teacher')
            ->select('teacher.id', DB::raw('CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) AS full_name'), 'suffix', 'amount as salaryamount', 'utype as designation', 'employee_basicsalaryinfo.salarybasistype')
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->where('employee_basicsalaryinfo.deleted', 0)
            ->where('employee_basicsalaryinfo.salarybasistype', '!=', NULL)
            ->orderBy('lastname', 'asc')
            ->get();

        
        return $employees;
    }

    public static function saveallsubjectperemployee(Request $request) {
        $peremployeesubjects = $request->get('subjectarray');
        $responses = [];
        // return $peremployeesubjects;
        // Check if $peremployeesubjects is empty or null
        if (empty($peremployeesubjects)) {
            return response()->json([
                (object)[
                    'status' => 0,
                    'message' => 'Nothing to be Updated!',
                ]
            ]);
        }

        foreach ($peremployeesubjects as $subject) {
            if ($subject["active"] == "0" || $subject["active"] == "1" && $subject["action"] == 'savebyrow') {
                $existingRecord = DB::table('employee_overload')
                    ->where('employeeid', $subject['teacherid'])
                    ->where('subjid', $subject['subjid'])
                    ->where('deleted', 0)
                    ->first(); // Use first() to get a single record
                
                if ($existingRecord) {
                    // Update the existing record
                    DB::table('employee_overload')
                        ->where('id', $existingRecord->id) // Use the ID of the existing record
                        ->update([
                            'active' => $subject['active'],
                            'hourlyrate' => $subject['amountperhour'],
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => now(),
                        ]);

                    $responses[] = (object)[
                        'status' => 1,
                        'message' => 'Updated Successfully!',
                        'employeeid' => $subject['teacherid'],
                    ];
                }
            } else {
                foreach ($peremployeesubjects as $perempdata) {
                    $existingRecord = DB::table('employee_overload')
                        ->where('employeeid', $perempdata['teacherid'])
                        ->where('subjid', $perempdata['subjid'])
                        ->where('deleted', 0)
                        ->first(); // Use first() to get a single record
                    if ($existingRecord) {
                        // Update the existing record
                        DB::table('employee_overload')
                            ->where('id', $existingRecord->id) // Use the ID of the existing record
                            ->update([
                                'active' => $perempdata['active'],
                                'hourlyrate' => $perempdata['amountperhour'],
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => now(),
                            ]);
                        $responses[] = (object)[
                            'status' => 1,
                            'message' => 'Updated Successfully!',
                            'employeeid' => $perempdata['teacherid'],
                        ];
                    } else {
                        // Insert a new record
                        DB::table('employee_overload')->insert([
                            'employeeid' => $perempdata['teacherid'],
                            'subjdesc' => $perempdata['subjdesc'],
                            'subjid' => $perempdata['subjid'],
                            'syid' => $perempdata['syid'],
                            'semid' => $perempdata['semid'],
                            'hourlyrate' => $perempdata['amountperhour'],
                            'hours' => $perempdata['totalHours'],
                            'mondays' => $perempdata['monday'],
                            'tuesdays' => $perempdata['tuesday'],
                            'wednesdays' => $perempdata['wednesday'],
                            'thursdays' => $perempdata['thursday'],
                            'fridays' => $perempdata['friday'],
                            'saturdays' => $perempdata['saturday'],
                            'sundays' => $perempdata['sunday'],
                            'active' => 1,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => now(),
                        ]);
                        $responses[] = (object)[
                            'status' => 1,
                            'message' => 'Assigned Successfully!',
                            'employeeid' => $perempdata['teacherid'],
                        ];
                    }
                }
            }
        }

        
    
        return response()->json($responses);
    }

    public static function loadallemployeeoverload(Request $request){
        $teacherid = $request->get('teacherid');
        $semid = $request->get('semid');
        $syid = $request->get('syid');

        $data = DB::table('employee_overload')
            ->where('employeeid', $teacherid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->get();

        return $data;
    }

    public static function loadalloverload(Request $request){
        $semid = $request->get('semid');
        $syid = $request->get('syid');

        $data = DB::table('employee_overload')
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->get();

        return $data;
    }

    // load payroll date that is active
    public static function loadallpayrollactive(Request $request){
        
        $data = DB::table('hr_payrollv2')
            // ->where('status', 1)
            ->where('deleted', 0)
            ->get();

        return $data;
    }
    
    public static function loadoverloaddateactive(Request $request){
        $overloadperiod = DB::table('hr_payrolldateoverload')
            ->select([
                'id',
                DB::raw("CONCAT(DATE_FORMAT(datefrom, '%b %d'), ' - ', DATE_FORMAT(dateto, '%b %d, %Y')) as text"),
                'status',
                'leapyear',
                'updatedby',
                'updateddatetime',
                'createdby',
                'createddatetime',
                'deleted',
                'deletedby',
                'deleteddatetime',
                'salarytypeid',
            ])
            ->where('deleted', '0')
            //->where('status', '1')
            ->orderBy('datefrom', 'asc') // Order by datefrom in ascending order
            ->get();

        return $overloadperiod;

        
    }

    public static function addoverloadperiod(Request $request){

        
        $dates = explode(' - ', $request->get('dates'));
        $datefrom = date('Y-m-d', strtotime($dates[0]));
        $dateto   = date('Y-m-d', strtotime($dates[1]));


        DB::table('hr_payrolldateoverload')
            ->insert([
                'datefrom'          => $datefrom,
                'dateto'            => $dateto,
                'createdby'         => auth()->user()->id,
                'createddatetime'   => date('Y-m-d H:i:s')
            ]);

        return array((object)[
            'status' => 1,
            'message' => 'Already Exist!',
        ]);
    }

    public static function activateoverloadperiod(Request $request){

        $overloaddateid = $request->get('overloaddateid');


        DB::table('hr_payrolldateoverload')
            ->where('deleted', 0)
            ->where('id', $overloaddateid)
            ->update([
                'status'            => 1,
                'updatedby'         => auth()->user()->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);

        return array((object)[
            'status' => 1,
            'message' => 'Activated Successfully!',
        ]);
    }
    
    
}
