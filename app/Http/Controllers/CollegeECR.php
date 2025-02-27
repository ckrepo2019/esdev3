<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DB;
use PDF;
use Session;


class CollegeECR extends \App\Http\Controllers\Controller
{
   

    public static function download_ecr(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $schedid = $request->get('schedid');
        $schoolinfo = DB::table('schoolinfo')->first();

        $terms = [
            'PRE-MIDTERM',
            'PRELIM',
            'MIDTERM',
            'CR MIDTERM SUMMARY',
            'SEMI-FINAL',
            'PRE-FINAL',
            'FINAL',
            'CR FINAL SUMMARY',
            'CR SEMESTER SUMMARY'
        ];

        if($schoolinfo->abbreviation == 'DCC'){
            $students = \App\Http\Controllers\CTController\CTController::enrolled_learners($syid,$semid,$schedid);
        }else{
            $students = \App\Http\Controllers\SuperAdminController\College\CollegeSectionsController::sched_enrolled_learners($request);
        }

        if(collect($students)->whereNotNull('studid')->count() == 0){
            return "No students found";
        }
     
        $students = DB::table('college_loadsubject')
                        ->where('schedid',$schedid)
                        ->whereIn('studid',collect($students)->pluck('studid'))
                        ->where('college_loadsubject.deleted',0)
                        ->join('studinfo',function($join){
                            $join->on('college_loadsubject.studid','=','studinfo.id');
                        })
                        ->select(
                            'college_loadsubject.studid as studid',
                            'college_loadsubject.syid as syid',
                            'college_loadsubject.semid as semid',
                            'college_loadsubject.sectionID as sectionid',
                            'college_loadsubject.subjectID as subjectid',
                            'college_loadsubject.schedid as schedid',
                            'studinfo.courseID as courseid',
                            'studinfo.lastname',
                            'studinfo.firstname',
                            'studinfo.middlename',
                            'studinfo.sid'
                        )
                        ->get();

        $headerinfo = Db::table('college_exlgrade')
                            ->where('syid',$syid)
                            ->where('semid',$semid)
                            ->where('schedid',$schedid)
                            ->where('deleted',0)
                            ->get();

        $headerdetail = Db::table('college_exlgrade_detail')
                            ->whereIn('headerid',collect($headerinfo)->pluck('id'))
                            ->whereIn('studid',collect($students)->pluck('studid'))
                            ->where('deleted',0)
                            ->get();

        $subjdesc = [];
        $schedtime = [];
        if($schoolinfo->abbreviation == 'DCC'){
            $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->first()
                        ->id;

            $subjdesc = \App\Http\Controllers\CTController\CTController::subjects($syid,$semid,$teacherid,$schedid);
            if (!empty($subjdesc)) {
                $subjdesc = collect($subjdesc)->where('id', $schedid)->values();
            }

            $teacherinfo = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->first();

            $semdesc = DB::table('semester')
                        ->where('id',$semid)
                        ->first();

            $sydesc = DB::table('sy')
                        ->where('id',$syid)
                        ->first();

            if (!empty($subjdesc)) {
                foreach($subjdesc as $item){
                    $item->firstname = $teacherinfo->firstname;
                    $item->lastname = $teacherinfo->lastname;
                    $item->middlename = $teacherinfo->middlename;
                    $item->suffix = $teacherinfo->suffix;
                    $item->title = $teacherinfo->title;
                    $item->acadtitle = $teacherinfo->acadtitle;
                    $item->semester = $semdesc->semester;
                    $item->sydesc = $sydesc->sydesc;
                }
            }

            $schedtime = Db::table('schedulecodingdetails')
                            ->leftJoin('days',function($join){
                                $join->on('schedulecodingdetails.day','=','days.id');
                            })
                            ->where('schedulecodingdetails.headerid',$schedid)
                            ->where('schedulecodingdetails.deleted',0)
                            ->select(
                                'schedulecodingdetails.timestart as stime',
                                'schedulecodingdetails.timeend as etime',
                                'schedulecodingdetails.day',
                                'days.description'
                            )
                            ->get();
        } else {
            $subjdesc = DB::table('college_classsched')
                        ->where('college_classsched.id',$schedid)
                        ->where('college_classsched.syid',$syid)
                        ->where('college_classsched.semesterID',$semid)
                        ->join('college_prospectus',function($join){
                            $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            $join->where('college_prospectus.deleted',0);
                        })
                        ->join('college_subjects',function($join){
                            $join->on('college_prospectus.subjectID','=','college_subjects.id');
                            $join->where('college_subjects.deleted',0);
                        })
                        ->join('college_instructor', function($join){
                            $join->on('college_classsched.id', '=', 'college_instructor.classschedid');
                        })
                        ->join('teacher',function($join){
                            $join->on('college_instructor.teacherid','=','teacher.id');
                        })

                        ->join('sy',function($join){
                            $join->on('college_classsched.syID','=','sy.id');
                        })
                        ->join('semester',function($join){
                            $join->on('college_classsched.semesterID','=','semester.id');
                        })
                        ->join('college_sections',function($join){
                            $join->on('college_classsched.sectionID','=','college_sections.id');
                        })
                        // ->join('college_year',function($join){
                        //     $join->on('college_sections.yearID','=','college_year.levelid');
                        // })

                        ->join('gradelevel',function($join){
                            $join->on('college_sections.yearID','=','gradelevel.id');
                        })
                        ->join('college_courses',function($join){
                            $join->on('college_sections.courseID','=','college_courses.id');
                        })
                        ->select(
                            'college_classsched.id as schedid',
                            'college_classsched.syID as syid',
                            'college_classsched.semesterID as semid',
                            'college_classsched.sectionID as sectionid',
                            'college_subjects.subjDesc',
                            'college_subjects.subjCode',
                            'teacher.lastname',
                            'teacher.firstname',
                            'teacher.middlename',
                            'teacher.suffix',
                            'teacher.id as teacherID',
                            'teacher.title',
                            'teacher.acadtitle',
                            'college_prospectus.id as subjectID',
                            'college_prospectus.lecunits',
                            'college_prospectus.labunits',
                            'sy.sydesc',
                            'semester.semester',
                            'college_sections.sectionDesc',
                            'gradelevel.levelname',
                            'college_courses.id as courseID',
                            'college_courses.courseabrv',
                            'college_courses.courseDesc'
                        )
                        ->get();

           

            $schedtime = DB::table('college_scheddetail')
                        ->where('headerid',$schedid)
                        ->where('deleted',0)
                        ->orderBy('day')
                        ->get();
        }

        $schedule = '';
        $time_start = '';
        $time_end = '';

        foreach($schedtime as $item){
            if($time_start == ''){
                $time_start = \Carbon\Carbon::create($item->stime)->isoFormat('hh:mm A');
            }
            if($time_end == ''){
                $time_end = \Carbon\Carbon::create($item->etime)->isoFormat('hh:mm A');
            }
            $temp_day = '';
            if($item->day == 1){
                $temp_day = 'Monday';
            }else if($item->day == 2){
                $temp_day = 'Tuesday';
            }else if($item->day == 3){
                $temp_day = 'Wednesday';
            }else if($item->day == 4){
                $temp_day = 'Thursday';
            }else if($item->day == 5){
                $temp_day = 'Friday';
            }else if($item->day == 6){
                $temp_day = 'Saturday';
            }else if($item->day == 7){
                $temp_day = 'Sunday';
            }
            if(!str_contains($schedule,$temp_day)){
                if($schedule == ''){
                    $schedule.= $temp_day;
                }else{
                    $schedule.='-'.$temp_day;
                }
            }
        }

        $daytimeinfo = array((object)[
            'schedule'=>$schedule,
            'time_start'=>$time_start,
            'time_end'=>$time_end
        ]);

        $instructor = '';
        $userid = auth()->user()->id;

        foreach($subjdesc as $item){
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            $temp_acadtitle = '';
            if(isset($item->middlename)){
                $temp_middle = $item->middlename[0].'.';
            }
            if(isset($item->title)){
                $temp_title = $item->title.'. ';
            }
            if(isset($item->suffix)){
                $temp_suffix = ', '.$item->suffix;
            }
            if(isset($item->acadtitle)){
                $temp_acadtitle = ', '.$item->acadtitle;
            }
            $instructor = $temp_title.$item->firstname.' '.$temp_middle.' '.$item->lastname.$temp_suffix.$temp_acadtitle;
        }

        foreach($terms as $term){

            $grade_sum = Db::table('college_exlgrade_sum')
                            ->where('schedid', $schedid )
                            ->where('deleted',0)
                            ->get();

            $checkheader = collect($headerinfo)
                                ->where('term',$term)
                                ->first();

            if(isset($checkheader)){
                $headerid = $checkheader->id;
                foreach($students as $student){

                    $checkstudent = collect($headerdetail)
                                        ->where('studid',$student->studid)
                                        ->where('headerid',$headerid)
                                        ->first();

                    if(!isset( $checkstudent)){
                        DB::table('college_exlgrade_detail')
                        ->insertGetID([
                            'studid'=>$student->studid,
                            'headerid'=>$headerid,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
                    }

                    $checkstudent = collect($grade_sum)
                                        ->where('studid',$student->studid)
                                        ->where('schedid',$schedid)
                                        ->first();

                    if(!isset( $checkstudent)){
                        DB::table('college_exlgrade_sum')
                            ->insertGetID([
                                'studid'=>$student->studid,
                                'schedid'=>$schedid,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                    }

                }
            }else{

                $headerid = DB::table('college_exlgrade')
                                ->insertGetId([
                                    'term'=>$term,
                                    'syid'=>$syid,
                                    'semid'=>$semid,
                                    'schedid'=>$schedid,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                ]);

                foreach($students as $student){
                    DB::table('college_exlgrade_detail')
                            ->insertGetID([
                                'studid'=>$student->studid,
                                'headerid'=>$headerid,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);

                    $checkstudent = collect($grade_sum)
                                        ->where('studid',$student->studid)
                                        ->where('schedid',$schedid)
                                        ->first();

                    if(!isset( $checkstudent)){
                        DB::table('college_exlgrade_sum')
                            ->insertGetID([
                                'studid'=>$student->studid,
                                'schedid'=>$schedid,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                    }
                }
            }
        }

        $headerinfo = Db::table('college_exlgrade')
                            ->where('syid',$syid)
                            ->where('semid',$semid)
                            ->where('schedid',$schedid)
                            ->where('deleted',0)
                            ->select('id','term')
                            ->get();

        $headerdetail = Db::table('college_exlgrade_detail')
                            ->whereIn('headerid',collect($headerinfo)->pluck('id'))
                            ->where('deleted',0)
                            ->get();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("College ECR_BCCV1.xlsx");

        $sheetNames = $spreadsheet->getSheetNames();

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        $headerinfo = Db::table('college_exlgrade')
                            ->where('syid',$syid)
                            ->where('semid',$semid)
                            ->where('schedid',$schedid)
                            ->where('deleted',0)
                            ->get();

        $headerdetail = Db::table('college_exlgrade_detail')
                            ->whereIn('headerid',collect($headerinfo)->pluck('id'))
                            ->where('deleted',0)
                            ->get();

        $gradesum = Db::table('college_exlgrade_sum')
                            ->where('schedid',$schedid)
                            ->where('deleted',0)
                            ->get();

        if($spreadsheet->sheetNameExists('BASIC INFO')){

            $sheet = $spreadsheet->setActiveSheetIndexByName('BASIC INFO');

            // Set class details
            if (!empty($subjdesc) && isset($subjdesc[0])) {
                $sheet->setCellValue('B2', $subjdesc[0]->courseabrv . ' ' . $subjdesc[0]->levelname . ' ' . $subjdesc[0]->sectionDesc);
                $sheet->setCellValue('A3', 'Course:');
                $sheet->setCellValue('B3', $subjdesc[0]->courseDesc);
                $sheet->setCellValue('A4', 'Semester:');
                $sheet->setCellValue('B4', $subjdesc[0]->semester);
                $sheet->setCellValue('A5', 'Subject:');
                $sheet->setCellValue('B5', $subjdesc[0]->subjDesc);
                $sheet->setCellValue('A6', 'Instructor:');
                $sheet->setCellValue('B6', $instructor);
                $sheet->setCellValue('A7', 'Schedule:');
                $sheet->setCellValue('B7', $daytimeinfo[0]->schedule);
                $sheet->setCellValue('A8', 'Time Start:');
                $sheet->setCellValue('B8', $daytimeinfo[0]->time_start);
                $sheet->setCellValue('A9', 'Time End:');
                $sheet->setCellValue('B9', $daytimeinfo[0]->time_end);

                $sheet->setCellValue('B10', 'SID');

                //schoolinfo
                $sheet->setCellValue('L2', $schoolinfo->schoolname);
                $sheet->setCellValue('L3', $schoolinfo->address);
                // $sheet->setCellValue('C11', $subjdesc[0]->courseID);
                // $sheet->setCellValue('D11', $subjdesc[0]->subjectID);
                // $sheet->setCellValue('G11', $subjdesc[0]->schedid);
                // $sheet->setCellValue('H11', $subjdesc[0]->syid);
                // $sheet->setCellValue('I11', $subjdesc[0]->semid);
                // $sheet->setCellValue('J11', $subjdesc[0]->sectionid);

                // Set student list header
                $row = 10;
                // $sheet->setCellValue('A'.$row, 'Student List');
                $row++;
                $students = $students->sortBy('lastname');
                // Add student data
                foreach($students as $studitem){
                   
                    $fullname = trim($studitem->lastname . ', ' . $studitem->firstname . ' ' . $studitem->middlename);
                    $sheet->setCellValue('A'.$row, $fullname);
                    $sheet->setCellValue('B'.$row, $studitem->sid);
                    $sheet->setCellValue('C'.$row, $studitem->courseid);
                    $sheet->setCellValue('D'.$row, $studitem->subjectid);
                    $sheet->setCellValue('G'.$row, $studitem->schedid);
                    $sheet->setCellValue('H'.$row, $studitem->syid);
                    $sheet->setCellValue('I'.$row, $studitem->semid);
                    $sheet->setCellValue('J'.$row, $studitem->sectionid);
                    $sheet->setCellValue('K'.$row, $userid);
                    $row++;
                }

                // Hide unused rows
                for($x = $row; $x <= 69; $x++){
                    $sheet->getRowDimension($x)->setVisible(false);
                }

                // Apply formatting
                $sheet->getStyle('A2:D2')->getFont()->setBold(true);
                $sheet->getStyle('B10')->getFont()->setBold(true);
                // $sheet->getStyle('A11')->getFont()->setBold(true);
                $sheet->getColumnDimension('A')->setAutoSize(true);
            }
        }

        if($spreadsheet->sheetNameExists('PRELIM')){

        }

        try{
            ob_end_clean();
        }catch(\Exception $e){}

        if (!empty($subjdesc) && isset($subjdesc[0])) {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $schoolinfo->abbreviation . '_' . $subjdesc[0]->courseabrv . '_' . $subjdesc[0]->levelname . '_' . $subjdesc[0]->sectionDesc . '_' . str_replace(["\r", "\n"], ' ', $subjdesc[0]->subjDesc) . ' ECR.xlsx"');
            $writer->save("php://output");
            // header('Content-Disposition: attachment; filename="'. $schoolinfo->abbreviation . '_' . $subjdesc[0]->courseabrv . '_' 
            // . $subjdesc[0]->yearDesc . '_' . $subjdesc[0]->sectionDesc . '_' . $subjdesc[0]->subjDesc . ' ECR.xlsx"');
            // $writer->save("php://output");
            // header('Content-Disposition: attachment; filename="BCC_College _'. '_' . $subjdesc[0]->courseabrv . '_' 
            // . $subjdesc[0]->yearDesc . '_' . $subjdesc[0]->sectionDesc . '_' . $subjdesc[0]->subjDesc . ' ECR.xlsx"');
            // $writer->save("php://output");
        }
        else{
            return "No sheet found";
        }
       
        exit();
    }
    
    // public static function download_ecr(Request $request){

    //     $syid = $request->get('syid');
    //     $semid = $request->get('semid');
    //     $schedid = $request->get('schedid');
    //     $schoolinfo = DB::table('schoolinfo')->first();

    //     $terms = [
    //         'PRE-MIDTERM',
    //         'PRELIM',
    //         'MIDTERM',
    //         'CR MIDTERM SUMMARY',
    //         'SEMI-FINAL',
    //         'PRE-FINAL',
    //         'FINAL',
    //         'CR FINAL SUMMARY',
    //         'CR SEMESTER SUMMARY'
    //     ];

    //     if($schoolinfo->abbreviation == 'DCC'){
    //         $students = \App\Http\Controllers\CTController\CTController::enrolled_learners($syid,$semid,$schedid);
    //     }else{
    //         $students = \App\Http\Controllers\SuperAdminController\College\CollegeSectionsController::sched_enrolled_learners($request);
    //     }


    //     if(collect($students)->whereNotNull('studid')->count() == 0){
    //         return "No students found";
    //     }
     
    //     $students = DB::table('college_studsched')
    //                     ->where('schedid',$schedid)
    //                     ->whereIn('studid',collect($students)->pluck('studid'))
    //                     ->where('college_studsched.deleted',0)
    //                     ->join('studinfo',function($join){
    //                         $join->on('college_studsched.studid','=','studinfo.id');
    //                     })
    //                     ->select(
    //                         'college_studsched.*',
    //                         'studinfo.lastname',
    //                         'studinfo.firstname',
    //                         'studinfo.middlename'
    //                     )
    //                     ->get();

    //     $headerinfo = Db::table('college_exlgrade')
    //                         ->where('syid',$syid)
    //                         ->where('semid',$semid)
    //                         ->where('schedid',$schedid)
    //                         ->where('semid',$semid)
    //                         ->where('deleted',0)
    //                         ->get();

    //     $headerdetail = Db::table('college_exlgrade_detail')
    //                         ->whereIn('headerid',collect($headerinfo)->pluck('id'))
    //                         ->whereIn('studid',collect($students)->pluck('studid'))
    //                         ->where('deleted',0)
    //                         ->get();


        
    //     if($schoolinfo->abbreviation == 'DCC'){
			
	// 		$teacherid = DB::table('teacher')
    //                     ->where('tid',auth()->user()->email)
    //                     ->first()
    //                     ->id;

						
    //         $subjdesc = \App\Http\Controllers\CTController\CTController::subjects($syid,$semid,$teacherid,$schedid);
	// 		$subjdesc = collect($subjdesc)->where('id',$schedid)->values();
		
	// 		$teacherinfo = DB::table('teacher')
    //                     ->where('tid',auth()->user()->email)
    //                     ->first();
						
	// 		$semdesc = DB::table('semester')
	// 						->where('id',$semid)
	// 						->first();
							
	// 		$sydesc = DB::table('sy')
	// 						->where('id',$syid)
	// 						->first();		
						
	// 		foreach($subjdesc as $item){
	// 			$item->firstname = $teacherinfo->firstname;
	// 			$item->lastname = $teacherinfo->lastname;
	// 			$item->middlename = $teacherinfo->middlename;
	// 			$item->suffix = $teacherinfo->suffix;
	// 			$item->title = $teacherinfo->title;
	// 			$item->acadtitle = $teacherinfo->acadtitle;
	// 			$item->semester = $semdesc->semester;
	// 			$item->sydesc = $sydesc->sydesc;
	// 		}
			
	// 		$schedtime = Db::table('schedulecodingdetails')
    //                         ->leftJoin('days',function($join){
    //                             $join->on('schedulecodingdetails.day','=','days.id');
    //                         })
    //                         ->where('schedulecodingdetails.headerid',$schedid)
    //                         ->where('schedulecodingdetails.deleted',0)
    //                         ->select(
    //                             'schedulecodingdetails.timestart as stime',
    //                             'schedulecodingdetails.timeend as etime',
    //                             'schedulecodingdetails.day',
    //                             'days.description'
    //                         )
    //                         ->get();
			
    //     }else{
    //         $subjdesc = DB::table('college_classsched')
    //                     ->where('college_classsched.id',$schedid)
    //                     ->where('college_classsched.syid',$syid)
    //                     ->where('college_classsched.semesterID',$semid)
    //                     ->join('college_prospectus',function($join){
    //                         $join->on('college_classsched.subjectID','=','college_prospectus.id');
    //                         $join->where('college_prospectus.deleted',0);
    //                     })
    //                     ->join('teacher',function($join){
    //                         $join->on('college_classsched.teacherid','=','teacher.id');
    //                     })
    //                     ->join('sy',function($join){
    //                         $join->on('college_classsched.syid','=','sy.id');
    //                     })
    //                     ->join('semester',function($join){
    //                         $join->on('college_classsched.semesterID','=','semester.id');
    //                     })
    //                     ->select(
    //                         'subjDesc',
    //                         'subjCode',
    //                         'lastname',
    //                         'firstname',
    //                         'middlename',
    //                         'suffix',
    //                         'teacherid',
    //                         'title',
    //                         'acadtitle',
    //                         'lecunits',
    //                         'labunits',
    //                         'sydesc',
    //                         'semester'
    //                     )
    //                     ->get();

    //         $schedtime = DB::table('college_scheddetail')
    //                     ->where('headerid',$schedid)
    //                     ->where('deleted',0)
    //                     ->orderBy('day')
    //                     ->get();

    //     }
        
     

    //     // return $schedtime;
    //     $schedule = '';
    //     $time_start = '';
    //     $time_end = '';
       
    //     foreach($schedtime as $item){
    //         if($time_start == ''){
    //             $time_start = \Carbon\Carbon::create($item->stime)->isoFormat('hh:mm A');
    //         }
    //         if($time_end == ''){
    //             $time_end = \Carbon\Carbon::create($item->etime)->isoFormat('hh:mm A');
    //         }
    //         $temp_day = '';
    //         if($item->day == 1){
    //             $temp_day = 'Monday';
    //         }else if($item->day == 2){
    //             $temp_day = 'Tuesday';
    //         }else if($item->day == 3){
    //             $temp_day = 'Wednesday';
    //         }else if($item->day == 4){
    //             $temp_day = 'Thursday';
    //         }else if($item->day == 5){
    //             $temp_day = 'Friday';
    //         }else if($item->day == 6){
    //             $temp_day = 'Saturday';
    //         }else if($item->day == 7){
    //             $temp_day = 'Sunday';
    //         }
    //         if(!str_contains($schedule,$temp_day)){
    //             if($schedule == ''){
    //                 $schedule.= $temp_day;
    //             }else{
    //                 $schedule.='-'.$temp_day;
    //             }
    //         }
    //     }

    //     $daytimeinfo = array((object)[
    //         'schedule'=>$schedule,
    //         'time_start'=>$time_start,
    //         'time_end'=>$time_end
    //     ]);

    //     $instructor = '';

    //     foreach($subjdesc as $item){
    //         $temp_middle = '';
    //         $temp_suffix = '';
    //         $temp_title = '';
    //         $temp_acadtitle = '';
    //         if(isset($item->middlename)){
    //             $temp_middle = $item->middlename[0].'.';
    //         }
    //         if(isset($item->title)){
    //             $temp_title = $item->title.'. ';
    //         }
    //         if(isset($item->suffix)){
    //             $temp_suffix = ', '.$item->suffix;
    //         }
    //         if(isset($item->acadtitle)){
    //             $temp_acadtitle = ', '.$item->acadtitle;
    //         }
    //         $instructor = $temp_title.$item->firstname.' '.$temp_middle.' '.$item->lastname.$temp_suffix.$temp_acadtitle;
    //     }

        

    //     foreach($terms as $term){

            

    //         $grade_sum = Db::table('college_exlgrade_sum')
    //                         ->where('schedid', $schedid )
    //                         ->where('deleted',0)
    //                         ->get();

    //         $checkheader = collect($headerinfo)
    //                             ->where('term',$term)
    //                             ->first();


    //         if(isset($checkheader)){
    //             $headerid = $checkheader->id;
    //             foreach($students as $student){

    //                 $checkstudent = collect($headerdetail)
    //                                     ->where('studid',$student->studid)
    //                                     ->where('headerid',$headerid)
    //                                     ->first();

    //                 if(!isset( $checkstudent)){
    //                     DB::table('college_exlgrade_detail')
    //                     ->insertGetID([
    //                         'studid'=>$student->studid,
    //                         'headerid'=>$headerid,
    //                         'createdby'=>auth()->user()->id,
    //                         'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //                     ]);
    //                 }

    //                 $checkstudent = collect($grade_sum)
    //                                     ->where('studid',$student->studid)
    //                                     ->where('schedid',$schedid)
    //                                     ->first();

    //                 if(!isset( $checkstudent)){
    //                     DB::table('college_exlgrade_sum')
    //                         ->insertGetID([
    //                             'studid'=>$student->studid,
    //                             'schedid'=>$schedid,
    //                             'createdby'=>auth()->user()->id,
    //                             'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //                         ]);
    //                 }
                  
    //             }
    //         }else{

    //             $headerid = DB::table('college_exlgrade')
    //                             ->insertGetId([
    //                                 'term'=>$term,
    //                                 'syid'=>$syid,
    //                                 'semid'=>$semid,
    //                                 'schedid'=>$schedid,
    //                                 'createdby'=>auth()->user()->id,
    //                                 'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //                             ]);

    //             foreach($students as $student){
    //                 DB::table('college_exlgrade_detail')
    //                         ->insertGetID([
    //                             'studid'=>$student->studid,
    //                             'headerid'=>$headerid,
    //                             'createdby'=>auth()->user()->id,
    //                             'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //                         ]);

    //                 $checkstudent = collect($grade_sum)
    //                                     ->where('studid',$student->studid)
    //                                     ->where('schedid',$schedid)
    //                                     ->first();

    //                 if(!isset( $checkstudent)){
    //                     DB::table('college_exlgrade_sum')
    //                         ->insertGetID([
    //                             'studid'=>$student->studid,
    //                             'schedid'=>$schedid,
    //                             'createdby'=>auth()->user()->id,
    //                             'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //                         ]);
    //                 }
    //             }
    //         }
    //     }
        
    //     $headerinfo = Db::table('college_exlgrade')
    //                         ->where('syid',$syid)
    //                         ->where('semid',$semid)
    //                         ->where('schedid',$schedid)
    //                         ->where('semid',$semid)
    //                         ->where('deleted',0)
    //                         ->select('id','term')
    //                         ->get();

    //                         return $headerinfo;

    //     $headerdetail = Db::table('college_exlgrade_detail')
    //                         ->whereIn('headerid',collect($headerinfo)->pluck('id'))
    //                         ->where('deleted',0)
    //                         ->get();

    //     $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //     $spreadsheet = $reader->load("College ECR.xlsx");

    //     $sheetNames = $spreadsheet->getSheetNames();
    //     // return $sheetNames;

    //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        
    //     $headerinfo = Db::table('college_exlgrade')
    //                         ->where('syid',$syid)
    //                         ->where('semid',$semid)
    //                         ->where('schedid',$schedid)
    //                         ->where('semid',$semid)
    //                         ->where('deleted',0)
    //                         ->get();

    //     $headerdetail = Db::table('college_exlgrade_detail')
    //                         ->whereIn('headerid',collect($headerinfo)->pluck('id'))
    //                         ->where('deleted',0)
    //                         ->get();

    //     $gradesum = Db::table('college_exlgrade_sum')
    //                         ->where('schedid',$schedid)
    //                         ->where('deleted',0)
    //                         ->get();

    //     return $headerinfo;

    //     foreach($headerinfo as $item){

    //         if($item->term != 'CR MIDTERM SUMMARY' && $item->term != 'CR FINAL SUMMARY' && $item->term != 'CR SEMESTER SUMMARY'){

                

    //             $row = 10;
    //             if(!$spreadsheet->sheetNameExists($item->term)){
    //                 continue;
    //             }
    //             $sheet = $spreadsheet->setActiveSheetIndexByName($item->term);

    //             $sheet->setCellValue('B65',$instructor);
    //             $sheet->setCellValue('A3','Subject Code: '.$subjdesc[0]->subjCode);
    //             $sheet->setCellValue('A4','Subject Description: '.$subjdesc[0]->subjDesc);

    //             $sheet->setCellValue('F'.$row,$item->f1 == 0 ? $item->f1 != 0 ? 0 : "" : $item->f1);
    //             $sheet->setCellValue('G'.$row,$item->f2 == 0 ? $item->f2 != 0 ? 0 : "" : $item->f2);
    //             $sheet->setCellValue('H'.$row,$item->f3 == 0 ? $item->f3 != 0 ? 0 : "" : $item->f3);
    //             $sheet->setCellValue('I'.$row,$item->f4 == 0 ? $item->f4 != 0 ? 0 : "" : $item->f4);
    //             $sheet->setCellValue('J'.$row,$item->f5 == 0 ? $item->f5 != 0 ? 0 : "" : $item->f5);
    //             $sheet->setCellValue('K'.$row,$item->f6 == 0 ? $item->f6 != 0 ? 0 : "" : $item->f6);

    //             $sheet->setCellValue('N'.$row,$item->s1 == 0 ? $item->s1 != 0 ? 0 : "" : $item->s1);
    //             $sheet->setCellValue('O'.$row,$item->s2 == 0 ? $item->s2 != 0 ? 0 : "" : $item->s2);
    //             $sheet->setCellValue('P'.$row,$item->s3 == 0 ? $item->s3 != 0 ? 0 : "" : $item->s3);
    //             $sheet->setCellValue('Q'.$row,$item->s4 == 0 ? $item->s4 != 0 ? 0 : "" : $item->s4);
    //             $sheet->setCellValue('R'.$row,$item->s5 == 0 ? $item->s5 != 0 ? 0 : "" : $item->s5);
    //             $sheet->setCellValue('S'.$row,$item->s6 == 0 ? $item->s6 != 0 ? 0 : "" : $item->s6);

    //             $sheet->setCellValue('X'.$row,$item->ur1 == 0 ? $item->ur1 != 0 ? 0 : "" : $item->ur1);
    //             $sheet->setCellValue('Y'.$row,$item->ur2 == 0 ? $item->ur2 != 0 ? 0 : "" : $item->ur2);
    //             $sheet->setCellValue('Z'.$row,$item->ur3 == 0 ? $item->ur3 != 0 ? 0 : "" : $item->ur3);

    //             $sheet->setCellValue('AC'.$row,$item->tr1 == 0 ? $item->tr1 != 0 ? 0 : "" : $item->tr1);
    //             $sheet->setCellValue('AD'.$row,$item->tr2 == 0 ? $item->tr2 != 0 ? 0 : "" : $item->tr2);
    //             $sheet->setCellValue('AE'.$row,$item->tr3 == 0 ? $item->tr3 != 0 ? 0 : "" : $item->tr3);

    //             $sheet->setCellValue('AJ'.$row,$item->exam1 == 0 ? $item->exam1 != 0 ? 0 : "" : $item->exam1);

    //             $sheet->setCellValue('AZ1',$item->id);
    //             $sheet->setCellValue('AZ2',$item->schedid);

    //             $gradedetail = collect($headerdetail)
    //                                 ->where('headerid',$item->id)
    //                                 ->where('deleted',0)
    //                                 ->values();

    //             DB::table('college_exlgrade_logs')
    //                                 ->insert([
    //                                     'headerid'=>$item->id,
    //                                     'status'=>'DOWNLOAD',
    //                                     'createdby'=>auth()->user()->id,
    //                                     'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //                                 ]);

    //             foreach( $students as $studitem){

    //                 $row += 1;

    //                 $gradedetailitem = collect($headerdetail)
    //                                 ->where('headerid',$item->id)
    //                                 ->where('studid',$studitem->studid)
    //                                 ->where('deleted',0)
    //                                 ->first();

    //                 $sheet->setCellValue('B'.$row,$studitem->lastname);
    //                 $sheet->setCellValue('C'.$row,$studitem->firstname);
    //                 $sheet->setCellValue('D'.$row,$studitem->middlename);

    //                 $sheet->setCellValue('F'.$row,$gradedetailitem->f1 == 0 ? $gradedetailitem->f1 != 0 ? 0 : "" : $gradedetailitem->f1);
    //                 $sheet->setCellValue('G'.$row,$gradedetailitem->f2 == 0 ? $gradedetailitem->f2 != 0 ? 0 : "" : $gradedetailitem->f2);
    //                 $sheet->setCellValue('H'.$row,$gradedetailitem->f3 == 0 ? $gradedetailitem->f3 != 0 ? 0 : "" : $gradedetailitem->f3);
    //                 $sheet->setCellValue('I'.$row,$gradedetailitem->f4 == 0 ? $gradedetailitem->f4 != 0 ? 0 : "" : $gradedetailitem->f4);
    //                 $sheet->setCellValue('J'.$row,$gradedetailitem->f5 == 0 ? $gradedetailitem->f5 != 0 ? 0 : "" : $gradedetailitem->f5);
    //                 $sheet->setCellValue('K'.$row,$gradedetailitem->f6 == 0 ? $gradedetailitem->f6 != 0 ? 0 : "" : $gradedetailitem->f6);
        
    //                 $sheet->setCellValue('N'.$row,$gradedetailitem->s1 == 0 ? $gradedetailitem->s1 != 0 ? 0 : "" : $gradedetailitem->s1);
    //                 $sheet->setCellValue('O'.$row,$gradedetailitem->s2 == 0 ? $gradedetailitem->s2 != 0 ? 0 : "" : $gradedetailitem->s2);
    //                 $sheet->setCellValue('P'.$row,$gradedetailitem->s3 == 0 ? $gradedetailitem->s3 != 0 ? 0 : "" : $gradedetailitem->s3);
    //                 $sheet->setCellValue('Q'.$row,$gradedetailitem->s4 == 0 ? $gradedetailitem->s4 != 0 ? 0 : "" : $gradedetailitem->s4);
    //                 $sheet->setCellValue('R'.$row,$gradedetailitem->s5 == 0 ? $gradedetailitem->s5 != 0 ? 0 : "" : $gradedetailitem->s5);
    //                 $sheet->setCellValue('S'.$row,$gradedetailitem->s6 == 0 ? $gradedetailitem->s6 != 0 ? 0 : "" : $gradedetailitem->s6);
        
    //                 $sheet->setCellValue('X'.$row,$gradedetailitem->ur1 == 0 ? $gradedetailitem->ur1 != 0 ? 0 : "" : $gradedetailitem->ur1);
    //                 $sheet->setCellValue('Y'.$row,$gradedetailitem->ur2 == 0 ? $gradedetailitem->ur2 != 0 ? 0 : "" : $gradedetailitem->ur2);
    //                 $sheet->setCellValue('Z'.$row,$gradedetailitem->ur3 == 0 ? $gradedetailitem->ur3 != 0 ? 0 : "" : $gradedetailitem->ur3);
        
    //                 $sheet->setCellValue('AC'.$row,$gradedetailitem->tr1 == 0 ? $gradedetailitem->tr1 != 0 ? 0 : "" : $gradedetailitem->tr1);
    //                 $sheet->setCellValue('AD'.$row,$gradedetailitem->tr2 == 0 ? $gradedetailitem->tr2 != 0 ? 0 : "" : $gradedetailitem->tr2);
    //                 $sheet->setCellValue('AE'.$row,$gradedetailitem->tr3 == 0 ? $gradedetailitem->tr3 != 0 ? 0 : "" : $gradedetailitem->tr3);

    //                 $sheet->setCellValue('AJ'.$row,$gradedetailitem->prelim == 0 ? $gradedetailitem->prelim != 0 ? 0 : "" : $gradedetailitem->prelim);

    //                 $sheet->setCellValue('AZ'.$row,$gradedetailitem->id);
    //                 $sheet->setCellValue('BA'.$row,$gradedetailitem->studid);
    //                 $sheet->setCellValue('BB'.$row,$gradedetailitem->headerid);
    //             }

    //             for($x=$row ; $x <= 58 ; $x++){
    //                 $sheet->getRowDimension($x)->setVisible(false);
    //             }
    //         }
    //     }

    //     $row = 20;
    //     if($spreadsheet->sheetNameExists('CR MIDTERM SUMMARY')){
            
    //         $sheet = $spreadsheet->setActiveSheetIndexByName('CR MIDTERM SUMMARY');
    
    //         $sheet->setCellValue('D13',$subjdesc[0]->subjCode);
    //         $sheet->setCellValue('D14',$subjdesc[0]->subjDesc);
    //         $sheet->setCellValue('D15',$subjdesc[0]->lecunits + $subjdesc[0]->labunits );
    //         $sheet->setCellValue('R12',$daytimeinfo[0]->schedule);
    //         $sheet->setCellValue('R13',$daytimeinfo[0]->time_start);
    //         $sheet->setCellValue('U13',$daytimeinfo[0]->time_end);
    //         $sheet->setCellValue('R14',$subjdesc[0]->semester);
    //         $sheet->setCellValue('R15',$subjdesc[0]->sydesc);
    //         $sheet->setCellValue('B74',$instructor);
    
    //         foreach( $students as $studitem){
    //             $gradedetailitem = collect($gradesum)
    //                                     ->where('studid',$studitem->studid)
    //                                     ->where('deleted',0)
    //                                     ->first();
    
    //             $sheet->setCellValue('B'.$row,$studitem->lastname);
    //             $sheet->setCellValue('C'.$row,$studitem->firstname);
    //             $sheet->setCellValue('D'.$row,$studitem->middlename);
    
    //             $sheet->setCellValue('AZ'.$row,$gradedetailitem->id);
    //             $sheet->setCellValue('BA'.$row,$gradedetailitem->studid);
    //             $sheet->setCellValue('BB'.$row,$gradedetailitem->schedid);
    //             $row += 1;
    //         }
    
    //         for($x=$row ; $x <= 69 ; $x++){
    //             $sheet->getRowDimension($x)->setVisible(false);
    //         }
            
    //     }

    //     $row = 20;
    //     if($spreadsheet->sheetNameExists('CR FINAL SUMMARY')){

    //         $sheet = $spreadsheet->setActiveSheetIndexByName('CR FINAL SUMMARY');

    //         $sheet->setCellValue('D13',$subjdesc[0]->subjCode);
    //         $sheet->setCellValue('D14',$subjdesc[0]->subjDesc);
    //         $sheet->setCellValue('D15',$subjdesc[0]->lecunits + $subjdesc[0]->labunits );
    //         $sheet->setCellValue('R12',$daytimeinfo[0]->schedule);
    //         $sheet->setCellValue('R13',$daytimeinfo[0]->time_start);
    //         $sheet->setCellValue('U13',$daytimeinfo[0]->time_end);
    //         $sheet->setCellValue('R14',$subjdesc[0]->semester);
    //         $sheet->setCellValue('R15',$subjdesc[0]->sydesc);
    //         $sheet->setCellValue('B74',$instructor);

    //         foreach( $students as $studitem){
            
    //             $gradedetailitem = collect($gradesum)
    //                                     ->where('studid',$studitem->studid)
    //                                     ->where('deleted',0)
    //                                     ->first();

    //             $sheet->setCellValue('B'.$row,$studitem->lastname);
    //             $sheet->setCellValue('C'.$row,$studitem->firstname);
    //             $sheet->setCellValue('D'.$row,$studitem->middlename);
                            

    //             $sheet->setCellValue('AZ'.$row,$gradedetailitem->id);
    //             $sheet->setCellValue('BA'.$row,$gradedetailitem->studid);
    //             $sheet->setCellValue('BB'.$row,$gradedetailitem->schedid);
    //             $row += 1;
    //         }

    //         for($x=$row ; $x <= 69 ; $x++){
    //             $sheet->getRowDimension($x)->setVisible(false);
    //         }

    //     }

    //     $row = 23;
    //     if($spreadsheet->sheetNameExists('CR SEMESTER SUMMARY')){

    //         $sheet = $spreadsheet->setActiveSheetIndexByName('CR SEMESTER SUMMARY');
    
    //         $sheet->setCellValue('D13',$subjdesc[0]->subjCode);
    //         $sheet->setCellValue('D14',$subjdesc[0]->subjDesc);
    //         $sheet->setCellValue('D15',$subjdesc[0]->lecunits + $subjdesc[0]->labunits );
    //         $sheet->setCellValue('D16',$daytimeinfo[0]->schedule);
    //         $sheet->setCellValue('D17',$daytimeinfo[0]->time_start);
    //         $sheet->setCellValue('G17',$daytimeinfo[0]->time_end);
    //         $sheet->setCellValue('D18',$subjdesc[0]->semester);
    //         $sheet->setCellValue('D19',$subjdesc[0]->sydesc);
    //         $sheet->setCellValue('A77',$instructor);
        
    //         foreach( $students as $studitem){
            
    //             $gradedetailitem = collect($gradesum)
    //                                     ->where('studid',$studitem->studid)
    //                                     ->where('deleted',0)
    //                                     ->first();
        
    //             $sheet->setCellValue('B'.$row,$studitem->lastname);
    //             $sheet->setCellValue('C'.$row,$studitem->firstname);
    //             $sheet->setCellValue('D'.$row,$studitem->middlename);
                            
        
    //             $sheet->setCellValue('AZ'.$row,$gradedetailitem->id);
    //             $sheet->setCellValue('BA'.$row,$gradedetailitem->studid);
    //             $sheet->setCellValue('BB'.$row,$gradedetailitem->schedid);
    //             $row += 1;
    //         }
            
    //         for($x=$row ; $x <= 72 ; $x++){
    //             $sheet->getRowDimension($x)->setVisible(false);
    //         }
      
    //     }



    //     try{
    //         ob_end_clean();
    //     }catch(\Exception $e){}

    //     header('Content-Type: application/vnd.ms-excel');
    //     header('Content-Disposition: attachment; filename="College ECR.xlsx"');
    //     $writer->save("php://output");
    //     exit();
    // }
      
    public static function upload_ecr(Request $request){
        $term = $request->get('input_term');
        $path = $request->file('input_ecr')->getRealPath();
        $start_column = $request->get('input_coordinates');
            
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($path);

        if($spreadsheet->sheetNameExists($term)){
            $sheet = $spreadsheet->setActiveSheetIndexByName($term);
            
            // Parse column and row from coordinates
            preg_match('/([A-Z]+)(\d+)/', $start_column, $matches);
            $column = $matches[1];
            $startRow = intval($matches[2]);

            // Get all student grades and sids starting from the specified coordinates
            $grades = [];
            $currentRow = $startRow;
            while(true) {
                $gradeCell = $sheet->getCell($column . $currentRow);
                $sidCell = $sheet->getCell('AJ' . $currentRow); 
                $courseCell = $sheet->getCell('AK' . $currentRow );
                $subjectCell = $sheet->getCell('AL' . $currentRow );
                $schedCell = $sheet->getCell('AM' . $currentRow );
                $syidCell = $sheet->getCell('AN' . $currentRow );
                $semidCell = $sheet->getCell('AO' . $currentRow );
                $sectionidCell = $sheet->getCell('AP' . $currentRow );
                $useridCell = $sheet->getCell('AQ' . $currentRow );
                $fgcell = $sheet->getCell('AR' . $currentRow );
                $fgremarkscell = $sheet->getCell('AS' . $currentRow );

                $gradeValue = round($gradeCell->getOldCalculatedValue(), 2);
                $sidValue = $sidCell->getOldCalculatedValue();
                $courseValue = $courseCell->getOldCalculatedValue();
                $subjectValue = $subjectCell->getOldCalculatedValue();
                $schedValue = $schedCell->getOldCalculatedValue();
                $syidValue = $syidCell->getOldCalculatedValue();
                $semidValue = $semidCell->getOldCalculatedValue();
                $sectionidValue = $sectionidCell->getOldCalculatedValue();
                $useridValue = $useridCell->getOldCalculatedValue();
                $fgValue = round($fgcell->getOldCalculatedValue(), 2);
                $fgremarksValue = $fgremarkscell->getOldCalculatedValue();
                
                // Stop if we hit an empty cell for SID or Grade
                // if(empty($gradeValue) || empty($sidValue)) {
                //     break;
                // }

                if(empty($gradeValue) || empty($sidValue)) {
                    // $currentRow++;
                    break;
                }


                $grades[] = [
                    'sid' => $sidValue,
                    'grade' => $gradeValue,
                    'course' => $courseValue,
                    'subject' => $subjectValue,
                    'sched' => $schedValue,
                    'syid' => $syidValue,
                    'semid' => $semidValue,
                    'sectionid' => $sectionidValue,
                    'userid' => $useridValue,
                    'fg' => $fgValue,
                    'fgremarks' => $fgremarksValue
                ];

                $currentRow++;
            }

            // Update grades in database based on term
            foreach($grades as $gradeData) {
                switch($term) {
                    case 'PRELIM':
                        $existingRecord = DB::table('college_studentprospectus')
                            ->where('studid', $gradeData['sid'])
                            ->where('syid', $gradeData['syid'])
                            ->where('semid', $gradeData['semid'])
                            ->where('sectionid', $gradeData['sectionid'])
                            ->where('schedid', $gradeData['sched'])
                            ->first();

                            if ($existingRecord) {
                                $updateData = [
                                    'prelim_excel_status' => 1,
                                    'prelemgrade' => $gradeData['grade'],
                                    'courseID' => $gradeData['course'],
                                    'prospectusID' => $gradeData['subject'],
                                    'schedid' => $gradeData['sched'],
                                    'syid' => $gradeData['syid'],
                                    'semid' => $gradeData['semid'],
                                    'sectionid' => $gradeData['sectionid'],
                                    'updatedby' => $gradeData['userid'],
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ];
        
                                $existingGrades_database = DB::table('college_studentprospectus')
                                    ->where('studid', $gradeData['sid'])
                                    ->where('syid', $gradeData['syid'])
                                    ->where('semid', $gradeData['semid'])
                                    ->where('sectionid', $gradeData['sectionid'])
                                    ->where('schedid', $gradeData['sched'])
                                    ->first(['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade']);
                                // if (!empty($existingGrades_database->prelemgrade)) {
                            if (!empty($existingGrades_database->midtermgrade) && !empty($existingGrades_database->finalgrade)) {
                                    $updateData['fg'] = $gradeData['fg'];
                                    $updateData['fgremarks'] = $gradeData['fgremarks'];
                                }
                                else if(empty($existingGrades_database->midtermgrade) && empty($existingGrades_database->finalgrade)){
                                    // $updateData['fg'] = " ";
                                    $updateData['fgremarks'] = " ";
                                }
        
                                DB::table('college_studentprospectus')
                                    ->where('studid', $gradeData['sid'])
                                    ->where('syid', $gradeData['syid'])
                                    ->where('semid', $gradeData['semid'])
                                    ->where('sectionid', $gradeData['sectionid'])
                                    ->where('schedid', $gradeData['sched'])
                                    ->update($updateData);
                            } else {
                                $insertData = [
                                    'prelim_excel_status' => 1,
                                    'studid' => $gradeData['sid'],
                                    'prelemgrade' => $gradeData['grade'],
                                    'courseID' => $gradeData['course'],
                                    'prospectusID' => $gradeData['subject'],
                                    'schedid' => $gradeData['sched'],
                                    'syid' => $gradeData['syid'],
                                    'semid' => $gradeData['semid'],
                                    'sectionid' => $gradeData['sectionid'],
                                    'createdby' => $gradeData['userid'],
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ];
        
                                $existingGrades_database = DB::table('college_studentprospectus')
                                    ->where('studid', $gradeData['sid'])
                                    ->where('syid', $gradeData['syid'])
                                    ->where('semid', $gradeData['semid'])
                                    ->where('sectionid', $gradeData['sectionid'])
                                    ->where('schedid', $gradeData['sched'])
                                    ->first(['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade']);
                                    // if (!empty($existingGrades_database->prelemgrade)) {
                                if (!empty($existingGrades_database->midtermgrade) && !empty($existingGrades_database->finalgrade)) {
                                    $insertData['fg'] = $gradeData['fg'];
                                    $insertData['fgremarks'] = $gradeData['fgremarks'];
                                }
                                else if(empty($existingGrades_database->midtermgrade) && empty($existingGrades_database->finalgrade)){
                                    // $insertData['fg'] = " ";
                                    $insertData['fgremarks'] = " ";
                                }
        
                                DB::table('college_studentprospectus')
                                    ->insert($insertData);
                            }
                        break;

                    case 'MIDTERM':
                        $existingRecord = DB::table('college_studentprospectus')
                            ->where('studid', $gradeData['sid'])
                            ->where('syid', $gradeData['syid'])
                            ->where('semid', $gradeData['semid'])
                            ->where('sectionid', $gradeData['sectionid'])
                            ->where('schedid', $gradeData['sched'])
                            ->first();

                            if ($existingRecord) {
                                $updateData = [
                                    'midterm_excel_status' => 1,
                                    'midtermgrade' => $gradeData['grade'],
                                    'courseID' => $gradeData['course'],
                                    'prospectusID' => $gradeData['subject'],
                                    'schedid' => $gradeData['sched'],
                                    'syid' => $gradeData['syid'],
                                    'semid' => $gradeData['semid'],
                                    'sectionid' => $gradeData['sectionid'],
                                    'updatedby' => $gradeData['userid'],
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ];
        
                                $existingGrades_database = DB::table('college_studentprospectus')
                                    ->where('studid', $gradeData['sid'])
                                    ->where('syid', $gradeData['syid'])
                                    ->where('semid', $gradeData['semid'])
                                    ->where('sectionid', $gradeData['sectionid'])
                                    ->where('schedid', $gradeData['sched'])
                                    ->first(['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade']);
                                // if (!empty($existingGrades_database->prelemgrade)) {
                            if (!empty($existingGrades_database->prelemgrade) && !empty($existingGrades_database->finalgrade)) {
                                    $updateData['fg'] = $gradeData['fg'];
                                    $updateData['fgremarks'] = $gradeData['fgremarks'];
                                }
                                else if(empty($existingGrades_database->prelemgrade) && empty($existingGrades_database->finalgrade)){
                                    // $updateData['fg'] = " ";
                                    $updateData['fgremarks'] = " ";
                                }
        
                                DB::table('college_studentprospectus')
                                    ->where('studid', $gradeData['sid'])
                                    ->where('syid', $gradeData['syid'])
                                    ->where('semid', $gradeData['semid'])
                                    ->where('sectionid', $gradeData['sectionid'])
                                    ->where('schedid', $gradeData['sched'])
                                    ->update($updateData);
                            } else {
                                $insertData = [
                                    'midterm_excel_status' => 1,
                                    'studid' => $gradeData['sid'],
                                    'midtermgrade' => $gradeData['grade'],
                                    'courseID' => $gradeData['course'],
                                    'prospectusID' => $gradeData['subject'],
                                    'schedid' => $gradeData['sched'],
                                    'syid' => $gradeData['syid'],
                                    'semid' => $gradeData['semid'],
                                    'sectionid' => $gradeData['sectionid'],
                                    'createdby' => $gradeData['userid'],
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                ];
        
                                $existingGrades_database = DB::table('college_studentprospectus')
                                    ->where('studid', $gradeData['sid'])
                                    ->where('syid', $gradeData['syid'])
                                    ->where('semid', $gradeData['semid'])
                                    ->where('sectionid', $gradeData['sectionid'])
                                    ->where('schedid', $gradeData['sched'])
                                    ->first(['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade']);
                                    // if (!empty($existingGrades_database->prelemgrade)) {
                                if (!empty($existingGrades_database->prelemgrade) && !empty($existingGrades_database->finalgrade)) {
                                    $insertData['fg'] = $gradeData['fg'];
                                    $insertData['fgremarks'] = $gradeData['fgremarks'];
                                }
                                else if(empty($existingGrades_database->prelemgrade) && empty($existingGrades_database->finalgrade)){
                                    // $insertData['fg'] = " ";
                                    $insertData['fgremarks'] = " ";
                                }
        
                                DB::table('college_studentprospectus')
                                    ->insert($insertData);
                            }
                        break;

                    // case 'SEMI-FINAL':
                    //     $existingRecord = DB::table('college_studentprospectus')
                    //         ->where('studid', $gradeData['sid'])
                    //         ->where('syid', $gradeData['syid'])
                    //         ->where('semid', $gradeData['semid'])
                    //         ->where('sectionid', $gradeData['sectionid'])
                    //         ->where('schedid', $gradeData['sched'])
                    //         ->first();

                    //         if ($existingRecord) {
                    //             $updateData = [
                    //                 'prefigrade' => $gradeData['grade'],
                    //                 'courseID' => $gradeData['course'],
                    //                 'prospectusID' => $gradeData['subject'],
                    //                 'schedid' => $gradeData['sched'],
                    //                 'syid' => $gradeData['syid'],
                    //                 'semid' => $gradeData['semid'],
                    //                 'sectionid' => $gradeData['sectionid'],
                    //                 'updatedby' => $gradeData['userid'],
                    //                 'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    //             ];
        
                    //             $existingGrades_database = DB::table('college_studentprospectus')
                    //                 ->where('studid', $gradeData['sid'])
                    //                 ->where('syid', $gradeData['syid'])
                    //                 ->where('semid', $gradeData['semid'])
                    //                 ->where('sectionid', $gradeData['sectionid'])
                    //                 ->where('schedid', $gradeData['sched'])
                    //                 ->first(['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade']);
                    //             // if (!empty($existingGrades_database->prelemgrade)) {
                    //         if (!empty($existingGrades_database->prelemgrade) && !empty($existingGrades_database->midtermgrade) && !empty($existingGrades_database->finalgrade)) {
                    //                 $updateData['fg'] = $gradeData['fg'];
                    //                 $updateData['fgremarks'] = $gradeData['fgremarks'];
                    //             }
                    //             else if(empty($existingGrades_database->prelemgrade) && empty($existingGrades_database->midtermgrade) && empty($existingGrades_database->finalgrade)){
                    //                 // $updateData['fg'] = " ";
                    //                 $updateData['fgremarks'] = " ";
                    //             }
        
                    //             DB::table('college_studentprospectus')
                    //                 ->where('studid', $gradeData['sid'])
                    //                 ->where('syid', $gradeData['syid'])
                    //                 ->where('semid', $gradeData['semid'])
                    //                 ->where('sectionid', $gradeData['sectionid'])
                    //                 ->where('schedid', $gradeData['sched'])
                    //                 ->update($updateData);
                    //         } else {
                    //             $insertData = [
                    //                 'studid' => $gradeData['sid'],
                    //                 'prefigrade' => $gradeData['grade'],
                    //                 'courseID' => $gradeData['course'],
                    //                 'prospectusID' => $gradeData['subject'],
                    //                 'schedid' => $gradeData['sched'],
                    //                 'syid' => $gradeData['syid'],
                    //                 'semid' => $gradeData['semid'],
                    //                 'sectionid' => $gradeData['sectionid'],
                    //                 'createdby' => $gradeData['userid'],
                    //                 'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                    //             ];
        
                    //             $existingGrades_database = DB::table('college_studentprospectus')
                    //                 ->where('studid', $gradeData['sid'])
                    //                 ->where('syid', $gradeData['syid'])
                    //                 ->where('semid', $gradeData['semid'])
                    //                 ->where('sectionid', $gradeData['sectionid'])
                    //                 ->where('schedid', $gradeData['sched'])
                    //                 ->first(['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade']);
                    //                 // if (!empty($existingGrades_database->prelemgrade)) {
                    //             if (!empty($existingGrades_database->prelemgrade) && !empty($existingGrades_database->midtermgrade) && !empty($existingGrades_database->finalgrade)) {
                    //                 $insertData['fg'] = $gradeData['fg'];
                    //                 $insertData['fgremarks'] = $gradeData['fgremarks'];
                    //             }
                    //             else if(empty($existingGrades_database->prelemgrade) && empty($existingGrades_database->midtermgrade) && empty($existingGrades_database->finalgrade)){
                    //                 // $insertData['fg'] = " ";
                    //                 $insertData['fgremarks'] = " ";
                    //             }
        
                    //             DB::table('college_studentprospectus')
                    //                 ->insert($insertData);
                    //         }
                    //     break;

                    case 'FINAL':
                        $existingRecord = DB::table('college_studentprospectus')
                            ->where('studid', $gradeData['sid'])
                            ->where('syid', $gradeData['syid'])
                            ->where('semid', $gradeData['semid'])
                            ->where('sectionid', $gradeData['sectionid'])
                            ->where('schedid', $gradeData['sched'])
                            ->first();
    
                    if ($existingRecord) {
                        $updateData = [
                            'final_excel_status' => 1,
                            'finalgrade' => $gradeData['grade'],
                            'courseID' => $gradeData['course'],
                            'prospectusID' => $gradeData['subject'],
                            'schedid' => $gradeData['sched'],
                            'syid' => $gradeData['syid'],
                            'semid' => $gradeData['semid'],
                            'sectionid' => $gradeData['sectionid'],
                            'updatedby' => $gradeData['userid'],
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ];

                        $existingGrades_database = DB::table('college_studentprospectus')
                            ->where('studid', $gradeData['sid'])
                            ->where('syid', $gradeData['syid'])
                            ->where('semid', $gradeData['semid'])
                            ->where('sectionid', $gradeData['sectionid'])
                            ->where('schedid', $gradeData['sched'])
                            ->first(['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade']);
                        // if (!empty($existingGrades_database->prelemgrade)) {
                    if (!empty($existingGrades_database->prelemgrade) && !empty($existingGrades_database->midtermgrade)) {
                            $updateData['fg'] = $gradeData['fg'];
                            $updateData['fgremarks'] = $gradeData['fgremarks'];
                        }
                        else if(empty($existingGrades_database->prelemgrade) && empty($existingGrades_database->midtermgrade)){
                            // $updateData['fg'] = " ";
                            $updateData['fgremarks'] = " ";
                        }

                        DB::table('college_studentprospectus')
                            ->where('studid', $gradeData['sid'])
                            ->where('syid', $gradeData['syid'])
                            ->where('semid', $gradeData['semid'])
                            ->where('sectionid', $gradeData['sectionid'])
                            ->where('schedid', $gradeData['sched'])
                            ->update($updateData);
                    } else {
                        $insertData = [
                            'final_excel_status' => 1,
                            'studid' => $gradeData['sid'],
                            'finalgrade' => $gradeData['grade'],
                            'courseID' => $gradeData['course'],
                            'prospectusID' => $gradeData['subject'],
                            'schedid' => $gradeData['sched'],
                            'syid' => $gradeData['syid'],
                            'semid' => $gradeData['semid'],
                            'sectionid' => $gradeData['sectionid'],
                            'createdby' => $gradeData['userid'],
                            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ];

                        $existingGrades_database = DB::table('college_studentprospectus')
                            ->where('studid', $gradeData['sid'])
                            ->where('syid', $gradeData['syid'])
                            ->where('semid', $gradeData['semid'])
                            ->where('sectionid', $gradeData['sectionid'])
                            ->where('schedid', $gradeData['sched'])
                            ->first(['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade']);
                            // if (!empty($existingGrades_database->prelemgrade)) {
                        if (!empty($existingGrades_database->prelemgrade) && !empty($existingGrades_database->midtermgrade)) {
                            $insertData['fg'] = $gradeData['fg'];
                            $insertData['fgremarks'] = $gradeData['fgremarks'];
                        }
                        else if(empty($existingGrades_database->prelemgrade) && empty($existingGrades_database->midtermgrade)){
                            // $insertData['fg'] = " ";
                            $insertData['fgremarks'] = " ";
                        }

                        DB::table('college_studentprospectus')
                            ->insert($insertData);
                    }
                        break;
                }
            }
            return array((object)[
                'status' => 1,
                'message' => count($grades) . ' grades uploaded',
                'data' => $grades // Include the grades array in response
            ]);

        } else {
            return array((object)[
                'status' => 0,
                'message' => 'Selected term sheet not found in Excel file'
            ]);
        }
    }
    //working code
    // public static function upload_ecr(Request $request){
    //     $term = $request->get('input_term');
    //     $path = $request->file('input_ecr')->getRealPath();
    //     $start_column = $request->get('input_coordinates');
            
    //     $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //     $spreadsheet = $reader->load($path);

    //     if($spreadsheet->sheetNameExists($term)){
    //         $sheet = $spreadsheet->setActiveSheetIndexByName($term);
            
    //         // Parse column and row from coordinates
    //         preg_match('/([A-Z]+)(\d+)/', $start_column, $matches);
    //         $column = $matches[1];
    //         $startRow = intval($matches[2]);

    //         // Get all student grades starting from the specified coordinates
    //         $grades = [];
    //         $currentRow = $startRow;
    //         while(true) {
    //             $cellValue = $sheet->getCell($column . $currentRow)->getValue();
                
    //             // Stop if we hit an empty cell
    //             if(empty($cellValue)) {
    //                 break;
    //             }

    //             // Validate grade value
    //             // if(!is_numeric($cellValue)) {
    //             //     return array((object)[
    //             //         'status' => 0,
    //             //         'message' => 'Invalid grade value found at ' . $column . $currentRow
    //             //     ]);
    //             // }

    //             $grades[] = [
    //                 // 'row' => $currentRow,
    //                 'grade' => $cellValue
    //             ];

    //             $currentRow++;
    //             // return $grades;
    //         }

    //         // Update grades in database based on term
    //         foreach($grades as $gradeData) {
    //             switch($term) {
    //                 case 'PRELIM':
    //                     // Update the grade in college_studentprospectus table for prelim grades
    //                     // - Finds the record where term is 'prelemgrade' and matches the Excel row number
    //                     // - Updates the grade value from Excel and sets updated timestamp
    //                     DB::table('college_studentprospectus')
    //                         ->where('prelemgrade', $term)
    //                         // ->where('row_number', $gradeData['row']) 
    //                         ->update([
    //                             'grade' => $gradeData['grade'],
    //                             'updateddatetime' => now()
    //                         ]);
    //                     break;

    //                 case 'MIDTERM':
    //                     DB::table('college_studentprospectus')
    //                         ->where('midtermgrade', $term)
    //                         // ->where('row_number', $gradeData['row'])
    //                         ->update([
    //                             'grade' => $gradeData['grade'],
    //                             'updateddatetime' => now()
    //                         ]);
    //                     break;

    //                 case 'SEMI-FINAL':
    //                     DB::table('college_studentprospectus')
    //                         ->where('prefigrade', $term)
    //                         // ->where('row_number', $gradeData['row'])
    //                         ->update([
    //                             'grade' => $gradeData['grade'],
    //                             'updateddatetime' => now()
    //                         ]);
    //                     break;

    //                 case 'FINAL':
    //                     DB::table('college_studentprospectus')
    //                         ->where('finalgrade', $term)
    //                         // ->where('row_number', $gradeData['row'])
    //                         ->update([
    //                             'grade' => $gradeData['grade'],
    //                             'updateddatetime' => now()
    //                         ]);
    //                     break;
    //             }
    //         }
    //         return array((object)[
    //             'status' => 1,
    //             'message' => count($grades) . ' grades uploaded successfully',
    //             'data' => $grades // Include the grades array in response
    //         ]);

    //     } else {
    //         return array((object)[
    //             'status' => 0,
    //             'message' => 'Selected term sheet not found in Excel file'
    //         ]);
    //     }
    // }
    //RECENT 2
    // public static function upload_ecr(Request $request){
    //     $term = $request->get('input_term');
    //     $path = $request->file('input_ecr')->getRealPath();
    //     $cell_coordinates = $request->get('cell_coordinates');
            
    //     $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //     $spreadsheet = $reader->load($path);

    //     if($spreadsheet->sheetNameExists($term)){
    //         $sheet = $spreadsheet->setActiveSheetIndexByName($term);
            
    //         // Parse column and row from coordinates
    //         preg_match('/([A-Z]+)(\d+)/', $cell_coordinates, $matches);
    //         $column = $matches[1];
    //         $startRow = intval($matches[2]);

    //         // Get all student grades starting from the specified coordinates
    //         $grades = [];
    //         $currentRow = $startRow;
    //         while(true) {
    //             $cellValue = $sheet->getCell($column . $currentRow)->getValue();
                
    //             // Stop if we hit an empty cell
    //             if(empty($cellValue)) {
    //                 break;
    //             }

    //             // Validate grade value
    //             if(!is_numeric($cellValue)) {
    //                 return array((object)[
    //                     'status' => 0,
    //                     'message' => 'Invalid grade value found at ' . $column . $currentRow
    //                 ]);
    //             }

    //             $grades[] = [
    //                 'row' => $currentRow,
    //                 'grade' => $cellValue
    //             ];

    //             $currentRow++;

    //             return $grades;
    //         }

    //         // Update grades in database based on term
    //         foreach($grades as $gradeData) {
    //             switch($term) {
    //                 case 'PRELIM':
    //                     DB::table('college_grades')
    //                         ->where('term', 'prelim')
    //                         ->where('row_number', $gradeData['row']) 
    //                         ->update([
    //                             'grade' => $gradeData['grade'],
    //                             'updated_at' => now()
    //                         ]);
    //                     break;

    //                 case 'MIDTERM':
    //                     DB::table('college_grades')
    //                         ->where('term', 'midterm')
    //                         ->where('row_number', $gradeData['row'])
    //                         ->update([
    //                             'grade' => $gradeData['grade'],
    //                             'updated_at' => now()
    //                         ]);
    //                     break;

    //                 case 'SEMI-FINAL':
    //                     DB::table('college_grades')
    //                         ->where('term', 'semi_final')
    //                         ->where('row_number', $gradeData['row'])
    //                         ->update([
    //                             'grade' => $gradeData['grade'],
    //                             'updated_at' => now()
    //                         ]);
    //                     break;

    //                 case 'FINAL':
    //                     DB::table('college_grades')
    //                         ->where('term', 'final')
    //                         ->where('row_number', $gradeData['row'])
    //                         ->update([
    //                             'grade' => $gradeData['grade'],
    //                             'updated_at' => now()
    //                         ]);
    //                     break;
    //             }
    //         }

    //         return array((object)[
    //             'status' => 1,
    //             'message' => count($grades) . ' grades uploaded successfully'
    //         ]);

    //     } else {
    //         return array((object)[
    //             'status' => 0,
    //             'message' => 'Selected term sheet not found in Excel file'
    //         ]);
    //     }
    // }


    // public static function upload_ecr(Request $request){

    //     $term = $request->get('input_term');
    //     $path = $request->file('input_ecr')->getRealPath();

            
    //     $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //     $spreadsheet = $reader->load($path);
     
        
        
    //     $schoolinfo = DB::table('schoolinfo')->first();

    //     // if($term != 'CR MIDTERM SUMMARY' && $term != 'CR FINAL SUMMARY' && $term != 'CR SEMESTER SUMMARY'){

    //     //     $worksheet = $spreadsheet->setActiveSheetIndexByName($term);
    //     //     $data = $worksheet->toArray();

    //     //     $headerid = $data[0][51];
    //     //     $schedid = $data[1][51];

        
        
    //     //     $gradeinfo = DB::table('college_exlgrade')
    //     //                 ->where('id',$headerid)
    //     //                 ->where('schedid',$schedid)
    //     //                 ->first();

    //     //     if($gradeinfo->status == 1 && $gradeinfo->status != 0  && $gradeinfo->status != 3){
    //     //         return array((object)[
    //     //             'status'=>0,
    //     //             'message'=>'Grade is already submitted'
    //     //         ]);
    //     //     }

    //     //     if(!isset($gradeinfo)){
    //     //         return array((object)[
    //     //             'status'=>0,
    //     //             'message'=>'Something went wrong'
    //     //         ]);
    //     //     }

    //     //     $syid = $gradeinfo->syid;
    //     //     $semid = $gradeinfo->semid;

    //     //     DB::table('college_exlgrade')
    //     //         ->where('id',$headerid)
    //     //         ->where('schedid',$schedid)
    //     //         ->take(1)
    //     //         ->update([
    //     //             'f1'=>$data[9][5],
    //     //             'f2'=>$data[9][6],
    //     //             'f3'=>$data[9][7],
    //     //             'f4'=>$data[9][8],
    //     //             'f5'=>$data[9][9],
    //     //             'f6'=>$data[9][10],
    //     //             'ftotal'=>$data[9][11],

    //     //             's1'=>$data[9][13],
    //     //             's2'=>$data[9][14],
    //     //             's3'=>$data[9][15],
    //     //             's4'=>$data[9][16],
    //     //             's5'=>$data[9][17],
    //     //             's6'=>$data[9][18],
    //     //             'stotal'=>$data[9][19],

    //     //             'ur1'=>$data[9][23],
    //     //             'ur2'=>$data[9][24],
    //     //             'ur3'=>$data[9][25],
    //     //             'urtotal'=>$data[9][26],

    //     //             'tr1'=>$data[9][28],
    //     //             'tr2'=>$data[9][29],
    //     //             'tr3'=>$data[9][30],
    //     //             'trtotal'=>$data[9][31],

    //     //             'exam1'=>$data[9][35],
    //     //             'examtotal'=>$data[9][36],
    //     //             'date_upload'=>\Carbon\Carbon::now('Asia/Manila'),
    //     //             'updatedby'=>auth()->user()->id,
    //     //             'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //     //         ]);

    //     //     $start_row = 10;

    //     //     DB::table('college_exlgrade_logs')
    //     //             ->insert([
    //     //                 'headerid'=>$headerid,
    //     //                 'status'=>'UPLOAD',
    //     //                 'createdby'=>auth()->user()->id,
    //     //                 'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //     //             ]);
        
    //     //     if($term == 'PRE-MIDTERM' || $term == 'PRELIM' || $term == 'MIDTERM'){
    //     //         $worksheet = $spreadsheet->setActiveSheetIndexByName('CR MIDTERM SUMMARY');
    //     //         $sum_data = $worksheet->toArray();
    //     //         $sum_start_row = 19;
    //     //     }else{
    //     //         $worksheet = $spreadsheet->setActiveSheetIndexByName('CR FINAL SUMMARY');
    //     //         $sum_data = $worksheet->toArray();
    //     //         $sum_start_row = 19;
    //     //     }

    //     //     for($x = $start_row; $x <= 150; $x++){

    //     //         $id = $data[$x][51];
    //     //         $studid = $data[$x][52];
    //     //         $headerid = $data[$x][53];

    //     //         if( $id != null &&  $id != ""){

    //     //             DB::table('college_exlgrade_detail')
    //     //                 ->where('id',$id)
    //     //                 ->where('headerid',$headerid)
    //     //                 ->where('studid',$studid)
    //     //                 ->take(1)
    //     //                 ->update([
    //     //                     'f1'=>$data[ $x][5],
    //     //                     'f2'=>$data[ $x][6],
    //     //                     'f3'=>$data[ $x][7],
    //     //                     'f4'=>$data[ $x][8],
    //     //                     'f5'=>$data[ $x][9],
    //     //                     'f6'=>$data[ $x][10],
    //     //                     'ftotal'=>$data[ $x][11],
    //     //                     'fave'=>$data[ $x][12],
    //     //                     's1'=>$data[ $x][13],
    //     //                     's2'=>$data[ $x][14],
    //     //                     's3'=>$data[ $x][15],
    //     //                     's4'=>$data[ $x][16],
    //     //                     's5'=>$data[ $x][17],
    //     //                     's6'=>$data[ $x][18],
    //     //                     'stotal'=>$data[ $x][19],
    //     //                     'save'=>$data[ $x][20],
    //     //                     'orgenave'=>$data[ $x][21],
    //     //                     'orpercentage'=>$data[ $x][22],

    //     //                     'ur1'=>$data[ $x][23],
    //     //                     'ur2'=>$data[ $x][24],
    //     //                     'ur3'=>$data[ $x][25],
    //     //                     'urtotal'=>$data[ $x][26],
    //     //                     'urave'=>$data[ $x][27],

    //     //                     'tr1'=>$data[ $x][28],
    //     //                     'tr2'=>$data[ $x][29],
    //     //                     'tr3'=>$data[ $x][30],
    //     //                     'trtotal'=>$data[ $x][31],
    //     //                     'trave'=>$data[ $x][32],

    //     //                     'ptgenave'=>$data[ $x][33],
    //     //                     'ptpercentage'=>$data[ $x][34],
    //     //                     'prelim'=>$data[ $x][35],
    //     //                     'prelimtotal'=>$data[ $x][36],

    //     //                     'examgenave'=>$data[ $x][37],
    //     //                     'exampercentage'=>$data[ $x][38],
    //     //                     'totalave'=>$data[ $x][39],
                            
    //     //                     'updatedby'=>auth()->user()->id,
    //     //                     'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //     //                 ]);

    //     //                 $columnMapping = [
    //     //                     'PRE-MIDTERM' => ['column' => 'pre_midterm', 'index' => 17 ],
    //     //                     'PRELIM' => ['column' => 'prelim', 'index' => 10 ],
    //     //                     'MIDTERM' => ['column' => 'midterm', 'index' => 24 ],
    //     //                     'SEMI-FINAL' => ['column' => 'semi_final', 'index' => 10 ],
    //     //                     'PRE-FINAL' => ['column' => 'pre_final', 'index' => 17 ],
    //     //                     'FINAL' => ['column' => 'final', 'index' => 24 ],
    //     //                 ];

    //     //                 if (isset($columnMapping[$term])) {
    //     //                     $columnToUpdate = $columnMapping[$term]['column'];
    //     //                     $id = $sum_data[$sum_start_row][51];
                        
    //     //                     DB::table('college_exlgrade_sum')
    //     //                         ->where('schedid', $schedid)
    //     //                         ->where('studid', $studid)
    //     //                         ->where('id', $id)
    //     //                         ->take(1)
    //     //                         ->update([
    //     //                             $columnToUpdate => $sum_data[$sum_start_row][$columnMapping[$term]['index']],
    //     //                             'updatedby' => auth()->user()->id,
    //     //                             'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
    //     //                         ]);
                                
    //     //                 }

    //     //                 if($schoolinfo->abbreviation == 'DCC'){
    //     //                     self::insert_to_studpros_dcc($syid,$semid,$term,$gradeinfo->schedid,$sum_data);
    //     //                 }else{
    //     //                     self::insert_to_studpros($syid,$semid,$term,$gradeinfo->schedid,$sum_data);
    //     //                 }

    //     //                 $sum_start_row += 1;
            
            
    //     //         }
    //     //     }
    //     // }else{
          
    //     //     $worksheet = $spreadsheet->setActiveSheetIndexByName('PRELIM');
    //     //     $data = $worksheet->toArray();

    //     //     $headerid = $data[0][51];
    //     //     $schedid = $data[1][51];
        
    //     //     $gradeinfo = DB::table('college_exlgrade')
    //     //                 ->where('id',$headerid)
    //     //                 ->where('schedid',$schedid)
    //     //                 ->first();

    //     //     $syid = $gradeinfo->syid;
    //     //     $semid = $gradeinfo->semid;

            
    //     //     $worksheet = $spreadsheet->setActiveSheetIndexByName('CR MIDTERM SUMMARY');
    //     //     $midsum_data = $worksheet->toArray();
    //     //     $midsum_start_row = 19;
    //     // }

    //     // if( $term == 'CR MIDTERM SUMMARY' ){
    //     //     $start_row = 19;

    //     //     $exclgrade_sum =  DB::table('college_exlgrade_sum')
    //     //                         ->where('schedid',$schedid)
    //     //                         ->get();

        

    //     //     for($x = $start_row; $x <= 150; $x++){
    //     //         if(isset($midsum_data[$x])){
    //     //             $id = $midsum_data[$x][51];
    //     //             $studid = $midsum_data[$x][52];
    //     //             $schedid = $midsum_data[$x][53];
    //     //             if( $id != null &&  $id != ""){
    //     //                 $check = collect($exclgrade_sum)
    //     //                             ->where('studid',$studid)
    //     //                             ->first();

    //     //                 //$temp_mid_sum = number_format($check->prelim + $check->pre_midterm + $check->midterm,2);

	// 	// 				//return  substr($midsum_data[ $x][10],0,6);

    //     //                 if($midsum_data[ $x][25] == '#DIV/0!'){
    //     //                     return array((object)[
    //     //                         'status'=>0,
    //     //                         'message'=>'Something went wrong!'
    //     //                     ]);
    //     //                 }

    //     //                 if( number_format($midsum_data[ $x][10],3) != $check->prelim || 
	// 	// 						number_format($midsum_data[ $x][17],3) != $check->pre_midterm ||
	// 	// 							number_format($midsum_data[ $x][24],3) != $check->midterm){
							
	// 	// 					//return collect(substr($midsum_data[ $x][24],0,6) != $check->midterm);
    //     //                     return array((object)[
    //     //                         'status'=>0,
    //     //                         'message'=>'Something went wrong!'
    //     //                     ]);
    //     //                 }

    //     //             }
    //     //         }
    //     //     }

    //     //     for($x = $start_row; $x <= 150; $x++){
    //     //         if(isset($midsum_data[$x])){
    //     //             $id = $midsum_data[$x][51];
    //     //             $studid = $midsum_data[$x][52];
    //     //             $schedid = $midsum_data[$x][53];
    //     //             if( $id != null &&  $id != ""){

    //     //                 DB::table('college_exlgrade_sum')
    //     //                     ->where('id',$id)
    //     //                     ->where('schedid',$schedid)
    //     //                     ->where('studid',$studid)
    //     //                     ->take(1)
    //     //                     ->update([
    //     //                         'mid_term_sum'=>$midsum_data[ $x][25],
    //     //                         'mid_term_per'=>$midsum_data[ $x][26],
    //     //                         'mid_term_fg'=>$midsum_data[ $x][27],
    //     //                         'mid_term_remarks'=>$midsum_data[ $x][28],
    //     //                         'updatedby'=>auth()->user()->id,
    //     //                         'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //     //                     ]);
    //     //             }
    //     //         }
    //     //     }
    //     //     if($schoolinfo->abbreviation == 'DCC'){
    //     //         self::insert_to_studpros_dcc($syid,$semid,'CR MIDTERM SUMMARY',$gradeinfo->schedid,$midsum_data);
    //     //     }else{
    //     //         self::insert_to_studpros($syid,$semid,'CR MIDTERM SUMMARY',$gradeinfo->schedid,$midsum_data);
    //     //     }
    //     // }

    //     // if( $term == 'CR FINAL SUMMARY' ){
    //     //     $worksheet = $spreadsheet->setActiveSheetIndexByName('CR FINAL SUMMARY');
    //     //     $data = $worksheet->toArray();
    //     //     $start_row = 19;

    //     //     $exclgrade_sum =  DB::table('college_exlgrade_sum')
    //     //                         ->where('schedid',$schedid)
    //     //                         ->get();


    //     //     for($x = $start_row; $x <= 150; $x++){
    //     //         if(isset($data[$x])){
    //     //             $id = $data[$x][51];
    //     //             $studid = $data[$x][52];
    //     //             $schedid = $data[$x][53];
    //     //             if( $id != null &&  $id != ""){

    //     //                 $check = collect($exclgrade_sum)
    //     //                             ->where('studid',$studid)
    //     //                             ->first();

    //     //                 $temp_mid_sum = number_format($check->semi_final + $check->pre_final + $check->final,2);

    //     //                 if($data[ $x][25] == '#DIV/0!'){
    //     //                     return array((object)[
    //     //                         'status'=>0,
    //     //                         'message'=>'Something went wrong!'
    //     //                     ]);
    //     //                 }

    //     //                 if( number_format($data[ $x][10],3) != $check->semi_final || 
	// 	// 						number_format($data[ $x][17],3) != $check->pre_final ||
	// 	// 							number_format($data[ $x][24],3) != $check->final){
							
						
    //     //                     return array((object)[
    //     //                         'status'=>0,
    //     //                         'message'=>'Something went wrong!'
    //     //                     ]);
    //     //                 }

                     
    //     //             }
    //     //         }
    //     //     }

    //     //     for($x = $start_row; $x <= 150; $x++){
    //     //         if(isset($data[$x])){
    //     //             $id = $data[$x][51];
    //     //             $studid = $data[$x][52];
    //     //             $schedid = $data[$x][53];
    //     //             if( $id != null &&  $id != ""){
    //     //                 DB::table('college_exlgrade_sum')
    //     //                     ->where('id',$id)
    //     //                     ->where('schedid',$schedid)
    //     //                     ->where('studid',$studid)
    //     //                     ->take(1)
    //     //                     ->update([
    //     //                         'final_term_sum'=>$data[ $x][25],
    //     //                         'final_term_per'=>$data[ $x][26],
    //     //                         'final_term_fg'=>$data[ $x][27],
    //     //                         'final_term_remarks'=>$data[ $x][28],
    //     //                         'updatedby'=>auth()->user()->id,
    //     //                         'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //     //                     ]);
    //     //             }
    //     //         }
    //     //     }

    //     //     if($schoolinfo->abbreviation == 'DCC'){
    //     //         self::insert_to_studpros_dcc($syid,$semid,'CR FINAL SUMMARY',$gradeinfo->schedid,$data);
    //     //     }else{
    //     //         self::insert_to_studpros($syid,$semid,'CR FINAL SUMMARY',$gradeinfo->schedid,$data);
    //     //     }
    //     // }

    //     // if( $term == 'CR SEMESTER SUMMARY' ){
    //     //     $worksheet = $spreadsheet->setActiveSheetIndexByName('CR SEMESTER SUMMARY');
    //     //     $data = $worksheet->toArray();
    //     //     $start_row = 22;

    //     //     $exclgrade_sum =  DB::table('college_exlgrade_sum')
    //     //                         ->where('schedid',$schedid)
    //     //                         ->get();

    //     //     for($x = $start_row; $x <= 150; $x++){
    //     //         if(isset($data[$x])){
    //     //             $id = $data[$x][51];
    //     //             $studid = $data[$x][52];
    //     //             $schedid = $data[$x][53];
    //     //             if( $id != null &&  $id != ""){
    //     //                 $check = collect($exclgrade_sum)
    //     //                             ->where('studid',$studid)
    //     //                             ->first();

    //     //                 $temp_mid_sum = number_format($check->mid_term_per + $check->final_term_per);

    //     //                 if($data[ $x][8] == '#DIV/0!'){

    //     //                     return array((object)[
    //     //                         'status'=>0,
    //     //                         'message'=>'Something went wrong!'
    //     //                     ]);
    //     //                 }

    //     //                 if( number_format($data[ $x][5],3) != $check->mid_term_per || 
    //     //                         number_format($data[ $x][7],3) != $check->final_term_per ){
                            
	// 	// 					return $studid;
    //     //                     return array((object)[
    //     //                         'status'=>0,
    //     //                         'message'=>'Something went wrong!'
    //     //                     ]);
    //     //                 }

    //     //                 // if( $temp_mid_sum != str_replace(" ","",$data[ $x][8])){
							
    //     //                 //     return array((object)[
    //     //                 //         'status'=>0,
    //     //                 //         'message'=>'Something went wrong!'
    //     //                 //     ]);
    //     //                 // }

    //     //             }
    //     //         }
    //     //     }

    //     //     for($x = $start_row; $x <= 150; $x++){
    //     //         if(isset($data[$x])){
    //     //             $id = $data[$x][51];
    //     //             $studid = $data[$x][52];
    //     //             $schedid = $data[$x][53];
    //     //             if( $id != null &&  $id != ""){
    //     //                 DB::table('college_exlgrade_sum')
    //     //                     ->where('id',$id)
    //     //                     ->where('schedid',$schedid)
    //     //                     ->where('studid',$studid)
    //     //                     ->take(1)
    //     //                     ->update([
    //     //                         'grade'=>$data[ $x][8],
    //     //                         'grade_dec'=>$data[ $x][9],
    //     //                         'graderemarks'=>$data[ $x][10],
    //     //                         'updatedby'=>auth()->user()->id,
    //     //                         'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
    //     //                     ]);
    //     //             }
    //     //         }
    //     //     }
    
    //     //     if($schoolinfo->abbreviation == 'DCC'){
    //     //         self::insert_to_studpros_dcc($syid,$semid,'CR SEMESTER SUMMARY',$gradeinfo->schedid,$data);
    //     //     }else{
    //     //          self::insert_to_studpros($syid,$semid,'CR SEMESTER SUMMARY',$gradeinfo->schedid,$data);
    //     //     }
    //     // }

    //     if($spreadsheet->sheetNameExists('PRELIM')){

    //         $sheet = $spreadsheet->setActiveSheetIndexByName('PRELIM');
    //         $data = $sheet->toArray();

    //     }

    //     return array((object)[
    //         'status'=>1,
    //         'message'=>'Uploaded Successfully'
    //     ]);
    // }

    public static function insert_to_studpros($syid,$semid,$term,$schedid,$data){

        $prospectusID = DB::table('college_classsched')
                        ->where('id',$schedid)
                        ->where('syid',$syid)
                        ->where('semesterID',$semid)
                        ->select(
                            'subjectID'
                        )
                        ->first();

        $student_prospectus = DB::table('college_studentprospectus')
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('prospectusID',$prospectusID->subjectID)
                                    ->get();

       

        if($term == 'CR SEMESTER SUMMARY'){
            $start_row = 22;
        }else{
            $start_row = 19;
        }

        for($x = $start_row; $x <= 150; $x++){
            if(isset($data[$x])){
                $id = $data[$x][51];
                $studid = $data[$x][52];
                if( $id != null &&  $id != ""){
                    $check = collect($student_prospectus)
                            ->where('studid',$studid)
                            ->where('syid',$syid)
                            ->where('semid',$semid)
                            ->where('prospectusID',$prospectusID->subjectID)
                            ->first();

                    if(!isset($check)){
                        $pid = DB::table('college_studentprospectus')
                                    ->insertGetId([
                                        'studid'=>$studid,
                                        'syid'=>$syid,
                                        'semid'=>$semid,
                                        'prospectusID'=>$prospectusID->subjectID,
                                        'createdby'=>auth()->user()->id,
                                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                    }else{
                        $pid =  $check->id;
                    }

                    $columnMappings = [
                        'PRE-MIDTERM' => ['column' => 'pre_midterm', 'index' => 16],
                        'PRELIM' => ['column' => 'prelemgrade', 'index' => 9],
                        'MIDTERM' => ['column' => 'midtermgrade', 'index' => 23],
                        'CR MIDTERM SUMMARY' => ['column' => 'midtermcomgrade', 'index' => 25],
                        'SEMI-FINAL' => ['column' => 'semifinal', 'index' => 9],
                        'PRE-FINAL' => ['column' => 'prefigrade', 'index' => 16],
                        'FINAL' => ['column' => 'finalgrade', 'index' => 23],
                        'CR FINAL SUMMARY' => ['column' => 'fg', 'index' => 25],
                    ];
                    
                    if (isset($columnMappings[$term])) {
                        $mapping = $columnMappings[$term];
                    
                        DB::table('college_studentprospectus')
                            ->where('id', $pid)
                            ->take(1)
                            ->update([
                                $mapping['column'] => $data[$x][$mapping['index']],
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => \Carbon\Carbon::now(),
                            ]);
                    }

                    if($term == 'CR SEMESTER SUMMARY'){

                      
                        DB::table('college_studentprospectus')
                            ->where('id',$id)
                            ->take(1)
                            ->update([
                                'finalcomgrade'=>$data[ $x][8],
                                'grade'=>$data[ $x][9],
                                'remarks'=>$data[ $x][10],
                                'updatedby'=>auth()->user()->id,
                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                    }
                }
            }

        }
        // if($term == 'CR MIDTERM SUMMARY' || $term == 'CR FINAL SUMMARY'){
        //     for($x = $start_row; $x <= 150; $x++){
        //         if(isset($data[$x])){
        //             $id = $data[$x][51];
        //             $studid = $data[$x][52];
        //             if( $id != null &&  $id != ""){
        //                 $check = collect($student_prospectus)
        //                             ->where('studid',$studid)
        //                             ->where('syid',$syid)
        //                             ->where('semid',$semid)
        //                             ->where('prospectusID',$prospectusID->subjectID)
        //                             ->first();

        //                 if(!isset($check)){
        //                     $pid = DB::table('college_studentprospectus')
        //                                 ->insertGetId([
        //                                     'studid'=>$studid,
        //                                     'syid'=>$syid,
        //                                     'semid'=>$semid,
        //                                     'prospectusID'=>$prospectusID->subjectID,
        //                                     'createdby'=>auth()->user()->id,
        //                                     'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        //                                 ]);
        //                 }else{
        //                     $pid =  $check->id;
        //                 }

        //                 if($term == 'CR MIDTERM SUMMARY'){
        //                     DB::table('college_studentprospectus')
        //                         ->where('id',$pid)
        //                         ->take(1)
        //                         ->update([
        //                             'prelemgrade'=>$data[$x][9],
        //                             'pre_midterm'=>$data[$x][16],
        //                             'midtermgrade'=>$data[$x][23],
        //                             'midtermcomgrade'=>$data[$x][25],
        //                             'updatedby'=>auth()->user()->id,
        //                             'updateddatetime'=>\Carbon\Carbon::now()
        //                         ]);
        //                 }
                
        //                 if($term == 'CR FINAL SUMMARY'){
        //                     DB::table('college_studentprospectus')
        //                         ->where('id',$pid)
        //                         ->take(1)
        //                         ->update([
        //                             'semifinal'=>$data[$x][9],
        //                             'prefigrade'=>$data[$x][16],
        //                             'finalgrade'=>$data[$x][23],
        //                             'fg'=>$data[$x][25],
        //                             'updatedby'=>auth()->user()->id,
        //                             'updateddatetime'=>\Carbon\Carbon::now()
        //                         ]);
        //                 }
        //             }
        //         }
        //     }
        // }else{
        
        //     for($x = $start_row; $x <= 150; $x++){
        //         if(isset($data[$x])){
        //             $id = $data[$x][51];
        //             $studid = $data[$x][52];
        //             $schedid = $data[$x][53];
        //             if( $id != null &&  $id != ""){

        //                 $check = collect($student_prospectus)
        //                             ->where('studid',$studid)
        //                             ->where('syid',$syid)
        //                             ->where('semid',$semid)
        //                             ->where('prospectusID',$prospectusID->subjectID)
        //                             ->first();

        //                 if(!isset($check)){
        //                     $pid = DB::table('college_studentprospectus')
        //                                 ->insertGetId([
        //                                     'studid'=>$studid,
        //                                     'syid'=>$syid,
        //                                     'semid'=>$semid,
        //                                     'prospectusID'=>$prospectusID->subjectID,
        //                                     'createdby'=>auth()->user()->id,
        //                                     'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        //                                 ]);
        //                 }else{
        //                     $pid =  $check->id;
        //                 }
                        
        //                 DB::table('college_studentprospectus')
        //                     ->where('id',$id)
        //                     ->take(1)
        //                     ->update([
        //                         'finalcomgrade'=>$data[ $x][10],
        //                         'updatedby'=>auth()->user()->id,
        //                         'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        //                     ]);
        //             }
        //         }
        //     }
        // }

    }

    public static function insert_to_studpros_dcc($syid,$semid,$term,$schedid,$data){

        $prospectusID = DB::table('schedulecoding')
                        ->where('id',$schedid)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->select(
                            'subjid',
                            'id'
                        )
                        ->first();

        $student_prospectus = DB::table('college_studentprospectus')
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('prospectusID',$prospectusID->subjid)
                                    ->get();

        $start_row = 19;
        if($term == 'CR MIDTERM SUMMARY' || $term == 'CR FINAL SUMMARY'){
            for($x = $start_row; $x <= 150; $x++){
                if(isset($data[$x])){
                    $id = $data[$x][51];
                    $studid = $data[$x][52];
                    if( $id != null &&  $id != ""){
                        $check = collect($student_prospectus)
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('prospectusID',$prospectusID->id)
                                    ->first();

                        if(!isset($check)){
                            $pid = DB::table('college_studentprospectus')
                                        ->insertGetId([
                                            'studid'=>$studid,
                                            'syid'=>$syid,
                                            'semid'=>$semid,
                                            'prospectusID'=>$prospectusID->id,
                                            'sectionID'=>$prospectusID->id,
                                            'createdby'=>auth()->user()->id,
                                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                        ]);
                        }else{
                            $pid =  $check->id;
                        }

                        if($term == 'CR MIDTERM SUMMARY'){
                            DB::table('college_studentprospectus')
                                ->where('id',$pid)
                                ->take(1)
                                ->update([
                                    // 'prelemgrade'=>$data[$x][9],
                                    // 'pre_midterm'=>$data[$x][16],
                                    'midtermgrade'=>$data[$x][25],
                                    // 'midtermcomgrade'=>$data[$x][25],
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now()
                                ]);
                        }
                
                        if($term == 'CR FINAL SUMMARY'){
                            DB::table('college_studentprospectus')
                                ->where('id',$pid)
                                ->take(1)
                                ->update([
                                    // 'semifinal'=>$data[$x][9],
                                    // 'prefigrade'=>$data[$x][16],
                                    'prefigrade'=>$data[$x][23],
                                    'finalgrade'=>$data[$x][25],
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now()
                                ]);
                        }
                    }
                }
            }
        }else{
            // $start_row = 22;
            // for($x = $start_row; $x <= 150; $x++){
            //     if(isset($data[$x])){
            //         $id = $data[$x][51];
            //         $studid = $data[$x][52];
            //         $schedid = $data[$x][53];
            //         if( $id != null &&  $id != ""){

            //             $check = collect($student_prospectus)
            //                         ->where('studid',$studid)
            //                         ->where('syid',$syid)
            //                         ->where('semid',$semid)
            //                         ->where('prospectusID',$prospectusID->subjectID)
            //                         ->first();

            //             if(!isset($check)){
            //                 $pid = DB::table('college_studentprospectus')
            //                             ->insertGetId([
            //                                 'studid'=>$studid,
            //                                 'syid'=>$syid,
            //                                 'semid'=>$semid,
            //                                 'prospectusID'=>$prospectusID->subjectID,
            //                                 'createdby'=>auth()->user()->id,
            //                                 'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            //                             ]);
            //             }else{
            //                 $pid =  $check->id;
            //             }
                        
            //             DB::table('college_studentprospectus')
            //                 ->where('id',$id)
            //                 ->take(1)
            //                 ->update([
            //                     'finalcomgrade'=>$data[ $x][10],
            //                     'updatedby'=>auth()->user()->id,
            //                     'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            //                 ]);
            //         }
            //     }
            // }
        }

    }

    public static function view_grade(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $schedid = $request->get('schedid');
        $term = $request->get('term');
        $schoolinfo = DB::table('schoolinfo')->first();


        if($schoolinfo->abbreviation == 'DCC'){
            $students = \App\Http\Controllers\CTController\CTController::enrolled_learners($syid,$semid,$schedid);
        }else{
            $students = \App\Http\Controllers\SuperAdminController\College\CollegeSectionsController::sched_enrolled_learners($request);
        }

       

        if($term == 'CR MIDTERM SUMMARY' || $term == 'CR FINAL SUMMARY'){

            $header = DB::table('college_exlgrade')
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where('schedid',$schedid)
                        ->where('deleted',0)
                        
                        ->select('id')
                        ->get();
                        
            $grade_sum = DB::table('college_exlgrade_sum')
                            ->join('studinfo',function($join){
                                $join->on('college_exlgrade_sum.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->whereIn('college_exlgrade_sum.studid',collect($students)->pluck('studid'))
                            ->where('college_exlgrade_sum.deleted',0)
                            ->where('schedid',$schedid)
                            ->get();

            $gradedetail = DB::table('college_exlgrade_detail')
                        ->join('studinfo',function($join){
                            $join->on('college_exlgrade_detail.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->join('college_exlgrade',function($join){
                            $join->on('college_exlgrade_detail.headerid','=','college_exlgrade.id');
                            $join->where('college_exlgrade.deleted',0);
                        })
                        ->whereIn('headerid',collect($header)->pluck('id'))
                        ->where('college_exlgrade_detail.deleted',0)
                        ->whereIn('college_exlgrade_detail.studid',collect($students)->pluck('studid'))
                        ->select(
                            'college_exlgrade_detail.*',
                            'lastname',
                            'firstname',
                            'middlename',
                            'term'
                        )
                        ->get();

            $header = DB::table('college_exlgrade')
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where('schedid',$schedid)
                        ->where('deleted',0)
                        ->where('term',$term)
                        ->get();

            return view('ctportal.pages.grade_sum')
                    ->with('header',$header)
                    ->with('gradedetail',$gradedetail)
                    ->with('grade_sum',$grade_sum)
                    ->with('term',$term);
        }

        if($term == 'CR SEMESTER SUMMARY' ){

            $header = DB::table('college_exlgrade')
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where('schedid',$schedid)
                        ->where('term',$term)
                        ->where('deleted',0)
                        ->get();
                        
            $grade_sum = DB::table('college_exlgrade_sum')
                            ->join('studinfo',function($join){
                                $join->on('college_exlgrade_sum.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->whereIn('college_exlgrade_sum.studid',collect($students)->pluck('studid'))
                            ->where('college_exlgrade_sum.deleted',0)
                            ->where('schedid',$schedid)
                            ->get();

            $header = self::get_header_statdetails($header);
            return view('ctportal.pages.grade_sem_sum')
                    ->with('grade_sum',$grade_sum)
                    ->with('header',$header);
        }

        $header = DB::table('college_exlgrade')
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->where('schedid',$schedid)
                    ->where('term',$term)
                    ->where('deleted',0)
                    ->get();

        if(count($header) == 0){
            $header = self::get_header_statdetails($header);
            return view('ctportal.pages.grade_view')
                    ->with('header',$header);
        }

        $gradedetail = DB::table('college_exlgrade_detail')
                            ->join('studinfo',function($join){
                                $join->on('college_exlgrade_detail.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->whereIn('college_exlgrade_detail.studid',collect($students)->pluck('studid'))
                            ->where('headerid',$header[0]->id)
                            ->where('college_exlgrade_detail.deleted',0)
                            ->select(
                                'college_exlgrade_detail.*',
                                'lastname',
                                'firstname',
                                'middlename'
                            )
                            ->get();

       

        $header = self::get_header_statdetails($header);

        return view('ctportal.pages.grade_view')
                ->with('header',$header)
                ->with('gradedetail',$gradedetail);
        
    }

    public static function get_header_statdetails($data){

        foreach($data as $item){
            $item->statustext = 'Not Submitted';
            if($item->status == 1){
                $item->statustext = 'Submitted';
            }else if($item->status == 2){
                $item->statustext = 'Approved';
            }else if($item->status == 3){
                $item->statustext = 'Pending';
            }else if($item->status == 4){
                $item->statustext = 'Posted';
            } else if($item->status == 5){
                $item->statustext = 'Unposted';
            }

            if($item->statdatetime != null){
                $item->statusdate = \Carbon\Carbon::create($item->statdatetime)->isoFormat('MMM DD, YYYY hh:mm A');
            }else{
                $item->statusdate = '';
            }

            if($item->date_upload != null){
                $item->uploaddate = \Carbon\Carbon::create($item->date_upload)->isoFormat('MMM DD, YYYY hh:mm A');
            }else{
                $item->uploaddate = '';
            }
        }

        return $data;

    }

    public static function submit_grade(Request $request){
        try{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $schedid = $request->get('schedid');
            $term = $request->get('term');
            $id = $request->get('id');
            DB::table('college_exlgrade')
                ->where('syid',$syid)
                ->where('semid',$semid)
                ->where('term',$term)
                ->where('schedid',$schedid)
                ->where('id',$id)
                ->update([
                    'status'=>1,
                    'statdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('college_exlgrade_logs')
                ->insert([
                    'headerid'=>$id,
                    'status'=>'SUBMIT',
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                'status'=>1,
                'message'=>'Submitted Successfully!'
            ]);

        }catch(\Exception $e){
            return array((object)[
                'status'=>0,
                'message'=>'Something went wrong!'
            ]);
        }
    }

    public static function approve_grade(Request $request){
        try{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $schedid = $request->get('schedid');
            $term = $request->get('term');
            $id = $request->get('id');

            $check_status =  DB::table('college_exlgrade')
                                ->where('syid',$syid)
                                ->where('semid',$semid)
                                ->where('term',$term)
                                ->where('schedid',$schedid)
                                ->where('id',$id)
                                ->first();


            if( $check_status->status == 2){
                return array((object)[
                    'status'=>2,
                    'message'=>'Grade is already approved!'
                ]);
            }

            DB::table('college_exlgrade')
                ->where('syid',$syid)
                ->where('semid',$semid)
                ->where('term',$term)
                ->where('schedid',$schedid)
                ->where('id',$id)
                ->update([
                    'status'=>2,
                    'statdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('college_exlgrade_logs')
                ->insert([
                    'headerid'=>$id,
                    'status'=>'APPROVE',
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                'status'=>1,
                'message'=>'Approved Successfully!'
            ]);

        }catch(\Exception $e){
            return array((object)[
                'status'=>0,
                'message'=>'Something went wrong!'
            ]);
        }
    }

    public static function post_grade(Request $request){
        try{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $schedid = $request->get('schedid');
            $term = $request->get('term');
            $id = $request->get('id');

            $check_status =  DB::table('college_exlgrade')
                                ->where('syid',$syid)
                                ->where('semid',$semid)
                                ->where('term',$term)
                                ->where('schedid',$schedid)
                                ->where('id',$id)
                                ->first();


            if( $check_status->status == 4){
                return array((object)[
                    'status'=>2,
                    'message'=>'Grade is already posted!'
                ]);
            }

            DB::table('college_exlgrade')
                ->where('syid',$syid)
                ->where('semid',$semid)
                ->where('term',$term)
                ->where('schedid',$schedid)
                ->where('id',$id)
                ->update([
                    'status'=>4,
                    'statdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('college_exlgrade_logs')
                ->insert([
                    'headerid'=>$id,
                    'status'=>'POST',
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                'status'=>1,
                'message'=>'Posted Successfully!'
            ]);

        }catch(\Exception $e){
            return array((object)[
                'status'=>0,
                'message'=>'Something went wrong!'
            ]);
        }
    }

    public static function pending_grade(Request $request){
        try{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $schedid = $request->get('schedid');
            $term = $request->get('term');
            $id = $request->get('id');

            $check_status =  DB::table('college_exlgrade')
                            ->where('syid',$syid)
                            ->where('semid',$semid)
                            ->where('term',$term)
                            ->where('schedid',$schedid)
                            ->where('id',$id)
                            ->first();

            if( $check_status->status == 3){
                return array((object)[
                    'status'=>2,
                    'message'=>'Grade is already added to pending!'
                ]);
            }

            DB::table('college_exlgrade')
                ->where('syid',$syid)
                ->where('semid',$semid)
                ->where('term',$term)
                ->where('schedid',$schedid)
                ->where('id',$id)
                ->update([
                    'status'=>3,
                    'statdatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('college_exlgrade_logs')
                ->insert([
                    'headerid'=>$id,
                    'status'=>'PENDING',
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                'status'=>1,
                'message'=>'Pending Successfully!'
            ]);

        }catch(\Exception $e){
            return array((object)[
                'status'=>0,
                'message'=>'Something went wrong!'
            ]);
        }
    }

    public static function grade_monitoring(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $term = $request->get('term');
        $status = $request->get('status');

        $search = $request->get('search');
        $search = $search['value'];
        $sched_filter = array();
        $schoolinfo = DB::table('schoolinfo')->first();

        

       
                       
        if( ( $term != null && $term != "" ) || ( $status != null && $status != "" )){

            if($status != 0){
                $sched_filter = DB::table('college_exlgrade')
                                ->where('syid',$syid)
                                ->where('semid',$semid)
                                ->where('deleted',0)
                                ->where(function($query)use($term,$status){
                                    if($status != null && $status != "" ){
                                        $query->where('status',$status);
                                    }
                                    if($term != null && $term != "" ){
                                        $query->where('term',$term);
                                    }
                                })
                                ->select(
                                    'schedid',
                                    'status'
                                )
                                ->where('college_exlgrade.deleted',0)
                                ->get();
            }else{
                $submitted = DB::table('college_exlgrade')
                                ->where('syid',$syid)
                                ->where('semid',$semid)
                                ->where('deleted',0)
                                ->where('status','!=',0)
                                ->select(
                                    'schedid',
                                    'status'
                                )
                                ->where('college_exlgrade.deleted',0)
                                ->groupBy('schedid')
                                ->get();

                $notsubmitted = DB::table('college_exlgrade')
                                ->where('syid',$syid)
                                ->where('semid',$semid)
                                ->where('deleted',0)
                                ->where('status',0)
                                ->select(
                                    'schedid',
                                    'status'
                                )
                                ->where('college_exlgrade.deleted',0)
                                ->groupBy('schedid')
                                ->get();

                $sched_filter = collect($submitted)
                                    ->whereNotIn('schedid',collect( $notsubmitted)->pluck('schedid'))
                                    ->values();
            }
          
        }

        $teacherid = $request->get('teacherid');

        if(Session::get('currentPortal') == 18){
            $teacherid = DB::table('teacher')
                            ->where('tid',auth()->user()->email)
                            ->first()
                            ->id;
        }

        if($schoolinfo->abbreviation == 'DCC'){
            // $sched = \App\Http\Controllers\CTController\CTController::grade_subjects_ajax($request);
            $sched = DB::table('schedulecoding')
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->join('college_subjects',function($join){
                            $join->on('schedulecoding.subjid','=','college_subjects.id');
                            $join->where('college_subjects.deleted',0);
                        })
                        ->leftJoin('teacher',function($join){
                            $join->on('schedulecoding.teacherid','=','teacher.id');
                        })
                        ->where(function($query)use($search,$term,$status,$sched_filter, $teacherid){
                            if($search != null && $search != ""){
                                $query->where('college_subjects.subjDesc','like','%'.$search.'%');
                                $query->orWhere('college_subjects.subjCode','like','%'.$search.'%');
                                $query->orWhere('schedulecoding.code','like','%'.$search.'%');
                                $query->orWhere('lastname','like','%'.$search.'%');
                                $query->orWhere('firstname','like','%'.$search.'%');
                            }
                        
                            if( ( $term != null && $term != "" ) || ( $status != null && $status != "" ) ){
                                if($status != 0){
                                    $query->whereIn('schedulecoding.id',collect($sched_filter)->pluck('schedid'));
                                }else{
                                    $query->whereNotIn('schedulecoding.id',collect($sched_filter)->pluck('schedid'));
                                }
                            }

                            if( $teacherid != null && $teacherid != ""){
                                $query->where('schedulecoding.teacherid',$teacherid);
                            }

                        })
                        ->select(
                            'lastname',
                            'firstname',
                            'tid',
                            'teacherid',
                            'college_subjects.subjDesc',
                            'college_subjects.subjCode',
                            'schedulecoding.id',
                            'schedulecoding.id as schedid',
                            'code'
                        );

            $sched_count = $sched->count();
            $sched = $sched
                    ->take($request->get('length'))
                    ->skip($request->get('start'))
                    ->get();


        }else{

       
            $sched = DB::table('college_classsched')
                        ->where('college_classsched.syid',$syid)
                        ->where('college_classsched.semesterID',$semid)
                        ->where('college_classsched.deleted',0)
                        ->join('college_prospectus',function($join){
                            $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            $join->where('college_prospectus.deleted',0);
                        })
                        ->leftJoin('teacher',function($join){
                            $join->on('college_classsched.teacherid','=','teacher.id');
                        })
                        ->where(function($query)use($search,$term,$status,$sched_filter, $teacherid){
                            if($search != null && $search != ""){
                                $query->where('college_prospectus.subjDesc','like','%'.$search.'%');
                                $query->orWhere('college_prospectus.subjCode','like','%'.$search.'%');
                            }
                        
                            if( ( $term != null && $term != "" ) || ( $status != null && $status != "" ) ){
                                if($status != 0){
                                    $query->whereIn('college_classsched.id',collect($sched_filter)->pluck('schedid'));
                                }else{
                                    $query->whereNotIn('college_classsched.id',collect($sched_filter)->pluck('schedid'));
                                }
                            }

                            if( $teacherid != null && $teacherid != ""){
                                $query->where('college_classsched.teacherid',$teacherid);
                            }

                        })
                        ->select(
                            'lastname',
                            'firstname',
                            'tid',
                            'teacherid',
                            'college_prospectus.subjDesc',
                            'college_prospectus.subjCode',
                            'college_classsched.id',
                            'college_classsched.id as schedid'
                        );

            
        

            $sched_count = $sched->count();
            $sched = $sched
                    ->take($request->get('length'))
                    ->skip($request->get('start'))
                    ->get();

            $check_groups = DB::table('college_schedgroup_detail')
                    ->where('college_schedgroup_detail.deleted',0)
                    ->join('college_schedgroup',function($join){
                        $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                        $join->where('college_schedgroup.deleted',0);
                    })
                    ->whereIn('schedid',collect($sched)->pluck('schedid'))
                    ->leftJoin('college_courses',function($join){
                            $join->on('college_schedgroup.courseid','=','college_courses.id');
                            $join->where('college_courses.deleted',0);
                    })
                    ->leftJoin('gradelevel',function($join){
                            $join->on('college_schedgroup.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                    })
                    ->leftJoin('college_colleges',function($join){
                            $join->on('college_schedgroup.collegeid','=','college_colleges.id');
                            $join->where('college_colleges.deleted',0);
                    })
                    ->select(
                        'college_schedgroup.courseid',
                        'college_schedgroup.levelid',
                        'college_schedgroup.collegeid',
                        'courseDesc',
                        'collegeDesc',
                        'levelname',
                        'courseabrv',
                        'collegeabrv',
                        'college_schedgroup.id',
                        'college_schedgroup.schedgroupdesc',
                        'schedid'
                    )
                    ->get();

            foreach( $sched as $item){

                $check_group = collect($check_groups)->where('schedid',$item->schedid)->values();
                
                foreach($check_group as $schedgroupitem){
                    $text = '';
                    if($schedgroupitem->courseid != null){
                            $text = $schedgroupitem->courseabrv;
                    }else{
                            $text = $schedgroupitem->collegeabrv;
                    }
                    $text .= '-'.$schedgroupitem->levelname[0] . ' '.$schedgroupitem->schedgroupdesc;
                    $schedgroupitem->schedgroupdesc = $text;
                    $item->sectionDesc = $text;
        
                }

                if(count($check_group) == 0){
                    $item->sectionDesc = '';
                }

                $item->levelanme = '';
                $item->courseabrv = '';
                $item->sections = $check_group;
            }
        }

        $sched_grades = DB::table('college_exlgrade')
                                ->whereIn('schedid',collect($sched)->pluck('id'))
                                ->where('syid',$syid)
                                ->where('semid',$semid)
                                ->where('college_exlgrade.deleted',0)
                                ->get();

        foreach($sched_grades as $item){
            $item->statustext = 'Not Submitted';
            if($item->status == 1){
                $item->statustext = 'Submitted';
            }else if($item->status == 2){
                $item->statustext = 'Approved';
            }else if($item->status == 3){
                $item->statustext = 'Pending';
            }else if($item->status == 4){
                $item->statustext = 'Posted';
            } else if($item->status == 5){
                $item->statustext = 'Unposted';
            }

            if($item->statdatetime != null){
                $item->statusdate = \Carbon\Carbon::create($item->statdatetime)->isoFormat('MMM DD, YYYY hh:mm A');
            }else{
                $item->statusdate = '';
            }

            if($item->date_upload != null){
                $item->uploaddate = \Carbon\Carbon::create($item->date_upload)->isoFormat('MMM DD, YYYY hh:mm A');
            }else{
                $item->uploaddate = '';
            }

            
        }

        return @json_encode((object)[
            'sched'=>$sched,
            // 'terms'=>$terms,
            'gradeinfo'=>$sched_grades,

            'data'=>$sched,
            'recordsTotal'=>$sched_count,
            'recordsFiltered'=>$sched_count
      ]);

    }

    public static function grade_monitoring_count(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $schoolinfo = DB::table('schoolinfo')->first();
        $teacherid = null;

        if(Session::get('currentPortal') == 18){
            $teacherid = DB::table('teacher')
                            ->where('tid',auth()->user()->email)
                            ->first()
                            ->id;

            
        }

        if($schoolinfo->abbreviation == 'DCC'){
            $sched = DB::table('schedulecoding')
                        ->where('schedulecoding.syid',$syid)
                        ->where('schedulecoding.semid',$semid)
                        ->where('schedulecoding.deleted',0)
                        ->where(function($query) use($teacherid){
                            if($teacherid != null){
                                $query->where('teacherid',$teacherid);
                            }
                        })
                        ->select(
                            'schedulecoding.id',
                            'schedulecoding.id as schedid'
                        )->get();
        }else{
            $sched = DB::table('college_classsched')
                        ->where('college_classsched.syid',$syid)
                        ->where('college_classsched.semesterID',$semid)
                        ->where('college_classsched.deleted',0)
                        ->where(function($query) use($teacherid){
                            if($teacherid != null){
                                $query->where('teacherid',$teacherid);
                            }
                        })
                        ->select(
                            'college_classsched.id',
                            'college_classsched.id as schedid'
                        )->get();
        }
       

        

        $sched_grades = DB::table('college_exlgrade')
                    ->whereIn('schedid',collecT($sched)->pluck('id'))
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->where('college_exlgrade.deleted',0)
                    ->select(
                        'status',
                        'term'
                    )
                    ->get();

        $terms = array( 
            (object)['term'=>'PRE-MIDTERM'],
            (object)['term'=>'PRELIM'],
            (object)['term'=>'MIDTERM'],
            (object)['term'=>'CR MIDTERM SUMMARY'],
            (object)['term'=>'SEMI-FINAL'],
            (object)['term'=>'PRE-FINAL'],
            (object)['term'=>'FINAL'],
            (object)['term'=>'CR FINAL SUMMARY'],
            (object)['term'=>'CR SEMESTER SUMMARY'],
        );
                        
        foreach($terms as $item){
            $item->submitted = collect($sched_grades)->where('term',$item->term)->where('status',1)->count();
            $item->posted = collect($sched_grades)->where('term',$item->term)->where('status',4)->count();
            $item->approved = collect($sched_grades)->where('term',$item->term)->where('status',2)->count();
            $item->pending = collect($sched_grades)->where('term',$item->term)->where('status',3)->count();
            $item->unsubmitted = collect($sched)->count() - ( $item->submitted +  $item->posted +  $item->approved +  $item->pending);
        }

        
        return array((object)[
                'terms'=>$terms,
                'schedcount'=>count($sched)
            ]);

    }

    public static function grade_logs(Request $request){

        $schedid = $request->get('schedid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $term = $request->get('term');

        $header =  DB::table('college_exlgrade')
                            ->where('syid',$syid)
                            ->where('semid',$semid)
                            ->where('term',$term)
                            ->where('schedid',$schedid)
                            ->first();


        $logs = DB::table('college_exlgrade_logs')
                    ->where('headerid',$header->id)
                    ->get();

        foreach($logs as $item){
            $item->date = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:mm A');
        }

        return $logs;

    }

}
