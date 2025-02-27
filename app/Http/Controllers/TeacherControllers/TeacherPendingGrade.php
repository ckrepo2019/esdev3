<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;

class TeacherPendingGrade extends \App\Http\Controllers\Controller
{
    //get list of pending student grades
    public static function peding_student_grades(Request $request){

        // return $request->all();

        $subject = array();

        $syid = $request->get('syid');

        if($syid == null){
            $syid = DB::table('sy')
                        ->where('isactive',1)
                        ->first()
                        ->id;
        }


       

        $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->first()->id;

        $sched = DB::table('sh_classsched')
                ->where('sh_classsched.syid',$syid)
                ->where('sh_classsched.deleted',0)
                ->where('sh_classsched.teacherid',$teacherid)
                ->join('sh_subjects',function($join) use($teacherid){
                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                    $join->where('sh_subjects.deleted',0);
                })
                ->join('gradelevel',function($join) use($teacherid){
                    $join->on('sh_classsched.glevelid','=','gradelevel.id');
                    $join->where('gradelevel.deleted',0);
                })
                ->join('sections',function($join) use($teacherid){
                    $join->on('sh_classsched.sectionid','=','sections.id');
                    $join->where('sections.deleted',0);
                })
                ->select(
                    'subjid',
                    'glevelid as levelid',
                    'sectionid',
                    'subjtitle as subjdesc',
                    'subjcode',
                    'type',
                    'sectionname',
                    'levelname',
                    'gradelevel.acadprogid',
                    'sh_classsched.semid',
                    'sh_isSP as isSP'
                )
                ->get();

                // return $sched;

        foreach($sched as $item){
        
            $check_if_exist_in_plot = DB::table('subject_plot')
                                            ->where('deleted',0)
                                            ->where('levelid',$item->levelid)
                                            ->where('subjid',$item->subjid)
                                            ->where('syid',$syid)
                                            ->count();

            if($check_if_exist_in_plot > 0){
                    array_push($subject, $item);
            }      

        }

        // dd($subject);

        $asssubj = DB::table('assignsubj')
                    ->where('assignsubj.syid',$syid)
                    ->where('assignsubj.deleted',0)
                    ->join('assignsubjdetail',function($join) use($teacherid){
                        $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                        $join->where('assignsubjdetail.deleted',0);
                        $join->where('assignsubjdetail.teacherid',$teacherid);
                    })
                    ->join('subjects',function($join){
                        $join->on('assignsubjdetail.subjid','=','subjects.id');
                        $join->where('subjects.deleted',0);
                    })
                    ->join('gradelevel',function($join) use($teacherid){
                        $join->on('assignsubj.glevelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted',0);
                    })
                    ->join('sections',function($join) use($teacherid){
                        $join->on('assignsubj.sectionid','=','sections.id');
                        $join->where('sections.deleted',0);
                    })
                    ->leftJoin('teacher',function($join){
                        $join->on('assignsubjdetail.teacherid','=','teacher.id');
                        $join->where('teacher.deleted',0);
                    })
                    ->select(
                        'isSP',
                        'subjid',
                        'glevelid as levelid',
                        'sectionid',
                        'subjdesc',
                        'subjcode',
                        'sectionname',
                        'levelname',
                        'gradelevel.acadprogid'
                    )
                    ->get();

        foreach($asssubj as $item){

            $check_if_exist_in_plot = DB::table('subject_plot')
                                            ->where('deleted',0)
                                            ->where('levelid',$item->levelid)
                                            ->where('subjid',$item->subjid)
                                            ->where('syid',$syid)
                                            ->count();

            if($check_if_exist_in_plot > 0){
                    array_push($subject, $item);
            }                    
        }

        // dd($asssubj);

       

        foreach($subject as $item){

            $item->with_pending = false;
            $pending_quarter = array();
            $temp_strand = array();

            for($x = 1; $x<=4;$x++){

                if($item->isSP == 0){

                    $temp_students = array();

                    $acad = DB::table('gradelevel')
                                ->where('id',$item->levelid)
                                ->first();

                    if($acad->acadprogid == 5){
                        $enrolledstud = DB::table('sh_enrolledstud')
                            ->where('sh_enrolledstud.sectionid',$item->sectionid)
                            ->where('sh_enrolledstud.levelid',$item->levelid)
                            ->where('sh_enrolledstud.syid',$syid)
                            ->where('sh_enrolledstud.deleted',0)
                            ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                            ->select(
                                'sh_enrolledstud.studid'
                            )
                            ->get();
                    }else{
                        $enrolledstud = DB::table('enrolledstud')
                            ->where('enrolledstud.sectionid',$item->sectionid)
                            ->where('enrolledstud.levelid',$item->levelid)
                            ->where('enrolledstud.syid',$syid)
                            ->where('enrolledstud.deleted',0)
                            ->whereIn('enrolledstud.studstatus',[1,2,4])
                            ->select(
                                'enrolledstud.studid'
                            )
                            ->get();

                    }
                    
                    $grade_count = DB::table('grades')
                                ->where('levelid',$item->levelid)
                                ->where('sectionid',$item->sectionid)
                                ->where('grades.status',3)
                                ->where('syid',$syid)
                                ->where('quarter',$x)
                                ->where('subjid',$item->subjid)
                                ->where('deleted',0)
                                ->join('gradesdetail',function($join) use($teacherid, $enrolledstud){
                                    $join->on('grades.id','=','gradesdetail.headerid');
                                    $join->where('gradesdetail.gdstatus',3);
                                    $join->whereIn('gradesdetail.studid',collect($enrolledstud)->pluck('studid'));
                                })
                                ->select('grades.id')
                                ->first();
    
                    if(isset($grade_count->id)){
    
    
                        if($item->levelid == 14 || $item->levelid == 15){
    
                            $strandinfo = DB::table('gradesdetail')
                                                ->where('gradesdetail.headerid',$grade_count->id)
                                                ->where('gradesdetail.gdstatus',3)
                                                ->join('sh_enrolledstud',function($join){
                                                    $join->on('gradesdetail.studid','=','sh_enrolledstud.studid');
                                                    $join->where('sh_enrolledstud.deleted',0);
                                                    $join->whereIn('sh_enrolledstud.studstatus',[1,2,4]);
                                                })
                                                ->join('sh_strand',function($join){
                                                    $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                                    $join->where('sh_strand.deleted',0);
                                                })->distinct('strandid')
                                                ->select(
                                                    'strandid',
                                                    'strandcode'
                                                )->get();
    
                            foreach($strandinfo as $strand_item){
                                array_push($temp_strand,$strand_item);
                            }
    
                        }
    
                        array_push($pending_quarter,$x);
                        $item->with_pending = true;
                    }
                }else{
                    
                    $quarter  = $x; 
                    $subjid = $item->subjid;
                    $sectionid = $item->sectionid;
                    $levelid = $item->levelid;
                    
                    $enrolledstud = DB::table('enrolledstud')
                                    ->join('subjects_studspec',function($join) use($subjid,$quarter,$syid){
                                        $join->on('enrolledstud.studid','=','subjects_studspec.studid');
                                        $join->where('subjects_studspec.deleted',0);
                                        $join->where('subjects_studspec.syid',$syid);
                                        $join->where('subjects_studspec.subjid',$subjid);
                                        if($quarter == 1){
                                            $join->where('subjects_studspec.q1',1);
                                        }else if($quarter == 2){
                                            $join->where('subjects_studspec.q2',1);
                                        }else if($quarter == 3){
                                            $join->where('subjects_studspec.q3',1);
                                        }else if($quarter == 4){
                                            $join->where('subjects_studspec.q4',1);
                                        }
                                    })
                                    ->where('enrolledstud.sectionid',$sectionid)
                                    ->where('enrolledstud.levelid',$levelid)
                                    ->where('enrolledstud.syid',$syid)
                                    ->where('enrolledstud.deleted',0)
                                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                                    ->select(
                                        'subjects_studspec.studid'
                                    )
                                    ->get();
                    
                    $grade_count = DB::table('grades')
                                ->where('levelid',$item->levelid)
                                ->where('sectionid',$item->sectionid)
                                ->where('grades.status','!=',3)
                                ->where('syid',$syid)
                                ->where('quarter',$x)
                                ->where('subjid',$item->subjid)
                                ->where('deleted',0)
                                ->join('gradesdetail',function($join) use($enrolledstud){
                                    $join->on('grades.id','=','gradesdetail.headerid');
                                    $join->where('gradesdetail.gdstatus',3);
                                    $join->whereIn('gradesdetail.studid',collect($enrolledstud)->pluck('studid'));
                                })
                                ->select('grades.id')
                                ->first();
                                
                    if(isset($grade_count->id)){
                        array_push($pending_quarter,$x);
                        $item->with_pending = true;
                    }
                }

              

            }
          
            $item->strand = collect($temp_strand)->unique('strandid')->values();
            $item->pending_quarter = $pending_quarter;
            
        }

        // dd($subject);
        
        return collect($subject)->where('with_pending',true)->values();

    }



    public function getGrades(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $gradelevelid = $request->get('gradelevelid');
        $subjectid = $request->get('subjectid');
        $quarter =  $request->get('quarter');
        $sectionid =  $request->get('sectionid');
        

        if($request->get('syid') == null){
            $syid =  DB::table('sy')->where('isactive',1)->select('id')->first()->id;
        }

        if($request->get('semid') == null){
            $semid =  DB::table('semester')->where('isactive',1)->select('id')->first()->id;
        }

        $levelInfo = DB::table('gradelevel')->where('id',$gradelevelid)->select('id','acadprogid')->first();

        $gradesetup = DB::table('subject_plot')
                        ->where('syid',$syid)
                        ->where('levelid',$gradelevelid);

        if($gradelevelid == 14 || $gradelevelid == 15){
            $gradesetup = $gradesetup->where('strandid',$request->get('strandid'))
                                    ->where('semid',$semid);
        }
                       
        $gradesetup = $gradesetup->where('subjid',$subjectid)
                        ->where('subject_plot.deleted',0)
                        ->join('subject_gradessetup',function($join){
                            $join->on('subject_plot.gradessetup','=','subject_gradessetup.id');
                            $join->where('subject_gradessetup.deleted',0);
                        })
                        ->select(
                            'subject_plot.id',
                            'ww as writtenworks',
                            'pt as performancetask',
                            'qa as qassesment'
                        )
                        ->first();

       
        if($levelInfo->acadprogid != 5){

           

            $semid = 1;
        }

        if(!isset($gradesetup->id)){
            return "NGS";
        }

        $enrolledstud = [];



        $grade_header = DB::table('grades')
                            ->where('sectionid',$sectionid)
                            ->where('levelid',$gradelevelid)
                            ->where('quarter',$quarter)
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->where('subjid',$subjectid)
                            ->get();

        if($levelInfo->acadprogid != 5){

            $check_subject = DB::table('subjects')
                    ->where('deleted',0)
                    ->where('id',$subjectid)
                    ->first();

            $temp_students = array();

            if( isset($check_subject->isSP)){
                if($check_subject->isSP == 1){
                    $enrolledstud = DB::table('enrolledstud')
                        ->join('studinfo',function($join){
                            $join->on('enrolledstud.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->join('subjects_studspec',function($join) use($subjectid){
                            $join->on('enrolledstud.studid','=','subjects_studspec.studid');
                            $join->where('subjects_studspec.deleted',0);
                            $join->where('subjects_studspec.subjid',$subjectid);
                      })
                        ->where('enrolledstud.sectionid',$sectionid)
                        ->where('enrolledstud.levelid',$gradelevelid)
                        ->where('enrolledstud.syid',$syid)
                        ->where('enrolledstud.deleted',0)
                        ->whereIn('enrolledstud.studstatus',[1,2,4])
                        ->orderBy('gender','desc')
                        ->orderBy('lastname')
                        ->select(
                            'enrolledstud.studid',
                            'firstname',
                            'lastname',
                            'middlename',
                            'suffix',
                            'mol',
                            'gender',
                            DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('gender','desc')
                        ->orderBy('studentname','asc')
                        ->get();
                }else{
                    $enrolledstud = DB::table('enrolledstud')
                        ->join('studinfo',function($join){
                            $join->on('enrolledstud.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->where('enrolledstud.sectionid',$sectionid)
                        ->where('enrolledstud.levelid',$gradelevelid)
                        ->where('enrolledstud.syid',$syid)
                        ->where('enrolledstud.deleted',0)
                        ->whereIn('enrolledstud.studstatus',[1,2,4])
                        ->orderBy('gender','desc')
                        ->orderBy('lastname')
                        ->select(
                            'studid',
                            'firstname',
                            'lastname',
                            'middlename',
                            'suffix',
                            'mol',
                            'gender',
                            DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                        )
                        ->orderBy('gender','desc')
                        ->orderBy('studentname','asc')
                        ->get();
                }

                foreach($enrolledstud as $item){
                    array_push($temp_students,$item);
                }

                $enrolledstud = DB::table('student_specsubj')
                        ->join('studinfo',function($join){
                            $join->on('student_specsubj.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->join('enrolledstud',function($join) use($syid){
                            $join->on('student_specsubj.studid','=','enrolledstud.studid');
                            $join->whereIn('enrolledstud.studstatus',[1,2,4]);
                            $join->where('enrolledstud.deleted',0);
                            $join->where('enrolledstud.syid',$syid);
                        })
                        ->where('student_specsubj.status','ADDITIONAL')
                        ->where('student_specsubj.syid',$syid)
                        ->where('student_specsubj.deleted',0)
                        ->where('student_specsubj.sectionid',$sectionid)
                        ->where('student_specsubj.subjid',$subjectid)
                        ->select(
                            'enrolledstud.studid',
                            'firstname',
                            'lastname',
                            'middlename',
                            'suffix',
                            'mol',
                            'gender',
                            DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                        )
                        ->get();

                foreach($enrolledstud as $item){
                    array_push($temp_students,$item);
                }

                $enrolledstud = collect($temp_students)->sortBy('studentname')->sortBy('gender')->values();

            }

           

        }else{

            $temp_strand = $request->get('strandid');

            if($temp_strand != null){
                $subject_strand = DB::table('sh_strand')
                                    ->where('id',$temp_strand)
                                    ->select('id as strandid')
                                    ->get();
            }else{
                $subject_strand = DB::table('subject_plot')
                                        ->where('syid',$syid)
                                        ->where('levelid',$gradelevelid)
                                        ->where('subjid',$subjectid)
                                        ->where('semid',$semid)
                                        ->where('deleted',0)
                                        ->select('strandid')
                                        ->get();

            }

     
           
            $temp_strand = array();

            foreach($subject_strand as $item){
                array_push($temp_strand , $item->strandid);
            }


            foreach($subject_strand as $item){
                array_push($temp_strand , $item->strandid);
            }

            $temp_students = array();

            $enrolledstud = DB::table('sh_enrolledstud')
                            ->join('studinfo',function($join){
                                $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->join('sh_strand',function($join){
                                $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                $join->where('sh_strand.deleted',0);
                            })
                            ->where('sh_enrolledstud.sectionid',$sectionid)
                            ->where('sh_enrolledstud.levelid',$gradelevelid)
                            ->where('sh_enrolledstud.syid',$syid)
                            ->where('sh_enrolledstud.semid',$semid)
                            ->where('sh_enrolledstud.deleted',0)
                            ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                            ->whereIn('sh_enrolledstud.strandid',$temp_strand)
                            ->orderBy('gender','desc')
                            ->orderBy('lastname')
                            ->select(
                                'studid',
                                'firstname',
                                'lastname',
                                'middlename',
                                'suffix',
                                'mol',
                                'gender',
                                'strandcode'
                            )
                            ->get();

            foreach($enrolledstud as $item){
                array_push($temp_students,$item);
            }

            $enrolledstud = DB::table('student_specsubj')
                        ->join('studinfo',function($join){
                            $join->on('student_specsubj.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->join('sh_enrolledstud',function($join) use($syid,$semid){
                            $join->on('student_specsubj.studid','=','sh_enrolledstud.studid');
                            $join->whereIn('sh_enrolledstud.studstatus',[1,2,4]);
                            $join->where('sh_enrolledstud.deleted',0);
                            $join->where('sh_enrolledstud.syid',$syid);
                            $join->where('sh_enrolledstud.semid',$semid);
                        })
                        ->join('sh_strand',function($join){
                            $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                            $join->where('sh_strand.deleted',0);
                        })
                        ->where('student_specsubj.status','ADDITIONAL')
                        ->where('student_specsubj.syid',$syid)
                        ->where('student_specsubj.semid',$semid)
                        ->where('student_specsubj.sectionid',$sectionid)
                        ->where('student_specsubj.subjid',$subjectid)
                        ->where('student_specsubj.deleted',0)
                        ->select(
                            'sh_enrolledstud.studid',
                            'firstname',
                            'lastname',
                            'middlename',
                            'suffix',
                            'mol',
                            'gender',
                            'strandcode',
                            DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                        )
                        ->get();

                foreach($enrolledstud as $item){
                    array_push($temp_students,$item);
                }

                $enrolledstud = collect($temp_students)->sortBy('studentname')->sortBy('gender')->values();


            
        }

      
        if( count($enrolledstud) == 0){
            return "NSE";
        }
	
        if(count($grade_header) == 0){

            $gradeId = DB::table('grades')->insertGetId([
                'syid'=>$syid,
                'levelid'=>$gradelevelid,
                'quarter'=>$quarter,
                'sectionid'=>$sectionid,
                'subjid'=>$subjectid,
                'deleted'=>0,
                'createdby'=>auth()->user()->id,
                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                'submitted'=>0,
                'status'=>0,
                'wwhr1'=>0,
                'wwhr2'=>0,
                'wwhr3'=>0,
                'wwhr4'=>0,
                'wwhr5'=>0,
                'wwhr6'=>0,
                'wwhr7'=>0,
                'wwhr8'=>0,
                'wwhr9'=>0,
                'wwhr0'=>0,
                'pthr1'=>0,
                'pthr2'=>0,
                'pthr3'=>0,
                'pthr4'=>0,
                'pthr5'=>0,
                'pthr6'=>0,
                'pthr7'=>0,
                'pthr8'=>0,
                'pthr9'=>0,
                'pthr0'=>0,
                'qahr1'=>0,
                'semid'=>$semid
            ]);

            $grade_header = DB::table('grades')
                                    ->where('id',$gradeId)
                                    ->where('deleted',0)
                                    ->get();

        }
    

        $hps = $grade_header;

        $is_modular = false;
        $mode_of_learning = '';
        $grades = [];
        
        if(count($grade_header) > 0){
            foreach($enrolledstud as $item){
                $middlename = explode(" ",$item->middlename);
                $temp_middle = '';
                if($middlename != null){
                    foreach ($middlename as $middlename_item) {
                        if(strlen($middlename_item) > 0){
                            $temp_middle .= $middlename_item[0].'.';
                        } 
                    }
                }

                $item->student = $item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;

                $ifGradeExist = DB::table('gradesdetail')
                                    ->where('headerid',$hps[0]->id)
                                    ->where('studid',$item->studid)
                                    ->where('gdstatus',3)
                                    ->first();
                

                if(isset($ifGradeExist->id)){
                    $ifGradeExist->student = $item->student;
                    $ifGradeExist->gender = $item->gender;
                    if($levelInfo->acadprogid != 5){
                        $ifGradeExist->stradname = null;
                    }else{
                        $ifGradeExist->stradname = $item->strandcode;
                    }
                    array_push($grades,$ifGradeExist);
                }

              
            }
       }
	   
        $submitted = $hps[0]->submitted == 0 ? false : true;
        $is_modular = false;

        $transmutation = DB::table('gradetransmutation')->get();
        
        if(isset($temp_sectioninfo->sectionname)){
            if (str_contains($temp_sectioninfo->sectionname, 'MODULAR')) {
                $transmutation = DB::table('gradetransmutation_modular')->get();
                $transmutation_array = array();
                foreach($transmutation as $item){
                    $item->gfrom = number_format($item->gfrom,2);
                    $item->gto = number_format($item->gto,2);
                    if($item->gto == 90.00){
                        $item->gto = number_format(99.99,2);
                 
                    }
                     array_push($transmutation_array,$item);
                }
                
                array_push($transmutation_array,(object)[
                        'id'=>32,
                        'gto'=>number_format(100,2),
                        'gfrom'=>number_format(100,2),
                        'gvalue'=>90
                    ]);
                    
                $transmutation = $transmutation_array;
            }
        }

        $hps[0]->date_submitted = \Carbon\Carbon::create($hps[0]->date_submitted)->isoFormat('MMMM DD, YYYY hh:mm a');
        $submitted = false;
        $hps[0]->submitted = 0;

        return view('teacher.grading.gradestable')
                            ->with('grades',$grades)
                            ->with('hps',$hps)
                            ->with('is_modular',$is_modular)
                            ->with('submitted',$submitted)
                            ->with('transmutation',$transmutation)
                            ->with('activeSem',$semid)
                            ->with('gradesetup',$gradesetup);
        
      

    }

    public static function submit_pending_grades(Request $request){

        $levelid = $request->get('levelid');
        $sectionid = $request->get('sectionid');
        $subjid = $request->get('subjid');
        $quarter = $request->get('quarter');
        $include = $request->get('include');
        $syid = $request->get('syid');

        if($include == null){
            $include = array();
        }

        try{

            DB::table('grades')
                ->where('sectionid',$sectionid)
                ->where('subjid',$subjid)
                ->where('levelid',$levelid)
                ->where('quarter',$quarter)
                ->where('syid',$syid)
                ->where('grades.deleted',0)
                ->join('gradesdetail',function($join) use($include){
                    $join->on('grades.id','=','gradesdetail.headerid');
                    $join->where('gradesdetail.gdstatus',3);
                    $join->whereIn('gradesdetail.studid',$include);
                })
                ->update([
                    'gradesdetail.updatedby'=>auth()->user()->id,
                    'gradesdetail.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'gdstatus'=>1,
                    'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

                $headercount = DB::table('grades')
                                ->where('sectionid',$sectionid)
                                ->where('subjid',$subjid)
                                ->where('levelid',$levelid)
                                ->where('quarter',$quarter)
                                ->where('syid',$syid)
                                ->where('grades.deleted',0)
                                ->join('gradesdetail',function($join) use($include){
                                    $join->on('grades.id','=','gradesdetail.headerid');
                                    $join->where('gradesdetail.gdstatus',3);
                                })
                                ->count();

                if($headercount == 0){
                    DB::table('grades')
                        ->where('sectionid',$sectionid)
                        ->where('subjid',$subjid)
                        ->where('levelid',$levelid)
                        ->where('quarter',$quarter)
                        ->where('syid',$syid)
                        ->where('grades.deleted',0)
                        ->update([
                            'status'=>0,
                            'submitted'=>1,
                            'submittedby'=>auth()->user()->id,
                            'date_submitted'=>\Carbon\Carbon::now('Asia/Manila'),
                            'coorapp'=>null,
                            'coorappdatetime'=>null,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        ]);
                }
           

            return array((object)[
                    'status'=>1,
                    'data'=>'Submitted Successfully!',
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }

        
            


    }

    public static function store_error($e){
        DB::table('zerrorlogs')
        ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
        return array((object)[
              'status'=>0,
              'data'=>'Something went wrong!'
        ]);
    }

    //get list of pending student grades

}


     