<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Image;
use File;

class SyncController extends Controller
{
  public function syncdb($tablename, $maxid)
  {
    $max = db::table($tablename)
        ->max('id');

    $data = db::table($tablename)
        ->whereBetween('id', [$maxid + 1, $max])
        ->get();

    return $data;
  }

  // public function syncappend($tablename)
  // {
  //   return \Response::json();

  // }

  public function synccheck(Request $request)
  {
    if($request->ajax())
    {
      $tablename = $request->get('tablename');

      $syncsetup = SyncModel::syncsetup();

      if($syncsetup->type == 'LOCAL')
      {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $syncsetup->url. '/synccheckreturn/' . $tablename);
        $result = $response->getBody()->getContents();

        $curRow = db::table($tablename)
          ->max('id');


        if($result > $curRow)
        {
          //no code to be used
        }
        else if($result < $curRow)
        {
          $rows = db::table($tablename)
              ->whereBetween('id', [$result+1, $curRow])
              ->get();

          $append = new \GuzzleHttp\Client([
            'headers' => ['Content-Type' => 'application/json']
          ]);

          $response = $append->post('http://es.ck/syncappend/' . $tablename, $rows);

          $response = json_decode($response->getBody(), true);
        }

      }

    }
  }


  public function syncupdatereturn($tablename,$date)
  {

    $dateto =  Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD hh:mm:ss');
    $datefrom = Carbon::create('2020-05-01 00:00:0')->isoFormat('YYYY-MM-DD hh:mm:ss');

    try{

      $list = db::table($tablename)
              ->whereBetween('updateddatetime', [$datefrom, $dateto])
              ->get();

      return $list;

    }catch (\Exception $e) {
  
      return 0;

    }
   
  }


  public function syncdeletedreturn($tablename,$date)
  {

    $dateto =  Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD hh:mm:ss');
    $datefrom = Carbon::create('2020-05-01 00:00:0')->isoFormat('YYYY-MM-DD hh:mm:ss');

    try{

      $list = db::table($tablename)
              ->whereBetween('deleteddatetime', [$datefrom, $dateto])
              ->get();

      return $list;

    }catch (\Exception $e) {
  
      return 0;

    }
   
  }

  public function synccheckreturn($tablename, $curmaxid)
  {
    $maxid = db::table($tablename)->max('id');

    if($maxid > $curmaxid)
    {

      $return = db::table($tablename)
          ->whereBetween('id', [$curmaxid + 1, $maxid])
          ->get();

      return $return;

    }
    else
    {
      return 0;
    }
    
  }

  // working
  public function checktargetmaxtable($tablename){

    try{

      $maxid = db::table($tablename)->max('id');

      return $maxid;

    }catch (\Exception $e) {

      if(str_contains($e->getMessage(),'doesn\'t exist')){

        return "not found";

      }
      else if(str_contains($e->getMessage(),'Unknown column')){

        $maxid = db::table($tablename)->max('ID');

        return $maxid;

      }
  
    }

  }

  //working
  public function updatetargettable(Request $request){

    $data = json_decode($request->get('data'),true);

    try{

      db::table($request->get('table'))
        ->where('id', $data['id'])
        ->update(collect($data)->toArray());

      if($request->get('table') == 'teacher'){

        DB::table('users')
            ->where('id',$data['userid'])
            ->update(['type'=>$data['usertypeid']]);

      }

      return "ok";

    }catch (\Exception $e) {
        
      try{

        db::table($request->get('table'))
          ->where('ID', $data['ID'])
          ->update(collect($data)->toArray());
          
        return "ok";

      }catch (\Exception $e) {

        return $e;
        
      }

    }

  }

  //working
  public function deletetargettable(Request $request){

    $data = json_decode($request->get('data'),true);


    try{

      db::table($request->get('table'))
            ->where('id', $data['id'])
            ->update([
              'deleted' => $data['deleted'],
              'deleteddatetime' => $data['deleteddatetime'],
              'deletedby' => $data['deletedby']
            ]);

      return "ok";

    }catch (\Exception $e) {


      try{

        db::table($request->get('table'))
            ->where('ID', $data['ID'])
            ->update([
              'deleted' => $data['deleted'],
              'deleteddatetime' => $data['deleteddatetime'],
              'deletedby' => $data['deletedby']
            ]);

        return "ok";

      }catch (\Exception $e) {

        return $e;
        
      }

    }

  }




  public function insertdatatotable(Request $request){

    $data = json_decode($request->get('data'),true);

    try{

      DB::table($request->get('table'))
            ->insert($data);

      return 0;

    }catch (\Exception $e) {

      return $e;

    }
    
  }

  public function cloudNewData($tablename, $maxid){

    $max = db::table($tablename)->max('id');

    $data = db::table($tablename)
        ->whereBetween('id', [$maxid + 1, $max])
        ->get();

    return $data;

  }

  public function cloudUpdatedData($tablename, $date = null){

    $dateto =  Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');
    $datefrom = Carbon::create($date)->isoFormat('YYYY-MM-DD HH:mm:ss');
    
    $data = db::table($tablename)
            ->whereBetween('updateddatetime', [$datefrom, $dateto])
            ->get();

    return  $data;

  }

  public function cloudDeletedData($tablename, $date = null){

    $dateto =  Carbon::now('Asia/Manila')->isoFormat('YYYY-MM-DD HH:mm:ss');
    $datefrom = Carbon::create($date)->isoFormat('YYYY-MM-DD HH:mm:ss');

    try{

      $data = db::table($tablename)
              ->whereBetween('deleteddatetime', [$datefrom, $dateto])
              ->get();

      return $data;

    }catch (\Exception $e) {

      if(str_contains($e->getMessage(),'doesn\'t exist')){

        return "not found";

      }
      else if(str_contains($e->getMessage(),'Unknown column')){

        return "not found";

      }
  
    }

  }

  public function cloudGetSyncSetup(){

    return DB::table('syncsetup')->get();

  }


  public function storeImage(Request $request){

    $client = new \GuzzleHttp\Client();
    $response = file_get_contents( $request->get('imagepath'));

    $folderPath = explode('/',$request->get('imagepath'));

    if($request->get('tablename') == 'onlinepayments'){
      
      if (! File::exists(public_path().'onlinepayments/'.$folderPath[4])) {

        $path = public_path('onlinepayments/'.$folderPath[4]);

        if(!File::isDirectory($path)){

            File::makeDirectory($path, 0777, true, true);
        }
        
      }

      file_put_contents(public_path('onlinepayments/'.$folderPath[4].'/'.$folderPath[5]), $response);

    }

  }

  public function getOfflinerefIdMax($tablename){

    $data =  DB::table($tablename)->where('grefid','like','%'.'OF'.'%')->latest('grefid')->get();

    if(count($data) > 0 ){

      return $data[0]->grefid;

    }else{

      return 0;

    }


  }

    public function cloudtesting(Request $request){

      $data = array((object)[
        'status'=>1,
        'url'=>$request->root()
      ]);

      return  $data;

    }
    
    public function localCheckConnection(){

      try{

          $client = new \GuzzleHttp\Client();
          $syncsetup = DB::table('syncsetup')->first();
          $response = $client->request('GET', $syncsetup->url. '/cloudtesting');
          $result = json_decode($response->getBody()->getContents()); 

      }catch (\Exception $e) {

          $result = array((object)[
              'status'=>0
            ]);

      }

      return $result;

  }


  public function querylogsToCloud(Request $request){

    $logrequest =   $request->get('logs');

    $logrequest = str_replace('ABCDEF1234562020','#',$request->get('logs'));

    $log = json_decode($logrequest );

    foreach($log as $item){
      

      $query = $item->query;

      if(stripos ($query, 'TRUNCATE') !== false){


      } else{
          
        DB::update($item->query, $item->bindings);

      }

    }

    return "ok";

  }

  public function querylogsToLocal(){

    $updatelogs = DB::table('updatelogs')->where('status',0)->get();

    $updatelogs = json_encode($updatelogs);

    $updatelogs =   $logrequest = str_replace('#','ABCDEF1234562020', $updatelogs);

    return  $updatelogs;

  }

  public function updatelogid(Request $request){

    $updatelogs = DB::table('updatelogs')
                    ->where('id',$request->get('logid'))
                    ->update(['status'=>1]);

  }

  public function getTableFields($tablename){

    return json_decode(collect(DB::getSchemaBuilder()->getColumnListing($tablename)));

  }

}