<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Model;
use DB;

class SuperAdminData extends Model
{
    
    public static function student_promotion(
        $syid  = null, 
        $semid = null,
        $levelid = null
    ){

        $check_acadprog = null;


        if($levelid != null){
            $check_acadprog = DB::table('gradelevel')->where('id',$levelid)->select('acadprogid')->first()->acadprogid;
        }
        

        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;
        }

        if($semid == null){
            $semid = DB::table('semester')->where('isactive',1)->select('id')->first()->id;
        }

        $student_promotion = array();

        if($check_acadprog == 6 ){

            $student_promotion = DB::table('college_enrolledstud')
                                    ->join('gradelevel',function($join){
                                        $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                        $join->where('gradelevel.deleted',0);
                                    })
                                    ->join('studinfo',function($join){
                                        $join->on('college_enrolledstud.studid','=','studinfo.id');
                                        $join->where('studinfo.deleted',0);
                                    })
                                    ->where('college_enrolledstud.syid',$syid)
                                    ->where('college_enrolledstud.semid',$semid)
                                    ->where('college_enrolledstud.yearLevel',$levelid)
                                    ->where('college_enrolledstud.deleted',0)
                                    ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                                    ->select(
                                        'levelname',
                                        'firstname',
                                        'lastname',
                                        'gender',
                                        'middlename',
                                        'sid',
                                        'promotionstatus',
                                        'studid',
                                        'studinfo.studstatus',
                                        'suffix'
                                    )
                                    ->get();

        }

        else if($check_acadprog == 5 ){

            $student_promotion = DB::table('sh_enrolledstud')
                                    ->join('gradelevel',function($join){
                                        $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                        $join->where('gradelevel.deleted',0);
                                    })
                                    ->join('studinfo',function($join){
                                        $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                        $join->where('studinfo.deleted',0);
                                    })
                                    ->where('sh_enrolledstud.syid',$syid)
                                    ->where('sh_enrolledstud.semid',$semid)
                                    ->where('sh_enrolledstud.levelid',$levelid)
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                    ->select(
                                        'sh_enrolledstud.strandid',
                                        'levelname',
                                        'firstname',
                                        'lastname',
                                        'gender',
                                        'middlename',
                                        'sid',
                                        'promotionstatus',
                                        'studid',
                                        'studinfo.studstatus',
                                        'suffix'
                                    )
                                    ->get();

        }

        else if($check_acadprog == 4 ){

            $student_promotion = DB::table('enrolledstud')
                                    ->join('gradelevel',function($join){
                                        $join->on('enrolledstud.levelid','=','gradelevel.id');
                                        $join->where('gradelevel.deleted',0);
                                    })
                                    ->join('studinfo',function($join){
                                        $join->on('enrolledstud.studid','=','studinfo.id');
                                        $join->where('studinfo.deleted',0);
                                    })
                                    ->where('enrolledstud.syid',$syid)
                                    ->where('enrolledstud.levelid',$levelid)
                                    ->where('enrolledstud.deleted',0)
                                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                                    ->select(
                                        'levelname',
                                        'firstname',
                                        'lastname',
                                        'gender',
                                        'middlename',
                                        'sid',
                                        'promotionstatus',
                                        'studid',
                                        'studinfo.studstatus',
                                        'suffix'
                                    )
                                    ->get();

        }

        return $student_promotion;

    }

    public static function all_college_students($course = null){

        $new_students = array();

        $students = DB::table('studinfo')
                        ->where('studinfo.deleted',0)
                        ->where('gradelevel.acadprogid',6)
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                        })
                        ->leftJoin('college_courses',function($join){
                            $join->on('studinfo.courseid','=','college_courses.id');
                            $join->where('college_courses.deleted',0);
                        })
                        ->join('college_studentcurriculum',function($join){
                            $join->on('studinfo.id','=','college_studentcurriculum.studid');
                            $join->where('college_studentcurriculum.deleted',0);
                        })
                        ->join('college_curriculum',function($join){
                            $join->on('college_studentcurriculum.curriculumid','=','college_curriculum.id');
                            $join->on('studinfo.courseid','=','college_curriculum.courseID');
                            $join->where('college_curriculum.deleted',0);
                        })
                        ->join('studentstatus',function($join){
                            $join->on('studinfo.studstatus','=','studentstatus.id');
                        })
                        ->select(
                            'studentstatus.description',
                            'studinfo.id',
                            'sid',
                            'firstname',
                            'lastname',
                            'gender',
                            'middlename',
                            'sectionname',
                            'studstatus',
                            'levelname',
                            'courseDesc',
                            'curriculumname',
                            'sectionid',
                            'studinfo.courseid',
                            'studinfo.levelid',
                            'suffix',
                            'college_studentcurriculum.curriculumid'
                        )
                        ->whereNotNull('curriculumname')
                        ->get();

        $student_array = collect($students)->pluck('id');


        $no_cur = DB::table('studinfo')
                        ->where('studinfo.deleted',0)
                        ->where('gradelevel.acadprogid',6)
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                        })
                        ->leftJoin('college_courses',function($join){
                            $join->on('studinfo.courseid','=','college_courses.id');
                            $join->where('college_courses.deleted',0);
                        })
                        ->leftJoin('college_studentcurriculum',function($join){
                            $join->on('studinfo.id','=','college_studentcurriculum.studid');
                            $join->where('college_studentcurriculum.deleted',0);
                        })
                        ->leftJoin('college_curriculum',function($join){
                            $join->on('college_studentcurriculum.curriculumid','=','college_curriculum.id');
                            $join->on('studinfo.courseid','=','college_curriculum.courseID');
                            $join->where('college_curriculum.deleted',0);
                        })
                        ->join('studentstatus',function($join){
                            $join->on('studinfo.studstatus','=','studentstatus.id');
                        })
                        ->select(
                            'studentstatus.description',
                            'studinfo.id',
                            'sid',
                            'firstname',
                            'lastname',
                            'gender',
                            'middlename',
                            'sectionname',
                            'studstatus',
                            'levelname',
                            'courseDesc',
                            'curriculumname',
                            'sectionid',
                            'studinfo.courseid',
                            'studinfo.levelid',
                            'suffix',
                            'college_studentcurriculum.curriculumid'
                        )
                        ->whereNull('curriculumname')
                        ->whereNotIn('studinfo.id',$student_array)
                        ->get();

        foreach($students as $item){
            array_push($new_students, $item);
        }
                      
        foreach($no_cur as $item){
            array_push($new_students, $item);
        }
      
        return $new_students;

    }

    public static function college_sections($syid = null, $semid = null, $courseid = null){

        $college_sections = DB::table('college_sections')->where('deleted',0);

        if($courseid != null){
            $college_sections = $college_sections->where('courseid',$courseid);
        }
                
        return $college_sections->select('id','sectionDesc','yearID','semesterID','syID')->get();

    }

    public static function curriculum_propectus($syid = null, $semid = null, $curriculumid = null){

        $curriculum_prospectus = DB::table('college_prospectus')
                                        ->where('deleted',0)
                                        ->where('curriculumID',$curriculumid)
                                        ->get();

        return   $curriculum_prospectus;

    }

    public static function student_college_enrollment($syid = null, $semid = null, $studid = null){

        $enrollment_record = DB::table('college_enrolledstud')
                                ->where('college_enrolledstud.deleted',0)
                                ->join('semester',function($join){
                                    $join->on('college_enrolledstud.semid','=','semester.id');
                                })
                                ->join('gradelevel',function($join){
                                    $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                })
                                ->leftJoin('college_courses',function($join){
                                    $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                })
                                ->join('sy',function($join){
                                    $join->on('college_enrolledstud.syid','=','sy.id');
                                })
                                ->leftJoin('college_studentcurriculum',function($join){
                                    $join->on('college_enrolledstud.studid','=','college_studentcurriculum.studid');
                                    $join->where('college_studentcurriculum.deleted',0);
                                })
                                ->leftJoin('college_curriculum',function($join){
                                    $join->on('college_studentcurriculum.curriculumid','=','college_curriculum.id');
                                    $join->on('college_enrolledstud.courseid','=','college_curriculum.courseID');
                                    $join->where('college_curriculum.deleted',0);
                                })
                                ->join('studentstatus',function($join){
                                    $join->on('college_enrolledstud.studstatus','=','studentstatus.id');
                                })
                                ->leftJoin('college_sections',function($join){
                                    $join->on('college_enrolledstud.sectionid','=','college_sections.id');
                                    $join->where('college_sections.deleted',0);
                                })
                                ->join('studinfo',function($join){
                                    $join->on('college_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                                })
                                ->leftJoin('college_classsched',function($join){
                                    $join->on('college_sections.id','=','college_classsched.sectionid');
                                    $join->where('college_classsched.deleted',0);
                                })
                                ->where('college_enrolledstud.studid',$studid);

        if($syid != null){
            $enrollment_record = $enrollment_record->where('college_enrolledstud.syid',$syid);
        }
        if($semid != null){
            $enrollment_record = $enrollment_record->where('college_enrolledstud.semid',$semid);
        }
                
        $enrollment_record = $enrollment_record
                                        ->select(
                                            'college_enrolledstud.syid',
                                            'college_enrolledstud.semid',
                                            'yearLevel',
                                            'yearLevel as levelid',
                                            'courseabrv',
                                            'semester',
                                            'sydesc',
                                            'college_enrolledstud.sectionid',
                                            'sectionDesc as sectionname',
                                            'studentstatus.description',
                                            'levelname',
                                            'courseDesc',
                                            'curriculumname',
                                            'acadprogid',
                                            'levelname',
                                            'firstname',
                                            'lastname',
                                            'gender',
                                            'middlename',
                                            'college_enrolledstud.studid',
                                            'gender',
                                            'sid',
                                            'suffix',
                                            'college_classsched.id as schedid',
                                            'college_enrolledstud.courseid'
                                        )
                                        ->get();


        if(count($enrollment_record) > 0){

            $collegesection = DB::table('college_schedgroup_detail')
                                ->where('college_schedgroup_detail.deleted',0)
                                ->whereIn('schedid',collect($enrollment_record)->pluck('schedid'))
                                ->join('college_schedgroup',function($join){
                                    $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                                    $join->where('college_schedgroup.deleted',0);
                                })
                                ->leftJoin('college_courses',function($join){
                                    $join->on('college_schedgroup.courseid','=','college_courses.id');
                                    $join->where('college_courses.deleted',0);
                                })
                                ->leftJoin('gradelevel',function($join){
                                    $join->on('college_schedgroup.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                })
                                ->leftJoin('college_colleges',function($join){
                                    $join->on('college_schedgroup.collegeid','=','college_colleges.id');
                                    $join->where('college_colleges.deleted',0);
                                })
                                ->select(
                                    'college_schedgroup.courseid',
                                    'college_schedgroup.levelid',
                                    'college_schedgroup.collegeid',
                                    'courseDesc',
                                    'collegeDesc',
                                    'levelname',
                                    'courseabrv',
                                    'collegeabrv',
                                    'college_schedgroup.id',
                                    'college_schedgroup.schedgroupdesc',
                                    'schedgroupdesc as text',
                                    'schedid'
                                )
                                ->get();

            foreach($enrollment_record as $item){
                        $courseid = $item->courseid;
                        $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('courseid',$courseid)->values();
                        if(count($checkcoursegroup) != 0){
                                $text = $checkcoursegroup[0]->courseabrv;
                                $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                                $item->sectionname = $text;   
                        }else{
                                $collegeid = DB::table('college_courses')
                                                ->where('id',$courseid)
                                                ->select('collegeid')
                                                ->first();
                                if(isset($collegeid)){
                                    $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('collegeid',$collegeid->collegeid)->values();
                                    if(count($checkcoursegroup) != 0){
                                            $text = $checkcoursegroup[0]->collegeabrv;
                                            $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                                            $item->sectionname = $text;  
                                    }else{
                                            $item->sectionname = 'Not Found';
                                    }
                                }else{
                                    $item->sectionname = null;
                                }
                        }
                    }
        }

    
        return $enrollment_record;

    }


    public static function student_sh_enrollment($syid = null, $semid = null, $studid = null, $sectionid = null){

        $enrollment_record = DB::table('sh_enrolledstud')
                                ->where('sh_enrolledstud.deleted',0)
                                ->join('semester',function($join){
                                    $join->on('sh_enrolledstud.semid','=','semester.id');
                                })
                                ->join('sy',function($join){
                                    $join->on('sh_enrolledstud.syid','=','sy.id');
                                })
                                ->join('sh_strand',function($join){
                                    $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                })
                                ->join('studentstatus',function($join){
                                    $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                                })
                                ->leftJoin('sections',function($join){
                                    $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                })
                                ->leftJoin('gradelevel',function($join){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                })
                                ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                                });
                            
        if($studid != null){
            $enrollment_record = $enrollment_record->where('sh_enrolledstud.studid',$studid);
        }
        if($syid != null){
            $enrollment_record = $enrollment_record->where('sh_enrolledstud.syid',$syid);
        }
        if($sectionid != null){
            $enrollment_record = $enrollment_record->where('sh_enrolledstud.sectionid',$sectionid);
        }
        if($semid != null){
            $enrollment_record = $enrollment_record->where('sh_enrolledstud.semid',$semid);
        }
       
                
        $enrollment_record = $enrollment_record->select(
                                            'sh_enrolledstud.syid',
                                            'sh_enrolledstud.semid',
                                            'sh_enrolledstud.levelid',
                                            'sh_enrolledstud.strandid',
                                            'semester',
                                            'sydesc',
                                            'sh_enrolledstud.strandid',
                                            'sh_enrolledstud.sectionid',
                                            'sections.sectionname',
                                            'acadprogid',
                                            'levelname',
                                            'studentstatus.description',
                                            'sh_enrolledstud.blockid',
                                            'levelname',
                                            'firstname',
                                            'lastname',
                                            'gender',
                                            'middlename',
                                            'gender',
                                            'studid',
                                            'gender',
                                            'suffix',
                                            'sid',
                                            'studinfo.levelid as clevelid',
                                            'sh_enrolledstud.strandid',
                                            'sh_enrolledstud.promotionstatus',
                                            'strandname',
											'strandcode'
                                        )
                                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                        ->where('sh_enrolledstud.deleted',0)
                                        ->orderBy('gender','desc')
                                        ->orderBy('strandid','desc')
                                        ->orderBy('lastname')
                                        ->get();
        
    
        return $enrollment_record;

    }

    public static function student_gshs_enrollment($syid = null, $studid = null, $sectionid = null, $acadprog = null){

        $enrollment_record = DB::table('enrolledstud')
                                ->where('enrolledstud.deleted',0)
                                ->join('sy',function($join){
                                    $join->on('enrolledstud.syid','=','sy.id');
                                })
                                ->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                                })
                                 ->join('studentstatus',function($join){
                                    $join->on('enrolledstud.studstatus','=','studentstatus.id');
                                })
                                ->leftJoin('sections',function($join){
                                    $join->on('enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                                })
                                ->leftJoin('gradelevel',function($join){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                });

        if($studid != null){
            $enrollment_record = $enrollment_record->where('enrolledstud.studid',$studid);
        }

        if($syid != null){
            $enrollment_record = $enrollment_record->where('enrolledstud.syid',$syid);
        }

        if($sectionid != null){
            $enrollment_record = $enrollment_record->where('enrolledstud.sectionid',$sectionid);
        }

        if($acadprog != null){
            $enrollment_record = $enrollment_record->where('gradelevel.acadprogid',$acadprog);
        }

                
        $enrollment_record = $enrollment_record->select(
                                            'enrolledstud.syid',
                                            'enrolledstud.syid',
                                            'enrolledstud.levelid',
                                            'sydesc',
                                            'enrolledstud.sectionid',
                                            'sections.sectionname',
                                            'acadprogid',
                                            'levelname',
                                            'firstname',
                                            'lastname',
                                            'gender',
                                            'middlename',
                                            'studid',
                                            'gender',
                                            'studinfo.levelid as clevelid',
                                            'suffix',
                                            'sid',
                                            'studentstatus.description',
                                            'enrolledstud.promotionstatus'
                                        )
                                        ->whereIn('enrolledstud.studstatus',[1,2,4,])
                                        ->where('enrolledstud.deleted',0)
                                        ->orderBy('gender','desc')
                                        ->orderBy('lastname')
                                        ->get();
        
    
        return $enrollment_record;

    }

    public static function enrollment_record($syid = null, $semid = null, $studid = null){

        $gshs_enrollment = self::student_gshs_enrollment($syid,$studid);
        $sh_enrollment = self::student_sh_enrollment($syid,$semid,$studid);
        

        $enrollment = array();

        foreach($gshs_enrollment as $item){
            $item->semester = '';
            array_push($enrollment,$item);
        }

        foreach($sh_enrollment as $item){
            array_push($enrollment,$item);
        }

        try{  
            $col_enrollment = self::student_college_enrollment($syid,$semid,$studid);
            foreach($col_enrollment as $item){
                array_push($enrollment,$item);
            }
        }catch(\Exception $e){
        }
      

        return $enrollment;

    }

    public static function subject_enrollment_records($syid = null, $semid = null, $studid = null){

        $subject_records = DB::table('college_studsched')
                    ->join('college_classsched',function($join){
                        $join->on('college_studsched.schedid','=','college_classsched.id');
                        $join->where('college_classsched.deleted',0);
                         $join->where('college_studsched.schedstatus','!=','DROPPED');
                    })
                    ->join('college_sections',function($join){
                        $join->on('college_classsched.sectionID','=','college_sections.id');
                        $join->where('college_sections.deleted',0);
                    })
                    ->join('college_prospectus',function($join){
                        $join->on('college_classsched.subjectID','=','college_prospectus.id');
                        $join->where('college_prospectus.deleted',0);
                    })
                    ->leftJoin('teacher',function($join){
                        $join->on('college_classsched.teacherID','=','teacher.id');
                        $join->where('teacher.deleted','0');
                    })
                    ->leftJoin('college_scheddetail',function($join){
                        $join->on('college_classsched.id','=','college_scheddetail.headerid');
                        $join->where('college_scheddetail.deleted','0');
                    })
                    ->where('schedstatus','!=','DROPPED')
                    ->leftJoin('rooms',function($join){
                        $join->on('college_scheddetail.roomID','=','rooms.id');
                        $join->where('rooms.deleted','0');
                    })
                    ->leftJoin('days',function($join){
                        $join->on('college_scheddetail.day','=','days.id');
                    })
                    ->where('studid',$studid)
                    ->where('college_studsched.deleted',0);


        if($syid != null){
            $subject_records = $subject_records->where('college_classsched.syID',$syid);
        }
        if($semid != null){
            $subject_records = $subject_records->where('college_classsched.semesterID',$semid);
        }

        $subject_records = $subject_records->select(
                                    'days.description',
                                    'rooms.roomname',
                                    'college_scheddetail.etime',
                                    'college_scheddetail.stime',
                                    'teacher.firstname',
                                    'teacher.lastname',
                                    'college_classsched.subjectUnit',
                                    'college_prospectus.subjDesc',
                                    'college_prospectus.subjCode',
                                    'college_prospectus.lecunits',
                                    'college_prospectus.labunits',
                                    'college_classsched.id',
                                    'college_sections.sectionDesc',
                                    'college_sections.id as sectionid',
                                    'college_prospectus.id as subjID',
                                    'college_prospectus.subjectID',
                                    'college_scheddetail.scheddetialclass'
                                    // 'schedcodeid',
                                    // 'code'
                                )->get();


        $data = array();

        $bySubject = collect($subject_records)->groupBy('subjID');

        foreach($bySubject as $item){
            $day = '';
            foreach(collect($item)->groupBy('etime') as $secondItem){
                foreach($secondItem as $thirdItem){
                    $details = $thirdItem;
                    if($thirdItem->description == 'Thursday'){
                        $day .= substr($thirdItem->description, 0 , 1).'h ';
                    }
                    elseif($thirdItem->description == 'Sunday'){
                        $day .= substr($thirdItem->description, 0 , 1).'un';
                    }
                    else{
                        $day .= substr($thirdItem->description, 0 , 1).'';
                    }
                }
                $details->description = $day;
                array_push($data, $details);
            };
        }

        return $data;
               
    }

    public static function section_schedule($sectionid = null){


        $schedules = DB::table('college_classsched')
                            ->where('sectionID',$sectionid)
                            ->where('college_classsched.deleted','0')
                            ->join('college_sections',function($join){
                                $join->on('college_classsched.sectionID','=','college_sections.id');
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
                            ->leftJoin('college_scheddetail',function($join){
                                $join->on('college_classsched.id','=','college_scheddetail.headerid');
                                $join->where('college_scheddetail.deleted','0');
                            })
                            ->leftJoin('rooms',function($join){
                                $join->on('college_scheddetail.roomID','=','rooms.id');
                                $join->where('rooms.deleted','0');
                            })
                            ->leftJoin('days',function($join){
                                $join->on('college_scheddetail.day','=','days.id');
                               
                            })
                            ->select(
                                'days.description',
                                'rooms.roomname',
                                'college_scheddetail.etime',
                                'college_scheddetail.stime',
                                'college_scheddetail.scheddetialclass',
                                'teacher.firstname',
                                'teacher.lastname',
                                'college_classsched.subjectUnit',
                                'college_prospectus.subjDesc',
                                'college_prospectus.subjCode',
                                'college_prospectus.id as subjID',
                                'college_prospectus.subjectID',
                                'college_classsched.id',
                                'college_prospectus.lecunits',
                                'college_prospectus.labunits',
                                'college_sections.sectionDesc'
                              
                                )
                            ->orderBy('college_prospectus.subjCode')
                            ->get();

        $data = array();

        $bySubject = collect($schedules)->groupBy('subjID');

        foreach($bySubject as $item){
            $day = '';
            foreach(collect($item)->groupBy('etime') as $secondItem){
                foreach($secondItem as $thirdItem){
                    $details = $thirdItem;
                    if($thirdItem->description == 'Thursday'){
                        $day .= substr($thirdItem->description, 0 , 1).'h ';
                    }
                    elseif($thirdItem->description == 'Sunday'){
                        $day .= substr($thirdItem->description, 0 , 1).'un';
                    }
                    else{
                        $day .= substr($thirdItem->description, 0 , 1).'';
                    }
                }
                $details->description = $day;
                array_push($data, $details);
            };
        }

        return $data;

    }

    public static function student_grade($syid = null, $semid = null, $studid = null){

        $student_grade = DB::table('college_studsched')
                        ->join('college_classsched',function($join){
                            $join->on('college_studsched.schedid','=','college_classsched.id');
                            $join->where('college_classsched.deleted','0');
                        })
                        ->leftJoin('college_grade_status',function($join){
                            $join->on('college_classsched.id','=','college_grade_status.schedid');
                            $join->where('college_grade_status.deleted','0');
                        })
                        ->leftJoin('college_studentprospectus',function($join) use($studid,$semid,$syid){
                            $join->on('college_classsched.subjectID','=','college_studentprospectus.prospectusID');
                            $join->where('college_studentprospectus.deleted','0');
                            $join->where('college_studentprospectus.studid',$studid);
                            $join->where('college_studentprospectus.semid',$semid);
                            $join->where('college_studentprospectus.syid',$syid);
                        })
                        ->where('college_studsched.studid',$studid)
                        ->where('college_studsched.deleted',0);


        if($syid != null){
            $student_grade = $student_grade->where('college_classsched.syID',$syid);
        }
        if($semid != null){
            $student_grade = $student_grade->where('college_classsched.semesterID',$semid);
        }

        $student_grade = $student_grade->select(
                                'college_studentprospectus.prelemgrade',
                                'college_studentprospectus.midtermgrade',
                                'college_studentprospectus.prefigrade',
                                'college_studentprospectus.finalgrade',
                                'college_grade_status.prelimstatus',
                                'college_grade_status.midtermstatus',
                                'college_grade_status.prefistatus',
                                'college_grade_status.finalstatus',
                                'college_studentprospectus.remarks',
                                'college_classsched.id'
                            )->get();

        $grade = array();

        foreach($student_grade as $item){

            $temp_grade =  (object)[];

            if($item->prelimstatus != 3){
                $temp_grade->prelemgrade = null;
            }else{
                $temp_grade->prelemgrade = $item->prelemgrade;
            }

            if($item->midtermstatus != 3){
                $temp_grade->midtermgrade = null;
            }else{
                $temp_grade->midtermgrade = $item->midtermgrade;
            }

            if($item->prefistatus != 3){
                $temp_grade->prefigrade = null;
            }else{
                $temp_grade->prefigrade = $item->prefigrade;
            }

            if($item->finalstatus != 3){
                $temp_grade->finalgrade = null;
                $temp_grade->remarks = null;
            }else{
                $temp_grade->finalgrade = $item->finalgrade;
                $temp_grade->remarks = $item->remarks;
            }

            if($item->remarks == 'DROPPED' || $item->remarks == 'INC'){
                $temp_grade->prelemgrade = null;
                $temp_grade->midtermgrade = null;
                $temp_grade->prefigrade = null;
                $temp_grade->finalgrade = null;
            }

            $temp_grade->id = $item->id;
            array_push($grade, $temp_grade);

        }

        return $grade;


    }

    public static function student_billing($syid = null, $semid = null, $studid = null){

        // $check_payment_setup = DB::table('shssetup')
        $with_detail = false;

        $paysched = DB::table('studpayscheddetail')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where('deleted',0)
                        ->groupBy(DB::raw("MONTH(duedate)"))
                        ->select(
                            'studpayscheddetail.particulars',
                            'studpayscheddetail.duedate',
                            'studpayscheddetail.paymentno',
                            DB::raw("SUM(amountpay) as amountpay"),
                            DB::raw("SUM(amount) as amount"),
                            DB::raw("SUM(balance) as balance")
                        )
                        ->orderBy('duedate','asc')
                        ->get();

        foreach($paysched as $item){

            $month = $item->duedate != null ? ' - '.\Carbon\Carbon::create($item->duedate)->isoFormat('MMMM') : '';
            $item->particulars = 'TUTION/BOOKS/OTH FEE'.$month;

        }
       
        return   $paysched;

    }

    public static function student_student_ledger($syid = null, $semid = null, $studid = null){

        $with_detail = false;

        $paysched = DB::table('studledger')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        // ->where('semid',$semid)
                        ->where('deleted',0)
                        ->where('void',0)
                        ->select('particulars','amount','payment','ornum','classid')
                        ->orderBy('createddatetime')
                        ->get();

        $student_ledger = array();
        $tuition = 0;
        $sort = 3;
        $balfor = 0;
                        
        foreach($paysched as $item){

            $checkBalFor = DB::table('balforwardsetup')->where('classid',$item->classid)->count();

            if($checkBalFor > 0){
                if($balfor == 0){
                    array_push($student_ledger, $item);
                    $item->sort = 1;
                    $balfor += 1;
                }else if($balfor == 1){
                    array_push($student_ledger, $item);
                    $item->sort = count($paysched);
                }
            }else if($item->ornum != null){
                array_push($student_ledger, $item);
                $item->sort = $sort;
                $sort += 1;
            }else{
                
                $tuition += $item->amount;

            }

        }


   
       
        array_push($student_ledger, (object)[
            'particulars'=>'TUITION FEE',
            'amount'=> $tuition,
            'payment'=>0,
            'ornum'=>null,
            'sort'=>2
        ]);
        
        $sorted_ledger = array();

        foreach(collect($student_ledger)->sortBy('sort') as $item){
            array_push( $sorted_ledger, $item);

        }

        return $sorted_ledger;

    }

    public static function previous_balance($syid = null, $semid = null, $studid = null){

       

        $paysched = DB::table('studledger')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where('deleted',0)
                        ->where('void',0)
                        ->select(DB::raw('sum(amount) - sum(payment) as prev_balance'))
                        ->orderBy('createddatetime')
                        ->get();
       
        return   $paysched;

    }

    public static function student_preenrolled_college(){

        $students = DB::table('studinfo')
                        ->where('studinfo.deleted',0)
                        ->where('gradelevel.acadprogid',6)
                        ->where('preEnrolled',1)
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                        })
                        ->leftJoin('college_courses',function($join){
                            $join->on('studinfo.courseid','=','college_courses.id');
                            $join->where('college_courses.deleted',0);
                        })
                        ->leftJoin('college_studentcurriculum',function($join){
                            $join->on('studinfo.id','=','college_studentcurriculum.studid');
                            $join->where('college_studentcurriculum.deleted',0);
                        })
                        ->join('studentstatus',function($join){
                            $join->on('studinfo.studstatus','=','studentstatus.id');
                        })
                        ->leftJoin('college_curriculum',function($join){
                            $join->on('college_studentcurriculum.curriculumid','=','college_curriculum.id');
                            $join->on('studinfo.courseid','=','college_curriculum.courseID');
                            $join->where('college_curriculum.deleted',0);
                        })
                        ->select(
                            'studentstatus.description',
                            'studinfo.id',
                            'sid',
                            'firstname',
                            'lastname',
                            'sectionname',
                            'studstatus',
                            'levelname',
                            'courseDesc',
                            'curriculumname',
                            'sectionid',
                            'studinfo.courseid',
                            'studinfo.levelid',
                            'college_studentcurriculum.curriculumid'
                        )
                        ->get();

        return $students;

    }


    public static function student_enrollment($syid = null, $semid = null, $stuid = null){

        return self::student_sh_enrollment($syid,$semid,$studid);

    }

    public static function college_courses(){

        $courses = DB::table('college_courses')
                    ->where('deleted',0)
                    ->select('id','courseDesc')
                    ->get();

        return $courses;

    }

    public static function college_curriculum(){

        $courses = DB::table('college_curriculum')
                    ->where('deleted',0)
                    ->select('id','curriculumname','courseID')
                    ->get();

        return $courses;

    }

  


}
