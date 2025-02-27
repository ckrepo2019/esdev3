<?php

namespace App\Models\Student\Requirements;
use DB;
use File;
use Image;

use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\Model;

class RequirementsProccess extends Model
{

      public static function insert_requirement($studid = null,  $sid = null, $url = null, $img = null, $reqid = null, $syid = null, $semid = null){
            
            try{

            
                  if (! File::exists(public_path().'preregrequirements/'.$sid.'/')) {
      
                        $path = public_path('preregrequirements/'.$sid.'');
      
                        if(!File::isDirectory($path)){
      
                              File::makeDirectory($path, 0777, true, true);
                        }
                  }
      
                  if (! File::exists(dirname(base_path(), 1).'/'.$url.'/preregrequirements/'.$sid.'/')) {
      
                        $cloudpath = dirname(base_path(), 1).'/'.$url.'/preregrequirements/'.$sid.'/';
      
                        if(!File::isDirectory($cloudpath)){
      
                              File::makeDirectory($cloudpath, 0777, true, true);
      
                        }
                        
                  }
      
                  $extension = 'png';
                  $img = Image::make($img);
                  $time = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss');

                  $destinationPath = public_path('preregrequirements/'.$sid.'/'.'requirement'.$reqid.'-'.$time.'.'.$extension);

                  $clouddestinationPath = dirname(base_path(), 1).'/'.$url.'/'.'preregrequirements/'.$sid.'/'.'requirement'.$reqid.'-'.$time.'.'.$extension;

                  $img->resize(1000, 1000, function ($constraint) {
                              $constraint->aspectRatio();
                              })->save($destinationPath);


                  $img->save($clouddestinationPath);
                  
                  $check_requirement = DB::table('preregistrationrequirements')
                                          ->where('studid',$studid)
                                          ->where('syid',$syid)
                                          ->where('semid',$semid)
                                          ->where('preregreqtype',$reqid)
                                          ->where('deleted',0)
                                          ->select('id')
                                          ->first();

                  if(isset($check_requirement->id)){
                        DB::table('preregistrationrequirements')
                              ->where('id',$check_requirement->id)
                              ->take(1)
                              ->update([
                                    'deleted'=>1
                              ]);
                  }

                  DB::table('preregistrationrequirements')    
                              ->insert([
                              'picurl'=>'preregrequirements/requirement'.$reqid.'-'.$time.'.'.$extension,
                              'qcode'=>$sid,
                              'preregreqtype'=>$reqid,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'studid'=>$studid,
                              'syid'=>$syid,
                              'semid'=>$semid
                              ]);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Submitted Successfully'
                  ]);
                         

            }catch(\Exception $e){
                  return self::insert_errorlogs($e);
            }

      }

      public static function insert_errorlogs($e){

          
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

      
}
