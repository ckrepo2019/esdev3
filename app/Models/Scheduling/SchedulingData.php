<?php

namespace App\Models\Scheduling;

use Illuminate\Database\Eloquent\Model;
use DB;

class SchedulingData extends Model
{
    
   public static function gshs_schedule($syid = null, $sectionid = null){

        $sched = DB::table('classsched')
                        ->where('classsched.deleted',0)
                        ->where('classsched.syid',$syid)
                        ->where('classsched.sectionid',$sectionid)
                        ->join('subjects',function($join){
                            $join->on('classsched.subjid','=','subjects.id');
                            $join->where('classsched.deleted',0);
                        })  
                        ->join('classscheddetail',function($join){
                            $join->on('classsched.id','=','classscheddetail.headerid');
                            $join->where('classscheddetail.deleted',0);
                        })  
                        ->leftJoin('rooms',function($join){
                            $join->on('classscheddetail.roomid','=','rooms.id');
                            $join->where('rooms.deleted','0');
                        })
                        ->leftJoin('days',function($join){
                            $join->on('classscheddetail.days','=','days.id');
                        })
                        ->join('schedclassification',function($join){
                            $join->on('classscheddetail.classification','=','schedclassification.id');
                            $join->where('schedclassification.deleted',0);
                        }) 
                        ->join('assignsubj',function($join) use($sectionid,$syid){
                            $join->on('classsched.sectionid','=','assignsubj.sectionid');
                            $join->where('assignsubj.syid',$syid);
                            $join->where('assignsubj.sectionid',$sectionid);
                            $join->where('assignsubj.deleted',0);
                        }) 
                        ->join('assignsubjdetail',function($join){
                            $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                            $join->on('classsched.subjid','=','assignsubjdetail.subjid');
                            $join->where('assignsubjdetail.deleted',0);
                        }) 
                        ->leftJoin('teacher',function($join){
                            $join->on('assignsubjdetail.teacherid','=','teacher.id');
                            $join->where('teacher.deleted','0');
                        })
                        ->select(
                            'classsched.subjid',
                            'subjdesc as subjdesc',
                            'days.description',
                            'rooms.roomname',
                            'schedclassification.description as classification',
                            'classscheddetail.etime',
                            'classscheddetail.stime',
                            'firstname',
                            'lastname'
                        )
                        ->orderBy('days.id')
                        ->distinct()
                        ->get();

        $bySubject = collect($sched)->groupBy('subjid');
        $data = array();

        foreach($bySubject as $key=>$item){
            
            foreach(collect($item)->groupBy('stime') as $secondItem){
                $day = '';
                foreach($secondItem as $thirdItem){
                    $details = $thirdItem;
                    if($thirdItem->description == 'Thursday'){
                        $day .= substr($thirdItem->description, 0 , 1).'h';
                    }
                    elseif($thirdItem->description == 'Sunday'){
                        $day .= substr($thirdItem->description, 0 , 1).'un';
                    }
                    else{
                        $day .= substr($thirdItem->description, 0 , 1).'';
                    }
                }

                $details->description = $day;
                array_push($data, $details);
            };
        }


    
        $data = collect($data)->groupBy('subjid');

        $second_filter = array();

        foreach($data as $item){
            $temp_sched = array();
            
            foreach($item as $new_item){
                array_push($temp_sched, (object)[
                    'etime'=>\Carbon\Carbon::create($new_item->etime)->isoFormat('hh:mm A'),
                    'stime'=>\Carbon\Carbon::create($new_item->stime)->isoFormat('hh:mm A'),
                    'description'=>$new_item->description,
                    'roomname'=>$new_item->roomname,
                    'classification'=>$new_item->classification
                ]);
            }

            $teacher = '';
            if($item[0]->lastname != null && $item[0]->firstname != null){
                $teacher  = $item[0]->lastname .', ' .$item[0]->firstname;
            }

            array_push( $second_filter , (object)[
                'subjid'=>$item[0]->subjid,
                'subjdesc'=>$item[0]->subjdesc,
                'schedule'=>$temp_sched,
                'teacher'=>$teacher
            ]);
        }

        return $second_filter; 

    }

   public static function sh_schedule($syid = null, $semid = null, $sectionid = null, $blockid = null){

        $sched = array();

        $sh_sched = DB::table('sh_classsched')
                        ->join('sh_subjects',function($join){
                            $join->on('sh_classsched.subjid','=','sh_subjects.id');
                            $join->where('sh_classsched.deleted',0);
                        })  
                        ->join('sh_classscheddetail',function($join){
                            $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                            $join->where('sh_classscheddetail.deleted',0);
                        })  
                        ->leftJoin('rooms',function($join){
                            $join->on('sh_classscheddetail.roomid','=','rooms.id');
                            $join->where('rooms.deleted','0');
                        })
                        ->leftJoin('days',function($join){
                            $join->on('sh_classscheddetail.day','=','days.id');
                        })
                        ->join('schedclassification',function($join){
                            $join->on('sh_classscheddetail.classification','=','schedclassification.id');
                            $join->where('schedclassification.deleted',0);
                        })  
                        ->leftJoin('teacher',function($join){
                            $join->on('sh_classsched.teacherid','=','teacher.id');
                            $join->where('teacher.deleted','0');
                        })
                        ->where('sectionid',$sectionid)
                        ->where('sh_classsched.deleted',0)
                        ->where('sh_classsched.syid',$syid)
                        ->where('sh_classsched.semid',$semid)
                        ->select(
                            'subjid',
                            'subjtitle as subjdesc',
                            'days.description',
                            'rooms.roomname',
                            'schedclassification.description as classification',
                            'sh_classscheddetail.etime',
                            'sh_classscheddetail.stime',
                            'firstname',
                            'lastname'
                        )
                        ->orderBy('days.id')
                        ->distinct()
                        ->get();

        $block_sched = DB::table('sh_blocksched')
                        ->join('sh_subjects',function($join){
                            $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                            $join->where('sh_blocksched.deleted',0);
                        })  
                        ->join('sh_blockscheddetail',function($join){
                            $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                            $join->where('sh_blockscheddetail.deleted',0);
                        }) 
                        ->join('schedclassification',function($join){
                            $join->on('sh_blockscheddetail.classification','=','schedclassification.id');
                            $join->where('schedclassification.deleted',0);
                        })  
                        ->leftJoin('teacher',function($join){
                            $join->on('sh_blocksched.teacherid','=','teacher.id');
                            $join->where('teacher.deleted','0');
                        })
                        ->leftJoin('rooms',function($join){
                            $join->on('sh_blockscheddetail.roomid','=','rooms.id');
                            $join->where('rooms.deleted','0');
                        })
                        ->leftJoin('days',function($join){
                            $join->on('sh_blockscheddetail.day','=','days.id');
                        })
                        ->where('blockid',$blockid)
                        ->where('sh_blocksched.deleted',0)
                        ->where('sh_blocksched.syid',$syid)
                        ->where('sh_blocksched.semid',$semid)
                        ->select(
                            'subjid',
                            'subjtitle as subjdesc',
                            'days.description',
                            'rooms.roomname',
                            'sh_blockscheddetail.etime',
                            'sh_blockscheddetail.stime',
                            'schedclassification.description as classification',
                            'firstname',
                            'lastname'
                        )
                        ->orderBy('days.id')
                        ->distinct()
                        ->get();

        
        foreach($sh_sched as $item){
            array_push($sched,$item);
        }
        foreach($block_sched as $item){
            array_push($sched,$item);
        }

        $bySubject = collect($sched)->groupBy('subjid');
        $data = array();

        foreach($bySubject as $key=>$item){
         
            foreach(collect($item)->groupBy('stime') as $secondItem){
                $day = '';
                foreach($secondItem as $thirdItem){
                    $details = $thirdItem;
                    if($thirdItem->description == 'Thursday'){
                        $day .= substr($thirdItem->description, 0 , 1).'h';
                    }
                    elseif($thirdItem->description == 'Sunday'){
                        $day .= substr($thirdItem->description, 0 , 1).'un';
                    }
                    else{
                        $day .= substr($thirdItem->description, 0 , 1).'';
                    }
                }

                $details->description = $day;
                array_push($data, $details);
            };
        }


    
        $data = collect($data)->groupBy('subjid');

        $second_filter = array();

        foreach($data as $item){
            $temp_sched = array();
           
            foreach($item as $new_item){
                array_push($temp_sched, (object)[
                    'etime'=>\Carbon\Carbon::create($new_item->etime)->isoFormat('hh:mm A'),
                    'stime'=>\Carbon\Carbon::create($new_item->stime)->isoFormat('hh:mm A'),
                    'description'=>$new_item->description,
                    'roomname'=>$new_item->roomname,
                    'classification'=>$new_item->classification
                ]);
            }

            $teacher = '';
            if($item[0]->lastname != null && $item[0]->firstname != null){
                $teacher  = $item[0]->lastname .', ' .$item[0]->firstname;
            }

            array_push( $second_filter , (object)[
                'subjid'=>$item[0]->subjid,
                'subjdesc'=>$item[0]->subjdesc,
                'schedule'=>$temp_sched,
                'teacher'=>$teacher
            ]);
        }

        return $second_filter; 



   }

}
