<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;

class DocumentsDescController extends \App\Http\Controllers\Controller
{
    //docdesc setup start
    public static function list(Request $request){
        $id = $request->get('id');
        return self::docdesc_list($id);
    }

    public static function create(Request $request){
        $description = $request->get('description');
        return self::docdesc_create($description);
    }

    public static function update(Request $request){
        $docdescid = $request->get('docdescid');
        $description = $request->get('description');
        return self::docdesc_update($docdescid, $description);
    }

    public static function delete(Request $request){
        $docdescid = $request->get('docdescid');
        return self::docdesc_delete($docdescid);
    }
    //docdesc setup end


    //proccess
    public static function docdesc_create(
        $description = null
    ){
        try{
                $check_if_exist = DB::table('preregistration_docdesc')
                    ->where('description', $description)
                    ->where('deleted',0)
                    ->get();

                if(count($check_if_exist) > 0){
                    return array((object)[
                            'status'=>2,
                            'message'=>'Document description already exist!',
                    ]);
                }

                $docdescid = DB::table('preregistration_docdesc')
                    ->insertGetId([
                            'description'=>$description,
                            'createdby'=>auth()->user()->id,
                            'deleted'=>0,
                            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                $docdesc_setup = self::docdesc_list($docdescid);

                $message = auth()->user()->name.' added new document description';
                
                self::create_logs(
                    $message,
                    $docdescid
                );

                return array((object)[
                    'status'=>1,
                    'message'=>'Created Successfully!',
                    'info'=> $docdesc_setup
                ]);

        }catch(\Exception $e){
                return self::store_error($e);
        }
    }

    public static function docdesc_update(
        $docdescid = null,
        $description = null
    ){
        try{
                DB::table('preregistration_docdesc')
                    ->take(1)
                    ->where('id', $docdescid)
                    ->where('deleted',0)
                    ->update([
                            'description'=>$description,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                            'updatedby'=>auth()->user()->id
                    ]);

                $message = auth()->user()->name.' updated a document description';
                self::create_logs($message, $docdescid);

                $docdesc_setup = self::docdesc_list($docdescid);

                return array((object)[
                    'status'=>1,
                    'info'=>$docdesc_setup,
                    'message'=>'Updated Successfully!'
                ]);

        }catch(\Exception $e){
                return self::store_error($e);
        }
    }
    
    public static function docdesc_delete(
        $docdescid = null
    ){
        try {


                $check = DB::table('preregistrationreqlist')
                    ->where('headerid',$docdescid)
                    ->where('deleted',0)
                    // ->get();
                    ->count();

                // dd($check);
                
                if($check > 0){
                    return array((object)[
                        'status'=>0,
                        'message'=>'Document in used',
                        'icon'=>'error'
                    ]);
                }


                DB::table('preregistration_docdesc')
                    ->take(1)
                    ->where('id',$docdescid)
                    ->where('deleted',0)
                    ->update([
                            'deleted'=>1,
                            'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                            'updatedby'=>auth()->user()->id
                    ]);
                    
                $message = auth()->user()->name.' removed a document description';
                self::create_logs($message,$docdescid);

                $docdesc_setup = self::docdesc_list($docdescid);

                return array((object)[
                    'status'=>1,
                    'info'=>$docdesc_setup,
                    'message'=>'Deleted Successfully!'
                ]);
        } catch(\Exception $e) {
                return self::store_error($e);
        }
    }

    //data
    public static function docdesc_list(
        $id = null
    ){
        $documents = DB::table('preregistration_docdesc')
                        ->where('deleted',0);

        if($id != null){
                $documents = $documents->where('preregistration_docdesc.id', $id);
        }

        $documents = $documents
                    ->select(
                        'id',
                        'description as text'
                    )
                    ->get();

        return $documents;
        
    }

    public static function create_logs(
        $message = null,
        $id = null
    ){
        DB::table('logs') 
        ->insert([
            'dataid'=>$id,
            'module'=>4,
            'message'=>$message,
            'createdby'=>auth()->user()->id,
            'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        ]);
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
}
