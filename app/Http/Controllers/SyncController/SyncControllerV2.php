<?php

namespace App\Http\Controllers\SyncController;

use Illuminate\Http\Request;

class SyncControllerV2 extends \App\Http\Controllers\Controller
{
    public function synccreate(Request $request){
      $tablename = $request->get('tablename');
      $data = $request->get('data');
      return \App\Models\Synchronization\SychronizationProcess::insert_new_data($tablename,$data);
    }

    public function insertsynclogs(Request $request){
      $date = $request->get('date');
      return \App\Models\Synchronization\SychronizationProcess::insert_synclogs($date);
    }

    public function syncupdate(Request $request){
      $tablename = $request->get('tablename');
      $data = $request->get('data');
      return \App\Models\Synchronization\SychronizationProcess::process_update($tablename,$data);
    }

    public function syncdelete(Request $request){
      $tablename = $request->get('tablename');
      $data = $request->get('data');
      return \App\Models\Synchronization\SychronizationProcess::process_delete($tablename,$data);
    }
    public function process_updatelogs(Request $request){
      $query = $request->get('query');
      $binding = $request->get('binding');
      return \App\Models\Synchronization\SychronizationProcess::process_updatelogs($query, $binding);
    }

    public function process_updatelogs_status(Request $request){
      $id = $request->get('id');
      return \App\Models\Synchronization\SychronizationProcess::process_updatelogs_status($id);
    }
    
    
}