<?php

namespace App\Http\Controllers\SuperAdminController\TeacherECR;

use Illuminate\Http\Request;
use DB;
use Hash;
use Session;
use \DateTime;
use PhpOffice\PhpSpreadsheet\Style\Protection;
class TeacherFormatAABController extends \App\Http\Controllers\Controller
{
      
      public static function download_ecr(Request $request){
            
            // http://sppv2.ck/ecr/download?syid=2&levelid=10&subjid=2&sectionid=1
            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            $ecrformat = $request->get('ecrformat');

            $schoolinfo = DB::table('schoolinfo')->first();

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
            // subject information 

            $subjinfo = collect($subjinfo)->where('ww','!=',null)->values();
               
            if(count($subjinfo) == 0){
                return "No grade setup";
            }

            if($levelinfo->acadprogid != 5){
                  $semid = 1;
            }

            $setup = '3'; 
            
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

         
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

            $schoolinfo = DB::table('schoolinfo')->first();
           
            if($levelinfo->acadprogid == 5){
                  $spreadsheet = $reader->load("ECR/ECR_HIGHEST_PERFECT_SCORE/ECR-SHS.xlsx");
            }else{
                  $spreadsheet = $reader->load("ECR/ECR_HIGHEST_PERFECT_SCORE/ECR.xlsx");
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

            $headers_array = array();

            for($x = 0 ; $x < 4; $x++){

                  $applicable = true;
                  $quarter = $x + 1;

                  // for SHS restrict to 3rd and 4th quarter
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


                              try{
                                    DB::table('grades_date')
                                          ->insert([
                                                'headerid' => $headerid,
                                                'deleted' => 0,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                                    

                              }catch(\Exception $e){

                              }

                              try{
                                    DB::table('grades_perfectscore')
                                          ->insert([
                                                'headerid' => $headerid,
                                                'deleted' => 0,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                                    

                              }catch(\Exception $e){

                              }
                                          
                        }else{

                              // for grades date
                              try{
                                    $check_gradedates = DB::table('grades_date')
                                                      ->where('headerid',$check_header[0]->id)
                                                      ->get();

                                    if(count( $check_gradedates) > 0){
                                          $count = 10;
                                          $sheet->setCellValue('F'.$count,$check_gradedates[0]->dww1 != null && $check_gradedates[0]->dww1 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dww1)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('G'.$count,$check_gradedates[0]->dww2 != null && $check_gradedates[0]->dww2 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dww2)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('H'.$count,$check_gradedates[0]->dww3 != null && $check_gradedates[0]->dww3 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dww3)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('I'.$count,$check_gradedates[0]->dww4 != null && $check_gradedates[0]->dww4 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dww4)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('J'.$count,$check_gradedates[0]->dww5 != null && $check_gradedates[0]->dww5 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dww5)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('K'.$count,$check_gradedates[0]->dww6 != null && $check_gradedates[0]->dww6 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dww6)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('L'.$count,$check_gradedates[0]->dww7 != null && $check_gradedates[0]->dww7 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dww7)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('M'.$count,$check_gradedates[0]->dww8 != null && $check_gradedates[0]->dww8 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dww8)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('N'.$count,$check_gradedates[0]->dww9 != null && $check_gradedates[0]->dww9 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dww9)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('O'.$count,$check_gradedates[0]->dww0 != null && $check_gradedates[0]->dww0 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dww0)->isoFormat('MM/DD/YYYY') : '');

                                          $sheet->setCellValue('S'.$count,$check_gradedates[0]->dpt1 != null && $check_gradedates[0]->dpt1 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dpt1)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('T'.$count,$check_gradedates[0]->dpt2 != null && $check_gradedates[0]->dpt2 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dpt2)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('U'.$count,$check_gradedates[0]->dpt3 != null && $check_gradedates[0]->dpt3 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dpt3)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('V'.$count,$check_gradedates[0]->dpt4 != null && $check_gradedates[0]->dpt4 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dpt4)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('W'.$count,$check_gradedates[0]->dpt5 != null && $check_gradedates[0]->dpt5 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dpt5)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('X'.$count,$check_gradedates[0]->dpt6 != null && $check_gradedates[0]->dpt6 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dpt6)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('Y'.$count,$check_gradedates[0]->dpt7 != null && $check_gradedates[0]->dpt7 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dpt7)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('Z'.$count,$check_gradedates[0]->dpt8 != null && $check_gradedates[0]->dpt8 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dpt8)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('AA'.$count,$check_gradedates[0]->dpt9 != null && $check_gradedates[0]->dpt9 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dpt9)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('AB'.$count,$check_gradedates[0]->dpt0 != null && $check_gradedates[0]->dpt0 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dpt0)->isoFormat('MM/DD/YYYY') : '');
                                      
                                          // $sheet->setCellValue('AL'.$count,$check_gradedates[0]->dqa1 != null && $check_gradedates[0]->dqa1 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dqa1)->isoFormat('MM/DD/YYYY') : '');
                                          // $sheet->setCellValue('AM'.$count,$check_gradedates[0]->dqa2 != null && $check_gradedates[0]->dqa2 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dqa2)->isoFormat('MM/DD/YYYY') : '');

                                          $sheet->setCellValue('AF'.$count,$check_gradedates[0]->dcg1 != null && $check_gradedates[0]->dcg1 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dcg1)->isoFormat('MM/DD/YYYY') : '');
                                          $sheet->setCellValue('AG'.$count,$check_gradedates[0]->dcg2 != null && $check_gradedates[0]->dcg2 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dcg2)->isoFormat('MM/DD/YYYY') : '');
                                          // $sheet->setCellValue('AH'.$count,$check_gradedates[0]->dcg3 != null && $check_gradedates[0]->dcg3 != '0000-00-00' ? \Carbon\Carbon::create($check_gradedates[0]->dcg3)->isoFormat('MM/DD/YYYY') : '');

                                    }else{
                                          DB::table('grades_date')
                                                ->insert([
                                                      'headerid' => $check_header[0]->id,
                                                      'deleted' => 0,
                                                      'createdby'=>auth()->user()->id,
                                                      'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                                ]);

                                    }
                                    
                              }catch(\Exception $e){

                                    // return $e;

                              }

                              // for perfect score

                              try{
                                    $check_gradedates = DB::table('grades_perfectscore')
                                                      ->where('headerid',$check_header[0]->id)
                                                      ->get();
                                                      
                                    if(count( $check_gradedates) > 0){
                                          $count = 12;

                                          $sheet->setCellValue('F'.$count,$check_gradedates[0]->pww1 != null && $check_gradedates[0]->pww1 != null ? $check_gradedates[0]->pww1: null);
                                          $sheet->setCellValue('G'.$count,$check_gradedates[0]->pww2 != null && $check_gradedates[0]->pww2 != null ? $check_gradedates[0]->pww2: null);
                                          $sheet->setCellValue('H'.$count,$check_gradedates[0]->pww3 != null && $check_gradedates[0]->pww3 != null ? $check_gradedates[0]->pww3: null);
                                          $sheet->setCellValue('I'.$count,$check_gradedates[0]->pww4 != null && $check_gradedates[0]->pww4 != null ? $check_gradedates[0]->pww4: null);
                                          $sheet->setCellValue('J'.$count,$check_gradedates[0]->pww5 != null && $check_gradedates[0]->pww5 != null ? $check_gradedates[0]->pww5: null);
                                          $sheet->setCellValue('K'.$count,$check_gradedates[0]->pww6 != null && $check_gradedates[0]->pww6 != null ? $check_gradedates[0]->pww6: null);
                                          $sheet->setCellValue('L'.$count,$check_gradedates[0]->pww7 != null && $check_gradedates[0]->pww7 != null ? $check_gradedates[0]->pww7: null);
                                          $sheet->setCellValue('M'.$count,$check_gradedates[0]->pww8 != null && $check_gradedates[0]->pww8 != null ? $check_gradedates[0]->pww8: null);
                                          $sheet->setCellValue('N'.$count,$check_gradedates[0]->pww9 != null && $check_gradedates[0]->pww9 != null ? $check_gradedates[0]->pww9: null);
                                          $sheet->setCellValue('O'.$count,$check_gradedates[0]->pww0 != null && $check_gradedates[0]->pww0 != null ? $check_gradedates[0]->pww0: null);

                                          $sheet->setCellValue('S'.$count,$check_gradedates[0]->ppt1 != null && $check_gradedates[0]->ppt1 != null ? $check_gradedates[0]->ppt1: null);
                                          $sheet->setCellValue('T'.$count,$check_gradedates[0]->ppt2 != null && $check_gradedates[0]->ppt2 != null ? $check_gradedates[0]->ppt2: null);
                                          $sheet->setCellValue('U'.$count,$check_gradedates[0]->ppt3 != null && $check_gradedates[0]->ppt3 != null ? $check_gradedates[0]->ppt3: null);
                                          $sheet->setCellValue('V'.$count,$check_gradedates[0]->ppt4 != null && $check_gradedates[0]->ppt4 != null ? $check_gradedates[0]->ppt4: null);
                                          $sheet->setCellValue('W'.$count,$check_gradedates[0]->ppt5 != null && $check_gradedates[0]->ppt5 != null ? $check_gradedates[0]->ppt5: null);
                                          $sheet->setCellValue('X'.$count,$check_gradedates[0]->ppt6 != null && $check_gradedates[0]->ppt6 != null ? $check_gradedates[0]->ppt6: null);
                                          $sheet->setCellValue('Y'.$count,$check_gradedates[0]->ppt7 != null && $check_gradedates[0]->ppt7 != null ? $check_gradedates[0]->ppt7: null);
                                          $sheet->setCellValue('Z'.$count,$check_gradedates[0]->ppt8 != null && $check_gradedates[0]->ppt8 != null ? $check_gradedates[0]->ppt8: null);
                                          $sheet->setCellValue('AA'.$count,$check_gradedates[0]->ppt9 != null && $check_gradedates[0]->ppt9 != null ? $check_gradedates[0]->ppt9: null);
                                          $sheet->setCellValue('AB'.$count,$check_gradedates[0]->ppt0 != null && $check_gradedates[0]->ppt0 != null ? $check_gradedates[0]->ppt0: null);
                                      
                                          // $sheet->setCellValue('AL'.$count,$check_gradedates[0]->pqa1 != null && $check_gradedates[0]->pqa1 != null ? $check_gradedates[0]->pqa1: null);
                                          // $sheet->setCellValue('AM'.$count,$check_gradedates[0]->pqa2 != null && $check_gradedates[0]->pqa2 != null ? $check_gradedates[0]->pqa2: null);

                                          $sheet->setCellValue('AF'.$count,$check_gradedates[0]->pcg1 != null && $check_gradedates[0]->pcg1 != null ? $check_gradedates[0]->pcg1: null);
                                          $sheet->setCellValue('AG'.$count,$check_gradedates[0]->pcg2 != null && $check_gradedates[0]->pcg2 != null ? $check_gradedates[0]->pcg2: null);
                                          // $sheet->setCellValue('AH'.$count,$check_gradedates[0]->pcg3 != null && $check_gradedates[0]->pcg3 != null ? $check_gradedates[0]->pcg3: null);

                                          // Array of column letters where the formula needs to be applied
                                          $columns = ['F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 
                                                      'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 
                                                      'AF', 'AG'];

                                    foreach ($columns as $column) {
                                          // Apply the formula and lock the cell
                                          $sheet->setCellValue($column.$count, '=IF(COUNT('.$column.'13:'.$column.'151)=0, "", MAX('.$column.'13:'.$column.'151))')
                                                ->getStyle($column.$count)
                                                ->getProtection()
                                                ->setLocked(Protection::PROTECTION_PROTECTED); // Locking individual cells
                                          }
                                          
                                          // Protect the entire sheet
                                          $sheet->getParent()->getActiveSheet()->getProtection()->setSheet(true);

                                    }else{
                                          DB::table('grades_perfectscore')
                                                ->insert([
                                                      'headerid' => $check_header[0]->id,
                                                      'deleted' => 0,
                                                      'createdby'=>auth()->user()->id,
                                                      'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                                ]);

                                    }
                                    
                              }catch(\Exception $e){

                                    // return $e;

                              }

                              $headerid = $check_header[0]->id;
                              $count = 11;

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

                              // $sheet->setCellValue('AF'.$count,$check_header[0]->cghr1 == 0 ? "": $check_header[0]->cghr1);
                              // $sheet->setCellValue('AG'.$count,$check_header[0]->cghr2 == 0 ? "": $check_header[0]->cghr2);
                              // $sheet->setCellValue('AH'.$count,$check_header[0]->cghr3 == 0 ? "": $check_header[0]->cghr3);

                              $sheet->setCellValue('AF'.$count,$check_header[0]->qahr1 == 0 ? "": $check_header[0]->qahr1);
                              $sheet->setCellValue('AG'.$count,$check_header[0]->qahr2 == 0 ? "": $check_header[0]->qahr2);
                        
                        }
                       
                        $male = 0;
                        $female = 0;
                        $count = 13;
                        
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
                       
                        

                        $sheet->setCellValue('G5', $schoolinfo->schoolname);
                        $sheet->setCellValue('X5', $schoolinfo->schoolid);
                        $sheet->setCellValue('AI5', $sy->sydesc);
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

                        // if($subjinfo[0]->comp4 != null && $subjinfo[0]->comp4 != 0){
                        //       $sheet->setCellValue('AF8', 'Character Grade('.$subjinfo[0]->comp4.'%)');

                        //       $sheet->setCellValue('AK11', $subjinfo[0]->comp4 / 100 );

                        // }

                        if($subjinfo[0]->qa != null && $subjinfo[0]->qa != 0){
                              $sheet->setCellValue('AF8', 'Quarterly Assessment('.$subjinfo[0]->qa.'%)');

                              $sheet->setCellValue('AJ11', $subjinfo[0]->qa / 100 );
                        }

                        $sheet->setCellValue('R11', $subjinfo[0]->ww / 100 );
                        $sheet->setCellValue('AE11', $subjinfo[0]->pt / 100 );


                        $sheet->getStyle('R11')
                                    ->getNumberFormat()
                                    ->setFormatCode('0%;[Red]-0%');
                        
                        $sheet->getStyle('AE11')
                              ->getNumberFormat()
                              ->setFormatCode('0%;[Red]-0%');

                        // $sheet->getStyle('AK11')
                        //       ->getNumberFormat()
                        //       ->setFormatCode('0%;[Red]-0%');

                        $sheet->getStyle('AK11')
                              ->getNumberFormat()
                              ->setFormatCode('0%;[Red]-0%');
                      

                        $sheet->setCellValue('S7', $adviser);

                        $row_count = 1;

                        // special case nga mga bata
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

                              $range = 'A'.$count.':'.'AK'.$count;
                              

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
                                    $sheet->getStyle($range)->getFill()
                                          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                          ->getStartColor()->setARGB('bfbfbf');

                                    $sheet->getStyle($range)->applyFromArray($gender);

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

                                    // $sheet->setCellValue('AF'.$count,$check_detail->qa1 == 0 ? "": $check_detail->cg1);
                                    // $sheet->setCellValue('AG'.$count,$check_detail->qa2 == 0 ? "": $check_detail->cg2);
                                    // $sheet->setCellValue('AH'.$count,$check_detail->qa2 == 0 ? "": $check_detail->cg3);

                                    $sheet->setCellValue('AF'.$count,$check_detail->qa1 == 0 ? "": $check_detail->qa1);
                                    $sheet->setCellValue('AF'.$count,$check_detail->qa2 == 0 ? "": $check_detail->qa2);



                              }

                              if($subjinfo[0]->comp4 != null && $subjinfo[0]->comp4 != 0){
                                    // $sheet->setCellValue('AK'.$count, "= ROUND(SUM(R".$count.",AE".$count.",AK".$count.",AL".$count."),0)");
                                    $sheet->setCellValue('AK'.$count, "= ROUND(SUM(R".$count.",AE".$count.",AJ".$count."),0)");
                              } else {
                                    $sheet->setCellValue('AK'.$count, "= ROUND(SUM(R".$count.",AE".$count.",AJ".$count."),0)");
                              }

                              $sheet->setCellValue('B'.$count,$item->student);
                              $sheet->setCellValue('A'.$count,$row_count);
                              $sheet->setCellValue('AZ'.$count, $item->id);
                              $count +=1;
                              $row_count += 1;
                        }


                        $sheet->getStyle('A'.$count.':'.'AK151')->applyFromArray($no_border);
                        $sheet->getStyle('A'.$count.':'.'AK'.$count)->applyFromArray($thick_top);
                        $sheet->getPageSetup()->setPrintArea('A1:AK'.($count-1));
                             
                       
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
            $count = 13;
           
            $male = 0;
            $female = 0;


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

            $sheet->getStyle('A'.$count.':'.'AB151')->applyFromArray($no_border);
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


             

                  $start_row = 9;

                  // return $headerid;
          
                  // return $data[$start_row];

                  DB::table('grades_date')
                        ->where('headerid',$headerid)
                        ->take(1)
                        ->update([
                              'dww1'=>self::validateDate($data[$start_row][5]) ? \Carbon\Carbon::create($data[$start_row][5]) : '',
                              'dww2'=>self::validateDate($data[$start_row][6]) ? \Carbon\Carbon::create($data[$start_row][6]) : '',
                              'dww3'=>self::validateDate($data[$start_row][7]) ? \Carbon\Carbon::create($data[$start_row][7]) : '',
                              'dww4'=>self::validateDate($data[$start_row][8]) ? \Carbon\Carbon::create($data[$start_row][8]) : '',
                              'dww5'=>self::validateDate($data[$start_row][9]) ? \Carbon\Carbon::create($data[$start_row][9]) : '',
                              'dww6'=>self::validateDate($data[$start_row][10]) ? \Carbon\Carbon::create($data[$start_row][10]) : '',
                              'dww7'=>self::validateDate($data[$start_row][11]) ? \Carbon\Carbon::create($data[$start_row][11]) : '',
                              'dww8'=>self::validateDate($data[$start_row][12]) ? \Carbon\Carbon::create($data[$start_row][12]) : '',
                              'dww9'=>self::validateDate($data[$start_row][13]) ? \Carbon\Carbon::create($data[$start_row][13]) : '',
                              'dww0'=>self::validateDate($data[$start_row][14]) ? \Carbon\Carbon::create($data[$start_row][14]) : '',

                              'dpt1'=>self::validateDate($data[$start_row][18]) ? \Carbon\Carbon::create($data[$start_row][18]) : '',
                              'dpt2'=>self::validateDate($data[$start_row][19]) ? \Carbon\Carbon::create($data[$start_row][19]) : '',
                              'dpt3'=>self::validateDate($data[$start_row][20]) ? \Carbon\Carbon::create($data[$start_row][20]) : '',
                              'dpt4'=>self::validateDate($data[$start_row][21]) ? \Carbon\Carbon::create($data[$start_row][21]) : '',
                              'dpt5'=>self::validateDate($data[$start_row][22]) ? \Carbon\Carbon::create($data[$start_row][22]) : '',
                              'dpt6'=>self::validateDate($data[$start_row][23]) ? \Carbon\Carbon::create($data[$start_row][23]) : '',
                              'dpt7'=>self::validateDate($data[$start_row][24]) ? \Carbon\Carbon::create($data[$start_row][24]) : '',
                              'dpt8'=>self::validateDate($data[$start_row][25]) ? \Carbon\Carbon::create($data[$start_row][25]) : '',
                              'dpt9'=>self::validateDate($data[$start_row][26]) ? \Carbon\Carbon::create($data[$start_row][26]) : '',
                              'dpt0'=>self::validateDate($data[$start_row][27]) ? \Carbon\Carbon::create($data[$start_row][27]) : '',
                              
                              'dqa1'=>self::validateDate($data[$start_row][37]) ? \Carbon\Carbon::create($data[$start_row][37]) : '',
                              'dqa2'=>self::validateDate($data[$start_row][38]) ? \Carbon\Carbon::create($data[$start_row][38]) : '',
                              
                              'dcg1'=>self::validateDate($data[$start_row][31]) ? \Carbon\Carbon::create($data[$start_row][31]) : '',
                              'dcg2'=>self::validateDate($data[$start_row][32]) ? \Carbon\Carbon::create($data[$start_row][32]) : '',
                              'dcg3'=>self::validateDate($data[$start_row][33]) ? \Carbon\Carbon::create($data[$start_row][33]) : '',
                              // 'dqa2'=>self::validateDate($data[$start_row][19]) ? \Carbon\Carbon::create($data[$start_row][19]) : '',

                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                         
                  $start_row = 10;
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

                              'cghr1'=>(float)$data[$start_row][31],
                              'cghr2'=>(float)$data[$start_row][32],
                              'cghr3'=>(float)$data[$start_row][33],
                              'cghrtotal'=>(float)$data[$start_row][34],

                              'qahr1'=>(float)$data[$start_row][37],
                              'qahr2'=>(float)$data[$start_row][38],
                              'qahrtotal'=>(float)$data[$start_row][39],

                              'uploadedby'=>auth()->user()->id,
                              'uploadeddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);



                        $start_row = 11;

                        DB::table('grades_perfectscore')
                              ->where('headerid',$headerid)
                              ->take(1)
                              ->update([
                                    'pww1'=>(float)$data[$start_row][5],
                                    'pww2'=>(float)$data[$start_row][6],
                                    'pww3'=>(float)$data[$start_row][7],
                                    'pww4'=>(float)$data[$start_row][8],
                                    'pww5'=>(float)$data[$start_row][9],
                                    'pww6'=>(float)$data[$start_row][10],
                                    'pww7'=>(float)$data[$start_row][11],
                                    'pww8'=>(float)$data[$start_row][12],
                                    'pww9'=>(float)$data[$start_row][13],
                                    'pww0'=>(float)$data[$start_row][14],
                                    'pwwtotal'=>(float)$data[$start_row][15],
      
                                    'ppt1'=>(float)$data[$start_row][18],
                                    'ppt2'=>(float)$data[$start_row][19],
                                    'ppt3'=>(float)$data[$start_row][20],
                                    'ppt4'=>(float)$data[$start_row][21],
                                    'ppt5'=>(float)$data[$start_row][22],
                                    'ppt6'=>(float)$data[$start_row][23],
                                    'ppt7'=>(float)$data[$start_row][24],
                                    'ppt8'=>(float)$data[$start_row][25],
                                    'ppt9'=>(float)$data[$start_row][26],
                                    'ppt0'=>(float)$data[$start_row][27],
                                    'ppttotal'=>(float)$data[$start_row][28],
      
                                    'pcg1'=>(float)$data[$start_row][31],
                                    'pcg2'=>(float)$data[$start_row][32],
                                    'pcg3'=>(float)$data[$start_row][33],
                                    'pcgtotal'=>(float)$data[$start_row][34],
      
                                    'pqa1'=>(float)$data[$start_row][37],
                                    'pqa2'=>(float)$data[$start_row][38],
                                    'pqatotal'=>(float)$data[$start_row][39],
      
                                    'uploadedby'=>auth()->user()->id,
                                    'uploadeddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
            }

            $start_row = 13 - 1;
           

            for($x = $start_row; $x <= 151; $x++){

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

                                    if(count($check_detail) == 0){
                                          DB::table('gradesdetail')
                                                ->insert([
                                                      'headerid'=>$headerid,
                                                      'studid'=>$studid,

                                                      'ig'=>(float)$data[$start_row][42],
                                                      'qg'=>(float)$data[$start_row][43],

                                                    
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

                                                      'cg1'=>(float)$data[$start_row][31],
                                                      'cg2'=>(float)$data[$start_row][32],
                                                      'cg3'=>(float)$data[$start_row][33],
                                                      'cgtotal'=>(float)$data[$start_row][34],
                                                      'cgps'=>(float)$data[$start_row][35],
                                                      'cgws'=>(float)$data[$start_row][36],

                                                      'qa1'=>(float)$data[$start_row][37],
                                                      'qa2'=>(float)$data[$start_row][38],
                                                      'qatotal'=>(float)$data[$start_row][39],
                                                      'qaps'=>(float)$data[$start_row][40],
                                                      'qaws'=>(float)$data[$start_row][41]
                                                ]);
                                    }else{
                                          DB::table('gradesdetail')
                                                ->where('headerid',$headerid)
                                                ->where('studid',$studid)
                                                ->whereIn('gdstatus',[0,3])
                                                ->take(1)
                                                ->update([
                                                      'ig'=>(float)$data[$start_row][42],
                                                      'qg'=>(float)$data[$start_row][43],

                                                    
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

                                                      'cg1'=>(float)$data[$start_row][31],
                                                      'cg2'=>(float)$data[$start_row][32],
                                                      'cg3'=>(float)$data[$start_row][33],
                                                      'cgtotal'=>(float)$data[$start_row][34],
                                                      'cgps'=>(float)$data[$start_row][35],
                                                      'cgws'=>(float)$data[$start_row][36],

                                                      'qa1'=>(float)$data[$start_row][37],
                                                      'qa2'=>(float)$data[$start_row][38],
                                                      'qatotal'=>(float)$data[$start_row][39],
                                                      'qaps'=>(float)$data[$start_row][40],
                                                      'qaws'=>(float)$data[$start_row][41]
                                                ]);
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

      public static function validateDate($date, $format = 'n/j/Y')
      {
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
      }
      
}
