<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;

class SPP_Attendance extends Model
{
    
    public static function schoolDays(
        $syid = null
    ){
        
        $schoolyear = DB::table('sy')->where('isactive','1')->get();

        $yearStart = Carbon::create($schoolyear[0]->sdate)->isoFormat('MMM DD, YYYY');
        $yearEnd = Carbon::create($schoolyear[0]->edate)->isoFormat('MMM DD, YYYY');

        $calendar = DB::table('schoolcal')
                    ->where('syid',$schoolyear[0]->id)
                    ->where('deleted','0')
                    ->select('datefrom','dateto','noclass','type')
                    ->get();

        $periods = CarbonPeriod::create($schoolyear[0]->sdate, $schoolyear[0]->edate)->toArray();

        $dayMonthWithDay = array();

        $firstCalendarLoop = true;

        $countWeekendSched = array();

        foreach($periods as $key=>$period){

            if($period->isWeekday()){

                $periodDate = new Carbon($period);

                $periods[$key] =  $periodDate->isoFormat('YYYY-MM-DD');

            }
            else{

                unset($periods[$key] );

            }

        }
      
        foreach($calendar as $key=>$item){
            
            $longEvents = CarbonPeriod::create($item->datefrom, $item->dateto)->toArray();

            foreach($longEvents as $longEvent){

                if($item->datefrom >= $schoolyear[0]->sdate && $item->dateto <= $schoolyear[0]->edate){

                    $dateTo = new Carbon($longEvent);

                    if($item->noclass == 1){

                        $data = array_search($dateTo->isoFormat('YYYY-MM-DD'),$periods,false);

                        if(array_search($dateTo->isoFormat('YYYY-MM-DD'),$periods)){
                            unset($periods[$data ] );
                        };

                    }
                    else{

                        $data = array_search($dateTo->isoFormat('YYYY-MM-DD'),$periods,false);

                        if(!array_search($dateTo->isoFormat('YYYY-MM-DD'),$periods)){

                            if($dateTo->isoFormat('dddd')!='Sunday' && $item->type != 11){
                                
                                array_push($periods,$dateTo->isoFormat('YYYY-MM-DD'));

                            }
                        }
                    }
                }
            }

        }

        foreach($periods as $item){
            
            $periodDate = new Carbon($item);

            array_push($dayMonthWithDay, (object)[
                'month'=>$periodDate->isoFormat('YYYY-MM'),
                'day'=>$periodDate->isoFormat('YYYY-MM-DD')
                ]
            );

        }

        $summary = array();

        foreach(collect($dayMonthWithDay)->unique('day')->groupBy('month') as $item){

            $count = count($item);
            $dateTo = new Carbon($item[0]->month);

            array_push($summary, (object)[
                'count'=> $count ,
                'month'=>$dateTo->isoFormat('MMMM'),
                'sorter' => $dateTo->isoFormat('YYYY-MM'),
                'days'=>$item
            ]);
            
        }

        return $summary;

    }


    public static function getStudentAttendance(
        $studid = null
    ){

        $studentAttendance = DB::table('studattendance')->where('studid',$studid)->get();

        // return   $studentAttendance;
        

        $attSum = self::schoolDays();
   

        // if(count($studentAttendance)>0 ){
           
            $studentAttendance = collect($studentAttendance);

            foreach($attSum as $item){

             

                $countp = 0;
                $counta = 0;
             
                foreach($item->days as $key=>$day){

                    date_default_timezone_set('Asia/Manila');
                    $today = date('Y-m-d H:i:s');

                    $dayDay = Carbon::create($day->day);

                        if( $dayDay->lessThanOrEqualTo($today)){

                         
                            if($studentAttendance->contains('tdate',$day->day)){

                                $dayData = $studentAttendance->where('tdate',$day->day);

                                foreach($dayData as $dayDataItem){

                                    if($dayDataItem->present == 1){
                                        
                                        $day->attendance = 1;
                                        $countp +=1;

                                    }

                                    if($dayDataItem->absent == 1){
                                        $counta +=1;
                                        $day->attendance = 2;
                                    }

                                    if($dayDataItem->cc == 1 || $dayDataItem->tardy == 1) {

                                        $day->attendance = 3;
                                        $countp +=1;

                                    }

                                    if($dayDataItem->intimeam == null && $dayDataItem->intimepm == null){
                                       
                                        $day->time = 1;

                                    }
                                    else{
                                        
                                        $day->time = $dayDataItem->intimeam;

                                    }
                                
                                }

                                
                            }
                            else{
                                
                                $counta +=1;
                                $day->attendance = 0;
                                $day->time = '00:00:00';

                            }

                        }
                        else{

                     

                            $dayData = $studentAttendance->where('tdate',$day->day);

                            if($studentAttendance->contains('tdate',$day->day)){
                            
                                $dayData = $studentAttendance->where('tdate',$day->day);

                                foreach($dayData as $dayDataItem){

                                    if($dayDataItem->present == 1){
                                        
                                        $day->attendance = 1;
                                        $countp +=1;

                                    }

                                    if($dayDataItem->absent == 1){
                                        $counta +=1;
                                        $day->attendance = 2;
                                    }

                                    if($dayDataItem->cc == 1 || $dayDataItem->tardy == 1) {

                                        $day->attendance = 3;
                                        $countp +=1;

                                    }

                                    if($dayDataItem->intimeam == null && $dayDataItem->intimepm == null){
                                       
                                        $day->time = 1;

                                    }
                                    else{
                                        $day->time = $dayDataItem->intimeam;
                                    }
                                }
                            }
                            else{

                                $day->attendance = 4;
                                $day->time = '00:00:00';

                            }
                        }
                }

                $item->countAbsent = $counta;
                $item->countPresent = $countp;

            }


       
        return  $attSum;

    }

   

}
      