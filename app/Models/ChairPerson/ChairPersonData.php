<?php

namespace App\Models\ChairPerson;

use Illuminate\Database\Eloquent\Model;
use DB;

class ChairPersonData extends Model
{
    public static function section_subject(
        $syid = null,
        $semid = null,
        $teacherid = null
    ){  
        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
        }
        if($semid == null){
            $semid = DB::table('semester')->where('isactive',1)->first()->id;
        }

        if($teacherid == null){
            $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
        }

        $subject = DB::table('college_courses')
                        ->where('courseChairman',$teacherid)
                        ->where('college_courses.deleted',0)
                        ->join('college_sections',function($join){
                            $join->on('college_courses.id','=','college_sections.courseID');
                            $join->where('college_sections.deleted',0);
                        })
                        ->join('college_classsched',function($join) use($syid,$semid){
                            $join->on('college_classsched.sectionID','=','college_sections.id');
                            $join->where('college_classsched.deleted',0);
                            $join->where('college_classsched.syID',$syid);
                            $join->where('college_classsched.semesterID',$semid);
                        })
                        ->join('college_prospectus',function($join){
                            $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            $join->where('college_prospectus.deleted',0);
                        })
                        ->leftJoin('college_grade_status',function($join){
                            $join->on('college_classsched.id','=','college_grade_status.schedid');
                            $join->where('college_grade_status.deleted',0);
                        })
                        ->join('teacher',function($join){
                            $join->on('college_classsched.teacherid','=','teacher.id');
                            $join->where('teacher.deleted',0);
                        })
                        ->select(
                            'subjCode',
                            'lastname',
                            'firstname',
                            'sectionDesc',
                            'subjDesc',
                            'college_classsched.id',
                            'prelimstatus',
                            'midtermstatus',
                            'prefistatus',
                            'finalstatus',
                            'college_classsched.sectionID',
                            'college_classsched.subjectID'
                        )
                        ->get();

     
        return $subject;
                   
    }

    public static function chairperson_sections(
        $syid = null,
        $semid = null,
        $courseid = null
    ){

        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
        }
        if($semid == null){
            $semid = DB::table('semester')->where('isactive',1)->first()->id;
        }

        $teacherid = DB::table('teacher')
                        ->join('college_courses',function($join){
                            $join->on('teacher.id','=','college_courses.courseChairman');
                            $join->where('college_courses.deleted',0);
                        })
                        ->where('teacher.deleted',0)
                        ->where('userid',auth()->user()->id)
                        ->select('college_courses.id')
                        ->get();

        $courses = array();

        foreach($teacherid as $item){
            array_push($courses,$item->id);
        }

        $sections = DB::table('college_sections')
                        ->join('college_courses',function($join){
                            $join->on('college_sections.courseID','=','college_courses.id');
                            $join->where('college_courses.deleted',0);
                        })
                        ->leftJoin('gradelevel',function($join){
                            $join->on('college_sections.yearID','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                        })
                        ->leftJoin('college_curriculum',function($join){
                            $join->on('college_sections.curriculumid','=','college_curriculum.id');
                            $join->where('college_curriculum.deleted',0);
                        })
                        ->where('syID',$syid)
                        ->where('semesterID',$semid)
                        ->where('college_sections.deleted',0)
                        ->whereIn('college_sections.courseID',$courses)
                        ->select(
                            'sectionDesc',
                            'college_sections.id',
                            'college_sections.courseID',
                            'courseabrv',
                            'curriculumname',
                            'levelname',
                            'yearID',
                            'curriculumid'
                        )
                        ->get();

        foreach($sections as $item){

            $enrolled_count = DB::table('college_enrolledstud')
                                ->where('sectionid',$item->id)
                                ->whereIn('studstatus',[1,3,4])
                                ->where('college_enrolledstud.deleted',0)
                                ->where('syid',$syid)
                                ->where('semid',$semid)
                                ->count();

            $item->enrolled_stud =  $enrolled_count;
        }

        return $sections;

    }

    public static function chairperson_courses($teacherid = null){

        if($teacherid == null){
            $teacherid = DB::table('teacher')
                            ->where('deleted',0)
                            ->where('userid',auth()->user()->id)
                            ->select('id')
                            ->first()
                            ->id;
        }

        $courses = DB::table('teacher')
                            ->join('college_courses',function($join){
                                $join->on('teacher.id','=','college_courses.courseChairman');
                                $join->where('college_courses.deleted',0);
                            })
                            ->where('teacher.deleted',0)
                            ->where('teacher.id',$teacherid)
                            ->select('college_courses.id','courseDesc','courseabrv')
                            ->get();

        return $courses;
        
    }


    public static function get_curriculum($teacherid = null){

        if($teacherid == null){

            $teacherid = DB::table('teacher')
                            ->where('deleted',0)
                            ->where('userid',auth()->user()->id)
                            ->select('id')
                            ->first()
                            ->id;
        }

        $teacherid = DB::table('teacher')
                        ->join('college_courses',function($join){
                            $join->on('teacher.id','=','college_courses.courseChairman');
                            $join->where('college_courses.deleted',0);
                        })
                        ->where('teacher.deleted',0)
                        ->where('teacher.id',$teacherid)
                        ->select('college_courses.id')
                        ->get();

        $courses = array();

        foreach($teacherid as $item){
            array_push($courses,$item->id);
        }

        $curriculum = DB::table('college_curriculum')
                            ->where('college_curriculum.deleted',0)
                            ->whereIn('courseID',$courses)
                            ->select('id','courseID','curriculumname','isactive')
                            ->get();

        return $curriculum;
        
    }


    public static function college_subjects($courseid = null , $curriculumid = null, $yearID = null, $semesterID = null){

        $course = DB::table('college_prospectus')
                    ->join('college_curriculum',function($join){
                        $join->on('college_prospectus.curriculumID','=','college_curriculum.id');
                        $join->where('college_curriculum.deleted',0);
                    })
                    ->join('semester',function($join){
                        $join->on('college_prospectus.semesterID','=','semester.id');
                    })
                    ->join('gradelevel',function($join){
                        $join->on('college_prospectus.yearID','=','gradelevel.id');
                        $join->where('gradelevel.deleted',0);
                    })
                    ->where('college_prospectus.deleted',0);

        if($courseid != null){
            $course = $course->where('college_prospectus.courseID',$courseid);
        }
        if($curriculumid != null){
            $course = $course->where('college_prospectus.curriculumID',$curriculumid);
        }
        if($yearID != null){
            $course = $course->where('college_prospectus.yearID',$yearID);
        }
        if($semesterID != null){
            $course = $course->where('college_prospectus.semesterID',$semesterID);
        }



        return $course->select(
                'college_prospectus.id',
                'subjDesc',
                'subjCode',
                'lecunits',
                'labunits',
                'semesterID',
                'yearID',
                'college_prospectus.id',
                'curriculumname',
                'levelname',
                'semester',
                'curriculumid',
                'subjectID'
              )->get();

    }

    public static function college_teacher(){
        $teachers = DB::table('teacher')
                        ->where('usertypeid','18')
                        ->where('deleted',0)
                        ->where('isactive',1)
                        ->select('id','firstname','lastname')
                        ->get();
        $fasprivteachers = DB::table('faspriv')
                            ->join('teacher',function($join){
                                $join->on('teacher.userid','=','faspriv.userid');
                                $join->where('teacher.deleted',0);
                                $join->where('teacher.isactive',1);
                            })
                            ->select('teacher.id','firstname','lastname')
                            ->where('faspriv.usertype',18)
                            ->where('faspriv.deleted',0)
                            ->where('privelege',2)
                            ->get();
        $teacherInfo = array();
        foreach( $teachers as $item){
            array_push($teacherInfo,(object)['id'=>$item->id,'name'=>$item->lastname.', '.$item->firstname]);
            foreach($fasprivteachers as $key=>$new_items){
                if($item->id == $new_items->id){
                    unset($fasprivteachers[$key]);
                }
            }
        }
        foreach( $fasprivteachers as $item){
            
            array_push($teacherInfo,(object)['id'=>$item->id,'name'=>$item->lastname.', '.$item->firstname]);
        }
        return $teacherInfo;
    }


    public static function subject_schedule($syid = null, $semid = nul, $prospectus = null, $sectionid = null, $subjectid = null){

        $subj_sched = DB::table('college_classsched')
                        ->where('college_classsched.deleted',0)
                        ->where('college_classsched.syID',$syid)
                        ->where('college_classsched.semesterID',$semid)
                        ->leftJoin('college_scheddetail',function($join){
                            $join->on('college_classsched.id','=','college_scheddetail.headerid');
                            $join->where('college_scheddetail.deleted',0);
        
                        })
                        ->join('college_sections',function($join){
                            $join->on('college_classsched.sectionid','=','college_sections.id');
                            $join->where('college_sections.deleted','0');
                        })
                       
                        ->join('college_prospectus',function($join){
                            $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            $join->where('college_prospectus.deleted','0');
                        })
                        ->leftJoin('teacher',function($join){
                            $join->on('college_classsched.teacherID','=','teacher.id');
                            $join->where('teacher.deleted','0');
                        })
                        ->leftJoin('rooms',function($join){
                            $join->on('college_scheddetail.roomID','=','rooms.id');
                            $join->where('rooms.deleted','0');
                        })
                        ->leftJoin('days',function($join){
                            $join->on('college_scheddetail.day','=','days.id');
                        })
                        ->select(
                            'days.id as daysort',
                            'days.description',
                            'college_classsched.id',
                            'college_scheddetail.id as schedid',
                            'rooms.roomname',
                            'college_scheddetail.roomid',
                            'college_scheddetail.etime',
                            'college_scheddetail.stime',
                            'teacher.firstname',
                            'teacher.lastname',
                            'college_classsched.subjectUnit',
                            'college_prospectus.subjDesc',
                            'college_prospectus.subjCode',
                            'college_prospectus.lecunits',
                            'college_prospectus.labunits',
                            'college_prospectus.id as subjID',
                            'college_prospectus.subjectID',
                            'college_scheddetail.scheddetialclass',
                            'teacher.firstname',
                            'teacher.lastname',
                            'sectionDesc',
                            'college_classsched.sectionid'
                            )
                        ->orderBy('sectionDesc');
                       

        if($prospectus != null){
            $subj_sched =  $subj_sched->where('college_classsched.subjectID',$prospectus);
        }

        if($subjectid != null){
            $subj_sched =  $subj_sched->where('college_prospectus.subjectID',$subjectid);
        }
        
        if($sectionid != null){
            $subj_sched =  $subj_sched->where('college_classsched.sectionid',$sectionid);
        }

        $subj_sched = $subj_sched->get();
        $classSched = $subj_sched;

        foreach($classSched as $item){
            $item->ftime = $item->stime.' - '.$item->etime;
        }

        $classSched = collect($subj_sched)->groupBy('id');
        $data = array();
        foreach($classSched as $subjitem){
            $byClass = collect($subjitem)->groupBy('scheddetialclass');
         
            foreach($byClass as $item){
                foreach(collect($item)->groupBy('ftime') as $secondItem){
                    $day = '';
                    $temp_sched = collect($secondItem)->sortBy('daysort');
                    $days_list = array();
                    foreach($temp_sched as $thirdItem){
                        $details = $thirdItem;
                        if($thirdItem->description == 'Thursday'){
                            $day .= substr($thirdItem->description, 0 , 1).'h';
                        }
                        elseif($thirdItem->description == 'Sunday'){
                            $day .= substr($thirdItem->description, 0 , 1).'un';
                        }
                        else{
                            $day .= substr($thirdItem->description, 0 , 1).'';
                        }
                        array_push($days_list, $thirdItem->daysort);
                    }
                    $details->description = $day;
                    $details->days_list = $days_list;
                    array_push($data, $details);
                };
            }
        }

        $classSched = collect($data)->groupBy('id');
        $schedules = $data;

        $schedules = collect($schedules)->groupBy('id');
       
        return $schedules;

    }

    public static function get_student_schedule($syid = null, $semid = null , $studid = null){

        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;
        }

        if($semid == null){
            $semid = DB::table('semester')->where('isactive',1)->select('id')->first()->id;
        }
        
        $subj_sched = DB::table('college_studsched')
                                ->where('college_studsched.deleted',0)
                                ->where('college_classsched.syID',$syid)
                                ->where('college_classsched.semesterID',$semid)
                                ->where('college_studsched.studid',$studid)
                                ->join('college_classsched',function($join){
                                    $join->on('college_studsched.schedid','=','college_classsched.id');
                                    $join->where('college_classsched.deleted','0');
                                })
                                ->leftJoin('college_scheddetail',function($join){
                                    $join->on('college_classsched.id','=','college_scheddetail.headerid');
                                    $join->where('college_scheddetail.deleted',0);

                                })
                                ->join('college_sections',function($join){
                                    $join->on('college_classsched.sectionid','=','college_sections.id');
                                    $join->where('college_sections.deleted','0');
                                })
                                ->join('college_prospectus',function($join){
                                    $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                    $join->where('college_prospectus.deleted','0');
                                })
                                ->leftJoin('teacher',function($join){
                                    $join->on('college_classsched.teacherID','=','teacher.id');
                                    $join->where('teacher.deleted','0');
                                })
                                ->leftJoin('rooms',function($join){
                                    $join->on('college_scheddetail.roomID','=','rooms.id');
                                    $join->where('rooms.deleted','0');
                                })
                                ->leftJoin('days',function($join){
                                    $join->on('college_scheddetail.day','=','days.id');
                                })
                                ->select(
                                    'days.id as daysort',
                                    'days.description',
                                    'college_classsched.id',
                                    'college_scheddetail.id as schedid',
                                    'rooms.roomname',
                                    'college_scheddetail.roomid',
                                    'college_scheddetail.etime',
                                    'college_scheddetail.stime',
                                    'teacher.firstname',
                                    'teacher.lastname',
                                    'college_classsched.subjectUnit',
                                    'college_prospectus.subjDesc',
                                    'college_prospectus.subjCode',
                                    'college_prospectus.lecunits',
                                    'college_prospectus.labunits',
                                    'college_prospectus.id as subjID',
                                    'college_prospectus.subjectID',
                                    'college_scheddetail.scheddetialclass',
                                    'teacher.firstname',
                                    'teacher.lastname',
                                    'sectionDesc',
                                    'college_classsched.sectionid'
                                    )
                                ->orderBy('sectionDesc');

        $subj_sched = $subj_sched->get();
        $classSched = $subj_sched;

        foreach($classSched as $item){
            $item->ftime = $item->stime.' - '.$item->etime;
        }

        $classSched = collect($subj_sched)->groupBy('id');
        $data = array();
        foreach($classSched as $subjitem){
            $byClass = collect($subjitem)->groupBy('scheddetialclass');
            
            foreach($byClass as $item){
                foreach(collect($item)->groupBy('ftime') as $secondItem){
                    $day = '';
                    $temp_sched = collect($secondItem)->sortBy('daysort');
                    $days_list = array();
                    foreach($temp_sched as $thirdItem){
                        $details = $thirdItem;
                        if($thirdItem->description == 'Thursday'){
                            $day .= substr($thirdItem->description, 0 , 1).'h';
                        }
                        elseif($thirdItem->description == 'Sunday'){
                            $day .= substr($thirdItem->description, 0 , 1).'un';
                        }
                        else{
                            $day .= substr($thirdItem->description, 0 , 1).'';
                        }
                        array_push($days_list, $thirdItem->daysort);
                    }
                    $details->description = $day;
                    $details->days_list = $days_list;
                    array_push($data, $details);
                };
            }
        }

        $classSched = collect($data)->groupBy('id');
        $schedules = $data;

        $schedules = collect($schedules)->groupBy('id');
        
        return $schedules;

    }


}