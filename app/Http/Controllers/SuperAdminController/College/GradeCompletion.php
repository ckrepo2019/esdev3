<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use Session;

class GradeCompletion extends \App\Http\Controllers\Controller
{

      public static function getsubjects(Request $request){

            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $teacherid = $request->get('teacherid');
            $subject = array();

            if($studid != null && $studid != ""){

                  $subject = DB::table('college_studsched')
                                    ->join('college_classsched',function($join) use($syid,$semid,$teacherid){
                                          $join->on('college_studsched.schedid','=','college_classsched.id');
                                          $join->where('college_classsched.deleted',0);
                                          $join->where('college_classsched.syid',$syid);
                                          $join->where('college_classsched.semesterID',$semid);
                                          
                                          if($teacherid != null && $teacherid != ""){
                                              $join->where('college_classsched.teacherid',$teacherid);
                                          }
                                    })
                                    ->join('college_prospectus',function($join){
                                          $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                          $join->where('college_prospectus.deleted',0);
                                    })
                                    ->join('college_enrolledstud',function($join) use($syid,$semid){
                                          $join->on('college_studsched.studid','=','college_enrolledstud.studid');
                                          $join->where('college_enrolledstud.deleted',0);
                                          $join->whereIn('college_enrolledstud.studstatus',[1,2,4]);
                                          $join->where('college_enrolledstud.syid',$syid);
                                          $join->where('college_enrolledstud.semid',$semid);
                                    })
                                    ->where('college_studsched.schedstatus','!=','DROPPED')
                                    ->where('college_studsched.studid',$studid)
                                    ->where('college_studsched.deleted',0)
                                    ->select(
                                          'college_classsched.sectionid',
                                          'college_studsched.studid',
                                          'college_prospectus.id',
                                          'subjDesc',
                                          'subjCode',
                                          'subjDesc'
                                    )
                                    ->get();
                                    


            }else if($teacherid != null && $teacherid != ""){

                  $subject = DB::table('college_classsched')
                                    ->join('college_studsched',function($join){
                                          $join->on('college_classsched.id','=','college_studsched.schedid');
                                          $join->where('college_studsched.deleted',0);
                                          $join->where('college_studsched.schedstatus','!=','DROPPED');
                                    })
                                    ->join('college_prospectus',function($join){
                                          $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                          $join->where('college_prospectus.deleted',0);
                                    })
                                    ->join('college_sections',function($join){
                                          $join->on('college_classsched.sectionid','=','college_sections.id');
                                          $join->where('college_sections.deleted',0);
                                    })
                                    ->join('college_enrolledstud',function($join) use($syid,$semid){
                                          $join->on('college_studsched.studid','=','college_enrolledstud.studid');
                                          $join->where('college_enrolledstud.deleted',0);
                                          $join->whereIn('college_enrolledstud.studstatus',[1,2,4]);
                                          $join->where('college_enrolledstud.syid',$syid);
                                          $join->where('college_enrolledstud.semid',$semid);
                                    })
                                    ->where('college_classsched.deleted',0)
                                    ->where('college_classsched.teacherid',$teacherid )
                                    ->where('college_classsched.syid',$syid)
                                    ->where('college_classsched.semesterID',$semid)
                                    ->select(
                                          'college_classsched.sectionid',
                                          'college_studsched.studid',
                                          'college_prospectus.id',
                                          'subjDesc',
                                          'subjCode',
                                          'subjDesc'
                                    )
                                    ->get();

            }


            $grades = DB::table('college_studentprospectus')
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->where('deleted',0)
                              ->whereIn('prospectusID',collect($subject)->pluck('id'))
                              ->whereIn('studid',collect($subject)->pluck('studid'))
                              ->get();

            $dates = DB::table('college_studentprosstat')
                              ->whereIn('headerid',collect($grades)->pluck('id'))
                              ->get();

            foreach($grades as $item){

                  $item->prelemdate = self::get_gradedate($item->prelemstatus,collect($dates)->where('term','prelem')->where('headerid',$item->id)->values());
                  $item->midtermdate = self::get_gradedate($item->midtermstatus,collect($dates)->where('term','midterm')->where('headerid',$item->id)->values()); 
                  $item->prefidate =  self::get_gradedate($item->prefistatus,collect($dates)->where('term','prefi')->where('headerid',$item->id)->values());
                  $item->finaldate =  self::get_gradedate($item->finalstatus,collect($dates)->where('term','final')->where('headerid',$item->id)->values());

                  $item->prelemclass = self::get_status($item->prelemstatus);
                  $item->midtermclass = self::get_status($item->midtermstatus); 
                  $item->preficlass =  self::get_status($item->prefistatus);
                  $item->finalclass =  self::get_status($item->finalstatus);
            }


            foreach($dates as $item){
                  $item->subjstatdatetime = $item->subjstatdatetime != null ? \Carbon\Carbon::create($item->subjstatdatetime)->isoFormat('MMM DD, YYYY hh:mm A') : '';
                  $item->appstatdatetime = $item->appstatdatetime != null ? \Carbon\Carbon::create($item->appstatdatetime)->isoFormat('MMM DD, YYYY hh:mm A') : '';
                  $item->poststatdatetime = $item->poststatdatetime != null ? \Carbon\Carbon::create($item->poststatdatetime)->isoFormat('MMM DD, YYYY hh:mm A') : '';
                  $item->pendstatdatetime = $item->pendstatdatetime != null ? \Carbon\Carbon::create($item->pendstatdatetime)->isoFormat('MMM DD, YYYY hh:mm A') : '';
            }
           

            // return $subject;
            $loadsubjects = json_decode(collect($subject)->unique('subjCode')->values());

            foreach($loadsubjects as $item){
                  $item->text = $item->subjCode .' - '.$item->subjDesc;
                  $item->id = $item->subjCode;
            }

           return array((object)[
                              'subjects'=>$subject,
                              'grades'=>$grades,
                              'subjectload'=>$loadsubjects,
                              'dates'=>$dates
                        ]);

      }

      public static function get_status($status = null){
            $td_class = 'input_grades';
            if($status == 1){
                  $td_class = 'bg-success';
            }else if($status == 7){
                  $td_class = 'bg-primary';
            }else if($status == 9){
                  $td_class = 'bg-danger';
            }else if($status == 8){
                  $td_class = 'bg-warning input_grades';
            }else if($status == 3){
                  $td_class = 'bg-warning input_grades';
            }else if($status == 4){
                  $td_class = 'bg-info';
            }else if($status == 2){
                  $td_class = 'bg-secondary';
            }else if($status == 10){
                  $td_class = 'bg-danger';
            }
            return $td_class;
      }


      
      public static function get_gradedate($status = null,$dates = array()){

            if(count($dates) > 1){
                  $dates_filter = collect($dates)->where('pendstatdatetime',0)->values();
                  if(count($dates_filter) == 0){
                        $dates =  array(collect($dates)->last());
                  }else{
                        $dates =   $dates_filter;
                  }
            }

            if(count($dates) > 0){
                  $td_date = 'input_grades';
                  if($status == 1){
                        $td_date = \Carbon\Carbon::create($dates[0]->subjstatdatetime)->isoFormat('MMM DD, YYYY hh:mm A');
                  }else if($status == 7){
                        $td_date =  \Carbon\Carbon::create($dates[0]->appstatdatetime)->isoFormat('MMM DD, YYYY hh:mm A');
                  }else if($status == 9){
                        $td_date = '';
                  }else if($status == 10){
                        $td_date =  \Carbon\Carbon::create($dates[0]->unpoststatdatetime)->isoFormat('MMM DD, YYYY hh:mm A');
                  }else if($status == 8){
                        $td_date = '';
                  }else if($status == 3){
                        $td_date =  \Carbon\Carbon::create($dates[0]->pendstatdatetime)->isoFormat('MMM DD, YYYY hh:mm A');
                  }else if($status == 4){
                        $td_date = \Carbon\Carbon::create($dates[0]->poststatdatetime)->isoFormat('MMM DD, YYYY hh:mm A');;
                  }else if($status == 2){
                        $td_date = \Carbon\Carbon::create($dates[0]->appstatdatetime)->isoFormat('MMM DD, YYYY hh:mm A');;
                  }
                  return $td_date;
            }else{
                  return '';
            }
          
      }




      public static function getstudents(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $teacherid = $request->get('teacherid');

            if($teacherid == null || $teacherid == ""){

                  $students = DB::table('college_enrolledstud')
                                    ->where('college_enrolledstud.syid',$syid)
                                    ->where('college_enrolledstud.semid',$semid)
                                    ->where('college_enrolledstud.deleted',0)
                                    ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                                    ->join('studinfo',function($join){
                                          $join->on('college_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->select(
                                          'college_enrolledstud.courseid',
                                          'sid',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studname"),
                                          'college_enrolledstud.studid',
                                          'studinfo.id',
                                          DB::raw("CONCAT(studinfo.sid,' - ',studinfo.lastname,' ',studinfo.firstname) as text")
                                    )
                                    ->orderBy('studname')
                                    ->distinct('studid')
                                    ->get();


            }else{


                  $students = DB::table('college_classsched')
                                    ->where('college_classsched.syid',$syid)
                                    ->where('college_classsched.semesterID',$semid)
                                    ->where('college_classsched.deleted',0)
                                    ->where('college_classsched.teacherid',$teacherid)
                                    ->join('college_studsched',function($join){
                                          $join->on('college_classsched.id','=','college_studsched.schedid');
                                          $join->where('college_studsched.deleted',0);
                                          $join->where('college_studsched.schedstatus','!=','DROPPED');
                                    })
                                    ->join('college_enrolledstud',function($join) use($syid,$semid){
                                          $join->on('college_studsched.studid','=','college_enrolledstud.studid');
                                          $join->where('college_enrolledstud.deleted',0);
                                          $join->whereIn('college_enrolledstud.studstatus',[1,2,4]);
                                          $join->where('college_enrolledstud.syid',$syid);
                                          $join->where('college_enrolledstud.semid',$semid);
                                    })
                                    ->join('studinfo',function($join){
                                          $join->on('college_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->select(
                                          'college_enrolledstud.courseid',
                                          'sid',
                                          DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studname"),
                                          'college_enrolledstud.studid',
                                          'studinfo.id',
                                          DB::raw("CONCAT(studinfo.sid,' - ',studinfo.lastname,' ',studinfo.firstname) as text")
                                    )
                                    ->orderBy('studname')
                                    ->distinct('studid')
                                    ->get();




            }

            

            return $students;
      }


      public static function getteachers(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $teachers = DB::table('college_classsched')
                              ->join('teacher',function($join){
                                    $join->on('college_classsched.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                              })
                              ->where('college_classsched.syid',$syid)
                              ->where('college_classsched.semesterID',$semid)
                              ->where('college_classsched.deleted',0)
                              ->select(
                                    'teacher.id',
                                    'teacher.userid',
                                    'teacher.lastname',
                                    'teacher.firstname',
                                    'teacher.middlename',
                                    'teacher.tid',
                                    'acadtitle',
                                    'suffix',
                                    'title'
                              )
                              ->distinct('teacherid')
                              ->get();


            foreach($teachers as $item){

                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                              if(strlen($middlename_item) > 0){
                                    $temp_middle .= $middlename_item[0].'.';
                              } 
                        }
                  }

                  $temp_acadtitle = '';
                  if($item->acadtitle != null){
                        $temp_acadtitle = ', '.$item->acadtitle;
                  }

                  $temp_title = '';
                  if($item->title != null){
                        $temp_title = $item->title.' ';
                  }

                  $item->fullname = $item->tid.' - '.$temp_title.' '.$item->firstname.' '.$temp_middle.' '.$item->lastname.' '.$item->suffix.$temp_acadtitle;
                  $item->text = $item->fullname;
            }


            return $teachers;

      }

     
}
