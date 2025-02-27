<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
class RCFGController extends Controller
{
    public function index(Request $request)
    {
        $students = DB::table('college_enrolledstud')
            ->select('studinfo.id','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','studinfo.gender')
            ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
            ->where('college_enrolledstud.deleted','0')
            ->where('studinfo.deleted','0')
            ->whereIn('college_enrolledstud.studstatus',[1,2,4])
            ->orderBy('lastname','asc')
            ->distinct()
            ->get();

        return view('registrar.forms.rcfg.index')
            ->with('students', $students);
    }
    public function getrecords(Request $request)
    {
        $gradelevels = DB::table('gradelevel')
            ->where('acadprogid','6')
            ->orderBy('sortid','asc')
            ->where('deleted','0')
            ->get();


        $schoolyears = Db::table('sy')
            ->select('id as syid','sydesc'
            ,'isactive'
            )
            ->orderByDesc('sydesc')
            ->get();
            
        $records = \App\Models\College\TOR::getrecords($request->get('studid'), $schoolyears);
        // return $records;
        $collectgradelevels = array();

        $semesters = DB::table('semester')
            ->where('deleted','0')
            ->get();
            
        foreach($gradelevels as $gradelevel)
        {
            for($x=0; $x<count($semesters); $x++)
            {
                // $gradelevel->semid = $semesters[$x]->id; 
                // echo $semesters[$x]->id;
                array_push($collectgradelevels,(object)array(
                    'id'                => $gradelevel->id,
                    'levelname'                => $gradelevel->levelname,
                    'acadprogid'                => $gradelevel->acadprogid,
                    'sortid'                => $gradelevel->sortid,
                    'semid'                =>  $semesters[$x]->id
                )
                );
            }
        }
        $coursename = null;
        $major = null;
        // return $collectgradelevels;
        // $enrollmentdetails = array();
        //check enrollment details
        foreach($collectgradelevels as $collectgradelevel)
        {

            $collectgradelevel->withdata = 0;
            $collectgradelevel->display = 0;
            $collectgradelevel->syid = 0;
            $collectgradelevel->sydesc = null;
            $collectgradelevel->details  = array();

            $checkenrollment = DB::table('college_enrolledstud')
                ->select('college_enrolledstud.*','sy.sydesc','college_sections.sectionDesc as sectionname')
                ->join('sy','college_enrolledstud.syid','sy.id')
                ->join('college_sections','college_enrolledstud.sectionID','college_sections.id')
                ->where('college_enrolledstud.yearLevel', $collectgradelevel->id)
                ->where('college_enrolledstud.semid', $collectgradelevel->semid)
                ->where('college_enrolledstud.studid', $request->get('studid'))
                ->where('college_enrolledstud.deleted','0')
                ->get();

            if(count($checkenrollment)>0)
            {
                $collectgradelevel->withdata = 1;
                $collectgradelevel->syid = $checkenrollment[0]->syid;
                $collectgradelevel->sydesc = $checkenrollment[0]->sydesc;
                $collectgradelevel->details  = $checkenrollment;
                $coursename = DB::table('college_courses')
                    ->where('id', $checkenrollment[0]->courseid)
                    ->where('deleted','0')
                    ->first()->courseDesc;

            }
            $subjects = array();
            if($collectgradelevel->syid >0)
            {
                $subjects = self::collegestudentsched_plot($request->get('studid'), $collectgradelevel->syid, $collectgradelevel->semid);
                $getthegrades = collect($records)->where('syid',$collectgradelevel->syid)->where('semid', $collectgradelevel->semid)->first()->subjdata ?? array();
                // return $getthegrades;

                if(count($subjects)>0)
                {
                    if($subjects[0]->status == 1 )
                    {
                        $subjects = (object)collect($subjects[0]->info)->map(function ($item, $key) use ($getthegrades){
                            $item->subjgrade = collect($getthegrades)->where('subjcode',$item->subjCode)->first()->subjgrade ?? null;
                            // return $item->subjgrade;
                            return collect($item)->forget('schedule');
                        })->values()->all();
                        // return collect($subjects);
                    }
                }
            }
            if(collect($subjects)->count()>0)
            {
                foreach($subjects as $subject)
                {
                    $checkifexistssubj = DB::table('rcfg_records')
                        ->where('studid', $request->get('studid'))
                        ->where('syid', $collectgradelevel->syid)
                        ->where('semid', $collectgradelevel->semid)
                        ->where('subjectid', $subject['subjectID'])
                        ->where('deleted','0')
                        ->first();

                    if($checkifexistssubj)
                    {
                        $subject['subjgroupid'] = $checkifexistssubj->subjgroupid;
                    }else{
                        $subject['subjgroupid'] = 0;
                    }
                }
            }

            $collectgradelevel->subjects = collect($subjects)->values();
        }
        
        if (str_contains(strtolower($coursename), 'major in')) { 
            $major =  strtoupper(str_replace('major in ', '', strtolower(substr($coursename, strpos(strtolower($coursename), 'major in ')))));
            $coursename = strtoupper(explode(' major',strtolower($coursename))[0]);
        }
        $subjgroups = DB::table('setup_subjgroups')
            ->where('deleted','0')
            ->orderBy('sortnum','asc')
            ->groupBy('sortnum')
            ->get();

        $studinfo = DB::table('studinfo')
            ->where('id', $request->get('studid'))
            ->first();
            
        $studinfo->dob = date('m/d/Y', strtotime($studinfo->dob));
        $today = date("Y-m-d");

        try{
            $diff = date_diff(date_create($studinfo->dob), date_create($today));

            $studinfo->age = $diff->format('%y');

            $firstcomparison = ['01','02','03','04','05'];

            if(in_array(date('m', strtotime($studinfo->dob)),$firstcomparison)){

                $studinfo->age = ((int)$studinfo->age - 1);

            }
        }catch(\Exception $error)
        {
            $studinfo->age = null;
        }
        $details = DB::table('college_tordetail')
        ->select('college_tordetail.*','dob','gender','mothername','fathername','parentaddress','guardianaddress')
        ->where('studid', $studinfo->id)
        ->join('studinfo','college_tordetail.studid','studinfo.id')
        ->where('college_tordetail.deleted','0')
        ->first();
            // return $collectgradelevels;
        if($request->ajax())
        {
            return view('registrar.forms.rcfg.results')
                ->with('details', $details)
                ->with('studinfo', $studinfo)
                ->with('degree', $details->degree ?? '')
                ->with('coursename', $coursename)
                ->with('subjgroups', $subjgroups)
                ->with('collectgradelevels', $collectgradelevels);
        }else{
            $degree = $request->get('degree');
            $entrancedata = $request->get('entrancedata');
            $checkedby = $request->get('checkedby');
            $collegereg = $request->get('collegereg');
            $intermediatecourse = $request->get('intermediatecourse');
            $intermediateyear = $request->get('intermediateyear');
            $secondarycourse = $request->get('secondarycourse');
            $secondaryyear = $request->get('secondaryyear');
            // return $intermediateyear;
            if($details)
            {
                DB::table('college_tordetail')
                    ->where('studid',$studinfo->id)
                    ->where('deleted',0)
                    ->update([
                        'degree'            => $degree,
                        'entrancedata'      => $entrancedata,
                        'secondcourse'      => $secondarycourse,            
                        'secondsy'          => $secondaryyear,            
                        'intermediatecourse'   => $intermediatecourse,
                        'intermediatesy'   => $intermediateyear,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s'),
                    ]);
            }else{
                DB::table('college_tordetail')
                    ->insert([
                        'studid'            => $studinfo->id,
                        'degree'            => $degree,
                        'entrancedata'      => $entrancedata,
                        'secondcourse'      => $secondarycourse,            
                        'secondsy'          => $secondaryyear,            
                        'intermediatecourse'   => $intermediatecourse,
                        'intermediatesy'   => $intermediateyear,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s'),
                    ]);
            }
            // return $collectgradelevels;
            if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
            {
        
                if(!$details)
                {
                    $details = (object)array(
                        'studid'            => null,
                        'parentguardian'    => null,
                        'address'           => null,
                        'elemcourse'        => null,
                        'elemdatecomp'      => null,
                        'secondcourse'      => null,
                        'seconddatecomp'    => null,
                        'admissiondate'     => null,
                        'degree'            => null,
                        'basisofadmission'  => null,
                        'major'             => null,
                        'specialorder'      => null,
                        'elemsy'           => null,
                        'secondsy'           => null,
                        'thirdsy'           => null,
                        'remarks'           => null,
                        'graduationdate'    => null,
                        'admissiondatestr'    => null,
                        'collegeof'    => null,
                        'entrancedata'    => null,
                        'intermediategrades'    => null,
                        'secondarygrades'    => null,
                        'secondarygrades'   => null,
                        'dob'               => null,
                        'gender'               => null,
                        'mothername'               => null,
                        'fathername'               => null,
                        'pob'               => null,
                        'acrno'             => null,
                        'citizenship'       => null,
                        'civilstatus'       => null,
                        'parentaddress'     => null,
                        'guardianaddress'   => null
                    );
                }
                if($details->elemdatecomp != null)
                {
                    $details->elemdatecomp = date('m/d/Y', strtotime($details->elemdatecomp));
                }
                if($details->seconddatecomp != null)
                {
                    $details->seconddatecomp = date('m/d/Y', strtotime($details->seconddatecomp));
                }
                if($details->admissiondate != null)
                {
                    $details->admissiondate = date('m/d/Y', strtotime($details->admissiondate));
                }
                if($details->graduationdate != null)
                {
                    $details->graduationdate = date('m/d/Y', strtotime($details->graduationdate));
                }
                $pdf = PDF::loadview('registrar/forms/rcfg/pdf_rcfg_mci', compact('studinfo','subjgroups','collectgradelevels','degree','entrancedata','checkedby','collegereg','details','major','coursename')); //mci
            }else{
                // return $studinfo->fathername;
                $pdf = PDF::loadview('registrar/forms/rcfg/pdf_rcfg', compact('studinfo','subjgroups','collectgradelevels','degree','entrancedata','checkedby','collegereg','details','coursename')); //sbc
            }
            return $pdf->stream('Record Of Candidate For Graduation - '.$studinfo->lastname.' - '.$studinfo->firstname.'.pdf'); 
        }
    }

    public static function collegestudentsched_plot($studid = null , $syid = null ,$semid = null){

        try{

              $subjects = DB::table('college_studsched')
                    ->join('college_classsched',function($join) use($syid,$semid){
                            $join->on('college_studsched.schedid','=','college_classsched.id');
                            $join->where('college_classsched.deleted',0);
                            $join->where('college_classsched.syid',$syid);
                            $join->where('college_classsched.semesterID',$semid);
                    })
                    ->join('college_prospectus',function($join){
                            $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            $join->where('college_prospectus.deleted',0);
                    })
                    ->join('college_sections',function($join){
                            $join->on('college_classsched.sectionID','=','college_sections.id');
                            $join->where('college_sections.deleted',0);
                    })
                    ->where('college_studsched.deleted',0)
                    ->where('college_studsched.studid',$studid)
                    ->select(
                            'lecunits',
                            'labunits',
                            'college_prospectus.subjectID as main_subjid',
                            'college_classsched.*',
                            'schedid',
                            'subjDesc',
                            'subjCode',
                            'sectionDesc',
                            'schedstatus'
                    )
                    ->get();

              foreach($subjects as $item){

                    $item->units = $item->lecunits + $item->labunits;

                    $sched = DB::table('college_scheddetail')
                                      ->where('college_scheddetail.headerid',$item->id)
                                      ->where('college_scheddetail.deleted',0)
                                      ->leftJoin('rooms',function($join){
                                            $join->on('college_scheddetail.roomid','=','rooms.id');
                                            $join->where('rooms.deleted',0);
                                      })
                                      ->join('days',function($join){
                                            $join->on('college_scheddetail.day','=','days.id');
                                      })
                                      ->select(
                                            'day',
                                            'roomid',
                                            'college_scheddetail.id as detailid',
                                            'roomname',
                                            'stime',
                                            'etime',
                                            'days.description',
                                            'schedotherclass'
                                      )
                                      ->get();

                    $item->teacher = null;
                    $item->teacherid = null;

                    if(isset($item->teacherID)){
                          $temp_teacher = DB::table('teacher')
                                            ->where('id',$item->teacherID)
                                            ->first();
                          $item->teacher = $temp_teacher->firstname.' '.$temp_teacher->middlename.' '.$temp_teacher->lastname;
                          $item->teacherid = $temp_teacher->tid;
                    }
              


                    foreach($sched as $sched_item){
                          $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                    }

                    $starting = collect($sched)->groupBy('time');

                    $sched_list = array();
                    $sched_count = 1;

                    foreach($starting as $sched_item){
                          
                          $dayString = '';
                          $days = array();

                          foreach($sched_item as $new_item){
                                $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                                $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                                $dayString.= substr($new_item->description, 0,3).' / ';
                                $detailid = $new_item->detailid;
                                $roomname = $new_item->roomname;
                                $roomid = $new_item->roomid;
                                $time = $new_item->time;
                                $schedotherclass = $new_item->schedotherclass;
                                array_push($days,$new_item->day);
                          }

                          $dayString = substr($dayString, 0 , -2);
                          
                          array_push($sched_list,(object)[
                                'day'=>$dayString,
                                'start'=>$start,
                                'end'=>$end,
                                'roomid',
                                'detailid'=>$detailid,
                                'roomname'=>$roomname,
                                'roomid'=>$roomid,
                                // 'teacher'=>$teacher,
                                // 'tid'=>$tid,
                                // 'teacherid'=>$teacherid,
                                'sched_count'=>$sched_count,
                                'time'=>$time,
                                'days'=>$days,
                                'classification'=>$schedotherclass
                          ]);


                          $sched_count += 1;

                    }
                    $item->schedule = $sched_list;


              }
              
              return array((object)[
                    'status'=>1,
                    'data'=>'Successfull.',
                    'info'=>$subjects
              ]);

        }catch(\Exception $e){
              return self::store_error($e);
        }


  }
    public function subjgroupunitplot(Request $request)
    {
        $studid       = $request->get('studid');
        $syid         = $request->get('syid');
        $semid        = $request->get('semid');
        $subjectid    = $request->get('subjectid');
        $subjgroupid  = $request->get('subjgroupid');

        $checkifexists = DB::table('rcfg_records')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('subjectid', $subjectid)
                ->where('deleted','0')
                ->first();
          
        if($checkifexists)
        {
                DB::table('rcfg_records')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'subjgroupid'       => $subjgroupid,
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
                return 1;
        }else{
            try{
                DB::table('rcfg_records')
                    ->insert([
                        'studid'           => $studid,
                        'syid'             => $syid,
                        'semid'            => $semid,
                        'subjectid'        => $subjectid,
                        'subjgroupid'      => $subjgroupid,
                        'createdby'        => auth()->user()->id,
                        'createddatetime'  => date('Y-m-d H:i:s')
                    ]);
                return 1;
            }catch(\Exception $error)
            {
                return 'error';
            }
        }
    }
}
