<?php

namespace App\Models\Grades;

use Illuminate\Database\Eloquent\Model;
use DB;

class GradesData extends Model
{
    
    public static function student_grades_sh($syid = null, $semid = null, $sectionid = null, $blockid = null, $studid = null){

        $sh_grades = DB::table('sh_classsched')
                        ->join('sh_subjects',function($join){
                            $join->on('sh_classsched.subjid','=','sh_subjects.id');
                            $join->where('sh_classsched.deleted',0);
                        })  
                        ->leftJoin('tempgradesum',function($join) use($studid){
                            $join->on('sh_classsched.subjid','=','tempgradesum.subjid');
                            $join->where('studid',$studid);
                            $join->where('inSF9',1);
                        }) 
                        ->where('sectionid',$sectionid)
                        ->where('inSF9',1)
                        ->where('sh_classsched.deleted',0)
                        ->where('sh_classsched.syid',$syid)
                        ->where('sh_classsched.semid',$semid)
                        ->select(
                            'subjtitle as subjdesc',
                            'sh_classsched.subjid',
                            'q1',
                            'q2',
                            'q3',
                            'q4',
                            'subj_sortid'
                        )
                        ->get();

        $block_grades = DB::table('sh_blocksched')
                        ->join('sh_subjects',function($join){
                            $join->on('sh_blocksched.subjid','=','sh_subjects.id');
                            $join->where('sh_blocksched.deleted',0);
                            $join->where('inSF9',1);
                        })  
                        ->leftJoin('tempgradesum',function($join) use($studid){
                            $join->on('sh_blocksched.subjid','=','tempgradesum.subjid');
                            $join->where('studid',$studid);
                        })  
                        ->where('inSF9',1)
                        ->where('blockid',$blockid)
                        ->where('sh_blocksched.deleted',0)
                        ->where('sh_blocksched.syid',$syid)
                        ->where('sh_blocksched.semid',$semid)
                        ->select(
                            'subjtitle as subjdesc',
                            'sh_blocksched.subjid',
                            'q1',
                            'q2',
                            'q3',
                            'q4',
                            'subj_sortid'
                        )
                        ->get();


        $grades = array();

        foreach($sh_grades as $item){
            array_push($grades,$item);
        }
        foreach($block_grades as $item){
            array_push($grades,$item);
        }
      
        return $grades;

    }

    public static function student_grades($syid = null, $sectionid = null, $studid = null){

        $grades = DB::table('assignsubj')
                    ->where('assignsubj.syid',$syid)
                    ->where('assignsubj.deleted',0)
                    ->where('assignsubj.sectionid',$sectionid)
                    ->join('assignsubjdetail',function($join){
                        $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                        $join->where('assignsubjdetail.deleted',0);
                    })
                    ->join('subjects',function($join){
                        $join->on('assignsubjdetail.subjid','=','subjects.id');
                        $join->where('subjects.deleted',0);
                        $join->where('inSF9',1);
                    })
                    ->leftJoin('tempgradesum',function($join) use($studid){
                        $join->on('assignsubjdetail.subjid','=','tempgradesum.subjid');
                        $join->where('studid',$studid);
                    })  
                    ->select(
                        'subjdesc as subjdesc',
                        'assignsubjdetail.subjid',
                        'q1',
                        'q2',
                        'q3',
                        'q4',
                        'subj_sortid',
                        'inMAPEH',
                        'inTLE',
                        'subj_per'
                    )
                    ->get();

        $grades_array = array();

        foreach($grades as $item){
            array_push($grades_array , $item);
        }

        $grades = $grades_array;

        $tle_grades = collect($grades)->where('inTLE',1);
        if(count($tle_grades) != 0){
            $tle_grade =  \App\Models\Grading\GradingReport::generate_tle($tle_grades);
            array_push($grades,$tle_grade);
        }
    
        $mapeh_grades = collect($grades)->where('inMAPEH',1);
        if(count($mapeh_grades) != 0){
            $mapeh_grades =  \App\Models\Grading\GradingReport::generate_mapeh($mapeh_grades);
            array_push($grades,$mapeh_grades);
        }

        $grades =  \App\Models\Grading\GradingReport::sort_sf9($grades);
            
        return  $grades;

    }


    public static function all_grades_v1($syid = null, $semid = null, $quarter = null, $gradelevel = null){

        $grades = DB::table('grades')
                    ->join('gradesdetail',function($join){
                        $join->on('grades.id','=','gradesdetail.headerid');
                    })
                    ->join('gradessetup',function($join){
                        $join->on('grades.subjid','=','gradessetup.id');
                        $join->where('gradessetup.deleted',0);
                    })
                    ->where('grades.syid',$syid)
                    ->where('grades.semid',$semid)
                    ->where('grades.quarter',$quarter)
                    ->where('grades.deleted',0)
                    ->select(
                        'gradesdetail.id',
                        'studname',
                        'wwtotal',
                        'pttotal',
                        'qa1',
                        'ig',
                        'qg',
                        'grades.*',
                        'writtenworks',
                        'performancetask',
                        'qassesment'
                    )
                    ->get();

        foreach($grades as $item){
            
            $wwhrtotal = 0;
            $pthrtotal = 0;

            for($x = 0 ; $x <= 9 ; $x++){

                $wwhrfile = 'wwhr'.$x;
                $pthrfile = 'pthr'.$x;
                $wwhrtotal += $item->$wwhrfile;
                $pthrtotal += $item->$pthrfile;
            }

            $item->wwhrtotal = $wwhrtotal;
            $item->pthrtotal = $pthrtotal;
        }

        return $grades;

    }

    public static function general_average($grades){

        $final = array();

        $sem1 = collect($grades)->where('semid',1);
        $sem2 = collect($grades)->where('semid',2);
       
        if(count($sem1) > 0){
            array_push($final,self::generate_final($sem1));
        }

        if(count($sem2) > 0){
            $gf = self::generate_final($sem2);
            $gf->semid = 2;
            array_push($final,$gf);
        }
       
        return $final;

    }

    public static function generate_final($grade){

        $genave1 = 0;
        $genave2 = 0;
        $genave3 = 0;
        $genave4 = 0;
        $withgenave1 = collect($grade)->where('quarter1',null)->where('mapeh',0)->where('inTLE',0)->count() == 0 ? true : false;
        $withgenave2 = collect($grade)->where('quarter2',null)->where('mapeh',0)->where('inTLE',0)->count() == 0 ? true : false;
        $withgenave3 = collect($grade)->where('quarter3',null)->where('mapeh',0)->where('inTLE',0)->count() == 0 ? true : false;
        $withgenave4 = collect($grade)->where('quarter4',null)->where('mapeh',0)->where('inTLE',0)->count() == 0 ? true : false;
        $subjcount = 0;

        foreach($grade as $item){
            if($item->mapeh == 0 && $item->inTLE == 0){
                $genave1 += $withgenave1 ? $item->quarter1 : 0;
                $genave2 += $withgenave2 ? $item->quarter2 : 0;
                $genave3 += $withgenave3 ? $item->quarter3 : 0;
                $genave4 += $withgenave4 ? $item->quarter4 : 0;
                $subjcount += 1;
            }
        }

        $genave1 = $withgenave1 ?  number_format( $genave1 / $subjcount ) : null;
        $genave2 = $withgenave2 ?  number_format( $genave2 / $subjcount ) : null;
        $genave3 = $withgenave3 ?  number_format( $genave3 / $subjcount ) : null;
        $genave4 = $withgenave4 ?  number_format( $genave4 / $subjcount ) : null;
        
        return (object)[
                'semid'=>1,
                'quarter1'=>$genave1,
                'quarter2'=>$genave2,
                'quarter3'=>$genave3,
                'quarter4'=>$genave4,
                'mapeh '=>0,
                'inTLE'=>0,
            ];

    }


    public static function get_finalrating($grades = [], $acad = null){

        if($acad == 5){
            foreach($grades as $item){
                $withfinal = $item->semid == 1 ? ($item->quarter1 != null && $item->quarter2 != null ? true:false) : ($item->quarter3 != null && $item->quarter4 != null ? true:false);
                $finalrating = $withfinal ? $item->semid == 1 ? number_format( ( $item->quarter1 + $item->quarter2 ) / 2 ) : number_format( ( $item->quarter3 + $item->quarter4 ) / 2 ) : null;
                $item->finalrating = $withfinal ? $finalrating : null;
                $item->actiontaken = $withfinal ? $finalrating >= 75 ? 'PASSED':'FAILED' : null;
            }
        }else{
            foreach($grades as $item){
                $withfinal = $item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null ? true:false;
                $finalrating = $withfinal ? number_format( ( $item->quarter1 + $item->quarter2 + $item->quarter3 + $item->quarter4 ) / 4 ) : null;
                $item->finalrating = $withfinal ? $finalrating : null;
                $item->actiontaken = $withfinal ? $finalrating >= 75 ? 'PASSED':'FAILED' : null;
            }
        }
        return $grades;
       
    }

    public static function student_grades_detail($syid = null, $semid = null, $sectionid = null, $quarter = null, $studid = null, $gradelevel = null, $strand = null, $subjid = null, $all_subjects = null){

        $grades =  DB::table('grades');

        if($sectionid != null){
            $grades = $grades->where('sectionid',$sectionid);
        }
        if($syid != null){
            $grades = $grades->where('syid',$syid);
        }
        if($semid != null){
            $grades = $grades->where('grades.semid',$semid);
        }
        // if($quarter != null){
        //     $grades = $grades->where('quarter',$quarter);
        // }
        if($subjid != null){
            $grades = $grades->where('subjid',$subjid);
        }

        if($gradelevel != 14 && $gradelevel != 15){
            $grades = $grades->join('subjects',function($join){
                $join->on('grades.subjid','=','subjects.id');
                $join->where('subjects.deleted',0);
            });
        }else{
            $grades = $grades->join('sh_subjects',function($join){
                $join->on('grades.subjid','=','sh_subjects.id');
                $join->where('sh_subjects.deleted',0);
            });
        }
                     
        $grades =   $grades->where('grades.deleted',0)   
                        ->join('gradesdetail',function($join) use($studid){
                            $join->on('grades.id','=','gradesdetail.headerid');
                            if(auth()->user()->type == 7 || auth()->user()->type == 9){
                                $join->where('gradesdetail.gdstatus',4);
                            }else{
                                $join->whereNotIn ('gradesdetail.gdstatus',[0,3]);
                            }
                            if($studid != null){
                                $join->where('gradesdetail.studid',$studid);
                            }
                        });

        if($gradelevel != 14 && $gradelevel != 15){
            $grades =  $grades->select('studid','inTLE','inMAPEH','inMAPEH as mapeh','subj_per','qg','subjid','gradesdetail.gdstatus','gradesdetail.id as gdid','quarter','grades.semid','gdstatus');
        }else{
            $grades =  $grades->select('studid','qg','subjid','gradesdetail.gdstatus','gradesdetail.id as gdid','quarter','grades.semid','gdstatus','type');
        }

        $grades =  $grades->get();

        if($quarter != null){
            $grades = collect($grades)->where('quarter',$quarter)->values();
            foreach($grades as $item){
                $item->semid = $semid;
                $item->quarter1 = null;
                $item->quarter2 = null;
                $item->quarter3 = null;
                $item->quarter4 = null;
                $item->q1 = null;
                $item->q2 = null;
                $item->q3 = null;
                $item->q4 = null;
                $temp_quarter = 'quarter'.$quarter;
                $item->$temp_quarter = $item->qg;
                $temp_quarter = 'q'.$quarter;
                $item->$temp_quarter = $item->qg;
                if($gradelevel == 14 || $gradelevel == 15){
                    $item->mapeh = 0;
                    $item->inTLE = 0;
                }
            }
        }else{
            $subjects = collect($grades)->unique('subjid')->values();
           
            foreach($subjects as $item){
                $item->semid = $item->semid;
                $item->quarter1 = null;
                $item->quarter2 = null;
                $item->quarter3 = null;
                $item->quarter4 = null;
                $item->q1 = null;
                $item->q2 = null;
                $item->q3 = null;
                $item->q4 = null;
                for($x = 1; $x <= 4; $x++){
                    $temp_quarter = 'quarter'.$x;
                    $item->$temp_quarter = collect($grades)->where('quarter',$x)->where('subjid',$item->subjid)->count() > 0 ? collect($grades)->where('subjid',$item->subjid)->where('quarter',$x)->first()->qg : null;
                    $temp_quarter = 'q'.$x;
                    $item->$temp_quarter = collect($grades)->where('quarter',$x)->where('subjid',$item->subjid)->count() > 0 ? collect($grades)->where('subjid',$item->subjid)->where('quarter',$x)->first()->qg : null;
                }
                if($gradelevel == 14 || $gradelevel == 15){
                    $item->mapeh = 0;
                    $item->inTLE = 0;
                }
            }
            
            $grades = $subjects;
        }

        $temp_grades = array();
        foreach($grades as $item){
             array_push($temp_grades,$item);
        }
        $grades =   $temp_grades;
        
        //get missing subject
        if($all_subjects != null){
           foreach($all_subjects as $item){
                $check = collect($grades)->where('subjid',$item->id)->count();
                if($check == 0){
                    $tle = isset($item->intTLE) ? $item->intTLE : 0;
                    $mapeh = isset($item->inMAPEH) ? $item->inMAPEH : 0;
                    $sem = isset($item->semid) ? $item->semid : 1;
                    $type = isset($item->type) ? $item->type : 1;
                    $teacherid = isset($item->teacherid) ? $item->teacherid : null;
                    $subj_sortid = isset($item->subj_sortid) ? $item->subj_sortid : null;
                    $temp_data = (object)[
                        'subjid'=>$item->id,
                        'subjdesc'=>$item->subjdesc,
                        'q1'=>null,
                        'q2'=>null,
                        'q3'=>null,
                        'q4'=>null,
                        'quarter1'=>null,
                        'quarter2'=>null,
                        'quarter3'=>null,
                        'quarter4'=>null,
                        'inMAPEH'=>$mapeh,
                        'mapeh'=>$mapeh,
                        'inTLE'=>$tle,
                        'semid'=>$sem,
                        'type'=>$type,
                        'teacherid'=>$teacherid,
                        'subj_sortid'=>$subj_sortid
                    ];
                    
                    array_push($grades,$temp_data);
                }
            }
        }
        //get missing subject

        

        //add subject description
        foreach($grades as $item){
            $check = collect($all_subjects)->where('id',$item->subjid)->values();
            if(count($check) > 0){
                $item->subjdesc = $check[0]->subjdesc;
                $item->subj_sortid = $check[0]->subj_sortid;
            }
        }
        //add subject description


      
        $grades_array = array();
        foreach($grades as $item){
            array_push($grades_array , $item);
        }
        $grades = $grades_array;

        $tle_grades = collect($grades)->where('inTLE',1);
        if(count($tle_grades) != 0){
            $tle_grade =  self::generate_tle($tle_grades);
            $check_count = collect($grades)->where('subjid','TLE1')->count();
            if($check_count == 0){
                array_push($grades,$tle_grade);
            }else{
                foreach($grades as $key=>$item){
                   if($item->subjid == 'TLE1'){
                        $grades[$key]=$tle_grade;
                   }
                }
            }
        }
    
        $mapeh_grades = collect($grades)->where('inMAPEH',1);
        if(count($mapeh_grades) != 0){
            $mapeh_grade =  self::generate_mapeh($mapeh_grades);
            $check_count = collect($grades)->where('subjid','M1')->count();
            if($check_count == 0){
                array_push($grades,$mapeh_grade);
            }else{
                foreach($grades as $key=>$item){
                   if($item->subjid == 'M1'){
                        $grades[$key]=$mapeh_grade;
                   }
                }
            }
        }

     

        $grades =  self::sort_sf9($grades);

        if($gradelevel == 14 || $gradelevel == 15){
            foreach($grades as $key=>$studgrades_item){
                $checkStrand = DB::table('sh_subjstrand')
                                    ->where('subjid',$studgrades_item->subjid)
                                    ->where('deleted',0)
                                    ->get();
                if( count($checkStrand) > 0 ){
                    $check_same_strand = collect($checkStrand)->where('strandid',$strand)->count();
                    if( $check_same_strand == 0){
                        unset($grades[$key]);
                    }
                }
            }

            $grades_array = array();
            foreach($grades as $item){
                array_push($grades_array , $item);
            }
            $grades = $grades_array;
        }

        foreach($grades as $item){
            $item->quarter1 = $item->quarter1 >= 60 ? $item->quarter1 : null;
            $item->quarter2 = $item->quarter2 >= 60 ? $item->quarter2 : null;
            $item->quarter3 = $item->quarter3 >= 60 ? $item->quarter3 : null;
            $item->quarter4 = $item->quarter4 >= 60 ? $item->quarter4 : null;
            $item->q1 = $item->q1 >= 60 ? $item->q1 : null;
            $item->q2 = $item->q2 >= 60 ? $item->q2 : null;
            $item->q3 = $item->q3 >= 60 ? $item->q3 : null;
            $item->q4 = $item->q4 >= 60 ? $item->q4 : null;
        }
            
        return  $grades;

    }

    public static function generate_tle($tle_grades){
        
        $tle1 = 0;
        $tle2 = 0;
        $tle3 = 0; 
        $tle4 = 0; 
        $with_grade1 = true;
        $with_grade2 = true;
        $with_grade3 = true;
        $with_grade4 = true;
        $mapehcount = 0;

        foreach($tle_grades as $tle_item){
            $tle1 += number_format( $tle_item->q1 * ( $tle_item->subj_per / 100 ) );
            $tle2 += number_format( $tle_item->q2 * ( $tle_item->subj_per / 100 ) );
            $tle3 += number_format( $tle_item->q3 * ( $tle_item->subj_per / 100 ) );
            $tle4 += number_format( $tle_item->q4 * ( $tle_item->subj_per / 100 ) );
        }

        if($tle1 == 0){
            $tle1 = null;
        }
        if($tle2 == 0){
            $tle2 = null;
        }
        if($tle3 == 0){
            $tle3 = null;
        }
        if($tle4 == 0){
            $tle4 = null;
        }

        $temp_data = (object)[
            'subjid'=>"TLE1",
            'subjdesc'=>'COMPUTER / HELE',
            'q1'=>$tle1,
            'q2'=>$tle2,
            'q3'=>$tle3,
            'q4'=>$tle4,
            'quarter1'=>$tle1,
            'quarter2'=>$tle2,
            'quarter3'=>$tle3,
            'quarter4'=>$tle4,
            'inMAPEH'=>0,
            'mapeh'=>0,
            'inTLE'=>0,
            'teacherid'=>null,
            'subj_sortid'=>'3T0' ,
            'semid'=>1
        ];
        
        return $temp_data;

    }

    public static function generate_mapeh($mapeh_grades){
           
        $mapehq1 = 0;
        $mapehq2 = 0;
        $mapehq3 = 0; 
        $mapehq4 = 0; 
        $with_grade1 = true;
        $with_grade2 = true;
        $with_grade3 = true;
        $with_grade4 = true;
        $mapehcount = 0;
        
        $grade_list = array();
        
        foreach($mapeh_grades as $mapeh_item){

            $with_grade1 = $with_grade1 ? $mapeh_item->q1 != null ? $with_grade1: false : '';
            $with_grade2 = $with_grade2 ? $mapeh_item->q2 != null ? $with_grade2: false : '';
            $with_grade3 = $with_grade3 ? $mapeh_item->q3 != null ? $with_grade3: false : '';
            $with_grade4 = $with_grade4 ? $mapeh_item->q4 != null ? $with_grade4: false : '';

            $mapehcount += 1;
            $mapehq1 += $mapeh_item->q1;
            $mapehq2 += $mapeh_item->q2;
            $mapehq3 += $mapeh_item->q3;
            $mapehq4 += $mapeh_item->q4;
        }

        if($with_grade1){
            $mapehq1 = number_format( $mapehq1 /$mapehcount );
        }else{
            $mapehq1 = null;
        }

        if($with_grade2){
            $mapehq2 = number_format( $mapehq2 /$mapehcount );
        }else{
            $mapehq2 = null;
        }

        if($with_grade3){
            $mapehq3 = number_format( $mapehq3 /$mapehcount );
        }else{
            $mapehq3 = null;
        }

        if($with_grade4){
            $mapehq4 = number_format( $mapehq4 /$mapehcount );
        }else{
            $mapehq4 = null;
        }
        
        $temp_data = (object)[
            'subjid'=>"M1",
            'subjdesc'=>'MAPEH',
            'q1'=>$mapehq1,
            'q2'=>$mapehq2,
            'q3'=>$mapehq3,
            'q4'=>$mapehq4,
            'quarter1'=>$mapehq1,
            'quarter2'=>$mapehq2,
            'quarter3'=>$mapehq3,
            'quarter4'=>$mapehq4,
            'inMAPEH'=>0,
            'mapeh'=>0,
            'inTLE'=>0,
            'teacherid'=>null,
            'semid'=>1,
            'subj_sortid'=>'2M0'
        ];

       return $temp_data;


    }

    public static function sort_sf9($grades){

        $grade_list = array();
        $new_temp_holder = collect($grades)->sortBy('subj_sortid');
        $grades_array = array();

        foreach($new_temp_holder as $new_temp_holder_item){
            array_push($grades_array,$new_temp_holder_item);
        }

        return $grades_array;

    }
        

    public static function hcb_special($grades = [], $levelid = null){

        foreach($grades as $item){
            array_push($temp_grades,$item);
        }
        $grades = $temp_grades;

        $tle =  (object)[ 
                            "subjdesc"=> "TLE/COMPUTER",
                            "q1"=> "",
                            "q2"=> "",
                            "q3"=> null,
                            "q4"=> null,
                            "subjid"=> "",
                            "mapeh"=> "0",
                            "inSF9"=> 1,
                            "subj_per"=> "0",
                            "inTLE"=> "0",
                            "semid"=> 1,
                            "finalRating"=> null,
                            "type"=> null,
                            "gdid"=> "",
                            "subj_sortid"=> "1F",
                            "semid"=>1
                        ];

        if($levelid == 10 || $levelid == 11){
            //grade 7 and 8 : 1st and 2nd quarter is tle, 3rd and 4th quarter is computer
            foreach($grades as $key=>$item){
                if($item->subjid == 9){ //tle
                    $tle->q1 = $item->q1;
                    $tle->q2 = $item->q2;
                    unset($grades[$key]);
                }elseif($item->subjid == 10){//computre
                    $tle->q3 = $item->q1;
                    $tle->q4 = $item->q2;
                    unset($grades[$key]);
                }
            }
            array_push($tle,$grades);
        }
        else if($levelid == 12 || $levelid == 13){
            //grade 9 and 10: 1st and 2nd quarter is computer, 3rd and 4th quarter is tle
            foreach($grades as $key=>$item){
                if($item->subjid == 9){
                    $tle->q3 = $item->q1;
                    $tle->q4 = $item->q2;
                    unset($grades[$key]);
                }elseif($item->subjid == 10){
                    $tle->q1 = $item->q1;
                    $tle->q2 = $item->q2;
                    unset($grades[$key]);
                }
            }
            array_push($grades,$tle);
        }

    }

    public static function hcb_special_2($grades = [], $levelid = null){
        if($levelid == 10 || $levelid == 11){
            //grade 7 and 8 : 1st and 2nd quarter is tle, 3rd and 4th quarter is computer
            foreach($grades as $key=>$item){
                if($item->subjid == 9){ //tle
                    $item->quarter = $item->quarter;
                }elseif($item->subjid == 10){//computre
                    if($item->quarter == 1){
                        $item->quarter = 3;
                    }
                    if($item->quarter == 2){
                        $item->quarter = 4;
                    }
                }
            }
        }
        else if($levelid == 12 || $levelid == 13){
            //grade 9 and 10: 1st and 2nd quarter is computer, 3rd and 4th quarter is tle
            foreach($grades as $key=>$item){
                if($item->subjid == 9){
                    if($item->quarter == 1){
                        $item->quarter = 3;
                    }
                    if($item->quarter == 2){
                        $item->quarter = 4;
                    }
                }elseif($item->subjid == 10){
                    $item->quarter = $item->quarter;
                }
            }
        }
        return $grades;
        
    }
    

}
