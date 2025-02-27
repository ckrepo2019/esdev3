<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class SPP_Blocks extends Model
{

    public static function loadAllBlocks(){
    
        return DB::table('sh_block')->get();
    
    }

    public static function blockQuery(){

        return DB::table('sh_block')
                    ->leftJoin('sh_strand',function($join){
                        $join->on('sh_block.strandid','=','sh_strand.id');
                        $join->where('sh_strand.deleted','0');
                        $join->where('sh_strand.active','1');
                    })
                    ->leftJoin('users as cb','sh_block.createdby','=','cb.id')
                    ->leftJoin('users as ub','sh_block.updatedby','=','ub.id')
                    ->select('sh_block.*','sh_strand.strandname',
                    'cb.name as cbname', 
                    'ub.name as ubname')
                    ->where('sh_block.deleted','0');

    }

    public static function blockinfo($blockid){

        return self::blockQuery()->where('sh_block.id',$blockid)->first();

    }

    public static function loadAllBlocksWithCount(){

        $data = array();

        $blockCount = count(self::blockQuery()->get())/6;
        
        if(round($blockCount) < $blockCount){
            $blockCount = round($blockCount)+1;
        }
        else{
            $blockCount = round($blockCount);
        }

        array_push($data, (object) array(
            'blocks'=>self::blockQuery()->take(6)->get(),
            'blockCount'=> $blockCount
            ));

        return $data;
    }

    public static function storeblock($request){

        $inputsareValid = true;
        $returnBack = back();

        if($request->get('bn')==null){
            $inputsareValid = false;
            $returnBack->with('bn', (object) ['message'=>'Block name is required']);
        }
        if($request->get('si')==null){
            $inputsareValid = false;
            $returnBack->with('si', (object) ['message'=>'Strand is required']);
        }

        if($inputsareValid){

            DB::table('sh_block')->insert([
                'blockname'=>$request->get('bn'),
                'strandid'=>$request->get('si')
            ]);

            toast('Block successfully updated','success')->autoClose(2000)->toToast($position = 'top-right')->hideCloseButton();

            return  $returnBack;
        }
        else{

            toast('Invalid Inptus','error')->autoClose(2000)->toToast($position = 'top-right')->hideCloseButton();

            return  $returnBack->with('error',['']);
        }
    }

    public static function searchblock($filtername,$pagenum){


        $dataString = '';

        $blocks =  self::blockQuery()
                    ->whereExists(function($query) use($filtername){
                        $query->where('sh_block.blockname','like',$filtername.'%')
                        ->orWhere('sh_strand.strandname','like',$filtername.'%');
                    })
                    ->skip(($pagenum-1)*6)
                    ->take(6)
                    ->get();

        $blockCount =  count(self::blockQuery()
                ->whereExists(function($query) use($filtername){
                    $query->where('sh_block.blockname','like',$filtername.'%')
                    ->orWhere('sh_strand.strandname','like',$filtername.'%');
                })
                ->get())/6;

        if(round($blockCount) < $blockCount){
            $blockCount = round($blockCount)+1;
        }
        else{
            $blockCount = round($blockCount);
        }


        if(count($blocks)>0){
            $dataString .='<div class="row d-flex align-items-stretch p-4">';
            foreach($blocks as $block){
                $dataString .= '<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                                    <div class="card bg-light">
                                        <div class="card-body pt-0 mt-2">
                                        <div class="row">
                                        <div class="col-7">
                                        <h2 class="lead"><b>'.$block->blockname.
                                            '<span class="h6"><br><span></b>
                                            </h2>
                                            <p class="text-muted text-sm"><b>Strand</b><br>'.$block->strandname.'</p>';
                                       
                                            
                                            
                                            
                                $dataString .= '  </div>
                                            <div class="col-5 text-center">
                                            <img src="../../dist/img/user2-160x160.jpg" alt="" class="img-circle img-fluid">
                                            </div>
                                        </div>
                                        </div>
                                        <a href="/prinicipalblockinfo/'.$block->id.'" class="card-footer bg-info text-center"><span class="text-white">More info </span><i class=" text-white fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>';
                }
                $dataString .=' </div>';

                $dataString .='<div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>';
                    for($x=1 ; $x <=$blockCount;$x++){
                        if($x==$pagenum){
                            $dataString .='<li class="page-item"><a  id="'.$x.'" class="page-link page-link-active" href="#">'.$x.'</a></li>'; 
                        }
                        else{
                            $dataString .='<li class="page-item"><a class="page-link" id="'.$x.'" href="#">'.$x.'</a></li>'; 
                        }
                    }
                $dataString .='<li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>';
            
            }
            else{
                $dataString .='<div class="row d-flex align-items-stretch p-4">';
                $dataString .= '<a class=" w-100 text-center">No Block Found</a>';
                $dataString .= '</div>';
            }

        return  $dataString;

    }


    public static function getblocksched($blockid, $day){

        $blocksched =  DB::table('sh_block')
                        ->join('sh_blocksched',function($join){
                            $join->on('sh_block.id','=','sh_blocksched.blockid');
                            $join->whereIn('sh_blocksched.syid',function($query){
                                $query->select('id')->from('sy')->where('sy.isactive','1');
                            });
                            $join->whereIn('sh_blocksched.semid',function($query){
                                $query->select('id')->from('semester')->where('semester.isactive','1');
                            });
                        })
                        ->join('sh_blockscheddetail',function($join) use($day){
                            $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                            $join->where('sh_blockscheddetail.deleted','0');
                            $join->where('day',$day);
                        })
                        ->join('sh_subjects',function($join){
                            $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                            $join->where('sh_subjects.deleted','0');
                            $join->where('sh_subjects.isactive','1');
                        })
                        ->join('teacher',function($join){
                            $join->on('sh_blocksched.teacherid','=','teacher.id')
                            ->where('teacher.deleted','0')
                            ->where('teacher.isactive','1');
                        })
                        ->leftJoin('rooms','sh_blockscheddetail.roomid','=','rooms.id')
                        ->where('sh_block.id',$blockid)
                        ->select(
                            'sh_blocksched.*',
                            'sh_blockscheddetail.roomid',
                            'sh_blockscheddetail.stime',
                            'sh_blockscheddetail.etime',
                            'teacher.firstname', 'teacher.lastname',
                            'rooms.roomname',
                            'sh_subjects.subjcode'
                        )
                        ->get();

        if(count($blocksched)>0){
            $dataString = '';

            foreach($blocksched as $key=>$item){

                $dataString .= '<tr class="period old" id="'.($key+1).'">';

                    $dataString .= '<td>'.Carbon::create($item->stime)->isoFormat('hh:mm A').' - '.Carbon::create($item->etime)->isoFormat('hh:mm A').'</td>
                                <td id="'.$item->subjid.'" class="tablesub bid'.$item->id.'">'.$item->subjcode.'</td>
                                <td id="'.$item->teacherid.'">'.$item->lastname.' '.substr($item->firstname,0,1).'.</td>
                                <td id="'.$item->roomid.'">'.$item->roomname.'</td>
                                <td class="act"></td>';

                $dataString .= '</tr>';

            }

            return $dataString;
        }

        else{

            return '<tr><td class="text-center" colspan="5">No Sched for this day</td></tr>';
        }
                    
    }



    public static function storeblocksched($request){

        // return $request->all();

        $activesem = DB::table('semester')->where('isactive','1')->first();
        $activesy = DB::table('sy')->where('isactive','1')->first();

        $storesuccessfull = true;
        $data = array();

        $blockSched = DB::table('sh_blocksched')
                        ->join('sy',function($join){
                            $join->on('sh_blocksched.syid','=','sy.id');
                            $join->where('sy.isactive','1');
                        })
                        ->join('semester',function($join){
                            $join->on('sh_blocksched.semid','=','semester.id');
                            $join->where('semester.id','1');
                        })
                        ->where('blockid',$request->get('bid'))
                        ->where('sh_blocksched.subjid',$request->get('s'))
                        ->where('deleted','0')
                        ->get();

        if(count($blockSched)==0){

            $blockschedid = DB::table('sh_blocksched')->insertGetID([
                                'blockid'=>$request->get('bid'),
                                'teacherid'=>$request->get('tea'),
                                'syid'=>$activesy->id,
                                'semid'=>$activesem->id,
                                'deleted'=>0,
                                'subjid'=>$request->get('s'),
                                'levelid'=>null
                            ]);
            
            foreach($request->get('days') as $d){

                $time = explode(" - ", $request->get('t'));

                $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

                DB::table('sh_blockscheddetail')->insert([
                    'headerid'=>$blockschedid,
                    'stime'=>$stime,
                    'etime'=>$etime,
                    'roomid'=>$request->get('r'),
                    'day'=>$d
                ]);

            }
            
        }

        return back();

    }

    public static function updateblocksched($request){


        DB::table('sh_blocksched')
                ->where('id',$request->get('bid'))
                ->where('deleted','0')
                ->update([
                    'subjid'=>$request->get('sub'),
                    'teacherid'=>$request->get('tea')
                ]);


        $classsched = DB::table('sh_blockscheddetail')
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

                DB::table('sh_blockscheddetail')
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

                DB::table('sh_blockscheddetail')
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

                DB::table('sh_blockscheddetail')
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


    // public static function storeblocksched($request){

    //     $activesem = DB::table('semester')->where('isactive','1')->first();
    //     $activesy = DB::table('sy')->where('isactive','1')->first();
    //     $levelid = DB::table('sh_block')->where('id',$request->get('bid'))->first();

    //     foreach($request->get('d') as $item){

    //         $checkBlockSched = DB::table('sh_blocksched')
    //                             ->where('blockid',$request->get('bid'))
    //                             ->where('semid',$activesem->id)
    //                             ->where('syid',$activesy->id)
    //                             ->where('deleted','0')
    //                             ->where('subjid',$item['su'])
    //                             ->get();

    //         if(count($checkBlockSched)==0){
              
    //             $classschedid = DB::table('sh_blocksched')->insertGetID([
    //                 'blockid'=>$request->get('bid'),
    //                 'teacherid'=>$item['te'],
    //                 'syid'=>$activesy->id,
    //                 'semid'=>$activesem->id,
    //                 'deleted'=>0,
    //                 'subjid'=>$item['su'],
    //                 'levelid'=>$levelid ->id
    //             ]);

    //             foreach($request->get('days') as $d){

    //                 $time = explode(" - ", $item['ti']);

    //                 $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
    //                 $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

    //                 DB::table('sh_blockscheddetail')->insert([
    //                     'headerid'=>$classschedid,
    //                     'stime'=>$stime,
    //                     'etime'=>$etime,
    //                     'roomid'=>$item['ro'],
    //                     'day'=>$d
    //                 ]);

    //                 if($item['ty']=="old"){
    //                     $asdata = explode(" ", $item['as']);
    //                     DB::table('sh_blockscheddetail')
    //                         ->where('headerid',str_replace("bid","",$asdata[1]))
    //                         ->where('day',$d)
    //                         ->update(['deleted'=>'1']);
    //                 }
    //             }

    //         }

    //         else{
                
    //             DB::table('sh_blocksched')
    //                 ->where('id', $checkBlockSched[0]->id)
    //                 ->update([
    //                     'teacherid'=>$item['te']
    //                 ]);
                
    //             foreach($request->get('days') as $d){

    //                 $classschedd = DB::table('sh_blockscheddetail')
    //                         ->where('headerid',$checkBlockSched[0]->id)
    //                         ->where('day',$d)
    //                         ->where('deleted','0')
    //                         ->get();

    //                 if(count($classschedd)==0){
    //                     $time = explode(" - ", $item['ti']);

    //                     $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
    //                     $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
    
    
    //                     DB::table('sh_blockscheddetail')->insert([
    //                         'headerid'=>$checkBlockSched[0]->id,
    //                         'stime'=>$stime,
    //                         'etime'=>$etime,
    //                         'roomid'=>$item['ro'],
    //                         'day'=>$d
    //                     ]);
                        
    //                 }
    //                 else{

    //                     $time = explode(" - ", $item['ti']);

    //                     $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
    //                     $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

    //                     DB::table('sh_blockscheddetail')
    //                             ->where('id',$classschedd[0]->id)
    //                             ->update([
    //                                 'stime'=>$stime,
    //                                 'etime'=>$etime,
    //                             ]);
    //                 }
                    
                    
    //             }
    //         }

    //         if($item['ty']=="old"){

    //             $asdata = explode(" ", $item['as']);

    //             $countSched = DB::table('sh_blockscheddetail')
    //                 ->where('headerid',str_replace("bid","",$asdata[1]))
    //                 ->where('deleted','0')
    //                 ->get();
                
    //             if(count($countSched)==0){

    //                 DB::table('sh_blocksched')
    //                     ->where('id',str_replace("bid","",$asdata[1]))
    //                     ->update([
    //                         'deleted'=>'1'
    //                     ]);

    //             }
              
    //         }
    //     }
    // }

    public static function getAllBlockSched($blockid){

        return DB::table('sh_block')
                        ->join('sh_blocksched',function($join){
                            $join->on('sh_block.id','=','sh_blocksched.blockid');
                            $join->whereIn('sh_blocksched.syid',function($query){
                                $query->select('id')->from('sy')->where('sy.isactive','1');
                            });
                            $join->whereIn('sh_blocksched.semid',function($query){
                                $query->select('id')->from('semester')->where('semester.isactive','1');
                            });
                        })
                        ->join('sh_blockscheddetail',function($join){
                            $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                            $join->where('sh_blockscheddetail.deleted','0');
                        })
                        ->join('sh_subjects',function($join){
                            $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                            $join->where('sh_subjects.deleted','0');
                            $join->where('sh_subjects.isactive','1');
                        })
                        ->join('teacher',function($join){
                            $join->on('sh_blocksched.teacherid','=','teacher.id')
                            ->where('teacher.deleted','0')
                            ->where('teacher.isactive','1');
                        })
                        ->leftJoin('rooms','sh_blockscheddetail.roomid','=','rooms.id')
                        ->join('days','sh_blockscheddetail.day','=','days.id')
                        ->where('sh_block.id',$blockid)
                        ->where('sh_blocksched.deleted','0')
                        ->select(
                            'sh_blocksched.*',
                            'sh_blockscheddetail.roomid',
                            'sh_blockscheddetail.stime',
                            'sh_blockscheddetail.etime',
                            'teacher.firstname', 'teacher.lastname',
                            'rooms.roomname',
                            'days.description',
                            'sh_subjects.subjcode'
                        )
                        ->get();

    }
    public static function getblockSubjects($blockid){

        return DB::table('sh_block')
                    ->join('sh_blocksched',function($join){
                        $join->on('sh_block.id','=','sh_blocksched.blockid');
                        $join->whereIn('sh_blocksched.syid',function($query){
                            $query->select('id')->from('sy')->where('sy.isactive','1');
                        });
                        $join->whereIn('sh_blocksched.semid',function($query){
                            $query->select('id')->from('semester')->where('semester.isactive','1');
                        });
                    })
                    ->join('sh_subjects',function($join){
                        $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                        $join->where('sh_subjects.deleted','0');
                        $join->where('sh_subjects.isactive','1');
                    })
                    ->where('sh_block.id',$blockid)
                    ->where('sh_blocksched.deleted','0')
                    ->select('sh_subjects.*')
                    ->get();

    }

    public static function getTeacherBlockSubjects($teacherid){

        return DB::table('sh_blocksched')
                ->leftJoin('sh_subjects',function($join){
                    $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                    $join->where('sh_subjects.deleted','0');
                    $join->where('sh_subjects.isactive','1');
                })
                ->leftJoin('sy',function($join){
                    $join->on('sy.id','=','sh_blocksched.syid');
                    $join->where('sy.isactive','1');
                })
                ->leftJoin('semester',function($join){
                    $join->on('semester.id','=','sh_blocksched.semid');
                    $join->where('sy.isactive','1');
                })
                ->join('sh_block',function($join){
                    $join->on('sh_blocksched.blockid','=','sh_block.id');
                    $join->where('sh_block.deleted','0');
                })
                ->join('sections',function($join){
                    $join->on('sh_block.id','=','sections.blockid');
                    $join->where('sections.deleted','0');
                })
                ->join('gradelevel',function($join){
                    $join->on('sections.levelid','=','gradelevel.id');
                    $join->where('gradelevel.deleted','0');
                })
                ->select(
                    'sections.sectionname',
                    'gradelevel.levelname',
                    'sh_blocksched.subjid',
                    'sh_subjects.subjcode',
                    'sh_blocksched.teacherid',
                    'gradelevel.acadprogid',
                    'sections.id as sectionid')
                ->where('sh_blocksched.teacherid',$teacherid)
                ->where('sh_blocksched.deleted','0')
                ->get();
    }

    public static function getAllTeacherBlockSchedule($teacherid){

        return DB::table('sh_blocksched')
                ->join('sh_blockscheddetail',function($join){
                    $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                    $join->where('sh_blockscheddetail.deleted','0');
                })
                ->leftJoin('sh_subjects',function($join){
                    $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                    $join->where('sh_subjects.deleted','0');
                    $join->where('sh_subjects.isactive','1');
                })
                ->leftJoin('sy',function($join){
                    $join->on('sy.id','=','sh_blocksched.syid');
                    $join->where('sy.isactive','1');
                })
                ->leftJoin('semester',function($join){
                    $join->on('semester.id','=','sh_blocksched.semid');
                    $join->where('sy.isactive','1');
                })
                ->join('sh_block',function($join){
                    $join->on('sh_blocksched.blockid','=','sh_block.id');
                    $join->where('sh_block.deleted','0');
                })
                ->join('sections',function($join){
                    $join->on('sh_block.id','=','sections.blockid');
                    $join->where('sections.deleted','0');
                })
                ->join('gradelevel',function($join){
                    $join->on('sections.levelid','=','gradelevel.id');
                    $join->where('gradelevel.deleted','0');
                })
                ->leftJoin('rooms','sh_blockscheddetail.roomid','=','rooms.id')
                ->join('days','sh_blockscheddetail.day','=','days.id')
                ->select(
                    'sh_subjects.subjcode as subjdesc',
                    'sh_blocksched.subjid',
                    'sections.sectionname',
                    'rooms.roomname',
                    'sh_blockscheddetail.stime',
                    'sh_blockscheddetail.etime',
                    'sh_blockscheddetail.day',
                    'gradelevel.levelname',
                    'days.description'
                   )
                ->where('sh_blocksched.teacherid',$teacherid)
                ->where('sh_blocksched.deleted','0')
                ->get();


    }
}
