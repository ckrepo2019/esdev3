<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use File;
use DB;

class BackUpController extends \App\Http\Controllers\Controller
{
    
      public function backUP(){

            $stringToAppend = 'testing';

            if (! File::exists(public_path().'dbbackup/')) {
    
                $path = public_path('dbbackup');
    
                if(!File::isDirectory($path)){
    
                    File::makeDirectory($path, 0777, true, true);
                }
            }
    
            $newLine = "\r\n";
            $targetTables = [];
    
    
            $queryTables = DB::select(DB::raw('SHOW TABLES'));
    
            $dbname = str_replace("Tables_in_","",collect($queryTables[0])->keys()[0]);
    
            $tableKey = collect($queryTables[0])->keys()[0];
    
            foreach($queryTables as $key=>$table){
               
                $targetTables[] = $table->$tableKey;
    
            }
    
            $content = "";

            $content .= "USE ".$dbname.';'.$newLine.$newLine;
    
            foreach($targetTables as $table){
    
                try{
                    $tableData = DB::select(DB::raw('SELECT * FROM '.$table));
                }
                catch (\Exception $e) {
                    
                }
    
                
    
                $content .= "DROP TABLE IF EXISTS `".$table."`".';'.$newLine.$newLine;
    
                $res = DB::select(DB::raw('SHOW CREATE TABLE '.$table));
             
                $content .= $res[0]->{'Create Table'}.';'.$newLine.$newLine;
    
               
                if(count($tableData)>0){
    
                    $content .=" INSERT INTO "."`".$table."` (";
    
                    $fields = array_keys(collect($tableData[0])->toArray());
    
                    foreach($fields as $field){
    
                        $content .= "`".$field."`,";
    
                    }
    
                    $content = substr($content,0,-1);
    
                    $content .= ") values ";
    
                    foreach($tableData as $item){
    
                        $content.="(";
                        
                        foreach($item as $key=>$data){
    
                            if($data == ''){
                            
                                $content .= "NULL";
    
                            }
                            else{
    
                                if(gettype($data) == 'integer'){
                                    $content .= $data;
                                }
                                else{
                                    $content .= " '".$data."'";
                                }
                               
    
                            }
                            
                            $content .= ",";
    
                        }
    
                        $content = substr($content,0,-1);
                       
                        $content.='),';
                        
                    }
                    
    
                    $content = substr($content,0,-1);
    
                    $content.=';'.$newLine.$newLine;
                   
                }
    
                
    
    
            }
    
            date_default_timezone_set('Asia/Manila');
            $date = date('mdY Hi');


            file_put_contents('dbbackup/'.$dbname.' '.$date.'.sql', $content, FILE_APPEND);

      }
      



      
}
