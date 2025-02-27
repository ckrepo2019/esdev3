<?php

namespace App\Models\GradeSetup;
use DB;

use Illuminate\Database\Eloquent\Model;

class GradeSetupProccess extends Model
{

     public static function update_grade_setup(
          $gsid = null,
          $ww = null,
          $pt = null,
          $qa = null,
          $syid = null,
          $levelid = null,
          $subjid = null
     ){

          if($gsid == null){

               try{


                    foreach($levelid as $item){

                         
                         if($item == 14 || $item == 15){
                              $acadprogid = 5;
                         }else{
                              $acadprogid = null;
                         }
                         
                         DB::table('gradessetup')
                                   ->where('syid',$syid)
                                   ->where('levelid',$item)
                                   ->where('subjid',$subjid)
                                   ->where('deleted',0)
                                   ->take(1)
                                   ->update([
                                        'deleted'=>1,
                                        'deletedby'=>auth()->user()->id,
                                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                   ]);


                         $setup_id = DB::table('gradessetup')
                         ->insertGetId([
                                   'deleted'=>0,
                                   'syid'=>$syid,
                                   'levelid'=>$item,
                                   'subjid'=>$subjid,
                                   'writtenworks'=>$ww,
                                   'performancetask'=>$pt,
                                   'qassesment'=>$qa,
                                   'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                   'createdby'=>auth()->user()->id,
                                   'first'=>1,
                                   'second'=>1,
                                   'fourth'=>1,
                                   'third'=>1,
                         ]);
     
                         $info = \App\Models\GradeSetup\GradeSetupData::get_grade_setup($syid,$subjid,$acadprogid,null);
                    }

                    return array((object)[
                          'status'=>1,
                          'data'=>'Updated Successfully!',
                          'info'=>$info,
                          'id'=>$setup_id
                    ]);
  
              }catch(\Exception $e){
                    return self::store_error($e);
              }

          }else{

               return array((object)[
                                        'status'=>0,
                                        'data'=>'Updated Successfully!'
                                   ]);

               try{

                    return ;

                    DB::table('gradessetup')
                          ->take(1)
                          ->where('id',$gsid)
                          ->where('deleted',0)
                          ->update([
                                'writtenworks'=>$ww,
                                'performancetask'=>$pt,
                                'qassesment'=>$qa,
                                'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                'updatedby'=>auth()->user()->id,
                                'first'=>1,
                                'second'=>1,
                                'fourth'=>1,
                                'third'=>1,
                          ]);

                    $info = \App\Models\GradeSetup\GradeSetupData::get_grade_setup($syid,$subjid,$acadprogid,null);
  
                    return array((object)[
                          'status'=>1,
                          'info'=>$info,
                          'data'=>'Updated Successfully!'
                    ]);
  
              }catch(\Exception $e){
                    return self::store_error($e);
              }


          }

     }

     public static function gradestatus_update_quarter(
          $setupid = null,
          $quarter = null,
          $status = null
     ){


          try{
               $temp_quarter = null;
               if($quarter == 1){
                    $temp_quarter = 'first';
               }elseif($quarter == 2){
                    $temp_quarter = 'second';
               }elseif($quarter == 3){
                    $temp_quarter = 'third';
               }elseif($quarter == 4){
                    $temp_quarter = 'fourth';
               }
               

               DB::table('gradessetup')
                         ->take(1)
                         ->where('id',$setupid)
                         ->where('deleted',0)
                         ->update([
                              $temp_quarter=>$status,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                         ]);

               return array((object)[
                         'status'=>1,
                         'data'=>'Updated Successfully!'
               ]);

          }catch(\Exception $e){
               return self::store_error($e);
          }


     }


     public static function delete_grade_setup(
          $gsid = null,
          $subjid = null,
          $syid = null,
          $acadprogid = null
     ){

          try{

               DB::table('gradessetup')
                    ->where('deleted',0)
                    ->where('id',$gsid)
                    ->take(1)
                    ->update([
                              'deleted'=>1,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'deletedby'=>auth()->user()->id
                         ]);

               $info = \App\Models\GradeSetup\GradeSetupData::get_grade_setup($syid,$subjid,$acadprogid,null);

               return array((object)[
                         'status'=>1,
                         'info'=>$info,
                         'data'=>'Updated Successfully!'
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
    
      
}
