<?php

namespace App\Http\Controllers\CollegeControllers;

use Illuminate\Http\Request;

use DB;
use Crypt;
use URL;
use Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use \Carbon\Carbon;
use Hash;


class CollegeController extends \App\Http\Controllers\Controller
{
    public static function viewcolleges(){

        $colleges = DB::table('college_colleges')
                        ->where('deleted','0')
                        ->get();

        $inputs = array(
            (object)['name'=>'collegeDesc','type'=>'input','table'=>null,'label'=>'COLLEGE DESCRIPTION','value'=>null],
            (object)['name'=>'collegeabrv','type'=>'input','table'=>null,'label'=>'COLLEGE ABBREVIATION','value'=>null]
        );

        $modalInfo = (object)[
            'modalName'=>'collegeModal',
            'modalheader'=>'COLLEGE',
            'method'=>route('college.create'),
            'crud'=>'CREATE'];

      


        return view('collegeportal.pages.colleges.viewcolleges')
                ->with('inputs',$inputs)
                ->with('modalInfo',$modalInfo)
                ->with('colleges',$colleges);

    }

    public static function storecollege(Request $request){

        $data = collect($request->all())->toArray();

        $rules = [
            'collegeDesc'=>[
                'required' ,  
                Rule::unique('college_colleges','collegeDesc')->where(function($query){
                    return  $query->where('deleted','0');
                })
            ],
            
        ];

        $message = [
            'collegeDesc.unique'=>'COLLEGE already exists.',
            'collegeDesc.required'=>'COLLEGE DESCRIPTION is required',
           
        ];

        return self::proccessstore('college_colleges', $data, $rules, $message,'colleges');


    }

    public static function showcollege($college){

        $collegeDesc =  strtoupper( str_replace("-"," ",$college));

        $collegeInfo = DB::table('college_colleges')
                        ->where('collegeDesc',$collegeDesc)
                        ->where('deleted','0')
                        ->get();

        if(!isset($collegeInfo)){
            return back();
        }

        $courses = DB::table('college_courses')
                        ->where('collegeid',$collegeInfo[0]->id)
                        ->where('deleted','0')
                        ->get();
        
        $inputs1 = array(
            (object)['name'=>'collegeDesc','type'=>'input','table'=>null,'label'=>'COLLEGE DESCRIPTION','value'=>$collegeInfo[0]->collegeDesc],
            (object)['name'=>'collegeabrv','type'=>'input','table'=>null,'label'=>'COLLEGE DESCRIPTION','value'=>$collegeInfo[0]->collegeabrv]
        );

        $modalInfo1 = (object)[
            'modalName'=>'collegeModal',
            'modalheader'=>'COLLEGE',
            'method'=>route('college.update',$college),
            'crud'=>'UPDATE'];


        $inputs2 = array(
            (object)['name'=>'courseDesc','type'=>'input','table'=>null,'label'=>'COURSE DESCRIPTION','value'=>null],
            (object)['name'=>'courseabrv','type'=>'input','table'=>null,'label'=>'COURSE ABBREVIATION','value'=>null]
            // (object)['name'=>'collegeid','type'=>'select','table'=>'college_colleges','label'=>'COLLEGE','selectValue'=>'collegeDesc','value'=>null]
        );

        $modalInfo2 = (object)[
            'modalName'=>'courseModal',
            'modalheader'=>'COURSE',
            'method'=>route('courses.create'),
            'crud'=>'CREATE'];


        $inputArray = array();

        array_push($inputArray,
            [$inputs1 , $modalInfo1 ],
            [$inputs2 , $modalInfo2 ]
        );
       
        return view('collegeportal.pages.colleges.viewcollege')
                    ->with('inputArray',$inputArray)
                    // ->with('modalInfo',$modalInfo)
                    ->with('collegeInfo',$collegeInfo)
                    ->with('courses',$courses);

    }

    public static function updatecollege(Request $request, $college){

     
        $getExploded = explode("/",url()->previous());

        if(end($getExploded) == $college){

            $college =  strtoupper( str_replace("-"," ",$college));

            $collegeID = DB::table('college_colleges')
                                ->where('collegeDesc',$college)
                                ->where('deleted','0')
                                ->get();


            if(isset($collegeID->id)){
                $param = Str::slug($request->get('collegeDesc'),'-');
            }
            else{
                $param = Str::slug($college,'-');
            }

            $data = collect($request->all())->toArray();

            $rules = [
                'collegeDesc'=>[
                    'required',
                    Rule::unique('college_colleges','collegeDesc')->where(function($query){
                        return  $query->where('deleted','0');
                    })->ignore($college,'collegeDesc')
                ],

            ];
    
            $message = [
                'collegeDesc.unique'=>'COLLEGE already exists.',
                'collegeDesc.required'=>'COLLEGE DESCRIPTION is required',
            ];
    
    
            return self::proccessupdate(
                'college_colleges',
                $data, 
                $rules, 
                $message , 
                ['colleges/'.Str::slug($college,'-'), 'colleges/'.Str::slug($request->get('collegeDesc'))],
                ['collegeDesc',$college]
            );

        }

        else{

            return "you are trying to alter the system";

        }

    }

    public static function deletecollege($college){

        $getExploded = explode("/",url()->previous());

        if(end($getExploded) == $college){

            $college =  strtoupper( str_replace("-"," ",$college));

            DB::table('college_colleges')
                        ->where('collegeDesc',$college)
                        ->update([
                            'deleted'=>'1'
                        ]);

            toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');
            return redirect()->route('colleges');
        
        }

        else{

            return "you are trying to alter the system";

        }
     
    }

    public static function viewcourses(){

        $courses = DB::table('college_courses')
                        ->where('college_courses.deleted','0')
                        ->join('college_colleges',function($join){
                            $join->on('college_courses.collegeid','=','college_colleges.id');
                            $join->where('college_colleges.deleted','0');
                        })
                        ->select('college_courses.courseDesc','college_colleges.collegeDesc')
                        ->get();

        

    

        $inputs = array(
            (object)['name'=>'courseDesc','type'=>'input','table'=>null,'label'=>'COURSE DESCRIPTION','value'=>null],
            (object)['name'=>'courseabrv','type'=>'input','table'=>null,'label'=>'COURSE ABBREVIATION','value'=>null],
            (object)['name'=>'collegeid','type'=>'select','table'=>'college_colleges','label'=>'COLLEGE','selectValue'=>'collegeDesc','value'=>null]
        );

        $modalInfo = (object)[
            'modalName'=>'courseModal',
            'modalheader'=>'COURSE',
            'method'=>route('course.create'),
            'crud'=>'CREATE'];

        return view('collegeportal.pages.courses.viewcourses')
                ->with('courses',$courses)
                ->with('modalInfo',$modalInfo)
                ->with('inputs',$inputs);

    }

    public static function storecourse(Request $request){

        $collegeDesc =  strtoupper( str_replace("-"," ",$request->get('collegeid')));

        if($collegeDesc == null){

            $getExploded = explode("/",url()->previous());

            $college = DB::table('college_colleges')->where('collegeDesc',strtoupper( str_replace("-"," ",end($getExploded))))->first();

            // return $college;

            // $courseDesc =  strtoupper( str_replace("-"," ",end($getExploded)));

        }

        // return "SDFSDF";

        $collegeID = DB::table('college_colleges')
                        ->where('deleted','0')
                        ->where('collegeDesc',$college->collegeDesc)
                        ->select('id')
                        ->first();


        // return $collegeID;

        if(isset($collegeID->id)){
           
            $request->merge(['collegeid'=>$collegeID->id ]);

        }

        $data = collect($request->all())->toArray();

        $rules = [
            'courseDesc'=>[
                'required' ,  
                Rule::unique('college_courses','courseDesc')->where(function($query){
                    return  $query->where('deleted','0');
                })
            ],
            'collegeid'=>'required',
        ];

        $message = [
            'courseDesc.unique'=>'COURSE already exists.',
            'courseDesc.required'=>'COURSE DESCRIPTION is required',
            'collegeid.required'=>'COLLGE is required',
        ];

    

        self::proccessstore('college_courses', $data, $rules, $message,'courses');

        return back();

    }

    public static function showcourse($course){

        $courseDesc =  strtoupper( str_replace("-"," ",$course));

        $courseInfo = DB::table('college_courses')
                            ->where('college_courses.deleted','0')
                            ->where('courseDesc',$courseDesc)
                            ->join('college_colleges',function($join){
                                $join->on('college_courses.collegeid','=','college_colleges.id');
                                $join->where('college_colleges.deleted','0');
                            })
                            ->select('college_courses.*','college_colleges.collegeDesc')
                            ->first();

        if(!isset($courseInfo)){

            return back();

        }


        $inputArray = array();

        $inputs = array(
            (object)['name'=>'courseDesc','type'=>'input','table'=>null,'label'=>'COURSE DESCRIPTION','value'=>$courseInfo->courseDesc],
            (object)['name'=>'courseabrv','type'=>'input','table'=>null,'label'=>'COURSE ABBREVIATION','value'=>$courseInfo->courseabrv],
            (object)['name'=>'collegeid','type'=>'select','table'=>'college_colleges','label'=>'COLLEGE','selectValue'=>'collegeDesc','value'=>Str::slug($courseInfo->collegeDesc,'-')]
        );

        $modalInfo = (object)[
            'modalName'=>'courseModal',
            'modalheader'=>'COURSE',
            'method'=>route('course.update', $course),
            'crud'=>'UPDATE'];

        $inputs2 = array(
            (object)['name'=>'yearID','type'=>'select','table'=>'college_year','selectValue'=>'yearDesc','label'=>'SCHOOL YEAR','value'=>NULL],
            (object)['name'=>'semesterID','type'=>'select','table'=>'semester','selectValue'=>'semester','label'=>'SEMESTER','value'=>NULL],
            (object)['name'=>'subjectID','type'=>'select','table'=>'college_subjects','label'=>'SUBJECT','selectValue'=>'subjDesc','value'=>NULL],
            (object)['name'=>'subjectUnit','type'=>'input','table'=>null,'label'=>'SUBJECT UNIT','value'=>NULL]
        );

        $modalInfo2 = (object)[
            'modalName'=>'prospectusModal',
            'modalheader'=>'SUBJECT',
            'method'=>route('prospectus.college.create', $course),
            'crud'=>'CREATE'];

        $inputs3 = array(
            (object)['name'=>'yearID','type'=>'select','table'=>'college_year','selectValue'=>'yearDesc','label'=>'SCHOOL YEAR','value'=>NULL],
            (object)['name'=>'semesterID','type'=>'select','table'=>'semester','selectValue'=>'semester','label'=>'SEMESTER','value'=>NULL],
            (object)['name'=>'sectionName','type'=>'input','table'=>null,'label'=>'SECTION NAME','value'=>NULL]
        );
        
        $modalInfo3 = (object)[
            'modalName'=>'sectionModal',
            'modalheader'=>'SECTION',
            'method'=>route('sections.college.create', $course),
            'crud'=>'CREATE'];

        $modalInfo4 = (object)[
            'modalName'=>'schedDetailModal',
            'modalheader'=>'SCHEDULE DETAIL',
            'method'=>route('sections.college.create', $course),
            'crud'=>'CREATE'];

        $inputs4 = array(
            (object)['name'=>'yearID','type'=>'select','table'=>'college_year','selectValue'=>'yearDesc','label'=>'SCHOOL YEAR','value'=>NULL],
            (object)['name'=>'semesterID','type'=>'select','table'=>'semester','selectValue'=>'semester','label'=>'SEMESTER','value'=>NULL],
            (object)['name'=>'sectionName','type'=>'input','table'=>null,'label'=>'SECTION NAME','value'=>NULL]
        );

        $prospectus = DB::table('college_prospectus')
                        ->where('college_prospectus.deleted','0')
                        ->where('courseID',$courseInfo->id)
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

        $sections = DB::table('college_sections')
                    ->where('courseID',$courseInfo->id)
                    ->join('college_classsched',function($join){
                        $join->on('college_sections.id','=','college_classsched.sectionID');
                        $join->where('college_classsched.deleted','0');
                    })
                    ->join('college_subjects',function($join){
                        $join->on('college_classsched.subjectID','=','college_subjects.id');
                        $join->where('college_subjects.deleted','0');
                    })
                 
                    ->get();

        $sections = collect($sections)->groupBy('sectionName');

        array_push($inputArray,
            [$inputs , $modalInfo ],
            [$inputs2 , $modalInfo2 ],
            [$inputs3 , $modalInfo3 ],
            [$inputs4 , $modalInfo4 ]
        );

        return view('collegeportal.pages.courses.viewcourse')
                ->with('inputArray',$inputArray)
                ->with('prospectus',$prospectus)
                ->with('sections',$sections)
                ->with('courseInfo',$courseInfo);
    }

    public static function updatecourse(Request $request, $course){

        $getExploded = explode("/",url()->previous());

        if(end($getExploded) == $course){

            $course =  strtoupper( str_replace("-"," ",$course));

            $collegeDesc =  strtoupper( str_replace("-"," ",$request->get('collegeid')));

            $collegeID = DB::table('college_colleges')
                            ->where('deleted','0')
                            ->where('collegeDesc',$collegeDesc)
                            ->select('id')
                            ->first();

            if(isset($collegeID->id)){
                $request->merge(['collegeid'=>$collegeID->id ]);
                $param = Str::slug($request->get('courseDesc'),'-');
            }
            else{
                $param = Str::slug($course,'-');
            }

            $data = collect($request->all())->toArray();

            $rules = [
                'courseDesc'=>[
                    'required',
                    Rule::unique('college_courses','courseDesc')->where(function($query){
                        return  $query->where('deleted','0');
                    })->ignore($course,'courseDesc')
                ],
                'collegeid'=>'required',
            ];
    
            $message = [
                'courseDesc.unique'=>'COURSE already exists.',
                'courseDesc.required'=>'COURSE DESCRIPTION is required',
                'collegeid.required'=>'COLLGE is required',
            ];
    
            return self::proccessupdate(
                'college_courses',
                $data, 
                $rules, 
                $message , 
                ['courses/show/'.Str::slug($course,'-'), 'courses/show/'.Str::slug($request->get('courseDesc'))],
                ['courseDesc',$course]
            );

        }

        else{

            return "you are trying to alter the system";

        }

    }

    
    public static function deletecourse($course){

        // return $course;

        // if(end($getExploded) == $course){

            $course =  strtoupper( str_replace("-"," ",$course));

            DB::table('college_courses')
                        ->where('courseDesc',strtoupper(str_replace('-',' ',$course)))
                        ->update([
                            'deleted'=>'1'
                        ]);

            // toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');
         
        
        // }

        // else{

        //     return "you are trying to alter the system";

        // }
     
    }

    public static function viewsubjects(){
      
        $subjects = DB::table('college_subjects')
                        ->where('college_subjects.deleted','0')
                        ->select('id','subjDesc','subjCode','lecunits','labunits','subjClass')
                        ->orderBy('subjDesc')
                        ->get();

        $subjectClassification = array(
            (object)['id'=>'1','description'=>'MAJOR'],
            (object)['id'=>'2','description'=>'MINOR'],
        );

        $inputs = array(
            (object)['name'=>'subjDesc','type'=>'input','table'=>null,'label'=>'SUBJECT DESCRIPTION','value'=>null],
            (object)['name'=>'subjCode','type'=>'input','table'=>null,'label'=>'SUBJECT CODE','value'=>null],
            // (object)['name'=>'prereq[]','type'=>'select','table'=>null,'data'=> $subjects,'label'=>'SUBJECT PRE-REQUISITE','value'=>null,'selectValue'=>'subjDesc','attr'=>'multiple','selectOption'=>'subjDesc'],
            // (object)['name'=>'subjCourse','type'=>'select','table'=>null,'data'=> $courses,'label'=>'COURSE','value'=>null,'selectValue'=>'courseDesc','attr'=>'multiple','selectOption'=>'courseDesc'],
            (object)['name'=>'subjclass','type'=>'select','table'=>null,'data'=> $subjectClassification,'label'=>'CLASSIFICATION','value'=>null,'selectValue'=>'id','selectOption'=>'description'],
            (object)['name'=>'lecunits','type'=>'input','table'=>null,'label'=>'LEC. UNITS','value'=>null],
            (object)['name'=>'labunits','type'=>'input','table'=>null,'label'=>'LAB. UNITS','value'=>null],
        );

        $modalInfo = (object)[
            'modalName'=>'subjectModal',
            'modalheader'=>'SUBJECT',
            'method'=>route('subject.college.create'),
            'crud'=>'CREATE'];

        return view('deanportal.pages.subjects.viewsubjects')
                    ->with('subjects',$subjects)
                    ->with('modalInfo',$modalInfo)
                    ->with('inputs',$inputs);


      
    }

    public static function storesubject(Request $request){

        $course = DB::table('college_courses')->where('courseDesc',strtoupper(str_replace('-',' ',$request->get('subjCourse'))))->first();

        // if(!isset($course)){
        //     $getExploded = explode("/",url()->previous());
        //     $course =  DB::table('college_courses')->where('courseDesc',strtoupper(str_replace('-',' ',end($getExploded))))->first();
        // }
        

        // $request->merge(['subjCourse'=> $course->id]);

        $data = collect($request->all())->forget('prereq')->toArray();

        $rules = [
            'subjDesc'=>[
                'required' ,  
                // Rule::unique('college_subjects','subjDesc')->where(function($query) use($request){
                //     $query->where('deleted','0');
                //     $query->where('subjCourse',$request->get('subjDesc'));
                // })
            ],
            'subjCode'=>'required'
        ];

        $message = [
            'subjDesc.unique'=>'SUBJECT already exists.',
            'subjDesc.required'=>'SUBJECT DESCRIPTION is required',
            'subjCode.required'=>'SUBJECT CODE is required',
        ];



        $subjID = self::proccessstore('college_subjects',$data, $rules, $message , 'subjects.college','subjectModal',true);
       
        if($request->has('prereq')){

            foreach($request->get('prereq') as $item){

                $subjDesc = strtoupper( str_replace("-"," ",$item));

                $prereqsubjID = DB::table('college_subjects')->where('subjDesc',$subjDesc)->first();

                DB::table('college_subjprereq')->insert([
                    'subjID'=>$subjID,
                    'prereqsubjID'=> $prereqsubjID->id

                ]);
               
            }

        }

        toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');

        return back();

        return redirect('/subjects/college');
       

    }

    public static function showsubject($subject){

        $subjDesc = strtoupper( str_replace("-"," ",$subject));

        $subjectInfo = DB::table('college_subjects')
                        ->where('college_subjects.deleted','0')
                        ->where('subjDesc',$subjDesc)
                        ->join('college_courses',function($join){
                            $join->on('college_subjects.subjCourse','=','college_courses.id');
                        })
                        ->select('college_subjects.*','college_courses.courseDesc')
                        ->first();

        if(!isset($subjectInfo)){

            return back();

        }

        $subjects = DB::table('college_subjects')
                        ->join('college_courses',function($join){
                            $join->on('college_subjects.subjCourse','=','college_courses.id');
                        })
                        ->where('college_subjects.deleted','0')
                        ->get();

    

        $prereq = DB::table('college_subjprereq')
                        ->join('college_subjects',function($join){
                            $join->on('college_subjprereq.prereqsubjID','=','college_subjects.id');
                            $join->where('college_subjects.deleted','0');
                        })
                        ->where('college_subjprereq.deleted','0')
                        ->select('subjDesc')
                        ->where('subjID',$subjectInfo->id)
                        ->get();

        $prereqdesc = array();

        $subjectClassification = array(
            (object)['id'=>'1','description'=>'MAJOR'],
            (object)['id'=>'2','description'=>'MINOR'],
        );
        
        foreach($prereq as $item){

            array_push($prereqdesc,$item->subjDesc);
        }

        $inputs = array(
            (object)['name'=>'subjDesc','type'=>'input','table'=>null,'label'=>'SUBJECT DESCRIPTION','value'=>$subjectInfo->subjDesc],
            (object)['name'=>'subjCode','type'=>'input','table'=>null,'label'=>'SUBJECT CODE','value'=>$subjectInfo->subjCode,'selectOption'=>'subjDesc'],
            (object)['name'=>'prereq[]','type'=>'select','table'=>null,'data'=> $subjects,'label'=>'SUBJECT PRE-REQUISITE','value'=>$prereqdesc,'selectValue'=>'subjDesc','attr'=>'multiple','selectOption'=>'subjDesc'],
            (object)['name'=>'subjclass','type'=>'select','table'=>null,'data'=> $subjectClassification,'label'=>'CLASSIFICATION','value'=>$subjectInfo->subjclass,'selectValue'=>'id','selectOption'=>'description'],
        );

        $modalInfo = (object)[
            'modalName'=>'subjectModal',
            'modalheader'=>'SUBJECT',
            'method'=>route('subject.college.update', [ Str::slug($subjectInfo->subjDesc , '-') , str::slug($subjectInfo->courseDesc , '-') ] ),
            'crud'=>'UPDATE'];



    return view('deanportal.pages.subjects.viewsubject')
                    ->with('prereq',$prereq)
                    ->with('inputs',$inputs)
                    ->with('modalInfo',$modalInfo)
                    ->with('subjectInfo',$subjectInfo);

    }

    public static function updatesubject(Request $request, $subject, $course){

        $getExploded = explode("/",url()->previous());

            $param = null;

            $subject =  strtoupper( str_replace("-"," ",$subject));

            $courseDesc = strtoupper( str_replace("-"," ",$course));

            $courseInfo = DB::table('college_courses')
                                ->where('deleted','0')
                                ->where('courseDesc',$courseDesc)
                                ->select('id')
                                ->first();


            if(isset($courseInfo->id)){
                $request->merge(['subjCourse'=>$courseInfo->id ]);
                $param = Str::slug($request->get('subjDesc'),'-');
            }
            else{
                $param = Str::slug($subject,'-');
            }

            $subjID = DB::table('college_subjects')
                        ->where('college_subjects.subjDesc',$subject)
                        ->where('college_subjects.subjCourse',$courseInfo->id)
                        ->where('college_subjects.deleted','0')
                        ->select('id')
                        ->first();

            if($request->has('prereq')){

                foreach($request->get('prereq') as $item){

                    $prereqSubjID = DB::table('college_subjects')->where('college_subjects.subjDesc',strtoupper( str_replace("-"," ",$item)))->where('college_subjects.deleted','0')->select('id')->first();

                    DB::table('college_subjprereq')->updateOrInsert(
                        ['subjID'=>$subjID->id, 'prereqsubjID'=>$prereqSubjID->id],
                        ['deleted'=>'0']
                    ); 

                }

                $prereq = DB::table('college_subjprereq')
                            ->join('college_subjects',function($join){
                                $join->on('college_subjprereq.prereqsubjID','=','college_subjects.id');
                                $join->where('college_subjects.deleted','0');
                            })
                            ->where('college_subjprereq.deleted','0')
                            ->where('college_subjprereq.subjID',$subjID->id)
                            ->select('college_subjects.subjDesc','college_subjprereq.id')
                            ->get();


                foreach($prereq as $item){

                    if(!in_array(Str::slug($item->subjDesc), collect($request->get('prereq'))->toArray())){
                    
                        DB::table('college_subjprereq')->where('college_subjprereq.id',$item->id)->update(['deleted'=>'1']);

                    }
                    

                }

            }
            else{

                DB::table('college_subjprereq')
                            ->where('college_subjprereq.deleted','0')
                            ->where('college_subjprereq.subjID',$subjID->id)
                            ->update([
                                'deleted'=>'1'
                            ]);
            }


            $data = collect($request->all())->forget('prereq')->forget('_token')->toArray();

            $rules = [
                'subjDesc'=>[
                    'required',
                    Rule::unique('college_subjects','subjDesc')->where(function($query){
                        return  $query->where('deleted','0');
                    })->ignore($subject,'subjDesc')
                ],
                'subjCode'=>'required',
            ];
    
            $message = [
                'subjDesc.unique'=>'SUBJECT already exists.',
                'subjDesc.required'=>'SUBJECT DESCRIPTION is required',
                'subjCode.required'=>'SUBJECT CODE is required',
            ];
    
            return self::proccessupdateV2(
                'college_subjects',
                $data, 
                $rules, 
                $message , 
                ['subjects/college/show/'.Str::slug($subject,'-'), 'subjects/college/show/'.Str::slug($request->get('subjDesc'))],
                ['subjDesc',$subject]
            );

        // }


    }

    
    public static function deletesubject($course,$subject){

        $getExploded = explode("/",url()->previous());

        $courseID = DB::table('college_courses')
                        ->where('courseDesc',strtoupper( str_replace("-"," ",$course)))
                        ->first();

        $checkCourse = DB::table('college_subjects')
                        ->where('id',$subject)
                        ->where('subjCourse',$courseID->id)
                        ->count();

        if($checkCourse != 0){
           
            DB::table('college_subjects')
                        ->where('id',$subject)
                        ->where('subjCourse',$courseID->id)
                        ->update([
                            'deleted'=>'1'
                        ]);

            return 1;
        }

        else{

            return 0;

        }
     
    }

    public static function viewfacultystaff(){

        $teachers = DB::table('teacher')
                        ->where('teacher.deleted','0')
                       ->where('createdby',auth()->user()->id)
                        ->select(
                            'teacher.firstname',
                            'teacher.lastname'
                        )
                        ->get();

        $inputs = array(
            (object)['name'=>'firstname','type'=>'input','table'=>null,'label'=>'FIRST NAME','value'=>null],
            (object)['name'=>'middlename','type'=>'input','table'=>null,'label'=>'MIDDLE NAME','value'=>null],
            (object)['name'=>'lastname','type'=>'input','table'=>null,'label'=>'LAST NAME','value'=>null],
            (object)['name'=>'courseDesc','type'=>'select','table'=>'college_courses','label'=>'COURSE','selectValue'=>'courseDesc','value'=>null],
        );


        $modalInfo = (object)[
            'modalName'=>'facultystaffModal',
            'modalheader'=>'FACULTY AND STAFF',
            'method'=>route('facultystaff.college.create'),
            'crud'=>'CREATE'];

       
      

        return view('collegeportal.pages.facultystaff.viewfacultystaffs')
                ->with('teachers',$teachers)
                ->with('modalInfo',$modalInfo)
                ->with('inputs',$inputs);
        
    }

    public static function storefacultystaff(Request $request){

        $request = $request->merge(['usertypeid'=>'1']);
        $request = $request->merge(['deleted'=>'0']);

        $data = collect($request->all())->toArray();

       
        $rules = [
            'firstname'=>['required', Rule::unique('teacher','firstname')->where(function($query){
                return  $query->where('deleted','0');
            })->where('lastname',$request->get('lastname'))],
            'lastname'=>'required',
            'middlename'=>'required',
            'courseDesc'=>'required'
        ];

        $message = [
            'firstname.required'=>'FIRST NAME is required',
            'lastname.required'=>'LAST NAME is required',
            'middlename.required'=>'MIDDLE NAME is required',
            'courseDesc.required'=>'COLLEGE is required',
            'firstname.unique'=>'NAME already exist'
        ];

     

        try{

            $teacherId = self::proccessstore('teacher',$data, $rules, $message,'facultystaff.college',null,true);
        
        }
        catch (\Exception $e){

            $data = collect($request->all())->forget('courseDesc')->toArray();

            $rules = [
                'firstname'=>'required',
                'lastname'=>'required',
                'middlename'=>'required',
            ];
    
            $message = [
                'firstname.required'=>'FIRST NAME is required',
                'lastname.required'=>'LAST NAME is required',
                'middlename.required'=>'MIDDLE NAME is required',
            ];

            $teacherId = self::proccessstore('teacher',$data, $rules, $message,'facultystaff.college',null,true);

        }

        if(gettype($teacherId) == 'object'){

            return $teacherId;

        }

        $teacheremail = Carbon::now()->isoFormat('YYYY').sprintf('%04d', $teacherId);

        $userid = DB::table('users')->insertGetId([
            'name'=>$request->get('lastname').', '.$request->get('firstname'),
            'email'=>$teacheremail,
            'password'=>Hash::make('123456'),
            'type'=> '1',
        ]);

        DB::table('teacher')->where('id',$teacherId)->update(['userid'=>$userid]);


        DB::table('faspriv')->insert([
            'userid'=>$userid,
            'usertype'=>'1',
            'privelege'=>'2',
            'deleted'=>'0'
        ]);

        $courseDesc = DB::table('college_courses')
                        ->where('courseDesc',strtoupper( str_replace("-"," ",$request->get('courseDesc'))))
                        ->first();

        DB::table('college_teachercourse')->insert([
            'teacherID'=>$teacherId,
            'courseID'=>$courseDesc->id,
            ]);

        return redirect()->route('facultystaff.college');


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


    public static function proccessupdate($table = null, $data, $rules = [], $message = [] , $url = [ null , null ] , $condition = [null,null],   $modalName = null, $moreProcess = false){

        $validator = Validator::make($data, $rules, $message);

        if ($validator->fails()) {


            $validator->errors()->add('modalName', $modalName);

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            return redirect($url[0])->withErrors($validator)->withInput();;
           
        }

        else{

            toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
            DB::table($table)
                ->where($condition[0], $condition[1])
                ->update($data);

            if($moreProcess){

                return true;

            }
            else{

                return redirect($url[1]);

            }

           

        }

    }


    public static function proccessupdateV2($table = null, $data, $rules = [], $message = [] , $url = [ null , null ] , $condition = [null,null],   $modalName = null, $moreProcess = false){

     
        
        $validator = Validator::make($data, $rules, $message);

        if ($validator->fails()) {

          
            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            return array(
                (object)[
                    'status'=>0,
                    'errors'=>$validator->errors()
                    ]);

            
           
        }

        else{

            toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');

            DB::table($table)
                ->where($condition[0], $condition[1])
                ->update($data);

            if($moreProcess){

                return true;

            }
            else{

                 return array(
                        (object)[
                            'status'=>1,
                            // 'inputs'=>$data
                            ]);

            }

           

        }

    }

    // public static function storeprospectus(Request $request){
        
    //     $getExploded = explode("/",url()->previous());

    //     $courseDesc =  strtoupper( str_replace("-"," ",end($getExploded)));

    //     $subjDesc =  $request->get('subjDesc');

    //     $yearID =  strtoupper( str_replace("-"," ",$request->get('yearID')));
    //     $semesterDesc =  strtoupper( str_replace("-"," ",$request->get('semesterID')));

    //     $courseID = DB::table('college_courses')->where('deleted','0')->where('courseDesc',$courseDesc)->first();

    //     $subjectID = DB::table('college_subjects')->where('deleted','0')->where('subjDesc',$subjDesc)->first();
    //     $yearID = DB::table('college_year')->where('deleted','0')->where('yearDesc',$yearID)->first();
    //     $semesterID = DB::table('semester')->where('deleted','0')->where('semester',$semesterDesc)->first();

    
    //     if(isset($yearID->id)){
    //         $request->merge(['yearID'=>$yearID->id]);
    //     }
    //     if(isset($subjectID->id)){
    //         $request->merge(['subjectID'=>$subjectID->id]);
    //     }
    //     if(isset($courseID->id)){
    //         $request->merge(['courseID'=>$courseID->id]);
    //     }
    //     if(isset($semesterID->id)){
    //         $request->merge(['semesterID'=>$semesterID->id]);
    //     }
      
      
    //     // return $subjDesc;

    //     $data = collect($request->all())->toArray();

    //     $rules = [
    //         'courseID'=>'required',
    //         'subjDesc'=>[
    //             'required',
    //             Rule::unique('college_prospectus','subjDesc')->where(function($query){
    //                 return  $query->where('deleted','0');
    //             })->where('subjDesc',$subjDesc)
    //               ->where('courseID',$courseID)
    //         ],
    //         'yearID'=>'required',
    //         'subjectUnit'=>'required',
    //         'semesterID'=>'required'
    //     ];

    //     $message = [
    //         'courseID.required'=>'COURSE is required',
    //         'subjectID.required'=>'SUBJECT is required',
    //         'yearID.required'=>'SCHOOL YEAR required',
    //         'subjectUnit.required'=>'UNIT required',
    //         'semesterID.required'=>'SEMESTER required',
    //         'subjectID.unique'=>'SUBJECT already exist'
    //     ];

    //     return self::proccessstore('college_prospectus',$data, $rules, $message , 'courses/show/'.end($getExploded),'prospectusModal');


    // }

    public static function deleteprospectus($subject){

        // return "sdfsdf";

        // $getExploded = explode("/",url()->previous());

        // $getExplodedURL = explode("--",$subject);

        // $courseDesc =  strtoupper( str_replace("-"," ",end($getExploded)));
        // $subjDesc =  strtoupper( str_replace("-"," ",$getExplodedURL[1]));

        // $courseID = DB::table('college_courses')->where('deleted','0')->where('courseDesc',$courseDesc)->first();
        // $subjectID = DB::table('college_subjects')->where('deleted','0')->where('subjDesc',$subjDesc)->first();

        // return $subject;

        DB::table('college_prospectus')
            ->where('id',$subject)
            ->update(['deleted'=>'1']);

        return 'done';


    }

    // public static function storesections(Request $request){

    //     return $request->all();

    //     $getExploded = explode("/",url()->previous());

    //     $courseDesc =  strtoupper( str_replace("-"," ",end($getExploded)));
    //     $yearID =  strtoupper( str_replace("-"," ",$request->get('yearID')));
    //     $semesterDesc =  strtoupper( str_replace("-"," ",$request->get('semesterID')));

    //     $courseID = DB::table('college_courses')->where('deleted','0')->where('courseDesc',$courseDesc)->first();
    //     $yearID = DB::table('college_year')->where('deleted','0')->where('yearDesc',$yearID)->first();
    //     $semesterID = DB::table('semester')->where('deleted','0')->where('semester',$semesterDesc)->first();

    //     if(isset($yearID->id)){
    //         $request->merge(['yearID'=>$yearID->id]);
    //     }
    //     if(isset($subjectID->id)){
    //         $request->merge(['subjectID'=>$subjectID->id]);
    //     }
    //     if(isset($courseID->id)){
    //         $request->merge(['courseID'=>$courseID->id]);
    //     }
    //     if(isset($semesterID->id)){
    //         $request->merge(['semesterID'=>$semesterID->id]);
    //     }

    //     $data = collect($request->all())->toArray();

    //     $rules = [
    //         'yearID'=>'required',
    //         'curriculum'=>'required',
    //         'sectionName'=>[
    //             'required',
    //             Rule::unique('college_sections','sectionName')->where(function($query){
    //                 return  $query->where('deleted','0');
    //             })
    //         ],
    //         'semesterID'=>'required',
    //     ];

    //     $message = [
    //         'yearID.required'=>'YEAR is required.',
    //         'sectionName.required'=>'SECTION NAME is required.',
    //         'semesterID.required'=>'SEMESTERS is required.',
    //         'sectionName.unique'=>'SECTION NAME already exists.',
           
    //     ];

    
    
    //     $sectionID = self::proccessstore('college_sections',$data,$rules,$message,'courses/show/'.end($getExploded),'sectionModal',true);

   
    //     if(gettype($sectionID) == 'object'){
         
    //         return $sectionID;

    //     };


    //     $prospectussubjects = DB::table('college_prospectus')
    //                     ->where('college_prospectus.deleted','0')
    //                     ->where('courseID',$courseID->id)
    //                     ->where('yearID',$yearID->id)
    //                     ->where('semesterID',$semesterID->id)
    //                     ->select('college_prospectus.subjectID','college_prospectus.subjectUnit')
    //                     ->get();

    //     foreach($prospectussubjects as $prospectussubject){

    //         DB::table('college_classsched')->insert([
    //             'syID'=>'1',
    //             'semesterID'=>'1',
    //             'sectionID'=> $sectionID,
    //             'teacherID'=>null,
    //             'subjectID'=>$prospectussubject->subjectID,
    //             'subjectUnit'=>$prospectussubject->subjectUnit
    //             ]);

    //     }


    // }


    public static function viewenrollement (){

        $students = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.firstname',
                            'studinfo.lastname'
                        )
                        ->take(10)
                        ->get();

       

        return view('collegeportal.pages.enrollement.viewenrollements')
                        ->with('students',$students);
                       


    }
    
    public static function showenrollement($studid, $studname){

        $getExploded = explode("/",url()->current());

        $studid = (int)$studid;
      
        $student = DB::table('studinfo')
                    ->join('college_enrolledstud',function($join){
                        $join->on('studinfo.id','=','college_enrolledstud.studid');
                        $join->where('deleted','0');
                    })
                    ->join('college_sections',function($join){
                        $join->on('college_enrolledstud.sectionID','=','college_sections.id');
                    })
                    ->select(
                        'college_sections.sectionName',
                        'studinfo.id',
                        'studinfo.firstname',
                        'studinfo.lastname',
                        'college_enrolledstud.studstatus',
                        'college_enrolledstud.id as enid',
                        'studinfo.sid'
                    )
                    ->where('studinfo.id',$studid)
                    ->first();

        $enrolled = false;

        if(isset($student->studstatus) && $student->studstatus == 1){

            $schedules = DB::table('college_studsched')
                        ->join('college_classsched',function($join){
                            $join->on('college_studsched.schedid','=','college_classsched.id');
                        })
                        ->join('college_sections',function($join){
                            $join->on('college_classsched.sectionID','=','college_sections.id');
                        })
                        ->join('college_subjects',function($join){
                            $join->on('college_classsched.subjectID','=','college_subjects.id');
                        })
                        ->select(
                            'college_subjects.subjCode',
                            'college_subjects.subjDesc',
                            'college_sections.sectionName',
                            'college_studsched.id as id',
                            'college_studsched.deleted as deleted',
                            'college_classsched.subjectUnit'
                        )
                        ->where('enrollid',$student->enid)
                        ->get();

            $enrolled = true;
            

        }
        else{

            $student = DB::table('studinfo')
                            ->where('studinfo.id',$studid)
                            ->first();

            $schedules = array();
        }

        $sections = DB::table('college_sections')->get();
        
       

        return view('collegeportal.pages.enrollement.viewenrollement')
                    ->with('enrolled',$enrolled)
                    ->with('sections',$sections)
                    ->with('schedules',$schedules)
                    ->with('student',$student);
                    

    }

    public static function sectscshed($section){


        $schedules = DB::table('college_classsched')
                    ->where('sectionID',$section)
                    ->join('college_prospectus',function($join){
                        $join->on('college_prospectus.id','=','college_classsched.subjectID');
                        $join->where('college_prospectus.deleted','0');
                    })
                    ->select(
                        'college_classsched.subjDesc',
                        'college_classsched.subjCode',
                        'college_classsched.subjectUnit',
                        'college_classsched.id as subjectID',
                        'college_classsched.id',
                        'college_classsched.lecunits',
                        'college_classsched.labunits'
                    )
                    ->get();


        return view('collegeportal.pages.tables.enrollmentsched')
                        ->with('schedules',$schedules);
                        
                        

    }

    public static function enrollstudentsection(Request $request, $student , $section){

   
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
                        'college_sections.sectionName',
                        'studinfo.id',
                        'studinfo.firstname',
                        'studinfo.lastname',
                        'college_enrolledstud.studstatus',
                        'college_enrolledstud.id as enid',
                        'studinfo.sid'
                    )
                    ->where('studinfo.id',$student)
                    ->first();

        return view('collegeportal.pages.cards.enrollmentinfo')->with('student',$student);

    }

    
    public function showfacultystaff($teacher){

        $names  = explode('-cl-',$teacher);

        $lastname = strtoupper(str_replace('-',' ',$names[0]));
        $firstname = strtoupper(str_replace('-',' ',$names[1]));


  

        $teacherInfo = DB::table('teacher as fasteacher')
                        ->where('fasteacher.firstname',$firstname)
                        ->where('fasteacher.lastname',$lastname)

                        ->join('college_teachercourse',function($join){
                            $join->on('fasteacher.id','=','college_teachercourse.teacherID');
                            $join->where('college_teachercourse.deleted','0');
                        })
                        ->join('college_courses',function($join){
                            $join->on('college_teachercourse.courseID','=','college_courses.id');
                            $join->where('college_courses.deleted','0');
                            $join->leftJoin('teacher as courserchairperson',function($join){
                                $join->on('college_courses.courseChairman','=','courserchairperson.id');
                                $join->where('college_courses.deleted','0');
                            });
                        })
                        ->join('college_colleges',function($join){
                            $join->on('college_courses.collegeid','=','college_colleges.id');
                            $join->where('college_colleges.deleted','0');
                            $join->leftJoin('teacher as collegedean',function($join){
                                $join->on('college_colleges.dean','=','collegedean.id');
                                $join->where('college_colleges.deleted','0');
                            });
                        })
                        ->select(
                            'fasteacher.firstname',
                            'fasteacher.lastname',
                            'fasteacher.id',

                            'courserchairperson.firstname as chairpersonfirstname',
                            'courserchairperson.lastname as chairpersonlastname',

                            'collegedean.firstname as deanfirstname',
                            'collegedean.lastname as deanlastname',

                            'college_courses.courseabrv',
                            'college_colleges.collegeabrv',
                            'college_courses.id as courseid',
                            'college_courses.collegeid as collegeid',
                            'college_colleges.dean as collegeDean',
                            'college_courses.courseChairman as courseChairPerson'
                        )
                        ->first();

        $TSP = DB::table('college_teachersubjects')
                    ->where('teacherID',$teacherInfo->id)
                    ->join('college_subjects',function($join){
                        $join->on('college_teachersubjects.subjID','=','college_subjects.id');
                        $join->where('college_teachersubjects.deleted','0');
                    })
                    ->select('subjDesc','college_subjects.id')
                    ->get();

            
        $inputs1 = array(
            (object)['name'=>'subjID[]','type'=>'select','table'=>'college_subjects','label'=>'SUBJECTS','value'=>null,'attr'=>'multiple','selectValue'=>'subjDesc'],
        );

        $modalInfo1 = (object)[
            'modalName'=>'subjectModal',
            'modalheader'=>'SUBJECTS',
            'method'=>'/admin/college/store/teachersubjects',
            'crud'=>'CREATE'];

        $courses = DB::table('college_courses')->where('collegeid',$teacherInfo->collegeid)->get();

        // $inputs2 = array(
        //     (object)['name'=>'id','type'=>'select','table'=>null,'label'=>'COURSES','value'=>null,'selectValue'=>'courseDesc','data'=>$courses],
        // );

        // $modalInfo2 = (object)[
        //     'modalName'=>'coursesModal',
        //     'modalheader'=>'COURSES',
        //     'method'=>'/admin/college/assign/chairperson',
        //     'crud'=>'UPDATE'];


        $inputArray = array();

        array_push($inputArray,
            [$inputs1 , $modalInfo1 ]
            // [$inputs2 , $modalInfo2 ]
        );

       

       
        return view('collegeportal.pages.facultystaff.viewfacultystaff')
                            ->with('TSP',$TSP)
                            ->with('inputArray',$inputArray)
                            ->with('teacherInfo',$teacherInfo);

    }

    public function storeteachersubjects(Request $request){

        $getExploded = explode("/",url()->previous());

        $names  = explode('-cl-',end($getExploded));

        $lastname = strtoupper(str_replace('-',' ',$names[0]));
        $firstname = strtoupper(str_replace('-',' ',$names[1]));

        $teacherInfo = DB::table('teacher')
                        ->where('firstname',$firstname)
                        ->where('lastname',$lastname)
                        ->select('id')
                        ->first();

        if($request->get('subjID') == null){

            $data = collect($request->get('subjID'))->toArray();

            $validator = Validator::make($data,['subjID[]'=>'required'],['subjID[].required'=>'SUBJECT is required']);

            $validator->errors()->add('modalName', 'subjectModal');

            return redirect('facultystaff/college/show/'.end($getExploded))->withErrors($validator)->withInput();

        }

        foreach($request->get('subjID') as $item){

            $subjDesc  = strtoupper(str_replace('-',' ',$item));

            $subjID = DB::table('college_subjects')->where('subjDesc',$subjDesc)->where('deleted','0')->select('id')->first();
        
            DB::table('college_teachersubjects')
                        ->updateOrInsert(
                            ['teacherID'=>$teacherInfo->id,'subjID'=>$subjID->id,],
                            ['deleted'=>'0']
                        );


        }

        return redirect('facultystaff/college/show/'.end($getExploded));

    }

    public static function removeteachersubject($subject){

        $getExploded = explode("/",url()->previous());

        $names  = explode('-cl-',end($getExploded));

        $lastname = strtoupper(str_replace('-',' ',$names[0]));
        $firstname = strtoupper(str_replace('-',' ',$names[1]));

        $teacherInfo = DB::table('teacher')
                        ->where('firstname',$firstname)
                        ->where('lastname',$lastname)
                        ->select('id')
                        ->first();

        $subjDesc = strtoupper(str_replace('-',' ',$subject));

        $subjID = DB::table('college_subjects')
                    ->where('subjDesc',$subjDesc)->where('deleted','0')
                    ->select('id')->first();

        DB::table('college_teachersubjects')
                    ->where('subjID',$subjID->id)
                    ->where('teacherID',$teacherInfo->id)
                    ->update(['deleted'=>'1']);

        toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');
        return redirect('facultystaff/college/show/'.end($getExploded));

    }

    public static function assignchairperson(){

        $getExploded = explode("/",url()->previous());

        $names  = explode('-cl-',end($getExploded));

        $lastname = strtoupper(str_replace('-',' ',$names[0]));
        $firstname = strtoupper(str_replace('-',' ',$names[1]));

        $teacherInfo = DB::table('teacher')
                        ->join('college_teachercourse',function($join){
                            $join->on('teacher.id','=','college_teachercourse.teacherID');
                            $join->where('college_teachercourse.deleted','0');
                        })
                        ->where('firstname',$firstname)
                        ->where('lastname',$lastname)
                        ->select(
                            'teacher.id',
                            'teacher.userid',
                            'college_teachercourse.courseID'
                        )
                        ->first();
        
        DB::table('college_courses')->where('id',$teacherInfo->courseID)->update(['courseChairman'=>$teacherInfo->id]);
        DB::table('teacher')->where('id',$teacherInfo->id)->update(['usertypeid'=>'16']);
        DB::table('users')->where('id',$teacherInfo->userid)->update(['type'=>'16']);

        DB::table('faspriv')->insert([
            'userid'=>$teacherInfo->userid,
            'usertype'=>'16',
            'privelege'=>'2',
            'deleted'=>'0'
        ]);

        return redirect('facultystaff/college/show/'.end($getExploded));
        
    }

    public static function removechairperson(){

        $getExploded = explode("/",url()->previous());

        $names  = explode('-cl-',end($getExploded));

        $lastname = strtoupper(str_replace('-',' ',$names[0]));
        $firstname = strtoupper(str_replace('-',' ',$names[1]));

        $teacherInfo = DB::table('teacher')
                        ->join('college_teachercourse',function($join){
                            $join->on('teacher.id','=','college_teachercourse.teacherID');
                            $join->where('college_teachercourse.deleted','0');
                        })
                        ->where('firstname',$firstname)
                        ->where('lastname',$lastname)
                        ->select(
                            'teacher.id',
                            'teacher.userid',
                            'college_teachercourse.courseID'
                        )
                        ->first();
        
        DB::table('college_courses')->where('id',$teacherInfo->courseID)->update(['courseChairman'=>null]);
        DB::table('teacher')->where('id',$teacherInfo->id)->update(['usertypeid'=>'1']);
        DB::table('users')->where('id',$teacherInfo->userid)->update(['type'=>'1']);

        DB::table('faspriv')
                ->where('userid',$teacherInfo->userid)
                ->where('usertype','16')
                ->update([
                    'deleted'=>'1'
                ]);

        return redirect('facultystaff/college/show/'.end($getExploded));
        
    }

    public static function assigndean(){

        $getExploded = explode("/",url()->previous());

        $names  = explode('-cl-',end($getExploded));

        $lastname = strtoupper(str_replace('-',' ',$names[0]));
        $firstname = strtoupper(str_replace('-',' ',$names[1]));

        $teacherInfo = DB::table('teacher')
                        ->join('college_teachercourse',function($join){
                            $join->on('teacher.id','=','college_teachercourse.teacherID');
                            $join->where('college_teachercourse.deleted','0');
                        })
                        ->join('college_courses',function($join){
                            $join->on('college_teachercourse.courseID','=','college_courses.id');
                            $join->where('college_courses.deleted','0');
                        })
                        ->where('firstname',$firstname)
                        ->where('lastname',$lastname)
                        ->select(
                            'teacher.id',
                            'teacher.userid',
                            'college_courses.collegeid'
                        )
                        ->first();

        DB::table('college_colleges')->where('id',$teacherInfo->collegeid)->update(['dean'=>$teacherInfo->id]);
        DB::table('teacher')->where('id',$teacherInfo->id)->update(['usertypeid'=>'14']);
        DB::table('users')->where('id',$teacherInfo->userid)->update(['type'=>'14']);

        DB::table('faspriv')->insert([
            'userid'=>$teacherInfo->userid,
            'usertype'=>'14',
            'privelege'=>'2',
            'deleted'=>'0'
        ]);
                
        return redirect('facultystaff/college/show/'.end($getExploded));
    }

    public static function removeDean(){

        $getExploded = explode("/",url()->previous());

        $names  = explode('-cl-',end($getExploded));

        $lastname = strtoupper(str_replace('-',' ',$names[0]));
        $firstname = strtoupper(str_replace('-',' ',$names[1]));

        $teacherInfo = DB::table('teacher')
                        ->join('college_teachercourse',function($join){
                            $join->on('teacher.id','=','college_teachercourse.teacherID');
                            $join->where('college_teachercourse.deleted','0');
                        })
                        ->join('college_courses',function($join){
                            $join->on('college_teachercourse.courseID','=','college_courses.id');
                            $join->where('college_courses.deleted','0');
                        })
                        ->where('firstname',$firstname)
                        ->where('lastname',$lastname)
                        ->select(
                            'teacher.id',
                            'teacher.userid',
                            'college_courses.collegeid'
                        )
                        ->first();

        DB::table('college_colleges')->where('id',$teacherInfo->collegeid)->update(['dean'=>null]);
        DB::table('teacher')->where('id',$teacherInfo->id)->update(['usertypeid'=>'1']);
        DB::table('users')->where('id',$teacherInfo->userid)->update(['type'=>'1']);

        DB::table('faspriv')
                ->where('userid',$teacherInfo->userid)
                ->where('usertype','14')
                ->update([
                    'deleted'=>'1'
                ]);
                
        return redirect('facultystaff/college/show/'.end($getExploded));
    }

    public function subjecttable($course){

        $course = DB::table('college_courses')
                    ->where('courseDesc',strtoupper(str_replace('-',' ',$course)))
                    ->first();

        $subjects = DB::table('college_subjects')
                        ->where('subjCourse',$course->id)
                        ->where('deleted','0')
                        ->get();

        return view('deanportal.pages.course.subjectstable')->with('subjects',$subjects);

    }

    public static function courseprospectus(Request $request,$course){

        $course = DB::table('college_courses')
                    ->where('college_courses.deleted',0)
                    ->where('courseDesc',strtoupper(str_replace('-',' ',$course)))
                    ->first();

       

        if(isset($course->id)){

            $prospectus = DB::table('college_prospectus')
                        ->where('courseid',$course->id)
                        ->where('college_prospectus.deleted','0')
                        ->where('college_prospectus.curriculumID',$request->get('curriculum'))
                        ->leftJoin('college_subjprereq',function($join){
                            $join->on('college_prospectus.id','=','college_subjprereq.subjid');
                            $join->where('college_prospectus.deleted',0);
                        })
                        ->join('college_semester',function($join){
                            $join->on('college_prospectus.semesterID','=','college_semester.id');
                            $join->where('college_semester.deleted','0');

                        })
                        ->join('gradelevel',function($join){
                            $join->on('college_prospectus.yearID','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->groupby('college_prospectus.id')
                        ->select(
                            'college_prospectus.*',
                            'description',
                            'gradelevel.id as gid',
                            'college_subjprereq.id as perID'
                        )
                        ->get();
        }
        

        return $prospectus;
    }

    public function courseprospectustable(Request $request,$course){

        $prospectus = self::courseprospectus($request,$course);


        return view('deanportal.pages.course.prospectustable')->with('prospectus',$prospectus);
    }

    public function prospectussubject(Request $request,$course,$subject){

        $prospectus = collect(self::courseprospectus($request,$course))->where('subjDesc',strtoupper(str_replace('-',' ',$subject)));

        $isArray = true;

        try{

            return array($prospectus[0]);

        }

        catch (\Exception $e){

            foreach($prospectus as $item){
                return array($item);
            }


        }

    }


    public function  updateprospectus(Request $request,$id){

        $getExploded = explode("/",url()->previous());

        $courseDesc =  strtoupper( str_replace("-"," ",end($getExploded)));

        $subjDesc =  $request->get('subjDesc');

        $course = DB::table('college_courses')
                    ->where('courseDesc',strtoupper(str_replace('-',' ',$courseDesc)))
                    ->first();

        if(isset($course->id)){
            $request->merge(['courseID'=>$course->id]);
        }
     
        $data = collect($request->all())->forget('prereq')->forget('_token')->toArray();

        $rules = [
            'courseID'=>'required',
            'subjDesc'=>[
                'required',
                Rule::unique('college_prospectus','subjDesc')->where(function($query) use($request){
                    return  $query->where('deleted','0');
                })
                ->where('courseID',$course->id)
                ->where('courseID',$request->get('courseID'))
                ->where('curriculumid',$request->get('curriculumID'))
                ->ignore($id,'id')
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
                    ->where('id',$id)
                    ->update([
                        'yearID'=>$request->get('yearID'),
                        'semesterID'=>$request->get('semesterID'),
                        'subjDesc'=>$request->get('subjDesc'),
                        'subjCode'=>$request->get('subjCode'),
                        'subjClass'=>$request->get('subjClass'),
                        'lecunits'=>$request->get('labunits'),
                        'curriculumID'=>$request->get('curriculum'),
                        'courseID'=>$request->get('courseID'),
                    ]);

            return array((object)[
                'status'=>1,
            ]);

        }

        
    }

    public function getcourse(Request $request){

        try{

            $adminitmodule = DB::table('modules_enable')->where('description','adminit')->first();

        }catch (\Exception $e) {

            $adminitmodule = (object)[
                'create'=>1,
                'updated'=>1,
                'delete'=>1,
            ];

        }

        $withCurriculum = false;
        $withEnrolledStud = false;

        $course = DB::table('college_courses')
                    ->where('college_courses.deleted','0');

        if($request->get('course') != null && $request->has('course')){

            $courseDesc = strtoupper(str_replace('-',' ',$request->get('course')));

            $course->where(function($query) use ($request,$courseDesc){
                $query->where('courseDesc',$courseDesc);
                $query->orWhere('id',$request->get('course'));
            });

        }

        if($request->get('curriculumcount') == 'curriculumcount' && $request->has('curriculumcount')){

            $curriculumCount = DB::table('college_curriculum')->where('courseID',$course->first()->id)->count();

            return $curriculumCount;

        }
        else if($request->get('curriculum') == 'curriculum' && $request->has('curriculum')){

            $withCurriculum = true;

        }

        if($request->get('enrolled') == 'enrolled' && $request->has('enrolled')){
            
            $withEnrolledStud = true;

        }
        else if($request->get('enrolledCount') == 'enrolledCount' && $request->has('enrolledCount')){

            $studentCount = DB::table('studinfo')
                        ->where('studstatus',1)
                        ->where('preEnrolled',0)
                        ->where('studinfo.deleted',0)
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.acadprogid',6);
                        })
                        ->where('courseid',$course->first()->id)
                        ->select('studinfo.id')
                        ->count();

            return $studentCount;

        }

        if($request->get('college') != null && $request->has('college')){

            $college = strtoupper(str_replace('-',' ',$request->get('college')));

            $getCollege = DB::table('college_colleges')
                            ->where('college_colleges.collegeDesc',$college)
                            ->where('deleted','0')
                            ->first();

            $course = $course->where('collegeid',$getCollege->id);

        }
        else if($request->get('colleges') != null && $request->has('colleges')){
            
            $course->whereIn('collegeid',explode(',',$request->get('colleges')));

        }

        if($request->get('search') != null && $request->has('search')){

            $course->where(function($query) use($request){

                $query->where('courseDesc','like','%'.$request->get('search').'%');
                $query->orWhere('courseabrv','like','%'.$request->get('search').'%');

            });

        }

        if($request->get('pagenum') != null && $request->has('pagenum')){

            $course->skip(( $request->get('pagenum') - 1 ) * $request->get('take'));

        }


        if($request->get('take') != null && $request->has('take')){

            $course->take(10);

        }

        if($request->get('table') == 'table' && $request->has('table')){

            if(auth()->user()->type == 14){

                $status = 1;

            }
            else{

                $status = 0;

            }

            return view('collegeportal.pages.colleges.coursetable')
                            ->with('courses',$course->get())
                            ->with('status',$status)
                            ->with('withCurriculum',$withCurriculum)
                            ->with('withEnrolledStud',$withEnrolledStud);

        }
        else if($request->get('count') == 'count' && $request->has('count')){

            return $course->count();

        }
        else if($request->get('info') == 'info' && $request->has('info')){

            return $course->get();

        }

        else if($request->get('delete') == 'delete' && $request->has('delete')){

            if($adminitmodule->delete == 1){

                $course->update(['deleted'=>'1']);

                return 1;

            } else{

                return response()->json(['error' => 'Error msg'], 404); 
                
            }
         

        }

        else if($request->get('update') == 'update' && $request->has('update')){

            $data = $request->all();

            $message = [
                'courseDesc.unique'=>'COURSE already exists.',
                'courseDesc.required'=>'COURSE DESCRIPTION is required',
                'collegeid.required'=>'COLLGE is required',
            ];

            $rules = [
                'courseDesc'=>[
                    'required' ,  
                    Rule::unique('college_courses','courseDesc')->where(function($query){
                        return  $query->where('deleted','0');
                    })->ignore($course->first()->id,'id')
                ],
                'courseabrv'=>'required' 
            ];

            $validator = Validator::make($data, $rules, $message);

            if ($validator->fails()) {

                return array(
                        (object)[
                            'status'=>0,
                            'errors'=>$validator->errors()
                        ]);
            }
            else{

                if($adminitmodule->update == 1){

                    $course->update([
                        'courseDesc'=>$request->get('courseDesc'),
                        'courseabrv'=>$request->get('courseabrv')
                    ]);

                    return array(
                        (object)[
                            'status'=>1,
                            'inputs'=>$data
                        ]);

                }else{

                    return response()->json(['error' => 'Error msg'], 404); 

                }
            }

        }

        else if($request->get('create') == 'create' && $request->has('create')){

            $data = $request->all();

            $message = [
                'courseDesc.unique'=>'COURSE already exists.',
                'courseDesc.required'=>'COURSE DESCRIPTION is required',
                'collegeid.required'=>'COLLGE is required',
            ];

            $rules = [
                'courseDesc'=>[
                    'required' ,  
                    Rule::unique('college_courses','courseDesc')->where(function($query){
                        return  $query->where('deleted','0');
                    })
                ],
                'courseabrv'=>'required' 
            ];

            $validator = Validator::make($data, $rules, $message);

            if ($validator->fails()) {

                return array(
                        (object)[
                            'status'=>0,
                            'errors'=>$validator->errors()
                        ]);
            }
            else{


                if($adminitmodule->create == 1){

                    $college = DB::table('college_colleges')
                                    ->where('collegeDesc',strtoupper(str_replace('-',' ',$request->get('college'))))
                                    ->where('deleted','0')
                                    ->first()->id;

                    DB::table('college_courses')->insert([
                        'courseDesc'=>$request->get('courseDesc'),
                        'courseabrv'=>$request->get('courseabrv'),
                        'collegeid'=>$college
                    ]);

                    return array(
                        (object)[
                            'status'=>2,
                            'inputs'=>$data
                        ]);

                }
                else{

                    return response()->json(['error' => 'Error msg'], 404); 

                }

            }

        }

      



        }


        public function getcolleges(Request $request){

            try{

                $adminitmodule = DB::table('modules_enable')->where('description','adminit')->first();
    
            }catch (\Exception $e) {
    
                $adminitmodule = (object)[
                    'create'=>1,
                    'updated'=>1,
                    'delete'=>1,
                ];
    
            }

            $colleges = DB::table('college_colleges')->where('deleted','0');

          
            if($request->get('college') != null && $request->has('college')){
            
                $collegeDesc = strtoupper(str_replace('-',' ',$request->get('college')));

                $colleges->where('collegeDesc',$collegeDesc);

            }

            // return $colleges->get();

            if($request->get('table') == 'table' && $request->has('table')){

                return view('collegeportal.pages.colleges.collegetable')->with('colleges',$colleges->get());
    
            }
            else if($request->get('info') == 'info' && $request->has('info')){
    
                return $colleges->get();
    
            }

            else if($request->get('blade') == 'blade' && $request->has('blade')){
    
           
                
    
            }

    
    
            else if($request->get('delete') == 'delete' && $request->has('delete')){
    
                if($adminitmodule->delete == 1){
                
                    $collegeid = DB::table('college_colleges')
                                        ->where('deleted','0')
                                        ->where('collegeDesc',$collegeDesc)
                                        ->first()->id;

                    $colleges->update(['deleted'=>'1']);

                    DB::table('college_courses')
                            ->where('collegeid',$collegeid)
                            ->update(['deleted'=>'1']);
        
                    return 1;
                }
                else{

                    return response()->json(['error' => 'Error msg'], 404); 

                }
            }

            else if($request->get('update') == 'update' && $request->has('update')){

                $data = $request->all();
    
                $message = [
                    'collegeDesc.unique'=>'COLLEGE already exists.',
                    'collegeDesc.required'=>'COLLEGE DESCRIPTION is required',
                    'collegeabrv.required'=>'Abbreviation is required',
                ];
    
                $rules = [
                    'collegeDesc'=>[
                        'required' ,  
                        Rule::unique('college_colleges','collegeDesc')->where(function($query){
                            return  $query->where('deleted','0');
                        })->ignore($colleges->first()->id,'id')
                    ],
                    'collegeabrv'=>'required' 
                ];
    
              

                $validator = Validator::make($data, $rules, $message);
    
                if ($validator->fails()) {
    
                    return array(
                            (object)[
                                'status'=>0,
                                'errors'=>$validator->errors()
                            ]);
                }
                else{

                    if($adminitmodule->update == 1){
        
                        $colleges->update([
                            'collegeDesc'=>$request->get('collegeDesc'),
                            'collegeabrv'=>$request->get('collegeabrv')
                        ]);
        
                        return array(
                            (object)[
                                'status'=>1,
                                'inputs'=>$data
                            ]);
                    }
                    else{

                        return response()->json(['error' => 'Error msg'], 404); 
    
                    }
        
                }
    
            }
    
            else if($request->get('create') == 'create' && $request->has('create')){
    
                $data = $request->all();
    
                $message = [
                    'collegeDesc.unique'=>'COLLEGE already exists.',
                    'collegeDesc.required'=>'COLLEGE DESCRIPTION is required',
                    'collegeabrv.required'=>'Abbreviation is required',
                ];
    
                $rules = [
                    'collegeDesc'=>[
                        'required' ,  
                        Rule::unique('college_colleges','collegeDesc')->where(function($query){
                            return  $query->where('deleted','0');
                        })
                    ],
                    'collegeabrv'=>'required' 
                ];
    
                $validator = Validator::make($data, $rules, $message);
    
                if ($validator->fails()) {
    
                    return array(
                            (object)[
                                'status'=>0,
                                'errors'=>$validator->errors()
                            ]);
                }
                else{

                    if($adminitmodule->create == 1){
    
                        DB::table('college_colleges')->insert([
                            'collegeDesc'=>$request->get('collegeDesc'),
                            'collegeabrv'=>$request->get('collegeabrv'),
                        
                        ]);
        
                        return array(
                            (object)[
                                'status'=>2,
                                'inputs'=>$data
                            ]);

                    }
                    else{

                        return response()->json(['error' => 'Error msg'], 404); 
    
                    }
    
                }
    
            }

        }


        public function collegeschedule(Request $request){

            $datatime = Carbon::now('ASia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');

            $sched = DB::table('college_classsched')
                    ->where('college_classsched.deleted',0)
                    ->where('syid',$request->get('syid'))
                    ->where('college_classsched.semesterID',$request->get('semid'));

          
            if($request->has('scheddetail') && $request->get('scheddetail') == 'scheddetail'){

                $sched->leftJoin('college_scheddetail',function($join){
                    $join->on('college_classsched.id','=','college_scheddetail.headerid');
                    $join->where('college_scheddetail.deleted',0);

                });


                if($request->has('scheddetailid') && $request->get('scheddetailid') != null){

                    $sched->where('college_scheddetail.id',$request->get('scheddetailid'));
    
                }

            }

            if($request->has('schedid') && $request->get('schedid') != null){

                $sched->where('college_classsched.id',$request->get('schedid'));

            }
   
            if($request->has('remove') && $request->get('remove') == 'remove'){


                if($request->has('instructor') && $request->get('instructor') == 'instructor'){

                    DB::table('college_classsched')
                            ->where('id',$request->get('schedid'))
                            ->where('sectionID',$request->get('sectionid'))
                            ->update([
                                'teacherID'=>null,
                                'college_classsched.updateddatetime'=>$datatime,
                                'updatedby'=>auth()->user()->id
                            ]);

                  

                            return 1;

                }
                else{
                    
                    if($sched->count() > 0 && ( $request->has('sectionid') && $request->get('sectionid') != null ) ){

                        $datatime = Carbon::now('ASia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');

                        $sectionID = $request->get('sectionid');
                        
                        $scheddetails = DB::table('college_scheddetail')
                                            ->join('college_classsched',function($join) use($sectionID){
                                                $join->on('college_scheddetail.headerID','=','college_classsched.id');
                                                $join->where('college_classsched.sectionID',$sectionID);
                                            })
                                            ->where('college_scheddetail.deleted','0')
                                            ->where('college_scheddetail.stime',$sched->first()->stime)
                                            ->where('college_scheddetail.etime',$sched->first()->etime)
                                            ->where('college_scheddetail.headerid',$sched->first()->headerID)
                                            ->where('college_scheddetail.scheddetialclass',$sched->first()->scheddetialclass)
                                            ->update([
                                                'college_scheddetail.deleted'=>'1',
                                                'college_scheddetail.deletedby'=>auth()->user()->id,
                                                'college_scheddetail.deleteddatetime'=>$datatime,
                                                'college_scheddetail.updateddatetime'=>$datatime,
                                                'college_scheddetail.updatedby'=>auth()->user()->id
                                            ]);

                        return 1;
                            
                                
                    }
                    else{

                        return "NO DATA FOUND!";

                    }

                }
               
            }

            

            if($request->has('table') && $request->get('table') == 'table'){
             
                if($request->has('sectionid') && $request->get('sectionid') != null){

                    $classSched =  $sched->where('sectionID',$request->get('sectionid'))
                                ->where('college_classsched.deleted','0')
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
                                    'teacher.lastname'
                                    )
                                ->orderBy('subjCode')
                                ->get();


                    $data = array();

                    foreach($classSched as $item){
                        $item->ftime = $item->stime.' - '.$item->etime;
                    }

                    $bySubject = collect($classSched)->groupBy('id');

                    foreach($bySubject as $subjitem){
            
                        $byClass = collect($subjitem)->groupBy('scheddetialclass');
            
                        foreach($byClass as $item){
                            foreach(collect($item)->groupBy('ftime') as $secondItem){
                                $day = '';

                                $temp_sched = collect($secondItem)->sortBy('daysort');

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
                                    // return $day;
                                }
                                // return collect($secondItem)->sortBy('description');
                                // return $day;

                                $details->description = $day;
                                array_push($data, $details);
                            };
                        }
                    }


                    $classSched = collect($data)->groupBy('id');

                    return view('chairpersonportal.pages.sections.collegeschedtable')->with('classSched',$classSched);
                }


            }
            elseif($request->has('create') && $request->get('create') == 'create'){

                if($request->has('instructor') && $request->get('instructor') == 'instructor'){

                    DB::table('college_classsched')
                        ->where('id',$request->get('schedid'))
                        ->where('sectionID',$request->get('sectionid'))
                        ->update([
                            'teacherID'=>$request->get('instructorid'),
                            'college_classsched.updateddatetime'=>$datatime,
                            'updatedby'=>auth()->user()->id
                        ]);

                    return 1;

                }
                else if($request->has('insertsched') && $request->get('insertsched') == 'insertsched'){

                    return $request->all();


                }


            }
            elseif($request->has('update') && $request->get('update') == 'update'){

                    return "sdsdf";

                    if($sched->count() > 0){

                        $datatime = Carbon::now('ASia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');

                        try{

                            $headerID = $sched->first()->headerID;
                            $scheddetialclass = $sched->first()->scheddetialclass;

                            $scheddetails = DB::table('college_scheddetail')
                                        ->where('college_scheddetail.deleted','0')
                                        ->where('college_scheddetail.headerID',$headerID)
                                        ->where('college_scheddetail.stime',$sched->first()->stime)
                                        ->where('college_scheddetail.etime',$sched->first()->etime)
                                        ->where('college_scheddetail.etime',$sched->first()->etime)
                                        ->where('college_scheddetail.scheddetialclass',$sched->first()->scheddetialclass)
                                        ->select('day','id')
                                        ->get();

                        }catch (\Exception $e){

                            $headerID = $sched->select('college_classsched.*')->first()->id;
                            $scheddetails = array();
                            $scheddetialclass = $request->get('scheddetialclass');

                        }
                        
                      

                       

                        $days = collect(explode(',',$request->get('days')))->toArray();

                        foreach($scheddetails as $item){

                            if(!collect($days)->contains($item->day)){
                             
                                DB::table('college_scheddetail')
                                    ->where('id',$item->id)
                                    ->update([
                                        'college_scheddetail.deleted'=>'1',
                                        'deletedby'=>auth()->user()->id,
                                        'deleteddatetime'=>$datatime
                                    ]);

                            }

                        }

                        $roomName = strtoupper(str_replace('-',' ',$request->get('roomID')));

                        $roomID = null;

                        $checkRoom = DB::table('rooms')
                                    ->where('roomname', $roomName)
                                    ->select('id')
                                    ->first();

                        if(isset($checkRoom->id) ){
                            $roomID = $checkRoom->id;
                        }

                                  

                        foreach($days as $item){

                            DB::table('college_scheddetail')
                                        ->updateOrInsert(
                                            [
                                                'headerID'=>$headerID, 
                                                'day'=>$item,
                                                'scheddetialclass'=>$scheddetialclass
                                            ],
                                            [
                                                'deleted'=>'0',
                                                'roomID'=>$roomID,
                                                'stime'=>$request->get('FROM'),
                                                'etime'=>$request->get('TO'),
                                                
                                            ]);

                        }

                        

                        return 1;
                            
                                
                    }
                    else{

                        return "NO DATA FOUND!";

                    }

      

            }

            elseif($request->has('info') && $request->get('info') == 'info'){

                $sched
                    ->where('college_classsched.deleted','0')
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
                    
                    })->select(
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
                        'college_scheddetail.scheddetialclass',
                        'teacher.firstname',
                        'teacher.lastname',
                        'college_classsched.subjectID'
                    );


                

                $sched = $sched->get();


                if(count($sched)){


                    $days = DB::table('college_scheddetail')
                                    ->where('headerID',$sched[0]->id)
                                    ->where('deleted','0')
                                    ->where('college_scheddetail.scheddetialclass',$sched[0]->scheddetialclass)
                                    ->get();

                    $sched[0]->days = collect($days)->pluck('day')->toArray();

                    return $sched;


                }
                else{


                    return "NO DATA FOUND!";


                }


                

            }
            else{

              
            }
           
           
            

           

        }


    public function collegesections(Request $request){


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

        $sections = DB::table('college_sections')
                    ->where('college_sections.deleted','0')
                    ->whereIn('college_sections.courseID',  $courseId)
                    ->join('college_courses',function($join){
                        $join->on('college_sections.courseID','=','college_courses.id');
                    });



        if($request->has('course') && $request->get('course') != null){

            $sections->where('courseID',$request->get('course'));

        }
        if($request->has('section') && $request->get('section') != null){

            $sections->where('college_sections.id',$request->get('section'));

        }

        if($request->has('info') && $request->get('info') == 'info'){

            return $sections->get();

        }
        else if($request->has('table') && $request->get('table') == 'table'){

            $sections = $sections->select('college_sections.id','college_sections.sectionDesc','college_courses.courseabrv')->orderBy('courseabrv')->orderBy('sectionDesc')->get();


            foreach($sections as $item){

                $count = DB::table('college_enrolledstud')
                            ->where('sectionid', $item->id)
                            ->where('deleted',0)
                            ->whereIn('studstatus',[1,2,4])
                            ->count();
    
                $item->count = $count;
    
            }

            return view('chairpersonportal.pages.sections.sectiontable')->with('sections',$sections);

        }
        else if($request->has('remove') && $request->get('remove') == 'remove'){

            DB::table('college_sections')
                    ->where('id',$request->get('section'))
                    ->update(['college_sections.deleted'=>'1']);

        }
        else if($request->has('update') && $request->get('update') == 'update'){

            $data = $request->all();

            $message = [
                'sectionDesc.unique'=>'SECTION already exists.',
            ];

            $rules = [
               
                'sectionDesc'=>[
                    'required' ,  
                        Rule::unique('college_sections','sectionDesc')->where(function($query){
                            return  $query->where('deleted','0');
                        })->ignore($request->get('section'),'id')
                ],
            ];

            $validator = Validator::make($data, $rules, $message);

            if ($validator->fails()) {

                return array(
                        (object)[
                            'status'=>0,
                            'errors'=>$validator->errors()
                        ]);
            }
            else{

                DB::table('college_sections')
                    ->where('id',$request->get('section'))
                    ->update([
                        'sectionDesc'=>$request->get('sectionDesc'),
                    ]);

                return array(
                    (object)[
                        'status'=>2,
                        'inputs'=>$data
                    ]);

            }

        }

        else if($request->has('create') && $request->get('create') == 'create'){

            $data = $request->all();

            // return $request->all();

            $message = [
                'sectionDesc.unique'=>'SECTION already exists.',
                'gradelevel.required'=>'GRADE LEVEL is required.',
                'course.required'=>'COURSE is required.'
            ];

            $rules = [
                'curriculum'=>'required',
                'sectionDesc'=>[
                    'required' ,  
                        Rule::unique('college_sections','sectionDesc')->where(function($query){
                            return  $query->where('deleted','0');
                        })->ignore($request->get('section'),'id')
                ],
                'course'=>'required',
                'gradelevel'=>'required'
            ];

            $validator = Validator::make($data, $rules, $message);

            if ($validator->fails()) {

                return array(
                        (object)[
                            'status'=>0,
                            'errors'=>$validator->errors()
                        ]);
            }
            else{


                $courseDesc =  strtoupper( str_replace("-"," ",$request->get('course')));
        
                $courseID = DB::table('college_courses')->where('deleted','0')->where('courseDesc',$courseDesc)->first();

                $sectID = DB::table('college_sections')
                            ->where('id',$request->get('section'))
                            ->insertGetId([
                                'curriculumid'=> $request->get('curriculum'),
                                'courseID' => $courseID->id,
                                'semesterID' => DB::table('semester')->where('isactive','1')->first()->id,
                                'syID' => DB::table('sy')->where('isactive','1')->first()->id,
                                'yearID' => $request->get('gradelevel'),
                                'sectionDesc'=>$request->get('sectionDesc'),
                            ]);

                $prospectussubjects = DB::table('college_prospectus')
                        ->where('college_prospectus.deleted','0')
                        ->where('courseID',$courseID->id)
                        ->where('curriculumID',$request->get('curriculum'))
                        ->where('yearID',$request->get('gradelevel'))
                        ->where('semesterID',DB::table('semester')->where('isactive','1')->first()->id)
                        ->select(
                            'college_prospectus.id'
                        )
                        ->get();

                foreach($prospectussubjects as $prospectussubject){

                    DB::table('college_classsched')->insert([
                                'syID'=>DB::table('sy')->where('isactive','1')->first()->id,
                                'semesterID'=>DB::table('semester')->where('isactive','1')->first()->id,
                                'sectionID'=> $sectID,
                                'teacherID'=>null,
                                'subjectID'=>$prospectussubject->id,
                                ]);

                }

                return array(
                    (object)[
                        'status'=>2,
                        'inputs'=>$data
                    ]);

            }

        }

    }

    public function viewprospectus(Request $request){

        $prospectus = DB::table('college_prospectus')
                        ->where('deleted',0);

        if($request->has('transfer') && $request->get('transfer') == 'transfer'){

            if($request->has('prosid') && $request->get('prosid') != null){

                if($request->has('currid') && $request->get('currid') != null){

                    $prospectus->where('id',$request->get('prosid'))
                                    ->update([
                                        'updatedby'=>auth()->user()->id,
                                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD'),
                                        'curriculumID'=>$request->get('currid')
                                    ]);

                }
                

            }

        }
        else if($request->has('info') && $request->get('info') == 'info'){

            if( $request->has('currid') && $request->get('currid') != null ){

                if($request->has('course') && $request->get('course') != null ){

                    $courseDesc = strtoupper(str_replace('-',' ',$request->get('course')));

                    $courseId = DB::table('college_courses')
                                    ->where('deleted',0)
                                    ->where('courseDesc',$courseDesc)
                                    ->first()->id;

                   
                    $prospectus = $prospectus
                                ->where('courseID',$courseId)
                                ->where('curriculumID',$request->get('currid'))
                                ->get();

                    return $prospectus;

                }
                
                


            }

        }

    }


    public function quarterSetup(Request $request){


        $datatime = Carbon::now('ASia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');
       
        $quarterSetup = DB::table('college_quartersetup')
                        ->where('college_quartersetup.deleted','0');


        if($request->get('teacher') != null && $request->has('teacher')){

            $quarterSetup->where('teacherid',$request->get('teacher'));
            
        }

        if($request->get('setup') != null && $request->has('setup')){

            $quarterSetup->where('id',$request->get('setup'));

        }

        if($request->get('table') == 'table' && $request->has('table')){

            $quarterSetup = $quarterSetup->join('college_gradestermsetup',function($join){
                $join->on('college_quartersetup.type','=','college_gradestermsetup.id');
                $join->where('college_gradestermsetup.isactive',1);
                $join->where('college_gradestermsetup.deleted',0);
            })
            ->select('college_gradestermsetup.description as termsetupdesc','college_quartersetup.*');

            return view('ctportal.pages.setup.quartersetuptable')->with('quartersetup',$quarterSetup->get());

        }
        elseif($request->get('info') == 'info' && $request->has('info')){

            return $quarterSetup->get();

        }

       
        
        if($request->get('remove') == 'remove' && $request->has('remove')){

            $checkExist =  DB::table('college_quartersetup')
                                ->where('id',$request->get('setup'))
                                ->where('createdby',auth()->user()->id);


            if($checkExist->count() > 0){

                $checkExist->update([
                                'deleted'=>'1',
                                'deletedby'=>auth()->user()->id,
                                'deleteddatetime'=> $datatime
                            ]);

            }
            else{

                return "YOU ARE NOT AUTHORIZED TO REMOVE THIS DATA";

            }
           

        }
        elseif($request->get('create') == 'create' && $request->has('create')){

            $teacherID = DB::table('teacher')->where('userid',auth()->user()->id)->first()->id;

            DB::table('college_quartersetup')
                ->insert([
                    'teacherid'=> $teacherID,
                    'qsDesc'=>$request->get('qsDesc'),
                    'type'=>$request->get('type'),
                    'semi'=>$request->get('semi'),
                    'mid'=>$request->get('mid'),
                    'pre'=>$request->get('prefi'),
                    'final'=>$request->get('final'),
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>$datatime
                ]);

            return array(
                (object)[
                    'status'=>1,
                ]);

        }
        elseif($request->get('update') == 'update' && $request->has('update')){

            $quarterSetup->where('createdby',auth()->user()->id);

            if($quarterSetup->count() > 0){
    
                $rules = [
                    'qsDesc'=>'required',
                    'type'=>'required',
                ];

                $data = $request->all();
    
                $validator = Validator::make($data, $rules);
               
              
             

                if($request->get('type') == 1){


                }elseif($request->get('type') == 2){

                    $validator->after(function ($validator) use($request){
                        if (($request->get('mid') + $request->get('final')) != 100) {
                            $validator->errors()->add('total', 'Written works, Performance Task and Quarter Assesment should equal to 100');
                        }
                    });

                }
                elseif($request->get('type') == 3){

                    $validator->after(function ($validator) use($request){
                        if (($request->get('semi') + $request->get('mid') + $request->get('prefi') + $request->get('final')) != 100) {
                            $validator->errors()->add('total', 'Written works, Performance Task and Quarter Assesment should equal to 100');
                        }
                    });

                }

                if ($validator->fails()) {

                    return array(
                        (object)[
                            'status'=>0,
                            'errors'=>$validator->errors()
                        ]);

                }
                else{

                    $quarterSetup->update([
                                'qsDesc'=>$request->get('qsDesc'),
                                'type'=>$request->get('type'),
                                'semi'=>$request->get('semi'),
                                'mid'=>$request->get('mid'),
                                'pre'=>$request->get('prefi'),
                                'final'=>$request->get('final'),
                                'updatedby'=>auth()->user()->id,
                                'updateddatetime'=>$datatime
                            ]);

                    return array(
                        (object)[
                            'status'=>1,
                        ]);

                }
            }
            else{

                return "YOU ARE NOT AUTHORIZED TO UPDATED THIS DATA";

            }
           

        }
        elseif($request->get('info') == 'info' && $request->has('info')){

            return $quarterSetup
                    ->where('createdby',auth()->user()->id)
                    ->get();

        }


    }


    public function getcurriculum(Request $request){

        $curriculum = DB::table('college_curriculum')
                            ->where('deleted',0);

        if($request->has('courseid') && $request->get('courseid') != null){

            $curriculum = $curriculum->where('courseID',$request->get('courseid'));

        }

        if($request->has('table') && $request->get('table') == 'table'){

            return view('deanportal.pages.curriculum.curriculumtable')->with('curriculum',$curriculum->get());

        }
        else if($request->has('info') && $request->get('info') == 'info'){

            if($request->has('curid') && $request->get('curid') != null){

                $curriculum = $curriculum->where('id',$request->get('curid'));
    
            }

            return $curriculum->get();

        }
        else if($request->has('setasactive') && $request->get('setasactive') == 'setasactive'){

            DB::table('college_curriculum')
                        ->where('deleted',0)
                        ->where('createdby',auth()->user()->id)
                        ->where('isactive',1)
                        ->where('courseID',$request->get('courseid'))
                        ->update([
                            'isactive'=>0,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                        ]);


            if($request->has('curid') && $request->get('curid') != null){

                $curriculum = $curriculum->where('id',$request->get('curid'));
    
            }
         
            $curriculum->update([
                'isactive'=>$request->get('activestatus'),
                'updatedby'=>auth()->user()->id,
                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
            ]);

        }
        else if($request->has('insert') && $request->get('insert') == 'insert'){

            $countCur = DB::table('college_curriculum')
                            ->where('courseID',$request->get('courseid'))
                            ->where('createdby',auth()->user()->id)
                            ->count();

            if($countCur == 0){

                DB::table('college_curriculum')
                            ->insert([
                                'courseID'=>$request->get('courseid'),
                                'curriculumname'=>$request->get('curDesc'),
                                'createdby'=>auth()->user()->id,
                                'isactive'=>1,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                            ]);

            }else{


                DB::table('college_curriculum')
                        ->insert([
                            'courseID'=>$request->get('courseid'),
                            'curriculumname'=>$request->get('curDesc'),
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                        ]);


            }
                            

           


        }
        else if($request->has('update') && $request->get('update') == 'update'){

            if($request->has('curid') && $request->get('curid') != null){

                $curriculum = $curriculum->where('id',$request->get('curid'));
    
            }
         
            $curriculum->update([
                'curriculumname'=>$request->get('curDesc'),
                'updatedby'=>auth()->user()->id,
                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
            ]);

        }
        else if($request->has('remove') && $request->get('remove') == 'remove'){

            if($request->has('curid') && $request->get('curid') != null){

                $curriculum = $curriculum->where('id',$request->get('curid'));
    
            }
         
            // return $curriculum->get();

            $curriculum->update([
                'deleted'=>1,
                'isactive'=>0,
                'deletedby'=>auth()->user()->id,
                'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
            ]);

        }
       

    }

    public function collegesubjects(Request $request){

        $collegesubjects = DB::table('college_subjects')
                                ->where('college_subjects.deleted',0)
                                ->orderBy('subjDesc');


        // return $request;

        if($request->has('take') && $request->get('take') != null){

            $collegesubjects = $collegesubjects->take($request->get('take'));

        }

        if($request->has('pagenum') && $request->get('pagenum') != null){

            $collegesubjects = $collegesubjects->skip(  ( $request->get('pagenum') - 1 )  *   $request->get('take')    );

        }

        if($request->has('search') && $request->get('search') != null){

            $collegesubjects = $collegesubjects->where(function($query) use($request){
                $query->where('college_subjects.subjDesc','like','%'.$request->get('search').'%');
                $query->orWhere('college_subjects.subjCode','like','%'.$request->get('search').'%');
            });
            

        }

        if($request->has('table') && $request->get('table') == 'table'){

            if($request->has('course') && $request->get('course') != null){

                if($request->has('search') && $request->get('search') != null){

                    $collegesubjects = $collegesubjects->where(function($query) use($request){
                        $query->where('college_subjects.subjDesc','like','%'.$request->get('search').'%');
                        $query->orWhere('college_subjects.subjCode','like','%'.$request->get('search').'%');
                    });


                }



                $courseDesc =  strtoupper( str_replace("-"," ",$request->get('course')));
        
                $courseID = DB::table('college_courses')->where('deleted','0')->where('courseDesc',$courseDesc)->first();

                $collegesubjects = $collegesubjects->leftJoin('college_prospectus',function($join) use ($request, $courseID){

                        $join->on('college_subjects.id','=','college_prospectus.subjectID');
                        $join->where('college_prospectus.deleted',0);
                        $join->where('college_prospectus.courseID',$courseID->id);
                        $join->where('college_prospectus.curriculumID',$request->get('currid'));

                })
                ->select('college_subjects.*','college_prospectus.id as prosid');

            }

            

            if($request->has('status') && $request->get('status') != null){

                
                if($request->has('status') && $request->get('status') == 3){

                    $collegesubjects->leftJoin('college_subjprereq',function($join) use($request){
                        $join->on('college_subjects.id','=','college_subjprereq.prereqsubjID');
                        $join->where('college_subjprereq.subjID',$request->get('cursubjid'));
                        $join->where('college_subjprereq.deleted',0);
                    })
                    ->select('college_subjects.*','college_subjprereq.id as prereqid');

                    return view('deanportal.pages.subjects.subjectstable')
                                    ->with('collegesubjects', $collegesubjects->get())
                                    ->with('status',3);

                }else{


                    return view('deanportal.pages.subjects.subjectstable')
                                    ->with('collegesubjects', $collegesubjects->get())
                                    ->with('status',2);

                }

            }
            else{

                return view('deanportal.pages.subjects.subjectstable')
                            ->with('collegesubjects', $collegesubjects->get())
                            ->with('status',1);
            }

            
            

        }
        elseif($request->has('addtopreq') && $request->get('addtopreq') == 'addtopreq'){

            DB::table('college_subjprereq')
                ->updateOrInsert(
                    [
                        'subjID'=>$request->get('cursubjid'),
                        'prereqsubjID'=>$request->get('subjid')
                    ],
                    [
                        'deleted'=>0
                    ]
                );

            return DB::table('college_subjprereq')
                        ->where('deleted',0)
                        ->where('subjID',$request->get('cursubjid'))
                        ->where('prereqsubjID',$request->get('subjid'))
                        ->get();

        }
        elseif($request->has('removetoprereq') && $request->get('removetoprereq') == 'removetoprereq'){

            DB::table('college_subjprereq')
                ->where('id',$request->get('prereqid'))
                ->where('subjID',$request->get('cursubjid'))
                ->where('prereqsubjID',$request->get('subjid'))
                ->update([
                    'deleted'=>1
                ]);
             

        }

        else if($request->has('insertoprospectus') && $request->get('insertoprospectus') == 'insertoprospectus'){

            $courseDesc =  strtoupper( str_replace("-"," ",$request->get('course')));
        
            $courseID = DB::table('college_courses')->where('deleted','0')->where('courseDesc',$courseDesc)->first();
           
            if($request->has('subjid') && $request->get('subjid') != null){

                $collegesubjects = $collegesubjects->where('id',$request->get('subjid'))->first();
               
                if(isset( $collegesubjects->id )){

                    $checkIfExist = DB::table('college_prospectus')
                                    ->where('subjectID',$collegesubjects->id)
                                    ->where('curriculumID',$request->get('currid'))
                                    ->where('yearID',$request->get('yearid'))
                                    ->where('semesterID',$request->get('semID'))
                                    ->where('courseID',$courseID->id)
                                    ->first();

                    if(isset( $checkIfExist->id)){

                        DB::table('college_prospectus')
                                    ->where('id',$checkIfExist->id)
                                    ->update([
                                        'deleted'=>0,
                                        'updatedby'=>auth()->user()->id,
                                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                                    ]);

                        return $checkIfExist->id;

                    }
                    else{

                        

                        $prosid = DB::table('college_prospectus')
                            ->insertGetId([
                                'subjectID'=> $collegesubjects->id,
                                'subjDesc'=> $collegesubjects->subjDesc,
                                'subjCode'=> $collegesubjects->subjCode,
                                'subjClass'=> $collegesubjects->subjClass,
                                'curriculumID'=> $request->get('currid'),
                                'yearID'=> $request->get('yearid'),
                                'semesterID'=> $request->get('semID'),
                                'labunits'=> $collegesubjects->labunits,
                                'lecunits'=> $collegesubjects->lecunits,
                                'courseID'=>$courseID->id,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                            ]);

                        return $prosid;

                    }

                    

                }


            }


        }
        else if($request->has('removefromprospectus') && $request->get('removefromprospectus') == 'removefromprospectus'){

            DB::table('college_prospectus')
                ->where('id',$request->get('prosid'))
                ->update([
                    'deleted'=>1,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                ]);

            return DB::table('college_prospectus')->where('id',$request->get('prosid'))->get();

        }
        else if($request->has('info') && $request->get('info') == 'info'){

            if($request->has('subjid') && $request->get('subjid') != null){

                return $collegesubjects->where('id',$request->get('subjid'))->get();

            }

        }
        else if($request->has('update') && $request->get('update') == 'update'){

            $data = $request->all();

            DB::table('college_subjects')
                ->where('id',$request->get('subjid'))
                ->update([
                    'subjDesc'=>$request->get('subjDesc'),
                    'subjCode'=>$request->get('subjCode'),
                    'lecunits'=>$request->get('lecunits'),
                    'labunits'=>$request->get('labunits'),
                    'subjClass'=>$request->get('subjclass'),

                ]);

                DB::table('college_prospectus')
                        ->where('subjectID',$request->get('subjid'))
                        ->update([
                            'subjDesc'=>$request->get('subjDesc'),
                            'subjCode'=>$request->get('subjCode'),
                            'lecunits'=>$request->get('lecunits'),
                            'labunits'=>$request->get('labunits'),
                            'subjClass'=>$request->get('subjclass'),
                        ]);

                return array((object)[
                    'status'=>2,
                ]);
        
        }
        else if($request->has('create') && $request->get('create') == 'create'){

            $data = $request->all();

            $rules = [
                'subjDesc'=>[
                    'required' ,  
                ],
                'subjCode'=>'required'
            ];

            $message = [
                'subjDesc.unique'=>'SUBJECT already exists.',
                'subjDesc.required'=>'SUBJECT DESCRIPTION is required',
                'subjCode.required'=>'SUBJECT CODE is required',
            ];

            $validator = Validator::make($data, $rules, $message);

            if ($validator->fails()) {
    
                return array((object)[
                    'status'=>0,
                    'errors'=>$validator->errors()
                ]);
               
            }
            else{

                if($request->get('lecunits') != null){

                    $lecunits = $request->get('lecunits');

                }
                else{

                    $lecunits = 0;

                }

                if($request->get('labunits') != null){

                    $labunits = $request->get('labunits');

                }
                else{

                    $labunits = 0;

                }
    
                DB::table('college_subjects')
                ->insert([
                    'subjDesc'=>$request->get('subjDesc'),
                    'subjCode'=>$request->get('subjCode'),
                    'lecunits'=>$lecunits,
                    'labunits'=>$labunits,
                    'subjClass'=>$request->get('subjclass'),
                ]);

                return array((object)[
                    'status'=>2,
                ]);
    
            }
        
        }
        elseif($request->has('count') && $request->get('count') == 'count'){

            return $collegesubjects->count();


        }



        // return $collegesubjects->get();


    }

    public static function viewstudenttor(Request $request){

        $studentInfo = DB::table('studinfo')
                        ->where('id',$request->get('student'))
                        ->where('deleted',0)
                        ->first();
        
        if(isset($studentInfo->id)){

            //create student tor
            if($request->has('cstor') && $request->get('cstor') == 'cstor'){

                if($request->has('currid') && $request->get('currid') != null){

                    $subjects = DB::table('college_prospectus')
                                ->where('courseID',$studentInfo->courseid)
                                ->get();

                    foreach($subjects as $item){

                        DB::table('college_studentprospectus')
                            ->insert([
                                'studid'=>$studentInfo->id,
                                'prospectusID'=>$item->id,
                                'curriculumID'=>$request->get('currid'),
                                'courseID'=>$studentInfo->courseid,
                                'name'=>$studentInfo->lastname.', '.$studentInfo->firstname,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                            ]);

                    }

                }

            }
            else if($request->has('info') && $request->get('info') == 'info'){

                return DB::table('college_studentprospectus')
                            ->where('studid',$request->get('student'))
                            ->where('college_studentprospectus.prospectusID',$request->get('subjid'))
                            ->get();


            }
            elseif($request->has('updategrade') && $request->get('updategrade') == 'updategrade'){



                DB::table('college_studentprospectus')
                        ->where('id',$request->get('studprosid'))
                        ->where('studid',$studentInfo->id)
                        ->update([
                            'midtermgrade'=>$request->get('midtermgrade'),
                            'finalgrade'=>$request->get('finalgrade'),
                            'remarks'=>$request->get('remarks'),
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                        ]);
                



            }
            else{

                $countToR = DB::table('college_studentprospectus')
                                    ->where('studid',$studentInfo->id)
                                    ->where('college_studentprospectus.deleted',0)
                                    ->where('college_studentprospectus.courseID',$studentInfo->courseid)
                                    ->count();

                if( $countToR > 0){

                    $countToR = DB::table('college_studentprospectus')
                                        ->where('studid',$studentInfo->id)
                                        ->where('college_studentprospectus.courseID',$studentInfo->courseid)
                                        ->join('college_prospectus',function($join){
                                            $join->on('college_studentprospectus.prospectusID','=','college_prospectus.id');
                                            $join->where('college_prospectus.deleted',0);
                                        })
                                        ->where('college_studentprospectus.deleted',0)
                                        ->select('college_studentprospectus.curriculumID')
                                        ->first();

                    $checkForOutDatedSubjects = DB::table('college_prospectus')
                                                ->where('college_prospectus.courseID',$studentInfo->courseid)
                                                ->where('college_prospectus.curriculumID',$countToR->curriculumID)
                                                ->leftJoin('college_studentprospectus',function($join) use($studentInfo){
                                                    $join->on('college_prospectus.id','=','college_studentprospectus.prospectusID');
                                                    $join->where('college_studentprospectus.studid','=',$studentInfo->id);
                                                    $join->where('college_studentprospectus.deleted',0);
                                                })
                                                ->select('college_prospectus.*','college_studentprospectus.studid')
                                                ->where('college_prospectus.deleted',0)
                                                ->get();

                    foreach(collect($checkForOutDatedSubjects)->where('studid',null) as $item){

                        DB::table('college_studentprospectus')
                            ->insert([
                                'studid'=>$studentInfo->id,
                                'prospectusID'=>$item->id,
                                'curriculumID'=>$item->curriculumID,
                                'courseID'=>$item->courseID,
                                'name'=>$studentInfo->lastname.', '.$studentInfo->firstname,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                            ]);

                    }


                }

                $studenttor = DB::table('college_studentprospectus')
                                    ->where('studid',$studentInfo->id)
                                    ->where('college_studentprospectus.deleted',0)
                                    ->where('college_studentprospectus.courseID',$studentInfo->courseid)
                                    ->join('college_prospectus',function($join){
                                        $join->on('college_studentprospectus.prospectusID','=','college_prospectus.id');
                                        $join->where('college_prospectus.deleted',0);
                                    })
                                    ->join('college_semester',function($join){
                                            $join->on('college_prospectus.semesterID','=','college_semester.id');
                                            $join->where('college_semester.deleted','0');
                                    })
                                    ->join('gradelevel',function($join){
                                        $join->on('college_prospectus.yearID','=','gradelevel.id');
                                        $join->where('gradelevel.deleted','0');
                                    })
                                     ->select(
                                        'college_prospectus.*',
                                        'college_studentprospectus.midtermgrade',
                                        'college_studentprospectus.finalgrade',
                                        'college_studentprospectus.remarks',
                                        'college_studentprospectus.id as studprosid',
                                        'description',
                                        'gradelevel.id as gid'
                                        )
                                    ->get();


                return view('chairpersonportal.pages.scheduling.studenttortable')
                        ->with('studenttor',$studenttor);
            }

          

        }
        


      

    }


    public function viewstudentgrades(Request $request){

        $grades = DB::table('college_gradesdetail');

        if(auth()->user()->type == 7){

            $students = DB::table('studinfo')
                                ->where('deleted',0)
                                ->where('userid',auth()->user()->id)
                                ->select('id','sectionid','lastname','firstname')
                                ->get();

            $gradeinfo = array();

        }
        else{

            $studentInfo = DB::table('studinfo')
                            ->where('studinfo.deleted',0)
                            ->join('gradelevel',function($join){
                                $join->on('gradelevel.id','=','studinfo.levelid');
                                $join->where('gradelevel.acadprogid',6);
                                $join->where('gradelevel.deleted',0);
                            });
                           

            if($request->has('subjid') && $request->get('subjid') != null){

                $studentInfo = $studentInfo
                                ->join('college_classsched',function($join){
                                    $join->on('studinfo.sectionid','=','college_classsched.sectionID');
                                    $join->where('college_classsched.deleted',0);
                                })
                                ->join('college_prospectus',function($join) use($request){
                                    $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                    $join->where('college_prospectus.subjectID',$request->get('subjid'));
                                    $join->where('college_prospectus.deleted',0);
                                });

            }

           

            if($request->has('sectionid') && $request->get('sectionid') != null){

                $students = $studentInfo->where('studinfo.sectionid',$request->get('sectionid'))
                                            ->select(   'studinfo.id',
                                                        'studinfo.sectionid',
                                                        'studinfo.lastname',
                                                        'studinfo.firstname')
                                           ->get();
            }

            $gradeinfo = array();

        }

        foreach($students as $studentInfo){

                $studentSubjGrade = array((object)[
                    'name'=>$studentInfo->lastname.', '.$studentInfo->firstname,
                    'prelim'=>null,
                    'midterm'=>null,
                    'semi'=>null,
                    'finalterm'=>null,
                    'finalgrade'=>null,
                    'subject'=>$request->get('subjid')
                ]);

               

                if($request->has('subjid') && $request->get('subjid') != null){

                    $grades =  DB::table('college_gradesdetail')
                                    ->where('studid',$studentInfo->id)
                                    ->join('college_gradesetup',function($join) use($request,$studentInfo){
                                        $join->on('college_gradesdetail.headerid','=','college_gradesetup.id');
                                        $join->where('college_gradesetup.deleted',0);
                                        $join->where('college_gradesetup.subjID',$request->get('subjid'));
                                        $join->where('college_gradesetup.sectionid',$studentInfo->sectionid);
                                    })
                                    ->select(
                                        'college_gradesdetail.term',
                                        DB::raw("SUM(td) as termgrade")
                                    )
                                    ->groupBy('college_gradesdetail.term')
                                    ->get();

                    $trasmutation = DB::table('college_gradetransmutation')->get();

                    $setupInfo = DB::table('college_gradesterm')
                                    ->join('college_gradestermsetup',function($join){
                                        $join->on('college_gradestermsetup.id','=','college_gradesterm.quartersetupid');
                                        $join->where('college_gradestermsetup.deleted',0);
                                    })
                                    ->where('subjid',$request->get('subjid'))
                                    ->where('sectionid',$studentInfo->sectionid)
                                    ->get();

                    $finalMidterm = null;

                    $ptptper = number_format(0,2);
                    $mtmtper = number_format(0,2);
                    $ststper = number_format(0,2);
                    $ftftper = number_format(0,2);

                    $prelim = number_format(0,2);
                    $midterm = number_format(0,2);
                    $semi = number_format(0,2);
                    $final = number_format(0,2);

                    if(count($setupInfo)>0){

                        if($setupInfo[0]->withpre == 1){

                            try{
                                    $prelim = collect($grades)->where('term',1)->first()->termgrade;
                            }catch (\Exception $e){
                                    $prelim = number_format(0,2);
                            }

                            $ptptper = number_format( $prelim * ( $setupInfo[0]->ptptper / 100 ),2);

                            $studentSubjGrade[0]->prelim = $ptptper;

                        }

                        if($setupInfo[0]->withmid == 1){

                            try{

                                    $midterm = collect($grades)->where('term',2)->first()->termgrade;

                            }catch (\Exception $e){

                                    $midterm = number_format(0,2);

                            }

                            if($setupInfo[0]->mttype == 1){

                                    $itemcount = 0;

                                    if($setupInfo[0]->mtptper != null){
                                        $itemcount += 1;
                                    }
                                    if($setupInfo[0]->mtmtper != null){
                                        $itemcount += 1;
                                    }

                                    $mtmtper = number_format ( ( $prelim + $midterm ) / $itemcount , 2);

                                    $studentSubjGrade[0]->midterm = $mtmtper;

                            }
                            else{

                                    $mtptper = number_format( $ptptper * ( $setupInfo[0]->mtptper / 100 ),2);

                                    $mtmtper = number_format( $midterm * ( $setupInfo[0]->mtmtper / 100 ),2);

                                    $mtmtper = $mtmtper +  $mtptper;

                                    $studentSubjGrade[0]->midterm = $mtmtper;

                            }

                        }

                        if($setupInfo[0]->withsemi == 1){
                            try{
                                    $semi = collect($grades)->where('term',3)->first()->termgrade;
                            }catch (\Exception $e){
                                    $semi = number_format(0,2);
                            }

                            $ststper = number_format( $semi * ( $setupInfo[0]->ptptper / 100 ),2);

                            $studentSubjGrade[0]->semi = $ststper;


                        }

                        if($setupInfo[0]->withfinal == 1){

                            try{
                                    $final = collect($grades)->where('term',4)->first()->termgrade;
                            }catch (\Exception $e){
                                    $final = number_format(0,2);
                            }
                            



                            $itemcount = 0;
                            $ftftper = 0;

                            if($setupInfo[0]->fttype == 1){

                                    if($setupInfo[0]->ftptper != null){

                                        $itemcount += 1;
                                        $ftftper += $prelim;
                                        
                                    }
                                    if($setupInfo[0]->ftmtper != null){

                                        $itemcount += 1;
                                        $ftftper += $midterm;

                                    }
                                    if($setupInfo[0]->ftstper != null){

                                        $itemcount += 1;
                                        $ftftper += $semi;

                                    }
                                    if($setupInfo[0]->ftftper != null){

                                        $itemcount += 1;
                                        $ftftper += $final;

                                    }

                                    $ftftper = number_format (  $ftftper / $itemcount , 2);

                                    $studentSubjGrade[0]->finalterm = $ftftper;

                            }
                            else{

                                    $ftftper = number_format( $final * ( $setupInfo[0]->ftftper / 100 ),2);

                                    $studentSubjGrade[0]->finalterm = $ftftper;
                            }


                            if($setupInfo[0]->fgtype == 2){


                                    $fgptper = number_format(0,2);
                                    $fgmtper = number_format(0,2);
                                    $fgstper = number_format(0,2);
                                    $fgftper = number_format(0,2);

                                    if($setupInfo[0]->fgptper != null){

                                        $fgptper = number_format( $ptptper * ( $setupInfo[0]->fgptper / 100 ),2);

                                    }
                                    if($setupInfo[0]->fgmtper != null){

                                        $fgmtper = number_format( $mtmtper * ( $setupInfo[0]->fgmtper / 100 ),2);

                                    }
                                    if($setupInfo[0]->fgstper != null){

                                        $fgstper = number_format( $ststper * ( $setupInfo[0]->fgstper / 100 ),2);

                                    }
                                    if($setupInfo[0]->fgftper != null){

                                        $fgftper = number_format( $ftftper * ( $setupInfo[0]->fgftper / 100 ),2);

                                    }

                                    $finalgrade =  number_format( $fgptper +  $fgmtper + $fgstper + $fgftper , 2);

                                    $studentSubjGrade[0]->finalgrade = $finalgrade;

                            }

                        }
                    }


                    if( $studentSubjGrade[0]->prelim != null && $setupInfo[0]->prelimsubmit == 2  ){

                        $transmuted_grade = collect($trasmutation)
                                                ->where('gfrom','<=',$studentSubjGrade[0]->prelim)
                                                ->where('gto','>=',$studentSubjGrade[0]->prelim)
                                                ->first();

                        $studentSubjGrade[0]->prelim =  collect( $transmuted_grade)['transmutation'];

                     

                    }
                    if( $studentSubjGrade[0]->midterm != null && $setupInfo[0]->midtermsubmit == 2 ){

                        $transmuted_grade = collect($trasmutation)
                                                        ->where('gfrom','<=',$studentSubjGrade[0]->midterm)
                                                        ->where('gto','>=',$studentSubjGrade[0]->midterm)
                                                        ->first();

                        $studentSubjGrade[0]->midterm =  collect( $transmuted_grade)['transmutation'];
                    }

                    if( $studentSubjGrade[0]->semi != null && $setupInfo[0]->prefisubmit == 2 ){

                        $transmuted_grade = collect($trasmutation)
                                                ->where('gfrom','<=',$studentSubjGrade[0]->semi)
                                                ->where('gto','>=',$studentSubjGrade[0]->semi)
                                                ->first();

                        $studentSubjGrade[0]->semi  =  collect( $transmuted_grade)['transmutation'];

                    }


                    if( $studentSubjGrade[0]->finalterm != null && $setupInfo[0]->finalsubmit == 2){
                                                        
                            $transmuted_grade = collect($trasmutation)
                                                        ->where('gfrom','<=',$studentSubjGrade[0]->finalterm)
                                                        ->where('gto','>=',$studentSubjGrade[0]->finalterm)
                                                        ->first();

                            $studentSubjGrade[0]->finalterm =  collect( $transmuted_grade)['transmutation'];
                    }

                    if( $studentSubjGrade[0]->finalgrade != null && $setupInfo[0]->finalsubmit == 2 ){

                        $transmuted_grade = collect($trasmutation)
                                                        ->where('gfrom','<=',$studentSubjGrade[0]->finalgrade)
                                                        ->where('gto','>=',$studentSubjGrade[0]->finalgrade)
                                                        ->first();

                        $studentSubjGrade[0]->finalgrade =  collect( $transmuted_grade)['transmutation'];

                    }
                  
                    // return $termSetup;

                    // if(isset($termSetup->type) > 0){


                    //     $presubmitted = false;
                    //     $midsubmitted = false;
                    //     $semisubmitted = false;
                    //     $finalsubmitted = false;

                    //     if(auth()->user()->type == 7){

                    //         if($termSetup->prelimsubmit == 3){
                    //             $presubmitted = true;
                    //         }
                    //         if($termSetup->midtermsubmit == 3){
                    //             $midsubmitted = true;
                    //         }
                    //         if($termSetup->prefisubmit == 3){
                    //             $semisubmitted = true;
                    //         }
                    //         if($termSetup->finalsubmit == 3){
                    //             $finalsubmitted = true;
                    //         }

                    //     }
                    //     else{

                    //         $presubmitted = true;
                    //         $midsubmitted = true;
                    //         $semisubmitted = true;
                    //         $finalsubmitted = true;

                    //     }

                    //     if($termSetup->type == 2){

                    //         $trasmutation = DB::table('college_gradetransmutation')->get();

                    //         if($midsubmitted){

                    //             $midgrades = collect($grades)
                    //                             ->whereIn('term',[1,2]);

                    //             $prelim = collect($midgrades)->where('term',1)->sum('td');
                    //             $midterm = collect($midgrades)->where('term',2)->sum('td');

                    //             $midAverageVal = number_format( ( $prelim +   $midterm ) / 2 ,2);

                            

                    //             $midtermtransmutation = collect($trasmutation)
                    //                                         ->where('gfrom','<=',$midAverageVal)
                    //                                         ->where('gto','>=',$midAverageVal)
                    //                                         ->first();

                                

                    //             if(isset( $midtermtransmutation->transmutation)){

                    //                 $studentSubjGrade[0]->midterm =  $midtermtransmutation->transmutation;

                    //             }

                    //         }
                            

                    //         if($finalsubmitted){
                                
                    //             $finalgrades = collect($grades)
                    //                             ->whereIn('term',[3,4]);

                    //             $prefi = collect($finalgrades)->where('term',3)->sum('td');
                    //             $final = collect($finalgrades)->where('term',4)->sum('td');

                    //             $finalAverage = number_format( ( $prefi +   $final ) / 2, 2 );

                    //             $finaltermtransmutation = collect($trasmutation)
                    //                                 ->where('gfrom','<=',$finalAverage)
                    //                                 ->where('gto','>=',$finalAverage)
                    //                                 ->first();

                    //             if(isset( $finaltermtransmutation->transmutation)){

                    //                 $studentSubjGrade[0]->finalterm =  $finaltermtransmutation->transmutation;

                    //             }

                    //         }

                    //         if($finalsubmitted && $midsubmitted){

                    //             $finalMidterm = number_format(  number_format( $midAverageVal * ( $termSetup->mid / 100) , 2)  ,2);

                    //             $finalFinalTerm = number_format(  $finalAverage * ( $termSetup->final / 100)  ,2);

                    //             $totalFinalGrade =  number_format($finalMidterm + $finalFinalTerm , 2);

                    //             $finalgradetransmutation = collect($trasmutation)
                    //                                             ->where('gfrom','<=',$totalFinalGrade)
                    //                                             ->where('gto','>=',$totalFinalGrade)
                    //                                             ->first();

                    //             if(isset($finalgradetransmutation->transmutation)){
                    //                 $studentSubjGrade[0]->finalgrade =  $finalgradetransmutation->transmutation;
                    //             }
                    //         }

                    //     }

                    //     else if($termSetup->type == 1){

                    //             $prelim = collect($grades)->where('term',1)->sum('td');
                    //             $midterm = collect($grades)->where('term',2)->sum('td');
                    //             $prefi = collect($grades)->where('term',3)->sum('td');
                    //             $final = collect($grades)->where('term',4)->sum('td');

                    //             $trasmutation = DB::table('college_gradetransmutation')->get();
                                
                    //             $prelimtransmutation = collect($trasmutation)
                    //                                             ->where('gfrom','<=',$prelim)
                    //                                             ->where('gto','>=',$prelim)
                    //                                             ->first();

                    //             $midtermtransmutation = collect($trasmutation)
                    //                                         ->where('gfrom','<=',$midterm)
                    //                                         ->where('gto','>=',$midterm)
                    //                                         ->first();

                    //             $prefitransmutation = collect($trasmutation)
                    //                                     ->where('gfrom','<=',$prefi)
                    //                                     ->where('gto','>=',$prefi)
                    //                                     ->first();
                                                        
                    //             $finaltransmutation = collect($trasmutation)
                    //                                     ->where('gfrom','<=',$prefi)
                    //                                     ->where('gto','>=',$prefi)
                    //                                     ->first();
                    
                    //             if(isset($prelimtransmutation->transmutation)){

                    //                 $studentSubjGrade[0]->prelim = $prelimtransmutation->transmutation;

                    //             }
                                
                    //             if(isset($midtermtransmutation->transmutation)){

                    //                 $studentSubjGrade[0]->midterm = $midtermtransmutation->transmutation;

                    //             }
                    //             if(isset($prefitransmutation->transmutation)){

                    //                 $studentSubjGrade[0]->semi = $prefitransmutation->transmutation;

                    //             }
                    //             if(isset($finaltransmutation->transmutation)){

                    //                 $studentSubjGrade[0]->finalterm = $finaltransmutation->transmutation;

                    //             }

                    //             $totalFinalGrade = number_format ( ( $prelim + $midterm + $prefi + $final ) / 4 , 2 );

                    //             $totaltransmutation = collect($trasmutation)
                    //                                     ->where('gfrom','<=',$totalFinalGrade)
                    //                                     ->where('gto','>=',$totalFinalGrade)
                    //                                     ->first();

                    //             $studentSubjGrade[0]->finalgrade = $totaltransmutation->transmutation;
                    //     }

                    //     else if($termSetup->type == 3){

                    //         $prelim = collect($grades)->where('term',1)->sum('td');
                    //         $midterm = collect($grades)->where('term',2)->sum('td');
                    //         $prefi = collect($grades)->where('term',3)->sum('td');
                    //         $final = collect($grades)->where('term',4)->sum('td');

                    //         $trasmutation = DB::table('college_gradetransmutation')->get();

                    //         $prelimtransmutation = collect($trasmutation)
                    //                                 ->where('gfrom','<=',$prelim)
                    //                                 ->where('gto','>=',$prelim)
                    //                                 ->first();

                    //         $midtermtransmutation = collect($trasmutation)
                    //                                 ->where('gfrom','<=',$midterm)
                    //                                 ->where('gto','>=',$midterm)
                    //                                 ->first();

                    //         $prefitransmutation = collect($trasmutation)
                    //                             ->where('gfrom','<=',$prefi)
                    //                             ->where('gto','>=',$prefi)
                    //                             ->first();
                                                
                    //         $finaltransmutation = collect($trasmutation)
                    //                             ->where('gfrom','<=',$prefi)
                    //                             ->where('gto','>=',$prefi)
                    //                             ->first();

                    //         if(isset($prelimtransmutation->transmutation)){

                    //             $studentSubjGrade[0]->prelim = $prelimtransmutation->transmutation;

                    //         }

                    //         if(isset($midtermtransmutation->transmutation)){

                    //             $studentSubjGrade[0]->midterm = $midtermtransmutation->transmutation;

                    //         }
                    //         if(isset($prefitransmutation->transmutation)){

                    //             $studentSubjGrade[0]->semi = $prefitransmutation->transmutation;

                    //         }
                    //         if(isset($finaltransmutation->transmutation)){

                    //             $studentSubjGrade[0]->finalterm = $finaltransmutation->transmutation;

                    //         }

                    //         $prelimper = number_format( $prelim * ( $termSetup->semi / 100 ), 2 );
                    //         $midtermper = number_format( $midterm * ( $termSetup->mid / 100 ), 2 );
                    //         $prefiper = number_format( $prefi * ( $termSetup->pre / 100 ), 2 );
                    //         $finalper = number_format( $final * ( $termSetup->final / 100 ) , 2 );


                    //         $finalgrade =  $prelimper + $midtermper + $prefiper +  $finalper;

                    //         $finalgradetransmutation = collect($trasmutation)
                    //                             ->where('gfrom','<=',$finalgrade)
                    //                             ->where('gto','>=',$finalgrade)
                    //                             ->first();

                    //         if(isset($finalgradetransmutation->transmutation)){

                    //             $studentSubjGrade[0]->finalgrade = $finalgradetransmutation->transmutation;

                    //         }

                    //     }

                    // }

                
                }

                array_push($gradeinfo,$studentSubjGrade[0]);
             

            }
            
        if(auth()->user()->type == 7){

            return $gradeinfo;

        }
        else{

            if($request->has('table') && $request->get('table') == 'table'){

                return view('deanportal.pages.subjects.studentgrades')
                            ->with('gradeinfo',$gradeinfo)
                            ->with('term',$request->get('term'));


            }

        }



    }

    public function viewsubmittedgrades(Request $request){

        $activeSy = DB::table('sy')->where('isactive','1')->first();
        $activeSem = DB::table('semester')->where('isactive','1')->first();

         $gradestatus = DB::table('college_classsched')
                            ->where('college_classsched.deleted',0)
                            ->where('college_classsched.semesterID', $activeSem->id)
                            ->where('college_classsched.syID',$activeSy->id);

        if($request->has('teacherid') && $request->get('teacherid') != null){

            $gradestatus = $gradestatus->where('college_classsched.teacherID',$request->get('teacherid'))
                                       ->join('teacher',function($join){
                                            $join->on('college_classsched.teacherID','=','teacher.id');
                                            $join->where('teacher.deleted',0);
                                       });

        }

  
        if($request->has('countsubjects') && $request->get('countsubjects') == 'countsubjects'){



            $gradestatus = $gradestatus->join('college_prospectus',function($join){
                                    $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                    $join->where('college_prospectus.deleted',0);
                                })
                                ->leftJoin('college_gradesterm',function($join){
                                    $join->on('college_classsched.sectionID','=','college_gradesterm.sectionid')
                                        ->on('college_prospectus.subjectID','=','college_gradesterm.subjid')
                                        ->where('college_gradesterm.deleted',0);
                                })
                                ->select('college_gradesterm.*')
                                ->get();

                                
            $gradesummary = array((object)[
                'subjectcount'=>$gradestatus->count(),
                'submittedmidtermgrades'=>collect($gradestatus)->where('midtermsubmit',1)->count(),
                'submittedfinaltermgrades'=>collect($gradestatus)->where('finalsubmit',1)->count(),
            ]);

            return $gradesummary;


        }
        elseif($request->has('table') && $request->get('table') == 'table'){

            if($request->has('teachersubj') && $request->get('teachersubj') == 'teachersubj'){

               
                $subjects = $gradestatus
                            ->join('college_sections',function($join){
                                $join->on('college_classsched.sectionID','=','college_sections.id');
                                $join->where('college_sections.deleted',0);
                            })
                            ->join('college_prospectus',function($join){
                                $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                $join->where('college_prospectus.deleted',0);
                            })
                            ->join('college_subjects',function($join){
                                $join->on('college_prospectus.subjectID','=','college_subjects.id');
                                $join->where('college_subjects.deleted',0);
                            })
                            ->leftJoin('college_gradesterm',function($join){
                                $join->on('college_classsched.sectionID','=','college_gradesterm.sectionid')
                                    ->on('college_prospectus.subjectID','=','college_gradesterm.subjid')
                                    ->where('college_gradesterm.deleted',0);
                            })
                        ->select(
                            'college_subjects.subjDesc',
                            'college_gradesterm.midtermsubmit',
                            'college_gradesterm.finalsubmit',
                            'college_gradesterm.id',
                            'college_classsched.sectionID',
                            'college_prospectus.subjectID',
                            'college_sections.sectionDesc'
                        )
                        ->orderBy('subjDesc')
                        ->get();
    


                $subjects = collect($subjects)->groupBy('subjectID');

                return view('deanportal.pages.subjects.teachersubjecttable')
                            ->with('subjects',$subjects);


            }

        }

        return $gradestatus->get();

                        // ->where('')
                        // ->join('college_classsched',function($join){
                        //     $join->on('teacher.id','=','college_classsched.teacherID');
                        //     $join->where('college_classsched.deleted',0);
                        // })
                        // ->join('college_prospectus',function($join){
                        //     $join->on('college_classsched.subjectID','=','college_prospectus.id');
                        //     $join->where('college_prospectus.deleted',0);
                        // })
                        // ->join('college_gradesterm',function($join){
                        //     $join->on('college_classsched.sectionID','=','college_gradesterm.sectionid')
                        //          ->on('college_prospectus.subjectID','=','college_gradesterm.subjid')
                        //         ->where('college_gradesterm.deleted',0);
                        // })  
                        // ->select('college_gradesterm.*','teacher.firstname','teacher.lastname','teacher.id as teacherid')
                        // ->get();

    }

    public function collegstudents(Request $request){

        $students = DB::table('studinfo')
                        ->select(
                            'studinfo.id',
                            'studinfo.sid',
                            'studinfo.firstname',
                            'studinfo.lastname',
                            'college_courses.courseabrv',
                            'gradelevel.levelname',
                            'studinfo.sectionname',
                            'courseid',
                            'courseDesc',
                            'studstatus',
                            'preEnrolled'
                        )
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                            $join->where('gradelevel.acadprogid',6);
                        });
                        
                       
        $courses = [];

        if(auth()->user()->type ==  16){

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

      


        else if(auth()->user()->type ==  14){

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
                        ->select('college_courses.id')
                        ->get();

            $courseId = collect($courses)->map(function($item, $key){
                return $item->id;
            });
        }

        if(count($courseId) == 0){

            if($request->has('table') && $request->get('table') == 'table'){

                $data = array((object)[
                    'data'=> [],
                    'count'=>0,
                ]);
    
                return view('chairpersonportal.pages.scheduling.collegestudenttable')
                            ->with('data',$data);
            }

            if($request->has('count') && $request->get('count') == 'count'){

                return  0;
    
            }

        }
         
        if( $request->has('location') && $request->get('location') != null ){

            if($request->get('location') == 'pre-enrolled'){
                $students = $students->where('studstatus',0);
                $students = $students->where('preEnrolled',1);
            }
            else if ($request->get('location') == 'enrolled'){
                $students = $students->where('studstatus',1);
                $students = $students->where('preEnrolled',0);
            }

        }
        
        if( ( $request->has('courseid') && $request->get('courseid') != null ) ||  count($courseId) != 0){


                           
            $students =   $students->leftJoin('college_courses',function($join){
                                $join->on('studinfo.courseid','=','college_courses.id');
                            })
                            ->where(function($query) use($courseId){
                                $query->whereIn('courseid',$courseId);
                                $query->orWhere('courseid',0);
                                $query->orWhere('courseid',null);
                            });

        }

        if( $request->has('pre-enrolled') && $request->get('pre-enrolled') != null){

            if( $request->get('pre-enrolled') == 1){

                $students = $students->where('studstatus',1);
                $students = $students->where('preEnrolled',0);

            }
            if( $request->get('pre-enrolled') == 2){

                $students = $students->where('studstatus',0);
                $students = $students->where('preEnrolled',1);
                
            }

        }

        if($request->has('search') && $request->get('search') != 'null'){

                $search = $request->get('search');
                    
                $students->where(function($query) use( $search ) {
                    $query->where('studinfo.lastname','like','%'.$search.'%');
                    $query->orWhere('studinfo.firstname','like','%'.$search.'%');
                    $query->orWhere('studinfo.sid','like','%'.$search.'%');
                    $query->orWhere('gradelevel.levelname','like','%'.$search.'%');
                    $query->orWhere('college_courses.courseabrv','like','%'.$search.'%');
                    $query->orWhere('studinfo.sectionname','like','%'.$search.'%');
                });
                
        }

        $students->where('studinfo.deleted',0)->count();

        $studentCount = $students->count();

        if($request->has('count') && $request->get('count') == 'count'){

            return  $studentCount;

        }



        if($request->has('take') && $request->get('take') != 'null'){

                $students->take($request->get('take'));

        }

        if($request->has('skip') && $request->get('skip') != 'null'){

                if($request->has('take')){

                    $students->skip( ( $request->get('skip')-1 ) * $request->get('take'));

                }
                else{

                    $students->take($count)->skip($request->get('skip'));

                }

        }
    
        if($request->has('table') && $request->get('table') == 'table'){

            $students = $students->get();
            $syid = null;
            $semid = null;

            if($syid == null){
                $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;
            }
    
            if($semid == null){
                $semid = DB::table('semester')->where('isactive',1)->select('id')->first()->id;
            }

            foreach($students as $item){

                $units = DB::table('college_studsched')
                            ->where('college_studsched.deleted',0)
                            ->where('studid',$item->id)
                            ->join('college_classsched',function($join){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted',0);
                            })
                            ->join('college_prospectus',function($join){
                                $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                $join->where('college_prospectus.deleted',0);
                            })
                            ->select(DB::raw('sum(labunits) as labunits, sum(lecunits) as lecunits'))
                            ->get();

                $student_sched = \App\Models\ChairPerson\ChairPersonData::get_student_schedule($syid,$semid,$item->id);

                $item->canchangecourse = true;

                if(count($student_sched) > 0){
                    $item->canchangecourse = false;
                }

                if(count($units) != 0){
                    $item->units = $units[0]->labunits + $units[0]->lecunits;
                }else{
                    $item->units = 0;
                }
               

            }

            $data = array((object)[
                'data'=> $students,
                'count'=>$studentCount,
            ]);

            return view('chairpersonportal.pages.scheduling.collegestudenttable')
                        ->with('data',$data);
        }

                      
        return $students->take(10)->get();


        return $students;
        


    }

    public function collegeteachers(Request $request){

        $collegeteachers = DB::table('teacher')
                    ->where('teacher.deleted',0)
                    ->where('isactive',1);

        if($request->get('blade') == 'blade' && $request->has('blade')){

            $collegeteachercount = $collegeteachers->whereIn('usertypeid',['18','16'])->count();

            $data = array((object)[
                'count'=> $collegeteachercount,
                'data'=>array()
            ]);

            return view('deanportal.pages.collegeteacher.collegeteacher')->with('data',$data);

        }

        if($request->get('table') == 'table' && $request->has('table')){

            $collegeteachercount = $collegeteachers->whereIn('usertypeid',['18','16'])->count();

            $collegeteachers = $collegeteachers
                                ->join('usertype',function($join){
                                    $join->on('teacher.usertypeid','=','usertype.id');
                                })
                                ->whereIn('usertypeid',['18','16'])
                                ->get();

            $data = array((object)[
                'count'=> $collegeteachercount,
                'data'=>$collegeteachers
            ]);

            return view('deanportal.pages.collegeteacher.collegeteachertable')
                            ->with('data',$data);

        }


















       




    }
    


}
