<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use Hash;
use File;
use Illuminate\Support\Facades\Schema;;

class TruncateControllerV2 extends \App\Http\Controllers\Controller
{

      public static function get_table_information(Request $request){

            $tablename = $request->get('tablename');

            $columns = Schema::getColumnListing( $tablename);
            $data = DB::table($tablename)->get();

            $temp_columns = array();

            foreach($columns as $item){
                  array_push($temp_columns,(object)[
                        'data'=>$item,
                        'title'=>$item
                  ]);
            }

            return array((object)[
                  'columns'=>$temp_columns,
                  'data'=>$data
            ]);

      }


      public static function not_truncated_tables(){

            $tables = DB::select(DB::raw('SHOW TABLES'));
            $temp_list = array();
            $tableKey = collect($tables[0])->keys()[0];
            foreach($tables as $key=>$item){
                 array_push($temp_list , (object)[
                     'text'=>$item->$tableKey,
                     'id'=>$item->$tableKey
                 ]);
            }
            $tables = $temp_list;

            $module_table_array = array();

            $module_tables = DB::table('modules_table')
                                    ->join('modules',function($join){
                                          $join->on('modules_table.moduleid','=','modules.id');
                                          $join->where('modules.deleted',0);
                                          $join->where('modules.startup',1);
                                    })
                                    ->where('modules_table.deleted',0)
                                    ->distinct('moduletable')
                                    ->get();

            

            foreach($module_tables as $item){
                  array_push($module_table_array,$item->moduletable);
            }
          
            $not_trunc_table = collect($tables)
                                    ->whereNotIn('text',$module_table_array)
                                    ->values();
                                 
            foreach($not_trunc_table as $item){

                  $tempdata = DB::table($item->id)->first();
                  if(isset($tempdata)){
                        $item->withdata = true;
                  }else{
                        $item->withdata = false;
                  }
            }

            return $not_trunc_table;

      }


      public static function back_up_table($tables = null , $module = null){

            if (! File::exists(public_path().'dbbackup/')) {

                  $path = public_path('dbbackup');
      
                  if(!File::isDirectory($path)){
      
                      File::makeDirectory($path, 0777, true, true);
                  }
            }

            $content = "";

            foreach($tables as $table_item){

                  $table = $table_item->moduletable;

                  try{
                      $tableData = DB::select(DB::raw('SELECT * FROM '.$table));
                  }
                  catch (\Exception $e) {
                      
                  }

                  $newLine = "\r\n";
      
                  $content .= "DROP TABLE IF EXISTS `".$table."`".';'.$newLine.$newLine;
      
                  $res = DB::select(DB::raw('SHOW CREATE TABLE '.$table));
               
                  $content .= $res[0]->{'Create Table'}.';'.$newLine.$newLine;
      
                  if(count($tableData)>0){
      
                      $content .=" INSERT INTO "."`".$table."` (";
      
                      $fields = array_keys(collect($tableData[0])->toArray());
      
                      foreach($fields as $field){
      
                          $content .= "`".$field."`,";
      
                      }
      
                      $content = substr($content,0,-1);
      
                      $content .= ") values ";
      
                      foreach($tableData as $item){
      
                          $content.="(";
                          
                          foreach($item as $key=>$data){
      
                              if($data == ''){
                              
                                  $content .= "NULL";
      
                              }
                              else{
      
                                  if(gettype($data) == 'integer'){
                                      $content .= $data;
                                  }
                                  else{
                                      $content .= " '".$data."'";
                                  }
                              }
                              $content .= ",";
                          }
                          $content = substr($content,0,-1);
                          $content.='),';
                      }
                      $content = substr($content,0,-1);
                      $content.=';'.$newLine.$newLine;
                  }
      
                  
      
      
              }
      
              $date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYYHHMM');
      
              file_put_contents('dbbackup/'.$module.' '.$date.'.sql', $content, FILE_APPEND);
      
      }

      public static function clear(Request $request){

            try{
                  $id = $request->get('id');
                  
                  $tables = DB::table('modules_table')
                              ->where('moduleid',$id)
                              ->where('deleted',0)
                              ->get();

                  $module_info = DB::table('modules')
                                    ->where('id',$id)
                                    ->get();

                  $module_items = DB::table('modules_group')
                                    ->join('modules_table',function($join){
                                          $join->on('modules_group.module','=','modules_table.moduleid');
                                          $join->where('modules_table.deleted',0);
                                    })
                                    ->where('modules_group.deleted',0)
                                    ->where('moduleheader',$id)             
                                    ->get();

                  foreach($module_items as $item){
                        $check = DB::table( $item->moduletable)->count();
                        if($check > 0){
                              return array((object)[
                                    'status'=>0,
                                    'message'=>'Please empty included tables.'
                              ]);
                        }
                  }

                  //self::back_up_table($tables, $module_info[0]->module_name);

                  // DB::table('college_colleges')
                  //       ->update([
                  //             'dean'=>null
                  //       ]);


                  // DB::table('college_courses')
                  //       ->update([
                  //             'courseChairman'=>null
                        // ]);

                  foreach($tables as $item){

                        DB::table($item->moduletable)->truncate();

                        if($item->moduletable == 'users'){

                              DB::table('studinfo')
                                    ->update([
                                          'userid'=>null
                                    ]);

                              DB::table('teacher')
                                    ->update([
                                          'userid'=>null
                                    ]);

                                    
                              DB::table('academicprogram')
                                    ->update([
                                          'principalid'=>null
                                    ]);

                              DB::table('users')->insert([
                                          'name'=>'SADMIN',
                                          'email'=>'ckgroup',
                                          'password'=>Hash::make('CK_publishin6'),
                                          'type'=>'17'
                                    ]);

                        }
                        elseif($item->moduletable == 'transcounter'){
                              DB::table('transcounter')->insert([
                                    'transno'=>1,
                                    'transdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'terminalno'=>1,
                                    'deleted'=>0
                              ]);

                        }elseif($item->moduletable == 'schoolinfo'){
                              DB::table('schoolinfo')
                                    ->insert([
                                          'schoolid'=>null,
                                          'schoolname'=>null,
                                          'region'=>null,
                                          'division'=>null,
                                          'district'=>null,
                                          'address'=>null,
                                          'picurl'=>null,
                                          'tagline'=>'SCHOOL TAGLINE',
                                          'tagline'=>null,
                                          'abbreviation'=>null
                                    ]);
                        }
                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Module Clear'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function check(Request $request , $id = null){

            try{
                  if($id == null){
                        $id = $request->get('id');
                  }

                  $tables = DB::table('modules_table')
                                    ->where('deleted',0)
                                    ->where('moduleid',$id)
                                    ->get();

                  $with_data = false;

                  if(count($tables) == 0){
                        return array((object)[
                              'data'=>$with_data,
                              'status'=>0,
                              'message'=>'No Table'
                        ]);
                  }
                  
                  foreach($tables as $item){
                        $check = DB::table($item->moduletable)
                                    ->count();
                        if($check > 0){
                              $with_data = true;
                        }
                  }

                  if($with_data){
                        return array((object)[
                              'data'=>$with_data,
                              'status'=>1,
                              'message'=>'With Data'
                        ]);
                  }else{
                        return array((object)[
                              'data'=>$with_data,
                              'status'=>1,
                              'message'=>'Empty'
                        ]);
                  }


            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function module_list(Request $request){

            $modules = Db::table('modules')
                        ->where('deleted',0)
                        ->select(
                              'startup',
                              'id',
                              'module_name',
                              'module_name as text'
                        )
                        ->get();

            foreach($modules as $item){

                  $module_item = DB::table('modules_group')
                                    ->where('moduleheader',$item->id)
                                    ->where('deleted',0)
                                    ->get();

                  $module_table = DB::table('modules_table')
                                    ->where('moduleid',$item->id)
                                    ->where('deleted',0)
                                    ->get();

                  $item->group = $module_item;
                  $item->tables = $module_table;

            }

            return $modules;

      }

      public static function create_module(Request $request){
            try{
                 $module_name = $request->get('modulename');
                 $startup = $request->get('startup');
                 
                 $check = DB::table('modules')
                              ->where('module_name',$module_name)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already Exist!'
                        ]);
                  }

                  DB::table('modules')
                        ->insert([
                              'module_name'=>$module_name,
                              'startup'=> $startup
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Module Created!'
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function update_module(Request $request){
            try{
                  $id = $request->get('id');
                  $module_name = $request->get('modulename');
                  $startup = $request->get('startup');

                  $check = DB::table('modules')
                              ->where('id','!=',$id)
                              ->where('module_name',$module_name)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already Exist!'
                        ]);
                  }

                  DB::table('modules')
                        ->where('id',$id)
                        ->take(1)
                        ->update([
                              'module_name'=>$module_name,
                              'startup'=> $startup
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Module Updated!'
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }


      public static function create_module_table(Request $request){
            try{

                  $moduleid = $request->get('moduleid');
                  $tables = $request->get('tables');

                  if($tables == "" || $tables == null){
                        DB::table('modules_table')
                                    ->where('moduleid',$moduleid)
                                    ->update([
                                          'deleted'=>1
                                    ]);

                        return array((object)[
                              'status'=>1,
                              'message'=>'Tables Updated!'
                        ]);
                  }

                  $to_delete = DB::table('modules_table')
                                    ->where('moduleid',$moduleid)
                                    ->where('deleted',0)
                                    ->whereNotIn('moduletable',$tables)
                                    ->get();

                  if(count($to_delete) > 0){
                        foreach($to_delete as $item){
                              DB::table('modules_table')
                                    ->where('id',$item->id)
                                    ->update([
                                          'deleted'=>1
                                    ]);
                        }
                  }else{

                        $existing = DB::table('modules_table')
                                          ->where('moduleid',$moduleid)
                                          ->where('deleted',0)
                                          ->get();

                        foreach($tables as $item){
                              $check = collect($existing)->where('moduletable',$item)->count();

                              if($check == 0){
                                    DB::table('modules_table')       
                                          ->insert([
                                                'moduleid'=>$moduleid,
                                                'moduletable'=>$item,
                                                'deleted'=>0
                                          ]);
                              }
                        }

                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Tables Updated!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function insert_module_to_group(Request $request){
            try{

                 
                  $moduleid = $request->get('moduleid');
                  $modules = $request->get('modules');


                  DB::table('modules_group')
                        ->where('moduleheader',$moduleid)
                        ->update([
                              'deleted'=>1
                        ]);

                  if($modules != "" || $modules != null){
                        foreach($modules as $item){
                              DB::table('modules_group')
                                    ->updateOrInsert([
                                          'moduleheader'=>$moduleid,
                                          'module'=>$item
                                    ],
                                    [
                                          'deleted'=>0
                                    ]
                              );
                        }
                  }
                  return array((object)[
                        'status'=>1,
                        'message'=>'Included Modules Updated!'
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
                  'message'=>'Something went wrong!'
            ]);
      }
     
}
