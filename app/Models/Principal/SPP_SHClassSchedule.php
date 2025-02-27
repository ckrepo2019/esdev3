<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;
use App\Models\Principal\SPP_Days;
use Session;

class SPP_SHClassSchedule extends Model
{
    public static function searchshschedulebyday($sectionid,$blockid,$day){

        $SHSectionSched = DB::table('sh_classsched')
            ->join('sh_classscheddetail',function($join) use ($day){
                $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                $join->where('sh_classscheddetail.day',$day);
                $join->where('sh_classscheddetail.deleted','0');
            })
            ->leftJoin('sy',function($join){
                $join->on('sy.id','=','sh_classsched.syid');
                $join->where('sy.isactive','1');
            })
            ->leftJoin('semester',function($join){
                $join->on('semester.id','=','sh_classsched.semid');
                $join->where('sy.isactive','1');
            })
            ->leftJoin('teacher',function($join){
                $join->on('sh_classsched.teacherid','=','teacher.id')
                ->where('teacher.deleted','0')
                ->where('teacher.isactive','1');
            })
            ->leftJoin('sh_subjects',function($join){
                $join->on('sh_classsched.subjid','=','sh_subjects.id');
                $join->where('sh_subjects.deleted','0');
                $join->where('sh_subjects.isactive','1');
            })
            ->leftJoin('rooms','sh_classscheddetail.roomid','=','rooms.id')
            ->select(
                'sh_classsched.*',
                'sh_classscheddetail.roomid',
                'sh_classscheddetail.stime',
                'sh_classscheddetail.etime',
                'teacher.firstname', 'teacher.lastname',
                'rooms.roomname',
                'sh_subjects.subjcode'
            )
            ->where('sectionid',$sectionid)
            ->get();

        $blockSched = DB::table('sh_blocksched')
                            ->leftJoin('sy',function($join){
                                $join->on('sy.id','=','sh_blocksched.syid');
                                $join->where('sy.isactive','1');
                            })
                            ->leftJoin('semester',function($join){
                                $join->on('semester.id','=','sh_blocksched.semid');
                                $join->where('sy.isactive','1');
                            })
                            ->join('sh_blockscheddetail',function($join) use ($day){
                                $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                                $join->where('sh_blockscheddetail.day',$day);
                                $join->where('sh_blockscheddetail.deleted','0');
                            })
                            ->leftJoin('teacher',function($join){
                                $join->on('sh_blocksched.teacherid','=','teacher.id')
                                ->where('teacher.deleted','0')
                                ->where('teacher.isactive','1');
                            })
                            ->leftJoin('sh_subjects',function($join){
                                $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                                $join->where('sh_subjects.deleted','0');
                                $join->where('sh_subjects.isactive','1');
                            })
                            ->leftJoin('rooms','sh_blockscheddetail.roomid','=','rooms.id')
                            ->select(
                                'sh_blocksched.*',
                                'sh_blockscheddetail.roomid',
                                'sh_blockscheddetail.stime',
                                'sh_blockscheddetail.etime',
                                'teacher.firstname', 'teacher.lastname',
                                'rooms.roomname',
                                'sh_subjects.subjcode'
                            )
                            ->where('blockid',$blockid)
                            ->get();
                        
        $emptySectionSched = true;
        $emptySectionSched = true;
    
        $dataString = '';


        if(count($SHSectionSched)>0){

            foreach($SHSectionSched as $key=>$item){

                $dataString .= '<tr class="period old" id="'.($key+1).'">';

                    $dataString .= '<td>'.Carbon::create($item->stime)->isoFormat('hh:mm A').' - '.Carbon::create($item->etime)->isoFormat('hh:mm A').'</td>
                                <td id="'.$item->subjid.'" class="tablesub bid'.$item->id.'">'.$item->subjcode.'</td>
                                <td id="'.$item->teacherid.'">'.$item->lastname.' '.substr($item->firstname,0,1).'.</td>
                                <td id="'.$item->roomid.'">'.$item->roomname.'</td>
                                <td class="act"></td>';

                $dataString .= '</tr>';

            }

            $emptySectionSched = false;

        }

        if(count($blockSched)>0){

            foreach($blockSched as $key=>$item){

                $dataString .= '<tr class="bg-success" id="'.($key+1).'">';

                    $dataString .= '<td>'.Carbon::create($item->stime)->isoFormat('hh:mm A').' - '.Carbon::create($item->etime)->isoFormat('hh:mm A').'</td>
                                <td id="'.$item->subjid.'" class="tablesub bid'.$item->id.'">'.$item->subjcode.'</td>
                                <td id="'.$item->teacherid.'">'.$item->lastname.' '.substr($item->firstname,0,1).'.</td>
                                <td id="'.$item->roomid.'">'.$item->roomname.'</td>
                                <td></td>';

                $dataString .= '</tr>';

            }

            $emptySectionSched = false;

        }

        if($emptySectionSched && $emptySectionSched){
            return '<tr><td class="text-center" colspan="5">No Sched for this day</td></tr>';
        }

        else{
            return $dataString;
        }

    }

    public static function storeshclassschedule($request){

        $activesem = DB::table('semester')->where('isactive','1')->first();
        $activesy = DB::table('sy')->where('isactive','1')->first();
        $levelid = DB::table('sections')->where('id',$request->get('section'))->first();

        $storesuccessfull = true;
        $data = array();

        $checkClassSched = DB::table('sh_classsched')
                                ->where('sh_classsched.sectionid',$request->get('section'))
                                ->where('sh_classsched.semid',$activesem->id)
                                ->where('sh_classsched.syid',$activesy->id)
                                ->where('sh_classsched.deleted','0')
                                ->where('sh_classsched.subjid',$request->get('s'))
                                ->get();

        if(count($checkClassSched)==0){

            $classschedid = DB::table('sh_classsched')->insertGetID([
                        'sectionid'=>$request->get('section'),
                        'teacherid'=>$request->get('tea'),
                        'syid'=>$activesy->id,
                        'semid'=>$activesem->id,
                        'deleted'=>0,
                        'subjid'=>$request->get('s'),
                        'glevelid'=>$levelid->levelid
                    ]);
            
            foreach($request->get('days') as $d){


                $detail = DB::table('sh_classscheddetail')
                            ->where('headerid',$classschedid)
                            ->where('day',$d)
                            ->where('classification',$request->get('class'))
                            ->get();

                if(count($detail) == 0){

                    $time = explode(" - ", $request->get('t'));

                    $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                    $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

                    DB::table('sh_classscheddetail')->insert([
                        'headerid'=>$classschedid,
                        'stime'=>$stime,
                        'etime'=>$etime,
                        'roomid'=>$request->get('r'),
                        'day'=>$d,
                        'classification'=>$request->get('class')
                    ]);

                }
                else{

                    $time = explode(" - ", $request->get('t'));

                    $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                    $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
    
                    DB::table('sh_classscheddetail')
                        ->where('id',$detail[0]->id)
                        ->where('classification',$request->get('class'))
                        ->update([
                            'headerid'=>$classschedid,
                            'stime'=>$stime,
                            'etime'=>$etime,
                            'roomid'=>$request->get('r'),
                            'deleted'=>'0',
                            'day'=>$d
                        ]);
                }

            }
            
        }
        else{


            foreach($request->get('days') as $d){

                $detail = DB::table('sh_classscheddetail')
                            ->where('headerid',$checkClassSched[0]->id)
                            ->where('day',$d)
                            ->where('classification',$request->get('class'))
                            ->get();

                if(count($detail) == 0){

                   
                    $time = explode(" - ", $request->get('t'));

                    $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                    $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
    
                    DB::table('sh_classscheddetail')->insert([
                        'headerid'=>$checkClassSched[0]->id,
                        'stime'=>$stime,
                        'etime'=>$etime,
                        'roomid'=>$request->get('r'),
                        'day'=>$d,
                        'classification'=>$request->get('class')
                    ]);

                }
                else{

                    $time = explode(" - ", $request->get('t'));

                    $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                    $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
    
                    DB::table('sh_classscheddetail')
                        ->where('id',$detail[0]->id)
                        ->where('classification',$request->get('class'))
                        ->update([
                            'headerid'=>$checkClassSched[0]->id,
                            'stime'=>$stime,
                            'etime'=>$etime,
                            'roomid'=>$request->get('r'),
                            'deleted'=>'0',
                            'day'=>$d,
                        ]);
                }

            }
        }


        return back();
        
    }

    public static function updateshclassschedule($request){

        DB::table('sh_classsched')
                ->where('id',$request->get('csid'))
                ->update([
                    'subjid'=>$request->get('sub'),
                    'teacherid'=>$request->get('tea')
                ]);

        $classsched = DB::table('sh_classscheddetail')
                     ->where('headerid',$request->get('csid'))
                     ->where('deleted','0')
                     ->get();

        $day = SPP_Days::loadDays();
        



        foreach($day  as $item){

            $indb = false;
            $ingiven = false;

            foreach($classsched as $csh){
                if($csh->day ==  $item->id){
                    $indb = true;
                }
            }

            foreach($request->get('days') as $day){

                if($day ==  $item->id){
                    $ingiven = true;
                }

            }


            if($indb && $ingiven){

                $time = explode(" - ", $request->get('tim'));

                $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

                DB::table('sh_classscheddetail')
                    ->where('headerid',$request->get('csid'))
                    ->where('day',$item->id)
                    ->where('deleted','0')
                    ->update([
                        'stime'=>$stime,
                        'etime'=>$etime,
                        'roomid'=>$request->get('roo')
                    ]);

            }

            else if($indb && !$ingiven){

                DB::table('sh_classscheddetail')
                    ->where('headerid',$request->get('csid'))
                    ->where('day',$item->id)
                    ->where('deleted','0')
                    ->update([
                       'deleted'=>'1'
                    ]);

            }
            else if(!$indb && $ingiven){

                $time = explode(" - ", $request->get('tim'));

                $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

                DB::table('sh_classscheddetail')
                    ->insert([
                        'headerid'=>$request->get('csid'),
                        'stime'=>$stime,
                        'etime'=>$etime,
                        'roomid'=>$request->get('roo'),
                        'day'=>$item->id
                    ]);

            }

        }

        return 'success';

    }

    public static function getStudentClasSchedule($studendinfo){

        return DB::table('sh_classsched')
                ->join('sh_classscheddetail',function($join){
                    $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                    $join->where('sh_classscheddetail.deleted','0');
                })
                ->leftJoin('sy',function($join){
                    $join->on('sy.id','=','sh_classsched.syid');
                    $join->where('sy.isactive','1');
                })
                ->leftJoin('semester',function($join){
                    $join->on('semester.id','=','sh_classsched.semid');
                    $join->where('sy.isactive','1');
                })
                ->leftJoin('teacher',function($join){
                    $join->on('sh_classsched.teacherid','=','teacher.id')
                    ->where('teacher.deleted','0')
                    ->where('teacher.isactive','1');
                })
                ->leftJoin('sh_subjects',function($join){
                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                    $join->where('sh_subjects.deleted','0');
                    $join->where('sh_subjects.isactive','1');
                })
                ->leftJoin('rooms','sh_classscheddetail.roomid','=','rooms.id')
                ->join('days','sh_classscheddetail.day','=','days.id')
                ->select(
                    'sh_classsched.sectionid',
                    'sh_classsched.id as id',
                    'rooms.roomname',
                    'sh_classscheddetail.stime',
                    'sh_classscheddetail.etime',
                    'days.description',
                    'sh_subjects.subjtitle as subjdesc',
                    'sh_subjects.id as subjid',
                    'sh_subjects.subjcode',
                    'teacher.id as teacherid',
                    'teacher.firstname',
                    'teacher.lastname'
                    )
                ->orderBy('sh_classscheddetail.stime')
                ->where('sectionid',$studendinfo->sectionid)
                ->where('sh_classsched.deleted','0')
                ->get();
        
    }

    public static function getAllTeacherSHSSubject($teacherid){

        return DB::table('sh_classsched')
                ->leftJoin('sh_subjects',function($join){
                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                    $join->where('sh_subjects.deleted','0');
                    $join->where('sh_subjects.isactive','1');
                })
                ->leftJoin('sy',function($join){
                    $join->on('sy.id','=','sh_classsched.syid');
                    $join->where('sy.isactive','1');
                })
                ->leftJoin('semester',function($join){
                    $join->on('semester.id','=','sh_classsched.semid');
                    $join->where('sy.isactive','1');
                })
                ->join('sections',function($join){
                    $join->on('sh_classsched.sectionid','=','sections.id');
                    $join->where('sections.deleted','0');
                })
                ->join('gradelevel',function($join){
                    $join->on('sections.levelid','=','gradelevel.id');
                    $join->where('gradelevel.deleted','0');
                })
                ->select(
                    'sh_classsched.sectionid',
                    'sections.sectionname',
                    'gradelevel.levelname',
                    'sh_classsched.subjid',
                    'sh_subjects.subjcode',
                    'sh_classsched.teacherid',
                    'gradelevel.acadprogid')
                ->where('sh_classsched.teacherid',$teacherid)
                ->where('sh_classsched.deleted','0')
                ->get();

    }

    public static function getAllTeacherSHSSchedule($teacherid){

        return DB::table('sh_classsched')
                ->leftJoin('sh_classscheddetail',function($join){
                    $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                    $join->where('sh_classscheddetail.deleted','0');
                })
                ->leftJoin('sh_subjects',function($join){
                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                    $join->where('sh_subjects.deleted','0');
                    $join->where('sh_subjects.isactive','1');
                })
                ->leftJoin('sy',function($join){
                    $join->on('sy.id','=','sh_classsched.syid');
                    $join->where('sy.isactive','1');
                })
                ->leftJoin('semester',function($join){
                    $join->on('semester.id','=','sh_classsched.semid');
                    $join->where('sy.isactive','1');
                })
                ->join('sections',function($join){
                    $join->on('sh_classsched.sectionid','=','sections.id');
                    $join->where('sections.deleted','0');
                })
                ->join('gradelevel',function($join){
                    $join->on('sections.levelid','=','gradelevel.id');
                    $join->where('gradelevel.deleted','0');
                })
                ->leftJoin('rooms','sh_classscheddetail.roomid','=','rooms.id')
                ->join('days','sh_classscheddetail.day','=','days.id')
                ->select(
                   'sh_subjects.subjtitle as subjdesc',
                   'sh_classsched.subjid',
                   'sections.sectionname',
                   'rooms.roomname',
                   'sh_classscheddetail.stime',
                   'sh_classscheddetail.etime',
                   'sh_classscheddetail.day',
                   'gradelevel.levelname',
                   'days.description'
                    )
                ->where('sh_classsched.teacherid',$teacherid)
                ->where('sh_classsched.deleted','0')
                ->get();
    }

    public static function storeshclassschedulev2($request){

        if($request->get('semid') != null){
            $activesem = DB::table('semester')->where('id',$request->get('semid'))->first();
        }
        else if(Session::has('semester')){
            $activesem = Session::get('semester');
        }else{
            $activesem = DB::table('semester')->where('isactive',1)->first();
        }


        if($request->get('syid') != null){
            $activesy = DB::table('sy')->where('id',$request->get('syid'))->first();
        }
        else if(Session::has('schoolYear')){
            $activesy = Session::get('schoolYear');
        }else{
            $activesy= DB::table('sy')->where('isactive',1)->first();
        }

        $levelid = DB::table('sections')->where('id',$request->get('section'))->first();

        $storesuccessfull = true;
        $data = array();

        $checkClassSched = DB::table('sh_classsched')
                                ->where('sh_classsched.sectionid',$request->get('section'))
                                ->where('sh_classsched.semid',$activesem->id)
                                ->where('sh_classsched.syid',$activesy->id)
                                ->where('sh_classsched.deleted','0')
                                ->where('sh_classsched.subjid',$request->get('s'))
                                ->get();

        if(count($checkClassSched)==0){

            $classschedid = DB::table('sh_classsched')->insertGetID([
                        'sectionid'=>$request->get('section'),
                        'teacherid'=>$request->get('tea'),
                        'syid'=>$activesy->id,
                        'semid'=>$activesem->id,
                        'deleted'=>0,
                        'subjid'=>$request->get('s'),
                        'glevelid'=>$levelid->levelid,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>Carbon::now('Asia/Manila')
                    ]);
            
            foreach($request->get('days') as $d){

                $time = explode(" - ", $request->get('t'));

                $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

                DB::table('sh_classscheddetail')->insert([
                    'headerid'=>$classschedid,
                    'stime'=>$stime,
                    'etime'=>$etime,
                    'roomid'=>$request->get('r'),
                    'day'=>$d,
                    'classification'=>$request->get('class'),
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>Carbon::now('Asia/Manila')
                ]);

            }
            
        }
        else{

            foreach($request->get('days') as $d){

                    $time = explode(" - ", $request->get('t'));

                    $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                    $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
    
                    DB::table('sh_classscheddetail')->insert([
                        'headerid'=>$checkClassSched[0]->id,
                        'stime'=>$stime,
                        'etime'=>$etime,
                        'roomid'=>$request->get('r'),
                        'day'=>$d,
                        'classification'=>$request->get('class'),
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>Carbon::now('Asia/Manila')
                    ]);
            

            }
        }

      
        
    }


}
