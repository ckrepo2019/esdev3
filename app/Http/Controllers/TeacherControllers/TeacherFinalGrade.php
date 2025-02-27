<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;

class TeacherFinalGrade extends \App\Http\Controllers\Controller
{
    public static function submit_grades(Request $request){

        $id = $request->get('id');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $levelid = $request->get('levelid');
        $quarter = $request->get('quarter');
        $subjid = $request->get('subjid');

        DB::table('grades')
            ->where('id',$id)
            ->where('levelid',$levelid)
            ->where('syid',$syid)
            ->where('sectionid',$sectionid)
            ->where('subjid',$subjid)
            ->where('deleted',0)
            ->update([
                'status'=>0,
                'submitted'=>1,
                'coorapp'=>null,
                'coorappdatetime'=>null,
                'date_submitted'=>\Carbon\Carbon::now('Asia/Manila')
            ]);

        DB::table('grades')
            ->where('grades.id',$id)
            ->where('grades.levelid',$levelid)
            ->where('grades.syid',$syid)
            ->where('grades.sectionid',$sectionid)
            ->where('grades.subjid',$subjid)
            ->where('grades.deleted',0)
            ->join('gradesdetail',function($join){
                $join->on('grades.id','=','gradesdetail.headerid');
                $join->whereIn('gdstatus',[0,3]);
            })
            ->update([
                'gradesdetail.gdstatus'=>1,
                'gradesdetail.updatedby'=>auth()->user()->id,
                'gradesdetail.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                'gradesdetail.statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);


    }


    public static function gradestatus(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $levelid = $request->get('levelid');
        $quarter = $request->get('quarter');
        $subjid = $request->get('subjid');




        if($levelid == 14 || $levelid == 15){

            if($semid == 1){
                for($x = 1; $x <= 2; $x ++){
                    $check = DB::table('grades')
                                ->where('levelid',$levelid)
                                ->where('syid',$syid)
                                ->where('sectionid',$sectionid)
                                ->where('subjid',$subjid)
                                ->where('quarter',$x)
                                ->where('semid',$semid)
                                ->where('deleted',0)
                                ->get();
        
                    if(count($check) == 0){
                        DB::table('grades')
                                ->insertGetId([
                                    'syid' => $syid,
                                    'levelid' => $levelid,
                                    'sectionid' => $sectionid,
                                    'subjid' => $subjid,
                                    'quarter' => $x,
                                    'deleted' => 0,
                                    'semid'=>1,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                ]);
                    }
                }
            }else{
                for($x = 3; $x <= 4; $x ++){
                    $check = DB::table('grades')
                                ->where('levelid',$levelid)
                                ->where('syid',$syid)
                                ->where('sectionid',$sectionid)
                                ->where('subjid',$subjid)
                                ->where('quarter',$x)
                                ->where('semid',$semid)
                                ->where('deleted',0)
                                ->get();
        
                    if(count($check) == 0){
                        DB::table('grades')
                                ->insertGetId([
                                    'syid' => $syid,
                                    'levelid' => $levelid,
                                    'sectionid' => $sectionid,
                                    'subjid' => $subjid,
                                    'quarter' => $x,
                                    'deleted' => 0,
                                    'semid'=>2,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                ]);
                    }
                }
            }

        }else{
            for($x = 1; $x <= 4; $x ++){
                $check = DB::table('grades')
                            ->where('levelid',$levelid)
                            ->where('syid',$syid)
                            ->where('sectionid',$sectionid)
                            ->where('subjid',$subjid)
                            ->where('quarter',$x)
                            ->where('deleted',0)
                            ->get();
    
                if(count($check) == 0){
                    DB::table('grades')
                        ->insertGetId([
                            'syid' => $syid,
                            'levelid' => $levelid,
                            'sectionid' => $sectionid,
                            'subjid' => $subjid,
                            'quarter' => $x,
                            'deleted' => 0,
                            'semid'=>1,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
                }
            }
        }

        $gradestatus = DB::table('grades')
                        ->where('levelid',$levelid)
                        ->where('syid',$syid)
                        ->where('sectionid',$sectionid)
                        ->where('subjid',$subjid)
                        ->where('deleted',0)
                        ->select(
                            'date_submitted',
                            'submitted',
                            'status',
                            'quarter',
                            'id'
                        )
                        ->get();

        foreach($gradestatus as $item){
            $item->date_submitted = \Carbon\Carbon::create($item->date_submitted)->isoFormat('MMM DD, YYYY hh:mm A');
        }

        return $gradestatus;

    }

    public static function teachingload(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->first()->id;

        $subject = array();

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
					'sectionid'
                )
                ->get();
				
		$sectstrand = DB::table('sh_sectionblockassignment')
                ->join('sh_block',function($join){
                    $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                    $join->where('sh_block.deleted',0);
                })
                ->where('syid',$syid)
                ->where('sh_sectionblockassignment.deleted',0)
                ->select(
                    'sh_sectionblockassignment.sectionid',
                    'sh_block.strandid'
                    
                )
                ->get();

        foreach($sched as $item){
			
			$sectionstrand = collect($sectstrand)
                                ->where('sectionid',$item->sectionid)
                                ->values();
        
            $check_if_exist_in_plot = DB::table('subject_plot')
                                            ->where('deleted',0)
                                            ->where('levelid',$item->levelid)
                                            ->where('subjid',$item->subjid)
                                            ->where('syid',$syid)
											->where('semid',$item->semid)
                                            ->whereIn('strandid',collect($sectionstrand)->pluck('strandid'))
                                            ->count();

            if($check_if_exist_in_plot > 0){
                    array_push($subject, $item);
            }      

        }

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

        foreach($subject as $item){

            // 11132021 - grades
            $item->with_pending = false;
            $quarter_pending = array();
            $quarter_pending_perst = array();

            for($x = 1; $x<=4; $x++){

                $grades = DB::table('grades')
                        ->where('levelid',$item->levelid)
                        ->where('sectionid',$item->sectionid)
                        ->where('syid',$syid)
                        ->where('quarter',$x)
                        ->where('subjid',$item->subjid)
                        ->where('deleted',0)
                        ->where('status',3)
                        ->count();

                if($grades > 0){
                    $item->with_pending = true;
                    array_push($quarter_pending,$x);
                }else{

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

                    $grades = DB::table('grades')
                                ->where('levelid',$item->levelid)
                                ->where('sectionid',$item->sectionid)
                                ->where('syid',$syid)
                                ->where('quarter',$x)
                                ->where('subjid',$item->subjid)
                                ->where('deleted',0)
                                ->join('gradesdetail',function($join) use($teacherid, $enrolledstud){
                                    $join->on('grades.id','=','gradesdetail.headerid');
                                    $join->where('gradesdetail.gdstatus',3);
                                    $join->whereNotNull('gradesdetail.studid');
                                    $join->whereIn('gradesdetail.studid',collect($enrolledstud)->pluck('studid'));
                                })
                                ->count();

                    if($grades > 0){
                        $item->with_pending = true;
                        array_push($quarter_pending_perst,$x);
                    }

                }

            }

            if($item->levelid == 14 || $item->levelid == 15){
                $item->optiondisplay = $item->subjdesc.' '.'<i class="badge badge-primary">'.$item->subjcode.'</i>';
            }else{
                $item->optiondisplay = $item->subjdesc;
            }

            $item->quarter_pending_perst = $quarter_pending_perst;
            $item->quarter_pending = $quarter_pending;
            
           
            //11132021 - grades
        }

        return collect($subject)->sortBy('subjdesc')->values();


    }

    public static function enrolled_learners(Request $request){


        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $levelid = $request->get('levelid');
        $quarter = $request->get('quarter');
        $subjid = $request->get('subjid');

        if($levelid == 14 || $levelid == 15){

            $strand = array();
            $subjstrand = DB::table('subject_plot')
                            ->where('syid',$syid)
                            ->where('semid',$semid)
                            ->where('levelid',$levelid)
                            ->where('subjid',$subjid)
                            ->where('deleted',0)
                            ->get();

            foreach($subjstrand as $stranditem){
                array_push($strand, $stranditem->strandid);
            }

            $temp_students = array();

            $enrolledstud = DB::table('sh_enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                                ->join('studentstatus',function($join){
                                    $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                                })
                              ->where('sh_enrolledstud.sectionid',$sectionid)
                              ->where('sh_enrolledstud.levelid',$levelid)
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.deleted',0)
                              //->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                              ->where('sh_enrolledstud.studstatus','!=',0)
                              ->whereIn('sh_enrolledstud.strandid',$strand)
                              ->orderBy('gender','desc')
                                    ->orderBy('lastname')
                                    ->distinct('studid')
                                    ->select(
                                          'studid',
                                          'firstname',
                                          'lastname',
                                          'middlename',
                                          'suffix',
                                          'mol',
                                          'gender',
                                          'sid',
                                          'description',
                                          'sh_enrolledstud.studstatus',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
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
                        //$join->whereIn('sh_enrolledstud.studstatus',[1,2,4]);
                        $join->where('sh_enrolledstud.studstatus','!=',0);
                        $join->where('sh_enrolledstud.deleted',0);
                        $join->where('sh_enrolledstud.syid',$syid);
                        $join->where('sh_enrolledstud.semid',$semid);
                    })
                    ->join('studentstatus',function($join){
                        $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                    })
                    ->join('sh_strand',function($join){
                        $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                        $join->where('sh_strand.deleted',0);
                    })
                    ->where('student_specsubj.status','ADDITIONAL')
                    ->where('student_specsubj.syid',$syid)
                    ->where('student_specsubj.semid',$semid)
                    ->where('student_specsubj.sectionid',$sectionid)
                    ->where('student_specsubj.subjid',$subjid)
                    ->where('student_specsubj.deleted',0)
                    ->select(
                        'student_specsubj.studid',
                        'firstname',
                        'lastname',
                        'middlename',
                        'suffix',
                        'mol',
                        'gender',
                        'sid',
                        'description',
                        'sh_enrolledstud.studstatus',
                        DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                    )
                    ->get();

            foreach($enrolledstud as $item){
                array_push($temp_students,$item);
            }

           

            $temp_students = collect($temp_students)->unique('studid')->values();
            // $enrolledstud = collect($temp_students)->sortBy('studentname')->sortBy('gender')->values();

            $enrolledstud = collect($temp_students)->sortBy(function($item) {
                $gsort = $item->gender == 'MALE' ? 0 : 1;
                return $gsort.'-'.$item->studentname;
            })->values();
            

            if($semid == 1){
                $header = DB::table('grades')
                            ->where('levelid',$levelid)
                            ->where('syid',$syid)
                            ->where('sectionid',$sectionid)
                            ->where('subjid',$subjid)
                            ->where('semid',$semid)
                            ->where('deleted',0)
                            ->get();
            }else{
                $header = DB::table('grades')
                            ->where('levelid',$levelid)
                            ->where('syid',$syid)
                            ->where('sectionid',$sectionid)
                            ->where('subjid',$subjid)
                            ->where('semid',$semid)
                            ->where('deleted',0)
                            ->get();
            }

        }else{

            $temp_students = array();

            $enrolledstud = DB::table('enrolledstud')
                ->join('studinfo',function($join){
                    $join->on('enrolledstud.studid','=','studinfo.id');
                    $join->where('studinfo.deleted',0);
                })
                ->join('studentstatus',function($join){
                    $join->on('enrolledstud.studstatus','=','studentstatus.id');
                })
                ->where('enrolledstud.sectionid',$sectionid)
                ->where('enrolledstud.levelid',$levelid)
                ->where('enrolledstud.syid',$syid)
                ->where('enrolledstud.deleted',0)
                // ->whereIn('enrolledstud.studstatus',[1,2,4])
                ->where('enrolledstud.studstatus','!=',0)
                ->orderBy('gender','desc')
                    ->orderBy('lastname')
                    ->distinct('studid')
                    ->select(
                            'studid',
                            'firstname',
                            'lastname',
                            'middlename',
                            'suffix',
                            'mol',
                            'gender',
                            'sid',
                            'description',
                            'enrolledstud.studstatus',
                            DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                    )
                    ->get();

            foreach($enrolledstud as $item){
                array_push($temp_students,$item);
            }

            


            // $enrolledstud = DB::table('student_specsubj')
            //         ->join('studinfo',function($join){
            //             $join->on('student_specsubj.studid','=','studinfo.id');
            //             $join->where('studinfo.deleted',0);
            //         })
            //         ->join('enrolledstud',function($join) use($syid){
            //             $join->on('student_specsubj.studid','=','enrolledstud.studid');
            //             $join->whereIn('enrolledstud.studstatus',[1,2,4]);
            //             $join->where('enrolledstud.deleted',0);
            //             $join->where('enrolledstud.syid',$syid);
            //         })
            //         ->where('student_specsubj.status','ADDITIONAL')
            //         ->where('student_specsubj.syid',$syid)
            //         ->where('student_specsubj.deleted',0)
            //         ->where('student_specsubj.sectionid',$sectionid)
            //         ->where('student_specsubj.subjid',$subjid)
            //         ->select(
            //             'student_specsubj.studid',
            //             'firstname',
            //             'lastname',
            //             'middlename',
            //             'suffix',
            //             'mol',
            //             'gender',
            //             'sid',
            //             DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
            //         )
            //         ->get();

            foreach($enrolledstud as $item){
                array_push($temp_students,$item);
            }

            $check_subject = DB::table('subjects')
                    ->where('deleted',0)
                    ->where('id',$subjid)
                    ->first();
                    
            if($check_subject->isSP == 1){
                
                $temp_students = array();
                
                $enrolledstud = DB::table('enrolledstud')
                                    ->join('studinfo',function($join){
                                        $join->on('enrolledstud.studid','=','studinfo.id');
                                        $join->where('studinfo.deleted',0);
                                    })
                                    ->join('studentstatus',function($join){
                                        $join->on('enrolledstud.studstatus','=','studentstatus.id');
                                    })
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
                                    // ->whereIn('enrolledstud.studstatus',[1,2,4])
                                    ->where('enrolledstud.studstatus','!=',0)
                                    ->orderBy('gender','desc')
                                    ->orderBy('lastname')
                                    ->select(
                                        'subjects_studspec.studid',
                                        'firstname',
                                        'lastname',
                                        'middlename',
                                        'suffix',
                                        'mol',
                                        'gender',
                                        'sid',
                                        'description',
                                        'enrolledstud.studstatus',
                                        DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                    )
                                    // ->orderBy('gender','desc')
                                    // ->orderBy('studentname','asc')
                                    ->get();
                                    
                foreach($enrolledstud as $item){
                    array_push($temp_students,$item);
                }
            }

            $temp_students = collect($temp_students)->unique('studid')->values();
            // $enrolledstud = collect($temp_students)->sortBy('studentname')->sortBy('gender')->values();

            $enrolledstud = collect($temp_students)->sortBy(function($item) {
                $gsort = $item->gender == 'MALE' ? 0 : 1;
                return $gsort.'-'.$item->studentname;
            })->values();

            $header = DB::table('grades')
                    ->where('levelid',$levelid)
                    ->where('syid',$syid)
                    ->where('sectionid',$sectionid)
                    ->where('subjid',$subjid)
                    ->where('deleted',0)
                    ->select(
                        'id',
                        'quarter',
                        'submitted',
                        'status'
                    )
                    ->get();

        }

       
        $student_quarter = DB::table('student_quarter')
                            ->where('syid',$syid)
                            ->whereIn('studid',collect($enrolledstud)->whereNotIn('studstatus',[1,2,4])->pluck('studid'))
                            ->get();

        $header_array = array();

        foreach($header as $item){
            array_push($header_array,$item->id);
        }

        $grades = DB::table('gradesdetail')
                ->whereIn('headerid',$header_array)
                ->select(
                    'studid',
                    'qg',
                    'headerid',
                    'id',
                    'gdstatus'
                )
                ->get();

        // return $grades;

        $check_subject = DB::table('subjects')
                            ->where('deleted',0)
                            ->where('id',$subjid)
                            ->first();

        foreach($enrolledstud as $item){

            $temp_middle = '';
            if(strlen($item->middlename) > 0){
                $temp_middle = $item->middlename[0].'.';
            }
            $item->middle = $temp_middle;
            $item->student = $item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;

            $item->qgrade1 = null;
            $item->qgrade2 = null;
            $item->qgrade3 = null;
            $item->qgrade4 = null;

            $item->q1 = 1;
            $item->q2 = 1;
            $item->q3 = 1;
            $item->q4 = 1;

           

            $item->qheader1 = null;
            $item->qheader2 = null;
            $item->qheader3 = null;
            $item->qheader4 = null;

            $item->gstatus1 = 0;
            $item->gstatus2 = 0;
            $item->gstatus3 = 0;
            $item->gstatus4 = 0;

            $item->qid1 = null;
            $item->qid2 = null;
            $item->qid3 = null;
            $item->qid4 = null;

            $item->gdcolor1 = '';
            $item->gdcolor2 = '';
            $item->gdcolor3 = '';
            $item->gdcolor4 = '';

            if($levelid != 14 || $levelid != 15){
                if( isset($check_subject->isSP)){
                    if($check_subject->isSP == 1){

                        $check_stud_spec = DB::table('subjects_studspec')
                                                ->where('syid',$syid)
                                                ->where('deleted',0)
                                                ->where('studid',$item->studid)
                                                ->first();

                        if(isset($check_stud_spec->id)){
                            $item->q1 = $check_stud_spec->q1;
                            $item->q2 = $check_stud_spec->q2;
                            $item->q3 = $check_stud_spec->q3;
                            $item->q4 = $check_stud_spec->q4;
                        }
                    }
                }
            }

            $with_fg = true;

            foreach($header as $header_item){

                $quarter = $header_item->quarter;
                $string_grade = 'qgrade'.$quarter;
                $string_header = 'qheader'.$quarter;
                $string_status = 'gstatus'.$quarter;
                $string_color = 'gdcolor'.$quarter;
                $string_id = 'qid'.$quarter;

                $item->$string_header = $header_item->id;

                $check = collect($grades)->where('headerid',$header_item->id)->where('studid',$item->studid)->first();

                if(isset($check->studid)){
                    $item->$string_grade = $check->qg;
                    $item->$string_id = $check->id;
                    $item->$string_status = $check->gdstatus;
                }else{
                    
                    $temp_gradesdetail_id = DB::table('gradesdetail')
                            ->insertGetId([
                                'studid'=>$item->studid,
                                'headerid'=>$header_item->id,
                                'qg'=>null,
                                'gdstatus'=>0,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);

                    $item->$string_grade = null;
                    $item->$string_id = $temp_gradesdetail_id;
                    $item->$string_status = 0;

                    $with_fg = false;
                }

                if($item->$string_status == 2){
                    $item->$string_color = 'bg-primary';
                }elseif($item->$string_status == 4){
                    $item->$string_color = 'bg-info';
                }elseif($item->$string_status == 1){
                    $item->$string_color = 'bg-success';
                }

            }

            $check_student_quarters = collect($student_quarter)
                                        ->where('studid',$item->studid)
                                        ->values();

            foreach($check_student_quarters as $check_student_quarter){
                $item->q1 = $check_student_quarter->quarter1;
                $item->q2 = $check_student_quarter->quarter2;
                $item->q3 = $check_student_quarter->quarter3;
                $item->q4 = $check_student_quarter->quarter4;;
            }

        }
        
        return $enrolledstud;

    }

    public static function save_grades(Request $request){

        $id = $request->get('id');
        $headerid = $request->get('headerid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $levelid = $request->get('levelid');
        $quarter = $request->get('quarter');
        $subjid = $request->get('subjid');
        $studid = $request->get('studid');
        $qg = $request->get('qg');
        
        if($id != null){
           
            $check = DB::table('gradesdetail')
                        ->where('id',$id)
                        ->where('headerid',$headerid)
                        ->where('studid',$studid)
                        ->select('qg','studid')
                        ->first();



            if(isset($check->studid)){

                DB::table('gradesdetail')
                        ->where('id',$id)
                        ->take(1)
                        ->update([
                            'qg'=>$qg,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            }

        }else{

            $check = DB::table('gradesdetail')
                            ->where('headerid',$headerid)
                            ->where('studid',$studid)
                            ->select('qg','studid')
                            ->first();

            if(isset($check->studid)){

                DB::table('gradesdetail')
                        ->where('id',$check->id)
                        ->take(1)
                        ->update([
                            'qg'=>$qg,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            }else{

                DB::table('gradesdetail')
                    ->insert([
                        'studid'=>$studid,
                        'headerid'=>$headerid,
                        'qg'=>$qg,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            }
            
        }

        return "done";

        

    }

    

}


     