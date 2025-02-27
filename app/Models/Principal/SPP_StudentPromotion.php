<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;


class SPP_StudentPromotion extends Model
{
    public static function getstudentPromotion(
        $student = null,
        $acadid = null,
        $promsum = false
    ){
        $activesy = DB::table('sy')->where('isactive',1)->first();

        $sections = DB::table('sections')
                    ->join('gradelevel',function($join){
                        $join->on('sections.levelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted',0);
                    })
                    ->where('sections.deleted','0')
                    ->where('gradelevel.acadprogid',$acadid)
                    ->select(
                        'sections.id',
                        'sections.blockid'
                        )
                    ->get();

        $gradesum =  DB::table('tempgradesum');

                        if(Session::has('schoolYear')){

                            $gradesum->join('sy',function($join){
                                $join->on('tempgradesum.syid','=','sy.id');
                            $join->where('sy.id',Session::get('schoolYear')->id);
                            });

                        }
                        else{

                            $gradesum->join('sy',function($join){
                                $join->on('tempgradesum.syid','=','sy.id');
                                $join->where('sy.isactive','1');
                            });

                        }

        if($acadid == 5){

            $gradesum->join('semester',function($join){
                $join->on('tempgradesum.semid','=','semester.id');
                $join->where('semester.isactive','1');
            });

        }
                        
                       


        $gradesum = $gradesum->get();

        $countIncomplete = 0;
        $countComplete = 0;
        $countPromoted = 0;
        $countRetained = 0;
        $countConditional = 0;
        $countUnPromoted = 0;
        $countpromotable = 0;
        $countconditionable = 0;
        $countRatainable = 0;

      
        $promotionsummary = array();

        foreach($sections as $item){

            $subjects = SPP_Subject::getSubject(null,null,null,$item->id, $item->blockid,null,null,null,'sf9');

            if($subjects[0]->count>0){

                $subjectarray = array();

                foreach($subjects[0]->data as $subjitem){

                    array_push($subjectarray,$subjitem->id);

                }

                foreach(collect($student[0]->data)->where('ensectid',$item->id) as $studitem){

                 

                    $studgradesum = collect($gradesum)
                                    ->where('studid',$studitem->id)
                                    ->wherein('subjid', $subjectarray);

                    $countPassSubj = 0;

                    
                    


                    if(count($studgradesum) != $subjects[0]->count){

                        $countIncomplete += 1;
                        $complete = true;

                        foreach($studgradesum as $studgradesumitem){

                            if($studitem->acadprogid != 5){

                                if(
                                    $studgradesumitem->q1 == null &&
                                    $studgradesumitem->q2 == null &&
                                    $studgradesumitem->q3 == null &&
                                    $studgradesumitem->q4 == null
                                ){
        
                                    $complete = false;
                                    break;

                                }
                                else{

                                    $calculateFinal =   ( $studgradesumitem->q1 +
                                                            $studgradesumitem->q2 +
                                                            $studgradesumitem->q3 +
                                                            $studgradesumitem->q4 )/4;

                                    if( $calculateFinal >= 75){

                                        $countPassSubj += 1;

                                    }

                                }

                            }
                            else{

                        

                                if(
                                    $studgradesumitem->q1 == null &&
                                    $studgradesumitem->q2 == null
                                    
                                ){
        
                                    $complete = false;
                                    break;

                                }
                                else{

                                    $calculateFinal =   ( $studgradesumitem->q1 +
                                                            $studgradesumitem->q2 )
                                                           / 2;

                                    if( $calculateFinal >= 75){

                                        $countPassSubj += 1;

                                    }

                                }




                            }

                        }

                    }
                    else{

             
                        $complete = true;
                        
                        if($studitem->acadprogid != 5){

                            foreach($studgradesum as $studgradesumitem){

                                if(
                                    $studgradesumitem->q1 == null &&
                                    $studgradesumitem->q2 == null &&
                                    $studgradesumitem->q3 == null &&
                                    $studgradesumitem->q4 == null
                                ){
        
                                    $complete = false;
                                    break;

                                }
                                else{

                                    $calculateFinal =   ( $studgradesumitem->q1 +
                                                            $studgradesumitem->q2 +
                                                            $studgradesumitem->q3 +
                                                            $studgradesumitem->q4 )/4;

                                    if( $calculateFinal >= 75){

                                        $countPassSubj += 1;

                                    }

                                }

                            }

                        }
                        else{

                         

                            foreach($studgradesum as $studgradesumitem){

                                if(
                                    $studgradesumitem->q1 == null &&
                                    $studgradesumitem->q2 == null
                                    
                                ){
        
                                    $complete = false;
                                    break;

                                }
                                else{

                                    $calculateFinal =   ( $studgradesumitem->q1 +
                                                            $studgradesumitem->q2 )/2;


                                    if( $calculateFinal >= 75){

                                        $countPassSubj += 1;

                                    }

                                }

                            }

                        }


         

                        if( $complete){

                            $countComplete += 1;

                        }
                        else{

                            $countIncomplete += 1;

                        }
                        
                    }

      

                    if( $studitem->promotionstatus == 3){

                        $countRetained += 1;
                        $promstatus = 3;

                    }
                    else if( $studitem->promotionstatus == 1){

                        $countPromoted += 1;
                        $promstatus = 1;

                    }
                    else if($studitem->promotionstatus == 2  ){

                        $countConditional += 1;
                        $promstatus = 2;

                    }
                    else{
                        
                        $countUnPromoted += 1;
                        $promstatus = 0;

                        if( ($subjects[0]->count - $countPassSubj) >= 3 && $subjects[0]->count > 3){

                            $countRatainable += 1;
                            $promstatus = 3;
                            
                        }

                        else if( ($subjects[0]->count - $countPassSubj) == 0 && $subjects[0]->count > 3){

                            $countpromotable += 1;
                            $promstatus = 1;

                        }
                        else if( ( ($subjects[0]->count - $countPassSubj) == 0  || $countPassSubj == 3 ) &&  $subjects[0]->count <= 3 ){

                            $countpromotable += 1;
                            $promstatus = 1;

                        }
                        else if(  $countPassSubj == 0 && $subjects[0]->count <= 3){

                            $countRatainable += 1;
                            $promstatus = 3;

                        }
                        else if(  $countPassSubj != 0 &&  ( $subjects[0]->count <= 3) ){

                            $countconditionable += 1;
                            $promstatus = 2;

                        }
                        else{

                            if( $subjects[0]->count > 3){

                                $countconditionable += 1;
                                $promstatus = 2;
                            }

                        }

                    }

                    if(!$promsum){

                        array_push($promotionsummary, (object)[
                            'studid'=>$studitem->sid,
                            'name'=>$studitem->lastname.' , '.$studitem->firstname,
                            'totalSubjects'=>$subjects[0]->count,
                            'passedSubjects'=>$countPassSubj,
                            'failedSubjects'=>$subjects[0]->count -$countPassSubj,
                            'studsatus'=>$promstatus,
                            'promstatus'=> $studitem->promotionstatus,
                            'id'=>$studitem->id,
                            'levelid'=>$studitem->levelid,
                            'enlevelid'=>$studitem->enlevelid,
                        
                        ]);

                    }
                   

                }

            }
            else{

                foreach(collect($student[0]->data)->where('ensectid',$item->id) as $studitem){

                    if( $studitem->promotionstatus == 3){

                        $countRetained += 1;
                       

                    }
                    else if( $studitem->promotionstatus == 1){

                        $countPromoted += 1;
         

                    }
                    else if($studitem->promotionstatus == 2  ){

                        $countConditional += 1;
                     

                    }

                    $promstatus = 3;
                    $countRatainable += 1;
                    $countUnPromoted += 1;
                    $countComplete += 1;

                    if(!$promsum){
                 
                        array_push($promotionsummary, (object)[
                            'studid'=>$studitem->sid,
                            'name'=>$studitem->lastname.' , '.$studitem->firstname,
                            'totalSubjects'=>0,
                            'passedSubjects'=>0,
                            'failedSubjects'=>0,
                            'studsatus'=>$promstatus,
                            'promstatus'=> $studitem->promotionstatus,
                            'id'=>$studitem->id,
                            'levelid'=>$studitem->levelid,
                            'enlevelid'=>$studitem->enlevelid,
                        
                        ]);
                    }
                }
                
            }

        }
        

        if($promsum){
            

            array_push($promotionsummary, (object)[
                'incomplete'=>$countIncomplete,
                'complete'=>$countComplete,
                'retained'=> $countRetained,
                'conditional'=>$countConditional,
                'promoted'=>$countPromoted,
                'promotable'=>$countpromotable,
                'ratainable'=> $countRatainable,
                'conditionable'=>$countconditionable,
                'unpromoted'=>$countUnPromoted
                
            ]);

        }

        return $promotionsummary;

    }

    public static function promoteStudents(
        $student = null,
        $acadid = null
    ){

        $studentpromotions  = self::getstudentPromotion( $student, $acadid);

        $sem = DB::table('semester')->where('isactive','1')->first();

        foreach($studentpromotions as $item){

            $levelinfo = DB::table('gradelevel')->where('id',$item->levelid)->first();

            $levelinfo = DB::table('gradelevel')->where('sortid',$levelinfo->sortid+1)->first();

            if($acadid == 5){

                if($sem->id == 1){

                    DB::table('studinfo')
                        ->where('id',$item->id)
                        ->where('studstatus','!=','0')
                        ->update([
                            'studstatus'=>'0',
                            'sectionid'=>null,
                            'blockid'=>null,
                            'sectionname'=>null,
                            'semid'=>null
                        ]);
                }

                else if($sem->id == 2){

                    if($item->studsatus == 1 || $item->studsatus == 2){

                        DB::table('studinfo')
                            ->where('id',$item->id)
                            ->where('studstatus','!=','0')
                            ->update([
                                'levelid'=>$levelinfo->id,
                                'studstatus'=>'0',
                                'sectionid'=>null,
                                'sectionname'=>null,
                                'blockid'=>null,
                                'semid'=>null
                            ]);

                    }
                    else{

                        
                        DB::table('studinfo')
                            ->where('id',$item->id)
                            ->where('studstatus','!=','0')
                            ->update([
                                'studstatus'=>'0',
                                'sectionid'=>null,
                                'sectionname'=>null,
                                // 'strandid'=>null,
                                'blockid'=>null,
                                'semid'=>null
                            ]);

                    }



                }

                DB::table('sh_enrolledstud')
                    ->join('sy',function($join){
                        $join->on('sh_enrolledstud.syid','=','sy.id');
                        $join->where('isactive','1');
                    })
                    ->where('studid',$item->id)
                    ->update([
                        'promotionstatus'=>$item->studsatus
                    ]);


            }
            else{

                if($item->studsatus == 1 || $item->studsatus == 2){

                    DB::table('studinfo')
                        ->where('id',$item->id)
                        ->where('studstatus','!=','0')
                        ->update([
                            'levelid'=>$levelinfo->id,
                            'studstatus'=>'0',
                            'sectionid'=>null,
                            // 'strandid'=>null,
                            'sectionname'=>null,
                            'blockid'=>null,
                            'semid'=>null
                        ]);

                }
                else{

                    DB::table('studinfo')
                        ->where('id',$item->id)
                        ->where('studstatus','!=','0')
                        ->update([
                            'studstatus'=>'0',
                            'sectionid'=>null,
                            'sectionname'=>null,
                            // 'strandid'=>null,
                            'blockid'=>null,
                            'semid'=>null
                        ]);

                }

                DB::table('enrolledstud')
                    ->join('sy',function($join){
                        $join->on('enrolledstud.syid','=','sy.id');
                        $join->where('isactive','1');
                    })
                    ->where('studid',$item->id)
                    ->update([
                        'promotionstatus'=>$item->studsatus
                    ]);

            }



        }

        return back();



    }
}
