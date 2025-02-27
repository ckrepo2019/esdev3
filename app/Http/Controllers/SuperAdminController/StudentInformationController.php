<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Session;

class StudentInformationController extends \App\Http\Controllers\Controller
{
      
      public static function student_info_ajax(Request $request){
            $studid = $request->get('student');
            return self::student_info($studid);
      }

      public static function all_student_ajax(Request $request){
           $syid = $request->get('syid');
           return self::all_student($syid);
      }

      public static function student_enrollment(Request $request){
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            return self::all_student();
       }

      public static function student_info($studid = null){

         
            $student = DB::table('studinfo')
                        ->join('gradelevel',function($join){
                              $join->on('studinfo.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted','0');
                        })
                        ->leftJoin('nationality',function($join){
                              $join->on('studinfo.nationality','=','nationality.id');
                           
                        })
                        ->where('studinfo.deleted',0)
                        ->where('studinfo.id',$studid)
                        ->select(
                              'nationality.nationality as nationalitytext',
                              'studinfo.*',
                              'gradelevel.levelname'
                        )
                        ->get();
         

            foreach($student as $item){
                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                              if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                              } 
                        }
                  }
                  
                  $item->student=$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
                  $item->text = $item->sid.' - '.$item->student;


                  $check_userid = DB::table('users')
                                    ->where('email','S'.$item->sid)
                                    ->where('deleted',0)
                                    ->first();

                  if(isset($check_userid)){
                        $item->userid = $check_userid->id;
                  }


            }

            return $student;
      }

      public static function all_student($syid = null, $semid = null){

            if(auth()->user()->type == 17){
                  $students = DB::table('studinfo')
                              ->where('deleted',0)
                              ->orderBy('lastname')
                              ->select(
                                    'firstname',
                                    'lastname',
                                    'middlename',
                                    'suffix',
                                    'sid',
                                    'id',
                                    'userid'
                              )
                              ->get();

            }else{

                  $refid = DB::table('usertype')->where('id',auth()->user()->type)->where('deleted',0)->select('refid')->first();
                  $teacherid = DB::table('teacher')->where('tid',auth()->user()->email)->select('id')->first()->id;

                  if( Session::get('currentPortal') == 2){

                        // $academicprogram = DB::table('academicprogram')
                        //             ->where('principalid',$teacherid)
                        //             ->select('id')
                        //             ->get();

                        $academicprogram = DB::table('teacheracadprog')
                                                ->where('teacherid',$teacherid)
                                                ->where('syid',$syid)
                                                ->where('acadprogutype',2)
                                                ->where('acadprogid','!=',6)
                                                ->select('acadprogid as id')
                                                ->where('deleted',0)
                                                ->get();

                  }else{

                        $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;

                        $academicprogram = DB::table('teacheracadprog')
                                          ->where('teacherid',$teacherid)
                                          ->where('syid',$syid)
                                          ->select('acadprogid as id')
                                          ->where('deleted',0)
                                          ->get();
                  }

                  $acad = array();

                  $students = array();
                  foreach($academicprogram as $item){
                        $acad = $item->id ;
                       

                        if(Session::get('currentPortal') == 3){

                              $students_list = DB::table('studinfo')
                                                ->join('gradelevel',function($join) use($acad){
                                                      $join->on('studinfo.levelid','=','gradelevel.id');
                                                      $join->where('acadprogid',$acad);
                                                })
                                                ->where('studinfo.deleted',0)
                                                ->orderBy('lastname')
                                                ->select(
                                                      'studinfo.firstname',
                                                      'studinfo.lastname',
                                                      'studinfo.middlename',
                                                      'studinfo.suffix',
                                                      'studinfo.sid',
                                                      'studinfo.id'
                                                )
                                                ->get();

                              foreach($students_list as $item){
                                    array_push($students,$item);
                              }
                              
                        }else{

                              if($item->id != 5){

                                    $acad = $item->id;

                                    $student = DB::table('enrolledstud')
                                                ->where('enrolledstud.deleted',0)
                                                ->where('enrolledstud.syid',$syid)
                                                ->whereIn('enrolledstud.studstatus',[1,2,4])
                                                ->join('studinfo',function($join){
                                                      $join->on('studinfo.id','=','enrolledstud.studid');
                                                      $join->where('studinfo.deleted',0);
                                                })
                                                ->join('gradelevel',function($join) use($acad){
                                                      $join->on('enrolledstud.levelid','=','gradelevel.id');
                                                      $join->where('acadprogid',$acad);
                                                })
                                                ->orderBy('lastname')
                                                ->distinct('studid')
                                                ->select(
                                                      'firstname',
                                                      'lastname',
                                                      'middlename',
                                                      'suffix',
                                                      'sid',
                                                      'studinfo.id'
                                                )
                                                ->get();

                                    foreach($student as $item){
                                          array_push($students,$item);
                                    }

                              }else{
                              
                                    $student = DB::table('sh_enrolledstud')
                                          ->where('sh_enrolledstud.deleted',0)
                                          ->where('sh_enrolledstud.syid',$syid)
                                          ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                          ->join('studinfo',function($join){
                                                $join->on('studinfo.id','=','sh_enrolledstud.studid');
                                                $join->where('studinfo.deleted',0);
                                          })
                                          ->orderBy('lastname')
                                          ->select(
                                                'firstname',
                                                'lastname',
                                                'middlename',
                                                'suffix',
                                                'sid',
                                                'studinfo.id'
                                          )
                                          ->distinct('studid')
                                          ->get();

                                    foreach($student as $item){
                                          array_push($students,$item);
                                    }
                              }

                              

                        }
                  }

            }

            foreach($students as $item){
                  $temp_middle = '';
               
                  if(isset($item->middlename)){
                        if(strlen($item->middlename) > 0){
                              $temp_middle = ' '.$item->middlename[0].'.';
                        }
                        
                  }
                  
                  $item->student=$item->lastname.', '.$item->firstname.' '.$item->suffix.$temp_middle;
                  $item->text = $item->sid.' - '.$item->student;

            }

            return collect($students)->unique('id')->values();
      }

      public static function enrollment_record(Request $request){

            $enrollment = array();
            $studid = $request->get('studid');
         
            $enrollment_1 = DB::table('enrolledstud')
                                    ->where('studid',$studid)
                                    ->where('enrolledstud.deleted',0)
                                    ->join('sections',function($join){
                                          $join->on('enrolledstud.sectionid','=','sections.id');
                                          $join->where('sections.deleted',0);
                                    })
                                    ->join('gradelevel',function($join){
                                          $join->on('enrolledstud.levelid','=','gradelevel.id');
                                          $join->where('gradelevel.deleted',0);
                                    })
                                    ->join('sy',function($join){
                                          $join->on('enrolledstud.syid','=','sy.id');
                                    })
                                    ->select(
                                          'enrolledstud.syid',
                                          'enrolledstud.sectionid',
                                          'enrolledstud.levelid',
                                          'levelname',
                                          'sectionname',
                                          'sydesc'
                                    )
                                    ->get();

            foreach($enrollment_1 as $item){
                  $item->semid = null;
                  $item->strandcode = null;
                  array_push($enrollment,$item);
            }

            $enrollment_1 = DB::table('sh_enrolledstud')
                              ->where('studid',$studid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->join('sections',function($join){
                                    $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->join('sh_strand',function($join){
                                    $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                    $join->where('sh_strand.deleted',0);
                              })
                              ->join('sy',function($join){
                                    $join->on('sh_enrolledstud.syid','=','sy.id');
                              })
                              ->select(
                                    'sh_enrolledstud.syid',
                                    'sh_enrolledstud.sectionid',
                                    'sh_enrolledstud.strandid',
                                    'strandcode',
                                    'semid',
                                    'sh_enrolledstud.levelid',
                                    'levelname',
                                    'sectionname',
                                    'sydesc'
                              )
                              ->get();

            foreach($enrollment_1 as $item){
                  array_push($enrollment,$item);
            }

            return $enrollment;

      }

      public static function enrollment_grades(Request $request){

            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $enrollment = self::enrollment_record($request);



            $check = collect($enrollment)->where('syid',$syid)->values();

            if(count($check) == 0){
                  return array((object)[
                        'status'=>2,
                        'message'=>'No enrollment record found'
                  ]);
            }

            $gradelevel = $check[0]->levelid;
            $stud = $check[0]->levelid;
            $section = $check[0]->sectionid;
            $strand = null;

            if($gradelevel == 14 || $gradelevel == 15){
                  $strand = $enrollment[0]->strandid;
            }


            $grades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel,$studid,$syid,$strand,null,$section,true);

            if($gradelevel == 14 || $gradelevel == 15){
                  $subjects =  \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects_sh($gradelevel,$strand,null,$syid);
            }else {
                  $subjects =   \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel,$syid);
            }

            $grades = collect($grades)->sortBy('sort');

            $transferable_grades = DB::table('gradesdetail')
                                          ->join('grades',function($join) use($syid,$section){
                                                $join->on('gradesdetail.headerid','=','grades.id');
                                                $join->where('syid',$syid);
                                                $join->where('grades.deleted',0);
                                                $join->where('sectionid','!=',$section);
                                          })
                                          ->join('sections',function($join) use($syid,$section){
                                                $join->on('grades.sectionid','=','sections.id');
                                                $join->where('sections.deleted',0);
                                          })
                                          ->select(
                                                'sectionname',
                                                'headerid',
                                                'qg',
                                                'quarter',
                                                'subjid'
                                          )
                                          ->where('studid',$studid)
                                          ->get();

            return array((object)[
                  'status'=>1,
                  'message'=>'Grades Found',
                  'sf9'=>$grades,
                  'subjects'=>$subjects,
                  'transferable'=>$transferable_grades
            ]);

      }

      public function transfer_grades(Request $request){

            $studid = $request->get('studid');
            $headerid = $request->get('headerid');

        

            $grade_header_detial = DB::table('grades')
                                    ->where('id',$headerid)
                                    ->where('deleted',0)
                                    ->first();

            if(!isset($grade_header_detial->id)){
                  return array((object)[
                        'status'=>0,
                        'message'=>'Grades not found!'
                  ]);
            }

            

            if($grade_header_detial->levelid == 14 || $grade_header_detial->levelid == 15){
                  $check_enrollment = DB::table('sh_enrolledstud')
                                          ->where('studid',$studid)
                                          ->where('deleted',0)
                                          ->where('syid',$grade_header_detial->syid)
                                          ->where('semid',$grade_header_detial->semid)
                                          ->first();
            }else{
                  $check_enrollment = DB::table('enrolledstud')
                                          ->where('studid',$studid)
                                          ->where('deleted',0)
                                          ->where('syid',$grade_header_detial->syid)
                                          ->first();
            }

            if(!isset($check_enrollment->id)){
                  return array((object)[
                        'status'=>0,
                        'message'=>'No enrollment found'
                  ]);
            }

            $check_if_exist = DB::table('grades')
                                    ->where('syid',$grade_header_detial->syid)
                                    ->where('levelid',$grade_header_detial->levelid)
                                    ->where('sectionid',$check_enrollment->sectionid)
                                    ->where('deleted',0)
                                    ->where('subjid',$grade_header_detial->subjid)
                                    ->where('quarter',$grade_header_detial->quarter)
                                    ->where('semid',$grade_header_detial->semid)
                                    ->first();

            if(isset($check_if_exist->id)){

                  $check = DB::table('gradesdetail')
                              ->where('headerid',$check_if_exist->id)
                              ->where('studid',$studid)
                              ->take(1)
                              ->update([
                                    'studid'=>null,
                                    'studname'=>$studid
                              ]);

                  DB::table('gradesdetail')
                        ->where('headerid',$headerid)
                        ->where('studid',$studid)
                        ->take(1)
                        ->update([
                              'headerid'=>$check_if_exist->id,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            }else{

                  $gradeId = DB::table('grades')->insertGetId([
                        'syid'=>$grade_header_detial->syid,
                        'levelid'=>$check_enrollment->levelid,
                        'quarter'=>$grade_header_detial->quarter,
                        'sectionid'=>$check_enrollment->sectionid,
                        'subjid'=>$grade_header_detial->subjid,
                        'deleted'=>0,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'submitted'=>0,
                        'status'=>0,
                        'wwhr1'=>0,
                        'wwhr2'=>0,
                        'wwhr3'=>0,
                        'wwhr4'=>0,
                        'wwhr5'=>0,
                        'wwhr6'=>0,
                        'wwhr7'=>0,
                        'wwhr8'=>0,
                        'wwhr9'=>0,
                        'wwhr0'=>0,
                        'pthr1'=>0,
                        'pthr2'=>0,
                        'pthr3'=>0,
                        'pthr4'=>0,
                        'pthr5'=>0,
                        'pthr6'=>0,
                        'pthr7'=>0,
                        'pthr8'=>0,
                        'pthr9'=>0,
                        'pthr0'=>0,
                        'qahr1'=>0,
                        'semid'=>$grade_header_detial->semid
                    ]);

                  DB::table('gradesdetail')
                        ->where('id',$headerid)
                        ->where('studid',$studid)
                        ->take(1)
                        ->update([
                              'headerid'=>$gradeId,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            }

            return array((object)[
                  'status'=>1,
                  'message'=>'Grade Transfered'
            ]);
                        

      }
      
      
      public static function excel_student_profile(){

            $syid = 3;

            $sh_enrolled = DB::table('sh_enrolledstud')
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->join('sections',function($join){
                                    $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->where('sh_enrolledstud.deleted',0)
                              ->where('sh_enrolledstud.syid',$syid)
                              ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                              ->orderBy('lastname')
                              ->distinct('studid')
                              ->select(
                                    'lrn',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'sh_enrolledstud.sectionid',
                                    'gradelevel.levelname',
                                    'sections.sectionname',
                                    'dob',
                                    'gender'
                              )
                              ->get();


            $gshs_enrolled = DB::table('enrolledstud')
                        ->join('studinfo',function($join){
                              $join->on('enrolledstud.studid','=','studinfo.id');
                              $join->where('studinfo.deleted',0);
                        })
                        ->join('gradelevel',function($join){
                              $join->on('enrolledstud.levelid','=','gradelevel.id');
                              $join->where('gradelevel.deleted',0);
                        })
                        ->join('sections',function($join){
                              $join->on('enrolledstud.sectionid','=','sections.id');
                              $join->where('sections.deleted',0);
                        })
                        ->where('enrolledstud.deleted',0)
                        ->where('enrolledstud.syid',$syid)
                        ->whereIn('enrolledstud.studstatus',[1,2,4])
                        ->orderBy('lastname')
                        ->select(
                              'lrn',
                              'lastname',
                              'firstname',
                              'middlename',
                              'enrolledstud.sectionid',
                              'suffix',
                              'gradelevel.levelname',
                              'sections.sectionname',
                              'dob',
                              'gender'
                        )
                        ->get();

            $sections = DB::table('sections')
                              ->where('sections.deleted',0)
                              ->join('gradelevel',function($join){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->orderBy('sortid')
                              ->select(
                                    'sections.id',
                                    'levelid',
                                    'sections.sectionname',
                                    'gradelevel.levelname'
                              )
                              ->get();

           

           

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load("Learners Profile.xlsx");
            $schoolyear = DB::table('sy')->where('id',$syid)->first(); 

            foreach($sections as $item){
                  $students = array();

                  $sheet_name = str_replace('GRADE ','',$item->levelname).' '.self::get_avatar($item->sectionname);
                  $clonedWorksheet = clone $spreadsheet->getSheetByName('Sheet1');
                  $clonedWorksheet->setTitle($sheet_name);
                  $spreadsheet->addSheet($clonedWorksheet);

                  $spreadsheet->setActiveSheetIndexByName($sheet_name);
                  $sheet = $spreadsheet->getActiveSheet();

                  if($item->levelid == 14 || $item->levelid == 15){
                        $students = collect($sh_enrolled)->where('sectionid',$item->id)->values();
                  }else{
                        $students = collect($gshs_enrolled)->where('sectionid',$item->id)->values();
                  }

                  $row = 13;
                  foreach($students as $studitem){

                        $birthDate = $studitem->dob; // Your birthdate
                        $currentYear = explode("-",$schoolyear->sydesc)[0]; // Current Year
                        $birthYear = date('Y', strtotime($birthDate)); // Extracted Birth Year using strtotime and date() function
                        $age = $currentYear - $birthYear; // Current year minus birthyear

                        $sheet->setCellValue('B'.$row,$studitem->lrn);
                        $sheet->setCellValue('C'.$row,$studitem->lastname);
                        $sheet->setCellValue('D'.$row,$studitem->firstname);
                        $sheet->setCellValue('E'.$row,$studitem->middlename);
                        $sheet->setCellValue('F'.$row,$studitem->suffix);
                        $sheet->setCellValue('G'.$row,$studitem->levelname);
                        $sheet->setCellValue('H'.$row,$studitem->sectionname);
                        $sheet->setCellValue('I'.$row,\Carbon\Carbon::create($studitem->dob)->isoFormat('MM/DD/YYYY'));
                        $sheet->setCellValue('J'.$row,$age);
                        $sheet->setCellValue('K'.$row,$studitem->gender);
                        $row += 1 ;
                  }   
            }


            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="ENROLLMENT.xlsx"');
            $writer->save("php://output");
            exit();

      }
      
      public static function get_avatar($str){
            $acronym ='';
            $word = '';
            $words = preg_split("/(\s|\-|\.)/", $str);
            foreach($words as $w) {
                $acronym .= substr($w,0,1);
            }
            $word = $word . $acronym ;
            return $word;
      }
}
