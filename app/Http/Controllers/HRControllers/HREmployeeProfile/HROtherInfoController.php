<?php

namespace App\Http\Controllers\HRControllers\HREmployeeProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class HROtherInfoController extends Controller
{
    public function employeeotherstabindex(Request $request)
    {
        
        $teacherid = $request->get('employeeid');

        $timeschedule = Db::table('employee_customtimesched')
            ->where('employeeid',$teacherid)
            ->where('deleted','0')
            ->get();
        
        if(count(DB::table('employee_basicsalaryinfo')->where('employeeid', $teacherid)->get()) == 0)
        {
            $shiftid = (object) array(
                
                'shiftid'   => 0
            );
            $attendancebased = 1;
        }else{
            $shiftid = Db::table('employee_basicsalaryinfo')
                ->select('employee_basicsalaryinfo.shiftid')
                // ->join('employee_basistype','employee_basicsalaryinfo.salarybasistype','=','employee_basistype.id')
                ->where('employee_basicsalaryinfo.employeeid',$teacherid)
                ->where('employee_basicsalaryinfo.deleted','0')
                // ->where('employee_basistype.deleted','0')
                ->first();
            // return $shiftid
            $attendancebasedstatus = Db::table('employee_basicsalaryinfo')
                ->select('employee_basicsalaryinfo.attendancebased')
                ->where('employee_basicsalaryinfo.employeeid',$teacherid)
                ->where('employee_basicsalaryinfo.deleted','0')
                ->first();

            if(count(collect($attendancebasedstatus)) == 0)
            {
                $attendancebased = 1;
                DB::table('employee_basicsalaryinfo')
                    ->where('employeeid', $teacherid)
                    ->update([
                        'attendancebased' => 1
                    ]);
            }else{
                $attendancebased = $attendancebasedstatus->attendancebased;
            }
        }

        // return $shiftid;

            
        if(count($timeschedule) == 0){
            Db::table('employee_customtimesched')
                ->insert([
                    'employeeid'    => $teacherid,
                    'amin'          => '08:00',
                    'amout'         => '12:00',
                    'pmin'          => '13:00',
                    'pmout'         => '17:00'
                ]);

            $timeschedule = Db::table('employee_customtimesched')
                ->where('employeeid',$teacherid)
                ->where('deleted','0')
                ->get();
        }   
        elseif(count($timeschedule) > 0){

            foreach($timeschedule as $timesched){

                foreach($timesched as $key => $value){

                    if($key == 'amin'){

                        if($value == null){

                            $timesched->amin = '00:00';

                        }

                    }
                    elseif($key == 'amout'){

                        if($value == null){

                            $timesched->amout = '00:00';

                        }

                    }
                    elseif($key == 'pmin'){

                        if($value == null){

                            $timesched->pmin = '00:00';

                        }else{
                            if(strtolower(date('A', strtotime($timesched->pmin))) == 'pm')
                            {
                                $timesched->pmin = date('h:i:s', strtotime($timesched->pmin));
                            }
                        }

                    }
                    elseif($key == 'pmout'){
                        if($value == null){
                            $timesched->pmout = '00:00';
                        }else{
                            if(strtolower(date('A', strtotime($timesched->pmout))) == 'pm')
                            {
                                $timesched->pmout = date('h:i:s', strtotime($timesched->pmout));
                            }
                        }

                    }
                }
            }
        }
        
        $designationid = null;
        $officeid = null;

        $selecteddesignations = array();

        $teacherinfo = DB::table('teacher')
            ->where('id', $teacherid)
            ->first();

        $departmentid = $teacherinfo->schooldeptid;

        $selecteddesignation = DB::table('usertype')
            ->where('id', $teacherinfo->usertypeid)
            ->first();

        if(count(collect($selecteddesignation)) > 0)
        {
            $designationid = $teacherinfo->usertypeid;

            $selectedoffice = DB::table('hr_school_department')
                ->where('id', $selecteddesignation->departmentid)
                ->where('deleted','0')
                ->first();

            if(count(collect($selectedoffice)) > 0)
            {
                $officeid = $selectedoffice->id;
                $selecteddesignations = Db::table('usertype')
                    ->where('departmentid', $officeid)
                    ->where('deleted','0')
                    ->where('utype','!=','PARENT')
                    ->where('utype','!=','STUDENT')
                    ->distinct()
                    ->get();
                    
                if(count($selecteddesignations)>0)
                {
                    foreach($selecteddesignations as $selecteddesignation)
                    {
                        if($selecteddesignation->id == $designationid)
                        {
                            $selecteddesignation->selected = '1';
                        }else{
                            $selecteddesignation->selected = '0';
                        }
                    }
                }else{

                    $selecteddesignations = Db::table('usertype')
                        ->where('deleted','0')
                        ->where('utype','!=','PARENT')
                        ->where('utype','!=','STUDENT')
                        ->distinct()
                        ->get();

                    foreach($selecteddesignations as $selecteddesignation)
                    {
                        if($selecteddesignation->id == $designationid)
                        {
                            $selecteddesignation->selected = '1';
                        }else{
                            $selecteddesignation->selected = '0';
                        }
                    }
                }
            }else{

                $selecteddesignations = Db::table('usertype')
                    ->where('deleted','0')
                    ->where('utype','!=','PARENT')
                    ->where('utype','!=','STUDENT')
                    ->distinct()
                    ->get();

                foreach($selecteddesignations as $selecteddesignation)
                {
                    if($selecteddesignation->id == $designationid)
                    {
                        $selecteddesignation->selected = '1';
                    }else{
                        $selecteddesignation->selected = '0';
                    }
                }

            }
            
        }
        // return $designationid;


        $offices = DB::table('hr_school_department')
            ->where('deleted','0')
            ->orderBy('department','asc')
            ->get();

        $departments = DB::table('hr_departments')
            ->where('deleted','0')
            ->orderBy('department','asc')
            ->get();
        
        $tardinesssetup = DB::table('deduction_tardinesssetup')
            ->where('status','1')
            ->get();
            
            
        return view('hr.employeeprofile.others')
            ->with('profileinfoid',$teacherid)
            ->with('shiftid',$shiftid)
            ->with('timeschedule',$timeschedule)
            ->with('attendancebased',$attendancebased)
            ->with('offices',$offices)
            ->with('departments',$departments)
            ->with('departmentid',$departmentid)
            ->with('designationid',$designationid)
            ->with('officeid',$officeid)
            ->with('selecteddesignations',$selecteddesignations)
            ->with('tardinesssetup',$tardinesssetup);
    }
    // public function getdesignations(Request $request)
    // {
    //     $selecteddesignations = Db::table('usertype')
    //         ->where('departmentid',$request->get('officeid'))
    //         ->where('deleted','0')
    //         ->where('utype','!=','PARENT')
    //         ->where('utype','!=','STUDENT')
    //         ->get();

    //     return $selecteddesignations;
    // }
    public function updatedesignation(Request $request)
    {
        DB::table('teacher')
            ->where('id', $request->get('employeeid'))
            ->update([
                'usertypeid'    => $request->get('selecteddesignation')
            ]);
    }
    public function updatedepartment(Request $request)
    {
        DB::table('teacher')
            ->where('id', $request->get('employeeid'))
            ->update([
                'schooldeptid'    => $request->get('selecteddepartment')
            ]);
    }
    public function employeeworkshift(Request $request)
    {
        
        DB::table('employee_basicsalaryinfo')
            ->where('employeeid', $request->get('employeeid'))
            ->update([
                'shiftid'   => $request->get('shiftid')
            ]);
    }
    public function employeecustomtimesched(Request $request){
        // return $request->all();
        
        date_default_timezone_set('Asia/Manila');
        
        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->first();
            
        
        $checkifexists = Db::table('employee_customtimesched')
            ->where('employeeid',$request->get('employeeid'))
            ->where('deleted','0')
            ->get();
        if(count($checkifexists) == 0){

                DB::table('employee_customtimesched')
                    ->insert([
                        'employeeid'    => $request->get('employeeid'),
                        'amin'          => $request->get('am_in'),
                        'amout'        => $request->get('am_out'),
                        'pmin'          => date('H:i:s',strtotime($request->get('pm_in').' PM')),
                        'pmout'         => date('H:i:s',strtotime($request->get('pm_out').' PM')),
                        'createdby'     => $getMyid->id,
                        'createdon'     =>  date('Y-m-d H:i:s')
                    ]);

        }else{
            
            $explodeamin = explode(':', $request->get('am_in'));
            
            if($explodeamin[0] == '00')
            {
                $amin = null;
            }else{
                $amin = $request->get('am_in');
            }

            $explodeamout = explode(':', $request->get('am_out'));
            if($explodeamout[0] == '00')
            {
                $amout = null;
            }else{
                $amout = $request->get('am_out');
            }

            $explodepmin = explode(':', $request->get('pm_in'));
            if($explodepmin[0] == '00')
            {
                $pmin = null;
            }else{
                $pmin = $request->get('pm_in');
            }

            $explodepmout = explode(':', $request->get('pm_out'));
            if($explodepmout[0] == '00')
            {
                $pmout = null;
            }else{
                $pmout = $request->get('pm_out');
            }

            DB::table('employee_customtimesched')
                ->where('employeeid', $request->get('employeeid'))
                ->update([
                    'amin'              => $amin,
                    'amout'             => $amout,
                    'pmin'              => date('H:i:s',strtotime($pmin.' PM')),
                    'pmout'             => date('H:i:s',strtotime($pmout.' PM'))
                    // 'updatedby'         => auth()->user()->id,
                    // 'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

        }
    }
    public function employeeattendancebasedsalary(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        // return $request->all();
        $teacherid = $request->get('employeeid');
        // attendancebasedstatus = 0 === no  || attendancebasedstatus = 1 === yes
        DB::table('employee_basicsalaryinfo')
            ->where('employeeid', $teacherid)
            ->update([
                'attendancebased'   => $request->get('attendancebasedstatus'),
                'updatedby'         => auth()->user()->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
}
