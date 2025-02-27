<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;
class SchoolCalendarController extends Controller
{
    public function show(){


        $acad_prog = DB::table('academicprogram')
            ->select(
                'id',
                'progname as text'
            )
            ->get();

        $users= DB::table('users')
            ->select(
                'id',
                'name as text'
            )
            ->get();

        $college_courses = DB::table('college_courses')
            ->where('deleted', 0)
            ->select(
                'id',
                'courseabrv as text'
            )
            ->get(); 


        $gradelevel = DB::table('gradelevel')
            ->where('deleted', 0)
            ->select(
                'id',
                'levelname as text'
            )
            ->get();   

        $college_colleges = DB::table('college_colleges')
            ->where('deleted', 0)
            ->select(
                'id',
                'collegeabrv as text'
            )
            ->get(); 

        $faculty = DB::table('school_calendarfaculty')
            ->where('deleted', 0)
            ->select(
                'id',
                'name as text'
            )
            ->get();

        $activeSY = DB::table('sy')
            ->where('isactive', 1)
            ->first();
        $sy = DB::table('sy')
            ->get();

        $sem = DB::table('semester')
            ->where('isactive', 1)
            ->first();
            
        return view('superadmin.pages.school-calendar.school-calendar',[

            'acad_prog'=>$acad_prog,
            'gradelevel'=>$gradelevel,
            'courses'=>$college_courses,
            'colleges'=>$college_colleges,
            'faculty'=>$faculty,
            'activeSY'=>$activeSY,
            'sy'=>$sy,
            'sem'=>$sem
        ]);
        
    }

    public function getall_event($type, $syid, Request $request){

        $today = Carbon::today()->toDateString(); // Get today's date in 'YYYY-MM-DD' format
    
        if ($type == 7 || $type == 9) {
            $events = DB::table('school_calendar')
                ->where('deleted', 0)
                ->where('syid', $syid)
                ->whereIn('type', [0, 1])
                ->select(
                    'id',
                    'title',
                    'stime',
                    'etime',
                    'start',
                    'end',
                    // DB::raw("CONCAT('$today', ' ', stime) AS start"),  // Combine the date with stime
                    // DB::raw("CONCAT('$today', ' ', etime) AS end"),    // Combine the date with etime
                    'allDay'
                )
                ->get();
    
                foreach ($events as $value) {
                    // Convert the allDay field to a boolean
                    $value->allDay = $value->allDay == 1;
                    
                    $sdate = explode(' ', $value->start)[0];
                    $edate = explode(' ', $value->end)[0];
    
                    $value->start = $sdate . ' ' . $value->stime;
                    $value->end = $edate . ' ' . $value->etime;
                }
    
            return response()->json($events); // Move this outside of the loop
        } else {
            $events = DB::table('school_calendar')
                ->where('deleted', 0)
                ->where('syid', $syid)
                ->when(isset($request->param1) && $request->param1 == 'thismonth', function ($query) {
                    // Filter by current month if param1 is 'thismonth'
                    $query->whereMonth('start', Carbon::now('Asia/Manila')->month)
                          ->whereYear('start', Carbon::now('Asia/Manila')->year);
                })
                ->when(isset($request->param2) && $request->param2 == 'thisday', function ($query) {
                    // Filter by the current day if param2 is 'thisday'
                    $query->whereDate('start', Carbon::today('Asia/Manila'));
                })
                
                ->select(
                    'id',
                    'title',
                    'stime',
                    'etime',
                    // DB::raw("CONCAT('0000-00-00', ' ', stime) AS start"), 
                    // DB::raw("CONCAT('0000-00-00', ' ', etime) AS end"), 
                    'start',  // Use the actual start from DB
                    'end',    // Use the actual end from DB
                    'allDay'
                )
                ->get();
    
            foreach ($events as $value) {
                // Convert the allDay field to a boolean
                $value->allDay = $value->allDay == 1;
                
                $sdate = explode(' ', $value->start)[0];
                $edate = explode(' ', $value->end)[0];

                $value->start = $sdate . ' ' . $value->stime;
                $value->end = $edate . ' ' . $value->etime;
            }
    
            return response()->json($events); // Move this outside of the loop
        }
    }
    public function get_event(Request $request){

        $event = DB::table('school_calendar')
            ->select('school_calendar.*', 'schoolcaltype.typename', 'hr_holidaytype.description')
            ->leftJoin('schoolcaltype', 'school_calendar.holiday', '=', 'schoolcaltype.id')
            ->leftJoin('hr_holidaytype', 'school_calendar.holidaytype', '=', 'hr_holidaytype.id')
            ->where('school_calendar.id', $request->id)
            ->where('school_calendar.deleted', 0)
            ->where('schoolcaltype.deleted', 0)
            ->get();

        return $event;
        
    }

    public function add_event(Request $request) {
        $eventtypeid = $request->get('eventypeid');
        $time = explode(" - ", $request->get('ttime'));
        $stime = '';
        $etime = '';
        
        if (!empty($time) && count($time) == 2 && !empty($time[0]) && !empty($time[1])) {
            // If $time is not empty and has two valid parts
            $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
            $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
        } else {
            // If $time is empty or invalid, set default times
            $stime = Carbon::create('08:00:00')->isoFormat('HH:mm:ss'); // Default start time
            $etime = Carbon::create('17:00:00')->isoFormat('HH:mm:ss'); // Default end time
        }
    
        // dd($stime, $etime);
    
        $startDate = Carbon::parse($request->start); // Parse the start date from the request
        $endDate = Carbon::parse($request->end);     // Parse the end date from the request
    
        // Check if the end time is earlier than the start time, indicating the event crosses over to the next day
        if ($etime < $stime) {
            $endDate->addDay();  // Add 1 day to the end date if the time exceeds midnight
        }
    
        $colleges = null;
        $courses = null;
    
        if ($request->collegeid != null) {
            $colleges = implode(" ", $request->collegeid);
        }
        if ($request->courseid != null) {
            $courses = implode(" ", $request->courseid);
        }
    
        if ($eventtypeid == 1) {
            $ifeventexist = DB::table('school_calendar')
                ->where('title', $request->event_desc)
                ->where('start', $request->start)
                ->where('end', $request->end)
                ->where('deleted', 0)
                ->count();
            
            if ($ifeventexist) {
                return array (
                    (object)[
                        'status' => 409,
                        'statusCode' => 'warning',
                        'message' => 'Event Already Exists!'
                ]); 
            } else {
                DB::table('school_calendar')
                    ->insert([
                        "start" => $startDate->toDateString(), 
                        "end" => $endDate->toDateString(),
                        "title" => $request->event_desc, 
                        "venue"=> $request->act_venue ? $request->act_venue : 'Not Specified',
                        "involve"=> $request->involve ? $request->involve : 'Not Specified',
                        'isnoclass'=> $request->isNoClass,
                        'gradelevelid'=> $request->gradelevelid,
                        'acadprogid'=> $request->acadprogid,
                        'courseid'=> $courses,
                        'collegeid'=> $colleges,
                        'type'=> $request->type,
                        'syid'=> $request->syid,
                        'holiday' => $request->holiday,
                        'holidaytype' => $request->typeholiday,
                        'withpay' => 1,
                        'stime' => $stime,
                        'etime' => $etime
                    ]);
                
                return array (
                    (object)[
                        'status'=>200,
                        'statusCode'=>"success",
                        'message'=>'Event Added Successfully!'
                ]); 
            }
        } else {
            $ifeventexist = DB::table('school_calendar')
                ->where('title', $request->event_desc)
                ->where('venue', $request->act_venue)
                ->where('start', $request->start)
                ->where('end', $request->end)
                ->count();
            
            if ($ifeventexist) {
                return array (
                    (object)[
                        'status' => 409,
                        'statusCode' => 'warning',
                        'message' => 'Event Already Exists!'
                ]); 
            } else {
                DB::table('school_calendar')
                    ->insert([
                        "start" => $startDate->toDateString(), 
                        "end" => $endDate->toDateString(),
                        "title" => $request->event_desc, 
                        "venue"=> $request->act_venue,
                        "involve"=> $request->involve,
                        'isnoclass'=> $request->isNoClass,
                        'gradelevelid'=> $request->gradelevelid,
                        'acadprogid'=> $request->acadprogid,
                        'courseid'=> $courses,
                        'collegeid'=> $colleges,
                        'type'=> $request->type,
                        'syid'=> $request->syid,
                        'holiday' => $request->holiday,
                        'stime' => $stime,
                        'etime' => $etime
                    ]);
                
                return array (
                    (object)[
                        'status'=>200,
                        'statusCode'=>"success",
                        'message'=>'Event Added Successfully!'
                ]); 
            }
        }
    }
    
    
    public function update_event(Request $request){

        if(strlen($request->start) == 10){

            DB::table('school_calendar')
            ->where('id', $request->id)
            ->update([

                "start" => $request->start, 
                "end" => $request->end, 
                "allDay" => 1
            ]);

        }else{

            DB::table('school_calendar')
            ->where('id', $request->id)
            ->update([

                "start" => $request->start, 
                "end" => $request->end, 
                "allDay" => 0
            ]);
        }

        
    }

    public function update_event_details(Request $request){

        $colleges = null;
        $courses = null;

        if($request->collegeid != null){

            $colleges =  implode(" ",$request->collegeid);
        }
        if($request->courseid != null){

            $courses =  implode(" ",$request->courseid);
        }

        DB::table('school_calendar')
            ->where('id', $request->id)
            ->update([

                "title" => $request->event_desc, 
                "venue"=> $request->act_venue,
                "involve"=> $request->involve,
                'isnoclass'=> $request->isNoClass,
                'gradelevelid'=> $request->gradelevelid,
                'acadprogid'=> $request->acadprogid,
                'courseid'=> $courses,
                'collegeid'=> $colleges,
                'type'=> $request->type,
                'holiday'=> $request->holiday
            ]);
        
        $event = DB::table('school_calendar')
            ->where('id', $request->id)
            ->where('deleted', 0)
            ->get();
        
        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Event Updated Successfully!',
            'event'=>$event

        ]); 
    }

    public function delete_event(Request $request){

        $event = DB::table('school_calendar')
            ->where('id', $request->id)
            ->update([

                "deleted" => 1
            ]);

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Event Removed Successfully!'

        ]); 
    }

    public function get_select2_gradelvl(Request $request){

        if($request->acad_prog == 2){

            $gradelevel = $this->gradelevelQuery(2);

        }

        else if($request->acad_prog == 3){

            $gradelevel = $this->gradelevelQuery(3);

        }

        else if($request->acad_prog == 4){

            $gradelevel = $this->gradelevelQuery(4);

        }

        else if($request->acad_prog == 5){

            $gradelevel= $this->gradelevelQuery(5);
        }

        else if($request->acad_prog == 6){

            $gradelevel = $this->gradelevelQuery(6);
        }

        else{

            $gradelevel = $this->gradelevelQuery(7);
        }

        return response()->Json([

            'gradelevel'=> $gradelevel

        ]);
          
    }

    public function get_select2_faculty(Request $request){

        $faculty = DB::table('school_calendarfaculty')
        ->where('deleted', 0)
        ->select(
            'id',
            'name as text'
        )
        ->get();

        return response()->Json([

            'faculty'=>$faculty

        ]);
          
    }
    
    public function add_faculty(Request $request){

        DB::table('school_calendarfaculty')
            ->insert([

                "name" => $request->name,  
        ]);
    
        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Added Faculty!'

        ]); 
 
    }

    public function generatePDF($syid){

        $events = DB::table('school_calendar')
            ->where('deleted', 0)
            ->where('syid', $syid)
			->orderBy('start', 'ASC')
            ->get();

        $schoolinfo = DB::table('schoolinfo')
            ->first();
            
        $schoolyear = DB::table('sy')
            ->where('id', $syid)
            ->first();


        $pdf = PDF::loadView('superadmin.pages.printable.schoolcalendar-pdf', compact('events', 'schoolinfo', 'schoolyear'))->setPaper('legal', 'portrait');
        
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);

        return $pdf->stream();

    }

    public function generateExcel($syid){

        //initialize

        $events = DB::table('school_calendar')
            ->where('deleted', 0)
            ->where('syid', $syid)
            ->get();

        $schoolinfo = DB::table('schoolinfo')
            ->first();
            
        $schoolyear = DB::table('sy')
            ->where('id', $syid)
            ->first();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("schoolcalendar/school-calendar-temp.xlsx");


        //

        
        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $sheet->setCellValue('A3', $schoolinfo->schoolname);
        $sheet->setCellValue('A4', $schoolinfo->address);
        $sheet->setCellValue('F7', "SY ".$schoolyear->sydesc);

        $months = [
            "January",
            "Febuary",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];

        $count = 11;
        $itemCounter = 0;
        $monthly = 12;
        for ($i=0; $i < count($months); $i++) {
            
            $sheet->setCellValue('C'.($count+$itemCounter), $months[$i]);
            $sheet->getStyle('C'.($count+$itemCounter))->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('1c1d1f');

            $sheet->getStyle('C'.($count+$itemCounter))->getFont()->getColor()->setARGB('ffffff');
            $sheet->mergeCells('C'.($count+$itemCounter).':H'.($count+$itemCounter));


            foreach ($events as $event) {
            
                // $count++;
    
                $month = date_create($event->start); 
                $date = date_create($event->start); 
                $dayS = date_create($event->start); 
                $dayE = date_create($event->end); 
                    
                $startday = date_format(date_create($event->start),"d"); 
                $endday = date_format(date_create($event->end),"d"); 
                $themonth = date_format(date_create($event->start),"M"); 

                if( date_format($month,"m") == ($i+1)){
    
                    if($monthly >= 11){


                        if($startday == $endday){

                            $sheet->setCellValue('C'.($monthly+$itemCounter), date_format($dayS,"d"));
                            $sheet->setCellValue('D'.($monthly+$itemCounter), date_format($dayS,"D"));
        
                        }else{
                            $sheet->setCellValue('C'.($monthly+$itemCounter), $startday.'-'.$endday);
                            $sheet->setCellValue('D'.($monthly+$itemCounter), date_format($dayS,"D")."-".date_format($dayE,"D"));
                        }
                        
                        $sheet->setCellValue('E'.($monthly+$itemCounter), $event->title);
                        $sheet->setCellValue('F'.($monthly+$itemCounter), $event->venue);
                        $sheet->setCellValue('G'.($monthly+$itemCounter), $event->involve);
                        // $sheet->setCellValue('H'.($monthly+$itemCounter), $themonth);
    
                    }

                    $itemCounter++;
                }

                            
            }

            $monthly++;
            $count++;


        }
        

        //


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="calendar-'.$schoolyear->sydesc.'.xlsx"');
        $writer->save("php://output");
        exit();
        
    }

    public function edit_faculty(Request $request){

        DB::table('school_calendarfaculty')
            ->where('id', $request->id)
            ->update([
                'name' => $request->name, 
            ]);
    
        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Saved!',
            'id'=>$request->id

        ]); 
 
    }

    public function delete_faculty(Request $request){

        DB::table('school_calendarfaculty')
            ->update([

                "name" => $request->name, 
                
        ]);
    
        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Deleted Faculty!'

        ]); 
 
    }

    public static function gradelevelQuery($id){

        $gradelevel = DB::table('gradelevel')
        ->where('acadprogid', $id)
        ->where('deleted', 0)
        ->select(
            'id',
            'levelname as text'
        )
        ->get();


        return $gradelevel;
    }
	
	public static function load_schoolcaltype(Request $request){
        $schoolcaltype = DB::table('schoolcaltype')
            ->where('deleted', 0)
            ->select(
                'id',
                'typename as text',
                'type'
            )
            ->get();

        return $schoolcaltype;
	}


    // Additional Gian

    public static function loadallholidaytype(Request $request){
        $data = DB::table('hr_holidaytype')
            ->select('id', 'description as text')
            ->where('deleted', 0)
            ->get();

        return $data;
    }
    // Adding Activity
    public static function add_activity(Request $request){
        
        $ifexist = DB::table('schoolcaltype')
            ->where('deleted', 0)
            ->where('typename', $request->desc)
            ->count();
        
        if ($ifexist) {
            return array (
                (object)[
                'status'=>400,
                'statusCode'=>"warning",
                'message'=>'Already Exist!'
            ]); 
        } else {
            $data = DB::table('schoolcaltype')
            ->insert([
                'type' => $request->eventypeid,
                'typename' => $request->desc,  
                'createdby' => auth()->user()->id,
                'createddatetime' => date('Y-m-d H:i:s')
            ]);

            return array (
                (object)[
                'status'=>200,
                'statusCode'=>"success",
                'message'=>'Succesfully Added Activity!'
            ]); 
        }
        
	}

    // Adding Holiday
    public static function add_holiday(Request $request){
        
        $ifexist = DB::table('schoolcaltype')
            ->where('deleted', 0)
            ->where('typename', $request->desc)
            ->count();
        
        if ($ifexist) {
            return array (
                (object)[
                'status'=>400,
                'statusCode'=>"warning",
                'message'=>'Already Exist!'
            ]); 
        } else {
            $data = DB::table('schoolcaltype')
            ->insert([
                'type' => $request->eventypeid,
                'typename' => $request->desc,  
                'createdby' => auth()->user()->id,
                'createddatetime' => date('Y-m-d H:i:s')
            ]);

            return array (
                (object)[
                'status'=>200,
                'statusCode'=>"success",
                'message'=>'Succesfully Added Holiday!'
            ]); 
        }
        
	}
    
}
