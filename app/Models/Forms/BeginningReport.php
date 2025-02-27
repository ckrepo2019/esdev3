<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Model;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class BeginningReport extends Model
{
    
    public static function beginning_report($syid = null, $semid = null, $courseid = null, $sectionid = null, $levelid = null){

        $courses = DB::table('college_courses')
                    ->where('deleted',0);

        if($courseid != null){
            $courses = $courses->where('id',$courseid);
        }

        $courses = $courses->select('id','courseDesc')
                    ->get();

        $promotional_report = array();


       
        foreach($courses as $item){

            $enrolled_stud = DB::table('college_enrolledstud')
                                ->join('studinfo',function($join){
                                    $join->on('college_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                                })
                                ->where('college_enrolledstud.courseid',$item->id)
                                ->where('college_enrolledstud.deleted',0)
                                ->where('college_enrolledstud.syid',$syid)
                                ->where('college_enrolledstud.semid',$semid)
                                ->whereIn('college_enrolledstud.studstatus',[1,2,4]);

            //if($sectionid != null){
                //$enrolled_stud = $enrolled_stud->where('college_enrolledstud.sectionid',$sectionid);
            //}

            if($levelid != null){
                $enrolled_stud = $enrolled_stud->where('college_enrolledstud.yearLevel',$levelid);
            }
                        
            $enrolled_stud =    $enrolled_stud->select(
                                    'studid',
                                    'gender',
                                    'lastname',
                                    'firstname',
                                    'college_enrolledstud.sectionid',
                                    'college_enrolledstud.yearLevel'
                                )
                                ->orderBy('gender','desc')
                                ->orderBy('lastname')
                                ->get();

            $student_grades = array();

            foreach($enrolled_stud as $enrolled_stud_item){

                $data_array = array();

                $sched = DB::table('college_studsched')
                            ->join('college_classsched',function($join)use($syid,$semid){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted',0);
                                $join->where('syID',$syid);
                                $join->where('semesterID',$semid);
                            })
                            ->join('college_prospectus',function($join)use($syid,$semid){
                                $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                $join->where('college_prospectus.deleted',0);
                            })
                            ->where('schedstatus','REGULAR')
                            ->where('college_studsched.deleted',0)
                            ->where('college_studsched.studid',$enrolled_stud_item->studid)
                            ->select('subjCode','labunits','lecunits')
                            ->orderBy('subjCode')
                            ->get();


                foreach($sched as $item){

                    $units = 0;

                    if($item->labunits != null){
                        $units += $item->labunits;
                    }

                    if($item->lecunits != null){
                        $units += $item->lecunits;
                    }

                    array_push($data_array, (object)['data'=>$item->subjCode]);
                    array_push($data_array, (object)['data'=> $units]);
                }

                $lacking_sched = 20 - count($data_array);

                for($x = 0 ; $x <  $lacking_sched; $x++){

                    array_push( $data_array, (object)[
                        'data'=>""
                    ]);

                }

                array_push($promotional_report,
                (object)[
                    'student'=>$enrolled_stud_item->lastname.', '.$enrolled_stud_item->firstname,
                    'gender'=>$enrolled_stud_item->gender,
                    'levelid'=>$enrolled_stud_item->yearLevel,
                    'sectionid'=>$enrolled_stud_item->sectionid,
                    'subjects'=>$data_array
                ]);
                
            }
            
        }

        return $promotional_report;


    }

    public static function beginning_report_excel(
        $syid = null, 
        $semid = null, 
        $courseid = null, 
        $sectionid = null
    ){


        $spreadsheet    = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet          = $spreadsheet->getActiveSheet();

        $courses = DB::table('college_courses')
                    ->where('deleted',0);

        if($courseid != null){
            $courses = $courses->where('id',$courseid);
        }

        $courses = $courses->select('id','courseDesc','courseabrv')
                    ->get();

        

        $styleArray = array(
            'font'  => array(
                'size'  => 11,
                'name'  => 'Agency FB'
        ));

        $styleArray_course = array(
            'font'  => array(
                'bold' => true,
                'size'  => 14,
        ));

        $schoolInfo = DB::table('schoolinfo')->select('schoolname','address')->first();
        $semester = DB::table('semester')->where('isactive',1)->first()->semester;
        $sy = DB::table('sy')->where('isactive',1)->first()->sydesc;

        $x = 1;

        $sheet->mergeCells('A'.$x.':AB'.$x);
        $sheet->getStyle('A'.$x)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A'.$x)->applyFromArray($styleArray_course);
        $sheet->setCellValue('A'.$x,'BEGINNING REPORT');

        $x += 1;

        $sheet->mergeCells('A'.$x.':AB'.$x);
        $sheet->getStyle('A'.$x)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A'.$x)->applyFromArray($styleArray_course);
        $sheet->setCellValue('A'.$x,$semester.': S.Y.'.$sy);

        $x += 1;

        $sheet->mergeCells('A'.$x.':L'.$x);
        $sheet->setCellValue('A'.$x,'SCHOOL: '.$schoolInfo->schoolname);
        $sheet->getStyle('A'.$x)->applyFromArray($styleArray_course);

        $sheet->mergeCells('M'.$x.':Z'.$x);
        $sheet->setCellValue('M'.$x,'REGION: 10');
        $sheet->getStyle('M'.$x)->applyFromArray($styleArray_course);
        $sheet->getStyle('M'.$x)->getAlignment()->setHorizontal('right');
        $x += 1;

        $sheet->mergeCells('A'.$x.':L'.$x);
        $sheet->setCellValue('A'.$x,'ADDRESS: '.$schoolInfo->address);
        $sheet->getStyle('A'.$x)->applyFromArray($styleArray_course);
        $x += 1;
       

        foreach($courses as $item){
            $temp_level = 1;
            for($y = 17; $y <= 20; $y++){
             
                $grades = self::beginning_report($syid,$semid,$item->id,$sectionid,$y);

                if(count($grades) > 0){

                    $x += 1;

                    $male = 0;
                    $female = 0;
                    $counter = 1;

                    $sheet->mergeCells('A'.$x.':AB'.$x);
                    $sheet->getStyle('A'.$x)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('A'.$x)->applyFromArray($styleArray_course);
                    $sheet->setCellValue('A'.$x,$item->courseDesc.' ( '.$item->courseabrv.' '.$temp_level.' )');
                    $temp_level += 1;

                    $sheet->getStyle('A'.$x)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FABF8F');
                
                    $x += 1;

                    $sheet->mergeCells('A'.$x.':B'.$x);
                    $sheet->setCellValue('A'.$x,'Name of the Student/s');
                    $sheet->setCellValue('C'.$x,'Sex');
                    $sheet->getStyle('A'.$x)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('C'.$x)->getAlignment()->setHorizontal('center');
                    $subjectletterval = 68;

                    for($xy = $subjectletterval; $xy <= 90; $xy++){
                        $letter = chr($xy);
                        if($xy % 2 == 1){
                            $sheet->setCellValue($letter.$x,'Units');
                            $sheet->getStyle($letter.$x)->applyFromArray($styleArray);
                            $sheet->getStyle($letter.$x)->getAlignment()->setHorizontal('center');
                        }else{
                            $sheet->setCellValue($letter.$x,'Subject Code');
                            $sheet->getStyle($letter.$x)->getAlignment()->setHorizontal('center');
                            $sheet->getStyle($letter.$x)->applyFromArray($styleArray);
                            $sheet->getStyle($letter.$x)->getAlignment()->setWrapText(true);
                        }
                    }

                    $x += 1;

                    foreach($grades as $grade_item){

                        if( $male == 0 && strtolower($grade_item->gender) == 'male'){

                        }
                        if( $female == 0 && strtolower($grade_item->gender) == 'female'){
                            $sheet->mergeCells('A'.$x.':AB'.$x);
                            $sheet->setCellValue('A'.$x, '');
                            $x += 1;
                            $counter = 1;
                            $female = 1;
                        }

                        $sheet->getStyle('A'.$x.':AB'.$x)->getFont()->setSize('11');

                        $sheet->getColumnDimension('A')->setAutoSize(true);
                        $sheet->getColumnDimension('B')->setAutoSize(true);
                        $sheet->getColumnDimension('C')->setAutoSize(true);

                        $sheet->setCellValue('A'.$x,$counter.'.');
                        $sheet->setCellValue('B'.$x,$grade_item->student);
                        $sheet->setCellValue('C'.$x,$grade_item->gender);

                        $sheet->getStyle('C'.$x)->applyFromArray($styleArray);
                        $sheet->getStyle('C'.$x)->getAlignment()->setHorizontal('center');

                        $grade_count = 0;

                        $subjectletterval = 68;

                        for($xy = $subjectletterval; $xy <= 90; $xy++){

                            if(isset($grade_item->subjects[$grade_count])){
                                $letter = chr($xy);
                                $sheet->setCellValue($letter.$x,$grade_item->subjects[$grade_count]->data);
                                $sheet->getStyle($letter.$x)->applyFromArray($styleArray);
                                $sheet->getColumnDimensionByColumn($grade_count)->setWidth(7);
        
                                $grade_count += 1;
        
                                if($xy % 2 == 1){
                                    $styleArray_grade = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'name'  => 'Agency FB'
                                    ));
                                
                                    $sheet->getStyle($letter.$x)->applyFromArray($styleArray_grade);
                                    $sheet->getColumnDimensionByColumn($grade_count)->setWidth(7);
                                    $sheet->getStyle($letter.$x)->getAlignment()->setHorizontal('center');
                                }
                            }
                        
                        }

                        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                        $sheet->getPageSetup()->setFitToPage(true);

                        $sheet->getPageMargins()->setTop(0);
                        $sheet->getPageMargins()->setRight(0);
                        $sheet->getPageMargins()->setLeft(0);
                        $sheet->getPageMargins()->setBottom(0);

                        $sheet->getPageSetup()->setHorizontalCentered(true);
                        $sheet->getPageSetup()->setVerticalCentered(false);
                        $sheet->getPageSetup()->setFitToPage(true);
                        
                        $sheet->getPageSetup()->setFitToWidth(1);
                        $sheet->getPageSetup()->setFitToHeight(0);
                        $sheet->getSheetView()->setZoomScale(85);

                        $sheet->getPageSetup()->setPrintArea('A1:AB'.($grade_count+5));


                        $x += 1;
                        $counter += 1;

                    }

                }else{
                    $temp_level += 1;
                }
            }
             

        }


        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Beginning Report '.$sy.'.xlsx"');
        $writer->save("php://output");

    }



}
