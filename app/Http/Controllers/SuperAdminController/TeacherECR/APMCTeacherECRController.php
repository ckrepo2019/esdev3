<?php

namespace App\Http\Controllers\SuperAdminController\TeacherECR;

use Illuminate\Http\Request;
use DB;
use Hash;
use Session;

class APMCTeacherECRController extends \App\Http\Controllers\Controller
{
      
      public static function download_ecr(Request $request){

            // http://sppv2.ck/ecr/download?syid=2&levelid=10&subjid=2&sectionid=1

            $schoolinfo = DB::table('schoolinfo')->first();
            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');

            $levelinfo = DB::table('gradelevel')
                              ->where('id',$levelid)
                              ->first();
                                  
            $section = DB::table('sections')
                              ->where('id',$sectionid)
                              ->first();

            $sy = DB::table('sy')
                        ->where('id',$syid)
                        ->first();

            $strandcode = '';

            $subjinfo = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null,$subjid,$levelid,null,$syid);

            $subjinfo = collect($subjinfo)->where('ww','!=',null)->values();
               
            if(count($subjinfo) == 0){
                return "No grade setup";
            }
            
            if($subjinfo[0]->subjdesc == 'HOMEROOM'){
                  return self::download_homeroom($request);
            }
            // return $subjinfo;

            if($levelinfo->acadprogid != 5){
                  $semid = 1;
            }

            $setup = '1'; //default with single quarterly assesment
            if($schoolinfo->snr == 'spct'){
                  $setup = '2'; //spct setup
            }else if($schoolinfo->snr == 'mac'){
                  $setup = '3';  //default with single quarterly assesment
            }else if($schoolinfo->snr == 'shjms'){
                  $setup = '4';  //default with single quarterly assesment
            }else{
                  $setup = '3';
            }

            
            if($levelinfo->acadprogid != 5){

                  $student = DB::table('enrolledstud')
                              ->where('enrolledstud.deleted',0)
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.sectionid',$sectionid)
                              ->whereIn('enrolledstud.studstatus',[1,2,4])
                              ->join('studinfo',function($join){
                                    $join->on('studinfo.id','=','enrolledstud.studid');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->orderBy('gender','desc')
                              ->orderBy('lastname')
                              ->orderBy('studentname','asc')
                              ->select(
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'enrolledstud.levelid',
                                    'enrolledstud.sectionid',
                                    'dob',
                                    'gender',
                                    'lrn',
                                    'sid',
                                    'studinfo.id',
                                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                              )
                              ->get();

                  $check_subject = DB::table('subjects')
                              ->where('deleted',0)
                              ->where('id',$subjid)
                              ->first();

                  if( isset($check_subject->isSP)){
                        if($check_subject->isSP == 1){
                              $student = DB::table('enrolledstud')
                                                ->join('studinfo',function($join){
                                                      $join->on('studinfo.id','=','enrolledstud.studid');
                                                      $join->where('studinfo.deleted',0);
                                                })
                                                ->join('subjects_studspec',function($join) use($subjid,$syid){
                                                      $join->on('enrolledstud.studid','=','subjects_studspec.studid');
                                                      $join->where('subjects_studspec.deleted',0);
                                                      $join->where('subjects_studspec.syid',$syid);
                                                      $join->where('subjects_studspec.subjid',$subjid);
                                                })
                                                ->where('enrolledstud.deleted',0)
                                                ->where('enrolledstud.syid',$syid)
                                                ->where('enrolledstud.sectionid',$sectionid)
                                                ->whereIn('enrolledstud.studstatus',[1,2,4])
                                                ->orderBy('gender','desc')
                                                ->orderBy('studentname','asc')
                                                ->select(
                                                      'lastname',
                                                      'firstname',
                                                      'middlename',
                                                      'suffix',
                                                      'enrolledstud.levelid',
                                                      'enrolledstud.sectionid',
                                                      'dob',
                                                      'gender',
                                                      'lrn',
                                                      'sid',
                                                      'studinfo.id',
                                                      DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                                )
                                                ->get();
                        }
                  }

                  $temp_students = array();

                  foreach($student as $item){
                        array_push($temp_students,$item);
                  }

                  $student = DB::table('student_specsubj')
                                    ->join('studinfo',function($join){
                                          $join->on('student_specsubj.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('enrolledstud',function($join) use($syid){
                                          $join->on('student_specsubj.studid','=','enrolledstud.studid');
                                          $join->whereIn('enrolledstud.studstatus',[1,2,4]);
                                          $join->where('enrolledstud.deleted',0);
                                          $join->where('enrolledstud.syid',$syid);
                                    })
                                    ->where('student_specsubj.status','ADDITIONAL')
                                    ->where('student_specsubj.syid',$syid)
                                    ->where('student_specsubj.sectionid',$sectionid)
                                    ->where('student_specsubj.subjid',$subjid)
                                    ->where('student_specsubj.deleted',0)
                                    ->select(
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'enrolledstud.levelid',
                                          'enrolledstud.sectionid',
                                          'dob',
                                          'gender',
                                          'lrn',
                                          'sid',
                                          'studinfo.id',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                    )
                                    ->get();

                  foreach($student as $item){
                        array_push($temp_students,$item);
                  }

                  $student = collect($temp_students)->sortBy(function($item) {
                        $gsort = $item->gender == 'MALE' ? 0 : 1;
                        return $gsort.'-'.$item->studentname;
                  })->values();



            }else{


                  $strand = array();

                  $strandid = $request->get('strandid');

                  if($strandid != null){

                        $strandcode = Db::table('sh_strand')
                                          ->where('id',$strandid)
                                          ->select('strandcode')
                                          ->first()
                                          ->strandcode;

                        array_push($strand, $strandid);
                  }
                  else{
                        $subjstrand = DB::table('subject_plot')
                                          ->where('syid',$syid)
                                          ->where('levelid',$levelid)
                                          ->where('subjid',$subjid)
                                          ->where('deleted',0)
                                          ->get();

                        foreach($subjstrand as $stranditem){
                              array_push($strand, $stranditem->strandid);
                        }
                  }

                  $temp_students = array();
            
                  $student = DB::table('sh_enrolledstud')
                        ->where('sh_enrolledstud.deleted',0)
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.semid',$semid)
                        ->where('sh_enrolledstud.sectionid',$sectionid)
                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                        ->whereIn('sh_enrolledstud.strandid',$strand)
                        ->join('studinfo',function($join){
                              $join->on('studinfo.id','=','sh_enrolledstud.studid');
                              $join->where('studinfo.deleted',0);
                        })
                        ->orderBy('gender','desc')
                        ->orderBy('studentname','asc')
                        ->select(
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'sh_enrolledstud.levelid',
                              'sh_enrolledstud.sectionid',
                              'dob',
                              'gender',
                              'lrn',
                              'sid',
                              'studinfo.id',
                              DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                        )
                        ->distinct('studid')
                        ->get();

                  foreach($student as $item){
                        array_push($temp_students,$item);
                  }
          
                  $student = DB::table('student_specsubj')
                                      ->join('studinfo',function($join){
                                          $join->on('student_specsubj.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                      })
                                      ->join('sh_enrolledstud',function($join) use($syid,$semid){
                                          $join->on('student_specsubj.studid','=','sh_enrolledstud.studid');
                                          $join->whereIn('sh_enrolledstud.studstatus',[1,2,4]);
                                          $join->where('sh_enrolledstud.deleted',0);
                                          $join->where('sh_enrolledstud.syid',$syid);
                                          $join->where('sh_enrolledstud.semid',$semid);
                                      })
                                      ->join('sh_strand',function($join){
                                          $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                          $join->where('sh_strand.deleted',0);
                                      })
                                      ->where('student_specsubj.status','ADDITIONAL')
                                      ->where('student_specsubj.syid',$syid)
                                      ->where('student_specsubj.semid',$semid)
                                      ->where('student_specsubj.sectionid',$sectionid)
                                      ->where('student_specsubj.subjid',$subjid)
                                      ->where('student_specsubj.deleted',0)
                                      ->select(
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'sh_enrolledstud.levelid',
                                          'sh_enrolledstud.sectionid',
                                          'dob',
                                          'gender',
                                          'lrn',
                                          'sid',
                                          'studinfo.id',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                      )
                                      ->get();
          
                  foreach($student as $item){
                        array_push($temp_students,$item);
                  }

                  $student = collect($temp_students)->sortBy(function($item) {
                        $gsort = $item->gender == 'MALE' ? 0 : 1;
                        return $gsort.'-'.$item->studentname;
                  })->values();

            }
            
            foreach($student as $stud_item){
                  $temp_middle = '';
                  if(isset($stud_item->middlename)){
                        $middlename = explode(" ",$stud_item->middlename);
                        if($stud_item->middlename != '' && $stud_item->middlename != null){
                              $temp_middle = $stud_item->middlename[0].'.';
                        }
                  }
                  $stud_item->middlename = $temp_middle;
                  $stud_item->student = $stud_item->lastname.', '.$stud_item->firstname.' '.$stud_item->suffix.' '.$temp_middle;
            }

            // $students = collect($student)->sortBy('studentname');

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

            if($setup == 2){
                  if($levelinfo->acadprogid == 5){
                        $spreadsheet = $reader->load("ECR/SPCT/ECR-SHS.xlsx");
                  }else{
                        $spreadsheet = $reader->load("ECR/SPCT/ECR.xlsx");
                  }
            }else if($setup == 3){

                  $schoolinfo = DB::table('schoolinfo')->first();
                
                  if(strtoupper($schoolinfo->abbreviation) == 'HCHS'){
                        if($levelinfo->acadprogid != 5){
                              $spreadsheet = $reader->load("ECR/ECR_HCHS/ECR_T1.xlsx");
                        }else{

                              $strand_detail = DB::table('sh_strand')
                                          ->where('id',$strand[0])
                                          ->first();

                              $subj_info = DB::table('sh_subjects')
                                          ->where('id',$subjid)
                                          ->where('deleted',0)
                                          ->first();

                              if($strand_detail->trackid == 1){
                                    $subj_info = DB::table('sh_subjects')
                                                      ->where('id',$subjectid)
                                                      ->where('deleted',0)
                                                      ->first();
                                    if($subj_info->type == 1){
                                          $spreadsheet = $reader->load("ECR/ECR_HCHS/ECR-SHS_T2.xlsx");
                                    }else{
                                          $spreadsheet = $reader->load("ECR/ECR_HCHS/ECR-SHS_T3.xlsx");
                                    }
                              }else{
                                    if($subj_info->type != 1){
                                          $spreadsheet = $reader->load("ECR/ECR_HCHS/ECR-SHS_T1.xlsx");
                                    }else{
                                          $spreadsheet = $reader->load("ECR/ECR_HCHS/ECR-SHS_T2.xlsx");
                                    }
                              }
                        }
                  }else{
                        if($levelinfo->acadprogid == 5){
                              $spreadsheet = $reader->load("ECR/ECR_MUL_QA_APMC/ECR-SHS.xlsx");
                        }else{
                              $spreadsheet = $reader->load("ECR/ECR_MUL_QA_APMC/ECR.xlsx");
                        }
                  }
            }
            else if($setup == 1){
                  if($levelinfo->acadprogid == 5){
                        $spreadsheet = $reader->load("ECR/ECR_MUL_QA_APMC/ECR-SHS.xlsx");
                  }else{
                        $spreadsheet = $reader->load("ECR/ECR_MUL_QA_APMC/ECR.xlsx");
                  }
            }
            else if($setup == 4){
                  if($levelinfo->acadprogid == 5){
                        $spreadsheet = $reader->load("ECR/ECR_SHJMS/ECR-SHS.xlsx");
                  }else{
                        $spreadsheet = $reader->load("ECR/ECR_SHJMS/V2/ECR-SHJMS.xlsx");
                  }
            }
        
            $gender = [
                  'borders' => [
                      'outline' => [
                          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                      ],
                  ],
              ];

            $no_border = [
                  'borders' => [
                      'allBorders' => [
                          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                          'color' => array('argb' => 'd9d9d9'),
                      ],
                  ],
              ];

              $thick_top = [
                  'borders' => [
                      'top' => [
                          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                          'color' => array('argb' => '000000'),
                      ],
                  ],
              ];

              $signatories = [
                  'font' => [
                      'bold' => true,
                  ],
                  'borders' => [
                      'bottom' => [
                          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                          'color' => array('argb' => '000000'),
                      ],
                  ],
  
              ];
  

            $headers_array = array();

            for($x = 0 ; $x < 4; $x++){

                  $applicable = true;
                  $quarter = $x + 1;

                  if($levelinfo->acadprogid == 5){
                        if($x == 2 || $x == 3){
                              $applicable = false;  
                        }
                  }

                  if($applicable){

                        $sheet = $spreadsheet->setActiveSheetIndex($x);

                        if($levelinfo->acadprogid == 5 && $semid == 2){
                              if($x == 0){
                                    $quarter = 3;
                                    $sheet->setCellValue('A7','THIRD QUARTER');
                                    $sheet->setTitle('3rd Quarter');
                              }else if($x == 1){
                                    $quarter = 4;
                                    $sheet->setCellValue('A7','FOURTH QUARTER');
                                    $sheet->setTitle('4th Quarter');
                              }
                        }
                        
                        $subjid = $request->get('subjid');
                        $syid = $request->get('syid');
                        $levelid = $request->get('levelid');
                        $sectionid = $request->get('sectionid');

                        

                        $headerid = null;

                        if($levelinfo->acadprogid != 5){
                              $semid = 1;
                        }

                        $check_header = DB::table('grades')
                                          ->where('syid',$syid)
                                          ->where('semid',$semid)
                                          ->where('levelid',$levelid)
                                          ->where('sectionid',$sectionid)
                                          ->where('subjid',$subjid)
                                          ->where('quarter',$quarter)
                                          ->where('deleted',0)
                                          ->get();

                        if(count($check_header) == 0){
                              
                              $headerid =  DB::table('grades')
                                                ->insertGetId([
                                                      'syid' => $syid,
                                                      'semid' => $semid,
                                                      'levelid' => $levelid,
                                                      'sectionid' => $sectionid,
                                                      'subjid' => $subjid,
                                                      'quarter' => $quarter,
                                                      'deleted' => 0,
                                                      'createdby'=>auth()->user()->id,
                                                      'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                                ]);
                                          
                        }else{

                              $headerid = $check_header[0]->id;

                              if($setup == 4){
                                    $count = 11;
                              }else{
                                    $count = 11;
                              }

                              if($setup == 2){
                                    self::spct_ecr_header_download($sheet, $check_header[0], $count);
                              }
                              elseif($setup == 4){
                                    $sheet->setCellValue('C'.$count,$check_header[0]->wwhr1 == 0 ? "": $check_header[0]->wwhr1);
                                    $sheet->setCellValue('D'.$count,$check_header[0]->wwhr2 == 0 ? "": $check_header[0]->wwhr2);
                                    $sheet->setCellValue('E'.$count,$check_header[0]->wwhr3 == 0 ? "": $check_header[0]->wwhr3);
                                    $sheet->setCellValue('F'.$count,$check_header[0]->wwhr4 == 0 ? "": $check_header[0]->wwhr4);
                                    $sheet->setCellValue('G'.$count,$check_header[0]->wwhr5 == 0 ? "": $check_header[0]->wwhr5);
                                    $sheet->setCellValue('H'.$count,$check_header[0]->wwhr6 == 0 ? "": $check_header[0]->wwhr6);
                                    $sheet->setCellValue('I'.$count,$check_header[0]->wwhr7 == 0 ? "": $check_header[0]->wwhr7);
                                    $sheet->setCellValue('J'.$count,$check_header[0]->wwhr8 == 0 ? "": $check_header[0]->wwhr8);

                                    $sheet->setCellValue('M'.$count,$check_header[0]->pthr1 == 0 ? "": $check_header[0]->pthr1);
                                    $sheet->setCellValue('N'.$count,$check_header[0]->pthr2 == 0 ? "": $check_header[0]->pthr2);
                                    $sheet->setCellValue('O'.$count,$check_header[0]->pthr3 == 0 ? "": $check_header[0]->pthr3);
                                    $sheet->setCellValue('P'.$count,$check_header[0]->pthr3 == 0 ? "": $check_header[0]->pthr3);

                                    $sheet->setCellValue('S'.$count,$check_header[0]->qahr1 == 0 ? "": $check_header[0]->qahr1);
                                    $sheet->setCellValue('T'.$count,$check_header[0]->qahr2 == 0 ? "": $check_header[0]->qahr2);
                              }else{
                                    $sheet->setCellValue('F'.$count,$check_header[0]->wwhr1 == 0 ? "": $check_header[0]->wwhr1);
                                    $sheet->setCellValue('G'.$count,$check_header[0]->wwhr2 == 0 ? "": $check_header[0]->wwhr2);
                                    $sheet->setCellValue('H'.$count,$check_header[0]->wwhr3 == 0 ? "": $check_header[0]->wwhr3);
                                    $sheet->setCellValue('I'.$count,$check_header[0]->wwhr4 == 0 ? "": $check_header[0]->wwhr4);
                                    $sheet->setCellValue('J'.$count,$check_header[0]->wwhr5 == 0 ? "": $check_header[0]->wwhr5);
                                    $sheet->setCellValue('K'.$count,$check_header[0]->wwhr6 == 0 ? "": $check_header[0]->wwhr6);
                                    $sheet->setCellValue('L'.$count,$check_header[0]->wwhr7 == 0 ? "": $check_header[0]->wwhr7);
                                    $sheet->setCellValue('M'.$count,$check_header[0]->wwhr8 == 0 ? "": $check_header[0]->wwhr8);
                                    $sheet->setCellValue('N'.$count,$check_header[0]->wwhr9 == 0 ? "": $check_header[0]->wwhr9);
                                    $sheet->setCellValue('O'.$count,$check_header[0]->wwhr0 == 0 ? "": $check_header[0]->wwhr0);

                                    $sheet->setCellValue('S'.$count,$check_header[0]->pthr1 == 0 ? "": $check_header[0]->pthr1);
                                    $sheet->setCellValue('T'.$count,$check_header[0]->pthr2 == 0 ? "": $check_header[0]->pthr2);
                                    $sheet->setCellValue('U'.$count,$check_header[0]->pthr3 == 0 ? "": $check_header[0]->pthr3);
                                    $sheet->setCellValue('V'.$count,$check_header[0]->pthr4 == 0 ? "": $check_header[0]->pthr4);
                                    $sheet->setCellValue('W'.$count,$check_header[0]->pthr5 == 0 ? "": $check_header[0]->pthr5);
                                    $sheet->setCellValue('X'.$count,$check_header[0]->pthr6 == 0 ? "": $check_header[0]->pthr6);
                                    $sheet->setCellValue('Y'.$count,$check_header[0]->pthr7 == 0 ? "": $check_header[0]->pthr7);
                                    $sheet->setCellValue('Z'.$count,$check_header[0]->pthr8 == 0 ? "": $check_header[0]->pthr8);
                                    $sheet->setCellValue('AA'.$count,$check_header[0]->pthr9 == 0 ? "": $check_header[0]->pthr9);
                                    $sheet->setCellValue('AB'.$count,$check_header[0]->pthr0 == 0 ? "": $check_header[0]->pthr0);

                              
                                    if($subjinfo[0]->qa != null && $subjinfo[0]->qa != 0){
                                          if($setup == 3){
                                                $sheet->setCellValue('AF'.$count,$check_header[0]->qahr1 == 0 ? "": $check_header[0]->qahr1);
                                                $sheet->setCellValue('AG'.$count,$check_header[0]->qahr2 == 0 ? "": $check_header[0]->qahr2);
                                                $sheet->setCellValue('AH'.$count,$check_header[0]->qahr3 == 0 ? "": $check_header[0]->qahr3);
                                          }else if($setup == 1){
                                                $sheet->setCellValue('AF'.$count,$check_header[0]->qahr1 == 0 ? "": $check_header[0]->qahr1);
                                          }
                                    }

                     

                              }

                             

                        }

                      
                       
                        if($setup == 4){
                              $male = 1;
                              $female = 0;
                              $count = 12;
                        }else{
                              $male = 0;
                              $female = 0;
                              $count = 12;
                        }
                       
                        
                        array_push($headers_array,$headerid);

                        $sheet->setCellValue('AZ9', $headerid);
                       
                        $temp_teacher = DB::table('teacher')
                                                ->where('tid',auth()->user()->email)
                                                ->get();

                        if(count($temp_teacher) == 0){

                              $temp_teacher = DB::table('sectiondetail')
                                                ->where('sectiondetail.syid',$syid)
                                                ->where('sectiondetail.sectionid',$sectionid)
                                                ->where('sectiondetail.deleted',0)
                                                ->join('teacher',function($join){
                                                      $join->on('sectiondetail.teacherid','=','teacher.id');
                                                      $join->where('sectiondetail.deleted',0);
                                                })
                                                ->select('teacher.*')
                                                ->get();

                        }

                        foreach($temp_teacher as $teacher_item){
                              $middlename = explode(" ",$teacher_item->middlename);
                              $temp_middle = '';
							  $temp_title = '';
							  $temp_suffix = '';
                              if($middlename != null){
                                    foreach ($middlename as $middlename_item) {
                                          if(strlen($middlename_item) > 0){
                                                $temp_middle .= $middlename_item[0].'. ';
                                          } 
                                    }
                              }
							  if($teacher_item->suffix != null){
								  $temp_suffix = ' '.$teacher_item->suffix;
							  }
							  
							  if($teacher_item->title != null){
								  $temp_title = $teacher_item->title.' ';
							  }
							  
                              $adviser = $temp_title.$teacher_item->firstname.' '.$temp_middle.$teacher_item->lastname.$temp_suffix;
                        }
                       
                        if($setup == 2){
                              $sheet->setCellValue('S10', $subjinfo[0]->ww / 100 );
                              $sheet->setCellValue('AG10', $subjinfo[0]->pt / 100 );
                              $sheet->getStyle('S10')
                                    ->getNumberFormat()
                                    ->setFormatCode('0%;[Red]-0%');
                              $sheet->getStyle('AG10')
                                    ->getNumberFormat()
                                    ->setFormatCode('0%;[Red]-0%');
                              if($subjinfo[0]->qa != null && $subjinfo[0]->qa != 0){
                                    $sheet->setCellValue('AM10', $subjinfo[0]->qa / 100 );

                                    $sheet->getStyle('AM10')
                                          ->getNumberFormat()
                                          ->setFormatCode('0%;[Red]-0%');
                              }
                              $sheet->setCellValue('G4', $schoolinfo->regiontext);
                              $sheet->setCellValue('O4', $schoolinfo->divisiontext);
                              $sheet->setCellValue('Y4', $schoolinfo->districttext);
                              $sheet->setCellValue('G5', $schoolinfo->schoolname);
                              $sheet->setCellValue('Y5', $schoolinfo->schoolid);
                              $sheet->setCellValue('AI5', $sy->sydesc);
                              $sheet->setCellValue('K7', $levelinfo->levelname.' '.$section->sectionname);
                              $sheet->setCellValue('U7', $adviser);
                              $sheet->setCellValue('AF7', $subjinfo[0]->subjdesc);
                              $sheet->setCellValue('F8', 'Written Works('.$subjinfo[0]->ww.'%)');
                              $sheet->setCellValue('T8', 'Performance Task('.$subjinfo[0]->pt.'%)');
                              if($subjinfo[0]->qa != null && $subjinfo[0]->qa != 0){
                                    $sheet->setCellValue('AH8', 'Quarterly Assessment('.$subjinfo[0]->qa.'%)');
                              }
                        }
                        else if($setup == 4){
                              $sheet->setCellValue('A5', 'Subject: '. $subjinfo[0]->subjdesc);
                              $sheet->setCellValue('C8', 'Written Works('.$subjinfo[0]->ww.'%)');
                              $sheet->setCellValue('M8', 'Performance Task('.$subjinfo[0]->pt.'%)');
                              $sheet->setCellValue('S8', 'Quarterly Assessment('.$subjinfo[0]->qa.'%)');
                              $sheet->setCellValue('L11', $subjinfo[0]->ww / 100 );
                              $sheet->setCellValue('R11', $subjinfo[0]->pt / 100 );
                              $sheet->setCellValue('V11', $subjinfo[0]->qa / 100 );
                              $sheet->setCellValue('T6', 'SY:'. $sy->sydesc);
                              //$sheet->setCellValue('A6', 'SUBJECT & GRADE LEVEL: '. $levelinfo->levelname . ' - '. $subjinfo[0]->subjdesc);
                              $sheet->setCellValue('A6', 'Teacher: '. $adviser);
                        }
                        else{

                              $sheet->setCellValue('G5', $schoolinfo->schoolname);
                              $sheet->setCellValue('X5', $schoolinfo->schoolid);
                              $sheet->setCellValue('AH5', $sy->sydesc);
                              $sheet->setCellValue('K7', $levelinfo->levelname.' '.$section->sectionname);
      
                              $sheet->setCellValue('G4', $schoolinfo->regiontext);
                              $sheet->setCellValue('O4', $schoolinfo->divisiontext);
                              $sheet->setCellValue('X4', $schoolinfo->districttext);
      
                              if($levelinfo->acadprogid == 5){
                                    $sheet->setCellValue('AC7', $subjinfo[0]->subjdesc);
                              }else{
                                    $sheet->setCellValue('AE7', $subjinfo[0]->subjdesc);
                              }
                            
                              $sheet->setCellValue('F8', 'Written Works('.$subjinfo[0]->ww.'%)');
                              $sheet->setCellValue('S8', 'Performance Task('.$subjinfo[0]->pt.'%)');

                              if($subjinfo[0]->qa != null && $subjinfo[0]->qa != 0){
                                    if($setup == 3){
                                          $sheet->setCellValue('AF8', 'Quarterly Assessment('.$subjinfo[0]->qa.'%)');
                                    }else if($setup == 1){
                                          $sheet->setCellValue('AF8', 'Quarterly Assessment('.$subjinfo[0]->qa.'%)');
                                    }
                                   
                              }

                              $sheet->setCellValue('R11', $subjinfo[0]->ww / 100 );
                              $sheet->setCellValue('AE11', $subjinfo[0]->pt / 100 );

                              $sheet->getStyle('R11')
                                          ->getNumberFormat()
                                          ->setFormatCode('0%;[Red]-0%');
                              
                              $sheet->getStyle('AE11')
                                    ->getNumberFormat()
                                    ->setFormatCode('0%;[Red]-0%');

                              if($subjinfo[0]->qa != null && $subjinfo[0]->qa != 0){
                              
                                    if($setup == 3){
                                          $sheet->setCellValue('AK11', $subjinfo[0]->qa / 100 );
                                          $sheet->getStyle('AK11')
                                                ->getNumberFormat()
                                                ->setFormatCode('0%;[Red]-0%');
                                    }else if($setup == 1){
                                          $sheet->setCellValue('AH11', $subjinfo[0]->qa / 100 );
                                          $sheet->getStyle('AH11')
                                                ->getNumberFormat()
                                                ->setFormatCode('0%;[Red]-0%');
                                    }
                              }

                              $sheet->setCellValue('U7', $adviser);
                              $sheet->setCellValue('T7', $adviser);
                              if($setup == 3){
                                    $sheet->setCellValue('S7', $adviser);
                              }
                        }


                        $row_count = 1;

                        if($levelinfo->acadprogid != 5){
                              if(isset($check_subject->isSP)){
                                    if($check_subject->isSP == 1){
                                          $student = DB::table('enrolledstud')
                                                            ->join('studinfo',function($join){
                                                                  $join->on('studinfo.id','=','enrolledstud.studid');
                                                                  $join->where('studinfo.deleted',0);
                                                            })
                                                            ->join('subjects_studspec',function($join) use($subjid,$syid,$quarter){
                                                                  $join->on('enrolledstud.studid','=','subjects_studspec.studid');
                                                                  $join->where('subjects_studspec.deleted',0);
                                                                  $join->where('subjects_studspec.syid',$syid);
                                                                  $join->where('subjects_studspec.subjid',$subjid);
                                                                  if($quarter == 1){
                                                                        $join->where('subjects_studspec.q1',1);
                                                                  }else if($quarter == 2){
                                                                        $join->where('subjects_studspec.q2',1);
                                                                  }else if($quarter == 3){
                                                                        $join->where('subjects_studspec.q3',1);
                                                                  }else if($quarter == 4){
                                                                        $join->where('subjects_studspec.q4',1);
                                                                  }
                                                            })
                                                            ->where('enrolledstud.deleted',0)
                                                            ->where('enrolledstud.syid',$syid)
                                                            ->where('enrolledstud.sectionid',$sectionid)
                                                            ->whereIn('enrolledstud.studstatus',[1,2,4])
                                                            ->orderBy('gender','desc')
                                                            ->orderBy('studentname','asc')
                                                            ->select(
                                                                  'lastname',
                                                                  'firstname',
                                                                  'middlename',
                                                                  'suffix',
                                                                  'enrolledstud.levelid',
                                                                  'enrolledstud.sectionid',
                                                                  'dob',
                                                                  'gender',
                                                                  'lrn',
                                                                  'sid',
                                                                  'studinfo.id',
                                                                  DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                                            )
                                                            ->get();


                                          foreach($student as $stud_item){
                                                $temp_middle = '';
                                                if(isset($stud_item->middlename)){
                                                      $middlename = explode(" ",$stud_item->middlename);
                                                      if($stud_item->middlename != '' && $stud_item->middlename != null){
                                                            $temp_middle = $item->middlename[0].'.';
                                                      }
                                                }
                                                $stud_item->student = $stud_item->lastname.', '.$stud_item->firstname.' '.$stud_item->suffix.' '.$temp_middle;
                                          }
                                    }
                              }
                        }

                        foreach($student as $item){

                              $range = 'A'.$count.':'.'AJ'.$count;
                              if($setup == 2){
                                    $range = 'A'.$count.':'.'AO'.$count;
                              }else if($setup == 3){
                                    $range = 'A'.$count.':'.'AM'.$count;
                              }else if($setup == 4){
                                    $range = 'A'.$count.':'.'AX'.$count;
                              }

                              if($male == 0 && strtoupper($item->gender) == 'MALE' && $setup != 4){
                                    $sheet->setCellValue('B'.$count,'MALE');
                                    $sheet->setCellValue('P'.$count,"");
                                    $sheet->setCellValue('Q'.$count,"");
                                    $sheet->setCellValue('R'.$count,"");
                                    $sheet->setCellValue('AC'.$count,"");
                                    $sheet->setCellValue('AD'.$count,"");
                                    $sheet->setCellValue('AE'.$count,"");
                                    $sheet->setCellValue('AI'.$count,"");
                                    $sheet->setCellValue('AJ'.$count,"");
                                    $sheet->getStyle($range)->getFill()
                                          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                          ->getStartColor()->setARGB('bfbfbf');

                                    $sheet->getStyle($range)->applyFromArray($gender);

                                    $male = 1;
                                    $count +=1;
                              }elseif($female == 0  && strtoupper($item->gender) == 'FEMALE'  && $setup != 4){
                                    $sheet->setCellValue('B'.$count,'FEMALE');
                                    $sheet->setCellValue('P'.$count,"");
                                    $sheet->setCellValue('Q'.$count,"");
                                    $sheet->setCellValue('R'.$count,"");
                                    $sheet->setCellValue('AC'.$count,"");
                                    $sheet->setCellValue('AD'.$count,"");
                                    $sheet->setCellValue('AE'.$count,"");
                                    $sheet->setCellValue('AI'.$count,"");
                                    $sheet->setCellValue('AJ'.$count,"");

                                    if($setup == 4){
                                          $sheet->setCellValue('A'.$count,"");
                                          $sheet->setCellValue('M'.$count,"");
                                          $sheet->setCellValue('N'.$count,"");
                                          $sheet->setCellValue('R'.$count,"");
                                          $sheet->setCellValue('S'.$count,"");
                                          $sheet->setCellValue('V'.$count,"");
                                          $sheet->setCellValue('W'.$count,"");
                                          $sheet->setCellValue('X'.$count,"");
                                          $sheet->mergeCells('B'.$count.':D'.$count);
                                    }else{
                                          $sheet->getStyle($range)->applyFromArray($gender);
                                          $sheet->getStyle($range)->getFill()
                                          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                          ->getStartColor()->setARGB('bfbfbf');

                                    }

                                    $female = 1;
                                    $count +=1;
                                    $row_count = 1;
                              }


                              $check_detail = DB::table('gradesdetail')
                                                ->where('studid',$item->id)
                                                ->where('headerid',$headerid)
                                                ->first();

                              if(isset($check_detail->id)){

                                    if($setup == 2){
                                          self::spct_ecr_detail_download($sheet, $check_detail, $count);
                                    }
                                    else if($setup == 4){  
                                          $sheet->setCellValue('C'.$count,$check_detail->ww1 == 0 ? "" : $check_detail->ww1);
                                          $sheet->setCellValue('D'.$count,$check_detail->ww2 == 0 ? "" : $check_detail->ww2);
                                          $sheet->setCellValue('E'.$count,$check_detail->ww3 == 0 ? "" : $check_detail->ww3);
                                          $sheet->setCellValue('F'.$count,$check_detail->ww4 == 0 ? "" : $check_detail->ww4);
                                          $sheet->setCellValue('G'.$count,$check_detail->ww5 == 0 ? "" : $check_detail->ww5);
                                          $sheet->setCellValue('H'.$count,$check_detail->ww6 == 0 ? "" : $check_detail->ww6);
                                          $sheet->setCellValue('I'.$count,$check_detail->ww7 == 0 ? "" : $check_detail->ww7);
                                          $sheet->setCellValue('J'.$count,$check_detail->ww8 == 0 ? "" : $check_detail->ww8);

                                          $sheet->setCellValue('M'.$count,$check_detail->pt1 == 0 ? "" : $check_detail->pt1);
                                          $sheet->setCellValue('N'.$count,$check_detail->pt2 == 0 ? "" : $check_detail->pt2);
                                          $sheet->setCellValue('O'.$count,$check_detail->pt3 == 0 ? "" : $check_detail->pt3);
                                          $sheet->setCellValue('P'.$count,$check_detail->pt4 == 0 ? "" : $check_detail->pt4);

                                          $sheet->setCellValue('S'.$count,$check_detail->qa1 == 0 ? "": $check_detail->qa1);
                                          $sheet->setCellValue('T'.$count,$check_detail->qa1 == 0 ? "": $check_detail->qa2);
                                    }
                                    else{
                                          $sheet->setCellValue('F'.$count,$check_detail->ww1 == 0 ? $check_header[0]->wwhr1 != 0 ? 0 : "" : $check_detail->ww1);
                                          $sheet->setCellValue('G'.$count,$check_detail->ww2 == 0 ? $check_header[0]->wwhr2 != 0 ? 0 : "" : $check_detail->ww2);
                                          $sheet->setCellValue('H'.$count,$check_detail->ww3 == 0 ? $check_header[0]->wwhr3 != 0 ? 0 : "" : $check_detail->ww3);
                                          $sheet->setCellValue('I'.$count,$check_detail->ww4 == 0 ? $check_header[0]->wwhr4 != 0 ? 0 : "" : $check_detail->ww4);
                                          $sheet->setCellValue('J'.$count,$check_detail->ww5 == 0 ? $check_header[0]->wwhr5 != 0 ? 0 : "" : $check_detail->ww5);
                                          $sheet->setCellValue('K'.$count,$check_detail->ww6 == 0 ? $check_header[0]->wwhr6 != 0 ? 0 : "" : $check_detail->ww6);
                                          $sheet->setCellValue('L'.$count,$check_detail->ww7 == 0 ? $check_header[0]->wwhr7 != 0 ? 0 : "" : $check_detail->ww7);
                                          $sheet->setCellValue('M'.$count,$check_detail->ww8 == 0 ? $check_header[0]->wwhr8 != 0 ? 0 : "" : $check_detail->ww8);
                                          $sheet->setCellValue('N'.$count,$check_detail->ww9 == 0 ? $check_header[0]->wwhr9 != 0 ? 0 : "" : $check_detail->ww9);
                                          $sheet->setCellValue('O'.$count,$check_detail->ww0 == 0 ? $check_header[0]->wwhr0 != 0 ? 0 : "" : $check_detail->ww0);

                                          $sheet->setCellValue('S'.$count,$check_detail->pt1 == 0 ? $check_header[0]->pthr1 != 0 ? 0 : ""  : $check_detail->pt1);
                                          $sheet->setCellValue('T'.$count,$check_detail->pt2 == 0 ? $check_header[0]->pthr2 != 0 ? 0 : "" : $check_detail->pt2);
                                          $sheet->setCellValue('U'.$count,$check_detail->pt3 == 0 ? $check_header[0]->pthr3 != 0 ? 0 : "" : $check_detail->pt3);
                                          $sheet->setCellValue('V'.$count,$check_detail->pt4 == 0 ? $check_header[0]->pthr4 != 0 ? 0 : "" : $check_detail->pt4);
                                          $sheet->setCellValue('W'.$count,$check_detail->pt5 == 0 ? $check_header[0]->pthr5 != 0 ? 0 : "" : $check_detail->pt5);
                                          $sheet->setCellValue('X'.$count,$check_detail->pt6 == 0 ? $check_header[0]->pthr6 != 0 ? 0 : "" : $check_detail->pt6);
                                          $sheet->setCellValue('Y'.$count,$check_detail->pt7 == 0 ? $check_header[0]->pthr7 != 0 ? 0 : "" : $check_detail->pt7);
                                          $sheet->setCellValue('Z'.$count,$check_detail->pt8 == 0 ? $check_header[0]->pthr8 != 0 ? 0 : "" : $check_detail->pt8);
                                          $sheet->setCellValue('AA'.$count,$check_detail->pt9 == 0 ? $check_header[0]->pthr9 != 0 ? 0 : "" : $check_detail->pt9);
                                          $sheet->setCellValue('AB'.$count,$check_detail->pt0 == 0 ? $check_header[0]->pthr0 != 0 ? 0 : "" : $check_detail->pt0);

                                          if($subjinfo[0]->qa != null && $subjinfo[0]->qa != 0){
                                                if($setup == 3){
                                                        if($check_header[0]->qahr1 != 0 && $check_detail->qa1 == 0){
                                                            $sheet->setCellValue('AF'.$count,0);
                                                        }else{
                                                             $sheet->setCellValue('AF'.$count,$check_detail->qa1 == 0 ? "": $check_detail->qa1);
                                                        }
                                                        
                                                        if($check_header[0]->qahr2 != 0 && $check_detail->qa2 == 0){
                                                            $sheet->setCellValue('AG'.$count,0);
                                                        }else{
                                                             $sheet->setCellValue('AG'.$count,$check_detail->qa2 == 0 ? "": $check_detail->qa2);
                                                        }

                                                        if($check_header[0]->qahr2 != 0 && $check_detail->qa3 == 0){
                                                            $sheet->setCellValue('AH'.$count,0);
                                                        }else{
                                                             $sheet->setCellValue('AH'.$count,$check_detail->qa3 == 0 ? "": $check_detail->qa3);
                                                        }
                                                }
                                                else if($setup == 1){
                                                      $sheet->setCellValue('AF'.$count,$check_detail->qa1 == 0 ? "": $check_detail->qa1);
                                                }
                                          }
                                    }
                              }

                              if($subjinfo[0]->qa != null && $subjinfo[0]->qa != 0){
                                    if($setup == 3){
                                          $sheet->setCellValue('AL'.$count,'=IF(OR(R'.$count.'="",AE'.$count.'="",AK'.$count.'=""),"",SUM(R'.$count.',AE'.$count.',AK'.$count.'))');
                                    }else if($setup == 1){
                                          $sheet->setCellValue('AI'.$count,'=IF(OR(R'.$count.'="",AE'.$count.'="",AH'.$count.'=""),"",SUM(R'.$count.',AE'.$count.',AH'.$count.'))');
                                    }
                              }

                              if($setup == 4){
                                    $sheet->setCellValue('B'.$count,$item->lastname.', '.$item->firstname.', '.$item->middlename);
                              }else{
                                    $sheet->setCellValue('B'.$count,$item->student);
                              }

                              if($setup == 4){
                                    //$sheet->setCellValue('A'.$count,$row_count);
                                    $sheet->setCellValue('AZ'.$count, $item->id);
                                    $count +=2;
                                    $row_count += 2;
                              }else{
                                    $sheet->setCellValue('A'.$count,$row_count);
                                    $sheet->setCellValue('AZ'.$count, $item->id);
                                    $count +=1;
                                    $row_count += 1;
                              }
                              
                              
                        }

                        // $sheet->setCellValue('B'.($count+2),'Prepared by:');
                        $sheet->setCellValue('B'.($count+6),strtoupper($adviser));
                        $sheet->mergeCells('B'.($count+6).':E'.($count+6));
                        $sheet->setCellValue('B'.($count+3),'Prepared by:');
                        $sheet->getStyle('B'.($count+6).':E'.($count+6))->applyFromArray($signatories);
                        $sheet->getStyle('B'.($count+6))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                        $sheet->getStyle('B'.($count+2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                        $sheet->getStyle('B'.($count+3))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                        $sheet->mergeCells('B'.($count+7).':E'.($count+7));
                        $sheet->setCellValue('B'.($count+7),'Subject Teacher');
                        $sheet->getStyle('B'.($count+7))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                         
                        // $sheet->mergeCells('M'.($count+2).':R'.($count+2));
                        // $sheet->setCellValue('M'.($count+2),'Checked by:');
               
                        $sheet->mergeCells('M'.($count+6).':R'.($count+6));
                        $sheet->setCellValue('M'.($count+6),strtoupper('Lilybeth A. Villegas'));
                        $sheet->getStyle('M'.($count+6).':R'.($count+6))->applyFromArray($signatories);
                        $sheet->mergeCells('M'.($count+3).':R'.($count+3));
                        $sheet->setCellValue('M'.($count+3),'Checked by:');
                        
                        $sheet->mergeCells('M'.($count+7).':R'.($count+7));
                        $sheet->setCellValue('M'.($count+7),'School Administrator');
                        $sheet->getStyle('M'.($count+7))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                        
                        $sheet->getStyle('M'.($count+6))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                        $sheet->getStyle('M'.($count+2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                        $sheet->getStyle('M'.($count+3))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                        


                        if($setup == 2){ //spct
                              $sheet->getStyle('A'.$count.':'.'AO150')->applyFromArray($no_border);
                              $sheet->getStyle('A'.$count.':'.'AO'.$count)->applyFromArray($thick_top);
                              $sheet->getPageSetup()->setPrintArea('A1:AO'.($count-1));
                        }
                        else if($setup == 4){ //shjms
                              //$temp_count = 72 - $count+1;
                              $remaining_rows = 86 - $count;

                              for($x = $count ; $x <= ( $count + $remaining_rows ); $x++){
                                    $sheet->removeRow($count);
                              }

                              // for($x = $count ; $x <= ( $count + $remaining_rows ); $x++){
                              //       $sheet->removeRow($x);
                              // }
                             
                              //$sheet->getPageSetup()->setPrintArea('A1:X'.($count+5));
                        }
                        else{
                              if($setup == 3){
                                    $sheet->getStyle('A'.$count.':'.'AM150')->applyFromArray($no_border);
                                    $sheet->getStyle('A'.$count.':'.'AM'.$count)->applyFromArray($thick_top);
                                    $sheet->getPageSetup()->setPrintArea('A1:AM'.($count-1));
                              }else if($setup == 1){
                                    $sheet->getStyle('A'.$count.':'.'AJ150')->applyFromArray($no_border);
                                    $sheet->getStyle('A'.$count.':'.'AJ'.$count)->applyFromArray($thick_top);
                                    $sheet->getPageSetup()->setPrintArea('A1:AJ'.($count-1));
                              }
                           
                        }
                       
                        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                        $sheet->getPageSetup()->setFitToPage(true);

                        $sheet->getPageMargins()->setTop(0);
                        $sheet->getPageMargins()->setRight(0);
                        $sheet->getPageMargins()->setLeft(0);
                        $sheet->getPageMargins()->setBottom(0);
            
                        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
                        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
                        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToPage(true);
                        
                        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);
                        $spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(85);

                  }

            }


            // Summary of quarter grades
            if($levelinfo->acadprogid == 5){
                  $sheet = $spreadsheet->setActiveSheetIndex(2);
                  if( $semid == 2){
                        $sheet->setTitle('SUM QUARTERLY GRADES 2ND SEM');
                        $sheet->setCellValue('F10', "3rd Quarter");
                        $sheet->setCellValue('N10', "4th Quarter");
                  }
            }else{
                  $sheet = $spreadsheet->setActiveSheetIndex(4);
            }

        
            $row_count = 1;
            if($setup==4){
                  $count = 13;
            }else{
                  $count = 11;
            }
           
            $male = 0;
            $female = 0;

            if($setup==4){
           
            }else{
                  $sheet->setCellValue('G6', $schoolinfo->schoolname);
                  $sheet->setCellValue('W6', $schoolinfo->schoolid);
                 
                  $sheet->setCellValue('K8', $levelinfo->levelname.' '.$section->sectionname);
      
                  $sheet->setCellValue('G5', $schoolinfo->regiontext);
                  $sheet->setCellValue('O5', $schoolinfo->divisiontext);
                  $sheet->setCellValue('W5', $schoolinfo->districttext);
                  if($levelinfo->acadprogid == 5){
                      $sheet->setCellValue('Q9', $subjinfo[0]->subjdesc);
                      $sheet->setCellValue('I9', $adviser);
                      $sheet->setCellValue('Y8', $sy->sydesc);
                  }else{
                      $sheet->setCellValue('W9', $subjinfo[0]->subjdesc);
                      $sheet->setCellValue('K9', $adviser);
                      $sheet->setCellValue('W8', $sy->sydesc);
                  }
            }

            foreach($student as $item){

                  if($male == 0 && strtoupper($item->gender) == 'MALE'){
                        $sheet->setCellValue('B'.$count,'MALE');
                        $sheet->setCellValue('P'.$count,"");
                        $sheet->setCellValue('Q'.$count,"");
                        $sheet->setCellValue('R'.$count,"");
                        $sheet->setCellValue('AC'.$count,"");
                        $sheet->setCellValue('AD'.$count,"");
                        $sheet->setCellValue('AE'.$count,"");
                        $sheet->setCellValue('AI'.$count,"");
                        $sheet->setCellValue('AJ'.$count,"");

                        $sheet->getStyle('A'.$count.':'.'AB'.$count)->getFill()
                              ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                              ->getStartColor()->setARGB('bfbfbf');

                        $sheet->getStyle('A'.$count.':'.'AB'.$count)->applyFromArray($gender);

                        $male = 1;
                        $count +=1;

                  }elseif($female == 0  && strtoupper($item->gender) == 'FEMALE'){
                        $sheet->setCellValue('B'.$count,'FEMALE');
                        $sheet->setCellValue('P'.$count,"");
                        $sheet->setCellValue('Q'.$count,"");
                        $sheet->setCellValue('R'.$count,"");
                        $sheet->setCellValue('AC'.$count,"");
                        $sheet->setCellValue('AD'.$count,"");
                        $sheet->setCellValue('AE'.$count,"");
                        $sheet->setCellValue('AI'.$count,"");
                        $sheet->setCellValue('AJ'.$count,"");

                        $sheet->getStyle('A'.$count.':'.'AB'.$count)->getFill()
                              ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                              ->getStartColor()->setARGB('bfbfbf');

                        $sheet->getStyle('A'.$count.':'.'AB'.$count)->applyFromArray($gender);

                        $female = 1;
                        $count +=1;
                        $row_count = 1;
                  }

                  $sheet->setCellValue('A'.$count,$row_count);
                  $sheet->setCellValue('B'.$count,$item->student);
                  $sheet->setCellValue('AZ'.$count, $item->id);
                  $count += 1;
                  $row_count += 1;
            }

            if($setup==4){
                  $temp_count = 72 - $count+1;
                  $sheet->removeRow($count,$temp_count);
            }
            
            $sheet->getStyle('A'.$count.':'.'AB150')->applyFromArray($no_border);
            $sheet->getStyle('A'.$count.':'.'AB'.$count)->applyFromArray($thick_top);

            $sheet->getPageSetup()->setPrintArea('A1:AB'.($count-1));

            $sheet->getPageSetup()->setFitToPage(true);
            $sheet->getPageMargins()->setTop(0);
            $sheet->getPageMargins()->setRight(0);
            $sheet->getPageMargins()->setLeft(0);
            $sheet->getPageMargins()->setBottom(0);

            $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
            $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
            $spreadsheet->getActiveSheet()->getPageSetup()->setFitToPage(true);
            
            $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);
            $spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(85);
            // Summary of quarter grades


            foreach($headers_array as $headerItem){
                  DB::table('gradelogs')
                        ->insert([
                              'action'=>7,
                              'actiontext'=>'Download',
                              'gradeid'=>$headerItem,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            }
            
            $sheet = $spreadsheet->setActiveSheetIndex(0);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="ECR '.$strandcode.' '.$section->sectionname.' - '.$subjinfo[0]->subjdesc.' - '.$sy->sydesc.'.xlsx"');
            $writer->save("php://output");
            exit();
      }

      public static function upload_ecr(Request $request){
            
            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $quarter = $request->get('quarter');
            $schoolinfo = DB::table('schoolinfo')->first();


            if($schoolinfo->abbreviation == 'HCHS CP'){
                  if($syid >= 3){
                      return \App\Http\Controllers\SuperAdminController\TeacherECR\HCHSCPDownloadUpload::upload_ecr($request);
                  }
            }

            $setup = '1'; //default with single quarterly assesment
            if($schoolinfo->snr == 'spct'){
                  $setup = '2'; //spct setup
            }else if($schoolinfo->snr == 'mac'){
                  $setup = '3';  //default with single quarterly assesment
            }else if($schoolinfo->snr == 'shjms'){
                  $setup = '4';
            }else{
                  $setup = '3';
            }

            $subjinfo = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null,$subjid,$levelid,null,$syid);

            if($subjinfo[0]->subjdesc == 'HOMEROOM'){
                  return self::upload_homeroom($request);
            }
         
            $check_header = DB::table('grades')
                              ->where('syid',$syid)
                              ->where('levelid',$levelid)
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjid)
                              ->where('quarter',$quarter)
                              ->where('deleted',0)
                              ->get();

            if(count($check_header) == 0){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Does not contain details'
                  ]);
            }else if(count($check_header) > 1){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Contains multiple details'
                  ]);
            }

            // return $request->all

            $path = $request->file('input_ecr')->getRealPath();
            
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($path);
            if($levelid == 14 || $levelid == 15){
                  if($quarter == 3){
                        $quarter = 1;
                  }else if($quarter == 4){
                        $quarter = 2;
                  }
            }
         
            $worksheet = $spreadsheet->setActiveSheetIndex($quarter-1);
            
            $data = $worksheet->toArray();
           
            $id_col = 52 - 1;
            $headerid = $data[8][$id_col];

          

            if($headerid != $check_header[0]->id){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Unrecognized ECR'
                  ]);
            }

            $start_row = 10;

            $check = DB::table('grades')
                  ->where('id',$headerid)
                  ->get();
                  
            if($check[0]->submitted == 0){
                  if($setup == 2){
                        self::spct_ecr_header_upload($headerid,$data,$start_row);
                  }
                  else if($setup == 3){
                        DB::table('grades')
                              ->where('id',$headerid)
                              ->take(1)
                              ->update([
                                    'wwhr1'=>(float)$data[$start_row][5],
                                    'wwhr2'=>(float)$data[$start_row][6],
                                    'wwhr3'=>(float)$data[$start_row][7],
                                    'wwhr4'=>(float)$data[$start_row][8],
                                    'wwhr5'=>(float)$data[$start_row][9],
                                    'wwhr6'=>(float)$data[$start_row][10],
                                    'wwhr7'=>(float)$data[$start_row][11],
                                    'wwhr8'=>(float)$data[$start_row][12],
                                    'wwhr9'=>(float)$data[$start_row][13],
                                    'wwhr0'=>(float)$data[$start_row][14],
                                    'wwhrtotal'=>(float)$data[$start_row][15],

                                    'pthr1'=>(float)$data[$start_row][18],
                                    'pthr2'=>(float)$data[$start_row][19],
                                    'pthr3'=>(float)$data[$start_row][20],
                                    'pthr4'=>(float)$data[$start_row][21],
                                    'pthr5'=>(float)$data[$start_row][22],
                                    'pthr6'=>(float)$data[$start_row][23],
                                    'pthr7'=>(float)$data[$start_row][24],
                                    'pthr8'=>(float)$data[$start_row][25],
                                    'pthr9'=>(float)$data[$start_row][26],
                                    'pthr0'=>(float)$data[$start_row][27],
                                    'pthrtotal'=>(float)$data[$start_row][28],

                                    'qahr1'=>(float)$data[$start_row][31],
                                    'qahr2'=>(float)$data[$start_row][32],
                                    'qahr3'=>(float)$data[$start_row][33],
                                    'qahrtotal'=>(float)$data[$start_row][34],

                                    'uploadedby'=>auth()->user()->id,
                                    'uploadeddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
                  else if($setup == 1){
                        DB::table('grades')
                              ->where('id',$headerid)
                              ->take(1)
                              ->update([
                                    'wwhr1'=>(float)$data[$start_row][5],
                                    'wwhr2'=>(float)$data[$start_row][6],
                                    'wwhr3'=>(float)$data[$start_row][7],
                                    'wwhr4'=>(float)$data[$start_row][8],
                                    'wwhr5'=>(float)$data[$start_row][9],
                                    'wwhr6'=>(float)$data[$start_row][10],
                                    'wwhr7'=>(float)$data[$start_row][11],
                                    'wwhr8'=>(float)$data[$start_row][12],
                                    'wwhr9'=>(float)$data[$start_row][13],
                                    'wwhr0'=>(float)$data[$start_row][14],
                                    'wwhrtotal'=>(float)$data[$start_row][15],
                                    'pthr1'=>(float)$data[$start_row][18],
                                    'pthr2'=>(float)$data[$start_row][19],
                                    'pthr3'=>(float)$data[$start_row][20],
                                    'pthr4'=>(float)$data[$start_row][21],
                                    'pthr5'=>(float)$data[$start_row][22],
                                    'pthr6'=>(float)$data[$start_row][23],
                                    'pthr7'=>(float)$data[$start_row][24],
                                    'pthr8'=>(float)$data[$start_row][25],
                                    'pthr9'=>(float)$data[$start_row][26],
                                    'pthr0'=>(float)$data[$start_row][27],
                                    'pthrtotal'=>(float)$data[$start_row][28],
                                    'qahr1'=>(float)$data[$start_row][31],
                                    'uploadedby'=>auth()->user()->id,
                                    'uploadeddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
            }

            $start_row = 12 - 1;
           

            for($x = $start_row; $x <= 150; $x++){

                  try{
                        if($data[$start_row][1] != 'MALE' && $data[$start_row][1] != 'FEMALE' && $data[$start_row][1] != ""){

                              $check_detail = DB::table('gradesdetail')
                                                ->where('headerid',$headerid)
                                                ->where('studid',$data[$start_row][$id_col])
                                                ->get();

                              $valid_input = true;
                              $studid = $data[$start_row][$id_col];

                              if(count($check_detail) > 1){
                                    $valid_input = false;
                              }

                              if(count($check_detail) == 1){
                                    if($check_detail[0]->gdstatus != 0 && $check_detail[0]->gdstatus != 3){
                                          $valid_input = false;
                                    }
                              }
                             
                              

                              if($valid_input){

                                    if($schoolinfo->snr == 'spct'){
                                      
                                          self::spct_ecr_detail_upload($headerid,$data,$start_row,$check_detail,$studid);
                                    }
                                    else if($setup == 3){
                                          if(count($check_detail) == 0){
                                                DB::table('gradesdetail')
                                                      ->insert([
                                                            'headerid'=>$headerid,
                                                            'studid'=>$studid,

                                                            'ig'=>(float)$data[$start_row][37],
                                                            'qg'=>(float)$data[$start_row][38],
                                                            
                                                            'ww1'=>(float)$data[$start_row][5],
                                                            'ww2'=>(float)$data[$start_row][6],
                                                            'ww3'=>(float)$data[$start_row][7],
                                                            'ww4'=>(float)$data[$start_row][8],
                                                            'ww5'=>(float)$data[$start_row][9],
                                                            'ww6'=>(float)$data[$start_row][10],
                                                            'ww7'=>(float)$data[$start_row][11],
                                                            'ww8'=>(float)$data[$start_row][12],
                                                            'ww9'=>(float)$data[$start_row][13],
                                                            'ww0'=>(float)$data[$start_row][14],
                                                            'wwtotal'=>(float)$data[$start_row][15],
                                                            'wwps'=>(float)$data[$start_row][16],
                                                            'wwws'=>(float)$data[$start_row][17],

                                                            'pt1'=>(float)$data[$start_row][18],
                                                            'pt2'=>(float)$data[$start_row][19],
                                                            'pt3'=>(float)$data[$start_row][20],
                                                            'pt4'=>(float)$data[$start_row][21],
                                                            'pt5'=>(float)$data[$start_row][22],
                                                            'pt6'=>(float)$data[$start_row][23],
                                                            'pt7'=>(float)$data[$start_row][24],
                                                            'pt8'=>(float)$data[$start_row][25],
                                                            'pt9'=>(float)$data[$start_row][26],
                                                            'pt0'=>(float)$data[$start_row][27],
                                                            'pttotal'=>(float)$data[$start_row][28],
                                                            'ptps'=>(float)$data[$start_row][29],

                                                            'ptws'=>(float)$data[$start_row][30],
                                                            'qa1'=>(float)$data[$start_row][31],
                                                            'qa2'=>(float)$data[$start_row][32],
                                                            'qa3'=>(float)$data[$start_row][33],
                                                            'qatotal'=>(float)$data[$start_row][34],
                                                            'qaps'=>(float)$data[$start_row][35],
                                                            'qaws'=>(float)$data[$start_row][36]
                                                      ]);
                                          }else{
                                                DB::table('gradesdetail')
                                                      ->where('headerid',$headerid)
                                                      ->where('studid',$studid)
                                                      ->whereIn('gdstatus',[0,3])
                                                      ->take(1)
                                                      ->update([
                                                            'ig'=>(float)$data[$start_row][37],
                                                            'qg'=>(float)$data[$start_row][38],
                                                            
                                                            
                                                            'ww1'=>(float)$data[$start_row][5],
                                                            'ww2'=>(float)$data[$start_row][6],
                                                            'ww3'=>(float)$data[$start_row][7],
                                                            'ww4'=>(float)$data[$start_row][8],
                                                            'ww5'=>(float)$data[$start_row][9],
                                                            'ww6'=>(float)$data[$start_row][10],
                                                            'ww7'=>(float)$data[$start_row][11],
                                                            'ww8'=>(float)$data[$start_row][12],
                                                            'ww9'=>(float)$data[$start_row][13],
                                                            'ww0'=>(float)$data[$start_row][14],
                                                            'wwtotal'=>(float)$data[$start_row][15],
                                                            'wwps'=>(float)$data[$start_row][16],
                                                            'wwws'=>(float)$data[$start_row][17],


                                                            'pt1'=>(float)$data[$start_row][18],
                                                            'pt2'=>(float)$data[$start_row][19],
                                                            'pt3'=>(float)$data[$start_row][20],
                                                            'pt4'=>(float)$data[$start_row][21],
                                                            'pt5'=>(float)$data[$start_row][22],
                                                            'pt6'=>(float)$data[$start_row][23],
                                                            'pt7'=>(float)$data[$start_row][24],
                                                            'pt8'=>(float)$data[$start_row][25],
                                                            'pt9'=>(float)$data[$start_row][26],
                                                            'pt0'=>(float)$data[$start_row][27],
                                                            'pttotal'=>(float)$data[$start_row][28],
                                                            'ptps'=>(float)$data[$start_row][29],
                                                            'ptws'=>(float)$data[$start_row][30],

                                                            'qa1'=>(float)$data[$start_row][31],
                                                            'qa2'=>(float)$data[$start_row][32],
                                                            'qa3'=>(float)$data[$start_row][33],
                                                            'qatotal'=>(float)$data[$start_row][34],
                                                            'qaps'=>(float)$data[$start_row][35],
                                                            'qaws'=>(float)$data[$start_row][36]
                                                      ]);
                                          }

                                    }elseif($setup == 4){
                                          if(count($check_detail) == 0){
                                                DB::table('gradesdetail')
                                                      ->insert([
                                                            'ig'=>0,
                                                            'qg'=>(float)$data[$start_row][23],
                                                            'ww1'=>(float)$data[$start_row][5],
                                                            'ww2'=>(float)$data[$start_row][6],
                                                            'ww3'=>(float)$data[$start_row][7],
                                                            'ww4'=>(float)$data[$start_row][8],
                                                            'ww5'=>(float)$data[$start_row][9],
                                                            'ww6'=>(float)$data[$start_row][10],
                                                            'ww7'=>(float)$data[$start_row][11],
                                                            'wwps'=>0,
                                                            'wwtotal'=>(float)$data[$start_row][12],
                                                            'wwws'=>(float)$data[$start_row][13],
                                                            'pt1'=>(float)$data[$start_row][14],
                                                            'pt2'=>(float)$data[$start_row][15],
                                                            'pt3'=>(float)$data[$start_row][16],
                                                            'pttotal'=>(float)$data[$start_row][17],
                                                            'ptws'=>(float)$data[$start_row][18],
                                                            'ptps'=>0,
                                                            'qa1'=>(float)$data[$start_row][19],
                                                            'qa2'=>(float)$data[$start_row][20],
                                                            'qaps'=>0,
                                                            'qatotal'=>(float)$data[$start_row][21],
                                                            'qaws'=>(float)$data[$start_row][22]
                                                      ]);
                                          }else{
                                                DB::table('gradesdetail')
                                                      ->where('headerid',$headerid)
                                                      ->where('studid',$studid)
                                                      ->whereIn('gdstatus',[0,3])
                                                      ->take(1)
                                                      ->update([
                                                            'ig'=>0,
                                                            'qg'=>(float)$data[$start_row][23],
                                                            'ww1'=>(float)$data[$start_row][5],
                                                            'ww2'=>(float)$data[$start_row][6],
                                                            'ww3'=>(float)$data[$start_row][7],
                                                            'ww4'=>(float)$data[$start_row][8],
                                                            'ww5'=>(float)$data[$start_row][9],
                                                            'ww6'=>(float)$data[$start_row][10],
                                                            'ww7'=>(float)$data[$start_row][11],
                                                            'wwps'=>0,
                                                            'wwtotal'=>(float)$data[$start_row][12],
                                                            'wwws'=>(float)$data[$start_row][13],
                                                            'pt1'=>(float)$data[$start_row][14],
                                                            'pt2'=>(float)$data[$start_row][15],
                                                            'pt3'=>(float)$data[$start_row][16],
                                                            'pttotal'=>(float)$data[$start_row][17],
                                                            'ptws'=>(float)$data[$start_row][18],
                                                            'ptps'=>0,
                                                            'qa1'=>(float)$data[$start_row][19],
                                                            'qa2'=>(float)$data[$start_row][20],
                                                            'qaps'=>0,
                                                            'qatotal'=>(float)$data[$start_row][21],
                                                            'qaws'=>(float)$data[$start_row][22]
                                                      ]);
                                          }
                                    }
                                    else{

                                          if(count($check_detail) == 0){
                                                DB::table('gradesdetail')
                                                      ->insert([
                                                            'headerid'=>$headerid,
                                                            'studid'=>$studid,
                                                            'ig'=>(float)$data[$start_row][34],
                                                            'qg'=>(float)$data[$start_row][35],
                                                            'wwtotal'=>(float)$data[$start_row][15],
                                                            'wwps'=>(float)$data[$start_row][16],
                                                            'wwws'=>(float)$data[$start_row][17],
                                                            'ww1'=>(float)$data[$start_row][5],
                                                            'ww2'=>(float)$data[$start_row][6],
                                                            'ww3'=>(float)$data[$start_row][7],
                                                            'ww4'=>(float)$data[$start_row][8],
                                                            'ww5'=>(float)$data[$start_row][9],
                                                            'ww6'=>(float)$data[$start_row][10],
                                                            'ww7'=>(float)$data[$start_row][11],
                                                            'ww8'=>(float)$data[$start_row][12],
                                                            'ww9'=>(float)$data[$start_row][13],
                                                            'ww0'=>(float)$data[$start_row][14],
                                                            'pt1'=>(float)$data[$start_row][18],
                                                            'pt2'=>(float)$data[$start_row][19],
                                                            'pt3'=>(float)$data[$start_row][20],
                                                            'pt4'=>(float)$data[$start_row][21],
                                                            'pt5'=>(float)$data[$start_row][22],
                                                            'pt6'=>(float)$data[$start_row][23],
                                                            'pt7'=>(float)$data[$start_row][24],
                                                            'pt8'=>(float)$data[$start_row][25],
                                                            'pt9'=>(float)$data[$start_row][26],
                                                            'pt0'=>(float)$data[$start_row][27],
                                                            'pttotal'=>(float)$data[$start_row][28],
                                                            'ptps'=>(float)$data[$start_row][29],
                                                            'ptws'=>(float)$data[$start_row][30],
                                                            'qa1'=>(float)$data[$start_row][31],
                                                            'qaps'=>(float)$data[$start_row][32],
                                                            'qaws'=>(float)$data[$start_row][33],
                                                      ]);
                                          }else{
                                                DB::table('gradesdetail')
                                                      ->where('headerid',$headerid)
                                                      ->where('studid',$studid)
                                                      ->whereIn('gdstatus',[0,3])
                                                      ->take(1)
                                                      ->update([
                                                            'ig'=>(float)$data[$start_row][34],
                                                            'qg'=>(float)$data[$start_row][35],
                                                            'wwtotal'=>(float)$data[$start_row][15],
                                                            'wwps'=>(float)$data[$start_row][16],
                                                            'wwws'=>(float)$data[$start_row][17],
                                                            'ww1'=>(float)$data[$start_row][5],
                                                            'ww2'=>(float)$data[$start_row][6],
                                                            'ww3'=>(float)$data[$start_row][7],
                                                            'ww4'=>(float)$data[$start_row][8],
                                                            'ww5'=>(float)$data[$start_row][9],
                                                            'ww6'=>(float)$data[$start_row][10],
                                                            'ww7'=>(float)$data[$start_row][11],
                                                            'ww8'=>(float)$data[$start_row][12],
                                                            'ww9'=>(float)$data[$start_row][13],
                                                            'ww0'=>(float)$data[$start_row][14],
                                                            'pt1'=>(float)$data[$start_row][18],
                                                            'pt2'=>(float)$data[$start_row][19],
                                                            'pt3'=>(float)$data[$start_row][20],
                                                            'pt4'=>(float)$data[$start_row][21],
                                                            'pt5'=>(float)$data[$start_row][22],
                                                            'pt6'=>(float)$data[$start_row][23],
                                                            'pt7'=>(float)$data[$start_row][24],
                                                            'pt8'=>(float)$data[$start_row][25],
                                                            'pt9'=>(float)$data[$start_row][26],
                                                            'pt0'=>(float)$data[$start_row][27],
                                                            'pttotal'=>(float)$data[$start_row][28],
                                                            'ptps'=>(float)$data[$start_row][29],
                                                            'ptws'=>(float)$data[$start_row][30],
                                                            'qa1'=>(float)$data[$start_row][31],
                                                            'qaps'=>(float)$data[$start_row][32],
                                                            'qaws'=>(float)$data[$start_row][33],
                                                      ]);
                                          }
                                    }

                                    
                              
                              
                              }


                              
                        }
                  }catch(\Exception $e){

                  }

                  $start_row += 1;

            }

            DB::table('gradelogs')
                  ->insert([
                        'action'=>8,
                        'actiontext'=>'Upload',
                        'gradeid'=>$headerid,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);
           
            return array((object)[
                  'status'=>1,
                  'message'=>'Updated Successfully'
            ]);

      }

      //created 09/20/2021
      public static function upload_homeroom($request){
            
            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $quarter = $request->get('quarter');
            $schoolinfo = DB::table('schoolinfo')->first();

            $check_header = DB::table('grades')
                              ->where('syid',$syid)
                              ->where('levelid',$levelid)
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjid)
                              ->where('quarter',$quarter)
                              ->where('deleted',0)
                              ->get();

            if(count($check_header) == 0){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Does not contain details'
                  ]);
            }else if(count($check_header) > 1){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Contains multiple details'
                  ]);
            }

            $path = $request->file('input_ecr')->getRealPath();
            
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($path);
            $worksheet = $spreadsheet->setActiveSheetIndex($quarter-1);
            $data = $worksheet->toArray();

            $id_col = 77;
            $headerid = $data[9][77];

            if($headerid != $check_header[0]->id){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Unrecognized ECR'
                  ]);
            }

            $start_row = 9;
            
            
            DB::table('grades')
                  ->where('id',$headerid)
                  ->take(1)
                  ->update([
                        'wwhr1'=>(float)$data[$start_row][6],
                        'wwhr2'=>(float)$data[$start_row][7],
                        'wwhr3'=>(float)$data[$start_row][8],
                        'wwhr4'=>(float)$data[$start_row][9],
                        'wwhr5'=>(float)$data[$start_row][10],
                        'wwhr6'=>(float)$data[$start_row][11],
                        'wwhr7'=>(float)$data[$start_row][12],
                        'wwhr8'=>(float)$data[$start_row][13],
                        'wwhr9'=>(float)$data[$start_row][14],
                        'wwhr0'=>(float)$data[$start_row][15],

                        'wwhrtotal'=>(float)$data[$start_row][17],

                        'pthr1'=>(float)$data[$start_row][21],
                        'pthr2'=>(float)$data[$start_row][22],
                        'pthr3'=>(float)$data[$start_row][23],
                        'pthr4'=>(float)$data[$start_row][24],
                        'pthr5'=>(float)$data[$start_row][25],
                        'pthr6'=>(float)$data[$start_row][26],
                        'pthr7'=>(float)$data[$start_row][27],
                        'pthr8'=>(float)$data[$start_row][28],
                        'pthr9'=>(float)$data[$start_row][29],
                        'pthr0'=>(float)$data[$start_row][30],
                        'pthrtotal'=>(float)$data[$start_row][32],

                        'qahr1'=>(float)$data[$start_row][36],
                        'qahr2'=>(float)$data[$start_row][37],
                        'qahr3'=>(float)$data[$start_row][38],
                        'qahr4'=>(float)$data[$start_row][39],
                        'qahr5'=>(float)$data[$start_row][40],
                        'qahr6'=>(float)$data[$start_row][41],
                        'qahr7'=>(float)$data[$start_row][42],
                        'qahr8'=>(float)$data[$start_row][43],
                        'qahr9'=>(float)$data[$start_row][44],
                        'qahr0'=>(float)$data[$start_row][45],

                        'qahrtotal'=>(float)$data[$start_row][47],
                        'uploadedby'=>auth()->user()->id,
                        'uploadeddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);


            $start_row = 12 - 1;

            for($x = $start_row; $x <= 150; $x++){

                  try{
                        if($data[$start_row][2] != 'MALE' && $data[$start_row][2] != 'FEMALE' && $data[$start_row][2] != ""){

                              $check_detail = DB::table('gradesdetail')
                                                ->where('headerid',$headerid)
                                                ->where('studid',$data[$start_row][$id_col])
                                                ->get();

                              $valid_input = true;
                              $studid = $data[$start_row][$id_col];

                              if(count($check_detail) > 1){
                                    $valid_input = false;
                              }

                              if($valid_input){

                                    if(count($check_detail) == 0){

                                          DB::table('gradesdetail')
                                                ->insert([
                                                      'headerid'=>$headerid,
                                                      'studid'=>$studid,
                                                      'ig'=>(float)$data[$start_row][52],
                                                      'qg'=>(float)$data[$start_row][54],

                                                      'wwtotal'=>(float)$data[$start_row][16],
                                                      'wwhps'=>(float)$data[$start_row][17],
                                                      'wwps'=>(float)$data[$start_row][18],
                                                      'wwws'=>(float)$data[$start_row][19],
                                                      'ww1'=>(float)$data[$start_row][6],
                                                      'ww2'=>(float)$data[$start_row][7],
                                                      'ww3'=>(float)$data[$start_row][8],
                                                      'ww4'=>(float)$data[$start_row][9],
                                                      'ww5'=>(float)$data[$start_row][10],
                                                      'ww6'=>(float)$data[$start_row][11],
                                                      'ww7'=>(float)$data[$start_row][12],
                                                      'ww8'=>(float)$data[$start_row][13],
                                                      'ww9'=>(float)$data[$start_row][14],
                                                      'ww0'=>(float)$data[$start_row][15],

                                                      'pt1'=>(float)$data[$start_row][21],
                                                      'pt2'=>(float)$data[$start_row][22],
                                                      'pt3'=>(float)$data[$start_row][23],
                                                      'pt4'=>(float)$data[$start_row][24],
                                                      'pt5'=>(float)$data[$start_row][25],
                                                      'pt6'=>(float)$data[$start_row][26],
                                                      'pt7'=>(float)$data[$start_row][27],
                                                      'pt8'=>(float)$data[$start_row][28],
                                                      'pt9'=>(float)$data[$start_row][29],
                                                      'pt0'=>(float)$data[$start_row][30],

                                                      'pttotal'=>(float)$data[$start_row][31],
                                                      'pthps'=>(float)$data[$start_row][32],
                                                      'ptps'=>(float)$data[$start_row][33],
                                                      'ptws'=>(float)$data[$start_row][34],

                                                      'qa1'=>(float)$data[$start_row][36],
                                                      'qa2'=>(float)$data[$start_row][37],
                                                      'qa3'=>(float)$data[$start_row][38],
                                                      'qa4'=>(float)$data[$start_row][39],
                                                      'qa5'=>(float)$data[$start_row][40],
                                                      'qa6'=>(float)$data[$start_row][41],
                                                      'qa7'=>(float)$data[$start_row][42],
                                                      'qa8'=>(float)$data[$start_row][43],
                                                      'qa9'=>(float)$data[$start_row][44],
                                                      'qa0'=>(float)$data[$start_row][45],

                                                      'qatotal'=>(float)$data[$start_row][46],
                                                      'qahps'=>(float)$data[$start_row][47],
                                                      'qaps'=>(float)$data[$start_row][48],
                                                      'qaws'=>(float)$data[$start_row][49]
                                                ]);
                                    }else{

                                          DB::table('gradesdetail')
                                                ->where('headerid',$headerid)
                                                ->where('studid',$studid)
                                                //->whereIn('gdstatus',[0,3])
                                                ->take(1)
                                                ->update([
                                                      'ig'=>(float)$data[$start_row][52],
                                                      'qg'=>(float)$data[$start_row][54],

                                                      'wwtotal'=>(float)$data[$start_row][16],
                                                      'wwhps'=>(float)$data[$start_row][17],
                                                      'wwps'=>(float)$data[$start_row][18],
                                                      'wwws'=>(float)$data[$start_row][19],
                                                      'ww1'=>(float)$data[$start_row][6],
                                                      'ww2'=>(float)$data[$start_row][7],
                                                      'ww3'=>(float)$data[$start_row][8],
                                                      'ww4'=>(float)$data[$start_row][9],
                                                      'ww5'=>(float)$data[$start_row][10],
                                                      'ww6'=>(float)$data[$start_row][11],
                                                      'ww7'=>(float)$data[$start_row][12],
                                                      'ww8'=>(float)$data[$start_row][13],
                                                      'ww9'=>(float)$data[$start_row][14],
                                                      'ww0'=>(float)$data[$start_row][15],

                                                      'pt1'=>(float)$data[$start_row][21],
                                                      'pt2'=>(float)$data[$start_row][22],
                                                      'pt3'=>(float)$data[$start_row][23],
                                                      'pt4'=>(float)$data[$start_row][24],
                                                      'pt5'=>(float)$data[$start_row][25],
                                                      'pt6'=>(float)$data[$start_row][26],
                                                      'pt7'=>(float)$data[$start_row][27],
                                                      'pt8'=>(float)$data[$start_row][28],
                                                      'pt9'=>(float)$data[$start_row][29],
                                                      'pt0'=>(float)$data[$start_row][30],

                                                      'pttotal'=>(float)$data[$start_row][31],
                                                      'pthps'=>(float)$data[$start_row][32],
                                                      'ptps'=>(float)$data[$start_row][33],
                                                      'ptws'=>(float)$data[$start_row][34],

                                                      'qa1'=>(float)$data[$start_row][36],
                                                      'qa2'=>(float)$data[$start_row][37],
                                                      'qa3'=>(float)$data[$start_row][38],
                                                      'qa4'=>(float)$data[$start_row][39],
                                                      'qa5'=>(float)$data[$start_row][40],
                                                      'qa6'=>(float)$data[$start_row][41],
                                                      'qa7'=>(float)$data[$start_row][42],
                                                      'qa8'=>(float)$data[$start_row][43],
                                                      'qa9'=>(float)$data[$start_row][44],
                                                      'qa0'=>(float)$data[$start_row][45],

                                                      'qatotal'=>(float)$data[$start_row][46],
                                                      'qahps'=>(float)$data[$start_row][47],
                                                      'qaps'=>(float)$data[$start_row][48],
                                                      'qaws'=>(float)$data[$start_row][49]
                                                ]);
                                    }
                              
                              }


                              
                        }
                  }catch(\Exception $e){
                        return $e;
                  }

                  $start_row += 1;

            }

            DB::table('gradelogs')
                  ->insert([
                        'action'=>8,
                        'actiontext'=>'Upload',
                        'gradeid'=>$headerid,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);
           
            return array((object)[
                  'status'=>1,
                  'message'=>'Updated Successfully'
            ]);
     
      }

      public static function spct_ecr_detail_download($sheet = null, $check_detail = null, $count = null){

            $sheet->setCellValue('F'.$count,$check_detail->ww1 == 0 ? "" : $check_detail->ww1);
            $sheet->setCellValue('G'.$count,$check_detail->ww2 == 0 ? "" : $check_detail->ww2);
            $sheet->setCellValue('H'.$count,$check_detail->ww3 == 0 ? "" : $check_detail->ww3);
            $sheet->setCellValue('I'.$count,$check_detail->ww4 == 0 ? "" : $check_detail->ww4);
            $sheet->setCellValue('J'.$count,$check_detail->ww5 == 0 ? "" : $check_detail->ww5);
            $sheet->setCellValue('K'.$count,$check_detail->ww6 == 0 ? "" : $check_detail->ww6);
            $sheet->setCellValue('L'.$count,$check_detail->ww7 == 0 ? "" : $check_detail->ww7);
            $sheet->setCellValue('M'.$count,$check_detail->ww8 == 0 ? "" : $check_detail->ww8);
            $sheet->setCellValue('N'.$count,$check_detail->ww9 == 0 ? "" : $check_detail->ww9);
            $sheet->setCellValue('O'.$count,$check_detail->ww0 == 0 ? "" : $check_detail->ww0);
            $sheet->setCellValue('Q'.$count,$check_detail->wwhps == 0 ? "" : $check_detail->wwhps);

            $sheet->setCellValue('T'.$count,$check_detail->pt1 == 0 ? "" : $check_detail->pt1);
            $sheet->setCellValue('U'.$count,$check_detail->pt2 == 0 ? "" : $check_detail->pt2);
            $sheet->setCellValue('V'.$count,$check_detail->pt3 == 0 ? "" : $check_detail->pt3);
            $sheet->setCellValue('W'.$count,$check_detail->pt4 == 0 ? "" : $check_detail->pt4);
            $sheet->setCellValue('X'.$count,$check_detail->pt5 == 0 ? "" : $check_detail->pt5);
            $sheet->setCellValue('Y'.$count,$check_detail->pt6 == 0 ? "" : $check_detail->pt6);
            $sheet->setCellValue('Z'.$count,$check_detail->pt7 == 0 ? "" : $check_detail->pt7);
            $sheet->setCellValue('AA'.$count,$check_detail->pt8 == 0 ? "" : $check_detail->pt8);
            $sheet->setCellValue('AB'.$count,$check_detail->pt9 == 0 ? "" : $check_detail->pt9);
            $sheet->setCellValue('AC'.$count,$check_detail->pt0 == 0 ? "" : $check_detail->pt0);
            $sheet->setCellValue('AE'.$count,$check_detail->pthps == 0 ? "" : $check_detail->pthps);

            $sheet->setCellValue('AH'.$count,$check_detail->qa1 == 0 ? "" : $check_detail->qa1);
            $sheet->setCellValue('AI'.$count,$check_detail->qa2 == 0 ? "" : $check_detail->qa2);
            $sheet->setCellValue('AK'.$count,$check_detail->qahps == 0 ? "" : $check_detail->qahps);
            
      }

      public static function spct_ecr_header_download($sheet = null, $check_header = null, $count = null){

            $sheet->setCellValue('F'.$count,$check_header->wwhr1 == 0 ? "" : $check_header->wwhr1);
            $sheet->setCellValue('G'.$count,$check_header->wwhr2 == 0 ? "" : $check_header->wwhr2);
            $sheet->setCellValue('H'.$count,$check_header->wwhr3 == 0 ? "" : $check_header->wwhr3);
            $sheet->setCellValue('I'.$count,$check_header->wwhr4 == 0 ? "" : $check_header->wwhr4);
            $sheet->setCellValue('J'.$count,$check_header->wwhr5 == 0 ? "" : $check_header->wwhr5);
            $sheet->setCellValue('K'.$count,$check_header->wwhr6 == 0 ? "" : $check_header->wwhr6);
            $sheet->setCellValue('L'.$count,$check_header->wwhr7 == 0 ? "" : $check_header->wwhr7);
            $sheet->setCellValue('M'.$count,$check_header->wwhr8 == 0 ? "" : $check_header->wwhr8);
            $sheet->setCellValue('N'.$count,$check_header->wwhr9 == 0 ? "" : $check_header->wwhr9);
            $sheet->setCellValue('O'.$count,$check_header->wwhr0 == 0 ? "" : $check_header->wwhr0);
           

            $sheet->setCellValue('T'.$count,$check_header->pthr1 == 0 ? "" : $check_header->pthr1);
            $sheet->setCellValue('U'.$count,$check_header->pthr2 == 0 ? "" : $check_header->pthr2);
            $sheet->setCellValue('V'.$count,$check_header->pthr3 == 0 ? "" : $check_header->pthr3);
            $sheet->setCellValue('W'.$count,$check_header->pthr4 == 0 ? "" : $check_header->pthr4);
            $sheet->setCellValue('X'.$count,$check_header->pthr5 == 0 ? "" : $check_header->pthr5);
            $sheet->setCellValue('Y'.$count,$check_header->pthr6 == 0 ? "" : $check_header->pthr6);
            $sheet->setCellValue('Z'.$count,$check_header->pthr7 == 0 ? "" : $check_header->pthr7);
            $sheet->setCellValue('AA'.$count,$check_header->pthr8 == 0 ? "" : $check_header->pthr8);
            $sheet->setCellValue('AB'.$count,$check_header->pthr9 == 0 ? "" : $check_header->pthr9);
            $sheet->setCellValue('AC'.$count,$check_header->pthr0 == 0 ? "" : $check_header->pthr0);
          
            $sheet->setCellValue('AH'.$count,$check_header->qahr1 == 0 ? "" : $check_header->qahr1);
            $sheet->setCellValue('AI'.$count,$check_header->qahr2 == 0 ? "" : $check_header->qahr2);
           
      }

      public static function spct_ecr_detail_upload($headerid = null, $data = null, $start_row = null, $check_detail = 0, $studid = null){
           
            if(count($check_detail) == 0){
                
                  DB::table('gradesdetail')
                        ->insert([
                              'headerid'=>$headerid,
                              'studid'=>$studid,
                              'ig'=>(float)$data[$start_row][39],
                              'qg'=>(float)$data[$start_row][40],
                        
                              'ww1'=>(float)$data[$start_row][5],
                              'ww2'=>(float)$data[$start_row][6],
                              'ww3'=>(float)$data[$start_row][7],
                              'ww4'=>(float)$data[$start_row][8],
                              'ww5'=>(float)$data[$start_row][9],
                              'ww6'=>(float)$data[$start_row][10],
                              'ww7'=>(float)$data[$start_row][11],
                              'ww8'=>(float)$data[$start_row][12],
                              'ww9'=>(float)$data[$start_row][13],
                              'ww0'=>(float)$data[$start_row][14],
                              'wwhps'=>(float)$data[$start_row][16],
                              'wwtotal'=>(float)$data[$start_row][15],
                              'wwps'=>(float)$data[$start_row][17],
                              'wwws'=>(float)$data[$start_row][18],

                              'pt1'=>(float)$data[$start_row][19],
                              'pt2'=>(float)$data[$start_row][20],
                              'pt3'=>(float)$data[$start_row][21],
                              'pt4'=>(float)$data[$start_row][22],
                              'pt5'=>(float)$data[$start_row][23],
                              'pt6'=>(float)$data[$start_row][24],
                              'pt7'=>(float)$data[$start_row][25],
                              'pt8'=>(float)$data[$start_row][26],
                              'pt9'=>(float)$data[$start_row][27],
                              'pt0'=>(float)$data[$start_row][28],
                              'pthps'=>(float)$data[$start_row][30],
                              'pttotal'=>(float)$data[$start_row][29],
                              'ptps'=>(float)$data[$start_row][31],
                              'ptws'=>(float)$data[$start_row][32],

                              'qa1'=>(float)$data[$start_row][33],
                              'qa2'=>(float)$data[$start_row][34],
                              'qahps'=>(float)$data[$start_row][36],
                              'qatotal'=>(float)$data[$start_row][35],
                              'qaps'=>(float)$data[$start_row][37],
                              'qaws'=>(float)$data[$start_row][38]
                        ]);

            }else{

                  DB::table('gradesdetail')
                        ->where('headerid',$headerid)
                        ->where('studid',$studid)
                        //->whereIn('gdstatus',[0,3])
                        ->take(1)
                        ->update([
                              'ig'=>(float)$data[$start_row][39],
                              'qg'=>(float)$data[$start_row][40],
                        
                              'ww1'=>(float)$data[$start_row][5],
                              'ww2'=>(float)$data[$start_row][6],
                              'ww3'=>(float)$data[$start_row][7],
                              'ww4'=>(float)$data[$start_row][8],
                              'ww5'=>(float)$data[$start_row][9],
                              'ww6'=>(float)$data[$start_row][10],
                              'ww7'=>(float)$data[$start_row][11],
                              'ww8'=>(float)$data[$start_row][12],
                              'ww9'=>(float)$data[$start_row][13],
                              'ww0'=>(float)$data[$start_row][14],
                              'wwhps'=>(float)$data[$start_row][16],
                              'wwtotal'=>(float)$data[$start_row][15],
                              'wwps'=>(float)$data[$start_row][17],
                              'wwws'=>(float)$data[$start_row][18],

                              'pt1'=>(float)$data[$start_row][19],
                              'pt2'=>(float)$data[$start_row][20],
                              'pt3'=>(float)$data[$start_row][21],
                              'pt4'=>(float)$data[$start_row][22],
                              'pt5'=>(float)$data[$start_row][23],
                              'pt6'=>(float)$data[$start_row][24],
                              'pt7'=>(float)$data[$start_row][25],
                              'pt8'=>(float)$data[$start_row][26],
                              'pt9'=>(float)$data[$start_row][27],
                              'pt0'=>(float)$data[$start_row][28],
                              'pthps'=>(float)$data[$start_row][30],
                              'pttotal'=>(float)$data[$start_row][29],
                              'ptps'=>(float)$data[$start_row][31],
                              'ptws'=>(float)$data[$start_row][32],

                              'qa1'=>(float)$data[$start_row][33],
                              'qa2'=>(float)$data[$start_row][34],
                              'qahps'=>(float)$data[$start_row][36],
                              'qatotal'=>(float)$data[$start_row][35],
                              'qaps'=>(float)$data[$start_row][37],
                              'qaws'=>(float)$data[$start_row][38]

                        ]);

            }

      }

      public static function spct_ecr_header_upload($headerid = null, $data = null, $start_row = null){

            DB::table('grades')
                  ->where('id',$headerid)
                  ->take(1)
                  ->update([
                        'wwhr1'=>(float)$data[$start_row][5],
                        'wwhr2'=>(float)$data[$start_row][6],
                        'wwhr3'=>(float)$data[$start_row][7],
                        'wwhr4'=>(float)$data[$start_row][8],
                        'wwhr5'=>(float)$data[$start_row][9],
                        'wwhr6'=>(float)$data[$start_row][10],
                        'wwhr7'=>(float)$data[$start_row][11],
                        'wwhr8'=>(float)$data[$start_row][12],
                        'wwhr9'=>(float)$data[$start_row][13],
                        'wwhr0'=>(float)$data[$start_row][14],
                        'wwhrtotal'=>(float)$data[$start_row][16],
                        'pthr1'=>(float)$data[$start_row][19],
                        'pthr2'=>(float)$data[$start_row][20],
                        'pthr3'=>(float)$data[$start_row][21],
                        'pthr4'=>(float)$data[$start_row][22],
                        'pthr5'=>(float)$data[$start_row][23],
                        'pthr6'=>(float)$data[$start_row][24],
                        'pthr7'=>(float)$data[$start_row][25],
                        'pthr8'=>(float)$data[$start_row][26],
                        'pthr9'=>(float)$data[$start_row][27],
                        'pthr0'=>(float)$data[$start_row][28],
                        'pthrtotal'=>(float)$data[$start_row][30],
                        'qahr1'=>(float)$data[$start_row][33],
                        'qahr2'=>(float)$data[$start_row][34],
                        'qahrtotal'=>(float)$data[$start_row][36],
                        'uploadedby'=>auth()->user()->id,
                        'uploadeddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);

      }

      

      

      


      //created 09/20/2021
      public static function download_homeroom($request){

            $schoolinfo = DB::table('schoolinfo')->first();
            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
           
            $levelinfo = DB::table('gradelevel')
                              ->where('id',$levelid)
                              ->first();
                                  
            $section = DB::table('sections')
                              ->where('id',$sectionid)
                              ->first();

            $sy = DB::table('sy')
                        ->where('id',$syid)
                        ->first();

            $subjinfo = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null,$subjid,$levelid,null,$syid);

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load("ECR/HOMEROOM_SPCT/HOMEROOM_ECR.xlsx");


            if($levelinfo->acadprogid != 5){

                  $student = DB::table('enrolledstud')
                              ->where('enrolledstud.deleted',0)
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.sectionid',$sectionid)
                              ->whereIn('enrolledstud.studstatus',[1,2,4])
                              ->join('studinfo',function($join){
                                    $join->on('studinfo.id','=','enrolledstud.studid');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->orderBy('gender','desc')
                              ->orderBy('lastname')
                              ->select(
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'enrolledstud.levelid',
                                    'enrolledstud.sectionid',
                                    'dob',
                                    'gender',
                                    'lrn',
                                    'sid',
                                    'studinfo.id'
                              )
                              ->get();

                  $check_subject = DB::table('subjects')
                              ->where('deleted',0)
                              ->where('id',$subjid)
                              ->first();

                  if( isset($check_subject->isSP)){
                        if($check_subject->isSP == 1){
                              $student = DB::table('enrolledstud')
                                                ->join('studinfo',function($join){
                                                      $join->on('studinfo.id','=','enrolledstud.studid');
                                                      $join->where('studinfo.deleted',0);
                                                })
                                                ->join('subjects_studspec',function($join) use($subjid,$syid){
                                                      $join->on('enrolledstud.studid','=','subjects_studspec.studid');
                                                      $join->where('subjects_studspec.deleted',0);
                                                      $join->where('subjects_studspec.syid',$syid);
                                                      $join->where('subjects_studspec.subjid',$subjid);
                                                })
                                                ->where('enrolledstud.deleted',0)
                                                ->where('enrolledstud.syid',$syid)
                                                ->where('enrolledstud.sectionid',$sectionid)
                                                ->whereIn('enrolledstud.studstatus',[1,2,4])
                                                ->orderBy('gender','desc')
                                                ->orderBy('lastname')
                                                ->select(
                                                      'lastname',
                                                      'firstname',
                                                      'middlename',
                                                      'suffix',
                                                      'enrolledstud.levelid',
                                                      'enrolledstud.sectionid',
                                                      'dob',
                                                      'gender',
                                                      'lrn',
                                                      'sid',
                                                      'studinfo.id'
                                                )
                                                ->get();
                        }
                  }

            }else{

                  $strand = array();
                  $subjstrand = DB::table('subject_plot')
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('levelid',$levelid)
                                    ->where('subjid',$subjid)
                                    ->where('deleted',0)
                                    ->get();

                  foreach($subjstrand as $stranditem){
                        array_push($strand, $stranditem->strandid);
                  }
            
                  $student = DB::table('sh_enrolledstud')
                        ->where('sh_enrolledstud.deleted',0)
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.sectionid',$sectionid)
                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                        ->whereIn('sh_enrolledstud.strandid',$strand)
                        ->join('studinfo',function($join){
                              $join->on('studinfo.id','=','sh_enrolledstud.studid');
                              $join->where('studinfo.deleted',0);
                        })
                        ->orderBy('gender','desc')
                        ->orderBy('lastname')
                        ->select(
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'sh_enrolledstud.levelid',
                              'sh_enrolledstud.sectionid',
                              'dob',
                              'gender',
                              'lrn',
                              'sid',
                              'studinfo.id'
                        )
                        ->distinct('studid')
                        ->get();
            }
            
            foreach($student as $stud_item){
                  $temp_middle = '';
                  if(isset($stud_item->middlename)){
                        $middlename = explode(" ",$stud_item->middlename);
                        if(isset($stud_item->middlename)){
                              if($stud_item->middlename != '' && $stud_item->middlename != null){
                                    $temp_middle = $stud_item->middlename[0].'.';
                              }
                        }
                  }
                  $stud_item->middlename = $temp_middle;
                  $stud_item->student = $stud_item->lastname.', '.$stud_item->firstname.' '.$stud_item->suffix.' '.$temp_middle;
            }

            for($x = 0; $x < 4; $x ++){

                  $sheet = $spreadsheet->setActiveSheetIndex($x);
                  $row_count = 1;
                  $count = 10;
                  $male = 0;
                  $female = 0;

                  $sheet->setCellValue('G8','Written Output '.$subjinfo[0]->ww.'%');
                  $sheet->setCellValue('T9',$subjinfo[0]->ww / 100);
                  $sheet->getStyle('T9')
                        ->getNumberFormat()
                        ->setFormatCode('0%;[Red]-0%');

                  $sheet->setCellValue('V8','Involvement '.$subjinfo[0]->pt.'%');
                  $sheet->setCellValue('AI9',$subjinfo[0]->pt / 100);
                  $sheet->getStyle('AI9')
                        ->getNumberFormat()
                        ->setFormatCode('0%;[Red]-0%');

                  $sheet->setCellValue('AK8','Attendance '.$subjinfo[0]->qa.'%');
                  $sheet->setCellValue('AX9',$subjinfo[0]->qa / 100);
                        $sheet->getStyle('AX9')
                        ->getNumberFormat()
                        ->setFormatCode('0%;[Red]-0%');

                  $sheet->setCellValue('A8',$levelinfo->levelname.' - '.$section->sectionname);



                  $headerid = null;
                  $quarter = $x + 1;

                  $check_header = DB::table('grades')
                                    ->where('syid',$syid)
                                    ->where('levelid',$levelid)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjid)
                                    ->where('quarter',$quarter)
                                    ->where('deleted',0)
                                    ->get();

                  if(count($check_header) == 0){
                        
                        $headerid =  DB::table('grades')
                                          ->insertGetId([
                                                'syid' => $syid,
                                                'levelid' => $levelid,
                                                'sectionid' => $sectionid,
                                                'subjid' => $subjid,
                                                'quarter' => $quarter,
                                                'deleted' => 0,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);
                                    
                  }else{

                        $headerid = $check_header[0]->id;

                        $sheet->setCellValue('G'.$count,$check_header[0]->wwhr1 == 0 ? "": $check_header[0]->wwhr1);
                        $sheet->setCellValue('H'.$count,$check_header[0]->wwhr2 == 0 ? "": $check_header[0]->wwhr2);
                        $sheet->setCellValue('I'.$count,$check_header[0]->wwhr3 == 0 ? "": $check_header[0]->wwhr3);
                        $sheet->setCellValue('J'.$count,$check_header[0]->wwhr4 == 0 ? "": $check_header[0]->wwhr4);
                        $sheet->setCellValue('K'.$count,$check_header[0]->wwhr5 == 0 ? "": $check_header[0]->wwhr5);
                        $sheet->setCellValue('L'.$count,$check_header[0]->wwhr6 == 0 ? "": $check_header[0]->wwhr6);
                        $sheet->setCellValue('M'.$count,$check_header[0]->wwhr7 == 0 ? "": $check_header[0]->wwhr7);
                        $sheet->setCellValue('N'.$count,$check_header[0]->wwhr8 == 0 ? "": $check_header[0]->wwhr8);
                        $sheet->setCellValue('O'.$count,$check_header[0]->wwhr9 == 0 ? "": $check_header[0]->wwhr9);
                        $sheet->setCellValue('P'.$count,$check_header[0]->wwhr0 == 0 ? "": $check_header[0]->wwhr0);

                        $sheet->setCellValue('V'.$count,$check_header[0]->pthr1 == 0 ? "": $check_header[0]->pthr1);
                        $sheet->setCellValue('W'.$count,$check_header[0]->pthr2 == 0 ? "": $check_header[0]->pthr2);
                        $sheet->setCellValue('X'.$count,$check_header[0]->pthr3 == 0 ? "": $check_header[0]->pthr3);
                        $sheet->setCellValue('Y'.$count,$check_header[0]->pthr4 == 0 ? "": $check_header[0]->pthr4);
                        $sheet->setCellValue('Z'.$count,$check_header[0]->pthr5 == 0 ? "": $check_header[0]->pthr5);
                        $sheet->setCellValue('AA'.$count,$check_header[0]->pthr6 == 0 ? "": $check_header[0]->pthr6);
                        $sheet->setCellValue('AB'.$count,$check_header[0]->pthr7 == 0 ? "": $check_header[0]->pthr7);
                        $sheet->setCellValue('AC'.$count,$check_header[0]->pthr8 == 0 ? "": $check_header[0]->pthr8);
                        $sheet->setCellValue('AD'.$count,$check_header[0]->pthr9 == 0 ? "": $check_header[0]->pthr9);
                        $sheet->setCellValue('AE'.$count,$check_header[0]->pthr0 == 0 ? "": $check_header[0]->pthr0);

                        $sheet->setCellValue('AK'.$count,$check_header[0]->pthr1 == 0 ? "": $check_header[0]->pthr1);
                        $sheet->setCellValue('AL'.$count,$check_header[0]->pthr2 == 0 ? "": $check_header[0]->pthr2);
                        $sheet->setCellValue('AM'.$count,$check_header[0]->pthr3 == 0 ? "": $check_header[0]->pthr3);
                        $sheet->setCellValue('AN'.$count,$check_header[0]->pthr4 == 0 ? "": $check_header[0]->pthr4);
                        $sheet->setCellValue('AO'.$count,$check_header[0]->pthr5 == 0 ? "": $check_header[0]->pthr5);
                        $sheet->setCellValue('AP'.$count,$check_header[0]->pthr6 == 0 ? "": $check_header[0]->pthr6);
                        $sheet->setCellValue('AQ'.$count,$check_header[0]->pthr7 == 0 ? "": $check_header[0]->pthr7);
                        $sheet->setCellValue('AR'.$count,$check_header[0]->pthr8 == 0 ? "": $check_header[0]->pthr8);
                        $sheet->setCellValue('AS'.$count,$check_header[0]->pthr9 == 0 ? "": $check_header[0]->pthr9);
                        $sheet->setCellValue('AT'.$count,$check_header[0]->pthr0 == 0 ? "": $check_header[0]->pthr0);

                  }

                  $sheet->setCellValue('BZ'.$count, $headerid);

                  $count = 11;

                  foreach($student as $item){

                        $range = 'A'.$count.':'.'BC'.$count;
      
                        if($male == 0 && strtoupper($item->gender) == 'MALE'){
                              $sheet->setCellValue('C'.$count,'MALE');
                              $sheet->setCellValue('P'.$count,"");
                              $sheet->setCellValue('Q'.$count,"");
                              $sheet->setCellValue('R'.$count,"");
                              $sheet->setCellValue('AC'.$count,"");
                              $sheet->setCellValue('AD'.$count,"");
                              $sheet->setCellValue('AE'.$count,"");
                              $sheet->setCellValue('AI'.$count,"");
                              $sheet->setCellValue('AJ'.$count,"");
                              $sheet->getStyle($range)->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('bfbfbf');
                              $male = 1;
                              $count +=1;
                        }elseif($female == 0  && strtoupper($item->gender) == 'FEMALE'){
                              $sheet->setCellValue('C'.$count,'FEMALE');
                              $sheet->setCellValue('P'.$count,"");
                              $sheet->setCellValue('Q'.$count,"");
                              $sheet->setCellValue('R'.$count,"");
                              $sheet->setCellValue('AC'.$count,"");
                              $sheet->setCellValue('AD'.$count,"");
                              $sheet->setCellValue('AE'.$count,"");
                              $sheet->setCellValue('AI'.$count,"");
                              $sheet->setCellValue('AJ'.$count,"");
                              $sheet->getStyle($range)->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('bfbfbf');
                              $female = 1;
                              $count +=1;
                              $row_count = 1;
                        }

                        $check_detail = DB::table('gradesdetail')
                                                ->where('studid',$item->id)
                                                ->where('headerid',$headerid)
                                                ->first();

                        if(isset($check_detail->id)){

                              $sheet->setCellValue('G'.$count,$check_detail->ww1 == 0 ? "" : $check_detail->ww1);
                              $sheet->setCellValue('H'.$count,$check_detail->ww2 == 0 ? "" : $check_detail->ww2);
                              $sheet->setCellValue('I'.$count,$check_detail->ww3 == 0 ? "" : $check_detail->ww3);
                              $sheet->setCellValue('J'.$count,$check_detail->ww4 == 0 ? "" : $check_detail->ww4);
                              $sheet->setCellValue('K'.$count,$check_detail->ww5 == 0 ? "" : $check_detail->ww5);
                              $sheet->setCellValue('L'.$count,$check_detail->ww6 == 0 ? "" : $check_detail->ww6);
                              $sheet->setCellValue('M'.$count,$check_detail->ww7 == 0 ? "" : $check_detail->ww7);
                              $sheet->setCellValue('N'.$count,$check_detail->ww8 == 0 ? "" : $check_detail->ww8);
                              $sheet->setCellValue('O'.$count,$check_detail->ww9 == 0 ? "" : $check_detail->ww9);
                              $sheet->setCellValue('P'.$count,$check_detail->ww0 == 0 ? "" : $check_detail->ww0);
                              $sheet->setCellValue('R'.$count,$check_detail->wwhps == 0 ? "" : $check_detail->wwhps);

                              $sheet->setCellValue('V'.$count,$check_detail->pt1 == 0 ? "" : $check_detail->pt1);
                              $sheet->setCellValue('W'.$count,$check_detail->pt2 == 0 ? "" : $check_detail->pt2);
                              $sheet->setCellValue('X'.$count,$check_detail->pt3 == 0 ? "" : $check_detail->pt3);
                              $sheet->setCellValue('Y'.$count,$check_detail->pt4 == 0 ? "" : $check_detail->pt4);
                              $sheet->setCellValue('Z'.$count,$check_detail->pt5 == 0 ? "" : $check_detail->pt5);
                              $sheet->setCellValue('AA'.$count,$check_detail->pt6 == 0 ? "" : $check_detail->pt6);
                              $sheet->setCellValue('AB'.$count,$check_detail->pt7 == 0 ? "" : $check_detail->pt7);
                              $sheet->setCellValue('AC'.$count,$check_detail->pt8 == 0 ? "" : $check_detail->pt8);
                              $sheet->setCellValue('AD'.$count,$check_detail->pt9 == 0 ? "" : $check_detail->pt9);
                              $sheet->setCellValue('AE'.$count,$check_detail->pt0 == 0 ? "" : $check_detail->pt0);
                              $sheet->setCellValue('AG'.$count,$check_detail->pthps == 0 ? "" : $check_detail->pthps);

                              $sheet->setCellValue('AK'.$count,$check_detail->qa1 == 0 ? "" : $check_detail->qa1);
                              $sheet->setCellValue('AL'.$count,$check_detail->qa2 == 0 ? "" : $check_detail->qa2);
                              $sheet->setCellValue('AM'.$count,$check_detail->qa3 == 0 ? "" : $check_detail->qa3);
                              $sheet->setCellValue('AN'.$count,$check_detail->qa4 == 0 ? "" : $check_detail->qa4);
                              $sheet->setCellValue('AO'.$count,$check_detail->qa5 == 0 ? "" : $check_detail->qa5);
                              $sheet->setCellValue('AP'.$count,$check_detail->qa6 == 0 ? "" : $check_detail->qa6);
                              $sheet->setCellValue('AQ'.$count,$check_detail->qa7 == 0 ? "" : $check_detail->qa7);
                              $sheet->setCellValue('AE'.$count,$check_detail->qa8 == 0 ? "" : $check_detail->pt8);
                              $sheet->setCellValue('AS'.$count,$check_detail->qa9 == 0 ? "" : $check_detail->qa9);
                              $sheet->setCellValue('AT'.$count,$check_detail->qa0 == 0 ? "" : $check_detail->qa0);
                              $sheet->setCellValue('AV'.$count,$check_detail->qahps == 0 ? "" : $check_detail->qahps);
                        }

                        $sheet->setCellValue('C'.$count,$item->student);
                        $sheet->setCellValue('A'.$count,$row_count);
                        $sheet->setCellValue('BZ'.$count, $item->id);
                        $count +=1;
                  }

                  $sheet->getPageSetup()->setPrintArea('A1:BC'.($count-1));

            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="ECR '.$section->sectionname.' - '.$subjinfo[0]->subjdesc.' - '.$sy->sydesc.'.xlsx"');
            $writer->save("php://output");
            exit();
      }

      
      

      public static function schedule(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $teacherid = $request->get('teacherid');

            if(auth()->user()->id != 17){
                  $temp_teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->first();
                  if(isset($temp_teacherid)){
                        if($teacherid != $temp_teacherid->id){
                              return array((object)[
                                    'status'=>0,
                                    'message'=>'No results found.'
                              ]);
                        }
                  }
            }

            $subject = array();

            if($teacherid == ""){
                  return array((object)[
                        'status'=>0,
                        'message'=>'No teacher selected'
                  ]);
            }

            $sched = DB::table('sh_classsched')
                        ->where('sh_classsched.syid',$syid)
                        ->where('sh_classsched.semid',$semid)
                        ->where('sh_classsched.deleted',0)
                        ->where('sh_classsched.teacherid',$teacherid)
                        ->join('sh_subjects',function($join) use($teacherid){
                              $join->on('sh_classsched.subjid','=','sh_subjects.id');
                              $join->where('sh_subjects.deleted',0);
                        })
                        ->join('gradelevel',function($join) use($teacherid){
                              $join->on('sh_classsched.glevelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                        })
                        ->join('sections',function($join) use($teacherid){
                              $join->on('sh_classsched.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->select(
                              'subjid',
                              'glevelid as levelid',
                              'sectionid',
                              'subjtitle as subjdesc',
                              'subjcode',
                              'type',
                              'sectionname',
                              'levelname',
                              'gradelevel.acadprogid'
                        )
                        ->get();

            foreach($sched as $item){
                  
                  $check_if_exist_in_plot = DB::table('subject_plot')
                                                ->where('deleted',0)
                                                ->where('levelid',$item->levelid)
                                                ->where('subjid',$item->subjid)
                                                ->where('syid',$syid)
                                                ->count();

                  if($check_if_exist_in_plot > 0){
                        array_push($subject, $item);
                  }      

            }

            $asssubj = DB::table('assignsubj')
                        ->where('assignsubj.syid',$syid)
                        ->where('assignsubj.deleted',0)
                        ->join('assignsubjdetail',function($join) use($teacherid){
                            $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                            $join->where('assignsubjdetail.deleted',0);
                            $join->where('assignsubjdetail.teacherid',$teacherid);
                        })
                        ->join('subjects',function($join){
                              $join->on('assignsubjdetail.subjid','=','subjects.id');
                              $join->where('subjects.deleted',0);
                        })
                        ->join('gradelevel',function($join) use($teacherid){
                              $join->on('assignsubj.glevelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                        })
                        ->join('sections',function($join) use($teacherid){
                              $join->on('assignsubj.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->leftJoin('teacher',function($join){
                            $join->on('assignsubjdetail.teacherid','=','teacher.id');
                            $join->where('teacher.deleted',0);
                        })
                        ->select(
                          'subjid',
                          'glevelid as levelid',
                          'sectionid',
                          'subjdesc',
                          'subjcode',
                          'isCon',
                          'isSP',
                          'subjCom',
                          'sectionname',
                          'levelname',
                          'gradelevel.acadprogid'
                        )
                        ->get();

            foreach($asssubj as $item){

                  $check_if_exist_in_plot = DB::table('subject_plot')
                                                ->where('deleted',0)
                                                ->where('levelid',$item->levelid)
                                                ->where('subjid',$item->subjid)
                                                ->where('syid',$syid)
                                                ->count();

                  if($check_if_exist_in_plot > 0){
                        array_push($subject, $item);
                  }                    
                  
            }
            
            
            $temp_id = 1;
       
           
            
            foreach($subject as $item){

                  $item->temp_id = $temp_id;
                  $temp_id += 1;
                  $search = '';
                  $sectionid = $item->sectionid;
                  $stdudents = 0;

                  $strand = array();

                  if($item->acadprogid == 5){

                        $subjstrand = DB::table('subject_plot')
                                          ->where('subject_plot.subjid',$item->subjid)
                                          ->where('subject_plot.levelid',$item->levelid)
                                          ->where('subject_plot.syid',$syid)
                                          ->where('subject_plot.semid',$semid)
                                          ->where('subject_plot.deleted',0)
                                          ->join('sh_strand',function($join){
                                                $join->on('subject_plot.strandid','=','sh_strand.id');
                                                $join->where('sh_strand.deleted',0);
                                          })
                                          ->select('strandid','strandcode')
                                          ->get();


                        // $item->strand = $subjstrand;
                        $student_count = 0;

                        foreach($subjstrand as $stranditem){
                              
                              $students = DB::table('sh_enrolledstud')                                    
                                          ->where('sh_enrolledstud.syid',$syid)
                                          ->where('sh_enrolledstud.semid',$semid)
                                          ->where('sh_enrolledstud.sectionid',$sectionid)
                                          ->where('sh_enrolledstud.deleted',0)
                                          ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                          ->distinct('studid')
                                          ->where('sh_enrolledstud.strandid',$stranditem->strandid)
                                          ->count();

                              if($students != 0){
                                    $student_count +=  $students;
                                    array_push($strand, $stranditem);
                              }

                        }

                        $students = DB::table('student_specsubj')
                                    ->join('sh_enrolledstud',function($join) use($syid,$semid){
                                          $join->on('student_specsubj.studid','=','sh_enrolledstud.studid');
                                          $join->whereIn('sh_enrolledstud.studstatus',[1,2,4]);
                                          $join->where('sh_enrolledstud.deleted',0);
                                          $join->where('sh_enrolledstud.syid',$syid);
                                          $join->where('sh_enrolledstud.semid',$semid);
                                    })
                                    ->where('student_specsubj.status','ADDITIONAL')
                                    ->where('student_specsubj.syid',$syid)
                                    ->where('student_specsubj.semid',$semid)
                                    ->where('student_specsubj.deleted',0)
                                    ->where('student_specsubj.sectionid',$sectionid)
                                    ->where('student_specsubj.subjid',$item->subjid)
                                    ->count();

                        $student_count += $students;

                     
                        $item->strand = $strand;
                        $item->enrolled = $student_count;
                        
                        $sched = DB::table('sh_classsched')
                                    ->where('sh_classsched.syid',$syid)
                                    ->where('sh_classsched.semid',$semid)
                                    ->where('sh_classsched.subjid',$item->subjid)
                                    ->where('sh_classsched.sectionid',$sectionid)
                                    ->where('sh_classsched.deleted',0)
                                    ->join('sh_classscheddetail',function($join){
                                          $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                                          $join->where('sh_classscheddetail.deleted',0);
                                    })
                                    ->leftJoin('rooms',function($join){
                                          $join->on('sh_classscheddetail.roomid','=','rooms.id');
                                          $join->where('rooms.deleted',0);
                                    })
                                    ->join('days',function($join){
                                          $join->on('sh_classscheddetail.day','=','days.id');
                                    })
                                    ->join('schedclassification',function($join){
                                          $join->on('sh_classscheddetail.classification','=','schedclassification.id');
                                    })
                                    ->select(
                                          'day',
                                          'roomid',
                                          'sh_classscheddetail.id as detailid',
                                          'sh_classsched.id',
                                          'roomname',
                                          'stime',
                                          'etime',
                                          'days.description',
                                          'teacherid',
                                          'schedclassification.description as classification'
                                    )
                                    ->get();
      
                              if(count($sched) == 0){
      
                              $sched = DB::table('sh_blocksched')
                                    ->where('sh_blocksched.syid',$syid)
                                    ->where('sh_blocksched.subjid',$item->subjid)
                                    
                                    ->where('sh_blocksched.deleted',0)
                                    ->join('sh_blockscheddetail',function($join){
                                          $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                                          $join->where('sh_blockscheddetail.deleted',0);
                                    })
                                    ->leftJoin('rooms',function($join){
                                          $join->on('sh_blockscheddetail.roomid','=','rooms.id');
                                          $join->where('rooms.deleted',0);
                                    })
                                    ->join('days',function($join){
                                          $join->on('sh_blockscheddetail.day','=','days.id');
                                    })
                                    ->join('schedclassification',function($join){
                                          $join->on('sh_blockscheddetail.classification','=','schedclassification.id');
                                    })
                                    ->select(
                                          'day',
                                          'sh_blockscheddetail.id as detailid',
                                          'roomid',
                                          'sh_blocksched.id',
                                          'roomname',
                                          'stime',
                                          'etime',
                                          'teacherid',
                                          'days.description',
                                          'schedclassification.description as classification'
                                    )
                                    ->get();
      
                              }
      
                        $teacher = null;
                        $tid = null;
                        $teacherid = null;
      
                        if(isset($sched[0]->teacherid)){
                        
                              $temp_teacher = DB::table('teacher')
                                                ->where('id',$sched[0]->teacherid)
                                                ->first();
      
                              $teacher = $temp_teacher->firstname.' '.$temp_teacher->middlename.' '.$temp_teacher->lastname;
                              $tid = $temp_teacher->tid;
                              $teacherid = $temp_teacher->id;
                        }
      
      
                        foreach($sched as $sched_item){
                              $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                        }
      
                        
                        $starting = collect($sched)->groupBy('time');
      
                        $sched_list = array();
                        $sched_count = 1;
      
                        foreach($starting as $sched_item){
                              
                              $dayString = '';
                              $days = array();
      
                              foreach($sched_item as $new_item){
                                    $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                                    $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                                    $dayString.= substr($new_item->description, 0,3).' / ';
                                    $detailid = $new_item->detailid;
                                    $roomname = $new_item->roomname;
                                    $roomid = $new_item->roomid;
                                    $classification = $new_item->classification;
                                    $time = $new_item->time;
                                    array_push($days,$new_item->day);
                              }
      
                              $dayString = substr($dayString, 0 , -2);
                              
                              array_push($sched_list,(object)[
                                    'day'=>$dayString,
                                    'start'=>$start,
                                    'end'=>$end,
                                    'roomid',
                                    'detailid'=>$detailid,
                                    'roomname'=>$roomname,
                                    'roomid'=>$roomid,
                                    'classification'=>$classification,
                                    'teacher'=>$teacher,
                                    'tid'=>$tid,
                                    'teacherid'=>$teacherid,
                                    'sched_count'=>$sched_count,
                                    'time'=>$time,
                                    'days'=>$days
                              ]);
      
      
                              $sched_count += 1;
      
                        }
                        $item->datatype = 'seniorhigh';
                        $item->schedule = $sched_list;
      
                  }else{  

                        $students = 0;
      
                        $count = DB::table('enrolledstud')
                                    ->where('enrolledstud.syid',$syid)
                                    ->where('enrolledstud.sectionid',$sectionid)
                                    ->where('enrolledstud.deleted',0)
                                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                                    ->count();

                        $students += $count;

                        //check subject if specialized
                        $check_subject = DB::table('subjects')
                                    ->where('deleted',0)
                                    ->where('id',$item->subjid)
                                    ->first();
                                    
                        if( isset($check_subject->isSP)){
                              if($check_subject->isSP == 1){
                                    $temp_subjid = $item->subjid;

                                    $students = 0;

                                    $count = DB::table('enrolledstud')
                                                      ->join('subjects_studspec',function($join) use($temp_subjid,$syid){
                                                            $join->on('enrolledstud.studid','=','subjects_studspec.studid');
                                                            $join->where('subjects_studspec.deleted',0);
                                                            $join->where('subjects_studspec.syid',$syid);
                                                            $join->where('subjects_studspec.subjid',$temp_subjid);
                                                      })
                                                      ->where('enrolledstud.syid',$syid)
                                                      ->where('enrolledstud.sectionid',$sectionid)
                                                      ->where('enrolledstud.deleted',0)
                                                      ->whereIn('enrolledstud.studstatus',[1,2,4])
                                                      ->count();

                                    $students += $count;
                                  
                              }
                        }

                        $count = DB::table('student_specsubj')
                                          ->join('enrolledstud',function($join) use($syid){
                                          $join->on('student_specsubj.studid','=','enrolledstud.studid');
                                          $join->whereIn('enrolledstud.studstatus',[1,2,4]);
                                          $join->where('enrolledstud.deleted',0);
                                          $join->where('enrolledstud.syid',$syid);
                                          })
                                          ->where('student_specsubj.status','ADDITIONAL')
                                          ->where('student_specsubj.syid',$syid)
                                          ->where('student_specsubj.deleted',0)
                                          ->where('student_specsubj.sectionid',$sectionid)
                                          ->where('student_specsubj.subjid',$item->subjid)
                                          ->count();

                        $students += $count;

                        $item->enrolled = $students;

      
                        $sched = DB::table('classsched')
                                    ->where('classsched.syid',$syid)
                                    ->where('classsched.subjid',$item->subjid)
                                    ->where('classsched.sectionid',$sectionid)
                                    ->where('classsched.deleted',0)
                                    ->leftJoin('classscheddetail',function($join){
                                          $join->on('classsched.id','=','classscheddetail.headerid');
                                          $join->where('classscheddetail.deleted',0);
                                    })
                                    ->leftJoin('rooms',function($join){
                                          $join->on('classscheddetail.roomid','=','rooms.id');
                                          $join->where('rooms.deleted',0);
                                    })
                                    ->leftJoin('days',function($join){
                                          $join->on('classscheddetail.days','=','days.id');
                                    })
                                    ->leftJoin('schedclassification',function($join){
                                          $join->on('classscheddetail.classification','=','schedclassification.id');
                                    })
                                    ->select(
                                          'roomid',
                                          'classsched.id',
                                          'roomname',
                                          'stime',
                                          'etime',
                                          'days.description',
                                          'classscheddetail.id as detailid',
                                          'schedclassification.description as classification',
                                          'roomid',
                                          'days'
                                    )
                                    ->get();
      
                        $temp_subj = $item->subjid;
      
                        $asssubj = DB::table('assignsubj')
                                    ->where('assignsubj.syid',$syid)
                                    ->where('assignsubj.sectionid',$sectionid)
                                    ->where('assignsubj.deleted',0)
                                    ->join('assignsubjdetail',function($join) use($temp_subj){
                                          $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                                          $join->where('assignsubjdetail.deleted',0);
                                          $join->where('assignsubjdetail.subjid',$temp_subj);
                                    })
                                    ->leftJoin('teacher',function($join){
                                          $join->on('assignsubjdetail.teacherid','=','teacher.id');
                                          $join->where('teacher.deleted',0);
                                    })
                                    ->select(
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'title',
                                          'teacherid',
                                          'tid'
                                    )
                                    ->first();
      
                        $temp_teacher = null;
      
                        if(!isset($asssubj->tid)){
                              $asssubj = (object)[
                                          'lastname'=>null,
                                          'firstname'=>null,
                                          'middlename'=>null,
                                          'suffix'=>null,
                                          'title'=>null,
                                          'teacherid'=>null,
                                          'tid'=>null
                              ];
                              
                        }
      
                        foreach($sched as $sched_item){
                              $sched_item->time = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                        }
      
                        $starting = collect($sched)->groupBy('time');
                        $sched_list = array();
                        $sched_count = 1;
      
                      

                        foreach($starting as $sched_item){
      
                              $dayString = '';
                              $days = array();
                        
                              foreach($sched_item as $new_item){
                                    $start = \Carbon\Carbon::createFromTimeString($new_item->stime)->isoFormat('hh:mm A');
                                    $end = \Carbon\Carbon::createFromTimeString($new_item->etime)->isoFormat('hh:mm A');
                                    $dayString.= substr($new_item->description, 0,3).' / ';
                                    $detailid = $new_item->detailid;
                                    $roomname = $new_item->roomname;
                                    $roomid = $new_item->roomid;
                                    $classification = $new_item->classification;
                                    $time = $new_item->time;
                                    array_push($days,$new_item->days);
                              }
      
                              $dayString = substr($dayString, 0 , -2);
                              
                              array_push($sched_list,(object)[
                                    'day'=>$dayString,
                                    'start'=>$start,
                                    'end'=>$end,
                                    'roomid',
                                    'detailid'=>$detailid,
                                    'roomname'=>$roomname,
                                    'roomid'=>$roomid,
                                    'classification'=>$classification,
                                    'teacher'=> $asssubj->firstname.' '.$asssubj->middlename.' '.$asssubj->lastname,
                                    'tid'=>$asssubj->tid,
                                    'teacherid'=>$asssubj->teacherid,
                                    'sched_count'=>$sched_count,
                                    'time'=>$time,
                                    'days'=>$days
                              ]);
                              $search .= $dayString.' ';
                              $sched_count += 1;
      
                        }
                        $item->datatype = 'juniorhigh';
                        $item->schedule = $sched_list;
      
                  }

                   // 11132021 - grades
                  $item->with_pending = false;
                 
                  $grades = DB::table('grades')
                                    ->where('levelid',$item->levelid)
                                    ->where('sectionid',$item->sectionid)
                                    ->where('syid',$syid)
                                    ->where('subjid',$item->subjid)
                                    ->where('deleted',0)
                                    ->where('status',3)
                                    ->count();

                  if($grades > 0){
                        $item->with_pending = true;
                  }

                  $item->search = $search.$item->sectionname.' '.$item->levelname.' '.$item->subjcode.' '.$item->subjdesc;
      
            }

            $subject = collect($subject)->sortBy('sectionname')->values();

            return $subject;
      
            return view('superadmin.pages.teacher.schedplot')->with('schedule',$subject);

      }

      public static function teacher_list(){

            $teachers = DB::table('teacher')
                        ->where('deleted',0)
                        ->get();
                        
            foreach($teachers as $item){
                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                              if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                              } 
                        }
                  }
                  $adviser = $item->title.' '.$item->firstname.' '.$temp_middle.' '.$item->lastname.' '.$item->suffix;
                  $item->fullname = $item->tid.' - '.$adviser;
                  $item->text = $item->fullname;
            }

            return $teachers;

      }

      public static function view_ecr(Request $request){

            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $quarter = $request->get('quarter');
            $schoolinfo = DB::table('schoolinfo')->first();

            $subjinfo = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null,$subjid,$levelid,null,$syid);

            $check_header = DB::table('grades')
                  ->where('syid',$syid)
                  ->where('levelid',$levelid)
                  ->where('sectionid',$sectionid)
                  ->where('subjid',$subjid)
                  ->where('quarter',$quarter)
                  ->where('deleted',0)
                  ->get();

            if(count($check_header) == 0){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Does not contain any detail.'
                  ]);
            }else if(count($check_header) > 1){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Contains multiple detail.'
                  ]);
            }



            $check_header[0]->uploadeddatetime = $check_header[0]->uploadeddatetime == null ? "No file uploaded" : \Carbon\Carbon::create($check_header[0]->uploadeddatetime)->isoFormat('MMMM DD, YYYY hh:mm a');

            $check_header[0]->date_submitted = $check_header[0]->date_submitted == null ? "No file uploaded" : \Carbon\Carbon::create($check_header[0]->date_submitted)->isoFormat('MMMM DD, YYYY hh:mm a');

            $gradesetup = array((object)[
                  'ww'=>0,
                  'pt'=>0,
                  'qa'=>0,
                  'uploadeddatetime'
            ]);

            $percentage = DB::table('subject_plot')
                              ->join('subject_gradessetup',function($join){
                                    $join->on('subject_plot.gradessetup','=','subject_gradessetup.id');
                                    $join->where('subject_gradessetup.deleted',0);
                              })
                              ->where('subject_plot.syid',$syid)
                              ->where('subject_plot.levelid',$levelid)
                              ->where('subject_plot.subjid',$subjid)
                              ->where('subject_plot.deleted',0)
                              ->first();

            if(isset($percentage->ww)){

                  $gradesetup = array((object)[
                        'ww'=>$percentage->ww,
                        'pt'=>$percentage->pt,
                        'qa'=>$percentage->qa,
                  ]);
            }

            if($levelid != 14 && $levelid != 15){

                  $temp_students = array();

                  $enrolledstud = DB::table('enrolledstud')
                                    ->join('studinfo',function($join){
                                          $join->on('enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->where('enrolledstud.sectionid',$sectionid)
                                    ->where('enrolledstud.levelid',$levelid)
                                    ->where('enrolledstud.syid',$syid)
                                    ->where('enrolledstud.deleted',0)
                                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                                    ->distinct('studid')
                                    ->select(
                                          'studid',
                                          'firstname',
                                          'lastname',
                                          'middlename',
                                          'suffix',
                                          'mol',
                                          'gender',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                    )
                                    ->orderBy('gender','desc')
                                    ->orderBy('studentname','asc')
                                    ->get();

                  foreach($enrolledstud as $item){
                        array_push($temp_students,$item);
                  }

                  $check_subject = DB::table('subjects')
                                    ->where('deleted',0)
                                    ->where('id',$subjid)
                                    ->first();

                  if( isset($check_subject->isSP)){

                        if($check_subject->isSP == 1){

                              $temp_students = array();

                              $enrolledstud = DB::table('enrolledstud')
                                    ->join('studinfo',function($join){
                                          $join->on('enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('subjects_studspec',function($join) use($subjid,$syid,$quarter){
                                          $join->on('enrolledstud.studid','=','subjects_studspec.studid');
                                          $join->where('subjects_studspec.deleted',0);
                                          $join->where('subjects_studspec.syid',$syid);
                                          $join->where('subjects_studspec.subjid',$subjid);
                                          if($quarter == 1){
                                                $join->where('subjects_studspec.q1',1);
                                          }else if($quarter == 2){
                                                $join->where('subjects_studspec.q2',1);
                                          }else if($quarter == 3){
                                                $join->where('subjects_studspec.q3',1);
                                          }else if($quarter == 4){
                                                $join->where('subjects_studspec.q4',1);
                                          }
                                    })
                                    ->where('enrolledstud.sectionid',$sectionid)
                                    ->where('enrolledstud.levelid',$levelid)
                                    ->where('enrolledstud.syid',$syid)
                                    ->where('enrolledstud.deleted',0)
                                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                                    ->distinct('enrolledstud.studid')
                                    ->select(
                                          'enrolledstud.studid',
                                          'firstname',
                                          'lastname',
                                          'middlename',
                                          'suffix',
                                          'mol',
                                          'gender',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                                    )
                                    ->orderBy('gender','desc')
                                    ->orderBy('studentname','asc')
                                    ->get();

                              foreach($enrolledstud as $item){
                                    array_push($temp_students,$item);
                              }

                        }


                  }

                  $enrolledstud = DB::table('student_specsubj')
                            ->join('studinfo',function($join){
                                $join->on('student_specsubj.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->join('enrolledstud',function($join) use($syid){
                                $join->on('student_specsubj.studid','=','enrolledstud.studid');
                                $join->whereIn('enrolledstud.studstatus',[1,2,4]);
                                $join->where('enrolledstud.deleted',0);
                                $join->where('enrolledstud.syid',$syid);
                            })
                            ->where('student_specsubj.status','ADDITIONAL')
                            ->where('student_specsubj.syid',$syid)
                            ->where('student_specsubj.deleted',0)
                            ->where('student_specsubj.sectionid',$sectionid)
                            ->where('student_specsubj.subjid',$subjid)
                            ->select(
                                'enrolledstud.studid',
                                'firstname',
                                'lastname',
                                'middlename',
                                'suffix',
                                'mol',
                                'gender',
                                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                            )
                            ->get();

                    foreach($enrolledstud as $item){
                        array_push($temp_students,$item);
                    }

                    $enrolledstud = collect($temp_students)->sortBy(function($item) {
                        $gsort = $item->gender == 'MALE' ? 0 : 1;
                        return $gsort.'-'.$item->studentname;
                    })->values();

            }else{

                  $strand = array();

                  $subjstrand = DB::table('subject_plot')
                                    ->where('syid',$syid)
                                    ->where('levelid',$levelid)
                                    ->where('subjid',$subjid)
                                    ->where('semid',$semid)
                                    ->where('deleted',0)
                                    ->get();

                  foreach($subjstrand as $stranditem){
                        array_push($strand, $stranditem->strandid);
                  }

                  $temp_students = array();

                  $enrolledstud = DB::table('sh_enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->where('sh_enrolledstud.sectionid',$sectionid)
                              ->where('sh_enrolledstud.levelid',$levelid)
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.semid',$semid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                              ->whereIn('sh_enrolledstud.strandid',$strand)
                              ->distinct('studid')
                              ->select(
                                    'studid',
                                    'firstname',
                                    'lastname',
                                    'middlename',
                                    'suffix',
                                    'mol',
                                    'gender',
                                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                              )
                              ->orderBy('gender','desc')
                              ->orderBy('studentname','asc')
                              ->get();

                  foreach($enrolledstud as $item){
                        array_push($temp_students,$item);
                  }

                  $enrolledstud = DB::table('student_specsubj')
                            ->join('studinfo',function($join){
                                $join->on('student_specsubj.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->join('sh_enrolledstud',function($join) use($syid,$semid){
                                $join->on('student_specsubj.studid','=','sh_enrolledstud.studid');
                                $join->whereIn('sh_enrolledstud.studstatus',[1,2,4]);
                                $join->where('sh_enrolledstud.deleted',0);
                                $join->where('sh_enrolledstud.syid',$syid);
                                $join->where('sh_enrolledstud.semid',$semid);
                            })
                            ->join('sh_strand',function($join){
                                $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                $join->where('sh_strand.deleted',0);
                            })
                            ->where('student_specsubj.status','ADDITIONAL')
                            ->where('student_specsubj.syid',$syid)
                            ->where('student_specsubj.semid',$semid)
                            ->where('student_specsubj.sectionid',$sectionid)
                            ->where('student_specsubj.subjid',$subjid)
                            ->where('student_specsubj.deleted',0)
                            ->select(
                                'sh_enrolledstud.studid',
                                'firstname',
                                'lastname',
                                'middlename',
                                'suffix',
                                'mol',
                                'gender',
                                'strandcode',
                                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                            )
                            ->get();

                    foreach($enrolledstud as $item){
                        array_push($temp_students,$item);
                    }

                    $enrolledstud = collect($temp_students)->sortBy(function($item) {
                        $gsort = $item->gender == 'MALE' ? 0 : 1;
                        return $gsort.'-'.$item->studentname;
                    })->values();

            }

            $grades = array();

            foreach($enrolledstud as $item){

                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';

                  if($middlename != null){
                      foreach ($middlename as $middlename_item) {
                          if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                          } 
                      }
                  }
  
                  $item->student = $item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
                 
                  $ifGradeExist = DB::table('gradesdetail')
                              ->where('headerid',$check_header[0]->id)
                              ->where('studid',$item->studid)
                              ->first();
                  try{
                        $ifGradeExist->student = $item->student;
                        $ifGradeExist->gender = $item->gender;
                        array_push($grades,$ifGradeExist);
                  }catch(\Exception $e){

                  }

            }
            

            $view = 'superadmin.pages.teacher.gradeview';

            if($schoolinfo->snr == 'shjms'){
                  $view = 'superadmin.pages.teacher.gradeview_shjms';
            }

           


            return view($view)
                        ->with('hps',$check_header)
                        ->with('percentage',$gradesetup)
                        ->with('schoolinfo',$schoolinfo)
                        ->with('grades',$grades);

      }

      public static function submit_ecr(Request $request){

            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $quarter = $request->get('quarter');
            $exclude = $request->get('excluded');

            if($exclude == null){
                  $exclude = array();
            }

            $check_header = DB::table('grades')
                              ->where('syid',$syid)
                              ->where('levelid',$levelid)
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjid)
                              ->where('quarter',$quarter)
                              ->where('deleted',0)
                              ->get();

            if(count($check_header) == 0){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Does not contain details'
                  ]);
            }else if(count($check_header) > 1){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Contains multiple details'
                  ]);
            }

            try{

                  DB::table('grades')
                        ->where('id',$check_header[0]->id)
                        ->take(1)
                        ->update([
                              'date_submitted'=>\Carbon\Carbon::now('Asia/Manila'),
                              'submitted'=>1,
                              'status'=>1,
                              'submittedby'=>auth()->user()->id,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'coorapp'=>null,
                              'coorappdatetime'=>null
                        ]);

                  DB::table('gradelogs')
                        ->insert([
                              'action'=>1,
                              'actiontext'=>'Submit',
                              'gradeid'=>$check_header[0]->id,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('gradesdetail')
                        ->where('headerid',$check_header[0]->id)
                        ->whereNotIn('studid',$exclude)
						->where(function($query){
							$query->where('gdstatus',0);
							$query->orWhere('gdstatus',3);
							$query->orWhereNull('gdstatus');
						})
                        ->update([
                              'gdstatus'=>1,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                    DB::table('gradesdetail')
                        ->where('headerid',$check_header[0]->id)
                        ->whereIn('studid',$exclude)
                        ->where(function($query){
							$query->orWhere('gdstatus',0);
                                          $query->orWhere('gdstatus',3);
							$query->orWhereNull('gdstatus');
						})
                        ->update([
                              'gdstatus'=>3,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  return array((object)[
                        'status'=>1,
                        'message'=>'Submitted successfully'
                  ]);

            }catch(\Exception $e){
                  
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong.'
                  ]);
            }


      }

      public static function approve_ecr(Request $request){

            $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $quarter = $request->get('quarter');
            $exclude = $request->get('exclude');

            if($exclude == null){
                  $exclude = array();
            }

            $check_header = DB::table('grades')
                              ->where('syid',$syid)
                              ->where('levelid',$levelid)
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjid)
                              ->where('quarter',$quarter)
                              ->where('deleted',0)
                              ->select('id','coorapp')
                              ->get();
          
            try{

                  $header = $check_header[0]->id;

                  DB::table('gradesdetail')
                        ->where('headerid',$header)
                        ->where('gdstatus','!=',2)
                        ->whereNotIn('studid',$exclude)
                        ->whereIn('gdstatus',[1,0])
                        ->update([
                              'gdstatus'=>2,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  if(count($exclude) == 0){

                        if($check_refid->refid != 0){

                              if($check_refid->refid == 22){
                                    DB::table('grades')
                                          ->where('id',$header)
                                          ->take(1)
                                          ->update([
                                                'coorapp'=>auth()->user()->id,
                                                'coorappdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                                'updatedby'=>auth()->user()->id,
                                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }

                        }else{

                              if($check_header[0]->coorapp == null){


                                    DB::table('grades')
                                          ->where('id',$header)
                                          ->take(1)
                                          ->update([
                                                'status'=>2,
                                                'coorapp'=>auth()->user()->id,
                                                'coorappdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                                'updatedby'=>auth()->user()->id,
                                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                              }else{
                                    DB::table('grades')
                                          ->where('id',$header)
                                          ->take(1)
                                          ->update([
                                                'status'=>2,
                                                'updatedby'=>auth()->user()->id,
                                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);
                              }

                        }

                        DB::table('gradelogs')
                              ->insert([
                                    'action'=>2,
                                    'actiontext'=>'Approve',
                                    'gradeid'=>$header,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Approved successfully'
                  ]);

            }catch(\Exception $e){

                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong.'
                  ]);
            }

      }

      public static function post_ecr(Request $request){

            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $quarter = $request->get('quarter');
            $exclude = $request->get('exclude');

            if($exclude == null){
                  $exclude = array();
            }

            $check_header = DB::table('grades')
                              ->where('syid',$syid)
                              ->where('levelid',$levelid)
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjid)
                              ->where('quarter',$quarter)
                              ->where('deleted',0)
                              ->get();
          
            try{

                  if(count($exclude) == 0){
                        DB::table('grades')
                              ->where('id',$check_header[0]->id)
                              ->take(1)
                              ->update([
                                    'status'=>4,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('gradelogs')
                              ->insert([
                                    'action'=>4,
                                    'actiontext'=>'Post',
                                    'gradeid'=>$check_header[0]->id,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  DB::table('gradesdetail')
                        ->where('headerid',$check_header[0]->id)
                        ->whereNotIn('studid',$exclude)
                        ->where('gdstatus','!=',4)
                        ->update([
                              'gdstatus'=>4,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                 

                  return array((object)[
                        'status'=>1,
                        'message'=>'Posted successfully'
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong.'
                  ]);
            }

      }

      public static function pending_ecr(Request $request){

            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $quarter = $request->get('quarter');
            $exclude = $request->get('exclude');

            if($exclude == null){
                  $exclude = array();
            }

            $check_header = DB::table('grades')
                              ->where('syid',$syid)
                              ->where('levelid',$levelid)
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjid)
                              ->where('quarter',$quarter)
                              ->where('deleted',0)
                              ->select('id')
                              ->get();
          
            try{

                  $headerid = $check_header[0]->id;

                  DB::table('gradesdetail')
                        ->where('headerid',$check_header[0]->id)
                        ->whereNotIn('studid',$exclude)
                        ->where('gdstatus','!=',3)
                        ->update([
                              'gdstatus'=>3,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  


                  if(count($exclude) == 0){

                        DB::table('grades')
                              ->where('id',$headerid)
                              ->take(1)
                              ->update([
                                    'submitted'=>0,
                                    'status'=>3,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('gradelogs')
                              ->insert([
                                    'action'=>3,
                                    'actiontext'=>'Pending',
                                    'gradeid'=>$headerid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }
      
                  return array((object)[
                        'status'=>1,
                        'message'=>'Added to pending successfully'
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong.'
                  ]);
            }

      }

      public static function unpost_ecr(Request $request){

            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $quarter = $request->get('quarter');

            $exclude = $request->get('exclude');

            if($exclude == null){
                  $exclude = array();
            }

            $check_header = DB::table('grades')
                              ->where('syid',$syid)
                              ->where('levelid',$levelid)
                              ->where('sectionid',$sectionid)
                              ->where('subjid',$subjid)
                              ->where('quarter',$quarter)
                              ->where('deleted',0)
                              ->get();
          
            try{

                  DB::table('gradesdetail')
                        ->where('headerid',$check_header[0]->id)
                        ->where('gdstatus',4)
                        ->where('gdstatus','!=',2)
                        ->whereNotIn('studid',$exclude)
                        ->update([
                              'gdstatus'=>2,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  if(count($exclude) == 0){

                        DB::table('grades')
                              ->where('id',$check_header[0]->id)
                              ->take(1)
                              ->update([
                                    'status'=>2,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('gradelogs')
                              ->insert([
                                    'action'=>5,
                                    'actiontext'=>'Unpost',
                                    'gradeid'=>$check_header[0]->id,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Added to approved list'
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong.'
                  ]);
            }

      }


      public static function pending_student(Request $request){

            $studid = $request->get('studid');
            $id = $request->get('id');
          
            try{

                  DB::table('gradesdetail')
                        ->where('studid',$studid)
                        ->where('id',$id)
                        ->take(1)
                        ->update([
                              'gdstatus'=>3,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $get_header = Db::table('gradesdetail')
                                    ->where('studid',$studid)
                                    ->where('id',$id)
                                    ->select('headerid')
                                    ->get();

                  $headerid = $get_header[0]->headerid;

                  $check_detai = DB::table('gradesdetail')
                                    ->where('headerid',$headerid)
                                    ->whereNotIn('gdstatus',[0,3])
                                    ->get();

                  if(count($check_detai) == 0){

                        DB::table('grades')
                              ->where('id',$headerid)
                              ->take(1)
                              ->update([
                                    'submitted'=>0,
                                    'status'=>3,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('gradelogs')
                              ->insert([
                                    'action'=>3,
                                    'actiontext'=>'Pending',
                                    'gradeid'=>$headerid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Added to Pending'
                  ]);

            }catch(\Exception $e){

                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong.'
                  ]);
            }

      }

      public static function approve_student(Request $request){

            $studid = $request->get('studid');
            $id = $request->get('id');
          
            try{

                  DB::table('gradesdetail')
                        ->where('studid',$studid)
                        ->where('id',$id)
                        ->update([
                              'gdstatus'=>2,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $get_header = Db::table('gradesdetail')
                        ->where('studid',$studid)
                        ->where('id',$id)
                        ->select('headerid')
                        ->get();

                  $headerid = $get_header[0]->headerid;

                  $check_detai = DB::table('gradesdetail')
                                    ->where('headerid',$headerid)
                                    ->whereNotIn('gdstatus',[2])
                                    ->get();

                  if(count($check_detai) == 0){

                        DB::table('grades')
                              ->where('id',$headerid)
                              ->take(1)
                              ->update([
                                    'status'=>2,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('gradelogs')
                              ->insert([
                                    'action'=>2,
                                    'actiontext'=>'Approve',
                                    'gradeid'=>$headerid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Approved Successfully'
                  ]);

            }catch(\Exception $e){

                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong.'
                  ]);
            }

      }

      public static function post_student(Request $request){

            $studid = $request->get('studid');
            $id = $request->get('id');
          
            try{

                  DB::table('gradesdetail')
                        ->where('studid',$studid)
                        ->where('id',$id)
                        ->update([
                              'gdstatus'=>4,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $get_header = Db::table('gradesdetail')
                        ->where('studid',$studid)
                        ->where('id',$id)
                        ->select('headerid')
                        ->get();

                  $headerid = $get_header[0]->headerid;

                  $check_detai = DB::table('gradesdetail')
                                    ->where('headerid',$headerid)
                                    ->whereNotIn('gdstatus',[4])
                                    ->get();

                  if(count($check_detai) == 0){

                        DB::table('grades')
                              ->where('id',$headerid)
                              ->take(1)
                              ->update([
                                    'status'=>4,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('gradelogs')
                              ->insert([
                                    'action'=>2,
                                    'actiontext'=>'Approve',
                                    'gradeid'=>$headerid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Posted Successfully'
                  ]);

            }catch(\Exception $e){

                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong'
                  ]);
            }

      }

      public static function unpost_student(Request $request){

            $studid = $request->get('studid');
            $id = $request->get('id');
          
            try{

                  DB::table('gradesdetail')
                        ->where('studid',$studid)
                        ->where('id',$id)
                        ->update([
                              'gdstatus'=>2,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'statusdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  $get_header = Db::table('gradesdetail')
                        ->where('studid',$studid)
                        ->where('id',$id)
                        ->select('headerid')
                        ->get();

                  $headerid = $get_header[0]->headerid;

                  $check_detai = DB::table('gradesdetail')
                                    ->where('headerid',$headerid)
                                    ->whereNotIn('gdstatus',[2])
                                    ->get();

                  if(count($check_detai) == 0){

                        DB::table('grades')
                              ->where('id',$headerid)
                              ->take(1)
                              ->update([
                                    'status'=>2,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        DB::table('gradelogs')
                              ->insert([
                                    'action'=>2,
                                    'actiontext'=>'Approve',
                                    'gradeid'=>$headerid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Added to approved list'
                  ]);

            }catch(\Exception $e){

                  return array((object)[
                        'status'=>0,
                        'message'=>'Something went wrong'
                  ]);
            }

      }


      
}
