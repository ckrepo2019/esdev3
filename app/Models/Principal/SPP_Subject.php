<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use Crypt;
use App\Models\Principal\SPP_String;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use \Carbon\Carbon;

class SPP_Subject extends Model
{

    public static function getSubjectUsage(
        $subjects = null,
        $subjectid = null,
        $acadid = null
    ){


        if($subjects != null){

            foreach($subjects as $item){

                $usage = false;

                if($acadid != 5){

                    $assignsubjdetail = DB::table('assignsubjdetail')->where('subjid',$item->id)->where('deleted','0')->first();
                    $classsched = DB::table('classsched')->where('subjid',$item->id)->where('deleted','0')->first();
                    $gradesetup = DB::table('gradessetup')->where('subjid',$item->id)->whereNotIn ('levelid',['11','12'])->first();


                    if(isset($assignsubjdetail) || isset($classsched) || isset($gradesetup) ){
                        $usage = true;
                    }

                    $item->usage = $usage;

                }

                else{

                    $sh_classsched = DB::table('sh_classsched')->where('subjid',$item->id)->where('deleted','0')->first();
                    $sh_blocksched = DB::table('sh_blocksched')->where('subjid',$item->id)->where('deleted','0')->first();
                    $gradesetup = DB::table('gradessetup')->where('subjid',$item->id)->whereIn('levelid',['11','12'])->first();

                    if(isset($sh_classsched) || isset($sh_blocksched) || isset($gradesetup)){

                        $usage = true;

                    }

                    $item->usage = $usage;
            

                }
            }

            return $subjects;

        }

        if($subjectid != null){

            $usage = false;

            if($acadid != 5){

                $assignsubjdetail = DB::table('assignsubjdetail')->where('subjid',$subjectid)->where('deleted','0')->first();
                $classsched = DB::table('classsched')->where('subjid',$subjectid)->where('deleted','0')->first();

                if(isset($assignsubjdetail) || isset($classsched)){

                    $usage = true;

                }

            }

            else{

                $sh_classsched = DB::table('sh_classsched')->where('subjid',$subjectid)->where('deleted','0')->first();
                $sh_blocksched = DB::table('sh_blocksched')->where('subjid',$subjectid)->where('deleted','0')->first();

                if(isset($sh_classsched) || isset($sh_blocksched)){

                    $usage = true;

                }

            }
            
            return $usage;

        }
    }


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
                            ->leftJoin('semester',function($join){
                                $join->on('sh_subjects.semid','=','semester.id');
                            })
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
                    'sh_strand.strandcode',
                    'semester.semester'
                    // 'gradelevel.levelname'
                );

            }
            
            if($subjdesc != null){

                if( $acadid == 5){

                    $query->where(function($query) use($subjdesc){
                        $query->where('sh_subjects.subjtitle','like','%'.$subjdesc.'%');
                        $query->orWhere('sh_subjects.subjcode','like','%'.$subjdesc.'%');
                    });

                }
                else{

                    $query->where(function($query) use($subjdesc){
                        $query->where('subjects.subjdesc','like','%'.$subjdesc.'%');
                        $query->orWhere('subjects.subjcode','like','%'.$subjdesc.'%');
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
        $prereq = null,
        $sf9 = 0,
        $mapeh = 0,
        $semester = null,
        $track = 1
    ){

        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');


        // if($acadid == 5){

            
        //     $data   = [
        //         'sn'=> $sn,
        //         'sc'=>$sc,
        //         'type'=>$type,
        //         'strand'=>$strand,
        //         'prereq'=>$prereq
        //     ];

        //     $message = [
        //         'sn.required'=>'Subject name is required.',
        //         'sc.required'=>'Subject code is required.',
        //         'sn.unique'=>'Subject already exists.'
        //     ];

        // }
        // else{

            
        //     $data   = [
        //         'sn'=> $sn,
        //         'sc'=>$sc,
        //         'type'=>$type,
        //         'strand'=>$strand,
        //         'prereq'=>$prereq
        //     ];

        //     $message = [
        //         'sn.required'=>'Subject name is required.',
        //         'sc.required'=>'Subject code is required.',
        //         'sn.unique'=>'Subject already exists.',
        //         'type.required'=>'Subject type is required.',
        //         'strand.required_if'=>'Strand is required.'
        //     ];
        // }

        // if($acadid == 5){

        //     $validator = Validator::make($data, [

        //         'sn' => ['required',Rule::unique('sh_subjects','subjtitle')->where(function($query){
        //             return $query->where('deleted','0');
        //         })],
        //         'sc' => 'required',
        //         'type'=> 'required',
        //         'strand' => Rule::requiredIf( function() use($type){
        //             return $type == '2';
        //         })
            
        //     ], $message);

        // }
        // else{

        //     $validator = Validator::make($data, [
        //         'sn' => ['required',Rule::unique('subjects','subjdesc')->where(function($query) use($acadid){
        //             return $query->where('acadprogid',$acadid);
        //         })->where('deleted','0')],
        //         'sc' => 'required',
            
        //     ], $message);

        // }

        if($strand == null){

            $strand = null;

        }
        else{

            $strand = Crypt::decrypt($strand);
        }

        // if ($validator->fails()) {

        //     toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
        //     return back()->withErrors($validator)->withInput();

        // }

        // else{

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
                            'createddatetime'=>  Carbon::now('Asia/Manila'),
                            'inSF9'=>$sf9,
                            'inMAPEH' => $mapeh,
                            'semid' => $semester,
                            'subjtrackid' => $track,
                        ]);

               
                if($prereq!=null){
                    foreach($prereq as $item){

                        DB::table('sh_prerequisite')
                            ->insert([
                                'subjid'=>$subjid,
                                'prereqsubjid'=>$item,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>  Carbon::now('Asia/Manila') 
                            ]);
                    }
                }
               
                

                toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                
                return $subjid;

            }
            else{

                $subjid = DB::table('subjects')
                    ->insertGetId([
                        'subjdesc'=>strtoupper($sn),
                        'subjcode'=>$sc,
                        'acadprogid'=>$acadid,
                        'isactive'=>'1',
                        'deleted'=>'0',
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>  Carbon::now('Asia/Manila'),
                        'inSF9'=>$sf9,
                        'inMAPEH' => $mapeh,
                    ]);

                toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                
                return $subjid;
            }

        // }

    }

    public static function updateSubject(
        $sn = null,
        $sc = null,
        $acadid = null,
        $si = null,
        $type = null,
        $strand = null,
        $prereq = null,
        $sf9 = 0,
        $mapeh = 0,
        $semester = null,
        $track = 1
    ){


        // $data   = [
        //     'sn'=> $sn,
        //     'sc'=>$sc,
        //     'type'=>$type,
        //     'strand'=>$strand,
        //     'prereq'=>$prereq
        // ];


        if($acadid == 5){

            // $message = [
            //     'sn.required'=>'Subject name is required.',
            //     'sc.required'=>'Subject code is required.',
            //     'sn.unique'=>'Subject already exists.'
            // ];

            // $validator = Validator::make($data, [

            //     'sn' => ['required',Rule::unique('sh_subjects','subjtitle')->where(function($query){
            //                 return  $query->where('deleted','0');
              
            //     })->ignore($si,'id')],
            //     'sc' => 'required',
            //     'type'=> 'required',
            //     'strand' => Rule::requiredIf( function() use($type){
            //         return $type == '2';
            //     })
            
            // ], $message);

            // if ($validator->fails()) {

            //     toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            //     return back()->withErrors($validator)->withInput();

            // }
            // else{

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
                            'updateddatetime'=>  Carbon::now('Asia/Manila'),
                            'inSF9'=>$sf9,
                            'inMAPEH' => $mapeh,
                            'semid' => $semester,
                            'subjtrackid' => $track,
                        ]);

                DB::table('sh_prerequisite')->where('subjid',$si)->update(['deleted'=>'1']);

                if($prereq!=null){

                    foreach($prereq as $item){

                        $prereqGet = self::getPreRequisite(null,null,$si,$item);

                        if($prereqGet[0]->count == 0){

                            DB::table('sh_prerequisite')->insert([
                                'subjid'=>$si,
                                'prereqsubjid'=>$item,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>  Carbon::now('Asia/Manila') 
                            ]);
                            
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

                return 1;

            // }



        }
        else{

            // $message = [
            //     'sn.required'=>'Subject name is required',
            //     'sc.required'=>'Subject code is required',
            //     'sn.unique'=>'Subject already exists.',
            // ];

            // $validator = Validator::make($data, [
            //     'sn' => ['required',Rule::unique('subjects','subjdesc')->where(function($query) use($acadid){
            //         return $query->where('acadprogid',$acadid);
            //     })->ignore($si,'id')],
            //     'sc' => 'required',
            // ], $message);

            
            // if ($validator->fails()) {

            //     toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            //     return back()->withErrors($validator)->withInput();

            // }

            // else{

                date_default_timezone_set('Asia/Manila');
                $date = date('Y-m-d H:i:s');

                DB::table('subjects')->where('id',$si)
                    ->update([
                        'subjdesc'=>$sn,
                        'subjcode'=>$sc,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>  Carbon::now('Asia/Manila'),
                        'inSF9'=>$sf9,
                        'inMAPEH' => $mapeh
                    ]);

                toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                return 1;

            // }
        }





    }

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

    // public static function getSubjects($take=0, $skip=1, $string=''){

    //     if($take == 0){

    //         return DB::table('subjects')
    //             ->where('deleted','0')
    //             ->where('isactive','1')
    //             ->skip($take)
    //             ->where('subjects.subjdesc','like',$string.'%')
    //             ->orWhere('subjects.subjcode','like',$string.'%')
    //             ->get();
    //     }
    //     else{

    //         return DB::table('subjects')
    //                     ->where('deleted','0')
    //                     ->where('isactive','1')
    //                     ->where('subjects.subjdesc','like',$string.'%')
    //                     ->orWhere('subjects.subjcode','like',$string.'%')
    //                     ->take($take)
    //                     ->skip(($skip-1)*$take)
    //                     ->get();
    //     }
    // }

    // public static function getSubjectCount($string = ''){

    //         return DB::table('subjects')
    //             ->where('deleted','0')
    //             ->where('isactive','1')
    //             ->where('subjects.subjdesc','like',$string.'%')
    //             ->orWhere('subjects.subjcode','like',$string.'%')
    //             ->count();

    // }


    // public static function getSubjectPageCount($string = ''){

    //     $subjectCount = self::getSubjectCount($string);

    //     $subjectPageCount = $subjectCount/10;

    //     if(round($subjectPageCount) < $subjectCount){

    //         $subjectPageCount = round($subjectPageCount)+1;

    //     }
    //     else{

    //         $subjectPageCount = round($subjectPageCount);
    //     }

    //     return $subjectPageCount;

    // }


    // public static function searchSubject($take=0, $pagenum=1, $string=''){

    //     $subjectCount = SPP_Subject::getSubjectPageCount($string);
    //     $subjects = SPP_Subject::getSubjects($take,$pagenum,$string);

    //     $dataString = '';

    //     $dataString.='<table class="table ">
    //                     <thead>
    //                         <tr>
    //                             <th>Subject Name</th>
    //                             <th>Subject Code</th>
    //                             <th>Action</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>';
    //                     foreach ($subjects as $item){
    //                         $dataString.='<tr>
    //                             <td>'.$item->subjdesc.'</td>
    //                             <td>'.$item->subjcode.'</td>
    //                             <td>
    //                                 <button class="btn btn-xs btn-info edit" data-toggle="modal"  data-target="#modal-default" title="Contacts" data-widget="chat-pane-toggle" id="'.Crypt::encrypt($item->id).'">EDIT</button>
    //                             </td>
    //                         </tr>';
    //                     }
    //                     $dataString.='</tbody>
    //                 </table>';

    //                 $pagination = SPP_String::paginationString($subjectCount,'subjects',$pagenum);

    //                 $dataString.=$pagination;

    //     return $dataString;

    // }

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

            $classscheds= DB::table('sh_classsched');

            $classscheds->join('sh_subjects',function($join){
                                $join->on('sh_classsched.subjid','=','sh_subjects.id');
                            })
                            ->leftJoin('sh_classscheddetail',function($join){
                                $join->on('sh_classsched.id','=','sh_classscheddetail.headerid');
                                $join->where('sh_classscheddetail.deleted','0');
                            })
                            ->leftJoin('teacher',function($join){
                                $join->on('sh_classsched.teacherid','=','teacher.id')
                                ->where('teacher.deleted','0')
                                ->where('teacher.isactive','1');
                            })
                            ->join('days','sh_classscheddetail.day','=','days.id')
                            ->leftJoin('schedclassification','sh_classscheddetail.classification','=','schedclassification.id')
                            ->join('rooms',function($join){
                                $join->on('sh_classscheddetail.roomid','=','rooms.id');
                                $join->where('rooms.deleted','0');
                            })

                            // if(Session::has('semester')){

         

                            //     $query->join('semester',function($join){
                            //         $join->on('sh_classsched.semid','=','semester.id');
                            //         $join->where('semester.id',Session::get('semester')->id);
                            //     });
                
                            //     $blockassignment->join('semester',function($join){
                            //         $join->on('sh_blocksched.semid','=','semester.id');
                            //         $join->where('semester.id',Session::get('semester')->id);
                            //     });
                
                            // }
                            // else{
                
                            //     $query->join('semester',function($join){
                            //         $join->on('sh_classsched.semid','=','semester.id');
                            //         $join->where('semester.isactive','1');
                            //     });
                
                
                            // }
                            // ->join('semester',function($join){
                            //     $join->on('sh_classsched.semid','=','semester.id');
                            //     $join->where('semester.isactive','1');
                            // })
                            
                            ->where('sh_classsched.deleted','0')
                            ->where('sh_classsched.sectionid', $section);

            
            $blockassignment = DB::table('sh_sectionblockassignment')
                                ->join('sh_block',function($join){
                                    $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                    $join->where('sh_block.deleted','0');
                                })
                                ->join('sh_blocksched',function($join){
                                    $join->on('sh_sectionblockassignment.blockid','=','sh_blocksched.blockid');
                                    $join->where('sh_blocksched.deleted','0');
                                })
                                ->join('sh_subjects',function($join){
                                    $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                                })
                                ->join('sh_blockscheddetail',function($join){
                                    $join->on('sh_blocksched.id','=','sh_blockscheddetail.headerid');
                                    $join->where('sh_blockscheddetail.deleted','0');
                                })
                                ->leftJoin('schedclassification','sh_blockscheddetail.classification','=','schedclassification.id')
                                ->leftJoin('teacher',function($join){
                                    $join->on('sh_blocksched.teacherid','=','teacher.id')
                                    ->where('teacher.deleted','0')
                                    ->where('teacher.isactive','1');
                                })
                                ->join('days','sh_blockscheddetail.day','=','days.id')
                                ->join('rooms',function($join){
                                    $join->on('sh_blockscheddetail.roomid','=','rooms.id');
                                    $join->where('rooms.deleted','0');
                                })
                                ->where('sh_sectionblockassignment.sectionid',$section)
                                ->where('sh_sectionblockassignment.deleted','0');



            if(Session::has('schoolYear')){
    
                $classscheds->join('sy',function($join) {
                    $join->on('sy.id','=','sh_classsched.syid');
                    $join->where('sy.id',Session::get('schoolYear')->id);
                });

                $blockassignment->join('sy',function($join) {
                                    $join->on('sy.id','=','sh_sectionblockassignment.syid');
                                    $join->on('sy.id','=','sh_blocksched.syid');
                                    $join->where('sy.id',Session::get('schoolYear')->id);
                                });
                
            }
            else{

                $classscheds->join('sy',function($join){
                    $join->on('sy.id','=','sh_classsched.syid');
                    $join->where('sy.isactive','1');
                });
                
                $blockassignment->join('sy',function($join){
                    $join->on('sy.id','=','sh_sectionblockassignment.syid');
                    $join->on('sy.id','=','sh_blocksched.syid');
                    $join->where('sy.isactive','1');
                });
    
            }

               if(Session::has('semester')){

                    $classscheds->join('semester',function($join){
                        $join->on('sh_classsched.semid','=','semester.id');
                        $join->where('semester.id',Session::get('semester')->id);
                    });

                    $blockassignment->join('semester',function($join){
                        $join->on('sh_blocksched.semid','=','semester.id');
                        $join->where('semester.id',Session::get('semester')->id);
                    });

                }
                else{

                    $classscheds->join('semester',function($join){
                        $join->on('sh_classsched.semid','=','semester.id');
                        $join->where('semester.isactive','1');
                    });

                    $blockassignment->join('semester',function($join){
                        $join->on('sh_blocksched.semid','=','semester.id');
                        $join->where('semester.isactive','1');
                    });


                }

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
                            ->leftJoin('teacher',function($join){
                                $join->on('assignsubjdetail.teacherid','=','teacher.id')
                                ->where('teacher.deleted','0')
                                ->where('teacher.isactive','1');
                            })
                            ->leftJoin('schedclassification','classscheddetail.classification','=','schedclassification.id')
                            ->join('days','classscheddetail.days','=','days.id')
                            ->join('rooms',function($join){
                                $join->on('classscheddetail.roomid','=','rooms.id');
                                $join->where('rooms.deleted','0');
                            });

            if(Session::has('schoolYear')){

                $classscheds->join('sy',function($join) {
                                    $join->on('sy.id','=','assignsubj.syid');
                                    $join->on('sy.id','=','classsched.syid');
                                    $join->where('sy.id',Session::get('schoolYear')->id);
                                });
                
            }
            else{
                
                $classscheds->join('sy',function($join){
                        $join->on('sy.id','=','classsched.syid');
                        $join->on('sy.id','=','assignsubj.syid');
                        $join->where('sy.isactive','1');
                    });

            }
                          
                $classscheds->where('assignsubj.deleted','0')
                        ->where('assignsubj.sectionid',$section);
         
            
        }

       
        if( $day!=null){

            if($acadProg->acadprogid == 5){

                $classscheds->where('day',$day);

                $blockassignment->where('day',$day);

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
                'sh_subjects.type',
                'sh_classscheddetail.classification as classification',
                'schedclassification.description as schedclass'
            );

            $blockassignment->select(
                'sh_subjects.subjcode',
                'sh_subjects.subjtitle as subjdesc'
            );

            $blockassignment->addSelect(
                'sh_blocksched.subjid',
                'sh_blocksched.teacherid',
                'sh_blockscheddetail.etime',
                'sh_blockscheddetail.roomid',
                'sh_blockscheddetail.day as days',
                'sh_blockscheddetail.stime',
                'days.description',
                'rooms.roomname',
                'sh_blockscheddetail.id as detailid',
                'sh_blockscheddetail.headerid',
                'sh_blocksched.teacherid',
                'sh_subjects.type',
                'teacher.firstname',
                'teacher.lastname',
                'schedclassification.description as schedclass',
                'sh_blockscheddetail.classification as classification'
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
                'rooms.roomname',
                'schedclassification.description as schedclass',
                'classscheddetail.classification as classification'
            );
        }

        $classscheds->addSelect(

            'teacher.firstname',
            'teacher.lastname'
            
        );

        if($acadProg->acadprogid == 5){

            $classscheds = $classscheds
                    ->distinct()
                    ->get();


            foreach($blockassignment->get() as $item){
        
                $classscheds->push($item);
    
    
            }

        }

        else{

            $classscheds = $classscheds
                ->distinct()
                ->get();
        }

        foreach($classscheds as $item){

            $item->time = $item->stime.'-'.$item->etime;

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
        $day= null,
        $subjid = null,
        $type = null,
        $syid = null,
        $semid = null
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

            $query = DB::table('sh_classsched');

            $blockassignment = DB::table('sh_sectionblockassignment')
                            ->join('sh_block',function($join){
                                $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                                $join->where('sh_block.deleted','0');
                            })
                            ->join('sh_blocksched',function($join){
                                $join->on('sh_sectionblockassignment.blockid','=','sh_blocksched.blockid');
                                $join->where('sh_blocksched.deleted','0');
                            })
                            ->where('sh_sectionblockassignment.deleted','0');
                           
          
          
        

            if($syid != null){

                $query->where('sh_classsched.syid',$syid);
                $blockassignment->where('sh_blocksched.syid',$syid);

            }else{

                if(Session::has('schoolYear')){

                    $query->join('sy',function($join){
                        $join->on('sh_classsched.syid','=','sy.id');
                       $join->where('sy.id',Session::get('schoolYear')->id);
                    });
    
                    $blockassignment ->join('sy',function($join){
                        $join->on('sh_blocksched.syid','=','sy.id');
                        $join->where('sy.id',Session::get('schoolYear')->id);
                    });
    
                }
                else{
    
                    $query ->join('sy',function($join){
                        $join->on('sh_classsched.syid','=','sy.id');
                        $join->where('sy.isactive','1');
                    });
    
                    $blockassignment ->join('sy',function($join){
                        $join->on('sh_blocksched.syid','=','sy.id');
                        $join->where('sy.isactive','1');
                    });
    
                }

            }

          
            

            if($semid != null){

                $query->where('sh_classsched.semid',$semid);
                $blockassignment->where('sh_blocksched.semid',$semid);

            }else{

                // if(Session::has('semester')){

                //     $query->join('semester',function($join){
                //         $join->on('sh_classsched.semid','=','semester.id');
                //         $join->where('semester.id',Session::get('semester')->id);
                //     });
    
                //     $blockassignment->join('semester',function($join){
                //         $join->on('sh_blocksched.semid','=','semester.id');
                //         $join->where('semester.id',Session::get('semester')->id);
                //     });
    
                // }
                
                // else{
    
                //     $query->join('semester',function($join){
                //         $join->on('sh_classsched.semid','=','semester.id');
                //         $join->where('semester.isactive','1');
                //     });
    
                //     $blockassignment->join('semester',function($join){
                //         $join->on('sh_blocksched.semid','=','semester.id');
                //         $join->where('semester.isactive','1');
                //     });
    
                // }

            }

            $blockassignment->join('sh_subjects',function($join){
                $join->on('sh_blocksched.subjid','=','sh_subjects.id');
            })
            ->where('sh_blocksched.deleted','0');
                              
            $query->leftJoin('sh_subjects',function($join){
                        $join->on('sh_classsched.subjid','=','sh_subjects.id');
                    })
                    ->where('sh_classsched.deleted','0');

            if($section!=null){

                $query->where('sh_classsched.sectionid',$section);

                $blockassignment->where('sh_sectionblockassignment.sectionid',$section);

            }

            if($subjid!=null){

                $query->where('sh_classsched.subjid',$subjid);

            }


             $query = $query->select(
                'sh_subjects.id',
                'sh_subjects.subjtitle as subjdesc',
                'sh_subjects.subjcode',
                'sh_subjects.type',
                'sh_subjects.inMAPEH',
                'sh_subjects.semid',
                'sh_classsched.teacherid',
                'inSF9', // grade posting
                'sh_subj_sortid as subj_sortid'
            );

            $blockassignment = $blockassignment->select(
                'sh_subjects.id',
                'sh_subjects.subjtitle as subjdesc',
                'sh_subjects.subjcode',
                'sh_subjects.type',
                'sh_subjects.inMAPEH',
                'sh_subjects.semid',
                'sh_blocksched.teacherid',
                'inSF9', // grade posting
                'sh_subj_sortid as subj_sortid'
            );

        }

        else{

            $query = DB::table('assignsubj');

          

            if($syid != null){

                $query->where('assignsubj.syid',$syid);
               

            }else{

                if(Session::has('schoolYear')){

                    $query->join('sy',function($join){
                        $join->on('sy.id','=','assignsubj.syid');
                        $join->where('sy.id',Session::get('schoolYear')->id);
                    });
    
                }
                else{
    
                    $query->join('sy',function($join){
                        $join->on('sy.id','=','assignsubj.syid');
                        $join->where('sy.isactive','1');
                    });
    
                }


            }
                           
                        $query->leftJoin('assignsubjdetail',function($join){
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

            if($subjid!=null){

                $query->where('assignsubjdetail.subjid',$subjid);

            }
            
            $query = $query->select('subjects.id','subjects.subjdesc','subjects.subjcode', 'subjects.inMAPEH','teacherid','inSF9','inTLE','subj_per','subj_sortid'); //tle module //grade posting


        }

        if($type == 'sf9'){

            $query = $query->where('inSF9','1')
                            ->addSelect('inSF9','inMAPEH');
            
        }
        
        $count = $query->distinct()->count();

        if($acadProg->acadprogid == 5 ){

            $blockcount = $blockassignment->count();
    
    
        }
        else{

            $blockcount = 0;

        }

       

        $subjects = $query->distinct()->get();

        if($acadProg->acadprogid == 5 ){

            foreach($blockassignment->get() as $item){

                $subjects->push($item);

            }

        }

        $data = array();

        array_push($data, (object)['data'=>$subjects,'count'=>$count+$blockcount]);

        return $data;
        
    }

}
