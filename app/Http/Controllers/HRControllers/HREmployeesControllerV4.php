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
use App\Exports\PayrollGenerationExport;
use Maatwebsite\Excel\Facades\Excel;
class HREmployeesControllerV4 extends Controller
{

    public function index(Request $request)
    {

        if (!$request->has('action')) {
            // $employees = DB::table('teacher')
            // ->select(
            //     'teacher.id',
            //     'teacher.userid',
            //     'teacher.title',
            //     'teacher.lastname',
            //     'teacher.firstname',
            //     'teacher.middlename',
            //     'teacher.suffix',
            //     'teacher.licno',
            //     'employee_personalinfo.gender',
            //     'employee_personalinfo.dob',
            //     'employee_personalinfo.address',
            //     'employee_personalinfo.primaryaddress',
            //     'employee_personalinfo.email',
            //     'employee_personalinfo.contactnum',
            //     'employee_personalinfo.spouseemployment',
            //     'employee_personalinfo.numberofchildren',
            //     'employee_personalinfo.date_joined',
            //     'nationality.nationality',
            //     'religion.religionname as religion',
            //     'civilstatus.civilstatus',
            //     'usertype.utype',
            //     'teacher.isactive',
            //     'teacher.picurl',
            //     'teacher.tid',
            //     'teacher.deleted'
            //     )
            // ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            // ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
            // ->leftJoin('nationality','employee_personalinfo.nationalityid','=','nationality.id')
            // ->leftJoin('religion','employee_personalinfo.religionid','=','religion.id')
            // ->leftJoin('civilstatus','employee_personalinfo.maritalstatusid','=','civilstatus.id')
            // ->orderby('lastname', 'asc')
            // ->where('teacher.deleted','0')
            // ->where('teacher.isactive','1')
            // ->get();
            // return view('hr.employees.index')
            //     ->with('employees', $employees);
            return view('hr.employees.index');
        } else {
            if ($request->get('action') == 'getactiveemployees') {
                $search = $request->get('search');
                $search = $search['value'];

                $employees = DB::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.userid',
                    'teacher.title',
                    'teacher.lastname',
                    'teacher.firstname',
                    'teacher.middlename',
                    'teacher.suffix',
                    'teacher.licno',
                    'teacher.isactive',
                    'teacher.picurl',
                    'teacher.tid',
                    'teacher.deleted',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.dob',
                    'employee_personalinfo.address',
                    'employee_personalinfo.email',
                    'employee_personalinfo.contactnum',
                    'employee_personalinfo.date_joined',
                    'employee_personalinfo.hired_status',
                    'nationality.nationality',
                    'religion.religionname as religion',
                    'civilstatus.civilstatus',
                    'usertype.utype as main_usertype',
                    DB::raw("GROUP_CONCAT(subusertype.utype SEPARATOR ', ') as sub_usertype"),
                    'teacher.isactive',
                    'teacher.picurl',
                    'hr_departments.department',
                    'hr_empstatus.description as employmentstatus'
                )
                ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                ->leftJoin('nationality', 'employee_personalinfo.nationalityid', '=', 'nationality.id')
                ->leftJoin('religion', 'employee_personalinfo.religionid', '=', 'religion.id')
                ->leftJoin('civilstatus', 'employee_personalinfo.maritalstatusid', '=', 'civilstatus.id')
                ->leftJoin('hr_departments', 'employee_personalinfo.departmentid', '=', 'hr_departments.id')
                ->leftJoin('hr_empstatus', 'teacher.employmentstatus', '=', 'hr_empstatus.id')
                ->leftJoin('faspriv', 'teacher.userid', '=', 'faspriv.userid')
                ->leftJoin('usertype as subusertype', 'faspriv.usertype', '=', 'subusertype.id')
                ->where('teacher.deleted', '0')
                ->where('teacher.isactive', '1')
                ->groupBy('teacher.id')
                ->orderby('teacher.lastname', 'asc');
            

                if ($search != null) {
                    $employees = $employees->where(function ($query) use ($search) {
                        $query->orWhere('firstname', 'like', '%' . $search . '%');
                        $query->orWhere('lastname', 'like', '%' . $search . '%');
                    });
                }

                $employees = $employees->take($request->get('length'))
                    ->skip($request->get('start'))
                    ->orderBy('lastname', 'asc')
                    // ->whereIn('studinfo.studstatus',[1,2,4])
                    ->get();

             

                $employeescount = DB::table('teacher')
                    ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                    ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                    ->leftJoin('nationality', 'employee_personalinfo.nationalityid', '=', 'nationality.id')
                    ->leftJoin('religion', 'employee_personalinfo.religionid', '=', 'religion.id')
                    ->leftJoin('civilstatus', 'employee_personalinfo.maritalstatusid', '=', 'civilstatus.id')
                    ->orderby('lastname', 'asc')
                    ->where('teacher.deleted', '0')
                    ->where('teacher.isactive', '1');

                if ($search != null) {
                    $employeescount = $employeescount->where(function ($query) use ($search) {
                        $query->orWhere('firstname', 'like', '%' . $search . '%');
                        $query->orWhere('lastname', 'like', '%' . $search . '%');
                    });
                }


                $employeescount = $employeescount
                    ->orderBy('lastname', 'asc')
                    // ->whereIn('studinfo.studstatus',[1,2,4])
                    ->count();


                if ($employeescount > 0) {
                    foreach ($employees as $employee) {
                        $employee->lastname = strtoupper($employee->lastname);
                        $employee->firstname = strtoupper($employee->firstname);
                        $employee->middlename = strtoupper($employee->middlename);
                        $salaryamount = DB::table('employee_basicsalaryinfo')
                            ->join('employee_basistype', 'employee_basicsalaryinfo.salarybasistype', '=', 'employee_basistype.id')
                            ->where('employeeid', $employee->id)
                            ->where('employee_basicsalaryinfo.deleted', '0')
                            ->first();

                        if ($salaryamount) {
                            $employee->salaryamount = $salaryamount->amount;
                            $employee->basistype = $salaryamount->type;
                        } else {
                            $employee->salaryamount = '';
                            $employee->basistype = '';
                        }

                    }
                }


                // return $employees;

                return @json_encode((object) [
                    'data' => $employees,
                    'recordsTotal' => $employeescount,
                    'recordsFiltered' => $employeescount,
                    'employees_otherpreviledge' => $employees_otherpreviledge
                ]);


            } else {
                $schoolinfo = DB::table('schoolinfo')
                    ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
                    ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
                    ->select('schoolinfo.*', 'refregion.regDesc', 'refcitymun.citymunDesc')
                    ->get();

                


                $employees = DB::table('teacher')
                    ->select(
                        'teacher.id',
                        'teacher.userid',
                        'teacher.title',
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.suffix',
                        'teacher.licno',
                        'employee_personalinfo.gender',
                        'employee_personalinfo.sssid',
                        'employee_personalinfo.philhealtid',
                        'employee_personalinfo.pagibigid',
                        'employee_personalinfo.tinid',
                        'employee_personalinfo.dob',
                        'employee_personalinfo.address',
                        'employee_personalinfo.primaryaddress',
                        'employee_personalinfo.email',
                        'employee_personalinfo.contactnum',
                        'employee_personalinfo.spouseemployment',
                        'employee_personalinfo.numberofchildren',
                        'employee_personalinfo.departmentid',
                        'employee_personalinfo.date_joined',
                        'nationality.nationality',
                        'religion.religionname as religion',
                        'civilstatus.civilstatus',
                        'usertype.utype',
                        'teacher.isactive',
                        'teacher.picurl',
                        'teacher.tid',
                        'teacher.deleted',
                        'hr_departments.department',
                        'hr_empstatus.description as employmentstatus',
                        'faspriv.userid as faspriv_userid',
                       
                    )
                    ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                    // ->leftJoin('hr_empstatus', 'teacher.employmentstatus', '=', 'hr_empstatus.id')
                    ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                    ->leftJoin('nationality', 'employee_personalinfo.nationalityid', '=', 'nationality.id')
                    ->leftJoin('religion', 'employee_personalinfo.religionid', '=', 'religion.id')
                    ->leftJoin('civilstatus', 'employee_personalinfo.maritalstatusid', '=', 'civilstatus.id')
                    ->leftJoin('hr_departments', 'employee_personalinfo.departmentid', '=', 'hr_departments.id')
                    ->leftJoin('hr_empstatus', 'teacher.employmentstatus', '=', 'hr_empstatus.id')
                    ->leftJoin('faspriv', 'usertype.id', '=', 'faspriv.usertype')
                   
                    ->orderby('lastname', 'asc')
                    ->where('teacher.deleted', '0')
                    ->where('teacher.isactive', '1')
                    ->get();
                if ($request->exporttype == 'pdf') {
                    $pdf = PDF::loadview('hr/employees/employeelist_pdf', compact('employees', 'schoolinfo'))->setPaper('8.5x14', 'landscape');
                    return $pdf->stream('Employee List');
                } else {

                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();

                    $center = [
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ];

                    $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal('center');
                    foreach (range('A', 'X') as $col) {
                        $sheet->getColumnDimension($col)->setAutoSize(true);
                    }

                    $sheet->setCellValue('A1', '');
                    $sheet->setCellValue('B1', 'Name');
                    $sheet->setCellValue('C1', 'Birthday');
                    $sheet->setCellValue('D1', 'Date Hired');
                    $sheet->setCellValue('E1', 'Years in Service');
                    $sheet->setCellValue('F1', 'Primary Address');
                    $sheet->setCellValue('G1', 'Present Address');
                    $sheet->setCellValue('H1', 'Civil Status');
                    $sheet->setCellValue('I1', 'Religion');
                    $sheet->setCellValue('J1', 'Gender');
                    $sheet->setCellValue('K1', 'License Number');
                    $sheet->setCellValue('L1', 'Undergraduate Course');
                    $sheet->setCellValue('M1', 'Year Graduated');
                    $sheet->setCellValue('N1', 'School');
                    $sheet->setCellValue('O1', 'Post-Graduate Course');
                    $sheet->setCellValue('P1', 'Year Graduated');
                    $sheet->setCellValue('Q1', 'Units Taken');
                    $sheet->setCellValue('R1', 'School');
                    $sheet->setCellValue('S1', 'SSS Number');
                    $sheet->setCellValue('T1', 'PHIC Number');
                    $sheet->setCellValue('U1', 'Pag-Ibig Number');
                    $sheet->setCellValue('V1', 'Present Salary');
                    $sheet->setCellValue('W1', 'Current Position');
                    $sheet->setCellValue('X1', 'Other Assignments');
                    $startcellno = 2;

                    foreach ($employees as $key => $employee) {
                        if ($employee->date_joined == null) {
                            $yearsinservice = "";
                        } else {
                            $date1 = $employee->date_joined;
                            $date2 = date('Y-m-d');
                            $dateDifference = abs(strtotime($date2) - strtotime($date1));

                            $years = floor($dateDifference / (365 * 60 * 60 * 24));
                            $months = floor(($dateDifference - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                            // $days   = floor(($dateDifference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 *24) / (60 * 60 * 24));

                            $yearsinservice = $years . " year(s) and " . $months . " month(s)";
                        }

                        $sss = '';
                        if (DB::table('employee_accounts')->where('employeeid', $employee->id)->where('accountdescription', 'like', '%sss%')->where('deleted', '0')->first()) {
                            $phic = DB::table('employee_accounts')->where('employeeid', $employee->id)->where('accountdescription', 'like', '%sss%')->where('deleted', '0')->first()->accountnum;
                        }
                        $phic = '';
                        if (DB::table('employee_accounts')->where('employeeid', $employee->id)->where('accountdescription', 'like', '%phic%')->where('deleted', '0')->first()) {
                            $phic = DB::table('employee_accounts')->where('employeeid', $employee->id)->where('accountdescription', 'like', '%phic%')->where('deleted', '0')->first()->accountnum;
                        }

                        $ibig = '';
                        if (DB::table('employee_accounts')->where('employeeid', $employee->id)->where('accountdescription', 'like', '%ibig%')->where('deleted', '0')->first()) {
                            $ibig = DB::table('employee_accounts')->where('employeeid', $employee->id)->where('accountdescription', 'like', '%ibig%')->where('deleted', '0')->first()->accountnum;
                        }
                        if ($employee->dob != null) {
                            $employee->dob = date('m/d/Y', strtotime($employee->dob));
                        }
                        if ($employee->date_joined != null) {
                            $employee->date_joined = date('m/d/Y', strtotime($employee->date_joined));
                        }
                        $assignments = DB::table('faspriv')
                            ->select('utype')
                            ->where('userid', $employee->userid)
                            ->join('usertype', 'faspriv.usertype', '=', 'faspriv.usertype')
                            ->where('faspriv.deleted', '0')
                            ->get();
                        $sheet->setCellValue('A' . $startcellno, $key + 1);
                        $sheet->setCellValue('B' . $startcellno, $employee->title . ' ' . $employee->lastname . ', ' . $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->suffix);
                        $sheet->setCellValue('C' . $startcellno, $employee->dob);
                        $sheet->setCellValue('D' . $startcellno, $employee->date_joined);
                        $sheet->setCellValue('E' . $startcellno, $yearsinservice);
                        $sheet->setCellValue('F' . $startcellno, $employee->primaryaddress);
                        $sheet->setCellValue('G' . $startcellno, $employee->address);
                        $sheet->setCellValue('H' . $startcellno, $employee->civilstatus);
                        $sheet->setCellValue('I' . $startcellno, $employee->religion);
                        $sheet->setCellValue('J' . $startcellno, strtoupper($employee->gender));
                        $sheet->setCellValue('K' . $startcellno, $employee->licno);
                        $sheet->setCellValue('L' . $startcellno, '');
                        $sheet->setCellValue('M' . $startcellno, '');
                        $sheet->setCellValue('N' . $startcellno, '');
                        $sheet->setCellValue('O' . $startcellno, '');
                        $sheet->setCellValue('P' . $startcellno, '');
                        $sheet->setCellValue('Q' . $startcellno, '');
                        $sheet->setCellValue('R' . $startcellno, '');
                        $sheet->setCellValue('S' . $startcellno, $sss);
                        $sheet->setCellValue('T' . $startcellno, $phic);
                        $sheet->setCellValue('U' . $startcellno, $ibig);
                        $sheet->setCellValue('V' . $startcellno, ($employee->salaryamount) ?? "" . ' / ' . ($employee->basistype ?? ""));
                        $sheet->setCellValue('W' . $startcellno, $employee->utype);
                        $sheet->setCellValue('X' . $startcellno, collect(collect($assignments)->pluck('utype'))->implode(','));
                        $startcellno += 1;
                    }

                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="Employees.xlsx"');
                    $writer->save("php://output");
                }
            }
        }
    }

    public function addnewemployeesave(Request $request)
    {
        $createdby = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        $checkifexists = DB::table('teacher')
            ->where('firstname', 'like', '%' . $request->get('firstName'))
            ->where('lastname', 'like', '%' . $request->get('lastName'))
            // ->where('usertypeid', $request->get('designationid'))
            ->get();

        if (count($checkifexists) == 0) {

            $sy = DB::table('sy')
            ->where('isactive', '1')
            ->first();

            $filePath = null;
            if ($request->hasFile('employee_picture')) {
                $file = $request->file('employee_picture');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('employeeprofile/'. $sy->sydesc . '/' );
                $file->move($destinationPath, $fileName);
                $filePath = 'employeeprofile/' . $sy->sydesc . '/'. $fileName;
            }


            $newemployeeid = DB::table('teacher')
            ->insertGetId([
                'lastname' => $request->get('lastName'),
                'firstname' => $request->get('firstName'),
                'middlename' => $request->get('middleName'),
                'suffix' => $request->get('suffix'),
                'email' => $request->get('email'),
                'residentaddr' => $request->get('address'),
                'deleted' => 0,
                'createdby' => $createdby->id,
                'createddatetime' => date('Y-m-d H:i:s'),
                'phonenumber' => $request->get('contactnumber'),
                'rfid' => $request->get('rfid'),
                'isactive' => 1,
                'picurl' => $filePath,
                
            ]);


            $newuserid_tid = DB::table('teacher')
            ->where('id', $newemployeeid)
            ->update([
                'tid' => Carbon::now()->isoFormat('YYYY') . sprintf('%04d', $newemployeeid)
            ]);

            // Retrieve the updated value
            $Employee_tid = DB::table('teacher')
            ->where('id', $newemployeeid)
            ->select('tid')
            ->first();



            Db::table('employee_emergencycontact')
            ->insert([
                'employeeid' => $newemployeeid,
                'fname' => $request->get('contactFirstname'),
                'mname' => $request->get('contactMiddlename'),
                'lname' => $request->get('contactLastname'),
                'suffix' => $request->get('contactSuffix'),
                'relationship' => $request->get('Relationship'),
                'telno' => $request->get('Telephone'),
                'contactno' => $request->get('Cellphone'),
                'email' => $request->get('Email'),
            ]);


       

            if ($request->has('work_experiences')) {
                foreach ($request->input('work_experiences') as $workExperience) {
                    // employee_bankaccount::create([
                        DB::table('employee_experience')->insert([
                            'employeeid' => $newemployeeid,
                            'companyname' => $workExperience['Company'],
                            'designation' => $workExperience['Designation'],
                            'periodfrom' => $workExperience['Datefrom'],
                            'periodto' => $workExperience['Dateto'],
                            'createdby' => auth()->user()->id,
                            'createddatetime' => now(),
                        ]);
                }
            }

            if ($request->has('higher_educational_attainments')) {
                foreach ($request->input('higher_educational_attainments') as $higherEducationalAttainment) {
                    // employee_bankaccount::create([
                        DB::table('employee_educationinfo')->insert([
                            'employeeid' => $newemployeeid,
                            'schoolname' => $higherEducationalAttainment['Schoolname'],
                            'schoolyear' => $higherEducationalAttainment['Year_graduated'],
                            'coursetaken' => $higherEducationalAttainment['Course'],
                            'awards' => $higherEducationalAttainment['Award'],
                            'createdby' => auth()->user()->id,
                            'createddatetime' => now(),
                        ]);
                }
            }



            // $sy = DB::table('sy')
            // ->where('isactive', '1')
            // ->first();

            // $filePath = null;
            // if ($request->hasFile('employee_picture')) {
            //     $file = $request->file('employee_picture');
            //     $fileName = time() . '.' . $file->getClientOriginalExtension();
            //     $destinationPath = public_path('employeeprofile/'. $sy->sydesc . '/' );
            //     $file->move($destinationPath, $fileName);
            //     $filePath = 'employeeprofile/' . $sy->sydesc . '/'. $fileName;
            // }

            Db::table('employee_personalinfo')
            ->insert([
                'employeeid' => $newemployeeid,
                'sssid' => $request->get('SSS'),
                'philhealtid' => $request->get('Philhealth'),
                'pagibigid' => $request->get('PagIbig'),
                'tinid' => $request->get('TIN'),
                'hired_status' => 1,
                'gender' => $request->get('sex'),

                // 'employee_picture' => $request->get('employee_picture'),
                'employee_picture' => $filePath,
                'createdby' => auth()->user()->id,
                'createddatetime' => now()
            ]);


            if ($request->has('bank_accounts')) {
                foreach ($request->input('bank_accounts') as $bankAccount) {
                    // employee_bankaccount::create([
                        DB::table('employee_bankaccount')->insert([
                        'employeeid' =>  $newemployeeid,
                        'bankname' => $bankAccount['append_bank_name'],
                        'banknumber' => $bankAccount['append_bank_number'],
                        'createdby' => auth()->user()->id,
                        'createddatetime' => now(),
                    ]);
                }
            }



            // return response()->json([
            //     ['status' => 1, 'message' => 'New Employee Created Successfully']
            // ]);

            return response()->json([
                'status' => 1,
                'message' => 'New Employee Created Successfully',
                'employee_id' => $newemployeeid,
                'tid' => $Employee_tid->tid
            ]);

        }

    }


    public function employeeupdate(Request $request)
    {

        $sy = DB::table('sy')
        ->where('isactive', '1')
        ->first();

        $filePath = null;
        if ($request->hasFile('employee_picture')) {
            $file = $request->file('employee_picture');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('employeeprofile/'. $sy->sydesc . '/' );
            $file->move($destinationPath, $fileName);
            $filePath = 'employeeprofile/' . $sy->sydesc . '/'. $fileName;
        }

        
        DB::table('teacher')
        ->where('id', $request->get('id'))
        
        ->update([
            'picurl' => $filePath,
            'firstname' => $request->get('employee_firstname'),
            'lastname' => $request->get('employee_lastname'),
            'middlename' => $request->get('employee_middlename'),
            'phonenumber' => $request->get('employee_cellphone'),
            'email' => $request->get('employee_email'),
            'residentaddr' => $request->get('employee_address'),
            'rfid' => $request->get('employee_rfid')
        ]);


        DB::table('employee_personalinfo')
        ->where('employeeid', $request->get('id'))
        ->update([
            'sssid' => $request->get('employee_sss'),
            'philhealtid' => $request->get('employee_philhealth'),
            'pagibigid' => $request->get('employee_pagibig'),
            'tinid' => $request->get('employee_tin'),
            'gender' => $request->get('employee_sex')           
        ]);

        
        DB::table('employee_emergencycontact')
        ->where('employeeid', $request->get('id'))
        ->update([
    
            'mname' => $request->get('employee_contactmname'),
            'lname' => $request->get('employee_contactlname'),
            'suffix' => $request->get('employee_contactsuffix'),
            'relationship' => $request->get('employee_contactrelationship'),
            'telno' => $request->get('employee_contacttelephone'), 
            'contactno' => $request->get('employee_contactcellphone'),
            'email' => $request->get('employee_contactemail') 
        ]);


    if ($request->has('highestEducations') && is_array($request->highestEducations)) {
        // return $request->highestEducations; 
        foreach ($request->highestEducations as $highestEducationData) {
            $exists = DB::table('employee_educationinfo')
                ->where('employeeid', $request->get('id'))
                ->where('id', $highestEducationData['educationid'])
                ->exists();
    
            if ($exists) {
                DB::table('employee_educationinfo')
                    ->where('employeeid', $request->get('id'))
                    ->where('id', $highestEducationData['educationid'])
                    ->update([
                        'schoolname' => $highestEducationData['schoolname'],
                        'schoolyear' => $highestEducationData['yearsgraduated'],
                        'coursetaken' => $highestEducationData['course'],
                        'awards' => $highestEducationData['award']
                    ]);
            } else {
                DB::table('employee_educationinfo')
                    ->insert([
                        'employeeid' => $request->get('id'),
                        'schoolname' => $highestEducationData['schoolname'],
                        'schoolyear' => $highestEducationData['yearsgraduated'],
                        'coursetaken' => $highestEducationData['course'],
                        'awards' => $highestEducationData['award'],
                        'createdby' => auth()->user()->id,
                        'createddatetime' => now()
                    ]);
            }
        }
    }
    

    if ($request->has('work_experiences') && is_array($request->work_experiences)) {
        // return $request->highestEducations; 
        foreach ($request->work_experiences as $workExperienceData) {
            $exists = DB::table('employee_experience')
                ->where('employeeid', $request->get('id'))
                ->where('id', $workExperienceData['workexpid'])
                ->exists();
    
            if ($exists) {
                DB::table('employee_experience')
                    ->where('employeeid', $request->get('id'))
                    ->where('id', $workExperienceData['workexpid'])
                    ->update([
                        'companyname' => $workExperienceData['companyname'],
                        'designation' => $workExperienceData['designation'],
                        'periodfrom' => $workExperienceData['datefrom'],
                        'periodto' => $workExperienceData['dateto'],
                       
                    ]);
            } else {
                DB::table('employee_experience')
                    ->insert([
                        'employeeid' => $request->get('id'),
                        'companyname' => $workExperienceData['companyname'],
                        'designation' => $workExperienceData['designation'],
                        'periodfrom' => $workExperienceData['datefrom'],
                        'periodto' => $workExperienceData['dateto'],
                        'createdby' => auth()->user()->id,
                        'createddatetime' => now()
                    ]);
            }
        }
    }


    if ($request->has('bank_accounts') && is_array($request->bank_accounts)) {
        // return $request->highestEducations; 
        foreach ($request->bank_accounts as $bankAccountData) {
            $exists = DB::table('employee_bankaccount')
                ->where('employeeid', $request->get('id'))
                ->where('id', $bankAccountData['bankid'])
                ->exists();
    
            if ($exists) {
                DB::table('employee_bankaccount')
                    ->where('employeeid', $request->get('id'))
                    ->where('id', $bankAccountData['bankid'])
                    ->update([
                        'bankname' => $bankAccountData['bankname'],
                        'banknumber' => $bankAccountData['banknumber'] 
                    ]);
            } else {
                DB::table('employee_bankaccount')
                    ->insert([
                        'employeeid' => $request->get('id'),
                        'bankname' => $bankAccountData['bankname'],
                        'banknumber' => $bankAccountData['banknumber'],
                        'createdby' => auth()->user()->id,
                        'createddatetime' => now()
                    ]);
            }
        }
    }

    return response()->json([
        'status' => 1,
        'message' => 'Employee Updated Successfully'
    ]);
        

    }


    // public function employee_employmentdetails_update(Request $request)
    // {
    //     $sy = DB::table('sy')
    //     ->where('isactive', '1')
    //     ->first();

    //     $filePath = null;
    //     if ($request->hasFile('employee_picture')) {
    //         $file = $request->file('employee_picture');
    //         $fileName = time() . '.' . $file->getClientOriginalExtension();
    //         $destinationPath = public_path('employeeprofile/'. $sy->sydesc . '/' );
    //         $file->move($destinationPath, $fileName);
    //         $filePath = 'employeeprofile/' . $sy->sydesc . '/'. $fileName;
    //     }

    //     DB::table('teacher')
    //     ->where('id', $request->get('id'))
        
    //     ->update([
    //         'picurl' => $filePath,
    //         'usertypeid' => $request->get('edit_select_designation'),
    //         'employmentstatus' => $request->get('edit_select_jobstatus'),
          
    //     ]);

    //     DB::table('employee_personalinfo')
    //     ->where('employeeid', $request->get('id'))
        
    //     ->update([
    //         'departmentid' => $request->get('edit_select_department'),
    //         'officeid' => $request->get('edit_select_office'),
    //         'date_joined' => $request->get('edit_date_hired'),
    //         'yos' => $request->get('edit_accumulated_years_service'),
    //         'probation_start' => $request->get('edit_probationary_start_date'),
    //         'probation_end' => $request->get('edit_probationary_end_date'),   
    //     ]);

    //     $currentUserId = auth()->user()->id;
    //     $currentDatetime = date('Y-m-d H:i:s');
    //     $scheduleData = $request->get('edit_scheduleData');
    //     $data = [
    //         'updatedby' => $currentUserId,
    //         'updateddatetime' => $currentDatetime,
    //     ];

    //     foreach ($scheduleData as $day => $sched) {
    //         $data['employeeid'] = $request->get('id');
    //         $data['general_workdaysid'] = $request->get('general_workdays_id');
    //         $dayName = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'][$day];
    //         $data[$dayName] = $sched['dayType'];
    //         $data[$dayName . '_amin'] = $sched['amin'];
    //         $data[$dayName . '_amout'] = $sched['amout'];
    //         $data[$dayName . '_pmin'] = $sched['pmin'];
    //         $data[$dayName . '_pmout'] = $sched['pmout'];
    //     }

    //     DB::table('employee_workdays')
    //     ->where('employeeid', $request->get('id'))
    //     ->update($data);

    //     return response()->json([
    //         'status' => 1,
    //         'message' => 'Employment Details Updated Successfully'
    //     ]);


    // }


    public function employee_employmentdetails_update(Request $request)
    {
        $sy = DB::table('sy')
        ->where('isactive', '1')
        ->first();

        $filePath = null;
        if ($request->hasFile('employee_picture')) {
            $file = $request->file('employee_picture');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('employeeprofile/'. $sy->sydesc . '/' );
            $file->move($destinationPath, $fileName);
            $filePath = 'employeeprofile/' . $sy->sydesc . '/'. $fileName;
        }

        DB::table('teacher')
        ->where('id', $request->get('id'))
        
        ->update([
            'picurl' => $filePath,
            'usertypeid' => $request->get('edit_select_designation'),
            'employmentstatus' => $request->get('edit_select_jobstatus'),
          
        ]);

        DB::table('employee_personalinfo')
        ->where('employeeid', $request->get('id'))
        
        ->update([
            'departmentid' => $request->get('edit_select_department'),
            'officeid' => $request->get('edit_select_office'),
            'date_joined' => $request->get('edit_date_hired'),
            'yos' => $request->get('edit_accumulated_years_service'),
            'probation_start' => $request->get('edit_probationary_start_date'),
            'probation_end' => $request->get('edit_probationary_end_date'),   
        ]);


        $employeeExist = DB::table('employee_workdays')
        ->where('employeeid', $request->get('id'))
        ->exists();
        if ($employeeExist) {
            $currentUserId = auth()->user()->id;
            $currentDatetime = date('Y-m-d H:i:s');
            $scheduleData = $request->get('edit_scheduleData');
            $data = [
                'updatedby' => $currentUserId,
                'updateddatetime' => $currentDatetime,
            ];

            foreach ($scheduleData as $day => $sched) {
                $data['employeeid'] = $request->get('id');
                $data['general_workdaysid'] = $request->get('general_workdays_id');
                $dayName = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'][$day];
                $data[$dayName] = $sched['dayType'];
                $data[$dayName . '_amin'] = $sched['amin'];
                $data[$dayName . '_amout'] = $sched['amout'];
                $data[$dayName . '_pmin'] = $sched['pmin'];
                $data[$dayName . '_pmout'] = $sched['pmout'];
            }

            DB::table('employee_workdays')
            ->where('employeeid', $request->get('id'))
            ->update($data);

         }
         else {
            $scheduleData = $request->get('edit_scheduleData');
            $currentUserId = auth()->user()->id;
            $currentDatetime = date('Y-m-d H:i:s');

            $data = [
                'createdby' => $currentUserId,
                'createddatetime' => $currentDatetime,
            ];
    
            // Loop through each day and its schedule
            foreach ($scheduleData as $day => $sched) {
                // Set the description and day-specific data
                // $data['general_workdaysid'] = 1;
                $data['employeeid'] = $request->get('id');
                $data['general_workdaysid'] = $request->get('general_workdays_id');
                $dayName = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'][$day];
                $data[$dayName] = $sched['dayType'];
                $data[$dayName . '_amin'] = $sched['amin'];
                $data[$dayName . '_amout'] = $sched['amout'];
                $data[$dayName . '_pmin'] = $sched['pmin'];
                $data[$dayName . '_pmout'] = $sched['pmout'];
            }
    
            // Insert the data into the database
            DB::table('employee_workdays')->insert($data);
         }

        return response()->json([
            'status' => 1,
            'message' => 'Employment Details Updated Successfully'
        ]);


    }




    public function create_employmentdetails(Request $request)
    {

        $new_employeeid =  $request->get('selected_employeeid');

        $newuserid = DB::table('users')
        ->insertGetId([
            'name' => $request->get('lastname') . ', ' . $request->get('firstname'),
            'email' => Carbon::now()->isoFormat('YYYY') . sprintf('%04d', $new_employeeid),
            'type' => $request->get('designation'),
            'deleted' => '0',
            'password' => Hash::make('123456')
        ]);

        DB::table('teacher')
        ->where('id', $new_employeeid)
        ->update([
            'userid' => $newuserid
        ]);

        DB::table('employee_personalinfo')
            ->where('employeeid', $request->get('selected_employeeid'))
            ->update([
                'designationid' => $request->get('designation'),
                'departmentid' => $request->get('department'),
                'date_joined' => $request->get('date_hired'),
                'yos' => $request->get('yos'),
                'hired_status' => $request->get('job_status')
              
            ]);
            $currentUserId = auth()->user()->id;
            $currentDatetime = date('Y-m-d H:i:s');
            $scheduleData = $request->get('scheduleData');
            $data = [
                'createdby' => $currentUserId,
                'createddatetime' => $currentDatetime,
            ];

            foreach ($scheduleData as $day => $sched) {
                $data['employeeid'] = $request->get('selected_employeeid');
                $data['general_workdaysid'] = $request->get('general_workdays');
                $dayName = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'][$day];
                $data[$dayName . '_amin'] = $sched['amin'];
                $data[$dayName . '_amout'] = $sched['amout'];
                $data[$dayName . '_pmin'] = $sched['pmin'];
                $data[$dayName . '_pmout'] = $sched['pmout'];
                $data[$dayName] = $sched['dayType'];
            }
 
            DB::table('employee_workdays')->insert($data);

            $data_basic_employment_teacherdb = [
                'usertypeid' => $request->get('designation'),
                'employmentstatus' => $request->get('job_status')
            ];

            DB::table('teacher')->where('id', $request->get('selected_employeeid'))->update($data_basic_employment_teacherdb);


            $data_basic_employment_emp_peraonaldb = [
                'departmentid' => $request->get('department'),
                'officeid' => $request->get('office'),
                'date_joined' => $request->get('date_hired'),
                'yos' => $request->get('yos'),
                'probation_start' => $request->get('prob_start_date'),
                'probation_end' => $request->get('prob_end_date')
            ];

            DB::table('employee_personalinfo')
                ->where('employeeid', $request->get('selected_employeeid'))
                ->update($data_basic_employment_emp_peraonaldb);


        return response()->json([
            'status' => 1,
            'message' => 'Employment Details Created Successfully'
        ]);


    }

    public static function select_employees(Request $request)
    {
        $employees = DB::table('teacher')
            ->select(DB::raw("concat(lastname,', ',firstname) as full_name"), 'lastname' , 'id')
            ->where('deleted', '0')
            // ->orderBy('lastname', 'ASC')
            ->get();

        return $employees;
    }


    public static function select_offices(Request $request)
    {
        $offices = DB::table('hr_offices')
            ->select('officename as text', 'id')
            ->where('deleted', '0')
            ->orderBy('officename', 'ASC')
            ->get();

        return $offices;
    }

    public static function select_jobstatus(Request $request)
    {
        $job_status = DB::table('hr_empstatus')
        ->select('description as text', 'id')
        ->where('deleted', '0')
        ->orderBy('description', 'ASC')
        ->get();

        return $job_status;
    }


    public static function select_general_workdays(Request $request)
    {
        $offices = DB::table('hr_workdays')
            ->select('description as text', 'id')
            ->where('deleted', '0')
            ->orderBy('description', 'ASC')
            ->get();

        return $offices;
    }


    public static function edit_selected_employee(Request $request){

        $employeeid = $request->get('employee_id');

        $employee = DB::table('teacher')
                    ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                    ->leftJoin('employee_emergencycontact', 'teacher.id', '=', 'employee_emergencycontact.employeeid')
                    ->leftJoin('hr_departments', 'employee_personalinfo.departmentid', '=', 'hr_departments.id')
                    ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                    ->leftJoin('hr_offices', 'employee_personalinfo.officeid', '=', 'hr_offices.id')
                    ->leftJoin('hr_empstatus', 'teacher.employmentstatus', '=', 'hr_empstatus.id')
                    ->leftJoin('employee_workdays', 'teacher.id', '=', 'employee_workdays.employeeid')
                    ->leftJoin('hr_workdays', 'employee_workdays.general_workdaysid', '=', 'hr_workdays.id')
                    // ->leftJoin('hr_empstatus', 'employee_personalinfo.hired_status', '=', 'hr_empstatus.id')
                    ->where('teacher.deleted', 0)
                    ->where('teacher.id', $employeeid)
                    ->select(
                        'teacher.id',
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.suffix',
                        'teacher.phonenumber',
                        'teacher.email',
                        'teacher.residentaddr',
                        'teacher.licno',
                        'teacher.rfid',
                        'teacher.picurl',
                        'teacher.usertypeid',
                        'teacher.tid',
                        'teacher.employmentstatus',
                        
                        'employee_personalinfo.designationid',
                        'employee_personalinfo.departmentid',
                        'employee_personalinfo.officeid',
                        'employee_personalinfo.sssid',
                        'employee_personalinfo.philhealtid',
                        'employee_personalinfo.pagibigid',
                        'employee_personalinfo.tinid',
                        'employee_personalinfo.hired_status',
                        'employee_personalinfo.yos',
                        'employee_personalinfo.date_joined',
                        'employee_personalinfo.probation_start',
                        'employee_personalinfo.probation_end',
                        'employee_personalinfo.gender',
                        
                        'employee_emergencycontact.fname',
                        'employee_emergencycontact.mname',
                        'employee_emergencycontact.lname',
                        'employee_emergencycontact.suffix as contact_suffix',
                        'employee_emergencycontact.relationship',
                        'employee_emergencycontact.telno',
                        'employee_emergencycontact.contactno',
                        'employee_emergencycontact.email as contact_email',

                        'hr_departments.department',
                        'usertype.utype as designation',
                        'hr_offices.officename',
                        'hr_empstatus.description as jobstatus',

                        'employee_workdays.general_workdaysid',
                        'hr_workdays.description as general_workdays'
                    )
                    ->get();

        $educationInfo = DB::table('employee_educationinfo')
                    ->where('deleted', 0)
                    ->where('employeeid', $employeeid)
                    ->select(
                        'id',
                        'schoolname', 
                        'schoolyear', 
                        'coursetaken', 
                        'awards'
                    )
                    ->get();

        $work_experiences = DB::table('employee_experience')
                    ->where('deleted', 0)
                    ->where('employeeid', $employeeid)
                    ->select(
                        'id',
                        'companyname', 
                        'designation', 
                        'periodfrom', 
                        'periodto'
                    )
                    ->get();
                    
        $bankAccounts = DB::table('employee_bankaccount')
                    ->where('deleted', 0)
                    ->where('employeeid', $employeeid)
                    ->select(
                        'id',
                        'bankname', 
                        'banknumber', 
                    )
                    ->get();      
                    
        $departments = DB::table('hr_departments')
                     ->select('department as text', 'id')
                     ->where('deleted', '0')
                     ->orderBy('department', 'ASC')
                     ->get();

        $designations = DB::table('usertype')
                     ->select('utype as text', 'id')
                     ->where('deleted', '0')
                     ->orderBy('utype', 'ASC')
                     ->get();

        $offices = DB::table('hr_offices')
                     ->select('officename as text', 'id')
                     ->where('deleted', '0')
                     ->orderBy('officename', 'ASC')
                     ->get();

        $job_status = DB::table('hr_empstatus')
                     ->select('description as text', 'id')
                     ->where('deleted', '0')
                     ->orderBy('description', 'ASC')
                     ->get();

        $general_workdays = DB::table('hr_workdays')
                     ->select('description as text', 'id')
                     ->where('deleted', '0')
                     ->orderBy('description', 'ASC')
                     ->get();

        $employeeworkday_details = DB::table('employee_workdays')
                     ->where('employeeid',$employeeid)
                     ->where('deleted', '0')
                     ->first();
         



        // $job_status = DB::table('hr_empstatus')
        //              ->select('description as text', 'id')
        //              ->where('deleted', '0')
        //              ->orderBy('description', 'ASC')
        //              ->get();





        return response()->json([
            'employee' => $employee,
            'education_info' => $educationInfo,
            'work_experiences' => $work_experiences,
            'bankAccounts' => $bankAccounts,
            'departments' => $departments,
            'designations' => $designations,
            'offices' => $offices,
            'job_status' => $job_status,
            'general_workdays' => $general_workdays,
            'employeeworkday_details' => $employeeworkday_details
            // 'job_status' => $job_status

        ]);
    }

    public static function delete_selected_employee_education(Request $request){
      
        DB::table('employee_educationinfo')
        ->where('id',$request->educationid)
        ->take(1)
        ->update([
              'deleted'=>1,
              'deletedby'=>auth()->user()->id,
              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        ]);
        
        return response()->json([
            'status' => 1,
            'message' => 'Education information deleted successfully'
        ]);
    }


    public static function delete_selected_employee_work_experience(Request $request){
        DB::table('employee_experience')
        ->where('id',$request->workexpid)
        ->take(1)
        ->update([
              'deleted'=>1,
              'deletedby'=>auth()->user()->id,
              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        ]);
        
        return response()->json([
            'status' => 1,
            'message' => 'Education information deleted successfully'
        ]);
    }

    public static function create_leavefrequency(Request $request)
    {
        // Retrieve and validate inputs
        $leave_frequency_desc = $request->input('leave_frequency_desc');

        // Check if recognitionTypeDesc is provided
        if (empty($leave_frequency_desc)) {
            return response()->json([
                ['status' => 2, 'message' => 'Leave Frequency Description is required']
            ]);
        }

            DB::table('hr_leave_frequency')->insert([
            'leave_frequency_desc' => $leave_frequency_desc,
            'createdby' => auth()->user()->id,
            'createddatetime' => now(),
        ]);

        // Insert grade point data into the database
      

        return response()->json([
            ['status' => 1, 'message' => 'Leave Frequency Created Successfully']
        ]);
    }

    public static function fetch_leavefrequency(Request $request)
    {
        $leave_frequency = DB::table('hr_leave_frequency')
        ->select('leave_frequency_desc as text', 'id')
        ->where('deleted', '0')
        ->orderBy('leave_frequency_desc', 'ASC')
        ->get();

    return $leave_frequency;
     
    }

    public static function create_leave(Request $request)
    {
        // Retrieve and validate inputs
        $leave_name = $request->input('leave_name');
        $leave_days = $request->input('leave_days');
        $leave_frequency = $request->input('leave_frequency');
        $applied_to = $request->input('applied_to');
        $years_of_service = $request->input('years_of_service');
        $pay = $request->input('pay');
        $leave_attachment = $request->input('leave_attachment');
        $convert_to_cash = $request->input('convert_to_cash');

        // Check if recognitionTypeDesc is provided
        if (empty($leave_name)) {
            return response()->json([
                ['status' => 2, 'message' => 'Leave Description is required']
            ]);
        }

            DB::table('hr_leaves')->insert([
            'leave_type' => $leave_name,
            'days' => $leave_days,
            'leave_frequency' => $leave_frequency,
            'applied_to' => $applied_to,
            'yos' => $years_of_service,
            'withpay' => $pay,
            'leave_attachment' => $leave_attachment,
            'cash' => $convert_to_cash,
            'createdby' => auth()->user()->id,
            'createddatetime' => now(),
        ]);

        // Insert grade point data into the database
      

        return response()->json([
            ['status' => 1, 'message' => 'Leave Created Successfully']
        ]);
    }

    public static function fetch_leave(Request $request)
    {
        $leave = DB::table('hr_leaves')
        ->select(
             'id',
             'leave_type',
             'days',
             'leave_frequency',
             'applied_to',
             'yos',
             'withpay',
             'leave_attachment',
             'cash',
        )
        ->where('deleted', '0')
        ->orderBy('leave_type', 'ASC')
        ->get();

    return $leave;
     
    }
    
    public static function fetch_selected_leave(Request $request)
    {
        $leave = DB::table('hr_leaves')
        ->select(
             'hr_leaves.id',
             'hr_leaves.leave_type',
             'hr_leaves.days',
             'hr_leaves.leave_frequency',
             'hr_leaves.applied_to',
             'hr_leaves.yos',
             'hr_leaves.withpay',
             'hr_leaves.leave_attachment',
             'hr_leaves.cash',
             'hr_leave_frequency.leave_frequency_desc'
        )
        ->leftJoin('hr_leave_frequency', 'hr_leaves.leave_frequency', '=', 'hr_leave_frequency.id')
        ->where('hr_leaves.deleted', '0')
        ->where('hr_leaves.id', $request->input('leave_id'))
        ->orderBy('hr_leaves.leave_type', 'ASC')
        ->get();

        $leave_frequency = DB::table('hr_leave_frequency')
        ->select('leave_frequency_desc as text', 'id')
        ->where('deleted', '0')
        ->orderBy('leave_frequency_desc', 'ASC')
        ->get();



        return response()->json([
            'leave' => $leave,
            'leave_frequency' => $leave_frequency

        ]);
     
    }

    public function update_leave(Request $request)
    {      
        DB::table('hr_leaves')
        ->where('id', $request->get('id'))
        
        ->update([
            'leave_type' => $request->get('edit_leave_name'),
            'days' => $request->get('edit_leave_days'),
            'leave_frequency' => $request->get('edit_leave_frequency'),
            'yos' => $request->get('edit_years_of_service'),
            'withpay' => $request->get('edit_pay'),
            'leave_attachment' => $request->get('edit_leave_attachment'),   
            'cash' => $request->get('edit_convert_to_cash')
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Leave Details Updated Successfully'
        ]);

    }

    public static function delete_leave(Request $request){
        DB::table('hr_leaves')
        ->where('id',$request->leaveid)
        ->take(1)
        ->update([
              'deleted'=>1,
              'deletedby'=>auth()->user()->id,
              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        ]);
        
        return response()->json([
            'status' => 1,
            'message' => 'Leave information deleted successfully'
        ]);
    }


    // public function all_empoyees_for_attendance(Request $request)
    // {
    //     $search = $request->get('search');
    //     $search = isset($search['value']) ? $search['value'] : null;

    //     $employees = DB::table('teacher')
    //         ->select(
    //             'teacher.id',
    //             'teacher.lastname',
    //             'teacher.firstname'
    //         )
    //         ->where('teacher.deleted', '0')
    //         ->where('teacher.isactive', '1')
    //         ->groupBy('teacher.id')
    //         ->orderBy('lastname', 'asc');

    //     if ($search != null) {
    //         $employees = $employees->where(function ($query) use ($search) {
    //             $query->orWhere('firstname', 'like', '%' . $search . '%');
    //             $query->orWhere('lastname', 'like', '%' . $search . '%');
    //         });
    //     }

    //     $employees = $employees->get();

    //     return response()->json([
    //         'data' => $employees
    //     ]);
    // }

    //  public function all_empoyees_for_attendance(Request $request)
    // {
    //     $search = $request->get('search');
    //     $search = isset($search['value']) ? $search['value'] : null;

    //     $employees = DB::table('teacher')
    //         ->select(
    //             'teacher.id',
    //             'teacher.lastname',
    //             'teacher.firstname',
    //             'hr_attendance.id as attendanceid',
    //             'hr_attendance.tdate',
    //             'hr_attendance.ttime',
    //             'hr_attendance.tapstate',
    //             'hr_attendance.timeshift',
    //         )
    //         ->leftjoin('hr_attendance', 'teacher.id', '=', 'hr_attendance.studid')
    //         ->where('teacher.deleted', '0')
    //         ->where('teacher.isactive', '1')
    //         // ->where('hr_attendance.deleted', '0')
    //         ->groupBy('teacher.id')
    //         ->orderBy('lastname', 'asc');

    //     if ($search != null) {
    //         $employees = $employees->where(function ($query) use ($search) {
    //             $query->orWhere('firstname', 'like', '%' . $search . '%');
    //             $query->orWhere('lastname', 'like', '%' . $search . '%');
    //         });
    //     }

    //     $employees = $employees->get();

    //     return response()->json([
    //         'data' => $employees
    //     ]);
    // }


    public function all_empoyees_for_attendance(Request $request)
    {
        // $search = $request->get('search');
        // $search = isset($search['value']) ? $search['value'] : null;

        $employees = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.lastname',
                'teacher.firstname'
            )
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->groupBy('teacher.id')
            ->orderBy('lastname', 'asc');

        // if ($search != null) {
        //     $employees = $employees->where(function ($query) use ($search) {
        //         $query->orWhere('firstname', 'like', '%' . $search . '%');
        //         $query->orWhere('lastname', 'like', '%' . $search . '%');
        //     });
        // }

        $employees = $employees->get();

        $employee_manual_attendance = DB::table('hr_attendance')
            ->where('deleted', 0)
            ->whereIn('studid', $employees->pluck('id')->toArray())
            ->where('tdate', $request->get('single_date'))
            ->select(
                'id',
                'tdate', 
                'ttime', 
                'tapstate', 
                'timeshift', 
                'studid'
            )
            ->orderBy('tdate', 'asc')
            ->get()
            ->groupBy('studid');


        $employee_manual_attendance_remarks = DB::table('hr_attendanceremarks')
            ->where('deleted', 0)
            ->whereIn('employeeid', $employees->pluck('id')->toArray())
            ->where('tdate', $request->get('single_date'))
            ->select(
                'id',
                'tdate', 
                'remarks',
                'employeeid'
            )
            ->orderBy('tdate', 'asc')
            ->get()
            ->groupBy('employeeid');

        
        $employee_workdays = DB::table('employee_workdays')
            ->where('deleted', 0)
            ->whereIn('employeeid', $employees->pluck('id')->toArray())
            ->get()
            ->groupBy('employeeid');

        return response()->json([
            'data' => $employees,
            'employee_manual_attendance' => $employee_manual_attendance,
            'employee_manual_attendance_remarks' => $employee_manual_attendance_remarks,
            'employee_workdays' => $employee_workdays
        ]);
    }

    public function all_employees_for_multiple_attendance(Request $request)
    {
        $search = $request->get('search');
        $search = isset($search['value']) ? $search['value'] : null;
        $dateArray = $request->get('dateArray'); // Expecting an array of dates
    
        $employees = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.lastname',
                'teacher.firstname'
            )
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->groupBy('teacher.id')
            ->orderBy('lastname', 'asc');
    
        if ($search != null) {
            $employees = $employees->where(function ($query) use ($search) {
                $query->orWhere('firstname', 'like', '%' . $search . '%');
                $query->orWhere('lastname', 'like', '%' . $search . '%');
            });
        }
    
        $employees = $employees->get();
        
        // Fetch attendance records for multiple dates
        $employee_manual_attendance = DB::table('hr_attendance')
            ->where('deleted', 0)
            ->whereIn('studid', $employees->pluck('id')->toArray())
            ->whereIn('tdate', $dateArray)
            ->select(
                'id',
                'tdate', 
                'ttime', 
                'tapstate', 
                'timeshift', 
                'studid'
            )
            ->orderBy('tdate', 'asc')
            ->get()
            ->groupBy(['studid', 'tdate']); // Grouped by student ID and date
        
        // Fetch remarks for multiple dates
        $employee_manual_attendance_remarks = DB::table('hr_attendanceremarks')
            ->where('deleted', 0)
            ->whereIn('employeeid', $employees->pluck('id')->toArray())
            ->whereIn('tdate', $dateArray)
            ->select(
                'id',
                'tdate', 
                'remarks',
                'employeeid'
            )
            ->orderBy('tdate', 'asc')
            ->get()
            ->groupBy(['employeeid', 'tdate']); // Grouped by employee ID and date
    
        // Fetch workdays
        $employee_workdays = DB::table('employee_workdays')
            ->where('deleted', 0)
            ->whereIn('employeeid', $employees->pluck('id')->toArray())
            ->get()
            ->groupBy('employeeid');
    
        return response()->json([
            'data' => $employees,
            'employee_manual_attendance' => $employee_manual_attendance,
            'employee_manual_attendance_remarks' => $employee_manual_attendance_remarks,
            'employee_workdays' => $employee_workdays
        ]);
    }
    

}