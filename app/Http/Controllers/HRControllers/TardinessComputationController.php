<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class TardinessComputationController extends Controller
{
    public function index(Request $request)
    {
        $computations = DB::table('hr_tardinesscomp')
            ->where('deleted','0')
            ->get();

        $baseonattendance = DB::table('hr_tardinesscomp')
            ->where('baseonattendance', 1)
            ->where('deleted','0')
            ->get();

        $departments = DB::table('hr_departments')
            ->where('deleted','0')
            ->get();
            
        return view('hr.settings.tardinesscomp.index')
            ->with('computations',$computations)
            ->with('baseonattendance',$baseonattendance)
            ->with('departments',$departments);
    }
    public function getbrackets(Request $request)
    {
        $computations = DB::table('hr_tardinesscomp')
            ->where('departmentid',$request->get('deptid'))
            ->where('baseonattendance','0')
            ->where('deleted','0')
            ->get();

        $computationsbaseonattendance = DB::table('hr_tardinesscomp')
            ->where('departmentid',$request->get('deptid'))
            ->where('baseonattendance', 1)
            ->where('deleted','0')
            ->first();
            
        // return collect($computationsbaseonattendance);
        return view('hr.settings.tardinesscomp.brackets')
            ->with('deptid',$request->get('deptid'))
            ->with('computations',$computations)
            ->with('computationsbaseonattendance',$computationsbaseonattendance);
        
    }
    public function addbrackets(Request $request)
    {
        $brackets = json_decode($request->get('brackets'));
        // return $bracket;
        if(count($brackets)>0)
        {
            foreach($brackets as $bracket)
            {
                DB::table('hr_tardinesscomp')
                    ->insert([
                        'departmentid'      => $bracket->deptid,
                        'latefrom'          => $bracket->latefrom,
                        'lateto'            => $bracket->lateto,
                        'latetimetype'      => $bracket->timetype,
                        'deducttype'        => $bracket->deducttype,
                        'amount'            => $bracket->amount,
                        'baseonattendance'            => 0,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
        }
        return 1;
    }
    public function updatebracket(Request $request)
    {
        // return $request->all();
        DB::table('hr_tardinesscomp')
            ->where('id', $request->get('dataid'))
            ->update([
                'latefrom'          =>  $request->get('latefrom'),
                'lateto'            =>  $request->get('lateto'),
                'latetimetype'      =>  $request->get('timetype'),
                'deducttype'        =>  $request->get('deducttype'),
                'amount'            =>  $request->get('amount'),
                'updatedby'         => auth()->user()->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);

        return 1;
    }
    public function deletebracket(Request $request)
    {
        DB::table('hr_tardinesscomp')
            ->where('id', $request->get('id'))
            ->update([
                'deleted'           =>  1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);

        return 1;
    }
    public function activation(Request $request)
    {
        $countcomputations = DB::table('hr_tardinesscomp')
        ->where('departmentid', $request->get('deptid'))
        ->where('deleted','0')
        ->count();
        
        $isactive = $request->get('isactive');
        // return $isactive;
        if($request->get('deptid') == 0)
        {
            DB::table('hr_tardinesscomp')
                ->where('deleted','0')
                ->update([
                    'isactive'      => 0
                ]);

        }else{
            if ($isactive === 1) {

                $boa = DB::table('hr_tardinesscomp')
                ->where('departmentid', $request->get('deptid'))
                ->where('baseonattendance', 1)
                ->where('deleted','0')
                ->update([
                    'isactive'          => 0,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);


                $act = DB::table('hr_tardinesscomp')
                ->where('departmentid', $request->get('deptid'))
                ->where('baseonattendance', 0)
                ->where('deleted','0')
                ->update([
                    'isactive'      => $request->get('isactive')
                ]);

            } else {
                $boa = DB::table('hr_tardinesscomp')
                ->where('departmentid', $request->get('deptid'))
                ->where('baseonattendance', 1)
                ->where('deleted','0')
                ->update([
                    'isactive'          => 0,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
                
                $act = DB::table('hr_tardinesscomp')
                ->where('departmentid', $request->get('deptid'))
                ->where('baseonattendance', 0)
                ->where('deleted','0')
                ->update([
                    'isactive'      => $request->get('isactive')
                ]);
            }
            
        }

        if($countcomputations == 0)
        {
            return 3;
        }else{
            return 1;
        }
    }

    public function baseonattendance(Request $request)
    {
        $depid = $request->get('deptid');
        $applyall = $request->get('apply');
        
        $countcomputations = DB::table('hr_tardinesscomp')
            ->where('departmentid', $request->get('deptid'))
            ->where('baseonattendance', 1)
            ->where('deleted','0')
            ->first();

        // return collect($countcomputations);

        $countcomputationsactive = DB::table('hr_tardinesscomp')
            ->where('departmentid', $request->get('deptid'))
            ->where('baseonattendance', 0)
            ->where('deleted','0')
            ->count();


        if ($countcomputations) {

            if ($countcomputations->isactive == 1) {
                DB::table('hr_tardinesscomp')
                ->where('departmentid', $request->get('deptid'))
                ->where('deleted','0')
                ->where('baseonattendance', 0)
                ->update([
                    'isactive' => 0
                ]);

                DB::table('hr_tardinesscomp')
                ->where('departmentid', $request->get('deptid'))
                ->where('baseonattendance', 1)
                ->where('deleted','0')
                ->update([
                    'isactive'          => 0,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

                return 1;
            } else {
                DB::table('hr_tardinesscomp')
                ->where('departmentid', $request->get('deptid'))
                ->where('deleted','0')
                ->where('baseonattendance', 0)
                ->update([
                    'isactive' => 0
                ]);

                DB::table('hr_tardinesscomp')
                ->where('departmentid', $request->get('deptid'))
                ->where('baseonattendance', 1)
                ->where('deleted','0')
                ->update([
                    'isactive'          => 1,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
                return 1;
            }
                   
        } else {

            if ($countcomputationsactive) {
                DB::table('hr_tardinesscomp')
                ->where('departmentid', $request->get('deptid'))
                ->where('baseonattendance', 0)
                ->where('deleted','0')
                ->update([
                    'isactive' => 0
                ]);
            }

            DB::table('hr_tardinesscomp')
            ->where('departmentid', $request->get('deptid'))
            ->where('baseonattendance', 1)
            ->where('deleted','0')
            ->insert([
                'departmentid'      => $depid,
                'baseonattendance' => 1,
                'isactive' => 1,
                'createdby'         => auth()->user()->id,
                'createddatetime'   => date('Y-m-d H:i:s')
            ]);

            return 1;
        }
        
        
    }
    public function loadtardinessbos(Request $request)
    {
        $depid = $request->get('deptid');

        $computationsbaseonattendance = DB::table('hr_tardinesscomp')
            ->where('departmentid', $depid)
            ->where('baseonattendance', 1)
            ->where('deleted','0')
            ->first();
        
        return collect($computationsbaseonattendance);
    }
    public function baseonsalary(Request $request)
    {
        $deptid = $request->get('deptid');
        $status = $request->get('status');

        $countcomputations = DB::table('hr_tardinesscomp')
            ->where('departmentid', $deptid)
            ->where('baseonattendance', 1)
            ->where('deleted','0')
            ->first();

        // return collect($countcomputations);

        $countcomputationsactive = DB::table('hr_tardinesscomp')
            ->where('departmentid', $deptid)
            ->where('baseonattendance', 0)
            ->where('deleted','0')
            ->count();
        
        if ($countcomputations) {

            if ($countcomputations->isactive == 1) {
                DB::table('hr_tardinesscomp')
                ->where('departmentid', $deptid)
                ->where('deleted','0')
                ->where('baseonattendance', 0)
                ->update([
                    'isactive' => 0
                ]);

                DB::table('hr_tardinesscomp')
                ->where('departmentid', $deptid)
                ->where('baseonattendance', 1)
                ->where('deleted','0')
                ->update([
                    'isactive'          => 0,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

                return 1;
            } else {
                DB::table('hr_tardinesscomp')
                ->where('departmentid', $deptid)
                ->where('deleted','0')
                ->where('baseonattendance', 0)
                ->update([
                    'isactive' => 0
                ]);

                DB::table('hr_tardinesscomp')
                ->where('departmentid', $deptid)
                ->where('baseonattendance', 1)
                ->where('deleted','0')
                ->update([
                    'isactive'          => 1,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
                return 1;
            }
                    
        } else {

            if ($countcomputationsactive) {
                DB::table('hr_tardinesscomp')
                ->where('departmentid', $deptid)
                ->where('baseonattendance', 0)
                ->where('deleted','0')
                ->update([
                    'isactive' => 0
                ]);
            }

            DB::table('hr_tardinesscomp')
            ->where('baseonattendance', 1)
            ->where('deleted','0')
            ->insert([
                'departmentid'      => $deptid,
                'baseonattendance' => 1,
                'isactive' => 1,
                'createdby'         => auth()->user()->id,
                'createddatetime'   => date('Y-m-d H:i:s')
            ]);

            return 1;
        }
    }
    
}
