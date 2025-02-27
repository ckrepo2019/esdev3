<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;

class SPP_Blocks extends Model
{

    public static function getBlock(
        $skip = null,
        $take = null,
        $blockid = null,
        $blockinfo = null
    ){

        $query  = DB::table('sh_block')
                    ->leftJoin('sh_strand',function($join){
                        $join->on('sh_block.strandid','=','sh_strand.id');
                        $join->where('sh_strand.deleted','0');
                        $join->where('sh_strand.active','1');
                    })
                    ->leftJoin('users as cb','sh_block.createdby','=','cb.id')
                    ->leftJoin('users as ub','sh_block.updatedby','=','ub.id')
                    ->leftJoin('gradelevel',function($join){
                        $join->on('sh_block.levelid','=','gradelevel.id');
                    });

        if($blockid != null){

            $query->where('sh_block.id',$blockid);

        }

        if($blockinfo != null){
            
            $query->where(function($query) use($blockinfo){
                $query->where('sh_block.blockname','like','%'.$blockinfo.'%');
                $query->orWhere('sh_strand.strandname','like','%'.$blockinfo.'%');
              
            });

        }

        $query->select('sh_block.*','sh_strand.strandname','gradelevel.levelname','cb.name as cbname', 'ub.name as ubname' );

        $query->where('sh_block.deleted','0');

        $count = $query->count();


        if($take!=null){

            $query->take($take);

        }

        if($skip!=null){

            $query->skip(($skip-1)*$take);
        }


        $blocks = $query->get();

        $data = array();

        array_push($data,(object)['data'=>$blocks,'count'=>$count]);

        return $data;

    }

    public static function updateblock(
        $blockid = null,
        $blockname = null,
        $strand = null,
        $gradelevel = null
    ){

        $data = ['bn'=>$blockname,'si'=> $strand,'gradelevel'=>$gradelevel];

        $message = [
            'bn.required'=>'Block name is required.',
            'si.required'=>'Strand is required.',
            'si.unique'=>'Block already exists.',
            'gradelevel.required'=>'Grade Level is required'
        ];

        $validator = Validator::make($data, [
                    'bn' => ['required', Rule::unique('sh_block','blockname')->where(function($query){
                        return $query->where('deleted','0');
                    })->ignore($blockid,'id')],
                    'si' => 'required',
                    'gradelevel'=>'required'
        ], $message);


        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput();

        }
        else{

            date_default_timezone_set('Asia/Manila');
            $date = date('Y-m-d H:i:s');

            DB::table('sh_block')
                    ->where('id',$blockid)
                    ->update([
                        'blockname'=>$blockname,
                        'strandid'=> $strand,
                        'levelid'=> $gradelevel,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime'=>$date
                    ]);

            toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
            return back();

        }

    }






    public static function loadAllBlocks($id){
    
        $blocks = DB::table('sh_sectionblockassignment')
                ->join('sh_block',function($join){
                    $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                    $join->where('sh_block.deleted','0');
                });

        if(Session::has('schoolYear')){

            $blocks->join('sy',function($join) {
                                $join->on('sy.id','=','sh_sectionblockassignment.syid');
                                $join->where('sy.id',Session::get('schoolYear')->id);
                            });
            
        }
        else{
            
            $blocks->join('sy',function($join){
                    $join->on('sy.id','=','sh_sectionblockassignment.syid');
                    $join->where('sy.isactive','1');
                });

        }
                
        $blocks = $blocks->where('sh_sectionblockassignment.sectionid',$id)
                ->where('sh_sectionblockassignment.deleted','0')
                ->select('sh_block.*')
                ->get();

        return  $blocks;
    
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
        if($request->get('gradelevel')==null){
            $inputsareValid = false;
            $returnBack->with('gradelevel', (object) ['message'=>'Grade Level is required']);
        }

        if($inputsareValid){

            DB::table('sh_block')->insert([
                'blockname'=>$request->get('bn'),
                'strandid'=>$request->get('si'),
                'levelid'=>$request->get('gradelevel'),
                'createdby'=>auth()->user()->id,
                'createddatetime'=>Carbon::now('Asia/Manila')
            ]);

            toast('Block successfully created','success')->autoClose(2000)->toToast($position = 'top-right')->hideCloseButton();

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
                            $join->where('semester.isactive','1');
                        })
                        ->where('blockid',$request->get('bid'))
                        ->where('sh_blocksched.subjid',$request->get('s'))
                        ->where('sh_blocksched.deleted','0')
                        ->select('sh_blocksched.id')
                        ->get();

        

        if(count($blockSched)==0){

            $blockschedid = DB::table('sh_blocksched')->insertGetID([
                                'blockid'=>$request->get('bid'),
                                'teacherid'=>$request->get('tea'),
                                'syid'=>$activesy->id,
                                'semid'=>$activesem->id,
                                'deleted'=>0,
                                'subjid'=>$request->get('s'),
                                'levelid'=>null,
                            ]);
            
            foreach($request->get('days') as $d){

                $time = explode(" - ", $request->get('t'));

                $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

                DB::table('sh_blockscheddetail')->updateOrInsert (
                    [
                        'headerid'=>$blockschedid,
                        'stime'=>$stime,
                        'etime'=>$etime,
                        'roomid'=>$request->get('r'),
                        'day'=>$d,
                        'classification'=>$request->get('class')
                    ]
                );

            }
            
        }
        else{

            foreach($request->get('days') as $d){

                $time = explode(" - ", $request->get('t'));

                $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

                DB::table('sh_blockscheddetail')->updateOrInsert(
                    [
                        'headerid'=> $blockSched[0]->id,
                        'day'=>$d,'deleted'=>'0',
                        'classification'=>$request->get('class')
                    ],
                    ['stime'=>$stime, 'etime'=>$etime, 'roomid'=>$request->get('r')]
                    
                );

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

    public static function getAllBlockSched($blockid){


        $activeSem = DB::table('semester')->where('isactive',1)->first();
        $activesy= DB::table('sy')->where('isactive',1)->first();
       
        if(Session::has('semester')){
                $activeSem = Session::get('semester');
        }
        if(Session::has('schoolYear')){
                $activesy = Session::get('schoolYear');
        }


        return DB::table('sh_block')
                        ->join('sh_blocksched',function($join){
                            $join->on('sh_block.id','=','sh_blocksched.blockid');
                            // $join->whereIn('sh_blocksched.syid',function($query){
                            //     $query->select('id')->from('sy')->where('sy.isactive','1');
                            // });
                            // $join->whereIn('sh_blocksched.semid',function($query){
                            //     $query->select('id')->from('semester')->where('semester.isactive','1');
                            // });
                        })
                        ->join('sh_blockscheddetail',function($join){
                            $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                            $join->where('sh_blockscheddetail.deleted','0');
                        })
                        ->join('schedclassification',function($join){
                            $join->on('sh_blockscheddetail.classification','=','schedclassification.id');
                            $join->where('schedclassification.deleted','0');
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
                        ->where('sh_blocksched.syid',$activesy->id)
                        ->where('sh_blocksched.semid',$activeSem->id)
                        ->where('sh_blocksched.deleted','0')
                        ->select(
                            'sh_blocksched.*',
                            'sh_blockscheddetail.roomid',
                            'sh_blockscheddetail.stime',
                            'sh_blockscheddetail.etime',
                            'sh_blockscheddetail.id as detailid',
                            'teacher.firstname', 'teacher.lastname',
                            'rooms.roomname',
                            'days.description',
                            'sh_subjects.subjcode',
                            'sh_subjects.subjtitle as subjdesc',
                            'schedclassification.description as schedclass'
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


    public static function storeblockschedv2($request){

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

        

        $storesuccessfull = true;
        $data = array();

        $blockSched = DB::table('sh_blocksched')
                        ->where('syid',$activesy->id)
                        ->where('semid',$activesem->id)
                        ->where('blockid',$request->get('bid'))
                        ->where('sh_blocksched.subjid',$request->get('s'))
                        ->where('sh_blocksched.deleted','0')
                        ->select('sh_blocksched.id')
                        ->get();

        if(count($blockSched)==0){

            $blockschedid = DB::table('sh_blocksched')->insertGetID([
                                'blockid'=>$request->get('bid'),
                                'teacherid'=>$request->get('tea'),
                                'syid'=>$activesy->id,
                                'semid'=>$activesem->id,
                                'deleted'=>0,
                                'subjid'=>$request->get('s'),
                                'levelid'=>null,
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
                        'day'=>$d,
                        'classification'=>$request->get('class')
                    ]);

            }
            
        }
        else{

            foreach($request->get('days') as $d){

                $time = explode(" - ", $request->get('t'));

                $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

                DB::table('sh_blockscheddetail')->insert([
                    'headerid'=> $blockSched[0]->id,
                    'stime'=>$stime,
                    'etime'=>$etime,
                    'roomid'=>$request->get('r'),
                    'day'=>$d,
                    'classification'=>$request->get('class')
                ]);

            }

        }

    }
   


}


// DB::table('sh_block')
//                     ->leftJoin('sh_strand',function($join){
//                         $join->on('sh_block.strandid','=','sh_strand.id');
//                         $join->where('sh_strand.deleted','0');
//                         $join->where('sh_strand.active','1');
//                     })
//                     ->leftJoin('users as cb','sh_block.createdby','=','cb.id')
//                     ->leftJoin('users as ub','sh_block.updatedby','=','ub.id')
//                     ->select('sh_block.*','sh_strand.strandname',
//                     'cb.name as cbname', 
//                     'ub.name as ubname')
//                     ->where('sh_block.deleted','0');