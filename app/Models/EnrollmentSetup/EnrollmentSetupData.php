<?php

namespace App\Models\EnrollmentSetup;
use DB;

use Illuminate\Database\Eloquent\Model;

class EnrollmentSetupData extends Model
{

      public static function enrollment_setup($id = null, $syid = null, $acadprogid = null ){

            $enrollmentsetup = DB::table('early_enrollment_setup');    
            
            if($id != null){
                  $enrollmentsetup = $enrollmentsetup->where('id',$id);
            }

            if($syid != null){
                  $enrollmentsetup = $enrollmentsetup->where('syid',$syid);
            }
            
            $enrollmentsetup = $enrollmentsetup->join('sy',function($join){
                                                      $join->on('early_enrollment_setup.syid','=','sy.id');
                                                })
                                                ->join('semester',function($join){
                                                      $join->on('early_enrollment_setup.semid','=','semester.id');
                                                })
                                                ->join('academicprogram',function($join){
                                                      $join->on('early_enrollment_setup.acadprogid','=','academicprogram.id');
                                                })
                                                ->where('early_enrollment_setup.deleted',0)
                                                ->select(
                                                      'early_enrollment_setup.*',
                                                      'semester',
                                                      'sydesc',
                                                      'progname'
                                                )
                                                ->get();

            foreach( $enrollmentsetup as $item){
                  $item->enrollmentstart = \Carbon\Carbon::create($item->enrollmentstart)->isoFormat('MMM DD, YYYY');
                  $item->enrollmentend = \Carbon\Carbon::create($item->enrollmentend)->isoFormat('MMM DD, YYYY');

                  $item->enrollmentstart_format1 = \Carbon\Carbon::create($item->enrollmentstart)->isoFormat('YYYY-MM-DD');
                  $item->enrollmentend_format1 = \Carbon\Carbon::create($item->enrollmentend)->isoFormat('YYYY-MM-DD');
            }

            return $enrollmentsetup ;

      }

      public static function check_enrollment_setup($id = null , $acadprogid = null){

            $enrollmentsetup = DB::table('early_enrollment_setup')      
                                    ->where('early_enrollment_setup.deleted',0)
                                    ->join('sy',function($join){
                                          $join->on('early_enrollment_setup.syid','=','sy.id');
                                    })
                                    ->join('semester',function($join){
                                          $join->on('early_enrollment_setup.semid','=','semester.id');
                                    });

            if($id != null){
                  $enrollmentsetup = $enrollmentsetup->where('early_enrollment_setup.id',$id);
            }
            if($acadprogid != null){
                  $enrollmentsetup = $enrollmentsetup->where('early_enrollment_setup.acadprogid',$acadprogid);
            }
                  
                  $enrollmentsetup = $enrollmentsetup->where('early_enrollment_setup.isactive',1)
                                    ->get();

            if(count($enrollmentsetup) > 0){
                

                  $otherDate = \Carbon\Carbon::now()->subMinutes(10);
                  $nowDate = \Carbon\Carbon::now();
            
                  $result = $nowDate->gt($otherDate);

                  $todate = \Carbon\Carbon::now('Asia/Manila');
                  $enrollmentstart = \Carbon\Carbon::create($enrollmentsetup[0]->enrollmentstart);
                  $enrollmentend = \Carbon\Carbon::create($enrollmentsetup[0]->enrollmentend);
                 
                  if(!$todate->gt($enrollmentstart)){
                        return array((object)[
                              'status'=>0,
                              'data'=>$enrollmentsetup,
                              'message'=>'School Year ' . $enrollmentsetup[0]->sydesc . ( $enrollmentsetup[0]->type == 2 ? ' Early ' : ' Regular ' ) .' Pre-Enrollment / Pre-Registration will start on '. $enrollmentstart->isoFormat('MMMM DD, YYYY')
                        ]); 
                  }
                  else if($todate->gt($enrollmentend)){
                        return array((object)[
                              'status'=>0,
                              'data'=>$enrollmentsetup,
                              'message'=>'School Year ' .  $enrollmentsetup[0]->sydesc . ( $enrollmentsetup[0]->type == 2 ? ' Early ' : ' Regulary ' ). ' Pre-Enrollment / Pre-Registration ended last '. $enrollmentend->isoFormat('MMMM DD, YYYY')
                        ]); 
                  }
                  else{

                        if($enrollmentsetup[0]->type == 1){
                              return array((object)[
                                    'status'=>1,
                                    'data'=>$enrollmentsetup,
                                    'message'=>'School Year ' . $enrollmentsetup[0]->sydesc .' Regular Enrollment / Pre-Registration is now open.'
                              ]); 
                        }
                        else if($enrollmentsetup[0]->type == 2){
                              return array((object)[
                                    'status'=>1,
                                    'data'=>$enrollmentsetup,
                                    'message'=>'School Year ' . $enrollmentsetup[0]->sydesc .' Early Enrollment / Pre-Registration is now open.'
                              ]); 
                        }
                       
                  }
                  return collect( $todate->gt($enrollmentstart));
            }else{

                  $cadname = '';

                  if($acadprogid == 2){
                        $cadname = 'Pre-school';
                  }else if($acadprogid == 3){
                        $cadname = 'Grade School';
                  }else if($acadprogid == 4){
                        $cadname = 'Junior High School';
                  }else if($acadprogid == 5){
                        $cadname = 'Senior High School';
                  }
                  else if($acadprogid == 6){
                        $cadname = 'College';
                  }

                  return array((object)[
                        'status'=>0,
                        'data'=>[],
                        'message'=>$cadname . ' Pre-Enrollment / Pre-Registration is not yet available!'
                  ]);

            }
            
            return $enrollmentsetup ;

      }

     
      
}
