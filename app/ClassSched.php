<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;

class ClassSched extends Model
{


    public static function selectassignsubjects(){

        return self::loadSchedule()->select(
                'subjects.subjdesc',
                'subjects.id as subjid',
                'subjects.subjcode',
                'assignsubj.id as id'
                )
            ->where('enrolledstud.deleted','0')
            ->distinct();

    }

    public static function loadSchedule(){

        return  DB::table('enrolledstud')
                  ->leftJoin('classsched',function($join){
                      $join->on('enrolledstud.sectionid','=','classsched.sectionid')
                      ->where('classsched.deleted','0');
                  })
                  ->leftJoin('classscheddetail',function($join){
                      $join->on('classsched.id','=','classscheddetail.headerid');
                      $join->where('classscheddetail.deleted','0');
                  })
                  ->leftJoin('rooms',function($join){
                      $join->on('classscheddetail.roomid','=','rooms.id');
                      $join->where('rooms.deleted','0');
                  })
                  ->join('days','classscheddetail.days','=','days.id')
                  ->leftJoin('subjects',function($join){
                      $join->on('classsched.subjid','=','subjects.id');
                      $join->where('subjects.deleted','0');
                      $join->where('subjects.isactive','1');
                      $join->select('subjects.id as subjid');
                  })
                  ->leftJoin('assignsubj',function($join){
                      $join->on('enrolledstud.sectionid','=','assignsubj.sectionid');
                      $join->where('assignsubj.deleted','0');
                  });
               
    }

    public static function selectClassSched(){

        return self::loadSchedule()->select(
                                'classsched.sectionid as sectionid',
                                'assignsubj.id as id',
                                'rooms.roomname',
                                'classscheddetail.stime',
                                'classscheddetail.etime',
                                'days.description',
                                'subjects.subjdesc',
                                'subjects.id as subjid',
                                'subjects.subjcode'
                                )
                            ->orderBy('classscheddetail.stime')
                            ->where('enrolledstud.deleted','0')
                            ->distinct();
    }

    public static function insertTeacherInfo($schedinfos){

         foreach($schedinfos as $item){
             
            $subjdetail = DB::table('assignsubjdetail')
                        ->leftJoin('teacher',function($join){
                            $join->on('assignsubjdetail.teacherid','=','teacher.id')
                                ->where('teacher.deleted','0')
                                ->where('teacher.isactive','1');
                        })
                        ->where('assignsubjdetail.headerid',$item->id)
                        ->where('assignsubjdetail.subjid',$item->subjid)
                        ->where('assignsubjdetail.deleted','0')
                        ->select('teacher.firstname','teacher.middlename','teacher.lastname')
                        ->get();

            if(count($subjdetail)>0){

                $item->teacherid = $subjdetail[0];

            }
            
                

       
        }

        return $schedinfos;

    }

    //student schedule within the school year
    public static function studentSchedule($studentId){


        // return self::selectClassSched()
        //             ->join('sy',function($join){
        //                 $join->on('sy.id','=','enrolledstud.syid');
        //                 $join->where('sy.isactive','1');
        //             })
        //             ->where('studid',$studentId)->get();

        return  self::insertTeacherInfo(
                    self::selectClassSched()
                            ->join('sy',function($join){
                                $join->on('sy.id','=','enrolledstud.syid');
                                $join->where('sy.isactive','1');
                            })
                            ->where('studid',$studentId)->get()
                        );

    }

    //classSchdule within the school year
    public static function classSchedule($sectoinId){

        return self::insertTeacherInfo(
            self::selectClassSched()
                        ->join('sy',function($join){
                            $join->on('sy.id','=','enrolledstud.syid');
                            $join->where('sy.isactive','1');
                        })
                        ->where('enrolledstud.sectionid',$sectoinId)->get());

    }

    public static function dayswithbgcolors(){

        $days  = DB::table('days')->select('description')->get();
        $bg_colors = array("bg-primary","bg-secondary","bg-success","bg-danger","bg-warning","bg-info","bg-dark");
        
        foreach($days as $key=>$day){
            $day->color = $bg_colors[$key];
        }

        return $days;
    }


    //load todays schedule within the school year
    public static function todaySchedule($studentId){

        return self::loadSchedule($studentId)
                            ->join('sy',function($join){
                                $join->on('sy.id','=','enrolledstud.syid');
                                $join->where('sy.isactive','1');
                            })
                            ->where('classscheddetail.days', Carbon::now()->isoFormat('d'))
                            ->where('studid',$studentId)
                            ->get();
    }

    //get assignsubjects within the school year by section
    public static function getassignsubjects($sectoinId){

        return self::insertTeacherInfo(
                    self::selectassignsubjects()
                    ->join('sy',function($join){
                        $join->on('sy.id','=','enrolledstud.syid');
                        $join->where('sy.isactive','1');
                    })
                    ->where('enrolledstud.sectionid',$sectoinId)
                    ->get());

    }

    public static function getassignsubjectsbystudent($studentId){

        return self::selectassignsubjects()
                        ->join('sy',function($join){
                            $join->on('sy.id','=','enrolledstud.syid');
                            $join->where('sy.isactive','1');
                        })
                        ->where('enrolledstud.studid',$studentId)
                        ->get();

    }

    public static function gradelevelassignedsub($studentid,$gradelevelid){

        return self::selectassignsubjects()
                        ->where('enrolledstud.studid',$studentid)
                        ->where('enrolledstud.levelid',$gradelevelid)
                        ->get();
                    
    }

    public static function teacherDaySched($teacherId,$day){

        return self::classScheduleQuery($teacherId)
                        ->select(
                            'subjects.subjdesc',
                            'classscheddetail.stime',
                            'classscheddetail.etime'
                        )
                        ->where('classscheddetail.stime','!=',null)
                        ->where('classscheddetail.days',$day)
                        ->distinct()
                        ->orderBy('classscheddetail.days')
                        ->orderBy('classscheddetail.stime')
                        ->get();
    }

    public static function teacherclassSchedule($teacherId){

        return self::classScheduleQuery($teacherId)
                ->select(
                    'subjects.subjdesc',
                    'assignsubjdetail.subjid',
                    'sections.sectionname',
                    'rooms.roomname',
                    'classscheddetail.stime',
                    'classscheddetail.etime',
                    'days.description',
                    'gradelevel.levelname',
                    'classscheddetail.days as day'
                    )
                ->distinct()
                ->orderBy('classscheddetail.days')
                ->orderBy('classscheddetail.stime')
                ->get();
    }


    public static function classScheduleQuery($teacherId){
        return DB::table('assignsubj')
                ->leftJoin('sy',function($join){
                    $join->on('assignsubj.syid','=','sy.id');
                    $join->where('isactive','1');
                })
                ->join('assignsubjdetail',function($join) use ($teacherId){
                    $join->on('assignsubj.id','=','assignsubjdetail.headerid')
                    ->where('assignsubjdetail.teacherid',$teacherId)
                    ->where('assignsubjdetail.deleted','0');
                })
                ->leftJoin('sections',function($join){
                    $join->on('assignsubj.sectionid','=','sections.id');
                }) 
                ->join('subjects',function($join){
                    $join->on('assignsubjdetail.subjid','=','subjects.id')
                    ->where('subjects.isactive','1')
                    ->where('subjects.deleted','0');
                })
               
                ->leftJoin('classsched',function($join){
                    $join->on('assignsubjdetail.subjid','=','classsched.subjid');
                    $join->on('assignsubj.sectionid','=','classsched.sectionid');
                    $join->where('classsched.deleted','0');
                    $join->whereIn('classsched.syid',function($query){
                        $query->select('id')->from('sy')->where('sy.isactive','1');
                    });
                })
                ->leftJoin('classscheddetail',function($join){
                    $join->on('classsched.id','=','classscheddetail.headerid')
                         ->where('classscheddetail.deleted','0');
                })
                ->leftJoin('days','classscheddetail.days','=','days.id')
                ->leftJoin('rooms','classscheddetail.roomid','=','rooms.id')
                ->join('gradelevel',function($join){
                    $join->on('assignsubj.glevelid','=','gradelevel.id');
                    $join->where('gradelevel.deleted','0');
                });
    }

    public static function loadSectionSubject($sectoinId){

        return DB::table('assignsubj')
                ->leftJoin('sy',function($join){
                    $join->on('assignsubj.syid','=','sy.id');
                    $join->where('isactive','1');
                })
                ->join('assignsubjdetail',function($join){
                    $join->on('assignsubj.id','=','assignsubjdetail.headerid')
                    ->where('assignsubjdetail.deleted','0');
                })
                ->leftJoin('sections',function($join){
                    $join->on('assignsubj.sectionid','=','sections.id');
                }) 
                ->join('subjects',function($join){
                    $join->on('assignsubjdetail.subjid','=','subjects.id')
                    ->where('subjects.isactive','1')
                    ->where('subjects.deleted','0');
                })
               
                ->leftJoin('classsched',function($join){
                    $join->on('assignsubjdetail.subjid','=','classsched.subjid');
                    $join->on('assignsubj.sectionid','=','classsched.sectionid');
                    $join->where('classsched.deleted','0');
                    $join->whereIn('classsched.syid',function($query){
                        $query->select('id')->from('sy')->where('sy.isactive','1');
                    });
                })
                ->leftJoin('classscheddetail',function($join){
                    $join->on('classsched.id','=','classscheddetail.headerid')
                         ->where('classscheddetail.deleted','0');
                })
                ->leftJoin('days','classscheddetail.days','=','days.id')
                ->leftJoin('rooms','classscheddetail.roomid','=','rooms.id')
                ->join('gradelevel',function($join){
                    $join->on('assignsubj.glevelid','=','gradelevel.id');
                    $join->where('gradelevel.deleted','0');
                })
                ->select(
                    'subjects.subjdesc',
                    'assignsubjdetail.subjid',
                    'sections.sectionname',
                    'rooms.roomname',
                    'classscheddetail.stime',
                    'classscheddetail.etime',
                    'days.description',
                    'gradelevel.levelname'
                    )
                ->distinct()
                ->orderBy('classscheddetail.days')
                ->orderBy('classscheddetail.stime')
                ->where('assignsubj.sectionid',$sectoinId)
                ->get();
    }


    public static function todayClassSchedule($sectoinId, $day){

        return DB::table('assignsubj')
                        ->leftJoin('sy',function($join){
                            $join->on('assignsubj.syid','=','sy.id');
                            $join->where('isactive','1');
                        })
                        ->join('assignsubjdetail',function($join){
                            $join->on('assignsubj.id','=','assignsubjdetail.headerid')
                            ->where('assignsubjdetail.deleted','0');
                        })
                        ->join('teacher',function($join){
                            $join->on('assignsubjdetail.teacherid','=','teacher.id')
                            ->where('teacher.deleted','0')
                            ->where('teacher.isactive','1');
                        })
                        ->leftJoin('sections',function($join){
                            $join->on('assignsubj.sectionid','=','sections.id');
                        }) 
                        ->join('subjects',function($join){
                            $join->on('assignsubjdetail.subjid','=','subjects.id')
                            ->where('subjects.isactive','1')
                            ->where('subjects.deleted','0');
                        })
                    
                        ->leftJoin('classsched',function($join){
                            $join->on('assignsubjdetail.subjid','=','classsched.subjid');
                            $join->on('assignsubj.sectionid','=','classsched.sectionid');
                            $join->where('classsched.deleted','0');
                            $join->whereIn('classsched.syid',function($query){
                                $query->select('id')->from('sy')->where('sy.isactive','1');
                            });
                        })
                        ->leftJoin('classscheddetail',function($join) use($day){
                            $join->on('classsched.id','=','classscheddetail.headerid')
                            ->where('classscheddetail.deleted','0')
                            ->where('classscheddetail.days',$day);
                        })
                        ->leftJoin('days','classscheddetail.days','=','days.id')
                        ->leftJoin('rooms','classscheddetail.roomid','=','rooms.id')
                        ->join('gradelevel',function($join){
                            $join->on('assignsubj.glevelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->select(
                            'classsched.id as classchedid',
                            'assignsubjdetail.id as assid',
                            'teacher.id as teacherid',
                            'teacher.lastname',
                            'teacher.firstname',
                            'subjects.id as subjectid',
                            'subjects.subjcode',
                            'assignsubjdetail.subjid',
                            'sections.sectionname',
                            'rooms.roomname',
                            'rooms.id as roomid',
                            'classscheddetail.stime',
                            'classscheddetail.etime',
                            'days.description',
                            'gradelevel.levelname'
                            )
                        ->distinct()
                        ->where('classscheddetail.stime','!=',null)
                        ->orderBy('classscheddetail.days')
                        ->orderBy('classscheddetail.stime')
                        ->where('assignsubj.sectionid',$sectoinId)
                        ->get();
    }



    public static function filterClassSchedule($sectoinId, $day){

            $schedule = self::todayClassSchedule($sectoinId, $day);

            $dataString = '';

            if(count($schedule)){

                foreach($schedule as $key=>$item){

                    $dataString.='<tr class="period old" id="'.($key+1).'">';
                    
                    if(Carbon::create($item->stime)->isoFormat('hh:mm a')=='12:00 am' && Carbon::create($item->etime)->isoFormat('hh:mm a')=='12:00 am' ){
                        $dataString.='<td>Time Not Set</td>';
                    }          
                    else{
                        $dataString.='<td>'.Carbon::create($item->stime)->isoFormat('hh:mm A').' - '.Carbon::create($item->etime)->isoFormat('hh:mm A').'</td>';
                    }
                    
                    $dataString.=
                    '<td id="'.$item->subjectid.'" class="as'.$item->assid.' clasch'.$item->classchedid.' tablesub">'.$item->subjcode.'</td>'.
                    '<td id="'.$item->teacherid.'">'.$item->lastname.' '.substr($item->firstname,0,1).'.</td>';

                    if($item->roomname == null){
                        $dataString.='<td>Room not assigned</td>';
                    }
                    else{
                        $dataString.='<td id="'.$item->roomid.'">'.$item->roomname.'</td>';
                    }

                    $dataString.='<td class="act"></td>';
                               
                    $dataString.='</tr>';
                    
                }

                return $dataString;
            }
            
            else{
                return  '<td colspan="4" class="text-center">No Class Schedule For this day</td>';
            }

         
    }

    public static function storeClassSchedule($request){
        
        $currentSchoolYear = DB::table('sy')->where('isactive','1')->first();
        
            foreach( $request->get('i') as $item){

                
                $teacherid = $request->get('t');

                $assignedsub = DB::table('assignsubj')
                                        ->where('assignsubj.sectionid',$item['s'])
                                        ->leftJoin('assignsubjdetail',function($join) use($teacherid){
                                            $join->on('assignsubjdetail.headerid','=','assignsubj.id');
                                            $join->where('teacherid',$teacherid);
                                            $join->where('assignsubj.deleted','0');
                                        })
                                        ->leftJoin('sy',function($join) {
                                            $join->on('assignsubj.syid','=','sy.id');
                                            $join->where('isactive','1');
                                        })
                                        ->where('assignsubj.deleted','0')
                                        ->get();
                         

                $time = explode(" - ", $item['t']);

                $classSchedCheck = DB::table('classsched')
                                    ->select('classsched.*')
                                    ->where('sectionid',$item['s'])
                                    ->where('subjid',$request->get('s'))
                                    ->leftJoin('sy',function($join){
                                        $join->on('classsched.syid','=','sy.id');
                                        $join->where('isactive','1');
                                    })->get();

                if(count($classSchedCheck)==0){

                    $classSchedId = DB::table('classsched')->insertGetID([
                        'glevelid'=>$item['s'],
                        'sectionid'=>$item['s'],
                        'subjid'=>$request->get('s'),
                        'syid'=>$currentSchoolYear->id,
                        'deleted'=>'0',
                        'createdby'=>auth()->user()->id
                    ]);

                    $array_days = explode("/ ", $item['d']);

                    foreach($array_days as $days){
                        DB::table('classscheddetail')->insert([
                            'headerid'=>$classSchedId,
                            'days'=>Carbon::create($days)->isoFormat('d'),
                            'stime'=>Carbon::create($time[0])->isoFormat('HH:mm:ss'),
                            'etime'=>Carbon::create($time[1])->isoFormat('HH:mm:ss'),
                            'roomid'=>'1',
                            'createdby'=>auth()->user()->id
                        ]);
                    }
                }
                else{

                    $array_days = explode("/ ", $item['d']);

                    foreach($array_days as $days){
                        DB::table('classscheddetail')->insert([
                            'headerid'=>$classSchedCheck[0]->id,
                            'days'=>Carbon::create($days)->isoFormat('d'),
                            'stime'=>Carbon::create($time[0])->isoFormat('HH:mm:ss'),
                            'etime'=>Carbon::create($time[1])->isoFormat('HH:mm:ss'),
                            'roomid'=>'1',
                            'createdby'=>auth()->user()->id
                        ]);
                    }
                }

                if(count($assignedsub)==0){
                    $assignsubjid = DB::table('assignsubj')->insertGetID([
                        'glevelid'=>$item['s'],
                        'sectionid'=>$item['s'],
                        'syid'=>$currentSchoolYear->id,
                        'deleted'=>'0',
                        'createdby'=>auth()->user()->id
                    ]);
    
                    DB::table('assignsubjdetail')->insert([
                        'deleted'=>'0',
                        'headerid'=>$assignsubjid,
                        'subjid'=>$request->get('s'),
                        'teacherid'=>$request->get('t'),
                        'createdby'=>auth()->user()->id
                    ]);
                }
                else{

                    if($assignedsub[0]->teacherid == null){
                        DB::table('assignsubjdetail')->insert([
                            'deleted'=>'0',
                            'headerid'=>$assignedsub[0]->ID,
                            'subjid'=>$request->get('s'),
                            'teacherid'=>$request->get('t'),
                            'createdby'=>auth()->user()->id
                        ]);
                    }
                }
            }
    }


}
