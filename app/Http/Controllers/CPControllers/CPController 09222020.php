<?php

namespace App\Http\Controllers\CPControllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Str;
use \Carbon\Carbon;


class CPController extends \App\Http\Controllers\Controller
{
    
    public function viewsections(){

       
        if(auth()->user()->type == 14){

            
            $courses = DB::table('teacher')
                            ->where('userid',auth()->user()->id)
                            ->join('college_colleges',function($join){
                                $join->on('teacher.id','=','college_colleges.dean');
                                $join->where('college_colleges.deleted','0');
                            })
                            ->join('college_courses',function($join){
                                $join->on('college_colleges.id','=','college_courses.collegeid');
                                $join->where('college_courses.deleted','0');
                            })
                            ->select(
                                'college_courses.courseDesc',
                                'college_courses.id'
                                )
                            ->get();

           

            $courseId = collect($courses)->map(function($item, $key){
                return $item->id;
            });
         
        }
        else{


            $courses = DB::table('teacher')
                            ->where('userid',auth()->user()->id)
                            ->join('college_courses',function($join){
                                $join->on('teacher.id','=','college_courses.courseChairman');
                                $join->where('college_courses.deleted','0');
                            })
                            ->select('college_courses.id')
                            ->get();

            $courseId = collect($courses)->map(function($item, $key){
                return $item->id;
            });

        }

        $modalInfo = (object)[
            'modalName'=>'schedDetailModal',
            'modalheader'=>'SECTION',
            'method'=>'/chairperson/sections/create',
            'crud'=>'CREATE'];

        $gradelevels = DB::table('gradelevel')->where('acadprogid','6')->select('id','levelname')->get();

        $activeSem = DB::table('semester')->where('isactive','1')->first();
        $emptyCurriculum = array();

        $courses = DB::table('college_courses')
                    ->whereIn('id',$courseId)
                    ->select('id','courseDesc')
                    ->get();

        $inputs = array(
            (object)['name'=>'semesterID','type'=>'input','table'=>null,'selectValue'=>null,'label'=>'SEMESTER',
            'value'=>strtoupper($activeSem->semester),'attr'=>'readonly="readonly"'],
            (object)['name'=>'courseID','type'=>'select','table'=>null,'data'=> $courses,'label'=>'COURSE','value'=>null,'selectValue'=>'courseDesc'],
            (object)['name'=>'curriculum','type'=>'select','table'=>null,'data'=> $emptyCurriculum,'label'=>'CURRICULUM','value'=>null,'selectValue'=>'dataid'],
            (object)['name'=>'yearID','type'=>'select','table'=>null,'selectValue'=>'id','selectOption'=>'levelname','label'=>'GRADE LEVEL','value'=>NULL, 'data'=>$gradelevels],
            (object)['name'=>'sectionDesc','type'=>'input','table'=>null,'label'=>'SECTION NAME','value'=>NULL]
            
        );

        return view('chairpersonportal.pages.sections.sections')
                        ->with('modalInfo',$modalInfo)
                        ->with('inputs',$inputs);
                        // ->with('sections',$sections);

    }

    // public function storesections (Request $request){

       

    //     $activeSem = DB::table('semester')->where('isactive','1')->first();
    //     $activeSy = DB::table('sy')->where('isactive','1')->first();

     

    //     $course = DB::table('college_courses')->where('courseDesc',strtoupper(str_replace('-',' ',$request->get('courseID'))))->select('id')->first();

    //     if(isset($course->id)){
    //         $request->merge(['courseID'=>$course->id]);
    //     }
                    
    //     if(isset($subjectID->id)){
    //         $request->merge(['subjectID'=>$subjectID->id]);
    //     }
        
    //     $request->merge(['syID'=>$activeSy->id]);
    //     $request->merge(['semesterID'=>$activeSem->id]);


    //     $data = collect($request->all())->toArray();

      

    //     $rules = [
    //         'yearID'=>'required',
    //         'curriculum'=>'required',
    //         'sectionDesc'=>[
    //             'required',
    //             Rule::unique('college_sections','sectionDesc')->where(function($query)use($activeSem){
    //                 return  $query
    //                         ->where('semesterID',$activeSem->id)
    //                         ->where('deleted','0');
    //             })
    //         ],
    //         'semesterID'=>'required',
    //     ];

    //     $message = [
    //         'yearID.required'=>'YEAR is required.',
    //         'sectionDesc.required'=>'SECTION NAME is required.',
    //         'semesterID.required'=>'SEMESTERS is required.',
    //         'sectionDesc.unique'=>'SECTION NAME already exists.'
           
    //     ];

    //     $sectionID = self::proccessstore('college_sections',$data,$rules,$message,'/chairperson/sections',null,true);

   
    //     if(gettype($sectionID) == 'object'){
         
    //         return $sectionID;

    //     };

    //     $prospectussubjects = DB::table('college_prospectus')
    //                             ->where('college_prospectus.deleted','0')
    //                             ->where('courseID',$course->id)
    //                             ->where('yearID',$request->get('yearID'))
    //                             ->where('semesterID',$activeSem->id)
    //                             ->select(
    //                                 'college_prospectus.id'
    //                                 )
    //                             ->get();
       
    //     foreach($prospectussubjects as $prospectussubject){
          
    //         DB::table('college_classsched')->insert([
    //             'syID'=>$activeSy->id,
    //             'semesterID'=>$activeSem->id,
    //             'sectionID'=> $sectionID,
    //             'teacherID'=>null,
    //             'subjectID'=>$prospectussubject->id,
    //             ]);

    //     }

    //     return redirect('/chairperson/sections/show/'.Str::slug($request->get('sectionDesc')));

    // }


    public static function proccessstore(
        $table = null, 
        $data,
        $rules = [], 
        $message = [] , 
        $route = null, 
        $modalName = null,
        $getID = false
        ){

        $validator = Validator::make($data, $rules, $message);

        if ($validator->fails()) {

            $validator->errors()->add('modalName', $modalName);

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            try{
               
                return redirect()->route($route)->withErrors($validator)->withInput();

            }
            catch (\Exception $e){
               
                return redirect($route)->withErrors($validator)->withInput();

            }
        }
        else{

            if($getID){

                return DB::table($table)->insertGetId($data);
                
            }
            else{
                DB::table($table)->insert($data);
            }

            toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
            try{
                return redirect()->route($route);
            }
            catch (\Exception $e){

                return redirect($route);

            }

        }

    }

    public function removesection($section){
        
        $sectionInfo = DB::table('college_sections')
                            ->where('id',$section)
                            ->update([
                                'deleted'=>'1'
                            ]);

        return redirect('chairperson/sections/');
    
    }

    public function showection(Request $request, $section){

        $section =  DB::table('college_sections')
                        ->where('college_sections.id',$request->all('sectcode'))
                        ->leftJoin('college_curriculum',function($join){
                            $join->on('college_sections.curriculumid','=','college_curriculum.id');
                        })
                        ->leftJoin('gradelevel',function($join){
                            $join->on('college_sections.yearID','=','gradelevel.id');
                        })
                        ->where('college_sections.deleted',0)
                        ->select(
                            'semesterID',
                            'syID',
                            'college_sections.courseID',
                            'curriculumid',
                            'sectionDesc',
                            'section_specification',
                            'yearID',
                            'semesterID',
                            'levelname',
                            'curriculumname',
                            'college_sections.id'
                            )
                        ->first();

        if(!isset($section->sectionDesc)){
           
            return back();

        }else{

            $sectionname = $section->sectionDesc;
            
        }

        if($section->section_specification == 1){

            return view('chairpersonportal.pages.sections.viewsection')
                    ->with('sectionInfo',$section );

        }
        else{

            return view('chairpersonportal.pages.sections.specialsection')
                        ->with('sectionInfo',$section);

        }

    

    }

    public function viewprospectus(){

        $courseID = DB::table('teacher')
                    ->where('userid',auth()->user()->id)
                    ->join('college_courses',function($join){
                        $join->on('teacher.id','=','college_courses.courseChairman');
                        $join->where('college_courses.deleted','0');
                    })
                    ->select('college_courses.id')
                    ->first();

       
        $prospectus = DB::table('college_prospectus')
                    ->where('college_prospectus.deleted','0')
                    ->where('courseID',$courseID->id)
                    ->join('college_year',function($join){
                        $join->on('college_prospectus.yearID','=','college_year.id');
                        $join->where('college_year.deleted','0');
                    })
                    ->join('college_subjects',function($join){
                        $join->on('college_prospectus.subjectID','=','college_subjects.id');
                        $join->where('college_subjects.deleted','0');
                    })
                    ->join('semester',function($join){
                        $join->on('college_prospectus.semesterID','=','semester.id');
                        $join->where('semester.deleted','0');
                    })
                    ->select(
                        'college_prospectus.id',
                        'college_subjects.subjDesc',
                        'college_subjects.subjCode',
                        'college_year.yearDesc',
                        'college_prospectus.subjectUnit',
                        'semester.semester'
                    )
                    ->get();

                    return view('chairpersonportal.pages.prospectus')->with('prospectus',$prospectus);

    }

    public function scheduling($location){

 

        if(auth()->user()->type == 14){

            $courses = DB::table('teacher')
                    ->where('userid',auth()->user()->id)
                    ->join('college_colleges',function($join){
                        $join->on('teacher.id','=','college_colleges.dean');
                        $join->where('college_colleges.deleted','0');
                    })
                    ->leftJoin('college_courses',function($join){
                        $join->on('college_colleges.id','=','college_courses.collegeid');
                        $join->where('college_courses.deleted','0');
                    })
                    ->select(
                        'college_courses.courseDesc',
                        'college_courses.id'
                        )
                    ->get();

           

            $courseId = collect($courses)->map(function($item, $key){
                return $item->id;
            });
         
        }
        else{
 

            $courses = DB::table('teacher')
                            ->where('userid',auth()->user()->id)
                            ->join('college_courses',function($join){
                                $join->on('teacher.id','=','college_courses.courseChairman');
                                $join->where('college_courses.deleted','0');
                            })
                            ->select('college_courses.id')
                            ->get();

            $courseId = collect($courses)->map(function($item, $key){
                return $item->id;
            });
        }

        $studentcount = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.sid',
                            'studinfo.firstname',
                            'studinfo.lastname',
                            'college_courses.courseabrv',
                            'gradelevel.levelname',
                            'studinfo.sectionname',
                            'courseid',
                            'courseDesc'
                        )
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                        })
                        ->leftJoin('college_courses',function($join){
                            $join->on('studinfo.courseid','=','college_courses.id');
                        })
                        ->whereIn('levelid',['17','18','19','20','21'])
                        ->whereIn('courseid',$courseId)
                        ->count();

        $data = array( (object)[
            'data'=>[],
            'count'=> $studentcount
        ]);



        return view('chairpersonportal.pages.scheduling.students')
                        ->with('location',$location)
                        ->with('data',$data);
    }

    public function studentscheduling($studid, $studname){

   
        return view('chairpersonportal.pages.student_loading')->with('studid',  $studid);
   

        $syinfo = DB::table('sy')->where('isactive',1)->first();
        $seminfo = DB::table('semester')->where('isactive',1)->first();
        $syid = $syinfo->id;
        $semid = $seminfo->id;

        $student = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.firstname',
                        'studinfo.lastname',
                        'studinfo.sid',
                        'studinfo.levelid',
                        'studinfo.courseid',
                        'studinfo.sectionname',
                        'studinfo.sectionid',
                        'studinfo.qcode',
                        'studinfo.studstatus',
                        'levelname',
                        'courseDesc',
                        'contactno',
                        'mcontactno',
                        'fcontactno',
                        'gcontactno',
                        'semail',
                        'fathername',
                        'mothername',
                        'guardianname'
                    )
                    ->join('gradelevel',function($join){
                        $join->on('gradelevel.id','=','studinfo.levelid');
                        $join->where('gradelevel.deleted',0);
                    })
                    ->leftJoin('college_courses',function($join){
                        $join->on('studinfo.courseID','=','college_courses.id');
                        $join->where('college_courses.deleted',0);
                    })
                    ->where('studinfo.id',$studid)
                    ->first();
        
        if(isset($student->sectionid)){
            
            $sectionInfo = DB::table('college_sections')
                            ->where('id',$student->sectionid)
                            ->select('courseID')
                            ->where('deleted',0)
                            ->first();

            if($sectionInfo->courseID != $student->courseid){

                DB::table('studinfo')
                    ->where('studinfo.id',$studid)
                    ->where('deleted',0)
                    ->take(1)
                    ->update([
                        'studinfo.courseid'=>$sectionInfo->courseID,
                        'studinfo.updatedby'=>auth()->user()->id,
                        'studinfo.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                DB::table('college_enrolledstud')
                    ->where('college_enrolledstud.studid',$studid)
                    ->where('deleted',0)
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->take(1)
                    ->update([
                        'college_enrolledstud.courseid'=>$sectionInfo->courseID,
                        'college_enrolledstud.updatedby'=>auth()->user()->id,
                        'college_enrolledstud.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                $student = DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.firstname',
                        'studinfo.lastname',
                        'studinfo.sid',
                        'studinfo.levelid',
                        'studinfo.courseid',
                        'studinfo.sectionname',
                        'studinfo.sectionid',
                        'studinfo.qcode',
                        'studinfo.studstatus',
                        'levelname',
                        'courseDesc',
                        'contactno',
                        'mcontactno',
                        'fcontactno',
                        'gcontactno',
                        'semail',
                        'fathername',
                        'mothername',
                        'guardianname'
                    )
                    ->join('gradelevel',function($join){
                        $join->on('gradelevel.id','=','studinfo.levelid');
                        $join->where('gradelevel.deleted',0);
                    })
                    ->leftJoin('college_courses',function($join){
                        $join->on('studinfo.courseID','=','college_courses.id');
                        $join->where('college_courses.deleted',0);
                    })
                    ->where('studinfo.id',$studid)
                    ->first();
            }

        }

        if(!isset($check_for_promotion->promotionstatus)){

            $check_for_promotion = (object)['studstatus'=>0,
                                            'sectionDesc'=>$student->sectionname,
                                            'sectionID'=>$student->sectionid
                                        ];

        }

        $prev_sem = $semid;
        $prev_sy = $syid;

        if($semid == 2){
            $prev_sem = 1;
        }elseif($semid == 1){
            $prev_sem = 2;
            $prev_sy = $prev_sy -1;
        }


        $check_prev_promotion_status = DB::table('college_enrolledstud')
                                ->where('college_enrolledstud.studid',$studid)
                                ->where('college_enrolledstud.deleted',0)
                                ->where('college_enrolledstud.syid',$prev_sy)
                                ->where('college_enrolledstud.semid',$prev_sem)
                                ->select(
                                    'promotionstatus'
                                )
                                ->first();

        $prev_promotion_status = (object)[];

        if(isset($check_prev_promotion_status->promotionstatus)){

            if($check_prev_promotion_status->promotionstatus == null){
                $prev_promotion_status = (object)[
                    'status'=>4,
                    'message'=>'Not yet promoted'
                ];
            }
            else if($check_prev_promotion_status->promotionstatus == 1){
                $prev_promotion_status = (object)[
                    'status'=>1,
                    'message'=>'Promoted for '.$syinfo->sydesc.' '.$seminfo->semester
                ];
            }
            else if($check_prev_promotion_status->promotionstatus == 2){
                $prev_sy_info = DB::table('sy')->where('id',$prev_sy)->first();
                $prev_sem_info = DB::table('semester')->where('id',$prev_sy)->first();

                $prev_promotion_status = (object)[
                    'status'=>2,
                    'message'=>'Failed to pass '.$prev_sy_info->sydesc.' '.$prev_sem_info->semester
                ];
            }
           
        }else{
            $prev_promotion_status = (object)[
                'status'=>0,
                'message'=>'Not enrolled last semester'
            ];
        }

        $withCurriculum = true;
        $curriculum = array();
        $studentSched = array();

        $checkForCurriculum = DB::table('college_studentcurriculum')
                                    ->join('college_curriculum',function($join){
                                        $join->on('college_studentcurriculum.curriculumid','=','college_curriculum.id');
                                        $join->where('college_curriculum.deleted',0);
                                    })
                                    ->where('studid',$studid)
                                    ->where('college_studentcurriculum.deleted',0)
                                    ->orderBy('college_studentcurriculum.createddatetime', 'desc')
                                    ->select('college_studentcurriculum.id','curriculumname','curriculumid')
                                    ->first();

        if(!isset($checkForCurriculum->id)){

            $withCurriculum = false;

        }

        $curriculum = DB::table('college_curriculum')
                            ->where('courseID',$student->courseid)
                            ->where('deleted',0)
                            ->select('college_curriculum.id','curriculumname')
                            ->get();


        $getExploded = explode("/",url()->current());

        $courseID = DB::table('teacher')
                    ->where('userid',auth()->user()->id)
                    ->join('college_courses',function($join){
                        $join->on('teacher.id','=','college_courses.courseChairman');
                        $join->where('college_courses.deleted','0');
                    })
                    ->select('college_courses.id')
                    ->first();

        $studid = (int)$studid;

        $schedPros = array();

        if(isset($checkForCurriculum->id)){

            $schedPros = DB::table('college_prospectus')
                        ->join('gradelevel','college_prospectus.yearID','=','gradelevel.id')
                        ->join('semester',function($join){
                            $join->on('college_prospectus.semesterID','=','semester.id');
                            $join->where('isactive','1');
                        })
                        ->select(
                            'college_prospectus.subjCode',
                            'college_prospectus.subjDesc',
                            'college_prospectus.lecunits',
                            'college_prospectus.labunits',
                            'college_prospectus.subjectID',
                            'college_prospectus.id as subjID',
                            'college_prospectus.curriculumID'
                        )
                        ->where('college_prospectus.courseID',$student->courseid)
                        ->where('college_prospectus.curriculumID',$checkForCurriculum->curriculumid)
                        ->where('college_prospectus.deleted','0')
                        ->where('gradelevel.id',$student->levelid)
                        ->orderBy('college_prospectus.subjDesc')
                        ->get();

            $studentSched = DB::table('college_studsched')
                        ->join('college_classsched',function($join){
                            $join->on('college_studsched.schedid','=','college_classsched.id');
                        })
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
                        ->join('sy',function($join){
                            $join->on('college_classsched.syID','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('semester',function($join){
                            $join->on('college_classsched.semesterID','=','semester.id');
                            $join->where('semester.isactive','1');
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
                            'college_prospectus.lecunits',
                            'college_prospectus.labunits',
                            'college_classsched.id',
                            'college_prospectus.id as subjID',
                            'college_prospectus.subjectID',
                            'college_sections.sectionDesc',
                            'college_studsched.deleted'
                            )
                        ->orderBy('college_prospectus.subjCode')
                        ->where(function($query)use($student, $studid){
                            $query->where('studid',$studid);
                            $query->orWhere('studid',$student->qcode);
                        })
                        ->where('college_studsched.deleted','0')
                        ->get();

        }

        if(count($studentSched) == 0){

            $withSched = false;

        }
        else{

            $withSched = true;

        }


        $studentSched = collect($studentSched)->toArray();
        $schedPros = collect($schedPros)->toArray();

        $subjPass = false;

        if(isset($checkForCurriculum->id)){

            foreach ($schedPros as $item){

                $prereq = DB::table('college_subjprereq')   
                            ->where('subjID',$item->subjID)
                            ->where('college_subjprereq.deleted','0')
                            ->select(
                                'prereqsubjID'
                                )
                            ->get();

                $subjPass = true;


                foreach($prereq as $prereqitem){

                    $checkIfPassed = DB::table('college_studsched')
                            ->join('college_classsched',function($join){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted','0');
                            })
                            ->where('college_studsched.remarks','1')
                            ->where('college_classsched.subjectID',$prereqitem->prereqsubjID)
                            ->where('studid',$studid)
                            ->where('college_studsched.deleted','0')
                            ->count();


                    if($checkIfPassed == 0){

                        $backSujects = array();

                        if(isset($checkForCurriculum->id)){

                            $backSujects = DB::table('college_prospectus')
                                        ->where('college_prospectus.id',$prereqitem->prereqsubjID)
                                        ->where('college_prospectus.courseID',$student->courseid)
                                        ->where('college_prospectus.curriculumID',$checkForCurriculum->curriculumid)
                                        ->select(
                                            'college_prospectus.subjCode',
                                            'college_prospectus.subjDesc',
                                            'college_prospectus.lecunits',
                                            'college_prospectus.labunits',
                                            'college_prospectus.id as subjID',
                                            'college_prospectus.subjectID',
                                            'college_prospectus.id'
                                        )
                                        ->get();

                        }

                        if(count($backSujects) != 0){
                            $backSujects[0]->deleted = 0;
                            $backSujects[0]->passed = $subjPass;
                            array_push($schedPros, $backSujects[0]);
                            $subjPass = false;
                        }

                

                    }

                }

                if(collect($studentSched)
                        ->where('subjectID',$item->subjectID)
                        ->count() == 0
                        ){
                    $item->description = '';
                    $item->deleted = 1;
                    $item->passed = $subjPass;
                    array_push($studentSched, $item);

                }
                

            }

        }

        $enrollment_count = DB::table('college_enrolledstud')
                                ->where('studid',$studid)
                                ->where('deleted',0)
                                ->count();

        foreach ($studentSched as $item){

            $prereq = DB::table('college_subjprereq')
                        ->where('subjID',$item->subjID)
                        ->where('college_subjprereq.deleted','0')
                        ->select('prereqsubjID')
                        ->get();

            foreach($prereq as $prereqitem){

                if(!collect($studentSched)->contains('subjID',$prereqitem->prereqsubjID)){

                    $checkIfPassed = DB::table('college_studsched')
                            ->join('college_classsched',function($join){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted','0');
                            })
                            ->where('college_studsched.remarks','1')
                            ->where('college_classsched.subjectID',$prereqitem->prereqsubjID)
                            ->where('studid',$studid)
                            ->where('college_studsched.deleted','0')
                            ->count();

                    if($enrollment_count == 1 || $enrollment_count == 0){
                        $checkIfPassed = 1;
                    }

                    if($checkIfPassed == 0){
                       
                        $backSujects = array();
                        if(isset($checkForCurriculum->id)){
                            $backSujects = DB::table('college_prospectus')
                                        ->where('college_prospectus.id',$prereqitem->prereqsubjID)
                                        ->where('college_prospectus.curriculumID',$checkForCurriculum->curriculumid)
                                        ->select(
                                            'college_prospectus.subjCode',
                                            'college_prospectus.subjDesc',
                                            'college_prospectus.subjectUnit',
                                            'college_prospectus.id as subjID'
                                        )
                                        ->get();
                        }
                        if(count($backSujects) > 0){
                            $backSujects[0]->description = null;
                            $backSujects[0]->deleted = 1;
                            $backSujects[0]->stime = null;
                            $backSujects[0]->etime = null;
                            $backSujects[0]->lastname = null;
                            $backSujects[0]->firstname = null;
                            $backSujects[0]->lecunits = null;
                            $backSujects[0]->labunits = null;
                            $backSujects[0]->sectionDesc  = null;
                            $backSujects[0]->subjectID  = $item->subjectID;
                            $backSujects[0]->passed = $subjPass;
                            $backSujects[0]->subjID = $item->subjID;
                        }
                        else{
                            $backSujects = array((object)[
                                'description' => null,
                                'deleted' => null,
                                'stime' => null,
                                'etime' => null,
                                'lastname' => null,
                                'firstname' => null,
                                'lecunits' => null,
                                'labunits' => null,
                                'sectionDesc' => null,
                                'passed' => null,
                                'subjectID' => $item->subjectID,
                                'subjCode' => $item->subjCode,
                                'subjDesc' => $item->subjDesc,
                                'subjID' => $item->subjID
                            ]);
                        }
                        array_push($studentSched, $backSujects[0]);
                        $subjPass = false;
                    }

                }

            }

            if($subjPass && !isset($item->passed)){
                $item->passed = true;
            }

        }
      
        if(count($studentSched) > 0){

            $bySubject = collect($studentSched)->groupBy('subjID');

            $data = array();

            foreach($bySubject as $subjitem){

                $byClass = collect($subjitem)->groupBy('scheddetialclass');

                foreach($byClass as $item){

                    $day = '';
                    
                    foreach(collect($item)->groupBy('etime') as $secondItem){
                        
                        foreach($secondItem as $thirdItem){

                            $item->put('id','1');

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
                            
                        }

                        
                        if(!isset($details->id)){
                            $details->id = null;
                            $details->scheddetialclass = null;
                            $details->stime = null;
                            $details->etime = null;
                            $details->lastname  = null;
                            $details->firstname  = null;
                            $details->lecunits  = $secondItem[0]->lecunits;
                            $details->labunits  = $secondItem[0]->labunits;
                            $details->roomname  = null;
                            $details->sectionDesc   = null;

                        }
                     

                        
                        $details->description = $day;

                        array_push($data, $details);
                        
                    };

                  
                }

            }
            
            $studentSched = $data;

        }

        $studentSched = collect($studentSched)->groupBy('subjID');

        $schedPros = collect($schedPros)->sortByDesc('passed');
        $studentSched = collect($studentSched)->sortByDesc('passed');

        $sections = array();

        $sections = DB::table('college_sections')
                        ->where('syID',$syid)
                        ->where('semesterID',$semid)
                        ->select(
                            'college_sections.sectionDesc',
                            'college_sections.id'
                        )
                        ->where('college_sections.courseID',$student->courseid)
                        ->where('college_sections.deleted','0')
                        ->get();

      
   
      
                        
        

        $schedules = array();

        foreach($sections as $item){
            $count = DB::table('college_enrolledstud')
                        ->where('sectionid', $item->id)
                        ->where('deleted',0)
                        ->count();
            $item->count = $count;
        }

        // return collect($student);
        
        return view('chairpersonportal.pages.scheduling.studentscheduling')
                    ->with('withSched',$withSched)
                    ->with('sections',$sections)
                    ->with('schedPros',$schedPros)
                    ->with('schedules',$schedules)
                    ->with('withCurriculum',$withCurriculum)
                    ->with('checkForCurriculum',$checkForCurriculum)
                    ->with('studentSched',$studentSched)
                    ->with('curriculum',$curriculum)
                    ->with('check_for_promotion',$check_for_promotion)
                    ->with('prev_promotion_status',$prev_promotion_status)
                    // ->with('year_info',$year_info)
                    // ->with('sem_info',$sem_info)
                    ->with('student',$student);
    }

    public function sectscshed($section){

        $schedules = DB::table('college_classsched')
                            ->where('sectionID',$section)
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
                            ->join('sy',function($join){
                                $join->on('college_classsched.syID','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->join('semester',function($join){
                                $join->on('college_classsched.semesterID','=','semester.id');
                                $join->where('semester.isactive','1');
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

        $bySubject = collect($schedules)->groupBy('subjID');

        $data = array();

        foreach($bySubject as $scheditem){

        

            foreach(collect($scheditem)->groupBy('scheddetialclass') as $item){

                $day = '';

                foreach(collect($item)->groupBy('etime') as $secondItem){
                
                    foreach($secondItem as $thirdItem){

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
                        
                    }

                    $details->description = $day;

                    array_push($data, $details);
                
                };

            }

        }

        $schedules = $data;

        $schedules = collect($schedules)->groupBy('subjID');

        // return $schedules;

        return view('collegeportal.pages.tables.enrollmentsched')
                                ->with('schedules',$schedules);
    }


    public static function storestudentsched(Request $request, $student , $section){

        $section = DB::table('college_sections')
                            ->where('id',$section)
                            ->select(
                                'college_sections.id',
                                'college_sections.yearID as yearLevel',
                                'semesterID'
                            )
                            ->first();

        $enrollID = DB::table('college_enrolledstud')
                        ->insertGetId([
                            'studid'=>$student,
                            'semid'=>$section->semesterID,
                            'yearLevel'=>$section->yearLevel,
                            'sectionId'=>$section->id,
                            'syID'=>'1',
                            'studstatus'=>'1'
                        ]);
                

        $schedules = DB::table('college_sections')
                    ->where('college_sections.id',$section->id)
                    ->join('college_classsched',function($join){
                        $join->on('college_sections.id','=','college_classsched.sectionID');
                        $join->where('college_sections.deleted','0');
                    })
                   
                    ->select(
                        'college_classsched.id'
                    )
                    ->get();

       

        foreach($schedules as $schedule){

            $deleted = 1;

            if(collect($request->get('c'))->contains($schedule->id)){

                $deleted = 0;

            }

            DB::table('college_studsched')->insert([
                'enrollid'=>$enrollID,
                'schedid'=>$schedule->id,
                'deleted'=>$deleted
            ]);

        }

        $student = DB::table('studinfo')
                    ->join('college_enrolledstud',function($join){
                        $join->on('studinfo.id','=','college_enrolledstud.studid');
                        $join->where('deleted','0');
                    })
                    ->join('college_sections',function($join){
                        $join->on('college_enrolledstud.sectionID','=','college_sections.id');
                    })
                    ->select(
                        'college_sections.sectionDesc',
                        'studinfo.id',
                        'studinfo.firstname',
                        'studinfo.lastname',
                        'college_enrolledstud.studstatus',
                        'college_enrolledstud.id as enid',
                        'studinfo.sid'
                    )
                    ->where('studinfo.id',$student)
                    ->first();

        return view('chairpersonportal.pages.cards.enrollmentinfo')->with('student',$student);

    }

    public function teachersubject($subject){


        $subjectID = DB::table('college_prospectus')->where('subjDesc',$subject)->select('id')->first();

        // return $subjectID

        $teachers = DB::table('college_teachersubjects')
                        ->join('teacher',function($join){
                            $join->on('college_teachersubjects.teacherID','=','teacher.id');
                            $join->where('teacher.deleted','0');
                        })
                        ->where('subjID',$subjectID->id)
                        ->where('college_teachersubjects.deleted','0')
                        ->select(
                            'teacher.firstname',
                            'teacher.lastname'
                            )
                        ->get();

        return $teachers;


    }

    public function createscheddetail(Request $request,$section){

        $headerId = DB::table('college_classsched')
                        ->where('sectionID',$request->get('sectionid'))
                        ->where('subjectID',$request->get('subjid'))
                        //->where('id',$request->get('schedid'))
                        ->where('syID',$request->get('syid'))
                        ->where('semesterID',$request->get('semid'))
                        ->where('college_classsched.deleted',0)
                        ->select('id')
                        ->first();

        if($request->get('teacherID') != null){

            DB::table('college_classsched')
                        ->where('id',$headerId->id)
                        ->where('deleted','0')
                        ->update([
                            'teacherID'=>$request->get('teacherID'),
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
        }

        if($request->get('roomid') != null){
            $roomID = DB::table('rooms')
                        ->where('id', $request->get('roomid'))
                        ->where('deleted',0)
                        ->select('id')
                        ->first()->id;
        }
        else{
            $roomID = null;
        }

        $schedules = DB::table('college_classsched') 
                        ->where('sectionID',$request->get('sectionid'))
                        ->where('subjectID',$request->get('subjid'))
                        //->where('college_classsched.id',$request->get('schedid'))
                        ->where('college_classsched.syid',$request->get('syid'))
                        ->where('college_classsched.semesterID',$request->get('semid'))
                        ->where('college_classsched.deleted',0)
                        ->leftJoin('college_scheddetail',function($join) use($request){
                             $join->on('college_classsched.id','=','college_scheddetail.headerid');
                             $join->where('college_scheddetail.deleted','0');
							 $join ->where('scheddetialclass',$request->get('scheddetialclass'));
                        })
						->select(
							'college_classsched.*',
							'college_scheddetail.day'
						)
                        ->get();

        $duplicate_day = array();

        if($request->has('day')){
            foreach($request->get('day') as $item){
                foreach($schedules as $key=>$sched_item){
                    if($sched_item->day == $item){
                        array_push($duplicate_day,$sched_item);
                    }
                }
                DB::table('college_scheddetail')
                        ->where('deleted','0')
                        ->insert(
                            [
                                'headerID'=>$headerId->id, 
                                'day'=>$item,
                                'scheddetialclass'=>$request->get('scheddetialclass'),
                                'roomid'=>$roomID,
                                'stime'=>$request->get('t_from'),
                                'etime'=>$request->get('t_to'),
                                'updatedby'=>auth()->user()->id,
                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                
                            ]);
            }
        }

        // foreach($schedules as $items){
        //     DB::table('college_scheddetail')
        //             ->where('id',$items->id)
        //             ->where('college_scheddetail.deleted',0)
        //             ->update([
        //                 'deletedby'=>auth()->user()->id,
        //                 'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
        //                 'deleted'=>'1'
        //             ]);
        // }

        return array((object)[
            'status'=>0,
            'data'=>"Created Successfully"
        ]);

    }

    public function addtoSched($scheddetail,$sections){

        $studentID = (int) explode("/",url()->previous())[6];

        $studSched = explode(',',$scheddetail);
        $sections = explode(',',$sections);

        $studentSection = null;
        $secMaxCount = 0;

        $student = DB::table('studinfo')->where('id',$studentID)->select('levelid','qcode','id')->first();

        if($sections[0] != '0'){

            foreach(array_count_values($sections) as $key=>$item){
               
              
                if($item > $secMaxCount){

                    $sectionCount = DB::table('college_sections')
                                        ->where('sectionDesc',$key)
                                        ->count();
                                        // ->where('yearID',$student->levelid)
                                        // ->where('college_sections.deleted','0')
                                        // ->join('sy',function($join){
                                        //     $join->on('college_sections.syID','=','sy.id');
                                        //     $join->where('sy.isactive','1');
                                        // })
                                        // ->join('semester',function($join){
                                        //     $join->on('college_sections.semesterID','=','semester.id');
                                        //     $join->where('semester.isactive','1');
                                        // })
                                        // ->select('college_sections.id','college_sections.sectionDesc')
                                        // ->count();

                   

                    if($sectionCount > 0){

                        $secMaxCount = $item;
                        $studentSection = $key;

                    }

                }
            
            }

        }


        $sectionInfo = DB::table('college_sections')
                        ->where('sectionDesc',$studentSection)
                        ->where('college_sections.deleted','0')
                        ->join('sy',function($join){
                            $join->on('college_sections.syID','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('semester',function($join){
                            $join->on('college_sections.semesterID','=','semester.id');
                            $join->where('semester.isactive','1');
                        })
                        ->select('college_sections.id','college_sections.sectionDesc')
                        ->first();


        if( isset($sectionInfo->id) ){

            DB::table('studinfo')
                ->where('id',$studentID)
                ->update([
                    'sectionid'=>$sectionInfo->id,
                    'sectionname'=>$sectionInfo->sectionDesc
                ]);

            $activeSy = DB::table('sy')->where('isactive',1)->first();
            $activeSem = DB::table('semester')->where('isactive',1)->first();

            DB::table('college_enrolledstud')
                ->where('syid',$activeSy->id)
                ->where('studid',$studentID)
                ->where('semid',$activeSem->id)
                ->update([
                    'sectionID'=>$sectionInfo->id,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now()->isoFormat('YYYY')
                ]);

        }
        else{
           
            DB::table('studinfo')
                    ->where('id',$studentID)
                    ->update([
                        'sectionid'=>null,
                        'sectionname'=>null
                    ]);

        }

     
        // return $studSched;

        foreach($studSched as $item){

            if($item != 0){

                // return $studSched;
                 
                // return    DB::table('college_studsched')
                //             ->join('college_classsched',function($join){
                //                 $join->on('college_studsched.schedid','=','college_classsched.id');
                //                 $join->where('college_classsched.deleted','0');
                //             })
                //             ->join('sy',function($join){
                //                 $join->on('college_classsched.syID','=','sy.id');
                //                 $join->where('sy.isactive','1');
                //             })
                //             ->join('semester',function($join){
                //                 $join->on('college_classsched.semesterID','=','semester.id');
                //                 $join->where('semester.isactive','1');
                //             })
                //             ->where('college_studsched.deleted',0)
                //             ->where(function($query)use($student){
                //                 $query->where('studid',$student->qcode);
                //                 $query->orWhere('studid',$student->id);
                //             })->get();

                DB::table('college_studsched')
                            ->join('college_classsched',function($join){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted','0');
                            })
                            ->join('sy',function($join){
                                $join->on('college_classsched.syID','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->join('semester',function($join){
                                $join->on('college_classsched.semesterID','=','semester.id');
                                $join->where('semester.isactive','1');
                            })
                            ->where(function($query)use($student){
                                $query->where('studid',$student->qcode);
                                $query->orWhere('studid',$student->id);
                            })
                            ->updateOrInsert(
                                [
                                    'college_studsched.schedid'=>$item
                                ],
                                [
                                    'college_studsched.deleted'=>'0',
                                    'college_studsched.studid'=>$studentID,
                                    'isapprove'=>'1'
                                ]
                            );

            }

        }

        // return $studSched;

        $studentAvailableSched = DB::table('college_studsched')
                            ->join('college_classsched',function($join){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted','0');
                            })
                            ->join('sy',function($join){
                                $join->on('college_classsched.syID','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->join('semester',function($join){
                                $join->on('college_classsched.semesterID','=','semester.id');
                                $join->where('semester.isactive','1');
                            })
                            ->select('college_studsched.id','college_studsched.schedid')
                            ->where('college_studsched.deleted','0')
                            ->where('college_studsched.studid',$studentID)
                            ->get();



        foreach($studentAvailableSched as $item){
           
            if(!in_array($item->schedid, $studSched)){

                // if($item->schedid == 1366){
                //     return "hello";
                // }
           
                DB::table('college_studsched')
                        ->join('college_classsched',function($join){
                            $join->on('college_studsched.schedid','=','college_classsched.id');
                            $join->where('college_classsched.deleted','0');
                        })
                        ->join('sy',function($join){
                            $join->on('college_classsched.syID','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('semester',function($join){
                            $join->on('college_classsched.semesterID','=','semester.id');
                            $join->where('semester.isactive','1');
                        })
                        ->where('college_studsched.id',$item->id)
                        ->update([
                            'college_studsched.deleted'=>'1',
                            'college_studsched.isapprove'=>'0'
                        ]);

            }

        }

        // return "sdfsdf";
        return redirect(url()->previous());
      

    }

    public function checkifpassed($scheddetail, $studentid){

        $subjectPassed = 1;

        $getPrereq = DB::table('college_classsched')
                        ->join('college_subjprereq',function($join){
                            $join->on('college_classsched.subjectID','=','college_subjprereq.subjID');
                            $join->where('college_subjprereq.deleted','0');
                        })
                        ->where('college_classsched.id',$scheddetail)
                        ->select('college_subjprereq.prereqsubjID')
                        ->get();


        // return  $getPrereq;

        foreach($getPrereq as $item){

            $checkifExist = DB::table('college_studsched')
                                ->join('college_classsched',function($join){
                                    $join->on('college_studsched.schedid','=','college_classsched.id');
                                    $join->where('college_classsched.deleted','0');
                                })
                                ->where('college_classsched.subjectID',$item->prereqsubjID)
                                ->where('studid',(int)$studentid)
                                ->where('remarks',1)
                                ->where('college_studsched.deleted','0')
                                ->get();

            if(count($checkifExist) == 0){

                $subjectPassed = 0;
            }


        }

        return $subjectPassed;

    }

    // public static function getStudentCurrentProspectus(){

    //     $courseID = DB::table('teacher')
    //                     ->where('userid',auth()->user()->id)
    //                     ->join('college_courses',function($join){
    //                         $join->on('teacher.id','=','college_courses.courseChairman');
    //                         $join->where('college_courses.deleted','0');
    //                     })
    //                     ->select('college_courses.id')
    //                     ->first();

               

    // }

    public function allcollegesub($subject){

        $subjectID = DB::table('college_subjects')
                    ->join('college_prospectus',function($join){
                        $join->on('college_subjects.id','=','college_prospectus.subjectID');
                        $join->where('college_prospectus.deleted',0);
                    })
                    ->where('college_subjects.subjDesc',strtoupper(str_replace('-',' ',$subject )))
                    ->where('college_subjects.deleted','0')
                    ->select('college_prospectus.id')
                    ->get();
          
   

        $prosID = Array();

        foreach($subjectID as $item){
            array_push($prosID,$item->id);
        }

  

        // $prospectusId = DB::table('college_prospectus')
        //                     ->we

        $schedules = DB::table('college_classsched')
                            ->whereIn('college_classsched.subjectID',$prosID)
                            ->where('college_classsched.deleted','0')
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
                            ->join('college_sections',function($join){
                                $join->on('college_classsched.sectionID','=','college_sections.id');
                                $join->where('college_sections.deleted','0');
                            })
                            ->leftJoin('rooms',function($join){
                                $join->on('college_scheddetail.roomID','=','rooms.id');
                                $join->where('rooms.deleted','0');
                            })
                            ->leftJoin('days',function($join){
                                $join->on('college_scheddetail.day','=','days.id');
                            
                            })
                            ->join('sy',function($join){
                                $join->on('college_classsched.syID','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->join('semester',function($join){
                                $join->on('college_classsched.semesterID','=','semester.id');
                                $join->where('semester.isactive','1');
                            })
                            ->select(
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
                                'college_prospectus.id as subjID',
                                'college_prospectus.subjectID',
                                'college_scheddetail.scheddetialclass'
                                )
                            
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
                        $day .= substr($thirdItem->description, 0 , 1).' ';
                    }
                    
                }

                $details->description = $day;

                array_push($data, $details);
                
            };

            

        }
        
        $schedules = collect($data)->groupBy('subjID');

        return view('collegeportal.pages.tables.enrollmentsched')
                                ->with('sectionName',true)
                                ->with('schedules',$schedules);

    }


    public function getGrades($section,$quarter){

        $grades = DB::table('grades')
                ->join('gradesdetail',function($join){
                    $join->on('grades.id','=','gradesdetail.headerid');
                })
                ->join('sy',function($join){
                    $join->on('grades.syid','=','sy.id');
                    $join->where('sy.isactive','1');
                })
                ->where('grades.sectionid',$section)
                ->where('grades.quarter',$quarter)
                ->select('gradesdetail.*')
                ->where('grades.deleted','0')
                ->get();
        
        $hps = DB::table('grades')
                ->join('sy',function($join){
                    $join->on('grades.syid','=','sy.id');
                    $join->where('sy.isactive','1');
                })
                ->where('grades.sectionid',$section)
                ->where('grades.quarter',$quarter)
                ->where('grades.deleted','0')
                ->select('grades.*')
                ->get();
                
        $gradesetup = DB::table('gradessetup')->where('gradessetup.id','1')->first();

        
        

        return view('table')
                ->with('grades',$grades)
                ->with('hps',$hps)
                ->with('gradesetup',$gradesetup);

    }

    public function udpatestudencourse($studid,$course){

        $syid = null;
        $semid = null;

        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;
        }

        if($semid == null){
            $semid = DB::table('semester')->where('isactive',1)->select('id')->first()->id;
        }

        DB::table('studinfo')->where('id',$studid)
                ->update([
                    'courseid'=>$course,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                ]);

        $check_studentinfo =  DB::table('studinfo')->where('id',$studid)->where('deleted',0)->select('studstatus')->first();

        if($check_studentinfo->studstatus == 1){

            DB::table('college_enrolledstud')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->update([
                            'updatedby'=>auth()->user()->id,
                            'courseid'=>$course,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        ]);

        }

        $course = DB::table('college_courses')->where('id',$course)->first()->courseabrv;

        return $course;
        
    }

    public function removescheddetail($id){

        $sched = DB::table('college_scheddetail')->where('id',$id)->first();


     
        if(isset($sched)){


            DB::table('college_scheddetail')
                        ->where('headerID',$sched->headerID)
                        ->where('stime',$sched->stime)
                        ->where('etime',$sched->etime)
                        ->where('roomID',$sched->roomID)
                        ->where('scheddetialclass',$sched->scheddetialclass)
                        ->update([
                            'deleted'=>'1'
                        ]);

        }
        

        return back();

    }


public function updateigfg(Request $request){

  

    foreach($request->get('a') as $item){

        DB::table('gradesdetail')
            ->where('id',$item['id'])
            ->update([
                    'ig'=>$item['ig'],
                    'qg'=>$item['fg'],
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>Carbon::now('Asia/Manila')
            ]);


    }
    return 'done';

}



public static function updategrades(Request $request){

    $isSubmitted = DB::table('grades')
                        ->where('id',$request->get('inputedDataHPS')[0][0])
                        ->where('submitted','1')
                        ->count();

    if($isSubmitted > 0){
        return "fsfsdf";
    }
    

    foreach($request->get('inputedData') as $item){
       
        DB::table('gradesdetail')
                    ->where('id', $item[0])
                    ->update([
                            'ww0'=>$item[1],
                            'ww1'=>$item[2],
                            'ww2'=>$item[3],
                            'ww3'=>$item[4],
                            'ww4'=>$item[5],
                            'ww5'=>$item[6],
                            'ww6'=>$item[7],
                            'ww7'=>$item[8],
                            'ww8'=>$item[9],
                            'ww9'=>$item[10],
                            'wwtotal'=>$item[11],
                            'pt0'=>$item[12],
                            'pt1'=>$item[13],
                            'pt2'=>$item[14],
                            'pt3'=>$item[15],
                            'pt4'=>$item[16],
                            'pt5'=>$item[17],
                            'pt6'=>$item[18],
                            'pt7'=>$item[19],
                            'pt8'=>$item[20],
                            'pt9'=>$item[21],
                            'pttotal'=>$item[22],
                            'qa1'=>$item[23],
                            'ig'=>$item[25],
                            'qg'=>$item[26],
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>Carbon::now('Asia/Manila')
                    ]);


    }

    foreach($request->get('inputedDataHPS') as $item){
       
        DB::table('grades')
                    ->where('id', $item[0])
                    ->update([
                            'wwhr0'=>$item[1],
                            'wwhr1'=>$item[2],
                            'wwhr2'=>$item[3],
                            'wwhr3'=>$item[4],
                            'wwhr4'=>$item[5],
                            'wwhr5'=>$item[6],
                            'wwhr6'=>$item[7],
                            'wwhr7'=>$item[8],
                            'wwhr8'=>$item[9],
                            'wwhr9'=>$item[10],
                            'pthr0'=>$item[12],
                            'pthr1'=>$item[13],
                            'pthr2'=>$item[14],
                            'pthr3'=>$item[15],
                            'pthr4'=>$item[16],
                            'pthr5'=>$item[17],
                            'pthr6'=>$item[18],
                            'pthr7'=>$item[19],
                            'pthr8'=>$item[20],
                            'pthr9'=>$item[21],
                            'qahr1'=>$item[23]
                    ]);


    }


   
    // if($request->get('c')==''){
        
    //     $request->merge(['c'=>0]);

    // }

    // DB::table('gradesdetail')
    //     ->where('id',$request->get('a'))
    //     ->update([
    //             $request->get('b')=>(int)$request->get('c'),
    //             'ig'=>$request->get('d')
    //     ]);

    // $gradesDetail = DB::table('gradesdetail')->where('id',$request->get('a'))->first();


    // $wwSum = 0;
    // $wwhpsSum = 0;

    // for($x = 0 ; $x < 10 ; $x++){

    //     $string = 'ww'.$x;
    //     $wwSum += $gradesDetail->$string;

    // }

    // $grades = DB::table('grades')
    //                 ->where('id',$gradesDetail->headerid)
    //                 ->first();

    // for($x = 0 ; $x < 10 ; $x++){

    //     $string = 'wwhr'.$x;
    //     $wwhpsSum += $grades->$string;

    // }


    // DB::table('gradesdetail')
    //     ->where('id',$request->get('a'))
    //     ->update(['wwtotal'=>$wwSum]);


    // if( round( ( $wwSum / $wwhpsSum ) * 100 , 2) < 75){

    //     $wwhpsSum = 75;

    // }
    // else{

    //     $wwhpsSum = round( ( $wwSum / $wwhpsSum ) * 100 , 2);

    // }


    // $data = array(
    //     (object)[
    //     'wwtotal'=>$wwSum,
    //     'wwps'=> $wwhpsSum
    // ]);

    // return $data;


}

public static function updatehps(Request $request){

    // if($request->has('d')){

    //     foreach($request->get('d') as $item){
        
    //         DB::table('gradesdetail')
    //                     ->where('id',$item['id'])
    //                     ->update(['ig'=>$item['ig']]);
        
    //     }

    // }

    // return $request->all();

    if($request->get('c') == '' ){

        $request->merge(['c'=>0]);

    }

    DB::table('grades')
                ->where('id',$request->get('a'))
                ->update([$request->get('b')=>(int) $request->get('c')]);


    // $grades = DB::table('grades')
    //         ->where('id',$request->get('a'))
    //         ->first();

    // return $grades->id;

    // $wwhpsSum = 0;

    // for($x = 0 ; $x < 10 ; $x++){

    //     $string = 'wwhr'.$x;
    //     $wwhpsSum += $grades->$string;

    // }

    // $gradeDetails = DB::table('gradesdetail')
    //                     ->where('headerid',$grades->id)
    //                     ->get();


    // $data = array();
    // $grade = array();

    // return $gradeDetails;

    // foreach($gradeDetails as $items){

    //     $wwsSum = 0;

    //     for($x = 0 ; $x < 10 ; $x++){

    //         $string = 'ww'.$x;
    //         $wwsSum += $items->$string;

    //     }
        
    //     if( round( ( $wwsSum / $wwhpsSum ) * 100 , 2) < 75){

    //         $wwps = 75;

    //     }
    //     else{

    //         $wwps = round( ( $wwsSum / $wwhpsSum ) * 100 , 2);

    //     }

    //     array_push($grade,(object)
    //     [
    //         'wwps'=>$wwps
    //     ]);

    // }

    // array_push($data, (object)[
    //     'wwhpstotal'=>$wwhpsSum,
    //     'grades'=>$grade
        
    // ]);

    // return $data;
}

public function chairpersoninfo(request $request){

    if($request->get('courses') == 'courses' && $request->has('courses')){

       $college_courses = DB::table('college_courses')
                                ->where('deleted',0);

        if($request->get('teacherid') != null && $request->has('teacherid')){


            $college_courses =  $college_courses->where('courseChairman',$request->get('teacherid'));
    
        }    
          
        return  $college_courses->select('college_courses.id','college_courses.courseabrv')->get();

    }



}

//hello

public function studentcurriculum(Request $request){


    DB::table('college_studentcurriculum')
            ->where('studid',$request->get('studid'))
            ->update([
                'deleted'=>1,
                'deletedby'=>auth()->user()->id,
                'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);

    DB::table('college_studentcurriculum')
        ->insert([
            'studid'=>$request->get('studid'),
            'curriculumid'=>$request->get('curriculum'),
            'createdby'=>auth()->user()->id,
            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        ]);


    DB::table('studinfo')
        ->where('studinfo.id',$request->get('studid'))
        ->leftJoin('college_enrolledstud',function($join){
            $join->on('studinfo.id','=','college_enrolledstud.studid');
            $join->where('college_enrolledstud.deleted',0);
        })
        ->take(1)
        ->update([
            'studinfo.sectionid'=>null,
            'studinfo.sectionname'=>null,
            'college_enrolledstud.sectionID'=>null,
            'studinfo.updatedby'=>auth()->user()->id,
            'studinfo.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
            'college_enrolledstud.updatedby'=>auth()->user()->id,
            'college_enrolledstud.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        ]);

    
    $activeSy = DB::table('sy')->where('isactive',1)->first()->id;
    $activeSem = DB::table('semester')->where('isactive',1)->first()->id;


    //remove_added_sched
    $student_sched = DB::table('college_studsched')
                        ->join('college_classsched',function($join){
                            $join->on('college_studsched.schedid','=','college_classsched.id');
                        })
                        ->where('syID',$activeSy)
                        ->where('semesterID',$activeSem)
                        ->where('studid',$request->get('studid'))
                        ->update([
                            'college_studsched.deleted'=>1,
                            'college_studsched.deletedby'=>auth()->user()->id,
                            'college_studsched.deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

    return back();

}

    // 08 - 17 - 2020


    public function unloadedSubjects(Request $request){

        $activeSy = DB::table('sy')->where('isactive',1)->first();


        $unloadedSubjects = DB::table('college_prospectus')
                                ->where('college_prospectus.curriculumID',  $request->get('curriculumid'))
                                ->where('college_prospectus.semesterID',  $request->get('semesterID'))
                                ->where('college_prospectus.yearID',  $request->get('yearID'))
                                ->where('college_prospectus.courseID',  $request->get('courseID'))
                                ->where('college_prospectus.deleted',0)
                                ->leftJoin('college_classsched',function($join) use($request , $activeSy){
                                    $join->on('college_prospectus.id','=','college_classsched.subjectID');
                                    $join->where('college_classsched.sectionID',  $request->get('sectionID'));
                                    $join->where('college_classsched.syID',   $activeSy->id);
                                    $join->where('college_classsched.deleted',0);
                                })
                                ->select('college_prospectus.id','college_prospectus.subjDesc','college_classsched.sectionid')
                                ->get();


        foreach( $unloadedSubjects as $key=>$item){

            if($item->sectionid != null){

                unset($unloadedSubjects[$key]);

            }

        }

        return view('chairpersonportal.pages.sections.unloadedsubjects')->with('unloadedSubjects',$unloadedSubjects);

    }

    public function loadSubjetsToSection(Request $request){

        $activeSy = DB::table('sy')->where('isactive',1)->first();

        // return $request->all();

        DB::table('college_classsched')
            ->insert([
                'semesterID'=>$request->get('semesterID'),
                'syID'=>$request->get('syid'),
                'sectionID'=>$request->get('sectionID'),
                'subjectID'=>$request->get('subjectID'),
                'createdby'=>auth()->user()->id,
                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);



    }

    public function chairperson_student_grades(){
      
        return view('chairpersonportal.pages.student_grades.student_grades');
   
    }

    public function chairperson_section_subjects(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        return \App\Models\ChairPerson\ChairPersonData::section_subject($syid, $semid);
    }


    public function approve_grade_status(Request $request){
        $statusid = $request->get('statusid');
        $datafield = $request->get('datafield');
        return \App\Models\ChairPerson\ChairPersonProccess::approve_grades_status($statusid,$datafield);
    }

    public function pending_grade_status(Request $request){
        $statusid = $request->get('statusid');
        $datafield = $request->get('datafield');
        return \App\Models\ChairPerson\ChairPersonProccess::pending_grade_status($statusid,$datafield);
    }

    public function post_grade_status(Request $request){
        $statusid = $request->get('statusid');
        $datafield = $request->get('datafield');
        return \App\Models\ChairPerson\ChairPersonProccess::post_grade_status($statusid,$datafield);
    }

    public function chairperson_sections(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        return \App\Models\ChairPerson\ChairPersonData::chairperson_sections($syid, $semid, $courseid);

    }

    public function chairperson_courses(Request $request){
        return \App\Models\ChairPerson\ChairPersonData::chairperson_courses();
    }

    public function get_curriculum(Request $request){
        return \App\Models\ChairPerson\ChairPersonData::get_curriculum();
    }

    public function store_section(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        $curriculumid = $request->get('curriculumid');
        $specification = $request->get('specification');
        $sectionname = $request->get('sectionname');
        $levelid = $request->get('levelid');

        return \App\Models\ChairPerson\ChairPersonProccess::store_section($syid, $semid, $courseid, $curriculumid, $specification, $sectionname, $levelid);

    }


    public function college_subjects(Request $request){
        $courseid = $request->get('courseid');
        return \App\Models\ChairPerson\ChairPersonData::college_subjects($courseid);
    }


    public function college_teacher(Request $request){
        return \App\Models\ChairPerson\ChairPersonData::college_teacher();
    }

    public function subject_schedule_blade(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $subject_id = $request->get('subjectid');
        $section_id = $request->get('sectionid');
        $schedules = \App\Models\ChairPerson\ChairPersonData::subject_schedule($syid,$semid,$subject_id, $section_id);
        return view('chairpersonportal.pages.sections.subjectschedule')->with('classSched',$schedules);
        // resources\views\chairpersonportal\pages\sections\subjectschedule.blade.php
    }

    public function subject_schedule(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $prostectusid = $request->get('prostectusid');
        $section_id = $request->get('sectionid');
        $subjectid = $request->get('subjectid');
        return \App\Models\ChairPerson\ChairPersonData::subject_schedule($syid,$semid,$prostectusid, $section_id,$subjectid);
    }


    public function add_instructor(Request $request){
        $schedid = $request->get('schedid');
        $teacherid = $request->get('teacherid');
        return \App\Models\ChairPerson\ChairPersonProccess::add_instructor($schedid, $teacherid);
    }

    

    public function add_section_subject(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $subjectid = $request->get('subjectid');
        return \App\Models\ChairPerson\ChairPersonProccess::add_section_subject($syid, $semid, $sectionid, $subjectid);
    }

    public function update_section(Request $request){
        $sectionid = $request->get('sectionid');
        $sectionname = $request->get('sectionname');
        return \App\Models\ChairPerson\ChairPersonProccess::update_section($sectionid, $sectionname);
    }

    public function remove_section(Request $request){
        $sectionid = $request->get('sectionid');
        return \App\Models\ChairPerson\ChairPersonProccess::remove_section($sectionid);
    }

    public function removeSched(Request $request){
        $schedid = $request->get('schedid');
        return \App\Models\ChairPerson\ChairPersonProccess::removeSched($schedid);
    }

    

}
