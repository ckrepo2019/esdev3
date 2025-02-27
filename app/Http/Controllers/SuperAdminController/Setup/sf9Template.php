<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;


class sf9Template extends \App\Http\Controllers\Controller
{

      /* 
            Notes:
                  join sf9templatedetail.type grade to data to subject_plot
                  join sf9templatedetail.type information to sf9templateinfo
                  join sf9templatedetail.type observerdvalues to grading_system_detail
                  join sf9templatedetail.type schooldays to studattendance_setup
                  join sf9templatedetail.type dayspresent to studattendance_setup
                  join sf9templatedetail.type daysabsent to studattendance_setup
         
      */


      public static function downloadformat(Request $request){

            $id = $request->get('id');

            $template = DB::table('sf9template')
                  ->join('sy',function($join){
                        $join->on('sf9template.syid','=','sy.id');
                  })
                  ->where('sf9template.deleted',0)
                  ->where('sf9template.id',$id)
                  ->select('sf9template.*','sydesc')
                  ->first();

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load(base_path().'/public/'.$template->filelocation);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
			
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="'.$template->description.' ('.$template->sydesc.').xlsx"');
            $writer->save("php://output");
            exit();

      }


      public static function excel_letters(){
            $letters = array();
            for($c = 'A'; $c !== 'DB'; $c++){
                  array_push($letters,$c);
            }
            return $letters;
      }

      public static function sf9template_list(Request $request){

            $syid = $request->get('syid');

            $sf9template = DB::table('sf9template')
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->select(
                                    '*',
                                    'description as text'
                              )
                              ->get();

            return $sf9template;

      }

      public static function sf9template_create(Request $request){

            $sf9templateDesription = $request->get('sf9templateDecription');
            $syid = $request->get('syid');
            $userid = auth()->user()->id;
            
            try{

                  $check = DB::table('sf9template')
                              ->where('description','like','%'.$sf9templateDesription.'%')
                              ->where('syid',$syid)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0 ){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Template Already Exist!'
                        ]);
                  }



                  DB::table('sf9template')
                        ->insert([
                              'description'=>$sf9templateDesription,
                              'syid'=>$syid,
                              'createdby'=>$userid,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Template Created!'
                  ]);


            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function sf9template_create_gradelevel(Request $request){
            $sf9templateid = $request->get('sf9templateid');
            $levelid = $request->get('levelid');
            $userid = auth()->user()->id;
            try{
                  $check = DB::table('sf9template_gradelvl')
                              ->where('headerid',$sf9templateid)
                              ->where('levelid',$levelid)
                              ->where('deleted',0)
                              ->count();

                  if($check == 0){
                        DB::table('sf9template_gradelvl')
                               ->insert([
                                    'headerid'=>$sf9templateid,
                                    'levelid'=>$levelid,
                                    'createdby'=>$userid,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        return array((object)[
                              'status'=>0,
                              'message'=>'Grade Level added to template!'
                        ]);

                  }else{
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already Exist!'
                        ]);
                  }
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function sf9template_delete_gradelevel(Request $request){
            $id = $request->get('id');
            $userid = auth()->user()->id;
            try{

                  DB::table('sf9template_gradelvl')
                        ->where('id',$id)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>$userid,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Grade Level Deleted!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function sf9template_create_strand(Request $request){
            $sf9templateid = $request->get('sf9templateid');
            $strandid = $request->get('strandid');
            $userid = auth()->user()->id;
            try{
                  $check = DB::table('sf9template_strand')
                              ->where('headerid',$sf9templateid)
                              ->where('strandid',$strandid)
                              ->where('deleted',0)
                              ->count();

                  if($check == 0){
                        DB::table('sf9template_strand')
                               ->insert([
                                    'headerid'=>$sf9templateid,
                                    'strandid'=>$strandid,
                                    'createdby'=>$userid,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        return array((object)[
                              'status'=>1,
                              'message'=>'Strand added to template!'
                        ]);

                  }else{
                        return array((object)[
                              'status'=>0,
                              'message'=>'Already Exist!'
                        ]);
                  }
            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function sf9template_delete_strand(Request $request){
            $id = $request->get('id');
            $userid = auth()->user()->id;
            try{

                  DB::table('sf9template_strand')
                        ->where('id',$id)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>$userid,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Grade Level Deleted!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function sf9template_update(Request $request){

            $sf9templateDesription = $request->get('sf9templateDecription');
            $sf9templateID = $request->get('sf9templateID');
            $userid = auth()->user()->id;
            
            try{

                  
                  $check = DB::table('sf9template')
                              ->where('description','like','%'.$sf9templateDesription.'%')
                              ->where('syid',$syid)
                              ->where('id','!=',$sf9templateID)
                              ->where('deleted',0)
                              ->count();

                  if($check > 0 ){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Template Already Exist!'
                        ]);
                  }

                  DB::table('sf9template')
                        ->where('id',$sf9templateID)
                        ->take(1)
                        ->update([
                              'description'=>$sf9templateDesription,
                              'updatedby'=>$userid,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Template Created!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
            
      }

      public static function sf9template_upload(Request $request){
          
            $sf9templateID = $request->get('sf9templateID');
            $userid = auth()->user()->id;
            
            try{

                  $urlFolder = str_replace('http://','',$request->root());
                  // $urlFolder = str_replace('http://','',$urlFolder);

                  if (! File::exists(public_path().'SF9/Template')) {
                      $path = public_path('SF9/Template');
                      if(!File::isDirectory($path)){
                          File::makeDirectory($path, 0777, true, true);
                      }
                  }
                  if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/SF9/Template')) {
                      $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/SF9/Template';
                      if(!File::isDirectory($cloudpath)){
                          File::makeDirectory($cloudpath, 0777, true, true);
                      }
                  }

                  $file = $request->file('sf9templates_file');
              
                  $extension = $file->getClientOriginalExtension();;

                  $destinationPath = public_path('SF9/Template');
                  $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/SF9/Template';
                  

                  $file->move($destinationPath, 'template'.$sf9templateID.'.'.$extension);

                  DB::table('sf9template')
                        ->where('id',$sf9templateID)
                        ->take(1)
                        ->update([
                              'filelocation'=>'SF9/Template/template'.$sf9templateID.'.xlsx',
                              'updatedby'=>$userid,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Template Uploaded!'
                  ]);

            }catch(\Exception $e){
                  return $e;
                  return self::store_error($e);
            }
            
      }

      public static function sf9template_delete(Request $request){

            $sf9templateID = $request->get('sf9templateID');
            $userid = auth()->user()->id;

            try{

                  DB::table('sf9template')
                        ->where('id',$sf9templateID)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>$userid,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('sf9templatedetail')
                        ->where('headerid',$sf9templateID)
                        // ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>$userid,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  DB::table('sf9template_gradelvl')
                        ->where('headerid',$sf9templateID)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>$userid,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Template Deleted!'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
            
      }


      
      public static function sf9templateinfo_list(Request $request){

            $sf9templateinfo = DB::table('sf9templateinfo')
                                    ->select(
                                          'sf9templateinfo.*',
                                          'sf9templateinfo.infodesc as text'
                                    )
                                    ->get();

            return $sf9templateinfo;
      }

      //sf9 template detail
      public static function sf9templatedetail_list(Request $request){

            $sf9templateid = $request->get('sf9templateid');

            $sf9templatedetail = DB::table('sf9templatedetail')
                              ->where('sf9templatedetail.headerid',$sf9templateid)
                              ->where('deleted',0)
                              ->get();



            $sf9templategradelevel = DB::table('sf9template_gradelvl')
                              ->join('gradelevel',function($join){
                                    $join->on('sf9template_gradelvl.levelid','=','gradelevel.id');
                              })
                              ->where('sf9template_gradelvl.headerid',$sf9templateid)
                              ->where('sf9template_gradelvl.deleted',0)
                              ->select(
                                    'sf9template_gradelvl.*',
                                    'levelname'
                              )
                              ->get();

           $sf9templatestrand = DB::table('sf9template_strand')
                              ->join('sh_strand',function($join){
                                    $join->on('sf9template_strand.strandid','=','sh_strand.id');
                              })
                              ->where('sf9template_strand.headerid',$sf9templateid)
                              ->where('sf9template_strand.deleted',0)
                              ->select(
                                    'sf9template_strand.*',
                                    'strandcode'
                              )
                              ->get();

            return array((object)[
                  'detail'=>$sf9templatedetail,
                  'gradelevel'=>$sf9templategradelevel,
                  'strand'=>$sf9templatestrand
            ]);

      }

      public static function sf9templatedetail_create(Request $request){
            

            try{

                  $type = $request->get('type');
                  $dataid = $request->get('data-id');
                  $headerid = $request->get('headerid');
                  $cell = $request->get('cell');
                  $dataquarter = $request->get('data-quarter');

                  $check = DB::table('sf9templatedetail')
                              ->where('type',$type)
                              ->where('headerid',$headerid)
                              ->where('dataid',$dataid)
                              ->where('quarter',$dataquarter)
                              ->where('deleted',0)
                              ->count();

                  if($check == 0){
                        DB::table('sf9templatedetail')
                              ->insert([
                                    'headerid'=>$headerid,
                                    'type'=>$type,
                                    'dataid'=>$dataid,
                                    'cellvalue'=>$cell,
                                    'quarter'=>$dataquarter,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        DB::table('sf9templatedetail')
                              ->where('type',$type)
                              ->where('headerid',$headerid)
                              ->where('dataid',$dataid)
                              ->where('quarter',$dataquarter)
                              ->where('deleted',0)
                              ->update([
                                    'cellvalue'=>$cell,
                                    'quarter'=>$dataquarter,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                 
               
            }catch(\Exception $e){
                  return $e;
            }


      }

      public static function sf9templatedetail_update(Request $request){
            
      }

      public static function sf9templatedetail_delete(Request $request){
            
            $detailid = $request->get('detailid');

            try{
                  DB::table('sf9templatedetail')
                        ->where('id',$detailid)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\CArbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Student Information Deleted!'
                  ]);
            
            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function sf9templatedetail_copy(Request $request){
            
            $from = $request->get('from');
            $to = $request->get('to');

            try{

                  $from_information = DB::table('sf9templatedetail')
                                          ->where('headerid',$from)
                                          ->where('deleted',0)
                                          ->whereIn('type',['information','grades','genave2nd','genave1st','genave'])
                                          ->get();

                  foreach($from_information as $item){

                        DB::table('sf9templatedetail')
                              ->insert([
                                    'headerid'=>$to,
                                    'type'=>$item->type,
                                    'dataid'=>$item->dataid,
                                    'cellvalue'=>$item->cellvalue,
                                    'createdby'=>auth()->user()->id,
                                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                    'quarter'=>$item->quarter
                              ]);

                  }

                  return array((object)[
                        'status'=>1,
                        'message'=>'Detail Copied!'
                  ]);
            
            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function store_error($e){
            DB::table('zerrorlogs')
            ->insert([
                        'error'=>$e,
                        //'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            return array((object)[
                  'status'=>0,
                  'message'=>'Something went wrong!'
            ]);
      }
    
}
