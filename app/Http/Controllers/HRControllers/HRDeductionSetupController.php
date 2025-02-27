<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Crypt;
class HRDeductionSetupController extends Controller
{
    public static function getbracket_standard($employeeid = null, $rate = null, $particularid = null, $whattoget=null)
    {
        $particularinfo = Db::table('deduction_standard')
            ->where('id',$particularid)
            ->where('deleted','0')
            ->first();

        $amount = null;
        if($particularinfo)
        {
            // return collect($particularinfo);
            if($particularinfo->brackettype == 1) //Pag-Ibig
            {
                $brackets = Db::table('hr_bracketpi')
                    ->where('deleted','0')
                    ->get();
                $getratebracket  = collect($brackets)->where('rangefrom','<=',$rate)->last();
                // return collect($getratebracket);
                if($getratebracket):
                    // return collect($getratebracket);
                    $amount = ($rate*($getratebracket->eescrate/100));
                endif;
            }
            elseif($particularinfo->brackettype == 2) //Philhealth
            {
                $brackets = Db::table('hr_bracketph')
                    ->select(
                        'hr_bracketphdetail.id',
                        'hr_bracketphdetail.rangefrom',
                        'hr_bracketphdetail.rangeto',
                        'hr_bracketphdetail.premiumrate',
                        'hr_bracketphdetail.fixedamount'
                    )
                    ->join('hr_bracketphdetail', 'hr_bracketph.id','=','hr_bracketphdetail.bracketphid')
                    // ->where('hr_bracketph.year', date('Y'))
                    ->where('hr_bracketph.isactive', 1)
                    ->where('hr_bracketph.deleted','0')
                    ->where('hr_bracketphdetail.deleted','0')
                    ->get();
                $getratebracket  = collect($brackets)->where('rangefrom','<=',$rate)->last();
                // return collect($getratebracket);
                if($getratebracket):
                    // return collect($getratebracket);
                    $amount = ($rate*($getratebracket->premiumrate/100));
                endif;
            }
            elseif($particularinfo->brackettype == 3) //SSS
            {
                $brackets = Db::table('hr_bracketss')
                    ->where('deleted','0')
                    ->get();
                $getratebracket  = collect($brackets)->where('rangefrom','<=',$rate)->last();
                // return collect($getratebracket);
                if($getratebracket):
                    // return collect($getratebracket);
                    $amount = $getratebracket->eesamount;
                endif;
            }
            elseif($particularinfo->brackettype == 4)//Withholding Tax
            {
                $brackets = Db::table('hr_bracketwt')
                    ->where('deleted','0')
                    ->where('salarytypeid',4)
                    ->get();
                //     // return $rate;
                $getratebracket  = collect($brackets)->where('rangefrom','<=',$rate)->last();
                
                if($getratebracket):
                    // return collect($getratebracket);
                    $amount = $getratebracket->prescribeamount < 1 ? 0.00 : ($getratebracket->prescribeamount+(($getratebracket->prescribeamount*($getratebracket->prescriberate/100))/$getratebracket->prescribeover));
                endif;
            }else{

            }

        }
        if($whattoget == 'amount')
        {
            return $amount;
        }
    }
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
    public function deductionsetup(Request $request)
    {
        if(!$request->has('action'))
        {
            $deductiontypes = Db::table('deduction_standard')
                ->where('deleted','0')
                ->get();
            $otherdeductions = Db::table('deduction_others')
                ->where('deleted','0')
                ->get();
            
            $employees = DB::table('teacher')
                ->select(
                    'employee_basicsalaryinfo.*',
                    'teacher.id as employeeid',
                    DB::raw('UPPER(teacher.firstname) as firstname'),
					DB::raw('UPPER(teacher.middlename) as middlename'),
					DB::raw('UPPER(teacher.lastname) as lastname'),
                    'teacher.suffix',
                    'teacher.picurl',
                    'teacher.tid',
                    'usertype.id as usertypeid',
                    'usertype.utype',
                    'employee_basistype.type as salarytype'
                    )
                ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
                ->leftJoin('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                ->where('teacher.deleted','0')
                ->where('teacher.isactive','1')
				->orderBy('teacher.lastname', 'asc')
                ->get();
				
			//return $employees;
            foreach($employees as $employee)
            {
                $employee->sortname = $employee->lastname.', '.$employee->firstname.' '.$employee->suffix.' '.($employee->middlename != null ? $employee->middlename[0].'.' : '');
                $contributions = array();
                if(count($deductiontypes)>0)
                {
                    foreach($deductiontypes as $eachstandard)
                    {
                        $defaultamount = null;
                        $automatic = 0;
                        $standardstatus = 1;
                        if($employee->amount > 0 )
                        {
                            if($eachstandard->brackettype > 0)
                            {
                                $defaultamount = str_replace(',', '', self::getbracket_standard($employee->id, $employee->amount, $eachstandard->id,'amount'));
                                // if($eachstandard->brackettype == 4)
                                // {
                                //     return $defaultamount;
                                // }
                            }else{

                                $defaultamount = $eachstandard->amount;
                            }
                            $info = DB::table('employee_deductionstandard')
                                ->where('employee_deductionstandard.deduction_typeid', $eachstandard->id)
                                ->where('employee_deductionstandard.employeeid', $employee->employeeid)
                                ->where('employee_deductionstandard.deleted', 0)
                                ->first();
    
                            if($info)
                            {
                                // if($eachstandard->brackettype == 2)
                                // {
                                //     return self::getbracket_standard($employee->id, $employee->amount, $eachstandard->id,'amount');
                                // }
                                if($eachstandard->brackettype > 0)
                                {
                                    if($info->eesamount != $defaultamount)
                                    {
                                        $automatic = 0;
                                        $standardstatus = 1;
                                    }else{
                                        $automatic = 1;
                                    }
                                }else{
                                    if($info->eesamount != $eachstandard->amount)
                                    {
                                        $automatic = 0;
                                        $standardstatus = 1;
                                    }else{
                                        $automatic = 1;
                                    }
                                }
                                $getdeductionamount = $info->eesamount;
                                $standardstatus = $info->status;
                                // $getdeductionamount = self::getbracket_standard($employee->id, $employee->amount, $eachstandard->id);
                            }else{
                                $automatic = 1;
                                $getdeductionamount = $defaultamount;
                                DB::table('employee_deductionstandard')
                                    ->insert([
                                        'employeeid'        =>  $employee->employeeid,
                                        'deduction_typeid'  => $eachstandard->id,
                                        'eesamount'         => $getdeductionamount,
                                        'status'            => 1,
                                        'sc_me'            => 1,
                                        'datestarted'       => date('Y-m-d'),
                                        'createdby'         => 0,
                                        'createddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                                // if($eachstandard->brackettype ==3)
                                // {
                                //     return collect($getdeductionamount);
                                // }
                            }
                            // if($eachstandard->id == 4)
                            // {
                                
                            //     return $getdeductionamount;
                            // }
                        }else
                        {   
                            $defaultamount = null;
                            $getdeductionamount = null;
                        }
                        // if($employee->id == 3 && $eachstandard->id == 2)
                        // {
                        //     return str_replace(',', '', $defaultamount);
                        // }
                        // return $getdeductionamount;
                        array_push($contributions, (object)array(
                            'id'            => $eachstandard->id,
                            'description'   => $eachstandard->description,
                            'brackettype'   => $eachstandard->brackettype,
                            'cont_amount_default'   => str_replace(',', '', $defaultamount),
                            'cont_amount'   => $getdeductionamount == null ? '0.00' : str_replace(',', '', $getdeductionamount),
                            'status'   => $standardstatus,
                            'automatic'    => $automatic
                        ));
                    }
                }
                // return $contributions;
                $employee->contributions = $contributions;
                $otherconts = array();
                if(count($otherdeductions)>0)
                {
                    foreach($otherdeductions as $eachother)
                    {
                        $info = DB::table('employee_deductionother')
                            ->where('employee_deductionother.deductionotherid', $eachother->id)
                            ->where('employee_deductionother.employeeid', $employee->employeeid)
                            ->where('employee_deductionother.deleted', 0)
                            ->first();

                        if($info)
                        {
                            $getdeductionamount = $info->amount;
                            $custom = ($eachother->amount != $info->amount ? 1 : 0);
                            array_push($otherconts, (object)array(
                                'id'            => $eachother->id,
                                'description'   => $eachother->description,
                                'cont_amount_default'   => $eachother->amount,
                                'cont_amount'   => $getdeductionamount,
                                'status'   => $info->status,
                                'custom'    => $custom
                            ));
                        }
                    }
                }
                $employee->othercontributions = $otherconts;
                
            }
            // return $employees;
            return view('hr.settings.deductions.index')
                ->with('employees',collect($employees)->sortBy('sortname')->values()->all())
                ->with('otherdeductions',$otherdeductions)
                ->with('standarddeductions',$deductiontypes);
        }else{
            if($request->get('action') == 'add_standarddeduction')
            {
                // $checkifexists = DB::table('deduction_standard')
                //     ->where('description','like','%'.$request->get('desc').'%')
                //     // ->where('id','!=',$request->get('id'))
                //     ->where('deleted','0')
                //     ->first();

                // if($checkifexists)
                // {
                //     return 0 ;
                // }else{
                    $id = DB::table('deduction_standard')
                        ->insertGetId([
                            'description'       => $request->get('desc'),
                            'amount'            => $request->get('amount'),
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return $id;
                // }
            }
            if($request->get('action') == 'update_standarddeduction')
            {
                // $checkifexists = DB::table('deduction_standard')
                //     ->where('description','like','%'.$request->get('desc').'%')
                //     // ->where('id','!=',$request->get('id'))
                //     ->where('deleted','0')
                //     ->first();

                // if($checkifexists)
                // {
                //     return 0 ;
                // }else{
                    DB::table('deduction_standard')
                        ->where('id', $request->get('id'))
                        ->update([
                            // 'description'       => $request->get('desc'),
                            'amount'            => $request->get('amount'),
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return 1;
                // }
            }
            else if($request->get('action') == 'delete_standarddeduction')
            {
                DB::table('deduction_standard')
                    ->where('id', $request->get('id'))
                    ->update([
                        // 'description'       => $request->get('desc'),
                        'deleted'           => 1,
                        'deletedby'         => auth()->user()->id,
                        'deleteddatetime'   => date('Y-m-d H:i:s')
                    ]);

                return 1;
            }
            elseif($request->get('action') == 'add_otherdeduction')
            {
                // $checkifexists = DB::table('deduction_standard')
                //     ->where('description','like','%'.$request->get('desc').'%')
                //     // ->where('id','!=',$request->get('id'))
                //     ->where('deleted','0')
                //     ->first();

                // if($checkifexists)
                // {
                //     return 0 ;
                // }else{
                    $id = DB::table('deduction_others')
                        ->insertGetId([
                            'description'       => $request->get('desc'),
                            'amount'            => $request->get('amount'),
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return $id;
                // }
            }
            if($request->get('action') == 'update_otherdeduction')
            {
                // $checkifexists = DB::table('deduction_standard')
                //     ->where('description','like','%'.$request->get('desc').'%')
                //     // ->where('id','!=',$request->get('id'))
                //     ->where('deleted','0')
                //     ->first();

                // if($checkifexists)
                // {
                //     return 0 ;
                // }else{
                    DB::table('deduction_others')
                        ->where('id', $request->get('id'))
                        ->update([
                            // 'description'       => $request->get('desc'),
                            'amount'            => $request->get('amount'),
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return 1;
                // }
            }
            else if($request->get('action') == 'delete_otherdeduction')
            {
                DB::table('deduction_others')
                    ->where('id', $request->get('id'))
                    ->update([
                        // 'description'       => $request->get('desc'),
                        'deleted'           => 1,
                        'deletedby'         => auth()->user()->id,
                        'deleteddatetime'   => date('Y-m-d H:i:s')
                    ]);

                return 1;
            }
        }
    }
    public function customcontribution(Request $request)
    {
        // return $request->all();
        date_default_timezone_set('Asia/Manila');

        if($request->get('action') == 'custom_add')
        {
            
            if($request->get('startdate') == date('Y-m-d')){
                // return '1';
                $status = 1;
            }elseif($request->get('startdate') < date('Y-m-d')){
                // return '0';
                $status = 1;
            }elseif($request->get('startdate') > date('Y-m-d')){
                // return '0';
                $status = 0;
            }else{
                $status = 1;
            }
            $checkifexists = DB::table('employee_deductionstandard')
                ->where('employee_deductionstandard.deduction_typeid', $request->get('particularid'))
                ->where('employee_deductionstandard.employeeid', $request->get('employeeid'))
                ->where('employee_deductionstandard.deleted', 0)
                ->first();
                
            if($checkifexists)
            {
                DB::table('employee_deductionstandard')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'employeeid'        => $request->get('employeeid'),
                        'deduction_typeid'  => $request->get('particularid'),
                        'eesamount'         => $request->get('amount'),
                        'status'            => $status,
                        'sc_me'            => 2,
                        'datestarted'       => $request->has('startdate') ?  $request->get('startdate') : date('Y-m-d'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
            else{
                DB::table('employee_deductionstandard')
                    ->insert([
                        'employeeid'        => $request->get('employeeid'),
                        'deduction_typeid'  => $request->get('particularid'),
                        'eesamount'         => $request->get('amount'),
                        'status'            => $status,
                        'sc_me'            => 2,
                        'datestarted'       => $request->has('startdate') ?  $request->get('startdate') : date('Y-m-d'),
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
    
        }elseif($request->get('action') == 'custom_reset')
        {
            DB::table('employee_deductionstandard')
            ->where('employee_deductionstandard.deduction_typeid', $request->get('particularid'))
            ->where('employee_deductionstandard.employeeid', $request->get('employeeid'))
                ->update([
                    'eesamount'            =>  $request->get('defaultamount'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                    // 'deleted'            => 1,
                    // 'deletedby'         => auth()->user()->id,
                    // 'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
        }elseif($request->get('action') == 'custom_disable')
        {
            $checkifexists = DB::table('employee_deductionstandard')
                ->where('employee_deductionstandard.deduction_typeid', $request->get('particularid'))
                ->where('employee_deductionstandard.employeeid', $request->get('employeeid'))
                ->where('employee_deductionstandard.deleted', 0)
                ->first();

            if($checkifexists)
            {
                DB::table('employee_deductionstandard')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'status'            => 0,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }elseif($request->get('action') == 'custom_enable')
        {
            $checkifexists = DB::table('employee_deductionstandard')
                ->where('employee_deductionstandard.deduction_typeid', $request->get('particularid'))
                ->where('employee_deductionstandard.employeeid', $request->get('employeeid'))
                ->where('employee_deductionstandard.deleted', 0)
                ->first();

            if($checkifexists)
            {
                DB::table('employee_deductionstandard')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'status'            => 1,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }
        return 1;
    }
    public function otherdeductions(Request $request)
    {
        // return $request->all();            
        if($request->get('action') == 'add_otherdeductiontoemployee')
        {
            // return $request->all();
            DB::table('employee_deductionother')
                ->insert([
                    'employeeid'        => $request->get('employeeid'),
                    'deductionotherid'        => $request->get('particularid'),
                    'description'        => $request->get('desc'),
                    'fullamount'        => $request->get('fullamount'),
                    'amount'        => $request->get('amount'),
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);
        }        
        else if($request->get('action') == 'edit_otherdeductiontoemployee')
        {
            // return $request->all();
            DB::table('employee_deductionother')
                ->where('deductionotherid', $request->get('particularid'))
                ->where('employeeid', $request->get('employeeid'))
                ->where('deleted', '0')
                ->update([
                    'amount'        => $request->get('amount'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

        }
        elseif($request->get('action') == 'otherdeduction_disable')
        {
            $checkifexists = DB::table('employee_deductionother')
                ->where('employee_deductionother.deductionotherid', $request->get('particularid'))
                ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                ->where('employee_deductionother.deleted', 0)
                ->first();

            if($checkifexists)
            {
                DB::table('employee_deductionother')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'status'            => 0,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }elseif($request->get('action') == 'otherdeduction_enable')
        {
            $checkifexists = DB::table('employee_deductionother')
                ->where('employee_deductionother.deductionotherid', $request->get('particularid'))
                ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                ->where('employee_deductionother.deleted', 0)
                ->first();

            if($checkifexists)
            {
                DB::table('employee_deductionother')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'status'            => 1,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }elseif($request->get('action') == 'delete_otherdeduction')
        {
            $checkifexists = DB::table('employee_deductionother')
                ->where('employee_deductionother.deductionotherid', $request->get('particularid'))
                ->where('employee_deductionother.employeeid', $request->get('employeeid'))
                ->where('employee_deductionother.deleted', 0)
                ->first();

            if($checkifexists)
            {
                DB::table('employee_deductionother')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'deleted'            => 1,
                        'deletedby'         => auth()->user()->id,
                        'deleteddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }
        return 1;
    }
    public function bracketing(Request $request)
    {
        if(!$request->has('action'))
        {
            $id = $request->get('partid');
            $desc = $request->get('partdesc');
            $type = $request->get('parttype');
            $brackets = array();
            if($type == 1): //Pag-Ibig
                $brackets = Db::table('hr_bracketpi')
                ->where('deleted','0')
                            ->get();
            elseif($type == 2): //Philhealth
                $brackets = Db::table('hr_bracketph')
                            ->where('hr_bracketph.deleted','0')
                            ->get();
            elseif($type == 3): //SSS
                $brackets = Db::table('hr_bracketss')
                    ->where('deleted','0')
                            ->get();
            elseif($type == 4): //Withholding Tax
                $brackets = Db::table('hr_bracketwt')
                            ->get();
            endif;
        
            return view('hr.settings.deductions.brackets')
            ->with('type', $type)
            ->with('id', $id)
            ->with('brackets',$brackets);
        }else{
            if($request->get('action') == 'getbracketitems')
            {  
                $bracketitems = Db::table('hr_bracketphdetail')
                            ->select(
                                'hr_bracketphdetail.id',
                                'hr_bracketphdetail.rangefrom',
                                'hr_bracketphdetail.rangeto',
                                'hr_bracketphdetail.premiumrate',
                                'hr_bracketphdetail.fixedamount'
                            )
                            ->where('hr_bracketphdetail.bracketphid',$request->get('yearid'))
                            ->where('hr_bracketphdetail.deleted','0')
                            ->get();

                return $bracketitems;
            }
            else if($request->get('action') == 'getyearstatus')
            {  
                $status  = DB::table('hr_bracketph')
                    ->where('id',$request->get('yearid'))
                    ->first()->isactive;

                return $status;
            }
            elseif($request->get('action') == 'year_updatestatus')
            {
                DB::table('hr_bracketph')
                    ->where('deleted','0')
                    ->where('isactive','1')
                    ->update([
                        'isactive'  => 0
                    ]);

                DB::table('hr_bracketph')
                    ->where('id',$request->get('yearid'))
                    ->update([
                        'isactive' =>1
                    ]);

                
                return 1;
            }
            else if($request->get('action') == 'year_add')
            {  
                $checkifexists  = DB::table('hr_bracketph')
                    ->where('year','like','%'.$request->get('addyear').'%')
                    ->where('deleted','0')
                    ->first();
                if($checkifexists)
                {
                    return 0;
                }else{
                    DB::table('hr_bracketph')
                        ->where('deleted','0')
                        ->where('isactive','1')
                        ->update([
                            'isactive'  => 0
                        ]);

                    $id = DB::table('hr_bracketph')
                        ->insertGetId([
                            'year' => $request->get('addyear'),
                            'isactive'  => 1
                        ]);
    
                    return $id;
                }
            }
            elseif($request->get('action') == 'gettaxtable')
            {  
                $bracketitems = Db::table('hr_bracketwt')
                            ->where('hr_bracketwt.salarytypeid',$request->get('tableid'))
                            ->where('hr_bracketwt.deleted','0')
                            ->get();
                
                return $bracketitems;
            }
            else if($request->get('action') == 'bracketitem_add')
            {              
        
                if(strtolower($request->get('type')) == 'pag-ibig'):
                    $id = DB::table('hr_bracketpi')
                        ->insertGetId([
                            'rangefrom' => $request->get('bracketfrom'),
                            'rangeto'   => $request->get('bracketto'),
                            'eescrate'  => $request->get('bracketcontemployee'),
                            'erscrate'  => $request->get('bracketcontemployer')
                        ]);

                    return $id;

                elseif(strtolower($request->get('type')) == 'philhealth'):

                    $id = DB::table('hr_bracketphdetail')
                        ->insertGetId([
                            'bracketphid'     => $request->get('yearid'),
                            'rangefrom'     => $request->get('bracketfrom'),
                            'rangeto'       => $request->get('bracketto'),
                            'premiumrate'   => $request->get('bracketrate'),
                            'fixedamount'   => $request->get('bracketfixedamount'),
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);

                    return $id;

                elseif(strtolower($request->get('type')) == 'sss'):
                    
                    $id = DB::table('hr_bracketss')
                        ->insertGetId([
                            'rangefrom'             => $request->get('bracketfrom'),
                            'rangeto'               => $request->get('bracketto'),
                            'monthlysalarycredit'   => $request->get('bracketcredit'),
                            'ersamount'             => $request->get('bracketcontemployee'),
                            'eesamount'             => $request->get('bracketcontemployer')
                        ]);

                    return $id;
                elseif(strtolower($request->get('type')) == 'w_tax'):
                    
                    $id = DB::table('hr_bracketwt')
                        ->insertGetId([
                            'salarytypeid'          => $request->get('tableid'),
                            'rangefrom'             => $request->get('bracketrangefrom'),
                            'rangeto'               => $request->get('bracketrangeto'),
                            'prescribeamount'   => $request->get('bracketprescribeamount'),
                            'prescriberate'             => $request->get('bracketprescriberate'),
                            'prescribeover'             => $request->get('bracketprescribeover')
                        ]);

                    return $id;
                endif;
            }
            else if($request->get('action') == 'bracketitem_edit')
            {   
                // return $request->all();
                if(strtolower($request->get('type')) == 'pag-ibig'):
                    DB::table('hr_bracketpi')
                        ->where('id', $request->get('itemid'))
                        ->update([
                            'rangefrom' => $request->get('bracketfrom'),
                            'rangeto'   => $request->get('bracketto'),
                            'eescrate'  => $request->get('bracketcontemployee'),
                            'erscrate'  => $request->get('bracketcontemployer')
                            // 'updatedby'         => auth()->user()->id,
                            // 'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);

                elseif(strtolower($request->get('type')) == 'philhealth'):

                    DB::table('hr_bracketphdetail')
                        ->where('id', $request->get('itemid'))
                        ->update([
                            'rangefrom'     => $request->get('bracketfrom'),
                            'rangeto'       => $request->get('bracketto'),
                            'premiumrate'   => $request->get('bracketrate'),
                            'fixedamount'   => $request->get('bracketfixedamount'),
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);

                elseif(strtolower($request->get('type')) == 'sss'):
                    
                    // return $request->all();
                    DB::table('hr_bracketss')
                        ->where('id', $request->get('itemid'))
                        ->update([
                            'rangefrom'             => $request->get('bracketfrom'),
                            'rangeto'               => $request->get('bracketto'),
                            'monthlysalarycredit'   => $request->get('monthlysalarycredit'),
                            'ersamount'             => $request->get('eesamount'),
                            'eesamount'             => $request->get('ersamount')
                            // 'updatedby'         => auth()->user()->id,
                            // 'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                elseif(strtolower($request->get('type')) == 'w_tax'):
                    
                    // return $request->all();
                    DB::table('hr_bracketwt')
                        ->where('id', $request->get('itemid'))
                        ->update([
                            'rangefrom'             => $request->get('bracketrangefrom'),
                            'rangeto'               => $request->get('bracketrangeto'),
                            'prescribeamount'       => $request->get('bracketprescribeamount'),
                            'prescriberate'         => $request->get('bracketprescriberate'),
                            'prescribeover'         => $request->get('bracketprescribeover'),
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                endif;
                return 1;
            }
            else if($request->get('action') == 'bracketitem_delete')
            {
                // return $request->all();
                if(strtolower($request->get('type')) == 'pag-ibig'):
                    
                    DB::table('hr_bracketpi')
                        ->where('id', $request->get('id'))
                        ->update([
                            'deleted'             => 1,
                            'deletedby'               => auth()->user()->id,
                            'deleteddatetime'   => date('Y-m-d H:i:s')
                        ]);
    
                elseif(strtolower($request->get('type')) == 'philhealth'):
    
                    DB::table('hr_bracketphdetail')
                        ->where('id', $request->get('id'))
                        ->update([
                            'deleted'             => 1,
                            'deletedby'               => auth()->user()->id,
                            'deleteddatetime'   => date('Y-m-d H:i:s')
                        ]);
    
                elseif(strtolower($request->get('type')) == 'sss'):
                    DB::table('hr_bracketss')
                        ->where('id', $request->get('id'))
                        ->update([
                            'deleted'             => 1,
                            'deletedby'               => auth()->user()->id,
                            'deleteddatetime'   => date('Y-m-d H:i:s')
                        ]);
                elseif(strtolower($request->get('type')) == 'w_tax'):
                    DB::table('hr_bracketwt')
                        ->where('id', $request->get('id'))
                        ->update([
                            'deleted'             => 1,
                            'deletedby'               => auth()->user()->id,
                            'deleteddatetime'   => date('Y-m-d H:i:s')
                        ]);
                endif;
            }
            return 1;
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
            
        if($request->get('startdate') == date('Y-m-d')){
            // return '1';
            $status = 1;
        }elseif($request->get('startdate') < date('Y-m-d')){
            // return '0';
            $status = 1;
        }elseif($request->get('startdate') > date('Y-m-d')){
            // return '0';
            $status = 0;
        }else{
            $status = 1;
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
