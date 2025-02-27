<?php

namespace App\Models\Monitoring;
use DB;

use Illuminate\Database\Eloquent\Model;

class MonitoringData extends Model
{

     public static function textblast(){

            $total = DB::table('smsbunkertextblast')->count();
            $sent = DB::table('smsbunkertextblast')->where('smsstatus',1)->count();
            $unsent = DB::table('smsbunkertextblast')->where('smsstatus',0)->count();

            $lastdate = DB::table('smsbunkertextblast')->orderBy('createddatetime','desc')->first();

            if(isset($lastdate->createddatetime)){
                  $lastdate = \Carbon\Carbon::create($lastdate->createddatetime)->isoFormat('MMM DD, YYYY hh:mm A');
            }
            else{
                  $lastdate = 'No Text Available';
            }


            return array((object)[
                  'total'=>$total,
                  'sent'=>$sent,
                  'unsent'=>$unsent,
                  'lastdate'=>$lastdate
            ]);
            
     }

     public static function synchonization(){

            $lastdate = DB::table('syncmoduleslogs')->orderBy('id','desc')->first();

            if(isset($lastdate->date)){
                  $lastdate = \Carbon\Carbon::create($lastdate->date)->isoFormat('MMM DD, YYYY hh:mm A');
            }
            else{
                  $lastdate = 'No Sync Available';
            }

            return array((object)[
                  'lastdate'=>$lastdate
            ]);
            
      }

      public static function tables(){

            $tables = DB::select('SHOW TABLES');
            $all_table = array();

            foreach($tables as $item){
                  $keys = collect($item)->keys()[0];
                  array_push($all_table, $item->$keys);
            }

            return $all_table;

      }

      public static function table_count($tablename = null){

            $table_count = DB::table($tablename)->count();
            $last_index = DB::table($tablename)->max('id');

            try{
                 $last_updated = DB::table($tablename)->orderBy('updateddatetime','desc')->first()->updateddatetime;
            }catch(\Exception $e){
                $last_updated = 'Error!';
            }

            try{
                 $last_deleted = DB::table($tablename)->orderBy('deleteddatetime','desc')->first()->deleteddatetime;
            }catch(\Exception $e){
                $last_deleted = 'Error!';
            }
           
           
            return array((object)[
                        'tablecount'=>$table_count,
                        'lastindex'=>$last_index,
                        'lastupdated'=>$last_updated,
                        'lastdeleted'=>$last_deleted,
                    
                    ]);

      }


      public static function table_data($tablename = null, $tableindex = null){

            $table = DB::table($tablename);

            if($tableindex != null){
                  $table = $table->where('id','>',$tableindex);
            }

            return $table->get();

      }

      public static function table_data_update($tablename = null, $date = null){

            $dateto =  \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');
            $datefrom = \Carbon\Carbon::create($date)->isoFormat('YYYY-MM-DD HH:mm:ss');
    
            $table_date = DB::table($tablename)
                        ->whereBetween('updateddatetime', [$datefrom, $dateto])
                        ->get();

            return  $table_date;
      }

      public static function table_data_deleted($tablename = null, $date = null){

            $dateto =  \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');
            $datefrom = \Carbon\Carbon::create($date)->isoFormat('YYYY-MM-DD HH:mm:ss');

            $table_date = DB::table($tablename)
                        ->where('deleted',1)
                        ->whereBetween('deleteddatetime', [$datefrom, $dateto])
                        ->get();

            return  $table_date;
            
      }

      public static function get_pic_url($tablename = null, $date = null){

            return DB::table('schoolinfo')->select('picurl')->first()->picurl;
            
      }

      
      public static function last_date_sync($tablename = null, $date = null){

            $last_date =  DB::table('syncmoduleslogs')->orderBy('id','desc')->first()->date;
          
            return array((object)[
                  'lastsyncformat1'=>\Carbon\Carbon::create($last_date),
                  'lastsyncformat2'=>\Carbon\Carbon::create($last_date)->isoFormat('MMM DD, YYYY HH:mm:ss'),
                  'lastsynct5format1'=>\Carbon\Carbon::create($last_date)->subHour(5),
                  'lastsynct5format2'=>\Carbon\Carbon::create($last_date)->subHour(5)->isoFormat('MMM DD, YYYY HH:mm:ss')
            ]);
            
      }

      public static function updatelogs($tablename = null, $date = null){

            $updatelogs =  DB::table('updatelogs')->where('status',0)->get();
            return $updatelogs;
            
      }

      


     
      
}
