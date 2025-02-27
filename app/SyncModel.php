<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Response;

class SyncModel extends Model
{
  public static function syncsetup()
  {
    $syncsetup = db::table('syncsetup')
        ->first();

    return $syncsetup;
  }

  public static function sync($tablename)
  {
  	$rowcount = db::table($tablename)
      ->max('id');

    return $rowcount;

  }

  public static function execsync($tablename)
  {
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'http://es.ck/syncdb/' . $tablename);
    

    $result = $response->getBody()->getContents();

    $curRow = db::table($tablename)
        ->max('id');



    if($result > $curRow)
    {

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