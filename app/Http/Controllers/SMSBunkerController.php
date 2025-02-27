<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SMSBunkerController extends Controller
{

  public function apismsgetsmsbunker(Request $request)
  {
    $smsbunker = db::table('smsbunker')
      ->where('smsstatus', 0)
      ->get();

    return $smsbunker;
  }

  public function apismsgetok($status, $id1, $id2)
  {
    $smsbunker = db::table('smsbunker')
      ->whereBetween('id', [$id1, $id2])
      ->update([
        'smsstatus' => 1
      ]);

      // $smsbunker = db::table('smsbunker')
      //   ->whereBetween('id', [$id1, $id2])
      //   ->get();
      // return $smsbunker;
      
  }

  public function apismsblast()
  {
    $smsblaster = db::table('smsbunkertextblast')
      ->where('smsstatus', 0)
      ->take(6)
      ->get();

    return $smsblaster;
  }

  public function apismsblastok($status, $id1, $id2)
  {
    $smsbunker = db::table('smsbunkertextblast')
      ->whereBetween('id', [$id1, $id2])
      ->update([
        'smsstatus' => 1
      ]);    
  }
  
  public function apipushtotapbunker(Request $request)
  	{
	  	$message = $request->get('message');
	 	$receiver = $request->get('receiver');
	 	$school = $request->get('school');
	  
	  	$tapbunker = db::table('tapbunker')
			->where('message', $message)
			->first();
		
		if(!$tapbunker)
		{
			db::table('tapbunker')
				->insert([
					'message' => $message,
					'receiver' => '+' . $receiver,
					'smsstatus' => 0,
					'xml' => $school
				]);    
		}
	  
		return 'done';
  	} 	

  	public function apismsgettapbunker(Request $request)
  	{
    	$smsbunker = db::table('tapbunker')
      		->where('smsstatus', 0)
      		->get();

    	return $smsbunker;
  	}
  
  	public function apismsgetoktapbunker($status, $id1, $id2)
  	{
    	$smsbunker = db::table('tapbunker')
      		->whereBetween('id', [$id1, $id2])
      		->update([
        		'smsstatus' => 1
      		]);

      // $smsbunker = db::table('smsbunker')
      //   ->whereBetween('id', [$id1, $id2])
      //   ->get();
      // return $smsbunker;
      
  	}
	


  	public function studentfetch(Request $request)
  	{
        $studinfo = db::table('studinfo')
            ->select(db::raw('studinfo.id, sid, lrn, lastname, firstname, middlename, suffix, levelname, sectionname, picurl, rfid, gender, mcontactno, fcontactno, gcontactno, ismothernum, isfathernum, isguardannum'))
            ->join('gradelevel', 'studinfo.levelid', 'gradelevel.id')            
            ->get();
    
        return $studinfo;
  	}
  
  	public function employeesfetch(Request $request)
  	{
      	$teachers = db::table('teacher')
        	->get();
       
       	return $teachers;
  	}

  	public function api_pushtap(Request $request)
  	{
  		$tapid = $request->get('id');
        $tdate = $request->get('tdate');
        $ttime = $request->get('ttime');
        $tapstate = $request->get('tapstate');
        $studid = $request->get('studid');
        $createddatetime = $request->get('createddatetime');
        $deleted = 0;
        $utype = $request->get('utype');
        $mode = $request->get('mode');

        db::table('taphistory')
        	->insert([
        		'tdate' => $tdate,
        		'ttime' => $ttime,
        		'tapstate' => $tapstate,
        		'studid' => $studid,
        		'createddatetime' => $createddatetime,
        		'deleted' => 0,
        		'utype' => $utype,
        		'mode' => $mode
        	]);

        return 1;
  	}
















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

  public function synccheckreturn($tablename)
  {
    $return = db::table($tablename)
      ->max('id');

    return $return;
  }
}