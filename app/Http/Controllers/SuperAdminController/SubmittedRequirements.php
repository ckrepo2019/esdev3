<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use File;
use Image;

class SubmittedRequirements extends \App\Http\Controllers\Controller
{

      public static function downloadall(Request $request){
            
            
            $uploadeddocuments = self::uploadeddocs($request);

            $students = DB::table('studinfo')
                  ->where('studinfo.deleted',0)
                  ->where('id',$request->get('studid'))
                  ->select(
                        'studinfo.id',
                        'studinfo.levelid',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'sid',
                        DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                  )
                  ->first();



            $zip = new \ZipArchive;
            if ($zip->open('doc.zip', \ZipArchive::CREATE) === TRUE)
            {
                  foreach($uploadeddocuments as $uploadeddocument){
                       
                        foreach($uploadeddocument->uploaded as $item){
                           
                              $zip->addFile(substr($item->picurl, 1), $item->filename);
                        }
                  }
                  

                  $zip->close();
            }

            header("Content-type: application/zip"); 
            header("Content-Disposition: attachment; filename=".$students->studentname." (Requirements) ".\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYYYHHMmmss').".zip"); 
            header("Pragma: no-cache"); 
            header("Expires: 0"); 
            readfile("doc.zip");

            ignore_user_abort(true);
            unlink("doc.zip");
            exit();
      
      }


      public static function uploadeddocs(Request $request){

            $studid = $request->get('studid');
            $levelid = $request->get('levelid');
            $sid = $request->get('sid');

            $documents = \App\Http\Controllers\SuperAdminController\DocumentsController::list($request);

            $uploadeddocuments = DB::table('preregistrationreqregistrar')
                                          ->join('preregistrationreqlist',function($join){
                                                $join->on('preregistrationreqregistrar.requirement','=','preregistrationreqlist.id');
                                                $join->where('preregistrationreqlist.deleted',0);
                                          })
                                          ->where('studid',$studid)
                                          ->where('preregistrationreqregistrar.deleted',0)
                                          ->select(
                                                'preregistrationreqregistrar.*',
                                                'description'
                                          )
                                          ->get();

            $uploadedbystud =  $submitted = DB::table('preregistrationrequirements')
                                    ->join('preregistrationreqlist',function($join){
                                          $join->on('preregistrationrequirements.preregreqtype','=','preregistrationreqlist.id');
                                          $join->where('preregistrationreqlist.deleted',0);
                                    })
                                    ->where('preregistrationrequirements.qcode',$sid)
                                    ->where('preregistrationrequirements.deleted',0)
                                    ->select(
                                          'preregistrationrequirements.*',
                                          'preregistrationrequirements.preregreqtype as requirement',
                                          'description'
                                    )
                                    ->get();

            $uploadeddocuments = collect($uploadeddocuments)->toArray();
            
            foreach($uploadedbystud as $uploadedbystuditem){

                  $explode_picurl = explode('/',$uploadedbystuditem->picurl);
                  $uploadedbystuditem->filetype = 'image';
                  $uploadedbystuditem->filename = $explode_picurl[1];

                  $uploadedbystuditem->picurl = '/'.$explode_picurl[0]."/".$sid.'/'.$explode_picurl[1];

                  array_push($uploadeddocuments,$uploadedbystuditem);


            }
            


            foreach($documents as $item){
                  $check = collect($uploadeddocuments)
                              ->where('requirement',$item->id)
                              ->values();

                  $item->uploaded = $check;
            
            }

            return $documents;
      }

      public static function deleteuploadeddocs(Request $request){


            try{
                  $studid = $request->get('studid');
                  $requirement = $request->get('requirement');
                  $id = $request->get('id');
      
                  $uploadeddocuments = DB::table('preregistrationreqregistrar')
                                                ->where('studid',$studid)
                                                ->where('requirement',$requirement)
                                                ->where('id',$id)
                                                ->update([
                                                      'deleted'=>1,
                                                      'deletedby'=>auth()->user()->id,
                                                      'deleteddatetime'=>\Carbon\Carbon::now()
                                                ]);

                  return array((object)[
                        'icon'=>'success',
                        'status'=>1,
                        'message'=>'Document Deleted'
                  ]);
      
            }catch(\Exception $e){
                  return self::store_error($e);
            }

            
      }

      public static function students(Request $request){

            $search = $request->get('search');
            $search = $search['value'];

            $students = DB::table('studinfo')
                              ->where('studinfo.deleted',0)
                              ->where('studinfo.studisactive',1)
                              ->where(function($query) use($search){
                                    if($search != null){
                                          $query->where('sid','like','%'.$search.'%');
                                          $query->orWhere('lastname','like','%'.$search.'%');
                                          $query->orWhere('firstname','like','%'.$search.'%');
                                          $query->orWhere('middlename','like','%'.$search.'%'); 
                                    }
                              })
                              ->join('gradelevel',function($join){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->select(
                                    'studinfo.id',
                                    'studinfo.levelid',
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'sid',
                                    'studtype',
                                    'gradelevel.levelname',
                                    DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                              )
                              ->take($request->get('length'))
                              ->skip($request->get('start'))
                              ->orderBy('studentname')
                              ->get();

            $student_count = DB::table('studinfo')
                              ->where('studinfo.deleted',0)
                              ->where('studinfo.studisactive',1)
                              ->join('gradelevel',function($join){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where(function($query) use($search){
                                    if($search != null){
                                          $query->where('sid','like','%'.$search.'%');
                                          $query->orWhere('lastname','like','%'.$search.'%');
                                          $query->orWhere('firstname','like','%'.$search.'%');
                                          $query->orWhere('middlename','like','%'.$search.'%'); 
                                    }
                              })
                              ->count();

            $reqsetup = DB::table('preregistrationreqlist')
                              ->where('deleted',0)
                              ->get();

            foreach($students as $item){

                  $docnum = collect($reqsetup)
                                    ->whereIn('doc_studtype',[$item->studtype,null])
                                    ->where('levelid',$item->levelid)
                                    ->values();

                  $item->docnum = count($docnum);


                  $item->levelname = str_replace(' COLLEGE','',$item->levelname);

                  $uploadeddocuments = DB::table('preregistrationreqregistrar')
                                          ->join('preregistrationreqlist',function($join){
                                                $join->on('preregistrationreqregistrar.requirement','=','preregistrationreqlist.id');
                                                $join->where('preregistrationreqlist.deleted',0);
                                          })
                                          ->select(
                                                'preregistrationreqregistrar.*',
                                                'description'
                                          )
                                          ->where('studid',$item->id)
                                          ->where('preregistrationreqregistrar.deleted',0)
                                          ->get();

                  $sid = $item->sid;

                  $uploadedbystud =  $submitted = DB::table('preregistrationrequirements')
                                          ->where('qcode',$sid)
                                          ->where('deleted',0)
                                          ->select(
                                                'preregistrationrequirements.*',
                                                'preregistrationrequirements.preregreqtype as requirement'
                                          )
                                          ->get();

                  $uploadeddocuments = collect($uploadeddocuments)->toArray();
                  
                  foreach($uploadedbystud as $uploadedbystuditem){
                        array_push($uploadeddocuments,$uploadedbystuditem);
                  }
                  

                  $item->uploaded =  $uploadeddocuments;

                  $item->monitoring = collect($uploadeddocuments)
                                          ->whereIn('requirement',collect($docnum)->pluck('id'))
                                          ->unique('requirement')
                                          ->count();

                                          


            }


            return @json_encode((object)[
                'data'=>$students,
                'recordsTotal'=>$student_count,
                'recordsFiltered'=>$student_count
            ]);


      }      
      
      public static function upload(Request $request){

          
            $studid = $request->get('studid');
            $levelid = $request->get('levelid');
            $sid = $request->get('sid');

            try{

                  $urlFolder = str_replace('http://','',$request->root());

                  if (! File::exists(public_path().'Student/Documents/'.$sid)) {
                      $path = public_path('Student/Documents/'.$sid);
                      if(!File::isDirectory($path)){
                          File::makeDirectory($path, 0777, true, true);
                      }
                  }
                  if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/Student/Documents/'.$sid)) {
                      $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/Student/Documents/'.$sid;
                      if(!File::isDirectory($cloudpath)){
                          File::makeDirectory($cloudpath, 0777, true, true);
                      }
                  }

                  $documents = \App\Http\Controllers\SuperAdminController\DocumentsController::list($request);

                  foreach($documents as $item){
                        if($item->isActive == 1){
                              if($request->has('req'.$item->id) != null){
                                    $counting = 0;
                                    foreach($request->file('req'.$item->id) as $fileitem){
                                          $date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYYYHHmmss');
                                          $file = $fileitem;
                  
                                          $extension = $file->getClientOriginalExtension();
                                          $name = $file->getClientOriginalName();
                                          $filetype =  $file->getMimeType();
                                          $size =  $file->getSize();
                                          
                                          $check_if_exist = DB::table('preregistrationreqregistrar')
                                                                  ->where('originalfilename',$name)
                                                                  ->where('studid',$studid)
                                                                  // ->where('deleted',0)
                                                                  ->get();

                                       

                                          if(count($check_if_exist) > 0){

                                                $last = count($check_if_exist);
                                                $exist = true; 
                                                do{
                                                      $explodedname = explode('.',$name);
                                                      $newName = $explodedname[0]. ' ('.($last).')';
                                                      $tempname = '';
                                                      $count = 0;
                                                      foreach($explodedname as $explodeditem){
                                                            if($count == 0){
                                                                  $tempname = $newName;
                                                            }else{
                                                                  $tempname.= '.'.$explodeditem;
                                                            }
                                                            $count += 1;
                                                      }

                                                      $check = collect($check_if_exist)
                                                                  ->where('filename',$tempname)
                                                                  ->count();

                                                      if($check == 0){
                                                            $name = $tempname;
                                                            $exist = false;
                                                      }else{
                                                            $last += 1;
                                                      }

                                                }while($exist);

                                                
                                                $name =  $tempname;
                                          }
                  
                                          $destinationPath = public_path('Student/Documents/'.$sid);
                                          $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/Student/Documents/'.$sid;
                                          // $despath = 'document-'.$sid.'-'.$item->id.'-'.$counting.'-'.$date.'.'.$extension;
                                          $despath = $name;

                                          $file->move($destinationPath, $despath);
                  
                                          $counting += 1;

                                          DB::table('preregistrationreqregistrar')
                                                ->insert([
                                                      'studid'=>$studid,
                                                      'filetype'=>$filetype,
                                                      'filesize'=>$size,

                                                      'requirement'=>$item->id,
                                                      'picurl'=>'/Student/Documents/'.$sid.'/'.$name,
                                                      'createdby'=>auth()->user()->id,
                                                      'filename'=>$name,
                                                      'originalfilename'=>$file->getClientOriginalName(),
                                                      'createddatetime'=>\Carbon\Carbon::now()
                                                ]);
                  
                                    }
                              }
                        }
                  }



                  return array((object)[
                        'icon'=>'success',
                        'status'=>1,
                        'message'=>'Document Uploaded'
                  ]);
            }catch(\Exception $e){
                  return $e;
                  return self::store_error($e);
            }

      }

      public static function store_error($e){
            DB::table('zerrorlogs')
            ->insert([
                        'error'=>$e,
                        // 'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            return array((object)[
                  'status'=>0,
                  'icon'=>'error',
                  'message'=>'Something went wrong!'
            ]);
        }

}
