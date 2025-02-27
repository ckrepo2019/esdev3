<?php

namespace App\Models\Student;
use DB;
use File;
use App\FilePath;
use Image;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\Model;

class StudentProccess extends Model
{

     
      public static function upload_student_pic($studid = null, $sid = null, $request = null){

            $message = [
                  'image.required'=>'Student Picture is required',
            ];

            $validator = Validator::make($request->all(), [
                  'image' => ['required']
            
            ], $message);

            if ($validator->fails()) {

                  toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

                  $data = array(
                        (object)
                        [
                        'status'=>'0',
                        'message'=>'Error',
                        'errors'=>$validator->errors(),
                        'inputs'=>$request->all()
                  ]);

                  return $data;
                  
            }
            else{

                  $urlFolder = str_replace('http://','',$request->root());

                  if (! File::exists(public_path().'storage/STUDENT')) {

                        $path = public_path('storage/STUDENT');

                        if(!File::isDirectory($path)){

                        File::makeDirectory($path, 0777, true, true);
                        }
                  }

                  if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'storage/STUDENT')) {

                        $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT';

                        if(!File::isDirectory($cloudpath)){

                        File::makeDirectory($cloudpath, 0777, true, true);

                        }
                        
                  }
            
                  $data = $request->image;

                  list($type, $data) = explode(';', $data);

                  list(, $data)      = explode(',', $data);


                  $data = base64_decode($data);

                  $extension = 'png';

                  $destinationPath = public_path('storage/STUDENT/'.$sid.'.'.$extension);
                  
                  $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT/'.$sid.'.'.$extension;
            
                  file_put_contents($clouddestinationPath, $data);

                  file_put_contents($destinationPath, $data);

                  DB::table('studinfo')
                        ->where('id',$studid)
                        ->update(['picurl'=>'storage/STUDENT/'.$sid.'.'.$extension ]);


                  $data = array(
                        (object)
                  [
                        'status'=>'1',
                  ]);
      
                  return $data;

            }
      
      }
      

      
}
