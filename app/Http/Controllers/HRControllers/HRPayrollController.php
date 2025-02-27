<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Crypt;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use App\MoneyCurrency;
use PDF;
use TCPDF;
use FontLib\Font;
use DateTime;
use DateInterval;
use DatePeriod;
use Conversion;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\HR\HRAllowances;
use App\Models\HR\HRDeductions;
use App\Models\HR\HREmployeeAttendance;
use App\Models\HR\HRSalaryDetails;

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'PAYSLIP', false, false, false, $reseth=true, $align='L', $autopadding=true);
        // Ln();
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
class PayrollSummary extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        // $this->Image('@'.file_get_contents('/home/xxxxxx/public_html/xxxxxxxx/uploads/logo/logo.png'),10,6,0,13);
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.$schoollogo->picurl;
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),15,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'PAYROLL', false, false, false, $reseth=true, $align='L', $autopadding=true);
        // Ln();
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-40);
        // Set font
        $preparedby = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        $preparedby->title      = strtoupper($preparedby->title);
        $preparedby->lastname   = strtoupper($preparedby->lastname);
        $preparedby->firstname  = strtoupper($preparedby->firstname);
        $preparedby->middlename = strtoupper($preparedby->middlename);
        $preparedby->suffix     = strtoupper($preparedby->suffix);

        
        $this->SetFont('helvetica', 'I', 8);
        $footertable = '<table cellpadding="2" style="font-size: 10px;text-transform: uppercase;">
                            <thead>
                                <tr>
                                    <th>Prepared by:</th>
                                    <th>Checked by:</th>
                                    <th>Approved by:</th>
                                </tr>
                            </thead>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>'.$preparedby->title.' '.$preparedby->firstname.' '.$preparedby->lastname.' '.$preparedby->suffix.'</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Payroll Clerk</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>';
        $this->writeHTML($footertable, false, true, false, true);
        // $this->SetY(8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(-10, 10, date('m/d/Y'), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}
class HRPayrollController extends Controller
{
   public function index(Request $request)
   {
        date_default_timezone_set('Asia/Manila');
        $employees = Db::table('teacher')
            ->select(
                'teacher.id as employeeid',
                'teacher.lastname',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.suffix',
                'usertype.utype',
                'usertype.utype as designation',
                // 'teacher.gender',
                'teacher.picurl'
                )
            ->join('usertype','teacher.usertypeid','=','usertype.id')
            ->where('teacher.deleted','0')
            ->where('teacher.usertypeid','!=','7')
            ->where('teacher.usertypeid','!=','9')
            ->orderBy('lastname','asc')
            ->get();

            $payrolldates = DB::table('payroll')
                    ->where('status','1')
                    ->first();

            $newpayroll = 0;
            $lastpayrolldates = (object)array();

            if($payrolldates)
            {
                $payrolldates->datefrom  = date_format(date_create($payrolldates->datefrom),"m/d/Y");
                $payrolldates->dateto    = date_format(date_create($payrolldates->dateto),"m/d/Y");

                $checkifexists = DB::table('payroll_history')
                    ->where('payrollid', $payrolldates->id)
                    ->where('deleted','0')
                    ->count();

                if($checkifexists>0)
                {
                    $newpayroll = 1;
                }

                $lastpayrolldates = DB::table('payroll')
                        ->where('id','<',$payrolldates->id)
                        ->orderByDesc('id')
                        ->first();
            }
            // return  (int)count(collect($payrolldates));
            // return collect($payrolldates)->datefrom;
            // return gettype(0);
            // return $payrolldates->datefrom;
            return view('hr.payroll.index')
                    ->with('employees', $employees)
                    ->with('payrolldates', $payrolldates)
                    ->with('newpayroll', $newpayroll)
                    ->with('lastpayrolldates', $lastpayrolldates);
   }
   public function setpayrolldate(Request $request)
   {
        date_default_timezone_set('Asia/Manila');
    //    return $request->all();
       $dateexplode = explode(' - ', $request->get('payrolldates'));
       $datefrom    = date_format(date_create($dateexplode[0]),"Y-m-d");
       $dateto      = date_format(date_create($dateexplode[1]),"Y-m-d");
       DB::table('payroll')
        ->insert([
            'datefrom'      => $datefrom,
            'dateto'        => $dateto,
            'createdby'     => auth()->user()->id,
            'createdon'     => date('Y-m-d H:i:s')
        ]);
   }
   public function newpayroll(Request $request)
   {
        date_default_timezone_set('Asia/Manila');
    //    return $request->all();
        DB::table('payroll')
            ->update([
                'status'    => 0,
                'updatedby' => auth()->user()->id,
                'updatedon' => date('Y-m-d H:i:s')
            ]);

        DB::table('payroll')
            ->insert([
                'datefrom'  => $request->get('datefrom'),
                'dateto'    => $request->get('dateto'),
                'createdby' => auth()->user()->id,
                'createdon' => date('Y-m-d H:i:s')
            ]);
        
   }
   public function changepayroll(Request $request)
   {
        date_default_timezone_set('Asia/Manila');
    //    return $request->all();
        DB::table('payroll')
            ->where('id', $request->get('payrollid'))
            ->update([
                'datefrom'  => $request->get('datefrom'),
                'dateto'    => $request->get('dateto'),
                'updatedby' => auth()->user()->id,
                'updatedon' => date('Y-m-d H:i:s')
            ]);
        
   }
   public function payrollleapyear(Request $request)
   {
       // return $request->all();
       DB::table('payroll')
           ->where('status','1')
           ->update([
               'leapyear'  => $request->get('leapyearactivation')
           ]);

       return back();
   }
   public function getsalarydetails(Request $request)
   {
        date_default_timezone_set('Asia/Manila');
       
        $payrolldates = Db::table('payroll')
            ->where('status','1')
            ->first();

        $salarydetails = HRSalaryDetails::salarydetails($request->get('employeeid'),$payrolldates);
        // return collect($salarydetails);
        // ====================================================================================================================== standard deductions

        $standarddeductionsmodel = HRDeductions::standarddeductions($salarydetails->payrollinfo->datefrom, $salarydetails->payrollinfo->id, $request->get('employeeid'));

        $standarddeductions = $standarddeductionsmodel;
        
        if(count($standarddeductionsmodel) == 0)
        {
            $standarddeductionsfullamount = 0.00;
        }else{
            $standarddeductionsfullamount = $standarddeductionsmodel[0]->fullamount;
        }
        // ====================================================================================================================== other deductions

        $otherdeductionsmodel = HRDeductions::otherdeductions($salarydetails->payrollinfo->datefrom, $salarydetails->payrollinfo->id, $request->get('employeeid'));

        $otherdeductions = $otherdeductionsmodel;
        
        if(count($otherdeductionsmodel) == 0)
        {
            $otherdeductionsfullamount = 0.00;
        }else{
            $otherdeductionsfullamount = $otherdeductionsmodel[0]->fullamount;
        }

        // ====================================================================================================================== standard allowance

        $standardallowancesmodel = HRAllowances::standardallowances($salarydetails->payrollinfo->datefrom, $salarydetails->payrollinfo->id, $request->get('employeeid'));

        $standardallowances = $standardallowancesmodel;
        
        if(count($standardallowances) == 0)
        {
            $standardallowancesfullamount = 0.00;
        }else{

            $standardallowancesfullamount = $standardallowancesmodel[0]->fullamount;

        }
        
        // ====================================================================================================================== other allowance

        $otherallowancesmodel = HRAllowances::otherallowances($salarydetails->payrollinfo->datefrom, $salarydetails->payrollinfo->id, $request->get('employeeid'));
        // return $otherallowancesmodel;
        $otherallowances = $otherallowancesmodel;
        
        if(count($otherallowancesmodel) == 0)
        {

            $otherallowancesfullamount = 0.00;

        }else{

            $otherallowancesfullamount = $otherallowancesmodel[0]->fullamount;
            
        }

        $checkifreleased = Db::table('payroll_history')
            ->where('payrollid', $payrolldates->id)
            ->where('employeeid', $request->get('employeeid'))
            ->get();
            
        // return $salarydetails->leavedetails;
        return view('hr.payroll.salarydetails')
            ->with('employeeid', $request->get('employeeid'))
            ->with('payrollinfo', $salarydetails->payrollinfo)
            ->with('picurl', $salarydetails->picurl)
            ->with('personalinfo', $salarydetails->personalinfo)
            ->with('basicsalaryinfo', $salarydetails->basicsalaryinfo)
            ->with('attendancedetails', $salarydetails->attendancedetails)
            ->with('payrollworkingdays', $salarydetails->payrollworkingdays)
            ->with('perdaysalary', $salarydetails->perdaysalary)
            ->with('leavedetails', $salarydetails->leavedetails)
            ->with('overtimedetails', $salarydetails->overtimedetails)
            ->with('permonthhalfsalary', $salarydetails->permonthhalfsalary)
            ->with('standarddeductions',$standarddeductions)
            ->with('otherdeductions',$otherdeductions)
            ->with('standardallowances',$standardallowances)
            ->with('otherallowances',$otherallowances)
            ->with('checkifreleased',count($checkifreleased));
   }
   public function saveconfiguration(Request $request)
   {
        date_default_timezone_set('Asia/Manila');
        $payrolldates = Db::table('payroll')
            ->where('status','1')
            ->first();
            
       if($request->has('standarddeductionids'))
       {
        if(count($request->get('standarddeductionids')) > 0)
        {
            foreach($request->get('standarddeductionids') as $standarddeductionkey => $standarddeductionvalue)
            {
                $checkifexists = DB::table('payroll_historydetail')
                 ->where('employeeid',$request->get('employeeid'))
                 ->where('payrollid',$payrolldates->id)
                 ->where('deductionid',$standarddeductionvalue)
                 ->where('type','standard')
                 ->where('deleted','0')
                 ->count();
 
                 if($checkifexists == 0)
                 {
                     DB::table('payroll_historydetail')
                      ->insert([
                          'employeeid'        => $request->get('employeeid'),
                          'payrollid'         => $payrolldates->id,
                          'deductionid'       => $standarddeductionvalue,
                          'deductiondesc'     => $request->get('standarddeductiondescs')[$standarddeductionkey],
                          'type'              => 'standard',
                          'paymentoption'     => $request->get('standarddeductionpaymentoptions')[$standarddeductionkey],
                          'amount'            => $request->get('standarddeductionamounts')[$standarddeductionkey],
                          'createdby'         => auth()->user()->id,
                          'createddatetime'   => date('Y-m-d H:i:s')
                      ]);
                 }else{
                     DB::table('payroll_historydetail')
                         ->where('employeeid',$request->get('employeeid'))
                         ->where('payrollid',$payrolldates->id)
                         ->where('deductionid',$standarddeductionvalue)
                         ->where('type','standard')
                         ->update([
                             'deductiondesc'     => $request->get('standarddeductiondescs')[$standarddeductionkey],
                             'type'              => 'standard',
                             'paymentoption'     => $request->get('standarddeductionpaymentoptions')[$standarddeductionkey],
                             'amount'            => $request->get('standarddeductionamounts')[$standarddeductionkey],
                             'updatedby'         => auth()->user()->id,
                             'updateddatetime'   => date('Y-m-d H:i:s')
                         ]);
                 }
            }
        }
       }
       if($request->has('otherdeductionids'))
       {
        if(count($request->get('otherdeductionids')) > 0)
        {
            foreach($request->get('otherdeductionids') as $otherdeductionkey => $otherdeductionvalue)
            {
                $checkifexists = DB::table('payroll_historydetail')
                 ->where('employeeid',$request->get('employeeid'))
                 ->where('payrollid',$payrolldates->id)
                 ->where('deductionid',$otherdeductionvalue)
                 ->where('type','other')
                 ->where('deleted','0')
                 ->count();
 
                 if($checkifexists == 0)
                 {
                     DB::table('payroll_historydetail')
                      ->insert([
                          'employeeid'        => $request->get('employeeid'),
                          'payrollid'         => $payrolldates->id,
                          'deductionid'       => $otherdeductionvalue,
                          'deductiondesc'     => $request->get('otherdeductiondescs')[$otherdeductionkey],
                          'type'              => 'other',
                          'paymentoption'     => $request->get('otherdeductionpaymentoptions')[$otherdeductionkey],
                          'amount'            => $request->get('otherdeductionamounts')[$otherdeductionkey],
                          'createdby'         => auth()->user()->id,
                          'createddatetime'   => date('Y-m-d H:i:s')
                      ]);
                 }else{
                     DB::table('payroll_historydetail')
                         ->where('employeeid',$request->get('employeeid'))
                         ->where('payrollid',$payrolldates->id)
                         ->where('deductionid',$otherdeductionvalue)
                         ->where('type','other')
                         ->update([
                             'deductiondesc'     => $request->get('otherdeductiondescs')[$otherdeductionkey],
                             'type'              => 'other',
                             'paymentoption'     => $request->get('otherdeductionpaymentoptions')[$otherdeductionkey],
                             'amount'            => $request->get('otherdeductionamounts')[$otherdeductionkey],
                             'updatedby'         => auth()->user()->id,
                             'updateddatetime'   => date('Y-m-d H:i:s')
                         ]);
                 }
            }
        }
       }
       if($request->has('standardallowanceids'))
       {
        if(count($request->get('standardallowanceids')) > 0)
        {
            foreach($request->get('standardallowanceids') as $standardallowancekey => $standardallowancevalue)
            {
                $checkifexists = DB::table('payroll_historydetail')
                 ->where('employeeid',$request->get('employeeid'))
                 ->where('payrollid',$payrolldates->id)
                 ->where('allowanceid',$standardallowancevalue)
                 ->where('type','standard')
                 ->where('deleted','0')
                 ->count();
 
                 if($checkifexists == 0)
                 {
                     DB::table('payroll_historydetail')
                      ->insert([
                          'employeeid'        => $request->get('employeeid'),
                          'payrollid'         => $payrolldates->id,
                          'allowanceid'       => $standardallowancevalue,
                          'allowancedesc'     => $request->get('standardallowancedescs')[$standardallowancekey],
                          'type'              => 'standard',
                          'paymentoption'     => $request->get('standardallowancepaymentoptions')[$standardallowancekey],
                          'amount'            => $request->get('standardallowanceamounts')[$standardallowancekey],
                          'createdby'         => auth()->user()->id,
                          'createddatetime'   => date('Y-m-d H:i:s')
                      ]);
                 }else{
                     DB::table('payroll_historydetail')
                         ->where('employeeid',$request->get('employeeid'))
                         ->where('payrollid',$payrolldates->id)
                         ->where('allowanceid',$standardallowancevalue)
                         ->where('type','standard')
                         ->update([
                             'allowancedesc'     => $request->get('standardallowancedescs')[$standardallowancekey],
                             'type'              => 'standard',
                             'paymentoption'     => $request->get('standardallowancepaymentoptions')[$standardallowancekey],
                             'amount'            => $request->get('standardallowanceamounts')[$standardallowancekey],
                             'updatedby'         => auth()->user()->id,
                             'updateddatetime'   => date('Y-m-d H:i:s')
                         ]);
                 }
            }
        }
       }
       if($request->has('otherallowanceids'))
       {
        if(count($request->get('otherallowanceids')) > 0)
        {
            foreach($request->get('otherallowanceids') as $otherallowancekey => $otherallowancevalue)
            {
                $checkifexists = DB::table('payroll_historydetail')
                 ->where('employeeid',$request->get('employeeid'))
                 ->where('payrollid',$payrolldates->id)
                 ->where('allowanceid',$otherallowancevalue)
                 ->where('type','other')
                 ->where('deleted','0')
                 ->count();
 
                 if($checkifexists == 0)
                 {
                     DB::table('payroll_historydetail')
                      ->insert([
                          'employeeid'        => $request->get('employeeid'),
                          'payrollid'         => $payrolldates->id,
                          'allowanceid'       => $otherallowancevalue,
                          'allowancedesc'     => $request->get('otherallowancedescs')[$otherallowancekey],
                          'type'              => 'other',
                          'paymentoption'     => $request->get('otherallowancepaymentoptions')[$otherallowancekey],
                          'amount'            => $request->get('otherallowanceamounts')[$otherallowancekey],
                          'createdby'         => auth()->user()->id,
                          'createddatetime'   => date('Y-m-d H:i:s')
                      ]);
                 }else{
                     DB::table('payroll_historydetail')
                         ->where('employeeid',$request->get('employeeid'))
                         ->where('payrollid',$payrolldates->id)
                         ->where('allowanceid',$otherallowancevalue)
                         ->where('type','other')
                         ->update([
                             'allowancedesc'     => $request->get('otherallowancedescs')[$otherallowancekey],
                             'type'              => 'other',
                             'paymentoption'     => $request->get('otherallowancepaymentoptions')[$otherallowancekey],
                             'amount'            => $request->get('otherallowanceamounts')[$otherallowancekey],
                             'updatedby'         => auth()->user()->id,
                             'updateddatetime'   => date('Y-m-d H:i:s')
                         ]);
                 }
            }
        }
       }
   }
   public function payrollsummary(Request $request)
   {
        $payrollactive = DB::table('payroll')
            ->where('status','1')
            ->first();
        if(!$payrollactive)
        {
            $payrollactive = (object)array(
                'id'        => 0    
            );
        }
        $payrolldates = DB::table('payroll')
            ->orderBy('dateto','asc')
            ->get();

        $basistypes = DB::table('employee_basistype')
            ->where('deleted','0')
            ->get();

        // $departments = DB::table('hr_school_department')
        //     ->where('deleted','0')
        //     ->get();
        
        $departments = DB::table('hr_departments')
            ->where('deleted','0')
            ->get();
        $employees = Db::table('teacher')
            ->select(
                'teacher.id as employeeid',
                'employee_basicsalaryinfo.salarybasistype as salarytypeid'
                )
            ->join('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('hr_school_department','usertype.departmentid','=','hr_school_department.id')
            ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
            //    ->leftJoin('employee_basistype','teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->where('teacher.deleted','0')
            ->where('teacher.usertypeid','!=','7')
            ->where('teacher.usertypeid','!=','9')
            ->orderBy('lastname','asc')
            ->get();

        if(count($employees)>0)
        {
            foreach($employees as $employee)
            {
                $checkifreleased = Db::table('payroll_history')
                    ->where('employeeid', $employee->employeeid)
                    ->where('payrollid', $payrollactive->id)
                    ->where('deleted','0')
                    ->get();
                    
                if(count($checkifreleased) > 0)
                {
                    $employee->released = 1;
                }else{
                    $employee->released = 0;
                }
                $checkifconfigured = Db::table('payroll_historydetail')
                    ->where('employeeid', $employee->employeeid)
                    ->where('payrollid', $payrollactive->id)
                    ->where('deleted','0')
                    ->get();
                if(count($checkifconfigured) > 0)
                {
                    $employee->configured = 1;
                }else{
                    $employee->configured = 0;
                }
                if($employee->salarytypeid == null )
                {
                    $employee->salaryinfo = 1; //nosalaryinfo
                }else{
                    $employee->salaryinfo = 2;
                }
            }
        }

        // return $employees;
        $payrollsetup = Db::table('payroll_setup')
            ->where('payrollid',$payrollactive->id)
            ->where('deleted','0')
            ->get();

        
       return view('hr.payroll.payrollsummary')
        ->with('payrolldates', $payrolldates)
        ->with('employees', $employees)
        ->with('departments', $departments)
        ->with('basistypes', $basistypes)
        ->with('payrollsetup', $payrollsetup);
   }
   public function setup(Request $request)
   {
       if($request->ajax())
       {
            //      1 = sd;
            //      2 = od;
            //      3 = sa;
            //      4 = oa;

            $particulars = array();

            $standarddeductions = Db::table('deduction_standard')
                    ->select('id','description')
                    ->where('deleted','0')
                    ->get();

            if(count($standarddeductions)>0)
            {
                foreach($standarddeductions as $standarddeduction)
                {
                    array_push($particulars,(object)array(
                        'id'             => $standarddeduction->id,
                        'description'    => $standarddeduction->description,
                        'type'           => 1
                    ));
                }
            }

            $otherdeductions = Db::table('employee_deductionother')
                    ->select('description')
                    ->where('deleted','0')
                    ->distinct()
                    ->get();

            if(count($otherdeductions)>0)
            {
                foreach($otherdeductions as $otherdeduction)
                {
                    array_push($particulars,(object)array(
                        'id'             => 0,
                        'description'    => $otherdeduction->description,
                        'type'           => 2
                    ));
                }
            }
                    
            $standardallowances = Db::table('allowance_standard')
                    ->select('id','description')
                    ->where('deleted','0')
                    ->get();

            if(count($standardallowances)>0)
            {
                foreach($standardallowances as $standardallowance)
                {
                    array_push($particulars,(object)array(
                        'id'             => $standardallowance->id,
                        'description'    => $standardallowance->description,
                        'type'           => 3
                    ));
                }
            }

            $otherallowances = Db::table('employee_allowanceother')
                ->select('description')
                ->where('deleted','0')
                ->distinct()
                ->get();

            if(count($otherallowances)>0)
            {
                foreach($otherallowances as $otherallowance)
                {
                    array_push($particulars,(object)array(
                        'id'             => 0,
                        'description'    => $otherallowance->description,
                        'type'           => 4
                    ));
                }
            }
            return view('hr.payroll.setup')->with('particulars',$particulars);
       }

   }
   public function setupcreate(Request $request)
   {
    date_default_timezone_set('Asia/Manila');
    //    return $request->all();

       $activepayroll = Db::table('payroll')
        ->where('status','1')
        ->first();

       if(count($request->get('particularsid')) >0)
       {
           foreach($request->get('particularsid') as $key => $value)
           {
               $checkifexists = Db::table('payroll_setup')
                ->where('payrollid', $activepayroll->id)
                ->where('particularid', $value)
                ->where('description',$request->get('particularsdesc')[$key])
                ->where('type',$request->get('particularstype')[$key])
                ->where('deleted','0')
                ->get();

                if(count($checkifexists) == 0)
                {
                    DB::table('payroll_setup')
                        ->insert([
                            'payrollid'         =>  $activepayroll->id,
                            'particularid'      =>  $value,
                            'description'       =>  $request->get('particularsdesc')[$key],
                            'type'              =>  $request->get('particularstype')[$key],
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
           }
       }

   }
   public function setupshow(Request $request)
   {
    //    return $request->all();
        $releasestatus = Db::table('payroll_history')
            ->where('payrollid', $request->get('selectedpayrollid'))
            ->get();

        if(count($releasestatus)==0)
        {
            $releasestatus = 0;
        }else{
            $releasestatus = 1;
        }
       $particulars = Db::table('payroll_setup')
        ->select('id','particularid','description','type')
        ->where('payrollid', $request->get('selectedpayrollid'))
        ->where('deleted','0')
        ->get();

        return view('hr.payroll.setup_show')->with('particulars',$particulars)->with('releasestatus',$releasestatus);
   }
   public function setupdelete(Request $request)
   {
        date_default_timezone_set('Asia/Manila');
        //    return $request->all();
        DB::table('payroll_setup')
            ->where('payrollid',$request->get('selectedpayrollid'))
            ->update([
                'deleted'           => 1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    //    $particulars = Db::table('payroll_setup')
    //     ->select('id','particularid','description','type')
    //     ->where('payrollid', $request->get('selectedpayrollid'))
    //     ->where('deleted','0')
    //     ->get();

    //     return view('hr.payroll.setup_show')->with('particulars',$particulars);
   }
   public function filterpayrollsummary(Request $request)
   {
    //    if($request->ajax())
    //    {
            $payrolldates = Db::table('payroll')
                ->where('id',$request->get('selectedpayrollid'))
                ->first();
                
            $salarytype = 0;
            
            $employees = Db::table('teacher')
            ->select(
                'teacher.id as employeeid',
                'teacher.lastname',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.suffix',
                'usertype.utype',
                'teacher.employmentstatus',
                'teacher.picurl',
                'teacher.schooldeptid as departmentid',
                'employee_basicsalaryinfo.salarybasistype as salarytypeid'
                )
            ->join('usertype','teacher.usertypeid','=','usertype.id')
            ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->where('teacher.deleted','0')
            ->where('teacher.usertypeid','!=','7')
            ->where('teacher.usertypeid','!=','9')
            ->orderBy('lastname','asc')
            ->get();
            if($request->get('selecteddepartmentid')!= null)
            {
                $employees = collect($employees->where('departmentid', $request->get('selecteddepartmentid')))->values()->all();
            }

            if($request->get('selectedemploymentstatusid')!= null)
            {
                $employees = collect($employees)->where('employmentstatus', $request->get('selectedemploymentstatusid'))->values()->all();
            }

            if($request->get('selectedsalarytypeid')!= null)
            {
                $employees = collect($employees)->where('salarytypeid', $request->get('selectedsalarytypeid'))->values()->all();
                $salarytype = DB::table('employee_basistype')
                    ->where('id', $request->get('selectedsalarytypeid'))
                    ->first();
                    
                if($salarytype)
                {
                    $salarytype = $salarytype->type;
                }else{
                    $salarytype = null;

                }
            }
            // return $salarytype;
            if($salarytype!=null)
            {
                if(count($employees)>0)
                {
                    foreach($employees as $employee)
                    {
                        $employee->released = 0;
                        $checkifconfigured = DB::table('payroll_historydetail')
                            ->where('payrollid', $payrolldates->id)
                            ->where('employeeid', $employee->employeeid)
                            ->where('deleted','0')
                            ->get();
    
                        if(count($checkifconfigured)>0)
                        {
    
                            $employee->configured = 1;
    
                        }else{
    
                            $employee->configured = 0;
    
                        }
                        
                        $checkifreleased = DB::table('payroll_history')
                            ->where('payrollid', $payrolldates->id)
                            ->where('employeeid', $employee->employeeid)
                            ->where('deleted','0')
                            ->get();
    
                        if(count($checkifreleased)>0)
                        {
    
                            $employee->released = 1;
    
                        }
                        
                        $salarydetails = HRSalaryDetails::salarydetails($employee->employeeid,$payrolldates);
                        
                        // ====================================================================================================================== standard deductions
    
                        $standarddeductionsmodel = HRDeductions::standarddeductions($payrolldates->datefrom, $payrolldates->id,$employee->employeeid);
                        
                        $standarddeductions = $standarddeductionsmodel;
    
                        if(count($standarddeductionsmodel) == 0)
                        {
    
                            $standarddeductionsfullamount = 0.00;
    
                        }else{
    
                            $standarddeductionsfullamount = $standarddeductionsmodel[0]->fullamount;
    
                        };
                        // ====================================================================================================================== other deductions
    
                        $otherdeductionsmodel = HRDeductions::otherdeductions($payrolldates->datefrom, $payrolldates->id, $employee->employeeid);
                        // return $otherdeductionsmodel;
                        
                        $otherdeductions = $otherdeductionsmodel;
    
                        if(count($otherdeductionsmodel) == 0)
                        {
    
                            $otherdeductionsfullamount = 0.00;
    
                        }else{
    
                            $otherdeductionsfullamount = $otherdeductionsmodel[0]->fullamount;
    
                        }
    
                        // ====================================================================================================================== standard allowance
    
                        $standardallowancesmodel = HRAllowances::standardallowances($payrolldates->datefrom, $payrolldates->id, $employee->employeeid);
    
                        $standardallowances = $standardallowancesmodel;
                        
                        if(count($standardallowances) == 0)
                        {
    
                            $standardallowancesfullamount = 0.00;
    
                        }else{
    
                            $standardallowancesfullamount = $standardallowancesmodel[0]->fullamount;
    
                        }
                        
                        // ====================================================================================================================== other allowance
    
                        $otherallowancesmodel = HRAllowances::otherallowances($payrolldates->datefrom, $payrolldates->id, $employee->employeeid);
                        $otherallowances = $otherallowancesmodel;
                        if(count($otherallowancesmodel) == 0)
                        {
                            $otherallowancesfullamount = 0.00;
                        }else{
                            $otherallowancesfullamount = $otherallowancesmodel[0]->fullamount;
                        }
                        $salarydetails->standarddeductions  = $standarddeductions;
                        $salarydetails->otherdeductions = $otherdeductions;
                        $salarydetails->standardallowances  = $standardallowances;
                        $salarydetails->otherallowances = $otherallowances;
                        
                        $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
                            ->where('employeeid', $employee->employeeid)
                            ->first();
                        $ratetype = DB::table('employee_basistype')
                            ->where('id', $basicsalaryinfo->salarybasistype)
                            ->first()->type;
    
                            $basicsalaryinfo->salarytype = $ratetype;
    
                        $salarydetails->basicsalary   = self::getbasicsalary($payrolldates,$basicsalaryinfo);;
    
                        // return $salarydetails->basicsalaryinfo;
                            
                        // return collect($salarydetails)->forget(['payrollinfo','picurl','personalinfo'])->basicsalaryinfo;
                        unset($salarydetails->payrollinfo);
                        unset($salarydetails->picurl);
                        unset($salarydetails->personalinfo);
                        // unset($salarydetails["payrollinfo"]); 
                        // return collect($salarydetails);
                        $totalleavesearn = 0;
                        if(count($salarydetails->leavedetails)>0)
                        {
                            foreach($salarydetails->leavedetails as $leavedetail)
                            {
                                $totalleavesearn+=$leavedetail->amount;
                                // $totalleavesearn+=$leavedetail->amountearn;
                            }
                        }
                        $totalovertimesearn = 0;
                        if(count($salarydetails->overtimedetails)>0)
                        {
                            foreach($salarydetails->overtimedetails as $overtimedetail)
                            {
                                $totalovertimesearn+=$overtimedetail->amount;
                                // $totalleavesearn+=$leavedetail->amountearn;
                            }
                        }
                        // return $salarydetails->attendancedetails->attendanceearnings;
                        // return ($salarydetails->attendancedetails->attendancedeductions)+($salarydetails->attendancedetails->latedeductionamount);
                        // return collect($salarydetails->attendancedetails->attendancepresent);
    
                        
                        $employee->salarydetails        = $salarydetails;
                        $employee->absencesandtardiness = $salarydetails->attendancedetails->attendancedeductions + $salarydetails->attendancedetails->latedeductionamount;
                        $employee->totaldeductions      = $standarddeductionsfullamount+(float)$otherdeductionsfullamount;
                        $employee->totalallowances      = $standardallowancesfullamount+(float)$otherallowancesfullamount;
                        $employee->grosssalarypay       = $salarydetails->attendancedetails->attendanceearnings+$totalleavesearn;
                        $employee->netpay               = ($employee->totalallowances+$salarydetails->attendancedetails->attendanceearnings+$totalleavesearn+$totalovertimesearn)-($employee->totaldeductions+$salarydetails->attendancedetails->latedeductionamount);

                        
                        $employee->payrollhistory       = DB::table('payroll_history')
                                                            ->where('employeeid', $employee->employeeid)
                                                            ->where('payrollid', $payrolldates->id)
                                                            ->where('deleted','0')
                                                            ->get();
                                                            
                        $employee->payrollhistorydetail = DB::table('payroll_historydetail')
                                                            ->where('employeeid', $employee->employeeid)
                                                            ->where('payrollid', $payrolldates->id)
                                                            ->where('deleted','0')
                                                            ->get();
                    }
                }
    
            }
            $standarddeductions = Db::table('deduction_standard')
                ->where('deleted','0')
                ->get();
            
            $released = count(collect($employees)->where('released',1));
            if($released == count($employees))
            {
                $print = 1;
            }else{
                $print = 0;
            }
            if(strtolower($salarytype) == 'hourly')
            {
                return view('hr.payroll.filteredemployees.hourly')
                ->with('print', $print)
                ->with('employees', $employees)
                ->with('standarddeductions', $standarddeductions);
            }else{
                // return $employees;
                return view('hr.payroll.filteredemployees.monthly')
                ->with('print', $print)
                ->with('employees', $employees)
                ->with('standarddeductions', $standarddeductions);
            }
            // }else{

            //     // return $request->all();
            //     // return $employees;

            //     if(count($employees)>0)
            //     {
            //         foreach($employees as $employee)
            //         {
            //             $payrollhistory = DB::table('payroll_history')
            //                 ->where('employeeid', $employee->employeeid)
            //                 ->where('payrollid', $request->get('selectedpayrollid'))
            //                 ->where('deleted','0')
            //                 ->get();
                            
            //             $payrollhistorydetails = DB::table('payroll_historydetail')
            //                 ->where('employeeid', $employee->employeeid)
            //                 ->where('payrollid', $request->get('selsectedpayrollid'))
            //                 ->where('deleted','0')
            //                 ->get();

            //             $employee->payrollinfo = $payrollhistory;
            //             $employee->payrollinfodetails = $payrollhistorydetails;
            //         }
            //     }

            //     // return $employees;
            //     return view('hr.payroll.filteredemployees.payrollhistory')
            //         ->with('employees', $employees);

            // }
    //    }

   }
   public function releaseslipsingle(Request $request)
   {
        date_default_timezone_set('Asia/Manila');
    //    return $request->all();

        $exporttype          = $request->get('exporttype');
        $employeeid          = $request->get('employeeid');
        $payrollid           = $request->get('payrollid');
        $payrolldates        = DB::table('payroll')
                                ->where('id', $payrollid)
                                ->first();
                                

        $payrolldatefrom     = $payrolldates->datefrom;
        $payrolldateto       = $payrolldates->dateto;

        $basicsalaryinfo     = DB::table('employee_basicsalaryinfo')
                                ->select('employee_basicsalaryinfo.*','employee_basistype.type as salarytype')
                                ->leftJoin('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                                ->where('employee_basicsalaryinfo.employeeid', $employeeid)
                                ->first();
        $basicpay            =  self::getbasicsalary($payrolldates,$basicsalaryinfo);;
        $ratetype            =  $basicsalaryinfo->salarytype;
        $projectbasedtype    =  $basicsalaryinfo->projectbasedtype;
        $dayspresent         =  $request->get('dayspresent');
        $dayspresentamount   =  $request->get('dayspresentamount');
        $daysabsent          =  $request->get('daysabsent');
        $daysabsentamount    =  $request->get('daysabsentamount');
        $lateminutes         =  $request->get('lateminutes');
        $lateamount          =  $request->get('lateamount');
        $undertimeminutes    =  $request->get('undertimeminutes');
        $undertimeamount     =  $request->get('undertimeamount');
        $holidaypay          =  $request->get('holidaypay');
        $overtimepay         =  0;
        $holidayovertimepay  =  0;

        $hoursrendered       = $request->get('hoursrendered');
        $grosssalarypay      = $request->get('grosssalarypay');

        $leaves             = json_decode($request->get('leaves'));
        if($request->has('leaves'))
        {
            if(count($leaves) > 0)
            {
                foreach($leaves as $leave)
                {
                    $checkifexists = DB::table('payroll_historydetail')
                        ->where('employeeid',$employeeid)
                        ->where('payrollid',$payrollid)
                        ->where('employeeleaveid',$leave->ldateid)
                        ->where('deleted','0')
                        ->count();
                    if($checkifexists==0)
                    {
                        DB::table('payroll_historydetail')
                            ->insert([
                                'employeeid'        => $employeeid,
                                'payrollid'         => $payrollid,
                                'employeeleaveid'   => $leave->ldateid,
                                'days'              => 1,
                                'amount'            => $leave->amount
                            ]);
    
                        DB::table('employee_leavesdetail')
                            // ->where('employeeid',$employeeid)
                            ->where('id', $leave->ldateid)
                            ->update([
                                'payrolldone'   => 1
                            ]);
                    }
                }
            }
        }

        $overtimes          = json_decode($request->get('overtimes'));
        
        if($request->has('overtimes'))
        {
            if(count($overtimes) > 0)
            {
                foreach($overtimes as $overtime)
                {
                    $checkifexists = DB::table('payroll_historydetail')
                        ->where('employeeid',$employeeid)
                        ->where('payrollid',$payrollid)
                        ->where('employeeovertimeid',$overtime->overtimeid)
                        ->where('deleted','0')
                        ->count();

                    if($checkifexists==0)
                    {
                        if($overtime->holiday == 1)
                        {
                            $holidayovertimepay += $overtime->amount;
                        }else{
                            $overtimepay += $overtime->amount;
                        }
        

                        DB::table('payroll_historydetail')
                            ->insert([
                                'employeeid'            => $employeeid,
                                'payrollid'             => $payrollid,
                                'employeeovertimeid'    => $overtime->overtimeid,
                                'overtimehours'         => $overtime->numofhours,
                                'amount'                => $overtime->amount
                            ]);
    
                        DB::table('employee_overtime')
                            // ->where('employeeid',$employeeid)
                            ->where('id', $overtime->overtimeid)
                            ->update([
                                'payrolldone'   => 1
                            ]);
                    }
                }
            }
        }

        $deductionsamount = 0;
        
        $deductions = DB::table('payroll_historydetail')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $employeeid)
            ->where('deductionid','!=',0)
            ->where('deleted','0')
            ->get();
            
        if(count($deductions)>0)
        {
            foreach($deductions as $deduction)
            {
                $deductionsamount+=$deduction->amount;

                if($deduction->type == 'other')
                {
                    $originalamount = DB::table('employee_deductionother')
                        ->where('id', $deduction->deductionid)
                        ->first()
                        ->amount;


                    $totalpaid = DB::table('payroll_historydetail')
                        // ->where('payrollid', $payrollid)
                        ->where('employeeid', $employeeid)
                        ->where('type','other')
                        ->where('deductionid','>',0)
                        ->sum('amount');
                    // return $totalpaid;
                    if($totalpaid>=$originalamount)
                    {
                        DB::table('employee_deductionother')
                        ->where('id', $deduction->deductionid)
                        ->update([
                            'paid'  => 1
                        ]);
                    }
                }
                
            }
        }

        $allowancesamount = 0;

        $allowances = DB::table('payroll_historydetail')
            ->where('payrollid', $payrollid)
            ->where('employeeid', $employeeid)
            ->where('allowanceid','>',0)
            ->where('deleted','0')
            ->get();


        if(count($allowances)>0)
        {
            foreach($allowances as $allowance)
            {
                $allowancesamount+=$allowance->amount;
                if($allowance->type == 'other')
                {
                    $originalamount = DB::table('employee_allowanceother')
                        ->where('id', $allowance->allowanceid)
                        ->first()
                        ->amount;


                    $totalpaid = DB::table('payroll_historydetail')
                        // ->where('payrollid', $payrollid)
                        ->where('employeeid', $employeeid)
                        ->where('type','other')
                        ->where('allowanceid','>',0)
                        ->sum('amount');
                    // return $totalpaid;
                    if($totalpaid>=$originalamount)
                    {
                        DB::table('employee_allowanceother')
                        ->where('id', $allowance->allowanceid)
                        ->update([
                            'paid'  => 1
                        ]);
                    }
                }
            }
        }


        $totalearnings       =  $dayspresentamount+$holidaypay+$overtimepay+$holidayovertimepay+collect($leaves)->sum('amount')+$allowancesamount;
        // $totaldeductions     =  $lateamount+$deductionsamount;
        $totaldeductions     =  $deductionsamount;
        
        $netpay              =  $totalearnings-($totaldeductions+$lateamount);

        $checkifexists = DB::table('payroll_history')
            ->where('employeeid',$employeeid)
            ->where('payrollid',$payrollid)
            ->where('deleted','0')
            ->get();

        if(count($checkifexists) == 0)
        {
            DB::table('payroll_history')
                ->insert([
                    'employeeid'            =>$employeeid,
                    'payrollid'             =>$payrollid,
                    'payrolldatefrom'       =>$payrolldatefrom,
                    'payrolldateto'         =>$payrolldateto,
                    'basicpay'              =>$basicpay,
                    'ratetype'              =>$ratetype,
                    'projectbasedtype'      =>$projectbasedtype,
                    'hoursrendered'         =>$hoursrendered,
                    'dayspresent'           =>$dayspresent,
                    'dayspresentamount'     =>$dayspresentamount,
                    'daysabsent'            =>$daysabsent,
                    'daysabsentamount'      =>$daysabsentamount,
                    'lateminutes'           =>$lateminutes,
                    'lateamount'            =>$lateamount,
                    'undertimeminutes'      =>$undertimeminutes,
                    'undertimeamount'       =>$undertimeamount,
                    'holidaypay'            =>$holidaypay,
                    'overtimepay'           =>$overtimepay,
                    'holidayovertimepay'    =>$holidayovertimepay,
                    'grosssalarypay'        =>$grosssalarypay,
                    'totalearnings'         =>$totalearnings,
                    'totaldeductions'       =>$totaldeductions,
                    'netpay'                =>$netpay,
                    'createdby'             =>auth()->user()->id,
                    'createddatetime'       =>date('Y-m-d H:i:s')
                ]);
        }

        return  self::generateslip($employeeid,$payrollid,$exporttype,'single');

   }

   public static function generateslip($employeeid,$payrollid,$exporttype,$exportclass)
   {
        date_default_timezone_set('Asia/Manila');
        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region'
            )
            ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
            ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();
        $employeeinfo = Db::table('teacher')
            ->where('id', $employeeid)
            ->first();
            
       if($exportclass == 'single')
       {

            $payrolldetails = Db::table('payroll_history')
                ->where('payrollid', $payrollid)
                ->where('employeeid', $employeeid)
                ->where('deleted','0')
                ->first();

            $payrolldetails->otherdetails = Db::table('payroll_historydetail')
                ->where('payrollid', $payrollid)
                ->where('employeeid', $employeeid)
                ->where('deleted','0')
                ->get();

            if($exporttype == 'pdf')
            {
            
                $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                // set document information
                $pdf->SetCreator('CK');
                $pdf->SetAuthor('CK Children\'s Publishing');
                $pdf->SetTitle($schoolinfo->schoolname.' - PAYSLIP');
                $pdf->SetSubject('PAYSLIP');
                
                // set header and footer fonts
                // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                
                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                
                // set margins
                // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetMargins(5, 0, 5, true);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                
                // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                
                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                
                // set some language-dependent strings (optional)
                if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
                }
                
                // ---------------------------------------------------------
                
                // set font
                $pdf->SetFont('dejavusans', '', 10);
                
                
                // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                // Print a table
                
                // add a page
                $pdf->AddPage();
                    // return collect($payrolldetails);
                $payrolldetails->payrolldatefrom    = date('M d, Y', strtotime($payrolldetails->payrolldatefrom));
                $payrolldetails->payrolldateto      = date('M d, Y', strtotime($payrolldetails->payrolldateto));
                // return collect($employeeinfo);
                set_time_limit(3000);
                $view = \View::make('hr/payroll/payslip/pdf_single',compact('payrolldetails','employeeinfo','schoolinfo'));
                $html = $view->render();
                $pdf->writeHTML($html, true, false, true, false, '');
                // $pdf->writeHTML(view('registrar/pdf/pdf_numberofenrollees')->compact('enrollees','schoolinfo','from','to')->render());
                
                // $pdf->lastPage();
                
                // ---------------------------------------------------------
                //Close and output PDF document
                $pdf->Output('Payslip.pdf', 'I');
            }
            elseif($exporttype == 'excel')
            {
                $payrolldetails->payrolldatefrom    = date('M d, Y', strtotime($payrolldetails->payrolldatefrom));
                $payrolldetails->payrolldateto      = date('M d, Y', strtotime($payrolldetails->payrolldateto));
                
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $center = ['alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]];
                
                $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal('center');
                
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $schoolinfo->schoolname);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->mergeCells('A2:E2');
                $sheet->setCellValue('A2', $schoolinfo->address);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->mergeCells('A3:E3');
                $sheet->setCellValue('A3', 'PAYSLIP');
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->mergeCells('A4:E4');
                $sheet->setCellValue('A4', $payrolldetails->payrolldatefrom.' - '.$payrolldetails->payrolldateto);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A5:E5');
                $sheet->setCellValue('A5', strtoupper($employeeinfo->lastname.', '.$employeeinfo->firstname.' '.$employeeinfo->middlename[0].'. '.$employeeinfo->suffix.'.'));

                $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A7:B7');
                $sheet->setCellValue('A7', 'Employee ID');
                $sheet->setCellValue('C7', ': '.$employeeinfo->tid);

                $sheet->mergeCells('A9:B9');
                $sheet->setCellValue('A9', 'Particulars');
                $sheet->getStyle('A9:E9')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('A9:E9')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $sheet->setCellValue('C9', 'Earnings');
                $sheet->setCellValue('E9', 'Deductions');

                $sheet->mergeCells('A10:E10');
                $sheet->setCellValue('A10', 'Taxable earnings - Basic');

                $sheet->mergeCells('A11:B11');
                $sheet->setCellValue('A11', '         Basic Salary');
                $sheet->setCellValue('C11', number_format($payrolldetails->dayspresentamount,2,'.',','));

                $sheet->setCellValue('A12', ' Regular Deductions');

                $startcell = 13;

                
                foreach($payrolldetails->otherdetails as $standarddeduction)
                {
                    if($standarddeduction->type == 'standard' && $standarddeduction->deductionid > 0)
                    {
                        $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                        $sheet->setCellValue('A'.$startcell, '         '.$standarddeduction->deductiondesc);
                        $sheet->setCellValue('E'.$startcell, number_format($standarddeduction->amount,2,'.',','));

                        $startcell+=1;
                    }
                }
                
                $sheet->mergeCells('A'.$startcell.':E'.$startcell);
                $sheet->setCellValue('A'.$startcell, ' Other Deductions');

                $startcell+=1;

                foreach($payrolldetails->otherdetails as $otherdeduction)
                {
                    if($otherdeduction->type == 'other' && $otherdeduction->deductionid > 0)
                    {
                        $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                        $sheet->setCellValue('A'.$startcell, '         '.$otherdeduction->deductiondesc);
                        $sheet->setCellValue('E'.$startcell, number_format($otherdeduction->amount,2,'.',','));

                        $startcell+=1;
                    }
                }
                
                if($payrolldetails->lateamount > 0)
                {
                    $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                    $sheet->setCellValue('A'.$startcell, '         Tardiness');
                    $sheet->setCellValue('E'.$startcell, number_format($payrolldetails->lateamount,2,'.',','));

                    $startcell+=1;

                }
                
                $sheet->mergeCells('A'.$startcell.':E'.$startcell);
                $sheet->setCellValue('A'.$startcell, '  Non Taxable De minimis');

                $startcell+=1;

                foreach($payrolldetails->otherdetails as $standardallowance)
                {
                    if($standardallowance->type == 'standard' && $standardallowance->allowanceid > 0)
                    {

                        $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                        $sheet->setCellValue('A'.$startcell, '         '.$standardallowance->allowancedesc);
                        $sheet->setCellValue('C'.$startcell, number_format($standardallowance->amount,2,'.',','));
    
                        $startcell+=1;
                    }
                }
                foreach($payrolldetails->otherdetails as $otherallowance)
                {
                    if($otherallowance->type == 'other' && $otherallowance->allowanceid > 0)
                    {

                        $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                        $sheet->setCellValue('A'.$startcell, '         '.$otherallowance->allowancedesc);
                        $sheet->setCellValue('C'.$startcell, number_format($otherallowance->amount,2,'.',','));
    
                        $startcell+=1;
                    }
                }
                foreach($payrolldetails->otherdetails as $leave)
                {
                    if($leave->employeeleaveid > 0)
                    {
                        $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                        $sheet->setCellValue('A'.$startcell, '         Leave(s)');
                        $sheet->setCellValue('C'.$startcell, number_format($leave->amount,2,'.',','));
    
                        $startcell+=1;
                    }
                }
                if($payrolldetails->overtimepay > 0)
                {
                    $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                    $sheet->setCellValue('A'.$startcell, '         Overtime');
                    $sheet->setCellValue('C'.$startcell, number_format($payrolldetails->overtimepay,2,'.',','));

                    $startcell+=1;
                }
                if($payrolldetails->holidaypay > 0)
                {
                    $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                    $sheet->setCellValue('A'.$startcell, '         Holiday(s)');
                    $sheet->setCellValue('C'.$startcell, number_format($payrolldetails->holidaypay,2,'.',','));

                    $startcell+=1;
                }
                if($payrolldetails->holidayovertimepay > 0)
                {
                    $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                    $sheet->setCellValue('A'.$startcell, '         Holiday (Overtime)');
                    $sheet->setCellValue('C'.$startcell, number_format($payrolldetails->holidayovertimepay,2,'.',','));

                    $startcell+=1;
                }

                
                $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                $sheet->setCellValue('A'.$startcell, 'Total :');
                $sheet->getStyle('A'.$startcell.':E'.$startcell)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('A'.$startcell.':E'.$startcell)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->setCellValue('C'.$startcell, number_format($payrolldetails->totalearnings,2,'.',','));
                
                $sheet->setCellValue('E'.$startcell, number_format($payrolldetails->totaldeductions+$payrolldetails->lateamount,2,'.',','));

                $startcell+=1;
                
                $sheet->mergeCells('A'.$startcell.':B'.$startcell);
                $sheet->setCellValue('A'.$startcell, 'Net Pay:');
                $sheet->getStyle('A'.$startcell.':E'.$startcell)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('A'.$startcell.':E'.$startcell)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->setCellValue('C'.$startcell, number_format($payrolldetails->netpay,2,'.',','));
                
                $startcell+=2;
                
                $sheet->getStyle('A'.$startcell)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('C'.$startcell)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('E'.$startcell)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            

                $startcell+=1;
                $sheet->setCellValue('A'.$startcell, 'Prepared By:');
                $sheet->setCellValue('C'.$startcell, 'Recieved By:');
                $sheet->setCellValue('E'.$startcell, 'Date');
                
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="PAYSLIP.xlsx"');
                $writer->save("php://output");
            }
       }
   }
   public function viewslip(Request $request)
   {
    //    return $request->all();
        return  self::generateslip($request->get('employeeid'),$request->get('payrollid'),$request->get('exporttype'),$request->get('exportclass'));
   }
   public function exportsummary(Request $request)
   {
        date_default_timezone_set('Asia/Manila');
    //    return $request->all();
       $salarytype = DB::table('employee_basistype')
        ->where('id', $request->get('selectedsalarytypeid'))
        ->first();
        if($salarytype)
        {
            $salarytype = $salarytype->type;
       
            $schoolinfo = Db::table('schoolinfo')
             ->select(
                 'schoolinfo.schoolid',
                 'schoolinfo.schoolname',
                 'schoolinfo.authorized',
                 'refcitymun.citymunDesc as division',
                 'schoolinfo.district',
                 'schoolinfo.address',
                 'schoolinfo.picurl',
                 'refregion.regDesc as region'
             )
             ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
             ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
             ->first();
     
             $payrolldates = DB::table('payroll')
                 ->where('id',$request->get('selectedpayrollid'))
                 ->first();
     
             $payroll_history = DB::table('payroll_history')
                 ->where('payrollid', $request->get('selectedpayrollid'))
                 ->where('deleted',0)
                 ->get();
     
             if(count($payroll_history)>0)
             {
                 foreach($payroll_history as $history)
                 {
                     // $employeeinfo = DB::table('teacher')
                     //     ->select('firstname','lastname','middlename','suffix','usertypeid')
                     //     ->where('id', $history->employeeid)
                     //     ->first();
                     $employeeinfo = Db::table('teacher')
                         ->select(
                             'teacher.id as employeeid',
                             'teacher.lastname',
                             'teacher.firstname',
                             'teacher.middlename',
                             'teacher.suffix',
                             'usertype.utype',
                             'usertype.id as usertypeid',
                            'employee_basistype.type as ratetype',
                             'teacher.employmentstatus',
                             'teacher.picurl',
                             'hr_school_department.id as departmentid',
                             'employee_basicsalaryinfo.salarybasistype as salarytypeid'
                             )
                         ->join('usertype','teacher.usertypeid','=','usertype.id')
                         ->leftJoin('hr_school_department','usertype.departmentid','=','hr_school_department.id')
                         ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                        ->leftJoin('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                         ->where('teacher.deleted','0')
                         ->where('teacher.usertypeid','!=','7')
                         ->where('teacher.usertypeid','!=','9')
                         ->orderBy('lastname','asc')
                         ->where('teacher.id', $history->employeeid)
                         // ->where('employee_basicsalaryinfo.salarybasistype', $request->get('selectedsalarytypeid'))
                         ->first();
     
                     // return collect($employeeinfo);
                     $history->firstname     = strtoupper($employeeinfo->firstname);
                     $history->lastname      = strtoupper($employeeinfo->lastname);
                     $history->middlename    = strtoupper($employeeinfo->middlename);
                     $history->suffix        = strtoupper($employeeinfo->suffix);
                     $history->salarybasistype        = strtoupper($employeeinfo->salarytypeid);
     
                     $basicsalaryinfo = DB::table('employee_basicsalaryinfo')
                         ->where('employeeid', $history->employeeid)
                         ->first();
     
                     $basicsalaryinfo->salarytype = $history->ratetype;
                         
                     $history->basicsalary   = self::getbasicsalary($payrolldates,$basicsalaryinfo);;
     
                     $historydetail = array();
     
                     $history->historydetails = DB::table('payroll_historydetail')
                         ->where('payrollid', $request->get('selectedpayrollid'))
                         ->where('employeeid', $history->employeeid)
                         ->where('deleted',0)
                         ->get();
                         
                     $employeeworkdaysinaweek = 0;
                 
                     $payrollworkingdays = array();
             
                     $begin = new DateTime($payrolldates->datefrom);
             
                     $end = new DateTime($payrolldates->dateto);
             
                     $end = $end->modify( '+1 day' ); 
                     
                     $intervalday = new DateInterval('P1D');
             
                     $daterange = new DatePeriod($begin, $intervalday ,$end);
             
                     $totalhours = 0;
                     foreach($daterange as $date){
                         
                         if(strtolower($date->format("D")) == 'mon' && $basicsalaryinfo->mondays == 1)
                         {
                             array_push($payrollworkingdays,$date->format("Y-m-d"));
                             $employeeworkdaysinaweek+=1;
                             if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                             {
                                 $totalhours+=$basicsalaryinfo->mondayhours;
                             }
                         }
                         elseif(strtolower($date->format("D")) == 'tue' && $basicsalaryinfo->tuesdays == 1)
                         {
                             array_push($payrollworkingdays,$date->format("Y-m-d"));
                             $employeeworkdaysinaweek+=1;
                             if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                             {
                                 $totalhours+=$basicsalaryinfo->tuesdayhours;
                             }
                         }
                         elseif(strtolower($date->format("D")) == 'wed' && $basicsalaryinfo->wednesdays == 1)
                         {
                             array_push($payrollworkingdays,$date->format("Y-m-d"));
                             $employeeworkdaysinaweek+=1;
                             if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                             {
                                 $totalhours+=$basicsalaryinfo->wednesdayhours;
                             }
                         }
                         elseif(strtolower($date->format("D")) == 'thu' && $basicsalaryinfo->thursdays == 1)
                         {
                             array_push($payrollworkingdays,$date->format("Y-m-d"));
                             $employeeworkdaysinaweek+=1;
                             if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                             {
                                 $totalhours+=$basicsalaryinfo->thursdayhours;
                             }
                         }
                         elseif(strtolower($date->format("D")) == 'fri' && $basicsalaryinfo->fridays == 1)
                         {
                             array_push($payrollworkingdays,$date->format("Y-m-d"));
                             $employeeworkdaysinaweek+=1;
                             if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                             {
                                 $totalhours+=$basicsalaryinfo->fridayhours;
                             }
                         }
                         elseif(strtolower($date->format("D")) == 'sat' && $basicsalaryinfo->saturdays == 1)
                         {
                             array_push($payrollworkingdays,$date->format("Y-m-d"));
                             $employeeworkdaysinaweek+=1;
                             if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                             {
                                 $totalhours+=$basicsalaryinfo->saturdayhours;
                             }
                         }
                         elseif(strtolower($date->format("D")) == 'sun' && $basicsalaryinfo->sundays == 1)
                         {
                             array_push($payrollworkingdays,$date->format("Y-m-d"));
                             $employeeworkdaysinaweek+=1;
                             if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                             {
                                 $totalhours+=$basicsalaryinfo->sundayhours;
                             }
                         }
             
                     }
                     
                     $attendancepresent      = array();
                     $attendanceabsent       = array();
                     $attendanceunchecked    = array();
                     $attendanceabsentdeductions = 0;
     
                     $holidays               = array();
     
                     
                     $latedeductionamount    = 0;
     
                     $lateminutes            = 0;
     
                     $presentminutes         = 0;
                     $absentminutes          = 0;
     
                     $undertimeminutes       = 0;
                     
                     $holidaypay             = 0;
     
                     $dailynumofhours        = 0;
                     $minuteslate            = 0;
                     $minuteslatehalfday     = 0;
     
                     $lateamin               = 0;
                     $undertimeamout         = 0;
                     $latepmin               = 0;
                     $undertimepmout         = 0;
                     $undertimepmout         = 0;
     
                     $hoursrendered          = 0;
                     $presentdaysamount      = 0;
     
                     if(count($payrollworkingdays) > 0)
                     {
                         foreach($payrollworkingdays as $workingday)
                         {
                             $employeeinfo->employeeid = $history->employeeid;
                             $attendance = HREmployeeAttendance::payrollattendancev2($workingday,$employeeinfo,$basicsalaryinfo->amount,$basicsalaryinfo);
                             // return collect($attendance);
                             // return $attendance;
                             // $latedeductionamount    += $attendance->latedeductionamount;
                     
                             // $lateminutes            += $attendance->lateminutes;
                     
                             // $presentminutes         += $attendance->presentminutes;
                     
                             // $undertimeminutes       += $attendance->undertimeminutes;
                             
                             // $holidaypay             += $attendance->holidaypay;
                     
                             // $dailynumofhours        += $attendance->dailynumofhours;
     
                             // $lateamin               += $attendance->lateamin;
                             // $undertimeamout         += $attendance->undertimeamout;
                             // $latepmin               += $attendance->latepmin;
                             // $undertimepmout         += $attendance->undertimepmout;
                     
                             // $absentdeduction        += $attendance->absentdeduction;
                             // return count(collect($attendance));
                             if($workingday>date('Y-m-d'))
                             {
                                 // array_push($attendanceunchecked, $workingday);
                             }else{
                                 if($attendance->status == 1)
                                 {
                                     $latedeductionamount    += $attendance->latedeductionamount;
                             
                                     $lateminutes            += $attendance->lateminutes;
                             
                                     $presentminutes         += $attendance->presentminutes;
                             
                                     $undertimeminutes       += $attendance->undertimeminutes;
                                     
                                     // $holidaypay             += $attendance->holidaypay;
                             
                                     $dailynumofhours        += $attendance->dailynumofhours;
                     
                                     $lateamin               += $attendance->lateamin;
                                     $undertimeamout         += $attendance->undertimeamout;
                                     $latepmin               += $attendance->latepmin;
                                     $undertimepmout         += $attendance->undertimepmout;
                                     $hoursrendered         += $attendance->hoursrendered;
                                     $presentdaysamount         += $attendance->presentdaysamount;
                                     
                                     // array_push($attendancepresent, $payrollworkingday);
                 
                                 }elseif($attendance->status == 2){
                                     // if($payrollworkingday<=date('Y-m-d'))
                                     // {
                                         // array_push($attendanceabsent, $payrollworkingday);
                                         if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
                                         {
                                             
                                             $selectedday = strtolower(date('D', strtotime($workingday)));
                                             if(strtolower($selectedday) == 'mon')
                                             {
                                                 $hoursperday = $basicsalaryinfo->mondayhours;
                                             }
                                             elseif(strtolower($selectedday) == 'tue')
                                             {
                                                 $hoursperday = $basicsalaryinfo->tuesdayhours;
                                             }
                                             elseif(strtolower($selectedday) == 'wed')
                                             {
                                                 $hoursperday = $basicsalaryinfo->wednesdayhours;
                                             }
                                             elseif(strtolower($selectedday) == 'thu')
                                             {
                                                 $hoursperday = $basicsalaryinfo->thursdayhours;
                                             }
                                             elseif(strtolower($selectedday) == 'fri')
                                             {
                                                 $hoursperday = $basicsalaryinfo->fridayhours;
                                             }
                                             elseif(strtolower($selectedday) == 'sat')
                                             {
                                                 $hoursperday = $basicsalaryinfo->saturdayhours;
                                             }
                                             elseif(strtolower($selectedday) == 'sun')
                                             {
                                                 $hoursperday = $basicsalaryinfo->sundayhours;
                                             }
                                             else
                                             {
                                                 $hoursperday = 0;
                                             }
                 
                                             if($hoursperday >  0)
                                             {
                                                 $absentminutes+= ($hoursperday*60);
                                             }
                 
                                         }
                                     // }
                 
                                 }
                             }
                         }
                     }
                     $history->hoursrendered = $hoursrendered;
                 }
             }
     
             $standardallowances = DB::table('allowance_standard')
                 ->select('id', 'description')
                 ->where('deleted','0')
                 ->get();
     
             if(count($standardallowances)>0)
             {
                 foreach($standardallowances as $standardallowance)
                 {
                     $sumallow = 0;
     
                     if(count($payroll_history)>0)
                     {
                         foreach($payroll_history as $historydetails)
                         {
                             // return collect($historydetails->historydetails);
                             if(count($historydetails->historydetails)>0)
                             {
                                 foreach($historydetails->historydetails as $historydetail)
                                 {
                                     if($historydetail->allowanceid == $standardallowance->id && $historydetail->type == 'standard')
                                     {
                                         $sumallow+=$historydetail->amount;
                                     }
                                 }
                             }
                         }
                     }
                     $standardallowance->total = $sumallow;
                 }
             }
     
             // return $standardallowances;
     
             $standarddeductions = DB::table('deduction_standard')
                 ->select('id', 'description')
                 ->where('deleted','0')
                 ->get();
     
             if(count($standarddeductions)>0)
             {
                 foreach($standarddeductions as $standarddeduction)
                 {
                     $sumdeduct = 0;
     
                     if(count($payroll_history)>0)
                     {
                         foreach($payroll_history as $historydetails)
                         {
                             // return collect($historydetails->historydetails);
                             if(count($historydetails->historydetails)>0)
                             {
                                 foreach($historydetails->historydetails as $historydetail)
                                 {
                                     if($historydetail->deductionid == $standarddeduction->id && $historydetail->type == 'standard')
                                     {
                                         $sumdeduct+=$historydetail->amount;
                                     }
                                 }
                             }
                         }
                     }
                     $standarddeduction->total = $sumdeduct;
                 }
             }
     
     
             $payrolldates->datefrom    = date('M d, Y', strtotime($payrolldates->datefrom));
             $payrolldates->dateto      = date('M d, Y', strtotime($payrolldates->dateto));
             if($request->get('exporttype') == 'pdf')
             {
                 $pdf = new PayrollSummary(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                 // set document information
                 $pdf->SetCreator('CK');
                 $pdf->SetAuthor('CK Children\'s Publishing');
                 $pdf->SetTitle($schoolinfo->schoolname.' - PAYROLL SUMMARY '.$payrolldates->datefrom.' to '.$payrolldates->dateto);
                 $pdf->SetSubject('PAYSLIP');
                 
                 // set header and footer fonts
                 $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                 $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                 
                 // $pdf->setPrintHeader(false);
                 // $pdf->setPrintFooter(false);
                 
                 // set default monospaced font
                 $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                 
                 // set margins
                 $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                 // $pdf->SetMargins(5, 0, 5, true);
                 $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                 $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                 
                 // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
                 // set auto page breaks
                 $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                 
                 // set image scale factor
                 $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                 
                 // set some language-dependent strings (optional)
                 if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                     require_once(dirname(__FILE__).'/lang/eng.php');
                     $pdf->setLanguageArray($l);
                 }
                 
                 // ---------------------------------------------------------
                 
                 // set font
                 $pdf->SetFont('dejavusans', '', 10);
                 
                 
                 // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                 // Print a table
                 
                 // add a page
                 
                 $pdf->AddPage('L',array(216,356));
                     
                 set_time_limit(3000);
     
                 $payrolldetails = collect($payroll_history)->where('salarybasistype',$request->get('selectedsalarytypeid'))->sortBy('lastname');
                 $payrollsetup = DB::table('payroll_setup')
                     ->where('payrollid', $payrolldates->id)
                     ->where('deleted','0')
                     ->get();
                 
                 if(strtolower($salarytype) == 'hourly')
                 {
                     // return 'Not yet supported!';
                     // return $payrolldetails;
                     if(count($payrollsetup) == 0)
                     {
                         $view = \View::make('hr/payroll/payslip/pdf_summary_parttime',compact('payrolldetails','payrolldates','schoolinfo','standardallowances','standarddeductions','salarytype','payrollsetup'));
                     }else{
                         $view = \View::make('hr/payroll/payslip/pdf_summary_parttime_ws',compact('payrolldetails','payrolldates','schoolinfo','standardallowances','standarddeductions','salarytype','payrollsetup'));
                     }
                 }else{
                     if(count($payrollsetup) == 0)
                     {
                         $view = \View::make('hr/payroll/payslip/pdf_summary_default',compact('payrolldetails','payrolldates','schoolinfo','standardallowances','standarddeductions','salarytype','payrollsetup'));
                     }else{
                         $view = \View::make('hr/payroll/payslip/pdf_summary_default_ws',compact('payrolldetails','payrolldates','schoolinfo','standardallowances','standarddeductions','salarytype','payrollsetup'));
                     }
                 }
                 $html = $view->render();
                 $pdf->writeHTML($html, true, false, true, false, '');
                 // $pdf->writeHTML(view('registrar/pdf/pdf_numberofenrollees')->compact('enrollees','schoolinfo','from','to')->render());
                 
                 // $pdf->lastPage();
                 
                 // ---------------------------------------------------------
                 //Close and output PDF document
                 $pdf->Output('Payslip.pdf', 'I');
             }
        }   
        

        




   }

   
   public static function getbasicsalary($payrolldates,$basicsalaryinfo)
   {
        date_default_timezone_set('Asia/Manila');
       $employeeworkdaysinaweek = 0; 

        $payrollworkingdays = array();

        $begin = new DateTime($payrolldates->datefrom);

        $end = new DateTime($payrolldates->dateto);

        $end = $end->modify( '+1 day' ); 
        
        $intervalday = new DateInterval('P1D');

        $daterange = new DatePeriod($begin, $intervalday ,$end);

        foreach($daterange as $date){
            
            if(strtolower($date->format("D")) == 'mon' && $basicsalaryinfo->mondays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
            }
            elseif(strtolower($date->format("D")) == 'tue' && $basicsalaryinfo->tuesdays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
            }
            elseif(strtolower($date->format("D")) == 'wed' && $basicsalaryinfo->wednesdays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
            }
            elseif(strtolower($date->format("D")) == 'thu' && $basicsalaryinfo->thursdays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
            }
            elseif(strtolower($date->format("D")) == 'fri' && $basicsalaryinfo->fridays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
            }
            elseif(strtolower($date->format("D")) == 'sat' && $basicsalaryinfo->saturdays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
            }
            elseif(strtolower($date->format("D")) == 'sun' && $basicsalaryinfo->sundays == 1)
            {
                array_push($payrollworkingdays,$date->format("Y-m-d"));
                $employeeworkdaysinaweek+=1;
            }

        }
        
        
        $basicsalary    = 0;
        $permonthhalfsalary = 0;
        $perdaysalary       = 0;
        $hourlyrate         = 0;
        if(strtolower($basicsalaryinfo->salarytype) == 'monthly'){
            // return $workdays;
            if(count($payrollworkingdays) == 0){

                $dailyrate =  $basicsalaryinfo->amount / (int)(date('t') - date('01'));

            }else{
                // round($getrate[0]->amount / $monthdaycount, 2);
                // return $getrate[0]->amount / count($monthworkdays);
                // return $getrate[0]->amount;
                if($payrolldates->leapyear == 0){
                    
                    $dailyrate =  ($basicsalaryinfo->amount*12)/($employeeworkdaysinaweek*52);
                    
                }else{

                    $dailyrate =  ($basicsalaryinfo->amount*12)/(($employeeworkdaysinaweek*52)+1);

                }
            }
            // return $dailyrate;
            if($dailyrate == 0 || $basicsalaryinfo->hoursperday == 0){
                // return 'asdasd';
                $hourlyrate = 0;
            }else{
                
                $hourlyrate = ($dailyrate)/$basicsalaryinfo->hoursperday;
            }
            // return $payrollworkingdays;
            $permonthhalfsalary = $basicsalaryinfo->amount/2;
            if(count($payrollworkingdays) == 0)
            {
                $perdaysalary = $dailyrate;
            }else{
                $perdaysalary = $permonthhalfsalary/count($payrollworkingdays);
            }
            $basicsalary = $permonthhalfsalary;

        }
        elseif(strtolower($basicsalaryinfo->salarytype) == 'daily'){

            $dailyrate =  round($basicsalaryinfo->amount, 2);

            $hourlyrate = ($dailyrate)/$basicsalaryinfo->hoursperday;

            $perdaysalary = $dailyrate;
            $permonthhalfsalary = $perdaysalary*count($payrollworkingdays);
            $basicsalary = $permonthhalfsalary;

            // return $hourlyrate;

        }
        elseif(strtolower($basicsalaryinfo->salarytype) == 'hourly'){

            $hoursperday = 0;
            if($hoursperday == 0){
                if($basicsalaryinfo->mondayhours > 0){
                    $hoursperday = $basicsalaryinfo->mondayhours;
                }
                if($basicsalaryinfo->tuesdayhours > 0){
                    $hoursperday = $basicsalaryinfo->tuesdayhours;
                }
                if($basicsalaryinfo->wednesdayhours > 0){
                    $hoursperday = $basicsalaryinfo->wednesdayhours;
                }
                if($basicsalaryinfo->thursdayhours > 0){
                    $hoursperday = $basicsalaryinfo->thursdayhours;
                }
                if($basicsalaryinfo->fridayhours > 0){
                    $hoursperday = $basicsalaryinfo->fridayhours;
                }
                if($basicsalaryinfo->saturdayhours > 0){
                    $hoursperday = $basicsalaryinfo->saturdayhours;
                }
                if($basicsalaryinfo->sundayhours > 0){
                    $hoursperday = $basicsalaryinfo->sundayhours;
                }
            }
            
            $dailyrate = ($basicsalaryinfo->amount/$hoursperday);
            
            $hourlyrate = $basicsalaryinfo->amount;
            $perdaysalary = $dailyrate;
            $basicsalary = $perdaysalary*count($payrollworkingdays);


        }
        elseif(strtolower($basicsalaryinfo->salarytype) == 'project'){
            
            if($basicsalaryinfo->projectbasedtype == 'persalaryperiod'){
                
                $dailyrate =  $basicsalaryinfo->amount/count($workdays); 

                $hourlyrate =  ($basicsalaryinfo->amount/count($workdays))/$basicsalaryinfo->hoursperday; 

            }
            elseif($basicsalaryinfo->projectbasedtype == 'perday'){
                
                $dailyrate = $basicsalaryinfo->amount;

                $hourlyrate = $basicsalaryinfo->amount/$basicsalaryinfo->hoursperday;

            }
            elseif($basicsalaryinfo->projectbasedtype == 'permonth'){
                
                $payrollworkingdays = ($basicsalaryinfo->amount/count($monthworkdays))/$basicsalaryinfo->hoursperday;

                $dailyrate =  $basicsalaryinfo->amount/count($monthworkdays); 

                
                // return
                
            }
            $perdaysalary = $dailyrate;
            $basicsalary = $perdaysalary*count($payrollworkingdays);
            
        }

        return $basicsalary;
   }
}
