<?php

namespace App\Http\Controllers\SchoolMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class SchoolMonitoring extends Controller
{
    public function textblast(){

        return \App\Models\Monitoring\MonitoringData::textblast();

    }

    public function synchonization(){

        return \App\Models\Monitoring\MonitoringData::synchonization();

    }


    public function tables(){
        return \App\Models\Monitoring\MonitoringData::tables();
    }
    
    public function table_count(Request $request){
        $tablename = $request->get('tablename');
        return \App\Models\Monitoring\MonitoringData::table_count($tablename);
    }

    
    public function table_data(Request $request){
        $tablename = $request->get('tablename');
        $tableindex = $request->get('tableindex');
        return \App\Models\Monitoring\MonitoringData::table_data($tablename,$tableindex);
    }

    public function table_data_update(Request $request){
        $tablename = $request->get('tablename');
        $date = $request->get('date');
        return \App\Models\Monitoring\MonitoringData::table_data_update($tablename,$date);
    }

    public function table_data_deleted(Request $request){
        $tablename = $request->get('tablename');
        $date = $request->get('date');
        return \App\Models\Monitoring\MonitoringData::table_data_deleted($tablename,$date);
    }

    public function last_date_sync(Request $request){
        return \App\Models\Monitoring\MonitoringData::last_date_sync();
    }

    public function get_pic_url(Request $request){
        return \App\Models\Monitoring\MonitoringData::get_pic_url();
    }

    public function updatelogs(Request $request){
        return \App\Models\Monitoring\MonitoringData::updatelogs();
    }

    
    
}
