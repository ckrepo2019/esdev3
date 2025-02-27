<?php

namespace App\Models\Grading;
use DB;
use \Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class GradingSystem extends Model
{
   
      public static function create_grading_system(
            $type = null,
            $description = null,
            $acadprogid = null,
            $isactive = null,
            $specification = null,
            $trackid = null
      ){

            try{

                  DB::table('grading_system')
                        ->insert([
                              'type'=>$type,
                              'description'=>$description,
                              'acadprogid'=>$acadprogid,
                              'isactive'=>$isactive,
                              'specification'=>$specification,
                              'trackid'=>$trackid,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'createdby'=>auth()->user()->id
                        ]);

                  return 1;


            }catch(\Exception $e){

                  DB::table('zerrorlogs')
                                    ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                        return 0;

            }

      }

      public static function update_grading_system(
            $id = null,
            $type = null,
            $description = null,
            $acadprogid = null,
            $isactive = null,
            $specification = null,
            $trackid = null
      ){

            try{

                  DB::table('grading_system')
                        ->where('id',$id)
                        ->update([
                              'type'=>$type,
                              'description'=>$description,
                              'acadprogid'=>$acadprogid,
                              'isactive'=>$isactive,
                              'specification'=>$specification,
                              'trackid'=>$trackid,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  return 1;


            }catch(\Exception $e){

                  DB::table('zerrorlogs')
                                    ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                        return 0;

            }


      }

      public static function delete_grading_system(
            $id = null
      ){

            try{

                  DB::table('grading_system')
                        ->where('id',$id)
                        ->update([
                              'deleted'=>1,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'deletedby'=>auth()->user()->id
                        ]);


                  DB::table('grading_system_detail')
                              ->where('headerid',$id)
                              ->update([
                                    'deleted'=>1,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'deletedby'=>auth()->user()->id
                              ]);

                  return 1;


            }catch(\Exception $e){

                  DB::table('zerrorlogs')
                                    ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                        return 0;

            }

      }
            
      public static function get_active_grade_grading_setup($acadprogid){

            $grading_system =  DB::table('grading_system')
                                          ->where('acadprogid',$acadprogid)
                                          ->where('isactive',1)
                                          ->where('specification',1)
                                          ->where('deleted',0)
                                          ->get();

            return   $grading_system;
            
      }

      public static function get_active_grade_grading_setup_tvl(){

            $grading_system =  DB::table('grading_system')
                                          ->where('acadprogid',5)
                                          ->where('isactive',1)
                                          ->where('specification',1)
                                          ->where('trackid',2)
                                          ->where('deleted',0)
                                          ->get();

            return   $grading_system;
            
      }

      public static function get_active_grade_grading_setup_acad(){

            $grading_system =  DB::table('grading_system')
                                          ->where('acadprogid',5)
                                          ->where('isactive',1)
                                          ->where('specification',1)
                                          ->where('trackid',1)
                                          ->where('deleted',0)
                                          ->get();

            return   $grading_system;
            
      }

      public static function get_active_core_value_setup($acadprogid){

            $grading_system =  DB::table('grading_system')
                                          ->where('acadprogid',$acadprogid)
                                          ->where('isactive',1)
                                          ->where('specification',2)
                                          ->where('deleted',0)
                                          ->get();

            return   $grading_system;
            
      }



     

      public static function get_grading_system_by_id($id){

            $grading_system =  DB::table('grading_system')
                                          ->where('id',$id)
                                          ->get();

            return   $grading_system;
            
      }

      


      public static function evaluate_grading_system_preschool( $gsid = null){

            if( $gsid != '' || $gsid != null ){
                  
                  $grading_system  = self::get_grading_system_by_id($gsid);

            }else{

                  $grading_system  = self::get_active_grade_grading_setup(2);
                  
            }

            if(count($grading_system ) == 1){

                  $gsdetail =  DB::table('grading_system_detail')
                                    ->where('headerid',$grading_system[0]->id)
                                    ->where('deleted',0)
                                    ->count();

                  if($gsdetail == 0){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"This grading system does not contain any detail. \n Please add details to continue."
                        ]);

                        return $data;

                  }

            }
            // else if(count($grading_system) > 1){

            //       $data =  array((object)[
            //             'status'=>0,
            //             'data'=>"Mutiple grading system is active."
            //       ]);

            //       return $data;

            // }
            else if(count($grading_system) == 0){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"No available grading system for pre-school."
                  ]);

                  return $data;

            }
            
            return    $data =  array((object)[
                              'status'=>1,
                              'data'=> $grading_system
                        ]);


      }


      public static function evaluate_grading_system_gradeschool( $gsid = null){

            if( $gsid != '' || $gsid != null ){
                  
                  $grading_system  = self::get_grading_system_by_id($gsid);

            }else{

                  $grading_system  = self::get_active_grade_grading_setup(3);
                  
            }

            if(count($grading_system ) == 1){

                  $gsdetail =  DB::table('grading_system_detail')
                                    ->where('headerid',$grading_system[0]->id)
                                    ->where('deleted',0)
                                    ->count();

                  if($gsdetail == 0){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"This grading system does not contain any detail. \n Please add details to continue."
                        ]);

                        return $data;

                  }

            }
            else if(count($grading_system) > 1){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"Mutiple grading system is active."
                  ]);

                  return $data;

            }
            else if(count($grading_system) == 0){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"No available grading system for grade school."
                  ]);

                  return $data;

            }
            
            return    $data =  array((object)[
                              'status'=>1,
                              'data'=> $grading_system
                        ]);


      }

      public static function evaluate_grading_system_highschool( $gsid = null){

            if( $gsid != '' || $gsid != null ){
                  
                  $grading_system  = self::get_grading_system_by_id($gsid);

            }else{

                  $grading_system  = self::get_active_grade_grading_setup(4);
                  
            }

            if(count($grading_system ) == 1){

                  $gsdetail =  DB::table('grading_system_detail')
                                    ->where('headerid',$grading_system[0]->id)
                                    ->where('deleted',0)
                                    ->count();

                  if($gsdetail == 0){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"This grading system does not contain any detail. \n Please add details to continue."
                        ]);

                        return $data;

                  }

            }
            else if(count($grading_system) > 1){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"Mutiple grading system is active."
                  ]);

                  return $data;

            }
            else if(count($grading_system) == 0){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"No available grading system for high school."
                  ]);

                  return $data;

            }
            
            return    $data =  array((object)[
                              'status'=>1,
                              'data'=> $grading_system
                        ]);


      }

      public static function evaluate_grading_system_seniorhigh_tvl( $gsid = null){

            if( $gsid != '' || $gsid != null ){
                  
                  $grading_system  = self::get_grading_system_by_id($gsid);

            }else{

                  $grading_system  = self::get_active_grade_grading_setup_tvl();
                  
            }

            if(count($grading_system ) == 1){

                  $gsdetail =  DB::table('grading_system_detail')
                                    ->where('headerid',$grading_system[0]->id)
                                    ->where('deleted',0)
                                    ->count();

                  if($gsdetail == 0){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"This grading system does not contain any detail. \n Please add details to continue."
                        ]);

                        return $data;

                  }

            }
            else if(count($grading_system) > 1){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"Mutiple grading system is active."
                  ]);

                  return $data;

            }
            else if(count($grading_system) == 0){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"No available grading system for senior high tvl track."
                  ]);

                  return $data;

            }
            
            return    $data =  array((object)[
                              'status'=>1,
                              'data'=> $grading_system
                        ]);


      }

      public static function evaluate_grading_system_seniorhigh_acad( $gsid = null){

            if( $gsid != '' || $gsid != null ){
                  
                  $grading_system  = self::get_grading_system_by_id($gsid);

            }else{

                  $grading_system  = self::get_active_grade_grading_setup_acad();
                  
            }

            if(count($grading_system ) == 1){

                  $gsdetail =  DB::table('grading_system_detail')
                                    ->where('headerid',$grading_system[0]->id)
                                    ->where('deleted',0)
                                    ->count();

                  if($gsdetail == 0){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"This grading system does not contain any detail. \n Please add details to continue."
                        ]);

                        return $data;

                  }

            }
            else if(count($grading_system) > 1){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"Mutiple grading system is active."
                  ]);

                  return $data;

            }
            else if(count($grading_system) == 0){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"No available grading system for senior high academi track."
                  ]);

                  return $data;

            }
            
            return    $data =  array((object)[
                              'status'=>1,
                              'data'=> $grading_system
                        ]);

      }


      public static function get_corevalue_setup( $gsid = null, $acadprog = null){

            if( $gsid != '' || $gsid != null ){
                  
                  $grading_system  = self::get_grading_system_by_id($gsid);

            }else{

                  $grading_system  = self::get_active_core_value_setup($acadprog);
                  
            }

            if(count($grading_system ) == 1){

                  $gsdetail =  DB::table('grading_system_detail')
                                    ->where('headerid',$grading_system[0]->id)
                                    ->where('deleted',0)
                                    ->count();

                  if($gsdetail == 0){

                        $data = array((object)[
                              'status'=>0,
                              'data'=>"This grading system does not contain any detail. \n Please add details to continue."
                        ]);

                        return $data;

                  }

            }
            else if(count($grading_system) > 1){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"Mutiple grading system is active."
                  ]);

                  return $data;

            }
            else if(count($grading_system) == 0){

                  $data =  array((object)[
                        'status'=>0,
                        'data'=>"No core value setup."
                  ]);

                  return $data;

            }
            
            return    $data =  array((object)[
                              'status'=>1,
                              'data'=> $grading_system
                        ]);

      }

      public static function grading_sytem_subject_assignment($gsid){

            return DB::table('grading_system_subjassignment')
                        ->where('gsid',$gsid)
                        ->get();

      }


      public static function add_subject_assignment(
            $gsid = null,
            $subject = null
      ){

            $activeSy = DB::table('sy')->where('isactive',1)->select('id')->first();
            $grading_system_info = GradingSystem::get_grading_system_by_id($gsid);
            $acadprogid = $grading_system_info[0]->acadprogid;

            try{

                  DB::table('grading_system_subjassignment')
                        ->insert([
                              'gsid'=>$gsid,
                              'subjid'=>$subject,
                              'syid'=>$activeSy->id,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return 1;

           
            }catch(\Exception $e){

                  DB::table('zerrorlogs')
                                    ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                  return 0;

            }

      }

      public static function remove_subject_assignment(
            $gssid = null
          
      ){

            try{

                  DB::table('grading_system_subjassignment')
                        ->where('id',$gssid)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return 1;

           
            }catch(\Exception $e){

                  DB::table('zerrorlogs')
                                    ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                  return 0;

            }

      }


      public static function update_rating_value(
            $id = null,
            $sort = null,
            $description = null,
            $value = null
      ){

            try{

                  DB::table('grading_system_ratingvalue')
                              ->where('id',$id)
                              ->update([
                                    'sort'=>$sort,
                                    'description'=>$description,
                                    'value'=>$value,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                  return 1;

           
            }catch(\Exception $e){

                  DB::table('zerrorlogs')
                                    ->insert([
                                    'error'=>$e,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                  return 0;

            }
      }

      public static function checkVersion(){

            return DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();

      }



}
