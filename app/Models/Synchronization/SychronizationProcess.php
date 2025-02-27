<?php

namespace App\Models\Synchronization;
use DB;

use Illuminate\Database\Eloquent\Model;

class SychronizationProcess extends Model
{

      public static function process_delete($tablename = null, $data = null){

            try{
                  DB::table($tablename)
                        ->take(1)
                        ->where('id',$data['id'])
                        ->update([
                            'deleted'=>1,
                            'deleteddatetime'=>$data['deleteddatetime']
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!'
                  ]);
                  
            }catch(\Exception $e){
                  return self::store_error($e);
            }
            
      }

      public static function process_update($tablename = null, $data = null){

            try{
                  $dataid = $data['id'];
                  unset($data['id']);
                  
                  DB::table($tablename)
                        ->take(1)
                        ->where('id',$dataid)
                        ->update($data);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!'
                  ]);
                  
            }catch(\Exception $e){
                     return $e;
                  return self::store_error($e);
            }
            
      }

     public static function insert_new_data($tablename = null, $data = null){

		$message = 'Early registration payment has been recieved. To continue your enrollment, you may add Php500 to avail \"Php1000 Enrolled kana Promo\" on April 1-15.';

            try{
                DB::table($tablename)
                        ->insert($data);
						
				if($tablename == 'onlinepayments'){
					
					$contactno = '+63' . substr($data['opcontact'], 1);
					DB::table('smsbunker')
						->insert([
							'message'=>$message,
							'smsstatus'=>0,
							'receiver'=>$contactno
						]);
				}

                  return array((object)[
                        'status'=>1,
                        'data'=>'Added Successfully!'
                  ]);
                  
            }catch(\Exception $e){
				return $e;
                  return self::store_error($e);
            }
            
     }

     public static function insert_synclogs($date = null){

            try{

                  $date = \Carbon\Carbon::create($date);

                  DB::table('syncmoduleslogs')
                        ->insert([
                              'date'=>$date
                        ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Added Successfully!'
                  ]);
                  
            }catch(\Exception $e){
     
                  return self::store_error($e);
            }
            
      }

      public static function process_updatelogs($query = null, $binding = null){

            try{
				
				
				DB::update($query,$binding);
				
                  return array((object)[
                        'status'=>1,
                        'data'=>'Updated Successfully!'
                  ]);
                  
            }catch(\Exception $e){
     
                  return self::store_error($e);
            }
            
      }

      public static function process_updatelogs_status($id = null){

            try{

                  DB::table('updatelogs')
                        ->take(1)
                        ->where('id',$id)
                        ->update([
                              'status'=>1
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
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

            return array((object)[
                  'status'=>0,
                  'data'=>'Something went wrong!'
            ]);

      }





     
      
}
