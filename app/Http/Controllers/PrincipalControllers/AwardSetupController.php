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

            $check = DB::table('grades_ranking_setup')
                        ->where('award',$award)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->count();

            if($check > 0){

                DB::table('grades_ranking_setup')
                    ->where('award',$award)
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
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
            }


            
            return array((object)[
                    'status'=>1,
                    'message'=>'Setup Updated!'
            ]);
           
            
        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    public static function list_award_setup(Request $request){

        $syid = $request->get('syid');
        
        $award_setup = DB::table('grades_ranking_setup')
                            ->where('deleted',0)
                            ->where('syid',$syid)
                            ->select(
                                'id',
                                'award',
                                'gto',
                                'gfrom'
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

                $check = DB::table('grades_ranking_setup')
                            ->where('award',$award)
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->count();

                if($check > 0){
                    return array((object)[
                        'status'=>0,
                        'message'=>'Already Exist!'
                    ]);
                }
                
                DB::table('grades_ranking_setup')
                    ->insert([
                        'syid'=>$syid,
                        'award'=>$award,
                        'gto'=>$gto,
                        'gfrom'=>$gfrom,
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

                $check = DB::table('grades_ranking_setup')
                            ->where('id','!=',$id)
                            ->where('award',$award)
                            ->where('syid',$syid)
                            ->where('deleted',0)
                            ->count();

                if($check > 0){
                    return array((object)[
                        'status'=>0,
                        'message'=>'Already Exist!'
                    ]);
                }

                DB::table('grades_ranking_setup')
                    ->where('id',$id)
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
