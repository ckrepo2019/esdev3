<?php

namespace App\Models\Grading;
use DB;
use App\Models\Grading\GradingSystem;
use App\Models\Principal\SPP_Subject;
use Illuminate\Database\Eloquent\Model;
use App\Models\Principal\GenerateGrade;

class GradingReport extends Model
{


      public static function get_student_grading_sheet(
            $gradelevel = null,
            $section =  null,
            $syid = null,
            $semid = null
      ){
        
            $gradelevel = $gradelevel;
            $section = $section;
            
            if($gradelevel == 14 || $gradelevel == 15){
                  $semid = $semid;
            }else{
                $semid = 1;
            }

         
            $subjects = collect(SPP_Subject::getSubject(null,null,null,$section,null,null,null,null,null,$syid,$semid)[0]->data)->unique('id');

            $subjcount = 0;
            $data_detail = (object)[];
    
            $sort = 1;
    
            $data_detail->student = 'SUBJECTS';
            $data_detail->sort = $sort;
            $sort += 1;
    
            $all_data = array();
    
            $temp_holder = array();
            
            foreach($subjects as $item){
                if($item->inSF9 == 1){
                    $temp_data = (object)[
                        'subjid'=>$item->id,
                        'subjdesc'=>$item->subjcode,
                        'subjtitle'=>$item->subjdesc,
                        'teacherid'=>$item->teacherid
                    ];
                    array_push($temp_holder,$temp_data);
                }
            }

            $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();

            if($grading_version->version == 'v2'){
    
                $data_detail->grades = $temp_holder;
        
                array_push($all_data, $data_detail);
        
                if($gradelevel == 14 || $gradelevel == 15){

                    $students = DB::table('sh_enrolledstud')
                                ->where('sh_enrolledstud.deleted',0)
                                ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                ->where('sh_enrolledstud.sectionid',$section)
                                ->where('sh_enrolledstud.syid',$syid)
                                ->where('sh_enrolledstud.semid',$semid)
                                ->join('gradelevel',function($join){
                                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                })
                                ->join('sh_strand',function($join){
                                    $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                    $join->where('sh_strand.deleted',0);
                                })
                                ->join('studinfo',function($join){
                                    $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                                })
                                ->select('studinfo.id','lastname','firstname','acadprogid','sh_enrolledstud.strandid','strandcode')
                                ->get();
    
                }else{
    
                    $students = DB::table('enrolledstud')
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                                    ->where('sh_enrolledstud.sectionid',$section)
                                    ->where('enrolledstud.syid',$syid)
                                    ->join('gradelevel',function($join){
                                        $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                        $join->where('gradelevel.deleted',0);
                                    })
                                    ->join('studinfo',function($join){
                                        $join->on('sh_enrolledstud.studid','=','studinfo.id');
                                        $join->where('studinfo.deleted',0);
                                    })
                                    ->select('studinfo.id','lastname','firstname','acadprogid')
                                    ->get();
                }
    
    
                foreach($students as $item){
    
                    $data_detail_student = (object)[];
                
                    $data_detail_student->student = $item->lastname.', '.$item->firstname;
                    $data_detail_student->strand = $item->strandcode;
                    $data_detail_student->sort = $sort;
                    $data_detail_student->studid = $item->id;
                    $sort += 1;
    
                    $subjecInfo = DB::table('tempgradesum')
                                    ->where('studid',$item->id)   
                                    ->where('syid',$syid) 
                                    ->where('semid',$semid) 
                                    ->select('q1','q2','q3','q4','subjid')
                                    ->get();

                        if(count($subjecInfo) > 0 ){
    
                            $temp_holder = array();
    
                            foreach($subjects as $key=>$subj_item){

                                $subjIncluded = true;

                                $checkStrand = DB::table('sh_subjstrand')
                                                    ->where('subjid',$subj_item->id)
                                                    ->where('deleted',0)
                                                    ->select('strandid')
                                                    ->get();

                                if( count($checkStrand) > 0 ){
                                    $check_same_strand = collect($checkStrand)->where('strandid',$item->strandid)->count();
                                    if( $check_same_strand == 0){
                                       $subjIncluded = false;
                                    }
                                }

                                
                                
                                if($subj_item->inSF9 == 1 &&  $subjIncluded){
                                    $headerStatus = collect(collect($data_detail)['grades'])->where('subjid',$subj_item->id)->first();
                                    $subject = collect($subjecInfo)->where('subjid',$subj_item->id)->first();
                                    if(isset($subject->subjid)){
                                        $temp_data = (object)[
                                            'subjid'=>$subj_item->id,
                                            'subjdesc'=>$subj_item->subjdesc,
                                            'q1'=>$subject->q1,
                                            'q2'=>$subject->q2,
                                            'q3'=>$subject->q3,
                                            'q4'=>$subject->q4,
                                            'teacherid'=>$subj_item->teacherid
                                        ];
                                    }else{
                                        $temp_data = (object)[
                                            'subjid'=>$subj_item->id,
                                            'subjdesc'=>$subj_item->subjdesc,
                                            'q1'=>"",
                                            'q2'=>"",
                                            'q3'=>"",
                                            'q4'=>"",
                                            'gdid'=>"",
                                            'teacherid'=>""
                                        ];
                
                                    }
                                    array_push($temp_holder,$temp_data);
                                }
                            }
            
                            $data_detail_student->grades = $temp_holder;
            
                            array_push($all_data, $data_detail_student);
            
                        }

                                
                        for($x = 0; $x < ( 16 - count( $subjects ) ) ; $x++ ){
                
                            $temp_data = (object)[
                                'subjid'=>"",
                                'subjdesc'=>"",
                                'qg'=>"",
                                'status'=>"",
                                'teacherid'=>""
                            ];
                
                            array_push($temp_holder,$temp_data);
                
                        }
    
    
                }
                
    
                return $all_data;
    
    
            }else{
                //version 1
                
                if($syid == null){
                    $syid = DB::table('sy')->where('isactive',1)->first()->id;
                }
                if($semid == null){
                    $semid = DB::table('semester')->where('isactive',1)->first()->id;
                }
    
    
                foreach($temp_holder as $item){
    
                    $headerid = DB::table('grades')
                                        ->where('subjid',$item->subjid)
                                        ->where('sectionid',$section)   
                                        ->where('levelid',$gradelevel)   
                                        ->where('syid',$syid)   
                                        ->where('semid',$semid)   
                                        ->select('id','submitted','status')
                                        ->first();
        
                    if( isset($headerid->id) ){
        
                        $item->gsdid = $headerid->id;
        
                        if( $headerid->submitted == 0 && $headerid->status == 0 ){
        
                            $item->status = 0;
        
                        }
                        else if( $headerid->submitted == 1 && $headerid->status == 0 ){
        
                            $item->status = 1;
        
                        }
                        else{
                            $item->status = $headerid->status;
                        }
                       
        
                    }else{
        
                        $item->gsdid = null;
                        $item->status = 0;
        
                    }
        
                }
        
                for($x = 0; $x < ( 16 - count( $subjects ) ) ; $x++ ){
        
                    $temp_data = (object)[
                        'subjid'=>"",
                        'subjdesc'=>"",
                        'qg'=>"",
                        'status'=>"",
                        'teacherid'=>""
                    ];
        
                    array_push($temp_holder,$temp_data);
        
                }
        
                $data_detail->grades = $temp_holder;
        
        
                array_push($all_data, $data_detail);
        
                $students = DB::table('studinfo')
                                ->where('deleted',0)
                                ->whereIn('studstatus',[1,2,4])
                                ->where('sectionid',$section)
                                ->select('id','lastname','firstname')
                                ->get();

                foreach($students as $item){
        
                    $data_detail_student = (object)[];
                
                    $data_detail_student->student = $item->lastname.', '.$item->firstname;
                    $data_detail_student->sort = $sort;
                    $data_detail_student->studid = $item->id;
                    $sort += 1;
            
                    $subjecInfo = DB::table('gradesdetail')
                                    ->where('gradesdetail.studid',$item->id)
                                    ->join('grades',function($join)
                                    {
                                        $join->on('gradesdetail.headerid','=','grades.id');
                                        $join->where('grades.deleted',0);
                                    })
                                    ->select('qg','subjid','gradesdetail.gdstatus','gradesdetail.id as gdid','quarter')
                                    ->get();

                 

                    if(count($subjecInfo) > 0 ){
        
                        $temp_holder = array();
        
                        foreach($subjects as $subj_item){

                            if($subj_item->inSF9 == 1){

                                $headerStatus = collect(collect($data_detail)['grades'])->where('subjid',$subj_item->id)->first();
                                $subject = collect($subjecInfo)->where('subjid',$subj_item->id)->first();
                          
                                if(isset($subject->subjid)){

                                    $temp_data = (object)[
                                        'subjid'=>$subj_item->id,
                                        'subjdesc'=>$subj_item->subjdesc,
                                        'q1'=>null,
                                        'q2'=>null,
                                        'q3'=>null,
                                        'q4'=>null,
                                        'teacherid'=>$subj_item->teacherid
                                    ];

                                    for($x = 1; $x <= 4; $x++){

                                        $quarter_qg = collect( array($subject))->where('quarter',$x)->first();
                                        if(isset($quarter_qg->qg)){
                                            $qg_field = 'q'.$x;
                                            $temp_data->$qg_field = $quarter_qg->qg;
                                        }
                                        
                                    }
                                    
                                }else{
                                    
                                    $temp_data = (object)[
                                        'subjid'=>$subj_item->id,
                                        'subjdesc'=>$subj_item->subjdesc,
                                        'q1'=>"",
                                        'q2'=>"",
                                        'q3'=>"",
                                        'q4'=>"",
                                        'gdid'=>"",
                                        'teacherid'=>""
                                    ];
            
                                }
                                array_push($temp_holder,$temp_data);
                            }
                        }
        
                        for($x = 0; $x < ( 16 - count( $subjecInfo ) ) ; $x++ ){
        
                            $temp_data = (object)[
                                'subjid'=>"",
                                'subjdesc'=>"",
                                'qg'=>"",
                                'gdid'=>"",
                                'status'=>"",
                                'teacherid'=>""
                            ];
        
                            array_push($temp_holder,$temp_data);
        
                        }
        
                        $data_detail_student->grades = $temp_holder;
        
                        array_push($all_data, $data_detail_student);
        
        
                    }
                   
        
                }
        
                return $all_data;
    
    
    
    
            }
    
            
    
        }

        public static function student_promotion_filtered($studid = null, $syid = null, $semid = null){


            $enrolledstud = DB::table('enrolledstud')
                                ->join('gradelevel',function($join){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                                })
                                ->where('syid',$syid)
                                ->where('enrolledstud.deleted',0)
                                ->where('studid',$studid)
                                ->select('sectionid as ensectid','acadprogid','studid as id')
                                ->first();

            if(!isset($enrolledstud->id)){
                
                $enrolledstud = DB::table('sh_enrolledstud')
                                    ->join('gradelevel',function($join){
                                        $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                        $join->where('gradelevel.deleted',0);
                                    })
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where('sh_enrolledstud.deleted',0)
                                    ->where('studid',$studid)
                                    ->select('sectionid as ensectid','acadprogid','studid as id','strandid')
                                    ->first();
            }

            if(!isset($enrolledstud->id)){

                return array((object)[
                                'status'=>0,
                                'data'=>'No enrollment record found'
                            ]);

            }

            if($enrolledstud->acadprogid != 5){
                $semid = 1;
                $strand = null;
            }
            else{
                $strand = $enrolledstud->strandid;
            }

            $gradesv4 = GenerateGrade::all_grade_filtered($enrolledstud, true, 'sf9',$syid,$semid);

            if(count($gradesv4) == 0){
                return array((object)[
                    'status'=>0,
                    'data'=>'No grades found!'
                ]);
            }

            $grades = $gradesv4;

            $subject_grades = collect($gradesv4)->where('mapeh',0);
            
            $acad =  $enrolledstud->acadprogid;
            $subject_status;
            $subject_count = 0;
            $subjects_with_grades = 0;

            $incomplete_list = array();
            $incomplete_status = false;

            if($acad == 5){

                foreach($grades as $key=>$item){

                    $checkStrand = DB::table('sh_subjstrand')
                                        ->where('subjid',$item->subjid)
                                        ->where('strandid', $strand)
                                        ->where('deleted',0)
                                        ->count();

                    if($checkStrand == 0 && $item->type != 1){

                        unset($grades[$key]);

                    }

                }
                
                $subject_count = collect($grades)->count();

                if($semid == 1){
                    
                    for($x = 1; $x <=2; $x ++){
                        $field = 'q'.$x;

                        if( ( collect($grades)->where($field,'!=',null)->count() != $subject_count ) || collect($grades)->where($field,'<',75)->count() != 0 ){

                            $incomplete_status = true;
                            foreach(collect($grades)->where($field,null) as $item){
                                array_push($incomplete_list,(object)[
                                    'subject'=>$item->subjectcode,
                                    'quarter'=>'Quarter '.$x,
                                    'grade'=>''
                                ]);
                            }
                            foreach(collect($grades)->where($field,'<',75) as $item){
                                array_push($incomplete_list,(object)[
                                    'subject'=>$item->subjectcode,
                                    'quarter'=>'Quarter '.$x,
                                    'grade'=>$item->$field
                                ]);
                            }


                        }
                    }
                }
                elseif($semid == 2){
                    for($x = 3; $x <= 4; $x ++){
                        $field = 'q'.$x;
                        if(( collect($grades)->where($field,'!=',null)->count() != $subject_count ) || collect($grades)->where($field,'<',75)->count() != 0){
                            $incomplete_status = true;
                            $incomplete_status = true;
                            foreach(collect($grades)->where($field,null) as $item){
                                array_push($incomplete_list,(object)[
                                    'subject'=>$item->subjectcode,
                                    'quarter'=>'Quarter '.$x,
                                    'grade'=>''
                                ]);
                            }
                            foreach(collect($grades)->where($field,'<',75) as $item){
                                array_push($incomplete_list,(object)[
                                    'subject'=>$item->subjectcode,
                                    'quarter'=>'Quarter '.$x,
                                    'grade'=>$item->$field
                                ]);
                            }
                        }
                    }
                }

                $generalave = 'INC';

                if(!$incomplete_status){

                    if($semid == 1){

                        $quarter1genave = collect($grades)->sum('quarter1') / $subject_count;
                        $quarter2genave = collect($grades)->sum('quarter2') / $subject_count;
                        $generalave = number_format(  ( $quarter1genave + $quarter2genave ) / 2 );

                    }
                    elseif($semid == 2){

                        $quarter3genave = collect($grades)->sum('quarter3') / $subject_count;
                        $quarter4genave = collect($grades)->sum('quarter4') / $subject_count;
                        $generalave = number_format( ( $quarter3genave + $quarter4genave ) / 2);

                    }

                }

            }

            
            $promotion_status = array((object)[
                'status'=>1,
                'incomplete'=>$incomplete_status,
                'incomplete_list'=>$incomplete_list,
                'genave'=>$generalave
            ]);


            return $promotion_status;




        }

        // public static function grade_report_approved_v1($syid = null, $semid = null, $levelid = null, $sectionid = null){

        //     if($levelid == 14 || $levelid == 15){

        //         $students = DB::table('sh_enrolledstud')
        //                         ->join('studinfo',function($join){
        //                             $join->on('sh_enrolledstud.studid','=','studinfo.id');
        //                             $join->where('studinfo.deleted',0);
        //                         })
        //                         ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
        //                         ->where('sh_enrolledstud.syid',$syid)
        //                         ->where('sh_enrolledstud.semid',$semid)
        //                         ->where('sh_enrolledstud.deleted',0)
        //                         ->where('sh_enrolledstud.sectionid',$sectionid)
        //                         ->select(
        //                             'sh_enrolledstud.studid',
        //                             'sh_enrolledstud.strandid',
        //                             'studinfo.lastname',
        //                             'studinfo.firstname'
        //                         )
        //                         ->get();

        //     }
        //     else{

        //         $students = DB::table('enrolledstud')
        //                         ->join('studinfo',function($join){
        //                             $join->on('enrolledstud.studid','=','studinfo.id');
        //                             $join->where('studinfo.deleted',0);
        //                         })
        //                         ->whereIn('enrolledstud.studstatus',[1,2,4])
        //                         ->where('enrolledstud.syid',$syid)
        //                         ->where('enrolledstud.deleted',0)
        //                         ->where('enrolledstud.sectionid',$sectionid)
        //                         ->select(
        //                             'enrolledstud.studid',
        //                             'studinfo.lastname',
        //                             'studinfo.firstname'
        //                         )
        //                         ->get();

        //     }

        //     $subjects = collect(SPP_Subject::getSubject(null,null,null,$sectionid,null,null,null,null,null,$syid,$semid)[0]->data)->where('inSF9',1);

        //     $grades = DB::table('grades')
        //                 ->join('gradesdetail',function($join){
        //                     $join->on('grades.id','=','gradesdetail.headerid');
        //                 })
        //                 ->where('syid',$syid)
        //                 ->where('semid',$semid)
        //                 ->where('levelid',$levelid)
        //                 ->where('sectionid',$sectionid)
        //                 ->where('grades.deleted',0)
        //                 ->where('submitted',1)
        //                 ->select(
        //                     'gradesdetail.studid',
        //                     'gradesdetail.qg'
        //                 )
        //                 ->get();

        //     foreach($students as $student){

        //         $data_detail_student = (object)[];
        //         $data_detail_student->student = $item->lastname.', '.$item->firstname;
        //         $data_detail_student->sort = $sort;
        //         $data_detail_student->studid = $item->id;


        //     }

        //     // foreach(){


        //     // }

        //     return $grades;

        // }
        

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
                    $mapehcount += 1;
                    $mapehq1 += $mapeh_item->q1;
                    $mapehq2 += $mapeh_item->q2;
                    $mapehq3 += $mapeh_item->q3;
                    $mapehq4 += $mapeh_item->q4;
                }
    
                if($mapehq1 != 0){
                    $mapehq1 = round( $mapehq1 /$mapehcount );
                }else{
                    $mapehq1 = null;
                }
    
                if($mapehq2 != 0){
                    $mapehq2 = round( $mapehq2 /$mapehcount );
                }else{
                    $mapehq2 = null;
                }
    
                if($mapehq3 != 0){
                    $mapehq3 = round( $mapehq3 /$mapehcount );
                }else{
                    $mapehq3 = null;
                }
    
                if($mapehq4 != 0){
                    $mapehq4 = round( $mapehq4 /$mapehcount );
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
                    'inMAPEH'=>0,
                    'inTLE'=>0,
                    'teacherid'=>null,
                    'subj_sortid'=>'I0'
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
}
