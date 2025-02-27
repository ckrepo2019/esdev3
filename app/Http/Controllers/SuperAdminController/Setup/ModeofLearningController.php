<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class ModeofLearningController extends \App\Http\Controllers\Controller
{
        public static function modeoflearning_list(Request $request){

            $syid = $request->get('syid');
            $status = $request->get('status');
            $withrecord = $request->get('withrecord');

            $modeoflearning = DB::table('modeoflearning')
                                ->where('modeoflearning.deleted',0)
                                ->where('modeoflearning.syid',$syid);

            if($status != ""){
                $modeoflearning =   $modeoflearning ->where('modeoflearning.isactive',$status);
            }
            $modeoflearning =   $modeoflearning->select(
                                    'id',
                                    'description',
                                    'description as text',
                                    'isactive'
                                )
                                ->get();

            $gradelevel = DB::table('gradelevel')
                            ->where('deleted',0)
                            ->orderBy('sortid')
                            ->select('id','levelname')
                            ->get();
                    
            foreach($modeoflearning as $item){

                $check_gradelevel = DB::table('modeoflearning_lvl')
                                        ->join('gradelevel',function($join){
                                            $join->on('modeoflearning_lvl.levelid','=','gradelevel.id');
                                            $join->where('gradelevel.deleted',0);
                                        })
                                        ->where('modeoflearning_lvl.deleted',0)
                                        ->where('modeoflearning_lvl.mol_header',$item->id)
                                        ->orderBy('sortid')
                                        ->select(
                                            'levelname',
                                            'mol_header',
                                            'modeoflearning_lvl.id',
                                            'levelid'
                                        )
                                        ->get();

                if($withrecord){
                    $item->registered = DB::table('modeoflearning_student')
                                            ->where('deleted',0)
                                            ->where('syid',$syid)
                                            ->where('mol',$item->id)
                                            ->count();
                }


                if(count($check_gradelevel) > 0){
                    $item->gradelevel = $check_gradelevel;
                    $item->all = false;
                }else{
                    $item->all = true;
                    $item->gradelevel = $gradelevel;
                }


            }

            return $modeoflearning;

        }

        public static function update_schoolinfo_mol(Request $request){
            try{

                $status = $request->get('status');

                DB::table('schoolinfo')
                    ->update([
                        'withMOL'=>$status
                    ]);

                if($status == 1){
                    return array((object)[
                        'status'=>1,
                        'message'=>'Mode of Learning Enabled'
                    ]);
                }else{
                    return array((object)[
                        'status'=>1,
                        'message'=>'Mode of Learning Disabled'
                    ]);
                }

               

            }catch(\Exception $e){
                return self::store_error($e);
            }
        }

        public static function modeoflearning_create(Request $request){

            try{
                $description = $request->get('description');
                $gradelevel = $request->get('gradelevel');
                $syid = $request->get('syid');
                $active = $request->get('active');
                $userid = $request->get('userid');

                $check = DB::table('modeoflearning')
                            ->where('syid',$syid)
                            ->where('description',$description)
                            ->where('deleted',0)
                            ->count();

                if($check > 0){
                    return array((object)[
                        'status'=>0,
                        'message'=>'Mode of learning Already Exist!'
                    ]);
                }

                $mol_header = DB::table('modeoflearning')
                                ->insertGetId([
                                    'syid'=>$syid,
                                    'isactive'=>$active,
                                    'description'=>$description,
                                    'createdby'=>$userid,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                ]);

                if($gradelevel != ""){
                    foreach($gradelevel as $item){
                        DB::table('modeoflearning_lvl')
                            ->insertGetId([
                                'levelid'=>$item,
                                'mol_header'=>$mol_header,
                                'createdby'=>$userid,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                    }
                }

                
                
                return array((object)[
                    'status'=>1,
                    'message'=>'Mode of learning Created!'
                ]);

            }
            catch(\Exception $e){
                return self::store_error($e);
            }

        }
      
        public static function modeoflearning_update(Request $request){

            try{

                $id = $request->get('id');
                $syid = $request->get('syid');
                $gradelevel = $request->get('gradelevel');
                $description = $request->get('description');
                $active = $request->get('active');
                $userid = $request->get('userid');

                $check = DB::table('modeoflearning')
                            ->where('syid',$syid)
                            ->where('id','!=',$id)
                            ->where('description',$description)
                            ->where('deleted',0)
                            ->count();

                if($check > 0){
                    return array((object)[
                        'status'=>0,
                        'message'=>'Mode of learning Already Exist!'
                    ]);
                }

                $check = DB::table('modeoflearning_student')
                            ->where('mol',$id)
                            ->where('deleted',0)
                            ->count();


                if($check > 0){

                    $check = DB::table('modeoflearning')
                                ->where('syid',$syid)
                                ->where('id',$id)
                                ->where('description',$description)
                                ->where('deleted',0)
                                ->count();

                    if($check == 0){
                        return array((object)[
                            'status'=>0,
                            'message'=>'Mode of Learning already Used!'
                        ]);
                    }
                   
                }

                DB::table('modeoflearning')
                    ->where('id',$id)
                    ->take(1)
                    ->update([
                        'isactive'=>$active,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'description'=>$description,
                        'updatedby'=>$userid
                    ]);

                if($gradelevel != ""){

                    DB::table('modeoflearning_lvl')
                            ->where('deleted',0)
                            ->where('mol_header',$id)
                            ->whereNotIn('levelid',$gradelevel)
                            ->update([
                                'deleted'=>1,
                                'deletedby'=>$userid,
                                'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);

                    $existing = DB::table('modeoflearning_lvl')
                                    ->where('deleted',0)
                                    ->where('mol_header',$id)
                                    ->select('levelid')
                                    ->get();

                    foreach($gradelevel as $item){
                        $check = collect($existing)->where('levelid',$item)->count();
                        if($check == 0){
                            DB::table('modeoflearning_lvl')
                                ->insertGetId([
                                    'levelid'=>$item,
                                    'mol_header'=>$id,
                                    'createdby'=>$userid,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                ]);
                        }
                    }
                }else{
                    DB::table('modeoflearning_lvl')
                            ->where('deleted',0)
                            ->where('mol_header',$id)
                            ->update([
                                'deleted'=>1,
                                'deletedby'=>$userid,
                                'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                }

                return array((object)[
                    'status'=>1,
                    'message'=>'Payment Option updated!'
                ]);
            }
            catch(\Exception $e){
                return self::store_error($e);
            }
        }


        public static function modeoflearning_delete(Request $request){

            try{
                $id = $request->get('id');
                $userid = $request->get('userid');

                $check = DB::table('modeoflearning_student')
                            ->where('mol',$id)
                            ->where('deleted',0)
                            ->count();


                if($check > 0){
                    return array((object)[
                        'status'=>0,
                        'message'=>'Mode of Learning already Used!'
                    ]);
                }
                
                DB::table('modeoflearning')
                    ->where('id',$id)
                    ->take(1)
                    ->update([
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'deletedby'=>$userid,
                        'deleted'=>1,
                    ]);

                DB::table('modeoflearning_lvl')
                    ->where('mol_header',$id)
                    ->where('deleted',0)
                    ->update([
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'deletedby'=>$userid,
                        'deleted'=>1,
                    ]);

                return array((object)[
                    'status'=>1,
                    'message'=>'Mode of Learning  deleted!'
                ]);
            }
            catch(\Exception $e){
                return self::store_error($e);
            }
        }

        public static function store_error($e){
            DB::table('zerrorlogs')
            ->insert([
                        'error'=>$e,
                        // 'createdby'=>$userid,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            return array((object)[
                  'status'=>0,
                  'message'=>'Something went wrong!'
            ]);
        }

     
}
