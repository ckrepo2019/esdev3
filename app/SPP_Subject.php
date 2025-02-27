<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Crypt;
use App\SPP_String;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SPP_Subject extends Model
{

    public static function getAllSubject(
        $skip = null,
        $take = null,
        $subjid = null,
        $subjdesc = null,
        $acadid = null,
        $type = null,
        $strand = null
    ){
        
        if($acadid != null){

            $acadid = Crypt::decrypt($acadid);

            if( $acadid == 5){

                $query = DB::table('sh_subjects')
                            ->leftJoin('sh_strand',function($join){
                                $join->on('sh_subjects.strandid','=','sh_strand.id');
                                $join->where('sh_strand.deleted','0');
                            });

            }

            else{

                $query = DB::table('subjects');

                $query->where('acadprogid',$acadid);

            }

            if( $acadid == 5){

                $query->select(
                    'sh_subjects.*',
                    'sh_strand.strandname',
                    'sh_strand.strandcode'
                );

            }
            
            if($subjdesc != null){

                if( $acadid == 5){


                }
                else{

                    $query->where(function($query) use($subjdesc){
                        $query->where('subjects.subjdesc','like',$subjdesc.'%');
                        $query->orWhere('subjects.subjcode','like',$subjdesc.'%');
                    });

                }
            }

            if($type!=null){

                $query->whereIn('type',$type);

            }

            if($strand!=null){

                $query->where(function($query)use($strand){
                    $query->where('strandid',null);
                    $query->orWhere('strandid','==','0');
                    $query->orWhere('strandid',$strand);
                });

            }

            if($subjid!=null){

                if($acadid == 5){

                    $query->where('sh_subjects.id',$subjid);
                }
                else{
    
                    $query->where('subjects.id',$subjid);
    
                }
            
            }

            if($acadid == 5){

                $query->where('sh_subjects.deleted','0');
            }
            else{

                $query->where('subjects.deleted','0');

            }
            

            $count = $query->count();


            if($take!=null){

                $query->take($take);
    
            }
    
            if($skip!=null){
    
                $query->skip(($skip-1)*$take);
            }

    

            $subject = $query->get();

            $data = array();

            array_push($data,(object)['data'=>$subject,'count'=>$count]);

            return $data;     

        }
        else{

            $senioHighSubjects = DB::table('sh_subjects');
            $juniorHightSubjects = DB::table('subjects');

        }

    }

    public static function insertSubject(
        $sn = null,
        $sc = null,
        $acadid = null,
        $type = null,
        $strand = null,
        $prereq = null
    ){

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $data   = [
            'sn'=> $sn,
            'sc'=>$sc,
            'type'=>$type,
            'strand'=>$strand,
            'prereq'=>$prereq
        ];

        if($acadid == 5){

            $message = [
                'sn.required'=>'Subject name is required.',
                'sc.required'=>'Subject code is required.',
                'sn.unique'=>'Subject already exists.'
            ];

        }
        else{

            $message = [
                'sn.required'=>'Subject name is required.',
                'sc.required'=>'Subject code is required.',
                'sn.unique'=>'Subject already exists.',
                'type.required'=>'Subject type is required.',
                'strand.required_if'=>'Strand is required.'
            ];
        }

        if($acadid == 5){

            $validator = Validator::make($data, [

                'sn' => ['required',Rule::unique('sh_subjects','subjtitle')->where(function($query){
                    return $query->where('deleted','0');
                })],
                'sc' => 'required',
                'type'=> 'required',
                'strand' => Rule::requiredIf( function() use($type){
                    return $type == '2';
                })
            
            ], $message);

        }
        else{

            $validator = Validator::make($data, [
                'sn' => ['required',Rule::unique('subjects','subjdesc')->where(function($query) use($acadid){
                    return $query->where('acadprogid',$acadid);
                })],
                'sc' => 'required',
            
            ], $message);

        }

        if($strand == null){

            $strand = null;

        }
        else{

            $strand = Crypt::decrypt($strand);
        }

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput();

        }

        else{

            if($acadid == 5){

                $subjid = DB::table('sh_subjects')
                        ->insertGetId([
                            'subjtitle'=>strtoupper($sn),
                            'type'=>$type,
                            'subjcode'=>$sc,
                            'acadprogid'=>$acadid,
                            'isactive'=>'1',
                            'strandid'=>$strand ,
                            'deleted'=>'0',
                            'createdby'=>auth()->user()->id,
                            'createddatetime'=>  $date 
                        ]);

               
                if($prereq!=null){
                    foreach($prereq as $item){

                        DB::table('sh_prerequisite')
                            ->insert([
                                'subjid'=>$subjid,
                                'prereqsubjid'=>$item
                            ]);
                    }
                }
               
                

                toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                
                return back();

            }
            else{

                DB::table('subjects')
                    ->insert([
                        'subjdesc'=>strtoupper($sn),
                        'subjcode'=>$sc,
                        'acadprogid'=>$acadid,
                        'isactive'=>'1',
                        'deleted'=>'0',
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>  $date 
                    ]);

                toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                
                return back();
            }

        }

    }

    public static function updateSubject(
        $sn = null,
        $sc = null,
        $acadid = null,
        $si = null,
        $type = null,
        $strand = null,
        $prereq = null
    ){

        $data   = [
            'sn'=> $sn,
            'sc'=>$sc,
            'type'=>$type,
            'strand'=>$strand,
            'prereq'=>$prereq
        ];


        if($acadid == 5){

            $message = [
                'sn.required'=>'Subject name is required.',
                'sc.required'=>'Subject code is required.',
                'sn.unique'=>'Subject already exists.'
            ];

            $validator = Validator::make($data, [

                'sn' => ['required',Rule::unique('sh_subjects','subjtitle')->where(function($query){
                    return $query->where('deleted','0');
                })->ignore($si,'id')],
                'sc' => 'required',
                'type'=> 'required',
                'strand' => Rule::requiredIf( function() use($type){
                    return $type == '2';
                })
            
            ], $message);

            if ($validator->fails()) {

                toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
                return back()->withErrors($validator)->withInput();

            }
            else{

                date_default_timezone_set('Asia/Manila');
                $date = date('Y-m-d H:i:s');

               
                DB::table('sh_subjects')
                        ->where('id',$si)
                        ->update([
                            'subjtitle'=>strtoupper($sn),
                            'type'=>$type,
                            'subjcode'=>$sc,
                            'acadprogid'=>$acadid,
                            'isactive'=>'1',
                            'strandid'=>$strand ,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>  $date 
                        ]);

                DB::table('sh_prerequisite')->where('subjid',$si)->update(['deleted'=>'1']);

                if($prereq!=null){

                    foreach($prereq as $item){

                        $prereqGet = self::getPreRequisite(null,null,$si,$item);

                        if($prereqGet[0]->count == 0){

                            DB::table('sh_prerequisite')->insert(['subjid'=>$si,'prereqsubjid'=>$item]);
                            
                        }
                        else{

                            DB::table('sh_prerequisite')
                                    ->where('subjid',$si)
                                    ->where('subjid',$item)
                                    ->update(['deleted'=>'1']);
                        }
                    }

                }

                toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                
                return back();

            }



        }
        else{


            $message = [
                'sn.required'=>'Subject name is required',
                'sc.required'=>'Subject code is required',
                'sn.unique'=>'Subject already exists.',
            ];

            $validator = Validator::make($data, [
                'sn' => ['required',Rule::unique('subjects','subjdesc')->where(function($query) use($acadid){
                    return $query->where('acadprogid',$acadid);
                })->ignore($si,'id')],
                'sc' => 'required',
            ], $message);

            
            if ($validator->fails()) {

                toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
                return back()->withErrors($validator)->withInput();

            }

            else{

                date_default_timezone_set('Asia/Manila');
                $date = date('Y-m-d H:i:s');

                DB::table('subjects')->where('id',$si)
                    ->update([
                        'subjdesc'=>$sn,
                        'subjcode'=>$sc,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>  $date 
                    ]);

                toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                
                return back();

            }
        }





    }



    // public static function viewSHSubjects(
    //     $skip = null, 
    //     $take = null, 
    //     $subjid = null,
    //     $subjectname = null){

    //     $returndata = array();

    //     $data = DB::table('sh_subjects')
    //                     ->leftJoin('sh_strand',function($join){
    //                         $join->on('sh_subjects.strandid','=','sh_strand.id');
    //                         $join->where('sh_strand.deleted','0');
    //                     });

    //     if($subjectname != null){

    //         $data->where(function($query) use($subjectname){
    //             $query->where('sh_subjects.subjtitle','like',$subjectname.'%');
    //             $query->orWhere('sh_subjects.subjcode','like',$subjectname.'%');
    //         });
    //     }

    //     if($subjid != null){
    //         $data->where('sh_subjects.id',$subjid);
    //     }

    //     $data->where('sh_subjects.deleted','0');

    //     $data->select('sh_subjects.*','sh_strand.strandcode');

    //     $count = $data->count();

    //     if($take!=null){
    //         $data->take($take);
    //     }
    //     if($skip!=null){
    //         $data->skip(($skip-1)*$take);
    //     }

    //     $data = $data->get();
        
    //     array_push($returndata,(object)['data'=>$data,'count'=>$count]);

    //     return $returndata; 

    // }

    // public static function storeSHSubject($request){

    //     $dataString = '';

    //     $dataString = '<ul class="text-danger">';
        
    //     $validInput = true;

    //     if($request->get('sn')==null){

    //         $dataString .= '<li>Subject name is required</li>';
    //         $validInput = false;

    //     }
    //     if($request->get('sc')==null){

    //         $dataString .= '<li>Subject code is required</li>';
    //         $validInput = false;

    //     }
    //     if($request->get('type')==null){

    //         $dataString .= '<li>Subject type is required</li>';
    //         $validInput = false;

    //     }

    //     if($request->get('type')==2){

    //         if($request->get('strand')==null){

    //             $dataString .= '<li>Strand is required</li>';
    //             $validInput = false;

    //         }

    //     }

    //     if($validInput){

    //         if($request->get('type')==1){

    //             $subjid = DB::table('sh_subjects')->insertGetId([
    //                 'subjtitle'=>$request->get('sn'),
    //                 'subjcode'=>$request->get('sc'),
    //                 'type'=>$request->get('type'),
    //                 'isactive'=>'1',
    //                 'deleted'=>'0',
    //                 'createdby'=>auth()->user()->id,
    //             ]);

    //         }

    //         else if($request->get('type')==2){

    //             if($request->has('prereq')){

    //                 $subjid = DB::table('sh_subjects')->insertGetId([
    //                     'subjtitle'=>$request->get('sn'),
    //                     'subjcode'=>$request->get('sc'),
    //                     'type'=>$request->get('type'),
    //                     'isactive'=>'1',
    //                     'deleted'=>'0',
    //                     'strandid'=>Crypt::decrypt($request->get('strand')),
    //                     'createdby'=>auth()->user()->id,
    //                 ]);
                    
    //                 foreach($request->get('prereq') as $item){

    //                     DB::table('sh_prerequisite')->insert([
    //                         'subjid'=> $subjid,
    //                         'prereqsubjid'=>$item
    //                     ]);
    //                 }
    //             }
        
    //             else{
        
    //                 $subjid = DB::table('sh_subjects')->insertGetId([
    //                     'subjtitle'=>$request->get('sn'),
    //                     'subjcode'=>$request->get('sc'),
    //                     'isactive'=>'1',
    //                     'type'=>$request->get('type'),
    //                     'strandid'=>Crypt::decrypt($request->get('strand')),
    //                     'acadprogid'=>'5',
    //                     'deleted'=>'0',
    //                     'createdby'=>auth()->user()->id,
    //                 ]);
    //             }
    //         }

    //         return $dataString='';

    //     }
    //     else{

    //         return $dataString;

    //     }

    //     return back();

    // }

    public static function getPreRequisite(
        $skip=null,
        $take=null,
        $subjid = null,
        $prereq = null
    ){

        $query =    DB::table('sh_prerequisite')
                    ->leftJoin('sh_subjects',function($join){
                        $join->on('sh_prerequisite.prereqsubjid','=','sh_subjects.id');
                    });


        if($subjid!=null){

            $query->where('sh_prerequisite.subjid',$subjid);

        }

        if($prereq!=null){

            $query->where('sh_prerequisite.prereqsubjid',$prereq);

        }

        $query->where('sh_prerequisite.deleted','0');

        $count = $query->count();
      

        if($take!=null){

            $query->take($take);

        }

        if($skip!=null){

            $query->skip(($skip-1)*$take);
        }

        $prereq = $query->get();

        $data = array();

        array_push($data,(object)['data'=>$prereq,'count'=>$count]);

        return $data;     
     
                 
    }
    

    public static function getAllSHSubject(){

        return DB::table('sh_subjects')->where('isactive','1')->where('deleted','0')->get();
        
    }

    public static function getAllCoreSubjects(){

        return DB::table('sh_subjects')
                    ->where('isactive','1')
                    ->where('type','1')
                    ->where('deleted','0')->get();

    }

    public static function getAllSpecializedSubjectsByStrand($strandId){

        return DB::table('sh_subjects')
                    ->where('isactive','1')
                    ->where('type','2')
                    ->where('strandid',$strandId)
                    ->where('deleted','0')->get();

    }


    //grade setup

    //subjects without grade setup by gradelevel for senior high school

    public static function getSubjectsWithoutGradeSetupByGradeLevelSHS($gradelevelid){

        return DB::table('sh_subjects')
                    ->leftJoin('gradessetup',function($join) use ($gradelevelid){
                        $join->on('sh_subjects.id','=','gradessetup.subjid');
                        $join->where('gradessetup.deleted','0');
                        $join->where('gradessetup.levelid',$gradelevelid);
                    })
                    ->where('gradessetup.id',null)
                    ->where('sh_subjects.deleted','0')
                    ->where('sh_subjects.isactive','1')
                    ->select('sh_subjects.*')
                    ->get();

    }
    
    //subjects without grade setup by gradelevel for junior high school

    public static function getSubjectsWithoutGradeSetupByGradeLevelJHS($gradelevelid){

        return DB::table('subjects')
                    ->leftJoin('gradessetup',function($join) use ($gradelevelid){
                        $join->on('subjects.id','=','gradessetup.subjid');
                        $join->where('gradessetup.deleted','0');
                        $join->where('gradessetup.levelid',$gradelevelid);
                    })
                    ->where('gradessetup.id',null)
                    ->where('subjects.deleted','0')
                    ->where('subjects.isactive','1')
                    ->select('subjects.*')
                    ->get();

    }

    public static function getSubjects($take=0, $skip=1, $string=''){

        if($take == 0){

            return DB::table('subjects')
                ->where('deleted','0')
                ->where('isactive','1')
                ->skip($take)
                ->where('subjects.subjdesc','like',$string.'%')
                ->orWhere('subjects.subjcode','like',$string.'%')
                ->get();
        }
        else{

            return DB::table('subjects')
                        ->where('deleted','0')
                        ->where('isactive','1')
                        ->where('subjects.subjdesc','like',$string.'%')
                        ->orWhere('subjects.subjcode','like',$string.'%')
                        ->take($take)
                        ->skip(($skip-1)*$take)
                        ->get();
        }
    }

    public static function getSubjectCount($string = ''){

            return DB::table('subjects')
                ->where('deleted','0')
                ->where('isactive','1')
                ->where('subjects.subjdesc','like',$string.'%')
                ->orWhere('subjects.subjcode','like',$string.'%')
                ->count();

    }


    public static function getSubjectPageCount($string = ''){

        $subjectCount = self::getSubjectCount($string);

        $subjectPageCount = $subjectCount/10;

        if(round($subjectPageCount) < $subjectCount){

            $subjectPageCount = round($subjectPageCount)+1;

        }
        else{

            $subjectPageCount = round($subjectPageCount);
        }

        return $subjectPageCount;

    }


    public static function searchSubject($take=0, $pagenum=1, $string=''){

        $subjectCount = SPP_Subject::getSubjectPageCount($string);
        $subjects = SPP_Subject::getSubjects($take,$pagenum,$string);

        $dataString = '';

        $dataString.='<table class="table ">
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Subject Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                        foreach ($subjects as $item){
                            $dataString.='<tr>
                                <td>'.$item->subjdesc.'</td>
                                <td>'.$item->subjcode.'</td>
                                <td>
                                    <button class="btn btn-xs btn-info edit" data-toggle="modal"  data-target="#modal-default" title="Contacts" data-widget="chat-pane-toggle" id="'.Crypt::encrypt($item->id).'">EDIT</button>
                                </td>
                            </tr>';
                        }
                        $dataString.='</tbody>
                    </table>';

                    $pagination = SPP_String::paginationString($subjectCount,'subjects',$pagenum);

                    $dataString.=$pagination;

        return $dataString;

    }

    public static function getSchedule(
        $day = null,
        $student = null,
        $section = null
    ){

        $block = null;
        $acadProg = null;
        $classscheds = null;
        $subjects = null;


     
        if($section!=null && $student == null){

            $acadProg = DB::table('sections')
                            ->join('gradelevel','sections.levelid','=','gradelevel.id')
                            ->where('sections.id',$section)
                            ->where('sections.deleted','0')
                            ->select('acadprogid','sections.blockid')
                            ->first();

            $block = $acadProg->blockid;
            
        }

        if($student!=null){

            $acadProg = DB::table('studinfo')
                            ->where('studinfo.id',$student)
                            ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                            ->select('gradelevel.acadprogid','studinfo.sectionid','blockid')->first();

            $block = $acadProg->blockid;
            $section = $acadProg->sectionid;

        }

        

        

        if($acadProg->acadprogid==5){

            $classscheds= DB::table('sh_classsched')
                                ->join('sy',function($join){
                                    $join->on('sh_classsched.syid','=','sy.id');
                                    $join->where('sy.isactive','1');
                                })
                                ->join('sh_subjects',function($join){
                                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                                })->leftJoin('sh_classscheddetail',function($join){
                                    $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                                    $join->where('sh_classscheddetail.deleted','0');
                                })
                                ->join('teacher',function($join){
                                    $join->on('sh_classsched.teacherid','=','teacher.id')
                                    ->where('teacher.deleted','0')
                                    ->where('teacher.isactive','1');
                                })
                                ->join('days','sh_classscheddetail.day','=','days.id')
                                ->join('rooms',function($join){
                                    $join->on('sh_classscheddetail.roomid','=','rooms.id');
                                    $join->where('rooms.deleted','0');
                                })
                                
                                ->where('sh_classsched.deleted','0')
                                ->where('sh_classsched.sectionid', $section);

        }

        else{

           

            $classscheds = DB::table('assignsubj')
                            ->join('assignsubjdetail',function($join){
                                $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                                $join->where('assignsubjdetail.deleted','0');
                            })
                            ->join('classsched',function($join) use($section){
                                $join->on('classsched.subjid','=','assignsubjdetail.subjid');
                                $join->where('classsched.deleted','0');
                                $join->where('classsched.sectionid',$section);
                                $join->whereIn('classsched.syid',function($query){
                                        $query->select('id')->from('sy')->where('sy.isactive','1');
                                    });
                            })
                            ->join('classscheddetail',function($join) {
                                    $join->on('classsched.id','=','classscheddetail.headerid');
                                    $join->where('classscheddetail.deleted','0');
                            })
                            ->join('subjects',function($join){
                                $join->on('assignsubjdetail.subjid','=','subjects.id');
                                $join->where('subjects.deleted','0');
                                $join->where('subjects.isactive','1');
                            }) 
                            ->join('teacher',function($join){
                                $join->on('assignsubjdetail.teacherid','=','teacher.id')
                                ->where('teacher.deleted','0')
                                ->where('teacher.isactive','1');
                            })
                            ->join('days','classscheddetail.days','=','days.id')
                            ->join('rooms',function($join){
                                $join->on('classscheddetail.roomid','=','rooms.id');
                                $join->where('rooms.deleted','0');
                            })
                            ->join('sy',function($join){
                                $join->on('sy.id','=','assignsubj.syid');
                                $join->where('sy.isactive','1');
                            })
                            ->where('assignsubj.deleted','0')
                            ->where('assignsubj.sectionid',$section);
         
            
        }


        if( $day!=null){

            if($acadProg->acadprogid == 5){

                $classscheds->where('day',$day);

            }
            else{

                $classscheds->where('days',$day);

            }
        }

        if($acadProg->acadprogid == 5){

            $classscheds->select(
                'sh_subjects.subjcode',
                'sh_subjects.subjtitle as subjdesc'
            );

            $classscheds->addSelect(
                'sh_classsched.subjid',
                'sh_classsched.teacherid',
                'sh_classscheddetail.etime',
                'sh_classscheddetail.roomid',
                'sh_classscheddetail.day as days',
                'sh_classscheddetail.stime',
                'days.description',
                'rooms.roomname',
                'sh_classscheddetail.id as detailid',
                'sh_classscheddetail.headerid',
                'sh_classsched.teacherid',
                'sh_subjects.type'
            );

        }

        else{

            $classscheds->select(
                'subjects.subjcode',
                'subjects.subjdesc'
            );

            $classscheds->addSelect(
                'classsched.subjid',
                'classscheddetail.days',
                'classscheddetail.stime',
                'classscheddetail.etime',
                'classscheddetail.roomid',
                'classscheddetail.headerid',
                'assignsubjdetail.teacherid',
                'classscheddetail.id as detailid',
                'days.description',
                'classsched.sectionid',
                'rooms.roomname'
            );
        }

        $classscheds->addSelect(

            'teacher.firstname','teacher.lastname'
            
        );

        if($acadProg->acadprogid == 5){

            $classscheds = $classscheds
                    ->distinct()
                    ->get();

        }

        else{

            $classscheds = $classscheds
                ->distinct()
                ->get();
        }

      

        foreach($classscheds as $item){

            $item->time = $item->stime.'-'.$item->etime;

        }
       

        if($block!=null){

            $query =  DB::table('sh_blocksched')
                            ->join('sy',function($join){
                                $join->on('sy.id','=','sh_blocksched.syid');
                                $join->where('sy.isactive','1');
                            })
                            ->join('semester',function($join){
                                $join->on('semester.id','=','sh_blocksched.semid');
                                $join->where('semester.isactive','1');
                            })
                            ->join('sh_subjects',function($join){
                                $join->on('sh_blocksched.subjid','=','sh_subjects.id')
                                ->where('sh_subjects.isactive','1')
                                ->where('sh_subjects.deleted','0');
                            });

                
            $blockScheds =  $query->join('teacher',function($join){
                                $join->on('sh_blocksched.teacherid','=','teacher.id')
                                ->where('teacher.deleted','0')
                                ->where('teacher.isactive','1');
                            })
                            ->Leftjoin('sh_blockscheddetail',function($join){
                                $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                                $join->where('sh_blockscheddetail.deleted','0');
                            })
                            ->leftJoin('days','sh_blockscheddetail.day','=','days.id')
                            ->leftJoin('rooms',function($join){
                                $join->on('sh_blockscheddetail.roomid','=','rooms.id');
                                $join->where('rooms.deleted','0');
                            })
                            ->select(
                                'sh_blocksched.subjid',
                                'sh_blocksched.teacherid',
                                'sh_blockscheddetail.etime',
                                'sh_blockscheddetail.day as days',
                                'sh_blockscheddetail.stime',
                                'sh_blockscheddetail.headerid',
                                'days.description',
                                'rooms.roomname',
                                'sh_blockscheddetail.roomid',
                                'sh_subjects.subjcode',
                                'sh_subjects.type',
                                'sh_subjects.subjtitle as subjdesc',
                                'sh_blockscheddetail.id as detailid',
                                'teacher.firstname','teacher.lastname'
                            )
                            ->where('blockid',$block)
                            ->where('sh_blocksched.deleted','0');

            
            if( $day!=null){

                $blockScheds->where('sh_blockscheddetail.day',$day)
                            ->distinct();
                            
            }
   
            
            $blockScheds =  $blockScheds->get();

            $subjects = $query->select('sh_subjects.*')->distinct()->get();

            foreach($blockScheds as  $blockSched){

                $blockSched->time = $blockSched->stime.'-'.$blockSched->etime;

                $classscheds->push($blockSched);

            }
        }

        $data = array();

        return collect($classscheds);

    }

    public static function getSubject(
        $take = null,
        $skip = null,
        $student = null,
        $section = null,
        $blockid = null,
        $acadid = null,
        $day=null
    ){
        $count = 0;
       

        if($section!=null && $student == null){
    
            $acadProg = DB::table('sections')
                            ->join('gradelevel','sections.levelid','=','gradelevel.id')
                            ->where('sections.id',$section)
                            ->where('sections.deleted','0')
                            ->select('acadprogid','blockid')
                            ->first();

            $blockid = $acadProg->blockid;
        }

        if($student!=null){

            $acadProg = DB::table('studinfo')
                            ->where('studinfo.id',$student)
                            ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
                            ->select('gradelevel.acadprogid','studinfo.sectionid','blockid')->first();

            $blockid = $acadProg->blockid;

        }

        if($section!=null){
        
            $acadProg = DB::table('sections')
                            ->join('gradelevel','sections.levelid','=','gradelevel.id')
                            ->where('sections.id',$section)
                            ->where('sections.deleted','0')
                            ->select('acadprogid','blockid')
                            ->first();
        }
        else{

            $acadProg = (object)['acadprogid'=>1];

        }

        
        if($acadProg->acadprogid == 5 ){

            $query = DB::table('sh_classsched')
                                ->join('sy',function($join){
                                    $join->on('sh_classsched.syid','=','sy.id');
                                    $join->where('sy.isactive','1');
                                })
                                ->leftJoin('sh_subjects',function($join){
                                    $join->on('sh_classsched.subjid','=','sh_subjects.id');
                                })
                                ->where('sh_classsched.deleted','0');

            if($section!=null){

                $query->where('sh_classsched.sectionid',$section);

            }

            $query = $query->select(
                'sh_subjects.id',
                'sh_subjects.subjtitle as subjdesc',
                'sh_subjects.subjcode',
                'sh_subjects.type');

        }

        else{

            $query = DB::table('assignsubj')
                            ->join('sy',function($join){
                                $join->on('sy.id','=','assignsubj.syid');
                                $join->where('sy.isactive','1');
                            })
                            ->leftJoin('assignsubjdetail',function($join){
                                $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                                $join->where('assignsubjdetail.deleted','0');
                            })
                            ->leftJoin('subjects',function($join){
                                $join->on('assignsubjdetail.subjid','=','subjects.id');
                                $join->where('subjects.deleted','0');
                                $join->where('subjects.isactive','1');
                            })
                            ->where('assignsubj.deleted','0');

            if($section!=null){

                $query->where('assignsubj.sectionid',$section);

            }

            $query = $query->select('subjects.id','subjects.subjdesc','subjects.subjcode');

        }

        $count = $query->distinct()->count();

        $subjects = $query->distinct()->get();

        if($blockid!=null){

            $query =  DB::table('sh_blocksched')
                            ->join('sy',function($join){
                                $join->on('sy.id','=','sh_blocksched.syid');
                                $join->where('sy.isactive','1');
                            })
                            ->join('semester',function($join){
                                $join->on('semester.id','=','sh_blocksched.semid');
                                $join->where('semester.isactive','1');
                            })
                            ->join('sh_subjects',function($join){
                                $join->on('sh_blocksched.subjid','=','sh_subjects.id')
                                ->where('sh_subjects.isactive','1')
                                ->where('sh_subjects.deleted','0');
                            })
                            ->where('sh_blocksched.deleted','0');

            if($section!=null){

                $query->where('sh_blocksched.blockid',$blockid);

            }

            $query->select('sh_subjects.id','sh_subjects.subjtitle','sh_subjects.subjtitle as subjdesc','sh_subjects.subjcode','sh_subjects.type');

            $countBlockSubject = $query->distinct()->count();

            $count += $countBlockSubject;

            $blocksubjects = $query->distinct()->get();

            foreach($blocksubjects as  $blocksubject){

                $subjects->push($blocksubject);

            }
        
        }

        $data = array();

        array_push($data, (object)['data'=>$subjects,'count'=>$count]);

        return $data;
        
    }

}
