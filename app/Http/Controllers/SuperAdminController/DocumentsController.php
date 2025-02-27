<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class DocumentsController extends \App\Http\Controllers\Controller
{
      //attendance setup start
      public static function list(Request $request){
            $id = $request->get('id');
            $acadprog = $request->get('acadprog');
            $isactive = $request->get('isactive');
            $required = $request->get('required');
            $levelid = $request->get('levelid');
            return self::documents_list($id, $acadprog, $isactive , $required, $levelid);
      }

      public static function create(Request $request){
            $decription = $request->get('description');
            $docsort = $request->get('sequence');
            $isactive = $request->get('isactive');
            $acadprog = $request->get('acadprog');
            $isrequired = $request->get('isrequired');
            $levelid = $request->get('levelid');
            $studtype = $request->get('studtype');
            $headerid = $request->get('headerid');
            return self::documents_create($decription, $isactive, $acadprog, $isrequired, $docsort, $levelid, $studtype, $headerid);
      }
      public static function update(Request $request){
            $documentid = $request->get('documentid');
            $decription = $request->get('description');
            $docsort = $request->get('sequence');
            $isactive = $request->get('isactive');
            $acadprog = $request->get('acadprog');
            $isrequired = $request->get('isrequired');
            $levelid = $request->get('levelid');
            $studtype = $request->get('studtype');
            return self::documents_update($documentid, $decription, $isactive, $acadprog, $isrequired, $docsort, $levelid, $studtype);
      }
      public static function delete(Request $request){
            $documentid = $request->get('documentid');
            return self::documents_delete($documentid);
      }
      public static function copy(Request $request){
            $documentid = $request->get('documentid');
            $gradelevel_from = $request->get('gradelevel_from');
            $gradelevel_to = $request->get('gradelevel_to');
            return self::documents_copy($documentid, $gradelevel_from, $gradelevel_to);
      }
      //attendance setup end

      //proccess
      public static function documents_create(
            $decription = null, 
            $isactive = null,
            $acadprog  = null,
            $isrequired = null,
            $docsort = null,
            $levelid = null,
            $studtype = null,
            $headerid = null
      ){
            try{
                  $check_if_exist = $document_info = DB::table('preregistrationreqlist')
                                                                  ->where('description',$decription)
                                                                  ->where('levelid',$levelid)
                                                                  ->where('deleted',0)
                                                                  ->get();

                  if(count($check_if_exist) > 0){

                        return array((object)[
                              'status'=>2,
                              'data'=>'Document already exist!',
                        ]);

                  }

                  $documents_id = DB::table('preregistrationreqlist')
                        ->insertGetId([
                              'description'=>$decription,
                              'acadprogid'=>$acadprog,
                              'isActive'=>$isactive,
                              'docsort'=>$docsort,
                              'isRequired'=>$isrequired,
                              'levelid'=>$levelid,
                              'doc_studtype'=>$studtype,
                              'headerid'=>$headerid,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $document_setup = self::documents_list(null,null,null,null,$levelid);

                  $message = auth()->user()->name.' added new document requirement';
                  
                  self::create_logs($message,$documents_id);

                  return array((object)[
                        'status'=>1,
                        'data'=>'Created Successfully!',
                        'info'=> $document_setup
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function documents_update(
            $documentid = null,
            $decription = null, 
            $isactive = null,
            $acadprog  = null,
            $isrequired = null,
            $docsort = null,
            $levelid = null,
            $studtype = null
      ){
            try{
                  DB::table('preregistrationreqlist')
                        ->take(1)
                        ->where('id',$documentid)
                        ->where('deleted',0)
                        ->update([
                              'description'=>$decription,
                              'acadprogid'=>$acadprog,
                              'isActive'=>$isactive,
                              'isRequired'=>$isrequired,
                              'docsort'=>$docsort,
                              'levelid'=>$levelid,
                              'doc_studtype'=>$studtype,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);

                  $message = auth()->user()->name.' updated a document requirement';
                  self::create_logs($message,$documentid);

                  $document_setup = self::documents_list(null,null,null,null,$levelid);

                  return array((object)[
                        'status'=>1,
                        'info'=>$document_setup,
                        'data'=>'Updated Successfully!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      
      public static function documents_copy(
            $documentid = null,
            $gradelevel_from = null,
            $grade_to = null
      ){
            try{
                  $document_info = array();

                  if($documentid != null){

                        $document_info = DB::table('preregistrationreqlist')
                                                ->where('id',$documentid)
                                                ->get();

                  }else{

                        $document_info = DB::table('preregistrationreqlist')
                                                ->where('levelid',$gradelevel_from)
                                                ->where('deleted',0)
                                                ->get();

                  }

                  $copy_count = 0;
                  foreach($document_info as $item){
                        foreach($grade_to as $level_item){
                              
                              $status = self::documents_create(
                                                $item->description, 
                                                $item->isActive, 
                                                $item->acadprogid, 
                                                $item->isRequired, 
                                                $item->docsort, 
                                                $level_item,
                                                $item->doc_studtype
                                          );

                              if($status[0]->status == 1){
                                    $copy_count += 1;
                              }
                        }
                  }

                  return array((object)[
                        'status'=>1,
                        'data'=>$copy_count . ' document added!'
                  ]);

            }catch(\Exception $e){

                  return $e;
                  return self::store_error($e);
            }
      }

       
      public static function documents_delete(
            $documentid = null
      ){
            try{

                  $temp_info = DB::table('preregistrationreqlist')
                                    ->where('id',$documentid)
                                    ->first();

                  DB::table('preregistrationreqlist')
                        ->take(1)
                        ->where('id',$documentid)
                        ->where('deleted',0)
                        ->update([
                              'deleted'=>1,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'updatedby'=>auth()->user()->id
                        ]);
                        
                  $message = auth()->user()->name.' removed a document requirement';
                  self::create_logs($message,$documentid);

                  $document_setup = self::documents_list(null,null,null,null,$temp_info->levelid);

                  return array((object)[
                        'status'=>1,
                        'info'=>$document_setup,
                        'data'=>'Updated Successfully!'
                  ]);
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      
      //data
      public static function documents_list($id = null, $acadprog = null, $isactive = null, $isrequired = null, $levelid = null){
            $documents = DB::table('preregistrationreqlist')
                              ->leftJoin('academicprogram',function($join){
                                    $join->on('preregistrationreqlist.acadprogid','=','academicprogram.id');
                              })
                              ->where('deleted',0);

            if($id != null){
                  $documents = $documents->where('preregistrationreqlist.id',$id);
            }
            if($acadprog != null){
                  $documents = $documents->where('preregistrationreqlist.acadprogid',$acadprog);
            }
            if($isactive != null){
                  $documents = $documents->where('preregistrationreqlist.isActive',$isactive);
            }
            if($isrequired != null){
                  $documents = $documents->where('preregistrationreqlist.isRequired',$syid);
            }
            if($levelid != null){
                  $documents = $documents->where('preregistrationreqlist.levelid',$levelid);
            }

            $documents = $documents
                        ->select('preregistrationreqlist.*','progname')
                        ->get();

            return $documents;
            
      }


      public static function logs($syid = null){
            return DB::table('logs')->where('module',1)->get();
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
                  'data'=>'Something went wrong!'
            ]);
      }

      public static function create_logs($message = null, $id = null){
           DB::table('logs') 
             ->insert([
                  'dataid'=>$id,
                  'module'=>3,
                  'message'=>$message,
                  'createdby'=>auth()->user()->id,
                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
      }


      
     
      

}
