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
            // elseif($selectedstudenttype == 'transferee'){

            //     $enrolledstudtransferee = DB::table('studinfo')
            //         ->select(
            //             'studinfo.id',
            //             'studinfo.lastname',
            //             'studinfo.firstname',
            //             'studinfo.middlename',
            //             'studinfo.suffix',
            //             'studinfo.gender',
            //             'enrolledstud.dateenrolled',
            //             'enrolledstud.sectionid',
            //             'gradelevel.id as levelid',
            //             'gradelevel.sortid',
            //             'gradelevel.levelname'
            //         )
            //         ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
            //         ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
            //         ->join('sy','enrolledstud.syid','=','sy.id')
            //         ->where('enrolledstud.studstatus','4')
            //         ->where('sy.id',$selectedschoolyear)
            //         ->get();
                
            //     if(count($enrolledstudtransferee) > 0){
            
            //         foreach($enrolledstudtransferee as $stud){

            //             if($stud->middlename == null){
            //                 $stud->middlename = "";
            //             }

            //             if($stud->suffix == null){
            //                 $stud->suffix = "";
            //             }

            //             $studentinfo = Db::table('enrolledstud')
            //                 ->where('studid', $stud->id)
            //                 ->get();
            
            //             if(count($studentinfo) == 1){
            
            //                 array_push($students, $stud);
            
            //             }
            
            //         }
            
            //     }
            
            //     $shenrolledstudtransferee = DB::table('studinfo')
            //         ->select(
            //             'studinfo.id',
            //             'studinfo.lastname',
            //             'studinfo.firstname',
            //             'studinfo.middlename',
            //             'studinfo.suffix',
            //             'studinfo.gender',
            //             'sh_enrolledstud.dateenrolled',
            //             'sh_enrolledstud.sectionid',
            //             'sh_enrolledstud.strandid',
            //             'sh_strand.trackid',
            //             'gradelevel.id as levelid',
            //             'gradelevel.sortid',
            //             'gradelevel.levelname'
            //         )
            //         ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
            //         ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
            //         ->leftJoin('sh_strand','sh_enrolledstud.strandid','=','sh_strand.id')
            //         ->join('sy','sh_enrolledstud.syid','=','sy.id')
            //         ->where('sh_enrolledstud.studstatus','4')
            //         ->where('sy.id',$selectedschoolyear)
            //         ->get();
                
            //     if(count($shenrolledstudtransferee) > 0){
            
            //         foreach($shenrolledstudtransferee as $stud){

            //             if($stud->middlename == null){
            //                 $stud->middlename = "";
            //             }

            //             if($stud->suffix == null){
            //                 $stud->suffix = "";
            //             }

            //             $studentinfo = Db::table('sh_enrolledstud')
            //                 ->where('studid', $stud->id)
            //                 ->get();
            
            //             if(count($studentinfo) > 1){
            
            //                 array_push($students, $stud);
            
            //             }
            
            //         }
            
            //     }
                
            // }
            
            $filteredstudents = $students;
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
            // return $request->all();
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
            
            if(count($filteredstudents) > 0){

                foreach($filteredstudents as $filteredstudentkey => $filteredstudentvalue){
                    if($filteredstudentvalue->middlename == null)
                    {
                        $filteredstudentvalue->middlename = "";
                    }else{
                        $filteredstudentvalue->middlename =  $filteredstudentvalue->middlename[0].'.';
                    }
                    array_push($data, $filteredstudentvalue);

                }

            }
            
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
                
                $html = '<!DOCTYPE html>'.
                '<html lang="en">'.
                '<head>'.
                '<meta charset="UTF-8">'.
                '<meta name="viewport" content="width=device-width, initial-scale=1.0">'.
                '<meta http-equiv="X-UA-Compatible" content="ie=edge">'.
                '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'.

                '<title>Document</title>'.
                '<style>'.
                '*   {'.
                    'font-family: Arial, Helvetica, sans-serif;'.
                    '}'.

                    '.header{'.
                        ' width: 100%;'.
                        'table-layout: fixed;'.
                        'font-family: Arial, Helvetica, sans-serif;'.
                        '}'.

                        '.header td {'.
                            'font-size: 15px !important;'.
                            '}'.

                            '.studentstable{'.
                                'width: 100%;'.
                                'font-family: Arial, Helvetica, sans-serif;'.
                                'font-size: 12px;'.
                                'border: 1px solid black;'.
                                'border-collapse: collapse;'.
                                '}'.

                                '.studentstable td, .enrollees th{'.
                                    'border: 1px solid black;'.
                                    'padding: 5px;'.
                                    '}'.

                                    '.clear:after {'.
                                        'clear: both;'.
                                        'content: "";'.
                                        'display: table;'.
                                        'border: 1px solid black;'.
                                        '}'.

                                        'tbody td {'.
                                            'font-size: 11px !important;'.
                                            '}'.

                                            'footer {'.
                                                'position: fixed; '.
                                                'bottom: 0cm; '.
                                                'left: 0cm; '.
                                                'right: 0cm;'.
                                                'height: 2cm;'.
                                                '}'.

                                                '</style>'.
                                                '</head>'.
                                                '<body>'.
                '<table class="header">'.
                            '<tr>'.
                                '<td width="15%" rowspan="2"><img src="'.base_path().'/public/'.$schoolinfo->picurl.'" alt="school" width="70px"></td>'.
                                '<td>'.
                                    '<strong>'.$schoolinfo->schoolname.'</strong>'.
                                    '<br/>'.
                                    '<small style="font-size: 10px !important;">'.$schoolinfo->address.'</small>'.
                                '</td>'.
                                '<td style="text-align:right;">'.
                                    '<strong>Summary of Students</strong>'.
                                    '<br>';
                                    '<small style="font-size: 11px !important;">S.Y '.$sy->sydesc.'</small>'.
                                    '<br>'.
                                    '<small style="font-size: 11px !important;"><strong>'.$selectedacadprog.'</strong></small>';

                if($selecteddate != null){
                    $html .='<br>';
                    $html .='<small style="font-size: 11px !important;">Date enrolled: '.$selecteddatefrom.' - '.$selecteddateto.'</small>';
                }
        
                $html .='</td>';
                $html .='</tr>';
                $html .='</table>';
                $html .='<br>';
                if($selectedstudentstatus != 'all'){
                    $html .='<strong>'.$selectedstudentstatus.'</strong>';
                }
                $numofstudents = 1;   
                $numofstudentsall = 1;  

                if($shsbystrand == 1)
                {
                    if(count($strands) > 0)
                    {
                        
                        foreach($strands as $strand)
                        {
                            if(count(collect($filteredstud)->where('strandid', $strand->id)) > 0){

                                $html .='<table class="studentstable">'.
                                            '<thead style="border: 1px solid black;">'.
                                                '<tr>'.
                                                    '<th colspan="4">'.$strand->strandname.'<br/>'.$selectedgender.'</th>'.
                                                '</tr>'.
                                                '<tr>'.
                                                    '<th colspan="2">Name of Students</th>'.
                                                    '<th>Grade Level</th>'.
                                                    '<th>Status</th>'.
                                                '</tr>'.
                                            '</thead>'.
                                            '<tbody>';
                                        foreach($filteredstud as $student)
                                        {
                                            if($student->strandid == $strand->id)
                                            {
                                                $html .='<tr>'.
                                                            '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                            '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                            '<td>'.$student->levelname.'</td>'.
                                                            '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                        '</tr>';
                                                $numofstudents+=1;   
                                            }
                                        }
                                        $html .='</tbody>'.
                                        '</table>'.
                                        '<br>';
                            }
                        }
                    }
                }
                else
                {
                    if($trackid == null)
                    {
                        $html .='<table class="studentstable">'.
                                    '<thead style="border: 1px solid black;">'.
                                        '<tr>'.
                                            '<th colspan="4">'.strtoupper($selectedstudenttype).' STUDENTS<br/>'.$selectedgender.'</th>'.
                                        ' </tr>'.
                                        '<tr>'.
                                            '<th colspan="2">Name of Students</th>'.
                                            '<th>Grade Level</th>'.
                                            '<th>Status</th>'.
                                        '</tr>'.
                                    '</thead>'.
                                '<tbody>';
                                foreach($filteredstud as $student)
                                {
                                    if(strtolower($student->gender) == 'male')
                                    {
                                        $html .='<tr>'.
                                                    '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                    '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                    '<td>'.$student->levelname.'</td>'.
                                                    '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                '</tr>';
                                    }else{
                                        $html .='<tr>'.
                                                    '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                    '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                    '<td>'.$student->levelname.'</td>'.
                                                    '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                '</tr>';
                                    }
                                    $numofstudents+=1;   
                                }
                                $html .='</tbody>'.
                            '</table>';
                    }
                    else
                    {
                        
                        if($strandid == null)
                        {
                            if($trackid == 'all'){
                                foreach($tracks as $track)
                                {
                                    $html.='<table class="studentstable">'.
                                                '<thead style="border: 1px solid black;">'.
                                                    '<tr>'.
                                                        '<th colspan="4">'.$track->trackname.'<br/>'.$selectedgender.'</th>'.
                                                    '</tr>'.
                                                    '<tr>'.
                                                        '<th colspan="2">Name of Students</th>'.
                                                        '<th>Grade Level</th>'.
                                                        '<th>Status</th>'.
                                                    '</tr>'.
                                                '</thead>'.
                                            '<tbody>';
                                            foreach($filteredstud as $student){
                                                if($student->trackid == $track->id)
                                                {
                                                    
                                                    if(strtolower($student->gender) == 'male')
                                                    {
                                                        
                                                        $html.='<tr>'.
                                                                    '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                                    '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                                    '<td>'.$student->levelname.'</td>'.
                                                                    '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                                '</tr>';
                                                    }
                                                    else{
                                                        $html.='<tr>'.
                                                                    '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                                    '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                                    '<td>'.$student->levelname.'</td>'.
                                                                    '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                                '</tr>';
                                                    }
                                                    $numofstudentsall+=1;   
                                                }
                                            }
                                            $html.='</tbody>'.
                                            '</table>'.
                                            '<br>';
                                }
                            }
                            else{
                                $html.='<table class="studentstable">'.
                                            '<thead style="border: 1px solid black;">'.
                                                '<tr>'.
                                                    '<th colspan="4">'.$trackname[0]->trackname.'<br/>'.$selectedgender.'</th>'.
                                                '</tr>'.
                                                '<tr>'.
                                                    '<th colspan="2">Name of Students</th>'.
                                                    '<th>Grade Level</th>'.
                                                    '<th>Status</th>'.
                                                '</tr>'.
                                            '</thead>'.
                                            '<tbody>';
                                        foreach($filteredstud as $student)
                                        {
                                            if(strtolower($student->gender) == 'male')
                                            {
                                                $html.='<tr>'.
                                                        '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                        '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                        '<td>'.$student->levelname.'</td>'.
                                                        '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                    '</tr>';
                                            }else{
                                                $html.='<tr>'.
                                                            '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                            '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                            '<td>'.$student->levelname.'</td>'.
                                                            '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                        '</tr>';
                                            }
                                            $numofstudents+=1;  
                                        } 
                                        $html.='</tbody>'.
                                '</table>';
                            }
                        }
                        else{
                            if($strandid == 'all')
                            {
                                foreach($strands as $strand)
                                {
                                    $html.='<table class="studentstable">'.
                                                '<thead style="border: 1px solid black;">'.
                                                    '<tr>'.
                                                        '<th colspan="4">'.$strand->trackname.'<br>'.$strand->strandname.'<br/>'.$selectedgender.'</th>'.
                                                    '</tr>'.
                                                    '<tr>'.
                                                        '<th colspan="2">Name of Students</th>'.
                                                        '<th>Grade Level</th>'.
                                                        '<th>Status</th>'.
                                                    '</tr>'.
                                                '</thead>'.
                                            '<tbody>';
                                            foreach($filteredstud as $student)
                                            {
                                                if($student->strandid == $strand->id)
                                                {
                                                    if(strtolower($student->gender) == 'male')
                                                    {
                                                        $html.='<tr>'.
                                                                    '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                                    '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                                    '<td>'.$student->levelname.'</td>'.
                                                                    '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                                '</tr>';
                                                    }
                                                    else
                                                    {
                                                        $html.='<tr>'.
                                                                    '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                                    '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                                    '<td>'.$student->levelname.'</td>'.
                                                                    '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                                '</tr>';
                                                    }
                                                    $numofstudentsall+=1;  
                                                }
                                            }
                                            $html.='</tbody>'.
                                    '</table>'.
                                    '<br>';
                                }
                            }
                            else
                            {
                                $html.='<table class="studentstable">'.
                                            '<thead style="border: 1px solid black;">'.
                                                '<tr>'.
                                                    '<th colspan="4">'.$trackname[0]->trackname.'<br>'.$strandname[0]->strandname.'<br/>'.$selectedgender.'</th>'.
                                                '</tr>'.
                                                '<tr>'.
                                                    '<th colspan="2">Name of Students</th>'.
                                                    '<th>Grade Level</th>'.
                                                    '<th>Status</th>'.
                                                '</tr>'.    
                                            '</thead>'.
                                            '<tbody>';
                                        foreach($filteredstud as $student)
                                        {
                                            if(strtolower($student->gender) == 'male')
                                            {
                                                $html.='<tr>'.
                                                '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                '<td>'.$student->levelname.'</td>'.
                                                '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                '</tr>';
                                            }else{
                                                $html.='<tr>'.
                                                '<td style="width: 5px !important; text-align:center;">'.$numofstudents.'</td>'.
                                                '<td>'.$student->lastname.''.$student->firstname.' '.$student->middlename.'</td>'.
                                                '<td>'.$student->levelname.'</td>'.
                                                '<td>'.$student->studentstatus.' <span style="float: right">'.$student->studstatdate.'</span></td>'.
                                                '</tr>';
                                            }
                                            $numofstudents+=1;   
                                        }
                                        $html.='</tbody>'.
                                '</table>';
                            }
                        }
                    }
                }
                $html.='</body>'.
                '</html>';
                // $dompdf = new PDF();
                // $dompdf->loadview($html);
                
                // // (Optional) Setup the paper size and orientation
                // $dompdf->setPaper('A4', 'landscape');
                
                // // Render the HTML as PDF
                // $dompdf->render();
                
                // // Output the generated PDF to Browser
                // return $dompdf->stream();
                // return $html;

                $pdf = PDF::loaDHTML($html)->setPaper('8.5x11');
                set_time_limit(1300);
                $pdf->getDomPDF()->set_option("enable_php", true);
                $pdf->getDomPDF()->set_option('defaultMediaType', 'all');
                $pdf->getDomPDF()->set_option('isFontSubsettingEnabled', true);
                return $pdf->stream();

            }

        }

    }

}
