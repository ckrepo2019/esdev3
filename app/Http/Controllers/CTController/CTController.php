<?php

namespace App\Http\Controllers\CTController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use \Carbon\Carbon;
use App\Models\Principal\Billing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
class CTController extends Controller
{


    public static function print_grading_sheet(Request $request){
        
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        $pid = $request->get('pid');
        $schedid = $request->get('schedid');
        $subjid = $request->get('pid');

        $schedinfo = DB::table('college_classsched')
                        ->join('college_prospectus',function($join){
                            $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            $join->where('college_prospectus.deleted',0);
                        })
                        ->join('teacher',function($join){
                            $join->on('college_classsched.teacherid','=','teacher.id');
                            $join->where('teacher.deleted',0);
                        })
                        ->where('college_classsched.id',$schedid)
                        ->select(
                                'lastname',
                                'firstname',
                                'middlename',
                                'title',
                                'suffix',
                                'subjcode',
                                'subjdesc'
                            )
                        ->first();
                        
        $scheddetail = DB::table('college_scheddetail')
                        ->where('headerid',$schedid)
                        ->where('deleted',0)
                        ->select(
                            'day',
                            DB::raw("CONCAT(college_scheddetail.stime,' ',college_scheddetail.etime) as contime"),
                            'stime',
                            'etime'
                        )
                        ->get();
                        
                        
        $instructor = '';
                        
        if(isset($schedinfo)){
            $middlename = explode(" ",$schedinfo->middlename);
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
    	    if($schedinfo->suffix != null){
    		  $temp_suffix = ' '.$schedinfo->suffix;
    	    }
    	  
    	    if($schedinfo->title != null){
    		  $temp_title = $schedinfo->title.' ';
    	    }
    	  
            $instructor = $temp_title.$schedinfo->firstname.' '.$temp_middle.$schedinfo->lastname.$temp_suffix;
        }
                        
        $time_list = array();
        
        foreach($scheddetail as $item){
            $check =   collect($time_list)->where('time',$item->contime)->count();
            
            $day = '';
            if($item->day == 1){
                $day = 'M';
            }else if($item->day == 2){
                $day = 'T';
            }else if($item->day == 3){
                $day = 'W';
            }else if($item->day == 4){
                $day = 'Th';
            }else if($item->day == 5){
                $day = 'F';
            }else if($item->day == 6){
                $day = 'S';
            }
            
            if($check == 0){
                array_push($time_list,(object)[
                    'curtime'=>\Carbon\Carbon::create($item->stime)->isoFormat('hh:mm a').' - '.\Carbon\Carbon::create($item->etime)->isoFormat('hh:mm a'),
                    'time'=>$item->contime,
                    'day'=>$day
                ]);
            }else{
                
                 $check =   collect($time_list)->where('time',$item->contime)->values();
                 foreach($check as $check_item){
                     $check_item->day .= $day;
                 }
                
            }
        }

        $dean = DB::table('teacher')
                    ->where('id',$request->get('dean'))
                    ->first();

        $dean_text = '';

        if(isset($dean)){
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            if(isset($dean->middlename)){
                $temp_middle = $dean->middlename[0].'.';
            }
            if(isset($dean->title)){
                $temp_title = ', '.$dean->title;
            }
            if(isset($dean->suffix)){
                $temp_suffix = ', '.$dean->suffix;
            }
            $dean_text = $dean->firstname.' '.$temp_middle.' '.$dean->lastname.$temp_suffix.$temp_title;
        }

        
        
        $students = self::enrolled_learners($syid,$semid,$schedid,$subjid);
     
        $request->request->add(['sectionid' => array($sectionid)]);
        $request->request->add(['pid' => array($subjid)]);

        $grades =  self::get_grades($request);
        $syinfo = DB::table('sy')->where('id',$syid)->first();
        $seminfo = DB::table('semester')->where('id',$semid)->first();
    
        
        $scinfo = DB::table('schoolinfo')->first();
        $gradesetup = DB::table('semester_setup')
                        ->where('deleted',0)
                        ->first();
   
        
        $pdf = PDF::loadView('principalsportal.forms.sf9layout.grading_sheet',compact('gradesetup','dean_text','instructor','time_list','students','grades','schedinfo','seminfo','syinfo','scinfo'))->setPaper('legal');
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
        return $pdf->stream('SY: '.$syinfo->sydesc.'_SEM_'.$seminfo->semester.' GRADE SUMMARY [ '.$schedinfo->subjcode.' - '.$schedinfo->subjdesc.']');
    }

    public static function student_list_pdf(
        $syid = null, 
        $semid = null, 
        $schedid = null, 
        Request $request
    ){

        if($syid == null){
            $syid = $request->get('syid');
        }

        if($semid == null){
            $semid = $request->get('semid');
        }

        if($schedid == null){
            $schedid = $request->get('schedid');
        }

        $students = self::enrolled_learners($syid, $semid, $schedid);
        
        $schedules = self::subjects($syid,$semid,null,$schedid);

        $semester = DB::table('semester')
                        ->where('id',$semid)
                        ->first()
                        ->semester;
                        
        $sydesc = DB::table('sy')
                        ->where('id',$syid)
                        ->first()
                        ->sydesc;


        $schedinfo = DB::table('college_classsched')
                        ->join('teacher',function($join){
                            $join->on('college_classsched.teacherid','=','teacher.id');
                            $join->where('teacher.deleted',0);
                        })
                        ->where('college_classsched.id',$schedid)
                        ->select(
                                'lastname',
                                'firstname',
                                'middlename',
                                'title',
                                'suffix'
                             
                            )
                        ->first();
                        
        $instructor = '';
                        
        if(isset($schedinfo)){
            $middlename = explode(" ",$schedinfo->middlename);
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
    	    if($schedinfo->suffix != null){
    		  $temp_suffix = ' '.$schedinfo->suffix;
    	    }
    	  
    	    if($schedinfo->title != null){
    		  $temp_title = $schedinfo->title.' ';
    	    }
    	  
            $instructor = $temp_title.$schedinfo->firstname.' '.$temp_middle.$schedinfo->lastname.$temp_suffix;
        }
               
        foreach($schedules as $item){

            $schedule = Db::table('college_scheddetail')
                            ->leftJoin('rooms',function($join){
                                $join->on('college_scheddetail.roomid','=','rooms.id');
                                $join->where('rooms.deleted',0);
                            })
                            ->leftJoin('days',function($join){
                                $join->on('college_scheddetail.day','=','days.id');
                            })
                            ->where('college_scheddetail.headerID',$item->schedid)
                            ->where('college_scheddetail.deleted',0)
                            ->select(
                                'college_scheddetail.stime',
                                'college_scheddetail.etime',
                                'college_scheddetail.day',
                                'days.description',
                                'rooms.roomname',
                                'schedotherclass'
                            )
                            ->get();

            foreach($schedule as $sched_item){
                $start = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A');
                $end = \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                $time = $start.' - '.$end;
                $sched_item->time = $time;
                $sched_item->start = $start;
                $sched_item->end = $end;
            }

            $schedule = collect($schedule)->groupBy('time')->values();
            $sched_list = array();

            foreach($schedule as $sched_item){

                $dayString = '';
                $schedotherclass = '';
                $days = [];
              
                foreach($sched_item as $sched_item_by_day){
                    $dayString.= substr($sched_item_by_day->description, 0,3).' / ';
                    $sort = \Carbon\Carbon::createFromTimeString($sched_item_by_day->stime)->isoFormat('HH:mm A');
                    array_push($days,$sched_item_by_day->day);
                    $schedotherclass = $sched_item_by_day->schedotherclass;
                    $time = $sched_item_by_day->time;
                    $start = $sched_item_by_day->start;
                    $end = $sched_item_by_day->end;
                }

                $dayString = substr($dayString, 0,-3);

                array_push($sched_list,(object)[
                    'day'=>$dayString,
                    'start'=>$start,
                    'end'=>$end,
                    'time'=>$time,
                    'days'=>$days
                ]);
               
            }

            $item->schedule = $sched_list;
                
        }


        // return $schedules;

                        

        $pdf = PDF::loadView('ctportal.pages.studentinformation_pdf',compact('instructor','students','sydesc','semester','schedules'))->setPaper('legal');
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
        return $pdf->stream();

    }

    public static function student_list_excell(
        $syid = null, 
        $semid = null, 
        $schedid = null, 
        Request $request
    ){

        if($syid == null){
            $syid = $request->get('syid');
        }

        if($semid == null){
            $semid = $request->get('semid');
        }

        if($schedid == null){
            $schedid = $request->get('schedid');
        }
        $scinfo = DB::table('schoolinfo')->first();
        $students = self::enrolled_learners($syid, $semid, $schedid);
        
        $schedules = self::subjects($syid,$semid,null,$schedid);

        $semester = DB::table('semester')
                        ->where('id',$semid)
                        ->first()
                        ->semester;
                        
        $sydesc = DB::table('sy')
                        ->where('id',$syid)
                        ->first()
                        ->sydesc;


        $schedinfo = DB::table('college_classsched')
                        ->join('teacher',function($join){
                            $join->on('college_classsched.teacherid','=','teacher.id');
                            $join->where('teacher.deleted',0);
                        })
                        ->where('college_classsched.id',$schedid)
                        ->select(
                                'lastname',
                                'firstname',
                                'middlename',
                                'title',
                                'suffix'
                             
                            )
                        ->first();
                        
        $instructor = '';
                        
        if(isset($schedinfo)){
            $middlename = explode(" ",$schedinfo->middlename);
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
    	    if($schedinfo->suffix != null){
    		  $temp_suffix = ' '.$schedinfo->suffix;
    	    }
    	  
    	    if($schedinfo->title != null){
    		  $temp_title = $schedinfo->title.' ';
    	    }
    	  
            $instructor = $temp_title.$schedinfo->firstname.' '.$temp_middle.$schedinfo->lastname.$temp_suffix;
        }
               
        foreach($schedules as $item){

            $schedule = Db::table('college_scheddetail')
                            ->leftJoin('rooms',function($join){
                                $join->on('college_scheddetail.roomid','=','rooms.id');
                                $join->where('rooms.deleted',0);
                            })
                            ->leftJoin('days',function($join){
                                $join->on('college_scheddetail.day','=','days.id');
                            })
                            ->where('college_scheddetail.headerID',$item->schedid)
                            ->where('college_scheddetail.deleted',0)
                            ->select(
                                'college_scheddetail.stime',
                                'college_scheddetail.etime',
                                'college_scheddetail.day',
                                'days.description',
                                'rooms.roomname',
                                'schedotherclass'
                            )
                            ->get();

            foreach($schedule as $sched_item){
                $start = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A');
                $end = \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                $time = $start.' - '.$end;
                $sched_item->time = $time;
                $sched_item->start = $start;
                $sched_item->end = $end;
            }

            $schedule = collect($schedule)->groupBy('time')->values();
            $sched_list = array();

            foreach($schedule as $sched_item){

                $dayString = '';
                $schedotherclass = '';
                $days = [];
              
                foreach($sched_item as $sched_item_by_day){
                    $dayString.= substr($sched_item_by_day->description, 0,3).' / ';
                    $sort = \Carbon\Carbon::createFromTimeString($sched_item_by_day->stime)->isoFormat('HH:mm A');
                    array_push($days,$sched_item_by_day->day);
                    $schedotherclass = $sched_item_by_day->schedotherclass;
                    $time = $sched_item_by_day->time;
                    $start = $sched_item_by_day->start;
                    $end = $sched_item_by_day->end;
                }

                $dayString = substr($dayString, 0,-3);

                array_push($sched_list,(object)[
                    'day'=>$dayString,
                    'start'=>$start,
                    'end'=>$end,
                    'time'=>$time,
                    'days'=>$days
                ]);
               
            }

            $item->schedule = $sched_list;
                
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // return $scinfo->picurl;
        // Logo placement
        // Remove the query string from the logo path
        $logoPath = explode('?', public_path($scinfo->picurl))[0];

        // Add the logo to the Excel sheet
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('School Logo');
        $drawing->setDescription('School Logo');
        $drawing->setPath($logoPath);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);
        $drawing->setHeight(70);
        $drawing->setWorksheet($sheet);

        // School Name
        $sheet->setCellValue('A1', $scinfo->schoolname)
            ->mergeCells('A1:I1')
            ->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 19
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

        // School Address
        $sheet->setCellValue('A2', $scinfo->address)
            ->mergeCells('A2:I2')
            ->getStyle('A2')->applyFromArray([
                'font' => [
                    'size' => 12
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

        // Official Class List
        $sheet->setCellValue('A4', 'OFFICIAL CLASS LIST')
            ->mergeCells('A4:I4')
            ->getStyle('A4')->applyFromArray([
                'font' => [
                    'size' => 12
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

        // Subject Info
        $sheet->setCellValue('A5', '[ ' . collect($schedules)->first()->subjCode . ' - ' . collect($schedules)->first()->subjDesc . ' ]')
            ->mergeCells('A5:I5')
            ->getStyle('A5')->applyFromArray([
                'font' => [
                    'size' => 12
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

        // Instructor and other information
        $sheet->setCellValue('A7', 'Instructor:')
            ->setCellValue('B7', $instructor)
            ->mergeCells('B7:E7');

        $sheet->setCellValue('F7', 'School Year:')
            ->setCellValue('G7', $sydesc)
            ->mergeCells('G7:H7');

        $sheet->setCellValue('A8', 'Schedule:')
            ->setCellValue('B8', implode(', ', array_map(function($item) {
                return $item->time . ' - ' . $item->day;
            }, $schedules[0]->schedule)))
            ->mergeCells('B8:E8');

        $sheet->setCellValue('F8', 'Semester:')
            ->setCellValue('G8', $semester)
            ->mergeCells('G8:H8');

        // Headers
        $sheet->setCellValue('A10', 'NO')
            ->setCellValue('B10', 'Name')
            ->setCellValue('C10', 'Year Level')
            ->mergeCells('C10:D10')
            ->setCellValue('E10', 'Course')
            ->mergeCells('E10:G10')
            ->getStyle('A10:G10')->applyFromArray([
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

        // Align B10 to the left
        $sheet->getStyle('B10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $row = 11;
        foreach ($students as $key => $student) {
            $sheet->setCellValue('A' . $row, $key + 1)
                ->setCellValue('B' . $row, $student->lastname . ', ' . $student->firstname . ' ' . $student->middlename . ' ' . $student->suffix)
                ->setCellValue('C' . $row, str_replace("COLLEGE", "", $student->levelname))
                ->mergeCells('C' . $row . ':D' . $row)
                ->setCellValue('E' . $row, $student->courseabrv)
                ->mergeCells('E' . $row . ':G' . $row);

            $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            
            // Align B$row to the left
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $row++;
        }
        $sheet->getColumnDimension('B')->setAutoSize(true);
        // Export to Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Student List.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . collect($schedules)->first()->subjCode . ' - ' . collect($schedules)->first()->subjDesc . ' ' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');


    }

    //new
    public static function subjects(
        $syid  = null,
        $semid  = null,
        $teacherid = null,
        $schedid = null
    ){

        $schoolinfo = DB::table('schoolinfo')->first();

        $subjects = DB::table('college_classsched')
                        ->join('college_prospectus',function($join){
                            $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            $join->where('college_prospectus.deleted',0);
                        })
                        ->join('college_sections',function($join){
                            $join->on('college_classsched.sectionid','=','college_sections.id');
                            $join->where('college_sections.deleted',0);
                        })
                        ->join('college_courses',function($join){
                            $join->on('college_sections.courseid','=','college_courses.id');
                            $join->where('college_sections.deleted',0);
                        })
                        ->leftJoin('gradelevel',function($join){
                            $join->on('college_sections.yearID','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                        });

        if($schedid != null){
            $subjects = $subjects->where('college_classsched.id',$schedid);
        }

        if($teacherid != null){
            $subjects = $subjects->where('college_classsched.teacherid',$teacherid);
        }
                

        $subjects = $subjects
                        ->where('college_classsched.syid',$syid)
                        ->where('college_classsched.semesterID',$semid)
                        ->where('college_classsched.deleted',0)
                        ->select(
                            'college_prospectus.subjectID',
                            'levelname',
                            'courseDesc',
                            'courseabrv',
                            'subjDesc',
                            'subjCode',
                            'labunits',
                            'lecunits',
                            'sectionDesc',
                            'college_classsched.id as schedid',
                            'college_prospectus.id as pid',
                            'college_classsched.sectionID'
                        );

        if(strtoupper($schoolinfo->abbreviation) == 'SPCT' || strtoupper($schoolinfo->abbreviation) == 'GBBC' ){
            $subjects = $subjects->groupBy('subjectID');
        }

        $subjects = $subjects->get();

        return $subjects;
    }

    public static function enrolled_learners($syid = null, $semid = null, $schedid = null, $subjctid = null){

        $schoolinfo = DB::table('schoolinfo')->first();

        

        if(strtoupper($schoolinfo->abbreviation) == 'SPCT' || strtoupper($schoolinfo->abbreviation) == 'GBBC'){

            $teacherid = DB::table('teacher')
                            ->where('tid',auth()->user()->email)
                            ->first()
                            ->id;

            $sched =  DB::table('college_prospectus')
                        ->where('college_prospectus.deleted',0)
                        ->where('college_prospectus.subjectID',$subjctid)
                        ->join('college_classsched',function($join) use($syid,$semid,$teacherid){
                            $join->on('college_prospectus.id','=','college_classsched.subjectID');
                            $join->where('college_classsched.deleted',0);
                            $join->where('college_classsched.syid',$syid);
                            $join->where('college_classsched.semesterID',$semid);
                            $join->where('college_classsched.teacherid',$teacherid);
                        })
                        ->select('college_classsched.id')
                        ->get();

            $sched_array = array();
            foreach($sched as $item){
                array_push( $sched_array , $item->id);
            }

            $students =  DB::table('college_studsched')
                            ->where('college_studsched.deleted',0)
                            ->whereIn('college_studsched.schedid',$sched_array)
                            ->where('college_studsched.schedstatus','!=','DROPPED')
                            ->join('college_enrolledstud',function($join) use($syid,$semid){
                                $join->on('college_studsched.studid','=','college_enrolledstud.studid');
                                $join->where('college_enrolledstud.deleted',0);
                                $join->where('college_enrolledstud.syid',$syid);
                                $join->where('college_enrolledstud.semid',$semid);
                                $join->whereIn('studstatus',[1,2,4]);
                            })
                            ->join('studinfo',function($join){
                                $join->on('college_enrolledstud.studid','=','studinfo.id');
                                $join->where('college_enrolledstud.deleted',0);
                            })
                            ->leftJoin('college_courses',function($join){
                                $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                $join->where('college_courses.deleted',0);
                            })
                            ->join('gradelevel',function($join){
                                $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->join('college_classsched',function($join) use($syid,$semid,$teacherid){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted',0);
                            })
                            ->select(
                                'college_classsched.subjectID as pid',
                                'college_classsched.sectionID as sectionid',
                                'college_enrolledstud.courseid',
                                'levelname',
                                'courseabrv',
                                'gender',
                                'firstname',
                                'lastname',
                                'middlename',
                                'suffix',
                                'sid',
                                'college_enrolledstud.yearLevel as levelid',
                                'college_enrolledstud.studid',
                                DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                            )
                            ->distinct('studid')
                            ->orderBy('studentname','asc')
                            ->get();

        }else {
            
            $students = DB::table('college_enrolledstud')
                            ->join('gradelevel',function($join){
                                $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->join('studinfo',function($join){
                                $join->on('college_enrolledstud.studid','=','studinfo.id');
                                $join->where('college_enrolledstud.deleted',0);
                            })
                            ->join('college_studsched',function($join) use($schedid){
                                $join->on('college_enrolledstud.studid','=','college_studsched.studid');
                                $join->where('college_studsched.schedid',$schedid);
                                $join->where('college_studsched.deleted',0);
                                $join->where('college_studsched.schedstatus','!=','DROPPED');
                            })
                            ->leftJoin('college_courses',function($join){
                                $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                $join->where('college_courses.deleted',0);
                            })
                            ->join('college_classsched',function($join) use($syid,$semid){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted',0);
                                $join->where('college_classsched.syid',$syid);
                                $join->where('college_classsched.semesterID',$semid);
                            })
                            ->where('college_enrolledstud.syid',$syid)
                            ->where('college_enrolledstud.semid',$semid)
                            ->select(
                                'college_classsched.subjectID as pid',
                                'college_classsched.sectionID as sectionid',
                                'college_enrolledstud.courseid',
                                'levelname',
                                'courseabrv',
                                'gender',
                                'firstname',
                                'lastname',
                                'middlename',
                                'semail',
                                'contactno',
                                'suffix',
                                'sid',
                                'college_enrolledstud.yearLevel as levelid',
                                'college_enrolledstud.studid',
                                'college_enrolledstud.studid'
                            )
                            ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                            ->where('college_enrolledstud.deleted',0)
                            ->orderBy('gender','desc')
                            ->orderBy('lastname')
                            ->distinct('studid')
                            ->get();
        }
        
        


        foreach($students as $item){
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
            $item->search = $item->student.' '.$item->levelname.' '.$item->courseabrv.' '.$item->gender.' '.$item->sid;

        }

        return $students;

    }

    public static function grade_enroled_ajax(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $subjid =  $request->get('subjid');
        $schedid =  $request->get('schedid');

        return self::enrolled_learners($syid,$semid,$schedid,$subjid);
    }

    public static function grade_subjects_ajax(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $teacherid = $request->get('teacherid');

        $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->first()
                        ->id;

        $subjects = self::subjects($syid,$semid,$teacherid);
        
        foreach($subjects as $item){

            $schedotherclass = DB::table('college_scheddetail')
                                    ->where('headerid',$item->schedid)
                                    ->select('schedotherclass')
                                    ->first();

            $item->schedotherclass = isset($schedotherclass->schedotherclass) ? $schedotherclass->schedotherclass : null;
            $item->students = array();
            $item->students = self::enrolled_learners($syid,$semid,$item->schedid,$item->subjectID);

            $check_group = DB::table('college_schedgroup_detail')
                                    ->where('college_schedgroup_detail.deleted',0)
                                    ->join('college_schedgroup',function($join){
                                          $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                                          $join->where('college_schedgroup.deleted',0);
                                    })
                                    ->where('schedid',$item->schedid)
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
                                        'college_schedgroup.schedgroupdesc'
                                    )
                                    ->get();

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

        return $subjects;

    }


    public static function save_grades(Request $request){

        $studid = $request->get('studid');
        $termgrade = $request->get('termgrade');
        $term = $request->get('term');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        $pid = $request->get('pid');
        $isinc = false;
        $isdropped = false;


        $check = DB::table('college_studentprospectus')
                    ->where('studid',$studid)
                    ->where('semid',$semid)
                    ->where('syid',$syid)
                    ->where('deleted',0)
                    ->where('sectionid',$sectionid)
                    ->where('prospectusid',$pid)
                    ->get();



        if($termgrade == 'INC'){
            $termgrade = null;
            $isinc = true;
        }
        if($termgrade == 'DROPPED'){
            $termgrade = null;
            $isdropped = true;
        }

        if(count($check) == 0){

            DB::table('college_studentprospectus')
                ->insert([
                    $term=>$termgrade,
                    'studid'=>$studid,
                    'syid'=>$syid,
                    'semid'=>$semid,
                    'sectionid'=>$sectionid,
                    'courseid'=>$courseid,
                    'prospectusID'=>$pid,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                ]);

        }else{


            DB::table('college_studentprospectus')
                ->where('id',$check[0]->id)
                ->take(1)
                ->update([
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    $term=>$termgrade,
                ]);

                
            if($term == 'prelemgrade'){
                $term = 'prelemstatus';
            }else if($term == 'midtermgrade'){
                $term = 'midtermstatus';
            }else if($term == 'prefigrade'){
                $term = 'prefistatus';
            }else if($term == 'finalgrade'){
                $term = 'finalstatus';
            }

            if($check[0]->prelemstatus  == 9 || $check[0]->midtermgrade  == 9 || $check[0]->prefigrade  == 9 || $check[0]->finalgrade  == 9){
                DB::table('college_studentprospectus')
                    ->where('id',$check[0]->id)
                    ->take(1)
                    ->update([
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'prelemstatus'=>null,
                        'midtermstatus'=>null,
                        'prefistatus'=>null,
                        'finalstatus'=>null,
                    ]);
            }
            if($check[0]->$term  == 8){
                DB::table('college_studentprospectus')
                    ->where('id',$check[0]->id)
                    ->take(1)
                    ->update([
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        $term=>null,
                    ]);
            }

        }

        $check = DB::table('college_studentprospectus')
                    ->where('studid',$studid)
                    ->where('semid',$semid)
                    ->where('syid',$syid)
                    ->where('deleted',0)
                    ->where('sectionid',$sectionid)
                    ->where('prospectusid',$pid)
                    ->get();


        if($isinc){

            if($term == 'prelemgrade'){
                $term = 'prelemstatus';
            }else if($term == 'midtermgrade'){
                $term = 'midtermstatus';
            }else if($term == 'prefigrade'){
                $term = 'prefistatus';
            }else if($term == 'finalgrade'){
                $term = 'finalstatus';
            }

            DB::table('college_studentprospectus')
                    ->where('id',$check[0]->id)
                    ->take(1)
                    ->update([
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        $term=>8
                    ]);

        }

        if($isdropped){

            DB::table('college_studentprospectus')
                    ->where('id',$check[0]->id)
                    ->take(1)
                    ->update([
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'prelemstatus'=>9,
                        'midtermstatus'=>9,
                        'prefistatus'=>9,
                        'finalstatus'=>9,
                    ]);

        }


    }


    public static function get_grades(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $courseid = $request->get('courseid');
        $sectionid = $request->get('sectionid');
        $pid = $request->get('pid');

        $schoolinfo = DB::table('schoolinfo')->first();

        // if(strtoupper($schoolinfo->abbreviation) == 'GBBC'){
            $grades = DB::table('college_studentprospectus')
                            ->where('semid',$semid)
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->whereIn('prospectusid',$pid)
                            //->whereIn('sectionid',$sectionid)
                            ->select(
                                'id',
                                'studid',
                                'prelemgrade',
                                'midtermgrade',
                                'prefigrade',
                                'finalgrade',
                                'prospectusID',
                                'prelemstatus',
                                'midtermstatus',
                                'prefistatus',
                                'finalstatus',
                                'fg',
                                'fgremarks'
                            )
                            ->distinct('studid')
                            ->get();
            
            
        // }else{
        //     $grades = DB::table('college_studentprospectus')
        //                 ->where('semid',$semid)
        //                 ->where('syid',$syid)
        //                 ->where('deleted',0)
        //                 ->where('prospectusid',$pid)
        //                 ->where('sectionid',$sectionid)
        //                 ->select(
        //                     'id',
        //                     'studid',
        //                     'prelemgrade',
        //                     'midtermgrade',
        //                     'prefigrade',
        //                     'finalgrade',
        //                     'prospectusID',
        //                     'prelemstatus',
        //                     'midtermstatus',
        //                     'prefistatus',
        //                     'finalstatus'
        //                 )
        //                 ->distinct('studid')
        //                 ->get();
        // }

        return $grades;

    }

    public static function get_schedule_ajax(Request $request){


        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $teacherid = $request->get('teacherid');

        $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->first()
                        ->id;


        $subjects = self::subjects($syid,$semid,$teacherid);

        foreach($subjects as $item){

            $schedule = Db::table('college_scheddetail')
                            ->leftJoin('rooms',function($join){
                                $join->on('college_scheddetail.roomid','=','rooms.id');
                                $join->where('rooms.deleted',0);
                            })
                            ->leftJoin('days',function($join){
                                $join->on('college_scheddetail.day','=','days.id');
                            })
                            ->where('college_scheddetail.headerID',$item->schedid)
                            ->where('college_scheddetail.deleted',0)
                            ->select(
                                'college_scheddetail.stime',
                                'college_scheddetail.etime',
                                'college_scheddetail.day',
                                'days.description',
                                'rooms.roomname',
                                'schedotherclass'
                            )
                            ->get();

            $sched_list = array();
            $dayString = '';
            $schedotherclass = '';
            $days = [];
            $room = null;

            foreach($schedule as $sched_item){
                $start = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('hh:mm A');
                $end = \Carbon\Carbon::createFromTimeString($sched_item->etime)->isoFormat('hh:mm A');
                $time = $start.' - '.$end;
                $dayString.= substr($sched_item->description, 0,3).'/';
                $sort = \Carbon\Carbon::createFromTimeString($sched_item->stime)->isoFormat('HH:mm A');
                array_push($days,$sched_item->day);
                $schedotherclass = $sched_item->schedotherclass;
                $room = $sched_item->roomname;


               


            }

            $check_group = DB::table('college_schedgroup_detail')
                        ->where('college_schedgroup_detail.deleted',0)
                        ->join('college_schedgroup',function($join){
                            $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                            $join->where('college_schedgroup.deleted',0);
                        })
                        ->where('schedid',$item->schedid)
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
                            'college_schedgroup.schedgroupdesc'
                        )
                        ->get();

            foreach($check_group as $schedgroupitem){
                $text = '';
                if($schedgroupitem->courseid != null){
                $text = $schedgroupitem->courseabrv;
                }else{
                $text = $schedgroupitem->collegeabrv;
                }
                $text .= '-'.$schedgroupitem->levelname[0] . ' '.$schedgroupitem->schedgroupdesc;
                $schedgroupitem->sectionDesc = $text;

            }

            array_push($sched_list,(object)[
                'day'=>substr($dayString,0, -3),
                'start'=>$start,
                'end'=>$end,
                'time'=>$time,
                'days'=>$days
            ]);

            if(count($check_group) == 0){
                $schedgroupitem->sectionDesc = '';
            }
            
            $item->sort = $sort;
            $item->search = $dayString.' '.$item->subjDesc.' '.$item->subjCode.' '.$item->sectionDesc.' '.$item->courseDesc;
            $item->schedule = $sched_list;
            $item->schedotherclass = $schedotherclass;
            $item->courseabrv = '';
            $item->levelname = '';
        }


        return $subjects;


    }

    

    public static function get_exam_permit_ajax(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->first()
                        ->id;

        $subjects = self::subjects($syid,$semid,$teacherid);

        foreach($subjects as $item){

            $enrolled_students = self::enrolled_learners($syid,$semid,$item->schedid);
            $students = array();
           
            foreach($enrolled_students as $student_item){
                array_push($students,$student_item->studid);
            }

            // $exampermit = DB::table('permittoexam')
            //                 ->where('deleted',0)
            //                 ->where('syid',$syid)
            //                 ->where('semid',$semid)
            //                 ->whereIn('studid',$students)
            //                 ->select(
            //                     'studid',
            //                     'quarterid'
            //                 )
            //                 ->get();


            $exampermit = DB::table('epermitdetails')
                            ->join('monthsetup',function($query){
                                $query->on('epermitdetails.monthid','=','monthsetup.id');
                            })
                            // ->where('deleted',0)
                            ->where('examstatus','a')
                            ->where('syid',$syid)
                            ->where('semid',$semid)
                            ->whereIn('studid',$students)
                            ->orderBy('epermitdetails.id','desc')
                            // ->distinct('studid')
                            ->select(
                                'studid',
                                'description as month',
                                'epermitdetails.monthid'
                            )
                            ->get();
                            
            $item->exampermit = $exampermit;

        }

        return $subjects;


    }


    public static function my_profile(Request $request){

        $teacherid = $request->get('id');

        $teacherinfo = DB::table('teacher');

        if($teacherid != '' && $teacherid != null){
            if(Session::get('currentPortal') == 2 || Session::get('currentPortal') == 17){
                $teacherinfo = $teacherinfo->where('id',$teacherid);
            }
        }else{
            $teacherinfo = $teacherinfo->where('tid',auth()->user()->email);
        }
                        
        $teacherinfo = $teacherinfo->leftJoin('employee_personalinfo',function($join){
                            $join->on('teacher.id','=','employee_personalinfo.employeeid');
                        })
                        ->leftJoin('nationality',function($join){
                            $join->on('employee_personalinfo.nationalityid','=','nationality.id');
                        })
                        ->select(
                            'firstname',
                            'lastname',
                            'middlename',
                            'suffix',
                            'dob',
                            'address',
                            'contactnum',
                            'gender',
                            'nationality',
                            'email',
                            'tid',
                            'picurl'
                        )                        
                        ->get();


        foreach($teacherinfo as $item){
            $item->dob = \Carbon\Carbon::create($item->dob)->isoFormat('MMMM DD, YYYY');
        }

        return $teacherinfo;
    }


    public static function submit_grades(Request $request){
        try{

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $term = $request->get('term');
            $selected = $request->get('selected');
            $termholder = $term;

            if($term == "prelemgrade"){
                $term = 'prelemstatus';
            }else if($term == "midtermgrade"){
                $term = 'midtermstatus';
            }else if($term == "prefigrade"){
                $term = 'prefistatus';
            }else if($term == "finalgrade"){
                $term = 'finalstatus';
            }

            DB::table('college_studentprospectus')
                ->whereIn('id',$selected)
                ->where('syid',$syid)
                ->where('semid',$semid)
                ->where(function($query) use($term){
                    $query->whereNull($term);
                    $query->orWhere($term,3);
                })
                ->update([
                    $term => 1
                ]);

            foreach($selected as $item){

                $refid = DB::table('college_studentprosstat')
                            ->insertGetId([
                                'term'=>str_replace('grade','',$termholder),
                                'substat'=>1,
                                'subby'=>auth()->user()->id,
                                'subjstatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                'updatedby'=>auth()->user()->id,
                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                'headerid'=>$item
                            ]);

                if($termholder == "prelemgrade"){
                    $refterm = 'prelimstatref';
                }else if($termholder == "midtermgrade"){
                    $refterm = 'midstatref';
                }else if($termholder == "prefigrade"){
                    $refterm = 'prefistatref';
                }else if($termholder == "finalgrade"){
                    $refterm = 'finaltermstatref';
                }

                
                DB::table('college_studentprospectus')
                    ->where('id',$selected)
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->take(1)
                    // ->orWhere($term,1)
                    ->update([
                        $refterm => $refid
                    ]);
                
            }

            return array((object)[
                'status'=>1,
            ]);
        }catch(\Exception $e){
            return array((object)[
                'status'=>0
            ]);
        }
    }

    public static function ph_approve(Request $request){
        try{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $term = $request->get('term');
            $selected = $request->get('selected');
            if($term == "prelemgrade"){
                $term = 'prelemstatus';
            }else if($term == "midtermgrade"){
                $term = 'midtermstatus';
            }else if($term == "prefigrade"){
                $term = 'prefistatus';
            }else if($term == "finalgrade"){
                $term = 'finalstatus';
            }

            DB::table('college_studentprospectus')
                ->whereIn('id',$selected)
                ->where('syid',$syid)
                ->where('semid',$semid)
                ->where($term,1)
                ->update([
                    $term => 1
                ]);
                
            return array((object)[
                'status'=>1,
            ]);
        }catch(\Exception $e){
            return array((object)[
                'status'=>0
            ]);
        }
    }

    public static function get_grade_status_v2(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->first()
                        ->id;

        $subjects = self::subjects($syid,$semid,$teacherid);

        foreach($subjects as $item){

            $schedotherclass = DB::table('college_scheddetail')
                        ->where('headerid',$item->schedid)
                        ->select('schedotherclass')
                        ->first();

            $item->schedotherclass = isset($schedotherclass->schedotherclass) ? $schedotherclass->schedotherclass : null;

            $grade_status = DB::table('college_grade_status')
                                ->where('schedid',$item->schedid)
                                ->where('deleted',0)
                                ->select(
                                    'prelimstatus',
                                    'midtermstatus',
                                    'prefistatus',
                                    'finalstatus'
                                )
                                ->get();

            foreach($grade_status as $grade_status_item){

                $prelimdate = DB::table('college_gradelogs')
                                        ->where('college_gradeid',$item->schedid)
                                        ->where('term',1)
                                        ->orderBy('id','desc')
                                        ->select(
                                            'createddatetime'
                                        )
                                        ->first();

                $midtermdate = DB::table('college_gradelogs')
                                        ->where('college_gradeid',$item->schedid)
                                        ->where('term',2)
                                        ->orderBy('id','desc')
                                        ->select(
                                            'createddatetime'
                                        )
                                        ->first();

                $prefidate = DB::table('college_gradelogs')
                                        ->where('college_gradeid',$item->schedid)
                                        ->where('term',3)
                                        ->orderBy('id','desc')
                                        ->select(
                                            'createddatetime'
                                        )
                                        ->first();

                $finaldate = DB::table('college_gradelogs')
                                        ->where('college_gradeid',$item->schedid)
                                        ->where('term',4)
                                        ->orderBy('id','desc')
                                        ->select(
                                            'createddatetime'
                                        )
                                        ->first();

                if(isset($prelimdate->createddatetime)){
                    $grade_status_item->prelimdate = \Carbon\Carbon::create($prelimdate->createddatetime)->isoFormat('MM/DD/YYYY hh:mm A');
                }else{
                    $grade_status_item->prelimdate = '';
                }

                if(isset($midtermdate->createddatetime)){
                    $grade_status_item->midtermdate = \Carbon\Carbon::create($midtermdate->createddatetime)->isoFormat('MM/DD/YYYY hh:mm A');
                }else{
                    $grade_status_item->midtermdate = '';
                }

                if(isset($prefidate->createddatetime)){
                    $grade_status_item->prefidate = \Carbon\Carbon::create($prefidate->createddatetime)->isoFormat('MM/DD/YYYY hh:mm A');
                }else{
                    $grade_status_item->prefidate = '';
                }

                if(isset($finaldate->createddatetime)){
                    $grade_status_item->finaldate = \Carbon\Carbon::create($finaldate->createddatetime)->isoFormat('MM/DD/YYYY hh:mm A');
                }else{
                    $grade_status_item->finaldate = '';
                }

            }

            $item->gradestatus = $grade_status;

        }

        return $subjects;



    }
  

    // public static function ($syid,$semid,$teacherid){





















    //old

    public static function schedule(Request $request){

        $schedule = DB::table('college_classsched')
                        ->join('sy',function($join){
                            $join->on('college_classsched.syID','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('semester',function($join){
                            $join->on('college_classsched.semesterID','=','semester.id');
                            $join->where('semester.isactive','1');
                        })
                        ->join('college_sections',function($join){
                                $join->on('college_classsched.sectionID','=','college_sections.id');
                                $join->where('college_sections.deleted','0');
                        })
                        ->join('college_courses',function($join){
                                $join->on('college_sections.courseID','=','college_courses.id');
                                $join->where('college_courses.deleted','0');
                        })
                        ->join('college_prospectus',function($join){
                                $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                $join->where('college_prospectus.deleted','0');
                        });

                    
        if($request->has('teacher') && $request->get('teacher') != null){

            $schedule->where('college_classsched.teacherid',$request->get('teacher'));

        }

        if($request->has('section') && $request->get('section') != null){

            $schedule->where('college_classsched.sectionID',$request->get('section'));

        }

        if($request->has('student') && $request->get('student') != null){

            $schedule->join('college_studsched',function($join){
                    $join->on('college_classsched.id','=','college_studsched.schedid');
                    $join->where('college_studsched.deleted','0');
                    $join->where('college_studsched.schedstatus','!=','DROPPED');
                    $join->select('college_studsched.studid');
            });
            $schedule->join('studinfo',function($join){
                    $join->on('college_studsched.studid','=','studinfo.id');
                    $join->where('studinfo.deleted','0');
                    $join->where('studinfo.studstatus',1);
            });

        }

        if($request->has('select') && $request->get('select') != null){
                $select = explode(',',$request->get('select'));
                foreach($select as $item){
                    try{
                        $schedule->addSelect($item);
                    }catch (\Exception $e){

                    }
            }
        }


        if($request->has('subject') && $request->get('subject') != null){

            $schedule->where('college_prospectus.subjectID',$request->get('subject'));

        }

        if($request->has('gradetable') && $request->get('gradetable') != null){

                $schedule = $schedule->addSelect('college_classsched.subjectID as subjid','gender')->orderBy('gender')->orderBy('lastname')->get();

                $teacher = DB::table('teacher')->where('userid',auth()->user()->id)->select('userid','id')->first();
              
                if(count( $schedule) > 0){

                    $gradesetup = DB::table('college_gradesetup')
                                        ->select('college_gradesetup.*')
                                        ->where('college_gradesetup.deleted','0')
                                        ->where('subjID',$request->get('subject'))
                                        ->where('college_gradesetup.teacherid', $teacher->id)
                                        ->where('college_gradesetup.createdby', $teacher->userid)
                                        ->where('college_gradesetup.sectionid',$request->get('section'))
                                        ->where('college_gradesetup.term',$request->get('term'))
                                        ->get();


                    if(count($gradesetup)  > 0){

                        if(collect($gradesetup)->sum('percentage') != 100){

                            return 0;

                        }

                    }
                    else {
                        return 0;

                    }

                }
                else{

                    $gradesetup = []; 

                }


                $semid = DB::table('semester')->where('isactive',1)->first()->id;
                $syid= DB::table('sy')->where('isactive',1)->first()->id;
                
                foreach($schedule as $item){

                    $studentgrades = DB::table('college_gradesetup')
                            ->join('college_gradesdetail',function($join) use($request){
                                $join->on('college_gradesetup.id','=','college_gradesdetail.headerid');
                                $join->where('college_gradesetup.deleted','0');
                                $join->where('college_gradesdetail.term',$request->get('term'));
                            })
                            ->where('college_gradesetup.syid',$syid)
                            ->where('college_gradesetup.semid',$semid)
                            ->where('college_gradesetup.deleted','0')
                            ->where('subjID',$request->get('subject'))
                            ->where('college_gradesetup.sectionid',$request->get('section'))
                            ->select('college_gradesdetail.*','college_gradesetup.items')
                            ->count();

                }

              

                $term_setup = DB::table('college_gradesterm')
                                ->where('sectionid',$request->get('section'))
                                ->where('subjid',$request->get('subject'))
                                ->where('deleted',0)
                                ->first();

                $canEdit = true;

                if($request->get('term') == 1 && $term_setup->prelimsubmit != 0){

                    $canEdit = false;

                }else if($request->get('term') == 2 && $term_setup->midtermsubmit != 0){

                    $canEdit = false;
                    
                }
                else if($request->get('term') == 3 && $term_setup->prefisubmit != 0){
                    $canEdit = false;
                }
                else if($request->get('term') == 4 && $term_setup->finalsubmit != 0){
                    $canEdit = false;
                }

                return view('ctportal.pages.grading.gradetable')
                            ->with('students',$schedule)
                            ->with('canEdit',$canEdit)
                            ->with('studentgrades',$studentgrades)
                            ->with('gradesetup',$gradesetup);
                      
        }

        else if($request->has('setuptable') && $request->get('setuptable') != null){


            $teacher = DB::table('teacher')->where('userid',auth()->user()->id)->select('userid','id')->first();
          
            $gradesetup = DB::table('college_gradesetup')
                                ->select('college_gradesetup.*')
                                ->where('college_gradesetup.deleted','0')
                                ->where('subjID',$request->get('subject'))
                                ->where('college_gradesetup.teacherid', $teacher->id)
                                ->where('college_gradesetup.createdby', $teacher->userid)
                                ->where('college_gradesetup.sectionid',$request->get('section'))
                                ->where('college_gradesetup.term',$request->get('term'))
                                ->get();

            return view('ctportal.pages.grading.setuptable')
                        ->with('gradesetup',$gradesetup);
                  
        }
        // elseif ($request->has('gradesetup') && $request->get('gradesetup') != null){

        //     // return $request->all();

        //     $teacher = DB::table('teacher')->where('userid',auth()->user()->id)->select('userid','id')->first();

        //     $gradesetup = DB::table('college_gradesetup')
        //                     ->select('college_gradesetup.*')
        //                     ->where('college_gradesetup.deleted','0')
        //                     ->where('subjID',$request->get('subject'))
        //                     ->where('college_gradesetup.teacherid', $teacher->id)
        //                     ->where('college_gradesetup.createdby', $teacher->userid)
        //                     ->where('college_gradesetup.sectionid',$request->get('section'))
        //                     ->where('college_gradesetup.term',$request->get('term'))
        //                     ->get();

            

        //     return $gradesetup;

        // }





        return $schedule->get();

    }

    public function ctgrading(Request $request){

        $teacherInfo = DB::table('teacher')->where('userid',auth()->user()->id)->first();

        $request->merge([
            'teacher'=> $teacherInfo->id,
            'select'=>'sectionDesc,courseDesc,subjDesc,college_classsched.id,college_prospectus.subjectID as subjid,college_classsched.sectionID'
        ]);

        $gradesetup = DB::table('college_gradesetup')
                            ->join('college_gradesdetail',function($join){
                                $join->on('college_gradesetup.id','=','college_gradesdetail.headerid');
                                $join->where('college_gradesetup.deleted','0');
                            })
                            ->where('deleted','0')
                            ->where('teacherid',10)
                            ->where('subjID',2)
                            ->get();
      

        $schedule = collect(self::schedule($request))->groupBy('sectionDesc');

        return view('ctportal.pages.grading.students')->with('schedule',$schedule);

    }


    public function createSetup(Request $request){

        if($request->has('duplicate') && $request->get('duplicate') == 'duplicate'){

            $teacher = DB::table('teacher')
                            ->where('deleted','0')
                            ->where('userid',auth()->user()->id)
                            ->first();

            $existing = DB::table('college_gradesetup')
                            ->where('sectionid',$request->get('sectionid'))
                            ->where('subjID',$request->get('subjid'))
                            ->where('teacherID',$teacher->id)
                            ->where('term',1)
                            ->where('deleted',0)
                            ->get();

            foreach($existing as $item){

                DB::table('college_gradesetup')
                        ->insert([
                            'sectionid'=>$item->sectionid,
                            'subjID'=>$item->subjID,
                            'syid'=>$item->syid,
                            'teacherID'=>$item->teacherID,
                            'semid'=>$item->semid,
                            'items'=>$item->items,
                            'term'=>$request->get('term'),
                            'percentage'=>$item->percentage,
                            'setupDesc'=>$item->setupDesc,
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                        ]);

            }

        
        }
        else{

            $teacher = DB::table('teacher')->where('deleted','0')->where('userid',auth()->user()->id)->first();
            $datetime = Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');

            if($request->get('setupstatus') == 1){

                DB::table('college_gradesetup')
                            ->where('id',$request->get('setupId'))
                            ->where('createdby',auth()->user()->id)
                            ->update([
                                'items'=>$request->get('items'),
                                'percentage'=>$request->get('percentage'),
                                'setupDesc'=>$request->get('setupDesc'),
                                'sectionid'=>$request->get('sectionid'),
                                'updatedby'=>auth()->user()->id,
                                'updateddatetime'=> $datetime
                            ]);

                return 1;

            }
            elseif($request->get('setupstatus') == 2){

                DB::table('college_gradesetup')
                        ->where('id',$request->get('setupId'))
                        ->where('createdby',auth()->user()->id)
                        ->update([
                            'deleted'=>1,
                            'deletedby'=>auth()->user()->id,
                            'deleteddatetime'=> $datetime
                        ]);

                return 2;

            }
            elseif($request->get('setupstatus') == 0){

                $activeSem = DB::table('semester')->where('isactive','1')->first();
                $activeSY = DB::table('sy')->where('isactive','1')->first();

                $id = DB::table('college_gradesetup')
                                ->insertGetId([
                                    'teacherid'=>$teacher->id,
                                    'items'=>$request->get('items'),
                                    'percentage'=>$request->get('percentage'),
                                    'setupDesc'=>$request->get('setupDesc'),
                                    'subjID'=>$request->get('subjID'),
                                    'sectionid'=>$request->get('sectionid'),
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=> $datetime,
                                    'syid'=>$activeSY->id,
                                    'semid'=>$activeSem->id,
                                    'term'=>$request->get('term')
                                    ]
                                );

                return 0;

            }
                
        }

       

    }


    public function storegrades(Request $request){

        $header = 0;
        $first = true;
        $suminputs = 0;
        $lastheader;
        $lastterm;
        $laststudid;
        $gradesetup;

        foreach($request->get('hps') as $item){

            $data = explode(' ',$item['data']);

            DB::table('college_gradesetup')
                        ->where('id',$data[0])
                        ->update([
                            $data[1]=>$item['value']
                        ]);

        }

        foreach($request->get('inputs') as $item){

            $data = explode(' ',$item['data']);
          

            if($first){
                $header = $data[0];
                $first = false;
                $gradesetup = DB::table('college_gradesetup')
                                ->where('id',$header)
                                ->where('deleted',0)
                                ->first();

                $totalgradesetup = $gradesetup->sg1+
                                    $gradesetup->sg2+
                                    $gradesetup->sg3+
                                    $gradesetup->sg4+
                                    $gradesetup->sg5+
                                    $gradesetup->sg6+
                                    $gradesetup->sg7+
                                    $gradesetup->sg8+
                                    $gradesetup->sg9+
                                    $gradesetup->sg10;

            }

            // return  $suminputs;

            if($header != $data[0]){
               
                $tdd = 0;
                
                if($totalgradesetup != 0){
                    
                    $tdd =  ( ( $suminputs / $totalgradesetup ) * 100 ) * ( $gradesetup->percentage / 100 );
                    
                }
                
                DB::table('college_gradesdetail')
                        ->where('studid',$data[2])
                        ->where('headerid',$header)
                        ->where('term',$request->get('term'))
                        ->update([
                            'ig'=>$suminputs,
                            'td'=> $tdd
                        ]);

                       

                $suminputs = 0;
                $header = $data[0];

                $gradesetup = DB::table('college_gradesetup')
                                ->where('id',$header)
                                ->where('deleted',0)
                                ->first();

                $totalgradesetup = $gradesetup->sg1+
                                    $gradesetup->sg2+
                                    $gradesetup->sg3+
                                    $gradesetup->sg4+
                                    $gradesetup->sg5+
                                    $gradesetup->sg6+
                                    $gradesetup->sg7+
                                    $gradesetup->sg8+
                                    $gradesetup->sg9+
                                    $gradesetup->sg10;

            }

            $lastheader = $data[0];
            $lastterm = $request->get('term');
            $laststudid = $data[2];

            $suminputs += $item['value'];

            DB::table('college_gradesdetail')
                        ->where('studid',$data[2])
                        ->where('headerid',$data[0])
                        ->where('term',$request->get('term'))
                        ->update([
                            $data[1]=>$item['value']
                        ]);

        }
        

        // return $suminputs;

        if($suminputs>0){

            DB::table('college_gradesdetail')
                    ->where('studid',$laststudid)
                    ->where('headerid',$lastheader)
                    ->where('term',$lastterm)
                    ->update([
                        'ig'=>$suminputs,
                        'td'=>( ( $suminputs / $totalgradesetup ) * 100 ) * ( $gradesetup->percentage / 100 )
                    ]);

            return "1";

        }

       
    }


    public static function storegradev2(Request $request){

        $field = $request->get('field');
        $value = $request->get('value');
        $studid = $request->get('studid');
        $headerid = $request->get('headerid');
        $ig = $request->get('ig');
        $term = $request->get('term');
        $ws = $request->get('ws');
        $ts = $request->get('ts');

        DB::table('college_gradesdetail')
                ->where('studid',$studid)
                ->where('headerid',$headerid)
                ->where('term',$term)
                ->update([
                    $field=>$value,
                    'ws'=>$ws,
                    'ts'=>$ts,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

        $grade_setup = DB::table('college_gradesetup')
                        ->where('id', $headerid)
                        ->where('deleted',0)
                        ->select('sectionid','subjid','syid','teacherid','term')
                        ->first();

        $more_grade_setup = DB::table('college_gradesetup')
                            ->where('sectionid',$grade_setup->sectionid)
                            ->where('subjid',$grade_setup->subjid)
                            ->where('syid',$grade_setup->syid)
                            ->where('teacherid',$grade_setup->teacherid)
                            ->where('term',$grade_setup->term)
                            ->select('id')
                            ->get();

        foreach($more_grade_setup as $item){

            DB::table('college_gradesdetail')
                    ->where('studid',$studid)
                    ->where('headerid',$item->id)
                    ->where('term',$term)
                    ->update([
                        'ig'=>$ig,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

        }

        return 0;

    }

    public function updatehpsv2(Request $request){

        $field = $request->get('field');
        $value = $request->get('value');
        $headerid = $request->get('headerid');
        $term = $request->get('term');

        $grade_setup = DB::table('college_gradesetup')
                        ->where('id', $headerid)
                        ->where('term', $term)
                        ->take(1)
                        ->update([
                            $field=>$value,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

    }


    public function udpatestudentgd(Request $request){



        $gradesetup = DB::table('college_gradesetup')
                        ->join('college_gradesdetail',function($join) use ($request){
                            $join->on('college_gradesetup.id','=','college_gradesdetail.headerid');
                            $join->where('college_gradesetup.deleted','0');
                            $join->where('college_gradesdetail.studid',$request->get('student'));
                        })
                        ->join('sy',function($join){
                            $join->on('college_gradesetup.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('semester',function($join){
                            $join->on('college_gradesetup.semid','=','semester.id');
                            $join->where('semester.isactive','1');
                        })
                        ->where('college_gradesetup.deleted','0')
                        ->where('college_gradesdetail.term',$request->get('term'))
                        ->where('college_gradesetup.sectionid',$request->get('section'))
                        ->where('subjID',$request->get('subject'))
                        ->count();

      

        if($gradesetup == 0){

          
            $gradesetup = DB::table('college_gradesetup')
                            ->join('sy',function($join){
                                $join->on('college_gradesetup.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            })
                            ->join('semester',function($join){
                                $join->on('college_gradesetup.semid','=','semester.id');
                                $join->where('semester.isactive','1');
                            })
                            ->where('college_gradesetup.deleted','0')
                            ->where('subjID',$request->get('subject'))
                            ->where('college_gradesetup.sectionid',$request->get('section'))
                            ->select('college_gradesetup.id')
                            ->where('college_gradesetup.term',$request->get('term'))
                            ->get();
                          

            // return $gradesetup;

            foreach($gradesetup as $item){

                DB::table('college_gradesdetail')
                    ->insert([
                        'studid'=>$request->get('student'),
                        'headerid'=>$item->id,
                        'term'=>$request->get('term')
                    ]);

            }

            return 1;

        }
        else{

            return 2;

        }

    }

    public function quarterSetup(){
        
    }
    

    public function gradesetup(){

        $teacher = DB::table('teacher')->where('userid',auth()->user()->id)->first()->id;

        $request = new Request([
            'info'  => 'info',
            'teacher' => $teacher
        ]);

        $subjects = self::schedule($request);

        return view('ctportal.pages.setup.gradesetup')
                    ->with('subjects',$subjects);

    }


    public function gradesetuptable(){

        $teacher = DB::table('teacher')->where('userid',auth()->user()->id)->first()->id;

        $request = new Request([
            'info'  => 'info',
            'teacher' => $teacher
        ]);

        $schedule = self::schedule($request);

        foreach($schedule as $item){

            $setup = DB::table('college_gradesetup')
                            ->where('subjID',$item->subjectID)
                            ->where('sectionid',$item->sectionID)
                            ->where('teacherid',$item->teacherID)
                            ->where('deleted',0)
                            ->get();


            $term1setup = collect($setup)->where('term','1')->sum('percentage');
            $term2setup = collect($setup)->where('term','2')->sum('percentage');
            $term3setup = collect($setup)->where('term','3')->sum('percentage');
            $term4setup = collect($setup)->where('term','4')->sum('percentage');

            if($term1setup == 100){

                $item->prelem = 1;

            }
            else{

                $item->prelem = 0;

            }

            if($term2setup == 100){

                $item->midterm = 1;

            }
            else{

                $item->midterm = 0;

            }

            if($term3setup == 100){

                $item->prefi = 1;

            }
            else{

                $item->prefi = 0;

            }

            if($term4setup == 100){

                $item->final = 1;

            }
            else{

                $item->final = 0;

            }
            
            

        }

        // return $schedule;

        return view('ctportal.pages.setup.gradesetuptable')->with('schedule',$schedule);

    }

    public function viewsubjecttermsetup(Request $request){


        if($request->has('create') && $request->get('create') == 'create'){

            DB::table('college_gradesterm')
                ->updateOrInsert(
                    [
                        'sectionid'=>$request->get('sectionid'),
                        'subjid'=>$request->get('subjid'),
                        'createdby'=>auth()->user()->id,
                    ],
                    [
                        'quartersetupid'=>$request->get('quartersetupid'),
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                    ]
                
                );

        }
        else if($request->has('check') && $request->get('check') == 'check'){

            return  DB::table('college_gradesterm')
                        ->where('sectionid',$request->get('sectionid'))
                        ->where('subjid',$request->get('subjid'))
                        ->join('college_gradestermsetup',function($join){
                            $join->on('college_gradesterm.quartersetupid','=','college_gradestermsetup.id');
                            $join->where('college_gradestermsetup.deleted',0);
                        })
                        ->get();

        }
        else if($request->has('updatesubmission') && $request->get('updatesubmission') == 'updatesubmission'){

           


            $studentstermgrades = DB::table('college_gradesetup')
                                    ->join('college_gradesdetail',function($join){
                                        $join->on('college_gradesetup.id','=','college_gradesdetail.headerid');
                                    })
                                    ->join('studinfo',function($join){
                                        $join->on('college_gradesdetail.studid','=','studinfo.id');
                                        $join->where('studinfo.deleted',0);
                                        $join->where('studinfo.studstatus',1);
                                    })
                                    ->where('college_gradesetup.sectionid',$request->get('sectionid'))
                                    ->where('subjID',$request->get('subjid'))
                                    ->where('college_gradesdetail.term',$request->get('term'))
                                    ->where('college_gradesetup.deleted',0)
                                    ->select(
                                        'college_gradesdetail.studid'
                                    )
                                    ->count();

            if($studentstermgrades == 0){

                return "1";

            }

            $gradestermupdate = DB::table('college_gradesterm')
                                    ->where('college_gradesterm.sectionid',$request->get('sectionid'))
                                    ->where('college_gradesterm.subjid',$request->get('subjid'))
                                    ->where('college_gradesterm.deleted',0);

            $gradeloginfo = $gradestermupdate
                                ->join('college_sections',function($join){
                                    $join->on('college_gradesterm.sectionid','=','college_sections.id');
                                })
                                ->join('college_subjects',function($join){
                                    $join->on('college_gradesterm.subjid','=','college_subjects.id');
                                })
                                ->select('subjCode','sectionDesc','college_gradesterm.id')
                                ->first();

            $term = '';

            if($request->get('term') == 1){
                $term = 'prelim';
            }
            else if($request->get('term') == 2){
                $term = 'midterm';
            }
            else if($request->get('term') == 3){
                $term = 'semifinal';
            }
            else if($request->get('term') == 1){
                $term = 'final';
            }

                DB::table('college_gradelogs')->insert([
                    'college_gradeid'=>$gradeloginfo->id ,
                    'type'=>1,
                    'term'=>$request->get('term'),
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'message'=>auth()->user()->name.' submitted '.$gradeloginfo->subjCode.' '.$term.' grades for section '.$gradeloginfo->sectionDesc
                ]);


                if($request->get('term') == 1){

                    $gradestermupdate->update([
                        'fix'=>1,
                        'prelimsubmit'=>1,
                        'college_gradesterm.updatedby'=>auth()->user()->type,
                        'college_gradesterm.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                    ]);

                }
                else if($request->get('term') == 2){

                    $gradestermupdate->update([
                        'fix'=>1,
                        'midtermsubmit'=>1,
                        'college_gradesterm.updatedby'=>auth()->user()->type,
                        'college_gradesterm.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                    ]);

                }
                else if($request->get('term') == 3){

                    $gradestermupdate->update([
                        'fix'=>1,
                        'prefisubmit'=>1,
                        'college_gradesterm.updatedby'=>auth()->user()->type,
                        'college_gradesterm.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                    ]);

                }
                else if($request->get('term') == 4){

                    $gradestermupdate->update([
                        'fix'=>1,
                        'finalsubmit'=>1,
                        'college_gradesterm.updatedby'=>auth()->user()->type,
                        'college_gradesterm.updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD')
                    ]);

                }


        
                

        }
        else if($request->has('table') && $request->get('table') == 'table'){

            $setupInfo = DB::table('college_gradesterm')
                        ->join('college_gradestermsetup',function($join){
                            $join->on('college_gradesterm.quartersetupid','=','college_gradestermsetup.id');
                            $join->where('college_gradestermsetup.deleted',0);
                        })
                        ->where('sectionid',$request->get('sectionid'))
                        ->where('subjid',$request->get('subjid'))
                        ->get();

            $studentstermgrades = DB::table('college_gradesetup')
                                    ->join('college_gradesdetail',function($join){
                                        $join->on('college_gradesetup.id','=','college_gradesdetail.headerid');
                                    })
                                    ->join('studinfo',function($join){
                                        $join->on('college_gradesdetail.studid','=','studinfo.id');
                                        $join->where('studinfo.deleted',0);
                                        $join->where('studinfo.studstatus',1);
                                    })
                                    ->where('college_gradesetup.sectionid',$request->get('sectionid'))
                                    ->where('subjID',$request->get('subjid'))
                                    ->where('college_gradesetup.deleted',0)
                                    ->groupBy('studid')
                                    ->groupBy('college_gradesdetail.term')
                                    ->orderBy('lastname')
                                    ->select(
                                        'studid',
                                        'firstname',
                                        'lastname',
                                        'college_gradesdetail.term',
                                        'gender',
                                        'ig'
                                    )
                                    ->get();

            $studentstermgrades = collect($studentstermgrades)->groupBy('studid');

            $college_gradestermsetup = DB::table('college_gradestermsetup')
                                            ->where('id',$setupInfo[0]->quartersetupid)
                                            ->first();


            return  view('ctportal.pages.grading.gstable')
                        ->with('setupInfo',$setupInfo)
                        ->with('college_gradestermsetup',$college_gradestermsetup)
                        ->with('studentstermgrades',$studentstermgrades);
                   

        }


    }

    public function classsched(Request $request){

        if($request->get('blade') == 'blade' && $request->has('blade')){

            return view('ctportal.pages.schedule.schedule');

        }
        elseif($request->get('table') == 'table' && $request->has('table')){

            $teacherid = DB::table('teacher')
                            ->where('userid',auth()->user()->id)
                            ->where('deleted',0)
                            ->select('id')
                            ->first();


            $activeSy = Db::table('sy')->where('isactive',1)->first();
            $activeSem = Db::table('semester')->where('isactive',1)->first();

            $classsched = DB::table('college_classsched')
                        ->join('college_sections',function($join){
                            $join->on('college_classsched.sectionID','=','college_sections.id');
                            $join->where('college_sections.deleted',0);
                        }) 
                        ->join('college_courses',function($join){
                            $join->on('college_sections.courseID','=','college_courses.id');
                            $join->where('college_courses.deleted',0);
                        })
                        ->join('college_prospectus',function($join){
                            $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            $join->where('college_prospectus.deleted',0);
                        })
                        ->where('teacherID',$teacherid->id)
                        ->where('syid',$activeSy->id)
                        ->where('semesterID',$activeSem->id);

            if($request->get('search') != null && $request->get('search') != 'null' && $request->has('search')){

                $classsched = $classsched->where(function($query) use($request){
                    $query->where('college_prospectus.subjDesc','like','%'.$request->get('search').'%');
                    $query->orWhere('college_courses.courseabrv','like','%'.$request->get('search').'%');
                    $query->orWhere('college_sections.sectionDesc','like','%'.$request->get('search').'%');
                    $query->orWhere('college_prospectus.subjcode','like','%'.$request->get('search').'%');
                    // $query->orWhere('college_scheddetail.stime','like','%'.$request->get('search').'%');
                    // $query->orWhere('college_scheddetail.etime','like','%'.$request->get('search').'%');
                });

            }

            $count = $classsched->count();

            if($request->get('skip') != null && $request->has('skip')){

                $classsched = $classsched->skip(10 *  ( $request->get('skip') - 1 ) );

            }

            if($request->get('take') != null && $request->has('take')){

                $classsched = $classsched->take(10);

            }

            $classsched = collect($classsched->select('college_classsched.id')->get())->map(function($query){
                return $query->id;
            });

           

            $classsched = DB::table('college_classsched')
                        ->whereIn('college_classsched.id', $classsched);

            $classsched =  $classsched->join('college_sections',function($join){
                            $join->on('college_classsched.sectionID','=','college_sections.id');
                            $join->where('college_sections.deleted',0);
                        }) 
                        ->join('college_prospectus',function($join){
                            $join->on('college_classsched.subjectID','=','college_prospectus.id');
                            $join->where('college_prospectus.deleted',0);
                        })
                        ->join('college_courses',function($join){
                            $join->on('college_sections.courseID','=','college_courses.id');
                            $join->where('college_courses.deleted',0);
                        })
                        ->join('college_scheddetail',function($join){
                            $join->on('college_classsched.id','=','college_scheddetail.headerid');
                            $join->where('college_scheddetail.deleted',0);
                        })
                        ->leftJoin('days',function($join){
                            $join->on('college_scheddetail.day','=','days.id');
                        })
                        ->select('sectionDesc',
                                    'courseabrv',
                                    'stime',
                                    'etime',
                                    'scheddetialclass',
                                    'day',
                                    'college_classsched.subjectID',
                                    'subjDesc',
                                    'subjcode',
                                    'description',
                                    'sectionID'
                                );

            $classsched = $classsched->get();

            foreach($classsched as $item){

                $item->ftime = $item->stime.' - '.$item->etime;

            }

            $data = array();

            $bySubject = collect($classsched)->groupBy('sectionID');

            foreach($bySubject as $subjitem){

                $byClass = collect($subjitem)->groupBy('scheddetialclass');

                foreach($byClass as $item){
                    
                    foreach(collect($item)->groupBy('ftime') as $secondItem){

                        $day = '';
                        
                        foreach($secondItem as $thirdItem){

                            $details = $thirdItem;
                    
                            if($thirdItem->description == 'Thursday'){
                                $day .= substr($thirdItem->description, 0 , 1).'h';
                            }
                            elseif($thirdItem->description == 'Sunday'){
                                $day .= substr($thirdItem->description, 0 , 1).'un';
                            }
                            else{
                                $day .= substr($thirdItem->description, 0 , 1).'';
                            }

                        }

                        $details->description = $day;

                        array_push($data, $details);
                    
                    };

                }
            }

            $classsched = collect($data)->groupBy('sectionID');

            $data = array((object)[
                'count'=> $count,
                'data'=> $classsched
            ]);


            // return $data;
            // resources\views\ctportal\pages\schedule\scheduletable.blade.php

            return view('ctportal.pages.schedule.scheduletable')->with('data',$data);


        }

        

        // return $classsched;

        // resources\views\ctportal\pages\schedule\schedule.blade.php

       
                // ->with('classsched',$classsched);

        // return $classsched;


    }

    public static function evaluationTeacherId($teacherid = null){

        if($teacherid != null && $teacherid != null){

            $teacherid = $teacherid;

        }else{

            $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;

        }

        return $teacherid;

    }

    public static function teachersubjects(){

        $teacherid  = self::evaluationTeacherId();

        
        $schedule = DB::table('college_classsched')
                        ->join('sy',function($join){
                            $join->on('college_classsched.syID','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('semester',function($join){
                            $join->on('college_classsched.semesterID','=','semester.id');
                            $join->where('semester.isactive','1');
                        })
                        ->join('college_sections',function($join){
                                $join->on('college_classsched.sectionID','=','college_sections.id');
                                $join->where('college_sections.deleted','0');
                        })
                        ->where('college_classsched.deleted',0)
                        ->where('teacherid',$teacherid)
                        ->select('college_sections.id','sectionDesc')
                        ->distinct('sectionDesc')
                        ->get();

        return $schedule;


    }


    public function studentinformation(Request $request){

        $teacherInfo = DB::table('teacher')->where('userid',auth()->user()->id)->first();

        $request->merge([
            'teacher'=> $teacherInfo->id,
            'select'=>'sectionDesc,courseDesc,subjDesc,college_classsched.id,college_prospectus.subjectID as subjid,college_classsched.sectionID,college_prospectus.subjCode,college_classsched.subjectID as schedsubj'
        ]);

        $schedule = collect(self::schedule($request))->groupBy('sectionDesc');
        $scheds = array();
        $sections = array();
        $subjects = array();


        // return $schedule;
    
        foreach($schedule as $section){

            foreach($section as $item){

                array_push($scheds , $item->id);

                array_push($sections, (object)[
                        'sectionid'=>$item->sectionID,
                        'sectionname'=>$item->sectionDesc
                    ]);

                array_push($subjects, (object)[
                    'subjectid'=>$item->schedsubj,
                    'subjCode'=>$item->subjCode,
                    'sectionid'=>$item->sectionID
                ]);

            }

        }

        // return $subjects;

        if($request->get('blade') == 'blade' && $request->has('blade')){

            return view('ctportal.pages.studentinformation.studentinformation')
                        ->with('subjects',$subjects)
                        ->with('sections',$sections);

        }else if($request->get('data') == 'data' && $request->has('data') 
        // && $request->ajax()
        ){
            
             $students = DB::table('college_studsched')
                            ->whereIn('schedid',$scheds)
                            ->join('studinfo',function($join){
                                $join->on('college_studsched.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                                $join->whereIn('studstatus',[1,2,4]);
                            })
                            ->join('college_classsched',function($join){
                                $join->on('college_studsched.schedid','=','college_classsched.id');
                                $join->where('college_classsched.deleted',0);
                            })
                            ->select('studinfo.id','lastname','firstname','sid','studinfo.sectionid','schedid','subjectID','gender')
                            ->where('college_studsched.deleted',0)
                            ->get();
                        

            $month = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MM');


            foreach($students as $student){

                $assessment = Billing::monthlyAssessment($student->id, $month);

                $permit = 0;
                $student->quarter = null;
                                
                if(count($assessment) == 0){

                    $permit = 0;

                }
                else{
                    
                    if(collect($assessment)->sum('balance') == 0){

                        $permit = 1;

                    }
                    else{

                        $permit = 0;
                    }
                }

                if($permit == 0){

                    $assessment = Billing::checkExamPermitStatus($student->id, $month);
                    if(count($assessment) > 0){

                        $permit = 1;
                        $student->quarter = $assessment[0]->description;

                    }

                }

                $student->permit = $permit;
                $student->gender = strtolower($student->gender);

            }

            return $students;
            
        }
                              

    }

    public function grade_submission(Request $request){
        return view('ctportal.pages.grade_submission.grade_submission');
    }

    public function get_assigned_subj(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $teacherid = $request->get('teacherid');
        $sectionid = $request->get('sectionid');
        return \App\Models\CollegeInstructor\CollegeInstructorData::get_assigned_subj($syid, $semid, $teacherid, $sectionid);
    }

    public function subject_students(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $sectionid = $request->get('sectionid');
        $subjid = $request->get('subjid');
        return \App\Models\CollegeInstructor\CollegeInstructorData::subject_student_grades($syid, $semid, $sectionid, $subjid);
    }

    //  public function get_grade_status(Request $request){
    //     $syid = $request->get('syid');
    //     $semid = $request->get('semid');
    //     $sectionid = $request->get('sectionid');
    //     $subjid = $request->get('subjid');
    //     $taecherid = DB::table('teacher')->where('userid',auth()->user()->id)->first()->id;
    //     return \App\Models\CollegeInstructor\CollegeInstructorData::get_grade_status($syid, $semid, $sectionid, $subjid,$taecherid);
    // }

    public function submit_grade_status(Request $request){
        $statusid = $request->get('statusid');
        $datafield = $request->get('datafield');
        return \App\Models\CollegeInstructor\CollegeInstructorProccess::submit_grades_status($statusid,$datafield);
    }

    public function save_student_grade(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $subjid = $request->get('subjid');
        $grade = $request->get('grade');
        $field = $request->get('field');
        $studid = $request->get('studid');
        return \App\Models\CollegeInstructor\CollegeInstructorData::save_student_grade($syid, $semid, $subjid, $grade, $field, $studid);
    }


    public function ci_schedule(Request $request){
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $type = $request->get('type');
        $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
        return \App\Models\CollegeInstructor\CollegeInstructorData::get_schedule($syid,$semid,$teacherid,$type);
    }

    
    
}

