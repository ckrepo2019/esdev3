<?php

namespace App\Models\Grading;
use DB;

use Illuminate\Database\Eloquent\Model;

class RatingValue extends Model
{
   
      public static function create_rating_value(
            $headerid = null,
            $value = null,
            $sort = null,
            $description = null
      ){

            try{

                  DB::table('grading_system_ratingvalue')
                        ->insert([
                              'gsid'=>$headerid,
                              'value'=>$value ,
                              'sort'=>$sort,
                              'description'=>$description,
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

      public static function update_rating_value(
            $id = null,
            $value = null,
            $sort = null
      ){
            
            try{

                  DB::table('grading_system_ratingvalue')
                        ->where('id',$id)
                        ->update([
                              'value'=>$value,
                              'sort'=>$sort,
                              'description'=>$request->get('description'),
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

      public static function delete_rating_value(
            $id = null
      ){

            try{

                  DB::table('grading_system_ratingvalue')
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
