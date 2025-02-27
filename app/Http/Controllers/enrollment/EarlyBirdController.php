<?php

namespace App\Http\Controllers\enrollment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Hash;
class EarlyBirdController extends Controller
{
    public function index(){
        $schoolyears = DB::table('sy')
                        ->get();
        $semesters   = DB::table('semester')
                        ->get();
        $gradelevels = DB::table('gradelevel')
                        ->where('deleted','0')
                        ->orderBy('sortid','asc')
                        ->get();

        $religions = db::table('religion')
                        ->where('deleted', 0)
                        ->get();

        $mothertongues = db::table('mothertongue')
                        ->where('deleted', 0)
                        ->get();

        $ethnics = db::table('ethnic')
                        ->where('deleted', 0)
                        ->get();

        $mols = db::table('modeoflearning')
                        ->where('deleted', 0)
                        ->get();

        $nationalities = db::table('nationality')
                        ->where('deleted', 0)
                        ->orderBy('nationality', 'ASC')
                        ->get();

        $grantees = db::table('grantee')
                        // ->where('deleted', 0)
                        ->get();

        return view('enrollment.earlybirds.index')
            ->with('schoolyears',$schoolyears)
            ->with('semesters',$semesters)
            ->with('gradelevels',$gradelevels)
            ->with('religions',$religions)
            ->with('mothertongues',$mothertongues)
            ->with('ethnics',$ethnics)
            ->with('mols',$mols)
            ->with('nationalities',$nationalities)
            ->with('grantees',$grantees);

    }
    public function getotherfilter(Request $request)
    {
        // return $request->all();
        $acadprogcode = DB::table('gradelevel')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.id', $request->get('selectedgradelevel'))
            ->first()->acadprogcode;

        $courses = array();
        $strands = array();

        if(strtolower($acadprogcode) == 'college')
        {  
            $courses = DB::table('college_courses')
                ->where('deleted','0')
                ->get();
 
        }
        elseif(strtolower($acadprogcode) == 'shs')
        {
            $strands = DB::table('sh_strand')
                ->where('active','1')
                ->where('deleted','0')
                ->get();

        }

        return collect([
            'courses'   => $courses,
            'strands'   => $strands
        ]);
    }
    public function generatefilter(Request $request)
    {
        // return $request->all();
        $selectedschoolyear     =   $request->get('selectedschoolyear');
        $selectedsemester       =   $request->get('selectedsemester');
        $selectedgradelevel     =   $request->get('selectedgradelevel');
        $selectedstrand         =   $request->get('selectedstrand');
        $selectedcourse         =   $request->get('selectedcourse');

        $acadprogcode               = DB::table('gradelevel')
                                        ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                                        ->where('gradelevel.id', $selectedgradelevel)
                                        ->first()->acadprogcode;
                                        
        $earlybirds             =   DB::table('earlybirds')
                                    ->select('earlybirds.*','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','gradelevel.levelname')
                                    ->join('studinfo','earlybirds.studid','=','studinfo.id')
                                    ->join('gradelevel','earlybirds.levelid','=','gradelevel.id')
                                    ->where('earlybirds.syid', $selectedschoolyear)
                                    ->where('earlybirds.levelid', $selectedgradelevel)
                                    ->where('earlybirds.deleted','0')
                                    ->get();
                       
        if(count($earlybirds)>0)
        {
            foreach($earlybirds as $student)
            {
                
                $student->strandname = null;
                $student->coursename = null;
                $student->strandcode = null;

                $name_showfirst = "";

                $name_showfirst.=$student->firstname.' ';

                if($student->middlename != null)
                {
                    $name_showfirst.=$student->middlename[0].'. ';
                }
                $name_showfirst.=$student->lastname.' ';
                $name_showfirst.=$student->suffix.' ';

                $student->name_showfirst = strtoupper($name_showfirst);

                $name_showlast = "";

                $name_showlast.=$student->lastname.', ';
                $name_showlast.=$student->firstname.' ';

                if($student->middlename != null)
                {
                    $name_showlast.=$student->middlename[0].'. ';
                }
                $name_showlast.=$student->suffix.' ';

                $student->name_showlast = strtoupper($name_showlast);

                // $options.='<option value="'.$student->id.'">'.$name_showlast.'</option>';
            }
        }             
        if(strtolower($acadprogcode) == 'college')
        {
            $earlybirds         = collect($earlybirds)->where('semid',$selectedsemester)->where('courseid', $selectedcourse)->all();
     
            if(count($earlybirds)>0)
            {
                foreach($earlybirds as $student)
                {
                    $student->coursename = DB::table('college_courses')
                        ->where('id', $student->courseid)
                        ->first()->courseabrv;
                }
            }
        }
        elseif(strtolower($acadprogcode) == 'shs')
        {
            $earlybirds         = collect($earlybirds)->where('semid',$selectedsemester)->where('strandid', $selectedstrand)->all();
     
            if(count($earlybirds)>0)
            {
                foreach($earlybirds as $student)
                {
                    $student->strandcode = DB::table('sh_strand')
                        ->where('id', $student->strandid)
                        ->first()->strandcode;
                }
            }
        }
        // return $earlybirds;
        return view('enrollment.earlybirds.blade_results')
            ->with('earlybirds', $earlybirds);

    }
    public function getstudents(Request $request)
    {
        // if($request->ajax())
        // {
            $students = DB::table('studinfo')
                ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus')
                ->where('deleted','0')
                // ->where('userid','!=',null)
                ->orderBy('lastname','asc')
                ->get();
            
            $options = '';
    
            if(count($students)>0)
            {
                foreach($students as $student)
                {
                    
                    $name_showfirst = "";
    
                    $name_showfirst.=$student->firstname.' ';
    
                    if($student->middlename != null)
                    {
                        $name_showfirst.=$student->middlename[0].'. ';
                    }
                    $name_showfirst.=$student->lastname.' ';
                    $name_showfirst.=$student->suffix.' ';
    
                    $student->name_showfirst = strtoupper($name_showfirst);
    
                    $name_showlast = "";
    
                    $name_showlast.=$student->lastname.', ';
                    $name_showlast.=$student->firstname.' ';
    
                    if($student->middlename != null)
                    {
                        $name_showlast.=$student->middlename[0].'. ';
                    }
                    $name_showlast.=$student->suffix.' ';
    
                    $student->name_showlast = strtoupper($name_showlast);
    
                    // $options.='<option value="'.$student->id.'">'.$name_showlast.'</option>';
                }
            }
            $schoolyears = DB::table('sy')
                            ->get();
            $semesters   = DB::table('semester')
                            ->get();
            $gradelevels = DB::table('gradelevel')
                            ->where('deleted','0')
                            ->orderBy('sortid','asc')
                            ->get();
            
            return view('enrollment.earlybirds.blade_addstudent')
                ->with('schoolyears',$schoolyears)
                ->with('semesters',$semesters)
                ->with('gradelevels',$gradelevels)
                ->with('students',$students);
        // }
    }
    public function getstudinfo(Request $request)
    {
        // return $request->all();
        $studentid = $request->get('studid');
        $semid = $request->get('semid');

        $currentlevelid = DB::table('studinfo')
                        ->select('gradelevel.*')
                        ->where('studinfo.id', $studentid)
                        ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                        ->first();
                
        if($currentlevelid)
        {
            if($currentlevelid->acadprogid == '5')
            {
                $enrollcourseid = 0;
                $sh_enrolledinfo = DB::table('sh_enrolledstud')
                    ->where('studid', $studentid)
                    ->where('levelid', $currentlevelid->id)
                    ->where('deleted','0')
                    ->get();

                if(collect($sh_enrolledinfo)->where('semid', 1)->count()>0 && collect($sh_enrolledinfo)->where('semid', 2)->count()>0)
                {
                    $enrolllevelid = DB::table('gradelevel')
                                ->where('deleted','0')
                                ->where('sortid',($currentlevelid->sortid+1))
                                ->first();
                }else{
                    $enrolllevelid = DB::table('gradelevel')
                                ->where('deleted','0')
                                ->where('sortid',$currentlevelid->sortid)
                                ->first();
                }
                if(count($sh_enrolledinfo)>0)
                {
                    $enrollstrandid = $sh_enrolledinfo[0]->strandid;
                }else{
                    $enrollstrandid = 0;
                }
            }
            elseif($currentlevelid->acadprogid == '6')
            {
                $enrollstrandid = 0;
                $college_enrolledinfo = DB::table('college_enrolledstud')
                    ->where('studid', $studentid)
                    ->whereIn('studstatus', [1,2,4])
                    // ->where('yearLevel', $currentlevelid->id)
                    ->where('deleted','0')
                    ->get();

                if(collect($college_enrolledinfo)->where('semid', 1)->count()>0 && collect($college_enrolledinfo)->where('semid', 2)->count()>0)
                {
                    $enrolllevelid = DB::table('gradelevel')
                                ->where('deleted','0')
                                ->where('sortid',($currentlevelid->sortid+1))
                                ->first();
                }else{
                    $enrolllevelid = DB::table('gradelevel')
                                ->where('deleted','0')
                                ->where('sortid',$currentlevelid->sortid)
                                ->first();
                }
                if(count($college_enrolledinfo)>0)
                {
                    $enrollcourseid = $college_enrolledinfo[0]->courseid;
                }else{
                    $enrollcourseid = 0;
                }
            }else{
                $enrolllevelid = DB::table('gradelevel')
                            ->where('deleted','0')
                            ->where('sortid',($currentlevelid->sortid+1))
                            ->first();
                $enrollcourseid = 0;
                $enrollstrandid = 0;
            }
            if($enrolllevelid)
            {
                $enrolllevelid = $enrolllevelid->id;
            }else{
                $enrolllevelid = 0;
            }

            $acadprogid = $currentlevelid->acadprogid;
        }else{
            $enrolllevelid = 0;
            $enrollstrandid = 0;
            $enrollcourseid = 0;
            $acadprogid = 0;
        }

        return json_encode (collect([
            'enrolllevelid'          =>  $enrolllevelid,
            'enrollstrandid'         =>  $enrollstrandid,
            'enrollcourseid'         =>  $enrollcourseid,
            'acadprogid'             =>  $acadprogid
        ]));

    }
    public function addstudent(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $selectedstudent            =   $request->get('selectedstudent');
        $selectedstudentsy          =   $request->get('selectedstudentsy');
        $selectedstudentsem         =   $request->get('selectedstudentsem');
        $selectedstudentlevel       =   $request->get('selectedstudentlevel');
        $selectedstudentstrand      =   $request->get('selectedstudentstrand');
        $selectedstudentcourse      =   $request->get('selectedstudentcourse');

        $acadprogcode               = DB::table('gradelevel')
                                        ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                                        ->where('gradelevel.id', $selectedstudentlevel)
                                        ->first()->acadprogcode;

        if(strtolower($acadprogcode) == 'college')
        {
            $checkifexists              = Db::table('earlybirds')
                                         ->where('studid',$selectedstudent)
                                         ->where('syid', $selectedstudentsy)
                                         ->where('semid', $selectedstudentsem)
                                         ->where('deleted','0')
                                         ->count();
        }
        elseif(strtolower($acadprogcode) == 'shs')
        {
            $checkifexists              = Db::table('earlybirds')
                                         ->where('studid',$selectedstudent)
                                         ->where('syid', $selectedstudentsy)
                                         ->where('semid', $selectedstudentsem)
                                         ->where('deleted','0')
                                         ->count();
        }else{
            $selectedstudentsem         = null;
            $checkifexists              = Db::table('earlybirds')
                                         ->where('studid',$selectedstudent)
                                         ->where('syid', $selectedstudentsy)
                                        //  ->where('semid', $selectedstudentsem)
                                         ->where('deleted','0')
                                         ->count();
        }
        if($checkifexists == 0)
        {

            try{

                DB::table('earlybirds')
                    ->insert([
                        'studid'            => $selectedstudent,
                        'syid'              => $selectedstudentsy,
                        'semid'             => $selectedstudentsem,
                        'levelid'           => $selectedstudentlevel,
                        'strandid'          => $selectedstudentstrand,
                        'courseid'          => $selectedstudentcourse,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
                    return 1;

            }catch(\Exception $e)
            {
                return 'Something went wrong!';
            }

        }else{
            return 0;
        }
    }
    public function createstudent(Request $request)
    {
        // return $request->all();

        $fname              = $request->get('firstname');
        $mname              = $request->get('middlename');
        $lname              = $request->get('lastname');
        $suffix             = $request->get('suffix');

        $dob                = $request->get('dob');
        $gender             = $request->get('gender');
        $contactno          = $request->get('contactno');

        $checkifexists      = DB::table('studinfo')
                            ->where('lastname','like','%'.$lname.'%')
                            ->where('firstname','like','%'.$fname.'%')
                            ->where('dob','=',$dob)
                            // ->where('userid','!=',null)
                            ->where('deleted','0')
                            ->count();

        if($checkifexists == 0)
        {
            $street             = $request->get('street');
            $barangay           = $request->get('barangay');
            $city               = $request->get('city');
            $province           = $request->get('province');
            $bloodtype          = $request->get('bloodtype');
            $allergy            = $request->get('allergies');
    
            $religion           = $request->get('religion');
            $mt                 = $request->get('mothertongue');
            $eg                 = $request->get('ethnicgroup');
            $nationality        = $request->get('nationality');
    
            $fathername         = $request->get('fathername');
            $foccupation        = $request->get('foccupation');
            $fcontactno         = $request->get('fcontactno');
            $mothername         = $request->get('mothername');
            $moccupation        = $request->get('moccupation');
            $mcontactno         = $request->get('mcontactno');
            $guardianname       = $request->get('guardianname');
            $guardianrelation   = $request->get('guardianrelation');
            $gcontactno         = $request->get('gcontactno');
    
            $isfather           = 0;
            $ismother           = 0;
            $isguardian         = 0;
    
            if($request->get('whotocontact') == 'f')
            {
                $isfather = 1;
            }elseif($request->get('whotocontact') == 'm')
            {
                $ismother = 1;
            }elseif($request->get('whotocontact') == 'g')
            {
                $isguardian = 1;
            }
            
            $lrn                = $request->get('lrn');
            $rfid               = $request->get('rfid');
            $grantee            = $request->get('grantee');
            $glevel             = $request->get('levelid');
            // $others             = $request->get('others');
            $mol                = $request->get('mol');
            $lastschool         = $request->get('schoollastatt');
            $lastschoolsy       = $request->get('schoolyearlastatt');
    
    
            $studtype           = $request->get('studtype');
            $pantawid           = $request->get('pantawid');
    
            try{
                $studid             = DB::table('studinfo')
                                    ->insertGetId([
                                        'lrn'               => $lrn,
                                        'grantee'           => $grantee,
                                        'levelid'           => $glevel,
                                        'firstname'         => $fname,
                                        'middlename'        => $mname,
                                        'lastname'          => $lname,
                                        'suffix'            => $suffix,
                                        'dob'               => $dob,
                                        'gender'            => $gender,
                                        'contactno'         => $contactno,
                                        'religionid'        => $religion,
                                        'mtid'              => $mt,
                                        'egid'              => $eg,
                                        'street'            => $street,
                                        'barangay'          => $barangay,
                                        'city'              => $city,
                                        'province'          => $province,
                                        'fathername'        => $fathername,
                                        'foccupation'       => $foccupation,
                                        'fcontactno'        => $fcontactno,
                                        'isfathernum'       => $isfather,
                                        'mothername'        => $mothername,
                                        'moccupation'       => $moccupation,
                                        'mcontactno'        => $mcontactno,
                                        'ismothernum'       => $ismother,
                                        'guardianname'      => $guardianname,
                                        'guardianrelation'  => $guardianrelation,
                                        'gcontactno'        => $gcontactno,
                                        'isguardannum'      => $isguardian,
                                        'bloodtype'         => $bloodtype,
                                        'allergy'           => $allergy,
                                        // 'others'            => $others,
                                        'rfid'              => $rfid,
                                        'mol'               => $mol,
                                        'nationality'       => $nationality,
                                        'lastschoolatt'     => $lastschool,
                                        'lastschoolsy'      => $lastschoolsy,
                                        'deleted'           => 0,
                                        'studtype'          => $studtype,
                                        'pantawid'          => $pantawid
                                    ]);
        
                $idprefix = db::table('idprefix')->first();
                
                $id = sprintf('%06d', $studid);
        
                $sid = $idprefix->prefix . $id;
        
                // $newuserid = DB::table('users')
                //     ->insertGetId([
                //         'name'                  =>  strtolower($lname).', '.strtolower($fname),
                //         'email'                 =>  'S'.$sid,
                //         'type'                  =>  7,
                //         'deleted'               =>  '0',
                //         'password'              =>  Hash::make('123456')
                //     ]);
                    
                $upd = db::table('studinfo')
                    ->where('id', $studid)
                    ->update([
                        // 'userid'                => $newuserid,
                        'sid' => $sid
                    ]);
    
                return collect([
                    'id'    => $studid,
                    'lastname'  => $lname,
                    'firstname'  => $fname,
                    'levelid'  => $glevel
                ]);
            }catch(\Exception $error)
            {
                return 0;
            }
        }else{
            return 1;
        }
    }
    public function delete(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        DB::table('earlybirds')
            ->where('id', $request->get('id'))
            ->update([
                'deleted'           => 1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function apiregister_checkifexists(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $selectedstudent            =   $request->get('selectedstudent');
        $selectedstudentsy          =   $request->get('selectedstudentsy');
        $selectedstudentsem         =   $request->get('selectedstudentsem');
        $selectedstudentlevel       =   $request->get('selectedstudentlevel');
        $selectedstudentstrand      =   $request->get('selectedstudentstrand');
        $selectedstudentcourse      =   $request->get('selectedstudentcourse');
        $acadprogcode               = DB::table('gradelevel')
                                        ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                                        ->where('gradelevel.id', $selectedstudentlevel)
                                        ->first()->acadprogcode;
        $createdby                  =   $request->get('createdby');
        
        if(strtolower($acadprogcode) == 'college')
        {
            $checkifexists              = Db::table('earlybirds')
                                         ->where('studid',$selectedstudent)
                                         ->where('syid', $selectedstudentsy)
                                         ->where('semid', $selectedstudentsem)
                                         ->where('deleted','0')
                                         ->count();
        }
        elseif(strtolower($acadprogcode) == 'shs')
        {
            $checkifexists              = Db::table('earlybirds')
                                         ->where('studid',$selectedstudent)
                                         ->where('syid', $selectedstudentsy)
                                         ->where('semid', $selectedstudentsem)
                                         ->where('deleted','0')
                                         ->count();
        }else{
            $selectedstudentsem         = null;
            $checkifexists              = Db::table('earlybirds')
                                         ->where('studid',$selectedstudent)
                                         ->where('syid', $selectedstudentsy)
                                        //  ->where('semid', $selectedstudentsem)
                                         ->where('deleted','0')
                                         ->count();
        }
        
        if($checkifexists == 0)
        {

            try{

                DB::table('earlybirds')
                    ->insert([
                        'studid'            => $selectedstudent,
                        'syid'              => $selectedstudentsy,
                        'semid'             => $selectedstudentsem,
                        'levelid'           => $selectedstudentlevel,
                        'strandid'          => $selectedstudentstrand,
                        'courseid'          => $selectedstudentcourse,
                        'createdby'         => $createdby,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
                    return 1;

            }catch(\Exception $e)
            {
                return 'Something went wrong!';
            }

        }else{
            return 0;
        }
    }
    public function apicreatestud_checkifexists(Request $request)
    {
        // return $request->all();

        $fname              = $request->get('firstname');
        $mname              = $request->get('middlename');
        $lname              = $request->get('lastname');
        $suffix             = $request->get('suffix');

        $dob                = $request->get('dob');
        $gender             = $request->get('gender');
        $contactno          = $request->get('contactno');

        $checkifexists      = DB::table('studinfo')
                            ->where('lastname','like','%'.$lname.'%')
                            ->where('firstname','like','%'.$fname.'%')
                            ->where('dob','=',$dob)
                            ->where('deleted','0')
                            ->count();

        if($checkifexists == 0)
        {
            $street             = $request->get('street');
            $barangay           = $request->get('barangay');
            $city               = $request->get('city');
            $province           = $request->get('province');
            $bloodtype          = $request->get('bloodtype');
            $allergy            = $request->get('allergies');
    
            $religion           = $request->get('religion');
            $mt                 = $request->get('mothertongue');
            $eg                 = $request->get('ethnicgroup');
            $nationality        = $request->get('nationality');
    
            $fathername         = $request->get('fathername');
            $foccupation        = $request->get('foccupation');
            $fcontactno         = $request->get('fcontactno');
            $mothername         = $request->get('mothername');
            $moccupation        = $request->get('moccupation');
            $mcontactno         = $request->get('mcontactno');
            $guardianname       = $request->get('guardianname');
            $guardianrelation   = $request->get('guardianrelation');
            $gcontactno         = $request->get('gcontactno');
    
            $isfather           = 0;
            $ismother           = 0;
            $isguardian         = 0;
    
            if($request->get('whotocontact') == 'f')
            {
                $isfather = 1;
            }elseif($request->get('whotocontact') == 'm')
            {
                $ismother = 1;
            }elseif($request->get('whotocontact') == 'g')
            {
                $isguardian = 1;
            }
            
            $lrn                = $request->get('lrn');
            $rfid               = $request->get('rfid');
            $grantee            = $request->get('grantee');
            $glevel             = $request->get('levelid');
            // $others             = $request->get('others');
            $mol                = $request->get('mol');
            $lastschool         = $request->get('schoollastatt');
            $lastschoolsy       = $request->get('schoolyearlastatt');
    
    
            $studtype           = $request->get('studtype');
            $pantawid           = $request->get('pantawid');
    
            try{
                $studid             = DB::table('studinfo')
                                    ->insertGetId([
                                        'lrn'               => $lrn,
                                        'grantee'           => $grantee,
                                        'levelid'           => $glevel,
                                        'firstname'         => $fname,
                                        'middlename'        => $mname,
                                        'lastname'          => $lname,
                                        'suffix'            => $suffix,
                                        'dob'               => $dob,
                                        'gender'            => $gender,
                                        'contactno'         => $contactno,
                                        'religionid'        => $religion,
                                        'mtid'              => $mt,
                                        'egid'              => $eg,
                                        'street'            => $street,
                                        'barangay'          => $barangay,
                                        'city'              => $city,
                                        'province'          => $province,
                                        'fathername'        => $fathername,
                                        'foccupation'       => $foccupation,
                                        'fcontactno'        => $fcontactno,
                                        'isfathernum'       => $isfather,
                                        'mothername'        => $mothername,
                                        'moccupation'       => $moccupation,
                                        'mcontactno'        => $mcontactno,
                                        'ismothernum'       => $ismother,
                                        'guardianname'      => $guardianname,
                                        'guardianrelation'  => $guardianrelation,
                                        'gcontactno'        => $gcontactno,
                                        'isguardannum'      => $isguardian,
                                        'bloodtype'         => $bloodtype,
                                        'allergy'           => $allergy,
                                        // 'others'            => $others,
                                        'rfid'              => $rfid,
                                        'mol'               => $mol,
                                        'nationality'       => $nationality,
                                        'lastschoolatt'     => $lastschool,
                                        'lastschoolsy'      => $lastschoolsy,
                                        'deleted'           => 0,
                                        'studtype'          => $studtype,
                                        'pantawid'          => $pantawid,
                                        'createdby'         => $request->get('createdby'),
                                        'createddatetime'   => date('Y-m-d H:i:s')
                                    ]);
        
                $idprefix = db::table('idprefix')->first();
                
                $id = sprintf('%06d', $studid);
        
                $sid = $idprefix->prefix . $id;
        
                // $newuserid = DB::table('users')
                //     ->insertGetId([
                //         'name'                  =>  strtolower($lname).', '.strtolower($fname),
                //         'email'                 =>  'S'.$sid,
                //         'type'                  =>  7,
                //         'deleted'               =>  '0',
                //         'password'              =>  Hash::make('123456')
                //     ]);
                    
                $upd = db::table('studinfo')
                    ->where('id', $studid)
                    ->update([
                        // 'userid'                => $newuserid,
                        'sid' => $sid
                    ]);
    
                return collect([
                    'id'    => $studid,
                    'lastname'  => $lname,
                    'firstname'  => $fname,
                    'levelid'  => $glevel
                ]);
            }catch(\Exception $error)
            {
                return 0;
            }
        }else{
            return 1;
        }
    }
}
