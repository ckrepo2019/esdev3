<?php

namespace App\Http\Controllers\AdministratorControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use \Carbon\Carbon;

class AdmissionTestController extends Controller
{
    public function index(){


        // $count1= DB::table('admissiontestapplicant')
        //         ->where('admissiontestapplicant.deleted',0)
        //         ->count();

                
        $count1 = DB::table('admissiontestapplicant')
                    ->join('college_courses',function($join){
                            $join->on('college_courses.id','=','admissiontestapplicant.desiredprogramid');
                            $join->where('college_courses.deleted',0);
                        })
                    ->leftjoin('admissiontestrecords',function($join){
                            $join->on('admissiontestrecords.poolnum','=','admissiontestapplicant.poolingnumber');
                            $join->where('admissiontestrecords.deleted',0);
                        })
                    ->where('admissiontestapplicant.deleted',0)
                    ->select('admissiontestapplicant.*', 'college_courses.courseDesc as program', 'college_courses.id as programid' , 'admissiontestrecords.totalscore', 'admissiontestrecords.id as recordid',  'admissiontestrecords.admissiontestid' , 'admissiontestrecords.teststatus'  , 'admissiontestrecords.admissiontestid')
                    ->count();


        $admissiontest = Db::table('admissiontest')
                ->where('admissiontest.deleted','0')
                ->where('admissiontest.createdby',auth()->user()->id)
                ->count();


        $passing = DB::table('admissiontestapplicant')
                ->join('college_courses', function ($join) {
                    $join->on('college_courses.id', '=', 'admissiontestapplicant.desiredprogramid')
                        ->where('college_courses.deleted', 0);
                })
                ->join('admissiontestrecords', function ($join) {
                    $join->on('admissiontestrecords.poolnum', '=', 'admissiontestapplicant.poolingnumber')
                        ->where('admissiontestrecords.deleted', 0)
                        ->whereNotNull('admissiontestrecords.totalscore'); // Add this line to check for non-null totalscore
                })
                ->where('admissiontestapplicant.deleted', 0)
                ->select('admissiontestapplicant.*', 'college_courses.courseDesc as program', 'college_courses.id as programid', 'admissiontestrecords.totalscore', 'admissiontestrecords.id as recordid', 'admissiontestrecords.admissiontestid', 'admissiontestrecords.teststatus', 'admissiontestrecords.admissiontestid')
                ->get();

        $incoming =  $count1 - count($passing);

        $passingrate = 0;
        $num = count($passing);

        foreach($passing as $item){



                $maxpoints = DB::table('admissiontestquestions')
                    ->where('quizid', $item->admissiontestid)
                    ->where('deleted', 0)
                    ->where('typeofquiz', '!=', null)
                    ->where('typeofquiz', '!=', 4)
                    ->where('typeofquiz', '!=', 9)
                    ->sum('points');

                $passing = DB::table('admissiongeneralprogramsetup')
                    ->where('courseid', $item->programid)
                    ->where('setup', 'like','%Exam Result%')
                    ->where('deleted', 0)
                    ->value('percentage');
            
                $item->maxpoints = $maxpoints;

                if($maxpoints != 0){

                    $item->percentage = round(($item->totalscore / $maxpoints) * 100, 2); // Rounds to 2 decimal places

                }else{

                    $item->percentage = 0; // Rounds to 2 decimal places

                } // Rounds to 2 decimal places


                if($item->percentage >= $passing){

                    $passingrate += 1;
                
                }
                


        }

        if($num != 0){

        $passingpercentage = round(($passingrate /  $num) * 100, 2); // Rounds to 2 decimal places

		}else{
		$passingpercentage = 100;
		}




    return view('guidance.pages.admissiontest.index')
            ->with('count', $count1) 
            ->with('admissiontest', $admissiontest)
            ->with('incoming', $incoming)
            ->with('passingrate', $passingpercentage);
    }


    public function createBladeTest(){


    return view('guidance.pages.admissiontest.admissiontestcreate');
    }

    public function applicantIndex(){

        $count1 = DB::table('admissiontestapplicant')
                    ->join('college_courses',function($join){
                            $join->on('college_courses.id','=','admissiontestapplicant.desiredprogramid');
                            $join->where('college_courses.deleted',0);
                        })
                    ->leftjoin('admissiontestrecords',function($join){
                            $join->on('admissiontestrecords.poolnum','=','admissiontestapplicant.poolingnumber');
                            $join->where('admissiontestrecords.deleted',0);
                        })
                    ->where('admissiontestapplicant.deleted',0)
                    ->select('admissiontestapplicant.*', 'college_courses.courseDesc as program', 'college_courses.id as programid' , 'admissiontestrecords.totalscore', 'admissiontestrecords.id as recordid',  'admissiontestrecords.admissiontestid' , 'admissiontestrecords.teststatus'  , 'admissiontestrecords.admissiontestid')
                    ->count();


        $admissiontest = Db::table('admissiontest')
                ->where('admissiontest.deleted','0')
                ->where('admissiontest.createdby',auth()->user()->id)
                ->count();


        $passing = DB::table('admissiontestapplicant')
                ->join('college_courses', function ($join) {
                    $join->on('college_courses.id', '=', 'admissiontestapplicant.desiredprogramid')
                        ->where('college_courses.deleted', 0);
                })
                ->join('admissiontestrecords', function ($join) {
                    $join->on('admissiontestrecords.poolnum', '=', 'admissiontestapplicant.poolingnumber')
                        ->where('admissiontestrecords.deleted', 0)
                        ->whereNotNull('admissiontestrecords.totalscore'); // Add this line to check for non-null totalscore
                })
                ->where('admissiontestapplicant.deleted', 0)
                ->select('admissiontestapplicant.*', 'college_courses.courseDesc as program', 'college_courses.id as programid', 'admissiontestrecords.totalscore', 'admissiontestrecords.id as recordid', 'admissiontestrecords.admissiontestid', 'admissiontestrecords.teststatus', 'admissiontestrecords.admissiontestid')
                ->get();

        $incoming =  $count1 - count($passing);

        $passingrate = 0;
        $num = count($passing);

        foreach($passing as $item){



                $maxpoints = DB::table('admissiontestquestions')
                    ->where('quizid', $item->admissiontestid)
                    ->where('deleted', 0)
                    ->where('typeofquiz', '!=', null)
                    ->where('typeofquiz', '!=', 4)
                    ->where('typeofquiz', '!=', 9)
                    ->sum('points');

                $passing = DB::table('admissiongeneralprogramsetup')
                    ->where('courseid', $item->programid)
                    ->where('setup', 'like','%Exam Result%')
                    ->where('deleted', 0)
                    ->value('percentage');
            
                $item->maxpoints = $maxpoints;

                if($maxpoints != 0){

                    $item->percentage = round(($item->totalscore / $maxpoints) * 100, 2); // Rounds to 2 decimal places

                }else{

                    $item->percentage = 0; // Rounds to 2 decimal places

                }



                if($item->percentage >= $passing){

                    $passingrate += 1;
                
                }
                


        }


			if($num != 0){

			$passingpercentage = round(($passingrate /  $num) * 100, 2); // Rounds to 2 decimal places

			}else{
			$passingpercentage = 100;
			}


    return view('guidance.pages.admissiontest.admissionactivate')
            ->with('count', $count1) 
            ->with('admissiontest', $admissiontest)
            ->with('incoming', $incoming)
            ->with('passingrate', $passingpercentage);
    }


    public function testTable(){


        $data = Db::table('admissiontest')
                ->select(
                    'admissiontest.id',
                    'admissiontest.title',
                    'admissiontest.description',
                    'admissiontest.createddatetime',
                    'admissiontest.checking'
                )
                ->orderBy('admissiontest.id')
                ->where('admissiontest.deleted','0')
                // ->where('admissiontest.createdby',auth()->user()->id)
                ->get();


        return view('guidance.pages.admissiontest.include.testtable')
        ->with('data',$data);
    }

    public function createTest(Request $request){



        date_default_timezone_set('Asia/Manila');



        function codegenerator(){
            $length = 6;    
            return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
        }

        $code = codegenerator();

        $checkifexists = Db::table('admissiontest')
            ->where('testlink', $code)
            ->get();

        if(count($checkifexists) == 0){

            // return $code;

            $id = DB::table('admissiontest')->insertGetId([
                'title'           => $request->get('quizname'),
                'testlink'        => $code,
                'checking'        => $request->get('checking'),
                'createddatetime' => date('Y-m-d H:i:s'),
                'createdby'       => auth()->user()->id,
            ]);



        $checking = $request->get('checking');

        return array((object)[
                'check'=> $checking,
                'id'=>$id
            ]);

        }else{

            self::createTest();

        }

    

    }

    public function deleteTest(Request $request){


        try{

            DB::table('admissiontest')
                ->where('id', $request->get('id'))
                ->update([

                    'deleted' => '1'
                ]);

        return 0;


        }catch (\Exception $e) {
            // Handle the exception here
            return 1;
        }
        
    

    }

    public function Test($id , Request $request){


            $testinfo = DB::table('admissiontest')
                ->where('id', $id)
                ->first();

            $testquestions = DB::table('admissiontestquestions')
                ->where('quizid', $id)
                ->where('typeofquiz', '!=', null)
                ->leftjoin('admissiontestquestionscategory',function($join){
                            $join->on('admissiontestquestionscategory.id','=','admissiontestquestions.category');
                            $join->where('admissiontestquestionscategory.deleted',0);
                        })
                ->select('admissiontestquestions.*', 'admissiontestquestionscategory.category')
                ->where('admissiontestquestions.deleted', 0)
                ->get();



            if($testinfo->title == null || $testinfo->title == ""){
                $testinfo->title = "Untitled Test";
            }

            if($testinfo->description == null || $testinfo->description == ""){
                $testinfo->description = "";
            }



            return view('guidance.pages.admissiontest.test.testindex')
                ->with('id',$id)
                ->with('testquestions',$testquestions)
                ->with('testinfo',$testinfo);


    }

    public function Test2($id , Request $request){


            $testinfo = DB::table('admissiontest')
                ->where('id', $id)
                ->first();

            $testquestions = DB::table('admissiontestquestions')
                ->where('admissiontestquestions.quizid', $id)
                ->where('admissiontestquestions.typeofquiz', '!=', null)  
                ->leftjoin('admissiontestquestionscategory',function($join){
                    $join->on('admissiontestquestionscategory.id','=','admissiontestquestions.category');
                    $join->where('admissiontestquestionscategory.deleted',0);
                })
                ->select('admissiontestquestions.*', 'admissiontestquestionscategory.category')
                ->where('admissiontestquestions.deleted', 0)
                ->get();



            if($testinfo->title == null || $testinfo->title == ""){
                $testinfo->title = "Untitled Test";
            }

            if($testinfo->description == null || $testinfo->description == ""){
                $testinfo->description = "";
            }



            return view('guidance.pages.admissiontest.test.testindex2')
                ->with('id',$id)
                ->with('testquestions',$testquestions)
                ->with('testinfo',$testinfo);


    }

    public function saveTitle(Request $request)
    {


        DB::table('admissiontest')
            ->where('id', $request->get('quizId'))
            ->update([
                'Title'   => $request->get('title')
            ]);

        return 1;
    }
    


    public function saveDescription(Request $request)
    {


        DB::table('admissiontest')
            ->where('id', $request->get('quizId'))
            ->update([
                'description'   => $request->get('description')
            ]);

        return 1;
    }


    public function addQuestion(Request $request)
    {


        date_default_timezone_set('Asia/Manila');


        

        $category = DB::table('admissiontest')
            ->where('admissiontest.id', $request->get('quizId'))
            ->join('admissiontestquestionscategory',function($join){
                        $join->on('admissiontestquestionscategory.id','=','admissiontest.last_category');
                        $join->where('admissiontestquestionscategory.deleted',0);
                    })
            ->select('admissiontestquestionscategory.category','admissiontestquestionscategory.id')
            ->first();

        if(isset($category)){
            $id = DB::table('admissiontestquestions')->insertGetId([
                'quizid' => $request->get('quizId'),
                'category' => $category->id,
                'createddatetime' => date('Y-m-d H:i:s'),
            ]);

            $stringCategory = $category->category;
        }else{


            $id = DB::table('admissiontestquestions')->insertGetId([
                'quizid' => $request->get('quizId'),
                'createddatetime' => date('Y-m-d H:i:s'),
            ]);

            $stringCategory = "Category not Set";

        

        }



            $result = [
            'id' => $id,
            'category' => $stringCategory,
        ];

        return $result;


        //}
        
    }


    public function delQuestion(Request $request)
    {


        date_default_timezone_set('Asia/Manila');
        DB::table('admissiontestquestions')
            ->where('id', $request->get('id'))
            ->update([
                'deleted'                 => 1,
                'updateddatetime'         => date('Y-m-d H:i:s')
                    ]);

        return 1;
    }


    public function createQuestion(Request $request)
    {


        try {
            date_default_timezone_set('Asia/Manila');
            DB::table('admissiontestquestions') // Replace '' with the table name
                ->where('id', $request->get('id'))
                ->update([
                    'question' => $request->get('question'),
                    'typeofquiz' => $request->get('typeofquiz'),
                    'updateddatetime' => date('Y-m-d H:i:s'),
                    'points' => 1
                ]);

            return 1;
        } catch (\Exception $e) {
            // Handle the exception here
            return response()->json(['error' => $e->getMessage()], 500);
        }



    }

    public function createQuestion2(Request $request)
    {


        try {
            date_default_timezone_set('Asia/Manila');
            DB::table('admissiontestquestions') // Replace '' with the table name
                ->where('id', $request->get('id'))
                ->update([
                    'question' => $request->get('question'),
                    'typeofquiz' => $request->get('typeofquiz'),
                    'updateddatetime' => date('Y-m-d H:i:s'),
                    'points' => $request->get('points'),
                ]);

            return 1;
        } catch (\Exception $e) {
            // Handle the exception here
            return response()->json(['error' => $e->getMessage()], 500);
        }



    }


    public function createChoices(Request $request)
    {

        
        date_default_timezone_set('Asia/Manila');
        $count = DB::table('admissiontestchoices')
            ->where('questionid', $request->get('questionid'))
            ->where('sortid', $request->get('sortid'))
            ->where('deleted', 0)
            ->count();

        if($count == 0){
            DB::table('admissiontestchoices')
                ->insert([
                        'sortid'            =>  $request->get('sortid'),
                        'questionid'        =>  $request->get('questionid'),
                        'description'       =>  $request->get('description'),
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);

        }else{

            DB::table('admissiontestchoices')
                ->where('questionid', $request->get('questionid'))
                ->where('sortid', $request->get('sortid'))
                ->update([
                    'questionid'             =>  $request->get('questionid'),
                    'description'       =>  $request->get('description'),
                    'updatedatetime'   => date('Y-m-d H:i:s')
                ]);

        }
        

            return 1;

    }

    public function delOption(Request $request)
    {


        date_default_timezone_set('Asia/Manila');
        DB::table('admissiontestchoices')
            ->where('id', $request->get('id'))
            ->update([
                'deleted'         => 1,
                'deleteddatetime' => date('Y-m-d H:i:s')
                    ]);

        return 1;
    }

    public function getquestion(Request $request)
    {
        $question = DB::table('admissiontestquestions')
            ->where('id', $request->get('id'))
            ->select('id','question')
            ->where('deleted', 0)
            ->first();

        $question->choices = DB::table('admissiontestchoices')
        ->where('questionid', $question->id)
        ->where('deleted', 0)
        ->select('id', 'questionid' , 'description')
        ->orderBy('sortid')
        ->get();

        return response()->json($question);
        
    }


    
    public function setAnswerKey(Request $request)
    {

        if($request->get('questiontype') == 1){
                DB::table('admissiontestchoices')
                        ->where('questionid', $request->get('question_id'))
                        ->where('answer', 1)
                        ->update([
                            'answer'   => '0'
                        ]);

                
                DB::table('admissiontestchoices')
                        ->where('id', $request->get('answer'))
                        ->where('questionid', $request->get('question_id'))
                        ->update([
                            'answer'   => '1'
                        ]);

                return 1;
                    }
            else if($request->get('questiontype') == 7){

                        $checkifexist =  DB::table('admission_fill_answer')
                        ->where('headerid', $request->get('question_id'))
                        ->where('sortid', $request->get('sortid'))
                        ->count();

                        if($checkifexist > 0){

                            DB::table('admission_fill_answer')
                            ->where('headerid', $request->get('question_id'))
                            ->where('sortid', $request->get('sortid'))
                            ->update([
                                'answer'   => $request->get('answer')
                            ]);

                                return 0;


                        }else{

                            DB::table('admission_fill_answer')
                            ->insert([
                                'answer'   => $request->get('answer'),
                                'headerid'   => $request->get('question_id'),
                                'sortid'   => $request->get('sortid')
                            ]);

                                return 5;

                        }  

            }else if($request->get('questiontype') == 8){

                        $checkifexist =  DB::table('admission_test_enum_answer')
                        ->where('headerid', $request->get('question_id'))
                        ->where('sortid', $request->get('sortid'))
                        ->where('deleted', 0)
                        ->count();

                        if($checkifexist > 0){

                            DB::table('admission_test_enum_answer')
                            ->where('headerid', $request->get('question_id'))
                            ->where('sortid', $request->get('sortid'))
                            ->update([
                                'answer'   => $request->get('answer')
                            ]);

                                return 0;


                        }else{

                            DB::table('admission_test_enum_answer')
                            ->insert([
                                'answer'   => $request->get('answer'),
                                'headerid'   => $request->get('question_id'),
                                'sortid'   => $request->get('sortid')
                            ]);

                                return 5;

                        }  

            }else if($request->get('questiontype') == 16){


                if($request->get('answer') == 1){
                    DB::table('admissiontestquestions')
                    ->where('id', $request->get('question_id'))
                    ->update([
                        'ordered'   => 1
                    ]);

                    return 1;
                }else{
                    DB::table('admissiontestquestions')
                    ->where('id', $request->get('question_id'))
                    ->update([
                        'ordered'   => 0
                    ]);

                    return 0;

                }

            }
        
        
    }

    public function returneditquiz(Request $request)

    {


    $question = DB::table('admissiontestquestions')
            ->where('id', $request->get('id'))
            ->select('id','question')
            ->where('deleted', 0)
            ->first();

    $question->choices = DB::table('admissiontestchoices')
            ->where('questionid', $question->id)
            ->where('deleted', 0)
            ->select('id', 'questionid' , 'description' , 'answer')
            ->orderBy('sortid')
            ->get();


    return response()->json($question);
    
        
    }


    
    public function setPoints(Request $request)

    {
        DB::table('admissiontestquestions')
            ->where('id', $request->get('dataid'))
            ->update([
                'points'   => $request->get('points')
            ]);

    
        
    }


    public function createquestionitem(Request $request)
    {

    
            DB::table('admissiontestquestions')
                ->where('id', $request->get('id'))
                ->update([
                    'question'         => $request->get('question'),
                    'typeofquiz'   => $request->get('typeofquiz'),
                    'item'   => $request->get('item')
                ]);
            
            DB::table('admissiontestquestions')
                ->where('id', $request->get('id'))
                ->update([
                    'points'             =>  $request->get('item'),
                ]);
            
            DB::table('admission_test_enum_answer')
                ->where('headerid', $request->get('id'))
                ->where('sortid', '>',$request->get('item'))
                ->update([
                    'deleted'         => 1,

                ]);
            
                

            return 1;

    }


    public function getEnum(Request $request)
    {
        $question = DB::table('admissiontestquestions')
            ->where('id', $request->get('id'))
            ->select('id','question', 'item')
            ->where('deleted', 0)
            ->first();

        $question->answer = DB::table('admission_test_enum_answer')
            ->where('headerid', $question->id)
            ->select('answer')
            ->where('deleted', 0)
            ->orderBy('sortid')
            ->get();

        return response()->json($question);
        
    }


    public function returnEditenum(Request $request)
    {


        $question = DB::table('admissiontestquestions')
            ->where('id', $request->get('id'))
            ->select('id','question' , 'ordered' , 'item')
            ->where('deleted', 0)
            ->first();


            $answer = DB::table('admission_test_enum_answer')
                ->where('headerid', $question->id)
                ->where('deleted', 0)
                ->select('answer')
                ->orderBy('sortid')
                ->get();


            $question->answer = $answer;


    return response()->json($question);
    
        
    }

    public function createFillquestion(Request $request)
    {

        date_default_timezone_set('Asia/Manila');
        $checkifexist = DB::table('admission_fill_question')
            ->where('questionid', $request->get('questionid'))
            ->where('sortid', $request->get('sortid'))
            ->count();

        if($checkifexist == 0){

            
                DB::table('admission_fill_question')
                    ->insert([
                            'sortid'            =>  $request->get('sortid'),
                            'questionid'        =>  $request->get('questionid'),
                            'question'       =>  $request->get('description'),
                        ]);

                return 0;

        }else{


            if($request->get('description') != null || $request->get('description') != "" ){



                DB::table('admission_fill_question')
                    ->where('questionid', $request->get('questionid'))
                    ->where('sortid', $request->get('sortid'))
                    ->update([
                        'question'       =>  $request->get('description'),
                    ]);

                return 0;
                
            }else{



                DB::table('admission _fill_question')
                    ->where('questionid', $request->get('questionid'))
                    ->where('sortid', $request->get('sortid'))
                    ->update([
                        'deleted'             =>  1,
                    ]);


            return 1;

            }
        }        

        

    }

    public function setFillPoints(Request $request)
    {


        DB::table('admissiontestquestions')
                    ->where('id', $request->get('questionid'))
                    ->update([
                        'points'   =>  $request->get('total'),
                    ]);
        
        $return = $request->get('total');

        return $return; 
        


        
    }

    public function getFillQuestion(Request $request)
    {
        
        $question = DB::table('admissiontestquestions')
            ->where('id', $request->get('id'))
            ->select('id','question')
            ->where('deleted', 0)
            ->first();

        $question->fill = DB::table('admission_fill_question')
        ->where('questionid', $question->id)
        ->select('id', 'questionid' , 'question', 'sortid')
        ->orderBy('sortid')
        ->get();

        $key= 0;

        $counter = 0;

        $inputCounter = 0;
        foreach ($question->fill as $index => $item) {
            // Replace all occurrences of ~input with input fields that have unique IDs
            $key = 0;
            $questionWithInputs = preg_replace_callback('/~input/', function($matches) use ($item, &$inputCounter, &$key) {
            $inputField = '<input class="d-inline form-control q-input answer-field" data-type="7" data-sortid="'.++$inputCounter.'" data-question-id="'.$item->id.'" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-'.$item->id.'">';
            return $inputField;
            }, $item->question);
            $inputCounter = 0;

            $item->question = $questionWithInputs;
        }




        return response()->json($question);
        
    }


    public function returnEditfill(Request $request)
    {
        $question = DB::table('admissiontestquestions')
            ->where('id', $request->get('id'))
            ->select('id','question')
            ->where('deleted', 0)
            ->first();


        $question->fill = DB::table('admission_fill_question')
            ->where('questionid', $question->id)
            ->orderBy('sortid')
            ->get();

        foreach($question->fill as $item){

            $answer = DB::table('admission_fill_answer')
                ->where('headerid', $item->id)
                ->orderBy('sortid')
                ->pluck('answer');

            $answerString = implode(',', $answer->toArray());

            $item->answer = $answerString;

        }


    return response()->json($question);
    
        
    }


    public function createdragoption(Request $request)
    {

        date_default_timezone_set('Asia/Manila');
        $choice = DB::table('admission_drag_option')
            ->where('questionid', $request->get('questionid'))
            ->where('sortid', $request->get('sortid'))
            ->count();

        if($choice == 0){
        
            if($request->get('description') != null || $request->get('description') != "" ){
                DB::table('admission_drag_option')
                    ->insert([
                            'sortid'            =>  $request->get('sortid'),
                            'questionid'        =>  $request->get('questionid'),
                            'description'       =>  $request->get('description'),
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);
            }

        }else{

            if($request->get('description') != null || $request->get('description') != "" ){

                DB::table('admission_drag_option')
                    ->where('questionid', $request->get('questionid'))
                    ->where('sortid', $request->get('sortid'))
                    ->update([
                        'questionid'             =>  $request->get('questionid'),
                        'description'       =>  $request->get('description'),
                        'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
            }else{

                DB::table('admission_drag_option')
                    ->where('questionid', $request->get('questionid'))
                    ->where('sortid', $request->get('sortid'))
                    ->update([
                        'deleted'  =>  1
                ]);
                
            }

        }
        

        return 1;
    }

    public function createdropquestion(Request $request)
    {

        date_default_timezone_set('Asia/Manila');
        $choice = DB::table('admission_drop_question')
            ->where('questionid', $request->get('questionid'))
            ->where('sortid', $request->get('sortid'))
            ->where('deleted', 0)
            ->count();

        

        if($choice == 0){



            if($request->get('description') != null || $request->get('description') != "" ){

        

                DB::table('admission_drop_question')
                    ->insert([
                            'sortid'            =>  $request->get('sortid'),
                            'questionid'        =>  $request->get('questionid'),
                            'question'       =>  $request->get('description'),
                            'createddatetime'   => date('Y-m-d H:i:s')
                        ]);

    

                }
                

        }else{

            if($request->get('description') != null || $request->get('description') != "" ){




                DB::table('admission_drop_question')
                    ->where('questionid', $request->get('questionid'))
                    ->where('sortid', $request->get('sortid'))
                    ->update([
                        'questionid'             =>  $request->get('questionid'),
                        'question'       =>  $request->get('description'),
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);


                

            }else{

                

                DB::table('admission_drop_question')
                    ->where('questionid', $request->get('questionid'))
                    ->where('sortid', $request->get('sortid'))
                    ->update([
                        'deleted'             =>  1,
                    ]);




            }

        }
        

        return 1;
    }

    public function setDragPoints(Request $request)
    {


        DB::table('admissiontestquestions')
                    ->where('id', $request->get('questionid'))
                    ->update([
                        'points'   =>  $request->get('total'),
                    ]);
        
        
        


        
    }

    public function getDropQuestion(Request $request)

    {
        $question = DB::table('admissiontestquestions')
            ->where('id', $request->get('id'))
            ->select('id','question')
            ->where('deleted', 0)
            ->first();

        $question->drag = DB::table('admission_drag_option')
        ->where('questionid', $question->id)
        ->where('deleted', 0)
        ->select('id', 'description')
        ->orderBy('sortid')
        ->get();

        $question->drop = DB::table('admission_drop_question')
        ->where('questionid', $question->id)
        ->where('deleted', 0)
        ->select('id', 'questionid' , 'question', 'sortid')
        ->orderBy('sortid')
        ->get();

        $key= 0;

        $counter = 0;

        $inputCounter = 0;
        foreach ($question->drop as $index => $item) {
            // Replace all occurrences of ~input with input fields that have unique IDs
            $key = 0;
            $questionWithInputs = preg_replace_callback('/~input/', function($matches) use ($item, &$inputCounter, &$key) {
            $inputField = '<input class="d-inline form-control q-input drop-option q-input ui-droppable" data-sortid="'.++$inputCounter.'" data-question-id="'.$item->id.'" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-'.$item->id.'" disabled>';
            return $inputField;
            }, $item->question);
            $inputCounter = 0;

            $item->question = $questionWithInputs;
        }




        return response()->json($question);
        
    }
    public function setAnswerdrop(Request $request)
    {
        

        $checkifexist =  DB::table('admission_drop_answer')
            ->where('headerid', $request->get('question_id'))
            ->where('sortid', $request->get('sortId'))
            ->count();

        if($checkifexist == 1){

            DB::table('admission_drop_answer')
            ->where('headerid', $request->get('question_id'))
            ->where('sortid', $request->get('sortId'))
            ->update([
                'answer'   => $request->get('answer')
            ]);

                return 0;


        }else{

            DB::table('admission_drop_answer')
            ->insert([
                'answer'   => $request->get('answer'),
                'headerid'   => $request->get('question_id'),
                'sortid'   => $request->get('sortId')
            ]);

                return 1;

        }
        
    }

    public function returnEditdrag(Request $request)

    {
        $question = DB::table('admissiontestquestions')
            ->where('id', $request->get('id'))
            ->select('id','question')
            ->where('deleted', 0)
            ->first();


        $question->drag = DB::table('admission_drag_option')
            ->where('questionid', $question->id)
            ->orderBy('sortid')
            ->where('deleted', 0)
            ->get();
                                                            
        $question->drop = DB::table('admission_drop_question')
            ->where('questionid', $question->id)
            ->orderBy('sortid')
            ->where('deleted', 0)
            ->get();

        foreach($question->drop as $item){

        $answer = DB::table('admission_drop_answer')
            ->where('headerid', $item->id)
            ->orderBy('sortid')
            ->pluck('answer');

        $answerString = implode(',', $answer->toArray());
        $item->answer = $answerString;

        }


    return response()->json($question);
    
        
    }


    public function ActivateBlade(Request $request)
    {

    return view(' ');
    
        
    }

    public function ActivateTable(Request $request)
    {




            $data = Db::table('admissiontest')
                ->orderBy('admissiontest.id')
                ->leftjoin('admissiontestsched', 'admissiontestsched.admissiontestid', '=', 'admissiontest.id')
                ->select('admissiontest.*', 'admissiontestsched.admissiontestid','admissiontestsched.datefrom', 'admissiontestsched.timefrom' , 'admissiontestsched.dateto', 'admissiontestsched.timeto', 'admissiontestsched.createddatetime as created','admissiontestsched.hour','admissiontestsched.minute', 'admissiontestsched.updateddatetime as updated' )
                ->where('admissiontest.deleted','0')
                ->get();

            date_default_timezone_set('Asia/Manila');
            $now = date('Y-m-d H:i:s');

            foreach ($data as $item) {

                    $protocol = $request->getScheme();
                    $host = $request->getHost();

                    $rootDomain = $protocol . '://' . $host;


                    $item->link = $rootDomain . '/admission/' . $item->testlink;

                    if(isset($item->created))
                    {   
                        if(isset($item->updated))
                        {
                            
                            $carbonDate1 = Carbon::parse($item->updated);
                            $item->activatedon = $carbonDate1->format('F j, Y H:i A');


                        }else{

                            $carbonDate1 = Carbon::parse($item->created);
                            $item->activatedon = $carbonDate1->format('F j, Y H:i A' );

                        }

                    }else{

                        $item->activatedon = "Not Activated";
                    }


                    $dateTo = \Carbon\Carbon::create($item->dateto. ' ' .$item->timeto);
                    $dateFrom = \Carbon\Carbon::create($item->datefrom . ' ' . $item->timefrom);

                    if ($dateTo < $now) {

                        $item->type = "Expired";
                        $item->badge = "badge-danger";
                    } elseif ($dateFrom > $now) {
                        $item->badge = "badge-info";
                        $item->type = "Upcoming";
                    } else {
                        $item->badge = "badge-success";
                        $item->type = "Ongoing";
                    }

                    $carbonDate = Carbon::parse($item->createddatetime);

                    // Format the Carbon instance as per your requirement
                    $item->formattedDate = $carbonDate->format('F j, Y ');
                    $item->search = $item->description.' '.$item->title;

            }

            return $data;
    
        
    }

    public function testView($testid,Request $request)
    {

            $testinfo = DB::table('admissiontest')
                            ->where('testlink',$testid)
                            ->where('deleted',0)
                            ->first();

            $admissiontestsched = DB::table('admissiontestsched')
                            ->where('admissiontestid',$testinfo->id)
                            ->orderBy('createddatetime', 'desc')
                            ->where('admissiontestsched.deleted',0)
                            ->first();


            $schedid = $admissiontestsched->id;


            date_default_timezone_set('Asia/Manila');
            $now = date('Y-m-d H:i:s');



            $testcode = $testinfo->testlink;
            

            return view('guidance.pages.admissiontest.viewtake')
                        ->with('testinfo',$testinfo)
                        ->with('schedid',$schedid)
                        ->with('testcode',$testcode)
                        ->with('now',$now)
                        ->with('admissiontestsched',$admissiontestsched);


        
    }

    public function testActivation(Request $request)
    {

        $checkifexists = DB::table('admissiontestsched')
            ->where('admissiontestid', $request->get('testid'))
            ->where('deleted','0')
            ->get();


        date_default_timezone_set('Asia/Manila');
        if(count($checkifexists) == 0) {
            $createdsched = DB::table('admissiontestsched')
                ->insertGetId([
                    'admissiontestid'       => $request->get('testid'),
                    'datefrom'              => $request->get('dateFrom'),
                    'timefrom'              => $request->get('timeFrom'),
                    'dateto'                => $request->get('dateTo'),
                    'timeto'                => $request->get('timeTo'),
                    'hour'                  => $request->get('hour'),
                    'minute'                => $request->get('minute'),
                    'createddatetime'       => date('Y-m-d H:i:s'),
                ]);

            return 0;


        } else {
            DB::table('admissiontestsched')
                ->where('id', $checkifexists[0]->id)
                ->update([
                    'admissiontestid'       => $request->get('testid'),
                    'datefrom'              => $request->get('dateFrom'),
                    'timefrom'              => $request->get('timeFrom'),
                    'dateto'                => $request->get('dateTo'),
                    'timeto'                => $request->get('timeTo'),
                    'hour'                  => $request->get('hour'),
                    'minute'                => $request->get('minute'),
                    'updateddatetime'       => date('Y-m-d H:i:s'),
                ]);


            return 1;
        }


    }


    public function applicantGet(Request $request)
    {

        $applicants = DB::table('admissiontestapplicant')
                    ->join('college_courses',function($join){
                            $join->on('college_courses.id','=','admissiontestapplicant.desiredprogramid');
                            $join->where('college_courses.deleted',0);
                        })
                    ->leftjoin('admissiontestrecords',function($join){
                            $join->on('admissiontestrecords.poolnum','=','admissiontestapplicant.poolingnumber');
                            $join->where('admissiontestrecords.deleted',0);
                        })
                    ->distinct()
                    ->where('admissiontestapplicant.deleted',0)
                    ->select('admissiontestapplicant.*', 'college_courses.courseDesc as program', 'college_courses.id as programid' , 'admissiontestrecords.totalscore', 'admissiontestrecords.id as recordid',  'admissiontestrecords.admissiontestid' , 'admissiontestrecords.teststatus'  , 'admissiontestrecords.admissiontestid')
                    ->get();

        
        foreach($applicants as $item){

            $item->search = $item->applicantname.' '.$item->poolingnumber;

            if(isset($item->fileurl)){

                $protocol = $request->getScheme();
                $host = $request->getHost();

                $rootDomain = $protocol . '://' . $host;


                $item->file =  $rootDomain .'/' . $item->fileurl;


            }

                $carbonDate1 = Carbon::parse($item->birthday);
                $item->bdate = $carbonDate1->format('F j, Y' );



            if(isset($item->totalscore)){



                $maxpoints = DB::table('admissiontestquestions')
                    ->where('quizid', $item->admissiontestid)
                    ->where('deleted', 0)
                    ->where('typeofquiz', '!=', null)
                    ->where('typeofquiz', '!=', 4)
                    ->where('typeofquiz', '!=', 9)
                    ->sum('points');

                $passing = DB::table('admissiongeneralprogramsetup')
                    ->where('courseid', $item->programid)
                    ->where('setup', 'like','%Exam Result%')
                    ->where('deleted', 0)
                    ->value('percentage');
            
                $item->maxpoints = $maxpoints;

                if($maxpoints != 0){

                    $item->percentage = round(($item->totalscore / $maxpoints) * 100, 2); // Rounds to 2 decimal places

                }else{

                    $item->percentage = 0; // Rounds to 2 decimal places

                }


                if($item->percentage >= $passing){
                    $item->status = 'Passed';

                }else{

                    $item->status = 'Failed';

                
                }
                
            }


        }

        return $applicants;


    }

    

    public function addApplicant(Request $request)
    {

        function codegeneratornum(){
            $length = 6;    
            return substr(str_shuffle('0123456789'),1,$length);
        }
    


        $code = codegeneratornum();

        $checkifexists = Db::table('admissiontestapplicant')
            ->where('poolingnumber', $code)
            ->get();

        if(count($checkifexists) == 0){



            $id = DB::table('admissiontestapplicant')
            ->insert([
                'applicantname'           => $request->get('name'),
                'poolingnumber'           => $code,
                'address'                 => $request->get('address'),
                'desiredprogramid'        => $request->get('course'),
                'birthday'                => $request->get('birthday'),
                'shsgrades'               => $request->get('shsgrades'),
                'jhsgrades'               => $request->get('jhsgrades'),
                'createddatetime'         => date('Y-m-d H:i:s'),
                'createdby'               => auth()->user()->id,
            ]);

        }else{

            self::addApplicant();

        }



    }

    public function updateApplicant(Request $request)
    {

        DB::table('admissiontestapplicant')
            ->where('id', $request->get('id'))
            ->update([
                'applicantname'           => $request->get('name'),
                'address'                 => $request->get('address'),
                'desiredprogramid'        => $request->get('course'),
                'birthday'                => $request->get('birthday'),
                'shsgrades'               => $request->get('shsgrades'),
                'updateddatetime'         => date('Y-m-d H:i:s'),
            ]);



    }


    public function confirmPool(Request $request)
    {

        $checkifexists = DB::table('admissiontestapplicant')
            ->where('poolingnumber',  $request->get('poolnum'))
            ->get();

        if(count($checkifexists) == 0){

    


                return 1;

        }else{

                if($checkifexists[0]->eligible == "0"){

                    return 2;

                }else{


                    return 0;

                }

        }
    }  
    
    
    public function takeTest($poolnum, $testid, $schedid, Request $request)
    {


        $checkifexists = Db::table('admissiontestapplicant')
            ->where('poolingnumber',  $poolnum)
            ->get();

        if(count($checkifexists) > 0){


            $applicantinfo =  Db::table('admissiontestapplicant')
            ->where('poolingnumber',  $poolnum)
            ->first();

            $testinfo = DB::table('admissiontest')
                ->where('testlink', $testid)
                ->first();



            $scheddetails = DB::table('admissiontestsched')
                ->where('id', $schedid)
                ->first();


            

            $checkifexistrecords = Db::table('admissiontestrecords')
            ->where('poolnum',  $poolnum)
            ->where('admissiontestid',  $testinfo->id)
            ->get();



            if(count($checkifexistrecords) == 0 ){

                $time = 0;


                if($scheddetails->hour > 0){

                    $time = $scheddetails->hour * 60;
                }

                $time += $scheddetails->minute;


                date_default_timezone_set('Asia/Manila');
                $headerid = Db::table('admissiontestrecords')
                    ->insertGetId([
                        'admissiontestid'         =>  $testinfo->id,
                        'poolnum'                 =>  $poolnum,
                        'applicantname'           =>  $checkifexists[0]->applicantname,
                        'timereamaining'           => $time,
                        'submitteddatetime'       =>  date('Y-m-d H:i:s'),
                ]);


                
                    


            }else{


                date_default_timezone_set('Asia/Manila');
                $headerid =  $checkifexistrecords[0]->id;


                DB::table('admissiontestrecords')
                    ->where('id', $checkifexistrecords[0]->id)
                    ->update([

                        'updateddatetime' =>  date('Y-m-d H:i:s'),
                    ]);


                $time = DB::table('admissiontestrecords')
                            ->where('id', $checkifexistrecords[0]->id)
                            ->value('timereamaining');


            }




            



            $admissiontestquestions = DB::table('admissiontestquestions')
                    ->where('deleted','0')
                    ->where('quizid', $testinfo->id)
                    ->where('typeofquiz', '!=', null)
                    // ->inRandomOrder()
                    ->get(); 


            foreach($admissiontestquestions as $item){

                if($item->typeofquiz == 1){

                    $choices = DB::table('admissiontestchoices')
                                    ->where('questionid',$item->id)
                                    ->where('deleted',0)
                                    ->select('description','id','answer', 'sortid')
                                    ->orderBy('sortid')
                                    ->get();

                    $item->choices = $choices;

                    $answer = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $headerid)
                                    ->where('deleted',0)
                                    ->value('choiceid');

                    if(isset($answer)){
                        $item->answer = $answer;
                    }else{

                        $item->answer = 0;

                    }


                }

                if($item->typeofquiz == 2 || $item->typeofquiz == 3 ){

                    $answer = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $headerid)
                                    ->where('deleted',0)
                                    ->value('stringanswer');
                    if(isset($answer)){
                        $item->answer = $answer;
                    }else{

                        $item->answer = "";

                    }
                }



                if($item->typeofquiz == 7 ){


                    $fillquestions = DB::table('admission_fill_question')
                                                ->where('questionid', $item->id)
                                                ->orderBy('sortid')
                                                ->get();

                    $item->fill = $fillquestions;

                    $questionid = $item->id;


                    foreach ($item->fill as $index => $fillItem) {
                            // $key = 0;
    
                            // $questionWithInputs = preg_replace_callback('/~input/', function ($matches) use ($fillItem, &$inputCounter, &$questionid, &$key) {
                            //     $inputField = '<input class="answer-field d-inline form-control q-input" data-question-type="7" data-sortid="' . ++$inputCounter . '" data-question-id="' . $fillItem->id . '"  data-borderid="' . $questionid . '" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-' . $fillItem->id . '">';
                            //     return $inputField;
                            // }, $fillItem->question);
                            // $inputCounter = 0;

                            // $fillItem->question = $questionWithInputs;

                            $key = 0;
                            $answercount = DB::table('admissiontestrecordsdetail')
                                ->where('questionid', $fillItem->id)
                                ->where('headerid', $headerid)
                                ->where('typeofquestion', 7)
                                ->where('deleted', 0)
                                ->count();


        

                            if ($answercount == 1) {


            
                                $item->answer = 1;
                    

                                $fillItem->answer  = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid', $fillItem->id)
                                    ->where('headerid', $headerid)
                                    ->where('typeofquestion', 7)
                                    ->where('deleted', 0)
                                    ->value('stringanswer');

                                $questionWithInputs = preg_replace_callback('/~input/', function ($matches) use ($fillItem, &$inputCounter, &$key , &$questionid) {
                                    $inputField = '<input class="answer-field d-inline form-control q-input" data-question-type="7" data-sortid="' . ++$inputCounter . '" data-question-id="' . $fillItem->id . '" data-borderid="' . $questionid . '" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-' . $fillItem->id . '" value="' . $fillItem->answer . '">';
                                    return $inputField;
                                }, $fillItem->question);
                                $inputCounter = 0;

                                $fillItem->question = $questionWithInputs;
                            } else if ($answercount > 1) {

                                $item->answer = 1;
                                $answer = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid', $fillItem->id)
                                    ->where('headerid', $headerid)
                                    ->where('typeofquestion', 7)
                                    ->select('stringanswer')
                                    ->orderBy('sortid', 'asc')
                                    ->get();

                                $sort = -1;
                                $questionWithInputs = preg_replace_callback('/~input/', function ($matches) use ($fillItem, &$inputCounter, &$key, &$sort, &$answer , &$questionid) {
                                    $inputField = '<input class="answer-field d-inline form-control q-input" data-question-type="7" data-sortid="' . ++$inputCounter . '" data-borderid="' . $questionid . '" data-question-id="' . $fillItem->id . '" value="' . $answer[++$sort]->stringanswer . '" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-' . $fillItem->id . '">';
                                    return $inputField;
                                }, $fillItem->question);
                                $inputCounter = 0;

                                $fillItem->answer = $answer;
                                $fillItem->question = $questionWithInputs;
                            } else {
                                $item->answer = 0;
                                $questionWithInputs = preg_replace_callback('/~input/', function ($matches) use ($fillItem, &$inputCounter, &$key , &$questionid) {
                                    $inputField = '<input class="answer-field d-inline form-control q-input" data-question-type="7" data-sortid="' . ++$inputCounter . '" data-borderid="' . $questionid . '" data-question-id="' . $fillItem->id . '" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-' . $fillItem->id . '">';
                                    return $inputField;
                                }, $fillItem->question);
                                $inputCounter = 0;

                                $fillItem->question = $questionWithInputs;
                            }
        
                    }

                                            

                }

                if($item->typeofquiz == 8){
                

                    $numberOfTimes = $item->item;


                    $newArray = []; // Declare an empty array
                    $newArray2 = []; // Declare an empty array

                    $item->answer = 0;

                    for ($i = 0; $i < $numberOfTimes; $i++) {

                        $answer  = DB::table('admissiontestrecordsdetail')
                                        ->where('questionid',$item->id)
                                        ->where('headerid', $headerid)
                                        ->where('typeofquestion', 8)
                                        ->where('sortid', $i+1)
                                        ->where('deleted',0)
                                        ->value('stringanswer');
                                        
                        $newArray[] = $answer;

                        if($answer != null){

                            $newArray2[] = $answer;

                        }
                    }

                    $item->answer = $newArray;

                    if(count($newArray2) == 0){



                        $item->alreadyanswered = 0;

                    }else{


                        $item->alreadyanswered = 1;




                    }

                }



                if($item->typeofquiz == 5){

                    $dragoption = DB::table('admission_drag_option')
                                    ->where('questionid',$item->id)
                                    ->where('deleted',0)
                                    ->select('description','id')
                                    ->get();

                    $item->drag = $dragoption;

                    $questionid = $item->id;

                    $dropquestions = DB::table('admission_drop_question')
                                                ->where('questionid', $item->id)
                                                ->orderBy('sortid')
                                                ->get();

                    $item->drop = $dropquestions;


        
                    
                    foreach($dropquestions as $index => $dropitem){

                            // $key = 0;
        

                            // $questionWithInputs = preg_replace_callback('/~input/', function($matches) use ($item, &$inputCounter, &$questionid, &$key) {
                            // $inputField = '<input class="d-inline form-control q-input drop-option q-input ui-droppable answer-field" data-question-type="5" data-borderid="' . $questionid . '" data-sortid="'.++$inputCounter.'" data-question-id="'.$item->id.'" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-'.$item->id.'" disabled>';
                            // return $inputField;
                            // }, $item->question);
                            // $inputCounter = 0;

                            // $item->question = $questionWithInputs;

                            $key = 0;
                            $answercount = DB::table('admissiontestrecordsdetail')
                                            ->where('questionid',$dropitem->id)
                                            ->where('headerid', $headerid)
                                            ->where('typeofquestion', 5)
                                            ->where('deleted',0)
                                            ->count();
                            
                            $item->answer = 0;

                            if($answercount == 1){
                                $answer  = DB::table('admissiontestrecordsdetail')
                                            ->where('questionid',$dropitem->id)
                                            ->where('headerid', $headerid)
                                            ->where('typeofquestion', 5)
                                            ->where('deleted',0)
                                            ->value('stringanswer');
                                
                                $item->answer = 1;
                                
                                $questionWithInputs = preg_replace_callback('/~input/', function($matches) use ($dropitem, &$inputCounter, &$answer,  &$key , &$questionid) {
                                $inputField = '<input class="d-inline form-control q-input drop-option q-input ui-droppable bg-primary text-white answer-field" data-borderid="' . $questionid . '" data-question-type="5" data-sortid="'.++$inputCounter.'" data-question-id="'.$dropitem->id.'" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-'.$dropitem->id.'" value="'.$answer.'" disabled>';
                                return $inputField;
                                }, $dropitem->question);
                                $inputCounter = 0;

                                $dropitem->question = $questionWithInputs;

                            }

                            else if($answercount > 1){

                                $answer = DB::table('admissiontestrecordsdetail')
                                            ->where('questionid',$dropitem->id)
                                            ->where('headerid', $headerid)
                                            ->where('typeofquestion', 5)
                                            ->select('stringanswer')
                                            ->orderby('sortid', 'asc')
                                            ->get();


                                $item->answer = 1;
                                $sort = -1;
                                $questionWithInputs = preg_replace_callback('/~input/', function($matches) use ($dropitem, &$inputCounter, &$key , &$sort , &$answer , &$questionid) {
                                $inputField = '<input class="d-inline form-control q-input drop-option q-input ui-droppable bg-primary text-white answer-field" data-borderid="' . $questionid . '" data-question-type="5" data-sortid="'.++$inputCounter.'" data-question-id="'.$dropitem->id.'" value="'.$answer[++$sort]->stringanswer.'" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-'.$dropitem->id.'" disabled>';
                                return $inputField;
                                }, $dropitem->question);
                                $inputCounter = 0;
                                

                                $item->answer = $answer;



                                $dropitem->question = $questionWithInputs;

                            }
                            else{

                                $item->answer = 0;

                                $questionWithInputs = preg_replace_callback('/~input/', function($matches) use ($dropitem, &$inputCounter, &$key, &$questionid) {
                                $inputField = '<input class="d-inline form-control q-input drop-option q-input ui-droppable answer-field" data-question-type="5" data-borderid="' . $questionid . '" data-sortid="'.++$inputCounter.'" data-question-id="'.$dropitem->id.'" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-'.$dropitem->id.'" disabled>';
                                return $inputField;
                                }, $dropitem->question);
                                $inputCounter = 0;

                                $dropitem->question = $questionWithInputs;

                            }


                    }


                }

            }

            
            
                return view('guidance.pages.admissiontest.taketest')
                        ->with('testinfo',$testinfo)
                        ->with('applicantinfo',$applicantinfo)
                        ->with('headerid',$headerid)
                        ->with('time',$time)
                        ->with('admissiontestquestions',$admissiontestquestions);

            




        }else{


                return 'error';


        }

    }
    
    
    public function saveAnswer(Request $request){

        $checkIfexist =  DB::table('admissiontestrecordsdetail')
            ->where('headerid',$request->get('headerId'))
            ->where('questionid',$request->get('question_id'))
            ->where('typeofquestion',$request->get('questionType'))
            ->where('sortid', $request->get('sortId'))
            ->count();


        if ($checkIfexist == 0) {
                $data = [
                    'headerid' => $request->get('headerId'),
                    'questionid' => $request->get('question_id'),
                    'typeofquestion' => $request->get('questionType'),
                ];

                if ($request->get('questionType') != 1) {
                    $data['stringanswer'] = $request->get('answer');

                    if($request->get('sortId') != null && $request->get('questionType') == 5 ){
                        $data['sortid'] = $request->get('sortId');
                    }

                    if($request->get('questionType') == 8 ){
                        $data['sortid'] = $request->get('sortId');
                    }

                    if($request->get('questionType') == 2 ){
                        $data['stringanswer'] = $request->get('answer');
                    }

                    if($request->get('questionType') == 3 ){
                        $data['stringanswer'] = $request->get('answer');
                    }

                    if($request->get('sortId') != null && $request->get('questionType') == 7 ){
                        $data['sortid'] = $request->get('sortId');
                    }

                    if($request->get('sortId') != null && $request->get('questionType') == 7 ){
                        $data['sortid'] = $request->get('sortId');
                    }

                } else {
                    $data['choiceid'] = $request->get('answer');
                }



                DB::table('admissiontestrecordsdetail')->insert($data);

                DB::table('admissiontestrecords')
                    ->where('id', $request->get('headerId'))
                    ->update([
                        'updateddatetime'=> \Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=> auth()->user()->id
                    ]);

                return 1;
        }else{

                if ($request->get('questionType') != 1) {
                    $data['stringanswer'] = $request->get('answer');

                    if($request->get('sortId') != null && $request->get('questionType') == 5 ){
                        $data['sortid'] = $request->get('sortId');
                    }

                    if($request->get('questionType') == 8 ){
                        $data['sortid'] = $request->get('sortId');
                    }

                    if($request->get('questionType') == 2 ){
                        $data['stringanswer'] = $request->get('answer');
                    }

                    if($request->get('questionType') == 3 ){
                        $data['stringanswer'] = $request->get('answer');
                    }

                    if($request->get('sortId') != null && $request->get('questionType') == 7 ){
                        $data['sortid'] = $request->get('sortId');
                    }


                } else {
                    $data['choiceid'] = $request->get('answer');
                }

                $data['updateddatetime'] = \Carbon\Carbon::now('Asia/Manila');
                $data['updatedby'] = auth()->user()->id;

                DB::table('admissiontestrecordsdetail')
                ->where('headerid', $request->get('headerId'))
                ->where('questionid',$request->get('question_id'))
                ->where('sortid', $request->get('sortId'))
                ->update($data);

                DB::table('admissiontestrecords')
                    ->where('id', $request->get('headerId'))
                    ->update([
                        'updateddatetime'=> \Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=> auth()->user()->id
                    ]);

                return 0;


        }

    }




    public function saveTime(Request $request)
    {

        DB::table('admissiontestrecords')
            ->where('id', $request->get('headerId'))
            ->update([
                        'timereamaining' => $request->get('countdown')
                    ]);


    }

    public function setupTable(Request $request)
    {


        $setup = DB::table('admissiontestsetup')
                    ->where('admissiontestsetup.deleted',0)
                    ->join('college_courses',function($join){
                            $join->on('college_courses.id','=','admissiontestsetup.programid');
                            $join->where('college_courses.deleted',0);
                        })

                    ->join('admissiontest',function($join){
                        $join->on('admissiontest.id','=','admissiontestsetup.testid');
                        $join->where('admissiontest.deleted',0);
                    })
                    ->select('admissiontestsetup.*', 'college_courses.courseDesc as program' , 'admissiontest.title as test' )
    
                    ->get();

        foreach($setup as $item){

            $item->search = $item->program;
        }

        return $setup;



    }

    public function setupSave(Request $request)
    {


        $checkifexists = Db::table('admissiontestsetup')
            ->where('programid', $request->get('course'))
            ->where('deleted',0)   
            ->get();

        if(count($checkifexists) == 0){

            DB::table('admissiontestsetup')
            ->insert([
                'programid'               => $request->get('course'),
                'slot'                    => $request->get('slot'),
                'testid'                  => $request->get('test'),
                'createddatetime'         => date('Y-m-d H:i:s'),
                'createdby'               => auth()->user()->id,
            ]);

            return 1;

        }else{

            DB::table('admissiontestsetup')
            ->where('programid', $request->get('course'))
            ->update([
                'slot'                    => $request->get('slot'),
                'testid'                  => $request->get('test'),
                'updateddatetime'         => date('Y-m-d H:i:s'),
                'updatedby'               => auth()->user()->id,
            ]);

            return 0;

        }



    }

    public function setupDel(Request $request)
    {



            DB::table('admissiontestsetup')
            ->where('programid', $request->get('id'))
            ->update([
                'deleted'                 => 1,
                'deleteddatetime'         => date('Y-m-d H:i:s'),
                'deletedby'               => auth()->user()->id,
            ]);

    }

    public function submitAnswer( Request $request)
    {

        $recordid = $request->get('recordid');
        $quizid = $request->get('testid');



        $quizQuestions = DB::table('admissiontestquestions')
                    ->where('admissiontestquestions.deleted','0')
                    ->where('quizid', $quizid)
                    ->where('typeofquiz', '!=', null)
                    ->get();




        foreach($quizQuestions as $item){

            if($item->typeofquiz == 1){

                $answer = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$item->id)
                                ->where('headerid', $recordid)
                                ->where('deleted',0)
                                ->value('choiceid');

                $check = DB::table('admissiontestchoices')
                                ->where('questionid',$item->id)
                                ->where('id', $answer)
                                ->where('deleted',0)
                                ->value('answer');

                if(isset($answer)){
                    $item->answer = $answer;
                    $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$item->id)
                                ->where('headerid', $recordid)
                                ->where('deleted',0)
                                ->value('id');
                        
                    $item->detailsid = $chapterdetailsid;
                    if($check == 1){


                        $item->check = 1;
                        //update points value
                        DB::table('admissiontestrecordsdetail')
                        ->where('id', $chapterdetailsid)
                        ->where('deleted', 0)
                        ->update([
                            'points' => 1


                        ]);
        
                    }


                }
            }


            if($item->typeofquiz == 10){

                $answer = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$item->id)
                                ->where('headerid', $recordid)
                                ->where('deleted',0)
                                ->value('choiceid');

                $check = DB::table('admissiontestchoices')
                                ->where('questionid',$item->id)
                                ->where('id', $answer)
                                ->where('deleted',0)
                                ->value('answer');

                if(isset($answer)){
                    $item->answer = $answer;
                    $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$item->id)
                                ->where('headerid', $recordid)
                                ->where('deleted',0)
                                ->value('id');
                        
                    $item->detailsid = $chapterdetailsid;
                    if($check == 1){

                        DB::table('admissiontestrecordsdetail')

                        ->where('id', $chapterdetailsid)
                        ->where('deleted', 0)
                        ->update([
                            'points' => 1
                        ]);
                        


                    }
                }


            }


            if($item->typeofquiz == 7 ){

                $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$item->id)
                                ->where('headerid', $recordid)
                                ->where('deleted',0)
                                ->value('id');
                        
                $item->detailsid = $chapterdetailsid;


                $fillquestions = DB::table('admission_fill_question')
                                            ->where('questionid', $item->id)
                                            ->orderBy('sortid')
                                            ->get();

                $item->fill = $fillquestions;



                foreach ($item->fill as $index => $fillItem) {
                    $key = 0;
                    $answercount = DB::table('admissiontestrecordsdetail')
                        ->where('questionid', $fillItem->id)
                        ->where('headerid', $recordid)
                        ->where('deleted', 0)
                        ->count();

                    if ($answercount == 1) {
                        $fillItem->answer  = DB::table('admissiontestrecordsdetail')
                            ->where('questionid', $fillItem->id)
                            ->where('headerid', $recordid)
                            ->where('deleted', 0)
                            ->value('stringanswer');


                        $checkanswer = DB::table('admission_fill_answer')
                                ->where('headerid',$fillItem->id)
                                ->where('sortid', 1)
                                ->value('answer');

                        $check='';

                        if (strtolower($checkanswer) == strtolower($fillItem->answer)) {


                            $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$fillItem->id)
                                ->where('headerid', $recordid)
                                ->where('sortid', 1)
                                ->where('deleted',0)
                                ->value('id');

                            //update points value
                            DB::table('admissiontestrecordsdetail')
                            ->where('id', $chapterdetailsid)
                            ->where('deleted', 0)
                            ->where('sortid', 1)
                            ->update([
                                'points' => 1


                            ]);

                        
                        }


                    } else if ($answercount > 1) {

                        $answer = DB::table('admissiontestrecordsdetail')
                            ->where('questionid', $fillItem->id)
                            ->where('headerid', $recordid)
                            ->select('stringanswer', 'sortid')
                            ->orderBy('sortid', 'asc')
                            ->get();

                        foreach($answer as $ans){

                            $checkanswer = DB::table('lesson_quiz_fill_answer')
                                ->where('headerid',$fillItem->id)
                                ->where('sortid', $ans->sortid)
                                ->value('answer');

                            if(strtolower($checkanswer) == strtolower($ans->stringanswer)){
    

                                $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$fillItem->id)
                                ->where('headerid', $recordid)
                                ->where('sortid', $ans->sortid)
                                ->where('deleted',0)
                                ->value('id');

                                //update points value
                                DB::table('admissiontestrecordsdetail')
                                ->where('id', $chapterdetailsid)
                                ->where('sortid', $ans->sortid)
                                ->where('deleted', 0)
                                ->update([
                                    'points' => 1
                                ]);

                            }
                            

                        } 

                        
                    }
                }

    

                                        

            }



            if($item->typeofquiz == 8){


                $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$item->id)
                                ->where('headerid', $recordid)
                                ->where('deleted',0)
                                ->value('id');
                        
                $item->detailsid = $chapterdetailsid;
            

                $numberOfTimes = $item->item;


                $newArray = []; // Declare an empty array

                for ($i = 0; $i < $numberOfTimes; $i++) {

                    $answer  = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('sortid', $i+1)
                                    ->where('deleted',0)
                                    ->value('stringanswer');
                    $newArray[] = $answer;
                }

                $answerArray = [];

                $score = 0;

                foreach($newArray as $key=>$new) {
                    

                    if($item->ordered == 1){
                        $countval = DB::table('admission_test_enum_answer')
                                        ->whereRaw('LOWER(answer) = ?', strtolower($new))
                                        ->where('headerid', $item->id)
                                        ->count();


                        if($countval > 0){
                            $answerArray[] = 1;
                            $score+=1;

                            $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$item->id)
                                ->where('headerid', $recordid)
                                ->where('sortid', $key + 1)
                                ->where('deleted',0)
                                ->value('id');

                                //update points value
                                DB::table('admissiontestrecordsdetail')

                                ->where('id', $chapterdetailsid)
                                ->where('deleted', 0)
                                ->update([
                                    'points' => 1


                                ]);
                        }else{
                            $answerArray[] = 0;
                        }
                    }else{

                        $countval = DB::table('admission_test_enum_answer')
                                    ->whereRaw('LOWER(answer) = ?', strtolower($new))
                                    ->where('headerid', $item->id)
                                    ->where('sortid', $key + 1)
                                    ->count();


                        if($countval > 0){
                        

                            $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$item->id)
                                ->where('headerid', $recordid)
                                ->where('sortid', $key)
                                ->where('deleted',0)
                                ->value('id');

                            //update points value
                            DB::table('admissiontestrecordsdetail')
                            ->where('id', $chapterdetailsid)
                            ->where('deleted', 0)
                            ->update([
                                'points' => 1
                            ]);

                        }

                    }
                }




            }

            if($item->typeofquiz == 5){

                $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$item->id)
                                ->where('headerid', $recordid)
                                ->where('deleted',0)
                                ->value('id');
                        
                $item->detailsid = $chapterdetailsid;

                $dragoption = DB::table('admission_drag_option')
                                ->where('questionid',$item->id)
                                ->where('deleted',0)
                                ->select('description','id')
                                ->get();

                $item->drag = $dragoption;

                $dropquestions = DB::table('admission_drop_question')
                                            ->where('questionid', $item->id)
                                            ->orderBy('sortid')
                                            ->get();

                $item->drop = $dropquestions;


                $score = 0;

                

                
                foreach($dropquestions as $index => $drop) {
                    $key = 0;
                    $answercount = DB::table('admissiontestrecordsdetail')
                        ->where('questionid', $drop->id)
                        ->where('headerid', $recordid)
                        ->where('deleted', 0)
                        ->count();

                    if ($answercount == 1) {
                        $drop->answer = DB::table('admissiontestrecordsdetail')
                            ->where('questionid', $drop->id)
                            ->where('headerid', $recordid)
                            ->where('deleted', 0)
                            ->value('stringanswer');

                        $checkanswer = DB::table('admission_drop_answer')
                            ->where('headerid', $drop->id)
                            ->where('sortid', 1)
                            ->value('answer');

                        if ($checkanswer == $drop->answer) {
                            
                            $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$fillItem->id)
                                ->where('headerid', $recordid)
                                ->where('sortid', 1)
                                ->where('deleted',0)
                                ->value('id');

                                //update points value
                                DB::table('admissiontestrecordsdetail')
                                ->where('id', $chapterdetailsid)
                                ->where('sortid', 1)
                                ->where('deleted', 0)
                                ->update([
                                    'points' => 1


                                ]);
                        }
                    
                    } else if ($answercount > 1) {  
                        $answer = DB::table('admissiontestrecordsdetail')
                            ->where('questionid', $drop->id)
                            ->where('headerid', $recordid)
                            ->select('stringanswer', 'sortid')
                            ->orderBy('sortid', 'asc')
                            ->get();
                        
                        foreach ($answer as $ans) {
                            $checkanswer = DB::table('admission_drop_answer')
                                ->where('headerid', $drop->id)
                                ->where('sortid', $ans->sortid)
                                ->value('answer');

                            if ($checkanswer == $ans->stringanswer) {
                                $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                ->where('questionid',$fillItem->id)
                                ->where('headerid', $recordid)
                                ->where('sortid', $ans->sortid)
                                ->where('deleted',0)
                                ->value('id');

                                //update points value
                                DB::table('admissiontestrecordsdetail')
                                ->where('id', $chapterdetailsid)
                                ->where('sortid', $ans->sortid)
                                ->where('deleted', 0)
                                ->update([
                                    'points' => 1
                                ]);
                            } 
                        } 
                    }


                }


            }
        }


        $checking = DB::table('admissiontest')
                    ->where('id', $quizid)
                    ->value('checking');

    
        if($checking == 1){

            $sum = DB::table('admissiontestrecordsdetail')
                        ->where('headerid', $recordid)
                        ->sum('points');

            DB::table('admissiontestrecords')
                ->where('id', $recordid)
                ->update([
                    'checked'=> 1,
                    'totalscore' => $sum,
                    'teststatus' => 0
                ]);
        
        }else{


            DB::table('admissiontestrecords')
                ->where('id', $recordid)
                ->update([
                    'teststatus'=> 1,
                ]);



        }

    
        return view('guidance.pages.admissiontest.donetake');


    }

    public function viewResponse($recordid, $testid, Request $request){
        
        $quizid = $testid;


        $quizInfo = DB::table('admissiontest')
                        ->where('id',$quizid)
                        ->first();



        $quizQuestions = DB::table('admissiontestquestions')
                    ->where('admissiontestquestions.deleted','0')
                    ->where('quizid', $quizid)
                    ->where('typeofquiz', '!=', null)
                    ->get();

        $isAnswered = false;




            foreach($quizQuestions as $item){

                if($item->typeofquiz == 1){

                    $choices = DB::table('admissiontestchoices')
                                    ->where('questionid',$item->id)
                                    ->where('deleted',0)
                                    ->select('description','id','answer', 'sortid')
                                    ->orderBy('sortid')
                                    ->get();

                    $item->choices = $choices;

                    $answer = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('deleted',0)
                                    ->value('choiceid');

                    $check = DB::table('admissiontestchoices')
                                    ->where('questionid',$item->id)
                                    ->where('id', $answer)
                                    ->where('deleted',0)
                                    ->value('answer');

                    if(isset($answer)){
                        $item->answer = $answer;
                        $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('deleted',0)
                                    ->value('id');
                            
                        $item->detailsid = $chapterdetailsid;
                        if($check == 1){


                            $item->check = 1;
                            

                        }else{
                            $item->check = 0;
                        }
                        
                    }else{
                        $item->detailsid = -1;
                        $item->answer = 0;
                        $item->check = 0;

                    }

                }



                if($item->typeofquiz == 2 || $item->typeofquiz == 3 ){

                    $answer = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('deleted',0)
                                    ->value('stringanswer');


                    if(isset($answer)){

                        $item->answer = $answer;

                        $item->detailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('deleted',0)
                                    ->value('id');

                        $item->pointsgiven = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('deleted',0)
                                    ->value('points');





                    }else{

                        $item->detailsid = -1;
                        $item->answer = "";
                        $item->pointsgiven = 0;

                    }

                    
                }

                if($item->typeofquiz == 7 ){

                    $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('deleted',0)
                                    ->value('id');
                            
                    $item->detailsid = $chapterdetailsid;


                    $fillquestions = DB::table('admission_fill_question')
                                                ->where('questionid', $item->id)
                                                ->orderBy('sortid')
                                                ->get();

                    $item->fill = $fillquestions;

                    $score = 0;


                    foreach ($item->fill as $index => $fillItem) {
                        $key = 0;
                        $answercount = DB::table('admissiontestrecordsdetail')
                            ->where('questionid', $fillItem->id)
                            ->where('headerid', $recordid)
                            ->where('deleted', 0)
                            ->count();

                        if ($answercount == 1) {
                            $fillItem->answer  = DB::table('admissiontestrecordsdetail')
                                ->where('questionid', $fillItem->id)
                                ->where('headerid', $recordid)
                                ->where('deleted', 0)
                                ->value('stringanswer');


                            $checkanswer = DB::table('admission_fill_answer')
                                    ->where('headerid',$fillItem->id)
                                    ->where('sortid', 1)
                                    ->value('answer');

                            $check='';

                            if (strtolower($checkanswer) == strtolower($fillItem->answer)) {


                                $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$fillItem->id)
                                    ->where('headerid', $recordid)
                                    ->where('sortid', 1)
                                    ->where('deleted',0)
                                    ->value('id');

                                //update points value
                                DB::table('admissiontestrecordsdetail')

                                ->where('id', $chapterdetailsid)
                                ->where('deleted', 0)
                                ->where('sortid', 1)
                                ->update([
                                    'points' => 1


                                ]);

                                $score+= 1;

                                $check = '<span><i class="fa fa-check" style="color:rgb(7, 255, 7)" aria-hidden="true"></i></span>';
                            
                            }else{
                                $check = '<span><i class="fa fa-times" style="color: red;" aria-hidden="true"></i></span>';
                            }

                            $questionWithInputs = preg_replace_callback('/~input/', function ($matches) use ($fillItem, &$inputCounter, &$key, &$check) {
                                $inputField = '<input class="answer-field d-inline form-control q-input" data-question-type="7" data-sortid="' . ++$inputCounter . '" data-question-id="' . $fillItem->id . '" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-' . $fillItem->id . '" value="' . $fillItem->answer . '">'.$check;
                                return $inputField;
                            }, $fillItem->question);
                            $inputCounter = 0;

                            $fillItem->question = $questionWithInputs;
                        } else if ($answercount > 1) {

                            $answer = DB::table('admissiontestrecordsdetail')
                                ->where('questionid', $fillItem->id)
                                ->where('headerid', $recordid)
                                ->select('stringanswer', 'sortid')
                                ->orderBy('sortid', 'asc')
                                ->get();

                            foreach($answer as $ans){

                                $checkanswer = DB::table('admission_fill_answer')
                                    ->where('headerid',$fillItem->id)
                                    ->where('sortid', $ans->sortid)
                                    ->value('answer');

                                if(strtolower($checkanswer) == strtolower($ans->stringanswer)){
                    
                                    $score+= 1;

                                    $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$fillItem->id)
                                    ->where('headerid', $recordid)
                                    ->where('sortid', $ans->sortid)
                                    ->where('deleted',0)
                                    ->value('id');

                                    //update points value
                                    DB::table('admissiontestrecordsdetail')
                                    ->where('id', $chapterdetailsid)
                                    ->where('sortid', $ans->sortid)
                                    ->where('deleted', 0)
                                    ->update([
                                        'points' => 1


                                    ]);

                                    $ans->check = '<span><i class="fa fa-check" style="color:rgb(7, 255, 7)" aria-hidden="true"></i></span>';
                                }else{
                                    $ans->check = '<span><i class="fa fa-times" style="color: red;" aria-hidden="true"></i></span>'; 
                                }
                                

                            } 

                            

                            $sort = -1;
                            $questionWithInputs = preg_replace_callback('/~input/', function ($matches) use ($fillItem, &$inputCounter, &$key, &$sort, &$answer) {
                                $inputField = '<input class="answer-field d-inline form-control q-input" data-question-type="7" data-sortid="' . ++$inputCounter . '" data-question-id="' . $fillItem->id . '" value="' . $answer[++$sort]->stringanswer . '" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-' . $fillItem->id . '">'.$answer[$sort]->check;
                                return $inputField;
                            }, $fillItem->question);
                            $inputCounter = 0;

                            $fillItem->answer = $answer;
                            $fillItem->question = $questionWithInputs;
                        } else {
                            $questionWithInputs = preg_replace_callback('/~input/', function ($matches) use ($fillItem, &$inputCounter, &$key) {
                                $inputField = '<input class="answer-field d-inline form-control q-input" data-question-type="7" data-sortid="' . ++$inputCounter . '" data-question-id="' . $fillItem->id . '" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-' . $fillItem->id . '">';
                                return $inputField;
                            }, $fillItem->question);
                            $inputCounter = 0;

                            $fillItem->question = $questionWithInputs;
                        }
                    }

                    $item->score = $score;

                                            

                }


                if($item->typeofquiz == 6 ){

                    $protocol = $request->getScheme();
                    $host = $request->getHost();

                    $rootDomain = $protocol . '://' . $host;

                    $answer = DB::table('admissiontestrecordsdetail')
                        ->where('questionid',$item->id)
                        ->where('headerid', $recordid)
                        ->where('deleted',0)
                        ->value('picurl');

                    if(isset($answer)){
                        $item->picurl = $rootDomain.'/'.$answer;

                        $item->detailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('deleted',0)
                                    ->value('id');

                        $item->pointsgiven = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('deleted',0)
                                    ->value('points');
                    }else{
                        $item->picurl = "";
                        $item->detailsid = -1;
                        $item->pointsgiven = 0;
                    }
                }

                if($item->typeofquiz == 8){


                    $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('deleted',0)
                                    ->value('id');
                            
                    $item->detailsid = $chapterdetailsid;
                

                    $numberOfTimes = $item->item;


                    $newArray = []; // Declare an empty array

                    for ($i = 0; $i < $numberOfTimes; $i++) {

                        $answer  = DB::table('admissiontestrecordsdetail')
                                        ->where('questionid',$item->id)
                                        ->where('headerid', $recordid)
                                        ->where('sortid', $i+1)
                                        ->where('deleted',0)
                                        ->value('stringanswer');
                        $newArray[] = $answer;
                    }

                    $answerArray = [];

                    $score = 0;

                    foreach($newArray as $key=>$new) {
                        

                        if($item->ordered == 1){
                            $countval = DB::table('admission_test_enum_answer')
                                            ->whereRaw('LOWER(answer) = ?', strtolower($new))
                                            ->where('headerid', $item->id)
                                            ->count();


                            if($countval > 0){
                                $answerArray[] = 1;
                                $score+=1;

                                $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('sortid', $key + 1)
                                    ->where('deleted',0)
                                    ->value('id');

                                    //update points value
                                    DB::table('admissiontestrecordsdetail')

                                    ->where('id', $chapterdetailsid)
                                    ->where('deleted', 0)
                                    ->update([
                                        'points' => 1


                                    ]);
                            }else{
                                $answerArray[] = 0;
                            }
                        }else{

                            $countval = DB::table('admission_test_enum_answer')
                                        ->whereRaw('LOWER(answer) = ?', strtolower($new))
                                        ->where('headerid', $item->id)
                                        ->where('sortid', $key + 1)
                                        ->count();


                            if($countval > 0){
                                $answerArray[] = 1;
                                $score+=1;

                                $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('sortid', $key)
                                    ->where('deleted',0)
                                    ->value('id');

                                //update points value
                                DB::table('admissiontestrecordsdetail')
                                ->where('id', $chapterdetailsid)
                                ->where('deleted', 0)
                                ->update([
                                    'points' => 1


                                ]);

                            }else{
                                $answerArray[] = 0;
                            }

                        }
                    }


                    $item->answer = $newArray;
                    $item->check =  $answerArray;
                    $item->score =  $score;


                }

                if($item->typeofquiz == 5){

                    $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$item->id)
                                    ->where('headerid', $recordid)
                                    ->where('deleted',0)
                                    ->value('id');
                            
                    $item->detailsid = $chapterdetailsid;

                    $dragoption = DB::table('admission_drag_option')
                                    ->where('questionid',$item->id)
                                    ->where('deleted',0)
                                    ->select('description','id')
                                    ->get();

                    $item->drag = $dragoption;

                    $dropquestions = DB::table('admission_drop_question')
                                                ->where('questionid', $item->id)
                                                ->orderBy('sortid')
                                                ->get();

                    $item->drop = $dropquestions;


                    $score = 0;

                    

                    
                    foreach($dropquestions as $index => $drop) {
                        $key = 0;
                        $answercount = DB::table('admissiontestrecordsdetail')
                            ->where('questionid', $drop->id)
                            ->where('headerid', $recordid)
                            ->where('deleted', 0)
                            ->count();

                        if ($answercount == 1) {
                            $drop->answer = DB::table('admissiontestrecordsdetail')
                                ->where('questionid', $drop->id)
                                ->where('headerid', $recordid)
                                ->where('deleted', 0)
                                ->value('stringanswer');

                            $checkanswer = DB::table('admission_drop_answer')
                                ->where('headerid', $drop->id)
                                ->where('sortid', 1)
                                ->value('answer');

                            if ($checkanswer == $drop->answer) {
                                $score += 1;

                                    $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$drop->id)
                                    ->where('headerid', $recordid)
                                    ->where('sortid', 1)
                                    ->where('deleted',0)
                                    ->value('id');

                                    //update points value
                                    DB::table('admissiontestrecordsdetail')
                                    ->where('id', $chapterdetailsid)
                                    ->where('sortid', 1)
                                    ->where('deleted', 0)
                                    ->update([
                                        'points' => 1


                                    ]);
                                $check = '<span><i class="fa fa-check" style="color:rgb(7, 255, 7)" aria-hidden="true"></i></span>';
                            } else {
                                $check = '<span><i class="fa fa-times" style="color: red;" aria-hidden="true"></i></span>';
                            }
                            
                            $questionWithInputs = preg_replace_callback('/~input/', function($matches) use ($drop, &$inputCounter, &$key, &$check) {
                                $inputField = '<input class="d-inline form-control q-input drop-option q-input ui-droppable bg-primary text-white answer-field" data-question-type="5" data-sortid="'.(++$inputCounter).'" data-question-id="'.$drop->id.'" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-'.$drop->id.'" value="'.$drop->answer.'" disabled>'.$check;
                                return $inputField;
                            }, $drop->question);
                            $inputCounter = 0;
                            
                            $drop->question = $questionWithInputs;
                        } else if ($answercount > 1) {
                            $answer = DB::table('admissiontestrecordsdetail')
                                ->where('questionid', $drop->id)
                                ->where('headerid', $recordid)
                                ->select('stringanswer', 'sortid')
                                ->orderBy('sortid', 'asc')
                                ->get();
                            
                            foreach ($answer as $ans) {
                                $checkanswer = DB::table('admission_drop_answer')
                                    ->where('headerid', $drop->id)
                                    ->where('sortid', $ans->sortid)
                                    ->value('answer');

                                if ($checkanswer == $ans->stringanswer) {

                                    $chapterdetailsid = DB::table('admissiontestrecordsdetail')
                                    ->where('questionid',$drop->id)
                                    ->where('headerid', $recordid)
                                    ->where('sortid', $ans->sortid)
                                    ->where('deleted',0)
                                    ->value('id');

                                    //update points value
                                    DB::table('admissiontestrecordsdetail')
                                    ->where('id', $chapterdetailsid)
                                    ->where('sortid', $ans->sortid)
                                    ->where('deleted', 0)
                                    ->update([
                                        'points' => 1


                                    ]);

                                    $score += 1;
                                    $ans->check = '<span><i class="fa fa-check" style="color:rgb(7, 255, 7)" aria-hidden="true"></i></span>';
                                } else {
                                    $ans->check = '<span><i class="fa fa-times" style="color: red;" aria-hidden="true"></i></span>'; 
                                }
                            } 

                            $sort = -1;
                            $questionWithInputs = preg_replace_callback('/~input/', function($matches) use ($drop, &$inputCounter, &$key, &$sort, &$answer) {
                                $inputField = '<input class="d-inline form-control q-input drop-option q-input ui-droppable bg-primary text-white answer-field" data-question-type="5" data-sortid="'.++$inputCounter.'" data-question-id="'.$drop->id.'" value="'.$answer[++$sort]->stringanswer.'" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-'.$drop->id.'" disabled>'.$answer[$sort]->check;
                                return $inputField;
                            }, $drop->question);
                            $inputCounter = 0;

                            $drop->answer = $answer;
                            $drop->question = $questionWithInputs;
                        } else {
                            $questionWithInputs = preg_replace_callback('/~input/', function($matches) use ($drop, &$inputCounter, &$key) {
                                $inputField = '<input class="d-inline form-control q-input drop-option q-input ui-droppable answer-field" data-question-type="5" data-sortid="'.++$inputCounter.'" data-question-id="'.$drop->id.'" style="width: 200px; margin: 10px; border-color:black" type="text" id="input-'.$drop->id.'" disabled>';
                                return $inputField;
                            }, $drop->question);
                            $inputCounter = 0;

                            $drop->question = $questionWithInputs;
                        }
                    }

                    $item->score = $score;

                    


                }


            }


        
            return view('guidance.pages.admissiontest.viewresponse')
                ->with('quizInfo',$quizInfo)
                ->with('headerid',$recordid)
                ->with('quizQuestions',$quizQuestions);

    }


    public function updatescore(Request $request)
    {

        try {
            $recordId = $request->get('detailsid');
            $score = $request->get('score');
    
            DB::table('admissiontestrecordsdetail')
                ->where('id', $recordId)
                ->update([
                    'points'=> $score,
                ]);

            return 1;
        } catch (\Exception $e) {
            return 0;
        }

    }

    public function doneCheck(Request $request)
    {

        try {
            $headerid = $request->get('headerid');

            $sum = DB::table('admissiontestrecordsdetail')
                ->where('headerid', $headerid)
                ->sum('points');

            DB::table('admissiontestrecords')
                ->where('id', $headerid)
                ->update([
                    'teststatus'=> 0,
                    'checked'=> 1,
                    'totalscore' => $sum
                ]);

            

            return 1;
        } catch (\Exception $e) {
            return 0;
        }

    }

    public function addCategory(Request $request){

        // Get the maximum sortid from admissiontestquestionscategory table
        $maxSortId = DB::table('admissiontestquestionscategory')
            ->where('testid', $request->get('quizId'))
            ->where('deleted', 0)
            ->max('sortid');

        // Calculate the new sortid value
        $newSortId = $maxSortId + 1;

        // Insert the new record with the calculated sortid
        $id = DB::table('admissiontestquestionscategory')
            ->insertGetID([
                'category' => $request->get('category'),
                'testid' => $request->get('quizId'),
                'sortid' => $newSortId, // Insert the new calculated sortid
                'createddatetime' => date('Y-m-d H:i:s'),
                'createdby' => auth()->user()->id,
            ]);

    }
 
    public function getCategory(Request $request){

        $category = DB::table('admissiontestquestionscategory')
            ->where('testid', $request->get('quizId'))
            ->where('deleted', 0)
            ->orderBy('sortid', 'asc')
            ->get();


        return $category;
    }

    public function sortCategory(Request $request){

        DB::table('admissiontestquestionscategory')
                ->where('testid', $request->get('quizId'))
                ->where('id', $request->get('id'))
                ->update([
                    'sortid' =>  $request->get('sortid')
                ]);

        return 1;
    }

    public function delCategory(Request $request){

        DB::table('admissiontestquestionscategory')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted' =>  1
                ]);

        return 1;
    }

    public function categorySelect(Request $request)
    {
        $page = $request->get('page')*10;
        $search = $request->get('search');



        $query = Db::table('admissiontestquestionscategory')
            ->select(
                'admissiontestquestionscategory.id as id',
                'admissiontestquestionscategory.category as text'
            )
            ->orderBy('admissiontestquestionscategory.sortid')
            ->where('testid', $request->get('id'))
            ->where('deleted','0');


        if ($search) {

            $query->where('admissiontestquestionscategory.category', 'LIKE', '%' . $search . '%');

        }
        
        $query =$query->take(10)
            ->skip($page)
            ->get();


        $query_count = count($query);



            return @json_encode((object)[
                    "results"=>$query,
                    "pagination"=>(object)[
                            "more"=>$query_count > ($page)  ? true :false
                    ],
                    "count_filtered"=>$query_count
                ]);
    }


    public function asssignCategory(Request $request)
    {
        DB::table('admissiontestquestions')
                ->where('id', $request->get('id'))
                ->update([
                    'category' =>  $request->get('category')
                ]);

        DB::table('admissiontest')
                ->where('id', $request->get('quizId'))
                ->update([
                    'last_category' =>  $request->get('category')
                ]);

            

        
    }

    public function getScoreCategory(Request $request)
    {
        

        $category =  DB::table('admissiontestquestionscategory')
                    ->where('testid', $request->get('testid'))
                    ->where('deleted', 0)
                    ->get();


        foreach ($category as $item){
                $question = DB::table('admissiontestquestions')
                        ->where('category', $item->id)
                        ->pluck('id');

                $item->sum = DB::table('admissiontestquestions')
                    ->where('quizid', $request->get('testid'))
                    ->whereIn('id', $question)
                    ->sum('points');

                $item->coursesubject = DB::table('admissiontestsubjectsetup')
                    ->where('admissiontestsubjectsetup.subjectid', $item->id)
                    ->join('college_courses',function($join){
                            $join->on('college_courses.id','=','admissiontestsubjectsetup.courseid');
                            $join->where('college_courses.deleted',0);
                        })
                    ->join('admissiontestquestionscategory',function($join){
                            $join->on('admissiontestquestionscategory.id','=','admissiontestsubjectsetup.subjectid');
                            $join->where('college_courses.deleted',0);
                        })
                    ->select('college_courses.courseDesc', 'admissiontestquestionscategory.category' , 'admissiontestsubjectsetup.passing')
                    ->where('admissiontestsubjectsetup.deleted', 0)
                    ->get();

                $item->sum = DB::table('admissiontestquestions')
                    ->where('quizid', $request->get('testid'))
                    ->whereIn('id', $question)
                    ->sum('points');


                $item->score = DB::table('admissiontestrecordsdetail')
                    ->where('headerid', $request->get('recordid'))
                    ->whereIn('questionid', $question)
                    ->sum('points');

                

        }


        return($category);

        


    }

    public function applicantDelete(Request $request)
    {
        DB::table('admissiontestapplicant')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted' =>  1
                ]);
    }


    public function geSubject(Request $request)
    {
        $category = DB::table('admissiontestquestionscategory')
            ->where('testid', $request->get('testid'))
            ->where('deleted', 0)
            ->orderBy('sortid', 'asc')
            ->get();


        return $category;
    }

    public function subjectSetupSave(Request $request)
    {

        $checkifexist = DB::table('admissiontestsubjectsetup')
                            ->where('courseid' , $request->get('course'))
                            ->where('testid' , $request->get('testid'))
                            ->where('subjectid' , $request->get('subId'))
                            ->where('deleted' , 0)
                            ->get();

        if(count($checkifexist) == 0){

                DB::table('admissiontestsubjectsetup')
                    ->insert([
                        'testid'          => $request->get('test'),
                        'courseid'        => $request->get('course'),
                        'passing'         => $request->get('subPassing'),
                        'subjectid'       => $request->get('subId'),
                        'createddatetime' => date('Y-m-d H:i:s'),
                    ]);

            }else{

                DB::table('admissiontestsubjectsetup')
                    ->where('id', $checkifexist[0]->id)
                    ->update([
                        'passing'         => $request->get('subPassing'),
                        'updateddatetime' => date('Y-m-d H:i:s'),
                    ]);



            }
    }

    public function generalSetupSave(Request $request)
    {


        $setup = $request->get('setup');

        $checkIfExists = DB::table('admissiongeneralprogramsetup')
            ->where('courseid', $request->input('course'))
            ->where('fixed', $request->input('fix'))
            ->where('setup', 'like', '%' . $setup . '%')
            ->where('sortid', $request->input('sortid'))
            ->where('deleted', 0)
            ->get();


        if(count($checkIfExists) == 0){

            DB::table('admissiongeneralprogramsetup')
                ->insert([
                    'setup'          => $request->get('setup'),
                    'fixed'          => $request->get('fix'),
                    'courseid'        => $request->get('course'),
                    'sortid'         => $request->get('sortid'),
                    'createddatetime' => date('Y-m-d H:i:s'),
                ]);

        }
    }


    public function generalSetupSavePercentage(Request $request)
    {


        DB::table('admissiongeneralprogramsetup')
            ->where('courseid', $request->get('course'))
            ->where('sortid',   $request->get('sortid'))
            ->update([
                'percentage' =>  $request->get('percentage')
            ]);


        return  $request->get('percentage');
    }


    public function generalSetupSaveOverall(Request $request)
    {

        DB::table('admissiongeneralprogramsetup')
            ->where('courseid', $request->get('course'))
            ->where('sortid',   $request->get('sortid'))
            ->update([
                'overalpercentage' =>  $request->get('overalpercentage')
            ]);

        return  $request->get('percentage');
    }

    
    public function applicantResult(Request $request)
    {


        $checksetup = DB::table('admissiongeneralprogramsetup')
                        ->where('deleted', 0)
                        ->where('fixed', 0)
                        ->get();

        $checkinput = DB::table('applicantprogramsetup')
                        ->where('applicantid', $request->get('id'))
                        ->where('deleted', 0)
                        ->get();

                        
        if(count($checkinput) > (count($checksetup)) &&  count($checksetup) == 0){

            $returninput = DB::table('admissiongeneralprogramsetup')
                        ->where('admissiongeneralprogramsetup.deleted', 0)
                        ->join('college_courses',function($join){
                            $join->on('college_courses.id','=','admissiongeneralprogramsetup.courseid');
                            $join->where('college_courses.deleted',0);
                        })
                        ->where('admissiongeneralprogramsetup.fixed', 0)
                        ->select('admissiongeneralprogramsetup.id','admissiongeneralprogramsetup.courseid' ,'admissiongeneralprogramsetup.setup','college_courses.courseDesc')
                        ->get();
                

            return view('guidance.pages.admissiontest.test.modalcontentinput')
                    ->with('returninput', $returninput);
        }else{


            $applicantdata = DB::table('admissiontestapplicant')
                    ->join('admissiontestrecords',function($join){
                            $join->on('admissiontestrecords.poolnum','=','admissiontestapplicant.poolingnumber');
                            $join->where('admissiontestrecords.deleted',0);
                        })
                    ->where('admissiontestapplicant.deleted',0)
                    ->where('admissiontestapplicant.id', $request->get('id'))
                    ->select('admissiontestapplicant.*' , 'admissiontestrecords.totalscore', 'admissiontestrecords.admissiontestid')
                    ->first();

            $maxpoints = DB::table('admissiontestquestions')
                ->where('quizid', $request->get('testid'))
                ->where('deleted', 0)
                ->where('typeofquiz', '!=', null)
                ->where('typeofquiz', '!=', 4)
                ->where('typeofquiz', '!=', 9)
                ->sum('points');


            $college_courses = DB::table('college_courses')
                    ->where('college_courses.deleted',0)
                    ->join('admissiongeneralprogramsetup',function($join){
                            $join->on('admissiongeneralprogramsetup.courseid','=','college_courses.id');
                            $join->where('admissiongeneralprogramsetup.deleted',0);
                        })
                    ->distinct()
                    ->select('college_courses.id', 'college_courses.courseDesc')
                    ->get();



            foreach($college_courses as $item){
                $item->setups = DB::table('admissiongeneralprogramsetup')
                                ->where('deleted',0)
                                ->where('courseid', $item->id)
                                ->orderby('sortid')
                                ->get();

                $item->examResult = round(($applicantdata->totalscore / $maxpoints) * 100, 2);

                $item->overallpoints = 0;
                foreach($item->setups as $setup){
                    

                    switch ($setup->setup) {
                        case "Exam Result":
                            if ($item->examResult >= $setup->percentage) {
                                $setup->status = "Passed";
                            } else {
                                $setup->status = "Failed";
                            }

                            $setup->result = $item->examResult;

                            $decimalpercent = $item->examResult / 100;
                            $item->overallpoints += round(($decimalpercent * $setup->overalpercentage), 2);
                            break;

                        case "JHS GWA":
                            $jhs = $applicantdata->jhsgrades;
                            if ($jhs >= $setup->percentage) {
                                $setup->status = "Qualified";
                            } else {
                                $setup->status = "Not Qualified";
                            }
                            $setup->result = $jhs;
                            $decimalpercent = $jhs / 100;
                            $item->overallpoints += round(($decimalpercent * $setup->overalpercentage), 2);
                            break;

                        case "SHS GWA":
                            $shs = $applicantdata->shsgrades;
                            if ($shs >= $setup->percentage) {
                                $setup->status = "Qualified";
                            } else {
                                $setup->status = "Not Qualified";
                            }

                            $setup->result = $shs;
                            $decimalpercent = $shs / 100;
                            $item->overallpoints += round(($decimalpercent * $setup->overalpercentage), 2);

                            break;

                        // Add more cases if needed

                        default:
                            
                            $input = DB::table('applicantprogramsetup')
                                    ->where('deleted', 0)
                                    ->where('setupid', $setup->id)
                                    ->value('result');

                            $setup->result = $input;

                            if ($input >= $setup->percentage) {

                                $setup->status = "Qualified";

                            }else{

                                $setup->status = "Not Qualified";

                            }

                            $decimalpercent = $input / 100;
                            $item->overallpoints += round(($decimalpercent * $setup->overalpercentage), 2);
                            break;
                        }


                }


            }



            // Define a custom comparison function to sort based on overallpoints
            $sortedCourses = $college_courses
                            ->sortByDesc('overallpoints')
                            ->take(5);




            return view('guidance.pages.admissiontest.test.modalcontent')
                        ->with('courses', $sortedCourses);


        }

    }


    public function saveApplicantsInput(Request $request)
    {


        DB::table('applicantprogramsetup')
            ->insert([
                'applicantid'          => $request->get('applicantid'),
                'setupid'              => $request->get('setupid'),
                'result'               => $request->get('result'),
                'createddatetime'      => date('Y-m-d H:i:s'),
        ]);

    }


    public function testSelect(Request $request)
    {


                $page = $request->get('page')*10;
                $search = $request->get('search');



                $query = Db::table('admissiontest')
                ->select(
                        'admissiontest.id as id',
                        'title as text')
                ->where('deleted','0');
                if ($search) {
                        $query->where(function ($query) use ($search) {
                                $query->orWhere('admissiontest.title', 'LIKE', '%' . $search . '%');
                        });
                }
                $query =$query->take(10)
                ->skip($page)
                ->get();


                $query_count = count($query);



                return @json_encode((object)[
                        "results"=>$query,
                        "pagination"=>(object)[
                                "more"=>$query_count > ($page)  ? true :false
                        ],
                        "count_filtered"=>$query_count
                        ]);
    }

    public function getGeneralSetup(Request $request)
    {


        $data = DB::table('admissiongeneralprogramsetup')
            ->where('deleted',0)
            ->where('courseid', $request->get('courseid'))
            ->orderby('sortid')
            ->get();



        return $data;

    }

    public function getsubgeneralsetup(Request $request)
    {



        $data = DB::table('admissiontestquestionscategory')
                ->where('admissiontestquestionscategory.testid', $request->get('testid'))
                ->where('admissiontestquestionscategory.deleted', 0)
                ->select('admissiontestquestionscategory.category' , 'admissiontestquestionscategory.id')
                ->get();

        foreach ($data as $item){

            $item->input = DB::table('admissiontestsubjectsetup')
                ->where('testid',  $request->get('testid'))
                ->where('courseid',$request->get('courseid'))
                ->where('subjectid',$item->id)
                ->select('admissiontestsubjectsetup.id','admissiontestsubjectsetup.passing')
                ->first();


        }





        return $data;


    }


    public function acceptApplicant(Request $request)
    {



        $checkifexist = DB::table('admissionacceptedapplicant')
                            ->where('applicantid' , $request->get('applicantid'))
                            ->where('deleted', 0)
                            ->get();

        date_default_timezone_set('Asia/Manila');
        $now = date('Y-m-d H:i:s');


        if(count($checkifexist) == 0){



                DB::table('admissionacceptedapplicant')
                        ->insert([
                            'applicantid'      => $request->get('applicantid'),
                            'programid'        => $request->get('programid'),
                            'remarks'          => $request->get('remarks'),
                            'status'           => $request->get('status'),
                            'createddatetime'  => $now
                        ]);


                return 0;

        }else{

                DB::table('admissionacceptedapplicant')
                            ->where('id', $checkifexist[0]->id)
                            ->update([
                                'applicantid' => $request->get('applicantid'),
                                'programid'   => $request->get('programid'),
                                'remarks'     => $request->get('remarks'),
                                'status'      => $request->get('status'),
                                'updateddatetime'  => $now
                            ]);
            
                return 1;
        }




        


    }

    public function getAcceptApplicant(Request $request)
    {



        $data = DB::table('admissionacceptedapplicant')
                            ->join('admissiontestapplicant',function($join){
                                $join->on('admissiontestapplicant.id','=','admissionacceptedapplicant.applicantid');
                                $join->where('admissiontestapplicant.deleted',0);
                            })
                            ->join('college_courses',function($join){
                                $join->on('admissionacceptedapplicant.programid','=','college_courses.id');
                                $join->where('college_courses.deleted',0);
                            })
                            ->where('admissionacceptedapplicant.deleted', 0)
                            ->select('admissionacceptedapplicant.programid','admissiontestapplicant.applicantname','college_courses.courseDesc','admissiontestapplicant.poolingnumber','admissiontestapplicant.address', 'admissiontestapplicant.address', 'admissionacceptedapplicant.createddatetime' , 'admissiontestapplicant.id','admissionacceptedapplicant.remarks', 'admissionacceptedapplicant.status')
                            ->get();
        
        
        foreach($data as $item){

            if(isset($item->createddatetime))
            {   

                    $carbonDate1 = Carbon::parse($item->createddatetime);
                    $item->date = $carbonDate1->format('F j, Y H:i A' );

        

            }else{

                $item->date = "No Data Available";

            }

            if($item->status == 0){

                $item->status = "Permanent";
                $item->statusid = "0";


            }else{

                $item->status = "Probationary";
                $item->statusid = "1";

            }


        }




        return $data;


    }

    public function submitaddApplicant(Request $request)
    {

        function codegeneratornum(){
            $length = 6;    
            return substr(str_shuffle('0123456789'),1,$length);
        }


        $code = codegeneratornum();

        $checkifexists = Db::table('admissiontestapplicant')
            ->where('poolingnumber', $code)
            ->get();

        


        if(count($checkifexists) == 0){


            try {



                $validatedData = $request->validate([
                    'applicantName' => 'required|string',
                    'applicantAddress' => 'required|string',
                    'course' => 'required|numeric', // Adjust validation rules as needed
                    'shsgrades' => 'nullable|numeric',
                    'jhsgrades' => 'nullable|numeric',
                    'birthday' => 'nullable|date',
                ]);

                $applicant = [
                    'applicantname'   => $validatedData['applicantName'],
                    'address'         => $validatedData['applicantAddress'],
                    'desiredprogramid'=> $validatedData['course'],
                    'shsgrades'         => $validatedData['shsgrades'],
                    'jhsgrades'         => $validatedData['jhsgrades'],
                    'birthday'        => $validatedData['birthday'],
                    'poolingnumber'   => $code,
                ];

                $savedApplicant = DB::table('admissiontestapplicant')->insertGetId($applicant);


                if ($request->hasFile('documents')) {
                    $destinationPath = public_path('applicantdocs'); // Adjust the destination path as needed

                    foreach ($request->file('documents') as $file) {
                        try {
                            $newFileName = time() . '_' . $file->getClientOriginalName();
                            $file->move($destinationPath, $newFileName);

                            // Update the file_path column in the database
                            DB::table('admissiontestapplicant')
                                ->where('id', $savedApplicant)
                                ->update([
                                    'fileurl' => 'applicantdocs/' . $newFileName,
                                    // Add more fields as needed
                                ]);
                        } catch (\Exception $e) {
                            // Handle exceptions if needed
                        }
                    }
                }

                $name = $validatedData['applicantName'];




                $data = [
                    'code' => $code,
                    'name' => $name,
                ];

                return response()->json($data);



            }catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }

        


        }else{

            self::addApplicant();

        }



    }


    public function applicantEligible(Request $request)
    {


        $eligible = DB::table('admissiontestapplicant')
                ->where('id', $request->get('id'))
                ->value('eligible');


        if($eligible == 1){


            DB::table('admissiontestapplicant')
                    ->where('id', $request->get('id'))
                    ->update([
                        'eligible' =>  0
                    ]);


            return 1;

        }else{

            DB::table('admissiontestapplicant')
                    ->where('id', $request->get('id'))
                    ->update([
                        'eligible' =>  1
                    ]);

            return 0;

        }
    }


    public static function programSelect(Request $request){


                $page = $request->get('page')*10;
                $search = $request->get('search');



                $query = DB::table('college_courses')
                ->select(
                        'id as id',
                        'courseDesc as text'
                )
                ->where('deleted','0');


                if ($search) {
                        $query->where(function ($query) use ($search) {
                                $query->orWhere('courseDesc', 'LIKE', '%' . $search . '%');
                        });
                        }

                
                $query =$query->take(10)
                ->skip($page)
                ->get();


                $query_count = count($query);



                return @json_encode((object)[
                        "results"=>$query,
                        "pagination"=>(object)[
                                "more"=>$query_count > ($page)  ? true :false
                        ],
                        "count_filtered"=>$query_count
                        ]);
        }

        public function deleteAccepted(Request $request)
        {
            DB::table('admissionacceptedapplicant')
                    ->where('applicantid', $request->get('id'))
                    ->update([
                        'deleted' =>  1
                    ]);
        }

        public function successview(Request $request)
        {
            $code = $request->get('code');
            $name = $request->get('name');

            return view('guidance.pages.admissiontest.test.poolingnumber')
                ->with('code', $code)
                ->with('name', $name);
        }



    }
