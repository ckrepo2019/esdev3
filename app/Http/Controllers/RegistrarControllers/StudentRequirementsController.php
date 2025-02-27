<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;
use Image;
use \Carbon\Carbon;
use PDF;
class StudentRequirementsController extends Controller
{
    public function studentrequirementsindex(Request $request)
    {

        $requirementslist = DB::table('preregistrationreqlist')
            ->select('id','description')
            ->where('isActive','1')
            ->where('deleted','0')
            ->get();

            
        // $enrolledstuds = DB::table('enrolledstud')
        //     ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','studinfo.sid','studinfo.lrn','gradelevel.id as levelid','academicprogram.acadprogcode','gradelevel.levelname','sections.sectionname')
        //     ->join('studinfo','enrolledstud.studid','=','studinfo.id')
        //     ->join('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
        //     ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
        //     ->join('sections','enrolledstud.sectionid','=','sections.id')
        //     ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
        //     ->where('enrolledstud.studstatus','!=','0')
        //     ->where('studinfo.studstatus','!=','0')
        //     ->where('studinfo.studstatus','!=','6')
        //     ->where('enrolledstud.deleted','0')
        //     ->where('studinfo.deleted','0')
        //     ->orderBy('lastname','asc')
        //     ->get();

        // $sh_enrolledstuds = DB::table('sh_enrolledstud')
        //     ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','studinfo.sid','studinfo.lrn','gradelevel.id as levelid','academicprogram.acadprogcode','gradelevel.levelname','sections.sectionname')
        //     ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
        //     ->join('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
        //     ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
        //     ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
        //     ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
        //     ->where('sh_enrolledstud.studstatus','!=','0')
        //     ->where('studinfo.studstatus','!=','0')
        //     ->where('studinfo.studstatus','!=','6')
        //     ->where('sh_enrolledstud.deleted','0')
        //     ->where('studinfo.deleted','0')
        //     ->orderBy('lastname','asc')
        //     ->get();

        // $co_enrolledstuds = DB::table('college_enrolledstud')
        //     ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','studinfo.sid','studinfo.lrn','gradelevel.id as levelid','academicprogram.acadprogcode','gradelevel.levelname','college_sections.sectionDesc as sectionname')
        //     ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
        //     ->join('studentstatus','college_enrolledstud.studstatus','=','studentstatus.id')
        //     ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
        //     ->join('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
        //     ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
        //     ->where('college_enrolledstud.studstatus','!=','0')
        //     ->where('studinfo.studstatus','!=','0')
        //     ->where('studinfo.studstatus','!=','6')
        //     ->where('college_enrolledstud.deleted','0')
        //     ->where('studinfo.deleted','0')
        //     ->orderBy('lastname','asc')
        //     ->get();
        
        // $students = collect();
        // $students = $students->merge($enrolledstuds);
        // $students = $students->merge($sh_enrolledstuds);
        // $students = $students->merge($co_enrolledstuds);
        // $students = collect($students)->unique('id');
        // $students = collect($students)->sortBy('lastname')->values();

        // if(count($students)>0)
        // {
        //     foreach($students as $student)
        //     {
        //         // return collect($student);
        //         $studentlrn = $student->lrn;
        //         $studentsid = $student->sid;

        //         $queuecoderef = 2;

        //         $completed = 0;

        //         $submittedreqs = DB::table('preregistrationrequirements')
        //             ->where('preregistrationrequirements.deleted','0')
        //             // ->where('preregistrationrequirements.preregreqtype',$reqlist->id)
        //             ->where(function($query) use($studentlrn, $studentsid, $queuecoderef){
        //                 if($studentlrn != null)
        //                 {
        //                     $queuecoderef = 1; // lrn
        //                     $query->orWhere('qcode','=',$studentlrn);
        //                 }
        //                 if($studentsid != null)
        //                 {
        //                     $queuecoderef = 2; // sid
        //                     $query->orWhere('qcode','=',$studentsid);
        //                 }
        //             })
        //             ->get();


        //         // return $submittedreqs;

        //         $reqsresult = array();
        //         if(count($requirementslist)>0)
        //         {
        //             foreach($requirementslist as $reqlist)
        //             {
        //                 $submittedstat = 0;

        //                 $eachsubmittedreqs = collect($submittedreqs)->where('preregreqtype', $reqlist->id)->values();
        //                     // return $submittedreqs;
        //                 $submittedreqid = 0;

        //                 if(count($eachsubmittedreqs) > 0)
        //                 {
        //                     $completed+=1;
        //                     $submittedstat = 1;
        //                     $submittedreqid = $eachsubmittedreqs[0]->id;
        //                 }
        //                 array_push($reqsresult,(object)array(
        //                     'id'                 => $reqlist->id,
        //                     'description'        => $reqlist->description,
        //                     'submitted'          => $submittedstat,
        //                     'submittedreqid'              => $submittedreqid
        //                 ));
        //             }
        //         }
        //         // return $requirementslist;
        //         $student->requirements = $reqsresult;
        //         $student->queuecoderef = $queuecoderef;
        //         if(count($requirementslist) == $completed)
        //         {
        //             // return collect($student);
        //             $student->status = 1; //'Complete';
        //         }else{
        //             $student->status = 0; //'Incomplete';
        //         }
        //         // return $student->requirements ;
        //     }
        // }
        $gradelevels = DB::table('gradelevel')
            ->where('deleted','0')
            ->orderBy('sortid','asc')
            ->get();

        // $enrolledstudents = $students->merge($coenrolledstuds);
        // $filteredstudents = $students->unique('id')->sortBy('lastname')->values()->all();
        // $students = DB::table('studinfo')
        //     ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','studinfo.sid','studinfo.lrn')
        //     ->join('studentstatus','studinfo.studstatus','=','studentstatus.id')
        //     ->where('studstatus','!=','0')
        //     ->where('deleted','0')
        //     ->orderBy('lastname','asc')
        //     ->get();

        // return collect($students)->where('status','1')->pluck('requirements');
        return view('registrar.summaries.studentrequirements.index')
            ->with('gradelevels',$gradelevels)
            // ->with('students',$students)
            ;
    }
    public function studentrequirementsresults(Request $request)
    {
        


            
        $enrolledstuds = DB::table('enrolledstud')
            ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','studinfo.sid','studinfo.lrn','gradelevel.id as levelid','academicprogram.acadprogcode','gradelevel.levelname','sections.sectionname')
            ->join('studinfo','enrolledstud.studid','=','studinfo.id')
            ->join('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
            ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
            ->join('sections','enrolledstud.sectionid','=','sections.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('enrolledstud.studstatus','!=','0')
            ->where('studinfo.studstatus','!=','0')
            ->where('studinfo.studstatus','!=','6')
            ->where('enrolledstud.deleted','0')
            ->where('studinfo.deleted','0')
            ->orderBy('lastname','asc')
            ->get();

        $sh_enrolledstuds = DB::table('sh_enrolledstud')
            ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','studinfo.sid','studinfo.lrn','gradelevel.id as levelid','academicprogram.acadprogcode','gradelevel.levelname','sections.sectionname')
            ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
            ->join('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
            ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
            ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('sh_enrolledstud.studstatus','!=','0')
            ->where('studinfo.studstatus','!=','0')
            ->where('studinfo.studstatus','!=','6')
            ->where('sh_enrolledstud.deleted','0')
            ->where('studinfo.deleted','0')
            ->orderBy('lastname','asc')
            ->get();

        $co_enrolledstuds = DB::table('college_enrolledstud')
            ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','studinfo.sid','studinfo.lrn','gradelevel.id as levelid','academicprogram.acadprogcode','gradelevel.levelname','college_sections.sectionDesc as sectionname')
            ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
            ->join('studentstatus','college_enrolledstud.studstatus','=','studentstatus.id')
            ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
            ->join('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('college_enrolledstud.studstatus','!=','0')
            ->where('studinfo.studstatus','!=','0')
            ->where('studinfo.studstatus','!=','6')
            ->where('college_enrolledstud.deleted','0')
            ->where('studinfo.deleted','0')
            ->orderBy('lastname','asc')
            ->get();
        
        $students = collect();
        $students = $students->merge($enrolledstuds);
        $students = $students->merge($sh_enrolledstuds);
        $students = $students->merge($co_enrolledstuds);
        $students = collect($students)->unique('id');
        $students = collect($students)->sortBy('lastname')->values();

        if($request->get('levelid')!= null)
        {
            $students = collect($students)->where('levelid',$request->get('levelid'))->values();
        }

        $filterwherein = collect($students)->pluck('lrn');
        $filterwherein = $filterwherein->merge(collect($students)->pluck('sid'));
        $filterwherein = collect($filterwherein)->filter(function ($value) { return !is_null($value); })->values();
        // return $filterwherein;
        $submittedreqs = DB::table('preregistrationrequirements')
        ->where('preregistrationrequirements.deleted','0')
        // ->where('preregistrationrequirements.preregreqtype',$reqlist->id)
        ->whereIn('qcode',$filterwherein)
        ->where('deleted','0')
        ->get();

        if(count($students)>0)
        {
            foreach($students as $student)
            {
                $reqstat = DB::table('studreqstat')
                    ->where('studid',$student->id)
                    ->where('deleted','0')
                    ->get();
    
                $acadprogid = DB::table('gradelevel')
                    ->where('id', $student->levelid)
                    ->select('acadprogid')
                    ->first();

                $progid = $acadprogid->acadprogid;
                $requirementslist = DB::table('preregistrationreqlist')
                    ->select('id','description')
                    // ->where('acadprogid',$acadprogid->acadprogid)
                    ->where(function($query) use($progid){
                        $query->orWhere('acadprogid','=',$progid);
                        $query->orWhere('acadprogid','=',null);
                    })
                    ->where('isActive','1')
                    ->where('levelid',$student->levelid)
                    ->where('deleted','0')
                    ->get();
                // return collect($student);
                $studentlrn = $student->lrn;
                $studentsid = $student->sid;

                $queuecoderef = 2;

                $completed = 0;


                // $eachsubmittedreqs = DB::table('preregistrationrequirements')
                //     ->where('preregistrationrequirements.deleted','0')
                //     // ->where('preregistrationrequirements.preregreqtype',$reqlist->id)
                //     ->where(function($query) use($studentlrn, $studentsid, $queuecoderef){
                //         if($studentlrn != null)
                //         {
                //             $queuecoderef = 1; // lrn
                //             $query->orWhere('qcode','=',$studentlrn);
                //         }
                //         if($studentsid != null)
                //         {
                //             $queuecoderef = 2; // sid
                //             $query->orWhere('qcode','=',$studentsid);
                //         }
                //     })
                    // ->get();
                $eachsubmitted = collect($submittedreqs)->where('qcode',$studentlrn)->values();
                $queuecoderef = 1; // lrn
                if(count($eachsubmitted) == 0)
                {
                    $queuecoderef = 2; // sid
                    $eachsubmitted = collect($submittedreqs)->where('qcode',$studentsid)->values();
                }


                // return $eachsubmitted;

                $reqsresult = array();
                if(collect($requirementslist)->count()>0)
                {
                    foreach($requirementslist as $reqlist)
                    {
                        $stat = collect($reqstat)->where('reqtypeid', $reqlist->id)->values();
    
                        if(count($stat) == 0)
                        {
                            $reqlist->status = 0;
                        }else{
                            $reqlist->status = $stat[0]->reqstatus;
                        }

                        $submittedstat = 0;

                        $eachsubmittedreqs = collect($eachsubmitted)->where('preregreqtype', $reqlist->id)->values();
                            // return $eachsubmittedreqs;
                        $submittedreqid = 0;

                        if(count($eachsubmittedreqs) > 0)
                        {
                            $completed+=1;
                            $submittedstat = 1;
                            $submittedreqid = $eachsubmittedreqs[0]->id;
                        }
                        array_push($reqsresult,(object)array(
                            'id'                 => $reqlist->id,
                            'description'        => $reqlist->description,
                            'status'          => $reqlist->status,
                            'submitted'          => $submittedstat,
                            'submittedreqid'              => $submittedreqid
                        ));
                    }
                }
                // return $requirementslist;
                $student->requirements = $reqsresult;
                $student->queuecoderef = $queuecoderef;
                if(count($requirementslist) == $completed)
                {
                    // return collect($student);
                    $student->status = 1; //'Complete';
                }else{
                    $student->status = 0; //'Incomplete';
                }
                // return $student->requirements ;
            }
        }
        if(!$request->has('exporttype'))
        {
            return view('registrar.summaries.studentrequirements.viewresults')
                ->with('students',$students);
        }else{
            if($request->get('exporttype') == 'pdf')
            {
                $levelid = $request->get('levelid');
                // $students = collect($students)->take(5);
                // return $students;
                $pdf = PDF::loadview('registrar/pdf/pdf_studentreqs',compact('students','requirementslist','levelid'))->setPaper('legal','portrait');
                set_time_limit(30000);
                return $pdf->stream('Student Requirements Summary');
            }
        }
    }
    public function studentrequirementsgetinfo(Request $request)
    {
        // if($request->ajax())
        // {
            $studentid  = $request->get('studentid');
            $studentsid = $request->get('sid');
            $studentlrn = $request->get('lrn');

            $student   = DB::table('studinfo')
                ->where('id', $studentid)
                ->first();

            $acadprogid = DB::table('studinfo')
                ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                ->where('studinfo.id', $studentid)
                ->select('acadprogid')
                ->first();



            $progid = $acadprogid->acadprogid;
            $requirementslists = DB::table('preregistrationreqlist')
                ->select('id','description')
                ->where('isActive','1')
                // ->where('acadprogid')
                ->where(function($query) use($progid){
                    $query->orWhere('acadprogid','=',$progid);
                    $query->orWhere('acadprogid','=',null);
                })
                ->where('deleted','0')
                ->where('levelid',$student->levelid)
                ->get();
    
            $reqstat = DB::table('studreqstat')
                ->where('studid',$studentid)
                ->where('deleted','0')
                ->get();

            if(count($requirementslists)>0)
            {
                foreach($requirementslists as $requirementslist)
                {
                    $stat = collect($reqstat)->where('reqtypeid', $requirementslist->id)->values();

                    if(count($stat) == 0)
                    {
                        $requirementslist->status = 0;
                    }else{
                        $requirementslist->status = $stat[0]->reqstatus;
                    }
                    // $submittedreqs = DB::table('preregistrationrequirements')
                    //     ->where('preregistrationrequirements.deleted','0')
                    //     ->where('preregistrationrequirements.preregreqtype',$requirementslist->id)
                    //     ->where(function($query) use($studentlrn, $studentsid){
                    //         if($studentlrn != null)
                    //         {
                    //             $query->orWhere('qcode','=',$studentlrn);
                    //         }
                    //         if($studentsid != null)
                    //         {
                    //             $query->orWhere('qcode','=',$studentsid);
                    //         }
                    //     })
                    //     ->get();
    
                    // if(count($submittedreqs)==0)
                    // {
                    //     $requirementslist->submitted = 0;
                    // }else{
                    //     $requirementslist->submitted = 1;
                    // }
                }
            }
    
            return $requirementslists;
        // }
    }
    public function studentrequirementsupdatestat(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        $checkifexists = DB::table('studreqstat')
            ->where('studid',$request->get('studentid'))
            ->where('reqtypeid',$request->get('reqid'))
            ->where('deleted','0')
            ->first();

        try{
            if($checkifexists)
            {
                DB::table('studreqstat')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'reqstatus'         => $request->get('reqstatus'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
            else{
                DB::table('studreqstat')
                    ->insert([
                        'studid'        => $request->get('studentid'),
                        'reqtypeid'        => $request->get('reqid'),
                        'reqstatus'        => $request->get('reqstatus'),
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);
            }
            return 1;

        }catch(\Exception $error)
        {
            return 0;
        }


    }
    public function studentrequirementsgetphoto(Request $request)
    {
        // return $request->all();
//return "sdsdf";
        $reqid          = $request->get('reqid');
        $queuecoderef          = $request->get('queuecoderef'); // 1 = lrn; 2 = sid;

        $reqname        = Db::table('preregistrationreqlist')
                            ->where('id', $reqid)
                            ->first()->description;
                            

        $studname       = Db::table('studinfo')->where('id',$request->get('studid'))->first();

        $submittedreqid = $request->get('submittedreqid');

        $photoinfo = DB::table('preregistrationrequirements')
            // ->select('preregistrationrequirements.*','preregistrationreqlist.description')
            // ->join('preregistrationreqlist','preregistrationrequirements.preregreqtype','=','preregistrationreqlist.id')
            ->where('preregistrationrequirements.id', $submittedreqid)
            ->first();
        
		// return collect($photoinfo);
		if($photoinfo)
		{
        $photoinfo->otherpicurl = $photoinfo->picurl;
		
        $photoinfo->picurl = str_replace('/', '/' . $photoinfo->qcode . '/', $photoinfo->picurl);
		}else{
			$photoinfo = (object)array(
				'id'	=> '',
				'otherpicurl'	=> '',
				'picurl'	=> ''
			);
		}

		
        return view('registrar.summaries.studentrequirements.viewphoto')
            ->with('photoinfo',$photoinfo)
            ->with('queuecoderef',$queuecoderef)
            ->with('reqid',$reqid)
            ->with('reqname',$reqname)
            ->with('studname',$studname)
            ->with('studid',$request->get('studid'));
    }
    public function studentrequirementsgetphotos(Request $request)
    {
        $studinfo       = Db::table('studinfo')->where('id',$request->get('studid'))->first();
        $acadprogid = DB::table('studinfo')
            ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
            ->where('studinfo.id', $studinfo->id)
            ->select('acadprogid')
            ->first();

        $progid = $acadprogid->acadprogid;

        $requirementslists = DB::table('preregistrationreqlist')
            ->select('id','description')
            ->where('isActive','1')
            // ->where('acadprogid')
            ->where(function($query) use($progid){
                $query->orWhere('acadprogid','=',$progid);
                $query->orWhere('acadprogid','=',null);
            })
            ->where('deleted','0')
            ->where('levelid',$studinfo->levelid)
            ->get();

        if(count($requirementslists)>0)
        {
            foreach($requirementslists as $requirementslist)
            {
                $photoinfo = DB::table('preregistrationrequirements')
                    // ->select('preregistrationrequirements.*','preregistrationreqlist.description')
                    // ->join('preregistrationreqlist','preregistrationrequirements.preregreqtype','=','preregistrationreqlist.id')
                    ->whereIn('qcode',[$studinfo->sid, $studinfo->lrn])
                    ->where('preregreqtype', $requirementslist->id)
                    ->where('deleted','0')
                    ->first();

                if($photoinfo)
                {
                    $requirementslist->picurl = $photoinfo->picurl;
                }else{
                    $requirementslist->picurl = "none";
                }
            }
        }
	
		return $requirementslists;
        
        return view('registrar.summaries.studentrequirements.viewphotos')
            ->with('requirements',$requirementslists)
            ->with('studinfo',$studinfo)
            ->with('studid',$request->get('studid'));
    }
    public function studentrequirementsuploadphoto(Request $request)
    {
        // return $request->all();
        $studentinfo = DB::table('studinfo')
            ->where('id', $request->get('studid'))
            ->first();

        $reqid      = $request->get('reqid');
        $queuecoderef = $request->get('queuecoderef');
        $queuecode  = $studentinfo->sid;
        if($queuecoderef == 1)
        {
            $queuecode  = $studentinfo->lrn;
        }
        $extension  = 'png';
        
        // if($request->has('input-photo')){

            $urlFolder = str_replace('https://','',$request->root());

            if (! File::exists(public_path().'preregrequirements/'.$queuecode.'/')) {

                $path = public_path('preregrequirements/'.$queuecode.'/');

                if(!File::isDirectory($path)){

                    File::makeDirectory($path, 0777, true, true);
                }
            }

            if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/preregrequirements/'.$queuecode.'/')) {

                $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/preregrequirements/'.$queuecode.'/';

                if(!File::isDirectory($cloudpath)){

                    File::makeDirectory($cloudpath, 0777, true, true);

                }
                
            }


            $img = Image::make($request->file('input-photo')->path());
            $time = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss');

            $destinationPath = public_path('preregrequirements/'.$queuecode.'/'.'requirement'.$reqid.'-'.$time.'.'.$extension);

            $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.'preregrequirements/'.$queuecode.'/'.'requirement'.$reqid.'-'.$time.'.'.$extension;

            $img->resize(1000, 1000, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($destinationPath);


            $img->save($clouddestinationPath);


            // $img->resize(500, 500, function ($constraint) {
            //                 $constraint->aspectRatio();
            //             })->resizeCanvas(500, 500,'center')->save($clouddestinationPath);

            $datetime = Carbon::now('Asia/Manila');
                
            DB::table('preregistrationrequirements')    
                        ->insert([
                            'picurl'=>'preregrequirements/'.$queuecode.'/requirement'.$reqid.'-'.$time.'.'.$extension,
                            'qcode'=>$queuecode,
                            'preregreqtype'=>$reqid,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>$datetime
                        ]);

        // }
        return back();
    }
    public function studentrequirementsdeletephoto(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        try{
            DB::table('preregistrationrequirements')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'               => 1,
                    'deletedby'             => auth()->user()->id,
                    'deleteddatetime'       => date('Y-m-d H:i:s')
                ]);
            
            return 1;

        }catch(\Exception $error)
        {
            return 0;
        }
    }

    public function studentreqsindex(Request $request)
    {
        $studentinfo = DB::table('studinfo')
            ->select('studinfo.*', 'gradelevel.acadprogid')
            ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
            ->where('studinfo.deleted','0')
            ->where('userid',auth()->user()->id)
            ->first();
        $acadprogid =  $studentinfo->acadprogid;
        $requirementslist = DB::table('preregistrationreqlist')
            ->select('id','description')
            ->where('isActive','1')
            ->where('levelid',$studentinfo->levelid)
            ->where('deleted','0')
            // ->where('acadprogid',$studentinfo->acadprogid)
            ->where(function($query) use($acadprogid){
                $query->orWhere('acadprogid','=',$acadprogid);
                $query->orWhere('acadprogid','=',null);
            })
            ->get();
        

        $studentlrn = $studentinfo->lrn;
        $studentsid = $studentinfo->sid;

        $queuecoderef = 2;

        $completed = 0;

        $submittedreqs = DB::table('preregistrationrequirements')
            ->where('preregistrationrequirements.deleted','0')
            // ->where('preregistrationrequirements.preregreqtype',$reqlist->id)
            ->where(function($query) use($studentlrn, $studentsid, $queuecoderef){
                if($studentlrn != null)
                {
                    $queuecoderef = 1; // lrn
                    $query->orWhere('qcode','=',$studentlrn);
                }
                if($studentsid != null)
                {
                    $queuecoderef = 2; // sid
                    $query->orWhere('qcode','=',$studentsid);
                }
            })
            ->get();

            
        
        if(count($requirementslist)>0)
        {
            foreach($requirementslist as $reqlist)
            {
                $stat = DB::table('studreqstat')
                    ->where('studid', $studentinfo->id)
                    ->where('reqtypeid', $reqlist->id)
                    ->where('deleted','0')
                    ->first();

                if($stat)
                {
                    $reqlist->status = $stat->reqstatus;
                }else{
                    $reqlist->status = 0;
                }
                $reqlist->picurl = null;
                $reqlist->submittedreqid = null;
                if(count(collect($submittedreqs)->where('preregreqtype', $reqlist->id)->values()) > 0){
                    $reqlist->picurl = str_replace('/', '/' . collect($submittedreqs)->where('preregreqtype', $reqlist->id)->first()->qcode . '/', collect($submittedreqs)->where('preregreqtype', $reqlist->id)->first()->picurl);
                    $reqlist->otherpicurl = collect($submittedreqs)->where('preregreqtype', $reqlist->id)->first()->picurl;
                    $reqlist->submittedreqid = collect($submittedreqs)->where('preregreqtype', $reqlist->id)->first()->id;
                }
                
            }
        }
        
        $studentinfo->queuecoderef = $queuecoderef;
        
        return view('studentPortal.pages.requirements.index')
            ->with('requirements',$requirementslist)
            ->with('queuecoderef',$queuecoderef)
            ->with('studentinfo',$studentinfo);
    }
    // public function studentrequirementsexport(Request $request)
    // {
    //     // return $request->all();
    //     $exporttype = $request->get('exporttype');

    //     $requirementslist = DB::table('preregistrationreqlist')
    //         ->select('id','description')
    //         ->where('isActive','1')
    //         ->where('deleted','0')
    //         ->get();

            
    //     $enrolledstuds = DB::table('enrolledstud')
    //         ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','studinfo.sid','studinfo.lrn','gradelevel.id as levelid','academicprogram.acadprogcode','gradelevel.levelname','sections.sectionname')
    //         ->join('studinfo','enrolledstud.studid','=','studinfo.id')
    //         ->join('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
    //         ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
    //         ->join('sections','enrolledstud.sectionid','=','sections.id')
    //         ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
    //         ->where('enrolledstud.studstatus','!=','0')
    //         ->where('studinfo.studstatus','!=','0')
    //         ->where('studinfo.studstatus','!=','6')
    //         ->where('enrolledstud.deleted','0')
    //         ->where('studinfo.deleted','0')
    //         ->orderBy('lastname','asc')
    //         ->get();

    //     $sh_enrolledstuds = DB::table('sh_enrolledstud')
    //         ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','studinfo.sid','studinfo.lrn','gradelevel.id as levelid','academicprogram.acadprogcode','gradelevel.levelname','sections.sectionname')
    //         ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
    //         ->join('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
    //         ->join('gradelevel','sh_enrolledstud.levelid','=','gradelevel.id')
    //         ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
    //         ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
    //         ->where('sh_enrolledstud.studstatus','!=','0')
    //         ->where('studinfo.studstatus','!=','0')
    //         ->where('studinfo.studstatus','!=','6')
    //         ->where('sh_enrolledstud.deleted','0')
    //         ->where('studinfo.deleted','0')
    //         ->orderBy('lastname','asc')
    //         ->get();

    //     $co_enrolledstuds = DB::table('college_enrolledstud')
    //         ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','studinfo.gender','studinfo.studstatus','studentstatus.description','studinfo.sid','studinfo.lrn','gradelevel.id as levelid','academicprogram.acadprogcode','gradelevel.levelname','college_sections.sectionDesc as sectionname')
    //         ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
    //         ->join('studentstatus','college_enrolledstud.studstatus','=','studentstatus.id')
    //         ->join('gradelevel','college_enrolledstud.yearLevel','=','gradelevel.id')
    //         ->join('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
    //         ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
    //         ->where('college_enrolledstud.studstatus','!=','0')
    //         ->where('studinfo.studstatus','!=','0')
    //         ->where('studinfo.studstatus','!=','6')
    //         ->where('college_enrolledstud.deleted','0')
    //         ->where('studinfo.deleted','0')
    //         ->orderBy('lastname','asc')
    //         ->get();
        
    //     $students = collect();
    //     $students = $students->merge($enrolledstuds);
    //     $students = $students->merge($sh_enrolledstuds);
    //     $students = $students->merge($co_enrolledstuds);
    //     $students = collect($students)->unique('id');
    //     $students = collect($students)->sortBy('lastname')->values();
    //     $filterwherein = collect($students)->pluck('lrn');
    //     $filterwherein = $filterwherein->merge(collect($students)->pluck('sid'));
    //     $filterwherein = collect($filterwherein)->filter(function ($value) { return !is_null($value); })->values();
    //     // return $filterwherein;
    //     $submittedreqs = DB::table('preregistrationrequirements')
    //     ->where('preregistrationrequirements.deleted','0')
    //     // ->where('preregistrationrequirements.preregreqtype',$reqlist->id)
    //     ->whereIn('qcode',$filterwherein)
    //     ->where('deleted','0')
    //     ->get();
    //     // return $submittedreqs;
    //     if(count($students)>0)
    //     {
    //         foreach($students as $student)
    //         {
    //             // return collect($student);
    //             $studentlrn = $student->lrn;
    //             $studentsid = $student->sid;

    //             $queuecoderef = 2;

    //             $completed = 0;


    //             // $eachsubmittedreqs = DB::table('preregistrationrequirements')
    //             //     ->where('preregistrationrequirements.deleted','0')
    //             //     // ->where('preregistrationrequirements.preregreqtype',$reqlist->id)
    //             //     ->where(function($query) use($studentlrn, $studentsid, $queuecoderef){
    //             //         if($studentlrn != null)
    //             //         {
    //             //             $queuecoderef = 1; // lrn
    //             //             $query->orWhere('qcode','=',$studentlrn);
    //             //         }
    //             //         if($studentsid != null)
    //             //         {
    //             //             $queuecoderef = 2; // sid
    //             //             $query->orWhere('qcode','=',$studentsid);
    //             //         }
    //             //     })
    //                 // ->get();
    //             $eachsubmitted = collect($submittedreqs)->where('qcode',$studentlrn)->values();
    //             $queuecoderef = 1; // lrn
    //             if(count($eachsubmitted) == 0)
    //             {
    //                 $queuecoderef = 2; // sid
    //                 $eachsubmitted = collect($submittedreqs)->where('qcode',$studentsid)->values();
    //             }


    //             // return $eachsubmitted;

    //             $reqsresult = array();
    //             if(count($requirementslist)>0)
    //             {
    //                 foreach($requirementslist as $reqlist)
    //                 {
    //                     $submittedstat = 0;

    //                     $eachsubmittedreqs = collect($eachsubmitted)->where('preregreqtype', $reqlist->id)->values();
    //                         // return $eachsubmittedreqs;
    //                     $submittedreqid = 0;

    //                     if(count($eachsubmittedreqs) > 0)
    //                     {
    //                         $completed+=1;
    //                         $submittedstat = 1;
    //                         $submittedreqid = $eachsubmittedreqs[0]->id;
    //                     }
    //                     array_push($reqsresult,(object)array(
    //                         'id'                 => $reqlist->id,
    //                         'description'        => $reqlist->description,
    //                         'submitted'          => $submittedstat,
    //                         'submittedreqid'              => $submittedreqid
    //                     ));
    //                 }
    //             }
    //             // return $requirementslist;
    //             $student->requirements = $reqsresult;
    //             $student->queuecoderef = $queuecoderef;
    //             if(count($requirementslist) == $completed)
    //             {
    //                 // return collect($student);
    //                 $student->status = 1; //'Complete';
    //             }else{
    //                 $student->status = 0; //'Incomplete';
    //             }
    //             // return $student->requirements ;
    //         }
    //     }
        
    //     if($exporttype == 'pdf')
    //     {
    //         // $students = collect($students)->take(5);
    //         // return $students;
    //         $pdf = PDF::loadview('registrar/pdf/pdf_studentreqs',compact('students','requirementslist'))->setPaper('legal','portrait');
    //         set_time_limit(30000);
    //         return $pdf->stream('Student Requirements Summary');

    //     }else{

    //     }
    // }
}
