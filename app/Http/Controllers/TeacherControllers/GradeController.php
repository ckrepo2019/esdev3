<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use Carbon\Carbon;
use App\GenerateGrade;
use App\Models\Teacher\FormExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class GradeController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
         $semesters = DB::table('semester')
            ->where('id','!=',3)
            ->get();

        $schoolyears = DB::table('sy')
                        ->orderBy('sydesc')
                        ->get();

        return view('teacher.grading.v1.index')
            ->with('semesters',$semesters)
            ->with('schoolyears',$schoolyears);
    }
    
    public function getsections(Request $request)
    {
        if($request->ajax())
        {
            $selectedschoolyear     = $request->get('selectedschoolyear');
            $selectedsemester       = $request->get('selectedsemester');
            $selectedlevelid        = $request->get('selectedlevelid');
            $selectedsectionid      = $request->get('selectedsectionid');
    
            $teacherid              = DB::table('teacher')->where('userid', auth()->user()->id)->first()->id;
            
            $assignsubjectsLower = DB::table('assignsubj')
                ->select('gradelevel.id as levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname','subjid')
                ->join('assignsubjdetail','assignsubj.id','=','assignsubjdetail.headerid')
                ->join('teacher','assignsubjdetail.teacherid','=','teacher.id')
                ->join('gradelevel','assignsubj.glevelid','=','gradelevel.id')
                ->join('sections','assignsubj.sectionid','=','sections.id')
                ->where('teacher.userid',auth()->user()->id)
                ->where('assignsubj.syid',$selectedschoolyear)
                ->where('assignsubj.deleted','0')
                ->where('sections.deleted','0')
                ->where('assignsubjdetail.deleted','0')
                ->distinct()
                ->get();
    
            $assignsubjectsHigher = DB::table('sh_classsched')
                ->select('gradelevel.id as levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname','subjid')
                ->join('sh_classscheddetail','sh_classsched.id','=','sh_classscheddetail.headerid')
                ->join('teacher','sh_classsched.teacherid','=','teacher.id')
                ->join('gradelevel','sh_classsched.glevelid','=','gradelevel.id')
                ->join('sections','sh_classsched.sectionid','=','sections.id')
                ->where('teacher.userid',auth()->user()->id)
                ->where('sh_classsched.syid',$selectedschoolyear)
                ->where('sh_classsched.semid',$selectedsemester)
                ->where('sh_classsched.deleted','0')
                ->where('sections.deleted','0')
                ->distinct()
                ->get();
    
    
            $block = DB::table('sh_blocksched')
                ->select('gradelevel.id as levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname','subjid')
                ->join('sh_blockscheddetail','sh_blocksched.id','=','sh_blockscheddetail.headerid')
                ->join('sh_subjects','sh_blocksched.subjid','=','sh_subjects.id')
                ->join('sh_sectionblockassignment',function($join) use( $selectedschoolyear,  $selectedsemester ){
                    $join->on( 'sh_blocksched.blockid','=','sh_sectionblockassignment.blockid');
                    $join->where('sh_sectionblockassignment.deleted',0);
                   
                })
                ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
                ->leftJoin('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('days','sh_blockscheddetail.day','=','days.id')
                ->join('rooms','sh_blockscheddetail.roomid','=','rooms.id')
                ->join('sy','sh_blocksched.syid','=','sy.id')
                ->where('sh_blocksched.teacherid',$teacherid)
                ->where('sh_blocksched.syid',$selectedschoolyear)
                ->where('sh_blocksched.semid',  $selectedsemester)
                ->where('sh_blocksched.deleted','0')
                ->where('sh_blockscheddetail.deleted','0')
                ->where('sh_sectionblockassignment.deleted','0')
                ->where('sections.deleted','0')
                ->where('sections.deleted','0')
                ->where('sy.isactive','1')
                ->distinct()
                ->get();

                
            $sections = collect();
            $sections = $sections->merge($assignsubjectsLower);
            $sections = $sections->merge($assignsubjectsHigher);
            $sections = $sections->merge($block);

            $temp_sections = array();

            foreach($sections as $item){

                $check = collect($temp_sections)->where('sectionid',$item->sectionid)->count();
                $item->with_pending = false;

                $grades = DB::table('grades')
                            ->where('levelid',$item->levelid)
                            ->where('sectionid',$item->sectionid)
                            ->where('syid',$selectedschoolyear)
                            ->where('subjid',$item->subjid)
                            ->where('deleted',0)
                            ->where('status',3)
                            ->get();

                if($check == 0){
                    if(count($grades) > 0){
                        $item->with_pending = true;
                    }
                    array_push($temp_sections, $item);
                }else{
                    foreach($temp_sections as $temp_section_item){
                        if($temp_section_item->sectionid == $item->sectionid){
                            if(count($grades) > 0){
                                $temp_section_item->with_pending = true;
                            }
                        }
                    }
                }
            }

            return $temp_sections;

        }
   
    }
    public function getsubjects(Request $request)
    {
        $selectedschoolyear     = $request->get('selectedschoolyear');
        $selectedsemester       = $request->get('selectedsemester');
        $selectedlevelid        = $request->get('selectedlevelid');
        $selectedsectionid      = $request->get('selectedsectionid');

        $teacherid              = DB::table('teacher')->where('userid', auth()->user()->id)->first()->id;

        $assignsubjdetail_1 = DB::table('assignsubj')
            ->select('sections.id as sectionid','sections.sectionname','assignsubj.glevelid','assignsubj.syid','subjects.id as subjectid','subjects.subjdesc as subjectname')
            ->join('assignsubjdetail','assignsubj.ID','=','assignsubjdetail.headerid')
            ->join('sections','assignsubj.sectionid','=','sections.id')
            ->join('subjects','assignsubjdetail.subjid','=','subjects.id')
            ->where('assignsubj.glevelid',$selectedlevelid)
            ->where('assignsubj.sectionid',$selectedsectionid)
            ->where('assignsubj.deleted',0)
            ->where('assignsubj.syid',$selectedschoolyear)
            ->where('assignsubjdetail.teacherid',$teacherid)
            ->where('assignsubjdetail.deleted','0')
            ->where('subjects.deleted','0')
            ->where('assignsubj.deleted','0')
            ->distinct('assignsubj.subjid')
            ->get();

        $assignsubjdetail_2 = DB::table('sh_classsched')
            ->select('sections.id as sectionid','sections.sectionname','sh_classsched.glevelid','sh_classsched.syid','sh_subjects.id as subjectid','sh_subjects.subjtitle as subjectname')
            ->join('sections','sh_classsched.sectionid','=','sections.id')
            ->join('sh_subjects','sh_classsched.subjid','=','sh_subjects.id')
            ->where('sh_classsched.glevelid',$selectedlevelid)
            ->where('sh_classsched.sectionid',$selectedsectionid)
            ->where('sh_classsched.semid',$selectedsemester)
            ->where('sh_classsched.deleted',0)
            ->where('sh_classsched.syid',$selectedschoolyear)
            ->where('sh_classsched.teacherid',$teacherid)
            ->where('sh_subjects.deleted','0')
            ->where('sh_classsched.deleted','0')
            ->distinct()
            ->get();

        $assignsubjdetailblock = DB::table('sh_blocksched')
            ->select('sections.id as sectionid','sections.sectionname','sections.levelid as glevelid','sh_blocksched.syid','sh_subjects.id as subjectid','sh_subjects.subjtitle as subjectname')
            ->join('sh_subjects','sh_blocksched.subjid','=','sh_subjects.id')
            ->join('sh_sectionblockassignment',function($join){
                $join->on( 'sh_blocksched.blockid','=','sh_sectionblockassignment.blockid');
                $join->where('sh_sectionblockassignment.deleted',0);
            })
            ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
            ->where('sh_blocksched.teacherid',$teacherid)
            ->where('sections.id',$selectedsectionid)
            ->where('sh_blocksched.syid',$selectedschoolyear)
            ->where('sh_blocksched.semid',$selectedsemester)
            ->where('sh_subjects.deleted','0')
            ->where('sh_blocksched.deleted','0')
            ->distinct()
            ->get();

        $subjects = collect();

        $subjects = $subjects->merge($assignsubjdetail_1);
        $subjects = $subjects->merge($assignsubjdetail_2);
        $subjects = $subjects->merge($assignsubjdetailblock);


        // 11132021 - grades
        foreach($subjects as $item){
            $grades = DB::table('grades')
                            ->where('levelid',$item->glevelid)
                            ->where('sectionid',$item->sectionid)
                            ->where('syid',$selectedschoolyear)
                            ->where('subjid',$item->subjectid)
                            ->where('deleted',0)
                            ->where('status',3)
                            ->get();

            if(count($grades) > 0){
                $item->with_pending = true;
            }else{
                $item->with_pending = false;
            }
        }
        // 11132021 - grades

        if(count($subjects)!=0){
            return view('teacher.grading.v1.showsubjects')
                ->with('gradeLevelid',$selectedlevelid)
                ->with('schoolyearid',$selectedschoolyear)
                ->with('selectedsemester',$selectedsemester)
                ->with('subjects',$subjects);
        }
        else{
            return view('teacher.grading.v1.showsubjects')
                ->with('gradeLevelid',$selectedlevelid)
                ->with('schoolyearid',$selectedschoolyear)
                ->with('selectedsemester',$selectedsemester)
                ->with('message','No Assigned Subjects!');
        }
    }
    
    public function show($id, Request $request)
    {
        // return $id;
        $schoolyear = DB::table('sy')
        ->select('id','sydesc')
        ->where('isactive','1')
        ->get();
        if($request->get('getStudents')=='getGradeLevel'){
            $acad = DB::table('gradelevel')
                // ->select('academicprogram.progname')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('gradelevel.id',$id)
                ->get();
            // return $acad;
            $get_grade_level_idArray = array();
            if(strtolower($acad[0]->acadprogcode) == "shs"){
                $get_grade_level_id = DB::table('teacher')
                    ->select('sections.id','sections.sectionname')
                    ->join('sh_classsched','teacher.id','=','sh_classsched.teacherid')
                    ->join('sh_subjects','sh_classsched.subjid','=','sh_subjects.id')
                    ->join('sections','sh_classsched.sectionid','=','sections.id')
                    ->where('sh_classsched.glevelid',$id)
                    ->where('sh_classsched.syid',$schoolyear[0]->id)
                    ->where('teacher.userid',auth()->user()->id)
                    ->where('sh_classsched.deleted','0')
                    ->get();
                    // return $get_grade_level_id;
                $get_grade_level_id_block = DB::table('teacher')
                            ->select('sections.id','sections.sectionname')
                            ->join('sh_blocksched','teacher.id','=','sh_blocksched.teacherid')
                            ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
                            ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
                            ->join('gradelevel','sections.levelid','=','gradelevel.id')
                            ->where('sh_blocksched.syid',$schoolyear[0]->id)
                            ->where('gradelevel.id',$id)
                            ->where('sh_blocksched.deleted','0')
                            ->where('sh_sectionblockassignment.deleted','0')
                            ->where('userid',auth()->user()->id)
                            ->get();
                            
                if(count($get_grade_level_id)==0){
                        if(count($get_grade_level_id_block) > 0){

                                foreach($get_grade_level_id_block as $byblock){

                                    array_push($get_grade_level_idArray,$byblock);
                                }
                        }
                }else{
                    foreach($get_grade_level_id as $bylevel){

                        array_push($get_grade_level_idArray,$bylevel);
                    }
                    if(count($get_grade_level_id_block) > 0){

                            foreach($get_grade_level_id_block as $byblock){

                                array_push($get_grade_level_idArray,$byblock);
                            }
                    }
                }
                return collect($get_grade_level_idArray)->unique();
            }
            else{
                $get_grade_level_id = DB::table('teacher')
                    ->select('sections.id','sections.sectionname')
                    ->join('assignsubjdetail','teacher.id','=','assignsubjdetail.teacherid')
                    ->join('assignsubj','assignsubjdetail.headerid','=','assignsubj.ID')
                    ->join('sections','assignsubj.sectionid','=','sections.id')
                    ->where('assignsubj.glevelid',$id)
                    ->where('assignsubj.syid',$schoolyear[0]->id)
                    ->where('teacher.userid',auth()->user()->id)
                    ->where('assignsubj.deleted','0')
                    ->get();
                    foreach($get_grade_level_id as $bylevel){

                        array_push($get_grade_level_idArray,$bylevel);
                    }
                    return collect($get_grade_level_idArray)->unique();
            }
       }
    }
    public function update(Request $request, $id)
    {
        if($request->get('identifier')=='th'){
            $syid = $request->get('syid');
            $headerTH = $request->get('headerTH');
            $levelID = $request->get('levelID');
            $sectionid = $request->get('sectionid');
            $quarterID = $request->get('quarterID');
            $subjectID = $request->get('subjectID');
            $headerClass = $request->get('headerClass');
            $headerValue = $request->get('headerValue');
            $subj_id = DB::table('grades')
                    ->where('syid',$syid)
                    ->where('levelid',$levelID)
                    ->where('sectionid',$sectionid)
                    ->where('quarter',$quarterID)
                    ->where('subjid',$subjectID)
                    ->get();
            if(strlen($headerClass) == 5){
                DB::update('update grades set '.$headerClass.' = ? where syid = ? and levelid = ? and sectionid = ? and subjid = ? and quarter = ?' ,[$headerValue,$syid,$levelID,$sectionid,$subjectID,$quarterID]);
                // return $headerClass;
            }
            else if(strlen($headerClass) == 16){
                // return 'tue';
    
                $moreheaderclass = substr($headerClass, 0,-11);
                // return $moreclass;
                DB::update('update grades set '.$moreheaderclass.' = ? where syid = ? and levelid = ? and sectionid = ? and subjid = ? and quarter = ?' ,[$headerValue,$syid,$levelID,$sectionid,$subjectID,$quarterID]);
                return $id;
                
            }
            $totalscores = array();
            $hps_ww_total_score = $subj_id[0]->wwhr1 + $subj_id[0]->wwhr2 + $subj_id[0]->wwhr3 + $subj_id[0]->wwhr4 + $subj_id[0]->wwhr5 + $subj_id[0]->wwhr6 + $subj_id[0]->wwhr7 + $subj_id[0]->wwhr8 + $subj_id[0]->wwhr9+ $subj_id[0]->wwhr0;
            $hps_pt_total_score = $subj_id[0]->pthr1 + $subj_id[0]->pthr2 + $subj_id[0]->pthr3 + $subj_id[0]->pthr4 + $subj_id[0]->pthr5 + $subj_id[0]->pthr6 + $subj_id[0]->pthr7 + $subj_id[0]->pthr8 + $subj_id[0]->pthr9+ $subj_id[0]->pthr0;
            // return $hps_ww_total_score;
            array_push($totalscores,$hps_ww_total_score);
            array_push($totalscores,$hps_pt_total_score);
            return response()->json($totalscores);
            
        }
        else if($request->get('identifier')=='td'){
            // return $request->all();
            $student_ID = $request->get('student_ID');
            $student_header_class = $request->get('student_header_class');
            $student_grade = $request->get('student_grade');
            $headerID = $request->get('headerID');
            $wwtotal = $request->get('wwtotal');
            $wwps = $request->get('wwps');
            $wwws = $request->get('wwws');
            $pttotal = $request->get('pttotal');
            $ptps = $request->get('ptps');
            $ptws = $request->get('ptws');
            $qaps = $request->get('qaps');
            $qaws = $request->get('qaws');
            $ig = $request->get('ig');
            $qg = $request->get('qg');
            // return $student_ID.' '.$student_header_class.' '.$student_grade;
            if(strlen($student_header_class) == 3){
                // return 'tuyiyuiue';
                // $three_class = substr($student_header_class, 0,-11);
                DB::update('update gradesdetail set '.$student_header_class.' = ?, wwtotal = ?, wwws= ?, wwps = ?, pttotal = ?, ptws = ?, ptps = ?, qaws = ?, qaps = ?, ig = ?, qg = ? where studid = ? and headerid = ?' ,[$student_grade,$wwtotal,$wwws,$wwps,$pttotal,$ptws,$ptps,$qaws,$qaps,$ig,$qg,$student_ID,$headerID]);
                // return $id;
                return 'update gradesdetail set '.$student_header_class.' = ? where studid = ? and headerid = ?';
    
            }
            else if(strlen($student_header_class) == 14){
                // return 'tue';
    
                $moreclass = substr($student_header_class, 0,-11);
                // return $moreclass;
                DB::update('update gradesdetail set '.$moreclass.' = ?, wwtotal = ?, wwws= ?, wwps = ?, pttotal = ?, ptws = ?, ptps = ?, qaws = ?, qaps = ?, ig = ?, qg = ? where studid = ? and headerid = ?' ,[$student_grade,$wwtotal,$wwws,$wwps,$pttotal,$ptws,$ptps,$qaws,$qaps,$ig,$qg,$student_ID,$headerID]);
                // return $id;
                return 'update gradesdetail set '.$student_header_class.' = ? where studid = ? and headerid = '.$headerID;
            }
        }
        else if($request->get('identifier')=='passinggrade'){
            $test=DB::update('update grades set passinggrade = ? where levelid = ? and sectionid = ? and subjid = ? and quarter = ?',[$request->get('passinggrade'),$request->get('gradeLevel'),$request->get('section'),$request->get('subjects'),$request->get('quarters')]);

            $updatedPassingGrade = DB::table('grades')
                                    ->select('passinggrade')
                                    ->where('levelid',$request->get('gradeLevel'))
                                    ->where('sectionid',$request->get('section'))
                                    ->where('subjid',$request->get('subjects'))
                                    ->where('quarter',$request->get('quarters'))
                                    ->where('deleted','0')
                                    ->get();
            return $updatedPassingGrade;
        }
        
        // return $student_ID;
    }
    public function updateGradeStatus(Request $request)
    {
        $mutable = Carbon::now();
        $created_date_time = $mutable->toDateTimeString();
        $sy_id = $request->get('sy');
        $grade_level_id = $request->get('gradeLevel');
        $section_id = $request->get('section');
        $quarter = $request->get('quarters');
        $subject_id = $request->get('subjects');
        DB::update('update grades set submitted = ?, date_submitted = ? where syid = ? and levelid = ? and sectionid = ? and subjid = ? and quarter = ? and deleted = ?' ,[1,Carbon::today(),$sy_id,$grade_level_id,$section_id,$subject_id,$quarter,0]);
        $get_grade_id = DB::table('grades')
                        ->select('id')
                        ->where('syid',$sy_id)
                        ->where('levelid',$grade_level_id)
                        ->where('sectionid',$section_id)
                        ->where('subjid',$subject_id)
                        ->where('quarter',$quarter)
                        ->where('deleted',0)
                        ->where('submitted',1)
                        // ->where('submitted','0')
                        ->get();
        DB::insert('insert into gradelogs (user_id,gradeid,action,date) values(?,?,?,?)',[auth()->user()->id,$get_grade_id[0]->id,1,$created_date_time]);
        // $getHeader = DB::table('grades')
        //             ->where()
        return back();
    }
    function toBlade($section,$subject,$quarter, Request $request)
    {
        // return $request->all();
        $getSchoolInfo = DB::table('schoolinfo')
        ->select(
            'schoolinfo.schoolid',
            'schoolinfo.schoolname',
            'schoolinfo.authorized',
            'schoolinfo.picurl',
            'refcitymun.citymunDesc as division',
            'schoolinfo.district',
            'schoolinfo.address',
            'refregion.regDesc as region'
        )
        ->join('refregion','schoolinfo.region','=','refregion.regCode')
        ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
        ->get();

        $getSchoolYear = DB::table('sy')
                    ->select('sydesc')
                    ->where('isactive',1)
                    ->get();
        // return $getSchoolYear;
        $getQuarter = $quarter;
        $getLevelAndSection = DB::table('sections')
            ->select('sections.sectionname','gradelevel.levelname','academicprogram.acadprogcode','gradelevel.acadprogid','sections.levelid')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('sections.id',$section)
            ->get();

        // return $getLevelAndSection;
        $getTeacherName = DB::table('users')
                    ->select('teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                    ->join('teacher','users.id','=','teacher.userid')
                    ->where('users.id',auth()->user()->id)
                    ->get();
        if(strtolower($getLevelAndSection[0]->acadprogcode) == 'shs')
        {
            $getSubject = DB::table('sh_subjects')
                        ->select('subjtitle as subjdesc')
                        ->where('id',$subject)
                        ->get();
                        
        }else{
            $getSubject = DB::table('subjects')
                        ->select('subjdesc')
                        ->where('id',$subject)
                        ->get();
        }
                    // return $getSubject;
                    // number_format((float)$student_total_ww_grade, 2, '.','')

        // $grades = GenerateGrade::sectiongradeinfo($section,$subject,$quarter);
              
        $levelInfo = $getLevelAndSection[0];

        $semid = DB::table('semester')->where('isactive','1')->first();
        $syid = DB::table('sy')->where('isactive','1')->first();

        if($levelInfo->acadprogid != 5){

            $hps = DB::table('grades')
                        ->join('sy',function($join){
                            $join->on('grades.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->where('sectionid',$section)
                        ->where('levelid',$levelInfo->levelid)
                        ->where('quarter',$quarter)
                        ->where('syid',$syid->id)
                        ->where('subjid',$subject)
                        ->select('grades.*')
                        ->get();

        }else{

            $hps = DB::table('grades')
                        ->join('sy',function($join){
                            $join->on('grades.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->where('sectionid',$section)
                        ->where('levelid',$levelInfo->levelid)
                        ->where('quarter',$quarter)
                        ->where('syid',$syid->id)
                        ->where('subjid',$subject)
                        ->where('semid',$semid->id)
                        ->select('grades.*')
                        ->get();

            
        }
        
        if($levelInfo->acadprogid != 5){

            $grades = DB::table('grades')
                        ->join('gradesdetail',function($join){
                            $join->on('grades.id','=','gradesdetail.headerid');
                        })
                        ->join('sy',function($join){
                            $join->on('grades.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('studinfo',function($join){
                            $join->on('gradesdetail.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->where('grades.sectionid',$section)
                        ->where('grades.levelid',$levelInfo->levelid)
                        ->where('quarter',$quarter)
                        ->where('subjid',$subject)
                        ->select('gradesdetail.*')
                        ->where('grades.deleted','0')
                        ->select('gradesdetail.*','studinfo.gender','studinfo.mol')
                        ->orderby('gender','desc')
                        ->orderby('lastname') 
                        ->get();

        }
        else{
            
            $grades = DB::table('grades')
                        ->join('gradesdetail',function($join){
                            $join->on('grades.id','=','gradesdetail.headerid');
                        })
                        ->join('sy',function($join){
                            $join->on('grades.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('studinfo',function($join){
                            $join->on('gradesdetail.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->where('grades.semid',$semid->id)
                        ->where('grades.sectionid',$section)
                        ->where('grades.levelid',$levelInfo->levelid)
                        ->where('quarter',$quarter)
                        ->where('subjid',$subject)
                        ->select('gradesdetail.*','studinfo.gender','studinfo.mol')
                        ->where('grades.deleted','0')
                        ->orderby('gender','desc')
                        ->orderby('lastname')
                        ->get();
        }

        // $mode_of_learning = '';

        // foreach($grades as $item){

        //     if($mode_of_learning == ''){

        //         $mode = DB::table('studinfo')
        //                         ->where('studinfo.id',$item->studid)
        //                         ->leftJoin('modeoflearning',function($join){
        //                             $join->on('studinfo.mol','=','modeoflearning.id');
        //                             $join->where('modeoflearning.deleted',0);
        //                         })
        //                         ->select('description')
        //                         ->first();

        //         if($mode->description != null){

                    

        //             if(strpos($mode->description,'MODULAR') !== false){

        //                 $is_modular = true;

        //             }

        //             $mode_of_learning = $mode->description;

        //         }

        //     }



        // }

        // $ubject_grading = DB::table('grading_system_subjassignment')
        //                         ->join('grading_system',function($join) use($levelInfo){
        //                             $join->on('grading_system_subjassignment.gsid','=','grading_system.id');
        //                             $join->where('grading_system_subjassignment.deleted',0);
        //                             $join->where('grading_system.acadprogid',$levelInfo->acadprogid);
        //                         })
        //                         ->join('grading_system_detail',function($join) use($levelInfo){
        //                             $join->on('grading_system.id','=','grading_system_detail.headerid');
        //                             $join->where('grading_system_detail.deleted',0);
                                    
        //                         })
        //                         ->where('grading_system.description','like','%'.$mode_of_learning.'%')
        //                         ->where('subjid',$subject)
        //                         ->where('grading_system_subjassignment.deleted',0)
        //                         ->get();

        // if(count($ubject_grading) > 0){

        //     $gradesetup = (object)[
        //                             'levelid'=>$levelInfo->levelid,
        //                             'writtenworks'=>collect( $ubject_grading )->where('sf9val',1)->first()->value,
        //                             'performancetask'=>collect( $ubject_grading )->where('sf9val',2)->first()->value,
        //                             'qassesment'=>collect( $ubject_grading )->where('sf9val',3)->first()->value,
        //                         ];

        // }
        // else{

            $gradesetup = DB::table('gradessetup')
                            ->join('sy',function($join){
                                $join->on('gradessetup.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->where('gradessetup.levelid',$levelInfo->levelid)
                            ->where('gradessetup.subjid',$subject)
                            ->first();
        // }

        if(count($hps) == 0){

            return back();

        }


        $hpstotalwws = 0;
        $hpstotalpt = 0;
        $hpstotalqa = $hps[0]->qahr1;



        for($x = 0 ; $x < 10; $x++){
                
                $hpstotalwwsstring = 'wwhr'.$x;  
                $hpstotalptstring = 'pthr'.$x;  

                $hpstotalwws += $hps[0]->$hpstotalwwsstring;
                $hpstotalpt += $hps[0]->$hpstotalptstring;
               
                
        }

     
      
        foreach($grades as $item){

            $studtotalwws = 0;
            $studtotalpt = 0;
            $studtotalqa = $item->qa1;

            for($x = 0 ; $x < 10; $x++){
                
                    $studtotalwwsstring = 'ww'.$x;  
                    $studtotalptstring = 'pt'.$x;  

                    $studtotalwws += $item->$studtotalwwsstring;
                    $studtotalpt += $item->$studtotalptstring;
                    
            }
            
            if($studtotalwws == 0)
            {
                $item->wwps=  0.00;
                
            }else{
                
                $item->wwps=  number_format( ( $studtotalwws / $hpstotalwws ) * 100 , 2 );
                //$item->wwps=  number_format(( $studtotalwws / $hpstotalwws ) * 100,2);
                
            }
            
            if($hpstotalpt == 0)
            {
                
                $item->ptws=  0.00;
                
            }else{
                
                
                 $item->ptps = number_format(  ( $studtotalpt / $hpstotalpt ) * 100 , 2 );
                
               // $item->ptws =  number_format($item->ptps * ( $gradesetup->performancetask / 100 ),2);
                
            }
            
            if($hpstotalqa == 0)
            {
                $item->qaws =  0;
                
            }else{
                
                 $item->qaps = number_format(  ( $studtotalqa / $hpstotalqa ) * 100 , 2 );
                //$item->qaws =  number_format($item->qaps * ( $gradesetup->qassesment / 100 ),2);
            }
            
      

            
           
           

            
            // return  $item->wwwps * ( $gradesetup->writtenworks / 100 );;

   
       
            $item->wwws =  number_format($item->wwps * ( $gradesetup->writtenworks / 100 ),2);
            $item->ptws =  number_format($item->ptps * ( $gradesetup->performancetask / 100 ),2);
            $item->qaws =  number_format($item->qaps * ( $gradesetup->qassesment / 100 ),2);

           
        }
        // $percentage = array();
        // // return $hps;
        // // return collect($gradesetup);
        // try{
        //     $versioncontrol = DB::table('zversion_control')
        //                 ->where('module','1')
        //                 ->where('isactive','1')
        //                 ->get();

        //     if(count($versioncontrol) == 0)
        //     {
        //         array_push($percentage, (object)array(
        //             'wwp'   => $gradesetup->writtenworks,
        //             'ptp'   => $gradesetup->performancetask,
        //             'qap'   => $gradesetup->qassesment
        //         ));
        //     }else{

        //     }
        // }catch(\Exception $error){

        //     if(count($versioncontrol) == 0)
        //     {
        //         array_push($percentage, (object)array(
        //             'wwp'   => $gradesetup->writtenworks,
        //             'ptp'   => $gradesetup->performancetask,
        //             'qap'   => $gradesetup->qassesment
        //         ));
        //     }
        // }

        // return collect($gradesetup);
        if($request->get('exporttype') == 'pdf')
        {
            $pdf = PDF::loadview('teacher/pdf/classrecordpreview',compact('getSchoolInfo','getSchoolYear','getQuarter','getLevelAndSection','getSubject','getTeacherName','grades','hps','gradesetup'))->setPaper('8.5x14','landscape');
    
            return $pdf->stream('Class Record');
        }else{
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path().'/public/excelformats/eclassrecord.xlsx');
            $sheet = $spreadsheet->getActiveSheet();
            $cellstyle = [
                'alignment' => [
                    'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
                ],
                'borders' => [
                    'allborders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    ],
                ]
            ];

				// $sheet->getStyle('A1')
                // ->getAlignment()->applyFromArray( [ 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );
            // return $getSchoolInfo;
            $sheet->setCellValue('G4',$getSchoolInfo[0]->region);
            $sheet->setCellValue('G5',$getSchoolInfo[0]->schoolname);
            $sheet->setCellValue('O4',$getSchoolInfo[0]->division);
            $sheet->setCellValue('X4',$getSchoolInfo[0]->district);
            $sheet->setCellValue('X5',$getSchoolInfo[0]->schoolid);
            $sheet->setCellValue('AG5',$getSchoolYear[0]->sydesc);
            $quarter = '';
            if ($getQuarter == 1)
            {
                $q = 'FIRST';
            }
            elseif ($getQuarter == 2)
            {
                $q = 'SECOND';
            }
            elseif ($getQuarter == 3){
                $q = 'THIRD';
            }
            elseif ($getQuarter == 4){
                $q = 'FOURTH';
            }
            $q.=' QUARTER';
            $sheet->setCellValue('A7',$q);
            $sheet->getStyle('A7')
                ->getAlignment()
                ->applyFromArray($cellstyle);

            $sheet->setCellValue('K7',$getLevelAndSection[0]->levelname.' - '.$getLevelAndSection[0]->sectionname);
            $sheet->getStyle('K7')
                ->getAlignment()
                ->applyFromArray($cellstyle);

            $sheet->setCellValue('S7',$getTeacherName[0]->lastname.', '.$getTeacherName[0]->firstname.' '.$getTeacherName[0]->middlename[0].'. '.$getTeacherName[0]->suffix);
            $sheet->getStyle('S7')
                ->getAlignment()
                ->applyFromArray($cellstyle);

            $sheet->setCellValue('AG7',$getSubject[0]->subjdesc);
            $sheet->getStyle('AG7')
                ->getAlignment()
                ->applyFromArray($cellstyle);

			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="E-Class Record.xlsx"');
			$writer->save("php://output");
        }
                    
    }
    public function unpostRequest($id, Request $request)
    {
        $mutable = Carbon::now();
        $created_date_time = $mutable->toDateTimeString();
        $sy_id = $request->get('syid');
        $grade_level_id = $request->get('gradelevelid');
        $section_id = $request->get('section');
        $quarter = $request->get('quarter');
        $subject_id = $request->get('subjectid');
        $teacher_id = DB::table('teacher')
            ->where('userid',auth()->user()->id)
            ->get();
        if($request->get('dataHolder')=="request"){

            $getGradesID = DB::table('grades')
                ->where('syid',$sy_id)
                ->where('levelid',$grade_level_id)
                ->where('sectionid',$section_id)
                ->where('subjid',$subject_id)
                ->where('quarter',$quarter)
                ->where('submitted','1')
                // ->where('status','2')
                ->where('deleted','0')
                ->get();

            $ifExists = DB::table('gradelogs')
                ->where('gradeid',$getGradesID[0]->id)
                ->where('user_id',auth()->user()->id)
                ->where('action','5')
                ->get();
            if(count($ifExists)==0){
                DB::table('gradelogs')
                    ->insert([
                        'gradeid' => $getGradesID[0]->id,
                        'user_id' => auth()->user()->id,
                        'action' => 5
                    ]);
            }

            $gradeAndSection = DB::table('sections')
                ->select('sections.sectionname','gradelevel.levelname','teacher.id as principalid','academicprogram.progname')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->join('teacher','academicprogram.principalid','=','teacher.id')
                ->where('sections.id',$section_id)
                ->where('gradelevel.id',$grade_level_id)
                ->where('teacher.deleted','0')
                ->get();
            if($gradeAndSection[0]->progname == "SENIOR HIGH SCHOOL"){
                $getSubjectname = DB::table('sh_subjects')
                    ->select('subjcode as subjdesc')
                    ->where('id',$subject_id)
                    ->where('deleted','0')
                    ->get();
            }
            else{
                $getSubjectname = DB::table('subjects')
                    ->where('id',$subject_id)
                    ->where('deleted','0')
                    ->get();
            }
                
            $numOfPendingRequests = DB::table('announcements')
                ->where('title','Grades change request')
                ->where('content','Requesting permission to make changes to the posted grades of '.$gradeAndSection[0]->levelname.' - '.$gradeAndSection[0]->sectionname.', '.$quarter.' grading of '.$getSubjectname[0]->subjdesc.'')
                ->where('createdby',auth()->user()->id)
                ->get();
            if(count($numOfPendingRequests)==0){
                $gradelogid = DB::table('announcements')
                    ->insertGetId([
                        'title' => 'Grades change request',
                        'content'=> 'Requesting permission to make changes to the posted grades of '.$gradeAndSection[0]->levelname.' - '.$gradeAndSection[0]->sectionname.', '.$quarter.' grading of '.$getSubjectname[0]->subjdesc.'',
                        'recievertype' => '6',
                        'announcementtype' => 2,
                        'createdby' => auth()->user()->id
                    ]);
                DB::table('notifications')
                    ->insert([
                        'headerid' => $gradelogid,
                        'recieverid' => $gradeAndSection[0]->principalid,
                        'type' => 5,
                        'status' => '0'
                    ]);
                $message = 'Request sent!';
            }
            else{
                $message = 'Request already exist!';
            }
            return response()->json(['message' => $message]);
        }
    }
    
    public function summer($action, Request $request)
    {
        $getsy = Db::table('sy')
            ->where('isactive','1')
            ->first();
        $myid = DB::table('teacher')
            ->select('id')
            ->where('userid',auth()->user()->id)
            ->first();
        if($action == 'dashboard'){
            // return $myid;
            $summersubjects = Db::table('gradesspclass')
                ->select('gradesspclass.subjid','academicprogram.acadprogcode','gradelevel.levelname','gradelevel.id as levelid')
                ->join('gradelevel','gradesspclass.levelid','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','academicprogram.id')
                ->where('gradesspclass.teacherid',$myid->id)
                ->where('gradesspclass.syid',$getsy->id)
                ->get();
            $subjectsArray = array();
            if(count($summersubjects)==0){
                return view('teacher.summer')
                    ->with('message','No students enrolled!');
            }else{
                foreach($summersubjects as $subjects){
    
                    if($subjects->acadprogcode == 'SHS'){
                        $subject = Db::table('sh_subjects')
                            ->select('id','subjcode','subjtitle')
                            ->where('id',$subjects->subjid)
                            ->where('deleted','0')
                            ->get();
                        if(count($subject)==0){
    
                        }else{
                            foreach($subject as $subj){
    
                                array_push($subjectsArray,(object)array(
                                    'acadprogcode' => $subjects->acadprogcode,
                                    'levelname' => $subjects->levelname,
                                    'levelid' => $subjects->levelid,
                                    'subjects' => $subj
                                ));
                            }
                        }
                    }
                    else{
    
                        $subject = Db::table('subjects')
                            ->select('id','subjcode','subjdesc as subjtitle')
                            ->where('id',$subjects->subjid)
                            ->where('deleted','0')
                            ->get();
                        if(count($subject)==0){
    
                        }else{
                            // return $subject;
                            foreach($subject as $subj){
    
                                array_push($subjectsArray,(object)array(
                                    'acadprogcode' => $subjects->acadprogcode,
                                    'levelname' => $subjects->levelname,
                                    'levelid' => $subjects->levelid,
                                    'subjects' => $subj
                                ));
                            }
                        }
                    }
                }
                // return $subjectsArray[0]->subjects;
                $subjects = collect($subjectsArray)->unique();
                // return count($subjects);
                return view('teacher.summer')
                    ->with('subjects',$subjects);
            }
        }
        elseif($action == 'showstudents'){
            // return $request->all();
            $levelname = Db::table('gradelevel')
                ->select('gradelevel.levelname','academicprogram.acadprogcode')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->where('gradelevel.id',$request->get('levelid'))
                // ->where('id',$request->get('subjid'))
                ->where('gradelevel.deleted','0')
                ->first();
            if($levelname->acadprogcode == 'SHS'){
                $subjectname = DB::table('sh_subjects')
                    ->select('subjtitle as subjectname', 'subjcode')
                    ->where('id',$request->get('subjid'))
                    ->where('deleted','0')
                    ->first();
            }else{
                $subjectname = DB::table('subjects')
                    ->select('subjdesc as subjectname','subjcode')
                    ->where('id',$request->get('subjid'))
                    ->where('deleted','0')
                    ->first();
            }
                // return $levelname;
            $gradessetup = Db::table('gradessetup')
                ->select('writtenworks','performancetask','qassesment')
                ->where('levelid',$request->get('levelid'))
                ->where('subjid',$request->get('subjid'))
                ->get();
            $header = Db::table('gradessp')
                ->where('levelid',$request->get('levelid'))
                ->where('subjid',$request->get('subjid'))
                ->where('syid',$getsy->id)
                ->get();
            // return $header;
            $getstudents = Db::table('gradesspclass')
                ->select('gradesspclass.id','studinfo.id as studid','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','gradesspclass.wwws','gradesspclass.ptws','gradesspclass.qaws','gradesspclass.wwps','gradesspclass.ptps','gradesspclass.qaps','gradesspclass.wwtotal','gradesspclass.pttotal','gradesspclass.qatotal','gradesspclass.qa1','gradesspclass.ig','gradesspclass.qg','gradesspclass.ww1','gradesspclass.ww2','gradesspclass.ww3','gradesspclass.ww4','gradesspclass.ww5','gradesspclass.ww6','gradesspclass.ww7','gradesspclass.ww8','gradesspclass.ww9','gradesspclass.ww0','gradesspclass.pt1','gradesspclass.pt2','gradesspclass.pt3','gradesspclass.pt4','gradesspclass.pt5','gradesspclass.pt6','gradesspclass.pt7','gradesspclass.pt8','gradesspclass.pt9','gradesspclass.pt0')
                ->join('studinfo','gradesspclass.studid','=','studinfo.id')
                ->where('gradesspclass.levelid',$request->get('levelid'))
                ->where('gradesspclass.subjid',$request->get('subjid'))
                ->where('gradesspclass.teacherid',$myid->id)
                ->where('gradesspclass.syid',$getsy->id)
                ->get();
            if(count($header)==0){
                
                DB::insert('insert into gradessp (syid,levelid,subjid,deleted) values(?,?,?,?)',[$getsy->id,$request->get('levelid'),$request->get('subjid'),'0']);
                $header = Db::table('gradessp')
                    ->where('levelid',$request->get('levelid'))
                    ->where('subjid',$request->get('subjid'))
                    ->where('syid',$getsy->id)
                    ->get();
                // return 
                $wwtotal = $header[0]->wwhr1 + $header[0]->wwhr2 + $header[0]->wwhr3 + $header[0]->wwhr4 + $header[0]->wwhr5 + $header[0]->wwhr6 + $header[0]->wwhr7 + $header[0]->wwhr8 + $header[0]->wwhr9 + $header[0]->wwhr0;
                $pttotal = $header[0]->pthr1 + $header[0]->pthr2 + $header[0]->pthr3 + $header[0]->pthr4 + $header[0]->pthr5 + $header[0]->pthr6 + $header[0]->pthr7 + $header[0]->pthr8 + $header[0]->pthr9 + $header[0]->pthr0;
            }
            else{

                $wwtotal = $header[0]->wwhr1 + $header[0]->wwhr2 + $header[0]->wwhr3 + $header[0]->wwhr4 + $header[0]->wwhr5 + $header[0]->wwhr6 + $header[0]->wwhr7 + $header[0]->wwhr8 + $header[0]->wwhr9 + $header[0]->wwhr0;
                $pttotal = $header[0]->pthr1 + $header[0]->pthr2 + $header[0]->pthr3 + $header[0]->pthr4 + $header[0]->pthr5 + $header[0]->pthr6 + $header[0]->pthr7 + $header[0]->pthr8 + $header[0]->pthr9 + $header[0]->pthr0;
            }
            $header[0]->wwtotal = $wwtotal;
            $header[0]->pttotal = $pttotal;
            // return $header;
            return view('teacher.summerstudents')
                ->with('subjid',$request->get('subjid'))
                ->with('levelid',$request->get('levelid'))
                ->with('levelname',$levelname->levelname)
                ->with('subjectname',$subjectname->subjectname)
                ->with('subjcode',$subjectname->subjcode)
                ->with('syid',$getsy->id)
                ->with('header',$header)
                ->with('gradessetup',$gradessetup)
                ->with('students',$getstudents);
        }
        elseif($action == 'updateheader'){
            $checkifexists = Db::table('gradessp')
                ->where('syid', $request->get('syid'))
                ->where('levelid', $request->get('levelid'))
                ->where('subjid', $request->get('subjid'))
                ->where('deleted','0')
                ->get();
            if(substr($request->get('headerClass'), 0, 2) == 'ww'){
                $total = 'wwtotal';
            }
            elseif(substr($request->get('headerClass'), 0, 2) == 'pt'){
                $total = 'pttotal';
            }
            elseif(substr($request->get('headerClass'), 0, 2) == 'qa'){
                $total = 'qatotal';
            }
            if(count($checkifexists)==0){
                if(substr($request->get('headerClass'), 0, 2) == 'ww'){
                    $totalcomp = $request->get('headerValue');
                }
                elseif(substr($request->get('headerClass'), 0, 2) == 'pt'){
                    $totalcomp = $request->get('headerValue');
                }
                elseif(substr($request->get('headerClass'), 0, 2) == 'qa'){
                    $totalcomp = $request->get('headerValue');
                }
                DB::insert('insert into gradessp (syid,levelid,subjid,deleted,'.$request->get('headerClass').') values(?,?,?,?)',[$request->get('syid'),$request->get('levelid'),$request->get('subjid'),'0',$request->get('headerValue')]);
                $getheader = Db::table('gradessp')
                    ->where('syid', $request->get('syid'))
                    ->where('levelid', $request->get('levelid'))
                    ->where('subjid', $request->get('subjid'))
                    ->where('deleted','0')
                    ->get();
                return array($getheader,$total,$totalcomp);
            }
            else{
                if(substr($request->get('headerClass'), 0, 2) == 'ww'){
                    $totalcomp = $checkifexists[0]->wwhr1 + $checkifexists[0]->wwhr2 + $checkifexists[0]->wwhr3 + $checkifexists[0]->wwhr4 + $checkifexists[0]->wwhr5 + $checkifexists[0]->wwhr6 + $checkifexists[0]->wwhr7 + $checkifexists[0]->wwhr8 + $checkifexists[0]->wwhr9 + $checkifexists[0]->wwhr0;
                }
                elseif(substr($request->get('headerClass'), 0, 2) == 'pt'){
                    $totalcomp = $checkifexists[0]->pthr1 + $checkifexists[0]->pthr2 + $checkifexists[0]->pthr3 + $checkifexists[0]->pthr4 + $checkifexists[0]->pthr5 + $checkifexists[0]->pthr6 + $checkifexists[0]->pthr7 + $checkifexists[0]->pthr8 + $checkifexists[0]->pthr9 + $checkifexists[0]->pthr0;
                }
                elseif(substr($request->get('headerClass'), 0, 2) == 'qa'){
                    $totalcomp = $checkifexists[0]->qahr1;
                }
                Db::update('update gradessp set '.$request->get('headerClass').' = ? where syid = ? and levelid = ? and subjid = ?',[$request->get('headerValue'),$request->get('syid'),$request->get('levelid'),$request->get('subjid')]);
                $checkifexists = Db::table('gradessp')
                    ->where('syid', $request->get('syid'))
                    ->where('levelid', $request->get('levelid'))
                    ->where('subjid', $request->get('subjid'))
                    ->where('deleted','0')
                    ->get();
                $wwtotal = $checkifexists[0]->wwhr1 + $checkifexists[0]->wwhr2 + $checkifexists[0]->wwhr3 + $checkifexists[0]->wwhr4 + $checkifexists[0]->wwhr5 + $checkifexists[0]->wwhr6 + $checkifexists[0]->wwhr7 + $checkifexists[0]->wwhr8 + $checkifexists[0]->wwhr9 + $checkifexists[0]->wwhr0;
                $pttotal = $checkifexists[0]->pthr1 + $checkifexists[0]->pthr2 + $checkifexists[0]->pthr3 + $checkifexists[0]->pthr4 + $checkifexists[0]->pthr5 + $checkifexists[0]->pthr6 + $checkifexists[0]->pthr7 + $checkifexists[0]->pthr8 + $checkifexists[0]->pthr9 + $checkifexists[0]->pthr0;
                $qatotal = $checkifexists[0]->qahr1;
                return array($checkifexists[0],array(
                    'wwtotal' => $wwtotal,
                    'pttotal' => $pttotal,
                    'qatotal' => $qatotal
                ));
            }

        }
        elseif($action == 'updatestudentgrade'){
            // return $request->all();
            DB::update('update gradesspclass set '.$request->get('header').' = ?, wwtotal = ?, wwps = ?, wwws = ?, pttotal = ?, ptps = ?, ptws = ?, qaps = ?, qaws = ?, ig = ?, qg = ? where studid = ? and syid = ? and levelid = ? and subjid = ?',[$request->get('grade'),$request->get('wwtotal'),$request->get('wwps'),$request->get('wwws'),$request->get('pttotal'),$request->get('ptps'),$request->get('ptws'),$request->get('qaps'),$request->get('qaws'),$request->get('ig'),$request->get('qg'),$request->get('studid'),$request->get('syid'),$request->get('levelid'),$request->get('subjid')]);
            
            $getgrades = Db::table('gradesspclass')
                ->where('syid', $request->get('syid'))
                ->where('levelid', $request->get('levelid'))
                ->where('subjid', $request->get('subjid'))
                ->where('studid', $request->get('studid'))
                ->where('deleted','0')
                ->first();
            
            $header = Db::table('gradessp')
                ->where('syid', $request->get('syid'))
                ->where('levelid', $request->get('levelid'))
                ->where('subjid', $request->get('subjid'))
                ->where('deleted','0')
                ->first();
            $headerwwtotal = $header->wwhr1 + $header->wwhr2 + $header->wwhr3 + $header->wwhr4 + $header->wwhr5 + $header->wwhr6 + $header->wwhr7 + $header->wwhr8 + $header->wwhr9 + $header->wwhr0;
            $headerpttotal = $header->pthr1 + $header->pthr2 + $header->pthr3 + $header->pthr4 + $header->pthr5 + $header->pthr6 + $header->pthr7 + $header->pthr8 + $header->pthr9 + $header->pthr0;
            $setup = Db::table('gradessetup')
                // ->where('syid', $request->get('syid'))
                ->where('levelid', $request->get('levelid'))
                ->where('subjid', $request->get('subjid'))
                ->where('deleted','0')
                ->get();

            $studentwwtotal = $getgrades->ww1 + $getgrades->ww2 + $getgrades->ww3 + $getgrades->ww4 + $getgrades->ww5 + $getgrades->ww6 + $getgrades->ww7 + $getgrades->ww8 + $getgrades->ww9 + $getgrades->ww0;
            if($studentwwtotal == 0){
                $wwps = 0;
            }else{
                $wwps = ($studentwwtotal/$headerwwtotal)*100;
            }
            if($wwps == 0){
                $wwws = 0;
            }else{
                $wwwsfloat = number_format((float)$wwps, 2, '.','');
                $wwwsmultiplier = '.'.$setup[0]->writtenworks;
                $wwws = $wwwsfloat*$wwwsmultiplier;
            }
            $studentpttotal = $getgrades->pt1 + $getgrades->pt2 + $getgrades->pt3 + $getgrades->pt4 + $getgrades->pt5 + $getgrades->pt6 + $getgrades->pt7 + $getgrades->pt8 + $getgrades->pt9 + $getgrades->pt0;
            if($studentpttotal == 0){
                $ptps = 0;
            }else{
                $ptps = ($studentpttotal/$headerpttotal)*100;
            }
            if($ptps == 0){
                $ptws = 0;
            }else{
                $ptwsfloat = number_format((float)$ptps, 2, '.','');
                $ptwsmultiplier = '.'.$setup[0]->performancetask;
                $ptws = $ptwsfloat*$ptwsmultiplier;
            }
            // return $ptps;
            
            $studentqatotal = $getgrades->qa1;
            if($studentqatotal == 0){
                $qaps = 0;
            }else{
                $qaps = ($studentqatotal/$qatotal)*100;
            }
            if($qaps == 0){
                $qaws = 0;
            }else{
                $pqawsfloat = number_format((float)$qaps, 2, '.','');
                $qawsmultiplier = '.'.$setup[0]->performancetask;
                $qaws = $qawsfloat*$qawsmultiplier;
            }
            $ig = number_format((float)$wwws+$ptws+$qaws, 2, '.','');
            $gts = DB::table('gradetransmutation')->get();

            $qG = 0;
            $gtsfound = 0;

            foreach ($gts as $gt){
                if($gt->gfrom >= $ig && $gtsfound == 0){
                    foreach ($gts as $gtx){
                        if($gtx->gto >= $ig && $gtsfound == 0){
                            $gtsfound = 1;
                            $qG = $gtx->gvalue;
                        }
                    }
                }
            }
            return array(
                'studid' => $request->get('studid'),
                'wwtotal' => $studentwwtotal,
                'wwps' => number_format((float)$wwps, 2, '.',''),
                'wwws' => number_format((float)$wwws, 2, '.',''),
                'pttotal' => $studentpttotal,
                'ptps' => number_format((float)$ptps, 2, '.',''),
                'ptws' => number_format((float)$ptws, 2, '.',''),
                'ig' => $ig,
                'qg' => $qG
            );
        }
        elseif($action == 'submitgrades'){
            // return $request->all();
            DB::update('update gradessp set submitted = ? where syid = ? and levelid = ? and subjid = ?',[1,$request->get('syid'),$request->get('levelid'),$request->get('subjid')]);
            $get_grade_id = DB::table('gradessp')
                ->select('id')
                ->where('syid',$request->get('syid'))
                ->where('levelid',$request->get('levelid'))
                ->where('subjid',$request->get('subjid'))
                ->where('deleted',0)
                ->where('submitted',1)
                ->get();
            $getPrincipalId = DB::table('gradelevel')
            ->select('teacher.userid')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->join('teacher','academicprogram.principalid','=','teacher.id')
            ->where('gradelevel.id',$request->get('levelid'))
            ->get();

            $gradelogid = DB::table('gradelogs')->insertGetId([
                'user_id'=> auth()->user()->id,
                'gradeid'=>$get_grade_id[0]->id,
                'action'=>'1',
            ]);
            
            DB::table('notifications')
                ->insert([
                    'headerid' => $gradelogid,
                    'type' => '3',
                    'status' => '0',
                    'recieverid' => $getPrincipalId[0]->userid
                ]);
            return redirect()->back()->with("submitted", 'Grades submitted successfully!');
            // if($updategrade){
            //     return redirect('/summergrades/showstudents')->with("submitted", 'Grades submitted successfully!');
            // }else{
            //     // return $updategrade;
            //     return redirect()->back()->with("notsubmitted", 'Something went wrong!');
            // }
        }
        elseif($action == 'requestpending'){
            // return $request->all();
            $gradeAndSection = DB::table('sections')
                ->select('teacher.id as principalid')
                ->join('gradelevel','sections.levelid','=','gradelevel.id')
                ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                ->join('teacher','academicprogram.principalid','=','teacher.id')
                ->where('gradelevel.id',$request->get('levelid'))
                ->where('teacher.deleted','0')
                ->get();
            $numOfPendingRequests = DB::table('announcements')
                ->where('title','Grades change request')
                ->where('content','Requesting permission to make changes to the posted grades of '.$request->get('levelname').' with the subject code of '.$request->get('subjcode').'')
                ->where('createdby',auth()->user()->id)
                ->get();
            if(count($numOfPendingRequests)==0){
                $gradelogid = DB::table('announcements')
                    ->insertGetId([
                        'title' => 'Grades change request',
                        'content'=> 'Requesting permission to make changes to the posted grades of '.$request->get('levelname').' with the subject code of '.$request->get('subjcode').'',
                        'recievertype' => '6',
                        'announcementtype' => 2,
                        'createdby' => auth()->user()->id
                    ]);
                DB::table('notifications')
                    ->insert([
                        'headerid' => $gradelogid,
                        'recieverid' => $gradeAndSection[0]->principalid,
                        'type' => 2,
                        'status' => '0'
                    ]);
                    return redirect()->back()->with("submitted", 'Request submitted successfully!');
            }
            else{
                return redirect()->back()->with("exists", 'Request already exists!');
            }
        }
    }
}
