<?php

namespace App\Http\Controllers\DeanControllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class DeanController extends \App\Http\Controllers\Controller
{
    public function viewcourses(){

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

     
        $teacher = DB::table('teacher')->where('userid',auth()->user()->id)->first();
        $colleges = DB::table('college_colleges')->where('dean',$teacher->id)->get();
        $college = collect($colleges)->map(function ($college) {
                            return $college->id;
                        });

        return view('deanportal.pages.courses')->with('college',$college);

    }


    function deanviewsubmittedgrades(Request $request){


       

        // $gradestatus =  collect($gradestatus)->groupBy('teacherid')->toArray();

        $teachers = DB::table('teacher')
                    ->where('usertypeid','18')
                    ->where('isactive','1')
                    ->where('deleted','0')
                    ->get();

        return view('deanportal.pages.subjects.submittedgrades')
                    ->with('teachers',$teachers);

    }


    public function viewprospectus($course){



        $courseDesc = strtoupper( str_replace("-"," ",$course));

        $courseInfo = DB::table('college_courses')
                        ->where('courseDesc',$courseDesc)
                        ->where('deleted','0')
                        ->first();

        $subjects = DB::table('college_prospectus')
                    ->where('courseID',$courseInfo->id)
                    ->where('deleted','0')
                    ->select('id','subjDesc')
                    ->take(1)
                    ->get();

        // $addedsubjects = DB::table('college_prospectus')
        //             ->where('courseID',$courseInfo->id)
        //             ->where('deleted','0')
        //             ->select('id','subjDesc')
        //             ->take(1)
        //             ->get();

        $gradelevels = DB::table('gradelevel')->where('acadprogid','6')->get();

        $subjectClassification = array(
            (object)['id'=>'1','description'=>'MAJOR'],
            (object)['id'=>'2','description'=>'MINOR'],
        );

        $inputs1 = array(
            (object)['name'=>'yearID','type'=>'select','table'=>null,'selectValue'=>'id','selectValue'=>'id','selectOption'=>'levelname','label'=>'GRADE LEVEL','value'=>NULL, 'data'=>$gradelevels],
            
            (object)['name'=>'semesterID','type'=>'select','table'=>'college_semester','selectOption'=>'description','label'=>'SEMESTER','value'=>NULL,'selectValue'=>'id',],
            
            (object)['name'=>'subjDesc','type'=>'input','table'=>null,'label'=>'SUBJECT DESCRIPTION','value'=>null],
            
            (object)['name'=>'subjCode','type'=>'input','table'=>null,'label'=>'SUBJECT CODE','value'=>null],
            
            (object)['name'=>'prereq[]','type'=>'select','table'=>null,'data'=> $subjects,'label'=>'SUBJECT PRE-REQUISITE','value'=>null,'selectValue'=>'subjDesc','attr'=>'multiple','selectOption'=>'subjDesc'],
            
            (object)['name'=>'subjClass','type'=>'select','table'=>null,'data'=> $subjectClassification,'label'=>'CLASSIFICATION','value'=>null,'selectValue'=>'id','selectOption'=>'description'],

            (object)['name'=>'lecunits','type'=>'input','table'=>null,'label'=>'LEC. UNITS','value'=>null],
            (object)['name'=>'labunits','type'=>'input','table'=>null,'label'=>'LAB. UNITS','value'=>null],
        );

        $modalInfo1 = (object)[
            'modalName'=>'prospectusModal',
            'modalheader'=>'SUBJECT',
            'method'=>route('dean.store.prospectus', $course),
            'crud'=>'CREATE'];


      

        // $inputs2 = array(
        //     (object)['name'=>'subjDesc','type'=>'input','table'=>null,'label'=>'SUBJECT DESCRIPTION','value'=>null],
        //     (object)['name'=>'subjCode','type'=>'input','table'=>null,'label'=>'SUBJECT CODE','value'=>null],
        //     // (object)['name'=>'prereq[]','type'=>'select','table'=>null,'data'=> $subjects,'label'=>'SUBJECT PRE-REQUISITE','value'=>null,'selectValue'=>'subjDesc','attr'=>'multiple','selectOption'=>'subjDesc'],
        //     // (object)['name'=>'subjclass','type'=>'select','table'=>null,'data'=> $subjectClassification,'label'=>'CLASSIFICATION','value'=>null,'selectValue'=>'id','selectOption'=>'description'],
        //     (object)['name'=>'lecunits','type'=>'input','table'=>null,'label'=>'LEC. UNITS','value'=>null],
        //     (object)['name'=>'labunits','type'=>'input','table'=>null,'label'=>'LAB. UNITS','value'=>null],
        // );

        $modalInfo2 = (object)[
            'modalName'=>'subjectModal',
            'modalheader'=>'SUBJECT',
            'method'=>route('subject.college.create'),
            'crud'=>'CREATE'];

        $inputArray = array();

        array_push($inputArray,
            [$inputs1 , $modalInfo1 ]
            // ,[$inputs2 , $modalInfo2 ]
        );

        $prospectus = DB::table('college_prospectus')
                    ->where('courseid',$courseInfo->id)
                    ->where('college_prospectus.deleted','0')
                    ->join('college_subjects',function($join){
                        $join->on('college_prospectus.subjectID','=','college_subjects.id');
                        $join->where('college_subjects.deleted','0');
                    })
                    ->join('semester',function($join){
                        $join->on('college_prospectus.semesterID','=','semester.id');
                        $join->where('semester.deleted','0');
                    })
                    ->join('gradelevel',function($join){
                        $join->on('college_prospectus.yearID','=','gradelevel.id');
                        $join->where('gradelevel.deleted','0');
                    })
                    ->select(
                        'college_subjects.subjDesc',
                        'college_prospectus.subjectUnit',
                        'college_subjects.subjCode',
                        'college_subjects.lecunits',
                        'college_subjects.labunits',
                        'semester',
                        'gradelevel.id as gid',
                        'college_prospectus.id'
                        )
                    ->get();

        return view('deanportal.pages.prospectus')
                ->with('prospectus',$prospectus)
                ->with('subjects',$subjects)
                ->with('inputArray',$inputArray)
                ->with('courseInfo',$courseInfo);
    }

    public function viewfaculties(){

        $teacherInfo = DB::table('teacher')
                            ->where('userid',auth()->user()->id)
                            ->join('college_colleges',function($join){
                                $join->on('teacher.id','=','college_colleges.dean');
                                $join->where('college_colleges.deleted','0');
                            })
                            ->select('college_colleges.id')
                            ->first();

      
                            // $college_id = DB::table('college_colleges')->where('dean',$teacherInfo->id)->first();

        $teachers =  DB::table('teacher')->where('usertypeid','15')
                        ->join('college_teachercourse',function($join){
                            $join->on('teacher.id','=','college_teachercourse.teacherID');
                            $join->where('college_teachercourse.deleted','0');
                        })
                        ->join('college_courses',function($join){
                            $join->on('college_teachercourse.courseID','=','college_courses.id');
                            $join->where('college_courses.deleted','0');
                        })
                        ->where('college_courses.collegeid',$teacherInfo->id)
                        ->select('teacher.firstname','teacher.lastname','college_courses.courseabrv')
                        ->get();
    
        return view('deanportal.pages.faculty.viewfaculty')
                    ->with('teachers',$teachers);

    }

    public static function storeprospectus(Request $request){

        $getExploded = explode("/",url()->previous());

        $courseDesc =  strtoupper( str_replace("-"," ",end($getExploded)));


      
        $subjDesc =  $request->get('subjDesc');

        $yearID =  strtoupper( str_replace("-"," ",$request->get('yearID')));
        $semesterDesc =  strtoupper( str_replace("-"," ",$request->get('semesterID')));

        $courseID = DB::table('college_courses')->where('deleted','0')->where('courseDesc',$courseDesc)->first();
        $subjectID = DB::table('college_subjects')->where('deleted','0')->where('subjDesc',$subjDesc)->first();
        $yearID = DB::table('college_year')->where('deleted','0')->where('yearDesc',$yearID)->first();
        $semesterID = DB::table('semester')->where('deleted','0')->where('semester',$semesterDesc)->first();
    
        if(isset($yearID->id)){
            $request->merge(['yearID'=>$yearID->id]);
        }
        if(isset($subjectID->id)){
            $request->merge(['subjectID'=>$subjectID->id]);
        }
        if(isset($courseID->id)){
            $request->merge(['courseID'=>$courseID->id]);
        }
        if(isset($semesterID->id)){
            $request->merge(['semesterID'=>$semesterID->id]);
        }
      
        $data = collect($request->all())->forget('prereq')->toArray();

        $rules = [
            'courseID'=>'required',
            'subjDesc'=>[
                'required',
                Rule::unique('college_prospectus','subjDesc')->where(function($query) use($request){
                    return  $query->where('deleted','0')
                            ->where('curriculumID',$request->get('curriculumID'))
                            ->where('courseID',$request->get('courseID'));
                })
            ],
            'yearID'=>'required',
            'semesterID'=>'required'
        ];


     
        $message = [
            'courseID.required'=>'COURSE is required',
            'subjectID.required'=>'SUBJECT is required',
            'yearID.required'=>'SCHOOL YEAR required',
            'subjectUnit.required'=>'UNIT required',
            'semesterID.required'=>'SEMESTER required',
            'subjectID.unique'=>'SUBJECT already exist'
        ];

        $validator = Validator::make($data, $rules, $message);

        if ($validator->fails()) {

            return array((object)[
                'status'=>0,
                'errors'=>$validator->errors()
            ]);
           
        }
        else{

            DB::table('college_prospectus')
                    ->insert([
                        'yearID'=>$request->get('yearID'),
                        'semesterID'=>$request->get('semesterID'),
                        'subjDesc'=>$request->get('subjDesc'),
                        'subjCode'=>$request->get('subjCode'),
                        'subjClass'=>$request->get('subjClass'),
                        'lecunits'=>$request->get('lecunits'),
                        'labunits'=>$request->get('labunits'),
                        'curriculumID'=>$request->get('curriculumID'),
                        'courseID'=>$request->get('courseID'),
                    ]);

            return array((object)[
                'status'=>2,
            ]);

        }

        
        // $subjID = self::proccessstore('college_prospectus',$data, $rules, $message ,'dean/prospectus/'.end($getExploded),'prospectusModal',true);

        

        if($request->has('prereq')){

            foreach($request->get('prereq') as $item){

                $subjDesc = strtoupper( str_replace("-"," ",$item));

                $prereqsubjID = DB::table('college_prospectus')
                                    ->where('subjDesc',$subjDesc)
                                    ->where('courseID',$courseID->id)
                                    ->where('deleted','0')
                                    ->first();

                DB::table('college_subjprereq')->insert([
                    'subjID'=>$subjID,
                    'prereqsubjID'=> $prereqsubjID->id

                ]);
               
            }

        }

        toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');

        return  back();


    }

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

    public function removeprospectussubject($subject){

        $getExploded = explode("/",url()->previous());

        $getExplodedURL = explode("--",$subject);

        $courseDesc =  strtoupper( str_replace("-"," ",end($getExploded)));
        $subjDesc =  strtoupper( str_replace("-"," ",$getExplodedURL[1]));

        $courseID = DB::table('college_courses')->where('deleted','0')->where('courseDesc',$courseDesc)->first();
        $subjectID = DB::table('college_subjects')->where('deleted','0')->where('subjDesc',$subjDesc)->first();

        DB::table('college_prospectus')
            ->where('id',$getExplodedURL[0])
            ->where('subjectID',$subjectID->id)
            ->where('courseID',$courseID->id)
            ->where('deleted','0')
            ->update(['deleted'=>'1']);

        toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
        return redirect('/dean/prospectus/'.end($getExploded));

    }


    public function viewgrades(Request $request){

        if($request->get('blade') == 'blade' && $request->has('blade')){

            return view('deanportal.pages.grades.grades');

        }
        elseif($request->get('table') == 'table' && $request->has('table')){

            $gradelogs = DB::table('college_gradelogs')
                            ->where('type',1)
                            ->orderby('status');
                           
            $countlogs = $gradelogs->count();

            if($request->has('take') && $request->get('take') != null){

                $gradelogs = $gradelogs->take($request->get('take'));
    
            }
    
            if($request->has('skip') && $request->get('skip') != null){
    
                $gradelogs = $gradelogs->skip(  ( $request->get('skip') - 1 )  *   $request->get('take')    );
    
            }

          

            $gradelogs =  $gradelogs->get();


            $data = array((object)[
                'count'=>$countlogs,
                'data'=>$gradelogs
            ]);

            return view('deanportal.pages.grades.gradestable')->with('data',$data);
         
        }
        elseif($request->get('update') == 'update' && $request->has('update')){

            $gradelogs = DB::table('college_gradelogs')
                            ->where('id',$request->get('logid'))
                            ->update([
                                'status'=>1
                            ]);

        }
        elseif($request->get('postgrade') == 'postgrade' && $request->has('postgrade')){

            $gradesterm = DB::table('college_gradesterm')
                            ->where('id',$request->get('gradeid'));

            if($request->get('term') == 1){

                $gradesterm = $gradesterm->update([
                                'prelimsubmit'=>2,
                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                'updatedby'=>auth()->user()->id
                            ]);

            }
            else if($request->get('term') == 2){

                $gradesterm = $gradesterm->update([
                    'midtermsubmit'=>2,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'updatedby'=>auth()->user()->id
                ]);

            }
            else if($request->get('term') == 3){
                
                $gradesterm = $gradesterm->update([
                    'prefisubmit'=>2,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'updatedby'=>auth()->user()->id
                ]);
                
            }
            else if($request->get('term') == 4){

                $gradesterm = $gradesterm->update([
                    'finalsubmit'=>2,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'updatedby'=>auth()->user()->id
                ]);

            }

            $gradestermupdate = DB::table('college_gradesterm')
                                    ->where('college_gradesterm.id',$request->get('gradeid'));

            $gradeloginfo = $gradestermupdate
                                ->join('college_sections',function($join){
                                    $join->on('college_gradesterm.sectionid','=','college_sections.id');
                                })
                                ->join('college_subjects',function($join){
                                    $join->on('college_gradesterm.subjid','=','college_subjects.id');
                                })
                                ->select('subjCode','sectionDesc','college_gradesterm.id')
                                ->first();

            $term = '';

            if($request->get('term') == 1){
            $term = 'prelim';
            }
            else if($request->get('term') == 2){
            $term = 'midterm';
            }
            else if($request->get('term') == 3){
            $term = 'semifinal';
            }
            else if($request->get('term') == 1){
            $term = 'final';
            }

            DB::table('college_gradelogs')->insert([
                'college_gradeid'=>$request->get('gradeid') ,
                'type'=>2,
                'term'=>$request->get('term'),
                'createdby'=>auth()->user()->id,
                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                'message'=>auth()->user()->name.' submitted '.$gradeloginfo->subjCode.' '.$term.' grades for section '.$gradeloginfo->sectionDesc
            ]);

        }
        elseif($request->get('count') == 'count' && $request->has('count')){

            return DB::table('college_gradelogs')
                            ->where('status',1)
                            ->count();

        }
        elseif($request->get('viewgrade') == 'viewgrade' && $request->has('viewgrade')){

            $gradelogs = DB::table('college_gradelogs')
                            ->join('college_gradesterm',function($join){
                                $join->on('college_gradelogs.college_gradeid','=','college_gradesterm.id');
                                $join->where('college_gradesterm.deleted',0);
                            })
                            ->join('college_sections',function($join){
                                $join->on('college_gradesterm.sectionid','=','college_sections.id');
                                $join->where('college_sections.deleted',0);
                            })
                            ->join('college_subjects',function($join){
                                $join->on('college_gradesterm.subjid','=','college_subjects.id');
                                $join->where('college_subjects.deleted',0);
                            })
                            ->where('college_gradelogs.id',$request->get('logid'))
                            ->select('college_gradesterm.sectionid',
                                     'college_gradesterm.subjid',
                                     'college_gradelogs.term',
                                     'college_gradesterm.prelimsubmit',
                                     'college_gradesterm.midtermsubmit',
                                     'college_gradesterm.prefisubmit',
                                     'college_gradesterm.finalsubmit',
                                     'college_sections.sectionDesc',
                                     'college_subjects.subjDesc',
                                     'college_gradesterm.id',
                                     'college_gradelogs.id as logid'
                                     )
                            ->first();


            // $termSetupOrig = array((object)[
            //     'withpre'=>1,
            //     'widthmid'=>1,
            //     'withsemi'=>1,
            //     'withfinal'=>1
            // ]);

            // $termSetup = DB::table('college_gradestermsetup')
            //                     ->where('deleted',0)
            //                     ->select(
            //                         'id',
            //                         'withpre',
            //                         'widthmid',
            //                         'withsemi',
            //                         'withfinal',
            //                     )
            //                     ->first();

            // if(isset($termSetup->id)){

            //     $termSetupOrig->withpre = $termSetup->withpre;
            //     $termSetupOrig->widthmid = $termSetup->widthmid;
            //     $termSetupOrig->withsemi = $termSetup->withsemi;
            //     $termSetupOrig->withfinal = $termSetup->withfinal;

            // }
            

            if(isset($gradelogs->term)){

                $gradeStatus = 0;

                if($gradelogs->term == 1){

                    $gradeStatus = $gradelogs->prelimsubmit;

                }
                else if($gradelogs->term == 2){

                    $gradeStatus = $gradelogs->midtermsubmit;

                }
                else if($gradelogs->term == 3){

                    $gradeStatus = $gradelogs->prefisubmit;

                }
                else if($gradelogs->term == 4){

                    $gradeStatus = $gradelogs->finalsubmit;

                }

                $studentstermgrades = DB::table('college_gradesetup')
                                        ->join('college_gradesdetail',function($join) use($gradelogs){
                                            $join->on('college_gradesetup.id','=','college_gradesdetail.headerid');
                                            $join->where('college_gradesdetail.term',$gradelogs->term);
                                        })
                                        ->join('studinfo',function($join){
                                            $join->on('college_gradesdetail.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                            $join->where('studinfo.studstatus',1);
                                        })
                                        ->where('college_gradesetup.sectionid',$gradelogs->sectionid)
                                        ->where('subjID',$gradelogs->subjid)
                                        ->where('college_gradesetup.deleted',0)
                                        ->groupBy('studid')
                                        ->groupBy('college_gradesdetail.term')
                                        ->orderBy('lastname')
                                        ->select(
                                            'studid',
                                            'firstname',
                                            'lastname',
                                            'college_gradesdetail.term',
                                            DB::raw("SUM(td) as termgrade")
                                        )
                                        ->get();

                

                return view('deanportal.pages.grades.submittedgradeinfo')
                            ->with('gradeStatus',$gradeStatus)
                            ->with('gradelogs',$gradelogs)
                            // ->with('termSetupOrig',$termSetupOrig)
                            ->with('studentstermgrades',$studentstermgrades);

            }

        }


    }


    public function viewallgrades(Request $request){

        if($request->get('blade') == 'blade' && $request->has('blade')){

            return view('deanportal.pages.grades.allgrade');

        }
        elseif($request->get('table') == 'table' && $request->has('table')){

                $activeSem = DB::table('semester')->where('isactive',1)->select('id')->first();
                $activeSy = DB::table('sy')->where('isactive',1)->select('id')->first();

                $allgrades = DB::table('college_gradesterm')
                                ->join('teacher',function($join){
                                    $join->on('college_gradesterm.createdby','=','teacher.userid');
                                    $join->where('college_gradesterm.deleted',0);
                                })
                                ->join('college_sections',function($join){
                                    $join->on('college_gradesterm.sectionid','=','college_sections.id');
                                    $join->where('college_sections.deleted',0);
                                })
                                ->join('college_subjects',function($join){
                                    $join->on('college_gradesterm.subjid','=','college_subjects.id');
                                    $join->where('college_subjects.deleted',0);
                                })
                                ->where('college_gradesterm.semid',$activeSem->id)
                                ->where('college_gradesterm.syid',$activeSy->id)
                                ->select(
                                    'college_gradesterm.prelimsubmit',
                                    'college_gradesterm.midtermsubmit',
                                    'college_gradesterm.prefisubmit',
                                    'college_gradesterm.finalsubmit',
                                    'college_subjects.subjCode',
                                    'college_sections.sectionDesc',
                                    'teacher.firstname',
                                    'teacher.lastname',
                                    'college_gradesterm.id'
                                );

                $allgradescount = $allgrades->count();

                if($request->has('take') && $request->get('take') != null){
    
                    $allgrades = $allgrades->take($request->get('take'));
        
                }
        
                if($request->has('skip') && $request->get('skip') != null){
        
                    $allgrades = $allgrades->skip(  ( $request->get('skip') - 1 )  *   $request->get('take')    );
        
                }

                if($request->has('search') && $request->get('search') != null){

                    $allgrades = $allgrades->where(function($query) use($request){
                        $query->where('sectionDesc','like','%'.$request->get('search').'%');
                        $query->orWhere('firstname','like','%'.$request->get('search').'%');
                        $query->orWhere('lastname','like','%'.$request->get('search').'%');
                        $query->orWhere('subjCode','like','%'.$request->get('search').'%');
                    });
        
                }
    
                $allgrades =  $allgrades->get();

                
    
    
                $data = array((object)[
                    'count'=>$allgradescount,
                    'data'=>$allgrades
                ]);

                $termSetupOrig = (object)[
                    'withpre'=>1,
                    'withmid'=>1,
                    'withsemi'=>1,
                    'withfinal'=>1
                ];
    

                $termSetup = DB::table('college_gradestermsetup')
                                    ->where('deleted',0)
                                    ->select(
                                        'id',
                                        'withpre',
                                        'withmid',
                                        'withsemi',
                                        'withfinal'
                                    )
                                    ->first();

                if(isset($termSetup->id)){

                    $termSetupOrig->withpre = $termSetup->withpre;
                    $termSetupOrig->withmid = $termSetup->withmid;
                    $termSetupOrig->withsemi = $termSetup->withsemi;
                    $termSetupOrig->withfinal = $termSetup->withfinal;

                }

                return view('deanportal.pages.grades.allgradetable')
                            ->with('termSetupOrig',$termSetupOrig)
                            ->with('data',$data);

                return $allgrades;

        }
        elseif($request->get('viewgrade') == 'viewgrade' && $request->has('viewgrade')){

            // return $request->get('gradeid');

            $gradelogs = DB::table('college_gradesterm')
                            ->join('college_sections',function($join){
                                $join->on('college_gradesterm.sectionid','=','college_sections.id');
                                $join->where('college_sections.deleted',0);
                            })
                            ->join('college_subjects',function($join){
                                $join->on('college_gradesterm.subjid','=','college_subjects.id');
                                $join->where('college_subjects.deleted',0);
                            })
                            ->where('college_gradesterm.id',$request->get('gradeid'))
                            ->select('college_gradesterm.sectionid',
                                     'college_gradesterm.subjid',
                                     'college_gradesterm.prelimsubmit',
                                     'college_gradesterm.midtermsubmit',
                                     'college_gradesterm.prefisubmit',
                                     'college_gradesterm.finalsubmit',
                                     'college_sections.sectionDesc',
                                     'college_subjects.subjDesc',
                                     'college_gradesterm.id'
                                     )
                            ->first();

            $gradelogs->term = $request->get('term');

            if(isset($gradelogs->term)){

                $gradeStatus = 0;

                if($gradelogs->term == 1){

                    $gradeStatus = $gradelogs->prelimsubmit;

                }
                else if($gradelogs->term == 2){

                    $gradeStatus = $gradelogs->midtermsubmit;

                }
                else if($gradelogs->term == 3){

                    $gradeStatus = $gradelogs->prefisubmit;

                }
                else if($gradelogs->term == 4){

                    $gradeStatus = $gradelogs->finalsubmit;

                }

                $studentstermgrades = DB::table('college_gradesetup')
                                        ->join('college_gradesdetail',function($join) use($gradelogs){
                                            $join->on('college_gradesetup.id','=','college_gradesdetail.headerid');
                                            $join->where('college_gradesdetail.term',$gradelogs->term);
                                        })
                                        ->join('studinfo',function($join){
                                            $join->on('college_gradesdetail.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                            $join->where('studinfo.studstatus',1);
                                        })
                                        ->where('college_gradesetup.sectionid',$gradelogs->sectionid)
                                        ->where('subjID',$gradelogs->subjid)
                                        ->where('college_gradesetup.deleted',0)
                                        ->groupBy('studid')
                                        ->groupBy('college_gradesdetail.term')
                                        ->orderBy('lastname')
                                        ->select(
                                            'studid',
                                            'firstname',
                                            'lastname',
                                            'gender',
                                            'college_gradesdetail.term',
                                            'ig'
                                        )
                                        ->orderBy('gender')
                                        ->orderBy('lastname')
                                        ->get();

                

                return view('deanportal.pages.grades.submittedgradeinfo')
                            ->with('gradeStatus',$gradeStatus)
                            ->with('gradelogs',$gradelogs)
                            ->with('studentstermgrades',$studentstermgrades);

            }

        }


        // return $allgrades;


    }

    public function college_subject_list(){
        return \App\Models\CollegeSubjects\CollegeSubjectsData::college_subject_list();
    }

    // public static function viewsections(){

    //     $teacherInfo = DB::table('teacher')->where('userid',auth()->user()->id)->first();

    //     $college_id = DB::table('college_colleges')->where('dean',$teacherInfo->id)->first();

    //     $sections = DB::table('college_sections')->where('collegeID',$college_id->id)->get();

       
    

    //     return view('deanportal.pages.sections.sections')
    //             ->with('modalInfo',$modalInfo)
    //             ->with('inputs',$inputs)
    //             ->with('sections',$sections);
     

    // }

    


}
