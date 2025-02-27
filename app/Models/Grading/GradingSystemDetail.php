<?php

namespace App\Models\Grading;
use DB;

use Illuminate\Database\Eloquent\Model;

class GradingSystemDetail extends Model
{
   
      public static function create_grading_system_detail(
            $headerid  = null,
            $value = null,
            $sort = null,
            $description = null,
            $items = null,
            $group = null,
            $sf9val = null
      ){

            try{

                  DB::table('grading_system_detail')
                        ->insert([
                              'headerid'=>$headerid,
                              'value'=>$value,
                              'sort'=>$sort,
                              'description'=>$description,
                              'items'=>$items,
                              'sf9val'=>$sf9val,
                              'group'=>$group,
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

      public static function update_grading_system_detail(
            $id = null,
            $value = null,
            $sort = null,
            $description = null,
            $items = null,
            $group = null,
            $sf9val = null
      ){

            try{

                  DB::table('grading_system_detail')
                        ->where('id',$id)
                        ->update([
                              'value'=>$value,
                              'sort'=>$sort,
                              'description'=>$description,
                              'items'=>$items,
                              'group'=>$group,
                              'sf9val'=>$sf9val,
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

      public static function delete_grading_system_detail(
            $id = null
      ){

            try{

                  DB::table('grading_system_detail')
                        ->where('id',$id)
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




}
