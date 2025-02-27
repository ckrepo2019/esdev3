<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use \Carbon\CarbonPeriod;
use DB;
use App\Models\Principal\LoadData;
use Illuminate\Support\Collection;
use App\Models\Principal\SPP_Student;


class AttendanceReport extends Model
{

    //genererate SchoolDays with given school year
    public static function generatePeriod($schoolyear){

        $array_of_words = explode("-", $schoolyear->sydesc);

        $schoolyearstarted = "June 01, ".$array_of_words[0];
        $endofschoolyear = Carbon::now();

        $periods =  array_reverse(CarbonPeriod::create($schoolyearstarted, $endofschoolyear)->toArray());

        foreach($periods as $key=>$period){
            if($period->isWeekday()){
                $periodDate = new Carbon($period);
                $periods[$key] =  $periodDate->isoFormat('YYYY-MM-DD');
            }
            else{
                unset($periods[$key] );
            }
        }

        return $periods;

    }

    //generate student attendance of the current school year
    public static function attendanceReport($studentid){

        $schoolyear =  DB::table('sy')->where('isactive','1')->first();

        $periods = self::generatePeriod($schoolyear);

       

        $attendancelist = DB::table('studattendance')
                        ->where('studid',$studentid)
                        ->select('intimeam','intimepm','tdate')
                        ->whereIn('tdate',array_values($periods))
                        ->get();
        
        return self::loadAttendance( $periods, $attendancelist);

    }

    //generate student attendance base on given year
    public static function schoolYearBasedAttendanceReport($studentinfo){
        if($studentinfo->acadprogid=='5'){
            $enrolledstud = DB::table('sh_enrolledstud')->where('studid',$studentinfo->id)->where('levelid',$studentinfo->levelid)->first();
        }

        else{

            $enrolledstud = DB::table('enrolledstud')->where('studid',$studentinfo->id)->where('levelid',$studentinfo->levelid)->first();
        }

            $schoolyear =  DB::table('sy')->where('id',$enrolledstud->syid)->first();

            $periods = self::generatePeriod( $schoolyear);

            $attendancelist = DB::table('studattendance')
                                ->where('studid',$studentinfo->id)
                                ->select('intimeam','intimepm','tdate')
                                ->whereIn('tdate',array_values($periods))
                                ->get();
            
            return self::loadAttendance( $periods, $attendancelist);

    }

    //generate teachers attendance of the current school year
    public static function teacherAttendanceReport($teacherId){




        $schoolyear =  DB::table('sy')->where('isactive','1')->first();

        $periods = self::generatePeriod( $schoolyear);


       
        $attendancelist = DB::table('teacherattendance')
                            ->where('teacher_id',$teacherId)
                            ->select('in_am as intimeam','in_pm as intimepm','tdate')
                            ->whereIn('tdate',array_values($periods))
                            ->get();
    
        return self::loadAttendance( $periods, $attendancelist);

    }

    //generate attendance within given period of student attendancelist
    public static function loadAttendance($periods,$attendancelist){
     
        $attendance = array();
        $monthlyReport = array();
        $yearlyReport = array();
        $data = array();

        $currentMonth = new Carbon( collect($periods)->first());

        $count = 0;
        $countDays = 0;

        $countMonthLate = 0;
        $countMonthOnTime = 0;
        $countMonthPresent = 0;
        $countMonthAbsent = 0;
        $countMonthDays = 0;
        $countMonthHF = 0;

        $countLate = 0;
        $countOnTime = 0;
        $countPresent = 0;
        $countAbsent = 0;

        $dates =   collect($attendancelist);

        foreach($periods as $item){

            $pluckDate =  $dates->where('tdate',$item);

            $periodDate = new Carbon($item);
         
            $countMonthDays+=1;

            if($currentMonth->month != Carbon::create($item)->month){

                array_push($monthlyReport,(object) array(
                    "month"=>$currentMonth->isoFormat('MMM'),
                    "late"=>$countMonthLate,
                    "ontime"=>$countMonthOnTime,
                    "present"=>$countMonthPresent,
                    "absent"=>$countMonthAbsent,
                    "numDays"=>$countMonthDays-1
                ));
                
                $countMonthLate = 0;
                $countMonthOnTime = 0;
                $countMonthPresent = 0;
                $countMonthAbsent = 0;
                $currentMonth = new Carbon($item);
                $countMonthDays=1;

            }
            
            if(count($pluckDate)>0){

             
                $pluckDate = $dates[$dates->where('tdate',$item)->keys()[0]];

                $countPresent+=1;

                $countMonthPresent+=1;

                $timestamp = Carbon::create($pluckDate->intimeam)->isoFormat(' hh:mm a');

                $midtime = Carbon::create('12:00 am')->isoFormat(' hh:mm a');

                $inpm = new  Carbon($pluckDate->intimepm);

                $inTime = Carbon::create('07:30:00')->isoFormat(' hh:mm a');

               
                $status="";

                if($pluckDate->intimeam == NULL
                     && $pluckDate->intimepm == NULL
                     ){
                    $status = "absent";
                    $countAbsent+=1;
                    $countMonthAbsent+=1;
                }
        
                else if($inTime < $timestamp){

                    if($timestamp=='12:00 am'){
                        $status = "half day";
                        $countMonthHF+=1;
                        $countMonthLate+=1;
                    }
                    else{
                        $status = "late";
                        $countLate+=1;
                        $countMonthLate+=1;
                    }
                    
                }

                else{

                    $countOnTime+=1;
                    $countMonthOnTime+=1;
                    $status =  "on time";
                }
        
                if($pluckDate->intimeam == NULL
                     && $pluckDate->intimepm == NULL
                     ){
                    array_push($attendance,(object) array(
                        "time"=>$periodDate->isoFormat('hh:mm a'),
                        "date"=>$periodDate->isoFormat('MMM DD YYYY'),
                        "status"=>$status
                    ));
                }
                else if($timestamp == $midtime ){
                    array_push($attendance,(object) array(
                        "time"=>$inpm->isoFormat('hh:mm a'),
                        "date"=>$periodDate->isoFormat('MMM DD YYYY'),
                        "status"=>$status
                    ));
                    
                }

                else{
                    array_push($attendance,(object) array(
                        "time"=>$timestamp,
                        "date"=>$periodDate->isoFormat('MMM DD YYYY'),
                        "status"=>$status
                    ));
                }

            }
            else{
                $countAbsent+=1;
                $countMonthAbsent+=1;
                array_push($attendance,(object) array(
                    "time"=>$periodDate->isoFormat('hh:mm a'),
                    "date"=>$periodDate->isoFormat('MMM DD YYYY'),
                    "status"=>"absent"
                ));
            }
            
        }

        array_push( $yearlyReport,(object) array(
            "late"=>$countLate,
            "ontime"=>$countOnTime,
            "present"=>$countPresent,
            "absent"=>$countAbsent,
            "numDays"=>count($periods),
            "halfday"=>$countMonthHF,
        ));

        array_push( $data, (object) array(
                        'daily'=>$attendance,
                        'monthly'=>$monthlyReport,
                        'yearly'=>$yearlyReport[0]
                    ));

      
        
        return  $data ;

    }

    //current day beadle attendance
    public static function todaySubjectAttendance($studendinfo,$sectionid){

        $nowDate = Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD');

        $classScheds = SPP_Student::getTodayStudentClassScheduleJHS($sectionid);
        
        foreach($classScheds as $classSched){

            $subjectattendance =  DB::table('studentsubjectattendance')
                    ->where('studentsubjectattendance.section_id',$classSched->sectionid)
                    ->where('studentsubjectattendance.subject_id',$classSched->subjid)
                    ->where('student_id',$studendinfo)
                    ->where('date', $nowDate)
                    ->get();

            if(count($subjectattendance)>0){

                $classSched->subjectattendance = strtoupper($subjectattendance[0]->status);
            }
            else{
                $classSched->subjectattendance = "NOT CHECKED";
            }

            date_default_timezone_set('Asia/Manila');
            $date = date('Y-m-d H:i:s');
            
            $now = Carbon::now('Asia/Manila')->toArray();

            return   $classSched->stime;

            $newSchedFormatstart =  Carbon::create( $nowDate.$classSched->stime, 'Asia/Manila');

            $newSchedFormatend =  Carbon::create( $nowDate.$classSched->etime, 'Asia/Manila');

            $currentTimte = Carbon::create($now['year'], $now['month'], $now['day'],$now['hour'],$now['minute'],$now['second']);
            
            return $newSchedFormatstart;


            if($currentTimte>=$newSchedFormatstart && $currentTimte<=$newSchedFormatend){
                $classSched->classstatus ="current class";
            }
            else if($currentTimte>$newSchedFormatstart){
                $classSched->classstatus ="Class Over";
            }
            else{
                $classSched->classstatus = $currentTimte->diff($newSchedFormatstart)->format('%Hh %Im');
            }

            

        }
        return $classScheds;

    }

    public static function todaySubjectAttendanceSHS($studentid,$sectionid,$blockid){

        $nowDate = Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD');
      
        $classScheds = SPP_Student::getTodayStudentClassScheduleSHS($studentid,$sectionid,$blockid);

        foreach($classScheds as $classSched){

                $subjectattendance =  DB::table('studentsubjectattendance')
                            ->where('studentsubjectattendance.section_id',$sectionid)
                            ->where('studentsubjectattendance.subject_id',$classSched->subjid)
                            ->where('student_id',$studentid)
                            ->where('date', $nowDate)
                            ->get();

            if(count($subjectattendance)>0){

                $classSched->subjectattendance = strtoupper($subjectattendance[0]->status);

            }
            else{

                $classSched->subjectattendance = "NOT CHECKED";
            }

            $now = Carbon::now('Asia/Manila')->toArray();
            $newSchedFormatstart =  Carbon::create( $nowDate.$classSched->stime, 'Asia/Manila');
            $newSchedFormatend =  Carbon::create( $nowDate.$classSched->etime, 'Asia/Manila');
            $currentTimte = Carbon::create($now['year'], $now['month'], $now['day'],$now['hour'],$now['minute'],$now['second']);
    
            if($currentTimte>=$newSchedFormatstart && $currentTimte<=$newSchedFormatend){
                $classSched->classstatus ="current class";
            }
            else if($currentTimte>$newSchedFormatstart){
                $classSched->classstatus ="Class Over";
            }
            else{
                $classSched->classstatus = $currentTimte->diff($newSchedFormatstart)->format('%Hh %Im');
            }

        }

        return $classScheds;

    }

    //current day School Attendance
    public static function todaySchoolAttendance($studentid){

        $nowDate = Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD');
        
        $studentAttendance = DB::table('studattendance')
                ->where('tdate',$nowDate)
                ->where('studattendance.studid','=',$studentid)
                ->get();

        if(count($studentAttendance) > 0){
            
            if($studentAttendance[0]->tapstate == null){

                if($studentAttendance[0]->present == 1){

                    $studentAttendance[0]->tapstate = 'IN';
                    
                }
                
                if($studentAttendance[0]->updateddatetime == null ){

                    $studentAttendance[0]->updateddatetime = $studentAttendance[0]->createddatetime;
                }

            }
        
        }

        return $studentAttendance;
    
 
    }



}
