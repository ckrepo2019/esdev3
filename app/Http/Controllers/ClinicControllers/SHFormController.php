<?php

namespace App\Http\Controllers\ClinicControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\SchoolClinic\SchoolClinic;
class SHFormController extends Controller
{
    public function recordsindex()
    {
        // $users  = SchoolClinic::users();
        return Session::get('currentPortal');
        return view('clinic.records.index')
            ->with('users', $users);
    }
    public function getform(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        $userid         = $request->get('userid');
        $selectedform   = $request->get('selectedform');

        $userinfo = DB::tabLe('users')
                        ->where('id',$userid)
                        ->first();


        if(isset($userinfo)){
            // return collect($userinfo);
            $studentinfo = DB::table('studinfo')
                            ->where('id', $userid)
                            ->where('deleted','0')
                            ->first();
        }

        // $studentinfo = DB::table('studinfo')
        //     ->where('userid', $userid)
        //     ->where('deleted','0')
        //     ->first();

        if($selectedform == 'form1p1')
        {
            return view('clinic.forms.form1p1');
        }
        elseif($selectedform == 'form1a')
        {

            $questions = DB::table('clinic_shdform1a_questions')
                ->select('clinic_shdform1a_questions.id','clinic_shdform1a_questions.question','clinic_shdform1a_questions.yesorno as queyesorno')
                ->where('clinic_shdform1a_questions.deleted','0')
                ->get();

            $answers = DB::table('clinic_shdform1a')
                ->select('clinic_shdform1a.id','clinic_shdform1a.questionid','clinic_shdform1a.yesorno as ansyesorno','clinic_shdform1a_answers.choiceid','clinic_shdform1a_answers.description')
                ->leftJoin('clinic_shdform1a_answers','clinic_shdform1a.id','=','clinic_shdform1a_answers.headerid')
                ->where('userid', $userid)
                ->where('clinic_shdform1a.deleted','0')
                ->where('clinic_shdform1a_answers.deleted','0')
                ->get();
                
                
            $questionsanswered = DB::table('clinic_shdform1a')
                ->where('userid', $userid)
                ->where('deleted','0')
                ->get();
                
            if(count($questions)>0)
            {
                foreach($questions as $question)
                {

                    $choices = DB::table('clinic_shdform1a_choices')
                            ->where('questionid', $question->id)
                            ->where('deleted','0')
                            ->get();

                    if(count($choices)>0)
                    {
                        foreach($choices as $choice)
                        {
                            if(collect($answers)->where('questionid', $question->id)->where('choiceid', $choice->id)->count() == 0)
                            {
                                $choice->checked = 0;
                                $choice->description = "";
                            }else{
                                $choice->checked = 1;
                                $choice->description = collect($answers)->where('questionid', $question->id)->where('choiceid', $choice->id)->first()->description;
                            }
                        }
                    }
                    if(count($questionsanswered) == 0)
                    {
                        $question->ansyesorno = 0;
                    }
                    else{
                        if( collect($questionsanswered)->where('questionid',$question->id)->count() == 0)
                        {
                            $question->ansyesorno = 0;
                        }else{
                            $question->ansyesorno = collect($questionsanswered)->where('questionid',$question->id)->first()->yesorno;
                        }
                    }

                    $question->choices = $choices;

                }
            }
            // return $questions;
            return view('clinic.forms.form1a')
                ->with('studentinfo', $studentinfo)
                ->with('questions', $questions);
        }
        elseif($selectedform == 'form1b')
        {
            $findingdescs = DB::table('clinic_shdform1b_desc')
                ->where('deleted','0')
                ->get();

            $findings = DB::table('clinic_shdform1b_findings')
                ->where('deleted','0')
                ->where('userid',$userid)
                ->get();

            if(count($findingdescs)>0)
            {
                foreach($findingdescs as $findingdesc)
                {
                    $checkifexists = collect($findings)->where('descid', $findingdesc->id)->values();
                    if(count($checkifexists) == 0)
                    {
                        $findingdesc->answers = array();
                    }else{
                        $answers = array();
                        foreach ($checkifexists as $checkifexist)
                        {
                            array_push($answers, (object)array(
                                'levelid'       => $checkifexist->levelid,
                                'finding'       => $checkifexist->finding,
                                'monthchecked'  => $checkifexist->monthchecked
                            ));
                        }
                        $findingdesc->answers = $answers;
                    }
                }
            }
            
            $gradelevels = DB::table('gradelevel')
                ->where('deleted','0')
                ->whereIn('acadprogid',[2,3,4,5])
                ->orderBy('sortid','asc')
                ->get();
			
            return view('clinic.forms.form1b')
                ->with('studentinfo', $studentinfo)
                ->with('findings',$findings)
                ->with('findingdescs',$findingdescs)
                ->with('gradelevels',$gradelevels);
        }
        elseif($selectedform == 'form1c')
        {            
            $userinfo = collect(SchoolClinic::users())->where('userid', $userid)->first();

            $complaints = DB::table('clinic_complaints')
                        ->select('clinic_complaints.id','clinic_complaints.description','clinic_complaints.cdate','clinic_complaints.actiontaken','teacher.title','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                        ->leftJoin('teacher','clinic_complaints.createdby','=','teacher.userid')
                        ->where('clinic_complaints.userid', $userid)
                        ->where('clinic_complaints.deleted','0')
                        ->orderBy('clinic_complaints.cdate','asc')
                        ->get();
                   
            return view('clinic.forms.form1c')
                ->with('userinfo', $userinfo)
                ->with('complaints', $complaints);
        }
        elseif($selectedform == 'form2a')
        {
            $userinfo = collect(SchoolClinic::users())->where('userid', $userid)->first();

            $complaints = DB::table('clinic_complaints')
                        ->select('clinic_complaints.id','clinic_complaints.description','clinic_complaints.cdate','clinic_complaints.actiontaken','teacher.title','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                        ->leftJoin('teacher','clinic_complaints.createdby','=','teacher.userid')
                        ->where('clinic_complaints.userid', $userid)
                        ->where('clinic_complaints.deleted','0')
                        ->orderBy('clinic_complaints.cdate','asc')
                        ->get();
                   
            return view('clinic.forms.form1c')
                ->with('userinfo', $userinfo)
                ->with('complaints', $complaints);
        }
        elseif($selectedform == 'form4')
        {   
            $famhislist = DB::table('clinic_shdform4_famhislist')
                ->where('deleted','0')
                ->get();

            $pastmedhislist = DB::table('clinic_shdform4_pastmedhislist')
                ->where('deleted','0')
                ->get();

            $lasttakenexams = DB::table('clinic_shdform4_lasttakenexam')
                ->where('deleted','0')
                ->get();
                
            $userinfo = collect(SchoolClinic::users())->where('userid', $userid)->first();

            return view('clinic.forms.form4')
                ->with('userinfo', $userinfo)
                ->with('famhislist', $famhislist)
                ->with('pastmedhislist', $pastmedhislist)
                ->with('lasttakenexams', $lasttakenexams);
        }

    }


// SUBMIT FUNCTIONS
    public function submitform1a(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        // return $request->all();
        $userid              = $request->get('userid');
        $yesornovalues       = $request->get('yesornovalues');
        $answervalues        = $request->get('answervalues');
        // $descriptionvalues   = $request->get('descriptionvalues');
        // $descriptionvalues   = $request->get('descriptionvalues');

        $questionvalues = array();
        $descriptionvalues = array();
		
        if(count($yesornovalues)>0)
        {
            foreach($yesornovalues as $yesornovalue)
            {
                array_push($questionvalues, (object)$yesornovalue);
            }
        }
        //if(count($request->get('descriptionvalues'))>0)
        //{
			
			
            //foreach($request->get('descriptionvalues') as $descval)
            //{
               // array_push($descriptionvalues, (object)$descval);
            //}
        //}
        // return $descriptionvalues[0]->description;
        $answervalues = collect($answervalues)->groupBy('questionid');
        
        if(count($answervalues)>0)
        {
            foreach($answervalues as $answerkey => $answerval)
            {
                $checkquestionidifexists = collect($questionvalues)->where('questionid', $answerkey)->values();
                
                if(count($checkquestionidifexists) == 0)
                {
                    array_push($questionvalues, (object)array(
                        'questionid'        => $answerkey,
                        'yesorno'           => 0,
                        'answers'           => (object)$answerval
                    ));
                }else{
                    $answersarray = array();
                    foreach($answerval as $answer)
                    {
                        array_push($answersarray,(object)[
                            'choiceid'      => $answer['choiceid']
                        ]);
                    }
                    foreach($questionvalues as $questionvalue)
                    {
                        if($questionvalue->questionid == $answerkey)
                        {
                            $questionvalue->answers = $answersarray;
                        }
                    }
                }
            }
        }
        if(count($questionvalues)>0)
        {
            foreach($questionvalues as $questionvalue)
            {
                if(!isset($questionvalue->answers))
                {
                    $questionvalue->answers = array();
                }
            }
        }
        
        try{
            if(count($questionvalues)>0)
            {
                foreach($questionvalues as $questionvalue)
                {
                    $questionvaldescs = collect($descriptionvalues)->where('questionid',$questionvalue->questionid)->values();

                    $checkifexists = DB::table('clinic_shdform1a')
                        ->where('userid', $userid)
                        ->where('questionid', $questionvalue->questionid)
                        ->where('deleted','0')
                        ->first();
    
                    if($checkifexists)
                    {
                        $headerid = $checkifexists->id;
                        DB::table('clinic_shdform1a')
                            ->where('id', $checkifexists->id)
                            ->update([
                                'yesorno'           =>  $questionvalue->yesorno,
                                'updatedby'         => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }else{
                        $headerid = DB::table('clinic_shdform1a')
                            ->insertGetId([
                                'userid'            => $userid,
                                'questionid'        => $questionvalue->questionid,
                                'yesorno'           => $questionvalue->yesorno,
                                'createdby'         => auth()->user()->id,
                                'createddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }

                    if(count($questionvalue->answers) == 0)
                    {
                        Db::table('clinic_shdform1a_answers')
                            ->where('headerid', $headerid)
                            ->where('deleted','0')
                            ->update([
                                'deleted'               => 1,
                                'deletedby'             => auth()->user()->id,
                                'deleteddatetime'       => date('Y-m-d H:i:s')
                            ]);
                    }else{
                        
                        $deleteanswers = DB::table('clinic_shdform1a_answers')
                            ->where('headerid', $headerid)
                            ->whereNotIn('choiceid',collect($questionvalue->answers)->pluck('choiceid'))
                            ->where('deleted','0')
                            ->get();
                            
                        foreach($questionvalue->answers as $answerval)
                        {
                            try{
                                $answeridchoice = $answerval->choiceid;
                            }catch(\Exception $error)
                            {
                                $answeridchoice = $answerval['choiceid'];
                            }

                            $answervaldesc = collect($questionvaldescs)->where('choiceid',$answeridchoice)->values();

                            $desc = "";

                            if(count($answervaldesc)>0)
                            {
                                $desc = $answervaldesc[0]->description;
                            }

                            try{
                                $checkanswerifexists = Db::table('clinic_shdform1a_answers')
                                    ->where('headerid', $headerid)
                                    ->where('choiceid',$answeridchoice)
                                    ->where('deleted','0')
                                    ->get();
    
                                if(count($checkanswerifexists) == 0)
                                {
                                    DB::table('clinic_shdform1a_answers')
                                        ->insert([
                                            'headerid'           => $headerid,
                                            'choiceid'           => $answeridchoice,
                                            'description'        => $desc,
                                            'createdby'          => auth()->user()->id,
                                            'createddatetime'    => date('Y-m-d H:i:s')
                                        ]);
                                }else{
                                    DB::table('clinic_shdform1a_answers')
                                        ->where('id', $checkanswerifexists[0]->id)
                                        ->update([
                                            'choiceid'          =>  $answeridchoice,
                                            'description'       => $desc,
                                            'updatedby'         => auth()->user()->id,
                                            'updateddatetime'   => date('Y-m-d H:i:s')
                                        ]);
                                }
                            }catch(\Exception $error)
                            {
                                return 0;
                            }
                        }

                        if(count($deleteanswers)>0)
                        {
                            foreach($deleteanswers as $deleteanswer)
                            {
                                DB::table('clinic_shdform1a_answers')
                                    ->where('id', $deleteanswer->id)
                                    ->update([
                                        'deleted'           =>  1,
                                        'deletedby'         => auth()->user()->id,
                                        'deleteddatetime'   => date('Y-m-d H:i:s')
                                    ]);
                            }
                        }
                    }
                }
            }
            return 1;
        }catch(\exception $error){
            return $error;
        }
    }
    public function submitform1b(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        // $request = json_encode($request);
        // return $request->all();
        // return $request->get('descid');
        $userid             = $request->get('userid');
        $descid             = $request->get('descid');
        $inputvalues        = json_decode($request->get('inputs'));
        $monthcheckvalues   = json_decode($request->get('monthchecks'));
        $checkvalues        = json_decode($request->get('checkboxes'));
        // return $monthcheckvalues;
        $checkifexists = Db::table('clinic_shdform1b_findings')
            ->where('userid', $userid)
            ->where('descid',$descid)
            ->where('deleted','0')
            ->get();

        if(count($inputvalues) > 0)
        {
            foreach($inputvalues as $inputvalue)
            {
                $countexists = collect($checkifexists)->where('levelid', $inputvalue->levelid)->values();
                
                if(count($countexists) == 0)
                {
                    if(!empty($inputvalue->inputvalue))
                    {
                        DB::table('clinic_shdform1b_findings')
                            ->insert([
                                'userid'                => $userid,
                                'levelid'               => $inputvalue->levelid,
                                'descid'                => $descid,
                                'finding'               => $inputvalue->inputvalue,
                                'createdby'             => auth()->user()->id,
                                'createddatetime'       => date('Y-m-d H:i:s')
                            ]);
                    }
                }else{
                    DB::table('clinic_shdform1b_findings')
                        ->where('id', $countexists[0]->id)
                        ->update([
                            'finding'               => $inputvalue->inputvalue,
                            'updatedby'             => auth()->user()->id,
                            'updateddatetime'       => date('Y-m-d H:i:s')
                        ]);
                }
            }

        }
        if(count($checkvalues) > 0)
        {
            foreach($checkvalues as $checkvalue)
            {
                $countexists = collect($checkifexists)->where('levelid', $checkvalue->levelid)->values();
                
                if(count($countexists) == 0)
                {
                    if(!empty($checkvalue->checkstatus))
                    {
                        DB::table('clinic_shdform1b_findings')
                            ->insert([
                                'userid'                => $userid,
                                'levelid'               => $checkvalue->levelid,
                                'descid'                => $descid,
                                'finding'               => $checkvalue->checkstatus,
                                'createdby'             => auth()->user()->id,
                                'createddatetime'       => date('Y-m-d H:i:s')
                            ]);
                    }
                }else{
                    DB::table('clinic_shdform1b_findings')
                        ->where('id', $countexists[0]->id)
                        ->update([
                            'finding'               => $checkvalue->checkstatus,
                            'updatedby'             => auth()->user()->id,
                            'updateddatetime'       => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        if(count($monthcheckvalues) > 0)
        {
            foreach($monthcheckvalues as $monthcheckvalue)
            {
                $countexists = collect($checkifexists)->where('levelid', $monthcheckvalue->levelid)->where('monthchecked', $monthcheckvalue->monthchecked)->values();
                
                if(count($countexists) == 0)
                {
                    if($monthcheckvalue->monthcheckstatus == 1)
                    {
                        DB::table('clinic_shdform1b_findings')
                            ->insert([
                                'userid'                => $userid,
                                'levelid'               => $monthcheckvalue->levelid,
                                'descid'                => $descid,
                                'finding'               => $monthcheckvalue->monthcheckstatus,
                                'monthchecked'          => $monthcheckvalue->monthchecked,
                                'createdby'             => auth()->user()->id,
                                'createddatetime'       => date('Y-m-d H:i:s')
                            ]);
                    }
                }else{
                    DB::table('clinic_shdform1b_findings')
                        ->where('id', $countexists[0]->id)
                        ->update([
                            'finding'               => $monthcheckvalue->monthcheckstatus,
                            'updatedby'             => auth()->user()->id,
                            'updateddatetime'       => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }
        /////please checkdb
    }
    public function form4_addnewfamhisoption(Request $request)
    {
        $checkifexists = DB::table('clinic_shdform4_famhislist')
            ->where('description','like','%'.$request->get('newillnessoption').'%')
            ->where('deleted','0')
            ->count();

        if($checkifexists == 0)
        {
            DB::table('clinic_shdform4_famhislist')
                ->insert([
                    'description'       => $request->get('newillnessoption'),
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }else{
            return 2;
        }
    }
    public function form4_deletefamhisoption(Request $request)
    {
        try{

            DB::table('clinic_shdform4_famhislist')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
            return 1;
        }catch(\Exception $erro)
        {
            return 0;
        }
    }
    public function form4_addnewpastmedhisoption(Request $request)
    {
        $checkifexists = DB::table('clinic_shdform4_pastmedhislist')
            ->where('description','like','%'.$request->get('newillnessoption').'%')
            ->where('deleted','0')
            ->count();

        if($checkifexists == 0)
        {
            DB::table('clinic_shdform4_pastmedhislist')
                ->insert([
                    'description'       => $request->get('newillnessoption'),
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }else{
            return 2;
        }
    }
    public function form4_deletepastmedhisoption(Request $request)
    {
        try{

            DB::table('clinic_shdform4_pastmedhislist')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
            return 1;
        }catch(\Exception $erro)
        {
            return 0;
        }
    }
    public function form4_addlasttaken(Request $request)
    {
        $checkifexists = DB::table('clinic_shdform4_lasttakenexam')
            ->where('description','like','%'.$request->get('description').'%')
            ->where('deleted','0')
            ->count();

        if($checkifexists == 0)
        {
            DB::table('clinic_shdform4_lasttakenexam')
                ->insert([
                    'description'       => $request->get('description'),
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }else{
            return 2;
        }
    }
    public function  form4_deletelasttaken(Request $request)
    {
        try{

            DB::table('clinic_shdform4_lasttakenexam')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);
            return 1;
        }catch(\Exception $erro)
        {
            return 0;
        }
    }
//----------------
    public function emptyform(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $userid = $request->get('userid');
        $formtype = $request->get('formtype');
        if($formtype == 'form1b')
        {
            DB::table('clinic_shdform1b_findings')
                ->where('userid',  $userid)
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }
    }

    public function index()
    {
        $refid = DB::table('usertype')
            ->where('id', Session::get('currentPortal'))
            ->first();
            
        if($refid->refid == '23')
        {
            $extends = 'clinic';
        }elseif($refid->refid == '24'){

            $extends = 'clinic_nurse';
        }elseif($refid->refid == '25'){

            $extends = 'clinic_doctor';
        }

        // $users  = SchoolClinic::users();
        
        return view('clinic.forms.index')
            ->with('extends', $extends);
            // ->with('users', $users);
    }
}
