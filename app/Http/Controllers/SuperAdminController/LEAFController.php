<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use PDF;

class LEAFController extends \App\Http\Controllers\Controller
{
    
      
      public static function students_list(){

            $students = DB::table('studinfo')
                              ->where('studinfo.deleted',0)
                              ->select(
                                    'studinfo.id',
                                    'studinfo.sid',
                                    'studinfo.lastname',
                                    'studinfo.firstname',
                                    'studinfo.middlename',
                                    'studinfo.suffix'
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
                  $item->text = $item->sid.' - '.$item->student;
            }
            
            return $students;


      }

      public static function get_leaf_detail(Request $request){

            $syid = $request->get('syid');
            $studid = $request->get('studid');
            
            if($studid == ""){
                  return "";
            }

            $checkIfExist =  DB::table('leasf')
                              ->where('studid',$studid)
                              ->Where('deleted',0)
                              ->where('syid',$syid)
                              ->count();

            if( $checkIfExist > 0){

                  $surveyAns = DB::table('leasf')
                                    ->select('leasf.*','studinfo.lrn as b2','studinfo.lastname as b3','studinfo.firstname as b4','studinfo.middlename as b5','studinfo.suffix as b6','studinfo.dob as b7','studinfo.gender as b9')
                                    ->join('studinfo',function($join){
                                          $join->on('leasf.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->where('leasf.studid',$studid)
                                    ->Where('leasf.deleted',0)
                                    ->where('leasf.syid',$syid)
                                    ->first();

                  $check_enrollement = DB::table('sh_enrolledstud')
                                          ->join('sh_strand',function($join){
                                                $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                                $join->where('sh_strand.deleted',0);
                                          })
                                          ->join('sh_track',function($join){
                                                $join->on('sh_strand.trackid','=','sh_track.id');
                                                $join->where('sh_track.deleted',0);
                                          })
										  ->where('studid',$studid)
                                          ->where('sh_enrolledstud.syid',$syid)
                                          ->where('sh_enrolledstud.deleted',0)
                                          ->select('trackname','strandname')
                                          ->first();

                  $surveyAns->a15 = null;
                  $surveyAns->a16 = null;

                  if(isset($check_enrollement->strandname)){
                        $surveyAns->a15 = $check_enrollement->trackname;
                        $surveyAns->a16 = $check_enrollement->strandname;
                  }
            }
            else{

                  $surveyAns = DB::table('studinfo')
                                          ->where('studinfo.id',$studid)
                                          ->where('studinfo.deleted',0)
                                          ->leftJoin('leasf',function($join) use($syid){
                                                $join->on('studinfo.id','=','leasf.studid');
                                                $join->where('studinfo.deleted',0);
                                                $join->where('leasf.syid',$syid);
                                          })
                                          ->select('leasf.*','studinfo.lrn as b2','studinfo.lastname as b3','studinfo.firstname as b4','studinfo.middlename as b5','studinfo.suffix as b6','studinfo.dob as b7','studinfo.gender as b9')
                                          ->first();

                  $check_enrollement = DB::table('sh_enrolledstud')
                        ->join('sh_strand',function($join){
                              $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                              $join->where('sh_strand.deleted',0);
                        })
                        ->join('sh_track',function($join){
                              $join->on('sh_strand.trackid','=','sh_track.id');
                              $join->where('sh_track.deleted',0);
                        })
						->where('studid',$studid)
                        ->where('sh_enrolledstud.syid',$syid)
                        ->where('sh_enrolledstud.deleted',0)
                        ->select('trackname','strandname')
                        ->first();

                  $surveyAns->a15 = null;
                  $surveyAns->a16 = null;

                  if(isset($check_enrollement->strandname)){
                        $surveyAns->a15 = $check_enrollement->trackname;
                        $surveyAns->a16 = $check_enrollement->strandname;
                  }

            }

            return $surveyAns;

      }
     

      public static function leaf_blade(Request $request){

            $schoolinfo = Db::table('schoolinfo')->first();
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $sy = DB::table('sy')->where('id',$syid)->first();
            $sem = DB::table('semester')->where('id',1)->first();
            $surveyAns = self::get_leaf_detail($request);
            
            if($studid == ""){
                  return "";
            }

            $checkIfExist =  DB::table('leasf')
                              ->where('studid',$studid)
                              ->Where('deleted',0)
                              ->where('syid',$sy->id)
                              ->count();

            $student = DB::table('studinfo')
                              ->where('studinfo.id',$studid)
                              ->join('gradelevel',function($join){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted','0');
                              })
                              ->leftJoin('sh_strand',function($join){
                                    $join->on('studinfo.strandid','=','sh_strand.id');
                                    $join->where('sh_strand.deleted','0');
                              })
                              ->leftJoin('sh_track',function($join){
                                    $join->on('sh_strand.trackid','=','sh_track.id');
                                    $join->where('sh_track.deleted','0');
                              })
                              ->leftJoin('ethnic',function($join){
                                    $join->on('studinfo.egid','=','ethnic.id');
                                    $join->where('ethnic.deleted','0');
                              })
                              ->leftJoin('religion',function($join){
                                    $join->on('studinfo.religionid','=','religion.id');
                                    $join->where('religion.deleted','0');
                              })
                              ->leftJoin('mothertongue',function($join){
                                    $join->on('studinfo.mtid','=','mothertongue.id');
                                    $join->where('mothertongue.deleted','0');
                              })
                              ->select(
                                    'religion.religionname as studrel',
                                    'mothertongue.mtname as studmt',
                                    'ethnic.egname as studethnic',
                                    'studinfo.*',
                                    'levelname',
                                    'strandname',
                                    'trackname'
                              )
                              ->first();

            if($request->has('export'))
            {
                  $pdf = PDF::loadview('registrar/pdf/pdf_leasf', compact('student','surveyAns','checkIfExist'))->setPaper('portrait');
                  return $pdf->stream('Student Summary.pdf');
            }else{
                  return view('superadmin.pages.leaf_detail')
                        ->with('student',$student)
                        ->with('sy',$sy)
                        ->with('sem',$sem)
                        ->with('schoolinfo',$schoolinfo)
                        ->with('surveyAns',$surveyAns)
                        ->with('checkIfExist',$checkIfExist);
            }
          

      }

      public static function submit_leaf(Request $request){

            $studid = $request->get('studid');

            $student = DB::table('studinfo')
                        ->where('studinfo.id',$studid)
                        ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                        })
                        ->leftJoin('sh_strand',function($join){
                            $join->on('studinfo.strandid','=','sh_strand.id');
                            $join->where('sh_strand.deleted','0');
                        })
                        ->select(
                            'lrn',
                            'firstname',
                            'lastname',
                            'middlename',
                            'levelname',
                            'strandname',
                            'studinfo.id',
                            'suffix',
                            'dob',
                            'gender'
                            )
                        ->first();
        
            $sy = DB::table('sy')->where('id',$request->get('syid'))->first();
            $sem = DB::table('semester')->where('isactive','1')->first();
            
            $d1 = '';
            $d3 = '';
            $d6 = '';
            $d8 = '';
            $d4 = '';
            $d7 = '';

            if($request->get('d1') != null){
                  foreach($request->get('d1') as $item){
                  $d1 .=' '.$item;
                  }
            }

            if($request->get('d3') != null){
                  foreach($request->get('d3') as $item){
                  $d3 .=' '.$item;
                  }
            }

            if($request->get('d4') != null){
                  foreach($request->get('d4') as $item){
                  $d4 .=' '.$item;
                  }
            }

            if($request->get('d6') != null){
                  foreach($request->get('d6') as $item){
                  $d6 .=' '.$item;
                  }
            }

            if($request->get('d7') != null){
                  foreach($request->get('d7') as $item){
                  $d7 .=' '.$item;
                  }
            }

            if($request->get('d8') != null){
                  foreach($request->get('d8') as $item){
                  $d8 .=' '.$item;
                  }
            }

            $syid =DB::table('sy')->where('id',$request->get('syid'))->first()->id;

            DB::table('leasf')
                  ->where('deleted',0)
                  ->where('studid',$studid)
                  ->where('syid',$syid)
                  ->take(1)
                  ->update([
                        'deleted'=>1,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);


            DB::table('studinfo')
                  ->where('deleted',0)
                  ->where('id',$studid)
                  ->take(1)
                  ->update([
                        'street'=>$request->get('b18'),
                        'barangay'=>$request->get('b19'),
                        'city'=>$request->get('b20'),
                        'province'=>$request->get('b21'),
                        'fathername'=>$request->get('c1'),
                        'fcontactno'=>$request->get('c5'),
                        'mothername'=>$request->get('c7'),
                        'mcontactno'=>$request->get('c11'),
                        'guardianname'=>$request->get('c13'),
                        'gcontactno'=>$request->get('c17'),
                        'religionname'=>$request->get('b13'),
                        'mtname'=>$request->get('b12'),
                        'mtname'=>$request->get('b12'),
                        'lastschoolatt'=>$request->get('a7'),
                        'lastschoolsy'=>$request->get('a6'),
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  ]);


            DB::table('leasf')->insert([
                  'studid'=>$studid,
                  'createdby'=>auth()->user()->id,
                  'a1'=>$syid,
                  'a2'=>$request->get('a2'),
                  'a3'=>$request->get('a3'),
                  'a4'=>$request->get('a4'),
                  'a5'=>$request->get('a5'),
                  'a6'=>$request->get('a6'),
                  'a7'=>ucwords($request->get('a7')),
                  'a8'=>$request->get('a8'),
                  'a9'=>$request->get('a9'),
                  'a10'=>$request->get('a10'),
                  'a11'=>$request->get('a11'),
                  'a12'=>ucwords($request->get('a12')),
                  'a13'=>$request->get('a13'),
                  'a14'=>$request->get('a14'),
                  'a15'=>$request->get('a15'),
                  'a16'=>$request->get('a16'),
                  'b1'=>$request->get('b1'),
                  'b10'=>$request->get('b10'),
                  'b11'=>$request->get('b11'),
                  'b12'=>$request->get('b12'),
                  'b13'=>$request->get('b13'),
                  'b14'=>$request->get('b14'),
                  'b15'=>$request->get('b15'),
                  'b16'=>$request->get('b16'),
                  'b17'=>$request->get('b17'),
                  'b18'=>$request->get('b18'),
                  'b19'=>$request->get('b19'),
                  'b20'=>$request->get('b20'),
                  'b21'=>$request->get('b21'),
                  'b22'=>$request->get('b22'),
                  'b23'=>$request->get('b23'),
                  'c1'=>$request->get('c1'),
                  'c2'=>$request->get('c2'),
                  'c3'=>$request->get('c3'),
                  'c4'=>$request->get('c4'),
                  'c5'=>$request->get('c5'),
                  'c7'=>$request->get('c7'),
                  'c8'=>$request->get('c8'),
                  'c9'=>$request->get('c9'),
                  'c10'=>$request->get('c10'),
                  'c11'=>$request->get('c11'),
                  'c13'=>$request->get('c13'),
                  'c14'=>$request->get('c14'),
                  'c15'=>$request->get('c15'),
                  'c16'=>$request->get('c16'),
                  'c17'=>$request->get('c17'),
                  'd1'=>$d1,
                  'd2'=>$request->get('d2'),
                  'd3'=>$d3,
                  'd4'=>$d4,
                  'd5'=>$request->get('d5'),
                  'd6'=>$d6,
                  'd7'=>$d7,
                  'd8'=>$d8,
                  'd4others'=>$request->get('d4others'),
                  'd7others'=>$request->get('d7others'),
                  'd8others'=>$request->get('d8others'),
                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD'),
                  'syid'=>$syid,
                  'deleted'=>0
                  ]);


            toast('Submitted Successfully!','success')->autoClose(2000)->toToast($position = 'top-right');
            return redirect('/registrar/leaf?syid='.$syid.'&studid='.$studid);
            // return back();


      }

}
