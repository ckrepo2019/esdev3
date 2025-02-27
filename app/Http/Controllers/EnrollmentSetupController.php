<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Session;

class EnrollmentSetupController extends Controller
{

      public static function get_acad(){

            $syid = DB::table('sy')
                        ->where('isactive',1)
                        ->first()
                        ->id;

            if(auth()->user()->type == 17){
                  $acadprog = DB::table('academicprogram')
                                          ->select(
                                                'id',
                                                'progname as text',
                                                'academicprogram.acadprogcode'
                                          )
                                          ->get();
            }
            else{

                  $teacherid = DB::table('teacher')
                                    ->where('tid',auth()->user()->email)
                                    ->select('id')
                                    ->first()
                                    ->id;

                  $acadprog = DB::table('teacheracadprog')
                                    ->where('teacherid',$teacherid)
                                    ->where('acadprogutype',Session::get('currentPortal'))
                                    ->where('deleted',0)
                                    ->where('syid',$syid)
                                    ->join('academicprogram',function($join){
                                          $join->on('teacheracadprog.acadprogid','=','academicprogram.id');
                                    })
                                    ->select(
                                          'acadprogid as id',
                                          'progname as text',
                                          'academicprogram.acadprogcode'
                                    )
                                    ->distinct('acadprogid')
                                    
                                    ->get();
            }


            // $acadprog_list = array();
            // foreach($acadprog as $item){
            //       array_push($acadprog_list,$item->id);
            // }

            return $acadprog;

      }

      public static function admission_type(){

            $admission_type = DB::table('early_enrollment_setup_type')
                              ->where('deleted',0)
                              ->select(
                                    'id',
                                    'description as text',
                                    'description'
                              )
                              ->get();

            return $admission_type;
      }


      public static function admission_type_create(Request $request){

            try{
                  $description = $request->get('description');
                  $check = DB::table('early_enrollment_setup_type')
                              ->where('description',$description)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'data'=>'Admission Type already exist!'
                        ]);
                  }

                  DB::table('early_enrollment_setup_type')
                        ->insert([
                              'description'=>$description,
                              'deleted'=>0,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'createdby'=>auth()->user()->id
                        ]);

                  $admission_type = self::admission_type();

                  return array((object)[
                        'status'=>1,
                        'data'=>'Admission Type created!',
                        'type_list'=> $admission_type
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }
      
      public static function admission_type_update(Request $request){

    

            try{

                  $description = $request->get('description');
                  $id = $request->get('id');

                  $check = DB::table('early_enrollment_setup_type')
                              ->where('description',$description)
                              ->where('id','!=',$id)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'data'=>'Already Exist!'
                        ]);
                  }

                  $check = DB::table('early_enrollment_setup')
                              ->where('early_enrollment_setup.type',$id)
                              ->where('early_enrollment_setup.deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'data'=>'Already Used!'
                        ]);
                  }

                  DB::table('early_enrollment_setup_type')
                        ->where('id',$id)
                        ->take(1)
                        ->update([
                              'description'=>$description,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  $admission_type = self::admission_type();

                  return array((object)[
                        'status'=>1,
                        'data'=>'Admission Type Updated!',
                        'type_list'=> $admission_type
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function admission_type_delete(Request $request){
            try{

                  $id = $request->get('id');

                  $check = DB::table('early_enrollment_setup')
                              ->where('early_enrollment_setup.type',$id)
                              ->where('early_enrollment_setup.deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'data'=>'Already Used!'
                        ]);
                  }

                  DB::table('early_enrollment_setup_type')
                        ->where('id',$id)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'deletedby'=>auth()->user()->id
                        ]);

                  DB::table('early_enrollment_setup')
                        ->where('type',$id)
                        ->update([
                              'deleted'=>1,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'deletedby'=>auth()->user()->id
                        ]);

                  

                  $admission_type = self::admission_type();

                  return array((object)[
                        'status'=>1,
                        'data'=>'Admission Type Deleted!',
                        'type_list'=> $admission_type
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }


    public static function enrollmentsetup(Request $request){
        return view('superadmin.pages.enrollmentsetup.enrollmentsetup');
    }

    public static function list(Request $request){
      //   if($request->ajax())
	// 	{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $acadprogid = $request->get('acadprogid');
            $id = $request->get('id');
            $active = $request->get('active');
            $type = $request->get('type');

            if($acadprogid == 2 || $acadprogid == 3 || $acadprogid == 4){
                  $semid = 1;
            }

            return self::enrollment_setup($id,$syid,$acadprogid,$active,$semid,$type);
      //   }
    }
    public static function create(Request $request){
        if($request->ajax())
		{
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $acadprogid = $request->get('acadprogid');
            $enrollmentstart = $request->get('enrollmentstart');
            $enrollmentend = $request->get('enrollmentend');
            $studtype = $request->get('studtype');
            $addtype = $request->get('addtype');
            $enrollmenttype = $request->get('enrollmenttype');

            // return $request->all();
            return self::enrollmentsetup_create($syid,$semid,$acadprogid,$enrollmentstart,$enrollmentend,$studtype,$addtype,$enrollmenttype);
        }
    }
    public static function update(Request $request){
            if($request->ajax())
		{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $acadprogid = $request->get('acadprogid');
                  $enrollmentstart = $request->get('enrollmentstart');
                  $enrollmentend = $request->get('enrollmentend');
                  $studtype = $request->get('studtype');
                  $addtype = $request->get('addtype');
                  $id = $request->get('id');
                  $enrollmenttype = $request->get('enrollmenttype');
                  return self::enrollmentsetup_update($id,$syid,$semid,$acadprogid,$enrollmentstart,$enrollmentend,$studtype,$addtype,$enrollmenttype);
             }
    }
    public static function delete(Request $request){
        if($request->ajax())
		{
            $id = $request->get('id');
            return self::enrollmentsetup_delete($id);
        }
    }

    public static function update_active(Request $request){
        $id = $request->get('id');
        return self::enrollmentsetup_update_active($id);
    }
    public static function update_end(Request $request){
      $id = $request->get('id');
      return self::enrollmentsetup_update_end($id);
  }

    public static function check(Request $request){
        $id = $request->get('id');
        $acadprogid = $request->get('acadprogid');
        return self::check_enrollment_setup($id,$acadprogid);
    }

    public static function enrollment_setup($id = null, $syid = null, $acadprogid = null, $active = null, $semid = null, $enrollmenttype = null ){

      if (\Auth::check()) {
            if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
                  $temp_acad = self::get_acad();
                  $temp_acad = collect($temp_acad)->pluck('id');
            }else{
                  $temp_acad = [2,3,4,5,6,8];
            }

            $acad_porg = DB::table('academicprogram')
                               ->whereIn('id',$temp_acad)
                              ->get();

            // foreach($acad_porg as $item){

            //       $check = DB::table('early_enrollment_setup')
            //                   ->where('acadprogid',$item->id)
            //                   ->where('deleted',0)
            //                   ->where('type',$enrollmenttype)
            //                   ->where('syid',$syid)
            //                   ->count();

            //       if($check == 0 ){
            //             DB::table('early_enrollment_setup')
            //                   ->insert([
            //                         'acadprogid'=>$item->id,
            //                         'enrollmentstart'=>\Carbon\Carbon::now('Asia/Manila'),
            //                         'enrollmentend'=>\Carbon\Carbon::now('Asia/Manila'),
            //                         'syid'=>$syid,
            //                         'admission_studtype'=>0,
            //                         'type'=>$enrollmenttype,
            //                         'createdby'=>auth()->user()->id,
            //                         'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
            //                   ]);
            //       }
            // }            

      }else{
            $temp_acad = [2,3,4,5,6,8];
      }

     
    
        $enrollmentsetup = DB::table('early_enrollment_setup');  
        
        
        if($id != null){
              $enrollmentsetup = $enrollmentsetup->where('early_enrollment_setup.id',$id);
        }
        if($syid != null){
              $enrollmentsetup = $enrollmentsetup->where('early_enrollment_setup.syid',$syid);
        }
        if($active != null){
            $enrollmentsetup = $enrollmentsetup->where('early_enrollment_setup.isactive',1);
        }
        if($acadprogid != null){
            $enrollmentsetup = $enrollmentsetup->where('early_enrollment_setup.acadprogid',$acadprogid);
        }
        if($enrollmenttype != null){
            $enrollmenttype = $enrollmentsetup->where('early_enrollment_setup.type',$enrollmenttype);
        }
        $enrollmentsetup = $enrollmentsetup->join('sy',function($join){
                                                  $join->on('early_enrollment_setup.syid','=','sy.id');
                                            })
                                            ->leftJoin('semester',function($join){
                                                $join->on('early_enrollment_setup.semid','=','semester.id');
                                            })
                                            ->join('early_enrollment_setup_type',function($join){
                                                  $join->on('early_enrollment_setup.type','=','early_enrollment_setup_type.id');
                                            })
                                            ->join('academicprogram',function($join) use($temp_acad){
                                                  $join->on('early_enrollment_setup.acadprogid','=','academicprogram.id');
                                                  $join->whereIn('academicprogram.id',$temp_acad);
                                            })
                                            ->where('early_enrollment_setup.deleted',0)
                                            ->select(
                                                  'early_enrollment_setup.*',
                                                  'description',
                                                  'sydesc',
                                                  'progname',
                                                  'semester'
                                            )
                                            ->orderBy('acadprogid','asc')
                                            ->get();

            $current_date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('DD MMMM, YYYY');

            foreach( $enrollmentsetup as $item){

                  $item->enrollmentstart = \Carbon\Carbon::create($item->enrollmentstart)->isoFormat('MMMM DD, YYYY');
                  $item->enrollmentend = \Carbon\Carbon::create($item->enrollmentend)->isoFormat('MMMM DD, YYYY');

                  $item->enrollmentstart_format1 = \Carbon\Carbon::create($item->enrollmentstart)->isoFormat('YYYY-MM-DD');
                  $item->enrollmentend_format1 = \Carbon\Carbon::create($item->enrollmentend)->isoFormat('YYYY-MM-DD');

                  $item->enrollmentstart_format2 = \Carbon\Carbon::create($item->enrollmentstart)->isoFormat('DD MMMM, YYYY');
                  $item->enrollmentend_format2 = \Carbon\Carbon::create($item->enrollmentend)->isoFormat('DD MMMM, YYYY');

                  $current = \Carbon\Carbon::createFromFormat('d F, Y' , $current_date);
                  $start = \Carbon\Carbon::createFromFormat('d F, Y', $item->enrollmentstart_format2);
                  $end = \Carbon\Carbon::createFromFormat('d F, Y', $item->enrollmentend_format2);

                  if($start->lte($current) && $end->gte($current)){
                        $item->status = "On Going";
                  }else if($start->gt($current)){
                        $item->status = "Not Yet Started";
                  }else{
                        if($item->admission_ended == 0){
                              DB::table('early_enrollment_setup')
                                    ->where('id',$item->id)
                                    ->take(1)
                                    ->update([
                                          'isactive'=>0,
                                          'admission_ended'=>1,
                                          'admission_endeddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              $item->admission_ended = 1;
                              $item->isactive = 0;
                        }
                        $item->status = "Ended";
                  }

            }

        return $enrollmentsetup ;

    }

    public static function check_enrollment_setup($id = null , $acadprogid = null){

        $enrollmentsetup = DB::table('early_enrollment_setup')      
                                ->where('early_enrollment_setup.deleted',0)
                                ->join('sy',function($join){
                                      $join->on('early_enrollment_setup.syid','=','sy.id');
                                })
                                ->join('semester',function($join){
                                      $join->on('early_enrollment_setup.semid','=','semester.id');
                                });

        if($id != null){
              $enrollmentsetup = $enrollmentsetup->where('early_enrollment_setup.id',$id);
        }
        if($acadprogid != null){
              $enrollmentsetup = $enrollmentsetup->where('early_enrollment_setup.acadprogid',$acadprogid);
        }
              
        $enrollmentsetup = $enrollmentsetup->where('early_enrollment_setup.isactive',1)
                                ->get();

        if(count($enrollmentsetup) > 0){

            $otherDate = \Carbon\Carbon::now()->subMinutes(10);
              $nowDate = \Carbon\Carbon::now();
              $result = $nowDate->gt($otherDate);
              $todate = \Carbon\Carbon::now('Asia/Manila');
              $enrollmentstart = \Carbon\Carbon::create($enrollmentsetup[0]->enrollmentstart);
              $enrollmentend = \Carbon\Carbon::create($enrollmentsetup[0]->enrollmentend);
             
              if(!$todate->gt($enrollmentstart)){
                    return array((object)[
                          'status'=>0,
                          'data'=>$enrollmentsetup,
                          'message'=>'School Year ' . $enrollmentsetup[0]->sydesc . ( $enrollmentsetup[0]->type == 2 ? ' Early ' : ' Regular ' ) .' Pre-Enrollment / Pre-Registration will start on '. $enrollmentstart->isoFormat('MMMM DD, YYYY')
                    ]); 
              }
              else if($todate->gt($enrollmentend)){
                    return array((object)[
                          'status'=>0,
                          'data'=>$enrollmentsetup,
                          'message'=>'School Year ' .  $enrollmentsetup[0]->sydesc . ( $enrollmentsetup[0]->type == 2 ? ' Early ' : ' Regulary ' ). ' Pre-Enrollment / Pre-Registration ended last '. $enrollmentend->isoFormat('MMMM DD, YYYY')
                    ]); 
              }
              else{

                    if($enrollmentsetup[0]->type == 1){
                          return array((object)[
                                'status'=>1,
                                'data'=>$enrollmentsetup,
                                'message'=>'School Year ' . $enrollmentsetup[0]->sydesc .' Regular Enrollment / Pre-Registration is now open.'
                          ]); 
                    }
                    else if($enrollmentsetup[0]->type == 2){
                          return array((object)[
                                'status'=>1,
                                'data'=>$enrollmentsetup,
                                'message'=>'School Year ' . $enrollmentsetup[0]->sydesc .' Early Enrollment / Pre-Registration is now open.'
                          ]); 
                    }
                   
              }
              return collect( $todate->gt($enrollmentstart));
        }else{

              $cadname = '';

              if($acadprogid == 2){
                    $cadname = 'Pre-school';
              }else if($acadprogid == 3){
                    $cadname = 'Grade School';
              }else if($acadprogid == 4){
                    $cadname = 'Junior High School';
              }else if($acadprogid == 5){
                    $cadname = 'Senior High School';
              }
              else if($acadprogid == 6){
                    $cadname = 'College';
              }

              return array((object)[
                    'status'=>0,
                    'data'=>[],
                    'message'=>$cadname . ' Pre-Enrollment / Pre-Registration is not yet available!'
              ]);

        }
        
        return $enrollmentsetup ;

    }

    public static function enrollmentsetup_create(
        $syid = null,
        $semid = null,
        $acadprogid = null,
        $enrollmentstart = null,
        $enrollmentend = null,
        $studtype = null,
        $addtype = null,
        $enrollmenttype = 1
        ){

            try{

                  if($acadprogid == 3 || $acadprogid == 4 || $acadprogid == 2){
                        $semid = 1;
                  }

                  $id = DB::table('early_enrollment_setup')
                     ->insertGetId([
                           'syid'=>$syid,
                           'semid'=>$semid,
                           'acadprogid'=>$acadprogid,
                           'enrollmentstart'=>$enrollmentstart,
                           'enrollmentend'=>$enrollmentend,
                           'type'=>$addtype,
                           'admission_studtype'=>$studtype,
                           'deleted'=>0,
                           'collegeentype'=> $enrollmenttype,
                           'createdby'=>auth()->user()->id,
                           'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                     ]);
                  
                  $checkforActive = DB::table('early_enrollment_setup')
                                          ->where('isactive',1)
                                          ->where('deleted',0)
                                          ->where('acadprogid',$acadprogid)
                                          ->where('admission_ended',0)
                                          ->count();

                  if($checkforActive == 0 ){
                        self::enrollmentsetup_update_active($id);
                  }

                  $temp_data = self::enrollment_setup();
                 


                  return array((object)[
                        'info'=>$temp_data,
                        'status'=>1,
                        'data'=>'Created Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

    }

    public static function enrollmentsetup_update(
            $id = null,
            $syid = null,
            $semid = null,
            $acadprogid = null,
            $enrollmentstart = null,
            $enrollmentend = null,
            $studtype = null,
            $addtype = null,
            $enrollmenttype = 1
         ){

         try{

            if($acadprogid == 3 || $acadprogid == 4 || $acadprogid == 2){
                  $semid = 1;
            }

                  DB::table('early_enrollment_setup')
                        ->take(1)
                        ->where('id',$id)
                        ->update([
                              'syid'=>$syid,
                              'semid'=>$semid,
                              'acadprogid'=>$acadprogid,
                              'enrollmentstart'=>$enrollmentstart,
                              'enrollmentend'=>$enrollmentend,
                              'type'=>$addtype,
                              'admission_studtype'=>$studtype,
                              'collegeentype'=> $enrollmenttype,
                              'deleted'=>0,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $temp_data = self::enrollment_setup();

               return array((object)[
                        'info'=>$temp_data,
                        'status'=>1,
                        'data'=>'Admission Setup Updated!'
               ]);

         }catch(\Exception $e){
               return self::store_error($e);
         }

    }

    public static function enrollmentsetup_delete(
            $id = null
    ){

         try{

                  DB::table('early_enrollment_setup')
                     ->take(1)
                     ->where('id',$id)
                     ->where('isactive',0)
                     ->update([
                           'deleted'=>1,
                           'deletedby'=>auth()->user()->id,
                           'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                     ]);

                  $temp_data = self::enrollment_setup();

               return array((object)[
                     'info'=>$temp_data,
                     'status'=>1,
                     'data'=>'Deleted Successfully!'
               ]);

         }catch(\Exception $e){
               return self::store_error($e);
         }

    }


      public static function enrollmentsetup_update_active(
            $id = null
      ){

                  try{

                        DB::table('early_enrollment_setup')
                              ->take(1)
                              ->where('id',$id)
                              ->where('admission_ended',0)
                              ->update([
                                    'isactive'=>1,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        $temp_data = self::enrollment_setup();
                  
                        return array((object)[
                              'info'=>$temp_data,
                              'status'=>1,
                              'data'=>'Enrollment Setup Activted'
                        ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function enrollmentsetup_update_end(
            $id = null
      ){
                  try{
                        DB::table('early_enrollment_setup')
                        ->take(1)
                        ->where('id',$id)
                        ->update([
                              'isactive'=>0,
                              'admission_ended'=>1,
                              'admission_endeddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                        $temp_data = self::enrollment_setup();
                  
                        return array((object)[
                              'info'=>$temp_data,
                              'status'=>1,
                              'data'=>'Enrollment Ended'
                        ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function logs(){
            return DB::table('logs')->where('module',2)->get();
      }

      public static function create_logs($message = null,$id = null){
            DB::table('logs') 
              ->insert([
                   'module'=>2,
                   'dataid'=>$id,
                   'message'=>$message,
                   'createdby'=>auth()->user()->id,
                   'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
             ]);
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

     

}
