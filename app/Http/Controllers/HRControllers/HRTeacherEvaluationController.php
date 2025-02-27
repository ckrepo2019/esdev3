<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Grading\GradeSchool;
use App\Models\Student\TeacherEvaluation;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class HRTeacherEvaluationController extends Controller
{
    
    public function admin_view_results(Request $request){

        $teachers = DB::table('teacheracadprog')
                        ->join('sy',function($join){
                            $join->on('teacheracadprog.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('teacher',function($join){
                            $join->on('teacher.id','teacheracadprog.teacherid');
                            $join->where('teacher.deleted','0');
                            $join->where('teacher.isactive','1');
                        })
                        ->where('teacheracadprog.deleted',0)
                        ->select('lastname','firstname','teacher.id','userid','usertypeid')
                        ->distinct()
                        ->get();

        foreach($teachers as $key=>$item){

            if($item->usertypeid != 1){

                $checkWithPriv = DB::table('faspriv')
                                    ->where('userid',$item->userid)
                                    ->where('usertype',1)
                                    ->count();

                if($checkWithPriv == 0){

                    unset($teachers[$key]);

                }

            }

        }

        // resources\views\superadmin\pages\gradingsystem\teacherEvaluation\teachers.blade.php

        return view('hr.summaries.teachersevaluation')->with('teachers',$teachers);

    

    }
    public static function teacher_schedule(Request $request){
        return $request->all();

        
        $subjects = GradeSchool::teacher_assign_subjects($request->get('teacherid'));

        $teacherEvaluation = TeacherEvaluation::getTeacherEvaluationSetup();

        if($teacherEvaluation[0]->status == 0){

            return $teacherEvaluation;

        }
        else{

            $teacherEvaluation =  $teacherEvaluation[0]->data;

        }

        $evaluationDetail = TeacherEvaluation::evaluate_teacher_evaluation_setup($teacherEvaluation[0]->id);

        if($evaluationDetail[0]->status == 0){

            return    $evaluationDetail;

        }
        else{

            $evaluationDetail =  $evaluationDetail[0]->data;

        }

        $ratingValue = TeacherEvaluation::evaluate_teacher_evaluation_rating_value($teacherEvaluation[0]->id);

        if($ratingValue[0]->status == 0){

            return    $ratingValue;

        }
        else{

            $ratingValue =  $ratingValue[0]->data;

        }

        foreach( $subjects as $item){

            $studentCount = DB::table('studinfo')
                              ->whereIn('studstatus',[1,2,4])
                              ->where('sectionid',$item->id)
                              ->where('studinfo.deleted',0)
                              ->count();

            $item->studcount = $studentCount;

        }

        if($request->GET('exporttype') == 'excel')
        {

        }else{

        }

        // return $evaluationDetail;

        // return view('superadmin.pages.gradingsystem.teacherEvaluation.assignment_table')
        //         ->with('evaluationDetail',$evaluationDetail)
        //         ->with('ratingvalue',$ratingValue)
        //         ->with('subjects',$subjects);


        
    }
    public function viewcomment(Request $request)
    {
        $quarter = Db::table('quarter_setup')
            ->where('isactive','1')
            ->where('deleted','0')
            ->first()->id;
        // return $request->all();
        $syid = Db::table('sy')
            ->where('isactive','1')
            ->first()->id;
        $semid = Db::table('semester')
            ->where('isactive','1')
            ->first()->id;

        $evaluations = DB::table('grading_system_student_header')
            ->select('q'.$quarter.'com')
            ->join('grading_system_student_evalcom','grading_system_student_header.id','=','grading_system_student_evalcom.evalcom_studheader')
            ->where('syid', $syid)
            ->where('semid',$semid)
            ->where('grading_system_student_header.teacherid',$request->get('teacherid'))
            ->get();

        return collect($evaluations)->pluck('q'.$quarter.'com');
    }
    public function check_evaluation(Request $request){
        // return $request->all();
        $quarter = Db::table('quarter_setup')
            ->where('isactive','1')
            ->where('deleted','0')
            ->first()->id;

        // return $quarter;
        
        // $responses = DB::table('grading_system_student_header')
        //             ->join('grading_system_student_evaluation',function($join){
        //                 $join->on('grading_system_student_header.id','=','grading_system_student_evaluation.studheader');
        //                 $join->where('grading_system_student_evaluation.deleted',0);
        //             })
        //             ->where('sectionid',$request->get('section'))
        //             ->where('subjid',$request->get('subj'))
        //             ->where('teacherid',$request->get('teacherid'))
        //             ->where('grading_system_student_header.deleted',0)
        //             ->get();

        $teacherEvaluation = TeacherEvaluation::getTeacherEvaluationSetup();

        if($teacherEvaluation[0]->status == 0){

            return $teacherEvaluation;

        }
        else{

            $teacherEvaluation =  $teacherEvaluation[0]->data;

        }

      
        $evaluationDetail = TeacherEvaluation::evaluate_teacher_evaluation_setup($teacherEvaluation[0]->id);

        if($evaluationDetail[0]->status == 0){

            return    $evaluationDetail;

        }
        else{

            $evaluationDetail =  $evaluationDetail[0]->data;

        }

        $ratingValue = TeacherEvaluation::evaluate_teacher_evaluation_rating_value($teacherEvaluation[0]->id);

        if($ratingValue[0]->status == 0){

            return    $ratingValue;

        }
        else{

            $ratingValue =  $ratingValue[0]->data;

        }
        // return $request->get('ddvalues');

        $overall = array();
        // return $evaluationDetail;
        foreach($request->get('ddvalues') as $value)
        {

            $data = array((object)[
                'respondents'=>null,
                'responses'=>null
            ]);
    
            $field = 'q'.$quarter.'val';
            $detail = array();
            $newRv = array();

            // foreach($evaluationDetail as $evdetail)
            // {}

            // $evaluationDetail = collect($evaluationDetail)->where('id',$value);
            // return $evaluationDetail;

            foreach($evaluationDetail as $item){
                
                if($item->id == $value)
                {
                    $responses = DB::table('grading_system_student_header')
                                    ->join('grading_system_student_evaluation',function($join){
                                        $join->on('grading_system_student_header.id','=','grading_system_student_evaluation.studheader');
                                        $join->where('grading_system_student_evaluation.deleted',0);
                                    })
                                    // ->where('sectionid',$request->get('section'))
                                    // ->where('subjid',$request->get('subj'))
                                    ->where('teacherid',$request->get('teacherid'))
                                    ->where('grading_system_student_header.deleted',0)
                                    ->where('grading_system_student_evaluation.gsid',$item->id)
                                    ->select($field)
                                    ->get();
        
                    foreach($ratingValue as $rtvalue){
        
                        array_push($newRv,(object)[
                            'rtid'=>$rtvalue->id,
                            'ratingCount'=>collect($responses)->where($field, $rtvalue->value)->count(),
                            'ratingvalue'=> $rtvalue->description
                        ]);
        
                    }
        
                    // return $newRv;
        
                    array_push($detail, (object)[
                        'detail'=>$item,
                        'responses'=>$newRv
                    ]);

                    $respondents = DB::table('grading_system_student_header')
                                    ->join('grading_system_student_evaluation',function($join){
                                        $join->on('grading_system_student_header.id','=','grading_system_student_evaluation.studheader');
                                        $join->where('grading_system_student_evaluation.deleted',0);
                                    })
                                    // ->where('sectionid',$request->get('section'))
                                    // ->where('subjid',$request->get('subj'))
                                    ->where($field, '!=' ,null)
                                    ->where('teacherid',$request->get('teacherid'))
                                    ->where('grading_system_student_header.deleted',0)
                                    ->where('grading_system_student_evaluation.gsid',$item->id)
                                    ->select('studid')
                                    // ->distinct('studid')
                                    ->count();
        
                    $data[0]->respondents  = $respondents;
                    $data[0]->responses  = $detail;
                }
                
    
            }

            // return $detail;
                            // return $data;
            array_push($overall, $data);
        }

        $overalldetails = collect($overall)->flatten();
        
        $teacherinfo = DB::table('teacher')
            ->select(
                'teacher.lastname',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.suffix',
                'teacher.tid'
            )
            ->where('id', $request->get('teacherid'))
            ->first();

            // $alphabet = range('A', 'Z');

            // return  $alphabet[16]; // returns D
            
        if($request->get('exporttype') == 'pdf')
        {

            return 'Not yet supported';

        }elseif($request->get('exporttype') == 'excel')
        {
            // return $overalldetails;
            $groupresult = array();
            foreach ($overalldetails as $element) {
                // return $element->responses;
                $groupresult[$element->responses[0]->detail->group][] = $element;
            }
            // return $result;
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            //WorkSheet 1
            $spreadsheet->getProperties()
                    ->setTitle('Work Sheet 1')
                    ->setSubject('Office 2007 XLSX Test Document')
                    ->setDescription('PhpOffice')
                    ->setKeywords('PhpOffice')
                    ->setCategory('PhpOffice');
            
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Work Sheet 1');
            foreach(range('A','Z') as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            $sheet
                ->setCellValue('A1', 'Name')
                ->setCellValue('B1', ':')
                ->setCellValue('C1', strtoupper($teacherinfo->lastname).', '.strtoupper($teacherinfo->firstname).' '.strtoupper($teacherinfo->middlename).' '.strtoupper($teacherinfo->suffix))
                ->setCellValue('A2','Date')
                ->setCellValue('B2',':')
                ->setCellValue('C2',date('F d, Y'))
                ->setCellValue('A3','Submitted answers')
                ->setCellValue('B3',':')
                ->setCellValue('C3',$overalldetails[0]->respondents.' ')
                ->setCellValue('A4','Questions')
                ->setCellValue('B4',':')
                ->setCellValue('C4',count($overalldetails).' ')
                ->setCellValue('C6','Question')
                ->setCellValue('D6','Responses')
                ->setCellValue('D1',$overalldetails[0]->respondents);

            
            $alphabet = range('D', 'Z');
            $starcellno = 7;

            $ratingvalues = array();
            // return $overalldetails;
            if(count($overalldetails)>0)
            {
                $count = 1;


                foreach($overalldetails[0]->responses[0]->responses as $rateval)
                {
                    array_push($ratingvalues,$rateval->ratingvalue);
                }
                $ratingvalues = collect($ratingvalues)->reverse()->values();

                // return collect($ratingvalues);
                foreach($overalldetails as $overalldetail)
                {
                    $sheet
                        ->setCellValue('B'.$starcellno, $count.'. ')
                        ->setCellValue('C'.$starcellno, $overalldetail->responses[0]->detail->description);

                    $responseshead          = $overalldetail->responses[0]->responses;
                
                    $responsesheadcount     = count($overalldetail->responses[0]->responses);

                    $letterrangehead = range('D',$alphabet[$responsesheadcount]);

                    
                    if($responsesheadcount>0)
                    {
                        
                        for($y = 0; $y<count($responseshead) ; $y++)
                        {
                                $sheet
                                    ->setCellValue($letterrangehead[$y].$starcellno, $ratingvalues[$y]);

                                $responsesheadcount-=1;
                        }
                        
                    }
                    
                    $count+=1;

                    $starcellno+=1;

                    $responses          = collect($overalldetail->responses[0]->responses)->reverse()->values()->all();
                    
                    $responsescount     = count($overalldetail->responses[0]->responses) - 1;

                    $letterrange = range('D',$alphabet[$responsescount]);
                    
                    if($responsescount>0)
                    {
                        for($x = 0; $x<=$responsescount ; $x++)
                        {

                                $sheet
                                    ->setCellValue($letterrange[$x].$starcellno, $responses[$x]->ratingCount);
                        }
                        $starcellno+=1;

                    
                        $responsesfootcounter     = count($overalldetail->responses[0]->responses);

                        $responsesfootcount     = count($overalldetail->responses[0]->responses) - 1;

                        $letterrangefoot = range('D',$alphabet[$responsesfootcount]);

                        for($z = 0; $z<=$responsesfootcount ; $z++)
                        {

                            if($responses[$z]->ratingCount == 0)
                            {
                                $sheet
                                    ->setCellValue($letterrangefoot[$z].$starcellno, $responses[$z]->ratingCount);
                            }else{

                                #example
                                #[ 6 (rate) * (numberofresponses selected 6 as a rate) / number of respondents] / number of rating selection
                                $sheet
                                    ->setCellValue($letterrangefoot[$z].$starcellno, (($responsesfootcounter*$responses[$z]->ratingCount)/$overalldetail->respondents)/count($overalldetail->responses[0]->responses));
                            }
                            $responsesfootcounter-=1;
                        }

                        $starcellno+=1;


                    }
                    $starcellno+=1;
                }
            }
            $syid = Db::table('sy')
                ->where('isactive','1')
                ->first()->id;
            $semid = Db::table('semester')
                ->where('isactive','1')
                ->first()->id;

            $evaluations = DB::table('grading_system_student_header')
                ->select('q'.$quarter.'com')
                ->join('grading_system_student_evalcom','grading_system_student_header.id','=','grading_system_student_evalcom.evalcom_studheader')
                ->where('syid', $syid)
                ->where('semid',$semid)
                ->where('grading_system_student_header.teacherid',$request->get('teacherid'))
                ->get();

            // return $evaluationssds;
            $starcellno+=4;
            $sheet->setCellValue('A'.$starcellno, 'COMMENTS');

            $commentcount = 1;
            if(count($evaluations)>0)
            {
                foreach($evaluations as $comment)
                {

                    $field = 'q'.$quarter.'com';
                    $sheet->setCellValue('B'.$starcellno, $commentcount.'. ');
                    $sheet->setCellValue('C'.$starcellno, $comment->$field);

                    $starcellno+=1;
                    $commentcount+=1;
                }
            }
            //WorkSheet 2
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex(1);

            $spreadsheet->getActiveSheet()->setTitle('Result');

            $sheet2 = $spreadsheet->getActiveSheet();

            foreach(range('A','Z') as $columnID) {
                $sheet2->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            // return "=(('Work Sheet 1'!C7*6)
                        // +('TemplateHere!'!D7*5)+
            
                        // ('TemplateHere!'!E7*4)+('TemplateHere!'!F7*3)+
                        // ('TemplateHere!'!G7*2)+('TemplateHere!'!H7*1))/'TemplateHere!'!D1')";
            $sheet2startcell = 8;
            // return $groupresult;

            $groupaverage = array();

            if(count($groupresult)>0)
            {
                $count = 1;

                // return $ratingvalues;
                foreach($groupresult as $groupkey => $group)
                {
                    // return $group;
                    $start = 0;
                    $end = 0;
                    foreach($group as $overalldetailkey => $overalldetail)
                    {
                        if($overalldetailkey == 0)
                        {
                            $start = $count;
                            $end = $count+count($group)-1;
                        }
                        
                        $letterval = 68;
                        $stringvalue = "";

                        foreach($ratingvalues as $ratingval)
                        {
                            $stringvalue.="('Work Sheet 1'!".chr($letterval).$sheet2startcell."*".$ratingval.")+";
                            $letterval+=1;
                        }

                        $stringvalue = substr($stringvalue,0,-1);
                        $sheet2->setCellValue('A'.$count, $count.'. '.$overalldetail->responses[0]->detail->description);
                        $sheet2->setCellValue('B'.$count, "=(".$stringvalue.")/'Work Sheet 1'!D1");

                        $count+=1;
                        $sheet2startcell+=4;
                    }
                    array_push($groupaverage,(object)array(
                        'desc'      => $groupkey,
                        'start'     => $start,
                        'end'     => $end
                    ));
                    // $group[0] = $group;
                }
            }
            $count+=4;

            $colorfill = array('ecec13','b6ec13','49ec13','3bbd0f');
            $colorindex=0;
            foreach($groupaverage as $average)
            {
                
                $sheet2->getStyle('B'.$average->start.':B'.$average->end)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB($colorfill[$colorindex]);
                   
                // return collect($average);
                $sheet2->setCellValue('A'.$count, $average->desc);
                $sheet2->setCellValue('B'.$count, "=AVERAGE(B".$average->start.":B".$average->end.")");

                $count+=1;
                if($colorindex == 3)
                {
                    $colorindex = 0;
                }else{
                    $colorindex+=1;
                }
                // $sheet2startcell+=1;
            }

            // return $groupaverage;

            // $spreadsheet->getActiveSheet()->setCellValue('B8','=IF(C4>500,"profit","loss")');
// 
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Teaching Evalutation - '.$teacherinfo->lastname.', '.$teacherinfo->firstname.'.xlsx"');
            $writer->save("php://output");
        }


    }
}
