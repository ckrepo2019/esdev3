<?php

namespace App\Models\FinalGrading;

use Illuminate\Database\Eloquent\Model;
use DB;

class FinalGradingData extends Model
{

    

    public static function teacher_grading_final_getgrades(
        $sectionid = null,
        $gradelevel = null,
        $subjectid = null,
        $quarter = null,
        $acadprogid = null
    ){
        
        $activeSy = DB::table('sy')->where('isactive',1)->first();

        $students = DB::table('studinfo')   
                            ->where('studinfo.studstatus',1)
                            ->join('enrolledstud',function($join){
                                $join->on('studinfo.id','=','enrolledstud.studid');
                                $join->where('enrolledstud.deleted',0);
                            })
                            ->where('studinfo.sectionid',$sectionid)
                            ->where('studinfo.deleted',0)
                            ->whereIn('studinfo.studstatus',[1,2,3])
                            ->orderBy('gender','desc')
                            ->orderBy('lastname')
                            ->select('firstname','lastname','studinfo.id','gender','studinfo.levelid')
                            ->get();
    
        $nogscount = 0;

        $gradelevel = null;

        foreach($students as $item){

                if($gradelevel == null){

                    $gradelevel = $item->levelid;
                }
                if($acadprogid == 3){
                    $gsdget = DB::table('grading_system_gsgrades')
                            ->where('studid',$item->id)
                            ->where('syid',$activeSy->id)
                            ->where('subjid',$subjectid)
                            ->where('grading_system_gsgrades.deleted',0)
                            ->select('gsdid','id');
                }
                if($acadprogid == 4){
                    $gsdget = DB::table('grading_system_grades_hs')
                            ->where('studid',$item->id)
                            ->where('syid',$activeSy->id)
                            ->where('subjid',$subjectid)
                            ->where('grading_system_grades_hs.deleted',0)
                            ->select('gsdid','id');
                }
                if($acadprogid == 5){
                    $gsdget = DB::table('grading_system_grades_sh')
                            ->where('studid',$item->id)
                            ->where('syid',$activeSy->id)
                            ->where('subjid',$subjectid)
                            ->where('grading_system_grades_sh.deleted',0)
                            ->select('gsdid','id');
                }
                
                for($x = 1; $x <= 10; $x++){

                    $gsdget =  $gsdget->addSelect('g'.$x.'q'.$quarter);

                }

               

                $gsdget =  $gsdget->addSelect('psq'.$quarter);
                $gsdget =  $gsdget->addSelect('wsq'.$quarter);
                $gsdget =  $gsdget->addSelect('q'.$quarter.'total');
                $gsdget =  $gsdget->addSelect('igq'.$quarter);
                $gsdget =  $gsdget->addSelect('qgq'.$quarter);

                $gsdget =  $gsdget->get();

                if(count($gsdget) == 0){

                    $nogscount += 1;
                    $item->nogs = 0;
                    $item->gsdget = [];

                }
                else{
                    $item->nogs = 1;
                    $item->gsdget = $gsdget;
                }

        }

        return   $students;
    }

    public static function subject_grading_system($subject = null, $acadprogid = null){

        $grading_system = DB::table('subjects')
                                ->where('subjects.acadprogid',$acadprogid)
                                ->join('grading_system_subjassignment',function($join){
                                      $join->on('subjects.id','=','grading_system_subjassignment.subjid');
                                      $join->where('grading_system_subjassignment.deleted',0);
                                })
                                ->join('grading_system',function($join) use($acadprogid){
                                      $join->on('grading_system_subjassignment.gsid','=','grading_system.id');
                                      $join->where('grading_system.deleted',0);
                                      $join->where('grading_system.acadprogid',$acadprogid);
                                })
                                ->where('inSF9',1)
                                ->where('subjects.id',$subject)
                                ->select('grading_system.*')
                                ->get();

        if(count($grading_system ) == 1 && $grading_system[0]->id != null){

              $gsdetail =  DB::table('grading_system_detail')
                                ->where('headerid',$grading_system[0]->id)
                                ->where('deleted',0)
                                ->count();

              if($gsdetail == 0){

                    $data = array((object)[
                          'status'=>0,
                          'data'=>"This grading system does not contain any detail. \n Please add details to continue.",
                    ]);

                    return $data;

              }

        }
        else if(count($grading_system ) != 0 && $grading_system[0]->id == null){

              $data = array((object)[
                    'status'=>0,
                    'data'=>"This subject is not yet assigned to a grading system",
              ]);

              return $data;

        }
        else if(count($grading_system) > 1){

              $data =  array((object)[
                    'status'=>0,
                    'data'=>"Mutiple grading system is active."
              ]);

              return $data;

        }
        else if(count($grading_system) == 0){
              $data =  array((object)[
                    'status'=>0,
                    'data'=>"No available grading system for high school."
              ]);
              return $data;
        }
        
        return    $data =  array((object)[
                          'status'=>1,
                          'data'=> $grading_system
                    ]);
                    
    }


      public static function get_grades_status(
            $syid = null,
            $semid = null,
            $sectionid = null,
            $subjid = null,
            $quarter = null
      ){
                  $grade_status = DB::table('grades')
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where('sectionid',$sectionid)
                        ->where('subjid',$subjid)
                        ->where('quarter',$quarter)
                        ->where('deleted',0)
                        ->get();

                  return $grade_status;

      }

      public static function get_grades_detail(
                  $syid = null,
                  $semid = null,
                  $sectionid = null,
                  $subjid = null,
                  $quarter = null,
                  $acadprogid = null
            ){

                  if($acadprogid == 5){

                        $grades = DB::table('sh_enrolledstud')
                              ->leftJoin('gradesdetail',function($join){
                                    $join->on('sh_enrolledstud.studid','=','gradesdetail.studid');
                              })
                              ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('grades',function($join) use($syid,$semid,$sectionid,$subjid,$quarter){
                                    $join->on('gradesdetail.headerid','=','grades.id');
                                    $join->where('grades.deleted',0);
                                    $join->where('grades.syid',$syid);
                                    $join->where('grades.semid',$semid);
                                    $join->where('grades.sectionid',$sectionid);
                                    $join->where('grades.subjid',$subjid);
                                    $join->where('grades.quarter',$quarter);
                              })
                              ->where('sh_enrolledstud.semid',$semid)
                              ->where('sh_enrolledstud.syid',$syid)
                              ->where('sh_enrolledstud.sectionid',$sectionid)
                              ->where('sh_enrolledstud.deleted',0)
                              ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                              ->select('gradesdetail.*','studinfo.lastname','studinfo.firstname','gender')
                              ->orderby('gender','desc')
                              ->orderby('lastname')
                              ->get();

                  }
                  else{

                        $grades = DB::table('enrolledstud')
                                    ->join('studinfo',function($join){
                                          $join->on('enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->leftJoin('gradesdetail',function($join){
                                          $join->on('enrolledstud.studid','=','gradesdetail.studid');
                                    })
                                    ->join('grades',function($join) use($syid,$semid,$sectionid,$subjid,$quarter){
                                          $join->on('gradesdetail.headerid','=','grades.id');
                                          $join->where('grades.deleted',0);
                                          $join->where('grades.syid',$syid);
                                          $join->where('grades.semid',$semid);
                                          $join->where('grades.sectionid',$sectionid);
                                          $join->where('grades.subjid',$subjid);
                                          $join->where('grades.quarter',$quarter);
                                    })
                                    ->where('enrolledstud.syid',$syid)
                                    ->where('enrolledstud.sectionid',$sectionid)
                                    ->where('enrolledstud.deleted',0)
                                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                                    ->select('gradesdetail.*','studinfo.lastname','studinfo.firstname','gender')
                                    ->orderby('gender','desc')
                                    ->orderby('lastname')
                                    ->get();

                  }

                  if(count($grades) == 0){

                        if($acadprogid == 5){
                              $grades = DB::table('sh_enrolledstud')
                                    ->join('studinfo',function($join){
                                          $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->select('studinfo.lastname','studinfo.firstname','gender')
                                    ->where('sh_enrolledstud.semid',$semid)
                                    ->where('sh_enrolledstud.syid',$syid)
                                    ->where('sh_enrolledstud.sectionid',$sectionid)
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                    ->select('studinfo.lastname','studinfo.firstname','gender','sh_enrolledstud.id as studid')
                                    ->orderby('gender','desc')
                                    ->orderby('lastname')
                                    ->get();
                        }
                        else{
                              $grades = DB::table('enrolledstud')
                                    ->join('studinfo',function($join){
                                          $join->on('enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->select('studinfo.lastname','studinfo.firstname','gender')
                                    ->where('enrolledstud.syid',$syid)
                                    ->where('enrolledstud.sectionid',$sectionid)
                                    ->where('enrolledstud.deleted',0)
                                    ->whereIn('enrolledstud.studstatus',[1,2,4])
                                    ->select('studinfo.lastname','studinfo.firstname','gender','enrolledstud.id as studid')
                                    ->orderby('gender','desc')
                                    ->orderby('lastname')
                                    ->get();
                        }

                        foreach($grades as $item){
                              $item->qg = null;
                              $item->id = null;
                        }
                  }

                  return $grades;
                  
            }
}
