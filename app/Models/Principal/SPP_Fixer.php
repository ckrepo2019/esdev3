<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Principal\SPP_Subject;


class SPP_Fixer extends Model
{

    public static function fixpromstatus(
        $students = null
    ){

        $activeSY = DB::table('sy')->where('isactive',1)->first();

        foreach($students[0]->data as $item){

            $gradesum = DB::table('tempgradesum')
                            ->where('studid',$item->id)
                            ->where('syid',$activeSY->id)
                            ->get();

            $subjects = SPP_Subject::getSubject(null,null,null,$item->ensectid,null,null,null);


            if(count($gradesum) == $subjects[0]->count){

                $calculateFinal = 0;

                foreach($gradesum as $studgradesumitem){

                    if(
                        $studgradesumitem->q1 == null &&
                        $studgradesumitem->q2 == null &&
                        $studgradesumitem->q3 == null &&
                        $studgradesumitem->q4 == null
                    ){

                        $calculateFinal = 0;
                        break;

                    }
                    else{

                        $calculateFinal +=   ( $studgradesumitem->q1 +
                                                $studgradesumitem->q2 +
                                                $studgradesumitem->q3 +
                                                $studgradesumitem->q4 )/4;

                    }

                }

                if($calculateFinal!=0){

                    if($calculateFinal/$subjects[0]->count >= 75){

                        DB::table('enrolledstud')
                            ->join('sy',function($join){
                                $join->on('enrolledstud.syid','=','sy.id');
                                $join->where('isactive','1');
                            })
                            ->where('studid',$item->id)
                            ->update([
                                'promotionstatus'=>'1'
                            ]);

                    }
                    else{
                        
                    }
                }
            }
        }
    }

}
