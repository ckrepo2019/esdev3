<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class tchrTeacherSchedulingController extends Controller
{
    // index view with data return
    public function tchrViewSchedules(){
        // $userid = auth()->user()->id;
        // $teacherid = DB::table('teacher')
        //     ->select('id')
        //     ->where('userid', $userid)
        //     ->first();

            
        // return $room;
        $rooms  = DB::table('rooms')
            ->select('id', 'roomname as text')
            ->where('deleted', 0)
            ->get();
            
        // return $sy;
        $sy = DB::table('sy')
            ->select('id', 'sydesc as text', 'isactive')
            ->orderBy('isactive', 'desc')
            ->get();

        // return $semester;
        $semester = DB::table('semester')
            ->select('id', 'semester as text', 'isactive')
            ->where('deleted', 0)
            ->get();

        // return $schedclassification;
        $userid = auth()->user()->id;
        $teacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', $userid)
            ->get();
        
        $teacheracadprog = DB::table('teacheracadprog')
            ->select('acadprogid')
            ->where('teacherid', $teacherid[0]->id)
			->where('acadprogutype', 1)
            ->where('deleted', 0)
            ->get();
        
        $acadprog_list = array();
        foreach($teacheracadprog as $item){
                array_push($acadprog_list,$item->acadprogid);
        }
        // return $acadprog_list;

        // return $grade level;
        $gradelevel = DB::table('gradelevel')
            ->select('id', 'levelname as text')
            ->where('deleted', 0)
            ->where('id', '<' , 17)
            ->whereIn('acadprogid', $acadprog_list)
            ->orderBy('sortid', 'asc')
            ->get();

        

        // return subject component added by principal
        $subjcom = DB::table('teacher_subjcom')
            ->select('id', 'description as text')
            ->where('deleted', 0)
            ->where('teacherid', $teacherid[0]->id)
            ->get();

        $schedclassification = DB::table('schedclassification')
            ->select('id', 'description as text')
            ->where('deleted', 0)
            ->get();
        
        
        
        // return semid of subject
        $sh_subjects = DB::table('subject_plot')
            ->select(
                'subject_plot.syid',
                'subject_plot.semid',
                'subject_plot.levelid',
                'subject_plot.subjid',
                'subject_plot.strandid',
                'sh_subjects.subjtitle',
                'sh_subjects.subjcode'
                )
            ->join('sh_subjects', 'subject_plot.subjid', "=", 'sh_subjects.id')
            ->where('subject_plot.deleted', 0)
            ->get();

        return view('teacher.tchrscheduling.index')
            ->with('gradelevel', $gradelevel)
            ->with('sy', $sy)
            ->with('teacherid', $teacherid)
            ->with('schedclassification', $schedclassification)
            ->with('semester', $semester)
            ->with('sh_subjects', $sh_subjects)
            ->with('rooms', $rooms)
            ->with('subjcom', $subjcom);
    }

    //  Display All Section from View All Schedules Table
    public function tchrGetAllSections(Request $request){
        //$syid = $request->syid;
        //$gradelevelid = $request->gradelevelid;

        //if ($gradelevelid == null) {
            //$data = DB::table('sections')
            //->select(
                //'sections.sectionname',
                //'gradelevel.levelname',
                //'sections.levelid',
                //'sections.id as sectionid',
                //'rooms.id as roomid',
                //'rooms.roomname'
            //)
            //->join('sectiondetail', 'sections.id', "=", 'sectiondetail.sectionid')
            //->join('gradelevel', 'sections.levelid', "=", 'gradelevel.id')
            //->leftJoin('rooms', 'sections.roomid', "=", 'rooms.id')
            //->where('sections.deleted', 0)
            //->where('sectiondetail.syid', $syid)
            //->get();

        //} 
        //else {
            //$data = DB::table('sections')
            //->select(
                //'sections.sectionname',
                //'gradelevel.levelname',
                //'sections.levelid',
                //'sectiondetail.sectionid',
                //'rooms.id as roomid',
                //'rooms.roomname'
            //)
            //->join('sectiondetail', 'sections.id', "=", 'sectiondetail.sectionid')
            //->join('gradelevel', 'sections.levelid', "=", 'gradelevel.id')
            //->leftJoin('rooms', 'sections.roomid', "=", 'rooms.id')
            //->where('sections.deleted', 0)
            //->where('sectiondetail.syid', $syid)
            //->where('sections.levelid', $gradelevelid)
            //->get();
        //}
        //return $data;
		$userid = auth()->user()->id;
        $teacherid = DB::table('teacher')
            ->where('userid', $userid)
            ->get();
        
        $teacheracadprog = DB::table('teacheracadprog')
            ->select('acadprogid')
            ->where('teacherid', $teacherid[0]->id)
            ->where('acadprogutype', 1)
            ->where('deleted', 0)
            ->get();

        $acadprogIds = $teacheracadprog->pluck('acadprogid')->toArray();
        // return $acadprogIds;
        $syid = $request->syid;
        $gradelevelid = $request->gradelevelid;
        // return $request->all();
        if ($gradelevelid == null) {
            $data = DB::table('sections')
            ->select(
                'sections.sectionname',
                'gradelevel.levelname',
                'sections.levelid',
                'sections.id as sectionid',
                'rooms.id as roomid',
                'rooms.roomname',
                'gradelevel.acadprogid'
            )
            ->join('sectiondetail', 'sections.id', "=", 'sectiondetail.sectionid')
            ->join('gradelevel', 'sections.levelid', "=", 'gradelevel.id')
            ->leftJoin('rooms', 'sections.roomid', "=", 'rooms.id')
            ->where('sections.deleted', 0)
            ->where('sectiondetail.syid', $syid)
            ->get();
            
            // Filter $data based on acadprogids
            $filteredSections = $data->filter(function ($section) use ($acadprogIds) {
                return in_array($section->acadprogid, $acadprogIds);
            })->values(); // Use the values() method to reset array keys
            
        } 
        else {
            $data = DB::table('sections')
            ->select(
                'sections.sectionname',
                'gradelevel.levelname',
                'sections.levelid',
                'sectiondetail.sectionid',
                'rooms.id as roomid',
                'rooms.roomname',
                'gradelevel.acadprogid'
            )
            ->join('sectiondetail', 'sections.id', "=", 'sectiondetail.sectionid')
            ->join('gradelevel', 'sections.levelid', "=", 'gradelevel.id')
            ->leftJoin('rooms', 'sections.roomid', "=", 'rooms.id')
            ->where('sections.deleted', 0)
            ->where('sectiondetail.syid', $syid)
            ->where('sections.levelid', $gradelevelid)
            ->get();

            $filteredSections = $data->filter(function ($section) use ($acadprogIds) {
                return in_array($section->acadprogid, $acadprogIds);
            })->values(); // Use the values() method to reset array keys
        }
        return $filteredSections;
    }

    public function tchrViewAllSched(Request $request){
        $userid = auth()->user()->id;
        $syid = $request->get('syid');
        $teacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', $userid)
            ->get();
        
        
        
        $classsched = DB::table('classsched')
            ->select(
                'classscheddetail.stime',
                'classscheddetail.etime'
            )
            ->join('classscheddetail', function ($join){
                $join->on('classsched.id', "=", 'classscheddetail.headerid')
                ->where('classscheddetail.deleted', 0);
            })
            ->where('classsched.syid', $syid)
            ->where('classsched.deleted', 0)
            ->get();


        return $classsched;
    }

    // return subjects in SHS bys Sem
    public function tchrShsSubjectBySem(Request $request){
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');


        $strand = DB::table('sh_sectionblockassignment')
            ->select('sh_block.strandid')
            ->join('sh_block', 'sh_sectionblockassignment.blockid', "=", "sh_block.id")
			->join('sh_strand', 'sh_block.strandid', "=", "sh_strand.id")
            ->where('sh_sectionblockassignment.sectionid', $sectionid)
			->where('sh_strand.active',1)
            ->get();
        
        if ($levelid == 14 || $levelid == 15) {
		
			
            $subjects = DB::table('subject_plot')
							->select(
								'subject_plot.levelid',
								'sh_subjects.subjtitle as subjdesc',
								'subject_plot.subjid',
								'gradelevel.levelname',
								'gradelevel.acadprogid',
								'sections.roomid',
								'rooms.roomname'
								)
							->join('sh_subjects', 'subject_plot.subjid', "=", 'sh_subjects.id')
							->join('gradelevel', 'subject_plot.levelid', "=", 'gradelevel.id')
							->join('sections', 'gradelevel.id', "=", 'sections.levelid')
							->leftJoin('rooms', 'sections.roomid', "=", 'rooms.id')
							->where('subject_plot.levelid', $levelid)
							->where('subject_plot.syid', $syid)
							->where('subject_plot.deleted', 0)
							->whereIn('subject_plot.strandid', collect($strand)->pluck('strandid'))
							->where('subject_plot.semid', $semid)
							->get();

            $subjects = collect($subjects)->unique('subjid')->values();
			
 
            // sh_classsched = sectionid , subjid
            foreach ($subjects as $subject) {
                $teacher = DB::table('sh_classsched')
                    ->select(
                        'sh_classsched.teacherid','sh_classsched.sectionid',
                        DB::raw("CONCAT(teacher.lastname,', ', teacher.firstname) as teachername"),
						'teacher.id as tid'
                    )
                    ->join('teacher', 'sh_classsched.teacherid', "=", 'teacher.id')
                    ->where('teacher.deleted', 0)
                    ->where('sh_classsched.sectionid', $sectionid)
                    ->where('subjid', $subject->subjid)
                    ->where('sh_classsched.deleted', 0)
                    ->where('syid', $syid)
					->where('semid', $semid)
                    ->first();
                    
                if (isset($teacher->teacherid)) {
                    $subject->teacher = $teacher->teachername;
					$subject->tid = $teacher->tid;
                } else {
                    $subject->teacher = 'no teacher assign';
                }
            }
            
        } else {
            $subjects = DB::table('subject_plot')
            ->select(
                'subject_plot.levelid',
                'gradelevel.levelname',
                'gradelevel.id',
                'subjects.subjdesc as subjdesc',
				'subjects.isCon',
                'subject_plot.subjid',
                'gradelevel.acadprogid',
                'sections.roomid',
                'rooms.roomname'
            )
            ->join('subjects', 'subject_plot.subjid', "=", 'subjects.id')
            ->join('gradelevel', 'subject_plot.levelid', "=", 'gradelevel.id')
            ->join('sections', 'gradelevel.id', "=", 'sections.levelid')
            ->leftJoin('rooms', 'sections.roomid', "=", 'rooms.id')
            ->join('academicprogram', 'gradelevel.acadprogid', "=", 'academicprogram.id')
			->where('subjects.isCon', '!=', 1)
            ->where('subject_plot.levelid', $levelid)
            ->where('subject_plot.syid', $syid)
            ->where('subject_plot.deleted', 0)
            ->get();
            
            
            foreach ($subjects as $subject) {
                $teacher = DB::table('assignsubj')
                    ->select(
                        'assignsubjdetail.teacherid',
                        DB::raw("CONCAT(teacher.lastname,', ', teacher.firstname) as teachername"),
                        'teacher.id as tid'
                    )
                    ->join('assignsubjdetail', 'assignsubj.id', "=", 'assignsubjdetail.headerid')
                    ->join('teacher', 'assignsubjdetail.teacherid', "=", 'teacher.id')
                    ->where('assignsubj.sectionid', $sectionid)
                    ->where('assignsubjdetail.subjid', $subject->subjid)
                    ->where('assignsubj.deleted', 0)
                    ->where('assignsubjdetail.deleted', 0)
                    ->where('assignsubj.syid', $syid)
                    ->first();
                
                // $subject->sectionname = 'test';
                
                if (isset($teacher->teacherid)) {
                    $subject->teacher = $teacher->teachername;
					$subject->tid = $teacher->tid;
                } else {
                    $subject->teacher = 'no teacher assign';
                }
    
            }
            $subjects = collect($subjects)->unique('subjid')->values();
            // assign_subj join assign subjdetail
        }
        return $subjects;
    }
    // load all schedules available in the section
    public function tchrAllSectionSchedules(Request $request){
        $sectionid = $request->get('btn_viewschedules_id');
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');

        $strand = DB::table('sh_sectionblockassignment')
            ->select('sh_block.strandid')
            ->join('sh_block', 'sh_sectionblockassignment.blockid', "=", "sh_block.id")
            ->where('sh_sectionblockassignment.sectionid', $sectionid)
            ->get();
        


        if ($levelid == 14 || $levelid == 15) {

            
            $subjects = DB::table('subject_plot')
            ->select(
                'subject_plot.levelid',
                'sh_subjects.subjtitle as subjdesc',
                'subject_plot.subjid',
                'gradelevel.levelname',
                'subject_plot.semid',
                'gradelevel.acadprogid',
                'sections.roomid',
                'rooms.roomname'
                )
            ->join('sh_subjects', 'subject_plot.subjid', "=", 'sh_subjects.id')
            ->join('gradelevel', 'subject_plot.levelid', "=", 'gradelevel.id')
            ->join('sections', 'gradelevel.id', "=", 'sections.levelid')
            ->leftJoin('rooms', 'sections.roomid', "=", 'rooms.id')
            ->where('subject_plot.levelid', $levelid)
            ->where('subject_plot.syid', $syid)
            ->where('subject_plot.deleted', 0)
            ->whereIn('subject_plot.strandid', collect($strand)->pluck('strandid'))
            ->get();

            $subjects = collect($subjects)->unique('subjid')->values();
 
            // sh_classsched = sectionid , subjid
            foreach ($subjects as $subject) {
                $teacher = DB::table('sh_classsched')
                    ->select(
                        'sh_classsched.teacherid','sh_classsched.sectionid',
                        DB::raw("CONCAT(teacher.lastname,', ', teacher.firstname) as teachername"),
						'teacher.id as tid'
                    )
                    ->join('teacher', 'sh_classsched.teacherid', "=", 'teacher.id')
                    ->where('teacher.deleted', 0)
                    ->where('sh_classsched.sectionid', $sectionid)
                    ->where('subjid', $subject->subjid)
                    ->where('sh_classsched.deleted', 0)
                    ->where('syid', $syid)
                    ->first();
                    
                if (isset($teacher->teacherid)) {
                    $subject->teacher = $teacher->teachername;
					$subject->tid = $teacher->tid;
                } else {
                    $subject->teacher = 'no teacher assign';
                }
            }
            
        } else {
            $subjects = DB::table('subject_plot')
            ->select(
                'subject_plot.levelid',
                'gradelevel.levelname',
                'gradelevel.id',
                'subjects.subjdesc as subjdesc',
				'subjects.isCon',
                'subject_plot.subjid',
                'gradelevel.acadprogid',
                'sections.roomid',
                'rooms.roomname'
            )
            ->join('subjects', 'subject_plot.subjid', "=", 'subjects.id')
            ->join('gradelevel', 'subject_plot.levelid', "=", 'gradelevel.id')
            ->join('sections', 'gradelevel.id', "=", 'sections.levelid')
            ->leftJoin('rooms', 'sections.roomid', "=", 'rooms.id')
            ->join('academicprogram', 'gradelevel.acadprogid', "=", 'academicprogram.id')
			->where('subjects.isCon', '!=', 1)
            ->where('subject_plot.levelid', $levelid)
            ->where('subject_plot.syid', $syid)
            ->where('subject_plot.deleted', 0)
            ->get();

            $subjects = collect($subjects)->unique('subjid')->values();
            
            foreach ($subjects as $subject) {
                $teacher = DB::table('assignsubj')
                    ->select(
                        'assignsubjdetail.teacherid',
                        DB::raw("CONCAT(teacher.lastname,', ', teacher.firstname) as teachername"),
						'teacher.id as tid'
                    )
                    ->join('assignsubjdetail', 'assignsubj.id', "=", 'assignsubjdetail.headerid')
                    ->join('teacher', 'assignsubjdetail.teacherid', "=", 'teacher.id')
                    ->where('assignsubj.sectionid', $sectionid)
                    ->where('assignsubjdetail.subjid', $subject->subjid)
                    ->where('assignsubj.deleted', 0)
                    ->where('assignsubjdetail.deleted', 0)
                    ->where('assignsubj.syid', $syid)
                    ->first();
                
                if (isset($teacher->teacherid)) {
                    $subject->teacher = $teacher->teachername;
					$subject->tid = $teacher->tid;
                } else {
                    $subject->teacher = 'no teacher assign';
                }
    
            }
            $subjects = collect($subjects)->unique('subjid')->values();
            // assign_subj join assign subjdetail
        }
        return $subjects;
    }


    public function tchrgetallpercom() {
        $userid = auth()->user()->id;
        $teacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', $userid)
            ->get();

        // return subject component added by principal
        $subjcom = DB::table('teacher_subjcom')
            ->select('id', 'description as text')
            ->where('deleted', 0)
            ->where('teacherid', $teacherid[0]->id)
            ->get();
            
        return $subjcom;
    }
    
    
    // teacher add percentage component
    public function tchraddpercentagecomponent(Request $request){
        $ww = $request->get('ww_input');
        $qa = $request->get('qa_input');
        $pt = $request->get('pt_input');
        $ct = $request->get('ct_input');
        $teacherid = $request->get('teacherid');
        
        $checkifexist = DB::table('teacher_subjcom')
            ->where('ww',$ww)
            ->where('pt',$pt)
            ->where('qa',$qa)
            ->where('comp4',$ct)
            ->where('teacherid',$teacherid)
            ->where('deleted',0)
            ->count();

        if($checkifexist == 0){

            $description = '';

            if($ww != null){
                    $description .= 'WW'.$ww.' ';
            }
            if($pt != null){
                    $description .= 'PT'.$pt.' ';
            }

            if($qa != null){
                    $description .= 'QA'.$qa.' ';
            }

            if($ct != null){
                    $description .= 'CG'.$ct.' ';
            }


            DB::table('teacher_subjcom')
                    ->insert([
                        'description'=>$description,
                        'ww'=>$ww,
                        'pt'=>$pt,
                        'qa'=>$qa,
                        'comp4'=>$ct,
                        'teacherid'=>$teacherid,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return array((object)[
                    'status'=>1,
                    'message'=>'Component Percentage Created!',
            ]);

        }else{
            return array((object)[
                    'status'=>0,
                    'message'=>'Already Exist!',
            ]);
        }
    }
 
    
    public function tchreditpercentagecomponent(Request $request){
        $percomid = $request->get('percomid');
        $teacherid = $request->get('teacherid');
        $ww = $request->get('ww');
        $qa = $request->get('qa');
        $pt = $request->get('pt');
        $ct = $request->get('ct');

        $checkifexist = DB::table('teacher_subjcom')
            ->where('ww',$ww)
            ->where('pt',$pt)
            ->where('qa',$qa)
            ->where('comp4',$ct)
            ->where('teacherid',$teacherid)
            ->where('deleted',0)
            ->count();

        $description = '';

        if($ww != null){
                $description .= 'WW'.$ww.' ';
        }
        if($pt != null){
                $description .= 'PT'.$pt.' ';
        }

        if($qa != null){
                $description .= 'QA'.$qa.' ';
        }

        if($ct != null){
                $description .= 'CG'.$ct.' ';
        }

        if($checkifexist == 0){
            DB::table('teacher_subjcom')
                ->where('id', $percomid)
                ->update([
                    'description'=>$description,
                    'ww'=>$ww,
                    'qa'=>$qa,
                    'pt'=>$pt,
                    'comp4'=>$ct,
                    'updatedby' => $teacherid,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);


            return array((object)[
                'status'=>1,
                'message'=>'Component Percentage Created!',
            ]);

        }else{
            return array((object)[
                'status'=>0,
                'message'=>'Already Exist!',
            ]);
        }
    }


    public function tchrdeletepercentagecomponent(Request $request){
        $percomid = $request->get('percomid');
        $teacherid = $request->get('teacherid');


        // return $teacherid;
        // check first table teacher_subjectdetail if nagamit ang percentage
        $checkifexist = DB::table('teacher_subjectcomponentdetail')
            ->where('headerid', $percomid)
            ->where('createdby', $teacherid)
            ->count();

        if($checkifexist == 0){
            DB::table('teacher_subjcom')
                ->where('id', $percomid)
                ->update([
                    'deleted'=>1,
                    'deletedby' => $teacherid,
                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

                return array((object)[
                    'status'=>1,
                    'message'=>'Component Percentage Deleted!',
                ]);

        } else {
            return array((object)[
                'status'=>0,
                'message'=>'Already Used!',
            ]);
        }

    }

    // get all Component Percentage Created by Teacher
    public function tchrgetallpercentagecomponent(Request $request){
        $teacherid = $request->get('teacherid');
        
        $data = DB::table('teacher_subjcom')
            ->where('teacherid', $teacherid)
            ->where('deleted', 0)
            ->get();
        
        return $data;
    }


    // teacher add subject component / percentage
    public function tchraddsubjectcomponent(Request $request){
        $userid = auth()->user()->id;
        $teacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', $userid)
            ->first();

		//return $request->all();
        $levelid = $request->get('levelid');
        $sectionid = $request->get('sectionid');
        $subjid = $request->get('subjid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $teacherid = $request->get('teacherid');
        $headerid = $request->get('headerid');
        $subjcomid = $request->get('subjcomid');

        $subjcomexist = DB::table('teacher_subjectcomponentdetail')
            ->where('levelid', $levelid)
            ->where('sectionid', $sectionid)
            ->where('subjid', $subjid)
            ->where('semid', $semid)
            ->count();

        if ($subjcomexist) {
            // return response()->json(['message' => 'Record already exists in the table'], 400);
            $data = DB::table('teacher_subjectcomponentdetail')
                ->where('levelid', $levelid)
				->where('teacherid', $teacherid )
                ->where('sectionid', $sectionid)
                ->where('subjid', $subjid)
                ->where('semid', $semid)
                ->update([
                    'subjcomid' => $subjcomid,
                    'deleted' => 0,
                    'updatedby' => $teacherid
                ]);

            return $data;
        } else {
            $data = DB::table('teacher_subjectcomponentdetail')->insert([
                'levelid' => $levelid,
                'subjcomid' => $subjcomid,
                'sectionid' => $sectionid,
                'subjid' => $subjid,
                'teacherid' => $teacherid,
                'headerid' => $subjcomid,
                'syid' => $syid,
                'semid' => $semid,
                'createdby' => $teacherid
            ]);
    
            return response()->json($data);
        }
        
    }

    // get all the subject component of the teacher
    public function loadteachersubjcom(Request $request){
		
        $userid = auth()->user()->id;
        $teacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', $userid)
            ->first();
		//return $request->all();
        $teachersubjcom = DB::table('teacher_subjectcomponentdetail')
            ->select(
                'teacher_subjectcomponentdetail.id',
                'teacher_subjectcomponentdetail.headerid',
                'teacher_subjectcomponentdetail.levelid',
                'teacher_subjectcomponentdetail.subjcomid',
                'teacher_subjectcomponentdetail.sectionid',
                'teacher_subjectcomponentdetail.subjid',
                'teacher_subjectcomponentdetail.syid',
                'teacher_subjectcomponentdetail.teacherid',
                'subject_gradessetup.description',
                'subject_gradessetup.ww',
                'subject_gradessetup.pt',
                'subject_gradessetup.qa',
                'subject_gradessetup.comp4'
            )
            // ->join('teacher_subjcom', 'teacher_subjectcomponentdetail.headerid', '=', 'teacher_subjcom.id')
            ->leftJoin('subject_gradessetup', 'teacher_subjectcomponentdetail.headerid', '=', 'subject_gradessetup.id')
            ->where('teacher_subjectcomponentdetail.deleted', 0)
            ->where('teacher_subjectcomponentdetail.teacherid', $teacherid->id)
            ->get();
        
        return $teachersubjcom;
    }


    // edit subject component of teacher
    public function editeachersubjcom(Request $request){
        $teachersubjcomid = $request->get('teachersubjcomid');
        $subjcomid = $request->get('subjcomid');
        $headerid = $request->get('headerid');

        $userid = auth()->user()->id;
        $teacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', $userid)
            ->first();

        $data = DB::table('teacher_subjectcomponentdetail')
            ->where('id', $teachersubjcomid)
            ->update([
                'subjcomid' => $subjcomid,
                'headerid' => $subjcomid,
                'updatedby' => $teacherid->id
            ]);

        return $data;
    }

    // delete subject component of teacher
    public function deleteteachersubjcom(Request $request){
        $teachersubjcomid = $request->get('teachersubjcomid');
        $subjcomid = $request->get('subjcomid');

        $userid = auth()->user()->id;
        $teacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', $userid)
            ->first();
            
        $data = DB::table('teacher_subjectcomponentdetail')
                ->where('id', $teachersubjcomid)
                ->update([
                    'deleted' => 1,
                    'deletedby' => $teacherid->id
                ]);

        return $data;
    }
    
    // get all subject plot nga gi assign kang teacher

    public function getteachersubjectplot(Request $request){
        $levelid = $request->get('teacherlevellist');

        if (empty($levelid)) {
            $gslevelid = [];
            $shslevelid = [];
        } else {
            $gslevelid = array_filter($levelid, function ($value) {
                return $value != 14 && $value != 15;
            });
            $shslevelid = array_filter($levelid, function ($value) {
                return $value == 14 || $value == 15;
            });
        }
        // $intlevelid = array_map('intval', $levelid);
        $gslevelid = array_values($gslevelid);
        $shslevelid = array_values($shslevelid);
        // return $gslevelid;
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        // return $shslevelid;
        $data = DB::table('subject_plot')
            ->select(
                'subject_plot.levelid',
                'subject_plot.gradessetup',
                'subject_plot.subjid',
                'subjects.subjdesc',
                'subject_gradessetup.description',
                'subject_gradessetup.ww',
                'subject_gradessetup.pt',
                'subject_gradessetup.qa',
                'subject_gradessetup.comp4'
                )
            ->join('subjects', 'subject_plot.subjid', '=', 'subjects.id')
            ->join('subject_gradessetup', 'subject_plot.gradessetup', '=', 'subject_gradessetup.id')
            ->where('subject_plot.deleted', 0)
            ->where('subject_gradessetup.deleted', 0)
            ->where('subjects.deleted', 0)
			
            ->where('subject_plot.syid', $syid)
            ->where('subject_plot.gradessetup', '!=', null)
            ->whereIn('subject_plot.levelid', $gslevelid)
            ->get();
            
        // return $data;
        $shsdata = DB::table('subject_plot')
            ->select(
                'subject_plot.levelid',
                'subject_plot.gradessetup',
                'subject_plot.subjid',
                'sh_subjects.subjtitle as subjdesc',
                'subject_gradessetup.description',
                'subject_gradessetup.ww',
                'subject_gradessetup.pt',
                'subject_gradessetup.qa',
                'subject_gradessetup.comp4'
                )
            ->join('sh_subjects', 'subject_plot.subjid', '=', 'sh_subjects.id')
            ->join('subject_gradessetup', 'subject_plot.gradessetup', '=', 'subject_gradessetup.id')
            ->where('subject_plot.deleted', 0)
            ->where('subject_gradessetup.deleted', 0)
            ->where('sh_subjects.deleted', 0)
            ->where('subject_plot.syid', $syid)
			->where('subject_plot.semid', $semid)
            ->where('subject_plot.gradessetup', '!=', null)
            ->whereIn('subject_plot.levelid', $shslevelid)
            ->get();
        
        $subjects = collect(array());

        $subjects = $subjects->merge($data);
        $subjects = $subjects->merge($shsdata);

        return $subjects;
    }

    public function teacherdeletedsubjcom() {
        $userid = auth()->user()->id;
        $teacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', $userid)
            ->first();

        // return all subject component added by teacher wwhere deleted is = 1 / being deleted
        $deletedsubjcom = DB::table('teacher_subjectcomponentdetail')
        ->join('teacher_subjcom', 'teacher_subjectcomponentdetail.headerid', '=', 'teacher_subjcom.id')
        ->where('teacher_subjcom.teacherid', $teacherid->id)          
        ->where('teacher_subjectcomponentdetail.deleted', 1)          
        ->get();
  
      return $deletedsubjcom;
    }
    
    
}
