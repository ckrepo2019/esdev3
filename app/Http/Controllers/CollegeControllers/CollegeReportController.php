<?php

namespace App\Http\Controllers\CollegeControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
// use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CollegeReportController extends Controller
{
    public function studentsubjects(Request $request){


        if($request->get('blade') == 'blade' && $request->has('blade')){

            return view('scholarshipcoor.pages.collegereports.studentsubjects.studentsubjectsblade');

        }
        else if($request->get('table') == 'table' && $request->has('table')){

            

            $sectionid = $request->get('sectionid');
            $courseid = $request->get('courseid');
            $gradelevelid = $request->get('gradelevelid');
            $sy = $request->get('sy');
            $sem = $request->get('sem');
            $gender = $request->get('gender');

            $college_sections = DB::table('college_sections')
                            ->where('deleted',0)
                            ->get();

            $requestskip = 0;
    
            $college_classsched = DB::table('college_classsched')
                                    ->join('college_prospectus',function($join){
                                        $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                        $join->where('college_prospectus.deleted',0);
                                    })
                                    ->join('college_studsched',function($join){
                                        $join->on('college_classsched.id','=','college_studsched.schedid');
                                        $join->where('college_studsched.deleted',0);
                                    })
                                    ->where('college_classsched.syID',$sy)
                                    ->where('college_classsched.semesterID',$sem)
                                    ->where('college_classsched.deleted',0);

                               
            $students = DB::table('studinfo')
                            ->join('college_enrolledstud',function($join) use($sy, $sem){
                                $join->on('studinfo.id','=','college_enrolledstud.studid');
                                $join->where('college_enrolledstud.deleted',0);
                                $join->where('college_enrolledstud.syid',$sy);
                                $join->where('college_enrolledstud.semid',$sem);
                            })
                            ->where('studinfo.deleted',0)
                            ->orderBy('lastname');

           

            if( $sectionid  != null && $sectionid  != 'null' ){

                $college_classsched =  $college_classsched->where('college_classsched.sectionid',$sectionid);
                $students =  $students->where('college_enrolledstud.sectionID',$sectionid);

            }


      

            if( $courseid  != null && $courseid  != 'null' ){

                $college_classsched =  $college_classsched->where('college_prospectus.courseID',$courseid);
                $students =  $students->where('studinfo.courseid',$courseid);

            }

            // return $gradelevelid;
            
            if( $gradelevelid  != null && $gradelevelid  != 'null' ){

                $college_classsched =  $college_classsched->where('college_prospectus.yearID',$gradelevelid);
                $students =  $students->where('studinfo.levelid',$gradelevelid);

            }

            // return $students->get();

            if( $sy  != null && $sy  != 'null' ){

                $college_classsched =  $college_classsched->where('college_classsched.syID',$sy);
                $students =  $students->where('college_enrolledstud.syid',$sy);

            }

            if( $sem  != null && $sem  != 'null' ){

                $college_classsched =  $college_classsched->where('college_classsched.semesterID',$sem);
                $students =  $students->where('college_enrolledstud.semid',$sem);

            }

            if( $gender  != null && $gender  != 'null' ){

                $students =  $students->where('studinfo.gender',$gender);

            }


            $studentCount = $students->count();
           

            if( ( $request->get('take') != null && $request->get('take') != 'null' ) && $request->has('take')){

                $students = $students->take(10);

            }        

            if( ( $request->get('skip') != null && $request->get('skip') != 'null' ) && $request->has('skip')){

                $students = $students->skip( ( $request->get('skip') - 1 ) * 10);

                $requestskip =  ( $request->get('skip') - 1 ) * 10;

            } 


           
         
                     
            $college_classsched =   $college_classsched->select(
                                        'subjDesc', 
                                        'subjCode',
                                        'labunits',
                                        'lecunits',
                                        'college_classsched.subjectID as schedid'
                                        )
                                    ->distinct()
                                    ->get();

                

            $students = $students->select('studinfo.id','lastname','firstname')->get();
    
            // return $students;

            foreach($students as $item){
    
                $studentSched = DB::table('college_studsched')
                                ->join('college_classsched',function($join) use($sem, $sy){
                                    $join->on('college_studsched.schedid','=','college_classsched.id');
                                    $join->where('college_classsched.deleted',0);
                                    // $join->where('college_classsched.sectionid',$sectionid);
                                    $join->where('college_classsched.syID',$sy);
                                    $join->where('college_classsched.semesterID',$sem);
                                })
                                ->join('college_prospectus',function($join){
                                    $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                    $join->where('college_prospectus.deleted',0);
                                })
                                ->select(
                                    'labunits',
                                    'lecunits',
                                    'subjCode',
                                    'college_classsched.subjectID as schedid'
                                    )
                           
                                ->where('studid',$item->id)
                                ->where('college_studsched.deleted',0)
                                ->get();

             
    
                $item->sched = $studentSched;
    
            }


    
            $data = array((object)[
                'count'=>$studentCount,
                'data'=>$students
            ]);

            
            if( $request->get('pdf') == 'pdf' && $request->has('pdf') ){

                $activeSy = DB::table('sy')->where('id',$sy)->first();
                $activeSem = DB::table('semester')->where('id',$sem)->first();

                $course = DB::table('college_courses')->where('id',$courseid)->where('deleted',0)->select('courseDesc','courseabrv')->first();

                $gradelevel = DB::table('gradelevel')->where('id',$gradelevelid)->where('deleted',0)->select('levelname')->first();

                $schoolInfo = DB::table('schoolinfo')->join('refregion','schoolinfo.region','=','refregion.regCode')->first();

                $signatories = DB::table('signatory')->where('form','college_enrollment_report')->get();

                $pdf = PDF::loadView('scholarshipcoor.pages.collegereports.studentsubjects.studentsubjectpdf',compact('college_classsched','data','activeSy','activeSem','schoolInfo','course','gender','signatories'))->setPaper('legal', 'landscape');
                $pdf->getDomPDF()->set_option("enable_php", true);

                return $pdf->stream();
              

            }
            elseif( $request->get('excel') == 'excel' && $request->has('excel') ){

                $activeSy = DB::table('sy')->where('id',$sy)->first();
                $activeSem = DB::table('semester')->where('id',$sem)->first();

                $course = DB::table('college_courses')->where('id',$courseid)->where('deleted',0)->select('courseDesc','courseabrv')->first();

                $gradelevel = DB::table('gradelevel')->where('id',$gradelevelid)->where('deleted',0)->select('levelname')->first();

                $schoolInfo = DB::table('schoolinfo')->join('refregion','schoolinfo.region','=','refregion.regCode')->first();

                $rowNumber = 2;

                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $columnLength = count($college_classsched);
               
                $letterNumberValue = ord('C') + ( $columnLength * 2);
                $lastColumnValue = chr($letterNumberValue);

                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(17);
                $sheet->getColumnDimension('C')->setWidth(17);

                $sheet->mergeCells('A'.$rowNumber.':'.$lastColumnValue.$rowNumber);
                $sheet->getStyle('A'.$rowNumber)->getAlignment()->setVertical('center');
                $sheet->getStyle('A'.$rowNumber)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A'.$rowNumber)->getFont()->setUnderline(true);
                $sheet->getStyle('A'.$rowNumber)->getFont()->setBold(true);

                $sheet->setCellValue('A'.$rowNumber,'ENROLLMENT REPORT');

                $rowNumber +=1;

                $sheet->mergeCells('A'.$rowNumber.':'.$lastColumnValue.$rowNumber);
                $sheet->getStyle('A'.$rowNumber)->getAlignment()->setVertical('center');
                $sheet->getStyle('A'.$rowNumber)->getAlignment()->setHorizontal('center');
               
                $sheet->getStyle('A'.$rowNumber)->getFont()->setSize(9);

                $sheet->setCellValue('A'.$rowNumber,$activeSem->semester.' S.Y. '.$activeSy->sydesc);

                $rowNumber +=2;

                $si_last_row_number_value = $letterNumberValue - 3;
                $si_last_row_column_value = chr($si_last_row_number_value);

                $sheet->mergeCells('A'.$rowNumber.':'.$si_last_row_column_value.$rowNumber);
                $sheet->getStyle('A'.$rowNumber)->getAlignment()->setVertical('center');
                $sheet->getStyle('A'.$rowNumber)->getFont()->setBold(true);
                $sheet->getStyle('A'.$rowNumber)->getFont()->setUnderline(true);
                $sheet->setCellValue('A'.$rowNumber,'SCHOOL: '. $schoolInfo->schoolname);
             

                $sheet->mergeCells('A'.($rowNumber+1).':'.$si_last_row_column_value.($rowNumber+1));
                $sheet->getStyle('A'.($rowNumber+1))->getAlignment()->setVertical('center');
                $sheet->getStyle('A'.($rowNumber+1))->getFont()->setBold(true);
                $sheet->getStyle('A'.($rowNumber+1))->getFont()->setUnderline(true);
                $sheet->setCellValue('A'.($rowNumber+1),'ADDRESS: '. $schoolInfo->address);


                $si_last_row_number_value_b = $si_last_row_number_value + 3;
                $si_last_row_column_value_b = chr($si_last_row_number_value_b);
                
                $si_last_row_number_value_b_start = $si_last_row_number_value + 1;
                $si_last_row_column_value_b_start = chr($si_last_row_number_value_b_start);

                $sheet->mergeCells($si_last_row_column_value_b_start.$rowNumber.':'.$si_last_row_column_value_b.$rowNumber);
                $sheet->getStyle($si_last_row_column_value_b_start.$rowNumber)->getAlignment()->setVertical('center');
                $sheet->getStyle($si_last_row_column_value_b_start.$rowNumber)->getFont()->setBold(true);
                $sheet->setCellValue($si_last_row_column_value_b_start.$rowNumber,'Region: '. $schoolInfo->region);

                $sheet->mergeCells($si_last_row_column_value_b_start.( $rowNumber + 1).':'.$si_last_row_column_value_b. ( $rowNumber + 1 ));
                $sheet->getStyle($si_last_row_column_value_b_start.( $rowNumber + 1 ) )->getAlignment()->setVertical('center');
                $sheet->getStyle($si_last_row_column_value_b_start.( $rowNumber + 1 ))->getFont()->setBold(true);
                $sheet->setCellValue($si_last_row_column_value_b_start. ( $rowNumber + 1 ),'PAGES: ');

                $rowNumber +=3;

                $sheet->mergeCells('A'.$rowNumber.':'.$lastColumnValue.$rowNumber);
                $sheet->getStyle('A'.$rowNumber)->getAlignment()->setVertical('center');
                $sheet->getStyle('A'.$rowNumber)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A'.$rowNumber)->getFont()->setBold(true);

                $letter = 'A';

                for($x = 0 ; $x < ( $columnLength * 2 )+3; $x ++){

                    self::setBorder($letter,$rowNumber,$sheet);

                    $letterAscii = ord($letter);
                    $letterAscii++;
                    $letter = chr($letterAscii);

                    

                }


               
               
                $sheet->getRowDimension($rowNumber)->setRowHeight(30);

                $sheet->setCellValue('A'.$rowNumber,$course->courseDesc.' ( '.$course->courseabrv.' )');

                $rowNumber +=1;


                $sheet->getStyle('B'.$rowNumber)->getAlignment()->setVertical('center');
                $sheet->getStyle('B'.$rowNumber)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A')->getAlignment()->setVertical('center');
                $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
             

                $sheet->setCellValue('A'.$rowNumber,'No.');
                $sheet->mergeCells('B'.$rowNumber.':'.'C'.$rowNumber);
                self::setBorder('A',$rowNumber,$sheet);
                self::setBorder('B',$rowNumber,$sheet);
                self::setBorder('C',$rowNumber,$sheet);
                $sheet->setCellValue('B'.$rowNumber,'NAME OF STUDENTS');

                $letter = 'D';

                foreach($college_classsched as $item){

                    $sheet->getStyle($letter.$rowNumber)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle($letter.$rowNumber)->getAlignment()->setVertical('center');
                    self::setBorder($letter,$rowNumber,$sheet);
                    
                    $sheet->setCellValue($letter.$rowNumber,'SUBJECT CODE');
                    $sheet->getStyle($letter.$rowNumber)->getAlignment()->setWrapText(true);
    
                    $letterAscii = ord($letter);
                    $letterAscii++;
                    $letter = chr($letterAscii);
                    
                    $sheet->getStyle($letter.$rowNumber)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle($letter.$rowNumber)->getAlignment()->setVertical('center');
                    self::setBorder($letter,$rowNumber,$sheet);
                    $sheet->getColumnDimension($letter)->setWidth(6);
                   
                    $sheet->setCellValue($letter.$rowNumber,'UNITS');
    
                    $letterAscii = ord($letter);
                    $letterAscii++;
                    $letter = chr($letterAscii);

                    
                }


                $rowNumber +=1;

                $letter = 'A';

                $sheet->mergeCells('B'.$rowNumber.':'.'C'.$rowNumber);

                for($x = 0 ; $x < ( $columnLength * 2 )+3; $x ++){

                    self::setBorder($letter,$rowNumber,$sheet);

                    $letterAscii = ord($letter);
                    $letterAscii++;
                    $letter = chr($letterAscii);

                }

                if( $gender  != null && $gender  != 'null' ){

                    $sheet->getStyle('B'.$rowNumber)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('B'.$rowNumber)->getAlignment()->setVertical('center');
                    $sheet->setCellValue('B'.$rowNumber,$gender);
                
                }


                $rowNumber +=1;
                foreach ($data[0]->data as $key=>$item){

                    $letter = 'D';

                    $sheet->setCellValue('A'.$rowNumber,$key + 1);
                    self::setBorder('A',$rowNumber,$sheet);

                    $sheet->getStyle('B'.$rowNumber)->getAlignment()->setWrapText(true);
                    $sheet->getStyle('C'.$rowNumber)->getAlignment()->setWrapText(true);

                    $sheet->getStyle('B'.$rowNumber)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('C'.$rowNumber)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $sheet->getHeaderFooter()
                    ->setOddHeader('&C&HPlease treat this document as confidential!');
          

                    $sheet->setCellValue('B'.$rowNumber,$item->lastname);
                    self::setBorder('B',$rowNumber,$sheet);
                    $sheet->setCellValue('C'.$rowNumber,$item->firstname);
                    self::setBorder('C',$rowNumber,$sheet);

                        foreach ($college_classsched as $scheditem){

                            

                            $matchedSched = collect($item->sched)
                                                ->where('schedid',$scheditem->schedid)
                                                ->first();


                            if(isset($matchedSched->subjCode)){

                                $sheet->getStyle($letter.$rowNumber)->getAlignment()->setHorizontal('center');
                                $sheet->getStyle($letter.$rowNumber)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                                $sheet->setCellValue($letter.$rowNumber,$matchedSched->subjCode);
                                $sheet->getColumnDimension($letter)->setWidth(11);
                                self::setBorder($letter,$rowNumber,$sheet);

                                $letterAscii = ord($letter);
                                $letterAscii++;
                                $letter = chr($letterAscii);
                                
                                $sheet->getStyle($letter.$rowNumber)->getAlignment()->setHorizontal('center');
                                $sheet->setCellValue($letter.$rowNumber,$matchedSched->lecunits + $matchedSched->labunits);
                                $sheet->getStyle($letter.$rowNumber)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                                self::setBorder($letter,$rowNumber,$sheet);

                                $letterAscii = ord($letter);
                                $letterAscii++;
                                $letter = chr($letterAscii);
    
                               
                                

                            }
                            else{

                                $sheet->getStyle($letter.$rowNumber)->getAlignment()->setHorizontal('center');
                                self::setBorder($letter,$rowNumber,$sheet);
                                $letterAscii = ord($letter);
                                $letterAscii++;
                                $letter = chr($letterAscii);
                               
                                $sheet->getStyle($letter.$rowNumber)->getAlignment()->setHorizontal('center');
                                self::setBorder($letter,$rowNumber,$sheet);
                                $letterAscii = ord($letter);
                                $letterAscii++;
                                $letter = chr($letterAscii);
                               

                            }
                            

                           

                        }
                      
                    $rowNumber += 1;

                }

                // $rowNumber +=2;

                $signatories = DB::table('signatory')->where('form','college_enrollment_report')->get();

                $signatoryLetter = 'A';
                $signatoryColumnCount = 1;

                $spaceGag = 4;

                foreach($signatories as $item){

                    $preSigColumnNumber = ord($signatoryLetter);
                    $postSigColumnNumber = $preSigColumnNumber + 4;

                    $preSigColumnLetter = chr($preSigColumnNumber);
                    $postSigColumnLetter = chr($postSigColumnNumber);

                    $signatoryLetter =  chr($postSigColumnNumber + 1);

                    $sheet->getStyle($preSigColumnLetter.( $rowNumber + 1) )->getAlignment()->setHorizontal('left');
                    $sheet->getStyle($preSigColumnLetter.($rowNumber+$spaceGag))->getAlignment()->setHorizontal('left');
                    $sheet->getStyle($preSigColumnLetter.($rowNumber+$spaceGag+1))->getAlignment()->setHorizontal('left');

                    $sheet->mergeCells($preSigColumnLetter.($rowNumber+1).':'.$postSigColumnLetter.($rowNumber+1));
                    $sheet->mergeCells($preSigColumnLetter.($rowNumber+$spaceGag).':'.$postSigColumnLetter.($rowNumber+$spaceGag));
                    $sheet->mergeCells($preSigColumnLetter.($rowNumber+$spaceGag+1).':'.$postSigColumnLetter.($rowNumber+$spaceGag+1));

                    $sheet->getStyle($preSigColumnLetter.($rowNumber+1))->getFont()->setBold(true);
                    $sheet->getStyle($preSigColumnLetter.($rowNumber+$spaceGag))->getFont()->setBold(true);

                    $sheet->setCellValue($preSigColumnLetter.($rowNumber+1),$item->description);

                    $sheet->setCellValue($preSigColumnLetter.($rowNumber+$spaceGag),$item->name);

                    $sheet->setCellValue($preSigColumnLetter.($rowNumber+$spaceGag+1),$item->title);

                }

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="testing.xlsx"');
                $writer->save("php://output");

                return "done";

            }
            else{

                return view('scholarshipcoor.pages.collegereports.studentsubjects.studentsubjecttable')
                            ->with('skip',$requestskip)
                            ->with('college_classsched',$college_classsched)
                            ->with('data',$data);

            }

           

              


        }

        

        // return $students;
        // resources\views\collegeportal\pages\reports\mStudentSubject.blade.php     

        // return view('collegeportal.pages.reports.mStudentSubject')
        //             ->with('college_classsched',$college_classsched)
        //             ->with('students',$students);


        // return $students;


        // return $college_classsched;

    }

    public function signatory(Request $request){


        if($request->get('update') == 'update' && $request->has('update')){

            try{

                return  $request->get('field');

                DB::table('signatory')
                        ->where('id',$request->get('signatoryid'))
                        ->update([
                            $request->get('field')=>$request->get('value')
                        ]);

                return 1;

            }catch(\Exception $e){

                DB::table('zerrorlogs')
                            ->insert([
                                'error'=>$e,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);

                return 0;
             
            }

           


        }

    }

    public static function setBorder($letter,$rowNumber,$sheet){

        $sheet->getStyle($letter.$rowNumber)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        $sheet->getStyle($letter.$rowNumber)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        $sheet->getStyle($letter.$rowNumber)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        $sheet->getStyle($letter.$rowNumber)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

    }



}


