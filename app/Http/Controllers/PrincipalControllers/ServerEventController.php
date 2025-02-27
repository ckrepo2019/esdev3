<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use DB;
use \Carbon\Carbon;

class ServerEventController extends Controller
{
    public function getEventStream() {

        $teachers = DB::table('teacher')->get();
        $teachersAttendances = DB::table('teacherattendance')->get();
        $todate = Carbon::now();
        $countPresentTeachers = 0;
        $countAbsentTeachers = 0;
        $countLateTeachers = 0;
        $countOnTimeTeachers = 0;

        $data = array();

        foreach($teachers as $teacher){
            $date = $teacher->id;
            $teachersAttendances = DB::table('teacherattendance')
                                    ->where('teacher_id',$teacher->id)
                                    ->whereDate('in_am',Carbon::now()->isoFormat('Y-MM   -DD'))
                                    ->take(1)->get();
            if(count($teachersAttendances)>0){
                $time = new Carbon($teachersAttendances[0]->in_am);

                if($time->format('H:i')<="07:30"){
                    array_push($data,array(
                        "teacher"=>$teacher->lastname.', '.$teacher->firstname,
                        "attendance"=>count($teachersAttendances),
                        "time"=>$time->format('h:i a'),
                        "textcolor"=>"text-success"
                    ));
                    $countOnTimeTeachers += 1;
                }
                else{
                    array_push($data,array(
                        "teacher"=>$teacher->lastname.', '.$teacher->firstname,
                        "attendance"=>count($teachersAttendances),
                        "time"=>$time->format('h:i a'),
                        "textcolor"=>"text-warning"
                    ));
                    $countLateTeachers += 1;
                }

                $countPresentTeachers+=1;
            }
            else{
                array_push($data,array(
                    "teacher"=>$teacher->lastname.', '.$teacher->firstname,
                    "attendance"=>count($teachersAttendances),
                    "time"=>"00:00",
                    "textcolor"=>"text-danger"
                ));

                $countAbsentTeachers += 1;
                $countLateTeachers += 1;
            }
        }
        
        $data = [

            'data' => $data,
            'absentpercentage' => round(( $countAbsentTeachers / count($teachers) ) * 100) ,
            'presentpercentage' => round(( $countPresentTeachers / count($teachers) ) * 100) ,
            'latepercentage' => round(( $countLateTeachers / count($teachers) ) * 100 ),
            'ontimepercentage' => round(($countOnTimeTeachers / count($teachers)) * 100),
         
        ];

        $response = new StreamedResponse();
        $response->setCallback(function () use ($data){
             echo 'data: ' . json_encode($data) . "\n\n";
             ob_flush();
             flush();
             usleep(200000);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->send();
    }
}
