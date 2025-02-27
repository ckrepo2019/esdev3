<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Crypt;
class HRDeductionSetupController extends Controller
{
    //
    public function newdeductionsetup($id,Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');

        $id = Crypt::decrypt($id);
        if($id == 'dashboard'){
            
            $departments = Db::table('hr_departments')
            ->where('deleted',0)
            ->get();

            $tardinesstype = Db::table('deduction_tardinesssetup')
                ->where('deleted','0')
                ->get();

            $tardinessdetails = Db::table('deduction_tardinessdetail')
                ->where('deduction_tardinessdetail.deleted','0')
                ->get();

            $tardinesscomputations = array();

            foreach($tardinessdetails as $tardinessdetail){
                
                $tardinessdetail->modifiedamount = number_format($tardinessdetail->amount,2,'.',',');

                $tardinessdetail->modifiedpercentage = $tardinessdetail->dailyratepercentage.' %';

                if($tardinessdetail->specific == '1'){

                    $getdepartments = Db::table('deduction_tardinessapplication')
                        ->join('hr_school_department','deduction_tardinessapplication.departmentid','=','hr_school_department.id')
                        ->where('deduction_tardinessapplication.tardinessdetailid', $tardinessdetail->id)
                        ->where('deduction_tardinessapplication.deleted', '0')
                        ->get();

                    array_push($tardinesscomputations,(object)array(
                        'computationinfo'   => $tardinessdetail,
                        'computationdepartments'   => $getdepartments
                    ));

                }else{

                    array_push($tardinesscomputations,(object)array(
                        'computationinfo'   => $tardinessdetail,
                        'computationdepartments'   => 'All'
                    ));

                }

            }
            // return $tardinesscomputations;
            $deductiontypes = Db::table('deduction_standard')
                ->where('deleted','0')
                ->get();
            
            $employees = DB::table('teacher')
                ->select(
                    'teacher.id as employeeid',
                    'teacher.lastname',
                    'teacher.middlename',
                    'teacher.firstname',
                    'teacher.suffix',
                    'employee_personalinfo.gender',
                    'usertype.utype',
                    'employee_basicsalaryinfo.amount',
                    'employee_basistype.type'
                )
                ->leftjoin('employee_personalinfo', 'teacher.id','=','employee_personalinfo.employeeid')
                ->join('usertype', 'teacher.usertypeid','=','usertype.id')
                ->join('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                ->where('teacher.isactive','1')
                ->get();
                
            if(count($employees) > 0){

                foreach($employees as $employee){

                    if($employee->middlename == null){
                        $employee->middlename = "";
                    }else{
                        $employee->middlename = $employee->middlename[0].'.';
                    }
                    if($employee->suffix == null){
                        $employee->suffix = "";
                    }

                    $deductioninfo = DB::table('deduction_standard')
                        ->select(
                            'deduction_standard.description',
                            'deduction_standard.id as deductionid',
                            'employee_deductionstandard.status',
                            'employee_deductionstandard.datestarted'
                            )
                        ->join('employee_deductionstandard','deduction_standard.id','=','employee_deductionstandard.deduction_typeid')
                        // ->where('employee_deductionstandard.deduction_typeid',$request->get('deductionid'))
                        ->where('employee_deductionstandard.employeeid', $employee->employeeid)
                        // ->where('employee_deductionstandard.status', '1')
                        // ->where('employee_deductionstandard.paid', '0')
                        ->where('employee_deductionstandard.deleted', '0')
                        ->distinct()
                        ->get();
                        
                    if(count($deductioninfo) > 0){
                        
                        foreach($deductioninfo as $dedinfo){
                            
                            if($dedinfo->status == 0){
                                // return date('Y-m-d');
                                if(date('Y-m-d', strtotime($dedinfo->datestarted)) >= date('Y-m-d')){
                                    
                                    DB::table('employee_deductionstandard')
                                        ->where('deduction_typeid', $dedinfo->deductionid)
                                        ->where('employeeid', $employee->employeeid)
                                        ->where('deleted', '0')
                                        ->update([
                                            'status'    => 1
                                        ]);
                                }
                            }
                            $dedinfo->datestarted = date('M d,Y',strtotime($dedinfo->datestarted));

                        }
                    }
                    $employee->deductionsinfo = $deductioninfo;



                }

            }
            
            return view('hr.deductions')
                ->with('deductiontypes',$deductiontypes)
                ->with('employees',$employees)
                ->with('departments',$departments)
                ->with('tardinesstype',$tardinesstype)
                ->with('tardinesscomputations',$tardinesscomputations);
        }else{

            // $id = Crypt::decrypt($id);
            // return $request->all();
            if($id == 'adddeduction'){
                
                if($request->get('type') == 'standard'){
                    $type = 1;
                }
                elseif($request->get('type') == 'savings'){
                    $type = 2;
                }
                elseif($request->get('type') == 'other'){
                    $type = 3;
                }
                $checkifExists = Db::table('deduction_standard')
                    ->where('description','like','%'.$request->get('deductiondescription'))
                    ->where('type', $type)
                    ->where('deleted','0')
                    ->get();

                if(count($checkifExists) == 0){

                    Db::table('deduction_standard')
                        ->insert([
                            'description'   => strtoupper($request->get('deductiondescription')),
                            'type'          => $type
                        ]);

                    return '0';

                }else{

                    return '1';

                }

                // }

                // return back();

            }
            if($id == 'editdeduction'){
                // return $request->all();
                Db::table('deduction_standard')
                    ->where('id', $request->get('deductionid'))
                    ->update([
                        'description' => strtoupper($request->get('deductiondescription'))
                    ]);

                return 'success';

            }
            if($id == 'deletededuction'){
                // return $request->all();
                $checkemployees = DB::table('employee_deductionstandard')
                    ->where('deduction_typeid',$request->get('deductionid'))
                    ->where('status', '1')
                    // ->where('paid', '0')
                    // ->where('deleted', '0')
                    ->get();
                if(count($checkemployees) == 0){
                    Db::table('deduction_standard')
                        ->where('id', $request->get('deductionid'))
                        ->update([
                            'deleted' => '1'
                        ]);
                    return '0';
                }else{
                    return '1';
                }

            }
            if($id == 'getbydeduction'){
                
                $employees = DB::table('teacher')
                    ->select(
                        'teacher.id as employeeid',
                        'teacher.lastname',
                        'teacher.middlename',
                        'teacher.firstname',
                        'teacher.suffix',
                        'employee_personalinfo.gender',
                        'usertype.utype',
                        'employee_basicsalaryinfo.amount',
                        'employee_basistype.type'
                    )
                    ->join('employee_personalinfo', 'teacher.id','=','employee_personalinfo.employeeid')
                    ->join('usertype', 'teacher.usertypeid','=','usertype.id')
                    ->join('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                    ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                    ->where('teacher.isactive','1')
                    ->get();
                // return $employees;
                if(count($employees) > 0){

                    foreach($employees as $employee){

                        if($employee->middlename == null){
                            $employee->middlename = "";
                        }else{
                            $employee->middlename = $employee->middlename[0].'.';
                        }
                        if($employee->suffix == null){
                            $employee->suffix = "";
                        }
                        $employee->amount = number_format($employee->amount, 2, '.', ',');

                        $deductioninfo = DB::table('employee_deductionstandard')
                            ->join('deduction_standard','employee_deductionstandard.deduction_typeid','=','deduction_standard.id')
                            ->where('deduction_typeid',$request->get('deductionid'))
                            ->where('employeeid', $employee->employeeid)
                            // ->where('status', '1')
                            // ->where('paid', '0')
                            ->where('employee_deductionstandard.deleted', '0')
                            ->get();

                        // return $deductioninfo;
                        if(count($deductioninfo) > 0){
                            foreach($deductioninfo as $dedinfo){
    
                                if($dedinfo->status == 0){
                                    if($dedinfo->datestarted >= date('Y-m-d')){
                                        DB::table('employee_deductionstandard')
                                            ->where('deduction_typeid', $dedinfo->deductionid)
                                            ->where('employeeid', $employee->employeeid)
                                            ->update([
                                                'status'    => 1
                                            ]);
                                    }
                                }
                                $dedinfo->datestarted = date('M d,Y',strtotime($dedinfo->datestarted));
    
                            }
                            $employee->deductionsinfo = $deductioninfo;
                        }



    
                    }

                }
                return $employees;

            }
        }

    }
    public function hrapplicationofdeduction(Request $request)
    {
        // return $request->all();
        date_default_timezone_set('Asia/Manila');

        $createdby = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first()
            ->id;
            
        if($request->get('deductionstartdate') == date('Y-m-d')){
            // return '1';
            $status = 1;
        }elseif($request->get('deductionstartdate') < date('Y-m-d')){
            // return '0';
            $status = 1;
        }elseif($request->get('deductionstartdate') > date('Y-m-d')){
            // return '0';
            $status = 0;
        }
        // return $status;
        foreach($request->get('employeeids') as $employee){
            $employeeexplode = explode(' - ',$employee);
            // $status = $employeeexplode[0];
            $employeeid = $employeeexplode[1];
            $checkifexists = DB::table('employee_deductionstandard')
                ->where('employee_deductionstandard.deduction_typeid', $request->get('deductionid'))
                ->where('employee_deductionstandard.employeeid', $employeeid)
                ->where('employee_deductionstandard.deleted', 0)
                ->get();
                
            if(count($checkifexists) == 0){

                // DB::table('hr_deductionsdetail')
                //     ->insert([
                //         'deductionid'       => $request->get('deductionid'),
                //         'employeeid'        => $employeeid,
                //     ])
                Db::table('employee_deductionstandard')
                    ->insert([
                        'employeeid'        => $employeeid,
                        'deduction_typeid'  => $request->get('deductionid'),
                        'status'            => $status,
                        'datestarted'       => $request->get('deductionstartdate'),
                        'createdby'         => $createdby,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);

            }
        }
        return back();
    }
    public function hrapplicationdelete(Request $request)
    {
        // return $request->all();
        // return $request->all();
        date_default_timezone_set('Asia/Manila');

        $deletedby = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first()
            ->id;
        
        foreach($request->get('employeeids') as $employee){
            $employeeexplode = explode(' - ',$employee);
            // $status = $employeeexplode[0];
            $employeeid = $employeeexplode[1];
            Db::table('employee_deductionstandard') 
                ->where('employeeid', $employeeid)
                ->where('deduction_typeid', $request->get('deductionid'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => $deletedby,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }
        return back();
    }
}
