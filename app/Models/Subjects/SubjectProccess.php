<?php

namespace App\Models\Subjects;
use DB;

use Illuminate\Database\Eloquent\Model;

class SubjectProccess extends Model
{


      public static function create_subject(
            $subjdesc = null,
            $subjcode = null,
            $acadprogid = null,
            $inSF9 = null,
            $inMAPEH = null,
            $inTLE = null,
            $subj_sortid = null,
            $type = null,
            $semid = null,
            $strand = null,
            $isTLECon = null,
            $isMAPEHCon = null,
            $isCon = null,
            $isVisible = null,
            $isSP = null
      ){

            try{

                  $gradelevel = DB::table('gradelevel')
                                          ->where('acadprogid',$acadprogid)
                                          ->where('deleted',0)
                                          ->select('id')
                                          ->get();

                  $sy = Db::table('sy')->where('isactive',1)->first()->id;

                  if($acadprogid == 5){

                        $new_subj_id = DB::table('sh_subjects')
                              ->insertGetId([
                                    'subjtitle'=>$subjdesc,
                                    'subjcode'=>$subjcode,
                                    'sh_subj_sortid'=>$subj_sortid,
                                    'acadprogid'=>$acadprogid,
                                    'inSF9'=>$inSF9,
                                    'semid'=>$semid,
                                    'type'=>$type,
                                    'deleted'=>0,
                                    'isactive'=>1,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        if($strand != null){
                              foreach($strand as $item){
                                    self::add_strand($new_subj_id,$item);
                              }
                        }

                        foreach($gradelevel as $item){
                              $check_setup = \App\Models\GradeSetup\GradeSetupData::get_grade_setup($sy,$new_subj_id,null,$item->id);
                              if(count($check_setup) == 0){
                                    \App\Models\GradeSetup\GradeSetupProccess::update_grade_setup(null,30,50,20,$sy,$item->id,$new_subj_id);
                              }
                        }




                  }else{

                        $new_subj_id = DB::table('subjects')
                              ->insertGetId([
                              'subjdesc'=>$subjdesc,
                              'subjcode'=>$subjcode,
                              'subj_sortid'=>$subj_sortid,
                              'acadprogid'=>$acadprogid,
                              'inSF9'=>$inSF9,
                              'inMAPEH'=>$inMAPEH,
                              'inTLE'=>$inTLE,
                              // 'isTLECon'=>$isTLECon,
                              // 'isMAPEHCon'=>$isMAPEHCon,
                              'isCon'=>$isCon,
                              'isVisible'=>$isVisible,
                              'deleted'=>0,
                              'isactive'=>1,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        $ww = 20;
                        $pt = 50;
                        $qa = 30;

                        if($isTLECon == 1 || $isMAPEHCon == 1){
                              $ww = null;
                              $pt = null;
                              $qa = null;
                        }

                        foreach($gradelevel as $item){
                              $check_setup = \App\Models\GradeSetup\GradeSetupData::get_grade_setup($sy,$new_subj_id,null,$item->id);
                              if(count($check_setup) == 0){
                                    \App\Models\GradeSetup\GradeSetupProccess::update_grade_setup(null,$ww,$pt,$qa,$sy,$item->id,$new_subj_id);
                              }
                        }

                  }

                  

                  
                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        'id'=> $new_subj_id
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function update_subject_sort($subj_id = null, $sort_val = null){

            try{

                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'subj_sortid'=>$sort_val,
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

      public static function update_subject_mapeh($subj_id = null, $mapeh = 0){

            try{

                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'inMAPEH'=>$mapeh,
                              'inTLE'=>0,
                              // 'isTLECon'=>0,
                              // 'isMAPEHCon'=>0,
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


      public static function update_subject_issp($subj_id = null, $issp = 0){
            try{
                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'isSP'=>$issp,
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

      public static function update_subject_sf9($subj_id = null, $sf9 = 0){

            try{

                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'inSF9'=>$sf9,
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

      public static function update_subject_sf9_sh($subj_id = null, $sf9 = 0){

            try{

                  DB::table('sh_subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'inSF9'=>$sf9,
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

      public static function update_subject_tle($subj_id = null, $tle = 0){
            try{
                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'inTLE'=>$tle,
                              // 'isTLECon'=>0,
                              // 'isMAPEHCon'=>0,
                              'inMAPEH'=>0,
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

      public static function update_subject_tlecon($subj_id = null, $tlecon = 0){
            try{
                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'inTLE'=>0,
                              // 'isTLECon'=>$tlecon,
                              // 'isMAPEHCon'=>0,
                              'inMAPEH'=>0,
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

      public static function update_subject_mapehcon($subj_id = null, $mapehcon = 0){
            try{
                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'inTLE'=>0,
                              // 'isTLECon'=>0,
                              // 'isMAPEHCon'=>$mapehcon,
                              'inMAPEH'=>0,
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

      public static function update_subject_percentage($subj_id = null, $percentage = 0){
            try{
                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'subj_per'=>$percentage,
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

      public static function update_subject_visible($subj_id = null, $visible = 0){
            try{
                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'isVisible'=>$visible,
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

      public static function update_subject_consolidated($subj_id = null, $consolidated = 0){
            try{
                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'isCon'=>$consolidated,
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


      public static function update_subject_sort_sh($subj_id = null, $sort_val = null){

            try{

                  DB::table('sh_subjects')
                        ->take(1)
                        ->where('id',$subj_id)
                        ->where('deleted',0)
                        ->update([
                              'sh_subj_sortid'=>$sort_val,
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

      public static function update_subject(
            $subjid = null, 
            $subjcode = null, 
            $subjdesc = null
      ){

            try{

                  DB::table('subjects')
                        ->take(1)
                        ->where('id',$subjid)
                        ->where('deleted',0)
                        ->update([
                              'subjcode'=>$subjcode,
                              'subjdesc'=>$subjdesc,
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

      public static function update_subject_sh(
            $subjid = null, 
            $subjcode = null, 
            $subjdesc = null,
            $type = null,
            $semid = null
      ){

            try{

                  DB::table('sh_subjects')
                        ->take(1)
                        ->where('id',$subjid)
                        ->where('deleted',0)
                        ->update([
                              'subjcode'=>$subjcode,
                              'subjtitle'=>$subjdesc,
                              'type'=>$type,
                              'semid'=>$semid,
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

      public static function add_strand(
            $subjid = null,
            $strandid = null
      ){

            try{

                  $subj_strand_id = DB::table('sh_subjstrand')
                        ->insertGetId([
                              'subjid'=>$subjid,
                              'strandid'=>$strandid,
                              'deleted'=>0,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  
                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        'id'=>$subj_strand_id
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }


      public static function remove_strand(
            $subj_strand_id = null
      ){

            try{

                  DB::table('sh_subjstrand')
                        ->where('id',$subj_strand_id )
                        ->update([
                              'deleted'=>1,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  
                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function update_subject_component(
            $selectedsubj = null,
            $subjid = null
      ){
            try{
                  DB::table('subjects')
                        ->where('id',$selectedsubj )
                        ->take(1)
                        ->update([
                              'subjCom'=>$subjid,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);
                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted Successfully!'
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function subject_component_remove($subjid = null){
            try{
                  DB::table('subjects')
                        ->where('id',$subjid )
                        ->take(1)
                        ->update([
                              'subjCom'=>null,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);
                  return array((object)[
                        'status'=>1,
                        'data'=>'Deleted Successfully!'
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }



      


      
}
