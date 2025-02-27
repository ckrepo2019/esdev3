<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class CollegeFormsController extends Controller
{
    public function index()
    {
        return view('registrar.forms.college.index');
    }
    public function permanentrecordindex()
    {
        $schoolyears = DB::table('sy')
            // ->where('deleted','0')
            ->get();

        $semesters = DB::table('semester')
            // ->where('deleted','0')
            ->get();

        return view('registrar.forms.college.permanentrecord')
            ->with('schoolyears',$schoolyears)
            ->with('semesters',$semesters);
    }
    public function permanentrecordfilter(Request $request)
    {
        // return $request->all();
        $students = DB::table('college_enrolledstud')
            ->select('studinfo.id','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.suffix','college_enrolledstud.courseid','college_courses.courseDesc as coursename','college_courses.courseabrv as coursecode','college_colleges.collegeabrv as collegecode','college_year.levelid','college_year.yearDesc as levelname','college_enrolledstud.sectionID as sectionid','college_sections.sectionDesc as sectionname')
            ->join('studinfo','college_enrolledstud.studid','=','studinfo.id')
            ->join('college_sections','college_enrolledstud.sectionID','=','college_sections.id')
            ->join('college_courses','college_enrolledstud.courseid','=','college_courses.id')
            ->join('college_colleges','college_courses.collegeid','=','college_colleges.id')
            ->join('college_year','college_enrolledstud.yearLevel','=','college_year.levelid')
            ->where('college_enrolledstud.syid', $request->get('selectedschoolyear'))
            ->where('college_enrolledstud.semid', $request->get('selectedsemester'))
            ->where('college_sections.deleted','0')
            ->where('college_courses.deleted','0')
            ->where('college_colleges.deleted','0')
            ->where('college_enrolledstud.studstatus','!=','0')
            ->orderBy('lastname','asc')
            ->get();
        
        return view('registrar.forms.college.permanentrecord_table')
            ->with('students',$students);

    }
    public function permanentrecordgetrecord(Request $request)
    {
        $studentid                      = $request->get('id');
        $courseid                       = $request->get('courseid');
        $levelid                        = $request->get('levelid');
        $sectionid                      = $request->get('sectionid');
        $selectedschoolyear             = $request->get('selectedschoolyear');
        $selectedsemester               = $request->get('selectedsemester');
        
        $college_studentprospectus      = DB::table('college_studentprospectus')
                                            ->select('college_prospectus.id as subjectid','college_prospectus.subjCode as subjcode','college_prospectus.subjDesc as subjectname')
                                            ->join('college_prospectus','college_studentprospectus.prospectusID','=','college_prospectus.id')
                                            ->where('college_studentprospectus.studid', $studentid)
                                            ->where('college_studentprospectus.courseID', $courseid)
                                            ->where('college_prospectus.courseID', $courseid)
                                            ->where('college_studentprospectus.deleted','0')
                                            ->where('college_prospectus.semesterID',$selectedsemester)
                                            ->where('college_prospectus.yearID',$selectedschoolyear)
                                            ->where('college_prospectus.deleted','0')
                                            ->get();

        // return $college_studentprospectus;
        // if(count($college_studentprospectus)>0)
        // {
        //     foreach($college_studentprospectus as $prospectus)
        //     {
                
        //     }
        // }

    }
    public function subjgrouping(Request $request)
    {
        if(!$request->has('action'))
        {
            return view('registrar.setup.subjgroup.index');

        }else{
            if($request->get('action') == 'getgroups')
            {
                $subjgroups = DB::table('setup_subjgroups')
                    ->where('deleted','0')
                    ->orderBy('sortnum','asc')
                    ->get();
                    
                return view('registrar.setup.subjgroup.getgroups')
                    ->with('subjgroups',$subjgroups);

            }
            if($request->get('action') == 'addgroup')
            {                
                $sortnum = $request->get('subjnum');
                $subjgroup = $request->get('subjgroup');
                $subjunit = $request->get('subjunit');
                $checkifexists = DB::table('setup_subjgroups')
                    ->where('sortnum','like','%'.$sortnum.'%')
                    ->where('description','like','%'.$subjgroup.'%')
                    ->where('unitsreq','like','%'.$subjunit.'%')
                    ->where('deleted','0')
                    ->first();
                    
                if($checkifexists)
                {
                    return 0;
                }else{
                    try{
                        DB::table('setup_subjgroups')
                            ->insert([
                                'sortnum'           => $request->get('subjnum'),
                                'description'       => $request->get('subjgroup'),
                                'unitsreq'          => $request->get('subjunit'),
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                        return 1;
                    }catch(\Exception $error)
                    {
                        return 'error';
                    }
                }
            }
            if($request->get('action') == 'deletegroup')
            {  
                try{
                    DB::table('setup_subjgroups')
                        ->where('id', $request->get('id'))
                        ->update([
                            'deleted'           => 1,
                            'deletedby'         => auth()->user()->id,
                            'deleteddatetime'   => date('Y-m-d H:i:s')
                        ]);
                    return 1;
                }catch(\Exception $error)
                {
                    return 'error';
                }
            }
        }
    }
}
