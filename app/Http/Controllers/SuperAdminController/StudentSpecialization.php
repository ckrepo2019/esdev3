<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use PDF;

class StudentSpecialization extends \App\Http\Controllers\Controller
{
      public static function print_report(Request $request){

            //by subject
            //by section
            //by schedule
            $syid = $request->get('syid');
            $subjects = self::subjects($request);
            $students = self::subjects_studspec_ajax($request);
            $sections = self::sections($request);
            $gradelevel = self::gradelevel_list($request);
            $type = $request->get('type');
            $schoolinfo = DB::table('schoolinfo')->first();
            $sy = DB::table('sy')
                        ->where('id',$syid)
                        ->first();

            // return $subjects;

            $pdf = PDF::loadView('superadmin.pages.printable.ibed_studentspecialization',compact('sy','schoolinfo','subjects','gradelevel','students','sections','type'))->setPaper('8.5X11');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream('Subject Specialization ('.$sy->sydesc.')');

      }

      public static function subjects(Request $request){

            $all_subject = array();
            $syid = $request->get('syid');

            $subjects =  DB::table('subjects')
                              ->where('deleted',0)
                              ->where('isSP',1)
                              ->select(
                                    'subjdesc as text',
                                    'subjdesc',
                                    'subjcode',
                                    'subjects.id'      
                              )
                              ->get();

            $subject_gradelevel = DB::table('subject_plot')
                                          ->where('subject_plot.syid',$syid)
                                          ->whereIn('subject_plot.subjid',collect($subjects)->pluck('id'))
                                          ->whereNotIn('subject_plot.levelid',[14,15])
                                          ->where('subject_plot.deleted',0)
                                          ->join('subjects',function($join){
                                                $join->on('subject_plot.subjid','=','subjects.id');
                                                $join->where('subjects.deleted',0);
                                          })
                                          ->select(
                                                'subject_plot.subjid',
                                                'subject_plot.levelid', 
                                                'subjcode',
                                                'subjdesc'
                                          )
                                          ->get();

            $subjects = collect($subjects)->whereIn('id',collect($subject_gradelevel)->pluck('subjid'))->values();

            return array(
                  (object)[
                        'subjects'=>$subjects,
                        'info'=>$subject_gradelevel
                  ]
            );
      }

      public static function all_student_ajax(Request $request){
            $syid = $request->get('syid');
            $subjid = $request->get('subjid');
            $levelid = $request->get('levelid');
            $sectionid = $request->get('sectionid');
            return self::all_student($syid,$subjid,$levelid,$sectionid);
      }


      public static function all_student($syid = null, $subjid = null, $levelid = null, $sectionid = null){

            $temp_gradelevel = DB::table('subject_plot')
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->where('subjid',$subjid)
                                    ->select('levelid')
                                    ->get();

            if($subjid == null){
                  return array();
            }

            $gradelevel = array();

            foreach($temp_gradelevel as $item){
                  array_push($gradelevel,$item->levelid);
            }

            $students = DB::table('enrolledstud')
                              ->where('enrolledstud.deleted',0)
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.studstatus','!=',0);
                              // ->whereIn('enrolledstud.studstatus',[1,2,4]);

            if($levelid != null || $levelid != ""){
                  $students = $students->where('enrolledstud.levelid',$levelid);
            }else{
                  $students = $students->whereIn('enrolledstud.levelid',$gradelevel);
            }


            if($sectionid != null || $sectionid != ""){
                  $students = $students->where('enrolledstud.sectionid',$sectionid);
            }


            $students = $students->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              
                              ->join('subject_plot', function($join) use ($subjid) {
                                    $join->on('gradelevel.id','=','subject_plot.levelid');
                                    $join->where('subject_plot.deleted',0);
                                    if($subjid  != null){
                                          $join->where('subject_plot.subjid', $subjid);
                                    }
                              })

                              ->join('sections',function($join) use($syid){
                                    $join->on('enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->select(
                                    'firstname',
                                    'lastname',
                                    'middlename',
                                    'studid',
                                    'sections.sectionname',
                                    'sections.id as sectionid',
                                    'levelname',
                                    'enrolledstud.levelid',
                                    'sid',
                                    'suffix'
                              )
                              ->get();


            foreach($students as $item){
                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                              if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                              } 
                        }
                  }
                  $item->student=$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
                  $item->text=$item->sid.' - '.$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
                  $item->search = $item->sid.' '.$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$item->sectionname.' '.$item->levelname.' '.$temp_middle;
            }
            return $students;

      }

      
      public static function subjects_studspec_ajax(Request $request){
            $syid = $request->get('syid');
            $subjid = $request->get('subjid');
            $levelid = $request->get('levelid'); 
            $sectionid = $request->get('sectionid'); 

            $subjects = self::subjects($request);

            $gradelevel = DB::table('subject_plot')
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->whereIn('subjid',collect($subjects[0]->subjects)->pluck('id'))
                              ->select('levelid')
                              ->select('levelid')
                              ->distinct('levelid')
                              ->get();

            if($subjid != null){
                  $tempstudents =  DB::table('subjects_studspec')
                                    ->where('subjects_studspec.deleted',0)
                                    ->where('subjects_studspec.syid',$syid)
                                    ->where('subjid',$subjid)
                                    ->select(
                                          'studid'
                                    )->get();


            }

           

            $students = DB::table('enrolledstud')
                              ->where('enrolledstud.syid',$syid)
                              ->where('enrolledstud.deleted',0);

            if($levelid != null){
                  $students = $students->where('enrolledstud.levelid',$levelid);
            }

            if($sectionid != null){
                  $students = $students->where('enrolledstud.sectionid',$sectionid);
            }

            if($subjid != null){
                  $students = $students->whereIn('enrolledstud.studid',collect($tempstudents)->pluck('studid'));
            }

             $students = $students
                              ->where('enrolledstud.studstatus','!=',0)
                              // ->whereIn('enrolledstud.studstatus',[1,2,4])
                              ->whereIn('enrolledstud.levelid',collect($gradelevel)->pluck('levelid'))
                              ->join('studinfo',function($join){
                                    $join->on('enrolledstud.studid','=','studinfo.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($syid){
                                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                                    $join->where('studinfo.deleted',0);
                              })
                              ->join('sections',function($join) use($syid){
                                    $join->on('enrolledstud.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                              })
                              ->select(
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'sid',
                                    'sections.id as sectionid',
                                    'sections.sectionname',
                                    'levelname',
                                    'enrolledstud.studid',
                                    'gradelevel.id as grade',
                                    'suffix',
                                    'enrolledstud.levelid'
                              )
                              ->get();


            $specsubj =  DB::table('subjects_studspec')
                              ->where('subjects_studspec.deleted',0)
                              ->where('subjects_studspec.syid',$syid)
                              ->whereIn('subjects_studspec.studid',collect( $students)->pluck('studid'))
                              ->join('subjects',function($leftjoin) {
                                    $leftjoin->on('subjects_studspec.subjid','=','subjects.id');
                                    $leftjoin->where('subjects.deleted',0);
                                    $leftjoin->where('subjects.isSP',1);
                              })
                              ->select(
                                    'subjects_studspec.studid',
                                    'q1',
                                    'q2',
                                    'q3',
                                    'q4',
                                    'subjects_studspec.id',
                                    'subjects_studspec.subjid',
                                    'subjects_studspec.studid',
                                    'subjects_studspec.createddatetime',
                                    'subjects.subjcode'
                              )->get();


            // if($subjid != null){

            //       $filteredstudents = collect($specsubj)
            //                               ->where('subjid',$subjid)
            //                               ->pluck('studid');
                                          
            //       $students = collect($students)
            //                         ->whereIn('studid',$filteredstudents)
            //                         ->values();

            // }


            foreach($students as $item){
                  $middlename = explode(" ",$item->middlename);
                  $temp_middle = '';
                  if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                              if(strlen($middlename_item) > 0){
                              $temp_middle .= $middlename_item[0].'.';
                              } 
                        }
                  }
                  $item->student=$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
                  $item->text=$item->sid.' - '.$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
                  $item->search = $item->sid.' '.$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle.' '.$item->sectionname.' '.$item->levelname;

                  $item->q1 = 0;
                  $item->q2 = 0;
                  $item->q3 = 0;
                  $item->q4 = 0;
                  $item->q1subj = 0;
                  $item->q2subj = 0;
                  $item->q3subj = 0;
                  $item->q4subj = 0;
                  $item->subjtext1 = null;
                  $item->subjtext2 = null;
                  $item->subjtext3 = null;
                  $item->subjtext4 = null;

                  $studspecsubj = collect($specsubj)->where('studid',$item->studid)->values();

                  if(count($studspecsubj) > 0){
                        foreach($studspecsubj as $spectsubj_item){
                              if($spectsubj_item->q1 == 1){
                                    $item->q1 = 1;
                                    $item->q1subj = $spectsubj_item->subjid;
                                    $item->subjtext1 = $spectsubj_item->subjcode;
                              }

                              if($spectsubj_item->q2 == 1){
                                    $item->q2 = 1;
                                    $item->q2subj = $spectsubj_item->subjid;
                                    $item->subjtext2 = $spectsubj_item->subjcode;
                              }

                              if($spectsubj_item->q3 == 1){
                                    $item->q3 = 1;
                                    $item->q3subj = $spectsubj_item->subjid;;
                                    $item->subjtext3 = $spectsubj_item->subjcode;
                              }

                              if($spectsubj_item->q4 == 1){
                                    $item->q4 = 1;
                                    $item->q4subj = $spectsubj_item->subjid;;
                                    $item->subjtext4 = $spectsubj_item->subjcode;
                              }
                        }
                  }
            }

            return $students;

      }

      public static function subjects_studspec_create_all(Request $request){

            try{
                  $syid = $request->get('syid');
                  $subjid = $request->get('subjid');
                  $levelid = $request->get('levelid');
                  $sectionid = $request->get('sectionid');
                  $students =  self::all_student($syid,$subjid,$levelid,$sectionid);

                  foreach($students as $item){
                        $request->request->add(['studid' => $item->studid]);
                        self::subjects_studspec_create($request);
                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
                  

      }

      public static function subjects_studspec_create(Request $request){

            try{

                  $q1 = $request->get('q1');
                  $q2 = $request->get('q2');
                  $q3 = $request->get('q3');
                  $q4 = $request->get('q4');

                  // if($q1 == 0 && $q2 == 0&& $q3 == 0 && $q4 == 0){
                  //       return array((object)[
                  //             'status'=>0,
                  //             'data'=>'Subject is empty.'
                  //       ]);
                  // }

                  $validate = DB::table('subjects_studspec')
                                    ->where('studid',$request->get('studid'))
                                    ->where('subjid',$request->get('subjid'))
                                    ->where('syid',$request->get('syid'))
                                    ->where('deleted',0)
                                    ->count();

                  if($validate == 0){
                        $id = DB::table('subjects_studspec')
                                    ->insertGetId([
                                          'studid'=>$request->get('studid'),
                                          'subjid'=>$request->get('subjid'),
                                          'syid'=>$request->get('syid'),
                                          'q1'=>$q1,
                                          'q2'=>$q2,
                                          'q3'=>$q3,
                                          'q4'=>$q4,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                  }else{
                        // DB::table('subjects_studspec')
                        //       ->where('studid',$request->get('studid'))
                        //       ->where('subjid',$request->get('subjid'))
                        //       ->where('syid',$request->get('syid'))
                        //       ->where('deleted',0)
                        //       ->update([
                        //             'q1'=>$q1,
                        //             'q2'=>$q2,
                        //             'q3'=>$q3,
                        //             'q4'=>$q4,
                        //             'updatedby'=>auth()->user()->id,
                        //             'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        //       ]);

                        for($x = 1 ; $x <= 4; $x++){

                              $tempar = 'q'.$x;

                              if( $$tempar == 1){
                                    DB::table('subjects_studspec')
                                          ->where('studid',$request->get('studid'))
                                          ->where('subjid',$request->get('subjid'))
                                          ->where('syid',$request->get('syid'))
                                          ->where('deleted',0)
                                          ->update([
                                                'q'.$x=>1
                                          ]);
                              }else{
                                    DB::table('subjects_studspec')
                                    ->where('studid',$request->get('studid'))
                                    ->where('subjid',$request->get('subjid'))
                                    ->where('syid',$request->get('syid'))
                                    ->where('deleted',0)
                                    ->update([
                                          'q'.$x=>0
                                    ]);
                              }
                        }

                        // if( $q1 == 1){
                        //       DB::table('subjects_studspec')
                        //             ->where('studid',$request->get('studid'))
                        //             ->where('subjid',$request->get('subjid'))
                        //             ->where('syid',$request->get('syid'))
                        //             ->where('deleted',0)
                        //             ->update([
                        //                   'q1'=>1
                        //             ]);
                        // }

                        // if( $q2 == 1){
                        //       DB::table('subjects_studspec')
                        //             ->where('studid',$request->get('studid'))
                        //             ->where('subjid',$request->get('subjid'))
                        //             ->where('syid',$request->get('syid'))
                        //             ->where('deleted',0)
                        //             ->update([
                        //                   'q2'=>1
                                    
                        //             ]);
                        // }
                        // if( $q3 == 1){
                        //       DB::table('subjects_studspec')
                        //             ->where('studid',$request->get('studid'))
                        //             ->where('subjid',$request->get('subjid'))
                        //             ->where('syid',$request->get('syid'))
                        //             ->where('deleted',0)
                        //             ->update([
                        //                   'q3'=>1
                                    
                        //             ]);
                        // }
                        // if( $q4 == 1){
                        //       DB::table('subjects_studspec')
                        //             ->where('studid',$request->get('studid'))
                        //             ->where('subjid',$request->get('subjid'))
                        //             ->where('syid',$request->get('syid'))
                        //             ->where('deleted',0)
                        //             ->update([
                        //                   'q4'=>1
                        //             ]);
                        // }
                  }

                  for($x = 1 ; $x <= 4; $x++){
                        $tempar = 'q'.$x;

                        if( $$tempar == 1){
                              DB::table('subjects_studspec')
                                    ->where('studid',$request->get('studid'))
                                    ->where('subjid','!=',$request->get('subjid'))
                                    ->where('syid',$request->get('syid'))
                                    ->where('deleted',0)
                                    ->update([
                                          'q'.$x=>0
                                    ]);
                        }
                  }

                  // if($validate > 0){
                        // if( $q1 == 1){
                        //             DB::table('subjects_studspec')
                        //                   ->where('studid',$request->get('studid'))
                        //                   ->where('subjid','!=',$request->get('subjid'))
                        //                   ->where('syid',$request->get('syid'))
                        //                   ->where('deleted',0)
                        //                   ->update([
                        //                         'q1'=>0,
                        //                   ]);
                        //       }

                        // if( $q2 == 1){
                        //             DB::table('subjects_studspec')
                        //             ->where('studid',$request->get('studid'))
                        //             ->where('subjid','!=',$request->get('subjid'))
                        //             ->where('syid',$request->get('syid'))
                        //             ->where('deleted',0)
                        //             ->update([
                        //                   'q2'=>0,
                                    
                        //             ]);
                        // }
                        // if( $q3 == 1){
                        //             DB::table('subjects_studspec')
                        //             ->where('studid',$request->get('studid'))
                        //             ->where('subjid','!=',$request->get('subjid'))
                        //             ->where('syid',$request->get('syid'))
                        //             ->where('deleted',0)
                        //             ->update([
                        //                   'q3'=>0,
                                    
                        //             ]);
                        // }
                        // if( $q4 == 1){
                        //             DB::table('subjects_studspec')
                        //             ->where('studid',$request->get('studid'))
                        //             ->where('subjid','!=',$request->get('subjid'))
                        //             ->where('syid',$request->get('syid'))
                        //             ->where('deleted',0)
                        //             ->update([
                        //                   'q4'=>0,
                        //             ]);
                        // }
                        
                  // }

                  DB::table('subjects_studspec')
                        ->where('studid',$request->get('studid'))
                        ->where('subjid','!=',$request->get('subjid'))
                        ->where('syid',$request->get('syid'))
                        ->where('deleted',0)
                        ->where('q1',0)
                        ->where('q2',0)
                        ->where('q3',0)
                        ->where('q4',0)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  // $validate =  $validate = DB::table('subjects_studspec')
                  //                   ->where('studid',$request->get('studid'))
                  //                   ->where('syid',$request->get('syid'))
                  //                   ->where('deleted',0)
                  //                   ->get();

                  // $q1 = collect($validate)->where('q1',1)->count() > 0 ? 0 : $request->get('q1');
                  // $q2 = collect($validate)->where('q2',1)->count() > 0 ? 0 : $request->get('q2');
                  // $q3 = collect($validate)->where('q3',1)->count() > 0 ? 0 : $request->get('q3');
                  // $q4 = collect($validate)->where('q4',1)->count() > 0 ? 0 : $request->get('q4');

                  

                 

                  $syid = $request->get('syid');
                  $subjid = $request->get('subjid');

                  return array((object)[
                        'status'=>1,
                        // 'info'=>self::subjects_studspec($syid,$subjid),
                        'data'=>'Created Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
                  
      }

      public static function subjects_studspec_delete(Request $request){

            try{

                  DB::table('subjects_studspec')
                        // ->where('deleted',0)
                        ->where('studid',$request->get('id'))
                        // ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted successfully!'
                  ]);

            }catch(\Exception $e){

                  return self::store_error($e);
            }
                  
      }


      public static function store_error($e){

            DB::table('zerrorlogs')
            ->insert([
                        'error'=>$e,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            return array((object)[
                  'status'=>0,
                  'data'=>'Something went wrong!'
            ]);

      }

      public static function gradelevel_list(Request $request){

            $subjid = $request->get('subjid');
            $syid = $request->get('syid');
            $allsubj = array();

            if($subjid == null){
                  $allsubj = self::subjects($request);
                  $allsubj = $allsubj[0]->subjects;
            }

            $gradelevel = DB::table('gradelevel')
                              ->where('gradelevel.deleted',0)
                              ->orderBy('gradelevel.sortid')
                              ->select(
                                    'gradelevel.id',
                                    'levelname as text',
                                    'levelname'
                              )
                              ->join('subject_plot', function($join) use ($subjid,$allsubj,$syid) {
                                    $join->on('gradelevel.id','=','subject_plot.levelid');
                                    $join->where('subject_plot.deleted',0);
                                    if($subjid  != null){
                                          $join->where('subject_plot.subjid', $subjid);
                                    }else{
                                          $join->whereIn('subject_plot.subjid', collect($allsubj)->pluck('id'));
                                    }
                                    if($syid  != null){
                                          $join->where('subject_plot.syid', $syid);
                                    }
                              })
                              ->groupBy('gradelevel.id')
                              ->get();

            return $gradelevel;   
      }


      public static function sctnSelect_ajax($syid = null , $levelid= null, $search = null, $page =null){

            $section = DB::table('sectiondetail')
                              ->join('sections',function($join) use($levelid){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                                    if($levelid  != null){
                                    $join->where('sections.levelid',$levelid);
                                    }
                                    
                              })

                              ->join('gradelevel',function($join) use($levelid){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->join('subject_plot', function($join) {
                                    $join->on('gradelevel.id','=','subject_plot.levelid');
                                    $join->where('subject_plot.deleted',0);
                              })


                              ->where(function($query) use($search){
                                    if($search != null && $search != ""){
                                          $query->orWhere('sections.sectionname','like','%'.$search.'%');
                                    }
                              })
                              ->where('sectiondetail.deleted',0)
                              ->where('sectiondetail.syid',$syid)
                              ->select(
                                    'sections.id',
                                    'sections.sectionname as text'
                              )
                              ->take(10)
                              ->groupby('sectiondetail.id')
                              ->skip($page)
                              ->get();

            $section_count = DB::table('sectiondetail')
                              ->join('sections',function($join) use($levelid){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                                    $join->where('sections.levelid',$levelid);
                              })
                              ->where(function($query) use($search){
                                    if($search != null && $search != ""){
                                          $query->orWhere('sections.sectionname','like','%'.$search.'%');
                                    }
                              })
                              ->where('sectiondetail.deleted',0)
                              ->where('sectiondetail.syid',$syid)
                              ->count();


            return @json_encode((object)[
                  "results"=>$section,
                  "pagination"=>(object)[
                        "more"=>$section_count > ($page)  ? true :false
                  ],
                  "count_filtered"=>$section_count
            ]);
      }

      public static function sections(Request $request){


            $subjid = $request->get('subjid');
            $levelid = $request->get('levelid');
            $syid = $request->get('syid');

            $subjects = self::subjects($request);
            $subjects = $subjects[0]->subjects;


            if($subjid != null){
                  $subjects = collect($subjects)->where('id',$subjid)->values();
            }

            $gradelevel = DB::table('subject_plot')
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->whereIn('subjid',collect($subjects)->pluck('id'))
                              ->select('levelid')
                              ->select(
                                    'levelid'
                              )
                              ->distinct('levelid')
                              ->get();

            if($levelid != null){
                  $gradelevel = collect($gradelevel)->where('levelid',$levelid)->values();
            }

            $sections = DB::table('sectiondetail')
                              ->where('sectiondetail.deleted',0)
                              ->where('syid',$syid)
                              ->join('sections',function($join) use($gradelevel){
                                    $join->on('sectiondetail.sectionid','=','sections.id');
                                    $join->where('sections.deleted',0);
                                    $join->whereIn('sections.levelid',collect($gradelevel)->pluck('levelid'));
                              })
                              ->join('gradelevel',function($join) use($gradelevel){
                                    $join->on('sections.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->select(
                                    'levelid',
                                    'levelname',
                                    'sections.id',
                                    'sectionname as text',
                                    'sectionname'
                              )
                              ->get();

            return $sections;

      }

}
