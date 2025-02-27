<?php

namespace App\Http\Controllers\PrincipalControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Models\Principal\SPP_EnrolledStudent;
use Session;

class PrincipalSummaryController extends Controller
{
    public function summarytotalnumberofstudents($acadid)
    {

        
  
        
        return view('principalsportal.pages.summaries.totalstudents')->with('students','students');
  


        // date_default_timezone_set('Asia/Manila');

        
        // $escstudents        = array();

        // $regularstudents    = array();

        // $voucherstudents    = array();

        // $enrolledstuds      = DB::table('enrolledstud')
        //                         ->select(
        //                             'enrolledstud.studid',
        //                             'studinfo.firstname',
        //                             'studinfo.middlename',
        //                             'studinfo.lastname',
        //                             'studinfo.suffix',
        //                             'studinfo.gender',
        //                             'enrolledstud.levelid',
        //                             'gradelevel.levelname',
        //                             'studinfo.grantee'
        //                             )
        //                         ->leftJoin('studinfo','enrolledstud.studid','studinfo.id')
        //                         ->join('sy','enrolledstud.syid','sy.id')
        //                         ->join('gradelevel','enrolledstud.levelid','gradelevel.id')
        //                         ->where('sy.isactive','1')
        //                         ->where('enrolledstud.studstatus','!=','0')
        //                         ->get();
        
        // foreach($enrolledstuds as $enrolledstud){

        //     if($enrolledstud->grantee == 1){

        //         array_push($regularstudents, $enrolledstud);

        //     }

        //     if($enrolledstud->grantee == 2){

        //         array_push($escstudents, $enrolledstud);

        //     }

        //     if($enrolledstud->grantee == 3){

        //         array_push($voucherstudents, $enrolledstud);

        //     }

        // }

        // $shenrolledstuds    = DB::table('sh_enrolledstud')
        //                         ->select(
        //                             'sh_enrolledstud.studid',
        //                             'studinfo.firstname',
        //                             'studinfo.middlename',
        //                             'studinfo.lastname',
        //                             'studinfo.suffix',
        //                             'studinfo.gender',
        //                             'sh_enrolledstud.levelid',
        //                             'gradelevel.levelname',
        //                             'studinfo.grantee'
        //                             )
        //                         ->leftJoin('studinfo','sh_enrolledstud.studid','studinfo.id')
        //                         ->join('sy','sh_enrolledstud.syid','sy.id')
        //                         ->join('gradelevel','sh_enrolledstud.levelid','gradelevel.id')
        //                         ->where('sy.isactive','1')
        //                         ->where('sh_enrolledstud.studstatus','!=','0')
        //                         ->get();

        // foreach($shenrolledstuds as $shenrolledstud){


        //     if($shenrolledstud->grantee == 1){

        //         array_push($escstudents, $shenrolledstud);

        //     }

        //     if($shenrolledstud->grantee == 2){

        //         array_push($regularstudents, $shenrolledstud);

        //     }

        //     if($shenrolledstud->grantee == 3){

        //         array_push($voucherstudents, $shenrolledstud);

        //     }

        // }

        // $gradelevels        = DB::table('gradelevel')
        //                         ->select(
        //                             'id',
        //                             'levelname',
        //                             'sortid'
        //                         )
        //                         ->where('deleted','0')
        //                         ->orderBy('sortid','asc')
        //                         ->get();

        // if($id == 'view'){

        //     $selectedcategory   = 'all';
        //     $selectedgradelevel = 'all';
        //     $selectedgender     = 'all';

        //     return view('principalsportal.pages.summaries.summarytotalnumberofstudents')
        //         ->with('gradelevels',$gradelevels)
        //         ->with('regularstudents',$regularstudents)
        //         ->with('escstudents',$escstudents)
        //         ->with('voucherstudents',$voucherstudents)
        //         ->with('selectedcategory',$selectedcategory)
        //         ->with('selectedgradelevel',$selectedgradelevel)
        //         ->with('selectedgender',$selectedgender);

        // }
        // if($id == 'filter'){

        //     $selectedcategory   = $request->get('selectedcategory');
        //     $selectedgradelevel = $request->get('selectedgradelevel');
        //     $selectedgender     = $request->get('selectedgender');

        //     if($selectedcategory != 'all'){

        //         $regularstudents = collect($regularstudents)->where('levelid', $selectedgradelevel);
        //         $escstudents     = collect($escstudents)->where('levelid', $selectedgradelevel);
        //         $voucherstudents = collect($voucherstudents)->where('levelid', $selectedgradelevel);
    
        //     }
            
            
        //     if($selectedgradelevel != 'all'){

        //         $regularstudents = collect($regularstudents)->where('levelid', $selectedgradelevel);
        //         $escstudents     = collect($escstudents)->where('levelid', $selectedgradelevel);
        //         $voucherstudents = collect($voucherstudents)->where('levelid', $selectedgradelevel);

        //     }
        //     if($selectedgender != 'all'){

        //         $regularstudents = collect($regularstudents)->where('gender',strtoupper($selectedgender));
        //         $escstudents     = collect($escstudents)->where('gender',strtoupper($selectedgender));
        //         $voucherstudents = collect($voucherstudents)->where('gender',strtoupper($selectedgender));

        //     }

        //     if($selectedcategory == 'all'){

        //         return view('principalsportal.pages.summaries.summarytotalnumberofstudents')
        //                             ->with('gradelevels',$gradelevels)
        //                             ->with('regularstudents',$regularstudents)
        //                             ->with('escstudents',$escstudents)
        //                             ->with('voucherstudents',$voucherstudents)
        //                             ->with('selectedcategory',$selectedcategory)
        //                             ->with('selectedgradelevel',$selectedgradelevel)
        //                             ->with('selectedgender',$selectedgender);
        //     }
        //     if($selectedcategory == '1'){

        //         return view('principalsportal.pages.summaries.summarytotalnumberofstudents')
        //             ->with('gradelevels',$gradelevels)
        //             ->with('regularstudents',$regularstudents)
        //             ->with('selectedcategory',$selectedcategory)
        //             ->with('selectedgradelevel',$selectedgradelevel)
        //             ->with('selectedgender',$selectedgender);
        //     }
        //     if($selectedcategory == '2'){

        //         return view('principalsportal.pages.summaries.summarytotalnumberofstudents')
        //             ->with('gradelevels',$gradelevels)
        //             ->with('escstudents',$escstudents)
        //             ->with('selectedcategory',$selectedcategory)
        //             ->with('selectedgradelevel',$selectedgradelevel)
        //             ->with('selectedgender',$selectedgender);
        //     }
        //     if($selectedcategory == '3'){

        //         return view('principalsportal.pages.summaries.summarytotalnumberofstudents')
        //             ->with('gradelevels',$gradelevels)
        //             ->with('voucherstudents',$voucherstudents)
        //             ->with('selectedcategory',$selectedcategory)
        //             ->with('selectedgradelevel',$selectedgradelevel)
        //             ->with('selectedgender',$selectedgender);
        //     }

        // }
        // if($id == 'print'){

        //     $schoolinfo = DB::table('schoolinfo')
        //         ->first();

        //     $selectedcategory = $request->get('selectedcategory');
        //     $selectedgradelevel = $request->get('selectedgradelevel');
        //     $selectedgender     = $request->get('selectedgender');
                
        //     if($selectedgradelevel != 'all'){

        //         $regularstudents = collect($regularstudents)->where('levelid', $selectedgradelevel);
        //         $escstudents     = collect($escstudents)->where('levelid', $selectedgradelevel);
        //         $voucherstudents = collect($voucherstudents)->where('levelid', $selectedgradelevel);

        //     }
            
        //     if($selectedgender != 'all'){

        //         $regularstudents = collect($regularstudents)->where('gender',strtoupper($selectedgender));
        //         $escstudents     = collect($escstudents)->where('gender',strtoupper($selectedgender));
        //         $voucherstudents = collect($voucherstudents)->where('gender',strtoupper($selectedgender));

        //     }

        //     $displaytype = $request->get('displaytype');

        //     $preparedby = DB::table('teacher')
        //         ->where('userid', auth()->user()->id)
        //         ->first();

        //     $dateprepared =date('F d, Y h:i:s A');

        //     $sy = Db::table('sy')
        //         ->where('isactive','1')
        //         ->first();

        //     if($selectedcategory == 'all'){

        //         $pdf = PDF::loadview('principalsportal/pdf/pdf_summarytotalnumberofstudentsbycat',compact('regularstudents','escstudents','voucherstudents','selectedcategory','schoolinfo','preparedby','dateprepared','sy','gradelevels','enrolledstuds','shenrolledstuds','displaytype'))->setPaper('8.5x11');

        //         return $pdf->stream('Total Students - All.pdf'); 
        //     }
        //     if($selectedcategory == '1'){

        //         $pdf = PDF::loadview('principalsportal/pdf/pdf_summarytotalnumberofstudentsbycat',compact('regularstudents','selectedcategory','schoolinfo','preparedby','dateprepared','sy','gradelevels','enrolledstuds','shenrolledstuds','displaytype'))->setPaper('8.5x11');

        //         return $pdf->stream('Total Students Per Category - Regular.pdf'); 
        //     }
        //     if($selectedcategory == '2'){

        //         $pdf = PDF::loadview('principalsportal/pdf/pdf_summarytotalnumberofstudentsbycat',compact('escstudents','selectedcategory','schoolinfo','preparedby','dateprepared','sy','gradelevels','enrolledstuds','shenrolledstuds','displaytype'))->setPaper('8.5x11');

        //         return $pdf->stream('Total Students Per Category - ESC Grantee.pdf'); 
        //     }
        //     if($selectedcategory == '3'){

        //         $pdf = PDF::loadview('principalsportal/pdf/pdf_summarytotalnumberofstudentsbycat',compact('voucherstudents','selectedcategory','schoolinfo','preparedby','dateprepared','sy','gradelevels','enrolledstuds','shenrolledstuds','displaytype'))->setPaper('8.5x11');

        //         return $pdf->stream('Total Students Per Category - Voucher.pdf'); 
        //     }

        // }

    }
    public function summarytotalnumberofdropped($id, Request $request)
    {
        
        date_default_timezone_set('Asia/Manila');

        $schoolyears            = DB::table('sy')
                                    ->orderByDesc('sydesc')
                                    ->get();
            
        if($id == 'view'){

            $selectedschoolyear =   DB::table('sy')
                ->where('isactive','1')
                ->first()->id;

            $periodfrom         = date('Y-m-d');
            
            $periodto           = date('Y-m-d');

        }
        elseif($id == 'filter' || $id == 'print'){

            $selectedschoolyear = $request->get('selectedschoolyear');

            $daterange          = explode(' - ', $request->get('selectedperiod'));

            $periodfrom         = $daterange[0];
            
            $periodto           = $daterange[1];

        }

        $droppedstudents        = array();

        $enrolledstuds          = DB::table('enrolledstud')
                                    ->select(
                                        'enrolledstud.studid',
                                        'studinfo.firstname',
                                        'studinfo.middlename',
                                        'studinfo.lastname',
                                        'studinfo.suffix',
                                        'studinfo.gender',
                                        'enrolledstud.levelid',
                                        'gradelevel.levelname'
                                        )
                                    ->leftJoin('studinfo','enrolledstud.studid','studinfo.id')
                                    ->join('sy','enrolledstud.syid','sy.id')
                                    ->join('gradelevel','enrolledstud.levelid','gradelevel.id')
                                    ->where('sy.id',$selectedschoolyear)
                                    ->whereBetween('enrolledstud.createddatetime',[$periodfrom,$periodto])
                                    ->where('enrolledstud.studstatus','3')
                                    ->get();

        foreach($enrolledstuds as $enrolledstud){

            array_push($droppedstudents, $enrolledstud);

        }
            
        $shenrolledstuds        = DB::table('sh_enrolledstud')
                                    ->select(
                                        'sh_enrolledstud.studid',
                                        'studinfo.firstname',
                                        'studinfo.middlename',
                                        'studinfo.lastname',
                                        'studinfo.suffix',
                                        'studinfo.gender',
                                        'sh_enrolledstud.levelid',
                                        'gradelevel.levelname'
                                        )
                                    ->leftJoin('studinfo','sh_enrolledstud.studid','studinfo.id')
                                    ->join('sy','sh_enrolledstud.syid','sy.id')
                                    ->join('gradelevel','sh_enrolledstud.levelid','gradelevel.id')
                                    ->where('sy.id',$selectedschoolyear)
                                    ->whereBetween('sh_enrolledstud.createddatetime',[$periodfrom,$periodto])
                                    ->where('sh_enrolledstud.studstatus','3')
                                    ->get();

        foreach($shenrolledstuds as $shenrolledstud){

            array_push($droppedstudents, $shenrolledstud);

        }
        
        if($id == 'view' || $id == 'filter'){

            return view('principalsportal.pages.summaries.summarytotalnumberofdropped')
                ->with('droppedstudents',$droppedstudents)
                ->with('periodfrom',$periodfrom)
                ->with('periodto',$periodto)
                ->with('selectedschoolyear',$selectedschoolyear)
                ->with('schoolyears',$schoolyears);

        }
        elseif($id == 'print'){

            $schoolinfo = DB::table('schoolinfo')
                ->first();

            $preparedby = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first();

            $dateprepared =date('F d, Y h:i:s A');

            $sy = Db::table('sy')
                ->where('id',$selectedschoolyear)
                ->first();
            
            $pdf = PDF::loadview('principalsportal/pdf/pdf_summarytotalnumberofdroppedstudents',compact('droppedstudents','periodfrom','periodto','schoolinfo','preparedby','dateprepared','sy'))->setPaper('8.5x11');

            return $pdf->stream('Total Students - All.pdf'); 

        }

    }
    public function summarytotalnumberoftopstudents($id, Request $request)
    {

        date_default_timezone_set('Asia/Manila');

        $schoolyear = DB::table('sy')
            ->where('isactive','1')
            ->first()
            ->id;

        $gradelevels = DB::table('gradelevel')
            ->where('deleted','0')
            ->get();

        if($id == 'view'){

            $selectedgradelevel = 'all';

            

        }

        return view('principalsportal.pages.summaries.summarytotalnumberoftopstudents')
            // ->with('droppedstudents',$droppedstudents)
            // ->with('periodfrom',$periodfrom)
            // ->with('periodto',$periodto)
            // ->with('selectedschoolyear',$selectedschoolyear)
            ->with('gradelevels',$gradelevels);

    }
    public function summaryofretentions($id, Request $request)
    {

        date_default_timezone_set('Asia/Manila');

        $schoolyears            = DB::table('sy')
                                    ->get();

        $gradelevels            = DB::table('gradelevel')
                                    ->where('deleted','0')
                                    ->get();

        if($id == 'view'){

            $selectedschoolyear = DB::table('sy')
                                    ->where('isactive','1')
                                    ->first()
                                    ->id;

            $selectedgradelevel = "";

        }
        elseif($id == 'filter' || $id == 'print'){

            $selectedschoolyear = $request->get('selectedschoolyear');

            $selectedgradelevel = $request->get('selectedgradelevel');

        }

        $repeaters = array();

        $enrolledstudents = DB::table('enrolledstud')
            ->select(
                'studinfo.id',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix'
            )
            ->leftJoin('studinfo','enrolledstud.studid','=','studinfo.id')
            ->where('enrolledstud.levelid',$selectedgradelevel)
            ->where('enrolledstud.deleted','0')
            ->get();
            
        if(count($enrolledstudents) > 0){

            foreach($enrolledstudents as $enrolledstudent){

                $getschoolyears = DB::table('enrolledstud')
                    ->select('sy.sydesc')
                    ->join('sy','enrolledstud.syid','=','sy.id')
                    ->where('enrolledstud.levelid',$selectedgradelevel)
                    ->where('enrolledstud.syid','<',$selectedschoolyear)
                    ->where('enrolledstud.deleted','0')
                    ->where('enrolledstud.studid',$enrolledstudent->id)
                    ->get(); 

                if(count($getschoolyears) > 0){

                    $enrolledstudent->schoolyears = $getschoolyears;

                    array_push($repeaters, $enrolledstudent);
                    
                }

            }

        }

        $shenrolledstudents = DB::table('sh_enrolledstud')
            ->select(
                'studinfo.id',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix'
            )
            ->leftJoin('studinfo','sh_enrolledstud.studid','=','studinfo.id')
            ->where('sh_enrolledstud.levelid',$selectedgradelevel)
            ->where('sh_enrolledstud.deleted','0')
            ->get();
            
        if(count($shenrolledstudents) > 0){

            foreach($shenrolledstudents as $shenrolledstudent){

                $getschoolyears = DB::table('sh_enrolledstud')
                    ->select('sy.sydesc')
                    ->join('sy','sh_enrolledstud.syid','=','sy.id')
                    ->where('sh_enrolledstud.levelid',$selectedgradelevel)
                    ->where('sh_enrolledstud.syid','<=',$selectedschoolyear)
                    ->where('sh_enrolledstud.deleted','0')
                    ->where('sh_enrolledstud.studid',$shenrolledstudent->id)
                    ->get(); 

                if(count($getschoolyears) > 0){

                    $shenrolledstudent->schoolyears = $getschoolyears;

                    array_push($repeaters, $shenrolledstudent);
                    
                }

            }

        }

        if($id == 'view' || $id == 'filter'){

            return view('principalsportal.pages.summaries.summaryofretentions')
                ->with('selectedschoolyear',$selectedschoolyear)
                ->with('gradelevels',$gradelevels)
                ->with('selectedgradelevel',$selectedgradelevel)
                ->with('repeaters',$repeaters)
                ->with('schoolyears',$schoolyears);

        }else{

            $schoolinfo = DB::table('schoolinfo')
                ->first();

            $preparedby = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first();

            $dateprepared =date('F d, Y h:i:s A');

            $sy = Db::table('sy')
                ->where('id',$selectedschoolyear)
                ->first();
            
            $pdf = PDF::loadview('principalsportal/pdf/pdf_summaryofretentions',compact('repeaters','schoolyears','selectedgradelevel','selectedschoolyear','gradelevels','selectedgradelevel','schoolinfo','preparedby','dateprepared','sy'))->setPaper('8.5x11');

            return $pdf->stream('Grade Level Retention Report.pdf'); 

        }

    }
    public function summaryattendance($id, Request $request)
    {

        date_default_timezone_set('Asia/Manila');


        if($id == 'view'){

            $selectedgradelevel     = 'all';

            $periodfrom             = date('Y-m-d');

            $periodto               = date('Y-m-d');

        }
        elseif($id == 'filter' || $id == 'print'){

            $selectedgradelevel     = $request->get('selectedgradelevel');

            $daterange              = explode(' - ', $request->get('selectedperiod'));

            $periodfrom             = $daterange[0];

            $periodto               = $daterange[1];

        }

        $begin = new DateTime($periodfrom);

        $end = new DateTime($periodto);

        $end = $end->modify( '+1 day' ); 
        
        $interval = new DateInterval('P1D');

        $daterange = new DatePeriod($begin, $interval ,$end);

        $daysperiod = array();

        foreach($daterange as $date){

            array_push($daysperiod,$date->format("Y-m-d"));
            
        }



        $gradelevels                = DB::table('gradelevel')
                                        ->select(
                                            'gradelevel.id',
                                            'gradelevel.levelname',
                                            'academicprogram.acadprogcode'
                                        )
                                        ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                                        ->orderBy('gradelevel.sortid','asc')
                                        ->where('gradelevel.deleted','0')
                                        ->get();

        $attendancegradelevelsdata  = array();
        
        foreach($gradelevels as $gradelevel){

            // $studentsbylevel                    = array();

            $countmalebylevel                   = 0;

            $countfemalebylevel                 = 0;

            $attendanceperdaystudmalepresent    = 0;

            $attendanceperdaystudmaleabsent     = 0;

            $attendanceperdaystudfemalepresent  = 0;

            $attendanceperdaystudfemaleabsent   = 0;

            // $attendancestudents                 = array();

            if(strtolower($gradelevel->acadprogcode) == 'shs'){

                $students                       =   DB::table('sh_enrolledstud')
                                                    ->select(
                                                        'sh_enrolledstud.studid',
                                                        'studinfo.gender'
                                                    )
                                                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                                                    ->join('sy','sh_enrolledstud.syid','=','sy.id')
                                                    ->where('sh_enrolledstud.levelid',$gradelevel->id)
                                                    ->where('sy.isactive','1')
                                                    ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                                    ->get();




            }else{


                $students                       =   DB::table('enrolledstud')
                                                    ->select(
                                                        'enrolledstud.studid',
                                                        'studinfo.gender'
                                                    )
                                                    ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                                                    ->join('sy','enrolledstud.syid','=','sy.id')
                                                    ->where('enrolledstud.levelid',$gradelevel->id)
                                                    ->where('sy.isactive','1')
                                                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                                                    ->get();
                                                    
            }
            
            if(count($students) > 0){

                $countmalebylevel+= count($students->where('gender','MALE'));

                $countfemalebylevel+= count($students->where('gender','FEMALE'));

                foreach($daysperiod as $workday){

                    foreach($students as $student){

                        $getattendance          =   DB::table('studattendance')
                                                    ->where('studid', $student->studid)
                                                    ->where('tdate',$workday)
                                                    ->get();

                        if(count($getattendance) == 0){

                            if(strtolower($student->gender) == 'male'){

                                $attendanceperdaystudmaleabsent+=1;

                            }
                            elseif(strtolower($student->gender) == 'female'){

                                $attendanceperdaystudfemaleabsent+=1;

                            }

                        }else{

                            if($getattendance[0]->absent == 1){

                                if(strtolower($student->gender) == 'male'){

                                    $attendanceperdaystudmaleabsent+=1;
    
                                }
                                elseif(strtolower($student->gender) == 'female'){
    
                                    $attendanceperdaystudfemaleabsent+=1;
    
                                }

                            }else{

                                if(strtolower($student->gender) == 'male'){

                                    $attendanceperdaystudmalepresent+=1;
    
                                }
                                elseif(strtolower($student->gender) == 'female'){
    
                                    $attendanceperdaystudfemalepresent+=1;
    
                                }

                            }

                        }

                    }

                }
            }

            array_push($attendancegradelevelsdata, (object)array(
                'gradelevel'                        =>  $gradelevel,
                'noofdays'                          =>  count($daysperiod),
                'countmalebylevel'                  =>  $countmalebylevel,
                'countfemalebylevel'                =>  $countfemalebylevel,
                'attendanceperdaystudmalepresent'   =>  $attendanceperdaystudmalepresent,
                'attendanceperdaystudmaleabsent'    =>  $attendanceperdaystudmaleabsent,
                'attendanceperdaystudfemalepresent' =>  $attendanceperdaystudfemalepresent,
                'attendanceperdaystudfemaleabsent'  =>  $attendanceperdaystudfemaleabsent
            ));

        }

        $departments                = DB::table('hr_school_department')
                                        ->where('deleted','0')
                                        ->get();

        $attendancedepartmentsdata  = array();


        foreach($departments as $department){

            $employeesbydepartment  = array();

            // $countemployeesbydepartment = 0;

            $countmalebydepartment      = 0;

            $countfemalebydepartment    = 0;

            $attendanceperdaymalepresent    = 0;

            $attendanceperdaymaleabsent     = 0;

            $attendanceperdayfemalepresent  = 0;

            $attendanceperdayfemaleabsent   = 0;

            $attendance                 = array();

            $designations           = DB::table('usertype')
                                        ->where('departmentid', $department->id)
                                        ->where('deleted','0')
                                        ->get();

            if(count($designations) > 0){

                foreach($designations as $designation){

                    $getemployees   = DB::table('teacher')
                                        ->select(
                                            'teacher.id',
                                            'teacher.lastname',
                                            'teacher.firstname',
                                            'teacher.middlename',
                                            'teacher.suffix',
                                            'employee_personalinfo.gender'
                                        )
                                        ->leftJoin('employee_personalinfo','teacher.id','=','employeeid')
                                        ->where('teacher.usertypeid', $designation->id)
                                        ->where('teacher.isactive','1')
                                        ->get();
                    
                    $countmalebydepartment      +=   count($getemployees->where('gender', 'male'));

                    $countfemalebydepartment    +=   count($getemployees->where('gender', 'female'));
                                        
                    if(count($getemployees) > 0){

                        // $countemployeesbydepartment+=count($getemployees);

                        foreach($daysperiod as $workday){

                            // $attendanceperdaymalepresent    = 0;

                            // $attendanceperdaymaleabsent     = 0;

                            // $attendanceperdayfemalepresent  = 0;

                            // $attendanceperdayfemaleabsent   = 0;

                            foreach($getemployees as $getemployee){

                                $attendance = DB::table('teacherattendance')
                                    ->where('teacher_id', $getemployee->id)
                                    ->where('deleted','0')
                                    ->where('tdate',$workday)
                                    ->get();


                                if(count($attendance) == 0){
    
                                    if(strtolower($getemployee->gender) == "male"){

                                        $attendanceperdaymaleabsent+=1;
    
                                    }
                                    elseif(strtolower($getemployee->gender) == "female"){
                                        
                                        $attendanceperdayfemaleabsent+=1;
    
                                    }
    
                                }else{

                                    if(strtolower($getemployee->gender) == "male"){
                                        
                                        $attendanceperdaymalepresent+=1;
    
                                    }
                                    elseif(strtolower($getemployee->gender) == "female"){
                                        
                                        $attendanceperdayfemalepresent+=1;
    
                                    }

                                }

                            }

                            // array_push($employeesbydepartment,(object)array(
                            //     'day'                       => $workday,
                            //     'attendance'                => (object)array(
                            //                                     'male'      => (object)array(
                            //                                         'present'       => $attendanceperdaymalepresent,
                            //                                         'absent'        => $attendanceperdaymaleabsent
                            //                                     ),
                            //                                     'female'    => (object)array(
                            //                                         'present'       => $attendanceperdayfemalepresent,
                            //                                         'absent'        => $attendanceperdayfemaleabsent
                            //                                     )
                            //                                 )
                            // ));

                        }

                    }             
                                        
                }

            }

            $departmentname                 = str_replace('department', '', $department->department);

            $department->department         = $departmentname;

            array_push($attendancedepartmentsdata, (object)array(
                'department'                =>  $department,
                'noofdays'                  =>  count($daysperiod),
                'totalmalepresent'          =>  $attendanceperdaymalepresent,
                'totalmaleabsent'           =>  $attendanceperdaymaleabsent,
                'totalfemalepresent'        =>  $attendanceperdayfemalepresent,
                'totalfemaleabsent'         =>  $attendanceperdayfemaleabsent,
                'totalmalebydepartment'     =>  $countmalebydepartment,
                'totalfemalebydepartment'   =>  $countfemalebydepartment,
                'totalnumofemployees'       =>  $countmalebydepartment + $countfemalebydepartment
                // 'attendance'                =>  $employeesbydepartment
            ));

        }

        // return $attendancedata;


        if($id == 'view' || $id == 'filter'){

            return view('principalsportal.pages.summaries.summaryattendance')
                ->with('gradelevels',$gradelevels)
                ->with('attendancegradelevelsdata',$attendancegradelevelsdata)
                ->with('attendancedepartmentsdata',$attendancedepartmentsdata)
                ->with('periodfrom',$periodfrom)
                ->with('periodto',$periodto)
                ->with('selectedgradelevel',$selectedgradelevel);

        }

    }

}







// date_default_timezone_set('Asia/Manila');


//         if($id == 'view'){

//             $selectedgradelevel             = 'all';

//             $periodfrom                     = date('Y-m-d');

//             $periodto                       = date('Y-m-d');

//         }
//         elseif($id == 'filter' || $id == 'print'){

//             $selectedgradelevel             = $request->get('selectedgradelevel');

//             $daterange                      = explode(' - ', $request->get('selectedperiod'));

//             $periodfrom                     = $daterange[0];

//             $periodto                       = $daterange[1];

//         }

//         $begin                              = new DateTime($periodfrom);

//         $end                                = new DateTime($periodto);

//         $end                                = $end->modify( '+1 day' ); 
        
//         $interval                           = new DateInterval('P1D');

//         $daterange                          = new DatePeriod($begin, $interval ,$end);

//         $daysperiod                         = array();

//         foreach($daterange as $date){

//             array_push($daysperiod,$date->format("Y-m-d"));
            
//         }


//         $gradelevels                        = DB::table('gradelevel')
//                                                 ->where('deleted','0')
//                                                 ->get();

//         $departments                        = DB::table('hr_school_department')
//                                                 ->where('deleted','0')
//                                                 ->get();

//         $attendancedata                     = array();


//         foreach($departments as $department){

//             $employeesbydepartment          = array();

//             $countemployeesbydepartment     = 0;

//             $countmalebydepartment          = 0;

//             $countfemalebydepartment        = 0;

//             $designations                   = DB::table('usertype')
//                                                 ->where('departmentid', $department->id)
//                                                 ->where('deleted','0')
//                                                 ->get();
            
//             foreach($daysperiod as $workday){

//                 if(count($designations) > 0){

//                     foreach($designations as $designation){

//                         $getemployees       = DB::table('teacher')
//                                                 ->select(
//                                                     'teacher.id',
//                                                     'teacher.lastname',
//                                                     'teacher.firstname',
//                                                     'teacher.middlename',
//                                                     'teacher.suffix',
//                                                     'employee_personalinfo.gender'
//                                                 )
//                                                 ->leftJoin('employee_personalinfo','teacher.id','=','employeeid')
//                                                 ->where('teacher.usertypeid', $designation->id)
//                                                 ->where('teacher.isactive','1')
//                                                 ->get();

                                                
//                         $countmalebydepartment  +=   count($getemployees->where('gender', 'MALE'));

//                         $countfemalebydepartment+=   count($getemployees->where('gender', 'FEMALE'));

//                         $attendanceperdaypresent= array();

//                         $attendanceperdayabsent = array();
                        
//                         if(count($getemployees) > 0){

//                             $countemployeesbydepartment+=count($getemployees);


//                             foreach($getemployees as $getemployee){

//                                 $attendance = DB::table('teacherattendance')
//                                     ->where('teacher_id', $getemployee->id)
//                                     ->where('deleted','0')
//                                     ->where('tdate',$workday)
//                                     ->get();

//                             }

//                         }

//                     }

//                 }


//             }


//             // if(count($designations) > 0){

//                 // foreach($designations as $designation){

//                 //     $getemployees   = DB::table('teacher')
//                 //                         ->select(
//                 //                             'teacher.id',
//                 //                             'teacher.lastname',
//                 //                             'teacher.firstname',
//                 //                             'teacher.middlename',
//                 //                             'teacher.suffix',
//                 //                             'employee_personalinfo.gender'
//                 //                         )
//                 //                         ->leftJoin('employee_personalinfo','teacher.id','=','employeeid')
//                 //                         ->where('teacher.usertypeid', $designation->id)
//                 //                         ->where('teacher.isactive','1')
//                 //                         ->get();

//                 //     $countmalebydepartment      +=   count($getemployees->where('gender', 'MALE'));

//                 //     $countfemalebydepartment    +=   count($getemployees->where('gender', 'FEMALE'));

//                 //     $attendanceperdaypresent   = array();

//                 //     $attendanceperdayabsent = array();
                                        
//                 //     if(count($getemployees) > 0){

//                 //         $countemployeesbydepartment+=count($getemployees);

//                 //         foreach($daysperiod as $workday){


//                 //             foreach($getemployees as $getemployee){

//                 //                 $attendance = DB::table('teacherattendance')
//                 //                     ->where('teacher_id', $getemployee->id)
//                 //                     ->where('deleted','0')
//                 //                     ->where('tdate',$workday)
//                 //                     ->get();


//                 //                 if(count($attendance) == 0){
    
//                 //                     if(strtolower($getemployee->gender) == "male"){

//                 //                         array_push($attendanceperdaymale, (object)array(
//                 //                             'employee'          =>  $getemployee,
//                 //                             'status'            =>  'absent'
//                 //                         ));
    
//                 //                     }
//                 //                     elseif(strtolower($getemployee->gender) == "female"){

//                 //                         array_push($attendanceperdayfemale, (object)array(
//                 //                             'employee'          =>  $getemployee,
//                 //                             'status'            =>  'absent'
//                 //                         ));
    
//                 //                     }
    
//                 //                 }else{

//                 //                     if(strtolower($getemployee->gender) == "male"){
    
//                 //                         array_push($attendanceperdaymale, (object)array(
//                 //                             'employee'          =>  $getemployee,
//                 //                             'status'            =>  'present'
//                 //                         ));
    
//                 //                     }
//                 //                     elseif(strtolower($getemployee->gender) == "female"){

    
//                 //                         array_push($attendanceperdayfemale, (object)array(
//                 //                             'employee'          =>  $getemployee,
//                 //                             'status'            =>  'present'
//                 //                         ));
    
//                 //                     }

//                 //                 }

//                 //             }

//                 //             array_push($employeesbydepartment,(object)array(
//                 //                 'day'                       => $workday,
//                 //                 'attendance'                => (object)array(
//                 //                                                 'male'      => $attendanceperdaymale,
//                 //                                                 'female'    => $attendanceperdayfemale
//                 //                                             )
//                 //             ));

//                 //         }

//                 //     }             
                                        
//                 // }

//             // }?

//             array_push($attendancedata, (object)array(
//                 'department'                =>  $department,
//                 'totalmalebydepartment'     =>  $countmalebydepartment,
//                 'totalfemalebydepartment'   =>  $countfemalebydepartment,
//                 'totalnumofemployees'       =>  $countemployeesbydepartment,
//                 'attendance'                =>  $employeesbydepartment
//             ));

//         }

//         // return $attendancedata;


//         if($id == 'view' || $id == 'filter'){

//             return view('principalsportal.pages.summaries.summaryattendance')
//                 ->with('attendancedata',$attendancedata)
//                 ->with('gradelevels',$gradelevels)
//                 // ->with('selectedgradelevel',$selectedgradelevel)
//                 ->with('periodfrom',$periodfrom)
//                 ->with('periodto',$periodto)
//                 ->with('selectedgradelevel',$selectedgradelevel);

//         }