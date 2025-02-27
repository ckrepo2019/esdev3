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
                                          // dd($uploadeddocuments);

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
            
            // dd($uploadeddocuments);
            // dd($documents);

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

      public static function students(Request $request) {
            $search = $request->get('search')['value'] ?? null;
        
            // Determine the pagination values
            $length = $request->get('length', 10); // Default length to 10 if not provided
            $start = $request->get('start', 0); // Default start to 0 if not provided
        
            // Query to fetch students
            $studentsQuery = DB::table('studinfo')
                ->where('studinfo.deleted', 0)
                ->where('studinfo.studisactive', 1)
                ->join('gradelevel', function($join) {
                    $join->on('studinfo.levelid', '=', 'gradelevel.id')
                         ->where('gradelevel.deleted', 0);
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
                    DB::raw("CONCAT(studinfo.lastname, ' ', studinfo.firstname) as studentname")
                )
                ->orderBy('studentname');
        
            // Apply search filters if search value is present
            if ($search !== null) {
                $studentsQuery->where(function($query) use($search) {
                    $query->where('sid', 'like', '%' . $search . '%')
                          ->orWhere('lastname', 'like', '%' . $search . '%')
                          ->orWhere('firstname', 'like', '%' . $search . '%')
                          ->orWhere('middlename', 'like', '%' . $search . '%');
                });
            }
        
            // Apply pagination with LIMIT and OFFSET
            $students = $studentsQuery
                ->limit($length)
                ->offset($start)
                ->get();
        
            // Get the total count of students matching the criteria
            $studentCountQuery = DB::table('studinfo')
                ->where('studinfo.deleted', 0)
                ->where('studinfo.studisactive', 1)
                ->join('gradelevel', function($join) {
                    $join->on('studinfo.levelid', '=', 'gradelevel.id')
                         ->where('gradelevel.deleted', 0);
                });
        
            if ($search !== null) {
                $studentCountQuery->where(function($query) use($search) {
                    $query->where('sid', 'like', '%' . $search . '%')
                          ->orWhere('lastname', 'like', '%' . $search . '%')
                          ->orWhere('firstname', 'like', '%' . $search . '%')
                          ->orWhere('middlename', 'like', '%' . $search . '%');
                });
            }
        
            $studentCount = $studentCountQuery->count();
        
            // Fetch pre-registration requirements setup
            $reqSetup = DB::table('preregistrationreqlist')
                ->where('deleted', 0)
                ->get();
        
            foreach ($students as $student) {
                // Filter the documents required based on student type and level
                $docNum = $reqSetup
                    ->whereIn('doc_studtype', [$student->studtype, null])
                    ->where('levelid', $student->levelid)
                    ->count();
        
                $student->docnum = $docNum;
                $student->levelname = str_replace(' COLLEGE', '', $student->levelname);
        
                // Get uploaded documents by the student
                $uploadedDocuments = DB::table('preregistrationreqregistrar')
                    ->join('preregistrationreqlist', function($join) {
                        $join->on('preregistrationreqregistrar.requirement', '=', 'preregistrationreqlist.id')
                             ->where('preregistrationreqlist.deleted', 0);
                    })
                    ->where('studid', $student->id)
                    ->where('preregistrationreqregistrar.deleted', 0)
                    ->select('preregistrationreqregistrar.*', 'description')
                    ->get();

                    // Iterate through each document to check the 'picurl' validity
                  foreach ($uploadedDocuments as $document) {
                        // Check if the picurl exists and is not null
                        if (isset($document->picurl) && !empty($document->picurl)) {
                        // Construct the full path to the file
                        $filePath = public_path($document->picurl);
                  
                        // Check if the file exists on the server
                        if (file_exists($filePath)) {
                              $document->picurl_valid = true;
                        } else {
                              $document->picurl_valid = false;
                        }
                        } else {
                        $document->picurl_valid = false; // Mark as invalid if picurl is not set or empty
                        }
                  }
        
                // Get additional documents submitted by the student using QR code
                $uploadedByStud = DB::table('preregistrationrequirements')
                    ->where('qcode', $student->sid)
                    ->where('deleted', 0)
                    ->select('preregistrationrequirements.*', 'preregistrationrequirements.preregreqtype as requirement')
                    ->get();
        
                // Combine the documents
                $request->merge(['levelid' => $student->levelid]);
                $documents = \App\Http\Controllers\SuperAdminController\DocumentsController::list($request);

                $uploads = $uploadedDocuments->merge($uploadedByStud); // Merging uploaded docs by registrar and student
                
                // Initialize an empty array for student uploads
                $student->uploaded = [];
                
                // Loop through each document and check if it's uploaded
                foreach ($documents as $item) {
                    // Collect uploaded documents that match the current document's requirement
                    $check = collect($uploads)->where('requirement', $item->id)->values();
                
                    // If there's any matching document uploaded, add the item to the student's uploaded list
                    if ($check->isNotEmpty()) {
                        $student->uploaded = array_merge($student->uploaded, $check->toArray()); // Merge arrays
                    }
                
                
                    // Attach the uploaded document(s) to the current item
                  //   $item->uploaded = $check;
                }
                
                // Convert $student->uploaded to a collection for further operations
                $uploadedCollection = collect($student->uploaded);
                
                // Count unique required documents uploaded by the student based on the `requirement` field
                $student->monitoring = $uploadedCollection
                    ->whereIn('requirement', $reqSetup->pluck('id')) // Filter based on required document IDs
                    ->unique('requirement') // Remove duplicate entries based on the requirement ID
                    ->count();
                
            }

         

            // dd($students);
        
            return response()->json([
                'data' => $students,
                'recordsTotal' => $studentCount,
                'recordsFiltered' => $studentCount
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
