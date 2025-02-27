<?php

namespace App\Models\CollegeInstructor;

use Illuminate\Database\Eloquent\Model;
use DB;

class CollegeInstructorData extends Model
{
    public static function get_assigned_subj(
        $syid = null,
        $semid = null,
        $teacherid = null,
        $sectionid = null
    ){  
        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
        }
        if($semid == null){
            $semid = DB::table('semester')->where('isactive',1)->first()->id;
        }
        $schedule = DB::table('college_classsched')
                        ->where('college_classsched.syID',$syid)
                        ->where('college_classsched.semesterID',$semid)
                        ->where('college_classsched.deleted',0);
        if($teacherid != null){
            $schedule = $schedule->where('teacherID',$teacherid);
        }
        if($sectionid != null){
            $schedule = $schedule->where('sectionID',$sectionid);
        }
        $schedule = $schedule->join('college_sections',function($join){
                                    $join->on('college_classsched.sectionID','=','college_sections.id');
                                    $join->where('college_sections.deleted',0);
                                })
                                ->join('college_prospectus',function($join){
                                    $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                    $join->where('college_prospectus.deleted',0);
                                })
                                ->leftJoin('college_grade_status',function($join){
                                    $join->on('college_classsched.id','=','college_grade_status.schedid');
                                    $join->where('college_grade_status.deleted',0);
                                })
                                ->select(
                                    'sectionDesc',
                                    'subjCode',
                                    'subjDesc',
                                    'prelimstatus',
                                    'midtermstatus',
                                    'prefistatus',
                                    'finalstatus',
                                    'college_classsched.subjectID',
                                    'sectionID'
                                );

        return $schedule->get();
                   
    }

    public static function subject_students(
        $syid = null,
        $semid = null,
        $sectionid = null,
        $subjid = null
    ){  

        if($syid == null){
            $syid = DB::table('syid')->where('isactive',1)->first()->id;
        }
        if($semid == null){
            $semid = DB::table('semester')->where('isactive',1)->first()->id;
        }

        $students = DB::table('college_enrolledstud')
                            ->where('college_enrolledstud.deleted',0)
                            ->whereIn('college_enrolledstud.studstatus',[1,2,4]);
      
        if($sectionid != null){
            $students = $students->where('college_enrolledstud.sectionID',$sectionid);
        }

        $students = $students->join('studinfo',function($join){
                                    $join->on('college_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                                })
                                ->join('college_studsched',function($join){
                                    $join->on('college_enrolledstud.studid','=','college_studsched.studid');
                                    $join->where('college_studsched.deleted',0);
                                })
                                ->join('college_classsched',function($join) use($subjid){
                                    $join->on('college_studsched.schedid','=','college_classsched.id');
                                    $join->where('college_classsched.deleted',0);
                                    if($subjid != null){
                                        $join->where('college_classsched.subjectID',$subjid);
                                    }
                                })
                                ->select('lastname','firstname','gender','studinfo.id')
                                ->orderBy('gender')
                                ->orderBy('lastname')
                                ->orderBy('firstname')
                                ->distinct('studinfo.id')
                                ;

        return $students->get();
                   
    }

    public static function subject_student_grades(
        $syid = null,
        $semid = null,
        $sectionid = null,
        $subjid = null
    ){  

        if($syid == null){
            $syid = DB::table('syid')->where('isactive',1)->first()->id;
        }
        if($semid == null){
            $semid = DB::table('semester')->where('isactive',1)->first()->id;
        }

        if($subjid == null){

            return array((object)[
                'status'=>0,
                'data'=>'Subject is required'
            ]);

        }

        return DB::table('college_classsched')
                ->where('college_classsched.deleted',0)
                ->where('college_classsched.subjectID',$subjid)
                ->where('college_classsched.sectionid',$sectionid)
                ->where('college_classsched.syID',$syid)
                ->where('college_classsched.semesterID',$semid)
                ->join('college_studsched',function($join){
                    $join->on('college_classsched.id','=','college_studsched.schedid');
                    $join->where('college_studsched.deleted',0);
                })
                ->join('studinfo',function($join) use($subjid){
                    $join->on('college_studsched.studid','=','studinfo.id');
                    $join->where('studinfo.deleted',0);
                })
                ->join('college_enrolledstud',function($join) use($syid, $semid){
                    $join->on('studinfo.id','=','college_enrolledstud.studid');
                    $join->where('college_enrolledstud.deleted',0);
                    $join->whereIn('college_enrolledstud.studstatus',[1,2,4]);
                    $join->where('college_enrolledstud.syid',$syid);
                    $join->where('college_enrolledstud.semid',$semid);
                })
                ->leftJoin('college_studentprospectus',function($join) use($subjid){
                    $join->on('studinfo.id','=','college_studentprospectus.studid');
                    $join->where('college_studentprospectus.deleted',0);
                    $join->where('college_studentprospectus.prospectusID',$subjid);
                })
                // ->where('studinfo.id',2879)
                ->select(
                    'lastname',
                    'firstname',
                    'gender',
                    'studinfo.id',
                    'prelemgrade',
                    'midtermgrade',
                    'prefigrade',
                    'finalgrade',
                    'college_studentprospectus.remarks'
                )
                ->orderBy('gender')
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->distinct('studinfo.id')
                ->get();

        $students = DB::table('college_enrolledstud')
                            ->where('college_enrolledstud.deleted',0)
                            ->whereIn('college_enrolledstud.studstatus',[1,2,4]);
      
        // if($sectionid != null){
        //     $students = $students->where('college_enrolledstud.sectionID',$sectionid);
        // }

        $students = $students->join('studinfo',function($join){
                                    $join->on('college_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                                })
                                ->join('college_studsched',function($join){
                                    $join->on('college_enrolledstud.studid','=','college_studsched.studid');
                                    $join->where('college_studsched.deleted',0);
                                })
                                ->join('college_classsched',function($join) use($subjid, $sectionid){
                                    $join->on('college_studsched.schedid','=','college_classsched.id');
                                    $join->where('college_classsched.deleted',0);
                                    $join->where('college_classsched.subjectID',$subjid);
                                    $join->where('college_classsched.sectionid',$sectionid);
                                })
                                ->leftJoin('college_studentprospectus',function($join) use($subjid){
                                    $join->on('studinfo.id','=','college_studentprospectus.studid');
                                    $join->where('college_studentprospectus.deleted',0);
                                    $join->where('college_studentprospectus.prospectusID',$subjid);
                                })
                                ->where('college_classsched.syID',$syid)
                                ->where('college_classsched.semesterID',$semid)
                                ->select(
                                    'lastname',
                                    'firstname',
                                    'gender',
                                    'studinfo.id',
                                    'prelemgrade',
                                    'midtermgrade',
                                    'prefigrade',
                                    'finalgrade',
                                    'college_studentprospectus.remarks'
                                )
                                ->orderBy('gender')
                                ->orderBy('lastname')
                                ->orderBy('firstname')
                                ->distinct('studinfo.id');

        return $students->get();
                   
    }

    public static function save_student_grade(
        $syid = null,
        $semid = null,
        $subjid = null,
        $grade = null,
        $field = null,
        $studid = null
    ){

        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;
        }
        if($semid == null){
            $semid = DB::table('semester')->where('isactive',1)->select('id')->first()->id;
        }

        $get_record = DB::table('college_studentprospectus')
                        ->where('prospectusID',$subjid)
                        ->where('studid',$studid)
                        ->where('deleted',0)
                        ->first();

        $status_field = null;
        if($field == 'prelemgrade'){
            $status_field = 'prelemstatus';
        }
        elseif($field == 'midtermgrade'){
            $status_field = 'midtermstatus';
        }
        elseif($field == 'prefigrade'){
            $status_field = 'prefistatus';
        }   
        elseif($field == 'finalgrade'){
            $status_field = 'finalstatus';
        }

        // return $get_record->id;

        if(isset($get_record->studid)){
            if($status_field != null){
                if($get_record->$status_field == 0 || $get_record->$status_field == 4){
                    DB::table('college_studentprospectus')
                        ->where('studid',$studid)
                        ->where('prospectusID',$subjid)
                        ->where('deleted',0)
                        ->update([
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                            $field=>$grade
                        ]);
    
                    return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully'
                    ]);
                }
            }else{
                DB::table('college_studentprospectus')
                    ->where('studid',$studid)
                    ->where('prospectusID',$subjid)
                    ->where('deleted',0)
                    ->update([
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        $field=>$grade
                    ]);

                return array((object)[
                    'status'=>1,
                    'data'=>'Updated Successfully'
                ]);
            }
        }else{
            DB::table('college_studentprospectus')
                        ->updateOrInsert(
                            [
                                'studid'=>$studid,
                                'prospectusID'=>$subjid,
                                'syid'=>$syid,
                                'semid'=>$semid
                            ],
                            [
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                $field=>$grade
                            ]);

            return array((object)[
                'status'=>1,
                'data'=>'Created Successfully'
            ]);
        }

    }

    public static function get_grade_status(
        $syid = null,
        $semid = null,
        $sectionid = null,
        $subjid = null,
        $teacherid = null
    ){

        if($syid == null){
            $syid = DB::table('syid')->where('isactive',1)->first()->id;
        }

        if($semid == null){
            $semid = DB::table('semester')->where('isactive',1)->first()->id;
        }

        $grade_status = DB::table('college_classsched')
                            ->leftJoin('college_grade_status',function($join){
                                $join->on('college_classsched.id','=','college_grade_status.schedid');
                                $join->where('college_grade_status.deleted',0);
                            })
                            ->where('college_classsched.deleted',0)
                            ->where('syID',$syid)
                            ->where('semesterID',$semid)
                            ->where('sectionID',$sectionid)
                            ->where('subjectID',$subjid)
                            // ->where('teacherID',$teacherid)
                            ->select(
                                'college_grade_status.id as statid',
                                'prelimstatus',
                                'midtermstatus',
                                'prefistatus',
                                'finalstatus'
                            )
                            ->first();

        if(!isset($grade_status->statid)){

            $class_sched = DB::table('college_classsched')
                                ->where('college_classsched.deleted',0)
                                ->where('syID',$syid)
                                ->where('semesterID',$semid)
                                ->where('sectionID',$sectionid)
                                ->where('subjectID',$subjid);

            if($teacherid != null){

                $class_sched = $class_sched->where('teacherID',$teacherid);

            }

            $class_sched = $class_sched->select('id')->first();             

            if(isset($class_sched->id)){

                $insertId = DB::table('college_grade_status')
                        ->insertGetId([
                            'schedid'=>$class_sched->id,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        ]);

                $new_status = DB::table('college_grade_status')
                                    ->where('id',$insertId)
                                    ->select(
                                        'college_grade_status.id as statid',
                                        'prelimstatus',
                                        'midtermstatus',
                                        'prefistatus',
                                        'finalstatus'
                                    )
                                    ->get();

                return  $new_status;

            }
                                

        }
        else{

            return array($grade_status);

        }

       

        
    }

    public static function get_schedule($syid = null, $semid = null, $teacherid = null, $filter = null){

        $classsched = DB::table('college_classsched')
                        ->join('college_sections',function($join){
                            $join->on('college_classsched.sectionID','=','college_sections.id');
                            $join->where('college_sections.deleted',0);
                        }) 
                        ->join('college_courses',function($join){
                            $join->on('college_sections.courseID','=','college_courses.id');
                            $join->where('college_courses.deleted',0);
                        })
                        ->join('college_prospectus',function($join){
                            $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            $join->where('college_prospectus.deleted',0);
                        })
                        ->leftJoin('college_scheddetail',function($join){
                            $join->on('college_classsched.id','=','college_scheddetail.headerid');
                            $join->where('college_scheddetail.deleted',0);
                        })
                        ->leftJoin('days',function($join){
                            $join->on('college_scheddetail.day','=','days.id');
                        })
                        ->where('college_classsched.deleted',0)
                        ->where('college_classsched.teacherid',$teacherid)
                        ->where('college_classsched.syid',$syid)
                        ->where('college_classsched.semesterid',$semid)
                        ->orderBy('days.id')
                        ->orderBy('stime')
                     
                        ->select('sectionDesc',
                            'courseabrv',
                            'stime',
                            'etime',
                            'scheddetialclass',
                            'day',
                            'college_classsched.subjectID',
                            'subjDesc',
                            'subjcode',
                            'description',
                            'sectionID'
                        )
                        ->get();

        foreach($classsched as $item){

            if($item->stime != null && $item->etime != NULL){
                $item->ftime = \Carbon\Carbon::create($item->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::create($item->etime)->isoFormat('hh:mm A');
            }
            else{
                $item->ftime = 'NOT ASSIGNED';
                $item->day = 'NOT ASSIGNED';
            }

            
        }

        $all_classsched = array();
        
        // return $classsched;

        if($filter == 1){
            $classsched = collect($classsched)->groupBy('ftime');

            foreach($classsched as $item){

                $by_subject = collect($item)->groupBy('subjcode');
                $sections = array();

                foreach($by_subject as $by_subject_item1){

                    $day = '';

                    foreach($by_subject_item1 as $by_subject_item2){

                        array_push($sections,(object)[
                            'section_course'=>$by_subject_item2->courseabrv . ' - ' .  $by_subject_item2->sectionDesc
                        ]);

                        $time = $by_subject_item2->ftime;
                        $subject =  $by_subject_item2->subjcode .' - '.$by_subject_item2->subjDesc;
                        if($by_subject_item2->description == 'Thursday'){
                            $day .= substr($by_subject_item2->description, 0 , 1).'h';
                        }
                        elseif($by_subject_item2->description == 'Sunday'){
                            $day .= substr($by_subject_item2->description, 0 , 1).'un';
                        }
                        else{
                            $day .= substr($by_subject_item2->description, 0 , 1).'';
                        }
                    };

                }

                array_push($all_classsched, (object)[
                                'time'=>array((object)['time'=>$time]),
                                'day'=>array((object)['day'=>$day]),
                                'subject'=>array((object)['subject'=>$subject]),
                                'sections'=>collect($sections)->unique('section_course')
                            ]);
            }


        }elseif($filter == 2){

            $classsched = collect($classsched)->groupBy('day');
            // return $classsched;
            foreach($classsched as $item){

                $time = array();
                $subject = array();
                $sections = array();


                $by_time = collect($item)->groupBy('ftime');

              

                foreach($by_time as $key=>$by_time1){


                    array_push($time,(object)['time'=>$key]);

                    $section_desc = '';
                    $subj_desc= '';

                    foreach($by_time1 as $by_time2){

                        $subj_desc = $by_time2->subjDesc;

                        $section_desc .= $by_time2->sectionDesc . ' / ';

                        $day = '';

                        if($by_time2->description == 'Thursday'){
                            $day .= substr($by_time2->description, 0 , 1).'h';
                        }
                        elseif($by_time2->description == 'Sunday'){
                            $day .= substr($by_time2->description, 0 , 1).'un';
                        }
                        else{
                            $day .= substr($by_time2->description, 0 , 1).'';
                        }
                        

                    }
                    array_push($subject,(object)['subject'=>$subj_desc]);
                    
                    array_push($sections,(object)['section_course'=>$section_desc]);
                   

                   
                }

                

                array_push($all_classsched, (object)[
                    'time'=>collect($time)->unique('time'),
                    'day'=>array((object)['day'=>$day]),
                    'subject'=>$subject,
                    'sections'=>$sections
                ]);
    

            }

           

           
           

        }
       

        return $all_classsched;

    }



}
