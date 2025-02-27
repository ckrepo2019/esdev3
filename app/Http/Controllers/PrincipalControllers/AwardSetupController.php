<?php

namespace App\Http\Controllers\PrincipalControllers;
use Illuminate\Http\Request;
use DB;

class AwardSetupController extends \App\Http\Controllers\Controller
{

    //Award Setup
    public static function update_award_setup_lowest(Request $request){

        try{

            $syid = $request->get('syid');
            $award = 'lowest grade';
            $gto = $request->get('gto');
            $levelid = $request->get('levelid');

            $check = DB::table('grades_ranking_setup')
                        ->where('award',$award)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->where('levelid',$levelid)
                        ->count();

            if($check > 0){

                DB::table('grades_ranking_setup')
                    ->where('award',$award)
                    ->where('levelid',$levelid)
                    ->update([
                        'syid'=>$syid,
                        'gto'=>$gto,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            }else{

                DB::table('grades_ranking_setup')
                    ->insert([
                        'syid'=>$syid,
                        'award'=>$award,
                        'levelid'=>$levelid,
                        'gto'=>$gto,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            }

            $award = 'base grade';
            $basegrade = $request->get('basegrade');

            $check = DB::table('grades_ranking_setup')
                        ->where('award',$award)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->where('levelid',$levelid)
                        ->count();


            if($check > 0){

                $gto = null;
                $gfrom = null;

                if($basegrade == 1){
                    $gfrom = 1;
                }else{
                    $gto = 1;
                }

                DB::table('grades_ranking_setup')
                    ->where('award',$award)
                    ->where('levelid',$levelid)
                    ->update([
                        'gto'=>$gto,
                        'gfrom'=>$gfrom,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            }else{

                $gto = null;
                $gfrom = null;

                if($basegrade == 1){
                    $gto = 1;
                }else{
                    $gfrom = 1;
                }

                DB::table('grades_ranking_setup')
                    ->insert([
                        'syid'=>$syid,
                        'award'=>$award,
                        'gto'=>$gto,
                        'gfrom'=>$gfrom,
                        'levelid'=>$levelid,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            }

          $setup =  DB::table('grades_ranking_setup')
            ->where('deleted', 0)
            ->where('levelid', $levelid)
            ->whereIn('award', ['lowest grade', 'base grade'] )
            ->get();

            
            return array((object)[
                    'status'=>1,
                    'message'=>'Setup Updated!',
                    'setup' => $setup
            ]);
           
            
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function list_award_setup(Request $request){

        $syid = $request->get('syid');
        $levelid = $request->get('levelid');
        
        $award_setup = DB::table('grades_ranking_setup')
                            ->where('deleted',0)
                            ->where('syid',$syid)
                            ->where( function($query) use ($levelid) {
                                if(isset($levelid) || $levelid == null){
                                    $query->where('levelid', $levelid);
                                }
                            })
                            ->select(
                                'id',
                                'award',
                                'gto',
                                'gfrom', 
                                'levelid'
                            )
                            ->get();

        

        return $award_setup;
    }

    public static function create_award_setup(Request $request){
            try{

                $syid = $request->get('syid');
                $award = $request->get('award');
                $gto = $request->get('gto');
                $gfrom = $request->get('gfrom');
                $levelid = $request->get('levelid');

                $check = DB::table('grades_ranking_setup')
                            ->where('award',$award)
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->where('levelid',$levelid)
                            ->count();

                if($check > 0){
                    return array((object)[
                        'status'=>0,
                        'message'=>'Already Exist!'
                    ]);
                }

                $check = DB::table('grades_ranking_setup')
                    ->where('award', 'base grade')
                    ->where('syid',$syid)
                    ->where('deleted',0)
                    ->where('levelid',$levelid)
                    ->count();

                if($check == 0){
                    return array((object)[
                        'status'=>0,
                        'message'=>'Please add Base Grade first!'
                    ]);
                }
            
                DB::table('grades_ranking_setup')
                    ->insert([
                        'syid'=>$syid,
                        'award'=>$award,
                        'gto'=>$gto,
                        'gfrom'=>$gfrom,
                        'levelid'=>$levelid,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                return array((object)[
                        'status'=>1,
                        'message'=>'Setup Created!'
                ]);
                
            }catch(\Exception $e){
                return self::store_error($e);
            }
    }

    public static function update_award_setup(Request $request){
            try{

                $id = $request->get('id');
                $syid = $request->get('syid');
                $award = $request->get('award');
                $gto = $request->get('gto');
                $gfrom = $request->get('gfrom');
                $levelid = $request->get('levelid');

                $check = DB::table('grades_ranking_setup')
                            ->where('id','!=',$id)
                            ->where('award',$award)
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->where('levelid',$levelid)
                            ->count();

                if($check > 0){
                    return array((object)[
                        'status'=>0,
                        'message'=>'Already Exist!'
                    ]);
                }

                DB::table('grades_ranking_setup')
                    ->where('id',$id)
                    ->where('levelid',$levelid)
                    ->update([
                        'syid'=>$syid,
                        'award'=>$award,
                        'gto'=>$gto,
                        'gfrom'=>$gfrom,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                return array((object)[
                        'status'=>1,
                        'message'=>'Setup Updated!'
                ]);
                
            }catch(\Exception $e){
                return self::store_error($e);
            }
    }

    public static function delete_award_setup(Request $request){
            try{

                $id = $request->get('id');

                DB::table('grades_ranking_setup')
                    ->where('id',$id)
                    ->update([
                        'deleted'=>1,
                        'deletedby'=>auth()->user()->id,
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                return array((object)[
                        'status'=>1,
                        'message'=>'Setup Deleted!'
                ]);
                
            }catch(\Exception $e){
                return self::store_error($e);
            }
    }
    //Award Setup

    public static function store_error($e){
        DB::table('zerrorlogs')
        ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
        return array((object)[
              'status'=>0,
              'message'=>'Something went wrong!'
        ]);
   }

}
