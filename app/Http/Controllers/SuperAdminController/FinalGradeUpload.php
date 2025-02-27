<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;
use PDF;
use Carbon\Carbon;


class FinalGradeUpload extends \App\Http\Controllers\Controller
{

      public static function downloadGrades(Request $request){

            $subjid = $request->get('subjid');
            $sectionid = $request->get('sectionid');
            $syid = $request->get('syid');
            $levelid = $request->get('levelid');

            // $subjid = 9;
            // $sectionid = 4;
            // $syid = 5;
            // $levelid = 13;

            if($levelid == 14 || $levelid == 15){
                  $semid = $request->get('semid');
            }else{
                  $semid = 1;
            }

            $grades = DB::table('grades')
                        ->join('gradesdetail',function($join){
                              $join->on('gradesdetail.headerid','=','grades.id');
                        })
                        ->where('sectionid',$sectionid)
                        ->where('syid',$syid)
                        ->where('subjid',$subjid)
                        ->where('levelid',$levelid)
                        ->select(
                              'quarter',
                              'qg',
                              'studid'
                        )
                        ->get();

            $levelinfo = DB::table('gradelevel')
                        ->where('id',$levelid)
                        ->first();
                              
            $section = DB::table('sections')
                              ->where('id',$sectionid)
                              ->first();

            $sy = DB::table('sy')
                        ->where('id',$syid)
                        ->first();


            if($levelinfo->acadprogid != 5){

                  $subjectinfo = DB::table('subjects')
                                    ->where('id',$subjid)
                                    ->select(
                                          'subjdesc',
                                          'subjcode'
                                    )
                                    ->first();

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

                  $subjectinfo = DB::table('sh_subjects')
                                    ->where('id',$subjid)
                                    ->select(
                                          'subjtitle as subjdesc',
                                          'subjcode'
                                    )
                                    ->first();

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


            // return $student;

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load("FinalGradeExtract.xlsx"); 


            $sheet = $spreadsheet->setActiveSheetIndex(0);


            $sheet->setCellValue('H2',$semid);

            $sheet->setCellValue('F2',$subjid);
            $sheet->setCellValue('F3',$levelid);
            $sheet->setCellValue('F4',$sectionid);
            $sheet->setCellValue('F5',$syid);
            // $sheet->setCellValue('F6',$teacherid);

            $sheet->setCellValue('B2',$subjectinfo->subjcode.' - '.$subjectinfo->subjdesc);
            $sheet->setCellValue('B3',$levelinfo->levelname);
            $sheet->setCellValue('B4',$section->sectionname);
            $sheet->setCellValue('B5',$sy->sydesc);
            // $sheet->setCellValue('F6',$teacherid);

            $row = 8;

            foreach($student as $item){

                  $sheet->setCellValue('A'.$row,$item->studentname);


                  $studgrades = collect($grades)
                                    ->where('studid',$item->id)
                                    ->values();

                  $q1 = isset(collect($studgrades)->where('quarter',1)->first()->qg) ? collect($studgrades)->where('quarter',1)->first()->qg : null;
                  $q2 = isset(collect($studgrades)->where('quarter',2)->first()->qg) ? collect($studgrades)->where('quarter',2)->first()->qg : null;
                  $q3 = isset(collect($studgrades)->where('quarter',3)->first()->qg) ? collect($studgrades)->where('quarter',3)->first()->qg : null;
                  $q4 = isset(collect($studgrades)->where('quarter',4)->first()->qg) ? collect($studgrades)->where('quarter',4)->first()->qg : null;

                  

                  $sheet->setCellValue('E'.$row,$q1);
                  $sheet->setCellValue('F'.$row,$q2);
                  $sheet->setCellValue('G'.$row,$q3);
                  $sheet->setCellValue('H'.$row,$q4);


                  $sheet->setCellValue('K'.$row,$item->id);

                  $row += 1;
            }


            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="FinalGrade.xlsx"');
            $writer->save("php://output");
            exit();


      }

      public static function  uploadGrades(Request $request){


            $path = $request->file('input_finalgrade')->getRealPath();
            
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($path);
            $worksheet = $spreadsheet->setActiveSheetIndex(0);

            $data = $worksheet->toArray();

            $subjid =  $data[1][5];
            $semid =  $data[1][7];
            
            $sectionid = $data[3][5];
            $levelid = $data[2][5];
            $syid = $data[4][5];

            for($quarter = 1; $quarter <=  4 ; $quarter++){

                  $check_header = DB::table('grades')
                                    ->where('levelid',$levelid)
                                    ->where('sectionid',$sectionid)
                                    ->where('subjid',$subjid)
                                    ->where('semid',$semid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->where('quarter',$quarter)
                                    ->get();

                  if(count($check_header) == 0){
                        $headerid = DB::table('grades')
                                          ->insertGetId([
                                                'syid' => $syid,
                                                'levelid' => $levelid,
                                                'sectionid' => $sectionid,
                                                'subjid' => $subjid,
                                                'quarter' => $quarter,
                                                'semid'=>$semid,
                                                'deleted' => 0,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                  }else{
                        $headerid = $check_header[0]->id;
                  }


                  $start_row = 7 ;




                  for($x = $start_row; $x <= 150; $x++){

                        $studid = $data[$x][10];

                        $q1 = $data[$x][4];
                        $q2 = $data[$x][5];
                        $q3 = $data[$x][6];
                        $q4 = $data[$x][7];

                        if($data[$x][10] != ""){

                              $check_detail = DB::table('gradesdetail')
                                                ->where('studid',$studid)
                                                ->where('headerid',$headerid)
                                                ->first();

                              if($quarter == 1){
                                    $qg = $q1;
                              }else if($quarter == 2){
                                    $qg = $q2;
                              }else if($quarter == 3){
                                    $qg = $q3;
                              }else if($quarter == 4){
                                    $qg = $q4;
                              }

                              if(!isset($check_detail->id)){
                                    DB::table('gradesdetail')
                                                ->insertGetId([
                                                      'studid' => $studid,
                                                      'headerid' => $headerid,
                                                      'qg' => $qg,
                                                      'gdstatus'=>0,
                                                      'createdby'=>auth()->user()->id,
                                                      'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                                ]);
                              }else{

                                    if($check_detail->qg == null){
                                          DB::table('gradesdetail')
                                          ->where('studid',$studid)
                                          ->where('headerid',$headerid)
                                          ->where('id',$check_detail->id)
                                          ->take(1)
                                          ->update([
                                                'qg' => $qg,
                                                'gdstatus'=>0,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);
                                    }

                              }

                        }
                  }

            }

            
            return array((object)[
                  'status'=>1,
                  'message'=>'Uploaded Successfully'
            ]);

          

      }
      

}
