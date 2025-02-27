<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class ScholarshipController extends Controller
{
    public function index()
    {
        $programs = DB::table('scholarshipprog')
            ->where('deleted','0')
            ->get();

        $gradelevels = Db::table('gradelevel')
            ->where('deleted','0')
            ->orderBy('sortid','ASC')
            ->get();

        $students = Db::table('studinfo')
            ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','gradelevel.levelname')
            ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
            ->where('studinfo.deleted','0')
            ->whereIn('studinfo.studstatus',[1,2,4])
            ->orderBy('lastname')
            ->get();

        foreach($students as $student)
        {
            $scholarships = Db::table('scholarshipstud')
                ->select('scholarshipprog.id','scholarshipprog.program')
                ->where('studid',$student->id)
                ->where('scholarshipstud.deleted','0')
                ->join('scholarshipprog','scholarshipstud.progid','=','scholarshipprog.id')
                ->where('scholarshipprog.deleted','0')
                ->get();

            $student->scholarships = $scholarships;
            
        }

        $schoolyears = DB::table('sy')
            ->get();

        $semesters = DB::table('semester')
            ->get();

        return view('registrar.scholars.index')
            ->with('gradelevels',$gradelevels)
            ->with('schoolyears',$schoolyears)
            ->with('semesters',$semesters)
            ->with('programs',$programs)
            ->with('students',$students);
    }
    public function programstudents(Request $request)
    {
        // return $request->all();
        if($request->ajax())
        {
            $syid = DB::table('sy')
                ->where('isactive','1')
                ->first()->id;
    
            $semid = DB::table('semester')
                ->where('isactive','1')
                ->first()->id;

            $students = Db::table('studinfo')
                ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','gradelevel.id as levelid','gradelevel.levelname')
                ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                ->where('studinfo.deleted','0')
                // ->where('studinfo.levelid',$request->get('levelid'))
                ->whereIn('studinfo.studstatus',[1,2,4])
                ->orderBy('lastname')
                ->get();

            if($request->get('levelid') != null)
            {
                $students = collect($students)->where('levelid', $request->get('levelid'));
            }

            $filteredstudents = array();
    
            foreach($students as $student)
            {
                $scholarships = Db::table('scholarshipstud')
                    ->select('scholarshipprog.id','scholarshipprog.program','scholarshipprog.abbreviation','scholarshipstud.id as progstudid','scholarshipstud.amount')
                    ->where('studid',$student->id)
                    ->where('scholarshipstud.deleted','0')
                    ->where('scholarshipstud.syid',$syid)
                    ->where('scholarshipstud.semid',$semid)
                    ->join('scholarshipprog','scholarshipstud.progid','=','scholarshipprog.id')
                    ->where('scholarshipprog.deleted','0')
                    ->get();
                    
                $student->scholarships = $scholarships;
                
                if($request->has('programs'))
                {
                    if(count($student->scholarships)>0)
                    {
                        $count = 0;

                        foreach($request->get('programs') as $program)
                        {
                            if (collect($student->scholarships)->contains('id', $program)) {
                                $count+=1;
                            }
                        }
                        if($count>0)
                        {
                            array_push($filteredstudents, $student);
                        }
                    }
                }else{
                    array_push($filteredstudents, $student);
                }
            }
            return view('registrar.scholars.students')
                ->with('students', $filteredstudents);
        }
    }
    public function programselect(Request $request)
    {
        // return $request->all();
        $syid = DB::table('sy')
            ->where('isactive','1')
            ->first()->id;

        $semid = DB::table('semester')
            ->where('isactive','1')
            ->first()->id;

        $scholarships = Db::table('scholarshipprog')
            ->where('deleted','0')
            ->get();

        if(count($scholarships)>0)
        {
            foreach($scholarships as $scholarship)
            {
                $checkifgranted = Db::table('scholarshipstud')
                    ->where('studid', $request->get('id'))
                    ->where('progid', $scholarship->id)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('deleted','0')
                    ->first();

                
                if($checkifgranted)
                {
                    $scholarship->granted = 1;
                    $scholarship->type = $checkifgranted->type;
                    $scholarship->amount  = $checkifgranted->amount;
                }else{
                    $scholarship->granted = 0;
                    $scholarship->type = 0;
                    $scholarship->amount  = 0.00;
                }
            }
        }
                
        return view('registrar.scholars.selectscholarship')
            ->with('scholarships', $scholarships)
            ->with('studentid', $request->get('id'));
    }
    public function programsubmitselect(Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');

        $granted = Db::table('scholarshipstud')
            ->where('studid', $request->get('id'))
            ->where('deleted','0')
            ->get();

        $syid = $request->get('syid');

        $semid = $request->get('semid');

        $scholarships = json_decode($request->get('scholarships'));
        
        if(count($scholarships)>0)
        {
            
            foreach($scholarships as $key => $scholarship)
            {
                $check = collect($granted)->contains('progid', $scholarship->scholarshipid);
                
                if($check)
                {
                    DB::table('scholarshipstud')
                        ->where('studid', $request->get('id'))
                        ->where('deleted', 0)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('progid', $scholarship->scholarshipid)
                        ->update([
                            'amount'            => $scholarship->amount,
                            'type'              => $scholarship->type,
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                        
                }
                else{
                    DB::table('scholarshipstud')
                        ->insert([
                            'studid'            => $request->get('id'),
                            'progid'            => $scholarship->scholarshipid,
                            'syid'              => $syid,
                            'semid'             => $semid,
                            'amount'            => $scholarship->amount,
                            'type'              => $scholarship->type,
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }

        $granted = Db::table('scholarshipstud')
            ->where('studid', $request->get('id'))
            ->where('deleted','0')
            ->get();
        // return $granted;
        if(count($granted)>0)
        {
            foreach($granted as $grant)
            {
                if(count($scholarships)>0)
                {
                    if (collect($scholarships)->where('id', $grant->progid)->count() > 0) {

                        DB::table('scholarshipstud')
                            ->where('studid', $request->get('id'))
                            ->where('deleted', 0)
                            ->where('syid', $syid)
                            ->where('semid', $semid)
                            ->where('progid', $grant->progid)
                            ->update([
                                'deleted'           => 1,
                                'deletedby'         => auth()->user()->id,
                                'deleteddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }else{
                    DB::table('scholarshipstud')
                        ->where('studid', $request->get('id'))
                        ->where('deleted', 0)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('progid', $grant->progid)
                        ->update([
                            'deleted'           => 1,
                            'deletedby'         => auth()->user()->id,
                            'deleteddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
    }
    public function addprogram(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        if($request->ajax())
        {
            $checkifexists = Db::table('scholarshipprog')
                ->where('program','like','%'.$request->get('newprogram').'%')
                ->where('deleted','0')
                ->get();
    
            if(count($checkifexists)>0)
            {
                return 1;
            }else{
                try{
                    DB::table('scholarshipprog')
                        ->insert([
                            'program'           => strtoupper($request->get('newprogram')),
                            'abbreviation'      => strtoupper($request->get('abbreviation')),
                            'createdby'         => auth()->user()->id,
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
                }catch(\Exception $e)
                {
                    return '2';
                }

                
            }
        }
    }
    public function programname(Request $request)
    {
        if($request->ajax()){
            return collect(DB::table('scholarshipprog')
                ->where('id', $request->get('id'))
                ->first());
        }
    }
    public function programedit(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        $checkifexists = Db::table('scholarshipprog')
            ->where('program','like','%'.$request->get('programname').'%')
            ->where('deleted','0')
            ->get();

        if(count($checkifexists)>0)
        {
            foreach($checkifexists as $exists)
            {
                if($exists->id == $request->get('id'))
                {
                    DB::table('scholarshipprog')
                        ->where('id', $request->get('id'))
                        ->update([
                            'program'           => strtoupper($request->get('programname')),
                            'abbreviation'      => strtoupper($request->get('abbreviation')),
                            'fullamount'        => $request->get('fullamount'),
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                    return $request->get('id');
                }else{
                    return 'exists';
                }
            }
        }else{
            try{
                DB::table('scholarshipprog')
                    ->where('id', $request->get('id'))
                    ->update([
                        'program'           => strtoupper($request->get('programname')),
                        'abbreviation'      => strtoupper($request->get('abbreviation')),
                        'fullamount'        => $request->get('fullamount'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);

                return $request->get('id');
            }catch(\Exception $e)
            {
                return 'error';
            }

            
        }
    }
    public function programdelete(Request $request)
    {
        if($request->ajax())
        {
            try{
                DB::table('scholarshipprog')
                    ->where('id', $request->get('id'))
                    ->update([
                        'deleted'           => 1,
                        'deletedby'         => auth()->user()->id,
                        'deleteddatetime'   => date('Y-m-d H:i:s')
                    ]);

                return $request->get('id');
            }catch(\Exception $e)
            {
                return 'error';
            }

        }
    }
    public function filter(Request $request)
    {
        // return $request->all();
        if($request->ajax())
        {
            $syid = $request->get('syid');
    
            $semid = $request->get('semid');

            $programid = $request->get('programid');

            $students = Db::table('studinfo')
                ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','gradelevel.id as levelid','gradelevel.levelname')
                ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                ->where('studinfo.deleted','0')
                // ->where('studinfo.levelid',$request->get('levelid'))
                ->whereIn('studinfo.studstatus',[1,2,4])
                ->orderBy('lastname')
                ->get();

            if($request->get('levelid') != null)
            {
                $students = collect($students)->where('levelid', $request->get('levelid'));
            }

            $filteredstudents = array();
    
            foreach($students as $student)
            {
                $scholarships = Db::table('scholarshipstud')
                    ->select('scholarshipprog.id','scholarshipprog.program','scholarshipprog.abbreviation','scholarshipstud.id as progstudid','scholarshipstud.amount')
                    ->where('studid',$student->id)
                    ->where('scholarshipstud.deleted','0')
                    ->where('scholarshipstud.syid',$syid)
                    ->where('scholarshipstud.semid',$semid)
                    ->join('scholarshipprog','scholarshipstud.progid','=','scholarshipprog.id')
                    ->where('scholarshipprog.deleted','0')
                    ->get();

                $student->scholarships = $scholarships;

                if($programid == 0)
                {
                    array_push($filteredstudents, $student);
                }else{
                    if(collect($scholarships)->where('id', $programid)->count() > 0)
                    {
                        array_push($filteredstudents, $student);
                    }
                }
                
            }

            return view('registrar.scholars.students')
                ->with('students', $filteredstudents);
        }
    }
    public function getprogstud(Request $request)
    {
        $info = DB::table('scholarshipstud')
            ->where('id', $request->get('id'))
            ->first();

        return collect($info);
    }
    public function getamount(Request $request)
    {
        $fullamount = DB::table('scholarshipprog')
            ->where('id', $request->get('id'))
            ->first()->fullamount;

        if($fullamount>0)
        {
            if($request->get('type') == 1)
            {
                $amount = $fullamount;
            }elseif($request->get('type') == 2)
            {
                $amount = ($fullamount/2);
            }
        }else{
            $amount = 0.00;
        }

        return $amount;
    }
    public function updateamount(Request $request)
    {
        try{
            DB::table('scholarshipstud')
                ->where('id', $request->get('id'))
                ->update([
                    'amount'            => $request->get('amount'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }
    }
    public function deleteprogstud(Request $request)
    {
        try{
            DB::table('scholarshipstud')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }
    }
}
