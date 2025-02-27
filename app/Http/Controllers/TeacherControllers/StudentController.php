<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;
use Session;
use File;
use App\FilePath;
use Image;
use PDF;
use App\AssignSubject;
use App\Models\Principal\Billing;
use Illuminate\Support\Facades\Validator;
use App\Models\ExamPermit\StudentExamPermit;

class StudentController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function advisory(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        if(!$request->has('action'))
        {
            return view('teacher.studentsinfo.index');
            
        }else{            
            
            $sem = DB::table('semester')
                ->where('isactive','1')
                ->get();
            if($request->has('action'))
            {
                if($request->has('semid'))
                {
                    $sem = DB::table('semester')
                        ->where('id',$request->get('semid'))
                        ->get();
                }
                if($request->get('syid') == null)
                {
                    $syid = DB::table('sy')
                                    ->where('isactive','1')
                                    ->first();
                }else{
                    $syid = DB::table('sy')
                                    ->where('id',$request->get('syid'))
                                    ->first();
                }
            }else{
                $syid = DB::table('sy')
                                ->where('isactive','1')
                                ->first();
            }
            $collectsections = DB::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.userid',
                    'sections.levelid',
                    'gradelevel.levelname',
                    'gradelevel.sortid',
                    'sections.id as sectionid',
                    'sections.sectionname',
                    'academicprogram.progname',
                    'academicprogram.id as acadprogid',
                    'academicprogram.acadprogcode'
                    )
                ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
                ->join('sections','sectiondetail.sectionid','=','sections.id')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('teacher.userid',auth()->user()->id)
                ->where('sectiondetail.syid',$syid->id)
                ->where('sectiondetail.deleted','0')
                ->where('gradelevel.deleted','0')
                ->orderBy('sortid','asc')
                ->get();

                
            if(Session::get('currentPortal') == 1)
            {
                $collectsections = collect($collectsections)->where('userid', auth()->user()->id)->values();
            }
            
            if($request->has('acadprogid'))
            {
                if($request->get('acadprogid') == 0)
                {
                    $collectsections = collect($collectsections)->where('acadprogid', '!=','5')->values();
                }elseif($request->get('acadprogid') == 5)
                {
                    $collectsections = collect($collectsections)->where('acadprogid', 5)->values();
                }
            } 
            
            $sections = array();
            if(count($collectsections)>0){
                foreach($collectsections as $eachsection)
                {
                    $semester = 0;
                    if(strtolower($eachsection->acadprogcode) == 'shs')
                    {
                        if($sem[0]->id == 1)
                        {
                            $eachsection->semester = 1;
                            array_push($sections, $eachsection);
                        }
                        elseif($sem[0]->id == 2)
                        {                    
                            foreach(collect(DB::table('semester')->where('deleted','0')->get())->whereIn('id',[1,2])->values() as $eachsem)
                            {
                                $pushsection = (object)[
                                    'id'        => $eachsection->id,
                                    'userid'        => $eachsection->userid,
                                    'levelid'        => $eachsection->levelid,
                                    'sortid'        => $eachsection->sortid,
                                    'levelname'        => $eachsection->levelname,
                                    'sectionid'        => $eachsection->sectionid,
                                    'sectionname'        => $eachsection->sectionname,
                                    'progname'        => $eachsection->progname,
                                    'acadprogid'        => $eachsection->acadprogid,
                                    'acadprogcode'        => $eachsection->acadprogcode,
                                    'semester'        => $eachsem->id
                                ];
                                array_push($sections, $pushsection);
                            }
                        }
                        
                    }else{
                        $eachsection->semester = $semester;
                        array_push($sections, $eachsection);
                    }
                }
            }
            if($request->has('acadprogid') == 5)
            {
				$sections = collect($sections)->where('semester', $request->get('semid'))->values();
			}
            // return $sections;
            // $collectsections = DB::table('teacher')
            //     ->select(
            //         'teacher.id',
            //         'sections.levelid',
            //         'gradelevel.levelname',
            //         'sections.id as sectionid',
            //         'sections.sectionname',
            //         'academicprogram.progname',
            //         'academicprogram.acadprogcode'
            //         )
            //     ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
            //     ->join('sections','sectiondetail.sectionid','=','sections.id')
            //     ->join('gradelevel','sections.levelid','=','gradelevel.id')
            //     ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            //     ->where('teacher.userid',auth()->user()->id)
            //     ->where('sectiondetail.syid',$request->get('syid'))
            //     ->distinct()
            //     ->get();
            if(count($sections)>0){
                foreach($sections as $section)
                {
                    if(strtolower($section->acadprogcode) == 'shs')
                    {
                        $numberofstudents = Db::table('studinfo')
                            ->select('studinfo.*','sh_enrolledstud.studstatus as enrolledstudstatus')
                            ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                            ->where('sh_enrolledstud.sectionid', $section->sectionid)
                            ->where('sh_enrolledstud.levelid', $section->levelid)
                            ->where('sh_enrolledstud.studstatus','!=','0')
                            ->where('sh_enrolledstud.studstatus','!=','6')
                            ->where('studinfo.deleted','0')
                            ->where('sh_enrolledstud.deleted','0')
                            ->where('sh_enrolledstud.syid',$request->get('syid'))
                            ->where('sh_enrolledstud.semid',$request->get('semid'))
                            ->orderBy('lastname','asc')
                            ->get();
                            
                    }else{
                        $numberofstudents = Db::table('studinfo')
                            ->select('enrolledstud.studstatus as enrolledstudstatus')
                            ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                            ->where('enrolledstud.sectionid', $section->sectionid)
                            ->where('enrolledstud.studstatus', '!=','0')
                            ->where('enrolledstud.studstatus', '!=','6')
                            ->where('studinfo.deleted','0')
                            ->where('enrolledstud.deleted','0')
                            ->where('enrolledstud.syid',$request->get('syid'))
                            ->orderBy('lastname','asc')
                            ->get();
                    }
                    $section->numberofenrolled = count(collect($numberofstudents)->where('enrolledstudstatus','1'));
                    $section->numberoflateenrolled =  count(collect($numberofstudents)->where('enrolledstudstatus','2'));
                    $section->numberoftransferredin =  count(collect($numberofstudents)->where('enrolledstudstatus','4'));
                    $section->numberoftransferredout =  count(collect($numberofstudents)->where('enrolledstudstatus','5'));
                    $section->numberofdroppedout =  count(collect($numberofstudents)->where('enrolledstudstatus','3'));
    
                    // return count($numberofstudents);
                    $section->numberofstudents = count($numberofstudents);
                    // if(count($numberofstudents)>0)
                    // {
                    //     array_push($sections, (object)$section);
                    // }
                }
            }
            // return $sections;
            // else{
            return view('teacher.studentsinfo.sections')
                ->with('semid',$request->get('semid'))
                ->with('syid',$request->get('syid'))
                ->with('sections',$sections);
        }
                     
            
    }
    public function advisorygetstudents(Request $request)
    {
        $sem = DB::table('semester')
            ->where('id',$request->get('semid'))
            ->get();
        $syid = DB::table('sy')
        ->where('id',$request->get('syid'))
                        ->first();
        
        // return $syid->id;
        $section = DB::table('teacher')
            ->select(
                'teacher.id',
                'sections.levelid',
                'gradelevel.levelname',
                'sections.id as sectionid',
                'sections.sectionname',
                'academicprogram.progname',
                'academicprogram.acadprogcode',
                'academicprogram.id as acadprogid'
                )
            ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
            ->join('sections','sectiondetail.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('sections.id',$request->get('sectionid'))
            ->where('sectiondetail.syid',$syid->id)
            ->first();

        if($section)
        {
            // return $section;
            if(strtolower($section->acadprogcode) == 'shs')
            {
                // return 'adsasd';
                $students = Db::table('studinfo')
                    ->select('studinfo.*','studentstatus.description','modeoflearning.description as moldesc','msteams_creds.username as teamsemail','msteams_creds.password as teamspassword')
                    ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                    ->leftJoin('modeoflearning','studinfo.mol','=','modeoflearning.id')
                    ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                    ->leftJoin('msteams_creds','studinfo.id','=','msteams_creds.studid')
                    ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                    ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                    // ->where('sh_enrolledstud.studstatus','!=','0')
                    // ->where('sh_enrolledstud.studstatus','!=','6')
                    ->where('studinfo.studstatus','!=','6')
                    ->where('studinfo.deleted','0')
                    ->where('sh_enrolledstud.deleted','0')
                    ->where('sh_enrolledstud.semid',$sem[0]->id)
                    ->where('sh_enrolledstud.syid',$syid->id)
                    ->orderBy('lastname','asc')
                    ->get();
                    
            }else{
                $students = Db::table('studinfo')
                    // ->select('gender')
                    ->select('studinfo.*','studentstatus.description','modeoflearning.description as moldesc','msteams_creds.username as teamsemail','msteams_creds.password as teamspassword')
                    ->join('enrolledstud','studinfo.id','=','enrolledstud.studid')
                    ->leftJoin('modeoflearning','studinfo.mol','=','modeoflearning.id')
                    ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                    ->leftJoin('msteams_creds','studinfo.id','=','msteams_creds.studid')
                    ->where('enrolledstud.sectionid', $section->sectionid)
                    ->whereIn('enrolledstud.studstatus', [1,2,4])
                    // ->whereIn('enrolledstud.studstatus', [1,2,4])
                    // ->where('enrolledstud.studstatus','!=','0')
                    // ->where('enrolledstud.studstatus','!=','6')
                    ->where('studinfo.studstatus','!=','6')
                    ->where('studinfo.deleted','0')
                    ->where('enrolledstud.deleted','0')
                    ->where('enrolledstud.syid',$syid->id)
                    ->orderBy('lastname','asc')
                    ->get();
            }
        }else{
            $section = DB::table('sections')
                ->select(
                    'sections.levelid',
                    'gradelevel.levelname',
                    'sections.id as sectionid',
                    'sections.sectionname',
                    'academicprogram.progname',
                    'academicprogram.acadprogcode',
                    'academicprogram.id as acadprogid'
                    )
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('sections.id',$request->get('sectionid'))
                ->first();
    
            $students = array();
        }
        $students = collect($students)->unique('id')->all();
        if(count($students)>0)
        {
            foreach($students as $student)
            {
                $student->sortname = $student->lastname.', '.$student->firstname; 
            }
        }
        $students = collect($students)->sortBy('sortname');
        
        // return $request->all();
        // $students = DB::table('studinfo')
        //     ->where('sectionid', $request->get('sectionid'))
        //     ->whereIn('studstatus', [1,2,4])
        //     ->where('deleted','0')
        //     ->orderBy('lastname','asc')
        //     ->get();
        // return $students;
        $sectioninfo = $section;
        $mothertongues = DB::table('mothertongue')
            ->where('deleted','0')
            ->get();

        $ethnicgroups = DB::table('ethnic')
            ->where('deleted','0')
            ->get();
            
        $religions = DB::table('religion')
            ->where('deleted','0')
            ->get();
            
            
        if(!$request->has('action'))
        {
            return view('teacher.studentsinfo.studentsadvisory')
                ->with('students', $students)
                ->with('sectioninfo', $sectioninfo)
                ->with('mothertongues', $mothertongues)
                ->with('ethnicgroups', $ethnicgroups)
                ->with('religions', $religions)
                ->with('sectionid', $request->get('sectionid'))
                ->with('levelid', $request->get('levelid'))
                ->with('syid', $syid->id)
                ->with('semid', $sem[0]->id);
        }else{
            $teacherinfo = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->where('deleted','0')
                ->first();

            if($request->get('action') == 'printclasslist')
            {
                $pdf = PDF::loadview('teacher/studentsinfo/pdf_classlist',compact('students','sectioninfo','teacherinfo'))->setPaper('8.5x11');
                return $pdf->stream('Class List.pdf');
            }else{
                $pdf = PDF::loadview('teacher/studentsinfo/pdf_msteams',compact('students','sectioninfo'))->setPaper('8.5x11');
                return $pdf->stream('MSTeams Accounts.pdf');
            }
        }
    }
    public function advisorygetstudinfo(Request $request)
    {
        // if($request->ajax())
        // {
            $studentinfo = DB::table('studinfo')
                ->where('id', $request->get('studentid'))
                ->first();
            // return collect($studentinfo);
            $studentinfo->gender = strtolower($studentinfo->gender);

            $mothertongues = DB::table('mothertongue')
                ->where('deleted','0')
                ->get();

            $ethnicgroups = DB::table('ethnic')
                ->where('deleted','0')
                ->get();

            $religions = DB::table('religion')
                ->where('deleted','0')
                ->get();

            $parentsinfo = DB::table('studinfo_more')
                ->where('studid', $request->get('studentid'))
                ->where('deleted','0')
                ->first();

            $data = (object)[
                'info'          => $studentinfo,
                'mothertongues' => $mothertongues,
                'ethnicgroups'  => $ethnicgroups,
                'parentsinfo'     => $parentsinfo,
                'religions'     => $religions
            ];
            return view('teacher.studentsinfo.studentprofile_edit')
                ->with('data',$data);
        // }
    }
    public function advisoryphotosubmit(Request $request)
    {

        $message = [
     
            'image.required'=>'Student Picture is required',
        ];

        $validator = Validator::make($request->all(), [
            'image' => ['required']
        
        ], $message);

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            $data = array(
                (object)
              [
                'status'=>'0',
                'message'=>'Error',
                'errors'=>$validator->errors(),
                'inputs'=>$request->all()
            ]);

            return $data;
            
        }
        else{

            $studendinfo = DB::table('studinfo')->where('id', $request->get('studid'))->first();

            $urlFolder = str_replace('http://','',$request->root());

                if (! File::exists(public_path().'storage/STUDENT')) {

                    $path = public_path('storage/STUDENT');

                    if(!File::isDirectory($path)){

                        File::makeDirectory($path, 0777, true, true);
                    }
                }

                if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'storage/STUDENT')) {

                    $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT';

                    if(!File::isDirectory($cloudpath)){

                        File::makeDirectory($cloudpath, 0777, true, true);

                    }
                    
                }
            
                $data = $request->image;

                list($type, $data) = explode(';', $data);

                list(, $data)      = explode(',', $data);


                $data = base64_decode($data);

                $extension = 'png';

                $destinationPath = public_path('storage/STUDENT/'.$studendinfo->sid.'.'.$extension);
              
                $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT/'.$studendinfo->sid.'.'.$extension;
        
                file_put_contents($clouddestinationPath, $data);

                file_put_contents($destinationPath, $data);

                

                DB::table('studinfo')
                        ->where('id',$studendinfo->id)
                        ->update(['picurl'=>'storage/STUDENT/'.$studendinfo->sid.'.'.$extension ]);

                // $destinationPath = public_path('employeeprofile/'.$sy->sydesc.'/'. $request->username.'_'.$request->lastname.'.'.$extension);
                
                // file_put_contents($destinationPath, $data);

                // DB::table('teacher')
                //     ->where('id',$request->employeeid)
                //     ->update([
                //         'picurl' => 'employeeprofile/'.$sy->sydesc.'/'. $request->username.'_'.$request->lastname.'.'.$extension
                //     ]);

                // return response()->json(['success'=>'done']);
                // $file = $request->file('studpic');
            
                // $extension = $file->getClientOriginalExtension();
                
                // $img = Image::make($file->path());

                // $destinationPath = public_path('storage/STUDENT/'.$studendinfo->sid.'.'.$extension);

                // $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.'storage/STUDENT/'.$studendinfo->sid.'.'.$extension;

                // $img->resize(500, 500, function ($constraint) {
                //                         $constraint->aspectRatio();
                //                     })->resizeCanvas(500, 500,'center')->save($destinationPath);

                // $img->resize(500, 500, function ($constraint) {
                //                 $constraint->aspectRatio();
                //             })->resizeCanvas(500, 500,'center')->save($clouddestinationPath);

                

                // toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                // return back();

                $data = array(
                    (object)
                  [
                    'status'=>'1',
                ]);
    
                return $data;

            }
    }
    public function advisorygetstudinfosubmit(Request $request)
    {
        // return $request->all();
        if($request->ajax())
        {
            date_default_timezone_set('Asia/Manila');
            
            $checkifexists = DB::table('studinfo_more')
                ->where('studid', $request->get('studentid'))
                ->where('deleted','0')
                ->first();

            if($checkifexists)
            {
                DB::table('studinfo_more')
                    ->where('id',$checkifexists->id)
                    ->update([
                        'ffname'               =>  $request->get('ffname'),
                        'fmname'               =>  $request->get('fmname'),
                        'flname'               =>  $request->get('flname'),
                        'fsuffix'              =>  $request->get('fsuffix'),
                        'mfname'               =>  $request->get('mfname'),
                        'mmname'               =>  $request->get('mmname'),
                        'mlname'               =>  $request->get('mlname'),
                        'msuffix'              =>  $request->get('msuffix'),
                        'gfname'               =>  $request->get('gfname'),
                        'gmname'               =>  $request->get('gmname'),
                        'glname'               =>  $request->get('glname'),
                        'gsuffix'              =>  $request->get('gsuffix'),
                        'updatedby'            =>  auth()->user()->id,
                        'updateddatetime'      =>  date('Y-m-d H:i:s')
                    ]);
            }else{
                DB::table('studinfo_more')
                    ->insert([
                        'studid'               =>  $request->get('studentid'),
                        'ffname'               =>  $request->get('ffname'),
                        'fmname'               =>  $request->get('fmname'),
                        'flname'               =>  $request->get('flname'),
                        'fsuffix'              =>  $request->get('fsuffix'),
                        'mfname'               =>  $request->get('mfname'),
                        'mmname'               =>  $request->get('mmname'),
                        'mlname'               =>  $request->get('mlname'),
                        'msuffix'              =>  $request->get('msuffix'),
                        'gfname'               =>  $request->get('gfname'),
                        'gmname'               =>  $request->get('gmname'),
                        'glname'               =>  $request->get('glname'),
                        'gsuffix'              =>  $request->get('gsuffix'),
                        'deleted'              =>  0,
                        'createdby'            =>  auth()->user()->id,
                        'createddatetime'      =>  date('Y-m-d H:i:s')
                    ]);

            }
            DB::table('studinfo')
                ->where('id', $request->get('studentid'))
                ->update([
                    'lrn'                  => $request->get('lrn'),
                    'firstname'            => $request->get('firstname'),
                    'middlename'           => $request->get('middlename'),
                    'lastname'             => $request->get('lastname'),
                    'suffix'               => $request->get('suffix'),
                    'gender'               => $request->get('gender'),
                    'dob'                  => $request->get('birthdate'),
                    'mtid'                 => $request->get('mothertongue'),
                    'egid'                 => $request->get('ethnicgroup'),
                    'religionid'           => $request->get('religion'),
                    'street'               => $request->get('street'),
                    'barangay'             => $request->get('barangay'),
                    'city'                 => $request->get('city'),
                    'province'             => $request->get('province'),
                    'contactno'            => $request->get('contactno'),
                    'bloodtype'            => $request->get('bloodtype'),
                    'allergy'              => $request->get('allergy'),
                    'fathername'           => $request->get('flname').', '.$request->get('ffname').' '.$request->get('fmname').' '.$request->get('fsuffix'),
                    'fcontactno'           => $request->get('fcontactno'),
                    'mothername'           => $request->get('mlname').', '.$request->get('mfname').' '.$request->get('mmname').' '.$request->get('msuffix'),
                    'mcontactno'           => $request->get('mcontactno'),
                    'guardianname'         => $request->get('glname').', '.$request->get('gfname').' '.$request->get('gmname').' '.$request->get('gsuffix'),
                    'gcontactno'           => $request->get('gcontactno'),
                    'guardianrelation'     => $request->get('guardianrelationship'),
                    'updatedby'            => auth()->user()->id,
                    'updateddatetime'      => date('Y-m-d H:i:s')
                ]);
        }

    }
    public function bysubject(Request $request)
    {
        
        if(!$request->has('action'))
        {
            return view('teacher.studentsinfo.index_bysubject');
            
        }else{
            $sem = DB::table('semester')
                ->where('id',$request->get('semid'))
                ->get();
            $sy = DB::table('sy')
                ->where('id',$request->get('syid'))
                ->get();
            $headerIDArray  = array();
    
            $headerIdsLower = DB::table('assignsubj')
                ->select('assignsubj.id')
                ->join('assignsubjdetail','assignsubj.id','=','assignsubjdetail.headerid')
                ->join('teacher','assignsubjdetail.teacherid','=','teacher.id')
                ->where('teacher.userid',auth()->user()->id)
                ->where('assignsubj.deleted','0')
                ->where('assignsubjdetail.deleted','0')
                ->where('assignsubj.syid',$sy[0]->id)
                ->distinct()
                ->get();
            // return $sy;
            foreach ($headerIdsLower as $headerId){
                array_push($headerIDArray,(object) array(
                    'acad' => 'lower',
                    'lower' => $headerId->id
                ));
            }
            $headerIdsHigher = DB::table('sh_classsched')
                ->select('sh_classsched.id')
                ->join('sh_classscheddetail','sh_classsched.id','=','sh_classscheddetail.headerid')
                ->join('teacher','sh_classsched.teacherid','=','teacher.id')
                ->where('teacher.userid',auth()->user()->id)
                ->where('sh_classscheddetail.deleted','0')
                ->where('sh_classsched.syid',$sy[0]->id)
                ->where('sh_classsched.semid',$sem[0]->id)
                ->distinct()
                ->get();
                // return $headerIdsLower;
            if(count($headerIdsHigher)==0){
    
            }
            else{
                foreach ($headerIdsHigher as $headerId){
                    array_push($headerIDArray,(object) array(
                        'acad' => 'higher',
                        'higher' => $headerId->id
                    ));
                }
            }
            
            $teacherid = DB::table('teacher')->where('userid', auth()->user()->id)->first()->id;
            $block = DB::table('sh_blocksched')
                ->select('gradelevel.id as glevelid','gradelevel.levelname')
                ->join('sh_blockscheddetail','sh_blocksched.id','=','sh_blockscheddetail.headerid')
                ->join('sh_subjects','sh_blocksched.subjid','=','sh_subjects.id')
                ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
                ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
                ->leftJoin('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('days','sh_blockscheddetail.day','=','days.id')
                ->join('rooms','sh_blockscheddetail.roomid','=','rooms.id')
                ->join('sy','sh_blocksched.syid','=','sy.id')
                ->where('sh_blocksched.teacherid',$teacherid)
                ->where('sh_blocksched.deleted','0')
                ->where('sh_blockscheddetail.deleted','0')
                ->where('sh_sectionblockassignment.deleted','0')
                ->where('sections.deleted','0')
                ->where('sy.isactive','1')
                ->distinct()
                ->get();
                
            $headerID = collect($headerIDArray)->unique();
            
            $grade_level_id_array = array();
            foreach($headerID as $header){
                if($header->acad == 'lower'){
                    $get_grade_level_id_lower = DB::table('assignsubj')
                        ->select('glevelid')
                        ->where('id',$header->lower)
                        ->get();
                    if(count($get_grade_level_id_lower)==0){
        
                    }else{
                        array_push($grade_level_id_array,$get_grade_level_id_lower[0]);
                    }
                }
                if($header->acad == 'higher'){
                    $get_grade_level_id_higher = DB::table('sh_classsched')
                        ->select('glevelid')
                        ->where('id',$header->higher)
                        ->get();
                    if(count($get_grade_level_id_higher)==0){
        
                    }else{
                        array_push($grade_level_id_array,$get_grade_level_id_higher[0]);
                    }
                }
    
                
            }
            
            if(count($block)>0){
                foreach($block as $blockeach)
                {
                    array_push($grade_level_id_array,$blockeach);
                }
    
            }
            
            $grade_level_ids = collect($grade_level_id_array);
            $levels = $grade_level_ids->unique();
            $final_grade_level_data = array();
            foreach($levels as $grade_level_id){
                $get_grade_level = DB::table('gradelevel')
                    ->select('id','levelname','sortid')
                    ->where('id',$grade_level_id->glevelid)
                    ->orderBy('sortid','asc')
                    ->get();
                if(count($get_grade_level)==0){
                    array_push($final_grade_level_data,0);
                }
                else{
                    array_push($final_grade_level_data,$get_grade_level[0]);
                }
            }
            $final_grade_level_data = collect($final_grade_level_data)->sortBy('sortid')->values()->all();
            $final_grade_level_data = collect($final_grade_level_data)->unique();
            
            return $final_grade_level_data;
        }
        

    }
    public function bysubjectgetsections(Request $request)
    {
        // return $request->all();
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $teacherid = Db::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first()
            ->id;

        $sectionsarray = array();

        $acadprogcode = DB::table('gradelevel')
            ->select('academicprogram.acadprogcode','academicprogram.id as acadprogid')
            ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
            ->where('gradelevel.id',$request->get('gradelevelid'))
            ->first();

        if($request->has('action'))
        {
            
            $quarters = DB::table('quarter_setup')
            ->where('deleted','0')
            ->where('isactive','1')
            ->where('acadprogid',$acadprogcode->acadprogid)
            ->get();

            return collect($quarters);
        }else{
            $acadprogcode = $acadprogcode->acadprogcode;
        }
        if(strtolower($acadprogcode) == "shs"){
            // return 'sadas';
            $sectionsreg = DB::table('teacher')
                ->select('sections.id','sections.sectionname')
                ->join('sh_classsched','teacher.id','=','sh_classsched.teacherid')
                ->join('sections','sh_classsched.sectionid','=','sections.id')
                ->where('sh_classsched.glevelid',$request->get('gradelevelid'))
                ->where('sh_classsched.syid',$syid)
                ->where('sh_classsched.semid',$semid)
                ->where('teacher.userid',auth()->user()->id)
                ->where('sh_classsched.deleted','0')
                ->distinct()
                ->get();
                
            if(count($sectionsreg)> 0)
            {
                foreach($sectionsreg as $sectionreg)
                {
                    array_push($sectionsarray, (object)array(
                        'id'            => $sectionreg->id,
                        'sectionname'   => $sectionreg->sectionname
                    ));
                }
            }
            $sectionsblock = DB::table('teacher')
                ->select('sections.id','sections.sectionname','teacher.id as teacherid')
                ->join('sh_blocksched','teacher.id','=','sh_blocksched.teacherid')
                ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
                ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
                ->where('sh_blocksched.syid',$syid)
                ->where('sh_blocksched.semid',$semid)
                ->where('sections.levelid',$request->get('gradelevelid'))
                ->where('teacher.userid',auth()->user()->id)
                ->where('sh_blocksched.deleted','0')
                ->where('sh_sectionblockassignment.deleted','0')
                ->distinct()
                ->get();
                

            if(count($sectionsblock)> 0)
            {
                foreach($sectionsblock as $sectionblock)
                {
                    array_push($sectionsarray, (object)array(
                        'id'            => $sectionblock->id,
                        'sectionname'   => $sectionblock->sectionname
                    ));
                }
            }
        }
        else{
            $sectionsreg = DB::table('teacher')
                ->select('sections.id','sections.sectionname')
                ->join('assignsubjdetail','teacher.id','=','assignsubjdetail.teacherid')
                ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.ID')
                ->join('sections','assignsubj.sectionid','=','sections.id')
                ->where('assignsubj.syid',$syid)
                ->where('assignsubj.glevelid',$request->get('gradelevelid'))
                ->where('assignsubj.deleted','0')
                ->where('assignsubjdetail.deleted','0')
                ->where('teacher.userid',auth()->user()->id)
                ->distinct()
                ->get();
            if(count($sectionsreg)> 0)
            {
                foreach($sectionsreg as $sectionreg)
                {
                    array_push($sectionsarray, (object)array(
                        'id'            => $sectionreg->id,
                        'sectionname'   => $sectionreg->sectionname
                    ));
                }
            }
                // return $sections;
        }
        return collect($sectionsarray)->unique()->flatten();

    }
    public function bysubjectgetsubjects(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        $syid = $request->get('syid');
        $semid = $request->get('semid');
            
        $acadprogcode = DB::table('sections')
            ->select('acadprogcode','progname')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('sections.id',$request->get('sectionid'))
            ->first()->acadprogcode;
        
        if(strtolower($acadprogcode) == "shs"){
            $headerIDArray  = array();
            $getSubjID = DB::table('sh_classsched')
                ->select('sh_classsched.subjid')
                ->where('sh_classsched.sectionid',$request->get('sectionid'))
                ->join('teacher','sh_classsched.teacherid','=','teacher.id')
                ->where('teacher.userid',auth()->user()->id)
                ->where('sh_classsched.deleted','0')
                ->where('sh_classsched.syid',$syid)
                ->where('sh_classsched.semid',$semid)
                ->distinct()
                ->get();
            
            $getSubjIDBlock = DB::table('sh_blocksched')
                ->select('sh_blocksched.subjid')
                ->join('teacher','sh_blocksched.teacherid','=','teacher.id')
                ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
                ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
                ->where('teacher.userid',auth()->user()->id)
                ->where('sections.id',$request->get('sectionid'))
                ->where('sh_blocksched.syid',$syid)
                ->where('sh_blocksched.semid',$semid)
                ->where('sh_blocksched.deleted','0')
                ->distinct()
                ->get();
            if(count($getSubjIDBlock)!=0){
                foreach ($getSubjIDBlock as $subjects) {
                    array_push($headerIDArray,array((object)[
                        'subjid' => $subjects->subjid,
                        'acadprogcode' => $acadprogcode
                        ]));
                }
            }
            foreach ($getSubjID as $subjects) {
                array_push($headerIDArray,array((object)[
                    'subjid' => $subjects->subjid,
                    'acadprogcode' => $acadprogcode
                    ]));
            }
            $subjects = array();
            foreach ($headerIDArray as $header){
                    $getSubj = DB::table('sh_subjects')
                        ->select('id','subjtitle as subjdesc','subjcode')
                        ->where('id',$header[0]->subjid)
                        ->where('deleted','0')
                        ->where('isactive','1')
                        ->distinct()
                        ->get();
                    array_push($subjects,$getSubj[0]);
            }
            return $subjects;
            // return $headerIDArray;
        }
        else{
            $headerIDArray  = array();
            $headerIds = DB::table('assignsubj')
                ->select('assignsubj.ID', 'academicprogram.progname')
                ->join('gradelevel','assignsubj.glevelid','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
                ->where('assignsubj.sectionid',$request->get('sectionid'))
                ->where('assignsubj.syid',$syid)
                ->where('assignsubj.deleted','0')
                ->get();
            foreach($headerIds as $headerId){
                $getSubjID = DB::table('assignsubjdetail')
                    ->select('subjid')
                    ->where('headerid',$headerId->ID)
                    ->join('teacher','assignsubjdetail.teacherid','=','teacher.id')
                    ->where('teacher.userid',auth()->user()->id)
                    ->where('assignsubjdetail.deleted','0')
                    ->distinct()
                    ->get();
                foreach ($getSubjID as $subjects) {
                    array_push($headerIDArray,array((object)[
                        'subjid' => $subjects->subjid,
                        'acadprogcode' => $acadprogcode
                        ]));
                }
            }
            $subjects = array();
            foreach ($headerIDArray as $header){
                    $getSubj = DB::table('subjects')
                        ->select('id','subjdesc')
                        ->where('id',$header[0]->subjid)
                        ->where('deleted','0')
                        ->where('isactive','1')
                        ->distinct()
                        ->get();
                    array_push($subjects,$getSubj[0]);
            }
            return $subjects;
        }
    }
    public function bysubjectgetstudents(Request $request)
    {
        // if($request->ajax())
        // {
            //return $request->all();
            $syid = $request->get('syid');
            $setupid = $request->get('setupid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');

            $advisername = DB::table('sectiondetail')
                ->where('sectiondetail.syid', $syid)
                ->where('sectiondetail.sectionid', $sectionid)
                ->where('sectiondetail.deleted','0')
                ->join('teacher','sectiondetail.teacherid','=','teacher.id')
                ->select('teacher.id','teacher.title','teacher.lastname','teacher.firstname','teacher.middlename','teacher.suffix')
                ->first();

            $subjectteacher = DB::table('teacher')->where('userid', auth()->user()->id)->first();
            $teacherid = $subjectteacher->id;

            $acadprogid = DB::table('gradelevel')
                ->where('id',  $request->get('levelid'))->first()->acadprogid;
            
                
            
            if($acadprogid == 5)
            {
                $students = DB::table('sh_enrolledstud')
                    ->select('studinfo.*','studentstatus.description','modeoflearning.description as moldesc','sh_enrolledstud.strandid')
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->leftJoin('modeoflearning','studinfo.mol','=','modeoflearning.id')
                    ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                    ->where('sh_enrolledstud.sectionid', $request->get('sectionid'))
                    ->where('sh_enrolledstud.syid',$syid)
                    ->where('sh_enrolledstud.semid',$semid)
                    ->where('sh_enrolledstud.levelid',$request->get('levelid'))
                    ->groupBy('sh_enrolledstud.studid')
                    ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                    ->where('studinfo.deleted','0')
                    ->where('sh_enrolledstud.deleted','0')
                    // ->take(1)
                    ->get();
                if($request->get('strandid') > 0)
                {
                    $students = collect($students)->where('strandid', $request->get('strandid'))->values();
                }
            }else{
                $students = DB::table('enrolledstud')           
                            ->select('studinfo.*','studentstatus.description','modeoflearning.description as moldesc')
                            ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                            ->leftJoin('modeoflearning','studinfo.mol','=','modeoflearning.id')
                            ->leftJoin('studentstatus','enrolledstud.studstatus','=','studentstatus.id')
                            ->where('enrolledstud.sectionid', $request->get('sectionid'))
                            ->where('enrolledstud.syid',$syid)
                            ->where('enrolledstud.levelid',$request->get('levelid'))
                            ->where('studinfo.deleted','0')
                            ->whereIn('enrolledstud.studstatus', [1,2,4])
                            ->where('enrolledstud.deleted','0')
                            ->groupBy('enrolledstud.studid')
                            ->distinct()
                            ->get();
            }
                

            $students = collect($students)->unique('id')->values();
            if(count($students)>0)
            {

                foreach($students as $student)
                {
                    $student->added = 0;
                    $newrequest = new \Illuminate\Http\Request();
                    $newrequest->setMethod('POST');
                    $newrequest->request->add(['studid' => $student->id]);
                    $newrequest->request->add(['qid' => $setupid]);
                    $newrequest->request->add(['syid' => $request->get('syid')]);
                    $newrequest->request->add(['semid' => $request->get('semid')]);
                    $newrequest->request->add(['levelid' => $request->get('levelid')]);
                    $newrequest->request->add(['monthid' => $setupid]);

                    // $exampermit = \App\Http\Controllers\FinanceControllers\FinanceController::api_exampermit_flag($newrequest);
                    $exampermit = \App\Http\Controllers\FinanceControllers\ExamPermitController::ep_accounts($newrequest);
                    $student->exampermitstatus = json_decode($exampermit)->status;
                    if(json_decode($exampermit)->status == 'na')
                    {
                        $exampermit = 'Not Permitted';
                    }elseif(json_decode($exampermit)->status == 'a')
                    {
                        $exampermit = 'Permitted';
                    }else{
                        $exampermit = '';
                    }
                    $student->exampermitdesc = $exampermit;
                }

            }
            
            $customscheds = DB::table('sh_classsched')
                ->select('studsched.studid')
                ->join('studsched','sh_classsched.id','=','studsched.schedid')
                ->where('sh_classsched.sectionid', $request->get('sectionid'))
                ->where('sh_classsched.teacherid', $teacherid)
                ->where('sh_classsched.subjid',$request->get('subjectid'))
                ->where('sh_classsched.syid', $syid)
                ->where('sh_classsched.semid', $semid)
                ->where('sh_classsched.deleted','0')
                ->where('studsched.deleted','0')
                ->where('studsched.isapprove','1')
                ->distinct()
                ->get();

            
            $customschedstudents = array();
            if(count($customscheds)>0)
            {

                foreach($customscheds as $customsched)
                {
                    $customschedstud_info = DB::table('studinfo')
                        ->select('studinfo.*','studentstatus.description')
                        ->join('sh_enrolledstud','studinfo.id','=','sh_enrolledstud.studid')
                        ->leftJoin('studentstatus','sh_enrolledstud.studstatus','=','studentstatus.id')
                        // ->where('sh_enrolledstud.sectionid', $sectionid)
                        ->where('sh_enrolledstud.studstatus','!=','0')
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.semid',$semid)
                        ->orderBy('studinfo.lastname','asc')
                        ->distinct()
                        ->where('studinfo.id',$customsched->studid)
                        ->first();

                    if($customschedstud_info)
                    {
                        $customschedstud_info->added = 1;
                        array_push($customschedstudents, $customschedstud_info);
                    }
                }
            }


            $students = collect($students)->merge($customschedstudents);


            $subject_plots = DB::table('subject_plot')
                ->where('syid', $request->get('syid'))
                ->where('semid', $request->get('semid'))
                ->where('levelid', $request->get('levelid'))
                ->where('subjid', $request->get('subjectid'))
                ->where('deleted', 0)
                ->get();

            if($request->get('levelid') > 13)
            {
                $students = collect($students)->whereIn('strandid', collect($subject_plots)->pluck('strandid'))->values()->all();
            }
            $students = collect($students)->sortBy('lastname')->values();

            // $levelid = DB::table('sections')->where('id',$request->get('sectionid'))->where('deleted',0)->first()->levelid;
    
            // $month = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MM');
    
            // $exam_permit_version = DB::table('zversion_control')->where('module',3)->where('isactive',1)->get();
    
            // if(count($exam_permit_version) == 1){
    
            //     $exam_permit = StudentExamPermit::get_exam_status($levelid);
    
            // }
            // return $exam_permit;
            foreach($students as $student){
    
                // if(count($exam_permit_version) == 1){
                //     if($exam_permit[0]->status == 1){
    
                //         $examPermit = StudentExamPermit::getStudentPermit($student->id, $exam_permit[0]->data[0]->monthreq);
    
                //         $promisory = StudentExamPermit::check_promisory_permit($student->id, $exam_permit[0]->data[0]->id);
    
                //         $examPermit[0]->promisory = $promisory[0]->status;
                        
                //         $examPermit[0]->quarter = $exam_permit[0]->data[0]->description;
                        
                //         $student->permitstatus = $examPermit[0]->status;
                //         $student->permitmonth = $examPermit[0]->month;
                //         $student->permitdescription = $examPermit[0]->quarter;
    
                //     }
    
                // }else{
    
                //     $student->permitstatus = null;
                //     $student->permitmonth = null;
                //     $student->permitdescription = null;
    
                // }
    
                foreach($student as $key => $value){
    
                    
    
                    if($key == 'lrn'){
                        if($value==null){
                            $student->lrn = "";
                        }
                        elseif($value==""){
                            $student->lrn = "";
                        }
                    }
                    if($key == 'suffix'){
                        if($value==null){
                            $student->suffix = "";
                        }
                    }
                    if($key == 'dob'){
                        if($value==null){
                            $student->dob = "";
                        }
                    }
                    if($key == 'gender'){
                        if($value==null){
                            $student->gender = "";
                        }
                    }
                    if($key == 'contactno'){
                        if($value==null){
                            $student->contactno = "";
                        }
                    }
                    if($key == 'street'){
                        if($value==null){
                            $student->street = "";
                        }
                    }
                    if($key == 'barangay'){
                        if($value==null){
                            $student->barangay = "";
                        }
                    }
                    if($key == 'city'){
                        if($value==null){
                            $student->city = "";
                        }
                    }
                    if($key == 'province'){
                        if($value==null){
                            $student->province = "";
                        }
                    }
                    if($key == 'bloodtype'){
                        if($value==null){
                            $student->bloodtype = "";
                        }
                    }
                    if($key == 'allergy'){
                        if($value==null){
                            $student->allergy = "";
                        }
                    }
                    if($key == 'mothername'){
                        if($value == null){
                            $student->mothername = "";
                        }
                        elseif($value == ","){
                            $student->mothername = "";
                        }
                    }
                    if($key == 'mcontactno'){
                        if($value == null){
                            $student->mcontactno = "";
                        }
                        elseif($value == ","){
                            $student->mcontactno = "";
                        }
                    }
                    if($key == 'moccupation'){
                        if($value == null){
                            $student->moccupation = "";
                        }
                        elseif($value == ","){
                            $student->moccupation = "";
                        }
                    }
                    if($key == 'fathername'){
                        if($value == null){
                            $student->fathername = "";
                        }
                        elseif($value == ","){
                            $student->fathername = "";
                        }
                    }
                    if($key == 'fcontactno'){
                        if($value == null){
                            $student->fcontactno = "";
                        }
                        elseif($value == ","){
                            $student->fcontactno = "";
                        }
                    }
                    if($key == 'foccupation'){
                        if($value == null){
                            $student->foccupation = "";
                        }
                        elseif($value == ","){
                            $student->foccupation = "";
                        }
                    }
                    if($key == 'guardianname'){
                        if($value == null){
                            $student->guardianname = "";
                        }
                        elseif($value == ","){
                            $student->guardianname = "";
                        }
                    }
                    if($key == 'gcontactno'){
                        if($value == null){
                            $student->gcontactno = "";
                        }
                        elseif($value == ","){
                            $student->gcontactno = "";
                        }
                    }
                }
            }
            
            if($request->get('action') == 'getstudents')
            {
                return view('teacher.studentsinfo.studentsbysubject_list')
                    ->with('acadprogid',$acadprogid)
                    ->with('setupid',$request->get('setupid'))
                    ->with('students',collect($students)->sortBy('lastname')->values()->all());
            }else{
                if($levelid > 13)
                {
                    $classsched = DB::table('sh_classsched')
                        ->select('sh_classscheddetail.stime','sh_classscheddetail.etime','days.description as day','rooms.roomname','schedclassification.description as schedclassification')
                        ->join('sh_classscheddetail','sh_classsched.id','=','sh_classscheddetail.headerid')
                        ->leftJoin('schedclassification','sh_classscheddetail.classification','=','schedclassification.id')
                        ->join('days','sh_classscheddetail.day','=','days.id')
                        ->leftJoin('rooms','sh_classscheddetail.roomid','=','rooms.id')
                        ->where('sh_classsched.glevelid', $levelid)
                        ->where('sh_classsched.sectionid', $request->get('sectionid'))
                        ->where('sh_classsched.subjid', $request->get('subjectid'))
                        ->where('sh_classsched.syid', $syid)
                        ->where('sh_classsched.deleted', 0)
                        ->where('sh_classscheddetail.deleted', 0)
                        ->get();
                    $subjectname = DB::table('sh_subjects')
                        ->where('id', $request->get('subjectid'))->first()->subjtitle;
                }else{
                    $classsched = DB::table('classsched')
                        ->select('classscheddetail.stime','classscheddetail.etime','days.description as day','rooms.roomname','schedclassification.description as schedclassification')
                        ->join('classscheddetail','classsched.id','=','classscheddetail.headerid')
                        ->leftJoin('schedclassification','classscheddetail.classification','=','schedclassification.id')
                        ->join('days','classscheddetail.days','=','days.id')
                        ->leftJoin('rooms','classscheddetail.roomid','=','rooms.id')
                        ->where('classsched.glevelid', $levelid)
                        ->where('classsched.sectionid', $request->get('sectionid'))
                        ->where('classsched.subjid', $request->get('subjectid'))
                        ->where('classsched.syid', $syid)
                        ->where('classsched.deleted', 0)
                        ->where('classscheddetail.deleted', 0)
                        ->get();
                    $subjectname = DB::table('subjects')
                        ->where('id', $request->get('subjectid'))->first()->subjdesc;

                }
                $levelname = DB::table('gradelevel')
                    ->where('id', $request->get('levelid'))->first()->levelname;
                $sectionname = DB::table('sections')
                    ->where('id', $request->get('sectionid'))->first()->sectionname;
                    
                $classsched =collect($classsched)->groupBy('day');
                $students = collect($students)->sortBy('lastname')->values()->all();
                $pdf = PDF::loadview('teacher/studentsinfo/pdf_classlistbysubject',compact('acadprogid','students','classsched','sectionname','levelname','subjectname','advisername','subjectteacher'))->setPaper('8.5x11');
                return $pdf->stream('Non-Advisory_Class List - '.$levelname.'_'.$sectionname.'_'.strtoupper($subjectname).'.pdf');
            }
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $syid = DB::table('sy')
            ->where('isactive','1')
            ->get();
        if($request->get('getStudents')=='getSections'){
            $acadProg = DB::table('gradelevel')
                ->select('academicprogram.progname')
                ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
                ->where('gradelevel.id',$id)
                ->get();
            if($acadProg[0]->progname == "SENIOR HIGH SCHOOL"){
                $sections = DB::table('teacher')
                    ->select('sections.id','sections.sectionname')
                    ->join('sh_classsched','teacher.id','=','sh_classsched.teacherid')
                    ->join('sections','sh_classsched.sectionid','=','sections.id')
                    ->where('sh_classsched.glevelid',$id)
                    ->where('sh_classsched.syid',$syid[0]->id)
                    ->where('teacher.userid',auth()->user()->id)
                    ->distinct()
                    ->get();
                if(count($sections)==0){
                    $sections = DB::table('teacher')
                        ->select('sections.id','sections.sectionname')
                        ->join('sh_blocksched','teacher.id','=','sh_blocksched.teacherid')
                        ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
                        ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
                        ->where('sections.levelid',$id)
                        ->where('sh_blocksched.syid',$syid[0]->id)
                        ->where('teacher.userid',auth()->user()->id)
                        ->distinct()
                        ->get();
                }
            }
            else{
                $sections = DB::table('teacher')
                    ->select('sections.id','sections.sectionname')
                    ->join('assignsubjdetail','teacher.id','=','assignsubjdetail.teacherid')
                    ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.ID')
                    ->join('sections','assignsubj.sectionid','=','sections.id')
                    ->where('assignsubj.glevelid',$id)
                    ->where('assignsubj.deleted','0')
                    ->where('assignsubjdetail.deleted','0')
                    ->where('assignsubj.syid',$syid[0]->id)
                    ->where('teacher.userid',auth()->user()->id)
                    ->distinct()
                    ->get();
            }
            return $sections;
        }

        if($request->get('getStudents')=='getSubjects'){
            $acadprognameasfdasd = DB::table('sections')
                ->select('acadprogcode','progname')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('sections.id',$id)
                ->get();
            $sy = DB::table('sy')
                ->where('isactive', 1)
                ->get();
            // $headerIds = DB::table('assignsubj')
            //     ->select('assignsubj.ID', 'academicprogram.progname')
            //     ->join('gradelevel','assignsubj.glevelid','gradelevel.id')
            //     ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
            //     ->where('assignsubj.sectionid',$id)
            //     ->where('assignsubj.syid',$sy[0]->id)
            //     ->get();
                // return $headerIds;
            
            if($acadprognameasfdasd[0]->acadprogcode == "SHS"){
                $headerIDArray  = array();
                $getSubjID = DB::table('sh_classsched')
                    ->select('sh_classsched.subjid')
                    ->where('sh_classsched.sectionid',$id)
                    ->join('teacher','sh_classsched.teacherid','=','teacher.id')
                    ->where('sh_classsched.syid',$syid[0]->id)
                    ->where('teacher.userid',auth()->user()->id)
                    ->distinct()
                    ->get();
                
                $getSubjIDBlock = DB::table('sh_blocksched')
                    ->select('sh_blocksched.subjid')
                    ->join('teacher','sh_blocksched.teacherid','=','teacher.id')
                    ->join('sy','sh_blocksched.syid','=','sy.id')
                    ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
                    ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
                    ->where('teacher.userid',auth()->user()->id)
                    ->where('sh_blocksched.syid',$syid[0]->id)
                    ->where('sections.id',$id)
                    ->where('sy.isactive','1')
                    ->distinct()
                    ->get();
                if(count($getSubjIDBlock)!=0){
                    foreach ($getSubjIDBlock as $subjects) {
                        array_push($headerIDArray,array((object)[
                            'subjid' => $subjects->subjid,
                            'progname' => $acadprognameasfdasd[0]->progname
                            ]));
                    }
                }
                foreach ($getSubjID as $subjects) {
                    array_push($headerIDArray,array((object)[
                        'subjid' => $subjects->subjid,
                        'progname' => $acadprognameasfdasd[0]->progname
                        ]));
                }
                $subjects = array();
                foreach ($headerIDArray as $header){
                        $getSubj = DB::table('sh_subjects')
                            ->select('id','subjcode as subjdesc')
                            ->where('id',$header[0]->subjid)
                            ->distinct()
                            ->get();
                        array_push($subjects,$getSubj[0]);
                }
                return $subjects;
                // return $headerIDArray;
            }
            else{
                $headerIDArray  = array();
                $headerIds = DB::table('assignsubj')
                    ->select('assignsubj.ID', 'academicprogram.progname')
                    ->join('gradelevel','assignsubj.glevelid','gradelevel.id')
                    ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
                    ->where('assignsubj.sectionid',$id)
                    ->where('assignsubj.syid',$sy[0]->id)
                    ->get();
                foreach($headerIds as $headerId){
                    $getSubjID = DB::table('assignsubjdetail')
                        ->select('subjid')
                        ->where('headerid',$headerId->ID)
                        ->join('teacher','assignsubjdetail.teacherid','=','teacher.id')
                        ->where('teacher.userid',auth()->user()->id)
                        ->distinct()
                        ->get();
                    foreach ($getSubjID as $subjects) {
                        array_push($headerIDArray,array((object)[
                            'subjid' => $subjects->subjid,
                            'progname' => $acadprognameasfdasd[0]->progname
                            ]));
                    }
                }
                $subjects = array();
                foreach ($headerIDArray as $header){
                        $getSubj = DB::table('subjects')
                            ->select('id','subjdesc')
                            ->where('id',$header[0]->subjid)
                            ->distinct()
                            ->get();
                        array_push($subjects,$getSubj[0]);
                }
                return $subjects;
            }
        }
        

        if($request->get('getStudents')=='getStudents'){
            
            $student = DB::table('enrolledstud')
                ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                ->where('enrolledstud.sectionid', $request->get('sectionId'))
                ->where('enrolledstud.syid',$syid[0]->id)
                ->groupBy('enrolledstud.studid')
                ->whereIn('enrolledstud.studstatus', [1,2,4])
                ->where('studinfo.deleted','0')
                ->where('enrolledstud.deleted','0')
                ->distinct()
                ->get();
            if(count($student)==0){
                $student = DB::table('sh_enrolledstud')
                    ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                    ->where('sh_enrolledstud.sectionid', $request->get('sectionId'))
                    ->where('sh_enrolledstud.syid',$syid[0]->id)
                    ->groupBy('sh_enrolledstud.studid')
                    ->whereIn('sh_enrolledstud.studstatus', [1,2,4])
                    ->where('studinfo.deleted','0')
                    ->where('sh_enrolledstud.deleted','0')
                    // ->take(1)
                    ->get();
            }
            // return $student;
            foreach($student as $stud){
                foreach($stud as $key => $value){
                    if($key == 'lrn'){
                        if($value==null){
                            $student->lrn = "";
                        }
                        elseif($value==""){
                            $student->lrn = "";
                        }
                    }
                    if($key == 'suffix'){
                        if($value==null){
                            $student->suffix = "";
                        }
                    }
                    if($key == 'dob'){
                        if($value==null){
                            $student->dob = "";
                        }
                    }
                    if($key == 'gender'){
                        if($value==null){
                            $student->gender = "";
                        }
                    }
                    if($key == 'contactno'){
                        if($value==null){
                            $student->contactno = "";
                        }
                    }
                    if($key == 'street'){
                        if($value==null){
                            $student->street = "";
                        }
                    }
                    if($key == 'barangay'){
                        if($value==null){
                            $student->barangay = "";
                        }
                    }
                    if($key == 'city'){
                        if($value==null){
                            $student->city = "";
                        }
                    }
                    if($key == 'province'){
                        if($value==null){
                            $student->province = "";
                        }
                    }
                    if($key == 'bloodtype'){
                        if($value==null){
                            $student->bloodtype = "";
                        }
                    }
                    if($key == 'allergy'){
                        if($value==null){
                            $student->allergy = "";
                        }
                    }
                    // if($key == 'picurl'){
                    //     if($value==null){
                    //         $student->picurl = '{{asset("assets/images/avatars/female.png")}}';
                    //     }
                    // }
                    if($key == 'mothername'){
                        if($value == null){
                            $student->mothername = "";
                        }
                        elseif($value == ","){
                            $student->mothername = "";
                        }
                    }
                    if($key == 'mcontactno'){
                        if($value == null){
                            $student->mcontactno = "";
                        }
                        elseif($value == ","){
                            $student->mcontactno = "";
                        }
                    }
                    if($key == 'moccupation'){
                        if($value == null){
                            $student->moccupation = "";
                        }
                        elseif($value == ","){
                            $student->moccupation = "";
                        }
                    }
                    if($key == 'fathername'){
                        if($value == null){
                            $student->fathername = "";
                        }
                        elseif($value == ","){
                            $student->fathername = "";
                        }
                    }
                    if($key == 'fcontactno'){
                        if($value == null){
                            $student->fcontactno = "";
                        }
                        elseif($value == ","){
                            $student->fcontactno = "";
                        }
                    }
                    if($key == 'foccupation'){
                        if($value == null){
                            $student->foccupation = "";
                        }
                        elseif($value == ","){
                            $student->foccupation = "";
                        }
                    }
                    if($key == 'guardianname'){
                        if($value == null){
                            $student->guardianname = "";
                        }
                        elseif($value == ","){
                            $student->guardianname = "";
                        }
                    }
                    if($key == 'gcontactno'){
                        if($value == null){
                            $student->gcontactno = "";
                        }
                        elseif($value == ","){
                            $student->gcontactno = "";
                        }
                    }
                }
            }
            return $student;
        }
        
        
        
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
