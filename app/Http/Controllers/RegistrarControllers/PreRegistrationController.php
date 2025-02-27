<?php

namespace App\Http\Controllers\RegistrarControllers;

use Illuminate\Http\Request;
use DB;
// use App\Student;
// use App\ParentInfo;
use Illuminate\Support\Str;
use App\PreRegistration;
use Crypt;
class PreRegistrationController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function prereg($studentstatus)
    {
        // $studentstatus = Crypt::decrypt($studentstatus);

        // if($studentstatus == 'new'){
            $sy = Db::table('sy')
                ->where('isactive','1')
                ->first();

            $get_religion = DB::select('select * from religion');

            $getquestions = Db::table('preregistration_questions')
                ->where('preregistration_questions.deleted','0')
                ->get();

            $questions = array();

            foreach($getquestions as $question){
                
                $getanswers = Db::table('preregistration_answers')
                    ->where('preregistration_answers.questionid',$question->id)
                    ->where('preregistration_answers.deleted','0')
                    ->get();
                
                array_push($questions,(object)array(
                    'question'  => $question,
                    'answers'   => $getanswers,
                    'withcorrectanswer'   => count($getanswers->where('correctanswer',1))
                ));

            }
            $gradelevels = Db::table('gradelevel')
                ->where('deleted','0')
                ->orderby('sortid','asc')
                ->get();
            
            return view("registrar.preregistration")
                    ->with('religion',$get_religion)
                    ->with('questions',$questions)
                    ->with('gradelevels',$gradelevels)
                    ->with('sy',$sy);

        // }
        // elseif($studentstatus == 'old'){

        //     $get_religion = DB::select('select * from religion');

        //     $getquestions = Db::table('preregistration_questions')
        //         ->where('preregistration_questions.deleted','0')
        //         ->get();

        //     $questions = array();

        //     foreach($getquestions as $question){
                
        //         $getanswers = Db::table('preregistration_answers')
        //             ->where('preregistration_answers.questionid',$question->id)
        //             ->where('preregistration_answers.deleted','0')
        //             ->get();
                
        //         array_push($questions,(object)array(
        //             'question'  => $question,
        //             'answers'   => $getanswers,
        //             'withcorrectanswer'   => count($getanswers->where('correctanswer',1))
        //         ));

        //     }
        //     $gradelevels = Db::table('gradelevel')
        //         ->where('deleted','0')
        //         ->orderby('sortid','asc')
        //         ->get();
                
        //     return view("registrar.preregistrationoldstudents")
        //             ->with('religion',$get_religion)
        //             ->with('questions',$questions)
        //             ->with('gradelevels',$gradelevels);
        // }
          
    }
    public function entranceexamquestions()
    {
        $getquestions = Db::table('preregistration_questions')
            ->where('preregistration_questions.deleted','0')
            ->get();
        $questions = array();
        foreach($getquestions as $question){
            // $withanswer = 0;
            $getanswers = Db::table('preregistration_answers')
                ->where('preregistration_answers.questionid',$question->id)
                ->where('preregistration_answers.deleted','0')
                ->get();
            
            array_push($questions,(object)array(
                'question'  => $question,
                'answers'   => $getanswers,
                'withcorrectanswer'   => count($getanswers->where('correctanswer',1))
            ));
        }
        // return $questions;
        return view("registrar.entranceexams")
            ->with('questions', $questions);
    }
    public function addquestions(Request $request)
    {
        // return $request->all();
        $checkifquestionExists = Db::table('preregistration_questions')
            ->where('question','like','%'.$request->get('question'))
            ->where('deleted','0')
            ->get();

        date_default_timezone_set('Asia/Manila');

        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->where('isactive','1')
            ->where('deleted','0')
            ->first();
        if(count($checkifquestionExists) == 0){
            $questionid = DB::table('preregistration_questions')
                ->insertGetId([
                    'question'          =>  $request->get('question'),
                    'createdby'         =>  $getMyid->id,
                    'createddatetime'   =>  date('Y-m-d H:i:s')
                ]);
            // Db::
            foreach($request->get('answers') as $answer){
                Db::table('preregistration_answers')
                    ->insert([
                        'questionid'        => $questionid,
                        'answer'            => $answer
                    ]);
            }
            $viewall = Db::table('preregistration_answers')
                ->where('questionid', $questionid)
                ->get();
            foreach($viewall as $eachanswer){
                if($eachanswer->answer == $request->get('correctanswer')){
                    Db::table('preregistration_answers')
                        ->where('id', $eachanswer->id)
                        ->update([
                            'correctanswer' => 1
                        ]);
                }
            }
        }
        return back();
    }
    public function editquestion(Request $request)
    {
        // return $request->all();
        DB::table('preregistration_questions')
            ->where('id', $request->get('questionid'))
            ->update([
                'question'  =>  $request->get('question')
            ]);
        foreach($request->get('choiceids') as $choiceidkey => $choiceidvalue){
            if($choiceidvalue == $request->get('correctanswer')){
                Db::table('preregistration_answers')
                    ->where('id',$choiceidvalue)
                    ->update([
                        'answer'        => $request->get('answers')[$choiceidkey],
                        'correctanswer' => 1
                    ]);
            }else{
                Db::table('preregistration_answers')
                    ->where('id',$choiceidvalue)
                    ->update([
                        'answer'        => $request->get('answers')[$choiceidkey],
                        'correctanswer' => 0
                    ]);
            }

        }
        $getallanswers = Db::table('preregistration_answers')
            ->where('deleted','0')
            ->get();
        foreach($getallanswers as $getanswer){
            if($getanswer->answer == ""){
                Db::table('preregistration_answers')
                    ->where('id', $getanswer->id)
                    ->update([
                        'deleted'   => 1
                    ]);
            }
        }
        return back();
        
    }
    public function deletequestion(Request $request)
    {
        // return $request->all();
        DB::table('preregistration_questions')
            ->where('id', $request->get('deletequestionid'))
            ->update([
                'deleted'  =>  1
            ]);
        return back();
        
    }
    public function getpayables($studentstatus,Request $request)
    {
        // return $request->all();
        $studentstatus = Crypt::decrypt($studentstatus);
        // return $studentstatus;
        if($studentstatus == 'newstudent'){
            $currentsyid = Db::table('sy')
                ->where('isactive','1')
                ->first();
            
            $getheader = Db::table('tuitionheader')
                ->where('syid', $currentsyid->id)
                ->where('levelid', $request->get('gradelevel'))
                ->where('deleted','0')
                ->get();

            if(count($getheader)>0){
            
                $getpayables = Db::table('tuitiondetail')
                                    ->join('itemclassification','tuitiondetail.classificationid','=','itemclassification.id')
                                    ->where('tuitiondetail.headerid', $getheader[0]->id)
                                    ->where('tuitiondetail.deleted','0')
                                    ->where('itemclassification.deleted','0')
                                    ->get();
                
                $payables = array();
                $totalpayable = 0;
                foreach($getpayables as $getpayable){
                    $totalpayable+=$getpayable->amount;
                    $getpayable->amount = number_format($getpayable->amount,2,'.',',');
                    array_push($payables,$getpayable);
                }
                array_push($payables,(object)array(
                    'description' => 'TOTAL',
                    'amount'      =>  number_format($totalpayable,2,'.',',')
                ));

            }
            else {

                $payables = array();
    
            }

            return $payables;
            
        }elseif($studentstatus == 'oldstudent'){

            $currentsyid = Db::table('sy')
                ->where('isactive','1')
                ->first();


            $checkstudentofexists = Db::table('studinfo')
                ->where('lastname','like','%'.$request->get('lname'))
                ->where('firstname','like','%'.$request->get('fname'))
                ->where('middlename','like','%'.$request->get('mname'))
                ->get();

            if(count($checkstudentofexists) == 0){

                return 0;

            }else{

                $checkstudentifexistsinprereg = Db::table('preregistrationold')
                    ->where('studid',$checkstudentofexists[0]->id)
                    ->where('lastgradelevelid',$request->get('lastgradelevelid'))
                    ->get();
                if(count($checkstudentifexistsinprereg) == 0){
                    if($checkstudentofexists[0]->dob == date('Y-m-d', strtotime($request->get('dob')))){
    
                        $getlevel = Db::table('gradelevel')
                            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
                            ->where('gradelevel.id', $request->get('lastgradelevelid'))
                            ->get();
    
                        if($getlevel[0]->acadprogcode == 'SHS'){
    
                            $checkiflastgradelevelexists = Db::table('sh_enrolledstud')
                                ->where('studid',$checkstudentofexists[0]->id)
                                ->where('levelid',$request->get('lastgradelevelid'))
                                ->where('promotionstatus','1')
                                ->get();
    
                            if(count($checkiflastgradelevelexists) == 0){
    
                                return 0;
    
                            }else{
    
                                $nextgradelevel = Db::table('gradelevel')
                                    ->where('gradelevel.sortid','>', $getlevel[0]->sortid)
                                    ->orderby('sortid','asc')
                                    ->take(1)
                                    ->get();
                                    
                                $getheader = Db::table('tuitionheader')
                                                ->where('syid', $currentsyid->id)
                                                ->where('levelid', $nextgradelevel[0]->id)
                                                ->where('deleted','0')
                                                ->get();
                                
                                $getpayables = Db::table('tuitiondetail')
                                    ->join('itemclassification','tuitiondetail.classificationid','=','itemclassification.id')
                                    ->where('tuitiondetail.headerid', $getheader[0]->id)
                                    ->where('tuitiondetail.deleted','0')
                                    ->where('itemclassification.deleted','0')
                                    ->get();
                                
                                $payables = array();
                                
                                $totalpayable = 0;
    
                                foreach($getpayables as $getpayable){
    
                                    $totalpayable+=$getpayable->amount;
    
                                    $getpayable->amount = number_format($getpayable->amount,2,'.',',');
    
                                    array_push($payables,$getpayable);
    
                                }
    
                                array_push($payables,(object)array(
                                    'description' => 'TOTAL',
                                    'amount'      =>  number_format($totalpayable,2,'.',',')
                                ));
    
                                $allpayables = array();
    
                                array_push($allpayables,$getheader);
    
                                array_push($allpayables,$payables);
                                
                                return $allpayables;
                            }
                        }else{
    
                            $checkiflastgradelevelexists = Db::table('enrolledstud')
                                ->where('studid',$checkstudentofexists[0]->id)
                                ->where('levelid',$request->get('lastgradelevelid'))
                                ->where('promotionstatus','1')
                                ->get();
    
                            if(count($checkiflastgradelevelexists) == 0){
    
                                return 0;
    
                            }else{
    
                                $nextgradelevel = Db::table('gradelevel')
                                    ->where('gradelevel.sortid','>', $getlevel[0]->sortid)
                                    ->orderby('sortid','asc')
                                    ->take(1)
                                    ->get();
                                    
                                $getheader = Db::table('tuitionheader')
                                    ->where('syid', $currentsyid->id)
                                    ->where('levelid', $nextgradelevel[0]->id)
                                    ->where('deleted','0')
                                    ->get();
                                
                                $getpayables = Db::table('tuitiondetail')
                                    ->join('itemclassification','tuitiondetail.classificationid','=','itemclassification.id')
                                    ->where('tuitiondetail.headerid', $getheader[0]->id)
                                    ->where('tuitiondetail.deleted','0')
                                    ->where('itemclassification.deleted','0')
                                    ->get();
                                
                                $payables = array();
                                
                                $totalpayable = 0;
    
                                foreach($getpayables as $getpayable){
    
                                    $totalpayable+=$getpayable->amount;
    
                                    $getpayable->amount = number_format($getpayable->amount,2,'.',',');
    
                                    array_push($payables,$getpayable);
    
                                }
    
                                array_push($payables,(object)array(
                                    'description' => 'TOTAL',
                                    'amount'      =>  number_format($totalpayable,2,'.',',')
                                ));
    
                                $allpayables = array();
    
                                array_push($allpayables,$getheader);
    
                                array_push($allpayables,$payables);
    
                                return $allpayables;
    
                            }
    
                        }
    
                    }else{
    
                        return 0;
    
                    }
                }else{
                    return 1;
                }

            }

        }
        
    }
    public function storeprereg($studentstatus,Request $request)
    {

         // return $request->all();

         date_default_timezone_set('Asia/Manila');
         // return $request->get('questionids');
         // return 'asdasdas';
         // return $request->get('gender');
         $studentstatus = Crypt::decrypt($studentstatus);
         // return $studentstatus;
         if($studentstatus == 'newstudent'){
         
             $save = new PreRegistration;
             $save->studtype = $request->get('studentstatus');
             $save->last_name = strtoupper($request->get('lname'));
             $save->first_name = strtoupper($request->get('fname'));
             $save->middle_name = strtoupper($request->get('mname'));
             $save->suffix = $request->get('suffix');
             $save->gender = $request->get('gender');
             $save->dob = $request->get('dob');
             // $save->queing_code = Str::random(6);
             $save->contact_number = str_replace('-','',$request->get('student_contact_no'));
             $save->status = 0;
             $save->gradelevelid = $request->get('gradelevel');
             $save->date_created = \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');
     
             $count = DB::table('preregistration')
                            ->where('last_name',$request->get('lname'))
                            ->where('first_name',$request->get('fname'))
                            ->where('middle_name',$request->get('mname'))
                            ->get();
        
          
     
             if(count($count)==0){
 
                 $save->save();
     
                 $queuecode = sprintf('%05d', $save->id);
                 Db::table('preregistration')
                     ->where('id',$save->id)
                     ->update([
                         'queing_code'   => $queuecode
                     ]);
                 // DB::update('update preregistration set queing_code = ? where id = ?',[$queuecode,$save->id]);
                 $code = DB::table('preregistration')
                     ->select('queing_code')
                     ->where('id',$save->id)
                     ->get();
     
                 // DB::table('preregscholasticinfo')
                 //     ->insert([
                 //         'preregid' => $save->id,
                 //         'refusedadmission' => $request->get('refused'),
                 //         'refusedreason' => $request->get('refusedadmission'),
                 //         'difficulties' => $request->get('disciplinarydifficulties'),
                 //         'academiclevel' => $request->get('disciplacademiclevelinarydifficulties'),
                 //         'failedinschool' => $request->get('failedinschool'),
                 //         'failedreason' => $request->get('failedinschoolreason')
                 //     ]);
                 // DB::table('preregreligiousinfo')
                 //     ->insert([
                 //         'preregid' => $save->id,
                 //         'churchattended' => $request->get('churchattended'),
                 //         'churchaddress' => $request->get('churchaddress'),
                 //         'pastor' => $request->get('churchpastor'),
                 //         'churchinvolvement' => $request->get('churchinvolvement')
                 //     ]);
                 // DB::table('preregmedicalinfo')
                 //     ->insert([
                 //         'preregid' => $save->id,
                 //         'measles' => $request->get('measles'),
                 //         'pneumonia' => $request->get('pneumonia'),
                 //         'heartdisease' => $request->get('heartdisease'),
                 //         'chickenpox' => $request->get('chickenpox'),
                 //         'nosebleeding' => $request->get('nosebleeding'),
                 //         'dentaldefects' => $request->get('dentaldefects'),
                 //         'frequenturination' => $request->get('frequenturination'),
                 //         'speechdiff' => $request->get('speechdifficulty'),
                 //         'hearingdiff' => $request->get('hearingdifficulty'),
                 //         'dizziness' => $request->get('dizziness'),
                 //         'allergy' => $request->get('allergy'),
                 //         'immubcg' => $request->get('bcjimmu'),
                 //         'immumeasles' => $request->get('measlesimmu'),
                 //         'immudiptheria' => $request->get('diptheriaimmu'),
                 //         'immupolio' => $request->get('polioimmu'),
                 //         'immutetanus' => $request->get('tetanusimmu'),
                 //         'immutyphoid' => $request->get('typhoidimmu')
                 //     ]);
 
 
                     // $questionids = array();
                     // foreach($request->get('questionids') as $answeredquestion){
                     //     $answerid = 0;
                     //     if (in_array($answeredquestion, $questionids)) {
 
                     //     }else{
                     //         array_push($questionids,$answeredquestion);
                     //         foreach($request->except(
                     //             '_token',
                     //             'questionids',
                     //             'studentstatus',
                     //             'lname',
                     //             'fname',
                     //             'mname',
                     //             'suffix',
                     //             'gender',
                     //             'dob',
                     //             'student_contact_no',
                     //             'gradelevel'
                     //         ) as $answerkey => $answervalue){
                     //             $answer = explode('answer', $answerkey);
                     //             // return $answer;
                     //             if($answeredquestion == $answer[1]){
                     //                 $answerid = $answervalue;
                     //             }
                     //         }
                     //         Db::table('preregistration_examination')
                     //             ->insert([
                     //                 'preregistrationid' => $save->id,
                     //                 'questionid'        => $answeredquestion,
                     //                 'answerid'          => $answerid
                     //             ]);
                     //     }
                     // }
 
            
                     
                 return view('registrar.preregistrationgetcode')
                                ->with('fullname',$request->get('lname').', '.$request->get('fname'))
                                ->with('code',$code);
                         
             }
             elseif(count($count)!=0){
              
                $sy = Db::table('sy')
                        ->where('isactive','1')
                        ->first();

                $gradelevels = Db::table('gradelevel')
                        ->where('deleted','0')
                        ->orderby('sortid','asc')
                        ->get();

                return view('othertransactions.preregistration.preenrolled');

                //  return view('registrar.preregistration')
                //             ->with('name',$save->last_name.', '.$save->first_name.' '.$save->middle_name)
                //             ->with('gradelevels',$gradelevels)
                //             ->with('sy',$sy);

             } 
         }else{
             $getstudinfo = Db::table('studinfo')
                 ->where('firstname','like','%'.$request->get('fname'))
                 ->where('middlename','like','%'.$request->get('mname'))
                 ->where('lastname','like','%'.$request->get('lname'))
                 ->first();
             $checkifstudentexists = Db::table('preregistrationold')
                 ->where('studid',$getstudinfo->id)
                 ->where('lastgradelevelid',$request->get('lastgradelevelid'))
                 ->get();
             if(count($checkifstudentexists) == 0){
                 Db::table('preregistrationold')
                     ->insert([
                         'studid'            =>  $getstudinfo->id,
                         'fname'             =>  $getstudinfo->firstname,
                         'mname'             =>  $getstudinfo->middlename,
                         'lname'             =>  $getstudinfo->lastname,
                         'suffix'            =>  $getstudinfo->suffix,
                         'dob'               =>  $getstudinfo->dob,
                         'lastgradelevelid'  =>  $request->get('lastgradelevelid'),
                         'code'              =>  $getstudinfo->sid,
                         'createddatetime'   =>  date('Y-m-d H:i:s')
                     ]);
                 $code = array();
                 array_push($code, (object)array(
                     'queing_code'   => $getstudinfo->sid
                 ));
                 return view('registrar.preregistrationgetcode')
                         ->with('fullname',$getstudinfo->lastname.', '.$getstudinfo->firstname.' '.$getstudinfo->middlename.'')
                         ->with('code',$code);
             }else{
                 return back()->with('message',$request->get('lname').', '.$request->get('fname').' '.$request->get('mname'));
             }
         }            

    }
    // public function newpreregold(Request $request)
    // {
        
    //     date_default_timezone_set('Asia/Manila');
    //     return $request->all();

    // }
    public function entranceexamresults($action, Request $request)
    {
        // return 'adsd';
        $action = Crypt::decrypt($action);
        if($action == 'dashboard'){
            $students = Db::table('preregistration')
                ->select('id','first_name','middle_name','last_name','suffix','queing_code','studtype')
                ->get();
            $answerkeys = Db::table('preregistration_questions')
                ->select('preregistration_questions.id as questionid','preregistration_answers.id as answerid')
                ->join('preregistration_answers','preregistration_questions.id','=','preregistration_answers.questionid')
                ->where('preregistration_answers.deleted','0')
                ->where('preregistration_questions.deleted','0')
                ->where('preregistration_answers.correctanswer','1')
                ->get();
            // return $answerkeys;
            $studentstookexam = array();
            $totalitems = count($answerkeys);
            foreach($students as $student){
                $getexamresults = Db::table('preregistration_examination')
                    ->where('preregistrationid', $student->id)
                    ->get();
                $totalcorrect = 0;
                foreach($answerkeys as $answerkey){
                    foreach($getexamresults as $getexamresult){
                        $correct = false;
                        if($answerkey->questionid == $getexamresult->questionid){
                            if($answerkey->answerid == $getexamresult->answerid){
                                $correct = true;
                            }
                        }
                        if($correct == true){
                            $totalcorrect+=1;
                        }
                    }
                    }
                array_push($studentstookexam,(object)array(
                    'studentinfo'   =>  $student,
                    'score'         =>  $totalcorrect.'/'.$totalitems
                ));
                
            }
                // return $studentstookexam;
            return view("registrar.entranceexamresults")
                ->with('results', $studentstookexam);
        }
        elseif($action == 'viewresults'){
            // return $request->all();
            $student = Db::table('preregistration')
                ->select('id','first_name','middle_name','last_name','suffix','queing_code')
                ->where('id',$request->get('preregstudid'))
                ->first();
            if($student->suffix == null){
                $student->suffix = "";
            }
            $getexamanswers = Db::table('preregistration_examination')
                ->join('preregistration_answers','preregistration_examination.answerid','=','preregistration_answers.id')
                ->where('preregistration_examination.preregistrationid', $student->id)
                ->where('preregistration_answers.deleted','0')
                ->get();
            // return $getexamanswers;
            $getquestions = Db::table('preregistration_questions')
                ->where('preregistration_questions.deleted','0')
                ->get();

            $questions = array();

            foreach($getquestions as $question){

                $getanswers = Db::table('preregistration_answers')
                    ->where('preregistration_answers.questionid',$question->id)
                    ->where('preregistration_answers.deleted','0')
                    ->get();
                
                array_push($questions,(object)array(
                    'question'  => $question,
                    'answers'   => $getanswers,
                    'withcorrectanswer'   => count($getanswers->where('correctanswer',1))
                ));

            }

            $examresults = array();

            array_push($examresults,(object)array(
                'studentinfo'   =>  $student,
                'questions'     =>  $questions,
                'answerresults' => $getexamanswers
            ));

            return $examresults;
        }
    }
    
    public function senior()
    {
        return view("registrar.preregistrationsenior");
          
    }

   
}
