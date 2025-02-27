<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
class SummaryStudentController extends Controller
{
    public function reportssummariesallstudents($id, Request $request){
        
        date_default_timezone_set('Asia/Manila');
        
        $academicprogram = Db::table('academicprogram')
            ->get();
            
        $gradelevels = Db::table('gradelevel')
            ->where('deleted','0')
            ->orderBy('sortid','asc')
            ->get();

        $schoolyears = DB::table('sy')
            ->orderByDesc('sydesc','isactive')
            ->get();

        $studentstatus = Db::table('studentstatus')
            // ->where('deleted','0')
            ->get();

        $modeoflearnings = Db::table('modeoflearning')
            ->where('deleted','0')
            ->get();
            
        $grantees = Db::table('grantee')
        ->get();
            // ->
            // ->get();
        if($id == 'dashboard'){
            
            // $schoolyears = $schoolyeartable->get();

            $students = array();

            $selectedstudenttype = 'all';

            // $selectedschoolyear = $schoolyeartable->select('id')->where('isactive',1)->first()->id;
            
            $enrolledstuds = DB::table('studinfo')
                ->select(
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'gradelevel.levelname'
                )
                ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                ->join('sy','enrolledstud.syid','=','sy.id')
                ->where('enrolledstud.studstatus','1')
                ->where('sy.isactive','1')
                ->get();

            if(count($enrolledstuds) > 0){

                foreach($enrolledstuds as $enrolledstud){

                    array_push($students, $enrolledstud);

                }

            }

            $shenrolledstuds = DB::table('studinfo')
                ->select(
                    'studinfo.lastname',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'gradelevel.levelname'
                )
                ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                ->join('sy','sh_enrolledstud.syid','=','sy.id')
                ->where('sh_enrolledstud.studstatus','1')
                ->where('sy.isactive','1')
                ->get();
                
            if(count($shenrolledstuds) > 0){

                foreach($shenrolledstuds as $shenrolledstud){

                    array_push($students, $shenrolledstud);

                }

            }

            $tracks = Db::table('sh_track')
                    ->where('deleted','0')
                    ->get();

            $strands = Db::table('sh_strand')
                    ->where('active','1')
                    ->where('deleted','0')
                    ->get();


            // return  $schoolyeartable->all();
            

            return view('registrar.summaries.summariesallstudents')
                ->with('selectedstudenttype', $selectedstudenttype)
                // ->with('selectedschoolyear', $selectedschoolyear)
                // ->with('selecteddate', $selecteddate)
                // ->with('selectedgradelevel', $selectedgradelevel)
                ->with('grantees', $grantees)
                ->with('modeoflearnings', $modeoflearnings)
                ->with('studentstatus', $studentstatus)
                ->with('academicprogram', $academicprogram)
                ->with('gradelevels', $gradelevels)
                ->with('schoolyears', $schoolyears)
                ->with('tracks', $tracks)
                ->with('strands', $strands)
                ->with('students', $students);

        }else{
            // return $request->all();
            
            if($id == 'filter'){

                $selectedschoolyear     = $request->get('selectedschoolyear');
                $selectedacadprog       = $request->get('selectedacadprog');
                $selectedstudenttype    = $request->get('studenttype');
                $selectedstudentstatus  = $request->get('selectedstudentstatus');
                $selecteddate           = $request->get('selecteddate'); 
                $selectedgender         = $request->get('selectedgender'); 
                $selectedgradelevel     = $request->get('selectedgradelevel');
                $selectedsection        = $request->get('selectedsection');
                $trackid                = $request->get('trackid');
                $strandid               = $request->get('strandid');
                $selectedmode           = $request->get('selectedmode');
                $selectedgrantee        = $request->get('selectedgrantee');

            }elseif($id == 'print'){

                $selectedschoolyear     = $request->get('forminputselectedschoolyear');
                $selectedacadprog       = $request->get('forminputselectedacadprog');
                $selectedstudenttype    = $request->get('forminputstudenttype');
                $selectedstudentstatus  = $request->get('forminputstudentstatus');
                $selecteddate           = $request->get('forminputselecteddate'); 
                $selectedgender         = $request->get('forminputselectedgender'); 
                $selectedgradelevel     = $request->get('forminputselectedgradelevel');
                $selectedsection        = $request->get('forminputselectedsection');
                $trackid                = $request->get('forminputtrackid');
                $strandid               = $request->get('forminputstrandid');
                $selectedmode           = $request->get('forminputselectedmode');
                $selectedgrantee        = $request->get('forminputselectedgrantee');
            }
            
            $students = array();
            
            // if($selectedstudentstatus == 'all'){
            if($selectedstudenttype == 'all'){

                $enrolledstuds = DB::table('studinfo')
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.studstatus',
                        'studinfo.studstatdate',
                        'studinfo.grantee',
                        'studinfo.mol',
                        'studentstatus.description as studentstatus',
                        'enrolledstud.dateenrolled',
                        'enrolledstud.sectionid',
                        'gradelevel.id as levelid',
                        'gradelevel.acadprogid',
                        'gradelevel.sortid',
                        'gradelevel.levelname'
                    )
                    ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                    ->leftJoin('studentstatus','studinfo.studstatus','=','studentstatus.id')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->join('sy','enrolledstud.syid','=','sy.id')
                    // ->where('enrolledstud.studstatus','1')
                    ->where('sy.id',$selectedschoolyear)
                    ->get();

                if(count($enrolledstuds) > 0){
            
                    foreach($enrolledstuds as $enrolledstud){
            
                        $enrolledstud->trackid = null;

                        $enrolledstud->strandid = null;

                        if($enrolledstud->middlename == null){
                            $enrolledstud->middlename = "";
                        }

                        if($enrolledstud->suffix == null){
                            $enrolledstud->suffix = "";
                        }


                        array_push($students, $enrolledstud);
            
                    }
            
                }
            
                $shenrolledstuds = DB::table('studinfo')
                    ->select(
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.studstatus',
                        'studinfo.studstatdate',
                        'studinfo.grantee',
                        'studinfo.mol',
                        'studentstatus.description as studentstatus',
                        'sh_enrolledstud.dateenrolled',
                        'sh_enrolledstud.strandid',
                        'sh_enrolledstud.sectionid',
                        'sh_strand.trackid',
                        'gradelevel.id as levelid',
                        'gradelevel.acadprogid',
                        'gradelevel.sortid',
                        'gradelevel.levelname'
                    )
                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                    ->leftJoin('studentstatus','studinfo.studstatus','=','studentstatus.id')
                    ->leftJoin('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->join('sy','sh_enrolledstud.syid','=','sy.id')
                    // ->where('sh_enrolledstud.studstatus','1')
                    ->where('sy.id',$selectedschoolyear)
                    ->get();
                    
                if(count($shenrolledstuds) > 0){
            
                    foreach($shenrolledstuds as $shenrolledstud){

                        if($shenrolledstud->middlename == null){
                            $shenrolledstud->middlename = "";
                        }

                        if($shenrolledstud->suffix == null){
                            $shenrolledstud->suffix = "";
                        }
            
                        array_push($students, $shenrolledstud);
            
                    }
            
                }

            }elseif($selectedstudenttype == 'new'){
            
                // return
                $enrolledstudnew = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.dob',
                        'studinfo.studstatus',
                        'studinfo.studstatdate',
                        'studinfo.grantee',
                        'studinfo.mol',
                        'studentstatus.description as studentstatus',
                        'enrolledstud.dateenrolled',
                        'enrolledstud.sectionid',
                        'gradelevel.id as levelid',
                        'gradelevel.acadprogid',
                        'gradelevel.sortid',
                        'gradelevel.levelname'
                    )
                    ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                    ->leftJoin('studentstatus','studinfo.studstatus','=','studentstatus.id')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->join('sy','enrolledstud.syid','=','sy.id')
                    // ->where('enrolledstud.studstatus','1')
                    ->where('sy.id',$selectedschoolyear)
                    ->get();
                    
                if(count($enrolledstudnew) > 0){
            
                    foreach($enrolledstudnew as $stud){
            
                        $stud->trackid = null;

                        $stud->strandid = null;

                        if($stud->middlename == null){
                            $stud->middlename = "";
                        }

                        if($stud->suffix == null){
                            $stud->suffix = "";
                        }

                        $studentinfo = Db::table('enrolledstud')
                            ->where('studid', $stud->id)
                            ->get();
                            
                        if(count($studentinfo) == 1){
                            $prereg = Db::table('preregistration')
                                ->where('last_name','like','%'.$stud->lastname)
                                ->where('first_name','like','%'.$stud->firstname)
                                ->where('middle_name','like','%'.$stud->middlename)
                                ->where('dob','like','%'.$stud->dob)
                                ->whereYear('date_created', date('Y'))
                                ->get();

                            // return $prereg;
                            if(count($prereg) == 1){
                                array_push($students, $stud);
                            }
                            // if()
                        }
                        elseif(count($studentinfo) == 1){
                            array_push($students, $stud);
            
                        }
            
                    }

                }
                
                $shenrolledstudnew = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.dob',
                        'studinfo.studstatus',
                        'studinfo.studstatdate',
                        'studinfo.grantee',
                        'studinfo.mol',
                        'studentstatus.description as studentstatus',
                        'sh_enrolledstud.dateenrolled',
                        'sh_enrolledstud.sectionid',
                        'sh_enrolledstud.strandid',
                        'sh_strand.trackid',
                        'gradelevel.id as levelid',
                        'gradelevel.acadprogid',
                        'gradelevel.sortid',
                        'gradelevel.levelname'
                    )
                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                    ->leftJoin('studentstatus','studinfo.studstatus','=','studentstatus.id')
                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->leftJoin('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                    ->join('sy','sh_enrolledstud.syid','=','sy.id')
                    // ->where('sh_enrolledstud.studstatus','1')
                    ->where('sy.id',$selectedschoolyear)
                    ->get();
                    
                if(count($shenrolledstudnew) > 0){
            
                    foreach($shenrolledstudnew as $stud){

                        if($stud->middlename == null){
                            $stud->middlename = "";
                        }

                        if($stud->suffix == null){
                            $stud->suffix = "";
                        }

                        $studentinfo = Db::table('sh_enrolledstud')
                            ->where('studid', $stud->id)
                            ->get();
            
                        if(count($studentinfo) == 1){
                            $prereg = Db::table('preregistration')
                                ->where('last_name','like','%'.$stud->lastname)
                                ->where('first_name','like','%'.$stud->firstname)
                                ->where('middle_name','like','%'.$stud->middlename)
                                ->where('dob','like','%'.$stud->dob)
                                ->whereYear('date_created', date('Y'))
                                ->get();

                            // return $prereg;
                            if(count($prereg) == 1){
                                array_push($students, $stud);
                            }
                            // if()
                        }
                        elseif(count($studentinfo) == 1){
                            
                            array_push($students, $stud);

                        }
            
                    }

            
                }

            }elseif($selectedstudenttype == 'old'){

                $enrolledstudold = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.dob',
                        'studinfo.studstatus',
                        'studinfo.studstatdate',
                        'studinfo.grantee',
                        'studinfo.mol',
                        'studentstatus.description as studentstatus',
                        'enrolledstud.dateenrolled',
                        'enrolledstud.sectionid',
                        'gradelevel.id as levelid',
                        'gradelevel.acadprogid',
                        'gradelevel.sortid',
                        'gradelevel.levelname'
                    )
                    ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                    ->leftJoin('studentstatus','studinfo.studstatus','=','studentstatus.id')
                    ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                    ->join('sy','enrolledstud.syid','=','sy.id')
                    // ->where('enrolledstud.studstatus','1')
                    ->where('sy.id',$selectedschoolyear)
                    ->get();
                    
                if(count($enrolledstudold) > 0){
            
                    foreach($enrolledstudold as $stud){

                        if($stud->middlename == null){
                            $stud->middlename = "";
                        }

                        if($stud->suffix == null){
                            $stud->suffix = "";
                        }

                        $studentinfo = Db::table('enrolledstud')
                            ->where('studid', $stud->id)
                            ->get();
            
                        if(count($studentinfo) == 1){
                            $prereg = Db::table('preregistration')
                                ->where('last_name','like','%'.$stud->lastname)
                                ->where('first_name','like','%'.$stud->firstname)
                                ->where('middle_name','like','%'.$stud->middlename)
                                ->where('dob','like','%'.$stud->dob)
                                ->whereYear('date_created', date('Y'))
                                ->get();

                            // return $prereg;
                            if(count($prereg) != 1){
                                array_push($students, $stud);
                            }
                            // if()
                        }
                        elseif(count($studentinfo) > 1){
            
                            array_push($students, $stud);
            
                        }
            
                    }
            
                }
            
                $shenrolledstudold = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.lastname',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.dob',
                        'studinfo.studstatus',
                        'studinfo.studstatdate',
                        'studinfo.grantee',
                        'studinfo.mol',
                        'studentstatus.description as studentstatus',
                        'sh_enrolledstud.dateenrolled',
                        'sh_enrolledstud.sectionid',
                        'sh_enrolledstud.strandid',
                        'sh_strand.trackid',
                        'gradelevel.id as levelid',
                        'gradelevel.acadprogid',
                        'gradelevel.sortid',
                        'gradelevel.levelname'
                    )
                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                    ->leftJoin('studentstatus','studinfo.studstatus','=','studentstatus.id')
                    ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
                    ->leftJoin('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
                    ->join('sy','sh_enrolledstud.syid','=','sy.id')
                    // ->where('sh_enrolledstud.studstatus','1')
                    ->where('sy.id',$selectedschoolyear)
                    ->get();
                
                if(count($shenrolledstudold) > 0){
            
                    foreach($shenrolledstudold as $stud){

                        if($stud->middlename == null){
                            $stud->middlename = "";
                        }

                        if($stud->suffix == null){
                            $stud->suffix = "";
                        }

                        $studentinfo = Db::table('sh_enrolledstud')
                            ->where('studid', $stud->id)
                            ->get();
            
                        if(count($studentinfo) == 1){
                            $prereg = Db::table('preregistration')
                                ->where('last_name','like','%'.$stud->lastname)
                                ->where('first_name','like','%'.$stud->firstname)
                                ->where('middle_name','like','%'.$stud->middlename)
                                ->where('dob','like','%'.$stud->dob)
                                ->whereYear('date_created', date('Y'))
                                ->get();

                            // return $prereg;
                            if(count($prereg) != 1){
                                array_push($students, $stud);
                            }
                            // if()
                        }
                        elseif(count($studentinfo) > 1){
            
                            array_push($students, $stud);
            
                        }
            
                    }
            
                }

            }
            $filteredstudents = $students;
            
            // return $request->all();
            if($selectedstudentstatus == 'all'){
                
            }else{

                $filteredstudents = collect($filteredstudents)->where('studstatus',$selectedstudentstatus);
            }
            if($selectedacadprog != 'all'){
                $filteredstudents = collect($filteredstudents)->where('acadprogid',$selectedacadprog);
                $gradelevels = Db::table('gradelevel')
                    ->where('acadprogid',$selectedacadprog)
                    ->where('deleted','0')
                    ->get();
            }
            if($selectedgrantee != 'all'){
                $filteredstudents = collect($filteredstudents)->where('grantee',$selectedgrantee);
            }
            
            if($selectedmode == '0'){
                $filteredstudents = collect($filteredstudents)->filter(function ($value, $key) {
                    // dd($value->mol);
                    if($value->mol == 0 || $value->mol == null || $value->mol == "")
                    {
                        return $value;
                    }
                });
            }
            elseif($selectedmode == 'all'){
                $filteredstudents = collect($filteredstudents);
            }
            else{

                $filteredstudents = collect($filteredstudents)->where('mol',$selectedmode);
            }
            
            // return collect($students)->where('studstatus',$selectedstudentstatus);
            if($selecteddate != null){
                
                $selecteddate = explode(' - ', $selecteddate);

                $filteredstudents = collect($filteredstudents)->whereBetween('dateenrolled', [$selecteddate[0], $selecteddate[1]]);

            }
            if($selectedgender != 'all'){
                $filteredstudents = collect($filteredstudents)->where('gender',strtoupper($selectedgender));

            }
            if($selectedgradelevel != null){

                if($selectedsection != null){
                    
                    $filteredstudents = collect($filteredstudents)->where('sectionid', $selectedsection)->where('levelid', $selectedgradelevel);
                    // return $filteredstudents;
                }

                if($trackid != null){

                    if($strandid != null){
        
                        $filteredstudents = collect($filteredstudents)->where('trackid', $trackid)->where('levelid', $selectedgradelevel)->where('strandid', $strandid);
        
                    }else{
                        $filteredstudents = collect($filteredstudents)->where('trackid', $trackid)->where('levelid', $selectedgradelevel);
                    }
    
                }else{

                    if($strandid != null){
        
                        $filteredstudents = collect($filteredstudents)->where('levelid', $selectedgradelevel)->where('strandid', $strandid);
        
                    }else{

                        $filteredstudents = collect($filteredstudents)->where('levelid', $selectedgradelevel);
                    }

                }

                $sections = Db::table('sections')
                    ->where('levelid', $selectedgradelevel)
                    ->where('deleted','0')
                    ->get();

            }else{

                if($trackid != null){

                    if($strandid != null){
        
                        $filteredstudents = collect($filteredstudents)->where('trackid', $trackid)->where('strandid', $strandid);
        
                    }else{
                        $filteredstudents = collect($filteredstudents)->where('trackid', $trackid);
                    }
    
                }else{

                    if($strandid != null){
        
                        $filteredstudents = collect($filteredstudents)->where('strandid', $strandid);
        
                    }

                }

                $sections = null;

            }
            
            $data = array();
            // return $filteredstudents;
            if(count($filteredstudents) > 0){
                
                foreach($filteredstudents as $filteredstudentkey => $filteredstudentvalue){
                    if($filteredstudentvalue->middlename == null || $filteredstudentvalue->middlename == "")
                    {
                        $filteredstudentvalue->middlename = "";
                    }else{
                        
                        $filteredstudentvalue->middlename =  $filteredstudentvalue->middlename[0].'.';
                    }
                    array_push($data, $filteredstudentvalue);

                }

            }
            // foreach($filteredstudent as $filteredstudentkey => $filteredstudentvalue){
            //     if($filteredstudentkey == 'middlename')
            //     {
            //         if($filteredstudentvalue == null || $filteredstudentvalue == "" || $filteredstudentvalue == " ")
            //         {
            //             $filteredstudent->middlename = "";
            //         }else{
                        
            //             $filteredstudent->middlename =  $filteredstudentvalue[0].'.';
            //         }
            //     }
            // }
            
            if($id == 'filter'){
                
                return [collect($data)->sortBy('lastname')->values()->all(), $sections,$gradelevels];
                // $sortname = array_column($data->lastname, 'lastname');
                // return array_multisort($sortname, SORT_DESC, $data);
            }
            elseif($id == 'print'){
                $printoption = $request->get('printby');
                $schoolinfo = Db::table('schoolinfo')
                    ->select(
                        'schoolinfo.schoolid',
                        'schoolinfo.schoolname',
                        'schoolinfo.authorized',
                        'refcitymun.citymunDesc as city',
                        'schoolinfo.district',
                        'schoolinfo.address',
                        'schoolinfo.picurl',
                        'refregion.regDesc as region'
                    )
                    ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
                    ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
                    ->first();
                    
                if($trackid != null){

                    if($trackid == 'all'){

                        $tracks = Db::table('sh_track')
                            ->where('deleted', '0')
                            ->get();

                        $trackname = "";

                    }else{

                        $tracks = "";

                        $trackname = Db::table('sh_track')
                        ->where('id', $trackid)
                        ->get();
                    }

                }else{

                    $trackname = "";

                    $tracks = "";

                }

                if($strandid != null){

                    if($strandid == 'all'){

                        $strands = Db::table('sh_strand')
                            ->select(
                                'sh_strand.id',
                                'sh_strand.strandname',
                                'sh_track.id as trackid',
                                'sh_track.trackname'
                            )
                            ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                            ->where('sh_strand.active', '1')
                            ->where('sh_strand.deleted', '0')
                            ->where('sh_track.deleted', '0')
                            ->get();

                        $strandname = "";

                    }else{

                        $strands = "";

                        $strandname = Db::table('sh_strand')
                            ->where('id', $strandid)
                            ->get();
                        

                    }

                }else{


                    $strands = DB::table('sh_strand')
                        ->where('deleted','0')
                        ->get();

                    $strandname = "";

                }
                
                if($selectedstudenttype == 'all'){
                    
                    $filteredstud = collect($data)->sortBy('sortid')->values()->all();

                }else{

                    $filteredstud = collect($data)->sortBy('lastname')->values()->all();

                }

                $filteredstud = collect($filteredstud)->sortBy('lastname')->values()->all();

                $sy = DB::table('sy')
                    ->where('id',$selectedschoolyear)
                    ->first();
                // if($selecteddate != null){
                //     return $selecteddate;
                //     $date
                // }
                
                $selecteddatefrom   = date('M d,Y',strtotime($selecteddate[0]));
                $selecteddateto     = date('M d,Y',strtotime($selecteddate[1]));
                $shsbystrand = 0;
                if($selectedgradelevel >= 14){
                    if($trackid == null && $strandid == null){
                        $shsbystrand = 1;
                    }
                }
                // return $strands;
                if($selectedgender == 'all'){
                    $selectedgender = "";
                }
                elseif($selectedgender == 'male'){
                    $selectedgender = "(MALE)";
                }
                elseif($selectedgender == 'female'){
                    $selectedgender = "(FEMALE)";
                }
                if($selectedstudentstatus == 'all'){
                    $selectedstudentstatus = "";
                }else{
                    $selectedstudentstatus = Db::table('studentstatus')
                        ->where('id', $selectedstudentstatus)
                        ->first()
                        ->description;
                }
                if($selectedacadprog == 'all'){
                    $selectedacadprog = "";
                }else{
                    $selectedacadprog = Db::table('academicprogram')
                        ->where('id', $selectedacadprog)
                        ->first()
                        ->progname;
                }
                if($selectedmode == 'all')
                {
                    $selectedmode = "";
                }
                elseif($selectedmode == '0')
                {
                    $selectedmode = 'UNSPECIFIED';
                }else{
                    
                    $selectedmode = DB::table('modeoflearning')
                        ->where('id', $selectedmode)
                        ->first()
                        ->description;
                }
                if($selectedgrantee == 'all')
                {
                    $selectedgrantee = "";
                }else{
                    $selectedgrantee = DB::table('grantee')
                        ->where('id', $selectedgrantee)
                        ->first()
                        ->description;
                }
                
                $pdf = PDF::loadview('registrar/pdf/pdf_summaryofallstudents',compact('filteredstud','printoption','selectedstudentstatus','selecteddatefrom','selecteddateto','selecteddate','selectedgender','selectedstudenttype','trackid','tracks','trackname','strands','strandid','strandname','gradelevels','schoolinfo','sy','shsbystrand','selectedacadprog','selectedmode','selectedgrantee'))->setPaper('8.5x11');

                set_time_limit(1300);
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->stream('Summary of Students.pdf');

            }

        }

    }

}
